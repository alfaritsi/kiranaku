<!--
/*
	@application  		: Travel 
		@author       	: Airiza Yuddha (7849)
		@contributor  	:
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
						<table class="table table-bordered table-striped" id="sspTable">
							<thead>
								<tr>
									<th>tipe</th>
									<th>User</th>
									<th>Role Level</th>									
									<th>Approver</th>									
									<th>Email Approver</th>									
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								
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
									style="display:none">Buat Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-approval">
						<div class="box-body">
							
							<div class="form-group">
								<label for="nama_role">Jenis Input User</label>
								<div>
									<input type="hidden" class="form-control" name="jenis_input1_hidden" id="jenis_input1_hidden">
									<select class="form-control select2" id="jenis_input1" name="jenis_input1" style="width: 100%;" data-placeholder="Pilih Jenis">
					                  	<option value='jabatan' > JABATAN </option>
					                  	<option value='nik' > NIK </option>
				                	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="user">User</label>
								<div>									
									<input type="hidden" class="form-control" name="user_hidden" id="user_hidden">
									<select class="form-control select2" id="user" name="user" style="width: 100%;" data-placeholder="Pilih User">					                  	
				                	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="if_approve_spd">Role</label>
								<div>
									<input type="hidden" class="form-control" name="role_hidden" id="role_hidden" >
									<select class="form-control select2" id="role" name="role" style="width: 100%;" data-placeholder="Silahkan Pilih Role ">
				                  	<?php
				                  		// echo "<option value=''> Silahkan Pilih Role </option>";
				                		foreach($role as $dt){
				                			echo "<option value='".$dt->level."' >".$dt->role." </option>";
				                		}
				                	?>	
				                  	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="nama_role">Jenis Input Approver</label>
								<div>
									<input type="hidden" class="form-control" name="jenis_input2_hidden" id="jenis_input1_hidden">
									<select class="form-control select2" id="jenis_input2" name="jenis_input2" style="width: 100%;" data-placeholder="Pilih Jenis">
					                  	<option value='jabatan' > JABATAN </option>
					                  	<option value='nik' > NIK </option>
				                	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="user_app">Approver</label>
								<div>									
									<input type="hidden" class="form-control" name="user_app_hidden" id="user_app_hidden">
									<select class="form-control select2" id="user_app" name="user_app[]" style="width: 100%;"
                                            multiple
                                            data-placeholder="Pilih User">
					                <?php
				                  		foreach($jabatan as $dt){
				                			echo "<option value='".$dt->id_jabatan."' >".$dt->nama." </option>";
				                		}
				                		
				                	?>  	
				                	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="user_app_email">Email Approver</label>
								<div>									
									<input type="hidden" class="form-control" name="user_hidden" id="user_app_hidden">
									<select class="form-control select2" id="user_app_email" name="user_app_email[]" multiple="multiple" style="width: 100%;" data-placeholder="Pilih Email Approver">
					                  	
				                	</select>
								</div>
							</div>

						</div>
						<div class="box-footer">
							<input type="hidden" name="id_approval" />
							<button type="button" class="btn btn-sm btn-success" name="action_btn" value="submit">Submit</button>
						</div>
					</form>
				</div>
			</div>
			<!--modal add_modal_detail-->
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/travel/master/mapproval.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


