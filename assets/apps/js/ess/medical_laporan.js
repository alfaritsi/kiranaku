$(document).ready(function(){
    $('#filter-date input, #id_fbk_status, #jenis').on('change',function(){
        $('form').submit();
    });
});