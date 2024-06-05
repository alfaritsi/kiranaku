$(document).ready(function () {
	get_data_pi();

	$(document).on("click", "#log_status", function (e) {
		$('#KiranaModals .modal-dialog').addClass("modal-lg");
		$('#KiranaModals .modal-title').html("PI History");
		var output = "";
		var data = JSON.parse(atob(sessionStorage.getItem($("input[name='no_pi']").val())));

		output += '<div class="row">';
		output += '		<div class="col-sm-12">';
		output += '			<table class="table table-bordered table-striped my-datatable-extends">';
		output += '				<thead>';
		output += '					<th>No.PI</th>';
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

		var t = $('#KiranaModals table').DataTable({
			order: [[1, 'desc']],
			lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
			scrollCollapse: true,
			scrollY: false,
			scrollX: true,
			bautoWidth: false,
			columnDefs: [
				{className: "wrap-text", "targets": [3]}
			]
		});
		if (data) {
			$.each(data, function (i, v) {
				t.row.add([
					v.no_pi,
					v.tgl_status,
					"<span style='text-transform: capitalize'>" + v.action + "</span> oleh <br> <span class='label label-info'>" + v.nama_role + " : " + v.nama + "</label>",
					v.comment
				]).draw(false);
			});
		}

		setTimeout(function () {
			adjustDatatableWidth();
		}, 1500);

		$('#KiranaModals').modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});
	});

	$(document).on("click", ".action_item", function (e) {
		var action = $(this).val();
		$(this).closest(".row-summary").css("background-color", (action == 1 ? "rgba(0, 141, 76, 0.3)" : "rgba(255, 141, 76, 0.3)"));
		$(this).closest(".row-summary").find("td input[name='status_detail[]']").attr("value", action);
		if (action == 0) {
			$(this).closest(".row-summary").find("td input[name='total[]']").addClass("exclude")
			$(this).closest(".row-summary").find("td .budget_select").addClass("exclude");
		} else {
			$(this).closest(".row-summary").find("td input[name='total[]']").removeClass("exclude")
			$(this).closest(".row-summary").find("td .budget_select").removeClass("exclude");
		}

		if ($(".budget_select.exclude").length == $(".row-summary").length && $("input[name='last_action']").val() !== "assign") {
			$("button[name='action_btn']").hide();
		} else {
			$("button[name='action_btn']").show();
		}

		generate_summary_total($(this));
	});

	$(document).on("click", ".check_status_nsw", function (e) {
		let approve = '<i class="fa fa-check-square-o" title="Approved"></i>';
		let reject = '<i class="fa fa-close" title="Rejected"></i>';

		var statusnsw = $(this).data("statusnsw");
		var reasonnsw = ($(this).data("reasonnsw") == null ? '-' : $(this).data("reasonnsw"));
		switch (statusnsw) {
			case 1 :
				statusnsw = approve;
				break;
			case 0 :
				statusnsw = reject;
				break;
			default :
				statusnsw = 'Waiting confirmation';
				reasonnsw = 'Waiting confirmation';
				break;
		}

		var status = $(this).data("status");
		var reason = ($(this).data("reason") == null || $(this).data("reason").trim() == '' ? '-' : $(this).data("reason"));
		switch (status) {
			case 'approved' :
				status = approve;
				break;
			case 'rejected' :
				status = reject;
				break;
			default :
				status = 'Waiting confirmation';
				reason = 'Waiting confirmation';
				break;
		}

		var output = "";
		output += "<table class='table table-bordered'>";
		output += "	<tr>";
		output += "		<td align='center' width='33.3%'>&nbsp;</td>";
		output += "		<td align='center' width='33.3%'>Nusira Workshop</td>";
		if ($(this).data('role') == "Department Head")
		output += "		<td align='center' width='33.3%'>" + ($(this).data('role')) + "</td>";
		output += "	</tr>";
		output += "	<tr>";
		output += "		<td align='center' width='33.3%'>Action</td>";
		output += "		<td align='center' width='33.3%'>" + statusnsw + "</td>";
		if ($(this).data('role') == "Department Head")
		output += "		<td align='center' width='33.3%'>" + status + "</td>";
		output += "	</tr>";
		output += "	<tr>";
		output += "		<td align='center' width='33.3%'>Reason / Comment</td>";
		output += "		<td align='center' width='33.3%'>" + reasonnsw + "</td>";
		if ($(this).data('role') == "Department Head")
		output += "		<td align='center' width='33.3%'>" + reason + "</td>";
		output += "	</tr>";
		output += "	<tr>";
		output += "		<td align='center' width='33.3%'>Estimated Work Duration</td>";
		output += "		<td align='center' width='33.3%'>" + ($(this).data("delivnsw") == null ? 'Waiting confirmation' : $(this).data("delivnsw") + " minggu") + "</td>";
		if ($(this).data('role') == "Department Head")
		output += "		<td align='center' width='33.3%'>-</td>";
		output += "	</tr>";
		output += "</table>";
		kiranaAlert("modal", "", "info", "no", output);
	});

	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate(".form-tambah-order");
		if (empty_form == 0) {
			if ($(this).val() == 'decline' && $("select[name='reason_decline']").length > 0) {
				$("#select_reason").show();
			} else {
				$("#select_reason").hide();
			}

			if ($(this).val() == 'assign') {
				$("#assign_dept_head").show();
				$("#vendor_selection").hide();
			} else {
				$("#assign_dept_head").hide();
				$("#vendor_selection").show();
			}
			$("#action_modal").val($(this).val());
			$("#no_pi_modal").val($("input[name='no_pi']").val());

			var no_detail = "";
			$("input[name='no_detail[]']").each(function (e) {
				no_detail += $(this).val() + ",";
			});
			$("input[name='no_detail']").val(no_detail.slice(0, -1));
			var status = "";
			$("input[name='status_detail[]']").each(function (e) {
				status += $(this).val() + ",";
			});
			$("input[name='status_detail']").val(status.slice(0, -1));
			var itnum = "";
			$("input[name='itnum[]']").each(function (e) {
				itnum += $(this).val() + ",";
			});
			$("input[name='itnum_detail']").val(itnum.slice(0, -1));
			var matnr = "";
			$("input[name='matnr[]']").each(function (e) {
				matnr += $(this).val() + ",";
			});
			$("input[name='matnr_detail']").val(matnr.slice(0, -1));
			var kdmat = "";
			$("input[name='kdmat[]']").each(function (e) {
				kdmat += $(this).val() + ",";
			});
			$("input[name='kdmat_detail']").val(kdmat.slice(0, -1));

			switch ($(this).val()) {
				case "approve" :
					$("#comment_modal").removeAttr("class");
					$("#comment_modal").addClass("modal");
					$("#comment_modal").addClass("modal-success");
					break;
				case "decline" :
					$("#comment_modal").removeAttr("class");
					$("#comment_modal").addClass("modal");
					$("#comment_modal").addClass("modal-warning");
					break;
				case "assign" :
					$("#comment_modal").removeAttr("class");
					$("#comment_modal").addClass("modal");
					$("#comment_modal").addClass("modal-info");
					break;
				case "drop" :
					$("#comment_modal").removeAttr("class");
					$("#comment_modal").addClass("modal");
					$("#comment_modal").addClass("modal-danger");
					break;
			}

			$("#comment_modal_label").html($(this).val() + " PI");
			$('#comment_modal').modal('show');
			if ($(this).val() == 'approve') {
				$("#comment").removeAttr("required");
			} else {
				$("#comment").attr("required", "required");
			}
		}
	});

	$(document).on("click", "#save_form-action-pi", function (e) {
		var est_total = $("input[name='est_total']").val().replace(/,/g, "") * 1;
		var ava_budget = $("input[name='ava_budget']").val().replace(/,/g, "") * 1;

		var not_validate = false;

        var action = $("#action_modal").val();


        $(".budget_select").each(function (e) {
            // var data = $(this).select2('data');

            var count_budget = $("input[name='budget_available_to_select']").val();
            var total_need = $(this).closest(".row-summary").find("input[name='total[]']").val().replace(/,/g, "");
            var total_give = 0;
            var matnr = $(this).closest(".row-summary").find("input[name='matnr[]']").val();
            var required = ($(this).closest(".row-summary").find("select[name='tipe_pi[]']").val() == "budgeted" && matnr.trim() !== "") ? true : false;

            $("option:selected",this).each(function(i,v){
                total_give += $(v).attr("data-remaining") ? +$(v).attr("data-remaining") : +0;
			});

            // console.log(total_give+"=>"+total_need+"=>"+count_budget+"=>"+required);
            if(total_give < total_need && count_budget > 0 && required == true && action == "approve"){
                kiranaAlert("notOK", "Budget tidak mencukupi, mohon untuk melakukan perubahan terlebih dahulu.", "error", "no");
                not_validate = true;
            }
        });

		var empty_form = validate("#form-action-pi");
		if (empty_form == 0 && not_validate == false) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				if (action == "approve" && ava_budget < est_total && $("input[name='budget_available_to_select']").val() > 0) {
					kiranaAlert("notOK", "Budget tidak mencukupi, mohon untuk melakukan perubahan terlebih dahulu.", "error", "no");
					$("input[name='isproses']").val(0);
				} else {
					var formData = new FormData($("#form-action-pi")[0]);
					$.ajax({
						url: baseURL + 'nusira/order/save/approve',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						beforeSend: function () {
							var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
							$("#comment_modal .modal-content").append(overlay);
						},
						success: function (data) {
							console.log(data);
							if (data.sts == 'OK') {
								kiranaAlert(data.sts, data.msg, "success", data.link);
							} else {
								kiranaAlert(data.sts, data.msg, "error", "no");
								$("input[name='isproses']").val(0);
							}
						},
						error: function () {
							$("#comment_modal .modal-content .overlay").remove();
							$("input[name='isproses']").val(0);
							kiranaAlert("notOK", "Server Error", "error", "no");
						},
						complete: function () {
							$("#comment_modal .modal-content .overlay").remove();
						}
					});
				}
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai.", "error", "no");
			}
		}
		e.preventDefault();
		return false;
	});

	$(document).on("click", "#sync_sap", function () {
		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			$.ajax({
				url: baseURL + 'nusira/order/set/PO',
				type: 'POST',
				dataType: 'JSON',
				data: {
					no_pi: $("input[name='no_pi']").val()
				},
				beforeSend: function () {
					var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
					$("body .overlay-wrapper").append(overlay);
				},
				success: function (data) {
					if (data.sts == 'OK') {
						kiranaAlert(data.sts, data.msg, "success");
					} else {
						kiranaAlert(data.sts, data.msg, "error", "no");
						$("input[name='isproses']").val(0);
					}
				},
				error: function () {
					$("body .overlay-wrapper .overlay").remove();
					$("input[name='isproses']").val(0);
					kiranaAlert("notOK", "Server Error", "error", "no");
				},
				complete: function () {
					$("body .overlay-wrapper .overlay").remove();
				}
			});
		} else {
			kiranaAlert("notOK", "Silahkan tunggu proses selesai.", "error", "no");
		}
	});
});

