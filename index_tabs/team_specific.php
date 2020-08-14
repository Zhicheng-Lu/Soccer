	</header><!-- .site-header -->

	<?php
    // find the rank of a specific team
    function find_index($teams, $team_name) {
        foreach ($teams as $team_index => $team) {
            if ($team["temp_name"] == $team_name) {
                return $team_index;
            }
        }
        return -1;
    }

    include("includes/rank.php");

    $team_page = 1;
	$team_name = $_GET["team_name"];
	$sql = 'SELECT * FROM teams AS T LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE T.team_name="'.$team_name.'"';
	$result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
    	$team_name_chinese = $row["team_chinese_name"];
    	$nationality = $row["country_name"];
    	$nationality_chinese = $row["country_chinese_name"];
    	$continent = $row["country_continent"];
    }
    if ($continent == "Europe") $continent_chinese = "欧洲";
    if ($continent == "Asia") $continent_chinese = "亚洲";
    if ($continent == "South America") $continent_chinese = "南美洲";
    if ($continent == "North America") $continent_chinese = "北美洲";
    if ($continent == "Africa") $continent_chinese = "非洲";
    if ($continent == "Oceania") $continent_chinese = "大洋洲";
    if ($continent == "Antarctica") $continent_chinese = "南极洲";

    // rank by different tournaments, and all together
    usort($teams, "cmp2");
    $rank1 = find_index($teams, $team_name) + 1;
    usort($teams, "cmp3");
    $rank2 = find_index($teams, $team_name) + 1;
    usort($teams, "cmp4");
    $rank3 = find_index($teams, $team_name) + 1;
    usort($teams, "cmp5");
    $rank = find_index($teams, $team_name) + 1;
    $team = $teams[$rank - 1];
	?>

	<br><br><br><br><br>
    <!-- team header -->
    <div class="section">
        <div class="container">
        	<div class="row">
                <!-- logo and basic information of the team -->
        		<div class="col-lg-27 col-md-60 d-none d-md-block">
                    <img src="images/teams/<?php echo $team_name; ?>.png" style="width: 200px; height: 200px;">
                </div>
                <div class="col-lg-28 col-md-60 col-120" style="margin-bottom: 10px;">
                    <p style="font-size: 24px;"><b><?php echo $team_name_chinese; ?></b> <img class="d-md-none" src="images/teams_small/<?php echo $team_name; ?>.png" style="width: 24px; height: 24px;"></p>
                    <p style="font-size: 14px;"><b>英文名称：</b><?php echo $team_name; ?></p>
                    <p style="font-size: 14px;"><b>国籍：</b><?php echo $nationality_chinese; ?> <img src="images/countries_small/<?php echo $nationality; ?>.png" style="width: 14px; height: 14px;"></p>
                    <p style="font-size: 14px;"><b>大洲：</b><?php echo $continent_chinese; ?> <img src="images/continents_small/<?php echo $continent; ?>.png" style="width: 14px; height: 14px;"></p>
                    <p style="font-size: 14px;"><b>类别：</b><?php echo (($nationality == $team_name)? "国家队" : "俱乐部"); ?></p>
                </div>
                <!-- tournament performance of the team -->
                <div class="col-lg-65 col-120">
                	<table style="width: 100%; text-align: center;">
                		<tr>
                			<th style="width: 20%;"></th>
                			<th style="width: 20%;">冠军杯</th>
                			<th style="width: 20%;">联盟杯</th>
                			<th style="width: 20%;">优胜者杯</th>
                			<th style="width: 20%;">综合</th>
                		</tr>
                        <tr>
                            <th>最好成绩</th>
                            <?php
                            if ($team["champions_league_group"] == 0) $best_performance = "-";
                            else if ($team["champions_league_1_16"] == 0) $best_performance = "小组赛";
                            else if ($team["champions_league_1_8"] == 0) $best_performance = "32 强";
                            else if ($team["champions_league_1_4"] == 0) $best_performance = "16 强";
                            else if ($team["champions_league_semi_final"] == 0) $best_performance = "8 强";
                            else if ($team["champions_league_final"] == 0) $best_performance = "4 强";
                            else if ($team["champions_league_champion"] == 0) $best_performance = "亚军";
                            else $best_performance = "冠军";
                            echo '
                            <td>'.$best_performance.'</td>';

                            if ($team["union_associations_champion"] > 0) $best_performance = "冠军";
                            else if ($team["union_associations_final"] > 0) $best_performance = "亚军";
                            else if ($team["union_associations_semi_final"] > 0) $best_performance = "4 强";
                            else if ($team["union_associations_1_4"] > 0) $best_performance = "8 强";
                            else if ($team["union_associations_1_8"] > 0) $best_performance = "16 强";
                            else if ($team["union_associations_1_16"] > 0) $best_performance = "32 强";
                            else if ($team["union_associations_1_32"] > 0) $best_performance = "64 强";
                            else if ($team["union_associations_group"] > 0) $best_performance = "小组赛";
                            else $best_performance = "-";
                            echo '
                            <td>'.$best_performance.'</td>';

                            if ($team["winners_cup_champion"] > 0) $best_performance = "冠军";
                            else if ($team["winners_cup_finals"] > 0) $best_performance = "联合决赛";
                            else if ($team["winners_cup_6"] > 0) $best_performance = "6 强";
                            else if ($team["winners_cup_12"] > 0) $best_performance = "12 强";
                            else if ($team["winners_cup_24"] > 0) $best_performance = "24 强";
                            else if ($team["winners_cup_48"] > 0) $best_performance = "48 强";
                            else if ($team["winners_cup_70"] > 0) $best_performance = "70 强";
                            else if ($team["winners_cup_122"] > 0) $best_performance = "122 强";
                            else if ($team["winners_cup_group"] > 0) $best_performance = "小组赛";
                            else $best_performance = "-";
                            echo '
                            <td>'.$best_performance.'</td>';
                            ?>
                            <td></td>
                        </tr>
                        <tr>
                            <th>积分</th>
                            <td><?php echo $team["champions_league_points"]; ?></td>
                            <td><?php echo $team["union_associations_points"]; ?></td>
                            <td><?php echo $team["winners_cup_points"]; ?></td>
                            <td><?php $total_points = $team["champions_league_points"] + $team["union_associations_points"] + $team["winners_cup_points"]; echo $total_points; ?></td>
                        </tr>
                        <tr>
                            <th>排名</th>
                            <?php
                            if ($team["champions_league_points"] == 0) {
                                echo '
                            <td>-</td>';
                            }
                            else {
                                echo '
                            <td><a style="color: blue; text-decoration: underline;" href="index.php?tab=rank&tournament=champions_league#'.$team_name.'">'.$rank1.'</a></td>';
                            }
                            if ($team["union_associations_points"] == 0) {
                                echo '
                            <td>-</td>';
                            }
                            else {
                                echo '
                            <td><a style="color: blue; text-decoration: underline;" href="index.php?tab=rank&tournament=union_associations#'.$team_name.'">'.$rank2.'</a></td>';
                            }
                            if ($team["winners_cup_points"] == 0) {
                                echo '
                            <td>-</td>';
                            }
                            else {
                                echo '
                            <td><a style="color: blue; text-decoration: underline;" href="index.php?tab=rank&tournament=winners_cup#'.$team_name.'">'.$rank3.'</a></td>';
                            }
                            if ($team["champions_league_points"] + $team["union_associations_points"] + $team["winners_cup_points"]== 0) {
                                echo '
                            <td>-</td>';
                            }
                            else {
                                echo '
                            <td><a style="color: blue; text-decoration: underline;" href="index.php?tab=rank#'.$team_name.'">'.$rank.'</a></td>';
                            }
                            ?>
                        </tr>
                	</table>
                </div>
        	</div>
        </div>
    </div>

    <!-- performance of each competition -->
    <div class="container">
        <div class="row">
            <div class="col-lg-108 offset-lg-6 col-120">
                <div class="row display_box" style="font-weight: bold; text-align: center;">
                    <div class="col-sm-24 col-30"></div>
                    <div class="col-sm-24 col-30">冠军杯</div>
                    <div class="col-sm-24 col-30">联盟杯</div>
                    <div class="col-sm-24 col-30">优胜者杯</div>
                    <div class="col-24 d-none d-sm-block">
                        <button onclick="open_all_competitions()" style="height: 40px; width: 120px; background-color: white; color: black; border-radius: 5px;">全部展开</button>
                    </div>
                </div>
                <?php
                $sql1 = 'SELECT DISTINCT competition FROM champions_league';
                $result1 = $conn->query($sql1);
                while ($row1 = $result1->fetch_assoc()) {
                    $competition = $row1["competition"];
                    $results = get_results($conn, $competition, $team_name);
                    echo '
                <div onclick="open_competition('.$competition.')" class="row display_box" style="height: 33px; border-radius: 5px; background-color: #EEEEEE; margin-top: 20px; margin-bottom: 20px; text-align: center; cursor: pointer;">
                    <div class="col-sm-24 col-30" style="text-align: left;"><b>第 '.$competition.' 届</b></div>
                    <div class="col-sm-24 col-30">'.$results[0].'</div>
                    <div class="col-sm-24 col-30">'.$results[1].'</div>
                    <div class="col-sm-24 col-30">'.$results[2].'</div>
                    <div class="col-24 d-none d-sm-block"></div>
                </div>
                <div id="competition'.$competition.'" style="display: none;">';
                    $count = 0;
                    $sql2 = 'SELECT count(*) AS count FROM champions_league WHERE competition="'.$competition.'" AND team1="'.$team_name.'"';
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        $count += $row2["count"];
                    }
                    $sql2 = 'SELECT count(*) AS count FROM union_associations WHERE competition="'.$competition.'" AND team1="'.$team_name.'"';
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        $count += $row2["count"];
                    }
                    $sql2 = 'SELECT count(*) AS count FROM winners_cup WHERE competition="'.$competition.'" AND team1="'.$team_name.'"';
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        $count += $row2["count"];
                    }
                    if ($count > 0) {
                        include("index_tabs/team_specific/body.php");
                    }
                    echo '
                </div>';
                }
                ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function open_competition(competition) {
            var competition = document.getElementById("competition" + competition);
            if (competition.style.display == "none") {
                competition.style.display = "block";
            }
            else {
                competition.style.display = "none";
            }
        }
        var opened = false;
        function open_all_competitions() {
            for (var i = 1; i <= <?php echo $competition; ?>; i++) {
                if (opened) {
                    document.getElementById("competition" + i).style.display = "none";
                }
                else {
                    document.getElementById("competition" + i).style.display = "block";
                }
            }
            if (opened) {
                opened = false;
                document.getElementById("open_all_competitions").innerHTML = "展开全部";
            }
            else {
                opened = true;
                document.getElementById("open_all_competitions").innerHTML = "关闭全部";
            }
        }
    </script>

    <style type="text/css">
        @media (min-width: 576px) {
            .display_box {
                font-size: 18px;
            }
        }
        @media (max-width: 576px) {
            .display_box {
                font-size: 14px;
            }
        }
    </style>
    

	<?php
    include("includes/team_modal/modal.php");
	?>