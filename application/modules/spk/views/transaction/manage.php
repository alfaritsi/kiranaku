<?php
$this->load->view('header')
?>
<style>
	.btn-data>.badge {
    position: absolute;
    top: -3px;
    right: -10px;
    font-size: 10px;
    font-weight: 400;
}
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<?php if ($akses_buat == 1) : ?>
							<div class="btn-group btn-group-sm pull-right">
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="add-spk">
									<i class="fa fa-plus-square"></i> &nbsp Tambah Perjanjian
								</a>
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="download-template">
									<i class="fa fa-download"></i> &nbsp Download Template
								</a>
							</div>
						<?php endif; ?>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<form name="filter-data-spk" method="post">
							<div class="row">
								<div class="col-sm-2">
									<div class="form-group">
										<label>Pabrik: </label>
										<select class="form-control select2" multiple="multiple" id="filter_plant" name="filter_plant[]" data-placeholder="Pilih Pabrik">
											<?php
											$arr_plant	= (empty($_POST['akses_plant'])) ? NULL : $_POST['akses_plant'];
											foreach ($akses_plant as $plant) :
												echo "<option value='" . $plant . "'>" . $plant . "</option>";
											endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<label> Jenis Perjanjian: </label>
										<select class="form-control select2" multiple="multiple" id="filter_jenis" name="filter_jenis[]" data-placeholder="Pilih Jenis Perjanjian">
											<?php
											$arr_jenis	= (empty($_POST['filter_jenis'])) ? NULL : $_POST['filter_jenis'];
											foreach ($filter_jenis as $j) :
												$selected = (in_array($j->id_jenis_spk, $arr_jenis)) ? "selected='selected'" : "";
												echo "<option value='" . $j->id_jenis_spk . "' " . $selected . ">" . $j->jenis_spk . "</option>";
											endforeach;
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label>Periode Perjanjian: </label>
										<div class="input-group input-daterange">
                                            <input type="text" name="filter_tanggal_perjanjian_awal" class="form-control" autocomplete="off" onkeypress="return false;">
                                            <label class="input-group-addon" for="tanggal-awal">-</label>
                                            <input type="text" name="filter_tanggal_perjanjian_akhir" class="form-control" autocomplete="off" onkeypress="return false;">
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<label> Status: </label>
										<select class="form-control select2" multiple="multiple" id="filter_status" name="filter_status[]" data-placeholder="Pilih Status Perjanjian">
											<option value="onprogress">On Progress</option>
											<option value="confirmed">Confirmed</option>
											<option value="finaldraft">Final Draft</option>
											<option value="completed">Completed</option>
											<option value="cancelled">Cancelled</option>
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label>Tanggal Submit: </label>
										<div class="input-group input-daterange">
                                            <input type="text" name="filter_tanggal_submit_awal" class="form-control" autocomplete="off" onkeypress="return false;">
                                            <label class="input-group-addon" for="tanggal-awal">-</label>
                                            <input type="text" name="filter_tanggal_submit_akhir" class="form-control" autocomplete="off" onkeypress="return false;">
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<!-- /.box-filter -->
					<div class="box-body">
						<table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
							<thead>
								<tr>
									<!-- <th width="2%" data-searchable="false">&nbsp;</th> -->
									<th>Pabrik</th>
									<th>Jenis Perjanjian</th>
									<th>Tanggal<br>Submit</th>
									<th>Tanggal<br>Perjanjian</th>
									<th>Perihal</th>
									<th>Vendor</th>
									<th>Status</th>
									<th>Dokumen<br>Final</th>
									<th></th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('transaction/includes/modal_attachments') ?>
<?php $this->load->view('transaction/includes/modal_spk', compact('plant')) ?>
<?php $this->load->view('transaction/includes/modal_upload') ?>
<?php $this->load->view('transaction/includes/modal_review') ?>
<?php $this->load->view('transaction/includes/modal_final_draft') ?>
<?php $this->load->view('transaction/includes/modal_final') ?>
<?php $this->load->view('transaction/includes/modal_komentar') ?>
<?php $this->load->view('transaction/includes/modal_download') ?>
<?php $this->load->view('transaction/includes/modal_cancel') ?>
<?php $this->load->view('transaction/includes/modal_history') ?>
<?php $this->load->view('transaction/includes/modal_divterkait') ?>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/spk/spk.global.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css" />
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/spk.global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/spk_manage.js?<?php echo time(); ?>"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->