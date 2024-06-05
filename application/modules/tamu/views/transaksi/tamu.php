<!--
/*
@application  : BUKU TAMU
@author       : Lukman Hakim (7143)
@contributor  : 
      1. Benazi S. Bahari (10183) 17-06-2021
         tambah untuk keperluan hasil self assessment         
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
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label>Tanggal Awal :</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" value="<?php echo date('d.m.Y');?>" id="filter_from" name="filter_from" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Tanggal Akhir :</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<div id="div_filter_to">
										<input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" value="<?php echo date('d.m.Y');?>" id="filter_to" name="filter_to" readonly>
										</div>
									</div>
								</div>
							</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_status" name="status[]" style="width: 100%;" data-placeholder="Pilih Status">
				                  		<?php
											echo "<option value='n' selected>Not Complete</option>";
											echo "<option value='y'>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->	
					
		          	<div class="box-body">
						<table class="table table-bordered table-striped my-datatable-extends-order">	   
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>Nama</th>
									<th>Perusahaan</th>
									<th>Waktu Datang</th>
									<th>Waktu Pulang</th>
									<!-- <th>6 Angka<br>Pertama NIK</th>
									<th>No Handphone</th> -->
									<th>Tujuan Kunjungan</th>
									<th>Bertemu dengan<br>Karyawan KM</th>
									<th>Status</th>
									<th>Self<br>Assessment</th>
									<th>Action</th>
								</tr>
							</thead>
			              	<tbody>
			              		<?php
				              	foreach($tamu as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->caption_tanggal_kunjungan."</td>";
				              		echo "<td>".$dt->nama_tamu."</td>";
				              		echo "<td>".$dt->perusahaan."</td>";
				              		echo "<td>".$dt->caption_waktu_datang."</td>";
				              		echo "<td>".$dt->caption_waktu_pulang."</td>";
				              		// echo "<td>".$dt->nik_tamu."</td>";
				              		// echo "<td>".$dt->telepon."</td>";
				              		echo "<td>".$dt->tujuan_kunjungan."</td>";
				              		echo "<td>".$dt->nama_karyawan."</td>";
				              		echo "<td>".$dt->label_status."</td>";
									//assessment
									if ($dt->is_assessment == 1) {
										if ($dt->score_assessment >= 15 || $dt->score_assessment_danger > 0) {
											echo "<td style='text-align:center;'><a href='javascript:void(0)' class='hasil_assessment' data-id_tamu='".$dt->id_tamu."' data-score_assessment='".$dt->score_assessment."' data-score_assessment_danger='".$dt->score_assessment_danger."'><span class='badge bg-red'>Resiko Besar</span></a></td>";
										} else if ($dt->score_assessment >= 10) {
											echo "<td style='text-align:center;'><a href='javascript:void(0)' class='hasil_assessment' data-id_tamu='".$dt->id_tamu."' data-score_assessment='".$dt->score_assessment."' data-score_assessment_danger='".$dt->score_assessment_danger."'><span class='badge bg-yellow'>Resiko Sedang</span></a></td>";
										} else {
											echo "<td style='text-align:center;'><a href='javascript:void(0)' class='hasil_assessment' data-id_tamu='".$dt->id_tamu."' data-score_assessment='".$dt->score_assessment."' data-score_assessment_danger='".$dt->score_assessment_danger."'><span class='badge bg-green'>Resiko Kecil</span></a></td>";
										}
									} else {
										echo "<td></td>";
									}
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->completed == 'n'){
													if($dt->is_assessment == 1 && !$dt->waktu_datang) {
														echo "<li><a href='javascript:void(0)' class='konfirmasi_hadir' data-id_tamu='".$dt->id_tamu."'><i class='fa fa-pencil-square-o'></i> Konfirmasi Kehadiran</a></li>";
													} else {
														echo "<li><a href='javascript:void(0)' class='konfirmasi' data-id_tamu='".$dt->id_tamu."'><i class='fa fa-pencil-square-o'></i> Konfirmasi Kepulangan</a></li>";
													}
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
			
			<!--modal konfirmasi-->
			<div class="modal fade" id="konfirmasi_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-tamu">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Konfirmasi Kepulangan</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">	
											<label for="taggal">Tanggal Kunjungan</label>
											<input type="text" class="form-control" name="caption_tanggal_kunjungan" id="caption_tanggal_kunjungan" placeholder="Tanggal Kunjungan" readonly>
										</div>
										<div class="form-group">	
											<label for="nama">Nama Tamu</label>
											<input type="text" class="form-control" name="nama_tamu" id="nama_tamu" placeholder="Nama Tamu"  readonly>
										</div>
										<div class="form-group">	
											<label for="perusahaan">Perusahaan</label>
											<input type="text" class="form-control" name="perusahaan" id="perusahaan" placeholder="Perusahaan"  readonly>
										</div>
										<div class="form-group">	
											<label for="nama_karyawan">Bertemu Karyawan KM</label>
											<input type="text" class="form-control" name="nama_karyawan" id="nama_karyawan" placeholder="Nama Karyawan"  readonly>
										</div>
										<div class="form-group">	
											<label for="taggal">Konfirmasi NIK Karyawan KM</label>
											<select class="form-control" name="set_nik" id="set_nik" data-placeholder="Cari karyawan (nama atau nik)" required="required">
												<option></option>
											</select>
											<input type="hidden" class="form-control" name="nik_karyawan" placeholder="Nama User"  required="required">
										</div>
									</div>
									<div class="modal-footer">
										<input id="id_tamu" name="id_tamu" type="hidden">
										<button id="btn_save" type="button" class="btn btn-primary" name="action_btn_konfirmasi">Konfirmasi Kepulangan</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			
			<!-- modal hasil assessment -->
			<div class="modal fade" id="modal_hasil_assessment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Hasil Assessment</h4>
								</div>
								<div class="modal-body">
									<table>
										<tr>
											<td style="width: 30%;">Suhu Tubuh</td>
											<td>: <span id="suhu_assessment"></span></td>
										</tr>
										<tr>
											<td style="width: 30%;">Total Score</td>
											<td>: <span id="total_score_assessment"></span></td>
										</tr>
									</table>
									<br>
									<table id="table-assessment" class="table table-bordered">
										<thead>
											<tr>
												<th>No.</th>
												<th>Pertanyaan</th>
												<th>Skor</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/tamu/transaksi/tamu.js"></script>
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