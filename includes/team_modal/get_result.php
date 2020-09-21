    <?php
    function get_results($conn, $competition, $team_modal_name) {
        $tournaments = array(array("name"=>"champions_league"), array("name"=>"union_associations"), array("name"=>"winners_cup"));
        // find the tournament and group index for group match
        $group_tournament = "";
        foreach ($tournaments as $key => $tournament) {
            $sql = "SELECT * FROM ".$tournament["name"]." WHERE competition=".$competition." AND group_index<>'' AND (team1=\"".$team_modal_name."\" OR team2=\"".$team_modal_name."\")";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $group_tournament = $tournament["name"];
            }
        }

        // get all the knockout matches
        $knockout_matches = array();
        $knockout_tournament = "";
        foreach ($tournaments as $key => $tournament) {
            if ($tournament["name"] != "winners_cup") {
                $sql = "SELECT * FROM ".$tournament["name"]." WHERE competition=".$competition." AND group_index='' AND round<>'champion' AND (team1=\"".$team_modal_name."\" OR team2=\"".$team_modal_name."\") AND round<>'club_leveldown_first' AND round<>'club_leveldown_second' AND round<>'club_qualifications_first' AND round<>'club_qualifications_second' AND round<>'nation_qualifications_first'";
            }
            else {
                $sql = "SELECT * FROM winners_cup WHERE competition=".$competition." AND group_index='' AND round<>'champion' AND (team1=\"".$team_modal_name."\" OR team2=\"".$team_modal_name."\") AND round<>'club_leveldown_first' AND round<>'club_leveldown_second' AND round<>'club_qualifications_first' AND round<>'club_qualifications_second' AND round<>'nation_qualifications_first' ORDER BY CASE WHEN round='122' THEN 122 WHEN round='70' THEN 70 WHEN round='48' THEN 48 WHEN round='24' THEN 24 WHEN round='12' THEN 12 WHEN round='6' THEN 6 WHEN round='finals' THEN 3 END DESC, game ASC";
            }
            
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $knockout_tournament = $tournament["name"];
                array_push($knockout_matches, array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"], "extra_score1"=>$row["extra_score1"], "extra_score2"=>$row["extra_score2"], "penalty_score1"=>$row["penalty_score1"], "penalty_score2"=>$row["penalty_score2"]));
            }
        }

        if ($group_tournament == "") {
            return array("", "", "");
        }

        // results
        if ($group_tournament == "champions_league") {
            if ($knockout_tournament != "champions_league") $champions_league_result = '小组赛';
            else {
                $last_round = $knockout_matches[count($knockout_matches) - 1]["round"];
                if ($last_round == "1_16") $champions_league_result = '32 强';
                if ($last_round == "1_8") $champions_league_result = '16 强';
                if ($last_round == "1_4") $champions_league_result = '8 强';
                if ($last_round == "semi_final") $champions_league_result = '4 强';
                if ($last_round == "final") {
                    $sql = 'SELECT * FROM champions_league WHERE competition='.$competition.' AND round="champion"';
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $champions_league_result = (($row["team1"]==$team_modal_name)? '冠军': '亚军');
                    }
                }
            }
        }
        else {
            $champions_league_result = '';
        }

        if ($group_tournament == "union_associations") {
            if ($knockout_tournament != "union_associations") $union_associations_result = '小组赛';
            else {
                $last_round = $knockout_matches[count($knockout_matches) - 1]["round"];
                if ($last_round == "1_32") $union_associations_result = '64 强';
                if ($last_round == "1_16") $union_associations_result = '32 强';
                if ($last_round == "1_8") $union_associations_result = '16 强';
                if ($last_round == "1_4") $union_associations_result = '8 强';
                if ($last_round == "semi_final") $union_associations_result = '4 强';
                if ($last_round == "final") {
                    $sql = 'SELECT * FROM union_associations WHERE competition='.$competition.' AND round="champion"';
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $union_associations_result = (($row["team1"]==$team_modal_name)? '冠军': '亚军');
                    }
                }
            }
        }
        else {
            if ($knockout_tournament == "union_associations") {
                $last_round = $knockout_matches[count($knockout_matches) - 1]["round"];
                if ($last_round == "1_32") $union_associations_result = '64 强';
                if ($last_round == "1_16") $union_associations_result = '32 强';
                if ($last_round == "1_8") $union_associations_result = '16 强';
                if ($last_round == "1_4") $union_associations_result = '8 强';
                if ($last_round == "semi_final") $union_associations_result = '4 强';
                if ($last_round == "final") {
                    $sql = 'SELECT * FROM union_associations WHERE competition='.$competition.' AND round="champion"';
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        $union_associations_result = (($row["team1"]==$team_modal_name)? '冠军': '亚军');
                    }
                }
            }
            else {
                $union_associations_result = '';
            }
        }

        if ($group_tournament == "winners_cup") {
            if ($knockout_tournament != "winners_cup") $winners_cup_result = '小组赛';
            else {
                $last_round = $knockout_matches[count($knockout_matches) - 1]["round"];
                if ($last_round == "122") $winners_cup_result = '122 强';
                if ($last_round == "70") $winners_cup_result = '70 强';
                if ($last_round == "48") $winners_cup_result = '48 强';
                if ($last_round == "24") $winners_cup_result = '24 强';
                if ($last_round == "12") $winners_cup_result = '12 强';
                if ($last_round == "6") $winners_cup_result = '6 强';
                if ($last_round == "finals") {
                    // decide '冠军', '亚军', '季军'
                    $matches = array();
                    $sql = 'SELECT * FROM winners_cup WHERE competition='.$competition.' AND round="finals"';
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        array_push($matches, array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"]));
                    }

                    // get the unique team name in the group
                    $teams = array();
                    foreach ($matches as $match) {
                        if (!isset($teams[$match["team1"]])) {
                            $teams[$match["team1"]] = array();
                        }
                        if (!isset($teams[$match["team2"]])) {
                            $teams[$match["team2"]] = array();
                        }
                    }
                    $teams = rank($teams, $matches);
                    if ($teams[0]["team_name"] == $team_modal_name) $winners_cup_result = '冠军';
                    if ($teams[1]["team_name"] == $team_modal_name) $winners_cup_result = '亚军';
                    if ($teams[2]["team_name"] == $team_modal_name) $winners_cup_result = '季军';
                }
            }
        }
        else {
            if ($knockout_tournament == "winners_cup") {
                $last_round = $knockout_matches[count($knockout_matches) - 1]["round"];
                if ($last_round == "122") $winners_cup_result = '122 强';
                if ($last_round == "70") $winners_cup_result = '70 强';
                if ($last_round == "48") $winners_cup_result = '48 强';
                if ($last_round == "24") $winners_cup_result = '24 强';
                if ($last_round == "12") $winners_cup_result = '12 强';
                if ($last_round == "6") $winners_cup_result = '6 强';
                if ($last_round == "finals") {
                    // decide '冠军', '亚军', '季军'
                    $matches = array();
                    $sql = 'SELECT * FROM winners_cup WHERE competition='.$competition.' AND round="finals"';
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        array_push($matches, array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"]));
                    }

                    // get the unique team name in the group
                    $teams = array();
                    foreach ($matches as $match) {
                        if (!isset($teams[$match["team1"]])) {
                            $teams[$match["team1"]] = array();
                        }
                        if (!isset($teams[$match["team2"]])) {
                            $teams[$match["team2"]] = array();
                        }
                    }
                    $teams = rank($teams, $matches);
                    if ($teams[0]["team_name"] == $team_modal_name) $winners_cup_result = '冠军';
                    if ($teams[1]["team_name"] == $team_modal_name) $winners_cup_result = '亚军';
                    if ($teams[2]["team_name"] == $team_modal_name) $winners_cup_result = '季军';
                }
            }
            else {
                $winners_cup_result = '';
            }
        }

        return array($champions_league_result, $union_associations_result, $winners_cup_result);
    }
    ?>