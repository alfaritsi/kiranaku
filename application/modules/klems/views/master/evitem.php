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
	            		<h3 class="box-title"><strong>Master <?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Kategori</th>
				              	<th>Kode</th>
								<th>Pertanyaan</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($evitem as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->nama_kategori."</td>";
				              		echo "<td>".$dt->kode."</td>";
				              		echo "<td>".$dt->pertanyaan."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_feedback_pertanyaan)."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_feedback_pertanyaan)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-evitem' data-activate='".$generate->kirana_encrypt($dt->id_feedback_pertanyaan)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Master Item Kuesioner</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Master Item Kuesioner</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-item">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="kode">Kode</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode" required="required" value="<?php echo str_pad($no, 4, 0, STR_PAD_LEFT);?>" readonly>
		                	</div>
							<div class="form-group">		
								<label for="id_feedback_kategori">Kategori</label>
								<select class="form-control select2" name="id_feedback_kategori" id="id_feedback_kategori"  required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Kategori-</option>";
										foreach($kategori as $dt){
											echo"<option value='".$dt->id_feedback_kategori."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
		              		<div class="form-group">
		                		<label for="pertanyaan">Pertanyaan</label>
								<textarea class="form-control" name="pertanyaan" id="pertanyaan" placeholder="Masukkkan Pertanyaan" required="required"></textarea>
		                	</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_feedback_pertanyaan">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/evitem.js"></script>
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