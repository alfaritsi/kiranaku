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
					                			echo ">".$dt->nama." - ".$dt->kode."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Jenis Pengajuan</th>
				              	<th>Tanggal Pengajuan</th>
				              	<th>Pabrik</th>
								<th>Lokasi</th>
								<th>Sub Kategori</th>
								<th>Merk</th>
								<th>Nomor Asset SAP</th>
								<th>Nama User</th>
								<th>Nama Vendor</th>
								<th>Status</th>
								<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($aset_temp as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->label_proses."</td>";
				              		echo "<td>".$dt->tanggal_buat."</td>";
				              		echo "<td>".$dt->nama_pabrik."</td>";
				              		echo "<td>".$dt->nama_lokasi."</td>";
									echo "<td>".$dt->nama_jenis."</td>";
									echo "<td>".$dt->nama_merk."</td>";
									echo "<td>".$dt->nomor_sap."</td>";
									echo "<td>".$dt->nama_karyawan."</td>";
									echo "<td>".$dt->NAMA_VENDOR."</td>";
									echo "<td>".$dt->label_flag."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if(base64_decode($this->session->userdata("-ho-")) == 'y'){
													if($dt->proses == 'input'){ 
														echo "<li><a href='javascript:void(0)' class='edit' data-add='".$dt->id_aset."'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
													}
													if($dt->proses == 'update'){ 
														echo "<li><a href='javascript:void(0)' class='update' data-update='".$dt->id_aset."'  data-id_aset_temp='".$dt->id."'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
													}
												}else{
													if($dt->proses == 'input'){ 
														echo "<li><a href='javascript:void(0)' class='edit' data-add='".$dt->id_aset."'><i class='fa fa-search'></i> Detail</a></li>";
													}
													if($dt->proses == 'update'){ 
														echo "<li><a href='javascript:void(0)' class='update' data-update='".$dt->id_aset."' data-id_aset_temp='".$dt->id."'><i class='fa fa-search'></i> Detail</a></li>";
													}
												}
									echo " 	</ul>
				                          </div>
				                        </td>";
									
									echo "</tr>";
				              	}
				              	?>
			              	</tbody>
			            </table>
			        </div>
				</div>
			</div>

			<!--modal add-->
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-it-add" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Asset Approval</h4>
									</div>
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab-data" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi" data-toggle="tab">Lokasi</a></li>
										<li><a href="#tab-komentar" data-toggle="tab">Komentar</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane active" id="tab-data">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kode_barang">Nomor Aset</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Nomor Aset"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP"  disabled>
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
															<select class="form-control select2modal" name="id_jenis" id="id_jenis"  disabled>
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
															<select class="form-control select2modal" name="id_merk" id="id_merk"  disabled>
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
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe"  disabled>
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
															<select class="form-control select2modal" name="id_status" id="id_status"  disabled>
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
															<label for="tanggal_perolehan">Tgl Perolehan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nama_karyawan">PIC</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_karyawan" id="nama_karyawan" placeholder="PIC"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nama_vendor">Nama Vendor</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_vendor" id="nama_vendor" placeholder="Nama Vendor"  disabled>
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
															<input type="text" class="form-control" name="ip_address" id="ip_address" placeholder="IP Address"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="os">OS</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="os" id="os" disabled>
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
												<div class="form-group hide show_sn_os">		
													<div class="row">
														<div class="col-xs-3">
															<label for="os">SN OS</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="sn_os" id="sn_os" disabled>
																<?php
																	echo "<option value='0'>Pilih SN OS</option>";
																	foreach($sn_os as $dt){
																		echo"<option value='".$dt->sn_os."'>".$dt->sn_os."</option>";
																	}
																?>
																
															</select>
														</div>
													</div>
												</div>
												<!--
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="sn_os">SN/OS</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="sn_os" id="sn_os" placeholder="Serial Number"  disabled>
														</div>
													</div>
												</div>
												-->		
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="office_apps">Office Apps</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="office_apps" id="office_apps" disabled>
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
												<div class="form-group hide show_sn_office">		
													<div class="row">
														<div class="col-xs-3">
															<label for="os">SN Office</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="sn_office" id="sn_office" disabled>
																<?php
																	echo "<option value='0'>Pilih SN Office</option>";
																	foreach($sn_office as $dt){
																		echo"<option value='".$dt->sn_office."'>".$dt->sn_office."</option>";
																	}
																?>
																
															</select>
														</div>
													</div>
												</div>
												
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="mac_address">Mac Address</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="mac_address" id="mac_address" placeholder="Mac Address"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tipe_processor">Tipe Processor</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="tipe_processor" id="tipe_processor" placeholder="Tipe Processor"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="processor_series">Processor Series</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="processor_series" id="processor_series" placeholder="Processor Series"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="processor_spec">Processor Spec</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="processor_spec" id="processor_spec" placeholder="Processor Spec"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="ram">RAM</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="ram" id="ram" placeholder="RAM"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="hdd">HDD</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="hdd" id="hdd" placeholder="HDD"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="merk_monitor">Merk Monitor</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="merk_monitor" id="merk_monitor" placeholder="Merk Monitor"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="ukuran_monitor">Ukuran Monitor</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="ukuran_monitor" id="ukuran_monitor" placeholder="Ukuran Monitor"  disabled>
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
											<div class="tab-pane" id="tab-lokasi">
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
																<select class="form-control select2modal" name="id_area" id="id_area"  disabled>
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
											<!--komentar-->
											<div class="tab-pane" id="tab-komentar">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="komentar">Komentar</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="komentar" name="komentar" class="form-control" placeholder="Masukan Komentar" ></textarea>														
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset" name="id_aset" type="hidden">
										<input id="id_aset_temp" name="id_aset_temp" type="hidden">
										<input id="kode_barang" name="kode_barang" type="hidden">
										<input id="nama_vendor" name="nama_vendor" type="hidden">
										<input id="nomor_sap" name="nomor_sap" type="hidden">
										<input id="id_kategori" name="id_kategori" type="hidden">
										<input id="id_jenis" name="id_jenis" type="hidden">
										<input id="id_merk" name="id_merk" type="hidden">
										<input id="id_merk_tipe" name="id_merk_tipe" type="hidden">
										<input id="id_status" name="id_status" type="hidden">
										<input id="id_kondisi" name="id_kondisi" type="hidden">
										<input id="tahun_pembuatan" name="tahun_pembuatan" type="hidden">
										<input id="tanggal_perolehan" name="tanggal_perolehan" type="hidden">
										<input id="pic" name="pic" type="hidden">
										<input id="ip_address" name="ip_address" type="hidden">
										<input id="os" name="os" type="hidden">
										<!--<input id="lisensi_os" name="lisensi_os" type="hidden">-->
										<input id="sn_os" name="sn_os" type="hidden">
										<input id="office_apps" name="office_apps" type="hidden">
										<!--<input id="lisensi_office" name="lisensi_office" type="hidden">-->
										<input id="sn_office" name="sn_office" type="hidden">
										<input id="mac_address" name="mac_address" type="hidden">
										<input id="tipe_processor" name="tipe_processor" type="hidden">
										<input id="processor_series" name="processor_series" type="hidden">
										<input id="processor_spec" name="processor_spec" type="hidden">
										<input id="ram" name="ram" type="hidden">
										<input id="hdd" name="hdd" type="hidden">
										<input id="merk_monitor" name="merk_monitor" type="hidden">
										<input id="ukuran_monitor" name="ukuran_monitor" type="hidden">
										<input id="keterangan" name="keterangan" type="hidden">
										<input id="id_pabrik" name="id_pabrik" type="hidden">
										<input id="id_lokasi" name="id_lokasi" type="hidden">
										<input id="id_sub_lokasi" name="id_sub_lokasi" type="hidden">
										<input id="id_area" name="id_area" type="hidden">
										<?php 
										if(base64_decode($this->session->userdata("-ho-")) == 'y'){
											echo"
												<button type='button' class='btn btn-warning' name='reject_btn_add'>Reject</button>
												<button type='button' class='btn btn-primary' name='action_btn_add'>Approve</button>
											";	
										}
										?>	
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--end modal add-->
			<!--modal update-->
			<div class="modal fade" id="update_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-it-update" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Asset Approval</h4>
									</div>
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab-data2" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail2" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi2" data-toggle="tab">Lokasi</a></li>
										<li><a href="#tab-komentar2" data-toggle="tab">Komentar</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane active" id="tab-data2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="kode_barang">Kode Asset</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="kode_barang_old" id="kode_barang_old" placeholder="Kode Asset" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="kode_barang" id="kode_barang" placeholder="Kode Asset"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="nomor_sap">Nomor SAP</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_sap_old" id="nomor_sap_old" placeholder="Nomor SAP"  disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="jenis">Sub Kategori</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_jenis_old" id="nama_jenis_old" placeholder="Nomor SAP"  disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_jenis" id="nama_jenis" placeholder="Nomor SAP"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="id_merk">Merk</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_merk_old" id="nama_merk_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_merk" id="nama_merk" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="id_merk_tipe">Type</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_merk_tipe_old" id="nama_merk_tipe_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_merk_tipe" id="nama_merk_tipe" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="status">Status Barang</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_status_old" id="nama_status_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_status" id="nama_status" disabled>
														</div>
													</div>
												</div>
												<!--
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="kondisi">Kondisi</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_kondisi_old" id="nama_kondisi_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_kondisi" id="nama_kondisi" disabled>
														</div>
													</div>
												</div>
												-->
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="tanggal_perolehan">Tgl Perolehan</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control tanggal" name="tanggal_perolehan_old" id="tanggal_perolehan_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan" disabled>
														</div>
													</div>
												</div>
												<!--
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="pic">PIC</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="pic_old" id="pic_old" placeholder="PIC"  disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="pic" id="pic" placeholder="PIC"  disabled>
														</div>
													</div>
												</div>
												-->
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="nama_vendor">Nama Vendor</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_vendor_old" id="nama_vendor_old" placeholder="Nama Vendor"  disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_vendor" id="nama_vendor" placeholder="Nama Vendor"  disabled>
														</div>
													</div>
												</div>
												
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="ip_address">IP Address</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="ip_address_old" id="ip_address_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="ip_address" id="ip_address" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="os">OS</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="os_old" id="os_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="os" id="os" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="sn_os">SN/OS</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="sn_os_old" id="sn_os_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="sn_os" id="sn_os" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="office_apps">Office Apps</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="office_apps_old" id="office_apps_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="office_apps" id="office_apps" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="mac_address">Mac Address</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="mac_address_old" id="mac_address_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="mac_address" id="mac_address" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="tipe_processor">Tipe Processor</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="tipe_processor_old" id="tipe_processor_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="tipe_processor" id="tipe_processor" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="processor_series">Processor Series</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="processor_series_old" id="processor_series_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="processor_series" id="processor_series" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="processor_spec">Processor Spec</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="processor_spec_old" id="processor_spec_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="processor_spec" id="processor_spec" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="ram">RAM</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="ram_old" id="ram_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="ram" id="ram" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="hdd">HDD</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="hdd_old" id="hdd_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="hdd" id="hdd" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="merk_monitor">Merk Monitor</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="merk_monitor_old" id="merk_monitor_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="merk_monitor" id="merk_monitor" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="ukuran_monitor">Ukuran Monitor</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="ukuran_monitor_old" id="ukuran_monitor_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="ukuran_monitor" id="ukuran_monitor" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="keterangan">Keterangan</label>
														</div>
														<div class="col-xs-4">
															<textarea rows="4" id="keterangan_old" name="keterangan_old" class="form-control" placeholder="Keterangan" disabled></textarea>														
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<textarea rows="4" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan" disabled></textarea>														
														</div>
													</div>
												</div>
											</div>
											<!--lokasi-->
											<div class="tab-pane" id="tab-lokasi2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="pabrik">Pabrik</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_pabrik_old" id="nama_pabrik_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_pabrik" id="nama_pabrik" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="lokasi">Lokasi</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_lokasi_old" id="nama_lokasi_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="sub_lokasi">Sub Lokasi</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_sub_lokasi_old" id="nama_sub_lokasi_old" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_sub_lokasi" id="nama_sub_lokasi" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="area">Area</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_area_old" id="nama_area_old" placeholder="Nama Area"  disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_area" id="nama_area" placeholder="Nama Area"  disabled>
														</div>
													</div>
												</div>
											</div>
											<!--komentar-->
											<div class="tab-pane" id="tab-komentar2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="komentar">Komentar</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="komentar" name="komentar" class="form-control" placeholder="Masukan Komentar"></textarea>														
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset" name="id_aset" type="hidden">
										<input id="id_aset_temp" name="id_aset_temp" type="hidden">
										<input id="kode_barang" name="kode_barang" type="hidden">
										<input id="nama_vendor" name="nama_vendor" type="hidden">
										<input id="nomor_sap" name="nomor_sap" type="hidden">
										<input id="id_kategori" name="id_kategori" type="hidden">
										<input id="id_jenis" name="id_jenis" type="hidden">
										<input id="id_merk" name="id_merk" type="hidden">
										<input id="id_merk_tipe" name="id_merk_tipe" type="hidden">
										<input id="id_status" name="id_status" type="hidden">
										<input id="id_kondisi" name="id_kondisi" type="hidden">
										<input id="tahun_pembuatan" name="tahun_pembuatan" type="hidden">
										<input id="tanggal_perolehan" name="tanggal_perolehan" type="hidden">
										<input id="pic" name="pic" type="hidden">
										<input id="ip_address" name="ip_address" type="hidden">
										<input id="os" name="os" type="hidden">
										<input id="sn_os" name="sn_os" type="hidden">
										<input id="office_apps" name="office_apps" type="hidden">
										<input id="mac_address" name="mac_address" type="hidden">
										<input id="tipe_processor" name="tipe_processor" type="hidden">
										<input id="processor_series" name="processor_series" type="hidden">
										<input id="processor_spec" name="processor_spec" type="hidden">
										<input id="ram" name="ram" type="hidden">
										<input id="hdd" name="hdd" type="hidden">
										<input id="merk_monitor" name="merk_monitor" type="hidden">
										<input id="ukuran_monitor" name="ukuran_monitor" type="hidden">
										<input id="keterangan" name="keterangan" type="hidden">
										<input id="id_pabrik" name="id_pabrik" type="hidden">
										<input id="id_lokasi" name="id_lokasi" type="hidden">
										<input id="id_sub_lokasi" name="id_sub_lokasi" type="hidden">
										<input id="id_area" name="id_area" type="hidden">
										<?php 
										if(base64_decode($this->session->userdata("-ho-")) == 'y'){
											echo"
												<button type='button' class='btn btn-warning' name='reject_btn_update'>Reject</button>
												<button type='button' class='btn btn-primary' name='action_btn_update'>Approve</button>
											";	
										}
										?>	

									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--end modal update-->
			
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/approval_it.js"></script>
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

<style>
.small-box .icon{
    top: -13px;
}
</style>