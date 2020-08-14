						<?php
						// first leg
        				if ($match["game"] % 2 == 0) {
			                $score1_1 = $match["score1"];
			                $score1_2 = $match["score2"];
			                if ($match["round"] == "1_32") $round = '1/32 决赛';
				            else if ($match["round"] == "1_16") $round = '1/16 决赛';
				            else if ($match["round"] == "1_8") $round = '1/8 决赛';
				            else if ($match["round"] == "1_4") $round = '1/4 决赛';
				            else if ($match["round"] == "semi_final") $round = '半决赛';
				            else if ($match["round"] == "final") $round = '决赛';
				            else if ($match["round"] == "122") $round = '122 强';
				            else if ($match["round"] == "70") $round = '70 强';
				            else if ($match["round"] == "48") $round = '48 强';
				            else if ($match["round"] == "24") $round = '24 强';
				            else if ($match["round"] == "12") $round = '12 强';
				            else if ($match["round"] == "6") $round = '6 强';
			                echo '
                <div class="col-lg-60 col-120 border-div" style="margin-top: 10px; padding-bottom: 7px;">
        			<p style="font-size: 16px; font-weight: bold;">
        				<a href="competition.php?tournament='.$tournament.'&competition='.$competition.'&stage=knockout&round='.$match["round"].'" target="_blank" style="text-decoration: underline; color: black;">
        					第 '.$competition.' 届 '.$tournament_chinese.' '.$round.'
        				</a>
        			</p>
        			<div class="row" style="margin-bottom: 7px;">
        				<div class="col-xxl-18 col-xl-20 col-lg-22 col-md-18 col-sm-20" style="padding-right: 0px;">第 1 回合：</div>
        				<div class="col-xxl-60 col-xl-75 col-lg-90 col-md-60 col-sm-80">
        					<div class="row">
	        					<div class="col-57" style="text-align: right;">
	                                <a style="text-decoration: none; color: black; cursor: pointer;"  onclick="open_modal(\''.str_replace("'", "\'", $match["team1"]).'\', '.$competition.')">
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
	                                <a style="text-decoration: none; color: black; cursor: pointer;"  onclick="open_modal(\''.str_replace("'", "\'", $match["team2"]).'\', '.$competition.')">
	                                    '.get_team_chinese_name($conn, $match["team2"]).' <img src="images/teams_small/'.$match["team2"].'.png" style="width: 14px; height: 14px;">
	                                </a>
	                            </div>
                            </div>
                        </div>
                    </div>';
			            }
			            // second leg
			            else {
			            	$score2_1 = $match["score1"];
			                $score2_2 = $match["score2"];
			                $total_score1 = $score2_1 + $score1_2 + $match["extra_score1"];
			                $total_score2 = $score2_2 + $score1_1 + $match["extra_score2"];
			                $away_score1 = $score1_2;
			                $away_score2 = $score2_2 + $match["extra_score2"];

			                if ($total_score1 > $total_score2 || ($total_score1 == $total_score2 && $away_score1 > $away_score2) || ($total_score1 == $total_score2 && $away_score1 == $away_score2 && $match["penalty_score1"] > $match["penalty_score2"])) {
			                    $win_team = $match["team1"];
			                    $win_score = $total_score1;
			                    $lose_team = $match["team2"];
			                    $lose_score = $total_score2;
			                }
			                else {
			                    $win_team = $match["team2"];
			                    $win_score = $total_score2;
			                    $lose_team = $match["team1"];
			                    $lose_score = $total_score1;
			                }
			            	echo '
        			<div class="row" style="padding-bottom: 7px; border-bottom: 1px solid #DDDDDD;">
        				<div class="col-xxl-18 col-xl-20 col-lg-22 col-md-18 col-sm-20" style="padding-right: 0px;">第 2 回合：</div>
        				<div class="col-xxl-60 col-xl-75 col-lg-90 col-md-60 col-sm-80">
        					<div class="row">
	        					<div class="col-57" style="text-align: right;">
	                                <a style="text-decoration: none; color: black; cursor: pointer;"  onclick="open_modal(\''.str_replace("'", "\'", $match["team1"]).'\', '.$competition.')">
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
	                                <a style="text-decoration: none; color: black; cursor: pointer;"  onclick="open_modal(\''.str_replace("'", "\'", $match["team2"]).'\', '.$competition.')">
	                                    '.get_team_chinese_name($conn, $match["team2"]).' <img src="images/teams_small/'.$match["team2"].'.png" style="width: 14px; height: 14px;">
	                                </a>
	                            </div>
                            </div>
                        </div>
                        <div class="col-xxl-30 col-lg-120 col-md-30">';
                        	if ($match["extra_score1"] != "") {
			                    echo '
			                ( '.$match["extra_score1"].' - '.$match["extra_score2"];
			                    if ($match["penalty_score1"] != "") {
			                        echo ', '.$match["penalty_score1"].' - '.$match["penalty_score2"];
			                    }
			                    echo '
			                )';
			                }
                        	echo '
                        </div>
                    </div>
                    <div class="row" style="padding-top: 7px; padding-bottom: 7px; border-bottom: 1px solid #DDDDDD;">
        				<div class="col-xxl-18 col-xl-20 col-lg-22 col-md-18 col-sm-20">总比分：</div>
        				<div class="col-xxl-60 col-xl-75 col-lg-90 col-md-60 col-sm-80">
        					<div class="row">
	        					<div class="col-57" style="text-align: right;">
	                                <a style="text-decoration: none; color: black; cursor: pointer;"  onclick="open_modal(\''.str_replace("'", "\'", $win_team).'\', '.$competition.')">
	                                    <img src="images/teams_small/'.$win_team.'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $win_team).'
	                                </a>
	                            </div>
	                            <div class="col-6 no-padding">
	                                <a href="index.php?tab=history&team1='.$win_team.'&team2='.$lose_team.'" target="_blank" style="color: black; text-decoration: none;">
	                                    <div class="row">
	                                        <div class="col-30 offset-15 no-padding" style="text-align: center;">'.$win_score.'</div>
	                                        <div class="col-30 no-padding" style="text-align: center;"> - </div>
	                                        <div class="col-30 no-padding" style="text-align: center;">'.$lose_score.'</div>
	                                    </div>
	                                </a>
	                            </div>
	                            <div class="col-57" style="text-align: left;">
	                                <a style="text-decoration: none; color: black; cursor: pointer;"  onclick="open_modal(\''.str_replace("'", "\'", $lose_team).'\', '.$competition.')">
	                                    '.get_team_chinese_name($conn, $lose_team).' <img src="images/teams_small/'.$lose_team.'.png" style="width: 14px; height: 14px;">
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
        				<div class="col-xxl-18 col-xl-20 col-lg-22 col-md-18 col-sm-20 col-30">晋级：</div>
        				<a style="text-decoration: none; color: black; cursor: pointer;"  onclick="open_modal(\''.str_replace("'", "\'", $win_team).'\', '.$competition.')">
                            <img src="images/teams_small/'.$win_team.'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $win_team).'
                        </a>
        			</div>
        		</div>';
			            }
						?>