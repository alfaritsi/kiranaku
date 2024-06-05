<!--
/*
@application  : Simulasi Penjualan SPOT
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-success" id="excel_button">Export To Excel</button>
							<button type="button" class="btn btn btn-info" id="imp_button">Import Excel</button>
                        </div>						
						
						
						<?php 
							// echo'<div class="btn-group pull-right"><button type="button" class="btn btn-info" id="imp_button">Import Excel</button></div>';	
						?>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
									<select class="form-control select2" multiple="multiple" id="plant" name="plant[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
									</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Tahun: </label>
				                	<select class="form-control select2" multiple="multiple" id="tahun" name="tahun[]" style="width: 100%;" data-placeholder="Pilih Tahun">
				                  		<?php
											for ($tahun = 2019; $tahun <= date('Y')+1; $tahun++) {
												if($tahun==date('Y')){
													echo "<option value='".$tahun."' selected>".$tahun."</option>";
												}else{
													echo "<option value='".$tahun."'>".$tahun."</option>";
												}
											}											
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Buyer: </label>
									<select class="form-control select2" multiple="multiple" id="buyer" name="buyer[]" style="width: 100%;" data-placeholder="Pilih Buyer">
									</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="status" name="status[]" style="width: 100%;" data-placeholder="Pilih Status">
										<option value='1'>Sent</option>
										<option value='0'>Failed</option>
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
									<th>Date</th>
									<th>Form No</th>
									<th>Contract No</th>
									<th>Buyer</th>
									<th>factory</th>
									<th>QTY (MT)</th>
									<th>Contract<br>Month</th>
									<th>Product<br>Grade</th>
									<th>Port</th>

									<th>Selling<br>Price<br>(USC/KG)</th>
									<th>Kurs<br>Price<br>(IDR/USD)</th>
									<th>Selling<br>Price<br>(IDR/KG)</th>
									<th>Harga Modal<br>SPOT<br>(IDR/KG)</th>
									<th>Deal Harga<br>Pembelian<br>(IDR/KG)</th>

									<th>Prod Cost<br>(IDR/KG)</th>
									<th>Trucking Cost<br>(IDR/KG)</th>
									<th>Carry Cost<br>(IDR/KG)</th>
									<th>Margin<br>(IDR/KG)</th>
									<th>SICOM</th>

									<th>Amount (IDR)</th>
									<th>Margin After Packing Disc (IDR/KG)</th>
									<th>Amount After Packing Disc (IDR)</th>
									<th>Remark</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
		</div>
	</section>
</div>

<!--modal set kondisi-->
<div class="modal fade" id="detail_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="nav-tabs-custom" id="tabs-edit">
					<form role="form" class="form-transaksi-kondisi" enctype="multipart/form-data">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel"><b>Nomor Form</b></h4>
						</div>
						<div class="modal-body" id="show_detail">
							
						</div>
					</form>
				</div>
			</div>
			
		</div>
	</div>	
</div>

<!--modal imp-->
<div class="modal fade" id="imp_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-mg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-spot-imp">
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


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spot/transaksi/detail.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>


<style>
    .small-box .icon {
        top: -13px;
    }

    .select2-container--open {
        z-index: 9999999
    }
</style>