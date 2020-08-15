<form method="post" action="index.php" enctype="multipart/form-data">
	<div id="add_team_modal" class="modal" style="z-index: 9999;">
		<input type="hidden" name="tab" value="team">
		<div class="modal-content col-xl-50 offset-xl-35 col-md-90 offset-md-15 col-120">
			<div class="modal-header">
				<span class="close" onclick="close_modal('add_team_modal')">&times;</span>
			</div>
			<div class="modal-body justify-content-center">
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-50" style="text-align: right;">球队英文名：</div>
					<div class="col-70"><input type="text" name="team_name" required></div>
				</div>
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-50" style="text-align: right;">球队中文名：</div>
					<div class="col-70"><input type="text" name="team_chinese_name" required></div>
				</div>
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-50" style="text-align: right;">国家：</div>
					<div class="col-70">
						<select name="team_nationality" style="width: 161px;" required>
							<option value="" disabled selected></option>
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
				</div>
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-50" style="text-align: right;">队徽：</div>
					<div class="col-70">
						<input type="file" name="team_badge" onchange="preview_team_badge(this)" required>
						<img src="" id="team_badge_preview" style="height: 180px;">
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-center">
				<button class="col-xl-20 col-md-40 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" >确认</button>
				<button class="col-xl-20 col-md-40 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="close_modal('add_team_modal')">取消</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</form>

<script type="text/javascript">
	function preview_team_badge(input_img) {
		document.getElementById("team_badge_preview").src = URL.createObjectURL(input_img.files[0]);
	}
</script>