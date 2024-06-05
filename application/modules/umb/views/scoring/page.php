<!--
	/*
        @application  : UMB (Uang Muka Bokar)
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 26-Sep-18
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
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right pr">
							<button type="button"
									class="btn btn-sm btn-primary"
									id="plafon_terpakai"><i class="fa fa-info-circle icons_white"></i> Plafon Terpakai
							</button>
						</div>
						<input type="hidden" name="session_role_level" value="<?php echo $session_role[0]->level; ?>">
						<input type="hidden" name="session_role_nama" value="<?php echo $session_role[0]->nama_role; ?>">
						<input type="hidden" name="session_role_isRenewal" value="<?php echo $session_role[0]->is_renewal; ?>">
					</div>
					<!-- FILTER -->
					<div class="box-body">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>Pabrik / Plant</label>
									<select class="form-control select2"
											multiple="multiple"
											id="plant"
											name="plant[]"
											style="width: 100%;"
											data-placeholder="Silahkan pilih pabrik / plant">
										<?php
											if ($plant) {
												$output = "";
												foreach ($plant as $pl) {
													$output .= "<option value='" . $pl->plant . "'>" . $pl->nama . "</option>";
												}
												echo $output;
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>Tahun</label>
									<select class="form-control select2"
											multiple="multiple"
											id="tahun"
											name="tahun[]"
											style="width: 100%;"
											data-placeholder="Silahkan pilih tahun">
										<?php

											foreach ($tahun as $dt) {
												echo "<option value='" . $dt->tahun . "'";
												echo ">" . $dt->tahun . "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>Status</label>
									<select class="form-control select2"
											multiple="multiple"
											id="status"
											name="status[]"
											style="width: 100%;"
											data-placeholder="Silahkan pilih status">
										<option value="onprogress">
											On Progress
										</option>
										<option value="drop">Drop</option>
										<option value="finish">Finish</option>
										<option value="completed">Completed</option>
										<option value="stop">Stop</option>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>Tipe</label>
									<select class="form-control select2"
											multiple="multiple"
											id="tipe"
											name="tipe[]"
											style="width: 100%;"
											data-placeholder="Silahkan pilih tipe scoring">
										<?php

											foreach ($tipe as $tp) {
												echo "<option value='" . $tp->id_scoring_tipe . "'";
												echo ">" . $tp->tipe_scoring . "</option>";
											}
										?>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="box-body">
						<table class="table table-bordered table-striped table-responsive table-hover my-datatable-extends-order"
							   data-scrollx="true"
							   data-textright="3"
							   data-textcenter="0-6-7">
							<thead>
								<th>Tanggal</th>
								<th>No Form</th>
								<th>Jenis Scoring</th>
								<th>Supplier/Depo</th>
								<th>Nilai UM Diajukan</th>
								<th>Status</th>
								<th>Tanggal Berakhir</th>
								<th>Aging<br>(days)</th>
								<th>Action</th>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/umb/scoring/page.js"></script>
