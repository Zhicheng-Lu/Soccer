	<br><br><br><br><br>
	<?php
	include("competition_tabs/participants/large_screen.php");
	include("competition_tabs/participants/small_screen.php");

	function print_club_continent($conn, $tournament, $competition, $participants, $continent, $continent_chinese, $counter, $screen_size) {
		$counter1 = $counter;

		$continent_participants = array();
		if ($continent == "Europe" || $continent == "South America") {
			$continent_participants[0] = array("", 4, 4);
		}
		else if ($continent == "Oceania") {
			if ($tournament == "winners_cup") {
				$continent_participants[0] = array("", 1, 1);
			}
			else {
				$continent_participants[0] = array("", 0, 1);
			}
		}
		else {
			if ($tournament == "winners_cup") {
				$continent_participants[0] = array("", 4, 4);
			}
			else {
				$continent_participants[0] = array("", 2, 2);
			}
		}
		$counter1 += $continent_participants[0][1] + $continent_participants[0][2];

		$current_country = "";
		while ($participants[$counter1]["continent"] == $continent && $participants[$counter1]["nationality"] != $participants[$counter1]["team_name"]) {
			if ($participants[$counter1]["nationality"] != $current_country) {
				$current_country = $participants[$counter1]["nationality"];
				array_push($continent_participants, array($current_country, 0, 0));
			}

			if ($participants[$counter1]["type2"] == "finals") {
				$continent_participants[sizeof($continent_participants) - 1][1]++;
			}
			else {
				$continent_participants[sizeof($continent_participants) - 1][2]++;
			}

			$counter1++;
		}
		
		foreach ($continent_participants as $index => $country_participants) {
			$rowspan = ($index == 0)? sizeof($continent_participants) : 0;
			$counter = club_participants($conn, $participants, $competition, $rowspan, $continent, $continent_chinese, $country_participants[0], $country_participants[1], $country_participants[2], $counter, $screen_size);
		}

		return $counter;
	}

	function club_participants($conn, $participants, $competition, $rowspan, $continent, $continent_chinese, $country, $finals, $qualifications, $counter, $screen_size) {
		if ($continent != "" || $country != "") {
			echo '
		<tr>';

			if ($rowspan > 0) {
				$multiplier = ($screen_size == "small")? 2:1;
				echo '
			<td rowspan="'.($multiplier * $rowspan).'"><img class="badge-small" src="images/continents_small/'.$continent.'.png"> '.$continent_chinese.'</td>';
			}

			if ($country == "") {
				if ($continent == "Europe") $country_chinese = "欧洲足球冠军联赛";
				if ($continent == "South America") $country_chinese = "南美解放着杯";
				if ($continent == "Asia") $country_chinese = "亚洲足球冠军联赛";
				if ($continent == "North America") $country_chinese = "中北美及加勒比海联赛冠军杯";
				if ($continent == "Africa") $country_chinese = "非洲足球冠军联赛";
				if ($continent == "South America") $country_chinese = "南美解放着杯";
				if ($continent == "Oceania") $country_chinese = "大洋洲联赛冠军杯";
			}
			else {
				$sql = 'SELECT country_chinese_name FROM countries WHERE country_name="'.$country.'"';
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$country_chinese = '<img class="badge-small" src="images/countries_small/'.$country.'.png"> '.$row["country_chinese_name"];
				}
			}

			// teams join the finals directly
			if ($screen_size == "small") {
				echo '
			<td colspan="2" style="text-align: center;"><b>'.$country_chinese.'</b></td>
		</tr>
		<tr>';
			}
			else {
				echo '
			<td>'.$country_chinese.'</td>';
			}
		}

		echo '
			<td>
				<div class="row" style="padding-left: 15px;">';

		for ($i = 0; $i < $finals; $i++) {
			$team = $participants[$counter];
			$chinese_name = $team["team_chinese_name"];
			echo '
					<div class="col-xxl-30 col-xl-40 col-lg-60 no-padding" style="cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">
						<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small"> '.$chinese_name.'
					</div>';
			$counter++;
		}

		echo '
				</div>
			</td>';

		// teams that will join qualiafications
		echo '
			<td>
				<div class="row" style="padding-left: 15px;">';

		for ($i = 0; $i < $qualifications; $i++) {
			$team = $participants[$counter];
			$chinese_name = $team["team_chinese_name"];

			// get color (the tournament that the team enters)
			$color = "black";
			$sql = 'SELECT tournament FROM participants WHERE competition='.$competition.' AND type2="finals" AND team_name="'.$team["team_name"].'"';
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				if ($row["tournament"] == "champions_league") $color = "red";
				else if ($row["tournament"] == "union_associations") $color = "blue";
				else if ($row["tournament"] == "winners_cup") $color = "#1A9361";
			}

			echo '
					<div class="col-xxl-30 col-xl-40 col-lg-60 no-padding" style="color: '.$color.'; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">
						<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small"> '.$chinese_name.'
					</div>';
			$counter++;
		}

		echo '
				</div>
			</td>';

		echo '
		</tr>';

		return $counter;
	}

	function print_nation_continent($conn, $tournament, $competition, $participants, $continent, $continent_chinese, $counter) {
		$counter1 = $counter;

		$finals = 0;
		$qualifications = 0;
		while ($participants[$counter1]["continent"] == $continent && $participants[$counter1]["type1"] == "nation") {
			if ($participants[$counter1]["type2"] == "finals") {
				$finals++;
			}
			else {
				$qualifications++;
			}
			$counter1++;
		}

		$counter = national_participants($conn, $participants, $competition, $continent, $continent_chinese, $finals, $qualifications, $counter);

		return $counter;
	}

	function national_participants($conn, $participants, $competition, $continent, $continent_chinese, $finals, $qualifications, $counter) {
		if ($continent != "") {
			echo '
		<tr>';

			// teams join the finals directly
			echo '
			<td><img class="badge-small" src="images/continents_small/'.$continent.'.png"> '.$continent_chinese.'</td>';
		}
		echo '
			<td>
				<div class="row" style="padding-left: 15px;">';

		for ($i = 0; $i < $finals; $i++) {
			$team = $participants[$counter];
			$chinese_name = get_team_chinese_name($conn, $team["team_name"]);
			echo '
					<div class="col-xxl-24 col-xl-30 col-lg-40 col-sm-60 no-padding" style="cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">
						<img class="badge-small" src="images/teams_small/'.$team["team_name"].'.png"> '.$chinese_name.'
					</div>';
				$counter++;
		}

		echo '
				</div>
			</td>';

		// teams that will join qualifications
		echo '
			<td>
				<div class="row" style="padding-left: 15px;">';

		for ($i = 0; $i < $qualifications; $i++) {
			$team = $participants[$counter];
			$chinese_name = get_team_chinese_name($conn, $team["team_name"]);

			// get color (the tournament that the team enters)
			$color = "black";
			$sql = 'SELECT tournament FROM participants WHERE competition='.$competition.' AND type2="finals" AND team_name="'.$team["team_name"].'"';
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				if ($row["tournament"] == "champions_league") $color = "red";
				else if ($row["tournament"] == "union_associations") $color = "blue";
				else if ($row["tournament"] == "winners_cup") $color = "#1A9361";
			}

			if ($team["type2"] == "finals") {
				echo '
					<div class="col-40 no-padding" style="color: '.$color.'; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">
						<img class="badge-small" src="images/teams_small/'.$team["team_name"].'.png"> '.$chinese_name.'
					</div>';
			}
			else {
				echo '
					<div class="col-120 no-padding" style="color: '.$color.'; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">
						<img class="badge-small" src="images/teams_small/'.$team["team_name"].'.png"> '.$chinese_name.'
					</div>';
			}
			
			$counter++;
		}

		echo '
				</div>
			</td>';

		echo '
		</tr>';

		return $counter;
	}
	?>