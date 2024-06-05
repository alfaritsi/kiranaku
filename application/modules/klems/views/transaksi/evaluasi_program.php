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
	          		</div>
	          		<!-- /.box-header -->
					<div class="box-body">
						<div class="col-sm-2">Program</div>
						<div class="col-sm-2">: <?php echo $batch[0]->kode_program_batch;?></div><br>
						<div class="col-sm-2">Tanggal</div>
						<div class="col-sm-2">: <?php echo $batch[0]->tanggal_awal." - ".$batch[0]->tanggal_akhir;?></div><br>
						<div class="col-sm-2">Lokasi</div>
						<div class="col-sm-2">: <?php echo $batch[0]->tempat;?></div><br><br>
						<p><u>Intruksi Pengerjaan</u></p>
						<p>Kuesioner Evaluasi training ini berguna bagi kami untuk memastikan kami memberikan pengalaman training terbaik bagi Bapak/ Ibu diwaktu yang akan datang. Kuesioner ini tidak memiliki jawaban BENAR ataupun SALAH, maka dari itu, kami minta kesediaan Bapak/ Ibu untuk menjawab dengan jujur Kuesioner Evaluasi ini.</p>
						<p>Pilihan antara angka 1 sampai dengan 5 pada pilihan (Angka 1 = SANGAT TIDAK SETUJU & angka 5 = SANGAT SETUJU)</p>
						<?php 
						if($feedback_pertanyaan){
							foreach($feedback_pertanyaan as $tanya){
								$rowspan = 2+$feedback_nilai[0]->rows;
								foreach($tanya as $t2){}
								echo '
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th colspan='.$rowspan.'>'.$t2->nama_kategori.'</th>
										</tr>
										<tr>
											<th rowspan="2" width="25px">No</th>
											<th rowspan="2">Item Kuesioner</th>
											<th colspan='.$feedback_nilai[0]->rows.'><center>Nilai</center></th>
										</tr>
										<tr>
										';
										foreach($feedback_nilai as $fn){								
											echo"
												<th  width='50px'><center>".$fn->nilai."</center></th>
											";
										}		
								echo'
										</tr>
									</thead>
								';
								$n = 0;
								echo "<tbody>";
								foreach($tanya as $t){
									$n++;
									echo "<tr>";
									echo "<td align='center'>".$n."</td>";
									echo "<td>".$t->pertanyaan."</td>";
									foreach($feedback_nilai as $fn){
										$nama  = 'nilai_'.$t->id_feedback_pertanyaan;
										if(($t->id_karyawan==base64_decode($this->session->userdata("-nik-")))and($t->id_batch==$batch[0]->id_batch)and($t->id_feedback_nilai==$fn->id_feedback_nilai)){
											echo"<td align='center'><input checked='checked' type='radio' name='$nama' onclick=\"save_evaluasi('".$batch[0]->id_batch."','".base64_decode($this->session->userdata("-nik-"))."','".$t->id_feedback_pertanyaan."','".$fn->id_feedback_nilai."','".$t->id_feedback_kategori."',0)\"></td>";
										}else{
											echo"<td align='center'><input type='radio' name='$nama' onclick=\"save_evaluasi('".$batch[0]->id_batch."','".base64_decode($this->session->userdata("-nik-"))."','".$t->id_feedback_pertanyaan."','".$fn->id_feedback_nilai."','".$t->id_feedback_kategori."',0)\"></td>";
										}
									}		
									echo "</tr>";
								}
								echo "</tbody>";
								echo "</table>";
								echo "<br><br>";
							}
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/evaluasi_program.js"></script>
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