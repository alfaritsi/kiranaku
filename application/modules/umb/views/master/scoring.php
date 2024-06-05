<!--
/*
	@application  : UMB (Uang Muka Bokar)
	@author       : Lukman Hakim (7143)
	@date         : 01.10.2018
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
						<table class="table table-bordered table-striped my-datatable-order-col2">
							<thead>
								<tr>
									<th>Tipe UM</th>
									<th>Kelas</th>
									<th>Std. Minimal</th>
									<th>Min. Bulan Supply</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($scoring) {
										$output = "";
										foreach ($scoring as $r) {
											$output .= '<tr>';
											$output .= '	<td>' . $r->tipe_scoring . '</td>';
											$output .= '	<td>' . $r->kelas . '</td>';
											$output .= '	<td>' . $r->std_minimal . '</td>';
											$output .= '	<td>' . $r->min_bln_supply . '</td>';
											$output .= '	<td>' . $r->label_active . '</td>';
											$output .= '	<td>';
											$output .= '		<div class="input-group-btn">';
											$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
											$output .= '			<ul class="dropdown-menu pull-right">';
											if ($r->na == 'n') {
												$output .= '			<li><a href="javascript:void(0)" class="detail" data-detail="' . $r->id_mscoring_header . '"><i class="fa fa-search"></i> Detail</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="' . $r->id_mscoring_header . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="nonactive" data-nonactive="' . $r->id_mscoring_header . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_mscoring_header . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											} else {
												$output .= '			<li><a href="javascript:void(0)" class="setactive" data-setactive="' . $r->id_mscoring_header . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_mscoring_header . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											}
											$output .= '			</ul>';
											$output .= '		</div>';
											$output .= '	</td>';
											$output .= '</tr>';
										}
										echo $output;
									}
								?>
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
									style="display:none">Buat Scoring Baru
							</button>
						</div>
					</div>
					<form role="form"
						  class="form-master-scoring">
						<div class="box-body">
							<div class="form-group">
								<label for="id_scoring_tipe">Tipe UM</label>
								<select class="form-control select2"
										name="id_scoring_tipe"
										id="id_scoring_tipe"
										required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Tipe UM-</option>";
										foreach ($tipe as $tp) {
											echo "<option value='" . $tp->id_scoring_tipe . "'>" . $tp->tipe_scoring . "</option>";
										}
									?>
								</select>
							</div>
							<div id="show_kelas"
								 style="display: none">
								<div class="form-group">
									<label for="kelas">Kelas</label>
									<select class="form-control select2"
											name="kelas"
											id="kelas"
											required="required">
										<?php
											echo "<option value=''>-Silahkan Pilih Kelas-</option>";
											foreach ($kelas as $kl) {
												echo "<option value='" . $kl->kelas . "'>" . $kl->kelas . "</option>";
											}
										?>
									</select>
								</div>
								<div class="form-group">
									<label for="batas_bawah">Batas Bawah</label>
									<input type="number"
										   min="0"
										   class="form-control"
										   name="batas_bawah"
										   id="batas_bawah"
										   placeholder="Masukkan Batas Bawah"
										   required="required">
								</div>
								<div class="form-group">
									<label for="batas_bawah">Batas Atas</label>
									<input type="number"
										   min="0"
										   class="form-control"
										   name="batas_atas"
										   id="batas_atas"
										   placeholder="Masukkan Batas Atas"
										   required="required">
								</div>
							</div>
							<div class="form-group">
								<label for="std_minimal">Standar Min.</label>
								<input type="number"
									   min="0"
									   class="form-control"
									   name="std_minimal"
									   id="std_minimal"
									   placeholder="Masukkan Standar Minimal"
									   required="required">
							</div>
							<div class="form-group">
								<label for="min_bln_supply">Min. Bulan Supply</label>
								<input type="number"
									   min="0"
									   max="24"
									   class="form-control cek_min_max"
									   name="min_bln_supply"
									   id="min_bln_supply"
									   placeholder="Masukkan Minimal Bulan Supply"
									   required="required">
							</div>
							<div class="form-group">
								<label for="detail_scoring">Detail Scoring
									<button type="button"
											class="btn btn-sm btn-success add-row">+
									</button>
									<button type="button"
											class="btn btn-sm btn-danger delete-row">-
									</button>
								</label>
							</div>
							<div style="width:100%; overflow-x:auto">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Score<br>Awal</th>
											<th>Score<br>Akhir</th>
											<th>Nilai UM<br>yang diberikan</th>
										</tr>
									</thead>
									<tbody id="input-score-wrapper">
									</tbody>
								</table>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden"
								   name="id_mscoring_header" />
							<button type="button"
									class="btn btn-sm btn-success"
									name="action_btn"
									value="submit">Submit
							</button>
							<button type="button"
									class="btn btn-sm btn-danger"
									name="action_btn"
									value="reject">Reject
							</button>
						</div>
					</form>
				</div>
			</div>
			<!--modal add_modal_detail-->
			<div class="modal fade"
				 id="detail_master_scoring"
				 data-backdrop="static"
				 tabindex="-1"
				 role="dialog"
				 aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md"
					 role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button"
									class="close"
									data-dismiss="modal"
									aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title"
								id="myModalLabel">Detail Master Scoring</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="kode">Tipe UM</label>
								<input type="text"
									   class="form-control"
									   name="tipe_scoring"
									   id="tipe_scoring"
									   placeholder="Masukkkan Kode Batch Program"
									   readonly>
							</div>
							<div class="form-group">
								<label for="std_minimal">Standar Min.</label>
								<input type="text"
									   class="form-control"
									   name="std_minimal"
									   id="std_minimal"
									   placeholder="Masukkkan Nama Batch Program"
									   readonly>
							</div>
							<div class="form-group">
								<label for="min_bln_supply">Min. Bulan Supply</label>
								<input type="text"
									   class="form-control"
									   name="min_bln_supply"
									   id="min_bln_supply"
									   placeholder="Masukkkan Nama Batch Program"
									   readonly>
							</div>
							<div class="form-group">
								<label for="batas_bawah">Batas Bawah</label>
								<input type="text"
									   class="form-control"
									   name="batas_bawah"
									   id="batas_bawah"
									   placeholder="Masukkkan Nama Batch Program"
									   readonly>
							</div>
							<div class="form-group">
								<label for="batas_atas">Batas Atas</label>
								<input type="text"
									   class="form-control"
									   name="batas_atas"
									   id="batas_atas"
									   placeholder="Masukkkan Nama Batch Program"
									   readonly>
							</div>
							<div class="form-group">
								<label for="detail_scoring">Detail Scoring</label>
								<div id='show_detail_scoring'></div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/umb/master/scoring.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


