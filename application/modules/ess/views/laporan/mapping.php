<?php $this->load->view('header') ?>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
				                	<select class="form-control select2" id="pabrik" name="pabrik" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
					                		foreach($plant as $dt){
					                			echo "<option value='".$dt->plant."'>".$dt->plant." - ".$dt->plant_name."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Group Produksi Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="group_produksi" name="group_produksi[]" style="width: 100%;" data-placeholder="Pilih Group Produksi Status">
				                  		<?php
											echo "<option value='n' selected>Not Complete</option>";
											echo "<option value='y'>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Bagian Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_bagian" name="filter_bagian[]" style="width: 100%;" data-placeholder="Pilih Bagian Status">
				                  		<?php
											echo "<option value='n' selected>Not Complete</option>";
											echo "<option value='y'>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-striped"
									   id="sspTable">
									<thead>
										<tr>
											<th>NIK</th>
											<th>Nama</th>
											<th>Email</th>
											<th>Jabatan</th>
											<th>Group Produksi</th>
											<th>Bagian</th>
											<th>Action</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!--modal edit-->
<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-mapping">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Set Mapping</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">	
								<label for="NIK">NIK</label>
								<input type="text" class="form-control" name="nik" id="nik" placeholder="NIK"  required="required" disabled>
							</div>
							<div class="form-group">	
								<label for="Nama">Nama</label>
								<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama"  required="required" disabled>
							</div>
							<div class="form-group">		
								<label for="type">Bagian</label>
								<select class="form-control select2modal" name="bagian" id="bagian"  required="required">
									<?php
										foreach($bagian as $dt){
											echo "<option value='".$dt->PRUNT."'>".$dt->PRUNT." - ".$dt->DESCR."</option>";
											
										}
									?>
								</select>
							</div>
							<div class="form-group">		
								<label for="type">Group Produksi</label>
								<select class="form-control select2modal" name="group" id="group"  required="required">
									<?php
										foreach($group as $dt){
											echo "<option value='".$dt->PRGRP."'>".$dt->PRGRP." - ".$dt->DESCR."</option>";
										}
									?>
								</select>
							</div>
						</div>
						<div class="modal-footer">
							<input id="nik" name="nik" type="hidden">
							<button id="btn_save" type="button" class="btn btn-primary" name="action_btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/ess/mapping.js"></script>
