<div class="modal-header">
	<span class="close" onclick="close_draw_modal()">&times;</span>
</div>
<div class="modal-body">
	<table style="width: 100%;">
		<tr>
			<th style="width: 5%; min-width: 100px;">档次</th>
			<th style="width: 95%;">球队</th>
		</tr>

		<?php
		include("../includes/bipartite.php");
		$sql = 'SELECT COUNT(*) AS num_group_matches FROM '.$tournament.' WHERE competition='.$competition.' AND group_index<>"" AND score1 IS NOT NULL';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$num_group_matches = $row["num_group_matches"];
		}
		$group_finish = False;
		echo $num_group_matches;
		if ($tournament == "champions_league" && $num_group_matches == 252) $group_finish = True;
		if ($tournament == "union_associations" && $num_group_matches == 324) $group_finish = True;
		if ($tournament == "winners_cup" && $num_group_matches == 576) $group_finish = True;

		$pots = array(array(), array());
		if ($tournament == "champions_league") {
			$pots[0] = array_slice(get_inter_group_rank($conn, $tournament, $competition)[0], 0, 16);
			$pots[1] = array_merge(array_slice(get_inter_group_rank($conn, $tournament, $competition)[0], 16, 5), array_slice(get_inter_group_rank($conn, $tournament, $competition)[1], 0, 11));
		}
		if ($tournament == "union_associations") {
			$pots[0] = array_merge(array_slice(get_inter_group_rank($conn, $tournament, $competition)[0], 0, 27), array_slice(get_inter_group_rank($conn, "champions_league", $competition)[1], 11, 5));
			$pots[1] = array_merge(array_slice(get_inter_group_rank($conn, $tournament, $competition)[1], 0, 27), array_slice(get_inter_group_rank($conn, "champions_league", $competition)[1], 16, 5));
		}
		if ($tournament == "winners_cup") {
			$pots[0] = array_merge(array_slice(get_inter_group_rank($conn, $tournament, $competition)[0], 0, 48), array_slice(get_inter_group_rank($conn, "union_associations", $competition)[2], 1, 13));
			$pots[1] = array_merge(array_slice(get_inter_group_rank($conn, $tournament, $competition)[1], 0, 48), array_slice(get_inter_group_rank($conn, "union_associations", $competition)[2], 14, 13));
		}

		// check pot 1 or pot 2
		$sql = 'SELECT COUNT(DISTINCT(team1)) AS num_allocated_teams FROM '.$tournament.' WHERE competition='.$competition.' AND round="'.$round.'" AND team1 IS NOT NULL';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$num_allocated_teams = $row["num_allocated_teams"];
		}

		for ($i = 0; $i < 2; $i++) {
			for ($j=0; $j < sizeof($pots[$i]); $j++) {
				$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND round="'.$round.'" AND team1="'.$pots[$i][$j]["team_name"].'"';
				$result = $conn->query($sql);
				$allocated = False;
				while ($row = $result->fetch_assoc()) {
					$allocated = True;
				}
				if ($allocated) $pots[$i][$j]["availability"] = "unavailable";
				else $pots[$i][$j]["availability"] = "available";
				$sql = 'SELECT T.team_nationality, C.country_continent FROM teams as T LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE T.team_name="'.$pots[$i][$j]["team_name"].'"';
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$pots[$i][$j]["nationality"] = $row["team_nationality"];
					$pots[$i][$j]["continent"] = $row["country_continent"];
				}
			}
		}
		if ($num_allocated_teams % 2 == 1) {
			$sql = 'SELECT team2 FROM '.$tournament.' WHERE competition='.$competition.' AND round="'.$round.'" AND game=(SELECT MAX(game) FROM '.$tournament.' WHERE competition='.$competition.' AND round="'.$round.'")';
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				$opponent_team = $row["team2"];
			}
			$positions = array();
			foreach ($pots[1] as $team) {
				if ($team["team_name"] == $opponent_team) {
					array_push($positions, $team);
				}
			}
			foreach ($pots[1] as $team) {
				if ($team["availability"] == "available" && $team["team_name"] != $opponent_team) {
					array_push($positions, $team);
				}
			}
			$pots[0] = bipartite($positions, 0, $pots[0], $tournament, 0);
		}


		// test feasibility
		for ($i = 0; $i < 2; $i++) {
			for ($j=0; $j < sizeof($pots[$i]); $j++) { 
				if ($i == $num_allocated_teams % 2) {
					$pots[$i][$j]["availability"] = "unavailable";
				}
			}
		}

		for ($i = 0; $i < 2; $i++) {
			echo '
		<tr>
			<td style="text-align: center;">第 '.($i + 1).' 档次</td>
			<td>
				<div class="row" style="padding-left: 15px;">';

			foreach ($pots[$i] as $team) {
				$style = '';
				if ($team["availability"] == "unavailable") $style = ' opacity: 0.1;';
				elseif ($team["availability"] == "infeasible") $style = ' opacity: 0.4; text-decoration: line-through;';
				echo '
					<div class="col-xxl-15 col-xl-20 col-lg-24 col-md-30 col-sm-40 no-padding" style="margin-top: 4px; margin-bottom: 4px;'.$style.'">
						&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small"> '.get_team_chinese_name($conn, $team["team_name"]).'
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
		<div class="col-sm-60 col-120">
			<div class="row">
				<?php
				$counter = 0;
				for ($i = 0; $i < 2; $i++) {
					foreach ($pots[$i] as $team) {
						if (!$group_finish) break;
						if ($team["availability"] == "unavailable" || $team["availability"] == "infeasible") continue;
						$team_name = str_replace("'", "\'", $team["team_name"]);
						echo '
					<div class="col-xxl-10 col-xl-12 col-lg-20 col-md-24 col-sm-30 col-20">
						<img src="images/ball.png" style="width: 50px; height: 50px; cursor: pointer;" onclick="draw_new_team(\''.$tournament.'\', '.$competition.', \''.$title.'\', \''.$type1.'\', \''.$type2.'\', '.$exp_num_teams.', \''.$team_name.'\', '.$auto_draw.')" id="draw_ball_'.$counter.'">
					</div>';
						$counter += 1;
					}
				}

				?>
			</div>
		</div>
		<div class="col-sm-60 col-120">
			<div class="row">
				<?php
				$sql = 'SELECT team1, team2 FROM '.$tournament.' WHERE competition='.$competition.' AND round="'.$round.'" AND game%2=0';
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					echo '
				<div class="col-xxl-60 col-120">
					<div class="row">
						<div class="col-58" style="text-align: right;">
							<img src="images/teams_small/'.$row["team1"].'.png" class="badge-small"> '.get_team_chinese_name($conn, $row["team1"]).'
						</div>
						<div class="col-4 no-padding" style="text-align: center;">-</div>
						<div class="col-58">
							<img src="images/teams_small/'.$row["team2"].'.png" class="badge-small" alt=""> '.get_team_chinese_name($conn, $row["team2"]).'
						</div>
					</div>
				</div>';
}
				?>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer justify-content-center">
	<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="close_draw_modal()">确认</button>
	<?php
	if ($group_finish) {
		echo '
	<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_draw_modal(\''.$tournament.'\', '.$competition.', \''.$title.'\', \''.$type1.'\', \''.$type2.'\', '.$exp_num_teams.', 1)">模拟剩余全部</button>';
	}
	?>
</div>

<?php
function check_valid($position_teams, $temp_team, $tournament, $number_of_groups_with_two_nation_teams) {
	if ($position_teams["nationality"] == $temp_team["nationality"]) return False;
	if ($position_teams["continent"] == $temp_team["continent"] && $position_teams["continent"] != "Europe") return False;
	if ($position_teams["group_index"] == $temp_team["group_index"] && $position_teams["tournament"] == $tournament && $temp_team["tournament"] == $tournament) return False;
	return True;
}
?>