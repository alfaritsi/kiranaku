<div class="modal fade" role="dialog" id="modal-final">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Scan Perjanjian</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-final" enctype="multipart/form-data">
                    <input type="hidden" name="id_spk" id="id_spk">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-4">Plant</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="plant">ABL1</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Jenis Perjanjian</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="jenis_spk">-</p>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label class="col-md-4">Nama Perjanjian</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="nama_spk">-</p>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-md-4">Jenis Vendor</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="jenis_vendor">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Tanggal Perjanjian</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="tanggal_perjanjian">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Tanggal Berlaku Perjanjian</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="tanggal_berlaku_spk">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Tanggal Berakhir Perjanjian</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="tanggal_berakhir_spk">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">SPPKP</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="SPPKP">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nomor Perjanjian</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="nomor_spk">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Tanggal Pengiriman</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control datepicker" id="tanggal_kirim"
                                               name="tanggal_kirim" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nomor Resi</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="no_resi" name="no_resi"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Final Form Perjanjian</label>
                                <div class="col-md-6">
                                    <input type="file" name="dokumen" class="form-control" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-center">
                <button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i>&nbsp;Reset</button>
                <button name="save_final" class="btn btn-success" type="button"><i class="fa fa-save"></i>&nbsp;Save</button>
            </div>
        </div>
    </div>
</div>