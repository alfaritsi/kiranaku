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
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Jenis Pengajuan</th>
				              	<th>Tanggal Pengajuan</th>
				              	<th>Pabrik</th>
								<th>Lokasi</th>
								<th>Jenis</th>
								<th>Merk</th>
								<th>Nomor Asset SAP</th>
								<th>Nomor Polisi</th>
								<th>COP/Perusahaan</th>
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
									echo "<td>".$dt->nomor_polisi."</td>";
									echo "<td>".$dt->cop."</td>";
									echo "<td>".$dt->label_flag."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->proses == 'input'){ 
													echo "<li><a href='javascript:void(0)' class='add' data-add='".$dt->id_aset."'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
												}
												if($dt->proses == 'update'){ 
													echo "<li><a href='javascript:void(0)' class='update' data-update='".$dt->id_aset."'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
												}
												// if($dt->proses == 'delete'){ 
													// echo "<li><a href='javascript:void(0)' class='delete' data-delete='".$dt->id_aset."'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
												// }
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
								<form role="form" class="form-transaksi-hrga-add" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Asset Approval</h4>
									</div>
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab-data" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi" data-toggle="tab">Lokasi</a></li>
										<li><a href="#tab-gambar" data-toggle="tab">Gambar</a></li>
										<li><a href="#tab-komentar" data-toggle="tab">Komentar</a></li>
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
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jenis">Jenis</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="id_jenis" id="id_jenis"  required="required" disabled>
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
															<select class="form-control select2modal" name="id_merk" id="id_merk"  required="required" disabled>
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
															<select class="form-control select2modal" name="id_merk_tipe" id="id_merk_tipe"  required="required" disabled> 
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
															<select class="form-control select2modal" name="id_status" id="id_status"  required="required" disabled>
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
															<select class="form-control select2modal" name="id_kondisi" id="id_kondisi"  required="required" disabled>
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
															<select class="form-control select2modal" name="tahun_pembuatan" id="tahun_pembuatan"  required="required" disabled>
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
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan" placeholder="Tanggal Perolehan"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="pic">PIC</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="pic" id="pic" placeholder="PIC"  required="required" disabled>
															<!--<select class="form-control" multiple="multiple" name="pic[]" id="pic" disabled></select>-->
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
															<select class="form-control select2modal" name="plat" id="plat"  required="required" disabled>
																<?php
																	echo "<option value='0'>Plat</option>";
																	foreach($plat as $dt){
																		echo"<option value='".$dt->kode."'>".$dt->kode."</option>";
																	}
																?>
															</select>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="no_pol" id="no_pol" placeholder="Nopol"  required="required" disabled>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="bel_nomor_polisi" id="bel_nomor_polisi" placeholder="Plat Belakang"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_rangka">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tipe_aset">Tipe Asset</label>
														</div>
														<div class="col-xs-8">
															<select class="form-control select2modal" name="tipe_aset" id="tipe_aset" required="required" disabled>
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
															<select class="form-control select2modal" name="id_pabrik" id="id_pabrik"  required="required" disabled>
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
															<select class="form-control select2modal" name="id_lokasi" id="id_lokasi"  required="required" disabled>
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
															<select class="form-control select2modal" name="id_sub_lokasi" id="id_sub_lokasi"  required="required" disabled>
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
																<select class="form-control select2modal" name="id_area" id="id_area"  disabled>
																	<?php
																		echo "<option value='0'>Silahkan Pilih Sub Lokasi</option>";
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
															<input type="file" multiple="multiple" class="form-control" id="gambar_depan" name="gambar_depan[]" disabled>
														</div>
													</div>
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_belakang" />
														</div>
														<div class="form-group">
															<label for="nama">Gambar Belakang</label>
															<input type="file" multiple="multiple" class="form-control" id="gambar_belakang" name="gambar_belakang[]" disabled>
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
															<input type="file" multiple="multiple" class="form-control" id="gambar_kanan" name="gambar_kanan[]" disabled>
														</div>
													</div>
													<div class="col-xs-6">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_kiri" />
														</div>
														<div class="form-group">
															<label for="nama">Gambar Kiri</label>
															<input type="file" multiple="multiple" class="form-control" id="gambar_kiri" name="gambar_kiri[]" disabled>
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
															<textarea rows="4" id="komentar" name="komentar" class="form-control" placeholder="Masukan Komentar" required="required" ></textarea>														
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset_temp" name="id_aset_temp" type="hidden">
										<input id="nomor_sap" name="nomor_sap" type="hidden">
										<input id="id_jenis" name="id_jenis" type="hidden">
										<input id="id_merk" name="id_merk" type="hidden">
										<input id="id_merk_tipe" name="id_merk_tipe" type="hidden">
										<input id="id_status" name="id_status" type="hidden">
										<input id="id_kondisi" name="id_kondisi" type="hidden">
										<input id="tahun_pembuatan" name="tahun_pembuatan" type="hidden">
										<input id="tanggal_perolehan" name="tanggal_perolehan" type="hidden">
										<input id="pic" name="pic" type="hidden">
										<input id="plat" name="plat" type="hidden">
										<input id="no_pol" name="no_pol" type="hidden">
										<input id="bel_nomor_polisi" name="bel_nomor_polisi" type="hidden">
										<input id="nomor_rangka" name="nomor_rangka" type="hidden">
										<input id="nomor_mesin" name="nomor_mesin" type="hidden">
										<input id="tipe_aset" name="tipe_aset" type="hidden">
										<input id="keterangan" name="keterangan" type="hidden">
										<input id="id_pabrik" name="id_pabrik" type="hidden">
										<input id="id_lokasi" name="id_lokasi" type="hidden">
										<input id="id_sub_lokasi" name="id_sub_lokasi" type="hidden">
										<input id="id_area" name="id_area" type="hidden">
										<input id="gambar_depan" name="gambar_depan" type="hidden">
										<input id="gambar_belakang" name="gambar_belakang" type="hidden">
										<input id="gambar_kanan" name="gambar_kanan" type="hidden">
										<input id="gambar_kiri" name="gambar_kiri" type="hidden">

										<button type="button" class="btn btn-warning" name="reject_btn_add">Reject</button>
										<button type="button" class="btn btn-primary" name="action_btn_add">Approve</button>
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
								<form role="form" class="form-transaksi-hrga-update" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Asset Approval</h4>
									</div>
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab-data2" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail2" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi2" data-toggle="tab">Lokasi</a></li>
										<li><a href="#tab-gambar2" data-toggle="tab">Gambar</a></li>
										<li><a href="#tab-komentar2" data-toggle="tab">Komentar</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane active" id="tab-data2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="nomor_sap">Nomor SAP</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_sap_old" id="nomor_sap_old" placeholder="Nomor SAP"  required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="jenis">Jenis</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_jenis_old" id="nama_jenis_old" placeholder="Nomor SAP"  required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_jenis" id="nama_jenis" placeholder="Nomor SAP"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="id_merk">Merk</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_merk_old" id="nama_merk_old" required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_merk" id="nama_merk" required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="id_merk_tipe">Type</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_merk_tipe_old" id="nama_merk_tipe_old" required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_merk_tipe" id="nama_merk_tipe" required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="status">Status Barang</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_status_old" id="nama_status_old" required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_status" id="nama_status" required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="kondisi">Kondisi</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_kondisi_old" id="nama_kondisi_old" required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_kondisi" id="nama_kondisi" required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="tahun_pembuatan">Tahun Pembuatan</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="tahun_pembuatan_old" id="tahun_pembuatan_old" required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="tahun_pembuatan" id="tahun_pembuatan" required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="tanggal_perolehan">Tgl Perolehan</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control tanggal" name="tanggal_perolehan_old" id="tanggal_perolehan_old" required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control tanggal" name="tanggal_perolehan" id="tanggal_perolehan" required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="pic">PIC</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="pic_old" id="pic_old" placeholder="PIC"  required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="pic" id="pic" placeholder="PIC"  required="required" disabled>
														</div>
													</div>
												</div>
												
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="nomor_polisi">Nomor Polisi</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_polisi_old" id="nomor_polisi_old" placeholder="Nopol"  required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_polisi" id="nomor_polisi" placeholder="Nopol"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="nomor_rangka">Nomor Rangka</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka"  required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="nomor_mesin">Nomor Mesin</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin"  required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin"  required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="tipe_aset">Tipe Asset</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="tipe_aset_old" id="tipe_aset_old" placeholder="PIC"  required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="tipe_aset" id="tipe_aset" placeholder="PIC"  required="required" disabled>
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
															<input type="text" class="form-control" name="nama_pabrik_old" id="nama_pabrik_old" required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_pabrik" id="nama_pabrik" required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="lokasi">Lokasi</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_lokasi_old" id="nama_lokasi_old" required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="sub_lokasi">Sub Lokasi</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_sub_lokasi_old" id="nama_sub_lokasi_old" required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_sub_lokasi" id="nama_sub_lokasi" required="required" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-2">
															<label for="area">Area</label>
														</div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_area_old" id="nama_area_old" placeholder="Nama Area"  required="required" disabled>
														</div>
														<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
														<div class="col-xs-4">
															<input type="text" class="form-control" name="nama_area" id="nama_area" placeholder="Nama Area"  required="required" disabled>
														</div>
													</div>
												</div>
											</div>
											<!--gambar-->
											<div class="tab-pane" id="tab-gambar2">
												<div class="row">
													<div class="col-xs-2">
														<label for="area">Gambar Depan</label>
													</div>
													<div class="col-xs-4">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_depan_old" />
														</div>
													</div>
													<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
													<div class="col-xs-4">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_depan" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-2">
														<label for="area">Gambar Belakang</label>
													</div>
													<div class="col-xs-4">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_belakang_old" />
														</div>
													</div>
													<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
													<div class="col-xs-4">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_belakang" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-2">
														<label for="area">Gambar Kanan</label>
													</div>
													<div class="col-xs-4">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_kanan_old" />
														</div>
													</div>
													<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
													<div class="col-xs-4">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_kanan" />
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-xs-2">
														<label for="area">Gambar Kiri</label>
													</div>
													<div class="col-xs-4">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_kiri_old" />
														</div>
													</div>
													<div class="col-xs-1" align="center"><i class='fa fa-arrow-circle-right'></i></div>
													<div class="col-xs-4">
														<div class="form-group text-center">
															<img class="img-thumbnail img-responsive gambar_kiri" />
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
										<input id="nomor_sap" name="nomor_sap" type="hidden">
										<input id="id_jenis" name="id_jenis" type="hidden">
										<input id="id_merk" name="id_merk" type="hidden">
										<input id="id_merk_tipe" name="id_merk_tipe" type="hidden">
										<input id="id_status" name="id_status" type="hidden">
										<input id="id_kondisi" name="id_kondisi" type="hidden">
										<input id="tahun_pembuatan" name="tahun_pembuatan" type="hidden">
										<input id="tanggal_perolehan" name="tanggal_perolehan" type="hidden">
										<input id="pic" name="pic" type="hidden">
										<input id="plat" name="plat" type="hidden">
										<input id="no_pol" name="no_pol" type="hidden">
										<input id="bel_nomor_polisi" name="bel_nomor_polisi" type="hidden">
										<input id="nomor_rangka" name="nomor_rangka" type="hidden">
										<input id="nomor_mesin" name="nomor_mesin" type="hidden">
										<input id="tipe_aset" name="tipe_aset" type="hidden">
										<input id="keterangan" name="keterangan" type="hidden">
										<input id="id_pabrik" name="id_pabrik" type="hidden">
										<input id="id_lokasi" name="id_lokasi" type="hidden">
										<input id="id_sub_lokasi" name="id_sub_lokasi" type="hidden">
										<input id="id_area" name="id_area" type="hidden">
										<input id="gambar_depan" name="gambar_depan" type="hidden">
										<input id="gambar_belakang" name="gambar_belakang" type="hidden">
										<input id="gambar_kanan" name="gambar_kanan" type="hidden">
										<input id="gambar_kiri" name="gambar_kiri" type="hidden">

										<button type="button" class="btn btn-warning" name="reject_btn_update">Reject</button>
										<button type="button" class="btn btn-primary" name="action_btn_update">Approve</button>
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
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/approval_hrga.js"></script>
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