<!--
/*
@application  : BANK Specimen
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
	          		</div>
	          		<!-- /.box-header -->
					<form role="form" class="form-bank-create">
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-6">
			            		<div class="form-group">
				                	<label> Jenis Pengajuan: </label>
				                	<select class="form-control select2" id="jenis_pengajuan" name="jenis_pengajuan" style="width: 100%;" data-placeholder="Pilih Jenis Pengajuan"  required="required">
				                  		<?php
											echo "<option value=''></option>";
											echo "<option value='pembukaan'>Pembukaan Rekening</option>";
											echo "<option value='penutupan'>Penutupan Rekening</option>";
											echo "<option value='perubahan'>Perubahan Rekening</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-6">
			            		<div class="form-group">
				                	<label> Nomor: </label>
									<input type="text" class="form-control" name="nomor" id="nomor" placeholder="Input Nomor" readonly>
				            	</div>
			            	</div>
		            	</div>
			          	<div class="row">
			          		<div class="col-sm-6">
			            		<div class="form-group">
				                	<label> Tanggal: </label>
									<input type="text" class="form-control" name="tanggal" id="tanggal" value="<?php echo date('d.m.Y');?>" placeholder="Tanggal" required="required">
				            	</div>
			            	</div>
			          		<div class="col-sm-6">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
				                	<select class="form-control select2" id="pabrik" name="pabrik" style="width: 100%;" data-placeholder="Pilih Pabrik"  required="required">
				                  		<?php
											if(!empty($user_role[0]->pabrik)){
												$arr_pabrik = explode(",", $user_role[0]->pabrik);
												echo "<option value='0'>Pilih Pabrik</option>";
												foreach ($arr_pabrik as $pabrik) {
													if($pabrik!=''){
														echo "<option value='$pabrik'>$pabrik</option>";
													}
												}
											}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
			          	<div class="row">
			          		<div class="col-sm-12">
			            		<div class="form-group">
				                	<label> Latar Belakang: </label>
									<textarea name="latar_belakang" id="latar_belakang" class="form-control" rows="3" placeholder="Masukan Latar Belakang" required="required"></textarea>
				            	</div>
			            	</div>
		            	</div>
						<!--Umum-->
						<fieldset class="fieldset-success">
							<legend class="text-left"><h4>Umum</h4></legend>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Nama Bank: </label>
										<div id="show_nama_bank">
											<input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="Nama Bank" required="required">
										</div>
										<div id="show_nama_bank_auto">
											<select class="form-control select2 form-control-penutupan" name="id_data" id="id_data"  required="required"></select>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Nomor Rekening: </label>
										<input type="text" class="form-control" name="nomor_rekening" id="nomor_rekening" placeholder="Nomor Rekening" readonly>
									</div>
								</div>
							</div>	
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Cabang: </label>
										<input type="text" class="form-control" name="cabang_bank" id="cabang_bank" placeholder="Cabang Bank" required="required">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Mata Uang: </label>
										<select class="form-control select2" id="mata_uang" name="mata_uang" style="width: 100%;" required="required">
											<?php
												echo "<option value='0'>Pilih Mata Uang</option>";
												foreach($mata_uang as $dt){
													if($dt->mata_uang=='IDR'){
														echo"<option value='".$dt->mata_uang."' selected>".$dt->mata_uang."</option>";
													}else{
														echo"<option value='".$dt->mata_uang."'>".$dt->mata_uang."</option>";
													}
													
												}
											?>
										</select>
									</div>
								</div>
							</div>	
						</fieldset>		
						<!--pembukaan-->	
						<fieldset class="fieldset-success" id="form_pembukaan">
							<legend class="text-left"><h4>Pembukaan Rekening</h4></legend>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Tujuan Penggunaan: </label>
										<select class="form-control form-control-pembukaan select2" id="tujuan" name="tujuan" style="width: 100%;" required="required">
											<?php
												echo "<option value='0'>Pilih Tujuan Penggunaan</option>";
												echo "<option value='bokar'>Bokar</option>";
												echo "<option value='non_bokar'>Non Bokar</option>";
												echo "<option value='depo'>Depo</option>";
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Penggunaan Detail: </label>
										<input type="text" class="form-control form-control-pembukaan" name="tujuan_detail" id="tujuan_detail" placeholder="Penggunaan Detail" required="required">
									</div>
								</div>
							</div>	
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>No COA: </label>
										<input type="text" class="form-control form-control-pembukaan" name="no_coa" id="no_coa" placeholder="No COA" readonly>
										<sup>*Diisi oleh Accounting HO</sup>
									</div>
								</div>
							</div>	
							<fieldset class="fieldset-default">
								<legend class="text-left"><h4>Nama Penandatangan Specimen</h4></legend>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label>Pihak Prioritas 1: </label>
											<select class="form-control select2 form-control-pembukaan" name="prioritas1" id="prioritas1"  required="required">
											</select>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label>Pihak Pendamping</label>
											<select class="form-control select2 form-control-pembukaan" multiple="multiple" id="pendamping" name="pendamping[]" style="width: 100%;">
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label>Pihak Prioritas 2: </label>
											<select class="form-control select2 form-control-pembukaan" name="prioritas2" id="prioritas2"  required="required">
											</select>
										</div>
									</div>
								</div>
							</fieldset>										
						</fieldset>					
						<!--penutupan-->
						<fieldset class="fieldset-success" id="form_penutupan">
							<legend class="text-left"><h4>Penutupan Rekening</h4></legend>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Sisa Dana: </label>
										<input type="text" class="form-control angka form-control-penutupan" name="sisa_dana" id="sisa_dana" placeholder="Sisa Dana" required="required">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Ditransfer ke*: </label>
										<select class="form-control select2 form-control-penutupan" name="id_data_tujuan" id="id_data_tujuan"  required="required"></select>
										<sup>* Tujuan transfer sisa dana rekening yang akan ditutup</sup>
									</div>
								</div>
							</div>	
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Nama Bank Tujuan: </label>
										<input type="text" class="form-control form-control-penutupan" name="nama_bank_tujuan" id="nama_bank_tujuan" placeholder="Nama Bank Tujuan" required="required" readonly>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>Cabang Bank Tujuan: </label>
										<input type="text" class="form-control form-control-penutupan" name="cabang_bank_tujuan" id="cabang_bank_tujuan" placeholder="Cabang Bank Tujuan" required="required"  readonly>
									</div>
								</div>
							</div>	
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label>Nomor Rekening Tujuan: </label>
										<input type="text" class="form-control form-control-penutupan" name="nomor_rekening_tujuan" id="nomor_rekening_tujuan" placeholder="Nomor Rekening Tujuan" required="required"  readonly>
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group">
										<label>No COA Tujuan: </label>
										<input type="text" class="form-control form-control-penutupan" name="no_coa_tujuan" id="no_coa_tujuan" placeholder="No COA Tujuan" required="required" readonly>
									</div>
								</div>
							</div>	
						</fieldset>										
						<!--perubahan-->
						<fieldset class="fieldset-success"  id="form_perubahan">
							<legend class="text-left"><h4>Perubahan Rekening</h4></legend>
							<div class="row">
								<div class="col-sm-6">
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>Tujuan Lama</h4></legend>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label> Tujuan Penggunaan: </label>
													<select class="form-control form-control-perubahan select2" id="tujuan_old" name="tujuan_old" style="width: 100%;" required="required">
														<?php
															echo "<option value='0'>Pilih Tujuan</option>";
															echo "<option value='bokar'>Bokar</option>";
															echo "<option value='non_bokar'>Non Bokar</option>";
															echo "<option value='depo'>Depo</option>";
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Penggunaan Detail: </label>
													<input type="text" class="form-control form-control-perubahan" name="tujuan_detail_old" id="tujuan_detail_old" placeholder="Tujuan Detail" required="required">
												</div>
											</div>
										</div>
									</fieldset>	
								</div>
								<div class="col-sm-6">
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>Tujuan Baru</h4></legend>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label> Tujuan Penggunaan: </label>
													<select class="form-control form-control-perubahan select2" id="tujuan_new" name="tujuan_new" style="width: 100%;" required="required">
														<?php
															echo "<option value='0'>Pilih Tujuan</option>";
															echo "<option value='bokar'>Bokar</option>";
															echo "<option value='non_bokar'>Non Bokar</option>";
															echo "<option value='depo'>Depo</option>";
														?>
													</select>
												</div>
												<div class="form-group">
													<label>Penggunaan Detail: </label>
													<input type="text" class="form-control form-control-perubahan" name="tujuan_detail_new" id="tujuan_detail_new" placeholder="Tujuan Detail" required="required">
												</div>
											</div>
										</div>
									</fieldset>	
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>Specimen Lama</h4></legend>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label>Pihak Prioritas 1: </label>
													<select class="form-control select2 form-control-perubahan" name="prioritas1_old" id="prioritas1_old"  required="required">
													</select>
												</div>
												<div class="form-group">
													<label>Pihak Prioritas 2: </label>
													<select class="form-control select2 form-control-perubahan" name="prioritas2_old" id="prioritas2_old"  required="required">
													</select>
												</div>
												<div class="form-group">
													<label>Pihak Pendamping</label>
													<select class="form-control select2 form-control-perubahan" multiple="multiple" id="pendamping_old" name="pendamping_old[]" style="width: 100%;">
													</select>
												</div>
											</div>
										</div>
									</fieldset>	
								</div>
								<div class="col-sm-6">
									<fieldset class="fieldset-default">
										<legend class="text-left"><h4>Specimen Baru</h4></legend>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label>Pihak Prioritas 1: </label>
													<select class="form-control select2 form-control-perubahan" name="prioritas1_new" id="prioritas1_new"  required="required">
													</select>
												</div>
												<div class="form-group">
													<label>Pihak Prioritas 2: </label>
													<select class="form-control select2 form-control-perubahan" name="prioritas2_new" id="prioritas2_new"  required="required">
													</select>
												</div>
												<div class="form-group">
													<label>Pihak Pendamping</label>
													<select class="form-control select2 form-control-perubahan" multiple="multiple" id="pendamping_new" name="pendamping_new[]" style="width: 100%;">
													</select>
												</div>
											</div>
										</div>
									</fieldset>	
								</div>
							</div>
						</fieldset>										
		            </div>					
					<div class="box-footer">
						<input id="id_data_temp" name="id_data_temp" type="hidden">
						<input id="status" name="status" value="<?php echo $user_role[0]->level;?>" type="hidden">
						<input id="status_approve" name="status_approve" value="<?php echo $user_role[0]->if_approve;?>" type="hidden">
						<input id="status_decline" name="status_decline" value="<?php echo $user_role[0]->if_decline;?>" type="hidden">
						<button type="button" id="reset_btn" class="btn btn-danger">Reset</button>
						<button type="button" name="action_btn" class="btn btn-success">Submit</button>
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>


<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/bank/transaksi/create.js"></script>
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