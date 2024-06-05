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
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<form role="form" class="form-production-cost" name="form-production-cost" enctype="multipart/form-data">
			<div class="col-sm-8">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-primary" name="action_btn">Simpan</button>
						</div>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
						<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Plant</th>
				              	<th>Factory</th>
				              	<th>Production Cost</th>
								<th>Notes</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($cost as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$this->generate->kirana_decrypt($dt->WERKS)."</td>";
				              		echo "<td>".$dt->TPPCO."</td>";
				              		echo "<td>
											<input type='hidden' class='form-control' name='werks_".$dt->WERKS."' id='werks_".$dt->WERKS."' value='".$this->generate->kirana_decrypt($dt->WERKS)."'>
											<input type='hidden' class='form-control' name='tppco_".$dt->WERKS."' id='tppco_".$dt->WERKS."' value='".$dt->TPPCO."'>
											<input type='text' class='form-control angka text-right' name='cost_".$dt->WERKS."' id='cost_".$dt->WERKS."' value='".number_format($dt->cost,0,'.',',')."' placeholder='Cost'>
											</td>";
				              		echo "<td><input type='text'  size='40' class='form-control' name='note_".$dt->WERKS."' id='note_".$dt->WERKS."' value='".$dt->note."' placeholder='Notes'></td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>
												<li><a href='javascript:void(0)' class='history' data-edit='".$dt->WERKS."' data-plant='".$this->generate->kirana_decrypt($dt->WERKS)."'><i class='fa fa-h-square'></i> History</a></li>
											</ul>
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
			</form>
		</div>
	</section>
</div>
<!--modal history-->
<div class="modal fade" id="show_history" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-input">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel"><b>Historical Production Cost</b></h4>
						</div>
						<div class="modal-body">
							<div id='data_history'></div>									
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spot/master/cost.js"></script>
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