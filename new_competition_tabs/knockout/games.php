<?php
function draw_games($conn, $tournament, $competition, $round, $win_type2, $win_start_index, $loss_tournament, $loss_type2, $loss_start_index) {
	$win_index = $win_start_index;
	$loss_index = $loss_start_index;

	$sql = "SELECT * FROM ".$tournament." WHERE competition=".$competition." AND group_index='' AND round='".$round."' ORDER BY game";
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$match = $row;
		// first leg
		if ($match["game"] % 2 == 0) {
            $score1_1 = $match["score1"];
            $score1_2 = $match["score2"]; 
        }
        // second leg
        else {
        	$score2_1 = $match["score1"];
            $score2_2 = $match["score2"];
            if (is_null($score2_1)) {
            	$total_score1 = null;
            	$total_score2 = null;
            }
            else {
            	$total_score1 = $score2_1 + $score1_2 + $match["extra_score1"];
            	$total_score2 = $score2_2 + $score1_1 + $match["extra_score2"];
            }
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
            <div class="col-xxl-30 col-xl-40 col-md-60 border-div" style="margin-top: 15px;">
            	<div class="row d-md-none" onclick="show_details('.$match["game"].')" style="padding-top: 7px; padding-bottom: 7px; border-bottom: 1px solid #DDDDDD; background-color: #EEEEEE;">
    				<div style="width: 320px;">
    					<div class="row">
        					<div class="col-57" style="text-align: right;">
                                <img src="images/teams_small/'.$win_team.'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $win_team).'
                            </div>
                            <div class="col-6 no-padding">
                                <div class="row">
                                    <div class="col-30 offset-15 no-padding" style="text-align: center;">'.$win_score.'</div>
                                    <div class="col-30 no-padding" style="text-align: center;"> - </div>
                                    <div class="col-30 no-padding" style="text-align: center;">'.$lose_score.'</div>
                                </div>
                            </div>
                            <div class="col-57" style="text-align: left;">
                                '.get_team_chinese_name($conn, $lose_team).' <img src="images/teams_small/'.$lose_team.'.png" style="width: 14px; height: 14px;">
                            </div>
                        </div>
                    </div>
    			</div>
            	<div class="row d-none d-md-block knockout_box_'.$match["game"].'" style="margin-bottom: 7px;">
    				<div style="width: 320px;" onclick="simu_match(\''.$tournament.'\', '.$competition.', \''.$round.'\', '.($match["game"]-1).', \'\', 0, 0, \'\', \'\', 0, 0, -1)">
    					<div class="row">
        					<div class="col-57" style="text-align: right; padding-left: 0px;">
                                <a style="text-decoration: none; color: black; cursor: pointer;" title="'.show_team_title($conn, $competition, $match["team2"]).'">
                                    <img src="images/teams_small/'.$match["team2"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $match["team2"]).'
                                </a>
                            </div>
                            <div class="col-6 no-padding">
                                <div class="row" title="'.show_match_title($conn, $competition, $match["team2"], $match["team1"]).'" style="cursor: pointer;">
                                    <div class="col-30 offset-15 no-padding" style="text-align: center;">'.$score1_1.'</div>
                                    <div class="col-30 no-padding" style="text-align: center;"> - </div>
                                    <div class="col-30 no-padding" style="text-align: center;">'.$score1_2.'</div>
                                </div>
                            </div>
                            <div class="col-57" style="text-align: left; padding-right: 0px;">
                                <a style="text-decoration: none; color: black; cursor: pointer;" title="'.show_team_title($conn, $competition, $match["team1"]).'">
                                    '.get_team_chinese_name($conn, $match["team1"]).' <img src="images/teams_small/'.$match["team1"].'.png" style="width: 14px; height: 14px;">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
    			<div class="row d-none d-md-block knockout_box_'.$match["game"].'" style="padding-bottom: 7px; border-bottom: 1px solid #DDDDDD;">
    				<div style="width: 320px;" onclick="simu_match(\''.$tournament.'\', '.$competition.', \''.$round.'\', '.$match["game"].', \''.$win_type2.'\', '.$win_start_index.', '.$win_index.', \''.$loss_tournament.'\', \''.$loss_type2.'\', '.$loss_start_index.', '.$loss_index.', -1)">
    					<div class="row">
        					<div class="col-57" style="text-align: right; padding-left: 0px;">
                                <a style="text-decoration: none; color: black; cursor: pointer;" title="'.show_team_title($conn, $competition, $match["team1"]).'">
                                    <img src="images/teams_small/'.$match["team1"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $match["team1"]).'
                                </a>
                            </div>
                            <div class="col-6 no-padding">
                                <div class="row" title="'.show_match_title($conn, $competition, $match["team1"], $match["team2"]).'" style="cursor: pointer;">
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

                <div class="row d-none d-md-block knockout_box_'.$match["game"].'" style="padding-top: 7px; padding-bottom: 7px; border-bottom: 1px solid #DDDDDD;">
    				<div style="width: 320px;">
    					<div class="row">
        					<div class="col-57" style="text-align: right;">
                                <img src="images/teams_small/'.$win_team.'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $win_team).'
                            </div>
                            <div class="col-6 no-padding">
	                            <div class="row">
	                                <div class="col-30 offset-15 no-padding" style="text-align: center;">'.$win_score.'</div>
	                                <div class="col-30 no-padding" style="text-align: center;"> - </div>
	                                <div class="col-30 no-padding" style="text-align: center;">'.$lose_score.'</div>
	                            </div>
                            </div>
                            <div class="col-57" style="text-align: left;">
                                '.get_team_chinese_name($conn, $lose_team).' <img src="images/teams_small/'.$lose_team.'.png" style="width: 14px; height: 14px;">
                            </div>
                        </div>
                    </div>
    			</div>

    			<div class="row d-none d-md-block knockout_box_'.$match["game"].'" style="padding-top: 7px; padding-bottom: 7px;">
    				&nbsp;晋级：
    		';

    		if (!is_null($total_score1)) {

    			echo '
                    <img src="images/teams_small/'.$win_team.'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $win_team).'';
            }

            echo '
    			</div>
    		</div>';

    		$win_index += 1;
    		$loss_index += 1;
        }
	}
}

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

    $possibility1 = floor((($a1 + $h1 + 3) - ($d2 - $h2) + 40) * (($a1 + $h1 + 3) - ($d2 - $h2) + 40) / 12.5 * pow(1.12, ($m1 + $h1 + 3) / 10 - ($m2 - $h2) / 10));
    $possibility2 = floor((($a2 - $h2) - ($d1 + $h1 + 3) + 40) * (($a2 - $h2) - ($d1 + $h1 + 3) + 40) / 12.5 * pow(1.12, ($m2 - $h2) / 10 - ($m1 + $h1 + 3) / 10));
	
	return get_team_chinese_name($conn, $team1).'：'.$possibility1.'，'.get_team_chinese_name($conn, $team2).'：'.$possibility2;
}
?>