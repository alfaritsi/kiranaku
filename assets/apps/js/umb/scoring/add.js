$(document).ready(function () {
	//=======================================START TOP FORM=======================================//
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
			$(this).closest(".col-sm-8").append(overlay);
		}
	});

	$(document).on("click", ".btn_upload_file", function() {
		$(this).closest(".input-group-btn").find(".upload_file").click();
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

		$.ajax({
			url: baseURL + 'umb/scoring/get/no-form',
			type: 'POST',
			dataType: 'JSON',
			data: {
				tipe: $(this).data("text")
			},
			success: function (data) {
				$("input[name='no_form']").val(data);
				if ($("input[name='no_form']").val().split('/').shift() == "RG") {
					$("input[name='ktp_file[]']").removeAttr("required");
					$(".hs-tab").addClass('active');
					$("#historical-tab").addClass('active');
					// $("input[name='npwp_file[]']").removeAttr("required");
				}
			}
		});
	});

	$(document).on("change", "select[name='pabrik']", function () {
		$("select[name='supplier']").val(null).trigger("change");
		var tipe_um = $("input[name='tipe_scoring_text']").val();
		resetForm();

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

	/*vendor nonbkr*/
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

	$(document).on("change", "input[name='um_propose']", function () {
		var um_propose = parseFloat($(this).val().replace(/,/g, "")).toFixed(2);
		$("input[name='um_propose_summary']").val((um_propose ? numberWithCommas(um_propose) : 0));
		$("input[name='plafond_baru']").val((um_propose ? numberWithCommas(um_propose) : 0));
		get_data_um_setujui();
	});

	$(document).on("ifChanged", ".isJaminan", function () {
		var check = $(this).prop("checked");
		if (check == true) {
			$("a[href='#jaminan-tab']").parent("li").show();
			$(this).closest(".control-label").find("span").html("Ada");
			if ($(".row-jaminan").length == 0 && $("a[href='#jaminan-tab']").closest("li").is(":visible") == true) {
				$(".add-pemilik-jaminan").click();
			}
			$("input[name='isJaminan']").val('1');
		}
		else {
			$(this).closest(".control-label").find("span").html("Tidak Ada");
			if ($(".row-jaminan").length > 0 && $("a[href='#jaminan-tab']").closest("li").is(":visible") == true) {
				$(".row-jaminan").remove();
			}
			$("a[href='#jaminan-tab']").parent("li").hide();
			$("a[href='#supp-tab']").click();
			$("input[name='isJaminan']").val('0');
			$("input[name='um_nilai_jaminan_summary']").val('0.00');
			get_data_um_setujui();
		}
	});

	//=======================================START MIDDLE FORM=======================================//
	//-------------------------SUPPLIER-------------------------//
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
						var check = $(".isJaminan").prop("checked");

						if ($(".row-jaminan").length == 0 && $("a[href='#jaminan-tab']").closest("li").is(":visible") == true && check == true) {
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

							generate_kriteria(datang, (kolom4 / 6).toFixed(2), (growth / (suplai4bln_awal / 4)).toFixed(2), (kolom2 / 6).toFixed(2));
							generate_historical();
						}
					}
				},
				complete: function () {
					$("body .overlay-wrapper .overlay").remove();
				}
			});
		}

		if (tipe_um == "Ranger"){
			generate_historical();
		}
	});

	$("input[name='tgl_pengajuan']").on("change", function () {
		generate_lama_join();
	});

	//-------------------------JAMINAN-------------------------//
	$(document).on("click", ".collapsible-toogle", function (e) {
		if ($(this).attr("aria-expanded") === "true") {
			$(this).html("Hide");
		} else {
			$(this).html("Show");
		}
	});

	
	$(document).on("input", ".nama_penjamin", function () {
		var rowjaminan = $(this).data("row");
		var prev = $(this).attr("data-prev");
		var inputan = $(this).val();
		// console.log("RowJaminan = "+rowjaminan+"\nPrev = "+prev+" \nInputan = "+inputan);

		for (var i = 0; i < $("input[name='nama"+rowjaminan+"[]']").length; i++) {
			if($("input[name='nama"+rowjaminan+"[]']").eq(i).val() == prev){
				$("input[name='nama"+rowjaminan+"[]']").eq(i).val(inputan);
			}
		}

	});

	$(document).on("keydown", ".nama_penjamin", function () {
		$(this).attr("data-prev", $(this).val());
	});

	$(document).on("click", ".add-pemilik-jaminan", function () {
		var rowjaminan = ($(".row-jaminan").length);

		var output = '<tr class="row-jaminan">';
		output += '		<td align="center" width="5%">' + (rowjaminan + 1) + '</td>';
		output += '		<td style="width: 40%; min-width: 200px"><input type="text" class="form-control nama_penjamin" data-prev="" data-row="'+rowjaminan+'" name="nama_penjamin[]" required="required"/></td>';
		output += '		<td>';
		output += '			<div style="width: 100%" class="text-center"><button data-toggle="collapse" data-parent="#accordion" href="#jaminan' + rowjaminan + '" aria-expanded="false"  type="button" class="btn btn-sm btn-success collapsed collapsible-toogle">show</button></div>';
		output += '			<div id="jaminan' + rowjaminan + '" data-row="' + rowjaminan + '" class="panel-collapse collapse form-horizontal" aria-expanded="false" style="margin-top: 10px;">';
		output += '				<div class="form-group">';
		output += '					<label for="pabrik" class="col-sm-2 control-label text-left">Status Pemilik</label>';
		output += '					<div class="col-sm-4">';
		output += '						<select class="select2 form-control" name="status_penjamin[]" id="status_penjamin' + rowjaminan + '" required="required">';
		output += '							<option value="0">Silahkan Pilih</option>';
		output += '							<option value="Lajang">Lajang</option>';
		output += '							<option value="Menikah">Menikah</option>';
		output += '							<option value="Cerai Hidup">Cerai Hidup</option>';
		output += '							<option value="Cerai Meninggal">Cerai Meninggal</option>';
		output += '							<option value="Non PT">Non PT</option>';
		output += '							<option value="PT/Koperasi">PT/Koperasi</option>';
		output += '						</select>';
		output += '					</div>';
		output += '				</div>';
		output += '				<div class="form-group">';
		output += '					<label for="pabrik" class="col-sm-2 control-label text-left">Kepemilikan</label>';
		output += '					<div class="col-sm-4">';
		output += '						<select class="select2 form-control" name="kepemilikan[]" id="kepemilikan' + rowjaminan + '" required="required">';
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
		// output += '						<select class="select2 form-control" name="kepemilikan_badan[]" id="kepemilikan_badan' + rowjaminan + '">';
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
		output += '				<button type="button" class="btn btn-sm btn-success add-detail-jaminan" style="margin: 5px 5px;"><i class="fa fa-plus"></i></button>';
		output += '				<button type="button" class="btn btn-sm btn-danger delete-detail-jaminan" style="margin: 5px 5px;"><i class="fa fa-trash-o"></i></button>';
		output += '				<table class="table table-bordered table-striped table-responsive table-detail-jaminan detail-jaminan" style="width: 1800px !important; ">';
		output += '					<thead>';
		output += '						<th>Nama Sertifikat</th>';
		output += '						<th>Jenis Jaminan</th>';
		output += '						<th>Detail Jaminan</th>';
		output += '						<th>Nilai Jaminan</th>';
		output += '						<th>% Disc</th>';
		output += '						<th>Nilai Appraisal</th>';
		output += '						<th>Keterangan</th>';
		output += '						<th>Dokumen Jaminan</th>';
		// output += '						<th>Penilaian Jaminan</th>';
		// output += '						<th>Hasil Appraisal</th>';
		// output += '						<th>Status Appraisal</th>';
		output += '					</thead>';
		output += '					<tbody>';
		output += '					</tbody>';
		output += '				</table>';
		output += '			</div>';
		output += '		</td>';
		output += '		<td style="width: 40%; min-width: 200px"><input type="text" class="form-control angka" name="nilai_appraisal_penjamin[]" required="required" readonly="readonly" min="0" value="0"/></td>';
		output += '</tr>';

		$(".table-jaminan tbody").eq(0).append(output);

		$("#jaminan" + rowjaminan + " .add-detail-jaminan").click();
		$(".row-jaminan .select2").select2();
	});

	$(document).on("click", ".delete-pemilik-jaminan", function () {
		var count = $(".row-jaminan").length;
		if (count > 1) $(".row-jaminan:eq(" + (count - 1) + ")").remove();
	});

	$(document).on("click", ".add-detail-jaminan", function () {
		var elem = $(this).closest(".panel-collapse");
		var group = $(elem).attr("id");
		var rowjaminan = $(elem).data("row");
		var rowdetail = $("#" + group + " .table-detail-jaminan tbody .row-detail-jaminan").length;

		$.ajax({
			url: baseURL + "umb/master/get/jaminan",
			type: 'POST',
			dataType: 'JSON',
			beforeSend: function () {
				var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
				$("body .overlay-wrapper").append(overlay);
			},
			success: function (data) {
				if (data) {
					var nama = elem.closest("tr").find(".nama_penjamin").val() ? elem.closest("tr").find(".nama_penjamin").val() : "";
					var output = '<tr class="row-detail-jaminan"  data-rowdetail="' + rowdetail + '" data-rowjaminan="' + rowjaminan + '">';
					output += '		<td><input type="text" class="form-control" name="nama' + rowjaminan + '[]" value="'+nama+'" required="required"></td>';
					output += '		<td>';
					output += '			<select class="select2 form-control jenis_jaminan" name="jenis_jaminan' + rowjaminan + '[]" required="required">';
					output += '				<option value="0">Silahkan Pilih</option>';
					$.each(data, function (i, v) {
						output += '			<option value="' + v.id_mjaminan_header + '">' + v.jenis + '</option>';
					});
					output += '			</select>';
					output += '		</td>';
					output += '		<td>';
					output += '			<select class="select2 form-control detail_jaminan_select" name="detail_jaminan' + rowjaminan + '[]" required="required">';
					output += '				<option value="0">Silahkan Pilih</option>';
					output += '			</select>';
					output += '		</td>';
					output += '		<td><input type="text" class="form-control text-right angka nilai_jaminan" name="nilai_jaminan' + rowjaminan + '[]" required="required"></td>';
					output += '		<td><input type="text" class="form-control disc_jaminan' + rowdetail + '" name="disc_jaminan' + rowjaminan + '[]" readonly="readonly"></td>';
					output += '		<td><input type="text" class="form-control text-right nilai_appraisal' + rowdetail + '" name="nilai_appraisal' + rowjaminan + '[]" readonly="readonly"></td>';
					output += '		<td><textarea class="form-control" name="desc_jaminan' + rowjaminan + '[]"></textarea></td>';
					output += '		<td><input type="file" class="form-control berkas" name="dokumen_appraisal' + rowjaminan + '[]" required="required"></td>';
					// output += '		<td>';
					// output += '			<div class="nilai_aset_jaminan' + rowdetail + ' hidden"></div>';
					// output += '			<button class="btn btn-sm btn-primary" type="button" name="nilai_aset_jaminan">Nilai</button>';
					// output += '		</td>';
					// output += '		<td><input type="text" class="form-control hasil_appraisal' + rowdetail + '" name="hasil_appraisal' + rowjaminan + '[]" readonly="readonly"></td>';
					// output += '		<td>';
					// output += '			<div class="revised' + rowdetail + '"></div>';
					// output += '			<div class="btn-group">';
					// output += '				<button class="btn btn-sm btn-success ok_apprisal' + rowdetail + '" name="action_jaminan" value="ok" type="button">OK</button>';
					// output += '				<button class="btn btn-sm btn-danger rev_apprisal' + rowdetail + '" name="action_jaminan" value="notok" type="button">Revised</button>';
					// output += '			</div>';
					// output += '		</td>';
					output += '</tr>';
					$("#" + group + " .table-detail-jaminan tbody").append(output);
					$(".row-detail-jaminan .select2").select2();
				}
			},
			complete: function () {
				$("body .overlay-wrapper .overlay").remove();
			}
		});
	});

	$(document).on("click", ".delete-detail-jaminan", function () {
		var elem = $(this).closest(".panel-collapse");
		var group = $(elem).attr("id");

		var count = $("#" + group + " .table-detail-jaminan .row-detail-jaminan").length;
		if (count > 1) $("#" + group + " .table-detail-jaminan tr.row-detail-jaminan:eq(" + (count - 1) + ")").remove();
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

		var total_all = 0;
		$("input[name='nilai_appraisal_penjamin[]']").each(function () {
			total_all += +$(this).val().replace(/,/g, "");
		});
		total_all = parseFloat(total_all).toFixed(2);
		$("input[name='um_nilai_jaminan_summary']").val(numberWithCommas(total_all));
		get_data_um_setujui();
	});

	$(document).on("click", "button[name='nilai_aset_jaminan']", function () {
		var rowjaminan = $(this).closest(".row-detail-jaminan").data("rowjaminan");
		var rowdetail = $(this).closest(".row-detail-jaminan").data("rowdetail");
		var jenis = $(this).closest(".row-detail-jaminan").find(".jenis_jaminan option:selected").text();
		if (jenis !== "Silahkan Pilih") {
			console.log(rowjaminan + " => " + rowdetail);
			show_form_nilai_aset_jaminan(rowdetail, rowjaminan, jenis, this);
		} else {
			kiranaAlert("notOK", "Silahkan pilih jenis jaminan", "error", "no");
		}
	});

	$(document).on("ifChanged", "#KiranaModals .checkbox-nilai-aset", function () {
		var nama = $(this).data("value");
		var check = $(this).prop("checked");
		switch (nama) {
			case "isWawancara" :
				if (check == true) $(".wawancara-tab").show();
				else $(".wawancara-tab").hide();
				break;
			case "isAnalisaDesktop" :
				if (check == true) $(".analisa-tab").show();
				else $(".analisa-tab").hide();
				break;
		}
	});

	// $(document).on("change", "select[name='kepemilikan_badan[]']", function () {
	// 	var elem = $(this).closest(".panel-collapse");
	// 	var group = $(elem).attr("id");

	// 	var kepemilikan = $("#" + group + " select[name='kepemilikan_badan[]").val();
	// 	if(kepemilikan == 0)
	// 		$("#" + group + " select[name='kepemilikan[]").val(0).trigger('change.select2');

	// 	$("#" + group + " select[name='status_penjamin[]").val(kepemilikan).trigger('change.select2');
	// });
	
	$(document).on("change", "select[name='status_penjamin[]'] , select[name='kepemilikan[]']", function () {
		var elem = $(this).closest(".panel-collapse");
		var group = $(elem).attr("id");
		var rowjaminan = $(this).closest(".panel-collapse").data("row");

		var status_penjamin = $("#" + group + " select[name='status_penjamin[]").val();
		var kepemilikan = $("#" + group + " select[name='kepemilikan[]").val();

		// $("#" + group + " select[name='kepemilikan_badan[]").val(0).trigger('change.select2');
		// $("#" + group + " select[name='kepemilikan_badan[]").removeAttr("required");
		// if(status_penjamin == "Non PT" || status_penjamin == "PT/Koperasi"){
		// 	$("#" + group + " select[name='kepemilikan_badan[]").attr("required", "required");
		// 	$("#" + group + " select[name='kepemilikan_badan[]").val(status_penjamin).trigger('change.select2');
		// 	$("#" + group + " select[name='kepemilikan[]").val("Pihak Lain Badan").trigger('change.select2');			
		// }

		if((status_penjamin !== "Non PT" && status_penjamin !== "PT/Koperasi") && kepemilikan == "Pihak Lain Badan"){
			$("#" + group + " select[name='kepemilikan[]").val(0).trigger('change.select2');
		}

		kepemilikan = $("#" + group + " select[name='kepemilikan[]").val();

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
					$("#" + group + " .dokumen").html("");
					$.each(data, function (i, v) {
						var dok = v.document.split(",");

						var output = "";
						$.each(dok, function (idx, val) {
							output += '<input type="text" name="jns_dok' + rowjaminan + '[]" class="form-control" readonly="readonly" value="' + val + '" required="required">';
							output += '<input type="file" name="file_dok' + rowjaminan + '[]" class="form-control berkas" style="margin-bottom: 10px" required="required">';
						});

						$("#" + group + " .dokumen").html(output);

						$("#" + group + " .dokumen").closest(".form-group").show();
					});
				}
			}
		});
	});

	$(document).on("change", ".berkas", function () {
		ValidateSize(this, 0.8); // param 2 = size in MB
	});
	

	$('.kiranaCheckbox').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%' // optional
	});

	$(document).on("ifChanged", ".fnt", function () {
		var elem = $(this).closest(".form-group").find("input[type=text]");
		if ($(this).prop("checked") == true) {
			$(elem).removeAttr("readonly");
			$(elem).attr("required", true);
		} else {
			$(elem).val("");
			$(elem).attr("readonly", "readonly");
			$(elem).removeAttr("required");
		}
	});

	$(document).on("ifChanged", ".ftg", function () {
		var elem = $(this).closest(".form-group").find("input[type=text]");
		if ($(this).prop("checked") == true) {
			$(elem).removeAttr("readonly");
			$(elem).attr("required", true);
			$(".fng").closest(".icheckbox_square-green").removeClass("checked");
			$(".fng").closest(".form-group").find("input[type=text]").val("");
			$(".fng").closest(".form-group").find("input[type=text]").attr("readonly", "readonly");
			$(".fng").closest(".form-group").find("input[type=text]").removeAttr("required");
		} else {
			$(elem).val("");
			$(elem).attr("readonly", "readonly");
			$(elem).removeAttr("required");
		}
	});

	$(document).on("ifChanged", ".fng", function () {
		var elem = $(this).closest(".form-group").find("input[type=text]");
		if ($(this).prop("checked") == true) {
			$(elem).removeAttr("readonly");
			$(elem).attr("required", true);
			$(".ftg").closest(".icheckbox_square-green").removeClass("checked");
			$(".ftg").closest(".form-group").find("input[type=text]").val("");
			$(".ftg").closest(".form-group").find("input[type=text]").attr("readonly", "readonly");
			$(".ftg").closest(".form-group").find("input[type=text]").removeAttr("required");
		} else {
			$(elem).val("");
			$(elem).attr("readonly", "readonly");
			$(elem).removeAttr("required");
		}
	});

	//=======================================START SAVE FORM=======================================//
	$(document).on("click", "button[name='action_btn']", function (e) {		
		var um_jamin = (parseFloat($("input[name='um_nilai_jaminan_summary']").val().replace(/,/g, "")) > 0 ? parseFloat($("input[name='um_nilai_jaminan_summary']").val().replace(/,/g, "")) : 0).toFixed(2);
		var um_propose = (parseFloat($("input[name='um_propose_summary']").val().replace(/,/g, "")) > 0 ? parseFloat($("input[name='um_propose_summary']").val().replace(/,/g, "")) : 0).toFixed(2);
		var um_scoring = (parseFloat($("input[name='um_scoring_summary']").val().replace(/,/g, "")) > 0 ? parseFloat($("input[name='um_scoring_summary']").val().replace(/,/g, "")) : 0).toFixed(2);
		var plafon_pabrik = parseFloat($("input[name='plafon_pabrik']").val());
		var nilai_scoring = $("input[name='nilai_scoring']").val();
		var std_scoring = $("input[name='std_scoring']").val();
		var tipe_scoring = $("input[name='no_form']").val().split("/")[0];
		if (um_propose > plafon_pabrik) {
			kiranaAlert("notOK", "Gagal Submit. Uang muka yang diajukan melebihi Plafon yang dimiliki oleh pabrik.", "warning", "no");
			e.preventDefault();
			return false;
		} else if (tipe_scoring !== "RG" && tipe_scoring !== "DMT" && um_propose > um_scoring && nilai_scoring < std_scoring) {
			kiranaAlert("notOK", "Tidak dapat mengajukan UM diatas rekomendasi UM scoring dikarenakan Nilai Scoring Supplier tidak melebihi Standar Nilai Scoring.", "warning", "no");
			e.preventDefault();
			return false;
		}

		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				checked_fee = true;
				if ($("input[name='no_form']").val().indexOf("RG") >= 0) {
					checked_fee = ($(".ranger_fee:checked").length == 1 ? false : true);
				}

				if (checked_fee) {
					$("input[name='isproses']").val(1);
					$("input[name='action']").val($(this).val());
					var formData = new FormData($(".form-scoring-um")[0]);
					$.ajax({
						url: baseURL + "umb/scoring/save/scoring",
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
								if (tipe_scoring !== 'RG' && um_jamin > 0) {
									// adjusting to reguler and non reguler
									kiranaAlert(data.sts, "Data Berhasil ditambahkan. Mohon Lengkapi setiap Penilaian Jaminan pada halaman ini.", "success", baseURL + 'umb/scoring/detail/reguler/' + $("input[name='no_form']").val().replace(/\//g, "-"));
								} else {
									kiranaAlert(data.sts, data.msg, "success", baseURL + 'umb/scoring/data');
								}
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
					kiranaAlert("notOK", "Silahkan pilih minimal 2 tipe fee", "warning", "no");
				}
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

function generate_kriteria(jml_dtg, frek_dtg, growth, tonase_6bln) {
	$.ajax({
		url: baseURL + "umb/master/get/kriteria",
		type: 'POST',
		dataType: 'JSON',
		data: {
			kelas: $(".desc-supp h4 code").eq(0).html()
		},
		success: function (data) {
			if (data) {
				$('.my-datatable-extends-order.table-kriteria').DataTable().destroy();
				var t = generate_table('.my-datatable-extends-order.table-kriteria');
				t.clear().draw();

				var total_bobot = 0, total_score = 0, datafoot = ["Subtotal"];

				$.each(data, function (i, v) {
					var detail = v.list_detail.slice(0, -1).split(",");
					var datarow = [];
					var param_nilai_awal = 0, param_nilai_akhir = 0, param_nilai = 0, nilai = 0;
					datarow.push('<input type="hidden" name="kriteria[]" value="' + v.id_mkriteria_header + '" required="required" min="0"><input type="hidden" name="kriteria_desc[]" value="' + v.nama + '" required="required" min="0">' + v.nama);
					datarow.push('<input type="hidden" name="param[]" value="' + v.list_detail + '" required="required"><input type="hidden" name="bobot[]" value="' + v.persen_bobot + '" required="required" min="0">' + v.persen_bobot + "%");
					total_bobot += parseFloat(v.persen_bobot);
					if (i == (data.length - 1)) {
						datafoot.push(parseFloat(total_bobot).toFixed(2));
					}

					$.each(detail, function (idx, val) {
						var mydata = detail[idx].split("|");
						param_nilai_awal = mydata[1];
						param_nilai_akhir = mydata[2];
						param_nilai = mydata[3];

						var input_param = '<input type="hidden" name="param_nilai_awal" value="' + param_nilai_awal + '">';
						input_param += '<input type="hidden" name="param_nilai_akhir" value="' + param_nilai_akhir + '">';
						input_param += '<input type="hidden" name="param_nilai" value="' + param_nilai + '">';
						datarow.push(input_param + minusValue(mydata[1]) + ' - ' + minusValue(mydata[2]));

						if (i == (data.length - 1)) {
							datafoot.push("");
						}

						switch (i) {
							case 0 :
								if (between(jml_dtg, param_nilai_awal, param_nilai_akhir)) nilai = param_nilai;
								break;
							case 1 :
								if (between(frek_dtg, param_nilai_awal, param_nilai_akhir)) nilai = param_nilai;
								break;
							case 2 :
								// console.log(tonase_6bln+"=>"+param_nilai_awal+"=>"+param_nilai_akhir);
								if (between(tonase_6bln, param_nilai_awal, param_nilai_akhir)) nilai = param_nilai;
								break;
							case 3 :
								if (between(growth, param_nilai_awal, param_nilai_akhir)) nilai = param_nilai;
								break;
						}
					});

					var nilai = parseFloat(nilai).toFixed(2);
					var input_nilai = '<input type="hidden" name="nilai[]" value="' + nilai + '" required="required" min="0">';
					var score = parseFloat(v.persen_bobot * nilai / 100).toFixed(2);
					var input_score = '<input type="hidden" name="score[]" value="' + score + '" required="required" min="0">';

					datarow.push(input_nilai + nilai);
					datarow.push(input_score + score);

					total_score += parseFloat(score);
					var myrow = t.row.add(datarow).draw(false).node();

					if (i == (data.length - 1)) {
						datafoot.push("Total");
						datafoot.push(parseFloat(total_score).toFixed(2));
						get_data_um_scoring(parseFloat(total_score).toFixed(2));
						t.row.add(datafoot).draw(false).node();
						$("input[name='nilai_scoring']").val(total_score);
					}
				});
			}
		},
		complete: function () {
		}
	});
}

function generate_historical() {

	var tanggal_awal = $("#dari").val();
	var tanggal_akhir = $("#sampai").val();

	if ($("input[name='no_form']").val().split('/').shift() == 'UMK'){
		$('.hs-nama').html($("select[name='supplier'] option:selected").text());
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

function get_data_um_scoring(score) {
	$.ajax({
		url: baseURL + "umb/scoring/get/um-by-score",
		type: 'POST',
		dataType: 'JSON',
		data: {
			score: score,
			tipe: $("input[name='tipe_scoring']").val(),
			kelas: $(".desc-supp h4 code").eq(0).html()
		},
		success: function (data) {
			if (data && data.length > 0) {
				$.each(data, function (i, v) {
					$("input[name='um_scoring_summary']").val((v.UM ? numberWithCommas(parseFloat(v.UM).toFixed(2)) : 0));
					$("input[name='std_scoring']").val(v.std_minimal);
					get_data_um_setujui();
				});
			} else {
				$("input[name='um_scoring_summary']").val(parseFloat(0).toFixed(2));
				get_data_um_setujui();
			}
		}
	});
}

function get_data_um_setujui() {
	var um_scoring = (parseFloat($("input[name='um_scoring_summary']").val().replace(/,/g, "")) > 0 ? parseFloat($("input[name='um_scoring_summary']").val().replace(/,/g, "")) : 0).toFixed(2);
	var um_propose = (parseFloat($("input[name='um_propose_summary']").val().replace(/,/g, "")) > 0 ? parseFloat($("input[name='um_propose_summary']").val().replace(/,/g, "")) : 0).toFixed(2);
	var um_nilai_jaminan = (parseFloat($("input[name='um_nilai_jaminan_summary']").val().replace(/,/g, "")) > 0 ? parseFloat($("input[name='um_nilai_jaminan_summary']").val().replace(/,/g, "")) : 0).toFixed(2);

	var um_setujui = Math.min(um_scoring, um_propose, um_nilai_jaminan);
	$("input[name='um_setuju_summary']").val(numberWithCommas(parseFloat(um_setujui).toFixed(2)));
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
