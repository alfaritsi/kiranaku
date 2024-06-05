$(document).ready(function () {

    $('#form-pengajuan-jalan,#form-pengajuan-inap,#form-pengajuan-bersalin,#form-pengajuan-frame,#form-pengajuan-lensa')
        .each(function (i, el) {
            $(el).validate({
                errorElement: "em",
                errorPlacement: function (error, element) {
                    // Add the `help-block` class to the error element
                    error.addClass("help-block");

                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.parent("label"));
                    } else {
                        error.appendTo(element.parents('.form-group > div').first());
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).parents(".form-group > div").first().addClass("has-error").removeClass("has-success");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).parents(".form-group > div").first().addClass("has-success").removeClass("has-error");
                }
            });
        });

    $('.monthPicker').datepicker({
        startView: 'year',
        minViewMode: "years",
        format: 'yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // endDate: new Date()
        endDate: "2022"
    });

    $(document).on('changeDate', '.monthPicker', function (e) {
        var form = $(this).parents('form');
        form[0].submit();
    });

    $('.btn-add-pengajuan').on('click', function (e) {
        e.preventDefault();
        var form = $(this).attr('data-form');
        var modal = null;
        switch (form) {
            case "Rawat Jalan":
                modal = $('#modal-pengajuan-jalan');
                break;
            case "Rawat Inap":
                modal = $('#modal-pengajuan-inap');
                break;
            case "Bersalin":
                modal = $('#modal-pengajuan-bersalin');
                break;
            case "Frame":
                modal = $('#modal-pengajuan-frame');
                break;
            case "Lensa":
                modal = $('#modal-pengajuan-lensa');
                break;
            default:
                break;
        }
        if (modal != null) {
            modal.modal('show');
            $(modal).on('shown.bs.modal', function (e) {
                $('.btn-reset', modal).trigger('click');

                $('input[name="id_fbk"]', modal).val(null);

                $('.div-kwitansi tbody tr:not(.template)').remove();

                modal.off(e);
            });
            modal.find()
        }
    });

    $('.jenis-sakit').on('change', function (e) {
        if ($('.form-pengajuan:visible').length)
            $('.form-pengajuan:visible').valid();
        var div_sakit_lain = $('.jenis_sakit_lain');
        if ($(this).val() == 999) {
            div_sakit_lain.removeClass('hide');
            div_sakit_lain.find('input[name="sakit"]').attr('required', 'required');
        } else {
            div_sakit_lain.addClass('hide');
            div_sakit_lain.find('input[name="sakit"]').attr('required', null);
        }
    });
    /** Event Form Inap **/
    $('#id_rs').on('change', function (e) {
        if ($('.form-pengajuan:visible').length)
            $('.form-pengajuan:visible').valid();
        var div_sakit_lain = $('#rs_lain');
        if ($(this).val() == 999) {
            div_sakit_lain.removeClass('hide');
            div_sakit_lain.find('input[name="rs"]').attr('required', 'required');
        } else {
            div_sakit_lain.addClass('hide');
            div_sakit_lain.find('input[name="rs"]').attr('required', null);
        }
    });

    $(document).on('change', '#biaya_kamar', function (e) {
        $('#total_inap').trigger('change');
    });

    $(document).on('change', '#total_inap', function (e) {
        var modal = $(this).parents('.modal');
        var total = AutoNumeric.getNumber('#total_inap');
        var plafon = AutoNumeric.getNumber('#plafon_inap');
        var aktual = AutoNumeric.getNumber('#biaya_kamar');
        var diff = (plafon / aktual);
        var estimasi = 0;
        if (diff > 1)
            estimasi = total;
        else
            estimasi = diff * total;

        AutoNumeric.set('#estimasi_inap', estimasi);
        AutoNumeric.set('#estimasi_inap_karyawan', total - estimasi);

    });
    /** Event Form Inap **/

    /** Event Form Bersalin **/
    $(document).on('change', 'input[name="jenis_persalinan"]', function () {
        var total = 0;
        var modal = $('#modal-pengajuan-bersalin')
        var kode = $('input[name="kode"]', modal);
        if ($('#jenis_persalinan_normal').prop('checked')) {
            total = $('#plafon_normal').val();
            kode.val('BBNR');
        }
        else {
            total = $('#plafon_cesar').val();
            kode.val('BBCS');
        }
        $('#plafon_bersalin').attr('value', total);
        AutoNumeric.set('#plafon_bersalin', total);
        $('#plafon_bersalin').trigger('change');
        $('#total_bersalin').attr('numeric-total-max', total);
        $('#total_bersalin').trigger('change');
        $('#total_bersalin').valid();
    });

    $(document).on('show.bs.modal', '#modal-pengajuan-bersalin', function () {
        $('#jenis_persalinan_cesar').trigger('change');
    });
    /** Event Form Bersalin **/

    /** Event Jumlah kwitansi **/
    $('.jumlah-kwitansi').on('keyup', function (e) {
        $(this).trigger('change');
    });

    $('.jumlah-kwitansi').on('change', function (e) {
        var jumlah = $(this).val();
        var div_kwitansi = $(this).parents('.form-group').find('.div-kwitansi');
        if ($(this).valid()) {
            if (jumlah > 0) {
                div_kwitansi.removeClass('hide');
                var template = div_kwitansi.find('.template');
                var table = div_kwitansi.find('table');
                var edited = table.find('tbody tr.edited');
                table.find('tr:not(".template,.edited") .amount').off('change');
                table.find('tbody tr:not(".template,.edited")').remove();
                for (var i = edited.length; i < jumlah; i++) {
                    var clone = template.clone();

                    clone.removeClass('template');
                    clone.removeClass('hide');
                    clone.find('input:not(input[type=hidden],input[type=file])').attr('required', 'required');
                    clone.find('input').each(function (j, el) {
                        var name = $(el).attr('name').replace('$', i);
                        $(el).attr('disabled', null);
                        $(el).attr('name', name);
                        $(el).attr('id', name.replace(/[^a-zA-Z ]/g, "") + "_" + i);
                    });
                    clone.html(clone.html().replace('$', i));

                    table.find('tbody').append(clone);
                }

                AutoNumeric.multiple('.div-kwitansi table tr:not(.edited) .numeric:not([readonly])', {
                    digitGroupSeparator: '.',
                    decimalCharacter: ',',
                    allowDecimalPadding: false,
                    decimalPlaces: 0,
                    modifyValueOnWheel: false
                });

                AutoNumeric.multiple('.div-kwitansi table .numeric[readonly]', {
                    digitGroupSeparator: '.',
                    decimalCharacter: ',',
                    allowDecimalPadding: false,
                    decimalPlaces: 0,
                    modifyValueOnWheel: false,
                    readOnly: true,
                    noEventListeners: true
                });

                let modePengajuan = $('.modal-pengajuan.in input[name="kode"]').val();

                let momentAllow = moment().subtract(1, 'month').add(1, 'day');
                if (
                    momentAllow.isBefore(moment(tanggal_join_allowed, "YYYY-MM-DD")) &&
                    (
                        modePengajuan === 'BBNR' || modePengajuan === 'BBCS'
                        || modePengajuan === 'BLNS' || modePengajuan === 'BBKI'
                    )
                )
                    momentAllow = moment(tanggal_join_allowed, "YYYY-MM-DD");

                $('.datepicker').datepicker({
                    format: 'dd.mm.yyyy',
                    startDate: momentAllow.format('DD.MM.YYYY'),
                    endDate: moment().format('DD.MM.YYYY'),
                    autoclose: true,
                    weekStart: 1,
                });

                $(document, '.datepicker:visible').on('changeDate', function (e) {
                    $('.form-pengajuan:visible').valid();
                });

                $('.form-pengajuan:visible').validate();
                $('input[name="total_kwitansi"]:visible').valid();
            } else
                div_kwitansi.addClass('hide');
        } else {
            div_kwitansi.addClass('hide');
        }
    });
    /** Event Jumlah kwitansi **/

    /** Event Button Add lampiran **/
    $(document).on('click', '.file-add', function () {
        var template = $(this).parents('.row-file').find('.template-file').clone();
        template.removeClass('template-file hide');
        template.find('input').attr('disabled', null);
        $('.row-file').append(template);
    });

    $(document).on('click', '.file-remove', function () {
        $(this).parents('.new-file').fadeOut(300, function () {
            $(this).remove()
        });
    });
    /** Event Button Add lampiran **/

    $(document).on('click', '.btn-reset', function (e) {
        e.preventDefault();
        var modal = $(this).parents('.modal-pengajuan');
        var form = $('.form-pengajuan:visible');
        form[0].reset();
        $('.jumlah-kwitansi').trigger('change');
        $('.select2').trigger('change');
        form.validate().resetForm();

        // $('.new-file:not(".template-file")').fadeOut(300, function () {
        //     $(this).remove()
        // });
    });

    $(document).on('keyup', '.amount:visible', function (e) {
        $(this).trigger('change');
    });

    $(document).on('change', '.amount:visible', function (e) {
        var total = 0;
        var modal = $(this).parents('.modal');
        $('.amount:visible').each(function (i, el) {
            total += AutoNumeric.getNumber('#' + $(el).attr('id'));
        });

        AutoNumeric.set('#' + modal.attr('id') + ' input[name="total_kwitansi"]', total);
        $('#' + modal.attr('id') + ' input[name="total_kwitansi"]').trigger('change');
        $('#' + modal.attr('id') + ' input[name="total_kwitansi"]').valid();
    });

    $(document).on('click', '.btn-simpan', function (e) {
        e.preventDefault();
        var form = $('.form-pengajuan:visible');
        form.validate();
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            var kode = $('input[name="kode"]', form).val();

            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                $.ajax({
                    url: baseURL + 'ess/medical/rfc/validasi_benefit',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        bplan: kode
                    },
                    success: function (data) {
                        if (data.sts == 'OK') {
                            var formData = new FormData(form[0]);
                            $.ajax({
                                url: baseURL + 'ess/medical/save/pengajuan',
                                type: 'POST',
                                dataType: 'JSON',
                                data: formData,
                                contentType: false,
                                cache: false,
                                processData: false,
                                success: function (data) {
                                    if (data.sts == 'OK') {
                                        window.location = data.redirect;

                                        /*swal('Success', data.msg, 'success').then(function () {
                                            $('.modal-pengajuan:visible').modal('hide');
                                            KIRANAKU.confirm({
                                                text: "Apa anda ingin cetak form Klaim Benefit?",
                                                icon: 'info',
                                                successCallback: function(){
                                                    window.location = data.redirect;
                                                },
                                                failCallback: function(){
                                                    location.reload();
                                                }
                                            });
                                        });*/
                                    } else {
                                        $("input[name='isproses']").val(0);
                                        swal('Error', data.msg, 'error');
                                    }
                                },
                                error: function (data) {
                                    $("input[name='isproses']").val(0);
                                    kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                                }
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(data.sts, data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
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
        return false;
    });

    $(document).on('click', '.edit', function (e) {
        var id = $(this).attr('data-edit');
        var modal = $('#modal-cutiijin');
        $.ajax({
            url: baseURL + 'ess/medical/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                e.preventDefault();
                var form = data.data.fbk_jenis;
                var modal = null;
                switch (form) {
                    case "jalan":
                        modal = $('#modal-pengajuan-jalan');
                        break;
                    case "inap":
                        modal = $('#modal-pengajuan-inap');
                        break;
                    case "bersalin":
                        modal = $('#modal-pengajuan-bersalin');
                        break;
                    case "frame":
                        modal = $('#modal-pengajuan-frame');
                        break;
                    case "lensa":
                        modal = $('#modal-pengajuan-lensa');
                        break;
                    default:
                        break;
                }
                if (modal != null) {
                    let pengajuan = data.data;
                    $('input[name="id_fbk"]', modal).val(pengajuan.id_fbk);
                    $('select[name="nama_pasien"]', modal).val(pengajuan.nama_pasien);
                    $('select[name="nama_pasien"]', modal).trigger('change');
                    $('select[name="id_fbk_sakit"]', modal).val(pengajuan.id_fbk_sakit);
                    $('select[name="id_fbk_sakit"]', modal).trigger('change');
                    $('input[name="sakit"]', modal).val(pengajuan.sakit);
                    $('select[name="id_rs"]', modal).val(pengajuan.id_rs);
                    $('select[name="id_rs"]', modal).trigger('change');
                    $('input[name="rs"]', modal).val(pengajuan.rs);

                    switch (pengajuan.fbk_jenis) {
                        case "lensa":
                        case "frame":
                        case "jalan":
                        case "bersalin":
                            AutoNumeric.set('#' + modal.attr('id') + ' input.plafon', pengajuan.sisa_plafon_akhir + pengajuan.total_biaya);
                            $('input[name="total_kwitansi"]', modal).attr('numeric-total-max', pengajuan.sisa_plafon_akhir + pengajuan.total_biaya);
                            break;
                    }

                    switch (pengajuan.jenis_persalinan) {
                        case "normal":
                            $('#jenis_persalinan_normal', modal).prop('checked', true);
                            $('#jenis_persalinan_cesar', modal).prop('checked', false);
                            $('input[name="jenis_persalinan"]', modal).trigger('change');
                            break;
                        case "cesar":
                            $('#jenis_persalinan_normal', modal).prop('checked', false);
                            $('#jenis_persalinan_cesar', modal).prop('checked', true);
                            $('input[name="jenis_persalinan"]', modal).trigger('change');
                            break;
                    }

                    $('input[name="jumlah_hari"]', modal).val(pengajuan.jumlah_hari);
                    $('input[name="jumlah_kwitansi"]', modal).val(pengajuan.jumlah_kwitansi);

                    $('textarea[name="keterangan"]', modal).val(pengajuan.keterangan);
                    AutoNumeric.set('#' + modal.attr('id') + ' input[name="biaya_kamar"]', pengajuan.biaya_kamar);
                    modal.modal('show');

                    modal.on('shown.bs.modal', function (e) {

                        $('.div-kwitansi table tbody tr:not(.template)', modal).remove();

                        $('input[name="jumlah_kwitansi"]', this).trigger('change');

                        pengajuan.kwitansi.forEach(function (kwitansi, i) {
                            $('input[name="kwitansi[' + i + '][id_fbk_kwitansi]"]', modal).val(kwitansi.id_fbk_kwitansi);
                            $('input[name="kwitansi[' + i + '][nomor]"]', modal).val(kwitansi.nomor_kwitansi);
                            $('input[name="kwitansi[' + i + '][tanggal]"]', modal).datepicker('setStartDate', moment(kwitansi.tanggal_kwitansi).subtract(1, 'month').toDate());
                            $('input[name="kwitansi[' + i + '][tanggal]"]', modal).datepicker('setEndDate', moment(kwitansi.tanggal_kwitansi).toDate());
                            $('input[name="kwitansi[' + i + '][tanggal]"]', modal).datepicker('setDate', moment(kwitansi.tanggal_kwitansi).toDate());
                            AutoNumeric.set('#' + modal.attr('id') + ' input[name="kwitansi[' + i + '][nominal]"]', kwitansi.amount_kwitansi);
                            $('#' + modal.attr('id') + ' input[name="kwitansi[' + i + '][nominal]"]').valid();
                            $('input[name="kwitansi[' + i + '][nominal]"]').trigger('change');

                            if (KIRANAKU.isNullOrEmpty(kwitansi.gambar, false, true)) {
                                let divFileinput = $('#div-lampiran-' + i + ' .fileinput', modal);
                                divFileinput.removeClass('fileinput-exists');
                                divFileinput.addClass('fileinput-new');
                                divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
                                // $('#gambar_old', modal).attr('value',null);
                            } else {
                                let divFileinput = $('#div-lampiran-' + i + ' .fileinput', modal);
                                divFileinput.removeClass('fileinput-new');
                                divFileinput.addClass('fileinput-exists');

                                divFileinput.find('.fileinput-zoom').attr('href', kwitansi.gambar);
                                divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
                                // $('#gambar_old', modal).attr(kwitansi.gambar);
                            }

                        });

                        $('.div-kwitansi table tbody tr:not(.template)', modal).addClass('edited');

                        $('#total_inap', modal).trigger('change');

                        modal.off(e);
                    });
                }
            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(".delete").on("click", function (e) {
        var id = $(this).attr("data-delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'ess/medical/delete/pengajuan',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg);
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );

    });

    $(document).on('change.bs.fileinput', '.fileinput', function (e) {
        var lampiran = $(this).find('input[type="file"]');
        var targetPreview = $(this).find('.fileinput-zoom');
        readURL(lampiran[0], targetPreview);
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

    AutoNumeric.multiple('.numeric:not([readonly])', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        allowDecimalPadding: false,
        decimalPlaces: 0
    });

    AutoNumeric.multiple('.numeric[readonly]', {
        digitGroupSeparator: '.',
        decimalCharacter: ',',
        allowDecimalPadding: false,
        readOnly: true,
        decimalPlaces: 0
    });
})
;