<!--
/*
@application  : Simulasi Penjualan SPOT
@author       : Airiza Yuddha (7849)
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
						<!-- <table class="table table-bordered table-striped my-datatable-extends-order"> -->
						<table class="table table-bordered table-striped " id="sspTable">
		              		<thead>
				              	<th>ID</th>
				              	<th>Pabrik</th>
				              	<th>Tanggal</th>
				              	<th>QTY (TON)</th>
								<th>Harga</th>
								<th>Source</th>
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
		              	<h3 class="box-title title-form"><?php echo $title_form; ?></h3>
		              	<!-- <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Port Of Load</button> -->
		          	</div>
		          	<form role="form" class="form-master-upload" enctype="multipart/form-data">
	            		<div class="box-body">
							<div class="form-group">
								<label for="file">File Excel</label>
								<input type="file" class="form-control" name="file_excel" id="file_excel" multiple="">
								<span class="font-weight-bold text-danger pull-left"> * Type file yang diperbolehkan hanya xls/xlsx </span>
							</div>
							<br/>
						</div>
		            	<div class="box-footer">
							<input type="hidden" name="id">
							<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn" class="btn btn-success">Upload</button>
						</div>
		          	</form>
		        </div>
			</div>

			

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spot/master/upload.js"></script>
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