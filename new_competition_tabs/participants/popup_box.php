	<script type="text/javascript">
		var teams = [];
        <?php
        $sql = "SELECT * FROM teams LEFT JOIN countries on teams.team_nationality=countries.country_name";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
        teams.push({name: "'.$row["team_name"].'", chinese_name: "'.$row["team_chinese_name"].'", nationality: "'.$row["team_nationality"].'", continent: "'.$row["country_continent"].'"});';
        }
        ?>

		function open_modal(tournament, competition, counter, type1, type2, continent, country) {
			var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                   // Typical action to be performed when the document is ready:
                   document.getElementById("modal_body").innerHTML = xhttp.responseText;
                   document.getElementById("select_team_modal").style.display = "block";
                }
            };
            xhttp.open("POST", "new_competition_tabs/participants/modal_body.php", true);
            xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhttp.send("tournament=" + tournament + "&competition=" + competition + "&counter=" + counter + "&type1=" + type1 + "&type2=" + type2 + "&continent=" + continent + "&country=" + country);
		}

		function close_modal() {
			document.getElementById("select_team_modal").style.display = "none";
		}

		// close modal when click outside of popup box
		window.onclick = function(event) {
        	var modals = document.getElementsByClassName("modal");
        	for (var i = modals.length - 1; i >= 0; i--) {
        		var modal = modals[i];
        		if (event.target == modal) {
                    modal.style.display = "none";
                }
        	}
        }

        function select_country_function() {
        	selected_country = document.getElementById("select_country").value;
        	// filter options when choosing another country
            var team_drop_down = document.getElementById("select_team");
            for (var i = team_drop_down.length - 1; i >= 1; i--) {
                team_drop_down.remove(i);
            }
            for (var i = 0; i < teams.length; i++) {
                team = teams[i];
                if (team["nationality"] == selected_country && team["name"] != team["nationality"]) {
                    var option = document.createElement("option");
                    option.text = team["chinese_name"];
                    option.value = team["name"];
                    team_drop_down.add(option);
                }
            }
            document.getElementById("select_team").value = "";
        }

        // when confirm adding a new team
        function confirm(tournament, competition, counter, type1, type2, continent, country) {
        	team = document.getElementById("select_team").value;
        	attack = document.getElementById("attack").value;
        	middlefield = document.getElementById("middlefield").value;
        	defence = document.getElementById("defence").value;
        	home_plus = document.getElementById("home_plus").value;
        	points = document.getElementById("points").value;

        	if (team != "" && attack != "" && middlefield != "" && defence != "" && home_plus != "" && points != "") {
        		var xhttp = new XMLHttpRequest();
	        	xhttp.onreadystatechange = function() {
	                if (this.readyState == 4 && this.status == 200) {
	                	// Typical action to be performed when the document is ready:
	                	document.getElementById("box_" + counter).innerHTML = xhttp.responseText;
	                	document.getElementById("select_team_modal").style.display = "none";
	                }
	            };
	            xhttp.open("POST", "new_competition_tabs/participants/add_team.php", true);
	            xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	            xhttp.send("tournament=" + tournament + "&competition=" + competition + "&counter=" + counter + "&type1=" + type1 + "&type2=" + type2 + "&continent=" + continent + "&country=" + country + "&team=" + team + "&attack=" + attack + "&middlefield=" + middlefield + "&defence=" + defence + "&home_plus=" + home_plus + "&points=" + points);
        	}
        	else {
        		alert("Please fill in all the blanks!");
        	}
        }
	</script>

	<style type="text/css">
		#select_team_modal {
        	z-index: 9999;
        }

        .submit_button {
			height: 40px;
			border-radius: 5px;
			font-size: 20px;
			background-color: white;
			margin-top: 40px;
		}
	</style>

	<div id="select_team_modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close" onclick="close_modal()">&times;</span>
			</div>
			<div class="modal-body" id="modal_body">
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->