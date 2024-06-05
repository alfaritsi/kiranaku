<div class="modal fade" role="dialog" id="modal-upload">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Upload Dokumen</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-upload" enctype="multipart/form-data">
                    <input type="hidden" name="id_spk"/>
                    <input type="hidden" name="id_oto"/>
                    <input type="hidden" name="id_upload"/>
                    <input type="hidden" name="tipe"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-4">Upload</label>
                                <div class="col-md-12">
                                    <input type="file" name="dokumen[]" class="form-control" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i>&nbsp;Reset</button> -->
                <button name="save_upload" class="btn btn-success" type="button"><i class="fa fa-save"></i>&nbsp;Save</button>
            </div>
        </div>
    </div>
</div>