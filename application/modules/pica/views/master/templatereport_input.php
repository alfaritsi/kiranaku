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
</style>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12 ">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
						
					</div>
					<div class="box-body">						
			    		<form id="form_template" class="form-master-template">
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
														
														<!-- temuan -->
																<div class="col-sm-3">
																	<label for="temuan_fieldname">Jenis temuan</label>
																	<div>
																		<select class="form-control input-xxlarge select2" name="temuan_fieldname" id="temuan_fieldname" style="width: 100%;"  required="required">
																			<?php
												                				foreach($temuan as $dt){
														                			echo "<option value='".$dt->id_pica_jenis_temuan."|".$dt->jenis_temuan."' >";
														                			echo $dt->jenis_temuan." - ".$dt->requestor."</option>";
														                		}
												                			?>
																		</select>
																	</div>
																</div>
																
														<!-- jenis_report -->
													    		<div class="col-sm-3" >
																	<label for="jenis_report_fieldname">Jenis report</label>
																	<div id="divjenis_report">
																		<select class="form-control input-xxlarge select2" name="jenis_report_fieldname" id="jenis_report_fieldname" style="width: 100%;"  required="required">
																			<?php
																			/*	$report = "";
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

														<!-- Buyer -->
													    		<div class="col-sm-3" >
																	<label for="buyer_fieldname">Buyer</label>
																	<div id="divbuyer">
																		<select class="form-control input-xxlarge select2" name="buyer_fieldname"
																			id="buyer_fieldname" style="width: 100%;"  >
																			<?php
																				echo "<option value='0' >";
														                			echo "Silahkan Pilih Buyer </option>";
												                				foreach($buyer as $dt){
														                			echo "<option value='".$dt->label."' >";
														                			echo $dt->label."</option>";
														                		}
												                			?>
																		</select>
																	</div>
																</div>

														<!-- jumlah tipe -->
																<div class="col-sm-3">
																	<label for="jumlah_baris_fieldname">Jumlah tipe</label>
																	<div>
																		<input type="text" class="form-control input-xxlarge pull-left" name="jumlah_baris_fieldname" 
																			id="jumlah_baris_fieldname" placeholder="Masukkan Jumlah Tipe" 
																			required="required" width="100%" readonly="readonly">
																		
																	</div>
																</div>
																
															</div>

														</div>											
													</div>
													<br/>
													<div class="row text-center">
														<div class="form-group col-sm-12 pull-left" >
															<button type="button" class="btn btn-sm btn-success pull-right" id="add_baris">Tambah Tipe</button>
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
			              		<input type="hidden" id="baris_hidden" name="baris_hidden" value="0">
			              		<!-- <input type="hidden" id="id_header_edit" name="id_header_edit" value="0"> -->
							</div>
				      	</form>
				      	<button class="btn btn-dark back"  style="display: " name="back_button" id="bacButton">Back</button>
					</div>
				</div>
			
			</div>
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pica/master/mtemplatereport.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


