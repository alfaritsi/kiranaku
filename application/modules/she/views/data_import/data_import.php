<!--
/*
@application    : SHE 
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong> <?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">

					    <ul class="nav nav-tabs pull-right">
					      <li><a href="#tab_8" data-toggle="tab">Air Limbah Bulanan</a></li>
					      <li><a href="#tab_7" data-toggle="tab">Air Limbah Harian</a></li>
					      <li><a href="#tab_6" data-toggle="tab">Konversi Limbah</a></li>
					      <li><a href="#tab_5" data-toggle="tab">Baku Mutu</a></li>
					      <li><a href="#tab_4" data-toggle="tab">Parameter</a></li>
					      <li><a href="#tab_3" data-toggle="tab">Jenis / Lokasi</a></li>
					      <li><a href="#tab_2" data-toggle="tab">Kapasitas IPAL</a></li>
					      <li class="active"><a href="#tab_1" data-toggle="tab">Limbah</a></li>
					      <!--li class="pull-left header"><i class="fa fa-leaf"></i> Import Data</li-->
					    </ul>

					    <div class="tab-content">
					      <div class="tab-pane active" id="tab_1">
					      	<div class="row">
						      	<div class="col-md-6">
						      		<h4 class="page-header"><i class="fa fa-download"></i> Import data limbah dari excel</h4>
						      		<div id="messageBox" class="alert" style="display: none"></div>
						      		<form action="" id="limbahupload" method="POST" class="form-horizontal" role="form">
					      				<div class="form-group">
					      					<label for="filelimbah" class="control-label col-md-3">Pilih File</label>
					      					<div class="col-md-6">
					      						<input type="file" class="form-control" name="filelimbah" id="filelimbah" required>
					      					</div>
					      				</div>
					      				<div class="form-group">
					      					<div class="col-sm-9 col-sm-offset-3">
					      						<button type="reset" class="btn btn-default">Reset</button>
					      						<button type="submit" class="btn btn-primary">Go</button>
					      					</div>
					      				</div>
						      		</form>
						      	</div>
						      	<div class="col-md-6">
						      		<h4 class="page-header"><i class="fa fa-cloud-download"></i> Download Template upload</h4>
						      	</div>
						      </div>
					      </div>
					      <!-- /.tab-pane -->
					      <div class="tab-pane" id="tab_2">
					        <div class="row">
						      	<div class="col-md-6">
						      		<h4 class="page-header"><i class="fa fa-download"></i> Import data Kapasitas IPAL dari excel</h4>
						      		<div id="messageBox2" class="alert" style="display: none"></div>
						      		<form action="" id="kapasitas_ipalupload" method="POST" class="form-horizontal" role="form">
					      				<div class="form-group">
					      					<label for="filekapasitas_ipal" class="control-label col-md-3">Pilih File</label>
					      					<div class="col-md-6">
					      						<input type="file" class="form-control" name="filekapasitas_ipal" id="filekapasitas_ipal" required>
					      					</div>
					      				</div>
					      				<div class="form-group">
					      					<div class="col-sm-9 col-sm-offset-3">
					      						<button type="reset" class="btn btn-default">Reset</button>
					      						<button type="submit" class="btn btn-primary">Go</button>
					      					</div>
					      				</div>
						      		</form>
						      	</div>
						      	<div class="col-md-6">
						      		<h4 class="page-header"><i class="fa fa-cloud-download"></i> Download Template upload</h4>
						      	</div>
						      </div>
					      </div>
					      <!-- /.tab-pane -->
					      <div class="tab-pane" id="tab_3">
					        <div class="row">
						      	<div class="col-md-6">
						      		<h4 class="page-header"><i class="fa fa-download"></i> Import Data Jenis / Lokasi dari excel</h4>
						      		<div id="messageBox3" class="alert" style="display: none"></div>
						      		<form action="" id="jenisupload" method="POST" class="form-horizontal" role="form">
					      				<div class="form-group">
					      					<label for="filejenis" class="control-label col-md-3">Pilih File</label>
					      					<div class="col-md-6">
					      						<input type="file" class="form-control" name="filejenis" id="filejenis" required>
					      					</div>
					      				</div>
					      				<div class="form-group">
					      					<div class="col-sm-9 col-sm-offset-3">
					      						<button type="reset" class="btn btn-default">Reset</button>
					      						<button type="submit" class="btn btn-primary">Go</button>
					      					</div>
					      				</div>
						      		</form>
						      	</div>
						      	<div class="col-md-6">
						      		<h4 class="page-header"><i class="fa fa-cloud-download"></i> Download Template upload</h4>
						      	</div>
						      </div>
					      </div>
					      <!-- /.tab-pane -->
					      <div class="tab-pane" id="tab_4">
					        <div class="row">
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-download"></i> Import data Parameter dari excel</h4>
					            <div id="messageBox4" class="alert" style="display: none"></div>
					            <form action="" id="parameteupload" method="POST" class="form-horizontal" role="form">
					              <div class="form-group">
					                <label for="fileparamete" class="control-label col-md-3">Pilih File</label>
					                <div class="col-md-6">
					                  <input type="file" class="form-control" name="fileparamete" id="fileparamete" required>
					                </div>
					              </div>
					              <div class="form-group">
					                <div class="col-sm-9 col-sm-offset-3">
					                  <button type="reset" class="btn btn-default">Reset</button>
					                  <button type="submit" class="btn btn-primary">Go</button>
					                </div>
					              </div>
					            </form>
					          </div>
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-cloud-download"></i> Download Template upload</h4>
					          </div>
					        </div>
					      </div>
					      <!-- /.tab-pane -->
					      <div class="tab-pane" id="tab_5">
					        <div class="row">
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-download"></i> Import Data Baku Mutu dari excel</h4>
					            <div id="messageBox5" class="alert" style="display: none"></div>
					            <form action="" id="baku_mutuupload" method="POST" class="form-horizontal" role="form">
					              <div class="form-group">
					                <label for="filebaku_mutu" class="control-label col-md-3">Pilih File</label>
					                <div class="col-md-6">
					                  <input type="file" class="form-control" name="filebaku_mutu" id="filebaku_mutu" required>
					                </div>
					              </div>
					              <div class="form-group">
					                <div class="col-sm-9 col-sm-offset-3">
					                  <button type="reset" class="btn btn-default">Reset</button>
					                  <button type="submit" class="btn btn-primary">Go</button>
					                </div>
					              </div>
					            </form>
					          </div>
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-cloud-download"></i> Download Template upload</h4>
					          </div>
					        </div>
					      </div>
					      <!-- /.tab-pane -->
					      <div class="tab-pane" id="tab_6">
					        <div class="row">
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-download"></i> Import data Konversi Limbah dari excel</h4>
					            <div id="messageBox6" class="alert" style="display: none"></div>
					            <form action="" id="konversi_limbahupload" method="POST" class="form-horizontal" role="form">
					              <div class="form-group">
					                <label for="filekonversi_limbah" class="control-label col-md-3">Pilih File</label>
					                <div class="col-md-6">
					                  <input type="file" class="form-control" name="filekonversi_limbah" id="filekonversi_limbah" required>
					                </div>
					              </div>
					              <div class="form-group">
					                <div class="col-sm-9 col-sm-offset-3">
					                  <button type="reset" class="btn btn-default">Reset</button>
					                  <button type="submit" class="btn btn-primary">Go</button>
					                </div>
					              </div>
					            </form>
					          </div>
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-cloud-download"></i> Download Template upload</h4>
					          </div>
					        </div>
					      </div>
					      <!-- /.tab-pane -->
					      <div class="tab-pane" id="tab_7">
					        <div class="row">
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-download"></i> Import Data Air Limbah Harian dari excel</h4>
					            <div id="messageBox7" class="alert" style="display: none"></div>
					            <form action="" id="airlimbah_harianupload" method="POST" class="form-horizontal" role="form">
					              <div class="form-group">
					                <label for="fileairlimbah_harian" class="control-label col-md-3">Pilih File</label>
					                <div class="col-md-6">
					                  <input type="file" class="form-control" name="fileairlimbah_harian" id="fileairlimbah_harian" required>
					                </div>
					              </div>
					              <div class="form-group">
					                <div class="col-sm-9 col-sm-offset-3">
					                  <button type="reset" class="btn btn-default">Reset</button>
					                  <button type="submit" class="btn btn-primary">Go</button>
					                </div>
					              </div>
					            </form>
					          </div>
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-cloud-download"></i> Download Template upload</h4>
					          </div>
					        </div>
					      </div>
					      <!-- /.tab-pane -->
					      <div class="tab-pane" id="tab_8">
					        <div class="row">
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-download"></i> Import Data Air Limbah Bulanan dari excel</h4>
					            <div id="messageBox8" class="alert" style="display: none"></div>
					            <form action="" id="airlimbah_bulananupload" method="POST" class="form-horizontal" role="form">
					              <div class="form-group">
					                <label for="fileairlimbah_bulanan" class="control-label col-md-3">Pilih File</label>
					                <div class="col-md-6">
					                  <input type="file" class="form-control" name="fileairlimbah_bulanan" id="fileairlimbah_bulanan" required>
					                </div>
					              </div>
					              <div class="form-group">
					                <div class="col-sm-9 col-sm-offset-3">
					                  <button type="reset" class="btn btn-default">Reset</button>
					                  <button type="submit" class="btn btn-primary">Go</button>
					                </div>
					              </div>
					            </form>
					          </div>
					          <div class="col-md-6">
					            <h4 class="page-header"><i class="fa fa-cloud-download"></i> Download Template upload</h4>
					          </div>
					        </div>
					      </div>
					      
					      <!-- /.tab-pane -->
					    </div>
					    <!-- /.tab-content -->
		            
			        </div>
				</div>
			</div>
		</div>

	</section>
</div>

<?php $this->load->view('footer') ?>
<!-- <script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script> -->
<!-- <script src="<?php echo base_url() ?>assets/apps/js/she/import_data/import_data.js"></script> -->
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<style>
.small-box .icon{
    top: -13px;
}
</style>
