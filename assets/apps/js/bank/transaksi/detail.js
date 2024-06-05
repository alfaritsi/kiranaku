$(document).ready(function () {

	// $(document).on("change", ".tanggal", function(e) {
	$('#tanggal').datepicker({
		maxDate: new Date(),
		format: 'dd.mm.yyyy',
		autoclose: true
	});

    //history
    $(document).on("click", ".history", function() {
        var id_data_temp = $(this).data("id_data_temp");
        $.ajax({
			url: baseURL + 'bank/transaksi/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data_temp: id_data_temp
            },
            success: function(data) {
				var det_pengajuan	= "";
					det_pengajuan	+= 		'<table class="table table-bordered datatable-vendor">';
					det_pengajuan	+= 		'	<thead>';
					det_pengajuan	+= 		'		<tr>';
					det_pengajuan	+= 		'			<th>No. Bank Specimen</th>';
					det_pengajuan	+= 		'			<th>Tanggal Status</th>';
					det_pengajuan	+= 		'			<th>Status</th>';
					det_pengajuan	+= 		'			<th>Comment</th>';
					det_pengajuan	+= 		'		</tr>';
					det_pengajuan	+= 		'	</thead>';
					det_pengajuan	+= 		'	<tbody>';

                $.each(data, function(i, v) {
					det_pengajuan	+= 		'		<tr>';
					det_pengajuan	+= 		'			<td>'+v.nomor_specimen+'</td>';
					det_pengajuan	+= 		'			<td>'+v.tanggal_format+'<br>'+v.jam_format+'</td>';
					det_pengajuan	+= 		'			<td>Approve Oleh :<br><span class="label label-info">'+v.role_approval+' : '+v.nama_approval+'</span></td>';
					det_pengajuan	+= 		'			<td>'+v.label_catatan+'</span></td>';
					det_pengajuan	+= 		'		</tr>';
                });
					det_pengajuan	+= 		'	</tbody>';
					det_pengajuan	+= 		'</table>';
					$("#histori_pengajuan").html(det_pengajuan);
				
            },
            complete: function() {
				setTimeout(function () {
					$("table.datatable-vendor").DataTable({
						"bLengthChange": false
					}).columns.adjust();
				}, 1500);				
                $('#modal-history').modal('show');
            }
        });
    });


	//set hide form
	$('#form_pembukaan').hide();
	$('#form_penutupan').hide();
	$('#form_perubahan').hide();
	$('#show_nama_bank').hide();
	$('#show_nama_bank').hide();
	//set hide button
	$("#btn_decline").hide();
	$("#btn_update").hide();
	$("#btn_approve").hide();

	//get data temp
	var id_user = $('input[name="id_user"]').val();
	var id_data_temp = $('input[name="id_data_temp"]').val();
	var act = $('input[name="act"]').val();
	var status = $('input[name="status"]').val();
	var status_approve = $('input[name="status_approve"]').val();
	var status_decline = $('input[name="status_decline"]').val();
	$.ajax({
		url: baseURL + 'bank/transaksi/get/data_temp',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_data_temp: id_data_temp
		},
		success: function (data) {
			$.each(data, function (i, v) {
				//set all form non required and disabled
				$('.form-control-utama').prop('required', false);
				$('.form-control-utama').prop('disabled', true);
				$('.form-control-pembukaan').prop('required', false);
				$('.form-control-pembukaan').prop('disabled', true);
				$('.form-control-penutupan').prop('required', false);
				$('.form-control-penutupan').prop('disabled', true);
				$('.form-control-perubahan').prop('required', false);
				$('.form-control-perubahan').prop('disabled', true);

				// console.log(data);
				$("select[name='jenis_pengajuan']").val(v.jenis_pengajuan).trigger("change.select2");
				$("input[name='nomor']").val(v.nomor);
				$("input[name='tanggal']").val(v.tanggal_format);
				$("select[name='pabrik']").val(v.pabrik).trigger("change.select2");
				$("textarea[name='latar_belakang']").val(v.latar_belakang);
				$("input[name='nama_bank']").val(v.nama_bank);
				$("input[name='cabang_bank']").val(v.cabang_bank);
				$("input[name='nomor_rekening']").val(v.nomor_rekening);
				$("select[name='mata_uang']").val(v.mata_uang).trigger("change.select2");

				if (v.jenis_pengajuan == 'pembukaan') {
					$('#form_pembukaan').show();

					$('#show_nama_bank').show();
					$('#show_nama_bank_auto').hide();

					$("select[name='tujuan']").val(v.tujuan).trigger("change.select2");
					$("input[name='tujuan_detail']").val(v.tujuan_detail);
					$("input[name='no_coa']").val(v.no_coa);
					//buat auto complete prioritas1
					var control = $('#prioritas1').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = v.nama_prioritas1 + ' - [' + v.prioritas1 + ']';
					adapter.addOptions(adapter.convertToOptions([{ "id": v.prioritas1, "nama": nama }]));
					$('#prioritas1').trigger('change');
					//buat auto complete prioritas2
					var control = $('#prioritas2').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = v.nama_prioritas2 + ' - [' + v.prioritas1 + ']';
					adapter.addOptions(adapter.convertToOptions([{ "id": v.prioritas2, "nama": nama }]));
					$('#prioritas2').trigger('change');
					//buat autocomplete pendamping
					if (v.list_pendamping != null) {
						var pendamping = v.pendamping ? v.pendamping.split(",") : "";
						var arr_list_pendamping = v.list_pendamping.slice(0, -1).split(",");
						var array = [];
						$.each(arr_list_pendamping, function (x, y) {
							var arr_pendamping = y ? y.split("|") : "";
							var control = $('#pendamping').empty().data('select2');
							var adapter = control.dataAdapter;
							array.push({ "id": pendamping[x], "text": arr_pendamping[1] + ' - [' + arr_pendamping[0] + ']' });
							adapter.addOptions(adapter.convertToOptions(array));
							$('#pendamping').trigger('change');
						});
						console.log(array);
						$('#pendamping').val(pendamping).trigger('change');
					}
					//set show button edit
					if ((v.login_buat == id_user) && (v.status != 99) && ((v.status == 1) || (v.status == 3)) && (act == 'edt')) {
						$('.form-control-utama').prop('required', true);
						$('.form-control-utama').prop('disabled', false);
						$('.form-control-pembukaan').prop('required', true);
						$('.form-control-pembukaan').prop('disabled', false);
						$('#jenis_pengajuan').prop('required', false);
						$('#jenis_pengajuan').prop('disabled', true);
						$('#id_data').prop('required', false);
						$('#nomor_rekening').prop('required', false);
						$('#pabrik').prop('disabled', true);
						$('#no_coa').prop('required', false);
						$("#btn_decline").hide();
						$("#btn_update").show();
						$("#btn_approve").hide();

					} else {
						if (v.status == status) {
							if ((v.status == 1) || (v.status == 3)) {
								$("#btn_decline").hide();
							} else {
								$("#btn_decline").show();
							}
							$("#btn_update").hide();
							$("#btn_approve").show();
						}
					}

				} else if (v.jenis_pengajuan == 'penutupan') {
					$('#form_penutupan').show();
					$('#show_nama_bank').hide();
					$('#show_nama_bank_auto').show();
					// $("select[name='tujuan']").val(v.tujuan).trigger("change.select2");
					$("input[name='nama_bank_tujuan']").val(v.nama_bank_tujuan);
					$("input[name='cabang_bank_tujuan']").val(v.cabang_bank_tujuan);
					$("input[name='no_coa_tujuan']").val(v.no_coa_tujuan);
					$("input[name='nomor_rekening_tujuan']").val(v.nomor_rekening_tujuan);
					$("input[name='sisa_dana']").val(numberWithCommas(parseFloat(v.sisa_dana).toFixed(2)));

					//buat auto id_data selected
					$.each(v.arr_data_bank, function (x, y) {
						//buat auto id_data
						var control = $('#id_data').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = '(' + y.nama_bank + ') ' + y.cabang_bank + ' - ' + y.nomor_rekening;
						adapter.addOptions(adapter.convertToOptions([{ "id": v.id_data, "nama": nama }]));
						console.log(adapter);
						console.log(nama);
						$('#id_data').trigger('change');

					});
					//buat auto id_data_tujuan selected
					$.each(v.arr_data_bank_tujuan, function (x, y) {
						//buat auto id_data_tujuan
						var control = $('#id_data_tujuan').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = '(' + y.nama_bank + ') ' + y.cabang_bank + ' - ' + y.nomor_rekening;
						adapter.addOptions(adapter.convertToOptions([{ "id": v.id_data_tujuan, "nama": nama }]));
						console.log(adapter);
						console.log(nama);
						$('#id_data_tujuan').trigger('change');

					});
					//set show button edit
					if ((v.login_buat == id_user) && (v.status != 99) && ((v.status == 1) || (v.status == 3)) && (act == 'edt')) {
						$('.form-control-utama').prop('required', false);
						$('.form-control-utama').prop('disabled', true);
						$('.form-control-penutupan').prop('required', false);
						$('.form-control-penutupan').prop('disabled', true);

						$('#tanggal').prop('required', true);
						$('#tanggal').prop('disabled', false);
						$('#pabrik').prop('required', true);
						$('#pabrik').prop('disabled', false);
						$('#latar_belakang').prop('required', true);
						$('#latar_belakang').prop('disabled', false);
						$('#id_data').prop('required', true);
						$('#id_data').prop('disabled', false);
						$('#sisa_dana').prop('required', true);
						$('#sisa_dana').prop('disabled', false);
						$('#id_data_tujuan').prop('required', true);
						$('#id_data_tujuan').prop('disabled', false);
						$("#btn_decline").hide();
						$("#btn_update").show();
						$("#btn_approve").hide();
					} else {
						if (v.status == status) {
							if ((v.status == 1) || (v.status == 3)) {
								$("#btn_decline").hide();
							} else {
								$("#btn_decline").show();
							}

							$("#btn_update").hide();
							$("#btn_approve").show();
						}
					}
				} else if (v.jenis_pengajuan == 'perubahan') {
					$('#form_pembukaan').hide();
					$('#form_penutupan').hide();
					$('#form_perubahan').show();
					//input nama bank
					$('#show_nama_bank').hide();
					$('#show_nama_bank_auto').show();

					$.each(v.arr_data_bank, function (x, y) {
						$("input[name='id_data']").val(y.id_data);
						$("input[name='nama_bank']").val(y.nama_bank);
						$("input[name='nomor_rekening']").val(y.nomor_rekening);
						$("input[name='cabang_bank']").val(y.cabang_bank);
						$("select[name='mata_uang']").val(y.mata_uang).trigger("change.select2");
						$("select[name='tujuan_old']").val(y.tujuan).trigger("change.select2");
						$("input[name='tujuan_detail_old']").val(y.tujuan_detail);

						//buat auto id_data selected
						$.each(v.arr_data_bank, function (x, y) {
							//buat auto id_data
							var control = $('#id_data').empty().data('select2');
							var adapter = control.dataAdapter;
							var nama = '(' + y.nama_bank + ') ' + y.cabang_bank + ' - ' + y.nomor_rekening;
							adapter.addOptions(adapter.convertToOptions([{ "id": v.id_data, "nama": nama }]));
							$('#id_data').trigger('change');
						});

						//buat prioritas1_old
						var control = $('#prioritas1_old').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = y.nama_prioritas1 + ' - [' + y.prioritas1 + ']';
						adapter.addOptions(adapter.convertToOptions([{ "id": y.prioritas1, "nama": nama }]));
						$('#prioritas1_old').trigger('change');

						//buat prioritas2_old
						var control = $('#prioritas2_old').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = y.nama_prioritas2 + ' - [' + y.prioritas2 + ']';
						adapter.addOptions(adapter.convertToOptions([{ "id": y.prioritas2, "nama": nama }]));
						$('#prioritas2_old').trigger('change');

						//buat auto pendamping old
						if (y.list_pendamping != null) {
							var pendamping = y.pendamping ? y.pendamping.split(",") : "";
							var arr_list_pendamping = y.list_pendamping.slice(0, -1).split(",");
							var array = [];
							$.each(arr_list_pendamping, function (x, z) {
								var arr_pendamping = z ? z.split("|") : "";
								var control = $('#pendamping_old').empty().data('select2');
								var adapter = control.dataAdapter;
								array.push({ "id": pendamping[x], "text": arr_pendamping[1] + ' - [' + arr_pendamping[0] + ']' });
								adapter.addOptions(adapter.convertToOptions(array));
								$('#pendamping_old').trigger('change');
							});
							// console.log(array);
							$('#pendamping_old').val(pendamping).trigger('change');
						}

					});
					$("select[name='tujuan_new']").val(v.tujuan).trigger("change.select2");
					$("input[name='tujuan_detail_new']").val(v.tujuan_detail);

					//buat prioritas1_new
					var control = $('#prioritas1_new').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = v.nama_prioritas1 + ' - [' + v.prioritas1 + ']';
					adapter.addOptions(adapter.convertToOptions([{ "id": v.prioritas1, "nama": nama }]));
					$('#prioritas1_new').trigger('change');

					//buat prioritas2_new
					var control = $('#prioritas2_new').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = v.nama_prioritas2 + ' - [' + v.prioritas2 + ']';
					adapter.addOptions(adapter.convertToOptions([{ "id": v.prioritas2, "nama": nama }]));
					$('#prioritas2_new').trigger('change');

					//buat auto pendamping new
					if (v.list_pendamping != null) {
						var pendamping = v.pendamping ? v.pendamping.split(",") : "";
						var arr_list_pendamping = v.list_pendamping.slice(0, -1).split(",");
						var array = [];
						$.each(arr_list_pendamping, function (x, y) {
							var arr_pendamping = y ? y.split("|") : "";
							var control = $('#pendamping_new').empty().data('select2');
							var adapter = control.dataAdapter;
							array.push({ "id": pendamping[x], "text": arr_pendamping[1] + ' - [' + arr_pendamping[0] + ']' });
							adapter.addOptions(adapter.convertToOptions(array));
							$('#pendamping_new').trigger('change');
						});
						// console.log(array);
						$('#pendamping_new').val(pendamping).trigger('change');
					}

					//set show button edit
					if ((v.login_buat == id_user) && (v.status != 99) && ((v.status == 1) || (v.status == 3)) && (act == 'edt')) {
						$('.form-control-utama').prop('required', false);
						$('.form-control-utama').prop('disabled', true);
						$('.form-control-perubahan').prop('required', false);
						$('.form-control-perubahan').prop('disabled', true);

						$('#tanggal').prop('required', true);
						$('#tanggal').prop('disabled', false);
						$('#pabrik').prop('required', true);
						$('#pabrik').prop('disabled', false);
						$('#latar_belakang').prop('required', true);
						$('#latar_belakang').prop('disabled', false);
						$('#id_data').prop('required', true);
						$('#id_data').prop('disabled', false);
						$('#tujuan_new').prop('disabled', false);
						$('#tujuan_new').prop('required', true);
						$('#tujuan_detail_new').prop('disabled', false);
						$('#tujuan_detail_new').prop('required', true);
						$('#prioritas1_new').prop('disabled', false);
						$('#prioritas1_new').prop('required', true);
						$('#prioritas2_new').prop('disabled', false);
						$('#prioritas2_new').prop('required', true);
						$('#pendamping_new').prop('disabled', false);
						$('#pendamping_new').prop('required', false);

						//button
						$("#btn_decline").hide();
						$("#btn_update").show();
						$("#btn_approve").hide();
					} else {
						if (v.status == status) {
							if ((v.status == 1) || (v.status == 3)) {
								$("#btn_decline").hide();
							} else {
								$("#btn_decline").show();
							}
							$("#btn_update").hide();
							$("#btn_approve").show();
						}
					}
				} else {

				}

			});
		},
		complete: function () {
			// $("#btn_save_extend").hide();
			// $("#btn_save_extend_sap").hide();
			// $("#btn_decline_extend").hide();
		}
	});

	//change id_data
	$(document).on("change", "#id_data", function (e) {
		var id_data = $(this).val();
		$.ajax({
			url: baseURL + 'bank/transaksi/get/data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_data: id_data
			},
			success: function (data) {
				$.each(data, function (i, v) {
					// console.log(data);
					//penutupan
					$("input[name='id_data']").val(v.id_data);
					$("input[name='nama_bank']").val(v.nama_bank);
					$("input[name='cabang_bank']").val(v.cabang_bank);
					$("input[name='nomor_rekening']").val(v.nomor_rekening);
					$("select[name='mata_uang']").val(v.mata_uang).trigger("change.select2");
					//perubahan
					$("select[name='tujuan_old']").val(v.tujuan).trigger("change.select2");
					$("input[name='tujuan_detail_old']").val(v.tujuan_detail);

					//buat auto prioritas1 old
					var control = $('#prioritas1_old').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = v.nama_prioritas1 + ' - [' + v.prioritas1 + ']';
					adapter.addOptions(adapter.convertToOptions([{ "id": v.prioritas1, "nama": nama }]));
					$('#prioritas1_old').trigger('change');

					//buat auto prioritas2 old
					var control = $('#prioritas2_old').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = v.nama_prioritas2 + ' - [' + v.prioritas2 + ']';
					adapter.addOptions(adapter.convertToOptions([{ "id": v.prioritas2, "nama": nama }]));
					$('#prioritas2_old').trigger('change');

					// //buat auto pendamping old
					// if(v.list_pendamping!=null){
					// var pendamping			= v.pendamping.split(",");
					// var arr_list_pendamping	= v.list_pendamping.slice(0, -1).split(",");
					// var array  = [];
					// $.each(arr_list_pendamping, function(x, y){
					// var arr_pendamping	= y.split("|");
					// var control = $('#pendamping_old').empty().data('select2');
					// var adapter = control.dataAdapter;
					// array.push({"id":pendamping[x],"text":arr_pendamping[1]+' - ['+ arr_pendamping[0]+ ']'});
					// adapter.addOptions(adapter.convertToOptions(array));
					// $('#pendamping_old').trigger('change');
					// });
					// console.log(array);
					// $('#pendamping_old').val(pendamping).trigger('change');
					// }	


				});
			}
		});
	});

	//change jenis pengajuan
	$(document).on("change", "#jenis_pengajuan", function (e) {
		var jenis_pengajuan = $(this).val();
		if (jenis_pengajuan == 'pembukaan') {
			$('#form_pembukaan').show();
			$('#form_penutupan').hide();
			$('#form_perubahan').hide();
			$('.form-control-pembukaan').prop('required', true);
			$('.form-control-penutupan').prop('required', false);
			$('.form-control-perubahan').prop('required', false);
			$('#no_coa').prop('required', false);
		} else if (jenis_pengajuan == 'penutupan') {
			$('#form_pembukaan').hide();
			$('#form_penutupan').show();
			$('#form_perubahan').hide();
			$('.form-control-pembukaan').prop('required', false);
			$('.form-control-penutupan').prop('required', true);
			$('.form-control-perubahan').prop('required', false);
		} else if (jenis_pengajuan == 'perubahan') {
			$('#form_pembukaan').hide();
			$('#form_penutupan').hide();
			$('#form_perubahan').show();
			$('.form-control-pembukaan').prop('required', false);
			$('.form-control-penutupan').prop('required', false);
			$('.form-control-perubahan').prop('required', true);
		} else {
			$('#form_pembukaan').hide();
			$('#form_penutupan').hide();
			$('#form_perubahan').hide();
		}
	});
	//change id_data_tujuan
	$(document).on("change", "#id_data_tujuan", function (e) {
		var id_data_tujuan = $(this).val();
		$.ajax({
			url: baseURL + 'bank/transaksi/get/data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_data: id_data_tujuan
			},
			success: function (data) {
				$.each(data, function (i, v) {
					// console.log(data);
					$("input[name='nama_bank_tujuan']").val(v.nama_bank);
					$("input[name='cabang_bank_tujuan']").val(v.cabang_bank);
					$("input[name='no_coa_tujuan']").val(v.no_coa);
					$("input[name='nomor_rekening_tujuan']").val(v.nomor_rekening);
				});
			}
		});
	});

	//change tujuan
	$(document).on("change", "#tujuan", function (e) {
		var tujuan = $(this).val();
		if (tujuan == 'bokar') {
			$('#tujuan_detail').prop('required', false);
		} else {
			$('#tujuan_detail').prop('required', true);
		}
	});

	//auto complete prioritas1
	$("select[name='prioritas1']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'bank/transaksi/get/user_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					jenis: 'prioritas1',
					pabrik: $("select[name='pabrik']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.posst) $("input[name='caption']").val(repo.posst);
			if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
			else return repo.nama;
		}
	});
	$("#prioritas1").on('select2:select', function (e) {
		var id = e.params.data.id;
		console.log(id);
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});
	//auto complete prioritas1_old
	$("select[name='prioritas1_old']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'bank/transaksi/get/user_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					jenis: 'prioritas1',
					pabrik: $("select[name='pabrik']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.posst) $("input[name='caption']").val(repo.posst);
			if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
			else return repo.nama;
		}
	});
	$("#prioritas1_old").on('select2:select', function (e) {
		var id = e.params.data.id;
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});
	//auto complete prioritas1_new
	$("select[name='prioritas1_new']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'bank/transaksi/get/user_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					jenis: 'prioritas1',
					pabrik: $("select[name='pabrik']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.posst) $("input[name='caption']").val(repo.posst);
			if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
			else return repo.nama;
		}
	});
	$("#prioritas1_new").on('select2:select', function (e) {
		var id = e.params.data.id;
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});

	//auto complete prioritas2
	$("select[name='prioritas2']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'bank/transaksi/get/user_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					jenis: 'prioritas2',
					pabrik: $("select[name='pabrik']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.posst) $("input[name='caption']").val(repo.posst);
			if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
			else return repo.nama;
		}
	});
	$("#prioritas2").on('select2:select', function (e) {
		var id = e.params.data.id;
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});
	//auto complete prioritas2_old
	$("select[name='prioritas2_old']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'bank/transaksi/get/user_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					jenis: 'prioritas2',
					pabrik: $("select[name='pabrik']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.posst) $("input[name='caption']").val(repo.posst);
			if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
			else return repo.nama;
		}
	});
	$("select[name='prioritas2_old']").on('select2:select', function (e) {
		var id = e.params.data.id;
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});

	//auto complete prioritas2_new
	$("select[name='prioritas2_new']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'bank/transaksi/get/user_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					jenis: 'prioritas2',
					pabrik: $("select[name='pabrik']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.posst) $("input[name='caption']").val(repo.posst);
			if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
			else return repo.nama;
		}
	});
	$("select[name='prioritas2_new']").on('select2:select', function (e) {
		var id = e.params.data.id;
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});

	//autocomplete pendamping
	// $("select[name='pendamping[]']").select2({
	$("#pendamping").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'bank/transaksi/get/user_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					jenis: 'pendamping',
					pabrik: $("select[name='pabrik']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			console.log(repo);
			if (repo.nama) $("input[name='caption']").val(repo.nama);
			if (repo.text) return repo.text;
			else return repo.nama + ' - [' + repo.nik + ']';
			// return repo.text;
		}
	});

	// $("select[name='pendamping[]']").on('select2:select', function(e){
	$("#pendamping").on('select2:select', function (e) {
		var id = e.params.data.id;
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});

	//auto complete pendamping_old
	// $("select[name='pendamping_old[]']").select2({
	$("#pendamping_old").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'bank/transaksi/get/user_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					jenis: 'pendamping',
					pabrik: $("select[name='pabrik']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			// console.log(repo);	
			if (repo.nama) $("input[name='caption']").val(repo.nama);
			if (repo.text) return repo.text;
			else return repo.nama + ' - [' + repo.nik + ']';
		}
	});

	// $("select[name='pendamping_old[]']").on('select2:select', function(e){
	$("#pendamping_old").on('select2:select', function (e) {
		var id = e.params.data.id;
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});

	//auto complete pendamping_new
	$("#pendamping_new").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'bank/transaksi/get/user_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					jenis: 'pendamping',
					pabrik: $("select[name='pabrik']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			// console.log(repo);	
			if (repo.nama) $("input[name='caption']").val(repo.nama);
			if (repo.text) return repo.text;
			else return repo.nama + ' - [' + repo.nik + ']';
		}
	});

	$("#pendamping_new").on('select2:select', function (e) {
		var id = e.params.data.id;
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});

	//auto complete id_data
	$("select[name='id_data']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			// url: baseURL+'bank/transaksi/get/user_auto',
			url: baseURL + 'bank/transaksi/get/rekening_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					q: params.term, // search term
					pabrik: $("select[name='pabrik']").val(),
					id_data: null,
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">(' + repo.nama_bank + ') ' + repo.cabang_bank + ' - ' + repo.nomor_rekening + '</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.nama_bank && repo.cabang_bank) return '(' + repo.nama_bank + ') ' + repo.cabang_bank + ' - ' + repo.nomor_rekening;
			else return repo.nama;
		}
	});
	$("select[name='id_data']").on('select2:select', function (e) {
		var id = e.params.data.id;
		console.log(id);
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});

	//auto complete id_data_tujuan auto
	$("select[name='id_data_tujuan']").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			// url: baseURL+'bank/transaksi/get/user_auto',
			url: baseURL + 'bank/transaksi/get/rekening_auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					autocomplete: true,
					q: params.term, // search term
					pabrik: $("select[name='pabrik']").val(),
					id_data: $("select[name='id_data']").val(),
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
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">(' + repo.nama_bank + ') ' + repo.cabang_bank + ' - ' + repo.nomor_rekening + '</div>';
			return markup;
		},
		templateSelection: function (repo) {
			// console.log(repo);
			if (repo.nama_bank && repo.cabang_bank) return '(' + repo.nama_bank + ') ' + repo.cabang_bank + ' - ' + repo.nomor_rekening;
			else return repo.nama;
		}

	});
	$("select[name='id_data_tujuan']").on('select2:select', function (e) {
		var id = e.params.data.id;
		var option = $(e.target).children('[value="' + id + '"]');
		option.detach();
		$(e.target).append(option).change();
	});

	//save update
	$(document).on("click", "button[name='btn_update']", function (e) {
		var empty_form = validate('.form-bank-transaksi');
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-bank-transaksi")[0]);
				$.ajax({
					url: baseURL + 'bank/transaksi/save/update',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function () {
								window.location = baseURL + 'bank/transaksi/approve';
								// window.close();
								// history.go(-2);

							});
						} else {
							$("input[name='isproses']").val(0);
							swal('Error', data.msg, 'error');
						}
					}
				});
			} else {
				swal({
					title: "Silahkan tunggu proses selesai.",
					icon: 'info'
				});
			}
		}
		e.preventDefault();
		return false;
	});

	// //save approve(diganti dengan add comment)
	// $(document).on("click", "button[name='btn_approve']", function(e) {
	// var empty_form = validate('.form-bank-transaksi');
	// if (empty_form == 0) {
	// var isproses = $("input[name='isproses']").val();
	// if (isproses == 0) {
	// $("input[name='isproses']").val(1);
	// var formData = new FormData($(".form-bank-transaksi")[0]);
	// $.ajax({
	// url: baseURL + 'bank/transaksi/save/approve',
	// type: 'POST',
	// dataType: 'JSON',
	// data: formData,
	// contentType: false,
	// cache: false,
	// processData: false,
	// success: function(data) {
	// if (data.sts == 'OK') {
	// swal('Success', data.msg, 'success').then(function() {
	// window.location = baseURL + 'bank/transaksi/data';
	// // window.close();
	// // location.reload();
	// // history.go(-2);
	// });
	// } else {
	// $("input[name='isproses']").val(0);
	// swal('Error', data.msg, 'error');
	// }
	// }
	// });
	// } else {
	// swal({
	// title: "Silahkan tunggu proses selesai.",
	// icon: 'info'
	// });
	// }
	// }
	// e.preventDefault();
	// return false;
	// });

	//modal approve
	$(document).on("click", "#btn_approve", function () {
		var id_user = $(this).data("id_user");
		var id_data_temp = $(this).data("id_data_temp");
		var status = $(this).data("status");
		$("input[name='id_user']").val(id_user);
		$("input[name='id_data_temp']").val(id_data_temp);
		$("input[name='status']").val(status);
		$('#modal_approve').modal('show');
	});
	//save approve
	$(document).on("click", "button[name='btn_approve_save']", function (e) {
		var empty_form = validate('.form-bank-transaksi-approve');
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-bank-transaksi-approve")[0]);
				$.ajax({
					url: baseURL + 'bank/transaksi/save/approve',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function () {
								window.location = baseURL + 'bank/transaksi/approve';
								// window.close();
								// location.reload();
								// history.go(-2);
							});
						} else {
							$("input[name='isproses']").val(0);
							swal('Error', data.msg, 'error');
						}
					}
				});
			} else {
				swal({
					title: "Silahkan tunggu proses selesai.",
					icon: 'info'
				});
			}
		}
		e.preventDefault();
		return false;
	});

	//modal decline
	$(document).on("click", "#btn_decline", function () {
		var id_user = $(this).data("id_user");
		var id_data_temp = $(this).data("id_data_temp");
		var status = $(this).data("status");
		$("input[name='id_user']").val(id_user);
		$("input[name='id_data_temp']").val(id_data_temp);
		$("input[name='status']").val(status);
		$('#modal_decline').modal('show');
	});

	//save decline
	// $(document).on("click", "button[name='btn_decline']", function(e) {
	$(document).on("click", "button[name='btn_decline_save']", function (e) {
		var empty_form = validate('.form-bank-transaksi-decline');
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-bank-transaksi-decline")[0]);
				$.ajax({
					url: baseURL + 'bank/transaksi/save/decline',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function () {
								window.location = baseURL + 'bank/transaksi/approve';
								// window.close();
								// location.reload();
								// history.go(-2);
							});
						} else {
							$("input[name='isproses']").val(0);
							swal('Error', data.msg, 'error');
						}
					}
				});
			} else {
				swal({
					title: "Silahkan tunggu proses selesai.",
					icon: 'info'
				});
			}
		}
		e.preventDefault();
		return false;
	});
	//=========================

});

