	<?php
	$group = $_GET["group"];
	?>

	<br><br><br><br><br>
	<div class="section" id="section">

	</div>

	<script type="text/javascript">
		show_details();

		function show_details() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("section").innerHTML = xhttp.responseText;
				}
			};
			xhttp.open("POST", "new_competition_tabs/group/single_group.php", true);
			xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhttp.send("tournament=" + <?php echo '"'.$tournament.'"'; ?> + "&competition=" + <?php echo '"'.$competition.'"'; ?> + "&group=" + <?php echo '"'.$group.'"'; ?>);
		}

		function simu_match(tournament, competition, group_index, round, game) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					show_details();
				}
			};
			xhttp.open("POST", "new_competition_tabs/group/simu_match.php", true);
			xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhttp.send("tournament=" + tournament + "&competition=" + competition + "&group_index=" + group_index + "&round=" + round + "&game=" + game);
		}

		function change_group(new_group) {
			var tournament = <?php echo '"'.$tournament.'"' ?>;
			var competition = <?php echo '"'.$competition.'"' ?>;
			location.href = 'new_competition.php?tournament=' + tournament + '&competition=' + competition + '&stage=group&group=' + new_group;
		}
	</script>