<!--
/*
@application    : PCS (Production Cost Simulation)
@author 		: Akhmad Syaiful Yamang (8347)
@contributor	: 
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
			<div class="col-sm-3">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
					</div>
					<form role="form" class="form-calculate-pcs">
						<div class="box-body">
							<div class="form-group">
								<label for="formula">Grouping</label>
								<select class="form-control select2" name="grouping" id="grouping" required="required">
									<option value='standart'>Standar COA</option>
									<option value='pegrup'>PE Grup</option>
								</select>
							</div>
							<div class="form-group">
								<label for="formula">Plant</label>
								<select class="form-control select2" name="plant" id="plant" required="required">
									<?php
										echo "<option value='0'>Silahkan pilih plant</option>";
										foreach($plant as $dt){
											echo "<option value='".$dt->plant."'";
											echo ">".$dt->plant_name."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="formula">Bulan.Tahun</label>
								<input type="text" name="bln_thn" id="bulan" class="form-control kiranadatepicker" data-format="mm.yyyy" data-startview="months" data-minviewmode="months" data-autoclose="true" data-enddate="<?php echo date('Y-m-d'); ?>" value="<?php echo date('m').'.'.date('Y'); ?>" required="required">
							</div>
							<div class="form-group">
								<label for="formula">Jumlah produksi SIR (TON)</label>
								<input type="text" name="jml_prod_SIR" class="form-control angka cek_max" required="required">
							</div>
							<div class="form-group">
								<label for="formula">Konsumsi Listrik LWBP</label>
								<input type="text" name="listrik_lwbp" class="form-control angka" required="required" readonly="readonly">
							</div>
							<div class="form-group">
								<label for="formula">Konsumsi Listrik WBP</label>
								<input type="text" name="listrik_wbp" class="form-control angka" required="required" readonly="readonly">
							</div>
						</div>
						<div class="box-footer">
							<button type="button" name="action_btn" class="btn btn-success">Proses</button>
						</div>
					</form>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><strong>Hasil <?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered my-datatable-extends-order">
							<thead>
								<th>Group</th>
								<th>COA</th>
								<th>Nama Account</th>
								<th class="text-left">Jumlah</th>
								<th class="text-center">Satuan<br>Jumlah</th>
								<th class="text-left">Nilai Simulasi Biaya (IDR)</th>
								<th class="text-left">Biaya per KG (IDR)</th>
								<th class="text-left" style="display: none">SUM Nilai Simulasi Biaya</th>
								<th class="text-left" style="display: none">SUM Biaya per KG</th>
								<th class="text-left" style="display: none">Formula</th>
								<th class="text-left" style="display: none">Norma</th>
							</thead>
							<tbody>
							</tbody>
							<tfoot>
								<tr>
									<th class="text-right" colspan="5">TOTAL</th>
									<th class="text-right"></th>
									<th class="text-right"></th>
								</tr>
								<tr>
									<th class="text-right" colspan="5">TOTAL (exclude DEPO)</th>
									<th class="text-right"></th>
									<th class="text-right"></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pcs/calculate.js"></script>
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
	tr.group{
		background-color: #ddd !important;
	}
	tr.group:hover {
		background-color: #999 !important;
		color: white;
		cursor: pointer;
	}
</style>
