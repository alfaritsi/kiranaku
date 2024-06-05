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
						<!-- <table class="table table-bordered table-striped my-datatable"> -->
						<table class="table table-bordered table-striped" id="sspTable">
							<thead>
								<tr>									
									<th>Jenis Temuan</th>
									<th>Asal Temuan</th>									
									<th>Kode Temuan</th>
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
					<form role="form" class="form-master-temuan">
						<div class="box-body">							
							<div class="form-group">
								<label for="jenis_temuan">Jenis Temuan</label>
								<div>
									<input type="text" class="form-control" name="jenis_temuan" id="jenis_temuan" placeholder="Masukkan Jenis Temuan" required="required">
								</div>
							</div>
							<div class="form-group">
								<label for="requestor"> Asal Temuan </label>
								<div>
									<select class="form-control select2" id="requestor" name="requestor" style="width: 100%;" 	data-placeholder="Pilih Peruntukan Temuan">
				                  		<option value="Eksternal"> Eksternal </option>
				                  		<option value="Internal"> Internal </option>
				                  	</select>
								</div>
							</div>
							<div class="form-group">
								<label for="kode_temuan">Kode Temuan</label>
								<div>
									<input type="text" class="form-control" name="kode_temuan" id="kode_temuan" placeholder="Masukkan Kode Temuan" required="required">
								</div>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden" name="id_temuan" />
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
<script src="<?php echo base_url() ?>assets/apps/js/pica/master/mtemuan.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


