$(document).ready(function(){
    $(document).on("keyup", ".cek_soal", function(e){
        var nilai 		 = $(this).val();
		var topik	 	 = $(this).data("topik");
		var id_batch	 = $(this).data("id_batch");
		var id_soal_tipe = $(this).data("id_soal_tipe");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/soal_cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				nilai 	 : nilai,
				topik	 : topik,
				id_batch : id_batch,
				id_soal_tipe : id_soal_tipe
			},
			success: function(data){
				var jumlah = data.jumlah;
				if(nilai<0){
					alert('Nilai Minimal 0');
					$(this).val(0);
				}
				// if(nilai>100){
					// alert('Nilai Maksimal 100');
					// $(this).val(0);
				// }
				if(nilai>jumlah){
					$(".modal .cek_soal[data-id_soal_tipe='"+id_soal_tipe+"']").val(0);
					alert('Jumlah soal Maksimal '+jumlah);
				}
			}
		});
    });
	
	//generate soal
	$(".generate_soal").on("click", function(e){
		var id_batch	= $(this).data("id_batch");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_batch : id_batch
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Trainer Tahap Batch Program");
				$.each(data, function(i, v){
					$("#batch").val(v.batch);
					$("input[name='id_bpo']").val(v.id_bpo);
					$("input[name='id_batch']").val(v.id_batch);
					$("input[name='peserta']").val(v.peserta);
					$("input[name='peserta_tambahan']").val(v.peserta_tambahan);
					$("input[name='jumlah_soal']").val(v.jumlah_soal);
					$("input[name='topik']").val(v.topik);
					$('#add_generate_soal_modal').modal('show');
				});
			}
		});
    });
	$(document).on("click", "button[name='action_btn_generate_soal']", function(e){
		var empty_form = validate('.form-transaksi-batch_generate_soal');
		//alert(empty_form);
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-batch_generate_soal")[0]);
				console.log(formData);
				$.ajax({
					url: baseURL+'klems/transaksi/save/batch_generate_soal',
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

	//set tanggal batch
	$('#tanggal_awal').datepicker({
        format: 'yyyy-mm-dd',
	    autoclose: true
    });
	
    $(document).on("change", "#tanggal_awal", function(e){
		// var tanggal_awal_program_batch = $("#tanggal_awal_program_batch").val();
        $('#tanggal_akhir').val("");
        var akhir = $(this).val();
        $("#div_tanggal_akhir").html("");
        $("#div_tanggal_akhir").html('<input type="text" class="form-control" name="tanggal_akhir" id="tanggal_akhir" placeholder="Masukkkan Tanggal Akhir"  required="required" autocomplete="off">');

        $('#tanggal_akhir').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: akhir
        });
    });
    $(document).on("change", "#tanggal_akhir", function(e){
        $('#tanggal').val("");
        var akhir = $(this).val();
        $("#div_tanggal_test").html("");
        $("#div_tanggal_test").html('<input type="text" class="form-control tanggal" name="tanggal" id="tanggal" placeholder="Masukkkan Tanggal Test"  autocomplete="off">');

        $('#tanggal').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: $("#tanggal_awal").val(),
            endDate: akhir
        });
    });
	//set jam test
	$('#jam_awal').datetimepicker({
		format: 'HH:mm'
    });	
	//set jam test
	$('#jam_akhir').datetimepicker({
		format: 'HH:mm'
    });	
	
	//	
	$("#id_tahap").on("change", function(e){
    	var id_program_batch	= $("#id_program_batch").val();
    	var id_tahap			= $(this).val();
    	$.ajax({
    		url: baseURL+'klems/transaksi/get_data/batch_cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_batch: id_program_batch,
				id_tahap 		: id_tahap
			},
			success: function(data){
				// console.log(data);
				$.each(data, function(i, v){
					$("input[name='id_batch']").val(v.id_batch);
					$("input[name='nama_program']").val(v.nama_program);
					$("input[name='kode_program_batch']").val(v.kode_program_batch);
					$("input[name='nama_program_batch']").val(v.nama_program_batch);
					$("input[name='tanggal_program_batch_awal']").val(v.tanggal_program_batch_awal);
					$("input[name='tanggal_program_batch_akhir']").val(v.tanggal_program_batch_akhir);
					$("input[name='tanggal_awal']").val(v.tanggal_awal);
					$("input[name='tanggal_akhir']").val(v.tanggal_akhir);
					$("input[name='tanggal']").val(v.tanggal);
					$("input[name='jam_awal']").val(v.jam_awal);
					$("input[name='jam_akhir']").val(v.jam_akhir);
					$("input[name='lokasi']").val(v.tempat);
					//for check box
					if(v.online=='y'){
						$('input[name=online]').prop('checked', true);
					} else {
						$('input[name=online]').prop('checked', false);
					}
				});
			}
		});
    });
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(".set_active-batch").on("click", function(e){
		var id_batch	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/transaksi/set_data/activate_na/batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_batch : id_batch
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
    	var id_batch = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/transaksi/set_data/delete_na/batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_batch : id_batch
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
		var id_batch	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_batch : id_batch
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Tahap Batch Program");
				$.each(data, function(i, v){
					$("#batch").val(v.batch);
					$("input[name='id_batch']").val(v.id_batch);
					$("input[name='nama_program']").val(v.nama_program);
					$("input[name='kode_program_batch']").val(v.kode_program_batch);
					$("input[name='nama_program_batch']").val(v.nama_program_batch);
					$("input[name='tanggal_program_batch_awal']").val(v.tanggal_program_batch_awal);
					$("input[name='tanggal_program_batch_akhir']").val(v.tanggal_program_batch_akhir);
					$("select[name='id_tahap']").val(v.id_tahap).trigger("change");
					$("input[name='tanggal_awal']").val(v.tanggal_awal);
					$("input[name='tanggal_akhir']").val(v.tanggal_akhir);
					$("input[name='tanggal']").val(v.tanggal);
					$("input[name='jam_awal']").val(v.jam_awal);
					$("input[name='jam_akhir']").val(v.jam_akhir);
					$("input[name='lokasi']").val(v.tempat);
					//for check box
					if(v.online=='y'){
						$('input[name=online]').prop('checked', true);
					} else {
						$('input[name=online]').prop('checked', false);
					}
					$('#add_modal').modal('show');
				});
			}
		});
    });
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate('.form-transaksi-batch');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-batch")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/transaksi/save/batch',
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
	
	//trainer
	$(".trainer").on("click", function(e){
		var id_batch	= $(this).data("trainer");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_batch : id_batch
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Trainer Tahap Batch Program");
				$.each(data, function(i, v){
					$("#batch").val(v.batch);
					$("input[name='id_batch']").val(v.id_batch);
					$("input[name='nama_program']").val(v.nama_program);
					$("input[name='kode_program_batch']").val(v.kode_program_batch);
					$("input[name='nama_program_batch']").val(v.nama_program_batch);
					$("input[name='tanggal_program_batch_awal']").val(v.tanggal_program_batch_awal);
					$("input[name='tanggal_program_batch_akhir']").val(v.tanggal_program_batch_akhir);
					if(v.trainer!=null){
						var batch_trainer	= v.trainer.split(",");
						$("select[name='batch_trainer[]']").val(batch_trainer).trigger("change");
					}
					$('#add_trainer_modal').modal('show');
				});
			}
		});
    });
	$(document).on("click", "button[name='action_btn_trainer']", function(e){
		var empty_form = validate('.form-transaksi-batch_trainer');
		//alert(empty_form);
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-batch_trainer")[0]);
				console.log(formData);
				$.ajax({
					url: baseURL+'klems/transaksi/save/batch_trainer',
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
	//persen grade
	$(".persen_grade").on("click", function(e){
		var id_batch	= $(this).data("persen_grade");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_batch : id_batch
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Trainer Tahap Batch Program");
				$.each(data, function(i, v){
					$("#batch").val(v.batch);
					$("input[name='id_batch']").val(v.id_batch);
					$("input[name='nama_program']").val(v.nama_program);
					$("input[name='kode_program_batch']").val(v.kode_program_batch);
					$("input[name='nama_program_batch']").val(v.nama_program_batch);
					$("input[name='tanggal_program_batch_awal']").val(v.tanggal_program_batch_awal);
					$("input[name='tanggal_program_batch_akhir']").val(v.tanggal_program_batch_akhir);
					if(v.bobot!=null){
						bobot = v.bobot.replace(/,+$/,'').split(",");
						for(var i=0; i < bobot.length; i++){
							var a   = bobot[i].split("|");
							b = "bobot_"+a[0];
							$("input[name='"+b+"']").val(a[1]);	
						}
					}					
					$('#add_persen_grade_modal').modal('show');
				});
			}
		});
    });
	$(document).on("click", "button[name='action_btn_persen_grade']", function(e){
		var empty_form = validate('.form-transaksi-batch_persen_grade');
		//alert(empty_form);
		if(empty_form == 0){
			var sum_max_grade	= $(".sum_min_max");
			var value = 0;
			$.each(sum_max_grade, function(i,v){
				value += sum_max_grade[i].value*1;
			});
			sum_max_grade = value;
			if(sum_max_grade == 100){
				var isproses 		= $("input[name='isproses']").val();
				if(isproses == 0){
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-transaksi-batch_persen_grade")[0]);
					console.log(formData);
					$.ajax({
						url: baseURL+'klems/transaksi/save/batch_persen_grade',
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
			}else{
				swal({
					title: "Total Data yang diinput harus 100.",
					icon: 'info'
				});
			}
		}
		e.preventDefault();
		return false;
    });
	//grade
	$(".grade").on("click", function(e){
		var id_batch	= $(this).data("grade");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_batch : id_batch
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Trainer Tahap Batch Program");
				$.each(data, function(i, v){
					$("#batch").val(v.batch);
					$("input[name='id_batch']").val(v.id_batch);
					$("input[name='nama_program']").val(v.nama_program);
					$("input[name='kode_program_batch']").val(v.kode_program_batch);
					$("input[name='nama_program_batch']").val(v.nama_program_batch);
					$("input[name='tanggal_program_batch_awal']").val(v.tanggal_program_batch_awal);
					$("input[name='tanggal_program_batch_akhir']").val(v.tanggal_program_batch_akhir);
					if(v.grade_awal!=null){
						grade_awal = v.grade_awal.replace(/,+$/,'').split(",");
						for(var i=0; i < grade_awal.length; i++){
							var a   = grade_awal[i].split("|");
							b = "grade_awal_"+a[0];
							$("input[name='"+b+"']").val(a[1]);	
						}
					}					
					if(v.grade_akhir!=null){
						grade_akhir = v.grade_akhir.replace(/,+$/,'').split(",");
						for(var i=0; i < grade_akhir.length; i++){
							var a   = grade_akhir[i].split("|");
							b = "grade_akhir_"+a[0];
							$("input[name='"+b+"']").val(a[1]);	
						}
					}					
					$('#add_grade_modal').modal('show');
				});
			}
		});
    });
	$(document).on("click", "button[name='action_btn_grade']", function(e){
		var empty_form = validate('.form-transaksi-batch_grade');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-batch_grade")[0]);
				console.log(formData);
				$.ajax({
					url: baseURL+'klems/transaksi/save/batch_grade',
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
	//soal tipe
	$(".jumlah_soal").on("click", function(e){
		var id_batch	= $(this).data("jumlah_soal");
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/batch',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_batch : id_batch
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Jumlah Soal Tahap Batch Program");
				$.each(data, function(i, v){
					$("#batch").val(v.batch);
					$("input[name='topik']").val(v.topik);
					$("input[name='id_batch']").val(v.id_batch);
					$("input[name='nama_program']").val(v.nama_program);
					$("input[name='kode_program_batch']").val(v.kode_program_batch);
					$("input[name='nama_program_batch']").val(v.nama_program_batch);
					$("input[name='tanggal_program_batch_awal']").val(v.tanggal_program_batch_awal);
					$("input[name='tanggal_program_batch_akhir']").val(v.tanggal_program_batch_akhir);
					if(v.jumlah_soal!=null){
						jumlah_soal = v.jumlah_soal.replace(/,+$/,'').split(",");
						for(var i=0; i < jumlah_soal.length; i++){
							var a   = jumlah_soal[i].split("|");
							b = "jumlah_soal_"+a[0];
							$("input[name='"+b+"']").val(a[1]);	
						}
					}
						
					$('#add_jumlah_soal_modal').modal('show');
				});
			}
		});
    });
	$(document).on("click", "button[name='action_btn_jumlah_soal']", function(e){
		var empty_form = validate('.form-transaksi-batch_jumlah_soal');
		//alert(empty_form);
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-batch_jumlah_soal")[0]);
				console.log(formData);
				$.ajax({
					url: baseURL+'klems/transaksi/save/batch_jumlah_soal',
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
      							if(repo.posst) $("input[name='caption']").val(repo.posst);
      							if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
      							else return repo.nama;
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
      							else return repo.nama;
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
                title: 'Input Tahap Batch Program',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            },
			'colvis'
        ],
		scrollX:true,
		columnDefs: [
			{ "targets": 2, "visible": false },
			{ "targets": 3, "visible": false },
			{ "targets": 4, "visible": false },
			// { "targets": 5, "visible": false }
		]
    } );
	//date pitcker
	$('.tanggal').datepicker({
		format: 'yyyy-mm-dd',
		// startDate: new Date(),
		autoclose: true
		
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

    //cek all trainer
    $(document).on("change", ".isSelectAlltrainer", function(e){
        if($(".isSelectAlltrainer").is(':checked')) {
            $('#trainer').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#trainer').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    //back history
	$(document).on("click", "#back", function(e){
		window.history.back();
	});
    //open modal for add     
	$(document).on("click", "#add_button", function(e){
		resetForm_use($('#form_license'));
		$('#add_modal').modal('show');
	});
	function resetForm_use($form) {
		$('#myModalLabel').html("Tambah/ Edit Tahap Batch Program");
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
});
// //cek_jumlah_soal
// function cek_soal(jumlah){
	// var jumlah = jumlah.value;
	// alert(jumlah);
	// // $.ajax({
		// // url: baseURL+'klems/transaksi/save/score',
		// // type: 'POST',
		// // dataType: 'JSON',
		// // data: {
			// // id_batch 		: id_batch,
			// // id_peserta 		: id_peserta,
			// // id_batch_nilai 	: id_batch_nilai,
			// // id_karyawan 	: id_karyawan,
			// // score 			: score
		// // },
		// // success: function(data){
			// // console.log(data);
			// // if(data.sts == 'OK'){
				// // // alert(data.msg);
				// // // location.reload();
			// // }else{
				// // alert(data.msg);
			// // }
		// // }
	// // });
// }
