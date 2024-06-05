<!--
/*
@application  : Asset Management
@author		  : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->
<?php $this->load->view('header') ?>
<?php 
$awal = (empty($_POST['awal']))?date('Y-m-d', strtotime(date('Y-m-d').'-3 months')):$_POST['awal'];
$akhir = (empty($_POST['akhir']))?date('Y-m-d'):$_POST['akhir'];
?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Kode Pabrik</th>
								<th>NIK Karyawan</th>
								<th>Nama Karyawan</th>
								<th>Email</th>
								<th align='center'>APAR</th>
								<th align='center'>Alat LAB</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($email as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->pabrik."</td>";
				              		echo "<td>".$this->generate->kirana_decrypt($dt->id_karyawan)."</td>";
				              		echo "<td>".$dt->nama."</td>";
				              		echo "<td>".$dt->email."</td>";
				              		echo "<td align='center'><a href='javascript:void(0)' class='edit' data-id_email='".$dt->id_email."' data-id_karyawan='".$dt->id_karyawan."' data-update='apar' data-value_apar='".$dt->value_apar."'>".$dt->label_apar."</a></td>";
				              		echo "<td align='center'><a href='javascript:void(0)' class='edit' data-id_email='".$dt->id_email."' data-id_karyawan='".$dt->id_karyawan."' data-update='lab' data-value_lab='".$dt->value_lab."'>".$dt->label_lab."</a></td>";
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
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/email.js"></script>
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