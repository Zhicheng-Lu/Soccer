	</header><!-- .site-header -->

    <br><br><br><br><br>
    <!-- filter and list of all the teams -->
    <div class="section">
        <div class="container">
        	<div class="row">
        		<div class="col-xl-40 offset-xl-40 col-md-80 offset-md-20 col-120">
        			<input id="team_name_input" type="search" style="width: 100%; margin-bottom: 30px; border: 1px solid #AAAAAA;" oninput="team_name_oninput()" value="">
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
    </script>