<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css" />
<style>
    .bg-confirm {
        background-color: rgba(0, 141, 76, 0.3) !important;
    }

    .bg-reject {
        background-color: rgba(255, 141, 76, 0.3) !important;
    }

    .table-detail th {
        vertical-align: middle !important;
        text-align: center;
    }

    .table-detail td {
        position: relative;
    }

    .d-none {
        display: none;
    }
</style>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Form Pengajuan SPL</h3>
                    </div>
                    <div class="box-body">
                        <form id="form-spl" class="form-horizontal" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3">No. SPL</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="no_spl" id="no_spl" value="<?php echo $data_spl->no_spl; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Pabrik</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="plant" id="plant" value="<?php echo $data_spl->plant; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Departemen</label>
                                        <div class="col-sm-9">
                                            <input type="hidden" class="form-control" name="id_departemen" value="<?php echo $data_spl->id_departemen; ?>" readonly>
                                            <input type="text" class="form-control" name="departemen" value="<?php echo $data_spl->departemen; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Seksie</label>
                                        <div class="col-sm-9">
                                            <input type="hidden" class="form-control" name="id_seksie" value="<?php echo $data_spl->id_seksi; ?>" readonly>
                                            <input type="text" class="form-control" name="seksie" value="<?php echo $data_spl->seksi; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Unit</label>
                                        <div class="col-sm-9">
                                            <select class="form-control select2" name="id_unit[]" id="id_unit" multiple disabled>
                                                <?php
                                                $output = "";
                                                if ($data_spl->id_unit) {
                                                    $list_unit = explode(";", $data_spl->id_unit);
                                                    $list_nama_unit = explode(";", $data_spl->unit);
                                                    foreach ($list_unit as $i => $value) {
                                                        $output .= "<option value='" . $value . "' selected>" . $list_nama_unit[$i] . "</option>";
                                                    }
                                                }
                                                echo $output;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Keterangan</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="keterangan_lembur" value="<?php echo $data_spl->keterangan_lembur; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Jumlah Orang</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="jumlah_orang" id="jumlah_orang" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3">Tanggal Buat</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" name="tanggal_buat" value="<?php echo $data_spl->tanggal_pengajuan_format; ?>" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Tanggal SPL</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" name="tanggal_spl" value="<?php echo $data_spl->tanggal_spl_format; ?>" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label class="col-sm-3">Jam Lembur</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="datetime" class="form-control timepicker" id="jam_mulai">
                                                <span class="input-group-addon">s/d</span>
                                                <input type="datetime" class="form-control timepicker" id="jam_selesai">
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="form-group">
                                        <label class="col-sm-3">Plan Lembur</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="plan" id="plan" value="<?php echo $data_spl->plan_lembur; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group data-master-plan d-none">
                                        <label class="col-sm-3">Rincian Plan Lembur</label>
                                        <div class="col-sm-9">
                                            <textarea type="text" class="form-control" rows="3" name="rincian_plan" id="rincian_plan" readonly></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Lampiran</label>
                                        <div class="col-sm-9">
                                            <?php
                                            $output = "";
                                            if ($data_spl->filename) {
                                                $output = '<div class="form-control"><a href="' . base_url() . $data_spl->location_file . '" target="_blank" class="view_file"><i class="fa fa-file-o"></i>&nbsp;&nbsp;' . $data_spl->filename . '</a></div>';
                                            } else {
                                                $output = '<input type="text" class="form-control" name="lampiran[]" id="lampiran" readonly value="-">';
                                            }
                                            echo $output;
                                            ?>
                                        </div>
                                    </div>
                                    <?php if ($data_spl->filename_ba) { ?>
                                        <div class="form-group">
                                            <label class="col-sm-3">Lampiran BA</label>
                                            <div class="col-sm-9">
                                                <?php
                                                $output = '<div class="form-control"><a href="' . base_url() . $data_spl->location_file_ba . '" target="_blank" class="view_file"><i class="fa fa-file-o"></i>&nbsp;&nbsp;' . $data_spl->filename_ba . '</a></div>';
                                                echo $output;
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3">Alasan BA</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="alasan_ba" value="<?php echo $data_spl->alasan_ba; ?>" readonly>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <hr>
                            <div class="box-group" id="accordion">
                                <div class="panel box box-default">
                                    <div class="box-header with-border">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h3 class="box-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsePengajuan">
                                                        Daftar Karyawan (Pengajuan)
                                                    </a>
                                                </h3>
                                            </div>
                                            <div class="col-md-6">
                                                <?php if ($data_spl->access && $data_spl->status !== "finish") { ?>
                                                    <button type="button" class="btn btn-sm btn-default pull-right" id="confirm-all"><i class="fa fa-check"></i> Pilih Semua</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div id="collapsePengajuan" class="panel-collapse">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped table-detail">
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2" style="width: 7%;">No.</th>
                                                                    <th rowspan="2">NIK - Nama</th>
                                                                    <th rowspan="2">Posisi</th>
                                                                    <th rowspan="2" style="width: 7%;">Total Lembur Sebelum SPL</th>
                                                                    <th colspan="2" style="width: 20%;">Waktu</th>
                                                                    <th rowspan="2" style="width: 7%;">Total Lembur Setelah SPL</th>
                                                                    <th rowspan="2" style="width: 15%;" id="col-action"></th>
                                                                </tr>
                                                                <tr>
                                                                    <th>Mulai</th>
                                                                    <th>Selesai</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="list-karyawan"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($data_spl->realisasi == 1) { ?>
                                    <hr>
                                    <div class="panel box box-success">
                                        <div class="box-header with-border">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h3 class="box-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseRealisasi">
                                                            Daftar Karyawan (Realisasi)
                                                        </a>
                                                    </h3>
                                                </div>
                                            </div>
                                            <div id="collapseRealisasi" class="panel-collapse collapse in">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover table-striped table-detail">
                                                                <thead>
                                                                    <tr>
                                                                        <th rowspan="2" style="width: 7%;">No.</th>
                                                                        <th rowspan="2">NIK - Nama</th>
                                                                        <th rowspan="2">Posisi</th>
                                                                        <th rowspan="2" style="width: 7%;">Total Lembur Sebelum SPL</th>
                                                                        <th colspan="2" style="width: 20%;">Waktu</th>
                                                                        <th rowspan="2" style="width: 7%;">Total Lembur Setelah SPL</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Mulai</th>
                                                                        <th>Selesai</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="list-karyawan-realisasi"></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ($data_spl->access && $data_spl->status !== "finish") { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-sm-2">Catatan/Komentar</label>
                                            <div class="col-sm-10">
                                                <textarea type="text" class="form-control" rows="3" name="komentar" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                    <div class="box-footer">
                        <!-- <input type="hidden" name="action"> -->
                        <?php if ($data_spl->access && $data_spl->status !== "finish") { ?>
                            <button type="button" name="action_btn" class="btn btn-success" data-action="approve">Setujui</button>
                            <button type="button" name="action_btn" class="btn btn-warning" data-action="reject">Tolak</button>
                        <?php } ?>
                        <?php if ($data_spl->access && $data_spl->status == 'finish' && $data_spl->realisasi == 1) { ?>
                            <button type="button" name="action_btn" class="btn btn-success" data-action="confirm">Konfirmasi</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/spl/transaksi/detail.js?<?php echo time(); ?>"></script>