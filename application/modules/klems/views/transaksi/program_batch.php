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
	            		<h3 class="box-title"><strong>Setting <?php echo $title; ?></strong></h3>
						<button type="button" class="btn btn-sm btn-default pull-right" id="add_button">Tambah Batch Program</button> 
	          		</div>
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
								<th>BPO</th>
				              	<th>Program</th>
								<th>Batch Program</th>
								<th>Kode Batch Program</th>
								<th>Nama Batch Program</th>
								<th>Periode Batch Program</th>
								<th>Pabrik Peserta</th>
								<th>Jumlah Peserta</th>
								<th>Lokasi</th>
								<th>Status</th>
								<th>Aktif</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($program_batch as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->nama_bpo."</td>";
				              		echo "<td>".$dt->nama_program."</td>";
				              		echo "<td>".$dt->kode."<br>".$dt->nama."<br>".date_format(date_create($dt->tanggal_awal),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir),"d-m-Y")."</td>";
									echo "<td>".$dt->kode."</td>";
									echo "<td>".$dt->nama."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_awal),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir),"d-m-Y")."</td>";
									echo "<td>";
										$pabrik_list = explode(",", $dt->pabrik);
										foreach ($pabrik_list as $p) {
											echo "<button class='btn btn-sm btn-info btn-role'>".$p."</button>";
										}
									echo "</td>";
									echo "<td>";
										if($dt->peserta_tambahan!=null){
											$list_peserta 	= $dt->peserta.",".$dt->peserta_tambahan;	
										}else{
											$list_peserta 	= $dt->peserta;
										}
										$list_peserta 	= explode(",", str_replace(",,",",",$list_peserta));
										$no_peserta		= 0;
										foreach ($list_peserta as $ps) {
											$no_peserta++;
											// echo "<button class='btn btn-sm btn-info btn-role'>".$ps."</button>";
										}
										echo"<a href='#' class='detail_peserta' data-edit='".$generate->kirana_encrypt($dt->id_program_batch)."'><i class='fa fa-users'></i> $no_peserta Peserta</a>";
									echo "</td>";
									echo "<td>".$dt->lokasi."</td>";
									echo "<td>".$dt->status."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->status == 'Done'){
													echo"<li><a href='#'><i class='fa fa-lock'></i>Done</a></li>";	
												}
												if($dt->status == 'On Progress'){
													if($dt->na == 'n'){ 
													// <li><a href='#' class='grade' data-grade='".$generate->kirana_encrypt($dt->id_program_batch)."'><i class='fa fa-tachometer'></i> Set Grade </a></li>
													echo "
														  <li><a href='".base_url()."klems/transaksi/data/batch/".str_replace('/','_',$dt->kode)."'><i class='fa fa-gears'></i> Set Tahap Batch Program</a></li>	
														  <li><a href='#' class='set_biaya' data-edit='".$generate->kirana_encrypt($dt->id_program_batch)."'><i class='fa fa-money'></i> Set Biaya </a></li>
														  <li><a href='#' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_program_batch)."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>
														  <li><a href='#' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_program_batch)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
													}
													if($dt->na == 'y'){
													echo "<li><a href='#' class='set_active-program_batch' data-activate='".$generate->kirana_encrypt($dt->id_program_batch)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			<!--add persen grade-->
			<div class="modal fade" id="add_grade_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-program_grade">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Set Grade Program</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
		                		<label for="kode_program">Kode Program</label>
		                		<input type="text" class="form-control" name="kode_program" id="kode_program" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama_program">Nama Program</label>
		                		<input type="text" class="form-control" name="nama_program" id="nama_program" readonly>
							</div>
							<?php 
							foreach($grade as $n){
								echo'
									<div class="row">
										<div class="form-group">
											<div class="col-sm-3">
												<label>'.$n->nama.'</label>
												<input min="0" max="100" type="number" class="form-control cek_min_max" id="grade_awal_'.$n->id_grade.'" name="grade_awal_'.$n->id_grade.'" style="width: 100%;"/>
											</div>
											<div class="col-sm-1">	
												<label>&nbsp;</label>
												<label class="form-control no-border"> To </label>
											</div>
											<div class="col-sm-3">
												<label>&nbsp;</label>	
												<input min="0" max="100" type="number" class="form-control cek_min_max" id="grade_akhir_'.$n->id_grade.'" name="grade_akhir_'.$n->id_grade.'" style="width: 100%;"/>
											</div>
										</div>
									</div>
								';
							}
							?>	
		            	</div>
		            	<div class="box-footer">
							<input type="hidden" name="id_program_batch" id="id_program_batch"> 
		              		<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn_grade" class="btn btn-success btn-success_trainer">Submit</button>
						</div>
		          	</form>
					</div>
				</div>	
			</div>	
			
			<!--modal edit-->
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-program_batch">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Tambah/ Edit Batch Program</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
								<label for="bpo">BPO</label>
								<select class="form-control select2modal" name="bpo" id="bpo"  required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih BPO-</option>";
										foreach($bpo as $dt){
											echo"<option value='".$dt->id_bpo."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">	
								<label for="program">Program</label>
								<select class="form-control select2modal" name="program" id="program" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Program-</option>";
										foreach($program as $dt){
											echo"<option value='".$dt->id_program."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">		
		                		<label for="kode">Kode Batch Program</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode Batch Program"  required="required" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama">Nama Batch Program</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkkan Nama Batch Program"  required="required">
							</div>
							<div class="form-group">
								<div class="checkbox">
								<label>
								  <input type="checkbox" id="sertifikat_keahlian" name="sertifikat_keahlian" value="1" > Ceklist Jika Termasuk Sertifikat Keahlian
								</label>
								</div>
							</div>
							<div id="show_id_sertifikat" style="display: none">
								<div class="form-group">
									<label for="tanggal_awal">Tanggal Awal Sertifikat</label>
									<input type="text" class="form-control tanggal" name="tanggal_awal_sertifikat" id="tanggal_awal_sertifikat" placeholder="Masukkkan Tanggal Awal Sertifikat">
								</div>
								<div class="form-group">
									<label for="tanggal_akhir">Tanggal Akhir Sertifikat</label>
									<div id="div_tanggal_akhir_sertifikat">
										<input type="text" class="form-control tanggal" name="tanggal_akhir_sertifikat" id="tanggal_akhir_sertifikat" placeholder="Masukkkan Tanggal Akhir Sertifikat">
									</div>
								</div>
								<div class="form-group">		
									<label for="oleh">Dikeluarkan Oleh</label>
									<select class="form-control select2" name="oleh" id="oleh">
										<?php
											echo "<option value='0'>-Silahkan Pilih Institusi-</option>";
											foreach($institusi as $dt){
												echo"<option value='".$dt->nama."'>".$dt->nama."</option>";
											}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="tanggal_awal">Tanggal Awal Batch</label>
		                		<input type="text" class="form-control" name="tanggal_awal" id="tanggal_awal" placeholder="Masukkkan Tanggal Awal Batch"  required="required">
							</div>
							<div class="form-group">
								<label for="tanggal_akhir">Tanggal Akhir Batch</label>
								<div id="div_tanggal_akhir">
									<input type="text" class="form-control" name="tanggal_akhir" id="tanggal_akhir" placeholder="Masukkkan Tanggal Akhir Batch"  required="required">
								</div>
							</div>
							<div class="form-group">		
								<label for="lokasi">Lokasi</label>
								<input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Masukan Lokasi" required="required">
							</div>
							<div class="form-group">		
								<label for="kota">Kota</label>
								<input type="text" class="form-control" name="kota" id="kota" placeholder="Masukan Kota" required="required">
							</div>
		              		<div class="form-group">
		                		<label for="pabrik">Pabrik Peserta</label>
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAllpabrik"> Select All</label>
								</div>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="pabrik[]" id="pabrik" data-placeholder="Silahkan pilih Pabrik" required>
		                			<?php
		                				foreach($pabrik as $dt){
		                					echo "<option value='".$dt->plant."'>".$dt->plant_name."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
							<div class="form-group">		
								<label for="peserta">Peserta</label>
								<select class="form-control select2" multiple="multiple" name="peserta[]" id="peserta"  required="required">
								</select>
							</div>
							<div class="form-group">		
								<label for="peserta_tambahan">Peserta (Tambahan)</label>
								<select class="form-control select2" multiple="multiple" name="peserta_tambahan[]" id="peserta_tambahan">
								</select>
							</div>
							<div class="form-group">
								<div class="checkbox">
								<label>
								  <input type="checkbox" id="ck_ttd_kiri" name="ck_ttd_kiri" value="1" > Ceklist Jika Ada E-Signature 1
								</label>
								</div>
							</div>
							<div class="form-group">
								<label for="ttd_kiri">E-Signature 1</label>
								<select class="form-control select2 col-sm-12" name="ttd_kiri" id="ttd_kiri" data-placeholder="Silahkan pilih TTD">
									<?php
										echo "<option value=''>-Silahkan Pilih E-Signature-</option>";
										foreach($ttd as $dt){
											echo "<option value='".$dt->nik."'>".$dt->nik." [".$dt->nama."]</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<div class="checkbox">
								<label>
								  <input type="checkbox" id="ck_ttd_kanan" name="ck_ttd_kanan" value="1" > Ceklist Jika Ada E-Signature 2
								</label>
								</div>
							</div>
							<div class="form-group">
								<label for="ttd_kanan">E-Signature 2</label>
								<select class="form-control select2 col-sm-12" name="ttd_kanan" id="ttd_kanan" data-placeholder="Silahkan pilih TTD">
									<?php
										echo "<option value=''>-Silahkan Pilih E-Signature-</option>";
										foreach($ttd as $dt){
											echo "<option value='".$dt->nik."'>".$dt->nik." [".$dt->nama."]</option>";
										}
									?>
								</select>
							</div>
							<input type="hidden" name="status" value="On Progress">
							<input type="hidden" name="biaya_training" value="0">
							<input type="hidden" name="biaya_traveling" value="0">
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_program_batch">
							<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
						
					</div>
				</div>	
			</div>
			
			<!--modal biaya-->
			<div class="modal fade" id="add_modal_biaya" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-program_batch_biaya">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Set Biaya Program Batch</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
		                		<label for="kode">Kode Batch Program</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode Batch Program" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama">Nama Batch Program</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkkan Nama Batch Program" readonly>
							</div>
							<div class="form-group">
								<label for="biaya_training">Biaya Training</label>
		                		<input type="text" class="angka form-control" data-currency="no" min="0" maxlength='8' name="biaya_training" id="biaya_training" placeholder="Masukkan Biaya Training" required="required">
							</div>
							<div class="form-group">
								<label for="biaya_traveling">Biaya Traveling</label>
		                		<input type="text" class="angka form-control" data-currency="no" min="0" maxlength='8' name="biaya_traveling" id="biaya_traveling" placeholder="Masukkan Biaya Traveling" required="required">
							</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_program_batch">
							<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn_biaya" class="btn btn-success">Submit</button>
						</div>
		          	</form>
						
					</div>
				</div>	
			</div>
			<!--modal add_modal_detail_peserta-->
			<div class="modal fade" id="add_modal_detail_peserta" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-program_batch_detail_peserta">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Detail Peserta</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
		                		<label for="kode">Kode Batch Program</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode Batch Program" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama">Nama Batch Program</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkkan Nama Batch Program" readonly>
							</div>
							<div class="form-group">
								<label for="biaya_training">Peserta Training</label>
								<div id='show_peserta'></div>
							</div>
							<div class="form-group">
								<label for="biaya_training">Peserta Training(Tambahan)</label>
								<div id='show_peserta_tambahan'></div>
							</div>
		            	</div>
		          	</form>
					</div>
				</div>	
			</div>
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/program_batch.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.colVis.min.js"></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>