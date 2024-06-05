<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css"/>
<style type="text/css">
.help1{
    position: fixed;
    right: 16px;
    bottom: 56px;
    max-width: 250px;
    z-index: 21;
}
.help2{
    position: relative;
    z-index: 10;
}
.help1 .help2{
    display: block;
    background-color: #008d4c;
    padding: 12px 16px;
    line-height: 24px;
    font-size: 16px;
    -webkit-box-shadow: 0 4px 8px 0 rgba(27,27,27,.2), 0 16px 24px 0 rgba(27,27,27,.2);
    box-shadow: 0 4px 8px 0 rgba(27,27,27,.2), 0 16px 24px 0 rgba(27,27,27,.2);
    color: #fff;
    border-radius: 50px;
    cursor: pointer;
    font-weight: 700;
}

.description-text{
    font-size: larger;
    /* font-weight: 700; */
    /* color: #008d4c; */
    text-transform: capitalize !important;

}

.widget-user .widget-user-header {
    padding: 20px;
    height: 30px;
    border-top-right-radius: 3px;
    border-top-left-radius: 3px;
}

.widget-user .widget-user-image {
    position: absolute;
    top: 7px;
    left: 51%;
    margin-left: -49px;
}

.widget-user .widget-user-image>img {
    width: 70px;
    height: auto;
    border: 3px solid #fff;
}

.whitesmoke{
  background-color:whitesmoke;
}

.input-group-addon.primary {
    color: rgb(255, 255, 255);
    background-color: rgb(50, 118, 177);
    border-color: rgb(40, 94, 142);
}
.input-group-addon.success {
    color: rgb(255, 255, 255);
    background-color: rgb(92, 184, 92);
    border-color: rgb(76, 174, 76);
}
.input-group-addon.info {
    color: rgb(255, 255, 255);
    background-color: rgb(57, 179, 215);
    border-color: rgb(38, 154, 188);
}
.input-group-addon.warning {
    color: rgb(255, 255, 255);
    background-color: rgb(240, 173, 78);
    border-color: rgb(238, 162, 54);
}
.input-group-addon.danger {
    color: rgb(255, 255, 255);
    background-color: rgb(217, 83, 79);
    border-color: rgb(212, 63, 58);
}

.input-group-addon.custom {
    color: #606971;
    background-color: #ffffff;
    border-color: #606971;
}

.custom {
    color: #606971;
    background-color: #ffffff;
    border-color: #606971;
}

.colors-green{
  color:#20c997;
}

.colors-purple{
  color: rgb(147, 22, 130);
}

.colors-orange1{
  color:rgb(252, 160, 0);
}

.colors-orange2{
  color:rgb(226, 88, 35);
}

.colors-tosca{
  color:#087E8B;
}

.colors-peach{
  color:rgb(255, 109, 112);
}

.clickable{
    cursor: pointer;   
}

.panel-heading span {
	margin-top: -20px;
	font-size: 15px;
}

.form-group label{
  font-weight: 100 !important;
}

</style>

