<div class="modal fade" role="dialog" id="modal-spd-penerimaan" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Form Penerimaan Kedatangan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-penerimaan">
                    <input type="hidden" name="id_travel_header">
                    <input type="hidden" name="id_travel_detail">
                    <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn"
                              style="margin-top: 0">
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
                    <fieldset class="fieldset-4px-rad fieldset-success no-pad-top animated fadeIn">
                        <legend class="no-pad-top"><h4>Detail Perjalanan</h4></legend>
                        <div class="form-group no-padding">
                            <label class="col-md-4">Aktifitas</label>
                            <div class="col-md-6">
                                <p class="form-control-static no-padding" id="label_activity">Activity</p>
                            </div>
                        </div>
                        <div class="form-group no-padding">
                            <label class="col-md-4">Keperluan</label>
                            <div class="col-md-8">
                                <p class="form-control-static no-padding" id="label_keperluan">Activity</p>
                            </div>
                        </div>
                        <div class="form-group no-padding">
                            <label class="col-md-4" for="select-country-single">Tujuan</label>
                            <div class="col-md-6">
                                <p class="form-control-static no-padding" id="label_tujuan">Activity</p>
                            </div>
                        </div>
                        <div class="form-group no-padding">
                            <label class="col-md-4">Tanggal Perjalanan</label>
                            <div class="col-md-4">
                                <p class="form-control-static no-padding">
                                    <small>Berangkat</small>
                                </p>
                                <p class="form-control-static no-padding" id="label_single_start"></p>
                            </div>
                            <div class="col-md-4">
                                <p class="form-control-static no-padding">
                                    <small>Kembali</small>
                                </p>
                                <p class="form-control-static no-padding" id="label_single_end"></p>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fieldset-4px-rad fieldset-success no-pad-top animated fadeIn">
                        <legend>Penjemputan & Penginapan</legend>
                        <div class="form-group">
                            <label class="col-md-4" for="transport_pick">Kendaraan Penjemput</label>
                            <div class="col-md-6">
                                <select name="transport_pick" class="form-control select2">
                                    <?php foreach ($transports as $transport) : ?>
                                        <option value="<?php echo $transport->kode ?>"><?php echo $transport->nama ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mess-input">
                            <label class="col-md-4" for="mess_available">Ketersediaan mess</label>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12 checkbox">
                                        <input type="hidden" name="mess_available" value="0">
                                        <label>
                                            <input type="checkbox" name="mess_available" value="1">&nbsp;Tersedia
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"></div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer text-center">
                <button name="simpan_btn" class="btn btn-success" type="button">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>