$(document).ready(function () {
    $('#search-nik')
        .on('blur', function () {
            $(this).closest('form')[0].submit();
        })
        .on('keyup', function (e) {
            if (e.keyCode == 13)
                $(this).closest('form')[0].submit();
        });

    $(".edit").on("click", function (e) {
        var id = $(this).data("edit");
        $.ajax({
            url: baseURL + 'settings/adminstaff/get_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $.each(data.data, function (i, v) {
                    $(".inik").val(v.nik);
                    $(".inama").val(v.nama);
                    $(".itelepon").val(v.telepon);
                    $(".iimage").attr('src', v.user_image);

                    $("input[name='id']").val(data.id);
                    $('#modalEdit').modal('show');
                });
            }
        });
    });
    $(".detail").on("click", function (e) {
        var id = $(this).data("edit");
        $.ajax({
            url: baseURL + 'settings/adminstaff/get_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $.each(data.data, function (i, v) {
                    $(".inik").val(v.nik);
                    $(".inama").val(v.nama);
                    $(".itelepon").val(v.telepon);
                    $(".iemail").val(v.email);
                    $(".idepartemen").val(v.nama_departemen);
                    $(".iimage").attr('src', v.user_image);

                    $('#modalDetail').modal('show');
                });
            }
        });
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate(".form-settings-adminstaff");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-settings-adminstaff")[0]);

                $.ajax({
                    url: baseURL + 'settings/adminstaff/set_data/save',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg, 'success');
                        } else {
                            kiranaAlert(data.sts, data.msg, 'error', 'no');
                            $("input[name='isproses']").val(0);
                        }
                    }
                });
            } else {
                kiranaAlert('OK', "Silahkan tunggu proses selesai.", 'info', 'no');
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on("click", "button[name='import_btn']", function (e) {
        var empty_form = validate(".form-import-ext");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-import-ext")[0]);
                $.ajax({
                    url: baseURL + 'settings/adminstaff/set_data/import',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg, 'success');
                        } else {
                            kiranaAlert(data.sts, data.msg, 'error', 'no');
                            $("input[name='isproses']").val(0);
                        }
                    }
                });
            } else {
                kiranaAlert('OK', "Silahkan tunggu proses selesai.", 'info', 'no');
            }
        }
        e.preventDefault();
        return false;
    });
});