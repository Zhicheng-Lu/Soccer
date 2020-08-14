	<br><br><br><br><br>
	<style type="text/css">
		@media (min-width: 576px) {
		    .table1 {
		        font-size: 14px;
		    }
		}
		@media (max-width: 576px) {
		    .table1 {
		        font-size: 12px;
		    }
		}
	</style>
	<?php include("new_competition_tabs/group/inter_group.php"); ?>
	<div class="section">
		<div class="container">
			<div class="row">
				<button class="col-md-30 offset-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_group_draw(<?php echo '\''.$tournament.'\', '.$competition; ?>, 0)">小组赛抽签</button>
				<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_inter_group()">小组间排名</button>
			</div>
			<div class="row">
				<?php
				$groups = array();
				$sql = 'SELECT * FROM '.$tournament.' WHERE competition='.$competition.' AND group_index<>""';
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$group_index = $row["group_index"];
					if (!isset($groups[$group_index])) {
						$groups[$group_index] = array();
					}
					array_push($groups[$group_index], array("round"=>$row["round"], "game"=>$row["game"], "team1"=>$row["team1"], "score1"=>$row["score1"], "score2"=>$row["score2"], "team2"=>$row["team2"]));
				}
				foreach ($groups as $group_index => $group) {
					// get the unique team name in the group & rank
					$teams = array();
					foreach ($group as $match) {
						if (!isset($teams[$match["team1"]]) && $match["team1"] != "") {
							$teams[$match["team1"]] = array();
						}
						if (!isset($teams[$match["team2"]]) && $match["team2"] != "") {
							$teams[$match["team2"]] = array();
						}
					}
					if (sizeof($teams) < 4) continue;
					$teams = rank($teams, $group);

					echo '
				<div class="col-xxl-40 col-md-60 col-120" style="margin-top: 20px;">
					<b style="font-size: 20px; margin-bottom: 55px;"><a href="new_competition.php?tournament='.$tournament.'&competition='.$competition.'&stage=group&group='.$group_index.'" style="text-decoration: underline; color: black;" target="_blank">'.$group_index.' 组</a></b>
				    <table style="width: 100%;" class="table1">
				        <p></p>
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
						$team_name = $team["team_name"];
						$sql = 'SELECT team_chinese_name FROM teams WHERE team_name="'.$team_name.'"';
						$result = $conn->query($sql);
						while ($row = $result->fetch_assoc()) {
							$team_chinese_name = $row["team_chinese_name"];
						}

						$color = "black";
						if ($team_index == 0) {
							if ($tournament == "champions_league") $color = "red";
							if ($tournament == "union_associations") $color = "blue";
							if ($tournament == "winners_cup") $color = "#1A9361";
						}
						if ($team_index == 1) {
					    	if ($tournament == "champions_league") {
					    		$color = "blue";
					    		for ($i=0; $i < 11; $i++) { 
					    			$second_place_team = $second_place[$i];
					    			if ($team_name == $second_place_team["team_name"]) {
					    				$color = "red";
					    			}
					    		}
					    	}
					    	if ($tournament == "union_associations") {
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
					    	if ($tournament == "winners_cup") $color = "#1A9361";
					    }
					    if ($team_index == 2) {
					    	if ($tournament == "champions_league" && $competition >= 15) $color = "#1A9361";
					    	if ($tournament == "union_associations" && $competition >= 15) $color = "#1A9361";
					    }

					    // if group matches not completed, color is black
					    if ($team["win"] + $team["draw"] + $team["lose"] != 6) {
					    	$color = "black";
					    }

					   	echo '
		        <tr>
		            <td>
		            	&nbsp;<a style="text-decoration: none; color: '.$color.';"><img src="images/teams/'.$team_name.'.png" style="width: 16px; height: 16px; margin-top: 3px;"> '.$team_chinese_name.'</a>
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
				</div>';
				}
				?>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function open_group_draw(tournament, competition, auto_draw) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("group_draw_modal_content").innerHTML = xhttp.responseText;
					document.getElementById("group_draw_modal").style.display = "block";

					ball_0 = document.getElementById("draw_ball_0");
					if (typeof(ball_0) != 'undefined' && ball_0 != null && auto_draw == 1) {
						ball_0.click();
					}
				}
			};
			xhttp.open("POST", "new_competition_tabs/group/draw_modal.php", true);
			xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhttp.send("tournament=" + tournament + "&competition=" + competition + "&auto_draw=" + auto_draw);
		}

		function open_inter_group() {
			document.getElementById("inter_group_modal").style.display = "block";
		}

		function close_group_draw_modal() {
        	// document.getElementById("group_draw_modal").style.display = "none";
        	location.reload();
        }

        function close_inter_group_modal() {
        	document.getElementById("inter_group_modal").style.display = "none";
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

        function draw_new_team(tournament, competition, group_name, team_name, auto_draw) {
        	var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("chosen_team_modal_body").innerHTML = xhttp.responseText;
					document.getElementById("chosen_team_modal").style.display = "block";
					if (auto_draw == 0) stop_time = 400;
					else stop_time = 0;
					setTimeout(function(){
						document.getElementById("chosen_team_modal").style.display = "none";
						open_group_draw(tournament, competition, auto_draw);
					}, stop_time);
				}
			};
			xhttp.open("POST", "new_competition_tabs/group/draw_new_team.php", true);
			xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhttp.send("tournament=" + tournament + "&competition=" + competition + "&group_name=" + group_name + "&team_name=" + team_name + "&auto_draw=" + auto_draw);
        }
	</script>

	<!-- The Modal -->
	<div id="group_draw_modal" class="modal" style="z-index:9999">
	    <!-- Modal content -->
	    <div class="modal-content col-lg-108 offset-lg-6 col-120" id="group_draw_modal_content">

	    	?>
	    </div>
	</div>

	<div id="chosen_team_modal" class="modal" style="z-index: 19999; font-size: 40px">
		<div class="modal-content col-lg-108 offset-lg-6 col-120">
			<div class="modal-body" id="chosen_team_modal_body" onclick="close_chosen_team_modal()" style="height: 500px; text-align: center; vertical-align: middle;">
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->