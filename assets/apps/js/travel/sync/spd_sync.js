function pageScroll() {
    window.scrollBy(0, 50); // horizontal and vertical scroll increments
    scrolldelay = setTimeout('pageScroll()', 100); // scrolls every 100 milliseconds
}

$(document).ready(function () {
    /** Validasi related */
    $.validator.addMethod("numeric-max", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value <= params;
    }, jQuery.validator.format("<small>Harap masukkan nilai lebih kecil atau sama dengan {0}</small>"));

    $.validator.addMethod("numeric-min", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value >= params;
    }, jQuery.validator.format("<small>Harap masukkan nilai lebih besar atau sama dengan {0}</small>"));

    $.validator.addMethod("numeric-total-max", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value <= params;
    }, jQuery.validator.format("<small>Total hanya boleh lebih kecil atau sama dengan {0}</small>"));

    $.validator.addMethod("numeric-leftover", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || parseFloat(value) === parseFloat(params);
    }, jQuery.validator.format("<small>Sisa hanya boleh sama dengan {0}</small>"));

    /** Plugin related */
    $('.icheck', document).bootstrapToggle({
        on: 'Ya',
        off: 'Tidak',
        size: 'small',
        onstyle: 'success',
        offstyle: 'default',
        width: 70
    });

   

    $.fn.datepicker.dates['en'] = {
        days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
        daysShort: ["Ming", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
        daysMin: ["Mn", "Sn", "Sl", "Rb", "Km", "Jm", "Sa"],
        months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        today: "Today",
        clear: "Clear",
        format: "dd.mm.yyyy",
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        weekStart: 1
    };

    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop() || $('html').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
    });

    /** Modal declare */
    const modalDetail = $('#modal-detail-spd-pengajuan');
    const modalTujuan = $('#modal-tujuan-spd');
    const modalHistory = $('#modal-history-spd');
    /** Detail spd related */
    const detailMultiTripTable = $('#table-detail-multi-trip', modalDetail).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const detailUangmukaTable = $('#table-detail-uangmuka', modalDetail).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    /** Tujuan SPD */
    const tujuanSpdTable = $('#table-tujuan-spd', modalTujuan).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    /** History SPD */
    const historySpdTable = $('#table-history-spd', modalHistory).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    $(document).on('hide.bs.modal', modalDetail, function () {
        detailMultiTripTable
            .clear()
            .draw();
    });

    /** Link tujuan spd clicked */
    $(document).on('click', '.spd-tujuan', function (e) {
        // console.log('masuk');
        e.preventDefault();
        const modal = $('#modal-tujuan-spd');
        const idHeader = $(this).data('id');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idHeader
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const {details} = data.data;

                    let template = $('#tujuan_spd_template', modal).html();
                    $.each(details, function (i, trip) {
                        let newTrip = $(template).clone();
                        $('.label_multi_no', newTrip).html(trip.no_urut);
                        $('.label_multi_tujuan', newTrip).html(trip.tujuan_lengkap);
                        $('.label_multi_start', newTrip).html(trip.tanggal_berangkat);
                        $('.label_multi_keperluan', newTrip).html(trip.keperluan);

                        tujuanSpdTable
                            .row
                            .add($(newTrip))
                            .draw();
                    });
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


    $(document).on('hide.bs.modal', '#modal-tujuan-spd', function (e) {
        tujuanSpdTable
            .clear()
            .draw();
    });

    /** Link detail spd clicked */
    $(document).on('click', '.spd-detail', function (e) {
        e.preventDefault();
        const modal = $('#modal-detail-spd-pengajuan');
        const idHeader = $(this).data('id');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idHeader
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const {pengajuan, details, personel, downpayments, cancel} = data.data;
                    const tipe_trip = pengajuan.tipe_trip;

                    $(".badge-status", modalDetail).removeClass(function (index, className) {
                        return (className.match(/(^|\s)bg-\S+/g) || []).join(' ');
                    });
                    $('.badge-status', modalDetail).html(pengajuan.status);
                    $('.badge-status', modalDetail).addClass(pengajuan.status_color);
                    /** Detail perjalanan */
                    $('#label_activity', modal).html(pengajuan.activity_label);

                    if (tipe_trip === 'single') {
                        $('#label_tipe_trip', modal).html('Sekali Jalan / Pulang Pergi');
                        $('#div-single-trip', modal).removeClass('hide');
                        $('#div-multi-trip', modal).addClass('hide');
                        // modal.find('.modal-dialog').removeClass('modal-lg');
                        $('#label_keperluan', modal).html(pengajuan.keperluan);
                        $('#label_tujuan', modal).html(pengajuan.tujuan_lengkap);
                        $('#label_single_start', modal).html(pengajuan.tanggal_berangkat);
                        $('#label_single_end', modal).html(pengajuan.tanggal_kembali);
                    } else {
                        $('#label_tipe_trip', modal).html('Multi Perjalanan');
                        $('#div-multi-trip', modal).removeClass('hide');
                        $('#div-single-trip', modal).addClass('hide');
                        // modal.find('.modal-dialog').addClass('modal-lg');
                        let template = $('#detail_multitrip_template', modal).html();
                        $.each(details, function (i, trip) {
                            let newTrip = $(template).clone();
                            $('.label_multi_no', newTrip).html(trip.no_urut);
                            $('.label_multi_tujuan', newTrip).html(trip.tujuan_lengkap);
                            $('.label_multi_start', newTrip).html(trip.tanggal_berangkat);
                            $('.label_multi_keperluan', newTrip).html(trip.keperluan);
                            detailMultiTripTable
                                .row
                                .add($(newTrip))
                                .draw();
                        });
                        $('#label_multi_end', modal).html(pengajuan.tanggal_kembali);
                    }

                    $('#label_transportasi', modal).html('');
                    $.each(pengajuan.transportasi_label, function (i, tr) {
                        $('#label_transportasi', modal).append(
                            '<li>' + tr + '</li>'
                        );
                    });
                    $('#label_booking_brgkt')
                        .prop('disabled', false)
                        .prop('checked', pengajuan.booking_brgkt)
                        .trigger('change')
                        .prop('disabled', true)
                        .trigger('change');
                    $('#label_booking_kembali')
                        .prop('disabled', false)
                        .prop('checked', pengajuan.booking_kembali)
                        .trigger('change')
                        .prop('disabled', true)
                        .trigger('change');
                    $('#label_jenis_penginapan', modal).html(pengajuan.jenis_penginapan);
                    /** Detail personel */
                    $('#label_no_hp', modal).html(pengajuan.no_hp);
                    $('#label_p_nik', modal).html(personel.nik);
                    $('#label_p_nama', modal).html(personel.nama);
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
                    /** Uang muka */
                    $('.label_total_um_jumlah', modal).html(parseFloat(pengajuan.total_um));
                    if (downpayments.length) {
                        $('#div-uangmuka', modal).removeClass('hide');

                        $('#div-um-kembali', modal).removeClass('hide');

                        let firstCurrency = '';

                        detailUangmukaTable
                            .clear()
                            .draw();
                        let template = $('#detail_uangmuka_template', modal).html();
                        $.each(downpayments, function (i, dp) {
                            let expense = $(template).clone();
                            const rateV = parseFloat(dp.value) * 100;

                            $('.uangmuka-label-expense', expense).html(dp.tipe_expense_text);
                            $('.uangmuka-label-rate', expense).html(rateV);
                            $('.uangmuka-label-durasi', expense).html(dp.durasi);
                            $('.uangmuka-label-jumlah', expense).html(parseFloat(dp.jumlah));
                            $('.uangmuka-label-currency', expense).html(dp.currency);

                            /** Set first currency */
                            if (KIRANAKU.isNullOrEmpty(firstCurrency)) {
                                firstCurrency = dp.currency;
                            }

                            detailUangmukaTable
                                .row
                                .add($(expense))
                                .draw();
                        });
                        $('.label_total_um_currency', modalDetail).html(firstCurrency);
                    } else {
                        $('#div-uangmuka', modal).addClass('hide');
                    }

                    /** Detail pembatalan */
                    if (KIRANAKU.isNotNullOrEmpty(cancel)) {
                        $('#fieldset-pembatalan', modalDetail).removeClass('hide');
                        $('.badge-pembatalan', modalDetail).removeClass('hide');
                        $('.label_jumlah_kembali_jumlah', modal).html(cancel.jumlah_kembali);
                        $('#label_batal_um', modal)
                            .prop('disabled', false)
                            .prop('checked', cancel.batal_um_only)
                            .trigger('change')
                            .prop('disabled', true)
                            .trigger('change');

                        $('#label_catatan', modal).html(KIRANAKU.isNullOrEmpty(cancel.catatan, cancel.catatan, '-'));
                    } else {
                        $('#fieldset-pembatalan', modalDetail).addClass('hide');
                        $('.badge-pembatalan', modalDetail).addClass('hide');
                    }

                    AutoNumeric.multiple('#modal-detail-spd-pengajuan .numeric-label', {
                        digitGroupSeparator: '.',
                        decimalCharacter: ',',
                        allowDecimalPadding: false,
                        readOnly: true,
                        noEventListeners: true,
                        decimalPlaces: 0
                    });
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

    /** Link history spd clicked */
    $(document).on('click', '.spd-history', function (e) {
        e.preventDefault();
        const modal = $('#modal-history-spd');
        const idHeader = $(this).data('id');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idHeader
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const history = data.data;

                    let template = $('#history_spd_template', modal).html();
                    $.each(history, function (i, h) {
                        let newHistory = $(template).clone();
                        $('.label_tanggal', newHistory).html(moment(h.tgl_status_f).format('DD.MM.YYYY HH:mm:ss'));
                        $('.label_action', newHistory).html(h.action);
                        $('.label_remark', newHistory).html(KIRANAKU.isNullOrEmpty(h.remark, h.remark, '-'));
                        $('.label_comment', newHistory).html(KIRANAKU.isNullOrEmpty(h.comment, h.comment, '-'));
                        $('.label_by', newHistory).html(
                            '[' + h.action_by + '] <br/>' + h.action_by_name
                        );

                        historySpdTable
                            .row
                            .add($(newHistory))
                            .draw();
                    });
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
    $(document).on('hide.bs.modal', '#modal-history-spd', function (e) {
        historySpdTable
            .clear()
            .draw();
    });

    
});