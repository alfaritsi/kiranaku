<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css" />
<!-- for attchment -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css" />

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
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Deklarasi Perjalanan Dinas</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
                            <legend class="no-pad-top">
                                <h4>Personal</h4>
                            </legend>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="validate-select">No Trip</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-keyboard-o"></span></span>
                                            <input type="number" class="form-control" name="no_trip" id="no_trip" placeholder="Masukkkan No Trip" disabled>
                                        </div>
                                    </div>
                                </div>
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
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="validate-select">Tanggal Keberangkatan</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar colors-peach"></span></span>
                                            <input type="text" data-date-format="DD-MM-YYYY" class="form-control" name="start_date_label" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="validate-select">Tanggal Kembali</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-calendar colors-peach"></span></span>
                                            <input type="text" data-date-format="DD-MM-YYYY" class="form-control" name="end_date_label" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="validate-select">Durasi</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-clock-o colors-purple"></span></span>
                                            <input type="text" class="form-control" name="durasi_label" id="durasi_label" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form id="form-edit-deklarasi" class="form-edit-deklarasi">
                    <input type="hidden" name='idDeklarasiHeader'>
                    <input type="hidden" name='id_travel_deklarasi_header' value="<?php echo $id_header ?>">
                    <input type="hidden" name='id_header' value="<?php echo $id_header ?>">
                    <input type="hidden" name='id_travel_header' value="<?php echo $id_header ?>">
                    <input type="hidden" name='no_hp'>
                    <input type="hidden" name='status_transportasi'>
                    <input type="hidden" name='is_approval_hr' value="<?php echo (($flagApproval == 'true' && $pengajuan_deklarasi->deklarasi_approval_level == '3') ? 1 : 0); ?>">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_deklarasi" data-toggle="tab" aria-expanded="true">
                                    <span><i class="fa  fa-sticky-note-o colors-tosca"></i></span>
                                    Deklarasi
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab_biaya" data-toggle="tab" aria-expanded="false">
                                    <span><i class="fa fa-money colors-green"></i></span>
                                    Biaya
                                </a>
                            </li>
                            <?php if ($personel->ho == 'y') { ?>
                                <li class="">
                                    <a href="#tab_cuti" data-toggle="tab" aria-expanded="false">
                                        <span><i class="fa fa-list-alt colors-purple"></i></span>
                                        Cuti Pengganti
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="">
                                <a href="#tab_history" data-toggle="tab" aria-expanded="false">
                                    <span><i class="fa fa-history colors-orange2"></i></span>
                                    History
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" style="min-height: 300px;">
                            <!-- tab deklarasi -->
                            <div class="tab-pane active" id="tab_deklarasi">
                                <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn hide">
                                    <legend class="no-pad-top">
                                        <h4>Detail Pengajuan</h4>
                                    </legend>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="validate-select">Aktifitas</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-list-alt colors-purple"></span></span>
                                                    <input type="text" class="form-control" name="activity_label" id="activity_label" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="validate-select">No Handphone</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-phone colors-peach"></span></span>
                                                    <input type="number" class="form-control" name="no_hp" id="no_hp" placeholder="Masukkkan No Handphone" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <div class="form-group">
                                                <label for="validate-select">Tanggal Kembali</label>
                                                <div class="input-group date dt_end trip_end_datetime_multi">
                                                    <span class="input-group-addon"><span class="fa fa-calendar colors-tosca"></span></span>
                                                    <input type="text" data-date-format="DD-MM-YYYY HH:mm:ss" class="form-control" name="detail_end" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="validate-select">No Trip</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-keyboard-o"></span></span>
                                                    <input type="number" class="form-control" name="no_trip" id="no_trip" placeholder="Masukkkan No Trip" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <input type="hidden" name='tipe_trip' value='single'>
                                            <input type="radio" value="single" class="iradio_flat-blue" id="single_trip_disabled" disabled> <label style="text-transform: uppercase; padding-left: 10px;"> Single - Trip </label>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="radio" value="multi" class="iradio_flat-blue" id="multi_trip_disabled" disabled> <label style="text-transform: uppercase; padding-left: 10px;"> Multi - Trip </label>
                                        </div>
                                    </div>
                                    <br>

                                    <div class="" id="pengajuan_single_disabled">
                                    </div>

                                    <div class="hidden" id="pengajuan_multi_disabled">
                                    </div>
                                </fieldset>

                                <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
                                    <legend class="no-pad-top">
                                        <h4>Deklarasi</h4>
                                    </legend>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="validate-select">Aktifitas</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-list-alt colors-purple"></span></span>
                                                    <select class="form-control" name="activity" id="activity" disabled>
                                                        <?php foreach ($jenis_aktifitas as $ja) : ?>
                                                            <option value="<?php echo $ja->kode_jns_aktifitas ?>"><?php echo $ja->jenis_aktifitas ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="validate-select">Tanggal Deklarasi</label>
                                                <div class="input-group date dt_end trip_end_datetime_multi">
                                                    <span class="input-group-addon"><span class="fa fa-calendar colors-peach"></span></span>
                                                    <input type="text" data-date-format="DD-MM-YYYY HH:mm:ss" class="form-control" name="tanggal_deklarasi" value="<?php echo date('d-m-Y H:i:s'); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 hide" id="div_kembali">
                                            <div class="form-group">
                                                <label for="validate-select">Tanggal Kembali</label>
                                                <div class="input-group date dt_end trip_end_datetime_multi">
                                                    <span class="input-group-addon"><span class="fa fa-calendar colors-tosca"></span></span>
                                                    <input type="text" data-date-format="DD-MM-YYYY HH:mm:ss" class="form-control" name="detail_end" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="validate-select">No Trip</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-keyboard-o"></span></span>
                                                    <input type="number" class="form-control" name="no_trip" id="no_trip" placeholder="Masukkkan No Trip" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- <div class="col-sm-2">
                                            <input type="hidden" name='tipe_trip' value='single'>
                                            <input type="radio" id="single_trip" value="single" class="iradio_flat-blue" disabled> <label style="text-transform: uppercase; padding-left: 10px;"> Single - Trip </label>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="radio" id="multi_trip" value="multi" class="iradio_flat-blue multi_trip" disabled> <label style="text-transform: uppercase; padding-left: 10px;"> Multi - Trip </label>
                                        </div> -->
                                        <div class="col-sm-12">
                                            <label id="jenis-trip" style="padding-left: 10px;"></label>
                                        </div>
                                    </div>
                                    <!-- <br> -->
                                    <div id="list-trip"></div>

                                    <!-- <div class="" id="pengajuan_single">

                                    </div>

                                    <div class="hidden" id="pengajuan_multi">
                                        <div class="form_multi" id="form_multi">
                                        </div>

                                        <div class="row row_navigate hide">
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
                                    </div> -->
                                </fieldset>
                            </div>
                            <!-- tab biaya -->
                            <div class="tab-pane" id="tab_biaya">
                                <fieldset class="fieldset-warning fieldset-4px-rad">
                                    <legend>
                                        <h4>Biaya</h4>
                                    </legend>
                                    <script type="text/template" id="biaya_template">
                                        <tr class="template-trip">
                                            <td>
                                                <div class="input-group biaya_tanggal">
                                                    <input name="biaya[{no}][tanggal]" type="text"
                                                        placeholder="Pilih tanggal"
                                                        disabled class="form-control">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="hidden" name="biaya[{no}][id]" class="deklarasi-detail-id">
                                                <select name="biaya[{no}][biaya]"
                                                        disabled
                                                        class="select2 select-biaya form-control"
                                                        data-placeholder="Pilih Jenis Biaya">
                                                </select>
                                            </td>
                                            <td>
                                                <input name="biaya[{no}][keterangan]" type="text" class="form-control biaya-keterangan" style="width: 100%;" disabled >
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input id="biaya_{no}_jumlah"
                                                        disabled value="0"
                                                        name="biaya[{no}][jumlah]" numeric-min="0"
                                                        class="form-control biaya-jumlah text-right numeric">
                                                </div>
                                            </td>
                                            <td>
                                                <select name="biaya[{no}][currency]"
                                                        disabled
                                                        class="select2 select-currency form-control"
                                                        data-placeholder="Pilih Mata uang">
                                                </select>
                                            </td>
                                            <td>
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="btn-group btn-sm no-padding">
                                                        <a class="btn btn-default btn-sm fileinput-exists fileinput-zoom"target="_blank" data-fancybox="gallery"><i class="fa fa-search"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button"
                                                        class="btn hide btn-sm btn-flat btn-danger btn-block biaya_delete" disabled>
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </script>
                                    <div class="row hidden">
                                        <div class="col-md-4">
                                            <button id="biaya_add_btn" class="btn btn-default" type="button"><i class="fa fa-plus"></i> Tambah Biaya
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="table-biaya" class="table table-bordered table-striped table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th width="15%">Tanggal</th>
                                                        <th width="25%">Biaya</th>
                                                        <th width="25%">Keterangan</th>
                                                        <th width="15%">Jumlah</th>
                                                        <th width="12%">Mata uang</th>
                                                        <th width="5%"><i class="fa fa-image"></i></th>
                                                        <th width="5%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <hr />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="total_biaya">Jumlah Biaya</label>
                                                <div class="col-md-8">
                                                    <input id="total_biaya" readonly name="total_biaya" type="text" class="form-control biaya-jumlah text-right numeric" value="0">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="total_um">Uang muka</label>
                                                <div class="col-md-8">
                                                    <input id="total_um" name="total_um" type="text" class="form-control numeric text-right angka" value="0" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="total_bayar">Dibayarkan</label>
                                                <div class="col-md-8">
                                                    <input id="total_bayar" readonly name="total_bayar" type="text" numeric-total-min="0" class="form-control numeric text-right" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <!-- tab cuti -->
                            <?php if ($personel->ho == 'y') { ?>
                                <div class="tab-pane" id="tab_cuti">
                                    <fieldset class="fieldset-warning fieldset-4px-rad">
                                        <legend>
                                            <h4>Cuti Pengganti</h4>
                                        </legend>

                                        <!-- <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-sm btn-default" name="add_cuti"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <br> -->
                                        <div class="row">
                                            <div class="col-md-6 col-sm-12">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:45%;">Tanggal</th>
                                                            <th>Keterangan</th>
                                                            <th style="width:8%;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="list-cuti">
                                                        <tr>
                                                            <td colspan="3" id="no-data-cuti">No Data</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            <?php } ?>
                            <!-- tab history -->
                            <div class="tab-pane" id="tab_history">
                                <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
                                    <legend class="no-pad-top">
                                        <h4>Timeline</h4>
                                    </legend>
                                    <div class="header">
                                    </div>
                                    <div class="body no-padding">
                                        <script type="text/template" id="history_spd_template">
                                            <tr class="template-trip">
                                                <td width="10%" align="center">
                                                    <p class="form-control-static no-padding label_tanggal"></p>
                                                </td>
                                                <td width="30%">
                                                    <p class="form-control-static no-padding label_action"></p>
                                                </td>
                                                <td width="15%">
                                                    <p class="form-control-static no-padding label_remark"></p>
                                                </td>
                                                <td width="30%">
                                                    <p class="form-control-static no-padding label_comment"></p>
                                                </td>
                                                <td width="15%" align="center">
                                                    <p class="form-control-static no-padding label_by"></p>
                                                </td>
                                            </tr>
                                        </script>
                                        <script type="text/template" id="history_spd_template_timeline">
                                            <li class="time-label">
                                                <span class="bg-blue span_tgl" >
                                                    10 Feb. 2014
                                                </span>
                                            </li>
                                            <li>
                                                <i class="" id="icon_action{no}"></i>
                                                <div class="timeline-item">
                                                    <span class="time span_jam"><i class="fa fa-clock-o"></i> 12:05</span>

                                                    <h3 class="timeline-header action_his">Pengajuan SPD</h3>

                                                    <div class="timeline-body action_by">
                                                        Dilakukan oleh Tom Cruise [127]
                                                    </div>

                                                    <div class="timeline-footer">
                                                    
                                                    </div>
                                                </div>
                                            </li>
                                        </script>
                                        <ul class="timeline tm_his">

                                        </ul>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="box-footer">
                            <?php if ($flagApproval == 'true' || $flagApprovalBy == 'true') {
                                // echo json_encode($pengajuan->no_trip);
                            ?>

                                <?php if ($pengajuan->approval_level == '4' && $pengajuan->approval_status == '1' && $pengajuan->no_trip !== null) { ?>
                                <?php } else { ?>
                                    <button value="revise" type="button" name="action_btn" class="btn action_btn btn-warning">Ask to revise</button>
                                    <button value="approve" type="button" name="action_btn" class="btn action_btn btn-success">Disetujui</button>
                                    <button value="disapprove" type="button" name="action_btn" class="btn action_btn btn-danger">Ditolak</button>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css" />
<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" /> -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/travel/spd_global.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css" />

<script>
    let tanggal_travels = <?php echo json_encode($tanggal_travels) ?>;
    $.each(tanggal_travels, function(i, v) {
        tanggal_travels[i] = moment(v, 'DD/MM/YYYY');
    });
    let input_max_length = <?php echo TR_INPUT_MAXLENGTH ?>;
    let backdated_max = <?php echo TR_BACKDATED_DAYS_MAX ?>;


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
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/detail_deklarasi.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_pembatalan.js?<?php echo time(); ?>"></script>