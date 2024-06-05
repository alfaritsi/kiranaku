<!--
/*
	@application  : UMB (Uang Muka Bokar)
	@author       : Lukman Hakim (7143)
	@date         : 03.10.2018
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
						<table class="table table-bordered table-striped my-datatable-extends">
							<thead>
								<tr>
									<th>Status Dokumen</th>
									<th>Kepemilikan</th>
									<th>Dokumen</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($dokumen) {
										$output = "";
										foreach ($dokumen as $r) {
											$output .= '<tr>';
											$output .= '	<td>' . $r->status . '</td>';
											$output .= '	<td>' . $r->kepemilikan . '</td>';
											$output .= '	<td>' . $generate->add_space_after_comma($r->document) . '</td>';
											$output .= '	<td>' . $r->label_active . '</td>';
											$output .= '	<td>';
											$output .= '		<div class="input-group-btn">';
											$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
											$output .= '			<ul class="dropdown-menu pull-right">';
											if ($r->na == 'n') {
												$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="' . $r->id_mdokumen . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="nonactive" data-nonactive="' . $r->id_mdokumen . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_mdokumen . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											} else {
												$output .= '			<li><a href="javascript:void(0)" class="setactive" data-setactive="' . $r->id_mdokumen . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_mdokumen . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
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
									style="display:none">Buat Dokumen Baru
							</button>
						</div>
					</div>
					<form role="form"
						  class="form-master-dokumen">
						<div class="box-body">
							<div class="form-group">
								<label for="status">Status Dokumen</label>
								<select class="form-control select2"
										name="status"
										id="status"
										required="required">
									<?php
										echo "<option value='0'>Silahkan Pilih</option>";
										echo "<option value='Lajang'>Lajang</option>";
										echo "<option value='Menikah'>Menikah</option>";
										echo "<option value='Cerai Hidup'>Cerai Hidup</option>";
										echo "<option value='Cerai Meninggal'>Cerai Meninggal</option>";
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="kepemilikan">Kepemilikan</label>
								<select class="form-control select2"
										name="kepemilikan"
										id="kepemilikan"
										required="required">
									<?php
										echo "<option value='0'>Silahkan Pilih</option>";
										echo "<option value='Supplier Sendiri'>Supplier Sendiri</option>";
										echo "<option value='Pihak Lain Perorangan'>Pihak Lain Perorangan</option>";
										echo "<option value='Pihak Lain Badan'>Pihak Lain Badan</option>";
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="detail_dokumen">Detail Dokumen
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
									<tbody id="input-dokumen-wrapper">
									</tbody>
								</table>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden"
								   name="id_mdokumen" />
							<button type="button"
									class="btn btn-sm btn-success"
									name="action_btn"
									value="submit">Submit
							</button>
						</div>
					</form>
				</div>
			</div>
			<!--modal add_modal_detail-->
			<div class="modal fade"
				 id="detail_master_dokumen"
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
								id="myModalLabel">Detail Master dokumen</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="alias">Jenis dokumen</label>
								<input type="text"
									   class="form-control"
									   name="jenis"
									   id="jenis"
									   placeholder="Masukkkan Kode Batch Program"
									   readonly>
							</div>
							<div class="form-group">
								<label for="detail_dokumen">Detail dokumen</label>
								<div id='show_detail_dokumen'></div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/umb/master/dokumen.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


