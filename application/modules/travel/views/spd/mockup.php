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
    font-weight: 700;
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

</style>

<div class="content-wrapper">
    <section class="content">
        <div class="box box-widget widget-user">
            <div class="box-header">
              <div class="row">
                <div class="col-sm-3 border-right">
                  <div class="description-block clickable whitesmoke" id="head_pengajuan">
                    <h5 class="description-header">
                        <span class="fa-stack fa-lg">
                            <!-- <i style="color:#008d4c;" class="fa fa-circle fa-stack-2x"></i> -->
                            <i class="fa fa-plus fa-stack-2x colors-tosca"></i>
                        </span>
                    </h5>
                    <span class="description-text">Pengajuan</span>
                  </div>
                </div>
                <div class="col-sm-3 border-right">
                  <div class="description-block clickable" id="head_uang_muka">
                    <h5 class="description-header">
                        <span class="fa-stack fa-lg">
                            <!-- <i style="color:#008d4c;" class="fa fa-circle fa-stack-2x"></i> -->
                            <i class="fa fa-money fa-stack-2x colors-green"></i>
                        </span>
                    </h5>
                    <span class="description-text">Uang Muka</span>
                  </div>
                </div>
                <div class="col-sm-3 border-right" style="pointer-events:none;">
                  <div class="description-block clickable" id="head_transportasi">
                  <h5 class="description-header">
                        <span class="fa-stack fa-lg">
                            <!-- <i style="color:#008d4c;" class="fa fa-circle fa-stack-2x"></i> -->
                            <i class="fa fa-plane fa-stack-2x colors-peach"></i>
                        </span>
                    </h5>
                    <span class="description-text">Transportasi</span>
                  </div>
                </div>
                <div class="col-sm-3" style="pointer-events:none;">
                  <div class="description-block clickable" id="head_history">
                  <h5 class="description-header">
                        <span class="fa-stack fa-lg">
                            <!-- <i style="color:#008d4c;" class="fa fa-circle fa-stack-2x"></i> -->
                            <i class="fa fa-history fa-stack-2x colors-orange2"></i>
                        </span>
                    </h5>
                    <span class="description-text">History</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-body" id="tab_pengajuan">
            <form id="form-pengajuan">
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="validate-select">Aktifitas</label>
                    <div class="input-group">
                      <span class="input-group-addon"><span class="fa fa-list-alt colors-purple"></span></span>
                      <select class="form-control" name="activity" id="activity" required>
                      </select>
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <label for="validate-select">NO HANDPHONE</label>
                    <div class="input-group">
                      <span class="input-group-addon custom"><span class="fa fa-phone"></span></span>
                      <input type="number" class="form-control" name="" id="" placeholder="Masukkkan No Handphone">
                    </div>
                  </div> -->
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="validate-select">No Handphone</label>
                    <div class="input-group">
                      <span class="input-group-addon"><span class="fa fa-phone colors-peach"></span></span>
                      <input type="number" class="form-control" name="no_hp" id="no_hp" placeholder="Masukkkan No Handphone">
                    </div>
                  </div>
                </div>
                <div class="col-sm-3" id="div_kembali">
                  <div class="form-group">
                    <label for="validate-select">Tanggal Kembali</label>    
                    <div class="input-group date dt_end trip_end_datetime_multi" >
                        <span class="input-group-addon"><span class="fa fa-calendar colors-tosca"></span></span>
                        <input type="text" data-date-format="DD-MM-YYYY HH:mm:ss" class="form-control" name="detail_end">
                    </div>                    
                  </div>
                </div>
                <!-- <div class="col-sm-3">
                  <div class="form-group">
                    
                    <i class="fa fa-phone fa-2x colors-green"></i>
                    <i class="fa fa-phone fa-2x colors-orange1"></i>
                    <i class="fa fa-phone fa-2x colors-orange2"></i>
                    <i class="fa fa-phone fa-2x colors-purple"></i>
                    <i class="fa fa-phone fa-2x colors-peach"></i>
                    <i class="fa fa-phone fa-2x colors-tosca"></i>
                  </div>
                </div> -->
              </div>
              <div class="row">
                <!-- <div class = "col-sm-7"></div> -->
                <div class="col-sm-2">
                  <input type="hidden" name='tipe_trip' value='single'>
                  <input type="radio" id="single_trip" value="single" class="iradio_flat-blue"> <label style="text-transform: uppercase;
                  padding-left: 10px;"> Single - Trip </label>
                </div>
                <div class="col-sm-2">
                  <input type="radio" id="multi_trip" value="multi" class="iradio_flat-blue"> <label style="text-transform: uppercase;
                  padding-left: 10px;"> Multi - Trip </label>
                </div>
              </div>
              <br>

              <div class="" id="pengajuan_single">
                
              </div>

              <div class="hidden" id="pengajuan_multi">
                <div class="form_multi" id="form_multi">
                  
                </div>

                <div class="row row_navigate">
                  <div class="col-sm-2 clickable" id="tambah_trip">
                    <span class="fa-stack">
                        <i style="color:#008d4c;" class="fa fa-circle fa-stack-2x"></i>
                        <i style="color:white;" class="fa fa-plus fa-stack-1x"></i>
                    </span>
                    <label class="clickable" style="color:#008d4c;">Tambah Perjalanan</label>
                  </div>
                  <div class="col-sm-2 clickable hidden" id="hapus_trip">
                    <span class="fa-stack">
                        <i style="color:#d00101;" class="fa fa-circle fa-stack-2x"></i>
                        <i style="color:white;" class="fa fa-minus fa-stack-1x"></i>
                    </span>
                    <label class="clickable" style="color:#d00101;">Hapus Perjalanan</label>
                  </div>
                </div>

              </div>
              <!-- </form> -->
            </div>

            <div class="box-body hidden" id="tab_uang_muka">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="validate-select">UANG MUKA</label>
                    <div class="input-group">
                      <span class="input-group-addon">IDR</span>
                      <input type="number" class="form-control" name="total_um" id="total_um" placeholder="Masukkkan Uang Muka" required>
                      <span class="input-group-addon"><span class="fa fa-money colors-green"></span></span>
                    </div>
                  </div>
                </div>
              </div>
              </form>
            </div>
            <div class="box-body hidden" id="tab_transportasi"></div>
            <div class="box-body hidden" id="tab_history"></div>

            <div class="box-footer">
              <button type="button" id="sbmit" value="submit" style="margin-left: 50%;" class="btn btn-success">Submit</button>
            </div>
        </div>
        <div id="help" class="help1">
          <a href="#" class="help2"><i style="color:white;" class="fa fa-question-circle"></i> Need help?</a>
        </div>
    </section>
</div>

<?php $this->load->view('pengajuan/_modal_spd_pengajuan', compact('approval', 'penginapan')) ?>
<?php $this->load->view('pengajuan/_modal_spd_um_tambah', compact('approval')) ?>
<?php $this->load->view('pembatalan/_modal_spd_pembatalan') ?>
<?php echo $modal_detail . $modal_chat . $modal_tujuan . $modal_history; ?>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/travel/spd_global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>

<script>
    let tanggal_travels = <?php echo json_encode($tanggal_travels)?>;
    $.each(tanggal_travels, function(i,v){
       tanggal_travels[i] = moment(v, 'DD/MM/YYYY');
    });
    let input_max_length = <?php echo TR_INPUT_MAXLENGTH ?>;
    let backdated_max = <?php echo TR_BACKDATED_DAYS_MAX?>;

  
    $(document).ready(function(){
      $('input').iCheck({
        checkboxClass: 'icheckbox_flat',
        radioClass: 'iradio_flat'
      });

    });

</script>

<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>

<!-- Plugin ini bikin burger icon sidebar gabisa di klik -->
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.js"></script>

<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_pengajuan.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_pembatalan.js"></script>

