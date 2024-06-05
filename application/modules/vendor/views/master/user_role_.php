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
				              	<th>Nama</th>
				              	<th>Role</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($user_role as $dt){
									$no++;
									echo "<tr>";
				              		echo "<td>".strtoupper($dt->nama_karyawan)." - [".$dt->nik."]</td>";
				              		echo "<td>".$dt->nama_role."</td>";
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
								<label for="nik">Nama & NIK</label>
								<select class="form-control select2" name="nik" id="nik"  required="required"></select>
							</div>
							<div class="form-group">
								<label for="id_role">Role</label>
								<select class="form-control select2" name="id_role" id="id_role" required="required">
									<?php
										echo "<option value='0'>Pilih Role</option>";
										foreach($role as $dt){
											echo"<option value='".$dt->id_role."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							
		            	</div>
		            	<div class="box-footer">
							<input id="id_user_role" name="id_user_role" type="hidden">
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
<script src="<?php echo base_url() ?>assets/apps/js/vendor/master/user_role.js"></script>
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