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
				              	<th>Kode Topik</th>
				              	<th>Nama Topik</th>
								<th>Nama File</th>
								<th>Tipe File</th>
								<th>Ukuran File</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($topik_materi as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->kode_topik."</td>";
				              		echo "<td>".$dt->nama_topik."</td>";
				              		echo "<td>".$dt->nama."</td>";
				              		echo "<td>".$dt->tipe_files."</td>";
				              		echo "<td>".$dt->size_files."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_materi)."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_materi)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-topik_materi' data-activate='".$generate->kirana_encrypt($dt->id_materi)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Setting Materi Topik</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Materi Topik</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-topik_materi">
	            		<div class="box-body">
							<div class="form-group">	
								<label for="nama">Nama File</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkkan Nama Materi" required="required">
		                	</div>
							<div class="form-group">
								<label for="gambar">Files</label>
								<input type="file" multiple="multiple" class="form-control " id="gambar" name="gambar[]" required="required">
								Contoh : pdf, zip, mp4.
		             		</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_materi">
							<input type="hidden" name="id_topik" value="<?php if (isset($topik))echo $topik[0]->id_topik?>">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/topikmateri.js"></script>
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