$(document).ready(function () {
	let dataMTS = null;

	get_data_mts();
	get_limit_date();

	$(".select2").select2({dropdownParent: $("#KiranaModals")});

	$(document).on("change", "#status", function () {
		get_data_mts();
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
		get_data_mts();
	});

	$(document).on('click', '.mts-detail', function () {
		var action = $(this).data("action");
		// let data = JSON.parse($(this).attr("data-mts").replace(/;;;/g, '"'));
		// let data = $(this).data("mts").replace(/;;;/g, '"');
		// dataMTS = data;
		// console.log(dataMTS);
		var no_io = $(this).data("no_io");
		var qty = $(this).data("qty");
		var uom = $(this).data("uom");
		var no_mat = $(this).data("no_mat");
		var nama_mat = $(this).data("nama_mat");
		var start_date = $(this).data("start_date");
		var end_date = $(this).data("end_date");

		// $("#KiranaModals .modal-title").html("Surat Perintah Kerja (MTS)");
		$('#KiranaModals .modal-dialog').addClass("modal-lg");

		var output = '';
		output += generate_modal_spk();

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
		footer += '	</div>';
		footer += '</div>';

		if (output !== "") {
			$("#KiranaModals .modal-footer").remove();

			$('#KiranaModals .modal-body').html(output);
			$('#KiranaModals .modal-content').append(footer);
			// $('#KiranaModals #form-buat-spk, .buat-spk').addClass('hide');
			// $('#KiranaModals .lihat-spk').removeClass('hide');

			if (action == "buat") {
				$("#KiranaModals .modal-title").html("Make To Stock");
				$('#KiranaModals #form-buat-spk').removeClass('hide');
				$('#KiranaModals .lihat-spk').addClass('hide');
				$('#KiranaModals .buat-spk').removeClass('hide');
			} else if (action == "lihat") {
				$("#KiranaModals .modal-title").html("Surat Perintah Kerja (MTS) - " + no_io);
				$("#KiranaModals .material").append("<option value='"+no_mat+"'> "+no_mat+" - "+nama_mat+"</option>"); 
				// $("#KiranaModals #material").val(no_mat);
				$("#KiranaModals #qty").val(qty);
				$("#KiranaModals .uom").val(uom);
				$("#KiranaModals #start").val(start_date);
				$("#KiranaModals #end").val(end_date);
				$('#KiranaModals #form-buat-spk').removeClass('hide');
				$('#KiranaModals .lihat-spk').removeClass('hide');
				$('#KiranaModals .buat-spk').addClass('hide');
				$("*", "#form-buat-spk").prop('disabled',true);		
				get_data_so(no_mat);
				get_data_bom(no_mat);							
			} else {
				window.open(baseURL + 'nusira/monitoring/cetak/mts/'+ no_io, '_blank');
				return false;
			}


			// $(".select2").select2({dropdownParent: $("#KiranaModals")});

			// $.ajax({
			// 	url: baseURL + 'nusira/monitoring/get/item_material',
			// 	type: 'POST',
			// 	dataType: 'JSON',
			// 	beforeSend: function () {
			// 		var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
			// 		$("body .overlay-wrapper").append(overlay);
			// 	},
			// 	success: function (data) {
			// 		if ($.isEmptyObject(data) === false) {
	  //               	$("#material").html("<option></option>");  
			// 			$.each(data, function (i, v) {
		 //                	$("#material").append("<option value='"+v.MATNR+"' data-nama_mat='"+v.MAKTX+"' data-uom='"+v.MEINS+"'>"+v.MATNR+" - "+v.MAKTX+"</option>");  
			// 			});
			// 			$("#KiranaModals #material").val(no_mat).trigger("change");
			// 			$(".select2").select2({dropdownParent: $("#KiranaModals")});

			// 		} else {
			// 			kiranaAlert("notOK", "Material tidak ditemukan di SAP", "error", "no");
			// 		}
			// 	},
			// 	complete: function () {
			// 		$("body .overlay-wrapper .overlay").remove();
			// 	}
			// });


			$('[data-js=datepicker]').datepicker({
				format: 'dd.mm.yyyy',
				autoclose: true,
				todayHighlight: true,
				weekStart: 1,
				inputs: $('.tgl_awal_akhir')
			});
            var minDateInput = new Date();
            $('.tgl_awal_akhir').datepicker('setStartDate', minDateInput);
			$('#start').on('changeDate', function (e) {
				$('#end').datepicker('setStartDate', e.date);
			});

			// KIRANAKU.showLoading();

			$('#KiranaModals').modal({
				backdrop: 'static',
				keyboard: true,
				show: true
			});

			$("#KiranaModals #table-bom-spk").dataTable();
			$("#KiranaModals #table-qty").dataTable();

			$(".material").select2({
				dropdownParent: $("#KiranaModals"),
				allowClear: true,
				placeholder: {
					id: "",
					placeholder: "Leave blank to ..."
				},
				ajax: {
					url: baseURL + 'nusira/setting/get/bom',
					dataType: 'json',
					delay: 250,
					data: function (params) {
						return {
							q: params.term, // search term
							page: params.page
						};
					},
					processResults: function (data, page) {
						return {
							results: data.items
						};
					},
					cache: false
				},
				escapeMarkup: function (markup) {
					return markup;
				}, // let our custom formatter work
				minimumInputLength: 3,
				templateResult: function (repo) {
					if (repo.loading) return repo.text;
					var markup = '<div class="clearfix">[' + repo.MATNR + '] - ' + repo.MAKTX + '</div>';
					return markup;
				},
				templateSelection: function (repo) {
					if (repo.MATNR && repo.MAKTX) {
						return '[' + repo.MATNR + '] - ' + repo.MAKTX;
					} else {
						return repo.text;
					}
				}
			});

			$("#KiranaModals .material").on('select2:select select2:unselecting change', function (e) {
				$("#KiranaModals #table-bom-spk").DataTable().clear();
				$("#KiranaModals #table-qty").DataTable().clear();
				$("#KiranaModals #table-qty").DataTable().draw();
				$("#KiranaModals #table-bom-spk").DataTable().draw();
				$("#totalorder").html("0");
				$("#freestock").html("0");
				$("#needed_qty").html("0");
				$("#matnr").val("");					
				$("#maktx").val("");					
				$("#uom").val("");

				if (typeof e.params !== "undefined" && e.params.data) {
					var matnr = e.params.data.MATNR;
					var maktx = e.params.data.MAKTX;
					var uom = e.params.data.MEINS;
					get_data_so(matnr);
					get_data_bom(matnr);
					$("#matnr").val(matnr);					
					$("#maktx").val(maktx);					
					$("#uom").val(uom);
				}
				// e.preventDefault();
				// return false;
			});

		}

	});

	$(document).on("keyup", "#qty", function (e) {
		$("#KiranaModals #table-bom-spk").DataTable().clear();
		get_data_bom($("#matnr").val());
		e.preventDefault();
		return false;
	});

	$(document).on("change", "#qty", function (e) {
		$("#KiranaModals #table-bom-spk").DataTable().clear();
		get_data_bom($("#matnr").val());
		e.preventDefault();
		return false;
	});

	$(document).on('click', '.btn-spk', function (e) {
		e.preventDefault();

		let modal = $('#KiranaModals');
		// let data = dataMTS;

		let no_mat = $('#matnr', modal).val();
		let nama_mat = $('#maktx', modal).val();

		let start = $('#start', modal).val();
		let end = $('#end', modal).val();
		let qty = $('#qty', modal).val();
		let uom = $('#uom', modal).val();

		let valid = validate('#form-buat-spk', true);

		if (valid === 0) {
			KIRANAKU.showLoading();
			$.ajax({
				url: baseURL + 'nusira/monitoring/set/spk_mts',
				type: 'POST',
				dataType: 'JSON',
				data: {
					no_mat: no_mat,
					no_pos: "10",
					nama_mat: nama_mat,
					start: start,
					end: end,
					qty: qty,
					uom: uom,
				},
				success: function (data) {
					KIRANAKU.hideLoading();
					if (data.sts == 'OK') {
						kiranaAlert(data.sts, data.msg);
						window.open(baseURL + 'nusira/monitoring/cetak/mts/'+data.no_io, '_blank');
					} else {
						kiranaAlert(data.sts, data.msg, 'error', 'no');
					}
				},
				error: function (data) {
					KIRANAKU.hideLoading();
					kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
				}
			});
		}
		return false;
	});

});

