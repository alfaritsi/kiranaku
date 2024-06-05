$(document).ready(function () {
    get_data_layout();

    $(document).on("click", "#btn-new, #btn_reset", function (e) {
        reset_form();
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        const empty_form = validate('#form-master');

        if (empty_form == 0) {
            let isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData($("#form-master")[0]);

                $.ajax({
                    url: baseURL + 'outspec/master/save/layout',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                get_data_layout()
                                reset_form();
                                $("input[name='isproses']").val(0);
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', '.edit', function (e) {
        reset_form();
        const id_layout = $(this).attr('data-edit');
        $.ajax({
            url: baseURL + 'outspec/master/get/layout',
            type: 'POST',
            dataType: 'JSON',
            data: {
                return: 'json',
                all: 'yes',
                id_layout: id_layout
            },
            success: function (data) {
                if (data) {
                    $("input[name='id_layout']").val(data.id_layout);
                    $("input[name='nama']").val(data.nama);
                    $("input[name='jumlah_bales']").val(data.jumlah_bales);
                    $("input[name='urutan']").val(data.urutan);
                    $('input[name="file[]"]').prop("required", false);
                    $("#btn-new").removeClass("hidden");
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    })

    $(document).on("click", '.delete', function (e) {
        const id_layout = $(this).attr("data-delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'outspec/master/set/layout',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_layout: id_layout,
                            action: 'delete'
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg, 'success', 'no');
                                get_data_layout();
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );

    });

    $(document).on("click", ".activate", function (e) {
        const id_layout = $(this).attr("data-active");
        const action = $(this).attr("data-action");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan " + action + " data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'outspec/master/set/layout',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_layout: id_layout,
                            action: action
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg, 'success', 'no');
                                get_data_layout();
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );
    });
});

function get_data_layout() {
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
            [0, 'asc']
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
        // serverSide: true,
        searching: true,
        // columnDefs: [{ "targets": 2, "type": "date-eu" }],
        ajax: {
            url: baseURL + "outspec/master/get/layout",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                all: 'yes',
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },
            complete: function () { }
        },
        columns: [{
            data: "urutan",
            name: "urutan",
            width: "5%",
            visible: true,
            orderable: true
        },
        {
            data: "nama",
            name: "nama",
            visible: true,
            orderable: true
        },
        {
            data: "files",
            name: "files",
            render: function (data, type, row) {
                let output = "";
                if (row.files) {
                    output = '<img src="' + baseURL + 'assets/' + row.files + '" alt="" width="120">';
                }
                return output;
            },
            visible: true,
            orderable: true
        },
        {
            data: "jumlah_bales",
            name: "jumlah_bales",
            width: "15%",
            visible: true,
            orderable: true
        },
        {
            data: "status",
            name: "status",
            width: "10%",
            render: function (data, type, row) {
                let label = 'success';
                let status = 'AKTIF'
                if (row.na != 'n') {
                    label = 'danger';
                    status = 'NON AKTIF';
                }
                let output = '<div><button class="btn btn-sm btn-' + label + '">' + status + '</button></div>';
                return output;
            },
            visible: true,
            orderable: false
        },
        {
            data: "deskripsi",
            name: "deskripsi",
            width: "5%",
            render: function (data, type, row) {
                output = "			<div class='btn-group'>";
                output += "				<button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>";
                output += "				<ul class='dropdown-menu pull-right'>";
                output += "                 <li><a href='#' class='edit' data-edit='" + row.id_layout + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
                if (row.na == 'n') {
                    output += "                 <li><a href='#' class='activate' data-active='" + row.id_layout + "' data-action='deactivate'><i class='fa fa-minus text-danger'></i> Non Active</a></li>";
                    output += "                 <li><a href='#' class='delete' data-delete='" + row.id_layout + "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                } else {
                    output += "                 <li><a href='#' class='activate' data-active='" + row.id_layout + "' data-action='activate'><i class='fa fa-check text-success'></i> Set Active</a></li>";
                }
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

const reset_form = () => {
    $("#btn-new").addClass("hidden");
    $('input[name="id_layout"]').val("");
    $('input[name="nama"]').val("");
    $('input[name="file[]"]').val("");
    $('input[name="file[]"]').prop("required", true);
    $('input[name="jumlah_bales"]').val("");
    $('input[name="urutan"]').val("");
}