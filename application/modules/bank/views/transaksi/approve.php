<!--
/*
@application  : BANK SPESIMEN
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
				                	<label> Jenis Pengajuan: </label>
				                	<select class="form-control select2" multiple="multiple" id="jenis_pengajuan_filter" name="jenis_pengajuan_filter[]" style="width: 100%;" data-placeholder="Pilih Jenis Pengajuan">
				                  		<?php
											echo "<option value='pembukaan'>Pembukaan Rekening</option>";
											echo "<option value='penutupan'>Penutupan Rekening</option>";
											echo "<option value='perubahan'>Perubahan Rekening</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
				                	<select class="form-control select2" multiple="multiple" id="pabrik_filter" name="pabrik_filter[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
											if(!empty($user_role[0]->pabrik)){
												$arr_pabrik = explode(",", $user_role[0]->pabrik);
												foreach ($arr_pabrik as $pabrik) {
													if($pabrik!=''){
														echo "<option value='$pabrik'>$pabrik</option>";
													}
												}
											}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
							<!--
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_filter" name="status_filter[]" style="width: 100%;" data-placeholder=" Pilih Status">
				                  		<?php
											echo "<option value='completed'>Completed</option>";
											echo "<option value='on_progress'>On Progress</option>";
											echo "<option value='decline'>Decline</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
							-->
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
						<table class="table table-bordered table-striped"
							   id="sspTable">
							<thead>
								<tr>
									<th>Id</th>
									<th>Jenis Pengajuan</th>
									<th>Pabrik</th>
									<th>Nomor</th>
									<th>Tanggal</th>
									<th>Status</th>
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

<!--modal rekening-->
<div class="modal fade" id="modal_rekening" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-rekening">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Input Nomor Rekening</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">		
								<label for="nomor">Nomor</label>
								<input type="text" class="form-control" name="nomor" id="nomor" placeholder="Nomor" readonly>
							</div>
							<div class="form-group">	
								<label for="nama_bank">Nama Bank</label>
								<input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="Nama Bank"  readonly>
							</div>
							<div class="form-group">
								<label for="cabang_bank">Cabang Bank</label>
								<input type="text" class="form-control" name="cabang_bank" id="cabang_bank" placeholder="Cabang Bank" readonly>
							</div>
							<div class="form-group form-control_komentar">	
								<label for="mata_uang">Mata Uang</label>
								<input type="text" class="form-control" name="mata_uang" id="mata_uang" placeholder="Mata Uang" readonly>
							</div>
							<div class="form-group form-control_komentar">	
								<label for="nomor_rekening">Nomor Rekening</label>
								<input type="text" class="form-control" name="nomor_rekening" id="nomor_rekening" placeholder="Nomor Rekening" required="required">
							</div>
						</div>
						<div class="modal-footer">
							<input id="id_data_temp" name="id_data_temp" type="text">
							<button id="btn_save_rekening" type="button" class="btn btn-primary" name="btn_save_rekening">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>

<!--modal coa-->
<div class="modal fade" id="modal_coa" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-coa">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Input No COA</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">		
								<label for="nomor">Nomor</label>
								<input type="text" class="form-control" name="nomor" id="nomor" placeholder="Nomor" readonly>
							</div>
							<div class="form-group">	
								<label for="nama_bank">Nama Bank</label>
								<input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="Nama Bank"  readonly>
							</div>
							<div class="form-group">
								<label for="cabang_bank">Cabang Bank</label>
								<input type="text" class="form-control" name="cabang_bank" id="cabang_bank" placeholder="Cabang Bank" readonly>
							</div>
							<div class="form-group form-control_komentar">	
								<label for="mata_uang">Mata Uang</label>
								<input type="text" class="form-control" name="mata_uang" id="mata_uang" placeholder="Mata Uang" readonly>
							</div>
							<div class="form-group form-control_komentar">	
								<label for="no_coa">No COA</label>
								<input type="number" maxlength="10" class="form-control" name="no_coa" id="no_coa" placeholder="No COA" required="required">
							</div>
						</div>
						<div class="modal-footer">
							<input id="id_data_temp" name="id_data_temp" type="text">
							<button id="btn_save_coa" type="button" class="btn btn-primary" name="btn_save_coa">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/bank/transaksi/approve.js"></script>
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