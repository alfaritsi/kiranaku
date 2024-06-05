$(document).ready(function () {
	get_data_provinsi();

	// console.log($("input[name='session_role_nama']").val());

	$(document).on("change", ".upload_file", function (e) {
		$(this).closest(".input-group").find(".caption_file").val(e.target.files[0].name);
		$(this).closest(".input-group").find(".caption_file").attr("title", e.target.files[0].name);
	});

	$(document).on("click", ".view_file", function () {
		if ($(this).data("link") !== "") {
			window.open(baseURL + $(this).data("link"), '_blank');
		} else {
			// kiranaAlert("notOK", "File Tidak Ditemukan", "warning", "no");
			var overlay = "<label class='err_msg' style='color:red;'>File tidak ditemukan</label>"; 
			if ($(".err_msg").length > 0) {
				$(".err_msg").remove();
			}
			if($(this).data("col") == '8'){
				$(this).closest(".col-sm-8").append(overlay);
			}else{
				$(this).closest(".col-sm-12").append(overlay);
			}
		}
	});

	$(document).on("click", ".btn_upload_file", function() {
		$(this).closest(".input-group-btn").find(".upload_file").click();
	});

	$(document).on("click", "#log_status", function() {
		$.ajax({
			url: baseURL + "umb/scoring/get/log-status",
			type: 'POST',
			dataType: 'JSON',
			data: {
				no_form: $("input[name='no_form']").val().replace(/\-/g, "/"),
			},
			beforeSend: function() {
				$('#KiranaModals .modal-title').html("Log Status Scoring");
        		$("#KiranaModals .modal-dialog").addClass("modal-lg");

				var elements = '<table class="table table-bordered table-modals">';
				elements += '	<thead>';
				elements += '		<th>No Form Scoring UM</th>';
				elements += '		<th>Tanggal Status</th>';
				elements += '		<th>Status</th>';
				elements += '		<th>Comment</th>';
				elements += '	</thead>';
				elements += '	<tbody></tbody>';
				elements += '</table>';
				$('#KiranaModals .modal-body').html(elements);
			},
			success: function(data) {
				if (data) {
					$('.table-modals').DataTable().destroy();
					var t = $('.table-modals').DataTable({
						scrollX: true
					});
					t.clear().draw();

					$.each(data, function(i, v) {
						var myrow = t.row.add([
							v.no_form_scoring,
							generateDatetimeFormat(v.tgl_status),
							"<span style='text-transform: capitalize'>" + v.action + "</span> oleh <br> <span class='label label-info'>" + v.nama_role + " : " + v.nama + "</label>",
							v.comment
						]).draw(false);
					});
				}
			},
			complete: function() {
				setTimeout(function() {
					adjustDatatableWidth();
				}, 1000);
				$('#KiranaModals').modal('show');
			}
		});
	});

	/*============LOAD DATA SCORING============*/
	$.ajax({
		url: baseURL + "umb/scoring/get/scoring",
		type: 'POST',
		dataType: 'JSON',
		data: {
			no_form: $("input[name='no_form']").val().replace(/\-/g, "/")
		},
		beforeSend: function() {
			var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
			$("body .overlay-wrapper").append(overlay);
		},
		success: function(data) {
			if (data) {
				$(".page-wrapper").show();

				//load scoring data
				$.each(data, function(i, v) {
					
					if (v.status == 'finish' && v.file_ceo_group !== null) {
						$("input[name='caption_file_ceo']").val(v.file_ceo_group.split('/').pop());
						$("#view_bukti_ceo").val(v.file_ceo_group);
						$(".hide_file_ceo").removeClass("hide");
					}

					if (v.no_sap !== null) {
						$("#no_sap").val(v.no_sap);
						$(".hide_no_sap").removeClass("hide");
					}

					//dirops ranger
					if(v.dirops !== null){
						$("select[name='dirops']").val(v.dirops).trigger("change");
					}

					if (v.status == 'stop' && v.file_stop_um !== null) {
						$("input[name='caption_file_stop_um']").val(v.file_stop_um.split('/').pop());
						$("#view_stop_um").val(v.file_stop_um);
						$(".hide_file_stop_um").removeClass("hide");
					}

					$(".hide" + v.scoring).addClass("hide-display");
					$(".hide" + v.scoring).find("input, select , textarea").removeAttr("required");
					$("input[name='tipe_scoring']").val(v.id_scoring_tipe);
                    if (v.tipe_scoring == "Ranger")
                        $("select[name='depo']").closest(".form-group").find("label").html("Ranger");

					$("select[name='pabrik']").val(v.plant).trigger("change.select2");
					get_data_depo(v.depo);
					generate_data_supply(v);

					//load supplier
					if (v.kode_supplier) {
						var array = [];
						var control = $("select[name='supplier']").empty().data("select2");
						var adapter = control.dataAdapter;
						array.push({
							"id": v.kode_supplier,
							"NAME1": v.nama_supplier,
							"LIFNR": v.supplier,
							"text": v.nama_supplier + ' - [' + v.supplier + ']'
						});
						adapter.addOptions(adapter.convertToOptions(array));
						$("select[name='supplier']").trigger('change');
						$("select[name='supplier']").val(v.kode_supplier).trigger('change.select2');
					}

					//load provinsi
					get_data_provinsi(v.provinsi);

					//load kab
					var array = [];
					$.each(v.kabupaten, function(x, y) {
						var control = $("select[name='kabupaten[]']").empty().data("select2");
						var adapter = control.dataAdapter;
						array.push({ "id": y, "nama_kab": v.kabupaten_nama[x] });
						adapter.addOptions(adapter.convertToOptions(array));
						$("select[name='kabupaten[]']").trigger('change');
					});
					$("select[name='kabupaten[]']").val(v.kabupaten).trigger('change');

					$("input[name='tgl_pengajuan']").val(generateDateFormat(v.tanggal));
					$("input[name='jarak_tempuh']").val((v.jarak_tempuh ? numberWithCommas(v.jarak_tempuh) : 0));

					// $("input[name='ktp_file_hidden']").val(v.file_ktp);
					if (v.file_ktp !== null) {
						$("input[name='caption_file_ktp']").val(v.file_ktp.split('/').pop());
						$("#view_ktp").val(v.file_ktp);
					}

					// $("input[name='npwp_file_hidden']").val(v.file_npwp);
					if (v.file_npwp !== null) {
						$("input[name='caption_file_npwp']").val(v.file_npwp.split('/').pop());
						$("#view_npwp").val(v.file_npwp);
					}

					if (v.file_ranger !== null) {
						$("input[name='caption_file_ranger']").val(v.file_ranger.split('/').pop());
						$("input[name='caption_file_ranger']").attr("title", v.file_ranger.split('/').pop());
						$("#view_ranger").attr("data-link", v.file_ranger);
					}

					$("input[name='um_propose']").val((v.um_minta ? numberWithCommas(v.um_minta) : 0));
					$("input[name='waktu']").val(v.waktu_selesai);

					//load kriteria
					if (v.kriteria) {
						// console.log(v.kriteria);
						$('.my-datatable-extends-order.table-kriteria').DataTable().destroy();
						var t = generate_table('.my-datatable-extends-order.table-kriteria');
						t.clear().draw();
						var total_bobot = 0, total_score = 0, datafoot = ["Subtotal"];

						$.each(v.kriteria, function (x, y) {
							var data_param = [];
							data_param.push(y.deskripsi);
							data_param.push(y.bobot+' %');

							total_bobot += parseFloat(y.bobot);
							if (x == (v.kriteria.length - 1)) {
								datafoot.push(parseFloat(total_bobot).toFixed(2)+' %');
							}

							// console.log(y.uom);
							var satuan = y.satuan == 'Persentase' ? ' %' : y.satuan; 

							var param = y.param.slice(0, -1).split(",");
							$.each(param, function (a, b) {
								var mydata = param[a].split("|");
								data_param.push(minusValue(mydata[1]) + ' - ' + minusValue(mydata[2]) + ' ' + satuan);
								if (x == (v.kriteria.length - 1)) {
									datafoot.push("");
								}
							});
							var nilai = parseFloat(y.nilai).toFixed(2);
							var score = parseFloat(y.score).toFixed(2);
							data_param.push(nilai);
							data_param.push(score);

							total_score += parseFloat(score);
							var myrow = t.row.add(data_param).draw(false).node();
							if (x == (v.kriteria.length - 1)) {
								datafoot.push("Total");
								datafoot.push(parseFloat(total_score).toFixed(2));
								t.row.add(datafoot).draw(false).node();
							}
						});
					}

					//load jaminan
					var total_all_jaminan = 0;
					$(".isJaminan").prop("checked",false).iCheck('update');
					$(".isJaminan").closest(".control-label").find("span").html("Tidak Ada");
					if (v.jaminan) {
						if(v.jaminan.length > 0) {
							$(".isJaminan").prop("checked", true).iCheck('update');
							$(".isJaminan").closest(".control-label").find("span").html("Ada");
						}else{
							$("a[href='#jaminan-tab']").parent("li").hide();
						}
						$.each(v.jaminan, function (x, y) {
							generate_data_jaminan(y, x);

							if (y.dokumen) {
								var output = '';
								$.each(y.dokumen, function (a, b) {
									var caption_file_doks = (b.file_location !== null ? b.file_location.split("/").pop() : 'Data tidak ditemukan');
									output += '<input type="text" name="jns_dok' + x + '[]" class="form-control readonly" readonly="readonly" value="' + b.jns_dokumen + '" required="required">';
									output += '<div class="input-group" style="margin-bottom: 10px">';
									output += '	<input type="text" name="caption_file_dok' + x + '[]" class="form-control readonly" required="required" value="'+caption_file_doks+'">';
									output += '	<div class="input-group-btn">';
									output += '		<input type="text" name="file_dok_hidden' + x + '[]" class="form-control hidden data-lihat-file" value="' + b.file_location + '">';
									output += '		<button type="button" class="btn btn-default btn-flat lihat-file" data-title="File ' + b.jns_dokumen + '" title="klik untuk lihat file"><i class="fa fa-search"></i></button>';
									output += '	</div>';
									output += '</div>';
								});

								$("#jaminan" + x + " .dokumen").html(output);
								$("#jaminan" + x + " .dokumen").closest(".form-group").show();
							}

							if (y.detail) {
								$.each(y.detail, function (a, b) {
									// console.log(b);
									var add_detail = generate_data_jaminan_detail(b, x, a);
									add_detail.promise().done(function (arg1) {
										get_data_jenis_jaminan_detail(b, x, a);
									});
									var nilai_jaminan = (b.nilai_jaminan ? numberWithCommas(b.nilai_jaminan) : 0);
									var nilai_appraisal = (b.nilai_appraisal ? numberWithCommas(b.nilai_appraisal) : 0);
									var hasil_appraisal = (b.hasil_appraisal ? numberWithCommas(b.hasil_appraisal) : "");
									var disc = parseFloat(((b.nilai_jaminan - b.nilai_appraisal) / b.nilai_jaminan) * 100).toFixed(2);
									$("input[name='nilai_jaminan" + x + "[]']").eq(a).val(nilai_jaminan);
									$("input[name='disc_jaminan" + x + "[]']").eq(a).val(disc);
									$("input[name='nilai_appraisal" + x + "[]']").eq(a).val(nilai_appraisal);
									$(".hasil_appraisal" + x +"_"+ a).val(hasil_appraisal);
									$("textarea[name='desc_jaminan" + x + "[]']").eq(a).val(b.desc);
									$("input[name='nama" + x + "[]']").eq(a).val(b.nama);
									$("input[name='dokumen_appraisal_hidden" + x + "[]']").eq(a).val(b.dokumen_location);
									var dokumen_locations = b.dokumen_location !== null ? b.dokumen_location.split('/').pop() : "Data tidak ditemukan";
									$("input[name='caption_dokumen_appraisal_hidden" + x + "[]']").eq(a).val(dokumen_locations);
								});
							}

							$("input[name='nilai_appraisal_penjamin[]']").eq(x).val((y.total_appraisal ? numberWithCommas(y.total_appraisal) : 0));
							total_all_jaminan += +y.total_appraisal;
						});
					}

					//load ranger
					if (v.fee_non_tax) {
						$("input[name='fee_non_tax']").val((v.fee_non_tax ? numberWithCommas(v.fee_non_tax) : 0));
						$("input[name='fee_non_tax']").closest(".form-group").find("input[name='fee_check']").iCheck('check');
					}
					if (v.fee_tax_gross) {
						$("input[name='fee_tax_gross']").val((v.fee_tax_gross ? numberWithCommas(v.fee_tax_gross) : 0));
						$("input[name='fee_tax_gross']").closest(".form-group").find("input[name='fee_check']").iCheck('check');
					}
					if (v.fee_non_gross) {
						$("input[name='fee_non_gross']").val((v.fee_non_gross ? numberWithCommas(v.fee_non_gross) : 0));
						$("input[name='fee_non_gross']").closest(".form-group").find("input[name='fee_check']").iCheck('check');
					}
					$("input[name='fee_non_tax']").closest(".form-group").find("input[name='fee_check']").attr("disabled", "disabled");
					$("input[name='fee_tax_gross']").closest(".form-group").find("input[name='fee_check']").attr("disabled", "disabled");
					$("input[name='fee_non_gross']").closest(".form-group").find("input[name='fee_check']").attr("disabled", "disabled");
					if (v.point_of_purch) $("select[name='pop']").val(v.point_of_purch).trigger("change");
					if (v.vendor_nonbkr) {
						var array = [];
						var control = $("select[name='vendor_nonbkr']").empty().data("select2");
						var adapter = control.dataAdapter;
						array.push({
							"id": v.kode_vendor_nonbkr,
							"NAME1": v.nama_vendor_nonbkr,
							"LIFNR": v.vendor_nonbkr,
							"text": v.nama_vendor_nonbkr + ' - [' + v.vendor_nonbkr + ']'
						});
						adapter.addOptions(adapter.convertToOptions(array));
						$("select[name='vendor_nonbkr']").trigger('change');
						$("select[name='vendor_nonbkr']").val(v.kode_vendor_nonbkr).trigger('change.select2');
					}

					generate_historical();

					$("input[name='waktu_summary']").val(v.waktu_selesai);
					$("input[name='um_propose_summary']").val((v.um_minta ? numberWithCommas(parseFloat(v.um_minta).toFixed(2)) : 0));
					$("input[name='um_scoring_summary']").val((v.um_scoring ? numberWithCommas(parseFloat(v.um_scoring).toFixed(2)) : 0));
					$("input[name='um_setuju_summary']").val((v.um_setuju ? numberWithCommas(parseFloat(v.um_setuju).toFixed(2)) : 0));
					$("input[name='um_rekom_summary']").val((v.um_rekom ? numberWithCommas(parseFloat(v.um_rekom).toFixed(2)) : 0));
					$("input[name='um_nilai_jaminan_summary']").val((total_all_jaminan ? numberWithCommas(parseFloat(total_all_jaminan).toFixed(2)) : 0));

					// var um_setujui = Math.min(v.um_minta, v.UM_scoring, total_all_jaminan);
					// $("input[name='um_setuju_summary']").val((um_setujui ? numberWithCommas(parseFloat(um_setujui).toFixed(2)) : 0));
										
				});
				
				if ( ($("input[name='session_role_nama']").val() == 'Direktur Operasional' && $("input[name='session_role_level']").val() == '2') && $("input[name='status_scoring']").val() == '2' ){
					for (var i = 0; i < $("input[name='flag_penilaian[]']").length; i++) {
						var s  = $("input[name='flag_penilaian[]").eq(i).val();
						if (s == "false"){
							$(".flag_penilaian_jaminan").hide();
							$(".notes_penilaian_jaminan").removeClass('hide');
						}
					}	
				}

				if (
					($("input[name='session_role_level']").val() == '6' && $("input[name='status_scoring']").val() == '6') ||
					($("input[name='session_role_level']").val() == '7' && $("input[name='status_scoring']").val() == '7') ||
					($("input[name='session_role_level']").val() == '71' && $("input[name='status_scoring']").val() == '71') 
				   ) 
				{	   
					//attachment ranger
					$("#btn-ranger").prop("disabled", false); 
				}

			}
		},
		complete: function () {
			$("body .overlay-wrapper .overlay").remove();
			$(".readonly").attr("readonly", "readonly");
			$("button.readonly").prop("disabled", true);
			$(".select2.readonly").prop("disabled", true);
			$("input[type='file'].readonly").prop("disabled", true);
		}
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


	$(document).on("change", "select[name='pabrik']", function () {
		$("select[name='supplier']").val(null).trigger("change");
		resetForm();

		$.ajax({
			url: baseURL + "umb/master/get/depo",
			type: 'POST',
			dataType: 'JSON',
			data: {
				plant: $(this).val()
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
	});

	/*============SUPPLIER============*/
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

	$("select[name='vendor_nonbkr']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'umb/scoring/get/vendor-nonbkr',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					plant: $("select[name='pabrik']").val()
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

	/*============KABUPATEN============*/
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

	generate_lama_join();

	$(document).on("click", ".lihat-file", function () {
		var link = $(this).closest(".input-group-btn").find(".data-lihat-file").val();
		var ext = (link !== "" ? link.split('.').pop() : null);

		$('#KiranaModals .modal-title').html($(this).data("title"));

		var output = '';
		switch (ext) {
			case 'pdf' :
				output += showPdf(link);
				break;
			case 'png' :
			case 'jpg' :
				output += '<img class="img-responsive" style="margin: 0 auto;" alt="Photo" src="' + baseURL + link + '">';
				break;
		}

		if (output == "") {
			kiranaAlert("notOK", "File tidak ditemukan", "error", "no");
		} else {
			$('#KiranaModals .modal-body').html(output);

			$('#KiranaModals').modal({
				backdrop: 'static',
				keyboard: true,
				show: true
			});

			setTimeout(function(){

				// console.log($('#iframe').contents());
			},100);
		}
	});

	$(document).on("change", "select[name='provinsi[]']", function () {
		$("select[name='kabupaten[]']").val(null).trigger("change");
	});

	//=======================================START MIDDLE FORM=======================================//
	//-------------------------JAMINAN-------------------------//
	$(document).on("click", ".collapsible-toogle", function (e) {
		if ($(this).attr("aria-expanded") === "true") {
			$(this).html("Hide");
		} else {
			$(this).html("Show");
		}
	});


	$(document).on("change", ".jenis_jaminan", function () {
		var elem = $(this).closest(".panel-collapse");
		var group = $(elem).attr("id");
		var rowdetail = $(this).closest(".row-detail-jaminan").data("rowdetail");
		var rowjaminan = $(this).closest(".row-detail-jaminan").data("rowjaminan");

		$.ajax({
			url: baseURL + "umb/master/get/jaminan-detail",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mjaminan_header: $(this).val()
			},
			beforeSend: function () {
				var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
				$("body .overlay-wrapper").append(overlay);
			},
			success: function (data) {
				if (data) {
					var output = '<option value="0">Silahkan Pilih</option>';
					$.each(data, function (i, v) {
						output += '<option value="' + v.id_mjaminan_detail + '" data-disc="' + v.persen_discount + '">' + v.detail + '</option>';
					});
					$("#" + group + " .table-detail-jaminan tbody .row-detail-jaminan").eq(rowdetail).find("select[name='detail_jaminan" + rowjaminan + "[]']").html(output);
					$(".row-detail-jaminan .select2").select2();
				}
			},
			complete: function () {
				$("body .overlay-wrapper .overlay").remove();
			}
		});
	});

	$(document).on("change", ".nilai_jaminan , .detail_jaminan_select", function () {
		var nilai = $(this).closest(".row-detail-jaminan").find(".nilai_jaminan").val().replace(/,/g, "");
		var elem = $(this).closest(".panel-collapse");
		var group = $(elem).attr("id");
		var rowjaminan = $(this).closest(".row-detail-jaminan").data("rowjaminan");
		var rowdetail = $(this).closest(".row-detail-jaminan").data("rowdetail");

		//set disc input
		var disc_set = $(this).closest(".row-detail-jaminan").find(".detail_jaminan_select option:selected", this).data("disc");
		$("#" + group + " .table-detail-jaminan tbody .row-detail-jaminan").eq(rowdetail).find("input[name='disc_jaminan" + rowjaminan + "[]']").val(disc_set);

		//get disc input
		var disc_get = $(this).closest(".row-detail-jaminan").find(".disc_jaminan" + rowdetail).val().replace(/,/g, "");

		$(this).closest(".row-detail-jaminan").find(".nilai_appraisal" + rowdetail).val(numberWithCommas(nilai - (nilai * disc_get) / 100));
		var total_row = 0;
		$(this).closest(".row-jaminan").find("input[name='nilai_appraisal" + rowjaminan + "[]']").each(function () {
			total_row += +$(this).val().replace(/,/g, "");
		});
		$(this).closest(".row-jaminan").find("input[name='nilai_appraisal_penjamin[]']").val(numberWithCommas(total_row));
	});

	$(document).on("click", "button[name='nilai_aset_jaminan']", function () {
		// var rowjaminan = $(this).closest(".row-detail-jaminan").data("rowjaminan");
		// var rowdetail = $(this).closest(".row-detail-jaminan").data("rowdetail");
		// var jenis = $(this).closest(".row-detail-jaminan").find(".jenis_jaminan option:selected").text();
		// if (jenis !== "Silahkan Pilih") {
		// 	// console.log(rowjaminan + " => " + rowdetail);
		// 	show_form_nilai_aset_jaminan(rowdetail, rowjaminan, jenis, this);
		// } else {
		// 	kiranaAlert("notOK", "Silahkan pilih jenis jaminan", "error", "no");
		// }

		var rowjaminan = $(this).closest(".row-detail-jaminan").data("rowjaminan");
		var rowdetail = $(this).closest(".row-detail-jaminan").data("rowdetail");
		var id_header = $(this).closest(".row-detail-jaminan").data("id_header");
		var id_detail = $(this).closest(".row-detail-jaminan").data("id_detail");
		var jenis = $(this).closest(".row-detail-jaminan").find(".jenis_jaminan option:selected").text();


		$.ajax({
			url: baseURL + "umb/scoring/get/jaminan-nilai",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_scoring_jaminan_header: id_header,
				id_scoring_jaminan_detail: id_detail
			},
			success: function (data) {
				// if (( ($("input[name='session_role_nama']").val() == 'Finance Controller HO Div Head' && $("input[name='session_role_level']").val() == '7' ) || 
					  // ($("input[name='session_role_nama']").val() == 'Finance Controller HO Dept Head' && $("input[name='session_role_level']").val() == '71')) && 
					 // $("input[name='status_scoring']").val() == $("input[name='session_role_level']").val()) {
				if ( ( $("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1') &&
		 			($("input[name='status_scoring']").val() == '1' || $("input[name='status_scoring']").val() == '2') ) {
					show_form_nilai_aset_jaminan(rowdetail, rowjaminan, jenis, this, data, id_header, id_detail);
				}else{
					if (data) {
						show_form_nilai_aset_jaminan(rowdetail, rowjaminan, jenis, this, data, id_header, id_detail);	
					}else{
						kiranaAlert("notOK", "Belum ada data", "warning", "no");
					}
				}
			}
		});



	});

	$(document).on("ifChanged", "#KiranaModals .checkbox-nilai-aset", function () {
		var nama = $(this).data("value");
		var check = $(this).prop("checked");
		switch (nama) {
			case "isWawancara" :
				if (check == true){
					$(".wawancara-tab").show();

                    var id = $(this).closest(".tab-content").find(".tab-pane").attr("id");
                    id = id.replace("general","wawancara");
                    // $("#"+id+" input:not(input[type=hidden])").attr("required","required");
                    // $("#"+id+" select").attr("required","required");
                    $("#"+id+" tbody tr").each(function(i, v){
                    	$("td:nth-child(2) input:not(input[type=hidden])", v).attr("required","required");
					});
                }
				else {
					$(".wawancara-tab").hide();

                    var id = $(this).closest(".tab-content").find(".tab-pane").attr("id");
                    id = id.replace("general","wawancara");
                    $("#"+id+" input").removeAttr("required");
                    $("#"+id+" select").removeAttr("required");
                }
				break;
			case "isAnalisaDesktop" :
				if (check == true) {
                    $(".analisa-tab").show();

                    var id = $(this).closest(".tab-content").find(".tab-pane").attr("id");
                    id = id.replace("general","analisa");
                    // $("#"+id+" input:not(input[type=hidden])").attr("required","required");
                    // $("#"+id+" select").attr("required","required");
                    $("#"+id+" tbody tr").each(function(i, v){
                        $("td:nth-child(2) input:not(input[type=hidden],input[type=file])", v).attr("required","required");
                    });
                }
				else{
                    $(".analisa-tab").hide();

                    var id = $(this).closest(".tab-content").find(".tab-pane").attr("id");
                    id = id.replace("general","analisa");
                    $("#"+id+" input").removeAttr("required");
                    $("#"+id+" select").removeAttr("required");
				}
				break;
		}
	});

	$(document).on("change", ".berkas", function () {
		ValidateSize(this, 0.8); // param 2 = size in MB
	});

	$(document).on("click", "button[name='nilai_aset_jaminan_btn']", function (e) {
		var rowdetail = $(this).data("rowdetail");
		var rowjaminan = $(this).data("rowjaminan");
			
		//validasi harus pilih minimal satu diantara wawancara/analisa
		if ($(".ckwawancara").prop("checked") == false && $(".ckanalisa").prop("checked") == false) {
			kiranaAlert("notOK", "Mohon untuk memilih minimal satu diantara Wawancara/Analisa Desktop", "warning", "no");
			e.preventDefault();
			return false;	
		}
			
		var empty_form = validate(".form-penilaian-jaminan");
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-penilaian-jaminan")[0]);
				$.ajax({
					url: baseURL + "umb/scoring/save/nilai/jaminan",
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function () {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("#KiranaModals .modal-content").append(overlay);  
						// $("body .overlay-wrapper").append(overlay); 
					},
					success: function (data) {
						if (data.sts == 'OK') {
							$("input[name='penilaian"+rowjaminan+"_"+rowdetail+"']").val("true");
							$("input[name='penilaian"+rowjaminan+"_"+rowdetail+"']").closest(".text-center").find(".btn-sm").html("<i class='fa fa-check' style='padding-right:5px;'></i> Edit Penilaian");
							$("input[name='penilaian"+rowjaminan+"_"+rowdetail+"']").closest(".text-center").find(".btn-sm").removeClass("btn-primary");
							$("input[name='penilaian"+rowjaminan+"_"+rowdetail+"']").closest(".text-center").find(".btn-sm").addClass("btn-success");
							$('#KiranaModals').modal('hide');
							$("input[name='isproses']").val(0);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					complete: function () {
						// $("body .overlay-wrapper .overlay").remove();
						$("#KiranaModals .modal-content .overlay").remove();
					}
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
	});

	$(document).on("click", "button[name='action_legal']", function () {
		var rowjaminan = $(this).closest(".row-detail-jaminan").data("rowjaminan");
		var rowdetail = $(this).closest(".row-detail-jaminan").data("rowdetail");
		var id_scoring_jaminan_detail = $(this).closest(".row-detail-jaminan").attr('data-id_detail');
		var value = $(this).val() == 'y' ? 'y' : 'n';
		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			$.ajax({
				url: baseURL + "umb/scoring/save/legal",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_scoring_jaminan_detail: id_scoring_jaminan_detail,
					rekomendasi_legal: value
				},
				success: function (data) {
					if(data.sts == 'OK'){
						var span = '<span class="label '+(value !== null ? (value == 'y' ? "label-success" : "label-danger") : "label-default")+' rekom_legal' +rowjaminan+'_'+ rowdetail + '">'+(value !== null ? (value == 'y' ? "VALID" : "INVALID") : "WAITING")+'</span>';
						$('.rekom_legal'+rowjaminan+'_'+rowdetail).closest("td").find($("input[name='cek_legal[]")).val(value == 'y' ? "VALID" : "INVALID");
						$('.rekom_legal'+rowjaminan+'_'+rowdetail).closest("td").find("span").remove();
						$('.td_legal'+rowjaminan+'_'+rowdetail).append(span);
						kiranaAlert(data.sts, data.msg, "success", "no");
						$("input[name='isproses']").val(0);
					}else{
						kiranaAlert("notOK", data.msg, "error", "no");
						$("input[name='isproses']").val(0);
					}
				}
			});
		} else {
			kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
		}
	});

	// $(document).on("click", "button[name='action_jaminan']", function () {
	// 	var rowjaminan = $(this).closest(".row-detail-jaminan").data("rowjaminan");
	// 	var rowdetail = $(this).closest(".row-detail-jaminan").data("rowdetail");
	// 	var id_scoring_jaminan_detail = $(this).closest(".row-detail-jaminan").attr('data-id_detail');
	// 	var id_scoring_jaminan_header = $(this).closest(".row-detail-jaminan").attr('data-id_header');
	// 	// var file_attach = 


	// 	if ($("input[name='penilaian"+rowjaminan+"_"+rowdetail+"']").val() == 'false') {
	// 		kiranaAlert("notOK", "Mohon berikan Penilaian untuk Jaminan ini terlebih dahulu", "warning", "no");
	// 	// } else if (){
	// 	// 	kiranaAlert("notOK", "Silahkan Klik tombol Revisi untuk mengubah hasil yang sudah pernah ditambahkan", "warning", "no");
	// 	} else{
	// 		var nilai_appraisal = $(".hasil_appraisal" +rowjaminan+"_"+ rowdetail).closest(".row-detail-jaminan").find(".nilai_appraisal" + rowdetail).val();
	// 		if ($(this).val() == "ok") {
	// 			var isproses = $("input[name='isproses']").val();
	// 			if (isproses == 0) {
	// 				$("input[name='isproses']").val(1);
	// 				$.ajax({
	// 					url: baseURL + "umb/scoring/save/appraisal",
	// 					type: 'POST',
	// 					dataType: 'JSON',
	// 					data: {
	// 						id_scoring_jaminan_detail: id_scoring_jaminan_detail,
	// 						id_scoring_jaminan_header: id_scoring_jaminan_header,
	// 						hasil_appraisal: nilai_appraisal.replace(/\,/g,'')
	// 					},
	// 					success: function (data) {
	// 						if(data.sts == 'OK'){
	// 							$(".hasil_appraisal" +rowjaminan+"_"+ rowdetail).closest(".row-jaminan").find($("input[name='nilai_appraisal_penjamin[]")).val(numberWithCommas(data.total_appraisal));
	// 							$(".hasil_appraisal" +rowjaminan+"_"+ rowdetail).closest(".row-detail-jaminan").find(".hasil_appraisal" +rowjaminan+"_"+ rowdetail).val(nilai_appraisal);
	// 							$("input[name='um_nilai_jaminan_summary']").val(numberWithCommas(data.um_jaminan));
	// 							if ($("input[name='no_form']").val().split('/').shift() !== 'DMT') {
	// 								$("input[name='um_setuju_summary']").val(numberWithCommas(data.um_setuju));
	// 							}
	// 							kiranaAlert(data.sts, data.msg, "success", "no");
	// 							$("input[name='isproses']").val(0);
	// 						}else{
	// 							kiranaAlert("notOK", data.msg, "error", "no");
	// 							$("input[name='isproses']").val(0);
	// 						}
	// 					}
	// 				});
	// 			} else {
	// 				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
	// 			}
	// 		} else {
	// 			show_form_revised(rowdetail, rowjaminan, id_scoring_jaminan_header, id_scoring_jaminan_detail, nilai_appraisal);
	// 		}
	// 	}

	// });

	$(document).on("click", "button[name='action_jaminan']", function () {
		var rowjaminan = $(this).closest(".row-detail-jaminan").data("rowjaminan");
		var rowdetail = $(this).closest(".row-detail-jaminan").data("rowdetail");
		var id_scoring_jaminan_detail = $(this).closest(".row-detail-jaminan").attr('data-id_detail');
		var id_scoring_jaminan_header = $(this).closest(".row-detail-jaminan").attr('data-id_header');
		
		if ($("input[name='penilaian"+rowjaminan+"_"+rowdetail+"']").val() == 'false') {
			kiranaAlert("notOK", "Mohon berikan Penilaian untuk Jaminan ini terlebih dahulu", "warning", "no");
		} else{
			var nilai_appraisal = $(".hasil_appraisal" +rowjaminan+"_"+ rowdetail).closest(".row-detail-jaminan").find(".nilai_appraisal" + rowdetail).val();
			if ($(this).val() == "ok") {
				show_form_revised(rowdetail, rowjaminan, id_scoring_jaminan_header, id_scoring_jaminan_detail, nilai_appraisal, 'ok');
			} else {
				show_form_revised(rowdetail, rowjaminan, id_scoring_jaminan_header, id_scoring_jaminan_detail, nilai_appraisal, 'revised');
			}
		}

	});

	$(document).on("click", "button[name='revisi_btn']", function() {
		var rowdetail = $(this).data("rowdetail");
		var rowjaminan = $(this).data("rowjaminan");
		var nilai_revisi = $("input[name='value_revisi']").val();

		var empty_form = validate(".form-revisi-appraisal");
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-revisi-appraisal")[0]);
				$.ajax({
					url: baseURL + "umb/scoring/save/revisi",
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function() {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("#KiranaModals .modal-content").append(overlay);  
						// $("body .overlay-wrapper").append(overlay); 
					},
					success: function(data) {
						if (data.sts == 'OK') {
							$(".hasil_appraisal" + rowjaminan+ "_"+ rowdetail).closest(".row-jaminan").find($("input[name='nilai_appraisal_penjamin[]")).val(numberWithCommas(data.total_appraisal));
							// $(".hasil_appraisal" +rowjaminan+"_"+ rowdetail).closest(".row-detail-jaminan").find(".hasil_appraisal" +rowjaminan+"_"+ rowdetail).val(nilai_revisi);
							$(".hasil_appraisal" + rowjaminan+ "_"+ rowdetail).closest('td').empty().html('<div class="input-group" style="margin-bottom: 10px"><input type="text" class="form-control hasil_appraisal' +rowjaminan+'_'+ rowdetail + ' readonly" name="hasil_appraisal[]" value="'+nilai_revisi+'" readonly="readonly"><div class="input-group-btn"><input type="text" name="dokumen_revisi_hidden' +rowjaminan+'_'+ rowdetail + '" value="'+data.file_attachment+'" class="form-control hidden data-lihat-file readonly"><button type="button" class="btn btn-default btn-flat lihat-file" data-title="File Dokumen Jaminan" title="klik untuk lihat file"><i class="fa fa-search"></i></button></div></div>');
							$("input[name='um_nilai_jaminan_summary']").val(numberWithCommas(data.um_jaminan));
							if ($("input[name='no_form']").val().split('/').shift() !== 'DMT') {
								$("input[name='um_setuju_summary']").val(numberWithCommas(data.um_setuju));
							}
							$('#KiranaModals').modal('hide');
							$("input[name='isproses']").val(0);
						} else {
							var overlay = "<label class='label_error pull-left' style='color:red;'>Gagal melakukan perubahan</label>"; 
							if ($(".label_error").length > 0) {
								$(".label_error").remove();
							}
							$(this).closest(".form-group").append(overlay);
							$("input[name='isproses']").val(0);
						}
					},
					error: function () {
						kiranaAlert("notOK", "Server Error", "error", "no");
					},
					complete: function () {
						$("#KiranaModals .modal-content .overlay").remove();
					}
				});
			} else {
				var overlay = "<label class='label_error pull-left' style='color:red;'>Silahkan tunggu proses selesai</label>"; 
				if ($(".label_error").length > 0) {
					$(".label_error").remove();
				}
				$(this).closest(".form-group").append(overlay);
			}
		}
	});

	$(document).on("change", "select[name='status_penjamin[]'] , select[name='kepemilikan[]']", function () {
		var elem = $(this).closest(".panel-collapse");
		var group = $(elem).attr("id");
		var rowjaminan = $(this).closest(".panel-collapse").data("row");

		var status_penjamin = $("#" + group + " select[name='status_penjamin[]").val();
		var kepemilikan = $("#" + group + " select[name='kepemilikan[]").val();

		$.ajax({
			url: baseURL + "umb/master/get/dokumen",
			type: 'POST',
			dataType: 'JSON',
			data: {
				status: status_penjamin,
				kepemilikan: kepemilikan
			},
			success: function (data) {
				// console.log(data);
				if (data) {
					$.each(data, function (i, v) {
						var dok = v.document.split(",");

						var output = "";
						$.each(dok, function (idx, val) {
							output += '<input type="text" name="jns_dok' + rowjaminan + '[]" class="form-control readonly" readonly="readonly" value="' + val + '" required="required">';
							output += '<input type="file" name="file_dok' + rowjaminan + '[]" class="form-control berkas readonly" style="margin-bottom: 10px" required="required">';
						});

						$("#" + group + " .dokumen").html(output);
						$("#" + group + " .dokumen").closest(".form-group").show();
					});
				}
			}
		});
	});

	$('.kiranaCheckbox').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%' // optional
	});

	$(document).on("ifChanged", ".kiranaCheckbox", function() {
		var elem = $(this).closest(".form-group").find("input");
		if ($(this).prop("checked") == true) {
			$(elem).removeAttr("readonly");
			$(elem).attr("required", true);
		} else {
			$(elem).val("");
			$(elem).attr("readonly", "readonly");
			$(elem).removeAttr("required");
		}
	});

	//=======================================START BOTTOM FORM=======================================//
	$(document).on("change", "input[name='waktu']", function () {
		$("input[name='waktu_summary']").val($(this).val());
	});

	$(document).on("change", "input[name='um_propose']", function () {
		$("input[name='um_propose_summary']").val($(this).val());
	});

	$(document).on("click", "button[name='action_btn']", function (e) {
		
		if ( $(this).val() == "approve" && ($("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1') && $("input[name='status_scoring']").val() == '1' ){
			for (var i = 0; i < $("input[name='flag_penilaian[]']").length; i++) {
				var s  = $("input[name='flag_penilaian[]").eq(i).val();
				if (s == "false"){
					kiranaAlert("notOK", "Mohon Lengkapi Form Penilaian Jaminan Terlebih dahulu.", "warning", "no");
					e.preventDefault();
					return false;
				}
			}	
		}

		//kalau dept/div fincon approve harus cek hasil appraisal dulu
		if ($(this).val() == "approve" && ( ($("input[name='session_role_nama']").val() == 'Finance Controller HO Div Head' && $("input[name='session_role_level']").val() == '7') || 
		  ($("input[name='session_role_nama']").val() == 'Finance Controller HO Dept Head' && $("input[name='session_role_level']").val() == '71') ) ){
			for (var i = 0; i < $("input[name='hasil_appraisal[]']").length; i++) {
				var s  = $("input[name='hasil_appraisal[]").eq(i).val();
				if (s < 1){
					kiranaAlert("notOK", "Mohon Lengkapi Form Penilaian Jaminan Terlebih dahulu.", "warning", "no");
					e.preventDefault();
					return false;
				}
			}	
		}

		if ($(this).val() == "approve" && ( ($("input[name='session_role_nama']").val() == 'Legal HO Div Head' && $("input[name='session_role_level']").val() == '5') || 
		  ($("input[name='session_role_nama']").val() == 'Legal HO Dept Head' && $("input[name='session_role_level']").val() == '51') ) ){
			for (var i = 0; i < $("input[name='cek_legal[]']").length; i++) {
				var l  = $("input[name='cek_legal[]").eq(i).val();
				if (l !== 'VALID' && l !== 'INVALID'){
					kiranaAlert("notOK", "Mohon berikan rekomendasi terkait legalitas pada setiap Dokumen Jaminan terlebih dahulu.", "warning", "no");
					e.preventDefault();
					return false;
				}
			}	
		}

		$('#KiranaModals .modal-title').css("text-transform", "capitalize");
		$('#KiranaModals .modal-title').html($(this).val() + " Scoring UM");
		var no_form_scoring = $("input[name='no_form']").val();
		var required = "";
		if ($(this).val() !== "approve" && $(this).val() !== "assign") {
			required = "required";
		}

		switch ($(this).val()) {
			case "approve" :
				$("#KiranaModals").removeAttr("class");
				$("#KiranaModals").addClass("modal");
				$("#KiranaModals").addClass("modal-success");
				break;
			case "decline" :
				$("#KiranaModals").removeAttr("class");
				$("#KiranaModals").addClass("modal");
				$("#KiranaModals").addClass("modal-warning");
				break;
			case "assign" :
				$("#KiranaModals").removeAttr("class");
				$("#KiranaModals").addClass("modal");
				$("#KiranaModals").addClass("modal-info");
				break;
			case "stop" :
			case "drop" :
				$("#KiranaModals").removeAttr("class");
				$("#KiranaModals").addClass("modal");
				$("#KiranaModals").addClass("modal-danger");
				break;
		}

		var rekom_value = "";
		if ( ($("input[name='session_role_nama']").val() == 'Finance Controller HO Div Head' && $("input[name='session_role_level']").val() == '7' ) || 
		  ($("input[name='session_role_nama']").val() == 'Finance Controller HO Dept Head' && $("input[name='session_role_level']").val() == '71')){
			var tipescoring = $("input[name='no_form']").val().split('/').shift();
			rekom_value = (tipescoring == "UMK" || tipescoring == "DM" || tipescoring == "DMT") ? $("input[name='um_nilai_jaminan_summary']").val() : "0"; 
		}

		var output = '';
		output += '<form class="form-approval-scoring-um" enctype="multipart/form-data">';
		output += '	<div class="modal-body">';
		output += '		<div class="form-horizontal">';
		output += '			<div class="form-group">';
		output += '				<label for="komentar" class="col-sm-12 control-label text-left">Komentar</label>';
		output += '				<div class="col-sm-12">';
		output += '					<textarea class="form-control" name="komentar" ' + required + '></textarea>';
		output += '				</div>';
		output += '			</div>';
		if ($(this).val() == "approve" && $("input[name='session_role_isRekom']").val() == "1") {
			output += '			<div class="form-group">';
			if ( $("input[name='session_role_level']").val() == '9' && $("input[name='status_scoring']").val() == '9') {
				output += '				<label for="attachment" class="col-sm-12 control-label text-left">Uang Muka yang diberikan</label>';
			}else{
				output += '				<label for="attachment" class="col-sm-12 control-label text-left">Rekomendasi UM</label>';
			}
			output += '				<div class="col-sm-12">';
			output += '					<input type="text" class="form-control angka" min="0" required="required" value="'+rekom_value+'" name="rekom_um_app">';
			output += '				</div>';
			output += '			</div>';
		}
		if ($(this).val() == "stop") {
			output += '			<div class="form-group">';
			output += '				<label for="attachment" class="col-sm-12 control-label text-left">Dokumen Pengakhiran Kerjasama</label>';
			output += '				<div class="col-sm-12">';
			output += '					<input type="file" class="form-control berkas" name="stop_um[]" required="required"/>';
			output += '				</div>';
			output += '			</div>';
		}
		output += '		</div>';
		output += '	</div>';
		output += '	<div class="modal-footer">';
		output += '		<div class="form-group">';
		output += '			<input type="hidden" name="action" value="' + $(this).val() + '">';
		output += '			<input type="hidden" name="no_form_scoring" value="' + no_form_scoring + '">';
		output += '			<button type="button" class="btn btn-primary" name="submit-form-approval-scoring-um">Submit</button>';
		output += '		</div>';
		output += '	</div>';
		output += '</form>';

		$('#KiranaModals .modal-body').remove();
		$('#KiranaModals .modal-footer').remove();
		$('#KiranaModals form').remove();
		$('#KiranaModals .modal-content').append(output);

		$('#KiranaModals').modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});
	});

	$(document).on("click", "button[name='submit-form-approval-scoring-um']", function () {
		var empty_form = validate(".form-approval-scoring-um");
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-approval-scoring-um")[0]);

				if (
					($("input[name='session_role_level']").val() == '6' && $("input[name='status_scoring']").val() == '6') ||
					($("input[name='session_role_level']").val() == '7' && $("input[name='status_scoring']").val() == '7') ||
					($("input[name='session_role_level']").val() == '71' && $("input[name='status_scoring']").val() == '71') 
				   ) 
				{	   
					formData.append('ranger_file[]', $('input[type=file]')[0].files[0]); 
				}

				$.ajax({
					url: baseURL + "umb/scoring/save/approval",
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function () {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("#KiranaModals .modal-content").append(overlay);  
						// $("body .overlay-wrapper").append(overlay); 
					},
					success: function (data) {
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					error: function () {
						kiranaAlert("notOK", "Server Error", "error", "no");
					},
					complete: function () {
						$("#KiranaModals .modal-content .overlay").remove();
					}
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
	});

	$(document).on("click", "#upload_form", function () {

	//=============== MODAL UNTUK SECRETARY UPLOAD========================
        $("#KiranaModals .modal-dialog").addClass("modal-success");
		$('#KiranaModals .modal-title').html("<strong>Approval CEO Group</strong>");
		var elements ='<form class="form-upload-secretary" enctype="multipart/form-data">';
		elements += '<div class="form-group"><label>Dokumen Otentik CEO Group</label>';
		elements += '<input type="file" class="form-control berkas" name="file_ceo_group[]" required="required"/>';
		elements += '		</div>';
		elements += '<div class="form-group"><label>Uang Muka yang disetujui</label>';
		elements += '	<input type="text" class="form-control text-right angka" name="rekom_um_app" required="required" min="0" value="0"/>';
		elements += '</div>';
		elements += '<div class="form-group"><label>Persetujuan CEO Group</label>';
		elements += '	<select class="form-control select2" name="action" id="action" required="required">';
		elements += '		<option value="approve">Approve</option>';
		elements += '		<option value="drop">Drop</option>';
		elements += '	</select>';
		elements += '</div>';
		elements += '<div class="form-group"><label>Komentar</label>';
		elements += '	<textarea class="form-control" name="komentar" required="required"></textarea>';
		elements += '	<input type="hidden" name="no_form_scoring" value="'+$("input[name='no_form']").val()+'">';
		elements += '</div></form>';
		
		var output_footer = '<div class="modal-footer">';
		output_footer += '	<div class="form-group">';
		output_footer += '		<button type="button" class="btn btn-primary" name="submit-form-upload-secretary">Submit</button>';
		output_footer += '	</div>';
		output_footer += '</div>';						
								
		$('#KiranaModals .modal-body').html(elements);
		if ($(".modal-footer").length > 0) {
			$(".modal-footer").remove();
		}
		$('#KiranaModals .modal-content').append(output_footer);

		$('#KiranaModals').modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});

		$("#KiranaModals .select2").select2({
			dropdownParent: $('#KiranaModals')
		});
	});

	$(document).on("click", "button[name='submit-form-upload-secretary']", function () {
		var empty_form = validate(".form-upload-secretary");
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-upload-secretary")[0]);
				$.ajax({
					url: baseURL + "umb/scoring/save/approval",
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function () {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("#KiranaModals .modal-content").append(overlay);  
						// $("body .overlay-wrapper").append(overlay); 
					},
					success: function (data) {
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					error: function () {
						kiranaAlert("notOK", "Server Error", "error", "no");
					},
					complete: function () {
						$("#KiranaModals .modal-content .overlay").remove();
					}
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
	});

	$(document).on("click", "#upload_mou", function () {
		var level = $("input[name='session_role_level']").val();
		var no_form_scoring = $("input[name='no_form']").val();
		var status_mou = $("input[name='status_mou']").val();

	// console.log("level = "+level+"----"+"no_form_scoring = "+no_form_scoring+"----"+"status_mou = "+status_mou);

	//=============== MODAL UNTUK MOU========================
		$('#KiranaModals .modal-title').html("<strong>Form Dokumen Pendukung Perjanjian</strong>");

		var elements ='<form class="form-upload-mou" enctype="multipart/form-data">';
		elements += '<table class="table table-bordered">';
		elements += '	<thead>';
		elements += '		<th>Detail</th>';
		elements += '		<th class="text-center">Status Legal</th>';
		elements += '	</thead>';
		elements += '	<tbody>';
		elements += '		<tr>';
		elements += '			<td>';
		elements += '				<div class="input-group">';
		elements += '					<input type="text" style="font-weight:bold;" class="form-control" value="Dokumen Perjanjian Kerjasama" readonly="readonly">';
		elements += '					<div class="input-group-btn">;';
		elements += '						<input type="file" class="form-control upload_file berkas dok" style="display:none;" name="dok_jaminan[]" required>';
		elements += '						<input type="hidden" class="legal_app" name="app_dok_jaminan">';
		elements += '						<button type="button" class="btn btn-default btn-flat btn_upload_file manager" data-title="Upload" title="Upload" disabled><i class="fa fa-upload"></i></button>';
		elements += '					</div>';
		elements += '					<div class="input-group-btn">';
		elements += '						<button type="button" class="btn btn-default btn-flat views" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
		elements += '					</div>';
		elements += '				</div>';
		elements += '			</td>';
		if (level == '5' && status_mou == '5') {
			elements += '			<td class="text-center">';
			elements += '				<div class="btn-group">';	
			elements += '					<button type="button" class="btn btn-sm btn-success action_item" title="Approve" value="1"><i class="fa fa-check"></i></button>';
			elements += '					<button type="button" class="btn btn-sm btn-danger action_item" title="Reject" value="0"><i class="fa fa-close"></i></button>';
			elements += '				</div>';
			elements += '			</td>';
		}else{
			elements += '		<td class="text-center">';
			elements += '			<i class="fa fa-clock-o status_legal"></i></button>';
			elements += '		</td>';
		}
		elements += '		</tr>';
		
		if (parseFloat($("input[name='um_nilai_jaminan_summary']").val()) > 0) {
			elements += '		<tr>';
			elements += '			<td>';
			elements += '				<div class="input-group">';
			elements += '					<input type="text" style="font-weight:bold;" class="form-control" value="Tanda Terima Jaminan" readonly="readonly">';
			elements += '					<div class="input-group-btn">;';
			elements += '						<input type="file" class="form-control upload_file berkas dok" style="display:none;" name="dok_tanda_terima[]" required>';
			elements += '						<input type="hidden" class="legal_app" name="app_dok_tanda_terima">';
			elements += '						<button type="button" class="btn btn-default btn-flat btn_upload_file manager" data-title="Upload" title="Upload" disabled><i class="fa fa-upload"></i></button>';
			elements += '					</div>';
			elements += '					<div class="input-group-btn">';
			elements += '						<button type="button" class="btn btn-default btn-flat views" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
			elements += '					</div>';
			elements += '				</div>';
			elements += '			</td>';
			if (level == '5' &&status_mou == '5') {
				elements += '			<td class="text-center">';
				elements += '				<div class="btn-group">';	
				elements += '					<button type="button" class="btn btn-sm btn-success action_item" title="Approve" value="1"><i class="fa fa-check"></i></button>';
				elements += '					<button type="button" class="btn btn-sm btn-danger action_item" title="Reject" value="0"><i class="fa fa-close"></i></button>';
				elements += '				</div>';
				elements += '			</td>';
			}else{
				elements += '			<td class="text-center">';
				elements += '			<i class="fa fa-clock-o status_legal"></i></button>';
				elements += '			</td>';
			}
			elements += '		</tr>';			
		}

		elements += '		<tr>';
		elements += '			<td>';
		elements += '				<div class="input-group">';
		elements += '					<input type="text" style="font-weight:bold;" class="form-control" value="Foto Bersama" readonly="readonly">';
		elements += '					<div class="input-group-btn">;';
		elements += '						<input type="file" class="form-control upload_file berkas dok" style="display:none;" name="dok_foto_bersama[]" required>';
		elements += '						<input type="hidden" class="legal_app" name="app_dok_foto_bersama">';
		elements += '						<button type="button" class="btn btn-default btn-flat btn_upload_file manager" data-title="Upload" title="Upload" disabled><i class="fa fa-upload"></i></button>';
		elements += '					</div>';
		elements += '					<div class="input-group-btn">';
		elements += '						<button type="button" class="btn btn-default btn-flat views" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
		elements += '					</div>';
		elements += '				</div>';
		elements += '			</td>';
		
		if (level == '5' && status_mou == '5') {
			elements += '			<td class="text-center">';
			elements += '				<div class="btn-group">';	
			elements += '					<button type="button" class="btn btn-sm btn-success action_item" title="Approve" value="1"><i class="fa fa-check"></i></button>';
			elements += '					<button type="button" class="btn btn-sm btn-danger action_item" title="Reject" value="0"><i class="fa fa-close"></i></button>';
			elements += '				</div>';
			elements += '			</td>';
		}else{
			elements += '			<td class="text-center">';
			elements += '			<i class="fa fa-clock-o status_legal"></i></button>';
			elements += '			</td>';
		}
		
		elements += '		</tr>';
		elements += '		<tr>';
		elements += '			<td>';
		elements += '				<div class="input-group">';
		elements += '					<input type="text" style="font-weight:bold;" class="form-control" value="Dokumen Jaminan Asli (Legal)" readonly="readonly">';
		elements += '					<div class="input-group-btn">;';
		elements += '						<input type="file" class="form-control upload_file berkas leg" style="display:none;" name="dok_asli[]">';
		elements += '						<input type="hidden" name="dokumen_asli" value="false">';
		elements += '						<button type="button" class="btn btn-default btn-flat btn_upload_file legal" data-title="Upload" title="Upload" disabled><i class="fa fa-upload"></i></button>';
		elements += '					</div>';
		elements += '					<div class="input-group-btn">';
		elements += '						<button type="button" class="btn btn-default btn-flat views" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
		elements += '					</div>';
		elements += '				</div>';
		elements += '			</td>';
		elements += '			<td>';
		elements += '				<textarea class="form-control ket_dok_asli" name="ket_dok_asli" readonly></textarea>';
		elements += '				<input type="hidden" name="no_form_scoring" value="'+no_form_scoring+'">';
		elements += '				<input type="hidden" name="action_mou">';
		elements += '			</td>';
		elements += '		</tr>';
		elements += '	</tbody>';
		elements += '</table>';
		elements += '</form>';
		
		$('#KiranaModals .modal-body').html(elements);
		if ($(".modal-footer").length > 0) {
			$(".modal-footer").remove();
		}
		
		if (status_mou == level) {
			var output_footer = '<div class="modal-footer">';
			output_footer += '	<div class="form-group">';
			if (level == '1') {
				$(".manager").removeAttr("disabled");
				output_footer += '		<button type="button" class="btn btn-success" name="btn_mou" value="upload">Upload</button>';
			}else{
				$(".legal").removeAttr("disabled");
				$(".upload_file").removeAttr("required");
				$(".ket_dok_asli").attr("readonly", false);
				output_footer += '		<button type="button" class="btn btn-success" name="btn_mou" value="approve">Approve</button>';
				output_footer += '		<button type="button" class="btn btn-warning" name="btn_mou" value="reject">Decline</button>';
			}
			output_footer += '	</div>';
			output_footer += '</div>';						
			$('#KiranaModals .modal-content').append(output_footer);
		}
								

		$('#KiranaModals').modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});

		$('#KiranaModals .checkbox-mou').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green',
			increaseArea: '20%' // optional
		});

		$.ajax({
			url: baseURL + "umb/scoring/get/mou",
			type: 'POST',
			dataType: 'JSON',
			data: {
				no_form: no_form_scoring
			},
			success: function (data) {
				if (data) {
					$.each(data, function (i, v) {
						if (v.desc == "Dokumen Perjanjian Kerjasama") {
							$("input[name='dok_jaminan[]']").closest(".input-group").find(".views").attr("data-link", v.file_location);
							$("input[name='dok_jaminan[]']").closest("tr").css("background-color", (v.status == 'approve' ? "rgba(0, 141, 76, 0.3)" : (v.status == 'reject' ? "rgba(255, 141, 76, 0.3)" : "")));
							if (v.status == "approve") {
								$("input[name='dok_jaminan[]']").closest("tr").find(".status_legal").removeClass('fa-clock-o').addClass('fa-check');
								$("input[name='dok_jaminan[]']").closest("tr").find(".btn_upload_file").attr('disabled', "disabled");
								$("input[name='dok_jaminan[]']").closest("tr").find(".dok").removeAttr('required');
								$("input[name='app_dok_jaminan']").val('approve');
							}else{
								$("input[name='dok_jaminan[]']").closest("tr").find(".status_legal").removeClass('fa-clock-o').addClass('fa-times');
							}
						}else if(v.desc == "Foto Bersama"){
							$("input[name='dok_foto_bersama[]']").closest(".input-group").find(".views").attr("data-link", v.file_location);
							$("input[name='dok_foto_bersama[]']").closest("tr").css("background-color", (v.status == 'approve' ? "rgba(0, 141, 76, 0.3)" : (v.status == 'reject' ? "rgba(255, 141, 76, 0.3)" : "")));
							if (v.status == "approve") {
								$("input[name='app_dok_foto_bersama']").val('approve');
								$("input[name='dok_foto_bersama[]']").closest("tr").find(".btn_upload_file").attr('disabled', "disabled");
								$("input[name='dok_foto_bersama[]']").closest("tr").find(".dok").removeAttr('required');
								$("input[name='dok_foto_bersama[]']").closest("tr").find(".status_legal").removeClass('fa-clock-o').addClass('fa-check');
							}else{
								$("input[name='dok_foto_bersama[]']").closest("tr").find(".status_legal").removeClass('fa-clock-o').addClass('fa-times');
							}
						}else if(v.desc == "Tanda Terima Jaminan"){
							$("input[name='dok_tanda_terima[]']").closest(".input-group").find(".views").attr("data-link", v.file_location);
							$("input[name='dok_tanda_terima[]']").closest("tr").css("background-color", (v.status == 'approve' ? "rgba(0, 141, 76, 0.3)" : (v.status == 'reject' ? "rgba(255, 141, 76, 0.3)" : "")));
							if (v.status == "approve") {
								$("input[name='app_dok_tanda_terima']").val('approve');
								$("input[name='dok_tanda_terima[]']").closest("tr").find(".btn_upload_file").attr('disabled', "disabled");
								$("input[name='dok_tanda_terima[]']").closest("tr").find(".dok").removeAttr('required');
								$("input[name='dok_tanda_terima[]']").closest("tr").find(".status_legal").removeClass('fa-clock-o').addClass('fa-check');
							}else{
								$("input[name='dok_tanda_terima[]']").closest("tr").find(".status_legal").removeClass('fa-clock-o').addClass('fa-times');
							}
						}else if (v.desc == 'Dokumen Jaminan Asli'){
							$("input[name='dok_asli[]']").closest(".input-group").find(".views").attr("data-link", v.file_location);
							$("input[name='dok_asli[]']").closest("tr").find(".ket_dok_asli").val(v.komentar);
							$("input[name='dokumen_asli']").val("true");
							$("textarea[name='ket_dok_asli]").attr("readonly", true);
							$(".legal").attr("disabled");
							$("input[name='dok_asli[]']").closest("tr").css("background-color", "rgba(0, 141, 76, 0.3)");
						}
					});
					if ($("input[name='app_dok_jaminan']").val() == "approve" && $("input[name='app_dok_tanda_terima']").val() == "approve" && $("input[name='app_dok_foto_bersama']").val() == "approve") {
						$(".leg").attr('required', 'required');
					}
				}
			}
		});

	});

	$(document).on("click", ".views", function () {
		if ($(this).data("link") !== "") {
			window.open(baseURL+$(this).data("link"), '_blank');
		}else{
			// kiranaAlert("notOK", "File Tidak Ditemukan", "warning", "no");
			var overlay = "<label class='err_msg' style='font-size:12px;color:red;'>&nbspFile tidak ditemukan</label>"; 
			if ($(".err_msg").length > 0) {
				$(".err_msg").remove();
			}
			$(this).closest("td").append(overlay);
		}
	});

	$(document).on("change", ".dok", function () {
		$(this).closest("tr").css("background-color", "rgba(0, 141, 76, 0.3)");
	});

	$(document).on("change", ".leg", function () {
		$(this).closest("tr").css("background-color", "rgba(0, 141, 76, 0.3)");
		$(".ket_dok_asli").attr('required', 'required');
		$("input[name='dokumen_asli']").val("true");
	});

	$(document).on("click", ".action_item", function (e) {
		var action = $(this).val();
		$(this).closest("tr").css("background-color", (action == 1 ? "rgba(0, 141, 76, 0.3)" : "rgba(255, 141, 76, 0.3)"));
		$(this).closest("tr").find(".legal_app").val((action == 1 ? "approve" : "reject"))

	});

	$(document).on("click", "button[name='btn_mou']", function (e) {
		var um_jamin = parseFloat($("input[name='um_nilai_jaminan_summary']").val());
		$("input[name='action_mou']").val($(this).val());
		
		if (um_jamin > 0) {
			if ($(this).val() == 'approve' || $(this).val() == 'reject') {
				if ($("input[name='app_dok_jaminan']").val() !== "" || $("input[name='app_dok_tanda_terima']").val() !== "" || $("input[name='app_dok_foto_bersama']").val() !== "") {
					if($(this).val() == 'approve'){
						if ($("input[name='app_dok_jaminan']").val() !== "approve" || $("input[name='app_dok_tanda_terima']").val() !== "approve" || $("input[name='app_dok_foto_bersama']").val() !== "approve") {
							kiranaAlert("notOK", "Tidak dapat melakukan approve dikarenakan terdapat dokumen yang di reject.", "warning", "no");
							e.preventDefault();
							return false;
						}
					}else{
						if ($("input[name='app_dok_jaminan']").val() == "approve" && $("input[name='app_dok_tanda_terima']").val() == "approve" && $("input[name='app_dok_foto_bersama']").val() == "approve") {
							kiranaAlert("notOK", "Tidak dapat melakukan Decline dikarenakan tidak terdapat dokumen yang di reject.", "warning", "no");
							e.preventDefault();
							return false;
						}
					}
				}else{
					kiranaAlert("notOK", "Mohon berikan approval pada setiap item.", "warning", "no");
					e.preventDefault();
					return false;	
				}
			}
		}else{
			if ($(this).val() == 'approve' || $(this).val() == 'reject') {
				if ($("input[name='app_dok_jaminan']").val() !== "" || $("input[name='app_dok_foto_bersama']").val() !== "") {
					if($(this).val() == 'approve'){
						if ($("input[name='app_dok_jaminan']").val() !== "approve" || $("input[name='app_dok_foto_bersama']").val() !== "approve") {
							kiranaAlert("notOK", "Tidak dapat melakukan approve dikarenakan terdapat dokumen yang di reject.", "warning", "no");
							e.preventDefault();
							return false;
						}
					}else{
						if ($("input[name='app_dok_jaminan']").val() == "approve" && $("input[name='app_dok_foto_bersama']").val() == "approve") {
							kiranaAlert("notOK", "Tidak dapat melakukan Decline dikarenakan tidak terdapat dokumen yang di reject.", "warning", "no");
							e.preventDefault();
							return false;
						}
					}
				}else{
					kiranaAlert("notOK", "Mohon berikan approval pada setiap item.", "warning", "no");
					e.preventDefault();
					return false;	
				}
			}
		}

		var empty_form = validate(".form-upload-mou");
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-upload-mou")[0]);
				$.ajax({
					url: baseURL + "umb/scoring/save/mou",
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function () {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("#KiranaModals .modal-content").append(overlay);  
					},
					success: function (data) {
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					error: function () {
						kiranaAlert("notOK", "Server Error", "error", "no");
					},
					complete: function () {
						$("#KiranaModals .modal-content .overlay").remove();
					}
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
	});

	$(document).on('hide.bs.modal','#KiranaModals', function () {
		$("#KiranaModals .modal-dialog").removeAttr("class");
		$("#KiranaModals div:eq(0)").addClass("modal-dialog");
    	if ($(".modal-footer").length > 0) {
			$(".modal-footer").remove();
		}
	});	

});

function rekom_um_by_hasil_appraisal(){
	// console.log($("input[name='hasil_appraisal[]']").length);
	var checker = true;
	var total = 0;
	for (var i = 0; i < $("input[name='hasil_appraisal[]']").length; i++) {
		var s  = $("input[name='hasil_appraisal[]").eq(i).val().replace(/,/g, "");
		total += +s;
		if (s < 1){
			checker = false;
		}
	}

	if (checker == true) {
		$("input[name='um_rekom_summary").val(numberWithCommas(total.toFixed(2)));
	}	
}

function show_form_revised(rowdetail, rowjaminan, id_scoring_jaminan_header, id_scoring_jaminan_detail, nilai_appraisal, status) {
	
	var title = "Revisi Hasil Appraisal";
	var readonlys = "";
	var requireds = "required";
	
	if(status !== 'revised'){
		title = "Hasil Appraisal";
		requireds = "";
		readonlys = "readonly";
	}
	
	$(".modal button.close").show();
	$('#KiranaModals .modal-title').html(title);
	var output = '<form class="form-revisi-appraisal" enctype="multipart/form-data">';
	output += '		<div class="form-horizontal">';
	output += '			<div class="form-group">';
	output += '				<label for="value_revise" class="col-sm-4 control-label text-left">Nilai Appraisal</label>';
	output += '				<div class="col-sm-8">';
	output += '					<input type="text" class="form-control angka" name="value_revisi" min="0" value="' + nilai_appraisal + '" '+readonlys+' required>';
	output += '				</div>';
	output += '			</div>';
	output += '			<div class="form-group">';
	output += '				<label for="pabrik" class="col-sm-4 control-label text-left">Attachment</label>';
	output += '				<div class="col-sm-8">';
	output += '					<input type="file" class="form-control berkas" name="attach_revised[]" '+requireds+'>';
	output += '				</div>';
	output += '			</div>';
	output += '			<input type="hidden" name="id_scoring_jaminan_detail" value="'+id_scoring_jaminan_detail+'">';
	output += '			<input type="hidden" name="id_scoring_jaminan_header" value="'+id_scoring_jaminan_header+'">';
	output += '</div>';

	var output_footer = '<div class="modal-footer">';
	output_footer += '	<div class="form-group">';
	output_footer += '		<button type="button" class="btn btn-success" data-rowdetail="' + rowdetail + '" data-rowjaminan="' + rowjaminan + '" name="revisi_btn">OK</button>';
	output_footer += '	</div>';
	output_footer += '</div>';
	output_footer += '</form>';

	$('#KiranaModals .modal-body').html(output);
	if ($(".modal-footer").length > 0) {
		$(".modal-footer").remove();
	}
	$('#KiranaModals .modal-content').append(output_footer);

	$('#KiranaModals').modal({
		backdrop: 'static',
		keyboard: true,
		show: true
	});
}

function show_form_nilai_aset_jaminan(rowdetail, rowjaminan, jenis, elem, data, id_header, id_detail) {
	
	var readonlys = "readonly";
	var disableds = "disabled";
	// if (( ($("input[name='session_role_nama']").val() == 'Finance Controller HO Div Head' && $("input[name='session_role_level']").val() == '7' ) || 
		  // ($("input[name='session_role_nama']").val() == 'Finance Controller HO Dept Head' && $("input[name='session_role_level']").val() == '71')) && 
		 	// $("input[name='status_scoring']").val() == $("input[name='session_role_level']").val()) {
	if ( ( $("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1') &&
		 ($("input[name='status_scoring']").val() == '1' || $("input[name='status_scoring']").val() == '2') ) {
		readonlys = "";
		disableds = "";
	}

	$("#KiranaModals").removeAttr("class");
	$("#KiranaModals").addClass("modal");

	$(".modal button.close").show();
	$('#KiranaModals .modal-title').html("Data Aset Jaminan (" + jenis + ")");

	var select_jenis = $(elem).closest(".row-detail-jaminan").find(".detail_jaminan").html();
	// console.log(rowdetail+"--"+rowjaminan);

	var output = '<form class="form-penilaian-jaminan" enctype="multipart/form-data">';
	output += '		<div class="form-horizontal">';
	output += '			<div class="nav-tabs-custom">';
	output += '				<ul class="nav nav-tabs">';
	output += '					<li class="active"><a href="#general' + rowjaminan + '_' + rowdetail + '-tab" data-toggle="tab">General</a></li>';
	output += '					<li><a href="#kendaraan' + rowjaminan + '_' + rowdetail + '-tab" class="kendaraan-tab" data-toggle="tab">Kendaraan</a></li>';
	output += '					<li><a href="#bangunan' + rowjaminan + '_' + rowdetail + '-tab" class="bangunan-tab" data-toggle="tab">Bangunan/Tanah</a></li>';
	output += '					<li><a href="#wawancara' + rowjaminan + '_' + rowdetail + '-tab" class="wawancara-tab" data-toggle="tab" style="display:none">Wawancara</a></li>';
	output += '					<li><a href="#analisa' + rowjaminan + '_' + rowdetail + '-tab" class="analisa-tab" data-toggle="tab" style="display:none">Analisa Desktop</a></li>';
	output += '				</ul>';
	output += '			</div>';
	output += '			<div class="tab-content">';
	output += '				<div class="tab-pane active" id="general' + rowjaminan + '_' + rowdetail + '-tab">';
	output += '					<div class="form-group">';
	output += '						<label for="alamat" class="col-sm-4 control-label text-left">Alamat</label>';
	output += '						<div class="col-sm-8">';
	output += '							<textarea class="form-control" name="alamat_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'></textarea>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="foto_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Gambar Aset</label>';
	output += '						<div class="col-sm-8">';
	output += '							<div class="row">';
	output += '								<div class="col-sm-12">';
	output += '									<div class="input-group">';
	output += '										<input type="text" class="form-control caption_file" name="caption' + rowjaminan + '_' + rowdetail + '[]" required="required" readonly="readonly">';
	output += '										<div class="input-group-btn">;';
	output += '											<input type="file" class="form-control upload_file berkas" style="display:none;" name="foto_nilai_aset_jaminan_sisi1_' + rowjaminan + '_' + rowdetail + '[]">';
	output += '											<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload" title="Upload" ' + disableds + '><i class="fa fa-upload"></i></button>';
	output += '										</div>';
	output += '										<div class="input-group-btn">';
	output += '											<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
	output += '										</div>';
	output += '									</div>';
	output += '									<small><em>*Sisi 1</em></small>';
	output += '								</div>';
	output += '								<div class="col-sm-12">';
	output +='									<div class="input-group">';
	output +='										<input type="text" class="form-control caption_file" name="caption' + rowjaminan + '_' + rowdetail + '[]" required="required" readonly="readonly">';
	output +='										<div class="input-group-btn">;';
	output +='											<input type="file" class="form-control upload_file berkas" style="display:none;" name="foto_nilai_aset_jaminan_sisi2_' + rowjaminan + '_' + rowdetail + '[]">';
	output +='											<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload" title="Upload" '+disableds+'><i class="fa fa-upload"></i></button>';
	output +='										</div>';
	output +='										<div class="input-group-btn">';
	output +='											<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
	output += '										</div>';
	output += '									</div>';
	output += '									<small><em>*Sisi 2</em></small>';
	output += '								</div>';
	output += '								<div class="col-sm-12">';
	output +='									<div class="input-group">';
	output +='										<input type="text" class="form-control caption_file" name="caption' + rowjaminan + '_' + rowdetail + '[]" required="required" readonly="readonly">';
	output +='										<div class="input-group-btn">;';
	output +='											<input type="file" class="form-control upload_file berkas" style="display:none;" name="foto_nilai_aset_jaminan_sisi3_' + rowjaminan + '_' + rowdetail + '[]">';
	output +='											<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload" title="Upload" '+disableds+'><i class="fa fa-upload"></i></button>';
	output +='										</div>';
	output +='										<div class="input-group-btn">';
	output +='											<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
	output += '										</div>';
	output += '									</div>';
	output += '									<small><em>*Sisi 3</em></small>';
	output += '								</div>';
	output += '								<div class="col-sm-12">';
	output +='									<div class="input-group">';
	output +='										<input type="text" class="form-control caption_file" name="caption' + rowjaminan + '_' + rowdetail + '[]" required="required" readonly="readonly">';
	output +='										<div class="input-group-btn">;';
	output +='											<input type="file" class="form-control upload_file berkas" style="display:none;" name="foto_nilai_aset_jaminan_sisi4_' + rowjaminan + '_' + rowdetail + '[]">';
	output +='											<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload" title="Upload" '+disableds+'><i class="fa fa-upload"></i></button>';
	output +='										</div>';
	output +='										<div class="input-group-btn">';
	output +='											<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
	output += '										</div>';
	output += '									</div>';
	output += '									<small><em>*Sisi 4</em></small>';
	output += '								</div>';
	output += '							</div>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<div class="checkbox">';
	output += '							<label><input type="checkbox" class="checkbox-nilai-aset ckwawancara readonly" data-value="isWawancara" name="isWawancara' + rowjaminan + '_' + rowdetail + '" '+disableds+'> Wawancara</label>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<div class="checkbox">';
	output += '							<label><input type="checkbox" class="checkbox-nilai-aset ckanalisa readonly" data-value="isAnalisaDesktop" name="isAnalisaDesktop' + rowjaminan + '_' + rowdetail + '" '+disableds+'> Analisa Desktop</label>';
	output += '						</div>';
	output += '					</div>';
	output += '				</div>';
	output += '				<div class="tab-pane" id="kendaraan' + rowjaminan + '_' + rowdetail + '-tab">';
	output += '					<div class="form-group">';
	output += '						<label for="merk_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Merk</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" class="form-control" name="merk_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="type_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Type</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" class="form-control" name="type_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="thn_buat_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Tahun Pembuatan</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" data-startview="years" data-format="yyyy" data-minviewmode="years" onkeydown="return false" data-autoclose="true" class="form-control kiranadatepicker readonly" name="thn_buat_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'>';
	output += '						</div>';
	output += '					</div>';
	output += '				</div>';
	output += '				<div class="tab-pane" id="bangunan' + rowjaminan + '_' + rowdetail + '-tab">';
	output += '					<div class="form-group">';
	output += '						<label for="lt_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Luas Tanah (m2)</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" class="form-control angka" min="0" name="lt_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="lb_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Luas Bangunan (m2)</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" class="form-control angka" min="0" name="lb_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" ' + readonlys + '>';
	output += '							<small><em>*Jika jaminan merupakan tanah, silahkan diisi dengan 0</em></small>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="jns_sertifikat_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Jenis Sertifikat</label>';
	output += '						<div class="col-sm-8">';
	output += '							<select class="form-control select2" name="jns_sertifikat_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+disableds+'>';
	// output += select_jenis;
	output += '							<option value="0">Silahkan Pilih</option>';
	output += '							<option value="SHM">SHM</option>';
	output += '							<option value="HGB">HGB</option>';
	output += '							<option value="GIRIK">GIRIK</option>';
	output += '							</select>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="no_sert_nilai_aset_jaminan" class="col-sm-4 control-label text-left">No Sertifikat</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" class="form-control" name="no_sert_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="tgl_terbit_sertifikat_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Tanggal Terbit</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" onkeydown="return false" data-autoclose="true" class="form-control kiranadatepicker" name="tgl_terbit_sertifikat_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="tgl_akhir_sertifikat_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Tanggal Berakhir</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" onkeydown="return false" data-autoclose="true" class="form-control kiranadatepicker" name="tgl_akhir_sertifikat_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="no_nilai_aset_jaminan" class="col-sm-4 control-label text-left">No</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" class="form-control angka" name="no_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'>';
	output += '						</div>';
	output += '					</div>';
	output += '					<div class="form-group">';
	output += '						<label for="tgl_situasi_nilai_aset_jaminan" class="col-sm-4 control-label text-left">Tanggal Gambar Situasi</label>';
	output += '						<div class="col-sm-8">';
	output += '							<input type="text" onkeydown="return false" data-autoclose="true" class="form-control kiranadatepicker readonly" name="tgl_situasi_nilai_aset_jaminan' + rowjaminan + '_' + rowdetail + '" '+readonlys+'>';
	output += '						</div>';
	output += '					</div>';
	output += '				</div>';
	output += '				<div class="tab-pane" id="wawancara' + rowjaminan + '_' + rowdetail + '-tab">';
	output += generate_row_penilaian_aset_method(rowdetail, rowjaminan, 'wawancara', jenis);
	output += '				</div>';
	output += '				<div class="tab-pane" id="analisa' + rowjaminan + '_' + rowdetail + '-tab">';
	output += generate_row_penilaian_aset_method(rowdetail, rowjaminan, 'analisa', jenis);
	output += '				</div>';
	output += '			</div>';
	output += '			<input type="hidden" name="id_scoring_jaminan_nilai">';
	output += '			<input type="hidden" name="id_scoring_jaminan_header" value="'+id_header+'">';
	output += '			<input type="hidden" name="id_scoring_jaminan_detail" value="'+id_detail+'">';
	output += '			<input type="hidden" name="rowjaminan" value="'+rowjaminan+'">';
	output += '			<input type="hidden" name="rowdetail" value="'+rowdetail+'">';
	output += '			<input type="hidden" name="no_form_scoring" value="'+$("input[name='no_form']").val()+'">';
	output += '			<input type="hidden" name="pabriks" value="'+$("select[name='pabrik']").val()+'">';
	output += '</div>';

	var output_footer = '<div class="modal-footer">';
	output_footer += '		<div class="form-group">';
	output_footer += '			<button type="button" class="btn btn-success" data-rowdetail="' + rowdetail + '" data-rowjaminan="' + rowjaminan + '" name="nilai_aset_jaminan_btn">OK</button>';
	output_footer += '		</div>';
	output_footer += '	</div>';
	output_footer += '</form>';

	$('#KiranaModals .modal-body').html(output);
	if ($(".modal-footer").length > 0) {
		$(".modal-footer").remove();
	}

	// if (( ($("input[name='session_role_nama']").val() == 'Finance Controller HO Div Head' && $("input[name='session_role_level']").val() == '7' ) || 
		  // ($("input[name='session_role_nama']").val() == 'Finance Controller HO Dept Head' && $("input[name='session_role_level']").val() == '71')) && 
		 	// $("input[name='status_scoring']").val() == $("input[name='session_role_level']").val()) {
	if ( ( $("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1') &&
		 ($("input[name='status_scoring']").val() == '2' || $("input[name='status_scoring']").val() == '1') ) {
		$('#KiranaModals .modal-content').append(output_footer);
	}

	if (data) {
		$("input[name='id_scoring_jaminan_nilai']").val(data.id_scoring_jaminan_nilai);
		$("textarea[name='alamat_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.alamat);
		
		for (var i = 0; i < $("input[name='caption" + rowjaminan + "_" + rowdetail + "[]']").length; i++) {
			var img = eval("data.img"+(i+1));
			$("input[name='caption" + rowjaminan + "_" + rowdetail + "[]']").eq(i).val(img.split("/").pop());
			$("input[name='caption" + rowjaminan + "_" + rowdetail + "[]']").eq(i).attr("title",img.split("/").pop());
			$("input[name='caption" + rowjaminan + "_" + rowdetail + "[]']").eq(i).closest(".input-group").find(".view_file").attr("data-link",img);
		}		

		//tab kendaraaan
		$("input[name='merk_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.merk);
		$("input[name='type_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.type);
		$("input[name='thn_buat_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.tahun); //datepicker

		//tab bangunan
		$("input[name='lt_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.luas_tanah);
		$("input[name='lb_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.luas_bangunan);
		$("select[name='jns_sertifikat_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.jenis_sertifikat).trigger('change');
		$("input[name='no_sert_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.no_sertifikat);
		$("input[name='tgl_terbit_sertifikat_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.tgl_terbit);
		$("input[name='tgl_akhir_sertifikat_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.tgl_akhir);
		$("input[name='no_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.no);
		$("input[name='tgl_situasi_nilai_aset_jaminan" + rowjaminan + "_" + rowdetail + "']").val(data.tgl_gambar_situasi);

		if (data.metode) {
			var j=0;
			var k=0;
			var l=0;
			$.each(data.metode, function (i, v) {
				if(v.tipe == "wawancara" && v.tgl_nilai && v.tgl_nilai !== ""){
					$(".ckwawancara").iCheck('check');
				}
				if(v.tipe == "analisa" && v.tgl_nilai && v.tgl_nilai !== ""){
                    $(".ckanalisa").iCheck('check');
				}

				if (v.tipe == "wawancara") {
					j = k;
				}else{
					j = l;
				}
					$("input[name='tgl_penilaian_jaminan_" + v.tipe + rowjaminan + "_" + rowdetail + "[]']").eq(j).val(v.tgl_nilai);
					$("input[name='sumber_info_jaminan_" + v.tipe +  rowjaminan + "_" + rowdetail + "[]']").eq(j).val(v.sumber_info);
					$("input[name='id_scoring_jaminan_metode_" + v.tipe  + rowjaminan + "_" + rowdetail + "[]']").eq(j).val(v.id_scoring_jaminan_metode);
					$("input[name='jns_aset_jaminan_" + v.tipe + rowjaminan + "_" + rowdetail + "[]']").eq(j).val(v.jenis_aset);
					$("input[name='spek_aset_jaminan_" + v.tipe + rowjaminan + "_" + rowdetail + "[]']").eq(j).val(v.spek_aset);
					$("input[name='lokasi_jaminan_" + v.tipe + rowjaminan + "_" + rowdetail + "[]']").eq(j).val(v.alamat);
					$("input[name='tgl_trans_jaminan_" + v.tipe + rowjaminan + "_" + rowdetail + "[]']").eq(j).val(v.tgl_transaksi);
					$("input[name='harga_trans_jaminan_" + v.tipe + rowjaminan + "_" + rowdetail + "[]']").eq(j).val(v.harga_transaksi);
					$("input[name='harga_trans_m2_jaminan_" + v.tipe + rowjaminan + "_" + rowdetail + "[]']").eq(j).val(v.harga_per_m);

					$("input[name='caption_file_pendukung_" + v.tipe +  rowjaminan + "_" + rowdetail + "[]']").eq(j).val((v.file_pendukung !== null ? v.file_pendukung.split("/").pop() : v.file_pendukung));
					$("input[name='caption_file_pendukung_" + v.tipe +  rowjaminan + "_" + rowdetail + "[]']").eq(j).attr("title", (v.file_pendukung !== null ? v.file_pendukung.split("/").pop() : v.file_pendukung));
					$("input[name='caption_file_pendukung_" + v.tipe +  rowjaminan + "_" + rowdetail + "[]']").eq(j).closest(".input-group").find(".view_file").attr("data-link",v.file_pendukung);
					
				if (v.tipe == "wawancara") {
					k++;
				}else{
					l++;
				}

			});

			if (k == 0) {
				$(".nav-tabs .wawancara-tab").hide();
				$(".ckwawancara").removeAttr("checked");
				$(".ckwawancara").closest(".icheckbox_square-green").removeClass("checked");
			}
			if (l == 0) {
				$(".nav-tabs .analisa-tab").hide();
				$(".ckanalisa").removeAttr("checked");
				$(".ckanalisa").closest(".icheckbox_square-green").removeClass("checked");
			}
		}
	}

	if (jenis == "Bangunan/Tanah") {
		$(".nav-tabs .bangunan-tab").show();
		$(".nav-tabs .kendaraan-tab").hide();

        $('#bangunan' + rowjaminan + '_' + rowdetail + '-tab input').attr("required", "required");
        $('#bangunan' + rowjaminan + '_' + rowdetail + '-tab select').attr("required", "required");
	} else if (jenis == "Kendaraan") {
		$(".nav-tabs .bangunan-tab").hide();
		$(".nav-tabs .kendaraan-tab").show();

		$('#kendaraan' + rowjaminan + '_' + rowdetail + '-tab input').attr("required", "required");
		$('#kendaraan' + rowjaminan + '_' + rowdetail + '-tab select').attr("required", "required");
	}

	$('#KiranaModals .checkbox-nilai-aset').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%' // optional
	});

	$('#KiranaModals').modal({
		backdrop: 'static',
		keyboard: true,
		show: true
	});

	$("#KiranaModals .kiranadatepicker").each(function () {
		$(this).datepicker({
			//setDate: '2001-10-11',//($(this).val() !== "" ? $(this).val() : new Date("d.m.Y")),
			todayHighlight: true,
			disableTouchKeyboard: true,
			format: ($(this).data("format") != null ? $(this).data("format") : "dd.mm.yyyy"),
			startView: ($(this).data("startview") != null ? $(this).data("startview") : "days"),
			minViewMode: ($(this).data("minviewmode") != null ? $(this).data("minviewmode") : "days"),
			autoclose: ($(this).data("autoclose") != null ? $(this).data("autoclose") : false),
		});

		$(this).datepicker().on('hide.bs.modal', function(e) {     
		 	// prevent datepicker from firing bootstrap modal "show.bs.modal"     
		 	e.stopPropagation();  
		});
	});

	$("#KiranaModals .select2").select2({
		dropdownParent: $('#KiranaModals')
	});

}

function generate_row_penilaian_aset_method(rowdetail, rowjaminan, tipe, jenis) {
	var readonlys = "readonly";
	var disableds = "disabled";
	// if (( ($("input[name='session_role_nama']").val() == 'Finance Controller HO Div Head' && $("input[name='session_role_level']").val() == '7' ) || 
		  // ($("input[name='session_role_nama']").val() == 'Finance Controller HO Dept Head' && $("input[name='session_role_level']").val() == '71')) && 
		 	// $("input[name='status_scoring']").val() == $("input[name='session_role_level']").val()) {
	if ( ( $("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1') &&
		 ($("input[name='status_scoring']").val() == '2' || $("input[name='status_scoring']").val() == '1') ) {
		readonlys = "";
		disableds = "";
	}
	var output = "";
	output += '	<table class="table table-striped table-bordered">';
	output += '		<thead>';
	output += '			<th>Data Komparasi</th>';
	output += '			<th>Komparasi 1</th>';
	output += '			<th>Komparasi 2</th>';
	output += '			<th>Komparasi 3</th>';
	output += '		</thead>';
	output += '		<tbody>';
	output += '			<tr>';
	output += '				<td>Tanggal Penilaian</td>';
	output += '				<td><input type="text" class="form-control kiranadatepicker" onkeydown="return false" data-autoclose="true" name="tgl_penilaian_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control kiranadatepicker" onkeydown="return false" data-autoclose="true" name="tgl_penilaian_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control kiranadatepicker" onkeydown="return false" data-autoclose="true" name="tgl_penilaian_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '			</tr>';
	output += '			<tr>';
	output += '				<td>Sumber Informasi</td>';
	output += '				<td>';
	output += '					<input type="text" class="form-control" name="sumber_info_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/>';
	output += '					<input type="hidden" class="form-control" name="id_scoring_jaminan_metode_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/>';
	output += '				</td>';	
	output += '				<td>';
	output += '					<input type="text" class="form-control" name="sumber_info_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/>';
	output += '					<input type="hidden" class="form-control" name="id_scoring_jaminan_metode_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/>';
	output += '				</td>';
	output += '				<td>';
	output += '					<input type="text" class="form-control" name="sumber_info_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/>';
	output += '					<input type="hidden" class="form-control" name="id_scoring_jaminan_metode_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/>';
	output += '				</td>';
	// output += '				<td><input type="text" class="form-control" name="sumber_info_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]"/></td>';
	// output += '				<td><input type="text" class="form-control" name="sumber_info_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]"/></td>';
	output += '			</tr>';
	if (tipe !== "wawancara") {
		output += '			<tr>';
		output += '				<td>File Pendukung</td>';
		output += '				<td>';
		output +='					<div class="input-group">';
		output +='						<input type="text" class="form-control caption_file" name="caption_file_pendukung_'+ tipe + rowjaminan + '_' + rowdetail + '[]" readonly="readonly">';
		if ( ( $("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1') &&
		 ($("input[name='status_scoring']").val() == '2' || $("input[name='status_scoring']").val() == '1') ) {
			output +='						<div class="input-group-btn">;';
			output +='							<input type="file" class="form-control upload_file berkas" style="display:none;" name="file_pendukung_analisa0_' + rowjaminan + '_' + rowdetail + '[]">';
			output +='							<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload" title="Upload" '+disableds+'><i class="fa fa-upload"></i></button>';
			output +='						</div>';
		}
		output +='						<div class="input-group-btn">';
		output +='							<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
		output += '						</div>';
		output += '					 </div>'; 
		output += '				</td>';
		output += '				<td>';
		output +='					<div class="input-group">';
		output +='						<input type="text" class="form-control caption_file" name="caption_file_pendukung_'+ tipe + rowjaminan + '_' + rowdetail + '[]" readonly="readonly">';
		if ( ( $("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1') &&
		 ($("input[name='status_scoring']").val() == '2' || $("input[name='status_scoring']").val() == '1') ) {
			output +='						<div class="input-group-btn">;';
			output +='							<input type="file" class="form-control upload_file berkas" style="display:none;" name="file_pendukung_analisa1_' + rowjaminan + '_' + rowdetail + '[]">';
			output +='							<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload" title="Upload" '+disableds+'><i class="fa fa-upload"></i></button>';
			output +='						</div>';
		}
		output +='						<div class="input-group-btn">';
		output +='							<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
		output += '						</div>';
		output += '					 </div>'; 
		output += '				</td>';
		output += '				<td>';
		output +='					<div class="input-group">';
		output +='						<input type="text" class="form-control caption_file" name="caption_file_pendukung_'+ tipe + rowjaminan + '_' + rowdetail + '[]" readonly="readonly">';
		if ( ( $("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1') &&
		 ($("input[name='status_scoring']").val() == '2' || $("input[name='status_scoring']").val() == '1') ) {
			output +='						<div class="input-group-btn">;';
			output +='							<input type="file" class="form-control upload_file berkas" style="display:none;" name="file_pendukung_analisa2_' + rowjaminan + '_' + rowdetail + '[]">';
			output +='							<button type="button" class="btn btn-default btn-flat btn_upload_file" data-title="Upload" title="Upload" '+disableds+'><i class="fa fa-upload"></i></button>';
			output +='						</div>';
		}
		output +='						<div class="input-group-btn">';
		output +='							<button type="button" class="btn btn-default btn-flat view_file" data-link="" title="Lihat file"><i class="fa fa-search"></i></button>';
		output += '						</div>';
		output += '					 </div>'; 
		output += '				</td>';
		// output += '				<td><input type="text" class="form-control angka" name="file_pendukung' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
		// output += '				<td><input type="text" class="form-control angka" name="file_pendukung' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
		// output += '				<td><input type="text" class="form-control angka" name="file_pendukung' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
		output += '			</tr>';
	}
	output += '			<tr>';
	output += '				<td>Jenis Aset</td>';
	output += '				<td><input type="text" class="form-control" name="jns_aset_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control" name="jns_aset_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control" name="jns_aset_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '			</tr>';
	output += '			<tr>';
	output += '				<td>Spesifikasi Aset</td>';
	output += '				<td><input type="text" class="form-control" name="spek_aset_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control" name="spek_aset_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control" name="spek_aset_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '			</tr>';
	output += '			<tr>';
	output += '				<td>Lokasi / Alamat</td>';
	output += '				<td><input type="text" class="form-control" name="lokasi_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control" name="lokasi_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control" name="lokasi_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '			</tr>';
	output += '			<tr>';
	output += '				<td>Tanggal Transaksi</td>';
	output += '				<td><input type="text" class="form-control kiranadatepicker" onkeydown="return false" data-autoclose="true" name="tgl_trans_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control kiranadatepicker" onkeydown="return false" data-autoclose="true" name="tgl_trans_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control kiranadatepicker" onkeydown="return false" data-autoclose="true" name="tgl_trans_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '			</tr>';
	output += '			<tr>';
	output += '				<td>Harga Transaksi</td>';
	output += '				<td><input type="text" class="form-control angka" min="0" name="harga_trans_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control angka" min="0" name="harga_trans_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '				<td><input type="text" class="form-control angka" min="0" name="harga_trans_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
	output += '			</tr>';
	if (jenis == "Bangunan/Tanah") {
		output += '			<tr>';
		output += '				<td>Harga per m2</td>';
		output += '				<td><input type="text" class="form-control angka" min="0" name="harga_trans_m2_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
		output += '				<td><input type="text" class="form-control angka" min="0" name="harga_trans_m2_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
		output += '				<td><input type="text" class="form-control angka" min="0" name="harga_trans_m2_jaminan_' + tipe + rowjaminan + '_' + rowdetail + '[]" '+readonlys+'/></td>';
		output += '			</tr>';
	}
	output += '		</tbody>';
	output += '	</table>';

	return output;
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

function get_data_depo(depo) {
	$.ajax({
		url: baseURL + "umb/master/get/depo",
		type: 'POST',
		dataType: 'JSON',
		data: {
			plant: $("select[name='pabrik']").val()
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
			if (depo) {
				$("select[name='depo']").val(depo).trigger("change.select2");
			}
		}
	});
}

function get_data_provinsi(provinsi) {
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
		},
		complete: function () {
			if (provinsi) {
				$("select[name='provinsi[]']").val(provinsi).trigger("change.select2");
			}
		}
	});
}

function generate_data_supply(data) {
	if ((data.kode_supplier !== null || data.depo !== '0') && data.tipe_scoring !== "Ranger") {
		$.ajax({
			url: baseURL + 'umb/scoring/get/supply',
			type: 'POST',
			dataType: 'JSON',
			data: {
				plant: $("select[name='pabrik']").val(),
				supplier: data.kode_supplier, //$("select[name='supplier']").val(),
				depo: data.depo_nama,
				tipe: data.id_scoring_tipe,
				tanggal: $("input[name='tgl_pengajuan']").val()
			},
			beforeSend: function () {
				var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
				$("body .overlay-wrapper").append(overlay);
			},
			success: function (data) {
				if (data && data.supply) {
					if ($(".row-jaminan").length == 0 && $("a[href='#jaminan-tab']").closest("li").is(":visible") == true) {
						$(".add-pemilik-jaminan").click();
					}

					if (data.first) {
						$.each(data.first, function (i, v) {
							$("input[name='supply_since']").val(generateDateFormat(v.BEDAT));
						});
						generate_lama_join();
					}

					if (data.supply) { // supply
						$('.my-datatable-extends-order.table-supplier').DataTable().destroy();
						var t = generate_table('.my-datatable-extends-order.table-supplier');
						t.clear().draw();

						var kolom2 = 0,
							kolom3 = 0, 
							kolom4 = 0,
							kolom5 = 0,
							kolom6 = 0,
							datang = 0,
							suplai4bln_awal = 0,
							suplai2bln_akhir = 0,
							idx_rata2 = 0,
							kelas = "";
						$.each(data.supply, function (i, v) {
							var myrow = t.row.add([
								v.bulan,
								numberWithCommas(Math.round(v.qty_suplai)),
								numberWithCommas(Math.round(v.qty_suplai_perweek)),
								numberWithCommas(Math.round(v.total_dtg_perweek)),
								numberWithCommas(Math.ceil(v.jumlah_hk)),
								numberWithCommas(Math.round(v.qty_suplai_perhari))
							]).draw(false).node();

							if (data.supply.length > 6) {
								if (i > (data.supply.length - 7)) {
									kolom2 += (v.qty_suplai * 1.00);
									kolom3 += (v.qty_suplai_perweek * 1.00);
									kolom4 += (v.total_dtg_perweek * 1.00);
									kolom5 += (v.jumlah_hk * 1.00);
									kolom6 += (v.qty_suplai_perhari * 1.00);

									if (idx_rata2 < 4) {
										suplai4bln_awal += (v.qty_suplai_perhari * 1.00);
									} else {
										suplai2bln_akhir += (v.qty_suplai_perhari * 1.00);
									}

									idx_rata2++;
								}
							} else {
								kolom2 += (v.qty_suplai * 1.00);
								kolom3 += (v.qty_suplai_perweek * 1.00);
								kolom4 += (v.total_dtg_perweek * 1.00);
								kolom5 += (v.jumlah_hk * 1.00);
								kolom6 += (v.qty_suplai_perhari * 1.00);

								if (idx_rata2 < 4) {
									suplai4bln_awal += (v.qty_suplai_perhari * 1.00);
								} else {
									suplai2bln_akhir += (v.qty_suplai_perhari * 1.00);
								}

								idx_rata2++;
							}

							if (i == (data.supply.length - 1)) {
								t.row.add([
									"6 Bulan Terakhir<br>(average)",
									numberWithCommas(Math.round(kolom2 / 6)),
									numberWithCommas(Math.round(kolom3 / 6)),
									numberWithCommas(Math.round(kolom4 / 6)),
									numberWithCommas(Math.ceil(kolom5 / 6)),
									numberWithCommas(Math.round(kolom6 / 6))
								]).draw(false);

								kelas = v.kelas;
							}

							if (v.qty_suplai > 0) {
								datang++;
							}
						});

						//==Description Supplier==//
						$(".desc-supp h4 code").eq(0).html(kelas); //Kelas : [..]
						$(".desc-supp .form-group").eq(0).find("label span").eq(0).html(data.supply.length); //Jumlah kedatangan dalam [..] bulan terakhir
						$(".desc-supp .form-group").eq(0).find("div code").eq(1).html(data.supply.length); //5 dari [..] bulan
						$(".desc-supp .form-group").eq(0).find("div code").eq(0).html(datang); //[..] dari x bulan
						$(".desc-supp .form-group").eq(1).find("div code").eq(0).html(numberWithCommas((kolom4 / 6).toFixed(2))); //Frekuensi kedatangan / minggu dalam 6 bulan [..] x seminggu
						$(".desc-supp .form-group").eq(2).find("div code").eq(0).html(parseFloat(suplai4bln_awal / 4).toFixed(2)); //Tren suplai harian 4 bulan pertama dalam 6 bulan [..]
						$(".desc-supp .form-group").eq(3).find("div code").eq(0).html(parseFloat(suplai2bln_akhir / 2).toFixed(2)); //Tren suplai harian 2 bulan terakhir [..]
						var growth = (suplai2bln_akhir / 2) - (suplai4bln_awal / 4);
						$(".desc-supp .form-group").eq(4).find("div code").eq(0).html(parseFloat(growth).toFixed(2)); //Growth 2-4 6 bulan terakhir [..] atau x
						$(".desc-supp .form-group").eq(4).find("div code").eq(1).html((growth / (suplai4bln_awal / 4)).toFixed(2) + " %"); //Growth 2-4 6 bulan terakhir x atau [..]
					}
				}
			},
			complete: function () {
				$("body .overlay-wrapper .overlay").remove();
			}
		});
	}
}

function generate_data_jaminan(data, rowjaminan) {
	var output = '<tr class="row-jaminan">';
	output += '		<td align="center" width="5%">' + (rowjaminan + 1) + '</td>';
	output += '		<td style="width: 40%; min-width: 200px"><input type="text" class="form-control readonly" name="nama_penjamin[]" required="required"/></td>';
	output += '		<td>';
	output += '			<div style="width: 100%" class="text-center"><button data-toggle="collapse" data-parent="#accordion" href="#jaminan' + rowjaminan + '" aria-expanded="false"  type="button" class="btn btn-sm btn-success collapsed collapsible-toogle">show</button></div>';
	output += '			<div id="jaminan' + rowjaminan + '" data-row="' + rowjaminan + '" class="panel-collapse collapse form-horizontal" aria-expanded="false" style="margin-top: 10px;">';
	output += '				<div class="form-group">';
	output += '					<label for="pabrik" class="col-sm-2 control-label text-left">Status</label>';
	output += '					<div class="col-sm-4">';
	output += '						<select class="select2 form-control readonly" name="status_penjamin[]" id="status_penjamin' + rowjaminan + '" required="required">';
	output += '							<option value="0">Silahkan Pilih</option>';
	output += '							<option value="Lajang">Lajang</option>';
	output += '							<option value="Menikah">Menikah</option>';
	output += '							<option value="Cerai Hidup">Cerai Hidup</option>';
	output += '							<option value="Cerai Meninggal">Cerai Meninggal</option>';
	output += '						</select>';
	output += '					</div>';
	output += '				</div>';
	output += '				<div class="form-group">';
	output += '					<label for="pabrik" class="col-sm-2 control-label text-left">Kepemilikan</label>';
	output += '					<div class="col-sm-4">';
	output += '						<select class="select2 form-control readonly" name="kepemilikan[]" id="kepemilikan' + rowjaminan + '" required="required">';
	output += '							<option value="0">Silahkan Pilih</option>';
	output += '							<option value="Supplier Sendiri">Supplier Sendiri</option>';
	output += '							<option value="Pihak Lain Perorangan">Pihak Lain Perorangan</option>';
	output += '							<option value="Pihak Lain Badan">Pihak Lain Badan</option>';
	output += '						</select>';
	output += '					</div>';
	output += '				</div>';
	// output += '				<div class="form-group">';
	// output += '					<label for="pabrik" class="col-sm-2 control-label text-left">Kepemilikan Badan</label>';
	// output += '					<div class="col-sm-4">';
	// output += '						<select class="select2 form-control readonly" name="kepemilikan_badan[]" id="kepemilikan_badan' + rowjaminan + '" required="required">';
	// output += '							<option value="0">Silahkan Pilih</option>';
	// output += '							<option value="Non PT">Non PT</option>';
	// output += '							<option value="PT/Koperasi">PT/Koperasi</option>';
	// output += '						</select>';
	// output += '					</div>';
	// output += '				</div>';
	output += '				<div class="form-group" style="display: none;">';
	output += '					<label for="pabrik" class="col-sm-2 control-label text-left">Dokumen</label>';
	output += '					<div class="col-sm-4 dokumen">';
	output += '					</div>';
	output += '				</div>';
	output += '				<button type="button" class="btn btn-sm btn-success add-detail-jaminan readonly hidden" style="margin: 5px 5px;"><i class="fa fa-plus"></i></button>';
	output += '				<button type="button" class="btn btn-sm btn-danger delete-detail-jaminan readonly hidden" style="margin: 5px 5px;"><i class="fa fa-trash-o"></i></button>';
	output += '				<table class="table table-bordered table-striped table-responsive table-detail-jaminan detail-jaminan" style="width: 2000px !important; ">';
	output += '					<thead>';
	output += '						<th>Nama</th>';
	output += '						<th>Jenis Jaminan</th>';
	output += '						<th>Detail Jaminan</th>';
	output += '						<th>Nilai Jaminan</th>';
	output += '						<th>% Disc</th>';
	output += '						<th>Nilai Appraisal</th>';
	output += '						<th>Keterangan</th>';
	output += '						<th>Dokumen Jaminan</th>';
	if ((($("input[name='session_role_nama']").val() == 'Legal HO Div Head' && $("input[name='session_role_level']").val() == '5') || 
		 ($("input[name='session_role_nama']").val() == 'Legal HO Dept Head' && $("input[name='session_role_level']").val() == '51')) &&
		 $("input[name='status_scoring']").val() == $("input[name='session_role_level']").val()) {
		output += '						<th>Rekomendasi Legal</th>'
		output += '						<th>Action Legal</th>'
	}else{
		output += '						<th>Rekomendasi Legal</th>'
	}
	//fincon only
	if ((($("input[name='session_role_nama']").val() == 'Finance Controller HO Div Head' && $("input[name='session_role_level']").val() == '7') || 
		  ($("input[name='session_role_nama']").val() == 'Finance Controller HO Dept Head' && $("input[name='session_role_level']").val() == '71')) && 
		 $("input[name='status_scoring']").val() == $("input[name='session_role_level']").val()) {
		output += '						<th>Penilaian Jaminan</th>';
		output += '						<th>Hasil Appraisal</th>';
		output += '						<th style="min-width: 142px;">Status Appraisal</th>';
	} else {
		output += '						<th>Penilaian Jaminan</th>';
		output += '						<th>Hasil Appraisal</th>';
	}
	output += '					</thead>';
	output += '					<tbody>';
	output += '					</tbody>';
	output += '				</table>';
	output += '			</div>';
	output += '		</td>';
	output += '		<td style="width: 40%; min-width: 200px"><input type="text" class="form-control angka readonly" name="nilai_appraisal_penjamin[]" required="required" readonly="readonly" min="0" value="0"/></td>';
	output += '</tr>';

	$(".table-jaminan tbody").eq(0).append(output);

	if (data) {
		$("input[name='nama_penjamin[]']").eq(rowjaminan).val(data.nama);
		$("input[name='nilai_appraisal_penjamin[]']").eq(rowjaminan).val((data.total_appraisal ? numberWithCommas(data.total_appraisal) : 0));
		$("select[name='status_penjamin[]']").eq(rowjaminan).val(data.status_penjamin);
		$("select[name='kepemilikan[]']").eq(rowjaminan).val(data.kepemilikan_penjamin);
		// $("select[name='kepemilikan_badan[]']").eq(rowjaminan).val(data.kepemilikan_bdn);
	}
	$(".row-jaminan .select2").select2();  
}

function generate_data_jaminan_detail(dataheader, rowjaminan, rowdetail) {
	// console.log('dataheader : '+dataheader.file_attachment);
	// console.log(dataheader);
	var group = "jaminan" + rowjaminan;
	var output = '<tr class="row-detail-jaminan" data-id_detail="'+dataheader.id_scoring_jaminan_detail+'" data-id_header="'+dataheader.id_scoring_jaminan_header+'"  data-rowdetail="' + rowdetail + '"  data-rowjaminan="' + rowjaminan + '">';
	output += '		<td><input type="text" class="form-control readonly" name="nama' + rowjaminan + '[]" required="required"></td>';
	output += '		<td>';
	output += '			<select class="select2 form-control jenis_jaminan readonly" name="jenis_jaminan' + rowjaminan + '[]" required="required">';
	output += '				<option value="0">Silahkan Pilih</option>';
	output += '			</select>';
	output += '		</td>';
	output += '		<td>';
	output += '			<select class="select2 form-control detail_jaminan_select readonly" name="detail_jaminan' + rowjaminan + '[]" required="required">';
	output += '				<option value="0">Silahkan Pilih</option>';
	output += '			</select>';
	output += '		</td>';
	output += '		<td><input type="text" class="form-control angka nilai_jaminan readonly" name="nilai_jaminan' + rowjaminan + '[]" required="required"></td>';
	output += '		<td><input type="text" class="form-control disc_jaminan' + rowdetail + ' readonly" name="disc_jaminan' + rowjaminan + '[]" readonly="readonly"></td>';
	output += '		<td><input type="text" class="form-control nilai_appraisal' + rowdetail + ' readonly" name="nilai_appraisal' + rowjaminan + '[]" readonly="readonly"></td>';
	output += '		<td><textarea class="form-control readonly" name="desc_jaminan' + rowjaminan + '[]"></textarea></td>';
	output += '		<td>';
	output += '			<div class="input-group" style="margin-bottom: 10px">';
	output += '				<input type="text" class="form-control readonly" name="caption_dokumen_appraisal_hidden' + rowjaminan + '[]" required="required">';
	// output += '				<input type="file" class="form-control readonly" name="dokumen_appraisal' + rowjaminan + '[]" required="required">';
	output += '				<div class="input-group-btn">';
	output += '					<input type="text" name="dokumen_appraisal_hidden' + rowjaminan + '[]" class="form-control hidden data-lihat-file readonly">';
	output += '					<button type="button" class="btn btn-default btn-flat lihat-file" data-title="File Dokumen Jaminan" title="klik untuk lihat file"><i class="fa fa-search"></i></button>';
	output += '				</div>';
	output += '			</div>';
	output += '		</td>';
	if ((($("input[name='session_role_nama']").val() == 'Legal HO Div Head' && $("input[name='session_role_level']").val() == '5') || 
		 ($("input[name='session_role_nama']").val() == 'Legal HO Dept Head' && $("input[name='session_role_level']").val() == '51') ) &&
		 $("input[name='status_scoring']").val() == $("input[name='session_role_level']").val() ) {
		output += '		<td class="text-center td_legal' +rowjaminan+'_'+ rowdetail + '"><input type="hidden" name="cek_legal[]" value="'+(dataheader.rekomendasi_legal !== null ? (dataheader.rekomendasi_legal == 'y' ? "VALID" : "INVALID") : "WAITING")+'"><span class="label '+(dataheader.rekomendasi_legal !== null ? (dataheader.rekomendasi_legal == 'y' ? "label-success" : "label-danger") : "label-default")+' rekom_legal' +rowjaminan+'_'+ rowdetail + '">'+(dataheader.rekomendasi_legal !== null ? (dataheader.rekomendasi_legal == 'y' ? "VALID" : "INVALID") : "WAITING")+'</span></td>';
		output += '		<td>';
		// output += '			<div class="btn_rekom_legal' + rowdetail + '"></div>';
		output += '			<div class="btn-group">';
		output += '				<button class="btn btn-sm btn-success ok_valid' + rowdetail + '" name="action_legal" value="y" type="button">VALID</button>';
		output += '				<button class="btn btn-sm btn-danger reject_valid' + rowdetail + '" name="action_legal" value="n" type="button">INVALID</button>';
		output += '			</div>'; 
		output += '		</td>';
	}else{
		output += '		<td class="text-center"><input type="hidden" name="cek_legal[]" value="'+(dataheader.rekomendasi_legal !== null ? (dataheader.rekomendasi_legal == 'y' ? "VALID" : "INVALID") : "WAITING")+'"><span class="label '+(dataheader.rekomendasi_legal !== null ? (dataheader.rekomendasi_legal == 'y' ? "label-success" : "label-danger") : "label-default")+' rekom_legal' +rowjaminan+'_'+ rowdetail + '">'+(dataheader.rekomendasi_legal !== null ? (dataheader.rekomendasi_legal == 'y' ? "VALID" : "INVALID") : "WAITING")+'</span></td>';
	}

	if (($("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1') &&
		 ($("input[name='status_scoring']").val() == '2' || $("input[name='status_scoring']").val() == '1')) {
		output += '		<td class="text-center">';
		output += '			<div class="nilai_aset_jaminan' + rowdetail + ' hidden"></div>';
		if (dataheader.id_scoring_jaminan_nilai !== null) {
			output += '			<input type="hidden" name="penilaian' + rowjaminan + '_' + rowdetail + '" value="true"></div>';
			output += '			<input type="hidden" name="flag_penilaian[]" value="true"></div>';
			output += '			<button class="btn btn-sm btn-success" type="button" name="nilai_aset_jaminan"><i class="fa fa-check" style="padding-right:5px;"></i> Edit Penilaian</button>';
		} else {
			output += '			<input type="hidden" name="penilaian' + rowjaminan + '_' + rowdetail + '" value="false"></div>';
			output += '			<input type="hidden" name="flag_penilaian[]" value="false"></div>';
			output += '			<button class="btn btn-sm btn-primary" type="button" name="nilai_aset_jaminan"><i class="fa fa-plus" style="padding-right:5px;"></i> Input Penilaian</button>';
		}
		output += '		</td>';

	} else {
		if (dataheader.id_scoring_jaminan_nilai !== null) {
			output += '			<input type="hidden" name="flag_penilaian[]" value="true"></div>';
		} else {
			output += '			<input type="hidden" name="flag_penilaian[]" value="false"></div>';
		}
		output += '		<td><button class="btn btn-sm btn-primary" type="button" name="nilai_aset_jaminan"><i class="fa fa-file" style="padding-right:5px;"></i> View Penilaian</button></td>';
	}

	if ((($("input[name='session_role_nama']").val() == 'Finance Controller HO Div Head' && $("input[name='session_role_level']").val() == '7') || 
		 ($("input[name='session_role_nama']").val() == 'Finance Controller HO Dept Head' && $("input[name='session_role_level']").val() == '71'))){
		//update
		// if(dataheader.file_attachment !== null){
			output += '			<td><div class="input-group" style="margin-bottom: 10px">';
			output += '				<input type="text" class="form-control hasil_appraisal' +rowjaminan+'_'+ rowdetail + ' readonly" name="hasil_appraisal[]" readonly="readonly">';
			output += '				<div class="input-group-btn">';
			output += '					<input type="text" name="dokumen_revisi_hidden' +rowjaminan+'_'+ rowdetail + '" value="'+dataheader.file_attachment+'" class="form-control hidden data-lihat-file readonly">';
			output += '					<button type="button" class="btn btn-default btn-flat lihat-file" data-title="File Dokumen Hasil Appraisal" title="klik untuk lihat file"><i class="fa fa-search"></i></button>';
			output += '				</div>';
			output += '			</div></td>';
		// }else{
		// 	output += '		<td><input type="text" class="form-control hasil_appraisal' +rowjaminan+'_'+ rowdetail + ' readonly" name="hasil_appraisal[]" readonly="readonly"></td>';
		// }
		if((( $("input[name='session_role_nama']").val() == 'Finance Controller HO Div Head' && $("input[name='session_role_level']").val() == '7') || 
		($("input[name='session_role_nama']").val() == 'Finance Controller HO Dept Head' && $("input[name='session_role_level']").val() == '71') ) &&
		$("input[name='status_scoring']").val() == $("input[name='session_role_level']").val()){
			output += '		<td>';
			// output += '			<div class="revised' + rowdetail + '"></div>';
			output += '			<div class="btn-group">';
			output += '				<button class="btn btn-sm btn-success ok_apprisal' + rowdetail + '" name="action_jaminan" value="ok" type="button">OK</button>';
			output += '				<button class="btn btn-sm btn-danger rev_apprisal' + rowdetail + '" name="action_jaminan" value="notok" type="button">Revised</button>';
			output += '			</div>'; 
			output += '		</td>';
		}
	}else{
		output += '		<td><input type="text" class="form-control hasil_appraisal' +rowjaminan+'_'+ rowdetail + ' readonly" name="hasil_appraisal[]" readonly="readonly"></td>';
	}
	output += '</tr>';
	$("#" + group + " .table-detail-jaminan tbody").append(output);

	return $(".row-detail-jaminan .select2").select2();
}

function get_data_jenis_jaminan_detail(datadetail, rowjaminan, rowdetail) {
	// console.log(datadetail);
	$.ajax({
		url: baseURL + "umb/master/get/jaminan",
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			if (data) {
				var output = '<option value="0">Silahkan Pilih</option>';
				$.each(data, function (i, v) {
					output += '			<option value="' + v.id_mjaminan_header + '">' + v.jenis + '</option>';
				});
				$("select[name='jenis_jaminan" + rowjaminan + "[]']").eq(rowdetail).html(output);
			}
		},
		complete: function () {
			$("select[name='jenis_jaminan" + rowjaminan + "[]']").eq(rowdetail).val(datadetail.id_mjaminan_header).trigger("change.select2");
			get_data_detail_jaminan_detail(datadetail, rowjaminan, rowdetail);
		}
	});
}

function get_data_detail_jaminan_detail(datadetail, rowjaminan, rowdetail) {
	$.ajax({
		url: baseURL + "umb/master/get/jaminan-detail",
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_mjaminan_header: datadetail.id_mjaminan_header
		},
		success: function (data) {
			if (data) {
				var output = '<option value="0">Silahkan Pilih</option>';
				$.each(data, function (i, v) {
					output += '<option value="' + v.id_mjaminan_detail + '" data-disc="' + v.persen_discount + '">' + v.detail + '</option>';
				});
				$("#jaminan" + rowjaminan + " .table-detail-jaminan tbody .row-detail-jaminan").eq(rowdetail).find("select[name='detail_jaminan" + rowjaminan + "[]']").html(output);
				$(".row-detail-jaminan .select2").select2();
			}
		},
		complete: function () {
			$("select[name='detail_jaminan" + rowjaminan + "[]']").eq(rowdetail).val(datadetail.id_mjaminan_detail).trigger("change.select2");
		}
	});
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
	//==Description Supplier==//
	$(".desc-supp h4 code").eq(0).html("[kelas]"); //Kelas : [..]
	$(".desc-supp .form-group").eq(0).find("label span").eq(0).html("[angka1]"); //Jumlah kedatangan dalam [..] bulan terakhir
	$(".desc-supp .form-group").eq(0).find("div code").eq(1).html("[angka2]"); //5 dari [..] bulan
	$(".desc-supp .form-group").eq(0).find("div code").eq(0).html("[angka]"); //[..] dari x bulan
	$(".desc-supp .form-group").eq(1).find("div code").eq(0).html("[angka]"); //Frekuensi kedatangan / minggu dalam 6 bulan [..] x seminggu
	$(".desc-supp .form-group").eq(2).find("div code").eq(0).html("[angka]"); //Tren suplai harian 4 bulan pertama dalam 6 bulan [..]
	$(".desc-supp .form-group").eq(3).find("div code").eq(0).html("[angka]"); //Tren suplai harian 2 bulan terakhir [..]
	$(".desc-supp .form-group").eq(4).find("div code").eq(0).html("[angka]"); //Growth 2-4 6 bulan terakhir [..] atau x
	$(".desc-supp .form-group").eq(4).find("div code").eq(1).html("[angka]%"); //Growth 2-4 6 bulan terakhir x atau [..]

	resetDatatable('.my-datatable-extends-order.table-supplier');
	resetDatatable('.my-datatable-extends-order.table-kriteria');

	resetDatatable('.my-datatable-extends-order.table-hs');
	resetDatatable('.table-historical');
	$('.hs-nama').html('Nama Supplier');
	$('.tpo').html('');
	$('.tqty').html('');
	$('.tnpo').html('');
	$("input[name='plafond_awal']").val('');
	$("input[name='avg_nilai_po']").val('');

	$("input[name='supply_since']").val("");
	$("input[name='lama_join']").val("");
}

function resetDatatable(elem) {
	var t = $(elem).DataTable();
	t.clear().draw();
}
