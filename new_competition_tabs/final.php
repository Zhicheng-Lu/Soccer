				<?php
				$sql = 'SELECT COUNT(*) AS num_semi_final_matches FROM '.$tournament.' WHERE competition='.$competition.' AND round="semi_final" AND score1 IS NOT NULL';
				$result = $conn->query($sql);
				$finish_semi_final = False;
				while ($row = $result->fetch_assoc()) {
					if ($row["num_semi_final_matches"] == 4) $finish_semi_final = True;
				}
				
				if ($finish_semi_final) {
					$proceed_teams = array();
					$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="semi_final" ORDER BY game ASC';
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
					$sql = 'INSERT IGNORE INTO '.$tournament.'(competition, group_index, round, game, team1, team2) VALUES('.$competition.', "", "final", 0, "'.$proceed_teams[0]["team_name"].'", "'.$proceed_teams[1]["team_name"].'")';
					$conn->query($sql);
				}

				?>
				<div class="col-xxl-30 col-xl-40 col-md-60 border-div" style="margin-top: 15px;">
	            	<div class="row" style="margin-bottom: 7px; border-bottom: 1px solid #DDDDDD;">
        				<div style="width: 320px;">
    						<?php
    						$match = array("competition"=>$competition, "group_index"=>"", "round"=>"final", "game"=>0, "team1"=>"", "score1"=>"", "score2"=>"", "team2"=>"", "extra_score1"=>"", "extra_score2"=>"", "penalty_score1"=>"", "penalty_score2"=>"");
    						$sql = "SELECT * FROM ".$tournament." WHERE competition=".$competition." AND group_index='' AND round='".$round."' ORDER BY game";
							$result = $conn->query($sql);
							while ($row = $result->fetch_assoc()) {
								$match = $row;
							}

							// decide who win
							if ($match["score1"] > $match["score2"] || ($match["score1"] == $match["score2"] && $match["extra_score1"] > $match["extra_score2"]) || ($match["score1"] == $match["score2"] && $match["extra_score1"] == $match["extra_score2"] && $match["penalty_score1"] > $match["penalty_score2"])) {
								$win_team = $match["team1"];
							}
							else {
								$win_team = $match["team2"];
							}

    						echo '
        					<div class="row" onclick="simu_match(\''.$tournament.'\', '.$competition.', \'final\', 0, \'\', 0, 0, \'\', \'\', 0, 0, -1)">
        						<div class="col-57" style="text-align: right; padding-left: 0px;">
	                                <a style="text-decoration: none; color: black; cursor: pointer;" title="'.show_team_title($conn, $competition, $match["team1"]).'">
	                                    <img src="images/teams_small/'.$match["team1"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $match["team1"]).'
	                                </a>
	                            </div>
	                            <div class="col-6 no-padding" style="cursor: pointer;">
	                                <div class="row" title="'.show_match_title($conn, $competition, $match["team1"], $match["team2"]).'">
                                        <div class="col-30 offset-15 no-padding" style="text-align: center;">'.$match["score1"].'</div>
                                        <div class="col-30 no-padding" style="text-align: center;"> - </div>
                                        <div class="col-30 no-padding" style="text-align: center;">'.$match["score2"].'</div>
                                    </div>
	                            </div>
	                            <div class="col-57" style="text-align: left; padding-right: 0px;">
	                                <a style="text-decoration: none; color: black; cursor: pointer;" title="'.show_team_title($conn, $competition, $match["team2"]).'">
	                                    '.get_team_chinese_name($conn, $match["team2"]).' <img src="images/teams_small/'.$match["team2"].'.png" style="width: 14px; height: 14px;">
	                                </a>
	                            </div>
	                      	</div>
        				</div>
	                    <div class="col-120" style="text-align: right;">';
                        		if ($match["extra_score1"] != "") {
			                    	echo '
	                		（加时 '.$match["extra_score1"].' - '.$match["extra_score2"];
			                    	if ($match["penalty_score1"] != "") {
			                        	echo '， 点球 '.$match["penalty_score1"].' - '.$match["penalty_score2"];
			                    	}
			                		echo '）';
			                	}
			                	else {
			                		echo '
			                <b style="color: white;">1</b>';
			                	}
                        		echo '
                        </div>
                    </div>
                    <div class="row" style="padding-top: 7px; padding-bottom: 7px;">
        				<div style="width: 50px;">&nbsp;冠军：</div>';
        						if ($match["score1"] != "") {
        							echo '
                        <img src="images/teams_small/'.$win_team.'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $win_team).'
        			';
        						}
        						?>
        			</div>
        		</div>

				<?php
				function show_team_title($conn, $competition, $team_name) {
					$sql = 'SELECT * FROM participants WHERE competition='.$competition.' AND team_name="'.$team_name.'"';
					$result = $conn->query($sql);
					while ($row = $result->fetch_assoc()) {
						$attack = $row["attack"];
						$middlefield = $row["middlefield"];
						$defence = $row["defence"];
						$home_plus = $row["home_plus"];
					}
					return '进攻：'.$attack.'，中场：'.$middlefield.'，防守：'.$defence.'，主场：'.$home_plus;
				}

				function show_match_title($conn, $competition, $team1, $team2) {
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

					return get_team_chinese_name($conn, $team1).'：'.$possibility1.'，'.get_team_chinese_name($conn, $team2).'：'.$possibility2;
				}
				?>