	<br><br><br><br><br>
	<div class="section">
		<div class="container">
			<h3 style="margin-bottom: 30px;">世界足球冠军杯</h3>
			<?php print_tournament($conn, "champions_league"); ?>

			<h3 style="margin-top: 30px; margin-bottom: 30px;">世界足球联盟杯</h3>
			<?php print_tournament($conn, "union_associations"); ?>

			<h3 style="margin-top: 30px; margin-bottom: 30px;">世界足球优胜者杯</h3>
			<?php print_tournament($conn, "winners_cup"); ?>
		</div>
	</div>

	<?php
	$countries_champions = array();
	$countries_champions = fill_countries_champions($conn, "champions_league", $countries_champions);
	$countries_champions = fill_countries_champions($conn, "union_associations", $countries_champions);
	$countries_champions = fill_countries_champions($conn, "winners_cup", $countries_champions);
	usort($countries_champions, "sort_countries_champions");
	?>

	<div class="section">
		<div class="container">
			<table style="width: 100%;">
				<tr>
					<th rowspan="2" colspan="2" style="width: 22%;">国家</th>
					<th colspan="3" style="width: 26%;">冠军杯</th>
					<th colspan="3" style="width: 26%;">联盟杯</th>
					<th colspan="3" style="width: 26%;">优胜者杯</th>
				</tr>
				<tr>
					<th style="width: 3%;">届数</th>
					<th style="width: 20%;">球队</th>
					<th style="width: 3%;">总数</th>
					<th style="width: 3%;">届数</th>
					<th style="width: 20%;">球队</th>
					<th style="width: 3%;">总数</th>
					<th style="width: 3%;">届数</th>
					<th style="width: 20%;">球队</th>
					<th style="width: 3%;">总数</th>
				</tr>
				<?php
				foreach ($countries_champions as $country_champions) {
					$num_champions_league = sizeof($country_champions["champions_league"]);
					$num_union_associations = sizeof($country_champions["union_associations"]);
					$num_winners_cup = sizeof($country_champions["winners_cup"]);
					$total_num = $num_champions_league + $num_union_associations + $num_winners_cup;
					$max_num = max($num_champions_league, $num_union_associations, $num_winners_cup);

					$sql = 'SELECT country_chinese_name FROM countries WHERE country_name="'.$country_champions["country_name"].'"';
					$result = $conn->query($sql);
					while ($row = $result->fetch_assoc()) {
						$country_chinese_name = $row["country_chinese_name"];
					}

					for ($i=0; $i < $max_num; $i++) { 
						echo '
				<tr>';

						if ($i == 0) {
							echo '
					<td rowspan="'.$max_num.'" style="width: 19%;">&nbsp;<img src="images/countries_small/'.$country_champions["country_name"].'.png" class="badge-small">&nbsp;'.$country_chinese_name.'</td>
					<td rowspan="'.$max_num.'" style="width: 3%; text-align: center;">'.$total_num.'</td>';
						}

						print_country_champion($conn, "champions_league", $country_champions, $i, $max_num);
						print_country_champion($conn, "union_associations", $country_champions, $i, $max_num);
						print_country_champion($conn, "winners_cup", $country_champions, $i, $max_num);
					
						echo '
				</tr>';
					}
				}
				?>
			</table>
		</div>
	</div>

	<?php
	include("includes/team_modal/modal.php");

	function print_tournament($conn, $tournament) {
		$champions = array();
		$sql = 'SELECT C.competition, C.team1, T.team_chinese_name, T.team_nationality FROM '.$tournament.' AS C LEFT JOIN teams AS T ON C.team1=T.team_name WHERE C.round="champion" ORDER BY C.competition ASC';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			array_push($champions, array("competition"=>$row["competition"], "team_name"=>$row["team1"], "chinese_name"=>$row["team_chinese_name"]));
		}

		echo '
			<div class="row">';

		foreach ($champions as $champion) {
			echo '
				<div class="col-xxl-30 col-xl-30 col-lg-40 col-md-60 col-120" style="margin-bottom: 20px;">
					<div style="width: 90px; display: inline-block;">
						<a href="competition.php?tournament='.$tournament.'&competition='.$champion["competition"].'&stage='.($champion["competition"]<=14? "groups":"participants").'" target="_blank" style="text-decoration: underline; color: blue;">第 '.$champion["competition"].' 届：</a>
					</div>
					<a href="javascript:void(0)" style="color: black;" onclick="open_modal(\''.str_replace("'", "\'", $champion["team_name"]).'\', '.$champion["competition"].')">
						<img class="badge-small" src="images/teams_small/'.$champion["team_name"].'.png">&nbsp;'.$champion["chinese_name"].'
					</a>
				</div>';
		}

		echo '
				<div class="col-xxl-30 col-xl-30 col-lg-40 col-md-60 col-120" style="margin-bottom: 20px;">
					<div style="width: 90px; display: inline-block;">
						<a href="new_competition.php?tournament='.$tournament.'&competition='.($champion["competition"] + 1).'&stage=participants" target="_blank" style="text-decoration: underline; color: blue;">第 '.($champion["competition"] + 1).' 届：</a>
					</div>
					？？？
				</div>
			</div>	';
	}

	function fill_countries_champions($conn, $tournament, $countries_champions) {
		$champions = array();
		$sql = 'SELECT C.competition, C.team1, T.team_chinese_name, T.team_nationality FROM '.$tournament.' AS C LEFT JOIN teams AS T ON C.team1=T.team_name WHERE C.round="champion" ORDER BY C.competition ASC';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			array_push($champions, array("competition"=>$row["competition"], "team_name"=>$row["team1"], "chinese_name"=>$row["team_chinese_name"], "nationality"=>$row["team_nationality"]));
		}

		foreach ($champions as $champion) {
			$country_exists = False;
			foreach ($countries_champions as $index => $country_champions) {
				if ($champion["nationality"] == $country_champions["country_name"]) {
					array_push($countries_champions[$index][$tournament], $champion);
					$country_exists = True;
					break;
				}
			}
			if (!$country_exists) {
				array_push($countries_champions, array("country_name"=>$champion["nationality"], "champions_league"=>array(), "union_associations"=>array(), "winners_cup"=>array()));
				array_push($countries_champions[sizeof($countries_champions)-1][$tournament], $champion);
			}
		}

		return $countries_champions;
	}

	function sort_countries_champions($country1, $country2) {
		$num_champions_league1 = sizeof($country1["champions_league"]);
		$num_union_associations1 = sizeof($country1["union_associations"]);
		$num_winners_cup1 = sizeof($country1["winners_cup"]);
		$num_champions_league2 = sizeof($country2["champions_league"]);
		$num_union_associations2 = sizeof($country2["union_associations"]);
		$num_winners_cup2 = sizeof($country2["winners_cup"]);

		$total1 = $num_champions_league1 + $num_union_associations1 + $num_winners_cup1;
		$total2 = $num_champions_league2 + $num_union_associations2 + $num_winners_cup2;

		if ($total1 > $total2) return -1;
		if ($total1 < $total2) return 1;

		if ($num_champions_league1 > $num_champions_league2) return -1;
		if ($num_champions_league1 < $num_champions_league2) return 1;

		if ($num_union_associations1 > $num_union_associations2) return -1;
		if ($num_union_associations1 < $num_union_associations2) return 1;

		return 0;
	}

	function print_country_champion($conn, $tournament, $country_champions, $i, $max_num) {
		if (sizeof($country_champions[$tournament]) == 0) {
			if ($i == 0) {
				echo '
						<td rowspan="'.$max_num.'" style="text-align: center;" colspan="3"></td>';
			}
		}
		else {
			if ($i == sizeof($country_champions[$tournament])) {
				echo '
						<td colspan="2" rowspan="'.($max_num-sizeof($country_champions[$tournament])).'"></td>';
			}
			elseif ($i < sizeof($country_champions[$tournament])) {
				$team = $country_champions[$tournament][$i];
				echo '
						<td style="text-align: center;"><a href="competition.php?tournament='.$tournament.'&competition='.$team["competition"].'&stage='.($team["competition"]<=14? "groups":"participants").'" target="_blank" style="text-decoration: underline; color: blue;">'.$team["competition"].'<a></td>
						<td><a href="javascript:void(0)" style="color: black;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$team["competition"].')">&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small">&nbsp;'.$team["chinese_name"].'</a></td>';
			}

			if ($i == 0) {
				echo '
						<td rowspan="'.$max_num.'" style="text-align: center;">'.sizeof($country_champions[$tournament]).'</td>';
			}
		}
	}
	?>