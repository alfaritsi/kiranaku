<!--
/*
	@application  		: PICA 
		@author       	: Airiza Yuddha (7849)
		@contributor  	:
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
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
	@-moz-document url-prefix() {
	  .legend2 {
		  position: absolute;
		  top: -2.7em;
		  right: 20px;
		  background: #fff;
		  line-height:1.2em;
		  z-index:1;
		}
	}
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
	.disabled.day {
	  opacity: 0.90;
	  filter: alpha(opacity=90);
	  background-color: lightgrey !important;
	  color: black !important;

	}
</style>
<script type="text/javascript">
	var login_nik 		= "<?php echo base64_decode($this->session->userdata("-nik-")); ?>";
	var login_posisi	= "<?php echo base64_decode($this->session->userdata("-posst-")); ?>";
	var login_ho		= "<?php echo base64_decode($this->session->userdata("-ho-")); ?>";
	var id_gedung 		= "<?php echo base64_decode($this->session->userdata("-id_gedung-")); ?>"; 
	/*<?php 
		// check otorisasi
		$posisi 	= base64_decode($this->session->userdata("-posst-"));
		$dataposisi = $this->dtranspica->get_data_pica_otorisasi('portal',$posisi);
		$level 		= 0;
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
	var if_decline = "<?php echo $if_decline; ?>"; */ 
	// console.log(level_user, if_approve, if_decline );
