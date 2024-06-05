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
				                	<label> Regional : </label>
				                	<select class="form-control select2" multiple="multiple" id="regional" name="regional[]" style="width: 100%;" data-placeholder="Silahkan Pilih Regional">
				                  		<?php
					                		foreach($regional as $dt){
					                			echo "<option value='".$dt->region_name."'";
					                			echo ">".$dt->region_name."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> NIK/ Nama : </label>
				                	<select class="form-control select2" multiple="multiple" id="nik" name="nik[]" style="width: 100%;" data-placeholder="Silahkan Pilih NIK/ Nama">
				                  		<?php
					                		foreach($peserta as $dt){
					                			echo "<option value='".$dt->nik."'";
					                			echo ">".$dt->nama_karyawan." [".$dt->nik."]</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Posisi : </label>
				                	<select class="form-control select2" multiple="multiple" id="posisi" name="posisi[]" style="width: 100%;" data-placeholder="Silahkan Pilih Posisi">
				                  		<?php
					                		foreach($posisi as $dt){
					                			echo "<option value='".$dt->nama."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Nama Program : </label>
				                	<select class="form-control select2" multiple="multiple" id="program" name="program[]" style="width: 100%;" data-placeholder="Silahkan Pilih Program">
				                  		<?php
					                		foreach($program as $dt){
					                			echo "<option value='".$dt->id_program."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik : </label>
				                	<select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Silahkan Pilih Pabrik">
				                  		<?php
					                		foreach($pabrik as $dt){
					                			echo "<option value='".$dt->plant."'";
					                			echo ">".$dt->plant."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Tahun : </label>
				                	<select class="form-control select2" multiple="multiple" id="tahun" name="tahun[]" style="width: 100%;" data-placeholder="Silahkan Pilih Tahun">
				                  		<?php
					                		foreach($tahun as $dt){
					                			echo "<option value='".$dt->tahun."'";
					                			echo ">".$dt->tahun."</option>";
					                		}
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
									<th>NIK</th>
									<th>Nama</th>
									<th>Pabrik</th>
									<th>Kode Batch Program</th>
									<th>Nama Batch Program</th>
									<th>Nomor Sertifikat</th>
									<th>Approval Signature 1</th>
									<th>Approval Signature 2</th>
									<th>Status Print</th>
									<th>Action</th>
								</tr>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($peserta as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->nik."</td>";
									echo "<td>".$dt->nama_karyawan."</td>";
									echo "<td>".$dt->gsber."</td>";
				              		echo "<td>".$dt->kode_program_batch."</td>";
				              		echo "<td>".$dt->nama_program_batch."</td>";
									echo "<td>".$dt->nomor_sertifikat."</td>";
									echo "<td align='center'>".$dt->status1."<br>".$dt->nama_ttd_kiri."</td>";
									echo "<td align='center'>".$dt->status2."<br>".$dt->nama_ttd_kanan."</td>";
									echo "<td>".$dt->status_print."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if(($dt->status_kiri == 1)and($dt->ttd_kiri==base64_decode($this->session->userdata("-id_karyawan-")))){
													echo "<li><a href='#' onclick=\"cancel_approve('".$dt->nik."','".$dt->id_program_batch."','kiri')\"><i class='fa fa-times-circle'></i> Cancel Approve</a></li>";	
												}
												if((($dt->status_kiri == null)or($dt->status_kiri ==0))and($dt->ttd_kiri==base64_decode($this->session->userdata("-id_karyawan-")))){
													echo "<li><a href='#' onclick=\"set_approve('".$dt->nik."','".$dt->id_program_batch."','kiri')\"><i class='fa fa-thumbs-o-up'></i> Set Approve</a></li>";	
												}
												if(($dt->status_kanan == 1)and($dt->ttd_kanan==base64_decode($this->session->userdata("-id_karyawan-")))){
													echo "<li><a href='#' onclick=\"cancel_approve('".$dt->nik."','".$dt->id_program_batch."','kanan')\"><i class='fa fa-times-circle'></i> Cancel Approve </a></li>";	
												}
												if((($dt->status_kanan == null)or($dt->status_kanan ==0))and($dt->ttd_kanan==base64_decode($this->session->userdata("-id_karyawan-")))){
													echo "<li><a href='#' onclick=\"set_approve('".$dt->nik."','".$dt->id_program_batch."','kanan')\"><i class='fa fa-thumbs-o-up'></i> Set Approve</a></li>";	
												}
												if(($dt->status_kiri == 1)and($dt->status_kanan == 1)){
													if(base64_decode($this->session->userdata("-id_karyawan-"))!=$dt->ttd_kiri){
														echo "<li><a href='".base_url()."klems/transaksi/cetak/".$dt->nik."/".$dt->id_program_batch."/"."'><i class='fa fa-print'></i> Cetak Sertifikat</a></li>";		
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
			<!--modal-->
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Detail</h4>
						</div>
	            		<div class="modal-body">
							<table class="table table-bordered">
								<thead>
									<th>No</th>
									<th>Tahap</th>
									<th>Nilai</th>
									<th>Grade</th>
								</thead>
								<tbody>
									<?php
									$no = 1;
									// foreach($program_batch as $dt){
										// $no++;
										echo "<tr>";
										echo "<td>".$no."</td>";
										echo "<td>tahap</td>";
										echo "<td>nilai</td>";
										echo "<td>grade</td>";
										echo "</tr>";
									// }
									?>
								</tbody>
							</table>
		            	</div>
					</div>
				</div>	
			</div>			
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/approval.js"></script>
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