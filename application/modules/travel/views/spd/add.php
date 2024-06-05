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
        border-radius: 8px;
        cursor: pointer;
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

    .select2-selection {
        overflow: hidden !important;
    }

    .select2-selection__rendered {
        white-space: normal !important;
        word-break: break-all !important;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <form id="form-pengajuan" class="form-pengajuan">
                    <div class="nav-tabs-custom">

                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_pengajuan" data-toggle="tab" aria-expanded="true">
                                    <span><i class="fa fa-briefcase colors-tosca"></i></span>
                                    Pengajuan
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab_aktifitas" data-toggle="tab" aria-expanded="false">
                                    <span><i class="fa fa-list-alt colors-purple"></i></span>
                                    Aktifitas
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab_uang_muka" data-toggle="tab" aria-expanded="false">
                                    <span><i class="fa fa-money colors-green"></i></span>
                                    Uang Muka
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" style="min-height: 300px;">

                            <!-- tab pengajuan -->
                            <div class="tab-pane active" id="tab_pengajuan">
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
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="validate-select">No Handphone</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-phone colors-peach"></span></span>
                                                <input type="number" class="form-control" name="no_hp" id="no_hp" placeholder="Masukkkan No Handphone" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 hide" id="div_kembali">
                                        <div class="form-group">
                                            <label for="validate-select">Tanggal Kembali</label>
                                            <div class="input-group date dt_end trip_end_datetime_multi">
                                                <span class="input-group-addon"><span class="fa fa-calendar colors-tosca"></span></span>
                                                <input type="text" data-date-format="DD.MM.YYYY HH:mm:ss" class="form-control" name="detail_end" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <input type="hidden" name='tipe_trip' value='single'>
                                        <input type="radio" id="single_trip" value="single" class="iradio_flat-blue"> <label style="text-transform: uppercase; padding-left: 10px;"> Single - Trip </label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="radio" id="multi_trip" value="multi" class="iradio_flat-blue"> <label style="text-transform: uppercase; padding-left: 10px;"> Multi - Trip </label>
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
                            </div>
                            <!-- tab aktifitas -->
                            <div class="tab-pane" id="tab_aktifitas">
                                <p>Rencana Aktifitas:</p>
                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-sm bg-gradient-secondary" name="add_aktifitas"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <br> -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width:20%;">Tanggal</th>
                                                    <th style="width:25%;">Pabrik/Kota</th>
                                                    <th>Aktifitas</th>
                                                    <!-- <th style="width:5%;"></th> -->
                                                </tr>
                                            </thead>
                                            <tbody id="list-aktifitas">
                                                <!-- <tr class="row-aktifitas">
                                                    <td>
                                                        <div class="input-group ">
                                                            <div class="input-group-addon"><i class="fa fa-calendar colors-tosca"></i></div>
                                                            <input type="text" data-date-format="dd.mm.yyyy" class="form-control date-aktifitas" name="tanggal_aktifitas_add[]" required>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control"  name="pabrik_aktifitas_add[]" autocomplete="off" required></td>
                                                    <td><input type="text" class="form-control" name="aktifitas_add[]" required></td>
                                                    <td></td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- tab uang muka -->
                            <div class="tab-pane" id="tab_uang_muka">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label style="font-weight:700 !important;">Uang Muka</label>
                                            <div>
                                                <div class="input-group">
                                                    <span class="input-group-addon">IDR</span>
                                                    <input readonly type="text" name="total_um" id="total_um" class="form-control text-right numeric" numeric-min="0" value="0">
                                                    <span class="input-group-addon"><span class="fa fa-money colors-green"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="div-uangmuka" class="hide">
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <label for="validate-select">Detail Uang Muka</label>
                                            <script type="text/template" id="uangmuka_template">
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="uangmuka[{no}][id]" class="uangmuka-id">
                                                        <input type="hidden" name="uangmuka[{no}][fk]" class="uangmuka-fk">
                                                        <input type="hidden" name="uangmuka[{no}][kode_expense]" class="uangmuka-kode_expense">
                                                        <input type="hidden" name="uangmuka[{no}][rate]" class="uangmuka-rate">
                                                        <p class="uangmuka-label-expense"></p>
                                                    </td>
                                                    <td align="right">
                                                        <p class="uangmuka-label-rate numeric-label"></p>
                                                    </td>
                                                    <td align="center">
                                                        <input type="hidden" name="uangmuka[{no}][durasi]" class="uangmuka-durasi">
                                                        <p class="uangmuka-label-durasi"></p>
                                                    </td>
                                                    <td align="center">
                                                        
                                                        <div class="input-group" style="border-left:1px solid lightgray; width:100%;">
                                                            <input type="hidden" name="uangmuka[{no}][currency]" class="uangmuka-currency">
                                                            <span class="input-group-addon">IDR</span>
                                                            <input id="uangmuka_{no}_jumlah" name="uangmuka[{no}][jumlah]" numeric-min="0" class="form-control uangmuka-jumlah text-right numeric">
                                                            <!-- <span class="input-group-addon"><span class="fa fa-money colors-green"></span></span> -->
                                                        </div>
                                                        

                                                    </td>
                                                </tr>
                                            </script>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <table id="table-uangmuka" class="table table-responsive table-bordered table-striped table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th width="">Jenis Biaya</th>
                                                        <th width="10%">Rate</th>
                                                        <th width="5%">Durasi (hari)</th>
                                                        <th width="30%">Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label style="font-weight:700 !important;">Sisa</label>
                                                <div>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">IDR</span>
                                                        <input type="text" class="form-control text-right numeric" numeric-leftover="0" value="0" name="sisa_um" id="sisa_um" disabled readonly>
                                                        <span class="input-group-addon"><span class="fa fa-money colors-green"></span></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="button" id="sbmit" value="submit" class="btn btn-success pull-right">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
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

<script>
    let tanggal_travels = <?php echo json_encode($tanggal_travels) ?>;
    let tanggal_travels_sorted = <?php echo json_encode($tanggal_travels) ?>;
    $.each(tanggal_travels, function(i, v) {
        tanggal_travels[i] = moment(v, 'DD/MM/YYYY');
    });

    let input_max_length = <?php echo TR_INPUT_MAXLENGTH ?>;
    let backdated_max = <?php echo TR_BACKDATED_DAYS_MAX ?>;

    tanggal_travels_sorted.sort(function(a, b) {
        var aa = a.split('/').reverse().join(),
            bb = b.split('/').reverse().join();
        return aa < bb ? -1 : (aa > bb ? 1 : 0);
    });

    $.each(tanggal_travels_sorted, function(i, v) {
        tanggal_travels_sorted[i] = moment(v, 'DD/MM/YYYY');
    });

    $(document).ready(function() {
        $('input').iCheck({
            checkboxClass: 'icheckbox_flat',
            radioClass: 'iradio_flat'
        });

    });
</script>

<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>

<!-- Plugin ini bikin burger icon sidebar gabisa di kliks -->
<!-- <script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.js"></script> -->

<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/add.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_pembatalan.js"></script>