$(document).ready(function () {
    Array.prototype.clean = function (deleteValue) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == deleteValue) {
                this.splice(i, 1);
                i--;
            }
        }
        return this;
    };

    $('#pilih_clear').on('click', function () {
        $('#nik_akses').empty().trigger('change');
    });

    $('#list-karyawan,#list-ktp').slimscroll({
        color: "rgba(0,0,0,0.8)",
        size: "5px"
    });

    $(".set_active").on("click", function (e) {
        var id = $(this).data("id");
        var action = $(this).data("action");

        $.ajax({
            url: baseURL + 'settings/menus/set_data/publish/' + action,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg, 'success');
                } else {
                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                }
            }
        });
    });

    $(".delete").on("click", function (e) {
        var id = $(this).data("delete");
        $.ajax({
            url: baseURL + 'settings/menus/delete',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg, 'success');
                } else {
                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                }
            }
        });
    });

    $(document).on('click', '.akses', function (e) {
        var id = $(this).data("akses");
        $.ajax({
            url: baseURL + 'settings/menus/get_nik_akses',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $("input[name='id']").val(id);
                $("#tabs-akses #nama").html(data.menu.nama);
                // var template = $('.hide.template');
                // var list = $('#list-karyawan');
                var i = 0;
                var selectNikAkses = $('#nik_akses');
                if (selectNikAkses.prop) {
                    var options = selectNikAkses.prop('options');
                }
                else {
                    var options = selectNikAkses.attr('options');
                }
                $('option', selectNikAkses).remove();
                for (var karyawan of data.karyawan) {
                    options[options.length] = new Option(
                        karyawan.nama_karyawan, karyawan.id_karyawan,
                        data.selected.indexOf(karyawan.id_karyawan) >= 0,
                        data.selected.indexOf(karyawan.id_karyawan) >= 0
                    );
                    i++;
                }
                var i = 0;
                var selectNikAksesKtp = $('#nik_akses_ktp');
                if (selectNikAksesKtp.prop) {
                    var optionsKtp = selectNikAksesKtp.prop('options');
                }
                else {
                    var optionsKtp = selectNikAksesKtp.attr('options');
                }
                $('option', selectNikAksesKtp).remove();
                for (var karyawan of data.user_ktp) {
                    optionsKtp[optionsKtp.length] = new Option(
                        karyawan.nama, karyawan.nik,
                        data.selected.indexOf(karyawan.nik) >= 0,
                        data.selected.indexOf(karyawan.nik) >= 0
                    );
                    i++;
                }

                $('#tabs-akses').removeClass('hide');
                $('#tabs-edit').addClass('hide');

                $('#nik_akses').multiselect('resync');
                $('#nik_akses_ktp').multiselect('resync');
                $('#nik_akses,#nik_akses_ktp').multiselect('option', 'menuWidth', '100%');
                $('#nik_akses,#nik_akses_ktp').multiselect('option', 'menuHeight', '200px');
                // $('#modal-hak-akses').modal('show');
            }
        });
    });

    $(".edit").on("click", function (e) {
        var id = $(this).data("edit");
        $.ajax({
            url: baseURL + 'settings/menus/get_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $(".title-form").html("Edit Menu");

                $('#tabs-akses').addClass('hide');
                $('#tabs-edit').removeClass('hide');
                // $('#tabs-tab-hak').removeClass('hide');

                $.each(data.data, function (i, v) {
                    $("#nama").val(v.nama);
                    $("#id_parent").val(v.id_parent).trigger('change');
                    $("#id_level").val(v.id_level).trigger('change');
                    $("#url").val(v.url);
                    $("#url_external").val(v.url_external);
                    $("#urutan").val(v.urutan);
                    $("#kelas").val(v.kelas);
                    $(".target[value='" + v.target + "']")
                        .prop('checked', true)
                        .trigger('change');
                    $(".oldportal[value=" + v.na_oldportal + "]")
                        .prop('checked', true)
                        .trigger('change');
                    if (v.departemen_akses != null) {
                        var depArray = v.departemen_akses.split('.').clean("");
                        $('#departemen_akses').val(depArray);
                    }
                    if (v.divisi_akses != null) {
                        var divArray = v.divisi_akses.split('.').clean("");
                        $('#divisi_akses').val(divArray);
                    }
                    if (v.notification_categories != null) {
                        var notifArray = v.notification_categories.split('.').clean("");
                        $('#notification_categories').val(notifArray).trigger('change');
                    }
                    // $('#divisi_akses,#departemen_akses').trigger('change');

                    $('#tabs-edit a[href="#tab-edit"]').tab('show');
                    $("input[name='id']").val(data.id);
                    $(".btn-new").removeClass("hidden");
                });

                $('#divisi_akses,#departemen_akses').multiselect('resync');

                let dataMenu = data.data[0];

                $('#nik_akses').val(null).trigger('change');

                $('#pilih_semua').off('click');

                $('#pilih_semua').on('click', function () {
                    $('#nik_akses').empty().trigger('change');
                    $.ajax({
                        url: baseURL + 'settings/menus/get_nik_akses',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            data: dataMenu
                        }
                    }).then(function (data) {
                        for (let item of data.items) {
                            item.selected = true;
                        }
                        $('#nik_akses').select2({
                            data: data.items
                        }).trigger('change');

                    });
                });
            }
        });
    });

    $('#menus-table').treetable({ expandable: true });

    $('#departemen_akses,#divisi_akses,#nik_akses,#nik_akses_ktp').on("multiselectopen", function (event, ui) {
        $(this).multiselect('option', 'menuWidth', '100%');
        $(this).multiselect('option', 'menuHeight', '200px');
    });
    $('#departemen_akses,#divisi_akses,#nik_akses,#nik_akses_ktp').multiselect({
        classes: 'form-control',
        buttonWidth: '100%',
        menuHeight: '200px',
        menuWidth: '100%'
    }).multiselectfilter();

    $(".btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate('.form-settings-menus');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                // $('#nik_akses2').val($('#nik_akses').val().join('.'));
                $('#divisi_akses2').val($('#divisi_akses').val().join('.'));
                $('#departemen_akses2').val($('#departemen_akses').val().join('.'));
                $('#notification_categories2').val($('#notification_categories').val().join('.'));
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-settings-menus")[0]);
                $.ajax({
                    url: baseURL + 'settings/menus/set_data/save',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg, 'success');
                            $("input[name='isproses']").val(0);
                        } else {
                            kiranaAlert(data.sts, data.msg, 'error', 'no');
                            $("input[name='isproses']").val(0);
                        }
                    }
                });
            } else {
                kiranaAlert('OK', "Silahkan tunggu proses selesai.", 'warning', 'no');
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on("click", "button[name='action_hak_btn']", function (e) {
        var empty_form = validate('.form-settings-hak-akses');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $('#nik_akses2').val(
                    $('#nik_akses').val().join('.')
                    + '.'
                    + $('#nik_akses_ktp').val().join('.')
                );
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-settings-hak-akses")[0]);
                $.ajax({
                    url: baseURL + 'settings/menus/set_data/save_hak',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg, 'success','no');
                            $('#tabs-edit').removeClass('hide');
                            $('#tabs-akses').addClass('hide');
                            $('option', '#nik_akses').remove();
                            $("input[name='isproses']").val(0);
                        } else {
                            kiranaAlert(data.sts, data.msg, 'error', 'no');
                            $("input[name='isproses']").val(0);
                        }
                    }
                });
            } else {
                kiranaAlert('OK', "Silahkan tunggu proses selesai.", 'warning', 'no');
            }
        }
        e.preventDefault();
        return false;
    });
});