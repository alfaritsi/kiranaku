$(document).ready(function () {
    'use strict';
    const modalPenerimaan = $('#modal-spd-penerimaan');
    const modalPenerimaanDetail = $('#modal-detail-spd-booking');

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

    /** Form transportasi submit action **/
    validator = $('.form-penerimaan', modalPenerimaan).validate({
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

    $(document).on('click', '.spd-penerimaan', function (e) {
        e.preventDefault();
        const modal = modalPenerimaan;
        const idDetail = $(this).data('id');
        const idHeader = $(this).data('id-header');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/penerimaan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idDetail,
                id_header: idHeader,
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const { pengajuan, detail, personel } = data.data;

                    $('input[name="id_travel_header"]', modal).val(idHeader);
                    $('input[name="id_travel_detail"]', modal).val(idDetail);

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

                    $('#label_activity', modal).html(detail.activity);
                    $('#label_keperluan', modal).html(detail.keperluan);
                    $('#label_tujuan', modal).html(detail.tujuan_lengkap);
                    $('#label_single_start', modal).html(detail.tanggal_berangkat);
                    $('#label_single_end', modal).html(detail.tanggal_kembali);

                    $('.mess-input input[type="checkbox"][name="mess_available"]').prop('checked', false);
                    if (detail.jenis_penginapan === 'mess') {
                        $('.mess-input', modal).removeClass('hide');
                    } else {
                        $('.mess-input', modal).addClass('hide');
                    }

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
        const modal = modalPenerimaan;
        const form = $('.form-penerimaan', modalPenerimaan);
        form.validate();
        let valid = form.valid();

        if (valid) {
            const isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses();
                const formData = new FormData(form[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/penerimaan',
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
});