<div class="content-wrapper">
    <section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong>Persetujuan Deklarasi</strong></h3>
	          		</div>
					<form class="form-persetujuan">
						<input type="hidden" name="id_travel_header" id="id_travel_header" value='<?php echo $id_travel_header;?>'>
						<input type="hidden" name="approval_type" value="pengajuan">
						<input type="hidden" name="is_approval_by" id="is_approval_by" value='<?php echo $is_approval_by;?>'>
						<!-- /.box-header -->
						<div class="box-body">
							<fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
								<legend class="no-pad-top"><h4>Personal</h4></legend>
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="validate-select">NIK</label>
											<div class="input-group">
											  <span class="input-group-addon"><span class="fa fa-keyboard-o colors-purple"></span></span>
											  <input type="text" class="form-control" name="nik_label" id="nik_label" disabled>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="validate-select">Nama</label>
											<div class="input-group">
											  <span class="input-group-addon"><span class="fa fa-user colors-peach"></span></span>
											  <input type="text" class="form-control" name="nama_label" id="nama_label" disabled>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="validate-select">Kantor/ Pabrik</label>
											<div class="input-group">
											  <span class="input-group-addon"><span class="fa fa-building-o colors-peach"></span></span>
											  <input type="text" class="form-control" name="kantor_label" id="kantor_label" disabled>
											</div>
										</div>
									</div>
								</div>							
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<label for="validate-select">Jabatan</label>
											<div class="input-group">
											  <span class="input-group-addon"><span class="fa fa-tags colors-purple"></span></span>
											  <input type="text" class="form-control" name="jabatan_label" id="jabatan_label" disabled>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="validate-select">Bagian</label>
											<div class="input-group">
											  <span class="input-group-addon"><span class="fa fa-users colors-peach"></span></span>
											  <input type="text" class="form-control" name="bagian_label" id="bagian_label" disabled>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="validate-select">No HP</label>
											<div class="input-group">
											  <span class="input-group-addon"><span class="fa fa-phone colors-peach"></span></span>
											  <input type="number" class="form-control" name="no_hp_label" id="no_hp_label" disabled>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
								<legend class="no-pad-top"><h4>Persetujuan</h4></legend>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="validate-select">Catatan</label>
											<div class="input-group">
												<textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Ketik catatan persetujuan" style="margin: 0px; width: 506px; height: 97px;"></textarea>
											</div>
										</div>
									</div>
								</div>							
							</fieldset>
						</div>
						<!--footer-->
						<div class="row">
							<div class="col-md-12">
								<div class="box-footer">
									<button data-action="revise" type="button" class="btn btn-approval btn-warning">Ask to revise</button>
									<button data-action="approve" type="button" class="btn btn-approval btn-success">Disetujui</button>
									<!--<button data-action="disapprove" type="button" class="btn btn-approval btn-danger">Ditolak</button>-->
									<button name="back_btn_deklarasi" id="back_btn_deklarasi" class="btn btn-danger " type="button" >Kembali</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	
        <div class="box box-widget widget-user">
            <div class="box-header">
              <div class="row">
                <div class="col-sm-3 border-right">
                  <div class="description-block clickable whitesmoke" id="head_pengajuan">
                    <h5 class="description-header">
                        <span class="fa-stack fa-lg">
                            <i class="fa fa-search fa-stack-2x colors-tosca"></i>
                        </span>
                    </h5>
                    <span class="description-text">Detail Biaya</span>
                  </div>
                </div>
                <div class="col-sm-3 border-right">
                  <div class="description-block clickable" id="head_deklarasi">
                    <h5 class="description-header">
                        <span class="fa-stack fa-lg">
                            <i class="fa fa-search fa-stack-2x colors-tosca"></i>
                        </span>
                    </h5>
                    <span class="description-text">Detail Perjalanan</span>
                  </div>
                </div>
                <div class="col-sm-3 border-right">
                  <div class="description-block clickable" id="head_uang_muka">
                    <h5 class="description-header">
                        <span class="fa-stack fa-lg">
                            <i class="fa fa-money fa-stack-2x colors-green"></i>
                        </span>
                    </h5>
                    <span class="description-text">Uang Muka</span>
                  </div>
                </div>
              </div>
            </div>
			<!--detail pengajuan-->
            <div class="box-body" id="tab_pengajuan">
				<?php $this->load->view('persetujuan/_tab_modal_spd_persetujuan_deklarasi') ?>
            </div>
			<!--detail pengajuan-->
            <div class="box-body hidden" id="tab_deklarasi">
				<?php $this->load->view('persetujuan/_tab_modal_spd_persetujuan_pengajuan') ?>
            </div>
			
			<!--detail uang muka-->
			<div class="box-body hidden" id="tab_uang_muka">
				<?php $this->load->view('persetujuan/_tab_modal_spd_persetujuan_uangmuka') ?>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/travel/spd_global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>

<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>

<!-- Plugin ini bikin burger icon sidebar gabisa di klik -->
<!-- <script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.js"></script> -->

<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>

<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/app_deklarasi.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_pembatalan.js"></script>

