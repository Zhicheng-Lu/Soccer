<?php
include("../../includes/connection.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];
$counter = $_POST["counter"];
$type1 = $_POST["type1"];
$type2 = $_POST["type2"];
if ($type2=="qualifications") $type2 = "qualifications_first";
$continent = $_POST["continent"];
$country = $_POST["country"];
$team = $_POST["team"];
$attack = $_POST["attack"];
$middlefield = $_POST["middlefield"];
$defence = $_POST["defence"];
$home_plus = $_POST["home_plus"];
$points = $_POST["points"];

$sql = 'INSERT INTO participants VALUES("'.$tournament.'", '.$competition.', '.$counter.', "'.$type1.'", "'.$type2.'", "'.str_replace("'", "\'", $team).'", '.$attack.', '.$middlefield.', '.$defence.', '.$home_plus.', '.$points.') ON DUPLICATE KEY UPDATE team_name="'.str_replace("'", "\'", $team).'", attack='.$attack.', middlefield='.$middlefield.', defence='.$defence.', home_plus='.$home_plus.', points='.$points;
$conn->query($sql);

$sql = 'SELECT * FROM teams AS T LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE T.team_name="'.str_replace("'", "\'", $team).'"';
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
	$team_chinese_name = $row["team_chinese_name"];
}

echo '
	<div style="cursor: pointer;" onclick="open_modal(\''.$tournament.'\', '.$competition.', '.$counter.', \''.$type1.'\', \''.$type2.'\', \''.$continent.'\', \''.str_replace("'", "\'", $country).'\')" title="进攻='.$attack.'，中场='.$middlefield.'，防守='.$defence.'，主场='.$home_plus.'，积分='.$points.'">
		<img src="images/teams_small/'.$team.'.png" class="badge-small"> '.$team_chinese_name.'
	</div>';
?>