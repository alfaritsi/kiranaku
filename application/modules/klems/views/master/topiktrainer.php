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
								<th>Nama Trainer</th>
								<th>Asal Trainer</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($topik_trainer as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->kode_topik."</td>";
				              		echo "<td>".$dt->nama_topik."</td>";
				              		echo "<td>".strtoupper($dt->nama_trainer)."</td>";
				              		echo "<td>".$dt->asal_trainer."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_topik_trainer)."'><i class='fa fa-pencil-square-o'></i> Edit Topik Trainer</a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_topik_trainer)."'><i class='fa fa-trash-o'></i> Hapus Topik Trainer</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-topik_trainer' data-activate='".$generate->kirana_encrypt($dt->id_topik_trainer)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Setting Topik Trainer</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Topik Trainer</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-topik_trainer">
	            		<div class="box-body">
							<div class="form-group">
								<div class="checkbox">
								<label>
								  <input type="checkbox" id="trainer" name="trainer" value="1" > Ceklist Jika Trainer Eksternal
								</label>
								</div>
							</div>
							<div class="form-group" id="show_trainer_internal" >		
								<label for="id_trainer_internal">Nama Trainer Internal</label>
								<select class="form-control select2" name="id_trainer_internal" id="id_trainer_internal">
									<?php
										echo "<option value='0'>-Silahkan Pilih Trainer-</option>";
										foreach($trainer_internal as $dt){
											echo"<option value='".$dt->nik."'>".$dt->nama." - ".$dt->nik." - ".$dt->nama_pabrik."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group" id="show_trainer_eksternal" style="display: none">		
								<label for="id_trainer_eksternal">Nama Trainer Eksternal</label>
								<select class="form-control select2" name="id_trainer_eksternal" id="id_trainer_eksternal">
									<?php
										echo "<option value='0'>-Silahkan Pilih Trainer-</option>";
										foreach($trainer_eksternal as $dt){
											echo"<option value='".$dt->id_trainer."'>".strtoupper($dt->nama)."</option>";
										}
									?>
								</select>
							</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_topik_trainer">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/topiktrainer.js"></script>
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