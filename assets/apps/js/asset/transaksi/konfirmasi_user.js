$(document).ready(function () {
    $(document).on("click", "button[name='action_btn']", function (e) {
        e.preventDefault();
        var empty_form = validate('form',true);
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                var formData = new FormData($("form")[0]);
                $.ajax({
                    url: baseURL + 'asset/maintenance/save/all/konfirmasi',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $("input[name='isproses']").val(0);
                        if (data.sts == 'OK') {
                            KIRANAKU.alert(data.sts, data.msg, 'success');
                        } else {
                            KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                    }
                });
                $("input[name='isproses']").val(0);
            } else {
                KIRANAKU.alert('OK', "Silahkan tunggu proses selesai.", 'info', 'no');
            }
        }
        return false;
    });
});