$(document).ready(function() {
    get_data_ppb();
    // $("#sspTable").DataTable();

    $('.input-daterange').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        autoclose: true
    });

    $(document).on("change", "select[name='pabrik_filter[]'], select[name='status_konfirmasi_filter[]'], select[name='status_ppb_filter[]'], select[name='status_po_ho_filter[]'], select[name='status_po_site_filter[]']", function(e) {
        get_data_ppb();
    });

    $(document).on("changeDate", "[name='tanggal_awal'], input[name='tanggal_akhir']", function(e) {
        get_data_ppb();
    });

    $(document).on("click", ".delete", function(e) {
        let id_ppb = $(this).data("id_ppb");

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
                    if (id_ppb && typeof id_ppb !== "undefined") {
                        $.ajax({
                            url: baseURL + "plantation/transaksi/set/delete/ppb",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                id_ppb: id_ppb,
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
                        swal('Error', 'PPB Tidak Ditemukan.', 'error');
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

function get_data_ppb() {
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
            [2, 'desc']
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
        columnDefs: [{ "targets": 2, "type": "date-eu" }, { "targets": 3, "type": "date-eu" }],
        ajax: {
            url: baseURL + "plantation/transaksi/get/ppb",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                pabrik: $("select[name='pabrik_filter[]']").val(),
                IN_status_konfirmasi: $("select[name='status_konfirmasi_filter[]']").val(),
                IN_status_po_ho: $("select[name='status_po_ho_filter[]']").val(),
                IN_status_po_site: $("select[name='status_po_site_filter[]']").val(),
                IN_status_ppb: $("select[name='status_ppb_filter[]']").val(),
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
        columns: [{
                data: "no_ppb",
                name: "no_ppb",
                // width: "20%",
                render: function(data, type, row) {
                    let output = "";
                    output += row.no_ppb;
                    return output;
                },
                visible: true,
                orderable: false
            },
            {
                data: "plant",
                name: "plant",
                width: "8%",
                render: function(data, type, row) {
                    return row.plant;
                },
                visible: true,
                orderable: false
            },
            {
                data: "tanggal",
                name: "tanggal",
                width: "10%",
                render: function(data, type, row) {
                    return row.tanggal_format;
                },
                visible: true
            },
            {
                data: "tanggal_buat",
                name: "tanggal_buat",
                width: "10%",
                render: function(data, type, row) {
                    return row.tanggal_buat_format;
                },
                visible: true
            },
            {
                data: "perihal",
                name: "perihal",
                visible: true,
                orderable: false,
            },
            {
                data: "status_konfirmasi",
                name: "status_konfirmasi",
                width: "18%",
                render: function(data, type, row) {
                    let output = "";
                    const total_detail = row.jumlah_detail;
                    const total_konfirmasi = row.jumlah_konfirmasi;
                    // const icon = (total_konfirmasi == total_detail) ? '<i class="icon fa fa-check text-green"></i>' : '<i class="icon fa fa-warning text-yellow"></i>';

                    // return icon + ' <code>' + total_konfirmasi + '</code> dari <code>' + total_detail + '</code> barang telah dikonfirmasi.';

                    if (total_konfirmasi == 0) 
                        output = '<span class="label label-danger"><i class="icon fa fa-warning"></i> Belum Terkonfirmasi</span>';
                    else if (total_konfirmasi < total_detail) 
                        output = '<span class="label label-warning"><i class="icon fa fa-warning"></i> Terkonfirmasi Sebagian</span>';
                    else if (total_konfirmasi == total_detail)
                        output = '<span class="label label-success"><i class="icon fa fa-check"></i> Terkonfirmasi Lengkap</span>';

                    return output;
                },
                visible: true,
                orderable: false,
                searchable: false,
                // className: 'text-center'
            },
            {
                data: "status_po",
                name: "status_po",
                width: "15%",
                render: function(data, type, row) {
                    let output_ho = "";
                    const total_detail_ho = row.jumlah_po_ho;
                    const total_complete_ho = row.jumlah_po_ho_complete;

                    if (total_detail_ho > 0) {
                        // const icon = (total_complete == total_detail) ? '<i class="icon fa fa-check text-green"></i>' : '<i class="icon fa fa-warning text-yellow"></i>';

                        // output = icon + ' <code>' + total_complete + '</code> dari <code>' + total_detail + '</code> barang PO HO selesai diproses.';

                        if (total_complete_ho == 0) 
                            output_ho = 'HO  : <span class="label label-danger"><i class="icon fa fa-warning"></i> Belum</span>';
                        else if (total_complete_ho < total_detail_ho) 
                            output_ho = 'HO  : <span class="label label-warning"><i class="icon fa fa-warning"></i> Sebagian</span>';
                        else if (total_complete_ho == total_detail_ho)
                            output_ho = 'HO  : <span class="label label-success"><i class="icon fa fa-check"></i> Lengkap</span>';
                    }

                    let output_site = "";
                    const total_detail_site = row.jumlah_po_site;
                    const total_complete_site = row.jumlah_po_site_complete;

                    if (total_detail_site > 0) {
                        if (total_complete_site == 0) 
                            output_site = 'SITE : <span class="label label-danger"><i class="icon fa fa-warning"></i> Belum</span>';
                        else if (total_complete_site < total_detail_site) 
                            output_site = 'SITE : <span class="label label-warning"><i class="icon fa fa-warning"></i> Sebagian</span>';
                        else if (total_complete_site == total_detail_site)
                            output_site = 'SITE : <span class="label label-success"><i class="icon fa fa-check"></i> Lengkap</span>';
                    }

                    const divider = (total_detail_ho > 0 && total_detail_site > 0) ? "<br>" : "";
                    const output = output_ho + divider + output_site;

                    return output;
                },
                visible: true,
                orderable: false,
                searchable: false
            },
            {
                data: "jumlah_hari_berjalan",
                name: "jumlah_hari_berjalan",
                width: "12%",
                render: function(data, type, row) {
                    let output = "";
                    if (row.jumlah_hari_berjalan > 30)
                        output = '<i class="icon fa fa-warning text-yellow"></i> Closed';

                    return output;
                },
                visible: true,
                orderable: false,
                searchable: false
            },
            {
                data: "deskripsi",
                name: "deskripsi",
                width: "5%",
                render: function(data, type, row) {
                    const link_konfirmasi = baseURL + "plantation/transaksi/konfirmppb/" + row.id;
                    const link_create_poho = baseURL + "plantation/transaksi/createpoho/" + row.id;
                    const link_detail = baseURL + "plantation/transaksi/detail/ppb/" + row.id;
                    const target_link = "_blank";

                    output = "<div class='btn-group'>";
                    output += " <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>";
                    output += " <ul class='dropdown-menu pull-right'>";
                    if (row.akses_konfirmasi_ppb == 1)
                        output += "     <li><a href='" + link_konfirmasi + "' target='" + target_link + "' class='set_data' ><i class='fa fa-pencil'></i> Konfirmasi</a></li>";
                    output += "     <li><a href='" + link_detail + "' target='" + target_link + "' class='set_data' ><i class='fa fa-search'></i> Detail</a></li>";
                    if (row.jumlah_po_ho && row.jumlah_po_ho > 0 
                        && row.akses_create_po == 1 
                        //&& (row.is_closed == 0 && row.jumlah_hari_berjalan <= 30)
                    )
                        output += "     <li><a href='" + link_create_poho + "' target='" + target_link + "' class='detail' ><i class='fa fa-cart-plus'></i> Buat PO HO</a></li>";
                    if (row.akses_delete == 1 && row.jumlah_konfirmasi == 0 && (row.is_closed == 0 && row.jumlah_hari_berjalan <= 30))
                        output += "     <li><a href='javascript:void(0)' class='delete' data-id_ppb='" + row.id + "'><i class='fa fa-trash'></i> Hapus</a></li>";
                    
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