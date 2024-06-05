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
						<table class="table table-bordered table-striped my-datatable-order-col2">
							<thead>
								<tr>
									<th>Jenis Jaminan</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($jaminan) {
										$output = "";
										foreach ($jaminan as $r) {
											$output .= '<tr>';
											$output .= '	<td>' . $r->jenis . '</td>';
											$output .= '	<td>' . $r->label_active . '</td>';
											$output .= '	<td>';
											$output .= '		<div class="input-group-btn">';
											$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
											$output .= '			<ul class="dropdown-menu pull-right">';
											if ($r->na == 'n') {
												$output .= '			<li><a href="javascript:void(0)" class="detail" data-detail="' . $r->id_mjaminan_header . '"><i class="fa fa-search"></i> Detail</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="' . $r->id_mjaminan_header . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="nonactive" data-nonactive="' . $r->id_mjaminan_header . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_mjaminan_header . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											} else {
												$output .= '			<li><a href="javascript:void(0)" class="setactive" data-setactive="' . $r->id_mjaminan_header . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_mjaminan_header . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
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
									style="display:none">Buat Jaminan Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-jaminan">
						<div class="box-body">
							<div class="form-group">		
								<label for="jenis">Jenis Jaminan</label>
								<select class="form-control select2" name="jenis" id="jenis" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih Jenis Jaminan-</option>";
										foreach($jenis as $jn){
											echo"<option value='".$jn->jenis."'>".$jn->jenis."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="detail_jaminan">Detail Jaminan
									<button type="button" class="btn btn-sm btn-success add-row">+</button>								
									<button type="button" class="btn btn-sm btn-danger delete-row">-</button>
								</label>
							</div>
							<div style="width:100%; overflow-x:auto">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Detail Jaminan</th>
											<th>% Discount</th>
										</tr>
									</thead>
									<tbody id="input-jaminan-wrapper">
									</tbody>
								</table>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden"
								   name="id_mjaminan_header" />
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
			<div class="modal fade" id="detail_master_jaminan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Detail Master Jaminan</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
		                		<label for="alias">Jenis Jaminan</label>
		                		<input type="text" class="form-control" name="jenis_modal" placeholder="Masukkkan" readonly>
							</div>
							<div class="form-group">
								<label for="detail_jaminan">Detail Jaminan</label>
								<div id='show_detail_jaminan'></div>
							</div>
		            	</div>
					</div>
				</div>	
			</div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/umb/master/jaminan.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


