
$(document).ready(function () {
    get_data_provinsi();

    $(document).on("click", "#log_status", function () {
		$.ajax({
			url: baseURL + "umb/scoring/get/log-status",
			type: 'POST',
			dataType: 'JSON',
			data: {
				no_form: $("input[name='no_form']").val().replace(/\-/g, "/"),
			},
			beforeSend: function () {
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
			success: function (data) {
				if (data) {
					$('.table-modals').DataTable().destroy();
					var t = $('.table-modals').DataTable({
						scrollX: true
					});
					t.clear().draw();

					$.each(data, function (i, v) {
						var myrow = t.row.add([
							v.no_form_scoring,
							generateDatetimeFormat(v.tgl_status),
							"<span style='text-transform: capitalize'>" + v.action + "</span> oleh <br> <span class='label label-info'>" + v.nama_role + " : " + v.nama + "</label>",
							v.comment
						]).draw(false);
					});
				}
			},
			complete: function () {
				setTimeout(function () {
					adjustDatatableWidth();
				}, 1000);
				$('#KiranaModals').modal('show');
			}
		});
    });
    
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

			},100);
		}
	});
    /*============LOAD DATA SCORING============*/
    $.ajax({
        url: baseURL + "umb/scoring/get/scoring",
        type: 'POST',
        dataType: 'JSON',
        data: {
            no_form: $("input[name='no_form']").val().replace(/\-/g, "/")
        },
        beforeSend: function () {
            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
            $("body .overlay-wrapper").append(overlay);
        },
        success: function (data) {
            if (data) {
                $(".page-wrapper").show();

                //load scoring data
                $.each(data, function (i, v) {
                    if (v.no_sap !== null) {
                        $("#no_sap").val(v.no_sap);
                        $(".hide_no_sap").removeClass("hide");
                    }

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
                    $.each(v.kabupaten, function (x, y) {
                        var control = $("select[name='kabupaten[]']").empty().data("select2");
                        var adapter = control.dataAdapter;
                        array.push({"id": y, "nama_kab": v.kabupaten_nama[x]});
                        adapter.addOptions(adapter.convertToOptions(array));
                        $("select[name='kabupaten[]']").trigger('change');
                    });
                    $("select[name='kabupaten[]']").val(v.kabupaten).trigger('change');

                    $("input[name='tgl_pengajuan']").val(generateDateFormat(v.tanggal));
                    $("input[name='tgl_berakhir']").val(v.tanggal_akhir);
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

                    if (v.file_berita_acara !== null) {
                        $("input[name='caption_file_ba']").val(v.file_berita_acara.split('/').pop());
                        $("#view_ba").val(v.file_berita_acara);
                    }

                    $("input[name='um_propose']").val((v.um_minta ? numberWithCommas(v.um_minta) : 0));
                    $("input[name='waktu']").val(v.waktu_selesai);             
                                     
				});
				
				generate_historical();

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
    
    $(document).on("change", "select[name='provinsi[]']", function () {
		$("select[name='kabupaten[]']").val(null).trigger("change");
	});

});

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