function resetForm_use($form, $act) {
	$('#myModalLabel').html("Create Vendor");
	$('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
	$form.find('input:text, input:password, input:file,  textarea, input:hidden').val("");
	$form.find('input:text, input:password, input:file,  textarea, input:hidden').prop('disabled', false);
	$form.find('select').val(0);
	$form.find('select').prop('disabled', false);
	$form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
	$form.find('input:radio, input:checkbox').prop('disabled', false);

	// $('#service_level').val("").prop('disabled', false);
	$('#net_weight').val("").prop('disabled', false);
	$('#gross_weight').val("").prop('disabled', false);
	$("#plant").val(0).trigger("change");
	$("#vtweg").val(0).trigger("change");
	$("#sales_plant").find('checkbox').removeAttr('checked');
	$('.switch-onoff').bootstrapToggle('off');
	$('.switch-onoff').removeAttr('checked');;
	$('#plant_extend').prop('disabled', false);
	if ($act != 'edit') {
		$("#show_images").hide();
	}
	$("#gambar").show();
	$("#btn_save").show();
	$('#isproses').val("");
	$('#isconvert').val('0');
	$('#code').prop('disabled', true);
	$('#detail').prop('disabled', true);
	$('#status_do').prop('disabled', true);
	validateReset('.form-transaksi-vendor');
}


function validateReset(target = 'form') {
	var element = $("input, select, textarea", $(target));
	$.each(element, function (i, v) {
		if (v.tagName == 'SELECT' && v.nextSibling.firstChild != null) {
			v.nextSibling.firstChild.firstChild.style.borderColor = "#d2d6de";
		}
		v.style.borderColor = "#d2d6de";
	});
}
function rupiah(num) {
	// var number = parseInt(num);
	var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
	if (str.indexOf(",") > 0) {
		parts = str.split(",");
		str = parts[0];
	}
	str = str.split("").reverse();
	for (var j = 0, len = str.length; j < len; j++) {
		if (str[j] != ".") {
			output.push(str[j]);
			if (i % 3 == 0 && j < (len - 1)) {
				output.push(",");
			}
			i++;
		}
	}
	formatted = output.reverse().join("");
	return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
};
