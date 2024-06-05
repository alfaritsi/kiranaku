$(document).ready(function(){
    //switch
    $('.switch-onoff').bootstrapToggle({
        on: 'Yes',
        off: 'No'
    });
	
	//add
	$(document).on("click", ".edit", function (e) {
		var id_aset	= $(this).data("add");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/approval/it',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset,
				act		: 'proses'
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Asset Approval");
				$.each(data, function(i,v){
					$("input[name='id_aset_temp']").val(v.id_aset);
					$("input[name='kode_barang']").val(v.KODE_BARANG);
					$("input[name='nama_vendor']").val(v.NAMA_VENDOR);
					$("input[name='nomor_sap']").val(v.nomor_sap);
					$("input[name='id_kategori']").val(v.id_kategori);
					$("input[name='id_jenis']").val(v.id_jenis);
					$("input[name='id_merk']").val(v.id_merk);
					$("input[name='id_merk_tipe']").val(v.id_merk_tipe);
					$("input[name='id_status']").val(v.id_status);
					$("input[name='id_kondisi']").val(v.id_kondisi);
					$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
					$("input[name='ip_address']").val(v.IP_ADDRESS);
					$("select[name='os']").val(v.OS).trigger("change");
					$("input[name='os']").val(v.OS);
					$("input[name='lisensi_os']").val(v.lisensi_os);
					$("input[name='sn_os']").val(v.SN_OS);
					$("select[name='office_apps']").val(v.OFFICE_APPS).trigger("change");
					$("input[name='office_apps']").val(v.OFFICE_APPS);
					$("input[name='lisensi_office']").val(v.lisensi_office);
					$("input[name='sn_office']").val(v.sn_office);
					
					// $("input[name='office_apps']").val(v.OFFICE_APPS);
					$("input[name='mac_address']").val(v.MAC_ADDRESS);
					$("input[name='tipe_processor']").val(v.TIPE_PROCESSOR);
					$("input[name='processor_series']").val(v.PROCESSOR_SERIES);
					$("input[name='processor_spec']").val(v.PROCESSOR_SPEC);
					$("input[name='ram']").val(v.RAM);
					$("input[name='hdd']").val(v.HDD);
					$("input[name='merk_monitor']").val(v.MERK_MONITOR);
					$("input[name='ukuran_monitor']").val(v.UKURAN_MONITOR);
					$("input[name='keterangan']").val(v.keterangan);
					$("input[name='id_pabrik']").val(v.id_pabrik);
					$("input[name='id_lokasi']").val(v.id_lokasi);
					$("input[name='id_sub_lokasi']").val(v.id_sub_lokasi);
					$("input[name='id_area']").val(v.id_area);
					
					//load jenis
					get_data_kategori(v.id_kategori);
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
					
					$("input[name='pic']").val(v.pic);
					$("input[name='nama_karyawan']").val(v.nama_karyawan);
					$("input[name='nama_vendor']").val(v.NAMA_VENDOR);
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
					
					//cr 2241
                    if (v.lisensi_os == 'y') {
                        $("input[name='lisensi_os']").attr('checked');
                        $("input[name='lisensi_os']").bootstrapToggle('on');
						$('.show_sn_os').removeClass('hide');
						$('#sn_os').prop('required', false);
						//load sn_os
						get_sn_os(v.SN_OS);
                    } else {
                        $("input[name='lisensi_os']").removeAttr('checked');
                        $("input[name='lisensi_os']").bootstrapToggle('off');
						$('.show_sn_os').addClass('hide');
						$('#sn_os').prop('required', false);
						//load sn_os
						get_sn_os(v.SN_OS);
                    }
                    if (v.lisensi_office == 'y') {
                        $("input[name='lisensi_office']").attr('checked');
                        $("input[name='lisensi_office']").bootstrapToggle('on');
						$('.show_sn_office').removeClass('hide');
						$('#sn_office').prop('required', false);
						//load sn_office
						get_sn_office(v.sn_office);
                    } else {
                        $("input[name='lisensi_office']").removeAttr('checked');
                        $("input[name='lisensi_office']").bootstrapToggle('off');
						$('.show_sn_office').addClass('hide');
						$('#sn_office').prop('required', false);
						//load sn_office
						get_sn_office(v.sn_office);
                    }
					
				});
				
			},
			complete: function () {
				$('#add_modal').modal('show');
			}
			
		});
    });
	//update
	$(document).on("click", ".update", function (e) {
		var id_aset	= $(this).data("update");
		var id_aset_temp	= $(this).data("id_aset_temp");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/approval/it',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset,
				id_aset_temp : id_aset_temp,
				proses	: 'update',
				act		: 'proses'	
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Asset Approval");
				$.each(data, function(i,v){
					
					$("input[name='id_aset_temp']").val(v.id_aset);
					$("input[name='kode_barang']").val(v.KODE_BARANG);
					$("input[name='nomor_sap']").val(v.nomor_sap);
					$("input[name='id_kategori']").val(v.id_kategori);
					$("input[name='nama_kategori']").val(v.nama_kategori);
					$("input[name='id_jenis']").val(v.id_jenis);
					$("input[name='nama_jenis']").val(v.nama_jenis);
					$("input[name='id_merk']").val(v.id_merk);
					$("input[name='nama_merk']").val(v.nama_merk);
					$("input[name='id_merk_tipe']").val(v.id_merk_tipe);
					$("input[name='nama_merk_tipe']").val(v.nama_merk_tipe);
					$("input[name='id_status']").val(v.id_status);
					$("input[name='nama_status']").val(v.nama_status);
					$("input[name='id_kondisi']").val(v.id_kondisi);
					$("input[name='nama_kondisi']").val(v.nama_kondisi);
					$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
					$("input[name='pic']").val(v.pic);
					$("input[name='nama_vendor']").val(v.NAMA_VENDOR);
					$("input[name='ip_address']").val(v.IP_ADDRESS);
					$("select[name='os']").val(v.OS).trigger("change");
					$("input[name='os']").val(v.OS);
					$("input[name='sn_os']").val(v.SN_OS);
					$("select[name='office_apps']").val(v.OFFICE_APPS).trigger("change");
					$("input[name='office_apps']").val(v.OFFICE_APPS);
					$("input[name='mac_address']").val(v.MAC_ADDRESS);
					$("input[name='tipe_processor']").val(v.TIPE_PROCESSOR);
					$("input[name='processor_series']").val(v.PROCESSOR_SERIES);
					$("input[name='processor_spec']").val(v.PROCESSOR_SPEC);
					$("input[name='ram']").val(v.RAM);
					$("input[name='hdd']").val(v.HDD);
					$("input[name='merk_monitor']").val(v.MERK_MONITOR);
					$("input[name='ukuran_monitor']").val(v.UKURAN_MONITOR);
					$("textarea[name='keterangan']").val(v.keterangan);
					$("input[name='id_pabrik']").val(v.id_pabrik);
					$("input[name='nama_pabrik']").val(v.nama_pabrik);
					$("input[name='id_lokasi']").val(v.id_lokasi);
					$("input[name='nama_lokasi']").val(v.nama_lokasi);
					$("input[name='id_sub_lokasi']").val(v.id_sub_lokasi);
					$("input[name='nama_sub_lokasi']").val(v.nama_sub_lokasi);
					$("input[name='id_area']").val(v.id_area);
					$("input[name='nama_area']").val(v.nama_area);
					$.each(v.arr_aset, function (x, y) {
						$("input[name='id_aset']").val(y.id_aset);
						$("input[name='kode_barang_old']").val(y.KODE_BARANG);
						$("input[name='nomor_sap_old']").val(y.nomor_sap);
						$("input[name='nama_kategori_old']").val(y.nama_kategori);
						$("input[name='nama_jenis_old']").val(y.nama_jenis);
						$("input[name='nama_merk_old']").val(y.nama_merk);
						$("input[name='nama_merk_tipe_old']").val(y.nama_merk_tipe);
						$("input[name='nama_status_old']").val(y.nama_status);
						$("input[name='nama_kondisi_old']").val(y.nama_kondisi);
						$("input[name='tanggal_perolehan_old']").val(y.tanggal_perolehan);
						$("input[name='pic_old']").val(y.pic);
						$("input[name='nama_vendor_old']").val(y.NAMA_VENDOR);
						//
						$("input[name='ip_address_old']").val(y.IP_ADDRESS);
						$("input[name='os_old']").val(y.OS);
						$("input[name='sn_os_old']").val(y.SN_OS);
						$("input[name='office_apps_old']").val(y.OFFICE_APPS);
						$("input[name='mac_address_old']").val(y.MAC_ADDRESS);
						$("input[name='tipe_processor_old']").val(y.TIPE_PROCESSOR);
						$("input[name='processor_series_old']").val(y.PROCESSOR_SERIES);
						$("input[name='processor_spec_old']").val(y.PROCESSOR_SPEC);
						$("input[name='ram_old']").val(y.RAM);
						$("input[name='hdd_old']").val(y.HDD);
						$("input[name='merk_monitor_old']").val(y.MERK_MONITOR);
						$("input[name='ukuran_monitor_old']").val(y.UKURAN_MONITOR);
						$("textarea[name='keterangan_old']").val(y.keterangan);
						$("input[name='nama_pabrik_old']").val(y.nama_pabrik);
						$("input[name='nama_lokasi_old']").val(y.nama_lokasi);
						$("input[name='nama_sub_lokasi_old']").val(y.nama_sub_lokasi);
						$("input[name='nama_area_old']").val(y.nama_area);
					});
				});
				
			},
			complete: function () {
				$('#update_modal').modal('show');
			}
			
		});
    });
	function get_data_kategori(id_kategori) {
		$.ajax({
			url: baseURL + 'asset/transaksi/get/kategori/it',
			type: 'POST',
			dataType: 'JSON',
			success: function (data) {
				if (data) {
					var output = '';
					$.each(data, function (i, v) {
						output += '<option value="' + v.id_kategori + '">' + v.nama + '</option>';
					});
					$("select[name='id_kategori']").html(output);
				}
			},
			complete: function () {
				if (id_kategori) {
					$("select[name='id_kategori']").val(id_kategori).trigger("change.select2");
				}
			}
		});
	}
	
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
		var empty_form = validate('.form-transaksi-it-add');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-it-add")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/reject/it',
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
		var empty_form = validate('.form-transaksi-it-update');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-it-update")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/reject/it',
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
		var empty_form = validate('.form-transaksi-it-add');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-it-add")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/approval/it',
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
		var empty_form = validate('.form-transaksi-it-update');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-it-update")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/approval/it',
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
    		url: baseURL+'asset/transaksi/get/merk/hrga',
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
    		url: baseURL+'asset/transaksi/get/tipe/hrga',
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
				url: baseURL+'asset/transaksi/get/depo/hrga',
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
			url: baseURL+'asset/transaksi/get/approval/it',
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
				console.log(data);
				var output 	= "";
	        	var desc	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					
					// //option action
					if(v.proses == 'input'){ 
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a href='javascript:void(0)' class='add' data-add='"+v.id_aset+"'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
						output += "				</ul>";
						output += "	        </div>";
					}
					if(v.proses == 'update'){ 
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a href='javascript:void(0)' class='update' data-update='"+v.id_aset+"'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
						output += "				</ul>";
						output += "	        </div>";
					}
					
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
						v.nama_karyawan,
						v.NAMA_VENDOR,
						v.label_flag,
						output
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
	
	function get_sn_os(sn_os) {
		$.ajax({
			url: baseURL + 'asset/transaksi/get/sn_os',
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				console.log('aaa');
				if (data) {
					var output = '';
					$.each(data, function(i, v) {
						output += '<option value="' + v.sn_os + '">' + v.sn_os + '</option>';
					});
					$("select[name='sn_os']").html(output);
				}
			},
			complete: function() {
				if (sn_os) {
					$("select[name='sn_os']").val(sn_os).trigger("change.select2");
				}
			}
		});
	}
	function get_sn_office(sn_office) {
		$.ajax({
			url: baseURL + 'asset/transaksi/get/sn_office',
			type: 'POST',
			dataType: 'JSON',
			success: function(data) {
				if (data) {
					var output = '';
					$.each(data, function(i, v) {
						output += '<option value="' + v.sn_office + '">' + v.sn_office + '</option>';
					});
					$("select[name='sn_office']").html(output);
				}
			},
			complete: function() {
				if (sn_office) {
					$("select[name='sn_office']").val(sn_office).trigger("change.select2");
				}
			}
		});
	}
	
	
});