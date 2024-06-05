<!--
/*
	@application  : UMB (Uang Muka Bokar)
	@author       : Akhmad Syaiful Yamang (8347)
	@date         : 21-Sep-18
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
			<div class="col-sm-8">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped my-datatable-order-col2">
							<thead>
								<tr>
									<th>Role</th>
									<th>Level</th>
									<th>Limit Approve</th>
									<th>Last Update</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($role) {
										$output = "";
										foreach ($role as $r) {
											$output .= '<tr>';
											$output .= '	<td>';
											$output .= '		' . $r->nama_role . ' ' . $r->label_active;
											if ($r->approve_role != NULL)
												$output .= '		<br><label class="label label-success">Submit / Approve Scoring to : ' . $generate->add_space_after_comma(rtrim($r->approve_role, ",")) . '</label>';
											if ($r->assign_role != NULL)
												$output .= '		<br><label class="label label-info">Assign Scoring to : ' . $generate->add_space_after_comma(rtrim($r->assign_role, ",")) . '</label>';
											if ($r->decline_role != NULL)
												$output .= '		<br><label class="label label-warning">Decline Scoring to : ' . $generate->add_space_after_comma(rtrim($r->decline_role, ",")) . '</label>';
											if ($r->drop_role != NULL)
												$output .= '		<br><label class="label label-danger">Drop Scoring to : ' . $generate->add_space_after_comma(rtrim($r->drop_role, ",")) . '</label>';
											if ($r->disposisi_nik != NULL)
												$output .= '		<br><label class="label label-info">Disposisi to : ' . $r->disposisi_nama . '</label>';
											if ($r->akses_plafon != NULL)
												$output .= '		<br><label class="label label-default">Akses Plafon : ' . ucwords($r->akses_plafon) . '</label>';
											$output .= '	</td>';
											$output .= '	<td>' . $r->level . '</td>';
											$output .= '	<td>' . ($r->limit_app !== NULL ?  number_format($r->limit_app,2) : "") . '</td>';
											$output .= '	<td>' . $generate->generateDateTimeFormat($r->tanggal_edit) . '</td>';
											$output .= '	<td>';
											$output .= '		<div class="input-group-btn">';
											$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
											$output .= '			<ul class="dropdown-menu pull-right">';
											if ($r->na == 'n') {
												$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="' . $r->kode_role . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="nonactive" data-nonactive="' . $r->kode_role . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->kode_role . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											} else {
												$output .= '			<li><a href="javascript:void(0)" class="setactive" data-setactive="' . $r->kode_role . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->kode_role . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											}
											$output .= '			</ul>';
											$output .= '		</div>';
											$output .= '	</td>';
											$output .= '</tr>';
										}
										echo $output;
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong><?php echo $title_form; ?></strong></h3>
						<div class="pull-right">
							<button type="button"
									class="btn btn-sm btn-default"
									id="btn-new"
									style="display:none">Buat Role Baru
							</button>
						</div>
					</div>
					<form role="form"
						  class="form-master-role">
						<div class="box-body">
							<div class="form-group">
								<label for="role">Role</label>
								<input type="text"
									   class="form-control"
									   id="role"
									   name="role"
									   required="required"
									   placeholder="Masukkkan nama role">
							</div>
							<div class="form-group">
								<label for="level">Level</label>
								<input type="number"
									   class="form-control"
									   id="level"
									   name="level"
									   required="required"
									   placeholder="Masukkkan level role"
									   min="0">
							</div>
							<div class="form-group">
								<div class="checkbox">
					                <label>
					                  <input type="checkbox" id="isAksesRekom" name="isAksesRekom" value="on" > <strong>Akses Rekomendasi UM</strong>
					                  <input type="hidden" id="isRekom" name="isRekom" value="off">
					                </label>
		              			</div>
							</div>
							<div class="form-group">
								<div class="checkbox">
					                <label>
					                  <input type="checkbox" id="isAksesRenewal" name="isAksesRenewal" value="on" > <strong>Akses Perpanjang Masa UM</strong>
					                  <input type="hidden" id="isRenewal" name="isRenewal" value="off">
					                </label>
		              			</div>
							</div>
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#scoring_setting"
														  data-toggle="tab">Scoring</a></li>
									<li><a href="#plafon_setting"
										   data-toggle="tab">Plafon</a></li>
								</ul>
							</div>
							<div class="tab-content">
								<div class="tab-pane active"
									 id="scoring_setting">
									<div class="form-group">
										<label for="if_approve">Jika Approve</label>
										<select name="if_approve"
												class="form-control select2">
											<option value="0">Silahkan Pilih</option>
											<?php
												if ($role_select) {
													foreach ($role_select as $r) {
														echo "<option value='" . $r->level . "'>" . $r->nama_role . "</option>";
													}
												}
											?>
										</select>
									</div>
									<div class="form-group">
										<label for="if_assign">Jika Assign</label>
										<select name="if_assign"
												class="form-control select2">
											<option value="0">Silahkan Pilih</option>
											<?php
												if ($role_select) {
													foreach ($role_select as $r) {
														echo "<option value='" . $r->level . "'>" . $r->nama_role . "</option>";
													}
												}
											?>
										</select>
									</div>
									<div class="form-group">
										<label for="if_decline">Jika Decline</label>
										<select name="if_decline"
												class="form-control select2">
											<option value="0">Silahkan Pilih</option>
											<?php
												if ($role_select) {
													foreach ($role_select as $r) {
														echo "<option value='" . $r->level . "'>" . $r->nama_role . "</option>";
													}
												}
											?>
										</select>
									</div>
									<div class="form-group">
										<label for="if_drop">Jika Drop</label>
										<select name="if_drop"
												class="form-control select2">
											<option value="0">Silahkan Pilih</option>
											<?php
												if ($role_select) {
													foreach ($role_select as $r) {
														echo "<option value='" . $r->level . "'>" . $r->nama_role . "</option>";
													}
												}
											?>
										</select>
									</div>
									<div class="form-group">
										<label for="disposisi">Disposisi</label>
										<select name="disposisi"
												class="form-control select2-user-search">

										</select>
									</div>
									<div class="form-group">
										<label for="limit-app">Limit Approval</label>
										<input type="text"
											   class="form-control angka"
											   name="limit-app"
											   value="0">
									</div>
								</div>
								<div class="tab-pane"
									 id="plafon_setting">
									<div class="form-group">
										<label for="if_approve">Hak Akses</label>
										<select name="hak_akses_plafon"
												class="form-control select2">
											<option value="0">Silahkan Pilih</option>
											<option value="submit">Submit</option>
											<option value="approve">Approve</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden"
								   name="kode_role" />
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
<script src="<?php echo base_url() ?>assets/apps/js/umb/master/role.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


