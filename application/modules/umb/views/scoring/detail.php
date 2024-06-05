<!--
	/*
        @application  : UMB (Uang Muka Bokar)
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 24-Oct-18
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css">
<style type="text/css">
	/*fancy fancy XD*/
	.pr{
	    padding-right: 5px;
	}
	.icons_white{
	    color: #ffffff;
	    padding-right: 5px;
	}
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success page-wrapper"
					 style="display: none">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
			    		<div class="btn-group pull-right pr">
							<button type="button"
									class="btn btn-sm btn-primary"
									id="log_status"><i class="fa fa-info-circle icons_white"></i> HISTORY
							</button>
						</div>
						<?php if ($summary[0]->status == '9' && $session_role[0]->level == '10'): ?>
				    		<div class="btn-group pull-right pr">
										<a href="<?php echo base_url() . 'umb/scoring/cetak/' .  $no_form; ?>"
								   class="btn btn-sm btn-warning pull-right"><i class="fa fa-print icons_white"></i>&nbsp;&nbsp;PRINT</a>
				    		</div>
				    		<div class="btn-group pull-right pr">
								<button type="button"
										class="btn btn-sm btn-success"
										id="upload_form"><i class="fa fa-upload icons_white"></i> UPLOAD
								</button>	
							</div>
						<?php endif ?>
						<?php if ($summary[0]->status == 'finish' || $summary[0]->status == 'completed'): ?>
				    		<div class="btn-group pull-right pr">
								<button type="button"
										class="btn btn-sm bg-purple"
										id="upload_mou"><i class="fa fa-upload icons_white"></i> MOU
								</button>	
							</div>
						<?php endif ?>
					</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-6 form-horizontal">
									<div class="form-group">
										<label for="pabrik"
											   class="col-sm-4 control-label">Pabrik</label>
										<div class="col-sm-8">
											<select class="form-control select2 readonly"
													name="pabrik"
													style="width: 100%;"
													required="required">
												<option value="0">Silahkan pilih</option>
												<?php
													if ($plant) {
														$output = "";
														foreach ($plant as $pl) {
															$output .= "<option value='" . $pl->plant . "'>" . $pl->nama . "</option>";
														}
														echo $output;
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group hide2 hide3 hide4">
										<label for="supplier"
											   class="col-sm-4 control-label">Supplier</label>
										<div class="col-sm-8">
											<select class="form-control select2 readonly"
													name="supplier"
													style="width: 100%;"
													required="required">
											</select>
										</div>
									</div>
									<div class="form-group hide1">
										<label for="depo"
											   class="col-sm-4 control-label">Depo</label>
										<div class="col-sm-8">
											<select class="form-control select2 readonly"
													name="depo"
													style="width: 100%;"
													required="required">
												<option value="0">Silahkan pilih</option>
											</select>
										</div>
									</div>
									<div class="form-group hide1 hide2 hide3">
										<label for="depo"
												class="col-sm-4 control-label">Direktur Operasional</label>
										<div class="col-sm-8">
											<select class="form-control select2 readonly"
													name="dirops"
													style="width: 100%;"
													required="required">
												<option value="0">Silahkan pilih</option>
												<?php
													if ($dirops) {
														$output = "";
														foreach ($dirops as $dir) {
															$output .= "<option value='" . $dir->id . "'>" . $dir->NAME1 . " ( ". $dir->EKORG." ) </option>";
														}
														echo $output;
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group hide1 hide2">
										<label for="depo"
											   class="col-sm-4 control-label">Jarak Tempuh</label>
										<div class="col-sm-8">
											<input type="text"
												   name="jarak_tempuh"
												   class="angka form-control readonly"
												   placeholder="Masukkan Jarak Tempuh"
												   min="0"
												   required="required" />
										</div>
									</div>
									<div class="form-group">
										<label for="provinsi[]"
											   class="col-sm-4 control-label">Provinsi (Sumber Bokar)</label>
										<div class="col-sm-8">
											<select class="form-control select2 readonly"
													name="provinsi[]"
													style="width: 100%;"
													required="required"
													multiple="multiple">
												<?php
													if ($provinsi) {
														$output = "";
														foreach ($provinsi as $pr) {
															$output .= "<option value='" . $generate->kirana_encrypt($pr->id) . "'>" . $pr->nama_provinsi . "</option>";
														}
														echo $output;
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="kabupaten[]"
											   class="col-sm-4 control-label">Kabupaten/Kota <br>(Sumber Bokar)</label>
										<div class="col-sm-8">
											<select class="form-control select2 readonly"
													name="kabupaten[]"
													style="width: 100%;"
													required="required"
													multiple="multiple">
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="tgl_pengajuan"
											   class="col-sm-4 control-label">Tanggal Pengajuan</label>
										<div class="col-sm-8">
											<input type="text"
												   name="tgl_pengajuan"
												   class="form-control readonly"
												   placeholder="Masukkan Tanggal Pengajuan"
												   required="required"
												   id="tgl_pengajuan"
												   value="<?php echo date("d.m.Y"); ?>" />
										</div>
									</div>
									<div class="form-group hide3 hide4">
										<label for="supply_since"
											   class="col-sm-4 control-label">Supply Sejak</label>
										<div class="col-sm-8">
											<input type="text"
												   name="supply_since"
												   class="form-control readonly"
												   placeholder="Masukkan Tanggal Awal Supply"
												   required="required"
												   readonly="readonly"
											/>
										</div>
									</div>
									<div class="form-group hide3 hide4">
										<label
											class="col-sm-4 control-label">Lama Bergabung</label>
										<div class="col-sm-8">
											<input type="text"
												   name="lama_join"
												   class="form-control readonly"
												   readonly="readonly" />
										</div>
									</div>
								</div>
								<div class="col-sm-6 form-horizontal">
									<div class="form-group">
										<label for="pabrik"
											   class="col-sm-4 control-label">No Form</label>
										<div class="col-sm-8">
											<input type="text"
												   class="form-control readonly"
												   name="no_form"
												   placeholder="Masukkan No Form"
												   readonly="readonly"
												   required="required"
												   value="<?php echo str_replace("-", "/", $no_form); ?>">
										</div>
									</div>

									<div class="form-group">
										<label for="npwp"
											   class="col-sm-4 control-label">File KTP</label>
										<div class="col-sm-8">
											<div class="input-group">
												<input type=""
													   class="form-control"
													   name="caption_file_ktp"
													   required="required"
													   readonly="readonly">
												<div class="input-group-btn">
													<button type="button"
															class="btn btn-default btn-flat lihat-file data-lihat-file"
															data-title="File" id="view_ktp"
															title="Lihat file"><i class="fa fa-search"></i>
													</button>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="npwp"
											   class="col-sm-4 control-label">File NPWP</label>
										<div class="col-sm-8">
											<div class="input-group">
												<input type=""
													   class="form-control"
													   name="caption_file_npwp"
													   required="required"
													   readonly="readonly">
												<div class="input-group-btn">
													<button type="button"
															class="btn btn-default btn-flat lihat-file data-lihat-file"
															data-title="File" id="view_npwp"
															title="Lihat file"><i class="fa fa-search"></i>
													</button>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="pabrik"
											   class="col-sm-4 control-label">UM Yang Diajukan</label>
										<div class="col-sm-8">
											<input type="text"
												   class="angka form-control readonly text-right"
												   name="um_propose"
												   min="0"
												   placeholder="Masukkan UM Yang Diajukan"
												   required="required">
										</div>
									</div>
									<div class="form-group">
										<label for="pabrik"
											   class="col-sm-4 control-label">Waktu Penyelesaian</label>
										<div class="col-sm-8">
											<div class="input-group">
												<input type="text"
													   class="angka form-control readonly"
													   name="waktu"
													   min="0"
													   placeholder="Masukkan Waktu Penyelesaian"
													   required="required">

												<div class="input-group-addon">
													Hari
												</div>
											</div>
										</div>
									</div>
									<div class="form-group hide2 hide3">
										<label for="pabrik"
											   class="col-sm-4 control-label">Max. UM Outstanding</label>
										<div class="col-sm-8">
											<div class="input-group">
												<input type="text"
													   class="angka form-control readonly"
													   name="max_um_outs"
													   placeholder="Masukkan Max. UM Outstanding"
													   required="required"
													   value="2"
													   readonly="readonly">

												<div class="input-group-addon">
													Kali
												</div>
											</div>
										</div>
									</div>
									<div class="form-group hide4">
										<label for="jaminan-check"
											   class="col-sm-4 control-label">Jaminan</label>
										<div class="col-sm-8">
											<label class="control-label">
												<input type="checkbox"
													   class="kiranaCheckbox isJaminan" disabled
													   checked>
												<span>Ada</span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div> <!-- TOP FORM -->
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<fieldset class="fieldset-success">
										<legend class="text-center">Detail Form Scoring</legend>
										<div class="nav-tabs-custom">
											<ul class="nav nav-tabs">
												<li class="active hide4"><a href="#supp-tab"
																			data-toggle="tab">Supplier</a></li>
												<li class="hide3 hide4"><a href="#kriteria-tab"
																		   data-toggle="tab">Kriteria</a></li>
												<li class="hide4"><a href="#jaminan-tab"
																	 data-toggle="tab">Jaminan</a></li>
												<li class="hs-tab"><a href="#historical-tab"
																	 data-toggle="tab">Historical</a></li>
											</ul>
										</div>
										<div class="tab-content">
											<div class="tab-pane active hide4"
												 id="supp-tab">
												<div class="row">
													<div class="col-sm-12">
														<label>Profil Supplier/Depo/Mitra</label>
														<table class="table table-bordered table-striped table-responsive my-datatable-extends-order table-supplier"
															   data-paging="false"
															   data-searching="false"
															   data-info="false"
															   data-ordering="false"
															   data-scrollx="true"
															   data-textright="1-2-5"
															   data-textcenter="0-3-4"
															   style="padding-bottom:0px !important;">
															<thead>
																<tr>
																	<th rowspan="3"
																		class="text-center">Bulan
																	</th>
																	<th rowspan="1"
																		colspan="5"
																		class="text-center">Supplier
																	</th>
																</tr>
																<tr>
																	<th rowspan="1"
																		colspan="5"
																		class="text-center">Faktur
																	</th>
																</tr>
																<tr>
																	<th rowspan="1"
																		colspan="1"
																		class="text-center">Qty Suplai
																	</th>
																	<th rowspan="1"
																		colspan="1"
																		class="text-center">Qty Suplai/Minggu<br>(Ton
																							Kering)
																	</th>
																	<th rowspan="1"
																		colspan="1"
																		class="text-center">Total Kedatangan<br>(per
																							Minggu)
																	</th>
																	<th rowspan="1"
																		colspan="1"
																		class="text-center">Jumlah HK
																	</th>
																	<th rowspan="1"
																		colspan="1"
																		class="text-center">Qty Suplai/Hari Beli<br>(Ton
																							Kering)
																	</th>
																</tr>
															</thead>
															<tbody></tbody>
														</table>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-8 form-horizontal desc-supp">
														<h4><strong>Kelas : <code>[kelas]</code></strong></h4>
														<div class="form-group"
															 style="margin-bottom: 0">
															<label class="col-sm-6 control-label"
																   style="margin-bottom: 0;">Jumlah kedatangan dalam
																<span style="margin-bottom: 0;">[angka]</span>
																							 bulan terakhir</label>
															<div class="col-sm-6 control-label text-left">
																<code>[angka1]</code> dari <code>[angka2]</code> bulan
															</div>
														</div>
														<div class="form-group"
															 style="margin-bottom: 0">
															<label class="col-sm-6 control-label"
																   style="margin-bottom: 0;">Frekuensi kedatangan /
																							 minggu dalam 6
																							 bulan</label>
															<div class="col-sm-6 control-label text-left">
																<code>[angka]</code> x seminggu
															</div>
														</div>
														<div class="form-group"
															 style="margin-bottom: 0">
															<label class="col-sm-6 control-label"
																   style="margin-bottom: 0;">Tren suplai harian 4 bulan
																							 pertama dalam 6
																							 bulan</label>
															<div class="col-sm-6 control-label text-left">
																<code>[angka]</code></div>
														</div>
														<div class="form-group"
															 style="margin-bottom: 0">
															<label class="col-sm-6 control-label"
																   style="margin-bottom: 0;">Tren suplai harian 2 bulan
																							 terakhir</label>
															<div class="col-sm-6 control-label text-left">
																<code>[angka]</code></div>
														</div>
														<div class="form-group"
															 style="margin-bottom: 0">
															<label class="col-sm-6 control-label"
																   style="margin-bottom: 0;">Growth 2-4 6 bulan
																							 terakhir</label>
															<div class="col-sm-6 control-label text-left">
																<code>[angka]</code> atau <code>[angka]%</code></div>
														</div>
													</div>
												</div>
											</div>
											<div class="tab-pane hide3 hide4"
												 id="kriteria-tab">
												<div class="row">
													<div class="col-sm-12">
														<label>Kriteria Scoring</label>
														<table class="table table-bordered table-striped table-responsive my-datatable-extends-order table-kriteria"
															   data-paging="false"
															   data-searching="false"
															   data-info="false"
															   data-ordering="false"
															   data-scrollx="true"
															   data-textright="6-7"
															   data-textcenter="1-2-3-4-5"
															   style="padding-bottom:0px !important;">
															<thead>
																<th class="text-left">Kriteria</th>
																<th class="text-center">Bobot</th>
																<?php
																	if ($kriteria) {
																		$max = 0;
																		foreach ($kriteria as $k) {
																			$detail = rtrim($k->list_detail, ",");
																			$kolom  = explode(",", $detail);
																			if ($max < count($kolom))
																				$max = count($kolom);
																		}
																		$output = "";
																		for ($i = 0; $i < $max; $i++) {
																			$output .= '<th class="text-center">' . ($i + 1) . '</th>';
																		}
																		echo $output;
																	}
																?>
																<th class="text-center">Nilai</th>
																<th class="text-center">Score</th>
															</thead>
															<tbody></tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="tab-pane hide4"
												 id="jaminan-tab">
												<div class="row">
													<div class="col-sm-12">
														<div style="margin-bottom: 20px;">
															<label>List Jaminan</label>
															<button type="button"
																	style="margin: 0 5px;"
																	class="btn btn-sm btn-danger pull-right delete-pemilik-jaminan readonly hidden">
																Hapus
															</button>
															<button type="button"
																	style="margin: 0 5px;"
																	class="btn btn-sm btn-success pull-right add-pemilik-jaminan readonly hidden">
																Tambah
															</button>
														</div>
														<div style="width: 100%; overflow-x: auto">
															<table class="table table-bordered table-striped table-responsive table-jaminan"
																   style="margin-bottom: 0;">
																<thead>
																	<th class='text-center'>No</th>
																	<th class='text-center'>Nama</th>
																	<th class='text-center'>Detail</th>
																	<th class='text-center'>Nilai Appraisal</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
											<div class="tab-pane"
												 id="historical-tab">
												 <div class="row">
													<div class="col-sm-12">
														<label for="title" class="hs-nama">Nama Supplier</label>
													</div>
												 </div>
												 <div class="row">		
													<div class="col-sm-3 pull-right">
														<div class="form-group">
															<label>Date To :</label>
															<div class="input-group date">
																<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
																<div id="div_filter_to">
																	<input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" value="<?php echo date('d.m.Y');?>" id="sampai" name="sampai" readonly>
																</div>
															</div>
														</div>
													</div>
													<div class="col-sm-3 pull-right">
														<div class="form-group">
															<label>Date From :</label>
															<div class="input-group date">
																<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
																<input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" value="<?php echo '01.01.'.date('Y');?>" id="dari" name="sampai" readonly>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12">
														<div class="box box-success box-solid collapsed-box">
															<div class="box-header with-border">
																<h3 class="box-title" style="font-size: 
																				15px;">Historical UM</h3>
																<div class="box-tools pull-right">
																	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
																	</button>
																</div>
															</div>

															<div class="box-body">
																
																<div class="col-sm-12 form-horizontal">	
																	<div class="form-group">
																		<div style="width: 100%; overflow-x: auto">
																			<table class="table table-bordered table-striped table-responsive my-datatable-extends-order table-hs"
																				style="margin-bottom: 0;">
																				<thead>
																					<th class='text-center'>No Form</th>
																					<th class='text-center'>Nilai UM Diajukan</th>
																					<th class='text-center'>Nilai UM Disetujui</th>
																					<th class='text-center'>Tanggal Final Approve</th>
																					<th class='text-center'>Tanggal Berakhir</th>
																				</thead>
																				<tbody></tbody>
																			</table>
																		</div>
																	</div>															
																</div>
															</div>
															<div class="box-footer">
																<div class="col-sm-6 form-horizontal">
																	<div class="form-group">
																		<label for="supplier"
																			class="col-sm-4 control-label">Plafond Awal (Adjustment)</label>
																		<div class="col-sm-8">
																			<input type='text' class="form-control text-right"
																					name="plafond_awal"
																					style="width: 100%;"
																					readonly="readonly">
																		</div>
																	</div>
																</div>
																<div class="col-sm-6 form-horizontal">
																	<div class="form-group">
																		<label for="supplier"
																			class="col-sm-4 control-label">Plafond Baru</label>
																		<div class="col-sm-8">
																			<input type='text' class="form-control text-right"
																					name="plafond_baru"
																					style="width: 100%;"
																					readonly="readonly">
																		</div>
																	</div>
																</div>
															</div>															
														</div>
														
													</div>
												</div>

												<div class="row">
													<div class="col-sm-12">
														<div class="box box-success box-solid collapsed-box">
															<div class="box-header with-border">
																<h3 class="box-title" style="font-size: 
																				15px;">Historical PO</h3>
																<div class="box-tools pull-right">
																	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
																	</button>
																</div>
															</div>

															<div class="box-body">

																<div class="col-sm-12">
																	<table class="table table-bordered table-striped table-responsive my-datatable-extends-order table-historical"
																		data-paging="false"
																		data-searching="false"
																		data-info="false"
																		data-ordering="false"
																		data-scrollx="true"
																		style="padding-bottom:0px !important;">
																		<thead>
																			<tr>
																				<th rowspan="1"
																					class="text-center">Bulan
																				</th>
																			<!-- </tr>
																			<tr> -->
																				<th rowspan="1"
																					colspan="1"
																					class="text-center">Tanggal PO
																				</th>
																				<th rowspan="1"
																					colspan="1"
																					class="text-center">Qty Kering
																				</th>
																				<th rowspan="1"
																					colspan="1"
																					class="text-center">Nilai PO
																				</th>
																				<th rowspan="1"
																					colspan="1"
																					class="text-center">Harga
																				</th>
																			</tr>
																		</thead>
																		<tbody></tbody>
																		<tfoot>
																		<tr>
																			<th class="text-right">Grand Total</th>
																			<th class="text-right tpo"></th>
																			<th class="text-right tqty"></th>
																			<th class="text-right tnpo"></th>
																			<th></th>
																		</tr>
																	</tfoot>
																	</table>
																</div>
															</div>
															<div class="box-footer">
																<div class="col-sm-6 form-horizontal">
																	<div class="form-group">
																		<label for="supplier"
																			class="col-sm-4 control-label">Average Nilai PO</label>
																		<div class="col-sm-8">
																			<input type='text' class="form-control text-right"
																					name="avg_nilai_po"
																					style="width: 100%;"
																					readonly="readonly">
																		</div>
																	</div>
																</div>
																<div class="col-sm-6 form-horizontal">
																	<div class="form-group">
																		<label for="supplier"
																			class="col-sm-4 control-label">Average Nilai PO Over Plafond</label>
																		<div class="col-sm-8">
																			<input type='text' class="form-control text-right"
																					name="avg_nilai_po_over"
																					style="width: 100%;"
																					readonly="readonly">
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>	
											</div>
										</div>
									</fieldset>
								</div>
							</div>
						</div> <!-- MIDDLE FORM -->
						<div class="box-body hide1 hide2 hide3">
							<div class="row">
								<div class="col-sm-12">
									<fieldset class="fieldset-success">
										<legend class="text-center">Proses Ranger / Tender</legend>
										<div class="row">
											<div class="col-sm-6 form-horizontal">
												<div class="form-group hide1 hide2 hide3">
													<label for="depo"
														   class="col-sm-4 control-label text-left">Tipe Fee</label>
													<div class="col-sm-8">
													</div>
												</div>
												<div class="form-group hide1 hide2 hide3">
													<div class="checkbox">
														<label class="col-sm-4 control-label text-left">
															<input type="checkbox"
																   name="fee_check"
																   class="kiranaCheckbox readonly"> Fee Non Tax
														</label>
														<div class="col-sm-8">
															<input type="text"
																   class="angka form-control text-right readonly"
																   name="fee_non_tax"
																   min="0"
																   readonly="readonly">
														</div>
													</div>
												</div>
												<div class="form-group hide1 hide2 hide3">
													<div class="checkbox">
														<label class="col-sm-4 control-label text-left">
															<input type="checkbox"
																   name="fee_check"
																   class="kiranaCheckbox readonly"> Fee Tax Gross
														</label>
														<div class="col-sm-8">
															<input type="text"
																   class="angka form-control text-right readonly"
																   name="fee_tax_gross"
																   min="0"
																   readonly="readonly">
														</div>
													</div>
												</div>
												<div class="form-group hide1 hide2 hide3">
													<div class="checkbox">
														<label class="col-sm-4 control-label text-left">
															<input type="checkbox"
																   name="fee_check"
																   class="kiranaCheckbox readonly"> Fee Non Gross
														</label>
														<div class="col-sm-8">
															<input type="text"
																   class="angka form-control text-right readonly"
																   name="fee_non_gross"
																   min="0"
																   readonly="readonly">
														</div>
													</div>
												</div>
											</div>
											<div class="col-sm-6 form-horizontal">
												<div class="form-group hide1 hide2 hide3">
													<label for="depo"
														   class="col-sm-4 control-label">Point of Purchase</label>
													<div class="col-sm-8">
														<select class="form-control select2 readonly"
																name="pop"
																style="width: 100%;"
																required="required">
															<option value="0">Silahkan pilih</option>
															<option value="pabrik">Pabrik</option>
															<option value="lokasi ranger">Lokasi Ranger</option>
														</select>
													</div>
												</div>
												<div class="form-group hide1 hide2 hide3">
													<label for="depo"
														   class="col-sm-4 control-label">Vendor</label>
													<div class="col-sm-8">
														<select class="form-control select2 readonly"
																name="vendor_nonbkr"
																style="width: 100%;"
																required="required">
														</select>
													</div>
												</div>
												<div class="form-group hide1 hide2 hide3">
													<label class="col-sm-4 control-label">Attachment</label>
													<div class="col-sm-8">
														<div class="input-group">
															<input type="text" class="form-control caption_file" name="caption_file_ranger" required="required" readonly="readonly">
															<div class="input-group-btn">
																<input type="file" name="ranger_file[]" class="form-control upload_file berkas" style="display:none;">
																<button type="button" class="btn btn-default btn-flat btn_upload_file" id="btn-ranger" data-title="Upload" disabled><i class="fa fa-upload"></i></button>
															</div>
															<div class="input-group-btn">
																<button type="button" class="btn btn-default btn-flat view_file" data-link="" data-col="8" id="view_ranger" data-title="Lihat file"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<fieldset class="fieldset-success">
										<legend class="text-center">Summary</legend>
										<div class="row">
											<div class="col-sm-6 form-horizontal">
												<div class="form-group">
													<label for="pabrik"
														   class="col-sm-4 control-label">UM Yang Diajukan</label>
													<div class="col-sm-8">
														<input type="text"
															   class="angka form-control text-right readonly"
															   name="um_propose_summary"
															   readonly="readonly">
													</div>
												</div>
												<div class="form-group hide3 hide4">
													<label for="pabrik"
														   class="col-sm-4 control-label">UM Scoring</label>
													<div class="col-sm-8">
														<input type="text"
															   class="angka form-control text-right readonly"
															   name="um_scoring_summary"
															   readonly="readonly">
													</div>
												</div>
												<div class="form-group hide4">
													<label for="pabrik"
														   class="col-sm-4 control-label">UM Nilai Jaminan</label>
													<div class="col-sm-8">
														<input type="text"
															   class="angka form-control text-right readonly"
															   name="um_nilai_jaminan_summary"
															   readonly="readonly">
													</div>
												</div>
												<div class="form-group hide3 hide4">
													<label for="pabrik"
														   class="col-sm-4 control-label">UM Yang Disetujui</label>
													<div class="col-sm-8">
														<input type="text"
															   class="angka form-control text-right readonly"
															   name="um_setuju_summary"
															   readonly="readonly">
													</div>
												</div>
												<div class="form-group hide_file_ceo hide">
													<label for="pabrik"
														   class="col-sm-4 control-label">File CEO Group</label>
													<div class="col-sm-8">
														<div class="input-group">
															<input type=""
																   class="form-control"
																   name="caption_file_ceo"
																   required="required"
																   readonly="readonly">
															<div class="input-group-btn">
																<button type="button"
																		class="btn btn-default btn-flat lihat-file data-lihat-file"
																		data-title="File" id="view_bukti_ceo"
																		title="Lihat file"><i class="fa fa-search"></i>
																</button>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group hide_file_stop_um hide">
													<label for="pabrik"
														   class="col-sm-4 control-label">File Pemberhentian Kerjasama</label>
													<div class="col-sm-8">
														<div class="input-group">
															<input type=""
																   class="form-control"
																   name="caption_file_stop_um"
																   required="required"
																   readonly="readonly">
															<div class="input-group-btn">
																<button type="button"
																		class="btn btn-default btn-flat lihat-file data-lihat-file"
																		data-title="File" id="view_stop_um"
																		title="Lihat file"><i class="fa fa-search"></i>
																</button>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group hide3">
													<label for="pabrik"
														   class="col-sm-4 control-label">Rekomendasi UM</label>
													<div class="col-sm-8">
														<!-- <input type="text"
															   class="angka form-control text-right readonly"
															   name="um_rekom_summary"
															   readonly="readonly"> -->
														<div class="box box-success box-solid">
												            <div class="box-header with-border">
												              <h3 class="box-title" style="font-size: 
												              15px;">Lihat Rekomendasi</h3>
												              <div class="box-tools pull-right">
												                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
												                </button>
												              </div>
												            </div>

												            <div class="box-body" style="">
												              <table class="table table-bordered">
												              	<thead>
												              		<th>Role</th>
												              		<th>UM</th>
												              		<th>Komentar</th>
												              		<!-- <th>Tanggal</th> -->
												              	</thead>
												              	<tbody>
														<?php 	if (count($rekom_um_app) > 0 ){
																	$counter = 0; 
												              		 foreach($rekom_um_app as $rup){
														              		// if (isset($rup->rekom_um_app) && $rup->rekom_um_app > 0) {
												              		 	
														              		if (isset($rup->rekom_um_app)) {
															              		echo "<tr>";
															              		echo "<td>".$rup->nama_role."</td>";
															              		echo "<td>".number_format($rup->rekom_um_app, 2)."</td>";
															              		echo "<td>".$rup->comment."</td>";
															              		echo "</tr>";
															              		$counter++;
														              		}
														              	}
														            if ($counter == 0) {
																	 	echo "<tr>";
													              		echo "<td colspan='2'>Tidak ada rekomendasi.</td>";
													              		echo "</tr>";
														            }
																 } 
																  ?>
												              	</tbody>
												              </table>
												            </div>
												           
												        </div>
													</div>
												</div>
											</div>
											<div class="col-sm-6 form-horizontal">
												<div class="form-group">
													<label for="pabrik"
														   class="col-sm-4 control-label">Waktu Penyelesaian</label>
													<div class="col-sm-8">
														<div class="input-group">
															<input type="text"
																   class="angka form-control readonly"
																   name="waktu_summary"
																   readonly="readonly">

															<div class="input-group-addon">
																Hari
															</div>
														</div>
													</div>
												</div>
												<div class="form-group hide hide2 hide3">
													<label for="pabrik"
														   class="col-sm-4 control-label">Max. UM Outstanding</label>
													<div class="col-sm-8">
														<div class="input-group">
															<input type="text"
																   class="angka form-control readonly"
																   name="max_um_outs_summary"
																   value="2"
																   readonly="readonly">

															<div class="input-group-addon">
																Kali
															</div>
														</div>
													</div>
												</div>
												<div class="form-group hide_no_sap hide">
													<label for="pabrik"
														   class="col-sm-4 control-label">Nomor SAP</label>
													<div class="col-sm-8">
														<input type="text"
															   class="angka form-control readonly"
															   name="no_sap"
															   id="no_sap"
															   readonly="readonly">
													</div>
												</div>
											</div>
										</div>
									</fieldset>
								</div>
							</div>
						</div> <!-- BOTTOM FORM -->
						<div class="box-footer">
							<input type="hidden" name="session_role_level" value="<?php echo $session_role[0]->level; ?>">
							<input type="hidden" name="session_role_isRekom" value="<?php echo $session_role[0]->is_rekom; ?>">
							<input type="hidden" name="session_role_nama" value="<?php echo $session_role[0]->nama_role; ?>">
							<input type="hidden" name="status_scoring" value="<?php echo $summary[0]->status; ?>">
							<input type="hidden" name="status_mou" value="<?php echo $summary[0]->status_mou; ?>">
							<div class="callout callout-danger notes_penilaian_jaminan hide">
							  	<h4>Perhatian!</h4>
							  	<p style="font-size: 15px;">Tidak dapat melanjutkan approval. Mohon untuk meminta <strong><i>Manager Kantor</i></strong> untuk <strong><i>melengkapi Penilaian Jaminan</i></strong> Pada Form Scoring ini.</p>
						  	</div>

							<?php
								if ($summary[0]->status == $session_role[0]->level && $summary[0]->status > 0) {
									echo '<input type="hidden" name="tipe_scoring">';
									echo '<input type="hidden" name="id_scoring">';
									if ($session_role[0]->if_approve)
										echo '<button type="button" class="btn btn-success btn-role flag_penilaian_jaminan" name="action_btn" value="approve">Approve</button>';
									if ($session_role[0]->if_assign)
										echo '<button type="button" class="btn btn-info btn-role flag_penilaian_jaminan" name="action_btn" value="assign">Assign</button>';
									if ($session_role[0]->if_decline)
										echo '<button type="button" class="btn btn-warning btn-role" name="action_btn" value="decline">Decline</button>';
									if ($session_role[0]->if_drop)
										echo '<button type="button" class="btn btn-danger btn-role flag_penilaian_jaminan" name="action_btn" value="drop">Drop</button>';
								}
								if ($summary[0]->status == 'completed' && ($session_role[0]->level == '5' || $session_role[0]->level == '51') ) {
									echo '<button type="button" class="btn btn-danger btn-role flag_penilaian_jaminan" name="action_btn" value="stop">Stop</button>';
								}
							?>
						</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<style>
	@media (min-width: 768px) {
		.dl-horizontal dt {
			white-space: normal;
		}
	}
</style>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/umb/scoring/detail.js"></script>
