$(document).ready(function () {
    'use strict';
    const modalPembatalan = $('#modal-spd-pembatalan');

    /** Form pembatalan form validator**/
    validator = $('form', modalPembatalan).validate({
        ignore: '.hide input , .hide select, .hide textarea',
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                if (element.parents('.form-group').length) {
                    error.appendTo(element.parents('.form-group > div'));
                } else if (element.parents('td').length) {
                    error.appendTo(element.parents('td'));
                }
            }
        },
        highlight: function (element, errorClass, validClass) {
            if ($(element).parents('.form-group').length) {
                $(element).parents(".form-group > div").addClass("has-error").removeClass("has-success");
            } else if ($(element).parents('td').length) {
                $(element).parents("td").addClass("has-error").removeClass("has-success");
            }
        },
        unhighlight: function (element, errorClass, validClass) {

            if ($(element).parents('.form-group').length) {
                $(element).parents(".form-group > div").addClass("has-success").removeClass("has-error");
            } else if ($(element).parents('td').length) {
                $(element).parents("td").addClass("has-success").removeClass("has-error");
            }
        }

    });
    /** Pembatalan spd related */
    const pembatalanMultiTripTable = $('#table-cancel-multi-trip', modalPembatalan).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const pembatalanUangmukaTable = $('#table-cancel-uangmuka', modalPembatalan).DataTable({
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

    $(document).on('hide.bs.modal', modalPembatalan, function () {
        $('#id_travel_cancel', modalPembatalan).val(null);
        $('.numeric-label', modalPembatalan).each(function (i, el) {
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
    $(document).on('click', '.spd-cancel', function (e) {
        e.preventDefault();
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
                    const { pengajuan, details, downpayments, cancel } = data.data;
                    const tipe_trip = pengajuan.tipe_trip;

                    $('input[name="id_travel_header"]', modalPembatalan).val(pengajuan.id_travel_header);
                    if (cancel) {
                        $('input[name="id_travel_cancel"]', modalPembatalan).val(cancel.id_travel_cancel);
                    }
                    $('#label_activity', modalPembatalan).html(pengajuan.activity_label);
                    $('#label_tipe_trip', modalPembatalan).html('Pulang Pergi');

                    if (tipe_trip === 'single') {
                        $('#div-single-trip', modalPembatalan).removeClass('hide');
                        $('#div-multi-trip', modalPembatalan).addClass('hide');
                        modalPembatalan.find('.modalPembatalan-dialog').removeClass('modalPembatalan-lg');
                        $('#label_keperluan', modalPembatalan).html(pengajuan.keperluan);
                        $('#label_tujuan', modalPembatalan).html(pengajuan.tujuan_lengkap);
                        $('#label_single_start', modalPembatalan).html(pengajuan.tanggal_berangkat);
                        $('#label_single_end', modalPembatalan).html(pengajuan.tanggal_kembali);
                    } else {
                        $('#div-multi-trip', modalPembatalan).removeClass('hide');
                        $('#div-single-trip', modalPembatalan).addClass('hide');
                        modalPembatalan.find('.modalPembatalan-dialog').addClass('modalPembatalan-lg');
                        let template = $('#detail_multitrip_template', modalPembatalan).html();
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
                        $('#label_multi_end', modalPembatalan).html(pengajuan.tanggal_kembali);
                    }

                    $('#label_transportasi', modalPembatalan).html('');
                    $.each(pengajuan.transportasi_label, function (i, tr) {
                        $('#label_transportasi', modalPembatalan).append(
                            '<li>' + tr + '</li>'
                        );
                    });
                    $('#label_booking_brgkt', modalPembatalan)
                        .prop('disabled', true)
                        .prop('checked', pengajuan.booking_brgkt)
                        .trigger('change');
                    $('#label_booking_kembali', modalPembatalan)
                        .prop('disabled', true)
                        .prop('checked', pengajuan.booking_kembali)
                        .trigger('change');
                    $('#label_jenis_penginapan', modalPembatalan).html(pengajuan.jenis_penginapan);
                    /** Uang muka */
                    $('.label_total_um_jumlah', modalPembatalan).html(parseFloat(pengajuan.total_um));
                    if (downpayments.length) {
                        $('#div-uangmuka', modalPembatalan).removeClass('hide');
                        $('#div-um-kembali', modalPembatalan).removeClass('hide');

                        if (KIRANAKU.isNullOrEmpty(cancel)) {
                            $('#div-um-kembali input[name="lampiran"]', modalPembatalan).attr('required', true);
                        }

                        let firstCurrency = '';

                        pembatalanUangmukaTable
                            .clear()
                            .draw();
                        let template = $('#uangmuka_template', modalPembatalan).html();
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

                        $('.label_total_um_currency', modalPembatalan).html(firstCurrency);
                    } else {
                        $('#div-uangmuka', modalPembatalan).addClass('hide');
                        $('#div-um-kembali', modalPembatalan).addClass('hide');

                        $('#div-um-kembali input[name="lampiran"]', modalPembatalan).attr('required', false);
                    }

                    /** Pembatalan */
                    $('#jumlah_kembali', modalPembatalan).attr('numeric-max', pengajuan.total_um);
                    if (KIRANAKU.isNotNullOrEmpty(cancel)) {
                        AutoNumeric.set('#modal-spd-pembatalan #jumlah_kembali', parseFloat(cancel.jumlah_kembali));
                        $('#batal_um', modalPembatalan)
                            .prop('checked', cancel.batal_um_only)
                            .trigger('change');

                        $('#catatan', modalPembatalan).val(cancel.catatan);
                    }

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
    });

    $(document).on('click', 'button[name="simpan_btn_pembatalan"]', function (e) {
        const modal = modalPembatalan;
        var form = $('form', modalPembatalan);

        form.validate();
        var valid = form.valid();

        if (valid) {
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
                const topAdds = $('#div-um-kembali', modal).parents('fieldset').position().top;

                modal.scrollTop($('.has-error', modal).position().top + topAdds);
            }
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
});