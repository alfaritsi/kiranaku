let datafilter = null;

$(document).ready(function () {
	$('.filter').trigger("click");

	datatables_report_dashboard();

	generate_data_dashboard();

	setInterval(function () {
		$('#report_dashboard').DataTable().ajax.reload();
		generate_data_dashboard();
	}, 60000);

	$(document).on("click", ".btn_detail_pi", function () {
		$('#KiranaModals .modal-dialog').addClass("modal-lg");
		var output = "";
		output += '<div class="row">';
		output += '		<div class="col-sm-12">';
		output += '			<table class="table table-bordered table-striped" id="sspTable">';
		output += '				<thead>';
		output += '					<th>Plant</th>';
		output += '					<th>No PI</th>';
		output += '					<th>Perihal</th>';
		output += '					<th>Tanggal</th>';
		output += '					<th>Status PI</th>';
		output += '					<th>Plan Delivery Date</th>';
		output += '				</thead>';
		output += '				<tbody>';
		output += '				</tbody>';
		output += '			</table>';
		output += '		</div>';
		output += '</div>';
		show_modal("List PI Baru", output);
		datatables_pi();
	});

	$(document).on("click", ".btn_so_spk", function () {
		// alert();
		$('#KiranaModals .modal-dialog').addClass("modal-lg");
		var output = "";
		output += '<div class="row">';
		output += '		<div class="col-sm-12">';
		output += '			<table class="table table-bordered table-striped" id="sspTable">';
		output += '				<thead>';
		output += '					<th>No SO</th>';
		output += '					<th>Pabrik Pemesan</th>';
		output += '					<th>No PO</th>';
		output += '					<th>No PI</th>';
		output += '					<th>Tanggal PO</th>';
		output += '					<th>Status</th>';
		output += '				</thead>';
		output += '				<tbody>';
		output += '				</tbody>';
		output += '			</table>';
		output += '		</div>';
		output += '</div>';
		show_modal("List SO Dengan SPK Belum Lengkap", output);
		datatables_so();
	});

	$(document).on("click", ".btn_spk_late", function () {
		// alert();
		$('#KiranaModals .modal-dialog').addClass("modal-lg");
		var output = "";
		output += '<div class="row">';
		output += '		<div class="col-sm-12">';
		output += '			<table class="table table-bordered table-striped" id="sspTable">';
		output += '				<thead>';
		output += '					<th>Pabrik Pemesan</th>';
		output += '					<th>No SO</th>';
		output += '					<th>No PO</th>';
		output += '					<th>No PI</th>';
		output += '					<th>Material</th>';
		output += '					<th>Qty</th>';
		output += '					<th>Overdue<br>(hari)</th>';
		output += '				</thead>';
		output += '				<tbody>';
		output += '				</tbody>';
		output += '			</table>';
		output += '		</div>';
		output += '</div>';
		show_modal("List SPK Terlambat", output);
		datatables_spk();
	});

	$(document).on('click', '.item-detail', function () {
		let no_io = $(this).data("io");

		let dataTable = $('#report_dashboard').DataTable();
		let tr = $(this);
		let row = dataTable.row(tr);

		if (row.child.isShown()) {
			$(this).css('background-color', '');
			row.child.hide();
			adjustDatatableWidth();
		} else {
			if (no_io) {
				$.ajax({
					url: baseURL + 'nusira/dashboard/get/report_dashboard_detail',
					type: 'POST',
					dataType: 'JSON',
					data: {
						no_io: no_io
					},
					beforeSend: function () {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("body .overlay-wrapper").append(overlay);
					},
					success: function (response) {
						var data = response.data;
						if ($.isEmptyObject(data) === false) {
							var output = "";
							output += '<div id="table-items-' + no_io + '">';
							output += '		<div class="box box-info" style="margin-top:10px; max-width: max-content;">';
							output += '			<div class="box-header with-border">';
							output += '				<h3 class="box-title">Component Overview</h3>';
							output += '			</div>';
							output += '			<div class="box-body no-padding">';
							output += '				<table class="table table-bordered table-so-detail">';
							output += '					<thead>';
							output += '						<th>Component</th>';
							output += '						<th>Descriptiom</th>';
							output += '						<th>UOM</th>';
							output += '						<th>GI Qty</th>';
							output += '						<th>GI Number</th>';
							output += '						<th>GI Date</th>';
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
							kiranaAlert("notOK", "Nomor IO " + no_io + " tidak ditemukan di SAP", "error", "no");
						}
					},
					complete: function () {
						$("body .overlay-wrapper .overlay").remove();
						adjustDatatableWidth();
					}
				});
			}
		}
	});

	$(document).on("click", ".filter", function () {
		var check = this.value;
		var checkbox = this.checked;
		var chk_arr = document.getElementsByName("filter[]");
		var chklength = chk_arr.length;
		var filter_arr = [];

		for (k = 0; k < chklength; k++) {
			if (chk_arr[k].checked === true) {
				filter_arr.push(chk_arr[k].value);
			}
		}
		datafilter = filter_arr;
		datatables_report_dashboard();
		generate_data_dashboard();
	});

});

