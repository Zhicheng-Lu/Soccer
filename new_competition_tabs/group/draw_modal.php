<?php
include("../../includes/connection.php");
include("../../includes/group_rank.php");
include("../includes/bipartite.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];
$auto_draw = $_POST["auto_draw"];
?>

<div class="modal-header">
	<h3>小组赛抽签</h3>
    <span class="close" onclick="close_group_draw_modal()">&times;</span>
</div>
<div class="modal-body">
	<table style="width: 100%;">
		<tr>
			<th style="width: 5%; min-width: 100px;">档次</th>
			<th style="width: 95%;">球队</th>
		</tr>
		<?php
		// determine the number of teams each
		if ($tournament == "champions_league") {
			$number_of_clubs = array(13, 13, 13, 13);
			$number_of_nations = array(8, 8, 8, 8);
			$number_of_groups = 21;
		}
		else if ($tournament == "union_associations") {
			$number_of_clubs = array(20, 20, 20, 20);
			$number_of_nations = array(7, 7, 7, 7);
			$number_of_groups = 27;
		}
		else {
			$number_of_clubs = array(41, 41, 42, 42);
			$number_of_nations = array(7, 7, 6, 6);
			$number_of_groups = 48;
		}

		$teams = array(array(), array(), array(), array());
		$groups = array();
		// get all teams in groups
		if ($number_of_groups <= 26) {
			for ($i=0; $i < $number_of_groups; $i++) { 
				$group = chr($i+65);
				array_push($groups, array("group_name"=>$group));
				$groups[$i]["teams"] = array();
			}
		}
		else {
			for ($i=0; $i < 26; $i++) { 
				$group = chr($i+65);
				array_push($groups, array("group_name"=>$group));
				$groups[$i]["teams"] = array();
			}
			for ($i=26; $i < $number_of_groups; $i++) {
				if ($i - 25 < 10) $group = 'Z0'.($i-25);
				else $group = 'Z'.($i-25);
				array_push($groups, array("group_name"=>$group));
				$groups[$i]["teams"] = array();
			}
		}
		$number_of_groups_with_two_nation_teams = 0;
		for ($i=0; $i < $number_of_groups; $i++) { 
		 	$group = $groups[$i];
			$group_name = $group["group_name"];
			$sql = 'SELECT DISTINCT(TOU.team1), T.team_nationality, C.country_continent FROM '.$tournament.' AS TOU LEFT JOIN teams AS T ON TOU.team1=T.team_name LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE TOU.competition='.$competition.' AND TOU.group_index="'.$group_name.'" AND TOU.team1 IS NOT NULL';
			$result = $conn->query($sql);
			$number_of_nation_teams = 0;
			while ($row = $result->fetch_assoc()) {
				if ($row["team1"] == $row["team_nationality"]) $number_of_nation_teams += 1;
				array_push($groups[$i]["teams"], array("team_name"=>$row["team1"], "nationality"=>$row["team_nationality"], "continent"=>$row["country_continent"]));
			}
			if ($number_of_nation_teams == 2) $number_of_groups_with_two_nation_teams = 1;
		}

		// determine current group
		$sql = 'SELECT COUNT(DISTINCT(team1)) AS num_allocated_teams FROM '.$tournament.' WHERE competition='.$competition.' AND group_index<>""';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$num_allocated_teams = $row["num_allocated_teams"];
		}
		$pot = floor($num_allocated_teams / $number_of_groups);
		$current_group = $num_allocated_teams % $number_of_groups;

		// get all teams
		for ($i = 0; $i < 4; $i++) {
			$offset = 0;
			for ($j = 0; $j < $i; $j++) {
				$offset += $number_of_clubs[$j];
			}
			$sql = 'SELECT T.team_name, T.team_chinese_name, T.team_nationality, C.country_continent FROM participants AS P LEFT JOIN teams AS T ON P.team_name=T.team_name LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE tournament="'.$tournament.'" AND competition='.$competition.' AND type1="club" AND type2="finals" ORDER BY points DESC LIMIT '.$offset.', '.$number_of_clubs[$i];
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				array_push($teams[$i], array("team_name"=>$row["team_name"], "team_chinese_name"=>$row["team_chinese_name"], "nationality"=>$row["team_nationality"], "continent"=>$row["country_continent"], "availability"=>"unavailable"));
			}

			$offset = 0;
			for ($j = 0; $j < $i; $j++) {
				$offset += $number_of_nations[$j];
			}
			$sql = 'SELECT T.team_name, T.team_chinese_name, T.team_nationality, C.country_continent FROM participants AS P LEFT JOIN teams AS T ON P.team_name=T.team_name LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE tournament="'.$tournament.'" AND competition='.$competition.' AND type1="nation" AND type2="finals" ORDER BY points DESC LIMIT '.$offset.', '.$number_of_nations[$i];
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				array_push($teams[$i], array("team_name"=>$row["team_name"], "team_chinese_name"=>$row["team_chinese_name"], "nationality"=>$row["team_nationality"], "continent"=>$row["country_continent"], "availability"=>"unavailable"));
			}
		}

		// test feasibility
		for ($i=0; $i < $number_of_groups; $i++) {
			if ($pot == 4) break; 
		 	$team = $teams[$pot][$i];
		 	$teams[$pot][$i]["availability"] = "available";
			$sql = 'SELECT DISTINCT(team1) FROM '.$tournament.' WHERE competition='.$competition.' AND group_index<>""';
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				if ($row["team1"] == $team["team_name"]) {
					$teams[$pot][$i]["availability"]  = "unavailable";
				}
			}
		}
		if ($pot < 4) $teams[$pot] = bipartite($groups, $current_group, $teams[$pot], $tournament, $number_of_groups_with_two_nation_teams);

		for ($i = 0; $i < 4; $i++) {
			echo '
		<tr>
			<td style="text-align: center;">第 '.($i + 1).' 档次</td>
			<td>
				<div class="row" style="padding-left: 15px;">';

			foreach ($teams[$i] as $team) {
				if ($team["team_name"] == $team["nationality"]) continue;
				$style = '';
				if ($team["availability"] == "unavailable") $style = ' opacity: 0.1;';
				elseif ($team["availability"] == "infeasible") $style = ' opacity: 0.4; text-decoration: line-through;';
				echo '
					<div class="col-xxl-15 col-xl-20 col-lg-24 col-md-30 col-sm-40 no-padding" style="margin-top: 4px; margin-bottom: 4px;'.$style.'">
						&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small"> '.$team["team_chinese_name"].'
					</div>';
			}

			echo '
				</div>
				<div class="row" style="padding-left: 15px;">';

			foreach ($teams[$i] as $team) {
				if ($team["team_name"] != $team["nationality"]) continue;
				$style = '';
				if ($team["availability"] == "unavailable") $style = ' opacity: 0.1;';
				elseif ($team["availability"] == "infeasible") $style = ' opacity: 0.4; text-decoration: line-through;';
				echo '
					<div class="col-xxl-15 col-xl-20 col-lg-24 col-md-30 col-sm-40 no-padding" style="margin-top: 4px; margin-bottom: 4px;'.$style.'">
						&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small"> '.$team["team_chinese_name"].'
					</div>';
			}

			echo '
				</div>
			</td>
		</tr>';
		}
		?>
	</table>

	<div class="row" style="margin-top: 40px;">
		<div class="col-xxl-50 col-xl-30 col-lg-60 col-md-50 col-sm-40">
			<div class="row">
				<?php
				$group_name = $groups[$current_group]["group_name"];
				$available_teams = array();
				if ($pot < 4) {
					foreach ($teams[$pot] as $team) {
						if ($team["availability"] == "available") {
							array_push($available_teams, $team["team_name"]);
						}
					}
				}
				shuffle($available_teams);
				for ($i=0; $i < sizeof($available_teams); $i++) {
					if (4*$number_of_groups != sizeof($teams[0]) + sizeof($teams[1]) + sizeof($teams[2]) + sizeof($teams[3])) break;
					$team_name = str_replace("'", "\'", $available_teams[$i]);
					echo '
				<div class="col-xxl-10 col-xl-12 col-lg-20 col-md-24 col-sm-30 col-20">
					<img src="images/ball.png" style="width: 50px; height: 50px; cursor: pointer;" onclick="draw_new_team(\''.$tournament.'\', '.$competition.', \''.$group_name.'\', \''.$team_name.'\', '.$auto_draw.')" id="draw_ball_'.$i.'">
				</div>';
				}
				?>
			</div>
		</div>
		<div class="col-xxl-70 col-xl-90 col-lg-60 col-md-70 col-sm-80">
			<?php
			foreach ($groups as $group) {
				echo '
			<div class="row">
				<div class="col-10" style="text-align: right;">'.$group["group_name"].'</div>
				<div class="col-110">
					<div class="row">';

				$sql = 'SELECT DISTINCT(TOU.team1) FROM '.$tournament.' AS TOU LEFT JOIN participants AS P ON TOU.team1=P.team_name WHERE TOU.competition='.$competition.' AND TOU.group_index="'.$group["group_name"].'" AND TOU.team1 IS NOT NULL AND P.competition='.$competition;
				$result = $conn->query($sql);
				$teams_in_group = array();
				while ($row = $result->fetch_assoc()) {
					array_push($teams_in_group, $row["team1"]);
					
				}

				foreach ($teams as $pot_teams) {
					foreach ($pot_teams as $team) {
						foreach ($teams_in_group as $team_in_group) {
							if ($team["team_name"] == $team_in_group) {
								echo '
						<div class="col-xl-30 col-60">
							<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small"> '.$team["team_chinese_name"].'
						</div>';
							}
						}
					}
				}

				echo '
					</div>
				</div>
			</div>';
			}
			?>
		</div>
	</div>
