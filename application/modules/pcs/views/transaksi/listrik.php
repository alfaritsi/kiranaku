<!--
/*
@application    : PCS (Production Cost Simulation)
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

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header">
		        		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
		      		</div>
					<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i> Month/ Year
									</div>
									<input type="text" name="bulan" id="bulan" class="form-control kiranadatepicker" data-format="mm.yyyy" data-startview="months" data-minviewmode="months" data-autoclose="true" data-enddate="<?php echo date('Y-m-d'); ?>" value="<?php echo date('m').'.'.date('Y'); ?>" required="required">
								</div>							
			            	</div>
						</div>	
					</div>
		      		<div class="box-body">
		      			<table class="table table-bordered my-datatable-extends-order">
		              		<thead>
				              	<th>Kode</th>
				              	<th>Pabrik</th>
				              	<th>Month/ Year</th>
				              	<th>LWBP</th>
				              	<th>WBP</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($listrik as $dt){
									$lwbp = ($dt->lwbp!=NULL)?number_format($dt->lwbp,0,'.',','):"";
									$wbp = ($dt->wbp!=NULL)?number_format($dt->wbp,0,'.',','):"";
									echo "<tr>";
				              		echo "<td>".$dt->plant."</td>";
				              		echo "<td>".$dt->plant_name."</td>";
				              		echo "<td>".$dt->bulan."</td>";
				              		echo "<td><input type='text' value='$lwbp' class='form-control angka nilai_lwbp' data-plant='".$dt->plant."' data-bulan='".$dt->bulan."'></td>";
				              		echo "<td><input type='text' value='$wbp' class='form-control angka nilai_wbp' data-plant='".$dt->plant."' data-bulan='".$dt->bulan."'></td>";
									echo "</tr>";
				              	}
				              	?>
			              	</tbody>
			            </table>
		      		</div>
	      		</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pcs/transaksi/listrik.js"></script>
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
