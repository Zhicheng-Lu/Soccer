	<?php
	$team_modal_group_result = "";
    foreach ($teams as $key => $team) {
        if ($team["team_name"] == $team_name) $team_modal_group_result = $team;
    }
    if ($team_modal_group_result == "") {
        $team_modal_group_result = array("win"=>0, "draw"=>0, "lose"=>0, "score"=>0, "concede"=>0);
    }

    $knockout_win = 0;
    $knockout_draw = 0;
    $knockout_lose = 0;
    $knockout_score = 0;
    $knockout_concede = 0;
    $home_win = 0;
    $home_draw = 0;
    $home_lose = 0;
    $home_score = 0;
    $home_concede = 0;
    $away_win = 0;
    $away_draw = 0;
    $away_lose = 0;
    $away_score = 0;
    $away_concede = 0;
    foreach ($matches as $key => $match) {
        if ($match["team1"] == $team_name) {
            $home_score += $match["score1"];
            $home_concede += $match["score2"];
            if ($match["score1"] > $match["score2"]) $home_win++;
            if ($match["score1"] == $match["score2"]) $home_draw++;
            if ($match["score1"] < $match["score2"]) $home_lose++;
        }
        else if ($match["team2"] == $team_name) {
            $away_score += $match["score2"];
            $away_concede += $match["score1"];
            if ($match["score1"] < $match["score2"]) $away_win++;
            if ($match["score1"] == $match["score2"]) $away_draw++;
            if ($match["score1"] > $match["score2"]) $away_lose++;
        }
    }
    foreach ($knockout_matches as $key => $match) {
        if ($match["team1"] == $team_name) {
            if ($match["round"] != 'final') {
                $home_score += $match["score1"] + $match["extra_score1"];
                $home_concede += $match["score2"] + $match["extra_score2"];
            }
            $knockout_score += $match["score1"] + $match["extra_score1"];
            $knockout_concede += $match["score2"] + $match["extra_score2"];
            if ($match["score1"] > $match["score2"]) {
                if ($match["round"] != 'final') $home_win++;
                $knockout_win++;
            }
            if ($match["score1"] == $match["score2"]) {
                if ($match["round"] != 'final') $home_draw++;
                $knockout_draw++;
            }
            if ($match["score1"] < $match["score2"]) {
                if ($match["round"] != 'final') $home_lose++;
                $knockout_lose++;
            }
        }
        else if ($match["team2"] == $team_name) {
            if ($match["round"] != 'final') {
                $away_score += $match["score2"] + $match["extra_score2"];
                $away_concede += $match["score1"] + $match["extra_score1"];
            }
            $knockout_score += $match["score2"] + $match["extra_score2"];
            $knockout_concede += $match["score1"] + $match["extra_score1"];
            if ($match["score1"] < $match["score2"]) {
                if ($match["round"] != 'final') $away_win++;
                $knockout_win++;
            }
            if ($match["score1"] == $match["score2"]) {
                if ($match["round"] != 'final') $away_draw++;
                $knockout_draw++;
            }
            if ($match["score1"] > $match["score2"]) {
                if ($match["round"] != 'final') $away_lose++;
                $knockout_lose++;
            }
        }
    }

    $results = get_results($conn, $competition, $team_name);
    $champions_league_result = ($results[0]==""? "无":$results[0]);
    $union_associations_result = ($results[1]==""? "无":$results[1]);
    $winners_cup_result = ($results[2]==""? "无":$results[2]);

	echo '
	                    <div style="margin-top: 15px;" class="row">
	                        <div class="col-xxl-80 col-xl-20 col-md-30 col-sm-40 col-65">世界足球冠军杯</div>
	                        <div class="col-xxl-40 col-xl-100 col-md-90 col-sm-80 col-55 no-padding">'.$champions_league_result.'</div>
	                    </div>
	                    <div style="margin-top: 15px;" class="row">
	                        <div class="col-xxl-80 col-xl-20 col-md-30 col-sm-40 col-65">世界足球联盟杯</div>
	                        <div class="col-xxl-40 col-xl-100 col-md-90 col-sm-80 col-55 no-padding">'.$union_associations_result.'</div>
	                    </div>
	                    <div style="margin-top: 15px;" class="row">
	                        <div class="col-xxl-80 col-xl-20 col-md-30 col-sm-40 col-65">世界足球优胜者杯</div>
	                        <div class="col-xxl-40 col-xl-100 col-md-90 col-sm-80 col-55 no-padding">'.$winners_cup_result.'</div>
	                    </div>

	                    <div style="margin-top: 40px;">小组赛：</div>
	                    <div style="margin-left: 25px;" class="row">
	                    	<div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$team_modal_group_result["win"].'</div>胜
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$team_modal_group_result["draw"].'</div>平
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$team_modal_group_result["lose"].'</div>负
		                    </div>
		                    <div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        进<div style="width: 25px; display: inline-block; text-align: center;">'.$team_modal_group_result["score"].'</div>球，
		                        失<div style="width: 25px; display: inline-block; text-align: center;">'.$team_modal_group_result["concede"].'</div>球
		                    </div>
	                    </div>

	                    <div style="margin-top: 20px;">淘汰赛：</div>
	                    <div style="margin-left: 25px;" class="row">
	                    	<div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$knockout_win.'</div>胜
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$knockout_draw.'</div>平
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$knockout_lose.'</div>负
	                        </div>
		                    <div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        进<div style="width: 25px; display: inline-block; text-align: center;">'.$knockout_score.'</div>球，
		                        失<div style="width: 25px; display: inline-block; text-align: center;">'.$knockout_concede.'</div>球
		                    </div>
	                    </div>

	                    <div style="margin-top: 20px;">主场：</div>
	                    <div style="margin-left: 25px;" class="row">
	                    	<div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$home_win.'</div>胜
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$home_draw.'</div>平
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$home_lose.'</div>负
		                    </div>
		                    <div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        进<div style="width: 25px; display: inline-block; text-align: center;">'.$home_score.'</div>球，
		                        失<div style="width: 25px; display: inline-block; text-align: center;">'.$home_concede.'</div>球
		                    </div>
	                    </div>

	                    <div style="margin-top: 20px;">客场：</div>
	                    <div style="margin-left: 25px;" class="row">
	                    	<div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$away_win.'</div>胜
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$away_draw.'</div>平
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.$away_lose.'</div>负
		                    </div>
		                    <div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        进<div style="width: 25px; display: inline-block; text-align: center;">'.$away_score.'</div>球，
		                        失<div style="width: 25px; display: inline-block; text-align: center;">'.$away_concede.'</div>球
		                    </div>
	                    </div>

	                    <div style="margin-top: 20px;">总计：</div>
	                    <div style="margin-left: 25px;" class="row">
	                    	<div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.($team_modal_group_result["win"] + $knockout_win).'</div>胜
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.($team_modal_group_result["draw"] + $knockout_draw).'</div>平
		                        <div style="width: 25px; display: inline-block; text-align: center;">'.($team_modal_group_result["lose"] + $knockout_lose).'</div>负
		                    </div>
		                    <div class="col-xxl-120 col-lg-20 col-md-30 col-sm-40 col-60 no-padding">
		                        进<div style="width: 25px; display: inline-block; text-align: center;">'.($team_modal_group_result["score"] + $knockout_score).'</div>球，
		                        失<div style="width: 25px; display: inline-block; text-align: center;">'.($team_modal_group_result["concede"] + $knockout_concede).'</div>球
		                    </div>
	                    </div>';
	?>