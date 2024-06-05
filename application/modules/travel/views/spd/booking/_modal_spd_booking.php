<div class="modal fade" role="dialog" id="modal-spd-booking" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Form Transportasi & Penginapan</h3>
            </div>
            <div class="modal-body no-padding">
                <form class="form-horizontal form-booking">
                    <input type="hidden" name="id_travel_header" />
                    <input type="hidden" name="tujuan_trip" id="tujuan_trip" />
                    <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn margin"
                              style="margin-top: 0">
                        <legend class="no-pad-top"><h4>Data Pemesanan</h4></legend>
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
                                    <label class="col-md-4" for="label_p_kantor">Lokasi</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static no-padding" id="label_p_kantor">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="div_p_no_hp">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="label_no_hp">No HP</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static no-padding" id="label_no_hp">-</p>
                                    </div>
                                </div>
                            </div>                           
                            <div class="row" id="div_p_berangkat">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="label_p_berangkat">Berangkat</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static no-padding" id="label_p_berangkat">-</p>
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
                                    <!-- <input class="switch-onoff" type="checkbox" name="condition_fieldname" 
                                        id="dot_fieldname_fieldname{no}" checked data-toggle="toggle" data-size="normal"
                                        data-on="Issued" data-off="Cancel" data-onstyle="success" data-offstyle="danger"> -->
                                </div>
                            </div>
                            <div class="row" id="div_p_jabatan">
                                <div class="form-group no-padding" >
                                    <label class="col-md-4" for="label_p_jabatan">Jabatan</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static no-padding" id="label_p_jabatan">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="div_p_bagian">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="label_p_bagian">Bagian</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static no-padding" id="label_p_bagian">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="div_p_kembali">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="label_p_kembali">Kembali</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static no-padding" id="label_p_kembali">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="div_no_trip">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="label_no_trip">No. Trip</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static no-padding" id="label_no_trip">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="nav-tabs-custom tab-success margin-bottom-none">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#modal-tab-booking-transportasi" data-toggle="tab" style="font-size: 18px;">Transportasi</a>
                            </li>
                            <li>
                                <a href="#modal-tab-booking-penginapan" data-toggle="tab" style="font-size: 18px;">Penginapan</a>
                            </li>
                        </ul>
                        <div class="tab-content" style="background-color: #ecf0f5;">
                            <div class="tab-pane active" id="modal-tab-booking-transportasi">
                                <?php $this->load->view('booking/_tab_modal_spd_booking_transportasi') ?>
                            </div>
                            <div class="tab-pane" id="modal-tab-booking-penginapan">
                                <?php $this->load->view('booking/_tab_modal_spd_booking_penginapan') ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer text-center">
                <input type="hidden" name="availablehotel" id="availablehotel" value="">
                <button name="simpan_btn" class="btn btn-success" type="button">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>