<!--
	/*
        @application  : 
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 07-Jan-19
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/apps/css/order/order.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-8">
				<div class="box box-success page-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped table-wrap"
							   id="sspTable">
							<thead>
								<tr>
									<th>Nama Material</th>
									<th>Spesifikasi</th>
									<th>Harga</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-success page-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong><?php echo $title_form; ?></strong></h3>
					</div>
					<form class="form-setting-material"
						  enctype="multipart/form-data">
						<div class="box-body">
							<div class="form-group">
								<label for="plant">Kode Material</label>
								<input type="text"
									   name="kode"
									   class="form-control"
									   readonly="readonly"
									   required="required" />
							</div>
							<div class="form-group">
								<label for="plant">Nama Material</label>
								<select class="form-control material"
										name="material"
										required="required">
								</select>
							</div>
							<div class="form-group">
								<label for="plant">Spesifikasi</label>
								<textarea name="spesifikasi"
										  class="form-control"
										  required="required"></textarea>
							</div>
							<div class="form-group">
								<label for="plant">Gambar</label>
								<div class="input-group">
									<input type="file"
										   class="form-control readonly"
										   name="file_material[]"
										   multiple="multiple">
									<div class="input-group-btn">
										<input type="text"
											   name="file_material_hidden"
											   class="form-control hidden data-lihat-file readonly">
										<button type="button"
												class="btn btn-default btn-flat lihat-file"
												data-title="File Material"
												title="klik untuk lihat file"><i class="fa fa-search"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden" name="itnum">
							<button type="button"
									class="btn btn-success"
									name="action_btn"
									value="button">Submit
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/setting/material.js"></script>
<style>
	.wrap-text{
		word-wrap: break-word;
		white-space: normal !important;
	}
</style>
