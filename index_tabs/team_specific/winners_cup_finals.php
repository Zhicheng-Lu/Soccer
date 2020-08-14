	<?php
	// winners cup finals
    $finals = FALSE;
    $finals_matches = array();
    $finals_teams = array();
    foreach ($knockout_matches as $key => $match) {
        if ($match["round"] == "finals") {
            $finals = TRUE;
        }
    }
    if ($finals) {
        $sql = 'SELECT * FROM winners_cup WHERE competition='.$competition.' AND round="finals"';
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            array_push($finals_matches, array("game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"]));
        }

        // get the unique team name in the finals
        foreach ($finals_matches as $match) {
            if (!isset($finals_teams[$match["team1"]])) {
                $finals_teams[$match["team1"]] = array();
            }
        }
        $finals_teams = rank($finals_teams, $finals_matches);
        
        echo '
                        <div class="row">
                            <div class="col-xl-12 col-md-18 col-sm-23 col-120"><a style="text-decoration: underline; color: black;" href="competition.php?tournament=winners_cup&competition='.$competition.'&stage=knockout&round=finals" target="_blank">联合决赛：</a></div>
                            <div class="col-xl-50 col-md-70 col-sm-97 col-120" style="font-size: 12px;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <th style="width: 43%;">球队</th>
                                            <th style="width: 7%;">胜</th>
                                            <th style="width: 7%;">平</th>
                                            <th style="width: 7%;">负</th>
                                            <th style="width: 9%;">进球</th>
                                            <th style="width: 9%;">失球</th>
                                            <th style="width: 9%;">净胜</th>
                                            <th style="width: 9%;">积分</th>
                                        </tr>';

        $chinese_name = array();
        foreach ($finals_teams as $team_index=>$team) {
            $temp_name = $team["team_name"];
            $sql = 'SELECT team_chinese_name FROM teams WHERE team_name="'.$temp_name.'"';
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $team_chinese_name = $row["team_chinese_name"];
                $chinese_name[$temp_name] = $team_chinese_name;
            }

            if ($temp_name == $team_name) {
                $font_weight = "bold";
                if ($team_index == 0) $final_rank = "冠军";
                else if ($team_index == 1) $final_rank = "亚军";
                else $final_rank = "季军";
            }
            else $font_weight = "normal";

            echo '
                                    <tr style="font-weight: '.$font_weight.';">
                                        <td style="color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $temp_name).'\', '.$competition.')">&nbsp;
                                            <img src="images/teams_small/'.$temp_name.'.png" style="width: 14px; height: 14px; margin-top: 3px;"> '.$team_chinese_name.'
                                        </td>
                                        <td style="text-align: center;">'.$team["win"].'</td>
                                        <td style="text-align: center;">'.$team["draw"].'</td>
                                        <td style="text-align: center;">'.$team["lose"].'</td>
                                        <td style="text-align: center;">'.$team["score"].'</td>
                                        <td style="text-align: center;">'.$team["concede"].'</td>
                                        <td style="text-align: center;">'.$team["difference"].'</td>
                                        <td style="text-align: center;">'.$team["point"].'</td>
                                    </tr>';
        }

        echo '
                                    </table>
                                </a>
                            </div>
                            <div class="col-xl-57 offset-xl-1 col-md-85 col-120 offset-4 no-padding">';
    }

    foreach ($finals_matches as $index => $match) {
         echo '
                                <div class="row">
                                    <div class="col-sm-25 col-120">第 '.($match["game"] + 1).' 轮：</div>';

        $font_weight1 = "normal";
        $font_weight2 = "normal";
        if ($match["team1"] == $team_name ) {
            $font_weight1 = "bold";
        }
        if ($match["team2"] == $team_name) {
            $font_weight2 = "bold";
        }

        echo '
                                    <div class="col-sm-95 col-120 no-padding">
                                        <div class="row">
                                            <div class="col-56" style="text-align: right; font-weight: '.$font_weight1.';">
                                                <a style="text-decoration: none; color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $match["team1"]).'\', '.$competition.')"><img src="images/teams_small/'.$match["team1"].'.png" style="width: 14px; height: 14px;"> '.$chinese_name[$match["team1"]].'</a>
                                            </div>
                                            <div class="col-8 no-padding">
                                                <a href="index.php?tab=history&team1='.$match["team1"].'&team2='.$match["team2"].'" target="_blank" style="color: black; text-decoration: none;">
                                                    <div class="row">
                                                        <div class="col-30 offset-15 no-padding" style="text-align: center; font-weight: '.$font_weight1.';">'.$match["score1"].'</div>
                                                        <div class="col-30 no-padding" style="text-align: center;"> - </div>
                                                        <div class="col-30 no-padding" style="text-align: center; font-weight: '.$font_weight2.';">'.$match["score2"].'</div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-56" style="font-weight: '.$font_weight2.';">
                                                <a style="text-decoration: none; color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $match["team2"]).'\', '.$competition.')">'.$chinese_name[$match["team2"]].' <img src="images/teams_small/'.$match["team2"].'.png" style="width: 14px; height: 14px;"></a>
                                            </div>
                                        </div>
                                    </div>';

        echo '
                                </div>';
    }
    if ($finals) {
        echo '
                            </div>
                        </div>';
    }
	?>