<?php
include("../../includes/connection.php");
include("../../includes/group_rank.php");

$tournament = $_POST["tournament"];
$competition = $_POST["competition"];

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
?>

<div class="container">
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
		        for ($i = 0; $i < 3; $i++) {
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
	for ($i = 0; $i < 6; $i++) {
		$match = $matches[$i];
		$team1 = $match["team1"];
		$team2 = $match["team2"];
		$team1_chinese_name = get_team_chinese_name($conn, $team1);
		$team2_chinese_name = get_team_chinese_name($conn, $team2);

		echo '
	<div class="row" style="margin-top: 15px;" id="round'.$match["round"].'">
		<div class="col-xl-10 col-lg-15 col-sm-20" style="text-decoration: underline;">第 '.($i + 1).' 轮：</div>
		<div class="col-xxl-30 col-xl-40 col-lg-50 col-md-60 col-sm-80" onclick="simu_match(\''.$tournament.'\', '.$competition.', '.$match["game"].')">
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
        </div>
	</div>';
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