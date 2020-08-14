    <?php
    // knockouts
    if ($knockout_tournament != "") {
        echo '
                        <div style="margin-top: 30px;"><p><b style="font-size: 16px;">淘汰赛（'.$knockout_tournament_chinese.'）：</b></p></div>';
    }
    // knockouts
    foreach ($knockout_matches as $index => $match) {
        // 优胜者杯联合决赛
        if ($match["round"] == "finals") continue;

        $team1 = $match["team1"];
        $team2 = $match["team2"];

        $sql = 'SELECT team_chinese_name FROM teams WHERE team_name="'.$team1.'"';
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $team_chinese_name1 = $row["team_chinese_name"];
        }
        $sql = 'SELECT team_chinese_name FROM teams WHERE team_name="'.$team2.'"';
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $team_chinese_name2 = $row["team_chinese_name"];
        }

        $round = $match["round"];
        if ($round == "1_32") $round = "1/32 决赛";
        else if ($round == "1_16") $round = "1/16 决赛";
        else if ($round == "1_8") $round = "1/8 决赛";
        else if ($round == "1_4") $round = "1/4 决赛";
        else if ($round == "semi_final") $round = "半决赛";
        else if ($round == "final") $round = "决赛";
        else $round = $round." 强";

        // first leg
        if ($match["game"] % 2 == 0) {
            $score1_1 = $match["score1"];
            $score1_2 = $match["score2"];
            echo '
                        <div class="row">
                            <div class="col-xl-15 col-120"><a href="competition.php?tournament='.$knockout_tournament.'&competition='.$competition.'&stage=knockout&round='.$match["round"].'" target="_blank" style="color: black; text-decoration: underline;">'.$round.'：</a></div>';
        }

        $font_weight1 = "normal";
        $font_weight2 = "normal";
        if ($team1 == $team_name ) {
            $font_weight1 = "bold";
        }
        if ($team2 == $team_name) {
            $font_weight2 = "bold";
        } 

        echo '
                            <div class="col-xl-36 offset-xl-0 col-lg-44 col-md-52 col-sm-90 col-120 offset-2 no-padding">
                                <div class="row">
                                    <div class="col-56" style="text-align: right; font-weight: '.$font_weight1.';">
                                        <a style="text-decoration: none; color: black;" href="index.php?tab=team&team_name='.$team1.'" target="_blank">
                                            <img src="images/teams_small/'.$team1.'.png" style="width: 14px; height: 14px;"> '.$team_chinese_name1.'
                                        </a>
                                    </div>
                                    <div class="col-8 no-padding">
                                        <a href="index.php?tab=history&team1='.$team1.'&team2='.$team2.'" target="_blank" style="color: black; text-decoration: none;">
                                            <div class="row">
                                                <div class="col-30 offset-15 no-padding" style="text-align: center; font-weight: '.$font_weight1.';">'.$match["score1"].'</div>
                                                <div class="col-30 no-padding" style="text-align: center;"> - </div>
                                                <div class="col-30 no-padding" style="text-align: center; font-weight: '.$font_weight2.';">'.$match["score2"].'</div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-56" style="text-align: left; font-weight: '.$font_weight2.';">
                                        <a style="text-decoration: none; color: black;" href="index.php?tab=team&team_name='.$team2.'" target="_blank">
                                            '.$team_chinese_name2.' <img src="images/teams_small/'.$team2.'.png" style="width: 14px; height: 14px;">
                                        </a>
                                    </div>
                                </div>
                            </div>';

        // second leg
        if ($match["game"] % 2 == 1) {
            $score2_1 = $match["score1"];
            $score2_2 = $match["score2"];

            if ($match["team1"] == $team_name) {
                $score = $score2_1 + $score1_2 + $match["extra_score1"];
                $away_score = $score1_2;
                $penalty_score = $match["penalty_score1"];
                $concede = $score2_2 + $score1_1 + $match["extra_score2"];
                $away_concede = $score2_2 + $match["extra_score2"];
                $penalty_concede = $match["penalty_score2"];
            }
            else {
                $score = $score2_2 + $score1_1 + $match["extra_score2"];
                $away_score = $score2_2 + $match["extra_score2"];
                $penalty_score = $match["penalty_score2"];
                $concede = $score2_1 + $score1_2 + $match["extra_score1"];
                $away_concede = $score1_2;
                $penalty_concede = $match["penalty_score1"];
            }

            if ($score > $concede || ($score == $concede && $away_score > $away_concede) || ($score == $concede && $away_score == $away_concede && $penalty_score > $penalty_concede)) {
                $knockout_result = '总比分 <b>'.$score.'</b> - '.$concede.' 晋级';
            }
            else {
                $knockout_result = '总比分 <b>'.$score.'</b> - '.$concede.' 出局';
            }

            echo '
                            <div class="col-md-12 col-sm-20 col-120">';

            if ($match["extra_score1"] != '') {
                echo '
                                ('.$match["extra_score1"].' - '.$match["extra_score2"].')';
            }

            if ($match["penalty_score1"] != '') {
                echo '
                                <br>('.$match["penalty_score1"].' - '.$match["penalty_score2"].')';
            }

            echo '
                            </div>';

            echo '
                            <div class="col-xl-17 col-lg-16 offset-lg-0 col-120 offset-4 no-padding" style="height: 65px;">
                                '.$knockout_result.'

                            </div>
                        </div>';
        }

        // final (which is one leg)
        if ($match["round"] == "final") {
            if ($match["team1"] == $team_name) {
                $score = $match["score1"] + $match["extra_score1"];
                $concede = $match["score2"] + $match["extra_score2"];
                $penalty_score = $match["penalty_score1"];
                $penalty_concede = $match["penalty_score2"];
            }
            else {
                $score = $match["score2"] + $match["extra_score2"];
                $concede = $match["score1"] + $match["extra_score1"];
                $penalty_score = $match["penalty_score2"];
                $penalty_concede = $match["penalty_score1"];
            }

            if ($score > $concede || ($score == $concede && $penalty_score > $penalty_concede)) {
                $knockout_result = '<b>'.$score.'</b> - '.$concede.' 夺得冠军';
                $is_champion = TRUE;
            }
            else {
                $knockout_result = '<b>'.$score.'</b> - '.$concede.' 夺得亚军';
                $is_champion = FALSE;
            }

            echo '
                            <div class="col-xl-36 offset-xl-0 col-lg-44 col-md-52 col-sm-90 col-120 offset-2 no-padding">
                            </div>
                            <div class="col-md-12 col-sm-20 col-120">';

            if ($match["extra_score1"] != '') {
                echo '
                                ('.$match["extra_score1"].' - '.$match["extra_score2"].')';
            }

            if ($match["penalty_score1"] != '') {
                echo '
                                <br>('.$match["penalty_score1"].' - '.$match["penalty_score2"].')';
            }

            echo '
                            </div>';

            echo '
                            <div class="col-xl-17 col-lg-16 offset-lg-0 col-120 offset-4 no-padding" style="height: 65px;">
                                '.$knockout_result.'
                            </div>
                        </div>';
        }
    }
    ?>