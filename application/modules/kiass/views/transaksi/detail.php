<?php $this->load->view('header') ?>
<!-- 
/*
    @application  : K-IASS
    @author       : MATTHEW JODI (8944)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */ -->

<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css">

<style type="text/css">
	.datepicker{
		border-radius: 0;
	}
	.table-form,
	.table-form th, 
	.table-form td, 
	.table-form tr{
		border: solid 1px black !important;
	}

	.border{
		border: solid 1px black !important;
		padding: 10px;
	}
	textarea{
		resize: vertical;
	}

	.c-label{
		font-weight:400;
		font-size:small;
	}

	.mw200{
		min-width:200px;
	}

	.mw100{
		min-width:100px;
	}

	.mw110{
		min-width:110px;
	}

	.mw150{
		min-width:150px;
	}

	.mw40{
		min-width:40px;
	}

	.clickable{
		cursor: pointer;   
	}

	.panel-heading span {
		margin-top: -20px;
		font-size: 15px;
	}

	.pr{
		padding-right:5px;
	}

	.scrolls {
		overflow-x: scroll;
		overflow-y: hidden;
		/* height: 80px; */
		white-space:nowrap
	}

	.panel-title {
		text-align:left;
	}


	.select2-container--default .select2-selection--multiple .select2-selection__choice:first-of-type {
	    background-color: #da4a38 !important;
	    border-color: #dd4b39;
	}
</style>

