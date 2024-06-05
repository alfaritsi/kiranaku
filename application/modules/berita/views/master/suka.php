<!--
/*
@application  : KODE MATERIAL
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-8">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
						<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>No</th>
				              	<th>NIK</th>
				              	<th>Nama</th>
				              	<th>Tanggal</th>
				              	<th>Tanggal Dibuat</th>
				              	<th>Dibuat Oleh</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 0;
				              	foreach($berita as $dt){
									$no++;
									echo "<tr>";
				              		echo "<td>".$no."</td>";
				              		echo "<td>".$dt->nik."</td>";
				              		echo "<td>".$dt->nama_karyawan."</td>";
				              		echo "<td>".$dt->tanggal_convert."</td>";
									echo "<td>".$dt->tanggal_buat_konversi."</td>";
				              		echo "<td>".$dt->nik_buat."-".$dt->nama_buat."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
												echo"<ul class='dropdown-menu pull-right'>";	
												if($dt->na == 'n'){ 
													echo "<li><a href='javascript:void(0)' class='sent' data-edit='".$dt->id_notif_berita."' data-to='".$dt->email_buat."'><i class='fa fa-envelope-o'></i> Cek Email</a></li>";
													echo "<li><a href='javascript:void(0)' class='status' data-edit='".$dt->id_notif_berita."' data-nik_duka='".$dt->nik."'><i class='fa fa-users'></i> Penerima Email</a></li>";
													echo "<li><a href='javascript:void(0)' class='sent' data-edit='".$dt->id_notif_berita."' data-to='all' data-nik_duka='".$dt->nik."'><i class='fa fa-envelope'></i> Blast Email</a></li>";
													echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_notif_berita."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
													echo "<li><a href='javascript:void(0)' class='delete' data-delete='".$dt->id_notif_berita."'><i class='fa fa-trash-o'></i> Delete</a></li>";
												}
												echo"</ul>";
									echo " 	
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
		              	<h3 class="box-title title-form">Form List Berita Suka Cita</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Berita Suka Cita</button>
		          	</div>
		          	<form role="form" class="form-master-berita_suka">
	            		<div class="box-body">
							<div class="form-group">
								<label for="gambar">Template(.PNG dengan dimensi 800x500 px.)</label>
								<input type="file" multiple="multiple" class="form-control" id="gambar" name="gambar[]">
		             		</div>
		              		<div class="form-group">
		                		<label for="editorial1">Editorial</label>
								<textarea class="form-control" name="editorial1" id="editorial1" placeholder="Masukan Editorial" required="required"></textarea>
		                	</div>
		              		<div class="form-group">
		                		<label for="nama_anak">Nama Anak</label>
		                		<input type="text" class="form-control" name="nama_anak" id="nama_anak" placeholder="Input Nama Anak" required="required">
		                	</div>
		              		<div class="form-group">
		                		<label for="gender">Gender</label>
		                		<select class="form-control select2 col-sm-12" name="gender" id="gender" data-placeholder="Pilih Gender" required="required">
		                			<option value=''>Pilih Gender</option>
		                			<option value='Son'>Son</option>
		                			<option value='Daughter'>Daughter</option>
		                		</select>
		                	</div>
							<div class="form-group">
								<label for="tanggal">Tanggal</label>
		                		<input type="text" class="form-control" name="tanggal" id="tanggal" placeholder="Input Tanggal" required="required">
		                	</div>
							<div class="form-group">		
								<label for="nik">Nama & NIK</label>
								<select class="form-control select2" name="nik" id="nik"  required="required"></select>
							</div>
		            	</div>
		            	<div class="box-footer">
							<input id="id_notif_berita" name="id_notif_berita" type="hidden">
							<input id="gambar_url" name="gambar_url" type="hidden">
							<button type="reset" class="btn btn-danger btn_hide">Reset</button>
		              		<button type="button" name="action_btn" class="btn btn-success btn_hide">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>
		</div>
		<!--modal status-->
		<div class="modal fade" id="status_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="col-sm-12">
						<div class="modal-content">
							<form role="form" class="form-transaksi-input">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Detail Penerima Email</h4>
								</div>
								<div class="modal-body">
									<div id='show_status'></div>									
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>	
		</div>
		
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/berita/master/suka.js"></script>
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