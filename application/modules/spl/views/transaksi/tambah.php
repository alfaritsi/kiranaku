<?php $this->load->view('header') ?>
<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/> -->
<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/> -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css" />
<style>
    #table-detail th {
        vertical-align: middle;
        text-align: center;
    }

    #table-detail td {
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
                            <input type="hidden" name="action" value="submit">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3">No. SPL</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="no_spl" value="<?php echo $no_spl; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Pabrik</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="plant" value="<?php echo $plant; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Departemen</label>
                                        <div class="col-sm-9">
                                            <select class="form-control select2" name="id_departemen" id="id_departemen" data-allowClear="true" required>
                                                <option value=""></option>
                                                <?php
                                                foreach ($departemen as $dt) {
                                                    echo "<option value='$dt->id'>$dt->nama</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Seksie</label>
                                        <div class="col-sm-9">
                                            <select class="form-control select2" name="id_seksie" id="id_seksie" data-allowClear="true" required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Unit</label>
                                        <div class="col-sm-9">
                                            <select class="form-control select2" name="id_unit[]" id="id_unit" multiple>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Keterangan</label>
                                        <div class="col-sm-9">
                                            <select class="form-control select2" name="keterangan_lembur" id="keterangan_lembur" data-allowClear="true" required>
                                                <option value=""></option>
                                                <?php
                                                foreach ($keterangan_lembur as $dt) {
                                                    $disabled = ($dt->check_master == 1) ? "disabled" : "";
                                                    echo "<option value='$dt->keterangan' data-check_master='$dt->check_master' $disabled>$dt->keterangan</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3">Tanggal SPL</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" id="tanggal_spl" name="tanggal_spl" value="<?php echo date('d.m.Y'); ?>" data-autoclose="true" readonly required style="background-color: #fff;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Jam Lembur</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="time" class="form-control timepicker" id="jam_mulai">
                                                <span class="input-group-addon">s/d</span>
                                                <input type="time" class="form-control timepicker" id="jam_selesai">
                                            </div>
                                            <span class="help-block" style="color: red;"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3">Plan Lembur</label>
                                        <div class="col-sm-9">
                                            <input type="hidden" name="is_plan_fo" id="is_plan_fo">
                                            <input type="text" class="form-control" name="plan" id="plan" value="UNPLAN" readonly>
                                            <!-- <textarea type="text" class="form-control" rows="1" name="plan" id="plan" value="UNPLAN" readonly></textarea> -->
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
                                            <input type="file" class="form-control" name="lampiran[]" id="lampiran" required>
                                        </div>
                                    </div>
                                    <div class="form-group data-ba d-none">
                                        <label class="col-sm-3">Lampiran BA</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control input-ba" name="lampiran_ba[]" id="lampiran_ba">
                                        </div>
                                    </div>
                                    <div class="form-group data-ba d-none">
                                        <label class="col-sm-3">Alasan Backdate</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control input-ba" name="alasan_ba" id="alasan_ba" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Daftar Karyawan</h4>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-sm btn-default pull-right add_karyawan"><i class="fa fa-plus"></i> Tambah</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="table-detail">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="width: 7%;">No.</th>
                                                    <th rowspan="2">NIK - Nama</th>
                                                    <th rowspan="2">Posisi</th>
                                                    <th rowspan="2" style="width: 7%;">Total Lembur Sebelum SPL</th>
                                                    <th colspan="2" style="width: 20%;">Waktu</th>
                                                    <th rowspan="2" style="width: 7%;">Total Lembur Setelah SPL</th>
                                                    <th rowspan="2" style="width: 8%;"></th>
                                                </tr>
                                                <tr>
                                                    <th>Mulai</th>
                                                    <th>Selesai</th>
                                                </tr>
                                            </thead>
                                            <tbody id="list-karyawan">
                                                <tr id="no-data-karyawan">
                                                    <td colspan="8">No Data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer">
                        <!-- <a href="<?php echo base_url() . 'plantation/transaksi/data'; ?>"><button type="button" class="btn btn-default" style="width:100px;">Kembali</button></a> -->
                        <button type="button" name="action_btn" class="btn btn-success" data-btn="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<!-- <script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script> -->
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/spl/transaksi/tambah.js?<?php echo time(); ?>"></script>