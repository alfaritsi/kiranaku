$(document).ready(function () {
    'use strict';

    $(document).on('click', '#btn-new', function (e) {
        location.reload();
    });

    $(document).on("click", ".edit", function (e) {
        e.preventDefault();
        const id = $(this).data('edit');
        const form = $('.form-mess-option');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/mess_options',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const editData = data.data;
                    $('.box-title').html('Edit Ketersediaan Mess');
                    $('#btn-new').removeClass('hide');
                    $('input[name="id_travel_mess_option"]', form).val(editData.id_travel_mess_option);
                    $('#persa', form)
                        .val(editData.persa)
                        .trigger('change');
                    $('#available', form)
                        .prop('checked', editData.available);
                }
                else {
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

    $(document).on("click", ".delete", function (e) {
        e.preventDefault();
        var id = $(this).data($(this).attr("class"));
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    KIRANAKU.hideLoading();
                    $.ajax({
                        url: baseURL + 'travel/spd/delete/mess_options',
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

    $(document).on("click", "button[name='action_btn']", function (e) {
        const form = $('.form-mess-option');
        validate('.form-mess-option', true);
        let valid = form.valid();

        if (valid) {
            let isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses();
                const formData = new FormData(form[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/mess_options',
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
                                location.reload();
                            });
                        } else {
                            KIRANAKU.endProses();
                            KIRANAKU.alert('OK', data.msg, 'error', 'no');
                        }
                    }
                });
            } else {
                KIRANAKU.alert(false, 'Silahkan tunggu sampai proses selesai.', 'info', 'no');
            }
        }
        e.preventDefault();
        return false;
    });
});