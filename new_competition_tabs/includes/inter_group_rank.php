<?php
function get_inter_group_rank($conn, $tournament, $competition) {
	$first_place = array();
	$second_place = array();
	$third_place = array();
	$fourth_place = array();

	// champions league
	$groups = array();
	$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND group_index<>"" ORDER BY group_index';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$group_index = $row["group_index"];
		if (!isset($groups[$group_index])) {
			$groups[$group_index] = array();
		}
		array_push($groups[$group_index], array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"]));
	}

	// for each group
	foreach ($groups as $group_index => $group) {
		// get the unique team name in the group
		$teams = array();
		foreach ($group as $match) {
			if (!isset($teams[$match["team1"]]) && $match["team1"] != "") {
				$teams[$match["team1"]] = array();
			}
			if (!isset($teams[$match["team2"]]) && $match["team2"] != "") {
				$teams[$match["team2"]] = array();
			}
		}
		$teams = rank($teams, $group);
		$teams[0]["group_index"] = $group_index;
		$teams[0]["tournament"] = $tournament;
		$teams[1]["group_index"] = $group_index;
		$teams[1]["tournament"] = $tournament;
		$teams[2]["group_index"] = $group_index;
		$teams[2]["tournament"] = $tournament;
		$teams[3]["group_index"] = $group_index;
		$teams[3]["tournament"] = $tournament;
		// print_r($teams);
		array_push($first_place, $teams[0]);
		array_push($second_place, $teams[1]);
		array_push($third_place, $teams[2]);
		array_push($fourth_place, $teams[3]);
	}

	// rank all the teams
	$sql = 'SELECT COUNT(DISTINCT(team1)) AS num_teams FROM '.$tournament.' WHERE competition='.$competition.' AND group_index<>""';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		if (($tournament == "champions_league" && $row["num_teams"] == 84) || ($tournament == "union_associations" && $row["num_teams"] == 108) || ($tournament == "winners_cup" && $row["num_teams"] == 192)) {
			usort($first_place, "cmp1");
			usort($second_place, "cmp1");
			usort($third_place, "cmp1");
			usort($fourth_place, "cmp1");
		}
		else {
			$first_place = array();
			$second_place = array();
			$third_place = array();
			$fourth_place = array();
		}
	}

	return array($first_place, $second_place, $third_place, $fourth_place);
}
?>