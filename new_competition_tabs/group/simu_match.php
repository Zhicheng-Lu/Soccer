<?php
include("../../includes/connection.php");
include("../../includes/group_rank.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];
$group_index = $_POST["group_index"];
$round = $_POST["round"];
$game = $_POST["game"];

if ($game == -1) {
	for ($i=1; $i <= 6; $i++) { 
		for ($j=1; $j <= 2; $j++) { 
			simu_match($conn, $tournament, $competition, $group_index, $i, $j);
		}
	}
}
else {
	simu_match($conn, $tournament, $competition, $group_index, $round, $game);
	if ($round == "finals") {
		$sql = 'SELECT COUNT(*) AS num_finals_played FROM winners_cup WHERE competition='.$competition.' AND round="finals" AND score1 IS NOT NULL';
		$result = $conn->query($sql);
		$finish_finals = False;
		while ($row = $result->fetch_assoc()) {
			if ($row["num_finals_played"] == 6) $finish_finals = True;
		}
		if ($finish_finals) {
			$matches = array();
			$sql = 'SELECT * FROM winners_cup WHERE competition='.$competition.' AND round="finals"';
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				array_push($matches, array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"]));
			}

			// get the unique team name in the group
			$teams = array();
			foreach ($matches as $match) {
				if (!isset($teams[$match["team1"]])) {
					$teams[$match["team1"]] = array();
				}
				if (!isset($teams[$match["team2"]])) {
					$teams[$match["team2"]] = array();
				}
			}
			$teams = rank($teams, $matches);

			$sql = 'INSERT INTO winners_cup(competition, group_index, round, game, team1) VALUES('.$competition.', "", "champion", 0, "'.$teams[0]["team_name"].'")';
			$conn->query($sql);
		}
	}
}

function simu_match($conn, $tournament, $competition, $group_index, $round, $game) {
	// get two teams
	$current_score1 = "";
	$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="'.$group_index.'" AND round="'.$round.'" AND game='.$game;
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$team1 = $row["team1"];
		$team2 = $row["team2"];
		$current_score1 = $row["score1"];
	}

	// calculate possibility of scoring
	$sql = 'SELECT * FROM participants WHERE competition='.$competition.' AND team_name="'.$team1.'"';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$a1 = $row["attack"];
		$m1 = $row["middlefield"];
		$d1 = $row["defence"];
		$h1 = $row["home_plus"];
	}

	$sql = 'SELECT * FROM participants WHERE competition='.$competition.' AND team_name="'.$team2.'"';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$a2 = $row["attack"];
		$m2 = $row["middlefield"];
		$d2 = $row["defence"];
		$h2 = $row["home_plus"];
	}

	$possibility1 = floor((($a1 + $h1 + 3) - ($d2 - $h2) + 40) * (($a1 + $h1 + 3) - ($d2 - $h2) + 40) / 12.5 * pow(1.12, ($m1 + $h1 + 3) / 10 - ($m2 - $h2) / 10));
	$possibility2 = floor((($a2 - $h2) - ($d1 + $h1 + 3) + 40) * (($a2 - $h2) - ($d1 + $h1 + 3) + 40) / 12.5 * pow(1.12, ($m2 - $h2) / 10 - ($m1 + $h1 + 3) / 10));

	// simu
	$score1 = 0;
	$score2 = 0;

	for ($i=0; $i < 15; $i++) { 
		$rand = rand(1, 1500);
		if ($rand <= $possibility1) $score1 += 1;
	}
	for ($i=0; $i < 15; $i++) { 
		$rand = rand(1, 1500);
		if ($rand <= $possibility2) $score2 += 1;
	}

	$sql = 'UPDATE '.$tournament.' SET score1='.$score1.',score2='.$score2.' WHERE competition='.$competition.' AND group_index="'.$group_index.'" AND round="'.$round.'" AND game='.$game;
	if ($current_score1 == "") $conn->query($sql);
}
?>