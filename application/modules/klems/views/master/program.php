<!--
/*
@application  : KLEMS (Kirana Learning Management System)
@author     : Lukman Hakim (7143)
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
	            		<h3 class="box-title"><strong>Setting <?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Jenis Program</th>
				              	<th>Kode Program</th>
				              	<th>Nama Program</th>
				              	<th>Abbreviation</th>
				              	<th>Kategori</th>
				              	<th>Tipe Program</th>
				              	<th>Tipe Penyelenggara</th>
				              	<th>Sertifikat</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($program as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->jenis."</td>";
				              		echo "<td>".$dt->kode."</td>";
				              		echo "<td>".$dt->nama."</td>";
				              		echo "<td>".$dt->abbreviation."</td>";
				              		echo "<td>".$dt->kategori."</td>";
				              		echo "<td>".$dt->tipe_program."</td>";
				              		echo "<td>".$dt->tipe_penyelenggara."</td>";
				              		echo "<td>".str_replace("_"," ",$dt->jenis_sertifikat)."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "
													  <li><a href='".base_url()."klems/master/data/budget/".$dt->kode."'><i class='fa fa-money'></i> Setting Budget</a></li>
													  <li><a href='".base_url()."klems/master/data/matrix/".$dt->kode."'><i class='fa fa-group'></i> Setting Matrix</a></li>
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_program)."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_program)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-program' data-activate='".$generate->kirana_encrypt($dt->id_program)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			<div class="col-sm-4">
				<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title title-form">Buat Setting Program</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Program Baru</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-program">
	            		<div class="box-body">
		              		<div class="form-group">
								<label for="jenis">Jenis Program</label>
								<select class="form-control select2" name="jenis" id="jenis" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Jenis-</option>";
										echo "<option value='Training'>Training</option>";
										echo "<option value='Sharing'>Sharing</option>";
										echo "<option value='Event'>Event</option>";
									?>
								</select>
							</div>
							<div class="form-group">		
		                		<label for="kode">Kode Program</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode Program" required="required" value="<?php echo str_pad($no, 4, 0, STR_PAD_LEFT);?>" readonly>
							</div>
							<div class="form-group">	
								<label for="nama">Nama Program</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkkan Nama Program" required="required">
		                	</div>
							<div class="form-group">
								<label for="abbreviation">Abbreviation</label>
		                		<input type="text" class="form-control" data-currency="no" maxlength='8' name="abbreviation" id="abbreviation" placeholder="Masukkan Abbreviation" required="required">
							</div>
							<div class="form-group">
								<div class="checkbox">
								<label>
								  <input type="checkbox" id="sertifikat_keahlian" name="sertifikat_keahlian" value="1" > Ceklist Jika Termasuk Sertifikat Keahlian
								</label>
								</div>
							</div>
							<div class="form-group" id="show_id_sertifikat" style="display: none">		
								<label for="id_sertifikat">Nama Sertifikasi Keahlian</label>
								<select class="form-control select2" name="id_sertifikat" id="id_sertifikat">
									<?php
										echo "<option value='0'>-Silahkan Pilih Sertifikasi-</option>";
										foreach($sertifikat as $dt){
											echo"<option value='".$dt->id_sertifikat."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">	
								<label for="kategori">Kategori</label>
								<select class="form-control select2" name="kategori" id="kategori" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Kategori-</option>";
										echo "<option value='Mandatori'>Mandatori</option>";
										echo "<option value='Non Mandatori'>Non Mandatori</option>";
									?>
								</select>
							</div>
							<div class="form-group">	
								<label for="tipe_program">Tipe Program</label>
								<select class="form-control select2" name="tipe_program" id="tipe_program" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Tipe Program-</option>";
										echo "<option value='Basic Training'>Basic Training</option>";
										echo "<option value='Leadership Training'>Leadership Training</option>";
										echo "<option value='Functional Training'>Functional Training</option>";
										echo "<option value='Improvement Training'>Improvement Training</option>";
										echo "<option value='General Training'>General Training</option>";
									?>
								</select>
							</div>
							<div class="form-group">	
								<label for="tipe_penyelenggara">Tipe Penyelenggara</label>
								<select class="form-control select2" name="tipe_penyelenggara" id="tipe_penyelenggara" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Tipe Penyelenggara-</option>";
										echo "<option value='Internal'>Internal</option>";
										echo "<option value='Eksternal'>Eksternal</option>";
									?>
								</select>
							</div>
							<div class="form-group">	
								<label for="jenis_sertifikat">Jenis Sertifikat</label>
								<select class="form-control select2" name="jenis_sertifikat" id="jenis_sertifikat" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Jenis Sertifikat-</option>";
										echo "<option value='Attendance'>Attendance</option>";
										echo "<option value='Achievement'>Achievement</option>";
										echo "<option value='Non_Certificate'>Non Certificate</option>";
									?>
								</select>
							</div>
							<div class="form-group">		
		                		<label for="keterangan">Keterangan</label>
		                		<input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Masukkkan Keterangan" required="required">
		             		</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_program">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/program.js"></script>
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