<!--
/*
@application  : MENTORING
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-success" id="export_excel_rating">Export To Excel</button>
						</div>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-4">
			            		<div class="form-group">
				                	<label>Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_status" name="filter_status[]" style="width: 100%;" data-placeholder="Pilih Status">
				                  		<?php
					                		foreach($status as $dt){
												if($dt->id_status>=3 && $dt->id_status<=5)
					                			echo "<option value='".$dt->id_status."'>".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
						<table class="table table-bordered table-striped"
							   id="sspTable">
							<thead>
								<tr>
									<th>Nomor</th>
									<th>NIK Mentor</th>
									<th>Nama Mentor</th>
									<th>NIK Mentor<br>(Additional)</th>
									<th>Nama Mentor<br>(Additional)</th>
									<th>Tanggal Sesi</th>
									<th>Jenis Sesi</th>
									<th>NIK Mentee</th>
									<th>Nama Mentee</th>
									<th>Departemen Mentee</th>
									<th>Mentee Rate</th>
									<th>Comm Rate</th>
									<th>Over all</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
		</div>
	</section>
</div>



<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/mentor/laporan/rating.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>