<div class="modal fade" role="dialog" id="modal-download">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Download Template</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-download" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-4">Jenis Perjanjian</label>
                                <div class="col-md-12">
                                    <select id="id_jenis_spk_d" name="id_jenis_spk" class="select2" data-placeholder="Pilih jenis Perjanjian">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i>&nbsp;Reset</button>
                <button id="btn_download" class="btn btn-success" type="button"><i class="fa fa-save"></i>&nbsp;Download</button>
            </div>
        </div>
    </div>
</div>