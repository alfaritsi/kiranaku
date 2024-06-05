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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
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
				              	<th>Jenis Program</th>
				              	<th>Kode Program</th>
				              	<th>Nama Program</th>
								<th>Periode</th>
								<th>Level</th>
								<th>Organisasi Level</th>
								<th>Posisi</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($program_matrix as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->jenis_program."</td>";
									echo "<td>".$dt->kode_program."</td>";
				              		echo "<td>".$dt->nama_program."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_awal),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir),"d-m-Y")."</td>";
									echo "<td>";
										$level_list = explode(",", substr($dt->level_list,0,-1));
										foreach ($level_list as $l) {
											echo "<button class='btn btn-sm btn-info btn-role'>".$l."</button>";
										}
									echo "</td>";
									echo "<td>";
										$organisasi_level_list = explode(",", substr($dt->organisasi_level_list,0,-1));
										foreach ($organisasi_level_list as $ol) {
											echo "<button class='btn btn-sm btn-info btn-role'>".$ol."</button>";
										}
									echo "</td>";
									echo "<td>";
										$posisi_list = explode(",", substr($dt->posisi_list,0,-1));
										foreach ($posisi_list as $p) {
											echo "<button class='btn btn-sm btn-info btn-role'>".$p."</button>";
										}
									echo "</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_program_matrix)."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_program_matrix)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-program_matrix' data-activate='".$generate->kirana_encrypt($dt->id_program_matrix)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Setting Program Matrix</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Program Matrix</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-program_matrix">
	            		<div class="box-body">
							<div class="form-group">		
		                		<label for="kode_program">Kode Program</label>
		                		<input type="text" class="form-control" name="kode_program" id="kode_program" value="<?php if (isset($program))echo $program[0]->kode?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama_program">Nama Program</label>
		                		<input type="text" class="form-control" name="nama_program" id="nama_program" value="<?php if (isset($program))echo $program[0]->nama?>" readonly>
							</div>
							<div class="form-group">
								<label for="tanggal_awal">Tanggal Awal</label>
		                		<input type="text" class="form-control tanggal" name="tanggal_awal" id="tanggal_awal" placeholder="Masukkkan Tanggal Awal" required>
							</div>
							<div class="form-group">
								<label for="tanggal_akhir">Tanggal Akhir</label>
								<input type="text" class="form-control tanggal" name="tanggal_akhir" id="tanggal_akhir" placeholder="Masukkkan Tanggal Akhir" required>
							</div>
		              		<div class="form-group">
		                		<label for="role">Level</label>
								<!--
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAllLevel"> Select All</label>
								</div>
								-->
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="level[]" id="level" data-placeholder="Silahkan pilih Level" required>
		                			<?php
		                				foreach($level as $dt){
		                					echo "<option value='".$dt->id_jabatan."'>".$dt->nama."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
		              		<div class="form-group">
		                		<label for="role">Organisasi Level</label>
								<!--
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAllOrganisasi"> Select All</label>
								</div>
								-->
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="organisasi_level[]" id="organisasi_level" data-placeholder="Silahkan pilih Organisasi Level" required>
		                			<?php
		                				foreach($organisasi_level as $dt){
		                					echo "<option value='".$dt->id_level."'>".$dt->nama."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
							<div class="form-group">
								<label for="posisi">Posisi</label>
								<div id='show_posisi'> 
								<select id="posisi" name="posisi[]" multiple class="form-control col-sm-12">
									<?php
									foreach($posisi as $dt){
										echo "<option value='".$dt->id_posisi."'>".$dt->nama."</option>";
									}
									?>
								</select>
								</div>
							</div>
							
							<!--
							<div class="form-group">		
								<label for="posisi">Posisi</label>
								<select class="form-control select2" multiple="multiple" name="posisi[]" id="posisi"  required="required">
								</select>
							</div>
							-->
							<!--		
		              		<div class="form-group">
		                		<label for="role">Posisi</label>
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAllPosisi"> Select All</label>
								</div>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="posisi[]" id="posisi" data-placeholder="Silahkan pilih Posisi" required>
		                			<?php
		                				foreach($posisi as $dt){
		                					echo "<option value='".$dt->id_posisi."'>".$dt->nama."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
							-->
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_program_matrix">
							<input type="hidden" name="id_program" value="<?php if (isset($program))echo $program[0]->id_program?>">
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
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/programmatrix.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.custom.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.theme.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/prettify.css"/>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/prettify.js"></script>




<style>
.small-box .icon{
    top: -13px;
}
</style>