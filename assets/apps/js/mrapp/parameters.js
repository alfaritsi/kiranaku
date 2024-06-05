// form-mrapp-report-parameters

$(function () {
    $('input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%' // optional
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate(".form-mrapp-report-parameters");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-mrapp-report-parameters")[0]);

                $.ajax({
                    url: baseURL + 'mrapp/reports/save/parameter',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if(data.sts == 'OK'){
                            swal('Success',data.msg,'success').then(function(){
                                location.reload();
                            });
                        }else{
                            $("input[name='isproses']").val(0);
                            swal('Error',data.msg,'error');
                        }
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });

    $(".edit").on("click", function (e) {
        var id = $(this).data("edit");
        var id_report = $('#id_report').val();
        $.ajax({
            url: baseURL + 'mrapp/reports/get/parameter',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_report: id_report,
                id: id
            }, success: function (data) {
                // console.log(data);
                $(".title-form").html("Edit Report Parameter");
                $.each(data,function(i,v){
                    $('#id_report_parameter').val(v.id_report_parameter);
                    $('#parameter_nama').val(v.parameter_nama);
                    $('#parameter_alias').val(v.parameter_alias);
                    $('#parameter_kolom').val(v.parameter_kolom);
                    $('#parameter_default').val(v.parameter_default);
                    $('#deskripsi').val(v.deskripsi);
                    $('#urutan').val(v.urutan);
                });
            }
        });
    });

    $(".set_active").on("click", function(e){
        var id	= $(this).data("id");
        var action	= $(this).data("action");
        $.ajax({
            url: baseURL+'mrapp/reports/set/parameter/'+action,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id : id
            },
            success: function(data){
                if(data.sts == 'OK'){
                    swal('Success',data.msg,'success').then(function(){
                        location.reload();
                    });
                }else{
                    swal('Error',data.msg,'error');
                }
            }
        });
    });

    $(".delete").on("click", function (e) {
        var id = $(this).data("delete");
        $.ajax({
            url: baseURL + 'mrapp/reports/delete/parameter',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if(data.sts == 'OK'){
                    swal('Success',data.msg,'success').then(function(){
                        location.reload();
                    });
                }else{
                    swal('Error',data.msg,'error');
                }
            }
        });
    });

});