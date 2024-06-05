$(document).ready(function(){

    $('input[name="awal"],input[name="akhir"]').on('change',function(){
        $(this).closest('form')[0].submit();
    });

    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate(".form-send-komentar");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-send-komentar")[0]);

                $.ajax({
                    url: baseURL + 'infokirana/save/komentar',
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
});