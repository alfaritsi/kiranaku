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
                            <li class="pull-left header"><strong>Master Kategori Asset & Sub Kategori Asset</strong></li>
							<li><a href="#jenis_asset" id="tab2" data-toggle="tab">Sub Kategori Asset</a></li>
							<li class="active"><a href="#kategori_asset" id="tab1" data-toggle="tab">Kategori Asset</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="kategori_asset">
								<!--lokasi-->
								<div class="box-body">
									<table class="table table-bordered table-striped datatable-custom">
										<thead>
											<tr>
												<th>Kategori</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
								              	foreach($kategori as $k){
								              		echo "<tr>";
								              		echo "<td>".$k->nama."</td>";
								              		echo "<td>".$k->keterangan."</td>";
								              		echo "<td>";
									              		if($k->na == 'n'){
									                        echo "<span class='label label-success'>ACTIVE</span>";
									                      }
									                      if($k->na == 'y'){
									                        echo "<span class='label label-danger'>NOT ACTIVE</span>";
									                      }
								              		echo "</td>";
								              		echo "<td>
								                          <div class='input-group-btn'>
								                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
								                            <ul class='dropdown-menu pull-right'>";
								                      if($k->na == 'n'){
								                        echo "<li><a href='javascript:void(0)' class='edit_kategori' data-edit-kat='".$k->id_kategori."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
								                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='kategori' data-non_active='".$k->id_kategori."'><i class='fa fa-times'></i> Non Aktif</a></li>
								                              <li><a href='javascript:void(0)' class='delete' data-tab='kategori' data-delete='".$k->id_kategori."'><i class='fa fa-trash-o'></i> Hapus</a></li>";

								                      }
								                      if($k->na == 'y'){
								                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='kategori' data-set_active='".$k->id_kategori."'><i class='fa fa-check'></i> Set Aktif</a></li>";
								                      }
								                  	echo "</ul>
								                          </div>
								                        </td>";
								              		echo "</tr>";
								              	}
								            ?>
										</tbody>
									</table>
								</div>
								<!--end lokasi-->
                            </div>
                            <div class="tab-pane" id="jenis_asset">
								<!--begin sub lokasi-->
								<div class="box-body">
									<table class="table table-bordered table-striped datatable-customs">
										<thead>
											<tr>
												<th>Kategori</th>
												<th>Sub Kategori Asset</th>
												<?php if ($pengguna == 'fo'): ?>
													<th>Periode</th>
													<th>Alat Berat</th>
												<?php endif ?>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
								              	foreach($jenis as $j){
								              		echo "<tr>";
								              		echo "<td>".$j->kategori."</td>";
								              		echo "<td>".$j->nama."</td>";

								              		if ($pengguna == 'fo') {
								              			$periode_fo = isset($j->periode) ? $j->periode.' Tahun' : '';
								              			echo "<td>".$periode_fo."</td>";
								              			$alat_berat = $j->berat == 'y' ? 'YES' : 'NO';
								              			echo "<td>".$alat_berat."</td>";
								              		}

								              		echo "<td>".$j->keterangan."</td>";
								              		echo "<td>";
									              		if($j->na == 'n'){
									                        echo "<span class='label label-success'>ACTIVE</span>";
									                      }
									                      if($j->na == 'y'){
									                        echo "<span class='label label-danger'>NOT ACTIVE</span>";
									                      }
								              		echo "</td>";
								              		echo "<td>
								                          <div class='input-group-btn'>
								                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
								                            <ul class='dropdown-menu pull-right'>";
								                      if($j->na == 'n'){
								                        echo "<li><a href='javascript:void(0)' class='edit_jenis' data-edit-jen='".$j->id_jenis."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
								                        	<li><a href='javascript:void(0)' class='non_active' data-tab='jenis_asset' data-non_active='".$j->id_jenis."'><i class='fa fa-times'></i> Non Aktif</a></li>
								                            <li><a href='javascript:void(0)' class='delete' data-tab='jenis_asset' data-delete='".$j->id_jenis."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
								                            if ($pengguna == 'fo' || $pengguna == 'it') {
								                        		echo "<li><a target='_blank' href='".base_url()."asset/master/komponen/".$j->id_jenis."'><i class='fa fa-list'></i> List Komponen Jenis</a></li>";
								                            }
								                      }
								                      if($j->na == 'y'){
								                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='jenis_asset' data-set_active='".$j->id_jenis."'><i class='fa fa-check'></i> Set Aktif</a></li>";
								                      }
								                  	echo "</ul>
								                          </div>
								                        </td>";
								              		echo "</tr>";
								              	}
								            ?>
										</tbody>
									</table>
								</div>
                            </div>
								<!--end sub lokasi-->
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
			        <div class="box box-success" id="box-add-kategori">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-kat"><strong>Buat Kategori Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-kat">Buat Kategori Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-kategori">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="departemen">Kategori</label>
			                <input type="text" class="form-control" name="kategori" id="kategori" placeholder="Masukkkan Kategori" required="required">
			              </div>
			              <div class="form-group">
			                <label for="departemen">Keterangan</label>
			              	<textarea class="form-control" name="ket_kategori" id="ket_kategori" placeholder="Masukan Keterangan" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_kategori">
			              <input type="hidden" name="pengguna" value="<?php echo $pengguna; ?>">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			        <div class="box box-success hidden" id="box-add-jenis">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-jen"><strong>Buat Sub Kategori Asset Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-jen">Buat Sub Kategori Asset Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-jenis">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="departemen">Kategori</label>
			                <select id="kategori_jen" name="kategori_jen" class="form-control select2 col-sm-12">
                            	<option value='0'>Pilih Kategori</option>
                            	<?php
	                				foreach ($kategori as $kat) {
                                        echo "<option value='$kat->id_kategori'>$kat->nama</option>";
                                    }
	                			?>
                            </select>
			              </div>
			              <div class="form-group">
			                <label for="departemen">Sub Kategori Asset</label>
			                <input type="text" class="form-control" name="input_jenis" id="input_jenis" required="required" placeholder="Masukkkan Sub Kategori Asset">
			              </div>
			              <!-- FO ONLY -->
			              <?php if ($pengguna == 'fo'): ?>
			              	<div class="form-group">
				                <label for="periode_fo">Periode (Tahunan)</label>
				                <input type="number" class="form-control" value="0" name="periode_fo" id="periode_fo" required="required">
				            </div>
				            <div class="form-group">
				                <label for="departemen">Alat Berat</label>
				                <select id="alat_berat" name="alat_berat" class="form-control select2 col-sm-12">
	                            	<option value='n'>NO</option>
	                            	<option value='y'>YES</option>
	                            </select>
				            </div>
			              <?php endif ?>
							<div class="form-group">
								<label for="departemen">Keterangan</label>
								<textarea class="form-control" name="ket_jenis" id="ket_jenis" placeholder="Masukan Keterangan" required="required"></textarea>
							</div>
							<!-- <div class="form-group">
								<div class="checkbox">
								<label>
								  <input type="checkbox" id="keep_it" name="keep_it" value="y" > Ceklist Jika Keep IT
								</label>
								</div>
							</div> -->
							<div class="form-group">
								<div class="checkbox">
								<label>
								  <input type="checkbox" id="have_ratio" name="have_ratio" value="y" > Ceklist jika jenis memiliki ratio
								</label>
								</div>
							</div>
			              <!-- IT ONLY -->
			              <?php if ($pengguna == 'it'): ?>
				            <div class="form-group">
								<label for="departemen">PIC</label>
								<select id="pic" name="pic" class="form-control select2 col-sm-12">
									<option value='0'>Pilih PIC</option>
									<?php
										foreach ($pic as $p) {
											echo "<option value='$p->id_karyawan'>$p->nama</option>";
										}
									?>
								</select>
				            </div>
							<div class="form-group"> 
								<div class="checkbox">
								<label>
								  <input type="checkbox" id="keep_it" name="keep_it" value="y" > Ceklist Jika Keep IT
								</label>
								</div>
							</div>
			              <?php endif ?>
						  
						  
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_jenis">
			              <input type="hidden" name="pengguna" value="<?php echo $pengguna; ?>">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			    </div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/kategori.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


