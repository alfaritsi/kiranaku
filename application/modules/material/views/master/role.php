<!--
/*
@application  : KODE MATERIAL
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
	          		<!-- /.box-header -->
		          	<div class="box-body">
						<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Master PIC</th>
				              	<th>Type</th>
				              	<th>Posisi</th>
								<th>Divisi</th>
								<th>Seksie</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($role as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->master_pic."</td>";
				              		echo "<td>".$dt->tipe."</td>";
									echo "<td>";
										if(!empty($dt->list_posisi)){
											$list_posisi = explode(",", substr($dt->list_posisi,0,-1));
											foreach ($list_posisi as $l) {
												echo "<button class='btn btn-sm btn-info btn-role'>".$l."</button>";
											}
										}
									echo "</td>";
									echo "<td>";
										if(!empty($dt->list_divisi)){
											$list_divisi = explode(",", substr($dt->list_divisi,0,-1));
											foreach ($list_divisi as $l) {
												echo "<button class='btn btn-sm btn-info btn-role'>".$l."</button>";
											}
										}
									echo "</td>";
									echo "<td>";
										if(!empty($dt->list_seksi)){
											$list_seksi = explode(",", substr($dt->list_seksi,0,-1));
											foreach ($list_seksi as $l) {
												echo "<button class='btn btn-sm btn-info btn-role'>".$l."</button>";
											}
										}
									echo "</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_item_setting_user."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
													  <li><a href='javascript:void(0)' class='nonactive' data-nonactive='".$dt->id_item_setting_user."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_item_setting_user."'><i class='fa fa-check'></i> Set Aktif</a></li>";
												}
									echo " 	</ul>
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
		              	<h3 class="box-title title-form">Form Master Role</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Add Role</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-role">
	            		<div class="box-body">
							<div class="form-group">
								<label for="id_item_master_pic">Master PIC</label>
								<select class="form-control select2" name="id_item_master_pic" id="id_item_master_pic"  required="required">
									<?php
										echo "<option value='0'>Pilih Master PIC</option>";
										foreach($pic as $dt){
											echo"<option value='".$dt->id_item_master_pic."'>".$dt->master_pic."</option>";
										}
									?>
								</select>
		                	</div>
		              		<div class="form-group">
								<label for="tipe">Type</label>
								<select class="form-control select2" name="tipe" id="tipe" required="required">
									<?php
										echo "<option value='0'>Pilih Type</option>";
										echo "<option value='Approver'>Approver</option>";
										echo "<option value='Requestor'>Requestor</option>";
									?>
								</select>
							</div>
		              		<div class="form-group" id="show_posisi" style="display: none">
		                		<label for="posisi">Posisi</label>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="posisi[]" id="posisi" data-placeholder="Pilih Posisi">
		                			<?php
		                				foreach($posisi as $dt){
		                					echo "<option value='".$dt->id_posisi."'>".$dt->nama."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
		              		<div class="form-group" id="show_divisi" style="display: none">
		                		<label for="divisi">Divisi</label>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="divisi[]" id="divisi" data-placeholder="Pilih Divisi">
		                			<?php
		                				foreach($divisi as $dt){
		                					echo "<option value='".$dt->id_divisi."'>".$dt->nama."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
		              		<div class="form-group" id="show_seksi" style="display: none">
		                		<label for="seksie">Seksie</label>
		                		<select class="form-control select2 col-sm-12" multiple="multiple" name="seksi[]" id="seksi" data-placeholder="Pilih Seksie">
		                			<?php
		                				foreach($seksi as $dt){
		                					echo "<option value='".$dt->id_seksi."'>".$dt->nama."</option>";
		                				}
		                			?>
		                		</select>
		              		</div>
							
		            	</div>
		            	<div class="box-footer">
							<input type="hidden" name="id_item_setting_user">
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
<script src="<?php echo base_url() ?>assets/apps/js/material/master/role.js"></script>
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