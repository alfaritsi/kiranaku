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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">

<style type="text/css">
  .disabled.day {
    opacity: 0.90;
    filter: alpha(opacity=90);
    background-color: lightgrey !important;
    color: black !important;

  }
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<?php 
								echo'<button type="button" class="btn btn-success" id="add_mentee">Add Mentee</button>';	
							?>
						</div>
						
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-4">
			            		<div class="form-group">
				                	<label>Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_status" name="filter_status[]" style="width: 100%;" data-placeholder="Pilih Status">
				                  		<?php
											echo "<option value='on progress'>On Progress</option>";
											echo "<option value='completed'>Completed</option>";
											echo "<option value='not completed'>Not Completed</option>";
										
					                		// foreach($status as $dt){
					                			// echo "<option value='".$dt->id_status."'>".$dt->nama."</option>";
					                		// }
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
									<th>Nomor</th>
									<th>NIK</th>
									<th>Nama Mentee</th>
									<th>Jabatan</th>
									<th>Departemen</th>
									<th>Tanggal Sesi 1</th>
									<th>Tanggal Sesi 2</th>
									<th>Tanggal DMC 1</th>
									<th>Tanggal DMC 2</th>
									<th>Tanggal DMC 3</th>
									<th>SLA</th>
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
<!--modal edit-->
<div class="modal fade" id="add_modal" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-input-mentee">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Form Penambahan Mentee</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label>NIK/Nama Mentee</label>
										<select class="form-control select2" name="nik_mentee" id="nik_mentee" placeholder="Nama Mentee" required="required">
										</select>
									</div>
									<div class="col-xs-6">
										<label>Jabatan Mentee</label>
										<input type="text" class="form-control" name="nama_jabatan_mentee" id="nama_jabatan_mentee" placeholder="Jabatan Mentee"  required="required" readonly>
										<input type="hidden" class="form-control" name="jabatan_mentee" id="jabatan_mentee" placeholder="Jabatan Mentee" readonly>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label>Departemen Mentee</label>
										<input type="text" class="form-control" name="nama_departemen_mentee" id="nama_departemen_mentee" placeholder="Departemen Mentee"  required="required" readonly>
										<input type="hidden" class="form-control" name="departemen_mentee" id="departemen_mentee" placeholder="Departemen Mentee" readonly>
									</div>
									<div class="col-xs-6">
										<label>Telepon Mentee</label>
										<input type="text" class="form-control" name="telepon_mentee" id="telepon_mentee" placeholder="Telepon Mentee"  required="required">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label>Tanggal Rencana Sesi 1</label>
										<input type="text" class="form-control tanggal_sesi1_range" name="tanggal_sesi1_rencana" id="tanggal_sesi1_rencana" placeholder="Tanggal Rencana Sesi 1"  required="required">
									</div>
									<div class="col-xs-6">
										<label>Tanggal Rencana Sesi 2</label>
										<input type="text" class="form-control tanggal_sesi2_range" name="tanggal_sesi2_rencana" id="tanggal_sesi2_rencana" placeholder="Tanggal Rencana Sesi 2"  required="required">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label>Tanggal Rencana DMC 1</label>
										<input type="text" class="form-control tanggal_dmc1_range" name="tanggal_dmc1_rencana" id="tanggal_dmc1_rencana" placeholder="Tanggal Rencana DMC 1"  required="required">
									</div>
									<div class="col-xs-6">
										<label>Tanggal Rencana DMC 2</label>
										<input type="text" class="form-control tanggal_dmc2_range" name="tanggal_dmc2_rencana" id="tanggal_dmc2_rencana" placeholder="Tanggal Rencana DMC 2"  required="required">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label>Tanggal Rencana DMC 3</label>
										<input type="text" class="form-control tanggal_dmc3_range" name="tanggal_dmc3_rencana" id="tanggal_dmc3_rencana" placeholder="Tanggal Rencana DMC 3"  required="required">
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input id="act" name="act" value="create" type="text">
							<input id="nomor" name="nomor" type="text">
							<input id="tanggal_buat" name="tanggal_buat" type="text">
							<button id="btn_save" type="button" class="btn btn-primary" name="action_btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>

