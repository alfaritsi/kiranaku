$(document).ready(function () {
	//========LOAD DATA=========//
	$.ajax({
		url: baseURL + "umb/master/get/plafon",
		type: 'POST',
		dataType: 'JSON',
		data: {
			active: true
		},
		success: function (data) {
			var output_display = "";
			var output_form = "";
			var sum_plafon = 0;
			console.log(data);
			if (data) {
				myelement = [];
				$('.my-datatable-extends-order').DataTable().destroy();
				var t = $('.my-datatable-extends-order').DataTable({
					columnDefs: [
						{"className": "text-right", "targets": [1, 2]}
					]
				});
				t.clear().draw();

				var x = 0;
				$.each(data, function (i, v) {
					if (v.start_date == null && v.end_date == null && v.active == true) {
						if ($(".add-plafon").is(":visible") == true) {
							$("#close_list_form").hide();
							$(".add-plafon").attr("data-row", "no");
							$(".add-plafon").click();
						}
						myelement.push({
							element: 'select[name="plant_plafon[]"]:eq(' + x + ')',
							value: v.kode_pabrik
						});
						// myelement.push({element: 'input[name="ranger[]"]:eq(' + x + ')', value: v.ranger});
						// myelement.push({element: 'input[name="lain[]"]:eq(' + x + ')', value: v.lain});
						myelement.push({element: 'input[name="limit[]"]:eq(' + x + ')', value: v.limit_plafon});
						add_row(myelement);
						x++;


						// $(".btn-pengajuan").hide();
						// $(".btn-approval").show();
						$("input[name='caption']").val(v.file_path.split('/').pop());
						$("#view_bukti").attr('data-link', v.file_path);
						$("#view_bukti").removeAttr('disabled');

					}

					if (v.start_date !== null && v.end_date == null && v.active == true) {
						sum_plafon += +v.limit_plafon;
						var myrow = t.row.add([
							v.plant_name,
							// numberWithCommas(parseFloat(v.ranger).toFixed(2)),
							// numberWithCommas(parseFloat(v.lain).toFixed(2)),
							numberWithCommas(parseFloat(v.limit_plafon).toFixed(2)),
							generateDateFormat(v.start_date)
						]).draw(false).node();

						$(myrow).attr("data-plant", v.kode_pabrik);
						$(myrow).attr("data-plantname", v.plant_name);
						$(myrow).attr("title", "Klik untuk lihat data log");
						$(myrow).addClass("detail");
					}
				});

				$('.sum_plafon').html('<strong>Total Plafon Seluruh Pabrik : Rp. '+numberWithCommas(parseFloat(sum_plafon).toFixed(2))+'</strong>');
				console.log(numberWithCommas(parseFloat(sum_plafon).toFixed(2)));
			}
		},
		complete: function () {
		}
	});

	$(document).on("change", "input[name='bukti_file[]']", function () {
        $.each($(this).get(0).files, function(i,v){
            // name = v.name.substr(0, v.name.lastIndexOf("."));
            // name = v.name;
            $("input[name='caption']").val(v.name);
        });
		$("#view_bukti").attr('disabled');
		$("#isnew").val('yes');

	});

	$(document).on("click", "#view_bukti", function () {
		if ($(this).data("link") !== "") {
			window.open(baseURL+$(this).data("link"), '_blank');
		}else{
			kiranaAlert("notOK", "File Tidak Ditemukan", "warning", "no");
		}
	});

	$(document).on("click", "#upload_bukti", function () {
		$("input[name='bukti_file[]']").click();
	});

	$(document).on("click", "#close_list_form", function () {
		$("#list_display").removeAttr("class");
		$("#list_display").addClass("col-sm-12");
		$("#list_form").hide();

		adjustDatatableWidth();

		$(".add-plafon").show();
		$("#input-plafon-wrapper").html("");
	});

	$(document).on("click", ".add-plafon", function () {
		// $("#list_display").removeAttr("class");
		// $("#list_display").addClass("col-sm-7");
		$("#list_form").show();

		adjustDatatableWidth();

		$(".add-plafon").hide();
		if ($(this).data("row") == "yes") {
			if ($(".add-row").is(":visible") == true) {
				$(".add-row").click();
			} else {
				$("#list_form").hide();
				$(".add-plafon").show();
				kiranaAlert("notOK", "Tidak ada plafon yang perlu di approve", "warning", "no");
			}
		}
	});

	$(document).on("click", ".add-row", function () {
		add_row();
	});

	$(document).on("click", ".delete-row", function () {
		var count = $("#input-plafon-wrapper tr").length;
		if (count > 1) $("#input-plafon-wrapper tr:eq(" + (count - 1) + ")").remove();
	});

	$(document).on("change", "select[name='plant_plafon[]']", function () {
		var current = $(this);
		var count = 0;
		var test = current.closest("tr").find("#file");
		test.removeClass('btn-danger').addClass('btn-success');

		if (current.val() !== 0) {
			var i = 0;
			$("select[name='plant_plafon[]']").each(function () {
				// console.log(current.val() + "<=>" + $(this).val());
				if ($(this).val() !== '0' && current.val() !== '0' && current.val() == $(this).val()) {
					count++;
				}
				i++;
			});
			if (count > 1 && i == $("select[name='plant_plafon[]']").length) {
				console.log("ada yg sama");
				kiranaAlert("notOK", "Pabrik tidak boleh sama", "warning", null);
				current.val('0').trigger("change");
			}
		}
	});

	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				$("input[name='action']").val($(this).val());
				var formData = new FormData($(".form-master-plafon")[0]);

				$.ajax({
					url: baseURL + 'umb/master/save/plafon',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						// console.log(data);
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					complete: function () {
						$("input[name='isproses']").val(0);
					}
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
	});

	$(document).on("click", ".detail", function (e) {
		var plant = $(this).data("plant");
		var plantname = $(this).data("plantname");
		$.ajax({
			url: baseURL + "umb/master/get/plafon",
			type: 'POST',
			dataType: 'JSON',
			data: {
				plant: plant,
				start: 'NOT NULL'
			},
			beforeSend: function () {
				var elements = '<table class="table table-bordered table-striped table-modals">';
				elements += '	<thead>';
				// elements += '		<th>Ranger</th>';
				// elements += '		<th>lain-lain</th>';
				elements += '		<th>Limit Plafon</th>';
				elements += '		<th>Start Date</th>';
				elements += '		<th>End Date</th>';
				elements += '		<th>Bukti Perubahan</th>';
				elements += '	</thead>';
				elements += '	<tbody></tbody>';
				elements += '</table>';
				$('#KiranaModals .modal-title').html("Log Data Master Plafon " + plantname);
				$('#KiranaModals .modal-body').html(elements);
				$('.table-modals').DataTable({
					columnDefs: [
						{"className": "text-right", "targets": [0, 1, 2]}
					],
					order: [[1, 'desc']],
					scrollX: true
				});
			},
			success: function (data) {
				if (data) {
					$('.table-modals').DataTable().destroy();
					var t = $('.table-modals').DataTable({
						columnDefs: [
							{"className": "text-right", "targets": [0, 1, 2]}
						],
						order: [[1, 'desc']]
					});
					t.clear().draw();

					$.each(data, function (i, v) {
						let view = "";
						if (v.file_path != "" && v.file_path != null) {
							view = '<button type="button" class="btn btn-default btn-flat lihat-file" data-title="File" data-link="'+v.file_path+'" id="view_bukti" title="Lihat file"><i class="fa fa-search"></i> View File</button>'
						}

						var myrow = t.row.add([
							// numberWithCommas(parseFloat(v.ranger).toFixed(2)),
							// numberWithCommas(parseFloat(v.lain).toFixed(2)),
							numberWithCommas(parseFloat(v.limit_plafon).toFixed(2)),
							generateDateFormat(v.start_date),
							generateDateFormat(v.end_date),
							view
						]).draw(false);
					});
				}
			},
			complete: function () {
				$('#KiranaModals').modal('show');
			}
		});

		e.preventDefault();
		return false;
	});

	// $(document).on("change", "input[name='ranger[]'], input[name='lain[]']", function () {
	// 	var ranger = $(this).closest("tr").find("input[name='ranger[]']").val().replace(/,/g, "");
	// 	var lain = $(this).closest("tr").find("input[name='lain[]']").val().replace(/,/g, "");

	// 	if (!ranger) {
	// 		ranger = 0;
	// 		$(this).closest("tr").find("input[name='ranger[]']").val(0);
	// 	}
	// 	if (!lain) {
	// 		lain = 0;
	// 		$(this).closest("tr").find("input[name='lain[]']").val(0);
	// 	}

	// 	$(this).closest("tr").find("input[name='limit[]']").val(numberWithCommas(+ranger + +lain));

	// });

	// $(document).on("click", ".modify_plafon", function (e) {
	// 	$.ajax({
	// 		url: baseURL + "umb/master/get/plant",
	// 		type: 'POST',
	// 		dataType: 'JSON',
	// 		success: function (data) {
	// 			if (data.length > 0) {
	// 				$('#KiranaModals .modal-title').html("Perubahan Plafon");
	// 				var elements ='<form class="form-perubahan-plafon">';
	// 				elements += '<div class="form-group"><label>Nama Pabrik</label>';
	// 				elements += '		<select name="plant" class="form-control select2" required="required">';
	// 				elements += '			<option value="0">Silahkan pilih pabrik</option>';
	// 				$.each(data, function (i, v) {
	// 					elements += '		<option value="' + v.plant + '">' + v.plant_name + '</option>';
	// 				})
	// 				elements += '		</select></div>';
	// 				elements += '<div class="form-group"><label>Limit Plafon Uang Muka</label>';
	// 				elements += '	<input type="text" class="form-control text-right angka" name="um" required="required" min="0" value="0"/>';
	// 				elements += '</div>';
	// 				elements += '<div class="form-group"><label>Bukti Perubahan Plafon</label>';
	// 				elements += '	<input type="file" class="form-control" name="bukti_perubahan" required="required"/>';
	// 				elements += '</div></form>';
					
	// 				var output_footer = '<div class="modal-footer">';
	// 				output_footer += '	<div class="form-group">';
	// 				output_footer += '		<button type="button" class="btn btn-primary" id="submit_perubahan">Submit</button>';
	// 				output_footer += '		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>';
	// 				output_footer += '	</div>';
	// 				output_footer += '</div>';						
											
	// 				$('#KiranaModals .modal-body').html(elements);
	// 				if ($(".modal-footer").length == 0) {
	// 					$('#KiranaModals .modal-content').append(output_footer);
	// 				}
	// 			}
	// 		},
	// 		complete: function () {
	// 			$('#KiranaModals').modal('show');
	// 		}
	// 	});

	// 	e.preventDefault();
	// 	return false;
	// });

	// $(document).on('hide.bs.modal','#KiranaModals', function () {                 
 //    	if ($(".modal-footer").length > 0) {
	// 		$(".modal-footer").remove();
	// 	}
	// });

	// $(document).on("click", "#submit_perubahan", function (e) {
	// 	var empty_form = validate();
	// 	if (empty_form == 0) {
	// 		var isproses = $("input[name='isproses']").val();
	// 		if (isproses == 0) {
	// 			$("input[name='isproses']").val(1);
	// 			$("input[name='action']").val($(this).val());
	// 			var formData = new FormData($(".form-perubahan-plafon")[0]);

	// 			// $.ajax({
	// 			// 	url: baseURL + 'umb/master/save/plafon',
	// 			// 	type: 'POST',
	// 			// 	dataType: 'JSON',
	// 			// 	data: formData,
	// 			// 	contentType: false,
	// 			// 	cache: false,
	// 			// 	processData: false,
	// 			// 	success: function (data) {
	// 			// 		console.log(data);
	// 			// 		if (data.sts == 'OK') {
	// 			// 			kiranaAlert(data.sts, data.msg);
	// 			// 		} else {
	// 			// 			kiranaAlert(data.sts, data.msg, "error", "no");
	// 			// 			$("input[name='isproses']").val(0);
	// 			// 		}
	// 			// 	},
	// 			// 	complete: function () {
	// 			// 		$("input[name='isproses']").val(0);
	// 			// 	}
	// 			// });
	// 		} else {
	// 			kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
	// 		}
	// 	}
	// 	e.preventDefault();
	// 	return false;
	// });


});

