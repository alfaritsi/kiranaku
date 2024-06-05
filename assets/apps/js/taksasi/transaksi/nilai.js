$(document).ready(function () {
	//get jadwal
	var id_jadwal = $('input[name="id_jadwal"]').val();
	$.ajax({
		url: baseURL+'taksasi/transaksi/get/data',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_jadwal : id_jadwal
		},
		success: function(data){
			$.each(data, function(i, v){
				$("input[name='nama']").val(v.caption_nama);
				$("input[name='nama_tahap']").val(v.caption_nama_tahap);
				$("input[name='pass_grade']").val(v.pass_grade);
				$("input[name='id_program_batch']").val(v.id_program_batch);
				//detail peserta
				if (v.arr_peserta) {
					let no = 0;
					let output = "";
					$.each(v.arr_peserta, function(a, b) {	
						if(b.lulus==1){
							let arr_nilai	= b.list_nilai.slice(0, -1).split("|");
							let no_nilai	= 0;
							let total_nilai	= 0;
							no++;
							output += "<tr class='row-peserta peserta" + no + "'>";
							output += "	<td>"+b.nama_karyawan+"</td>";
							output += "	<td class='text-center'>"+b.nik;
							output += "		<input type='hidden' class='form-control' name='nik[]' value='"+b.nik+"'/>";
							output += "	</td>";
							$.each(v.arr_bobot, function(x, y) {	
								let post_nilai	=  "input_nilai_"+y.id_nilai+"[]";
								let readonly 	= ((y.otomatis=='y')||(v.status_bokin==1))?'readonly':'';
								total_nilai	+= parseFloat(arr_nilai[no_nilai])*(y.bobot/100);
								output += "	<td>";
								output += "		<input type='text' class='form-control form-control-hide text-center angka cek_min_max' name='"+post_nilai+"' data-bobot='"+y.bobot+"' value='"+arr_nilai[no_nilai]+"' required='required' "+readonly+"/>";
								output += "	</td>";
								no_nilai++;
							});
							let caption_lulus 	= (total_nilai>=v.pass_grade)?"<span class='label label-success'>LULUS</span>":"<span class='label label-danger'>TIDAK LULUS</span>";
							let lulus_bokin 	= (total_nilai>=v.pass_grade)?1:0;
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center angka' name='total[]' value='"+parseFloat(total_nilai).toFixed(2)+"' readonly/>";
							output += "		<input type='hidden' class='form-control text-center angka' name='lulus_bokin[]' value='"+lulus_bokin+"' readonly/>";
							output += "	</td>";
							output += "	<td class='text-center pass-desc'>"+caption_lulus+"</td>";
							output += "</tr>";
						}
					});
					$(".table-peserta tbody").html(output);
				}	
				if(v.status_bokin==1){
					$('#id_button').hide();
				}else{
					$('#id_button').show();
				}				
				
				
			});
		}
	});
	//change
    $(document).on("keyup", "input[name*='input_nilai_']", function() {
        if ($(this).val().replace(/,/g, "") < 0)
            $(this).val(0);
		
		let row 	= $(this).closest(".row-peserta");
		let values = $("input[name*='input_nilai_']", row);
		let final_nilai = 0;
		let pass_grade 	= $("input[name='pass_grade']").val();	
		$.each(values, function(i, v){
			let bobot = $(v).data("bobot");
			let nilai = $(v).val();
			final_nilai += +(bobot/100) * nilai;
		});
		$("input[name='total[]']", row).val(parseFloat(final_nilai).toFixed(2));
		if(final_nilai >= pass_grade){
			$("input[name='lulus_bokin[]']", row).val(1);
		}else{
			$("input[name='lulus_bokin[]']", row).val(0);
		}
		$(".pass-desc", row).html(final_nilai >= pass_grade? "<span class='label label-success'>LULUS</span>" : "<span class='label label-danger'>TIDAK LULUS</span>");
		
    });
	
	
	//datatable
	$('.my-datatable-extends-order').DataTable(
		{
			searching: false, paging: false, info: false
		}
	);
	//cek min max
    $(document).on("keyup", ".cek_min_max", function(e){
        var nilai = $(this).val();
		if(nilai<0){
			alert('Nilai Minimal 0');
			$(this).val(0);
		}
		if(nilai>100){
			alert('Nilai Miksimal 100');
			$(this).val(0);
		}
    });
 	//save nilai
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-taksasi-nilai");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-taksasi-nilai")[0]);

				$.ajax({
					url: baseURL+'taksasi/transaksi/save/nilai',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
					}
				});
			}else{
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
			}
		}
		e.preventDefault();
		return false;
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