</script>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12 ">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left" id="title_form_all"><strong><?php echo $title; ?></strong></h3>
						<button type="button" class="btn btn-success pull-right" id="excel_button">Export To Excel</button> 
						<button class="btn btn-warning history pull-right" data-history="" style="display: block" name="history_btn" id="hisButton">History</button>
					</div>
					<div class="box-body">						
			    		<form id="form_template" class="form-master-template">
				    		<div class="modal-body">
					      		<div class="row">
						      		<div class="col-sm-12">
						    			<div class="row">
											<div class="col-sm-12">
												<fieldset class="fieldset-success">
													<legend class="text-left" >Header Pica</legend>
													<div class="row" id="divheader">
														<?php 
															// echo json_encode($this->session->userdata('--'));
														?>
														<div class="col-sm-6">
															<!-- jenis temuan pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="temuan_fieldname">Jenis Temuan</label>
																<div class="col-sm-8">
																	<select class="form-control input-xxlarge select2" name="temuan_fieldname" id="temuan_fieldname" style="width: 100%;"  required="required">
																		<!-- <option value='0'>Silahkan pilih jenis temuan</option> -->
																	<?php
										                				foreach($temuan as $dt){
																			echo "<option value='".$dt->id_pica_jenis_temuan."|".$dt->jenis_temuan."|".$dt->kode_temuan."|".$dt->requestor."' >";
												                			echo $dt->jenis_temuan." - ".$dt->requestor."</option>";
												                		}
										                			?>
																	</select>
																</div>
															</div>
															<!-- jenis report pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="report_fieldname">Jenis Report</label>
																<div class=" col-sm-8">
																	<select class="form-control input-xxlarge select2" name="jenis_report_fieldname" id="jenis_report_fieldname" style="width: 100%;"  required="required">
																		<!-- <option value="0">Silahkan pilih jenis report</option> -->
																	<?php
																		/*$report = "";
										                				foreach($jenis_report as $dt){
										                					if($report != trim($dt->jenis_report)) {
										                						$report = trim($dt->jenis_report);
													                			echo "<option value='".$dt->jenis_report."' >";
													                			echo $dt->jenis_report."</option>";	
										                					}										 
										                				}*/
										                			?>
																	</select>
																</div>
															</div>
															<!-- kategori pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="kategori_fieldname">Kategori</label>
																<div class="col-sm-8">
																	<select class="form-control input-xxlarge select2" id="kategori_fieldname" name="kategori_fieldname" style="width: 100%;" data-placeholder="Pilih Kategori">
																				<option value='0'>Silahkan pilih jenis Kategori</option>
												                  		<?php
													                		foreach($kategori as $dt){
													                			echo "<option value='".$dt->id_pica_mst_kategori."'";
													                			echo ">".$dt->kategori."</option>";
													                		}
													                	?>
												                  	</select>
																</div>
															</div>
															<!-- buyer pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="buyer_fieldname">Buyer</label>	
																<div class="col-sm-8">
																	<select class="form-control input-xxlarge select2" name="buyer_fieldname"
																	id="buyer_fieldname" style="width: 100%;" data-placeholder="Pilih Buyer" >
																	<option value="0">Silahkan pilih buyer</option>
																	<?php
										                				foreach($buyer as $dt){
												                			echo "<option value='".$dt->label."' >";
												                			echo $dt->label."</option>";
												                		}
										                			?>
																	</select>
																</div>
															</div>
															<!-- plant pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="pabrik_fieldname">Plant</label>	
																<div class="col-sm-8">
																	<select class="form-control input-xxlarge select2" name="pabrik_fieldname" id="pabrik_fieldname" style="width: 100%;"  required="required">
																		<?php
																			// foreach($plant as $dt){
											        //         					echo "<option value='".$dt->plant."' >";
														     //            		echo $dt->plant_name."</option>";	
											        //         				}
											                			?>
																	</select>
																</div>
															</div>
															<!-- tanggal pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="tanggal_fieldname">Tanggal</label>	
																<div class="col-sm-8">
																    <div class="input-group col-md-12 date" 
						                                            	data-js="datepicker">
						                                                <div class="input-group-addon">
						                                                    <i class="fa fa-calendar"></i>
						                                                </div>  
						                                                <input type="text" name="tanggal_fieldname" 
						                                                id="tanggal_fieldname" 
																		class="form-control kiranadatepicker" 
																		data-autoclose="true" 
																		required="required" readonly="readonly"> 
						                                            </div>
							                                   	</div>
															</div>
															<!-- No pica -->	
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="number_fieldname">No Pica</label>
																<div class="col-sm-8">
																	<input type="text" class="form-control input-xxlarge "
																		name="number_fieldname" id="number_fieldname" 
																		style="width: 100%;"  required="required" 
																		readonly="readonly">
																</div>
															</div>
														</div>

														<div class="col-sm-6">
														
															
															<!-- No SI pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="si_fieldname">No SI</label>
																<div class="col-sm-8" id="div_si_fieldname">
																	<select class="form-control select2" id="si_fieldname" 
																		name="si_fieldname" style="width: 100%;" 
																		data-placeholder="Pilih Nomor SI" readonly="readonly">
																	</select>
																</div>
															</div>
															<!-- No SO pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="so_fieldname">No SO</label>
																<div class="col-sm-8" id="div_so_fieldname">
																	<input type="hidden" class="form-control input-xxlarge " name="so_hidden" id="so_hidden" style="width: 100%;">
																	<select class="form-control select2" id="so_fieldname" 
																		name="so_fieldname" style="width: 100%;" 
																		data-placeholder="Pilih Nomor SO" readonly="readonly">
																	</select>
																</div>
															</div>
															<!-- No Lot pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="lot_fieldname">No Lot</label>
																<div class="col-sm-8" id="div_lot_fieldname">
																	<!-- <input type="text" class="form-control input-xxlarge " name="lot_fieldname" id="lot_fieldname" style="width: 100%;" readonly="readonly" > -->
																	<select class="form-control select2" id="lot_fieldname" 
																		name="lot_fieldname" style="width: 100%;" 
																		data-placeholder="Pilih Nomor Lot"  readonly="readonly">
																	</select>
																</div>
															</div>
															<!-- No Pallet pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="pallet_fieldname">No Pallet</label>
																<div class="col-sm-8" id="div_pallet_fieldname">
																	<input type="hidden" class="form-control input-xxlarge " name="pallet_hidden" id="pallet_hidden" style="width: 100%;">
																	<select class="form-control select2" id="pallet_fieldname" 
																		name="pallet_fieldname" style="width: 100%;" 
																		data-placeholder="Pilih Nomor Pallet"  readonly="readonly">
																	</select>
																</div>
															</div>
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="tanggal_prod_fieldname">Tanggal Produksi</label>	
																<div class="col-sm-8">
																    <div class="input-group col-md-12 date" 
						                                            	data-js="datepicker">
						                                                <div class="input-group-addon">
						                                                    <i class="fa fa-calendar"></i>
						                                                </div>  
						                                                <input type="text" name="tanggal_prod_fieldname" 
						                                                id="tanggal_prod_fieldname" 
																		class="form-control kiranadatepicker" 
																		data-autoclose="true" 
																		 readonly="readonly"> 
						                                            </div>
							                                   	</div>
															</div>
															<!-- verificator pica -->
															<div class="form-group col-sm-12" style="display: none ;">
																<label class="col-sm-3" for="verificator_fieldname">Verificator</label>
																<div class="col-sm-8">
																	<!-- <select class="form-control select2" id="verificator_fieldname" name="verificator_fieldname[]" multiple="multiple" style="width: 100%;" data-placeholder="Pilih verificator"
																	required="required">
												                  		<?php
													                		foreach($posisi as $dt){
													                			echo "<option id='".$dt->id_posisi."' value='".$dt->id_posisi."'";
													                			echo ">".$dt->posisi."</option>";
													                		}
													                	?>
												                  	</select> -->

																	<input type="text" class="form-control" name="verificator_fieldname[]" id="verificator_fieldname" readonly="readonly">
																	<input type="hidden" class="form-control" name="id_verificator[]" id="id_verificator" readonly="readonly">
																</div>
															</div>
															<!-- def pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="def_fieldname">Definition Pica</label>
																<div class="col-sm-8">
																	<textarea class="form-control input-xxlarge " name="def_fieldname" id="def_fieldname" style="width: 100%;"  required="required" cols="3"></textarea>
																</div>
															</div>

															<!-- foto pica -->
															<div class="form-group col-sm-12">
																<label class="col-sm-3" for="foto_fieldname">Lampiran</label>
																
																<div class="col-sm-8">
																	<div class="col-sm-12 pull-left form-group" >
																		<div class="fileinput fileinput-new" id='fileinput' data-provides="fileinput">
				                                            				<div class="btn-group btn-sm no-padding">
				                                                				<a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox><i class="fa fa-search"></i></a>
				                                                				<a class="btn btn-facebook btn-file">
					                                                    			<div class="fileinput-new">Attachment</div>
					                                                    			<div class="fileinput-exists">
					                                                    				<i class="fa fa-edit"></i>
					                                                    			</div>
					                                                    			<input class="pull-left" type="file" name="foto_fieldname[]" id="foto_fieldname">
				                                                    			</a> 
					                                                			<a href="#" class="btn btn-pinterest fileinput-exists"data-dismiss="fileinput">
					                                                				<i class="fa fa-trash"></i>
					                                                			</a>
				                                            				</div>
			                                            				</div>
			                                            			</div>
																</div>
																<div class="col-sm-6  text-danger"><span class="font-weight-bold"> * Kapasitas maksimum file adalah 2Mb </span></div>

															</div>

														</div>
															
																									
													</div>
													
													<div class="row text-center" id="divpilih_template">
														<div class="form-group col-sm-2 " >
															<button type="button" class="btn btn-sm form-control btn-success pull-right" id="add_baris"> Pilih Template </button>

														</div>													
														<div class="form-group col-sm-2 pull-left" >
															<div id="tipe_template" class="pull-right">
																		
															</div>
														</div>
														<div class="form-group col-sm-2 pull-left" >
															<div id="action_add_template" class="pull-left">
																		
															</div>
														</div>
													</div>
												<!-- </fieldset> -->
											</div>
											<div class="row">
												<div class="col-sm-12" id="detail_template"></div>	
											</div>											
										</div>
									</div>
					      		</div>
				      		</div>
				      		<div class="modal-footer">
				      			<div class="pull-left text-danger"><span class="font-weight-bold"> * Kapasitas maksimum file adalah 2Mb </span></div>
				      			<!-- <button type="reset" class="btn btn-info" id="reset_button">Reset</button> -->
				      			<button type="submit" class="btn btn-success " name="action_btn" id="addButton">Submit</button>
								<input type="hidden" id="finding_app" name="finding_app" value="0">
			              		<input type="hidden" id="id_hide" name="id_hide" value="0">
			              		<input type="hidden" id="baris_hidden" name="baris_hidden" value="0">
			              		<input type="hidden" id="id_delete_hidden" name="id_delete_hidden" value="0">
			              		<input type="hidden" id="thisday" name="thisday" value="<?php echo date("d.m.Y"); ?>" >
			              		<input type="hidden" id="mode_hidden" name="mode_hidden" value="0">
			              		<input type="hidden" id="type_hide_details" name="type_hide_details" >
				      			<input type="hidden" id="if_approve_hide_details" name="if_approve_hide_details" >
				      			<input type="hidden" id="if_decline_hide_details" name="if_decline_hide_details" >
				      			<input type="hidden" id="status_pica_details" name="status_pica_details" >


			              		<!-- <input type="hidden" id="id_header_edit" name="id_header_edit" value="0"> -->
							</div>
				      	</form>
				      	<button class="btn btn-success approve" data-approve="" style="display: none" name="approve_btn" id="appButton">Approve</button>
		      			<button class="btn btn-danger reject" data-reject="" style="display: none" name="reject_btn" id="rejButton">Reject</button>
		      			<button class="btn btn-dark back"  style="display: " name="back_button" id="bacButton">Back</button>
					</div>
				</div>
			
			</div>
			
		</div>
	</section>
