<!-- Modal PM action -->
<div class="modal fade" id="perbaikan_modal" data-backdrop="static" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="col-sm-12">
                <div class="nav-tabs-custom" id="tabs-edit">
                    <form role="form" class="form-transaksi-perbaikan" name="form-transaksi-perbaikan"
                          enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Perbaikan Kerusakan</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="label_no_aset">No Aset</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <span id="label_no_aset" class="form-control-static">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="label_nama_pabrik">Pabrik</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <span id="label_nama_pabrik"
                                              class="form-control-static">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="label_nama_sub_lokasi">Sub Lokasi</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <span id="label_nama_sub_lokasi" class="form-control-static">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="label_nama_area">Area</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <span id="label_nama_area" class="form-control-static">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="label_nama_pic">Pengguna</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <span id="label_nama_pic" class="form-control-static">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="label_jadwal_service">Jadwal Service</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <span id="label_jadwal_service" class="form-control-static">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="jenis_tindakan">Jenis Maintenance</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <input type="hidden" id="jenis_tindakan" name="jenis_tindakan"
                                               value="perbaikan">
                                        <span class="form-control-static">Perbaikan kerusakan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="tanggal_rusak">Tanggal rusak</label>
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input class="form-control tanggal" readonly name="tanggal_rusak"
                                                   id="tanggal_rusak">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="tanggal_mulai">Tanggal mulai</label>
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input class="form-control tanggal" required readonly name="tanggal_mulai"
                                                   id="tanggal_mulai">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="tanggal_mulai">Tanggal selesai</label>
                                    </div>
                                    <div class="col-xs-5">
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input class="form-control tanggal" required readonly name="tanggal_selesai"
                                                   id="tanggal_selesai">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="tanggal_mulai">Catatan</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <textarea required class="form-control" rows="4" name="catatan_service"
                                                  placeholder="Ketik catatan kerusakan"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group div_items hide">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <fieldset class="fieldset-info">
                                            <legend class="text-center">Item Perbaikan </legend>
                                            <div class="pull-right">
                                                <a href="javascript:void(0);" id="add_perbaikan_item" class="btn btn-xs btn-success">Tambah Item</a>
                                            </div>
                                            <table class="table table-responsive" id="table-perbaikan-item">
                                                <thead>
                                                <th>Item</th>
                                                <th>Keterangan</th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="id_main" name="id_main">
                            <button type="button" class="btn btn-primary" name="action_btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>