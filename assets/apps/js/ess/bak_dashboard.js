$(document).ready(function () {

    $('#filter-date input').on('change', function () {
        $(this).parents('form').submit();
    });
});