$(document).ready(function () {
    get_data_cek();

    $('.input-daterange').datepicker({
    	format: 'dd.mm.yyyy',
        todayHighlight: true,
        changeMonth: true,
        changeYear: true,
        autoclose: true
    });

    $(document).on("change", "#filter_plant", function () {
        get_data_cek();
    });

    $(document).on("changeDate", "[name='filter_tanggal_awal'], [name='filter_tanggal_akhir']", function () {
        get_data_cek();
    });
});

function get_data_cek() {
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
            [1, 'desc']
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
        columnDefs: [{ "targets": 1, "type": "date-eu" }],
        ajax: {
            url: baseURL + "outspec/transaksi/get/data",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                IN_plant: $("select[name='filter_plant[]']").val(),
                tanggal_awal: $("input[name='filter_tanggal_awal']").val(),
                tanggal_akhir: $("input[name='filter_tanggal_akhir']").val(),
                tipe: 'random'
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },
            complete: function () { }
        },
        columns: [{
            data: "plant",
            name: "plant",
            width: "10%",
            visible: true,
            orderable: true
        },
        {
            data: "tanggal",
            name: "tanggal",
            width: "15%",
            render: function (data, type, row) {
                return row.tanggal_format;
            },
            visible: true
        },
        {
            data: "no_si",
            name: "no_si",
            visible: true,
            orderable: true
        },
        {
            data: "tahun_produksi",
            name: "tahun_produksi",
            visible: true,
            orderable: true
        },
        {
            data: "no_produksi",
            name: "no_produksi",
            visible: true,
            orderable: true
        },
        {
            data: "deskripsi",
            name: "deskripsi",
            width: "5%",
            render: function (data, type, row) {
                const link_detail = baseURL + "outspec/transaksi/detail/random/" + row.id;

                output = "			<div class='btn-group'>";
                output += "				<button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>";
                output += "				<ul class='dropdown-menu pull-right'>";
                output += "                 <li><a href='" + link_detail + "' target='_blank' class='cek-detail' ><i class='fa fa-search'></i> Detail</a></li>";
                output += "				</ul>";
                output += "	        </div>";
                return output;
            },
            visible: true,
            orderable: false,
            className: 'text-center'
        }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $("td:eq(0)", row).html();
        }
    });
}