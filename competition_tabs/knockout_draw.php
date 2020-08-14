			<div class="row">
				<button class="col-sm-30 offset-sm-15 col-40" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_knockout_modal('knockout_participants')">参赛球队</button>
				<button class="col-sm-30 col-40" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_knockout_modal('knockout_draw_stats')">统计</button>
				<button class="col-sm-30 col-40" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_knockout_modal('knockout_draw_results')">抽签结果</button>
			</div>

			<!-- The Modal -->
		    <div id="knockout_participants_modal" class="modal">
		        <!-- Modal content -->
		        <div class="modal-content col-lg-108 offset-lg-6 col-120">
		        	<div class="modal-header">
		        		<h3>参赛球队</h3>
		                <span class="close" onclick="close_knockout_modal('knockout_participants')">&times;</span>
		            </div>
		        	<div class="modal-body" style="font-size: 13px;">
		        		<div class="row">
			        		<?php
			        		function cmp_continent_country($team1, $team2) {
								if ($team1["continent"] == $team2["continent"]) {
									return strcmp($team1["nationality"], $team2["nationality"]);
								}
								return strcmp($team1["continent"], $team2["continent"]);
							}

			        		function draw_participants($conn, $title, $teams, $competition) {
			        			for ($i = 0; $i < count($teams); $i++) {
									$team_name = $teams[$i]["team_name"];
									$sql = 'SELECT T.team_chinese_name, C.country_name, C.country_chinese_name, C.country_continent FROM teams AS T LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE T.team_name="'.$team_name.'"';
									$result = $conn->query($sql);
									while ($row = $result->fetch_assoc()) {
										$teams[$i]["chinese_name"] = $row["team_chinese_name"];
										$teams[$i]["nationality"] = $row["country_name"];
										$teams[$i]["nationality_chinese"] = $row["country_chinese_name"];
										$teams[$i]["continent"] = $row["country_continent"];
										// switch continent to get chinese name
										$continent = $row["country_continent"];
										if ($continent == "Antarctica") $continent_chinese = "南极洲";
										if ($continent == "Africa") $continent_chinese = "非洲";
										if ($continent == "Asia") $continent_chinese = "亚洲";
										if ($continent == "Europe") $continent_chinese = "欧洲";
										if ($continent == "North America") $continent_chinese = "北美洲";
										if ($continent == "Oceania") $continent_chinese = "大洋洲";
										if ($continent == "South America") $continent_chinese = "南美洲";
										$teams[$i]["continent_chinese"] = $continent_chinese;

									}
								}
								usort($teams, "cmp_continent_country");

								echo '
								<table style="width: 100%;">
									'.($title == ""? '':'<tr><th colspan="3">'.$title.'</th></tr>').'
									<tr>
										<th style="width: 45%;">球队名称</th>
										<th style="width: 38%;">国籍</th>
										<th style="width: 17%;">大洲</th>
									</tr>';

								foreach ($teams as $key => $team) {
									echo '
									<tr>
										<td style="cursor: pointer;" onclick=\'open_modal("'.str_replace("'", "\'", $team["team_name"]).'", '.$competition.')\'>&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" style="width: 18px; height: 18px;"> '.$team["chinese_name"].'</td>
										<td>&nbsp;'.$team["nationality_chinese"].'</td>
										<td>&nbsp;'.$team["continent_chinese"].'</td>
									</tr>';
								}

								echo '
								</table>';

								return $teams;
			        		}

			        		$teams = array(array(), array());
			        		$sql = 'SELECT game, team2 FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" ORDER BY game ASC';
							$result = $conn->query($sql);
							while ($row = $result->fetch_assoc()) {
								if (($tournament == "champions_league" && $round == "1_16") || ($tournament == "union_associations" && $competition == 11 && $round == "1_16") || ($tournament == "union_associations" && $round == "1_32") || ($tournament == "winners_cup" && $round == "122")) {
									array_push($teams[$row["game"] % 2], array("team_name"=>$row["team2"]));
								}
								else {
									array_push($teams[0], array("team_name"=>$row["team2"]));
								}
							}
							if (sizeof($teams[1]) == 0) {
								echo '
							<div class="col-md-60 col-sm-90 offset-md-30 offset-sm-15">';
								$teams[0] = draw_participants($conn, "", $teams[0], $competition);
								echo '
							</div>';
							}
							else {
								echo '
							<div class="col-md-60 col-sm-90 offset-md-0 offset-sm-15" style="margin-bottom: 20px;">';
								$teams[0] = draw_participants($conn, "第一档次", $teams[0], $competition);
								echo '
							</div>';
							echo '
							<div class="col-md-60 col-sm-90 offset-md-0 offset-sm-15">';
								$teams[1] = draw_participants($conn, "第二档次", $teams[1], $competition);
								echo '
							</div>';
							}
			        		?>
		        		</div>
		        	</div>
		        </div>
		    </div>

		    <!-- The Modal -->
		    <div id="knockout_draw_stats_modal" class="modal">
		        <!-- Modal content -->
		        <div class="modal-content col-lg-108 offset-lg-6 col-120">
		        	<div class="modal-header">
		        		<h3>统计</h3>
		                <span class="close" onclick="close_knockout_modal('knockout_draw_stats')">&times;</span>
		            </div>
		        	<div class="modal-body" style="font-size: 13px;">
		        		<div class="row">
		        			<div class="col-xxl-20 offset-xxl-35 col-xl-30 offset-xl-25 col-lg-40 offset-lg-15 col-md-40 offset-md-10 col-sm-45" style="margin-bottom: 20px;">
			        			<?php
				        		function cmp_count($team1, $team2) {
									return $team2["count"] - $team1["count"];
								}

				        		$teams = array_merge($teams[0], $teams[1]);
				        		$continents = array();
								$countries = array();
								foreach ($teams as $key => $team) {
									$continent = $team["continent"];
									$country = $team["nationality"];
									if (!isset($continents[$continent])) {
										$continents[$continent] = array("continent"=>$continent, "count"=>1);
									}
									else {
										$continents[$continent]["count"]++;
									}
									if (!isset($countries[$country])) {
										$countries[$country] = array("country"=>$country, "count"=>1);
									}
									else {
										$countries[$country]["count"]++;
									}
								}
								usort($continents, "cmp_count");
								usort($countries, "cmp_count");

								echo '
								<table style="width: 100%;">
									<tr>
										<th style="width: 45%;">大洲</th>
										<th style="width: 36%;">数量</th>
										<th style="width: 19%;"></th>
									</tr>
									<tr>
										<td style="text-align:center;">总计</td>
										<td style="text-align:center;">'.count($teams).'</td>
										<td style="text-align:center;"><input type="radio" name="continent" onchange="change_continent(\'all\')" checked></td>
									</tr>';

								// display number of teams for each continent
								foreach ($continents as $key => $continent) {
									if ($continent["continent"] == "Antarctica") $continent_chinese = "南极洲";
									if ($continent["continent"] == "Africa") $continent_chinese = "非洲";
									if ($continent["continent"] == "Asia") $continent_chinese = "亚洲";
									if ($continent["continent"] == "Europe") $continent_chinese = "欧洲";
									if ($continent["continent"] == "North America") $continent_chinese = "北美洲";
									if ($continent["continent"] == "Oceania") $continent_chinese = "大洋洲";
									if ($continent["continent"] == "South America") $continent_chinese = "南美洲";
									echo '
										<tr>
											<td>&nbsp;<img src="images/continents_small/'.$continent["continent"].'.png" style="width: 18px; height: 18px;"> '.$continent_chinese.'</td>
											<td style="text-align:center;">'.$continent["count"].'</td>
											<td style="text-align:center;"><input type="radio" name="continent" onchange="change_continent(\''.$continent["continent"].'\')"></td>
										</tr>';
								}

								echo '
								</table>
							</div>
							<div class="col-xxl-30 col-xl-40 col-lg-50 col-md-60 col-sm-75">
								<table style="width: 100%;">
									<tr>
										<th style="width: 45%;">国家</th>
										<th style="width: 10%;">数量</th>
										<th style="width: 45%;">球队</th>
									</tr>';

								// display all teams for each country
								foreach ($countries as $country) {
									$sql = 'SELECT country_chinese_name, country_continent FROM countries WHERE country_name="'.$country["country"].'"';
									$result = $conn->query($sql);
									while ($row = $result->fetch_assoc()) {
										$country_chinese_name = $row["country_chinese_name"];
										$country_continent = $row["country_continent"];
									}

									echo '
										<td rowspan="'.($country["count"] + 1).'" class="display '.$country_continent.'">&nbsp;<img src="images/countries_small/'.$country["country"].'.png" style="width: 18px; height: 18px;"> '.$country_chinese_name.'</td>
										<td rowspan="'.($country["count"] + 1).'" style="text-align: center;" class="display '.$country_continent.'">'.$country["count"].'</td>';

									foreach ($teams as $team) {
										if ($team["nationality"] == $country["country"]) {
											echo '
										<tr class="display '.$country_continent.'">
											<td style="cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">
												&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" style="width: 18px; height: 18px;"> '.$team["chinese_name"].'
											</td>
										</tr>';
										}
									}
								}

								echo '
								</table>';
				        		?>
			        		</div>
		        		</div>
		        	</div>
		        </div>
		    </div>

		    <!-- The Modal -->
		    <div id="knockout_draw_results_modal" class="modal">
		        <!-- Modal content -->
		        <div class="modal-content col-lg-108 offset-lg-6 col-120">
		        	<div class="modal-header">
		        		<h3>抽签结果</h3>
		                <span class="close" onclick="close_knockout_modal('knockout_draw_results')">&times;</span>
		            </div>
		        	<div class="modal-body">
	        			<?php
	        			$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND group_index="" AND round="'.$round.'" ORDER BY game ASC';
	        			$result = $conn->query($sql);
						while ($row = $result->fetch_assoc()) {
							if ($row["game"] % 2 == 0) {
								echo '
						<div class="row" style="margin-top: 10px;">
							<div class="col-59" style="text-align: right;">
								<img src="images/teams_small/'.$row["team1"].'.png" style="width: 18px; height: 18px;"> '.get_team_chinese_name($conn, $row["team1"]).'
							</div>
							<div class="col-2 no-padding" style="text-align: center;">-</div>
							<div class="col-59" style="text-align: left;">
								<img src="images/teams_small/'.$row["team2"].'.png" style="width: 18px; height: 18px;"> '.get_team_chinese_name($conn, $row["team2"]).'
							</div>
						</div>';
							}
						}
	        			?>
		        	</div>
		        </div>
		    </div>

			<script type="text/javascript">
				function open_knockout_modal(name) {
					document.getElementById(name + "_modal").style.display = "block";
				}

				function close_knockout_modal(name) {
		        	document.getElementById(name + "_modal").style.display = "none";
		        }

		        // When the user clicks anywhere outside of the modal, close it
		        window.onclick = function(event) {
		        	var modals = document.getElementsByClassName("modal");
		        	for (var i = modals.length - 1; i >= 0; i--) {
		        		var modal = modals[i];
		        		if (event.target == modal) {
		                    modal.style.display = "none";
		                }
		        	}
		        }

		        function change_continent(continent) {
					displays = document.getElementsByClassName("display");
					for (var i = 0; i < displays.length; i++) {
						if (continent == "all") {
							displays[i].style.display = "";
						}
						else {
							displays[i].style.display = "none";
						}
						
					}

					continent = document.getElementsByClassName(continent);
					for (var i = 0; i < continent.length; i++) {
						continent[i].style.display = "";
					}
				}
			</script>