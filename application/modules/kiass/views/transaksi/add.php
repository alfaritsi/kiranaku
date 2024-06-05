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

	.mw150{
		min-width:150px;
	}

	.mw40{
		min-width:40px;
	}

	.scrolls {
		overflow-x: scroll;
		overflow-y: hidden;
		/* height: 80px; */
		white-space:nowrap
	}

	.select2-container--default .select2-selection--multiple .select2-selection__choice:first-of-type {
	    background-color: #da4a38 !important;
	    border-color: #dd4b39;
	}

	button{
		margin-right : 5px;
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
												   value="<?php echo date("d-m-Y"); ?>"
												   readonly="readonly"/>
										</div>
									</div>
									<div class="form-group hide1 hide2">
										<label for="depo"
											   class="col-sm-4 control-label">Lokasi</label>
										<div class="col-sm-8">
											<select class="form-control readonly select2 lokasi"
													name="lokasi"
													style="width: 100%;"
													required="required" readonly>
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
									<!-- <div class="form-group hide2 hide3">
										<label for="nilai"
											   class="col-sm-4 control-label">Nilai Penawaran</label>
										<div class="col-sm-8">
											<div class="input-group">
												<div class="input-group-addon">
													Rp. 
												</div>
												<input type="text"
													   class="angka form-control"
													   name="nilai_penawaran"
													   disabled
													   >

											</div>
										</div>
									</div> -->
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
													id="pic_ho"
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
			              					<textarea class="form-control" name="latar_belakang" id="latar_belakang" required="required"></textarea>
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
											<button type="button" class="btn btn-default btn-sm add-ann">Tambah Analisa Harga</button>
											<button type="button" class="btn btn-default btn-sm del-ann">Hapus Analisa Harga</button>
										</div>

										<div class="box boxAnalisa boxAn1" style="border: 1px solid black;">
										<div class="box-header" style="border-bottom: 1px solid black;">
											<h3 class="box-title" style="font-size: 
															15px;">Analisa Harga 1</h3>
											<div class="box-tools pull-right">
												<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
												</button>
											</div>
										</div>

										<div class="box-body">
											<div class="row">
												<div class="col-sm-12">
												<!-- <label for="">Analisa Harga</label> -->
													<div class="form-group">
														<button type="button" value='1' class="btn btn-default btn-sm add-row">Tambah baris tabel</button>
														<button type="button" value='1' class="btn btn-default btn-sm del-row">Hapus baris tabel</button>
														<button type="button" value='1' class="btn btn-default btn-sm add-col-pembeli">Tambah kolom alternatif</button>
														<button type="button" value='1' class="btn btn-default btn-sm del-col-pembeli">Hapus kolom alternatif</button>
													</div>
													<div class="table-responsive scrolls">
														<!-- <label for="">Analisa Harga 1</label> -->
														
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
																<tr class="input-table-row1 row1" data-row="1" data-tabel="1">
																	<td class="text-center"><span class="form-control">1</span></td>
																	<td class="text-center mw200"><select name='kode_material_tabel1_row1'class='form-control select-material autocomplete' required data-test="kode_material_tabel1_row1" data-allowclear="true"><option></option></select></td>
																	<td class="text-center mw200">
																		<textarea name="deskripsi_tabel1_row1" class="form-control col-sm-12 deskripsi" readonly required="required" ></textarea>
																	</td>
																	<td class="text-center mw200">
																		<textarea name="rincian_tabel1_row1" class="form-control col-sm-12" required="required"></textarea>
																	</td>
																	<td class="text-center mw100">
																		<input type="text" class="text-center form-control col-sm-12 uom" required="required" readonly name='satuan_tabel1_row1'>
																	</td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<input type="text" class="form-control mw150" readonly name='kode_asset_tabel1_row1' >
																			<div class="input-group-addon">-</div>
																			<input type="text" class="form-control mw40" readonly name='sno_tabel1_row1' >
																		</div>
																	</td>
																	<td class="text-center mw200">
																		<textarea name="deskripsi_asset_tabel1_row1" class="form-control col-sm-12" readonly></textarea>
																	</td>
																	<td class="text-center mw150"><input type="text" class="form-control col-sm-12" name='cap_date_tabel1_row1' readonly></td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<div class="input-group-addon">Rp</div>
																			<input type="text" name="nbv_tabel1_row1" class="text-right form-control col-sm-12 angka" readonly>
																		</div>
																	</td>
																	<td class="text-center mw100"><input type="text" name="qty_tabel1_row1" required='required' value="1" readonly class="text-center form-control col-sm-12 qty angka"></td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<div class="input-group-addon">Rp</div>
																			<input type="text" name="harga_terakhir_tabel1_row1" class="text-right form-control col-sm-12 angka" value="0" required="required">
																		</div>
																	</td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<div class="input-group-addon">Rp</div>
																			<input type="text" name="harga_satuan_tabel1_row1_calon1" class="text-right form-control harga-satuan harga_satuan_tabel1_row1 col-sm-12 angka" value="0" required="required">
																		</div>
																	</td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<div class="input-group-addon">Rp</div>
																			<input type="text" name="harga_total_tabel1_row1_calon1" data-calon="1" class="text-right form-control col-sm-12 total-harga-satuan angka calon_harga_tabel1_row1" value="0" readonly required="required">
																		</div>
																	</td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<div class="input-group-addon">Rp</div>
																			<input type="text" name="harga_satuan_tabel1_row1_calon2" class="text-right form-control harga-satuan harga_satuan_tabel1_row1 col-sm-12 angka" value="0" required="required">
																		</div>
																	</td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<div class="input-group-addon">Rp</div>
																			<input type="text" name="harga_total_tabel1_row1_calon2" data-calon="2" class="text-right form-control total-harga-satuan col-sm-12 angka calon_harga_tabel1_row1" value="0" readonly required="required">
																		</div>
																	</td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<div class="input-group-addon">Rp</div>
																			<input type="text" name="harga_nego_tabel1_row1" readonly class="text-right form-control col-sm-12 angka" value="0">
																		</div>
																	</td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<div class="input-group-addon">Rp</div>
																			<input type="text" name="total_harga_nego_tabel1_row1" class="text-right form-control col-sm-12" value="0" readonly>
																		</div>	
																	</td>
																	<td class="text-center mw200">
																		<select class="form-control select2 select-calon-pembeli_tabel1 readonly"
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
																			<input type="text" name="harga_varian_tabel1_row1" readonly class="text-right form-control col-sm-12 angka" value="0">
																		</div>
																	</td>
																	<td class="text-center mw200">
																		<div class="input-group">
																			<div class="input-group-addon">Rp</div>
																			<input type="text" name="total_varian_tabel1_row1" readonly class="text-right form-control col-sm-12 angka" value="0">
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
																			<input type="text" class="form-control caption_file" name="" required="required" readonly="readonly">
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
																			<input type="text" class="form-control caption_file" name="" required="required" readonly="readonly">
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

										</div><!--end box-->
						
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
			              					<textarea class="form-control" name="catatan_proc" id="catatan_proc" readonly="readonly"></textarea>
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
													<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload"><i class="fa fa-upload"></i></button>
												</div>
												<div class="input-group-btn">
													<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>
												</div>
											</div>
                                          
                                        </div>
		              				</div>
		              			</div>
		              		</div>
		            	</div>

						<div class="box-footer">
							<input type="hidden"
								   name="action" value='submit'>
							<div id="counter"></div>
							<button type="button"
									class="btn btn-sm btn-success"
									name="action_btn"
									value="submit">Submit
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/kiass/transaksi/add.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>

