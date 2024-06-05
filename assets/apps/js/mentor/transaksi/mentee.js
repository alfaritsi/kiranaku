$(document).ready(function() {
	$('.tanggal').datepicker({
        format: 'dd.mm.yyyy',
	    autoclose: true
    });

    //open modal for add     
    $(document).on("click", "#add_mentee", function(e) {
        resetForm_use($('.form-input-mentee'));
		
        $('#add_modal').modal('show');
		$.ajax({
    		// url: baseURL+'mentor/master/get/status',
    		url: baseURL+'mentor/transaksi/get/range',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_status: 1,
				return: "json"
			},
			success: function(data){
				$(".modal-title").html("Form Penambahan Mentee");
				$.each(data, function(i, v){
					$("input[name='tanggal_sesi1_rencana']").val(v.tanggal_akhir_sesi1);
					$("input[name='tanggal_sesi2_rencana']").val(v.tanggal_akhir_sesi2);
					$("input[name='tanggal_dmc1_rencana']").val(v.tanggal_akhir_dmc1);
					$("input[name='tanggal_dmc2_rencana']").val(v.tanggal_akhir_dmc2);
					$("input[name='tanggal_dmc3_rencana']").val(v.tanggal_akhir_dmc3);
					$('.tanggal_sesi1_range').datepicker({
						startDate : v.tanggal_awal_sesi1,
						endDate   : v.tanggal_akhir_sesi1,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_sesi2_range').datepicker({
						startDate : v.tanggal_akhir_sesi1,
						endDate   : v.tanggal_akhir_sesi2,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc1_range').datepicker({
						startDate : v.tanggal_akhir_sesi2,
						endDate   : v.tanggal_akhir_dmc1,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc2_range').datepicker({
						startDate : v.tanggal_akhir_dmc1,
						endDate   : v.tanggal_akhir_dmc2,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc3_range').datepicker({
						startDate : v.tanggal_akhir_dmc2,
						endDate   : v.tanggal_maks,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
				});
			}
		});
		
    });
    $(document).on("change", ".tanggal_sesi1_range", function() {
		var tanggal	= $(this).val();
		var tanggal_buat = $("#tanggal_buat").val();
		$.ajax({
    		url: baseURL+'mentor/transaksi/get/range',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_status: 1,
				tanggal: tanggal,
				tanggal_buat: tanggal_buat,
				return: "json"
			},
			success: function(data){
				$(".modal-title").html("Form Penambahan Mentee");
				$.each(data, function(i, v){
					$("input[name='tanggal_sesi1_rencana']").val(v.tanggal_awal_sesi1);
					$("input[name='tanggal_sesi2_rencana']").val(v.tanggal_akhir_sesi1);
					$("input[name='tanggal_dmc1_rencana']").val(v.tanggal_akhir_sesi2);
					$("input[name='tanggal_dmc2_rencana']").val(v.tanggal_akhir_dmc1);
					$("input[name='tanggal_dmc3_rencana']").val(v.tanggal_akhir_dmc2);
					$('.tanggal_sesi2_range').datepicker({
						startDate : v.tanggal_awal_sesi1,
						endDate   : v.tanggal_akhir_sesi1,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc1_range').datepicker({
						startDate : v.tanggal_akhir_sesi1,
						endDate   : v.tanggal_akhir_sesi2,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc2_range').datepicker({
						startDate : v.tanggal_akhir_sesi2,
						endDate   : v.tanggal_akhir_dmc1,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc3_range').datepicker({
						startDate : v.tanggal_akhir_dmc1,
						endDate   : v.tanggal_maks,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
				});
			}
		});
    });
	
    $(document).on("change", ".tanggal_sesi2_range", function() {
		var tanggal	= $(this).val();
		var tanggal_buat = $("#tanggal_buat").val();
		$.ajax({
    		url: baseURL+'mentor/transaksi/get/range',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_status: 2,
				tanggal: tanggal,
				tanggal_buat: tanggal_buat,
				return: "json"
			},
			success: function(data){
				$(".modal-title").html("Form Penambahan Mentee");
				$.each(data, function(i, v){
					$("input[name='tanggal_sesi2_rencana']").val(v.tanggal_akhir_sesi1);
					$("input[name='tanggal_dmc1_rencana']").val(v.tanggal_akhir_sesi2);
					$("input[name='tanggal_dmc2_rencana']").val(v.tanggal_akhir_dmc1);
					$("input[name='tanggal_dmc3_rencana']").val(v.tanggal_akhir_dmc2);
					$('.tanggal_dmc1_range').datepicker({
						startDate : v.tanggal_akhir_sesi1,
						endDate   : v.tanggal_akhir_sesi2,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc2_range').datepicker({
						startDate : v.tanggal_akhir_sesi2,
						endDate   : v.tanggal_akhir_dmc1,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc3_range').datepicker({
						startDate : v.tanggal_akhir_dmc1,
						endDate   : v.tanggal_maks,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
				});
			}
		});
    });
	
    $(document).on("change", ".tanggal_dmc1_range", function() {
		var tanggal	= $(this).val();
		var tanggal_buat = $("#tanggal_buat").val();
		$.ajax({
    		url: baseURL+'mentor/transaksi/get/range',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_status: 3,
				tanggal: tanggal,
				tanggal_buat: tanggal_buat,
				return: "json"
			},
			success: function(data){
				$(".modal-title").html("Form Penambahan Mentee");
				$.each(data, function(i, v){
					$("input[name='tanggal_dmc1_rencana']").val(v.tanggal_akhir_sesi2);
					$("input[name='tanggal_dmc2_rencana']").val(v.tanggal_akhir_dmc1);
					$("input[name='tanggal_dmc3_rencana']").val(v.tanggal_akhir_dmc2);
					$('.tanggal_dmc2_range').datepicker({
						startDate : v.tanggal_akhir_sesi2,
						endDate   : v.tanggal_akhir_dmc1,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc3_range').datepicker({
						startDate : v.tanggal_akhir_dmc1,
						endDate   : v.tanggal_maks,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
				});
			}
		});
    });

    $(document).on("change", ".tanggal_dmc2_range", function() {
		var tanggal	= $(this).val();
		var tanggal_buat = $("#tanggal_buat").val();
		$.ajax({
    		url: baseURL+'mentor/transaksi/get/range',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_status: 4,
				tanggal: tanggal,
				tanggal_buat: tanggal_buat,
				return: "json"
			},
			success: function(data){
				$(".modal-title").html("Form Penambahan Mentee");
				$.each(data, function(i, v){
					$("input[name='tanggal_dmc2_rencana']").val(v.tanggal_akhir_dmc1);
					$("input[name='tanggal_dmc3_rencana']").val(v.tanggal_akhir_dmc2);
					$('.tanggal_dmc3_range').datepicker({
						startDate : v.tanggal_akhir_dmc1,
						endDate   : v.tanggal_maks,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
				});
			}
		});
    });
	
    $(document).on("change", ".tanggal_dmc1_range", function() {
		var tanggal	= $(this).val();
		var tanggal_buat = $("#tanggal_buat").val();
		$.ajax({
    		url: baseURL+'mentor/transaksi/get/range',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_status: 3,
				tanggal: tanggal,
				tanggal_buat: tanggal_buat,
				return: "json"
			},
			success: function(data){
				$(".modal-title").html("Form Penambahan Mentee");
				$.each(data, function(i, v){
					$("input[name='tanggal_dmc1_rencana']").val(v.tanggal_akhir_dmc1);
					$("input[name='tanggal_dmc2_rencana']").val(v.tanggal_akhir_dmc2);
					$("input[name='tanggal_dmc3_rencana']").val(v.tanggal_akhir_dmc3);
					$('.tanggal_dmc1_range').datepicker({
						startDate : v.tanggal_akhir_sesi2,
						endDate   : v.tanggal_akhir_dmc1,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc2_range').datepicker({
						startDate : v.tanggal_akhir_dmc1,
						endDate   : v.tanggal_akhir_dmc2,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc3_range').datepicker({
						startDate : v.tanggal_akhir_dmc2,
						endDate   : v.tanggal_maks,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
				});
			}
		});
    });
	
    //edit, copy dan change  
    $(document).on("click", ".edit", function() {
        resetForm_use($('.form-input-mentee'), 'edit');
        var nomor 	= $(this).data("nomor");
		var act 	= $(this).data("act");
        $.ajax({
            // url: baseURL + 'material/transaksi/get/spec',
            url: baseURL + 'mentor/transaksi/get/mentee',
            type: 'POST',
            dataType: 'JSON',
            data: {
                nomor: nomor
            },
            success: function(data) {
                $(".modal-title").html("Edit Penambahan Mentee");
                $.each(data, function(i, v) {
                    // $("#nomor").val(v.nomor);
                    // $("#tanggal_buat").val(v.tanggal_buat_format);
					$("input[name='act']").val(act);
					$("input[name='nomor']").val(v.nomor);
					$("input[name='tanggal_buat']").val(v.tanggal_buat_format);

					$("input[name='jabatan_mentee']").val(v.jabatan_mentee);
					$("input[name='nama_jabatan_mentee']").val(v.nama_jabatan_mentee);
					$("input[name='departemen_mentee']").val(v.departemen_mentee);
					$("input[name='nama_departemen_mentee']").val(v.nama_departemen_mentee);
					$("input[name='telepon_mentee']").val(v.telepon_mentee);
					$("input[name='tanggal_sesi1_rencana']").val(v.tanggal_sesi1_rencana_format);
					$("input[name='tanggal_sesi2_rencana']").val(v.tanggal_sesi2_rencana_format);
					$("input[name='tanggal_dmc1_rencana']").val(v.tanggal_dmc1_rencana_format);
					$("input[name='tanggal_dmc2_rencana']").val(v.tanggal_dmc2_rencana_format);
					$("input[name='tanggal_dmc3_rencana']").val(v.tanggal_dmc3_rencana_format);
					//buat auto nik_mentee
					var control = $('#nik_mentee').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = v.nama_mentee+' - ['+v.nik_mentee+']';
					adapter.addOptions(adapter.convertToOptions([{"id":v.nik_mentee,"nama":nama}]));
					$('#nik_mentee').trigger('change');		
					//validasi range date
					if (v.arr_data_range) {
						$.each(v.arr_data_range, function(a, b){
							//date
							$('.tanggal_sesi1_range').datepicker({
								startDate : b.tanggal_awal_sesi1,
								endDate   : b.tanggal_akhir_sesi1,
								format	  : 'dd.mm.yyyy',
								autoclose : true
							});
							$('.tanggal_sesi2_range').datepicker({
								startDate : b.tanggal_akhir_sesi1,
								endDate   : b.tanggal_akhir_sesi2,
								format	  : 'dd.mm.yyyy',
								autoclose : true
							});
							$('.tanggal_dmc1_range').datepicker({
								startDate : b.tanggal_akhir_sesi2,
								endDate   : b.tanggal_akhir_dmc1,
								format	  : 'dd.mm.yyyy',
								autoclose : true
							});
							$('.tanggal_dmc2_range').datepicker({
								startDate : b.tanggal_akhir_dmc1,
								endDate   : b.tanggal_akhir_dmc2,
								format	  : 'dd.mm.yyyy',
								autoclose : true
							});
							$('.tanggal_dmc3_range').datepicker({
								startDate : b.tanggal_akhir_dmc2,
								endDate   : b.tanggal_maks,
								format	  : 'dd.mm.yyyy',
								autoclose : true
							});

						});
					}							
                });
            },
            complete: function() {
                $('#add_modal').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_modal')
                });
				if(act=='sesi2'){
					$(".modal-title").html("Update Tanggal Rencana Sesi 2");
					$('.form-input-mentee .form-control').prop('disabled', true);
					$('.form-input-mentee .form-control').prop('required', false);
					$('.form-input-mentee .tanggal_sesi2_range').prop('disabled', false);
					$('.form-input-mentee .tanggal_dmc1_range').prop('disabled', false);
					$('.form-input-mentee .tanggal_dmc2_range').prop('disabled', false);
					$('.form-input-mentee .tanggal_dmc3_range').prop('disabled', false);
				}
				
            }

        });
    });
	
    //detail
    $(document).on("click", ".detail", function() {
        resetForm_use($('.form-input-detail'), 'edit');
        var nomor 	= $(this).data("nomor");
        var act 	= $(this).data("act");
        // var act2 	= $(this).data("act2");
        var nik_mentor_dmc1 	= $(this).data("nik_mentor_dmc1");
        var nik_mentor_dmc2 	= $(this).data("nik_mentor_dmc2");
        var nik_mentor_dmc3 	= $(this).data("nik_mentor_dmc3");
		
        $.ajax({
            url: baseURL + 'mentor/transaksi/get/mentor',
            type: 'POST',
            dataType: 'JSON',
            data: {
                nomor: nomor
            },
            success: function(data) {
                $.each(data, function(i, v) {
					$("input[name='act']").val(act);
					$("input[name='nomor']").val(v.nomor);
					$("input[name='nomor_mentoring']").val(v.nomor_mentoring);
					$("input[name='nama_mentor']").val(v.nama_mentor);
					$("input[name='nama_jabatan_mentee']").val(v.nama_jabatan_mentee);
					$("input[name='nama_departemen_mentee']").val(v.nama_departemen_mentee);
					$("input[name='telepon_mentee']").val(v.telepon_mentee);
					$("input[name='nik_mentee']").val(v.nama_mentee+' - ['+v.nik_mentee+']');
					$("input[name='tanggal_sesi1_rencana']").val(v.tanggal_sesi1_rencana_format);
					$("input[name='tanggal_sesi1_aktual']").val(v.tanggal_sesi1_aktual_format);
					$("input[name='tanggal_sesi2_rencana']").val(v.tanggal_sesi2_rencana_format);
					$("input[name='tanggal_sesi2_aktual']").val(v.tanggal_sesi2_aktual_format);
					$("input[name='tanggal_dmc1_rencana']").val(v.tanggal_dmc1_rencana_format);
					$("input[name='tanggal_dmc1_aktual']").val(v.tanggal_dmc1_aktual_format);
					$("input[name='tanggal_dmc2_rencana']").val(v.tanggal_dmc2_rencana_format);
					$("input[name='tanggal_dmc2_aktual']").val(v.tanggal_dmc2_aktual_format);
					$("input[name='tanggal_dmc3_rencana']").val(v.tanggal_dmc3_rencana_format);
					$("input[name='tanggal_dmc3_aktual']").val(v.tanggal_dmc3_aktual_format);
					$("input[name='isu_dmc1']").val(v.isu_dmc1);
					$("input[name='tujuan_dmc1']").val(v.tujuan_dmc1);
					$("input[name='realitas_dmc1']").val(v.realitas_dmc1);
					$("input[name='opsi_dmc1']").val(v.opsi_dmc1);
					$("input[name='rencana_aksi_dmc1']").val(v.rencana_aksi_dmc1);
					$("input[name='waktu_dmc1']").val(v.waktu_dmc1);
					$("input[name='indikator_berhasil_dmc1']").val(v.indikator_berhasil_dmc1);
					$("textarea[name='catatan_dmc1']").val(v.catatan_dmc1);
					$("input[name='isu_dmc2']").val(v.isu_dmc2);
					$("input[name='tujuan_dmc2']").val(v.tujuan_dmc2);
					$("input[name='realitas_dmc2']").val(v.realitas_dmc2);
					$("input[name='opsi_dmc2']").val(v.opsi_dmc2);
					$("input[name='rencana_aksi_dmc2']").val(v.rencana_aksi_dmc2);
					$("input[name='waktu_dmc2']").val(v.waktu_dmc2);
					$("input[name='indikator_berhasil_dmc2']").val(v.indikator_berhasil_dmc2);
					$("textarea[name='catatan_dmc2']").val(v.catatan_dmc2);
					$("input[name='isu_dmc3']").val(v.isu_dmc3);
					$("input[name='tujuan_dmc3']").val(v.tujuan_dmc3);
					$("input[name='realitas_dmc3']").val(v.realitas_dmc3);
					$("input[name='opsi_dmc3']").val(v.opsi_dmc3);
					$("input[name='rencana_aksi_dmc3']").val(v.rencana_aksi_dmc3);
					$("input[name='waktu_dmc3']").val(v.waktu_dmc3);
					$("input[name='indikator_berhasil_dmc3']").val(v.indikator_berhasil_dmc3);
					$("textarea[name='catatan_dmc3']").val(v.catatan_dmc3);
					

					
					//buat auto nik_mentor_dmc1
					if(v.nik_mentor_dmc1!=null){
						var control = $('#nik_mentor_dmc1').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = v.nama_mentor_dmc1+' - ['+v.nik_mentor_dmc1+']';
						adapter.addOptions(adapter.convertToOptions([{"id":v.nik_mentor_dmc1,"nama":nama}]));
						$('#nik_mentor_dmc1').trigger('change');					
					}
					//buat auto nik_mentor_dmc2
					if(v.nik_mentor_dmc2!=null){
						var control = $('#nik_mentor_dmc2').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = v.nama_mentor_dmc2+' - ['+v.nik_mentor_dmc2+']';
						adapter.addOptions(adapter.convertToOptions([{"id":v.nik_mentor_dmc2,"nama":nama}]));
						$('#nik_mentor_dmc2').trigger('change');					
					}
					//buat auto nik_mentor_dmc3
					if(v.nik_mentor_dmc3!=null){
						var control = $('#nik_mentor_dmc3').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = v.nama_mentor_dmc3+' - ['+v.nik_mentor_dmc3+']';
						adapter.addOptions(adapter.convertToOptions([{"id":v.nik_mentor_dmc3,"nama":nama}]));
						$('#nik_mentor_dmc3').trigger('change');					
					}
					
					//title tab
					if(v.nama_mentor_dmc1!=null)
						$(".modal-title_dmc1").html("Jurnal DMC 1 ("+v.nama_mentor_dmc1+") - Additional Mentor");
					if(v.nama_mentor_dmc2!=null)
						$(".modal-title_dmc2").html("Jurnal DMC 2 ("+v.nama_mentor_dmc2+") - Additional Mentor");
					if(v.nama_mentor_dmc3!=null)
						$(".modal-title_dmc3").html("Jurnal DMC 3 ("+v.nama_mentor_dmc3+") - Additional Mentor");

					$('.tanggal_sesi1_range_aktual').datepicker({
						startDate : v.tanggal_buat_format,
						endDate   : v.tanggal_sesi1_rencana_format,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_sesi2_range_aktual').datepicker({
						startDate : v.tanggal_sesi1_aktual_format,
						endDate   : v.tanggal_sesi2_rencana_format,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc1_range_aktual').datepicker({
						startDate : v.tanggal_sesi2_aktual_format,
						endDate   : v.tanggal_dmc1_rencana_format,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc2_range_aktual').datepicker({
						startDate : v.tanggal_dmc1_aktual_format,
						endDate   : v.tanggal_dmc2_rencana_format,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					$('.tanggal_dmc3_range_aktual').datepicker({
						startDate : v.tanggal_dmc2_aktual_format,
						endDate   : v.tanggal_dmc3_rencana_format,
						format	  : 'dd.mm.yyyy',
						autoclose : true
					});
					
					
                });
            },
            complete: function() {
                $('#detail_modal').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_modal')
                });
				if(act=='sesi1'){
					$(".modal-title").html("Input Persiapan 1");
					$('.show_sesi1').removeClass('hide');
					$('.nav-tabs-custom').addClass('hide');
					$('.form-control').prop('required', false);
					$('.form-control-radio').prop('required', false);
					$('#tanggal_sesi1_aktual').prop('required', true);
					$("button[name='btn_approve']").show();
				}
				if(act=='sesi2'){
					$(".modal-title").html("Input Dokumen AIM");
					$('.show_sesi2').removeClass('hide');
					$('.nav-tabs-custom').addClass('hide');
					$('.form-control').prop('required', false);
					$('.form-control-radio').prop('required', false);
					$('#dokumen_aim').prop('required', true);
					$("button[name='btn_approve']").show();
					
					
				}
				if(act=='dmc1'){
					$(".modal-title").html("Input Jurnal DMC 1");
					$('.show_dmc1').removeClass('hide');
					$('#tanggal_dmc1_aktual').prop('required', true);
					$('#isu_dmc1').prop('required', true);
					$('#tujuan_dmc1').prop('required', true);
					$('#realitas_dmc1').prop('required', true);
					$('#opsi_dmc1').prop('required', true);
					$('#rencana_aksi_dmc1').prop('required', true);
					$('#waktu_dmc1').prop('required', true);
					$('#indikator_berhasil_dmc1').prop('required', true);
					$('#catatan_dmc1').prop('required', true);
					if(nik_mentor_dmc1!=null){	
						$('.show_mentor_dmc1').removeClass('hide');
						$('#nik_mentor_dmc1').prop('disabled', true);
					}
					
				}
				if(act=='dmc2'){
					$(".modal-title").html("Input Jurnal DMC 2");
					$('.show_dmc2').removeClass('hide');
					$('#tanggal_dmc2_aktual').prop('required', true);
					$('#isu_dmc2').prop('required', true);
					$('#tujuan_dmc2').prop('required', true);
					$('#realitas_dmc2').prop('required', true);
					$('#opsi_dmc2').prop('required', true);
					$('#rencana_aksi_dmc2').prop('required', true);
					$('#waktu_dmc2').prop('required', true);
					$('#indikator_berhasil_dmc2').prop('required', true);
					$('#catatan_dmc2').prop('required', true);
					if(nik_mentor_dmc2!=null){	
						$('.show_mentor_dmc2').removeClass('hide');
						$('#nik_mentor_dmc2').prop('disabled', true);
					}
				}
				if(act=='dmc3'){
					$(".modal-title").html("Input Jurnal DMC 3");
					$('.show_dmc3').removeClass('hide');
					$('#tanggal_dmc3_aktual').prop('required', true);
					$('#isu_dmc3').prop('required', true);
					$('#tujuan_dmc3').prop('required', true);
					$('#realitas_dmc3').prop('required', true);
					$('#opsi_dmc3').prop('required', true);
					$('#rencana_aksi_dmc3').prop('required', true);
					$('#waktu_dmc3').prop('required', true);
					$('#indikator_berhasil_dmc3').prop('required', true);
					$('#catatan_dmc3').prop('required', true);
					if(nik_mentor_dmc3!=null){	
						$('.show_mentor_dmc3').removeClass('hide');
						$('#nik_mentor_dmc3').prop('disabled', true);
					}
				}
				if(act=='all'){
					$(".modal-title").html("Detail Mentoring");
					// $('.show_sesi1').removeClass('hide');
					// $('.show_sesi2').removeClass('hide');
					$('.show_dmc1').removeClass('hide');
					$('.show_dmc2').removeClass('hide');
					$('.show_dmc3').removeClass('hide');
					$('.form-input-detail .form-control').prop('disabled', true);
					$('.form-control-radio').prop('disabled', true);
					$("button[name='btn_approve']").hide();
					// $('#btn_save').hide();
				}
				//aditional mentor
				if(act=='mentor_dmc1'){
					$(".modal-title").html("Additional Mentor DMC 1");
					$('.show_mentor_dmc1').removeClass('hide');
					$('.nav-tabs-custom').addClass('hide');
					$('.form-control').prop('required', false);
					$('.form-control-radio').prop('required', false);
					$('#nik_mentor_dmc1').prop('required', true);
					$("button[name='btn_approve']").show();
					
				}
				if(act=='mentor_dmc2'){
					$(".modal-title").html("Additional Mentor DMC 2");
					$('.show_mentor_dmc2').removeClass('hide');
					$('.nav-tabs-custom').addClass('hide');
					$('.form-control').prop('required', false);
					$('.form-control-radio').prop('required', false);
					$('#nik_mentor_dmc2').prop('required', true);
				}
				if(act=='mentor_dmc3'){
					$(".modal-title").html("Additional Mentor DMC 3");
					$('.show_mentor_dmc3').removeClass('hide');
					$('.nav-tabs-custom').addClass('hide');
					$('.form-control').prop('required', false);
					$('.form-control-radio').prop('required', false);
					$('#nik_mentor_dmc3').prop('required', true);
				}
				
            }

        });
    });	
	
	//save add/edit	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-input-mentee");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-input-mentee")[0]);
				$.ajax({
					url: baseURL+'mentor/transaksi/save/mentee',
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
	
	//approve mentor
	$(document).on("click", "button[name='btn_approve']", function(e){
		var empty_form = validate(".form-input-detail");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-input-detail")[0]);
				$.ajax({
					url: baseURL+'mentor/transaksi/save/approve_mentor',
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

    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
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

    datatables_ssp();

    //=======FILTER=======//
    $(document).on("change", "#filter_status", function() {
        datatables_ssp();
    });
	
	//auto complete nik_mentee
	$("select[name='nik_mentee']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'mentor/transaksi/get/user_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
					jenis		: 'mentee',
                    q			: params.term, // search term
                    page		: params.page
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
																  
		templateResult: function(repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">'+ repo.nama+' - ['+repo.nik + ']</div>';
			return markup;
		},
      	templateSelection: function(repo){ 
			if(repo.posst) $("input[name='caption']").val(repo.posst);
			if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
			else return repo.nama;
		}
		   
    });

    $("#nik_mentee").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });	
	
	//auto complete nik_mentor_dmc1
	$("select[name='nik_mentor_dmc1']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'mentor/transaksi/get/user_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
					jenis		: 'mentor',
                    q			: params.term, // search term
                    page		: params.page
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
																  
		templateResult: function(repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">'+ repo.nama+' - ['+repo.nik + ']</div>';
			return markup;
		},
      	templateSelection: function(repo){ 
			if(repo.posst) $("input[name='caption']").val(repo.posst);
			if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
			else return repo.nama;
		}
		   
    });

    $("#nik_mentor_dmc1").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });	
	
	//auto complete nik_mentor_dmc2
	$("select[name='nik_mentor_dmc2']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'mentor/transaksi/get/user_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
					jenis		: 'mentor',
                    q			: params.term, // search term
                    page		: params.page
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
																  
		templateResult: function(repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">'+ repo.nama+' - ['+repo.nik + ']</div>';
			return markup;
		},
      	templateSelection: function(repo){ 
			if(repo.posst) $("input[name='caption']").val(repo.posst);
			if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
			else return repo.nama;
		}
		   
    });

    $("#nik_mentor_dmc2").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });	
	
	//auto complete nik_mentor_dmc3
	$("select[name='nik_mentor_dmc3']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'mentor/transaksi/get/user_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
					jenis		: 'mentor',
                    q			: params.term, // search term
                    page		: params.page
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
																  
		templateResult: function(repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">'+ repo.nama+' - ['+repo.nik + ']</div>';
			return markup;
		},
      	templateSelection: function(repo){ 
			if(repo.posst) $("input[name='caption']").val(repo.posst);
			if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
			else return repo.nama;
		}
		   
    });

    $("#nik_mentor_dmc3").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });	
	
	//change nik_mentee get user
    $(document).on("change", "#nik_mentee", function(e){
		var nik_mentee	= $(this).val();
        $.ajax({
			url: baseURL + 'mentor/transaksi/get/user',
            type: 'POST',
            dataType: 'JSON',
            data: {
                nik: nik_mentee
            },
            success: function(data) {
                $.each(data, function(i, v) {
					$("input[name='jabatan_mentee']").val(v.id_jabatan);
					$("input[name='nama_jabatan_mentee']").val(v.nama_jabatan);
					$("input[name='departemen_mentee']").val(v.id_departemen);
					$("input[name='nama_departemen_mentee']").val(v.nama_departemen);
					
					// //buat auto nik_mentee
					// var control = $('#nik_mentee').empty().data('select2');
					// var adapter = control.dataAdapter;
					// var nama = v.nama+' - ['+v.nik+']';
					// adapter.addOptions(adapter.convertToOptions([{"id":v.nik,"nama":nama}]));
					// $('#nik_mentee').trigger('change');					
					
                });
            }
        });
    });
	
    //history
    $(document).on("click", ".history", function() {
        var nomor = $(this).data("nomor");
        $.ajax({
			// url: baseURL + 'depo/evaluasi/get/history',
			url: baseURL + 'mentor/transaksi/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                nomor: nomor
            },
            success: function(data) {
				var det_pengajuan	= "";
					det_pengajuan	+= 		'<table class="table table-bordered datatable-vendor">';
					det_pengajuan	+= 		'	<thead>';
					det_pengajuan	+= 		'		<tr>';
					det_pengajuan	+= 		'			<th>Nomor Mentoring</th>';
					det_pengajuan	+= 		'			<th>Tanggal Status</th>';
					det_pengajuan	+= 		'			<th>Status</th>';
					det_pengajuan	+= 		'		</tr>';
					det_pengajuan	+= 		'	</thead>';
					det_pengajuan	+= 		'	<tbody>';

                $.each(data, function(i, v) {
					det_pengajuan	+= 		'		<tr>';
					det_pengajuan	+= 		'			<td>'+v.nomor+'</td>';
					det_pengajuan	+= 		'			<td>'+v.tanggal_format+'<br>'+v.jam_format+'</td>';
					det_pengajuan	+= 		'			<td>'+v.action.toUpperCase()+' OLEH :<br><span class="label label-info">'+v.author.toUpperCase()+' : '+v.nama_karyawan+'</span></td>';
					det_pengajuan	+= 		'		</tr>';
                });
					det_pengajuan	+= 		'	</tbody>';
					det_pengajuan	+= 		'</table>';
					$("#histori_mentor").html(det_pengajuan);
				
            },
            complete: function() {
				setTimeout(function () {
					$("table.datatable-vendor").DataTable({
						"order": [[1, 'desc']],
						"bLengthChange": false
					}).columns.adjust();
				}, 1500);				
                $('#modal-history').modal('show');
            }
        });
    });
	

});

