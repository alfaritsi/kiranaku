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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Tipe Vendor: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_tipe_filter" name="id_tipe[]" style="width: 100%;" data-placeholder="Pilih Tipe Vendor">
				                  		<?php
					                		foreach($tipe as $dt){
					                			echo "<option value='".$dt->id_tipe."'>".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Kategori Vendor: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_kategori_filter" name="id_kategori[]" style="width: 100%;" data-placeholder="Pilih Kategori Vendor">
				                  		<?php
					                		foreach($kategori as $dt){
					                			echo "<option value='".$dt->id_kategori."'>".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status Extend: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_filter" name="status_filter[]" style="width: 100%;" data-placeholder="Pilih Status Pengajuan">
				                  		<?php
											echo "<option value='y' selected>On Progress</option>";
											echo "<option value='n' selected>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
						<table class="table table-bordered table-striped"
							   id="sspTable">
							<thead>
								<tr>
									<th>Id</th>
									<th>Nama Vendor</th>
									<th>Tipe Vendor</th>
									<th>Kategori Vendor</th>
									<th>Total Nilai</th>
									<th>Status Extend</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
		</div>
	</section>
</div>

<!--modal extend-->
<div class="modal fade" id="add_extend" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-extend-vendor">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Extend Master Vendor</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">		
								<label for="code">LIFNR</label>
								<input type="text" class="form-control form-control-hide" name="lifnr" id="lifnr" placeholder="Material Code"  required="required" disabled>
							</div>
							<div class="form-group">	
								<label for="description">Nama Vendor</label>
								<input type="text" class="form-control" name="nama" id="nama" placeholder="Description"  required="required" disabled>
							</div>
							
							<div class="form-group">	
								<label for="plant_asis">Plant</label>
								<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_asis[]" id="plant_asis" data-placeholder="Pilih Plant Extend" disabled>
								</select>
							</div>
							<div class="form-group">	
								<label for="plant_extend">Plant Extend</label>
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAllPlantExtend form-control-hide"> All Plant Extend</label>
								</div>
								<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_extend[]" id="plant_extend" data-placeholder="Pilih Plant Extend" required>
								</select>
							</div>
						</div>
						<div class="modal-footer">
							<input id="id_data" name="id_data" type="text">
							<input id="I_LIFNR" name="I_LIFNR" type="text">
							<input id="I_EKORG_REF" name="I_EKORG_REF" type="text">
							<input id="I_BUKRS_REF" name="I_BUKRS_REF" type="text">
							<input id="I_KTOKK" name="I_KTOKK" type="text">
							<!--
							<input id="acc_group" name="acc_group" type="text">
							<input id="bukrs" name="bukrs" type="text">
							<input id="plant" name="plant" type="text">
							-->
							<button id="btn_save" type="button" class="btn btn-primary" name="action_btn_extend">Submit</button>
							<button id="btn_approve" type="button" class="btn btn-primary" name="action_btn_approve_extend">Approve</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/vendor/transaksi/extend.js"></script>
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