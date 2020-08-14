	<br><br><br><br><br>
	<div class="section">
		<div class="col-lg-108 offset-lg-6 col-120">
			<?php
			include("includes/draw_knockout.php");
			if ($tournament == "champions_league") {
				echo '
			<h2>俱乐部第一轮</h2>';
				draw_games($conn, $tournament, $competition, "club_qualifications_first");

				echo '
			<h2>俱乐部第二轮</h2>';
				draw_games($conn, $tournament, $competition, "club_qualifications_second");

				echo '
			<h2>国家队</h2>';
				draw_games($conn, $tournament, $competition, "nation_qualifications_first");
			}
			else if($tournament == "union_associations") {
				echo '
			<h2>混合冠军杯第一轮</h2>';
				draw_games($conn, $tournament, $competition, "club_leveldown_first");

				echo '
			<h2>混合冠军杯第二轮</h2>';
				draw_games($conn, $tournament, $competition, "club_leveldown_second");

				echo '
			<h2>俱乐部第一轮</h2>';
				draw_games($conn, $tournament, $competition, "club_qualifications_first");

				echo '
			<h2>俱乐部第二轮</h2>';
				draw_games($conn, $tournament, $competition, "club_qualifications_second");
			}
			else {
				echo '
			<h2>混合联盟杯第一轮</h2>';
				draw_games($conn, $tournament, $competition, "club_leveldown_first");

				echo '
			<h2>混合联盟杯第二轮</h2>';
				draw_games($conn, $tournament, $competition, "club_leveldown_second");

				echo '
			<h2>俱乐部</h2>';
				draw_games($conn, $tournament, $competition, "club_qualifications_first");

				echo '
			<h2>国家队</h2>';
				draw_games($conn, $tournament, $competition, "nation_qualifications_first");
			}
			?>
		</div>
	</div>