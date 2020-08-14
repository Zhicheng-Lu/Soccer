	</header><!-- .site-header -->

    <style type="text/css">
        @media screen and (max-width: 576px) {
            #hottest_table {
                font-size: 12px;
            }
        }
    </style>

    <br><br><br><br><br>
    <form action="index.php" target="_blank" method="get">
        <input type="hidden" name="tab" value="history">
        <div class="section">
            <div class="container">
                <!-- result display -->
            	<div class="row">
                    <div class="col-lg-15 offset-lg-40 col-sm-25 offset-sm-30 col-35 offset-20">
                        <img id="selected_1" src="images/ball.png" style="width: 100%;">
                    </div>
                    <div class="col-10"><p style="margin: 0px; position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);">VS</p></div>
                    <div class="col-lg-15 col-sm-25 col-35">
                        <img id="selected_2" src="images/ball.png" style="width: 100%;">
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <!-- drop down for left side -->
                    <div class="col-45 offset-10">
                        <div class="row">
                            <div class="col-xxl-40 col-120" style="margin-top: 10px;">
                                <select id="select_continent_1" name="continent1" style="width: 100%;" onchange="select_continent(1)">
                                    <option value="" disabled selected> -- 选择大洲 -- </option>
                                    <option value=""></option>
                                    <option value="Europe">欧洲</option>
                                    <option value="South America">南美洲</option>
                                    <option value="North America">北美洲</option>
                                    <option value="Asia">亚洲</option>
                                    <option value="Africa">非洲</option>
                                    <option value="Oceania">大洋洲</option>
                                    <option value="Antarctica">南极洲</option>
                                </select>
                            </div>
                            <div class="col-xxl-40 col-120" style="margin-top: 10px;">
                                <select id="select_country_1" name="country1" style="width: 100%;" onchange="select_country(1)">
                                    <option value="" disabled selected> -- 选择国家 -- </option>
                                    <option value=""></option>
                                    <?php
                                    $sql = "SELECT * FROM countries";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                    <option value="'.$row["country_name"].'">'.$row["country_chinese_name"].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-xxl-40 col-120" style="margin-top: 10px;">
                                <select id="select_team_1" name="team1" style="width: 100%;" onchange="select_team(1)">
                                    <option value="" disabled selected> -- 选择球队 -- </option>
                                    <option value=""></option>
                                    <?php
                                    $sql = "SELECT * FROM teams";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                    <option value="'.$row["team_name"].'">'.$row["team_chinese_name"].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- drop down for right side -->
                    <div class="col-45 offset-10">
                        <div class="row">
                            <div class="col-xxl-40 col-120" style="margin-top: 10px;">
                                <select id="select_continent_2" name="continent2" style="width: 100%;" onchange="select_continent(2)">
                                    <option value="" disabled selected> -- 选择大洲 -- </option>
                                    <option value=""></option>
                                    <option value="Europe">欧洲</option>
                                    <option value="South America">南美洲</option>
                                    <option value="North America">北美洲</option>
                                    <option value="Asia">亚洲</option>
                                    <option value="Africa">非洲</option>
                                    <option value="Oceania">大洋洲</option>
                                    <option value="Antarctica">南极洲</option>
                                </select>
                            </div>
                            <div class="col-xxl-40 col-120" style="margin-top: 10px;">
                                <select id="select_country_2" name="country2" style="width: 100%;" onchange="select_country(2)">
                                    <option value="" disabled selected> -- 选择国家 -- </option>
                                    <option value=""></option>
                                    <?php
                                    $sql = "SELECT * FROM countries";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                    <option value="'.$row["country_name"].'">'.$row["country_chinese_name"].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-xxl-40 col-120" style="margin-top: 10px;">
                                <select id="select_team_2" name="team2" style="width: 100%;" onchange="select_team(2)">
                                    <option value="" disabled selected> -- 选择球队 -- </option>
                                    <option value=""></option>
                                    <?php
                                    $sql = "SELECT * FROM teams";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '
                                    <option value="'.$row["team_name"].'">'.$row["team_chinese_name"].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- search button -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-40 offset-lg-40 col-sm-60 offset-sm-30 col-80 offset-20">
                        <button id="search_button" style="height: 50px; width: 100%; background-color: white; color: black; border-radius: 5px; font-size: 16px; visibility: hidden;">搜索</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <?php
    function cmp100($pair1, $pair2) {
        return $pair2["times"] - $pair1["times"];
    }
    function get_chinese_name($conn, $team_name) {
        $sql = 'SELECT team_chinese_name FROM teams WHERE team_name="'.$team_name.'"';
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            return $row["team_chinese_name"];
        }
    }

    $competitions = array("champions_league", "union_associations", "winners_cup");
    $all_pairs = array();
    foreach ($competitions as $key => $competition) {
        $sql = 'SELECT team1, team2 FROM '.$competition.' WHERE round<>"champion" AND round<>"club_leveldown_first" AND round<>"club_leveldown_second" AND round<>"club_qualifications_first" AND round<>"club_qualifications_second" AND round<>"nation_qualifications_first"';
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            if (strcmp($row["team1"], $row["team2"]) < 0) {
                $combined_name = $row["team1"].'_'.$row["team2"];
            }
            else {
                $combined_name = $row["team2"].'_'.$row["team1"];
            }

            if (!isset($all_pairs[$combined_name])) {
                $all_pairs[$combined_name] = array("times"=>1, "team1"=>$row["team1"], "team2"=>$row["team2"]);
            }
            else {
                $all_pairs[$combined_name]["times"]++;
            }
        }
    }
    usort($all_pairs, "cmp100");
    ?>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-50 offset-lg-35 col-sm-100 offset-sm-10 col-110 offset-5">
                    <table style="width: 100%;" id="hottest_table">
                        <tr>
                            <th style="width: 40%">球队1</th>
                            <th style="width: 40%">球队2</th>
                            <th style="width: 20%">交锋次数</th>
                        </tr>
                        <?php
                        $times = 0;
                        for ($i = 0; $i < sizeof($all_pairs); $i++) {
                            $pair = $all_pairs[$i];
                            if ($i > 14) {
                                if ($all_pairs[$i]["times"] != $times) {
                                    break;
                                }
                            }
                            $times = $pair["times"];
                            echo '
                        <tr style="cursor: pointer;" onclick="window.open(\'index.php?tab=history&team1='.str_replace("'", "\'", $pair["team1"]).'&team2='.str_replace("'", "\'", $pair["team2"]).'\')">
                            <td>&nbsp;<img src="images/teams_small/'.$pair["team1"].'.png" style="width: 16px; height: 16px;">&nbsp;'.get_chinese_name($conn, $pair["team1"]).'</td>
                            <td>&nbsp;<img src="images/teams_small/'.$pair["team2"].'.png" style="width: 16px; height: 16px;">&nbsp;'.get_chinese_name($conn, $pair["team2"]).'</td>
                            <td style="text-align: center;">'.$times.'</td>
                        </tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // determine to show or hide the search button
        var selected1 = false;
        var selected2 = false;
        function button_display(select1, select2) {
            if (select1 == 1) selected1 = true;
            if (select1 == -1) selected1 = false;
            if (select2 == 1) selected2 = true;
            if (select2 == -1) selected2 = false;
            if (selected1 && selected2) {
                document.getElementById("search_button").style.visibility = "visible";
            }
            else {
                document.getElementById("search_button").style.visibility = "hidden";
            }
        }

        // information of continent/country/team
        var continents = {"Europe": "欧洲", "Asia": "亚洲", "South America": "南美洲", "North America": "北美洲", "Africa": "非洲", "Antarctica": "南极洲", "Oceania": "大洋洲"};
        var countries = [];
        <?php
        $sql = "SELECT * FROM countries";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
        countries.push({name: "'.$row["country_name"].'", chinese_name: "'.$row["country_chinese_name"].'", continent: "'.$row["country_continent"].'"});';
        }
        ?>
        var teams = [];
        <?php
        $sql = "SELECT * FROM teams LEFT JOIN countries on teams.team_nationality=countries.country_name";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
        teams.push({name: "'.$row["team_name"].'", chinese_name: "'.$row["team_chinese_name"].'", nationality: "'.$row["team_nationality"].'", continent: "'.$row["country_continent"].'"});';
        }
        ?>

        function select_continent(index) {
            var value = document.getElementById("select_continent_" + index).value;
            if (value == "") document.getElementById("select_continent_" + index).value = "";
            document.getElementById("select_country_" + index).value = "";
            document.getElementById("select_team_" + index).value = "";

            // filter options for country and team
            var country_drop_down = document.getElementById("select_country_" + index);
            for (var i = country_drop_down.length - 1; i >= 2; i--) {
                country_drop_down.remove(i);
            }
            for (var i = 0; i < countries.length; i++) {
                country = countries[i];
                if (country["continent"] == value || value == "") {
                    var option = document.createElement("option");
                    option.text = country["chinese_name"];
                    option.value = country["name"];
                    country_drop_down.add(option);
                }
            }

            var team_drop_down = document.getElementById("select_team_" + index);
            for (var i = team_drop_down.length - 1; i >= 2; i--) {
                team_drop_down.remove(i);
            }
            for (var i = 0; i < teams.length; i++) {
                team = teams[i];
                if (team["continent"] == value || value == "") {
                    var option = document.createElement("option");
                    option.text = team["chinese_name"];
                    option.value = team["name"];
                    team_drop_down.add(option);
                }
            }

            // modify logo
            if (value != "") document.getElementById("selected_" + index).src = "images/continents/" + value + ".png";
            else document.getElementById("selected_" + index).src = "images/ball.png";

            if (value == "") {
                if (index == 1) button_display(-1, 0);
                else button_display(0, -1);
            }
            else {
                if (index == 1) button_display(1, 0);
                else button_display(0, 1);
            }
        }

        function select_country(index) {
            var continent_value = document.getElementById("select_continent_" + index).value;
            var value = document.getElementById("select_country_" + index).value;

            if (continent_value != "" && value == "") {
                select_continent(index);
                return;
            }
            
            if (value == "") document.getElementById("select_country_" + index).value = "";
            document.getElementById("select_team_" + index).value = "";

            var team_drop_down = document.getElementById("select_team_" + index);
            for (var i = team_drop_down.length - 1; i >= 2; i--) {
                team_drop_down.remove(i);
            }
            for (var i = 0; i < teams.length; i++) {
                team = teams[i];
                if (team["nationality"] == value || value == "") {
                    var option = document.createElement("option");
                    option.text = team["chinese_name"];
                    option.value = team["name"];
                    team_drop_down.add(option);
                }
            }

            // modify logo
            if (value != "") document.getElementById("selected_" + index).src = "images/football associations/" + value + ".png";
            else document.getElementById("selected_" + index).src = "images/ball.png";

            if (value == "") {
                if (index == 1) button_display(-1, 0);
                else button_display(0, -1);
            }
            else {
                if (index == 1) button_display(1, 0);
                else button_display(0, 1);
            }
        }

        function select_team(index) {
            var value = document.getElementById("select_team_" + index).value;
            if (value == "") {
                if (document.getElementById("select_country_" + index).value != "") {
                    select_country(index);
                    return;
                }
                if (document.getElementById("select_continent_" + index).value != "") {
                    select_continent(index);
                    return;
                }
                document.getElementById("select_team_" + index).value = "";
            }

            // modify logo
            if (value != "") document.getElementById("selected_" + index).src = "images/teams/" + value + ".png";
            else document.getElementById("selected_" + index).src = "images/ball.png";

            if (value == "") {
                if (index == 1) button_display(-1, 0);
                else button_display(0, -1);
            }
            else {
                if (index == 1) button_display(1, 0);
                else button_display(0, 1);
            }
        }
    </script>