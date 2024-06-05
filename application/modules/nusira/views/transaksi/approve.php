<!--
/*
@application  : Nusira Workshop
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label> Plant: </label>
									<select class="form-control select2" multiple="multiple" name="plant_filter[]" style="width: 100%;" data-placeholder="Filter Plant">
										<?php
										foreach ($pabrik as $dt) {
											echo "<option value='" . $dt->plant . "'>" . $dt->plant_name . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label> Status: </label>
									<select class="form-control select2" multiple="multiple" name="status_filter[]" style="width: 100%;" data-placeholder="Filter Status">
										<?php
										echo "<option value='1'>Approved</option>";
										echo "<option value='0' selected>Waiting</option>";
										?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<!-- /.box-filter -->
					<div class="box-body">
						<table class="table table-bordered table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true">
							<thead>
								<tr>
									<th>Plant</th>
									<th>Nomor PI</th>
									<th>Perihal</th>
									<th>Tanggal</th>
									<th>Status PI</th>
									<th>Plan Delivery Date</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>

			<!--modal edit-->
			<div class="modal fade" id="approve_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<form role="form" class="form-transaksi-approve">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">Form Konfirmasi Delivery date</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<div class="row">
										<label for="plant" class="col-sm-2 control-label">Pabrik Pemesan</label>
										<div class="col-sm-4">
											<input type="text" class="form-control" name="plant" id="plant" placeholder="Plant" required="required" disabled>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<div class="row">
												<label for="nomor_pi" class="col-sm-4 control-label">Nomor PI</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="nomor_pi" id="nomor_pi" placeholder="Nomor PI" required="required" disabled>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="row">
												<label for="tanggal_pi" class="col-sm-4 control-label">Tanggal PI</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" name="tanggal_pi" id="tanggal_pi" placeholder="Tanggal PI" required="required" disabled>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id='show_detail'></div>
							</div>
							<div class="modal-footer">
								<input name="count" type="hidden">
								<input name="no_pi" type="hidden">
								<button id="btn_save" type="button" class="btn btn-primary" name="action_btn">Submit</button>
							</div>
						</form>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/transaksi/approve.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>

<style>
	.small-box .icon {
		top: -13px;
	}
</style>