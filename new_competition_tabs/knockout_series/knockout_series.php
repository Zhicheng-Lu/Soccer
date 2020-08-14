	<?php
	function knockout_series($conn, $tournament, $competition, $title, $margin, $type1, $type2, $expected_num_teams, $win_type2, $loss_competition, $loss_type2) {
		echo '
			<div style="margin-top: '.$margin.'px;">';

		$button = "";

		$sql = 'SELECT COUNT(*) AS num_game FROM '.$tournament.' where competition='.$competition.' AND round="'.$type1.'_'.$type2.'"';
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) {
			$num_game = $row["num_game"];
		}

		if ($num_game == 0) {
			$num_participants = find_num_participants($conn, $tournament, $competition, $type1, $type2);
			if ($num_participants == $expected_num_teams) {
				$button = '<button class="click-button">抽签</button>';
			}
		}

		echo '
				<center><h2>'.$title.'</h2>'.$button.'</center>';

		echo '
			</div>';
	}

	function find_num_participants($conn, $tournament, $competition, $type1, $type2) {
		if ($type1 != "") {
			$sql = 'SELECT COUNT(*) AS num_participants FROM participants where tournament="'.$tournament.'" AND competition='.$competition.' AND type1="'.$type1.'" AND type2="'.$type2.'"';
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				$num_participants = $row["num_participants"];
			}
			return $num_participants;
		}
	}
	?>