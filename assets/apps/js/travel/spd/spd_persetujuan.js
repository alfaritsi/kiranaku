$(document).ready(function () {
    'use strict';
    const modalPersetujuan = $('#modal-spd-persetujuan');
    /** Detail spd related */
    const approvalMultiTripTable = $('#table-multi-trip', modalPersetujuan).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const approvalUangmukaTable = $('#table-uangmuka', modalPersetujuan).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const approvalBiayaTable = $('#table-biaya', modalPersetujuan).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    KIRANAKU.createValidator($('.form-persetujuan', modalPersetujuan));

    $('#filter-date input', 'form[name="filter-history"]').on('change', function () {
        $('form[name="filter-history"]').attr('action', baseURL + 'travel/spd/persetujuan#tab-history');
        $('form[name="filter-history"]').submit();
    });

    $(document).on('hide.bs.modal', '#modal-spd-persetujuan', function () {
        $('.numeric-label').each(function (i, el) {
            AutoNumeric.set(el, 0);
            $(el).attr('value', 0);
        });

        approvalUangmukaTable
            .clear()
            .draw();

        approvalMultiTripTable
            .clear()
            .draw();
    });

    /** Link detail spd clicked */
    $(document).on('click', '.spd-approval', function (e) {
        e.preventDefault();
        const modal = $('#modal-spd-persetujuan');
        const idHeader = $(this).data('id');
        const isApprovalBy = $(this).hasClass('spd-approval-by') ? 1 : 0;
        const approvalByDiv = $('#approval_lampiran_div', modal);
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
                    const {pengajuan, details, personel, downpayments, cancel, deklarasi, deklarasi_details} = data.data;
                    const tipe_trip = pengajuan.tipe_trip;

                    $('input[name="id_travel_header"]', modal).val(pengajuan.id_travel_header);
                    $('#label_activity', modal).html(pengajuan.activity_label);
                    $('#label_tipe_trip', modal).html('Pulang Pergi');

                    /** Approval mewakili by superuser */
                    $('input[name="is_approval_by"]', modal).val(isApprovalBy);
                    if (isApprovalBy) {
                        approvalByDiv.removeClass('hide');
                        $('input', approvalByDiv).prop('required', true);
                    }else{
                        approvalByDiv.addClass('hide');
                        $('input', approvalByDiv).prop('required', false);
                    }

                    if (tipe_trip === 'single') {
                        $('#div-single-trip', modal).removeClass('hide');
                        $('#div-multi-trip', modal).addClass('hide');
                        $('#label_keperluan', modal).html(pengajuan.keperluan);
                        $('#label_tujuan', modal).html(pengajuan.tujuan_lengkap);
                        $('#label_single_start', modal).html(pengajuan.tanggal_berangkat);
                        $('#label_single_end', modal).html(pengajuan.tanggal_kembali);
                    } else {
                        $('#div-multi-trip', modal).removeClass('hide');
                        $('#div-single-trip', modal).addClass('hide');
                        let template = $('#detail_multitrip_template', modal).html();
                        $.each(details, function (i, trip) {
                            let newTrip = $(template).clone();
                            $('.label_multi_no', newTrip).html(trip.no_urut);
                            $('.label_multi_tujuan', newTrip).html(trip.tujuan_lengkap);
                            $('.label_multi_start', newTrip).html(trip.tanggal_berangkat);
                            $('.label_multi_keperluan', newTrip).html(trip.keperluan);
                            approvalMultiTripTable
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
                        .prop('checked', pengajuan.booking_brgkt)
                        .trigger('change');
                    $('#label_booking_kembali')
                        .prop('checked', pengajuan.booking_kembali)
                        .trigger('change');
                    $('#label_booking_kembali, #label_booking_brgkt')
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

                        approvalUangmukaTable
                            .clear()
                            .draw();
                        let template = $('#uangmuka_template', modal).html();
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

                            approvalUangmukaTable
                                .row
                                .add($(expense))
                                .draw();
                        });
                        $('.label_total_um_currency', modal).html(firstCurrency);
                    } else {
                        $('#div-uangmuka', modal).addClass('hide');
                        $('#div-um-kembali', modal).addClass('hide');
                    }
                    /** reset button tolak */
                    $('button[data-action="disapprove"]', modal).removeClass('hide');
                    /** Detail pembatalan */
                    if (KIRANAKU.isNotNullOrEmpty(cancel)) {
                        $('.modal-title', modal).html('Persetujuan Pembatalan');
                        $('input[name="approval_type"]', modal).val('pembatalan');
                        $('#detail-cancel', modal).removeClass('hide');

                        $('.label_jumlah_kembali_jumlah', modal).html(cancel.jumlah_kembali);
                        $('#label_batal_um', modal)
                            .prop('disabled', false)
                            .prop('checked', cancel.batal_um_only)
                            .trigger('change')
                            .prop('disabled', true)
                            .trigger('change');

                        $('.label_cancel_catatan', modal).html(KIRANAKU.isNullOrEmpty(cancel.catatan, cancel.catatan, '-'));
                    }
                    /** Detail Deklarasi */
                    else if (KIRANAKU.isNotNullOrEmpty(deklarasi)) {
                        $('.modal-title', modal).html('Persetujuan Deklarasi');
                        $('input[name="approval_type"]', modal).val('deklarasi');
                        $('#div-biaya').removeClass('hide');
                        $('a[href="#modal-tab-deklarasi"]', modal)
                            .parents('li')
                            .removeClass('hide');
                        $('a[href="#modal-tab-deklarasi"]', modal).trigger('click');

                        approvalBiayaTable
                            .clear()
                            .draw();
                        $.each(deklarasi_details, function (i, detail) {
                            let template = $('#biaya_template', modal).html();
                            let newBiaya = $(template);

                            $('.biaya_tanggal', newBiaya)
                                .html(
                                    moment(detail.tanggal).format('DD.MM.YYYY')
                                );
                            $('.biaya_jenis', newBiaya).html(detail.tipe_expense_text);
                            $('.biaya_keterangan', newBiaya).html(detail.keterangan);
                            KIRANAKU.numericSet($('.biaya_jumlah .jumlah', newBiaya), parseFloat(detail.jumlah));
                            $('.biaya_jumlah .currency', newBiaya).html(detail.currency);

                            approvalBiayaTable
                                .row
                                .add($(newBiaya))
                                .draw();
                        });
                        KIRANAKU.numericSet($('.total_biaya .jumlah', modal), parseFloat(deklarasi.total_biaya));
                        KIRANAKU.numericSet($('.total_bayar .jumlah', modal), parseFloat(deklarasi.total_bayar));
                        KIRANAKU.numericSet($('.uang_muka .jumlah', modal), parseFloat(pengajuan.total_um));
                        $('button[data-action="disapprove"]', modal).addClass('hide');
                    } else {
                        $('.modal-title', modal).html('Persetujuan Perjalanan Dinas');
                        $('input[name="approval_type"]', modal).val('pengajuan');
                        $('#detail-cancel', modal).addClass('hide');
                    }

                    KIRANAKU.convertNumericLabel($('.numeric-label', modalPersetujuan), {
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

    $(document).on("click", ".btn-approval", function (e) {
        e.preventDefault();
        const isproses = KIRANAKU.isProses();

        if (isproses == 0) {
            const formData = new FormData($(".form-persetujuan")[0]);
            const action = $(this).data('action');
            let commentValid = true;
            if (action !== 'approve') {
                commentValid = KIRANAKU.isNotNullOrEmpty($('#comment', modalPersetujuan).val());
            }
            var valid = $(".form-persetujuan", modalPersetujuan).valid();

            if (commentValid && valid) {
                KIRANAKU.startProses();
                formData.append('action', action);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/persetujuan',
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
                                modalPersetujuan.modal('hide');
                                location.reload();
                            });
                        } else {
                            KIRANAKU.endProses();
                            KIRANAKU.alert('OK', data.msg, 'error', 'no', data.msg);
                        }
                    },
                    error: function (data) {
                        KIRANAKU.hideLoading();
                        KIRANAKU.endProses();
                        KIRANAKU.alert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                KIRANAKU.alert(false, 'Harap isi catatan apabila meminta untuk revisi atau menolak pengajuan.', 'error', 'no');
            }
        } else {
            KIRANAKU.alert(false, 'Silahkan tunggu sampai proses selesai.', 'info', 'no');
        }
    });
});