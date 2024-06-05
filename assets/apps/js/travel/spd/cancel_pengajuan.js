$(document).ready(function () {
    $(document).on('click', '#head_pengajuan', function (e) {
        if (!$(this).hasClass('whitesmoke')) {

            if ($("#head_uang_muka").hasClass('whitesmoke')) {
                $("#head_uang_muka").removeClass('whitesmoke');
                $("#tab_uang_muka").addClass('hidden');
            }
            if ($("#head_deklarasi").hasClass('whitesmoke')) {
                $("#head_deklarasi").removeClass('whitesmoke');
                $("#tab_deklarasi").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_pengajuan").removeClass('hidden');
        }
    });
    $(document).on('click', '#head_uang_muka', function (e) {
        if (!$(this).hasClass('whitesmoke')) {

            if ($("#head_pengajuan").hasClass('whitesmoke')) {
                $("#head_pengajuan").removeClass('whitesmoke');
                $("#tab_pengajuan").addClass('hidden');
            }
            if ($("#head_deklarasi").hasClass('whitesmoke')) {
                $("#head_deklarasi").removeClass('whitesmoke');
                $("#tab_deklarasi").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_uang_muka").removeClass('hidden');
        }
    });
    $(document).on('click', 'button[name="batal_btn"]', function (e) {
        window.opener = self;
        window.close();
    });
    //aaa	
    'use strict';
    const modalPembatalan = $('#modal-spd-pembatalan');

    /** Form pembatalan form validator**/
    KIRANAKU.createValidator($('.form-persetujuan'));

    /** Pembatalan spd related */
    const pembatalanMultiTripTable = $('#table-cancel-multi-trip').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const pembatalanUangmukaTable = $('#table-cancel-uangmuka').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    AutoNumeric.multiple('#modal-spd-pembatalan .numeric:not([readonly])', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        allowDecimalPadding: false,
        decimalPlaces: 0,
        modifyValueOnWheel: false
    });

    $(document).on('hide.bs.modal', function () {
        $('#id_travel_cancel').val(null);
        $('.numeric-label').each(function (i, el) {
            AutoNumeric.set(el, 0);
            $(el).attr('value', 0);
        });

        pembatalanUangmukaTable
            .clear()
            .draw();

        pembatalanMultiTripTable
            .clear()
            .draw();
    });

    /** Link cancel spd clicked */
    const idHeader = $("#id_travel_header").val();
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
                const { pengajuan, details, downpayments, cancel } = data.data;
                const tipe_trip = pengajuan.tipe_trip;

                $('input[name="id_travel_header"]').val(pengajuan.id_travel_header);
                if (cancel) {
                    $('input[name="id_travel_cancel"]').val(cancel.id_travel_cancel);
                }
                $('#label_activity').html(pengajuan.activity_label);
                $('#label_tipe_trip').html('Pulang Pergi');

                if (tipe_trip === 'single') {
                    $('#div-single-trip').removeClass('hide');
                    $('#div-multi-trip').addClass('hide');
                    modalPembatalan.find('.modalPembatalan-dialog').removeClass('modalPembatalan-lg');
                    $('#label_keperluan').html(pengajuan.keperluan);
                    $('#label_tujuan').html(pengajuan.tujuan_lengkap);
                    $('#label_single_start').html(pengajuan.tanggal_berangkat);
                    $('#label_single_end').html(pengajuan.tanggal_kembali);
                } else {
                    $('#div-multi-trip').removeClass('hide');
                    $('#div-single-trip').addClass('hide');
                    modalPembatalan.find('.modalPembatalan-dialog').addClass('modalPembatalan-lg');
                    let template = $('#detail_multitrip_template').html();
                    $.each(details, function (i, trip) {
                        let newTrip = $(template).clone();
                        $('.label_multi_no', newTrip).html(trip.no_urut);
                        $('.label_multi_tujuan', newTrip).html(trip.tujuan_lengkap);
                        $('.label_multi_start', newTrip).html(trip.tanggal_berangkat);
                        $('.label_multi_keperluan', newTrip).html(trip.keperluan);
                        pembatalanMultiTripTable
                            .row
                            .add($(newTrip))
                            .draw();
                    });
                    $('#label_multi_end').html(pengajuan.tanggal_kembali);
                }

                $('#label_transportasi').html('');
                $.each(pengajuan.transportasi_label, function (i, tr) {
                    $('#label_transportasi').append(
                        '<li>' + tr + '</li>'
                    );
                });
                $('#label_booking_brgkt').bootstrapToggle((pengajuan.booking_brgkt == 1 ? 'on' : 'off')).prop('disabled', true);
                $('#label_booking_kembali').bootstrapToggle((pengajuan.booking_kembali == 1 ? 'on' : 'off')).prop('disabled', true);
                $('#label_jenis_penginapan').html(pengajuan.jenis_penginapan);
                /** Uang muka */
                $('.label_total_um_jumlah').html(numberWithCommas(parseFloat(pengajuan.total_um)));
                if (downpayments.length) {
                    $('#div-uangmuka').removeClass('hide');
                    $('#div-um-kembali').removeClass('hide');

                    if (KIRANAKU.isNullOrEmpty(cancel)) {
                        $('#div-um-kembali input[name="lampiran"]').attr('required', true);
                    }

                    let firstCurrency = '';

                    pembatalanUangmukaTable
                        .clear()
                        .draw();
                    let template = $('#uangmuka_template').html();
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

                        pembatalanUangmukaTable
                            .row
                            .add($(expense))
                            .draw();
                    });

                    $('.label_total_um_currency').html(firstCurrency);
                } else {
                    $('#div-uangmuka').addClass('hide');
                    $('#div-um-kembali').addClass('hide');

                    $('#div-um-kembali input[name="lampiran"]').attr('required', false);
                }

                /** Pembatalan */
                $('#jumlah_kembali').attr('numeric-max', pengajuan.total_um);
                if (KIRANAKU.isNotNullOrEmpty(cancel)) {
                    AutoNumeric.set('#modal-spd-pembatalan #jumlah_kembali', parseFloat(cancel.jumlah_kembali));
                    $('#batal_um')
                        .prop('checked', cancel.batal_um_only)
                        .trigger('change');

                    $('#catatan').val(cancel.catatan);
                }
                KIRANAKU.convertNumeric($('#jumlah_kembali'));

                AutoNumeric.multiple('#modal-spd-pembatalan .numeric-label', {
                    digitGroupSeparator: '.',
                    decimalCharacter: ',',
                    allowDecimalPadding: false,
                    readOnly: true,
                    noEventListeners: true,
                    decimalPlaces: 0
                });
                modalPembatalan.modal('show');
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

    $(document).on('click', 'button[name="simpan_btn_pembatalan"]', function (e) {
        const modal = modalPembatalan;
        var form = $('.form-persetujuan');

        var empty_form = validate(".form-persetujuan", true);
        if (empty_form == 0) {
            var isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses();
                var formData = new FormData(form[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/pembatalan',
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
                                history.go(-1);
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
            kiranaAlert('notOK', 'Silahkan lengkapi form terlebih dahulu', 'error', 'no');
        }
        e.preventDefault();
        return false;
    });

    /** Link delete pengajuan pembatalan */
    $(".spd-cancel-delete").on("click", function (e) {
        var id = $(this).attr("data-id");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    KIRANAKU.hideLoading();
                    $.ajax({
                        url: baseURL + 'travel/spd/delete/pembatalan',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        success: function (data) {
                            KIRANAKU.hideLoading();
                            if (data.sts === 'OK') {
                                kiranaAlert(data.sts, data.msg);
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            KIRANAKU.hideLoading();
                            kiranaAlert('notOK', 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );
    });

    $(document).on('click', 'button[name="back_btn_deklarasi"]', function (e) {
        history.go(-1);
    });
});