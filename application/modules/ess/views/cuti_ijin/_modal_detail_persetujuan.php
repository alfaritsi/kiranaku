<div class="modal fade" role="dialog" id="modal-detail-persetujuan">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detail Pengajuan <span>Cuti</span></h4>
            </div>
            <div class="modal-body no-padding">
                <div id="datepicker-detail"></div>
                <table class="table table-striped no-margin">
                    <tr>
                        <td>
                            <form class="form-horizontal" style="padding:5px;" name="form-detail-persetujuan">
                                <input type="hidden" id="id_cuti" name="id_cuti"/>
                                <div class="form-group">
                                    <label for="inik" class=" col-md-3 col-md-offset-1">NIK</label>
                                    <div class="col-md-7 ">
                                        <span class="form-control-static" id="inik"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inama_karyawan" class=" col-md-3 col-md-offset-1">Nama Karyawan</label>
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
                                    <label for="isaldo" class=" col-md-3 col-md-offset-1">Sisa Saldo</label>
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
                                <div id="div-detail-lampiran" class="form-group hide">
                                    <label for="ilampiran" class=" col-md-3 col-md-offset-1">Lampiran</label>
                                    <div class="col-md-7 ">
                                        <span class="form-control-static" id="ilampiran"><a class="text-primary">Lihat lampiran</a> </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="icatatan" class=" col-md-3 col-md-offset-1">Catatan Atasan</label>
                                    <div class="col-md-7 ">
                                        <textarea class="form-control" rows="4"
                                                  name="catatan" id="icatatan"
                                                  placeholder="Ketik catatan disini"
                                        ></textarea>
                                    </div>
                                </div>
                            </form>

                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer text-center">
                <button name="btn-detail-disapprove" class="btn btn-danger" data-action="disapprove" type="button">Ditolak</button>
                <button name="btn-detail-approve" class="btn btn-success" data-action="approve" type="button">Disetujui</button>
            </div>
        </div>
    </div>
</div>