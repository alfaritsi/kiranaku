<!--
/*
	@application  : UMB (Uang Muka Bokar)
	@author       : Akhmad Syaiful Yamang (8347)
	@contributor  :
		  1. <insert your fullname> (<insert your nik>) <insert the date>
			 <insert what you have modified>
		  2. <insert your fullname> (<insert your nik>) <insert the date>
			 <insert what you have modified>
		  etc.
*/
-->

<?php $this->load->view('header') ?>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12"
				 id="list_form"
				 style="display: none">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title_form; ?></strong></h3>
						<div class="pull-right">
							<?php if (isset($session_role) && isset($session_role[0]->akses_plafon) && $session_role[0]->akses_plafon == "submit"): ?>
								<!-- <button type="button"
										class="btn btn-sm btn-danger modify_plafon"
										data-row="yes">Perubahan Plafon
								</button> -->
								<button type="button"
										class="btn btn-sm btn-default add-row">Tambah Baris
								</button>
								<button type="button"
										class="btn btn-sm btn-warning delete-row">Hapus Baris
								</button>
								<button type="button"
										class="btn btn-sm btn-danger"
										id="close_list_form">X
								</button>
							<?php endif; ?>
						</div>
					</div>
					<form class="form-master-plafon" enctype="multipart/form-data">
						<div class="box-body">
							<div style="width:100%; overflow-x:auto">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Nama Pabrik</th>
											<!-- <th>Ranger</th>
											<th>Lain-lain</th> -->
											<th>Limit Plafon Uang Muka</th>
										</tr>
									</thead>
									<tbody id="input-plafon-wrapper">
									</tbody>
								</table>
							</div>
							<?php if (isset($session_role) && isset($session_role[0]->akses_plafon)): ?>
							<div class="form-group">
								<label for="persen_bobot">Bukti Perubahan Plafon</label>
								<div class="input-group" style="width:412px;">
									<input type=""
										   class="form-control"
										   name="caption"
										   required="required"
										   readonly="readonly">
									<div class="input-group-btn">
										<input type="file"
										   class="form-control"
										   style="display:none;"
										   name="bukti_file[]"
										>
										<input type="hidden"
										   name="isnew" id="isnew" value="no"
										>
										<button type="button"
												class="btn btn-default btn-flat"
												data-title="Upload File" id="upload_bukti"
												title="Upload File"><i class="fa fa-upload"></i>
										</button>
									</div>
									<div class="input-group-btn">
										<button type="button"
												class="btn btn-default btn-flat lihat-file"
												data-title="File" id="view_bukti"
												title="Lihat file" disabled><i class="fa fa-search"></i>
										</button>
									</div>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<div class="box-footer">
							<input type="hidden"
								   name="action">
							<div class="btn-pengajuan">
								<?php if (isset($session_role) && isset($session_role[0]->akses_plafon) && $session_role[0]->akses_plafon == "submit"): ?>
									<button type="button"
											class="btn btn-sm btn-success"
											name="action_btn"
											value="submit">Submit
									</button>
								<?php endif; ?>
							</div>
							<div class="btn-approval">
								<?php if (isset($session_role) && isset($session_role[0]->akses_plafon) && $session_role[0]->akses_plafon == "approve"): ?>
									<button type="button"
											class="btn btn-sm btn-success"
											name="action_btn"
											value="approve">Approve
									</button>
									<button type="button"
											class="btn btn-sm btn-danger"
											name="action_btn"
											value="reject">Reject
									</button>
								<?php endif; ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12"
				 id="list_display">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
						<div class="pull-right">
							<?php if (isset($session_role) && isset($session_role[0]->akses_plafon) && $session_role[0]->akses_plafon !== NULL): ?>
								<button type="button"
										class="btn btn-sm btn-default add-plafon"
										data-row="yes">Pengajuan Baru
								</button>
							<?php endif; ?>
						</div>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped table-hover my-datatable-extends-order">
							<thead>
								<tr>
									<th>Nama Pabrik</th>
									<!-- <th>Ranger</th>
									<th>Lain-lain</th> -->
									<th>Limit Plafon Uang Muka</th>
									<th>Start Date</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
					<div class="box-footer text-center">
						<LABEL class="sum_plafon"><strong>Total Plafon Seluruh Pabrik : </strong></LABEL>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/umb/master/plafon.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
