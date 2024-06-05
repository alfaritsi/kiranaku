$(document).ready(function(){
    $('#filter-date input, select').on('change',function(){
        $('form[name="filter-laporan-cuti"]').submit();
    });
});