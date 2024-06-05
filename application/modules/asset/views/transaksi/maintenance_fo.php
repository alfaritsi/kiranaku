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
								echo'<button type="button" class="btn btn-sm btn-default" id="add_button">Tambah Asset</button>';
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
							<!--
			          		<div class="col-sm-1">
			            		<div class="form-group">
				                	<label> Jam Jalan:</label>
									<input type="number" class="form-control" name="jam_mulai" id="jam_mulai" placeholder="Dari">
				            	</div>
			            	</div>
			          		<div class="col-sm-1">
			            		<div class="form-group">
				                	<label>&nbsp;</label>
									<input type="number" class="form-control" name="jam_sampai" id="jam_sampai" placeholder="Sampai">
				            	</div>
			            	</div>
			          		<div class="col-sm-1">
			            		<div class="form-group">
				                	<label> Umur Bulan:</label>
									<input type="number" class="form-control" name="umur_mulai" id="umur_mulai" placeholder="Dari">
				            	</div>
			            	</div>
			          		<div class="col-sm-1">
			            		<div class="form-group">
				                	<label>&nbsp;</label>
									<input type="number" class="form-control" name="umur_sampai" id="umur_sampai" placeholder="Sampai">
				            	</div>
			            	</div>
							-->
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
							<!--
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Overdue: </label>
				                	<select class="form-control select2" multiple="multiple" id="overdue" name="overdue[]" style="width: 100%;" data-placeholder="Pilih Overdue">
				                  		<?php
				                			echo "<option value='jam'>Jam</option>";
				                			echo "<option value='bulan'>Bulan</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
							-->
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
									<th>Ratio</th>
									<th>Nomor SAP</th>
									<th>Jam Jalan</th>
									<!--
									<th>Umur Bulan</th>
									<th>Jenis Service Next</th>
									<th>Jam Service Next</th>
									<th>Jam Overdue</th>
									<th>Tanggal Service Next</th>
									<th>Bulan Overdue</th>
									-->
									<th>Last Update</th>
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
			<div class="modal fade" id="add_modal" data-backdrop="static"  role="dialog" aria-labelledby="myModalLabel">
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
										<li><a href="#tab-gambar" data-toggle="tab">Gambar</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											
											<!--data-->
											<div class="tab-pane active" id="tab-data">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap_add_modal">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control cek_data" data-tabel="tbl_inv_aset" data-field="nomor_sap" name="nomor_sap" id="nomor_sap_add_modal" placeholder="Nomor SAP">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kategori" id="id_kategori_add_modal"  required="required">
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
															<select class="form-control select2modal" name="id_jenis" id="id_jenis_add_modal"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk_add_modal">Merk</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk" id="id_merk_add_modal"  required="required">
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
															<label for="id_merk_tipe_add_modal">Type</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe_add_modal" >
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group divratio" style="display: none" >		
													<div class="row">
														<div class="col-xs-3">
															<label for="ratio">Ratio *</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="ratio"  placeholder="Ratio">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="status_add_modal">Status Barang</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_status" id="id_status_add_modal"  required="required">
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
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi_add_modal"  required="required">
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
												<div class="form-group divrusak" style="display:none; ">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_jenis_kerusakan">Jenis Kerusakan *</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis_kerusakan"  >
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis Kerusakan</option>";
																	foreach($kerusakan as $dt){
																		echo"<option value='".$dt->id_kerusakan."'>".$dt->kerusakan."</option>";
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
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan_add_modal"  required="required">
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
															<label for="tanggal_perolehan_add_modal">Tgl Perolehan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan_add_modal" placeholder="Tanggal Perolehan"  required="required">
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
															<input type="number" class="form-control" name="spesifikasi" id="spesifikasi_add_modal" placeholder="Satuan"  required="required">
														</div>
														<div class="col-xs-3">
															<select class="form-control select2modal" name="id_satuan" id="id_satuan_add_modal"  required="required">
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
															<label for="nomor_rangka_add_modal">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka_add_modal" placeholder="Nomor Rangka"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin_add_modal">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin_add_modal" placeholder="Nomor Mesin"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1_add_modal">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris1" id="aksesoris1_add_modal" >
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
															<label for="aksesoris2_add_modal">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris2" id="aksesoris2_add_modal">
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
															<label for="keterangan_add_modal">Keterangan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="keterangan_add_modal" name="keterangan" class="form-control" placeholder="Keterangan"></textarea>														
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
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik_add_modal"  required="required">
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
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi_add_modal"  required="required">
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi_add_modal"  required="required">
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
																<select class="form-control select2modal" name="id_area" id="id_area_add_modal"  required="required">
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
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
														<div class="form-group">
															<label for="gambar">Gambar</label>
															<input type="file" multiple="multiple"
															 class="form-control" id="gambar" name="gambar[]">
														</div>
													</div>
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img 
																class="img-thumbnail img-responsive gambar_fo" />
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset_add_modal" name="id_aset" type="hidden">
										<input id="hidden_gambar_fo_add_modal" name="hidden_gambar_fo" type="hidden">
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
			<div class="modal fade" id="modal_perubahan" data-backdrop="static"  role="dialog" aria-labelledby="myModalLabel">
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
										<li><a href="#tab-gambar1" data-toggle="tab">Gambar</a></li>
										<li class="active"><a href="#tab-main1" data-toggle="tab">Jam Jalan</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane" id="tab-data1">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap_modal_perubahan">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap_modal_perubahan" placeholder="Nomor SAP" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kategori" id="id_kategori_modal_perubahan"  disabled>
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
															<select class="form-control select2modal" name="id_jenis" id="id_jenis_modal_perubahan" disabled>
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
															<label for="id_merk_modal_perubahan">Merk</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk" id="id_merk_modal_perubahan" disabled>
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
															<label for="id_merk_tipe_modal_perubahan">Type</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe_modal_perubahan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group divratio" style="display: none" >		
													<div class="row">
														<div class="col-xs-3">
															<label for="ratio">Ratio *</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="ratio"  placeholder="Ratio" disabled>
														</div>
													</div>
												</div>												
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="status_modal_perubahan">Status Barang</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_status" id="id_status_modal_perubahan" disabled>
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
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi_modal_perubahan" disabled>
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
												<div class="form-group divrusak" style="display:none; ">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_jenis_kerusakan">Jenis Kerusakan *</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis_kerusakan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis Kerusakan</option>";
																	foreach($kerusakan as $dt){
																		echo"<option value='".$dt->id_kerusakan."'>".$dt->kerusakan."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tahun_pembuatan_modal_perubahan">Tahun Pembuatan</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan_modal_perubahan" disabled>
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
															<label for="tanggal_perolehan_modal_perubahan">Tgl Perolehan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan_modal_perubahan" placeholder="Tanggal Perolehan"  disabled>
														</div>
													</div>
												</div>
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail1">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_polisi_modal_perubahan">Satuan</label>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="spesifikasi" id="spesifikasi_modal_perubahan" placeholder="Satuan" disabled>
														</div>
														<div class="col-xs-3">
															<select class="form-control select2modal" name="id_satuan" id="id_satuan_modal_perubahan" disabled>
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
															<label for="nomor_rangka_modal_perubahan">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka_modal_perubahan" placeholder="Nomor Rangka" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin_modal_perubahan">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin_modal_perubahan" placeholder="Nomor Mesin" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1_modal_perubahan">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris1" id="aksesoris1_modal_perubahan" disabled>
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
															<label for="aksesoris2_modal_perubahan">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris2" id="aksesoris2_modal_perubahan" disabled>
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
															<label for="keterangan_modal_perubahan">Keterangan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="keterangan_modal_perubahan" name="keterangan" class="form-control" placeholder="Keterangan" disabled></textarea>														
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
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik_modal_perubahan" disabled>
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
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi_modal_perubahan" disabled>
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi_modal_perubahan" disabled>
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
																<select class="form-control select2modal" name="id_area" id="id_area_modal_perubahan" disabled>
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
																	?>
																</select>
															</div>	
														</div>
													</div>
												</div>
											</div>
											<!--gambar-->
											<div class="tab-pane" id="tab-gambar1">
												<div class="row">
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img 
																class="img-thumbnail img-responsive gambar_fo" />
														</div>
													</div>
												</div>
											</div>
											<!--main1-->
											<div class="tab-pane active" id="tab-main1">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jam_jalan_modal_perubahan">Jam Jalan Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="jam_jalan" id="jam_jalan_modal_perubahan" placeholder="Jam Jalan"  required="required">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset_modal_perubahan" name="id_aset" type="hidden">
										<input id="id_jenis_modal_perubahan_hidden" name="id_jenis" type="hidden">
										<input id="pilihan_modal_perubahan" name="pilihan" value="perubahan" type="hidden">
										<input id="hidden_gambar_fo_modal_perubahan" name="hidden_gambar_fo" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_perubahan">
										Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal perubahan-->
			<!--modal perbaikan-->
			<div class="modal fade" id="perbaikan_modal" data-backdrop="static"  role="dialog" aria-labelledby="myModalLabel">
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
										<li><a href="#tab-gambar2" data-toggle="tab">Gambar</a></li>
										<li class="active"><a href="#tab-main2" data-toggle="tab">Proses Perbaikan</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane" id="tab-data2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap_perbaikan_modal">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap_perbaikan_modal" placeholder="Nomor SAP" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kategori" id="id_kategori_perbaikan_modal" disabled>
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
															<select class="form-control select2modal" name="id_jenis" id="id_jenis_perbaikan_modal" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk_perbaikan_modal">Merk</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk" id="id_merk_perbaikan_modal" disabled>
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
															<label for="id_merk_tipe_perbaikan_modal">Type</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe_perbaikan_modal" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group divratio" style="display: none" >		
													<div class="row">
														<div class="col-xs-3">
															<label for="ratio">Ratio *</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="ratio"  placeholder="Ratio" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="status_perbaikan_modal">Status Barang</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_status" id="id_status_perbaikan_modal" disabled>
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
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi_perbaikan_modal" disabled>
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
												<div class="form-group divrusak" style="display:none; ">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_jenis_kerusakan">Jenis Kerusakan *</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis_kerusakan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis Kerusakan</option>";
																	foreach($kerusakan as $dt){
																		echo"<option value='".$dt->id_kerusakan."'>".$dt->kerusakan."</option>";
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
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan_perbaikan_modal" disabled>
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
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan_perbaikan_modal" placeholder="Tanggal Perolehan" disabled>
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
															<input type="text" class="form-control" name="spesifikasi" id="spesifikasi_perbaikan_modal" placeholder="Satuan" disabled>
														</div>
														<div class="col-xs-3">
															<select class="form-control select2modal" name="id_satuan" id="id_satuan_perbaikan_modal" disabled>
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
															<label for="nomor_rangka_perbaikan_modal">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka_perbaikan_modal" placeholder="Nomor Rangka" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin_perbaikan_modal">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin_perbaikan_modal" placeholder="Nomor Mesin" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1_perbaikan_modal">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris1" id="aksesoris1_perbaikan_modal" disabled>
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
															<label for="aksesoris2_perbaikan_modal">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris2" id="aksesoris2_perbaikan_modal" disabled>
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
															<label for="keterangan_perbaikan_modal">Keterangan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="keterangan_perbaikan_modal" name="keterangan" class="form-control" placeholder="Keterangan" disabled></textarea>														
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
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik_perbaikan_modal" disabled>
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
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi_perbaikan_modal" disabled>
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi_perbaikan_modal" disabled>
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
																<select class="form-control select2modal" name="id_area" id="id_area_perbaikan_modal" disabled>
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
																	?>
																</select>
															</div>	
														</div>
													</div>
												</div>
											</div>
											<!--gambar-->
											<div class="tab-pane" id="tab-gambar2">
												<div class="row">
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img 
																class="img-thumbnail img-responsive gambar_fo" />
														</div>
													</div>
												</div>
											</div>
											<!--main1-->
											<div class="tab-pane active" id="tab-main2">
												<input id="pilihan_perbaikan_modal" name="pilihan" value="jam" type="hidden">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jam_jalan_perbaikan_modal">Jam Jalan Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="jam_jalan" id="jam_jalan_perbaikan_modal" placeholder="Jam Jalan"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="operator">Operator</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="operator" id="operator_perbaikan_modal" placeholder="Operator"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="catatan_perbaikan_modal">Catatan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="catatan_perbaikan_modal" name="catatan" class="form-control" placeholder="Catatan"></textarea>														
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset_perbaikan_modal" name="id_aset" type="hidden">
										<input id="id_jenis_perbaikan_modal_hidden" name="id_jenis" type="hidden">
										<input id="pilihan_perbaikan_modal_hidden" name="pilihan" value="perbaikan" type="hidden">
										<input id="hidden_gambar_fo_perbaikan_modal" name="hidden_gambar_fo" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_perbaikan">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal perbaikan-->
			<!--modal histori-->
			<div class="modal fade" id="histori_modal" data-backdrop="static"  role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-fo-perbaikan" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Historical Asset FO</h4>
									</div>
									<ul class="nav nav-tabs">
										<li><a href="#tab-data3" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail3" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi3" data-toggle="tab">Lokasi</a></li>
										<li><a href="#tab-gambar3" data-toggle="tab">Gambar</a></li>
										<li class="active"><a href="#tab-hour" data-toggle="tab">Hour Meter</a></li>
										<li><a href="#tab-maintenance" data-toggle="tab">Maintenance</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane" id="tab-data3">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap_histori_modal">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap_histori_modal" placeholder="Nomor SAP" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kategori" id="id_kategori_histori_modal" disabled>
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
															<select class="form-control select2modal" name="id_jenis" id="id_jenis_histori_modal" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk_histori_modal">Merk</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk" id="id_merk_histori_modal" disabled>
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
															<label for="id_merk_tipe_histori_modal">Type</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe_histori_modal" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group divratio" style="display: none" >		
													<div class="row">
														<div class="col-xs-3">
															<label for="ratio">Ratio *</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="ratio"  placeholder="Ratio" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="status">Status Barang</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_status" id="id_status_histori_modal" disabled>
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
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi_histori_modal" disabled>
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
												<div class="form-group divrusak" style="display:none; ">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_jenis_kerusakan">Jenis Kerusakan *</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis_kerusakan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis Kerusakan</option>";
																	foreach($kerusakan as $dt){
																		echo"<option value='".$dt->id_kerusakan."'>".$dt->kerusakan."</option>";
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
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan_histori_modal" disabled>
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
															<label for="tanggal_perolehan_histori_modal">Tgl Perolehan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan_histori_modal" placeholder="Tanggal Perolehan" disabled>
														</div>
													</div>
												</div>
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail3">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_polisi">Satuan</label>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="spesifikasi" id="spesifikasi_histori_modal" placeholder="Satuan" disabled>
														</div>
														<div class="col-xs-3">
															<select class="form-control select2modal" name="id_satuan" id="id_satuan_histori_modal" disabled>
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
															<label for="nomor_rangka_histori_modal">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka_histori_modal" placeholder="Nomor Rangka" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin_histori_modal">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin_histori_modal" placeholder="Nomor Mesin" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1_histori_modal">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris1" id="aksesoris1_histori_modal" disabled>
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
															<label for="aksesoris2_histori_modal">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris2" id="aksesoris2_histori_modal" disabled>
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
															<label for="keterangan_histori_modal">Keterangan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="keterangan_histori_modal" name="keterangan" class="form-control" placeholder="Keterangan" disabled></textarea>														
														</div>
													</div>
												</div>
												
											</div>
											<!--lokasi-->
											<div class="tab-pane" id="tab-lokasi3">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="pabrik">Pabrik</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik_histori_modal" disabled>
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
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi_histori_modal" disabled>
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi_histori_modal" disabled>
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
																<select class="form-control select2modal" name="id_area" id="id_area_histori_modal" disabled>
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
																	?>
																</select>
															</div>	
														</div>
													</div>
												</div>
											</div>
											<!--gambar-->
											<div class="tab-pane" id="tab-gambar3">
												<div class="row">
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img 
																class="img-thumbnail img-responsive gambar_fo" />
														</div>
													</div>
												</div>
											</div>
											<!--hour meter-->
											<div class="tab-pane active" id="tab-hour">
												<div id='show_hour_meter'></div>
											</div>
											<!--hour maintenance-->
											<div class="tab-pane" id="tab-maintenance">
												<div id='show_maintenance'></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal histori-->
			<!--modal perawatan-->
			<div class="modal fade" id="modal_perawatan" data-backdrop="static"  role="dialog" aria-labelledby="myModalLabel">
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
										<li><a href="#tab-gambar21" data-toggle="tab">Gambar</a></li>
										<li class="active"><a href="#tab-main21" data-toggle="tab">Proses Perawatan</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane" id="tab-data21">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap_modal_perawatan">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap_modal_perawatan" placeholder="Nomor SAP" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_kategori" id="id_kategori_modal_perawatan" disabled>
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
															<select class="form-control select2modal" name="id_jenis" id="id_jenis_modal_perawatan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk_modal_perawatan">Merk</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk" id="id_merk_modal_perawatan" disabled>
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
															<label for="id_merk_tipe_modal_perawatan">Type</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe_modal_perawatan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Type</option>";
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group divratio" style="display: none" >		
													<div class="row">
														<div class="col-xs-3">
															<label for="ratio">Ratio *</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="ratio"  placeholder="Ratio" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="status_modal_perawatan">Status Barang</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_status" id="id_status_modal_perawatan" disabled>
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
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi_modal_perawatan" disabled>
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
												<div class="form-group divrusak" style="display:none; ">	
													<div class="row">
														<div class="col-xs-3">
															<label for="id_jenis_kerusakan">Jenis Kerusakan *</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis_kerusakan" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Jenis Kerusakan</option>";
																	foreach($kerusakan as $dt){
																		echo"<option value='".$dt->id_kerusakan."'>".$dt->kerusakan."</option>";
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
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan_modal_perawatan" disabled>
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
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan_modal_perawatan" placeholder="Tanggal Perolehan"  disabled>
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
															<input type="text" class="form-control" name="spesifikasi" id="spesifikasi_modal_perawatan" placeholder="Satuan"  disabled>
														</div>
														<div class="col-xs-3">
															<select class="form-control select2modal" name="id_satuan" id="id_satuan_modal_perawatan"  disabled>
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
															<label for="nomor_rangka_modal_perawatan">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka_modal_perawatan" placeholder="Nomor Rangka" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin_modal_perawatan">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin_modal_perawatan" placeholder="Nomor Mesin" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1_modal_perawatan">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris1" id="aksesoris1_modal_perawatan" disabled>
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
															<label for="aksesoris2_modal_perawatan">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="aksesoris2" id="aksesoris2_modal_perawatan"  disabled>
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
															<label for="keterangan_modal_perawatan">Keterangan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="keterangan_modal_perawatan" name="keterangan" class="form-control" placeholder="Keterangan" disabled></textarea>														
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
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik_modal_perawatan"  disabled>
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
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi_modal_perawatan"  disabled>
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi_modal_perawatan"  disabled>
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
																<select class="form-control select2modal" name="id_area" id="id_area_modal_perawatan" disabled>
																	<?php
																		echo "<option value='0'>Silahkan Pilih Area</option>";
																	?>
																</select>
															</div>	
														</div>
													</div>
												</div>
											</div>
											<!--gambar-->
											<div class="tab-pane" id="tab-gambar21">
												<div class="row">
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img 
																class="img-thumbnail img-responsive gambar_fo" />
														</div>
													</div>
												</div>
											</div>
											<!--main1-->
											<div class="tab-pane active" id="tab-main21">
												<input id="pilihan_modal_perawatan" name="pilihan" value="jam" type="hidden">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jam_jalan_modal_perawatan">Jam Jalan Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="jam_jalan" id="jam_jalan_modal_perawatan" placeholder="Jam Jalan"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="periode">Periode</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_periode" id="id_periode"  required="required">
																<?php
																	echo "<option value='0'>Silahkan Pilih Periode</option>";
																	foreach($periode as $dt){
																		echo"<option value='".$dt->id_periode."'>".$dt->nama."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="operator">Operator</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="operator" id="operator_modal_perawatan" placeholder="Operator"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="catatan_modal_perawatan">Catatan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="catatan_modal_perawatan" name="catatan" class="form-control" placeholder="Catatan"></textarea>														
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset_modal_perawatan" name="id_aset" type="hidden">
										<input id="id_jenis_modal_perawatan_hidden" name="id_jenis" type="hidden">
										<input id="pilihan_modal_perawatan_hidden" name="pilihan" value="perawatan" type="hidden">
										<input id="hidden_gambar_fo_modal_perawatan" name="hidden_gambar_fo" type="hidden">										
										<button type="button" class="btn btn-primary" name="action_btn_perawatan">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal perawatan-->

			<!--modal compare-->
			<div class="modal fade" id="modal_compare" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel_compare">Compare Ratio</h4>
								</div>
								
								<div class="modal-body">
									<!--main1-->
									<div class="col-sm-12" id="tab-compare">
										<table class="table table-bordered table-striped "
											   id="sspTable2">
											<thead>
												<tr>
													<th>Nomor</th>
													<th>Sub Kategori</th>
													<th>Merk</th>
													<th>Ratio</th>
													<th>Pabrik</th>
												</tr>
								            </thead>
							            </table>
									</div>
									
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal compare-->
			<!--modal Detail-->
			<div class="modal fade" id="detail_modal" data-backdrop="static"  role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Data Asset FO</h4>
								</div>
								<ul class="nav nav-tabs">
									<li><a href="#tab-data_det" data-toggle="tab">Data Asset</a></li>
									<li class="active"><a href="#tab-detail_det" data-toggle="tab">Detail Asset</a></li>
									<li><a href="#tab-lokasi_det" data-toggle="tab">Lokasi</a></li>
									<li><a href="#tab-gambar_det" data-toggle="tab">Gambar</a></li>
								</ul>
								<div class="modal-body">
									<div class="tab-content">
										<!--data-->
										<div class="tab-pane" id="tab-data_det">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_sap_det_modal">Nomor SAP</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_sap" id="nomor_sap_det_modal" placeholder="Nomor SAP" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="kategori">Kategori</label>
													</div>
													<div class="col-xs-8">
														<select class="form-control select2modal" name="id_kategori" id="id_kategori_det_modal" disabled>
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
														<select class="form-control select2modal" name="id_jenis" id="id_jenis_det_modal" disabled>
															<?php
																echo "<option value='0'>Silahkan Pilih Jenis</option>";
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="id_merk_det_modal">Merk</label>
													</div>
													<div class="col-xs-8">
														<select class="form-control select2modal" name="id_merk" id="id_merk_det_modal" disabled>
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
														<label for="id_merk_tipe_det_modal">Type</label>
													</div>
													<div class="col-xs-8">
														<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe_det_modal" disabled>
															<?php
																echo "<option value='0'>Silahkan Pilih Type</option>";
															?>
														</select>
													</div>
												</div>
											</div>
											<div class="form-group divratio" style="display: none" >		
												<div class="row">
													<div class="col-xs-3">
														<label for="ratio">Ratio *</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="ratio"  placeholder="Ratio" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="status">Status Barang</label>
													</div>
													<div class="col-xs-8">
														<select class="form-control select2modal" name="id_status" id="id_status_det_modal" disabled>
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
														<select class="form-control select2modal" name="id_kondisi" id="id_kondisi_det_modal" disabled>
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
											<div class="form-group divrusak" style="display:none; ">		
												<div class="row">
													<div class="col-xs-3">
														<label for="id_jenis_kerusakan">Jenis Kerusakan *</label>
													</div>
													<div class="col-xs-8">
														<select class="form-control select2modal" name="id_jenis_kerusakan" disabled>
															<?php
																echo "<option value='0'>Silahkan Pilih Jenis Kerusakan</option>";
																foreach($kerusakan as $dt){
																	echo"<option value='".$dt->id_kerusakan."'>".$dt->kerusakan."</option>";
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
														<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan_det_modal" disabled>
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
														<label for="tanggal_perolehan_det_modal">Tgl Perolehan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan_det_modal" placeholder="Tanggal Perolehan" disabled>
													</div>
												</div>
											</div>
										</div>
										<!--detail-->
										<div class="tab-pane" id="tab-detail_det">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_polisi">Satuan</label>
													</div>
													<div class="col-xs-3">
														<input type="text" class="form-control" name="spesifikasi" id="spesifikasi_det_modal" placeholder="Satuan" disabled>
													</div>
													<div class="col-xs-3">
														<select class="form-control select2modal" name="id_satuan" id="id_satuan_det_modal" disabled>
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
														<label for="nomor_rangka_det_modal">Nomor Rangka</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka_det_modal" placeholder="Nomor Rangka" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_mesin_det_modal">Nomor Mesin</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin_det_modal" placeholder="Nomor Mesin" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="aksesoris1_det_modal">Rotary Lamp</label>
													</div>
													<div class="col-xs-8">
														<select class="form-control select2modal" name="aksesoris1" id="aksesoris1_det_modal" disabled>
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
														<label for="aksesoris2_det_modal">Buzzer</label>
													</div>
													<div class="col-xs-8">
														<select class="form-control select2modal" name="aksesoris2" id="aksesoris2_det_modal" disabled>
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
														<label for="keterangan_det_modal">Keterangan</label>
													</div>
													<div class="col-xs-8">
														<textarea rows="4" id="keterangan_det_modal" name="keterangan" class="form-control" placeholder="Keterangan" disabled></textarea>														
													</div>
												</div>
											</div>
										</div>
										<!--lokasi-->
										<div class="tab-pane" id="tab-lokasi_det">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="pabrik">Pabrik</label>
													</div>
													<div class="col-xs-8">
														<select class="form-control select2modal" name="id_pabrik" id="id_pabrik_det_modal" disabled>
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
														<select class="form-control select2modal" name="id_lokasi" id="id_lokasi_det_modal" disabled>
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
														<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi_det_modal" disabled>
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
															<select class="form-control select2modal" name="id_area" id="id_area_det_modal" disabled>
																<?php
																	echo "<option value='0'>Silahkan Pilih Area</option>";
																?>
															</select>
														</div>	
													</div>
												</div>
											</div>
										</div>
										<!--gambar-->
										<div class="tab-pane" id="tab-gambar_det">
											<div class="row">
												<div class="col-xs-6">
													<div class="form-group text-center">
														<img 
															class="img-thumbnail img-responsive gambar_fo" />
													</div>
												</div>
											</div>
										</div>
										
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal Detail-->
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/maintenance_fo.js"></script>
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