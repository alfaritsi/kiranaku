<div class="modal fade" role="dialog" id="modal-keterangan">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span id="title">Tambah</span> Keterangan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-keterangan" enctype="multipart/form-data">
                    <input type="hidden" id="id_pengembalian" name="id_pengembalian"/>
                    <input type="hidden" id="id_keterangan" name="id_keterangan"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-12">Keterangan</label>
                                <div class="col-md-12">
                                    <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i>&nbsp;Reset</button>
                <button name="save_keterangan" class="btn btn-success" type="button"><i class="fa fa-save"></i>&nbsp;Save
                </button>
            </div>
        </div>
    </div>
</div>