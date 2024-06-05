<!--
/*
	@application  : PICA 
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<style type="text/css">
	fieldset {
	  position: relative;
	}
	.legend2 {
	  position: absolute;
	  top: 0.7em;
	  right: 20px;
	  background: #fff;
	  line-height:1.2em;
	}
</style>
<script type="text/javascript">
	var login_nik 		= "<?php echo base64_decode($this->session->userdata("-nik-")); ?>"; 
	var login_posisi	= "<?php echo base64_decode($this->session->userdata("-posst-")); ?>";
	var login_ho		= "<?php echo base64_decode($this->session->userdata("-ho-")); ?>";
	var id_gedung 		= "<?php echo base64_decode($this->session->userdata("-id_gedung-")); ?>";
	<?php 
		// check otorisasi
		$posisi 	= base64_decode($this->session->userdata("-posst-"));
		$dataposisi = $this->dtranspica->get_data_pica_otorisasi('portal',$posisi);
		// echo json_encode($dataposisi);
		$level 		= '0';
		$if_approve = 0;
		$if_decline = 0;
		foreach ($dataposisi as $dt) {
			$level 		= $dt->level;
			$pabrik 	= rtrim($dt->pabrik,', ');
			$nama_role 	= $dt->nama_role;
			$if_approve = $dt->if_approve;
			$if_decline = $dt->if_decline;
		}
		
	?>
	var level_user = "<?php echo $level; ?>"; 
	var if_approve = "<?php echo $if_approve; ?>"; 
	var if_decline = "<?php echo $if_decline; ?>";  
</script>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12 ">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
						<div id="divAddButton">
							<button type="button" class="btn btn-sm btn-success pull-right" id="add_template_button">Tambah Data</button>
						</div>
						
					</div>
					<div class="box-body" id="divfilter">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik : </label>
				                	<!-- select all pabrik -->
				                	<div class="checkbox pull-right select_all" style="margin:0; display: ;">
			                			<label><input type="checkbox" class="isSelectAll"> Select All</label>
			                		</div>
				                	<select class="form-control select2" multiple="multiple" id="filter_pabrik" name="filter_pabrik" style="width: 100%;">
				                  		<?php
					                		foreach($plant as $dt){
					                			echo "<option value='".$dt->plant."'";
					                			echo ">".$dt->plant_name."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			            	<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Jenis Report : </label>
				                	<!-- select all pabrik -->
				                	<div class="checkbox pull-right select_all" style="margin:0; display: ;">
			                			<label><input type="checkbox" class="isSelectAll1"> Select All</label>
			                		</div>
				                	<select class="form-control select2" multiple="multiple" id="filter_report" name="filter_report" style="width: 100%;">
				                  		<?php
					                		foreach($jenis_report as $dt){
			                					if($report != trim($dt->jenis_report)) {
			                						$report = trim($dt->jenis_report);
						                			echo "<option value='".$dt->jenis_report."' >";
						                			echo $dt->jenis_report."</option>";	
			                					}										 
			                				}
					                	?>
				                  	</select>									
				            	</div>
				            </div>
			            	<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Temuan : </label>
				                	<!-- select all pabrik -->
				                	<div class="checkbox pull-right select_all" style="margin:0; display: ;">
			                			<label><input type="checkbox" class="isSelectAll2"> Select All</label>
			                		</div>
				                	<select class="form-control select2" multiple="multiple" id="filter_temuan" name="filter_temuan" style="width: 100%;">
				                  		<?php
					                		foreach($temuan as $dt){
												echo "<option value='".$dt->jenis_temuan."-".$dt->requestor."' >";
					                			echo $dt->jenis_temuan." - ".$dt->requestor."</option>";
					                		}
					                	?>
				                  	</select>
								</div>
				            </div>
				            <div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Buyer : </label>
				                	<!-- select all pabrik -->
				                	<div class="checkbox pull-right select_all" style="margin:0; display: ;">
			                			<label><input type="checkbox" class="isSelectAll3"> Select All</label>
			                		</div>
				                	<select class="form-control select2" multiple="multiple" id="filter_buyer" name="filter_buyer" style="width: 100%;">
				                  		<?php
					                		foreach($buyer as $dt){
					                			echo "<option value='".$dt->label."' >";
					                			echo $dt->label."</option>";
					                		}
					                	?>
				                  	</select>
								</div>
				            </div>

			            	
			            </div>
			            <div class="row" style="display: none">
			            	<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> No Pica : </label>
				                	<!-- select all pabrik -->
				                	<div class="checkbox pull-right select_all" style="margin:0; display: ;">
			                			<label><input type="checkbox" class="isSelectAll4"> Select All</label>
			                		</div>
				                	<select class="form-control select2" id="filter_no" name="filter_no" style="width: 100%;"></select>
								</div>
				            </div>
			            	<div class="col-sm-5">
			            		<div class="form-group">
				                	<div class="col-sm-4">
				                	<label>Periode : </label>
				                	<?php 
				                		$tanggal_awalfilter 	= date('Y-m-d', strtotime('-14 days', strtotime(date("Y-m-d")))) ; 
				                		$tanggal_akhirfilter 	= date('Y-m-d');
				                	?>
				                	<!-- <input class="form-control datepicker"  id="tanggal_awal" name="tanggal_awal" style="width: 100%;"/> -->
					                	<div class="input-group col-md-12 date" 
	                                    	data-js="datepicker">
	                                        <div class="input-group-addon">
	                                            <i class="fa fa-calendar"></i>
	                                        </div>  
	                                        <input type="text" name="tanggal_fieldname" 
	                                        id="tanggal_fieldname" 
											class="form-control kiranadatepicker" 
											data-autoclose="true" 
											required="required"> 
	                                    </div>
				                	</div>
				                	<div class="col-sm-1">	
				                		<label>&nbsp;</label>
				                	<label class="form-control no-border" style="width: 5%;" > To </label>
				                	
				            		</div>
				            		
									<div class="col-sm-4">
										<label>&nbsp;</label>	
					            		<!-- <input class="form-control datepicker" value="<?php echo $tanggal_akhirfilter ?>" id="tanggal_akhir" name="tanggal_akhir" style="width: 100%;"/> -->
					            		<div class="input-group col-md-12 date" 
	                                    	data-js="datepicker">
	                                        <div class="input-group-addon">
	                                            <i class="fa fa-calendar"></i>
	                                        </div>  
	                                        <input type="text" name="tanggal_fieldname" 
	                                        id="tanggal_fieldname" 
											class="form-control kiranadatepicker" 
											data-autoclose="true" 
											required="required"> 
	                                    </div>
				            		</div>

				            		<div class="col-sm-3">
				            			<label>&nbsp;</label>	
				            			<button type="button" class="btn btn-sm btn-success pull-right" id="excel_button">Export To Excel</button>
				            		</div>
				            	</div>
				            </div>	
			            </div>
		            </div>
					<div class="box-body">
						<table class="table table-bordered table-striped" id="sspTable">
							<thead>
								<tr>
									<th>Nomor Pica</th>
									<th>Tanggal</th>
									<th>Pabrik</th>
									<th>Jenis Report</th>									
									<th>Jenis Temuan</th>									
									<th>Buyer</th>
									<th>Status</th>
									<!-- <th>Jumlah Baris</th> -->
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
				

			</div>
			
			<!--modal add_modal_detail-->
			<!-- Modal -->
				<div class="modal fade" id="add_template_modal" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel">
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
							    			<div class="row">
												<div class="col-sm-12">
													<fieldset class="fieldset-success">
														<legend class="text-left" >Header Template</legend>
														<div class="row">
															<div class="col-sm-12 form-horizontal">
																<div class="nav-tabs-custom" id="divdetail_titik">
															
															<!-- lokasi -->
																	<div class="col-sm-4">
																		<label for="lokasi_fieldname_parent">Jenis temuan</label>
																		<div>
																			<select class="form-control input-xxlarge " name="lokasi_fieldname_parent" id="lokasi_fieldname_parent" style="width: 100%;"  required="required">
																				<option value="0">Silahkan pilih lokasi</option>
													                			<?php
													                				foreach($lokasi_parent as $r){
													                					echo "<option value='".$r->id_lokasi."'>".$r->nama."</option>";
													                				}
													                			?>
																			</select>
																		</div>
																	</div>
																	
															<!-- sublokasi -->
														    		<div class="col-sm-4" >
																		<label for="lokasi_fieldname">Jenis report</label>
																		<div id="divsublok">
																			<select class="form-control input-xxlarge " name="lokasi_fieldname" id="lokasi_fieldname" style="width: 100%;"  required="required">
																				<option value="0">Silahkan pilih sublokasi</option>
																			</select>
																		</div>
																	</div>

															<!-- Buyer -->
														    		<div class="col-sm-4" >
																		<label for="buyer">Buyer</label>
																		<div id="divsublok">
																			<select class="form-control input-xxlarge " name="buyer"
																				id="buyer" style="width: 100%;"  required="required">
																				<option value="0">Silahkan pilih sublokasi</option>
																			</select>
																		</div>
																	</div>

															<!-- jumlah baris -->
																	<div class="col-sm-4">
																		<label for="jumlah_baris">Jumlah tipe</label>
																		<div>
																			<input type="text" class="form-control input-xxlarge pull-left" name="jumlah_baris" 
																				id="jumlah_baris" placeholder="Masukkan Jumlah baris" 
																				required="required" width="100%" readonly="readonly">
																			
																		</div>
																	</div>
																	
																<!-- duedate -->
														    		<!-- <div class="col-sm-4" >
																		<label for="lokasi_fieldname">lama due date</label>
																		<div id="divsublok">
																			<input type="text" class="form-control input-xxlarge pull-left" name="duedate" 
																				id="duedate" placeholder="Masukkan Jumlah baris" 
																				required="required" width="100%">
																		</div>
																	</div> -->
																
																</div>

															</div>											
														</div>
														<div class="row">
															<div class="form-group col-sm-12 pull-right" >
																<button type="button" class="btn btn-sm btn-success pull-right" id="add_baris">Tambah Baris</button>
															</div>
														</div>
													</fieldset>
												</div>
												<div class="col-sm-12">
													<fieldset class="fieldset-success">
														<legend class="text-center" >Detail Template</legend>
														<div class="row">
															<div class="col-sm-12 form-horizontal">
																<div class="nav-tabs-custom" id="divdetail_baris">
																	
																	<div id="detail_template">
																		
																	</div>


																</div>
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
				              		<!-- <input type="hidden" id="baris_hidden" name="baris_hidden" value="0"> -->
								</div>
					      	</form>
				    	</div>
				  	</div>
				</div>


		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pica/transaksi/pica.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>