function generate_detail(data) {
	var output = "";
	$.each(data, function (i, v) {
		output += '<tr>';
		output += '	<td class="text-center">' + v.material + '</td>';
		output += '	<td class="text-left">' + v.MAKTX + '</td>';
		output += '	<td class="text-left">' + v.entry_uom + '</td>';
		output += '	<td class="text-center">' + parseInt(v.entry_qnt) + '</td>';
		output += '	<td class="text-center">' + v.mat_doc + '</td>';
		output += '	<td class="text-center">' + v.pstng_date + '</td>';
		output += '</tr>';
	});
	return output;
}

function show_modal(title, content) {
	$('#KiranaModals .modal-title').html(title);
	$('#KiranaModals .modal-body').html(content);

	$('#KiranaModals').modal({
		backdrop: 'static',
		keyboard: true,
		show: true
	});
}

function datatables_so() {
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
	let date = new Date();

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
			url: baseURL + '/nusira/monitoring/get/so',
			type: 'POST',
			data: {
				status: 'tidak lengkap',
				tanggal_awal: date.getFullYear() + "0101"
			},
			error: function (a, b, c) {
				console.log(a);
				console.log(b);
				console.log(c);
			},
			complete: function () {
				setTimeout(function () {
					adjustDatatableWidth();
				}, 1500);
			}
		},
		columns: [
			{
				"data": "no_so",
				"name": "no_so",
				"width": "5%",
				"render": function (data, type, row) {
					return row.no_so;
				}
			},
			{
				"data": "plant",
				"name": "plant",
				"width": "5%",
				"render": function (data, type, row) {
					return row.plant;
				}
			},
			{
				"data": "no_po",
				"name": "no_po",
				"width": "5%",
				"render": function (data, type, row) {
					return row.no_po ? row.no_po : '-';
				}
			},
			{
				"data": "no_pi",
				"name": "no_pi",
				"width": "5%",
				"render": function (data, type, row) {
					return row.no_pi ? row.no_pi : '-';
				}
			},
			{
				"data": "tanggal",
				"name": "tanggal",
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
		}
	});
}

