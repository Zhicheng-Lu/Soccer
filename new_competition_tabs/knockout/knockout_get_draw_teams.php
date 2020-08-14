<?php
include("../../includes/connection.php");
include("../../includes/group_rank.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];
$title = $_POST["title"];
$type1 = $_POST["type1"];
$type2 = $_POST["type2"];
if ($type2 == "") $round = $type1;
else $round = $type1.'_'.$type2;
$exp_num_teams = $_POST["exp_num_teams"];
$auto_draw = $_POST["auto_draw"];
include("../includes/inter_group_rank.php");

if ($tournament == "champions_league") {
	if ($round == "1_16") {
		include("knockout_first_round_get_draw_teams.php");
	}
	else {
		include("knockout_other_round_get_draw_teams.php");
	}
}
if ($tournament == "union_associations") {
	if ($round == "1_32") {
		include("knockout_first_round_get_draw_teams.php");
	}
	else {
		include("knockout_other_round_get_draw_teams.php");
	}
} 
if ($tournament == "winners_cup") {
	if ($round == "122") {
		include("knockout_first_round_get_draw_teams.php");
	}
	else {
		include("knockout_other_round_get_draw_teams.php");
	}
}
?>