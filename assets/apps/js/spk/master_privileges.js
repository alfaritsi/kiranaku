$(document).ready(function () {
    $('select', 'form[name="filter-privileges"]').on('change', function () {
        $('form[name="filter-privileges"]').submit();
    });

    $(document).on('click', '.assign', function (e) {
        var id = $(this).attr('data-assign');
        var id_divisi = $('#id_divisi').val();
        var modal = $('#modal-assign');
        $.ajax({
            url: baseURL + 'spk/master/get/privileges',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                id_divisi: id_divisi
            },
            success: function (data) {
                $('#id_user', modal).val(data.data.id_user);
                $('#leg_level_id', modal).val(data.data.leg_level_id);
                $('#leg_level_id', modal).trigger('change');
                modal.modal('show');
            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', 'button[name="simpan"]', function (e) {
        e.preventDefault();
        var form = $('#form-privileges');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/master/save/privileges',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('.modal-kelengkapan:visible').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
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