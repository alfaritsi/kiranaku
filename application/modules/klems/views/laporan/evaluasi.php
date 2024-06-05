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
	          		<!-- /.box-header -->
		          	
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Nama Program : </label>
				                	<select class="form-control select2" multiple="multiple" id="program" name="program[]" style="width: 100%;" data-placeholder="Pilih Program">
				                  		<?php
					                		foreach($program as $dt){
					                			echo "<option value='".$dt->id_program."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			            	<div class="col-sm-7">
			            		<div class="form-group">
				                	<div class="col-sm-3">
				                	<label>Periode: </label>
				                	<input class="form-control tanggal" id="awal" name="awal" value="<?php echo $awal;?>" style="width: 100%;"/>
				                	</div>
				                	<div class="col-sm-1">	
				                		<label>&nbsp;</label>
				                	<label class="form-control no-border" style="width: 5%;" > To </label>
				            		</div>
									<div class="col-sm-3">
									<label>&nbsp;</label>	
				            		<input class="form-control tanggal" id="akhir" name="akhir" value="<?php echo $akhir;?>" style="width: 100%;"/>
				            		</div>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Kode Batch Program</th>
								<th>Nama Batch Program</th>
								<th>Nama Program</th>
								<th>Tanggal Tahap</th>
								<th>Tahap</th>
								<th>Trainer</th>
								<th>Jumlah Evaluasi</th>
								<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$kode_program_batch = "zzzzzzzzz";
				              	foreach($batch as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->kode_program_batch."</td>";
				              		echo "<td>".$dt->nama_program_batch."</td>";
									echo "<td>".$dt->nama_program."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_awal),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir),"d-m-Y")."</td>";
									echo "<td>".$dt->nama_tahap."</td>";
									echo "<td><ul style='padding-left: 0px;'>";
										if($dt->list_trainer!=""){
											$list_trainer = explode(",", substr($dt->list_trainer,0,-1));
											foreach ($list_trainer as $lt) {
												$ex_lt = explode("|",$lt);
												echo "<li style='list-style-type:none'><i class='fa fa-user'></i> ".$ex_lt[0]."(".$ex_lt[1].")</li>";		
											}
										}else{
											echo "<li style='list-style-type:none'>-</li>";		
										}
									echo "</ul></td>";
									echo "<td>".$dt->jumlah_evaluasi."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->list_trainer!=""){
													$list_trainer = explode(",", substr($dt->list_trainer,0,-1));
													foreach ($list_trainer as $lt) {
														$ex_trainer = explode("|", $lt);
														echo "<li><a href='".base_url()."klems/laporan/data/sesi/".str_replace('/','_',$dt->id_batch)."/".$ex_trainer[2]."/".$ex_trainer[1]."'><i class='fa fa-file-text'></i> Feedback Evaluasi Sesi (".$ex_trainer[0].")</a></li>";		
													}
												}
												if($kode_program_batch!=$dt->kode_program_batch){
													echo"<li><a href='".base_url()."klems/laporan/data/prog/".str_replace('/','_',$dt->id_batch)."'><i class='fa fa-file-text'></i> Feedback Evaluasi Program</a></li>";
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
<!--<script src="<?php echo base_url() ?>assets/apps/js/klems/transaksi/nilai.js"></script>-->
<script src="<?php echo base_url() ?>assets/apps/js/klems/laporan/evaluasi.js"></script>
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