<!--
/*
	@application  : Monitoring CCTV 
		@author       : Airiza Yuddha (7849)
		@contributor  :
			  1. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
*/
 -->
<?php $this->load->view('header') ?>
<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/cctv/monitoring.css"> -->
<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.theme.default.css"/> -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<style type="text/css">
	#filterbulan .picker-switch{
	  display: none !important;
	}
	#filterbulan .prev{
	  display: none !important;
	}
	#filterbulan .next{
	  display: none !important;
	}
</style>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3><br />
						<!-- <button type="button" class="btn btn-sm btn-success pull-right" id="add_monitoring_button"><span class="fa fa-plus"> Tambah Monitoring </span></button> -->
					</div>
					<div class="box-body">
						<div class="row">
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Pabrik : </label>
				                	<select class="form-control" id="filterpabrik" name="filterpabrik" style="width: 110%;">
				                  		<?php
				                  			echo "<option value='0' >Silahkan Pilih Pabrik</option>";
					                		foreach($plant as $dt){
					                			echo "<option value='".$dt->plant."'";
					                			echo ">".$dt->plant_name."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>	
			            	<div class="col-sm-2">
			            		<div class="form-group">			            			
		                			<label> Bulan : </label>		                		
	                				<input type="text" class="form-control" id="filterbulan" name="filterbulan" autocomplete="off" readonly="readonly" value="<?php echo date('m'); ?>">	
	                			</div>				            	
			            	</div>
			            	<div class="col-sm-4">
			            		<div class="form-group">
			            			<div class="row">
			                			<div class="col-sm-3">
				                			<label> Tahun : </label>
				                		</div>
										<!-- <div class="checkbox select_all col-sm-3" style="margin:0; display: ;">
				                			<label><input type="checkbox" class="isSelectAll1"> Select All</label>
				                		</div>	 -->
				                		<div class="col-sm-4"></div>
									</div>
				                	<!-- select all pabrik -->
				                	
			                		<div class="row">
			                			<div class="col-sm-6">
			                				<input type="text" class="form-control" id="filtertahun" name="filtertahun" autocomplete="off" readonly="readonly" value="<?php echo date('Y'); ?>">
					                		<!-- <select class="form-control"  id="filtertahun" name="filtertahun" style="width: 100%;">
						                  		<?php
						                  	// 		$year 	= date('Y');
						                  	// 		$year1 	= $year - 1;
						                  	// 		$year2 	= $year + 1; 
							                		// for($i=0; $year1 <= $year2; $i++){
							                		// 	echo "<option value='".$year1."'>".$year1."</option>";
							                		// 	$year1++;
							                		// }
							                	?>
						                  	</select> -->
				                		</div>
					                	<!-- <div class="col-sm-2 ">
					                  		<button type="button" class="btn btn-sm btn-success" id="filter">Filter</button>
					                	</div> -->
					                	<div class="col-sm-6">&nbsp;</div>
			                		</div>
			                		
				            	</div>				            	
			            	</div>
			            		
			            	<div class="col-sm-2 ">
			            		<!-- <div class="form-group"> -->
			            			<div class="">&nbsp;</div>
									
								<!-- </div> -->
			            	</div>		            	
			            </div>

						<div id=div_mainTable>
							<table class="table table-bordered table-striped table-responsive table_main" id="table_main" >
								<thead>
									<tr>
										<th>No</th>
										<!-- <th>Pabrik</th> -->
										<th>Area</th>
										<th>Lokasi</th>
										<th>Periode</th>
										<th>Kondisi</th>
										<th>Keterangan</th>	
										<th>View CCTV</th>
									</tr>
								</thead>
								<tbody id="divOut">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
					
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/cctv/monitoring/monitoring_detail.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<!-- <script src="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.js"></script> -->




