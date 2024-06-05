<!--
/*
	@application  : PICA 
		@author       : Airiza Yuddha (7849)
		@contributor  :
			  1. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
*/
 -->
<?php $this->load->view('header') ?>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-8">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped" id="sspTable">
							<thead>
								<tr>
									<th>Posisi</th>
									<th>Role</th>
									<th>Jenis Temuan</th>									
									<th>Pabrik</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong><?php echo $title_form; ?></strong></h3>
						<div class="pull-right">
							<button type="button"
									class="btn btn-sm btn-default"
									id="btn-new"
									style="display:none">Buat Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-roleposisi">
						<div class="box-body">
							
							<div class="form-group">
								<label for="nama_role">Role</label>
								<div>
									<!-- <input type="text" class="form-control" name="nama_role" id="nama_role" placeholder="Masukkan Role" required="required"> -->
									<select class="form-control select2" id="nama_role" name="nama_role" style="width: 100%;" data-placeholder="Pilih Role" required="required">
										<!-- <option> Silahkan Pilih Role </option> -->
				                  		<?php
					                		foreach($role as $dt){
					                			echo "<option value='".$dt->id_pica_role."|".$dt->id_pica_jenis_temuan."'";
					                			echo ">".$dt->nama_role." ".$dt->nama_temuan." </option>";
					                		}
					                	?>
				                  	</select>
								</div>
							</div>
							
							<div class="form-group">
								<label for="if_approve">Posisi</label>
								<div>
									<!-- <input type="text" class="form-control" name="posisi" id="posisi" placeholder="Masukkan nik ketika Approve" required="required"> -->

									<!-- <select class="form-control select2-user-search col-sm-12" name="posisi[]" id="posisi"></select> -->
									<select class="form-control select2" id="posisi" name="posisi[]" style="width: 100%;" data-placeholder="Pilih Posisi" required="required">
										<option> Silahkan Pilih Posisi </option>
				                  		<?php
					                		foreach($posisi as $dt){
					                			echo "<option value='".$dt->id_posisi."|".$dt->posisi."'";
					                			echo ">".$dt->posisi."</option>";
					                		}
					                	?>
				                  	</select>
								</div>
							</div>
							<div class="form-group">
								<div>
									<label for="if_decline">Pabrik</label>
									<div class="checkbox pull-right select_all" style="margin:0;">
			                			<label id="label_checkall"><input type="checkbox" class="isSelectAll"> Select All</label>
			                		</div>
								</div>
								<div id="divpabrik">
									<select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Pabrik" required="required">
				                  		<?php
					                		foreach($plant as $dt){
					                			echo "<option value='".$dt->plant."'";
					                			echo ">".$dt->plant_name."</option>";
					                		}
					                	?>
				                  	</select>
								</div>
							</div>

						</div>
						<div class="box-footer">
							<input type="hidden" name="id_roleposisi" />
							<button type="button" class="btn btn-sm btn-success" name="action_btn" value="submit">Submit</button>
						</div>
					</form>
				</div>
			</div>
			<!--modal add_modal_detail-->
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pica/master/mroleposisi.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


