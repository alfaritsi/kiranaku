<!--
/*
@application  : KODE MATERIAL
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-10">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-success" id="matrix_button">Generate Matrix</button>
						</div>
	          		</div>
	          		<!-- /.box-header -->
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Column Input: </label>
				                	<select class="form-control select2" id="filter_kolom" name="filter_kolom" style="width: 100%;" data-placeholder="Pilih Column Input">
				                  		<?php
					                		foreach($kolom as $dt){
					                			echo "<option value='".$dt->kolom."'>".$dt->kolom."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Material Type: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_mtart" name="filter_mtart[]" style="width: 100%;" data-placeholder="Pilih Material Type">
				                  		<?php
					                		foreach($mtart as $dt){
					                			echo "<option value='".$dt->mtart."'>".$dt->mtart."-".$dt->mtbez."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Classification: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_class" name="filter_class[]" style="width: 100%;" data-placeholder="Pilih Material Type">
				                  		<?php
											echo "<option value='A'>A-Asset</option>";
											echo "<option value='E'>E-Expense</option>";
											echo "<option value='I'>I-Inventory</option>";
											echo "<option value='IE'>IE-Inventory Expense</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->					
		          	<div class="box-body">
						<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Column Input</th>
				              	<th>Material Type</th>
				              	<th>Classification</th>
								<th>Required</th>
								<th>Default</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($matrix as $dt){
									$ck_req = ($dt->required=='y')?"checked":"";
									echo "<tr>";
				              		echo 	"<td>".$dt->kolom."</td>";
									echo 	"<td>".$dt->mtart."-".$dt->mtbez."</td>";
				              		echo 	"<td>".$dt->classification."-".$dt->classification_name."</td>";
				              		echo 	"<td><input $ck_req type='checkbox' class='switch-onoff' name='req' id='req' data-id='".$dt->id_item_master_matrix."'></td>";
									if($dt->tabel_sap!=null){
										echo 	"<td>";
										echo 		"<select class='form-control select2 def' name='def' data-id='".$dt->id_item_master_matrix."'>";
										echo 			"<option value=''>Pilih Set Default</option>";
										foreach($default as $dt2){
											$ck	= ($dt->def==$dt2->kd)?"selected":"";	
											echo 		"<option value='".$dt2->kd."' $ck>[".$dt2->kd."] ".$dt2->nm."</option>";
										}
										echo 		"</select>"; 
										echo 	"</td>";
									}else{
										echo	'<td><input type="text" class="form-control form-control-hide" name="def" id="def" placeholder="Set Default"></td>';	
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
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/material/master/matrix.js"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>