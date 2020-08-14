<?php
if ($round == "1_16") {
	$previous_round = "1_32";
}
if ($round == "1_8") {
	$previous_round = "1_16";
}
if ($round == "1_4") {
	$previous_round = "1_8";
}
if ($round == "semi_final") {
	$previous_round = "1_4";
}
if ($round == "70") {
	$previous_round = "122";
}
if ($round == "48") {
	$previous_round = "70";
}
if ($round == "24") {
	$previous_round = "48";
}
if ($round == "12") {
	$previous_round = "24";
}
if ($round == "6") {
	$previous_round = "12";
}

$proceed_teams = array();
$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$previous_round.'" ORDER BY game ASC';
$result = $conn->query($sql);
$score1_1 = 0;
$score1_2 = 0;
while ($row = $result->fetch_assoc()) {
	if ($row["game"] % 2 == 0) {
		if ($row["score1"] == "") continue;
		$score1_1 = $row["score1"];
		$score1_2 = $row["score2"];
	}
	else {
		if ($row["score1"] == "") continue;
		$score2_1 = $row["score1"];
		$score2_2 = $row["score2"];

		if ($score2_1 + $score1_2 > $score1_1 + $score2_2) {
			array_push($proceed_teams, array("team_name"=>$row["team1"], "availability"=>"available"));
		}
		elseif ($score2_1 + $score1_2 < $score1_1 + $score2_2) {
			array_push($proceed_teams, array("team_name"=>$row["team2"], "availability"=>"available"));
		}
		else {
			if ($score1_2 > $score2_2) {
				array_push($proceed_teams, array("team_name"=>$row["team1"], "availability"=>"available"));
			}
			elseif ($score1_2 < $score2_2) {
				array_push($proceed_teams, array("team_name"=>$row["team2"], "availability"=>"available"));
			}
			else {
				if ($row["extra_score1"] > $row["extra_score2"]) {
					array_push($proceed_teams, array("team_name"=>$row["team1"], "availability"=>"available"));
				}
				else {
					array_push($proceed_teams, array("team_name"=>$row["team2"], "availability"=>"available"));
				}
			}
		}
	}
}

if ($round == "70") {
	$proceed_teams = array_merge($proceed_teams, array_slice(get_inter_group_rank($conn, "champions_league", $competition)[2], 13, 8), array_slice(get_inter_group_rank($conn, "union_associations", $competition)[2], 0, 1));
}
if ($round == "48") {
	$proceed_teams = array_merge($proceed_teams, array_slice(get_inter_group_rank($conn, "champions_league", $competition)[2], 0, 13));
}
for ($i=0; $i < sizeof($proceed_teams); $i++) {
	$proceed_teams[$i]["availability"] = "available";
}


?>
<div class="modal-header">
	<span class="close" onclick="close_draw_modal()">&times;</span>
</div>
<div class="modal-body">
	<div class="row">
		<?php
		for ($i=0; $i < sizeof($proceed_teams); $i++) { 
		 	$team = $proceed_teams[$i];
			$sql = 'SELECT game FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND team1="'.$team["team_name"].'"';
			$result = $conn->query($sql);
			$style = '';
			while ($row = $result->fetch_assoc()) {
				$style = 'opacity: 0.1; text-decoration: line-through';
				$proceed_teams[$i]["availability"] = "unavailable";
			}
			echo '
		<div class="col-xxl-15 col-xl-20 col-lg-24 col-md-30 col-sm-40 col-60" style="'.$style.'">
			<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small"> '.get_team_chinese_name($conn, $team["team_name"]).'
		</div>';
		}
		?>
	</div>
	<div class="row" style="margin-top: 40px;">
		<div class="col-sm-60 col-120">
			<div class="row">
			<?php
			$counter = 0;
			shuffle($proceed_teams);
			foreach ($proceed_teams as $team) {
				if (sizeof($proceed_teams) != $exp_num_teams) break;
				if ($team["availability"] == "available") {
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
			$sql = 'SELECT team1, team2 FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game%2=0';
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
	if (sizeof($proceed_teams) == $exp_num_teams) {
		echo '
	<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_draw_modal(\''.$tournament.'\', '.$competition.', \''.$title.'\', \''.$type1.'\', \''.$type2.'\', '.$exp_num_teams.', 1)">模拟剩余全部</button>';
	}
	?>
</div>
