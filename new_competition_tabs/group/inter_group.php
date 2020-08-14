	<?php
	include("new_competition_tabs/includes/inter_group_rank.php");
	$inter_group_rank = get_inter_group_rank($conn, $tournament, $competition);
	$first_place = $inter_group_rank[0];
	$second_place = $inter_group_rank[1];
	$third_place = $inter_group_rank[2];
	$fourth_place = $inter_group_rank[3];
	$width = "col-xxl-30 col-xl-40 col-md-60";
	?>

	<!-- The Modal -->
    <div id="inter_group_modal" class="modal" style="z-index: 9999;">
        <!-- Modal content -->
        <div class="modal-content col-lg-108 offset-lg-6 col-120">
        	<div class="modal-header">
        		<h3>小组间排名</h3>
                <span class="close" onclick="close_inter_group_modal()">&times;</span>
            </div>
        	<div class="modal-body" style="font-size: 13px;">
        		<div class="row">
        			<!-- 小组第一 -->
        			<div class="<?php echo $width; ?>" style="margin-top: 10px; margin-bottom: 10px;">
        				<table style="width: 100%;">
        					<tr><th colspan="6">小组第一</th></tr>
        					<tr>
	        					<th style="width: 11%;">名次</th>
								<th style="width: 11%;">组别</th>
								<th style="width: 45%;">球队</th>
								<th style="width: 11%;">积分</th>
								<th style="width: 11%;">净胜</th>
								<th style="width: 11%;">进球</th>
							</tr>
							<?php
							foreach ($first_place as $index => $team) {
								if ($tournament == "champions_league" && $index == 0) {
									echo '
							<tr><th colspan="6">混合冠军杯淘汰赛第一档次</th></tr>';
								}
								if ($tournament == "champions_league" && $index == 16) {
									echo '
							<tr><th colspan="6">混合冠军杯淘汰赛第二档次</th></tr>';
								}
								if ($tournament == "union_associations" && $index == 0) {
									echo '
							<tr><th colspan="6">混合联盟杯淘汰赛第一档次</th></tr>';
								}
								if ($tournament == "union_associations" && $competition == 11 && $index == 11) {
									echo '
							<tr><th colspan="6">混合联盟杯淘汰赛第二档次</th></tr>';
								}
								if ($tournament == "winners_cup" && $index == 0) {
									echo '
							<tr><th colspan="6">混合优胜者杯淘汰赛第一档次</th></tr>';
								}

								echo '
							<tr>
								<td style="text-align: center">'.($index + 1).'</td>
								<td style="text-align: center">'.$team["group_index"].'</td>
								<td style="cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $team["team_name"]).'</td>
								<td style="text-align: center">'.$team["point"].'</td>
								<td style="text-align: center">'.$team["difference"].'</td>
								<td style="text-align: center">'.$team["score"].'</td>
							</tr>';
							}
							?>
        				</table>
        			</div>

        			<!-- 小组第二 -->
        			<div class="<?php echo $width; ?>" style="margin-top: 10px; margin-bottom: 10px;">
        				<table style="width: 100%;">
        					<tr><th colspan="6">小组第二</th></tr>
        					<tr>
	        					<th style="width: 11%;">名次</th>
								<th style="width: 11%;">组别</th>
								<th style="width: 45%;">球队</th>
								<th style="width: 11%;">积分</th>
								<th style="width: 11%;">净胜</th>
								<th style="width: 11%;">进球</th>
							</tr>
							<?php
							foreach ($second_place as $index => $team) {
								if ($tournament == "champions_league" && $index == 0) {
									echo '
							<tr><th colspan="6">混合冠军杯淘汰赛第二档次</th></tr>';
								}
								if ($tournament == "champions_league" && $competition >= 11 && $index == 11) {
									echo '
							<tr><th colspan="6">混合联盟杯淘汰赛第一档次</th></tr>';
								}
								if ($tournament == "champions_league" && $competition >= 11 && $index == 16) {
									echo '
							<tr><th colspan="6">混合联盟杯淘汰赛第二档次</th></tr>';
								}
								if ($tournament == "champions_league" && $competition < 11 && $index == 11) {
									echo '
							<tr><th colspan="6">无</th></tr>';
								}
								if ($tournament == "union_associations" && $index == 0) {
									echo '
							<tr><th colspan="6">混合联盟杯淘汰赛第二档次</th></tr>';
								}
								if ($tournament == "union_associations" && $competition == 11 && $index == 1) {
									echo '
							<tr><th colspan="6">无</th></tr>';
								}
								if ($tournament == "winners_cup" && $index == 0) {
									echo '
							<tr><th colspan="6">混合优胜者杯淘汰赛第二档次</th></tr>';
								}

								echo '
							<tr>
								<td style="text-align: center">'.($index + 1).'</td>
								<td style="text-align: center">'.$team["group_index"].'</td>
								<td style="cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $team["team_name"]).'</td>
								<td style="text-align: center">'.$team["point"].'</td>
								<td style="text-align: center">'.$team["difference"].'</td>
								<td style="text-align: center">'.$team["score"].'</td>
							</tr>';
							}
							?>
        				</table>
        			</div>

        			<!-- 小组第三 -->
        			<div class="<?php echo $width; ?>" style="margin-top: 10px; margin-bottom: 10px;">
        				<table style="width: 100%;">
        					<tr><th colspan="6">小组第三</th></tr>
        					<tr>
	        					<th style="width: 11%;">名次</th>
								<th style="width: 11%;">组别</th>
								<th style="width: 45%;">球队</th>
								<th style="width: 11%;">积分</th>
								<th style="width: 11%;">净胜</th>
								<th style="width: 11%;">进球</th>
							</tr>
							<?php
							foreach ($third_place as $index => $team) {
								if ($tournament == "champions_league" && $competition >= 15 && $index == 0) {
									echo '
							<tr><th colspan="6">混合优胜者杯48强</th></tr>';
								}
								if ($tournament == "champions_league" && $competition >= 15 && $index == 13) {
									echo '
							<tr><th colspan="6">混合优胜者杯70强</th></tr>';
								}
								if ($tournament == "union_associations" && $competition >= 15 && $index == 0) {
									echo '
							<tr><th colspan="6">混合优胜者杯70强</th></tr>';
								}
								if ($tournament == "union_associations" && $competition >= 15 && $index == 1) {
									echo '
							<tr><th colspan="6">混合优胜者杯122强第一档次</th></tr>';
								}
								if ($tournament == "union_associations" && $competition >= 15 && $index == 14) {
									echo '
							<tr><th colspan="6">混合优胜者杯122强第二档次</th></tr>';
								}
								if ($tournament == "winners_cup" && $index == 0) {
									echo '
							<tr><th colspan="6">无</th></tr>';
								}
								if ($competition < 15 && $index == 0) {
									echo '
							<tr><th colspan="6">无</th></tr>';
								}

								echo '
							<tr>
								<td style="text-align: center">'.($index + 1).'</td>
								<td style="text-align: center">'.$team["group_index"].'</td>
								<td style="cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $team["team_name"]).'</td>
								<td style="text-align: center">'.$team["point"].'</td>
								<td style="text-align: center">'.$team["difference"].'</td>
								<td style="text-align: center">'.$team["score"].'</td>
							</tr>';
							}
							?>
        				</table>
        			</div>

        			<!-- 小组第四 -->
        			<div class="<?php echo $width; ?>" style="margin-top: 10px; margin-bottom: 10px;">
        				<table style="width: 100%;">
        					<tr><th colspan="6">小组第四</th></tr>
        					<tr>
	        					<th style="width: 11%;">名次</th>
								<th style="width: 11%;">组别</th>
								<th style="width: 45%;">球队</th>
								<th style="width: 11%;">积分</th>
								<th style="width: 11%;">净胜</th>
								<th style="width: 11%;">进球</th>
							</tr>
							<?php
							foreach ($fourth_place as $index => $team) {
								if ($index == 0) {
									echo '
							<tr><th colspan="6">无</th></tr>';
								}

								echo '
							<tr>
								<td style="text-align: center">'.($index + 1).'</td>
								<td style="text-align: center">'.$team["group_index"].'</td>
								<td style="cursor: pointer;" onclick="open_modal(\''.str_replace("'", "\'", $team["team_name"]).'\', '.$competition.')">&nbsp;<img src="images/teams_small/'.$team["team_name"].'.png" style="width: 14px; height: 14px;"> '.get_team_chinese_name($conn, $team["team_name"]).'</td>
								<td style="text-align: center">'.$team["point"].'</td>
								<td style="text-align: center">'.$team["difference"].'</td>
								<td style="text-align: center">'.$team["score"].'</td>
							</tr>';
							}
							?>
        				</table>
        			</div>
        		</div>
        	</div>
        </div>
    </div>