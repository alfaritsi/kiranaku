<!--
/*
@application  : BANK Specimen
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
					<form role="form" class="form-taksasi-nilai">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="nama">Nama Jadwal BOKIN</label>
									<input style="text-transform: uppercase" type="text" class="form-control" name="nama" id="nama" placeholder="Nama Jadwal" readonly>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="nama_tahap">Tahap</label>
									<input style="text-transform: uppercase" type="text" class="form-control" name="nama_tahap" id="nama_tahap" placeholder="Nama Tahap" readonly>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="pass_grade">Pass Grade</label>
									<input style="text-transform: uppercase" type="text" class="form-control" name="pass_grade" id="pass_grade" placeholder="Pass Grade" readonly>
								</div>
							</div>
		            	</div>
						<div class="row">
							<div class="col-sm-12">
								<!--<table class="table table-bordered table-striped my-datatable-extends-order">-->
								<table class="table table-bordered table-striped table-peserta">
									<thead>
										<tr>
											<th>Nama Peserta</th>
											<th>Nik</th>
											<?php 
											foreach($bobot as $dt){								
												echo"
													<th width='10%'>".$dt->nama_nilai."<br>(".$dt->bobot.")</th>
												";
											}		
											?>
											<th width='10%'>Grand Total</th>
											<th width='10%'>Status</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
						
		            </div>					
					<div class="box-footer">
						<input id="id_jadwal" name="id_jadwal" value="<?php echo $id_jadwal;?>" type="hidden">
						<button id="id_button" type="button" name="action_btn" class="btn btn-success">Simpan</button>
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/taksasi/transaksi/nilai.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>