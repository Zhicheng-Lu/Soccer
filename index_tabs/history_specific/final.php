						<?php
						if (($match["score1"] + $match["extra_score1"] + $match["penalty_score1"]) > ($match["score2"] + $match["extra_score2"] + $match["penalty_score2"])) {
			                $win_team = $match["team1"];
			            }
			            else {
			                $win_team = $match["team2"];
			            }
        				echo '
        		<div class="col-lg-60 col-120 border-div" style="margin-top: 10px; padding-bottom: 7px;">
        			<p style="font-size: 16px; font-weight: bold;">
        				<a href="competition.php?tournament='.$tournament.'&competition='.$competition.'&stage=knockout&round=final" target="_blank" style="text-decoration: underline; color: black;">
        					第 '.$competition.' 届 '.$tournament_chinese.' 决赛
        				</a>
        			</p>
        			<div class="row" style="border-bottom: 1px solid #DDDDDD; padding-bottom: 7px;">
        				<div class="col-xxl-60 col-xl-75 col-lg-90 col-md-60 col-sm-80">
        					<div class="row">
	        					<div class="col-57" style="text-align: right;">
	                                <a style="text-decoration: none; color: black;" href="index.php?tab=team&team_name='.$match["team1"].'" target="_blank">
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
	                            <div class="col-57" style="text-align: left;">
	                                <a style="text-decoration: none; color: black;" href="index.php?tab=team&team_name='.$match["team2"].'" target="_blank">
	                                    '.get_team_chinese_name($conn, $match["team2"]).' <img src="images/teams_small/'.$match["team2"].'.png" style="width: 14px; height: 14px;">
	                                </a>
	                            </div>
                            </div>
                        </div>
                        <div class="col-xxl-30 col-lg-120 col-md-30">';
                        	if ($match["penalty_score1"] != "") {
			                    echo '( '.$match["penalty_score1"].' - '.$match["penalty_score2"].' )';
			                }

                        	echo '
                        </div>
        			</div>
        			<div class="row" style="padding-top: 7px;">
        				<div class="col-xxl-18 col-xl-20 col-lg-22 col-md-18 col-sm-20">冠军：</div>
        				<a style="text-decoration: none; color: black;" href="index.php?tab=team&team_name='.$win_team.'" target="_blank">
        					<img src="images/teams_small/'.$win_team.'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $win_team).'
        				</a>
        			</div>
        		</div>';
						?>