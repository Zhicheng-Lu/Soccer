<?php
include("../../includes/connection.php");
include("../../includes/group_rank.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];
$round = $_POST["round"];
$game = $_POST["game"];
$win_type2 = $_POST["win_type2"];
$win_start_index = $_POST["win_start_index"];
$win_index = $_POST["win_index"];
$loss_tournament = $_POST["loss_tournament"];
$loss_type2 = $_POST["loss_type2"];
$loss_start_index = $_POST["loss_start_index"];
$loss_index = $_POST["loss_index"];

if ($round == "final") {
	if (is_valid_match($conn, $tournament, $competition, "final", 0) && !is_match_played($conn, $tournament, $competition, "final", 0)) {
		$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND round="final" AND game=0';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$team1 = $row["team1"];
			$team2 = $row["team2"];
		}

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

		$possibility1 = floor(($a1 - $d2 + 50) * ($a1 - $d2 + 50) / 20 * pow(1.12, $m1 / 10 - $m2 / 10));
		$possibility2 = floor(($a2 - $d1 + 50) * ($a2 - $d1 + 50) / 20 * pow(1.12, $m2 / 10 - $m1 / 10));

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

		$sql = 'UPDATE '.$tournament.' SET score1='.$score1.',score2='.$score2.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.$game;
		$conn->query($sql);

		if ($score1 > $score2) $champion = $team1;
		elseif ($score1 < $score2) $champion = $team2;
		else {
			$extra_score1 = 0;
			$extra_score2 = 0;

			for ($i=0; $i < 4; $i++) { 
				$rand = rand(1, 1500);
				if ($rand <= $possibility1) $extra_score1 += 1;
			}
			for ($i=0; $i < 4; $i++) { 
				$rand = rand(1, 1500);
				if ($rand <= $possibility2) $extra_score2 += 1;
			}

			$sql = 'UPDATE '.$tournament.' SET extra_score1='.$extra_score1.',extra_score2='.$extra_score2.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.$game;
			$conn->query($sql);

			if ($extra_score1 > $extra_score2) $champion = $team1;
			elseif ($extra_score1 < $extra_score2) $champion = $team2;
			else {
				$penalty_scores = play_penalties(75, 75);
				$penalty_score1 = $penalty_scores[0];
				$penalty_score2 = $penalty_scores[1];
				$sql = 'UPDATE '.$tournament.' SET penalty_score1='.$penalty_score1.',penalty_score2='.$penalty_score2.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.$game;
				$conn->query($sql);
				if ($penalty_score1 > $penalty_score2) $champion = $team1;
				else $champion = $team2;
			}
		}

		$sql = 'INSERT INTO '.$tournament.'(competition, round, game, team1) VALUES('.$competition.', "champion", 0, "'.$champion.'")';
		$conn->query($sql);
	}
	include("../final.php");
}











