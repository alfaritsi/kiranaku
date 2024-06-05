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
<style type="text/css">
  .disabled.day {
    opacity: 0.90;
    filter: alpha(opacity=90);
    background-color: lightgrey !important;
    color: black !important;

  }
</style>

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
				              	<th>Periode</th>
								<th>Index</th>
								<th>Nama Index</th>
								<th>Targeted Index</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($evindex as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".date_format(date_create($dt->tanggal_awal),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir),"d-m-Y")."</td>";
				              		echo "<td>".$dt->kode."</td>";
				              		echo "<td>".$dt->nama."</td>";
									echo "<td>".$dt->nilai."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_feedback_index)."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_feedback_index)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-evindex' data-activate='".$generate->kirana_encrypt($dt->id_feedback_index)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
		              	<h3 class="box-title title-form">Buat Setting Targeted Index</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Targeted Index</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-evindex">
	            		<div class="box-body">
							<div class="form-group">
								<label for="tanggal_awal">Tanggal Awal</label>
		                		<input type="text" class="form-control" name="tanggal_awal" id="tanggal_awal" placeholder="Masukkkan Tanggal Awal" required="required">
							</div>
							<div class="form-group">
								<label for="tanggal_akhir">Tanggal Akhir</label>
								<input type="text" class="form-control" name="tanggal_akhir" id="tanggal_akhir" placeholder="Masukkkan Tanggal Akhir" required="required">
							</div>
		              		<div class="form-group">
		                		<label for="kode">Index</label>
		                		<input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkkan Kode" required="required">
		                	</div>
							<div class="form-group">
								<label for="nama">Nama Index</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Masukkkan Nama" required="required">
		                	</div>
							<div class="form-group">
								<label for="nilai">Targeted Index</label>
		                		<input type="number" class="form-control" name="nilai" id="nilai" placeholder="Masukkkan Targeted Index" required="required">
		                	</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_feedback_index">
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/evindex.js"></script>
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