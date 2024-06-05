$(document).ready(function(){
    $('#filter-date input, select', 'form[name="filter-fbk-sap"]').on('change', function () {
        $('form[name="filter-fbk-sap"]').submit();
    });

    $(document).on('click','.btn-sap-medical-nik',function(e){
        (async function getText() {
            let defText = "";
            if(typeof defText === "undefined")
                defText = "";
            const {value: text} = await swal({
                input: 'number',
                inputPlaceholder: 'Ketik NIK',
                inputValue: defText,
                showCancelButton: true
            });

            if (text) {
                $('#nik').val(text);
                $('.btn-sap-medical').trigger('click');
            }

        })();
    });

    $(document).on('click','.btn-sap-medical',function(e){
        e.preventDefault();
        var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
        $("body .overlay-wrapper").append(overlay);
        var formData = new FormData($('form[name="filter-medical-sap"]')[0]);

        $.ajax({
            url: baseURL + 'ess/scheduler/medical',
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                $("body .overlay-wrapper").find('.overlay').remove();
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg);
                } else {
                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                }
            },
            error: function (data) {
                $("body .overlay-wrapper").find('.overlay').remove();
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });
});