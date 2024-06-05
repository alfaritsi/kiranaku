<!--
/*
	@application  : Notering 
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
						<!-- <table class="table table-bordered table-striped my-datatable"> -->
						<table class="table table-bordered table-striped" id="sspTable">
							<thead>
								<tr>									
									<th>NIK</th>
									<th>Role</th>									
									<th>Pabrik</th>									
									<th>Device ID</th>
									<th>Temp Device ID</th>									
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
						<h3 class="box-title pull-left title-form"><?php echo $title_form; ?></h3>
						<div class="pull-right">
							<button type="button"
									class="btn btn-sm btn-default"
									id="btn-new"
									style="display:none">Buat Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-user">
						<div class="box-body">							
							<div class="form-group">
								<label for="nik">Nama</label>
								<div>
									<!-- <input type="text" class="form-control" name="nik" id="nik" placeholder="Masukkan NIK" required="required"> -->
									<select class="form-control select2" name="nik" id="nik"  required="required"></select>
								</div>
							</div>
							<!-- <div class="form-group">
								<label for="nama">Nama</label>
								<div>
									<input type="text" class="form-control" name="nama" id="nama" placeholder="" disabled>
								</div>
							</div> -->
							<div class="form-group">
								<label for="plant">Akses Plant</label>
								<div>
									<input type="text" class="form-control" name="plant" id="plant" placeholder="" disabled>
								</div>
							</div>
							<div class="form-group">
								<label for="kode_role">Role</label>
								<div>
									<select class="form-control select2" id="kode_role" name="kode_role" style="width: 100%;" 	data-placeholder="Pilih Jenis Role">
				                  		<?php 
				                  			foreach ($role as $key ) {
				                  				echo "<option value='".$key->kode_role."'> ".$key->nama_role." </option>";
				                  			}
				                  		?>
				                  		<!-- <option value="Eksternal"> Eksternal </option>
				                  		<option value="Internal"> Internal </option> -->
				                  	</select>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden" name="id_user" />
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
<script src="<?php echo base_url() ?>assets/apps/js/notering/master/muser.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


