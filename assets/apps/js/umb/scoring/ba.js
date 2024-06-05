$(document).ready(function () {
	//=======================================START TOP FORM========================================//
	show_form_tipe_picker();

	$(".my-datatable-extends-order").each(function () {
		generate_table(this);
	});

	$(".table-historical").DataTable();

	$('.datePicker').datepicker({
        format: 'dd.mm.yyyy',
        autoclose: true
	});
	
	$(document).on("changeDate", "#dari", function(e) {
		console.log($("select[name='supplier']").val());
		console.log($("select[name='depo']").val());
		
		if($("select[name='supplier']").val() !== null || $("select[name='depo']").val() !== '0' ){
			generate_historical();
		}
    });
    $(document).on("changeDate", "#sampai", function(e) {
        if($("select[name='supplier']").val() !== null || $("select[name='depo']").val() !== '0' ){
			generate_historical();
		}
    });

	$(document).on("click", ".change-um", function () {
		location.reload();
	});

	$(document).on("click", ".tipe_scoring", function () {
		$("input[name='tipe_scoring']").val($(this).data("text"));
		$("input[name='tipe_scoring_text']").val($(this).data("tipe"));
		$(".page-wrapper").show();
		$('#KiranaModals').modal('hide');
		$(".hide" + $(this).data("id")).addClass("hide-display");

		$(".hide" + $(this).data("id")).find("input, select , textarea").removeAttr("required");

		$("select[name='depo']").closest(".form-group").find("label").html("Depo");
		if ($(this).data("tipe") == "Ranger")
			$("select[name='depo']").closest(".form-group").find("label").html("Ranger");
	});

	$(document).on("change", "select[name='pabrik']", function () {
		
		if($(this).val() == 0){
			$(".sisa_plafons").html("");
			$("input[name='plafon_pabrik']").val("");
			$("input[name='no_form']").val("");
			$("select[name='supplier']").val(null).trigger("change");
			$("select[name='dirops']").val(null).trigger("change");
			$("select[name='depo']").val(null).trigger("change");
			$("input[name='supply_since']").val("");
			$("input[name='lama_join']").val("");
		}else{
			$.ajax({
				url: baseURL + 'umb/scoring/get/no-ba',
				type: 'POST',
				dataType: 'JSON',
				data: {
					tipe: $("input[name='tipe_scoring']").val(),
					plant: $(this).val(),
				},
				beforeSend: function () {
					var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
					$("body .overlay-wrapper").append(overlay);
				},
				success: function (data) {
					$(".sisa_plafons").html("*Sisa Plafon Pabrik : Rp. "+numberWithCommas(data.sisa_plafon));
					$("input[name='plafon_pabrik']").val(data.sisa_plafon);
					$("input[name='no_form']").val(data.no_ba);
					if ($("input[name='no_form']").val().split('/').shift() == "RG") {
						$("input[name='ktp_file[]']").removeAttr("required");
						// $("input[name='npwp_file[]']").removeAttr("required");
					}
					if(data.dirops){
						var output = '<option value="0">Silahkan pilih</option>';
						$.each(data.dirops, function (i, v) {
							output += '<option value="' + v.id + '">' + v.NAME1 + ' ( '+ v.EKORG +' )</option>';
						});
						$("select[name='dirops']").html(output);
					}
				},
				complete: function () {
					$("body .overlay-wrapper .overlay").remove();
				}
			});
			
			$("select[name='supplier']").val(null).trigger("change");
			var tipe_um = $("input[name='tipe_scoring_text']").val();
	
			$.ajax({
				url: baseURL + "umb/master/get/depo",
				type: 'POST',
				dataType: 'JSON',
				data: {
					plant: $(this).val(),
					tipe_um: tipe_um
				},
				success: function (data) {
					if (data) {
						var output = '<option value="0">Silahkan pilih</option>';
						$.each(data, function (i, v) {
							output += '<option value="' + v.DEPID + '" data-depnm="' + v.DEPNM + '">' + v.DEPNM + '</option>';
						});
						$("select[name='depo']").html(output);
					}
				},
				complete: function () {
				}
			});
		}

	});

	/*vendor*/
	$("select[name='supplier']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'umb/scoring/get/vendor',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					plant: $("select[name='pabrik']").val(),
					limit: 6
				};
			},
			processResults: function (data, page) {
				return {
					results: data.items
				};
			},
			cache: true
		},
		escapeMarkup: function (markup) {
			return markup;
		},
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.NAME1 + ' - [' + repo.LIFNR + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.NAME1 && repo.LIFNR) return repo.NAME1 + ' - [' + repo.LIFNR + ']';
			else return repo.NAME1;
		}
	});

	$.ajax({
		url: baseURL + 'umb/scoring/get/provinsi',
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			if (data) {
				var output = '';
				$.each(data, function (i, v) {
					output += '<option value="' + v.id + '">' + v.nama_provinsi + '</option>';
				});
				$("select[name='provinsi[]']").html(output);
			}
		}
	});

	/*kabupaten*/
	$("select[name='kabupaten[]']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'umb/scoring/get/kabupaten',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					provinsi_in: $("select[name='provinsi[]']").val()
				};
			},
			processResults: function (data, page) {
				// console.log(data);
				return {
					results: data.items
				};
			},
			cache: true
		},
		escapeMarkup: function (markup) {
			return markup;
		}, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama_kab + '</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.nama_kab) return repo.nama_kab;
			else return repo.nama_kab;
		}
	});

	$(document).on("change", "select[name='provinsi[]']", function () {
		$("select[name='kabupaten[]']").val(null).trigger("change");
	});

	$(document).on("change", "input[name='waktu']", function () {
		$("input[name='waktu_summary']").val($(this).val());
	});

	// ================================= END FORM ATAS ==========================================

	$(document).on("change", "select[name='supplier'], select[name='depo']", function () {
		resetForm();
		var supplier = $("select[name='supplier']").val();
		var depo = $("select[name='depo']").val();
		var tipe_um = $("input[name='tipe_scoring_text']").val();

		if ((supplier !== null || depo !== '0') && tipe_um !== "Ranger") {
			$.ajax({
				url: baseURL + 'umb/scoring/get/supply',
				type: 'POST',
				dataType: 'JSON',
				data: {
					plant: $("select[name='pabrik']").val(),
					supplier: $("select[name='supplier']").val(),
					depo: $("select[name='depo'] option:selected").data("depnm"),
					tipe: $("input[name='tipe_scoring']").val(),
					tanggal: $("input[name='tgl_pengajuan']").val()
				},
				beforeSend: function () {
					var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
					$("body .overlay-wrapper").append(overlay);
				},
				success: function (data) {
					if (data && data.supply) {
						
						if (data.first) {
							$.each(data.first, function (i, v) {
								$("input[name='supply_since']").val(generateDateFormat(v.BEDAT));
							});
							generate_lama_join();
						}
					}
					generate_historical();
				},
				complete: function () {
					$("body .overlay-wrapper .overlay").remove();
				}
			});
		}

		if(tipe_um == "Ranger"){
			generate_historical();
		}

	});

	$("input[name='tgl_pengajuan']").on("change", function () {
		generate_lama_join();
	});

	// $(document).on("change", ".berkas", function () {
	// 	ValidateSize(this, 0.8); // param 2 = size in MB
	// });

	//=======================================START SAVE FORM=======================================//
	$(document).on("click", "button[name='action_btn']", function (e) {		
        
        // kiranaAlert("notOK", "Fitur Berita Acara ini masih dalam proses development.", "warning", "no");
        // e.preventDefault();
        // return false;

		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {	
				$("input[name='isproses']").val(1);
				$("input[name='action']").val($(this).val());
				var formData = new FormData($(".form-ba-um")[0]);
				$.ajax({
					url: baseURL + "umb/scoring/save/ba",
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function () {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("body .overlay-wrapper").append(overlay);
					},
					success: function (data) {
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, "Data Berhasil ditambahkan", "success", baseURL + 'umb/scoring/detail/nonreguler/' + $("input[name='no_form']").val().replace(/\//g, "-"));							
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					error: function () {
						kiranaAlert("notOK", "Server Error", "error", "no");
					},
					complete: function () {
						$("body .overlay-wrapper .overlay").remove();
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

function show_form_tipe_picker() {
	$(".modal button.close").hide();
	$.ajax({
		url: baseURL + "umb/master/get/tipe-scoring",
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			if (data) {
				var elements = '<div class="text-center">';
				$.each(data, function (i, v) {
					elements += '<a class="btn btn-app tipe_scoring" data-id="' + (parseInt(i) + 1) + '" data-text="' + v.id + '" data-tipe="' + v.tipe_scoring + '">';
					elements += '	<i class="' + v.icon + '"></i> ' + v.tipe_scoring;
					elements += '</a>';
				});
				elements += '</div>';
				$('#KiranaModals .modal-title').html("Silahkan Pilih Tipe Scoring");
				$('#KiranaModals .modal-body').html(elements);
			}
		},
		complete: function () {
			$('#KiranaModals').modal({
				backdrop: 'static',
				keyboard: true,
				show: true
			});
		}
	});
}

function generate_lama_join() {
	if ($("input[name='tgl_pengajuan']").val().trim() !== "" && $("input[name='supply_since']").val().trim() !== "") {
		var pengajuan = $("input[name='tgl_pengajuan']").val().split(".");
		var supply = $("input[name='supply_since']").val().split(".");
		var toDate = new Date(pengajuan[2] + "-" + pengajuan[1] + "-" + pengajuan[0]);
		var fromDate = new Date(supply[2] + "-" + supply[1] + "-" + supply[0]);

		$("input[name='lama_join']").val(dateDiff('y', fromDate, toDate));
	}
}

function generate_historical() {

	var tanggal_awal = $("#dari").val();
	var tanggal_akhir = $("#sampai").val();

	if ($("input[name='no_form']").val().split('/').shift() == 'UMK'){
		$('.hs-nama').html($("select[name='supplier'] option:selected").html());
	}else{
		$('.hs-nama').html($("select[name='depo'] option:selected").data("depnm"));
	}

		
	$.ajax({
		url: baseURL + "umb/scoring/get/historical",
		type: 'POST',
		dataType: 'JSON',
		data: {
			tanggal_awal: tanggal_awal,
			tanggal_akhir: tanggal_akhir,
			plant: $("select[name='pabrik']").val(),
			supplier: $("select[name='supplier']").val(),
			depo: $("select[name='depo']").val(),
			deponm: $("select[name='depo'] option:selected").data("depnm"),
			tipe: $("input[name='tipe_scoring']").val()
		},
		success: function (data) {
			if (data.historical) {
				$('.table-hs').DataTable().destroy();
				var t = $('.table-hs').DataTable({
					columnDefs: [
						{"className": "text-right", "targets": 1},
						{"className": "text-right", "targets": 2},
						{"type": 'date-eu',"className": "text-center", "targets": 3},
						{"type": 'date-eu',"className": "text-center", "targets": 4},
					],
				});
				t.clear().draw();
				var plafon_awal;
				$.each(data.historical, function (i, v) {
					var um_setuju = (v.file_berita_acara !== null) ? v.um_minta : v.um_setuju;
					t.row.add([
						v.no_form_scoring,
						numberWithCommas(v.um_minta),
						numberWithCommas(um_setuju),
						generateDateFormat(v.tanggal_finish),
						generateDateFormat(v.tanggal_berakhir)
					]).draw(false);

					plafon_awal = numberWithCommas(um_setuju);
				});

				$("input[name='plafond_awal']").val(plafon_awal);
				
			}
			if (data.po) {
				var sum = data.sum_po;
				$('.table-historical').DataTable().destroy();
				var tt = $('.table-historical').DataTable({
					columnDefs: [
						{"type": 'date-eu',"className": "text-center", "targets": 1},
						{"className": "text-right", "targets": 2},
						{"className": "text-right", "targets": 3},
						{"className": "text-right", "targets": 4},
					]
				});
				tt.clear().draw();
				
				var tpo = 0;
				var tqty = 0;
				var tnpo = 0;
				var over_tpo = 0;
				var over_tnpo = 0;
				$.each(data.po, function (i, j) {
					var qty_kering = parseFloat(j.qty_kering).toFixed(2);
					var nilai_po = parseFloat(j.nilai_po).toFixed(2);

					if((nilai_po*1) > ($("input[name='plafond_awal']").val().replace(/,/g, "")*1)){
						over_tpo += 1;
						over_tnpo = (over_tnpo*1) + (nilai_po*1);
					}
					
					tt.row.add([
						j.bulan,
						generateDateFormat(j.tanggal_po),
						numberWithCommas(qty_kering),
						numberWithCommas(nilai_po),
						numberWithCommas(parseFloat(j.harga).toFixed(2))
					]).draw(false);
					tpo = i+1;
					tqty += qty_kering;
					tnpo += nilai_po;
				});
				$('.tpo').html(tpo+' PO');
				if(sum.sum_qty && sum.sum_npo){
					$('.tqty').html(numberWithCommas(Math.round(sum.sum_qty)));
					$('.tnpo').html(numberWithCommas(sum.sum_npo));
					$("input[name='avg_nilai_po']").val(numberWithCommas(parseFloat(sum.sum_npo/tpo).toFixed(2)));

					if(over_tnpo > 0){
						$("input[name='avg_nilai_po_over']").val(numberWithCommas(parseFloat(over_tnpo/over_tpo).toFixed(2)));
					}
				}
			}
		},
		complete: function () {
		}
	});
}

function resetForm() {
	resetDatatable('.my-datatable-extends-order.table-hs');
	resetDatatable('.table-historical');
	$('.hs-nama').html('Nama Supplier');
	$('.tpo').html('');
	$('.tqty').html('');
	$('.tnpo').html('');
	$("input[name='plafond_awal']").val('');
	$("input[name='avg_nilai_po']").val('');
}

function resetDatatable(elem) {
	var t = $(elem).DataTable();
	t.clear().draw();
}
