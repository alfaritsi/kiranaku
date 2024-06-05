<!--
/*
@application  : SPK - Matrix Vendor
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
			<div class="col-sm-8">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
			          	<div class="row">
							<!--
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Plant: </label>
									<select class="form-control select2" multiple="multiple" id="plant" name="plant[]" style="width: 100%;" data-placeholder="Pilih Plant">
									</select>
				            	</div>
			            	</div>
							-->
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Status PKP: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_pkp" name="status_pkp[]" style="width: 100%;" data-placeholder="Pilih Status PKP">
										<option value='PKP'>PKP</option>
										<option value='NON PKP'>NON PKP</option>
				                  	</select>
				            	</div>
			            	</div>  
			          		<div class="col-sm-3"> 
			            		<div class="form-group"> 
				                	<label> Status: </label>
				                	<select class="form-control select2" multiple="multiple" id="status" name="status[]" style="width: 100%;" data-placeholder="Pilih Status">
										<option value='Completed'>Completed</option>
										<option value='Not Completed'>Not Completed</option>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Jenis Vendor: </label>
									<select class="form-control select2" multiple="multiple" id="jenis_vendor" name="jenis_vendor[]" style="width: 100%;" data-placeholder="Pilih Jenis Vendor">
									</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
					<!-- /.box-filter -->
		          	<div class="box-body"> 
						<table class="table table-bordered table-striped"
							   id="sspTable">
							<thead>
								<tr>
									<th>Id</th> 
									<th>Plant</th>
									<th>Status PKP</th>
									<th>Vendor</th>
									<th>Kota</th>
									<th>Alamat</th>
									<th>Jenis Vendor</th>
									<th>Jenis SPK</th>
									<th>Kualifikasi</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
						</table>
			        </div>
				</div>
			</div>
            <div class="col-sm-4">

                <div class="nav-tabs-custom" id="tabs-edit">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab-edit" data-toggle="tab">
                                <strong class="title-form">Setting Vendor</strong>
                            </a>
                        </li>
                    </ul>
                    <form role="form" class="form-master-matrix" enctype="multipart/form-data">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-edit">
                                <div class="box-body">
									
									<div class="form-group">
										<label for="lifnr">Kode Vendor</label>
										<input type="text" class="form-control" name="lifnr" id="lifnr" disabled  required="required">
									</div>
									<!--
									<div class="form-group">
										<label for="plant">Plant</label>
										<input type="text" class="form-control" name="plant" id="plant" disabled  required="required">
									</div>
									-->
									<div class="form-group">
										<label for="vendor">Vendor</label>
										<input type="text" class="form-control" name="vendor" id="vendor" disabled  required="required">
									</div>
									<!--
									<div class="form-group">
										<div class="col-xs12">
											<fieldset class="fieldset-info">
												<legend>Dokumen Berita Acara</legend>
												<div class="box-body">
													<table table-bordered table-doc">
														<tbody>
															<tr>
																<td><i class="fa fa-times text-red"></i> Dokumen Berita Acara</td>
																<td width='10%'>
																	<div class='input-group-btn'>
																	<button type='button' class='btn btn-default btn-xs dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
																	<ul class='dropdown-menu pull-right'>
																		<li><a href='javascript:void(0)' class='upload' >Upload</a></li>
																	</ul>	
																</td>
															</tr>
														</tbody>
													</table>	
											</fieldset>
										</div>
									</div>
									-->
									<div class="form-group">
										<label for="id_jenis_vendor">Jenis Vendor</label>
										<select class="form-control select2" name="id_jenis_vendor" id="id_jenis_vendor"  required="required">
										</select>
									</div>
									<div class="form-group">
										<div id="dokumen_vendor"> 
										</div>
									</div>
									<div class="form-group"> 
										<label for="id_jenis_spk">Jenis SPK</label>
										<select class="form-control select2" name="id_jenis_spk" id="id_jenis_spk"  required="required">
										</select>
									</div>
									<div class="form-group">
										<label for="kualifikasi">Kualifikasi</label> 
										<select class="form-control select2 col-sm-12" multiple="kualifikasi" name="kualifikasi[]" id="kualifikasi" data-placeholder="Pilih Kualifikasi" required="required">
											<?php
												// foreach($kualifikasi as $dt){
													// echo "<option value='".$dt->id_kualifikasi."'>".$dt->nama_kualifikasi."</option>";
												// }
											?>
										</select> 
									</div> 
									<div class="form-group">
										<div id="dokumen_kualifikasi">
										</div>  
									</div>			  
                                </div>
                            </div> 
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="count_mandatory">
                            <input type="hidden" name="count_dokumen">
                            <input type="hidden" name="count_mandatory_kualifikasi">
                            <input type="hidden" name="count_dokumen_kualifikasi">
							<input type="hidden" name="kualifikasi_value">
							<input type="hidden" name="lifnr">
                            <input type="hidden" name="ekorg">  
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
		</div>
	</section>
</div>
<!-- Upload Modal --> 
<div class="modal fade" id="upload_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form role="form" id="form-upload" enctype="multipart/form-data">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="upload_title" style="text-transform: capitalize; font-weight: bold;">UPLOAD FILE</h4>
			</div> 
			<div class="modal-body">
				<div class="row">  
					<div class="col-sm-12"> 
						<div class="form-group"> 
							<label id="Upload_text" style="font-weight: 500;">Please Select Files to Upload Below</label><br>
							<input type="hidden" name="id_folder">
							<input type="hidden" name="id_file">
							<input type="hidden" name="apps" value="vendor"> 
							<input type="hidden" name="name">    
							<!--<input type="file" class="form-control" id="fileUpload" name="fileUpload[]" multiple="multiple">-->
							<input type="file" multiple="multiple" class="form-control" id="file" name="file[]">
							<label style="color: red; font-weight: 500;">*File format (.pdf | .docx | .doc | .xls | .xlsx).</label>
							<div class="form-group" id="file_list"></div>
						</div> 
					</div>   
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success" name="submit_upload" id="submit_upload">Submit</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="view_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="view_modal_label" style="text-transform: capitalize">FILE</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<div id="show_file"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spk/master_matrix.js"></script>
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