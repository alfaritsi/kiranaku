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
				                	<label> Regional : </label>
				                	<select class="form-control select2" multiple="multiple" id="regional" name="regional[]" style="width: 100%;" data-placeholder="Pilih Regional">
				                  		<?php
					                		foreach($regional as $dt){
					                			echo "<option value='".$dt->region_name."'";
					                			echo ">".$dt->region_name."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> NIK/ Nama : </label>
				                	<select class="form-control select2" multiple="multiple" id="nik" name="nik[]" style="width: 100%;" data-placeholder="Pilih NIK/ Nama">
				                  		<?php
					                		foreach($peserta as $dt){
					                			echo "<option value='".$dt->nik."'";
					                			echo ">".$dt->nama_karyawan." [".$dt->nik."]</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Posisi : </label>
				                	<select class="form-control select2" multiple="multiple" id="posisi" name="posisi[]" style="width: 100%;" data-placeholder="Pilih Posisi">
				                  		<?php
					                		foreach($posisi as $dt){
					                			echo "<option value='".$dt->nama."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
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
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik : </label>
				                	<select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
					                		foreach($pabrik as $dt){
					                			echo "<option value='".$dt->plant."'";
					                			echo ">".$dt->plant."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			            	<div class="col-sm-6">
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
							<!--
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Tahun : </label>
				                	<select class="form-control select2" multiple="multiple" id="tahun" name="tahun[]" style="width: 100%;" data-placeholder="Pilih Tahun">
				                  		<?php
					                		foreach($tahun as $dt){
					                			echo "<option value='".$dt->tahun."'";
					                			echo ">".$dt->tahun."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
							-->
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
								<tr>
									<th rowspan='2'>NIK</th>
									<th rowspan='2'>Nama</th>
									<th rowspan='2'>Pabrik</th>
									<th rowspan='2'>Posisi Batch</th>
									<th rowspan='2'>Posisi Sekarang</th>
									<th rowspan='2'>Tgl Bergabung</th>
									<th rowspan='2'>Tgl Lahir</th>
									<th rowspan='2'>Kode Batch Program</th>
									<th rowspan='2'>Nama Batch Program</th>
									<th rowspan='2'>Nama Program</th>
									<th rowspan='2'>Tanggal Batch</th>
									<th colspan='<?php echo $tahap[0]->rows;?>'><div align='center'>Tahap</div></th>
									<th rowspan='2'>Nilai Akhir</th>
								</tr>
								<tr>
									<?php 
									foreach($tahap as $th){								
										echo"<th>".$th->nama."</th>";
									}		
									?>
								</tr>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($peserta as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->nik."</td>";
									echo "<td>".$dt->nama_karyawan."</td>";
									echo "<td>".$dt->gsber."</td>";
									echo "<td>".$dt->posisi_batch."</td>";
									echo "<td>".$dt->posisi_sekarang."</td>";
									echo "<td>".$dt->tanggal_join."</td>";
									echo "<td>".$dt->gbpas."</td>";
				              		echo "<td>".$dt->kode_program_batch."</td>";
									echo "<td>".$dt->nama_program_batch."</td>";
									echo "<td>".$dt->nama_program."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_awal_batch),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir_batch),"d-m-Y")."</td>";

									$score		= explode(",", $dt->list_nilai_tahap);
									$i 			= 0;
									foreach($tahap as $th){
										echo "<td align='right'>".number_format($score[$i], 2, '.', '')."</td>";
										$i++;
									}		
									echo "<td align='right'>".number_format($dt->average, 2, '.', '')."</td>";
									echo "</tr>";
				              	}
				              	?>
			              	</tbody>
			            </table>
			        </div>
				</div>
			</div>
			<!--modal-->
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Detail</h4>
						</div>
	            		<div class="modal-body">
							<table class="table table-bordered">
								<thead>
									<th>No</th>
									<th>Tahap</th>
									<th>Nilai</th>
									<th>Grade</th>
								</thead>
								<tbody>
									<?php
									$no = 1;
									// foreach($program_batch as $dt){
										// $no++;
										echo "<tr>";
										echo "<td>".$no."</td>";
										echo "<td>tahap</td>";
										echo "<td>nilai</td>";
										echo "<td>grade</td>";
										echo "</tr>";
									// }
									?>
								</tbody>
							</table>
		            	</div>
					</div>
				</div>	
			</div>			
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/klems/laporan/history.js"></script>
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