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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/jquery.dataTables.min.css">
<div class="content-wrapper">
	<section class="content">
		<div id="show_filter">
			<div class="row">
				<form role="form" class="form-production-cost-simulasi" name="form-production-cost-simulasi" enctype="multipart/form-data">
				<div class="col-sm-12">
					<div class="box box-success">
						<div class="box-header">
							<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="row">
								<div class="col-xs-4">
									<fieldset class="fieldset-info">
										<legend>Port Of Load Price</legend>
										<div id="filter_port"></div>
									</fieldset>
								</div>
								<div class="col-xs-4">
									<fieldset class="fieldset-info">
										<legend>Interest Percentage</legend>
										<div class="form-group">
											<label for="libor_rate">LIBOR Rate</label>
											<div class="input-group">
												<?php 
												if(!empty($_SESSION['-libor_rate-'])){	
													echo '<input type="text" class="form-control angka text-right" name="libor_rate" id="libor_rate" value="'.round($_SESSION['-libor_rate-'],2).'" required="required">';
												}else{
													echo '<input type="text" class="form-control angka text-right" name="libor_rate" id="libor_rate" value="" required="required">';
												}
												?>
												<span class="input-group-addon">p.o</span>
											</div>							
										</div>
										<div class="form-group">
											<label for="interest_rate">Interest Rate</label>
											<div class="input-group">
												<?php 
												if(!empty($_SESSION['-interest_rate-'])){
													echo '<input type="text" class="form-control angka text-right" name="interest_rate" id="interest_rate" value="'.round($_SESSION['-interest_rate-'],2).'" required="required">';
												}else{
													echo '<input type="text" class="form-control angka text-right" name="interest_rate" id="interest_rate" value="" required="required">';
												}
												?>
												<span class="input-group-addon">p.o</span>
											</div>							
										</div>
										<div class="form-group">
											<label for="interest">Interest</label>
											<div class="input-group">
												<?php 
												if(!empty($_SESSION['-interest_rate-'])){
													echo '<input type="text" class="form-control angka text-right" name="interest" id="interest" value="'.round($_SESSION['-interest-'],2).'" disabled>';
												}else{
													echo '<input type="text" class="form-control angka text-right" name="interest" id="interest" value="" disabled>';
												}
												?>
												<span class="input-group-addon">p.o</span>
											</div>							
										</div>
									</fieldset>
									<div class="form-group">
										<div class="input-group">
										</div>							
									</div>
									<div class="form-group">
										<label for="sales">SICOM Price</label>
										<div class="input-group">
											<input type="text" class="form-control angka text-right" name="sicom" id="sicom" required="required">
											<span class="input-group-addon">USC/KG</span>
										</div>							
									</div>
									<div class="form-group">
										<label for="buyer">Buyer</label>
										<select class="form-control select2" id="buyer" name="buyer" style="width: 100%;" data-placeholder="Pilih Buyer"  required="required">
										</select>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="form-group">
										<label for="year">1 Year</label>
										<div class="input-group">
											<input type="text" class="form-control text-right" name="years" id="years" value="360" disabled>
											<span class="input-group-addon">Days</span>
										</div>							
									</div>
									<div class="form-group">
										<label for="sales">Sales</label>
										<div class="input-group">
											<input type="text" class="form-control" name="sales" id="sales" required="required">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>							
									</div>
									<div class="form-group">
										<label for="days">Additional Days</label>
										<div class="input-group">
											<input type="text" class="form-control text-right" name="days" id="days" disabled>
											<span class="input-group-addon">Days</span>
										</div>							
									</div>
									<div class="form-group">
										<label for="currency_rate">Currency Rate (IDR - USD)</label>
										<div class="input-group">
											<input type="text" class="form-control text-right" name="currency_rate" id="currency_rate" disabled>
											<span class="input-group-addon">IDR</span>
										</div>							
									</div>
									<div class="form-group">
										<label for="no_urut">Production Cost Type</label>
										<select class="form-control select2" name="type" id="type" required="required">
											<option value='budget'>Master Budget</option>
											<option value='outlook'>Outlook</option>
										</select>
									</div>
									<div class="form-group">
										<label for="no_urut"></label>
										<div class="btn-group pull-right">
											<button type="button" class="btn btn-primary" name="action_btn">Proses</button>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>

										

		<div id="show_simulasi"></div>
		<div id="show_confirmation"></div>
	</section>
</div>
<!--modal history-->
<div class="modal fade" id="show_konfirmasi_email" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-mg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel"><b>Send Email Confirmation</b></h4>
					</div>
					<div class="modal-body">
						<div id='data_konfirmasi_email'></div>									
					</div>
					<div class="modal-footer" align="center">
						<button type="button" data-dismiss="modal" class="btn btn-primary" name="kirim_email" id="kirim_email_yes">Yes</button>
						<button type="button" data-dismiss="modal" class="btn" id="kirim_email_no">No</button>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spot/transaksi/simulasi.js"></script>
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