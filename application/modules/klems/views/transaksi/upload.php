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
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Nama Program : </label>
				                	<select class="form-control select2" multiple="multiple" id="program" name="program[]" style="width: 100%;" data-placeholder="Silahkan Program">
				                  		<?php
					                		foreach($program as $dt){
					                			echo "<option value='".$dt->id_program."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			            	<div class="col-sm-7">
			            		<div class="form-group">
				                	<div class="col-sm-3">
				                	<label>Periode: </label>
				                	<input class="form-control tanggal" id="awal" name="awal" style="width: 100%;"/>
				                	</div>
				                	<div class="col-sm-1">	
				                		<label>&nbsp;</label>
				                	<label class="form-control no-border" style="width: 5%;" > To </label>
				            		</div>
									<div class="col-sm-3">
									<label>&nbsp;</label>	
				            		<input class="form-control tanggal" id="akhir" name="akhir" style="width: 100%;"/>
				            		</div>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Nama Program</th>
				              	<th>Batch Program</th>
								<th>Tahap</th>
								<th>Tanggal Test</th>
								<th>Lokasi</th>
								<th>Online</th>
								<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($batch as $dt){
									if($dt->online=='y'){
										$tanggal = date_format(date_create($dt->tanggal),"d-m-Y");
										$jam = $dt->jam_awal." - ".$dt->jam_akhir;
									}else{
										$tanggal = "-";
										$jam = "-";
									}
									echo "<tr>";
				              		echo "<td>".$dt->nama_program."</td>";
				              		echo "<td>".$dt->kode_program_batch."<br>".$dt->nama_program_batch."<br>".$dt->tanggal_awal_program_batch." sd ".$dt->tanggal_akhir_program_batch."</td>";
									echo "<td>".$dt->nama_tahap."<br>".$dt->tanggal_awal_batch." sd ".$dt->tanggal_akhir_batch."</td>";
									echo "<td>".$dt->tanggal_test."<br>".$jam."</td>";
									echo "<td>".$dt->tempat."</td>";
									echo "<td>".$dt->label_online."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->list_trainer!=""){
													$list_trainer = explode(",", substr($dt->list_trainer,0,-1));
													foreach ($list_trainer as $lt) {
														$ex_trainer = explode("|", $lt);
														echo "<li><a href='#' class='upload' data-batch='".$dt->id_batch."' data-trainer='".$ex_trainer[2]."' data-jenis='sesi'><i class='fa fa-file-text'></i> Upload Feedback Sesi (".$ex_trainer[0].")</a></li>";		
													}
												}
									echo "												
												<li><a href='#' class='upload' data-batch='".$dt->id_batch."' data-trainer='0' data-jenis='program'><i class='fa fa-file-text'></i> Upload Feedback  Program</a></li>	
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
			
			<!--modal-->	
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
		             		<input type="text" name="id_trainer">
							<input type="text" name="jenis">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/upload.js"></script>
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