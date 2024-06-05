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
						<button type="button" class="btn btn-sm btn-success pull-right" id="add_button_save">Save Nilai</button> 
	          		</div>
	          		<!-- /.box-header --> 
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
								<tr>
									<th rowspan="2">Nama Peserta</th>
									<th rowspan="2">Nik</th>
									<th rowspan="2">Divisi</th>
									<th colspan="<?php echo $nilai_akademik[0]->rows;?>"><center>Akademik</center></th>
									<th colspan="<?php echo $nilai_non_akademik[0]->rows;?>"><center>Non Akademik</center></th>
									<th rowspan="2">Alasan</th>
									<th rowspan="2">Grand Total</th>
								</tr>
								<tr>
									<?php 
									foreach($nilai_akademik as $ak){								
										echo"
											<th>".$ak->nama."</th>
											<th>".$ak->bobot."%</th>
										";
									}		
									foreach($nilai_non_akademik as $nak){								
										echo"
											<th>".$nak->nama."</th>
											<th>".$nak->bobot."%</th>
										";
									}		
									?>
								</tr>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($peserta as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->nama."</td>";
				              		echo "<td>".$dt->id_karyawan."</td>";
				              		echo "<td>".$dt->nama_divisi."</td>";
									
									$batch_nilai= explode(",", $dt->list_batch_nilai);
									$score		= explode(",", $dt->list_score);
									$i 			= 0;
									foreach($nilai_akademik as $ak){
										$ck_online = ($ak->nama=='Tertulis')and($dt->online=='y')?'readonly':'';
										echo"
											<td><input $ck_online min='0' max='100' type='number' class='form-control cek_min_max' value='$score[$i]' onkeyup=\"save_score('".$batch[0]->id_batch."','".$dt->id_peserta."','".$batch_nilai[$i]."',this,'".$dt->id_karyawan."')\"></td>
											<td>".$ak->bobot."%</td>
										";
										$i++;
									}		
									foreach($nilai_non_akademik as $nak){								
										echo"
											<td><input min='0' max='100' type='number' class='form-control cek_min_max' value='$score[$i]' onkeyup=\"save_score('".$batch[0]->id_batch."','".$dt->id_peserta."','".$batch_nilai[$i]."',this,'".$dt->id_karyawan."')\"></td>
											<td>".$nak->bobot."%</td>
										";
										$i++;
									}		
									if($dt->grand_total==0){
										$ck1 = ($dt->alasan=='Tidak Hadir')?"selected":"";
										$ck2 = ($dt->alasan=='Tidak Ikut Test')?"selected":"";
										$ck3 = ($dt->alasan=='Tidak Lulus')?"selected":"";
										echo"
											<td>
											<select class='form-control select2' name='alasan' id='alasan' onchange=\"save_alasan('".$batch[0]->id_batch."','".$dt->id_peserta."','".$batch_nilai[$i]."',this,'".$dt->id_karyawan."')\">
												<option value='0'>-Silahkan Pilih Alasan-</option>
												<option value='Tidak Hadir' $ck1>Tidak Hadir</option>
												<option value='Tidak Ikut Test' $ck2>Tidak Ikut Test</option>
												<option value='Tidak Lulus' $ck3>Tidak Lulus</option>
											</select>
											</td>
										";
									}else{
										echo "<td></td>";
									}
									echo "<td>".number_format($dt->grand_total, 2, '.', ',')."</td>";
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
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/nilai_batch.js"></script>
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