</div>
<div class="modal fade" id="approve_modal" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel_app">
	<div class="modal-dialog modal-md" role="document">
    	<div class="modal-content">
    		<form id="form_approval" class="form-approve-pica">
	    		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="myModalLabel_app">Approve Pica</h4>
	      		</div>
	      		<div class="modal-body">
		      		<div class="row">
			      		<div class="col-sm-12">
			    			<div class="form-horizontal">
			    				<div class="form-group">
				                	<label for="pabrik" class="col-sm-1 control-label">Komentar</label>
				                	<div class="col-sm-12">
										<textarea name="komentar" id="komentar" class="form-control" rows="7" style="resize: none;" ></textarea>
				                  	</div>
				                </div>
							</div>
						</div>
		      		</div>
	      		</div>
	      		<div class="modal-footer">
	      			<!-- <button type="reset" class="btn btn-info" id="reset_button">Reset</button> -->
	      			<button type="submit" class="btn btn-success action" name="action_button">Submit</button>
	      			<input type="hidden" id="data_hide" name="data_hide">
	      			<input type="hidden" id="type_hide" name="type_hide" >
	      			<input type="hidden" id="if_approve_hide" name="if_approve_hide" >
	      			<input type="hidden" id="if_decline_hide" name="if_decline_hide" >
              		<input type="hidden" id="id_hide_approval" name="id_hide_approval">
              		<input type="hidden" id="id_number_approval" name="id_number_approval">
					<input type="hidden" id="status_pica_act" name="status_pica_act" >
					<input type="hidden" id="baris_act" name="baris_act" value="0">
					<input type="hidden" id="finding_app_act" name="finding_app_act" value="0">			              		
              	</div>
	      	</form>
    	</div>
  	</div>
