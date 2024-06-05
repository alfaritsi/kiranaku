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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<!--<button type="button" class="btn btn-info" id="rfc_button">Sync SAP</button>-->
							<button type="button" class="btn btn-success" id="req_button">Confirm</button>
                        </div>						
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
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
				                  		<?php
					                		foreach($item as $dt){
					                			echo "<option value='".$dt->id_item_name."'";
					                			echo ">".$dt->description."</option>";
					                		}
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
									<th>Item Group</th>
									<th>Item Name</th>
									<th>Material Code</th>
									<th>Material Description</th>
									<th>Purchase Type</th>
									<th>Purchase Authorization</th>
									<!--
									<th>Beli di NSI2</th>
									<th>Spesification Check</th>
									-->
									<th>Request Status</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
			<!--modal req-->
			<div class="modal fade" id="req_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-proc">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Confirm Procurement Data</h4>
									</div>
									<div class="modal-body">
										<table id="periode_detail" class="table table-bordered datatable-periode">
											<thead>
												<th>
													<div class="checkbox">
														<center><label><input type="checkbox" class="selectALL"></label></center>
													</div>
												</th>
												<th>Item Group</th>
												<th>Item Name</th>
												<th>Item Spec ID</th>
												<th>Item Spec Description</th>
												<th>Purchase Type</th>
												<th>Purchase Authorization</th>
												<!--
												<th>Beli di NSI2</th>
												<th>Spesification Check</th>
												-->
											</thead>
											<tbody id="show_detail">
											</tbody>
										</table>
									</div>
									<div class="modal-footer">
										<input id="count" name="count" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn">Proses</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--modal req-->
			<div class="modal fade" id="modal_edit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-edit-proc">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Edit Konfirmasi Procurement</h4>
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
											<label for="purchase_type_edit">Purchase Type</label>
											<select class="form-control select2" name="purchase_type_edit" id="purchase_type_edit" required="required">
												<?php
													echo "<option value='0'>-Chose-</option>";
													echo "<option value='PO'>PO</option>";
													echo "<option value='Non PO'>Non PO</option>";
												?>
											</select>
										</div>
										<div class="form-group">
											<label for="purchase_authorization_edit">Purchase Authorization</label>
											<select class="form-control select2" name="purchase_authorization_edit" id="purchase_authorization_edit" required="required">
												<?php
													echo "<option value='0'>-Chose-</option>";
													echo "<option value='HO'>HO</option>";
													echo "<option value='Pabrik'>Pabrik</option>";
												?>
											</select>
										</div>
									</div>
									<div class="modal-footer">
										<input id="id_item_spec" name="id_item_spec" type="hidden">
										<button id="btn_save" type="button" class="btn btn-primary" name="action_btn_edit">Edit</button>
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
<script src="<?php echo base_url() ?>assets/apps/js/material/transaksi/proc.js"></script>
<!--export to excel-->
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