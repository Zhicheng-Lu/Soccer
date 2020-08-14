<?php
include("../../includes/connection.php");
include("../../includes/group_rank.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];
$group_name = $_POST["group_name"];
$team_name = $_POST["team_name"];
$auto_draw = $_POST["auto_draw"];

$positions = array(array([1,1],[3,2],[5,1],[7,2],[9,2],[11,1]), array([1,2],[4,1],[6,1],[8,2],[9,1],[12,2]), array([2,1],[4,2],[5,2],[7,1],[10,2],[12,1]), array([2,2],[3,1],[6,2],[8,1],[10,1],[11,2]));
$available_positions = array();

for ($i=0; $i < 4; $i++) {
	$round = floor(($positions[$i][0][0]+1)/2);
	$game = ($positions[$i][0][0]+1) % 2 + 1;
	$sql = 'SELECT team'.$positions[$i][0][1].' AS team FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="'.$group_name.'" AND round="'.$round.'" AND game='.$game;
	$team_exist = "";
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$team_exist = $row["team"];
	}
	if ($team_exist == "") array_push($available_positions, $i);
}

shuffle($available_positions);
$team_positions = $positions[$available_positions[0]];

foreach ($team_positions as $team_position) {
	$round = floor(($team_position[0]+1)/2);
	$game = ($team_position[0]+1) % 2 + 1;

	$sql = 'INSERT INTO '.$tournament.'(competition,group_index,round,game,team'.$team_position[1].') VALUES('.$competition.', "'.$group_name.'", "'.$round.'", '.$game.', "'.$team_name.'") ON DUPLICATE KEY UPDATE team'.$team_position[1].'="'.$team_name.'"';
	$conn->query($sql);
}

echo '
<p style="margin-top: 200px;"><img src="images/teams/'.$team_name.'.png" style="height: 50px; width: 50px;">&nbsp;'.get_team_chinese_name($conn, $team_name).'</p>';
?>