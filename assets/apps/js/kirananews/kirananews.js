$(document).ready(function() {

    $('input[name="awal"],input[name="akhir"]').on('change',function(){
        $(this).closest('form')[0].submit();
    });

    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true
    });
});