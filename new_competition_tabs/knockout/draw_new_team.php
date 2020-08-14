<?php
include("../../includes/connection.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];
$round = $_POST["round"];
$team_name = $_POST["team_name"];

$sql = 'SELECT team_chinese_name FROM teams WHERE team_name="'.$team_name.'"';
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
	$team_chinese_name = $row["team_chinese_name"];
}

$sql = 'SELECT MAX(game) as max_game FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'"';
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
	if ($row["max_game"] == "") $max_game = -1;
	else $max_game = $row["max_game"];
}

if ($max_game == -1) {
	$sql = 'INSERT INTO '.$tournament.'(competition, round, game, team1) VALUES('.$competition.', "'.$round.'", 0, "'.$team_name.'")';
	$conn->query($sql);
	$sql = 'INSERT INTO '.$tournament.'(competition, round, game, team2) VALUES('.$competition.', "'.$round.'", 1, "'.$team_name.'")';
	$conn->query($sql);
}
else {
	$sql = 'SELECT team1 FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" and game='.$max_game;
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$check_null = $row["team1"];
	}
	if ($check_null == "") {
		$sql = 'UPDATE '.$tournament.' SET team2="'.$team_name.'" WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" and game='.($max_game-1);
		$conn->query($sql);
		$sql = 'UPDATE '.$tournament.' SET team1="'.$team_name.'" WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" and game='.($max_game);
		$conn->query($sql);
	}
	else {
		$sql = 'INSERT INTO '.$tournament.'(competition, round, game, team1) VALUES('.$competition.', "'.$round.'", '.($max_game+1).', "'.$team_name.'")';
		$conn->query($sql);
		$sql = 'INSERT INTO '.$tournament.'(competition, round, game, team2) VALUES('.$competition.', "'.$round.'", '.($max_game+2).', "'.$team_name.'")';
		$conn->query($sql);
	}
}

echo '
<p style="margin-top: 200px;"><img src="images/teams/'.$team_name.'.png" style="height: 50px; width: 50px;">&nbsp;'.$team_chinese_name.'</p>';
?>