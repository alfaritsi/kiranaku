<!--
/*
	@application  		: Travel 
		@author       	: Airiza Yuddha (7849)
		@contributor  	:
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
									<th>User</th>
									<th>Personal Area</th>									
									<th>Personal Sub Area</th>									
									<th>Level</th>									
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
						<h3 class="box-title pull-left title-form"><strong><?php echo $title_form; ?></strong></h3>
						<div class="pull-right">
							<button type="button"
									class="btn btn-sm btn-default"
									id="btn-new"
									style="display:none">Buat Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-pic_book">
						<div class="box-body">
							
							<!-- <div class="form-group">
								<label for="nama_role">Jenis Input User</label>
								<div>
									<input type="hidden" class="form-control" name="jenis_input1_hidden" id="jenis_input1_hidden">
									<select class="form-control select2" id="jenis_input1" name="jenis_input1" style="width: 100%;" data-placeholder="Pilih Jenis">
					                  	<option value='jabatan' > Jabatan </option>
					                  	<option value='nik' > NIK </option>
				                	</select>
								</div>
							</div> -->
							<div class="form-group">
								<label for="user">User</label>
								<div>									
									<input type="hidden" class="form-control" name="user_hidden" id="user_hidden">
									<select class="form-control select2" id="user" name="user" style="width: 100%;" data-placeholder="Pilih User" required="required">					                  	
				                	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="personal_area">Personal Area</label>
								<div>
									<input type="hidden" class="form-control" name="personal_area_hidden" id="personal_area_hidden">
									<select class="form-control select2" id="personal_area" name="personal_area" style="width: 100%;" data-placeholder="Pilih Personal Area" required="required">
				                  	<?php
				                  		// echo "<option value='0'> Silahkan Pilih Personal Area </option>";
				                		foreach($personal_area as $dt){
				                			/*
												MSPLANT.PERSA as plant_code,
							                    ZDMMSPLANT.WERKS as plant, 
							                    ZDMMSPLANT.NAME1 as plant_name,
				                			*/
				                			echo "<option value='".$dt->plant_code."|".$dt->plant."' >".$dt->plant_name." </option>";
				                		}
				                	?>	
				                  	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="personal_subarea">Personal Sub Area</label>
								<div>
									<input type="hidden" class="form-control" name="personal_subarea_hidden" id="personal_subarea_hidden">
									<select class="form-control select2" id="personal_subarea" name="personal_subarea" style="width: 100%;" data-placeholder="Silahkan Pilih Personal Sub Area" required="required">
					                	<?php
					                  // 		echo "<option > Silahkan Pilih Personal Sub Area </option>";
					                  // 		// echo json_encode($persub);
					                		// foreach($persub as $dt){
					                		// 	echo "<option value='".$dt->BTRTL."' >".$dt->BTEXT." </option>";
					                		// }
					                	?>  
				                	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="nama_role">Jenis Level</label>
								<div>
									<input type="hidden" class="form-control" name="jenis_input2_hidden" id="jenis_input1_hidden">
									<select class="form-control select2" id="jenis_input2" name="jenis_input2" style="width: 100%;" data-placeholder="Pilih Jenis" required="required">
					                  	<option value='jabatan' > JABATAN </option>
					                  	<option value='nik' > NIK </option>
				                	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="user_app">Level</label>
								<div>									
									<input type="hidden" class="form-control" name="user_hidden" id="user_app_hidden">
									<select class="form-control select2" id="user_app" name="user_app[]" multiple="multiple" style="width: 100%;" data-placeholder="Pilih User" required="required">
					                <?php
				                  		foreach($jabatan as $dt){
				                			echo "<option value='".$dt->id_jabatan."' >".$dt->nama." </option>";
				                		}
				                		
				                	?>  	
				                	</select>
								</div>
							</div>

						</div>
						<div class="box-footer">
							<input type="hidden" name="id_pic_book" />
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
<script src="<?php echo base_url() ?>assets/apps/js/travel/master/mpic_book.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


