	<br><br><br><br><br>
	<div class="section">
		<div class="col-lg-108 offset-lg-6 col-120">
			<?php
			include("new_competition_tabs/knockout/round.php");
			if ($round == "final") {
				echo '
			<div class="row" id="final_games">';
				include("new_competition_tabs/final.php");
				echo '
			</div>';
			}
			elseif ($round == "finals") {
				include("new_competition_tabs/finals.php");
			}
			else {
				include("new_competition_tabs/knockout/games.php");
				$round = $_GET["round"];
				$round_chinese = "";
				if ($round == "1_32") {
					$round_chinese = "1/32 决赛";
					$exp_num_teams = 64;
				}
				if ($round == "1_16") {
					$round_chinese = "1/16 决赛";
					$exp_num_teams = 32;
				}
				if ($round == "1_8") {
					$round_chinese = "1/8 决赛";
					$exp_num_teams = 16;
				}
				if ($round == "1_4") {
					$round_chinese = "1/4 决赛";
					$exp_num_teams = 8;
				}
				if ($round == "semi_final") {
					$round_chinese = "半决赛";
					$exp_num_teams = 4;
				}
				if ($round == "122") {
					$round_chinese = "122 强";
					$exp_num_teams = 122;
				}
				if ($round == "70") {
					$round_chinese = "70 强";
					$exp_num_teams = 70;
				}
				if ($round == "48") {
					$round_chinese = "48 强";
					$exp_num_teams = 48;
				}
				if ($round == "24") {
					$round_chinese = "24 强";
					$exp_num_teams = 24;
				}
				if ($round == "12") {
					$round_chinese = "12 强";
					$exp_num_teams = 12;
				}
				if ($round == "6") {
					$round_chinese = "6 强";
					$exp_num_teams = 6;
				}

				knockout_round($conn, $tournament, $competition, $round_chinese, $round, "", $exp_num_teams, "", 0, "", "", 0);
			}
			?>
		</div>
	</div>