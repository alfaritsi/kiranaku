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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<div class="btn-group pull-right">
							<?php 
								if(($user_role[0]->level==1)or($user_role[0]->level==4)){
									echo'<button type="button" class="btn btn-warning" id="cek_vendor">Cek data SAP</button>';
									echo'<button type="button" class="btn btn-success" id="add_button">Create Vendor</button>';	
								}
							?>
						</div>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-4">
			            		<div class="form-group">
				                	<label> Jenis Vendor: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_jenis_vendor_filter" name="id_jenis_vendor[]" style="width: 100%;" data-placeholder="Pilih Tipe Vendor">
				                  		<?php
					                		foreach($jenis as $dt){
					                			echo "<option value='".$dt->id_jenis_vendor."'>".$dt->jenis_vendor."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-4">
			            		<div class="form-group">
				                	<label> Kualifikasi Vendor: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_kualifikasi_spk_filter" name="id_kualifikasi_spk_filter[]" style="width: 100%;" data-placeholder="Pilih Kategori Vendor">
				                  		<?php
					                		foreach($kualifikasi as $dt){
					                			echo "<option value='".$dt->id_kualifikasi_spk."'>".$dt->kualifikasi_spk."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
						</div>	
						<div class="row">	
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status Pengajuan: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_filter_pengajuan" name="status_filter_pengajuan[]" style="width: 100%;" data-placeholder="Pilih Status Pengajuan">
				                  		<?php
											echo "<option value='y' selected>On Progress</option>";
											echo "<option value='n' selected>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status Pending: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_role_filter" name="id_role_filter[]" style="width: 100%;" data-placeholder="Pilih Role">
				                  		<?php
					                		foreach($role as $dt){
												$ck = ($user_role[0]->id_role==$dt->id_role)?"selected":"";
					                			echo "<option value='".$dt->id_role."' $ck>".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Jenis Proses: </label>
				                	<select class="form-control select2" multiple="multiple" id="filter_jenis_pengajuan" name="filter_jenis_pengajuan[]" style="width: 100%;" data-placeholder="Pilih Jenis Proses">
				                  		<?php
											echo "<option value='extend'>Extend</option>";
											echo "<option value='change'>Change</option>";
											echo "<option value='delete'>Delete</option>";
											echo "<option value='undelete'>Undelete</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
							
							<!--
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status Extend: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_filter_extend" name="status_filter_extend[]" style="width: 100%;" data-placeholder="Pilih Status Pengajuan">
				                  		<?php
											echo "<option value='y' selected>On Progress</option>";
											echo "<option value='n'>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status Change: </label>
				                	<select class="form-control select2 " multiple="multiple" id="status_filter_change" name="status_filter_change[]" style="width: 100%;" data-placeholder="Pilih Status Pengajuan">
				                  		<?php
											echo "<option value='y' selected>On Progress</option>";
											echo "<option value='n'>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status Deleted: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_filter_delete" name="status_filter_delete[]" style="width: 100%;" data-placeholder="Pilih Status Pengajuan">
				                  		<?php
											echo "<option value='y' selected>On Progress</option>";
											echo "<option value='n'>Completed</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Status Undeleted: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_filter_undelete" name="status_filter_undelete[]" style="width: 100%;" data-placeholder="Pilih Status Pengajuan">
				                  		<?php
											echo "<option value='y' selected>On Progress</option>";
											echo "<option value='n'>Completed</option>";
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
									<th>Kode Vendor</th>
									<th>Nama Vendor</th>
									<th>Jenis Vendor</th>
									<th>Kualifikasi Vendor</th>
									<th>Total Nilai</th>
									<th>Plant</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
		</div>
	</section>
</div>

<!--modal add/edit-->
<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="nav-tabs-custom" id="tabs-edit">
					<form role="form" class="form-transaksi-vendor" enctype="multipart/form-data">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Create Vendor</h4>
						</div>
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab-general" data-toggle="tab">Data Vendor</a></li>
							<li><a href="#tab-detail" data-toggle="tab">Detail Vendor</a></li>
							<li><a href="#tab-alamat" data-toggle="tab">Alamat</a></li>
							<li><a href="#tab-nilai" data-toggle="tab">Penilaian</a></li>
							<li><a href="#tab-dokumen" data-toggle="tab">Dokumen</a></li>
							<li><a href="#tab-additional" data-toggle="tab">Additional</a></li>
							<li class="form-control_komentar"><a href="#tab-komentar" data-toggle="tab">Komentar</a></li>
						</ul>
						<div class="modal-body">
							<div class="tab-content">
								<!--general-->
								<div class="tab-pane active" id="tab-general">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="plant">Plant</label>
												<select class="form-control select2modal form-control-hide" name="plant" id="plant" required="required">
													<?php
														echo "<option value='0'>Pilih Plant</option>";
														foreach($plant as $dt){
															echo"<option value='".$dt->plant."'>".$dt->plant."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="acc_group">ACC Group</label>
												<select class="form-control select2modal form-control-hide" name="acc_group" id="acc_group" required="required">
													<?php
														echo "<option value='0'>Pilih ACC Group</option>";
														foreach($acc_group as $dt){
															echo"<option value='".$dt->KTOKK."'>".$dt->KTOKK."</option>";
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
												<select class="form-control select2modal form-control-hide" name="title" id="title">
													<?php
														echo "<option value='0'>Pilih Title</option>";
														foreach($title_medi as $dt){
															echo"<option value='".$dt->title."'>".$dt->title."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="nama">Nama Vendor</label>
												<input style="text-transform: uppercase" type="text" class="form-control form-control-hide form-control-perubahan_data" name="nama" id="nama" placeholder="Nama Vendor"  required="required">
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<!--
												<label for="jenis_barang_jasa1">Search Term 1</label>
												<input type="text" class="form-control form-control-hide" name="jenis_barang_jasa1" id="jenis_barang_jasa1" placeholder="Search Term 1"  required="required">
												-->
												<label for="jenis_barang_jasa1">Search Term 1</label>
												<select class="form-control select2modal form-control-hide" name="jenis_barang_jasa1" id="jenis_barang_jasa1" required="required">
													<?php
														echo "<option value='0'>Pilih Search Term 1</option>";
														foreach($term1 as $dt){
															echo"<option value='".$dt->nama."'>".$dt->nama."</option>";
														}
													?>
												</select>
												
											</div>
											<div class="col-xs-6">
												<!--
												<label for="jenis_barang_jasa2">Search Term 2</label>
												<input type="text" class="form-control form-control-hide" name="jenis_barang_jasa2" id="jenis_barang_jasa2" placeholder="Search Term 2"  required="required">
												-->
												<label for="jenis_barang_jasa2">Search Term 2</label>
												<select class="form-control select2modal form-control-hide" name="jenis_barang_jasa2" id="jenis_barang_jasa2" required="required">
													<?php
														echo "<option value='0'>Search Term 2</option>";
														foreach($term2 as $dt){
															echo"<option value='".$dt->nama."'>".$dt->nama."</option>";
														}
													?>
												</select>
												
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="nama_bank">Nama Bank</label>
												<input type="text" class="form-control form-control-hide" name="nama_bank" id="nama_bank" placeholder="Nama Bank"  required="required">
											</div>
											<div class="col-xs-6">
												<label for="nama_rekening">Nama Pemilik Rekening</label>
												<input type="text" class="form-control form-control-hide" name="nama_rekening" id="nama_rekening" placeholder="Nama Pemilik Rekening"  required="required">
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="nomor_rekening">Nomor Rekening Bank</label>
												<input type="number" class="form-control form-control-hide" name="nomor_rekening" id="nomor_rekening" placeholder="Nomor Rekening Bank"  required="required">
											</div>
											<div class="col-xs-6">
												<label for="ktp">KTP</label>
												<input type="number" onKeyPress="if(this.value.length==16) return false;" class="form-control form-control-hide form-control-perubahan_data" name="ktp" id="ktp" placeholder="KTP">
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="npwp">NPWP</label>
												<input type="number" onKeyPress="if(this.value.length==15) return false;" class="form-control form-control-hide form-control-perubahan_data" name="npwp" id="npwp" placeholder="NPWP">
											</div>
											<!--
											<div class="col-xs-6">
												<label for="payment">Payment</label>
												<input type="text" class="form-control form-control-hide" name="payment" id="payment" placeholder="Payment"  required="required">
											</div>
											-->
										</div>
									</div>
								</div>
								<!--detail-->
								<div class="tab-pane" id="tab-detail">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="industri">Industri</label>
												<select class="form-control select2modal form-control-hide" name="industri" id="industri">
													<?php
														echo "<option value='0'>Pilih Industri</option>";
														foreach($industri as $dt){
															echo"<option value='".$dt->id_industri."'>".$dt->id_industri." - ".$dt->nama_industri."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="service_agent">Service Agent</label>
												<select class="form-control select2modal form-control-hide" name="dlgrp" id="dlgrp">
													<?php
														echo "<option value='0'>Pilih Service Agent</option>";
														echo"<option value='0001'>0001 - Street</option>";
														echo"<option value='0002'>0002 - Post and Package Services</option>";
														echo"<option value='0003'>0003 - Rail</option>";
														echo"<option value='0004'>0004 - Sea</option>";
														echo"<option value='0005'>0005 - Load tranfer point</option>";
														echo"<option value='0006'>0006 - Customs</option>";
														echo"<option value='Z001'>Z001 - Trip</option>";
													?>
												</select>
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="akont">Recon Account</label>
												<select class="form-control select2modal form-control-hide" name="akont" id="akont"  required="required">
													<?php
														echo "<option value='0'>Pilih Recon Account</option>";
														// echo"<option value='1112001'>1112001 - Uang Muka Pembelian Non Bokar-Pihak Ketiga</option>";
														// echo"<option value='1112002'>1112002 - Uang Muka Pembelian Non Bokar-Hubungan Istimewa</option>";
														// echo"<option value='1112003'>1112003 - Uang Muka Pembelian Bokar-Pihak Ketiga</option>";
														// echo"<option value='1112004'>1112004 - Uang Muka Pembelian Bokar-Hubungan Istimewa</option>";
														// echo"<option value='1112005'>1112005 - Uang Muka Lain-Lain</option>";
														// echo"<option value='1222001'>1222001 - Uang Muka Investasi Jangka Panjang</option>";
														// echo"<option value='2101001'>2101001 - Hutang Usaha-Bokar-Pihak Ketiga</option>";
														// echo"<option value='2101002'>2101002 - Hutang Usaha-Bokar-Hubungan Istimewa</option>";
														// echo"<option value='2101003'>2101003 - Hutang Usaha-Pihak Ketiga</option>";
														// echo"<option value='2101004'>2101004 - Hutang Usaha-Hubungan Istimewa</option>";
														echo"<option value='2102001' selected='selected'>2102001 - Hutang Lain-Lain-Pihak Ketiga</option>";
														// echo"<option value='2102002'>2102002 - Hutang Lain-Lain-Hubungan Istimewa</option>";
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="zterm">Payment Term</label>
												<select class="form-control select2modal form-control-hide" name="zterm" id="zterm">
													<?php
														echo "<option value='0'>Pilih Payment Term</option>";
														foreach($payment_term as $dt){
															echo"<option value='".$dt->payment_term."'>".$dt->payment_term." - ".$dt->payment_term_detail."</option>";
														}
													?>
												</select>
											</div>
										</div>
									</div>	
									<!--
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="reprf">Check double inv</label>
												<input type="text" class="form-control form-control-hide" name="reprf" id="reprf" placeholder="X" value="X" maxlength="1" readonly>
											</div>
											<div class="col-xs-6">
												<label for="qland">WH Tax Country</label>
												<input type="text" class="form-control form-control-hide" name="qland" id="qland" placeholder="ID" value="ID" maxlength="3" readonly>
											</div>
										</div>
									</div>	
									-->
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="tax_type">WH Tax Type</label>
												<select class="form-control select2modal form-control-hide form-control-perubahan_data" name="tax_type" id="tax_type">
													<?php
														echo "<option value='0'>Pilih Tax Type</option>";
														foreach($tax_type as $dt){
															echo"<option value='".$dt->tax_type."'>".$dt->tax_type." - ".$dt->tax_type_name."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="tax_code">WH Tax Code</label>
												<select class="form-control select2modal form-control-hide form-control-perubahan_data" name="tax_code" id="tax_code">
													<?php
														echo "<option value='0'>Pilih Tax Code</option>";
														foreach($tax_code as $dt){
															echo"<option value='".$dt->tax_code."'>".$dt->tax_code." - ".$dt->tax_code_name."</option>";
														}
													?>
												</select>
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<!--
											<div class="col-xs-6">
												<label for="tax_code2">WH Tax Code2</label>
												<select class="form-control select2modal form-control-hide" name="tax_code2" id="tax_code2">
													<?php
														echo "<option value='0'>Pilih Tax Code</option>";
														foreach($tax_code as $dt){
															echo"<option value='".$dt->tax_code."'>".$dt->tax_code." - ".$dt->tax_code_name."</option>";
														}
													?>
												</select>
											</div>
											-->
											<div class="col-xs-6">
												<label for="curr">Order Currency</label>
												<select class="form-control select2modal form-control-hide" name="curr" id="curr" required="required">
													<?php
														echo "<option value='0'>Pilih Currency</option>";
														foreach($cur as $dt){
															echo"<option value='".$dt->cur."'>".$dt->cur."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="sales_person">Sales Person</label>
												<input type="text" class="form-control form-control-hide" name="sales_person" id="sales_person" placeholder="Sales Person">
											</div>
											<!--
											//schema_grup hardcode NB pada saat save
											<div class="col-xs-6">
												<label for="schema_grup">Schema Group</label>
												<input type="text" class="form-control form-control-hide" name="schema_grup" id="schema_grup" placeholder="NB" readonly>
											</div>
											-->
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="sales_phone">Sales Person Telephone</label>
												<input type="text" class="form-control form-control-hide" name="sales_phone" id="sales_phone" placeholder="Sales Person Telephone">
											</div>
											<div class="col-xs-6">
												<label for="status_do">Status DO</label>
												<select  class="form-control select2modal form-control-hide" name="status_do" id="status_do">
													<?php
														echo "<option value='0'>Pilih Status DO</option>";
														echo"<option value='DO'>DO</option>";
													?>
												</select>
											</div>
										</div>
									</div>	
									<!--
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="status_pkp">Status PKP</label>
												<select class="form-control select2modal form-control-hide" name="status_pkp" id="status_pkp">
													<?php
														echo "<option value='0'>Pilih Status PKP</option>";
														echo"<option value='Status PKP'>Status PKP</option>";
														echo"<option value='Non PKP'>Non PKP</option>";
													?>
												</select>
											</div>
										</div>
									</div>	
									-->
									<!--
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="webre">GR Based Inv</label>
												<input type="text" class="form-control form-control-hide" name="webre" id="webre" placeholder="X" readonly>
											</div>
											<div class="col-xs-6">
												<label for="deletion_flag">Deletion Flag</label>
												<input type="text" class="form-control form-control-hide" name="deletion_flag" id="deletion_flag" placeholder="X" readonly>
											</div>
										</div>
									</div>	
									-->
								</div>
								<!--alamat-->
								<div class="tab-pane" id="tab-alamat">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="negara">Negara</label>
												<select class="form-control select2modal form-control-hide form-control-perubahan_data" name="negara" id="negara" required="required">
													<?php
														echo "<option value='0'>Pilih Negara</option>";
														foreach($negara as $dt){
															echo"<option value='".$dt->id."'>".$dt->id." - ".$dt->negara."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="provinsi">Provinsi</label>
												<select class="form-control select2modal form-control-hide form-control-perubahan_data" name="provinsi" id="provinsi">
													<?php
														echo "<option value='0'>Pilih Provinsi</option>";
														foreach($provinsi as $dt){
															echo"<option value='".$dt->id_provinsi."'>".$dt->id_provinsi." - ".$dt->nama_provinsi."</option>";
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
												<input type="text" class="form-control form-control-hide form-control-perubahan_data" name="kota" id="kota" placeholder="Kota">
											</div>
											<div class="col-xs-6">
												<label for="alamat">Alamat</label>
												<input type="text" class="form-control form-control-hide form-control-perubahan_data" onKeyPress="if(this.value.length==60) return false;" name="alamat" id="alamat" placeholder="Alamat">
											</div>
										</div>
									</div>													
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="no">Nomor Rumah</label>
												<input type="text" class="form-control form-control-hide form-control-perubahan_data" name="no" id="no" placeholder="Nomor">
											</div>
											<div class="col-xs-6">
												<label for="kode_pos">Kode Pos</label>
												<input type="number" onKeyPress="if(this.value.length==5) return false;" class="form-control form-control-hide form-control-perubahan_data" name="kode_pos" id="kode_pos" placeholder="Kode Pos">
											</div>
											
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="telepon">Telepon</label>
												<input type="number" class="form-control form-control-hide" name="telepon" id="telepon" placeholder="Telepon">
											</div>
											<div class="col-xs-6">
												<label for="fax">Fax</label>
												<input type="text" class="form-control form-control-hide" name="fax" id="fax" placeholder="Fax">
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="email">Email</label>
												<input type="text" class="form-control form-control-hide" name="email" id="email" placeholder="Email">
											</div>
										</div>
									</div>	
								</div>
								<!--nilai-->
								<div class="tab-pane" id="tab-nilai">
									<table class="table table-bordered">
										<thead>
											<tr>
											<th>NO</th>
											<th>KRITERIA</th>
											<th>PENILAIAN</th>
											<th>MAKS</th>
											<th>BOBOT</th>
											<th width="11%">NILAI</th>
											<th width="11%">TOTAL<br><sup>(Bobot x Nilai)</sup></th>
											<th width="11%">TOTAL<br><sup>(Bobot x Maks)</sup></th>
											<tr>
										</thead>
										<tbody>
											<?php
											$no	= 0;
											$total_bobot = 0;
											foreach($kriteria as $dt){
												$no++;
												$total_bobot += $dt->bobot;
												$id_kriteria = $this->generate->kirana_decrypt($dt->id_kriteria);
												echo "<tr>";
												echo "<td>".$no."</td>";
												echo "<td>".$dt->nama."</td>";
												echo "<td>";
														$arr_nilai = explode('|', substr($dt->list_nilai, 0, -1));
														if(count($arr_nilai) >= 1 ){
															for ($brs = 0; $brs < count($arr_nilai); $brs++) {
																$det = explode("#", $arr_nilai[$brs]);
																$checked = "checked_".$id_kriteria."_".@$det[0];
																echo "<table class='table table-bordered table-striped'>";
																echo "<tr>";
																echo "	
																		<td align='center' width='10%'>
																			<input class='form-control-hide $checked' type='radio' name='opt_nilai_".$id_kriteria."' id='opt_nilai'  data-id_kriteria='".$id_kriteria."' data-id_nilai='".@$det[0]."' data-nilai='".@$det[5]."' data-bobot='".@$dt->bobot."' data-max='".@$dt->max."'>
																		</td>
																	 ";
																echo "	<td><b>".@$det[1]."</b> (".@$det[2].")<br><b>".@$det[3]."</b> (".@$det[4].")";
																echo "	<td align='center'>".@$det[5]."</td>";
																echo "</tr>";
																echo"</table>";
															}	
														}
												echo "</td>";
												echo "<td>".$dt->max."<input type='hidden' name='id_nilai_".$id_kriteria."' ></td>";
												echo "<td>".$dt->bobot."%</td>";
												echo '<td><input type="text" class="form-control col-xs-2 hitung_nilai" name="nilai_'.$id_kriteria.'" readonly width="50px"></td>';
												echo '<td><input type="text" class="form-control col-xs-2 hitung_nilai_bobot" name="nilai_bobot_'.$id_kriteria.'" readonly width="50px"></td>';
												echo '<td><input type="text" class="form-control col-xs-2 hitung_nilai_max" name="nilai_max_'.$id_kriteria.'" readonly width="50px"></td>';
												echo "</tr>";
											}
											echo "<tr>";
											echo "<td colspan='4'>TOTAL</td>";
											echo "<td>$total_bobot %</td>";
											echo '<td><input type="text" class="form-control col-xs-2" name="total_nilai" readonly width="50px" required="required"></td>';
											echo '<td><input type="text" class="form-control col-xs-2" name="total_penilaian" readonly width="50px" required="required"></td>';
											echo '<td><input type="text" class="form-control col-xs-2" name="total_nilai_max" readonly width="50px" required="required"></td>';
											echo "</tr>";
											?>
											
										</tbody>
									</table>
								</div>
								<!--dokumen-->
								<div class="tab-pane" id="tab-dokumen">
									<!--
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="add_pilihan">Ada Perubahan Dokumen Jenis Vendor?</label>
												<div><input type='checkbox' class='switch-onoff form-control-hide' name='cek_dok_jenis_vendor' id='cek_dok_jenis_vendor'></div>
											</div>
											<div class="col-xs-6">
												<label for="add_pilihan">Ada Perubahan Dokumen Kualifikasi Vendor?</label>
												<div><input type='checkbox' class='switch-onoff form-control-hide' name='cek_dok_kualifikasi_vendor' id='cek_dok_kualifikasi_vendor'></div>
											</div>
										</div>
									</div>	
									-->
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="id_tipe">Jenis Vendor</label>
												<select class="form-control select2modal form-control-hide" name="id_jenis_vendor" id="id_jenis_vendor" required="required">
													<?php
														echo "<option value='0'>Pilih Tipe Vendor</option>";
														foreach($jenis as $dt){
															echo"<option value='".$dt->id_jenis_vendor."'>".$dt->jenis_vendor."</option>";
														}
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="id_kategori">Kualifikasi Vendor</label>
												<select class="form-control select2 col-sm-12 form-control-hide" multiple="kualifikasi_spk" name="kualifikasi_spk[]" id="kualifikasi_spk" data-placeholder="Pilih Kualifikasi">
													<?php
														foreach($kualifikasi as $dt){
															echo"<option value='".$dt->id_kualifikasi_spk."'>".$dt->kualifikasi_spk."</option>";
														}
													?>
												</select>
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<div id="show_dokumen_jenis"></div>  
											</div>
											<div class="col-xs-6">
												<div id="show_dokumen_kualifikasi"></div>  
											</div>
										</div>
									</div>	
									
									<!--
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<div id="show_dokumen_tipe"></div>  
											</div>
											<div class="col-xs-6">
												<div id="show_dokumen_kategori"></div>  
											</div>
										</div>
									</div>	
									-->
								</div>
								<!--additional-->
								<div class="tab-pane" id="tab-additional">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="add_pilihan">Apakah sebelumnya pernah melakukan pengadaan tsb?</label>
												<div><input type='checkbox' class='switch-onoff form-control-hide' name='add_pilihan' id='add_pilihan'></div>
											</div>
											<div class="col-xs-6">
												<label for="add_vendor_existing">Sebutkan Kode Vendor Existing</label>
												<input type="text" class="form-control form-control-hide" name="add_vendor_existing" id="add_vendor_existing" placeholder="Kode Vendor Existing"  required="required">
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="add_alasan">Mengapa ganti Vendor?</label>
												<select class="form-control select2modal form-control-hide" name="add_alasan" id="add_alasan" required="required">
													<?php
														echo "<option value='0'>Pilih Alasan</option>";
														echo "<option value='Harga Lebih Murah'>Harga Lebih Murah</option>";
														echo "<option value='Kualitas Lebih Bagus'>Kualitas Lebih Bagus</option>";
														echo "<option value='Jarak Lebih Dekat'>Jarak Lebih Dekat</option>";
														echo "<option value='Perubahan Legalitas'>Perubahan Legalitas</option>";
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="add_vendor_flag">Sebutkan Kode Vendor Existing yang dapat di flag?</label>
												<input type="text" class="form-control form-control-hide" name="add_vendor_flag" id="add_vendor_flag" placeholder="Kode Vendor Existing yang dapat di flag"  required="required">
											</div>
										</div>
									</div>	
								</div>								
								<!-- //// -->
								<!--komentar-->
								<div class="tab-pane" id="tab-komentar">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="komentar">Komentar</label>
												<input type="text" class="form-control" name="komentar" id="komentar" placeholder="Komentar">
											</div>
										</div>
									</div>	
								</div>								
								<!-- //// -->
							</div>
							<div class="clearfix"></div> 
						</div>
						<div class="modal-footer"> 
							<input id="id_data" name="id_data" type="hidden">
							<input id="id_data_temp" name="id_data_temp" type="hidden">
							<input id="level" name="level" type="hidden">
							<input id="approval_legal" name="approval_legal" type="hidden">
							<input id="approval_proc" name="approval_proc" type="hidden">
							<input id="perubahan_data" name="perubahan_data" type="hidden">
							<input id="I_LIFNR" name="I_LIFNR" type="hidden">  
							<input id="I_EKORG" name="I_EKORG" type="hidden">  
							<input id="I_BUKRS" name="I_BUKRS" type="hidden">
							<input id="I_KTOKK" name="I_KTOKK" type="hidden">
							<input id="id_status" name="id_status" type="hidden">
							<input id="id_jenis_vendor_hide" name="id_jenis_vendor_hide" type="hidden">
							<input id="kualifikasi_spk_hide" name="kualifikasi_spk_hide" type="hidden">
							<input id="arr_kualifikasi_spk_hide" name="arr_kualifikasi_spk_hide[]" type="hidden">
							<input id="pengajuan_ho" name="pengajuan_ho" type="hidden">
							<input id="pengajuan_ho_temp" name="pengajuan_ho_temp" type="hidden">
							<input id="action" name="action" type="hidden">
							<input id="action_detail" name="action_detail" type="hidden">
							<input id="pengajuan" name="pengajuan" type="hidden">
							<input id="nama_hide" name="nama_hide" type="hidden">
							<button id="btn_save" type="button" class="btn btn-primary" name="action_btn">Submit</button>
							<button id="btn_change" type="button" class="btn btn-primary" name="btn_change">Change</button>
							<!--<button id="btn_change_sap" type="button" class="btn btn-primary" name="btn_change_sap">Change SAP</button>-->
							<button id="btn_decline_change" type="button" class="btn btn-danger" name="btn_decline_change">Decline</button>
							<button id="btn_approve_change" type="button" class="btn btn-primary" name="btn_approve_change">Approve</button>
							<button id="btn_decline" type="button" class="btn btn-danger" name="btn_decline">Decline</button>
							<button id="btn_approve" type="button" class="btn btn-primary" name="btn_approve">Approve</button>
							<button id="btn_approve_sap" type="button" class="btn btn-primary" name="btn_approve_sap">Approve</button>
							<!--
							<button id="action_btn_approve_manager" type="button" class="btn btn-primary" name="action_btn_approve_manager">Approve Manager Kantor</button>
							<button id="action_btn_approve_legal" type="button" class="btn btn-primary" name="action_btn_approve_legal">Approve Legal HO</button>
							<button id="action_btn_approve_proc" type="button" class="btn btn-primary" name="action_btn_approve_proc">Approve Procurement HO</button>
							-->
						</div>
					</form>
				</div>
			</div>
			
		</div>
	</div>	
</div>
<!--modal extend old-->
<!--
<div class="modal fade" id="add_extend_old" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-extend_vendor">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Extend Master Vendor</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">		
								<label for="code">LIFNR</label>
								<input type="text" class="form-control form-control-hide" name="lifnr" id="lifnr" placeholder="Material Code" readonly>
							</div>
							<div class="form-group">	
								<label for="description">Nama Vendor</label>
								<input type="text" class="form-control" name="nama" id="nama" placeholder="Description"  readonly>
							</div>
							
							<div class="form-group">	
								<label for="plant_asis">Plant</label>
								<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_asis[]" id="plant_asis" data-placeholder="Pilih Plant Extend" disabled>
								</select>
							</div>
							<div class="form-group">	
								<label for="plant_extend">Plant Extend</label>
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAllPlantExtend form-control-hide" id="ck_all"> All Plant Extend</label>
								</div>
								<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_extend[]" id="plant_extend" data-placeholder="Pilih Plant Extend" required="required" >
								</select>
							</div>
						</div>
						<div class="modal-footer">
							<input id="id_data" name="id_data" type="hidden">
							<input id="id_data_temp" name="id_data_temp" type="hidden">
							<input id="level" name="level" type="hidden">
							<input id="pengajuan_ho" name="pengajuan_ho" type="hidden">
							<input id="pengajuan" name="pengajuan" type="hidden">
							<input id="plant_extend_hide" name="plant_extend_hide" type="hidden">
							<input id="I_LIFNR" name="I_LIFNR" type="hidden">
							<input id="I_EKORG_REF" name="I_EKORG_REF" type="hidden">
							<input id="I_BUKRS_REF" name="I_BUKRS_REF" type="hidden">
							<input id="I_KTOKK" name="I_KTOKK" type="hidden">
							
							<button id="btn_save_extend" type="button" class="btn btn-primary" name="btn_save_extend">Submit</button>
							<button id="btn_decline_extend" type="button" class="btn btn-danger" name="btn_decline_extend">Decline</button>
							<button id="btn_save_extend_sap" type="button" class="btn btn-primary" name="btn_save_extend_sap">Approve</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>
-->

<!--modal extend-->
<div class="modal fade" id="add_extend" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="nav-tabs-custom" id="tabs-edit">
					<form role="form" class="form-transaksi-extend_vendor" enctype="multipart/form-data">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Extend Master Vendor</h4>
						</div>
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab-general_extend" data-toggle="tab">Data Vendor</a></li>
							<li><a href="#tab-additional_extend" data-toggle="tab">Additional</a></li>
							<li class="form-control_komentar"><a href="#tab-komentar_extend" data-toggle="tab">Komentar</a></li>
						</ul>
						<div class="modal-body">
							<div class="tab-content">
								<!--general extend-->
								<div class="tab-pane active" id="tab-general_extend">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="code">LIFNR</label>
												<input type="text" class="form-control form-control-hide" name="lifnr" id="lifnr" placeholder="Material Code" readonly>
											</div>
											<div class="col-xs-6">
												<label for="description">Nama Vendor</label>
												<input type="text" class="form-control" name="nama" id="nama" placeholder="Description"  readonly>
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="plant_asis">Plant</label>
												<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_asis[]" id="plant_asis" data-placeholder="Pilih Plant Extend" disabled>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="plant_extend">Plant Extend</label>
												<div class="checkbox pull-right select_all" style="margin:0; display: ;">
													<label><input type="checkbox" class="isSelectAllPlantExtend form-control-hide" id="ck_all"> All Plant Extend</label>
												</div>
												<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_extend[]" id="plant_extend" data-placeholder="Pilih Plant Extend" required="required" >
												</select>
											</div>
										</div>
									</div>	
									
								</div>

								<!--additional extend-->
								<div class="tab-pane" id="tab-additional_extend">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="add_pilihan_extend">Apakah sebelumnya pernah melakukan pengadaan tsb?</label>
												<div><input type='checkbox' class='switch-onoff form-control-hide' name='add_pilihan_extend' id='add_pilihan_extend'></div>
											</div>
											<div class="col-xs-6">
												<label for="add_vendor_existing_extend">Sebutkan Kode Vendor Existing</label>
												<input type="text" class="form-control form-control-hide" name="add_vendor_existing_extend" id="add_vendor_existing_extend" placeholder="Kode Vendor Existing"  disabled">
											</div>
										</div>
									</div>	
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="add_alasan_extend">Mengapa ganti Vendor?</label>
												<select class="form-control select2modal form-control-hide" name="add_alasan_extend" id="add_alasan_extend" disabled>
													<?php
														echo "<option value='0'>Pilih Alasan</option>";
														echo "<option value='Harga Lebih Murah'>Harga Lebih Murah</option>";
														echo "<option value='Kualitas Lebih Bagus'>Kualitas Lebih Bagus</option>";
														echo "<option value='Jarak Lebih Dekat'>Jarak Lebih Dekat</option>";
														echo "<option value='Perubahan Legalitas'>Perubahan Legalitas</option>";
													?>
												</select>
											</div>
											<div class="col-xs-6">
												<label for="add_vendor_flag_extend">Sebutkan Kode Vendor Existing yang dapat di flag?</label>
												<input type="text" class="form-control form-control-hide" name="add_vendor_flag_extend" id="add_vendor_flag_extend" placeholder="Kode Vendor Existing yang dapat di flag"  disabled>
											</div>
										</div>
									</div>	
								</div>								
								<!-- //// -->
								<!--komentar-->
								<div class="tab-pane" id="tab-komentar_extend">
									<div class="form-group">
										<div class="row">
											<div class="col-xs-6">
												<label for="komentar">Komentar</label>
												<input type="text" class="form-control" name="komentar_extend" id="komentar_extend" placeholder="Komentar">
											</div>
										</div>
									</div>	
								</div>								
								<!-- //// -->
								
							</div>
							<div class="clearfix"></div> 
						</div>

						<div class="modal-footer">
							<input id="id_data" name="id_data" type="hidden">
							<input id="id_data_temp" name="id_data_temp" type="hidden">
							<input id="level" name="level" type="hidden">
							<input id="pengajuan_ho" name="pengajuan_ho" type="hidden">
							<input id="pengajuan" name="pengajuan" type="hidden">
							<input id="plant_extend_hide" name="plant_extend_hide" type="hidden">
							<input id="I_LIFNR" name="I_LIFNR" type="hidden">
							<input id="I_EKORG_REF" name="I_EKORG_REF" type="hidden">
							<input id="I_BUKRS_REF" name="I_BUKRS_REF" type="hidden">
							<input id="I_KTOKK" name="I_KTOKK" type="hidden">
							
							<button id="btn_save_extend" type="button" class="btn btn-primary" name="btn_save_extend">Submit</button>
							<button id="btn_decline_extend" type="button" class="btn btn-danger" name="btn_decline_extend">Decline</button>
							<button id="btn_save_extend_sap" type="button" class="btn btn-primary" name="btn_save_extend_sap">Approve</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>
<!--modal delete-->
<div class="modal fade" id="add_delete" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-delete_vendor">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Delete Master Vendor</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">		
								<label for="code">LIFNR</label>
								<input type="text" class="form-control form-control-hide" name="lifnr" id="lifnr" placeholder="Material Code" readonly>
							</div>
							<div class="form-group">	
								<label for="description">Nama Vendor</label>
								<input type="text" class="form-control" name="nama" id="nama" placeholder="Description"  readonly>
							</div>
							<div class="form-group">	
								<label for="plant_extend">Plant Delete</label>
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAllPlantDelete form-control-hide" id="ck_all_delete"> All Plant Delete</label>
								</div>
								<!--
								<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_delete[]" id="plant_delete" data-placeholder="Pilih Plant Delete" required="required" >
								</select>
								-->
								<select class="form-control select2" multiple="multiple" name="plant_delete[]" id="plant_delete" data-placeholder="Pilih Plant Delete" required="required">
									<?php
										foreach($plant as $dt){
											echo "<option value='".$dt->plant."'>".$dt->plant." xx</option>";
										}
									?>
								</select>
								
							</div>
							<div class="form-group">	
								<label for="description">Alasan Delete</label>
								<select class="form-control form-control-hide select2" id="alasan_delete" name="alasan_delete" style="width: 100%;" data-placeholder="Pilih Alasan Delete" required>
									<?php
										echo "<option value='0'>Alasan Delete</option>";
										echo"<option value='Double Vendor'>Double Vendor</option>";
										echo"<option value='Evaluasi Vendor'>Evaluasi Vendor</option>";
										echo"<option value='Ganti Vendor'>Ganti Vendor</option>";
										echo"<option value='Perubahan Legalitas'>Perubahan Legalitas</option>";
										echo"<option value='Rekomendasi Audit'>Rekomendasi Audit</option>";
										echo"<option value='Tanpa Transaksi'>Tanpa Transaksi</option>";
										echo"<option value='Tanpa Update Dokumen'>Tanpa Update Dokumen</option>";
									?>
								</select>
								
							</div>
							<div class="form-group">
								<label for="description">Kode Vendor</label>
								<input type="number" class="form-control form-control-hide alasan_delete_detail" name="alasan_delete_detail" id="alasan_delete_detail" placeholder="Kode Vendor">
							</div>
							<div class="form-group form-control_komentar">	
								<label for="komentar">Komentar</label>
								<input type="text" class="form-control" name="komentar_delete" id="komentar_delete" placeholder="Komentar">
							</div>
						</div>
						<div class="modal-footer">
							<input id="id_data" name="id_data" type="hidden">
							<input id="id_data_temp" name="id_data_temp" type="hidden">
							<input id="pengajuan_ho" name="pengajuan_ho" type="hidden">
							<input id="level" name="level" type="hidden">
							<input id="plant_delete_hide" name="plant_delete_hide" type="hidden">
							<input id="I_LIFNR" name="I_LIFNR" type="hidden">  
							<input id="I_EKORG" name="I_EKORG" type="hidden">  
							<input id="I_BUKRS" name="I_BUKRS" type="hidden">
							<button id="btn_save_delete" type="button" class="btn btn-primary" name="btn_save_delete">Submit</button>
							<button id="btn_decline_delete" type="button" class="btn btn-danger" name="btn_decline_delete">Decline</button>
							<button id="btn_save_delete_sap" type="button" class="btn btn-primary" name="btn_save_delete_sap">Approve</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>
<!--modal undelete-->
<div class="modal fade" id="add_undelete" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-undelete_vendor">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Undelete Master Vendor</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">		
								<label for="code">LIFNR</label>
								<input type="text" class="form-control form-control-hide" name="lifnr" id="lifnr" placeholder="Material Code" readonly>
							</div>
							<div class="form-group">	
								<label for="description">Nama Vendor</label>
								<input type="text" class="form-control" name="nama" id="nama" placeholder="Description"  readonly>
							</div>
							<div class="form-group">	
								<label for="plant_extend">Plant Undelete</label>
								<div class="checkbox pull-right select_all" style="margin:0; display: ;">
									<label><input type="checkbox" class="isSelectAllPlantUndelete form-control-hide" id="ck_all_undelete"> All Plant Undelete</label>
								</div>
								<!--
								<select class="form-control form-control-hide select2 col-sm-12" multiple="multiple" name="plant_undelete[]" id="plant_undelete" data-placeholder="Pilih Plant Undelete" required="required" >
								</select>
								-->
								<select class="form-control select2" multiple="multiple" name="plant_undelete[]" id="plant_undelete" data-placeholder="Pilih Plant Undelete" required="required">
									<?php
										foreach($plant as $dt){
											echo "<option value='".$dt->plant."'>".$dt->plant."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">	
								<label for="alasan_undelete">Alasan Undelete</label>
								<input type="text" class="form-control" name="alasan_undelete" id="alasan_undelete" placeholder="Alasan Undelete" >
							</div>
							<div class="form-group form-control_komentar">	
								<label for="komentar">Komentar</label>
								<input type="text" class="form-control" name="komentar_undelete" id="komentar_undelete" placeholder="Komentar">
							</div>
							
						</div>
						<div class="modal-footer">
							<input id="id_data" name="id_data" type="hidden">
							<input id="id_data_temp" name="id_data_temp" type="hidden">
							<input id="pengajuan_ho" name="pengajuan_ho" type="hidden">
							<input id="plant_undelete_hide" name="plant_undelete_hide" type="hidden">
							<input id="level" name="level" type="hidden">
							<input id="I_LIFNR" name="I_LIFNR" type="hidden">  
							<input id="I_EKORG" name="I_EKORG" type="hidden">  
							<input id="I_BUKRS" name="I_BUKRS" type="hidden">
							<input id="id_status_undelete" name="id_status_undelete" type="hidden">
							<button id="btn_save_undelete" type="button" class="btn btn-primary" name="btn_save_undelete">Submit</button>
							<button id="btn_decline_undelete" type="button" class="btn btn-danger" name="btn_decline_undelete">Decline</button>
							<button id="btn_save_undelete_sap" type="button" class="btn btn-primary" name="btn_save_undelete_sap">Approve</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>
<!--modal status-->
<div class="modal fade" id="vendor_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-input">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Cek data SAP</h4>
						</div>
						<div class="modal-body">
							<div class="input-group input-group-sm">
								<input style="text-transform: uppercase" type="text" name="nama_vendor" id="nama_vendor" class="form-control" placeholder="Masukan Nama Vendor"  required="required">
								<span class="input-group-btn">
									<button class="btn btn-info btn-flat" type="button" id="cek_btn_vendor">Cek</button>
								</span>
							</div>					
						</div>
						<div class="modal-body">
							<div id='show_vendor'></div>									
						</div>
						<div class="modal-footer">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>
<!--modal history-->
<!--
<div class="modal fade" id="modal-history" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="modal-content">
					<form role="form" class="form-transaksi-extend-vendor">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">History Master Vendor</h4>
						</div>
						<div class="modal-body">
							<div id='show_history'></div>									
						</div>
						<div class="modal-footer">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>	
</div>
-->
<!--modal history-->
<div class="modal fade" id="modal-history" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="col-sm-12">
				<div class="nav-tabs-custom" id="tabs-edit">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">History Master Vendor</h4>
					</div>
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-4">
			            		<div class="form-group">
				                	<label> LIFNR: </label>
									<input type="text" class="form-control" name="lifnr" id="lifnr" placeholder="LIFNR"  disabled>
				            	</div>
			            	</div>
			          		<div class="col-sm-4">
			            		<div class="form-group">
				                	<label> Nama Vendor: </label>
									<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Vendor"  disabled>
				            	</div>
			            	</div>
						</div>	
					</div>	
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-pengajuan" data-toggle="tab">Create</a></li>
						<li><a href="#tab-extend" data-toggle="tab">Extend</a></li>
						<li><a href="#tab-change" data-toggle="tab">Change</a></li>
						<li><a href="#tab-delete" data-toggle="tab">Delete</a></li>
						<li><a href="#tab-undelete" data-toggle="tab">Undelete</a></li>
					</ul>
					<div class="modal-body">
						<div class="tab-content">
							<!--general-->
							<div class="tab-pane active" id="tab-pengajuan">
								<div id='histori_pengajuan'></div>
							</div>
							<!--extend-->
							<div class="tab-pane" id="tab-extend">
								<div id='histori_extend'></div>
							</div>
							<!--change-->
							<div class="tab-pane" id="tab-change">
								<div id='histori_change'></div>
							</div>
							<!--delete-->
							<div class="tab-pane" id="tab-delete">
								<div id='histori_delete'></div>
							</div>
							<!--undelete-->
							<div class="tab-pane" id="tab-undelete">
								<div id='histori_undelete'></div>
							</div>
						</div>
						<div class="clearfix"></div> 
					</div>
					<div class="modal-footer"> 
					</div>
				</div>
			</div>
			
		</div>
	</div>	
</div>


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/vendor/transaksi/input.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
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