function get_limit_date() {
	var minDate = new Date(regenerateDatetimeFormat($("#tanggal_awal_filter").val(), "DD.MM.YYYY", "YYYY-MM-DD"));
	$('#tanggal_akhir_filter').datepicker('setStartDate', minDate);
	var maxDate = new Date(regenerateDatetimeFormat($('#tanggal_akhir_filter').val(), "DD.MM.YYYY", "YYYY-MM-DD"));
	$('#tanggal_awal_filter').datepicker('setEndDate', maxDate);
}

function get_data_mts() {
	let tanggal_awal_filter = $("#tanggal_awal_filter").val();
	let tanggal_akhir_filter = $("#tanggal_akhir_filter").val();
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
			url: baseURL + 'nusira/monitoring/get/mts',
			type: 'POST',
			data: {
				tanggal_awal: tanggal_awal_filter,
				tanggal_akhir: tanggal_akhir_filter
			},
			error: function (a, b, c) {
				console.log(a);
				console.log(b);
				console.log(c);				
			}
		},
		columns: [
			{
				"data": "no_io",
				"name": "no_io",
				"width": "20%",
				"render": function (data, type, row) {
					return row.no_io;
				}
			},
			{
				"data": "prod_schedule_start",
				"name": "prod_schedule_start",
				"width": "15%",
				"className": "text-center",
				"render": function (data, type, row) {
					return row.prod_schedule_start;
				}
			},
			{
				"data": "prod_schedule_end",
				"name": "prod_schedule_end",
				"width": "15%",
				"className": "text-center",
				"render": function (data, type, row) {
					return row.prod_schedule_end;
				}
			},
			{
				"data": "no_mat",
				"name": "no_mat",
				"align": "center",
				"className": "text-center",
				"render": function (data, type, row) {
					return row.no_mat;
				}
			},
			{
				"data": "MAKTX",
				"name": "MAKTX",
				"render": function (data, type, row) {
					return row.MAKTX;
				}
			},
			{
				"data": "prod_uom",
				"name": "prod_uom",
				"width": "10%",
				"className": "text-center",
				"render": function (data, type, row) {
					return row.prod_uom;
				}
			},
			{
				"data": "prod_qty",
				"name": "prod_qty",
				"width": "5%",
				"className": "text-center",
				"render": function (data, type, row) {
					return row.prod_qty;
				}
			},
			{
				"data": "",
				"name": "",
				"width": "1%",
				"className": "text-center",
				"render": function (data, type, row) {
					return '<div class="input-group-btn">'
						+'		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>'
						+'		<ul class="dropdown-menu pull-right">'
						+'			<li><a href="javascript:void(0)" class="mts-detail" data-no_io="' + row.no_io + '" data-no_mat="' + row.no_mat + '" data-nama_mat="' + row.MAKTX + '" data-qty="' + row.prod_qty + '" data-uom="' + row.prod_uom + '" data-start_date="' + row.prod_schedule_start + '" data-end_date="' + row.prod_schedule_end + '" data-action="lihat"><small><i class="fa fa-bars"></i></small> Detail SPK</a></li>'
						+'			<li><a href="javascript:void(0)" class="mts-detail" data-no_io="' + row.no_io + '" data-no_mat="' + row.no_mat + '" data-nama_mat="' + row.MAKTX + '" data-qty="' + row.prod_qty + '" data-uom="' + row.prod_uom + '" data-start_date="' + row.prod_schedule_start + '" data-end_date="' + row.prod_schedule_end + '" data-action="print"><small><i class="fa fa-print"></i></small> Print SPK</a></li>'
						+'		</ul>'
						+'	</div>';
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
		}
	});
}

