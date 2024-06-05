<!--
	/*
        @application  : UMB (Uang Muka Bokar)
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 26-Sep-18
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc...
    */
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css">

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
					</div>
					<form class="form-ba-um"
						  enctype="multipart/form-data">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-6 form-horizontal">
									<div class="form-group">
										<label for="pabrik"
											   class="col-sm-4 control-label">Pabrik</label>
										<div class="col-sm-8">
											<input type="hidden"
												   name="plafon_pabrik"
												   >
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
											<div class="input-group">
												<input type="text"
													   name="jarak_tempuh"
													   class="angka form-control readonly"
													   placeholder="Masukkan Jarak Tempuh"
													   min="0"
													   required="required"/>

												<div class="input-group-addon">
													KM
												</div>
											</div>
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
												   value="<?php echo date("d.m.Y"); ?>"
												   readonly="readonly"/>

										</div>
									</div>

									<!-- tambahan end date -->
									<div class="form-group">
										<label for="tgl_berakhir"
											   class="col-sm-4 control-label">Tanggal Berakhir</label>
										<div class="col-sm-8">
											<input type="text"
												   name="tgl_berakhir"
												   class="form-control readonly"
												   placeholder="Masukkan Tanggal Berakhir"
												   required="required"
												   id="tgl_berakhir"
												   />

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
											<div class="input-group">
												<input type="text"
												   name="lama_join"
												   class="form-control readonly"
												   readonly="readonly" />
												<div class="input-group-addon">
													Tahun
												</div>
											</div>
										</div>
									</div>
                                    <div class="form-group">
										<label for="ba"
											   class="col-sm-4 control-label">File Berita Acara</label>
										<div class="col-sm-8">
											<div class="input-group">
												<input type=""
													   class="form-control"
													   name="caption_file_ba"
													   required="required"
													   readonly="readonly">
												<div class="input-group-btn">
													<button type="button"
															class="btn btn-default btn-flat lihat-file data-lihat-file"
															data-title="File Berita Acara" id="view_ba"
															title="Lihat file"><i class="fa fa-search"></i>
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 form-horizontal">
									<div class="form-group">
										<label for="pabrik"
											   class="col-sm-4 control-label">No Form</label>
										<div class="col-sm-8">
											<input type="text"
												   class="form-control"
												   name="no_form"
												   value="<?php echo str_replace("-", "/", $no_form); ?>"
												   readonly="readonly"
												   required="required">
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
															data-title="File KTP" id="view_ktp"
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
															data-title="File NPWP" id="view_npwp"
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
												   class="angka form-control text-right readonly"
												   name="um_propose"
												   min="0"
												   required="required">
												   <small><em class="sisa_plafons" style="color:red;"></em></small>
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
                                    <div class="form-group">
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
						</div> <!-- TOP FORM -->

						<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<fieldset class="fieldset-success">
										<legend class="text-center">Historical</legend>

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
									</fieldset>
								</div>
							</div>
						</div> <!-- MIDDLE FORM -->
			
						<div class="box-footer">
							<input type="hidden"
								   name="tipe_scoring">
							<input type="hidden"
								   name="tipe_scoring_text">
							<input type="hidden"
								   name="id_scoring">
							<input type="hidden"
								   name="action">
						</div>
					</form>
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
<script src="<?php echo base_url() ?>assets/apps/js/umb/scoring/ba_detail.js"></script>
