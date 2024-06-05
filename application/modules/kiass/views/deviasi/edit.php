<?php $this->load->view('header') ?>
<!-- 
/*
    @application  : K-IASS
    @author       : MATTHEW JODI (8944)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */ -->

<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css">

<style type="text/css">
	.datepicker{
		border-radius: 0;
	}
	.table-form,
	.table-form th, 
	.table-form td, 
	.table-form tr{
		border: solid 1px black !important;
	}

	.border{
		border: solid 1px black !important;
		padding: 10px;
	}
	textarea{
		resize: vertical;
	}

	.c-label{
		font-weight:400;
		font-size:small;
	}

	.mw200{
		min-width:200px;
	}

	.mw100{
		min-width:100px;
	}

	.scrolls {
		overflow-x: scroll;
		overflow-y: hidden;
		/* height: 80px; */
		white-space:nowrap
	}

	.select2-container--default .select2-selection--multiple .select2-selection__choice:first-of-type {
	    background-color: #da4a38 !important;
	    border-color: #dd4b39;
	}
</style>

<!-- mockup form scrap -->

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success page-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong>Form Edit Deviasi</strong></h3>
					</div>
					<form class="form-deviasi-scrap"
						  enctype="multipart/form-data">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-6 form-horizontal">
									<div class="form-group">
										<label for="no_pp"
											   class="col-sm-4 control-label">Nomor Deviasi</label>
										<div class="col-sm-8">
											<input type="text"
												   class="form-control"
												   name="no_deviasi"
												   value="<?php echo $no_deviasi; ?>"
												   readonly="readonly"
												   required="required">
										</div>
									</div>
									<div class="form-group">
										<label for="no_pp"
											   class="col-sm-4 control-label">Nomor PP</label>
										<div class="col-sm-8">
											<input type="text"
												   class="form-control"
												   name="no_pp"
												   readonly="readonly"
												   required="required">
										</div>
									</div>
									<div class="form-group">
										<label for="tgl_pengajuan"
											   class="col-sm-4 control-label">Tanggal Pengajuan</label>
										<div class="col-sm-8">
											<input type="text"
												   name="tgl_pengajuan"
												   class="form-control"
												   placeholder="Masukkan Tanggal Pengajuan"
												   required="required"
												   id="tgl_pengajuan"
												   readonly="readonly"/>
										</div>
									</div>
									<div class="form-group hide">
										<label for="no_pp"
											   class="col-sm-4 control-label">Selisih</label>
										<div class="col-sm-8">
											<input type="text"
												   class="form-control"
												   name="selisih"
												   value="10%"
												   readonly="readonly"
												   required="required">
										</div>
									</div>
									
								</div>
							</div>

						</div> <!-- TOP FORM -->
						
						<div class="box-body">
		              		<div class="row">
		              			<div class="col-sm-12">
		              				<div class="border">
			              				<div class="form-group">
			              					<label>Penjelasan Deviasi</label>
			              					<textarea class="form-control" name="latar_belakang" id="latar_belakang" required="required"></textarea>
			              				</div>
		              				</div>
		              			</div>
		              		</div>
		            	</div> <!--end box-body-->
		            	
		            	<div class="box-body">
							<div class="row">
								<div class="col-sm-12">
									<fieldset style="border:1px solid black;">
										<legend class="text-center">Analisa Harga</legend>
											<div class="table-responsive scrolls">
												<table class="table table-hover table-form">
													<thead>
														<tr>
															<th class="text-center" rowspan="4">NO</th>
															<th class="text-center" rowspan="4">Kode Material</th>
															<th class="text-center" rowspan="4">Deskripsi</th>
															<th class="text-center" rowspan="4">Rincian</th>
															<th class="text-center" rowspan="4">UOM</th>
															<!-- <th class="text-center" rowspan="4">Kode Asset</th> -->
															<th class="text-center" rowspan="4">SO number</th>
															<th class="text-center" rowspan="4">Pemenang</th>												
															<th class="text-center" rowspan="4">Qty (Disetujui)</th>
															<th class="text-center" rowspan="4">Harga Satuan (Disetujui)<br><small><em>Sebelum PPN</em></small></th>												
															<th class="text-center" rowspan="4">Harga Total (Disetujui)<br><small><em>Sebelum PPN</em></small></th>
															<th class="text-center" rowspan="4">Qty (Deviasi)</th>
															<th class="text-center" rowspan="4">Harga Satuan (Deviasi)<br><small><em>Sebelum PPN</em></small></th>												
															<th class="text-center" rowspan="4">Harga Total (Deviasi)<br><small><em>Sebelum PPN</em></small></th>																								
															<th class="text-center" rowspan="4">Keterangan</th>												
														</tr>									
													</thead>
													<tbody class="tbody">
																									
													</tbody>
												</table>
											</div>
										</legend>
									</fieldset>			
								</div>				
							</div>						
						</div>

						<div class="box-body">
		              		<div class="row">
		              			<div class="col-sm-12">
		              				<div class="border">
									  <div class="form-group" style="margin-bottom:20px;">
			              					<label>Lampiran</label>
											<div class="input-group" style="width: 100%;">
												<input type="text" class="form-control caption_file" name="caption_lampiran" readonly="readonly">
												<div class="input-group-btn">
													<input type="file" name="lampiran[]" class="form-control upload_file berkas" style="display:none;">
													<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload"><i class="fa fa-upload"></i></button>
												</div>
												<div class="input-group-btn">
													<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>
												</div>
											</div>
                                          
                                        </div>
		              				</div>
		              			</div>
		              		</div>
		            	</div>

						
						<div class="box-footer">
							<input type="hidden" name="counter">
							<input type="hidden" name="id_flow">
							<input type="hidden" name="action" value="edit">
							<button type="button"
									class="btn btn-sm btn-success"
									name="action_btn"
									value="submit">Submit
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/kiass/deviasi/edit.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>

