// form-mrapp-report-links

$(function () {
    $('input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%' // optional
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate(".form-mrapp-report-links");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-mrapp-report-links")[0]);

                $.ajax({
                    url: baseURL + 'mrapp/reports/save/link',
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
            url: baseURL + 'mrapp/reports/get/link',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_report: id_report,
                id: id
            }, success: function (data) {
                // console.log(data);
                $(".title-form").html("Edit Report Link");
                $.each(data,function(i,v){
                    $('#id_report_link').val(v.id_report_link);
                    $('#id_report_link').trigger('change');
                    $('#id_report_link_old').val(v.id_report_link);
                });
            }
        });
    });

    $(".set_active").on("click", function(e){
        var id	= $(this).data("id");
        var id_report = $('#id_report').val();
        var action	= $(this).data("action");
        $.ajax({
            url: baseURL+'mrapp/reports/set/link/'+action,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id : id,
                id_report: id_report
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
        var id_report = $('#id_report').val();
        $.ajax({
            url: baseURL + 'mrapp/reports/delete/link',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                id_report: id_report
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