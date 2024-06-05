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
									<th>Nama Mentor</th>
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
										<input type="text" class="form-control" name="nomor_mentoring" id="nomor_mentoring" placeholder="Nomor" readonly>
									</div>
									<div class="col-xs-6">
										<label>Nama Mentor</label>
										<input type="text" class="form-control" name="nama_mentor" id="nama_mentor" placeholder="Nama Mentor" readonly>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label>NIK/Nama Mentee</label>
										<input type="text" class="form-control" name="nik_mentee" id="nik_mentee" placeholder="NIK Mentee" readonly>
										</select>
									</div>
									<div class="col-xs-6">
										<label>Jabatan Mentee</label>
										<input type="text" class="form-control" name="nama_jabatan_mentee" id="nama_jabatan_mentee" placeholder="Jabatan Mentee" readonly>
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
							<!--SESI1-->
							<fieldset class="fieldset-default show_sesi1 hide">
								<legend class="text-left"><h4>Persiapan 1</h4></legend>
								<div class="form-group">
									<div class="row">
										<div class="col-xs-6">
											<label>Dokumen AIM Assessment</label>
											<input type="file" multiple="multiple" class="form-control" id="dokumen_scraft" name="dokumen_scraft[]">
										</div>
									</div>
								</div>
							</fieldset>
							<!--SESI2--> 
							<fieldset class="fieldset-default show_sesi2 hide">
							<legend class="text-left"><h4>Persiapan 2</h4></legend>
								<div class="form-group">
									<div class="row">
										<div class="col-xs-6">
											<label>Tanggal Aktual Sesi 2</label>
											<input type="text" class="form-control tanggal" name="tanggal_sesi2_aktual" id="tanggal_sesi2_aktual" placeholder="Tanggal Aktual Sesi 2">
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
									<li class="active"><a href="#tab-dmc1" data-toggle="tab">DMC 1</a></li>
									<li><a href="#tab-dmc2" data-toggle="tab">DMC 2</a></li>
									<li><a href="#tab-dmc3" data-toggle="tab">DMC 3</a></li>
								</ul>
								<div class="modal-body">
									<div class="tab-content">
										<div class="tab-pane" id="tab-dmc1">
											<!--DMC1--> 
											<fieldset class="fieldset-default show_dmc1 hide">
											<legend class="text-left"><h4 class="modal-title_dmc1">Mentee Rating DMC 1</h4></legend>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Apa Sasaran pengembangan/tujuan/goal?</label>
															<textarea name="sasaran_pengembangan_dmc1" id="sasaran_pengembangan_dmc1" class="form-control" rows="3" placeholder="Sasaran Pengembangan DMC 1"></textarea>
														</div>
														<div class="col-xs-6">
															<label>Apa Kriteria keberhasilan atas sasaran pengembangan? </label>
															<textarea name="kriteria_keberhasilan_dmc1" id="kriteria_keberhasilan_dmc1" class="form-control" rows="3" placeholder="Kriteria Keberhasilan DMC 1"></textarea>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="btn-group pull-right">
														<label class="label" style="background-color:#11e70d;">1:Sangat Tidak Setuju</label>	
														<label class="label" style="background-color:#11e70d;">5:Sangat Setuju</label>	
													</div>
													<table class="table table-bordered table-striped  table-feedback_dmc1 datatable-mentor">
														<thead>
															<tr>
																<th>Pertanyaan</th>
																<th>1</th>
																<th>2</th>
																<th>3</th>
																<th>4</th>
																<th>5</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_feedback_dmc1">
																<td colspan="6">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</fieldset>
										</div>	
										<div class="tab-pane" id="tab-dmc2">
											<!--DMC2--> 
											<fieldset class="fieldset-default show_dmc2 hide">
											<legend class="text-left"><h4 class="modal-title_dmc2">Mentee Rating DMC 2</h4></legend>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Apa Sasaran pengembangan/tujuan/goal?</label>
															<textarea name="sasaran_pengembangan_dmc2" id="sasaran_pengembangan_dmc2" class="form-control" rows="3" placeholder="Sasaran Pengembangan DMC 2"></textarea>
														</div>
														<div class="col-xs-6">
															<label>Apa Kriteria keberhasilan atas sasaran pengembangan? </label>
															<textarea name="kriteria_keberhasilan_dmc2" id="kriteria_keberhasilan_dmc2" class="form-control" rows="3" placeholder="Kriteria Keberhasilan DMC 2"></textarea>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="btn-group pull-right">
														<label class="label" style="background-color:#11e70d;">1:Sangat Tidak Setuju</label>	
														<label class="label" style="background-color:#11e70d;">5:Sangat Setuju</label>	
													</div>
													<table class="table table-bordered table-striped  table-feedback_dmc2 datatable-mentor">
														<thead>
															<tr>
																<th>Pertanyaan</th>
																<th>1</th>
																<th>2</th>
																<th>3</th>
																<th>4</th>
																<th>5</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_feedback_dmc2">
																<td colspan="6">No data found</td>
															</tr>
														</tbody>
													</table>
												</div>
											</fieldset>							
										</div>	
										<div class="tab-pane" id="tab-dmc3">
											<!--DMC3--> 
											<fieldset class="fieldset-default show_dmc3 hide">
											<legend class="text-left"><h4 class="modal-title_dmc3">Mentee Rating DMC 3</h4></legend>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label>Apa Sasaran pengembangan/tujuan/goal?</label>
															<textarea name="sasaran_pengembangan_dmc3" id="sasaran_pengembangan_dmc3" class="form-control" rows="3" placeholder="Sasaran Pengembangan DMC 3"></textarea>
														</div>
														<div class="col-xs-6">
															<label>Apa Kriteria keberhasilan atas sasaran pengembangan? </label>
															<textarea name="kriteria_keberhasilan_dmc3" id="kriteria_keberhasilan_dmc3" class="form-control" rows="3" placeholder="Kriteria Keberhasilan DMC 3"></textarea>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="btn-group pull-right">
														<label class="label" style="background-color:#11e70d;">1:Sangat Tidak Setuju</label>	
														<label class="label" style="background-color:#11e70d;">5:Sangat Setuju</label>	
													</div>
													<table class="table table-bordered table-striped  table-feedback_dmc3 datatable-mentor">
														<thead>
															<tr>
																<th>Pertanyaan</th>
																<th>1</th>
																<th>2</th>
																<th>3</th>
																<th>4</th>
																<th>5</th>
															</tr>
														</thead>
														<tbody>
															<tr id="nodata_feedback_dmc3">
																<td colspan="6">No data found</td>
															</tr>
														</tbody>
													</table>
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
							<input id="nik_mentor" name="nik_mentor" type="text">
							<input id="nik_mentor_dmc1" name="nik_mentor_dmc1" type="text">
							<input id="nik_mentor_dmc2" name="nik_mentor_dmc2" type="text">
							<input id="nik_mentor_dmc3" name="nik_mentor_dmc3" type="text">
							<button id="btn_save" type="button" class="btn btn-primary" name="action_btn">Submit</button>
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
<script src="<?php echo base_url() ?>assets/apps/js/mentor/transaksi/mentor.js"></script>
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