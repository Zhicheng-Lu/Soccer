	<div class="section d-sm-none">
        <div class="col-116 offset-2">
        	<table style="width: 100%; font-size: 12px;">
				<tr>
					<th colspan="3">俱乐部</th>
				</tr>
				<tr style="width: 100%;">
					<th style="width: 10%; min-width: 75px;">大洲</th>
					<th style="width: 45%;">正赛</th>
					<th style="width: 45%;">预选赛</th>
				</tr>
				<?php
				// all the participants
				$participants = [];
				$sql = "SELECT * FROM participants AS P LEFT JOIN teams AS T on P.team_name=T.team_name LEFT JOIN countries AS C on T.team_nationality=C.country_name WHERE tournament='".$tournament."' AND competition='".$competition."' ORDER BY team_index ASC";
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$participants[$row["team_index"]] = array("team_name"=>$row["team_name"], "team_chinese_name"=>$row["team_chinese_name"], "nationality"=>$row["team_nationality"], "attack"=>$row["attack"], "middlefield"=>$row["middlefield"], "defence"=>$row["defence"], "home_plus"=>$row["home_plus"], "points"=>$row["points"], "nationality"=>$row["team_nationality"], "continent"=>$row["country_continent"], "type1"=>$row["type1"], "type2"=>$row["type2"]);
				}
				$counter = 0;

				// for all clubs
				if ($tournament == "union_associations") {
					// from champions league
					echo '
				<tr>
					<td style="text-align: center;">混合冠军杯预选赛出局</td>';

					$counter = club_participants($conn, $participants, $competition, 1, "", "", "", 12, 24, $counter, "small");
				}
				else if ($tournament == "winners_cup") {
					// from union associations
					echo '
				<tr>
					<td style="text-align: center;">混合联盟杯预选赛出局</td>';

					$counter = club_participants($conn, $participants, $competition, 1, "", "", "", 19, 32, $counter, "small");
				}

				$counter = print_club_continent($conn, $tournament, $competition, $participants, "Europe", "欧洲", $counter, "small");
				$counter = print_club_continent($conn, $tournament, $competition, $participants, "South America", "南美洲", $counter, "small");
				$counter = print_club_continent($conn, $tournament, $competition, $participants, "North America", "北美洲", $counter, "small");
				$counter = print_club_continent($conn, $tournament, $competition, $participants, "Asia", "亚洲", $counter, "small");
				$counter = print_club_continent($conn, $tournament, $competition, $participants, "Africa", "非洲", $counter, "small");
				$counter = print_club_continent($conn, $tournament, $competition, $participants, "Oceania", "大洋洲", $counter, "small");
				?>
			</table>

			<table style="width: 100%; margin-top: 50px; font-size: 12px;">
				<tr>
					<th colspan="4">国家队</th>
				</tr>
				<tr>
					<th style="width: 10%; min-width: 75px;">大洲</th>
					<th style="width: 45%;">正赛</th>
					<th style="width: 45%;">预选赛</th>
				</tr>

				<?php
				// for all national teams
				if ($tournament == "union_associations") {
					echo '
				<tr>
					<td style="text-align: center;">混合冠军杯预选赛出局</td>';

					$counter = national_participants($conn, $participants, $competition, "", "", 2, 0, $counter);
				}
				$counter = print_nation_continent($conn, $tournament, $competition, $participants, "Europe", "欧洲", $counter);
				$counter = print_nation_continent($conn, $tournament, $competition, $participants, "South America", "南美洲", $counter);
				$counter = print_nation_continent($conn, $tournament, $competition, $participants, "Africa", "非洲", $counter);
				$counter = print_nation_continent($conn, $tournament, $competition, $participants, "Asia", "亚洲", $counter);
				$counter = print_nation_continent($conn, $tournament, $competition, $participants, "North America", "北美洲", $counter);
				$counter = print_nation_continent($conn, $tournament, $competition, $participants, "Oceania", "大洋洲", $counter);
				?>

			</table>
        </div>
    </div>