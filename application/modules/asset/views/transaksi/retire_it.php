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
						<button type="button" class="btn btn-sm btn-default pull-right" id="add_button">Retire Asset</button> 
						
	          		</div>
	          		<!-- /.box-header -->
		          	
		          	<div class="box-body">
						<?php 
							// $session_id_user	= base64_decode($this->session->userdata('-id_user-'))
							echo "<input type='hidden' id='session_id_user' value='".base64_decode($this->session->userdata('-id_user-'))."'>";
							echo "<input type='hidden' id='session_id_divisi' value='".base64_decode($this->session->userdata('-id_divisi-'))."'>";
							echo "<input type='hidden' id='session_id_level' value='".base64_decode($this->session->userdata('-id_level-'))."'>";
						?>
						
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
					  	<div class="table-responsive scrolls">
							<table class="table table-bordered table-striped my-datatable-extends-order">
								<thead>
									<th>Tanggal Pengajuan</th>
									<th>Nama Asset</th>
									<th>Nomor SAP Asset</th>
									<th>Jenis</th>
									<th>Pabrik</th>
									<th>Lokasi</th>
									<th>Area</th>
									<th>Tanggal Retirement</th>
									<th>Alasan</th>
									<th>Opsi Penghapusan Asset</th>
									<th>No Doc KIASS</th>
									<th>Doc Berita Acara</th>
									<th>Status</th>
									<th>Action</th>
								</thead>
								<tbody>
									<?php
									foreach($aset_temp as $dt){
										echo "<tr>";
										echo "<td>".date('d.m.Y',strtotime($dt->tanggal_buat))."</td>";
										echo "<td>".$dt->KODE_BARANG."</td>";
										echo "<td>".$dt->nomor_sap."</td>";
										echo "<td>".$dt->nama_jenis."</td>";
										echo "<td>".$dt->nama_pabrik."</td>";
										echo "<td>".$dt->nama_lokasi."</td>";
										echo "<td>".$dt->nama_area."</td>";
										echo "<td>".date('d.m.Y',strtotime($dt->tanggal_retire))."</td>";
										echo "<td>".$dt->alasan."</td>";
										echo "<td>".$dt->opt_opsi."</td>";
										echo "<td>".$dt->no_doc."</td>";
										echo "<td>".$dt->file_ba."</td>";
										echo "<td>".$dt->label_flag."</td>";
										echo "<td>
											<div class='input-group-btn'>
												<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
												<ul class='dropdown-menu pull-right'>";
													if((base64_decode($this->session->userdata("-id_divisi-")) == 754)and(base64_decode($this->session->userdata("-id_level-")) == 9102)){
														if($dt->flag=='menunggu'){
															echo "<li><a href='javascript:void(0)' class='set_proses' data-id_aset='".$dt->id_aset."'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
														}
													}
													if((base64_decode($this->session->userdata("-id_user-")) == $dt->login_buat)and($dt->flag=='menunggu')){
														echo "<li><a href='javascript:void(0)' class='detail' data-id_aset='".$dt->id_aset."' data-act='batal'><i class='fa fa-search'></i> Detail</a></li>";
													}else{
														echo "<li><a href='javascript:void(0)' class='detail' data-id_aset='".$dt->id_aset."'><i class='fa fa-search'></i> Detail</a></li>";
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
			</div>
		</div>
		<!--modal set proses-->
		<div class="modal fade" id="set_proses_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="col-sm-12">
						<div class="nav-tabs-custom" id="tabs-edit">
							<form role="form" class="form-transaksi-proses" enctype="multipart/form-data">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Persetujuan Retire Asset</h4>
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
												<label for="label_nama_pabrik_move">Pabrik</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_pabrik_move" class="form-control-static"></span>
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
												<label for="label_nama_alasan">Alasan</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_alasan_move" class="form-control-static"></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-xs-4">
												<label for="label_nama_opsi">Opsi Penghapusan Asset</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_opsi_move" class="form-control-static">-</span>
											</div>
										</div>
									</div>
									<div class="form-group mv-ba hide">
										<div class="row">
											<div class="col-xs-4">
												<label for="label_nama_ba">Doc. Berita Acara Penghapusan Asset</label>
											</div>
											<div class="col-xs-8">
												<div class="input-group" style="width: 100%;">
													<input type="text" class="form-control caption_file" name="caption_lampiran_move" readonly="readonly">
													<div class="input-group-btn">
														<button type="button" class="btn btn-default btn-flat view_file" data-link="" data-title="Lihat file"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group mv-kiass hide">
										<div class="row">
											<div class="col-xs-4">
												<label for="label_nama_no_doc">No. Doc. Pengajuan KIASS</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_no_doc_move" class="form-control-static">-</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-xs-4">
												<label for="catatan_service">Catatan</label>
											</div>
											<div class="col-xs-8">
												<div class="input-group">
													<textarea rows="3" cols="100%" class="form-control" name="catatan" id="catatan" placeholder="Masukan Catatan"></textarea>													
												</div>
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
									<button type="button" class="btn btn-primary" name="action_btn_proses">Submit</button>
								</div>
							</form>
						</div>
					</div>
					
				</div>
			</div>	
		</div>
		<!--modal set detail-->
		<div class="modal fade" id="set_detail_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="col-sm-12">
						<div class="nav-tabs-custom" id="tabs-edit">
							<form role="form" class="form-transaksi-proses-detail" enctype="multipart/form-data">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Detail Retire Asset</h4>
								</div>
								<div class="modal-body">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-4">
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
											<div class="col-xs-4">
												<label for="label_nama_pabrik">Pabrik</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_pabrik" class="form-control-static"></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-xs-4">
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
											<div class="col-xs-4">
												<label for="label_nama_alasan">Alasan</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_alasan" class="form-control-static">-</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-xs-4">
												<label for="label_nama_opsi">Opsi Penghapusan Asset</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_opsi" class="form-control-static">-</span>
											</div>
										</div>
									</div>
									<div class="form-group det-ba hide">
										<div class="row">
											<div class="col-xs-4">
												<label for="label_nama_ba">Doc. Berita Acara Penghapusan Asset</label>
											</div>
											<div class="col-xs-8">
												<div class="input-group" style="width: 100%;">
													<input type="text" class="form-control caption_file" name="caption_lampiran_detail" readonly="readonly">
													<div class="input-group-btn">
														<button type="button" class="btn btn-default btn-flat view_file" data-link="" data-title="Lihat file"><i class="fa fa-search"></i></button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group det-kiass hide">
										<div class="row">
											<div class="col-xs-4">
												<label for="label_nama_no_doc">No. Doc. Pengajuan KIASS</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_no_doc" class="form-control-static">-</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-xs-4">
												<label for="label_nama_label_flag">Status</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_label_flag" class="form-control-static">-</span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-xs-4">
												<label for="label_nama_catatan">Catatan</label>
											</div>
											<div class="col-xs-8">
												<span id="label_nama_catatan" class="form-control-static">-</span>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="modal-footer">
									<input id="id_aset_post" name="id_aset_post" type="hidden">
									<input id="flag" name="flag" type="hidden">
									<input id="proses" name="proses" type="hidden">
									<button type="button" class="btn btn-danger" name="action_btn_batal" id="action_btn_batal">Batalkan Pengajuan</button>
								</div>
								
							</form>
						</div>
					</div>
					
				</div>
			</div>	
		</div>
		
		<!-- Modal Transaksi Retire -->
		<div class="modal fade" id="add_modal_perbaikan" data-backdrop="static" role="dialog"
			 aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="col-sm-12">
						<div class="nav-tabs-custom">
							<form role="form" class="form-transaksi-retire" 
								  enctype="multipart/form-data">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
												aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Retire Asset</h4>
								</div>
								<div class="modal-body">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-4">
												<label for="kategori">Aset</label>
											</div>
											<div class="col-xs-8">
												<select class="form-control" name="id_aset"
														id="id_aset_ajax" required="required"
														data-placeholder="Cari aset">
													<option></option>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-xs-4">
												<label for="tanggal_retire">Tanggal Retire</label>
											</div>
											<div class="col-xs-8">
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input class="form-control tanggal" required readonly name="tanggal_retire" id="tanggal_retire">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">		
										<div class="row">
											<div class="col-xs-4">
												<label for="label_alasan">Alasan</label>
											</div>
											<div class="col-xs-8">
												<select class="form-control select2modal" name="opt_alasan" id="opt_alasan"  required="required">
													<?php
														echo "<option value='0'>Silahkan Pilih Alasan</option>";
														echo "<option value='Accident'>Accident</option>";
														echo "<option value='Hilang'>Hilang</option>";
														echo "<option value='Mati Total'>Mati Total</option>";
														echo "<option value='Lain-Lain'>Lain-Lain</option>";
													?>
												</select>
												<div id="show_alasan"></div>
											</div>
										</div>
									</div>
									<div class="form-group">		
										<div class="row">
											<div class="col-xs-4">
												<label for="label_opsi">Opsi Penghapusan Asset</label>
											</div>
											<div class="col-xs-8">
												<select class="form-control select2modal" name="opt_opsi" id="opt_opsi" required="required">
													<option value='0'>Silahkan Pilih Opsi</option>
													<option value='Penghapusan Asset'>Penghapusan Asset</option>
													<option value='Pengajuan KIASS'>Pengajuan KIASS</option>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group p-asset hide">		
										<div class="row">
											<div class="col-xs-4">
												<label for="label_ba">Doc. Berita Acara Penghapusan Asset</label>
											</div>
											<div class="col-xs-8">
												<div class="input-group" style="width: 100%;">
													<input type="text" class="form-control caption_file inp-asset" name="caption_lampiran" readonly="readonly">
													<div class="input-group-btn">
														<input type="file" name="lampiran[]" class="form-control upload_file berkas" style="display:none;">
														<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload"><i class="fa fa-upload"></i></button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-4">
											</div>
											<div class="col-xs-8">
												<div class="alert alert-info">
													<small>
														<ol style="padding-inline-start: 10px;">
															<li>File lampiran hanya diperbolehkan yang ber ekstensi JPG, JPEG,
																PNG, XLS, DOC atau PDF.
															</li>
															<li>Ukuran file lampiran hanya diperbolehkan maksimal 5MB.</li>
														</ol>
													</small>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group p-kiass hide">		
										<div class="row">
											<div class="col-xs-4">
												<label for="label_nomor">No. Doc. Pengajuan KIASS</label>
											</div>
											<div class="col-xs-8">
												<input type="text"
													   class="form-control inp-kiass"
													   name="no_doc"
													   placeholder="cth: PP/1/KMTR/01/2022"
													   >
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="modal-footer">
									<input type="hidden" name="kode" />
									<input type="hidden" name="id_jenis" />
									<button type="button" class="btn btn-primary" name="action_btn_retire">Submit</button>
								</div>
							</form>
						</div>
					</div>

				</div>
			</div>
		</div>		
		
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/retire_it.js"></script>
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