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
					<form role="form" class="form-depo-input" enctype="multipart/form-data">
						<div class="modal-header">
							<h4 class="modal-title" id="myModalLabel">Form Master Depo</h4>
						</div>
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab-supplier" data-toggle="tab">Supplier</a></li>
							<li><a href="#tab-lingkungan" data-toggle="tab">Lingkungan</a></li>
							<li><a href="#tab-aktifitas" data-toggle="tab">Aktivitas Usaha</a></li>
							<li><a href="#tab-peta" data-toggle="tab">Peta Potensi Depo</a></li>
							<li><a href="#tab-biaya" data-toggle="tab">Biaya</a></li>
							<li><a href="#tab-dokumen" data-toggle="tab">Lampiran Dokumen</a></li>
						</ul>
						<div class="modal-body">
							<div class="tab-content">
								<!--dokumen-->
								<div class="tab-pane" id="tab-dokumen">
									<div class="row">
										<div class="col-sm-8">
											<div class="table-responsive">
												<table class="table table-hover table-bordered table-lampiran_dokumen">
													<thead>
														<tr>
															<th class="text-left" colspan="3">Lampiran Dokumen</th>
														</tr>
														<tr>
															<th class="text-center">Nama Dokumen</th>
															<th class="text-center" width="30%">Mandatory</th>
															<th class="text-center" width="30%">Upload File</th>
														</tr>
													</thead>
													<tbody>
														<tr id="nodata_lampiran_dokumen">
															<td colspan="10">No data found</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>	
								<!--biaya -->
								<div class="tab-pane" id="tab-biaya">
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>BIAYA OPERASIONAL DEPO PER BULAN</h4></legend>
										<!--all biaya depo mitra/tetap-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_biaya_depo" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-biaya_depo">
														<thead>
															<tr>
																<th class="text-left" colspan="5">Biaya Depo</th>
															</tr>
															<tr>
																<th class="text-center">Jenis Biaya Depo</th>
																<th class="text-center" width="10%">Rp</th>
																<th class="text-center" width="10%">Tonase (Kg Basah)</th>
																<th class="text-center" width="20%">Total (Rp/ Kg Basah)</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_biaya_depo">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<!--all biaya sdm-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_biaya_sdm" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-biaya_sdm">
														<thead>
															<tr>
																<th class="text-left" colspan="8">Biaya SDM</th>
															</tr>
															<tr>
																<th class="text-center">Jenis Biaya SDM</th>
																<th class="text-center" width="10%">Budget/ Unbudget</th>
																<th class="text-center" width="10%">NIK</th>
																<th class="text-center" width="20%">Nama</th>
																<th class="text-center" width="10%">Gaji Pokok Per Bulan</th>
																<th class="text-center" width="10%">Tunjangan Per Bulan</th>
																<th class="text-center" width="10%">Status</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_biaya_sdm">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</fieldset>								
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>BIAYA TRANSPORTASI</h4></legend>
										<!--all biaya transportasi darat-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_biaya_darat" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-biaya_darat">
														<thead>
															<tr>
																<th class="text-left" colspan="2">Biaya Transportasi Darat</th>
															</tr>
															<tr>
																<th class="text-left">Vendor Expedisi</th>
																<th class="text-center" width="10%">Penentuan Tarif</th>
																<th class="text-center" width="10%">Kapasitas Basah Max</th>
																<th class="text-center" width="10%">Rp Per Trip</th>
																<th class="text-center" width="10%">Rp Per KG</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_biaya_darat">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<!--all biaya transportasi air-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_biaya_air" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-biaya_air">
														<thead>
															<tr>
																<th class="text-left" colspan="2">Biaya Transportasi Air</th>
															</tr>
															<tr>
																<th class="text-left">Vendor Expedisi</th>
																<th class="text-center" width="10%">Kapasitas Basah Max</th>
																<th class="text-center" width="10%">Rp Per Trip</th>
																<th class="text-center" width="10%">Rp Per KG</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_biaya_air">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</fieldset>								
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>BIAYA INVESTASI</h4></legend>
										<!--all biaya investasi-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_biaya_investasi" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-biaya_investasi">
														<thead>
															<tr>
																<th class="text-center">Jenis Biaya Investasi</th>
																<th class="text-center" width="10%">Kepemilikan</th>
																<th class="text-center" width="10%">QTY</th>
																<th class="text-center" width="10%">Rp/ Unit</th>
																<th class="text-center" width="10%">Total (Rp)</th>
																<th class="text-center" width="15%">Keterangan</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_biaya_investasi">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</fieldset>								
								</div>	
								<!--peta-->
								<div class="tab-pane" id="tab-peta">
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>PETA POTENSI DEPO</h4></legend>
										<!--Data Desa-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_desa" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-desa">
														<thead>
															<tr>
																<th class="text-left" colspan="4">Data Potensi Bokar</th>
															</tr>
															<tr>
																<th class="text-center">Desa</th>
																<th class="text-center" width="15%">Luas Area (HA)</th>
																<th class="text-center" width="20%">Keterangan</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_desa">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<!--Data Survei-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_survei" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-survei">
														<thead>
															<tr>
																<th class="text-left" colspan="7">DATA YANG DIPEROLEH BERDASARKAN SURVEI</th>
															</tr>
															<tr>
																<th class="text-center">Tanggal</th>
																<th class="text-center" width="15%">Harga/ Hari (Lokasi Calon Depo)</th>
																<th class="text-center" width="15%">Harga Notarin Pabrik</th>
																<th class="text-center" width="15%">SICOM (Rp)</th>
																<th class="text-center" width="15%">Est. Total Peod. Cost Factory</th>
																<th class="text-center" width="15%">Rata-Rata (%)</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_survei">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<!--Data Target-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-hover table-bordered">
														<thead>
															<tr>
																<th class="text-left" colspan="7">TARGET BELI (TON KERING)</th>
															</tr>
															<tr>
																<th class="text-center">M1</th>
																<th class="text-center">M2</th>
																<th class="text-center">M3</th>
																<th class="text-center">M4</th>
																<th class="text-center">M5</th>
																<th class="text-center">M6</th>
																<th class="text-center">M7</th>
																<th class="text-center">M8</th>
																<th class="text-center">M9</th>
																<th class="text-center">M10</th>
																<th class="text-center">M11</th>
																<th class="text-center">M12</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td><input type='text' class='angka form-control text-center' name='target_m1' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m2' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m3' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m4' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m5' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m6' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m7' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m8' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m9' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m10' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m11' value='99999' required='required' /></td>
																<td><input type='text' class='angka form-control text-center' name='target_m12' value='99999' required='required' /></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</fieldset>								
								</div>	
								<!--supplier-->
								<div class="tab-pane active" id="tab-supplier">
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>DATA PRIBADI</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="jenis_depo">Jenis Depo</label>
													<select class="form-control form-control-hide select2" name="jenis_depo" id="jenis_depo" required="required" data-placeholder="Pilih Jenis Depo">
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
														<select class="form-control select2" id="pabrik" name="pabrik" style="width: 100%;" data-placeholder="Pilih Pabrik"  required="required">
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
										</div>											
										<div class="form-group">
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label> Nomor </label>
														<input type="text" class="form-control" name="nomor" id="nomor" placeholder="Nomor" required="required" readonly>
													</div>
												</div>
												<div class="col-xs-6">
													<label for="nama">Nama</label>
													<input style="text-transform: uppercase" type="text" class="form-control form-control-hide" name="nama" id="nama" value="99999" placeholder="Nama"  required="required">
												</div>
											</div>
										</div>	
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="nip">NIP</label>
													<input style="text-transform: uppercase" type="text" class="form-control form-control-hide" name="nip" id="nip" value="99999" placeholder="NIP"  required="required">
												</div>
												<div class="col-xs-6">
													<label for="npwp">NPWP</label>
													<input style="text-transform: uppercase" type="text" class="form-control form-control-hide" name="npwp" id="npwp" value="99999" placeholder="NPWP"  required="required">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label>Alamat Rumah</label>
													<textarea name="alamat_rumah" id="alamat_rumah" class="form-control form-control-hide" rows="3" placeholder="Masukan Alamat Rumah" required="required">99999</textarea>
												</div>
												<div class="col-xs-6">
													<label>Alamat Depo</label>
													<textarea name="alamat_depo" id="alamat_depo" class="form-control form-control-hide" rows="3" placeholder="Masukan Alamat Depo" required="required">99999</textarea>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="gps_depo">GPS Depo</label>
													<input style="text-transform: uppercase" type="text" class="form-control form-control-hide" name="gps_depo" id="gps_depo" value="99999" placeholder="GPS Depo"  required="required">
												</div>
												<div class="col-xs-6">
													<label for="pekerjaan">Pekerjaan</label>
													<input style="text-transform: uppercase" type="text" class="form-control form-control-hide" name="pekerjaan" id="pekerjaan" value="99999" placeholder="Pekerjaan"  required="required">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="status_kepemilikan_tanah">Status Kepemilikan Tanah</label>
													<select class="form-control form-control-hide select2" name="status_kepemilikan_tanah" id="status_kepemilikan_tanah" required="required"  data-placeholder="Pilih Status Kepemilikan">
														<?php
															echo "<option value='sendiri'>Milik Sendiri</option>";
															echo "<option value='keluarga'>Milik Keluarga</option>";
															echo "<option value='orang_lain'>Milik Orang Lain</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="status_sertifikat_tanah">Status Sertifikat Tanah</label>
													<select class="form-control form-control-hide select2" name="status_sertifikat_tanah" id="status_sertifikat_tanah" required="required"  data-placeholder="Pilih Status Sertifikat">
														<?php
															echo "<option value='sertifikat'>Sertifikat</option>";
															echo "<option value='non_sertifikat'>Non-Sertifikat</option>";
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="dana_pembelian_bokar">Dana Pembelian Bokar</label>
													<select class="form-control form-control-hide select2" name="dana_pembelian_bokar" id="dana_pembelian_bokar" required="required"  data-placeholder="Pilih Dana Pembelian Bokar">
														<?php
															echo "<option value='cek'>Cek</option>";
															echo "<option value='transfer'>Transfer</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="rekomendasi_oleh">Rekomendasi Oleh</label>
													<select class="form-control form-control-hide select2" name="rekomendasi_oleh" id="rekomendasi_oleh" required="required"  data-placeholder="Pilih Rekomendasi">
														<?php
															echo "<option value='ceo_region'>CEO Region</option>";
															echo "<option value='dirop'>Direktur Operational</option>";
															echo "<option value='manager_pabrik'>Manager Pabrik</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>JARAK DAN WAKTU</h4></legend>
										<!-- DETAIL FORM -->
										<!--jarak depo ke lokasi-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-hover table-bordered table-lokasi">
														<thead>
															<tr>
																<th class="text-center">Depo - Lokasi</th>
																<th class="text-center" width="10%">Jarak (KM)</th>
																<th class="text-center" width="10%">Waktu (JAM)</th>
																<th class="text-center" width="27%">Keterangan</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_lokasi">
																<td colspan="4">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<!--jarak depo ke depo km-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_depo" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-summary">
														<thead>
															<tr>
																<th class="text-center">Depo - Depo KMG</th>
																<th class="text-center" width="10%">Jarak (KM)</th>
																<th class="text-center" width="10%">Waktu (JAM)</th>
																<th class="text-center" width="20%">Keterangan</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<!--jarak depo ke gudang kompetitor-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_gudang" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-gudang">
														<thead>
															<tr>
																<th class="text-center">Depo - Gudang Kompetitor Terdekat</th>
																<th class="text-center" width="10%">Jarak (KM)</th>
																<th class="text-center" width="10%">Waktu (JAM)</th>
																<th class="text-center" width="20%">Keterangan</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_gudang">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<!--jarak depo ke pabrik kompetitor-->
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<button type="button" name="add_pabrik" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
													<table class="table table-hover table-bordered table-pabrik">
														<thead>
															<tr>
																<th class="text-center">Depo - Pabrik Kompetitor Terdekat</th>
																<th class="text-center" width="10%">Jarak (KM)</th>
																<th class="text-center" width="10%">Waktu (JAM)</th>
																<th class="text-center" width="20%">Keterangan</th>
																<th class="text-center" width="7%">&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_pabrik">
																<td colspan="10">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
								<!--lingkungan-->
								<div class="tab-pane" id="tab-lingkungan">
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>LINGKUNGAN SEKITAR DEPO</h4></legend>
										<div class="row">
											<div class="col-sm-12" border=="1">
												<div class="row katalog-product">
												</div>
											</div>
										</div>
									</fieldset>
								
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>INFRASTRUKTUR</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="luas_gudang">Luas Gudang</label>
													<div class="input-group">
														<input type="number" class="form-control form-control-hide" name="luas_gudang" id="luas_gudang" value="99999" placeholder="Luas Gudang"  required="required">
														<span class="input-group-addon">M<sup>2</sup></span>
													</div>												
												</div>
												<div class="col-xs-6">
													<label for="luas_tanah">Luas Tanah</label>
													<div class="input-group">
														<input type="number" class="form-control form-control-hide" name="luas_tanah" id="luas_tanah" value="99999" placeholder="Luas Tanah"  required="required">
														<span class="input-group-addon">M<sup>2</sup></span>
													</div>												
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="koneksi_internet">Dana Pembelian Bokar</label>
													<select class="form-control form-control-hide select2" name="koneksi_internet" id="koneksi_internet" required="required"  data-placeholder="Pilih Koneksi">
														<?php
															echo "<option value='modem'>Modem</option>";
															echo "<option value='internet_kabel'>Internet Kabel</option>";
															echo "<option value='smartphone'>Smartphone</option>";
															echo "<option value='tidak_ada'>Tidak Ada</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="akses_jalan">Akses Jalan</label>
													<select class="form-control form-control-hide select2" name="akses_jalan" id="akses_jalan" required="required"  data-placeholder="Pilih Akses Jalan">
														<?php
															echo "<option value='tronton'>Dapat Dilalui Tronton</option>";
															echo "<option value='engkel'>Dapat Dilalui Engkel</option>";
															echo "<option value='colt'>Dapat Dilalui Colt Diesel</option>";
															echo "<option value='tidak_ada'>Tidak Ada</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
								<!--aktifitas-->
								<div class="tab-pane" id="tab-aktifitas">
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>AKTIVITAS PEMBELIAN BOKAR DENGAN PETANI/AGEN</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="kualitas_bokar">Kualitas Bokar</label>
													<select class="form-control form-control-hide select2" name="kualitas_bokar" id="kualitas_bokar" required="required"  data-placeholder="Pilih Kualitas Bokar">
														<?php
															echo "<option value='harian'>Harian</option>";
															echo "<option value='mingguan'>Mingguan</option>";
															echo "<option value='2_mingguan'>2 Mingguan</option>";
															echo "<option value='3_mingguan'>3 Mingguan</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="cara_penyimpanan">Cara Penyimpanan</label>
													<select class="form-control form-control-hide select2" name="cara_penyimpanan" id="cara_penyimpanan" required="required"  data-placeholder="Pilih Cara Penyimpanan">
														<?php
															echo "<option value='rendam'>Rendam</option>";
															echo "<option value='gudang'>Gudang</option>";
															echo "<option value='jemur'>Jemur</option>";
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="jenis_bokar">Jenis Bokar</label>
													<select class="form-control form-control-hide select2" name="jenis_bokar" id="jenis_bokar" required="required"  data-placeholder="Pilih Jenis Bokar">
														<?php
															echo "<option value='slab_tebal'>Slab Tebal</option>";
															echo "<option value='lump'>Lump</option>";
															echo "<option value='lain'>lain-Lain</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="jenis_pembayaran">Jenis Pembayaran</label>
													<select class="form-control form-control-hide select2" name="jenis_pembayaran" id="jenis_pembayaran" required="required"  data-placeholder="Pilih Jenis Pembayaran">
														<?php
															echo "<option value='cash'>Cash</option>";
															echo "<option value='cheque'>Cheque</option>";
															echo "<option value='transfer'>Transfer</option>";
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="pph_22">PPH 22</label>
													<select class="form-control form-control-hide select2" name="pph_22" id="pph_22" required="required"  data-placeholder="Pilih PPH 22">
														<?php
															echo "<option value='mengerti'>Mengerti</option>";
															echo "<option value='kurang_mengerti'>Kurang Mengerti</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="pengelola_keuangan">Pengelola Keuangan</label>
													<select class="form-control form-control-hide select2" name="pengelola_keuangan" id="pengelola_keuangan" required="required"  data-placeholder="Pilih Pengelola Keuangan">
														<?php
															echo "<option value='mitra'>Mitra</option>";
															echo "<option value='karyawan_kmg'>Karyawan KMG</option>";
															echo "<option value='tidak_ada'>Tidak Ada</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>							
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>AKTIVITAS USAHA MITRA</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="frekuensi_penjualan_mitra_per_minggu">Frekuensi Penjualan Mitra Per Minggu</label>
													<select class="form-control form-control-hide select2" name="frekuensi_penjualan_mitra_per_minggu" id="frekuensi_penjualan_mitra_per_minggu" required="required"  data-placeholder="Pilih Frekuensi">
														<?php
															echo "<option value='1'>0 Hari</option>";
															echo "<option value='2'>1-3 Hari</option>";
															echo "<option value='3'>4-5 Hari</option>";
															echo "<option value='4'>6-7 Hari</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="volume_bokar_per_hari">Volume Bokar Per Hari</label>
													<select class="form-control form-control-hide select2" name="volume_bokar_per_hari" id="volume_bokar_per_hari" required="required"  data-placeholder="Pilih Volume">
														<?php
															echo "<option value='1'>0-5 Ton</option>";
															echo "<option value='2'>6-10 Ton</option>";
															echo "<option value='3'>11-15 Ton</option>";
															echo "<option value='4'> >15 Ton</option>";
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-xs-6">
													<label for="sumber_pendapatan_mitra">Sumber Pendapatan Mitra</label>
													<select class="form-control form-control-hide select2" name="sumber_pendapatan_mitra" id="sumber_pendapatan_mitra" required="required"  data-placeholder="Pilih Sumber Pendapatan">
														<?php
															echo "<option value='1'>Pendapatan hanya berasal dari penjualan bokar</option>";
															echo "<option value='2'>Pendapatan dari penjualan bokar > Pendapatan dari usaha </option>";
															echo "<option value='3'>Pendapatan dari penjualan bokar < Pendapatan dari usaha</option>";
															echo "<option value='4'>Pendapatan hanya berasal dari usaha lainnya</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>							
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>AKTIVITAS PENJUALAN BOKAR OLEH REKAN MITRA</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="frekuensi_penjualan_rekan_mitra_per_minggu">Frekuensi Penjualan Mitra Per Minggu</label>
													<select class="form-control form-control-hide select2" name="frekuensi_penjualan_rekan_mitra_per_minggu" id="frekuensi_penjualan_rekan_mitra_per_minggu" required="required"  data-placeholder="Pilih Frekuensi">
														<?php
															echo "<option value='1'>0 Hari</option>";
															echo "<option value='2'>1-3 Hari</option>";
															echo "<option value='3'>4-5 Hari</option>";
															echo "<option value='4'>6-7 Hari</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="volume_bokar_per_hari">Volume Bokar Per Hari</label>
													<select class="form-control form-control-hide select2" name="volume_bokar_rekan_mitra_per_hari" id="volume_bokar_rekan_mitra_per_hari" required="required"  data-placeholder="Pilih Volume">
														<?php
															echo "<option value='1'>0-5 Ton</option>";
															echo "<option value='2'>6-10 Ton</option>";
															echo "<option value='3'>11-15 Ton</option>";
															echo "<option value='4'> >15 Ton</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>								
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>ASPEK SOSIAL MITRA</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="status_sosial_mitra">Frekuensi Penjualan Mitra Per Minggu</label>
													<select class="form-control form-control-hide select2" name="status_sosial_mitra" id="status_sosial_mitra" required="required"  data-placeholder="Pilih Sosial Mitra">
														<?php
															echo "<option value='1'>Pendatang < 1 Tahun</option>";
															echo "<option value='2'>Pendatang < 3 Tahun</option>";
															echo "<option value='3'>Pendatang >= 3 Tahun</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>MODAL KERJA</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="total_volume_penjualan_per_hari">Total Transaksi Penjualan BOKAR Per Hari</label>
													<select class="form-control form-control-hide select2" name="total_volume_penjualan_per_hari" id="total_volume_penjualan_per_hari" required="required"  data-placeholder="Pilih Total Transaksi">
														<?php
															echo "<option value='1'>< 150 Jt</option>";
															echo "<option value='2'>150-250 Jt</option>";
															echo "<option value='3'>250-350 Jt</option>";
															echo "<option value='4'>> 350 Jt</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="modal_kerja">Modal Kerja</label>
													<select class="form-control form-control-hide select2" name="modal_kerja" id="modal_kerja" required="required"  data-placeholder="Pilih Modal Kerja">
														<?php
															echo "<option value='kmg'>Sumber Modal dari KMG</option>";
															echo "<option value='mitra'>Sumber Modal dari Mitra</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>ESTIMASI PENERIMAAN BOKAR DI DEPO PER HARI</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="estimasi_tonase_kering">Total Transaksi Penjualan BOKAR Per Hari</label>
													<select class="form-control form-control-hide select2" name="estimasi_tonase_kering" id="estimasi_tonase_kering" required="required"  data-placeholder="Pilih Transaksi Penjualan">
														<?php
															echo "<option value='1'>< 5 Ton</option>";
															echo "<option value='2'>5-7 Ton</option>";
															echo "<option value='3'>8-10 Ton</option>";
															echo "<option value='4'>> 10 Ton</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>ALIRAN DANA PEMBELIAN BOKAR DI DEPO</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="pengiriman_dana_bokar">Pemgiriman Dana Untuk Pemeblain Bokar</label>
													<select class="form-control form-control-hide select2" name="pengiriman_dana_bokar" id="pengiriman_dana_bokar" required="required"  data-placeholder="Pilih Pengiriman Dana">
														<?php
															echo "<option value='cheque'>Cheque</option>";
															echo "<option value='transfer'>Transfer</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="rekening_tujuan">Rekening Tujuan</label>
													<select class="form-control form-control-hide select2" name="rekening_tujuan" id="rekening_tujuan" required="required"  data-placeholder="Pilih Rekening Tujuan">
														<?php
															echo "<option value='pabrik'>Pabrik</option>";
															echo "<option value='mitra'>Mitra</option>";
															echo "<option value='karyawan_kmg'>Karyawan KMG</option>";
															echo "<option value='supplier'>Supplier</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>AKTIVITAS PELELANGAN / KOMPETITOR</h4></legend>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-6">
													<label for="jumlah_pelanggan">Jumlah Pelelangan Sekitar Depo</label>
													<select class="form-control form-control-hide select2" name="jumlah_pelanggan" id="jumlah_pelanggan" required="required"  data-placeholder="Pilih Jumlah Pelelangan">
														<?php
															echo "<option value='1'>> 2</option>";
															echo "<option value='2'>2</option>";
															echo "<option value='3'>1</option>";
														?>
													</select>
												</div>
												<div class="col-xs-6">
													<label for="jumlah_tronton_per_minggu">Jumlah Tronton Per Minggu</label>
													<select class="form-control form-control-hide select2" name="jumlah_tronton_per_minggu" id="jumlah_tronton_per_minggu" required="required"  data-placeholder="Pilih Tronton">
														<?php
															echo "<option value='1'><=2</option>";
															echo "<option value='2'>3</option>";
															echo "<option value='3'>4</option>";
															echo "<option value='4'>>=5</option>";
														?>
													</select>
												</div>
											</div>
										</div>
									</fieldset>									
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="modal-footer">
							<input id="id_data" name="id_data" type="hidden">
							<button id="btn_save" type="button" class="btn btn-primary" name="action_btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/depo/transaksi/input.js"></script>
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