function resetForm_use($form, $act) {
    $('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
    $form.find('input:text, input:password, input:file,  textarea, input:hidden').val("");
    $form.find('input:text, input:password, input:file,  textarea, input:hidden').prop('disabled', false);
    $form.find('select').val(0);
    $form.find('select').prop('disabled', false);
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    $form.find('input:radio, input:checkbox').prop('disabled', false);

    validateReset('.form-input-mentee');
}

function resetForm_extend($form) {
    $('#plant_extend').prop('disabled', false);
}

function datatables_ssp() {
    var jenis_depo_filter 	= $("#jenis_depo_filter").val();
    var pabrik_filter		= $("#pabrik_filter").val();
    var filter_status		= $("#filter_status").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        pageLength: 25,
        initComplete: function() {
            var api = this.api();
            $("#sspTable_filter input").attr(
                "placeholder",
                "Press enter to start searching"
            );
            $("#sspTable_filter input").attr(
                "title",
                "Press enter to start searching"
            );
            $("#sspTable_filter input")
                .off(".DT")
                .on("keypress change", function(evt) {
                    if (evt.type == "change") {
                        api.search(this.value).draw();
                    }
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
		order: [[0, 'desc']],
        ajax: {
            url: baseURL + 'mentor/transaksi/get/mentee/bom',
            type: 'POST',
            data: function(data) {
                data.filter_status		= filter_status;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "nomor_mentoring",
                "name": "nomor_mentoring",
                "width": "10%",
                "render": function(data, type, row) {
					return row.nomor_mentoring;
                }
            },
            {
                "data": "nik_mentee",
                "name": "nik_mentee",
                "width": "10%",
                "render": function(data, type, row) {
					return row.nik_mentee;
                }
            },
            {
                "data": "nama_mentee",
                "name": "nama_mentee",
                "width": "10%",
                "render": function(data, type, row) {
					if(row.nik_mentor!=row.nik_login){
						return row.nama_mentee+"<br>(Additional)";
					}else{
						return row.nama_mentee;
					}
					
                }
            },
            {
                "data": "nama_jabatan_mentee",
                "name": "nama_jabatan_mentee",
                "width": "20%",
                "render": function(data, type, row) {
					return row.nama_jabatan_mentee;
                }
            },
            {
                "data": "nama_departemen_mentee",
                "name": "nama_departemen_mentee",
                "width": "15%",
                "render": function(data, type, row) {
					return row.nama_departemen_mentee;
                }
            },
            {
                "data": "tanggal_sesi1_rencana_format",
                "name": "tanggal_sesi1_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					if(row.url_scraft!=null){
						link_scraft = "<a href='"+baseURL+""+row.url_scraft+"' target='_blank'>Dokumen AIM Assessment</a>"; 
					}else{
						link_scraft = "";
					}
					return '<b>Rencana:</b><br>'+row.tanggal_sesi1_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_sesi1_aktual_format+"<br>"+link_scraft;
                }
            },
            {
                "data": "tanggal_sesi2_rencana_format",
                "name": "tanggal_sesi2_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					return '<b>Rencana:</b><br>'+row.tanggal_sesi2_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_sesi2_aktual_format;
                }
            },
            {
                "data": "tanggal_dmc1_rencana_format",
                "name": "tanggal_dmc1_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					if((row.nik_mentor_dmc1!=null)&&(row.nik_login!=row.nik_mentor_dmc1)){
						show_mentor_dmc1 = "<b>Additional Mentor:</b><br>"+row.nama_mentor_dmc1;
					}else{
						show_mentor_dmc1 = "";
					}
					return '<b>Rencana:</b><br>'+row.tanggal_dmc1_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_dmc1_aktual_format+"<br>"+show_mentor_dmc1;
                }
            },
            {
                "data": "tanggal_dmc2_rencana_format",
                "name": "tanggal_dmc2_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					if((row.nik_mentor_dmc2!=null)&&(row.nik_login!=row.nik_mentor_dmc2)){
						show_mentor_dmc2 = "<b>Additional Mentor:</b><br>"+row.nama_mentor_dmc2;
					}else{
						show_mentor_dmc2 = "";
					}
					return '<b>Rencana:</b><br>'+row.tanggal_dmc2_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_dmc2_aktual_format+"<br>"+show_mentor_dmc2;
                }
            },
            {
                "data": "tanggal_dmc3_rencana_format",
                "name": "tanggal_dmc3_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					if((row.nik_mentor_dmc3!=null)&&(row.nik_login!=row.nik_mentor_dmc3)){
						show_mentor_dmc3 = "<b>Additional Mentor:</b><br>"+row.nama_mentor_dmc3;
					}else{
						show_mentor_dmc3 = "";
					}
					return '<b>Rencana:</b><br>'+row.tanggal_dmc3_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_dmc3_aktual_format+"<br>"+show_mentor_dmc3;
                }
            },
            {
                "data": "sla",
                "name": "sla",
                "width": "10%",
                "render": function(data, type, row) {
					return row.sla;
                }
            },
            {
                "data": "nama_status",
                "name": "nama_status",
                "width": "15%",
                "render": function(data, type, row) {
					if(row.id_status==6){
						return "<label class='label label-success'>"+row.nama_status_group+"</label><br>"+row.detail_status;
					}else if(row.warna_status==7){
						return "<label class='label label-error'>"+row.nama_status_group+"</label><br>"+row.detail_status;
					}else{
						return "<label class='label label-warning'>"+row.nama_status_group+"</label><br>"+row.detail_status;
					}
                }
            },
            {
                "data": "nomor",
                "name": "nomor",
                "width": "5%",
                "render": function(data, type, row) {
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					//=======
					//sesi1
					//=======
					if((row.id_status==1)&&(row.login_buat==row.id_user)&&(row.url_scraft==null))
					output += "					<li><a href='javascript:void(0)' class='edit' data-nomor='" + row.nomor+ "' data-act='sesi1'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
					if((row.id_status==1)&&(row.login_buat==row.id_user)&&(row.url_scraft!=null))
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='sesi1'><i class='fa fa-calendar'></i> Set Sesi 1</a></li>";
					//=======
					//sesi2
					//=======
					if((row.id_status==2)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_sesi2_aktual_format=='-')&&(row.hari_ini < row.tanggal_sesi2_rencana))
						output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='sesi2'><i class='fa fa-clipboard'></i> Input AIM</a></li>";
					if((row.id_status==2)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_sesi2_aktual_format=='-')&&(row.hari_ini > row.tanggal_sesi2_rencana))
						output += "					<li><a href='javascript:void(0)' class='edit' data-nomor='" + row.nomor+ "' data-act='sesi2'><i class='fa fa-calendar'></i> Set Tanggal Rencana Sesi 2</a></li>";
					//=======
					//dmc1
					//=======
					if((row.id_status==3)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_dmc1_aktual_format=='-')&&(row.hari_ini < row.tanggal_dmc1_rencana))
						output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='mentor_dmc1'><i class='fa fa-users'></i> Addtional Mentor DMC 1</a></li>";
					if((row.id_status==3)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_dmc1_aktual_format=='-')&&(row.hari_ini > row.tanggal_dmc1_rencana))
						output += "					<li><a href='javascript:void(0)' class='edit' data-nomor='" + row.nomor+ "' data-act='dmc1'><i class='fa fa-calendar'></i> Set Tanggal Rencana DMC 1</a></li>";
					//input jurnal dmc1 mentor utama
					if((row.id_status==3)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_dmc1_aktual_format=='-')&&(row.nik_mentor_dmc1==null))	
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='dmc1' data-nik_mentor_dmc1='" + row.nik_mentor_dmc1+ "'><i class='fa fa-clipboard'></i> Input Jurnal DMC 1</a></li>";
					//input jurnal dmc1 mentor additional
					if((row.id_status==3)&&(row.nik_login==row.nik_mentor_dmc1)&&(row.tanggal_dmc1_aktual_format=='-')&&(row.nik_mentor_dmc1!=null))
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='dmc1' data-nik_mentor_dmc1='" + row.nik_mentor_dmc1+ "'><i class='fa fa-clipboard'></i> Input Jurnal DMC 1</a></li>";	
					//=======
					//dmc2
					//=======
					if((row.id_status==4)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_dmc2_aktual_format=='-')&&(row.hari_ini < row.tanggal_dmc2_rencana))
						output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='mentor_dmc2'><i class='fa fa-users'></i> Addtional Mentor DMC 2</a></li>";
					if((row.id_status==4)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_dmc2_aktual_format=='-')&&(row.hari_ini > row.tanggal_dmc2_rencana))
						output += "					<li><a href='javascript:void(0)' class='edit' data-nomor='" + row.nomor+ "' data-act='dmc2'><i class='fa fa-calendar'></i> Set Tanggal Rencana DMC 2</a></li>";
					//input jurnal dmc2 mentor utama
					if((row.id_status==4)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_dmc2_aktual_format=='-')&&(row.nik_mentor_dmc2==null))	
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='dmc2' data-nik_mentor_dmc2='" + row.nik_mentor_dmc2+ "'><i class='fa fa-clipboard'></i> Input Jurnal DMC 2</a></li>";
					//input jurnal dmc3 mentor additional
					if((row.id_status==4)&&(row.nik_login==row.nik_mentor_dmc2)&&(row.tanggal_dmc2_aktual_format=='-')&&(row.nik_mentor_dmc2!=null))
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='dmc2' data-nik_mentor_dmc2='" + row.nik_mentor_dmc2+ "'><i class='fa fa-clipboard'></i> Input Jurnal DMC 2</a></li>";	
					//=======
					//dmc2
					//=======
					if((row.id_status==5)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_dmc3_aktual_format=='-')&&(row.hari_ini < row.tanggal_dmc3_rencana))
						output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='mentor_dmc3'><i class='fa fa-users'></i> Addtional Mentor DMC 3</a></li>";
					if((row.id_status==5)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_dmc3_aktual_format=='-')&&(row.hari_ini > row.tanggal_dmc3_rencana))
						output += "					<li><a href='javascript:void(0)' class='edit' data-nomor='" + row.nomor+ "' data-act='dmc3'><i class='fa fa-calendar'></i> Set Tanggal Rencana DMC 3</a></li>";
					//input jurnal dmc3 mentor utama
					if((row.id_status==5)&&(row.nik_login==row.nik_mentor)&&(row.tanggal_dmc3_aktual_format=='-')&&(row.nik_mentor_dmc3==null))	
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='dmc3' data-nik_mentor_dmc3='" + row.nik_mentor_dmc3+ "'><i class='fa fa-clipboard'></i> Input Jurnal DMC 3</a></li>";
					//input jurnal dmc3 mentor additional
					if((row.id_status==5)&&(row.nik_login==row.nik_mentor_dmc3)&&(row.tanggal_dmc3_aktual_format=='-')&&(row.nik_mentor_dmc3!=null))
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='dmc3' data-nik_mentor_dmc3='" + row.nik_mentor_dmc3+ "'><i class='fa fa-clipboard'></i> Input Jurnal DMC 3</a></li>";	
					
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='all'><i class='fa fa-search'></i> Detail</a></li>";

					output += "					<li><a href='javascript:void(0)' class='history' data-nomor='" + row.nomor+ "'><i class='fa fa-h-square'></i> History</a></li>";
					output += "				</ul>";
					output += "	        </div>";
                    return output;
                }
            }

        ],
        rowCallback: function(row, data, iDisplayIndex) {
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

function load_plant(plant) {
    $.ajax({
        url: baseURL + 'material/master/get/plant',
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            if (data) {
                var output = '';
                $.each(data, function(i, v) {
                    output += '<option value="' + v.plant + '">' + v.plant + '</option>';
                });
                $("select[name='plant[]']").html(output);
            }
        },
        complete: function() {
            // var plant	= plant.split(",");
            $("select[name='plant[]']").val(plant).trigger("change");
        }
    });
}

