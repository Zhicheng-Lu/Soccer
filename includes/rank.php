<?php
// get all the teams
$teams = array();
$sql = 'SELECT * FROM teams AS T LEFT JOIN countries AS C ON T.team_nationality=C.country_name';
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
	$continent = $row["country_continent"];
	if ($continent == "Antarctica") $continent_chinese = "南极洲";
	if ($continent == "Africa") $continent_chinese = "非洲";
	if ($continent == "Asia") $continent_chinese = "亚洲";
	if ($continent == "Europe") $continent_chinese = "欧洲";
	if ($continent == "North America") $continent_chinese = "北美洲";
	if ($continent == "Oceania") $continent_chinese = "大洋洲";
	if ($continent == "South America") $continent_chinese = "南美洲";

	array_push($teams, array(
		"temp_name"=>$row["team_name"],
		"chinese_name"=>$row["team_chinese_name"],
		"nationality"=>$row["country_name"],
		"nationality_chinese"=>$row["country_chinese_name"],
		"continent"=>$continent,
		"continent_chinese"=>$continent_chinese,
		"champions_league_group"=>0,
		"champions_league_group_win"=>0,
		"champions_league_group_draw"=>0,
		"champions_league_group_lose"=>0,
		"champions_league_1_16"=>0,
		"champions_league_1_16_win"=>0,
		"champions_league_1_16_draw"=>0,
		"champions_league_1_16_lose"=>0,
		"champions_league_1_8"=>0,
		"champions_league_1_8_win"=>0,
		"champions_league_1_8_draw"=>0,
		"champions_league_1_8_lose"=>0,
		"champions_league_1_4"=>0,
		"champions_league_1_4_win"=>0,
		"champions_league_1_4_draw"=>0,
		"champions_league_1_4_lose"=>0,
		"champions_league_semi_final"=>0,
		"champions_league_semi_final_win"=>0,
		"champions_league_semi_final_draw"=>0,
		"champions_league_semi_final_lose"=>0,
		"champions_league_final"=>0,
		"champions_league_final_win"=>0,
		"champions_league_final_draw"=>0,
		"champions_league_final_lose"=>0,
		"union_associations_group"=>0,
		"union_associations_group_win"=>0,
		"union_associations_group_draw"=>0,
		"union_associations_group_lose"=>0,
		"union_associations_1_32"=>0,
		"union_associations_1_32_win"=>0,
		"union_associations_1_32_draw"=>0,
		"union_associations_1_32_lose"=>0,
		"union_associations_1_16"=>0,
		"union_associations_1_16_win"=>0,
		"union_associations_1_16_draw"=>0,
		"union_associations_1_16_lose"=>0,
		"union_associations_1_8"=>0,
		"union_associations_1_8_win"=>0,
		"union_associations_1_8_draw"=>0,
		"union_associations_1_8_lose"=>0,
		"union_associations_1_4"=>0,
		"union_associations_1_4_win"=>0,
		"union_associations_1_4_draw"=>0,
		"union_associations_1_4_lose"=>0,
		"union_associations_semi_final"=>0,
		"union_associations_semi_final_win"=>0,
		"union_associations_semi_final_draw"=>0,
		"union_associations_semi_final_lose"=>0,
		"union_associations_final"=>0,
		"union_associations_final_win"=>0,
		"union_associations_final_draw"=>0,
		"union_associations_final_lose"=>0,
		"winners_cup_group"=>0,
		"winners_cup_group_win"=>0,
		"winners_cup_group_draw"=>0,
		"winners_cup_group_lose"=>0,
		"winners_cup_122"=>0,
		"winners_cup_122_win"=>0,
		"winners_cup_122_draw"=>0,
		"winners_cup_122_lose"=>0,
		"winners_cup_70"=>0,
		"winners_cup_70_win"=>0,
		"winners_cup_70_draw"=>0,
		"winners_cup_70_lose"=>0,
		"winners_cup_48"=>0,
		"winners_cup_48_win"=>0,
		"winners_cup_48_draw"=>0,
		"winners_cup_48_lose"=>0,
		"winners_cup_24"=>0,
		"winners_cup_24_win"=>0,
		"winners_cup_24_draw"=>0,
		"winners_cup_24_lose"=>0,
		"winners_cup_12"=>0,
		"winners_cup_12_win"=>0,
		"winners_cup_12_draw"=>0,
		"winners_cup_12_lose"=>0,
		"winners_cup_6"=>0,
		"winners_cup_6_win"=>0,
		"winners_cup_6_draw"=>0,
		"winners_cup_6_lose"=>0,
		"winners_cup_finals"=>0,
		"winners_cup_finals_win"=>0,
		"winners_cup_finals_draw"=>0,
		"winners_cup_finals_lose"=>0,
		"champions_league_win"=>0,
		"champions_league_draw"=>0,
		"champions_league_lose"=>0,
		"union_associations_win"=>0,
		"union_associations_draw"=>0,
		"union_associations_lose"=>0,
		"winners_cup_win"=>0,
		"winners_cup_draw"=>0,
		"winners_cup_lose"=>0,
		"champions_league_champion"=>0,
		"union_associations_champion"=>0,
		"winners_cup_champion"=>0,
		"champions_league_score"=>0,
		"champions_league_concede"=>0,
		"union_associations_score"=>0,
		"union_associations_concede"=>0,
		"winners_cup_score"=>0,
		"winners_cup_concede"=>0));
}

