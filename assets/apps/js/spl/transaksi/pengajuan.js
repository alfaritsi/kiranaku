$(document).ready(function () {
    get_data_pengajuan();

    $(document).on("change", "#filter_plant, #filter_tahun, #filter_departemen, #filter_status", function () {
        get_data_pengajuan();
    });

    $(document).on('click', '.spl-history', function (e) {
        let noSpl = $(this).attr('data-no_spl');
        $.ajax({
            url: baseURL + 'spl/transaksi/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                no_spl: noSpl,
            },
            success: function (data) {
                $('#KiranaModals .modal-dialog').addClass("modal-lg");
                $('#KiranaModals .modal-title').html("SPL History");
                let output = "";
                output += '<div class="row">';
                output += '		<div class="col-sm-12">';
                output += '			<table class="table table-bordered table-striped">';
                output += '				<thead>';
                output += '					<th>No. SPL</th>';
                output += '					<th>Tanggal Status</th>';
                output += '					<th>Status</th>';
                output += '					<th>Comment</th>';
                output += '				</thead>';
                output += '				<tbody>';
                output += '				</tbody>';
                output += '			</table>';
                output += '		</div>';
                output += '</div>';
                $('#KiranaModals .modal-body').html(output);

                $.fn.dataTable.moment('DD.MM.YYYY HH:mm:ss');
                let t = $('#KiranaModals table').DataTable({
                    order: [
                        [1, 'desc']
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
                        { "orderable": false, "className": "wrap-text", "targets": [3] }
                    ]
                });
                if (data && Array.isArray(data)) {
                    $.each(data, function (i, v) {
                        let action = "";
                        switch (v.action) {
                            case 'reject':
                                action = 'ditolak';
                                break;
                            default:
                                action = v.action;
                                break;
                        }

                        t.row.add([
                            v.no_spl,
                            v.tgl_status_format + " " + v.jam_status_format,
                            "<span style='text-transform: capitalize'>" + action + "</span> oleh <br> <span class='label label-info'>" + v.nama_role + " : " + v.nama + "</label>",
                            v.comment
                        ]).draw(false);
                    });

                    // } else {
                    //     kiranaAlert("notOK", 'Server error.', 'error', 'no');
                }
                setTimeout(function () {
                    adjustDatatableWidth();
                }, 1500);

                // modal.modal('show');
                $('#KiranaModals').modal({
                    backdrop: 'static',
                    keyboard: true,
                    show: true
                });
            },
            error: function (data) {
                $("input[name='isproses']").val(0);
                kiranaAlert("notOK", 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });
});

function get_data_pengajuan() {
    $.ajax({
        url: baseURL + 'spl/transaksi/get/spl',
        type: 'POST',
        dataType: 'JSON',
        data: {
            return: "datatables",
            data: "header",
            IN_plant: $("select[name='filter_plant']").val(),
            tahun: $("select[name='filter_tahun']").val(),
            IN_departemen: $("select[name='filter_departemen']").val(),
            status: $("select[name='status']").val(),
            page: $("select[name='status']").length > 0 ? "list" : "approval"
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
        success: function (data) {
            $(".my-datatable-extends-order").DataTable().clear().destroy();
            let t = $('.my-datatable-extends-order').DataTable({
                order: [[2, 'asc']],
                columnDefs: [
                    { "targets": 2, "visible": false, "searchable": false },
                    { "targets": [3,4], "type": "date-eu" },
                    { "targets": -2, "orderable" : false },
                    { "targets": [-1], "orderable" : false, "searchable": false }
                ],
            });
            t.clear().draw();
            $.each(data, function (i, v) {
                //status
                let status = "";
                switch (v.status_spl) {
                    case 'finish':
                        status = '<div class="label label-success">FINISH</div>';
                        if (v.realisasi == 0)
                            status += '<br><small>Menunggu Realisasi</small>';
                        else
                            status += '<br><small>Menunggu Konfirmasi Realisasi</small>';
                        break;
                    case 'completed':
                        status = '<div class="label label-primary">COMPLETED</div>';
                        break;
                    case 'drop':
                        status = '<div class="label label-danger">DROP</div>';
                        break;
                    case 'rejected':
                        status = '<div class="label label-danger">REJECTED</div>';
                        status += '<br><small>Ditolak oleh ' + v.status_spl_reject + '</small>';
                        break;
                    default:
                        status = '<div class="label label-warning">ON PROGRESS</div>';
                        status += '<br><small>Sedang diproses oleh ' + v.status_spl_desc + '</small>';
                        break;
                }

                const link_realisasi = baseURL + "spl/transaksi/realisasi/" + v.no_spl.replace(/\//g, "-");
                const link_detail = baseURL + "spl/transaksi/detail/" + v.no_spl.replace(/\//g, "-");
                const target_link = "_blank";

                action = "<div class='btn-group'>";
                action += " <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>";
                action += " <ul class='dropdown-menu pull-right'>";
                action += "     <li><a href='" + link_detail + "' target='" + target_link + "' class=''><i class='fa fa-search'></i> Detail</a></li>";
                action += "     <li><a href='javascript:void(0)' class='spl-history' data-no_spl='" + v.no_spl + "'><i class='fa fa-list'></i> History</a></li>";

                if (v.status == 'finish' && v.access == 1 && v.realisasi == 0)
                    action += "     <li><a href='" + link_realisasi + "' target='" + target_link + "' class=''><i class='fa fa-edit'></i> Buat Realisasi</a></li>";

                action += " </ul>";
                action += "</div>";

                t.row.add([
                    v.no_spl,
                    v.plant,
                    v.tanggal_buat,
                    v.tanggal_pengajuan_format,
                    v.tanggal_spl_format,
                    v.departemen,
                    v.seksi,
                    status,
                    action
                ]).draw(false);
            });

        }
    });
}

function get_data_pengajuan_old() {
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
            [2, 'asc']
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
        columnDefs: [{ "targets": 2, "type": "date-eu" }],
        ajax: {
            url: baseURL + "spl/transaksi/get/spl",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                IN_plant: $("select[name='filter_plant']").val(),
                tahun: $("select[name='filter_tahun']").val(),
                IN_departemen: $("select[name='filter_departemen']").val(),
                status: $("select[name='status']").val(),
                page: $("select[name='status']").length > 0 ? "list" : "approve"
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
        columns: [{
            data: "no_spl",
            name: "no_spl",
            width: "20%",
            render: function (data, type, row) {
                let output = "";
                output += row.no_spl;
                return output;
            },
            visible: true,
            orderable: false
        },
        {
            data: "plant",
            name: "plant",
            width: "10%",
            render: function (data, type, row) {
                return row.plant;
            },
            visible: true,
            orderable: false
        },
        {
            data: "tanggal_buat",
            name: "tanggal_buat",
            width: "10%",
            render: function (data, type, row) {
                return row.tanggal_buat_format;
            },
            visible: true
        },
        {
            data: "tanggal_spl",
            name: "tanggal_spl",
            width: "10%",
            render: function (data, type, row) {
                return row.tanggal_spl_format;
            },
            visible: true
        },
        {
            data: "departemen",
            name: "departemen",
            render: function (data, type, row) {
                return row.departemen;
            },
            visible: true
        },
        {
            data: "seksi",
            name: "seksi",
            render: function (data, type, row) {
                return row.seksi;
            },
            visible: true
        },
        {
            data: "status",
            name: "status",
            width: "20%",
            render: function (data, type, row) {
                let status = "";
                switch (row.status) {
                    case 'finish':
                        status = '<div class="label label-success">FINISH</div>';
                        if (row.realisasi == 0)
                            status += '<br><small>Menunggu Realisasi</small>';
                        else
                            status += '<br><small>Menunggu Konfirmasi Realisasi</small>';
                        break;
                    case 'completed':
                        status = '<div class="label label-primary">COMPLETED</div>';
                        break;
                    case 'drop':
                        status = '<div class="label label-danger">DROP</div>';
                        break;
                    case 'rejected':
                        status = '<div class="label label-danger">REJECTED</div>';
                        status += '<br><small>Ditolak oleh ' + row.status_spl_reject + '</small>';
                        break;
                    default:
                        status = '<div class="label label-warning">ON PROGRESS</div>';
                        status += '<br><small>Sedang diproses oleh ' + row.status_spl + '</small>';
                        break;
                }

                return status;
            },
            visible: true,
            orderable: false
        },
        {
            data: "deskripsi",
            name: "deskripsi",
            width: "5%",
            render: function (data, type, row) {
                const link_realisasi = baseURL + "spl/transaksi/realisasi/" + row.no_spl.replace(/\//g, "-");
                const link_detail = baseURL + "spl/transaksi/detail/" + row.no_spl.replace(/\//g, "-");
                const target_link = "_blank";

                output = "<div class='btn-group'>";
                output += " <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>";
                output += " <ul class='dropdown-menu pull-right'>";
                output += "     <li><a href='" + link_detail + "' target='" + target_link + "' class=''><i class='fa fa-search'></i> Detail</a></li>";
                output += "     <li><a href='javascript:void(0)' class='spl-history' data-no_spl='" + row.no_spl + "'><i class='fa fa-list'></i> History</a></li>";

                if (row.status == 'finish' && row.access_realisasi == 1 && row.realisasi == 0)
                    output += "     <li><a href='" + link_realisasi + "' target='" + target_link + "' class=''><i class='fa fa-edit'></i> Buat Realisasi</a></li>";

                output += " </ul>";
                output += "</div>";
                return output;
            },
            visible: true,
            orderable: false
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