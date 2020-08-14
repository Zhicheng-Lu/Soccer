<?php
include("../../includes/connection.php");
include("../../includes/group_rank.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];
$group = $_POST["group"];

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
	if ($group_index == $group) {
		if ($index == 0) $prev = $group_indices[$size - 1];
		else $prev = $group_indices[$index - 1];
		if ($index == $size - 1) $next = "A";
		else $next = $group_indices[$index + 1];
	}
}
?>

<div class="container">
	<div class="row">
		<button class="col-xl-18 col-md-30 col-38" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="change_group('<?php echo $prev; ?>')"><< <?php echo $prev; ?> 组</button>
		<select class="col-xl-12 col-md-18 col-34 offset-5" onchange="change_group(this.value)">
			<?php
			foreach ($group_indices as $group_index) {
				if ($group_index == $group) $selected = " selected";
				else $selected = "";
				echo '
			<option value="'.$group_index.'"'.$selected.'>'.$group_index.' 组</option>';
			}
			?>
		</select>
		<button class="col-xl-18 col-md-30 col-38 offset-5" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="change_group('<?php echo $next; ?>')"><?php echo $next; ?> 组 >></button>
	</div>
	<div class="row" style="margin-top: 10px;">
		<button class="col-xl-58 col-md-88 col-120" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="simu_match(<?php echo '\''.$tournament.'\', '.$competition.', \''.$group.'\', \'\', -1'; ?>)">模拟剩余全部</button>
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
		        	<td id="team'.$i.'">&nbsp;<img src="images/teams_small/'.$teams[$i]["team_name"].'.png"> '.get_team_chinese_name($conn, $teams[$i]["team_name"]).'</td>
		        	<td id="win'.$i.'" style="text-align: center;">'.$teams[$i]["win"].'</td>
		        	<td id="draw'.$i.'" style="text-align: center;">'.$teams[$i]["draw"].'</td>
		        	<td id="lose'.$i.'" style="text-align: center;">'.$teams[$i]["lose"].'</td>
		        	<td id="score'.$i.'" style="text-align: center;">'.$teams[$i]["score"].'</td>
		        	<td id="scored'.$i.'" style="text-align: center;">'.$teams[$i]["concede"].'</td>
		        	<td id="difference'.$i.'" style="text-align: center;">'.$teams[$i]["difference"].'</td>
		        	<td id="points'.$i.'" style="text-align: center;">'.$teams[$i]["point"].'</td>
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
		<div class="col-xl-10 col-lg-15 col-sm-20" style="text-decoration: underline;">第 '.($i / 2 + 1).' 轮：</div>';
			}

		echo '
		<div class="col-xxl-30 col-xl-40 col-lg-50 col-md-60 col-sm-80 '.($match["game"] == 2? "offset-sm-20":"").'" onclick="simu_match(\''.$tournament.'\', '.$competition.', \''.$group.'\', \''.$match["round"].'\', '.$match["game"].')">
            <div class="row">
                <div class="col-57" style="text-align: right;">
                    <a style="text-decoration: none; color: black; cursor: pointer;" title="'.show_team_title($conn, $competition, $match["team1"]).'">
                        <img src="images/teams_small/'.$match["team1"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $match["team1"]).'
                    </a>
                </div>
                <div class="col-6 no-padding">
                    <div class="row" style="cursor: pointer;" title="'.show_match_title($conn, $competition, $match["team1"], $match["team2"]).'">
                        <div class="col-30 offset-15 no-padding" style="text-align: center;;">'.$match["score1"].'</div>
                        <div class="col-30 no-padding" style="text-align: center;"> - </div>
                        <div class="col-30 no-padding" style="text-align: center;">'.$match["score2"].'</div>
                    </div>
                </div>
                <div class="col-57" style="text-align: left;;">
                    <a style="text-decoration: none; color: black; cursor: pointer;" title="'.show_team_title($conn, $competition, $match["team2"]).'">
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

<?php
function show_team_title($conn, $competition, $team_name) {
	$sql = 'SELECT * FROM participants WHERE competition='.$competition.' AND team_name="'.$team_name.'"';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$attack = $row["attack"];
		$middlefield = $row["middlefield"];
		$defence = $row["defence"];
		$home_plus = $row["home_plus"];
	}
	return '进攻：'.$attack.'，中场：'.$middlefield.'，防守：'.$defence.'，主场：'.$home_plus;
}

function show_match_title($conn, $competition, $team1, $team2) {
	$sql = 'SELECT * FROM participants WHERE competition='.$competition.' AND team_name="'.$team1.'"';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$a1 = $row["attack"];
		$m1 = $row["middlefield"];
		$d1 = $row["defence"];
		$h1 = $row["home_plus"];
	}

	$sql = 'SELECT * FROM participants WHERE competition='.$competition.' AND team_name="'.$team2.'"';
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$a2 = $row["attack"];
		$m2 = $row["middlefield"];
		$d2 = $row["defence"];
		$h2 = $row["home_plus"];
	}

	$possibility1 = floor((($a1 + $h1 + 3) - ($d2 - $h2) + 40) * (($a1 + $h1 + 3) - ($d2 - $h2) + 40) / 12.5 * pow(1.12, ($m1 + $h1 + 3) / 10 - ($m2 - $h2) / 10));
	$possibility2 = floor((($a2 - $h2) - ($d1 + $h1 + 3) + 40) * (($a2 - $h2) - ($d1 + $h1 + 3) + 40) / 12.5 * pow(1.12, ($m2 - $h2) / 10 - ($m1 + $h1 + 3) / 10));
	return get_team_chinese_name($conn, $team1).'：'.$possibility1.'，'.get_team_chinese_name($conn, $team2).'：'.$possibility2;
}
?>