function validateReset(target = 'form') {
    var element = $("input, select, textarea", $(target));
    $.each(element, function(i, v) {
        if (v.tagName == 'SELECT' && v.nextSibling.firstChild != null) {
            v.nextSibling.firstChild.firstChild.style.borderColor = "#d2d6de";
        }
        v.style.borderColor = "#d2d6de";
    });
}
function get_next_date(param) {
    var date = new Date();
    date.setDate(date.getDate()+param);
	var dd	= date.getDate();
	var mm	= (date.getMonth()+1);
	var yy	= date.getFullYear();
	if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} 
	
    return dd+'.'+mm+'.'+yy;
}

// function hitung_nilai() {
	// //hitung total_nilai
	// if($('input[name="nilai_1"]').val()!=''){
		// var nilai_1 = $('input[name="nilai_1"]').val();
	// }else{
		// var nilai_1 = 0;
	// }
	// if($('input[name="nilai_2"]').val()!=''){
		// var nilai_2 = $('input[name="nilai_2"]').val();
	// }else{
		// var nilai_2 = 0;
	// }
	// if($('input[name="nilai_3"]').val()!=''){
		// var nilai_3 = $('input[name="nilai_3"]').val();
	// }else{
		// var nilai_3 = 0;
	// }
	// var total_nilai = parseInt(nilai_1)+parseInt(nilai_2)+parseInt(nilai_3);
	// $("input[name='total_nilai']").val(total_nilai);
// }