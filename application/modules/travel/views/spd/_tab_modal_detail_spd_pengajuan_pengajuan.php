<fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn" style="display: none;">
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
<fieldset class="fieldset-4px-rad fieldset-success no-pad-top">
    <legend class="no-pad-top"><h4>Detail Perjalanan</h4></legend>
    <div id="btn-info-approval-detail" class="legend2 btn">
        <!-- <p class="form-control-static"> -->
            <a id="popApprovalDetail" class="pull-right text-info" role="button"
                data-toggle="popover" title='Info Approval' data-placement="left">
                
                <i class="fa fa-info-circle"></i>

            </a>
        <table id="template-approval-detail" class="hidden table table-striped text-sm" style="min-width:200px;">
            <thead>
            <!-- <th>Nama</th>             -->
            </thead>
            <tbody></tbody>
        </table>
        <!-- </p> -->
    </div>
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
                        <td width="25%">
                            <p class="form-control-static no-padding label_multi_keperluan"></p>
                        </td>
                        <td width="15%">
                            <p class="form-control-static no-padding label_multi_start"></p>
                        </td>
                        <td width="30%">
                            <p class="form-control-static no-padding label_multi_trans"></p>
                        </td>
                    </tr>
                </script>
                <table id="table-detail-multi-trip"
                       class="table table-responsive table-bordered table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Tujuan</th>
                        <th>Keperluan</th>
                        <th>Keberangkatan</th>
                        <th>Transportasi dan penginapan</th>
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
<fieldset class="fieldset-4px-rad fieldset-info no-pad-top" style="display: none">
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
                    <input class="icheck" type="checkbox" checked
                           disabled
                           id="label_booking_brgkt" value="1">
                </div>
            </div>
            <div class="row">
                <label class="col-md-6" for="label_booking_kembali">Dipesankan tiket kembali&nbsp;</label>
                <div class="col-md-4">
                    <input class="icheck" type="checkbox"
                           disabled
                           id="label_booking_kembali" value="1">
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
<fieldset id="fieldset-pembatalan" class="fieldset-4px-rad fieldset-danger no-pad-top animated fadeIn hide">
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
                        <p class="form-control-static no-padding text-right" id="label_jumlah_kembali">
                            <span class="label_jumlah_kembali_jumlah numeric-label">0</span>
                            <span class="label_total_um_currency"></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group no-padding">
                    <label class="col-md-4" for="lampiran">Lampiran bukti</label>
                    <div class="col-md-8">
                        <a class="btn btn-default btn-xs"><i class="fa fa-search"></i>&nbsp;Lihat lampiran</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group no-padding">
                    <label class="col-md-4" for="lampiran">Batalkan uang muka saja</label>
                    <div class="col-md-8">
                        <input class="icheck" type="checkbox"
                               id="label_batal_um">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group no-padding">
                <label class="col-md-4" for="catatan">Catatan</label>
                <div class="col-md-8">
                    <p class="form-control-static no-padding" id="label_catatan"></p>
                </div>
            </div>
        </div>
    </div>
</fieldset>