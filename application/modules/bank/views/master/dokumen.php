<!--
/*
@application  : BANK SPECIMEN
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
				              	<th>Nama Dokumen</th>
				              	<th>Jenis Pengajuan</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($dokumen as $dt){
									echo "<tr>";
				              		echo "<td>".strtoupper($dt->nama)."</td>";
									echo "<td>";
									if(!empty($dt->jenis_pengajuan)){
										$arr_jenis_pengajuan = explode(",", $dt->jenis_pengajuan);
										foreach ($arr_jenis_pengajuan as $l) {
											if($l!=''){
												echo "<button class='btn btn-sm btn-info btn-role'>".ucfirst($l)."</button>";
											}
										}
									}
									echo "<td width='15%'>".$dt->label_active."</td>";
				              		echo "<td width='15%'>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
												echo"<ul class='dropdown-menu pull-right'>";
													if($dt->na == 'n'){ 
														echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_dokumen."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
														echo "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_dokumen."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
													}
													if($dt->na == 'y'){
														echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_dokumen."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Form Dokumen Bank Specimen</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Dokumen</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-dokumen-bank_specimen">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="nama">Nama Dokumen</label>
									<input type="text" class="form-control" name="nama" id="nama" placeholder="Input Nama Role" required="required">
								</div>
								<div class="form-group">
									<label> Jenis Pengajuan: </label>
									<select class="form-control select2" multiple="multiple" id="jenis_pengajuan" name="jenis_pengajuan[]" style="width: 100%;" data-placeholder="Pilih Jenis Pengajuan"  required="required">
										<?php
											echo "<option value='pembukaan'>Pembukaan</option>";
											echo "<option value='penutupan'>Penutupan</option>";
											echo "<option value='perubahan'>Perubahan</option>";
										?>
									</select>
								</div>
								
							</div><!-- /.col -->
							
						</div>
					</div>
					<div class="modal-footer">
						<input id="id_dokumen" name="id_dokumen" type="hidden">
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
<script src="<?php echo base_url() ?>assets/apps/js/bank/master/dokumen.js"></script>
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