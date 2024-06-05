$(document).ready(function() {
    $("#sspTable").dataTable();
    get_data_po();

    $('.input-daterange').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        autoclose: true
    });

    $(document).on("change", "select[name='pabrik_filter[]'], select[name='status_sap_filter[]']", function(e) {
        get_data_po();
    });

    $(document).on("changeDate", "[name='tanggal_awal'], input[name='tanggal_akhir']", function(e) {
        get_data_po();
    });

    $(document).on("click", "#btn_kirim_sap", function(e) {
        kirim_sap();
    });

    $(document).on("click", ".delete", function(e) {
        let id_po = $(this).data("id_po");

        const notes = Swal.fire({
            title: 'Alasan',
            input: 'textarea',
            inputPlaceholder: 'Masukkan alasan anda',
            inputAttributes: {
                'required': 'required'
            },
            // validationMessage: 'Alasan tidak boleh kosong',
            showCancelButton: true
        }).then(function(notes) {
            if (!notes.dismiss) {
                if (notes.value && notes.value.trim() !== "" && notes.value !== "") {
                    if (id_po && typeof id_po !== "undefined") {
                        $.ajax({
                            url: baseURL + "plantation/transaksi/set/delete/po",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                id_po: id_po,
                                alasan: notes.value
                            },
                            error: function(a, b, c) {
                                kiranaAlert("Error", "Server Error", "error", false, false);
                            },
                            success: function(response) {
                                if (response) {
                                    let icon = "error";
                                    let reload = false;

                                    if (response.sts == "OK") {
                                        icon = "success";
                                    }

                                    if (response.html) {
                                        kiranaAlert(response.sts, response.msg, icon, reload, response.msg);
                                    } else {
                                        kiranaAlert(response.sts, response.msg, icon, reload, false);
                                    }

                                    if (response.sts == "OK") {
                                        $("#sspTable")
                                            .DataTable()
                                            .ajax.reload();
                                    }
                                }
                            },
                            complete: function() {}
                        });
                    } else {
                        swal('Error', 'PO Tidak Ditemukan.', 'error');
                    }
                } else {
                    kiranaAlert('NotOK', "Alasan tidak boleh kosong", "warning", false, false);
                }
            }
        });

        e.preventDefault();
        return false;
    });
});

function get_data_po() {
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
            [0, 'desc']
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
            url: baseURL + "plantation/transaksi/get/po",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                pabrik: $("select[name='pabrik_filter[]']").val(),
                IN_status_sap: $("select[name='status_sap_filter[]']").val(),
                tanggal_awal: $("input[name='tanggal_awal']").val(),
                tanggal_akhir: $("input[name='tanggal_akhir']").val(),
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },
            complete: function() {}
        },
        columns: [
            {
                data: "tanggal",
                name: "tanggal",
                width: "12%",
                render: function(data, type, row) {
                    return row.tanggal_format;
                },
                visible: true
            },
            {
                data: "plant",
                name: "plant",
                width: "8%",
                visible: true,
                orderable: true
            },
            {
                data: "no_po",
                name: "no_po",
                width: "12%",
                render: function(data, type, row) {
                    let output = row.no_po;
                    return output;
                },
                visible: true,
                orderable: true
            },
            {
                data: "list_ppb",
                name: "list_ppb",
                render: function(data, type, row) {
                    let output = "";
                    if (row.list_ppb) {
                        $.each(row.list_ppb.split(";"), function(i, v) {
                            if (v)
                                output += '<div>' + v + '</div>';
                        });
                    }
                    return output;
                },
                visible: true,
                orderable: true
            },
            {
                data: "vendor",
                name: "vendor",
                render: function(data, type, row) {
                    return "[" + row.vendor + "] "+ row.nama_vendor;
                },
                visible: true,
                orderable: true
            },
            {
                data: "tipe_po",
                name: "tipe_po",
                width: "10%",
                render: function(data, type, row) {
                    let tipe = "";
                    switch (row.tipe_po) {
                        case 'HO':
                            tipe = '<span class="label label-info">HO</span>';
                            break;
                        case 'SITE':
                            tipe = '<span class="label label-warning">SITE</span>';
                            break;
                        default:
                            tipe = '<span class="label label-default">' + row.tipe_po + '</span>';
                            break;
                    }

                    return tipe;
                },
                visible: true,
                orderable: true,
                // className: 'text-center'
            },
            {
                data: "status_sap",
                name: "status_sap",
                width: "20%",
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
                orderable: true,
                searchable: false,
            },
            {
                data: null,
                name: "action",
                width: "5%",
                render: function(data, type, row) {
                    const link_edit = baseURL + "plantation/transaksi/edit/po/" + row.id;
                    const link_detail = baseURL + "plantation/transaksi/detail/po/" + row.id;
                    const target_link = "_blank";

                    output = "<div class='btn-group'>";
                    output += " <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>";
                    output += " <ul class='dropdown-menu pull-right'>";
                    output += "     <li><a href='" + link_detail + "' target='" + target_link + "' class='set_data' ><i class='fa fa-search'></i> Detail</a></li>";
                    
                    if (row.akses_delete == 1)
                        output += "     <li><a href='javascript:void(0)' class='delete' data-id_po='" + row.id + "'><i class='fa fa-trash'></i> Hapus</a></li>";

                    output += " </ul>";
                    output += "</div>";
                    return output;
                },
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
}

function kirim_sap() {
    const isproses = $("input[name='isproses']").val();
    if (isproses == 0) {
        $("input[name='isproses']").val(1);
        $.ajax({
            url: baseURL + 'data/rfc/set/plantation_po_ho',
            type: 'GET',
            error: function () {
                swal('Error', 'Server Error', 'error');
            },
            complete: function () {
                $("input[name='isproses']").val(0);
                get_data_po();
            }
        });
    } else {
        swal({
            title: "Silahkan tunggu proses selesai.",
            icon: 'info'
        });
    }
}