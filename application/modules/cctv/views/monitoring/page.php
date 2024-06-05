<!--
/*
	@application  : Monitoring CCTV 
		@author       : Airiza Yuddha (7849)
		@contributor  :
			  1. <airiza yuddha> (7849) 10.06.2019
				 - edit form input 
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
*/
 -->
<?php $this->load->view('header') ?>
<!-- bootstrap toggle -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/cctv/monitoring.css"> -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.theme.default.css"/>
<!-- bootstrap-switch -->
<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-switch/bootstrap-switch.min.css"/> -->

<!-- bootstrap toggle -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<!-- for attachment -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<!-- <script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script> -->
<style type="text/css">
	.btn-file {
    position: relative;
    overflow: hidden;
	}
	textarea {
	  resize: none;
	}
	.btn-file input[type=file] {
	    position: absolute;
	    top: 0;
	    right: 0;
	    min-width: 100%;
	    min-height: 100%;
	    font-size: 100px;
	    text-align: right;
	    filter: alpha(opacity=0);
	    opacity: 0;
	    outline: none;
	    background: white;
	    cursor: inherit;
	    display: block;
	}
	.text-align-top {
	    vertical-align: top !important
	}
<?php 
	$ho = base64_decode($this->session->userdata("-ho-"));
	$nik = base64_decode($this->session->userdata("-nik-"));
	$session = ($this->session->userdata());
?>
	

</style>
<script type="text/javascript">
	var ho 			= '<?php echo $ho; ?>';
	var nik			= '<?php echo $nik; ?>';
	var gsber 		= '<?php echo base64_decode($this->session->userdata("-gsber-")); ?>';
	var pabrik_sess = "";
	if(ho == 'n'){
		pabrik_sess = gsber;
	} else {
		pabrik_sess = null;
	}
