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
				              	<th>BPO</th>
								<th>Nama Program</th>
								<th>Kode Tahap</th>
								<th>Nama Tahap</th>
								<th>Topik</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($tahap as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->nama_bpo."</td>";
				              		echo "<td>".$dt->nama_program."</td>";
				              		echo "<td>".$dt->kode."</td>";
				              		echo "<td>".$dt->nama."</td>";
				              		// echo "<td>".$dt->topik."</td>";
									echo "<td>";
										$topik_list = explode(",", substr($dt->topik_list,0,-1));
										foreach ($topik_list as $t) {
											echo "<button class='btn btn-sm btn-info btn-role'>".$t."</button>";
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
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_tahap)."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_tahap)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-tahap' data-activate='".$generate->kirana_encrypt($dt->id_tahap)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Setting Tahap</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Tahap</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-tahap">
	            		<div class="box-body">
							<div class="form-group">		
								<label for="id_bpo">BPO</label>
								<select class="form-control select2" name="id_bpo" id="id_bpo"  required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih BPO-</option>";
										foreach($bpo as $dt){
											echo"<option value='".$dt->id_bpo."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">	
								<label for="id_program">Program</label>
								<select class="form-control select2" name="id_program" id="id_program" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Program-</option>";
										foreach($program as $dt){
											echo"<option value='".$dt->id_program."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">		
		                		<label for="kode">Kode Tahap</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode Program" required="required" value="<?php echo str_pad($no, 4, 0, STR_PAD_LEFT);?>" readonly>
							</div>
							<div class="form-group">	
								<label for="nama">Nama Tahap</label>
								<select class="form-control select2" name="nama" id="nama" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Nama Tahap-</option>";
										echo "<option value='ICT'>ICT</option>";
										echo "<option value='ICT1-Pre Test'>ICT1-Pre Test</option>";
										echo "<option value='ICT1-Post Test'>ICT1-Post Test</option>";
										echo "<option value='ICT2-Pre Test'>ICT2-Pre Test</option>";
										echo "<option value='ICT2-Post Test'>ICT2-Post Test</option>";
										echo "<option value='ICT3-Pre Test'>ICT3-Pre Test</option>";
										echo "<option value='ICT3-Post Test'>ICT3-Post Test</option>";
										echo "<option value='ICT4-Pre Test'>ICT4-Pre Test</option>";
										echo "<option value='ICT4-Post Test'>ICT4-Post Test</option>";
										echo "<option value='ICT5-Pre Test'>ICT5-Pre Test</option>";
										echo "<option value='ICT5-Post Test'>ICT5-Post Test</option>";
										echo "<option value='OJT1'>OJT1</option>";
										echo "<option value='OJT2'>OJT2</option>";
										echo "<option value='OJT Mid Review'>OJT Mid Review</option>";
										echo "<option value='OJT Final Review'>OJT Final Review</option>";
									?>
								</select>
							</div>
		              		<div class="form-group">
		                		<label for="topik">Topik</label>
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAllTopik"> Select All</label>
								</div>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="topik[]" id="topik" data-placeholder="Silahkan pilih Topik" required>
		                			<?php
		                				foreach($topik as $dt){
		                					echo "<option value='".$dt->id_topik."'>".$dt->nama."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
							
							<div class="form-group">		
		                		<label for="keterangan">Keterangan</label>
		                		<input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Masukkkan Keterangan" required="required">
							</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_tahap">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/tahap.js"></script>
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