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
                    url: baseURL + 'mrapp/reports/save/report',
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
        var id_report = $(this).data("edit");
        $.ajax({
            url: baseURL + 'mrapp/reports/get/report',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_report: id_report
            }, success: function (data) {
                // console.log(data);
                $(".title-form").html("Edit Report");
                $.each(data,function(i,v){
                    $('#id_report').val(v.id_report);
                    $('#kode_report').val(v.kode_report);
                    $('#nama_report').val(v.nama_report);
                    $('#report_function').val(v.report_function);
                    $('#report_function').trigger('change');
                    if(v.scheduled)
                        $('#scheduled').iCheck('check');
                    else
                        $('#scheduled').iCheck('uncheck');

                    $('#schedule_period').val(v.schedule_period);
                    $('#schedule_period').trigger('change');
                    $('#schedule_period_counter').val(v.schedule_period_counter);
                    $('#schedule_start').datepicker(
                        'update',
                        v.schedule_start
                    );

                });
            }
        });
    });

    $(".delete").on("click", function (e) {
        var id = $(this).data("delete");
        $.ajax({
            url: baseURL + 'mrapp/reports/delete/report',
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
            url: baseURL+'mrapp/reports/set/report/'+action,
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

    $(".parameter").on("click", function (e) {
        var id = $(this).data("id");
        $(this).siblings('form.param-form').submit();
    });

    $(".link").on("click", function (e) {
        var id = $(this).data("id");
        $(this).siblings('form.link-form').submit();
    });

    $(".subscriber").on("click", function (e) {
        var id = $(this).data("id");
        $(this).siblings('form.subscriber-form').submit();
    });

    $(".threshold").on("click", function (e) {
        var id = $(this).data("id");
        $(this).siblings('form.threshold-form').submit();
    });
});