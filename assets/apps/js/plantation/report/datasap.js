$(document).ready(function() {
    get_data();

    $(document).on("change", "select[name='pabrik_filter[]']", function(e) {
        get_data();
    });
});

function get_data() {
    $("#sspTable").DataTable().clear().destroy();

    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
        return {
            iStart: oSettings._iDisplayStart,
            iEnd: oSettings.fnDisplayEnd(),
            iLength: oSettings._iDisplayLength,
            iTotal: oSettings.fnRecordsTotal(),
            iFilteredTotal: oSettings.fnRecordsDisplay(),
            iPage: Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            iTotalPages: Math.ceil(
                oSettings.fnRecordsDisplay() / oSettings._iDisplayLength
            )
        };
    };

    let table = $("#sspTable").DataTable({
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
        ],
        ordering: $("#sspTable").data("ordering") ? $("#sspTable").data("ordering") : false,
        scrollY: $("#sspTable").data("scrolly") ? $("#sspTable").data("scrolly") : false,
        scrollX: $("#sspTable").data("scrollx") ? $("#sspTable").data("scrollx") : false,
        bautoWidth: $("#sspTable").data("bautowidth") ? $("#sspTable").data("bautowidth") : false,
        pageLength: $("#sspTable").data("pagelength") ? $("#sspTable").data("pagelength") : 10,
        paging: $("#sspTable").data("paging") ? $("#sspTable").data("paging") : true,
        fixedHeader: $("#sspTable").data("fixedheader") ? $("#sspTable").data("fixedheader") : false,
        order: [
            [0, 'desc'], [1, 'desc']
        ],
        initComplete: function() {
            var api = this.api();
            $("#sspTable_filter input").attr("placeholder", "Press enter to start searching");
            $("#sspTable_filter input").attr("title", "Press enter to start searching");
            $("#sspTable_filter input").off(".DT").on("keypress change", function(evt) {
                if (evt.type == "change") {
                    api.search(this.value).draw();
                }
            });
        },
        oLanguage: {
            sProcessing: "Please wait ..."
        },
        processing: true,
        serverSide: true,
        searching: true,
        columnDefs: [{ "targets": 0, "type": "date-eu" }],
        ajax: {
            url: baseURL + "plantation/report/get/datasap",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                pabrik: $("select[name='pabrik_filter[]']").val(),
                tahun: $("select[name='tahun']").val(),
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
                KIRANAKU.alert({
                    text: "Server Error",
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
            complete: function() {}
        },
        columns: [
            {
                data: "tanggal",
                name: "tanggal",
                render: function(data, type, row) {
                    return row.tanggal_format;
                },
                visible: true,
                orderable: true
            },
            {
                data: "jenis",
                name: "jenis",
                visible: true,
                orderable: true
            },
            {
                data: "plant",
                name: "plant",
                visible: true,
                orderable: true
            },
            {
                data: "no_transaksi",
                name: "no_transaksi",
                visible: true,
                orderable: true
            },
            {
                data: "status_sap",
                name: "status_sap",
                render: function(data, type, row) {
                    let output = "-";
                    if(row.done_kirim_sap) {
                        if (row.status_sap == 'success') {
                            output = '<span class="label label-success">Success</span><br>' + row.keterangan_sap;
                        } else if (row.status_sap == 'fail') {
                            output = '<span class="label label-danger">Fail:</span><br>' + row.keterangan_sap;
                        }
                    }

                    return output;
                },
                visible: true,
                orderable: false,
                searchable: false,
            },
            {
                data: "id_reference_sap",
                name: "id_reference_sap",
                visible: true,
                orderable: true,
                searchable: true,
            },
            {
                data: "nomor_sap",
                name: "nomor_sap",
                visible: true,
                orderable: false
            }
        ],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $("td:eq(0)", row).html();
        }
    });

    new $.fn.dataTable.Buttons( table, {
        buttons: [
            { 
                extend: 'excel',
                title: '',
                text: 'Export Excel',
                className: 'btn btn-default btn-sm'
            }
        ]
    } );

    table.buttons().container().appendTo("#action-button-datatable");
}