<!--modal detail-->
<div class="modal fade" id="detail_modal" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-input-detail">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Detail Data Mentoring</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label>Nomor Mentoring</label>
										<input type="text" class="form-control" name="nomor_mentoring" id="nomor_mentoring" placeholder="Nomor"  required="required" readonly>
									</div>
									<div class="col-xs-6">
										<label>Nama Mentor</label>
										<input type="text" class="form-control" name="nama_mentor" id="nama_mentor" placeholder="Nama Mentor" required="required" readonly>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label>NIK/Nama Mentee</label>
										<input type="text" class="form-control" name="nik_mentee" id="nik_mentee" placeholder="NIK Mentee" required="required" readonly>
									</div>
									<div class="col-xs-6">
										<label>Jabatan Mentee</label>
										<input type="text" class="form-control" name="nama_jabatan_mentee" id="nama_jabatan_mentee" placeholder="Jabatan Mentee"  required="required" readonly>
									</div>
								</div>
							</div>
							<div class="form-group show_mentor_dmc1 hide">
								<div class="row">
									<div class="col-xs-6">
										<label>Additional Mentor DMC 1</label>
										<select class="form-control select2" name="nik_mentor_dmc1" id="nik_mentor_dmc1" placeholder="Nama Mentor DMC 1">
										</select>
									</div>
								</div>
							</div>
							<div class="form-group show_mentor_dmc2 hide">
								<div class="row">
									<div class="col-xs-6">
										<label>Additional Mentor DMC 2</label>
										<select class="form-control select2" name="nik_mentor_dmc2" id="nik_mentor_dmc2" placeholder="Nama Mentor DMC 2">
										</select>
									</div>
								</div>
							</div>
							<div class="form-group show_mentor_dmc3 hide">
								<div class="row">
									<div class="col-xs-6">
										<label>Additional Mentor DMC 3</label>
										<select class="form-control select2" name="nik_mentor_dmc3" id="nik_mentor_dmc3" placeholder="Nama Mentor DMC 3">
										</select>
									</div>
								</div>
							</div>
							<fieldset class="fieldset-default show_sesi1 hide">
							<legend class="text-left"><h4>Persiapan 1</h4></legend>
								<div class="form-group">
									<div class="row">
										<div class="col-xs-6">
											<label>Tanggal Aktual Sesi 1</label>
											<input type="text" class="form-control tanggal_sesi1_range_aktual" name="tanggal_sesi1_aktual" id="tanggal_sesi1_aktual" placeholder="Tanggal Aktual Sesi 1">
										</div>
									</div>
								</div>
							</fieldset>
							<fieldset class="fieldset-default show_sesi2 hide">
							<legend class="text-left"><h4>Persiapan 2</h4></legend>
								<div class="form-group">
									<div class="row">
										<div class="col-xs-6">
											<label>Tanggal Aktual Sesi 2</label>
											<input type="text" class="form-control tanggal_sesi2_range_aktual" name="tanggal_sesi2_aktual" id="tanggal_sesi2_aktual" placeholder="Tanggal Aktual Sesi 2">
										</div>
										<div class="col-xs-6">
											<label>Sasaran Pengembangan</label>
											<textarea name="sasaran_pengembangan" id="sasaran_pengembangan" class="form-control" rows="3" placeholder="Sasaran Pengembangan"></textarea>
										</div>
									</div>
								</div>
							</fieldset>
							<div class="nav-tabs-custom" id="tabs-edit">
								<ul class="nav nav-tabs">
									<li><a href="#tab-dmc1" data-toggle="tab">DMC 1</a></li>
									<li><a href="#tab-dmc2" data-toggle="tab">DMC 2</a></li>
									<li><a href="#tab-dmc3" data-toggle="tab">DMC 3</a></li>
								</ul>
								<div class="modal-body">
									<div class="tab-content">
										<div class="tab-pane" id="tab-dmc1">
											<!--DMC1--> 
											<fieldset class="fieldset-default show_dmc1 hide">
											<legend class="text-left"><h4 class="modal-title_dmc1">Jurnal DMC 1</h4></legend>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Tanggal Aktual DMC 1</label>
															<input type="text" class="form-control tanggal_dmc1_range_aktual" name="tanggal_dmc1_aktual" id="tanggal_dmc1_aktual" placeholder="Tanggal Aktual DMC 1">
														</div>
														<div class="col-xs-6">
															<label>Isu DMC 1</label>
															<input type="text" class="form-control" name="isu_dmc1" id="isu_dmc1" placeholder="Isu DMC1">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Tujuan DMC 1</label>
															<input type="text" class="form-control" name="tujuan_dmc1" id="tujuan_dmc1" placeholder="Tujuan DMC 1">
														</div>
														<div class="col-xs-6">
															<label>Realitas DMC 1</label>
															<input type="text" class="form-control" name="realitas_dmc1" id="realitas_dmc1" placeholder="Realitas DMC 1">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Opsi DMC 1</label>
															<input type="text" class="form-control" name="opsi_dmc1" id="opsi_dmc1" placeholder="Opsi DMC 1">
														</div>
														<div class="col-xs-6">
															<label>Rencana Aksi DMC 1</label>
															<input type="text" class="form-control" name="rencana_aksi_dmc1" id="rencana_aksi_dmc1" placeholder="Rencana Aksi DMC 1">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Waktu DMC 1</label>
															<input type="text" class="form-control" name="waktu_dmc1" id="waktu_dmc1" placeholder="Waktu DMC 1">
														</div>
														<div class="col-xs-6">
															<label>Indikator Berhasil DMC 1</label>
															<input type="text" class="form-control" name="indikator_berhasil_dmc1" id="indikator_berhasil_dmc1" placeholder="Indikator Berhasil DMC 1">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Catatan DMC 1</label>
															<textarea name="catatan_dmc1" id="catatan_dmc1" class="form-control" rows="3" placeholder="Catatan DMC 1"></textarea>
														</div>
													</div>
												</div>
											</fieldset>
										</div>	
										<div class="tab-pane" id="tab-dmc2">
											<!--DMC2--> 
											<fieldset class="fieldset-default show_dmc2 hide">
											<legend class="text-left"><h4 class="modal-title_dmc2">Jurnal DMC 2</h4></legend>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Tanggal Aktual DMC 2</label>
															<input type="text" class="form-control tanggal_dmc2_range_aktual" name="tanggal_dmc2_aktual" id="tanggal_dmc2_aktual" placeholder="Tanggal Aktual DMC 2">
														</div>
														<div class="col-xs-6">
															<label>Isu DMC 2</label>
															<input type="text" class="form-control" name="isu_dmc2" id="isu_dmc2" placeholder="Isu dmc2">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Tujuan DMC 2</label>
															<input type="text" class="form-control" name="tujuan_dmc2" id="tujuan_dmc2" placeholder="Tujuan DMC 2">
														</div>
														<div class="col-xs-6">
															<label>Realitas DMC 2</label>
															<input type="text" class="form-control" name="realitas_dmc2" id="realitas_dmc2" placeholder="Realitas DMC 2">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Opsi DMC 2</label>
															<input type="text" class="form-control" name="opsi_dmc2" id="opsi_dmc2" placeholder="Opsi DMC 2">
														</div>
														<div class="col-xs-6">
															<label>Rencana Aksi DMC 2</label>
															<input type="text" class="form-control" name="rencana_aksi_dmc2" id="rencana_aksi_dmc2" placeholder="Rencana Aksi DMC 2">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Waktu DMC 2</label>
															<input type="text" class="form-control" name="waktu_dmc2" id="waktu_dmc2" placeholder="Waktu DMC 2">
														</div>
														<div class="col-xs-6">
															<label>Indikator Berhasil DMC 2</label>
															<input type="text" class="form-control" name="indikator_berhasil_dmc2" id="indikator_berhasil_dmc2" placeholder="Indikator Berhasil DMC 2">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Catatan DMC 2</label>
															<textarea name="catatan_dmc2" id="catatan_dmc2" class="form-control" rows="3" placeholder="Catatan DMC 2"></textarea>
														</div>
													</div>
												</div>
											</fieldset>
										</div>	
										<div class="tab-pane" id="tab-dmc3">
											<!--DMC3-->		
											<fieldset class="fieldset-default show_dmc3 hide">
											<legend class="text-left"><h4 class="modal-title_dmc3">Jurnal DMC 3</h4></legend>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Tanggal Aktual DMC 3</label>
															<input type="text" class="form-control tanggal_dmc3_range_aktual" name="tanggal_dmc3_aktual" id="tanggal_dmc3_aktual" placeholder="Tanggal Aktual DMC 3">
														</div>
														<div class="col-xs-6">
															<label>Isu DMC 3</label>
															<input type="text" class="form-control" name="isu_dmc3" id="isu_dmc3" placeholder="Isu dmc3">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Tujuan DMC 3</label>
															<input type="text" class="form-control" name="tujuan_dmc3" id="tujuan_dmc3" placeholder="Tujuan DMC 3">
														</div>
														<div class="col-xs-6">
															<label>Realitas DMC 3</label>
															<input type="text" class="form-control" name="realitas_dmc3" id="realitas_dmc3" placeholder="Realitas DMC 3">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Opsi DMC 3</label>
															<input type="text" class="form-control" name="opsi_dmc3" id="opsi_dmc3" placeholder="Opsi DMC 3">
														</div>
														<div class="col-xs-6">
															<label>Rencana Aksi DMC 3</label>
															<input type="text" class="form-control" name="rencana_aksi_dmc3" id="rencana_aksi_dmc3" placeholder="Rencana Aksi DMC 3">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Waktu DMC 3</label>
															<input type="text" class="form-control" name="waktu_dmc3" id="waktu_dmc3" placeholder="Waktu DMC 3">
														</div>
														<div class="col-xs-6">
															<label>Indikator Berhasil DMC 3</label>
															<input type="text" class="form-control" name="indikator_berhasil_dmc3" id="indikator_berhasil_dmc3" placeholder="Indikator Berhasil DMC 3">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Catatan DMC 3</label>
															<textarea name="catatan_dmc3" id="catatan_dmc3" class="form-control" rows="3" placeholder="Catatan DMC 3"></textarea>
														</div>
													</div>
												</div>
											</fieldset>
										</div>	
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input id="act" name="act" type="text">
							<input id="nomor" name="nomor" type="text">
							<button id="btn_save" type="button" class="btn btn-primary" name="btn_approve">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>

<!--modal history-->
<div class="modal fade" id="modal-history" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">History Mentoring</h4>
					</div>
					<div class="modal-body">
						<div id='histori_mentor'></div>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
	</div>	
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/mentor/transaksi/mentee.js"></script>
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