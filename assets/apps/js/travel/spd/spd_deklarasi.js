$(document).ready(function () {
    const modalDeklarasi = $('#modal-spd-deklarasi');
    /** Datatable related */
    const detailMultiTripTable = $('#table-multi-trip', modalDeklarasi).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const biayaTable = $('#table-biaya', modalDeklarasi).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

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
    };

    /** variable index detail */
    let biayaNo = 0;
    let detailNo = 0;

    let expensesOptions = [];
    let expensesCurrencyOptions = [];

    let totalDays = 0;

    /** Form pengajuan submit action **/
    validator = $('.form-deklarasi').validate({
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

    KIRANAKU.convertNumericLabel($('.numeric[readonly]', modalDeklarasi));

    /** Alert untuk perjalanan dinas yang belum selesai menurut tanggal akhir pengajuan ayy aktifkan ketika live*/
    // $(document).on('click', 'li.disable > a.spd-deklarasi', function (e) {
    //     KIRANAKU.alert('OK','Pengajuan dinas belum selesai, tidak bisa melakukan deklarasi', 'warning', 'no');
    // });

    $(document).on('click', '.spd-deklarasi', function (e) { // li:not(.disable) > a.spd-deklarasi ayy ubah ketika live
        e.preventDefault();
        const modal = modalDeklarasi;
        const idHeader = $(this).data('id');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/deklarasi',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idHeader
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    biayaTable.clear();
                    const {pengajuan, details, expenses, expenses_currency, deklarasi, deklarasi_details} = data.data;
                    const tipe_trip = pengajuan.tipe_trip;

                    $(".badge-status", modal).removeClass(function (index, className) {
                        return (className.match(/(^|\s)bg-\S+/g) || []).join(' ');
                    });
                    $('#id_travel_header', modal).val(pengajuan.id_travel_header);
                    $('.badge-status', modal).html(pengajuan.status);
                    $('.badge-status', modal).addClass(pengajuan.status_color);
                    /** Detail perjalanan */
                    $('#label_no_trip', modal).html(pengajuan.no_trip);
                    $('#label_activity', modal).html(pengajuan.activity_label);

                    detailMultiTripTable
                        .clear()
                        .draw();

                    if (tipe_trip === 'single') {
                        $('#label_tipe_trip', modal).html('Pulang Pergi');
                        $('.div-single-trip', modal).removeClass('hide');
                        $('.div-multi-trip', modal).addClass('hide');
                        $('#label_keperluan', modal).html(pengajuan.keperluan);
                        $('#label_tujuan', modal).html(pengajuan.tujuan_lengkap);
                        $('#tanggal_berangkat', modal).val(pengajuan.start_date);
                        $('#label_single_start', modal).html(pengajuan.tanggal_berangkat);
                        $('#single_end', modal).val(pengajuan.tanggal_kembali);
                        $('#keperluan', modal).val(pengajuan.keperluan);
                    } else {
                        $('#label_tipe_trip', modal).html('Multi Perjalanan');
                        $('.div-multi-trip', modal).removeClass('hide');
                        $('.div-single-trip', modal).addClass('hide');
                        let endDateSet = false;

                        $.each(details, function (i, trip) {
                            let template = $('#multitrip_template', modal).html();
                            template = template.replaceAll('{no}', detailNo++);
                            let newTrip = $(template).clone();
                            $('.multi-id-detail', newTrip).val(trip.id_travel_detail);
                            $('.label_multi_tujuan', newTrip).html(trip.tujuan_lengkap);
                            $('.label_multi_start', newTrip).html(trip.tanggal_berangkat);
                            $('.label_multi_keperluan', newTrip).val(trip.keperluan);
                            detailMultiTripTable
                                .row
                                .add($(newTrip))
                                .draw();
                            if (!endDateSet) {
                                $('#tanggal_berangkat', modal).val(trip.start_date);
                                endDateSet = true;
                            }
                        });
                        $('#multi_end', modal).val(pengajuan.tanggal_kembali);
                    }

                    $('.trip_end_datetime', modal).datetimepicker(datetimepickerOptions);

                    KIRANAKU.numericSet($('#total_um', modal), parseFloat(pengajuan.total_um));

                    /** Detail biaya */
                    expensesOptions = [];
                    expenses.map(function (option) {
                        expensesOptions.push(
                            {
                                id: option.kode_expense,
                                text: option.tipe_expense_text,
                                data: {
                                    currency: option.currency,
                                    max: parseFloat(option.value),
                                    auto_total: option.auto_total,
                                    validate_currency: option.validate_currency,
                                    total_min: parseFloat(option.total_min),
                                    total_max: parseFloat(option.total_max),
                                    day_min: option.day_min,
                                    day_max: option.day_max,
                                }
                            }
                        );
                    });
                    expensesCurrencyOptions = [];
                    expenses_currency.map(function (option) {
                        expensesCurrencyOptions.push(
                            {
                                id: option.currency,
                                text: option.currency,
                            }
                        );
                    });

                    /** inisialisasi edit data deklarasi */
                    if (deklarasi) {
                        $('#id_travel_deklarasi_header', modal).val(deklarasi.id_travel_deklarasi_header);

                        $.each(deklarasi_details, function (i, detail) {
                            const startDate = $('#tanggal_berangkat', modalDeklarasi).val();
                            const endDate = $('.trip_end_datetime', modalDeklarasi).data('DateTimePicker').date();
                            let template = $('#biaya_template').html();
                            template = template.replaceAll('{no}', biayaNo++);
                            let newBiaya = $(template);

                            $(newBiaya).find('.biaya_delete')
                                .removeClass('hide');

                            biayaTable
                                .row
                                .add($(newBiaya))
                                .draw();

                            $('.biaya_tanggal input', newBiaya)
                                .val(
                                    moment(detail.tanggal).format('DD.MM.YYYY')
                                );

                            $('.biaya_tanggal', newBiaya).datetimepicker(datepickerOptions);
                            $('.biaya_tanggal', newBiaya).data('DateTimePicker')
                                .date(moment(startDate))
                                .minDate(moment(startDate))
                                .maxDate(moment(endDate));

                            $.each(expensesOptions, function (i, val) {
                                const option = new Option(val.text, val.id, false, false);
                                option.setAttribute('data-biaya', JSON.stringify(val.data));
                                $('.select-biaya', newBiaya).append(option);
                            });
                            $('.select-biaya', newBiaya).val(detail.kode_expense)
                                .trigger('change');

                            $('.biaya-keterangan', newBiaya).val(detail.keterangan);
                            $('.biaya-jumlah', newBiaya).val(parseFloat(detail.jumlah));

                            $.each(expensesCurrencyOptions, function (i, val) {
                                const option = new Option(val.text, val.id, false, false);
                                $('.select-currency', newBiaya).append(option);
                            });
                            $('.select-currency', newBiaya).val(detail.currency)
                                .trigger('change');

                            $('.select2', newBiaya)
                                .select2()
                                .trigger('change');
                            $('.fileinput', newBiaya).fileinput();

                            KIRANAKU.convertNumeric($('.numeric', newBiaya));
                        });
                        calculateTotalBiaya();
                    }

                    totalDays = moment(pengajuan.start_date).diff(moment(pengajuan.end_date), 'days') + 1;

                    
                } else {
                    KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                }
                KIRANAKU.hideLoading();
            },
            complete: function(data){
                modal.modal('show');
                
            },
            error: function (data) {
                KIRANAKU.hideLoading();
                KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
            }
        });
    });

    $(document).on('change', '.select-biaya', function (e) {
        const data = $('option:selected', this).data('biaya');
        const selectCurrency = $(this).closest('.template-trip').find('.select-currency');
        const inputJumlah = $(this).closest('.template-trip').find('.biaya-jumlah');
        selectCurrency
            .val(data.currency)
            .trigger('change');

        if (data.auto_total === 1) {
            inputJumlah.attr('numeric-max', totalDays * data.max);
            inputJumlah.trigger('change');
            KIRANAKU.numericSet(inputJumlah, totalDays * data.max);
        }
    });

    $(document).on('change', '.biaya-jumlah', function (e) {
        calculateTotalBiaya();
    });

    $(document).on('dp.change', '.trip_end_datetime', function ({date, oldDate}) {
        const tableBiaya = $('#table-biaya', modalDeklarasi);
        if ($('.biaya_tanggal', tableBiaya).data('DateTimePicker')) {
            $('.biaya_tanggal', tableBiaya).data('DateTimePicker')
                .maxDate(date);
        }
        totalDays = moment(date).diff($('#tanggal_berangkat', modalDeklarasi).val(), 'days') + 1;

        $('.select-biaya', modalDeklarasi).trigger('change');
    });

    function calculateTotalBiaya() {
        const totalUm = KIRANAKU.numericGet('#total_um');
        const tableBiaya = $('#table-biaya', modalDeklarasi);
        let total = 0;
        $('.biaya-jumlah', tableBiaya).each(function (i, el) {
            total += KIRANAKU.numericGet($(this));
        });
        KIRANAKU.numericSet('#total_biaya', total);
        KIRANAKU.numericSet('#total_bayar', total - totalUm);
    }

    $(document).on('click', '#biaya_add_btn', function (e) {
        e.preventDefault();
        const startDate = $('#tanggal_berangkat', modalDeklarasi).val();
        const endDate = $('.trip_end_datetime:visible', modalDeklarasi).data('DateTimePicker').date();
        let template = $('#biaya_template').html();
        template = template.replaceAll('{no}', biayaNo++);
        let newBiaya = $(template);

        $(newBiaya).find('.biaya_delete')
            .removeClass('hide');

        biayaTable
            .row
            .add($(newBiaya))
            .draw();

        $('.biaya_tanggal', newBiaya).datetimepicker(datepickerOptions);
        $('.biaya_tanggal', newBiaya).data('DateTimePicker')
            .date(moment(startDate))
            .minDate(moment(startDate))
            .maxDate(moment(endDate));

        $.each(expensesOptions, function (i, val) {
            const option = new Option(val.text, val.id, false, false);
            option.setAttribute('data-biaya', JSON.stringify(val.data));
            $('.select-biaya', newBiaya).append(option);
        });
        $.each(expensesCurrencyOptions, function (i, val) {
            const option = new Option(val.text, val.id, false, false);
            $('.select-currency', newBiaya).append(option);
        });

        $('.select2', newBiaya)
            .select2()
            .trigger('change');
        $('.fileinput', newBiaya).fileinput();

        KIRANAKU.convertNumeric($('.numeric', newBiaya));
    });

    $(document).on('click', '.biaya_delete:visible', function (e) {
        e.preventDefault();
        biayaTable
            .row($(this).parents('tr'))
            .remove()
            .draw();
        calculateTotalBiaya();
    });

    $(document).on('click', 'button[name="simpan_btn"]', function (e) {
        const modal = modalDeklarasi;
        const form = $('.form-deklarasi', modalDeklarasi);
        const biayaCount = biayaTable.rows().count();
        calculateTotalBiaya();
        form.validate();
        let valid = form.valid();

        if (valid) {
            if (KIRANAKU.isProses() == 0) {
                KIRANAKU.startProses();
                const formData = new FormData(form[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/deklarasi',
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
        }
        e.preventDefault();
        return false;
    });

    /** List pengajuan delete clicked */
    $(".spd-deklarasi-delete").on("click", function (e) {
        var id = $(this).attr("data-id");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    KIRANAKU.hideLoading();
                    $.ajax({
                        url: baseURL + 'travel/spd/delete/deklarasi',
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

    $(document).on("click", ".add_deklarasi", function (e) {
        const id_travel_header = this.dataset.id_travel_header;
        const no_trip = this.dataset.no_trip;
        const nik = this.dataset.nik;
        const cek_status = this.dataset.cek_status;

        const data = {
            id_travel_header: id_travel_header,
            no_trip: no_trip,
            nik: nik
        };

        if (cek_status == 0)
            create_deklarasi(data);
        else {
            cek_status_trip(data);
        }
    });
});

function create_deklarasi(data) {
    let link_deklarasi = baseURL + "travel/spd/add_deklarasi/" + data.id_travel_header;
    location.href =link_deklarasi;
}

function cek_status_trip(data) {
    $.ajax({
        url: baseURL + "travel/spd/get/status_trip",
        type: "POST",
        dataType: "JSON",
        data: data,
        beforeSend: function () { },
        success: function (response) {
            if (response) {
                if (response.sts == 'OK') {
                    if (response.status_trip == 'Settled/Posted to Financial Accounting') {
                        create_deklarasi(data)
                    } else {
                        kiranaAlert('notOK', 'Belum dilakukan posting untuk trip ini. Silahkan menghubungi HRD.', 'error', 'no');
                    }
                } else {
                    kiranaAlert('notOK', response.msg, 'error', 'no');
                }
            }
        },
        error: function (xhr, status, error) {
            let errorMessage = xhr.status + ': ' + xhr.statusText;
            kiranaAlert('notOK', `Server Error, (${errorMessage})`, 'error', 'no');
        },
    });
}