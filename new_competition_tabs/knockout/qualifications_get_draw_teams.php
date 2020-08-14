<?php
include("../../includes/connection.php");
include("../../includes/group_rank.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];
$title = $_POST["title"];
$type1 = $_POST["type1"];
$type2 = $_POST["type2"];
$round = $type1.'_'.$type2;
$exp_num_teams = $_POST["exp_num_teams"];
$auto_draw = $_POST["auto_draw"];

echo '
<div class="modal-header">
	<span class="close" onclick="close_draw_modal()">&times;</span>
</div>
<div class="modal-body">
	<div class="row">';
$sql = 'SELECT T.team_name, T.team_chinese_name FROM participants AS P LEFT JOIN teams AS T ON P.team_name=T.team_name WHERE P.tournament="'.$tournament.'" AND P.competition='.$competition.' AND P.type1="'.$type1.'" AND P.type2="'.$type2.'"';
$result = $conn->query($sql);
$num_teams = 0;
while ($row = $result->fetch_assoc()) {
	$sql1 = 'SELECT game FROM '.$tournament.' WHERE competition='.$competition.' AND round="'.$round.'" AND team1="'.$row["team_name"].'"';
	$result1 = $conn->query($sql1);
	$style = '';
	while ($row1 = $result1->fetch_assoc()) {
		$style = 'opacity: 0.1; text-decoration: line-through';
	}
	echo '
		<div class="col-xxl-15 col-xl-20 col-lg-24 col-md-30 col-sm-40 col-60" style="'.$style.'">
			<img src="images/teams_small/'.$row["team_name"].'.png" class="badge-small"> '.$row["team_chinese_name"].'
		</div>';
	$num_teams += 1;
}
echo '
	</div>
	<div class="row" style="margin-top: 40px;">
		<div class="col-sm-60 col-120">
			<div class="row">';

$sql = 'SELECT P.team_name FROM participants AS P WHERE P.tournament="'.$tournament.'" AND P.competition='.$competition.' AND P.type1="'.$type1.'" AND P.type2="'.$type2.'" AND P.team_name NOT IN (SELECT team1 FROM '.$tournament.' WHERE competition='.$competition.' AND round="'.$round.'" and team1 IS NOT NULL) ORDER BY RAND()';
$result = $conn->query($sql);
$counter = 0;
while ($row = $result->fetch_assoc()) {
	if ($num_teams != $exp_num_teams) break;
	$team_name = str_replace("'", "\'", $row["team_name"]);
	echo '
				<div class="col-xxl-10 col-xl-12 col-lg-20 col-md-24 col-sm-30 col-20">
					<img src="images/ball.png" style="width: 50px; height: 50px; cursor: pointer;" onclick="draw_new_team(\''.$tournament.'\', '.$competition.', \''.$title.'\', \''.$type1.'\', \''.$type2.'\', '.$exp_num_teams.', \''.$team_name.'\', '.$auto_draw.')" id="draw_ball_'.$counter.'">
				</div>';
	$counter += 1;
}

echo '
			</div>
		</div>
		<div class="col-sm-60 col-120">
			<div class="row">';

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

echo '
			</div>
		</div>
	</div>
</div>
<div class="modal-footer justify-content-center">
	<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="close_draw_modal()">确认</button>';

if ($num_teams == $exp_num_teams) {
	echo '
	<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_draw_modal(\''.$tournament.'\', '.$competition.', \''.$title.'\', \''.$type1.'\', \''.$type2.'\', '.$exp_num_teams.', 1)">模拟剩余全部</button>';
}

echo '
</div>';
?>