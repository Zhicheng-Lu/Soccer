	</header><!-- .site-header -->

	<?php
	// translate continent name from English to Chinese
	function get_continent_chinese($continent) {
		if ($continent == "Europe") return "欧洲";
		if ($continent == "Asia") return "亚洲";
		if ($continent == "South America") return "南美洲";
		if ($continent == "North America") return "北美洲";
		if ($continent == "Africa") return "非洲";
		if ($continent == "Oceania") return "大洋洲";
		if ($continent == "Antarctica") return "南极洲";
	}

	function get_country($conn, $team_name) {
		$sql = 'SELECT team_nationality FROM teams WHERE team_name="'.$team_name.'"';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			return $row["team_nationality"];
		}
	}

	function get_continent($conn, $team_name) {
		$sql = 'SELECT C.country_continent FROM teams AS T LEFT JOIN countries AS C on T.team_nationality=C.country_name WHERE T.team_name="'.$team_name.'"';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			return $row["country_continent"];
		}
	}

	// check if matches
	function match($match, $team_index, $criteria_index) {
		if (isset($_GET["team".$criteria_index])) {
			if ($match["team".$team_index] == $_GET["team".$criteria_index]) return TRUE;
		}
		else if (isset($_GET["country".$criteria_index])) {
			if ($match["country".$team_index] == $_GET["country".$criteria_index]) return TRUE;
		}
		else if (isset($_GET["continent".$criteria_index])) {
			if ($match["continent".$team_index] == $_GET["continent".$criteria_index]) return TRUE;
		}
		return FALSE;
	}
	?>

	<style type="text/css">
		@media (min-width: 768px) {
			.box1 {
				font-size: 16px;
			}
		}
		@media (min-width: 992px) {
			.box1 {
				font-size: 18px;
			}
		}
		@media (min-width: 1200px) {
			.box1 {
				font-size: 20px;
			}
		}
		@media (min-width: 1500px) {
			.line1 {
				margin-top: 30px;
			}
			.line2 {
				margin-top: 10px;
			}
		}
		@media (min-width: 768px) and (max-width: 992px) {
			.line1 {
				margin-top: 30px;
			}
			.line2 {
				margin-top: 10px;
			}
		}
		@media (min-width: 576px) {
			.title {
				font-size: 24px;
			}
			.title_logo {
				width: 24px;
				height: 24px;
			}
		}
		@media (max-width: 576px) {
			.title {
				font-size: 15px;
			}
			.title_logo {
				width: 16px;
				height: 16px;
			}
		}
	</style>

	<br><br><br><br><br>
	<div class="section">
        <div class="container">
        	<div class="row">
	        	<?php
				for ($i = 1; $i <= 2; $i++) {
					echo '
				<div class="col-60 border-div" style="margin-bottom: 5px;">
					<div class="row">';

					// if a team
					if (isset($_GET["team".$i])) {
						$team_name = $_GET["team".$i];
						
						// get basic information of the team
						$sql = 'SELECT * FROM teams AS T LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE T.team_name="'.$team_name.'"';
						$result = $conn->query($sql);
						while ($row = $result->fetch_assoc()) {
						    $team_chinese_name = $row["team_chinese_name"];
						    $nationality = $row["team_nationality"];
						    $nationality_chinese = $row["country_chinese_name"];
			    			$continent = $row["country_continent"];
						}

						echo '
		                <div class="col-lg-60 col-120">
		                    <p class="title"><b>'.$team_chinese_name.'</b> <img class="d-lg-none title_logo" src="images/teams_small/'.$team_name.'.png"></p>
		                    <p style="font-size: 14px; color: blue;">类别：'.(($nationality == $team_name)? "国家队" : "俱乐部").'</p>
		                    <p style="font-size: 14px;">英文名称：'.$team_name.'</p>
		                    <p style="font-size: 14px;">国籍：'.$nationality_chinese.' <img src="images/countries_small/'.$nationality.'.png" style="width: 14px; height: 14px;"></p>
		                    <p style="font-size: 14px;">大洲：'.get_continent_chinese($continent).' <img src="images/continents_small/'.$continent.'.png" style="width: 14px; height: 14px;"></p>
		                </div>
		                <div class="col-lg-60 d-none d-lg-block">
		                    <img src="images/teams/'.$team_name.'.png" style="width: 200px; height: 200px;">
		                </div>';
					}

					// if a country
					else if (isset($_GET["country".$i])) {
						$nationality = $_GET["country".$i];
						
						// get basic information of the team
						$sql = 'SELECT * FROM countries WHERE country_name="'.$nationality.'"';
						$result = $conn->query($sql);
						while ($row = $result->fetch_assoc()) {
						    $nationality_chinese = $row["country_chinese_name"];
			    			$continent = $row["country_continent"];
						}

						echo '
					    <div class="col-lg-60 col-120">
					        <p class="title"><b>'.$nationality_chinese.'</b> <img class="d-lg-none title_logo" src="images/football associations/'.$nationality.'.png"></p>
					        <p style="color: blue;">类别：国家</p>
					        <p>英文名称：'.$nationality.'</p>
					        <p>大洲：'.get_continent_chinese($continent).' <img style="width: 14px; height: 14px;" src="images/continents/'.$continent.'.png"></p>
					    </div>
					    <div class="col-lg-60 d-none d-lg-block">
						    <img src="images/football associations/'.$nationality.'.png" style="width: 200px; height: 200px;">
					    </div>';
					}

					// if a continent
					else if (isset($_GET["continent".$i])) {
						$continent = $_GET["continent".$i];

						echo '
					    <div class="col-lg-60 col-120">
					        <p class="title"><b>'.get_continent_chinese($continent).'</b> <img class="d-lg-none title_logo" src="images/continents/'.$continent.'.png"></p>
					        <p style="color: blue;">类别：大洲</p>
					        <p>英文名称：'.$continent.'</p>
					    </div>

					    <div class="col-lg-60 d-none d-lg-block">
						    <img src="images/continents/'.$continent.'.png" style="width: 200px; height: 200px;">
					    </div>';
					}

					echo '
					</div>
				</div>';
				}

				include("index_tabs/history_specific/get_results.php");
				?>

				<!-- overall winning rate, average score -->
				<div class="col-43 border-div no-padding box1" style="text-align: center; margin-bottom: 5px;">
					<?php echo $win1; ?> 胜 （<?php echo $win_rate1; ?>%）<br>
					进 <?php echo $score1 ?> 球<br class="d-sm-none"> (场均 <?php echo $average_score1; ?> 球)
				</div>
				<div class="col-34 border-div no-padding box1" style="text-align: center; margin-bottom: 5px;">
					<?php echo $draw; ?> 平 （<?php echo $draw_rate; ?>%）<br>
				</div>
				<div class="col-43 border-div no-padding box1" style="text-align: center; margin-bottom: 5px;">
					<?php echo $win2; ?> 胜 （<?php echo $win_rate2; ?>%）<br>
					进 <?php echo $score2 ?> 球<br class="d-sm-none"> (场均 <?php echo $average_score2; ?> 球)
				</div>

				<!-- performance in each home stadium -->
				<div class="col-60 border-div box1" style="text-align: center;">
					主场<br>
					<div class="row">
						<div class="col-40 no-padding"><?php echo $home_win1; ?> 胜 (<?php echo $home_win_rate1; ?>%)</div>
						<div class="col-40 no-padding"><?php echo $home_draw1; ?> 平 (<?php echo $home_draw_rate1; ?>%)</div>
						<div class="col-40 no-padding"><?php echo $home_lose1; ?> 负 (<?php echo $home_lose_rate1; ?>%)</div>
						<div class="col-md-60 no-padding col-120">进 <?php echo $home_score1; ?> 球 (场均 <?php echo $home_average_score1; ?> 球)</div>
						<div class="col-md-60 no-padding col-120">失 <?php echo $home_concede1; ?> 球 (场均 <?php echo $home_average_concede1; ?> 球)</div>
					</div>
				</div>
				<div class="col-60 border-div box1" style="text-align: center;">
					主场<br>
					<div class="row">
						<div class="col-40 no-padding"><?php echo $home_win2; ?> 胜 (<?php echo $home_win_rate2; ?>%)</div>
						<div class="col-40 no-padding"><?php echo $home_draw2; ?> 平 (<?php echo $home_draw_rate2; ?>%)</div>
						<div class="col-40 no-padding"><?php echo $home_lose2; ?> 负 (<?php echo $home_lose_rate2; ?>%)</div>
						<div class="col-md-60 no-padding col-120">进 <?php echo $home_score2; ?> 球 (场均 <?php echo $home_average_score2; ?> 球)</div>
						<div class="col-md-60 no-padding col-120">失 <?php echo $home_concede2; ?> 球 (场均 <?php echo $home_average_concede2; ?> 球)</div>
					</div>
				</div>
        	</div>
        </div>
    </div>

    <div class="section" style="padding-top: 0px;">
        <div class="container">
        	<div class="row">
        		<?php
        		$temp_matches = array();
        		foreach ($matches as $match_index => $match) {
        			$tournament = $match["tournament"];
        			$tournament_chinese = $match["tournament_chinese"];
        			$competition = $match["competition"];
        			$group = $match["group"];
        			$round = $match["round"];
        			$game = $match["game"];
        			$team1 = $match["team1"];
        			$country1 = $match["country1"];
        			$continent1 = $match["continent1"];
        			$score1 = $match["score1"];
        			$score2 = $match["score2"];
        			$team2 = $match["team2"];
        			$country2 = $match["country2"];
        			$continent2 = $match["continent2"];
        			$extra_score1 = $match["extra_score1"];
        			$extra_score2 = $match["extra_score2"];
        			$penalty_score1 = $match["penalty_score1"];
        			$penalty_score2 = $match["penalty_score2"];

        			// group matches
        			if ($group != "") {
        				include("index_tabs/history_specific/group.php");
        			}

        			// if final
        			else if ($match["round"] == "final") {
        				include("index_tabs/history_specific/final.php");
        			}

        			// finals
        			else if ($match["round"] == "finals") {
        				include("index_tabs/history_specific/finals.php");
        			}

        			// other knockout matches
        			else {
        				include("index_tabs/history_specific/knockout.php");
        			}
        		}
        		?>
        	</div>
        </div>
    </div>

    <?php
    include("includes/team_modal/modal.php")
    ?>