function datatables_pi() {
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
			url: baseURL + 'nusira/dashboard/get/pi_pagination',
			type: 'POST',
			error: function (a, b, c) {
				console.log(a);
				console.log(b);
				console.log(c);
			},
			complete: function () {
				setTimeout(function () {
					adjustDatatableWidth();
				}, 1500);
			}
		},
		columns: [
			{
				"data": "plant",
				"name": "plant",
				"width": "5%",
				"render": function (data, type, row) {
					return row.plant;
				}
			},
			{
				"data": "no_pi",
				"name": "no_pi",
				"width": "8%",
				"render": function (data, type, row) {
					return row.no_pi;
				}
			},
			{
				"data": "perihal",
				"name": "perihal",
				"orderable": false,
				"width": "15%",
				"render": function (data, type, row) {
					return row.perihal;
				}
			},
			{
				"data": "tanggal",
				"name": "tanggal",
				"width": "5%",
				"render": function (data, type, row) {
					return row.tanggal;
				}
			},
			{
				"data": "view_status",
				"name": "view_status",
				"width": "10%",
				"render": function (data, type, row) {
					if ((row.status == 'finish') || (row.status == 'drop')) {
						return row.view_status;
					} else if (row.status == 'deleted') {
						return row.view_status + '<br><small>oleh ' + row.status_pi_delete + '</small>';
					} else {
						return row.view_status + '<br><small>Sedang diproses di ' + row.status_pi + '</small>';
					}
				}
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
				}
			},
		],
		rowCallback: function (row, data, iDisplayIndex) {
			var info = this.fnPagingInfo();
			var page = info.iPage;
			var length = info.iLength;
			$('td:eq(0)', row).html();
		}
	});
}

function datatables_spk() {
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
		order: [[6, 'desc']],
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
			url: baseURL + 'nusira/dashboard/get/spk_late_pagination',
			type: 'POST',
			data: {
				filter: datafilter
			},
			error: function (a, b, c) {
				console.log(a);
				console.log(b);
				console.log(c);
			},
			complete: function () {
				setTimeout(function () {
					adjustDatatableWidth();
				}, 1500);
			}
		},
		columns: [
			{
				"data": "plant",
				"width": "5%",
				"render": function (data, type, row) {
					return row.plant;
				}
			},
			{
				"data": "no_so",
				"width": "8%",
				"render": function (data, type, row) {
					return row.no_so;
				}
			},
			{
				"data": "no_po",
				"width": "8%",
				"render": function (data, type, row) {
					return row.no_po;
				}
			},
			{
				"data": "no_pi",
				"width": "8%",
				"render": function (data, type, row) {
					return row.no_pi;
				}
			},
			{
				"data": "deskripsi",
				"width": "8%",
				"render": function (data, type, row) {
					return row.deskripsi;
				}
			},
			{
				"data": "prod_qty",
				"width": "8%",
				"render": function (data, type, row) {
					return row.prod_qty + ' ' + row.uom;
				}
			},
			{
				"data": "overdue",
				"width": "8%",
				"render": function (data, type, row) {
					return row.overdue;
				}
			},
		],
		rowCallback: function (row, data, iDisplayIndex) {
			var info = this.fnPagingInfo();
			var page = info.iPage;
			var length = info.iLength;
			$('td:eq(0)', row).html();
		}
	});
}

