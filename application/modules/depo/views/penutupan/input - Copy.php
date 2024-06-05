<!--
/*
@application  : MASTER DEPO
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/order/order.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="nav-tabs-custom" id="tabs-edit">
					<form role="form" id="form_penutupan_depo" enctype="multipart/form-data">
						<div class="modal-header">
							<h4 class="modal-title" id="myModalLabel">Penutupan Depo</h4>
						</div>
						<div class="modal-body">
							<div class="tab-content">
								<div class="row">
									<div class="col-xs-6">
										<label for="id_depo_master">Nama Depo</label>
										<select class="form-control select2 form-control-penutupan" name="id_depo_master" id="id_depo_master" data-placeholder="Nama Depo" required="required">
											<option ></option>
										</select>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label> Nomor</label>
											<input type="text" class="form-control" name="nomor" id="nomor" value="" placeholder="Nomor" required="required" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<label for="jenis_depo">Jenis Depo</label>
										<select class="form-control form-control-hide select2" name="jenis_depo" id="jenis_depo" required="required" data-placeholder="Pilih Jenis Depo" disabled>
											<?php
												echo "<option ></option>";
												echo "<option value='tetap'>Tetap</option>";
												echo "<option value='mitra'>Mitra</option>";
											?>
										</select>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label> Pabrik </label>
											<select class="form-control select2" id="pabrik" name="pabrik" style="width: 100%;" data-placeholder="Pilih Pabrik"  required="required" disabled>
												<?php
													if(!empty($user_role[0]->pabrik)){
														$arr_pabrik = explode(",", $user_role[0]->pabrik);
														echo "<option value='0'>Pilih Pabrik</option>";
														foreach ($arr_pabrik as $pabrik) {
															if($pabrik!=''){
																echo "<option value='$pabrik'>$pabrik</option>";
															}
														}
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
										<fieldset class="fieldset-default">
											<legend class="text-left"><h4>Daftar Karyawan</h4></legend>
											<button type="button" name="add_sdm" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
											<table class="table table-hover table-bordered table-sdm">
												<thead>
													<tr>
														<th class="text-center" rowspan="2" width="5%">NIK</th>
														<th class="text-center" rowspan="2" width="20%">Nama</th>
														<th class="text-center" rowspan="2" width="15%">Jabatan</th>
														<th class="text-center" colspan="4">Plan</th>
														<th class="text-center" colspan="4">Actual</th>
														<th class="text-center" rowspan="2" width="3%"></th>
													</tr>
													<tr>
														<th class="text-center">Perubahan Status Karyawan</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center">Tanggal Rencana</th>
														<th class="text-center">Disetujui Oleh</th>
														<th class="text-center">Perubahan Status Karyawan</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center">Tanggal Actual</th>
														<th class="text-center">Dievaluasi Oleh</th>
													</tr>
												</thead>
												<tbody>
													<tr id="nodata">
														<td colspan="13">No data found</td>
													</tr>
												</tbody>
											</table>
										</fieldset>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
										<fieldset class="fieldset-default">
											<legend class="text-left"><h4>Daftar Asset</h4></legend>
											<table class="table table-hover table-bordered table-detail">
												<thead>
													<tr>
														<th class="text-center" rowspan="2" width="5%">No</th>
														<th class="text-center" rowspan="2" width="20%">Nama Asset</th>
														<th class="text-center" rowspan="2" width="10%">Kode</th>
														<th class="text-center" rowspan="2" width="5%">Jml</th>
														<th class="text-center" colspan="4">Plan</th>
														<th class="text-center" colspan="4">Actual</th>
													</tr>
													<tr>
														<th class="text-center">Keterangan</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center">Tanggal Rencana</th>
														<th class="text-center">Disetujui Oleh</th>
														<th class="text-center">Perubahan Status Karyawan</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center">Tanggal Efektif</th>
														<th class="text-center">Dievaluasi Oleh</th>
													</tr>
												</thead>
												<tbody>
													<tr id="nodata_detail">
														<td colspan="13">No data found</td>
													</tr>
												</tbody>
											</table>
										</fieldset>		
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
										<fieldset class="fieldset-default">
											<legend class="text-left"><h4>Keuangan</h4></legend>
											<table class="table table-hover table-bordered table-detail">
												<thead>
													<tr>
														<th class="text-center" rowspan="2" width="20%">Jumlah Saldo Tersisa</th>
														<th class="text-center" colspan="3">Plan</th>
														<th class="text-center" colspan="3">Actual</th>
													</tr>
													<tr>
														<th class="text-center">Penyelesaian</th>
														<th class="text-center">Tanggal Rencana</th>
														<th class="text-center">Disetujui Oleh</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center">Tanggal Efektif</th>
														<th class="text-center">Dievaluasi Oleh</th>
													</tr>
												</thead>
												<tbody>
													<tr id="nodata_detail">
														<td colspan="13">No data found</td>
													</tr>
												</tbody>
											</table>
										</fieldset>			
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
										<fieldset class="fieldset-default">
											<legend class="text-left"><h4>Stok Bokar</h4></legend>
											<table class="table table-hover table-bordered table-detail">
												<thead>
													<tr>
														<th class="text-center" rowspan="2" width="20%">Jumlah Tonase (KGB)</th>
														<th class="text-center" colspan="3">Plan</th>
														<th class="text-center" colspan="3">Actual</th>
													</tr>
													<tr>
														<th class="text-center">Penyelesaian</th>
														<th class="text-center">Tanggal Rencana</th>
														<th class="text-center">Disetujui Oleh</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center">Tanggal Efektif</th>
														<th class="text-center">Dievaluasi Oleh</th>
													</tr>
												</thead>
												<tbody>
													<tr id="nodata_detail">
														<td colspan="13">No data found</td>
													</tr>
												</tbody>
											</table>
										</fieldset>				
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
										<fieldset class="fieldset-default">
											<legend class="text-left"><h4>Lain-Lain</h4></legend>
											<table class="table table-hover table-bordered table-detail">
												<thead>
													<tr>
														<th class="text-center" rowspan="2" width="20%">Jumlah Tonase (KGB)</th>
														<th class="text-center" rowspan="2" width="5%">Jumlah</th>
														<th class="text-center" colspan="3">Plan</th>
														<th class="text-center" colspan="3">Actual</th>
													</tr>
													<tr>
														<th class="text-center">Penyelesaian</th>
														<th class="text-center">Tanggal Rencana</th>
														<th class="text-center">Disetujui Oleh</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center">Tanggal Efektif</th>
														<th class="text-center">Dievaluasi Oleh</th>
													</tr>
												</thead>
												<tbody>
													<tr id="nodata_detail">
														<td colspan="13">No data found</td>
													</tr>
												</tbody>
											</table>
										</fieldset>	
										</div>
									</div>
								</div>
							<div class="clearfix"></div>
						</div>
						<div class="modal-footer">
							<button id="btn_decline" type="button" class="btn btn-danger" name="action_btn" value="decline">Decline</button>
							<button id="btn_approve" type="button" class="btn btn-primary" name="action_btn" value="approve">Approve</button>
						</div>
						<input name="status_akhir" type="text">
						<input name="level" type="text">
						<input name="id_depo_master" type="text">
						<input name="jenis_depo" type="text">
					</form>	
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/depo/penutupan/input.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>