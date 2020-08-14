	<?php
	function knockout_round($conn, $tournament, $competition, $title, $type1, $type2, $exp_num_teams, $win_type2, $win_start_index, $loss_tournament, $loss_type2, $loss_start_index) {
		if ($type2 == "") $round = $type1;
		else $round = $type1.'_'.$type2;
		echo '
			<div class="row">
				<button class="col-md-30 offset-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_draw_modal(\''.$tournament.'\', '.$competition.', \''.$title.'\', \''.$type1.'\', \''.$type2.'\', '.$exp_num_teams.', 0)">'.$title.'抽签</button>
				<button class="col-md-30 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="simu_all_matches(\''.$tournament.'\', '.$competition.', \''.$round.'\', \''.$win_type2.'\', '.$win_start_index.', \''.$loss_tournament.'\', \''.$loss_type2.'\', '.$loss_start_index.', '.$exp_num_teams.')">模拟剩余全部</button>
			</div>
			<div class="row" style="margin-bottom: 40px;" id="'.$round.'_games'.'">';
		draw_games($conn, $tournament, $competition, $round, $win_type2, $win_start_index, $loss_tournament, $loss_type2, $loss_start_index);
		echo '
			</div>';
	}
	?>

	<script type="text/javascript">
		function open_draw_modal(tournament, competition, title, type1, type2, exp_num_teams, auto_draw) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					// Typical action to be performed when the document is ready:
					document.getElementById("draw_modal_content").innerHTML = xhttp.responseText;
					document.getElementById("draw_modal").style.display = "block";

					ball_0 = document.getElementById("draw_ball_0");
					if (typeof(ball_0) != 'undefined' && ball_0 != null && auto_draw == 1) {
						ball_0.click();
					}
                }
            };
            xhttp.open("POST", "new_competition_tabs/knockout/<?php echo $stage; ?>_get_draw_teams.php", true);
            xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhttp.send("tournament=" + tournament + "&competition=" + competition + "&title=" + title + "&type1=" + type1 + "&type2=" + type2 + "&exp_num_teams=" + exp_num_teams + "&auto_draw=" + auto_draw);
		}

		function close_draw_modal() {
			// document.getElementById(modal_name + "_modal").style.display = "none";
			location.reload();
		}

		function close_chosen_team_modal() {
			document.getElementById("chosen_team_modal").style.display = "none";
		}

        function draw_new_team(tournament, competition, title, type1, type2, exp_num_teams, team_name, auto_draw) {
        	var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                   // Typical action to be performed when the document is ready:
                   document.getElementById("chosen_team_modal_body").innerHTML = xhttp.responseText;
                   document.getElementById("chosen_team_modal").style.display = "block";
                   if (auto_draw == 0) stop_time = 400;
                   else stop_time = 0;
                   setTimeout(function(){
                   	document.getElementById("chosen_team_modal").style.display = "none";
                   	open_draw_modal(tournament, competition, title, type1, type2, exp_num_teams, auto_draw);
                   }, stop_time);
                }
            };
            if (type2 == "") round = type1;
            else round = type1 + "_" + type2;
            xhttp.open("POST", "new_competition_tabs/knockout/draw_new_team.php", true);
            xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhttp.send("tournament=" + tournament + "&competition=" + competition + "&round=" + round + "&team_name=" + team_name);
        }

        function show_details(game_index) {
			boxes = document.getElementsByClassName("knockout_box_" + game_index);
			for (var i  = 0; i < boxes.length; i++) {

				box = boxes[i];
				if (box.classList.contains("d-none")) {
					box.classList.remove("d-none");
					box.classList.remove("d-md-block");
				}
				else {
					box.classList.add("d-none");
					box.classList.add("d-md-block");
				}
			}
		}

		function simu_match(tournament, competition, round, game, win_type2, win_start_index, win_index, loss_tournament, loss_type2, loss_start_index, loss_index, exp_auto) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById(round + "_games").innerHTML = xhttp.responseText;
					if (game < exp_auto) {
						game +=1;
						if (game%2 == 0) {
							win_index += 1;
							loss_index += 1;
						}
						simu_match(tournament, competition, round, game, win_type2, win_start_index, win_index, loss_tournament, loss_type2, loss_start_index, loss_index, exp_auto);
					}
				}
			};
			xhttp.open("POST", "new_competition_tabs/knockout/simu_match.php", true);
			xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhttp.send("tournament=" + tournament + "&competition=" + competition + "&round=" + round + "&game=" + game + "&win_type2=" + win_type2 + "&win_start_index=" + win_start_index + "&win_index=" + win_index + "&loss_tournament=" + loss_tournament + "&loss_type2=" + loss_type2 + "&loss_start_index=" + loss_start_index + "&loss_index=" + loss_index);
		}

		function simu_all_matches(tournament, competition, round, win_type2, win_start_index, loss_tournament, loss_type2, loss_start_index, exp_num_teams) {
			simu_match(tournament, competition, round, 0, win_type2, win_start_index, win_start_index, loss_tournament, loss_type2, loss_start_index, loss_start_index, exp_num_teams);
		}
	</script>

	<div id="draw_modal" class="modal" style="z-index: 9999;">
		<div class="modal-content col-lg-108 offset-lg-6 col-120" id="draw_modal_content">

		</div><!-- /.modal-content -->
	</div><!-- /.modal -->

	<div id="chosen_team_modal" class="modal" style="z-index: 19999; font-size: 40px">
		<div class="modal-content col-lg-108 offset-lg-6 col-120">
			<div class="modal-body" id="chosen_team_modal_body" onclick="close_chosen_team_modal()" style="height: 500px; text-align: center; vertical-align: middle;">
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->