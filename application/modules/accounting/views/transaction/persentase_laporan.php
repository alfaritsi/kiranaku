<!--
/*
@application    : Attachment Accounting 
@author 		: Matthew Jodi (8944)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<link rel='stylesheet' href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.css" />
<link rel='stylesheet' href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.theme.default.css" />

<style type="text/css">
.yellowcell {
    background-color: #ffc1078c;
}
.greencell {
    background-color: #00ff3a59;
}
.redcell {
    background-color: #ff001840;
}
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	            		<div class="clearfix"></div>

						<div class="col-md-6" style="margin-top: 20px; padding-left:0px;">
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<label>Tahun</label>
										<select class="form-control select2" id="filteryear" name="filteryear" style="width: 100%;">
											<?php
												foreach ($tahun as $dt) {
													echo "<option value='" . $dt->tahun . "'";
													if ($dt->tahun == date('Y')) {
														echo "selected";
													}
													echo ">" . $dt->tahun . "</option>";
												}
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label>Type</label>
										<select class="form-control select2" id="filtertype" name="filtertype" style="width: 100%;">
											<option value="HO">HO</option>
											<option value="BRANCH">PABRIK</option>
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<label style="visibility:hidden;">Filter</label>
									<div class="form-group">
										<button type="button" class="form-control btn btn-default btn-filter"> <i class="fa fa-search"></i> Filter</button>
									</div>
								</div>
							</div>
						</div>
			            
	          		</div>
	          		<!-- /.box-header -->

		          	<div class="box-body">		          		
						<table class="table table-bordered table-striped table-responsive table-hover my-datatable-extends-order">
							<thead>
								<th>HO</th>
								<th>Notes</th>
								<th>Jan</th>
								<th>Feb</th>
								<th>Mar</th>
								<th>Apr</th>
								<th>May</th>
								<th>Jun</th>
								<th>Jul</th>
								<th>Aug</th>
								<th>Sep</th>
								<th>Oct</th>
								<th>Nov</th>
								<th>Dec</th>
							</thead>
							<tbody></tbody>
							<tfoot>
								<th>HO</th>
								<th>Notes</th>
								<th>Jan</th>
								<th>Feb</th>
								<th>Mar</th>
								<th>Apr</th>
								<th>May</th>
								<th>Jun</th>
								<th>Jul</th>
								<th>Aug</th>
								<th>Sep</th>
								<th>Oct</th>
								<th>Nov</th>
								<th>Dec</th>
							</tfoot>
						</table>        	
			        </div>
				</div>
			</div>
		</div>


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datatables/plugins/rowgroup/dataTables.rowsGroup.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/accounting/transaction/persentase_laporan.js"></script>



