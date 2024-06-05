<!--
/*
@application  : Mapping Plant Exclude
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
				              	<th>Apps</th>
				              	<th>Plant Exclude</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($plant_header as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->apps."</td>";
									echo "<td>";
										if(!empty($dt->plant_exclude)){
											$plant_exclude = explode(",", $dt->plant_exclude);
											foreach ($plant_exclude as $l) {
												echo "<button class='btn btn-sm btn-info btn-role'>".$l."</button>";
											}
										}
									echo "</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->apps."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
													  <li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->apps."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->apps."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Form Mapping Plant Header</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Mapping Plant</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-mapping-header">
	            		<div class="box-body">
							<div class="form-group">
								<label for="apps">APPS</label>
								<input type="text" class="form-control" name="apps" id="apps" placeholder="Apps"  required="required">
		                	</div>
		              		<div class="form-group">
		                		<label for="posisi">Plant Exclude</label>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="plant_exclude[]" id="plant_exclude" data-placeholder="Chose Plant Exclude" required="required">
		                			<?php
		                				foreach($plant as $dt){
		                					echo "<option value='".$dt->plant."'>".$dt->plant."</option>";
		                				}
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
<script src="<?php echo base_url() ?>assets/apps/js/settings/mapping/plant_header.js"></script>
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