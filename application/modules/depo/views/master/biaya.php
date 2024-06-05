<!--
/*
@application  : MASTER DEPO
@author       : Lukman Hakim (7143)
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
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-8">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-4">
			            		<div class="form-group">
				                	<label> Jenis Depo: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_jenis_depo" name="filter_jenis_depo[]" style="width: 100%;" data-placeholder="Pilih Jenis Depo">
				                  		<?php
											echo "<option></option>";
											echo"<option value='mitra'>Mitra</option>";
											echo"<option value='tetap'>Tetap</option>";
											echo"<option value='all'>All</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-4">
			            		<div class="form-group">
				                	<label> Jenis Biaya: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_jenis_biaya" name="filter_jenis_biaya[]" style="width: 100%;" data-placeholder="Pilih Jenis Biaya">
				                  		<?php
											echo "<option></option>";
											echo"<option value='operational'>Operasional</option>";
											echo"<option value='investasi'>Investasi</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Jenis Depo</th>
				              	<th>Jenis Biaya</th>
				              	<th>Jenis Biaya Detail</th>
				              	<th>Nama Biaya</th>
				              	<th>Satuan</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($biaya as $dt){
									echo "<tr>";
				              		echo "<td>".strtoupper($dt->jenis_depo)."</td>";
				              		echo "<td>".strtoupper($dt->jenis_biaya)."</td>";
				              		echo "<td>".strtoupper($dt->jenis_biaya_detail)."</td>";
									echo "<td>".strtoupper($dt->nama)."</td>";
				              		echo "<td>".strtoupper($dt->satuan)."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
												echo"<ul class='dropdown-menu pull-right'>";
													if($dt->na == 'n'){ 
														echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_biaya."'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
														echo "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_biaya."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
													}
													if($dt->na == 'y'){
														echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_biaya."'><i class='fa fa-check'></i> Set Aktif</a></li>";
													}
												echo"</ul>";
									echo " 	
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
			<div class="col-sm-4">
				<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title title-form">Form Master Biaya</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Biaya</button>
		          	</div>
		          	<form role="form" class="form-master-biaya">
	            		<div class="box-body">
							<div class="form-group">
								<label for="jenis_depo">Jenis Depo</label>
								<select class="form-control select2" name="jenis_depo" id="jenis_depo" required="required">
									<?php
										echo "<option value='0'>Pilih Jenis Depo</option>";
										echo"<option value='mitra'>Mitra</option>";
										echo"<option value='tetap'>Tetap</option>";
										echo"<option value='all'>All</option>";
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="jenis_biaya">Jenis Biaya</label>
								<select class="form-control select2" name="jenis_biaya" id="jenis_biaya" required="required">
									<?php
										echo "<option value='0'>Pilih Jenis Biaya</option>";
										echo"<option value='operational'>Operasional</option>";
										echo"<option value='investasi'>Investasi</option>";
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="jenis_biaya_detail">Jenis Biaya Detail</label>
								<select class="form-control select2" name="jenis_biaya_detail" id="jenis_biaya_detail">
									<?php
										echo "<option value='0'>Pilih Jenis Biaya Detail</option>";
										echo"<option value='transaksi'>Transaksi</option>";
										echo"<option value='sdm'>SDM</option>";
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="nama">Nama Biaya</label>
		                		<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Biaya" required="required">
		                	</div>
							<div class="form-group">
								<label for="satuan">Satuan</label>
		                		<input type="text" class="form-control" name="satuan" id="satuan" placeholder="Nama Satuan">
		                	</div>
		            	</div>
		            	<div class="box-footer">
							<input id="id_biaya" name="id_biaya" type="hidden">
							<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/depo/master/biaya.js"></script>
<!--export to excel-->
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