$(document).ready(function () {
    get_data();

    $(document).on("change", "select[name='pabrik_filter[]'], select[name='jenis_po']", function (e) {
        get_data();
    });

    $(document).on("changeDate", "input[name='tanggal_awal'], input[name='tanggal_akhir']", function (e) {
        if (e.target.name === 'tanggal_awal') {
            $("input[name='tanggal_akhir']").datepicker('setStartDate', $(this).val());
        }
        if (e.target.name === 'tanggal_akhir') {
            $("input[name='tanggal_awal']").datepicker('setEndDate', $(this).val());
        }
        get_data();
    });
});

function get_data() {
    $("#sspTable").DataTable().clear().destroy();

    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
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
        initComplete: function () {
            var api = this.api();
            $("#sspTable_filter input").attr("placeholder", "Press enter to start searching");
            $("#sspTable_filter input").attr("title", "Press enter to start searching");
            $("#sspTable_filter input").off(".DT").on("keypress change", function (evt) {
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
        ajax: {
            url: baseURL + "plantation/report/get/po",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                pabrik: $("select[name='pabrik_filter[]']").val(),
                tanggal_awal: $("input[name='tanggal_awal']").val(),
                tanggal_akhir: $("input[name='tanggal_akhir']").val(),
                tipe_po: $("select[name='jenis_po']").val(),
            },
            error: function (a, b, c) {
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
            complete: function () { }
        },
        columns: [
            {
                data: "plant",
                name: "plant",
                width: "3%",
                visible: true,
                orderable: true
            },
            {
                data: "no_ppb",
                name: "no_ppb",
                width: "5%",
                visible: true,
                orderable: false
            },
            {
                data: "tanggal_upload",
                name: "tanggal_upload",
                width: "7%",
                render: function (data, type, row) {
                    return row.tanggal_upload_format;
                },
                visible: true,
                orderable: true
            },
            {
                data: "tipe_po",
                name: "tipe_po",
                width: "3%",
                visible: true,
                orderable: false
            },
            {
                data: "tanggal_konfirmasi",
                name: "tanggal_konfirmasi",
                width: "7%",
                render: function (data, type, row) {
                    return row.tanggal_konfirmasi_format;
                },
                visible: true,
                orderable: true
            },
            {
                data: "kode_barang",
                name: "kode_barang",
                width: "7%",
                visible: true,
                orderable: false
            },
            {
                data: "nama_barang",
                name: "nama_barang",
                width: "13%",
                visible: true,
                orderable: false
            },
            {
                data: "no_po",
                name: "no_po",
                width: "5%",
                visible: true,
                orderable: true
            },
            {
                data: "tanggal_kirim_sap",
                name: "tanggal_kirim_sap",
                width: "7%",
                render: function (data, type, row) {
                    return row.tanggal_kirim_sap_format;
                },
                visible: true,
                orderable: true
            },
            {
                data: "tanggal_gr_format",
                name: "tanggal_gr_format",
                width: "7%",
                render: function (data, type, row) {
                    let output = "";
                    if (row.tanggal_gr_format) {
                        $.each(row.tanggal_gr_format.split(";"), function (i, v) {
                            if (v) {
                                output += v + ';\r\n';
                            }
                        });
                    }
                    return output;
                },
                visible: true
            }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $("td:eq(0)", row).html();
        }
    });

    new $.fn.dataTable.Buttons(table, {
        buttons: [
            {
                extend: 'excel',
                title: '',
                text: 'Export Excel',
                className: 'btn btn-default btn-sm'
            }
        ]
    });

    table.buttons().container().appendTo("#action-button-datatable");
}