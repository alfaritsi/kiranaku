<fieldset class="fieldset-4px-rad fieldset-success no-pad-top">
    <legend class="no-pad-top"><h4>Detail Perjalanan</h4></legend>
    <div class="form-group no-padding">
        <label class="col-md-4">Aktifitas</label>
        <div class="col-md-6">
            <p class="form-control-static no-padding" id="label_activity">Activity</p>
        </div>
    </div>
    <div class="form-group no-padding">
        <label class="col-md-4">Perjalanan</label>
        <div class="col-md-6">
            <p class="form-control-static no-padding" id="label_tipe_trip">Activity</p>
        </div>
    </div>
    <div id="div-single-trip" class="animated fadeIn hide">
        <div class="form-group no-padding">
            <label class="col-md-4">Keperluan</label>
            <div class="col-md-8">
                <p class="form-control-static no-padding" id="label_keperluan">Activity</p>
            </div>
        </div>
        <div class="form-group no-padding">
            <label class="col-md-4" for="select-country-single">Tujuan</label>
            <div class="col-md-6">
                <p class="form-control-static no-padding margin-bottom" id="label_tujuan">Activity</p>
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
    </div>
    <div id="div-multi-trip" class="animated fadeIn hide">
        <div class="row">
            <div class="col-md-12">
                <script type="text/template" id="detail_multitrip_template">
                    <tr class="template-trip">
                        <td width="5%">
                            <p class="form-control-static no-padding label_multi_no"></p>
                        </td>
                        <td width="25%">
                            <p class="form-control-static no-padding label_multi_tujuan"></p>
                        </td>
                        <td width="15%">
                            <p class="form-control-static no-padding label_multi_start"></p>
                        </td>
                        <td width="35%">
                            <p class="form-control-static no-padding label_multi_keperluan"></p>
                        </td>
                    </tr>
                </script>
                <table id="table-cancel-multi-trip"
                       class="table table-responsive table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tujuan</th>
                        <th>Keberangkatan</th>
                        <th>Keperluan</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-4">
                <label>Jadwal kembali</label>
            </div>
            <div class="col-md-3">
                <p class="form-control-static no-padding" id="label_multi_end"></p>
            </div>
        </div>
    </div>
</fieldset>
<fieldset class="fieldset-4px-rad fieldset-info no-pad-top">
    <legend class="no-pad-top"><h4>Transportasi & Penginapan</h4></legend>
    <div class="form-group no-padding">
        <label class="col-md-4">Transportasi</label>
        <ol class="col-md-6" id="label_transportasi"></ol>
    </div>
    <div class="form-group no-padding">
        <div class="col-md-8 col-md-offset-4">
            <div class="row margin-bottom">
                <label class="col-md-6" for="label_booking_brgkt">Dipesankan tiket berangkat&nbsp;</label>
                <div class="col-md-4">
                    <input class="icheck" type="checkbox" id="label_booking_brgkt" value="1">
                </div>
            </div>
            <div class="row">
                <label class="col-md-6" for="label_booking_kembali">Dipesankan tiket kembali&nbsp;</label>
                <div class="col-md-4">
                    <input class="icheck" type="checkbox" id="label_booking_kembali" value="1">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group no-padding">
        <label class="col-md-4">Penginapan</label>
        <div class="col-md-4">
            <p class="form-control-static no-padding" id="label_jenis_penginapan"></p>
        </div>
    </div>
</fieldset>