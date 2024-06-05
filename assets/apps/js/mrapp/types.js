$(function () {
    $('input[type="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%' // optional
    });

    $('.datepicker').datepicker({
        initialDate: new Date(),
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayBtn: true,
        todayHighlight: true
    });
    $('.datepicker').datepicker('update', new Date());

    $('#scheduled').on('ifChanged',function(){
        if($(this).is(':checked'))
        {
            $('#div_schedule').show();
        }
        else
        {
            $('#div_schedule').hide();
        }
    });

    $('#scheduled').trigger('ifChanged');

    $('#schedule_period').on('change',function(){
        if($(this).val() == "xmonthly")
        {
            $('#div_schedule_periode_counter').show();
            $('#schedule_periode_counter input').attr('disabled',false);
        }else{
            $('#div_schedule_periode_counter').hide();
            $('#schedule_periode_counter input').val(1);
            $('#schedule_periode_counter input').attr('disabled',true);
        }
    });
    $('#schedule_period').trigger('change');

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate(".form-mrapp-reports");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-mrapp-reports")[0]);

                $.ajax({
                    url: baseURL + 'mrapp/reports/save/type',
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
        var id_report_type = $(this).data("edit");
        $.ajax({
            url: baseURL + 'mrapp/reports/get/type',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_report_type: id_report_type
            }, success: function (data) {
                // console.log(data);
                $(".title-form").html("Edit Report Tipe");
                $.each(data,function(i,v){
                    $('#id_report_type').val(v.id_report_type);
                    $('#kode_type').val(v.kode_type);
                    $('#nama_type').val(v.nama_type);
                    $('#deskripsi').val(v.deskripsi);
                });
            }
        });
    });

    $(".set_active").on("click", function(e){
        var id	= $(this).data("id");
        var action	= $(this).data("action");
        $.ajax({
            url: baseURL+'mrapp/reports/set/type/'+action,
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
            url: baseURL + 'mrapp/reports/delete/type',
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