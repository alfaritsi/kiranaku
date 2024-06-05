<!--
/*
@application  : KODE VENDOR
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<?php 
								echo'<button type="button" class="btn btn-warning" id="cek_vendor">Cek Vendor</button>';
								echo'<button type="button" class="btn btn-success" id="add_button">Input Vendor</button>';	
							?>
						</div>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Tipe Supplier: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_tipe_filter" name="id_tipe[]" style="width: 100%;" data-placeholder="Pilih Tipe Supplier">
				                  		<?php
					                		foreach($tipe as $dt){
					                			echo "<option value='".$dt->id_tipe."'>".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Kategori Vendor: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_kategori_filter" name="id_kategori[]" style="width: 100%;" data-placeholder="Pilih Kategori Vendor">
				                  		<?php
					                		foreach($kategori as $dt){
					                			echo "<option value='".$dt->id_kategori."'>".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status Pengajuan: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_filter" name="status_filter[]" style="width: 100%;" data-placeholder="Pilih Status Pengajuan">
				                  		<?php
											echo "<option value='y' selected>Requested</option>";
											echo "<option value='n'>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
						<table class="table table-bordered table-striped"
							   id="sspTable">
							<thead>
								<tr>
									<th>Id</th>
									<th>Nama Supplier</th>
									<th>Tipe Supplier</th>
									<th>Kategori Vendor</th>
									<th>Total Nilai</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
			
			<!--modal edit-->
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-vendor" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Form Input Master Vendor</h4>
									</div>
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab-general" data-toggle="tab">Input Vendor</a></li>
										<li><a href="#tab-nilai" data-toggle="tab">Input Nilai</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--general-->
											<div class="tab-pane active" id="tab-general">
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="plant">Plant</label>
															<input style="text-transform: uppercase" type="text" class="form-control" name="plant" id="plant" placeholder="Plant"  required="required">
														</div>
														<div class="col-xs-6">
															<label for="acc_group">ACC Group</label>
															<input style="text" type="text" class="form-control" name="acc_group" id="acc_group" placeholder="ACC Group"  required="required">
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="id_tipe">Tipe Supplier</label>
															<select class="form-control select2modal form-control-hide" name="id_tipe" id="id_tipe" required="required">
																<?php
																	echo "<option value='0'>Pilih Tipe Supplier</option>";
																	foreach($tipe as $dt){
																		echo"<option value='".$dt->id_tipe."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="id_kategori">Kategori Supplier</label>
															<select class="form-control select2modal form-control-hide" name="id_kategori" id="id_kategori" required="required">
																<?php
																	echo "<option value='0'>Pilih Kategori Supplier</option>";
																	foreach($kategori as $dt){
																		echo"<option value='".$dt->id_kategori."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="nama">Title</label>
															<select class="form-control select2modal form-control-hide" name="title" id="title" required="required">
																<?php
																	echo "<option value='0'>Pilih Tipe Title</option>";
																	echo"<option value='Company'>Company</option>";
																	echo"<option value='Mr'>Mr</option>";
																	echo"<option value='Mrs'>Mrs</option>";
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="nama">Nama Supplier</label>
															<input style="text-transform: uppercase" type="text" class="form-control" name="nama" id="nama" placeholder="Nama Supplier"  required="required">
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="provinsi">Provinsi</label>
															<select class="form-control select2modal form-control-hide" name="provinsi" id="provinsi" required="required">
																<?php
																	echo "<option value='0'>Pilih Propinsi</option>";
																	foreach($tipe as $dt){
																		echo"<option value='".$dt->id_tipe."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
														<div class="col-xs-6">
															<label for="kota">Kabupaten/ Kota</label>
															<select class="form-control select2modal form-control-hide" name="kota" id="kota" required="required">
																<?php
																	echo "<option value='0'>Pilih Kabupaten</option>";
																	foreach($tipe as $dt){
																		echo"<option value='".$dt->id_tipe."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>													
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="kota">Kota</label>
															<input type="text" class="form-control" name="kota" id="kota" placeholder="Kota"  required="required">
														</div>
														<div class="col-xs-6">
															<label for="alamat">Alamat</label>
															<input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat Supplier"  required="required">
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="kode_pos">Kode Pos</label>
															<input type="text" class="form-control" name="kode_pos" id="kode_pos" placeholder="Kode Pos"  required="required">
														</div>
														<div class="col-xs-6">
															<label for="telepon">Telepon / Fax</label>
															<input type="text" class="form-control" name="telepon" id="telepon" placeholder="Telepon / Fax"  required="required">
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="jenis_barang_jasa1">Jenis Barang Jasa 1</label>
															<input type="text" class="form-control" name="jenis_barang_jasa1" id="jenis_barang_jasa1" placeholder="Jenis Barang Jasa 1"  required="required">
														</div>
														<div class="col-xs-6">
															<label for="jenis_barang_jasa2">Jenis Barang Jasa 2</label>
															<input type="text" class="form-control" name="jenis_barang_jasa2" id="jenis_barang_jasa2" placeholder="Jenis Barang Jasa 2"  required="required">
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="nama_bank">Nama Bank</label>
															<input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="Nama Bank"  required="required">
														</div>
														<div class="col-xs-6">
															<label for="nama_rekening">Nama Pemilik Rekening</label>
															<input type="text" class="form-control" name="nama_rekening" id="nama_rekening" placeholder="Nama Pemilik Rekening"  required="required">
														</div>
													</div>
												</div>	
												<div class="form-group">
													<div class="row">
														<div class="col-xs-6">
															<label for="nomor_rekening">Nomor Rekening Bank</label>
															<input type="text" class="form-control" name="nomor_rekening" id="nomor_rekening" placeholder="Nomor Rekening Bank"  required="required">
														</div>
														<div class="col-xs-6">
															<label for="term_payment">Term of Payment</label>
															<input type="text" class="form-control" name="term_payment" id="term_payment" placeholder="Term of Payment"  required="required">
														</div>
													</div>
												</div>	
												
											</div>
											<!--mrp-->
											<div class="tab-pane" id="tab-nilai">
												<table class="table table-bordered">
													<thead>
														<tr>
														<th>NO</th>
														<th>KRITERIA</th>
														<th>PENILAIAN</th>
														<th>MAKS</th>
														<th>BOBOT</th>
														<th>NILAI</th>
														<th width="11%">TOTAL<br><sup>(Bobot x Nilai)</sup></th>
														<th width="11%">TOTAL<br><sup>(Bobot x Maks)</sup></th>
														<tr>
													</thead>
													<tbody>
														<?php
														$no	= 0;
														foreach($kriteria as $dt){
															$no++;
															echo "<tr>";
															echo "<td>".$no."</td>";
															echo "<td>".$dt->nama."</td>";
															echo "<td>";
																	$arr_nilai = explode('|', substr($dt->list_nilai, 0, -1));
																	if(count($arr_nilai) >= 1 ){
																		for ($brs = 0; $brs < count($arr_nilai); $brs++) {
																			$nilai	= "nilai_".$dt->id_kriteria;
																			$det = explode("#", $arr_nilai[$brs]);
																			echo "<table class='table table-bordered table-striped'>";
																			echo "<tr>";
																			echo "	<td align='center' width='10%'><input type='radio' name='$nilai' value=''></td>";
																			echo "	<td><b>".@$det[1]."</b> (".@$det[2].")<br><b>".@$det[3]."</b> (".@$det[4].")";
																			echo "	<td align='center'>".@$det[5]."</td>";
																			echo "</tr>";
																			echo"</table>";
																		}	
																	}
															echo "</td>";
															echo "<td>".$dt->max."</td>";
															echo "<td>".$dt->bobot."%</td>";
															echo "<td>0</td>";
															echo "<td>0</td>";
															echo "<td>".($dt->max * $dt->bobot)/100 ."</td>";
															echo "</tr>";
														}
														?>
													</tbody>
												</table>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_data" name="id_data" type="hidden">
										<button id="btn_save" type="button" class="btn btn-primary" name="action_btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>	
			</div>
			<!--modal status-->
			<div class="modal fade" id="status_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-input">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Status SAP</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">	
											<div class="row">
												<div class="col-xs-2">
													<label for="code">Code</label>
												</div>
												<div class="col-xs-8">
													<input type="text" class="form-control" name="code" id="code" placeholder="Net Weight"  required="required" disabled>
												</div>
											</div>
										</div>
										<div class="form-group">	
											<div class="row">
												<div class="col-xs-2">
													<label for="description">Description</label>
												</div>
												<div class="col-xs-8">
													<input type="text" class="form-control" name="description" id="description" placeholder="Net Weight"  required="required" disabled>
												</div>
											</div>
										</div>
									
										<div id='show_plant'></div>									
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--modal req-->
			<div class="modal fade" id="add_extend" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-extend">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Extend Material Code</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">		
											<label for="code">Material Code</label>
											<input type="text" class="form-control form-control-hide" name="code" id="code" placeholder="Material Code"  required="required" disabled>
										</div>
										<div class="form-group">	
											<label for="description">Description</label>
											<input type="text" class="form-control" name="description" id="description" placeholder="Description"  required="required" disabled>
										</div>
										<div class="form-group">	
											<label for="plant">Plant</label>
											<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant[]" id="plant" data-placeholder="Pilih Plant" required  disabled>
												<?php
													foreach($plant as $dt){
														echo "<option value='".$dt->plant."'>".$dt->plant."</option>";
													}
												?>
											</select>
										</div>
										<div class="form-group">	
											<label for="plant_extend">Plant Extend</label>
											<div class="checkbox pull-right select_all" style="margin:0; display: ;">
												<label><input type="checkbox" class="isSelectAllPlantExtend form-control-hide"> All Plant Extend</label>
											</div>
											<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_extend[]" id="plant_extend" data-placeholder="Pilih Plant Extend">
											</select>
										</div>
										<!--
										<div class="form-group">	
											<label for="vtweg">Distribution Channel</label>
											<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="vtweg[]" id="vtweg" data-placeholder="Distribution Channel" required disabled>
												<?php
													foreach($dist as $dt){
														echo"<option value='".$dt->vtweg."'>[".$dt->vtweg."] ".$dt->vtext."</option>";
													}
												?>
											</select>
										</div>
										<div class="form-group">	
											<label for="vtweg_extend">Distribution Channel Extend</label>
											<div class="checkbox pull-right select_all" style="margin:0; display: ;">
												<label><input type="checkbox" class="isSelectAllVtwegExtend form-control-hide"> All Distribution Channel Extend</label>
											</div>
											<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="vtweg_extend[]" id="vtweg_extend" data-placeholder="Pilih Distribution Channel Extend">
											</select>
										</div>
										-->
									</div>
									<div class="modal-footer">
										<input id="id_item_spec" name="id_item_spec" type="hidden">
										<input id="plant" name="plant" type="hidden">
										<input id="vtweg" name="vtweg" type="hidden">
										<button id="btn_save" type="button" class="btn btn-primary" name="action_btn_extend">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/vendor/transaksi/input.js"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>