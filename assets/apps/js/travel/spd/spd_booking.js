$(document).ready(function () {
    const modalBooking = $('#modal-spd-booking');
    const modalBookingDetail = $('#modal-detail-spd-booking');

    const datetimepickerOptions = {
        useCurrent: false,
        format: 'DD.MM.YYYY HH:mm',
        showTodayButton: true,
        sideBySide: true,
        ignoreReadonly: true,

        showClose: true,
        showClear: true,
        toolbarPlacement: 'top',

        widgetPositioning: {
            horizontal: 'left',
            vertical: 'top'
        },
        // debug: true
    };
    const datepickerOptions = {
        useCurrent: false,
        format: 'DD.MM.YYYY',
        showTodayButton: true,
        sideBySide: true,
        ignoreReadonly: true,

        showClose: true,
        showClear: true,
        toolbarPlacement: 'top',

        widgetPositioning: {
            horizontal: 'left',
            vertical: 'top'
        },
        // debug: true
    };

    // declare table refund
    $("#table-refund").DataTable({
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        'columnDefs': [
            {
                'targets': 0,
                'checkboxes': {
                    'selectRow': true,
                    'selectAllPages': false,
                    'selectCallback': function () {

                    }
                }
            }
        ],
        'select': {
            'style': 'multi'
        },
        "drawCallback": function () {
            $("#table-refund tr").each(function () {
                if ($(this).find("td:eq(7)").text() == "Sudah direfund") {
                    $($(this)).find("td:eq(0)").find("input[type='checkbox']").remove();
                }

            });
        },
        'order': [[1, 'asc']],
        "oSearch": { "sSearch": $("#table-refund").attr('data-search') }
    });

    $(document).on("click", "#btn-refund-spd", function (e) {
        e.preventDefault();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            let chosenCatatans = [];
            $.each($('#table-refund').DataTable().column(0).checkboxes.selected(), function (i, value) {
                chosenCatatans.push({
                    id: value,
                    // catatan: catatans[value]
                });
            });
            if (chosenCatatans == "") {
                swal({
                    title: "Tidak ada data yang disinkronisasi.",
                    type: 'info'
                });
            } else {

                $.ajax({
                    url: baseURL + 'travel/booking/save/refund',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        approvals: JSON.stringify(chosenCatatans)
                    },
                    beforeSend: function () {
                        var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                        $("body .overlay-wrapper").append(overlay);
                    },
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                            location.reload();
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                        swal('Error', 'Server error. Mohon ulangi proses.', 'error');
                    },
                    complete: function (data) {
                        //proces spiner
                        $("body .overlay-wrapper .overlay").remove();
                    }
                });
            }
        } else {
            swal({
                title: "Silahkan tunggu sampai proses selesai.",
                type: 'info'
            });
        }
        return false;
    });

    const templatePesawatx = '<div id="template-transport-pesawat{no}" >'
        + '    <div class="box transport-booking transport-pesawat animated fadeIn">'
        + '        <div class="box-header with-border">'
        + '            <h4 class="box-title">Transportasi Pesawat'
        + '                <span id="span_status_tiket{no}" class="select_tiket"><input data-width="59px" class="switch-onoff select-status-tiket" type="checkbox" name="transport[{no}][status_tiket]" '
        + '                    id="status_tiket{no}" checked data-toggle="toggle" data-size="mini" data-baris="{no}"'
        + '                    data-on="Issued" data-off="Cancel" data-onstyle="success" data-offstyle="danger">'
        + '                  </span>'
        + '              </h4>'
        + '            <h5 class="text-muted transport-tujuan"></span>'
        + '                <span class="transport-jadwal-perjalanan_pesawat{no}"></span>'
        + '                <span class="pull-right transport-jadwal-keberangkatan_pesawat{no}"></span>'
        + '            </h5>'

        + '            <div class="box-tools pull-right">  '

        + '                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>'
        + '                </button>'
        + '                <button type="button" class="btn btn-box-tool text-danger transport_remove_btn">'
        + '                            <i class="fa fa-trash"></i></button>'
        + '            </div>'
        + '        </div>'
        + '        <div class="box-body">'
        + '            <input disabled type="hidden" name="transport[{no}][id_travel_transport]" class="transport-id">'
        + '            <input disabled type="hidden" name="transport[{no}][jenis_kendaraan]" value="pesawat">'
        + '            <div class="form-group no-padding div_perjalanan" >'
        + '                <label class="col-md-4" for="transport[{no}][id_travel_detail]">Perjalanan</label>'
        + '                <div class="col-md-8">'
        + '                    <select disabled class="select-perjalanan form-control" name="transport[{no}][id_travel_detail]">'
        + '                    </select>'
        + '                </div>'
        + '            </div>'
        + '            <div class="form-group no-padding">'
        + '                <label class="col-md-4" for="keberangkatan">Keberangkatan</label>'
        + '                <div class="col-md-8">'
        + '                    <div class="input-group transport_jadwal">'
        + '                        <input disabled readonly name="transport[{no}][jadwal]" type="text"'
        + '                               placeholder="Pilih jadwal"'
        + '                               required class="form-control">'
        + '                        <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>'
        + '                    </div>'
        + '                </div>'
        + '            </div>'
        + '            <div class="form-group no-padding">'
        + '                <label class="col-md-4" for="keberangkatan">Maskapai</label>'
        + '                <div class="col-md-8 divvendor" id="divvendor{no}">'

        + '                    <select id="select-vendor-pesawat{no}" name="transport[{no}][vendor]"'
        + '                            class="transport-vendor"'
        + '                            required>'

        + '                    </select>'

        + '              </div>'
        + '            </div>'
        + '            <div class="form-group no-padding">'
        + '                <label class="col-md-4" for="keberangkatan">Tiket</label>'
        + '                <div class="col-md-4">'
        + '                    <input disabled name="transport[{no}][no_tiket]" type="text"'
        + '                           placeholder="Ketik no tiket"'
        + '                            class="form-control transport-no_tiket">'
        + '                </div>'
        + '                <div class="col-md-4">'
        + '                    <div class="input-group">'
        + '                        <input disabled name="transport[{no}][harga]" type="text"'
        + '                               placeholder="Harga tiket"'
        + '                                class="form-control text-right numeric transport-harga">'
        + '                        <span class="input-group-addon">IDR</span>'
        + '                    </div>'
        + '                </div>'
        + '            </div>'
        + '            <div class="form-group no-padding">'
        + '                <label class="col-md-4" for="keberangkatan">Status refund</label>'
        + '                <div class="col-md-8 divrefund" id="divrefund{no}">'
        + '                    <select id="select-refund-pesawat{no}" name="transport[{no}][status_tiket_refund]"'
        + '                            class="transport-refund select2 form-control"'
        + '                            required>'
        + '                      <option value="Refundable">Bisa direfund</option>'
        + '                      <option value="Unrefundable">Tidak bisa direfund</option>'
        + '                    </select>'
        + '                     <input name="transport[{no}][status_tiket_primary]" type="hidden"'
        + '                           class="form-control transport-status_tiket_primary" readonly>'
        + '              </div>'
        + '            </div>'
        + '            <div class="form-group no-padding">'
        + '                <label class="col-md-4" for="keberangkatan">Lampiran</label>'
        + '                <div class="col-md-8" >'
        + '                    <div class="fileinput fileinput-new" id="fileinput_{no}" data-provides="fileinput">'
        + '                        <div class="btn-group btn-sm no-padding">'
        + '                            <a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox><i class="fa fa-search"></i></a>'
        + '                            <a class="btn btn-facebook btn-file">'
        + '                                <div class="fileinput-new">Attachment</div>'
        + '                                <div class="fileinput-exists">'
        + '                                    <i class="fa fa-edit"></i>'
        + '                                </div>'
        + '                                <input disabled name="transport[{no}][lampiran]" type="file"'
        + '                                   placeholder="Pilih lampiran tiket"'
        + '                                   required class="form-control transport-lampiran">'

        + '                            </a> '
        + '                            <a href="#" class="btn btn-pinterest fileinput-exists"data-dismiss="fileinput">'
        + '                                <i class="fa fa-trash"></i>'
        + '                            </a>'
        + '                        </div>'
        + '                    </div>'

        + '                </div>'
        + '            </div>'
        + '            <div class="form-group no-padding">'
        + '                <label class="col-md-4" for="keberangkatan">Keterangan</label>'
        + '               <div class="col-md-8">'
        + '                    <textarea disabled name="transport[{no}][keterangan]"'
        + '                              id="transport_{no}_keterangan"'
        + '                              placeholder="Ketik keterangan transportasi"'
        + '                              class="form-control transport-keterangan"></textarea>'
        + '                </div>'
        + '            </div>'
        + '            <div id="div_alasan_cancel{no}" class="form-group no-padding" style="display:none">'
        + '                <label class="col-md-4" for="keberangkatan">Alasan cancel</label>'
        + '               <div class="col-md-8">'
        + '                    <textarea disabled name="transport[{no}][alasan_cancel]"'
        + '                              id="transport_{no}_alasan_cancel"'
        + '                              placeholder="Ketik keterangan cancel"'
        + '                              class="form-control transport-keterangan_cancel"></textarea>'
        + '                </div>'
        + '            </div>'
        + '        </div>'
        + '    </div>'
        + '</div>';
    const templateTaxix = '<div id="template-transport-taxi{no}" >'
        + '    <div class="box transport-booking transport-taxi animated fadeIn">'
        + '        <div class="box-header with-border">'
        + '            <h4 class="box-title">Transportasi Taksi</h4>'
        + '              <span id="span_status_tiket{no}" class="select_tiket" style="display:none">'
        + '                <input data-width="59px"  class="switch-onoff" type="checkbox" name="transport[{no}][status_tiket]" '
        + '                    id="status_tiket{no}" checked data-toggle="toggle" data-size="mini" '
        + '                    data-on="Issued" data-off="Cancel" data-onstyle="success" data-offstyle="danger">'
        + '                  </span>'
        + '            <h5 class="text-muted transport-tujuan">'
        + '                <span class="transport-jadwal-perjalanan_taxi{no}"></span>'
        + '                <span class="pull-right transport-jadwal-keberangkatan_taxi{no}"></span>'
        + '            </h5>'
        + '            <div class="box-tools pull-right">'
        + '                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>'
        + '                </button>'
        + '                <button type="button" class="btn btn-box-tool text-danger transport_remove_btn"><i'
        + '                            class="fa fa-trash"></i></button>'
        + '            </div>'
        + '        </div>'
        + '        <div class="box-body">'
        + '            <input disabled type="hidden" name="transport[{no}][id_travel_transport]" class="transport-id">'
        + '            <input disabled type="hidden" name="transport[{no}][jenis_kendaraan]" value="taxi">'
        + '            <div class="form-group no-padding div_perjalanan" >'
        + '                <label class="col-md-4" for="transport[{no}][id_travel_detail]">Perjalanan</label>'
        + '                <div class="col-md-8">'
        + '                    <select disabled class="select-perjalanan form-control" name="transport[{no}][id_travel_detail]">'
        + '                        <option>Kembali</option>'
        + '                    </select>'
        + '                </div>'
        + '            </div>'
        + '            <div class="form-group no-padding">'
        + '                <label class="col-md-4" for="keberangkatan">Taksi</label>'
        + '                <div class="col-md-8 divvendor" id="divvendor{no}">'

        + '                    <select id="select-vendor-taxi{no}" name="transport[{no}][vendor]"'
        + '                            class="form-control transport-vendor"'
        + '                             required>'

        + '                    </select>'
        + '                </div>'
        + '            </div>'
        + '            <div class="form-group no-padding">'
        + '                <label class="col-md-4" for="keberangkatan">Voucher</label>'
        + '                <div class="col-md-4">'
        + '                    <input disabled name="transport[{no}][no_tiket]" type="text"'
        + '                           placeholder="Ketik no voucher"'
        + '                           required class="form-control transport-no_tiket">'
        + '                </div>'
        + '            </div>'
        + '            <div class="form-group no-padding div_lampiran" class="hide">'
        + '                <label class="col-md-4" for="keberangkatan">Lampiran</label>'
        + '                <div class="col-md-8">'

        + '                    <div class="fileinput fileinput-new" id="fileinput_{no}" data-provides="fileinput">'
        + '                        <div class="btn-group btn-sm no-padding">'
        + '                            <a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox><i class="fa fa-search"></i></a>'
        + '                            <a class="btn btn-facebook btn-file">'
        + '                                <div class="fileinput-new">Attachment</div>'
        + '                                <div class="fileinput-exists">'
        + '                                    <i class="fa fa-edit"></i>'
        + '                                </div>'
        + '                                <input disabled name="transport[{no}][lampiran]" type="file"'
        + '                                   placeholder="Pilih lampiran tiket"'
        + '                                    class="form-control transport-lampiran">'

        + '                            </a> '
        + '                            <a href="#" class="btn btn-pinterest fileinput-exists"data-dismiss="fileinput">'
        + '                                <i class="fa fa-trash"></i>'
        + '                            </a>'
        + '                        </div>'
        + '                    </div>'
        + '                </div>'
        + '            </div>'
        + '            <div class="form-group no-padding">'
        + '                <label class="col-md-4" for="keberangkatan">Keterangan</label>'
        + '                <div class="col-md-8">'
        + '                    <textarea disabled name="transport[{no}][keterangan]"'
        + '                              id="transport_{no}_keterangan"'
        + '                              placeholder="Ketik keterangan transportasi"'
        + '                              class="form-control transport-keterangan"></textarea>'
        + '                </div>'
        + '            </div>'
        + '        </div>'
        + '    </div>'
        + '</div>';

    let transportNo = 0;
    $(document).on('click', '.transport_add_btns', function (e, data) { // outstanding
        e.preventDefault();
        var x = ($('#tujuan_trip').val()).slice(0, -1);

        const transportType = $(this).data('type');
        const transportList = $('#transport-list', modalBooking);
        let newTransport = null;
        var transportNo = parseInt($('#count_field').val()) + 1;
        if (transportType === 'taxi') {
            let template = $('#template-transport-taxi', modalBooking).html();
            template = template.replaceAll('{no}', transportNo);
            newTransport = $(template);
        } else if (transportType === 'pesawat') {
            let template = $('#template-transport-pesawat', modalBooking).html();
            template = template.replaceAll('{no}', transportNo);
            newTransport = $(template);
        }

        if (newTransport) {
            $(':disabled', newTransport).attr('disabled', false);
            $('.transport_jadwal', newTransport).datetimepicker(datetimepickerOptions);
            $('.select-perjalanan', newTransport).select2();
            $('.numeric', newTransport).each(function (i, el) {
                KIRANAKU.convertNumeric(el);
            });

            transportList.append(newTransport);

            var kembali = $('#label_p_kembali').html();
            kembali = moment(kembali);

            var transx = x.split("~");
            var option_dt = "";
            var data_idopt = "";
            var data_dateopt = "";
            $.each(transx, function (i, val) {
                var splitval = val.split('|');
                if (jQuery.inArray("kembali", splitval) == -1) {
                    option_dt += "<option data-jadwal='" + splitval[1] + "' value=" + splitval[0] + ">"
                        + splitval[2]
                        + "</option>";
                    data_idopt = splitval[0];
                    data_dateopt = splitval[1];
                }
            })
            option_dt += "<option data-jadwal='" + data_dateopt + "' value='" + data_idopt + "_kembali'>Kembali</option>";
            $('select[name="transport[' + (transportNo) + '][id_travel_detail]"]', modalBooking)
                .append(option_dt)
                .val($('select[name="transport[' + (transportNo) + '][id_travel_detail]"]', modalBooking).val())
                .trigger("change.select2");
            $('select[name="transport[' + (transportNo) + '][status_tiket_refund]"]', modalBooking).select2();
            $('input[name="transport[' + (transportNo) + '][status_tiket_primary]"]', modalBooking).val("secondary");
            $('input[name="transport[' + (transportNo) + '][status_tiket]"]', modalBooking).val("on");
            $.ajax({
                url: baseURL + 'travel/spd/get/vendor_transport',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    transport: "pesawat"
                },
                success: function (data) {
                    var option_tr = "";
                    var trans = "";
                    $.each(data.data, function (x, vendor) {
                        $.each(vendor, function (k, dt) {
                            option_tr += "<option value=" + dt.kode_merk + ">" + dt.merk + "</option>";
                        });
                    });

                    trans = "lion";
                    $('select[name="transport[' + (transportNo) + '][vendor]"]', modalBooking)
                        .append(option_tr)
                        .val(trans)
                        .trigger("change.select2");
                    $('select[name="transport[' + (transportNo) + '][vendor]"]', modalBooking)
                        .select2();
                },
                error: function (data) {
                    KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                },

            });
            var kembali = $('#label_p_kembali').html();
            kembali = (moment(kembali)).format('DD.MM.YYYY HH:mm');
            $('.transport-jadwal-perjalanan_pesawat' + (transportNo)).html("Kembali");
            $('.transport-jadwal-keberangkatan_pesawat' + (transportNo)).html(kembali);
            $('input[name="transport[' + (transportNo) + '][jadwal]"]').val(kembali);
        }
        $('#count_field').val(transportNo);
    });

    let penginapanNo = 0;
    $(document).on('click', '.penginapan_add_btn', function (e) {
        e.preventDefault();
        const penginapanType = $(this).data('type');
        const penginapanList = $('#penginapan-list', modalBooking);
        let newPenginapan = null;
        if (penginapanType === 'hotel') {
            let template = $('#template-penginapan-hotel', modalBooking).html();
            template = template.replaceAll('{no}', penginapanNo++);
            newPenginapan = $(template);
        }
        if (newPenginapan) {
            $(':disabled', newPenginapan).attr('disabled', false);
            $('.penginapan_start_date, .penginapan_end_date', newPenginapan).datetimepicker(datetimepickerOptions);
            $('.select-perjalanan', newPenginapan).select2();

            penginapanList.append(newPenginapan);
        }
    });

    $(document).on('click', '.transport_remove_btn, .penginapan_remove_btn', function (e) {
        e.preventDefault();
        const box = $(this).parents('.box');
        const idTransport = box.find('.transport-id').val();
        var transportNo = $('#count_field').val();

        if (idTransport) {
            KIRANAKU.confirm({
                successCallback: function () {
                    box.remove();
                }
            })
        } else {
            box.remove();
        }
    });

    $(document).on('click', '.spd-booking', function (e) {
        e.preventDefault();
        const modal = modalBooking;
        const idHeader = $(this).data('id');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/booking',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idHeader
            },
            success: function (data) {

                var transportNo = 0;
                var transNo = 0;
                $('#button-add-trans').show();
                $('#button-add-inn').show();
                if (data.sts === 'OK') {
                    const { pengajuan, details, personel, transports, hotels, transport_pesawat, transport_taxi } = data.data;
                    const transportasi = pengajuan.transportasi.split(',');

                    // add ayy 4 set hiden hotel
                    const hotelhd = pengajuan.jenis_penginapan.split(',');
                    var availablehotel = false;
                    if (jQuery.inArray('Hotel', hotelhd) != -1) {
                        $('#availablehotel').val(true);
                        availablehotel = true;
                    } else {
                        $('#availablehotel').val(false);
                        availablehotel = false;
                    }
                    $('input[name="id_travel_header"]', modal).val(pengajuan.id_travel_header);

                    /** Detail personel */
                    $('#label_no_hp', modal).html(pengajuan.no_hp);
                    $('#label_p_nik', modal).html(personel.nik);
                    $('#label_p_nama', modal).html(personel.nama);
                    $('#label_no_trip', modal).html(pengajuan.no_trip);
                    let kantor = '';
                    let bagian = '';
                    if (personel.ho === 'y') {
                        kantor = 'Head Office';
                        if (personel.nama_departemen == null) {
                            bagian = personel.nama_divisi;
                        } else {
                            bagian = personel.nama_departemen;
                        }
                    } else {
                        kantor = personel.nama_pabrik;
                        if ((personel.nama_seksi == null) && (personel.nama_sub_divisi == null)) {
                            bagian = personel.nama_pabrik;
                        } else if (personel.nama_seksi == null) {
                            bagian = personel.nama_departemen;
                        } else {
                            bagian = personel.nama_seksi;
                        }
                    }
                    $('#label_p_kantor', modal).html(kantor);
                    $('#label_p_bagian', modal).html(bagian);
                    $('#label_p_jabatan', modal).html(personel.posst);

                    $('.transport_jadwal', modal).datetimepicker(datetimepickerOptions);

                    $('#label_p_berangkat', modal).html(pengajuan.start_date);
                    $('#label_p_kembali', modal).html(pengajuan.end_date);
                    // ayy aktifkan
                    $('#div_p_berangkat').hide();
                    $('#div_p_kembali').hide();
                    $('#div_p_jabatan').hide();
                    $('#div_p_no_hp').hide();
                    $('#div_p_bagian').hide();

                    $('.div_lampiran').hide();
                    /** Opsi default combobox perjalanan */
                    $('.select-perjalanan', modalBooking)
                        .html('');
                    $('.transport-vendor', modalBooking)
                        .html('');

                    /** Transport List manipulasi */
                    let templatePesawat = $('#divtransport', modalBooking);
                    let templateTaxi = $('#template-transport-taxi', modalBooking);
                    const transportList = $('#transport-list', modalBooking);
                    $('.transport-pesawat, .transport-taxi', transportList)
                        .remove();
                    // =========================================================transport==============
                    if (transports.length > 0) {
                        $('#transport-list').html("");
                        // add ayy
                        $('#ul_list_transport').html('');
                        var list_ul_transport = "";
                        var trans_arr = [];
                        var transNo = 0;
                        var isback = true;
                        templatePesawat.html('');
                        var data_tujuan_hidden = "";
                        $.each(transports, function (i, transport) {
                            let template = null;
                            var templatePesawaty = templatePesawatx.replace(/{no}/g, transportNo);
                            templatePesawaty = templatePesawaty.replace(/disabled/g, " ");
                            var templateTaxiy = templateTaxix.replace(/{no}/g, transportNo);
                            templateTaxiy = templateTaxiy.replace(/disabled/g, " ");
                            var option_tr = "";
                            var trans = "";

                            if (transport.jenis_kendaraan === 'pesawat') {
                                template = templatePesawaty;

                                $.each(transport_pesawat, function (x, vendor) {
                                    option_tr += "<option value=" + vendor.kode_merk + ">" + vendor.merk + "</option>";
                                })
                                trans = "lion";
                            } else if (transport.jenis_kendaraan === 'taxi') {
                                template = templateTaxiy;
                                $.each(transport_taxi, function (x, vendor) {
                                    option_tr += "<option value=" + vendor.kode_merk + ">" + vendor.merk + "</option>";
                                })
                                trans = "bluebird";
                            }

                            // membatasi button tambah tranportasi
                            if (jQuery.inArray(transport.jenis_kendaraan, trans_arr) == -1) {
                                trans_arr.push(transport.jenis_kendaraan);
                                if (transport.jenis_kendaraan === 'pesawat') {
                                    list_ul_transport += '<li><a href="#" class="transport_add_btn" data-type="pesawat"><i class="fa fa-plane"></i> <span class="pull-right">Pesawat</span></a></li>';
                                } else if (transport.jenis_kendaraan === 'taxi') {
                                    list_ul_transport += '<li><a href="#" class="transport_add_btn" data-type="taxi"><i class="fa fa-taxi"></i> <span class="pull-right">Taksi</span></a></li>';
                                }
                            }
                            transportList.append(template);
                            $('.switch-onoff').each(function () {
                                $(this).closest(".toggle").css('min-width', $(this).data("width"));
                            })

                            $('.transport-lampiran').prop('required', false);
                            $('.transport_remove_btn').addClass('hide');

                            $('input[name="transport[' + transNo + '][no_tiket]"]').val(transport.no_tiket);
                            $('input[name="transport[' + transNo + '][harga]"]').val(transport.harga);
                            $('#transport_' + transNo + '_keterangan').html(transport.keterangan);

                            $('#template-transport-pesawat' + transportNo).addClass('collapsed-box');
                            $('.box-body').css('display', 'none');
                            $('.fa-minus')
                                .addClass('fa-plus')
                                .removeClass('fa-minus');

                            // =======================================================add ayy=========
                            var found_array = $.grep(details, function (v) {
                                return v.id_travel_detail === transport.id_travel_detail;
                            });
                            $.each(found_array, function (o, fa) {
                                trans = transport.vendor;
                                var startdt_trip = moment(fa.start_date);
                                var tujuan_trip = fa.tujuan_lengkap;
                                var startdt_trippc = "";
                                var datetime = "";

                                if (transport.transport_kembali == 0) {
                                    tujuan_trip = fa.tujuan_lengkap;
                                } else {
                                    tujuan_trip = "kembali";
                                }
                                startdt_trip = moment(transport.tanggal);
                                datetime = new Date(transport.tanggal + " " + transport.jam);
                                // var mindate         = startdt_trip;
                                startdt_trippc = moment(datetime).format("DD.MM.YYYY HH:mm");
                                const dtoptionprop = {
                                    minDate: startdt_trip,

                                    useCurrent: false,
                                    format: 'DD.MM.YYYY HH:mm',
                                    showTodayButton: true,
                                    sideBySide: true,
                                    ignoreReadonly: true,

                                    showClose: true,
                                    showClear: true,
                                    toolbarPlacement: 'top',

                                    widgetPositioning: {
                                        horizontal: 'left',
                                        vertical: 'top'
                                    },
                                    // debug: true
                                };
                                $('.transport-jadwal-keberangkatan_' + transport.jenis_kendaraan + '' + transNo).html(startdt_trippc);
                                $('.transport-jadwal-perjalanan_' + transport.jenis_kendaraan + '' + transNo).html(tujuan_trip);
                                $('input[name="transport[' + transNo + '][jadwal]"]')
                                    .datetimepicker(dtoptionprop)
                                    .val(startdt_trippc)
                                    .trigger('change');

                                if (transport.transport_kembali == 1) {
                                    var id_detailx = fa.id_travel_detail + '_kembali';
                                    var tujuanx = "kembali";
                                    var option = new Option("kembali", id_detailx, false, false);
                                } else {
                                    var id_detailx = fa.id_travel_detail;
                                    var tujuanx = fa.tujuan_lengkap;
                                    var option = new Option(fa.tujuan_lengkap, fa.id_travel_detail, false, false);
                                }

                                option.setAttribute('data-jadwal', fa.tanggal_berangkat);
                                $('select[name="transport[' + transNo + '][id_travel_detail]"]', modalBooking)
                                    .append(option)
                                    .val(id_detailx).trigger('change.select2');
                                // get data for hidden tujuan all    
                                data_tujuan_hidden += id_detailx + "|" + fa.tanggal_berangkat + "|" + tujuanx + "~"


                                // lampiran
                                if (transport.lampiran != "" && transport.lampiran != null) {
                                    var existfile = false;
                                    if (transport.lampiran.match(/(.jpg|.png|.pdf|.zip|.jpeg)/)) { existfile = true; } else { existfile = false; }
                                    if (existfile == false) {
                                        let divFileinput = $('#fileinput');
                                        divFileinput.removeClass('fileinput-exists');
                                        divFileinput.addClass('fileinput-new');
                                        divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
                                    } else {
                                        var href_det = transport.lampiran != null ? baseURL + 'assets/file/travel/' + transport.lampiran : 'javascript:void(0);';

                                        let divFileinput = $('#fileinput_' + transNo);
                                        divFileinput.removeClass('fileinput-new');
                                        divFileinput.addClass('fileinput-exists');
                                        divFileinput.find('.fileinput-zoom').attr('href', baseURL + 'assets/file/travel/' + transport.lampiran);
                                        divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
                                        if (href_det.match(/(.zip)/)) {
                                            divFileinput.find('.fileinput-zoom').removeAttr('data-fancybox');
                                        }
                                        $('.btn-file').show();
                                    }
                                }
                                $('select[name="transport[' + transNo + '][vendor]"]', modalBooking)
                                    .append(option_tr)
                                    .val(trans)
                                    .trigger("change.select2");
                                $('select[name="transport[' + transNo + '][vendor]"]', modalBooking).select2();
                                $('input[name="transport[' + transNo + '][id_travel_transport]"]').val(transport.id_travel_transport);

                                $('input[name="transport[' + transNo + '][status_tiket]"]').prop('checked', true).change();
                                if (transport.status_tiket != "") {
                                    if (transport.status_tiket == "Issued") {
                                        $('input[name="transport[' + transNo + '][status_tiket]"]').bootstrapToggle('on');
                                    } else {
                                        $('input[name="transport[' + transNo + '][status_tiket]"]').bootstrapToggle('off');
                                    }
                                } else {
                                    $('input[name="transport[' + transNo + '][status_tiket]"]').bootstrapToggle('on');
                                }
                                $('select[name="transport[' + transNo + '][status_tiket_refund]"]', modalBooking).
                                    val(transport.status_tiket_refund).
                                    trigger("change");
                                var status_primary = transport.status_tiket_primary == null ? "primary" : transport.status_tiket_primary;
                                $('input[name="transport[' + transNo + '][status_tiket_primary]"]', modalBooking).
                                    val(status_primary);
                                $('textarea[name="transport[' + transNo + '][alasan_cancel]"]', modalBooking).
                                    html(transport.alasan_cancel);
                                if (transport.no_tiket == "") {
                                    $("#span_status_tiket" + transNo).hide();
                                } else {
                                    $("#span_status_tiket" + transNo).show();
                                }
                                transNo++;
                            });

                            // =======================================================add ayy=========
                            transportNo++;
                        });

                        $('#tujuan_trip', modal).val(data_tujuan_hidden);

                        $('#ul_list_transport').append(list_ul_transport);
                        $('.switch-onoff').bootstrapToggle();
                    } else {
                        $('#transport-list').html("");
                        /** Create default transportasi */
                        const pilihPesawat = transportasi.some(function (t) {
                            return t === 'pesawat';
                        });
                        const pilihTaxi = transportasi.some(function (t) {
                            return t === 'taxi';
                        });
                        let newTransport = null;

                        // add ayy
                        $('#ul_list_transport').html('');
                        var list_ul_transport = "";
                        var trans_arr = [];

                        var transNo = 0;
                        var isback = true;
                        var id_det = 0;
                        var transportNo = 0;
                        $.each(details, function (i, det) {
                            var trs = det.transportasi + '';
                            var trans_detail = (trs).split(',');

                            $.each(trans_detail, function (i, tr) {
                                let template = null;
                                if (tr == "pesawat" || tr == "taxi") {
                                    if (jQuery.inArray(tr, trans_arr) == -1) {
                                        trans_arr.push(tr);
                                        if (tr === 'pesawat') {
                                            list_ul_transport += '<li><a href="#" class="transport_add_btn" data-type="pesawat"><i class="fa fa-plane"></i> <span class="pull-right">Pesawat</span></a></li>';
                                        } else if (tr === 'taxi') {
                                            list_ul_transport += '<li><a href="#" class="transport_add_btn" data-type="taxi"><i class="fa fa-taxi"></i> <span class="pull-right">Taksi</span></a></li>';
                                        }
                                    }
                                    var option_tr = "";
                                    var trans = "";
                                    // if looping data
                                    var templatePesawaty = templatePesawatx.replace(/{no}/g, transportNo);
                                    templatePesawaty = templatePesawaty.replace(/disabled/g, " ");
                                    var templateTaxiy = templateTaxix.replace(/{no}/g, transportNo);
                                    templateTaxiy = templateTaxiy.replace(/disabled/g, " ");

                                    if (tr === 'pesawat') {
                                        template = templatePesawaty;
                                        $.each(transport_pesawat, function (x, vendor) {
                                            option_tr += "<option value=" + vendor.kode_merk + ">" + vendor.merk + "</option>";
                                        })
                                        trans = "lion";

                                    } else if (tr === 'taxi') {
                                        template = templateTaxiy;
                                        $.each(transport_taxi, function (x, vendor) {
                                            option_tr += "<option value=" + vendor.kode_merk + ">" + vendor.merk + "</option>";
                                        })
                                        trans = "bluebird";
                                    }

                                    transportList.append(template);

                                    var startdt_trip = moment(det.start_date);
                                    var tujuan_trip = det.tujuan_lengkap;
                                    var datetime = new Date(det.start_date + " " + det.start_time);
                                    var startdt_trippc = moment(datetime).format("DD.MM.YYYY HH:mm");

                                    const dtoptionprop = {
                                        minDate: startdt_trip,

                                        useCurrent: false,
                                        format: 'DD.MM.YYYY HH:mm',
                                        showTodayButton: true,
                                        sideBySide: true,
                                        ignoreReadonly: true,

                                        showClose: true,
                                        showClear: true,
                                        toolbarPlacement: 'top',

                                        widgetPositioning: {
                                            horizontal: 'left',
                                            vertical: 'top'
                                        },
                                        // debug: true
                                    };

                                    $('.switch-onoff').each(function () {
                                        $(this).closest(".toggle").css('min-width', $(this).data("width"));
                                    })

                                    $('.transport-lampiran').prop('required', false);
                                    $('.transport_remove_btn').addClass('hide');

                                    $('#template-transport-pesawat' + transportNo).addClass('collapsed-box');
                                    $('.box-body').css('display', 'none');
                                    $('.fa-minus')
                                        .addClass('fa-plus')
                                        .removeClass('fa-minus');

                                    // =============================================

                                    $('.transport-jadwal-keberangkatan_' + tr + '' + transNo).html(startdt_trippc);
                                    $('.transport-jadwal-perjalanan_' + tr + '' + transNo).html(tujuan_trip);

                                    $('input[name="transport[' + transNo + '][jadwal]"]', transportList)
                                        .datetimepicker(dtoptionprop)
                                        .val(startdt_trippc)
                                        .trigger('change');

                                    // set tujuan dropdown   
                                    let option = new Option(det.tujuan_lengkap, det.id_travel_detail, false, false);
                                    option.setAttribute('data-jadwal', det.tanggal_berangkat);
                                    $('select[name="transport[' + transNo + '][id_travel_detail]"]', modalBooking)
                                        .append(option)
                                        .val(det.id_travel_detail)
                                        .trigger('change.select2');

                                    // set maskapai dropdown 
                                    $('select[name="transport[' + transNo + '][vendor]"]', modalBooking)
                                        .append(option_tr)
                                        .val(trans)
                                        .trigger("change.select2");

                                    var status_primary = "primary";
                                    $('input[name="transport[' + transNo + '][status_tiket_primary]"]', modalBooking).
                                        val(status_primary);
                                    $('textarea[name="transport[' + transNo + '][alasan_cancel]"]', modalBooking).
                                        html("");
                                    //hide status
                                    $("#span_status_tiket" + transNo).hide();

                                    id_det = det.id_travel_detail;
                                    transNo++;
                                }

                            });
                            transportNo++;

                            var tkt = det.transportasi_tiket + '';
                            var tiket_pulang = (tkt).split(',');
                            id_det = id_det;

                            if (isback && tiket_pulang.length > 1) {
                                var kembali = $('#label_p_kembali').html();
                                kembali = moment(kembali);
                                let template = templatePesawat.clone();
                                $(':disabled', template).prop('disabled', false);
                                template = template.html().replaceAll('{no}', transportNo++);
                                newTransport = $(template);
                                transportList.append(newTransport);
                                /** Opsi kembali */
                                let option = new Option('Kembali', id_det + '_kembali', false, false);
                                option.setAttribute('data-jadwal', kembali);
                                $('select[name="transport[' + transNo + '][id_travel_detail]"]', modalBooking)
                                    .append(option);
                                isback = false;

                                var dtoptionprop = {
                                    useCurrent: false,
                                    format: 'DD.MM.YYYY HH:mm',
                                    showTodayButton: true,
                                    sideBySide: true,
                                    ignoreReadonly: true,

                                    showClose: true,
                                    showClear: true,
                                    toolbarPlacement: 'top',

                                    widgetPositioning: {
                                        horizontal: 'left',
                                        vertical: 'top'
                                    },
                                    // debug: true
                                };
                                $('input[name="transport[' + transNo + '][jadwal]"]')
                                    .datetimepicker(dtoptionprop)
                                    .val(kembali.format('DD.MM.YYYY'))
                                    .trigger('change');
                                $('.transport-jadwal-keberangkatan_pesawat' + transNo).html(kembali.format('DD.MM.YYYY'));
                                $('.transport-jadwal-perjalanan_pesawat' + transNo).html("Kembali");

                                var option_tr = "";
                                $.each(transport_pesawat, function (x, vendor) {
                                    option_tr += "<option value=" + vendor.kode_merk + ">" + vendor.merk + "</option>";
                                });
                                $('select[name="transport[' + transNo + '][vendor]"]', modalBooking)
                                    .append(option_tr)
                                    .val("lion")
                                    .trigger("change.select2");
                            }

                        });
                        $('#ul_list_transport').append(list_ul_transport);
                        $('.switch-onoff').bootstrapToggle();
                    }

                    // set tombol tambah transportasi
                    var list_ul_transport = "";
                    $('#ul_list_transport').html("");
                    list_ul_transport += '<li><a href="#" class="transport_add_btn" data-type="pesawat"><i class="fa fa-plane"></i> <span class="pull-right">Pesawat</span></a></li>';
                    list_ul_transport += '<li><a href="#" class="transport_add_btn" data-type="taxi"><i class="fa fa-taxi"></i> <span class="pull-right">Taksi</span></a></li>';
                    $('#ul_list_transport').append(list_ul_transport);

                    // ===============================================Penginapan list manipulasi =========
                    let templateHotel = $('#template-penginapan-hotel', modalBooking);
                    const penginapanList = $('#penginapan-list', modalBooking);
                    $('.penginapan-hotel', penginapanList)
                        .remove();

                    $('#div-add-penginapan', modalBooking).removeClass('hide');
                    $('#div-no-penginapan', modalBooking).addClass('hide');

                    if (hotels.length > 0) {
                        $.each(hotels, function (i, hotel) {
                            let template = templateHotel.clone();

                            $(':disabled', template).prop('disabled', false);
                            template = template.html().replaceAll('{no}', penginapanNo++);
                            const newPenginapan = $(template);

                            $('.select-perjalanan', newPenginapan)
                                .val(hotel.id_travel_detail)
                                .trigger('change');

                            const checkIn = moment(hotel.start_date);
                            $('.penginapan_start_date input', newPenginapan)
                                .val(checkIn.format('DD.MM.YYYY'));
                            const checkOut = moment(hotel.end_date);
                            $('.penginapan_end_date input', newPenginapan)
                                .val(checkOut.format('DD.MM.YYYY'));

                            $('.penginapan-id', newPenginapan).val(hotel.id_travel_hotel);
                            $('.penginapan-nama_hotel', newPenginapan).val(hotel.nama_hotel);
                            $('.penginapan-alamat', newPenginapan).val(hotel.alamat);
                            $('.penginapan-pic_hotel', newPenginapan).val(hotel.PIC_hotel);
                            $('.penginapan-lampiran', newPenginapan).prop('required', false);
                            $('.penginapan-keterangan', newPenginapan).val(hotel.keterangan);

                            penginapanList.append(newPenginapan);
                        });

                        $('.penginapan_start_date, .penginapan_end_date', penginapanList)
                            .datetimepicker(datepickerOptions)
                            .trigger('change');
                        var startdt_trip = moment(hotel.start_date);
                        var tujuan_trip = hotel.tujuan_lengkap;
                        $('.penginapan-jadwal-keberangkatan' + '' + transNo).html(startdt_trip.format('DD.MM.YYYY'));
                        $('.penginapan-jadwal-perjalanan' + '' + transNo).html(tujuan_trip);
                        transNo++;
                    } else {
                        var transNo = 0;
                        var isback = true;
                        $.each(details, function (i, det) {
                            var penginapan_detail = (det.jenis_penginapan).split(',');
                            if (jQuery.inArray('Hotel', penginapan_detail) == -1) {
                                let template = templateHotel.clone();
                                $(':disabled', template).prop('disabled', false);
                                template = template.html().replaceAll('{no}', transNo);
                                const newPenginapan = $(template);
                                penginapanList.append(newPenginapan);
                                // set tujuan dropdown   
                                let option = new Option(det.tujuan_lengkap, det.id_travel_detail, false, false);
                                option.setAttribute('data-jadwal', det.tanggal_berangkat);
                                $('select[name="penginapan[' + penginapanNo + '][id_travel_detail]"]', modalBooking)
                                    .append(option);
                            }
                            var startdt_trip = moment(det.start_date);
                            var dtoptionprop = {
                                minDate: startdt_trip,

                                useCurrent: false,
                                format: 'DD.MM.YYYY HH:mm',
                                showTodayButton: true,
                                sideBySide: true,
                                ignoreReadonly: true,

                                showClose: true,
                                showClear: true,
                                toolbarPlacement: 'top',

                                widgetPositioning: {
                                    horizontal: 'left',
                                    vertical: 'top'
                                },
                                // debug: true
                            };

                            $('.penginapan_start_date, .penginapan_end_date', penginapanList)
                                .datetimepicker(dtoptionprop)
                                .trigger('change');
                            var startdt_trip = moment(det.start_date);
                            var tujuan_trip = det.tujuan_lengkap;
                            $('.penginapan-jadwal-keberangkatan' + '' + transNo).html(startdt_trip.format('DD.MM.YYYY'));
                            $('.penginapan-jadwal-perjalanan' + '' + transNo).html(tujuan_trip);
                            transNo++;
                        });
                    }

                    // remove required form
                    if (availablehotel == false) {
                        $('#modal-tab-booking-penginapan').find(':input', ':select').prop('required', true).removeAttr("required");
                    }

                    $('.select-perjalanan, .transport-vendor, .transport-refund', transportList)
                        .select2()
                        .trigger('change.select2');

                    $('.select-perjalanan', penginapanList)
                        .select2()
                        .trigger('change.select2');



                    KIRANAKU.convertNumericLabel('#modal-spd-booking .numeric-label');
                    KIRANAKU.convertNumeric('#modal-spd-booking .numeric:not([readonly])');
                    $('#count_field').val(transNo);
                    modal.modal('show');
                } else {
                    KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                }
                KIRANAKU.hideLoading();
            },
            complete: function (data) {

            },
            error: function (data) {
                KIRANAKU.hideLoading();
                KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
            }
        });
    });

    $(document).on('click', '.spd-detail-transport', function (e) {
        e.preventDefault();
        const modal = modalBooking;
        const idHeader = $(this).data('id');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/booking',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idHeader
            },
            success: function (data) {
                var transportNo = 0;
                var transNo = 0;
                $('#button-add-trans').hide();
                $('#button-add-inn').hide();
                if (data.sts === 'OK') {
                    const { pengajuan, details, personel, transports, hotels, transport_pesawat, transport_taxi } = data.data;
                    const transportasi = pengajuan.transportasi.split(',');
                    // add ayy 4 set hiden hotel
                    const hotelhd = pengajuan.jenis_penginapan.split(',');
                    var availablehotel = false;
                    if (jQuery.inArray('Hotel', hotelhd) != -1) {
                        $('#availablehotel').val(true);
                        availablehotel = true;
                    } else {
                        $('#availablehotel').val(false);
                        availablehotel = false;
                    }
                    $('input[name="id_travel_header"]', modal).val(pengajuan.id_travel_header);

                    /** Detail personel */
                    $('#label_no_hp', modal).html(pengajuan.no_hp);
                    $('#label_p_nik', modal).html(personel.nik);
                    $('#label_p_nama', modal).html(personel.nama);
                    $('#label_no_trip', modal).html(pengajuan.no_trip);
                    let kantor = '';
                    let bagian = '';
                    if (personel.ho === 'y') {
                        kantor = 'Head Office';
                        if (personel.nama_departemen == null) {
                            bagian = personel.nama_divisi;
                        } else {
                            bagian = personel.nama_departemen;
                        }
                    } else {
                        kantor = personel.nama_pabrik;
                        if ((personel.nama_seksi == null) && (personel.nama_sub_divisi == null)) {
                            bagian = personel.nama_pabrik;
                        } else if (personel.nama_seksi == null) {
                            bagian = personel.nama_departemen;
                        } else {
                            bagian = personel.nama_seksi;
                        }
                    }
                    $('#label_p_kantor', modal).html(kantor);
                    $('#label_p_bagian', modal).html(bagian);
                    $('#label_p_jabatan', modal).html(personel.posst);

                    $('.transport_jadwal', modal).datetimepicker(datetimepickerOptions);

                    $('#label_p_berangkat', modal).html(pengajuan.start_date);
                    $('#label_p_kembali', modal).html(pengajuan.end_date);
                    $('#div_p_berangkat').hide();
                    $('#div_p_kembali').hide();
                    $('#div_p_jabatan').hide();
                    $('#div_p_no_hp').hide();
                    $('#div_p_bagian').hide();

                    /** Opsi default combobox perjalanan */
                    $('.select-perjalanan', modalBooking)
                        .html('');

                    /** Transport List manipulasi */
                    let templatePesawat = $('#template-transport-pesawat', modalBooking);
                    let templateTaxi = $('#template-transport-taxi', modalBooking);
                    const transportList = $('#transport-list', modalBooking);
                    $('.transport-pesawat, .transport-taxi', transportList)
                        .remove();
                    // =========================================================transport==============
                    if (transports.length > 0) {
                        var transNo = 0;
                        var isback = true;
                        $.each(transports, function (i, transport) {
                            let template = null;
                            var option_tr = "";
                            var trans = "";
                            if (transport.jenis_kendaraan === 'pesawat') {
                                template = templatePesawat.clone();
                                $.each(transport_pesawat, function (x, vendor) {
                                    option_tr += "<option value=" + vendor.kode_merk + ">" + vendor.merk + "</option>";
                                })
                                trans = "lion";
                            } else if (transport.jenis_kendaraan === 'taxi') {
                                template = templateTaxi.clone();
                                $.each(transport_taxi, function (x, vendor) {
                                    option_tr += "<option value=" + vendor.kode_merk + ">" + vendor.merk + "</option>";
                                })
                                trans = "bluebird";
                            }
                            $(':disabled', template).prop('disabled', false);
                            template = template.html().replaceAll('{no}', transportNo++);
                            const newTransport = $(template);
                            const jadwal = moment(transport.tanggal + " " + transport.jam);
                            $('.transport_jadwal input', newTransport)
                                .val(jadwal.format('DD.MM.YYYY HH:mm'));
                            $('.transport-id', newTransport).val(transport.id_travel_transport);
                            $('.transport-vendor', newTransport).val(transport.vendor);
                            $('.transport-no_tiket', newTransport).val(transport.no_tiket);
                            $('.transport-harga', newTransport).val(parseFloat(transport.harga));
                            $('.transport-lampiran', newTransport).prop('required', false);
                            $('.transport-keterangan', newTransport).val(transport.keterangan);

                            $('.transport_remove_btn', newTransport).addClass('hide');

                            $('input,select,textarea', newTransport)
                                .prop('disabled', true)
                                .trigger('change');
                            $('.btn-file').hide();

                            newTransport.addClass('collapsed-box');
                            $('.box-body', newTransport).css('display', 'none');
                            $('.fa-minus', newTransport)
                                .addClass('fa-plus')
                                .removeClass('fa-minus');

                            transportList.append(newTransport);
                            // =======================================================add ayy=========
                            var found_array = $.grep(details, function (v) {
                                return v.id_travel_detail === transport.id_travel_detail;
                            });
                            $.each(found_array, function (i, fa) {
                                trans = transport.vendor;
                                var startdt_trip = moment(fa.start_date);
                                var datetime = new Date(fa.start_date + " " + fa.start_time);
                                var startdt_trippc = moment(datetime).format("DD.MM.YYYY HH:mm");
                                var tujuan_trip = fa.tujuan_lengkap;
                                const dtoptionprop = {
                                    minDate: startdt_trip,

                                    useCurrent: false,
                                    format: 'DD.MM.YYYY HH:mm',
                                    showTodayButton: true,
                                    sideBySide: true,
                                    ignoreReadonly: true,

                                    showClose: true,
                                    showClear: true,
                                    toolbarPlacement: 'top',

                                    widgetPositioning: {
                                        horizontal: 'left',
                                        vertical: 'top'
                                    },
                                    // debug: true
                                };
                                $('.transport-jadwal-keberangkatan_' + transport.jenis_kendaraan + '' + transNo).html(startdt_trippc);
                                $('.transport-jadwal-perjalanan_' + transport.jenis_kendaraan + '' + transNo).html(tujuan_trip);
                                $('input[name="transport[' + transNo + '][jadwal]"]', transportList)
                                    .datetimepicker(dtoptionprop)
                                    .trigger('change');

                                // set tujuan dropdown   
                                let option = new Option(fa.tujuan_lengkap, fa.id_travel_detail, false, false);
                                option.setAttribute('data-jadwal', fa.tanggal_berangkat);
                                $('select[name="transport[' + transNo + '][id_travel_detail]"]', modalBooking)
                                    .append(option);

                                let option2 = new Option('Kembali', fa.id_travel_detail + '_kembali', false, false);
                                option2.setAttribute('data-jadwal', pengajuan.tanggal_kembali);
                                $('select[name="transport[' + transNo + '][id_travel_detail]"]', modalBooking)
                                    .append(option2);

                                if (transport.transport_kembali == 1) {
                                    var id_detailx = fa.id_travel_detail + '_kembali';
                                } else {
                                    var id_detailx = fa.id_travel_detail;
                                }

                                $('select[name="transport[' + transNo + '][id_travel_detail]"]').val(id_detailx).trigger('select2.change')
                                if (transport.lampiran != "" && transport.lampiran != null) {
                                    var existfile = false;
                                    if (transport.lampiran.match(/(.jpg|.png|.pdf|.zip|.jpeg)/)) { existfile = true; } else { existfile = false; }
                                    if (existfile == false) {
                                        let divFileinput = $('#fileinput');
                                        divFileinput.removeClass('fileinput-exists');
                                        divFileinput.addClass('fileinput-new');
                                        divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
                                    } else {
                                        var href_det = transport.lampiran != null ? baseURL + 'assets/file/travel/' + transport.lampiran : 'javascript:void(0);';

                                        let divFileinput = $('#fileinput_' + transNo);
                                        divFileinput.removeClass('fileinput-new');
                                        divFileinput.addClass('fileinput-exists');
                                        divFileinput.find('.fileinput-zoom').attr('href', baseURL + 'assets/file/travel/' + transport.lampiran);
                                        divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
                                        if (href_det.match(/(.zip)/)) {
                                            divFileinput.find('.fileinput-zoom').removeAttr('data-fancybox');
                                        }
                                    }

                                }
                                $('select[name="transport[' + transNo + '][vendor]"]', modalBooking)
                                    .append(option_tr)
                                    .val(trans)
                                    .trigger("change.select2");
                                $('select[name="transport[' + transNo + '][vendor]"]').select2();

                                if (transport.status_tiket == "Issued" && transport.no_tiket != "") {
                                    var csslabel = "label-success";
                                    var statx = "Issued";
                                } else if (transport.status_tiket == "Cancel" && transport.no_tiket != "") {
                                    var csslabel = "label-danger";
                                    var statx = "Cancel";
                                } else {
                                    var csslabel = "";
                                    var statx = "";
                                }
                                $('.status_ticket_label' + transNo).addClass(csslabel);
                                $('.status_ticket_label' + transNo).html(statx);

                                transNo++;
                            });
                            // =======================================================add ayy=========
                        });

                        $('.transport_jadwal', transportList)
                            .datetimepicker(datetimepickerOptions)
                            .trigger('change');
                    } else {
                        $('#transport-list').html("");
                        /** Create default transportasi */
                        const pilihPesawat = transportasi.some(function (t) {
                            return t === 'pesawat';
                        });
                        const pilihTaxi = transportasi.some(function (t) {
                            return t === 'taxi';
                        });
                        let newTransport = null;

                        // add ayy
                        $('#ul_list_transport').html('');
                        var list_ul_transport = "";
                        var trans_arr = [];

                        var transNo = 0;
                        var isback = true;
                        var id_det = 0;
                        $.each(details, function (i, det) {
                            var trans_detail = (det.transportasi).split(',');

                            $.each(trans_detail, function (i, tr) {
                                if (jQuery.inArray(tr, trans_arr) == -1) {
                                    trans_arr.push(tr);
                                    if (tr === 'pesawat') {
                                        list_ul_transport += '<li><a href="#" class="transport_add_btn" data-type="pesawat"><i class="fa fa-plane"></i> <span class="pull-right">Pesawat</span></a></li>';
                                    } else if (tr === 'taxi') {
                                        list_ul_transport += '<li><a href="#" class="transport_add_btn" data-type="taxi"><i class="fa fa-taxi"></i> <span class="pull-right">Taksi</span></a></li>';
                                    }
                                }

                                // if looping data
                                if (tr === 'pesawat') {
                                    let template = templatePesawat.clone();
                                    $(':disabled', template).prop('disabled', false);
                                    template = template.html().replaceAll('{no}', transportNo++);
                                    newTransport = $(template);
                                    transportList.append(newTransport);
                                } else {
                                    let template = templateTaxi.clone();
                                    $(':disabled', template).prop('disabled', false);
                                    template = template.html().replaceAll('{no}', transportNo++);
                                    newTransport = $(template);
                                    transportList.append(newTransport);
                                }

                                var startdt_trip = moment(det.start_date);
                                var tujuan_trip = det.tujuan_lengkap;
                                const dtoptionprop = {
                                    minDate: startdt_trip,

                                    useCurrent: false,
                                    format: 'DD.MM.YYYY HH:mm',
                                    showTodayButton: true,
                                    sideBySide: true,
                                    ignoreReadonly: true,

                                    showClose: true,
                                    showClear: true,
                                    toolbarPlacement: 'top',

                                    widgetPositioning: {
                                        horizontal: 'left',
                                        vertical: 'top'
                                    },
                                    // debug: true
                                };
                                $('.transport-jadwal-keberangkatan_' + tr + '' + transNo).html(startdt_trip.format('DD.MM.YYYY'));
                                $('.transport-jadwal-perjalanan_' + tr + '' + transNo).html(tujuan_trip);
                                $('input[name="transport[' + transNo + '][jadwal]"]', transportList)
                                    .datetimepicker(dtoptionprop)
                                    .trigger('change');

                                // set tujuan dropdown   
                                let option = new Option(det.tujuan_lengkap, det.id_travel_detail, false, false);
                                option.setAttribute('data-jadwal', det.tanggal_berangkat);
                                $('select[name="transport[' + transNo + '][id_travel_detail]"]', modalBooking)
                                    .append(option);

                                let option2 = new Option('Kembali', det.id_travel_detail + '_kembali', false, false);
                                option2.setAttribute('data-jadwal', pengajuan.tanggal_kembali);
                                $('select[name="transport[' + transNo + '][id_travel_detail]"]', modalBooking)
                                    .append(option2);
                                id_det = det.id_travel_detail;
                                transNo++;
                            });
                            var tiket_pulang = (det.transportasi_tiket).split(',');
                            id_det = id_det;

                            if (isback && tiket_pulang.length > 1) {
                                var kembali = $('#label_p_kembali').html();
                                kembali = moment(kembali);

                                let template = templatePesawat.clone();
                                $(':disabled', template).prop('disabled', false);
                                template = template.html().replaceAll('{no}', transportNo++);
                                newTransport = $(template);
                                transportList.append(newTransport);
                                /** Opsi kembali */
                                let option = new Option('Kembali', id_det + '_kembali', false, false);
                                option.setAttribute('data-jadwal', kembali);
                                $('select[name="transport[' + transNo + '][id_travel_detail]"]', modalBooking)
                                    .append(option);
                                isback = false;

                                var dtoptionprop = {
                                    minDate: kembali,

                                    useCurrent: false,
                                    format: 'DD.MM.YYYY HH:mm',
                                    showTodayButton: true,
                                    sideBySide: true,
                                    ignoreReadonly: true,

                                    showClose: true,
                                    showClear: true,
                                    toolbarPlacement: 'top',

                                    widgetPositioning: {
                                        horizontal: 'left',
                                        vertical: 'top'
                                    },
                                    // debug: true
                                };
                                $('input[name="transport[' + transNo + '][jadwal]"]', modalBooking)
                                    .datetimepicker(dtoptionprop)
                                    .trigger('change');
                                $('.transport-jadwal-keberangkatan_pesawat' + transNo).html(kembali.format('DD.MM.YYYY'));
                                $('.transport-jadwal-perjalanan_pesawat' + transNo).html("Kembali");
                            }
                        });

                        $('#ul_list_transport').append(list_ul_transport);
                    }

                    // ===============================================Penginapan list manipulasi =========
                    let templateHotel = $('#template-penginapan-hotel', modalBooking);
                    const penginapanList = $('#penginapan-list', modalBooking);
                    $('.penginapan-hotel', penginapanList)
                        .remove();

                    $('#div-add-penginapan', modalBooking).removeClass('hide');
                    $('#div-no-penginapan', modalBooking).addClass('hide');

                    if (hotels.length > 0) {
                        var transNo = 0;
                        $.each(hotels, function (i, hotel) {
                            let template = templateHotel.clone();

                            $(':disabled', template).prop('disabled', false);
                            template = template.html().replaceAll('{no}', penginapanNo++);
                            const newPenginapan = $(template);

                            $('.select-perjalanan', newPenginapan)
                                .val(hotel.id_travel_detail)
                                .trigger('change');

                            const checkIn = moment(hotel.start_date);
                            $('.penginapan_start_date input', newPenginapan)
                                .val(checkIn.format('DD.MM.YYYY'));
                            const checkOut = moment(hotel.end_date);
                            $('.penginapan_end_date input', newPenginapan)
                                .val(checkOut.format('DD.MM.YYYY'));

                            $('.penginapan-id', newPenginapan).val(hotel.id_travel_hotel);
                            $('.penginapan-nama_hotel', newPenginapan).val(hotel.nama_hotel);
                            $('.penginapan-alamat', newPenginapan).val(hotel.alamat);
                            $('.penginapan-pic_hotel', newPenginapan).val(hotel.PIC_hotel);
                            $('.penginapan-lampiran', newPenginapan).prop('required', false);
                            $('.penginapan-keterangan', newPenginapan).val(hotel.keterangan);

                            penginapanList.append(newPenginapan);

                            $('input,select,textarea', newPenginapan)
                                .prop('disabled', true)
                                .trigger('change');
                            var startdt_trip = moment(hotel.start_date);
                            var tujuan_trip = hotel.tujuan_lengkap;
                            $('.penginapan-jadwal-keberangkatan' + '' + transNo).html(startdt_trip.format('DD.MM.YYYY'));
                            $('.penginapan-jadwal-perjalanan' + '' + transNo).html(tujuan_trip);
                            transNo++;
                        });

                        $('.penginapan_start_date, .penginapan_end_date', penginapanList)
                            .datetimepicker(datepickerOptions)
                            .trigger('change');
                    } else {
                        var transNo = 0;
                        var isback = true;
                        $.each(details, function (i, det) {
                            var penginapan_detail = (det.jenis_penginapan).split(',');
                            if (jQuery.inArray('Hotel', penginapan_detail) == -1) {
                                let template = templateHotel.clone();
                                $(':disabled', template).prop('disabled', false);
                                template = template.html().replaceAll('{no}', transNo++);
                                const newPenginapan = $(template);
                                penginapanList.append(newPenginapan);
                                // set tujuan dropdown   
                                let option = new Option(det.tujuan_lengkap, det.id_travel_detail, false, false);
                                option.setAttribute('data-jadwal', det.tanggal_berangkat);
                                $('select[name="penginapan[' + penginapanNo + '][id_travel_detail]"]', modalBooking)
                                    .append(option);
                            }
                            var startdt_trip = moment(det.start_date);
                            var dtoptionprop = {
                                minDate: startdt_trip,

                                useCurrent: false,
                                format: 'DD.MM.YYYY HH:mm',
                                showTodayButton: true,
                                sideBySide: true,
                                ignoreReadonly: true,

                                showClose: true,
                                showClear: true,
                                toolbarPlacement: 'top',

                                widgetPositioning: {
                                    horizontal: 'left',
                                    vertical: 'top'
                                },
                                // debug: true
                            };

                            $('.penginapan_start_date, .penginapan_end_date', penginapanList)
                                .datetimepicker(dtoptionprop)
                                .trigger('change');

                            var startdt_trip = moment(det.start_date);
                            var tujuan_trip = det.tujuan_lengkap;
                            $('.penginapan-jadwal-keberangkatan' + '' + transNo).html(startdt_trip.format('DD.MM.YYYY'));
                            $('.penginapan-jadwal-perjalanan' + '' + transNo).html(tujuan_trip);
                            transNo++;
                        });
                    }

                    $('.select-perjalanan', transportList)
                        .select2()
                        .trigger('change.select2');

                    $('.select-perjalanan', penginapanList)
                        .select2()
                        .trigger('change.select2');

                    KIRANAKU.convertNumericLabel('#modal-spd-booking .numeric-label');
                    KIRANAKU.convertNumeric('#modal-spd-booking .numeric:not([readonly])');
                    $('#count_field').val(transNo);
                    modal.modal('show');
                } else {
                    KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                }
                KIRANAKU.hideLoading();
            },
            error: function (data) {
                KIRANAKU.hideLoading();
                KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
            }
        });
    });

    $(document).on('click', 'button[name="simpan_btn"]', function (e) {
        const modal = modalBooking;
        const form = $('.form-booking', modalBooking);
        const transportList = $('#transport-list', modalBooking);
        const availablehotel = $('#availablehotel').val();
        const modalBookinginap = $('#modal-tab-booking-transportasi');

        form.validate();
        let valid = form.valid();
        /** Validasi transportasi */
        const totalTransportasi = $('.transport-booking', transportList).length;

        if (!totalTransportasi) {
            valid = false;
            KIRANAKU.alert('NotOK', 'Diwajibkan membooking minimal satu transportasi', 'warning', 'no');
        }

        if (valid) {
            const isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses();
                const formData = new FormData(form[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/booking/save_booking_spd',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        KIRANAKU.hideLoading();
                        if (data.sts === 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $(modal).modal('hide');
                                location.reload();
                            });
                        } else {
                            KIRANAKU.endProses();
                            KIRANAKU.alert('OK', data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        KIRANAKU.hideLoading();
                        KIRANAKU.endProses();
                        KIRANAKU.alert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                KIRANAKU.alert(false, 'Silahkan tunggu sampai proses selesai.', 'info', 'no');
            }
        } else {
            if (validator.errorList.length) {
                let switchTab = true;
                $.each(validator.errorList, function (i, el) {
                    if ($('#modal-tab-booking-transportasi').has($(el.element)).length) {
                        switchTab = false;
                    }
                });
                if (switchTab) {
                    $('a[href="#modal-tab-booking-penginapan"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                } else {
                    $('a[href="#modal-tab-booking-transportasi"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                }
            }
        }
        e.preventDefault();
        return false;
    });

    // get temporary file name for preview
    $(document).on("change.bs.fileinput", ".fileinput", function (e) {
        readURL($('input[type="file"]', $(this))[0], $('.fileinput-zoom', $(this)));
    });

    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        if (oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        }
    };

    datatables_ssp();
    $(document).on("change", " #kelengkapan ", function (e) {
        datatables_ssp();
    });
});

function datatables_ssp() {
    var jenis = 1;
    var kelengkapan = $("#kelengkapan").val();
    var tipe_screens = tipe_screen;
    var tipe_sc = true;
    if (tipe_screens == 'pemesan') {
        tipe_sc = false;
    }

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        ordering: true,
        order: [[0, "desc"]],
        pageLength: $(".my-datatable-extends-order", this).data("page") ? $(".my-datatable-extends-order", this).data("page") : 10,
        paging: $(".my-datatable-extends-order", this).data("paging") ? $(".my-datatable-extends-order", this).data("paging") : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,

        pageLength: 10,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input').attr("placeholder", "Press enter to start searching");
            $('#sspTable_filter input').attr("title", "Press enter to start searching");
            $('#sspTable_filter input')
                .off('.DT')
                .on('keypress change', function (evt) {
                    if (evt.type == "change") {
                        api.search(this.value).draw();
                    }
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: baseURL + 'travel/booking/gets/list_transportasi_header',
            type: 'POST',
            data: function (data) {
                data.jenis = jenis;
                data.kelengkapan = kelengkapan;
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },
        },
        columns: [
            {
                "data": "id_travel_header",
                "name": "id_travel_header",
                "width": "10%",
                "render": function (data, type, row) {
                    return row.id_travel_header;
                },
                "visible": false
            },
            {
                "data": "nik",
                "name": "nik",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nik;
                },
                "visible": tipe_sc
            },
            {
                "data": "nama_karyawan",
                "name": "nama_karyawan",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.nama_karyawan;
                },
                "visible": tipe_sc
            },
            {
                "data": "no_trip",
                "name": "no_trip",
                "width": "7%",
                "render": function (data, type, row) {
                    return row.no_trip;
                }
            },
            {
                "data": "activity_label",
                "name": "activity_label",
                "width": "10%",
                "render": function (data, type, row) {
                    return row.activity_label;
                }
            },
            {
                "data": "jenis_tujuan",
                "name": "jenis_tujuan",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.jenis_tujuan;
                }
            },
            {
                "data": "details",
                "name": "start_date",
                "width": "15%",
                "render": function (data, type, row) {
                    var dt = (row.details).replace(/~\s*$/, "");
                    var arr = dt.split('~');
                    var result = '';
                    $.each(arr, function (i, v) {
                        var arr_det = v.split('|');

                        var trans_berangkat = arr_det[6]
                        var trans_pulang = arr_det[7]
                        var trans_berangkat_available = arr_det[8]
                        var trans_pulang_available = arr_det[9]
                        var status = '';
                        if (trans_berangkat_available == 1 && trans_berangkat == 1) {
                            status = '<span class="label label-success">Issued</span>';
                        } else if (trans_berangkat == 0 && trans_berangkat_available == 1) {
                            status = '<span class="label label-warning">Belum dipesankan</span>';
                        }
                        result += "Tanggal :" + arr_det[2] + ' ' + arr_det[3] + '<br>' + status + '<br>';
                        // }
                    })
                    return result;
                }
            },
            {
                "data": "end_date",
                "name": "end_date",
                "width": "15%",
                "render": function (data, type, row) {
                    var dt = (row.details).replace(/~\s*$/, "");
                    var arr = dt.split('~');
                    var result = '';
                    $.each(arr, function (i, v) {
                        var arr_det = v.split('|');

                        var trans_berangkat = arr_det[6]
                        var trans_pulang = arr_det[7]
                        var trans_berangkat_available = arr_det[8]
                        var trans_pulang_available = arr_det[9]
                        var status = '';
                        if (trans_pulang_available == 1 && trans_pulang == 1) {
                            status = '<span class="label label-success">Issued</span>';
                        } else if (trans_pulang == 0 && trans_pulang_available == 1) {
                            status = '<span class="label label-warning">Belum dipesankan</span>';
                        }
                        result += "Tanggal :" + arr_det[4] + ' ' + arr_det[5] + '<br>' + status + '<br>';
                        // }
                    })
                    return result;
                }
            },

            {
                "data": "id_travel_header" + "na",
                "name": "id_travel_header",
                "width": "5%",
                "render": function (data, type, row) {
                    var comments = '';
                    if (row.jumlah_komentar > 0) {
                        comments = "<span class='badge bg-yellow'>" + row.jumlah_komentar + "</span>";
                    }
                    var idreplace = (row.id_travel_header).replace("=", "");
                    var link = baseURL + 'travel/booking/add/' + idreplace;

                    output = "          <div class='input-group-btn'>";
                    output += "             <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span>";
                    output += comments + "</button>";
                    output += "             <ul class='dropdown-menu pull-right'>";
                    output += "                 <li><a href='" + link + "'  data-id='" + row.id_travel_header + "'><i class='fa fa-search'></i> Detail</a></li>";
                    output += "                 <li><a href='javascript:void(0)'  data-id='" + row.id_travel_header + "' class='spd-chat'><i class='fa fa-comments'></i> Chat Personalia</a></li>";
                    output += "             </ul>";
                    output += "         </div>";

                    return output;
                }
            }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            if (info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });

    return mydDatatables;
}

function readURL(input, targetPreview) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            targetPreview.attr('href', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function get_datas(show = null, tanggal_awal = null, tanggal_akhir = null) {
    var x = 1;
    KIRANAKU.showLoading();
    var DataforTable;
    $.ajax({
        url: baseURL + 'travel/booking/get_book',
        type: 'POST',
        dataType: 'JSON',
        data: {
            show: show,
        },
        success: function (data) {
            var customers = [data];
        }
    });
}