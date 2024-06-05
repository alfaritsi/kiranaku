<div class="modal fade" role="dialog" id="modal-batal-cutiijin" xmlns="http://www.w3.org/1999/html">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pembatalan Pengajuan <span>Cuti</span></h4>
            </div>
            <div class="modal-body no-padding">
                <div id="datepicker-detail"></div>
                <table class="table table-striped no-margin">
                    <tr>
                        <td>
                            <form class="form-horizontal" style="padding:5px;" name="form-batal-cutiijin">
                                <input type="hidden" id="id_cuti" name="id_cuti"/>
                                <div class="form-group">
                                    <label for="inik" class=" col-md-3 col-md-offset-1">NIK</label>
                                    <div class="col-md-7 ">
                                        <span class="form-control-static" id="inik"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inama_karyawan" class=" col-md-3 col-md-offset-1">Nama
                                        Karyawan</label>
                                    <div class="col-md-7 ">
                                        <span class="form-control-static" id="inama_karyawan"></span>
                                    </div>
                                </div>
                                <div class="form-group" id="row-ijenis">
                                    <label for="inama_jenis" class=" col-md-3 col-md-offset-1">Jenis</label>
                                    <div class="col-md-7">
                                        <span class="form-control-static" id="inama_jenis"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ijumlah" class=" col-md-3 col-md-offset-1">Jumlah Hari</label>
                                    <div class="col-md-7 ">
                                        <span class="form-control-static" id="ijumlah"></span>
                                    </div>
                                </div>
                                <div class="form-group" id="row-isaldo">
                                    <label for="isaldo" class=" col-md-3 col-md-offset-1">Saldo saat ini</label>
                                    <div class="col-md-7 ">
                                        <span class="form-control-static" id="isaldo"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ialasan" class=" col-md-3 col-md-offset-1">Catatan</label>
                                    <div class="col-md-7 ">
                                        <span class="form-control-static" id="ialasan"></span>
                                    </div>
                                </div>
                                <fieldset class="fieldset-warning">
                                    <legend class="text-center">Data Bukti Pembatalan</legend>
                                    <div class="form-group">
                                        <label for="gambar_bukti" class="col-md-4">Upload Bukti</label>
                                        <div class="col-md-7 ">
                                            <input type="file" name="gambar_bukti" id="gambar_bukti"
                                                   class="form-control" required/>
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
                            </form>

                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer text-center">
                <button class="btn btn-warning" type="reset" name="reset_btn">Reset</button>
                <button name="btn-pembatalan" class="btn btn-success" data-action="approve" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>