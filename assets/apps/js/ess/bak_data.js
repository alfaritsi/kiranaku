$(document).ready(function () {
    $(document).on('click', '#table-data-bak > tbody > tr', function () {
        window.location = baseURL + 'ess/bak/karyawan/'+$(this).attr('data-id');
    });

    $('#filter-date input').on('change', function () {
        $(this).parents('form').submit();
    });
});