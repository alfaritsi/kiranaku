$(document).ready(function(){
    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate(".form-notification-category");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-notification-category")[0]);

                $.ajax({
                    url: baseURL + 'notifications/save/category',
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
            url: baseURL + 'notifications/get/category',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            }, success: function (data) {
                $(".title-form").html("Edit Notification Category");
                $.each(data,function(i,v){
                    $('#notification_category_id').val(v.notification_category_id);
                    $('#notification_category_id').trigger('change');
                    $('#app_id').val(v.app_id);
                    $('#category_name').val(v.category_name);
                    $('#alias_code').val(v.alias_code);
                    $('#notification_url').val(v.notification_url);
                    $('#notification_format').val(v.notification_format);
                    $('#priority').val(v.priority);

                });
            }
        });
    });

    $(".delete").on("click", function (e) {
        var id = $(this).data("delete");
        $.ajax({
            url: baseURL + 'notifications/delete/category',
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
            url: baseURL+'notifications/set/category/'+action,
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