function datatables_report_dashboard() {
	$('#report_dashboard').DataTable().clear().destroy();

	var chk_arr = document.getElementsByName("filter[]");
	var chklength = chk_arr.length;
	var filter_arr = [];
	var checkbox = false;

	for (k = 0; k < chklength; k++) {
		if (chk_arr[k].checked === true) {
			checkbox = true;
		}
	}

	if (checkbox === false) {
		kiranaAlert("OK", "Tidak ada data yang dipilih", "warning");
	}

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
	var table = $("#report_dashboard").dataTable({
		ordering: true,
		order: [[9, 'asc'], [3, 'asc'], [1, 'asc'], [4, 'asc']],
		scrollCollapse: true,
		scrollY: false,
		scrollX: true,
		bautoWidth: false,
		initComplete: function () {
			var api = this.api();
			$('#report_dashboard input')
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
			url: baseURL + 'nusira/dashboard/get/report_dashboard',
			type: 'POST',
			data: {
				filter: datafilter
			},
			error: function (a, b, c) {
				console.log(a);
				console.log(b);
				console.log(c);
			},
			complete: function () {
				setTimeout(function () {
					adjustDatatableWidth();
				}, 1500);
			}
		},
		columns: [
			{
				"data": "no_pi",
				"width": "5%",
				"render": function (data, type, row) {
					return row.no_pi;
				}
			},
			{
				"data": "no_so",
				"width": "5%",
				"render": function (data, type, row) {
					return row.no_so;
				}
			},
			{
				"data": "req_deliv_date",
				"width": "5%",
				"render": function (data, type, row) {
					return generateDateFormat(row.req_deliv_date);
				}
			},
			{
				"data": "plan_deliv_date",
				"width": "5%",
				"render": function (data, type, row) {
					return generateDateFormat(row.plan_deliv_date);
				}
			},
			{
				"data": "no_io",
				"width": "5%",
				"render": function (data, type, row) {
					return row.no_io;
				}
			},
			{
				"data": "no_mat",
				"width": "5%",
				"render": function (data, type, row) {
					return row.no_mat;
				}
			},
			{
				"data": "prod_qty",
				"width": "5%",
				"className": "text-right",
				"render": function (data, type, row) {
					return row.prod_qty;
				}
			},
			{
				"data": "mat_doc",
				"width": "5%",
				"render": function (data, type, row) {
					return row.mat_doc;
				}
			},
			{
				"data": "pstng_date",
				"width": "5%",
				"render": function (data, type, row) {
					return generateDateFormat(row.pstng_date);
				}
			},
			{
				"data": "mat_doc",
				"width": "5%",
				"render": function (data, type, row) {
					// console.log(row.mat_doc+'=>'+row.no_io);
					var status = ''
					if (row.mat_doc == null && row.no_io)
						status = '<span class="label label-warning">ON PROGRESS</span>';
					else if (row.mat_doc && row.no_io)
						status = '<span class="label label-success">FINISH</span>';
					else if (row.mat_doc == null && row.no_io == null)
						status = '<span class="label label-danger">BELUM ADA SPK</span>';

					return status;//(row.mat_doc ? '<span class="label label-success">FINISH</span>' : '<span class="label label-warning">ON PROGRESS</span>');
				}
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
			$(row).attr("data-io", data.no_io);
		}
	});
}

function generate_data_dashboard() {
	let date = new Date();

	//pi baru
	$.ajax({
		url: baseURL + '/nusira/dashboard/get/pi',
		type: 'POST',
		dataType: 'JSON',
		beforeSend: function () {
			$(".box-body .col-lg-4").eq(0).find("h3").html("<i class='fa fa-refresh fa-spin'></i>");
		},
		success: function (data) {
			if (data) {
				$(".box-body .col-lg-4").eq(0).find("h3").html(data.length);
			}
		},
		error: function () {
			$(".box-body .col-lg-4").eq(0).find("h3").html(0);
		}
	});

	//sales order
	$.ajax({
		url: baseURL + '/nusira/monitoring/get/count_so',
		type: 'POST',
		dataType: 'JSON',
		data: {
			status: 'tidak lengkap',
			tanggal_awal: date.getFullYear() + "0101"
		},
		beforeSend: function () {
			$(".box-body .col-lg-4").eq(1).find("h3").html("<i class='fa fa-refresh fa-spin'></i>");
		},
		success: function (data) {
			if (data) {
				$(".box-body .col-lg-4").eq(1).find("h3").html(data.count);
			}
		},
		error: function () {
			$(".box-body .col-lg-4").eq(1).find("h3").html(0);
		}
	});

	//spk terlambat
	$.ajax({
		url: baseURL + '/nusira/dashboard/get/spk_late',
		type: 'POST',
		dataType: 'JSON',
		data: {
			filter: datafilter
		},
		beforeSend: function () {
			$(".box-body .col-lg-4").eq(2).find("h3").html("<i class='fa fa-refresh fa-spin'></i>");
		},
		success: function (data) {
			if (data) {
				$(".box-body .col-lg-4").eq(2).find("h3").html(data.length);
			}
		},
		error: function () {
			$(".box-body .col-lg-4").eq(2).find("h3").html(0);
		}
	});
}
