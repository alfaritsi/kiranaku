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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css">

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
						<button type="button" class="btn btn-sm btn-default pull-right" id="add_button">Tambah Tahap Batch Program</button> 
						<button type="button" class="btn btn-sm btn-default pull-right" id="back">Back</button> 
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Nama Program</th>
				              	<th>Batch Program</th>
								<th>Kode Batch Program</th>
								<th>Nama Batch Program</th>
								<th>Periode Batch Program</th>
								<th>Tahap</th>
								<th>Tanggal Awal</th>
								<th>Tanggal Akhir</th>
								<th>Tanggal Test</th>
								<th>Jam Test</th>
								<th>Lokasi</th>
								<th>Online</th>
								<th>Aktif</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($batch as $dt){
									$tanggal = ($dt->tanggal=='1900-01-01')?'-':date_format(date_create($dt->tanggal),"d-m-Y");
									echo "<tr>";
				              		echo "<td>".$dt->nama_program."</td>";
				              		echo "<td>".$dt->kode_program_batch."<br>".$dt->nama_program_batch."<br>".date_format(date_create($dt->tanggal_awal_program_batch),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir_program_batch),"d-m-Y")."</td>";
									echo "<td>".$dt->kode_program_batch."</td>";
				              		echo "<td>".$dt->nama_program_batch."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_awal_program_batch),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir_program_batch),"d-m-Y")."</td>";
									echo "<td>".$dt->nama_tahap."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_awal),"d-m-Y")."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_akhir),"d-m-Y")."</td>";
									echo "<td>".$tanggal."</td>";
									echo "<td>".$dt->jam_awal." - ".$dt->jam_akhir."</td>";
									echo "<td>".$dt->tempat."</td>";
									echo "<td>".$dt->label_online."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
													if($dt->generate_soal=='y'){
														echo"<li><a href='".base_url()."klems/transaksi/data/soal/".$generate->kirana_encrypt($dt->id_batch)."'><i class='fa fa-print'></i> Cetak Soal</a></li>";	
														// echo"<li><a href='".base_url()."klems/transaksi/data/cetak/".$dt->id_batch."'><i class='fa fa-print'></i> Cetak Soal</a></li>";	
														echo "<li><a href='#'><i class='fa fa-lock'></i> Soal Tergenarate</a></li>";													
													}else{
														echo "
																<li><a href='#' class='jumlah_soal' data-jumlah_soal='".$generate->kirana_encrypt($dt->id_batch)."'><i class='fa fa-file-text'></i> Set Jumlah Soal </a></li>
																<li><a href='#' class='grade' data-grade='".$generate->kirana_encrypt($dt->id_batch)."'><i class='fa fa-tachometer'></i> Set Grade Batch</a></li>
																<li><a href='#' class='persen_grade' data-persen_grade='".$generate->kirana_encrypt($dt->id_batch)."'><i class='fa fa-tasks'></i> Set Persen Grade </a></li>
																<li><a href='#' class='trainer' data-trainer='".$generate->kirana_encrypt($dt->id_batch)."'><i class='fa fa-users'></i> Set Trainer </a></li>";
														if($dt->trainer!=null){
															echo "	<li><a href='#' class='generate_soal' data-id_batch='".$generate->kirana_encrypt($dt->id_batch)."'><i class='fa fa-clipboard'></i> Generate Soal </a></li>";	
														}
														echo "	
																<li><a href='#' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_batch)."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>
																<li><a href='#' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_batch)."'><i class='fa fa-trash-o'></i> Hapus</a></li>
														 ";
													}
												}
												if($dt->na == 'y'){
													echo "<li><a href='#' class='set_active-batch' data-activate='".$generate->kirana_encrypt($dt->id_batch)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-batch">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Tambah/ Edit Tahap Batch Program</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
		                		<label for="nama_program">Nama Program</label>
		                		<input type="text" class="form-control" name="nama_program" id="nama_program" value="<?php if (isset($program_batch))echo $program_batch[0]->nama_program?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="kode_program_batch">Kode Batch Program</label>
		                		<input type="text" class="form-control" name="kode_program_batch" id="kode_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->kode?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama_batch">Nama Batch Program</label>
		                		<input type="text" class="form-control" name="nama_batch" id="nama_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->nama?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="tanggal_awal_program_batch">Tanggal Batch Program Awal</label>
		                		<input type="text" class="form-control" name="tanggal_awal_program_batch" id="tanggal_awal_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->tanggal_awal?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="tanggal_akhir_program_batch">Tanggal Batch Program Akhir</label>
		                		<input type="text" class="form-control" name="tanggal_akhir_program_batch" id="tanggal_akhir_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->tanggal_akhir?>" readonly>
							</div>
							<div class="form-group">		
								<label for="id_tahap">Tahap</label>
								<select class="form-control select2" name="id_tahap" id="id_tahap"  required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Tahap-</option>";
										foreach($tahap as $dt){
											echo"<option value='".$dt->id_tahap."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="tanggal_awal">Tanggal Awal</label>
		                		<input type="text" class="form-control tanggal" name="tanggal_awal" id="tanggal_awal" placeholder="Masukan Tanggal Awal" required="required">
							</div>
							<div class="form-group">
								<label for="tanggal_akhir">Tanggal Akhir</label>
								<div id="div_tanggal_akhir">
									<input type="text" class="form-control tanggal" name="tanggal_akhir" id="tanggal_akhir" placeholder="Masukan Tanggal Akhir" required="required">
								</div>
							</div>
							<div class="form-group">
								<label for="tanggal">Tanggal Test</label>
								<div id="div_tanggal_test">
									<input type="text" class="form-control tanggal" name="tanggal" id="tanggal" placeholder="Masukan Tanggal Test">
								</div>	
							</div>
							<div class="form-group">
								<label for="jam_awal">Jam Awal Test</label>
								<input type="text" class="form-control" name="jam_awal" id="jam_awal" placeholder="Masukan Jam Awal Test">
							</div>
							<div class="form-group">
								<label for="jam_akhir">Jam Akhir Test</label>
								<input type="text" class="form-control" name="jam_akhir" id="jam_akhir" placeholder="Masukan Jam Akhir Test">
							</div>
							<div class="form-group">
								<label for="lokasi">Lokasi</label>
								<input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Masukan Lokasi"  required="required">
							</div>
							<div class="form-group">
								<div class="checkbox">
								<label>
								  <input type="checkbox" id="online" name="online" value="1" > Ceklist Jika Test Online
								</label>
								</div>
							</div>
		            	</div>
		            	<div class="box-footer">
							
		             		<input type="hidden" name="id_batch">
		             		<input type="hidden" name="id_program" id="id_program" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program?>">
							<input type="hidden" name="id_program_batch" id="id_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program_batch?>">
		              		<button type="reset" class="btn btn-danger">Reset</button>
							<button type="button" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
					</div>
				</div>	
			</div>	
			<!--add trainer-->
			<div class="modal fade" id="add_trainer_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-batch_trainer">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Set Trainer Tahap Batch Program</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
		                		<label for="nama_program">Nama Program</label>
		                		<input type="text" class="form-control" name="nama_program" id="nama_program" value="<?php if (isset($program_batch))echo $program_batch[0]->nama_program?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="kode_program_batch">Kode Batch Program</label>
		                		<input type="text" class="form-control" name="kode_program_batch" id="kode_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->kode?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama_batch">Nama Batch Program</label>
		                		<input type="text" class="form-control" name="nama_batch" id="nama_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->nama?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="tanggal_awal_program_batch">Tanggal Batch Program Awal</label>
		                		<input type="text" class="form-control" name="tanggal_awal_program_batch" id="tanggal_awal_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->tanggal_awal?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="tanggal_akhir_program_batch">Tanggal Batch Program Akhir</label>
		                		<input type="text" class="form-control" name="tanggal_akhir_program_batch" id="tanggal_akhir_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->tanggal_akhir?>" readonly>
							</div>
		              		<div class="form-group">
		                		<label for="batch_trainer">Trainer</label>
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAlltrainer"> Select All</label>
								</div>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="batch_trainer[]" id="batch_trainer" data-placeholder="Silahkan pilih Trainer" required="required">
		                			<?php
		                				foreach($topik_trainer as $dt){
		                					echo "<option value='".$dt->id_topik_trainer."'>".$dt->nama."-".$dt->caption."-".$dt->nama_topik."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="peserta_batch" id="peserta_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->peserta?>">
							<input type="hidden" name="peserta_tambahan_batch" id="peserta_tambahan_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->peserta_tambahan?>">
							<input type="hidden" name="id_batch">
		             		<input type="hidden" name="id_program" id="id_program" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program?>">
							<input type="hidden" name="id_program_batch" id="id_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program_batch?>">
							<input type="hidden" name="ttd_kiri" id="ttd_kiri" value="<?php if (isset($program_batch))echo $program_batch[0]->ttd_kiri?>">
							<input type="hidden" name="ttd_kanan" id="ttd_kanan" value="<?php if (isset($program_batch))echo $program_batch[0]->ttd_kanan?>">
							<input type="hidden" name="ck_ttd_kiri" id="ck_ttd_kiri" value="<?php if (isset($program_batch))echo $program_batch[0]->ck_ttd_kiri?>">
							<input type="hidden" name="ck_ttd_kanan" id="ck_ttd_kanan" value="<?php if (isset($program_batch))echo $program_batch[0]->ck_ttd_kanan?>">
							<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn_trainer" class="btn btn-success btn-success_trainer">Submit</button>
						</div>
		          	</form>
					</div>
				</div>	
			</div>	
			<!--add persen grade-->
			<div class="modal fade" id="add_persen_grade_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-batch_persen_grade">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Set Persen Grade Tahap Batch Program</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
		                		<label for="nama_program">Nama Program</label>
		                		<input type="text" class="form-control" name="nama_program" id="nama_program" value="<?php if (isset($program_batch))echo $program_batch[0]->nama_program?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="kode_program_batch">Kode Batch Program</label>
		                		<input type="text" class="form-control" name="kode_program_batch" id="kode_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->kode?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama_batch">Nama Batch Program</label>
		                		<input type="text" class="form-control" name="nama_batch" id="nama_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->nama?>" readonly>
							</div>
							<?php
							$no = 0;	
							foreach($nilai as $n){
								$no++;
								echo'
									<div class="form-group">		
										<label for="bobot_'.$n->id_nilai.'">'.$n->nama.'</label>
										<input type="number" class="form-control cek_min_max sum_min_max" name="bobot_'.$n->id_nilai.'" id="bobot_'.$n->id_nilai.'">
									</div>
								';
							}
							?>	
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_batch">
							<input type="hidden" name="id_program" id="id_program" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program?>">
							<input type="hidden" name="id_program_batch" id="id_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program_batch?>">
							<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn_persen_grade" class="btn btn-success btn-success_trainer">Submit</button>
						</div>
		          	</form>
					</div>
				</div>	
			</div>	
			<!--add generate soal-->
			<div class="modal fade" id="add_generate_soal_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-batch_generate_soal">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Generate Soal</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
								<div class="callout callout-danger">
									<h4>Peringatan!</h4>
									<p>Proses Generate Soal akan lock semua setting di Tahap.</p>
								</div>							
							</div>
							<div class="form-group">		
		                		<label for="nama_program">Nama Program</label>
		                		<input type="text" class="form-control" name="nama_program" id="nama_program" value="<?php if (isset($program_batch))echo $program_batch[0]->nama_program?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="kode_program_batch">Kode Batch Program</label>
		                		<input type="text" class="form-control" name="kode_program_batch" id="kode_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->kode?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama_batch">Nama Batch Program</label>
		                		<input type="text" class="form-control" name="nama_batch" id="nama_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->nama?>" readonly>
							</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_bpo">
							<input type="hidden" name="id_batch">
							<input type="hidden" name="peserta">
							<input type="hidden" name="peserta_tambahan">
							<input type="hidden" name="jumlah_soal">
							<input type="hidden" name="topik">
		              		<button type="button" name="action_btn_generate_soal" class="btn btn-success btn-success_generate_soal">Proses Generate Soal</button>
						</div>
		          	</form>
					</div>
				</div>	
			</div>	
			
			<!--add persen grade-->
			<div class="modal fade" id="add_grade_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-batch_grade">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Set Grade Tahap Batch Program</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
		                		<label for="nama_program">Nama Program</label>
		                		<input type="text" class="form-control" name="nama_program" id="nama_program" value="<?php if (isset($program_batch))echo $program_batch[0]->nama_program?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="kode_program_batch">Kode Batch Program</label>
		                		<input type="text" class="form-control" name="kode_program_batch" id="kode_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->kode?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama_batch">Nama Batch Program</label>
		                		<input type="text" class="form-control" name="nama_batch" id="nama_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->nama?>" readonly>
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
		             		<input type="hidden" name="id_batch">
							<input type="hidden" name="id_program" id="id_program" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program?>"> 
							<input type="hidden" name="id_program_batch" id="id_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program_batch?>">
		              		<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn_grade" class="btn btn-success btn-success_trainer">Submit</button>
							
						</div>
		          	</form>
					</div>
				</div>	
			</div>	
			<!--add jumlah soal-->
			<div class="modal fade" id="add_jumlah_soal_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<form role="form" class="form-transaksi-batch_jumlah_soal">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">Set Jumlah Soal Tahap Batch Program</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">		
									<label for="nama_program">Nama Program</label>
									<input type="text" class="form-control" name="nama_program" id="nama_program" value="<?php if (isset($program_batch))echo $program_batch[0]->nama_program?>" readonly>
								</div>
								<div class="form-group">		
									<label for="kode_program_batch">Kode Batch Program</label>
									<input type="text" class="form-control" name="kode_program_batch" id="kode_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->kode?>" readonly>
								</div>
								<div class="form-group">		
									<label for="nama_batch">Nama Batch Program</label>
									<input type="text" class="form-control" name="nama_batch" id="nama_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->nama?>" readonly>
								</div>
								<?php 
								if (isset($soal_tipe)) {
									foreach ($soal_tipe as $n) {
										if(isset($batch) && count($batch) >0) {
											$output = '<div class="form-group">';
											$output .= '<label for="jumlah_soal_' . $n->id_soal_tipe . '">' . $n->nama . '</label>';
											$output .= '<input type="number" class="form-control cek_soal" data-topik=' . $generate->kirana_encrypt($batch[0]->topik) . ' data-id_batch=' . $generate->kirana_encrypt($batch[0]->id_batch) . ' data-id_soal_tipe=' . $generate->kirana_encrypt($n->id_soal_tipe) . ' name="jumlah_soal_' . $n->id_soal_tipe . '" id="jumlah_soal_' . $n->id_soal_tipe . '">';
											$output .= '</div>';
											echo $output;
										}
									}
								}
								?>	
							</div>
							<div class="box-footer">
								<input type="hidden" name="id_batch">
								<input type="hidden" name="id_program" id="id_program" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program?>">
								<input type="hidden" name="id_program_batch" id="id_program_batch" value="<?php if (isset($program_batch))echo $program_batch[0]->id_program_batch?>">
								<button type="reset" class="btn btn-danger">Reset</button>
								<button type="button" name="action_btn_jumlah_soal" class="btn btn-success btn-success_trainer">Submit</button>
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/batch.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.colVis.min.js"></script>




<style>
	.small-box .icon{
		top: -13px;
	}
	.box-header .btn{
		margin: 5px 5px 5px 5px !important;
	}

</style>