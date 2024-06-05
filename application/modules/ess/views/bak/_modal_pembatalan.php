<div class="modal fade modal-pembatalan" role="dialog" id="modal-batal-bak">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pembatalan Berita Acara Kehadiran</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" name="form-batal-bak">
                    <input type="hidden" id="id_bak" name="id_bak"/>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="fieldset-success">
                                <legend class="text-center">Data Pengajuan</legend>
                                <div class="form-group">
                                    <label class="col-md-4">NIK</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static" id="nik"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="  col-md-4">Nama</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static" id="nama_karyawan"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Tanggal absen</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static" id="tanggal_absen"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="  col-md-4">Absen masuk</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static" id="absen_masuk"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="  col-md-4">Absen keluar</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static" id="absen_keluar"></p>
                                    </div>
                                </div>
                                <div class="form-group" id="tanggal_input_div">
                                    <label class="col-md-4">Tanggal input</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static" id="tanggal_input"></p>
                                    </div>
                                </div>
                                <div class="form-group" id="alasan_div">
                                    <label class="  col-md-4">Alasan</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static" id="alasan"></p>
                                    </div>
                                </div>
                                <div class="form-group" id="keterangan_div">
                                    <label class="  col-md-4">Keterangan</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static" id="keterangan"></p>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fieldset-warning">
                                <legend class="text-center">Data Bukti Pembatalan</legend>
                                <div class="form-group">
                                    <label for="gambar_bukti" class="col-md-4">Upload Bukti</label>
                                    <div class="col-md-7 ">
                                        <input type="file" name="gambar_bukti" id="gambar_bukti" class="form-control" required />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="catatan_batal" class="col-md-4">Catatan</label>
                                    <div class="col-md-7 ">
                                        <textarea class="form-control" rows="4"
                                                  name="catatan_batal" id="catatan_batal"
                                                  placeholder="Ketik catatan disini" required
                                        ></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-center">
                <button class="btn btn-warning" type="reset" name="reset_btn">Reset</button>
                <button name="btn-pembatalan" class="btn btn-success" data-action="approve" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>