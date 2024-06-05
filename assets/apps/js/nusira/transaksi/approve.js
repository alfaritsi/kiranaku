$(document).ready(function () {
    datatables_ssp();

    //=======FILTER=======//
    $(document).on("change", "select[name='plant_filter[]'], select[name='status_filter[]']", function () {
        datatables_ssp();
    });

    //approve
    $(document).on("click", ".approve", function () {
        var no_pi = $(this).data("edit");
        var action = $(this).data("action");
        $.ajax({
            url: baseURL + 'nusira/transaksi/get/approve',
            type: 'POST',
            dataType: 'JSON',
            data: {
                no_pi: no_pi,
                return: "json"
            },
            success: function (data) {
                $(".title-form").html("Form Approve PI");
                if (data) {
                    $("input[name='no_pi']").val(data.no_pi);
                    $("input[name='nomor_pi']").val(data.no_pi);
                    $("input[name='plant']").val(data.plant);
                    $("input[name='tanggal_pi']").val(data.tanggal_format);

                    if (data.detail && (action == 'approve' || action == 'detail')) {
                        //detail
                        var det = "";
                        det += "<table class='table table-bordered'>";
                        det += "	<thead>";
                        det += "		<tr>";
                        det += "			<th rowspan=2 width='5%'>No</th>";
                        det += "			<th rowspan=2 width='20%'>Material</th>";
                        det += "			<th rowspan=2 width='5%'>Order Qty</th>";
                        det += "			<th rowspan=2 width='5%'><center>Y/N</center></th>";
                        det += "			<th colspan=2 width='40%'><center>Delivery Date</center></th>";
                        det += "			<th rowspan=2 width='25%'>Reason</th>";
                        det += "		</tr>";
                        det += "		<tr>";
                        det += "			<th width='12%'>Request</th>";
                        det += "			<th width='18%'>Durasi<br>Pengerjaan</th>";
                        det += "		</tr>";
                        det += "	</thead>";
                        det += "	<tbody>";
                        $.each(data.detail, function (i, v) {
                            if (v.matnr !== "" && v.matnr !== "ONGKIR-001") {
                                det += "<tr>";
                                det += "	<td>";
                                det += v.no;
                                det += "		<input type='hidden' name='no[]' value='" + v.no + "'/>";
                                det += "	</td>";
                                det += "	<td>";
                                det += "		[" + v.matnr + "] " + v.MAKTX;
                                det += "	</td>";
                                det += "	<td>";
                                det += v.jumlah;
                                det += "	</td>";
                                det += "	<td>";
                                det += "		<input type='checkbox' class='switch-onoff' name='status_nsw[]' data-no='" + v.no + "' " + (v.status_nsw == 1 ? "checked" : "") + ">";
                                det += "	</td>";
                                det += "	<td>";
                                det += v.req_deliv_date_format;
                                det += "	</td>";
                                det += "	<td>";
                                det += "		<div class='input-group date'>";
                                det += "			<input type='number' class='form-control' name='durasi[]' placeholder='Durasi' value='" + (v.nsw_durasi_mgg ? v.nsw_durasi_mgg : 0) + "' readonly='readonly'>";
                                det += "			<div class='input-group-addon'>minggu</div>";
                                det += "		</div>";
                                det += "	</td>";
                                det += "	<td>";
                                det += "		<select class='form-control select2modal form-control-hide' min='0' name='reason[]' " + (v.status_nsw == 1 ? "" : "required='required'") + " >";
                                det += "			<option></option>";
                                if (data.reason) {
                                    $.each(data.reason, function (id, val) {
                                        if (v.nsw_reason == val.reason) {
                                            det += "	<option value='" + val.reason + "' selected>" + val.reason + "</option>";
                                        } else {
                                            det += "	<option value='" + val.reason + "'>" + val.reason + "</option>";
                                        }

                                    });
                                }
                                det += "		</select>";
                                det += "	</td>";
                                det += "</tr>";
                            }
                        });
                        det += "	</tbody>";
                        det += "</table>";
                    }

                    if (data.history && action == 'history') {
                        var his = "";
                        his += "<table class='table table-bordered'>";
                        his += "	<thead>";
                        his += "		<tr>";
                        his += "			<th rowspan=2 width='5%'>No</th>";
                        his += "			<th rowspan=2 width='20%'>Material</th>";
                        his += "			<th rowspan=2 width='5%'>Order Qty</th>";
                        his += "			<th rowspan=2 width='5%'><center>Y/N</center></th>";
                        his += "			<th colspan=2 width='20%'><center>Delivery Date</center></th>";
                        his += "			<th rowspan=2>Reason</th>";
                        his += "			<th rowspan=2 width='15%'>Edited By</th>";
                        his += "			<th rowspan=2 width='10%'>Edited Date</th>";
                        his += "		</tr>";
                        his += "		<tr>";
                        his += "			<th width='12%'>Request</th>";
                        his += "			<th width='18%'>Durasi<br>Pengerjaan</th>";
                        his += "		</tr>";
                        his += "	</thead>";
                        his += "	<tbody>";
                        $.each(data.history, function (i, v) {
                            if (v.matnr !== "" && v.matnr !== "ONGKIR-001") {
                                his += "<tr>";
                                his += "	<td>" + v.no + "</td>";
                                his += "	<td>";
                                his += "		[" + v.matnr + "] " + v.MAKTX;
                                his += "	</td>";
                                his += "	<td align='right'>" + v.jumlah + "</td>";
                                if ((v.status_nsw == 0) || (v.status_nsw == null)) {
                                    his += "<td><input type='checkbox' class='switch-onoff' disabled></td>";
                                }
                                if (v.status_nsw == 1) {
                                    his += "<td><input type='checkbox' class='switch-onoff' checked disabled></td>";
                                }
                                his += "	<td align='right'></td>";
                                his += "	<td align='right'>" + (v.nsw_durasi_mgg == null ? '-' : v.nsw_durasi_mgg) + "</td>";
                                his += "	<td align='right'>" + (v.nsw_reason == null ? '-' : v.nsw_reason) + "</td>";
                                his += "	<td align='right'></td>";
                                his += "	<td align='right'></td>";
                                his += "</tr>";
                            }
                        });
                        his += "	</tbody>";
                        his += "</table>";
                    }

                    if (action == 'history') {
                        $("#show_detail").html(his);
                    } else {
                        $("#show_detail").html(det);
                    }
                };
            },
            error: function () {
                kiranaAlert("notOK", "Server Error", "error", "no");
            },
            complete: function () {
                if (action !== 'approve') {
                    $('.form-control-hide').prop('disabled', true);
                    $('.switch-onoff').prop('disabled', true);
                    $("#btn_save").hide();
                }
                $('.switch-onoff').bootstrapToggle({
                    on: 'Yes',
                    off: 'No'
                });
                $('#approve_modal').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#approve_modal'),
                    allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
                    placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
                });
            }
        });
    });

    $(document).on("change", ".switch-onoff", function (e) {
        let no = $(this).data("no");
        let stat = $(this).prop('checked');
        $(this).closest("tr").find("select[name='reason[]']").val(null).trigger("change");
        $(this).closest("tr").find("input[name='durasi[]']").val(0);
        if (stat == false) {
            $(this).closest("tr").find("input[name='durasi[]']").attr("readonly", true);
            $(this).closest("tr").find("select[name='reason[]']").attr("required", true);
        } else {
            $(this).closest("tr").find("input[name='durasi[]']").attr("readonly", false);
            $(this).closest("tr").find("select[name='reason[]']").attr("required", false);
        }
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate('.form-transaksi-approve');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-approve")[0]);
                $.ajax({
                    url: baseURL + 'nusira/transaksi/save/approve',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(data.sts, data.msg, "error", "no");
                        }
                    },
                    error: function () {
                        $("input[name='isproses']").val(0);
                        kiranaAlert("notOK", "Server Error", "error", "no");
                    }
                });
            } else {
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
            }
        }
        e.preventDefault();
        return false;
    });

});

