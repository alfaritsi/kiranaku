<div class="modal fade" role="dialog" id="modal-detail-so-item" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Surat Perintah Kerja</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="fieldset-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6"><strong>Pabrik pemesan</strong></div>
                                        <div class="col-md-6" id="plant">ABL1</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><strong>Sales Order</strong></div>
                                        <div class="col-md-6" id="no_so">SO#100002</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><strong>Purchase Order</strong></div>
                                        <div class="col-md-6" id="no_po">SO#100002</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6"><strong>No PI</strong></div>
                                        <div class="col-md-6" id="no_pi">SO#100002</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-7"><strong>Material Number</strong></div>
                                        <div class="col-md-5 no_mat">MCH</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7"><strong>Req Delivery Date</strong></div>
                                        <div class="col-md-5" id="tanggal_req_delivery">01.01.2019</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7"><strong>Plan Delivery Date</strong></div>
                                        <div class="col-md-5" id="tanggal_plan_delivery">01.01.2019</div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                    </div>
                </div>
                <fieldset class="fieldset-success">
                    <legend>Jadwal Produksi</legend>
                    <form class="form-horizontal" id="form-buat-spk">
                        <div class="form-group">
                            <label class="col-md-4 text-right control-label">Material Number</label>
                            <span class="col-md-6 form-control-static no_mat">MCH</span>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 text-right control-label">Schedule Produksi</label>
                            <div class="col-md-6">
                                <div class="input-group col-md-12 date" data-js="datepicker">
                                    <input class="form-control tgl_awal_akhir" readonly type="text"
                                           name="start" id="start" required>
                                    <label class="input-group-addon" for="tanggal-awal_filter">-</label>
                                    <input class="form-control tgl_awal_akhir" readonly type="text"
                                           name="end" id="end" required>
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 text-right control-label">Qty Produksi</label>
                            <div class="col-md-4">
                                <div class="input-group col-md-12">
                                    <input type="number" class="form-control" name="qty" id="qty" min="1"/>
                                    <input type="hidden" name="uom"/>
                                    <div class="input-group-addon uom">PCS</div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive table-bordered" id="table-history-spk">
                                <thead>
                                <tr>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th class="text-center">Qty Produksi</th>
                                    <th>UoM</th>
                                    <th>No IO</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                <th colspan="2" class="text-right">Total</th>
                                <th class="text-center" id="total">0</th>
                                <th colspan="2" class="uom">Total</th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 text-center lihat-spk">
                        <button class="btn btn-danger" type="reset" data-dismiss="modal">Tutup</button>
                    </div>
                    <div class="col-md-6 col-md-offset-3 text-center buat-spk hide">
                        <button class="btn btn-danger" type="reset" data-dismiss="modal">Batal</button>
                        <button class="btn btn-success btn-spk" type="submit">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>