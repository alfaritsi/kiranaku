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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order-width">
		              		<thead>
								<tr>
									<th>Nama Peserta</th>
									<th>Nik</th>
									<th>Divisi</th>
									<th>Action</th>
								</tr>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($peserta as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->nama."</td>";
				              		echo "<td>".$dt->id_karyawan."</td>";
				              		echo "<td>".$dt->nama_divisi."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>
												<li><a href='#' class='upload' data-batch='".$dt->id_batch."'  data-karyawan='".$dt->id_karyawan."' data-peserta='".$dt->id_peserta."' data-trainer='0'><i class='fa fa-file-text'></i> Upload Feedback </a></li>											
											</ul>
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
			
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-transaksi-upload_feedback" enctype="multipart/form-data">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Upload Feedback</h4>
						</div>
	            		<div class="modal-body">
			            	<div class="form-group">
			                  	<label for="excelData">File input</label>
			                  	<input type="file" id="excelData" name="excelData">
			                  	<p class="help-block">*pastikan anda mengunggah file excel sesuai template yang disediakan</p>
			                </div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="text" name="id_batch">
		             		<input type="text" name="id_karyawan">
		             		<input type="text" name="id_peserta">
		             		<input type="text" name="id_trainer">
		              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
						
					</div>
				</div>	
			</div>			
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/upload_batch.js"></script>
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