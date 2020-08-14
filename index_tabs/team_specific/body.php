	<?php    
	$matches = array();
    $tournaments = array(array("name"=>"champions_league", "chinese_name"=>"世界足球冠军杯"), array("name"=>"union_associations", "chinese_name"=>"世界足球联盟杯"), array("name"=>"winners_cup", "chinese_name"=>"世界足球优胜者杯"));

    // get all the qualification matches
    $qualification_matches = array();
    $qualification_rounds= array();
    foreach ($tournaments as $tournament) {
        $sql = "SELECT * FROM ".$tournament["name"]." WHERE competition=".$competition." AND (team1=\"".$team_name."\" OR team2=\"".$team_name."\") AND (round='club_leveldown_first' OR round='club_leveldown_second' OR round='club_qualifications_first' OR round='club_qualifications_second' OR round='nation_qualifications_first')";
        
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            if ($tournament["name"] == "champions_league") $round = "世界足球冠军杯";
            if ($tournament["name"] == "union_associations") $round = "世界足球联盟杯";
            if ($tournament["name"] == "winners_cup") $round = "世界足球优胜者杯";
            if ($row["round"] == "club_leveldown_first") $round = $round."落选赛第 1 轮";
            if ($row["round"] == "club_leveldown_second") $round = $round."落选赛第 2 轮";
            if ($row["round"] == "club_qualifications_first") $round = $round."俱乐部第 1 轮";
            if ($row["round"] == "club_qualifications_second") $round = $round."俱乐部第 2 轮";
            if ($row["round"] == "nation_qualifications_first") $round = $round."国家队第 1 轮";
            array_push($qualification_matches, array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"], "extra_score1"=>$row["extra_score1"], "extra_score2"=>$row["extra_score2"], "penalty_score1"=>$row["penalty_score1"], "penalty_score2"=>$row["penalty_score2"]));
            array_push($qualification_rounds, $round);
        }
    }

    // find the tournament and group index for group match
    $group = "-1";
    $group_tournament = "";
    foreach ($tournaments as $key => $tournament) {
        $sql = "SELECT * FROM ".$tournament["name"]." WHERE competition=".$competition." AND group_index<>'' AND (team1=\"".$team_name."\" OR team2=\"".$team_name."\")";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $group_tournament = $tournament["name"];
            $group_tournament_chinese = $tournament["chinese_name"];
            $group = $row["group_index"];
        }
    }

    // get all the group matches
    if ($group_tournament != "") {
        $sql = "SELECT * FROM ".$group_tournament." WHERE competition=".$competition." AND group_index='".$group."'";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            array_push($matches, array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"]));
        }
    }

    // get the unique team name in the group, and rank them
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
    if (sizeof($teams) > 0) {
        $teams[0]["chinese_name"] = get_team_chinese_name($conn, $teams[0]["team_name"]);
        $teams[1]["chinese_name"] = get_team_chinese_name($conn, $teams[1]["team_name"]);
        $teams[2]["chinese_name"] = get_team_chinese_name($conn, $teams[2]["team_name"]);
        $teams[3]["chinese_name"] = get_team_chinese_name($conn, $teams[3]["team_name"]);
    }

    // get all the knockout matches
    $knockout_matches = array();
    $knockout_tournament = "";
    foreach ($tournaments as $key => $tournament) {
        if ($tournament["name"] != "winners_cup") {
            $sql = "SELECT * FROM ".$tournament["name"]." WHERE competition=".$competition." AND group_index='' AND round<>'champion' AND (team1=\"".$team_name."\" OR team2=\"".$team_name."\") AND round<>'club_leveldown_first' AND round<>'club_leveldown_second' AND round<>'club_qualifications_first' AND round<>'club_qualifications_second' AND round<>'nation_qualifications_first'";
        }
        else {
            $sql = "SELECT * FROM winners_cup WHERE competition=".$competition." AND group_index='' AND round<>'champion' AND (team1=\"".$team_name."\" OR team2=\"".$team_name."\") AND round<>'club_leveldown_first' AND round<>'club_leveldown_second' AND round<>'club_qualifications_first' AND round<>'club_qualifications_second' AND round<>'nation_qualifications_first' ORDER BY CASE WHEN round='122' THEN 122 WHEN round='70' THEN 70 WHEN round='48' THEN 48 WHEN round='24' THEN 24 WHEN round='12' THEN 12 WHEN round='6' THEN 6 WHEN round='finals' THEN 3 END DESC, game ASC";
        }
        
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $knockout_tournament = $tournament["name"];
            $knockout_tournament_chinese = $tournament["chinese_name"];
            array_push($knockout_matches, array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"], "extra_score1"=>$row["extra_score1"], "extra_score2"=>$row["extra_score2"], "penalty_score1"=>$row["penalty_score1"], "penalty_score2"=>$row["penalty_score2"]));
        }
    }


	echo '
				<div class="row">
					<div class="col-xxl-100 col-120 modal-content-left">';

	include("index_tabs/team_specific/qualifications.php");
    include("index_tabs/team_specific/group.php");
    include("index_tabs/team_specific/knockouts.php");
    include("index_tabs/team_specific/winners_cup_finals.php");

	echo '
					</div>';

    echo '
					<div class="col-xxl-20 col-120 modal-content-right">
						<div><p><b style="font-size: 16px;">战绩：</b></p></div>';

    include("index_tabs/team_specific/display_result.php");

    echo '
					</div>
				</div>';
	?>