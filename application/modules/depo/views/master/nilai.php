<!--
/*
@application  : MASTER DEPO
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
				              	<th>Nilai Awal</th>
				              	<th>Nilai Akhir</th>
				              	<th>Keterangan</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($nilai as $dt){
									if($dt->na == 'n'){
										echo "<tr>";
										echo "<td>".$dt->nilai_awal."</td>";
										echo "<td>".$dt->nilai_akhir."</td>";
										echo "<td>".$dt->keterangan."</td>";
										echo "<td>".$dt->label_active."</td>";
										echo "<td>
											  <div class='input-group-btn'>
												<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
													echo"<ul class='dropdown-menu pull-right'>";
													echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->keterangan."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
													echo"</ul>";
										echo " 	
											  </div>
											</td>";
										echo "</tr>";
									}
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
		              	<h3 class="box-title title-form">Form Master Nilai</h3>
		          	</div>
		          	<form role="form" class="form-master-nilai">
	            		<div class="box-body">
							<div class="form-group">
								<label for="nilai_awal">Nilai Awal</label>
		                		<input type="number" class="form-control" name="nilai_awal" id="nilai_awal" placeholder="Nilai Awal" required="required" readonly>
		                	</div>
							<div class="form-group">
								<label for="nilai_akhir">Nilai Akhir</label>
		                		<input type="number" class="form-control" name="nilai_akhir" id="nilai_akhir" placeholder="Nilai Akhir" required="required" readonly>
		                	</div>
							<div class="form-group">
								<label for="keterangan">Keterangan</label>
		                		<input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan" required="required" readonly>
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
<script src="<?php echo base_url() ?>assets/apps/js/depo/master/nilai.js"></script>
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