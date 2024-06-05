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
				              	<th>Kode Soal</th>
				              	<th>Nama Soal</th>
				              	<th>Tipe Soal</th>
								<th>BPO</th>
								<th>Nama Topik</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($soal as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->kode."</td>";
									echo "<td>";
										echo "<i class='fa fa-question-circle'></i> ".$dt->soal;
										if($dt->gambar!=null){
											echo "<br><img src='".$dt->gambar."' width='200px'>";	
										}
										$list_jawaban = explode(";", substr($dt->jawaban,0,-1));
										$n = 0;
										foreach ($list_jawaban as $t) {
											$n++;
											if($n==1){
												echo "<br><i class='fa fa-circle'></i> ".$t;	
											}else{
												echo "<br><i class='fa fa-circle-o'></i> ".$t;
											}
										}
									echo "</td>";
				              		echo "<td>".$dt->tipe_soal."</td>";									
				              		echo "<td>".$dt->nama_bpo."</td>";
				              		echo "<td>".$dt->nama_topik."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_soal)."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_soal)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-soal' data-activate='".$generate->kirana_encrypt($dt->id_soal)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Setting Soal</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Soal</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-soal">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="kode">Kode</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode" required="required" value="<?php echo str_pad($no, 4, 0, STR_PAD_LEFT);?>" readonly>
		                	</div>
							<div class="form-group">		
								<label for="id_bpo">BPO</label>
								<select class="form-control select2" name="id_bpo" id="id_bpo" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih BPO-</option>";
										foreach($bpo as $dt){
											echo"<option value='".$dt->id_bpo."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">		
								<label for="id_topik">Topik</label>
								<select class="form-control select2" name="id_topik" id="id_topik"  required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Topik-</option>";
										foreach($topik as $dt){
											echo"<option value='".$dt->id_topik."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">		
								<label for="id_soal_tipe">Tipe Soal</label>
								<select class="form-control select2" name="id_soal_tipe" id="id_soal_tipe"  required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Tipe Soal-</option>";
										foreach($soal_tipe as $dt){
											echo"<option value='".$dt->id_soal_tipe."'>".$dt->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="gambar">Gambar</label>
								<input type="file" multiple="multiple" class="form-control " id="gambar" name="gambar[]">
		             		</div>
							<div class="form-group">	
								<label for="soal">Nama Soal</label>
		                		<input type="text" class="form-control" name="soal" id="soal" placeholder="Masukkkan Soal" required="required">
		                	</div>
							<div class="form-group">	
								<label for="jawaban1">Jawaban 1 (Jawaban Yang benar)</label>
		                		<input type="text" class="form-control" name="jawaban1" id="jawaban1" placeholder="Masukkkan Jawaban 1" required="required">
		                	</div>
							<div class="form-group">	
								<label for="jawaban2">Jawaban 2</label>
		                		<input type="text" class="form-control" name="jawaban2" id="jawaban2" placeholder="Masukkkan Jawaban 2" required="required">
		                	</div>
							<div class="form-group">	
								<label for="jawaban3">Jawaban 3</label>
		                		<input type="text" class="form-control" name="jawaban3" id="jawaban3" placeholder="Masukkkan Jawaban 3" required="required">
		                	</div>
							<div class="form-group">	
								<label for="jawaban4">Jawaban 4</label>
		                		<input type="text" class="form-control" name="jawaban4" id="jawaban4" placeholder="Masukkkan Jawaban 4" required="required">
		                	</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_soal">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/soal.js"></script>
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