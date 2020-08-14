	<br><br><br><br><br>
	<div class="section">
		<div class="col-lg-108 offset-lg-6 col-120">
			<?php
			$round = $_GET["round"];
			if ($round != "semi_final" && $round != "final" && $round != "finals") {
				include("competition_tabs/knockout_draw.php");
			}
			include("includes/draw_knockout.php");
			if ($round == "final") include("competition_tabs/final.php");
			else if ($round == "finals") include("competition_tabs/finals.php");
			else draw_games($conn, $tournament, $competition, $round);
			?>
		</div>
	</div>