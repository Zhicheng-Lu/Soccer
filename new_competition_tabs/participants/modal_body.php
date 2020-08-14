				<?php
				include("../../includes/connection.php");
				$tournament = $_POST["tournament"];
				$competition = $_POST["competition"];
				$counter = $_POST["counter"];
				$type1 = $_POST["type1"];
				$type2 = $_POST["type2"];
				$continent = $_POST["continent"];
				$country = $_POST["country"];

				// team in the current position
				$current_team_name = $attack = $middlefield = $defence = $home_plus = $points = "";
				$sql = 'SELECT * FROM participants WHERE tournament="'.$tournament.'" AND competition='.$competition.' AND team_index='.$counter;
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$current_team_name = $row["team_name"];
					$attack = $row["attack"];
					$middlefield = $row["middlefield"];
					$defence = $row["defence"];
					$home_plus = $row["home_plus"];
					$points = $row["points"];
				}

				// all countries in the current continent
				$countries = [];
				if ($type1 == "club") {
					$sql = 'SELECT * FROM countries WHERE country_continent="'.$continent.'"';
					$result = $conn->query($sql);
				    while ($row = $result->fetch_assoc()) {
					    array_push($countries, array("name"=>$row["country_name"], "chinese_name"=>$row["country_chinese_name"]));
				    }
				}
				
			    // all teams in the current continent/country
			    $teams = [];
			    if ($country == "") {
			    	$sql = 'SELECT * FROM teams AS T LEFT JOIN countries AS C ON T.team_nationality=C.country_name WHERE C.country_continent="'.$continent.'"';
			    }
			    else {
			    	$sql = 'SELECT * FROM teams WHERE team_nationality="'.$country.'"';
			    }
			    $result = $conn->query($sql);
			    while ($row = $result->fetch_assoc()) {
			    	if ($type1 == "nation" && $row["team_name"] == $row["team_nationality"]) {
			    		array_push($teams, array("name"=>$row["team_name"], "chinese_name"=>$row["team_chinese_name"]));
			    	}
				    if ($type1 == "club" && $row["team_name"] != $row["team_nationality"]) {
				    	array_push($teams, array("name"=>$row["team_name"], "chinese_name"=>$row["team_chinese_name"]));
				    }
			    }
				?>

				<div class="row">
					<div class="offset-xxl-10 offset-xl-5 offset-lg-0 col-lg-40 offset-sm-20 col-sm-80" style="margin-top: 20px; margin-bottom: 20px;">
						<div class="row">
						    <select onchange="select_country_function()" id="select_country" class="col-60" style="visibility: <?php echo (sizeof($countries)==0)? "hidden":"visible"; ?>;">
							    <option value="" disabled selected> - - 请选择国家 - - </option>
							    <?php
							    foreach ($countries as $country) {
									echo '
							    <option value="'.$country["name"].'" class="country_options">'.$country["chinese_name"].'</option>';
							    }
							    ?>
						    </select>
						    <select id="select_team" class="col-60">
							    <option value="" disabled selected> - - 请选择球队 - - </option>
							    <?php
							    foreach ($teams as $team) {
								    echo '
							    <option value="'.$team["name"].'" class="team_options"'.(($team["name"]==$current_team_name)? " selected":"").'>'.$team["chinese_name"].'</option>';
							    }
							    ?>
						    </select>
						</div>
				    </div>

				    <div class="offset-lg-5 col-xxl-55 col-xl-65 col-lg-70">
				    	<div class="row">
				    		<div class="col-24">进攻：<input value="<?php echo $attack;?>" id="attack" type="number" style="border: 1px solid #888888;"></div>
					    	<div class="col-24">中场：<input value="<?php echo $middlefield;?>" id="middlefield" type="number" style="border: 1px solid #888888;"></div>
					    	<div class="col-24">防守：<input value="<?php echo $defence;?>" id="defence" type="number" style="border: 1px solid #888888;"></div>
					    	<div class="col-24">主场：<input value="<?php echo $home_plus;?>" id="home_plus" type="number" style="border: 1px solid #888888;"></div>
					    	<div class="col-24">积分：<input value="<?php echo $points;?>" id="points" type="number" style="border: 1px solid #888888;"></div>
				    	</div>
				    </div>

				    <div class="offset-lg-40 col-lg-40 offset-md-30 col-md-60 offset-sm-20 col-sm-80">
				    	<button class="submit_button" onclick="confirm(<?php echo '\''.$tournament.'\', '.$competition.', '.$counter.', \''.$type1.'\', \''.$type2.'\', \''.$_POST["continent"].'\', \''.str_replace("'", "\'", $_POST["country"]).'\''; ?>)" style="width: 100%;">确认</button>
				    </div>
				</div>