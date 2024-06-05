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
					<form role="form" id="form_evaluasi_depo_edit" enctype="multipart/form-data">
						<div class="modal-header">
							<h4 class="modal-title" id="myModalLabel">Edit Evaluasi Kinerja Depo</h4>
						</div>
						<div class="modal-body">
							<div class="tab-content">
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label> Nomor </label>
											<input type="text" class="form-control" name="nomor" id="nomor" value="<?php echo $nomor;?>" placeholder="Nomor" required="required" readonly>
										</div>
									</div>
									<div class="col-xs-6">
										<label for="nama">Nama Depo</label>
										<input style="text-transform: uppercase" type="text" class="form-control form-control-hide" name="nama" id="nama" value="99999" placeholder="Nama"  required="required"  readonly>
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
									<div class="col-xs-6">
										<label for="kabupaten">Kabupaten</label>
										<input type="text" class="form-control form-control-hide" name="kabupaten" id="kabupaten" value="" placeholder="Kabupaten"  readonly>
									</div>
									<div class="col-xs-6">
										<label for="propinsi">Provinsi</label>
										<input type="text" class="form-control form-control-hide" name="propinsi" id="propinsi" value="" placeholder="Provinsi"  readonly>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-6">
										<label>Alamat Rumah</label>
										<textarea name="alamat_rumah" id="alamat_rumah" class="form-control form-control-hide" rows="3" placeholder="Masukan Alamat Rumah" readonly>99999</textarea>
									</div>
									<div class="col-xs-6">
										<label>Alamat Depo</label>
										<textarea name="alamat_depo" id="alamat_depo" class="form-control form-control-hide" rows="3" placeholder="Masukan Alamat Depo" readonly>99999</textarea>
									</div>
								</div>
								<fieldset class="fieldset-default">
									<legend class="text-left"><h4>DATA PEMBELIAN</h4></legend>
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-hover table-bordered table-detail">
													<thead>
														<tr>
															<th class="text-center" colspan="2">TARGET BELI<br>KERING (TON KRG)</th>
															<th class="text-center" colspan="10">DATA AKTUAL BELI</th>
														</tr>
														<tr>
															<th class="text-center" width="100px">BULAN</th>
															<th class="text-center">QTY</th>
															<th class="text-center">QTY KERING<br>ACTUAL<br>(KG KRG)</th>
															<th class="text-center">QTY BASAH<br>ACTUAL<br>(KG BSH)</th>
															<th class="text-center">HARGA RATA2<br>NOTARIN PABRIK<br>(KG KRG)</th>
															<th class="text-center">SICOM</th>
															<th class="text-center">EST. TOT. PROD.<br>COST FACTORY<br>(RP / KG KRG)</th>
															<th class="text-center">HARGA BELI<br>DEPO<br>(RP / KG KRG)</th>
															<th class="text-center">% Susut/Lebih<br>Pabrik</th>
															<th class="text-center">Harga Beli<br>Batch<br>Pabrik</th>
															<th class="text-center">% Susut/Lebih<br>Depo</th>
															<th class="text-center">Harga Beli<br>Batch<br>Depo</th>
														</tr>
													</thead>
													<tbody>
														<tr id="nodata_detail">
															<td colspan="13">No data found</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</fieldset>
								<fieldset class="fieldset-default">
									<legend class="text-left"><h4>ANALISIS PROFITABILITAS</h4></legend>
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-hover table-bordered">
													<thead>
														<tr>
															<th class="text-left" colspan="3">A. Biaya Operasionalxx</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td colspan="3">
																<!--sub tabel-->
																<div class="table-responsive">
																	<table class="table table-hover table-bordered table-biaya_profesional">
																		<thead>
																			<tr style='background-color: rgba(0, 141, 76, 0.3);'>
																				<th class="text-left" colspan="3">1. Biaya Profesional</th>
																			</tr>
																			<tr>
																				<th class="text-center">Jenis Biaya</th>
																				<th class="text-center" width="20%">Biaya KGB</th>
																				<th class="text-center" width="20%">Biaya KGK</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr id="nodata_biaya_profesional">
																				<td colspan="3">No data found</td>
																			</tr>
																		</tbody>
																		<tfoot>
																			<th>&nbsp;</th>
																			<th><input type="text" class='form-control text-center' name='total_biaya_profesional_kgb' readonly /></th>
																			<th><input type="text" class='form-control text-center' name='total_biaya_profesional_kgk' readonly /></th>
																		</tfoot>
																	</table>
																</div>
																<!--sub tabel-->
																<div class="table-responsive">
																	<table class="table table-hover table-bordered  table-biaya_opex">
																		<thead>
																			<tr style='background-color: rgba(0, 141, 76, 0.3);'>
																				<th class="text-left" colspan="3">2. Biaya Opex</th>
																			</tr>
																			<tr>
																				<th class="text-center">Jenis Biaya</th>
																				<th class="text-center" width="20%">Biaya KGB</th>
																				<th class="text-center" width="20%">Biaya KGK</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr id="nodata_biaya_opex">
																				<td colspan="3">No data found</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
																<!--sub tabel-->
																<div class="table-responsive">
																	<table class="table table-hover table-bordered table-biaya_angkut">
																		<thead>
																			<tr style='background-color: rgba(0, 141, 76, 0.3);'>
																				<th class="text-left" colspan="3">3. Ongkos Angkut</th>
																			</tr>
																			<tr>
																				<th class="text-center">Jenis Biaya</th>
																				<th class="text-center" width="20%">Biaya KGB</th>
																				<th class="text-center" width="20%">Biaya KGK</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr id="nodata_biaya_angkut">
																				<td colspan="3">No data found</td>
																			</tr>
																		</tbody>
																	</table>
																</div>
																<!--sub tabel-->
																<div class="table-responsive">
																	<table class="table table-hover table-bordered  table-biaya_gaji">
																		<thead>
																			<tr style='background-color: rgba(0, 141, 76, 0.3);'>
																				<th class="text-left" colspan="3">4. Biaya Terkait Karyawan</th>
																			</tr>
																			<tr>
																				<th class="text-center">Jenis Biaya</th>
																				<th class="text-center" width="20%">Biaya</th>
																				<th class="text-center" width="20%">Biaya KGK</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr id="nodata_biaya_gaji">
																				<td colspan="3">No data found</td>
																			</tr>
																		</tbody>
																		<tfoot>
																			<th>&nbsp;</th>
																			<th><input type="text" class='form-control text-center' name='total_biaya_gaji_kgb' readonly /></th>
																			<th><input type="text" class='form-control text-center' name='total_biaya_gaji_kgk' readonly /></th>
																		</tfoot>
																		
																	</table>
																</div>
																<!--sub tabel-->
																<div class="table-responsive">
																	<table class="table table-hover table-bordered">
																		<thead>
																			<tr style='background-color: rgba(0, 141, 76, 0.3);'>
																				<th class="text-left" colspan="3">5. Biaya Premi Asuransi</th>
																			</tr>
																			<tr>
																				<th class="text-center">Jenis Biaya</th>
																				<th class="text-center" width="20%">Biaya</th>
																				<th class="text-center" width="20%">Biaya KGK</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr>
																				<td><input type="text" class='form-control' name='xxaa' value="Cash in Safe" required='required' readonly /></td>
																				<td><input type="text" class='form-control text-center' name='biaya_cash_save_kgb' required='required' readonly /></td>
																				<td><input type="text" class='form-control text-center' name='biaya_cash_save_kgk' required='required' readonly /></td>
																			</tr>
																			<tr>
																				<td><input type="text" class='form-control' name='xxaa' value="Cash in Transit" required='required' readonly /></td>
																				<td><input type="text" class='form-control text-center' name='biaya_cash_transit_kgb' required='required' readonly /></td>
																				<td><input type="text" class='form-control text-center' name='biaya_cash_transit_kgk' required='required' readonly /></td>
																			</tr>
																			<tr>
																				<td><input type="text" class='form-control' name='xxaa' value="Expedition" required='required' readonly /></td>
																				<td><input type="text" class='form-control text-center' name='biaya_expedition_kgb' required='required' readonly /></td>
																				<td><input type="text" class='form-control text-center' name='biaya_expedition_kgk' required='required' readonly /></td>
																			</tr>
																		</tbody>
																		<tfoot>
																			<th>&nbsp;</th>
																			<th><input type="text" class='form-control text-center' name='total_biaya_asuransi_kgb' required='required' readonly /></th>
																			<th><input type="text" class='form-control text-center' name='total_biaya_asuransi_kgk' required='required' readonly /></th>
																		</tfoot>
																		
																	</table>
																</div>
																<!--sub tabel total-->
																<div class="table-responsive">
																	<table class="table table-hover table-bordered">
																		<thead>
																			<tr style='background-color: rgba(0, 141, 76, 0.3);'>
																				<th class="text-left" colspan="2">Total Biaya</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr>
																				<th><input type="text" class='form-control text-right' value='Total Biaya Operasional' required='required' readonly /></th>
																				<th width="20%"><input type="text" class='form-control text-center' name='total_biaya_operasional' required='required' readonly /></th>
																			</tr>
																			<tr>
																				<th><input type="text" class='form-control text-right' value='DRC Rata-Rata' required='required' readonly /></th>
																				<th><input type="text" class='form-control text-center' name='drc_rata_rata' required='required' readonly /></th>
																			</tr>
																		</tbody>
																	</table>
																</div>
															</td>
														</tr>
													</tbody>
												</table>
												<table class="table table-hover table-bordered  table-asumsi_depo">
													<thead>
														<tr>
															<th class="text-left" colspan="8">B. Asumsi Pembelian Depo (Rp/Kgk)</th>
														</tr>
														<tr>
															<th class="text-center" width="100px">BULAN KE-</th>
															<th class="text-center">SICOM</th>
															<th class="text-center">Harga Aktual<br>Beli Depo</th>
															<th class="text-center">Harga Beli Depo<br>(Effect Batch)</th>
															<th class="text-center">Sim. Prod<br>Cost Factory</th>
															<th class="text-center">Total Biaya<br>Operasional</th>
															<th class="text-center">Nett Margin<br>Depo</th>
															<th class="text-center" width="200px">Nett Margin<br>Effect Batch Depo</th>
														</tr>
													</thead>
													<tbody>
														<tr id="nodata_asumsi_depo">
															<td colspan="8">No data found</td>
														</tr>
													</tbody>
												</table>
												<table class="table table-hover table-bordered  table-asumsi_pabrik">
													<thead>
														<tr>
															<th class="text-center" width="100px">BULAN KE-</th>
															<th class="text-center">Harga Aktual<br>Beli Pabrik</th>
															<th class="text-center">Harga Aktual<br>Beli Depo</th>
															<th class="text-center">Total Biaya<br>Operasional</th>
															<th class="text-center" width="200px">Nett Selisih<br>Harga Beli<br>Depo VS Pabrik</th>
														</tr>
													</thead>
													<tbody>
														<tr id="nodata_asumsi_pabrik">
															<td colspan="5">No data found</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</fieldset>
								<fieldset class="fieldset-default">
									<legend class="text-left"><h4>KESIMPULAN</h4></legend>
									<table class="table table-hover table-bordered  table-asumsi">
										<thead>
											<tr>
												<th class="text-center"></th>
												<th class="text-center">NILAI</th>
												<th class="text-center">KETERANGAN</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input type="text" class='form-control text-right' value='NETT MARGIN EFFECT BATCH DEPO' required='required' readonly /></td>
												<td width="20%"><input type="text" class='form-control text-center' name='total_net_margin_depo_efek_batch' required='required' readonly /></td>
												<td width="20%"><input type="text" class='form-control text-center' name='nilai_net_margin_depo_efek_batch' value='NEGATIF' required='required' readonly /></td>
											</tr>
											<tr>
												<td><input type="text" class='form-control text-right' value='PURCHASE VARIANCE' required='required' readonly /></td>
												<td><input type="text" class='form-control text-center' name='total_margin_depo_pabrik' required='required' readonly /></td>
												<td><input type="text" class='form-control text-center' name='nilai_margin_depo_pabrik' value='NEGATIF' required='required' readonly /></td>
											</tr>
											<tr>
												<td><input type="text" class='form-control text-right' value='PENCAPAIAN VOLUME DEPO (6 BLN)' required='required' readonly /></td>
												<td><input type="text" class='form-control text-center' name='pencapaian_depo' required='required' readonly /></td>
												<td><input type="text" class='form-control text-center' name='nilai_pencapaian_depo' value='<=50 %' required='required' readonly /></td>
											</tr>
											<tr>
												<td><input type="text" class='form-control text-right' value='HASIL PERHITUNGAN SCORING' required='required' readonly /></td>
												<td colspan='2' id="nilai_hasil_perhitungan" align="center"></td>
											</tr>
										</tbody>
									</table>
								</fieldset>								
							<div class="clearfix"></div>
						</div>
						<div class="modal-footer">
							<button id="btn_approve" type="button" class="btn btn-primary" name="action_btn" value="simpan">Update</button>
						</div>
						<input name="status_akhir" type="hidden">
						<input name="level" type="hidden">
						<input name="id_depo_master" type="hidden">
						<input name="jenis_depo" type="hidden">
					</form>	
				</div>
			</div>
		</div>
	</section>
</div>
<!--modal history-->
<div class="modal fade" id="modal-history" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">History Evaluasi Depo</h4>
					</div>
					<div class="modal-body">
						<div id='histori_pengajuan'></div>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
	</div>	
</div>


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/depo/evaluasi/edit.js"></script>
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