function datatables_ssp() {
    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        if (oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        }
    };

    var plant_filter = $("select[name='plant_filter[]']").val();
    var status_filter = $("select[name='status_filter[]']").val();
    $("#sspTable").DataTable().destroy();

    var mydDatatables = $("#sspTable").DataTable({
        pageLength: 10,
        ordering: $("#sspTable").data("ordering") ?
            $("#sspTable").data("ordering") : false,
        scrollY: $("#sspTable").data("scrolly") ?
            $("#sspTable").data("scrolly") : false,
        scrollX: $("#sspTable").data("scrollx") ?
            $("#sspTable").data("scrollx") : false,
        bautoWidth: $("#sspTable").data("bautowidth") ?
            $("#sspTable").data("bautowidth") : false,
        pageLength: $("#sspTable").data("pagelength") ?
            $("#sspTable").data("pagelength") : 10,
        paging: $("#sspTable").data("paging") ?
            $("#sspTable").data("paging") : true,
        fixedHeader: $("#sspTable").data("fixedheader") ?
            $("#sspTable").data("fixedheader") : false,
        initComplete: function () {
            var api = this.api();
            $("#sspTable_filter input").attr(
                "placeholder",
                "Press enter to start searching"
            );
            $("#sspTable_filter input").attr(
                "title",
                "Press enter to start searching"
            );
            $("#sspTable_filter input")
                .off(".DT")
                .on("keypress change", function (evt) {
                    if (evt.type == "change") {
                        api.search(this.value).draw();
                    }
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
        searching: true,
        order: [
            [3, 'desc']
        ],
        ajax: {
            url: baseURL + 'nusira/transaksi/get/approve',
            type: 'POST',
            data: {
                plant_filter: plant_filter,
                status_filter: status_filter,
                return: "datatables"
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [{
            "data": "plant",
            "name": "plant",
            "width": "5%",
            "render": function (data, type, row) {
                return row.plant;
            },
            visible: true,
            orderable: false
        },
        {
            "data": "no_pi",
            "name": "no_pi",
            "width": "5%",
            "render": function (data, type, row) {
                return row.no_pi;
            },
            visible: true,
            orderable: false
        },
        {
            "data": "perihal",
            "name": "perihal",
            "width": "13%",
            "render": function (data, type, row) {
                return row.perihal;
            },
            visible: true,
            orderable: false
        },
        {
            data: "tanggal_buat",
            name: "tanggal_buat",
            width: "7%",
            render: function (data, type, row) {
                return row.tanggal_format;
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
                        status = '<label class="label label-success">FINISH</label>';
                        break;
                    case 'drop':
                        status = '<label class="label label-danger">DROP</label>';
                        break;
                    case 'deleted':
                        status = '<label class="label label-danger">DELETED</label>';
                        status += '<br><small>Dihapus oleh ' + row.status_pi_delete + '</small>';
                        break;
                    default:
                        status = '<label class="label label-warning">ON PROGRESS</label>';
                        status += '<br><small>Sedang diproses oleh ' + row.status_pi + '</small>';
                        break;
                }

                return status;
            },
            visible: true,
            orderable: false
        },
        {
            "data": "nsw_check",
            "name": "nsw_check",
            "width": "5%",
            "render": function (data, type, row) {
                if (row.nsw_check == 1) {
                    return '<label class="label label-success">Approved</label>';
                } else {
                    return '<label class="label label-warning">Waiting</label>';
                }
            },
            visible: true,
            orderable: false
        },
        {
            "data": "no_pi",
            "name": "no_pi",
            "width": "5%",
            "render": function (data, type, row) {
                output = "			<div class='input-group-btn'>";
                output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                output += "				<ul class='dropdown-menu pull-right'>";
                if (row.div_head_vendor == null || row.nsw_check == 0) {
                    output += "					<li><a href='javascript:void(0)' class='approve' data-edit='" + row.no_pi + "' data-action='approve'><i class='fa fa-check-square-o'></i> Approve</a></li>";
                } else {
                    output += "					<li><a href='javascript:void(0)' class='approve' data-edit='" + row.no_pi + "' data-action='detail'><i class='fa fa-search'></i> Detail</a></li>";
                    output += "					<li><a href='javascript:void(0)' class='approve' data-edit='" + row.no_pi + "' data-action='history'><i class='fa fa-h-square'></i> History</a></li>";
                }
                output += "				</ul>";
                output += "	        </div>";
                return output;
            },
            visible: true,
            orderable: false
        }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            if (info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });

    return mydDatatables;
}