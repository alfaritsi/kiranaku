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
				              	<th>Kode Topik</th>
								<th>Nama Topik</th>
								<th>Abbreviation</th>
								<th>Minimal Soal</th>
								<th>Trainner Topik</th>
								<th>Attachment</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($topik as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->nama_bpo."</td>";
				              		echo "<td>".$dt->kode."</td>";
				              		echo "<td>".$dt->nama."</td>";
				              		echo "<td>".$dt->abbreviation."</td>";
				              		echo "<td>".$dt->minimal_soal."</td>";
									echo "<td>";
										$list_trainer = explode(",", substr($dt->list_trainer,0,-1));
										foreach ($list_trainer as $t) {
											$ex_t = explode("|", $t);
											if(!empty($t)){
												echo "<button class='btn btn-sm btn-info btn-role'>".$ex_t[0]."(".$ex_t[1].")</button>";	
											}
										}
									echo "</td>";
									echo "<td>";
										$list_materi = explode(",", substr($dt->list_materi,0,-1));
										foreach ($list_materi as $m) {
											$ex_m = explode("|", $m);
											if(!empty($m)){
												echo "<button class='btn btn-sm btn-info btn-role'>".$ex_m[0].".".$ex_m[1]."</button>";
											}	
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
													  <li><a href='".base_url()."klems/master/data/trainer/".$dt->kode."'><i class='fa fa-group'></i>Setting Trainer untuk Topik </a></li>
													  <li><a href='".base_url()."klems/master/data/materi/".$dt->kode."'><i class='fa fa-paperclip'></i> Detail Attachment </a></li>
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_topik)."'><i class='fa fa-pencil-square-o'></i> Edit Topik </a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_topik)."'><i class='fa fa-trash-o'></i> Hapus Topik</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-topik' data-activate='".$generate->kirana_encrypt($dt->id_topik)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Setting Topik</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Topik</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-topik">
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
		                		<label for="kode">Kode</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode" required="required" value="<?php echo str_pad($no, 4, 0, STR_PAD_LEFT);?>" readonly>
		                	</div>
		              		<div class="form-group">
		                		<label for="pertanyaan">Nama Topik</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkkan Nama Topik"  required="required">
		                	</div>
							<div class="form-group">
								<label for="abbreviation">Abbreviation</label>
		                		<input type="text" class="form-control" data-currency="no" maxlength='4' name="abbreviation" id="abbreviation" placeholder="Masukkan Abbreviation" required="required">
							</div>
							<div class="form-group">
								<label for="minimal_soal">Minimal Soal</label>
		                		<input type="number" min="0" class="form-control" name="minimal_soal" id="minimal_soal" placeholder="Masukkan Minimal Soal" required="required">
							</div>
							<div class="form-group">
								<label for="tujuan">Tujuan</label>
		                		<input type="text" class="form-control" name="tujuan" id="tujuan" placeholder="Masukkkan Tujuan" required="required">
		             		</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_topik">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/topik.js"></script>
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