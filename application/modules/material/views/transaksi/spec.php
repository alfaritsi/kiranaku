<!--
/*
@application  : KODE MATERIAL
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

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<?php 
							if(base64_decode($this->session->userdata("-ho-"))=='y'){
								echo'<button type="button" class="btn btn-success" id="add_button">Add Item Spec</button>';	
							}
							?>
							<?php 
							if(base64_decode($this->session->userdata("-nik-"))=='7143'){
								echo'<div class="btn-group pull-right"><button type="button" class="btn btn-info" id="imp_button">Import Excel</button></div>';	
							}
							?>
						</div>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
						<input type='hidden' id='ho' name='ho' value='<?php echo base64_decode($this->session->userdata("-ho-"));?>'>
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Item Group: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_item_group_filter" name="id_item_group[]" style="width: 100%;" data-placeholder="Pilih Item Group">
				                  		<?php
					                		foreach($group as $dt){
					                			echo "<option value='".$dt->id_item_group."'";
					                			echo ">".$dt->description."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Item Name: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_item_name_filter" name="id_item_name[]" style="width: 100%;" data-placeholder="Pilih Item Name">
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Classification: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_classification" name="request_classification[]" style="width: 100%;" data-placeholder="Pilih Classification">
				                  		<?php
											echo "<option value='A'>Asset</option>";
											echo "<option value='E'>Expense</option>";
											echo "<option value='I'>Inventory</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Request Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_request_status" name="request_status[]" style="width: 100%;" data-placeholder="Pilih Request Status">
				                  		<?php
											echo "<option value='y' selected>Requested</option>";
											echo "<option value='n'>Completed</option>";
											echo "<option value='d'>Deleted</option>";
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
									<th>Id</th>
									<th>Material Type</th>
									<th>Item Group</th>
									<th>Item Name</th>
									<th>Material Code</th>
									<th>Material Description</th>
									<th>Request Status</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
			<!--modal imp-->
			<div class="modal fade" id="imp_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-mg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-spec-imp">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Import Data Excel</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<div class="row">
												<div class="col-xs-12">
													<label for="file_excel">Upload File Excel</label>
													<input type="file" class="form-control" name="file_excel" id="file_excel" required>
												</div>
											</div>
										</div>	
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary" name="action_btn_imp">Import</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			
			<!--modal edit-->
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-mg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-spec" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Form Item Spec</h4>
									</div>
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
										<li><a href="#tab-mrp" data-toggle="tab">MRP</a></li>
										<li><a href="#tab-sales" data-toggle="tab">Sales</a></li>
										<li><a href="#tab-gambar" data-toggle="tab">Gambar</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--gambar-->
											<div class="tab-pane" id="tab-gambar">
												<div class="form-group">	
													<label for="gambar">Images</label>
													<div id="show_images"></div>
													<input type="file" multiple="multiple" class="form-control" id="gambar" name="gambar[]">
												</div>
											</div>	
											<!--general-->
											<div class="tab-pane active" id="tab-general">
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="id_item_group">Item Group</label>
															<select class="form-control select2modal form-control-hide" name="id_item_group" id="id_item_group"  required="required">
																<?php
																	echo "<option value='0'>Pilih Item Group</option>";
																	foreach($group as $dt){
																		echo"<option value='".$dt->id_item_group."'>".$dt->description."</option>";
																	}
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="msehi_uom">UOM</label>
															<select class="form-control select2modal form-control-hide" name="msehi_uom" id="msehi_uom" required="required">
																<?php
																	echo "<option value='0'>Pilih UOM</option>";
																	foreach($uom as $dt){
																		echo"<option value='".$dt->mseh3."'>[".$dt->mseh3."] ".$dt->msehl."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="id_item_name">Item Name</label>
															<select class="form-control select2modal form-control-hide" name="id_item_name" id="id_item_name"  required="required">
																<?php
																	echo "<option value='0'>Pilih Item Name</option>";
																	// foreach($item as $dt){
																		// echo"<option value='".$dt->id_item_name."'>".$dt->description."</option>";
																	// }
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="msehi_order">Order Unit</label>
															<select class="form-control select2modal form-control-hide" name="msehi_order" id="msehi_order">
																<?php
																	echo "<option value='0'>Pilih Order Unit</option>";
																	foreach($uom as $dt){
																		echo"<option value='".$dt->mseh3."'>[".$dt->mseh3."] ".$dt->msehl."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="description">Spec Description</label>
															<input style="text-transform: uppercase" type="text" class="form-control form-control-hide cek_data" data-tabel="tbl_item_spec" data-field="description" data-field2="id_item_name" name="description" id="description" placeholder="Description"  required="required">
														</div>
														<div class="col-xs-6">
															<label for="umrez">Conversion (Order Unit to UOM)</label>
															<input type="number" class="form-control form-control-hide" name="umrez" id="umrez" placeholder="Conversion">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="code">Material Code</label>
															<input type="text" class="form-control form-control-hide" name="code" id="code" placeholder="Material Code"  required="required" disabled>
														</div>
														<div class="col-xs-6">
															<label for="ekgrp">Purch Group</label>
															<select class="form-control form-control-hide select2modal" name="ekgrp" id="ekgrp"  required="required">
																<?php
																	echo "<option value='0'>Pilih Purch Group</option>";
																	foreach($ekgrp as $dt){
																		echo"<option value='".$dt->ekgrp."'>[".$dt->ekgrp."] ".$dt->eknam."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="detail">Material Description</label>
															<input style="text-transform: uppercase" type="text" class="form-control form-control-hide" name="detail" id="detail" placeholder="Detail Description"  required="required" disabled>
														</div>
														<div class="col-xs-6">
															<label for="availability_check">Availability Check</label>
															<select class="form-control form-control-hide select2" name="availability_check" id="availability_check" required="required">
																<?php
																	echo "<option value='0'>Pilih Availability Check</option>";
																	echo "<option value='01' selected>[01] Daily Requirements</option>";
																	echo "<option value='02'>[02] Individu Requirements</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="plant">Plant</label>
															<div class="checkbox pull-right select_all" style="margin:0; display: ;">
																<label><input type="checkbox" class="isSelectAllPlant form-control-hide"> All Factory</label>
															</div>
															<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant[]" id="plant" data-placeholder="Pilih Plant" required>
																<?php
																	foreach($plant as $dt){
																		echo "<option value='".$dt->plant."'>".$dt->plant."</option>";
																	}
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="lgort">Storage Location</label>
															<select class="form-control form-control-hide select2modal" name="lgort" id="lgort"  required="required">
																<?php
																	echo "<option value='0'>Pilih Storage Location</option>";
																	foreach($lgort as $dt){
																		echo"<option value='".$dt->lgort."'>[".$dt->lgort."] ".$dt->lgobe."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="old_material_number">Old Material Number</label>
															<input type="text" class="form-control form-control-hide" name="old_material_number" id="old_material_number" placeholder="Old Material Number">
														</div>
													</div>
												</div>
												
											</div>
											<!--mrp-->
											<div class="tab-pane" id="tab-mrp">
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="mrp_group">MRP Group</label>
															<select class="form-control form-control-hide select2modal" name="mrp_group" id="mrp_group" required="required">
																<?php
																	echo "<option value='0'>Pilih MRP Group</option>";
																	echo "<option value='0000'>[0000] External Procurement</option>";
																	echo "<option value='0001'>[0001] In-house Prod</option>";
																	echo "<option value='0002'>[0002] In-house Prod With Planned</option>";
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="service_level">Service Level(%)</label>
															<input type="number" class="form-control form-control-hide" name="service_level" id="service_level" placeholder="Service Level(%)"  required="required" min="0" max="99">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="mrp_type">MRP Type</label>
															<select class="form-control form-control-hide select2modal" name="mrp_type" id="mrp_type" required="required">
																<?php
																	echo "<option value='0'>Pilih MRP Type</option>";
																	echo "<option value='ND'>[ND] No Planning</option>";
																	echo "<option value='V1'>[V1] Manual Reorder Point</option>";
																	echo "<option value='V2' selected>[V2] Automatic Reorder Point</option>";
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="disls">Lot Size</label>
															<select class="form-control form-control-hide select2modal" name="disls" id="disls"  required="required">
																<?php
																	echo "<option value='0'>Pilih Lot Size</option>";
																	foreach($lot as $dt){
																		echo"<option value='".$dt->disls."'>[".$dt->disls."] ".$dt->loslt."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="id_item_group">MRP Controller</label>
															<select class="form-control form-control-hide select2modal" name="dispo" id="dispo"  required="required">
																<?php
																	echo "<option value='0'>Pilih MRP Controller</option>";
																	foreach($dispo as $dt){
																		if($dt->dispo==600){
																			echo"<option value='".$dt->dispo."' selected='selected'>[".$dt->dispo."] ".$dt->dsnam."</option>";
																		}else{
																			echo"<option value='".$dt->dispo."'>[".$dt->dispo."] ".$dt->dsnam."</option>";
																		}
																		
																	}
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="period_indicator">Period Indicator</label>
															<select class="form-control form-control-hide select2modal" name="period_indicator" id="period_indicator"  required="required">
																<?php
																	echo "<option value='0'>Pilih Period Indicator</option>";
																	echo "<option value='T'>[T] Daily</option>";
																	echo "<option value='W'>[W] Weekly</option>";
																	echo "<option value='M'>[M] Monthly</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="prmod">Forecast Model</label>
															<input type="text" class="form-control form-control-hide" name="prmod" id="prmod" placeholder="Forecast Model"  required="required" min="0" max="99">
														</div>
														<div class="col-xs-6">
															<label for="peran">History Periods</label>
															<input type="number" class="form-control form-control-hide" name="peran" id="peran" placeholder="History Periods"  required="required" min="0" max="99">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="anzpr">Forecast Periods</label>
															<input type="number" class="form-control form-control-hide" name="anzpr" id="anzpr" placeholder="Forecast Periods"  required="required" min="0" max="99">
														</div>
														<div class="col-xs-6">
															<label for="kzini">Initialization</label>
															<input type="text" class="form-control form-control-hide" name="kzini" id="kzini" placeholder="Initializtion"  required="required" min="0" max="99">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="siggr">Tracking Limit</label>
															<input type="number" class="form-control form-control-hide" name="siggr" id="siggr" placeholder="Tracking Limit"  required="required" min="0" max="99">
														</div>
													</div>
												</div>
												
											</div>
											<!--sales-->
											<div class="tab-pane" id="tab-sales">
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="sales_plant">Sales Org</label>
															<div><input type='checkbox' class='switch-onoff' name='sales_plant' id='sales_plant'></div>
														</div>
														<!--
														<div class="col-xs-6">
															<label for="sales_plant">Sales Org</label>
															<div class="checkbox pull-right form-control-hide select_all" style="margin:0; display: ;">
																<label><input type="checkbox" class="isSelectAllSalesPlant form-control-hide"> All Sales Org</label>
															</div>
															<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="sales_plant[]" id="sales_plant" data-placeholder="Pilih Sales Plant" required>
																<?php
																	foreach($plant as $dt){
																		echo "<option value='".$dt->plant."'>".$dt->plant."</option>";
																	}
																?>
															</select>
														</div>
														-->
														<div class="col-xs-6">
															<label for="gen_item_cat_group">Gen Item Cat Group</label>
															<select class="form-control form-control-hide select2modal" name="gen_item_cat_group" id="gen_item_cat_group"  required="required">
																<?php
																	echo "<option value='0'>Pilih Gen Item Cat Group</option>";
																	echo "<option value='0001'>[0001] Make-to-order</option>";
																	echo "<option value='0005'>[0005] Partial Billing</option>";
																	echo "<option value='LEIS'>[LEIS] Service w/o Delivery</option>";
																	echo "<option value='NORM'>[NORM] Standard item</option>";
																	echo "<option value='ZLEI'>[ZLEI] Scrap dan Limbah</option>";
																	echo "<option value='ZVRP'>[ZVRP] Packaging</option>";
																?>
															</select>
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="vtweg">Distribution Channel</label>
															<div class="checkbox pull-right select_all" style="margin:0; display: ;">
																<label><input type="checkbox" class="isSelectAllVtweg form-control-hide"> All</label>
															</div>
															<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="vtweg[]" id="vtweg" data-placeholder="Distribution Channel" required>
																<?php
																	foreach($dist as $dt){
																		echo"<option value='".$dt->vtweg."'>[".$dt->vtweg."] ".$dt->vtext."</option>";
																	}
																?>
															</select>
														</div>
														<!--
														<div class="col-xs-6">
															<label for="vtweg">Distribution Channel</label>
															<select class="form-control form-control-hide select2modal" name="vtweg" id="vtweg"  required="required">
																<?php
																	echo "<option value='0'>Pilih Distribution Channel</option>";
																	foreach($dist as $dt){
																		echo"<option value='".$dt->vtweg."'>[".$dt->vtweg."] ".$dt->vtext."</option>";
																	}
																?>
															</select>
														</div>
														-->
														<div class="col-xs-6">
															<label for="material_pricing_group">Material Pricing Group</label>
															<select class="form-control form-control-hide select2modal" name="material_pricing_group" id="material_pricing_group"  required="required">
																<?php
																	echo "<option value='0'>Pilih Material Pricing Group</option>";
																	echo "<option value='01'>[01] SIR</option>";
																	echo "<option value='02'>[02] Non-SIR</option>";
																?>
															</select>
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="spart">Division</label>
															<select class="form-control form-control-hide select2modal" name="spart" id="spart" required="required">
																<?php
																	echo "<option value='0'>Pilih Division</option>";
																	foreach($div as $dt){
																		echo"<option value='".$dt->spart."'>[".$dt->spart."] ".$dt->vtext."</option>";
																	}
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="material_statistic_group">Material Statistic Group</label>
															<select class="form-control form-control-hide select2modal" name="material_statistic_group" id="material_statistic_group"  required="required">
																<?php
																	echo "<option value='0'>Material Statistic Group</option>";
																	echo "<option value='1'>[1] A Material</option>";
																	echo "<option value='2'>[2] Group 2</option>";
																?>
															</select>
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="net_weight">Net Weight</label>
															<input type="number" class="form-control form-control-hide" name="net_weight" id="net_weight" placeholder="Net Weight"  required="required">
														</div>
														<div class="col-xs-6">
															<label for="acct_assignment_group">Acct Assignment Group</label>
															<select class="form-control form-control-hide select2modal" name="acct_assignment_group" id="acct_assignment_group"  required="required">
																<?php
																	echo "<option value='0'>Pilih Acct Assignment Group</option>";
																	echo "<option value='01'>[01] SIR</option>";
																	echo "<option value='02'>[02] Bokar</option>";
																	echo "<option value='03'>[03] Mesin</option>";
																	echo "<option value='04'>[04] Services (Maklon)</option>";
																	echo "<option value='05'>[05] Packing Charges</option>";
																	echo "<option value='06'>[06] Port Expenses</option>";
																	echo "<option value='07'>[07] Scrap dan Limbah</option>";
																	echo "<option value='08'>[08] Asset</option>";
																?>
															</select>
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="gross_weight">Gross Weight</label>
															<input type="number" class="form-control form-control-hide" name="gross_weight" id="gross_weight" placeholder="Gross Weight"  required="required">
														</div>
														<div class="col-xs-6">
															<label for="taxm1">Tax Class</label>
															<select class="form-control form-control-hide select2modal" name="taxm1" id="taxm1" required="required" min="0">
																<?php
																	echo "<option value='null'>[] NULL</option>";
																	echo "<option value='0'>[0] No Tax</option>";
																	echo "<option value='1'>[1] Full Tax</option>";
																?>
															</select>
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="xchpf">Batch Management</label>
															<div><input type='checkbox' class='switch-onoff' name='xchpf' id='xchpf'></div>
														</div>
													</div>
												</div>	
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="code" name="code" type="hidden">
										<input id="detail" name="detail" type="hidden">
										<input id="id_item_spec" name="id_item_spec" type="hidden">
										<input id="id_item_request" name="id_item_request" type="hidden">
										<input id="description_awal" name="description_awal" type="hidden">
										<input id="msehi_uom_awal" name="msehi_uom_awal" type="hidden">
										
										<button id="btn_save" type="button" class="btn btn-primary" name="action_btn">Submit</button>
										<button id="btn_change" type="button" class="btn btn-warning" name="action_btn_change">Change</button>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>	
			</div>
			<!--modal status-->
			<div class="modal fade" id="status_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-input">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Status SAP</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">	
											<div class="row">
												<div class="col-xs-2">
													<label for="code">Code</label>
												</div>
												<div class="col-xs-8">
													<input type="text" class="form-control" name="code" id="code" placeholder="Net Weight"  required="required" disabled>
												</div>
											</div>
										</div>
										<div class="form-group">	
											<div class="row">
												<div class="col-xs-2">
													<label for="description">Description</label>
												</div>
												<div class="col-xs-8">
													<input type="text" class="form-control" name="description" id="description" placeholder="Net Weight"  required="required" disabled>
												</div>
											</div>
										</div>
									
										<div id='show_plant'></div>									
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--modal req-->
			<div class="modal fade" id="add_extend" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-extend">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Extend Material Code</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">		
											<label for="code">Material Code</label>
											<input type="text" class="form-control form-control-hide" name="code" id="code" placeholder="Material Code"  required="required" disabled>
										</div>
										<div class="form-group">	
											<label for="description">Description</label>
											<input type="text" class="form-control" name="description" id="description" placeholder="Description"  required="required" disabled>
										</div>
										<div class="form-group">	
											<label for="plant">Plant</label>
											<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant[]" id="plant" data-placeholder="Pilih Plant" required  disabled>
												<?php
													foreach($plant as $dt){
														echo "<option value='".$dt->plant."'>".$dt->plant."</option>";
													}
												?>
											</select>
										</div>
										<div class="form-group">	
											<label for="plant_extend">Plant Extend</label>
											<div class="checkbox pull-right select_all" style="margin:0; display: ;">
												<label><input type="checkbox" class="isSelectAllPlantExtend form-control-hide"> All Plant Extend</label>
											</div>
											<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_extend[]" id="plant_extend" data-placeholder="Pilih Plant Extend">
											</select>
										</div>
										<!--
										<div class="form-group">	
											<label for="vtweg">Distribution Channel</label>
											<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="vtweg[]" id="vtweg" data-placeholder="Distribution Channel" required disabled>
												<?php
													foreach($dist as $dt){
														echo"<option value='".$dt->vtweg."'>[".$dt->vtweg."] ".$dt->vtext."</option>";
													}
												?>
											</select>
										</div>
										<div class="form-group">	
											<label for="vtweg_extend">Distribution Channel Extend</label>
											<div class="checkbox pull-right select_all" style="margin:0; display: ;">
												<label><input type="checkbox" class="isSelectAllVtwegExtend form-control-hide"> All Distribution Channel Extend</label>
											</div>
											<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="vtweg_extend[]" id="vtweg_extend" data-placeholder="Pilih Distribution Channel Extend">
											</select>
										</div>
										-->
									</div>
									<div class="modal-footer">
										<input id="id_item_spec" name="id_item_spec" type="hidden">
										<input id="plant" name="plant" type="hidden">
										<input id="vtweg" name="vtweg" type="hidden">
										<button id="btn_save" type="button" class="btn btn-primary" name="action_btn_extend">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/material/transaksi/spec.js"></script>
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