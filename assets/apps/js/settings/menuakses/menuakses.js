$(document).ready(function () {
    $('.edit').on('click',function () {
        var id = $(this).attr('data-edit');
        var nama = $(this).attr('data-nama');
        $(".inama").html(nama);

        $("input[name='id_karyawan']").val(id);
        $('#modalCompare').modal('show');
    })

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate(".form-compare");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-compare")[0]);

                $.ajax({
                    url: baseURL + 'settings/menuakses/save/compare',
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
                kiranaAlert('OK', "Silahkan tunggu proses selesai.", 'warning', 'no');
            }
        }
        e.preventDefault();
        return false;
    });
});