function get_data_pi() {
	$.ajax({
		url: baseURL + '/nusira/order/get/detail',
		type: 'POST',
		dataType: 'JSON',
		data: {
			no_pi: $("input[name='no_pi']").val()
		},
		beforeSend: function () {
			var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
			$("body .overlay-wrapper").append(overlay);
		},
		success: function (data) {
			if (data) {
				sessionStorage.setItem(data.no_pi, btoa(JSON.stringify(data.log)));
				$("select[name='kpd']").val(data.kepada).trigger("change");
				$("select[name='tujuan_inv']").val(data.tujuan_inv).trigger("change");
				$("input[name='perihal']").val(data.perihal);
				$("select[name='pic_proj']").val(data.pic_proj).trigger("change");
				$("input[name='tanggal']").val(data.tanggal);
				$("select[name='pic_pemb']").val(data.pic_pemb).trigger("change");
				if (data.total_rekom && data.total_rekom.total_rekom) {
					$("input[name='nilai_final_pi']").val(numberWithCommas(data.total_rekom.total_rekom));
					$("#nilai_final_pi_container").removeClass("hidden");
				}

				idx = 1;
				$("textarea[name='quest[]']").each(function (e) {
					$(this).val(data["quest" + idx]);
					idx++;
				});
				$("textarea[name='pic_proj']").val(data.pic_proj).trigger("change");

				//detail order
				if ($("#nodata").length > 0 && data.detail.length > 0)
					$("#nodata").remove();

				var avbudget = 0;
				var total = 0;
				$.each(data.detail, function (i, v) {
					var output = '';
					output += '<tr class="row-summary summaryproduct' + (i + 1) + '">';
					output += '	<td>';
					output += '		<input type="text" class="form-control hidden" name="no_detail[]" value="' + v.no + '"/>';
					output += '		<input type="text" class="form-control hidden" name="status_detail[]" min="0" required="required"/>';
					output += '		<input type="text" class="form-control hidden" name="itnum[]" value="' + v.itnum + '"/>';
					output += '		<input type="text" class="form-control hidden" name="matnr[]" value="' + v.matnr + '"/>';
					output += '		<input type="text" class="form-control hidden" name="kdmat[]" value="' + v.kdmat + '"/>';
					output += '		<textarea class="form-control" name="permin[]" style="resize:vertical"  rows="5" readonly="readonly">' + v.perm_invest + '</textarea>';
					output += '	</td>';
					output += '	<td><textarea class="form-control" name="spes[]" style="resize:vertical"  rows="5" readonly="readonly">' + (v.spesifikasi ? v.spesifikasi.replace(/<br\s*[\/]?>/gi, "\r\n") : '') + '</textarea></td>';
					output += '	<td>';
					output += '		<select class="form-control select2" name="tipe_pi[]" required="required">';
					output += '			<option value="budgeted">Budgeted</option>';
					output += '			<option value="unbudgeted">Unbudgeted</option>';
					output += '		</select>';
					output += '	</td>';
					output += '	<td><select class="form-control select2 budget_select" name="budget_select_' + (i + 1) + '[]" multiple="multiple" ' + (v.matnr && v.matnr.trim() !== "" ? 'required="required"' : '') + '></select></td>';
					output += '	<td><input type="text" class="form-control text-right angka" required="required" name="jml[]" value="' + numberWithCommas(v.jumlah) + '" ' + (v.matnr && v.matnr.trim() !== "" ? 'required="required"' : 'readonly="readonly"') + '/></td>';
					output += '	<td><input type="text" class="form-control text-center" name="satuan[]" value="' + v.satuan + '" readonly="readonly"/></td>';
					output += '	<td>';
					output += '		<div class="input-group">';
					output += '			<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
					output += '			<input type="text" class="form-control kiranadatepicker" data-format="yyyy-mm-dd" data-autoclose="true" name="req_deliver_date[]" value="' + (v.req_deliv_date ? v.req_deliv_date : '') + '" ' + (v.matnr && v.matnr.trim() !== "" ? 'required="required"' : 'readonly="readonly"') + '/>';
					output += ' 	</div>';
					output += ' </td>';
					output += '	<td>';
					output += '		<div class="input-group">';
					output += '			<div class="input-group-addon">Rp</div>';
					output += '			<input type="text" class="form-control text-right" name="hrg[]" value="' + numberWithCommas(v.harga) + '" readonly="readonly"/>';
					output += ' 	</div>';
					output += ' </td>';
					output += '	<td>';
					output += '		<div class="input-group">';
					output += '			<div class="input-group-addon">Rp</div>';
					output += '			<input type="text" class="form-control text-right" name="total[]" value="' + numberWithCommas(v.total) + '" readonly="readonly"/>';
					output += ' 	</div>';
					output += ' </td>';
					if (data.access === true) {
						output += '	<td class="text-center">';
						output += '		<div class="btn-group">';
						output += '			<button type="button" class="btn btn-sm btn-success approve_item action_item" data-product="product' + (i + 1) + '" title="Approve" value="1"><i class="fa fa-check"></i></button>';
						output += '			<button type="button" class="btn btn-sm btn-danger reject_item action_item" data-product="product' + (i + 1) + '" title="Reject" value="0"><i class="fa fa-close"></i></button>';
						if (data.detail_nsw === true && v.matnr.trim() !== "")
							output += '			<button type="button" class="btn btn-sm btn-warning check_status_nsw" data-product="product' + (i + 1) + '" data-statusnsw="' + data.detail[i].status_nsw + '" data-delivnsw="' + data.detail[i].nsw_durasi_mgg + '" data-reasonnsw="' + data.detail[i].nsw_reason + '" data-role="' + data.log[0].nama_role + '" data-status="' + data.detail[i].status + '" data-reason="' + data.log[0].comment + '" title="Detail Status"><i class="fa fa-search"></i></button>';
						output += '		</div>';
						output += '	</td>';
					}
					output += '</tr>';
					$(".table-summary").append(output);

					if (data.access) {
						$(".table-summary thead tr th:last-child").show();
						$(".table-summary tfoot tr td:last-child").show();
					} else {
						$(".table-summary thead tr th:last-child").hide();
						$(".table-summary tfoot tr td:last-child").hide();
					}
					$(".summaryproduct" + (i + 1) + " select[name='tipe_pi[]']").val(v.tipe_pi).trigger("change");

					var no_budget = [];
					$.each(data.budget, function (id, val) {
						if (v.no == val.no_detail) {
							var selects = "";
							// if (val.kategori == "Bangunan") {
								selects = val.value_budget_referensi * 1;
								avbudget += val.value_budget_referensi * 1;
							// } else {
							// 	selects = val.budget * 1;
							// 	avbudget += val.budget * 1;
							// }
							budget = "<option value='" + val.no_budget + "' id='" + val.no_budget + "' data-remaining='" + selects + "'>" + val.no_budget + ' || ' + val.investasi + ' || <b>' + val.kategori + '</b> || ' + numberWithCommas(selects) + ' || ' + numberWithCommas(selects) + "</option>";
							$("select[name='budget_select_" + (i + 1) + "[]']").append(budget);
							no_budget.push(val.no_budget);
						}
					});
					$("select[name='budget_select_" + (i + 1) + "[]']").val(no_budget).trigger("change");

					total += +v.total;
				});
				$("input[name='est_total']").val(numberWithCommas(total));
				$("input[name='ava_budget']").val(numberWithCommas(avbudget));
				var est_total = $("input[name='est_total']").val().replace(/,/g, "") * 1;
				var selisih = avbudget - est_total > -1 ? numberWithCommas(avbudget - est_total) : "(" + numberWithCommas(Math.abs(avbudget - est_total)) + ")";
				$("input[name='selisih_budget']").val(selisih);

				$(".row-form textarea[name='note_pi']").val(data.note_adm_proc);
			}
		},
		error: function () {
			$("body .overlay-wrapper .overlay").remove();
			kiranaAlert("notOK", "Server Error", "error", "no");
		},
		complete: function () {
            get_available_budget_to_select();
			$("body .overlay-wrapper .overlay").remove();
			$(".table-summary .select2").select2();
			$("#comment_modal .select2").select2({
				dropdownParent: $('#comment_modal')
			});
			$("textarea[name='spes[]']").each(function(){
				$(this).html($(this).val());
			});
			$("input,select,textarea").prop("readonly", true);
			$(".select2").prop("disabled", true);
			$("#log_status_modal input").prop("readonly", false); //for modal status
			$("#comment_modal input, #comment_modal textarea, #comment_modal select").prop("readonly", false); //for modal comment
			$("#comment_modal .select2").prop("disabled", false); //for modal comment
		}
	});
}

