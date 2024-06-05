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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">

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
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Jenis Depo: </label>
				                	<select class="form-control select2" multiple="multiple" id="tahap_filter" name="tahap_filter[]" style="width: 100%;" data-placeholder="Pilih Tahap">
				                  		<?php
											echo "<option value=''></option>";
											foreach($tahap as $dt){
												echo "<option value='".$dt->id_tahap."'>".strtoupper($dt->nama)."</option>";
											}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_filter" name="status_filter[]" style="width: 100%;" data-placeholder=" Pilih Status">
				                  		<?php
											echo "<option value='completed'>Completed</option>";
											echo "<option value='on_progress'>On Progress</option>";
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
				              	<th>Nama Jadwal Bokin</th>
				              	<th>Tahap</th>
				              	<th>Tanggal Awal</th>
				              	<th>Tanggal Akhir</th>
								<th width="15%">Status</th>
				              	<th width="15%">Action</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title title-form">Form Jadwal BOKIN</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Jadwal BOKIN</button>
		          	</div>
		          	<form role="form" class="form-taksasi-jadwal">
	            		<div class="box-body">
							<div class="form-group">
								<label for="nama">Nama Jadwal BOKIN</label>
		                		<input style="text-transform: uppercase" type="text" class="form-control" name="nama" id="nama" placeholder="Nama Jadwal" required="required">
		                	</div>
							<div class="form-group">
								<label for="id_tahap">Tahap</label>
								<select class="form-control form-control-hide select2" name="id_tahap" id="id_tahap" required="required" data-placeholder="PILIH TAHAP">
									<?php
										echo "<option ></option>";
										foreach ($tahap as $dt) {
											echo "<option value='" . $dt->id_tahap . "'>".$dt->nama."</option>";
										}
									?>
								</select>
								<input type="hidden" class="form-control" name="pra_syarat" id="pra_syarat" placeholder="Pra Syarat">
		                	</div>
							<div class="form-group">
								<label for="nik">Peserta</label>
								<select class="form-control select2" multiple="multiple" id="peserta" name="peserta[]" style="width: 100%;">
								</select>
		                	</div>
							<div class="form-group">
								<label for="pass_grade">Pass Grade</label>
		                		<input type="text" class="form-control angka" name="pass_grade" id="pass_grade" placeholder="PASS GRADE" required="required">
		                	</div>
							<div style="width:100%; overflow-x:auto">
								<button type="button" name="add_grade" class="btn btn-sm btn-secondary mb-2" title="Tambah Item"><i class="fa fa-plus"></i></button>
								<table class="table table-hover table-bordered table-grade">
									<thead>
										<tr>
											<th class="text-center">Penilaian</th>
											<th class="text-center" width="20%">Bobot</th>
											<th class="text-center" width="15%"></th>
										</tr>
									</thead>
									<tbody>
										<tr id="nodata_grade">
											<td colspan="3">No data found</td>
										</tr>
									</tbody>
								</table>
							</div>
							
		            	</div>
		            	<div class="box-footer">
							<input id="id_jadwal" name="id_jadwal" type="hidden">
		              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>
		</div>
	</section>
</div>

<!--modal history-->
<div class="modal fade" id="modal-history" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">History Master Depo</h4>
					</div>
					<div class="modal-body">
						<div id='histori_pengajuan'></div>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
	</div>	
</div>


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/taksasi/transaksi/jadwal.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
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