</script>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3><br />
						<div id="divAddButton">
							<button type="button" class="btn btn-sm btn-success pull-right" id="add_monitoring_button">Tambah Data</button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik : </label>
				                	<select class="form-control" id="filterpabrik" name="filterpabrik" style="width: 70%;">
				                  		<?php
				                  			echo "<option value='0' >Silahkan Pilih Pabrik</option>";
					                		foreach($plant as $dt){
					                			echo "<option value='".$dt->plant."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>

			            	</div>
							<div class="col-sm-4">
			            		<div class="form-group">
			            			<div class="row">
			                			<div class="col-sm-3">
				                			<label> Tahun : </label>
				                		</div>
										<div class="col-sm-4"></div>
									</div>
				                	<!-- select all pabrik -->
				                	
			                		<div class="row">
			                			<div class="col-sm-6">
			                				<input type="text" class="form-control" id="filtertahun" name="filtertahun" autocomplete="off"  value="<?php echo date('Y'); ?>" readonly="readonly"> 
			                				<!-- ayy 11.01.2019 -->
					                	</div>
					                	<div class="col-sm-6">&nbsp;</div>
			                		</div>
			                		
				            	</div>				            	
			            	</div>
			            </div>
						<div id=div_mainTable>
							
							<table class="table table-bordered  " id="table_main" >
								<!-- <thead>
									<tr>
										<th>Pabrik / Week</th>
										<th>Januari</th>
										<th>Februari</th>
										<th>Maret</th>	
										<th>April</th>
										<th>Mei</th>
										<th>Juni</th>
										<th>Juli</th>
										<th>Agustus</th>
										<th>September</th>
										<th>Oktober</th>
										<th>November</th>
										<th>Desember</th>								
										<!- <th>Action</th> ->
									</tr>
								</thead> -->
								<tbody id="divOut">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Modal -->
			<div class="modal fade" id="add_monitoring_modal" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
			    		<form id="form_license" class="form-trans-monitoring">
				    		<div class="modal-header">
				        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        		<h4 class="modal-title" id="myModalLabel">Tambah Data</h4>
				      		</div>
				      		<div class="modal-body">
					      		<div class="row">
						      		<div class="col-sm-12">
							    			<div class="form-horizontal">
							    				<div class="form-group">
								                	<label for="pabrik" class="col-sm-1 control-label">Pabrik</label>
								                	<div class="col-sm-4">
														<select class="form-control input-xxlarge " name="pabrik" id="pabrik" style="width: 105%;"  required>
							                				<option value="0">Silahkan pilih pabrik</option>
								                			<?php
								                				foreach($plant as $r){
								                					echo "<option value='".$r->plant."'>".$r->nama."</option>";
								                				}
								                			?>
							                			</select>
								                  	</div>
								                </div>
												<?php //if($ho == 'y'){ ?>
									                <div class="form-group" id="divweekho" style="display: none">
														<label for="Periode" class="col-sm-1 control-label">Minggu </label>
														<div class="input-group">
															<div class="col-sm-4 pull-left" id="divweekheader">
																<input type="text"
																   class="angka form-control"
																   name="waktu"
																   value = <?php echo $week; ?>
																   required="required"
																   readonly="readonly">
																
															</div>
															<div class="col-sm-5">
																<input type="text" name="bln_thn" id="bln_thn" 
																	class="form-control kiranadatepicker" 
																	data-format="mm.yyyy" data-startview="months" 
																	data-minviewmode="months" data-autoclose="true" 
																	data-enddate="<?php echo date('Y-m-d'); ?>" 
																	value="<?php echo $month.'.'.$year; ?>" 
																	required="required">
															</div>
														</div>
													</div>
												<?php //} else { ?>
									                <div class="form-group pull-left" id="divweekplant">
														<label for="Periode"
															   class="col-sm-1 control-label">Minggu </label>
														<div class="col-sm-4">
															<div class="input-group">
																<input type="text"
																	   class="angka form-control"
																	   name="waktu"
																	   value = <?php echo $week; ?>
																	   required = "required"
																	   readonly="readonly">
																<div class="input-group-addon" id="monthyear_gaddon">
																	<?php echo $month2.' '.$year; ?>
																</div>
															</div>
														</div>
													</div>
												<?php //} ?>
											</div>
												
											<div class="row">
												<div class="col-sm-12">
													<fieldset class="fieldset-success" style="display: none;">
														<legend class="text-center" >Lokasi CCTV</legend>
														<div class="row">
															<div class="col-sm-12 form-horizontal">
																<div class="nav-tabs-custom" id="divdetail_titik"></div>
															</div>
														</div>
													</fieldset>
												</div>
											</div>
									</div>
					      		</div>
				      		</div>
				      		<div class="modal-footer">
				      			<!-- <button type="reset" class="btn btn-info" id="reset_button">Reset</button> -->
				      			<button type="submit" class="btn btn-success " name="action_btn">Submit</button>
			              		<input type="hidden" id="id_hide" name="id_hide" value="0">
			              		<input type="hidden" id="week_hidden_bc" name="week_hidden_bc" value=<?php echo $week; ?> \>
			              		<input type="hidden" id="week_hidden" name="week_hidden" value=<?php echo $week; ?> \>		              		
			              		<input type="hidden" id="month_hidden" name="month_hidden" value=<?php echo $month; ?> \>
			              		<input type="hidden" id="month_hidden_bc" name="month_hidden_bc" value=<?php echo $month; ?> \>
			              		<input type="hidden" id="year_hidden" name="year_hidden" value=<?php echo $year; ?> \>
			              		<input type="hidden" id="year_hidden_bc" name="year_hidden_bc" value=<?php echo $year; ?> \>
			              		<input type="hidden" id="dot_hidden" name="dot_hidden" value=<?php //echo "'".rtrim($dot_hiden,",")."'"; ?> \>
			              		<input type="hidden" id="countdot_hidden" name="countdot_hidden" value=<?php echo $cdot; ?> \>
			              		<input type="hidden" id="editid_mdot_hidden" name="editid_mdot_hidden" value="">
			              		<input type="hidden" id="divchoice_hide" name="divchoice_hide" value="">
			              		<input type="hidden" id="divchoiceinput_hide" name="divchoiceinput_hide" value="">            		
			              		<input type="hidden" id="hidden_file" name="hidden_file" value="">
			              		<input type="hidden" id="hidden_file_count" name="hidden_file_count" value="1">
			              		
			              		<input type="hidden" id="isconvert" name="isconvert" value="0">
			              		<input type="hidden" id="pabrik_ext" name="pabrik_ext" value="0">
			              		<input type="hidden" id="license_ext" name="license_ext" value="0">
			              		<input type="hidden" id="isextend" name="isextend" value="0">

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
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>
<script src="<?php echo base_url() ?>assets/apps/js/cctv/monitoring/monitoring_input.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.js"></script>
<!-- for attchment -->
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/plugins/bootstrap-switch/bootstrap-switch.min.js" ></script> -->




