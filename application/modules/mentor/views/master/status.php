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
				              	<th width='5%'>ID</th>
				              	<th>Nama Status</th>
				              	<th>Warna</th>
				              	<th>Max Days</th>
				              	<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($status as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->id_status."</td>";
				              		echo "<td>".strtoupper($dt->nama)."</td>";
				              		echo "<td><label class='label' style='background-color:".$dt->warna.";'>".strtoupper($dt->nama)."</label></td>";
				              		echo "<td>".$dt->max_day."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
												echo"<ul class='dropdown-menu pull-right'>";
													if($dt->na == 'n'){ 
														echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
														// echo "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
													}
													if($dt->na == 'y'){
														echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Form Status Master Depo</h3>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-status_mentoring">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="nama">Nama Status</label>
									<input type="text" class="form-control" name="nama" id="nama" placeholder="Input Nama Status" required="required">
								</div>
								<div class="form-group">
									<label for="warna">Warna</label>
									<input type="color" class="form-control" name="warna" id="warna" placeholder="Input Warna" value='#ffffff' required="required">
								</div>
								<div class="form-group">
									<label for="max_day">Max Days</label>
									<input type="text" class="form-control angka" name="max_day" id="max_day" placeholder="Input Max Days" required="required">
								</div>
							</div><!-- /.col -->
						</div>
					</div>
					<div class="modal-footer">
						<input id="id_status" name="id_status" type="hidden">
						<button type="reset" class="btn btn-danger btn-reset hidden">Reset</button>
						<button type="button" name="action_btn" class="btn btn-success btn-submit hidden">Submit</button>
					</div>
					</form>
				</div>
			</div>
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/mentor/master/status.js"></script>
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