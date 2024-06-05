$(document).ready(function() {
    get_data();

    $('.input-daterange').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        autoclose: true
    });

    $(document).on("change", "select[name='pabrik_filter[]']", function(e) {
        get_data();
    });

    $(document).on("changeDate", "[name='tanggal_awal'], input[name='tanggal_akhir']", function(e) {
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
            [2, 'desc'], [0, 'desc']
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
        columnDefs: [{ "targets": 2, "type": "date-eu" }],
        ajax: {
            url: baseURL + "plantation/report/get/ppb",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                pabrik: $("select[name='pabrik_filter[]']").val(),
                tanggal_awal: $("input[name='tanggal_awal']").val(),
                tanggal_akhir: $("input[name='tanggal_akhir']").val(),
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
                data: "no_ppb",
                name: "no_ppb",
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
                data: "tanggal_ppb",
                name: "tanggal_ppb",
                render: function(data, type, row) {
                    return row.tanggal_ppb_format;
                },
                visible: true,
                orderable: true
            },
            {
                data: "kode_barang",
                name: "kode_barang",
                visible: true,
                orderable: false
            },
            {
                data: "nama_barang",
                name: "nama_barang",
                visible: true,
                orderable: false
            },
            {
                data: "jumlah",
                name: "jumlah",
                visible: true,
                orderable: false
            },
            {
                data: "satuan",
                name: "satuan",
                visible: true,
                orderable: false
            },
            {
                data: "tipe_po",
                name: "tipe_po",
                visible: true,
                // orderable: false
            },
            {
                data: "no_po",
                name: "no_po",
                render: function(data, type, row) {
                    let output = "";
                    if (row.no_po) {
                        output += '<div>';
                        $.each(row.no_po.split(";"), function(i, v) {
                            if (v)
                                output += v;
                        });
                        output += '</div>';
                    }
                    return output;
                },
                visible: true,
                // orderable: false
            },
            {
                data: "no_gr",
                name: "no_gr",
                render: function(data, type, row) {
                    let output = "";
                    if (row.no_gr) {
                        output += '<div>';
                        $.each(row.no_gr.split(";"), function(i, v) {
                            if (v)
                                output += v;
                        });
                        output += '</div>';
                    }
                    return output;
                },
                visible: true,
                // orderable: false
            },
            {
                data: "no_gr_sap",
                name: "no_gr_sap",
                render: function(data, type, row) {
                    let output = "";
                    if (row.no_gr_sap) {
                        output += '<div>';
                        $.each(row.no_gr_sap.split(";"), function(i, v) {
                            if (v)
                                output += v;
                        });
                        output += '</div>';
                    }
                    return output;
                },
                visible: true,
                // orderable: false
            },
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