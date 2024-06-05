<!--
/*
@application  : Plantation
@author       : Benazi S. Bahari (10183)
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
                        <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="false" data-bautowidth="true" data-pagelength="10">
		              		<thead>
				              	<th>User/ Posisi</th>
				              	<th>Role</th>
				              	<th>Pabrik</th>
				              	<th>Action</th>
				            </thead>
			            </table>
			        </div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title title-form">Form Setting User Role</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Usser Role</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" id="form-master" enctype="multipart/form-data">
	            		<div class="box-body">
							<div class="form-group">
								<label for="id_role">Role</label>
								<select class="form-control select2" name="id_role" id="id_role" required="required" data-allowClear="true">
									<?php
										echo "<option value=''>Pilih Role</option>";
										foreach($role as $dt){
											echo"<option value='".$dt->id_role."' data-tipe_user='".$dt->tipe_user."'>".$dt->nama_role."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">		
								<label for="user">User</label>
								<select class="form-control select2" name="user" id="user"  required="required">
								</select>
							</div>
							<div class="form-group">
								<label> Pabrik: </label>
								<div class="checkbox pull-right" style="margin:0;">
									<label><input type="checkbox" class="isSelectAllPlant"> All</label>
								</div>
								<select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Pabrik"  required="required">
									<?php
										foreach($plant as $dt){
											echo"<option value='".$dt->plant."'>".$dt->plant."</option>";
										}
									?>
								</select>
							</div>
		            	</div>
		            	<div class="box-footer">
							<input id="id_user_role" name="id_user_role" type="hidden">
							<button type="reset" id="btn_reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spk/master_userrole.js?<?php echo time(); ?>"></script>