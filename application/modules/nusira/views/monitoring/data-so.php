<!--
	/*
        @application  : 
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 05-Mar-19
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */
-->
<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css">

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
							<div class="col-sm-4">
								<div class="form-group">
									<label for="mesin" class="control-label text-left">Tanggal PO</label>
									<div class="input-group">
										<div class="input-group-addon">Tanggal</div>
										<input type="text" id="tanggal_awal_filter" name="tanggal_awal" value="<?php echo $tanggal_awal->format('d.m.Y'); ?>" class="form-control kiranadatepicker" autocomplete="off" data-autoclose="true">
										<div class="input-group-addon"> - </div>
										<input type="text" id="tanggal_akhir_filter" name="tanggal_akhir" value="<?php echo $tanggal_akhir->format('d.m.Y'); ?>" class="form-control kiranadatepicker" autocomplete="off" data-autoclose="true" data-minDate="#tanggal_awal_filter">
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="mesin" class="control-label text-left">Pabrik Pemesan</label>
									<select class="form-control select2" name="pabrik[]" id="pabrik" data-placeholder="Pilih pabrik" multiple>
										<option></option>
										<?php foreach ($pabrik as $p) : ?>
											<option value="<?php echo $p->plant ?>">
												<?php echo $p->plant . ' - ' . $p->nama ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="mesin" class="control-label text-left">Status</label>
									<select class="form-control select2" name="status" id="status" data-placeholder="Pilih status">
										<option value="semua">Semua</option>
										<option value="Tidak Lengkap" selected>SPK Tidak Lengkap</option>
										<option value="Lengkap">SPK Lengkap</option>
										<option></option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover" id="sspTable">
									<thead>
										<th width="5%">No SO</th>
										<th width="20%">Pabrik Pemesan</th>
										<th width="15%">No PO</th>
										<th width="15%">No PI</th>
										<th width="10%">Tanggal PO</th>
										<th width="5%">Status SPK</th>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/monitoring/data-so.js"></script>