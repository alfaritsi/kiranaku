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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<button type="button" class="btn btn-sm btn-default pull-right" id="add_button">Akuisisi Asset</button> 
						<input id="problem" name="problem" value="<?php echo $problem;?>" type="hidden">
						<!--
						<input id="id_merk_tipe" name="id_merk_tipe" value="<?php echo $id_merk_tipe;?>" type="hidden">
						-->
	          		</div>
	          		<!-- /.box-header -->
		          	
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
									<div class="checkbox pull-right" style="margin:0; display: ;">
										<label><input type="checkbox" class="isSelectAllPlant"> All Pabrik</label>
									</div>
				                	<select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
					                		foreach($pabrik as $dt){
												if(($dt->nama=='PT. Kirana Megatara')and(base64_decode($this->session->userdata("-ho-")) == 'y')){
													echo "<option value='".$dt->id_pabrik."' selected>".$dt->nama." - ".$dt->kode."</option>";
												}else{
													echo "<option value='".$dt->id_pabrik."'>".$dt->nama." - ".$dt->kode."</option>";	
												}
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
							<!--
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
							-->
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Kondisi: </label>
				                	<select class="form-control select2" multiple="multiple" id="kondisi" name="kondisi[]" style="width: 100%;" data-placeholder="Pilih Kondisi">
				                  		<?php
					                		foreach($kondisi as $dt){
					                			echo "<option value='".$dt->id_kondisi."'>".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
							<!--
			          		<div class="col-sm-3">
			            		<div class="form-group">
									<button type="button" class="btn btn-sm btn-default" id="button_filter">Filter</button> 
				            	</div>
			            	</div>
							-->
							<!--
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Idle: </label>
				                	<select class="form-control select2" multiple="multiple" id="idle" name="idle[]" style="width: 100%;" data-placeholder="Pilih Idle">
				                  		<?php
											echo "<option value='yes'>Yes</option>";
											echo "<option value='no'>No</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
							-->
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
						<table class="table table-bordered table-striped"
							   id="sspTable">
							<thead>
								<tr>
									<th>Id</th>
									<th>Nama Asset</th>
									<th>Nomor Asset SAP</th>
									<th>Sub Kategori</th>
									<th>Merk</th>
									<th>Tipe</th>
									<th>Pabrik</th>
									<th>Lokasi</th>
									<th>Sub Lokasi</th>
									<th>Area</th>
									<th>Nama User</th>
									<th>Nama Vendor</th>
									<th>Status</th>
									<th>Aktif</th>
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
								<form role="form" class="form-transaksi-it" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel"><b>Akuisisi/ Edit Asset IT</b></h4>
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
															<input type="text" class="form-control cek_data" data-tabel="tbl_inv_aset" data-field="nomor_sap" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP"  required="required">
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
																	echo "<option value='0'>Silahkan Pilih Jenis</option>";
																	// foreach($jenis as $dt){
																		// echo"<option value='".$dt->id_jenis."'>".$dt->nama."</option>";
																	// }
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
															<?php 
															$disabled = (base64_decode($this->session->userdata("-ho-")) == 'n')?"disabled":"";
															?>
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi" <?php echo $disabled; ?>>
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
															<label for="nama_user">Nama User</label>
														</div>
														<div class="col-xs-8">
                                                            <select class="form-control" name="pic"
                                                                    id="pic_ajax"
                                                                    data-placeholder="Cari karyawan (nama atau nik)"  readonly>
                                                                <option></option>
                                                            </select>
															<input type="text" class="form-control" name="nama_user" id="nama_user" placeholder="Nama User" readonly>
															<!--<select class="form-control" multiple="multiple" name="pic[]" id="pic"></select>-->
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nama_vendor">Nama Vendor</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_vendor" id="nama_vendor" placeholder="Nama Vendor"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kode_barang">Nama Asset</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Nama Aset"  required="required">
														</div>
													</div>
												</div>
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="ip_address">IP Address</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="ip_address" id="ip_address" placeholder="IP Address">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="os">OS</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="os" id="os">
																<?php
																	echo "<option value='0'>Pilih OS</option>";
																	foreach($os as $dt){
																		echo"<option value='".$dt->nilai_pilihan."'>".$dt->nilai_pilihan."</option>";
																	}
																?>
																
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="os">Lisensi OS</label>
														</div>
														<div class="col-xs-8">
															<input type='checkbox' class='switch-onoff form-control-hide' name='lisensi_os' id='lisensi_os'>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="sn_os">SN/OS</label>
														</div>
														<div class="col-xs-8">
															<div id="show_sn_os">
																<input type="text" class="form-control" name="sn_os" id="sn_os" placeholder="Serial Number">
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="office_apps">Office Apps</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="office_apps" id="office_apps">
																<?php
																	echo "<option value='0'>Pilih Office</option>";
																	foreach($office as $dt){
																		echo"<option value='".$dt->nilai_pilihan."'>".$dt->nilai_pilihan."</option>";
																	}
																?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="os">Lisensi Office</label>
														</div>
														<div class="col-xs-8">
															<input type='checkbox' class='switch-onoff form-control-hide' name='lisensi_office' id='lisensi_office'>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="sn_office">SN Office</label>
														</div>
														<div class="col-xs-8">
															<div id="show_sn_office">
																<input type="text" class="form-control" name="sn_office" id="sn_office" placeholder="Serial Number Office">
															</div>
														</div>
													</div>
												</div>
												
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="mac_address">Mac Address</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="mac_address" id="mac_address" placeholder="Mac Address">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tipe_processor">Tipe Processor</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="tipe_processor" id="tipe_processor" placeholder="Tipe Processor">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="processor_series">Processor Series</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="processor_series" id="processor_series" placeholder="Processor Series">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="processor_spec">Processor Spec</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="processor_spec" id="processor_spec" placeholder="Processor Spec">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="ram">RAM</label>
														</div>
														<div class="col-xs-8">
															<input type="number" class="form-control" name="ram" id="ram" placeholder="RAM">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="hdd">HDD</label>
														</div>
														<div class="col-xs-8">
															<input type="number" class="form-control" name="hdd" id="hdd" placeholder="HDD">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="merk_monitor">Merk Monitor</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="merk_monitor" id="merk_monitor" placeholder="Merk Monitor">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="ukuran_monitor">Ukuran Monitor</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="ukuran_monitor" id="ukuran_monitor" placeholder="Ukuran Monitor">
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
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="os">Sticker Label</label>
														</div>
														<div class="col-xs-8">
															<input type='checkbox' class='switch-onoff form-control-hide' name='sticker_label' id='sticker_label'>
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
																		echo"<option value='".$dt->id_pabrik."'>".$dt->nama." - ".$dt->kode."</option>";
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
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<!--
										<input id="id_sub_lokasi" name="id_sub_lokasi" type="hidden">
										<input id="id_area" name="id_area" type="hidden">
										-->
										<!--
										<input id="id_kondisi" name="id_kondisi" type="hidden">
										-->
										<input id="pic" name="pic" type="hidden">
										<input id="nama_user" name="nama_user" type="hidden">
										<input id="id_aset" name="id_aset" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>	
			</div>
			<!--modal set pic-->
			<div class="modal fade" id="set_pic_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-pic" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel"><b>Asset Movement</b></h4>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<div class="row">
												<div class="col-xs-4">
													<label for="label_nomor_sap">Nomor SAP / Nama Asset</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nomor_sap_move" class="form-control-static">-</span> / 
													<span id="label_kode_barang_move" class="form-control-static">ICT001</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-4">
													<label for="label_nama_lokasi">Lokasi / Area</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nama_lokasi_move" class="form-control-static">Lokasi</span> / 
													<span id="label_nama_area_move" class="form-control-static">Area</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-4">
													<label for="label_nama_karyawan_move">Pengguna Saat Ini</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nama_karyawan_move" class="form-control-static"></span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-4">
													<label for="label_kondisi">Status</label>
												</div>
												<div class="col-xs-8">
													<span id="label_kondisi_move" class="form-control-static"></span>
												</div>
											</div>
										</div>
										<div class="form-group">		
											<div class="row">
												<div class="col-xs-4">
													<label for="label_alasan">Tipe Movement</label>
												</div>
												<div class="col-xs-8">
													<select class="form-control select2modal" name="alasan" id="alasan"  required="required">
														<?php
															echo '<option value="0">Silahkan Pilih Tipe Movement</option>';
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group hide show_alasan_detail">		
											<div class="row">
												<div class="col-xs-4">
													<label for="label_alasan">Alasan</label>
												</div>
												<div class="col-xs-8">
													<select class="form-control select2modal" name="alasan_detail" id="alasan_detail"  required="required">
														<?php
															echo "<option value='0'>Silahkan Pilih Alasan</option>";
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group">		
											<div class="row">
												<div class="col-xs-4"><label for="nama_user">Pengguna Berikutnya</label></div>
												<div class="col-xs-8">
													<select class="form-control" name="pic" id="set_pic" data-placeholder="Cari karyawan (nama atau nik)">
														<option></option>
													</select>
													<input type="text" class="form-control" name="nama_user" id="nama_user" placeholder="Nama User"  required="required">
													<input type="hidden" class="form-control" name="id_divisi" id="id_divisi">
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="pic_awal" name="pic_awal" type="hidden">
										<input id="id_sub_lokasi_awal" name="id_sub_lokasi_awal" type="hidden">
										<input id="id_area_awal" name="id_area_awal" type="hidden">
										<input id="id_aset" name="id_aset" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_pic">Submit</button>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>	
			</div>
			<!--modal set kondisi-->
			<div class="modal fade" id="set_kondisi_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-kondisi" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel"><b>Set User Asset</b></h4>
									</div>
									<div class="modal-body">
										<div class="form-group">		
											<div class="row">
												<div class="col-xs-3">
													<label for="kondisi">Kondisi</label>
												</div>
												<div class="col-xs-8">
													<!--pada saat status disable, input type hidden harus diaktifkan 25.03.2021-->
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
								
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_kondisi_awal" name="id_kondisi_awal" type="hidden">
										<input id="id_aset" name="id_aset" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_kondisi">Submit</button>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>	
			</div>
			<!--modal set perbaikan-->
			<div class="modal fade" id="set_perbaikan_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-perbaikan" name="form-transaksi-perbaikan" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel"><b>Perbaikan Kerusakan</b></h4>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="label_nomor_sap">Nomor SAP / Nama Asset</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nomor_sap" class="form-control-static">-</span> / 
													<span id="label_kode_barang" class="form-control-static">ICT001</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="label_nama_lokasi">Lokasi / Area</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nama_lokasi" class="form-control-static">Lokasi</span> / 
													<span id="label_nama_area" class="form-control-static">Area</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="label_nama_karyawan">Pengguna</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nama_karyawan" class="form-control-static">Pengguna</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="tanggal_rusak">Tanggal Rusak</label>
												</div>
												<div class="col-xs-4">
													<div class="input-group">
														<span><?php echo date('d.m.Y');?></span>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="tanggal_estimasi">Tanggal Selesai (Estimasi)</label>
												</div>
												<div class="col-xs-3">
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
														<input class="form-control tanggal_current" required readonly name="tanggal_estimasi" id="tanggal_estimasi">
													</div>
												</div>
											</div>
										</div>
										<div class="form-group div_items">
											<div class="row">
												<div class="col-xs-12">
													<fieldset class="fieldset-info">
														<legend>Item Perbaikan</legend>
														<table class="table table-responsive" id="table-maintenance-item">
															<thead>
															<th>Cek</th>
															<th>Item</th>
															<th>Pekerjaan</th>
															<th>Catatan Perbaikan</th>
															</thead>
															<tbody>
															</tbody>
														</table>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<input type="hidden" id="id_aset" name="id_aset">
										<input type="hidden" id="id_jenis" name="id_jenis">
										<button type="button" class="btn btn-primary" name="action_btn_perbaikan">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--modal set perbaikan complete-->
			<div class="modal fade" id="set_perbaikan_complete_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-perbaikan-complete" name="form-transaksi-perbaikan-complete" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel"><b>Perbaikan Kerusakan</b></h4>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="label_nomor_sap">Nomor SAP / Nama Asset</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nomor_sap_complete" class="form-control-static">-</span> / 
													<span id="label_kode_barang_complete" class="form-control-static">ICT001</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="label_nama_lokasi">Lokasi / Area</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nama_lokasi_complete" class="form-control-static">Lokasi</span> / 
													<span id="label_nama_area_complete" class="form-control-static">Area</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="label_nama_karyawan">Pengguna</label>
												</div>
												<div class="col-xs-8">
													<span id="label_nama_karyawan_complete" class="form-control-static">Pengguna</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="label_tanggal_rusak">Tanggal Rusak</label>
												</div>
												<div class="col-xs-4">
													<span id="label_tanggal_rusak_complete" class="form-control-static">Tanggal Rusak</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="label_tanggal_estimasi">Tanggal Selesai (Estimasi)</label>
												</div>
												<div class="col-xs-4">
													<span id="label_tanggal_estimasi_complete" class="form-control-static">Tanggal Estimasi</span>
												</div>
											</div>
										</div>										
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="tanggal_mulai">Tanggal selesai</label>
												</div>
												<div class="col-xs-3">
													<div class="input-group">
														<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
														<input class="form-control tanggal_current_min" required readonly name="tanggal_selesai" id="tanggal_selesai">
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-xs-3">
													<label for="catatan_service">Catatan Perbaikan</label>
												</div>
												<div class="col-xs-8">
													<div class="input-group">
														<textarea rows="3" cols="100%" class="form-control" name="catatan_service" id="catatan_service" placeholder="Masukan Catatan"></textarea>													
													</div>
												</div>
											</div>
										</div>
										<div class="form-group div_items">
											<div class="row">
												<div class="col-xs-12">
													<fieldset class="fieldset-info">
														<legend>Item Perbaikan</legend>
														<table class="table table-responsive" id="table-maintenance-item-complete">
															<thead>
															<th>Item</th>
															<th>Pekerjaan</th>
															<th>Catatan Perbaikan</th>
															</thead>
															<tbody>
															</tbody>
														</table>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<input type="hidden" id="id_aset" name="id_aset">
										<input type="hidden" id="id_jenis" name="id_jenis">
										<input type="hidden" id="id_main" name="id_main">
										<button type="button" class="btn btn-primary" name="action_btn_perbaikan_complete">Submit</button>
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
<?php $this->load->view('maintenance/includes/_modal_pm_history') ?>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/maintenance_it.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>



<style>
    .small-box .icon {
        top: -13px;
    }

    .select2-container--open {
        z-index: 9999999
    }
</style>