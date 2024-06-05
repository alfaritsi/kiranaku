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
						<button type="button"
								class="btn btn-sm btn-default pull-right"
								id="log_status">History
						</button>
						<?php if ($header->status == 'finish' && $header->no_po == NULL && $header->tipe_pi == NULL && $header->div_head_vendor == "nsw") : ?>
							<button type="button"
									class="btn btn-sm btn-info pull-right"
									id="sync_sap">Create PO SAP
							</button>
						<?php endif; ?>
					</div>
					<form class="form-tambah-order"
						  enctype="multipart/form-data">
						<div class="box-body">
							<!--START HEADER-->
							<div class="row row-form"
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
													   class="form-control"
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
									<div class="form-group hidden"
										 id="nilai_final_pi_container">
										<label for="pic_pemb"
											   class="col-sm-4 control-label text-left">Nilai Rekom. Vendor</label>
										<div class="col-sm-8">
											<input type="text"
												   name="nilai_final_pi"
												   class="form-control"
												   style="background-color: #dd4b39;color: white;font-size: x-large;">
										</div>
									</div>
								</div>
							</div>
							<!--END HEADER-->

							<!--START SUMMARY-->
							<div class="row row-form">
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
							<div class="row row-form"
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
														width="12%">Spesifikasi
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
														width="11%">Harga
													</th>
													<th class="text-center"
														width="11%">Total
													</th>
													<th class="text-center"
														width="8%">
													</th>
												</tr>
											</thead>
											<tbody>
												<tr id="nodata">
													<td colspan="9">No data found</td>
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
													<td></td>
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
													<td></td>
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
													<td></td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
								<!--END SUMMARY-->
							</div>
							<div class="row row-form">
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
								   name="action">
							<input type="hidden"
								   name="last_action" value="<?php echo $last_action->action; ?>">

							<?php
								//action button
								if ($header->sum_detail <= $header->ceo_reg_app_limit) {
									$level = 1;
									$sts   = array(1, 2, 3, 4); //status for add vendor recom
								}
								else {
									$level = 7;
									$sts   = array(7); //status for add vendor recom
								}

								if (in_array(base64_encode(1), $this->session->userdata("-pi_level-")) == false &&
									(in_array(base64_encode($header->status), $this->session->userdata("-pi_level-")) == true || (count($disposisi) > 0 && $header->sum_detail <= $disposisi->app_lim_val_disposisi))) {
									// harus ada rekom vendor
									if ($header->jml_belum_rekom > 0 && in_array($header->status, $sts) == true) {
										if (in_array(base64_encode(2), $this->session->userdata("-pi_level-")) == true || in_array(base64_encode(3), $this->session->userdata("-pi_level-")) == true || (count($disposisi) > 0 && in_array($disposisi->lvl_pengaju_disposisi, array(2, 3)) && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
											$desc = "silahkan minta Admin Pabrik untuk melakukan pengisian Rekomendasi Vendor.";
										}
										else {
											$desc = "silahkan klik <a href='" . $base_url_pi . "vendor/tambah/" . str_replace("/", "-", $header->no_pi) . "'><b>link</b></a> ini untuk melakukan pengisian Rekomendasi Vendor.";
										}
										echo "<div class='callout callout-danger'>
												  <h4>Perhatian!</h4>
												  <p>PI ini masih ada yang belum memiliki Rekomendasi Vendor, " . $desc . "</p>
											  </div>";

										if (in_array(base64_encode(7), $this->session->userdata("-pi_level-")) == true || (count($disposisi) > 0 && $disposisi->lvl_pengaju_disposisi == 7 && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {     //only for procurement HO
											if ((isset($pi_action) && $pi_action[0]->if_decline) || (count($disposisi) > 0 && $disposisi->if_decline !== NULL && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
												if ((in_array(base64_encode("Division Head"), $this->session->userdata("-pi_nama_role-")) == true && base64_decode($this->session->userdata("-nik-")) == $header->kepada) || in_array(base64_encode("Division Head"), $this->session->userdata("-pi_nama_role-")) == false || (count($disposisi) > 0 && $disposisi->role_pengaju_disposisi != "Division Head" && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
													if ((in_array(base64_encode("Department Head"), $this->session->userdata("-pi_nama_role-")) == true && $header->dept_head == base64_decode($this->session->userdata("-id_user-"))) || in_array(base64_encode("Department Head"), $this->session->userdata("-pi_nama_role-")) == false || (count($disposisi) > 0 && $disposisi->role_pengaju_disposisi != "Department Head" && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
														echo "<button type='button' value='decline' name='action_btn' class='btn btn-warning'>Decline</button>";
													}
												}
											}
										}
										//										//ketika ada pi decline
									}
									else if ($last_action->action == "decline" && $last_action->nama_role == "Division Head" && (in_array(base64_encode("Manager Kantor"), $this->session->userdata("-pi_nama_role-")) == true || (count($disposisi) > 0 && $disposisi->role_pengaju_disposisi == "Manager Kantor" && $header->sum_detail <= $disposisi->app_lim_val_disposisi))) {
										echo "<div class='callout callout-danger'>
												  <h4>Perhatian!</h4>
												  <p>PI ini perlu untuk di edit. Silahkan klik <a href='" . base_url() . "invest/edit/" . str_replace("/", "-", $header->no_pi) . "'><b>link</b></a> ini.</p>
											  </div>";
										// else ketika rekom vendor lengkap
									}
									else if ($header->status > 3 && in_array($header->nsw_check, array(NULL, false)) && $header->jml_blm_req_delivdate > 0 && $header->div_head_vendor !== "others") {
										echo "<div class='callout callout-danger'>
												  <h4>Perhatian!</h4>
												  <p>PI ini perlu konfirmasi delivery dari Nusira Workshop.</p>
											  </div>";
                                        if (isset($pi_action) && $pi_action[0]->if_assign && base64_decode($this->session->userdata("-nik-")) == $header->kepada && $header->dept_head_check == 0) {
                                            echo "<button type='button' value='assign' name='action_btn' class='btn btn-info'>Assign</button>";
                                        }
									}
									else if ($header->status >= 8 && $header->jml_blm_no_asset > 0 && $header->div_head_vendor == "nsw") {
										echo "<div class='callout callout-danger'>
												  <h4>Perhatian!</h4>
												  <p>PI ini perlu konfirmasi no asset dari Accounting.</p>
											  </div>";
									}
									else {
										if ((((isset($pi_action) && $pi_action[0]->if_assign) && base64_decode($this->session->userdata("-nik-")) == $header->kepada) || (count($disposisi) > 0 && $disposisi->if_assign !== NULL && $disposisi->pengaju_disposisi == $header->kepada && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) && $header->dept_head_check == 0) {
											echo "<button type='button' value='assign' name='action_btn' class='btn btn-info'>Assign</button>";
										}
										if ((isset($pi_action) && $pi_action[0]->if_approve) || (count($disposisi) > 0 && $disposisi->if_approve !== NULL && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
											if ((in_array(base64_encode("Division Head"), $this->session->userdata("-pi_nama_role-")) == true && base64_decode($this->session->userdata("-nik-")) == $header->kepada) || in_array(base64_encode("Division Head"), $this->session->userdata("-pi_nama_role-")) == false || (count($disposisi) > 0 && $disposisi->role_pengaju_disposisi != "Division Head" && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
												if ((in_array(base64_encode("Department Head"), $this->session->userdata("-pi_nama_role-")) == true && $header->dept_head == base64_decode($this->session->userdata("-id_user-"))) || in_array(base64_encode("Department Head"), $this->session->userdata("-pi_nama_role-")) == false || (count($disposisi) > 0 && $disposisi->role_pengaju_disposisi != "Department Head" && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
													echo "<button type='button' value='approve' name='action_btn' class='btn btn-success'>Approve</button>";
												}
											}
										}
										if ((isset($pi_action) && $pi_action[0]->if_decline) || (count($disposisi) > 0 && $disposisi->if_decline !== NULL && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
											if ((in_array(base64_encode("Division Head"), $this->session->userdata("-pi_nama_role-")) == true && base64_decode($this->session->userdata("-nik-")) == $header->kepada) || in_array(base64_encode("Division Head"), $this->session->userdata("-pi_nama_role-")) == false || (count($disposisi) > 0 && $disposisi->role_pengaju_disposisi != "Division Head" && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
												if ((in_array(base64_encode("Department Head"), $this->session->userdata("-pi_nama_role-")) == true && $header->dept_head == base64_decode($this->session->userdata("-id_user-"))) || in_array(base64_encode("Department Head"), $this->session->userdata("-pi_nama_role-")) == false || (count($disposisi) > 0 && $disposisi->role_pengaju_disposisi != "Department Head" && $header->sum_detail <= $disposisi->app_lim_val_disposisi)) {
													echo "<button type='button' value='decline' name='action_btn' class='btn btn-warning'>Decline</button>";
												}
											}
										}
									}
								}
							?>
						</div>
					</form>
				</div>


				<!-- Modal -->
				<div class="modal fade"
					 id="comment_modal"
					 data-backdrop="static"
					 tabindex="-1"
					 role="dialog"
					 aria-labelledby="myModalLabel">
					<div class="modal-dialog"
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
									id="comment_modal_label"
									style="text-transform: capitalize">Konfirmasi</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-12">
										<form role="form"
											  id="form-action-pi">
											<?php if ($header->dept_head_check == 0 && in_array(base64_encode("Division Head"),$this->session->userdata("-pi_nama_role-")) == true) { ?>
												<div class="form-group"
													 id="assign_dept_head">
													<label>Departemen Head</label>
													<select name="dept_head"
															class="form-control select2"
															style="width: 100%;">
														<?php
															foreach ($dept_head as $dh) {
																echo "<option value='$dh->id_user'>" . $dh->nama . " - " . $dh->dept . "</option>";
															}
														?>
													</select>
													<input type="hidden"
														   name="nama_dept_head">
												</div>
											<?php } ?>

											<?php if (in_array(base64_encode("Division Head"), $this->session->userdata("-pi_nama_role-")) == true) { ?>
												<div class="form-group"
													 id="vendor_selection">
													<label>Vendor</label>
													<select name="div_head_vendor"
															class="form-control select2"
															style="width: 100%;">
														<option value="nsw">Nusira Workshop</option>
														<option value="others">Lainnya</option>
													</select>
												</div>
											<?php } ?>

											<?php if (
												in_array(base64_encode($header->status),$this->session->userdata("-pi_level-")) == true &&
												(in_array(base64_encode("Department Head"),$this->session->userdata("-pi_nama_role-")) == true ||
													in_array(base64_encode("Division Head"),$this->session->userdata("-pi_nama_role-")) == true ||
													in_array(base64_encode("Finance Controller"),$this->session->userdata("-pi_nama_role-")) == true)) { ?>
												<!--												<div class="form-group">-->
												<!--													<label for="fileAttach">File attachment</label>-->
												<!--													<input type="file"-->
												<!--														   multiple="multiple"-->
												<!--														   class="form-control"-->
												<!--														   id="fileAttach"-->
												<!--														   name="fileAttach[]">-->
												<!--												</div>-->
											<?php } ?>

											<?php if (isset($pi_action) && $pi_action[0]->dual_option_decline == 1) { ?>
												<div class="form-group"
													 id="select_reason">
													<label>Decline Reason</label>
													<select name="reason_decline"
															class="form-control select2"
															style="width: 100%;">
														<option value="0">Silahkan pilih</option>
														<option value="harga">Harga</option>
														<option value="spec">Sepesifikasi</option>
													</select>
												</div>
											<?php } ?>
											<div class="form-group">
												<label>Komentar</label>
												<textarea class="form-control"
														  name="note_pi"
														  id="comment"
														  required="required"></textarea>
												<input type="hidden"
													   name="budget_available_to_select">
												<input type="hidden"
													   name="action"
													   id="action_modal">
												<input type="hidden"
													   name="no_pi"
													   id="no_pi_modal">
												<input type="hidden"
													   name="no_detail">
												<input type="hidden"
													   name="status_detail">
												<input type="hidden"
													   name="itnum_detail">
												<input type="hidden"
													   name="matnr_detail">
												<input type="hidden"
													   name="kdmat_detail">
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button"
										class="btn btn-default"
										data-dismiss="modal">Batal
								</button>
								<button type="button"
										class="btn btn-primary"
										id="save_form-action-pi">Simpan
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/order/detail.js"></script>
