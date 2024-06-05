<!-- Modal PM tambah schedule -->
<div class="modal fade" id="add_modal_perbaikan" data-backdrop="static" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="col-sm-12">
                <div class="nav-tabs-custom">
                    <form role="form" class="form-transaksi-jadwal-perbaikan" name="form-transaksi-jadwal-perbaikan"
                          enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Tambah Jadwal Perbaikan</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="kategori">Aset</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <select class="form-control" name="id_aset"
                                                id="id_aset_ajax" required="required"
                                                data-placeholder="Cari aset">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="kategori">Operator</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <select class="form-control select2modal" name="operator"
                                                id="operator"
                                                data-placeholder="Pilih operator atau Random" data-allow-clear="true">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="jadwal_service">Tanggal service</label>
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input class="form-control tanggal" required readonly name="jadwal_service" id="jadwal_service">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="kategori">Catatan</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <textarea name="catatan" id="catatan" rows="4" class="form-control"
                                                  placeholder="Ketik catatan"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="kode" />
                            <input type="hidden" name="id_jenis" />
                            <button type="button" class="btn btn-primary" name="action_btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>