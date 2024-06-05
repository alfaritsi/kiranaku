$(document).ready(function () {
    'use strict';

    $(document).on('click', '#btn-new,button[type="reset"]', function (e) {
        location.reload();
    });

    // $(document).on('click', 'button[type="reset"]', function (e) {
    //     const form = $('.form-costcenter-declare');
    //     form[0].reset();
    //     $('select', form).trigger('change');
    // });

    $(document).on("click", ".edit", function (e) {
        e.preventDefault();
        const id = $(this).data('edit');
        const form = $('.form-costcenter-declare');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/costcenter_declare',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const editData = data.data;
                    $('.box-title').html('Edit Tipe Expense UM');
                    $('#btn-new').removeClass('hide');
                    $('input[name="id_travel_costcenter_declare"]', form).val(editData.id_travel_costcenter_declare);
                    $('#personal_area', form)
                        .val(editData.personal_area)
                        .trigger('change');
                    $('#activity', form)
                        .find('option')
                        .prop('selected', false)
                        .filter(function (i, v) {
                            return editData.activity_type.split('.').indexOf($(v).prop('value')) !== -1;
                        })
                        .prop('selected', true)
                        .trigger('change');
                    $('input[name="domestik"]', form).prop('checked', false);
                    $('input[name="domestik"][value="' + editData.domestik + '"]', form).prop('checked', true);
                    $('#kode_expense', form)
                        .find('option')
                        .prop('selected', false)
                        .filter(function (i, v) {
                            return editData.kode_expense.split('.').indexOf($(v).prop('value')) !== -1;
                        })
                        .prop('selected', true)
                        .trigger('change');
                    $('#cost_center', form)
                        .find('option')
                        .prop('selected', false)
                        .filter(function (i, v) {
                            return editData.cost_center.split('.').indexOf($(v).prop('value')) !== -1;
                        })
                        .prop('selected', true)
                        .trigger('change');

                    $('input[name="day_min"]', form).val(editData.day_min);
                    $('input[name="day_max"]', form).val(editData.day_max);
                    $('input[name="total_min"]', form).val(parseFloat(editData.total_min));
                    $('input[name="total_max"]', form).val(parseFloat(editData.total_max));
                    $('input[name="auto_total"]', form).prop('checked', editData.auto_total);
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
                        url: baseURL + 'travel/spd/delete/costcenter_declare',
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
        const form = $('.form-costcenter-declare');
        validate('.form-costcenter-declare', true);
        let valid = form.valid();

        if (valid) {
            let isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses(true);
                const formData = new FormData(form[0]);
                $.ajax({
                    url: baseURL + 'travel/spd/save/costcenter_declare',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        KIRANAKU.endProses(true);
                        if (data.sts === 'OK') {
                            KIRANAKU.alert(data.sts, data.msg, 'success', 'yes');
                        } else {
                            KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        KIRANAKU.endProses(true);
                        kiranaAlert('notOK', 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                KIRANAKU.alert(false, 'Silahkan tunggu sampai proses selesai.', 'info', 'no');
            }
        }
        e.preventDefault();
        return false;
    });


    $(document).on('click', '.select2-all', function (e) {
        const inpGroup = $(this).parents('.form-group');
        const select2 = $('.select2', inpGroup);
        $('option', select2).prop('selected', 'selected');
        select2.trigger('change');
    });
    $(document).on('click', '.select2-inverse', function (e) {
        const inpGroup = $(this).parents('.form-group');
        const select2 = $('.select2', inpGroup);
        const select2selected = select2.find(':selected');
        const select2unselected = $('option', select2).filter(function (i, v) {
            return select2selected.index(v) !== -1;
        });
        select2unselected.prop('selected', false);
        $('option', select2).filter(function (i, v) {
            return select2selected.index(v) === -1;
        }).prop('selected', true);
        select2.trigger('change');
    });
    $(document).on('click', '.select2-none', function (e) {
        const inpGroup = $(this).parents('.form-group');
        const select2 = $('.select2', inpGroup);
        $('option', select2).prop('selected', false);
        select2.trigger('change');
    });
});