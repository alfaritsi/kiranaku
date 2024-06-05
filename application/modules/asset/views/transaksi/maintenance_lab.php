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
						<div class="btn-group pull-right">
							<button type="button" class="btn btn-sm btn-success" id="excel_button">Export To Excel</button>
							<?php 
								if(base64_decode($_SESSION['-ho-'])=='y'){
									echo"<button type='button' class='btn btn-sm btn-default' id='add_button'>Tambah Asset</button>";	
								}
							?>
							
                        </div>						
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
			          		<div class="col-sm-2">
			            		<div class="form-kondisi">
				                	<label> Kondisi: </label>
				                	<select class="form-control select2" multiple="multiple" id="kondisi" name="kondisi[]" style="width: 100%;" data-placeholder="Pilih Kondisi">
				                  		<?php
				                			echo "<option value='1'>Beroperasi</option>";
				                			echo "<option value='2'>Tidak Beroperasi</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-status">
				                	<label> Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="status" name="status[]" style="width: 100%;" data-placeholder="Pilih Status">
				                  		<?php
				                			echo "<option value='n'>Aktif</option>";
				                			echo "<option value='y'>Non Aktif</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
			          	<div class="row">
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
			          		<div class="col-sm-2">
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
						<table class="table table-bordered table-striped "
							   id="sspTable">
							<thead>
								<tr>
									<th>Id</th>
									<th>Nomor</th>
									<th>Pabrik</th>
									<th>Lokasi</th>
									<th>Sub Lokasi</th>
									<th>Area</th>
									<th>Sub Kategori</th>
									<th>Merk</th>
									<th>Nomor SAP</th>
									<th>Tanggal Perolehan</th>
									<th>Tanggal Maintenance Terakhir</th>
									<th>Tanggal Maintenance Berikutnya</th>
									<th>Kondisi</th>
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
								<form role="form" class="form-transaksi-fo" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Tambah/ Edit Asset FO</h4>
									</div>
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab-data" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi" data-toggle="tab">Lokasi</a></li>
										<!--<li><a href="#tab-gambar" data-toggle="tab">Gambar</a></li>-->
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
															<input type="text" class="form-control cek_data" data-tabel="tbl_inv_aset" data-field="nomor_sap" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kategori" id="id_kategori"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Kategori</option>";
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
														<div class="col-xs-3">
															<label for="jenis">Sub Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis" id="id_jenis"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Sub Kategori</option>";
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
															<select class="form-control select2modal" name="id_merk" id="id_merk">
																<?php
																	echo "<option value='0'>Silahkan Pilih Merk</option>";
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
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe">
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
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
															<select class="form-control select2modal" name="id_status" id="id_status">
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
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan">
																<?php
																	echo "<option value='0'>Silahkan Pilih Tahun</option>";
																	for($i=0; $i<=30; $i++){
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
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_polisi">Satuan</label>
														</div>
														<div class="col-xs-3">
															<input type="number" class="form-control" name="spesifikasi" id="spesifikasi" placeholder="Satuan">
														</div>
														<div class="col-xs-3">
															<select class="form-control select2modal" name="id_satuan" id="id_satuan">
																<?php
																	echo "<option value='0'>Satuan</option>";
																	foreach($satuan as $dt){
																		echo"<option value='".$dt->id_satuan."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_rangka">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris1" id="aksesoris1">
																<?php
																	echo "<option value='0'>Pilih Rotary Lamp</option>";
																	foreach($buzzer as $dt){
																		echo"<option value='".$dt->id_buzzer."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris2">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris2" id="aksesoris2">
																<?php
																	echo "<option value='0'>Pilih Buzzer</option>";
																	foreach($buzzer as $dt){
																		echo"<option value='".$dt->id_buzzer."'>".$dt->nama."</option>";
																	}
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
															<textarea rows="4" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan"></textarea>														
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi">
																<?php
																	echo "<option value='0'>Silahkan Pilih Sub Lokasi</option>";
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
																<select class="form-control select2modal" name="id_area" id="id_area">
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
																	?>
																</select>
															</div>	
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset" name="id_aset" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>	
			</div>
			<!--sampe sini modal edit-->
			<!--modal perubahan-->
			<div class="modal fade" id="modal_perubahan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-fo-perubahan" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Maintenance Asset FO</h4>
									</div>
									<ul class="nav nav-tabs">
										<li><a href="#tab-data1" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail1" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi1" data-toggle="tab">Lokasi</a></li>
										<li class="active"><a href="#tab-main1" data-toggle="tab">Jam Jalan</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane" id="tab-data1">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kategori" id="id_kategori"  disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Kategori</option>";
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
														<div class="col-xs-3">
															<label for="jenis">Sub Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis" id="id_jenis" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Sub Kategori</option>";
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
															<select class="form-control select2modal" name="id_merk" id="id_merk" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Merk</option>";
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
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
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
															<select class="form-control select2modal" name="id_status" id="id_status" disabled>
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
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi"  disabled>
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
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Tahun</option>";
																	for($i=0; $i<=30; $i++){
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
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan"  disabled>
														</div>
													</div>
												</div>
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail1">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_polisi">Satuan</label>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="spesifikasi" id="spesifikasi" placeholder="Satuan" disabled>
														</div>
														<div class="col-xs-3">
															<select class="form-control select2modal" name="id_satuan" id="id_satuan" disabled>
																<?php
																	echo "<option value='0'>Satuan</option>";
																	foreach($satuan as $dt){
																		echo"<option value='".$dt->id_satuan."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_rangka">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris1" id="aksesoris1" disabled>
																<?php
																	echo "<option value='0'>Pilih Rotary Lamp</option>";
																	foreach($buzzer as $dt){
																		echo"<option value='".$dt->id_buzzer."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris2">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris2" id="aksesoris2" disabled>
																<?php
																	echo "<option value='0'>Pilih Buzzer</option>";
																	foreach($buzzer as $dt){
																		echo"<option value='".$dt->id_buzzer."'>".$dt->nama."</option>";
																	}
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
															<textarea rows="4" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan" disabled></textarea>														
														</div>
													</div>
												</div>
												
											</div>
											<!--lokasi-->
											<div class="tab-pane" id="tab-lokasi1">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="pabrik">Pabrik</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik" disabled>
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
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi" disabled>
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Sub Lokasi</option>";
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
																<select class="form-control select2modal" name="id_area" id="id_area" disabled>
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
																	?>
																</select>
															</div>	
														</div>
													</div>
												</div>
											</div>
											<!--main1-->
											<div class="tab-pane active" id="tab-main1">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jam_jalan">Jam Jalan Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="jam_jalan" id="jam_jalan" placeholder="Jam Jalan"  required="required">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset" name="id_aset" type="hidden">
										<input id="id_jenis" name="id_jenis" type="hidden">
										<input id="pilihan" name="pilihan" value="perubahan" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_perubahan">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal perubahan-->
			<!--modal perbaikan-->
			<div class="modal fade" id="perbaikan_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-fo-perbaikan" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Perbaikan Asset FO</h4>
									</div>
									<ul class="nav nav-tabs">
										<li><a href="#tab-data2" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail2" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi2" data-toggle="tab">Lokasi</a></li>
										<li class="active"><a href="#tab-main2" data-toggle="tab">Proses Perbaikan</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane" id="tab-data2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kategori" id="id_kategori" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Kategori</option>";
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
														<div class="col-xs-3">
															<label for="jenis">Sub Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis" id="id_jenis" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Sub Kategori</option>";
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
															<select class="form-control select2modal" name="id_merk" id="id_merk" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Merk</option>";
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
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
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
															<select class="form-control select2modal" name="id_status" id="id_status" disabled>
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
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi" disabled>
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
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Tahun</option>";
																	for($i=0; $i<=30; $i++){
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
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan" disabled>
														</div>
													</div>
												</div>
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_polisi">Satuan</label>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="spesifikasi" id="spesifikasi" placeholder="Satuan" disabled>
														</div>
														<div class="col-xs-3">
															<select class="form-control select2modal" name="id_satuan" id="id_satuan" disabled>
																<?php
																	echo "<option value='0'>Satuan</option>";
																	foreach($satuan as $dt){
																		echo"<option value='".$dt->id_satuan."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_rangka">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris1" id="aksesoris1" disabled>
																<?php
																	echo "<option value='0'>Pilih Rotary Lamp</option>";
																	foreach($buzzer as $dt){
																		echo"<option value='".$dt->id_buzzer."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris2">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris2" id="aksesoris2" disabled>
																<?php
																	echo "<option value='0'>Pilih Buzzer</option>";
																	foreach($buzzer as $dt){
																		echo"<option value='".$dt->id_buzzer."'>".$dt->nama."</option>";
																	}
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
															<textarea rows="4" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan" disabled></textarea>														
														</div>
													</div>
												</div>
												
											</div>
											<!--lokasi-->
											<div class="tab-pane" id="tab-lokasi2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="pabrik">Pabrik</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik" disabled>
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
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi" disabled>
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Sub Lokasi</option>";
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
																<select class="form-control select2modal" name="id_area" id="id_area" disabled>
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
																	?>
																</select>
															</div>	
														</div>
													</div>
												</div>
											</div>
											<!--main1-->
											<div class="tab-pane active" id="tab-main2">
												<input id="pilihan" name="pilihan" value="jam" type="hidden">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jam_jalan">Jam Jalan Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="jam_jalan" id="jam_jalan" placeholder="Jam Jalan"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="operator">Operator</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="operator" id="operator" placeholder="Operator"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="catatan">Catatan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="catatan" name="catatan" class="form-control" placeholder="Catatan"></textarea>														
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset" name="id_aset" type="hidden">
										<input id="id_jenis" name="id_jenis" type="hidden">
										<input id="pilihan" name="pilihan" value="perbaikan" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_perbaikan">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal perbaikan-->
			<!--modal perawatan-->
			<div class="modal fade" id="modal_perawatan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-fo-perawatan" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Perawatan Asset FO</h4>
									</div>
									<ul class="nav nav-tabs">
										<li><a href="#tab-data21" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail21" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi21" data-toggle="tab">Lokasi</a></li>
										<li class="active"><a href="#tab-main21" data-toggle="tab">Proses Perawatan</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane" id="tab-data21">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kategori" id="id_kategori" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Kategori</option>";
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
														<div class="col-xs-3">
															<label for="jenis">Sub Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis" id="id_jenis" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Sub Kategori</option>";
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
															<select class="form-control select2modal" name="id_merk" id="id_merk" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Merk</option>";
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
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
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
															<select class="form-control select2modal" name="id_status" id="id_status" disabled>
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
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi" disabled>
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
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Tahun</option>";
																	for($i=0; $i<=30; $i++){
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
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan"  disabled>
														</div>
													</div>
												</div>
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail21">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_polisi">Satuan</label>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="spesifikasi" id="spesifikasi" placeholder="Satuan"  disabled>
														</div>
														<div class="col-xs-3">
															<select class="form-control select2modal" name="id_satuan" id="id_satuan"  disabled>
																<?php
																	echo "<option value='0'>Satuan</option>";
																	foreach($satuan as $dt){
																		echo"<option value='".$dt->id_satuan."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_rangka">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris1" id="aksesoris1" disabled>
																<?php
																	echo "<option value='0'>Pilih Rotary Lamp</option>";
																	foreach($buzzer as $dt){
																		echo"<option value='".$dt->id_buzzer."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris2">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris2" id="aksesoris2"  disabled>
																<?php
																	echo "<option value='0'>Pilih Buzzer</option>";
																	foreach($buzzer as $dt){
																		echo"<option value='".$dt->id_buzzer."'>".$dt->nama."</option>";
																	}
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
															<textarea rows="4" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan" disabled></textarea>														
														</div>
													</div>
												</div>
												
											</div>
											<!--lokasi-->
											<div class="tab-pane" id="tab-lokasi21">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="pabrik">Pabrik</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik"  disabled>
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
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi"  disabled>
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi"  disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Sub Lokasi</option>";
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
																<select class="form-control select2modal" name="id_area" id="id_area" disabled>
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
																	?>
																</select>
															</div>	
														</div>
													</div>
												</div>
											</div>
											<!--main1-->
											<div class="tab-pane active" id="tab-main21">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tanggal">Tanggal</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal" id="tanggal" placeholder="Tanggal Perawatan"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="operator">Operator</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="operator" id="operator" placeholder="Operator"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="catatan">Catatan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="catatan" name="catatan" class="form-control" placeholder="Catatan"></textarea>														
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="berat" name="berat" type="hidden">
										<input id="id_kategori" name="id_kategori" type="hidden">
										<input id="id_aset" name="id_aset" type="hidden">
										<input id="id_jenis" name="id_jenis" type="hidden">
										<input id="pilihan" name="pilihan" value="perawatan" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_perawatan">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal perawatan-->
			<!--modal histori-->
			<div class="modal fade" id="histori_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-sg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-input">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">History Maintenance</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">	
											<div class="row">
												<div class="col-xs-2">
													<label for="description">Nomor </label>
												</div>
												<div class="col-xs-8">
													<input type="text" class="form-control" name="nomor" id="nomor" placeholder="Nomor"  required="required" disabled>
												</div>
											</div>
										</div>
										<div id='show_histori'></div>									
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--modal histori sampe sini-->	
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/maintenance_lab.js"></script>
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