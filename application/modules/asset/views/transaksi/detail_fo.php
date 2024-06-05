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
						<input type="hidden" name="alat" id="alat" value="<?php echo $alat;?>">
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
			            		<div class="form-status">
				                	<label> Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="status" name="status[]" style="width: 100%;" data-placeholder="Pilih Status">
				                  		<?php
				                			echo "<option value='n'>On Progress</option>";
				                			echo "<option value='y'>Done</option>";
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
									<th>Nomor SAP</th>
									<th>Tanggal Mulai</th>
									<th>Tanggal Selesai</th>
									<th>Jenis Tindakan</th>
									<th>Operator</th>
									<th>Catatan</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
				            </thead>
			            </table>			        </div>
				</div>
			</div>
			
			<!--modal detail perbaikan-->
			<div class="modal fade" id="modal_detail_perbaikan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Detail Maintenance Asset FO</h4>
								</div>
								<ul class="nav nav-tabs">
									<li><a href="#tab-data1" data-toggle="tab">Data Asset</a></li>
									<li><a href="#tab-detail1" data-toggle="tab">Detail Asset</a></li>
									<li><a href="#tab-lokasi1" data-toggle="tab">Lokasi</a></li>
									<li class="active"><a href="#tab-main1" data-toggle="tab">Proses Perbaikan</a></li>
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
														<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="kategori">Kategori</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_kategori" id="nama_kategori" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="jenis">Sub Kategori</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_jenis" id="nama_jenis" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="id_merk">Merk</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_merk" id="nama_merk" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="id_merk_tipe">Type</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_merk_tipe" id="nama_merk_tipe" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="status">Status Barang</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_status" id="nama_status" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="kondisi">Kondisi</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_kondisi" id="nama_kondisi" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tahun_pembuatan">Tahun Pembuatan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tahun_pembuatan" id="tahun_pembuatan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal_perolehan">Tgl Perolehan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_perolehan" id="tanggal_perolehan" disabled>
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
														<input type="text" class="form-control" name="spesifikasi" id="spesifikasi" disabled>
													</div>
													<div class="col-xs-3">
														<input type="text" class="form-control" name="nama_satuan" id="nama_satuan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_rangka">Nomor Rangka</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka"  disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_mesin">Nomor Mesin</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin"  disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="aksesoris1">Rotary Lamp</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_aksesoris1" id="nama_aksesoris1" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="aksesoris2">Buzzer</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_aksesoris2" id="nama_aksesoris2" disabled>
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
														<input type="text" class="form-control" name="nama_pabrik" id="nama_pabrik" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="lokasi">Lokasi</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" disabled>
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
														<input type="text" class="form-control" name="nama_sub_lokasi" id="nama_sub_lokasi" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="area">Area</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_area" id="nama_area" disabled>
													</div>
												</div>
											</div>
										</div>
										<!--main1-->
										<div class="tab-pane active" id="tab-main1">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="author">Author</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_karyawan" id="nama_karyawan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal">Tanggal</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_buat" id="tanggal_buat" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nama_jenis_tindakan">Jenis Tindakan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_jenis_tindakan" id="nama_jenis_tindakan" disabled>
													</div>
												</div>
											</div>
										
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="jam_jalan">Jam Jalan Mesin</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="jam_jalan" id="jam_jalan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="operator">Operator</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="operator" id="operator" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="catatan">Catatan</label>
													</div>
													<div class="col-xs-8">
														<textarea rows="4" id="catatan" name="catatan" class="form-control" disabled></textarea>														
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal_rusak">Tgl Kerusakan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_rusak" id="tanggal_rusak" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal_mulai">Tgl Mulai</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_mulai" id="tanggal_mulai" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal_selesai">Tgl Selesai</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_selesai" id="tanggal_selesai" disabled>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="modal-footer">
									<input id="id_aset" name="id_aset" type="hidden">
									<input id="id_main" name="id_main" type="hidden">
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal detail perbaikan-->			
			
			<!--modal detail perubahan-->
			<div class="modal fade" id="modal_detail_perubahan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Detail Maintenance Asset FO</h4>
								</div>
								<ul class="nav nav-tabs">
									<li><a href="#tab-data11" data-toggle="tab">Data Asset</a></li>
									<li><a href="#tab-detail11" data-toggle="tab">Detail Asset</a></li>
									<li><a href="#tab-lokasi11" data-toggle="tab">Lokasi</a></li>
									<li class="active"><a href="#tab-main11" data-toggle="tab">Jam Jalan</a></li>
								</ul>
								<div class="modal-body">
									<div class="tab-content">
										<!--data-->
										<div class="tab-pane" id="tab-data11">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_sap">Nomor SAP</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="kategori">Kategori</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_kategori" id="nama_kategori" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="jenis">Sub Kategori</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_jenis" id="nama_jenis" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="id_merk">Merk</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_merk" id="nama_merk" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="id_merk_tipe">Type</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_merk_tipe" id="nama_merk_tipe" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="status">Status Barang</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_status" id="nama_status" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="kondisi">Kondisi</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_kondisi" id="nama_kondisi" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tahun_pembuatan">Tahun Pembuatan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tahun_pembuatan" id="tahun_pembuatan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal_perolehan">Tgl Perolehan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_perolehan" id="tanggal_perolehan" disabled>
													</div>
												</div>
											</div>
										</div>
										<!--detail-->
										<div class="tab-pane" id="tab-detail11">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_polisi">Satuan</label>
													</div>
													<div class="col-xs-3">
														<input type="text" class="form-control" name="spesifikasi" id="spesifikasi" disabled>
													</div>
													<div class="col-xs-3">
														<input type="text" class="form-control" name="nama_satuan" id="nama_satuan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_rangka">Nomor Rangka</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka"  disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_mesin">Nomor Mesin</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin"  disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="aksesoris1">Rotary Lamp</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_aksesoris1" id="nama_aksesoris1" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="aksesoris2">Buzzer</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_aksesoris2" id="nama_aksesoris2" disabled>
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
										<div class="tab-pane" id="tab-lokasi11">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="pabrik">Pabrik</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_pabrik" id="nama_pabrik" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="lokasi">Lokasi</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" disabled>
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
														<input type="text" class="form-control" name="nama_sub_lokasi" id="nama_sub_lokasi" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="area">Area</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_area" id="nama_area" disabled>
													</div>
												</div>
											</div>
										</div>
										<!--main1-->
										<div class="tab-pane active" id="tab-main11">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="author">Author</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_karyawan" id="nama_karyawan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal">Tanggal</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_buat" id="tanggal_buat" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nama_jenis_tindakan">Jenis Tindakan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_jenis_tindakan" id="nama_jenis_tindakan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="jam_jalan">Jam Jalan Mesin</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="jam_jalan" id="jam_jalan" disabled>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="modal-footer">
									<input id="id_aset" name="id_aset" type="hidden">
									<input id="id_main" name="id_main" type="hidden">
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal detail perubahan-->			
			<!--modal detail perawatan-->
			<div class="modal fade" id="modal_detail_perawatan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Detail Maintenance Asset FO</h4>
								</div>
								<ul class="nav nav-tabs">
									<li><a href="#tab-data12" data-toggle="tab">Data Asset</a></li>
									<li><a href="#tab-detail12" data-toggle="tab">Detail Asset</a></li>
									<li><a href="#tab-lokasi12" data-toggle="tab">Lokasi</a></li>
									<li class="active"><a href="#tab-main12" data-toggle="tab">Detail Maintenance</a></li>
									<li><a href="#tab-rawat12" data-toggle="tab">Detail Perawatan</a></li>
								</ul>
								<div class="modal-body">
									<div class="tab-content">
										<!--data-->
										<div class="tab-pane" id="tab-data12">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_sap">Nomor SAP</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="kategori">Kategori</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_kategori" id="nama_kategori" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="jenis">Sub Kategori</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_jenis" id="nama_jenis" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="id_merk">Merk</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_merk" id="nama_merk" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="id_merk_tipe">Type</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_merk_tipe" id="nama_merk_tipe" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="status">Status Barang</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_status" id="nama_status" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="kondisi">Kondisi</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_kondisi" id="nama_kondisi" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tahun_pembuatan">Tahun Pembuatan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tahun_pembuatan" id="tahun_pembuatan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal_perolehan">Tgl Perolehan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_perolehan" id="tanggal_perolehan" disabled>
													</div>
												</div>
											</div>
										</div>
										<!--detail-->
										<div class="tab-pane" id="tab-detail12">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_polisi">Satuan</label>
													</div>
													<div class="col-xs-3">
														<input type="text" class="form-control" name="spesifikasi" id="spesifikasi" disabled>
													</div>
													<div class="col-xs-3">
														<input type="text" class="form-control" name="nama_satuan" id="nama_satuan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_rangka">Nomor Rangka</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka"  disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nomor_mesin">Nomor Mesin</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin"  disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="aksesoris1">Rotary Lamp</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_aksesoris1" id="nama_aksesoris1" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="aksesoris2">Buzzer</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_aksesoris2" id="nama_aksesoris2" disabled>
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
										<div class="tab-pane" id="tab-lokasi12">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="pabrik">Pabrik</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_pabrik" id="nama_pabrik" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="lokasi">Lokasi</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" disabled>
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
														<input type="text" class="form-control" name="nama_sub_lokasi" id="nama_sub_lokasi" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="area">Area</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_area" id="nama_area" disabled>
													</div>
												</div>
											</div>
										</div>
										<!--main1-->
										<div class="tab-pane active" id="tab-main12">
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="author">Author</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_karyawan" id="nama_karyawan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal">Tanggal</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_buat" id="tanggal_buat" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="nama_jenis_tindakan">Jenis Tindakan</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="nama_jenis_tindakan" id="nama_jenis_tindakan" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal_mulai">Tgl Mulai</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_mulai" id="tanggal_mulai" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="tanggal_selesai">Tgl Selesai</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="tanggal_selesai" id="tanggal_selesai" disabled>
													</div>
												</div>
											</div>
											<div class="form-group">		
												<div class="row">
													<div class="col-xs-3">
														<label for="jam_jalan">Jam Jalan Mesin</label>
													</div>
													<div class="col-xs-8">
														<input type="text" class="form-control" name="jam_jalan" id="jam_jalan" disabled>
													</div>
												</div>
											</div>
										</div>
										<!--rawat-->
										<div class="tab-pane" id="tab-rawat12">
											<table class="table table-bordered table-striped my-datatable-extends-order-detail">
												<thead>
													<th>Item</th>
													<th>Pekerjaan</th>
													<th>Cek?</th>
													<th>Keterangan</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
										
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="modal-footer">
									<input id="id_aset" name="id_aset" type="hidden">
									<input id="id_main" name="id_main" type="hidden">
									<input id="jam_jalan" name="jam_jalan" type="hidden">
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal detail perawatan-->			
			
			<!--modal perbaikan-->
			<div class="modal fade" id="perbaikan_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-fo-proses_perbaikan" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Proses Maintenance Asset FO</h4>
									</div>
									<ul class="nav nav-tabs">
										<li><a href="#tab-data2" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail2" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi2" data-toggle="tab">Lokasi</a></li>
										<li class="active"><a href="#tab-main2" data-toggle="tab">Detail Maintenance</a></li>
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
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_kategori" id="nama_kategori" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jenis">Sub Kategori</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_jenis" id="nama_jenis" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk">Merk</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_merk" id="nama_merk" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk_tipe">Type</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_merk_tipe" id="nama_merk_tipe" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="status">Status Barang</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_status" id="nama_status" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kondisi">Kondisi</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_kondisi" id="nama_kondisi" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tahun_pembuatan">Tahun Pembuatan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="tahun_pembuatan" id="tahun_pembuatan" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tanggal_perolehan">Tgl Perolehan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="tanggal_perolehan" id="tanggal_perolehan" disabled>
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
															<input type="text" class="form-control" name="spesifikasi" id="spesifikasi" disabled>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="nama_satuan" id="nama_satuan" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_rangka">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_aksesoris1" id="nama_aksesoris1" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris2">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_aksesoris2" id="nama_aksesoris2" disabled>
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
															<input type="text" class="form-control" name="nama_pabrik" id="nama_pabrik" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="lokasi">Lokasi</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" disabled>
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
															<input type="text" class="form-control" name="nama_sub_lokasi" id="nama_sub_lokasi" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="area">Area</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_area" id="nama_area" disabled>
														</div>
													</div>
												</div>
											</div>
											<!--main1-->
											<div class="tab-pane active" id="tab-main2">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jam_jalan">Jam Jalan Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="jam_jalan" id="jam_jalan" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="operator">Operator</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="operator" id="operator" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="catatan">Catatan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="catatan" name="catatan" class="form-control" disabled></textarea>														
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tanggal_rusak">Tgl Kerusakan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_rusak" id="tanggal_rusak" placeholder="Tanggal Kerusakan"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tanggal_mulai">Tgl Mulai</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_mulai" id="tanggal_mulai" placeholder="Tanggal Mulai"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tanggal_selesai">Tgl Selesai</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_selesai" id="tanggal_selesai" placeholder="Tanggal Selesai"  required="required">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset" name="id_aset" type="hidden">
										<input id="id_main" name="id_main" type="hidden">
										<input id="jam_jalan" name="jam_jalan" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_proses_perbaikan">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal perbaikan-->			
			<!--modal perawatan-->
			<div class="modal fade" id="perawatan_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-fo-proses_perawatan" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Proses Maintenance Asset FO</h4>
									</div>
									<ul class="nav nav-tabs">
										<li><a href="#tab-data22" data-toggle="tab">Data Asset</a></li>
										<li><a href="#tab-detail22" data-toggle="tab">Detail Asset</a></li>
										<li><a href="#tab-lokasi22" data-toggle="tab">Lokasi</a></li>
										<li class="active"><a href="#tab-main22" data-toggle="tab">Detail Maintenance</a></li>
										<li><a href="#tab-rawat22" data-toggle="tab">Detail Perawatan</a></li>
									</ul>
									<div class="modal-body">
										<div class="tab-content">
											<!--data-->
											<div class="tab-pane" id="tab-data22">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_sap">Nomor SAP</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kategori">Kategori</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_kategori" id="nama_kategori" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jenis">Sub Kategori</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_jenis" id="nama_jenis" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk">Merk</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_merk" id="nama_merk" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="id_merk_tipe">Type</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_merk_tipe" id="nama_merk_tipe" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="status">Status Barang</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_status" id="nama_status" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="kondisi">Kondisi</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_kondisi" id="nama_kondisi" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tahun_pembuatan">Tahun Pembuatan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="tahun_pembuatan" id="tahun_pembuatan" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tanggal_perolehan">Tgl Perolehan</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="tanggal_perolehan" id="tanggal_perolehan" disabled>
														</div>
													</div>
												</div>
											</div>
											<!--detail-->
											<div class="tab-pane" id="tab-detail22">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_polisi">Satuan</label>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="spesifikasi" id="spesifikasi" disabled>
														</div>
														<div class="col-xs-3">
															<input type="text" class="form-control" name="nama_satuan" id="nama_satuan" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_rangka">Nomor Rangka</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_rangka" id="nomor_rangka" placeholder="Nomor Rangka"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="nomor_mesin">Nomor Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nomor_mesin" id="nomor_mesin" placeholder="Nomor Mesin"  disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris1">Rotary Lamp</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_aksesoris1" id="nama_aksesoris1" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="aksesoris2">Buzzer</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_aksesoris2" id="nama_aksesoris2" disabled>
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
											<div class="tab-pane" id="tab-lokasi22">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="pabrik">Pabrik</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_pabrik" id="nama_pabrik" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="lokasi">Lokasi</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" disabled>
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
															<input type="text" class="form-control" name="nama_sub_lokasi" id="nama_sub_lokasi" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="area">Area</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="nama_area" id="nama_area" disabled>
														</div>
													</div>
												</div>
											</div>
											<!--main1-->
											<div class="tab-pane active" id="tab-main22">
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="jam_jalan">Jam Jalan Mesin</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="jam_jalan" id="jam_jalan" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="operator">Operator</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control" name="operator" id="operator" disabled>
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="catatan">Catatan</label>
														</div>
														<div class="col-xs-8">
															<textarea rows="4" id="catatan" name="catatan" class="form-control" disabled></textarea>														
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tanggal_mulai">Tgl Mulai</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_mulai" id="tanggal_mulai" placeholder="Tanggal Mulai"  required="required">
														</div>
													</div>
												</div>
												<div class="form-group">		
													<div class="row">
														<div class="col-xs-3">
															<label for="tanggal_selesai">Tgl Selesai</label>
														</div>
														<div class="col-xs-8">
															<input type="text" class="form-control tanggal" name="tanggal_selesai" id="tanggal_selesai" placeholder="Tanggal Selesai"  required="required">
														</div>
													</div>
												</div>
											</div>
											<!--rawat-->
											<div class="tab-pane" id="tab-rawat22">
												<table class="table table-bordered table-striped my-datatable-extends-order-detail">
													<thead>
														<th>Item</th>
														<th>Pekerjaan</th>
														<th>Cek?</th>
														<th>Keterangan</th>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="modal-footer">
										<input id="id_aset" name="id_aset" type="hidden">
										<input id="id_main" name="id_main" type="hidden">
										<input id="jam_jalan" name="jam_jalan" type="hidden">
										<button type="button" class="btn btn-primary" name="action_btn_proses_perawatan">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<!--sampe sini modal perawatan-->
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/detail_fo.js"></script>
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