function generate_summary_total(elem, action) {
	// var elemenAction = elem.closest(".row-summary");
	totalAction = 0;
	avbudgetAction = 0;
	if (action !== "1") {
		$("input[name='total[]'].exclude").each(function (i) {
			totalAction += +$(this).val().replace(/,/g, "");
		});
		$(".budget_select.exclude").each(function (i) {
			var data = $(this).select2().find(":selected");
			if (data.length > 0) {
				$.each(data, function (i, v) {
					avbudgetAction += +$(v).data('remaining');
				});
			}
		});
	}

	var total = 0;
	$("input[name='total[]']").each(function (i) {
		total += +$(this).val().replace(/,/g, "");
	});
	$(".summary_total").val(numberWithCommas(total - totalAction));

	var avbudget = 0;
	$(".budget_select").each(function (e) {
		var data = $(this).select2().find(":selected");
		if (data.length > 0) {
			$.each(data, function (i, v) {
				avbudget += +$(v).data('remaining');
			});
		}
	});

	$(".summary_budget").val(numberWithCommas(avbudget - avbudgetAction));

	var selisih = (avbudget - avbudgetAction) - (total - totalAction) >= 0 ? numberWithCommas((avbudget - avbudgetAction) - (total - totalAction)) : "(" + numberWithCommas(Math.abs((avbudget - avbudgetAction) - (total - totalAction))) + ")";
	$(".summary_selisih").val(numberWithCommas(selisih));
}

function get_available_budget_to_select() {
	$.ajax({
		url: baseURL + 'nusira/order/get/count_budget',
		type: 'POST',
		dataType: 'JSON',
		data:{
			plant : $("input[name='no_pi']").val().split("/")[2],
			year : $("input[name='no_pi']").val().split("/")[$("input[name='no_pi']").val().split("/").length - 1]
		},
		success: function (data) {
			$("input[name='budget_available_to_select']").val(data.length);
			if(data.length == 0){
				$(".budget_select").removeAttr("required");
			}
		},
		error: function () {
			kiranaAlert("notOK", "Server Error", "error", "no");
		}
	});
}
