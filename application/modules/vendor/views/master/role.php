<!--
/*
@application  : KODE VENDOR
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-8">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Level</th>
				              	<th>Role</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($role_all as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->level."</td>";
				              		echo "<td>".strtoupper($dt->nama)."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
												echo"<ul class='dropdown-menu pull-right'>";
													if($dt->na == 'n'){ 
														echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_role."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
														echo "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_role."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
													}
													if($dt->na == 'y'){
														echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_role."'><i class='fa fa-check'></i> Set Aktif</a></li>";
													}
												echo"</ul>";
									echo " 	
				                          </div>
				                        </td>";
									
									echo "</tr>";
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
		              	<h3 class="box-title title-form">Form Role Master Vendor</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Role</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-role">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="nama">Nama Role</label>
									<input type="text" class="form-control" name="nama" id="nama" placeholder="Input Nama Role" required="required">
								</div>
								<div class="form-group">
									<label for="level">Level</label>
									<input type="text" class="form-control" name="level" id="level" placeholder="Input Level" required="required">
								</div>
								<!-- Custom Tabs -->
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab_1" data-toggle="tab">Proses HO</a></li>
										<li><a href="#tab_2" data-toggle="tab">Proses Pabrik</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab_1">
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Create Vendor (HO)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_ho">Jika Disetujui (Tanpa Dokumen Legal)</label>
														<select class="form-control select2" name="if_approve_create_ho" id="if_approve_create_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_ho">Jika Ditolak (Tanpa Dokumen Legal)</label>
														<select class="form-control select2" name="if_decline_create_ho" id="if_decline_create_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_approve_create_legal_ho">Jika Disetujui (Dengan Dokumen Legal)</label>
														<select class="form-control select2" name="if_approve_create_legal_ho" id="if_approve_create_legal_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_legal_ho">Jika Ditolak (Dengan Dokumen Legal)</label>
														<select class="form-control select2" name="if_decline_create_legal_ho" id="if_decline_create_legal_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Change Vendor (HO)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_change_ho">Jika Disetujui (Tanpa Dokumen Legal)</label>
														<select class="form-control select2" name="if_approve_change_ho" id="if_approve_change_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_change_ho">Jika Ditolak (Tanpa Dokumen Legal)</label>
														<select class="form-control select2" name="if_decline_change_ho" id="if_decline_change_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_approve_change_legal_ho">Jika Disetujui (Dengan Dokumen Legal)</label>
														<select class="form-control select2" name="if_approve_change_legal_ho" id="if_approve_change_legal_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_change_legal_ho">Jika Ditolak (Dengan Dokumen Legal)</label>
														<select class="form-control select2" name="if_decline_change_legal_ho" id="if_decline_change_legal_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Extend Master Vendor (HO)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_extend_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_extend_ho" id="if_approve_extend_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_extend_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_extend_ho" id="if_decline_extend_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Delete Master Vendor (HO)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_delete_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_delete_ho" id="if_approve_delete_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_delete_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_delete_ho" id="if_decline_delete_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Undelete Master Vendor (HO)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_undelete_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_undelete_ho" id="if_approve_undelete_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_undelete_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_undelete_ho" id="if_decline_undelete_ho">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
										</div><!-- /.tab-pane -->
										<div class="tab-pane" id="tab_2">
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Create Vendor (Pabrik)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_pabrik">Jika Disetujui (Tanpa Dokumen Legal)</label>
														<select class="form-control select2" name="if_approve_create_pabrik" id="if_approve_create_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_pabrik">Jika Ditolak (Tanpa Dokumen Legal)</label>
														<select class="form-control select2" name="if_decline_create_pabrik" id="if_decline_create_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_approve_create_legal_pabrik">Jika Disetujui (Dengan Dokumen Legal)</label>
														<select class="form-control select2" name="if_approve_create_legal_pabrik" id="if_approve_create_legal_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_legal_pabrik">Jika Ditolak (Dengan Dokumen Legal)</label>
														<select class="form-control select2" name="if_decline_create_legal_pabrik" id="if_decline_create_legal_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Change Vendor (Pabrik)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_change_pabrik">Jika Disetujui (Tanpa Dokumen Legal)</label>
														<select class="form-control select2" name="if_approve_change_pabrik" id="if_approve_change_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_change_pabrik">Jika Ditolak (Tanpa Dokumen Legal)</label>
														<select class="form-control select2" name="if_decline_change_pabrik" id="if_decline_change_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_approve_change_legal_pabrik">Jika Disetujui (Dengan Dokumen Legal)</label>
														<select class="form-control select2" name="if_approve_change_legal_pabrik" id="if_approve_change_legal_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_change_legal_pabrik">Jika Ditolak (Dengan Dokumen Legal)</label>
														<select class="form-control select2" name="if_decline_change_legal_pabrik" id="if_decline_change_legal_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Extend Master Vendor (Pabrik)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_extend_pabrik">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_extend_pabrik" id="if_approve_extend_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_extend_pabrik">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_extend_pabrik" id="if_decline_extend_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Delete Master Vendor (Pabrik)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_delete_pabrik">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_delete_pabrik" id="if_approve_delete_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_delete_pabrik">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_delete_pabrik" id="if_decline_delete_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Undelete Master Vendor (Pabrik)</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_undelete_pabrik">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_undelete_pabrik" id="if_approve_undelete_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
																echo"<option value='99'>Finish</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_undelete_pabrik">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_undelete_pabrik" id="if_decline_undelete_pabrik">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																}
															?>
														</select>
													</div>
												</div>
											</fieldset>										
										</div><!-- /.tab-pane -->
									</div><!-- /.tab-content -->
								</div><!-- nav-tabs-custom -->
							</div><!-- /.col -->
						</div>
					</div>
					<div class="modal-footer">
						<input id="id_role" name="id_role" type="hidden">
						<button type="reset" class="btn btn-danger">Reset</button>
						<button type="button" name="action_btn" class="btn btn-success">Submit</button>
					</div>
					</form>
				</div>
			</div>
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/vendor/master/role.js"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>

<style>
.small-box .icon{
    top: -13px;
}
</style>