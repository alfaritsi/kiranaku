$(document).ready(function (e) {
    $('#filter-date input,#plant').on('change', function () {
        $(this).parents('form').submit();
    });

    //export to excel
    $(document).on('click', '#export_excel', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'spk/report/get/export?tgl_awal=' + $('#tanggal_awal_filter').val() + '&tgl_akhir=' + $('#tanggal_akhir_filter').val() + '&pabrik=' + $('#pabrik_filter').val()
        );
    });

    $('#spk-table').DataTable({
        order: [[4, 'desc']],
        columnDefs: [{
            targets: 4,
            visible: false,
            searchable: false,
            "type": "date-eu"
        }],
    });

    $(document).on('click', '.spk-history', function (e) {
        // $("#tb-history tbody").empty();
        $('#tb-history').DataTable().clear();
        $('#tb-history').DataTable().destroy();
        let id_spk = $(this).attr('data-id_spk');
        let modal = $('#modal-history');
        $.ajax({
            url: baseURL + 'spk/get/logspk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_spk: id_spk,
                return: 'json'
            },
            success: function (data) {
                $.fn.dataTable.moment('DD.MM.YYYY HH:mm:ss');
                let t = $('#tb-history').DataTable({
                    order: [
                        [0, 'desc']
                    ],
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"]
                    ],
                    scrollCollapse: true,
                    scrollY: false,
                    scrollX: true,
                    bautoWidth: false,
                    columnDefs: [
                        { "orderable": false, "targets": '_all' },
                    ]
                });
                if (data) {
                    $.each(data, function (i, v) {
                        t.row.add([
                            v.tgl_status_format + " " + v.jam_status_format,
                            '<span style="text-transform: capitalize">' + v.action + '</span> oleh <br>' + v.nama_role + ' : ' + v.nama + ((v.nama_divisi) ? " (" + v.nama_divisi + ")" : ""),
                            v.comment
                        ]).draw(false);
                    });
                }

                setTimeout(function () {
                    adjustDatatableWidth();
                }, 1500);

                modal.modal('show');
            }
        });
    });
});