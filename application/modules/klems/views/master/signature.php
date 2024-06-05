<!--
/*
test ssh
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
				              	<th>NIK</th>
				              	<th>Nama</th>
								<th>Tanda Tangan</th>
								<th>Posisi</th>
								<th>Posisi Sertifikat</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($signature as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->nik."</td>";
				              		echo "<td>".$dt->nama."</td>";
				              		echo "<td><img src='".base_url()."/".$dt->gambar."' height='80'></td>";
									echo "<td>".$dt->posst."</td>";
									echo "<td>".$dt->posisi_sertifikat."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_tandatangan)."'><i class='fa fa-pencil-square-o'></i> Edit Tanda Tangan </a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_tandatangan)."'><i class='fa fa-trash-o'></i> Hapus Tanda Tangan</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-signature' data-activate='".$generate->kirana_encrypt($dt->id_tandatangan)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Setting Tanda Tangan</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Tanda Tangan</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-signature">
	            		<div class="box-body">
							<div class="form-group">		
								<label for="nik">Nama & NIK</label>
								<select class="form-control select2" name="nik" id="nik"  required="required"></select>
							</div>
							<div class="form-group">
								<label for="gambar">Gambar</label>
								<input type="file" multiple="multiple" class="form-control" id="gambar" name="gambar[]">
		             		</div>
							<div class="form-group">	
								<label for="posisi_sertifikat">Posisi Sertfikat</label>
		                		<input type="text" class="form-control" name="posisi_sertifikat" id="posisi_sertifikat" placeholder="Masukkkan Posisi Sertifikat" required="required">
		                	</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_tandatangan">
							<input type="hidden" name="gambar_url">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/signature.js"></script>
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