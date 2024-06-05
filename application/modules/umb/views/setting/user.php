<!--
	/*
        @application  : UMB (Uang Muka Bokar)
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 24-Sep-18
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
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-responsive table-striped my-datatable-extends-order">
							<thead>
								<tr>
									<th>User</th>
									<th>Role</th>
									<th>Pabrik/Plant</th>
									<th>Last Update</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($userrole) {
										$output = "";
										foreach ($userrole as $u) {
											$pabrik = explode(",", rtrim($u->pabrik_list, ","));
											$output .= '<tr>';
											$output .= '	<td>';
											$output .= '		' . $u->nama . '<br>' . $u->label_active . '<br><label class="label label-success">' . $u->nik . '</label>';
											$output .= '	</td>';
											$output .= '	<td>' . $u->nama_role . '</td>';
											$output .= '	<td>';
											foreach ($pabrik as $p) {
												$output .= '<button class="btn btn-sm btn-info btn-role">' . $p . '</button>';
											}
											$output .= '	</td>';
											$output .= '	<td>' . $generate->generateDateTimeFormat($u->tanggal_edit) . '</td>';
											$output .= '	<td>';
											$output .= '		<div class="input-group-btn">';
											$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
											$output .= '			<ul class="dropdown-menu pull-right">';
											if ($u->na == 'n') {
												$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="' . $u->id_rolenik . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="nonactive" data-nonactive="' . $u->id_rolenik . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $u->id_rolenik . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											} else {
												$output .= '			<li><a href="javascript:void(0)" class="setactive" data-setactive="' . $u->id_rolenik . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $u->id_rolenik . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
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
									style="display:none">Buat Setting Baru
							</button>
						</div>
					</div>
					<form class="form-setting-user">
						<div class="box-body">
							<div class="form-group">
								<label for="karyawan">User</label>
								<select name="karyawan"
										required="required"
										class="form-control select2-user-search">
								</select>
							</div>
							<div class="form-group">
								<label for="role">Role</label>
								<select name="role"
										required="required"
										class="form-control select2">
									<option value="0">Silahkan Pilih</option>
									<?php
										if ($role_select) {
											foreach ($role_select as $r) {
												echo "<option value='" . $generate->kirana_encrypt($r->kode_role) . "'>" . $r->nama_role . "</option>";
											}
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="pabrik"
									   class="pull-left">Pabrik / Plant</label>
								<div class="checkbox pull-right select_all"
									 style="margin:0;">
									<label><input type="checkbox"
												  class="isSelectAll"> Select All</label>
								</div>
								<select name="pabrik[]"
										required="required"
										class="form-control select2"
										multiple="multiple">
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
						<div class="box-footer">
							<input type="hidden"
								   name="id">
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
<script src="<?php echo base_url() ?>assets/apps/js/umb/setting/user.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
