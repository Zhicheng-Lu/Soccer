	</header><!-- .site-header -->

    <?php 
    include ("index_tabs/add/add_country.php");
    include ("index_tabs/add/add_team.php");
    ?>

    <br><br><br><br><br><br><br>
    <div class="row">
        <button class="col-xl-20 offset-xl-40 col-md-40 offset-md-20 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_add_country_modal()">添加国家</button>
        <button class="col-xl-20 col-md-40 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="open_add_team_modal()">添加球队</button>
    </div>

    <!-- filter and list of all the teams -->
    <div class="section">
        <div class="container">
        	<div class="row">
        		<div class="col-xl-40 offset-xl-40 col-md-80 offset-md-20 col-120">
        			<input id="team_name_input" type="search" style="width: 100%; margin-bottom: 15px; border: 1px solid #AAAAAA;" oninput="team_name_oninput()" value="">
        			<table style="width: 100%; cursor: pointer;">
        				<tr>
        					<th style="width: 60%;">英文名称</th>
        					<th style="width: 40%;">中文名称</th>
        				</tr>
        				<?php
        				$sql = "SELECT * FROM teams ORDER BY team_name ASC";
        				$result = $conn->query($sql);
        				$counter = 0;
						while ($row = $result->fetch_assoc()) {
							echo '
						<tr id="team_'.$counter.'" onclick="window.open(\'index.php?tab=team&team_name='.str_replace("'", "\'", $row["team_name"]).'\')">
							<td>'.$row["team_name"].'</td>
							<td><img src="images/teams_small/'.$row["team_name"].'.png" class="badge-small"> '.$row["team_chinese_name"].'</td>
						</tr>';
							$counter += 1;
						}
        				?>
        			</table>
        		</div>
        	</div>
        </div>
    </div>

    <script type="text/javascript">
    	var counter = <?php echo $counter; ?>;
    	var teams = [];
    	<?php
    	$sql = "SELECT * FROM teams ORDER BY team_name ASC";
        $result = $conn->query($sql);
    	while ($row = $result->fetch_assoc()) {
    		echo '
    	teams.push({team_name: "'.$row["team_name"].'", team_chinese_name: "'.$row["team_chinese_name"].'"});';
    	}
    	?>

        // filter while input
	    function team_name_oninput() {
	    	var input = document.getElementById("team_name_input").value;
	    	for (i = 0; i < counter;  i++) {
	    		if (teams[i]["team_chinese_name"].toLowerCase().includes(input.toLowerCase()) || teams[i]["team_name"].toLowerCase().includes(input.toLowerCase())) {
	    			document.getElementById("team_" + i).style.display = "table-row";
	    		}
	    		else {
	    			document.getElementById("team_" + i).style.display = "none";
	    		}
	    	}
	    }

        function open_add_country_modal() {
            document.getElementById("add_country_modal").style.display = "block";
        }

        function open_add_team_modal() {
            document.getElementById("add_team_modal").style.display = "block";
        }

        function close_modal(modal_name) {
            document.getElementById(modal_name).style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            var modals = document.getElementsByClassName("modal");
            for (var i = modals.length - 1; i >= 0; i--) {
                var modal = modals[i];
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }
    </script>