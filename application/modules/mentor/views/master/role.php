<!--
/*
@application  : MENTORING
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
				              	<th>Tipe User</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($role as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->level."</td>";
				              		echo "<td>".strtoupper($dt->nama)."</td>";
				              		echo "<td>".$dt->tipe_user."</td>";
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
		              	<h3 class="box-title title-form">Form Role Master Depo</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Role</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-role-master_depo">
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
								<div class="form-group">
									<label for="tipe_user">Tipe User</label>
									<select class="form-control select2" name="tipe_user" id="tipe_user"  required="required">
										<?php
											echo "<option value='0'>Pilih Tipe User</option>";
											echo"<option value='nik'>NIK</option>";
											echo"<option value='posisi'>Posisi</option>";
										?>
									</select>
								</div>
								<!--
								<div class="form-group">
									<div class="checkbox">
										<label>
											<input type="checkbox" id="is_paralel" name="is_paralel"> Approve Paralel
										</label>
									</div>
								</div>
								-->
								<!-- Custom Tabs -->
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab_1" data-toggle="tab">Depo Tetap</a></li>
										<li><a href="#tab_2" data-toggle="tab">Depo Mitra</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab_1">
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Pembukaan Depo Tetap</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_pembukaan_tetap" id="if_approve_pembukaan_tetap">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_pembukaan_tetap" id="if_decline_pembukaan_tetap">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<div class="form-group input-paralel hidden">
															<label> Divisi Terkait: </label>
															<select class="form-control select2" multiple="multiple" id="divisi_pembukaan_tetap" name="divisi_pembukaan_tetap[]" style="width: 100%;" data-placeholder="Pilih Divisi">
																<?php
																foreach ($divisi as $dt) {
																	echo "<option value='" . $dt->id_divisi . "'>" . $dt->nama . "</option>";
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Evaluasi Depo Tetap</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_evaluasi_tetap" id="if_approve_evaluasi_tetap">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_evaluasi_tetap" id="if_decline_evaluasi_tetap">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<div class="form-group input-paralel hidden">
															<label> Divisi Terkait: </label>
															<select class="form-control select2" multiple="multiple" id="divisi_evaluasi_tetap" name="divisi_evaluasi_tetap[]" style="width: 100%;" data-placeholder="Pilih Divisi">
																<?php
																foreach ($divisi as $dt) {
																	echo "<option value='" . $dt->id_divisi . "'>" . $dt->nama . "</option>";
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Penutupan Depo</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_penutupan_tetap" id="if_approve_penutupan_tetap">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_penutupan_tetap" id="if_decline_penutupan_tetap">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<div class="form-group input-paralel hidden">
															<label> Divisi Terkait: </label>
															<select class="form-control select2" multiple="multiple" id="divisi_penutupan_tetap" name="divisi_penutupan_tetap[]" style="width: 100%;" data-placeholder="Pilih Divisi">
																<?php
																foreach ($divisi as $dt) {
																	echo "<option value='" . $dt->id_divisi . "'>" . $dt->nama . "</option>";
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Realisasi</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_realisasi_tetap" id="if_approve_realisasi_tetap">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
																
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_realisasi_tetap" id="if_decline_realisasi_tetap">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<div class="form-group input-paralel hidden">
															<label> Divisi Terkait: </label>
															<select class="form-control select2" multiple="multiple" id="divisi_realisasi_tetap" name="divisi_realisasi_tetap[]" style="width: 100%;" data-placeholder="Pilih Divisi">
																<?php
																foreach ($divisi as $dt) {
																	echo "<option value='" . $dt->id_divisi . "'>" . $dt->nama . "</option>";
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</fieldset>										
										</div><!-- /.tab-pane -->
										<div class="tab-pane" id="tab_2">
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Pembukaan Depo Mitra</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_pembukaan_trial" id="if_approve_pembukaan_trial">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_pembukaan_trial" id="if_decline_pembukaan_trial">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<div class="form-group input-paralel hidden">
															<label> Divisi Terkait: </label>
															<select class="form-control select2" multiple="multiple" id="divisi_pembukaan_trial" name="divisi_pembukaan_trial[]" style="width: 100%;" data-placeholder="Pilih Divisi">
																<?php
																foreach ($divisi as $dt) {
																	echo "<option value='" . $dt->id_divisi . "'>" . $dt->nama . "</option>";
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Evaluasi Depo Mitra</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_evaluasi_trial" id="if_approve_evaluasi_trial">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_evaluasi_trial" id="if_decline_evaluasi_trial">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<div class="form-group input-paralel hidden">
															<label> Divisi Terkait: </label>
															<select class="form-control select2" multiple="multiple" id="divisi_evaluasi_trial" name="divisi_evaluasi_trial[]" style="width: 100%;" data-placeholder="Pilih Divisi">
																<?php
																foreach ($divisi as $dt) {
																	echo "<option value='" . $dt->id_divisi . "'>" . $dt->nama . "</option>";
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Penutupan Depo Mitra</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_penutupan_trial" id="if_approve_penutupan_trial">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_penutupan_trial" id="if_decline_penutupan_trial">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<div class="form-group input-paralel hidden">
															<label> Divisi Terkait: </label>
															<select class="form-control select2" multiple="multiple" id="divisi_penutupan_trial" name="divisi_penutupan_trial[]" style="width: 100%;" data-placeholder="Pilih Divisi">
																<?php
																foreach ($divisi as $dt) {
																	echo "<option value='" . $dt->id_divisi . "'>" . $dt->nama . "</option>";
																}
																?>
															</select>
														</div>
													</div>
												</div>
											</fieldset>										
											<fieldset class="fieldset-success">
												<legend class="text-left"><h4>Realisasi</h4></legend>
												<div class="row">
													<div class="form-group">
														<label for="if_approve_create_ho">Jika Disetujui</label>
														<select class="form-control select2" name="if_approve_realisasi_trial" id="if_approve_realisasi_trial">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
																
															?>
														</select>
													</div>
													<div class="form-group">
														<label for="if_decline_create_ho">Jika Ditolak</label>
														<select class="form-control select2" name="if_decline_realisasi_trial" id="if_decline_realisasi_trial">
															<?php
																echo "<option value='0'>Pilih Role</option>";
																foreach($role as $dt){
																	if($dt->na='n'){
																		echo"<option value='".$dt->level."'>".$dt->nama."</option>";
																	}
																}
																echo"<option value='1000'>Rejected</option>";
																echo"<option value='999'>Completed</option>";
															?>
														</select>
													</div>
													<div class="form-group">
														<div class="form-group input-paralel hidden">
															<label> Divisi Terkait: </label>
															<select class="form-control select2" multiple="multiple" id="divisi_realisasi_trial" name="divisi_realisasi_trial[]" style="width: 100%;" data-placeholder="Pilih Divisi">
																<?php
																foreach ($divisi as $dt) {
																	echo "<option value='" . $dt->id_divisi . "'>" . $dt->nama . "</option>";
																}
																?>
															</select>
														</div>
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
<script src="<?php echo base_url() ?>assets/apps/js/depo/master/role.js"></script>
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