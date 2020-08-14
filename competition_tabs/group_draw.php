	<!-- The Modal -->
    <div id="group_draw_modal" class="modal">
        <!-- Modal content -->
        <div class="modal-content col-lg-108 offset-lg-6 col-120">
        	<div class="modal-header">
        		<h3>小组赛抽签</h3>
                <span class="close" onclick="close_group_draw_modal()">&times;</span>
            </div>
        	<div class="modal-body">
        		<table style="width: 100%;">
        			<tr>
        				<th style="width: 5%; min-width: 100px;">档次</th>
        				<th style="width: 95%;">球队</th>
        			</tr>
        			<?php
        			// determine the number of teams each
					if ($tournament == "champions_league") {
						$number_of_clubs = array(13, 13, 13, 13);
						$number_of_nations = array(8, 8, 8, 8);
						$number_of_groups = 21;
					}
					else if ($tournament == "union_associations") {
						$number_of_clubs = array(20, 20, 20, 20);
						$number_of_nations = array(7, 7, 7, 7);
						$number_of_groups = 27;
					}
					else {
						$number_of_clubs = array(41, 41, 42, 42);
						$number_of_nations = array(7, 7, 6, 6);
						$number_of_groups = 48;
					}

					$teams = array(array(), array(), array(), array());

					for ($i = 0; $i < 4; $i++) {
						echo '
					<tr>
						<td style="text-align: center;">第 '.($i + 1).' 档次</td>
						<td>
							<div class="row" style="padding-left: 15px;">';

						$offset = 0;
						for ($j = 0; $j < $i; $j++) {
							$offset += $number_of_clubs[$j];
						}
						$sql = 'SELECT T.team_name, T.team_chinese_name, T.team_nationality, C.country_continent FROM participants AS P LEFT JOIN teams AS T ON P.team_name=T.team_name LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE tournament="'.$tournament.'" AND competition='.$competition.' AND type1="club" AND type2="finals" ORDER BY points DESC LIMIT '.$offset.', '.$number_of_clubs[$i];
						$result = $conn->query($sql);
						while ($row = $result->fetch_assoc()) {
							echo '
								<div class="col-xxl-15 col-xl-20 col-lg-24 col-md-30 col-sm-40 no-padding" style="margin-top: 4px; margin-bottom: 4px;">
									&nbsp;<img src="images/teams_small/'.$row["team_name"].'.png" class="badge-small"> '.$row["team_chinese_name"].'
								</div>';
							array_push($teams[$i], array("team_name"=>$row["team_name"], "team_chinese_name"=>$row["team_chinese_name"], "nationality"=>$row["team_nationality"], "continent"=>$row["country_continent"]));
						}

						echo '
							</div>
							<div class="row" style="padding-left: 15px;">';

						$offset = 0;
						for ($j = 0; $j < $i; $j++) {
							$offset += $number_of_nations[$j];
						}
						$sql = 'SELECT T.team_name, T.team_chinese_name, T.team_nationality, C.country_continent FROM participants AS P LEFT JOIN teams AS T ON P.team_name=T.team_name LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE tournament="'.$tournament.'" AND competition='.$competition.' AND type1="nation" AND type2="finals" ORDER BY points DESC LIMIT '.$offset.', '.$number_of_nations[$i];
						$result = $conn->query($sql);
						while ($row = $result->fetch_assoc()) {
							echo '
								<div class="col-xxl-15 col-xl-20 col-lg-24 col-md-30 col-sm-40 no-padding" style="margin-top: 4px; margin-bottom: 4px;">
									&nbsp;<img src="images/teams_small/'.$row["team_name"].'.png" class="badge-small"> '.$row["team_chinese_name"].'
								</div>';
							array_push($teams[$i], array("team_name"=>$row["team_name"], "team_chinese_name"=>$row["team_chinese_name"], "nationality"=>$row["team_nationality"], "continent"=>$row["country_continent"]));
						}

						echo '
							</div>
						</td>
					</tr>';
					}
        			?>
        		</table>

        		<div class="row">
        			<?php
        			for ($i = 0; $i < 2; $i++) {
        				if ($i == 0) {
        					$start = 0;
        					$end = ceil($number_of_groups / 2);
        				}
        				else {
        					$start = ceil($number_of_groups / 2);
        					$end = $number_of_groups;
        				}

        				echo '
        			<div class="col-xxl-60">';

        				for ($j = $start; $j < $end; $j++) {
        					if ($j <= 25) {
								$group_index = (chr($j + 65));
							}
							else if ($j <= 34) {
								$group_index = 'Z0'.($j - 25);
							}
							else {
								$group_index = 'Z'.($j - 25);
							}

        					echo '
        				<div class="row" style="margin-top: 10px;">
        					<div class="col-6"><b>'.$group_index.'</b></div>
        					<div class="col-114">
        						<div class="row" style="padding-left: 15px;">';

        					$group_teams = array();
							$sql = 'SELECT team1 FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="'.$group_index.'"';
							$result = $conn->query($sql);
							while ($row = $result->fetch_assoc()) {
								array_push($group_teams, $row["team1"]);
							}
							$group_teams = array_unique($group_teams);

							for ($k = 0; $k < 4; $k++) {
								$team = get_level_team($teams, $group_teams, $k);
								echo '
									<div class="col-md-30 col-60 no-padding">
										<img src="images/teams_small/'.$team["team_name"].'.png" class="badge-small"> '.$team["team_chinese_name"].'
									</div>';
							}

        					echo '
        						</div>
        					</div>
        				</div>';
        				}

        				echo '
        			</div>';
        			}

        			function get_level_team($teams, $group_teams, $level) {
						foreach ($teams[$level] as $index => $team) {
							if ($team["team_name"] == $group_teams[0]) return $team;
							if ($team["team_name"] == $group_teams[1]) return $team;
							if ($team["team_name"] == $group_teams[2]) return $team;
							if ($team["team_name"] == $group_teams[3]) return $team;
						}
					}
        			?>
        		</div>
        	</div>
        </div>
    </div>