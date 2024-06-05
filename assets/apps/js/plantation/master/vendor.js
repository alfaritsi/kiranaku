$(document).ready(function() {
    get_data_vendor();

    $(document).on("change", "select[name='pabrik_filter[]']", function(e) {
        get_data_vendor();
    });
});

function get_data_vendor() {
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
            [2, 'asc']
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
            url: baseURL + "plantation/master/get/vendor",
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
                data: "EKORG",
                name: "EKORG",
                width: "10%",
                visible: true,
                orderable: true
            },
            {
                data: "LIFNR",
                name: "LIFNR",
                width: "12%",
                visible: true,
                orderable: true
            },
            {
                data: "NAME1",
                name: "NAME1",
                width: "23%",
                visible: true,
                orderable: true
            },
            {
                data: "CITY1",
                name: "CITY1",
                width: "13%",
                visible: true,
                orderable: false
            },
            {
                data: "STRAS",
                name: "STRAS",
                visible: true,
                orderable: false
            },
            {
                data: "TELF1",
                name: "TELF1",
                width: "12%",
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