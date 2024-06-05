<!--
/*
@application    : Attachment Accounting 
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<style type="text/css">
	.listframe {
		border: 1px solid #ccc;
		padding: 4px;
		min-height: 50px;
		background-color: #efe6e6;
	}
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="clearfix"></div>

						<form method="POST"
							  id="filterform"
							  action="<?php echo base_url() ?>accounting/transaction/upload/jurnal"
							  class="filter-transaction-upload-jurnal"
							  role="form">
							<div class="col-md-12"
								 style="margin-top: 20px; padding-left:0px;">
								<div class="col-md-3">
									<div class="form-group">
										<label>Plant</label>
										<select data-placeholder="Pilih Plant"
												name="filterpabrik"
												id="filterpabrik"
												class="form-control select2"
												style="width: 100%;"
												required>
											<option value=""></option>
											<?php
												foreach ($pabrik as $pabrik) {
													if ($pabrik->kode == $filterpabrik) {
														$selected = "selected";
													}
													else {
														$selected = "";
													}
													echo "<option value='" . $this->generate->kirana_encrypt($pabrik->kode) . "' " . $selected . ">" . $pabrik->nama . "</option>";
												}
											?>
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Tanggal Jurnal</label>
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text"
												   class="form-control datePicker"
												   style="padding: 10px;"
												   placeholder="dd.mm.yyyy"
												   id="filtertanggal"
												   name="filtertanggal"
												   value="<?php echo $filtertanggal; ?>"
												   readonly
												   required>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Account No.</label>
										<input type="text"
											   class="form-control"
											   style="padding: 10px;"
											   id="filteraccount"
											   name="filteraccount"
											   value="<?php echo $filteraccount; ?>"
											   autocomplete="off">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Doc No.</label>
										<input type="text"
											   class="form-control"
											   style="padding: 10px;"
											   id="filterdoc"
											   name="filterdoc"
											   value="<?php echo $filterdoc; ?>"
											   autocomplete="off">
									</div>
								</div>
								<div class="col-md-2">
									<?php
										$checked = "";
										if ($filternoupload != "") {
											$checked = "checked";
										}
										echo "<label class='checkbox' style='margin-top: 30px;'>";
										echo "<input type='checkbox' id='chknoupload' name='chknoupload' " . $checked . ">document not uploaded";
										echo "</label>";
									?>
								</div>
								<div class="col-md-1">
									<div class="form-group"
										 style="margin-top: 25px;">
										<button type="submit"
												class="btn btn-default"><i class="fa fa-search"></i> View
										</button>
										<!-- <a href="" class="btn btn-primary" name="upload" value="Upload"> <i class="fa fa-upload"></i> Upload </a> -->
									</div>
								</div>

							</div>
						</form>

					</div>
					<!-- /.box-header -->

					<div class="box-body">
						<legend style="font-size: 14px;"><label> List Data </label></legend>
						<table width="100%"
							   class="table table-bordered table-striped my-datatable-extends-order">
							<thead>
								<tr>
									<th class='text-center'>No</th>
									<th class='text-center'>Doc. No</th>
									<th class='text-center'>Header Text</th>
									<th class='text-center'>Reference</th>
									<th class='text-center'>G/L Account</th>
									<th class='text-center'>File</th>
									<th class='text-center'>Upload Date</th>
									<th class='text-center'>Transfer Date</th>
									<th class='text-center'>Info</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach ($jurnal as $key => $dt) {
										$no = $key + 1;
										echo "<tr>";
										echo "<td align='center'>" . $no . "</td>";
										echo "<td align='center'>" . $dt->no_doc . "</td>";
										echo "<td>" . $dt->text . "</td>";
										echo "<td>" . $dt->reff . "</td>";
										echo "<td>" . $dt->account . "</td>";
										echo "<td>";
										if ($dt->data != "" && $dt->data != "-" && !empty($dt->data)) {
											$data = explode("|", $dt->data);
											foreach ($data as $key => $file) {
												if ($file != "") {
													if (substr($file, 0, 3) != "img") {
														echo "<a href='" . base_url() . $file . '?' . time() . "' target='_blank' style='color:green;'> <i class='fa fa-file-pdf-o'></i> " . str_replace("assets/file/acc/uploadjurnal/", "", $file) . "</a><br/>";
													}
													else {
														echo "<a href='http://10.0.0.249/dev/kiranaku/home/pdfviewer.php?q=" . $file . '&' . time() . "' target='_blank' style='color:green;'> <i class='fa fa-file-pdf-o'></i> " . str_replace("img/acc/", "", $file) . "</a><br/>";
													}
												}
											}
										}
										else {
											echo $dt->remark;
										}
										echo "</td>";
										$uploaddate = empty($dt->upload_date) ? '' : $this->generate->generateDateFormat($dt->upload_date);
										echo "<td align='center'>" . $uploaddate . "</td>";
										echo "<td align='center'>" . $this->generate->generateDateFormat($dt->in_datefirst) . "</td>";
										echo "<td>" . $dt->info . "</td>";
										echo "<td align='center'>";
										if ($dt->checklist == NULL || $dt->checklist == "n") {
											echo "<div class='input-group-btn'>";
											echo "<button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
							                                class='fa fa-th-large'></span></button>";
											echo "<ul class='dropdown-menu pull-right'>";
											if (!empty($dt->remark)) {
												echo "<li><a href='javascript:void(0)' class='request' data-request='" . $this->generate->kirana_encrypt($dt->id) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-cloud-upload'></i> Re-upload Request</a></li>";
											}
											else {
												if ($dt->data != "" && $dt->data != "-" && !empty($dt->data)) {
													echo "<li><a href='javascript:void(0)' class='upload' data-upload='" . $this->generate->kirana_encrypt($dt->id) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-upload'></i> Upload Replacement </a></li>";
													echo "<li><a href='javascript:void(0)' class='add' data-add='" . $this->generate->kirana_encrypt($dt->id) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-plus-circle'></i> Add File Upload</a></li>";
												}
												else {
													echo "<li><a href='javascript:void(0)' class='upload' data-upload='" . $this->generate->kirana_encrypt($dt->id) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-upload'></i> Upload </a></li>";
												}
											}
											echo "</ul>";
											echo "</div>";
										}
										echo "</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade"
			 id="modal-form">
			<div class="modal-dialog"
				 style="width:500px;">
				<form role="form"
					  class="form-uploadjurnal">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button"
									class="close"
									data-dismiss="modal"
									aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title"> Form Upload Journal </h4>
						</div>
						<div class="modal-body"
							 style="min-height:200px;">
							<div class="row">
								<div class="col-md-12">
									<fieldset class="fieldset-primary">
										<legend class="text-center">Document Information</legend>
										<div class="form-group"
											 style="margin-bottom: 5px;">
											<label class="col-md-4">Doc. No.</label>
											<div class="col-md-8">
												<p class="form-control-static"
												   id="doc_no"></p>
											</div>
										</div>
										<div class="form-group"
											 style="margin-bottom: 5px;">
											<label class="col-md-4">Text</label>
											<div class="col-md-8">
												<p class="form-control-static"
												   id="text"></p>
											</div>
										</div>
										<div class="form-group"
											 style="margin-bottom: 5px;">
											<label class="col-md-4">Type</label>
											<div class="col-md-8">
												<p class="form-control-static"
												   id="tipe"></p>
											</div>
										</div>
										<div class="form-group"
											 style="margin-bottom: 5px;">
											<label class="col-md-4">Existing File</label>
											<div class="col-md-8">
												<div id="fileexist"
													 style="margin-bottom:10px;"></div>
											</div>
										</div>
										<div id="uploaddiv">
										</div>
										<div id="infodiv">
										</div>
									</fieldset>
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<div class="col-md-12">
								<input type="hidden"
									   name="id"
									   id="id"
									   style="width:100%">
								<input type="hidden"
									   name="action"
									   id="action"
									   style="width:100%">
								<button type="submit"
										name="action_btn"
										class="btn btn-primary"><i class="fa fa-save"></i> Save
								</button>
							</div>
						</div>
					</div>
				</form>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/accounting/transaction/upload_jurnal.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
	.small-box .icon {
		top: -13px;
	}
</style>