<!-- mockup form scrap -->

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success page-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong>Form Pengajuan Penjualan</strong></h3>
						<input type="hidden" name="session_role_level" value="<?php echo $session_role[0]->level; ?>">
						<input type="hidden" name="session_role_nama" value="<?php echo $session_role[0]->nama_role; ?>">
						<div class="btn-group pull-right pr">
							<button type="button"
									class="btn btn-sm btn-primary"
									id="log_status"><i class="fa fa-info-circle icons_white"></i> HISTORY
							</button>
						</div>
						<?php
							if ($kode_accounting == true) {
								echo '<div class="btn-group pull-right pr">';
								echo '<button type="button"';
								echo 'class="btn btn-sm btn-primary"';
								echo 'id="accounting_kode"><i class="fa fa-plus icons_white"></i> ACCOUNTING';
								echo '</button>';
								echo '</div>';

							}

						?>
					</div>
					<form class="form-pengajuan-penjualan"
						  enctype="multipart/form-data">
						<div class="box-body">
						<div class="row">
								<div class="col-sm-6 form-horizontal">
									<div class="form-group">
										<label for="tgl_pengajuan"
											   class="col-sm-4 control-label">Tanggal Pengajuan</label>
										<div class="col-sm-8">
											<input type="text"
												   name="tgl_pengajuan"
												   class="form-control"
												   placeholder="Masukkan Tanggal Pengajuan"
												   required="required"
												   id="tgl_pengajuan"
												   readonly="readonly"/>
										</div>
									</div>
									<div class="form-group hide1 hide2">
										<label for="depo"
											   class="col-sm-4 control-label">Lokasi</label>
										<div class="col-sm-8">
											<select class="form-control select2 lokasi"
													name="lokasi"
													style="width: 100%;"
													required="required">
												<option value="Pabrik">Pabrik</option>
												<option value="HO">Head Office</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="pembeli"
											   class="col-sm-4 control-label">Pembeli</label>
										<div class="col-sm-8">
											<select class="form-control select2"
													name="pembeli"
													style="width: 100%;"
													required="required">
												<option value="pihakKetiga">Pihak Ketiga</option>
												<option value="satuEntitas">Grup Usaha - Satu Entitas</option>
												<option value="bedaEntitas">Grup Usaha - Beda Entitas</option>
											</select>
										</div>
									</div>
									<div class="form-group hide3 hide4">
										<label for="perihal"
											   class="col-sm-4 control-label">Perihal</label>
										<div class="col-sm-8">
											<input type="text"
													   class="form-control"
													   name="perihal"
													   required="required">
										</div>
									</div>
									<div class="form-group nilai_penawaran">
										<label for="nilai"
											   class="col-sm-4 control-label">Nilai Rekomendasi</label>
										<div class="col-sm-8">
											<div class="input-group">
												<div class="input-group-addon">
													Rp. 
												</div>
												<input type="text"
													   class="angka form-control"
													   name="nilai_penawaran"
													   readonly
													   >

											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 form-horizontal">
									<div class="form-group">
										<label for="no_pp"
											   class="col-sm-4 control-label">Nomor PP</label>
										<div class="col-sm-8">
											<input type="text"
												   class="form-control"
												   name="no_pp"
												   value="<?php echo $no_pp; ?>"
												   readonly="readonly"
												   required="required">
											<input type="hidden" name="bukrs">
										</div>
									</div>
									<div class="form-group">
										<label for="jenis"
											   class="col-sm-4 control-label">Jenis Barang</label>
										<div class="col-sm-8" style ="border: 1px solid lightgray;
														padding: 6px;
														width: 62.5%;
														margin-left: 14px;">
											
												<div class="col-sm-5">
													<label class="c-label"><u>Aset Tetap</u></label>
													<div class="input-group">
														<label for="option1" class="c-label">
															<input type="radio" class="radioJenisPabrik" class="radioJenisPabrik" name="radioJenis" id="TB" value="TB" checked> Tanah & Bangunan
														</label>
														<label for="option2" class="c-label">
														<input type="radio" class="radioJenisPabrik radioJenisHO" name="radioJenis" id="STB" value="STB"> Selain T & B
														</label>
													</div>
												</div>
												<div class="col-sm-3" style="padding-left:5px;">
													<label for="" class="c-label"><u>Persedian</u></label>
													<div class="input-group">
														<label for="option1" class="c-label">
														<input type="radio" class="radioJenisPabrik" name="radioJenis" id="SPR" value="SPR"> Sparepart
														</label>
													</div>
												</div>
												<div class="col-sm-4" style="padding-left:5px;">
													<label for="" class="c-label"><u>Barang Bekas</u></label>
													<div class="input-group">
														<label for="option1" class="c-label">
														<input type="radio" class="radioJenisPabrik" name="radioJenis" id="LB3" value="LB3"> Limbah B3
														</label>
														<label for="option2" class="c-label">
														<input type="radio" class="radioJenisPabrik radioJenisHO" name="radioJenis" id="SLB3" value="SLB3"> Selain Limbah B3
														</label>
													</div>
												
												</div>
										</div>
									</div>
									<div class="form-group">
										<label for="pic_ho"
											   class="col-sm-4 control-label">Penanggung Jawab HO</label>
										<div class="col-sm-8">
											<select class="form-control select2"
													name="pic_ho"
													style="width: 100%;"
													readonly="readonly">
												<option value="Factory Operation">Factory Operation</option>
												<option value="Sourcing">Sourcing</option>
												<option value="ICT">ICT</option>
												<option value="HRGA">HRGA</option>
												<option value="Finance Controller">Finance Controller</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="pic_pabrik"
											   class="col-sm-4 control-label">PIC Pabrik</label>
										<div class="col-sm-8">
											<select class="form-control select2"
													name="pic_pabrik"
													style="width: 100%;"
													readonly="readonly">
												<option value="Manager Pembelian">Manager Pembelian</option>
												<option value="Manager Kantor">Manager Kantor</option>
												<option value="Manager Pabrik">Manager Pabrik</option>
											</select>
										</div>
									</div>
								</div>
							</div>

						</div> <!-- TOP FORM -->
						
						<div class="box-body">
		              		<div class="row">
		              			<div class="col-sm-12">
		              				<div class="border">
			              				<div class="form-group">
			              					<label>Latar Belakang</label>
			              					<textarea class="form-control" name="latar_belakang" id="latar_belakang" readonly="readonly"></textarea>
			              				</div>
		              				</div>
		              			</div>
		              		</div>
		            	</div> <!--end box-body-->
		            	
						<div class="box-body">
		              		<div class="row">
		              			<div class="col-sm-12">
									<fieldset style="border:1px solid black;">
										<legend class="text-center">Analisa Harga</legend>
										<div class="form-group">
											<button type="button" class="btn btn-default btn-sm hide add-ann">Tambah Analisa Harga</button>
											<button type="button" class="btn btn-default btn-sm hide del-ann">Hapus Analisa Harga</button>
											<button type="button" class="btn btn-default btn-sm hide add-kunnr">Tambar Row Kunnr</button>
										</div>

										<div class="box boxAnalisa boxAn1 collapsed-box" style="border: 1px solid black;">
											<div class="box-header" style="border-bottom: 1px solid black;">
												<h3 class="box-title" style="font-size: 
												              15px;">Analisa Harga 1</h3>
												<div class="box-tools pull-right">
													<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
													</button>
												</div>
											</div>

											<div class="box-body">
												<div class="row">
													<div class="col-sm-12">
														<div class="form-group">
															<button type="button" value='1' class="btn btn-default btn-sm hide add-row">Tambah baris tabel</button>
															<button type="button" value='1' class="btn btn-default btn-sm hide del-row">Hapus baris tabel</button>
															<button type="button" value='1' class="btn btn-default btn-sm hide add-col-pembeli">Tambah kolom alternatif</button>
															<button type="button" value='1' class="btn btn-default btn-sm hide del-col-pembeli">Hapus kolom alternatif</button>
														</div>
														
														<div class="table-responsive scrolls">
															<table class="table table-hover table-form">
																<thead>
																	<tr>
																		<th class="text-center" rowspan="5">NO</th>
																		<th class="text-center" rowspan="5">Kode Material</th>
																		<th class="text-center" rowspan="5">Deskripsi</th>
																		<th class="text-center" rowspan="5">Rincian</th>
																		<th class="text-center" rowspan="5">UOM</th>
																		<th class="text-center" rowspan="5">Kode Asset</th>
																		<th class="text-center" rowspan="5">Deskripsi Asset</th>
																		<th class="text-center" rowspan="5">Cap Date</th>
																		<th class="text-center" rowspan="5">NBV</th>
																		<th class="text-center" rowspan="5">Qty</th>
																		<th class="text-center" rowspan="5">Harga Terakhir</th>
																		<th class="text-center calon_tabel1 calon1" colspan="2">Alternatif 1 </th>
																		<th class="text-center calon_tabel1 calon2" colspan="2">Alternatif 2 </th>
																		<th class="text-center" colspan="3" rowspan="4">Rekomendasi Alternatif</th>
																		<th class="text-center" colspan="2" rowspan="4">Varian</th>
																		<th class="text-center" rowspan="4">Foto Kondisi Barang</th>												
																	</tr>
																	<tr>
																		<th colspan="2" class="text-center th-customer_tabel1">
																			
																				<select name='customer_tabel1_calon1' class='form-control select-customer autocomplete' data-allowclear="true"><option></option></select>
																			
																		</th>
																		<th colspan="2" class="text-center th-customer_tabel1">
																			
																				<select name='customer_tabel1_calon2' class='form-control select-customer autocomplete' data-allowclear="true"><option></option></select>
																			
																		</th>
																	</tr>
																	<tr>
																		<th colspan="2" class="text-center th-nama_customer_tabel1">
																			
																			<input name='nama_customer_tabel1_calon1' class='form-control' placeholder="Nama Alternatif" required>
																			
																		</th>
																		<th colspan="2" class="text-center th-nama_customer_tabel1">
																			
																			<input name='nama_customer_tabel1_calon2' class='form-control' placeholder="Nama Alternatif" required>
																			
																		</th>
																	</tr>
																	<tr>
																		<th class="text-center">
																			<div class="input-group date">
																				<div class="input-group-addon">
																					<i class="fa fa-id-card"></i>
																				</div>
																				<input type='text' name='identitas_tabel1_calon1' class='form-control' required placeholder='Nomor NPWP / KTP'>
																			</div>
																		</th>
																		<th class="text-center th-identitas_tabel1">
																			<div class="input-group date">
																				<div class="input-group-addon">
																					<i class="fa fa-mobile"></i>
																				</div>	
																				<input type='text' name='hp_tabel1_calon1' class='form-control' required placeholder='Nomor HP'>
																			</div>
																		</th>
																		<th class="text-center">
																			<div class="input-group date">
																				<div class="input-group-addon">
																					<i class="fa fa-id-card"></i>
																				</div>
																				<input type='text' name='identitas_tabel1_calon2' class='form-control' required placeholder='Nomor NPWP / KTP'>
																			</div>
																		</th>
																		<th class="text-center th-identitas_tabel1">
																			<div class="input-group date">
																				<div class="input-group-addon">
																					<i class="fa fa-mobile"></i>
																				</div>	
																				<input type='text' name='hp_tabel1_calon2' class='form-control' required placeholder='Nomor HP'>
																			</div>
																		</th>
																	</tr>
																	<tr>
																		<th class="text-center">Harga Satuan<br><small><em>Sebelum PPN</em></small></th>
																		<th class="text-center th-calon_tabel1">Total<br><small><em>Sebelum PPN</em></small></th>
																		<th class="text-center">Harga Satuan<br><small><em>Sebelum PPN</em></small></th>
																		<th class="text-center th-calon_tabel1">Total<br><small><em>Sebelum PPN</em></small></th>
																		<th class="text-center">Harga Nego<br><small><em>Sebelum PPN</em></small></th>
																		<th class="text-center">Total<br><small><em>Sebelum PPN</em></small></th>
																		<th class="text-center">Pilihan Alternatif</th>
																		<th class="text-center">Harga Satuan<br><small><em>Sebelum PPN</em></small></th>
																		<th class="text-center">Total<br><small><em>Sebelum PPN</em></small></th>
																		<!-- <th class="text-center">Kode Asset</th> -->
																		<th class="text-center">Sesuai Template</th>													
																	</tr>
																</thead>
																<tbody>
																	<tr class="input-table-row1 row1 tr-row" data-row="1" data-tabel="1">
																		<td class="text-center"><span class="form-control">1</span></td>
																		<td class="text-center mw200"><select name='kode_material_tabel1_row1'class='form-control select-material autocomplete' required data-allowclear="true"><option></option></select>
																		<input type="hidden" class="id_row" name="id_row_analisa_tabel1_row1">
																		</td>
																		<td class="text-center mw200">
																			<textarea name="deskripsi_tabel1_row1" class="form-control col-sm-12 deskripsi" required="required" readonly></textarea>
																		</td>
																		<td class="text-center mw200">
																			<textarea name="rincian_tabel1_row1" class="form-control col-sm-12" required="required"></textarea>
																		</td>
																		<td class="text-center mw100">
																			<input name="satuan_tabel1_row1" class="form-control uom col-sm-12 text-center" readonly>
																		</td>
																		<td class="text-center mw200">
																			<div class="input-group">
																				<input type="text" class="form-control mw110 input-acc acc" name='kode_asset_tabel1_row1' readonly>
																				<div class="input-group-addon">-</div>
																				<input type="text" class="form-control mw40 input-acc acc sno" name='sno_tabel1_row1' readonly>
																				<div class="btn input-group-addon btn-input-acc hide"><i class="fa fa-download"></i></div>
																			</div>																			
																		</td>
																		<td class="text-center mw200">
																			<textarea name="deskripsi_asset_tabel1_row1" class="form-control col-sm-12 acc" readonly></textarea>
																		</td>
																		<td class="text-center mw150"><input type="text" class="text-center form-control col-sm-12 acc" name='cap_date_tabel1_row1' readonly></td>
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" class="text-right form-control col-sm-12 nbv angka acc" readonly name="nbv_tabel1_row1">
																			</div>
																		</td>
																		<td class="text-center mw100"><input type="text" name="qty_tabel1_row1" value="0" class="text-center form-control col-sm-12 qty angka"></td>
																		
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="harga_terakhir_tabel1_row1" class="text-right harga-terakhir form-control col-sm-12 angka" required="required">
																			</div>
																		</td>
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="harga_satuan_tabel1_row1_calon1" class="text-right form-control harga-satuan harga_satuan_tabel1_row1 col-sm-12 angka" required="required">
																				<input type='hidden' class='id_calon' name='id_calon_pembeli_tabel1_row1_calon1'>
																			</div>
																		</td>
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="harga_total_tabel1_row1_calon1" data-calon="1" class="text-right form-control col-sm-12 total-harga-satuan angka calon_harga_tabel1_row1" readonly required="required">
																			</div>
																		</td>
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="harga_satuan_tabel1_row1_calon2" class="text-right form-control harga-satuan harga_satuan_tabel1_row1 col-sm-12 angka" required="required">
																				<input type='hidden' class='id_calon' name='id_calon_pembeli_tabel1_row1_calon2'>
																			</div>
																		</td>
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="harga_total_tabel1_row1_calon2" data-calon="2" class="text-right form-control total-harga-satuan col-sm-12 angka calon_harga_tabel1_row1" readonly required="required">
																			</div>
																		</td>
												
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="harga_nego_tabel1_row1" readonly class="text-right form-control harga-nego procurement col-sm-12 angka">
																			</div>
																		</td>
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="total_harga_nego_tabel1_row1" class="text-right total_harga_nego form-control col-sm-12" readonly>
																			</div>	
																		</td>
																		<td class="text-center mw200">
																			<select class="form-control select2 procurement select-calon select-calon-pembeli_tabel1 readonly"
																					name="pembeli_tabel1_row1"
																					style="width: 100%;"
																					required="required" readonly>
																				<option value="1">Alternatif 1</option>
																				<option value="2">Alternatif 2</option>
																			</select>	
																		</td>
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="harga_varian_tabel1_row1" readonly class="text-right varian form-control col-sm-12 angka">
																			</div>
																		</td>
																		<td class="text-center mw200">
																			<div class="input-group">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="total_varian_tabel1_row1" readonly class="text-right varian-total form-control col-sm-12 angka">
																			</div>
																		</td>
																		
																		<td class="text-center mw200">
																			<div class="input-group">
																				<input type="text" class="form-control caption_file" name="caption_tabel1_row1" required="required" readonly="readonly">
																				<div class="input-group-btn">
																					<input type="file" class="form-control upload_file berkas" name="foto_tabel1_row1[]" style="display:none;">
																					<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload"><i class="fa fa-upload"></i></button>
																				</div>
																				<div class="input-group-btn">
																					<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>
																				</div>
																			</div>
																		</td>
																	</tr>

																	<tr>
																		<td colspan="10" style="background:#d3d3d369;"></td>
																		<td class="text-right">Nilai Total</td>
																		<td colspan="2" class="nilai-calon_tabel1">
																			<div class="input-group col-sm-12">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="nilai_total_tabel1_calon1" class="text-right form-control col-sm-12 angka" value="0" readonly="readonly" >
																			</div>
																		</td>
																		<td colspan="2" class="nilai-calon_tabel1">
																			<div class="input-group col-sm-12">
																				<div class="input-group-addon">Rp</div>
																				<input type="text" name="nilai_total_tabel1_calon2" class="text-right form-control col-sm-12 angka" value="0" readonly="readonly" >
																				</div>
																		</td>
																		<td colspan="8" style="background:#d3d3d369;"></td>
																	</tr>
																	<tr>
																		<td colspan="10" style="background:#d3d3d369;"></td>
																		<td class="text-right">Metode Pembayaran</td>
																		<td colspan="2" class="metode-calon_tabel1">
																			<select class="form-control select2"
																					name="metode_tabel1_calon1"
																					style="width: 100%;"
																					required="required">
																				<option value="tunai">Tunai</option>
																				<option value="transfer">Transfer</option>
																			</select>
																		</td>
																		<td colspan="2" class="metode-calon_tabel1">
																			<select class="form-control select2"
																					name="metode_tabel1_calon2"
																					style="width: 100%;"
																					required="required">
																				<option value="tunai">Tunai</option>
																				<option value="transfer">Transfer</option>
																			</select>
																		</td>
																		<td colspan="10" style="background:#d3d3d369;"></td>
																	</tr>
																	<tr>
																		<td colspan="10" style="background:#d3d3d369;"></td>
																		<td class="text-right">Term of Delivery / Duration</td>
																		<td colspan="2" class="tod-calon_tabel1">
																			<input type="text" name="tod_tabel1_calon1" required class="text-center form-control col-sm-12">
																		</td>
																		<td colspan="2" class="tod-calon_tabel1">
																			<input type="text" name="tod_tabel1_calon2" required class="text-center form-control col-sm-12">
																		</td>
																		<td colspan="10" style="background:#d3d3d369;"></td>
																	</tr>
																	<tr>
																		<td colspan="10" style="background:#d3d3d369;"></td>
																		<td class="text-right">Lampiran</td>
																		<td colspan="2" class="lampiran-calon_tabel1">
																			<div class="input-group" style="width: 100%;">
																				<input type="text" class="form-control caption_file" name="caption_tabel1_calon1" required="required" readonly="readonly">
																				<div class="input-group-btn">
																					<input type="file" name="lampiran_tabel1_calon1[]" class="form-control upload_file berkas" style="display:none;">
																					<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload"><i class="fa fa-upload"></i></button>
																				</div>
																				<div class="input-group-btn">
																					<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>
																				</div>
																			</div>
																		</td>
																		<td colspan="2" class="lampiran-calon_tabel1">
																			<div class="input-group" style="width: 100%;">
																				<input type="text" class="form-control caption_file" name="caption_tabel1_calon2" required="required" readonly="readonly">
																				<div class="input-group-btn">
																					<input type="file" name="lampiran_tabel1_calon2[]" class="form-control upload_file berkas" style="display:none;">
																					<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload"><i class="fa fa-upload"></i></button>
																				</div>
																				<div class="input-group-btn">
																					<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>
																				</div>
																			</div>
																		</td>
																		<td colspan="10" style="background:#d3d3d369;"></td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div><!--end box-body-->
										</div><!--end box-body-->
									</fieldset>
								</div>
		              		</div>
		            	</div> <!--end box-body-->

						<div class="box-body">
		              		<div class="row">
		              			<div class="col-sm-12">
		              				<div class="border">
									  <div class="form-group">
			              					<label>Alternatif hanya satu, karena (diisi oleh pabrik) :</label>
			              					<textarea class="form-control" name="ket_satu_pembeli" id="ket_satu_pembeli"></textarea>
			              				</div>
										<div class="form-group">
			              					<label>SPK (khusus untuk limbah B3, diisi oleh Procurement HO) : </label>
											<div class="input-group">
												<div class="radio-inline">
													<label for="option1">
													<input type="radio" name="radiospk" readonly value="y" checked> Sudah ada
													</label>
												</div>
												<div class="radio-inline">
													<label for="option2">
													<input type="radio" name="radiospk" readonly value="n"> Belum ada, karena
													</label>
												</div>
											</div>
			              					<textarea class="form-control" name="keterangan_spk" id="keterangan_spk" readonly="readonly"></textarea>
			              				</div>
										<div class="form-group">
			              					<label>Catatan Procurement HO :</label>
			              					<textarea class="form-control procurement" name="catatan_proc" id="catatan_proc" readonly="readonly"></textarea>
			              				</div>
										<div class="form-group" style="margin-bottom:20px;">
			              					<label>Lampiran Procurement</label>
											<div class="input-group" style="width: 100%;">
												<input type="text" class="form-control caption_file" name="caption_lampiran_procurement" readonly="readonly">
												<div class="input-group-btn">
													<input type="file" name="lampiran_procurement[]" class="form-control file-proc procurement upload_file berkas" style="display:none;">
													<button type="button" class="btn btn-default procurement btn-flat btn_upload_file" data-title="Upload" disabled><i class="fa fa-upload"></i></button>
												</div>
												<div class="input-group-btn">
													<button type="button" class="btn btn-default btn-flat view_file" data-link="" data-title="Lihat file"><i class="fa fa-search"></i></button>
												</div>
											</div>
                                        </div>
		              				</div>
		              			</div>
		              		</div>
		            	</div>

						<div class="box-body">
		              		<div class="row">
		              			<div class="col-sm-12">
		              				<div class="border">
									  <div class="form-group" style="margin-bottom:20px;">
			              					<label>Lampiran Lainnya</label>
			              					
											<div class="input-group" style="width: 100%;">
												<input type="text" class="form-control caption_file" name="caption_lampiran" required="required" readonly="readonly">
												<div class="input-group-btn">
													<input type="file" name="lampiran[]" class="form-control upload_file berkas" style="display:none;">
													<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload" disabled><i class="fa fa-upload"></i></button>
												</div>
												<div class="input-group-btn">
													<button type="button" class="btn btn-default btn-flat view_file" data-link="" data-title="Lihat file"><i class="fa fa-search"></i></button>
												</div>
											</div>
                                          
                                        </div>
		              				</div>
		              			</div>
		              		</div>
		            	</div>

						<div class="box-footer">
							<input type="hidden"
								   name="action">
							<div id="counter"></div>
							<input type="hidden" name="id_flow" value="<?php echo $id_flow; ?>">
							
							<?php
								if (isset($approval)) {
									
									if ($approval->if_approve !== NULL)
										echo '<button type="button" class="btn btn-success btn-role btn_approve flag_penilaian_jaminan" name="action_btn" value="approve">Approve</button>';
									if ($approval->if_assign !== NULL)
										echo '<button type="button" class="btn btn-info btn-role flag_penilaian_jaminan" name="action_btn" value="assign">Assign</button>';
									if ($approval->if_decline !== NULL)
										echo '<button type="button" class="btn btn-warning btn-role" name="action_btn" value="decline">Decline</button>';
									if ($approval->if_drop !== NULL)
										echo '<button type="button" class="btn btn-danger btn-role flag_penilaian_jaminan" name="action_btn" value="drop">Drop</button>';
								}
							?>
						</div>

						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- <div class="modal fade"
				id="log_status_modal"
				data-backdrop="static"
				tabindex="-1"
				role="dialog"
				aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-lg"
					role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button"
								class="close"
								data-dismiss="modal"
								aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title"
							id="myModalLabel">History Pengajuan Penjualan</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-striped my-datatable-extends">
									<thead>
										<th>No Pengajuan Penjualan</th>
										<th>Tanggal Status</th>
										<th>Status</th>
										<th>Comment</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button"
								class="btn btn-default"
								data-dismiss="modal">Close
						</button>
					</div>
				</div>
			</div>
		</div> -->

		<!-- Modal -->
		<div class="modal fade" id="kunnr_modal" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-lg" role="document">
				<form role="form" class="form-kode-accounting">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="view_modal_label"><strong>Form Kode Customer Accounting</strong></h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-sm-12">
									<table class="table table-bordered">
										<thead>
											<th>Analisa Harga</th>
											<th>Baris</th>
											<th>Nama Pembeli Terpilih</th>
											<th>Kode Customer</th>
										</thead>
										<tbody id='kunnr_content'>
										
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-success" name="submit-form-kunnr" id="submit-form-kunnr">Submit</button>
							<input type="hidden" name="counter_kode">
              				<input type="hidden" name="no_pp_kode">
						</div>
					</div>
				</form>
		  	</div>
		</div>
    	<!-- Modal -->

		

	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/kiass/transaksi/detail.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>

