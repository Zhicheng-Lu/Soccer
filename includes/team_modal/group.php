    <?php
    // group
    if ($group != "-1") {
        echo '
                        <div style="margin-top: 30px;">
                            <p>
                                <a href="competition.php?tournament='.$group_tournament.'&competition='.$competition.'&stage=group&group='.$group.'" target="_blank" style="text-decoration: underline; color: black;">
                                    <b style="font-size: 16px;">小组赛（'.$group_tournament_chinese.' '.$group.'组）：</b>
                                </a>
                            </p>
                        </div>
                        <div class="row">
                            <div class="col-xl-40 col-md-60 col-sm-80 col-120">
                                <table style="width: 100%; font-size: 12px;">
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
        foreach ($teams as $team_index=>$team) {
            $temp_name = $team["team_name"];
            // determine the color based on the results
            $color = "black";
            if ($team_index == 0) {
                if ($group_tournament == "champions_league") $color = "red";
                if ($group_tournament == "union_associations") $color = "blue";
                if ($group_tournament == "winners_cup") $color = "#1A9361";
            }
            if ($team_index == 1) {
                if ($group_tournament == "champions_league") {
                    $sql = "SELECT * FROM champions_league WHERE competition=".$competition." AND round='1_16' AND team1=\"".$temp_name."\"";
                    $result = $conn->query($sql);
                    while ($result->fetch_assoc()) {
                        $color = "red";
                    }
                    if ($competition == 11) {
                        $sql = "SELECT * FROM union_associations WHERE competition=11 AND round='1_16' AND team1=\"".$temp_name."\"";
                    }
                    else {
                        $sql = "SELECT * FROM union_associations WHERE competition=".$competition." AND round='1_32' AND team1=\"".$temp_name."\"";
                    }
                    $result = $conn->query($sql);
                    while ($result->fetch_assoc()) {
                        $color = "blue";
                    }
                }
                if ($group_tournament == "union_associations") {
                    if ($competition == 11) {
                        $sql = "SELECT * FROM union_associations WHERE competition=11 AND round='1_16' AND team1=\"".$temp_name."\"";
                        $result = $conn->query($sql);
                        while ($result->fetch_assoc()) {
                            $color = "blue";
                        }
                    }
                    else {
                        $color = "blue";
                    }
                }
                if ($group_tournament == "winners_cup") $color = "#1A9361";
            }
            if ($team_index == 2) {
                if ($group_tournament == "champions_league" && $competition >= 15) $color = "#1A9361";
                if ($group_tournament == "union_associations" && $competition >= 15) $color = "#1A9361";
            }

            // if the team, then bold
            if ($temp_name == $team_name) $font_weight = "bold";
            else $font_weight = "normal";

            echo '
                                    <tr style="font-weight: '.$font_weight.'; cursor: pointer;" onclick="window.open(\'index.php?tab=team&team_name='.str_replace("'", "%27", $temp_name).'\')">
                                        <td style="color: '.$color.'">&nbsp;
                                            <img src="images/teams_small/'.$temp_name.'.png" style="width: 14px; height: 14px; margin-top: 3px;"> '.$team["chinese_name"].'
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
                            </div>
                            <div class="col-xl-79 offset-xl-1 col-120 offset-2 no-padding row">';
        
        // print all the group matches    
        foreach ($matches as $match) {
            if ($match["game"] == 1) {
                echo '
                                <div class="col-md-12 offset-md-0 col-120 offset-2 no-padding">第 '.$match["round"].' 轮：</div>';
            }

            $font_weight1 = "normal";
            $font_weight2 = "normal";
            if ($match["team1"] == $team_name ) {
                $font_weight1 = "bold";
            }
            if ($match["team2"] == $team_name) {
                $font_weight2 = "bold";
            }

            echo '
                                <div class="col-md-54 col-110 no-padding">
                                    <div class="row">
                                        <div class="col-57" style="text-align: right; font-weight: '.$font_weight1.';">
                                            <a style="text-decoration: none; color: black;" href="index.php?tab=team&team_name='.$match["team1"].'" target="_blank">
                                                <img src="images/teams_small/'.$match["team1"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $match["team1"]).'
                                            </a>
                                        </div>
                                        <div class="col-6 no-padding">
                                            <a href="index.php?tab=history&team1='.$match["team1"].'&team2='.$match["team2"].'" target="_blank" style="color: black; text-decoration: none;">
                                                <div class="row">
                                                    <div class="col-30 offset-15 no-padding" style="text-align: center; font-weight: '.$font_weight1.';">'.$match["score1"].'</div>
                                                    <div class="col-30 no-padding" style="text-align: center;"> - </div>
                                                    <div class="col-30 no-padding" style="text-align: center; font-weight: '.$font_weight2.';">'.$match["score2"].'</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-57" style="text-align: left; font-weight: '.$font_weight2.';">
                                            <a style="text-decoration: none; color: black;" href="index.php?tab=team&team_name='.$match["team2"].'" target="_blank">
                                                '.get_team_chinese_name($conn, $match["team2"]).' <img src="images/teams_small/'.$match["team2"].'.png" style="width: 14px; height: 14px;">
                                            </a>
                                        </div>
                                    </div>
                                </div>';
        }

        echo '
                            </div>
                        </div>';
    }
    ?>