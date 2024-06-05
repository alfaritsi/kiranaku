$(document).ready(function () {


    $("#btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
    });

    $(".edit").on("click", function (e) {
        var id = $(this).data("edit");
        $.ajax({
            url: baseURL + 'settings/users/get_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $(".title-form").html("Edit User");
                $.each(data.data, function (i, v) {
                    $("#username").val(v.username);
                    $("#username").attr('disabled','disabled');

                    $("input[name='id']").val(data.id);
                    $("#btn-new").removeClass("hidden");
                });
            }
        });
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate("#modalPassword");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-change-password")[0]);

                $.ajax({
                    url: baseURL + 'settings/users/set_data/save',
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