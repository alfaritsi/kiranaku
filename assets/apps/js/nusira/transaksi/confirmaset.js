$(document).ready(function () {
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

	validator = $('.form-transaksi-confirmaset').validate({
		errorElement: "em",
		errorPlacement: function (error, element) {
			// Add the `help-block` class to the error element
			error.addClass("help-block");

			if (element.prop("type") === "checkbox") {
				error.insertAfter(element.parent("label"));
			} else {
				error.appendTo(element.parents('.form-group > div'));
			}
		},
		highlight: function (element, errorClass, validClass) {
			$(element).parents(".form-group > div").addClass("has-error").removeClass("has-success");
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).parents(".form-group > div").addClass("has-success").removeClass("has-error");
		}
	});

	datatables_ssp();

	//=======FILTER=======//
	$(document).on("change", "#plant_filter, #tujuan_filter, #status_filter", function () {
		datatables_ssp();
	});

	$(document).on('change', '.acc_assign:not([disabled]),.asset_class:not([disabled]),.cost_center:not([disabled]),.gl_account:not([disabled])', function () {
		adjustDatatableWidth();
	});

	$(document).on('change', '.acc_assign:not([disabled])', function () {
		let tr = $(this).parents('tr');
		tr.find('.asset_class,.cost_center,.asset_desc,.gl_account').val('').trigger('change');

		if ($(this).val() == 'K') {
			tr.find('.asset_class,.asset_desc').attr('disabled', true);
			tr.find('.asset_class,.asset_desc').attr('required', false);
			tr.find('.gl_account').attr('disabled', false);
			tr.find('.gl_account').attr('required', true);
			tr.find('.asset_class,.asset_desc').val('').trigger('change');
			tr.find('.asset_class,.cost_center,.asset_desc,.gl_account').each(function (i, el) {
				$(el).parents(".form-group > div").removeClass("has-error");
				$(el).parents(".form-group > div").find('em').remove();
			})
		}
		else {
			tr.find('.asset_class,.asset_desc').attr('disabled', false);
			tr.find('.asset_class,.asset_desc').attr('required', true);
			tr.find('.gl_account').attr('disabled', true);
			tr.find('.gl_account').attr('required', false);
		}
	});

	var modal = $('#confirm_modal');
	var table = $('#table-detail-assets', modal);
	var templateTr = $('.template', table);
	var trClone = templateTr.clone();

	//approve
	$(document).on("click", ".approve", function () {
		var no_pi = $(this).data("edit");
		var btn_save = $(this).data("btn_save");
		$.ajax({
			url: baseURL + 'nusira/transaksi/get/asset_detail',
			type: 'POST',
			dataType: 'JSON',
			data: {
				no_pi: no_pi
			},
			success: function (data) {
				if (data) {
					$.each(data, function (i, v) {
						$('.template', table).remove();
						$("#no_pi").val(v.no_pi);
						$("input[name='nomor_pi']").val(v.nomor_pi);
						$("input[name='plant']").val(v.plant);
						$("input[name='tanggal_pi']").val(v.tanggal_pi);
						let complete = v.complete;
						if (complete)
							$('.modal-footer', modal).addClass('hide');
						else
							$('.modal-footer', modal).removeClass('hide');

						var kostl = [];

						$.each(v.arr_detail, function (x, y) {
							var clone = trClone.clone();
							clone.removeClass('template hide');
							clone.find('.mat_no').html(y.no);
							if (KIRANAKU.isNullOrEmpty(y.matnr) === false)
								clone.find('.mat_name').html(y.matnr + '<br/>' + y.maktx);
							else
								clone.find('.mat_name').html(y.perm_invest);
							clone.find('.order_qty').html(y.jumlah);
							clone.find('.no').val(y.no);
							clone.find('.cost_center option').each(function (ie, el) {
								if ($(el).attr('data-plant') != v.plant && $(el).attr('data-plant'))
									$(el).remove();
							});

							if (KIRANAKU.isNullOrEmpty(y.acc_assign) === false) {
								clone.find('.acc_assign').val(y.acc_assign);
							} else {
								if (KIRANAKU.isNullOrEmpty(y.matnr) === true)
									clone.find('.acc_assign').val("K").trigger("change.select2");
							}

							if (KIRANAKU.isNullOrEmpty(y.asset_class) === false) {
								clone.find('.asset_class').val(y.asset_class);
							}
							else {
								if (KIRANAKU.isNullOrEmpty(y.matnr) === false)
									clone.find('.asset_class').val("00005006").trigger("change.select2");
							}

							if (KIRANAKU.isNullOrEmpty(y.cost_center) === false) {
								clone.find('.cost_center').val(y.cost_center);
							}
							else {
								var nama = y.maktx;
								if (nama && nama.toLowerCase().includes("creeper") === true) {
									var milling = clone.find(".cost_center option:contains('Milling')").val();
									clone.find('.cost_center').val(milling).trigger("change.select2");
								}
								else if (nama && nama.toLowerCase().includes("shredder") === true) {
									var crumbing = clone.find(".cost_center option:contains('Crumbing')").val();
									clone.find('.cost_center').val(crumbing).trigger("change.select2");
								}
								else if (kostl.length > 1) {
									var clearing = clone.find(".cost_center option:contains('Clearing')").val();
									clone.find('.cost_center').val(clearing).trigger("change.select2");
								}
								else if (kostl.length == 1)
									clone.find('.cost_center').val(kostl[0]).trigger("change.select2");
							}
							if ($.inArray(clone.find('.cost_center').val(), kostl) < 0)
								kostl.push(clone.find('.cost_center').val());

							if (KIRANAKU.isNullOrEmpty(y.asset_desc) === false)
								clone.find('.asset_desc').val(y.asset_desc);

							if (KIRANAKU.isNullOrEmpty(y.gl_account) === false) {
								clone.find('.gl_account').val(y.gl_account);
							}
							else {
								if (KIRANAKU.isNullOrEmpty(y.matnr) === true)
									clone.find('.gl_account').val("0004402001").trigger("change.select2");
							}

							if (!complete)
								clone.find('input,select').removeAttr('disabled');

							clone.find('.gl_account').prop('disabled', true);
							if (KIRANAKU.isNullOrEmpty(y.matnr) === true && !complete) {
								clone.find('.asset_class').prop('disabled', true);
								clone.find('.asset_desc').prop('disabled', true);
								clone.find('.gl_account').prop('disabled', false);
							}

							clone.find('input,select').each(function (j, el) {
								$(el).attr('name', $(el).attr('name') + '[' + x + ']');
							});
							table.find('tbody').append(clone);
						});
					});
				}
			},
			error: function () {
				kiranaAlert("notOK", "Server Error", "error", "no");
			},
			complete: function () {
				if (btn_save == 'hidden') {
					$('.form-control-hide').prop('disabled', true);
					$('.switch-onoff').prop('disabled', true);
					$("#btn_save").hide();
				}
				$('#confirm_modal').modal('show');

				let table = $('#table-detail-assets').DataTable({
					paging: false,
					info: false,
					lengthChange: false,
					pageLength: 5,
					searching: false,
					scrollX: true,
					columnDefs: [
						{width: '20%', target: 6}
					]
				});

				$(table.table().container()).removeClass('form-inline');

				$('.s2m:not(.template .s2m)', modal).select2({
					dropdownParent: $('#confirm_modal')
				});
			}
		});
	});

	$('#confirm_modal').on('shown.bs.modal', function () {
		adjustDatatableWidth();
	});

	$('#confirm_modal').on('hidden.bs.modal', function () {
		$('#table-detail-assets').DataTable().destroy();
		$('#table-detail-assets tbody tr:not(.template)').remove();
		table.find('tbody').append(trClone);
	});

	$(document).on("click", "button[name='action_btn']", function (e) {
		e.preventDefault();
		validator.resetForm();
		let valid = validator.form();

		if (valid) {
			KIRANAKU.showLoading();
			var formData = new FormData($(".form-transaksi-confirmaset")[0]);
			$.ajax({
				url: baseURL + 'nusira/transaksi/save/confirmaset',
				type: 'POST',
				dataType: 'JSON',
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function (data) {
					KIRANAKU.hideLoading();
					if (data.sts == 'OK') {
						kiranaAlert(data.sts, data.msg);
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

function datatables_ssp() {
	var plant_filter = $("#plant_filter").val();
	var tujuan_filter = $("#tujuan_filter").val();
	var status_filter = $("#status_filter").val();
	$("#sspTable").DataTable().destroy();
	var mydDatatables = $("#sspTable").DataTable({
		pageLength: 10,
		initComplete: function () {
			var api = this.api();
			$('#sspTable_filter input')
				.off('.DT')
				.on('input.DT', function () {
					api.search(this.value).draw();
				});
		},
		oLanguage: {
			sProcessing: "Please wait..."
		},
		scrollX: true,
		processing: true,
		serverSide: true,
		ajax: {
			url: baseURL + 'nusira/transaksi/get/asset',
			type: 'POST',
			data: function (data) {
				data.plant_filter = plant_filter;
				data.tujuan_filter = tujuan_filter;
				data.complete = status_filter;
			},
			error: function (a, b, c) {
				console.log(a);
				console.log(b);
				console.log(c);
			}
		},
		columns: [
			{
				"data": "tbl_pi_header.plant",
				"name": "plant",
				"width": "5%",
				"render": function (data, type, row) {
					return row.plant;
				}
			},
			{
				"data": "tbl_pi_header.nomor_pi",
				"name": "no_pi",
				"width": "8%",
				"render": function (data, type, row) {
					return row.nomor_pi;
				}
			},
			// {
			// "data": "tbl_pi_mtujuan_inv.tujuan_inv",
			// "name" : "tujuan_inv",
			// "width": "15%",
			// "render": function (data, type, row) {
			// return row.tujuan_inv;
			// }
			// },
			{
				"data": "tbl_pi_header.perihal",
				"name": "perihal",
				"width": "15%",
				"render": function (data, type, row) {
					return row.perihal;
				}
			},
			{
				"data": "tbl_pi_header.tanggal",
				"name": "tanggal",
				"width": "5%",
				"render": function (data, type, row) {
					return row.tanggal;
				}
			},
			{
				"data": "tbl_pi_header.tanggal",
				"name": "tanggal",
				"width": "10%",
				"render": function (data, type, row) {
					if (row.status == 'finish') {
						return '<span class="label label-success">FINISH</span>';
					} else if (row.status == 'drop') {
						return '<span class="label label-danger">DROP</span>';
					} else if (row.status == 'deleted') {
						return '<span class="label label-danger">DELETED</span>' + '<br><small>oleh ' + row.status_pi_delete + '</small>';
					} else {
						return '<span class="label label-warning">ON PROGRESS</span>' + '<br><small>Sedang diproses di ' + row.status_pi + '</small>';
					}
				}
			},
			{
				"data": "tbl_pi_header.complete",
				"name": "complete",
				"width": "5%",
				"render": function (data, type, row) {
					if (row.complete == 1) {
						return '<label class="label label-success">Complete</label>';
					} else {
						return '<label class="label label-warning">Waiting</label>';
					}
				}
			},
			{
				"data": "tbl_pi_header.no_pi",
				"name": "no_pi",
				"width": "5%",
				"render": function (data, type, row) {

					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if (row.complete)
						output += "					<li><a href='javascript:void(0)' class='approve' data-edit='" + row.no_pi + "'><i class='fa fa-search'></i> Detail</a></li>";
					else
						output += "					<li><a href='javascript:void(0)' class='approve' data-edit='" + row.no_pi + "'><i class='fa fa-check-square-o'></i> Konfirmasi</a></li>";
					output += "				</ul>";
					output += "	        </div>";

					// if (row.nsw_check != 1) {
					//     output = "			<div class='input-group-btn'>";
					//     output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					//     output += "				<ul class='dropdown-menu pull-right'>";
					//     output += "					<li><a href='javascript:void(0)' class='approve' data-edit='" + row.no_pi + "'><i class='fa fa-check-square-o'></i> Approve</a></li>";
					//     output += "				</ul>";
					//     output += "	        </div>";
					// } else {
					//     output = "			<div class='input-group-btn'>";
					//     output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					//     output += "				<ul class='dropdown-menu pull-right'>";
					//     output += "					<li><a href='javascript:void(0)' class='approve' data-edit='" + row.no_pi + "' data-btn_save='hidden'><i class='fa fa-search'></i> Detail</a></li>";
					//     output += "					<li><a href='javascript:void(0)' class='approve' data-edit='" + row.no_pi + "' data-btn_save='hidden' data-show='history'><i class='fa fa-h-square'></i> History</a></li>";
					//     output += "				</ul>";
					//     output += "	        </div>";
					// }
					return output;
				}
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
