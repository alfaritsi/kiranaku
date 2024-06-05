<div class="modal fade" role="dialog" id="modal-review">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Review SPK</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-review" enctype="multipart/form-data">
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
                                <label class="col-md-4">Jenis SPK</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="jenis_spk">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nama SPK</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="nama_spk">-</p>
                                </div>
                            </div>
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
                                <label class="col-md-4">Tanggal Berlaku SPK</label>
                                <div class="col-md-6">
                                    <p class="form-control-static" id="tanggal_berlaku_spk">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Tanggal Berakhir SPK</label>
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
                                <label class="col-md-4">Nomor SPK</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" disabled id="nomor_spk" name="nomor_spk"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-center" id="buttons">
                <button class="btn btn-danger approval" type="button" data-action="decline"><i class="fa fa-times"></i>&nbsp;Decline</button>
                <button class="btn btn-success approval" type="button" data-action="approve"><i class="fa fa-save"></i>&nbsp;Approve
                </button>
            </div>
        </div>
    </div>
</div>