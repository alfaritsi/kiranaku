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
			          		<div class="col-sm-2">
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
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
								<th>Nama</th>
								<th>NIK</th>
								<th>Pabrik</th>
								<th>Kode Batch Program</th>
								<th>Nama Batch Program</th>
								<th>Nama Program</th>
								<th>Tanggal Batch</th>
								<th>Nilai</th>
								<th>Grade</th>
								<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($peserta as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->nama_karyawan."</td>";
									echo "<td>".$dt->nik."</td>";
									echo "<td>".$dt->gsber."</td>";
				              		echo "<td>".$dt->kode_program_batch."</td>";
				              		echo "<td>".$dt->nama_program_batch."</td>";
									echo "<td>".$dt->nama_program."</td>";
									echo "<td>".date_format(date_create($dt->tanggal_awal_batch),"d-m-Y")." sd ".date_format(date_create($dt->tanggal_akhir_batch),"d-m-Y")."</td>";
									echo "<td>".$dt->average."</td>";
									echo "<td>".$dt->grade."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>
												<li><a href='#' class='detail' data-id_program_batch='".$dt->id_program_batch."' data-id_karyawan='".$dt->nik."'><i class='fa fa-search'></i> Detail</a></li>
											</ul>
				                          </div>
				                        </td>";
									
									echo "</tr>";
				              	}
				              	?>
			              	</tbody>
			            </table>
			        </div>
				</div>
			</div>
			<!--modal-->
			<div class="modal fade" id="show_detail" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Detail</h4>
						</div>
	            		<div class="modal-body">
		              		<div id="container-nilai">
		             		</div>
		            	</div>
					</div>
				</div>	
			</div>			
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/klems/laporan/nilai.js"></script>
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