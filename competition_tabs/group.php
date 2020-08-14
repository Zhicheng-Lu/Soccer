	<?php
	$group = $_GET["group"];
	$matches = array();
	$sql = "SELECT * FROM ".$tournament." WHERE competition=".$competition." AND group_index='".$group."'";
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

	$colors = array();
	// top team in the group
	if ($tournament == "champions_league") array_push($colors, "red");
	else if ($tournament == "union_associations") array_push($colors, "blue");
	else array_push($colors, "#1A9361");
	// second team in the group
	$color = "black";
	$team_name = $teams[1]["team_name"];
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
	else if ($tournament == "union_associations") {
		if ($competition == 11) {
		    $sql = "SELECT * FROM union_associations WHERE competition=".$competition." AND round='1_16' AND team1=\"".$team_name."\"";
		    $result = $conn->query($sql);
		    while ($result->fetch_assoc()) {
		    	$color = "blue";
		    }
		}
		else {
		    $color = "blue";
		}
	}
	else $color = "#1A9361";
	array_push($colors, $color);
	// third team in the group
	if ($tournament == "champions_league" && $competition >= 15) array_push($colors, "#1A9361");
	else if ($tournament == "union_associations" && $competition >= 15) array_push($colors, "#1A9361");
	else array_push($colors, "black");
	// fourth team in the group
	array_push($colors, "black");

	// all the group indices
	$group_indices = array();
	for ($i = 65; $i < 86; $i++) {
		array_push($group_indices, chr($i));
	}
	if ($tournament == "union_associations" || $tournament == "winners_cup") {
		array_push($group_indices, "V");
		array_push($group_indices, "W");
		array_push($group_indices, "X");
		array_push($group_indices, "Y");
		array_push($group_indices, "Z");
		array_push($group_indices, "Z01");
	}
	if ($tournament == "winners_cup") {
		for ($i = 2; $i < 10; $i++) {
			array_push($group_indices, "Z0".$i);
		}
		for ($i = 10; $i < 23; $i++) {
			array_push($group_indices, "Z".$i);
		}
	}
	$size = sizeof($group_indices);
	foreach ($group_indices as $index=>$group_index) {
		if ($group_index == $_GET["group"]) {
			if ($index == 0) $prev = $group_indices[$size - 1];
			else $prev = $group_indices[$index - 1];
			if ($index == $size - 1) $next = "A";
			else $next = $group_indices[$index + 1];
		}
	}
	?>

	<br><br><br><br><br>
	<div class="section">
		<div class="container">
			<div class="row">
				<button class="col-xl-18 col-md-30 col-38" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="change_group('<?php echo $prev; ?>')"><< <?php echo $prev; ?> 组</button>
				<select class="col-xl-12 col-md-18 col-34 offset-5" onchange="change_group(this.value)">
					<?php
					foreach ($group_indices as $group_index) {
						if ($group_index == $_GET["group"]) $selected = " selected";
						else $selected = "";
						echo '
					<option value="'.$group_index.'"'.$selected.'>'.$group_index.' 组</option>';
					}
					?>
				</select>
				<button class="col-xl-18 col-md-30 col-38 offset-5" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="change_group('<?php echo $next; ?>')"><?php echo $next; ?> 组 >></button>
			</div>

			<br>
			<div class="row">
				<div class="col-xxl-50 col-xl-60 col-lg-70 col-md-80">
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
				        </tr>
				        <?php
				        for ($i = 0; $i < 4; $i++) {
				        	echo '
				        <tr id="line'.$i.'">
				        	<td id="team'.$i.'"></td>
				        	<td id="win'.$i.'" style="text-align: center;"></td>
				        	<td id="draw'.$i.'" style="text-align: center;"></td>
				        	<td id="lose'.$i.'" style="text-align: center;"></td>
				        	<td id="score'.$i.'" style="text-align: center;"></td>
				        	<td id="scored'.$i.'" style="text-align: center;"></td>
				        	<td id="difference'.$i.'" style="text-align: center;"></td>
				        	<td id="points'.$i.'" style="text-align: center;"></td>
				        </tr>';
				        }
				        ?>
					</table>
				</div>
			</div>

			<br>
			<?php
			for ($i = 0; $i < 12; $i++) {
				$match = $matches[$i];
				$team1 = $match["team1"];
				$team2 = $match["team2"];
				$team1_chinese_name = get_team_chinese_name($conn, $team1);
				$team2_chinese_name = get_team_chinese_name($conn, $team2);

				if ($match["game"] == 1) {
					echo '
			<div class="row" style="margin-top: 15px;" id="round'.$match["round"].'">
				<div class="col-xl-10 col-lg-15 col-sm-20" style="cursor: pointer; text-decoration: underline;" onclick="display_results('.($i / 2 + 1).')">第 '.($i / 2 + 1).' 轮：</div>';
					}

				echo '
				<div class="col-xxl-30 col-xl-40 col-lg-50 col-md-60 col-sm-80 '.($match["game"] == 2? "offset-sm-20":"").'">
                    <div class="row">
                        <div class="col-57" style="text-align: right;">
                            <a style="text-decoration: none; color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $match["team1"]).'\', '.$competition.')">
                                <img src="images/teams_small/'.$match["team1"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $match["team1"]).'
                            </a>
                        </div>
                        <div class="col-6 no-padding">
                            <a href="index.php?tab=history&team1='.$match["team1"].'&team2='.$match["team2"].'" target="_blank" style="color: black; text-decoration: none;">
                                <div class="row">
                                    <div class="col-30 offset-15 no-padding" style="text-align: center;;">'.$match["score1"].'</div>
                                    <div class="col-30 no-padding" style="text-align: center;"> - </div>
                                    <div class="col-30 no-padding" style="text-align: center;">'.$match["score2"].'</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-57" style="text-align: left;;">
                            <a style="text-decoration: none; color: black; cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $match["team2"]).'\', '.$competition.')">
                                '.get_team_chinese_name($conn, $match["team2"]).' <img src="images/teams_small/'.$match["team2"].'.png" style="width: 14px; height: 14px;">
                            </a>
                        </div>
                    </div>
                </div>';

                if ($match["game"] == 2) {
					echo '
			</div>';
				}
			}
			?>
		</div>
	</div>

	<script type="text/javascript">
	// record 12 matches in the group
	var matches = [];
	for (var i = 0; i < 6; i++) {
		matches[i] = [];
		for (var j = 0; j < 2; j++) {
			matches[i][j] = [];
		}
	}
	var competition = <?php echo '"'.$competition.'"' ?>;
	<?php
	foreach ($matches as $key=>$match) {
		echo '
	matches['.($match["round"] - 1).']['.($match["game"] - 1).'] = {"team1": "'.$match["team1"].'", "score1": "'.$match["score1"].'", "score2": "'.$match["score2"].'", "team2": "'.$match["team2"].'"};';
	}

	// color
	if ($tournament == "champions_league") {
		echo '
	var color = "red"';
	}
	else if ($tournament == "union_associations") {
		echo '
	var color = "blue"';
	}
	else {
		echo '
	var color = "#1A9361"';
	}
	?>

	// colors
	var colors = [];
	<?php
	for ($i = 0; $i < 4; $i++) {
		echo '
	colors.push("'.$colors[$i].'");
	';
	}
	?>

	// record 4 teams in the group
	var group_teams;
	var team_index;
	var max_round;

	display_results(6);

	// when user wants to change to another group
	function change_group(new_group) {
		var tournament = <?php echo '"'.$tournament.'"' ?>;
		var competition = <?php echo '"'.$competition.'"' ?>;
		location.href = 'competition.php?tournament=' + tournament + '&competition=' + competition + '&stage=group&group=' + new_group;
	}

	// call when display up to a round
	function display_results(round) {
		group_teams = [];
		team_index = [];
		max_round = round;

		<?php
		foreach ($teams as $key => $team) {
			echo '
		group_teams['.$key.'] = {"team_name": "'.$team["team_name"].'", "chinese_name": "'.get_team_chinese_name($conn, $team["team_name"]).'", "win": 0, "draw": 0, "lose": 0, "score": 0, "scored": 0, "difference": 0, "points": 0, "away_score": 0};
		team_index["'.$team["team_name"].'"] = '.$key;
		}
		?>

		update();
		rank();
		display();
	}


	// update the team results
	function update() {
		for (var i = 0; i < max_round; i++) {
			for (var j = 0; j < 2; j++) {
				var match = matches[i][j];
				if (match["score1"] == "" || match["score2"] == "") continue;
				var team1 = team_index[match["team1"]];
				var team2 = team_index[match["team2"]];
				var score1 = match["score1"] * 1;
				var score2 = match["score2"] * 1;
				group_teams[team1]["score"] += score1;
				group_teams[team1]["scored"] += score2;
				group_teams[team1]["difference"] += (score1 - score2);
				group_teams[team2]["score"] += score2;
				group_teams[team2]["away_score"] += score2;
				group_teams[team2]["scored"] += score1;
				group_teams[team2]["difference"] += (score2 - score1);

				if (score1 > score2) {
					group_teams[team1]["win"]++;
					group_teams[team2]["lose"]++;
					group_teams[team1]["points"] += 3;
				}
				else if (score1 < score2) {
					group_teams[team1]["lose"]++;
					group_teams[team2]["win"]++;
					group_teams[team2]["points"] += 3;
				}
				else {
					group_teams[team1]["draw"]++;
					group_teams[team2]["draw"]++;
					group_teams[team1]["points"]++;
					group_teams[team2]["points"]++;
				}
			}
		}
	}

	// rank the teams
	function rank() {
		group_teams = group_teams.sort(cmp);

		if (group_teams[0]["points"] == group_teams[1]["points"] && group_teams[1]["points"] != group_teams[2]["points"]) {
			compare_two_teams(0, 1);
		}
		if (group_teams[0]["points"] != group_teams[1]["points"] && group_teams[1]["points"] == group_teams[2]["points"] && group_teams[2]["points"] != group_teams[3]["points"]) {
			compare_two_teams(1, 2);
		}
		if (group_teams[1]["points"] != group_teams[2]["points"] && group_teams[2]["points"] == group_teams[3]["points"]) {
			compare_two_teams(2, 3);
		}
		if (group_teams[0]["points"] == group_teams[1]["points"] && group_teams[1]["points"] == group_teams[2]["points"] && group_teams[2]["points"] != group_teams[3]["points"]) {
			compare_three_teams(0, 1, 2);
		}
		if (group_teams[0]["points"] != group_teams[1]["points"] && group_teams[1]["points"] == group_teams[2]["points"] && group_teams[2]["points"] == group_teams[3]["points"]) {
			compare_three_teams(1, 2, 3);
		}

		// update index
		for (var i = 0; i < 4; i++) {
			team_index[group_teams[i]["team_name"]] = i;
		}
	}

	// actually display the group results up to max round
	function display() {
		for (var i = 0; i < 4; i++) {
			var team = group_teams[i];
			document.getElementById("team" + i).innerHTML = '<a style="cursor: pointer;" onclick="open_modal(\'' + team["team_name"].replace("'", "\\'") + '\', ' + competition + ')">&nbsp;<img src="images/teams_small/' + team["team_name"] + '.png" style="margin-top: 3px;" class="badge-small"> ' + team["chinese_name"] + "</a>";
			// only display colors when max round is 6
			if (max_round == 6) {
				document.getElementById('team' + i).style.color = colors[i];
			}
			else {
				document.getElementById('team' + i).style.color = "black";
			}

			var columns = ["win", "draw", "lose", "score", "scored", "difference", "points"];
			for (var j = 0; j < columns.length; j++) {
				var column = columns[j];
				document.getElementById(column + i).innerHTML = team[column];
			}
		}

		// default: all the black
		for (var i = 0; i < 6; i++) {
			document.getElementById('round' + (i + 1)).style.color = 'black';
			var tags = document.getElementById('round' + (i + 1)).getElementsByTagName('a');
			for (var j = 0; j < tags.length; j++) {
				tags[j].style.color = 'black';
			}
		}

		// make one line special color
		document.getElementById('round' + max_round).style.color = color;
		var tags = document.getElementById('round' + max_round).getElementsByTagName('a');
		for (var i = 0; i < tags.length; i++) {
			tags[i].style.color = color;
		}
	}

	// sort cmp function
	function cmp(team1, team2) {
		if (team1["points"] == team2["points"]) {
			if (team1["difference"] == team2["difference"]) {
				if (team1["score"] == team2["score"]) {
					return team2["away_score"] - team1["away_score"];
				}
				return team2["score"] - team1["score"];
			}
			return team2["difference"] - team1["difference"];
		}
		return team2["points"] - team1["points"];
	}

	// if two get the same points
	function compare_two_teams(index1, index2) {
		var team1 = group_teams[index1]["team_name"];
		var team2 = group_teams[index2]["team_name"];
		var score1 = 0;
		var away_score1 = 0;
		var score2 = 0;
		var away_score2 = 0;

		for (var i = 0; i < max_round; i++) {
			for (var j = 0; j < 2; j++) {
				var match = matches[i][j];
				if (match["team1"] == team1 && match["team2"] == team2 && match["score1"] != "" && match["score2"] != "") {
					score1 += match["score1"] * 1;
					score2 += match["score2"] * 1;
					away_score2 += match["score2"] * 1;
				}
				if (match["team1"] == team2 && match["team2"] == team1 && match["score1"] != "" && match["score2"] != "") {
					score1 += match["score2"] * 1;
					score2 += match["score1"] * 1;
					away_score1 += match["score2"] * 1;
				}
			}
		}

		// if swap required
		if (score2 > score1 || (score2 == score1 && away_score2 > away_score1)) {
			swap(index1, index2);
		}
	}

	// if three get the same points
	function compare_three_teams(index1, index2, index3) {
		var three_teams = [{"index": index1, "team_name": group_teams[index1]["team_name"], points: 0, difference: 0, score: 0, away_score: 0}, {"index": index2, "team_name": group_teams[index2]["team_name"], points: 0, difference: 0, score: 0, away_score: 0}, {"index": index3, "team_name": group_teams[index3]["team_name"], points: 0, difference: 0, score: 0, away_score: 0}];
		var combinations = [[0, 1], [1, 0], [0, 2], [2, 0], [1, 2], [2, 1]];

		for (var i = 0; i < max_round; i++) {
			for (var j = 0; j < 2; j++) {
				var match = matches[i][j];
				for (var k = 0; k < 6; k++) {
					var combination = combinations[k];
					var team_index1 = combination[0];
					var team_index2 = combination[1];
					var team1 = three_teams[team_index1]["team_name"];
					var team2 = three_teams[team_index2]["team_name"];
					if (match["team1"] == team1 && match["team2"] == team2 && match["score1"] != "" && match["score2"] != "") {
						var score1 = match["score1"] * 1;
						var score2 = match["score2"] * 1;
						if (score1 > score2) {
							three_teams[team_index1]["points"] += 3;
						}
						else if (score1 < score2) {
							three_teams[team_index2]["points"] += 3;
						}
						else {
							three_teams[team_index1]["points"]++;
							three_teams[team_index2]["points"]++;
						}
						three_teams[team_index1]["difference"] += (score1 - score2);
						three_teams[team_index2]["difference"] += (score2 - score1);
						three_teams[team_index1]["score"] += score1;
						three_teams[team_index2]["score"] += score2;
						three_teams[team_index2]["away_score"] += score2;
					}
				}
			}
		}

		var swap = [];
		swap[index1] = group_teams[index1];
		swap[index2] = group_teams[index2];
		swap[index3] = group_teams[index3];
		three_teams.sort(cmp);
		group_teams[index1] = swap[three_teams[0]["index"]];
		group_teams[index2] = swap[three_teams[1]["index"]];
		group_teams[index3] = swap[three_teams[2]["index"]];
	}

	// swap two teams
	function swap(index1, index2) {
		var swap = group_teams[index1];
		group_teams[index1] = group_teams[index2];
		group_teams[index2] = swap;
	}
	</script>