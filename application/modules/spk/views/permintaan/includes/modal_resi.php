<div class="modal fade" role="dialog" id="modal-resi">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span id="title">Tambah</span> Resi</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-resi" enctype="multipart/form-data">
                    <input type="hidden" id="id_permintaan" name="id_permintaan"/>
                    <input type="hidden" id="id_resi" name="id_resi"/>
                    <div class="row">
                        <div class="col-md-12"><div class="form-group">
                                <label class="col-md-12">Nama Ekspedisi</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="ekspedisi" name="ekspedisi" placeholder="Ketik Nama Ekspedisi" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Nomor Resi</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="no_resi" name="no_resi" placeholder="Ketik Nomor Resi" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i>&nbsp;Reset</button>
                <button name="save_resi" class="btn btn-success" type="button"><i class="fa fa-save"></i>&nbsp;Save</button>
            </div>
        </div>
    </div>
</div>