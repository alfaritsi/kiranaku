<!--
	/*
		@application  :
		@author       : Akhmad Syaiful Yamang (8347)
		@date         : 01-Mar-19
		@contributor  :
			  1. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
	*/
-->
<?php $this->load->view('header') ?>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success page-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-lg-4 col-xs-6">
								<!-- small box -->
								<div class="small-box bg-aqua">
									<div class="inner">
										<h3>&nbsp;</h3>

										<p>PI Baru</p>
									</div>
									<div class="icon">
										<i class="fa fa-cart-arrow-down"></i>
									</div>
									<a href="javascript:void(0)" class="small-box-footer btn_detail_pi">Detail
										<i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-4 col-xs-6">
								<!-- small box -->
								<div class="small-box bg-green">
									<div class="inner">
										<h3>&nbsp;</h3>

										<p>Sales Order</p>
									</div>
									<div class="icon">
										<i class="fa fa-cubes"></i>
									</div>
									<a href="javascript:void(0)" class="small-box-footer btn_so_spk">Detail
										<i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
							<div class="col-lg-4 col-xs-6">
								<!-- small box -->
								<div class="small-box bg-red">
									<div class="inner">
										<h3>&nbsp;</h3>

										<p>SPK Terlambat</p>
									</div>
									<div class="icon">
										<i class="fa fa-calendar-times-o"></i>
									</div>
									<a href="javascript:void(0)" class="small-box-footer btn_spk_late">Detail
										<i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-6">
										<h4>Report Status Produksi</h4>
									</div>
									<div class="col-sm-6">
										<div class="form-group pull-right">
											<label>Filter : </label>
											&nbsp;
											<label>
												<input class="filter" type="checkbox" name="filter[]" id="filtter" value="mto">
												Make To Order
											</label>
											&nbsp;
											<label>
												<input class="filter" type="checkbox" name="filter[]" id="filtter" value="mts">
												Make To Stock
											</label>
										</div>
									</div>

									<!-- 									<div class="form-group from-horizontal">
										<div class="col-sm-1">
								        	<label>Filter:</label>
										</div>
										<div class="col-sm-2">
											<select class="form-control select2" name="spk" id="spk">
												<option value="" selected>ALL</option>
												<option value="mto">Make To Order</option>
												<option value="mts">Make To Stock</option>
											</select>
										</div>
							        </div> -->
								</div>

								<div class="clearfix"></div>
								<table class="table table-bordered table-hover" id="report_dashboard">
									<thead>
										<tr>
											<th rowspan="2">No PO</th>
											<th rowspan="2">No SO</th>
											<th colspan="2">Delivery<br>Date</th>
											<th rowspan="2">SPK</th>
											<th rowspan="2">Material</th>
											<th rowspan="2">Qty</th>
											<th rowspan="2">No GR</th>
											<th rowspan="2">Tanggal GR</th>
											<th rowspan="2">Status Produksi</th>
										</tr>
										<tr>
											<th>Request</th>
											<th>Plan</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/dashboard.js"></script>