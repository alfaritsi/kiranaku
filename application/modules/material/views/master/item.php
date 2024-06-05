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
			<div class="col-sm-8">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<?php 
						if(base64_decode($this->session->userdata("-nik-"))=='7143'){
							echo'<div class="btn-group pull-right"><button type="button" class="btn btn-info" id="imp_button">Import Excel</button></div>';	
						}
						?>
	          		</div>
	          		<!-- /.box-header -->
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-4">
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
		            	</div>
		            </div>					
					<!-- /.box-filter -->					
		          	<div class="box-body">
						<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Item Group</th>
				              	<th>Item Name Code</th>
				              	<th>Item Name Desc</th>
				              	<th>Valuation Class</th>
				              	<th>Material Group</th>
				              	<th>Classification</th>
								<th>Jasa</th>
								<th>Request Status</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($item as $dt){
									$no++;
									$bkbez	= ($dt->bklas!=0)?$dt->bklas."-".$dt->bkbez:"-";
									echo "<tr>";
				              		echo "<td>".$dt->id_item_group."-".strtoupper($dt->group_description)."</td>";
				              		echo "<td>".$dt->code."</td>";
				              		echo "<td>".strtoupper($dt->description)."</td>";
				              		echo "<td>".strtoupper($bkbez)."</td>";
				              		echo "<td>".strtoupper($dt->matkl)."-".strtoupper($dt->wgbez)."</td>";
				              		echo "<td>".strtoupper($dt->classification_name)."</td>";
				              		echo "<td>".$dt->label_jasa."</td>";
				              		echo "<td>".$dt->label_req."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
												echo"<ul class='dropdown-menu pull-right'>";	
												if(($dt->na == 'n')and($dt->req == 'n')){ 
													echo "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_item_name."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
												}
												if(($dt->na == 'n')and($dt->req == 'y')){ 
													if($dt->jumlah == 0){
														echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_item_name."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
													}
													echo "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_item_name."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
												}
												if($dt->na == 'y'){
													echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_item_name."'><i class='fa fa-check'></i> Set Aktif</a></li>";
												}
												echo"</ul>";
									echo " 	
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
		              	<h3 class="box-title title-form">Form Master Item Name</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Item Name</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-item">
	            		<div class="box-body">
							<div class="form-group">
								<label for="id_item_group">Item Group</label>
								<select class="form-control select2" name="id_item_group" id="id_item_group"  required="required">
									<?php
										echo "<option value='0'>Pilih Item Group</option>";
										foreach($group as $dt){
											echo"<option value='".$dt->id_item_group."'>".$dt->description."</option>";
										}
									?>
								</select>
		                	</div>
		              		<div class="form-group">
		                		<label for="code">Item Name Code</label>
		                		<input type="text" class="form-control" name="code" id="code" placeholder="Input Name Code" required="required" readonly>
		                	</div>
							<!--
		              		<div class="form-group">
		                		<label for="id_item_name">Item Name ID</label>
		                		<input type="text" class="form-control" name="id_item_name" id="id_item_name" placeholder="Input Name ID" required="required" readonly>
		                	</div>
							-->
							<div class="form-group">
								<label for="description">Item Name Desc</label>
		                		<input style="text-transform: uppercase" type="text" class="form-control" name="description" id="description" placeholder="Input Description" required="required">
		                	</div>
		              		<div class="form-group">
								<label for="classification">Classification</label>
								<select class="form-control select2" name="classification" id="classification">
									<?php
										echo "<option value='0'>Pilih Classification</option>";
										echo "<option value='A'>[A] Asset</option>";
										echo "<option value='E'>[E] Expense</option>";
										echo "<option value='I'>[I] Inventory</option>";
										echo "<option value='IE' selected>[IE] Inventory Expense</option>";
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="bklas">Valuation Class</label>
								<select class="form-control select2" name="bklas" id="bklas"  required="required">
									<?php
										echo "<option value='NULL'>Pilih Valuation Class</option>";
										foreach($bklas as $dt){
											echo"<option value='".$dt->bklas."'>".$dt->bklas."-".$dt->bkbez."</option>";
										}
									?>
								</select>
		                	</div>
							<div class="form-group">
								<label for="matkl">Material Group</label>
								<select class="form-control select2" name="matkl" id="matkl"  required="required">
									<?php
										echo "<option value='0'>Pilih Material Group</option>";
										foreach($matkl as $dt){
											echo"<option value='".$dt->matkl."'>".$dt->matkl."-".$dt->wgbez."</option>";
										}
									?>
								</select>
		                	</div>
							<div class="form-group">
								<label for="jasa">Jasa</label>
								<select class="form-control select2" name="jasa" id="jasa"  required="required">
									<?php
										echo "<option value='0'>Pilih Jasa</option>";
										echo "<option value='y'>Ya</option>";
										echo "<option value='n'>Tidak</option>";
									?>
								</select>
		                	</div>
		            	</div>
		            	<div class="box-footer">
							<input id="id_item_name" name="id_item_name" type="hidden">
							<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>
			<!--modal imp-->
			<div class="modal fade" id="imp_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-mg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-master-item-imp">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Import Data Excel</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<div class="row">
												<div class="col-xs-12">
													<label for="file_excel">Upload File Excel</label>
													<input type="file" class="form-control" name="file_excel" id="file_excel">
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
			<!--modal imp sampai sini-->
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/material/master/item.js"></script>
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