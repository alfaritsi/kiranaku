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
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Nama Program</th>
				              	<th>Detail Batch Program</th>
								<th>Kode Batch Program</th>
								<th>Nama Batch Program</th>
								<th>Periode Batch Program</th>
								<th>Detail Tahap</th>
								<th>Nama Tahap</th>
								<th>Periode Tahap</th>
								<th>Lokasi Tahap</th>
								<th>Materi</th>
								<th>Trainer</th>
								<th>Tanggal Test</th>
								<th>Jam Test</th>
								<th>Online</th>
				            </thead>
			              	<tbody>
			              		<?php
								$ip  	= explode(".", $_SERVER["REMOTE_ADDR"]);
								$get_ip = (empty($ip[2]))?8:$ip[2];
								switch ($get_ip) {
									case "8":
										$base_url =  base_url();
										break;
									case "9":
										// $base_url =  base_url();
										$base_url =  "http://10.0.26.3/kiranaku/";
										break;
									case "10":
										$base_url =  base_url();
										break;
									case "11":
										$base_url =  base_url();
										break;
									case "12":
										$base_url =  base_url();
										break;
									case "16":
										$base_url =  "http://10.0.16.3/kiranaku/";
										break;
									case "17":
										$base_url =  "http://10.0.17.3/kiranaku/";
										break;
									case "18":
										$base_url =  "http://10.0.18.3/kiranaku/";
										break;
									case "19":
										$base_url =  "http://10.0.19.3/kiranaku/";
										break;
									case "20":
										$base_url =  "http://10.0.20.3/kiranaku/";
										break;
									case "21":
										$base_url =  "http://10.0.21.3/kiranaku/";
										break;
									case "22":
										$base_url =  "http://10.0.22.3/kiranaku/";
										break;
									case "23":
										$base_url =  "http://10.0.23.3/kiranaku/";
										break;
									case "24":
										$base_url =  "http://10.0.24.3/kiranaku/";
										break;
									case "25":
										$base_url =  "http://10.0.25.3/kiranaku/";
										break;
									case "26":
										$base_url =  "http://10.0.26.3/kiranaku/";
										break;
									case "31":
										$base_url =  "http://10.0.31.3/kiranaku/";
										break;
									case "32":
										$base_url =  "http://10.0.32.3/kiranaku/";
										break;
									default:
										$base_url =  base_url();
								}
							
				              	foreach($batch as $dt){
									if($dt->online=='y'){
										$tanggal = date_format(date_create($dt->tanggal),"d-m-Y");
										$jam = $dt->jam_awal." - ".$dt->jam_akhir;
									}else{
										$tanggal = "-";
										$jam = "-";
									}
									echo "<tr>";
				              		echo "<td>".$dt->nama_program."</td>";
				              		echo "<td>".$dt->kode_program_batch."<br>".$dt->nama_program_batch."<br>".date_format(date_create($dt->tanggal_awal_program_batch),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir_program_batch),"d-m-Y")."</td>";
									echo "<td>".$dt->kode_program_batch."</td>";
									echo "<td>".$dt->nama_program_batch."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_awal_program_batch),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir_program_batch),"d-m-Y")."</td>";
									echo "<td>".$dt->nama_tahap."<br>".date_format(date_create($dt->tanggal_awal),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir),"d-m-Y")."<br>".$dt->tempat."</td>";
									echo "<td>".$dt->nama_tahap."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_awal),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir),"d-m-Y")."</td>";
									echo "<td>".$dt->tempat."</td>";
									echo "<td><ul style='padding-left: 0px;'>";
										if($dt->materi_list!=""){
											$materi_list = explode(",", substr($dt->materi_list,0,-1));
											foreach ($materi_list as $m) {
												$dm = explode("|", $m);
												if($dm[4]=='zip'){
													echo "<li style='list-style-type:none'><i class='fa fa-save'></i> <a href='".base_url()."".$dm[3]."'>[".$dm[0]."] ".$dm[2].".".$dm[4]."</a></li>";			
												}else if($dm[4]=='mp4'){
													echo "<li style='list-style-type:none'><i class='fa fa-video-camera'></i> <a href='#' class='materi' data-base_url='".$base_url."' data-id_materi='".$generate->kirana_encrypt($dm[1])."'>[".$dm[0]."] ".$dm[2].".".$dm[4]."</a></li>";		
												}else{
													echo "<li style='list-style-type:none'><i class='fa fa-file-text'></i> <a href='#' class='materi' data-base_url='".$base_url."'  data-id_materi='".$generate->kirana_encrypt($dm[1])."'>[".$dm[0]."] ".$dm[2].".".$dm[4]."</a></li>";		
												}
												
											}
										}else{
											echo "<li style='list-style-type:none'>-</li>";		
										}
									echo "</ul></td>";
									echo "<td><ul style='padding-left: 0px;'>";
										if($dt->list_trainer!=""){
											$list_trainer = explode(",", substr($dt->list_trainer,0,-1));
											foreach ($list_trainer as $lt) {
												$tr = explode("|", $lt);
												if($tr[1]=="luar"){
													$dari = "Eksternal";
												}else{
													$dari = "Internal";
												}
												echo "<li style='list-style-type:none'><i class='fa fa-user'></i> [".$dari."] ".$tr[0]."</li>";		
											}
										}else{
											echo "<li style='list-style-type:none'>-</li>";		
										}
									echo "</ul></td>";
									echo "<td>".$tanggal."</td>";
									echo "<td>".$jam."</td>";
									echo "<td>".$dt->label_online."</td>";
									echo "</tr>";
				              	}
				              	?>
			              	</tbody>
			            </table>
			        </div>
				</div>
			</div>
			<!--materi modal-->
			<div class="modal fade" id="materi_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Materi Training</h4>
						</div>
	            		<div class="modal-body">
							<div id="show_materi"></div>
		            	</div>
					</div>
				</div>	
			</div>	
			
		
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/materi.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.colVis.min.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>