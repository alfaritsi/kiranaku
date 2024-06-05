<!--
/*
@application    : SHE 
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. Airiza Yuddha (7849) 10.09.2019
			   - add hidden button type and button upload manifest
			   - edit kondisi button yang tampil ketika limbah OUT 			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="col-md-2 pull-right" style="margin-top: 20px;">
							<div class="form-group">
								<button type="button" class="btn btn-primary pull-right" onclick="init()" data-toggle="modal" data-target="#modal-form">
									<i class="fa fa-plus"></i> Tambah Data
								</button>
							</div>
						</div>

						<div class="clearfix"></div>

						<form class="filter-limbahb3" id='filterform' role="form" method="POST" action="<?php echo base_url() ?>she/transaction/limbahb3/input">
							<div class="col-md-2">
								<div class="form-group">
									<label>Pabrik :</label>
									<select name="filter_pabrik" id="filter_pabrik" class="form-control select2" style="width: 100%;" onchange="filtersubmit()">
										<option value='0'>Pilih Pabrik</option>
										<?php
										foreach ($pabrik as $dt) {
											$selected = ($_POST['filter_pabrik'] == $dt->id_pabrik) ? "selected" : "";
											echo "<option value='" . $dt->id_pabrik . "' $selected>" . $dt->nama . " (" . $dt->kode . ")</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Status :</label>
									<select name="filter_status" id="filter_status" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
										<option value='0'>Pilih Status</option>
										<?php
										$selected = ($status == "1") ? "selected" : "";
										echo "<option value='1' " . $selected . ">Not Post</option>";
										$selected = ($status == "2") ? "selected" : "";
										echo "<option value='2' " . $selected . ">Posted</option>";
										$selected = ($status == "3") ? "selected" : "";
										echo "<option value='3' " . $selected . ">Requested</option>";
										?>
									</select>
								</div>
							</div>
						</form>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-striped my-datatable-extends-order">
							<thead>
								<tr>
									<th class='text-center'>Tipe</th>
									<th class='text-center'>Tgl. Transaksi</th>
									<th class='text-center'>Tgl. Exp.</th>
									<th class='text-center'>Jenis</th>
									<th class='text-center'>Kode Material</th>
									<th class='text-center'>Sumber</th>
									<th class='text-center'>Qty</th>
									<th class='text-center'>Stock Akhir</th>
									<th class='text-center'>Status</th>
									<th class='text-center' width="1px"></th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($limbah_b3 as $dt) {
									echo "<tr>";
									echo "<td align='center'>" . $dt->type . "</td>";
									echo "<td align='center'>" . $this->generate->generateDateFormat($dt->tanggal_transaksi) . "</td>";
									if ($dt->type == "OUT") {
										echo "<td align='center'></td>";
									} else {
										echo "<td align='center'>" . $this->generate->generateDateFormat($dt->tgl_exp) . "</td>";
									}

									echo "<td>" . $dt->jenis_limbah . "</td>";
									echo "<td>" . $dt->kode_material . "</td>";
									echo "<td>" . $dt->sumber_limbah . "</td>";
									echo "<td align='center'>" . $dt->quantity . " " . $dt->satuan . "</td>";
									$stock = ($dt->stok == -1) ? "" : $dt->stok . " " . $dt->satuan;
									echo "<td align='center'>" . $stock . "</td>";
									echo "<td align='center'>" . $dt->status . "</td>";

									if ($dt->type == "IN") {
										echo "<td align='center'>";
										if ($dt->stok == -1) {
											echo "
					                        <div class='input-group-btn pull-right'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>
					                        		<li><a href='#' class='edit' data-edit='" . $dt->id . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
					                              	<li><a href='#' class='post' data-post='" . $dt->id . "'><i class='fa fa-cloud-upload'></i> Post</a></li>
					                              	<li><a href='#' class='delete' data-delete='" . $dt->id . "'><i class='fa fa-trash-o'></i> Delete</a></li>
						                    	</ul>
						                    </div>";
										} else {
											echo "
					                        <div class='input-group-btn pull-right'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
											if (($dt->status == 'Requested') and (base64_decode($this->session->userdata("-ho-")) == 'n')) {
												echo "<li><a href='#' class='cancel_request' data-request='" . $dt->id . "'><i class='fa fa-trash-o'></i> Cancel Request For Delete</a></li>";
											}
											if (($dt->status == 'Posted') and (base64_decode($this->session->userdata("-ho-")) == 'n')) {
												echo "<li><a href='#' class='request' data-request='" . $dt->id . "'><i class='fa fa-trash-o'></i> Request For Delete</a></li>";
											}
											echo "	
												</ul>
						                    </div>";
										}

										echo "</td>";
									} elseif ($dt->type == "OUT") {
										echo "<td align='center'>";

										echo "<a title='Lihat file lampiran 1' target='_blank' href='" . base_url() . $dt->lampiran1 . "'><i class='fa fa-download'></i></a> &nbsp &nbsp";
										echo "<a title='Lihat file lampiran 2' target='_blank' href='" . base_url() . $dt->lampiran2 . "'><i class='fa fa-download'></i></a> &nbsp &nbsp";
										echo "<a title='Lihat file lampiran 3' target='_blank' href='" . base_url() . $dt->lampiran3 . "'><i class='fa fa-download'></i></a>";
										$Upload = "";
										$action = "";
										$lampiran1 = trim($dt->lampiran1);
										$lampiran2 = trim($dt->lampiran2);
										$lampiran3 = trim($dt->lampiran3);
										if ($lampiran1 == null || $lampiran2 == null || $lampiran3 == null) {
											$Upload = "<li><a href='#' class='reupload' data-reupload='" . $dt->id . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-upload'></i> Upload Manifest</a></li>
					                            ";
										}

										if ($dt->stok == -1) {
											$action = "<li><a href='#' class='edit' data-edit='" . $dt->id . "' data-toggle='modal' 
						                   				data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
					                              	<li><a href='#' class='post' data-post='" . $dt->id . "'><i class='fa fa-cloud-upload'></i> Post</a></li>
					                              	<li><a href='#' class='delete' data-delete='" . $dt->id . "'><i class='fa fa-trash-o'></i> Delete</a></li>";
										}

										if ($dt->stok == -1 || ($lampiran1 == null || $lampiran2 == null || $lampiran3 == null)) {
											echo "
					                        <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>
					                        		$action
					                              	$Upload
						                    	</ul>
						                    </div>";
										}



										echo "</td>";
									}
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="modal-form">
			<div class="modal-dialog" style="width:900px;">
				<form role="form" class="form-limbahb3_inputdata">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"> <i class="fa fa-plus"></i> Tambah Data </h4>
						</div>
						<div class="modal-body" style="min-height:200px;">
							<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Pabrik :</label>
									<select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" required>
										<option value="" selected>Silahkan Pilih</option>
										<?php
										foreach ($pabrik as $dt) {
											echo "<option value='" . $dt->id_pabrik . "' selected>" . $dt->nama . " (" . $dt->kode . ")</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Tipe Input :</label>
									<select name="tipe" id="tipe" class="form-control select2" style="width: 100%;" required>
										<option value="" selected>Silahkan Pilih</option>
										<option value="IN">Limbah Masuk</option>
										<option value="OUT">Limbah Keluar</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Tanggal :</label>
									<div class="input-group date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" name="tanggal" id="tanggal" class="datePicker2 init" style="width:100%; height:32px;padding:10px;" required readonly>
									</div>
								</div>
							</div>
							</div>
							<div class="clearfix"></div>

							<div id="divIn" class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Jenis Limbah :</label>
										<select name="jenislimbah" id="jenislimbah" class="form-control select2" style="width: 100%;" required onchange="jenislimbah_masuk()">
											<option value="" selected>Silahkan Pilih</option>
											<?php
											foreach ($limbah as $limbah) {
												echo "<option value='" . $limbah->id . "'>" . $limbah->jenis_limbah . "</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Sumber Limbah :</label>
										<select name="sumberlimbah" id="sumberlimbah" class="form-control select2" style="width: 100%;" required>
											<option value="" selected>Silahkan Pilih</option>
											<?php
											foreach ($sumberlimbah as $sumberlimbah) {
												echo "<option value='" . $sumberlimbah->id . "'>" . $sumberlimbah->sumber_limbah . "</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Quantity :</label>
										<input type="text" name="qty" id="qty" class="init" style="width:100%;height:32px;padding:10px;text-align:right;" required autocomplete="off">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Kode Material :</label>
										<input type="text" name="kode_material" id="kode_material" class="form-control init" readonly autocomplete="off">
									</div>
								</div>
							</div>

							<div class="clearfix"></div>
							<div id="divOut">
							</div>

							<div class="modal-footer">
								<input type="hidden" name="id" id="id" style="width:100%">
								<input type="hidden" name="type" id="type" style="width:100%">
								<button type="submit" name="action_btn" class="btn btn-primary">Save</button>
							</div>
						</div>
				</form>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/she/transaction/limbahb3_inputdata.js?<?php echo time();?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
	.small-box .icon {
		top: -13px;
	}
</style>