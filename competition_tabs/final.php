			<div class="row">
				<div class="col-xxl-30 col-xl-40 col-md-60 border-div" style="margin-top: 15px;">
	            	<div class="row" style="margin-bottom: 7px; border-bottom: 1px solid #DDDDDD;">
        				<div style="width: 320px;">
        					<div class="row">
        						<?php
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
        						<div class="col-57" style="text-align: right; padding-left: 0px;">
	                                <a style="text-decoration: none; color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $match["team1"]).'\', '.$competition.')">
	                                    <img src="images/teams_small/'.$match["team1"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $match["team1"]).'
	                                </a>
	                            </div>
	                            <div class="col-6 no-padding">
	                                <a href="index.php?tab=history&team1='.$match["team1"].'&team2='.$match["team2"].'" target="_blank" style="color: black; text-decoration: none;">
	                                    <div class="row">
	                                        <div class="col-30 offset-15 no-padding" style="text-align: center;">'.$match["score1"].'</div>
	                                        <div class="col-30 no-padding" style="text-align: center;"> - </div>
	                                        <div class="col-30 no-padding" style="text-align: center;">'.$match["score2"].'</div>
	                                    </div>
	                                </a>
	                            </div>
	                            <div class="col-57" style="text-align: left; padding-right: 0px;">
	                                <a style="text-decoration: none; color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $match["team2"]).'\', '.$competition.')">
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
        				<div style="width: 50px;">&nbsp;晋级：</div>
        				<a style="text-decoration: none; color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $win_team).'\', '.$competition.')">
                            <img src="images/teams_small/'.$win_team.'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $win_team).'
                        </a>
        			</div>';
        						?>
        		</div>
			</div>