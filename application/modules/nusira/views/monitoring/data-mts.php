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
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="pull-right">
							<a href="#" type="button" class="btn btn-primary pull-right mts-detail" data-toggle="modal" data-target="#KiranaModals" data-action="buat">
								<i class="fa fa-plus"></i> Make To Stock
							</a>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="mesin" class="control-label text-left">Jadwal Produksi</label>
									<div class="input-group">
										<div class="input-group-addon">Tanggal</div>
										<input type="text" id="tanggal_awal_filter" name="tanggal_awal" value="<?php echo $tanggal_awal->format('d.m.Y'); ?>" class="form-control kiranadatepicker" autocomplete="off" data-autoclose="true">
										<div class="input-group-addon"> - </div>
										<input type="text" id="tanggal_akhir_filter" name="tanggal_akhir" value="<?php echo $tanggal_akhir->format('d.m.Y'); ?>" class="form-control kiranadatepicker" autocomplete="off" data-autoclose="true" data-minDate="#tanggal_awal_filter">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-hover" id="sspTable">
									<thead>
										<th width="20%">No IO</th>
										<th width="15%">Start Date</th>
										<th width="15%">End Date</th>
										<th width="10%">Kode Material</th>
										<th>Nama Material</th>
										<th width="10%">UoM</th>
										<th width="5%">Qty</th>
										<th width="1%"></th>
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
<script src="<?php echo base_url() ?>assets/apps/js/nusira/monitoring/data-mts.js"></script>