$(document).ready(function () {
    let dataSO = null;

    get_data_so();
    get_limit_date();

    $(document).on("change", "#pabrik, #status", function () {
        get_data_so();
    });

    $(document).on("changeDate", "#tanggal_awal_filter, #tanggal_akhir_filter", function (e) {
        if (e.target == $("#tanggal_awal_filter")[0]) {
            var minDate = new Date(regenerateDatetimeFormat($(this).val(), "DD.MM.YYYY", "YYYY-MM-DD"));
            console.log(minDate);
            $('#tanggal_akhir_filter').datepicker('setStartDate', minDate);
        }
        if (e.target == $("#tanggal_akhir_filter")[0]) {
            var maxDate = new Date(regenerateDatetimeFormat($(this).val(), "DD.MM.YYYY", "YYYY-MM-DD"));
            console.log(maxDate);
            $('#tanggal_awal_filter').datepicker('setEndDate', maxDate);
        }
        get_data_so();
    });

    $(document).on('click', '.item-detail', function () {
        let no_pi = $(this).data("pi");
        let no_so = $(this).data("so");

        let dataTable = $('#sspTable').DataTable();
        let tr = $(this);
        let row = dataTable.row(tr);

        if (row.child.isShown()) {
            $(this).css('background-color', '');
            row.child.hide();
            adjustDatatableWidth();
        } else {
            if (no_pi && no_so) {
                $.ajax({
                    url: baseURL + 'nusira/monitoring/get/item_so',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        no_pi: no_pi,
                        no_so: no_so
                    },
                    success: function (data) {
                        if ($.isEmptyObject(data) === false) {
                            var output = "";
                            output += '<div id="table-items-' + no_so + '">';
                            output += '		<div class="box box-info" style="margin-top:10px; max-width: max-content;">';
                            output += '			<div class="box-header with-border">';
                            output += '				<h3 class="box-title">Sales Order Items</h3>';
                            output += '			</div>';
                            output += '			<div class="box-body no-padding">';
                            output += '				<table class="table table-bordered table-so-detail">';
                            output += '					<thead>';
                            output += '						<th>No Item</th>';
                            output += '						<th>Kode SAP</th>';
                            output += '						<th>Deskripsi</th>';
                            output += '						<th>UOM</th>';
                            output += '						<th>Order Qty</th>';
                            output += '						<th>Free Stock</th>';
                            output += '						<th>Fulfillment Qty</th>';
                            output += '						<th>Action</th>';
                            output += '					</thead>';
                            output += '					<tbody>';
                            output += generate_detail(data);
                            output += '					</tbody>';
                            output += '				</table>';
                            output += '			</div>';
                            output += '		</div>';
                            output += '</div>';

                            $(this).css('background-color', '#e6e6e6');
                            row.child(output).show();
                        } else {
                            kiranaAlert("notOK", "Nomor SO " + no_so + " tidak ditemukan di SAP", "error", "no");
                        }
                    },
                    complete: function () {
                        adjustDatatableWidth();
                    }
                });
            }
        }
    });

    $(document).on('click', '.so-detail', function () {
        var action = $(this).data("action");
        let data = JSON.parse($(this).attr("data-so").replace(/;;;/g, '"'));
        dataSO = data;
        console.log(dataSO);

        if (data.detail) {
            $('#KiranaModals .modal-dialog').addClass("modal-lg");

            var output = '';
            output += generate_modal_spk(data, this);

            var footer = "";
            footer += '<div class="modal-footer">';
            footer += '	<div class="row">';
            footer += '		<div class="col-md-6 col-md-offset-3 text-center lihat-spk">';
            footer += '			<button class="btn btn-danger" type="reset" data-dismiss="modal">Tutup</button>';
            footer += '		</div>';
            footer += '		<div class="col-md-6 col-md-offset-3 text-center buat-spk hide">';
            footer += '			<button class="btn btn-danger" type="reset" data-dismiss="modal">Batal</button>';
            footer += '			<button class="btn btn-success btn-spk" type="submit">Simpan</button>';
            footer += '		</div>';
            footer += '		<div class="col-md-6 col-md-offset-3 text-center buat-booked hide">';
            footer += '			<button class="btn btn-danger" type="reset" data-dismiss="modal">Batal</button>';
            footer += '			<button class="btn btn-success btn-booked" type="submit">Simpan</button>';
            footer += '		</div>';
            footer += '	</div>';
            footer += '</div>';

            if (output !== "") {
                $("#KiranaModals .modal-footer").remove();

                $('#KiranaModals .modal-body').html(output);
                $('#KiranaModals .modal-content').append(footer);

                if (action == "booked") {
                    $('#KiranaModals #form-buat-spk').removeClass('hide');
                    $('#KiranaModals .lihat-spk').addClass('hide');
                    $('#KiranaModals .table-history-spk').addClass('hide');
                    $('#KiranaModals .table-bom-spk').removeClass('hide');

                    $("#KiranaModals .modal-title").html("Booked Free Stock");
                    $('#KiranaModals .buat-booked').removeClass('hide');
                    $('#KiranaModals .buat-spk').addClass('hide');
                    $('#KiranaModals .action-spk').addClass('hide');
                    $('#KiranaModals .action-booked').removeClass('hide');
                    $('#KiranaModals .action-history').addClass('hide');
                    $('#KiranaModals .legend-lable').html('Demand');
                } else if (action == "buat") {
                    $('#KiranaModals #form-buat-spk').removeClass('hide');
                    $('#KiranaModals .lihat-spk').addClass('hide');
                    $('#KiranaModals .table-history-spk').addClass('hide');
                    $('#KiranaModals .table-bom-spk').removeClass('hide');

                    $("#KiranaModals .modal-title").html("Surat Perintah Kerja");
                    get_data_bom(data.no_mat);
                    $('#KiranaModals .buat-spk').removeClass('hide');
                    $('#KiranaModals .buat-booked').addClass('hide');
                    $('#KiranaModals .action-spk').removeClass('hide');
                    $('#KiranaModals .action-booked').addClass('hide');
                    $('#KiranaModals .action-history').addClass('hide');
                    $('#KiranaModals .legend-lable').html('Component Overview');
                } else {
                    $("#KiranaModals .modal-title").html("Detail Pemenuhan");
                    $('#KiranaModals #form-buat-spk').addClass('hide');
                    $('#KiranaModals .lihat-spk').removeClass('hide');
                    $('#KiranaModals .table-history-spk').removeClass('hide');
                    $('#KiranaModals .table-bom-spk').addClass('hide');
                    $('#KiranaModals .action-spk').addClass('hide');
                    $('#KiranaModals .action-booked').addClass('hide');
                    $('#KiranaModals .action-history').removeClass('hide');
                    $('#KiranaModals .legend-lable').html('History');
                }

                $('[data-js=datepicker]').datepicker({
                    format: 'dd.mm.yyyy',
                    autoclose: true,
                    todayHighlight: true,
                    weekStart: 1,
                    inputs: $('.tgl_awal_akhir')
                });
                $('#start').on('changeDate', function (e) {
                    $('#end').datepicker('setStartDate', e.date);
                });

                $('.kiranaCheckbox').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                    increaseArea: '20%' // optional
                });

                $('#KiranaModals').modal({
                    backdrop: 'static',
                    keyboard: true,
                    show: true
                });

                if (action == "booked") {
                    $.ajax({
                        url: baseURL + 'nusira/monitoring/get/list_demand',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            no_so: data.no_so,
                            no_mat: data.no_mat
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {

                                let table = $("#KiranaModals #table-booked").DataTable();
                                var totalqty = 0;
                                // let datatableData = [];
                                $.each(data.data, function (index, item) {
                                    // datatableData.push([
                                    var myRow = table.row.add([
                                        item.plant,
                                        item.no_so,
                                        parseInt(item.qty),
                                        ""
                                    ]).draw().node();

                                    $(myRow).find('td').eq(0).addClass('text-center');
                                    $(myRow).find('td').eq(1).addClass('text-center');
                                    $(myRow).find('td').eq(2).addClass('text-center');
                                    $(myRow).find('td').eq(3).addClass('text-center');
                                    totalqty = parseInt(totalqty) + parseInt(item.qty);
                                });
                                $("#th_totalqty").html(totalqty);
                                // table.clear();
                                // table.rows.add(datatableData).draw(false);
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                } else {
                    $.ajax({
                        url: baseURL + 'nusira/monitoring/get/list_history',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            no_po: data.no_po,
                            no_so: data.no_so,
                            no_mat: data.no_mat,
                            no_pos: data.no_pos
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {

                                let table = $("#KiranaModals #table-history-spk").DataTable();
                                var totalqty = 0;
                                // let datatableData = [];
                                $.each(data.data, function (index, item) {
                                    // datatableData.push([
                                    if (item.doc_from == "SPK") {
                                        var doc_numb = item.no_io;
                                        var doc_date = item.start + " s/d " + item.end;
                                    } else {
                                        var doc_numb = item.mat_doc;
                                        var doc_date = item.doc_date;
                                    }

                                    var myRow = table.row.add([
                                        item.doc_from,
                                        doc_numb,
                                        doc_date,
                                        item.qty,
                                        item.no_io ? "<a href='" + baseURL + "nusira/monitoring/cetak/mto/" + item.no_io + "' target='_blank'><i class='fa fa-print'></i></a>" : ""
                                    ]).draw().node();

                                    $(myRow).find('td').eq(0).addClass('text-center');
                                    $(myRow).find('td').eq(1).addClass('text-center');
                                    $(myRow).find('td').eq(2).addClass('text-center');
                                    $(myRow).find('td').eq(3).addClass('text-center');
                                    $(myRow).find('td').eq(4).addClass('text-center');
                                    totalqty = parseInt(totalqty) + parseInt(item.qty);
                                });
                                $("#total").html(totalqty);

                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }

                $("#KiranaModals #table-bom-spk").dataTable();

            }
        } else {
            kiranaAlert("notOK", "Server error, terdapat kesalahan saat pengambilan data dari SAP.", "error", "no");
        }
    });

    $(document).on('click', '.btn-spk', function (e) {
        e.preventDefault();

        let modal = $('#KiranaModals');
        let data = dataSO;

        let start = $('#start', modal).val();
        let end = $('#end', modal).val();
        let qty = $('#qty', modal).val();
        let uom = $('input[name="uom"]', modal).val();
        let isHouse = $('input[name="isHouse"]', modal).val();

        let valid = validate('#form-buat-spk', true);

        if (valid === 0) {
            $.ajax({
                url: baseURL + 'nusira/monitoring/set/spk',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    no_po: data.detail.no_po,
                    no_so: data.no_so,
                    no_mat: data.no_mat,
                    no_pos: data.no_pos,
                    nama_mat: data.nama_mat,
                    start: start,
                    end: end,
                    qty: qty,
                    uom: uom,
                    isHouse: isHouse,
                },
                success: function (data) {
                    if (data.sts == 'OK') {
                        kiranaAlert(data.sts, data.msg);
                        window.open(baseURL + 'nusira/monitoring/cetak/mto/' + data.no_io, '_blank');
                    } else {
                        kiranaAlert(data.sts, data.msg, 'error', 'no');
                    }
                },
                error: function (data) {
                    kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                }
            });
        }
        return false;
    });

    $(document).on('click', '.btn-booked', function (e) {
        e.preventDefault();

        let modal = $('#KiranaModals');
        let data = dataSO;

        let qty = $('#qty', modal).val();
        let uom = $('input[name="uom"]', modal).val();

        let valid = validate('#form-buat-spk', true);

        if (valid === 0) {
            $.ajax({
                url: baseURL + 'nusira/monitoring/set/booked',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    no_so: data.no_so,
                    no_mat: data.no_mat,
                    no_pos: data.no_pos,
                    nama_mat: data.nama_mat,
                    qty: qty,
                    uom: uom,
                },
                success: function (data) {
                    if (data.sts == 'OK') {
                        kiranaAlert(data.sts, data.msg);
                    } else {
                        kiranaAlert(data.sts, data.msg, 'error', 'no');
                    }
                },
                error: function (data) {
                    kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                }
            });
        }
        return false;
    });

    $(document).on("change", "#qty", function (e) {
        $("#KiranaModals #table-bom-spk").DataTable().clear();
        get_data_bom(dataSO.no_mat);
        e.preventDefault();
        return false;
    });

    $(document).on("ifChanged", ".isHouse", function () {
        var check = $(this).prop("checked");
        if (check == true) {
            $(this).closest(".control-label").find("span").html("Ya");
            $("input[name='isHouse']").val('1');
        } else {
            $(this).closest(".control-label").find("span").html("Tidak");
            $("input[name='isHouse']").val('0');
        }
    });
});


function get_limit_date() {
    var minDate = new Date(regenerateDatetimeFormat($("#tanggal_awal_filter").val(), "DD.MM.YYYY", "YYYY-MM-DD"));
    $('#tanggal_akhir_filter').datepicker('setStartDate', minDate);
    var maxDate = new Date(regenerateDatetimeFormat($('#tanggal_akhir_filter').val(), "DD.MM.YYYY", "YYYY-MM-DD"));
    $('#tanggal_awal_filter').datepicker('setEndDate', maxDate);
}

function get_data_so() {
    let tanggal_awal_filter = $("#tanggal_awal_filter").val();
    let tanggal_akhir_filter = $("#tanggal_akhir_filter").val();
    let pabrik = $("#pabrik").val();
    let status = $("#status").val();

    $('#sspTable').DataTable().clear().destroy();

    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };

    /**
     * Get data using serverside datatables
     * Rules:
     * if you need to get data from more than 1 table,
     *        you need to write down table alias + real column in column->data ex: tb1.column
     *        and you need to write down column alias in column->name
     */
    $("#sspTable").dataTable({
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input')
                .off('.DT')
                .on('input.DT', function () {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "Please wait ..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: baseURL + 'nusira/monitoring/get/so',
            type: 'POST',
            data: {
                tanggal_awal: tanggal_awal_filter,
                tanggal_akhir: tanggal_akhir_filter,
                pabrik: pabrik,
                status: status,
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [{
            "data": "no_so",
            "name": "no_so",
            "width": "5%",
            "render": function (data, type, row) {
                return row.no_so;
            }
        },
        {
            "data": "pabrik_pemesan",
            "name": "pabrik_pemesan",
            "width": "8%",
            "render": function (data, type, row) {
                return row.pabrik_pemesan;
            }
        },
        {
            "data": "no_po",
            "name": "no_po",
            "orderable": false,
            "width": "15%",
            "render": function (data, type, row) {
                return row.no_po;
            }
        },
        {
            "data": "no_pi",
            "name": "no_pi",
            "width": "5%",
            "render": function (data, type, row) {
                return row.no_pi;
            }
        },
        {
            "data": "tanggal_ori",
            "name": "tanggal_ori",
            "width": "5%",
            "render": function (data, type, row) {
                return row.tanggal;
            }
        },
        {
            "data": "status",
            "name": "status",
            "width": "5%",
            "render": function (data, type, row) {
                const percent = (row.jml_item_pi_sudah_spk / row.jml_item_pi) * 100;
                let label = "";
                if (percent <= 50) label = "text-red";
                else if (percent > 50 && percent <= 75) label = "text-yellow";
                else if (percent == 100) label = "text-green";

                return "<span class='" + label + "'>" + percent.toFixed(2) + "%</span>";
            },
            "className": "text-center"
        },
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $('td:eq(0)', row).html();
            $(row).addClass("item-detail");
            $(row).css("cursor", "pointer");
            $(row).attr("data-pi", data.no_pi);
            $(row).attr("data-so", data.no_so);
        }
    });
}

function generate_detail(data) {
    var output = "";
    $.each(data, function (i, v) {
        // var data_qty = "";
        // data_qty += "<dl class='dl-horizontal' style='margin:0;'>";
        // data_qty += "	<dt class='text-left' style='font-weight: normal'>Total Order</dt>";
        // data_qty += "	<dd class='text-left'>: " + v.qty_ord + "</dd>";
        // data_qty += "	<dt class='text-left' style='font-weight: normal'>Stock Booked</dt>";
        // data_qty += "	<dd class='text-left'>: " + v.qty_reserve + "</dd>";
        // data_qty += "	<dt class='text-left' style='font-weight: normal'>SPK Qty</dt>";
        // data_qty += "	<dd class='text-left'>: " + v.qty_spk + "</dd>";
        // data_qty += "	<dt class='text-left' style='font-weight: normal'>Order Remaining</dt>";
        // data_qty += "	<dd class='text-left'>: " + v.qty_ord_left + "</dd>";
        // data_qty += "</dl>";

        output += '<tr>';
        output += '	<td class="text-center">' + v.no_pos + '</td>';
        output += '	<td class="text-left">' + v.no_mat + '</td>';
        output += '	<td class="text-left">' + v.nama_mat + '</td>';
        output += '	<td class="text-center">' + v.uom + '</td>';
        // output += '	<td class="text-center">' + data_qty + '</td>';
        output += '	<td class="text-center">' + v.qty_ord + '</td>';
        output += '	<td class="text-center">' + parseFloat(v.qty_stock) + '</td>';
        output += '	<td class="text-center">' + v.qty_spk + '</td>';
        output += '	<td class="text-center">';
        output += '		<div class="input-group-btn">';
        output += '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
        output += '			<ul class="dropdown-menu pull-right">';
        var button = '';
        if (v.qty_ord > v.qty_spk) {
            if (parseFloat(v.qty_stock) > 0) {
                button += '	<li><a href="javascript:void(0)" class="so-detail" data-so="' + JSON.stringify(v).replace(/&quot;/g, '\\"').replace(/\"/g, ";;;") + '" data-action="booked"><small><i class="fa fa-plus"></i></small> Booked Free Stock</a></li>';
            }
            button += '	<li><a href="javascript:void(0)" class="so-detail" data-so="' + JSON.stringify(v).replace(/&quot;/g, '\\"').replace(/\"/g, ";;;") + '" data-action="buat"><small><i class="fa fa-plus"></i></small> Buat SPK</a></li>';
        }
        if (v.qty_spk > 0) {
            button += ' <li><a href="javascript:void(0)" class="so-detail" data-so="' + JSON.stringify(v).replace(/&quot;/g, '\\"').replace(/\"/g, ";;;") + '" data-action="lihat"><small><i class="fa fa-search"></i></small> Lihat Pemenuhan</a></li>';
        }
        output += button;
        output += ' 		</ul>';
        output += ' 	</div>';
        output += ' </td>';
        output += '</tr>';
    });
    return output;
}

function generate_modal_spk(data, btn) {
    var output = '';
    output += '<div class="row wrapper-row-modal">';
    output += '	<div class="col-sm-12">';
    output += '		<form id="form-buat-spk">';

    if ($(btn).attr("data-action") == 'buat') {
        output += '			<div class="row action-spk">';
        output += '				<div class="col-sm-8">';
        output += '					<div class="form-group row" style="margin-bottom:5px;">';
        output += '						<div class="col-sm-4"><strong>Nomor SO / Item</strong></div>';
        output += '						<div class="col-sm-8" id="no_so_po">' + data.no_so + ' / ' + data.no_mat + '</div>';
        output += '					</div>';
        output += '					<div class="form-group row" style="margin-bottom:5px;">';
        output += '						<div class="col-sm-4"><strong>Order Qty</strong></div>';
        output += '						<div class="col-sm-8" id="plant">' + data.qty_ord + '</div>';
        output += '					</div>';
        output += '					<div class="form-group row" style="margin-bottom:5px;">';
        output += '						<div class="col-sm-4"><strong>Pabrik Pemesan</strong></div>';
        output += '						<div class="col-sm-8" id="plant">' + data.header.plant + '</div>';
        output += '					</div>';
        output += '					<div class="form-group row" style="margin-bottom:5px;">';
        output += '						<div class="col-sm-4"><strong>Material</strong></div>';
        output += '						<div class="col-sm-8 no_mat">' + data.no_mat + ' - ' + data.nama_mat + '</div>';
        output += '					</div>';
        output += '					<div class="form-group row" style="margin-bottom:5px;">';
        output += '						<div class="col-sm-4"><strong>SPK Qty</strong></div>';
        output += '						<div class="col-sm-4">';
        output += '							<div class="input-group col-md-12">';
        output += '								<input type="number" class="form-control" name="qty" id="qty" min="1" max="' + data.qty_ord_left + '" value="' + data.qty_ord_left + '"/>';
        output += '								<input type="hidden" name="uom" value="' + data.uom + '"/>';
        output += '								<div class="input-group-addon uom">' + data.uom + '</div>';
        output += '							</div>';
        output += '						</div>';
        output += '					</div>';
        output += '					<div class="form-group row" style="margin-bottom:5px;" id="form-buat-spk">';
        output += '						<div class="col-sm-4"><strong>Jadwal Produksi</strong></div>';
        output += '						<div class="col-sm-7">';
        output += '							<div class="input-group col-md-12 date" data-js="datepicker">';
        output += '								<input class="form-control tgl_awal_akhir" readonly type="text" name="start" id="start" required>';
        output += '								<label class="input-group-addon" for="tanggal-awal_filter">-</label>';
        output += '								<input class="form-control tgl_awal_akhir" readonly type="text" name="end" id="end" required>';
        output += '								<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
        output += '							</div>';
        output += '						</div>';
        output += '					</div>';
        output += '					<div class="form-group row" style="margin-bottom:5px;">';
        output += '						<div class="col-sm-4"><strong>Production in House</strong></div>';
        output += '						<div class="col-sm-4">';
        output += '							<label class="control-label">';
        output += '								<input type="checkbox" class="kiranaCheckbox isHouse" checked>';
        output += '								<span>Ya</span>';
        output += '								<input type="hidden" name="isHouse" value="1">';
        output += '							</label>';
        output += '						</div>';
        output += '					</div>';
        output += '				</div>';
        output += '				<div class="col-sm-4">';
        output += '					<div class="form-group row" style="margin-bottom:5px;">';
        output += '						<div class="col-sm-7"><strong>Req Delivery Date</strong></div>';
        output += '						<div class="col-sm-5" id="tanggal_req_delivery">' + data.detail.tanggal_req_delivery + '</div>';
        output += '					</div>';
        output += '					<div class="form-group row" style="margin-bottom:5px;">';
        output += '						<div class="col-sm-7"><strong>Plan Delivery Date</strong></div>';
        output += '						<div class="col-sm-5" id="tanggal_plan_delivery">' + data.tanggal_plan_delivery + '</div>';
        output += '					</div>';
        output += '				</div>';
        output += '			</div>';
    }

    if ($(btn).attr("data-action") == 'booked') {
        output += '			<div class="row action-booked">';
        output += '				<div class="col-sm-8">';
        output += '					<div class="form-group">';
        output += '						<div class="col-sm-3"><strong>Nomor SO</strong></div>';
        output += '						<div class="col-sm-9" id="no_so_po">' + data.no_so + '</div>';
        output += '					</div>';
        output += '					<div class="form-group">';
        output += '						<div class="col-sm-3"><strong>Pabrik Pemesan</strong></div>';
        output += '						<div class="col-sm-9" id="plant">' + data.header.plant + '</div>';
        output += '					</div>';
        output += '					<div class="form-group">';
        output += '						<div class="col-sm-3"><strong>Material Number</strong></div>';
        output += '						<div class="col-sm-9 no_mat">' + data.no_mat + ' - ' + data.nama_mat + '</div>';
        output += '					</div>';
        output += '					<div class="form-group">';
        output += '						<div class="col-sm-3"><strong>Order Qty</strong></div>';
        output += '						<div class="col-sm-4">';
        output += '							<div class="input-group">';
        output += '								<input type="number" class="form-control" name="qty" id="qty" min="1" max="' + data.qty_ord_left + '" value="' + data.qty_ord_left + '"/>';
        output += '								<input type="hidden" name="uom" value="' + data.uom + '"/>';
        output += '								<div class="input-group-addon uom">' + data.uom + '</div>';
        output += '							</div>';
        output += '						</div>';
        output += '					</div>';
        output += '					<div class="clearfix" style="margin-bottom:5px;"></div>';
        output += '					<div class="form-group">';
        output += '						<div class="col-sm-3"><strong>Free Stock</strong></div>';
        output += '						<div class="col-sm-4">';
        output += '							<div class="input-group">';
        output += '								<input type="text" class="form-control" name="qty" id="qty" value="' + data.qty_stock + '" readonly/>';
        output += '								<div class="input-group-addon uom">' + data.uom + '</div>';
        output += '							</div>';
        output += '						</div>';
        output += '					</div>';
        output += '				</div>';
        output += '			</div>';
        output += '		</form>';
    }

    output += '		<div class="row action-history">';
    output += '			<div class="col-md-12">';
    output += '				<div class="form-group">';
    output += '					<div class="col-sm-3"><strong>Nomor SO</strong></div>';
    output += '					<div class="col-sm-9" id="no_so_po">' + data.no_so + '</div>';
    output += '				</div>';
    output += '				<div class="form-group">';
    output += '					<div class="col-sm-3"><strong>Pabrik Pemesan</strong></div>';
    output += '					<div class="col-sm-9" id="plant">' + data.header.plant + '</div>';
    output += '				</div>';
    output += '				<div class="form-group">';
    output += '					<div class="col-sm-3"><strong>Material Number</strong></div>';
    output += '					<div class="col-sm-9 no_mat">' + data.no_mat + ' - ' + data.nama_mat + '</div>';
    output += '				</div>';
    output += '			</div>';
    output += '		</div>';

    output += '	</div>';
    output += '</div>';
    output += '<div class="row wrapper-row-modal">';
    output += '	<div class="col-sm-12">';
    output += '		<fieldset class="fieldset-success">';
    output += '			<legend class="legend-lable" style="font-size:15px;"><strong>Component Overview</strong></legend>';
    output += '			<div class="row action-spk">';
    output += '				<div class="col-md-12">';
    output += '					<div class="table-bom-spk">';
    output += '					<table class="table table-responsive table-bordered" id="table-bom-spk">';
    output += '						<thead>';
    output += '							<th>Item</th>';
    output += '							<th>Component</th>';
    output += '							<th>Description</th>';
    output += '							<th>Reqmt Qty</th>';
    output += '							<th>Stock</th>';
    output += '							<th>Uom</th>';
    output += '						</thead>';
    output += '						<tbody id="tbody-bom-spk">';
    output += '						</tbody>';
    output += '					</table>';
    output += '					</div>';
    output += '				</div>';
    output += '			</div>';

    output += '			<div class="row action-history">';
    output += '				<div class="col-md-12">';
    output += '					<div class="table-history-spk">';
    output += '					<table class="table table-responsive table-bordered" id="table-history-spk">';
    output += '						<thead>';
    output += '							<th>Doc From</th>';
    output += '							<th>Doc Number</th>';
    output += '							<th>Date</th>';
    output += '							<th>Qty</th>';
    output += '							<th></th>';
    output += '						</thead>';
    output += '						<tbody>';
    output += '						</tbody>';
    output += '						<tfoot>';
    output += '							<th colspan="3" class="text-right">Total</th>';
    output += '							<th class="text-center" id="total">' + (data.qty_spk) + '</th>';
    output += '							<th class="uom">' + data.uom + '</th>';
    output += '						</tfoot>';
    output += '					</table>';
    output += '					</div>';
    output += '				</div>';
    output += '			</div>';

    output += '			<div class="row action-booked">';
    output += '				<div class="col-md-12">';
    output += '					<table class="table table-responsive table-bordered" id="table-booked">';
    output += '						<thead>';
    output += '							<th class="text-center">Plant</th>';
    output += '							<th class="text-center">SO Number</th>';
    output += '							<th class="text-center">Order Qty</th>';
    output += '							<th class="text-center"></th>';
    output += '						</thead>';
    output += '						<tbody id="tbody-booked">';
    output += '						</tbody>';
    output += '						<tfoot>';
    output += '							<th colspan="2" class="text-right">Total</th>';
    output += '							<th class="text-center" id="th_totalqty"></th>';
    output += '							<th class="uom">' + data.uom + '</th>';
    output += '						</thead>';
    output += '					</table>';
    output += '				</div>';
    output += '			</div>';
    output += '		</fieldset>';
    output += '	</div>';
    output += '</div>';

    return output;
}

function get_data_bom(matnr) {
    var output = '';
    var qty = $("#qty").val();
    if (qty == "") {
        qty = 0;
    }

    $.ajax({
        url: baseURL + 'nusira/monitoring/get/item_bom',
        type: 'POST',
        dataType: 'JSON',
        data: {
            matnr: matnr
        },
        success: function (data) {
            if ($.isEmptyObject(data) === false) {
                $("#tbody-bom-spk").html("");
                if ($.trim(data)) {
                    let table = $("#KiranaModals #table-bom-spk").DataTable();

                    $.each(data, function (i, v) {
                        var myRow = table.row.add([
                            v.SPOSN == "" ? "" : parseFloat(v.SPOSN),
                            v.IDNRK,
                            v.MAKTX,
                            v.KMPMG == "" ? "" : parseFloat(v.KMPMG) * parseFloat(qty),
                            numberWithCommas(v.KALAB),
                            v.KMPME
                        ]).draw().node();

                        $(myRow).find('td').eq(0).addClass('text-center');
                        $(myRow).find('td').eq(1).addClass('text-center');
                        $(myRow).find('td').eq(2).addClass('text-left');
                        $(myRow).find('td').eq(3).addClass('text-right');
                        $(myRow).find('td').eq(4).addClass('text-right');
                        $(myRow).find('td').eq(5).addClass('text-center');
                    });
                } else {
                    $("#KiranaModals #table-bom-spk").DataTable().clear();
                }
            } else {
                kiranaAlert("notOK", "BOM Material " + matnr + " tidak ditemukan di SAP", "error", "no");
                $("#KiranaModals #table-bom-spk").DataTable().clear();
            }
        },
        complete: function () {
            adjustDatatableWidth();
        }
    });

    output += '<div class="row wrapper-row-modal">';
    output += '	<div class="col-sm-12">';
    output += '		<form class="form-horizontal" id="form-buat-spk">';

    return output;
}