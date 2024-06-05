<div class="modal fade" role="dialog" id="modal-spk_cancel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">  
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cancel Perjanjian</h4>
            </div>
            <div class="modal-body"> 
                <form class="form-horizontal" id="form-cancel-spk" enctype="multipart/form-data">
                    <input type="hidden" id="id_spk" name="id_spk"/>
                    <input type="hidden" id="status_akhir" name="status_akhir"/> 
                    <div class="row"> 
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-12">Nama Perjanjian</label>
                                <div class="col-md-12">
                                    <p class="form-control-static" id="nama_spk">-</p>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-md-12">Alasan</label>
								<div class="col-md-12">
								<select class="form-control select2" id="alasan" name="alasan" style="width: 100%;" data-placeholder="Pilih Alasan" required>
									<option value=''>Pilih Alasan</option>
									<option value='Pekerjaan dibatalkan'>Pekerjaan dibatalkan</option>
									<option value='Dokumen vendor tidak lengkap'>Dokumen vendor tidak lengkap</option>
									<option value='Perjanjian tidak mendapatkan konfirmasi dari fungsi terkait'>Perjanjian tidak mendapatkan konfirmasi dari fungsi terkait</option>
								</select>
								</div> 
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Keterangan</label>
                                <div class="col-md-12">
									<textarea rows="4" id="keterangan" name="keterangan" class="form-control"
											  placeholder="Ketik keterangan untuk alasan"
											  required></textarea>
                                </div>
                            </div> 
							
							<div align="center">Data Perjanjian dicancel, apakah proses akan dilanjutkan??</div>
                        </div> 
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button name="save_cancel_spk" class="btn btn-success" type="button"><i class="fa fa-delete"></i>&nbsp;Cancel</button>
            </div>
        </div>
    </div>
</div>