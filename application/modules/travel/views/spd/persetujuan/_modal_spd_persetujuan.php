<div class="modal fade" role="dialog" id="modal-spd-persetujuan" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Persetujuan Perjalanan Dinas</h4>
            </div>
            <div class="modal-body no-padding">
                <form class="form-horizontal form-persetujuan">
                    <input type="hidden" name="id_travel_header">
                    <input type="hidden" name="approval_type" value="pengajuan">
                    <input type="hidden" name="is_approval_by">
                    <div class="nav-tabs-custom tab-success margin-bottom-none">
                        <ul class="nav nav-tabs">
                            <li class="hide">
                                <a href="#modal-tab-deklarasi" data-toggle="tab">Deklarasi</a>
                            </li>
                            <li class="active">
                                <a href="#modal-tab-pengajuan" data-toggle="tab">Perjalanan Dinas</a>
                            </li>
                            <li>
                                <a href="#modal-tab-um" data-toggle="tab">Uang Muka</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
                                <legend class="no-pad-top"><h4>Personal</h4></legend>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="label_p_nik">NIK</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static no-padding" id="label_p_nik">-</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="label_p_kantor">Kantor / Pabrik</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static no-padding" id="label_p_kantor">-</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="label_p_bagian">Bagian</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static no-padding" id="label_p_bagian">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="label_p_nama">Nama</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static no-padding" id="label_p_nama">-</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="label_p_jabatan">Jabatan</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static no-padding" id="label_p_jabatan">-</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group no-padding">
                                            <label class="col-md-4" for="label_no_hp">No HP</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static no-padding" id="label_no_hp">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="tab-pane" id="modal-tab-deklarasi">
                                <?php $this->load->view('persetujuan/_tab_modal_spd_persetujuan_deklarasi') ?>
                            </div>
                            <div class="tab-pane active" id="modal-tab-pengajuan">
                                <?php $this->load->view('persetujuan/_tab_modal_spd_persetujuan_pengajuan') ?>
                            </div>
                            <div class="tab-pane" id="modal-tab-um">
                                <?php $this->load->view('persetujuan/_tab_modal_spd_persetujuan_uangmuka') ?>
                            </div>
                        </div>
                    </div>
                    <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn margin">
                        <legend class="no-pad-top"><h4>Persetujuan</h4></legend>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="label_p_nama">Catatan</label>
                                    <div class="col-md-8">
                                        <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Ketik catatan persetujuan"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="approval_lampiran_div">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="label_p_nama">Lampiran</label>
                                    <div class="col-md-8">
                                        <input type="file" class="form-control" name="lampiran" id="lampiran" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer text-center">
                <button data-action="revise" type="button" class="btn btn-approval btn-warning">Ask to revise</button>
                <button data-action="approve" type="button" class="btn btn-approval btn-success">Disetujui</button>
                <button data-action="disapprove" type="button" class="btn btn-approval btn-danger">Ditolak</button>
            </div>
        </div>
    </div>
</div>