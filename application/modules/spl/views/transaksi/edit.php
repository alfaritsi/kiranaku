<?php $this->load->view('header') ?>
<style>
    .bg-confirm {
        background-color: rgba(0, 141, 76, 0.3) !important;
    }

    .bg-reject {
        background-color: rgba(255, 141, 76, 0.3) !important;
    }

    #table-detail th {
        vertical-align: middle;
        text-align: center;
    }
</style>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> FORM Pengajuan SPL</h3>
                    </div>
                    <div class="box-body">
                        <form id="form-spl" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pabrik</label>
                                        <input type="text" class="form-control" name="plant" value="<?php echo $plant; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Departemen</label>
                                        <input type="text" class="form-control" name="departemen" id="departemen" required readonly value="DEPARTEMEN PABRIK">
                                    </div>
                                    <div class="form-group">
                                        <label>Seksi</label>
                                        <input type="text" class="form-control" name="seksi" value="SEKSIE PRODUKSI" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tanggal SPL</label>
                                        <input type="text" class="form-control" name="tanggal" value="<?php echo date('d.m.Y'); ?>" readonly required>
                                    </div>
                                    <div class="form-group">
                                        <label>Lini</label>
                                        <select class="form-control" name="lini" id="lini" required readonly>
                                            <option value="miling">Milling</option>
                                            <option value="crumbing">Crumbing</option>
                                            <option value="">-</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Plan Lembur</label>
                                        <input type="text" class="form-control" name="plan" value="-" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Daftar Karyawan (Pengajuan)</h4>
                                </div>
                                <!-- <div class="col-md-6">
                                    <button type="button" class="btn btn-sm btn-default pull-right add_karyawan"><i class="fa fa-plus"></i> Tambah</button>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="table-detail">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="width: 7%;">No.</th>
                                                    <th rowspan="2">NIK</th>
                                                    <th rowspan="2">Nama</th>
                                                    <th rowspan="2">Posisi</th>
                                                    <th colspan="2" style="width: 20%;">Waktu</th>
                                                    <th rowspan="2" style="width: 20%;">Keterangan</th>
                                                    <!-- <th rowspan="2" style="width: 8%;"></th> -->
                                                </tr>
                                                <tr>
                                                    <th>Mulai</th>
                                                    <th>Selesai</th>
                                                </tr>
                                            </thead>
                                            <tbody id="list-karyawan">
                                                <tr class="row-karyawan">
                                                    <td>
                                                        <input type="text" name="number[]" class="form-control text-center" readonly value="1">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="nik[]" readonly value="2345">
                                                    </td>
                                                    <td><input type="text" class="form-control" name="nama[]" readonly value="Karyawan 1"></td>
                                                    <td><input type="text" class="form-control" name="posisi[]" readonly value="OPERATOR SKIM"></td>
                                                    <td>
                                                        <input type="datetime" class="form-control" name="jam_mulai[]" readonly value="07:30">
                                                    </td>
                                                    <td>
                                                        <input type="datetime" class="form-control" name="jam_selesai[]" readonly value="10:00">
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="keterangan[]" rows="1" readonly></textarea>
                                                    </td>
                                                </tr>
                                                <tr class="row-karyawan">
                                                    <td>
                                                        <input type="text" name="number[]" class="form-control text-center" readonly value="2">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="nik[]" readonly value="4345">
                                                    </td>
                                                    <td><input type="text" class="form-control" name="nama[]" readonly value="Karyawan 2"></td>
                                                    <td><input type="text" class="form-control" name="posisi[]" readonly value="OPERATOR QC PRODUKSI"></td>
                                                    <td>
                                                        <input type="datetime" class="form-control" name="jam_mulai[]" readonly value="07:30">
                                                    </td>
                                                    <td>
                                                        <input type="datetime" class="form-control" name="jam_selesai[]" readonly value="10:00">
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control" name="keterangan[]" rows="1" readonly></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Daftar Karyawan (Realisasi)</h4>
                                </div>
                                <!-- <div class="col-md-6">
                                    <button type="button" class="btn btn-sm btn-default pull-right add_karyawan"><i class="fa fa-plus"></i> Tambah</button>
                                </div> -->
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
                                                    <th colspan="2" style="width: 20%;">Waktu</th>
                                                    <th rowspan="2" style="width: 20%;">Keterangan</th>
                                                    <th rowspan="2" style="width: 15%;"></th>
                                                </tr>
                                                <tr>
                                                    <th>Mulai</th>
                                                    <th>Selesai</th>
                                                </tr>
                                            </thead>
                                            <tbody id="list-karyawan">
                                                <tr class="row-karyawan">
                                                    <td>
                                                        <input type="text" name="number_realisasi[]" class="form-control text-center" readonly value="1">
                                                    </td>
                                                    <td>
                                                        <select class="form-control input-edit" name="nik[]" disabled>
                                                            <option value="2345">2345 - Karyawan 1</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="posisi[]" readonly value="OPERATOR SKIM"></td>
                                                    <td>
                                                        <input type="datetime" class="form-control input-edit" name="jam_mulai[]" readonly value="07:30">
                                                    </td>
                                                    <td>
                                                        <input type="datetime" class="form-control input-edit" name="jam_selesai[]" readonly value="10:00">
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control input-edit" name="keterangan[]" rows="1" readonly></textarea>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-success btn-approve-karyawan" data-action="confirm"><i class="fa fa-check"></i></button>
                                                            <button type="button" class="btn btn-sm btn-danger btn-approve-karyawan" data-action="reject"><i class="fa fa-remove"></i></button>
                                                            <button type="button" class="btn btn-sm btn-warning btn-edit" data-action="edit"><i class="fa fa-edit"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="row-karyawan">
                                                    <td>
                                                    <input type="text" name="number_realisasi[]" class="form-control text-center" readonly value="2">
                                                    </td>
                                                    <td>
                                                        <select class="form-control input-edit" name="nik[]" disabled>
                                                            <option value="4345">4345 - Karyawan 2</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" class="form-control" name="posisi[]" readonly value="OPERATOR QC PRODUKSI"></td>
                                                    <td>
                                                        <input type="datetime" class="form-control input-edit" name="jam_mulai[]" readonly value="07:30">
                                                    </td>
                                                    <td>
                                                        <input type="datetime" class="form-control input-edit" name="jam_selesai[]" readonly value="10:00">
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control input-edit" name="keterangan[]" rows="1" readonly></textarea>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-sm btn-success btn-approve-karyawan" data-action="confirm"><i class="fa fa-check"></i></button>
                                                            <button type="button" class="btn btn-sm btn-danger btn-approve-karyawan" data-action="reject"><i class="fa fa-remove"></i></button>
                                                            <button type="button" class="btn btn-sm btn-warning btn-edit" data-action="edit"><i class="fa fa-edit"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="action">
                        <!-- <a href="<?php echo base_url() . 'plantation/transaksi/data'; ?>"><button type="button" class="btn btn-default" style="width:100px;">Kembali</button></a> -->
                        <button type="button" name="action_btn" class="btn btn-success" data-btn="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/spl/transaksi/edit.js?<?php echo time(); ?>"></script>