function generate_modal_spk() {
	var output = '';
	output += '<div class="row wrapper-row-modal">';
	output += '	<div class="col-sm-12">';
	output += '		<form id="form-buat-spk">';
	// output += '		<fieldset class="fieldset-info">';
	output += '			<div class="row action-spk">';
	output += '				<div class="col-sm-12">';
	output += '					<div class="form-group">';
	output += '						<div class="col-sm-2"><strong>Material</strong></div>';
	output += '						<div class="col-sm-8">';
	output += '							<select class="form-control material"';
	output += '								name="material"';
	output += '								id="material"';
	output += '								required>';
	output += '							</select>';
	output += '							<input type="hidden" name="matnr" id="matnr" value=""/>';
	output += '							<input type="hidden" name="maktx" id="maktx" value=""/>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="clearfix" style="margin-bottom:5px;"></div>';
	output += '					<div class="form-group">';
	output += '						<div class="col-sm-2"><strong>Target Qty</strong></div>';
	// output += '						<div class="col-sm-6 no_mat">' + data.qty_ord_left + ' ' + data.uom + '</div>';
	output += '						<div class="col-sm-4">';
	output += '							<div class="input-group col-md-6">';
	output += '								<input type="number" class="form-control" name="qty" id="qty" min="1" value="1" required/>';
	output += '								<input type="hidden" name="uom" id="uom" value=""/>';
	output += '								<div class="input-group-addon uom"> UNIT</div>';
	output += '							</div>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="clearfix" style="margin-bottom:5px;"></div>';
	output += '					<div class="form-group">';
	output += '						<div class="col-sm-2"><strong>Progress Qty</strong></div>';
	// output += '						<div class="col-sm-6 no_mat">' + data.qty_ord_left + ' ' + data.uom + '</div>';
	output += '						<div class="col-sm-4">';
	output += '							<div class="input-group col-md-6">';
	output += '								<input type="number" class="form-control" name="progress_qty" id="progress_qty" value="0" readonly/>';
	output += '								<input type="hidden" name="progress_uom" id="progress_uom" value=""/>';
	output += '								<div class="input-group-addon uom"> UNIT</div>';
	output += '							</div>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="clearfix" style="margin-bottom:5px;"></div>';
	output += '					<div class="form-group" id="form-buat-spk">';
	output += '						<div class="col-sm-2"><strong>Jadwal Produksi</strong></div>';
	output += '						<div class="col-sm-4">';
	output += '							<div class="input-group col-md-12 date" data-js="datepicker">';
	output += '								<input class="form-control tgl_awal_akhir" readonly type="text" name="start" id="start" required>';
	output += '								<label class="input-group-addon" for="tanggal-awal_filter">-</label>';
	output += '								<input class="form-control tgl_awal_akhir" readonly type="text" name="end" id="end" required>';
	output += '								<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
	output += '							</div>';
	output += '						</div>';
	output += '					</div>';
	output += '				</div>';
	output += '			</div>';
	// output += '		</fieldset>';
	output += '		</form>';
	output += '	</div>';
	output += '</div>';
	output += '<div class="row wrapper-row-modal">';
	output += '	<div class="col-sm-12">';

	output += '		<fieldset class="fieldset-success">';
	output += '			<legend class="legend-lable" style="font-size:15px;"><strong>Overview</strong></legend>';
	output += '			<div class="row">';
	output += '				<div class="col-md-12">';
	output += '					<div class="nav-tabs-custom">';
	output += '						<ul class="nav nav-tabs">';
	output += '							<li class="active"><a href="#bom" data-toggle="tab">Component</a></li>';
	output += '							<li><a href="#demand" data-toggle="tab">Demand</a></li>';
	output += '						</ul">';
	output += '						<div class="tab-content">';
	output += '							<div class="tab-pane active" id="bom">';
	output += '								<div class="">';
	output += '									<div class="col-md-12" style="margin-top:20px;">';
	output += '										<table class="table table-responsive table-bordered table-striped" id="table-bom-spk">';
	output += '											<thead>';
	output += '												<tr>';
	output += '													<th>Item</th>';
	output += '													<th>Component</th>';
	output += '													<th>Description</th>';
	output += '													<th>Reqmt Qty</th>';
	output += '													<th>Stock</th>';
	output += '													<th>Uom</th>';
	output += '												</tr>';
	output += '											</thead>';
	output += '											<tbody id="tbody-bom-spk">';
	output += '											</tbody>';
	output += '										</table>';
	output += '									</div>';
	output += '								</div>';
	output += '							</div>';
	output += '							<div class="tab-pane" id="demand">';
	output += '								<div class="">';
	output += '									<div class="col-md-12" style="margin-top:20px;">';
	output += '										<table class="table table-responsive table-bordered table-striped" id="table-qty">';
	output += '											<thead>';
	output += '												<tr>';
	output += '													<th class="text-center">SO Number</th>';
	output += '													<th class="text-center">Pabrik Pemesan</th>';
	output += '													<th class="text-center">Order Qty</th>';
	output += '												</tr>';
	output += '											</thead>';
	output += '											<tbody id="tbody-qty">';
	output += '											</tbody>';
	output += '											<tfoot>';
	output += '												<tr>';
	output += '													<th colspan="2" class="text-right">Total Order</th>';
	output += '													<th class="text-center" id="totalorder">0</th>';
	output += '												</tr>';
	output += '												<tr>';
	output += '													<th colspan="2" class="text-right">Booked Free Stock</th>';
	output += '													<th class="text-center" id="freestock">0</th>';
	output += '												</tr>';
	output += '												<tr>';
	output += '													<th colspan="2" class="text-right">Needed Qty</th>';
	output += '													<th class="text-center" id="needed_qty">0</th>';
	output += '												</tr>';
	output += '											</tfoot>';
	output += '										</table>';
	output += '									</div>';
	output += '								</div>';
	output += '							</div>';
	output += '						</div>';
	output += '					</div>';
	output += '				</div>';
	output += '			</div>';
	output += '		</fieldset>';

	output += '	</div>';
	output += '</div>';

	return output;
}

