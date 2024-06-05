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
                var formData = new FormData($(".form-mrapp-report-thresholds")[0]);

                $.ajax({
                    url: baseURL + 'mrapp/reports/save/threshold',
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
            url: baseURL + 'mrapp/reports/get/threshold',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_report: id_report,
                id: id
            }, success: function (data) {
                // console.log(data);
                $(".title-form").html("Edit Report Parameter");
                $.each(data,function(i,v){
                    $('#id_report_threshold').val(v.id_report_threshold);
                    $('#nama_threshold').val(v.nama_threshold);
                    $('#threshold_kolom').val(v.threshold_kolom);
                    $('#threshold_type').val(v.threshold_type);
                    $('#threshold_type').trigger('change');
                    $('#threshold_value').val(v.threshold_value);
                    $('#satuan').val(v.satuan);
                    $('#priority').val(v.priority);
                });
            }
        });
    });

    $(".set_active").on("click", function(e){
        var id	= $(this).data("id");
        var action	= $(this).data("action");
        $.ajax({
            url: baseURL+'mrapp/reports/set/threshold/'+action,
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
            url: baseURL + 'mrapp/reports/delete/threshold',
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