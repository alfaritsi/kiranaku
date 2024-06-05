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
				              	<th>Institusi</th>
				              	<th>Kode Trainer Eksternal</th>
								<th>Nama Trainer Eksternal</th>
								<th>Telepon Trainer Eksternal</th>
								<th>Email Trainer Eksternal</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($trainer as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->institusi."</td>";
									echo "<td>".$dt->kode."</td>";
				              		echo "<td>".$dt->nama."</td>";
									echo "<td>".$dt->telepon."</td>";
									echo "<td>".$dt->email."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_trainer)."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_trainer)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-trainer' data-activate='".$generate->kirana_encrypt($dt->id_trainer)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Master Trainer</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Master Trainer Baru</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-trainer">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="kode">Kode Trainer</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode Trainer" required="required" value="<?php echo str_pad($no, 4, 0, STR_PAD_LEFT);?>" readonly>
		                	</div>
							<div class="form-group">
								<label for="nama">Nama Trainer</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkkan Nama Trainer" required="required">
							<div class="form-group">
								<label for="institusi">Institusi</label>
								<select class="form-control select2" name="id_institusi" id="id_institusi" required="required">
									<?php
										echo "<option value='0'>Silahkan Pilih Institusi</option>";
										foreach($institusi as $dt){
											echo"<option value='".$dt->id_institusi."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
		                		<label for="nama">Telepon Trainer</label>
		                		<input type="number" class="form-control" name="telepon" id="telepon" placeholder="Masukkkan Telepon Trainer" required="required">
		                	</div>
							<div class="form-group">
								<label for="nama">Email Trainer</label>
		                		<input type="text" class="form-control" name="email" id="email" placeholder="Masukkkan Email Trainer" required="required">
		             		</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_trainer">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/trainer.js"></script>
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