function add_row(myelement) {
	$.ajax({
		url: baseURL + "umb/master/get/plant",
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			if (data.length > 0) {
				var output = "<tr>";
				output += '	<td width="25%">';
				output += '		<select name="plant_plafon[]" class="form-control select2" required="required">';
				output += '			<option value="0">Silahkan pilih pabrik</option>';
				$.each(data, function (i, v) {
					output += '		<option value="' + v.plant + '">' + v.plant_name + '</option>';
				})
				output += '		</select>';
				output += '	</td>';
				// output += '	<td width="25%">';
				// output += '		<input type="text" class="form-control text-right angka" name="ranger[]" required="required" min="0" value="0"/>';
				// output += '	</td>';
				// output += '	<td width="25%">';
				// output += '		<input type="text" class="form-control text-right angka" name="lain[]" required="required" min="0" value="0"/>';
				// output += '	</td>';
				output += '	<td width="25%">';
				output += '		<input type="text" class="form-control text-right angka" name="limit[]" required="required" min="0" value="0"/>';
				output += '	</td>';
				// output += '	<td>';
				// output += '	<button type="button" class="btn btn-sm btn-danger" id="file"><i class="fa fa-file"></i></button>';
				// output += '	</td>';
				output += '</tr>';
				$("#input-plafon-wrapper").append(output);
			} else {
				kiranaAlert("notOK", "Pabrik sudah terpilih semua", "warning", null);
			}
		},
		complete: function () {
			$(".select2").select2();
			if (myelement) {
				$.each(myelement, function (i, v) {
					$(v.element).val(v.value).trigger("change");
				});
			}
		}
	});
}
