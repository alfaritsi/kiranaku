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
									<th>Role</th>
									<th>Jenis Temuan</th>
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
						<h3 class="box-title pull-left"><strong><?php echo $title_form; ?></strong></h3>
						<div class="pull-right">
							<button type="button"
									class="btn btn-sm btn-default"
									id="btn-new"
									style="display:none">Buat Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-role">
						<div class="box-body">
							
							<div class="form-group">
								<label for="temuan">Jenis Temuan</label>
								<div>
									<select class="form-control select2" id="jenis_temuan" name="jenis_temuan" style="width: 100%;" data-placeholder="Pilih Jenis Temuan">
				                  		<?php
					                		foreach($temuan as $dt){
					                			echo "<option value='".$dt->id_pica_jenis_temuan."|".$dt->jenis_temuan."' >";
					                			echo $dt->jenis_temuan." - ".$dt->requestor."</option>";
					                		}
					                	?>
				                  	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="nama_role">Role</label>
								<div>
									<!-- <input type="text" class="form-control" name="nama_role" id="nama_role" placeholder="Masukkan Role" required="required"> -->
									<select class="form-control select2" id="nama_role" name="nama_role" style="width: 100%;" data-placeholder="Pilih Role">
				                  		<?php
					                		foreach($rolename as $dt){
					                			echo "<option value='".$dt->role_name."' >".$dt->desc."</option>";
					                		}
					                	?>
				                  	</select>

								</div>
							</div>
							<div class="form-group">
								<label for="level">Level</label>
								<div>									
									<input type="number" class="form-control" id="level" name="level" min='0' placeholder="Masukkkan level role" required="required" >
								</div>
							</div>
							<div class="form-group">
								<label for="if_approve">If Approve</label>
								<div>
									<input type="hidden" class="form-control" name="if_approve_hidden" id="if_approve_hidden" placeholder="Masukkan role ketika Approve" required="required">
									<!-- <select class="form-control select2-user-search col-sm-12" name="if_approve" id="if_approve"></select> -->
									<select class="form-control select2" id="if_approve" name="if_approve" style="width: 100%;" data-placeholder="Pilih Role If Approve">
				                  		<?php
				                  	// 		$nama_role = "";
					                		// foreach($role as $dt){					                			
					                		// 	if($dt->nama_role != $nama_role){
					                		// 		echo "<option value='".$dt->level."'";
					                		// 		echo ">".$dt->nama_role."</option>";
					                		// 		$nama_role = $dt->nama_role;
					                		// 	}
					                		// }
					                		// echo "<option value='100'>Finish</option>";
					                	?>
				                  	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="if_decline">If Decline</label>
								<div>
									<input type="hidden" class="form-control" name="if_decline_hidden" id="if_decline_hidden" placeholder="Masukkan role ketika Decline" >
									<!-- <select class="form-control select2-user2-search col-sm-12" name="if_decline" id="if_decline"></select> -->
									<select class="form-control select2" id="if_decline" name="if_decline" style="width: 100%;" data-placeholder="Pilih Role If Approve">

				                  		<?php
				                  	// 		$nama_role = "";
					                		// foreach($role as $dt){
					                		// 	if($dt->nama_role != $nama_role){
					                		// 		echo "<option value='".$dt->level."'";
					                		// 		echo ">".$dt->nama_role."</option>";
					                		// 		$nama_role = $dt->nama_role;
					                		// 	}
					                			
					                		// }
					                	?>
				                  	</select>
								</div>
							</div>
							<div class="form-group ">									
								<label>
									<input class=" pull-left" type="checkbox" name="multiple_plan" id="multiple_plan" value="1">
									&nbsp; Multiple Pabrik 
								</label>
							</div>
							<div class="form-group " style="display: none">									
								<label>
									<input class=" pull-left" type="checkbox" name="akses_delete" id="akses_delete" value="1">
									&nbsp; Akses Delete 
								</label>
							</div>
							<div class="form-group ">									
								<label>
									<input class=" pull-left" type="checkbox" name="isresponder" id="isresponder" value="1">
									&nbsp; Role sebagai responder 
								</label>
							</div>

						</div>
						<div class="box-footer">
							<input type="hidden" name="id_role" />
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
<script src="<?php echo base_url() ?>assets/apps/js/pica/master/mrole.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


