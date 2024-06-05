$(document).ready(function () {
    $('#filter-date input, select', 'form[name="filter-bak-laporan"]').on('change', function () {
        $('form[name="filter-bak-laporan"]').submit();
    });

    $(document).on('click', '#btn-export-laporan-bak', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'ess/bak/excel/laporan/'+$('#lokasi').val()
            +'?id_bak_status='+$('#id_bak_status_filter').val()
            +'&tanggal_awal='+$('#tanggal_awal_filter').val()
            +'&tanggal_akhir='+$('#tanggal_akhir_filter').val()
        );

    })

    $(document).on('click', '#btn-export-laporan-bak-ktp', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'ess/bak/excel/laporan/'+$('#lokasi').val()
            +'?cico='+$('#cico').val()
            +'&tanggal_awal='+$('#tanggal_awal_filter').val()
            +'&tanggal_akhir='+$('#tanggal_akhir_filter').val()
        );

    })
});