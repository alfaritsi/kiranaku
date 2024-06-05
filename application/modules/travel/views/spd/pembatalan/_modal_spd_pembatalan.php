<div class="modal fade" role="dialog" id="modal-spd-pembatalan" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pembatalan Perjalanan Dinas</h4>
            </div>
            <div class="modal-body no-padding">
                <form class="form-horizontal form-persetujuan">
                    <input type="hidden" name="id_travel_header">
                    <input type="hidden" name="id_travel_cancel">
                    <div class="nav-tabs-custom tab-success margin-bottom-none">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#modal-tab-pembatalan-pengajuan" data-toggle="tab">Pengajuan</a>
                            </li>
                            <li>
                                <a href="#modal-tab-pembatalan-um" data-toggle="tab">Uang Muka</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <fieldset class="fieldset-4px-rad fieldset-primary no-pad-top animated fadeIn" style="padding: 4px 10px;">
                                <div class="row">
                                    <div class="col-md-10">
                                        <hr>
                                    </div>
                                    <div class="col-md-2 text-sm">
                                        <a class="btn btn-xs btn-link btn-block" data-toggle="collapse" data-target="#list-approvals">
                                            Approval&nbsp;<i class="fa fa-bars"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="row collapse" id="list-approvals">
                                    <div class="col-md-12">
                                        <ul class="fa-ul">
                                            <?php foreach ($approval['list_atasan'] as $list) : ?>
                                                <li>
                                                    <i class="fa-li text-success fa fa-user"></i><?php echo $list ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="tab-pane active" id="modal-tab-pembatalan-pengajuan">
                                <?php $this->load->view('pembatalan/_tab_modal_spd_pembatalan_pengajuan') ?>
                            </div>
                            <div class="tab-pane" id="modal-tab-pembatalan-um">
                                <?php $this->load->view('pembatalan/_tab_modal_spd_pembatalan_uangmuka') ?>
                            </div>
                        </div>
                    </div>
                    <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn margin">
                        <legend class="no-pad-top"><h4>Pembatalan</h4></legend>
                        <div class="col-md-12">
                            <div class="hide" id="div-um-kembali">
                                <div class="row">
                                    <div class="form-group no-padding">
                                        <label class="col-md-4" for="label_p_nama">Jumlah Uang Muka</label>
                                        <div class="col-md-8">
                                            <p class="form-control-static no-padding text-right" id="label_total_um">
                                                <span class="label_total_um_jumlah numeric-label">0</span>
                                                <span class="label_total_um_currency"></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group no-padding">
                                        <label class="col-md-4" for="jumlah_kembali">Jumlah dikembalikan</label>
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="text" name="jumlah_kembali"
                                                       id="jumlah_kembali" class="form-control text-right numeric"
                                                       numeric-min="0" value="0"
                                                       placeholder="0">
                                                <span class="input-group-addon label_total_um_currency"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group no-padding">
                                        <label class="col-md-4" for="lampiran">Lampiran bukti</label>
                                        <div class="col-md-8">
                                            <input type="file" name="lampiran"
                                                   id="lampiran" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group no-padding">
                                        <label class="col-md-4" for="lampiran">Batalkan uang muka saja</label>
                                        <div class="col-md-8">
                                            <input type="hidden" name="batal_um_only" value="0">
                                            <input class="icheck" type="checkbox" name="batal_um_only" id="batal_um" value="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="catatan">Catatan</label>
                                    <div class="col-md-8">
                                        <textarea name="catatan" id="catatan"
                                                  required
                                                  class="form-control" rows="4"
                                                  placeholder="Ketik catatan"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-approval btn-success" name="simpan_btn_pembatalan">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>