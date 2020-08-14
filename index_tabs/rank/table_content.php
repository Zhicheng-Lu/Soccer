					<?php
					$print = false;
					if (!isset($_GET["tournament"])) {
						if ($team["total_points"] != 0) {
							if (isset($_GET["nationality"])) {
								if ($team["nationality"] == $_GET["nationality"]) {
									$counter++;
									echo '
				<tr style="background-color: '.($counter%2==0? "#FFFFFF":"#EAEAEA").';" id="'.$team["temp_name"].'">
					<td style="text-align: center; width: '.$columns[0][1].'px;">'.($i + 1).'</td>
					<td style="text-align: center; width: '.$columns[1][1].'px;">'.$counter.'</td>
					<td onclick="window.open(\'index.php?tab=team&team_name='.str_replace("'", "\'", $team["temp_name"]).'\')" style="cursor: pointer; width: '.$columns[2][1].'px;">&nbsp;<img src="images/teams_small/'.$team["temp_name"].'.png" class="badge-small"> '.$team["chinese_name"].'</td>
					<td style="text-align: center; font-weight: bold; width: '.$columns[3][1].'px;">'.$team["total_points"].'</td>';
									$print = true;
									$offset = 4;
								}
							}
							else if (isset($_GET["continent"])) {
								if ($team["continent"] == $_GET["continent"]) {
									$counter++;
									echo '
				<tr style="background-color: '.($counter%2==0? "#FFFFFF":"#EAEAEA").';" id="'.$team["temp_name"].'">
					<td style="text-align: center; width: '.$columns[0][1].'px;">'.($i + 1).'</td>
					<td style="text-align: center; width: '.$columns[1][1].'px;">'.$counter.'</td>
					<td onclick="window.open(\'index.php?tab=team&team_name='.str_replace("'", "\'", $team["temp_name"]).'\')" style="cursor: pointer; width: '.$columns[2][1].'px;">&nbsp;<img src="images/teams_small/'.$team["temp_name"].'.png" class="badge-small"> '.$team["chinese_name"].'</td>
					<td style="width: '.$columns[3][1].'px; text-align:">&nbsp;'.$team["nationality_chinese"].'</td>
					<td style="text-align: center; font-weight: bold; width: '.$columns[4][1].'px;">'.$team["total_points"].'</td>';
									$print = true;
									$offset = 5;
								}
							}
							else {
								echo '
				<tr style="background-color: '.$color.';" id="'.$team["temp_name"].'">
					<td style="text-align: center; width: '.$columns[0][1].'px;">'.($i + 1).'</td>
					<td onclick="window.open(\'index.php?tab=team&team_name='.str_replace("'", "\'", $team["temp_name"]).'\')" style="cursor: pointer; width: '.$columns[1][1].'px;">&nbsp;<img src="images/teams_small/'.$team["temp_name"].'.png" class="badge-small"> '.$team["chinese_name"].'</td>
					<td style="width: '.$columns[2][1].'px; text-align:">&nbsp;'.$team["nationality_chinese"].'</td>
					<td style="width: '.$columns[3][1].'px;">&nbsp;'.$team["continent_chinese"].'</td>
					<td style="text-align: center; font-weight: bold; width: '.$columns[4][1].'px;">'.$team["total_points"].'</td>';
								$print = true;
								$offset = 5;
							}

							if ($print) {
								$champions_league_total_match = $team["champions_league_win"] + $team["champions_league_draw"] + $team["champions_league_lose"];
								$union_associations_total_match = $team["union_associations_win"] + $team["union_associations_draw"] + $team["union_associations_lose"];
								$winners_cup_total_match = $team["winners_cup_win"] + $team["winners_cup_draw"] + $team["winners_cup_lose"];
								$total_match = array("champions_league"=>$champions_league_total_match, "union_associations"=>$union_associations_total_match, "winners_cup"=>$winners_cup_total_match);

								for ($j = $offset; $j < sizeof($columns); $j++) {
									$column = $columns[$j];
									if ($column[3] == "") {
										if ($total_match[$column[2]] > 0) $text = $total_match[$column[2]];
										else $text = "";
									}
									else if ($column[3] == "win" || $column[3] == "draw" || $column[3] == "lose") {
										if ($total_match[$column[2]] > 0) $text = $team[$column[2].'_'.$column[3]];
										else $text = "";
									}
									else {
										if ($team[$column[2].'_'.$column[3]] > 0) {
											if ($column[3] == "group") $text = $team[$column[2].'_'.$column[3]] / 6;
											else if ($column[3] == "final" || $column[3] == "champion" || $column[3] == "points") $text = $team[$column[2].'_'.$column[3]];
											else if ($column[3] == "finals") $text = $team[$column[2].'_'.$column[3]] /4;
											else $text = $team[$column[2].'_'.$column[3]] / 2;
										}
										else $text = "";
									}
									$columns[$j][6] += ($text==""? 0:$text);
									echo '
					<td style="width: '.$column[1].'px; text-align: center; font-weight: '.$column[4].'">'.$text.'</td>';
								}
							}
						}
					}









					else {
						$print = false;
						$tournament = $_GET["tournament"];
						$total_match = $team[$tournament."_win"] + $team[$tournament."_draw"] + $team[$tournament."_lose"];

						if ($total_match != 0) {
							if (isset($_GET["nationality"])) {
								if ($team["nationality"] == $_GET["nationality"]) {
									$counter++;
									echo '
				<tr style="background-color: '.($counter%2==0? "#FFFFFF":"#EAEAEA").';" id="'.$team["temp_name"].'">
					<td style="text-align: center; width: '.$columns[0][1].'px;">'.($i + 1).'</td>
					<td style="text-align: center; width: '.$columns[1][1].'px;">'.$counter.'</td>
					<td onclick="window.open(\'index.php?tab=team&team_name='.str_replace("'", "\'", $team["temp_name"]).'\')" style="cursor: pointer; width: '.$columns[2][1].'px;">&nbsp;<img src="images/teams_small/'.$team["temp_name"].'.png" class="badge-small"> '.$team["chinese_name"].'</td>
					<td style="text-align: center; font-weight: bold; width: '.$columns[3][1].'px;">'.$team[$tournament.'_points'].'</td>';
									$print = true;
								}
							}
							else if (isset($_GET["continent"])) {
								if ($team["continent"] == $_GET["continent"]) {
									$counter++;
									echo '
				<tr style="background-color: '.($counter%2==0? "#FFFFFF":"#EAEAEA").';" id="'.$team["temp_name"].'">
					<td style="text-align: center; width: '.$columns[0][1].'px;">'.($i + 1).'</td>
					<td style="text-align: center; width: '.$columns[1][1].'px;">'.$counter.'</td>
					<td onclick="window.open(\'index.php?tab=team&team_name='.str_replace("'", "\'", $team["temp_name"]).'\')" style="cursor: pointer; width: '.$columns[2][1].'px;">&nbsp;<img src="images/teams_small/'.$team["temp_name"].'.png" class="badge-small"> '.$team["chinese_name"].'</td>
					<td style="width: '.$columns[3][1].'px; text-align:">&nbsp;'.$team["nationality_chinese"].'</td>
					<td style="text-align: center; font-weight: bold; width: '.$columns[4][1].'px;">'.$team[$tournament.'_points'].'</td>';
									$print = true;
								}
							}
							else {
								echo '
				<tr style="background-color: '.$color.';" id="'.$team["temp_name"].'">
					<td style="text-align: center; width: '.$columns[0][1].'px;">'.($i + 1).'</td>
					<td onclick="window.open(\'index.php?tab=team&team_name='.str_replace("'", "\'", $team["temp_name"]).'\')" style="cursor: pointer; width: '.$columns[1][1].'px;">&nbsp;<img src="images/teams_small/'.$team["temp_name"].'.png" class="badge-small"> '.$team["chinese_name"].'</td>
					<td style="width: '.$columns[2][1].'px; text-align:">&nbsp;'.$team["nationality_chinese"].'</td>
					<td style="width: '.$columns[3][1].'px;">&nbsp;'.$team["continent_chinese"].'</td>
					<td style="text-align: center; font-weight: bold; width: '.$columns[4][1].'px;">'.$team[$tournament.'_points'].'</td>';
								$print = true;
							}

							if ($print) {
								foreach ($rounds as $index => $round) {
									if ($round[0] == "champion") {
										$text = $team[$tournament."_champion"];
										$rounds[$index][2][0] += $text;
										echo '
					<td style="text-align: center; font-weight: bold; color: red; width: '.$basic_unit2.'px;">'.($text==0? "":$text).'</td>';
									}
									else {
										$round_win = ($round[0]==""? $team[$tournament.'_win']:$team[$tournament.'_'.$round[0].'_win']);
										$round_draw = ($round[0]==""? $team[$tournament.'_draw']:$team[$tournament.'_'.$round[0].'_draw']);
										$round_lose = ($round[0]==""? $team[$tournament.'_lose']:$team[$tournament.'_'.$round[0].'_lose']);
										$round_total_match = $round_win + $round_draw + $round_lose;
										$rounds[$index][2][1] += $round_total_match;
										$rounds[$index][2][2] += $round_win;
										$rounds[$index][2][3] += $round_draw;
										$rounds[$index][2][4] += $round_lose;
										if ($round[0] != "") {
											if ($round_total_match == 0) $text = "";
											else {
												if ($round[0] == "final") $text = $round_total_match;
												else if ($round[0] == "finals") $text = $round_total_match / 4;
												else if ($round[0] == "group") $text = $round_total_match / 6;
												else $text = $round_total_match / 2;
												$rounds[$index][2][0] += $text;
											}
											echo '
					<td style="text-align: center; font-weight: bold; width: '.$basic_unit1.'px;">'.$text.'</td>';
										}
										echo '
					<td style="text-align: center; width: '.$basic_unit1.'px;">'.($round_total_match==0? "":$round_total_match).'</td>
					<td style="text-align: center; width: '.$basic_unit1.'px;">'.($round_total_match==0? "":$round_win).'</td>
					<td style="text-align: center; width: '.$basic_unit1.'px;">'.($round_total_match==0? "":$round_draw).'</td>
					<td style="text-align: center; width: '.$basic_unit1.'px;">'.($round_total_match==0? "":$round_lose).'</td>';
									}
								}
							}
						}
					}