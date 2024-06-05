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

    .select2-selection {
        overflow: hidden !important;
    }

    .select2-selection__rendered {
        white-space: normal !important;
        word-break: break-all !important;
    }

    .info-transport {
        border-radius: 3px;
        margin: 0 0 20px 0;
        padding: 3px 25px 10px 10px;
        border-left: 5px solid #eee;
        border-color: #00733e;
        border-bottom: 1px solid #eee;
    }

    .info-penginapan {
        border-radius: 3px;
        margin: 0 0 20px 0;
        padding: 3px 25px 10px 10px;
        border-left: 5px solid #eee;
        border-color: #0097bc;
        border-bottom: 1px solid #eee;
    }

    .info-penerimaan {
        border-radius: 3px;
        margin: 0 0 20px 0;
        padding: 3px 25px 10px 10px;
        border-left: 5px solid #eee;
        border-color: #357ca5;
        border-bottom: 1px solid #eee;
    }

    .capitalize {
        text-transform: capitalize;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <form id="form-pengajuan" class="form-pengajuan">
                    <input type="hidden" name='id_header' value="<?php echo $id_header ?>">
                    <input type="hidden" name='approval_type' value="pengajuan">
                    <input type="hidden" name='is_approval_by' value="<?php echo $flagApprovalBy ?>">
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
                            <li class="">
                                <a href="#tab_history" data-toggle="tab" aria-expanded="false">
                                    <span><i class="fa fa-history colors-orange2"></i></span>
                                    History
                                </a>
                            </li>
                            <li id="btn_tab_akomodasi" class="<?php echo ($pengajuan->no_trip && (!empty($list_transport) || !empty($list_penginapan))) ? '' : 'hidden'; ?>">
                                <a href="#tab_transportasi" data-toggle="tab" aria-expanded="false">
                                    <span><i class="fa fa-plane colors-peach"></i></span>
                                    Akomodasi
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" style="min-height: 300px;">
                            <!-- tab pengajuan -->
                            <div class="tab-pane active" id="tab_pengajuan">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="validate-select">Aktifitas</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-list-alt colors-purple"></span></span>
                                                <select class="form-control readonly" name="activity" id="activity" disabled>
                                                    <?php foreach ($jenis_aktifitas as $ja) : ?>
                                                        <option value="<?php echo $ja->kode_jns_aktifitas ?>"><?php echo $ja->jenis_aktifitas ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="validate-select">No Handphone</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><span class="fa fa-phone colors-peach"></span></span>
                                                <input type="number" class="form-control" name="no_hp" id="no_hp" placeholder="Masukkkan No Handphone" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="div_kembali">
                                        <div class="form-group">
                                            <label for="validate-select">Tanggal Kembali</label>
                                            <div class="input-group date dt_end trip_end_datetime_multi">
                                                <span class="input-group-addon"><span class="fa fa-calendar colors-tosca"></span></span>
                                                <input type="text" data-date-format="DD.MM.YYYY HH:mm:ss" class="form-control" name="detail_end" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- <div class="col-sm-2">
                                        <input type="hidden" name='tipe_trip' value='single'>
                                        <input type="radio" id="single_trip" value="single" class="iradio_flat-blue"> <label style="text-transform: uppercase; padding-left: 10px;"> Single - Trip </label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="radio" id="multi_trip" value="multi" class="iradio_flat-blue"> <label style="text-transform: uppercase; padding-left: 10px;"> Multi - Trip </label>
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

                                    <div class="row row_navigate hidden">
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
                            </div>
                            <!-- tab aktifitas -->
                            <div class="tab-pane" id="tab_aktifitas">
                                <p>Rencana Aktifitas:</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width:20%;">Tanggal</th>
                                                    <th style="width:25%;">Pabrik/Kota</th>
                                                    <th>Aktifitas</th>
                                                </tr>
                                            </thead>
                                            <tbody id="list-aktifitas"></tbody>
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
                                                    <td align="right">
                                                    <p class="uangmuka-label-jumlah numeric-label"></p>                                
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
                            <!-- tab history -->
                            <div class="tab-pane" id="tab_history">
                                <!-- =========== -->
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
                                                    <!-- <span class="time span_jam"><i class="fa fa-clock-o"></i> 12:05</span> -->

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




                                <!-- ======================= -->
                            </div>
                            <!-- tab transportasi -->
                            <div class="tab-pane" id="tab_transportasi">
                                <!-- Transport -->
                                <div class="">
                                    <?php
                                    foreach ($list_transport as $dt) {
                                        switch ($dt->jenis_kendaraan) {
                                            case 'pesawat':
                                                $icon = 'fa fa-plane';
                                                break;
                                            case 'taxi':
                                                $icon = 'fa fa-taxi';
                                                break;

                                            default:
                                                $icon = 'fa-briefcase';
                                                break;
                                        }
                                    ?>
                                        <div class="info-transport">
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <i class="fa <?php echo $icon; ?>"></i> <?php echo $dt->merk; ?>
                                                    <br>
                                                    <span><?php echo $dt->tanggal_format . ' ' . date('H:i', strtotime($dt->jam)); ?></span>
                                                    <br>
                                                    <?php echo $dt->tujuan . (($dt->kota_tujuan) ? ", " . $dt->kota_tujuan : "") ?>
                                                </div>
                                                <div class="col-sm-10">
                                                    <strong>No. Tiket:</strong> <?php echo $dt->no_tiket; ?><br>
                                                    <strong>Harga:</strong> Rp. <?php echo number_format($dt->harga, 2, ".", ","); ?><br>
                                                    <strong>Status Tiket:</strong> <?php echo $dt->status_tiket; ?><br>
                                                    <?php if (isset($dt->lampiran)) {
                                                        $link_file = site_url('assets/file/travel/' . $dt->lampiran);
                                                        $file_name = end((array_values(explode("/", $dt->lampiran))));
                                                        echo '<strong>Lampiran:</strong> <span><a href="' . $link_file . '" data-fancybox> ' . $file_name . ' </a></span><br>';
                                                    }
                                                    ?>
                                                    <strong>Keterangan:</strong> <?php echo $dt->keterangan; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php foreach ($list_penginapan as $dt) { ?>
                                        <div class="info-penginapan">
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <i class="fa fa-bed"></i> <?php echo ucwords($dt->nama_hotel); ?>
                                                    <br>
                                                    <span><?php echo $dt->start_date_format . ' - ' . $dt->end_date_format; ?></span>
                                                </div>
                                                <div class="col-sm-10">
                                                    <strong>Alamat:</strong> <?php echo $dt->alamat; ?><br>
                                                    <strong>PIC Hotel:</strong> <?php echo $dt->PIC_hotel; ?><br>
                                                    <?php if (isset($dt->lampiran)) {
                                                        $link_file = site_url('assets/file/travel/' . $dt->lampiran);
                                                        echo '<strong>Lampiran:</strong> <span><a href="' . $link_file . '" data-fancybox><span class="badge bg-red-gradient"><i class="fa fa-file"></i></span> </a></span><br>';
                                                    }
                                                    ?>
                                                    <strong>Keterangan:</strong> <?php echo $dt->keterangan; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div id="list-penerimaan"></div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <?php if ($flagApproval == 'true' || $flagApprovalBy == 'true') {
                                // echo json_encode($pengajuan->no_trip);
                            ?>

                                <?php if (($pengajuan->approval_level == '4' && $pengajuan->approval_status == '1' && $pengajuan->no_trip !== null) || $deklarasi) { ?>
                                <?php } elseif (isset($cancel) && $cancel->approval_level == '99') {
                                } else {

                                ?>
                                    <button value="revise" type="button" name="action_btn" class="btn action_btn btn-warning">Ask to revise</button>
                                    <button value="approve" type="button" name="action_btn" class="btn action_btn btn-success">Disetujui</button>
                                    <button value="disapprove" type="button" name="action_btn" class="btn action_btn btn-danger">Ditolak</button>
                                <?php } ?>
                            <?php } ?>
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

<!-- Plugin ini bikin burger icon sidebar gabisa di klik -->
<!-- <script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.js"></script> -->

<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/detail.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_pembatalan.js"></script>