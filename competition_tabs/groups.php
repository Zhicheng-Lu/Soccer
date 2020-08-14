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
	<div class="section">
		<div class="container">
			<div class="row">
				<?php
				if ($competition < 15) {
					echo '
				<button class="col-md-30 offset-md-45 col-60 offset-30" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_inter_group()">小组间排名</button>';
				}
				else {
					echo '
				<button class="col-md-30 offset-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_group_draw()">小组赛抽签</button>
				<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_inter_group()">小组间排名</button>';
				}
				?>
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
						if (!isset($teams[$match["team1"]])) {
							$teams[$match["team1"]] = array();
						}
						if (!isset($teams[$match["team2"]])) {
							$teams[$match["team2"]] = array();
						}
					}
					$teams = rank($teams, $group);

					echo '
				<div class="col-xxl-40 col-md-60 col-120" style="margin-top: 20px;">
					<b style="font-size: 20px; margin-bottom: 55px;"><a href="competition.php?tournament='.$tournament.'&competition='.$competition.'&stage=group&group='.$group_index.'" style="text-decoration: underline; color: black;" target="_blank">'.$group_index.' 组</a></b>
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
		            <td style="cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">
		            	&nbsp;<a style="text-decoration: none; color: '.$color.';"><img src="images/teams_small/'.$team_name.'.png" style="width: 16px; height: 16px; margin-top: 3px;"> '.$team_chinese_name.'</a>
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

	<?php
	include("competition_tabs/group_draw.php");
	include("competition_tabs/inter_group.php");
	?>

	<script type="text/javascript">
		function open_group_draw() {
			document.getElementById("group_draw_modal").style.display = "block";
		}

		function open_inter_group() {
			document.getElementById("inter_group_modal").style.display = "block";
		}

		function close_group_draw_modal() {
        	document.getElementById("group_draw_modal").style.display = "none";
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
	</script>