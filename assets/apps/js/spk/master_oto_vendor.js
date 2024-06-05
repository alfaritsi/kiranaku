$(document).ready(function () {
    validator = $('form').validate({
        ignore: [],
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.appendTo(element.parents('.form-group > div'));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group > div").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group > div").addClass("has-success").removeClass("has-error");
        }

    });

    $(".btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var valid = $('form').valid();
        // if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($("form")[0]);
                $.ajax({
                    url: baseURL + 'spk/master/save/otovendor',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
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
        // }
        e.preventDefault();
        return false;
    });

    $(document).on('click', '.edit', function (e) {
        var id = $(this).attr('data-edit');
        $.ajax({
            url: baseURL + 'spk/master/get/otovendor',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validator.resetForm();
                if (data.data) {
					
                    let dataEdit = data.data;
					console.log(dataEdit.id_master_dokumen);
                    $('input[name="id_oto_vendor"]').val(id);
                    $('#nama_dokumen_vendor').val(dataEdit.nama_dokumen);
					$("select[name='id_master_dokumen']").val(dataEdit.id_master_dokumen).trigger("change.select2");
                    $('input[name="mandatory"][value="'+dataEdit.mandatory+'"]').prop('checked',true);

                    $(".btn-new").removeClass("hidden");

                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    })

    $(".delete").on("click", function (e) {
        var id = $(this).attr("data-delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'spk/master/delete/otovendor',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg);
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );

    });
});