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
		<div class="row">
			<form role="form" class="form-production-cost-email" name="form-production-cost-email" enctype="multipart/form-data">
				<div class="col-sm-12">
					<div class="box box-success">
						<div class="box-header">
						<h3 class="box-title"><strong>Send Sales Confirmation</strong></h3>
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-danger" name="action_btn_cancel">Back</button>
							<button type="button" class="btn btn-primary" name="action_btn_email_konfirmasi">Send Email</button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-xs-12">
								<?php 
								$no = 0;
				              	foreach($sales as $dt){
									$no++;
									echo			'			<fieldset class="fieldset-info">';
									echo			'				<legend>Sales Confirmation '.$no.'</legend>';
									echo			'				<div class="row">';
									echo			'					<div class="col-xs-5">';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="sicom">SICOM Price</label></div>';
									echo			'								<div class="col-xs-7">';
									echo			'									<input type="text" class="form-control" name="sicom_'.$dt->id_simulate.'" required="required" value="'.$dt->sicom.'" readonly>';
									echo			'									<input type="hidden" class="form-control" name="plant_selected" id="plant_selected" value="'.$dt->id_simulate.'">';
									echo			'									<input type="hidden" class="form-control" name="id_simulate_selected" id="plant_selected" value="'.$dt->id_simulate.'">';
									echo			'									<input type="hidden" class="form-control" name="mode" value="edit">';
									echo			'								</div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="no_form">Form No</label></div>';
									echo			'								<div class="col-xs-7">';
									echo			'									<input type="text" class="form-control" name="no_form_'.$dt->id_simulate.'" value="'.$dt->no_form.'" required="required" readonly>';
									echo			'								</div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="buyer">Buyer</label></div>';
									echo 			'								<div class="col-xs-7">';
									echo			'									<input type="text" class="form-control" name="buyer_'.$dt->id_simulate.'" id="buyer" value="'.$dt->buyer.'" readonly>';	
									echo			'								</div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'									<div class="col-xs-5"><label for="buyer">Factory</label></div>';
									echo			'								<div class="col-xs-7">';
									echo			'									<input type="text" class="form-control" name="factory_'.$dt->id_simulate.'" id="factory" value="'.$dt->factory.'" readonly>';
									echo			'								</div>';	
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="buyer">Factory Code</label></div>';
									echo			'								<div class="col-xs-7"><input type="text" class="form-control" name="factory_code_'.$dt->id_simulate.'" id="factory_code_'.$dt->id_simulate.'" value="'.$dt->tppco.'" readonly></div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="prod_grade_det">Product Grade</label></div>';
									echo			'								<div class="col-xs-7">';
									echo			'									<select class="form-control select2" name="prod_grade_'.$dt->id_simulate.'" id="prod_grade_det">';
									echo			'										<option value="">Pilih Product Grade</option>';
									$grade = 'SIR-0010,SIR-0020,SIR-10MR,SIR-20CP,SIR-20MR,SIR-20VK';
									$arr_grade = explode(',', $grade);
									foreach ($arr_grade as $values){
										$ck	= ($values==$dt->prod_grade)?"selected":"";	
										echo		'										<option value="'.$values.'" '.$ck.'>'.$values.'</option>';
									}									
									echo			'									</select>';
									echo			'								</div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="qty">Quantity</label></div>';
									echo			'								<div class="col-xs-7">';
									echo			'									<div class="input-group">';
									echo			'										<input type="text" class="form-control angka text-right" name="qty_'.$dt->id_simulate.'" id="qty_det" value="'.$dt->qty.'" required="required">';
									echo			'										<span class="input-group-addon">MT</span>';
									echo			'									</div>';	
									echo			'								</div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="buyer">Margin</label></div>';
									echo			'								<div class="col-xs-7">';
									echo			'									<div class="input-group">';
									echo			'										<input type="text" class="form-control angka text-right" name="margin_'.$dt->id_simulate.'" id="margin_'.$dt->id_simulate.'" value="'.$dt->margin.'" readonly>';
									echo			'										<span class="input-group-addon">IDR/KG</span>';
									echo			'									</div>';
									echo			'								</div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'					</div>';
									echo			'					<div class="col-xs-2"></div>';
									echo			'					<div class="col-xs-5">';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="buyer">Distribution Channel</label></div>';
									echo			'								<div class="col-xs-7">';
									echo			'									<select class="form-control select2" name="distribution_channel_'.$dt->id_simulate.'">';
									echo			'										<option value="">Distribution Channel</option>';
									$arr_dc = explode(',', $dt->list_dc);
									foreach ($arr_dc as $values){
										$ck	= ($values==$dt->distribution_channel)?"selected":"";	
										echo		'										<option value="'.$values.'" '.$ck.'>'.$values.'</option>';
									}									
									echo			'									</select>';
									echo			'								</div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="cntty">Contract Type</label></div>';
									echo			'								<div class="col-xs-7">';
									echo			'									<select class="form-control select2" name="contract_type_'.$dt->id_simulate.'">';
									echo			'										<option value="">Contract Type</option>';
									$ct = 'SPOT-R,SPOT-F,SPOT-O';
									$arr_ct = explode(',', $ct);
									foreach ($arr_ct as $values){
										$ck	= ($values==$dt->contract_type)?"selected":"";	
										echo		'										<option value="'.$values.'" '.$ck.'>'.$values.'</option>';
									}									
									echo			'									</select>';
									echo			'								</div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="buyer">Shipment Period</label></div>';
									echo			'								<div class="col-xs-7"><input type="text" class="form-control" name="shipment_periode_'.$dt->id_simulate.'" id="shipment_periode_'.$dt->id_simulate.'" value="'.$dt->shipment_periode.'" readonly></div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="buyer">Shipment Term</label></div>';
									echo			'								<div class="col-xs-7"><input type="text" class="form-control" name="shipment_term_'.$dt->id_simulate.'" id="shipment_term_'.$dt->id_simulate.'" value="'.$dt->shipment_term.'" readonly></div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="buyer">Price</label></div>';
									echo			'								<div class="col-xs-7">';
									echo			'									<div class="input-group">';
									echo			'										<input type="text" class="form-control angka text-right" name="price_'.$dt->id_simulate.'" id="price_'.$dt->id_simulate.'" value="'.$dt->price.'" readonly>';
									echo			'										<span class="input-group-addon">USC/KG</span>';
									echo			'									</div>';
									echo			'								</div>';
									echo			'							</div>	';
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-5"><label for="buyer">Notes</label></div>';
									echo			'								<div class="col-xs-7"><textarea name="note_'.$dt->id_simulate.'" id="note_det" class="form-control" rows="3" placeholder="Notes">'.$dt->note.'</textarea></div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-12"></div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'						<div class="form-group">';
									echo			'							<div class="row">';
									echo			'								<div class="col-xs-12"><label for="libor_rate">* SICOM, Form No, Margin hanya dikirimkan kepada pihak internal perusahaan</label></div>';
									echo			'							</div>';	
									echo			'						</div>';
									echo			'					</div>';
									echo			'				</div>';	
									echo			'			</fieldset>';			
								}
								?>
							</div>
						</div>						
					</div>
				</div>
				</div>
			</form>
		</div>
	</section>
</div>
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
<script src="<?php echo base_url() ?>assets/apps/js/spot/transaksi/sales_edit.js"></script>
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