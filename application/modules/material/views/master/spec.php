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
				              	<th>Item Group</th>
				              	<th>Item Name</th>
				              	<th>Item Spec ID</th>
				              	<th>Item Spec Description</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($spec as $dt){
									$no++;
									echo "<tr>";
				              		// echo "<td>".$dt->id_item_group." - ".$dt->group_description."</td>";
				              		// echo "<td>".$dt->id_item_name." - ".$dt->name_description."</td>";
				              		// echo "<td>".$dt->id_item_desc."</td>";
				              		// echo "<td>".$dt->material_desc."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_item_spec."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>
													  <li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_item_spec."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_item_spec."'><i class='fa fa-check'></i> Set Aktif</a></li>";
												}
									echo " 	</ul>
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
		              	<h3 class="box-title title-form">Form Specification</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Specification</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-item">
	            		<div class="box-body">
							<div class="form-group">
								<label for="id_item_group">Item Group</label>
								<select class="form-control select2" name="id_item_group" id="id_item_group"  required="required">
									<?php
										echo "<option value='0'>Pilih Item Group</option>";
										foreach($group as $dt){
											echo"<option value='".$dt->id_item_group."'>".$dt->description."</option>";
										}
									?>
								</select>
		                	</div>
		              		<div class="form-group">
		                		<label for="id_item_name">Item Name ID</label>
		                		<input type="text" class="form-control" name="id_item_name" id="id_item_name" placeholder="Input ID Item Name" required="required" value="<?php echo str_pad($no, 4, 0, STR_PAD_LEFT);?>" readonly>
		                	</div>
							<div class="form-group">
								<label for="description">Item Name Desc</label>
		                		<input type="text" class="form-control" name="description" id="description" placeholder="Input Description" required="required">
		                	</div>
							<div class="form-group">
								<label for="bklas">Valuation Class</label>
								<select class="form-control select2" name="bklas" id="bklas"  required="required">
									<?php
										echo "<option value='0'>Pilih Item Group</option>";
										foreach($bklas as $dt){
											echo"<option value='".$dt->bklas."'>".$dt->bklas."-".$dt->bkbez."</option>";
										}
									?>
								</select>
		                	</div>
							<div class="form-group">
								<label for="matkl">Material Group</label>
								<select class="form-control select2" name="matkl" id="matkl"  required="required">
									<?php
										echo "<option value='0'>Pilih Item Group</option>";
										foreach($matkl as $dt){
											echo"<option value='".$dt->matkl."'>".$dt->matkl."-".$dt->wgbez."</option>";
										}
									?>
								</select>
		                	</div>
		              		<div class="form-group">
								<label for="classification">Classification</label>
								<select class="form-control select2" name="classification" id="classification">
									<?php
										echo "<option value='0'>Pilih Classification</option>";
										echo "<option value='Inventory'>Inventory</option>";
										echo "<option value='Inventory Expense'>Inventory Expense</option>";
									?>
								</select>
							</div>
		            	</div>
		            	<div class="box-footer">
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
<script src="<?php echo base_url() ?>assets/apps/js/material/master/spec.js"></script>
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