function get_data_so(matnr) {
	var output = '';
	// var matnr = $("#material").val();
	var qty = $("#qty").val();
	if(qty == ""){qty =0;}
	var stock = 0;
	var totalorder = 0;

	$.ajax({
		url: baseURL + 'nusira/monitoring/get/mts_progress',
		type: 'POST',
		dataType: 'JSON',
		data: {
			matnr: matnr
		},
		beforeSend: function () {
			var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
			$("body .overlay-wrapper").append(overlay);
		},
		success: function (data) {
			$.each(data, function (i, v) {
				$("#progress_qty").val(v.tot_qty);
			});
		},
		complete: function () {
			$("body .overlay-wrapper .overlay").remove();
			adjustDatatableWidth();
		}
	});

	$.ajax({
		url: baseURL + 'nusira/monitoring/get/list_demand',
		type: 'POST',
		dataType: 'JSON',
		data: {
			no_mat: matnr
		},
		beforeSend: function () {
			var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
			$("body .overlay-wrapper").append(overlay);
		},
		success: function (data) {
			if (data.sts == 'OK') {
				$("#KiranaModals #table-qty").DataTable().clear();
				$("#KiranaModals #table-qty").DataTable().draw();
				var totalorder = 0;		
				var needed_qty = 0;		
				var needed_qty = 0;		
				if ($.trim(data.data)) {
					let table = $("#KiranaModals #table-qty").DataTable();

					$.each(data.data, function (i, v) {
						var myRow = table.row.add([
							v.no_so,
							v.plant,
							parseInt(v.qty)
						]).draw().node();

						$( myRow ).find('td').eq(0).addClass('text-center');
						$( myRow ).find('td').eq(1).addClass('text-center');
						$( myRow ).find('td').eq(2).addClass('text-center');
						totalorder = parseInt(totalorder) + parseInt(v.qty);
					});					
				} else {
					$("#KiranaModals #table-qty").DataTable().clear();
					$("#KiranaModals #table-qty").DataTable().draw();
				}
				$("#totalorder").html(parseInt(totalorder));
				$("#freestock").html(parseInt(stock));
				$("#needed_qty").html(parseInt(totalorder) - parseInt(stock));				
			} else {
				kiranaAlert(data.sts, data.msg, 'error', 'no');
			}
		},
		complete: function () {
			$("body .overlay-wrapper .overlay").remove();
			adjustDatatableWidth();
		}
	});

	// output += '<div class="row wrapper-row-modal">';
	// output += '	<div class="col-sm-12">';
	// output += '		<form class="form-horizontal" id="form-buat-spk">';

	return output;

}

