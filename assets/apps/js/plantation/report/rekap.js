$(document).ready(function () {
    $("#box-data").hide();
    // get_data();

    $('.input-daterange').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        autoclose: true
    });

    // $(document).on("change", "select[name='pabrik_filter[]']", function(e) {
    //     get_data();
    // });

    // $(document).on("changeDate", "[name='tanggal_awal'], input[name='tanggal_akhir']", function(e) {
    //     get_data();
    // });

    $("#btn-submit").on("click", function () {
        const empty_form = validate('#form-report');
        if (empty_form == 0) {
            get_data();
            $("#box-form").hide("slow");
            $("#box-data").show("slow");
        }
    });

    $(".back_btn").on("click", function () {
        $("#box-data").hide("slow");
        $("#box-form").show("slow");
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
            [1, 'asc']
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
        // columnDefs: [{ "targets": 2, "type": "date-eu" }],
        ajax: {
            url: baseURL + "plantation/report/get/rekap",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                IN_plant: $("select[name='pabrik_filter[]']").val(),
                is_active: 1,
                tanggal_awal: $("input[name='tanggal_awal']").val(),
                tanggal_akhir: $("input[name='tanggal_akhir']").val(),
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
                data: "WERKS",
                name: "WERKS",
                width: "8%",
                visible: true,
                orderable: true
            },
            {
                data: "MATNR",
                name: "MATNR",
                width: "14%",
                render: function (data, type, row) {
                    let output = "";
                    output += row.MATNR;
                    return output;
                },
                visible: true,
                orderable: true
            },
            {
                data: "MAKTX",
                name: "MAKTX",
                render: function (data, type, row) {
                    return row.MAKTX;
                },
                visible: true,
                orderable: true
            },
            {
                data: "GROES",
                name: "GROES",
                render: function (data, type, row) {
                    return row.GROES;
                },
                visible: true,
                orderable: true
            },
            {
                data: "jumlah_gr",
                name: "jumlah_gr",
                width: "10%",
                render: function (data, type, row) {
                    return numberWithCommas(parseFloat(row.jumlah_gr));
                },
                visible: true,
                searchable: false,
                orderable: false,
                className: 'text-center'
            },
            {
                data: "jumlah_gi",
                name: "jumlah_gi",
                width: "10%",
                render: function (data, type, row) {
                    return numberWithCommas(parseFloat(row.jumlah_gi));
                },
                visible: true,
                searchable: false,
                orderable: false,
                className: 'text-center'
            },
            {
                data: "LABST",
                name: "LABST",
                width: "13%",
                render: function (data, type, row) {
                    return numberWithCommas(parseFloat(row.LABST));
                },
                visible: true,
                searchable: false,
                orderable: false,
                className: 'text-center'
            },
            {
                data: "MEINS",
                name: "MEINS",
                width: "10%",
                visible: true,
                searchable: false,
                orderable: false,
                className: 'text-center'
            },
            // {
            //     data: "LGORT",
            //     name: "LGORT",
            //     width: "10%",
            //     visible: true,
            //     orderable: false,
            // }
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

    $("#box-data-title").html("Transaksi Periode " + $("input[name='tanggal_awal']").val() + " - " + $("input[name='tanggal_akhir']").val())
}