</div>
<div class="modal-footer justify-content-center">
	<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="close_group_draw_modal()">确认</button>
	<?php
	if (4*$number_of_groups == sizeof($teams[0]) + sizeof($teams[1]) + sizeof($teams[2]) + sizeof($teams[3])) {
		echo '
	<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_group_draw(\''.$tournament.'\', '.$competition.', 1)">模拟剩余全部</button>';
	}
	?>
</div>

<?php
function check_valid($position_teams, $temp_team, $tournament, $number_of_groups_with_two_nation_teams) {
	foreach ($position_teams["teams"] as $team_in_group) {
		// cannot from same nation
		if ($team_in_group["nationality"] == $temp_team["nationality"]) {
			return False;
		}
		// cannot from same continent (except Europe)
		if ($team_in_group["continent"] == $temp_team["continent"] && $team_in_group["continent"] != "Europe") {
			return False;
		}
		// cannot have two national teams in group for Winners Cup
		if ($team_in_group["team_name"] == $team_in_group["nationality"]) $team_in_group_type = "nation";
		else $team_in_group_type = "club";
		if ($temp_team["team_name"] == $temp_team["nationality"]) $temp_team_type = "nation";
		else $temp_team_type = "club";
		if (get_type($team_in_group) == get_type($temp_team) && get_type($temp_team) == "nation" && $tournament == "union_associations" && $number_of_groups_with_two_nation_teams == 1) {
			return False;
		}
		if (get_type($team_in_group) == get_type($temp_team) && get_type($temp_team) == "nation" && $tournament == "winners_cup") {
			return False;
		}
	}
	if (sizeof($position_teams["teams"]) == 3) {
		if ($tournament == "champions_league" || $tournament == "union_associations") {
			if (get_type($position_teams["teams"][0]) == "club" && get_type($position_teams["teams"][1]) == "club" && get_type($position_teams["teams"][2]) == "club" && get_type($temp_team) == "club") {
				return False;
			}
			if (get_type($position_teams["teams"][0]) == "nation" && get_type($position_teams["teams"][1]) == "nation" && get_type($position_teams["teams"][2]) == "nation" && get_type($temp_team) == "nation") {
				return False;
			}
		}
		if ($position_teams["teams"][0]["continent"] == "Europe" && $position_teams["teams"][1]["continent"] == "Europe" && $position_teams["teams"][2]["continent"] == "Europe" && $temp_team["continent"] == "Europe") {
			return False;
		}
		if ($position_teams["teams"][0]["continent"] != "Europe" && $position_teams["teams"][1]["continent"] != "Europe" && $position_teams["teams"][2]["continent"] != "Europe" && $temp_team["continent"] != "Europe") {
			return False;
		}
	}
	return True;
}
?>