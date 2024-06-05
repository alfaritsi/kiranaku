<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css" />
<style type="text/css">
    .help1 {
        position: fixed;
        right: 16px;
        bottom: 56px;
        max-width: 250px;
        z-index: 21;
    }

    .help2 {
        position: relative;
        z-index: 10;
    }

    .help1 .help2 {
        display: block;
        background-color: #008d4c;
        padding: 12px 16px;
        line-height: 24px;
        font-size: 16px;
        -webkit-box-shadow: 0 4px 8px 0 rgba(27, 27, 27, .2), 0 16px 24px 0 rgba(27, 27, 27, .2);
        box-shadow: 0 4px 8px 0 rgba(27, 27, 27, .2), 0 16px 24px 0 rgba(27, 27, 27, .2);
        color: #fff;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 700;
    }

    .description-text {
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

    .whitesmoke {
        background-color: whitesmoke;
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

    .colors-green {
        color: #20c997;
    }

    .colors-purple {
        color: rgb(147, 22, 130);
    }

    .colors-orange1 {
        color: rgb(252, 160, 0);
    }

    .colors-orange2 {
        color: rgb(226, 88, 35);
    }

    .colors-tosca {
        color: #087E8B;
    }

    .colors-peach {
        color: rgb(255, 109, 112);
    }

    .clickable {
        cursor: pointer;
    }

    .panel-heading span {
        margin-top: -20px;
        font-size: 15px;
    }

    .form-group label {
        font-weight: 100 !important;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal form-persetujuan">
                    <input type="hidden" name="id_travel_header" id="id_travel_header" value='<?php echo $id_travel_header; ?>'>
                    <input type="hidden" name="id_travel_cancel" id="id_travel_cancel">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_pengajuan" data-toggle="tab" aria-expanded="true">
                                    <span><i class="fa fa-briefcase colors-tosca"></i></span>
                                    Detail Pengajuan
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab_uang_muka" data-toggle="tab" aria-expanded="false">
                                    <span><i class="fa fa-money colors-green"></i></span>
                                    Uang Muka
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- tab pengajuan -->
                            <div class="tab-pane active" id="tab_pengajuan">
                                <?php $this->load->view('pembatalan/_tab_modal_spd_pembatalan_pengajuan') ?>
                            </div>
                            <!-- tab uang muka -->
                            <div class="tab-pane" id="tab_uang_muka">
                                <?php $this->load->view('pembatalan/_tab_modal_spd_pembatalan_uangmuka') ?>
                            </div>
                        </div>

                        <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn margin">
                            <legend class="no-pad-top">
                                <h4>Pembatalan</h4>
                            </legend>
                            <div class="col-md-12">
                                <div class="hide" id="div-um-kembali">
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="label_p_nama">Jumlah Uang Muka</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static no-padding text-right" id="label_total_um">
                                                    <span class="label_total_um_jumlah numeric-label">0</span>
                                                    <span class="label_total_um_currency"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="jumlah_kembali">Jumlah dikembalikan</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input type="text" name="jumlah_kembali" id="jumlah_kembali" class="form-control text-right numeric" numeric-min="0" value="0" placeholder="0">
                                                    <span class="input-group-addon label_total_um_currency"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="lampiran">Lampiran bukti</label>
                                            <div class="col-md-8">
                                                <input type="file" name="lampiran" id="lampiran" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="lampiran">Batalkan uang muka saja</label>
                                            <div class="col-md-8">
                                                <input type="hidden" name="batal_um_only" value="0">
                                                <input class="icheck" type="checkbox" name="batal_um_only" id="batal_um" value="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group no-padding">
                                        <label class="col-md-4" for="catatan">Catatan</label>
                                        <div class="col-md-8">
                                            <textarea name="catatan" id="catatan" required class="form-control" rows="4" placeholder="Ketik catatan"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="box-footer">
                            <button type="button" class="btn btn-approval btn-success" name="simpan_btn_pembatalan">Simpan</button>
                            <button name="back_btn_deklarasi" id="back_btn_deklarasi" class="btn btn-warning " type="button">Kembali</button>
                        </div>
                    </div>

                </form>
            </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/travel/spd_global.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css" />

<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>

<!-- Plugin ini bikin burger icon sidebar gabisa di klik -->
<!-- <script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.js"></script> -->

<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>

<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/cancel_pengajuan.js?<?php echo time(); ?>"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_pembatalan.js?<?php echo time(); ?>"></script> -->