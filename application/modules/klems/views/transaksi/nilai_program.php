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
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
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
													if($dt->list_batch!=""){
														$list_batch = explode(",", substr($dt->list_batch,0,-1));
														foreach ($list_batch as $lb) {
															$ex_lb = explode("|", $lb);
															echo"<li><a href='".base_url()."klems/transaksi/data/nilai_batch/".$ex_lb[0]."'><i class='fa fa-file-text'></i> Input Nilai (".$ex_lb[1].")</a></li>";	
														}
													}
													echo"
													  <li><a href='#' class='set_cancel' data-cancel='".$generate->kirana_encrypt($dt->id_program_batch)."'><i class='fa fa-minus-square'></i> Set Cancel </a></li>
													  <li><a href='#' class='set_done' data-done='".$generate->kirana_encrypt($dt->id_program_batch)."'><i class='fa fa-check-square'></i> Set Done </a></li>
													";
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
			
			<!--add set done-->
			<div class="modal fade" id="add_set_done" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-set_done">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Set Done</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
								<div class="callout callout-danger">
									<h4>Peringatan!</h4>
									<p>Proses Set Done akan lock semua Tahap terkait.</p>
								</div>							
							</div>
							<div class="form-group">		
		                		<label for="nama_program">Nama Program</label>
		                		<input type="text" class="form-control" name="nama_program" id="nama_program" readonly>
							</div>
							<div class="form-group">		
		                		<label for="kode_program_batch">Kode Batch Program</label>
		                		<input type="text" class="form-control" name="kode" id="kode" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama_batch">Nama Batch Program</label>
		                		<input type="text" class="form-control" name="nama" id="nama" readonly>
							</div>
		            	</div>
		            	<div class="box-footer">
							<input type="hidden" class="form-control" name="id_program_batch" id="id_program_batch">
							<input type="hidden" class="form-control" name="list_peserta" id="list_peserta" value="<?php if (isset($program_batch))echo $program_batch[0]->peserta?>">
							<input type="hidden" class="form-control" name="list_peserta_tambahan" id="list_peserta_tambahan" value="<?php if (isset($program_batch))echo $program_batch[0]->peserta_tambahan?>">
							<input type="hidden" class="form-control" name="tahun" id="tahun" value="<?php if (isset($program_batch))echo $program_batch[0]->tahun?>">
							<input type="hidden" class="form-control" name="bulan" id="bulan" value="<?php if (isset($program_batch))echo $program_batch[0]->bulan?>">
							<input type="hidden" class="form-control" name="jenis_sertifikat" id="jenis_sertifikat" value="<?php if (isset($program_batch))echo $program_batch[0]->jenis_sertifikat?>">
		              		<button type="button" name="action_btn_set_done" class="btn btn-success btn-success_generate_soal">Proses Set Close</button>
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/nilai_program.js"></script>
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