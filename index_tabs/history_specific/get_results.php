				<?php
				// get all results
				$matches = array();
				$competitions = array();

				// get all the competitions and all the tournaments
				$sql = "SELECT DISTINCT competition FROM champions_league";
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					array_push($competitions, $row["competition"]);
				}
				$tournaments = array(array("name"=>"champions_league", "chinese_name"=>"世界足球冠军杯"), array("name"=>"union_associations", "chinese_name"=>"世界足球联盟杯"), array("name"=>"winners_cup", "chinese_name"=>"世界足球优胜者杯"));

				// matching criteria
				$criterias = array();
				for ($i = 1; $i <= 2; $i++) {
					if (isset($_GET["team".$i])) {
						array_push($criterias, 'X.team1="'.$_GET["team".$i].'"');
						array_push($criterias, 'X.team2="'.$_GET["team".$i].'"');
					}
					else if (isset($_GET["country".$i])) {
						array_push($criterias, 'T1.team_nationality="'.$_GET["country".$i].'"');
						array_push($criterias, 'T2.team_nationality="'.$_GET["country".$i].'"');
					}
					else if (isset($_GET["continent".$i])) {
						array_push($criterias, 'C1.country_continent="'.$_GET["continent".$i].'"');
						array_push($criterias, 'C2.country_continent="'.$_GET["continent".$i].'"');
					}
				}
				$criteria = '(('.$criterias[0].' AND '.$criterias[3].') OR ('.$criterias[2].' AND '.$criterias[1].'))';

				foreach ($competitions as $key => $competition) {
					foreach ($tournaments as $key => $tournament) {
						// group matches
						$sql = 'SELECT competition, group_index, round, game, team1, score1, score2, team2, extra_score1, extra_score2, penalty_score1, penalty_score2, T1.team_nationality AS country1, T2.team_nationality AS country2, C1.country_continent AS continent1, C2.country_continent AS continent2 FROM '.$tournament["name"].' AS X LEFT JOIN teams AS T1 ON X.team1=T1.team_name LEFT JOIN teams AS T2 ON X.team2=T2.team_name LEFT JOIN countries AS C1 ON T1.team_nationality=C1.country_name LEFT JOIN countries AS C2 ON T2.team_nationality=C2.country_name WHERE X.competition='.$competition.' AND X.group_index<>"" AND '.$criteria.' ORDER BY X.group_index, X.round, X.game';
						$result = $conn->query($sql);
				        while ($row = $result->fetch_assoc()) {
				            array_push($matches, array("tournament"=>$tournament["name"], "tournament_chinese"=>$tournament["chinese_name"], "competition"=>$row["competition"], "group"=>$row["group_index"], "round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "country1"=>$row["country1"], "continent1"=>$row["continent1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"], "country2"=>$row["country2"], "continent2"=>$row["continent2"], "extra_score1"=>$row["extra_score1"], "extra_score2"=>$row["extra_score2"], "penalty_score1"=>$row["penalty_score1"], "penalty_score2"=>$row["penalty_score2"]));
				        }
				        // knockout matches
				        $sql = 'SELECT competition, group_index, round, game, team1, score1, score2, team2, extra_score1, extra_score2, penalty_score1, penalty_score2, T1.team_nationality AS country1, T2.team_nationality AS country2, C1.country_continent AS continent1, C2.country_continent AS continent2 FROM '.$tournament["name"].' AS X LEFT JOIN teams AS T1 ON X.team1=T1.team_name LEFT JOIN teams AS T2 ON X.team2=T2.team_name LEFT JOIN countries AS C1 ON T1.team_nationality=C1.country_name LEFT JOIN countries AS C2 ON T2.team_nationality=C2.country_name WHERE X.competition='.$competition.' AND X.group_index="" AND '.$criteria.' AND round<>"club_leveldown_first" AND round<>"club_leveldown_second" AND round<>"club_qualifications_first" AND round<>"club_qualifications_second" AND round<>"nation_qualifications_first" ORDER BY CASE WHEN round="122" THEN 122 WHEN round="70" THEN 70 WHEN round="48" THEN 48 WHEN round="24" THEN 24 WHEN round="12" THEN 12 WHEN round="6" THEN 6 WHEN round="finals" THEN 3 WHEN round="1_32" THEN 64 WHEN round="1_16" THEN 32 WHEN round="1_8" THEN 16 WHEN round="1_4" THEN 8 WHEN round="semi_final" THEN 4 WHEN round="final" THEN 2 END DESC, game ASC';
						$result = $conn->query($sql);
				        while ($row = $result->fetch_assoc()) {
				            array_push($matches, array("tournament"=>$tournament["name"], "tournament_chinese"=>$tournament["chinese_name"], "competition"=>$row["competition"], "group"=>$row["group_index"], "round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "country1"=>$row["country1"], "continent1"=>$row["continent1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"], "country2"=>$row["country2"], "continent2"=>$row["continent2"], "extra_score1"=>$row["extra_score1"], "extra_score2"=>$row["extra_score2"], "penalty_score1"=>$row["penalty_score1"], "penalty_score2"=>$row["penalty_score2"]));
				        }
				    }
				}

				$win1 = 0;
				$score1 = 0;
				$win2 = 0;
				$score2 = 0;
				$draw = 0;
				$home_win1 = 0;
				$home_draw1 = 0;
				$home_lose1 = 0;
				$home_score1 = 0;
				$home_concede1 = 0;
				$home_win2 = 0;
				$home_draw2 = 0;
				$home_lose2 = 0;
				$home_score2 = 0;
				$home_concede2 = 0;

				foreach ($matches as $key => $match) {
					if (match($match, 1, 1) && match($match, 2, 2)) {
						$score1 += $match["score1"] + $match["extra_score1"];
						$score2 += $match["score2"] + $match["extra_score2"];
						if ($match["round"] != "final") $home_score1 += $match["score1"] + $match["extra_score1"];
						if ($match["round"] != "final") $home_concede1 += $match["score2"] + $match["extra_score2"];
						if ($match["score1"] > $match["score2"]) {
							$win1++;
							if ($match["round"] != "final") $home_win1++;
						}
						else if ($match["score1"] == $match["score2"]) {
							$draw++;
							if ($match["round"] != "final") $home_draw1++;
						}
						else {
							$win2++;
							if ($match["round"] != "final") $home_lose1++;
						}
					}

					if (match($match, 1, 2) && match($match, 2, 1)) {
						$score1 += $match["score2"] + $match["extra_score2"];
						$score2 += $match["score1"] + $match["extra_score1"];
						if ($match["round"] != "final") $home_score2 += $match["score1"] + $match["extra_score1"];
						if ($match["round"] != "final") $home_concede2 += $match["score2"] + $match["extra_score2"];
						if ($match["score1"] > $match["score2"]) {
							$win2++;
							if ($match["round"] != "final") $home_win2++;
						}
						else if ($match["score1"] == $match["score2"]) {
							$draw++;
							if ($match["round"] != "final") $home_draw2++;
						}
						else {
							$win1++;
							if ($match["round"] != "final") $home_lose2++;
						}
					}
				}

				$total_match = $win1 + $draw + $win2;
				$win_rate1 = (($total_match==0)? 0 : number_format($win1 / $total_match * 100, 1));
				$draw_rate = (($total_match==0)? 0 : number_format($draw / $total_match * 100, 1));
				$win_rate2 = (($total_match==0)? 0 : number_format($win2 / $total_match * 100, 1));
				$average_score1 = (($total_match==0)? 0 : number_format($score1 / $total_match, 2));
				$average_score2 = (($total_match==0)? 0 : number_format($score2 / $total_match, 2));
				$total_match = $home_win1 + $home_draw1 + $home_lose1;
				$home_win_rate1 = (($total_match==0)? 0 : number_format($home_win1 / $total_match * 100, 1));
				$home_draw_rate1 = (($total_match==0)? 0 : number_format($home_draw1 / $total_match * 100, 1));
				$home_lose_rate1 = (($total_match==0)? 0 : number_format($home_lose1 / $total_match * 100, 1));
				$home_average_score1 = (($total_match==0)? 0 : number_format($home_score1 / $total_match, 2));
				$home_average_concede1 = (($total_match==0)? 0 : number_format($home_concede1 / $total_match, 2));
				$total_match = $home_win2 + $home_draw2 + $home_lose2;
				$home_win_rate2 = (($total_match==0)? 0 : number_format($home_win2 / $total_match * 100, 1));
				$home_draw_rate2 = (($total_match==0)? 0 : number_format($home_draw2 / $total_match * 100, 1));
				$home_lose_rate2 = (($total_match==0)? 0 : number_format($home_lose2 / $total_match * 100, 1));
				$home_average_score2 = (($total_match==0)? 0 : number_format($home_score2 / $total_match, 2));
				$home_average_concede2 = (($total_match==0)? 0 : number_format($home_concede2 / $total_match, 2));
				?>