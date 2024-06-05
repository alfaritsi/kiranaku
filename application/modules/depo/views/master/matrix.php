<!--
/*
	@application  : MASTER DEPO
	@author       : Lukman Hakim (7143)
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
									<th>Jenis Matrix</th>
									<th>Nama Matrix</th>
									<th>Bobot</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($matrix_header) {
										$output = "";
										foreach ($matrix_header as $dt) {
											$output .= '<tr>';
											$output .= '	<td>' . strtoupper($dt->jenis_matrix) . '</td>';
											$output .= '	<td>' . $dt->nama_matrix . '</td>';
											$output .= '	<td>' . $dt->bobot . '</td>';
											$output .= '	<td>' . $dt->label_active . '</td>';
											$output .= '	<td>';
											$output .= '		<div class="input-group-btn">';
											$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
											$output .= '			<ul class="dropdown-menu pull-right">';
											if ($dt->na == 'n') {
												$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="' . $dt->id_matrix_header . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
												// $output .= '			<li><a href="javascript:void(0)" class="nonactive" data-nonactive="' . $dt->id_matrix_header . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
											} else {
												// $output .= '			<li><a href="javascript:void(0)" class="setactive" data-setactive="' . $dt->id_matrix_header . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
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
					</div>
					<form role="form" class="form-master-matrix">
						<div class="box-body">
							<div class="form-group">
								<label for="id_matrix">Jenis Matrix</label>
								<input type="hidden" class="form-control" name="id_matrix_hidden" value="0">
								<select class="form-control select2" name="id_matrix" id="id_matrix"  data-placeholder="Pilih Jenis Matrix" required="required" disabled>
									<?php
										echo "<option ></option>";
										foreach ($master_matrix as $tp) {
											echo "<option value='" . $tp->id_matrix . "'>".strtoupper($tp->jenis)." - ".$tp->nama."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="std_minimal">Bobot</label>
								<input type="number" min="0" class="form-control" name="bobot" id="bobot" placeholder="Bobot" required="required" disabled>
							</div>
							<div style="width:100%; overflow-x:auto">
								<label for="id_matrix">
									<button type="button" class="btn btn-sm btn-success add-row">+</button>
									<button type="button" class="btn btn-sm btn-danger delete-row">-</button>
								</label>
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Param Text</th>
											<th>Param Awal</th>
											<th>Param Akhir</th>
											<th>Nilai</th>
										</tr>
									</thead>
									<tbody id="input-score-wrapper">
									</tbody>
								</table>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden" name="id_matrix_header" id="id_matrix_header" value="0" />
							<button type="button" class="btn btn-sm btn-success" name="action_btn" value="submit">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/depo/master/matrix.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


