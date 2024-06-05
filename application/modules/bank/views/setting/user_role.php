<!--
/*
@application  : BANK SPECIMEN
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
				              	<th>User/ Posisi</th>
				              	<th>Role</th>
				              	<th>Pabrik</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
								
				              	foreach($user_role as $dt){
									$no++;
									echo "<tr>";
				              		echo "<td>".$dt->user." (".$dt->caption_user.")</td>";
				              		echo "<td>".$dt->nama_role."</td>";
									echo "<td>";
									if(!empty($dt->pabrik)){
										$arr_pabrik = explode(",", $dt->pabrik);
										foreach ($arr_pabrik as $l) {
											if($l!=''){
												echo "<button class='btn btn-sm btn-info btn-role'>".$l."</button>";
											}
										}
									}
									echo "</td>";
									echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
												echo"<ul class='dropdown-menu pull-right'>";
													if($dt->na == 'n'){ 
														echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_user_role."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
														echo "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_user_role."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
													}
													if($dt->na == 'y'){
														echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_user_role."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Form Setting User Role</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Usser Role</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-user_role">
	            		<div class="box-body">
							<div class="form-group">
								<label for="id_role">Role</label>
								<select class="form-control select2" name="id_role" id="id_role" required="required">
									<?php
										echo "<option value='0'>Pilih Role</option>";
										foreach($role as $dt){
											echo"<option value='".$dt->id_role."' data-tipe_user='".$dt->tipe_user."'>".$dt->nama."</option>";
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
								<div class="checkbox pull-right" style="margin:0; display: ;">
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
<script src="<?php echo base_url() ?>assets/apps/js/bank/setting/user_role.js"></script>
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