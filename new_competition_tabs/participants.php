	<br><br><br><br><br>
	<?php
	include("new_competition_tabs/participants/rank.php");
	include("new_competition_tabs/participants/allocation.php");
	include("new_competition_tabs/participants/large_screen.php");
	include("new_competition_tabs/participants/small_screen.php");
	include("new_competition_tabs/participants/popup_box.php");

	function print_club_continent($conn, $tournament, $competition, $participants, $continent, $continent_chinese, $counter, $screen_size) {
		global $club_rank;
		global $club_allocation;
		echo '
				<tr>';
		
		if ($continent == "Europe") $continent_cup_chinese = "欧洲足球冠军联赛";
		if ($continent == "South America") $continent_cup_chinese = "南美解放着杯";
		if ($continent == "Asia") $continent_cup_chinese = "亚洲足球冠军联赛";
		if ($continent == "North America") $continent_cup_chinese = "中北美及加勒比海联赛冠军杯";
		if ($continent == "Africa") $continent_cup_chinese = "非洲足球冠军联赛";
		if ($continent == "South America") $continent_cup_chinese = "南美解放着杯";
		if ($continent == "Oceania") $continent_cup_chinese = "大洋洲联赛冠军杯";
		$multiplier = ($screen_size == "small")? 2:1;
		$rowspan = sizeof($club_allocation[$tournament][$continent]) + 1;
		echo '
					<td rowspan="'.($multiplier * $rowspan).'"><img class="badge-small" src="images/continents_small/'.$continent.'.png"> '.$continent_chinese.'</td>';

		// teams that finish top in continent competition
		if ($screen_size == "small") {
			echo '
					<td colspan="2" style="text-align: center;"><b>'.$continent_cup_chinese.'</b></td>
				</tr>
				<tr>';
		}
		else {
			echo '
					<td>'.$continent_cup_chinese.'</td>';
		}

		if ($continent == "Europe" || $continent == "South America") {
			$continent_participants = array(4, 4);
		}
		else if ($continent == "Oceania") {
			if ($tournament == "winners_cup") {
				$continent_participants = array(1, 1);
			}
			else {
				$continent_participants = array(0, 1);
			}
		}
		else {
			if ($tournament == "winners_cup") {
				$continent_participants = array(4, 4);
			}
			else {
				$continent_participants = array(2, 2);
			}
		}
		$counter = print_row($conn, $tournament, $competition, $participants, "club", $continent, "", $continent_participants[0], $continent_participants[1], $counter);
		echo '
				</tr>';

		// print participants for all countries in the continent
		foreach ($club_rank[$continent] as $country_rank => $country) {
			if (isset($club_allocation[$tournament][$continent][$country_rank])) {
				$country_name = $country["name"];
				$sql = 'SELECT country_chinese_name FROM countries WHERE country_name="'.$country_name.'"';
					$result = $conn->query($sql);
					while ($row = $result->fetch_assoc()) {
						$country_chinese = '<img class="badge-small" src="images/countries_small/'.$country_name.'.png"> '.$row["country_chinese_name"];
					}
				echo '
				<tr>';
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

			
				$country_participants = $club_allocation[$tournament][$continent][$country_rank];

				$counter = print_row($conn, $tournament, $competition, $participants, "club", "", $country_name, $country_participants["finals"], $country_participants["qualifications"], $counter);
				echo '
				</tr>';
			}
		}

		return $counter;
	}

	function print_nation_continent($conn, $tournament, $competition, $participants, $continent, $continent_chinese, $counter) {
		global $nation_allocation;

		echo '
				<tr>
					<td><img class="badge-small" src="images/continents_small/'.$continent.'.png"> '.$continent_chinese.'</td>';

		$continent_participants = $nation_allocation[$tournament][$continent];

		$counter = print_row($conn, $tournament, $competition, $participants, "nation", $continent, "", $continent_participants["finals"], $continent_participants["qualifications"], $counter);

		echo '
				</tr>';

		return $counter;
	}

	function print_row($conn, $tournament, $competition, $participants, $type, $continent, $country, $finals, $qualifications, $counter) {
		echo '
					<td>
						<div class="row" style="padding-left: 15px; padding-right: 15px;">';

		for ($i = 0; $i < $finals; $i++) {
			show_team($conn, $tournament, $competition, $participants, $type, "finals", $continent, $country, $counter);
			$counter++;
		}

		echo '
						</div>
					</td>
					<td>
						<div class="row" style="padding-left: 15px; padding-right: 15px;">';

		for ($i = 0; $i < $qualifications; $i++) {
			show_team($conn, $tournament, $competition, $participants, $type, "qualifications", $continent, $country, $counter);
			$counter++;
		}

		echo '
						</div>
					</td>';

		return $counter;
	}

	function show_team($conn, $tournament, $competition, $participants, $type1, $type2, $continent, $country, $counter) {
		if ($type1 == "club") $col_size = [30, 40, 60, 120];
		else {
			if ($type2 == "finals") $col_size = [24, 30, 40, 60];
			else $col_size = [60, 60, 60, 120];
		}

		echo '
							<div id="box_'.$counter.'" class="col-xxl-'.$col_size[0].' col-xl-'.$col_size[1].' col-lg-'.$col_size[2].' col-sm-'.$col_size[3].' no-padding" style="margin-top: 1px; margin-bottom: 1px;">';

		// if alraedy got a team
		if (isset($participants[$counter])) {
			$team = $participants[$counter];
			echo '
								<div style="cursor: pointer;" onclick="open_modal(\''.$tournament.'\', '.$competition.', '.$counter.', \''.$type1.'\', \''.$type2.'\', \''.$continent.'\', \''.str_replace("'", "\'", $country).'\')" title="进攻='.$team["attack"].'，中场='.$team["middlefield"].'，防守='.$team["defence"].'，主场='.$team["home_plus"].'，积分='.$team["points"].'">
									<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small"> '.$team["team_chinese_name"].'
								</div>';
		}
		// if not, show a blank box
		else {
			if (($tournament == "union_associations" && ($counter <= 35 || $counter == 131 || $counter == 132)) || ($tournament == "winners_cup" && $counter <= 50)) {
				echo '
								&nbsp;';
			}
			else {
				echo '
								<div style="cursor: pointer; border: 1px solid #888888; border-radius: 3px; width: 98%; margin-left: 1%;" onclick="open_modal(\''.$tournament.'\', '.$competition.', '.$counter.', \''.$type1.'\', \''.$type2.'\', \''.$continent.'\', \''.str_replace("'", "\'", $country).'\')">
									&nbsp;
								</div>';
			}
		}

		echo '
							</div>';
	}
	?>