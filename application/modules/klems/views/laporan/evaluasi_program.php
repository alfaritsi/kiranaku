<!--
/*
@application  : KLEMS (Kirana Learning Management System)
@author     : Lukman Hakim (7143)
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<button class='btn btn-primary pull-right no-print' onclick='window.print();'><i class='fa fa-print'></i> Cetak</button>
	          		</div>
	          		<!-- /.box-header -->
					<div class="box-body">
						<table border=0>
							<tr><td width='20%'>Kode Batch Program</td><td>: <?php echo $batch[0]->nama_program;?></td></tr>
							<tr><td width='20%'>Nama Program</td><td>: <?php echo $batch[0]->nama_program;?></td></tr>
							<tr><td width='20%'>Tanggal</td><td>: <?php echo $batch[0]->tanggal_awal." - ".$batch[0]->tanggal_akhir;?></td></tr>
							<tr><td width='20%'>Lokasi</td><td>: <?php echo $batch[0]->tempat;?></td></tr>
						</table>
						<br>
						<?php 
						if($feedback_pertanyaan){
							echo"<div class='col-sm-12'>";
							echo'
								<table class="table table-bordered table-striped">
							';
							$nn = 0;
							foreach($feedback_pertanyaan as $tanya){
								$nn++;
								if($nn==1){
								echo '
									<thead>
										<tr>
											<th width="3%">No</th>
											<th>Dimensi</th>
											<th width="10%"><center>Nilai</center></th>
										</tr>
									</thead>
								';
								}
								$n = 0;
								$nil = 0;
								echo "<tbody>";
								foreach($tanya as $t){
									$n++;
									$nil += $t->average;
									echo "<tr>";
									echo "<td>".$n."</td>";
									echo "<td>".$t->pertanyaan."</td>";
									echo "<td align='right'>".number_format($t->average, 1, '.', ',')."</td>";
									echo "</tr>";
								}
								echo "</tbody>";
								foreach($tanya as $t2){}
								echo '
									<thead>
										<tr>
											<th colspan="2">'.$t2->nama_kategori.'</th>
											<th><div align="right">'.number_format($nil/$n, 1, '.', ',').'</div></th>
										</tr>
									</thead>
								';
								
							}
							echo "</table>";
							echo"</div>";
						}		
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
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
