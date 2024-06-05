$(document).ready(function() {
    get_data_io();

    $(document).on("change", "select[name='pabrik_filter[]']", function(e) {
        get_data_io();
    });
});

function get_data_io() {
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

    $("#sspTable").dataTable({
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
        // columnDefs: [{ "targets": 2, "type": "date-eu" }],
        ajax: {
            url: baseURL + "plantation/master/get/io",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                IN_plant: $("select[name='pabrik_filter[]']").val(),
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
                data: "GSBER",
                name: "GSBER",
                width: "10%",
                visible: true,
                orderable: true
            },
            {
                data: "AUFNR",
                name: "AUFNR",
                width: "20%",
                visible: true,
                orderable: true
            },
            {
                data: "KTEXT",
                name: "KTEXT",
                visible: true,
                orderable: true
            },
            {
                data: "PHAS3",
                name: "PHAS3",
                render: function(data, type, row) {
                    let output = '<span class="label label-success">Open</span>';
                    if (row.PHAS3 == 'X') output = '<span class="label label-danger">Closed</span>'
                    
                    return output;
                },
                visible: true,
                orderable: true,
                searchable: false,
            }
        ],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $("td:eq(0)", row).html();
        }
    });
}