<div class="modal fade" role="dialog" id="modal-spk_drop">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">  
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Drop Perjanjian</h4>
            </div>
            <div class="modal-body"> 
                <form class="form-horizontal" id="form-drop-spk" enctype="multipart/form-data">
                    <input type="hidden" name="id_spk"/>
                    <div class="row"> 
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-12">Jenis Perjanjian</label>
                                <div class="col-md-12">
                                    <p class="form-control-static" name="jenis_spk">-</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Upload Berita Acara</label>
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        <small>
                                            <ol style="padding-inline-start: 10px;"> 
                                                <li>File lampiran hanya diperbolehkan yang ber ekstensi doc, docx, xls, xlsx, pdf
                                                </li>
                                                <li>Ukuran file lampiran hanya diperbolehkan maksimal 5MB.</li>
                                            </ol>
                                        </small>   
                                    </div>
									<input type="file" multiple="multiple" class="form-control" id="gambar" name="gambar[]" required="required">
                                </div>
                            </div>
							<div align="center">Data Perjanjian didrop, apakah proses akan dilanjutkan??</div>
                        </div> 
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button name="save_drop_spk" class="btn btn-success" type="button"><i class="fa fa-delete"></i>&nbsp;Drop</button>
            </div>
        </div>
    </div>
</div>