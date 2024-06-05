var init_form_data;
function readURL(input, targetPreview) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            targetPreview.attr('href', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$(document).ready(function () {
    // get temporary file name for preview
    $(document).on("change.bs.fileinput", ".fileinput", function (e) {
        readURL($('input[type="file"]', $(this))[0], $('.fileinput-zoom', $(this)));
    });
    //lha
    $(document).on("change", ".select-biaya-value", function (e) {
        var kode_expense = $(this).val();
        var nomor = $(this).data("nomor");
        var id_header = $('input[name="id_travel_header"]').val();
        if (nomor > 0) {
            $.ajax({
                url: baseURL + 'travel/spd/get/expenses_value',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id_header: $('input[name="id_travel_header"]').val(),
                    country: 'ID',
                    jenis_aktifitas: 'B',
                    kode_expense: kode_expense,
                    type: 'declare'
                },
                success: function (data) {
                    if (data.sts === 'OK') {
                        $.each(data.data, function (i, v) {
                            const rateV = v.value * 100;
                            const durasiSpd = $("input[name='durasi_label']").val();
                            KIRANAKU.numericSet($("input[name='biaya[" + nomor + "][rate]']"), rateV);
                            if (rateV != 0) {
                                $("input[name='biaya[" + nomor + "][jumlah]']").attr('numeric-max', durasiSpd * rateV);
                            }
                        });
                    }
                }
            });
        }
    });

    /** variable index detail */
    const biayaTable = $('#table-biaya').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
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

    let biayaNo = 0;

    let expensesOptions = [];
    let expensesCurrencyOptions = [];

    let totalDays = 0;

    $(document).on('change', '.biaya-jumlah', function (e) {
        calculateTotalBiaya();
    });
    $(document).on('dp.change', '.trip_end_datetime', function ({ date, oldDate }) {
        const tableBiaya = $('#table-biaya');
        if ($('.biaya_tanggal', tableBiaya).data('DateTimePicker')) {
            $('.biaya_tanggal', tableBiaya).data('DateTimePicker')
                .maxDate(date);
        }
        totalDays = moment(date).diff($('#tanggal_berangkat').val(), 'days') + 1;

        $('.select-biaya').trigger('change');
    });

    function calculateTotalBiaya() {
        const totalUm = KIRANAKU.numericGet('#total_um');
        const tableBiaya = $('#table-biaya');
        let total = 0;
        $('.biaya-jumlah', tableBiaya).each(function (i, el) {
            total += KIRANAKU.numericGet($(this));
        });
        $('input[name="total_biaya"]').val(rupiah(total));
        $('input[name="total_bayar"]').val(rupiah(total - totalUm));
    }

    $(document).on('click', '#biaya_add_btn', function (e) {
        e.preventDefault();

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
        $.each(expensesOptions, function (i, val) {
            const option = new Option(val.text, val.id, false, false);
            option.setAttribute('data-biaya', JSON.stringify(val.data));
            $('.select-biaya', newBiaya).append(option);
        });
        $.each(expensesCurrencyOptions, function (i, val) {
            const option = new Option(val.text, val.id, false, false);
            $('.select-currency', newBiaya).append(option);
        });
        $('.select-currency').val('IDR').trigger('change');

        $('.select2', newBiaya)
            .select2()
            .trigger('change');
        //lha	
        // $('.fileinput', newBiaya).fileinput();

        KIRANAKU.convertNumeric($('.numeric', newBiaya));
        $('.biaya-rate', newBiaya).attr('readonly', true);
    });

    $(document).on('click', '.biaya_delete:visible', function (e) {
        e.preventDefault();
        biayaTable
            .row($(this).parents('tr'))
            .remove()
            .draw();
        calculateTotalBiaya();
    });

    $('#datetimepicker12').datetimepicker({
        inline: true,
        sideBySide: true
    });

    $.ajax({
        url: baseURL + 'travel/spd/get/pengajuan_deklarasi',
        type: 'POST',
        dataType: 'JSON',
        data: {
            id: $('input[name="id_header"]').val()
        },
        success: function (data) {
            if (data.sts === 'OK') {
                const { pengajuan, details, optpabrik, opttujuan, optcountry, cancel, deklarasi, history, downpayments, expenses, expenses_currency, pengajuan_deklarasi, details_deklarasi, details_deklarasi_biaya, personel, cuti_pengganti } = data.data;
                $('input[name="id_travel_header"]').val(pengajuan.id_travel_header);

                const tipe_trip = pengajuan.tipe_trip;
                const tipe_trip_deklarasi = pengajuan_deklarasi.tipe_trip;

                //START FORM HEADER
                //tab um
                var totalum = pengajuan.total_um > 0 ? pengajuan.total_um : '0';
                KIRANAKU.numericSet($('#total_um'), parseFloat(pengajuan.total_um));
                $('#total_um').prop('readonly', true);
                //AKTIFITAS
                $("select[name='activity']").val(pengajuan.activity).trigger('change');
                // *NO HP
                $('input[name="idDeklarasiHeader"]').val(pengajuan_deklarasi.id_travel_deklarasi_header);
                $('input[name="no_hp"]').val(pengajuan.no_hp);
                $('input[name="no_trip"]').val(pengajuan.no_trip);
                $('input[name="activity_label"]').val(pengajuan.activity_label);
                $('input[name="status_transportasi"]').val(pengajuan.status_transportasi);
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

                $('input[name="nik_label"]').val(personel.nik);
                $('input[name="nama_label"]').val(personel.nama);
                $('input[name="kantor_label"]').val(kantor);
                $('input[name="jabatan_label"]').val(personel.posst);
                $('input[name="bagian_label"]').val(bagian);
                $('input[name="no_hp_label"]').val(personel.no_hp);
                var start_date_label = moment(pengajuan_deklarasi.start_date).format("DD.MM.YYYY");
                $('input[name="start_date_label"]').val(start_date_label);
                var start_date_label_hidden = moment(pengajuan_deklarasi.start_date);
                $('input[name="start_date_label_hidden"]').val(start_date_label_hidden);
                var end_date_label = moment(pengajuan_deklarasi.end_date).format("DD.MM.YYYY");
                $('input[name="end_date_label"]').val(end_date_label);
                var toDate = new Date(pengajuan_deklarasi.end_date);
                var fromDate = new Date(pengajuan_deklarasi.start_date);
                let durasiSpd = dateDiff('d', fromDate, toDate) + 1;
                $("input[name='durasi_label']").val(durasiSpd);

                // *TIPE TRIP
                //pengajuan
                if (tipe_trip == 'single') {
                    $('#single_trip_disabled').iCheck('check').prop('disabled', true);
                    $('#multi_trip_disabled').prop('disabled', true);

                    var rowtrip_disabled = 1;
                    detail = details;

                    generate_data_trip_disabled(data, rowtrip_disabled, tipe_trip, detail);

                    $('select[name="detail[' + rowtrip_disabled + '][country]"]').val(detail.country).trigger('change.select2');
                    $('select[name="detail[' + rowtrip_disabled + '][tujuan_persa]"]').val(detail.tujuan_persa).trigger('change.select2');
                    $('select[name="detail[' + rowtrip_disabled + '][tujuan]"]').val(detail.tujuan).trigger('change.select2');
                    $('input[name="detail[' + rowtrip_disabled + '][tujuan_lain]"]').val(detail.tujuan_lain);
                    $('input[name="detail[' + rowtrip_disabled + '][id]"]').val(details.id_travel_detail);

                    if (detail.tujuan_lain !== "" && detail.tujuan_lain.trim() !== "") {
                        $('input[name="detail[' + rowtrip_disabled + '][tujuan_lain]"]').closest('.form-group').removeClass('hide');
                    }

                    var endDatetime = moment(detail.end_date + " " + detail.end_time).format("DD.MM.YYYY HH:mm");
                    if ($("#div_kembali").hasClass('hide')) {
                        $("#div_kembali").removeClass('hide');
                    }

                    var startDatetime = moment(detail.start_date + " " + detail.start_time).format("DD.MM.YYYY HH:mm");

                    $('input[name="detail[' + rowtrip_disabled + '][start]"]').val(startDatetime).trigger('change');
                    $('input[name="detail_end"]').val(endDatetime).trigger('change');

                    var transports = (detail.transportasi + '').split(',');
                    var transports_ticket = (detail.transportasi_tiket + '').split(',');
                    $('select[name="detail[' + rowtrip_disabled + '][trans][]"]').val(transports).trigger('change.select2');
                    $('select[name="detail[' + rowtrip_disabled + '][tiket][]"]').val(transports_ticket).trigger('change.select2');
                    if (detail.jenis_penginapan == 'mess' || detail.jenis_penginapan == 'Mess') {
                        details.jenis_penginapan = 'Mess';
                    }
                    if (detail.jenis_penginapan == 'Hotel' || detail.jenis_penginapan == 'hotel') {
                        detail.jenis_penginapan = 'Hotel';
                    }
                    $('select[name="detail[' + rowtrip_disabled + '][inap]"]').val(detail.jenis_penginapan).trigger('change.select2');
                } else {
                    $('#single_trip_disabled').prop('disabled', true);
                    $('#multi_trip_disabled').iCheck('check').prop('disabled', true);

                    $.each(details, function (x, detail) {
                        var rowtrip_disabled = x + 1;

                        generate_data_trip_disabled(data, rowtrip_disabled, tipe_trip, detail);

                        $('input[name="detail[' + rowtrip_disabled + '][id]"]').val(details.id_travel_detail);

                        $('select[name="detail[' + rowtrip_disabled + '][country]"]').val(detail.country).trigger('change.select2');
                        $('select[name="detail[' + rowtrip_disabled + '][tujuan_persa]"]').val(detail.tujuan_persa).trigger('change.select2');
                        $('select[name="detail[' + rowtrip_disabled + '][tujuan]"]').val(detail.tujuan).trigger('change.select2');
                        $('input[name="detail[' + rowtrip_disabled + '][tujuan_lain]"]').val(detail.tujuan_lain);
                        if (detail.tujuan_lain && detail.tujuan_lain.trim() !== "") {
                            $('input[name="detail[' + rowtrip_disabled + '][tujuan_lain]"]').closest('.form-group').removeClass('hide');
                        }

                        var endDatetime = moment(detail.end_date + " " + detail.end_time).format("DD.MM.YYYY HH:mm");
                        $('input[name="detail_end"]').val(endDatetime);
                        if ($("#div_kembali").hasClass('hide')) {
                            $("#div_kembali").removeClass('hide');
                        }

                        var startDatetime = moment(detail.start_date + " " + detail.start_time).format("DD.MM.YYYY HH:mm");
                        $('input[name="detail[' + rowtrip_disabled + '][start]"]').val(startDatetime);

                        var transports = (detail.transportasi + '').split(',');
                        var transports_ticket = (detail.transportasi_tiket + '').split(',');
                        $('select[name="detail[' + rowtrip_disabled + '][trans][]"]').val(transports).trigger('change.select2');
                        $('select[name="detail[' + rowtrip_disabled + '][tiket][]"]').val(transports_ticket).trigger('change.select2');
                        if (detail.jenis_penginapan == 'mess' || detail.jenis_penginapan == 'Mess') {
                            detail.jenis_penginapan = 'Mess';
                        }
                        if (detail.jenis_penginapan == 'Hotel' || detail.jenis_penginapan == 'hotel') {
                            detail.jenis_penginapan = 'Hotel';
                        }
                        $('select[name="detail[' + rowtrip_disabled + '][inap]"]').val(detail.jenis_penginapan).trigger('change.select2');
                    });
                }

                // //pengajuan deklarasi
                if (tipe_trip_deklarasi == 'single') {
                    $('#single_trip').iCheck('check').prop('disabled', true);
                    $('#multi_trip').prop('disabled', false);
                    var rowtrip = 1;
                    detail = details_deklarasi;

                    generate_data_trip_edit(data, rowtrip, tipe_trip, detail);

                    $('select[name="detail[' + rowtrip + '][country]"]').val(detail.country).trigger('change.select2');
                    $('select[name="detail[' + rowtrip + '][tujuan_persa]"]').val(detail.tujuan_persa).trigger('change.select2');
                    $('select[name="detail[' + rowtrip + '][tujuan]"]').val(detail.tujuan).trigger('change.select2');
                    $('input[name="detail[' + rowtrip + '][tujuan_lain]"]').val(detail.tujuan_lain);
                    $('input[name="detail[' + rowtrip + '][id]"]').val(detail.id_travel_detail);
                    $('#tanggal_berangkat').val(pengajuan.start_date);

                    if (detail.tujuan_lain !== "" && detail.tujuan_lain.trim() !== "") {
                        $('input[name="detail[' + rowtrip + '][tujuan_lain]"]').closest('.form-group').removeClass('hide');
                    }

                    var endDatetime = moment(detail.end_date + " " + detail.end_time).format("DD.MM.YYYY HH:mm");
                    if ($("#div_kembali").hasClass('hide')) {
                        $("#div_kembali").removeClass('hide');
                    }

                    var startDatetime = moment(detail.start_date + " " + detail.start_time).format("DD.MM.YYYY HH:mm");

                    $('input[name="detail[' + rowtrip + '][start]"]').val(startDatetime).trigger('change');
                    $('input[name="detail_end"]').val(endDatetime).trigger('change');

                    var transports = (detail.transportasi + '').split(',');
                    var transports_ticket = (detail.transportasi_tiket + '').split(',');
                    $('select[name="detail[' + rowtrip + '][trans][]"]').val(transports).trigger('change.select2');
                    $('select[name="detail[' + rowtrip + '][tiket][]"]').val(transports_ticket).trigger('change.select2');
                    if (detail.jenis_penginapan == 'mess' || detail.jenis_penginapan == 'Mess') {
                        detail.jenis_penginapan = 'Mess';
                    }
                    if (detail.jenis_penginapan == 'Hotel' || detail.jenis_penginapan == 'hotel') {
                        detail.jenis_penginapan = 'Hotel';
                    }
                    $('select[name="detail[' + rowtrip + '][inap]"]').val(detail.jenis_penginapan).trigger('change.select2');
                } else {
                    $('#single_trip').prop('disabled', false);
                    $('#multi_trip').iCheck('check').prop('disabled', true);
                    $.each(details_deklarasi, function (x, detail) {
                        var rowtrip = x + 1;

                        generate_data_trip_edit(data, rowtrip, tipe_trip, detail);

                        $('input[name="detail[' + rowtrip + '][id]"]').val(details_deklarasi.id_travel_detail);

                        $('select[name="detail[' + rowtrip + '][country]"]').val(detail.country).trigger('change.select2');
                        $('select[name="detail[' + rowtrip + '][tujuan_persa]"]').val(detail.tujuan_persa).trigger('change.select2');
                        $('select[name="detail[' + rowtrip + '][tujuan]"]').val(detail.tujuan).trigger('change.select2');
                        $('input[name="detail[' + rowtrip + '][tujuan_lain]"]').val(detail.tujuan_lain);
                        if (detail.tujuan_lain && detail.tujuan_lain.trim() !== "") {
                            $('input[name="detail[' + rowtrip + '][tujuan_lain]"]').closest('.form-group').removeClass('hide');
                        }

                        var endDatetime = moment(detail.end_date + " " + detail.end_time).format("DD.MM.YYYY HH:mm");
                        $('input[name="detail_end"]').val(endDatetime);
                        if ($("#div_kembali").hasClass('hide')) {
                            $("#div_kembali").removeClass('hide');
                        }

                        var startDatetime = moment(detail.start_date + " " + detail.start_time).format("DD.MM.YYYY HH:mm");
                        $('input[name="detail[' + rowtrip + '][start]"]').val(startDatetime);

                        var transports = (detail.transportasi + '').split(',');
                        var transports_ticket = (detail.transportasi_tiket + '').split(',');
                        $('select[name="detail[' + rowtrip + '][trans][]"]').val(transports).trigger('change.select2');
                        $('select[name="detail[' + rowtrip + '][tiket][]"]').val(transports_ticket).trigger('change.select2');
                        if (detail.jenis_penginapan == 'mess' || detail.jenis_penginapan == 'Mess') {
                            detail.jenis_penginapan = 'Mess';
                        }
                        if (detail.jenis_penginapan == 'Hotel' || detail.jenis_penginapan == 'hotel') {
                            detail.jenis_penginapan = 'Hotel';
                        }
                        $('select[name="detail[' + rowtrip + '][inap]"]').val(detail.jenis_penginapan).trigger('change.select2');
                    });
                }

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
                            text: option.currency
                        }
                    );
                });


                if (cancel) {
                    $('input[name="approval_type"]').val("pembatalan");
                }

                if (deklarasi) {
                    $('input[name="approval_type"]').val("deklarasi");
                }
                /** inisialisasi edit data deklarasi */
                // details_deklarasi_biaya
                if (KIRANAKU.isNotNullOrEmpty(details_deklarasi_biaya)) {
                    $('#id_travel_deklarasi_header').val(pengajuan_deklarasi.id_travel_deklarasi_header);
                    $.each(details_deklarasi_biaya, function (i, detail) {
                        const startDate = $('#tanggal_berangkat').val();
                        const endDate = $('#tanggal_berangkat').val();
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

                        $.each(expensesOptions, function (i, val) {
                            const option = new Option(val.text, val.id, false, false);
                            option.setAttribute('data-biaya', JSON.stringify(val.data));
                            $('.select-biaya', newBiaya).append(option);
                        });
                        $('.select-biaya', newBiaya).val(detail.kode_expense)
                            .trigger('change');

                        $('.biaya-keterangan', newBiaya).val(detail.keterangan);
                        $('.biaya-jumlah', newBiaya).val(parseFloat(detail.jumlah));
                        $('.biaya-rate', newBiaya).val(parseFloat(detail.rate));
                        if (detail.rate != 0) {
                            $('.biaya-jumlah', newBiaya).attr('numeric-max', durasiSpd * detail.rate);
                        }

                        $.each(expensesCurrencyOptions, function (i, val) {
                            const option = new Option(val.text, val.id, false, false);
                            $('.select-currency', newBiaya).append(option);
                        });
                        $('.select-currency', newBiaya).val(detail.currency)
                            .trigger('change');
                        $('.select-currency').val('IDR').trigger('change');
                        $('.select2', newBiaya)
                            .select2()
                            .trigger('change');

                        if ((detail.lampiran == ' ') || (detail.lampiran == null)) {
                            let divFileinput = $('#div-lampiran-' + i + ' .fileinput');
                            divFileinput.removeClass('fileinput-exists');
                            divFileinput.addClass('fileinput-new');
                            divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
                        } else {
                            let divFileinput = $('#div-lampiran-' + i + ' .fileinput');
                            divFileinput.removeClass('fileinput-new');
                            divFileinput.addClass('fileinput-exists');

                            divFileinput.find('.fileinput-zoom').attr('href', '../../../assets/file/travel/' + detail.lampiran);
                            divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
                        }

                        KIRANAKU.convertNumeric($('.numeric', newBiaya));
                    });
                    calculateTotalBiaya();
                    $(".biaya-rate").attr('readonly', 'readonly');
                    $("input[name='biaya[0][lampiran]']").prop('required', false);
                    $("button[name='biaya[0][btn_delete]']").addClass('disabled');

                }

                if (KIRANAKU.isNotNullOrEmpty(history)) {
                    var history_det = history;

                    $(".tm_his").html("");
                    var appendToUl = "";
                    var x = 0;
                    $.each(history, function (i, h) {
                        let template = $('#history_spd_template_timeline').html();
                        template = template.replaceAll('{no}', x);
                        let newHistory = $(template).clone();
                        $('.span_tgl', newHistory).html(moment(h.tgl_status_f).format('DD.MMMM.YYYY'));
                        $('.span_jam', newHistory).html(moment(h.tgl_status_f).format('HH:mm'));
                        $('.action_his', newHistory).html(h.action);
                        var komen = h.comment != "" ? h.comment + ' - ' + h.remark + '<br />' : "";
                        $('.action_by', newHistory).html(komen
                            + '<span class="badge bg-aqua">Dilakukan oleh '
                            + h.action_by_name + '[' + h.action_by + '] </span>'
                        );

                        var string = h.action; var classIcon = "";
                        var n = string.indexOf("Pengajuan");
                        if (string.indexOf("Pengajuan") != '-1') {
                            classIcon = "fa fa-user-plus bg-blue";
                            classBg = "bg-blue";
                        } else if (string.indexOf("Disetujui") != '-1') {
                            classIcon = "fa fa-check-circle-o bg-green";
                            classBg = "bg-green";
                        } else if (string.indexOf("Kembali") != '-1') {
                            classIcon = "fa fa-user-times bg-red";
                            classBg = "bg-red";
                        }
                        $("#icon_action" + x, newHistory).addClass(classIcon);
                        $("#span_tgl" + x, newHistory).addClass(classBg);

                        $(".tm_his").append(newHistory);
                        x++;
                    });
                }

                //Cuti Pengganti
                if (cuti_pengganti && cuti_pengganti.length) {
                    $.each(cuti_pengganti, function (i, v) {
                        let output = `<tr class="row-cuti">
                            <td>
                                <div class="input-group ">
                                    <div class="input-group-addon"><i class="fa fa-calendar colors-tosca"></i></div>
                                    <input type="text" data-date-format="dd.mm.yyyy" class="form-control date-cuti" name="tanggal_cuti_add[]" required value="${v.tanggal_cuti_format}">
                                </div>
                            </td>
                            <td><input type="text" class="form-control" name="cuti_add[]" autocomplete="off" required value="${v.keterangan}"></td>
                            <td><button type="button" class="btn btn-sm btn-danger remove_cuti" title="Remove"><i class="fa fa-trash"></i></button></td>
                        </tr>`;
                        $("#list-cuti").append(output);
                    });
                    configDateCuti();
                }
                cekListCuti();
            }
        },
    });



    const datetimepickerOptions = {
        useCurrent: false,
        format: 'DD.MM.YYYY HH:mm',
        showTodayButton: true,
        sideBySide: true,
        ignoreReadonly: true,
        keepOpen: false,
        locale: moment.locale('id'),

        showClose: true,
        showClear: true,
        toolbarPlacement: 'bottom',

        widgetPositioning: {
            horizontal: 'left',
            vertical: 'top'
        },
        disabledDates: tanggal_travels,
        // debug: true
    };

    $('#kiranadatetimepicker').datetimepicker({
        inline: true,
        sideBySide: true
    });

    $('#single_trip').iCheck('check');

    $('#multi_trip').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    $('#single_trip').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    $('#single_trip').on('ifChecked', function () {
        $('#multi_trip').iCheck('uncheck');
        $('input[name="tipe_trip"]').val('single');

        $("#pengajuan_single").html('');
        generate_data_trip(init_form_data, 1, 'single');
        $("#activity").trigger('change');

        $("#pengajuan_single").removeClass('hidden');
        $("#pengajuan_multi").addClass('hidden');
    });

    $('#multi_trip').on('ifChecked', function () {
        $('#single_trip').iCheck('uncheck');
        $('input[name="tipe_trip"]').val('multi');

        $("#form_multi").html('');
        $("#tambah_trip").removeClass('hidden');

        $("#pengajuan_multi").removeClass('hidden');
        $("#pengajuan_single").removeClass('hidden');
    });

    $(document).on('click', '#head_pengajuan', function (e) {
        if (!$(this).hasClass('whitesmoke')) {
            if ($("#head_uang_muka").hasClass('whitesmoke')) {
                $("#head_uang_muka").removeClass('whitesmoke');
                $("#tab_uang_muka").addClass('hidden');
            }

            if ($("#head_transportasi").hasClass('whitesmoke')) {
                $("#head_transportasi").removeClass('whitesmoke');
                $("#tab_transportasi").addClass('hidden');
            }

            if ($("#head_history").hasClass('whitesmoke')) {
                $("#head_history").removeClass('whitesmoke');
                $("#tab_history").addClass('hidden');
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

            if ($("#head_transportasi").hasClass('whitesmoke')) {
                $("#head_transportasi").removeClass('whitesmoke');
                $("#tab_transportasi").addClass('hidden');
            }

            if ($("#head_history").hasClass('whitesmoke')) {
                $("#head_history").removeClass('whitesmoke');
                $("#tab_history").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_uang_muka").removeClass('hidden');
        }
    });

    $(document).on('click', '#head_transportasi', function (e) {
        if (!$(this).hasClass('whitesmoke')) {
            if ($("#head_pengajuan").hasClass('whitesmoke')) {
                $("#head_pengajuan").removeClass('whitesmoke');
                $("#tab_pengajuan").addClass('hidden');
            }

            if ($("#head_uang_muka").hasClass('whitesmoke')) {
                $("#head_uang_muka").removeClass('whitesmoke');
                $("#tab_uang_muka").addClass('hidden');
            }

            if ($("#head_history").hasClass('whitesmoke')) {
                $("#head_history").removeClass('whitesmoke');
                $("#tab_history").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_transportasi").removeClass('hidden');
        }
    });

    $(document).on('click', '#head_history', function (e) {
        if (!$(this).hasClass('whitesmoke')) {
            if ($("#head_pengajuan").hasClass('whitesmoke')) {
                $("#head_pengajuan").removeClass('whitesmoke');
                $("#tab_pengajuan").addClass('hidden');
            }

            if ($("#head_transportasi").hasClass('whitesmoke')) {
                $("#head_transportasi").removeClass('whitesmoke');
                $("#tab_transportasi").addClass('hidden');
            }

            if ($("#head_uang_muka").hasClass('whitesmoke')) {
                $("#head_uang_muka").removeClass('whitesmoke');
                $("#tab_uang_muka").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_history").removeClass('hidden');
        }
    });

    $(document).on('click', '.panel-heading', function (e) {
        var $this = $(this);
        if (!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });

    $(document).on("click", "#sbmit", function (e) {
        var empty_form = validate("#form-edit-deklarasi", true);
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                KIRANAKU.showLoading();
                var formData = new FormData($("#form-edit-deklarasi")[0]);
                $.ajax({
                    url: baseURL + 'travel/spd/save/deklarasi2',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            KIRANAKU.alert(data.sts, data.msg, "success", baseURL + 'travel/spd/deklarasi');
                            $("input[name='isproses']").val(0);
                            KIRANAKU.hideLoading();
                        } else {
                            KIRANAKU.alert('NotOK', 'mohonxx periksa kembali data yang dimasukan', 'warning', 'no');
                            $("input[name='isproses']").val(0);
                            KIRANAKU.hideLoading();
                        }
                    }
                });

            } else {
                kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
            }
            e.preventDefault();
            return false;

        } else {
            kiranaAlert("notOK", 'Silahkan lengkapi form terlebih dahulu', 'error', 'no');
        }
        e.preventDefault();
        return false;
    });

    $(document).on("click", "#tambah_trip", function () {
        var rowtrip = $("#form_multi .rowtrip").length;

        // BUTTON NAVIGATE
        rowtrip = rowtrip + 1;
        if (rowtrip >= 2 && rowtrip <= 5) {
            if ($("#hapus_trip").hasClass('hidden')) {
                $("#hapus_trip").removeClass('hidden');
            }
        } else {
            if (!$("#hapus_trip").hasClass('hidden')) {
                $("#hapus_trip").addClass('hidden');
            }
        }

        if (init_form_data) {
            generate_data_trip(init_form_data, rowtrip);
            $("#activity").trigger('change');
        } else {
            $.ajax({
                url: baseURL + "travel/spd/init_data_form",
                type: 'POST',
                dataType: 'JSON',
                beforeSend: function () {
                    var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                    $("body .overlay-wrapper").append(overlay);
                },
                success: function (data) {
                    init_form_data = data;
                    generate_data_trip(data, rowtrip);
                    $("#activity").trigger('change');
                },
                complete: function () {
                    $("body .overlay-wrapper .overlay").remove();
                }
            });
        }
    });

    $(document).on("click", "#hapus_trip", function () {
        var rowtrip = $("#form_multi .rowtrip").length;
        $(".rowtrip:eq(" + rowtrip + ")").remove();

        rowtrip = rowtrip;

        //BUTTON NAVIGATE
        if (rowtrip > 2 && rowtrip <= 5) {
            if ($("#hapus_trip").hasClass('hidden')) {
                $("#hapus_trip").removeClass('hidden');
            }
        } else {
            if (!$("#hapus_trip").hasClass('hidden')) {
                $("#hapus_trip").addClass('hidden');
            }
        }

        if (rowtrip >= 2 && rowtrip < 5) {
            if ($("#tambah_trip").hasClass('hidden')) {
                $("#tambah_trip").removeClass('hidden');
            }
        } else {
            if (!$("#tambah_trip").hasClass('hidden')) {
                $("#tambah_trip").addClass('hidden');
            }
        }
    });

    // ============================================================================================


    $('#activity').select2();
    const modalPengajuan = $('#modal-spd-pengajuan');
    const modalPengajuantes = $('#modal-spd-pengajuantes');
    const setXLModal = modalPengajuan.find('.modal-dialog').addClass('modal-xl');
    const modalTambahUm = $('#modal-spd-tambah-um');
    /** Datatable related */
    const multiTripTable = $('#table-multi-trip', modalPengajuan).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    $('#popApproval').popover({
        html: true,
        // title   : 'Info Approval <button type="button" id="close" class="close pull-right" onclick="$(&quot;#example&quot;).popover(&quot;hide&quot;);">&times;</button>',
        content: function (data) {
            var html = $('#template-approval').clone().removeAttr('id').removeClass('hidden');
            var list = $(this).data('list');
            $.each(list, function (i, v) {
                $(html).find('tbody').append('<tr><td>' + v + '</td></tr>')
            });
            var $popover_togglers = this;
            return html;
        }
    });

    multiTripTable.on('draw.dt', function () {
        multiTripTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    const uangmukaTable = $('#table-uangmuka').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    const uangmukaTambahTable = $('#table-uangmuka-tambah').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    const uangmukaTambahBaruTable = $('#table-uangmuka-tambah-baru').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    $('#filter-date input', 'form[name="filter-history"]').on('change', function () {
        $('form[name="filter-history"]').attr('action', baseURL + 'travel/spd/pengajuan#tab-history');
        $('form[name="filter-history"]').submit();
    });

    $(document).on('dp.show', '.date', function () {
        updateDatetimePickerView();
    });

    $(document).on('dp.update', '.date', function () {
        updateDatetimePickerView();
    });

    function updateDatetimePickerView() {
        $('td.disabled[data-day]', modalPengajuan).each(function (i, v) {
            var isDisabled = jQuery.inArray(moment($(v).attr('data-day'), 'DD/MM/YYYY'), tanggal_travels);
            if (isDisabled) {
                $(v).addClass('traveled');
            }
        });
    }

    $(document).on('click', '#btn-add-pengajuan', function () {
        $('#tipe_trip_single', modalPengajuan)
            .prop('checked', true)
            .trigger('change');
        $('#div_detail_add_btn').hide();
        $('li > a[href="#modal-tab-pengajuan"]', modalPengajuan).tab("show");
        modalPengajuan.modal('show');
    });

    $(document).on('change.select2', '.select-area, .select-tujuan', function (cb) {
        const divInputLain = $(this).closest('.panel-body').find('.input-tujuan_lain');
        const selectedArea = $(this).closest('.panel-body').find('.select-area');
        const selectedAreaOption = selectedArea.find('option:selected');
        const selectedOption = $(this).find('option:selected');
        if (selectedOption.data('free')) {
            divInputLain.closest('.form-group').removeClass('hide');
            divInputLain.val('');
            divInputLain
                .attr('required', true)
                .attr('maxlength', input_max_length); //- selectedAreaOption.html().length
        } else {
            divInputLain.closest('.form-group').addClass('hide');
            divInputLain
                .attr('required', false);
        }
    });

    $(document).on('change', '#activity', function () {
        $('.select-country').trigger('change');
    });

    $(document).on('change', '.select-country', function () {
        const activity = $('select[name="activity"]').val();
        let selectArea = $(this).closest('.panel-body').find('.select-area');
        let selectTujuan = $(this).closest('.panel-body').find('.select-tujuan');

        $.ajax({
            url: baseURL + 'travel/spd/get/tujuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                country: $(this).val(),
                activity: activity,
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    selectArea.find('option').remove();
                    selectTujuan.find('option').remove();

                    if (data.pabrik.length > 0) {
                        selectArea.closest('.form-group').removeClass('hide');

                        $.each(data.pabrik, function (i, t) {
                            const option = new Option(t.label, t.value, false, false);
                            option.setAttribute('data-free', t.free);
                            selectArea.append(option);
                        });

                        const lainlain = new Option('Lain-lain', 'lain', false, false);
                        lainlain.setAttribute('data-free', '0');
                        selectArea.append(lainlain);

                        if (!selectArea.data('select2')) {
                            selectArea.select2();
                        }
                    } else {
                        if (selectArea.data('select2')) {
                            selectArea.select2('destroy');
                        }
                        selectArea.closest('.form-group').addClass('hide');
                    }

                    $.each(data.tujuan, function (i, t) {
                        const option = new Option(t.label, t.value, false, false);
                        option.setAttribute('data-free', t.free);
                        selectTujuan.append(option);
                    });

                    if (selectArea.data('selected')) {
                        selectArea.val(selectArea.data('selected'));
                    }

                    if (selectTujuan.data('selected')) {
                        selectTujuan.val(selectTujuan.data('selected'));
                    }

                    selectArea.trigger('change');
                    // selectTujuan.trigger('change');
                    manipulateTripUangmuka();
                } else {
                    KIRANAKU.alert('NotOK', data.msg, 'warning', 'no');
                }
            }
        });
    });

    $(document).on('change', '.select-area', function () {
        const activity = $('select[name="activity"]').val();
        let selectCountry = $(this).closest('.panel-body').find('.select-country');
        let selectTujuan = $(this).closest('.panel-body').find('.select-tujuan');

        /** load api tujuan */
        $.ajax({
            url: baseURL + 'travel/spd/get/tujuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                country: selectCountry.val(),
                personal_area: $(this).val(),
                activity: activity,
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    selectTujuan.find('option').remove();

                    $.each(data.tujuan, function (i, t) {
                        const option = new Option(t.label, t.value, false, false);
                        option.setAttribute('data-free', t.free);
                        selectTujuan.append(option);
                    });
                    if (selectTujuan.data('selected')) {
                        selectTujuan.val(selectTujuan.data('selected'));
                    }

                    selectTujuan.trigger('change');

                    const idEdit = $('input[name="id_travel_header"]', modalPengajuan).val();
                    if (data.transport_options && KIRANAKU.isNullOrEmpty(idEdit)) {

                        $('input[name="transport[]"]', modalPengajuan)
                            .prop('checked', false)
                            .trigger('change');

                        const transports = data.transport_options.split(',');
                        $.each(transports, function (i, e) {
                            $('input[name="transport[]"]', modalPengajuan)
                                .filter('[value=' + e + ']')
                                .prop('checked', true)
                                .trigger('change');
                        });
                    }
                    manipulateTripUangmuka();
                } else {
                    KIRANAKU.alert('NotOK', data.msg, 'warning', 'no');
                }
            }
        });
    });

    /** Form trip uang muka events handler */
    let uangmukaOptions = [];
    let durasiSpd = 0;
    let expenseNo = 0;

    KIRANAKU.convertNumeric($('#total_um'));

    /** On change jumlah uangmuka pengajuan */
    $(document).on('change', '#total_um, .uangmuka-jumlah', function () {
        const totalUM = AutoNumeric.getNumber('#total_um');
        let total = 0;
        $('input.uangmuka-jumlah').each(function (i, v) {
            const val = AutoNumeric.getNumber('#' + $(v).attr('id'));
            total += parseFloat(val);
        });
        AutoNumeric.set('#sisa_um', totalUM - total);
        $('#sisa_um').valid();
    });

    /** On change jumlah uangmuka tambahan*/
    $(document).on('change', '#total_umt, .uangmukat-jumlah', function () {
        const modal = modalTambahUm;
        const totalUM = KIRANAKU.numericGet($('#total_umt', modal));
        let total = 0;
        $('input.uangmukat-jumlah', modal).each(function (i, v) {
            const val = KIRANAKU.numericGet($(v)); //AutoNumeric.getNumber('#' + $(v).attr('id'));
            total += parseFloat(val);
        });
        KIRANAKU.numericSet($('#sisa_umt', modal), totalUM - total);
        $('#sisa_umt', modal).valid();
    });

    function manipulateTripUangmuka() {
        let startDate = null;
        let endDate = null;

        var tipe_trip = $('input[name="tipe_trip"]').val();

        if (tipe_trip == 'multi') {
            if ($('.trip_start_datetime_multi').data('DateTimePicker')) {
                startDate = $('input[name="detail[' + 1 + '][start]"]').parents('.trip_start_datetime_multi')
                    .data("DateTimePicker")
                    .date()
                    ;
            }
        } else {
            if ($('.trip_start_datetime_multi').data('DateTimePicker')) {
                startDate = $('.trip_start_datetime_multi').data('DateTimePicker').date();
            }
        }

        if ($('.trip_end_datetime_multi').data('DateTimePicker')) {
            endDate = $('.trip_end_datetime_multi').data('DateTimePicker').date();
        }
    }

    /** Form trip detail event handler **/
    $(document).on('click', '.detail_delete', function () {
        const totalRow = multiTripTable.rows().count();
        if (totalRow > 1) {
            multiTripTable
                .row($(this).parents('tr'))
                .remove()
                .draw();
        }
    });

    let detailNo = 1;

    $(document).on('click', '#detail_add_btn', function () {
        detailNo++;
        let template = $('#multitrip_template').html();
        template = template.replaceAll('{no}', detailNo);
        let newTrip = $(template);

        $(newTrip).find('.detail_delete')
            .removeClass('hide');

        multiTripTable
            .row
            .add($(newTrip))
            .draw();

        $('.select2', newTrip).select2();

        $('.trip_start_datetime_multi', newTrip).datetimepicker(datetimepickerOptions);

        $('.select-country', newTrip).trigger('change');
        manipulateTripDateTime();
    });

    function manipulateTripDateTime() {
        const jenisSpd = $('input[name="tipe_trip"]:checked').val();

        if (jenisSpd === 'multi') {
            const allDatetimepicker = $('.trip_start_datetime_multi');
            let lastDate = null;
            $.each(allDatetimepicker, function (i, dp) {
                let dpData = $(dp).data('DateTimePicker');
                if (KIRANAKU.isNotNullOrEmpty(lastDate)) {
                    dpData.minDate(lastDate);

                    if (moment(dpData.date()).isBefore(moment(lastDate))) {
                        dpData.date(lastDate);
                    }
                }
                if (dpData.date()) {
                    lastDate = dpData.date().add(1, 'minutes');
                }
            });

            if (KIRANAKU.isNotNullOrEmpty(lastDate)) {
                const endDateMulti = $('.trip_end_datetime_multi').data('DateTimePicker');
                endDateMulti
                    .minDate(lastDate);
                if (moment(endDateMulti.date()).isBefore(moment(lastDate))) {
                    endDateMulti
                        .date(lastDate);
                }
            }
        }
    }

    $(document).on('change', '.select-trans', function () {
        var value_trans = $(this).val();
        var value_tanggal = $(this).closest('.template-trip').find('.select-tanggal-berangkat-multi').val();
        if (($.inArray('pesawat', value_trans) != -1 || $.inArray('kereta_bus', value_trans) != -1)
        ) {
            $('.select-tiket').prop('selectedIndex', 0).trigger('change.select2');
        } else {
            $('.select-tiket').val('').trigger('change.select2');
        }
    });

    $(document).on('dp.change', '.trip_end_datetime, .trip_end_datetime_multi', function ({ date, oldDate }) {
        manipulateTripUangmuka();
    });

    /** Form pengajuan submit action **/
    KIRANAKU.createValidator($('.form-edit-deklarasi'));

    $(document).on('click', 'button[name="simpan_btn"]', function (e) {
        const modal = $('#modal-spd-pengajuan');
        var form = $('.form-edit-deklarasi');
        const multiTripTotal = multiTripTable.rows().count();
        form.validate();
        var valid = form.valid();
        /** Validasi total multi trip */
        const tipeTrip = $('input[name="tipe_trip"]:checked').val();
        if (tipeTrip === 'multi') {
            if (!multiTripTotal) {
                KIRANAKU.alert('NotOK', 'Diwajibkan membuat tujuan apabila memilih jenis perjalanan Multi', 'warning', 'no');
                return false;
            }
        }
        /** Validasi transportasi */
        const totalTransportasi = $('input[name="transport[]"]:checked').length;

        //jejak
        if (valid) {
            var isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses();
                var formData = new FormData($(".form-edit-deklarasi")[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/pengajuan',
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
                    if ($('#modal-tab-pengajuan').has($(el.element)).length) {
                        switchTab = false;
                    }
                });
                if (switchTab) {
                    $('a[href="#modal-tab-um"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                } else {
                    $('a[href="#modal-tab-pengajuan"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                }
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', 'button[name="simpan_btn_tambah_um"]', function (e) {
        const modal = $('#modal-tab-um-tambahan');
        var form = $('.form-edit-deklarasi-um-tambah');
        const multiTripTotal = multiTripTable.rows().count();
        form.validate();
        var valid = form.valid();

        if (valid) {
            var isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses();
                var formData = new FormData($(".form-edit-deklarasi-um-tambah")[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/um_tambahan',
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
                    if ($('#modal-tab-pengajuan').has($(el.element)).length) {
                        switchTab = false;
                    }
                });
                if (switchTab) {
                    $('a[href="#modal-tab-um"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                } else {
                    $('a[href="#modal-tab-pengajuan"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                }
            }
        }
        e.preventDefault();
        return false;
    });

    /** List pengajuan edit clicked  **/
    $(document).on('click', '.spd-edit', function (e) {
        e.preventDefault();
        const modal = modalPengajuan;
        const form = $('.form-edit-deklarasi', modal);
        const tableMulti = $('#table-multi-trip', form);
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
                    const { pengajuan, details, optpabrik, opttujuan } = data.data;
                    $('input[name="id_travel_header"]', form).val(pengajuan.id_travel_header);

                    const tipe_trip = pengajuan.tipe_trip;
                    const tipe_trip_radios = $('input:radio[name="tipe_trip"]', form);

                    /** Set tipe_trip checked **/
                    tipe_trip_radios.filter('[value=' + tipe_trip + ']').prop('checked', true);
                    tipe_trip_radios.trigger('change');

                    $('input[name="no_hp"]').val(pengajuan.no_hp);
                    if (tipe_trip === 'singless') {
                        $('input[name="keperluan"]', form).val(pengajuan.keperluan);
                        $('select[name="country"]', form)
                            .val(pengajuan.country);

                        $('select[name="tujuan_persa"]', form).data('selected', pengajuan.tujuan_persa);
                        $('select[name="tujuan"]', form).data('selected', pengajuan.tujuan);

                        $('input[name="tujuan_lain"]', form).val(pengajuan.tujuan_lain);

                        /** Set datetime picker value **/
                        const startDatetime = moment(pengajuan.start_date + " " + pengajuan.start_time);
                        const minDatetime = moment(pengajuan.start_date + " 00:00");

                        // ================================================================================ 
                        var checked = $('input[name="tipe_trip"]:checked');
                        var modal = $('#modal-spd-pengajuan');
                        var divSingle = $('#div-single-trip', modal);
                        var divMultiple = $('#div-multi-trip', modal);
                        var multiTripTotal = multiTripTable.rows().count();

                        // add by ayy
                        var divSingleTrans = $('#div-single-trip-trans', modal);
                        divSingle.addClass('hide');
                        divMultiple.removeClass('hide');
                        divMultiple.find('input[name="detail_end"]').attr('disabled', false);
                        modal.find('.modal-dialog').addClass('modal-xl');
                        // add by ayy
                        divSingleTrans.addClass('hide');

                        multiTripTable.clear().draw();

                        $('#div_detail_add_btn').hide();
                        let template = $('#multitrip_template').html();
                        template = template.replaceAll('{no}', 1);
                        let newTrip = $(template);

                        multiTripTable
                            .row
                            .add($(newTrip))
                            .draw();

                        $('.select2', newTrip).select2();

                        $('.trip_start_datetime_multi', newTrip).datetimepicker(datetimepickerOptions);

                        $('.select-country', newTrip).trigger('change');
                        manipulateTripDateTime();

                        var options = Object.assign({ minDate: moment().subtract(backdated_max, 'days') }, datetimepickerOptions);
                        $('.trip_start_datetime').datetimepicker(options);
                        $('.trip_end_datetime, .trip_end_datetime_multi').datetimepicker(datetimepickerOptions);
                        $('.select-country:visible').trigger('change');
                        $('.trip_end_checkbox').trigger('change');

                        // ================================================================================

                        const transports = details.transportasi.split(',');
                        const transports_ticket = details.transportasi_tiket.split(',');
                        if (pengajuan.end_date) {
                            const endDatetime = moment(pengajuan.end_date + " " + pengajuan.end_time);
                            $('input[name="detail_end"]', form).parents('.date')
                                .data("DateTimePicker")
                                .date(endDatetime)
                                .minDate(minDatetime);
                        }

                        $.each(transports, function (i, e) {
                            $('input[name="transport[]"]', form)
                                .filter('[value=' + e + ']')
                                .prop('checked', true);
                        });

                        $('select[name="tiket_single[]"]', form).val(transports_ticket).trigger('change');

                        $('input:radio[name="jenis_penginapan"]', form)
                            .filter('[value="' + details.jenis_penginapan + '"]')
                            .prop('checked', true)
                            .trigger('change');

                        if (pengajuan.end_date) {
                            const endDatetime = moment(pengajuan.end_date + " " + pengajuan.end_time);
                            $('input[name="single_end"]', form).parents('.date')
                                .data("DateTimePicker")
                                .date(endDatetime);

                            $('input[name="single_end"]', form).parents('.date')
                                .data("DateTimePicker")
                                .minDate(startDatetime);
                        }

                        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 
                        $('input.multi-id-detail', tableMulti).val(details.id_travel_detail);
                        $('input.input-keperluan', tableMulti).val(details.keperluan);
                        $('select.select-tujuan', tableMulti).data('selected', details.tujuan);
                        $('select.select-country', tableMulti)
                            .val(pengajuan.country)
                            .trigger('change');

                        $('input.input-tujuan_lain', tableMulti).val(details.tujuan_lain);

                        $('select[name="detail[1][trans][]"]', form).val(transports).trigger('change');
                        $('select[name="detail[1][tiket][]"]', form).val(transports_ticket).trigger('change');
                        $('select[name="detail[1][inap]"]', form).val(details.jenis_penginapan).trigger('change');
                        $('select[name="detail[1][tujuan_persa]"]', form).data('selected', pengajuan.tujuan_persa);
                        $('select[name="detail[1][tujuan]"]', form).data('selected', pengajuan.tujuan);

                        $('input[name="detail[1][keperluan]"]', form).val(pengajuan.keperluan);
                        $('#no_hp').val(pengajuan.no_hp);
                        // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

                    } else {

                        var startDatetime = moment();
                        multiTripTable
                            .clear()
                            .draw();
                        if (tipe_trip == 'single') {
                            var no = 1;
                            // ====================================================================
                            var checked = $('input[name="tipe_trip"]:checked');
                            var modal = $('#modal-spd-pengajuan');
                            var divSingle = $('#div-single-trip', modal);
                            var divMultiple = $('#div-multi-trip', modal);
                            var multiTripTotal = multiTripTable.rows().count();

                            // add by ayy
                            var divSingleTrans = $('#div-single-trip-trans', modal);
                            divSingle.addClass('hide');
                            divMultiple.removeClass('hide');
                            divMultiple.find('input[name="detail_end"]').attr('disabled', false);
                            modal.find('.modal-dialog').addClass('modal-xl');
                            // add by ayy
                            divSingleTrans.addClass('hide');

                            multiTripTable.clear().draw();

                            $('#div_detail_add_btn').hide();
                            let template = $('#multitrip_template').html();
                            template = template.replaceAll('{no}', 1);
                            let newTrip = $(template);

                            multiTripTable
                                .row
                                .add($(newTrip))
                                .draw();

                            $('.select2', newTrip).select2();
                            $('.trip_start_datetime_multi', newTrip).datetimepicker(datetimepickerOptions);
                            $('.select-country', newTrip).trigger('change');

                            manipulateTripDateTime();

                            var options = Object.assign({ minDate: moment().subtract(backdated_max, 'days') }, datetimepickerOptions);
                            $('.trip_start_datetime').datetimepicker(options);
                            $('.trip_end_datetime, .trip_end_datetime_multi').datetimepicker(datetimepickerOptions);

                            $('.select-country:visible').trigger('change');

                            $('.trip_end_checkbox').trigger('change');
                            $('select[name="detail[' + no + '][tujuan_persa]"]', form).data('selected', pengajuan.tujuan_persa);
                            $('select[name="detail[' + no + '][tujuan]"]', form).data('selected', pengajuan.tujuan);

                            // ====================================================================
                            $('input[name="detail[' + no + '][id]"]', form).val(details.id_travel_detail);
                            $('input[name="detail[' + no + '][keperluan]"]', form).val(details.keperluan);
                            $('select[name="detail[' + no + '][country]"]', form)
                                .val("ID")
                                .trigger('change');
                            var option_trpersa = "";
                            var option_trtujuan = "";
                            var tujuanary = [];
                            $.each(optpabrik, function (x, pabrik) {
                                option_trpersa += "<option value=" + pabrik.value + ">" + pabrik.label + "</option>";
                            });
                            var found_array = $.grep(opttujuan, function (v) {
                                return v.personal_area === details.tujuan_persa;
                            });
                            $.each(found_array, function (x, tujuan) {
                                if ($.inArray(tujuan.company_code, tujuanary) == -1) {
                                    tujuanary.push(tujuan.company_code);
                                    option_trtujuan += "<option value=" + tujuan.company_code + ">" + tujuan.personal_subarea_text + "</option>";
                                }
                            })
                            $('select[name="detail[' + no + '][tujuan]"]', form)
                                .append(option_trtujuan)
                                .val(details.tujuan)
                                .trigger("change");

                            $('select[name="detail[' + no + '][tujuan_persa]"]', form)
                                .append(option_trpersa)
                                .val(details.tujuan_persa)
                                .trigger("change");
                            $('select[name="detail[' + no + '][tujuan]"]', form).data('selected', details.tujuan);

                            $('input[name="detail[' + no + '][tujuan_lain]"]', form).val(details.tujuan_lain);

                            const transports = details.transportasi.split(',');
                            const transports_ticket = (details.transportasi_tiket + '').split(',');
                            $('select[name="detail[' + no + '][trans][]"]', form).val(transports).trigger('change.select2');
                            $('select[name="detail[' + no + '][tiket][]"]', form).val(transports_ticket).trigger('change.select2');
                            $('select[name="detail[' + no + '][inap]"]', form).val(details.jenis_penginapan).trigger('change.select2');
                            /** Set datetime picker value **/
                            startDatetime = moment(details.start_date + " " + details.start_time);

                            $('input[name="detail[' + no + '][start]"]', form).parents('.trip_start_datetime_multi')
                                .data("DateTimePicker")
                                .date(startDatetime);
                        } else {
                            var d = 1;
                            $.each(details, function (i, trip) {
                                var x = d + 1;
                                var no = i + x;

                                $.when($('#detail_add_btn').trigger('click')).done(function () {
                                    $('input[name="detail[' + no + '][id]"]', form).val(trip.id_travel_detail);
                                    $('input[name="detail[' + no + '][keperluan]"]', form).val(trip.keperluan);
                                    $('select[name="detail[' + no + '][country]"]', form)
                                        .val(trip.country)
                                        .trigger('change');
                                    $('select[name="detail[' + no + '][tujuan_persa]"]', form).data('selected', trip.tujuan_persa);
                                    $('select[name="detail[' + no + '][tujuan]"]', form).data('selected', trip.tujuan);

                                    $('input[name="detail[' + no + '][tujuan_lain]"]', form).val(trip.tujuan_lain);

                                    const transports = trip.transportasi.split(',');
                                    const transports_ticket = trip.transportasi_tiket.split(',');
                                    $('select[name="detail[' + no + '][trans][]"]', form).val(transports).trigger('change.select2');
                                    $('select[name="detail[' + no + '][tiket][]"]', form).val(transports_ticket).trigger('change.select2');
                                    $('select[name="detail[' + no + '][inap]"]', form).val(trip.jenis_penginapan).trigger('change.select2');

                                    /** Set datetime picker value **/
                                    startDatetime = moment(trip.start_date + " " + trip.start_time);

                                    $('input[name="detail[' + no + '][start]"]', form).parents('.trip_start_datetime_multi')
                                        .data("DateTimePicker")
                                        .date(startDatetime)
                                        ;
                                });
                            });
                        }

                        if (pengajuan.end_date) {
                            $('.trip_end_checkbox', form).prop('checked', true);
                            $('.trip_end_checkbox', form).trigger('changed');

                            const endDatetime = moment(pengajuan.end_date + " " + pengajuan.end_time);

                            $('.trip_end_datetime_multi')
                                .data("DateTimePicker")
                                .date(endDatetime)
                                .minDate(startDatetime);
                        }

                        /** add detail to single trip as alternative */
                        $('input[name="keperluan"]', form).val(pengajuan.keperluan);
                        $('select[name="country"]', form)
                            .val(pengajuan.country)
                            .trigger('change');
                        $('select[name="tujuan"]', form).data('selected', pengajuan.tujuan);
                        $('input[name="tujuan_lain"]', form).val(pengajuan.tujuan_lain);



                        /** Set datetime picker value **/
                        startDatetime = moment(pengajuan.start_date + " " + pengajuan.start_time);

                        $('input[name="single_start"]', form).parents('.date')
                            .data("DateTimePicker")
                            .date(startDatetime);

                        $('input[name="single_start"]', form).parents('.date')
                            .data("DateTimePicker")
                            .minDate(startDatetime);

                        if (pengajuan.end_date) {
                            $('.trip_end_checkbox', form).prop('checked', true);
                            $('.trip_end_checkbox', form).trigger('changed');
                            const endDatetime = moment(pengajuan.end_date + " " + pengajuan.end_time);
                            $('input[name="single_end"]', form).parents('.date')
                                .data("DateTimePicker")
                                .date(endDatetime);

                            $('input[name="single_end"]', form).parents('.date')
                                .data("DateTimePicker")
                                .minDate(startDatetime);
                        }
                    }

                    $('input[name="booking_brgkt"]', form)
                        .prop('checked', pengajuan.booking_brgkt)
                        .trigger('change');

                    $('input[name="booking_kembali"]', form)
                        .prop('checked', pengajuan.booking_kembali)
                        .trigger('change');

                    modalPengajuan.modal('show');
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

    /** List pengajuan tambah um clicked **/
    $(document).on('click', '.spd-tambah-um', function (e) {
        e.preventDefault();
        const modal = modalTambahUm;
        const idHeader = $(this).data('id');
        KIRANAKU.showLoading();
        uangmukaTambahTable
            .clear()
            .draw();
        uangmukaTambahBaruTable
            .clear()
            .draw();
        $.ajax({
            url: baseURL + 'travel/spd/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idHeader
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const { pengajuan, details, personel, downpayments, deklarasi, deklarasi_details, cancel } = data.data;
                    const tipe_trip = pengajuan.tipe_trip;

                    /** Detail perjalanan */
                    $('#label_activity', modal).html(pengajuan.activity_label);

                    if (tipe_trip === 'single') {
                        $('#label_tipe_trip', modal).html('Pulang Pergi');
                        $('#div-single-trip', modal).removeClass('hide');
                        $('#div-multi-trip', modal).addClass('hide');
                        $('#label_keperluan', modal).html(pengajuan.keperluan);
                        $('#label_tujuan', modal).html(pengajuan.tujuan_lengkap);
                        $('#label_single_start', modal).html(pengajuan.tanggal_berangkat);
                        $('#label_single_end', modal).html(pengajuan.tanggal_kembali);
                    } else {
                        $('#label_tipe_trip', modal).html('Multi Perjalanan');
                        $('#div-multi-trip', modal).removeClass('hide');
                        $('#div-single-trip', modal).addClass('hide');
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
                    $('#label_booking_kembali, #label_booking_brgkt', modal)
                        .prop('disabled', false)
                        .trigger('change');
                    $('#label_booking_brgkt', modal)
                        .prop('checked', pengajuan.booking_brgkt)
                        .trigger('change');
                    $('#label_booking_kembali', modal)
                        .prop('checked', pengajuan.booking_kembali)
                        .trigger('change');
                    $('#label_booking_kembali, #label_booking_brgkt', modal)
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
                        $('#div-um-sebelum', modal).removeClass('hide');
                        $('#div-um-sebelum-none', modal).addClass('hide');

                        let firstCurrency = '';

                        uangmukaTambahTable
                            .clear()
                            .draw();
                        let template = $('#t_detail_uangmuka_template', modal).html();
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

                            uangmukaTambahTable
                                .row
                                .add($(expense))
                                .draw();
                        });
                        $('.label_total_um_currency', modal).html(firstCurrency);
                    } else {
                        $('#div-um-sebelum', modal).addClass('hide');
                        $('#div-um-sebelum-none', modal).removeClass('hide');
                    }

                    /** Auto click tab pengajuan */
                    $('a[href="#modal-tab-detail-pengajuan"]', modal).trigger('click');
                    $('input[name="id_travel_header"]', modal).val(idHeader);
                    $('#id_travel_header_tambahan_um', modal).val(idHeader);
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

    /** List pengajuan delete clicked */
    $(document).on("click", ".spd-delete", function (e) {
        var id = $(this).attr("data-id");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    KIRANAKU.hideLoading();
                    $.ajax({
                        url: baseURL + 'travel/spd/delete/pengajuan',
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

    /** List pengajuan delete clicked */
    $(document).on("change", ".select-tiket", function (e) {
        var thisvalue = $(this).val();
        var texthtml = $("option:selected", this).html();
        const modal = $(this).closest(".modal").attr("id");

        $(this).val(null).trigger('change.select2');

        if (thisvalue.length > 0) {
            if ($.inArray('0', thisvalue) != -1) { // if tanpa pemesanan
                $(this).select2('destroy');
                $(this).removeAttr('multiple');
                $(this).select2();
                $(this).val("0").trigger('change.select2');
            } else { // if selain tanpa pemesanan\
                $(this).select2('destroy');
                $(this).attr('multiple', 'multiple');
                $(this).select2();
                $(this).val(thisvalue).trigger('change.select2');
            }
        }

    });

    $(document).on('dp.change', '.trip_start_datetime_multi', function (selected) {
        if ($("#div_kembali").hasClass('hide')) {
            $("#div_kembali").removeClass('hide');
        }
        $('.dt_end').data("DateTimePicker").minDate(selected.date);
    });

    $(document).on('dp.change', '#set_durasi', function ({ date, oldDate }) {
        var toDate = moment(date).format("DD.MM.YYYY");
        var durasi_spd = moment(date).diff($('input[name="start_date_label_hidden"]').val(), 'days') + 1;
        $("input[name='durasi_label']").val(durasi_spd);
        $("input[name='end_date_label']").val(toDate);
        $("input[name='biaya[0][jumlah]']").attr('numeric-max', (durasi_spd * 90000));
        $("input[name='biaya[0][jumlah]']").val(rupiah(durasi_spd * 90000));
        configDateCuti();
    });

    $(document).on("click", "button[name='add_cuti']", function () {
        addListCuti();
        cekListCuti();
    });

    $(document).on("click", ".remove_cuti", function (e) {
        $(this).closest("tr.row-cuti").remove();
        cekListCuti();
    });
});

function generate_data_trip(dataheader, rowtrip, single) {
    var output = '<div class="row rowtrip" id="rowtrip' + rowtrip + '"   data-rowtrip="' + rowtrip + '">';

    var countries = '<option value="0">Silahkan pilih</option>';
    var selected_countries = "";
    $.each(dataheader.countries, function (i, v) {
        if (v.country_code === 'ID') {
            selected_countries = 'selected';
        }
        countries += '<option value="' + v.country_code + '" ' + selected_countries + '>' + v.country_name + '</option>';
        selected_countries = "";
    });

    var trans = "";
    $.each(dataheader.transports, function (i, v) {
        trans += '<option value="' + v.kode + '">' + v.nama + '</option>';
    });

    // TUJUAN - country, persa, lokasi, lain-lain
    output += '		<div class="col-sm-3">';
    output += '		    <div class="form-group">';
    output += '		        <label for="">Tujuan</label>';
    output += '			        <div class="panel panel-default">';
    output += '			            <div class="panel-heading clickable panel-collapsed" style="padding: 7px 15px !important;">';
    output += '				            <h3 class="panel-title">Detail Tujuan</h3>';
    output += '			                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-down"></i></span>';
    output += '		                </div>';
    output += '		                <div class="panel-body" style="display:none;">';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Negara</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-globe colors-purple"></span></span>';
    output += '		                            <select class="form-control select2 select-country" name="detail[' + rowtrip + '][country]" required>';
    output += countries;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Pabrik</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-building colors-tosca"></span></span>';
    output += '		                            <select class="form-control select2 select-area" name="detail[' + rowtrip + '][tujuan_persa]" required>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Area</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-map-marker colors-peach"></span></span>';
    output += '		                            <select class="form-control select2 select-tujuan" name="detail[' + rowtrip + '][tujuan]" required>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group hide lain-lain">';
    output += '		                        <label for="validate-select">Lain - Lain</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-pencil colors-orange1"></span></span>';
    output += '		                            <input type="text" class="form-control input-tujuan_lain" name="detail[' + rowtrip + '][tujuan_lain]" placeholder="">';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                </div>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>'; //col-sm-4

    //keperluan
    output += '		    <div class="col-sm-3">';
    output += '		        <div class="form-group">';
    output += '		            <label for="validate-select">Keperluan</label>';
    output += '		            <div class="input-group">';
    output += '		                <span class="input-group-addon"><span class="fa fa-pencil colors-peach"></span></span>';
    output += '		                <textarea type="text" style="height:37px !important;" class="form-control input-keperluan" name="detail[' + rowtrip + '][keperluan]"></textarea>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>';

    // Tanggal Keberangkatan
    output += '		    <div class="col-sm-3">';
    output += '		        <div class="form-group">';
    output += '		            <label for="validate-select">Tanggal Keberangkatan</label>';
    output += '		            <div class="input-group dt_start trip_start_datetime_multi">';
    output += '		                <span class="input-group-addon"><span class="fa fa-calendar colors-tosca"></span></span>';
    output += '		                <input type="text" data-date-format="DD.MM.YYYY HH:mm:ss" class="form-control select-tanggal-berangkat-multi" name="detail[' + rowtrip + '][start]" id="tgl_starts" required>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>';

    // TRANSPORTASI - transportasi, pemesanan, penginapan
    output += '		<div class="col-sm-3">';
    output += '		    <div class="form-group">';
    output += '		        <label for="">Akomodasi</label>';
    output += '			        <div class="panel panel-default">';
    output += '			            <div class="panel-heading clickable panel-collapsed" style="padding: 7px 15px !important;">';
    output += '				            <h3 class="panel-title">Detail Akomodasi</h3>';
    output += '			                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-down"></i></span>';
    output += '		                </div>';
    output += '		                <div class="panel-body" style="display:none;">';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Transportasi</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-plane colors-orange2"></span></span>';
    output += '		                            <select class="form-control select2 select-trans" multiple="multiple" name="detail[' + rowtrip + '][trans][]" required>';
    output += trans;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Jenis Tiket</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-ticket colors-peach"></span></span>';
    output += '		                            <select class="form-control select2 select-tiket" multiple="multiple" name="detail[' + rowtrip + '][tiket][]" required>';
    output += '		                                <option value="berangkat">Berangkat</option>';
    output += '		                                <option value="pulang">Pulang</option>';
    output += '		                                <option value="0">Tanpa Pemesanan</option>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Penginapan</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-building colors-green"></span></span>';
    output += '		                            <select class="form-control select2 select-inap" name="detail[' + rowtrip + '][inap]" required>';
    output += '		                                <option value="mess">MESS</option>';
    output += '		                                <option value="hotel">HOTEL</option>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                </div>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>'; //col-sm-4
    output += '</div>';
    if (single) {
        $("#pengajuan_single").append(output);
        $("#pengajuan_single .select2").select2();
    } else {
        $("#form_multi").append(output);
        $("#form_multi .select2").select2();
    }
    $('.dt_start').datetimepicker({
        showTodayButton: true,
        sideBySide: true,
    });
    $('.dt_end').datetimepicker({
        showTodayButton: true,
        sideBySide: true,
    });

}

function generate_data_trip_edit(data, rowtrip, single, detail) {
    const { pengajuan, optpabrik, opttujuan, optcountry, opttransport } = data.data;
    var output = '<div class="row rowtrip" id="rowtrip' + rowtrip + '"   data-rowtrip="' + rowtrip + '">';

    var countries = '<option value="0">Silahkan pilih</option>';
    var selected_countries = "";
    $.each(optcountry, function (i, v) {
        if (v.country_code === detail.country) {
            selected_countries = 'selected';
        }
        countries += '<option value="' + v.country_code + '" ' + selected_countries + '>' + v.country_name + '</option>';
        selected_countries = "";
    });

    var tujuan_persa = '<option value="0">Silahkan pilih</option>';
    tujuan_persa += '<option value="lain">Lain-lain</option>';
    var selected_tujuan_persa = "";
    $.each(optpabrik, function (i, v) {
        if (v.value === detail.tujuan_persa) {
            selected_tujuan_persa = 'selected';
        }
        tujuan_persa += '<option value="' + v.value + '" ' + selected_tujuan_persa + '>' + v.label + '</option>';
        selected_tujuan_persa = "";
    });

    var tujuan = '<option value="0">Silahkan pilih</option>';
    tujuan += '<option value="lain">Lain-lain</option>';
    var selected_tujuan = "";
    $.each(opttujuan, function (i, v) {
        if (v.company_code === detail.tujuan && v.kode_jenis_aktifitas === pengajuan.activity) {
            selected_tujuan = 'selected';
        }
        tujuan += '<option value="' + v.company_code + '" ' + selected_tujuan + '>' + v.personal_subarea_text + '</option>';
        selected_tujuan = "";
    });



    var trans = "";
    $.each(opttransport, function (i, v) {
        trans += '<option value="' + v.kode + '">' + v.nama + '</option>';
    });

    // TUJUAN - country, persa, lokasi, lain-lain
    output += '		<div class="col-sm-3">';
    output += '		 <input type="hidden" name="detail[' + rowtrip + '][id]">';
    output += '		    <div class="form-group">';
    output += '		        <label for="">Tujuan</label>';
    output += '			        <div class="panel panel-default">';
    output += '			            <div class="panel-heading clickable panel-collapsed" style="padding: 7px 15px !important;">';
    output += '				            <h3 class="panel-title">Detail Tujuan</h3>';
    output += '			                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-down"></i></span>';
    output += '		                </div>';
    output += '		                <div class="panel-body" style="display:none;">';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Negara</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-globe colors-purple"></span></span>';
    output += '		                            <select class="form-control select2 select-country readonly" name="detail[' + rowtrip + '][country]" required>';
    output += countries;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Pabrik</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-building colors-tosca"></span></span>';
    output += '		                            <select class="form-control select2 select-area readonly" name="detail[' + rowtrip + '][tujuan_persa]" required>';
    output += tujuan_persa;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Area</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-map-marker colors-peach"></span></span>';
    output += '		                            <select class="form-control select2 select-tujuan readonly" name="detail[' + rowtrip + '][tujuan]" required>';
    output += tujuan;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group hide lain-lain">';
    output += '		                        <label for="validate-select">Lain - Lain</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-pencil colors-orange1"></span></span>';
    output += '		                            <input type="text" class="form-control input-tujuan_lain readonly" name="detail[' + rowtrip + '][tujuan_lain]" required>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                </div>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>'; //col-sm-4

    //keperluan
    output += '		    <div class="col-sm-3">';
    output += '		        <div class="form-group">';
    output += '		            <label for="validate-select">Keperluan</label>';
    output += '		            <div class="input-group">';
    output += '		                <span class="input-group-addon"><span class="fa fa-pencil colors-peach"></span></span>';
    output += '		                <textarea style="height:37px !important;" class="form-control input-keperluan readonly" name="detail[' + rowtrip + '][keperluan]" id="detail[' + rowtrip + '][keperluan]" required>' + detail.keperluan + '</textarea>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>';

    // Tanggal Keberangkatan
    output += '		    <div class="col-sm-3">';
    output += '		        <div class="form-group">';
    output += '		            <label for="validate-select">Tanggal Keberangkatan</label>';
    output += '		            <div class="input-group dt_start trip_start_datetime_multi">';
    output += '		                <span class="input-group-addon"><span class="fa fa-calendar colors-tosca"></span></span>';
    output += '		                <input type="text" data-date-format="DD.MM.YYYY HH:mm:ss" class="form-control select-tanggal-berangkat-multi readonly" name="detail[' + rowtrip + '][start]" required>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>';

    // TRANSPORTASI - transportasi, pemesanan, penginapan
    output += '		<div class="col-sm-3">';
    output += '		    <div class="form-group">';
    output += '		        <label for="">Akomodasi</label>';
    output += '			        <div class="panel panel-default">';
    output += '			            <div class="panel-heading clickable panel-collapsed" style="padding: 7px 15px !important;">';
    output += '				            <h3 class="panel-title">Detail Akomodasi</h3>';
    output += '			                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-down"></i></span>';
    output += '		                </div>';
    output += '		                <div class="panel-body" style="display:none;">';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Transportasi</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-plane colors-orange2"></span></span>';
    output += '		                            <select class="form-control select2 select-trans readonly" multiple="multiple" name="detail[' + rowtrip + '][trans][]" required>';
    output += trans;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Jenis Tiket</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-ticket colors-peach"></span></span>';
    output += '		                            <select class="form-control select2 select-tiket readonly" multiple="multiple" name="detail[' + rowtrip + '][tiket][]" required>';
    output += '		                                <option value="berangkat">Berangkat</option>';
    output += '		                                <option value="pulang">Pulang</option>';
    output += '		                                <option value="0">Tanpa Pemesanan</option>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Penginapan</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-building colors-green"></span></span>';
    output += '		                            <select class="form-control select2 select-inap readonly" name="detail[' + rowtrip + '][inap]" required>';
    output += '		                                <option value="Mess">MESS</option>';
    output += '		                                <option value="Hotel">HOTEL</option>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                </div>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>'; //col-sm-4


    output += '</div>';
    if (single) {
        $("#pengajuan_single").append(output);
        $("#pengajuan_single .select2").select2();
    } else {
        $("#form_multi").append(output);
        $("#form_multi .select2").select2();
    }
    $('.dt_start').datetimepicker({
        showTodayButton: true,
        sideBySide: true,
    });
    $('.dt_end').datetimepicker({
        showTodayButton: true,
        sideBySide: true,
    });

}

function generate_data_trip_disabled(data, rowtrip, single, detail) {
    const { pengajuan, optpabrik, opttujuan, optcountry, opttransport } = data.data;
    var output = '<div class="row rowtrip" id="rowtrip' + rowtrip + '"   data-rowtrip="' + rowtrip + '">';

    var countries = '<option value="0">Silahkan pilih</option>';
    var selected_countries = "";
    $.each(optcountry, function (i, v) {
        if (v.country_code === detail.country) {
            selected_countries = 'selected';
        }
        countries += '<option value="' + v.country_code + '" ' + selected_countries + '>' + v.country_name + '</option>';
        selected_countries = "";
    });

    var tujuan_persa = '<option value="0">Silahkan pilih</option>';
    tujuan_persa += '<option value="lain">Lain-lain</option>';
    var selected_tujuan_persa = "";
    $.each(optpabrik, function (i, v) {
        if (v.value === detail.tujuan_persa) {
            selected_tujuan_persa = 'selected';
        }
        tujuan_persa += '<option value="' + v.value + '" ' + selected_tujuan_persa + '>' + v.label + '</option>';
        selected_tujuan_persa = "";
    });

    var tujuan = '<option value="0">Silahkan pilih</option>';
    tujuan += '<option value="lain">Lain-lain</option>';
    var selected_tujuan = "";
    $.each(opttujuan, function (i, v) {
        if (v.company_code === detail.tujuan && v.kode_jenis_aktifitas === pengajuan.activity) {
            selected_tujuan = 'selected';
        }
        tujuan += '<option value="' + v.company_code + '" ' + selected_tujuan + '>' + v.personal_subarea_text + '</option>';
        selected_tujuan = "";
    });



    var trans = "";
    $.each(opttransport, function (i, v) {
        trans += '<option value="' + v.kode + '">' + v.nama + '</option>';
    });

    // TUJUAN - country, persa, lokasi, lain-lain
    output += '		<div class="col-sm-3">';
    output += '		 <input type="hidden" name="detail[' + rowtrip + '][id]">';
    output += '		    <div class="form-group">';
    output += '		        <label for="">Tujuan</label>';
    output += '			        <div class="panel panel-default">';
    output += '			            <div class="panel-heading clickable panel-collapsed" style="padding: 7px 15px !important;">';
    output += '				            <h3 class="panel-title">Detail Tujuan</h3>';
    output += '			                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-down"></i></span>';
    output += '		                </div>';
    output += '		                <div class="panel-body" style="display:none;">';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Negara</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-globe colors-purple"></span></span>';
    output += '		                            <select class="form-control select2 select-country readonly" name="detail[' + rowtrip + '][country]" disabled>';
    output += countries;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Pabrik</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-building colors-tosca"></span></span>';
    output += '		                            <select class="form-control select2 select-area readonly" name="detail[' + rowtrip + '][tujuan_persa]" disabled>';
    output += tujuan_persa;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Area</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-map-marker colors-peach"></span></span>';
    output += '		                            <select class="form-control select2 select-tujuan readonly" name="detail[' + rowtrip + '][tujuan]" disabled>';
    output += tujuan;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group hide lain-lain">';
    output += '		                        <label for="validate-select">Lain - Lain</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-pencil colors-orange1"></span></span>';
    output += '		                            <input type="text" class="form-control input-tujuan_lain readonly" name="detail[' + rowtrip + '][tujuan_lain]" required>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                </div>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>'; //col-sm-4

    //keperluan
    output += '		    <div class="col-sm-3">';
    output += '		        <div class="form-group">';
    output += '		            <label for="validate-select">Keperluan</label>';
    output += '		            <div class="input-group">';
    output += '		                <span class="input-group-addon"><span class="fa fa-pencil colors-peach"></span></span>';
    output += '		                <textarea style="height:37px !important;" class="form-control input-keperluan readonly" name="detail[' + rowtrip + '][keperluan]" id="detail[' + rowtrip + '][keperluan]" disabled>' + detail.keperluan + '</textarea>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>';

    // Tanggal Keberangkatan
    output += '		    <div class="col-sm-3">';
    output += '		        <div class="form-group">';
    output += '		            <label for="validate-select">Tanggal Keberangkatan</label>';
    output += '		            <div class="input-group dt_start trip_start_datetime_multi">';
    output += '		                <span class="input-group-addon"><span class="fa fa-calendar colors-tosca"></span></span>';
    output += '		                <input type="text" data-date-format="DD.MM.YYYY HH:mm:ss" class="form-control select-tanggal-berangkat-multi readonly" name="detail[' + rowtrip + '][start]" disabled>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>';

    // TRANSPORTASI - transportasi, pemesanan, penginapan
    output += '		<div class="col-sm-3">';
    output += '		    <div class="form-group">';
    output += '		        <label for="">Akomodasi</label>';
    output += '			        <div class="panel panel-default">';
    output += '			            <div class="panel-heading clickable panel-collapsed" style="padding: 7px 15px !important;">';
    output += '				            <h3 class="panel-title">Detail Akomodasi</h3>';
    output += '			                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-down"></i></span>';
    output += '		                </div>';
    output += '		                <div class="panel-body" style="display:none;">';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Transportasi</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-plane colors-orange2"></span></span>';
    output += '		                            <select class="form-control select2 select-trans readonly" multiple="multiple" name="detail[' + rowtrip + '][trans][]" disabled>';
    output += trans;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Jenis Tiket</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-ticket colors-peach"></span></span>';
    output += '		                            <select class="form-control select2 select-tiket readonly" multiple="multiple" name="detail[' + rowtrip + '][tiket][]" disabled>';
    output += '		                                <option value="berangkat">Berangkat</option>';
    output += '		                                <option value="pulang">Pulang</option>';
    output += '		                                <option value="0">Tanpa Pemesanan</option>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Penginapan</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-building colors-green"></span></span>';
    output += '		                            <select class="form-control select2 select-inap readonly" name="detail[' + rowtrip + '][inap]" disabled>';
    output += '		                                <option value="Mess">MESS</option>';
    output += '		                                <option value="Hotel">HOTEL</option>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                </div>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>'; //col-sm-4


    output += '</div>';
    if (single) {
        $("#pengajuan_single_disabled").append(output);
        $("#pengajuan_single_disabled .select2").select2();
    } else {
        $("#form_multi").append('bbb');
        $("#form_multi .select2").select2();
    }
    $('.dt_start').datetimepicker({
        showTodayButton: true,
        sideBySide: true,
    });
    $('.dt_end').datetimepicker({
        showTodayButton: true,
        sideBySide: true,
    });

}

function rupiah(num) {
    var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
    if (str.indexOf(",") > 0) {
        parts = str.split(",");
        str = parts[0];
    }
    str = str.split("").reverse();
    for (var j = 0, len = str.length; j < len; j++) {
        if (str[j] != ".") {
            output.push(str[j]);
            if (i % 3 == 0 && j < (len - 1)) {
                output.push(".");
            }
            i++;
        }
    }
    formatted = output.reverse().join("");
    return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
}

function addListCuti() {
    let output = `<tr class="row-cuti">
        <td>
            <div class="input-group ">
                <div class="input-group-addon"><i class="fa fa-calendar colors-tosca"></i></div>
                <input type="text" data-date-format="dd.mm.yyyy" class="form-control date-cuti" name="tanggal_cuti_add[]" required>
            </div>
        </td>
        <td><input type="text" class="form-control" name="cuti_add[]" required></td>
        <td><button type="button" class="btn btn-sm btn-danger remove_cuti" title="Remove"><i class="fa fa-trash"></i></button></td>
    </tr>`;
    $("#list-cuti").append(output);
    configDateCuti();
}

function cekListCuti() {
    if (!$("#list-cuti tr.row-cuti").length)
        $("#no-data-cuti").removeClass('hidden');
    else
        $("#no-data-cuti").addClass('hidden');
}

function configDateCuti() {
    let startDate = $("input[name='start_date_label']").val();
    let endDate = $("input[name='end_date_label']").val();

    if (startDate && endDate) {
        $(".date-cuti").datepicker({
            endDate: endDate,
            startDate: startDate,
            todayHighlight: true,
            disableTouchKeyboard: true,
            format: "dd.mm.yyyy",
            startView: "days",
            minViewMode: "days",
            autoclose: true
        });

        $(".date-cuti").datepicker('refresh');
    }
}