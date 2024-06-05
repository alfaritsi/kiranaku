<!--
/*
@application  : Equipment Management
@author     : Lukman Hakim (7143)
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
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li class="pull-left header"><?php echo $title; ?></li>
                            <li><a href="#area" id="tab-area" data-toggle="tab">Area</a></li>
							<li><a href="#sub_lokasi" id="tab-sublokasi" data-toggle="tab">Sub Lokasi</a></li>
							<li class="active"><a href="#lokasi" id="tab-lokasi" data-toggle="tab">Lokasi</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="lokasi">
								<!--lokasi-->
								<div class="box-body">
									<table class="table table-bordered table-striped my-datatable-order-col2">
										<thead>
											<tr>
												<th>Lokasi</th>
												<th>Pengguna</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if ($lokasi) {
													$output = "";
													$no		= 1;
													foreach ($lokasi as $r) {
														$no++;
														$output .= '<tr>';
														$output .= '	<td>' . $r->nama . '</td>';
														$output .= '	<td>';
																			$pengguna_list = explode(".", $r->pengguna);
																			foreach ($pengguna_list as $p) {
																				$output .= (!empty($p))?"<button class='btn btn-sm btn-info btn-role'>".$p."</button>":"";
																			}
														$output .= '	</td>';
														$output .= '	<td>' . $r->keterangan . '</td>';
														$output .= '	<td>' . $r->label_active . '</td>';
														$output .= '	<td>';
														$output .= '		<div class="input-group-btn">';
														$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
														$output .= '			<ul class="dropdown-menu pull-right">';
														if ($r->na == 'n') {
															$output .= '			<li><a href="javascript:void(0)" class="edit_lokasi" data-tab="lokasi" data-edit-lokasi="' . $r->id_lokasi . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
															$output .= '			<li><a href="javascript:void(0)" class="non_active" data-tab="lokasi" data-non_active="' . $r->id_lokasi . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
															$output .= '			<li><a href="javascript:void(0)" class="delete" data-tab="lokasi" data-delete="' . $r->id_lokasi . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
														} else {
															$output .= '			<li><a href="javascript:void(0)" class="set_active" data-tab="lokasi" data-set_active="' . $r->id_lokasi . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
															$output .= '			<li><a href="javascript:void(0)" class="delete" data-tab="lokasi" data-delete="' . $r->id_lokasi . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
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
								<!--end lokasi-->
                            </div>
                            <div class="tab-pane" id="sub_lokasi">
								<!--begin sub lokasi-->
								<div class="box-body">
									<table class="table table-bordered table-striped my-datatable-order-col2">
										<thead>
											<tr>
												<th>Sub Lokasi</th>
												<th>Lokasi</th>
												<th>Pengguna</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if ($sub_lokasi) {
													$output = "";
													$no		= 1;
													foreach ($sub_lokasi as $r) {
														$no++;
														$output .= '<tr>';
														$output .= '	<td>' . $r->nama . '</td>';
														$output .= '	<td>' . $r->nama_lokasi . '</td>';
														$output .= '	<td>';
																			$pengguna_list = explode(".", $r->pengguna);
																			foreach ($pengguna_list as $p) {
																				$output .= (!empty($p))?"<button class='btn btn-sm btn-info btn-role'>".$p."</button>":"";
																			}
														$output .= '	</td>';
														$output .= '	<td>' . $r->keterangan . '</td>';
														$output .= '	<td>' . $r->label_active . '</td>';
														$output .= '	<td>';
														$output .= '		<div class="input-group-btn">';
														$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
														$output .= '			<ul class="dropdown-menu pull-right">';
														if ($r->na == 'n') {
															$output .= '			<li><a href="javascript:void(0)" class="edit_sublokasi" data-tab="sub_lokasi" data-edit-sublokasi="' . $r->id_sub_lokasi . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
															$output .= '			<li><a href="javascript:void(0)" class="non_active" data-tab="sub_lokasi" data-non_active="' . $r->id_sub_lokasi . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
															$output .= '			<li><a href="javascript:void(0)" class="delete" data-tab="sub_lokasi" data-delete="' . $r->id_sub_lokasi . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
														} else {
															$output .= '			<li><a href="javascript:void(0)" class="set_active" data-tab="sub_lokasi" data-set_active="' . $r->id_sub_lokasi . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
															$output .= '			<li><a href="javascript:void(0)" class="delete" data-tab="sub_lokasi" data-delete="' . $r->id_sub_lokasi . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
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
								<!--end sub lokasi-->
                            </div>
                            <div class="tab-pane" id="area">
								<!--begin area-->
								<div class="box-body">
									<table class="table table-bordered table-striped my-datatable-order-col2">
										<thead>
											<tr>
												<th>Area</th>
												<th>Sub Lokasi</th>
												<th>Lokasi</th>
												<th>Pengguna</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if ($area) {
													$output = "";
													$no		= 1;
													foreach ($area as $r) {
														$no++;
														$output .= '<tr>';
														$output .= '	<td>' . $r->nama . '</td>';
														$output .= '	<td>' . $r->nama_sub_lokasi . '</td>';
														$output .= '	<td>' . $r->nama_lokasi . '</td>';
														$output .= '	<td>';
																			$pengguna_list = explode(".", $r->pengguna);
																			foreach ($pengguna_list as $p) {
																				$output .= (!empty($p))?"<button class='btn btn-sm btn-info btn-role'>".$p."</button>":"";
																			}
														$output .= '	</td>';
														$output .= '	<td>' . $r->keterangan . '</td>';
														$output .= '	<td>' . $r->label_active . '</td>';
														$output .= '	<td>';
														$output .= '		<div class="input-group-btn">';
														$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
														$output .= '			<ul class="dropdown-menu pull-right">';
														if ($r->na == 'n') {
															$output .= '			<li><a href="javascript:void(0)" class="edit_area" data-tab="area" data-edit-area="' . $r->id_area . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
															$output .= '			<li><a href="javascript:void(0)" class="non_active" data-tab="area" data-non_active="' . $r->id_area . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
															$output .= '			<li><a href="javascript:void(0)" class="delete" data-tab="area" data-delete="' . $r->id_area . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
														} else {
															$output .= '			<li><a href="javascript:void(0)" class="set_active" data-tab="area" data-set_active="' . $r->id_area . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
															$output .= '			<li><a href="javascript:void(0)" class="delete" data-tab="area" data-delete="' . $r->id_area . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
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
								<!--end area-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <!-- <div class="nav-tabs-custom hidden">
                        <ul class="nav nav-tabs pull-right">
                            <li class="pull-left header"><?php echo $title_form; ?></li>
							<li><button type="button" class="btn btn-sm btn-default" id="btn-new" style="display:none">+</button></li>
                        </ul>
						<div class="pull-right">
						</div>
							<form role="form"
								  class="form-master-lokasi">
								<div class="box-body">
									<div class="form-group">
										<label for="nama">Lokasi</label>
										<input type="text"
											   class="form-control"
											   name="nama"
											   id="nama"
											   placeholder="Masukkan Nama Lokasi"
											   required="required">
									</div>
									<div class="form-group">
										<label for="pengguna">Pengguna</label>
										<div class="checkbox pull-right select_all" style="margin:0; display: ;">
											<label><input type="checkbox" class="isSelectAllpengguna"> Select All</label>
										</div>
										<select class="form-control select2 col-sm-12" multiple="multiple" name="pengguna[]" id="pengguna" data-placeholder="Silahkan pengguna" required>
											<option value='IT'> IT</option>
											<option value='HRGA'> HRGA</option>
											<option value='FO'> FO</option>
										</select>
									</div>
									<div class="form-group">
										<label for="keterangan">Keterangan</label>
										<input type="text"
											   class="form-control"
											   name="keterangan"
											   id="keterangan"
											   placeholder="Masukkan Keterangan"
											   required="required">
									</div>
									
								</div>
								<div class="box-footer">
									<input type="hidden"
										   name="id_lokasi" />
									<input type="hidden"
										   name="kode" 
										   value="<?php echo str_pad($no, 3, 0, STR_PAD_LEFT);?>"/>
									<button type="button"
											class="btn btn-sm btn-success"
											name="action_btn"
											value="submit">Submit
									</button>
								</div>
							</form>
                    </div>
 -->
                    <!-- ===================== -->

                    <div class="box box-success" id="box-add-lokasi">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-lokasi"><strong>Buat Lokasi Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-lokasi">Buat Lokasi Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-lokasi">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="kegiatan">Lokasi</label>
			                <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Masukkkan Nama Lokasi" required="required">
			              </div>
			              <div class="form-group">
							<label for="pengguna">Pengguna</label>
							<div class="checkbox pull-right select_all" style="margin:0; display: ;">
								<label><input type="checkbox" class="isSelectAllpengguna"> Select All</label>
							</div>
							<select class="form-control select2 col-sm-12" multiple="multiple" name="pengguna[]" id="pengguna" data-placeholder="Silahkan Pilih Pengguna" required>
								<option value='IT'> IT</option>
								<option value='HRGA'> HRGA</option>
								<option value='FO'> FO</option>
							</select>
						  </div>
			              <div class="form-group">
			                <label for="kegiatan">Keterangan</label>
			              	<textarea class="form-control" name="ket_lokasi" id="ket_lokasi" placeholder="Masukan Keterangan" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_lokasi">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>


			        <div class="box box-success hidden" id="box-add-sublokasi">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-sublokasi"><strong>Buat Sub Lokasi Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-sublokasi">Buat Sub Lokasi Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-sublokasi">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="departemen">Lokasi</label>
			                <select id="lokasi_opt" name="lokasi_opt" class="form-control select2 col-sm-12">
                            	<option value='0'>Pilih Lokasi</option>
                            	<?php
	                				foreach ($lokasi as $lok) {
                                        echo "<option value='$lok->id_lokasi'>$lok->nama</option>";
                                    }
	                			?>
                            </select>
			              </div>
			              <div class="form-group">
			                <label for="Service">Sub Lokasi</label>
			                <input type="text" class="form-control" name="sublokasi" id="sublokasi" placeholder="Masukkkan Nama Sub Lokasi" required="required">
			              </div>
			              <div class="form-group">
			                <label for="Service">Keterangan</label>
			              	<textarea class="form-control" name="ket_sublokasi" id="ket_sublokasi" placeholder="Masukan Keterangan Sub Lokasi" required="required"></textarea>
			              </div>
			              <div class="form-group">
							<label for="pengguna">Pengguna</label>
							<div class="checkbox pull-right select_all" style="margin:0; display: ;">
								<label><input type="checkbox" class="isSelectAllpengguna2"> Select All</label>
							</div>
							<select class="form-control select2 col-sm-12" multiple="multiple" name="pengguna2[]" id="pengguna2" data-placeholder="Silahkan Pilih Pengguna" required>
								<option value='IT'> IT</option>
								<option value='HRGA'> HRGA</option>
								<option value='FO'> FO</option>
							</select>
						  </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_sub_lokasi">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			        <div class="box box-success hidden" id="box-add-area">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-area"><strong>Buat Area Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-area">Buat Area Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-area">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="departemen">Lokasi</label>
			                <select id="lokasi_opt2" name="lokasi_opt2" class="form-control select2 col-sm-12" required="required">
                            	<option value='0'>Pilih Lokasi</option>
                            	<?php
	                				foreach ($lokasi as $lok) {
                                        echo "<option value='$lok->id_lokasi'>$lok->nama</option>";
                                    }
	                			?>
                            </select>
			              </div>
			              <div class="form-group">
			                <label for="departemen">Sub Lokasi</label>
			                <select id="sublokasi_opt" name="sublokasi_opt" class="form-control select2 col-sm-12" required="required">
                            </select>
			              </div>
			              <div class="form-group">
			                <label for="satuan">Area</label>
			                <input type="text" class="form-control" name="area" id="area" placeholder="Masukkkan Area" required="required">
			              </div>
			              <div class="form-group">
			                <label for="satuan">Keterangan</label>
			              	<textarea class="form-control" name="ket_area" id="ket_area" placeholder="Masukan Keterangan Area" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_area">
			              <input type="hidden" name="pengguna_sublok">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>


                </div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/lokasi.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


