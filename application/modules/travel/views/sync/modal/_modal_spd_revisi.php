<div class="modal fade" role="dialog" id="modal-spd-revisi" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Revisi Nomor Perjalanan Dinas</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal form-persetujuan">
                            <input type="hidden" name="id_travel_header">
                            <input type="hidden" name="id_travel_cancel">
                                                            
                            <div class="form-group no-padding">
                                <label class="col-md-4">Nomor Trip Sebelumnya </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control input-xxlarge "
                                        name="nomor_fieldname" id="nomor_fieldname" 
                                        style="width: 100%;"  required="required" 
                                        readonly="readonly">
                                </div>
                            </div>
                            <div class="form-group no-padding">
                                <label class="col-md-4">Revisi Nomor Trip </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control input-xxlarge "
                                        name="nomor_baru_fieldname" id="nomor_baru_fieldname" 
                                        style="width: 100%;"  required="required">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-approval btn-success" name="simpan_btn_revisi" id="simpan_btn_revisi" >Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                <!-- <input type="text" id="id_hide" name="id_hide" value="0"> -->
            </div>
        </div>
    </div>
</div>