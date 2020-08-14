	</header><!-- .site-header -->

	<?php
	include("includes/rank.php");
	function print_overall1($col_span, $columns) {
		echo '
				<tr>
					<td style="text-align: center;" colspan="'.$col_span.'"><b>合计</b></td>';

		for ($i = $col_span; $i < sizeof($columns); $i++) {
			$column = $columns[$i];
			if ($column[3] == "points") $text = "-";
			else if ($column[6] == 0) $text = "";
			else $text = $column[6];
			echo '
					<td style="text-align: center;">'.$text.'</td>';
		}

		echo '
				</tr>';
	}
	function print_overall2($col_span, $rounds) {
		echo '
				<tr>
					<td style="text-align: center;" colspan="'.$col_span.'"><b>合计</b></td>';

		foreach ($rounds as $round) {
			if ($round[0] == "champion") {
				echo '
					<td style="text-align: center; font-weight: bold; color: red;">'.($round[2][0]>0? $round[2][0]:"").'</td>';
			}
			else {
				if ($round[0] != "") {
					echo '
					<td style="text-align: center; font-weight: bold;">'.($round[2][1]>0? $round[2][0]:"").'</td>';
				}
				echo '
					<td style="text-align: center;">'.($round[2][1]>0? $round[2][1]:"").'</td>
					<td style="text-align: center;">'.($round[2][1]>0? $round[2][2]:"").'</td>
					<td style="text-align: center;">'.($round[2][1]>0? $round[2][3]:"").'</td>
					<td style="text-align: center;">'.($round[2][1]>0? $round[2][4]:"").'</td>';
			}
		}

		echo '
				</tr>';
	}
	?>

    <br><br><br><br><br>
    <div class="section">
    	<form action="index.php" method="get">
    		<input type="hidden" name="tab" value="rank">
	    	<div class="row" style="margin-bottom: 30px;">
	    		<div class="col-10 offset-20" style="margin-top: 10px;">
	                <select id="select_continent" name="continent" style="width: 100%;" onchange="change_continent()">
	                    <option value="" disabled selected> -- 选择大洲 -- </option>
	                    <option value=""></option>
	                    <option value="Europe">欧洲</option>
	                    <option value="South America">南美洲</option>
	                    <option value="North America">北美洲</option>
	                    <option value="Asia">亚洲</option>
	                    <option value="Africa">非洲</option>
	                    <option value="Oceania">大洋洲</option>
	                    <option value="Antarctica">南极洲</option>
	                </select>
	            </div>
	            <div class="col-10" style="margin-top: 10px;">
	                <select id="select_country" name="nationality" style="width: 100%;" onchange="change_country()">
	                    <option value="" disabled selected> -- 选择国家 -- </option>
	                    <option value=""></option>
	                    <?php
	                    $sql = "SELECT * FROM countries";
	                    $result = $conn->query($sql);
	                    while ($row = $result->fetch_assoc()) {
	                        echo '
	                    <option value="'.$row["country_name"].'">'.$row["country_chinese_name"].'</option>';
	                    }
	                    ?>
	                </select>
	            </div>
	            <div class="col-10 offset-5" style="margin-top: 10px;">
	                <select id="select_tournament" name="tournament" style="width: 100%;" onchange="change_tournament()">
	                    <option value="" disabled selected> -- 选择赛事 -- </option>
	                    <option value=""></option>
	                    <option value="champions_league">世界足球冠军杯</option>
	                    <option value="union_associations">世界足球联盟杯</option>
	                    <option value="winners_cup">世界足球优胜者杯</option>>
	                </select>
	            </div>
	            <div class="col-10 offset-5" style="margin-top: 10px;">
	                <select id="select_operand" name="operand" style="width: 100%;" onchange="change_operand()">
	                    <option value="" disabled selected> -- 选择届数 -- </option>
	                    <option value=""></option>
	                    <option value="equal_to">等于</option>
	                    <option value="less_than_or_equal_to">小于等于</option>
	                </select>
	            </div>
	            <div class="col-5" style="margin-top: 10px;">
	                <select id="select_competition" name="competition" style="width: 100%; visibility: hidden;">
	                	<option value=""></option>
						<?php
						$sql = 'SELECT DISTINCT competition FROM champions_league';
						$result = $conn->query($sql);
						while ($row = $result->fetch_assoc()) {
							echo '
						<option value="'.$row["competition"].'">'.$row["competition"].'</option>';
						}
						?>
	                </select>
	            </div>
	            <div class="col-10 offset-5">
	            	<button style="height: 40px; width: 100%; background-color: white; color: black; border-radius: 5px;">确认</button>
	            </div>
	            <div class="col-10">
	            	<button type="button" onclick="clear_input()" style="height: 40px; width: 100%; background-color: white; color: black; border-radius: 5px;">清空</button>
	            </div>
	    	</div>
	    </form>
    	<table style="margin-left: 38px; width: 1844px; font-size: 13px; table-layout: fixed;">
    		<thead style="display: block; border: none; width: 1844px; overflow-y: scroll">
	    		<?php
	    		include("index_tabs/rank/table_header.php");
	    		if (isset($_GET["tournament"])) {
					if ($_GET["tournament"] == "champions_league") usort($teams, "cmp2");
					if ($_GET["tournament"] == "union_associations") usort($teams, "cmp3");
					if ($_GET["tournament"] == "winners_cup") usort($teams, "cmp4");
				}
				else {
					usort($teams, "cmp5");
				}
				?>
			</thead>
			<tbody style="display: block; height: 630px; width: 1844px; overflow-y: scroll; border: 0px;">
				<?php
				$counter = 0;
				for ($i = 0; $i < count($teams); $i++) {
					$team = $teams[$i];
					if ($i % 2 == 0) $color = "#FFFFFF";
					else $color = "#EAEAEA";
					include("index_tabs/rank/table_content.php");
					echo '
				</td>';
				}
				if (!isset($_GET["tournament"])) {
					if (isset($_GET["nationality"])) print_overall1(4, $columns);
					else if (isset($_GET["continent"])) print_overall1(5, $columns);
				}
				else {
					if (isset($_GET["nationality"])) print_overall2(4, $rounds);
					else if (isset($_GET["continent"])) print_overall2(5, $rounds);
				}
				// space below
				for ($i = 0; $i < 24; $i++) {
					echo '
				<tr style="border: 0px;">
					<td style="border: 0px; color: white">空白</td>
				</tr>';
				}
	    		?>
    		</tbody>
    	</table>
    </div>

    <script type="text/javascript">
    	var countries = [];
        <?php
        $sql = "SELECT * FROM countries";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
        countries.push({name: "'.$row["country_name"].'", chinese_name: "'.$row["country_chinese_name"].'", continent: "'.$row["country_continent"].'"});';
        }

        if (isset($_GET["continent"])) {
        	echo '
        document.getElementById("select_continent").value = "'.$_GET["continent"].'";
        change_continent()';
        }
        if (isset($_GET["nationality"])) {
        	echo '
        document.getElementById("select_country").value = "'.$_GET["nationality"].'";';
        }
        if (isset($_GET["tournament"])) {
        	echo '
        document.getElementById("select_tournament").value = "'.$_GET["tournament"].'";';
        }
        if (isset($_GET["operand"])) {
        	echo '
        document.getElementById("select_operand").value = "'.$_GET["operand"].'";
        document.getElementById("select_competition").style.visibility = "visible";';
        }
        if (isset($_GET["competition"])) {
        	echo '
        document.getElementById("select_competition").value = "'.$_GET["competition"].'";';
        }
        ?>

    	function change_continent() {
            var value = document.getElementById("select_continent").value;
            if (value == "") document.getElementById("select_continent").value = "";
            document.getElementById("select_country").value = "";

            // filter options for country and team
            var country_drop_down = document.getElementById("select_country");
            for (var i = country_drop_down.length - 1; i >= 2; i--) {
                country_drop_down.remove(i);
            }
            for (var i = 0; i < countries.length; i++) {
                country = countries[i];
                if (country["continent"] == value || value == "") {
                    var option = document.createElement("option");
                    option.text = country["chinese_name"];
                    option.value = country["name"];
                    country_drop_down.add(option);
                }
            }
        }

        function change_country() {
        	var value = document.getElementById("select_country").value;
            if (value == "") document.getElementById("select_country").value = "";
        }

        function change_tournament() {
        	var value = document.getElementById("select_tournament").value;
            if (value == "") document.getElementById("select_tournament").value = "";
        }

        function change_operand() {
        	var value = document.getElementById("select_operand").value;
            if (value == "") {
            	document.getElementById("select_operand").value = "";
            	document.getElementById("select_competition").value = "";
            	document.getElementById("select_competition").style.visibility = "hidden";
            }
            else {
            	document.getElementById("select_competition").style.visibility = "visible";
            }
        }

        function clear_input () {
        	window.location = "index.php?tab=rank";
        }
    </script>