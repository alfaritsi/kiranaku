<div class="modal fade" role="dialog" id="modal-approval">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Approval Permintaan Dokumen</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-approval" enctype="multipart/form-data">
                    <input type="hidden" name="id_permintaan" id="id_permintaan">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-4">Nama Dokumen</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="nama_permintaan_dok">ABL1</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nomor Dokumen</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="nomor_dokumen">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Keperluan</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="keperluan">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Keterangan</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="keterangan">-</p>
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