elseif (($game%2 == 0 && !is_match_played($conn, $tournament, $competition, $round, $game)) || ($game%2 == 1 && is_match_played($conn, $tournament, $competition, $round, $game-1) && !is_match_played($conn, $tournament, $competition, $round, $game))) {
	include("games.php");
	if (is_valid_match($conn, $tournament, $competition, $round, $game)) {
		// get two teams
		$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.$game;
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$team1 = $row["team1"];
			$team2 = $row["team2"];
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

		$sql = 'UPDATE '.$tournament.' SET score1='.$score1.',score2='.$score2.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.$game;
		$conn->query($sql);

		// extra time
		if ($game % 2 == 1) {
			$score2_1 = $score1;
			$score2_2 = $score2;
			$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.($game-1);
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				$score1_1 = $row["score1"];
				$score1_2 = $row["score2"];
			}

			if (($score2_1 + $score1_2 > $score2_2 + $score1_1) || ($score2_1 + $score1_2 == $score2_2 + $score1_1 && $score1_2 > $score2_2)) {
				$win_team = $team1;
				$loss_team = $team2;
			}
			elseif (($score2_1 + $score1_2 < $score2_2 + $score1_1) || ($score2_1 + $score1_2 == $score2_2 + $score1_1 && $score1_2 < $score2_2)) {
				$win_team = $team2;
				$loss_team = $team1;
			}
			else {
				$extra_score1 = 0;
				$extra_score2 = 0;

				for ($i=0; $i < 4; $i++) { 
					$rand = rand(1, 1500);
					if ($rand <= $possibility1) $extra_score1 += 1;
				}
				for ($i=0; $i < 4; $i++) { 
					$rand = rand(1, 1500);
					if ($rand <= $possibility2) $extra_score2 += 1;
				}
				$sql = 'UPDATE '.$tournament.' SET extra_score1='.$extra_score1.',extra_score2='.$extra_score2.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.$game;
				$conn->query($sql);

				if ($extra_score1 > $extra_score2) {
					$win_team = $team1;
					$loss_team = $team2;
				}
				elseif ($extra_score1 < $extra_score2 || ($extra_score1 == $extra_score2 && $extra_score1 > 0)) {
					$win_team = $team2;
					$loss_team = $team1;
				}
				else {
					$penalty_scores = play_penalties(77, 72);
					$penalty_score1 = $penalty_scores[0];
					$penalty_score2 = $penalty_scores[1];
					if ($penalty_score1 > $penalty_score2) {
						$win_team = $team1;
						$loss_team = $team2;
					}
					else {
						$win_team = $team2;
						$loss_team = $team1;
					}
					$sql = 'UPDATE '.$tournament.' SET penalty_score1='.$penalty_score1.',penalty_score2='.$penalty_score2.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.$game;
					$conn->query($sql);
				}
			}

			// check type
			if ($win_type2 != "") {
				$sql = 'SELECT team_nationality FROM teams WHERE team_name="'.$team1.'"';
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					if ($row["team_nationality"] == $team1) $type1 = "nation";
					else $type1 = "club";
				}

				// result of win and loss
				$sql = 'SELECT * FROM participants WHERE tournament="'.$tournament.'" AND competition='.$competition.' AND team_name="'.$win_team.'"';
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$attack = $row["attack"];
					$middlefield = $row["middlefield"];
					$defence = $row["defence"];
					$home_plus = $row["home_plus"];
					$points = $row["points"];
				}
				$sql = 'INSERT INTO participants VALUES("'.$tournament.'", '.$competition.', '.$win_index.', "'.$type1.'", "'.$win_type2.'", "'.$win_team.'", '.$attack.', '.$middlefield.', '.$defence.', '.$home_plus.', '.$points.')';
				$conn->query($sql);

				if ($loss_tournament != '') {
					$sql = 'SELECT * FROM participants WHERE tournament="'.$tournament.'" AND competition='.$competition.' AND team_name="'.$loss_team.'"';
					$result = $conn->query($sql);
					while ($row = $result->fetch_assoc()) {
						$attack = $row["attack"];
						$middlefield = $row["middlefield"];
						$defence = $row["defence"];
						$home_plus = $row["home_plus"];
						$points = $row["points"];
					}
					$sql = 'INSERT INTO participants VALUES("'.$loss_tournament.'", '.$competition.', '.$loss_index.', "'.$type1.'", "'.$loss_type2.'", "'.$loss_team.'", '.$attack.', '.$middlefield.', '.$defence.', '.$home_plus.', '.$points.')';
					$conn->query($sql);
				}
			}
		}
	}

	draw_games($conn, $tournament, $competition, $round, $win_type2, $win_start_index, $loss_tournament, $loss_type2, $loss_start_index);
}





function is_match_played($conn, $tournament, $competition, $round, $game) {
	$sql = 'SELECT score1 FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.$game;
	$result = $conn->query($sql);
	$score1 = "";
	while ($row = $result->fetch_assoc()) {
		$score1 = $row["score1"];
	}

	if ($score1 == "") return False;
	else return True;
}

function is_valid_match($conn, $tournament, $competition, $round, $game) {
	$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" AND game='.$game;
	$result = $conn->query($sql);
	$team1 = "";
	while ($row = $result->fetch_assoc()) {
		$team1 = $row["team1"];
	}

	if ($team1 == "") return False;
	else return True;
}

function play_penalties($possibility1, $possibility2) {
	$possibilities = [$possibility1, $possibility2];
	$penalty_scores = [0, 0];
	$start = rand(0, 1);
	$current = $start;
	$counter = 0;
	while (True) {
		$counter += 1;
		if ($current == 0) $another = 1;
		else $another = 0;

		if (rand(1, 100) <= $possibilities[$current]) {
			$penalty_scores[$current] += 1;
		}

		if ($counter <= 5) {
			if ($current == $start) {
				if ($penalty_scores[$current] - $penalty_scores[$another] > 5 - $counter + 1) break;
				if ($penalty_scores[$another] - $penalty_scores[$current] > 5 - $counter) break;
			}
			else {
				if ($penalty_scores[$current] - $penalty_scores[$another] > 5 - $counter) break;
				if ($penalty_scores[$another] - $penalty_scores[$current] > 5 - $counter) break;
			}
		}
		else {
			if ($current != $start && $penalty_scores[0] != $penalty_scores[1]) break;
		}

		$current = $another;
	}

	return $penalty_scores;
}
?>