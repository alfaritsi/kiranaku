<div class="modal fade modal-medical-detail" role="dialog" id="modal-medical-detail">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Pengajuan <span id="form-medical">Rawat Jalan</span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="fieldset-primary">
                            <legend class="text-center">Info Pasien</legend>
                            <div class="form-group">
                                <label class="col-md-4">NIK</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="nik"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nama Karyawan</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="nama_karyawan"></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nama Pasien</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="nama_pasien"></p>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="fieldset-warning">
                            <legend class="text-center">Data Pengajuan</legend>
                            <div class="form-group">
                                <label class="col-md-4">No Pengajuan</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="nomor"></p>
                                </div>
                            </div>
                            <div id="fbk-jenis-jalan" class="fbk-jenis-detail hide">
                                <div class="form-group">
                                    <label class="col-md-4">Jenis Sakit</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static" id="sakit"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Sisa Plafon Medical</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <p class="form-control-static" id="plafon">Rp. <span
                                                        class="numeric"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="fbk-jenis-inap" class="fbk-jenis-detail hide">
                                <div class="form-group">
                                    <label class="col-md-4">Nama RS</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static" id="rs"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Jenis Sakit</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static" id="sakit"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Plafon Kamar</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <p class="form-control-static" id="plafon_kamar">Rp. <span
                                                        class="numeric"></span> <sub>per hari</sub></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Biaya Kamar</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <p class="form-control-static" id="biaya_kamar">Rp. <span
                                                        class="numeric"></span> <sub>per hari</sub></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Jumlah Hari</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static" id="jumlah_hari"><span></span> Hari</p>
                                    </div>
                                </div>
                            </div>
                            <div id="fbk-jenis-lensa" class="fbk-jenis-detail hide">
                                <div class="form-group">
                                    <label class="col-md-4">Plafon Lensa</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <p class="form-control-static" id="plafon_lensa">Rp. <span
                                                        class="numeric"></span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Biaya Lensa</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <p class="form-control-static" id="biaya_lensa">Rp. <span
                                                        class="numeric"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="fbk-jenis-bersalin" class="fbk-jenis-detail hide">
                                <div class="form-group">
                                    <label class="col-md-4">Jenis Bersalin</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static" id="jenis_bersalin">Normal</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Plafon Bersalin</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <p class="form-control-static" id="plafon_bersalin">Rp. <span
                                                        class="numeric">0</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Biaya Bersalin</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <p class="form-control-static" id="biaya_bersalin">Rp. <span
                                                        class="numeric">0</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="fbk-jenis-frame" class="fbk-jenis-detail hide">
                                <div class="form-group">
                                    <label class="col-md-4">Plafon Frame</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <p class="form-control-static" id="plafon_frame">Rp. <span
                                                        class="numeric"></span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Biaya Frame</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <p class="form-control-static" id="biaya_frame">Rp. <span
                                                        class="numeric"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Keterangan</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="keterangan"></p>
                                </div>
                            </div>
                            <div class="form-group hide">
                                <label class="col-md-4">Gambar Kwitansi</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="gambar_kwitansi">
                                        <a href="#">Lihat</a>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Jumlah Kwitansi</label>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <p class="form-control-static" id="jumlah_kwitansi"><span></span>
                                                Buah</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 div-kwitansi" style="margin-top:10px;">
                                    <div class="box box-success">
                                        <div class="box-body no-padding">
                                            <table class="table table-responsive table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th width="5%"></th>
                                                    <th width="7%">No Detail</th>
                                                    <th width="8%">Tanggal</th>
                                                    <th width="15%">Nomor</th>
                                                    <th width="20%">Nominal Kwitansi</th>
                                                    <th width="20%">Nominal Disetujui</th>
                                                    <th width="10" class="text-center"><i class="fa fa-file" title="Lampiran"></i> </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="template hide">
                                                    <td class="" align="center">
                                                        <p id="kwitansi_disetujui_$" class="fa"></p>
                                                    </td>
                                                    <td>
                                                        <p id="kwitansi_nomor_detail_$"></p>
                                                    </td>
                                                    <td>
                                                        <p id="kwitansi_tanggal_$"></p>
                                                    </td>
                                                    <td>
                                                        <p id="kwitansi_nomor_$"></p>
                                                    </td>
                                                    <td>
                                                        <p id="kwitansi_nominal_$">
                                                            Rp. <span class="numeric">0</span>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p id="kwitansi_amount_ganti_$">
                                                            Rp. <span class="numeric">0</span>
                                                        </p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p id="kwitansi_lampiran_$">

                                                        </p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="4" align="right">
                                                        <strong>Total Kwitansi</strong>
                                                    </td>
                                                    <td colspan="3">
                                                        <strong>
                                                            <p id="total_kwitansi">
                                                                Rp. <span class="numeric"></span>
                                                            </p>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr class="hide">
                                                    <td colspan="4" align="right">
                                                        <strong>Total Estimasi</strong>
                                                    </td>
                                                    <td colspan="3">
                                                        <strong>
                                                            <p id="total_estimasi">
                                                                Rp. <span class="numeric"></span>
                                                            </p>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" align="right">
                                                        <strong id="total_akan_dibayar_label">Total Dibayar Perusahaan</strong>
                                                    </td>
                                                    <td colspan="3">
                                                        <strong>
                                                            <p id="total_akan_dibayar">
                                                                Rp. <span class="numeric"></span>
                                                            </p>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" align="right">
                                                        <strong id="total_akan_dibayar_karyawan_label">Total Dibayar Karyawan</strong>
                                                    </td>
                                                    <td colspan="3">
                                                        <strong>
                                                            <p id="total_akan_dibayar_karyawan">
                                                                Rp. <span class="numeric"></span>
                                                            </p>
                                                        </strong>
                                                    </td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group hide" id="box-pembiayaan">
                                <label class="col-md-4">Catatan</label>
                                <div class="col-md-8">
                                    <p id="catatan"></p>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>