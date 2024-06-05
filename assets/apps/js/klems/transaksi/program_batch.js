$(document).ready(function(){
    $(document).on("keyup", ".cek_min_max", function(e){
        var nilai = $(this).val();
		if(nilai<0){
			alert('Nilai Minimal 0');
			$(this).val(0);
		}
		if(nilai>100){
			alert('Nilai Maksimal 100');
			$(this).val(0);
		}
		
    });
	//grade
	$(".grade").on("click", function(e){
		var id_program_batch	= $(this).data("grade");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/program_batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_batch : id_program_batch
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Grade Program");
				$("input[name='id_program_batch']").val(data.id_program_batch);
				$("input[name='kode_program']").val(data.kode);
				$("input[name='nama_program']").val(data.nama);
				if(data.grade_awal!=null){
					grade_awal = data.grade_awal.replace(/,+$/,'').split(",");
					for(var i=0; i < grade_awal.length; i++){
						var a   = grade_awal[i].split("|");
						b = "grade_awal_"+a[0];
						$("input[name='"+b+"']").val(a[1]);	
					}
				}					
				if(data.grade_akhir!=null){
					grade_akhir = data.grade_akhir.replace(/,+$/,'').split(",");
					for(var i=0; i < grade_akhir.length; i++){
						var a   = grade_akhir[i].split("|");
						b = "grade_akhir_"+a[0];
						$("input[name='"+b+"']").val(a[1]);	
					}
				}					
				$('#add_grade_modal').modal('show');
			}
		});
    });
	$(document).on("click", "button[name='action_btn_grade']", function(e){
		var empty_form = validate('.form-transaksi-program_grade');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-program_grade")[0]);
				console.log(formData);
				$.ajax({
					url: baseURL+'klems/transaksi/save/program_grade',
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
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(".set_cancel").on("click", function(e){
    	var id_program_batch = $(this).data("cancel");
    	$.ajax({
    		url: baseURL+'klems/transaksi/save/set_status',
			type: 'POST',
			dataType: 'JSON',
			data: {
				status : 'Cancel',
				id_program_batch : id_program_batch
			},
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
    });
	$(".set_done").on("click", function(e){
    	var id_program_batch = $(this).data("done");
    	$.ajax({
    		url: baseURL+'klems/transaksi/save/set_status',
			type: 'POST',
			dataType: 'JSON',
			data: {
				status : 'Done',
				id_program_batch : id_program_batch
			},
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
    });
	
	$(".set_active-program_batch").on("click", function(e){
		var id_program_batch	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/transaksi/set_data/activate_na/program_batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_batch : id_program_batch
			},
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
    });

	$(".delete").on("click", function(e){
    	var id_program_batch = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/transaksi/set_data/delete_na/program_batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_batch : id_program_batch
			},
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
    });

	$(".edit").on("click", function(e){
		var id_program_batch	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/program_batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_batch : id_program_batch
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Program Batch");
				// $.each(data, function(v, i){
					// console.log(v);
					// console.log(i);
					$("#program_batch").val(data.program_batch);
					$("input[name='id_program_batch']").val(data.id_program_batch);
					$("select[name='bpo']").val(data.id_bpo).trigger("change");
					$("select[name='program']").val(data.id_program).trigger("change");
					$("input[name='kode']").val(data.kode);
					$("input[name='nama']").val(data.nama);
					//for check box
					if(data.sertifikat_keahlian=='1'){
						$("#show_id_sertifikat").show();
						$('input[name=sertifikat_keahlian]').prop('checked', true);
						$("input[name='tanggal_awal_sertifikat']").val(data.tanggal_awal_sertifikat);
						$("input[name='tanggal_akhir_sertifikat']").val(data.tanggal_akhir_sertifikat);
						$("select[name='oleh']").val(data.oleh).trigger("change");
						// $("input[name='oleh']").val(data.oleh);
					} else {
						$('input[name=sertifikat_keahlian]').prop('checked', false);
						$("#tanggal_awal_sertifikat").val("");
						$("#tanggal_akhir_sertifikat").val("");
						// $("#oleh").val("");
					}
					
					$("input[name='tanggal_awal']").val(data.tanggal_awal);
					$("input[name='tanggal_akhir']").val(data.tanggal_akhir);
					$("input[name='lokasi']").val(data.lokasi);
					$("input[name='kota']").val(data.kota);
					var pabrik	= data.pabrik.split(",");
					$("select[name='pabrik[]']").val(pabrik).trigger("change");

					var peserta 		= data.peserta.split(",");
					var nama_peserta	= data.nama_peserta.slice(0, -1).split(",");
					var array   		= [];
					$.each(nama_peserta, function(x, y){
						// console.log(y);
						var control = $('#peserta').empty().data('select2');
						var adapter = control.dataAdapter;
						array.push({"id":peserta[x],"text":y+' - ['+ peserta[x]+ ']'});

						adapter.addOptions(adapter.convertToOptions(array));
						$('#peserta').trigger('change');
					});
					$('#peserta').val(peserta).trigger('change');
					
					if(data.peserta_tambahan!=null){
						var peserta_tambahan		= data.peserta_tambahan.split(",");
						var nama_peserta_tambahan	= data.nama_peserta_tambahan.slice(0, -1).split(",");
						var array  = [];
						$.each(nama_peserta_tambahan, function(x, y){
							// console.log(y);
							var control = $('#peserta_tambahan').empty().data('select2');
							var adapter = control.dataAdapter;
							array.push({"id":peserta_tambahan[x],"text":y+' - ['+ peserta_tambahan[x]+ ']'});

							adapter.addOptions(adapter.convertToOptions(array));
							$('#peserta_tambahan').trigger('change');
						});
						$('#peserta_tambahan').val(peserta_tambahan).trigger('change');
					}	
					$("select[name='ttd_kiri']").val(data.ttd_kiri).trigger("change");						
					if(data.ck_ttd_kiri=='y'){
						$('input[name=ck_ttd_kiri]').prop('checked', true);
					} else {
						$('input[name=ck_ttd_kiri]').prop('checked', false);
					}
					$("select[name='ttd_kanan']").val(data.ttd_kanan).trigger("change");	
					if(data.ck_ttd_kanan=='y'){
						$('input[name=ck_ttd_kanan]').prop('checked', true);
					} else {
						$('input[name=ck_ttd_kanan]').prop('checked', false);
					}
					$("select[name='status']").val(data.status).trigger("change");
					// $("input[name='biaya_training']").val(numberWithCommas(data.biaya_training));
					// $("input[name='biaya_traveling']").val(numberWithCommas(data.biaya_traveling));
					$('#add_modal').modal('show');
				// });
			}
		});
    });
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate('.form-transaksi-program_batch');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-program_batch")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/transaksi/save/program_batch',
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
	//buat detail_peserta
	$(".detail_peserta").on("click", function(e){
		var id_program_batch	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/program_batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_batch : id_program_batch
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Set Biaya Program Batch");
				$("input[name='id_program_batch']").val(data.id_program_batch);
				$("input[name='kode']").val(data.kode);
				$("input[name='nama']").val(data.nama);
				$("input[name='peserta']").val(data.peserta);
				$("input[name='peserta_tambahan']").val(data.peserta_tambahan);
				//peserta
				var nil	= "<table class='table table-bordered'>";
				nil	 	+= "<thead>";
				nil	 	+= 		"<tr>";
				nil	 	+= 			"<th width='20'>No</th><th width='30'>NIK</th><th>Nama</th><th width='100'>Pabrik</th>";
				nil	 	+= 		"</tr>";
				nil	 	+= "</thead>";
				nil	 	+= "<tbody>";
				var peserta 			= data.peserta.split(",");
				var nama_peserta		= data.nama_peserta.slice(0, -1).split(",");
				var nama_peserta_pabrik	= data.nama_peserta_pabrik.slice(0, -1).split(",");
				var array   			= [];
				var n 	= 0;
				$.each(nama_peserta, function(x, y){
					n++;
					nil	 	+= 		"<tr>";
					nil	 	+= 			"<td>"+n+"</td><td>"+peserta[x]+"</td><td>"+nama_peserta[x]+"</td><td>"+nama_peserta_pabrik[x]+"</td>";
					nil	 	+= 		"</tr>";
				});
				nil	 	+= "</tbody>";
				nil	 	+= "</table>";
				$("#show_peserta").html(nil);

				//peserta tambahan
				if(data.nama_peserta_tambahan!=null){
				var nil	= "<table class='table table-bordered'>";
				nil	 	+= "<thead>";
				nil	 	+= 		"<tr>";
				nil	 	+= 			"<th width='20'>No</th><th width='30'>NIK</th><th>Nama</th><th width='100'>Pabrik</th>";
				nil	 	+= 		"</tr>";
				nil	 	+= "</thead>";
				nil	 	+= "<tbody>";
				var peserta 			= data.peserta_tambahan.split(",");
				var nama_peserta		= data.nama_peserta_tambahan.slice(0, -1).split(",");
				var nama_peserta_pabrik	= data.nama_peserta_tambahan_pabrik.slice(0, -1).split(",");
				var array   			= [];
				var n 	= 0;
				$.each(nama_peserta, function(x, y){
					n++;
					nil	 	+= 		"<tr>";
					nil	 	+= 			"<td>"+n+"</td><td>"+peserta[x]+"</td><td>"+nama_peserta[x]+"</td><td>"+nama_peserta_pabrik[x]+"</td>";
					nil	 	+= 		"</tr>";
				});
				nil	 	+= "</tbody>";
				nil	 	+= "</table>";
				$("#show_peserta_tambahan").html(nil);
				}
				
				$('#add_modal_detail_peserta').modal('show');
			}
		});
    });
	//buat set biaya 
	$(".set_biaya").on("click", function(e){
		var id_program_batch	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/program_batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_batch : id_program_batch
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Set Biaya Program Batch");
				$("input[name='id_program_batch']").val(data.id_program_batch);
				$("input[name='kode']").val(data.kode);
				$("input[name='nama']").val(data.nama);
				$("input[name='biaya_training']").val(numberWithCommas(data.biaya_training));
				$("input[name='biaya_traveling']").val(numberWithCommas(data.biaya_traveling));
				$('#add_modal_biaya').modal('show');
			}
		});
    });
	$(document).on("click", "button[name='action_btn_biaya']", function(e){
		var empty_form = validate('.form-transaksi-program_batch_biaya');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-program_batch_biaya")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/transaksi/save/program_batch_biaya',
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
	
	
	//auto complete peserta
	$("#peserta").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'klems/transaksi/get_data/peserta_program_batch',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
					program: $("select[name='program']").val(),
					pabrik: $("select[name='pabrik[]']").val(),
					peserta_tambahan: 'n'
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
								// return repo.text;		
      							if(repo.posst) $("input[name='caption']").val(repo.posst);
      							if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
      							else return repo.text;
      					   }
    });

    $("#peserta").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });
	//auto complete peserta_tambahan
	$("#peserta_tambahan").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'klems/transaksi/get_data/peserta_program_batch',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
					program: $("select[name='program']").val(),
					pabrik: $("select[name='pabrik[]']").val(),
					peserta: $("select[name='peserta[]']").val(),
					peserta_tambahan: 'y'
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
      							else return repo.text;
      					   }
    });

    $("#peserta_tambahan").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Setting Input Batch Program',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
			'colvis'
        ],
		scrollX:true,
		columnDefs: [
			{ "targets": 3, "visible": false },
			{ "targets": 4, "visible": false },
			{ "targets": 5, "visible": false }
		]
		
    } );
	//date pitcker
	$('.tanggal').datepicker({
		format: 'yyyy-mm-dd',
		// startDate: new Date(),
		autoclose: true
		
	});
	
	//set tanggal program batch
	$('#tanggal_awal').datepicker({
        format: 'yyyy-mm-dd',
	    autoclose: true
    });
	
    $(document).on("change", "#tanggal_awal", function(e){
        $('#tanggal_akhir').val("");
        var akhir = $(this).val();
        $("#div_tanggal_akhir").html("");
        $("#div_tanggal_akhir").html('<input type="text" class="form-control" name="tanggal_akhir" id="tanggal_akhir" placeholder="Masukkkan Tanggal Akhir Batch"  required="required" autocomplete="off">');

        $('#tanggal_akhir').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: akhir
        });
    });
	
	//set tanggal awal sertifikat
	$('#tanggal_awal_sertifikat').datepicker({
        format: 'yyyy-mm-dd',
	    autoclose: true
    });
    $(document).on("change", "#tanggal_awal_sertifikat", function(e){
        $('#tanggal_akhir_sertifikat').val("");
        var akhir = $(this).val();
        $("#div_tanggal_akhir_sertifikat").html("");
        $("#div_tanggal_akhir_sertifikat").html('<input type="text" class="form-control" name="tanggal_akhir_sertifikat" id="tanggal_akhir_sertifikat" placeholder="Masukkkan Tanggal Akhir Sertifikat"  required="required" autocomplete="off">');

        $('#tanggal_akhir_sertifikat').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: akhir
        });
    });	
	//set on click
    $(document).on("click", "#sertifikat_keahlian", function(e){
    	var valcheck = $("#sertifikat_keahlian").val();
   		if($('#sertifikat_keahlian').is(':checked')){
   			$("#show_id_sertifikat").show();
   		} else {
   			$("#show_id_sertifikat").hide();
   			$("#id_sertifikat").val("");
   		}
   		
    });
    $(document).on("click", "#ck_ttd_kiri", function(e){
    	var valcheck = $("#ck_ttd_kiri").val();
   		if($('#ck_ttd_kiri').is(':checked')){
   			$("#show_ttd_kiri").show();
   		} else {
   			$("#show_ttd_kiri").hide();
   			$("#ttd_kiri").val("");
   		}
   		
    });
    $(document).on("click", "#ck_ttd_kanan", function(e){
    	var valcheck = $("#ck_ttd_kanan").val();
   		if($('#ck_ttd_kanan').is(':checked')){
   			$("#show_ttd_kanan").show();
   		} else {
   			$("#show_ttd_kanan").hide();
   			$("#ttd_kanan").val("");
   		}
   		
    });

    //cek all pabrik
    $(document).on("change", ".isSelectAllpabrik", function(e){
        if($(".isSelectAllpabrik").is(':checked')) {
            $('#pabrik').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#pabrik').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    //cek all peserta
    $(document).on("change", ".isSelectAllPeserta", function(e){
        if($(".isSelectAllPeserta").is(':checked')) {
            $('#peserta').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#peserta').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    //cek all peserta tambahan
    $(document).on("change", ".isSelectAllPesertaTambahan", function(e){
        if($(".isSelectAllPesertaTambahan").is(':checked')) {
            $('#peserta_tambahan').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#peserta_tambahan').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    //open modal for add     
	$(document).on("click", "#add_button", function(e){
		resetForm_use($('#form_license'));
		$('#add_modal').modal('show');
	});
	function resetForm_use($form) {
		$('#myModalLabel').html("Tambah/ Edit Batch Program");
		$('#pabrik').select2('destroy').find('option').prop('selected', false).end().select2();
		$form.find('input:text, input:password, input:file,  textarea').val("");
		$form.find('select').val(0);
		$form.find('input:radio, input:checkbox')
			 .removeAttr('checked').removeAttr('selected');
		$('#add_attch').html("");
		$('#list_attch').html("");    
		$('#hidden_file_dellist').val("");
		$('#isproses').val("");
		$('#isconvert').val('0');
		
	}
	//set on change
    $(document).on("change", "#bpo", function(e){
		var program	= $("#program").val();
		var bpo	= $(this).val();
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/nomor',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program 	: program,
				id_bpo		: bpo
			},
			success: function(data){
				$.each(data, function(i, v){
					$("input[name='kode']").val(v.kode_penyelenggara+"/"+v.kode_jenis+"/"+v.abbreviation+"/"+v.nomor);
				});
			}
		});
    });
	
	//set on change
    $(document).on("change", "#program", function(e){
		var bpo	= $("#bpo").val();
		var program	= $(this).val();
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/nomor',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program 	: program,
				id_bpo		: bpo
			},
			success: function(data){
				$.each(data, function(i, v){
					$("input[name='kode']").val(v.kode_penyelenggara+"/"+v.kode_jenis+"/"+v.abbreviation+"/"+v.nomor);
				});
			}
		});
    });
	
});
