<!--
/*
@application  : Asset Management
@author		  : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->
<?php $this->load->view('header') ?>
<?php 
$awal = (empty($_POST['awal']))?date('Y-m-d', strtotime(date('Y-m-d').'-3 months')):$_POST['awal'];
$akhir = (empty($_POST['akhir']))?date('Y-m-d'):$_POST['akhir'];
?>
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
						<button type="button" class="btn btn-sm btn-default pull-right" id="add_button">Retire Asset</button> 
	          		</div>
	          		<!-- /.box-header -->
		          	
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
				                	<select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
					                		foreach($pabrik as $dt){
												if(($dt->nama=='PT. Kirana Megatara')and(base64_decode($this->session->userdata("-ho-")) == 'y')){
													echo "<option value='".$dt->id_pabrik."' selected>".$dt->nama."</option>";
												}else{
													echo "<option value='".$dt->id_pabrik."'>".$dt->nama."</option>";	
												}
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Lokasi: </label>
				                	<select class="form-control select2" multiple="multiple" id="lokasi" name="lokasi[]" style="width: 100%;" data-placeholder="Pilih Lokasi">
				                  		<?php
					                		foreach($lokasi as $dt){
					                			echo "<option value='".$dt->id_lokasi."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Area: </label>
				                	<select class="form-control select2" multiple="multiple" id="area" name="area[]" style="width: 100%;" data-placeholder="Pilih Area">
				                  		<?php
					                		foreach($area as $dt){
					                			echo "<option value='".$dt->id_area."'";
					                			echo ">".$dt->nama_lokasi." - ".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Jenis Asset: </label>
				                	<select class="form-control select2" multiple="multiple" id="jenis" name="jenis[]" style="width: 100%;" data-placeholder="Pilih Jenis">
				                  		<?php
					                		foreach($jenis as $dt){
					                			echo "<option value='".$dt->id_jenis."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
							<!--
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Merk Asset: </label>
				                	<select class="form-control select2" multiple="multiple" id="merk" name="merk[]" style="width: 100%;" data-placeholder="Pilih Merk">
				                  		<?php
					                		foreach($merk as $dt){
					                			echo "<option value='".$dt->id_merk."'";
					                			echo ">".$dt->nama_jenis." - ".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
							-->
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Kondisi: </label>
				                	<select class="form-control select2" multiple="multiple" id="kondisi" name="kondisi[]" style="width: 100%;" data-placeholder="Pilih Kondisi">
				                  		<?php
					                		foreach($kondisi as $dt){
					                			echo "<option value='".$dt->id_kondisi."'>".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="flag" name="flag[]" style="width: 100%;" data-placeholder="Pilih Status">
				                  		<?php
											echo "<option value='menunggu'>Menunggu Approve</option>";
											echo "<option value='proses'>Approved</option>";
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
									<th>Nama Asset</th>
									<th>Nomor Asset SAP</th>
									<th>Jenis</th>
									<th>Merk</th>
									<th>Pabrik</th>
									<th>Lokasi</th>
									<th>Sub Lokasi</th>
									<th>Area</th>
									<th>Nama User</th>
									<th>Nama Vendor</th>
									<th>Kondisi</th>
									<th>Status</th>
									<th>Aktif</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
			<!--modal set movement-->
			<div class="modal fade" id="set_retire_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-retire" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Retire Asset</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<div class="row">
												<div class="col-xs-4">
													<label for="label_nomor_sap">Nomor SAP / Nama Asset</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nomor_sap_move" class="form-control-static">-</span> / 
													<span id="label_kode_barang_move" class="form-control-static">ICT001</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-4">
													<label for="label_nama_lokasi">Lokasi / Area</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nama_lokasi_move" class="form-control-static">Lokasi</span> / 
													<span id="label_nama_area_move" class="form-control-static">Area</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-4">
													<label for="label_nama_karyawan_move">Pengguna</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nama_karyawan_move" class="form-control-static"></span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-4">
													<label for="label_kondisi">Kondisi</label>
												</div>
												<div class="col-xs-8">
													<span id="label_kondisi_move" class="form-control-static"></span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-4">
													<label for="tanggal_retire">Tanggal Retire</label>
												</div>
												<div class="col-xs-4">
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
														<input class="form-control tanggal" required readonly name="tanggal_retire" id="tanggal_retire">
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">		
											<div class="row">
												<div class="col-xs-4">
													<label for="label_alasan">Alasan</label>
												</div>
												<div class="col-xs-5">
													<select class="form-control select2modal" name="opt_alasan" id="opt_alasan"  required="required">
														<?php
															echo "<option value='0'>Silahkan Pilih Alasan</option>";
															echo "<option value='Accident'>Accident</option>";
															echo "<option value='Hilang'>Hilang</option>";
															echo "<option value='Mati Total'>Mati Total</option>";
															echo "<option value='Lain-Lain'>Lain-Lain</option>";
														?>
													</select>
													<div id="show_alasan"></div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_kondisi_awal" name="id_kondisi_awal" type="text">
										<input id="id_aset" name="id_aset" type="text">
										<button type="button" class="btn btn-primary" name="action_btn_retire">Submit</button>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>	
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('maintenance/includes/_modal_pm_history') ?>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/retire_it.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>


<style>
    .small-box .icon {
        top: -13px;
    }

    .select2-container--open {
        z-index: 9999999
    }
</style>