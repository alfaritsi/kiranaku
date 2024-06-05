$(document).ready(function () {

    $("#btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
    });

    $(".set_active").on("click", function (e) {
        var id = $(this).data("id");
        var action = $(this).data("action");

        $.ajax({
            url: baseURL + 'settings/approval/set_data/publish/' + action,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    });

    $(".delete").on("click", function (e) {
        var id = $(this).data("delete");
        $.ajax({
            url: baseURL + 'settings/approval/delete',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    });

    $(".edit").on("click", function (e) {
        var id = $(this).data("edit");
        $.ajax({
            url: baseURL + 'settings/approval/get_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $(".title-form").html("Edit Approval");
                $.each(data.data, function (i, v) {
                    $("#nik").val(v.nik);
                    $("#atasan").val(v.atasan);
                    $("#atasan_email").val(v.atasan_email);

                    $("input[name='id']").val(data.id);
                    $("#btn-new").removeClass("hidden");
                });
            }
        });
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate();
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-settings-approval")[0]);

                $.ajax({
                    url: baseURL + 'settings/approval/set_data/save',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            alert(data.msg);
                            location.reload();
                        } else {
                            alert(data.msg);
                            $("input[name='isproses']").val(0);
                        }
                    }
                });
            } else {
                alert("Silahkan tunggu proses selesai.");
            }
        }
        e.preventDefault();
        return false;
    });
});