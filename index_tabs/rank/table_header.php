				<?php 
				if (!isset($_GET["tournament"])) {
					if (isset($_GET["nationality"])) {
						$basic_unit1 = 35;
						$basic_unit2 = 40;
						$basic_unit3 = 55;
						$columns = array(
							array("总名次", 50, "", "", "normal", "black", 0),
							array("名次", 35, "", "", "normal", "black", 0),
							array("球队", 140, "", "", "normal", "black", 0),
							array("积分", $basic_unit3, "", "", "normal", "black", 0),
							array("场次", $basic_unit1, "champions_league", "", "normal", "black", 0),
							array("胜", $basic_unit1, "champions_league", "win", "normal", "black", 0),
							array("平", $basic_unit1, "champions_league", "draw", "normal", "black", 0),
							array("负", $basic_unit1, "champions_league", "lose", "normal", "black", 0),
							array("小组", $basic_unit2, "champions_league", "group", "normal", "black", 0),
							array("32强", $basic_unit2, "champions_league", "1_16", "normal", "black", 0),
							array("16强", $basic_unit2, "champions_league", "1_8", "normal", "black", 0),
							array("8强", $basic_unit2, "champions_league", "1_4", "normal", "black", 0),
							array("4强", $basic_unit2, "champions_league", "semi_final", "normal", "black", 0),
							array("决赛", $basic_unit2, "champions_league", "final", "normal", "black", 0),
							array("冠军", $basic_unit2, "champions_league", "champion", "normal", "black", 0),
							array("积分", $basic_unit3, "champions_league", "points", "bold", "black", 0),
							array("场次", $basic_unit1, "union_associations", "", "normal", "black", 0),
							array("胜", $basic_unit1, "union_associations", "win", "normal", "black", 0),
							array("平", $basic_unit1, "union_associations", "draw", "normal", "black", 0),
							array("负", $basic_unit1, "union_associations", "lose", "normal", "black", 0),
							array("小组", $basic_unit2, "union_associations", "group", "normal", "black", 0),
							array("64强", $basic_unit2, "union_associations", "1_32", "normal", "black", 0),
							array("32强", $basic_unit2, "union_associations", "1_16", "normal", "black", 0),
							array("16强", $basic_unit2, "union_associations", "1_8", "normal", "black", 0),
							array("8强", $basic_unit2, "union_associations", "1_4", "normal", "black", 0),
							array("4强", $basic_unit2, "union_associations", "semi_final", "normal", "black", 0),
							array("决赛", $basic_unit2, "union_associations", "final", "normal", "black", 0),
							array("冠军", $basic_unit2, "union_associations", "champion", "normal", "black", 0),
							array("积分", $basic_unit3, "union_associations", "points", "bold", "black", 0),
							array("场次", $basic_unit1, "winners_cup", "", "normal", "black", 0),
							array("胜", $basic_unit1, "winners_cup", "win", "normal", "black", 0),
							array("平", $basic_unit1, "winners_cup", "draw", "normal", "black", 0),
							array("负", $basic_unit1, "winners_cup", "lose", "normal", "black", 0),
							array("小组", $basic_unit2, "winners_cup", "group", "normal", "black", 0),
							array("出线", $basic_unit2, "winners_cup", "122", "normal", "black", 0),
							array("70强", $basic_unit2, "winners_cup", "70", "normal", "black", 0),
							array("48强", $basic_unit2, "winners_cup", "48", "normal", "black", 0),
							array("24强", $basic_unit2, "winners_cup", "24", "normal", "black", 0),
							array("12强", $basic_unit2, "winners_cup", "12", "normal", "black", 0),
							array("6强", $basic_unit2, "winners_cup", "6", "normal", "black", 0),
							array("3强", $basic_unit2, "winners_cup", "finals", "normal", "black", 0),
							array("冠军", $basic_unit2, "winners_cup", "champion", "normal", "black", 0),
							array("积分", $basic_unit3, "winners_cup", "points", "bold", "black", 0),
							);
						echo '
				<tr>';
						for ($i = 0; $i < 4; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						echo '
					<th colspan="12">世界足球冠军杯</th>
					<th colspan="13">世界足球联盟杯</th>
					<th colspan="14">世界足球优胜者杯</th>
				</tr>
				<tr>';
						for ($i = 4; $i < sizeof($columns); $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px">'.$column[0].'</th>';
						}
						echo '
				</tr>';
					}
					else if (isset($_GET["continent"])) {
						$basic_unit1 = 42;
						$basic_unit2 = 33;
						$basic_unit3 = 52;
						$columns = array(
							array("总名次", 42, "", "", "normal", "black", 0),
							array("名次", 29, "", "", "normal", "black", 0),
							array("球队", 136, "", "", "normal", "black", 0),
							array("国籍", 114, "", "", "normal", "black", 0),
							array("积分", $basic_unit3, "", "", "normal", "black", 0),
							array("场次", $basic_unit1, "champions_league", "", "normal", "black", 0),
							array("胜", $basic_unit1, "champions_league", "win", "normal", "black", 0),
							array("平", $basic_unit1, "champions_league", "draw", "normal", "black", 0),
							array("负", $basic_unit1, "champions_league", "lose", "normal", "black", 0),
							array("小组", $basic_unit2, "champions_league", "group", "normal", "black", 0),
							array("32强", $basic_unit2, "champions_league", "1_16", "normal", "black", 0),
							array("16强", $basic_unit2, "champions_league", "1_8", "normal", "black", 0),
							array("8强", $basic_unit2, "champions_league", "1_4", "normal", "black", 0),
							array("4强", $basic_unit2, "champions_league", "semi_final", "normal", "black", 0),
							array("决赛", $basic_unit2, "champions_league", "final", "normal", "black", 0),
							array("冠军", $basic_unit2, "champions_league", "champion", "normal", "black", 0),
							array("积分", $basic_unit3, "champions_league", "points", "bold", "black", 0),
							array("场次", $basic_unit1, "union_associations", "", "normal", "black", 0),
							array("胜", $basic_unit1, "union_associations", "win", "normal", "black", 0),
							array("平", $basic_unit1, "union_associations", "draw", "normal", "black", 0),
							array("负", $basic_unit1, "union_associations", "lose", "normal", "black", 0),
							array("小组", $basic_unit2, "union_associations", "group", "normal", "black", 0),
							array("64强", $basic_unit2, "union_associations", "1_32", "normal", "black", 0),
							array("32强", $basic_unit2, "union_associations", "1_16", "normal", "black", 0),
							array("16强", $basic_unit2, "union_associations", "1_8", "normal", "black", 0),
							array("8强", $basic_unit2, "union_associations", "1_4", "normal", "black", 0),
							array("4强", $basic_unit2, "union_associations", "semi_final", "normal", "black", 0),
							array("决赛", $basic_unit2, "union_associations", "final", "normal", "black", 0),
							array("冠军", $basic_unit2, "union_associations", "champion", "normal", "black", 0),
							array("积分", $basic_unit3, "union_associations", "points", "bold", "black", 0),
							array("场次", $basic_unit1, "winners_cup", "", "normal", "black", 0),
							array("胜", $basic_unit1, "winners_cup", "win", "normal", "black", 0),
							array("平", $basic_unit1, "winners_cup", "draw", "normal", "black", 0),
							array("负", $basic_unit1, "winners_cup", "lose", "normal", "black", 0),
							array("小组", $basic_unit2, "winners_cup", "group", "normal", "black", 0),
							array("出线", $basic_unit2, "winners_cup", "122", "normal", "black", 0),
							array("70强", $basic_unit2, "winners_cup", "70", "normal", "black", 0),
							array("48强", $basic_unit2, "winners_cup", "48", "normal", "black", 0),
							array("24强", $basic_unit2, "winners_cup", "24", "normal", "black", 0),
							array("12强", $basic_unit2, "winners_cup", "12", "normal", "black", 0),
							array("6强", $basic_unit2, "winners_cup", "6", "normal", "black", 0),
							array("3强", $basic_unit2, "winners_cup", "finals", "normal", "black", 0),
							array("冠军", $basic_unit2, "winners_cup", "champion", "normal", "black", 0),
							array("积分", $basic_unit3, "winners_cup", "points", "bold", "black", 0),
							);
						echo '
				<tr>';
						for ($i = 0; $i < 5; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						echo '
					<th colspan="12">世界足球冠军杯</th>
					<th colspan="13">世界足球联盟杯</th>
					<th colspan="14">世界足球优胜者杯</th>
				</tr>
				<tr>';
						for ($i = 5; $i < sizeof($columns); $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px">'.$column[0].'</th>';
						}
						echo '
				</tr>';
					}
					else {
						$basic_unit1 = 35;
						$basic_unit2 = 35;
						$basic_unit3 = 55;
						$columns = array(
							array("名次", 35, "", "", "normal", "black", 0),
							array("球队", 144, "", "", "normal", "black", 0),
							array("国籍", 115, "", "", "normal", "black", 0),
							array("大洲", 51, "", "", "normal", "black", 0),
							array("积分", $basic_unit3, "", "", "normal", "black", 0),
							array("场次", $basic_unit1, "champions_league", "", "normal", "black", 0),
							array("胜", $basic_unit1, "champions_league", "win", "normal", "black", 0),
							array("平", $basic_unit1, "champions_league", "draw", "normal", "black", 0),
							array("负", $basic_unit1, "champions_league", "lose", "normal", "black", 0),
							array("小组", $basic_unit2, "champions_league", "group", "normal", "black", 0),
							array("32强", $basic_unit2, "champions_league", "1_16", "normal", "black", 0),
							array("16强", $basic_unit2, "champions_league", "1_8", "normal", "black", 0),
							array("8强", $basic_unit2, "champions_league", "1_4", "normal", "black", 0),
							array("4强", $basic_unit2, "champions_league", "semi_final", "normal", "black", 0),
							array("决赛", $basic_unit2, "champions_league", "final", "normal", "black", 0),
							array("冠军", $basic_unit2, "champions_league", "champion", "normal", "black", 0),
							array("积分", $basic_unit3, "champions_league", "points", "bold", "black", 0),
							array("场次", $basic_unit1, "union_associations", "", "normal", "black", 0),
							array("胜", $basic_unit1, "union_associations", "win", "normal", "black", 0),
							array("平", $basic_unit1, "union_associations", "draw", "normal", "black", 0),
							array("负", $basic_unit1, "union_associations", "lose", "normal", "black", 0),
							array("小组", $basic_unit2, "union_associations", "group", "normal", "black", 0),
							array("64强", $basic_unit2, "union_associations", "1_32", "normal", "black", 0),
							array("32强", $basic_unit2, "union_associations", "1_16", "normal", "black", 0),
							array("16强", $basic_unit2, "union_associations", "1_8", "normal", "black", 0),
							array("8强", $basic_unit2, "union_associations", "1_4", "normal", "black", 0),
							array("4强", $basic_unit2, "union_associations", "semi_final", "normal", "black", 0),
							array("决赛", $basic_unit2, "union_associations", "final", "normal", "black", 0),
							array("冠军", $basic_unit2, "union_associations", "champion", "normal", "black", 0),
							array("积分", $basic_unit3, "union_associations", "points", "bold", "black", 0),
							array("场次", $basic_unit1, "winners_cup", "", "normal", "black", 0),
							array("胜", $basic_unit1, "winners_cup", "win", "normal", "black", 0),
							array("平", $basic_unit1, "winners_cup", "draw", "normal", "black", 0),
							array("负", $basic_unit1, "winners_cup", "lose", "normal", "black", 0),
							array("小组", $basic_unit2, "winners_cup", "group", "normal", "black", 0),
							array("出线", $basic_unit2, "winners_cup", "122", "normal", "black", 0),
							array("70强", $basic_unit2, "winners_cup", "70", "normal", "black", 0),
							array("48强", $basic_unit2, "winners_cup", "48", "normal", "black", 0),
							array("24强", $basic_unit2, "winners_cup", "24", "normal", "black", 0),
							array("12强", $basic_unit2, "winners_cup", "12", "normal", "black", 0),
							array("6强", $basic_unit2, "winners_cup", "6", "normal", "black", 0),
							array("3强", $basic_unit2, "winners_cup", "finals", "normal", "black", 0),
							array("冠军", $basic_unit2, "winners_cup", "champion", "normal", "black", 0),
							array("积分", $basic_unit3, "winners_cup", "points", "bold", "black", 0),
							);
						echo '
				<tr>';
						for ($i = 0; $i < 5; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						echo '
					<th colspan="12">世界足球冠军杯</th>
					<th colspan="13">世界足球联盟杯</th>
					<th colspan="14">世界足球优胜者杯</th>
				</tr>
				<tr>';
						for ($i = 5; $i < sizeof($columns); $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px">'.$column[0].'</th>';
						}
						echo '
				</tr>';
					}
				}










				else if ($_GET["tournament"] == "champions_league") {
					if (isset($_GET["nationality"])) {
						$basic_unit1 = 43;
						$basic_unit2 = 60;
						$columns = array(
							array("总名次", 53, "", "", "normal", "black", 0),
							array("名次", 40, "", "", "normal", "black", 0),
							array("球队", 150, "", "", "normal", "black", 0),
							array("积分", 60, "", "", "normal", "black", 0),
							);
						$rounds = array(
							array("", "总计", array(0, 0, 0, 0, 0)),
							array("group", "小组赛", array(0, 0, 0, 0, 0)),
							array("1_16", "32 强", array(0, 0, 0, 0, 0)),
							array("1_8", "16 强", array(0, 0, 0, 0, 0)),
							array("1_4", "8 强", array(0, 0, 0, 0, 0)),
							array("semi_final", "4 强", array(0, 0, 0, 0, 0)),
							array("final", "决赛", array(0, 0, 0, 0, 0)),
							array("champion", "冠军", array(0, 0, 0, 0, 0)));
						echo '
				<tr>';
						for ($i = 0; $i < 4; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						foreach ($rounds as $round) {
							if ($round[0] == "") {
								echo '
					<th colspan="4">'.$round[1].'</th>';
							}
							else if ($round[0] == "champion") {
								echo '
					<th rowspan="2" style="width: '.$basic_unit2.'px;">'.$round[1].'</th>';
							}
							else {
								echo '
					<th colspan="5">'.$round[1].'</th>';
							}
						}
						echo '
				</tr>
				<tr>';
						foreach ($rounds as $round) {
							if ($round[0] != "" && $round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">次数</th>';
							}
							if ($round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">场次</th>
					<th style="width: '.$basic_unit1.'px;">胜</th>
					<th style="width: '.$basic_unit1.'px;">平</th>
					<th style="width: '.$basic_unit1.'px;">负</th>';
							}
						}
						echo '
				</tr>';
					}
					else if (isset($_GET["continent"])) {
						$basic_unit1 = 40;
						$basic_unit2 = 55;
						$columns = array(
							array("总名次", 52, "", "", "normal", "black", 0),
							array("名次", 36, "", "", "normal", "black", 0),
							array("球队", 147, "", "", "normal", "black", 0),
							array("国籍", 118, "", "", "normal", "black", 0),
							array("积分", 57, "", "", "normal", "black", 0),
							);
						$rounds = array(
							array("", "总计", array(0, 0, 0, 0, 0)),
							array("group", "小组赛", array(0, 0, 0, 0, 0)),
							array("1_16", "32 强", array(0, 0, 0, 0, 0)),
							array("1_8", "16 强", array(0, 0, 0, 0, 0)),
							array("1_4", "8 强", array(0, 0, 0, 0, 0)),
							array("semi_final", "4 强", array(0, 0, 0, 0, 0)),
							array("final", "决赛", array(0, 0, 0, 0, 0)),
							array("champion", "冠军", array(0, 0, 0, 0, 0)));
						echo '
				<tr>';
						for ($i = 0; $i < 5; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						foreach ($rounds as $round) {
							if ($round[0] == "") {
								echo '
					<th colspan="4">'.$round[1].'</th>';
							}
							else if ($round[0] == "champion") {
								echo '
					<th rowspan="2" style="width: '.$basic_unit2.'px;">'.$round[1].'</th>';
							}
							else {
								echo '
					<th colspan="5">'.$round[1].'</th>';
							}
						}
						echo '
				</tr>
				<tr>';
						foreach ($rounds as $round) {
							if ($round[0] != "" && $round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">次数</th>';
							}
							if ($round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">场次</th>
					<th style="width: '.$basic_unit1.'px;">胜</th>
					<th style="width: '.$basic_unit1.'px;">平</th>
					<th style="width: '.$basic_unit1.'px;">负</th>';
							}
						}
						echo '
				</tr>';
					}
					else {
						$basic_unit1 = 40;
						$basic_unit2 = 55;
						$columns = array(
							array("名次", 36, "", "", "normal", "black", 0),
							array("球队", 147, "", "", "normal", "black", 0),
							array("国籍", 118, "", "", "normal", "black", 0),
							array("大洲", 52, "", "", "normal", "black", 0),
							array("积分", 57, "", "", "normal", "black", 0),
							);
						$rounds = array(
							array("", "总计", array(0, 0, 0, 0, 0)),
							array("group", "小组赛", array(0, 0, 0, 0, 0)),
							array("1_16", "32 强", array(0, 0, 0, 0, 0)),
							array("1_8", "16 强", array(0, 0, 0, 0, 0)),
							array("1_4", "8 强", array(0, 0, 0, 0, 0)),
							array("semi_final", "4 强", array(0, 0, 0, 0, 0)),
							array("final", "决赛", array(0, 0, 0, 0, 0)),
							array("champion", "冠军", array(0, 0, 0, 0, 0)));
						echo '
				<tr>';
						for ($i = 0; $i < 5; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						foreach ($rounds as $round) {
							if ($round[0] == "") {
								echo '
					<th colspan="4">'.$round[1].'</th>';
							}
							else if ($round[0] == "champion") {
								echo '
					<th rowspan="2" style="width: '.$basic_unit2.'px;">'.$round[1].'</th>';
							}
							else {
								echo '
					<th colspan="5">'.$round[1].'</th>';
							}
						}
						echo '
				</tr>
				<tr>';
						foreach ($rounds as $round) {
							if ($round[0] != "" && $round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">次数</th>';
							}
							if ($round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">场次</th>
					<th style="width: '.$basic_unit1.'px;">胜</th>
					<th style="width: '.$basic_unit1.'px;">平</th>
					<th style="width: '.$basic_unit1.'px;">负</th>';
							}
						}
						echo '
				</tr>';
					}
				}










				else if ($_GET["tournament"] == "union_associations") {
					if (isset($_GET["nationality"])) {
						$basic_unit1 = 38;
						$basic_unit2 = 55;
						$columns = array(
							array("总名次", 50, "", "", "normal", "black", 0),
							array("名次", 35, "", "", "normal", "black", 0),
							array("球队", 143, "", "", "normal", "black", 0),
							array("积分", 60, "", "", "normal", "black", 0),
							);
						$rounds = array(
							array("", "总计", array(0, 0, 0, 0, 0)),
							array("group", "小组赛", array(0, 0, 0, 0, 0)),
							array("1_32", "64 强", array(0, 0, 0, 0, 0)),
							array("1_16", "32 强", array(0, 0, 0, 0, 0)),
							array("1_8", "16 强", array(0, 0, 0, 0, 0)),
							array("1_4", "8 强", array(0, 0, 0, 0, 0)),
							array("semi_final", "4 强", array(0, 0, 0, 0, 0)),
							array("final", "决赛", array(0, 0, 0, 0, 0)),
							array("champion", "冠军", array(0, 0, 0, 0, 0)));
						echo '
				<tr>';
						for ($i = 0; $i < 4; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						foreach ($rounds as $round) {
							if ($round[0] == "") {
								echo '
					<th colspan="4">'.$round[1].'</th>';
							}
							else if ($round[0] == "champion") {
								echo '
					<th rowspan="2" style="width: '.$basic_unit2.'px;">'.$round[1].'</th>';
							}
							else {
								echo '
					<th colspan="5">'.$round[1].'</th>';
							}
						}
						echo '
				</tr>
				<tr>';
						foreach ($rounds as $round) {
							if ($round[0] != "" && $round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">次数</th>';
							}
							if ($round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">场次</th>
					<th style="width: '.$basic_unit1.'px;">胜</th>
					<th style="width: '.$basic_unit1.'px;">平</th>
					<th style="width: '.$basic_unit1.'px;">负</th>';
							}
						}
						echo '
				</tr>';
					}
					else if (isset($_GET["continent"])) {
						$basic_unit1 = 35;
						$basic_unit2 = 51;
						$columns = array(
							array("总名次", 55, "", "", "normal", "black", 0),
							array("名次", 34, "", "", "normal", "black", 0),
							array("球队", 145, "", "", "normal", "black", 0),
							array("国籍", 120, "", "", "normal", "black", 0),
							array("积分", 55, "", "", "normal", "black", 0),
							);
						$rounds = array(
							array("", "总计", array(0, 0, 0, 0, 0)),
							array("group", "小组赛", array(0, 0, 0, 0, 0)),
							array("1_32", "64 强", array(0, 0, 0, 0, 0)),
							array("1_16", "32 强", array(0, 0, 0, 0, 0)),
							array("1_8", "16 强", array(0, 0, 0, 0, 0)),
							array("1_4", "8 强", array(0, 0, 0, 0, 0)),
							array("semi_final", "4 强", array(0, 0, 0, 0, 0)),
							array("final", "决赛", array(0, 0, 0, 0, 0)),
							array("champion", "冠军", array(0, 0, 0, 0, 0)));
						echo '
				<tr>';
						for ($i = 0; $i < 5; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						foreach ($rounds as $round) {
							if ($round[0] == "") {
								echo '
					<th colspan="4">'.$round[1].'</th>';
							}
							else if ($round[0] == "champion") {
								echo '
					<th rowspan="2" style="width: '.$basic_unit2.'px;">'.$round[1].'</th>';
							}
							else {
								echo '
					<th colspan="5">'.$round[1].'</th>';
							}
						}
						echo '
				</tr>
				<tr>';
						foreach ($rounds as $round) {
							if ($round[0] != "" && $round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">次数</th>';
							}
							if ($round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">场次</th>
					<th style="width: '.$basic_unit1.'px;">胜</th>
					<th style="width: '.$basic_unit1.'px;">平</th>
					<th style="width: '.$basic_unit1.'px;">负</th>';
							}
						}
						echo '
				</tr>';
					}
					else {
						$basic_unit1 = 35;
						$basic_unit2 = 51;
						$columns = array(
							array("名次", 34, "", "", "normal", "black", 0),
							array("球队", 145, "", "", "normal", "black", 0),
							array("国籍", 120, "", "", "normal", "black", 0),
							array("大洲", 55, "", "", "normal", "black", 0),
							array("积分", 55, "", "", "normal", "black", 0),
							);
						$rounds = array(
							array("", "总计", array(0, 0, 0, 0, 0)),
							array("group", "小组赛", array(0, 0, 0, 0, 0)),
							array("1_32", "64 强", array(0, 0, 0, 0, 0)),
							array("1_16", "32 强", array(0, 0, 0, 0, 0)),
							array("1_8", "16 强", array(0, 0, 0, 0, 0)),
							array("1_4", "8 强", array(0, 0, 0, 0, 0)),
							array("semi_final", "4 强", array(0, 0, 0, 0, 0)),
							array("final", "决赛", array(0, 0, 0, 0, 0)),
							array("champion", "冠军", array(0, 0, 0, 0, 0)));
						echo '
				<tr>';
						for ($i = 0; $i < 5; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						foreach ($rounds as $round) {
							if ($round[0] == "") {
								echo '
					<th colspan="4">'.$round[1].'</th>';
							}
							else if ($round[0] == "champion") {
								echo '
					<th rowspan="2" style="width: '.$basic_unit2.'px;">'.$round[1].'</th>';
							}
							else {
								echo '
					<th colspan="5">'.$round[1].'</th>';
							}
						}
						echo '
				</tr>
				<tr>';
						foreach ($rounds as $round) {
							if ($round[0] != "" && $round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">次数</th>';
							}
							if ($round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">场次</th>
					<th style="width: '.$basic_unit1.'px;">胜</th>
					<th style="width: '.$basic_unit1.'px;">平</th>
					<th style="width: '.$basic_unit1.'px;">负</th>';
							}
						}
						echo '
				</tr>';
					}
				}










				else if ($_GET["tournament"] == "winners_cup") {
					if (isset($_GET["nationality"])) {
						$basic_unit1 = 33;
						$basic_unit2 = 60;
						$columns = array(
							array("总名次", 60, "", "", "normal", "black", 0),
							array("名次", 43, "", "", "normal", "black", 0),
							array("球队", 150, "", "", "normal", "black", 0),
							array("积分", 60, "", "", "normal", "black", 0),
							);
						$rounds = array(
							array("", "总计", array(0, 0, 0, 0, 0)),
							array("group", "小组赛", array(0, 0, 0, 0, 0)),
							array("122", "122 强", array(0, 0, 0, 0, 0)),
							array("70", "70 强", array(0, 0, 0, 0, 0)),
							array("48", "48 强", array(0, 0, 0, 0, 0)),
							array("24", "24 强", array(0, 0, 0, 0, 0)),
							array("12", "12 强", array(0, 0, 0, 0, 0)),
							array("6", "6 强", array(0, 0, 0, 0, 0)),
							array("finals", "联合决赛", array(0, 0, 0, 0, 0)),
							array("champion", "冠军", array(0, 0, 0, 0, 0)));
						echo '
				<tr>';
						for ($i = 0; $i < 4; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						foreach ($rounds as $round) {
							if ($round[0] == "") {
								echo '
					<th colspan="4">'.$round[1].'</th>';
							}
							else if ($round[0] == "champion") {
								echo '
					<th rowspan="2" style="width: '.$basic_unit2.'px;">'.$round[1].'</th>';
							}
							else {
								echo '
					<th colspan="5">'.$round[1].'</th>';
							}
						}
						echo '
				</tr>
				<tr>';
						foreach ($rounds as $round) {
							if ($round[0] != "" && $round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">次数</th>';
							}
							if ($round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">场次</th>
					<th style="width: '.$basic_unit1.'px;">胜</th>
					<th style="width: '.$basic_unit1.'px;">平</th>
					<th style="width: '.$basic_unit1.'px;">负</th>';
							}
						}
						echo '
				</tr>';
					}
					else if (isset($_GET["continent"])) {
						$basic_unit1 = 31;
						$basic_unit2 = 52;
						$columns = array(
							array("总名次", 55, "", "", "normal", "black", 0),
							array("名次", 34, "", "", "normal", "black", 0),
							array("球队", 145, "", "", "normal", "black", 0),
							array("国籍", 120, "", "", "normal", "black", 0),
							array("积分", 55, "", "", "normal", "black", 0),
							);
						$rounds = array(
							array("", "总计", array(0, 0, 0, 0, 0)),
							array("group", "小组赛", array(0, 0, 0, 0, 0)),
							array("122", "122 强", array(0, 0, 0, 0, 0)),
							array("70", "70 强", array(0, 0, 0, 0, 0)),
							array("48", "48 强", array(0, 0, 0, 0, 0)),
							array("24", "24 强", array(0, 0, 0, 0, 0)),
							array("12", "12 强", array(0, 0, 0, 0, 0)),
							array("6", "6 强", array(0, 0, 0, 0, 0)),
							array("finals", "联合决赛", array(0, 0, 0, 0, 0)),
							array("champion", "冠军", array(0, 0, 0, 0, 0)));
						echo '
				<tr>';
						for ($i = 0; $i < 5; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						foreach ($rounds as $round) {
							if ($round[0] == "") {
								echo '
					<th colspan="4">'.$round[1].'</th>';
							}
							else if ($round[0] == "champion") {
								echo '
					<th rowspan="2" style="width: '.$basic_unit2.'px;">'.$round[1].'</th>';
							}
							else {
								echo '
					<th colspan="5">'.$round[1].'</th>';
							}
						}
						echo '
				</tr>
				<tr>';
						foreach ($rounds as $round) {
							if ($round[0] != "" && $round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">次数</th>';
							}
							if ($round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">场次</th>
					<th style="width: '.$basic_unit1.'px;">胜</th>
					<th style="width: '.$basic_unit1.'px;">平</th>
					<th style="width: '.$basic_unit1.'px;">负</th>';
							}
						}
						echo '
				</tr>';
					}
					else {
						$basic_unit1 = 31;
						$basic_unit2 = 52;
						$columns = array(
							array("名次", 34, "", "", "normal", "black", 0),
							array("球队", 145, "", "", "normal", "black", 0),
							array("国籍", 120, "", "", "normal", "black", 0),
							array("大洲", 55, "", "", "normal", "black", 0),
							array("积分", 55, "", "", "normal", "black", 0),
							);
						$rounds = array(
							array("", "总计", array(0, 0, 0, 0, 0)),
							array("group", "小组赛", array(0, 0, 0, 0, 0)),
							array("122", "122 强", array(0, 0, 0, 0, 0)),
							array("70", "70 强", array(0, 0, 0, 0, 0)),
							array("48", "48 强", array(0, 0, 0, 0, 0)),
							array("24", "24 强", array(0, 0, 0, 0, 0)),
							array("12", "12 强", array(0, 0, 0, 0, 0)),
							array("6", "6 强", array(0, 0, 0, 0, 0)),
							array("finals", "联合决赛", array(0, 0, 0, 0, 0)),
							array("champion", "冠军", array(0, 0, 0, 0, 0)));
						echo '
				<tr>';
						for ($i = 0; $i < 5; $i++) {
							$column = $columns[$i];
							echo '
					<th style="width: '.$column[1].'px" rowspan="2">'.$column[0].'</th>';
						}
						foreach ($rounds as $round) {
							if ($round[0] == "") {
								echo '
					<th colspan="4">'.$round[1].'</th>';
							}
							else if ($round[0] == "champion") {
								echo '
					<th rowspan="2" style="width: '.$basic_unit2.'px;">'.$round[1].'</th>';
							}
							else {
								echo '
					<th colspan="5">'.$round[1].'</th>';
							}
						}
						echo '
				</tr>
				<tr>';
						foreach ($rounds as $round) {
							if ($round[0] != "" && $round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">次数</th>';
							}
							if ($round[0] != "champion") {
								echo '
					<th style="width: '.$basic_unit1.'px;">场次</th>
					<th style="width: '.$basic_unit1.'px;">胜</th>
					<th style="width: '.$basic_unit1.'px;">平</th>
					<th style="width: '.$basic_unit1.'px;">负</th>';
							}
						}
						echo '
				</tr>';
					}
				}
				?>