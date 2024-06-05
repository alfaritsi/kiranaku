$(document).ready(function () {

    $('#tahun').datepicker({
        format: 'yyyy',
        autoclose: true,
        weekStart: 1,
        minViewMode: 2,
        // startDate: moment().startOf('year').toDate(),
    });

    $('#tanggal_cutoff').datepicker({
        format: 'dd.mm.yyyy',
        autoclose: true,
        weekStart: 1,
        // startDate: moment().toDate(),
        // endDate: moment().endOf('year').toDate()
    });

    $('#tahun').on('changeDate',function(e){
        // if(moment(e.date).format('YYYY') == moment().format('YYYY'))
        //     $('#tanggal_cutoff').datepicker('setStartDate',moment().toDate());
        // else
        // {
        //     $('#tanggal_cutoff').datepicker('setStartDate',moment(e.date,'YYYY').startOf('year').toDate());
        // }
        // $('#tanggal_cutoff').datepicker('setEndDate',moment(e.date,'YYYY').endOf('year').toDate());

        // $('#tanggal_cutoff').datepicker('setDate',moment(e.date,'YYYY').endOf('year').toDate());
    });

    $('#jam_cutoff').datetimepicker({
        format: 'HH:mm'
    });
    validator = $('.form-medical-cutoff').validate({
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
        var valid = $('.form-medical-cutoff').valid();

        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-medical-cutoff")[0]);

                $.ajax({
                    url: baseURL + 'ess/medical/save/cutoff',
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
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', '.edit', function (e) {
        var id = $(this).attr('data-edit');
        $.ajax({
            url: baseURL + 'ess/medical/get/cutoff',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validator.resetForm();
                if (data.data) {
                    let dataEdit = data.data;
                    $('input[name="id_fbk_cutoff"]').val(id);
                    $('#tahun').datepicker('setDate',moment(dataEdit.tahun,'YYYY').toDate());
                    $('#tanggal_cutoff').datepicker('setDate',moment(dataEdit.jadwal).toDate());
                    $('#jam_cutoff').val(moment(dataEdit.jadwal).format('HH:mm'));
                    $('#catatan').html(dataEdit.catatan);

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
                        url: baseURL + 'ess/medical/delete/cutoff',
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