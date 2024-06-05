<div class="modal fade" role="dialog" id="modal-detail-bak-persetujuan">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Berita Acara Kehadiran</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" name="form-detail-bak-persetujuan">
                    <input type="hidden" id="id_bak" name="id_bak"/>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="fieldset-warning">
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
                                    <label class="  col-md-4">Absen masuk</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static" id="absen_masuk">
                                            <span class="tanggal"></span>
                                            <span class="jam"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="  col-md-4">Absen keluar</label>
                                    <div class="col-md-3">
                                        <p class="form-control-static" id="absen_keluar">
                                            <span class="tanggal"></span>
                                            <span class="jam"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group" id="tanggal_input_div">
                                    <label class="col-md-4">Tanggal input</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static" id="tanggal_input"></p>
                                    </div>
                                </div>
                                <div class="form-group hide" id="alasan_div">
                                    <label class="  col-md-4">Alasan</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static" id="alasan"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="  col-md-4">Keterangan</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static" id="keterangan"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Status</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static" id="status"></p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="icatatan" class="col-md-4">Catatan Atasan</label>
                                    <div class="col-md-7 ">
                                        <textarea class="form-control" rows="4"
                                                  name="catatan" id="icatatan"
                                                  placeholder="Ketik catatan disini"
                                        ></textarea>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-center">
                <button name="btn-detail-disapprove" class="btn btn-danger" data-action="disapprove" type="button">Ditolak</button>
                <button name="btn-detail-approve" class="btn btn-success" data-action="approve" type="button">Disetujui</button>
            </div>
        </div>
    </div>
</div>