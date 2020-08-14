	<style type="text/css">
		.click-button {
			height: 40px;
			border-radius: 5px;
			font-size: 20px;
			background-color: white;
			/*margin-top: 40px;*/
		}
	</style>

	<br><br><br><br><br>
	<div class="section">
		<div class="col-lg-108 offset-lg-6 col-120">
			<?php
			include("new_competition_tabs/knockout/round.php");
			include("new_competition_tabs/knockout/games.php");
			if ($tournament == "champions_league") {
				knockout_round($conn, $tournament, $competition, "俱乐部第一轮", "club", "qualifications_first", 48, "qualifications_second", 122, "union_associations", "leveldown_first", 12);
				knockout_round($conn, $tournament, $competition, "俱乐部第二轮", "club", "qualifications_second", 24, "finals", 148, "union_associations", "finals", 0);
				knockout_round($conn, $tournament, $competition, "国家队第一轮", "nation", "qualifications_first", 4, "finals", 146, "union_associations", "finals", 131);
			}
			else if($tournament == "union_associations") {
				knockout_round($conn, $tournament, $competition, "冠军杯第一轮", "club", "leveldown_first", 24, "leveldown_second", 159, "union_associations", "qualifications_first", 171);
				knockout_round($conn, $tournament, $competition, "冠军杯第二轮", "club", "leveldown_second", 12, "finals", 183, "union_associations", "qualifications_second", 189);
				knockout_round($conn, $tournament, $competition, "俱乐部第一轮", "club", "qualifications_first", 64, "qualifications_second", 195, "winners_cup", "leveldown_first", 19);
				knockout_round($conn, $tournament, $competition, "俱乐部第二轮", "club", "qualifications_second", 38, "finals", 227, "winners_cup", "finals", 0);
			}
			else {
				knockout_round($conn, $tournament, $competition, "联盟杯第一轮", "club", "leveldown_first", 32, "finals", 233, "winners_cup", "leveldown_second", 249);
				knockout_round($conn, $tournament, $competition, "联盟杯第二轮", "club", "leveldown_second", 16, "finals", 265, "winners_cup", "qualifications_first", 273);
				knockout_round($conn, $tournament, $competition, "俱乐部第一轮", "club", "qualifications_first", 80, "finals", 281, "", "", 0);
				knockout_round($conn, $tournament, $competition, "国家队第一轮", "nation", "qualifications_first", 2, "finals", 321, "", "", 0);
			}
			?>
		</div>
	</div>