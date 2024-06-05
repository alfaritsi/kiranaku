$(document).ready(function(){
    $('.colorpicker').colorpicker({
        useAlpha: false
    });

    $('#label_name').on('change',function(){
        $('#preview-label').html($('#label_name').val());
        $('#preview-label').css('background-color',$('#label_background_color').val());
        $('#preview-label').css('color',$('#label_text_color').val());
    });

    $('.colorpicker').on('change', function(){
        $('#preview-label').html($('#label_name').val());
        $('#preview-label').css('background-color',$('#label_background_color').val());
        $('#preview-label').css('color',$('#label_text_color').val());
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate(".form-notification-app");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-notification-app")[0]);

                $.ajax({
                    url: baseURL + 'notifications/save/app',
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
        $.ajax({
            url: baseURL + 'notifications/get/app',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            }, success: function (data) {
                $(".title-form").html("Edit Notification App");
                $.each(data,function(i,v){
                    $('#notification_app_id').val(v.notification_app_id);
                    $('#app_name').val(v.app_name);
                    $('#app_icon').val(v.app_icon);
                    $('#alias_code').val(v.alias_code);
                    $('#url').val(v.url);
                    $('#label_background_color').val(v.label_background_color);
                    $('#label_text_color').val(v.label_text_color);
                    $('#label_name').val(v.label_name).trigger('change');;
                    $('#priority').val(v.priority);

                });
            }
        });
    });

    $(".delete").on("click", function (e) {
        var id = $(this).data("delete");
        $.ajax({
            url: baseURL + 'notifications/delete/app',
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



    $(".set_active").on("click", function(e){
        var id	= $(this).data("id");
        var action	= $(this).data("action");
        $.ajax({
            url: baseURL+'notifications/set/app/'+action,
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
});