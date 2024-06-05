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
						<button type="button" class="btn btn-sm btn-success pull-right" id="req_button">Confirm</button> 
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
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
						<table class="table table-bordered table-striped my-datatable-extends-order">	   
							<thead>
								<tr>
									<th>Item Group</th>
									<th>Item Name</th>
									<th>Classification</th>
									<th>Valuation Class</th>
									<th>Material Group</th>
									<th>Material Type</th>
									<th>Price Control</th>
									<th>Request Status</th>
								</tr>
							</thead>
			              	<tbody>
			              		<?php
				              	foreach($acc as $dt){
									if($dt->na=='n'){
										echo "<tr>";
										echo "<td>".$dt->id_item_group." - ".$dt->group_description."</td>";
										echo "<td>".$dt->code." - ".$dt->description."</td>";
										echo "<td>".$dt->classification_name."</td>";
										echo "<td>".$dt->bklas." - ".$dt->bkbez."</td>";
										echo "<td>".$dt->matkl." - ".$dt->wgbez."</td>";
										echo "<td>".$dt->mtart." - ".$dt->mtbez."</td>";
										if(($dt->price_control!='')and($dt->req=='n')){
											echo "<td>".$dt->price_control_name."</td>";	
										}else{
											$ck_s = ($dt->price_control=='S')?"selected":"";
											$ck_v = ($dt->price_control=='V')?"selected":"";
											echo "<td>";
											echo "	<select class='form-control select2modal' name='price_control' id='price_control' data-id_item_name='".$dt->id_item_name."'  required='required'>";
											echo "		<option value='0'>-Chose Price Control-</option>";
											echo "		<option value='S' $ck_s>Standard Price</option>";
											echo "		<option value='V' $ck_v>Moving Price</option>";
											echo "	</select>";
											echo "</td>";
										}
										echo "<td>".$dt->label_req."</td>";
										echo "</tr>";
									}
				              	}
				              	?>
			              	</tbody>
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
								<form role="form" class="form-transaksi-acc">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Confirm Price Control</h4>
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
												<th>Valuation Class</th>
												<th>Material Group</th>
												<th>Classification</th>
												<th>Price Control</th>
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
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/material/transaksi/acc.js"></script>
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