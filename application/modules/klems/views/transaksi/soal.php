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
							<tr><td width='20%'>Nama Program</td><td>: <?php echo $batch[0]->nama_program;?></td></tr>
							<tr><td width='20%'>Tanggal Test</td><td>: <?php if($batch[0]->tanggal!='1900-01-01'){echo $batch[0]->tanggal;} ?></td></tr>
							<tr><td width='20%'>Waktu</td><td>: <?php echo $batch[0]->jam_awal." - ".$batch[0]->jam_akhir;?></td></tr>
							<tr><td width='20%'>Lokasi</td><td>: <?php echo $batch[0]->tempat;?></td></tr>
						</table>
						<br>
						<?php 
							$n = 0;
							$arr_jawab = array(1=>"a","b","c", "d");
							foreach($soal as $dt){
								$n++;
								echo $n.". ".$dt->nama_soal."<br>";
								if($dt->gambar!=null){
									echo "<img src=".$dt->gambar." width='400px'><br>";	
								}
								$list_jawaban = explode("|", substr($dt->nama_jawaban_random, 0, -1));
								$nn = 0;
								foreach ($list_jawaban as $jawaban) {
									$nn++;
									echo $arr_jawab[$nn].". ".$jawaban."<br>";
								}
								echo"<br>";
							}
						?>
					</div>
				</div>
				<div class='page-break'></div>
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong>Jawaban <?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
					<div class="box-body">
						<table border=0>
							<tr><td width='20%'>Nama Program</td><td>: <?php echo $batch[0]->nama_program;?></td></tr>
							<tr><td width='20%'>Tanggal Test</td><td>: <?php if($batch[0]->tanggal!='1900-01-01'){echo $batch[0]->tanggal;} ?></td></tr>
							<tr><td width='20%'>Waktu</td><td>: <?php echo $batch[0]->jam_awal." - ".$batch[0]->jam_akhir;?></td></tr>
							<tr><td width='20%'>Lokasi</td><td>: <?php echo $batch[0]->tempat;?></td></tr>
						</table>
						<br>
						<?php 
							$n = 0;
							$arr_jawab = array(1=>"a","b","c", "d");
							foreach($soal as $dt){
								$n++;
								echo $n.". ".$dt->nama_soal."<br>";
								if($dt->gambar!=null){
									echo "<img src=".$dt->gambar." width='400px'><br>";	
								}
								$list_jawaban = explode("|", substr($dt->nama_jawaban_random, 0, -1));
								$nn = 0;
								foreach ($list_jawaban as $jawaban) {
									$nn++;
									if($jawaban==$dt->jawaban_benar){
										echo "x. ".$jawaban."<br>";	
									}else{
										echo $arr_jawab[$nn].". ".$jawaban."<br>";	
									}
									
								}
								echo"<br>";
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
@media all {
.page-break { display: none; }
}

@media print {
.page-break { display: block; page-break-before: always; }
}
</style>

