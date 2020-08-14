						<?php
        				// start a new group
        				if ($match_index == 0) $prev_match = array("tournament"=>"", "competition"=>"", "group"=>"");
        				else $prev_match = $matches[$match_index - 1];
        				if ($tournament != $prev_match["tournament"] || $competition != $prev_match["competition"] || $group != $prev_match["group"]) {
        					echo '
        		<div class="col-lg-60 col-120 border-div" style="margin-top: 10px; padding-bottom: 7px;">
        			<p style="font-size: 16px; font-weight: bold;">
        				<a href="competition.php?tournament='.$tournament.'&competition='.$competition.'&stage=group&group='.$group.'" target="_blank" style="text-decoration: underline; color: black;">
        					第 '.$competition.' 届 '.$tournament_chinese.' '.$group.' 组
        				</a>
        			</p>
        			<div class="row">
        				<div class="col-xxl-56 col-xl-80 col-lg-100 col-md-56 col-120">
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

	                        $group_matches = array();
			                $sql = "SELECT * FROM ".$match["tournament"]." WHERE competition=".$match["competition"]." AND group_index='".$match["group"]."'";
			                $result = $conn->query($sql);
			                while ($row = $result->fetch_assoc()) {
			                    array_push($group_matches, array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"]));
			                }

			                // get the unique team name in the group
			                $teams = array();
			                foreach ($group_matches as $group_match) {
			                    if (!isset($teams[$group_match["team1"]])) {
			                        $teams[$group_match["team1"]] = array();
			                    }
			                    if (!isset($teams[$group_match["team2"]])) {
			                        $teams[$group_match["team2"]] = array();
			                    }
			                }
			                $teams = rank($teams, $group_matches);

			                $chinese_name = array();
			                foreach ($teams as $team_index => $team) {
			                    $group_tournament = $match["tournament"];

			                    $team_name = $team["team_name"];
			                    $sql = 'SELECT team_chinese_name FROM teams WHERE team_name="'.$team_name.'"';
			                    $result = $conn->query($sql);
			                    while ($row = $result->fetch_assoc()) {
			                        $team_chinese_name = $row["team_chinese_name"];
			                        $chinese_name[$team_name] = $team_chinese_name;
			                    }

			                    $color = "black";
			                    if ($team_index == 0) {
			                        if ($group_tournament == "champions_league") $color = "red";
			                        if ($group_tournament == "union_associations") $color = "blue";
			                        if ($group_tournament == "winners_cup") $color = "#1A9361";
			                    }
			                    if ($team_index == 1) {
			                        if ($match["tournament"] == "champions_league") {
			                            $sql = "SELECT * FROM champions_league WHERE competition=".$competition." AND round='1_16' AND team1=\"".$team_name."\"";
			                            $result = $conn->query($sql);
			                            while ($result->fetch_assoc()) {
			                                $color = "red";
			                            }
			                            if ($competition == 11) {
			                                $sql = "SELECT * FROM union_associations WHERE competition=11 AND round='1_16' AND team1=\"".$team_name."\"";
			                            }
			                            else {
			                                $sql = "SELECT * FROM union_associations WHERE competition=".$competition." AND round='1_32' AND team1=\"".$team_name."\"";
			                            }
			                            $result = $conn->query($sql);
			                            while ($result->fetch_assoc()) {
			                                $color = "blue";
			                            }
			                        }
			                        if ($group_tournament == "union_associations") {
			                            if ($competition == 11) {
			                                $sql = "SELECT * FROM union_associations WHERE competition=11 AND round='1_16' AND team1=\"".$team_name."\"";
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

			                    $font_weight = "normal";
			                    if (isset($_GET["team1"])) {
			                        if ($team_name == $_GET["team1"]) $font_weight = "bold";
			                    }
			                    else if (isset($_GET["country1"])) {
			                        if (get_country($conn, $team_name) == $_GET["country1"]) $font_weight = "bold";
			                    }
			                    else if (isset($_GET["continent1"])) {
			                        if (get_continent($conn, $team_name) == $_GET["continent1"]) $font_weight = "bold";
			                    }
			                    else {
			                        if ($team_name == $team1) $font_weight = "bold";
			                    }

			                    if (isset($_GET["team2"])) {
			                        if ($team_name == $_GET["team2"]) $font_weight = "bold";
			                    }
			                    else if (isset($_GET["country2"])) {
			                        if (get_country($conn, $team_name) == $_GET["country2"]) $font_weight = "bold";
			                    }
			                    else if (isset($_GET["continent2"])) {
			                        if (get_continent($conn, $team_name) == $_GET["continent2"]) $font_weight = "bold";
			                    }
			                    else {
			                        if ($team_name == $team2) $font_weight = "bold";
			                    }

			                    echo '
                                <tr style="font-weight: '.$font_weight.';">
                                    <td style="color: '.$color.'; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team_name).'\', '.$competition.')">&nbsp;<img src="images/teams/'.$team_name.'.png" style="width: 20px; height: 20px; margin-top: 3px;"> '.$team_chinese_name.'</td>
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
	                    <div class="col-xxl-64 col-lg-120 col-md-64 col-120">
	                    	<div class="row">';
        				}

        				// all matches
        				array_push($temp_matches, $match);
        				

        				// close a group
        				if ($match_index == sizeof($matches) - 1) $next_match = array("tournament"=>"", "competition"=>"", "group"=>"");
        				else $next_match = $matches[$match_index + 1];
        				if ($tournament != $next_match["tournament"] || $competition != $next_match["competition"] || $group != $next_match["group"]) {
        					$size = sizeof($temp_matches);
        					foreach ($temp_matches as $match) {
        						if ($size == 2) $class = " line1";
        						if ($size == 4) $class = " line2";
        						if ($size == 6) $class = "";
        						echo '
        						<div class="col-sm-20 col-120'.$class.'" style="padding-right: 0px;">第 '.$match["round"].' 轮</div>
        						<div class="col-sm-48 col-57'.$class.'" style="text-align: right; padding-left: 0px;">
                                    <a style="text-decoration: none; color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $match["team1"]).'\', '.$competition.')">
                                        <img src="images/teams_small/'.$match["team1"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $match["team1"]).'
                                    </a>
                                </div>
                                <div class="col-sm-4 col-6 no-padding'.$class.'">
                                    <div class="row">
                                        <div class="col-30 offset-15 no-padding" style="text-align: center;">'.$match["score1"].'</div>
                                        <div class="col-30 no-padding" style="text-align: center;"> - </div>
                                        <div class="col-30 no-padding" style="text-align: center;">'.$match["score2"].'</div>
                                    </div>
                                </div>
                                <div class="col-sm-48 col-57'.$class.'" style="text-align: left; padding-right: 0px;">
                                    <a style="text-decoration: none; color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $match["team2"]).'\', '.$competition.')">
                                        '.get_team_chinese_name($conn, $match["team2"]).' <img src="images/teams_small/'.$match["team2"].'.png" style="width: 14px; height: 14px;">
                                    </a>
                                </div>';
        					}
        					$temp_matches = array();

        					echo '
        					</div>
        				</div>
        			</div>
        		</div>';
        				}
						?>