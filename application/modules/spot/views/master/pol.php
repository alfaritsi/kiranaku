<!--
/*
@application  : Simulasi Penjualan SPOT
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
				              	<th>Port Of Load</th>
				              	<th>No Urut</th>
				              	<th>Selisih</th>
								<th>Plant</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($pol as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->nama_port."</td>";
				              		echo "<td>".$dt->no_urut."</td>";
				              		echo "<td>".number_format($dt->selisih, 2)."</td>";
									echo "<td>";
										if(!empty($dt->list_plant)){
											$list_plant = explode(",", substr($dt->list_plant,0,-1));
											foreach ($list_plant as $l) {
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
												// echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_spot_setting_pol."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
												echo "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_spot_setting_pol."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_spot_setting_pol."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Setting Port Of Load</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Port Of Load</button>
		          	</div>
		          	<form role="form" class="form-master-pol">
	            		<div class="box-body">
							<div class="form-group">
								<label for="port">Port Of Load</label>
								<select class="form-control select2" name="port" id="port"  required="required">
									<?php
										echo "<option value='0'>Pilih Port</option>";
										foreach($port as $dt){
											echo"<option value='".$dt->port."'>".$dt->name."</option>";
										}
									?>
								</select>
		                	</div>
		              		<div class="form-group">
		                		<label for="plant">Plant</label>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="plant[]" id="plant" data-placeholder="Pilih Plant">
				                  		<?php
					                		foreach($plant as $dt){
					                			echo "<option value='".$dt->plant."'>".$dt->plant_name."</option>";
					                		}
					                	?>
		                		</select>
		              		</div>
							<div class="form-group">
								<label for="no_urut">No Urut</label>
		                		<input type="text" class="form-control angka" name="no_urut" id="no_urut" placeholder="No Urut" required="required">
		                	</div>
							<div class="form-group">
								<label for="selisih">Selisih</label>
		                		<input type="text" class="form-control angka" name="selisih" id="selisih" placeholder="Selisih">
		                	</div>
							
		            	</div>
		            	<div class="box-footer">
							<input type="hidden" name="id_spot_setting_pol">
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
<script src="<?php echo base_url() ?>assets/apps/js/spot/master/pol.js"></script>
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