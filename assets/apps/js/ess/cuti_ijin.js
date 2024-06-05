$(document).ready(function () {

    $(document).on('click', '[name="reset_btn"]', function (e) {
        e.preventDefault();
        var modal = $(this).parents('.modal-pengajuan');
        var form = $('.form-pengajuan:visible');
        form[0].reset();
        $('.select2', form).trigger('change');
        $('.tgl_awal_akhir', form).trigger('changeDate');
        validator.resetForm();
    });

    $('.btn-add-pengajuan').on('click', function (e) {
        e.preventDefault();
        tanggal_cuti_edit = [];
        var form = $(this).attr('data-form');
        var modal = $('#modal-cutiijin');
        $('#form-title').html(form);
        $('.jumlah-hari-label', modal).html(form.toLowerCase());
        $('#id_cuti', modal).val(null);
        $('#gambar_old', modal).val(null);
        if (form == "Cuti") {
            $('#jenis_form_cuti', modal).prop('checked', true);
            $('#jenis_form_cuti', modal).trigger('change');
            $('#alasan', modal).prop('required', null);
        } else {
            $('#jenis_form_ijin', modal).prop('checked', true);
            $('#jenis_form_ijin', modal).trigger('change');
            $('#alasan', modal).prop('required', true);

        }
        var sisa = $(this).attr('data-saldo');
        sisa = JSON.parse(sisa);
        if (typeof sisa != "undefined") {
            if (sisa.sisa == sisa.negatif)
                $('#saldo_help', modal).removeClass('hide');
            else
                $('#saldo_help', modal).addClass('hide');
            $('#saldo_cuti', modal).val(sisa.sisa);
            $('#saldo_negatif', modal).val(sisa.negatif);
            $('#saldo_cuti_label', modal).html(sisa.sisa);
        } else {
            $('#saldo_cuti', modal).val(0);
            $('#saldo_negatif', modal).val(0);
            $('#saldo_cuti_label', modal).html(0);
        }
        $('#tanggal_awal_lama', modal).val(null);
        $('#tanggal_akhir_lama', modal).val(null);
        $('#tanggal_awal', modal).datepicker('setDate', null);
        $('#tanggal_akhir', modal).datepicker('setDate', null);
        $('#alasan', modal).text(null);

        $('#div-lampiran').addClass('hide');
        $('#lampiran', '#div-lampiran').prop('required', null);

        $('#kode', modal).val(null);
        $('#kode', modal).trigger('change');

        $('#jumlah_cuti_label', modal).html(0);

        let divFileinput = $('#div-lampiran .fileinput', modal);
        divFileinput.removeClass('fileinput-exists');
        divFileinput.addClass('fileinput-new');
        divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
        $('#gambar_old', modal).attr('value', null);

        $('#modal-cutiijin').modal('show');
    });

    $('#form_filter, #filter-date input, #id_cuti_status_filter', 'form[name="filter-history"]').on('change', function () {
        $('form[name="filter-history"]').attr('action', baseURL + 'ess/cutiijin/pengajuan#tab-history');
        $('form[name="filter-history"]').submit();
    });

    $('[data-js=datepicker]').datepicker({
        format: 'dd.mm.yyyy',
        startDate: moment().subtract(2, 'months').format('DD.MM.YYYY'),
        endDate: moment().add(12, 'months').format('DD.MM.YYYY'),
        autoclose: true,
        todayHighlight: true,
        // todayBtn: true,
        // daysOfWeekDisabled: (ho == 'y') ? [0, 6] : [0],
        weekStart: 1,
        inputs: $('.tgl_awal_akhir'),
        beforeShowDay: function (date) {
            let curDate = moment(date).format('YYYY-MM-DD');
            if (tanggal_cuti.indexOf(curDate) >= 0) {
                if ($('#id_cuti').val() != "") {
                    var tanggal_awal = moment($('#tanggal_awal_lama').val());
                    var tanggal_akhir = moment($('#tanggal_akhir_lama').val());
                    if (!moment(date).isBetween(tanggal_awal, tanggal_akhir, null, '[]'))
                        return {
                            enabled: false,
                            classes: 'cuti'
                        }
                } else {
                    return {
                        enabled: false,
                        classes: 'cuti'
                    }
                }
            } else if (tanggal_dinas.indexOf(curDate) >= 0) {
                return {
                    enabled: false,
                    classes: 'cuti'
                }
            } else {
                let libur = tanggal_libur.indexOf(curDate) >= 0;
                return {
                    enabled: !libur,
                    classes: libur ? 'weekend' : ''
                };
            }
        }
    })
        .on('change', function (e) {
            validate(".form-pengajuan", true);
        });

    $('.tgl_awal_akhir').on('changeDate', function (e) {
        var jumlah_cuti = 0;

        if ($('#tanggal_awal').val() != "" && $('#tanggal_akhir').val() != "") {
            var tanggal_awal = moment($('#tanggal_awal').val(), 'DD.MM.YYYY');
            var tanggal_akhir = moment($('#tanggal_akhir').val(), 'DD.MM.YYYY');

            for (var m = moment(tanggal_awal); m.diff(tanggal_akhir, 'days') <= 0; m.add(1, 'days')) {
				console.log(m);
                if (
                    (
                        tanggal_libur.indexOf(m.format('YYYY-MM-DD')) == -1 &&
                        tanggal_cuti.indexOf(m.format('YYYY-MM-DD')) == -1
                    ) ||
                    tanggal_cuti_edit.indexOf(m.format('YYYY-MM-DD')) >= 0
                )
                    jumlah_cuti++;
            }
        }
        // $('#jumlah_cuti_label').html(jumlah_cuti);
        $('#jumlah_cuti_label').html(99);
    });

    $('#tanggal_awal').on('changeDate', function (e) {
        $('#tanggal_akhir').datepicker('setStartDate', e.date);
        if ($('#jenis_form_cuti').prop('checked')) {
            let saldo = parseInt($('#saldo_cuti').val());
            let negatif = parseInt($('#saldo_negatif').val());

            if (negatif < 0) {
                if (saldo > 0)
                    saldo = Math.abs(saldo);
                else if (saldo > negatif)
                    saldo = Math.abs(negatif + Math.abs(saldo));
                else
                    saldo = 0;
            }

            let endDate = moment(e.date).add(saldo, 'days');

            $.each(tanggal_libur, function (i, date) {
                let isAfterChoosen = moment(date).isAfter(moment(e.date));
                let isBeforeAllowed = moment(date).isBefore(endDate);
                if (isAfterChoosen && isBeforeAllowed) {
                    endDate.add(1, 'days');
                    return;
                }
            });

            endDate.subtract(1, 'days');

            $.each(tanggal_merah, function (i, date) {
                let isAfterChoosen = moment(date).isAfter(moment(e.date));
                let isBeforeAllowed = moment(date).isBefore(endDate);
                let isNotDayoff = tanggal_libur.indexOf(date);
                if (isAfterChoosen && isBeforeAllowed && isNotDayoff) {
                    endDate = moment(date);//.subtract(1, 'days');
                    return;
                }
            });

            $('#tanggal_akhir').datepicker('setEndDate', endDate.toDate());
        } else {
            let jumlah = parseInt($('#kode option:selected').attr('data-jumlah'));
            if ($('#jarak:checked').length > 0)
                jumlah += parseInt($('#jarak').val());

            // $('#tanggal_akhir').datepicker('setEndDate', moment(e.date).add(jumlah - 1, 'days').toDate());

            let endDate = moment(e.date).add(jumlah, 'days');

            $.each(tanggal_libur, function (i, date) {
                let isAfterChoosen = moment(date).isAfter(moment(e.date));
                let isBeforeAllowed = moment(date).isBefore(endDate);
                if (isAfterChoosen && isBeforeAllowed) {
                    endDate.add(1, 'days');
                    return;
                }
            });

            endDate.subtract(1, 'days');

            $.each(tanggal_merah, function (i, date) {
                let isAfterChoosen = moment(date).isAfter(moment(e.date));
                let isBeforeAllowed = moment(date).isBefore(endDate);
                let isNotDayoff = tanggal_libur.indexOf(date);
                if (isAfterChoosen && isBeforeAllowed && isNotDayoff) {
                    endDate = moment(date);//.subtract(1, 'days');
                    return;
                }
            });

            $('#tanggal_akhir').datepicker('setEndDate', endDate.toDate());
        }
        if (isNaN($('#tanggal_akhir').datepicker('getDate'))) {
            $('#tanggal_akhir').datepicker('setDate', e.date);
        }
    });


    $('input[name="form"]').on('change', function () {
        if ($('#modal-cutiijin').is(':visible')) {
            $('#tanggal_akhir,#tanggal_awal').datepicker('setDate', null);
            $('#tanggal_akhir,#tanggal_awal').datepicker('setStartDate', moment().subtract(2, 'months').format('DD.MM.YYYY'));
            $('#tanggal_akhir,#tanggal_awal').datepicker('setEndDate', moment().add(12, 'months').format('DD.MM.YYYY'));
        }
        if ($('#jenis_form_ijin').prop('checked')) {
            $('#div-jenis-ijin').removeClass('hide');
            $('.div-jenis-cuti').addClass('hide');
        }
        else {
            $('#div-jenis-ijin').addClass('hide');
            $('.div-jenis-cuti').removeClass('hide');
            $('#kode').val('');
            $('#kode').trigger('change');
        }

    });

    $('#jarak').change(function () {
        let jumlah = parseInt($('#kode option:selected').attr('data-jumlah'));
        if ($('#jarak:checked').length > 0)
            jumlah += parseInt($('#jarak').val());

        let endDate = moment($('#tanggal_awal').datepicker('getDate')).add(jumlah, 'days');

        $.each(tanggal_libur, function (i, date) {
            let isAfterChoosen = moment(date).isAfter(moment($('#tanggal_awal').datepicker('getDate')));
            let isBeforeAllowed = moment(date).isBefore(endDate);
            if (isAfterChoosen && isBeforeAllowed) {
                endDate.add(1, 'days');
                return;
            }
        });

        endDate.subtract(1, 'days');

        $.each(tanggal_merah, function (i, date) {
            let isAfterChoosen = moment(date).isAfter(moment($('#tanggal_awal').datepicker('getDate')));
            let isBeforeAllowed = moment(date).isBefore(endDate);
            let isNotDayoff = tanggal_libur.indexOf(date);
            if (isAfterChoosen && isBeforeAllowed && isNotDayoff) {
                endDate = moment(date);//.subtract(1, 'days');
                return;
            }
        });

        if (endDate.isSameOrBefore(moment($('#tanggal_akhir').datepicker('getDate')))) {
            $('#tanggal_akhir').datepicker('setDate', endDate.toDate());
        }

        $('#tanggal_akhir').datepicker('setEndDate', endDate.toDate());
    });
    $('#kode').on('change', function () {
        $('.form-pengajuan').valid();
        if ($('#kode option:selected').attr('data-jarak') == 1) {
            $('#div-jarak').removeClass('hide');
        } else {
            $('#jarak').prop('checked',false);
            $('#jarak').trigger('change');
            $('#div-jarak').addClass('hide');
        }

        if ($(this).val() == sakit_w_surat) {
            $('#div-lampiran').removeClass('hide');
            if (KIRANAKU.isNullOrEmpty($('#gambar_old').val(), false, true))
                $('#lampiran', '#div-lampiran').prop('required', true);
            else
                $('#lampiran', '#div-lampiran').prop('required', null);
            validator.valid();
        } else {
            $('#div-lampiran').addClass('hide');
            $('#lampiran', '#div-lampiran').prop('required', null);
        }

        if (!isNaN($('#tanggal_awal').datepicker('getDate'))) {
            let jumlah = parseInt($('#kode option:selected').attr('data-jumlah'));
            if (jumlah) {
                if ($('#jarak:checked').length > 0)
                    jumlah += parseInt($('#jarak').val());
            }

            let currentMoment = moment($('#tanggal_awal').datepicker('getDate'));

            let endDate = moment($('#tanggal_awal').datepicker('getDate')).add(jumlah, 'days');

            $.each(tanggal_libur, function (i, date) {
                let isAfterChoosen = moment(date).isAfter(moment($('#tanggal_awal').datepicker('getDate')));
                let isBeforeAllowed = moment(date).isBefore(endDate);
                if (isAfterChoosen && isBeforeAllowed) {
                    endDate.add(1, 'days');
                    return;
                }
            });

            endDate.subtract(1, 'days');

            $.each(tanggal_merah, function (i, date) {
                let isAfterChoosen = moment(date).isAfter(moment($('#tanggal_awal').datepicker('getDate')));
                let isBeforeAllowed = moment(date).isBefore(endDate);
                let isNotDayoff = tanggal_libur.indexOf(date);
                if (isAfterChoosen && isBeforeAllowed && isNotDayoff) {
                    endDate = moment(date);//.subtract(1, 'days');
                    return;
                }
            });

            if (endDate.isSameOrBefore(moment($('#tanggal_akhir').datepicker('getDate')))) {
                $('#tanggal_akhir').datepicker('setDate', endDate.toDate());
            }

            if (currentMoment.clone().add(1, 'year').isSameOrAfter(endDate)) {
                $('#tanggal_akhir').datepicker('setEndDate', endDate.toDate());
            }
            else
                $('#tanggal_akhir').datepicker('setEndDate', currentMoment.clone().add(1, 'year').toDate());
        }
    });

    validator = $('.form-pengajuan').validate({
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.appendTo(element.parents('.form-group > div'));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group > div").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group > div").addClass("has-success").removeClass("has-error");
        }

    });

    $(document).on("click", "button[name='simpan_btn']", function (e) {
        var form = $('.form-pengajuan:visible');
        form.validate();
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-pengajuan")[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'ess/cutiijin/save/pengajuan',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $("body .overlay-wrapper").find('.overlay').remove();
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('#modal-cutiijin').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function (data) {
                        KIRANAKU.hideLoading();
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', '.edit', function (e) {
        var id = $(this).attr('data-edit');
        var modal = $('#modal-cutiijin');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'ess/cutiijin/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                KIRANAKU.hideLoading();
                tanggal_cuti_edit = data.tanggal;

                $('#id_cuti', modal).attr('value', data.detail.id_cuti);
                $('#atasan', modal).attr('value', data.detail.atasan);
                $('#atasan_email', modal).attr('value', data.detail.atasan_email);
                $('#form-title').html(data.detail.form);
                $('.jumlah-hari-label', modal).html(data.detail.form.toLowerCase());
                if (data.detail.form == 'Cuti') {
                    $('#jenis_form_cuti', modal).prop('checked', true);
                    $('#jenis_form_cuti', modal).trigger('change');

                    var sisa = $('#btn-add-pengajuan-cuti').attr('data-saldo');
                    sisa = JSON.parse(sisa);
                    if (typeof sisa != "undefined") {
                        $('#saldo_cuti', modal).attr('value', sisa.sisa + data.detail.jumlah);
                        $('#saldo_negatif', modal).attr('value', sisa.negatif);
                        $('#saldo_cuti_label', modal).html(sisa.sisa + data.detail.jumlah);

                        if ((sisa.sisa + data.detail.jumlah) <= sisa.negatif)
                            $('#saldo_help', modal).removeClass('hide');
                        else
                            $('#saldo_help', modal).addClass('hide');
                    } else {
                        $('#saldo_cuti', modal).attr('value', data.detail.jumlah);
                        $('#saldo_negatif', modal).attr('value', 0);
                        $('#saldo_cuti_label', modal).html(data.detail.jumlah);
                    }
                }
                else {
                    $('#jenis_form_ijin', modal).prop('checked', true);
                    $('#jenis_form_ijin', modal).trigger('change');
                    $('#kode', modal).attr('value', data.detail.kode);
                    $('#kode', modal).val(data.detail.kode);
                    $('#kode', modal).trigger('change');
                    if (data.detail.jarak != 0) {
                        $('#jarak', modal).prop('checked', true);
                        $('#jarak', modal).trigger('change');
                    }
                }
                $('#tanggal_awal_lama', modal).attr('value', data.detail.tanggal_awal);
                $('#tanggal_akhir_lama', modal).attr('value', data.detail.tanggal_akhir);
                $('#tanggal_awal', modal).attr('value', moment(data.detail.tanggal_awal).format('DD.MM.YYYY'));
                $('#tanggal_akhir', modal).attr('value', moment(data.detail.tanggal_akhir).format('DD.MM.YYYY'));
                $('#tanggal_awal', modal).datepicker('setDate', moment(data.detail.tanggal_awal).format('DD.MM.YYYY'));
                $('#tanggal_akhir', modal).datepicker('setDate', moment(data.detail.tanggal_akhir).format('DD.MM.YYYY'));
                $('#alasan', modal).text(data.detail.alasan);
                if (KIRANAKU.isNullOrEmpty(data.detail.gambar, false, true)) {
                    let divFileinput = $('#div-lampiran .fileinput', modal);
                    divFileinput.removeClass('fileinput-exists');
                    divFileinput.addClass('fileinput-new');
                    divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
                    $('#lampiran', modal).attr('required', 'required');
                    $('#gambar_old', modal).attr('value', null);
                } else {
                    let divFileinput = $('#div-lampiran .fileinput', modal);
                    divFileinput.removeClass('fileinput-new');
                    divFileinput.addClass('fileinput-exists');
                    divFileinput.find('.fileinput-zoom').attr('href', data.detail.gambar);
                    divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
                    $('#lampiran', modal).attr('required', null);
                    $('#gambar_old', modal).attr('value', data.detail.gambar);
                }

                $('#modal-cutiijin').modal('show');
            },
            error: function (data) {
                KIRANAKU.hideLoading();
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $('.fileinput').on('change.bs.fileinput', function (e) {
        readURL($('#lampiran')[0], $('.fileinput-zoom'));
    });

    function readURL(input, targetPreview) {

        if (input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = function (e) {
                targetPreview.attr('href', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }


    $('#modal-cutiijin').on('shown.bs.modal', function (e) {
        validator.resetForm();
    });

    $('#popSaldoCuti').popover({
        html: true,
        content: function (data) {
            var html = $('#template-saldo').clone().removeAttr('id').removeClass('hidden');
            var list = $(this).data('list');
            $.each(list, function (i, v) {
                var sisa = v.jumlah - v.terpakai - v.pengajuan;
                $(html).find('tbody').append('<tr><td>' + v.nama + '</td><td>' + sisa +
                    ' Hari</td></tr>'
                )
            });
            return html;
        }
    });

    $(".delete").on("click", function (e) {
        var id = $(this).attr("data-delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    KIRANAKU.showLoading();
                    $.ajax({
                        url: baseURL + 'ess/cutiijin/delete/pengajuan',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        success: function (data) {
                            KIRANAKU.hideLoading();
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg);
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            KIRANAKU.hideLoading();
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );

    });
});

function openBase64(base64URL) {
    var win = window.open();
    win.document.write('<iframe src="' + base64URL + '" frameborder="0" style="border:0; top:0px; left:0px; bottom:0px; right:0px; width:100%; height:100%;" allowfullscreen></iframe>');
}