foreach ($teams as $team_index => $team) {
	$temp_name = $team["temp_name"];
	$tournaments = array("champions_league", "union_associations", "winners_cup");
	foreach ($tournaments as $key => $tournament) {
		$sql = 'SELECT * FROM '.$tournament.' WHERE round<>"club_leveldown_first" AND round<>"club_leveldown_second" AND round<>"club_qualifications_first" AND round<>"club_qualifications_second" AND round<>"nation_qualifications_first" AND (team1="'.$temp_name.'" OR team2="'.$temp_name.'")';
		if (isset($_GET["operand"]) && isset($_GET["competition"]) && $_GET["competition"] != "") {
			if ($_GET["operand"] == "equal_to") {
				$sql = $sql.' AND competition='.$_GET["competition"];
			}
			else {
				$sql = $sql.' AND competition<='.$_GET["competition"];
			}
		}

		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			if ($row["round"] == "champion") {
				$teams[$team_index][$tournament."_champion"]++;
				continue;
			}

			// get match result
			if (($row["score1"] > $row["score2"] && $row["team1"] == $temp_name) || ($row["score1"] < $row["score2"] && $row["team2"] == $temp_name)) $match_result = "win";
			else if ($row["score1"] == $row["score2"]) $match_result = "draw";
			else $match_result = "lose";

			if ($row["group_index"] != "") $round = "group";
			else $round = $row["round"];

			// update number of win/lose and number of round participation
			$teams[$team_index][$tournament."_".$round."_".$match_result]++;
			$teams[$team_index][$tournament."_".$match_result]++;
			$teams[$team_index][$tournament."_".$round]++;

			// update scores and concedes
			if ($row["team1"] == $temp_name) {
				$teams[$team_index][$tournament."_score"] += $row["score1"];
				$teams[$team_index][$tournament."_concede"] += $row["score2"];

				if ($round == "final") continue;

				if (isset($teams[$team_index][$tournament."_home_".$match_result])) {
					$teams[$team_index][$tournament."_home_".$match_result]++;
				}
				else {
					$teams[$team_index][$tournament."_home_".$match_result] = 1;
				}
				if (isset($teams[$team_index][$tournament."_home_score"])) {
					$teams[$team_index][$tournament."_home_score"] += $row["score1"];
				}
				else {
					$teams[$team_index][$tournament."_home_score"] = $row["score1"];
				}
				if (isset($teams[$team_index][$tournament."_home_concede"])) {
					$teams[$team_index][$tournament."_home_concede"] += $row["score2"];
				}
				else {
					$teams[$team_index][$tournament."_home_concede"] = $row["score2"];
				}
			}
			else {
				$teams[$team_index][$tournament."_score"] += $row["score2"];
				$teams[$team_index][$tournament."_concede"] += $row["score1"];

				if ($round == "final") continue;

				if (isset($teams[$team_index][$tournament."_away_".$match_result])) {
					$teams[$team_index][$tournament."_away_".$match_result]++;
				}
				else {
					$teams[$team_index][$tournament."_away_".$match_result] = 1;
				}
				if (isset($teams[$team_index][$tournament."_away_score"])) {
					$teams[$team_index][$tournament."_away_score"] += $row["score2"];
				}
				else {
					$teams[$team_index][$tournament."_away_score"] = $row["score2"];
				}
				if (isset($teams[$team_index][$tournament."_away_concede"])) {
					$teams[$team_index][$tournament."_away_concede"] += $row["score1"];
				}
				else {
					$teams[$team_index][$tournament."_away_concede"] = $row["score1"];
				}
			}
		}
	}

	// champions league total points
	$helpers = array(array("group", 6, 120, 30), array("1_16", 2, 180, 34), array("1_8", 2, 240, 39), array("1_4", 2, 300, 45), array("semi_final", 2, 360, 52), array("final", 2, 420, 60));
	$points = 0;
	foreach ($helpers as $key => $helper) {
		$points += $helper[2] * ($teams[$team_index]["champions_league_".$helper[0]] / $helper[1]) + $helper[3] * $teams[$team_index]["champions_league_".$helper[0]."_draw"] + 3 * $helper[3] * $teams[$team_index]["champions_league_".$helper[0]."_win"];
	}
	$points += 500 * $teams[$team_index]["champions_league_champion"];
	$teams[$team_index]["champions_league_points"] = $points;

	// union associations total points
	$helpers = array(array("group", 6, 30, 15), array("1_32", 2, 50, 17), array("1_16", 2, 70, 19), array("1_8", 2, 90, 21), array("1_4", 2, 110, 23), array("semi_final", 2, 130, 25), array("final", 2, 150, 30));
	$points = 0;
	foreach ($helpers as $key => $helper) {
		$points += $helper[2] * ($teams[$team_index]["union_associations_".$helper[0]] / $helper[1]) + $helper[3] * $teams[$team_index]["union_associations_".$helper[0]."_draw"] + 3 * $helper[3] * $teams[$team_index]["union_associations_".$helper[0]."_win"];
	}
	$points += 200 * $teams[$team_index]["union_associations_champion"];
	$teams[$team_index]["union_associations_points"] = $points;

	// winners cup total points
	$helpers = array(array("group", 6, 10, 10), array("122", 2, 20, 11), array("70", 2, 30, 12), array("48", 2, 40, 13), array("24", 2, 50, 14), array("12", 2, 60, 15), array("6", 2, 70, 16), array("finals", 4, 80, 20));
	$points = 0;
	foreach ($helpers as $key => $helper) {
		$points += $helper[2] * ($teams[$team_index]["winners_cup_".$helper[0]] / $helper[1]) + $helper[3] * $teams[$team_index]["winners_cup_".$helper[0]."_draw"] + 3 * $helper[3] * $teams[$team_index]["winners_cup_".$helper[0]."_win"];
	}
	$points += 150 * $teams[$team_index]["winners_cup_champion"];
	$teams[$team_index]["winners_cup_points"] = $points;

	//total points
	$teams[$team_index]["total_points"] = $teams[$team_index]["champions_league_points"] + $teams[$team_index]["union_associations_points"] + $teams[$team_index]["winners_cup_points"];
}

// compare functions
function cmp2($team1, $team2) {
	return $team2["champions_league_points"] - $team1["champions_league_points"];
}
function cmp3($team1, $team2) {
	return $team2["union_associations_points"] - $team1["union_associations_points"];
}
function cmp4($team1, $team2) {
	return $team2["winners_cup_points"] - $team1["winners_cup_points"];
}
function cmp5($team1, $team2) {
	if ($team1["total_points"] == $team2["total_points"]) {
		if ($team1["champions_league_points"] == $team2["champions_league_points"]) {
			if ($team1["union_associations_points"] == $team2["union_associations_points"]) {
				return $team2["winners_cup_points"] - $team1["winners_cup_points"];
			}
			return $team2["union_associations_points"] - $team1["union_associations_points"];
		}
		return $team2["champions_league_points"] - $team1["champions_league_points"];
	}
	return $team2["total_points"] - $team1["total_points"];
}
?>