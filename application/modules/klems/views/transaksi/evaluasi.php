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
				              	<th>Kode Batch Program</th>
								<th>Nama Batch Program</th>
								<th>Tanggal Batch Program</th>
								<th>Tahap</th>
								<th>Tanggal Awal</th>
								<th>Tanggal Akhir</th>
								<th>Tanggal Test</th>
								<th>Jam Test</th>
								<th>Lokasi</th>
								<th>Online</th>
								<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$kode_program_batch = "zzzzzzzzz";
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
				              		echo "<td>".$dt->kode_program_batch."</td>";
				              		echo "<td>".$dt->nama_program_batch."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_awal_program_batch),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir_program_batch),"d-m-Y")."</td>";
									echo "<td>".$dt->nama_tahap."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_awal),"d-m-Y")."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_akhir),"d-m-Y")."</td>";
									echo "<td>".$tanggal."</td>";
									echo "<td>".$jam."</td>";
									echo "<td>".$dt->tempat."</td>";
									echo "<td>".$dt->label_online."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->list_trainer!=""){
													$list_trainer = explode(",", substr($dt->list_trainer,0,-1));
													foreach ($list_trainer as $lt) {
														$ex_trainer = explode("|", $lt);
														echo "<li><a href='".base_url()."klems/transaksi/data/sesi/".str_replace('/','_',$dt->id_batch)."/".$ex_trainer[2]."/".$ex_trainer[1]."'><i class='fa fa-file-text'></i> Input Evaluasi Topik (".$ex_trainer[0].")</a></li>";		
													}
												}
												if($kode_program_batch!=$dt->kode_program_batch){
													echo"<li><a href='".base_url()."klems/transaksi/data/prog/".str_replace('/','_',$dt->id_batch)."'><i class='fa fa-file-text'></i> Input Evaluasi Program</a></li>";
												}
									echo "												
											</ul>
				                          </div>
				                        </td>";
									echo "</tr>";
									$kode_program_batch=$dt->kode_program_batch;
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
<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/evaluasi.js"></script>
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