</div>

<div class="modal fade" id="history_modal" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel_history">
	<div class="modal-dialog modal-md" role="document">
    	<div class="modal-content">
    		<!-- <form id="form_approval" class="form-approve-pica"> -->
	    		<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        		<h4 class="modal-title" id="myModalLabel_history">History Pica</h4>
	      		</div>
	      		<div class="modal-body">
		      		<div class="row">
			      		<div class="col-sm-12">
			    			<div class="form-horizontal">
			    				<div class="form-group col-md-12" id="history_divData">
				                	<table class="table table-bordered table-striped my-datatable-extends">
					          			<thead>
						              		<th>No. Pica</th>
						              		<th>Tanggal</th>
						              		<th>Status</th>
						              		<th>Comment</th>
						              	</thead>
						              	<tbody>
						              		
						              	</tbody>
					          		</table>
				                </div>
							</div>
						</div>
		      		</div>
	      		</div>
	      		<!-- <div class="modal-footer"> -->
	      			<!-- <button type="reset" class="btn btn-info" id="reset_button">Reset</button> -->
	      			<!-- <button type="submit" class="btn btn-success action" name="action_button">Submit</button> -->
	      			<!-- <input type="text" id="data_hide" name="data_hide">
	      			<input type="text" id="type_hide" name="type_hide" >
	      			<input type="text" id="if_approve_hide" name="if_approve_hide" >
	      			<input type="text" id="if_decline_hide" name="if_decline_hide" >
              		<input type="text" id="id_hide_approval" name="id_hide_approval">
              		<input type="text" id="id_number_approval" name="id_number_approval"> -->
              	<!-- </div> -->
	      	<!-- </form> -->
    	</div>
  	</div>
</div>


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pica/transaksi/pica.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<!-- for attchment -->
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>


