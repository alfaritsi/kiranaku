$(document).ready(function () {

    $(document).on('click', '[name="reset_btn"]', function (e) {
        e.preventDefault();
        var modal = $(this).parents('.modal-pengajuan');
        var form = $('.form-pengajuan:visible');
        form[0].reset();
        $('.select2', form).trigger('change');
        form.validate().resetForm();

        // $('.new-file:not(".template-file")').fadeOut(300, function () {
        //     $(this).remove()
        // });
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

    $('#absen_masuk,#absen_keluar', '#modal-pengajuan-bak').datetimepicker({
        sideBySide: true,
        keepOpen: true,
        format: 'HH:mm'
    });

    $('#absen_masuk', '#modal-pengajuan-bak').on('dp.change', function (e) {
        // $('#absen_keluar').datetimepicker('minDate', e.date);
    });

    $('#filter-date input,#lengkap_filter').on('change', function () {
        $(this).parents('form').submit();
    });

    $('#id_bak_alasan', '#modal-pengajuan-bak').on('change', function (e) {
        var div_alasan_lain = $(this).parents('.form-group').find('.alasan_lain');
        var modal = $('#modal-pengajuan-bak');
        if ($(this).val() == 99) {
            div_alasan_lain.removeClass('hide');
            div_alasan_lain.find('input[name="alasan"]').attr('required', 'required');
        }
        else {
            $('#div-absen',modal).removeClass('hide');
            $('#div-absen',modal).find('input').attr('required',true);
            div_alasan_lain.addClass('hide');
            div_alasan_lain.find('input[name="alasan"]').attr('required', null);
            if ($(this).val() == 8) {
                $('#div-absen',modal).find('input').attr('required',false);
                $('#div-absen', modal).addClass('hide');
            }
        }
    });

    $(document).on('click', '.bak-delete', function () {
        var id = $(this).attr("data-pengajuan");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'ess/bak/delete/pengajuan',
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

    $(document).on('click', '.bak-pengajuan,.bak-edit', function () {
        var id = $(this).attr('data-pengajuan');
        var jenis = $(this).attr('data-jenis');
        var modal = $('#modal-pengajuan-bak');
        $.ajax({
            url: baseURL + 'ess/bak/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.data.absen_masuk_enable) {
                    $('#tanggal_masuk', modal).datepicker({
                        format: 'dd.mm.yyyy',
                        autoclose: true,
                        weekStart: 1,
                        enableOnReadonly: false
                    });

                    $('#tanggal_masuk', modal).datepicker('setEndDate', moment(data.data.tanggal_absen).toDate());
                    $('#tanggal_masuk', modal).datepicker('setStartDate', moment(data.data.tanggal_absen).subtract(1, 'days').toDate());

                    $('#tanggal_masuk', modal).datepicker(
                        'setDate',
                        moment(
                            KIRANAKU.isNullOrEmpty(
                                data.data.tanggal_masuk,
                                data.data.tanggal_masuk,
                                data.data.tanggal_absen
                            )
                        ).toDate()
                    );
                    $('#absen_masuk', modal).prop('disabled', false);
                    $('#absen_masuk', modal).datetimepicker('enable');
                    $('#tanggal_masuk', modal).prop('readonly', false);
                } else {
                    $('#absen_masuk', modal).prop('disabled', true);
                    $('#absen_masuk', modal).datetimepicker('disable');
                    $('#tanggal_masuk', modal).prop('readonly', true);
                    $('#tanggal_masuk', modal).val(
                        moment(
                            KIRANAKU.isNullOrEmpty(
                                data.data.tanggal_masuk,
                                data.data.tanggal_masuk,
                                data.data.tanggal_absen
                            )
                        ).format('DD.MM.YYYY')
                    );
                }

                if (data.data.absen_keluar_enable) {
                    $('#tanggal_keluar', modal).datepicker({
                        format: 'dd.mm.yyyy',
                        autoclose: true,
                        weekStart: 1,
                        enableOnReadonly: false
                    });

                    $('#tanggal_keluar', modal).datepicker('setEndDate', moment(data.data.tanggal_absen).add(1, 'days').toDate());
                    $('#tanggal_keluar', modal).datepicker('setStartDate', moment(data.data.tanggal_absen).toDate());

                    $('#tanggal_keluar', modal).datepicker(
                        'setDate',
                        moment(
                            KIRANAKU.isNullOrEmpty(
                                data.data.tanggal_keluar,
                                data.data.tanggal_keluar,
                                data.data.tanggal_absen
                            )
                        ).toDate()
                    );
                    $('#absen_keluar', modal).prop('disabled', false);
                    $('#absen_keluar', modal).datetimepicker('enable');
                    $('#tanggal_keluar', modal).prop('readonly', false);
                } else {
                    $('#absen_keluar', modal).prop('disabled', true);
                    $('#absen_keluar', modal).datetimepicker('disable');
                    $('#tanggal_keluar', modal).prop('readonly', true);
                    $('#tanggal_keluar', modal).val(
                        moment(
                            KIRANAKU.isNullOrEmpty(
                                data.data.tanggal_keluar,
                                data.data.tanggal_keluar,
                                data.data.tanggal_absen
                            )
                        ).format('DD.MM.YYYY')
                    );
                }

                $("#id_bak_alasan", modal).attr('readonly', false);
                $('#id_bak', modal).val(data.data.enId);
                $("#jenis", modal).val(jenis);
                $('#tanggal_absen', modal).html(
                    moment(data.data.tanggal_absen).format('DD.MM.YYYY')
                );

                $('input[name="tanggal_masuk"]', modal).attr(
                    'value',
                    moment(
                        KIRANAKU.isNullOrEmpty(
                            data.data.tanggal_masuk,
                            data.data.tanggal_masuk,
                            data.data.tanggal_absen
                        )
                    ).format('DD.MM.YYYY')
                );
                $('input[name="tanggal_keluar"]', modal).attr(
                    'value',
                    moment(
                        KIRANAKU.isNullOrEmpty(
                            data.data.tanggal_keluar,
                            data.data.tanggal_keluar,
                            data.data.tanggal_absen
                        )
                    ).format('DD.MM.YYYY')
                );

                $('input[name="absen_masuk"]', modal).attr(
                    'value',
                    moment(data.data.absen_masuk_label, 'HH:mm:ss').format('HH:mm')
                );
                $('input[name="absen_keluar"]', modal).attr(
                    'value',
                    moment(data.data.absen_keluar_label, 'HH:mm:ss').format('HH:mm')
                );
                $('#absen_masuk', modal).datetimepicker(
                    'date',
                    moment(data.data.absen_masuk_label, 'HH:mm:ss').toDate()
                );
                $('#absen_keluar', modal).datetimepicker(
                    'date',
                    moment(data.data.absen_keluar_label, 'HH:mm:ss').toDate()
                );

                if (data.data.new_method == 0) {
                    $('#tanggal_masuk', modal).prop('readonly', true);
                    $('#tanggal_keluar', modal).prop('readonly', true);
                    $('#tanggal_keluar,#tanggal_masuk', modal).datepicker('remove');
                    $('#id_bak_alasan option[value="8"]', modal).attr('disabled','disabled');
                }else{
                    $('#id_bak_alasan option[value="8"]', modal).attr('disabled',null);
                }
                $('#id_bak_alasan', modal).select2("destroy").select2();

                if (data.data.id_bak_alasan == 0 && jenis != 0) {
                    $("#id_bak_alasan", modal).val(jenis);
                    $("#id_bak_alasan option[value='" + jenis + "']", modal).attr('selected', 'selected');
                    $("#id_bak_alasan", modal).attr('readonly', true);
                }
                else {
                    $('#id_bak_alasan').val(data.data.id_bak_alasan);
                    $("#id_bak_alasan option[value='" + data.data.id_bak_alasan + "']", modal).attr('selected', 'selected');
                }

                $('#id_bak_alasan').trigger('change');

                $('#alasan').val(data.data.alasan);
                $('#keterangan').html(data.data.keterangan);

                $('#modal-pengajuan-bak').modal('show');

            },
            error: function (data) {
                $("body .overlay-wrapper").find('.overlay').remove();
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', 'button[name="simpan_btn"]', function (e) {
        e.preventDefault();
        var form = $('.form-pengajuan');
        form.validate();
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'ess/bak/save/pengajuan',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('.modal-pengajuan:visible').modal('hide');
                                location.reload();
                            });
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
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });
});