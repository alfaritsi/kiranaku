<!--
/*
@application  : KODE MATERIAL
@author       : Lukman Hakim (7143)
@contributor  : 
      1. Airiza Yuddha (7849) 14 okt 2020
         modified view table - add field estimate price  
      2. Airiza Yuddha (7849) 21 okt 2020
         a. add field spec_desc       
      3. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->

<?php $this->load->view('header') ?>
<style type="text/css">
	.text_right{
	    text-align:right;
	}
</style>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-success" id="excel_button">Export To Excel</button>
							<button type="button" class="btn btn-info" id="add_button">Add Material Code</button>
							<button type="button" class="btn btn-success" id="req_button">Request ke HO</button>
                        </div>						
						
						<!--
						<button type="button" class="btn btn-sm btn-default pull-right" id="add_button">Add Material Code</button> 
						<button type="button" class="btn btn-sm btn-success pull-right" id="req_button">Request ke HO</button> 
						-->
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label>Tanggal Dari :</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
										<input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" value="<?php echo date('d.m.Y', strtotime('-1 month'));?>" id="filter_from" name="filter_from" readonly>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Tanggal Sampai :</label>
									<div class="input-group date">
										<div class="input-group-addon"><i class="fa fa-calendar"></i></div> 
										<div id="div_filter_to">
										<input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" value="<?php echo date('d.m.Y');?>" id="filter_to" name="filter_to" readonly>
										</div>
									</div>
								</div>
							</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Request Status: </label> 
				                	<select class="form-control select2" multiple="multiple" id="filter_request_status" name="request_status[]" style="width: 100%;" data-placeholder="Pilih Request Status">
				                  		<?php
											echo "<option value='x'>Declined</option>";
											echo "<option value='o'>Pending Request</option>";
											echo "<option value='y'>Requested</option>";
											echo "<option value='n'>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_status" name="status[]" style="width: 100%;" data-placeholder="Pilih Status">
				                  		<?php
											echo "<option value='n' selected>Aktif</option>";
											echo "<option value='y'>Non Aktif</option>";
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
									<th>Request Date</th>
									<th>Confirm Date</th>
									<th>Type</th>
									<th>Description</th>
									<th>UOM</th>
									<th>Estimate <br>Price (Rp)</th>
									<th>Estimate <br>Price (Rp)</th>
									<th>Images</th>
									<th>Material Code</th>
									<th>Material Description</th>
									<th>Request Status</th>
									<th>PIC</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
			              	<tbody>
			              		<?php
				              	foreach($request as $dt){
				              		$tanggal_req 	= $dt->req != 'o' ? $dt->tanggal."<br>".$dt->jam_buat : "-";
				              		$tanggal_conf 	= $dt->req != 'o' ? $dt->tanggal_conf."<br>".$dt->jam_conf : "-";
									$code_spec		= ($dt->code!=NULL)?$dt->code:$dt->code_spec;
									$classification	= ($dt->req=='n')?$dt->label_classification:"";
									$spec_desc		= ($dt->spec_desc!=NULL)?$dt->spec_desc:$dt->spec_desc_sap;
				              		$price_show = $dt->estimate_price != "" && $dt->estimate_price != null ? number_format($dt->estimate_price,0,',','.') : "-";
				              		$price_hide = $dt->estimate_price != "" && $dt->estimate_price != null ? $dt->estimate_price : "-";
									echo "<tr>";
				              		echo "<td>".$tanggal_req."</td>";
				              		echo "<td>".$tanggal_conf."</td>";
				              		echo "<td>".$dt->type."</td>";
				              		echo "<td>".$dt->description."</td>";
				              		echo "<td>".$dt->uom."</td>";
				              		echo "<td class='text_right'>".$price_show."</td>";
				              		echo "<td class='text_right'>".$price_hide."</td>";
									echo "<td>";
										$list_gambar = explode("|", substr($dt->list_gambar,0,-1));
										foreach ($list_gambar as $l) {
											if(!empty($l)){
												echo "<img src='$l' class='img-thumbnail' style='height:80px;'>";	
											}
										}
									echo "</td>";
									echo "<td>".$code_spec."<br>".$classification."</td>";
									echo "<td>".$spec_desc."</td>";
				              		echo "<td>".$dt->label_request."</td>";
				              		echo "<td>".$dt->nama_pic."-".$dt->nik_pic."</td>";
									echo "<td>".$dt->label_status."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
													if($dt->req == 'o'){
														echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_item_request."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
														echo "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_item_request."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
													}else{
														echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_item_request."' data-btn_save='hidden'><i class='fa fa-search'></i> Detail</a></li>";
													}
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_item_request."'><i class='fa fa-check'></i> Set Aktif</a></li>";
												}
									echo " 	</ul>
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
			
			<!--modal edit-->
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-request">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Form Request Material Code</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">		
											<label for="type">Type</label>
											<select class="form-control select2modal" name="type" id="type"  required="required" <?php echo $_POST['disabled']; ?>>
												<?php
													echo "<option value='0'>Pilih Type ".$_POST['disabled']."</option>";
													echo "<option value='Barang'>Barang</option>";
													echo "<option value='Jasa'>Jasa</option>";
												?>
											</select>
										</div>
										<div class="form-group">	
											<label for="description">Description</label>
											<input type="text" class="form-control" name="description" id="description" placeholder="Description"  required="required">
										</div>
										<div class="form-group">	
											<label for="uom">UOM</label>
											<input type="text" class="form-control" name="uom" id="uom" placeholder="UOM"  required="required">
										</div>
										<div class="form-group">	
											<label for="estimate_price">Estimasi Harga Satuan (Rp)</label>
											<input type="text" class="form-control angkas" name="estimate_price" id="estimate_price" placeholder="Estimasi Harga"  required="required">
										</div>
										<div class="form-group">	
											<label for="gambar">Images</label>
											<div id="show_images"></div>
											<input type="file" multiple="multiple" class="form-control" id="gambar" name="gambar[]" required="required">
										</div>
									</div>
									<div class="modal-footer">
										<input id="id_item_request" name="id_item_request" type="hidden">
										<button id="btn_save" type="button" class="btn btn-primary" name="action_btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--modal req-->
			<div class="modal fade" id="req_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-request-ho">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Confirm Request to HO</h4>
									</div>
									<div class="modal-body">
										<table id="periode_detail" class="table table-bordered datatable-periode">
											<thead>
												<th>
													<div class="checkbox">
														<center><label><input type="checkbox" class="selectALL"></label></center>
													</div>
												</th>
												<th width="10%">Tanggal</th>
												<th>Type</th>
												<th width="60%">Description</th>
											</thead>
											<tbody id="show_detail">
											</tbody>
										</table>
									</div>
									<div class="modal-footer">
										<input id="count" name="count" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_ho">Proses</button>
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
<script src="<?php echo base_url() ?>assets/apps/js/material/transaksi/request.js"></script>
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