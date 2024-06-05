<!--
	/*
        @application  :
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 21-Dec-18
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
	  href="<?php echo base_url() ?>assets/apps/css/order/order.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success page-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
					</div>
					<form class="form-tambah-order"
						  enctype="multipart/form-data">
						<div class="box-body">
							<!--START HEADER-->
							<div class="row row-form 1 3"
								 id="form-header">
								<div class="col-sm-6 form-horizontal">
									<div class="form-group">
										<label for="kepada"
											   class="col-sm-4 control-label text-left">Kepada</label>
										<div class="col-sm-8">
											<select class="form-control select2"
													name="kpd"
													style="width: 100%;"
													required="required">
												<option value="0">Silahkan pilih</option>
												<?php
													if ($kepada) {
														foreach ($kepada as $k) {
															echo "<option value='" . $k->nik . "'>" . $k->nama . " - " . $k->caption . "</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="tujuan_inv"
											   class="col-sm-4 control-label text-left">Tujuan Investasi</label>
										<div class="col-sm-8">
											<select class="form-control select2"
													name="tujuan_inv"
													id="tujuan_inv"
													style="width: 100%;"
													required="required">
												<option value="0">Silahkan pilih</option>
												<?php
													if ($tujuan) {
														foreach ($tujuan as $tj) {
															echo "<option value='" . $generate->kirana_encrypt($tj->id_mtujuan_inv) . "'";
															echo ">" . $tj->tujuan_inv . "</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="perihal"
											   class="col-sm-4 control-label text-left">Perihal</label>
										<div class="col-sm-8">
											<input type="text"
												   class="form-control"
												   name="perihal"
												   id="perihal"
												   placeholder="Perihal"
												   required="required">
										</div>
									</div>
									<div class="form-group">
										<label for="pic_proj"
											   class="col-sm-4 control-label text-left">PIC Proyek</label>
										<div class="col-sm-8">
											<select class="form-control select2"
													name="pic_proj"
													id="pic_proj"
													style="width: 100%;"
													required="required">
												<option value="0">Silahkan pilih</option>
												<?php
													if ($pic_proj) {
														foreach ($pic_proj as $p) {
															echo "<option value='" . $p->nik . "'>" . $p->nama . " - " . $p->posst . "</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-6 form-horizontal">
									<div class="form-group">
										<label for="no_pi"
											   class="col-sm-4 control-label text-left">No. PI</label>
										<div class="col-sm-8">
											<input type="text"
												   class="form-control"
												   name="no_pi"
												   id="no_pi"
												   placeholder="Masukkan No PI"
												   value="<?php echo $no_pi; ?>"
												   readonly="readonly"
												   required="required">
										</div>
									</div>
									<div class="form-group">
										<label for="tanggal"
											   class="col-sm-4 control-label text-left">Tanggal</label>
										<div class="col-sm-8">
											<div class="input-group date">
												<input type="text"
													   class="form-control kiranadatepicker"
													   data-format="yyyy-mm-dd"
													   data-autoclose="true"
													   id="tanggal"
													   name="tanggal"
													   required="required"
													   value="<?php echo date("Y-m-d"); ?>">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="pic_pemb"
											   class="col-sm-4 control-label text-left">PIC Pembelian</label>
										<div class="col-sm-8">
											<select class="form-control select2"
													name="pic_pemb"
													id="pic_pemb"
													style="width: 100%;"
													required="required">
												<option value="0">Silahkan pilih</option>
												<option value="Pabrik">Pabrik</option>
												<option value="Head Office">Head Office</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<!--END HEADER-->

							<!--START KATALOG-->
							<div class="row row-form 2 hidden"
								 id="form-katalog">
								<div class="col-sm-12">
									<div class="row">
										<div class="form-horizontal col-sm-6">
											<h4>FILTER BY :</h4>
											<div class="form-group">
												<label for="mesin"
													   class="col-sm-3 control-label text-left">Jenis</label>
												<div class="col-sm-9">
													<select class="form-control select2"
															name="jenis"
															data-katalog="order"
															style="width: 100%;">
														<option value="0">Silahkan pilih</option>
														<option value="B">Mesin</option>
														<option value="C">Komponen</option>
													</select>
												</div>
											</div>
											<div class="form-group hidden"
												 id="filter_mesin">
												<label for="mesin"
													   class="col-sm-3 control-label text-left">Mesin</label>
												<div class="col-sm-9">
													<select class="form-control select2"
															name="mesin"
															data-katalog="order"
															style="width: 100%;">
														<option value="0">Silahkan pilih</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-3 pull-right">
											<div class="form-group">
												<div class="input-group">
													<input type="text"
														   class="form-control"
														   name="search"
														   data-katalog="order"
														   placeholder="Search ...">
													<span class="input-group-addon"><i class="fa fa-search"></i>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<ol class="breadcrumb breadcrumb-right-arrow">
												<li class="breadcrumb-item active"><a href="#">Katalog</a></li>
											</ol>
										</div>
									</div>
									<div class="row katalog-product">
									</div>
								</div>
								<div class="col-sm-12 text-center">
									<ul class="pagination pagination-wrapper">

									</ul>
								</div>
							</div>
							<!--END KATALOG-->

							<!--START SUMMARY-->
							<div class="row row-form 3 hidden">
								<div class="col-sm-12">
									<h4>LATAR BELAKANG & TUJUAN</h4>
									<div class="form-group">
										<label>1. Apa yang dibutuhkan?</label>
										<textarea class="form-control"
												  name="quest[]"
												  id="quest1"
												  required="required"></textarea>
									</div>
									<div class="form-group">
										<label>2. Siapa yang membutuhkan?</label>
										<textarea class="form-control"
												  name="quest[]"
												  id="quest2"
												  required="required"></textarea>
									</div>
									<div class="form-group">
										<label>3. Dimana barang tersebut akan ditempatkan?</label>
										<textarea class="form-control"
												  name="quest[]"
												  id="quest3"
												  required="required"></textarea>
									</div>
									<div class="form-group">
										<label>4. Kenapa barang tersebut dibutuhkan?</label>
										<textarea class="form-control"
												  name="quest[]"
												  id="quest4"
												  required="required"></textarea>
									</div>
									<div class="form-group">
										<label>5. Kapan dibutuhkan?</label>
										<textarea class="form-control"
												  name="quest[]"
												  id="quest5"
												  required="required"></textarea>
									</div>
									<div class="form-group">
										<label>6. Bagaimana setelah barang tersebut diadakan?</label>
										<textarea class="form-control"
												  name="quest[]"
												  id="quest6"
												  required="required"></textarea>
									</div>
								</div>
							</div>
							<div class="row row-form 3 hidden"
								 id="form-summary">
								<div class="col-sm-12">
									<h4>SUMMARY DETAIL ORDER</h4>
									<div class="table-responsive">
										<table class="table table-hover table-form table-summary"
											   style="min-width: 2000px !important">
											<thead>
												<tr>
													<th class="text-center"
														width="10%">Material
													</th>
													<th class="text-center"
														width="15%">Spesifikasi
													</th>
													<th class="text-center"
														width="8%">Tipe
													</th>
													<th class="text-center"
														width="15%">Budget
													</th>
													<th class="text-center"
														width="8%">Jumlah
													</th>
													<th class="text-center"
														width="7%">Satuan
													</th>
													<th class="text-center"
														width="10%">Request Delivery Date
													</th>
													<th class="text-center"
														width="13%">Harga
													</th>
													<th class="text-center"
														width="15%">Total
													</th>
													<th class="text-center"
														width="5%">
													</th>
												</tr>
											</thead>
											<tbody>
												<tr id="nodata">
													<td colspan="8">No data found</td>
													<td></td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="8"
														class="text-right">TOTAL
													</td>
													<td>
														<div class="input-group">
															<div class="input-group-addon">Rp</div>
															<input type="text"
																   class="form-control angka text-right summary_total"
																   value="0"
																   name="est_total"
																   readonly="readonly" />
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="8"
														class="text-right">BUDGET YANG TERSEDIA
													</td>
													<td>
														<div class="input-group">
															<div class="input-group-addon">Rp</div>
															<input type="text"
																   class="form-control angka text-right summary_budget"
																   name="ava_budget"
																   value="0"
																   readonly="readonly" />
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="8"
														class="text-right">LEBIH/(KURANG) DARI BUDGET
													</td>
													<td>
														<div class="input-group">
															<div class="input-group-addon">Rp</div>
															<input type="text"
																   class="form-control angka text-right summary_selisih"
																   name="selisih_budget"
																   value="0"
																   readonly="readonly" />
														</div>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<!--END SUMMARY-->
							</div>
							<div class="row row-form 3 hidden">
								<div class="col-sm-12">
									<h4>NOTE :</h4>
									<div class="form-group">
										<textarea class="form-control"
												  name="note_pi"
												  required="required"
												  style="min-width: 100%;"></textarea>
										<small>Note: (*) Permintaan Investasi harus disajikan lengkap sampai kondisi
											   siap pakai
										</small>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden"
								   name="budget_available_to_select">
							<input type="hidden"
								   name="action">
							<button type="button"
									name="action_btn"
									value="3"
									class="btn btn-success pull-right submit btn-form3 hidden"
									data-btn="edit">Edit
							</button>
							<button type="button"
									value="2"
									name="action_btn"
									class="btn btn-success pull-right next btn-form1 btn-form2"
									data-btn="next">Next
							</button>
							<button type="button"
									name="action_btn"
									class="btn btn-default pull-right back btn-form2 btn-form3 hidden"
									data-btn="back">Back
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/katalog/katalog.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/order/edit.js"></script>
