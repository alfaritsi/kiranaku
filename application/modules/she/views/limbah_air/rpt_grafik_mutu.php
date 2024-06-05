<!--
/*
@application    : SHE 
@author 		: Lukman Hakim (7143)
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
<!--devexpress-->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.spa.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.common.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.light.css">

<div class="content-wrapper">
	<section class="content">
	    <div class="row">
	    	<div class="col-sm-12">
	    		<div class="box box-success">
		          	<div class="box-header">
		            	<h3 class="box-title"><strong>Grafik Baku Mutu</strong></h3>
		          	</div>
		          	<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
							<div class="col-md-3">
								<div class="form-group">
								  <label>Pabrik :</label>
								  <select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;">
									<?php
									  foreach ($pabrik as $pabrik) {
										echo "<option value='".$pabrik->id_pabrik."'>".$pabrik->nama."</option>";
									  }
									?>
								  </select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
								  <label>From :</label>
								  <div class="input-group date">
									<div class="input-group-addon">
									  <i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control monthPicker" placeholder="mm.yyyy" id="dari" name="dari">
								  </div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
								  <label>To :</label>
								  <div class="input-group date">
									<div class="input-group-addon">
									  <i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control monthPicker" placeholder="mm.yyyy" id="sampai" name="sampai">
								  </div>
								</div>
							</div>
						</div>	
		            </div>
					<div class="box-body">
						<div id="sales"></div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-6">
								<div id="chart_cod"></div>
							</div>
							<div class="col-md-6">
								<div id="chart_bod"></div>
							</div>
							<div class="col-md-6">
								<div id="chart_tss"></div>
							</div>
							<div class="col-md-6">
								<div id="chart_ammonia"></div>
							</div>
							<div class="col-md-6">
								<div id="chart_nitro"></div>
							</div>
						</div>	
					</div>
		        </div>
	    	</div>
	    </div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_grafik_mutu.js"></script>
<!--devexpress-->
<script src="<?php echo base_url() ?>assets/plugins/devexpress/jszip.min.js"></script>

<script src="<?php echo base_url() ?>assets/plugins/devexpress/dx.all.js"></script>

<style>
.small-box .icon{
    top: -13px;
}
</style>

