	<?php
	$sql = 'SELECT COUNT(*) AS num_semi_final_matches FROM winners_cup WHERE competition='.$competition.' AND group_index="" AND round="6" AND score1 IS NOT NULL';
	$result = $conn->query($sql);
	$finish_semi_final = False;
	while ($row = $result->fetch_assoc()) {
		if ($row["num_semi_final_matches"] == 6) $finish_semi_final = True;
	}
	if ($finish_semi_final) {
		$proceed_teams = array();
		$sql = 'SELECT * FROM winners_cup WHERE competition='.$competition.' AND group_index="" AND round="6" ORDER BY game ASC';
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
		$sql = 'INSERT IGNORE INTO winners_cup(competition, group_index, round, game, team1, team2) VALUES('.$competition.', "", "finals", 0, "'.$proceed_teams[0]["team_name"].'", "'.$proceed_teams[1]["team_name"].'")';
		$conn->query($sql);
		$sql = 'INSERT IGNORE INTO winners_cup(competition, group_index, round, game, team1, team2) VALUES('.$competition.', "", "finals", 1, "'.$proceed_teams[2]["team_name"].'", "'.$proceed_teams[0]["team_name"].'")';
		$conn->query($sql);
		$sql = 'INSERT IGNORE INTO winners_cup(competition, group_index, round, game, team1, team2) VALUES('.$competition.', "", "finals", 2, "'.$proceed_teams[1]["team_name"].'", "'.$proceed_teams[2]["team_name"].'")';
		$conn->query($sql);
		$sql = 'INSERT IGNORE INTO winners_cup(competition, group_index, round, game, team1, team2) VALUES('.$competition.', "", "finals", 3, "'.$proceed_teams[1]["team_name"].'", "'.$proceed_teams[0]["team_name"].'")';
		$conn->query($sql);
		$sql = 'INSERT IGNORE INTO winners_cup(competition, group_index, round, game, team1, team2) VALUES('.$competition.', "", "finals", 4, "'.$proceed_teams[0]["team_name"].'", "'.$proceed_teams[2]["team_name"].'")';
		$conn->query($sql);
		$sql = 'INSERT IGNORE INTO winners_cup(competition, group_index, round, game, team1, team2) VALUES('.$competition.', "", "finals", 5, "'.$proceed_teams[2]["team_name"].'", "'.$proceed_teams[1]["team_name"].'")';
		$conn->query($sql);
	}
	?>

	<div class="section" id="section">

	</div>

	<script type="text/javascript">
		show_details();

		function show_details() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("section").innerHTML = xhttp.responseText;
				}
			};
			xhttp.open("POST", "new_competition_tabs/group/finals.php", true);
			xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhttp.send("tournament=" + <?php echo '"'.$tournament.'"'; ?> + "&competition=" + <?php echo '"'.$competition.'"'; ?>);
		}

		function simu_match(tournament, competition, game) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					show_details();
				}
			};
			xhttp.open("POST", "new_competition_tabs/group/simu_match.php", true);
			xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhttp.send("tournament=" + tournament + "&competition=" + competition + "&group_index=&round=finals&game=" + game);
		}
	</script>