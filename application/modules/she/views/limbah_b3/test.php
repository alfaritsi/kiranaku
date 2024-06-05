<!--
/*
@application    : SHE 
@author 		: Syah Jadianto (8604)
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

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">

		          	<div class="box-body">
		           		<table width="100%" class="table table-bordered table-striped">
		              		<thead id="table_header">

				                <?php
									echo "<tr>";
										echo "<th class='text-center' colspan='7'>Limbah B3 Masuk</th>";
										echo "<th class='text-center' colspan='5'>Limbah B3 Keluar</th>";
										echo "<th class='text-center' colspan='2'>Saldo</th>";
									echo "</tr>";
									echo "<tr>";
										echo "<th class='text-center' rowspan='2'>No.</th>";
										echo "<th class='text-center' rowspan='2'>Jenis Limbah</th>";
										echo "<th class='text-center' rowspan='2'>Tanggal Masuk</th>";
										echo "<th class='text-center' rowspan='2'>Sumber Limbah</th>";
										echo "<th class='text-center' colspan='2'>Jumlah Limbah Masuk</th>";
										echo "<th class='text-center' rowspan='2'>Max. Masa Simpan</th>";
										echo "<th class='text-center' rowspan='2'>Tanggal Keluar</th>";
										echo "<th class='text-center' colspan='2'>Jumlah Limbah Keluar</th>";
										echo "<th class='text-center' rowspan='2'>Tujuan Penyerahan</th>";
										echo "<th class='text-center' rowspan='2'>Nomor Manifest</th>";
										echo "<th class='text-center' colspan='2'>Sisa LB3 yang ada di TPS</th>";
									echo "</tr>";
									echo "<tr>";
										echo "<th class='text-center'>Jumlah</th>";
										echo "<th class='text-center'>Satuan</th>";
										echo "<th class='text-center'>Jumlah</th>";
										echo "<th class='text-center'>Satuan</th>";
										echo "<th class='text-center'>Jumlah</th>";
										echo "<th class='text-center'>Satuan</th>";
									echo "</tr>";
				                ?>									
				            </thead>
			            </table>
		            
			        </div>
				</div>
			</div>
		</div>

	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_logbook_b3.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<style>
.small-box .icon{
    top: -13px;
}
</style>
