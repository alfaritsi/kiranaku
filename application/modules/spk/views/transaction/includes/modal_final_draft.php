<div class="modal fade" role="dialog" id="modal-final-draft">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Upload Final Draft Perjanjian</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-final-draft" enctype="multipart/form-data">
                    <input type="hidden" id="id_spk" name="id_spk"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-12">Jenis Perjanjian</label>
                                <div class="col-md-12">
                                    <p class="form-control-static" id="jenis_spk">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Nomor Perjanjian</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="nomor_spk" name="nomor_spk" placeholder="Ketik nomor Perjanjian"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Final Draft Perjanjian</label>
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        <small>
                                            <ol style="padding-inline-start: 10px;">
                                                <li>File lampiran hanya diperbolehkan yang ber ekstensi JPG, JPEG,
                                                    PNG, PDF, ZIP, RAR atau 7z.
                                                </li>
                                                <li>Ukuran file lampiran hanya diperbolehkan maksimal 5MB.</li>
                                            </ol>
                                        </small>
                                    </div>
                                    <input type="file" name="dokumen" class="form-control" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i>&nbsp;Reset</button>
                <button name="save_final_draft" class="btn btn-success" type="button"><i class="fa fa-save"></i>&nbsp;Save</button>
            </div>
        </div>
    </div>
</div>