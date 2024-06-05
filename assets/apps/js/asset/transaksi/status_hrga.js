$(document).ready(function(){
	//add
	$(".add").on("click", function(e){
		var id_aset	= $(this).data("add");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/approval_hrga',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Asset Approval");
				$.each(data, function(i,v){
					$("input[name='id_aset_temp']").val(v.id_aset);
					$("input[name='nomor_sap']").val(v.nomor_sap);
					$("input[name='id_jenis']").val(v.id_jenis);
					$("input[name='id_merk']").val(v.id_merk);
					$("input[name='id_merk_tipe']").val(v.id_merk_tipe);
					$("input[name='id_status']").val(v.id_status);
					$("input[name='id_kondisi']").val(v.id_kondisi);
					$("input[name='tahun_pembuatan']").val(v.tahun_pembuatan);
					$("input[name='plat']").val(v.plat);
					$("input[name='tipe_aset']").val(v.tipe_aset);
					$("input[name='keterangan']").val(v.keterangan);
					$("input[name='id_pabrik']").val(v.id_pabrik);
					$("input[name='id_lokasi']").val(v.id_lokasi);
					$("input[name='id_sub_lokasi']").val(v.id_sub_lokasi);
					$("input[name='id_area']").val(v.id_area);
					$("input[name='gambar_depan']").val(v.gambar_depan);
					$("input[name='gambar_belakang']").val(v.gambar_belakang);
					$("input[name='gambar_kanan']").val(v.gambar_kanan);
					$("input[name='gambar_kiri']").val(v.gambar_kiri);
					
					//load jenis
					get_data_jenis(v.id_jenis);
					//load merk
					var output = '';
					$.each(v.arr_merk, function (x, y) {
						var selected = (y.id_merk == v.id_merk ? 'selected' : '');
						output += '<option value="' + y.id_merk + '" '+selected+'>' + y.nama + '</option>';
					});
					$("select[name='id_merk']").html(output).select2();
					//load merk tipe
					var output = '';
					$.each(v.arr_merk_tipe, function (x, y) {
						var selected = (y.id_merk_tipe == v.id_merk_tipe ? 'selected' : '');
						output += '<option value="' + y.id_merk_tipe + '" '+selected+'>' + y.nama + '</option>';
					});
					$("select[name='id_merk_tipe']").html(output).select2();
					$("select[name='id_status']").val(v.id_status).trigger("change");
					$("select[name='id_kondisi']").val(v.id_kondisi).trigger("change");
					$("select[name='tahun_pembuatan']").val(v.tahun_pembuatan).trigger("change");
					$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
					$("input[name='pic']").val(v.pic);
					$("select[name='plat']").val(v.plat).trigger("change");
					$("input[name='no_pol']").val(v.no_pol);
					$("input[name='bel_nomor_polisi']").val(v.bel_nomor_polisi);
					$("input[name='nomor_rangka']").val(v.nomor_rangka);
					$("input[name='nomor_mesin']").val(v.nomor_mesin);
					$("select[name='tipe_aset']").val(v.tipe_aset).trigger("change");
					$("textarea[name='keterangan']").val(v.keterangan);
					$("select[name='id_pabrik']").val(v.id_pabrik).trigger("change");
					//load lokasi
					get_data_lokasi(v.id_lokasi);
					//load sub lokasi
					var output = '';
					$.each(v.arr_sub_lokasi, function (x, y) {
						var selected = (y.id_sub_lokasi == v.id_sub_lokasi ? 'selected' : '');
						output += '<option value="' + y.id_sub_lokasi + '" '+selected+'>' + y.nama + '</option>';
					});
					$("select[name='id_sub_lokasi']").html(output).select2();
					//load area
					var output = '';
					$.each(v.arr_area, function (x, y) {
						var selected = (y.id_area == v.id_area ? 'selected' : '');
						output += '<option value="' + y.id_area + '" '+selected+'>' + y.nama + '</option>';
					});
					$("select[name='id_area']").html(output).select2();
					
					$(".gambar_depan").attr('src', v.gambar_depan);
					$(".gambar_belakang").attr('src', v.gambar_belakang);
					$(".gambar_kanan").attr('src', v.gambar_kanan);
					$(".gambar_kiri").attr('src', v.gambar_kiri);
				});
				
			},
			complete: function () {
				$('#add_modal').modal('show');
			}
			
		});
    });
	//update
	$(".update").on("click", function(e){
		var id_aset	= $(this).data("update");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/approval_hrga',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Asset Approval");
				$.each(data, function(i,v){
					
					$("input[name='id_aset_temp']").val(v.id_aset);
					$("input[name='id_jenis']").val(v.id_jenis);
					$("input[name='id_merk']").val(v.id_merk);
					$("input[name='id_merk_tipe']").val(v.id_merk_tipe);
					$("input[name='id_status']").val(v.id_status);
					$("input[name='id_kondisi']").val(v.id_kondisi);
					$("input[name='tahun_pembuatan']").val(v.tahun_pembuatan);
					$("input[name='plat']").val(v.plat);
					$("input[name='no_pol']").val(v.no_pol);
					$("input[name='bel_nomor_polisi']").val(v.bel_nomor_polisi);
					$("input[name='tipe_aset']").val(v.tipe_aset);
					$("textarea[name='keterangan']").val(v.keterangan);
					$("input[name='id_pabrik']").val(v.id_pabrik);
					$("input[name='id_lokasi']").val(v.id_lokasi);
					$("input[name='id_sub_lokasi']").val(v.id_sub_lokasi);
					$("input[name='id_area']").val(v.id_area);
					$("input[name='nama_area']").val(v.nama_area);
					$("input[name='gambar_depan']").val(v.gambar_depan);
					$("input[name='gambar_belakang']").val(v.gambar_belakang);
					$("input[name='gambar_kanan']").val(v.gambar_kanan);
					$("input[name='gambar_kiri']").val(v.gambar_kiri);
					
					$("input[name='nomor_sap']").val(v.nomor_sap);
					$("input[name='nama_jenis']").val(v.nama_jenis);
					$("input[name='nama_merk']").val(v.nama_merk);
					$("input[name='nama_merk_tipe']").val(v.nama_merk_tipe);
					$("input[name='nama_status']").val(v.nama_status);
					$("input[name='nama_kondisi']").val(v.nama_kondisi);
					$("input[name='tahun_pembuatan']").val(v.tahun_pembuatan);
					$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
					$("input[name='pic']").val(v.pic);
					
					$("input[name='nomor_polisi']").val(v.nomor_polisi);
					$("input[name='nomor_rangka']").val(v.nomor_rangka);
					$("input[name='nomor_mesin']").val(v.nomor_mesin);
					$("input[name='tipe_aset']").val(v.tipe_aset);
					$("input[name='keterangan']").val(v.keterangan);
					$("input[name='nama_pabrik']").val(v.nama_pabrik);
					$("input[name='nama_lokasi']").val(v.nama_lokasi);
					$("input[name='nama_sub_lokasi']").val(v.nama_sub_lokasi);
					$("input[name='nama_area']").val(v.nama_area);
					$(".gambar_depan").attr('src', v.gambar_depan);
					$(".gambar_belakang").attr('src', v.gambar_belakang);
					$(".gambar_kanan").attr('src', v.gambar_kanan);
					$(".gambar_kiri").attr('src', v.gambar_kiri);
					
					$.each(v.arr_aset, function (x, y) {
						$("input[name='id_aset']").val(y.id_aset);
						$("input[name='nomor_sap_old']").val(y.nomor_sap);
						$("input[name='nama_jenis_old']").val(y.nama_jenis);
						$("input[name='nama_merk_old']").val(y.nama_merk);
						$("input[name='nama_merk_tipe_old']").val(y.nama_merk_tipe);
						$("input[name='nama_status_old']").val(y.nama_status);
						$("input[name='nama_kondisi_old']").val(y.nama_kondisi);
						$("input[name='tahun_pembuatan_old']").val(y.tahun_pembuatan);
						$("input[name='tanggal_perolehan_old']").val(y.tanggal_perolehan);
						$("input[name='pic_old']").val(y.pic);
						$("input[name='nomor_polisi_old']").val(y.nomor_polisi);
						$("input[name='nomor_rangka_old']").val(y.nomor_rangka);
						$("input[name='nomor_mesin_old']").val(y.nomor_mesin);
						$("input[name='tipe_aset_old']").val(y.tipe_aset);
						$("textarea[name='keterangan_old']").val(y.keterangan);
						$("input[name='nama_pabrik_old']").val(y.nama_pabrik);
						$("input[name='nama_lokasi_old']").val(y.nama_lokasi);
						$("input[name='nama_sub_lokasi_old']").val(y.nama_sub_lokasi);
						$("input[name='nama_area_old']").val(y.nama_area);
						$(".gambar_depan_old").attr('src', y.gambar_depan);
						$(".gambar_belakang_old").attr('src', y.gambar_belakang);
						$(".gambar_kanan_old").attr('src', y.gambar_kanan);
						$(".gambar_kiri_old").attr('src', y.gambar_kiri);
						
					});
				});
				
			},
			complete: function () {
				$('#update_modal').modal('show');
			}
			
		});
    });
	function get_data_jenis(id_jenis) {
		$.ajax({
			url: baseURL + 'asset/transaksi/get/jenis',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				if (data) {
					var output = '';
					$.each(data, function (i, v) {
						output += '<option value="' + v.id_jenis + '">' + v.nama + '</option>';
					});
					$("select[name='id_jenis']").html(output);
				}
			},
			complete: function () {
				if (id_jenis) {
					$("select[name='id_jenis']").val(id_jenis).trigger("change.select2");
				}
			}
		});
	}
	
	function get_data_lokasi(id_lokasi) {
		$.ajax({
			url: baseURL + 'asset/transaksi/get/lokasi',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				if (data) {
					var output = '';
					$.each(data, function (i, v) {
						output += '<option value="' + v.id_lokasi + '">' + v.nama + '</option>';
					});
					$("select[name='id_lokasi']").html(output);
				}
			},
			complete: function () {
				if (id_lokasi) {
					$("select[name='id_lokasi']").val(id_lokasi).trigger("change.select2");
				}
			}
		});
	}
	
	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "asset/transaksi/set/hrga",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset 	 : $(this).data($(this).attr("class")),	
				type 	  	 : $(this).attr("class")
			},
			success: function(data){
				if(data.sts == 'OK'){
					kiranaAlert(data.sts, data.msg);
				}else{
					kiranaAlert("notOK", data.msg, "warning", "no");
				}
			}
		});
		e.preventDefault();
		return false;
	});	
	$(document).on("click", "button[name='reject_btn_add']", function(e){
		var empty_form = validate('.form-transaksi-hrga-add');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-hrga-add")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/reject_hrga',
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
	$(document).on("click", "button[name='reject_btn_update']", function(e){
		var empty_form = validate('.form-transaksi-hrga-update');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-hrga-update")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/reject_hrga',
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
	$(document).on("click", "button[name='action_btn_add']", function(e){
		var empty_form = validate('.form-transaksi-hrga-add');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-hrga-add")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/approval_hrga',
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
	$(document).on("click", "button[name='action_btn_update']", function(e){
		var empty_form = validate('.form-transaksi-hrga-update');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-hrga-update")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/approval_hrga',
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
	
	//set on change id_jenis
    $(document).on("change", "#id_jenis", function(e){
		var id_jenis	= $(this).val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/merk',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_jenis	: id_jenis
			},
			success: function(data){
				var value = '';
				value += '<option value="0">Silahkan Pilih Merk</option>';
				$.each(data, function(i,v){
					value += '<option value="'+v.id_merk+'">'+v.nama+'</option>';
				});
				$('#id_merk').html(value);
			}
		});
    });
	//set on change id_merk
    $(document).on("change", "#id_merk", function(e){
		var id_merk	= $(this).val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/tipe',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_merk	: id_merk
			},
			success: function(data){
				var value = '';
				value += '<option value="0">Silahkan Pilih Type</option>';
				if(data){
					$.each(data, function(i,v){
						value += '<option value="'+v.id_merk_tipe+'">'+v.nama+'</option>';
					});
				}
				$('#id_merk_tipe').html(value);
			}
		});
    });
	//set on change id_lokasi
    $(document).on("change", "#id_lokasi", function(e){
		var id_lokasi	= $(this).val();
		var id_pabrik	= $("#id_pabrik").val();
		if($("option:selected",this).text() == "Depo"){		
			$.ajax({
				url: baseURL+'asset/transaksi/get/depo',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_pabrik	: id_pabrik,
					id_lokasi	: id_lokasi
				},
				success: function(data){
					if(data){
						$('#show_depo').html('');
						var value = '';
						value +=								'<div class="form-group">';		
						value +=									'<div class="row">';
						value +=										'<div class="col-xs-3">';
						value +=											'<label for="id_depo">Nama Depo</label>';
						value +=										'</div>';
						value +=										'<div class="col-xs-8">';
						value +=											'<select class="form-control select2modal" name="id_depo" id="id_depo"  required="required">';
						value += 												'<option value="0">Silahkan Pilih Type</option>';
																				$.each(data, function(i,v){
						value += 													'<option value="'+v.DEPID+'">'+v.DEPNM+'</option>';
																				});
						value +=											'</select>';
						value +=										'</div>';
						value +=									'</div>';
						value +=								'</div>';
						$('#show_depo').append(value+'</select>');
					}else{
						$('#show_depo').append('');
					}
				},
				complete: function(){
					$(".select2modal").select2();
				}
			});
		}
    });
	//set on change id_lokasi
    $(document).on("change", "#id_lokasi", function(e){
		var id_lokasi	= $(this).val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/sublokasi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_lokasi	: id_lokasi
			},
			success: function(data){
				var value = '';
				value += '<option value="0">Silahkan Pilih Sub Lokasi</option>';
				if(data){
					$.each(data, function(i,v){
						value += '<option value="'+v.id_sub_lokasi+'">'+v.nama+'</option>';
					});
				}
				$('#id_sub_lokasi').html(value);
			}
		});
    });
	//set on change id_sub_lokasi
    $(document).on("change", "#id_sub_lokasi", function(e){
		var id_sub_lokasi	= $(this).val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/area',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_sub_lokasi	: id_sub_lokasi
			},
			success: function(data){
				var value = '';
				value += '<option value="0">Silahkan Pilih Area</option>';
				if(data){
					$.each(data, function(i,v){
						value += '<option value="'+v.id_area+'">'+v.nama+'</option>';
					});
				}
				$('#id_area').html(value);
			}
		});
    });
	//set on change jenis
    $(document).on("change", "#jenis", function(e){
		var jenis	= $("#jenis").val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/merk',
			type: 'POST',
			dataType: 'JSON',
			data: {
				jenis	: jenis
			},
			success: function(data){
				var value = '';
				value += '<option value="0">Pilih Merk</option>';
				$.each(data, function(i,v){
					value += '<option value="'+v.id_merk+'">['+v.nama_jenis+'] '+v.nama+'</option>';
				});
				$('#merk').html(value);
			}
		});
    });
	//set on change lokasi
    $(document).on("change", "#lokasi", function(e){
		var lokasi	= $("#lokasi").val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/area',
			type: 'POST',
			dataType: 'JSON',
			data: {
				lokasi	: lokasi
			},
			success: function(data){
				var value = '';
				value += '<option value="0">Pilih Area</option>';
				$.each(data, function(i,v){
					value += '<option value="'+v.id_area+'">['+v.nama_lokasi+'] '+v.nama+'</option>';
				});
				$('#area').html(value);
			}
		});
    });
	
    //=======FILTER=======//
	$(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area", function(){
		var jenis	= $("#jenis").val();
		var merk 	= $("#merk").val();
		var pabrik 	= $("#pabrik").val();
		var lokasi 	= $("#lokasi").val();
		var area 	= $("#area").val();
		$.ajax({
			url: baseURL+'asset/transaksi/get/status_hrga',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	jenis 	: jenis,
	        	merk 	: merk,
	        	pabrik 	: pabrik,
	        	lokasi 	: lokasi,
	        	area	: area
	        },
	        success: function(data){
				// console.log(data);
				var output 	= "";
	        	var desc	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					
					// console.log(v);
					//generate rows
	        		t.row.add( [
			            v.label_proses,
			            v.tanggal_buat,
			            v.nama_pabrik,
			            v.nama_lokasi,
						v.nama_jenis,
						v.nama_merk,
						v.nomor_sap,
						v.nomor_polisi,
						v.cop,
						v.label_flag
			        ] ).draw( false );
	        	});
			
	        }
		});
	});
	
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Penilaian',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            }
        ],
		scrollX:true
    } );
	
    //open modal for add     
	$(document).on("click", "#add_button", function(e){
		resetForm_use($('#form_license'));
		$('#add_modal').modal('show');
	});
	function resetForm_use($form) {
		$('#myModalLabel').html("Tambah/ Edit Asset HRGA");
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
	//date pitcker
	$('.tanggal').datepicker({
		format: 'yyyy-mm-dd',
		// startDate: new Date(),
		autoclose: true
		
	});
	
	
});