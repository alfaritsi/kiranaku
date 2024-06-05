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
					<form role="form" id="form_realisasi_penutupan_depo_detail" enctype="multipart/form-data">
						<div class="modal-header">
							<h4 class="modal-title" id="myModalLabel">Detail Realisasi Penutupan Depo</h4>
						</div>
						<div class="modal-body">
							<div class="tab-content">
								<div class="row">
									<div class="col-xs-6">
										<label for="id_depo_master">Nama Depo</label>
										<input type="text" class="form-control" name="nama_depo" id="nama_depo" value="" placeholder="Nama Depo" required="required" readonly>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label> Nomor</label>
											<input type="text" class="form-control" name="nomor" id="nomor" value="<?php echo $nomor;?>" placeholder="Nomor" required="required" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<label for="jenis_depo">Jenis Depo</label>
										<input style="text-transform: uppercase" type="text" class="form-control" name="jenis_depo" id="jenis_depo" value="" placeholder="Jenis Depo" required="required" readonly>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label> Pabrik </label>
											<input type="text" class="form-control" name="pabrik" id="pabrik" value="" placeholder="Pabrik" required="required" readonly>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
										<fieldset class="fieldset-default">
											<legend class="text-left"><h4>Daftar Karyawan</h4></legend>
											<table class="table table-hover table-bordered table-sdm">
												<thead>
													<tr>
														<th class="text-center" rowspan="2" width="15%">Jabatan</th>
														<th class="text-center" rowspan="2" width="35%">NIK/ Nama</th>
														<th class="text-center" colspan="3">Rencana</th>
														<th class="text-center" colspan="3">Aktual</th>
													</tr>
													<tr>
														<th class="text-center">Perubahan Status Karyawan</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center">Tanggal Rencana</th>
														<th class="text-center">Perubahan Status Karyawan</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center">Tanggal Aktual</th>
													</tr>
												</thead>
												<tbody>
													<tr id="nodata">
														<td colspan="8">No data found</td>
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
											<table class="table table-hover table-bordered table-asset">
												<thead>
													<tr>
														<th class="text-center" rowspan="2" width="35%">Nama Asset</th>
														<th class="text-center" rowspan="2" width="10%">Jml</th>
														<th class="text-center" colspan="3">Rencana</th>
														<th class="text-center" colspan="3">Aktual</th>
													</tr>
													<tr>
														<th class="text-center">Keterangan</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center"  width="10%">Tanggal Rencana</th>
														<th class="text-center">Keterangan</th>
														<th class="text-center">Lokasi Pemindahan</th>
														<th class="text-center"  width="10%">Tanggal Aktual</th>
													</tr>
												</thead>
												<tbody>
													<tr id="nodata_asset">
														<td colspan="8">No data found</td>
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
											<table class="table table-hover table-bordered table-keuangan">
												<thead>
													<tr>
														<th class="text-center" rowspan="2">Jenis</th>
														<th class="text-center" rowspan="2" width="15%">Jumlah Saldo Tersisa</th>
														<th class="text-center" colspan="2">Rencana</th>
														<th class="text-center" colspan="2">Aktual</th>
													</tr>
													<tr>
														<th class="text-center" width="15%">Penyelesaian</th>
														<th class="text-center" width="10%">Tanggal Rencana</th>
														<th class="text-center" width="15%">Penyelesaian</th>
														<th class="text-center" width="10%">Tanggal Aktual</th>
													</tr>
												</thead>
												<tbody>
													<tr id="nodata_keuangan">
														<td colspan="6">No data found</td>
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
											<table class="table table-hover table-bordered table-bokar">
												<thead>
													<tr>
														<th class="text-center" rowspan="2" width="20%">Jumlah Tonase (KGB)</th>
														<th class="text-center" colspan="2">Rencana</th>
														<th class="text-center" colspan="2">Aktual</th>
													</tr>
													<tr>
														<th class="text-center">Penyelesaian</th>
														<th class="text-center">Tanggal Rencana</th>
														<th class="text-center">Penyelesaian</th>
														<th class="text-center">Tanggal Aktual</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type='hidden' class='form-control text-center' name='bokar_nama' value='bokar'/>
															<input type='text' class='angka form-control text-center' name='bokar_jumlah' placeholder="Jumlah Tonase (KGB)" required='required' />
														</td>
														<td>
															<input type='text' class='angka form-control text-center' name='bokar_penyelesaian_rencana' placeholder="Jumlah Tonase Penyelesaian" required='required' />
														</td>
														<td>
															<input type='text' class='form-control tanggal' name='bokar_tanggal_rencana' placeholder="Jumlah Tonase (KGB)" required='required' />
														</td>
														
														<td>
															<input type='text' class='angka form-control input-control text-center' name='bokar_penyelesaian_aktual' placeholder="Jumlah Tonase Penyelesaian" required='required' />
														</td>
														<td>
															<input type='text' class='form-control input-control tanggal' name='bokar_tanggal_aktual' placeholder="Jumlah Tonase (KGB)" required='required' />
														</td>
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
											<table class="table table-hover table-bordered table-lain">
												<thead>
													<tr>
														<th class="text-center" rowspan="2">Nama Item</th>
														<th class="text-center" rowspan="2" width="10%">Jumlah</th>
														<th class="text-center" colspan="2">Rencana</th>
														<th class="text-center" colspan="2">Aktual</th>
													</tr>
													<tr>
														<th class="text-center">Penyelesaian</th>
														<th class="text-center">Tanggal Rencana</th>
														<th class="text-center">Penyelesaian</th>
														<th class="text-center">Tanggal Aktual</th>
													</tr>
												</thead>
												<tbody>
													<tr id="nodata_lain">
														<td colspan="6">No data found</td>
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
						<input name="id_depo_master" type="hidden">
						<input name="status_akhir" type="hidden">
						<input name="level" type="hidden">
					</form>	
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/depo/realisasi/detail.js"></script>
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