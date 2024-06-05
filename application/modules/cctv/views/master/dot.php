<!--
/*
	@application  : Monitoring CCTV 
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
						<table class="table table-bordered table-striped my-datatable">
							<thead>
								<tr>
									<th>Titik CCTV</th>
									<th>Lokasi CCTV</th>
									<th>Pabrik</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($dot) {
										$output = "";
										foreach ($dot as $r) {
											$output .= '<tr>'; 
											$output .= '	<td>' . $r->dot . '<br /> '. $r->label_active .'</td>';
											$output .= '	<td>' . $r->lokasi .'</td>';
											$output .= '	<td>' . $r->plant .'</td>';	
											$output .= '	<td>';
											$output .= '		<div class="input-group-btn">';
											$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
											$output .= '			<ul class="dropdown-menu pull-right">';
											if ($r->na == 'n') {
												// $output .= '			<li><a href="javascript:void(0)" class="detail" data-detail="' . $r->id_mdot . '"><i class="fa fa-search"></i> Detail</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="' . $r->id_mdot . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="nonactive" data-nonactive="' . $r->id_mdot . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_mdot . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											} else {
												$output .= '			<li><a href="javascript:void(0)" class="setactive" data-setactive="' . $r->id_mdot . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_mdot . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
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
									style="display:none">Buat Titik CCTV Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-dot">
						<div class="box-body">
							
							<div class="form-group">
								<label for="dot_fieldname">Titik CCTV</label>
								<div>
									<input type="text" class="form-control" name="dot_fieldname" id="dot_fieldname" placeholder="Masukkan Titik CCTV" required="required">
								</div>
							</div>
							<!-- <div class="form-group"> -->
							<div class="row">
								<div class="col-sm-12">
									<fieldset class="fieldset-success">
										<legend class="text-center" >Lokasi Titik CCTV</legend>
										<div class="row">
											<div class="col-sm-12 form-horizontal">
												<div class="nav-tabs-custom" id="divdetail_titik">
													<!-- lokasi -->
													<div class="form-group">
														<label for="lokasi_fieldname_parent">Lokasi</label>
														<div>
															<select class="form-control input-xxlarge " name="lokasi_fieldname_parent" id="lokasi_fieldname_parent" style="width: 100%;"  required="required">
																<option value="0">Silahkan pilih lokasi</option>
									                			<?php
									                				foreach($lokasi_parent as $r){
									                					echo "<option value='".$r->id_lokasi."'>".$r->nama."</option>";
									                				}
									                			?>
															</select>
														</div>
													</div>
													<!-- sublokasi -->
										    		<div class="form-group" >
														<label for="lokasi_fieldname">Sub Lokasi</label>
														<div id="divsublok">
															<select class="form-control input-xxlarge " name="lokasi_fieldname" id="lokasi_fieldname" style="width: 100%;"  required="required">
																<option value="0">Silahkan pilih sublokasi</option>
															</select>
														</div>
														
													</div>
													<!-- area -->
													<div class="form-group">
														<label for="area_fieldname">Area</label>
														<div id="divarea">
															<select class="form-control input-xxlarge " name="area_fieldname" id="area_fieldname" style="width: 100%;"  required="required">
																<option value="0">Silahkan pilih Area</option>
									                		</select>
														</div>
													</div>
													<div class="form-group" id="divpabrik">
														<label for="pabrik_fieldname">Pabrik</label>
														<div>
															<select class="form-control input-xxlarge select2" multiple="multiple" name="pabrik_fieldname[]" id="pabrik_fieldname" style="width: 100%;" placeholder="Silahkan pilih pabrik" required="required">
																<!-- <option value="0">Silahkan pilih pabrik</option> -->
									                			<?php
									                				foreach($plant as $r){
									                					echo "<option value='".$r->plant."'>".$r->plant_name."</option>";
									                				}
									                			?>
															</select>
														<div>
													</div>													
												</div>
											</div>											
										</div>
									</fieldset>
								</div>
							</div>
							<!-- </div> -->
						</div>
						<div class="box-footer">
							<input type="hidden" name="id_mdot" />
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
<script src="<?php echo base_url() ?>assets/apps/js/cctv/master/mdot.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


