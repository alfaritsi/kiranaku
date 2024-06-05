<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css" />
<!-- for attchment -->
<!-- link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css" />
-->
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

    @media only screen and (max-width: 767px) {
        div.bootstrap-datetimepicker-widget {
            bottom: 45px !important;
            top: auto !important;
            /*background-color: orangered !important;*/
        }
    }
</style>
<?php
echo "<input value='" . $tipe_screen[0] . "' id='tipe_screen' /> ";
foreach ($list as $dt) {
?>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-success">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-md-12">
                                    <h4>Data Pemesanan</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-2" for="label_p_nik">Nama</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static no-padding" id="label_p_nik"><?php echo $dt->nama_karyawan . " (" . $dt->nik . ") "; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-2" for="label_p_kantor">No. trip</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static no-padding" id="label_p_kantor">
                                                    <?php echo $dt->no_trip; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- </fieldset> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="box_table_trans">
                    <div class="nav-tabs-custom">

                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_transportasi" data-toggle="tab" aria-expanded="true">
                                    <span><i class="fa fa-plane colors-peach"></i></span>
                                    Transportasi
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab_penginapan" data-toggle="tab" aria-expanded="false">
                                    <span><i class="fa fa-bed colors-green"></i></span>
                                    Penginapan
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" style="min-height: 300px;">
                            <!-- tab transportasi -->
                            <div class="tab-pane active" id="tab_transportasi">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php if ($dt->approval_level <> 99) : ?>
                                            <div class="row">
                                                <div class="col-sm-12" id="button-add-trans">
                                                    <div class="btn-group pull-right">
                                                        <button class="btn btn-success btn-block  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button"><i class="fa fa-plus"></i> Tambah Transportasi
                                                        </button>
                                                        <input type="hidden" id="count_field" value='0'>
                                                        <ul class="dropdown-menu" id="ul_list_transport">
                                                            <li><a href="#" data-pesan="0" data-add='<?php echo json_encode($list_trans); ?>' class="transport_add_btn btn-default" data-type="pesawat"><i class="fa fa-plane"></i> <span class="pull-right">Pesawat</span></a></li>
                                                            <li><a href="#" data-pesan="0" data-add='<?php echo json_encode($list_trans); ?>' class="transport_add_btn" data-type="taxi"><i class="fa fa-taxi"></i> <span class="pull-right">Taksi</span></a></li>
                                                        </ul>
                                                        <hr /><br>
                                                    </div>
                                                </div>

                                            </div>
                                        <?php endif; ?>
                                        <table id="table-transportasi" class="table table-bordered table-responsive  table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="20%">Perjalanan</th>
                                                    <th width="8%">Berangkat</th>
                                                    <th width="8%">Transportasi</th>
                                                    <th width="8%">Status</th>
                                                    <th width="8%">Tiket</th>
                                                    <th width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                foreach ($list_trans as $dtdetail) {
                                                    $tgl    = $dtdetail['start_date'];
                                                    $tm     = $dtdetail['start_time'];
                                                    $tujuan_dt = $dtdetail['tujuan'];
                                                    $tujuan = str_replace(' ke ', ' <img src="' . base_url() . 'assets/apps/img/travel/right-arrow.png" width="20" height="20"> ', $dtdetail['tujuan']);
                                                    $waktu  = date("d.m.Y H:i", strtotime("$tgl $tm"));
                                                    $status = isset($dtdetail['status_pesan']) ? $dtdetail['status_pesan'] : "";


                                                    $tiket  = isset($dtdetail['tiket_pesan']) && $dtdetail['tiket_pesan'] != ""  ?
                                                        "<a class='fileinput-exists fileinput-zoom' target='_blank' data-fancybox='' href='" . base_url() . "assets/file/travel/" . $dtdetail['lampiran'] . "'>" . $dtdetail['tiket_pesan'] . "</a>" : "";
                                                    $tiket_trans_jenis = isset($dtdetail['tiket_trans_jenis']) ? $dtdetail['tiket_trans_jenis'] : "";
                                                    if ($status == "Issued") {
                                                        if ($tiket_trans_jenis == 'taxi') {
                                                            $status = "<span class='label label-success'>Sudah dipesankan</span>";
                                                        } else {
                                                            $status = "<span class='label label-success'>Issued</span>";
                                                        }
                                                    } else if ($status == "Cancel") {
                                                        $status = "<span class='label label-danger'>Cancel</span>";
                                                    } else {
                                                        if (in_array($tiket_trans_jenis, ['pesawat', 'taxi']))
                                                            $status = "<span class='label label-warning'>Belum dipesankan</span>";
                                                        else
                                                            $status = "-";
                                                    }
                                                    $tujuan_opt = isset($tujuan_dt) ? $tujuan_dt : "";
                                                    $data_edit = $dt->id_travel_header . "|" . ($dtdetail['id_travel_detail']) . "|" . $tujuan_opt . "|" . $dtdetail['start_date'] . "|" . date('h:i', strtotime($dtdetail['start_time'])) . "|" . $dtdetail['tiket_trans_jenis'] . "|" . $dtdetail['tiket_keperluan'] . "|" . date("d.m.Y H:i", strtotime("$tgl $tm")) . "|" . ($dtdetail['id_travel_transport']);
                                                    $action_text = isset($tiket) && $tiket != "" ? "Lihat" : "Pesan";
                                                    if ($tiket_trans_jenis == "pesawat") {
                                                        $action = '<a href="#" data-pesan="' . $data_edit . '"  class="transport_add_btn btn btn-default" data-type="pesawat"><i class="fa fa-plane"></i><span class="text_button_add"> ' . $action_text . '</span></a>';
                                                    } elseif ($tiket_trans_jenis == "taxi") {
                                                        $action = '<a href="#" data-pesan="' . $data_edit . '"  class="transport_add_btn btn btn-default" data-type="taxi"><i class="fa fa-taxi"></i><span class="text_button_add"> ' . $action_text . ' </span></a>';
                                                    } else {
                                                        $action = "";
                                                    }

                                                    echo "<tr>";
                                                    echo "<td>" . $tujuan . "</td>";
                                                    echo "<td>" . $waktu . "</td>";
                                                    echo "<td>" . ucwords($dtdetail['nama_transportasi']) . " - " . ucwords($dtdetail['tiket_keperluan']) . "</td>"; //
                                                    echo "<td>" . $status . "</td>";
                                                    echo "<td>" . $tiket . "</td>";
                                                    echo "<td>" . $action . "</td>";
                                                    echo "</tr>";
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                        <div class="col-md-6 pull-right">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- tab penginapan -->
                            <div class="tab-pane" id="tab_penginapan">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <?php if ($dt->approval_level <> 99) : ?>
                                            <div class="row">
                                                <div class="col-sm-12" id="button-add-hotel">
                                                    <div class="btn-group pull-right">
                                                        <button class="btn btn-success btn-block  dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button">
                                                            <i class="fa fa-plus"></i> Tambah Penginapan
                                                        </button>
                                                        <input type="hidden" id="count_field" value='0'>
                                                        <ul class="dropdown-menu" id="ul_list_hotel">
                                                            <li><a href="#" data-pesan="0" data-add='<?php echo json_encode($list_hotel); ?>' class="transport_add_btn btn-default" data-type="hotel"><i class="fa fa-hotel"></i> <span class="pull-right">Hotel</span></a></li>
                                                        </ul>
                                                        <hr /><br>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <table id="table-penginapan" class="table table-bordered table-responsive  table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Perjalanan</th>
                                                    <th>Waktu</th>
                                                    <th width="10%">Penginapan</th>
                                                    <th width="15%">Status</th>
                                                    <th width="15%">Hotel</th>
                                                    <th width="8%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                foreach ($list_hotel as $dtdetail) {
                                                    $tgl    = $dtdetail['start_date'];
                                                    $tm     = $dtdetail['start_time'];
                                                    $tujuan_dt = $dtdetail['tujuan'];
                                                    $tujuan = str_replace(' ke ', ' <img src="' . base_url() . 'assets/apps/img/travel/right-arrow.png" width="20" height="20"> ', $dtdetail['tujuan']);
                                                    // $waktu  = date("d-m-Y H:i:s", strtotime("$tgl $tm"));
                                                    $waktu = $dtdetail['start_date_format'] . ' - ' . $dtdetail['end_date_format'];
                                                    $status = isset($dtdetail['status_pesan']) ? $dtdetail['status_pesan'] : "";


                                                    $tiket  = isset($dtdetail['tiket_pesan']) && $dtdetail['tiket_pesan'] != ""  ?
                                                        "<a class='fileinput-exists fileinput-zoom' target='_blank' data-fancybox='' href='" . base_url() . "assets/file/travel/" . $dtdetail['lampiran'] . "'>" . $dtdetail['tiket_pesan'] . "</a>" : "";
                                                    $tiket_trans_jenis = isset($dtdetail['tiket_trans_jenis']) ? $dtdetail['tiket_trans_jenis'] : "";
                                                    if ($status == "Sudah dipesankan") {
                                                        $status = "<span class='label label-success'>Sudah dipesankan</span>";
                                                    } else {
                                                        $status = "<span class='label label-warning'>Belum dipesankan</span>";
                                                    }

                                                    $tujuan_opt = isset($tujuan_dt) ? $tujuan_dt : "";
                                                    $data_edit = $dt->id_travel_header . "|" . ($dtdetail['id_travel_detail']) . "|" . $tujuan_opt . "|" . $dtdetail['start_date'] . "|" . date('h:i', strtotime($dtdetail['start_time'])) . "|" . $dtdetail['tiket_trans_jenis'] . "|" . $dtdetail['tiket_keperluan'] . "|" . date("d.m.Y H:i", strtotime("$tgl $tm")) . "|" . ($dtdetail['id_travel_hotel']);
                                                    $action_text = isset($tiket) && $tiket != "" ? "Lihat" : "Pesan";
                                                    if ($tiket_trans_jenis == "hotel") {
                                                        $action = '<a href="#" data-pesan="' . $data_edit . '"  class="transport_add_btn btn btn-default" data-type="hotel"><i class="fa fa-hotel"></i><span class="text_button_add"> ' . $action_text . '</span></a>';
                                                    } else {
                                                        $action = "";
                                                    }

                                                    echo "<tr>";
                                                    echo "<td>" . $tujuan . "</td>";
                                                    echo "<td>" . $waktu . "</td>";
                                                    echo "<td>" . ucwords($tiket_trans_jenis) . "</td>"; //." - ".$dtdetail['tiket_keperluan']
                                                    echo "<td>" . $status . "</td>";
                                                    echo "<td>" . $tiket . "</td>";
                                                    echo "<td>" . $action . "</td>";
                                                    echo "</tr>";
                                                }
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer" align="center">
                            <form name="form-complete_trans" class="form-complete_trans">
                                <?php
                                if (
                                    $jumlah_trans_primary_booked >= $jumlah_trans_primary
                                    && $jumlah_hotel_booked >= $jumlah_hotel
                                ) {
                                    $complete = 1;
                                } else {
                                    $complete = 0;
                                }
                                ?>
                                <input type="hidden" name="complete_trans_hotel" id="complete_trans_hotel" value="<?php echo $complete; ?>" data-idheader="<?php echo $this->generate->kirana_encrypt($dt->id_travel_header);  ?>">
                                <input type="hidden" name="id_header" id="id_header" value="<?php echo $this->generate->kirana_encrypt($dt->id_travel_header); ?>">
                                <?php if ($dt->approval_level <> 99) : ?>
                                    <button name="simpan_btn_complete" id="simpan_btn_complete" class="btn btn-success " type="button" data-jenis="submitall">Submit</button>
                                <?php endif; ?>
                                <button name="back_btn_complete" id="back_btn_complete" class="btn btn-warning " type="button">Kembali</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 hide" id="box_form_trans">
                    <div class="box box-success" id="box_form_success">
                        <div class="row">
                            <div class="col-sm-12" id="form_book">


                                <div id="template-transport-pesawat1" class="hide">
                                    <form class="form-horizontal form-booking_pesawat">

                                        <input type="hidden" name="id_travel_header" value="<?php echo $this->generate->kirana_encrypt($dt->id_travel_header); ?>" />
                                        <input type="hidden" name="tujuan_trip" id="tujuan_trip_pesawat" />
                                        <input disabled type="hidden" name="transport[1][id_travel_transport]" class="transport-id">
                                        <input disabled type="hidden" name="transport[1][jenis_kendaraan]">
                                        <input disabled type="hidden" name="transport[1][transport_kembali]">
                                        <div class="box-header with-border">
                                            <h4 class="box-title">Transportasi Pesawat</h4>

                                            <h5 class="text-muted transport-tujuan"></span>
                                                <span class="transport-jadwal-perjalanan_pesawat1"></span>
                                                <span class="pull-right transport-jadwal-keberangkatan_pesawat1"></span>
                                            </h5>

                                            <div class="box-tools pull-right hide">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                </button>
                                                <button type="button" class="btn btn-box-tool text-danger transport_remove_btn"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">

                                            <div class="form-group no-padding div_perjalanan">
                                                <label class="col-md-4" for="transport[1][id_travel_detail]">Perjalanan</label>
                                                <div class="col-md-8">
                                                    <select disabled class="select-perjalanan form-control select2" name="transport[1][id_travel_detail]">
                                                        <!-- <option value="kembali">Kembali</option> -->
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="keberangkatan">Keberangkatan</label>
                                                <div class="col-md-8">
                                                    <div class="input-group transport_jadwal">
                                                        <input disabled readonly name="transport[1][jadwal]" type="text" placeholder="Pilih jadwal" required class="form-control">
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="keberangkatan">Maskapai</label>
                                                <div class="col-md-8 divvendor" id="divvendor1">

                                                    <select id="select-vendor-pesawat1" name="transport[1][vendor]" class="transport-vendor form-control select2" required>

                                                        <?php foreach ($pesawat_merk as $vendor) : ?>
                                                            <option value="<?php echo $vendor->kode_merk ?>">
                                                                <?php echo $vendor->merk ?>
                                                            </option>
                                                        <?php endforeach; ?>

                                                    </select>

                                                </div>
                                            </div>
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="keberangkatan">Tiket</label>
                                                <div class="col-md-8">
                                                    <input disabled name="transport[1][no_tiket]" type="text" placeholder="Ketik no tiket" class="form-control transport-no_tiket" required>
                                                </div>
                                            </div>
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="keberangkatan">Harga</label>
                                                <div class="col-md-8">
                                                    <div class="input-group">
                                                        <input disabled name="transport[1][harga]" type="text" placeholder="Harga tiket" class="form-control numeric transport-harga" required>
                                                        <span class="input-group-addon">IDR</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="keberangkatan">Refundable</label>
                                                <div class="col-md-8 divrefund" id="divrefund1">
                                                    <input type="hidden" name='transport[1][status_tiket_refund]' value='refundable'>
                                                    <div class="col-sm-6">
                                                        <input type="radio" id="refundable" value="Refundable" class="iradio_flat-blue"> <label> Ya </label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="radio" id="unrefundable" value="unrefundable" class="iradio_flat-blue" style="margin-left: 10px"> <label> Tidak </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="keberangkatan">Lampiran</label>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <div class="fileinput fileinput-new col-sm-4" data-provides="fileinput">
                                                            <div class="btn-group btn-sm no-padding">
                                                                <a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox="image" data-type="image"><i class="fa fa-search"></i></a>
                                                                <a class="btn btn-facebook btn-file">
                                                                    <div class="fileinput-new"><i class="fa fa-plus"> </i>Lampiran</div>
                                                                    <div class="fileinput-exists"><i class="fa fa-edit"></i></div>
                                                                    <input type="file" name="transport[1][lampiran]" id="lampiran_pesawat" required>
                                                                </a>
                                                                <a href="#" class="btn btn-pinterest fileinput-exists" data-dismiss="fileinput"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="text-danger"><small>* File yang diperbolehkan untuk diunggah hanya </br> jpg/jpeg/png/pdf dan ukuran file maksimum 2 Mb. </small></span>
                                                </div>
                                            </div>
                                            <div class="form-group no-padding">
                                                <label class="col-md-4" for="keberangkatan">Keterangan</label>
                                                <div class="col-md-8">
                                                    <textarea disabled name="transport[1][keterangan]" id="transport_1_keterangan" placeholder="Ketik keterangan transportasi" class="form-control transport-keterangan"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group no-padding">
                                                <label class="col-md-4"> Status tiket</label>
                                                <div class="col-md-8">
                                                    <span id="span_status_tiket1" class="select_tiket ">
                                                        <input data-width="70px" class="switch-onoff select-status-tiket" type="checkbox" name="transport[1][status_tiket]" id="status_tiket1_pesawat" checked data-toggle="toggle" data-baris="1" data-on="Issued" data-off="Cancel" data-onstyle="success" data-offstyle="danger">
                                                    </span>
                                                </div>
                                            </div>
                                            <div id="div_alasan_cancel1" class="form-group no-padding" style="display:none">
                                                <label class="col-md-4" for="keberangkatan">Alasan cancel</label>
                                                <div class="col-md-8">
                                                    <textarea disabled name="transport[1][alasan_cancel]" id="transport_1_alasan_cancel" placeholder="Ketik keterangan cancel" class="form-control transport-keterangan_cancel"></textarea>'
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <input name="transport[1][status_tiket_primary]" type="hidden" placeholder="" class="form-control transport-status_tiket_primary" readonly>
                                            <input type="hidden" name="availablehotel" id="availablehotel_psw" value="">
                                            <?php if ($dt->approval_level <> 99) : ?>
                                                <button name="simpan_btn" id="submit_pesawat" class="btn btn-primary hide" type="button" data-jenis="pesawat">Pesan Tiket</button>
                                                <button type="button" id="cancel_pesawat" class="btn btn-danger hide" data-dismiss="modal">Batal</button>
                                            <?php else : ?>
                                                <button type="button" id="cancel_pesawat" class="btn btn-danger hide" data-dismiss="modal">Tutup</button>
                                            <?php endif; ?>
                                        </div>

                                    </form>
                                </div>

                                <div id="template-transport-taxi" class="hide">
                                    <form class="form-horizontal form-booking_taxi">

                                        <input type="hidden" name="id_travel_header" value="<?php echo $this->generate->kirana_encrypt($dt->id_travel_header); ?>" />
                                        <input type="hidden" name="tujuan_trip" id="tujuan_trip_taxi" />
                                        <input disabled type="hidden" name="transport[1][id_travel_transport]" class="transport-id">
                                        <input disabled type="hidden" name="transport[1][jenis_kendaraan]">
                                        <input disabled type="hidden" name="transport[1][transport_kembali]">
                                        <div class="box transport-booking transport-taxi animated fadeIn">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Transportasi Taksi</h3>
                                                <span id="span_status_tiket1_taxi" class="select_tiket hide"><input data-width="59px" class="switch-onoff select-status-tiket" type="checkbox" name="transport[1][status_tiket]" id="status_tiket1_taxi" checked data-toggle="toggle" data-size="mini" data-baris="1" data-on="Issued" data-off="Cancel" data-onstyle="success" data-offstyle="danger">
                                                </span>
                                                <h5 class="text-muted transport-tujuan">
                                                    <span class="transport-jadwal-perjalanan_taxi1"></span>
                                                    <span class="pull-right transport-jadwal-keberangkatan_taxi1"></span>
                                                </h5>
                                                <div class="box-tools pull-right hide">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-box-tool text-danger transport_remove_btn"><i class="fa fa-trash"></i></button>
                                                </div>
                                            </div>
                                            <div class="box-body">
                                                <div class="form-group no-padding div_perjalanan">
                                                    <label class="col-md-4" for="transport[1][id_travel_detail]">Perjalanan</label>
                                                    <div class="col-md-8">
                                                        <select disabled class="select-perjalanan form-control select2" name="transport[1][id_travel_detail]">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">Keberangkatan</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group transport_jadwal">
                                                            <input disabled readonly name="transport[1][jadwal]" type="text" placeholder="Pilih jadwal" required class="form-control">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">Taksi</label>
                                                    <div class="col-md-8 divvendor" id="divvendor1">
                                                        <!-- <input disabled name="transport[1][vendor]" type="text"
                                                           placeholder="Ketik nama taksi"
                                                           required class="form-control transport-vendor"> -->
                                                        <select id="select-vendor-taxi1" name="transport[1][vendor]" class="form-control transport-vendor select2" required>
                                                            <?php foreach ($taxi_merk as $vendor) : ?>
                                                                <option value="<?php echo $vendor->kode_merk ?>">
                                                                    <?php echo $vendor->merk ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">Voucher</label>
                                                    <div class="col-md-8">
                                                        <input disabled name="transport[1][no_tiket]" type="text" placeholder="Ketik no voucher" required class="form-control transport-no_tiket">
                                                    </div>
                                                    <div class="col-md-4 hide">
                                                        <div class="input-group">
                                                            <input disabled name="transport[1][harga]" type="text" placeholder="Harga tiket" class="form-control text-right numeric transport-harga" required>
                                                            <span class="input-group-addon">IDR</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding div_lampiran" class="hide">
                                                    <label class="col-md-4" for="keberangkatan">Lampiran</label>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class="fileinput fileinput-new col-sm-4" data-provides="fileinput">
                                                                <div class="btn-group btn-sm no-padding">
                                                                    <a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox="image" data-type="image"><i class="fa fa-search"></i></a>
                                                                    <a class="btn btn-facebook btn-file">
                                                                        <div class="fileinput-new"><i class="fa fa-plus"> </i>Lampiran</div>
                                                                        <div class="fileinput-exists"><i class="fa fa-edit"></i></div>
                                                                        <input type="file" name="transport[1][lampiran]" id="lampiran_taxi" required>
                                                                    </a>
                                                                    <a href="#" class="btn btn-pinterest fileinput-exists" data-dismiss="fileinput">
                                                                        <i class="fa fa-trash"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="text-danger"><small>* File yang diperbolehkan untuk diunggah hanya </br> jpg/jpeg/png/pdf dan ukuran file maksimum 2 Mb. </small></span>
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding hide">
                                                    <label class="col-md-4" for="keberangkatan">Refundable</label>
                                                    <div class="col-md-8 divrefund" id="divrefund1_taxi">
                                                        <select id="select-refund-taxi1" name="transport[1][status_tiket_refund]" class="transport-refund select2 form-control" required>
                                                            <option value="Refundable">Ya</option>
                                                            <option selected="selected" value="Unrefundable">Tidak</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">Keterangan</label>
                                                    <div class="col-md-8">
                                                        <textarea disabled name="transport[1][keterangan]" placeholder="Ketik keterangan transportasi" class="form-control transport-keterangan"></textarea>
                                                    </div>
                                                </div>
                                                <div id="div_alasan_cancel1_taxi" class="form-group no-padding" style="display:none">
                                                    <label class="col-md-4" for="keberangkatan">Alasan cancel</label>
                                                    <div class="col-md-8">
                                                        <textarea disabled name="transport[1][alasan_cancel]" id="transport_1_alasan_cancel_taxi" placeholder="Ketik keterangan cancel" class="form-control transport-keterangan_cancel"></textarea>'
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                <input name="transport[1][status_tiket_primary]" type="hidden" placeholder="" class="form-control transport-status_tiket_primary" readonly>
                                                <input type="hidden" name="availablehotel" id="availablehotel_tx" value="">
                                                <?php if ($dt->approval_level <> 99) : ?>
                                                    <button name="simpan_btn" id="submit_taxi" class="btn btn-primary hide" type="button" data-jenis="taxi">Pesan Taksi</button>
                                                    <button type="button" id="cancel_taxi" class="btn btn-danger hide" data-dismiss="modal">Batal</button>
                                                <?php else : ?>
                                                    <button type="button" id="cancel_taxi" class="btn btn-danger hide" data-dismiss="modal">Tutup</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </form>
                                </div>

                                <!-- <button type="button" id="submit" value="submit" class="btn btn-success pull-right hide">Submit</button> -->


                                <!-- </fieldset> -->
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 hide" id="box_form_hotel">
                    <div class="box box-success " id="box_form_success_hotel">
                        <div class="row">
                            <div class="col-sm-12" id="form_book_hotel">
                                <!-- <div class="col-sm-4" class="hide" id="form_book_hotel"> -->
                                <!-- <fieldset class="fieldset-4px-rad fieldset-success no-pad-top animated fadeIn margin hide" id="fieldset_book_hotel" style="margin-top: 0"> -->
                                <!-- <legend class="no-pad-top"><h4>Data Pemesanan</h4></legend> -->
                                <form class="form-horizontal form-booking_hotel">
                                    <div id="template-penginapan-hotel" class="hide">
                                        <div class="box penginapan-booking penginapan-hotel animated fadeIn">
                                            <input type="hidden" name="id_travel_header" value="<?php echo $this->generate->kirana_encrypt($dt->id_travel_header); ?>" />
                                            <input type="hidden" name="tujuan_trip" id="tujuan_trip_hotel" />
                                            <input disabled type="hidden" name="penginapan[1][id_travel_hotel]" class="transport-id">

                                            <!-- <input disabled type="hidden" name="penginapan[1][jenis_kendaraan]" > -->
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Penginapan Hotel</h3>
                                                <h5 class="text-muted penginapan-tujuan">
                                                    <span class="penginapan-jadwal-perjalanan1"></span>
                                                    <span class="pull-right penginapan-jadwal-keberangkatan1"></span>
                                                </h5>
                                                <div class="box-tools pull-right hide">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-box-tool text-danger penginapan_remove_btn"><i class="fa fa-trash"></i></button>
                                                </div>
                                            </div>
                                            <div class="box-body">
                                                <input disabled type="hidden" name="penginapan[1][id_travel_hotel]" class="penginapan-id">
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="penginapan[1][id_travel_detail]">Perjalanan</label>
                                                    <div class="col-md-8">
                                                        <select disabled class="select-perjalanan form-control select2" name="penginapan[1][id_travel_detail]">

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">Check In</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group penginapan_start_date">
                                                            <input disabled readonly name="penginapan[1][start_date]" type="text" placeholder="Check in" required class="form-control">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <div class="col-sm-4">Check Out</div>
                                                    <div class="col-md-8 ">
                                                        <div class="input-group penginapan_end_date">
                                                            <input disabled readonly name="penginapan[1][end_date]" type="text" placeholder="Check out" required class="form-control">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">Nama Hotel</label>
                                                    <div class="col-md-8">
                                                        <input disabled name="penginapan[1][nama_hotel]" type="text" placeholder="Ketik nama hotel" maxlength="100" required class="form-control penginapan-nama_hotel">
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">PIC</label>
                                                    <div class="col-md-8">
                                                        <input disabled name="penginapan[1][pic_hotel]" type="text" placeholder="Ketik nama PIC hotel" maxlength="50" required class="form-control penginapan-pic_hotel">
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">Alamat</label>
                                                    <div class="col-md-8">
                                                        <input disabled name="penginapan[1][alamat]" type="text" placeholder="Ketik alamat hotel" maxlength="100" required class="form-control penginapan-alamat">
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">Lampiran</label>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class="fileinput fileinput-new col-sm-4" data-provides="fileinput">
                                                                <div class="btn-group btn-sm no-padding">
                                                                    <a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox="image" data-type="image"><i class="fa fa-search"></i></a>
                                                                    <a class="btn btn-facebook btn-file">
                                                                        <div class="fileinput-new"><i class="fa fa-plus"> </i>Lampiran</div>
                                                                        <div class="fileinput-exists"><i class="fa fa-edit"></i></div>
                                                                        <input type="file" name="penginapan[1][lampiran]" id="lampiran_hotel" required>
                                                                    </a>
                                                                    <a href="#" class="btn btn-pinterest fileinput-exists" data-dismiss="fileinput">
                                                                        <i class="fa fa-trash"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <input disabled name="penginapan[1][lampiran]" type="file"
                                                           placeholder="Pilih lampiran tiket"
                                                           required class="form-control penginapan-lampiran"> -->
                                                    </div>
                                                </div>
                                                <div class="form-group no-padding">
                                                    <label class="col-md-4" for="keberangkatan">Keterangan</label>
                                                    <div class="col-md-8">
                                                        <textarea disabled name="penginapan[1][keterangan]" placeholder="Ketik keterangan hotel" class="form-control penginapan-keterangan"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                <input name="penginapan[1][status_tiket_primary]" type="hidden" class="form-control hotel-status_tiket_primary" readonly>
                                                <input type="hidden" name="availablehotel" id="availablehotel_hotel" value="">
                                                <?php if ($dt->approval_level <> 99) : ?>
                                                    <button name="simpan_btn" id="submit_hotel" class="btn btn-primary hide" type="button" data-jenis="hotel">Pesan Hotel</button>
                                                    <button type="button" id="cancel_hotel" class="btn btn-danger hide" data-dismiss="modal">Batal</button>
                                                <?php else : ?>
                                                    <button type="button" id="cancel_hotel" class="btn btn-danger hide" data-dismiss="modal">Tutup</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                                <!-- </fieldset> -->
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    </section>
    </div>
<?php
}
?>

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
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.js"></script>

<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script> -->
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_booking.js"></script> -->
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/add_booking.js?<?php echo time(); ?>"></script>