function get_data_bom(matnr) {
	// var matnr = $("#material").val();
	var output = '';
	var qty = $("#qty").val();
	if(qty == ""){qty =0;}

	$.ajax({
		url: baseURL + 'nusira/monitoring/get/item_bom',
		type: 'POST',
		dataType: 'JSON',
		data: {
			matnr: matnr
		},
		beforeSend: function () {
			var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
			$("body .overlay-wrapper").append(overlay);
		},
		success: function (data) {
			if ($.isEmptyObject(data) === false) {
				$("#tbody-bom-spk").html("");
				if ($.trim(data)) {				
					let table = $("#KiranaModals #table-bom-spk").DataTable();

					$.each(data, function (i, v) {
						var myRow = table.row.add([
                            v.SPOSN ? parseFloat(v.SPOSN) : '',
							v.IDNRK,
							v.MAKTX,
                            v.KMPMG ? parseFloat(v.KMPMG) * parseFloat(qty) : '',
							v.KALAB,
							v.KMPME
						]).draw().node();

						$( myRow ).find('td').eq(0).addClass('text-center');
						$( myRow ).find('td').eq(1).addClass('text-center');
						$( myRow ).find('td').eq(2).addClass('text-left');
						$( myRow ).find('td').eq(3).addClass('text-right');
						$( myRow ).find('td').eq(4).addClass('text-right');
						$( myRow ).find('td').eq(5).addClass('text-center');
					});
				} else {
					$("#KiranaModals #table-bom-spk").DataTable().clear()
				}
			} else {
				kiranaAlert("notOK", "BOM Material " + matnr + " tidak ditemukan di SAP", "error", "no");
				$("#KiranaModals #table-bom-spk").DataTable().clear()
			}
		},
		complete: function () {
			$("body .overlay-wrapper .overlay").remove();
			adjustDatatableWidth();
		}
	});

	return output;
}

