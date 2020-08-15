<form method="post" action="index.php" enctype="multipart/form-data">
	<div id="add_country_modal" class="modal" style="z-index: 9999;">
		<input type="hidden" name="tab" value="team">
		<div class="modal-content col-xl-50 offset-xl-35 col-md-90 offset-md-15 col-120">
			<div class="modal-header">
				<span class="close" onclick="close_modal('add_country_modal')">&times;</span>
			</div>
			<div class="modal-body justify-content-center">
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-50" style="text-align: right;">国家英文名：</div>
					<div class="col-70"><input type="text" name="country_name" required></div>
				</div>
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-50" style="text-align: right;">国家中文名：</div>
					<div class="col-70"><input type="text" name="country_chinese_name" required></div>
				</div>
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-50" style="text-align: right;">大洲：</div>
					<div class="col-70">
						<select name="country_continent" style="width: 161px;" required>
							<option value="" disabled selected></option>
							<option value="Europe">欧洲</option>
							<option value="South America">南美洲</option>
							<option value="North America">北美洲</option>
							<option value="Asia">亚洲</option>
							<option value="Africa">非洲</option>
							<option value="Oceania">大洋洲</option>
						</select>
					</div>
				</div>
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-50" style="text-align: right;">国旗：</div>
					<div class="col-70">
						<input type="file" name="country_flag" onchange="preview_country_flag(this)" required>
						<img src="" id="country_flag_preview" style="height: 180px;">
					</div>
				</div>
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-50" style="text-align: right;">足协：</div>
					<div class="col-70">
						<input type="file" name="association_flag" onchange="preview_association_flag(this)" required>
						<img src="" id="association_flag_preview" style="height: 180px;">
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-center">
				<button class="col-xl-20 col-md-40 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" >确认</button>
				<button class="col-xl-20 col-md-40 col-60" style="height: 50px; background-color: white; color: black; border-radius: 5px; font-size: 16px; cursor: pointer;" onclick="close_modal('add_country_modal')">取消</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</form>

<script type="text/javascript">
	function preview_country_flag(input_img) {
		document.getElementById("country_flag_preview").src = URL.createObjectURL(input_img.files[0]);
	}

	function preview_association_flag(input_img) {
		document.getElementById("association_flag_preview").src = URL.createObjectURL(input_img.files[0]);
	}
</script>