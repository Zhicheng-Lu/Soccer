	<?php
	if ($tournament == "union_associations" && $competition != 11) {
		$font = 12;
	}
	else {
		$font = 14;
	}

	$sql = "SELECT * FROM ".$tournament." WHERE competition=".$competition." AND round='final'";
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$array1 = array(1=>array($row["team1"]));
		$array2 = array(1=>array($row["team2"]));
	}

	$sql = "SELECT * FROM ".$tournament." WHERE competition=".$competition." AND round='champion'";
	$result = $conn->query($sql);
	while ($row = $result->fetch_assoc()) {
		$champion = $row["team1"];
	}

	$number = 1;
	if ($tournament == "champions_league" || ($tournament == "union_associations" && $competition == 11)) {
	    $max = 8;
	}
	else {
	    $max = 16;
	}

	while ($number <= $max) {
		$current = $number * 2;
		$round = (($current == 2)? "semi_final" : "1_".$current);

		foreach ($array1[$number] as $key => $team) {
			$sql = $sql = "SELECT * FROM ".$tournament." WHERE competition=".$competition." AND round='".$round."' AND team1=\"".$team."\"";
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				if (isset($array1[$current])) {
					array_push($array1[$current], $team);
					array_push($array1[$current], $row["team2"]);
				}
				else {
					$array1[$current] = array();
					array_push($array1[$current], $team);
					array_push($array1[$current], $row["team2"]);
				}
			}
		}

		foreach ($array2[$number] as $key => $team) {
			$sql = $sql = "SELECT * FROM ".$tournament." WHERE competition=".$competition." AND round='".$round."' AND team1=\"".$team."\"";
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				if (isset($array2[$current])) {
					array_push($array2[$current], $team);
					array_push($array2[$current], $row["team2"]);
				}
				else {
					$array2[$current] = array();
					array_push($array2[$current], $team);
					array_push($array2[$current], $row["team2"]);
				}
			}
		}

		$number *= 2;
	}

	function draw($conn, $num_teams, $array1, $array2, $champion, $width1, $width2, $gap, $font) {
		$number = $num_teams;
		while ($number > 1) {
			echo_teams($conn, "left", $number, $array1, $array2, $champion, $width1, $font);
        	echo_lines("left", $number / 2, $width2);
        	$number /= 2;
		}
		echo_teams($conn, "left", 1, $array1, $array2, $champion, $width1, $font);
		echo '
	    <div style="height: 700px; width: '.$gap.'; display: inline-block;">

	    </div>';
	    echo_teams($conn, "right", 1, $array1, $array2, $champion, $width1, $font);
	    while ($number < $num_teams) {
	    	echo_lines("right", $number, $width2);
			echo_teams($conn, "right", $number * 2, $array1, $array2, $champion, $width1, $font);
        	$number *= 2;
		}
	}

	function echo_teams ($conn, $position, $number, $array1, $array2, $champion, $width, $font) {
		if ($position == "left") {
			$text_align = "right";
			$array = $array1[$number];
		}
		else {
			$text_align = "left";
			$array = $array2[$number];
		}

		$height = 720 / $number;
		echo '
	    <div style="width: '.$width.'; height: 720px; display: inline-block; vertical-align: top;">';

	        for ($i = 0; $i < $number; $i++) {
	        	$team = $array[$i];
		    	$sql = 'SELECT team_chinese_name FROM teams WHERE team_name="'.$team.'"';
		    	$result = $conn->query($sql);
		    	while ($row = $result->fetch_assoc()) {
		    		$team_chinese_name = $row["team_chinese_name"];
		    	}

		    	$color = "black";
		    	if ($i % 2 == 0) {
		    		if ($number == 1) {
		    			if ($team == $champion) {
		    				$color = "red";
		    			}
		    		}
		    		else {
		    			$color = "red";
		    		}
		    	}

	        	echo '
	        <div style="width: 100%; height: '.$height.'px; text-align: '.$text_align.'; line-height: '.$height.'px;">';

	            if ($position == "left") {
	            	echo '<a style="text-decoration: none; color: '.$color.';" href="#" onclick=\'open_modal("'.str_replace("'", "\'", $team).'", '.$_GET["competition"].')\'><img src="images/teams_small/'.$team.'.png" style="margin-top: 3px; height: '.($font + 3).'px; width: '.($font + 3).'px;" class="team_image"> '.$team_chinese_name.'</a>';
	            }
	            else {
	            	echo '<a style="text-decoration: none; color: '.$color.';" href="#" onclick=\'open_modal("'.str_replace("'", "\'", $team).'", '.$_GET["competition"].')\'>'.$team_chinese_name.' <img src="images/teams_small/'.$team.'.png" style="margin-top: 3px; height: '.($font + 3).'px; width: '.($font + 3).'px;" class="team_image"></a>';
	            }

	            echo '    
	        </div>';
	        }

	    echo '
	    </div>';
	}

	function echo_lines ($position, $number, $width) {
		echo '
	    <div style="width: '.$width.'; height: 720px; display: inline-block; vertical-align: top;">';

	        for ($i = 0; $i < $number; $i++) {
	        	echo '
	        <div style="width: 100%; height: '.(720 / $number).'px;">
	            <div style="width: 100%; height: 25%;"></div>';

	            if ($position == "left") {
	            	echo '
	            <div class="'.$position.'_circle">
	                <div style="width: 100%; height: 50%; border-top: 1px solid red; border-right: 1px solid red;"></div>
	                <div style="width: 100%; height: 50%; border-bottom: 1px solid black; border-right: 1px solid black;"></div>
	            </div>
	            <div class="line"></div>';
	            }
	            else {
	            	echo '
	            <div class="line"></div>
	            <div class="'.$position.'_circle">
	                <div style="width: 100%; height: 50%; border-top: 1px solid red; border-left: 1px solid red;"></div>
	                <div style="width: 100%; height: 50%; border-bottom: 1px solid black; border-left: 1px solid black;"></div>
	            </div>';
	            }
	            

	            echo '
	        </div>';
	        }

	    echo '
	    </div>';
	}
	?>

	<br><br><br><br><br>
	<div class="section" style="font-size: <?php echo $font ?>px;">
		<?php
		if ($tournament == "champions_league" || ($tournament == "union_associations" && $competition == 11)) {
			$width1 = "135px";
			$width2 = "50px";
			$gap = "50px";
			draw($conn, 16, $array1, $array2, $champion, $width1, $width2, $gap, $font);
		}
		else {
			$width1 = "122px";
			$width2 = "30px";
			$gap = "50px";
			draw($conn, 32, $array1, $array2, $champion, $width1, $width2, $gap, $font);
		}
		?>
	</div>

	<style type="text/css">
		.left_circle {
			width: 35%;
			height: 50%;
			display: inline-block;
		}

		.right_circle {
			width: 35%;
			height: 50%;
			display: inline-block;
		}
		.line {
			width: 50%;
			height: 25%;
			border-top: 1px solid red;
			display: inline-block;
		}
		.team_image {
			height: <?php echo ($font + 3) ?>px;
			width: <?php echo ($font + 3) ?>px;
		}
	</style>