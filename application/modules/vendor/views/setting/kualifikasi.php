<!--
/*
@application  : KODE VENDOR
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
				              	<th>Kualifikasi</th>
				              	<th>Dokumen</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($kualifikasi as $dt){
									$no++;
									echo "<tr>";
				              		echo "<td>".$dt->kualifikasi_spk."</td>";
									echo "<td>";
										if(!empty($dt->list_nama_master_dokumen)){
											$view_dok = "";
											$list_nama_master_dokumen = explode(",", substr($dt->list_nama_master_dokumen, 0, -1));
											foreach ($list_nama_master_dokumen as $l) {
												$view_dok .= "<button class='btn btn-sm btn-info btn-role'>".$l."</button> | ";
											}
											echo  substr($view_dok, 0, -2);
										}
									echo "</td>";
									echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
												echo"<ul class='dropdown-menu pull-right'>";
													if($dt->na == 'n'){ 
														echo "<li><a href='javascript:void(0)' class='dokumen' data-edit='".$dt->id_kualifikasi_spk."'><i class='fa fa-files-o'></i> Set Dokumen</a></li>";
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
		              	<h3 class="box-title title-form">Form Setting Kualifikasi Dokumen</h3>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-setting-kualifikasi">
	            		<div class="box-body">
							<div class="form-group">
								<label for="nama">Kualifikasi</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Kualifikasi" required="required" readonly>
		                	</div>
		              		<div class="form-group">
		                		<label for="dokumen">Dokumen</label>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="dokumen[]" id="dokumen" data-placeholder="Pilih Dokumen" required="required">
		                			<?php
		                				foreach($master_dokumen as $dt){
		                					echo "<option value='".$dt->id_master_dokumen."'>".$dt->nama."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
		            	</div>
		            	<div class="box-footer">
							<input id="id_kualifikasi_spk" name="id_kualifikasi_spk" type="hidden">
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
<script src="<?php echo base_url() ?>assets/apps/js/vendor/setting/kualifikasi.js"></script>
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