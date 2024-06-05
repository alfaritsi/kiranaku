<!--
/*
@application  : Asset Management
@author		  : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->
<?php $this->load->view('header') ?>
<?php 
$awal = (empty($_POST['awal']))?date('Y-m-d', strtotime(date('Y-m-d').'-3 months')):$_POST['awal'];
$akhir = (empty($_POST['akhir']))?date('Y-m-d'):$_POST['akhir'];
?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<button type="button" class="btn btn-sm btn-default pull-right" id="add_button">Tambah Asset</button> 
	          		</div>
	          		<!-- /.box-header -->
		          	
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Sub Kategori Asset: </label>
				                	<select class="form-control select2" multiple="multiple" id="jenis" name="jenis[]" style="width: 100%;" data-placeholder="Pilih Jenis">
				                  		<?php
					                		foreach($jenis as $dt){
					                			echo "<option value='".$dt->id_jenis."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Merk Asset: </label>
				                	<select class="form-control select2" multiple="multiple" id="merk" name="merk[]" style="width: 100%;" data-placeholder="Pilih Merk">
				                  		<?php
					                		foreach($merk as $dt){
					                			echo "<option value='".$dt->id_merk."'";
					                			echo ">".$dt->nama_jenis." - ".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
				                	<select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
					                		foreach($pabrik as $dt){
					                			echo "<option value='".$dt->id_pabrik."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Lokasi: </label>
				                	<select class="form-control select2" multiple="multiple" id="lokasi" name="lokasi[]" style="width: 100%;" data-placeholder="Pilih Lokasi">
				                  		<?php
					                		foreach($lokasi as $dt){
					                			echo "<option value='".$dt->id_lokasi."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Area: </label>
				                	<select class="form-control select2" multiple="multiple" id="area" name="area[]" style="width: 100%;" data-placeholder="Pilih Area">
				                  		<?php
					                		foreach($area as $dt){
					                			echo "<option value='".$dt->id_area."'";
					                			echo ">".$dt->nama_lokasi." - ".$dt->nama."</option>";
					                		}
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
									<th>Pabrik</th>
									<th>Lokasi</th>
									<th>Sub Lokasi</th>
									<th>Area</th>
									<th>Sub Kategori</th>
									<th>Merk</th>
									<th>Nomor Asset SAP</th>
									<th>Nomor Rangka</th>
									<th>Nomor Mesin</th>
									<th>Nomor Polisi</th>
									<th>COP/Perusahaan</th>
									<th>Kondisi</th>
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
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-hrga" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Tambah/ Edit Asset HRGA</h4>
									</div>
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab-data" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi" data-toggle="tab">Lokasi</a></li>
										<li><a href="#tab-gambar" data-toggle="tab">Gambar</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane active" id="tab-data">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control cek_data" data-tabel="tbl_inv_aset" data-field="nomor_sap" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jenis">Sub Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis" id="id_jenis"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis</option>";
																	foreach($jenis as $dt){
																		echo"<option value='".$dt->id_jenis."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk">Merk</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk" id="id_merk"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Merk</option>";
																	// foreach($merk as $dt){
																		// echo"<option value='".$dt->id_merk."'>".$dt->nama."</option>";
																	// }
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk_tipe">Type</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
																	// foreach($merk_tipe as $dt){
																		// echo"<option value='".$dt->id_tipe."'>".$dt->nama."</option>";
																	// }
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="status">Status Barang</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_status" id="id_status"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Status</option>";
																	foreach($status as $dt){
																		echo"<option value='".$dt->id_status."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kondisi">Kondisi</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Kondisi</option>";
																	foreach($kondisi as $dt){
																		echo"<option value='".$dt->id_kondisi."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tahun_pembuatan">Tahun Pembuatan</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Tahun</option>";
																	for($i=0; $i<=10; $i++){
																		$periode = date('Y', strtotime(date('Y-m-d').'-'.$i.' year'));
																		$ck2 = ($tahun_pembuatan==$periode) ? "selected":"";
																		echo '<option value="'.$periode.'" '.$ck2.'>'.$periode.'</option>'; 
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tanggal_perolehan">Tgl Perolehan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="pic">PIC</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control" multiple="multiple" name="pic[]" id="pic" required="required"></select>
														</div>
													</div>
												</div>
												
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_polisi">Nomor Polisi</label>
														</div>
														<div class="col-xs-2">
															<select class="form-control select2modal" name="plat" id="plat"  required="required">
																<?php
																	echo "<option value='0'>Plat</option>";
																	foreach($plat as $dt){
																		echo"<option value='".$dt->kode."'>".$dt->kode."</option>";
																	}
																?>
															</select>
														</div>
														<div class="col-xs-3">
															<input type="text" maxlength="4" class="form-control" name="no_pol" id="no_pol" placeholder="Nopol"  required="required">
														</div>
														<div class="col-xs-3">
															<input type="text" maxlength="3" onkeyup="this.value = this.value.toUpperCase();" class="form-control" name="bel_nomor_polisi" id="bel_nomor_polisi" placeholder="Plat Belakang"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_rangka">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control cek_data" data-tabel="tbl_inv_aset" data-field="nomor_rangka" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control cek_data" data-tabel="tbl_inv_aset" data-field="nomor_mesin" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tipe_aset">Tipe Asset</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="tipe_aset" id="tipe_aset" required="required">
																<?php
																	echo "<option value='0'>Pilih Tipe Asset</option>";
																	echo "<option value='COP'>COP</option>";
																	echo "<option value='Perusahaan'>Perusahaan</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="keterangan">Keterangan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan" required="required"></textarea>														
														</div>
													</div>
												</div>
											</div>
											<!--lokasi-->
											<div class="tab-pane" id="tab-lokasi">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="pabrik">Pabrik</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Pabrik</option>";
																	foreach($pabrik as $dt){
																		echo"<option value='".$dt->id_pabrik."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="lokasi">Lokasi</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Lokasi</option>";
																	foreach($lokasi as $dt){
																		echo"<option value='".$dt->id_lokasi."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div id='show_depo'>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="sub_lokasi">Sub Lokasi</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Sub Lokasi</option>";
																	// foreach($sub_lokasi as $dt){
																		// echo"<option value='".$dt->id_sub_lokasi."'>".$dt->nama."</option>";
																	// }
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="area">Area</label>
														</div>
														<div class="col-xs-8">
															<div id="show_area">
																<select class="form-control select2modal" name="id_area" id="id_area"  required="required">
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
																		// foreach($area as $dt){
																			// echo"<option value='".$dt->id_area."'>".$dt->nama."</option>";
																		// }
																	?>
																</select>
															</div>	
														</div>
													</div>
												</div>
											</div>
											<!--gambar-->
											<div class="tab-pane" id="tab-gambar">
												<div class="row">
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_depan" />
														</div>
														<div class="form-group">
															<label for="nama">Gambar Depan</label>
															<input type="file" multiple="multiple" class="form-control" id="gambar_depan" name="gambar_depan[]" required>
														</div>
													</div>
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_belakang" />
														</div>
														<div class="form-group">
															<label for="nama">Gambar Belakang</label>
															<input type="file" multiple="multiple" class="form-control" id="gambar_belakang" name="gambar_belakang[]" required>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_kanan" />
														</div>
														<div class="form-group">
															<label for="nama">Gambar Kanan</label>
															<input type="file" multiple="multiple" class="form-control" id="gambar_kanan" name="gambar_kanan[]" required>
														</div>
													</div>
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_kiri" />
														</div>
														<div class="form-group">
															<label for="nama">Gambar Kiri</label>
															<input type="file" multiple="multiple" class="form-control" id="gambar_kiri" name="gambar_kiri[]" required>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset" name="id_aset" type="hidden">
										<input id="hidden_gambar_depan" name="hidden_gambar_depan" type="hidden">
										<input id="hidden_gambar_belakang" name="hidden_gambar_belakang" type="hidden">
										<input id="hidden_gambar_kanan" name="hidden_gambar_kanan" type="hidden">
										<input id="hidden_gambar_kiri" name="hidden_gambar_kiri" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn">Submit</button>
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
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/maintenance_hrga.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>