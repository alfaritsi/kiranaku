$(document).ready(function(){
	//export to excel
    $(document).on('click', '#excel_button', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'asset/transaksi/excel/fo/lab'
            +'?jenis='+$('#jenis').val()
            +'&merk='+$('#merk').val()
            +'&kondisi='+$('#kondisi').val()
            +'&status='+$('#status').val()
            +'&pabrik='+$('#pabrik').val()
            +'&lokasi='+$('#lokasi').val()
            +'&area='+$('#area').val()
        );

    })
	
	
	// Setup datatables
	$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
	    if(oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
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
    $(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area, #jam_mulai, #jam_sampai, #umur_mulai, #umur_sampai, #kondisi, #status", function(){
         datatables_ssp();
    });
	
	
    $(document).on("change", ".cek_data", function(e){
		var value 	= $(this).val();
		var tabel	= $(this).data("tabel");
		var field	= $(this).data("field");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				value : value,
				tabel : tabel,
				field : field
			},
			success: function(data){
				console.log(data);
				if(data!=''){
					$(".cek_data").val('');
					swal('Warning', 'Data Sudah Terpakai', 'warning');
					
				}
			}
		});
    });
	
	$("#pic").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'asset/transaksi/get/pic',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
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

    $("#pic").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });
	
	//edit	
	$(document).on("click", ".edit", function(){	
		var id_aset	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/fo',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Program Batch");
				$.each(data, function(i,v){
					$("#id_aset").val(v.id_aset);
					$("input[name='nomor_sap']").val(v.nomor_sap);
					$("input[name='spesifikasi']").val(v.spesifikasi);
					$("select[name='id_satuan']").val(v.id_satuan).trigger("change");
					$("input[name='nomor_rangka']").val(v.nomor_rangka);
					$("input[name='nomor_mesin']").val(v.nomor_mesin);
					$("select[name='aksesoris1']").val(v.aksesoris1).trigger("change");
					$("select[name='aksesoris2']").val(v.aksesoris2).trigger("change");
					$("select[name='tahun_pembuatan']").val(v.tahun_pembuatan).trigger("change");
					$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);					
					
					// $("select[name='id_jenis']").val(v.id_jenis).trigger("change");
					//load kategori
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
	//perubahan
	$(document).on("click", ".perubahan", function(){	
		var id_aset	= $(this).data("id_aset");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/fo',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Maintenance Asset FO");
				$.each(data, function(i,v){
					$("#id_aset").val(v.id_aset);
					$("input[name='nomor_sap']").val(v.nomor_sap);
					$("input[name='spesifikasi']").val(v.spesifikasi);
					$("select[name='id_satuan']").val(v.id_satuan).trigger("change");
					$("input[name='nomor_rangka']").val(v.nomor_rangka);
					$("input[name='nomor_mesin']").val(v.nomor_mesin);
					$("select[name='aksesoris1']").val(v.aksesoris1).trigger("change");
					$("select[name='aksesoris2']").val(v.aksesoris2).trigger("change");
					$("select[name='tahun_pembuatan']").val(v.tahun_pembuatan).trigger("change");
					$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);					
					$("input[name='id_aset']").val(v.id_aset);					
					$("input[name='id_jenis']").val(v.id_jenis);					
					
					// $("select[name='id_jenis']").val(v.id_jenis).trigger("change");
					//load kategori
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
					
				});
				
			},
			complete: function () {
				$('#modal_perubahan').modal('show');
			}
			
		});
    });
	//perbaikan
	$(document).on("click", ".perbaikan", function(){	
		var id_aset	= $(this).data("id_aset");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/fo',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Perbaikan Asset FO");
				$.each(data, function(i,v){
					$("#id_aset").val(v.id_aset);
					$("input[name='nomor_sap']").val(v.nomor_sap);
					$("input[name='spesifikasi']").val(v.spesifikasi);
					$("select[name='id_satuan']").val(v.id_satuan).trigger("change");
					$("input[name='nomor_rangka']").val(v.nomor_rangka);
					$("input[name='nomor_mesin']").val(v.nomor_mesin);
					$("select[name='aksesoris1']").val(v.aksesoris1).trigger("change");
					$("select[name='aksesoris2']").val(v.aksesoris2).trigger("change");
					$("select[name='tahun_pembuatan']").val(v.tahun_pembuatan).trigger("change");
					$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);					
					$("input[name='id_aset']").val(v.id_aset);					
					$("input[name='id_jenis']").val(v.id_jenis);					
					
					// $("select[name='id_jenis']").val(v.id_jenis).trigger("change");
					//load kategori
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
					
				});
				
			},
			complete: function () {
				$('#perbaikan_modal').modal('show');
			}
			
		});
    });
	//perawatan
	$(document).on("click", ".perawatan", function(){	
		var id_aset	= $(this).data("id_aset");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/fo',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Perbaikan Asset FO");
				$.each(data, function(i,v){
					$("#id_aset").val(v.id_aset);
					$("#berat").val(v.berat);
					$("input[name='nomor_sap']").val(v.nomor_sap);
					$("input[name='spesifikasi']").val(v.spesifikasi);
					$("select[name='id_satuan']").val(v.id_satuan).trigger("change");
					$("input[name='nomor_rangka']").val(v.nomor_rangka);
					$("input[name='nomor_mesin']").val(v.nomor_mesin);
					$("select[name='aksesoris1']").val(v.aksesoris1).trigger("change");
					$("select[name='aksesoris2']").val(v.aksesoris2).trigger("change");
					$("select[name='tahun_pembuatan']").val(v.tahun_pembuatan).trigger("change");
					$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);					
					$("input[name='id_aset']").val(v.id_aset);					
					$("input[name='id_jenis']").val(v.id_jenis);					
					$("input[name='id_kategori']").val(v.id_kategori);					
					
					// $("select[name='id_jenis']").val(v.id_jenis).trigger("change");
					//load kategori
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
					
					//load periode
					var output = '';
					$.each(v.arr_periode, function (x, y) {
						var selected = (y.id_periode == v.id_periode ? 'selected' : '');
						output += '<option value="' + y.id_periode + '" '+selected+'>' + y.nama + '</option>';
					});
					$("select[name='id_periode']").html(output).select2();
					
					
				});
				
			},
			complete: function () {
				$('#modal_perawatan').modal('show');
			}
			
		});
    });
	
	//histori
	$(document).on("click", ".histori", function(){	
		var id_aset	= $(this).data("id_aset");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/fo',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset
			},
			success: function(data){
				$(".title-form").html("History Maintenance");
				$.each(data, function(i,v){
					$("#nomor").val(v.nomor);
					//histori
					var nil	= "<table class='table table-bordered'>";
						nil	 	+= "<thead>";
						nil	 	+= 		"<tr>";
						nil	 	+= 			"<th width='25'>Tanggal</th><th width='25'>Operator</th><th width='25'>Catatan</th>";
						nil	 	+= 		"</tr>";
						nil	 	+= "</thead>";
						nil	 	+= "<tbody>";
					$.each(v.arr_maintenance, function (x, y) {
						nil	 	+= 		"<tr>";
						nil	 	+= 			"<td>"+y.tanggal_konversi+"</td><td>"+y.operator+"</td><td>"+y.catatan+"</td>";
						nil	 	+= 		"</tr>";
						
					});
						nil	 	+= "</tbody>";
					$("#show_histori").html(nil);
				});
				
			},
			complete: function () {
				$('#histori_modal').modal('show');
			}
			
		});
    });
	
	
	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "asset/transaksi/set/fo",
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
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate('.form-transaksi-fo');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-fo")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/fo',
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
	$(document).on("click", "button[name='action_btn_perawatan']", function(e){
		var empty_form = validate('.form-transaksi-fo-perawatan');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-fo-perawatan")[0]);
				
				$.ajax({
					url: baseURL+'asset/transaksi/save/main',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						console.log(data);
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
	$(document).on("click", "button[name='action_btn_perubahan']", function(e){
		var empty_form = validate('.form-transaksi-fo-perubahan');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-fo-perubahan")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/main',
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
	$(document).on("click", "button[name='action_btn_perbaikan']", function(e){
		var empty_form = validate('.form-transaksi-fo-perbaikan');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-fo-perbaikan")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/main',
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
	
	//set on change id_kategori
    $(document).on("change", "#id_kategori", function(e){
		var id_kategori	= $(this).val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/jenis/fo',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_kategori	: id_kategori
			},
			success: function(data){
				var value = '';
				value += '<option value="0">Silahkan Pilih Jenis</option>';
				$.each(data, function(i,v){
					value += '<option value="'+v.id_jenis+'">'+v.nama+'</option>';
				});
				$('#id_jenis').html(value);
			}
		});
    });
	//set on change id_jenis
    $(document).on("change", "#id_jenis", function(e){
		var id_jenis	= $(this).val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/merk/fo',
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
    		url: baseURL+'asset/transaksi/get/tipe/fo',
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
				url: baseURL+'asset/transaksi/get/depo/fo',
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
    		url: baseURL+'asset/transaksi/get/merk/fo',
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
	
    //open modal for add     
	$(document).on("click", "#add_button", function(e){
		resetForm_use($('#form_license'));
		$('#add_modal').modal('show');
	});
	function resetForm_use($form) {
		$('#myModalLabel').html("Tambah/ Edit Asset FO");
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

function get_data_kategori(id_kategori) {
	$.ajax({
		url: baseURL + 'asset/transaksi/get/kategori/fo',
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
		url: baseURL + 'asset/transaksi/get/jenis/fo',
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

function datatables_ssp(){
    var jenis		= $("#jenis").val();
    var merk 		= $("#merk").val();
    var pabrik 		= $("#pabrik").val();
    var lokasi 		= $("#lokasi").val();
    var area 		= $("#area").val();
    var jam_mulai 	= $("#jam_mulai").val();
    var jam_sampai 	= $("#jam_sampai").val();
    var umur_mulai 	= $("#umur_mulai").val();
    var umur_sampai	= $("#umur_sampai").val();
    var overdue		= $("#overdue").val();
    var kondisi		= $("#kondisi").val();
    var status		= $("#status").val();
	

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
		// //export to excel
		// dom: 'Bfrtip',
        // buttons: [
            // {
                // extend: 'excelHtml5',
                // text: 'Export to Excel',
                // title: 'Asset FO',
                // download: 'open',
                // orientation:'landscape',
                // exportOptions: {
                    // columns: [1,2,3,4,5,6,7,8,9,10,11,12]
                // }
            // }
        // ],
		ordering : true,
	    pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
	    paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
		
        pageLength: 10,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input').attr("placeholder", "Press enter to start searching");
            $('#sspTable_filter input').attr("title", "Press enter to start searching");
            $('#sspTable_filter input')
                .off('.DT')
                .on('keypress change', function (evt) {
                    console.log(evt.type);
                    // if(evt.type == "keypress" && evt.keyCode == 13) {
                    //     api.search(this.value).draw();
                    // }
                    if(evt.type == "change"){
                        api.search(this.value).draw();
                    }
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: baseURL+'asset/transaksi/get/fo/bom/lab',
            type: 'POST',
            data: function(data){
                data.jenis 		= jenis;
                data.merk 		= merk;
                data.pabrik 	= pabrik;
                data.lokasi 	= lokasi;
                data.area 		= area;
                data.jam_mulai 	= jam_mulai;
                data.jam_sampai = jam_sampai;
                data.umur_mulai = umur_mulai;
                data.umur_sampai= umur_sampai;
                data.overdue	= overdue;
                data.kondisi	= kondisi;
                data.status		= status;
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "id_aset",
                "name" : "id_aset",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.id_aset;
                },
                "visible": false
            },
            {
                "data": "nomor",
                "name" : "nomor",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.nomor;
                }
            },
            {
                "data": "nama_pabrik",
                "name" : "nama_pabrik",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.nama_pabrik;
                }
            },
            {
                "data": "nama_lokasi",
                "name" : "nama_lokasi",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_lokasi;
                }
            },
            {
                "data": "nama_sub_lokasi",
                "name" : "nama_sub_lokasi",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_sub_lokasi;
                }
            },
            {
                "data": "nama_area",
                "name" : "nama_area",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.nama_area;
                }
            },
            {
                "data": "nama_jenis",
                "name" : "nama_jenis",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_jenis;
                }
            },
            {
                "data": "nama_merk",
                "name" : "nama_merk",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_merk;
                }
            },
            {
                "data": "nomor_sap",
                "name" : "nomor_sap",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nomor_sap;
                }
            },
            {
                "data": "tanggal_perolehan",
                "name" : "tanggal_perolehan",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.tanggal_perolehan;
                }
            },
            {
                "data": "tanggal_ck_lab",
                "name" : "tanggal_ck_lab",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.tanggal_ck_lab;
                }
            },
            {
                "data": "tanggal_next_lab",
                "name" : "tanggal_next_lab",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.tanggal_next_lab;
                }
            },
            {
                "data": "id_kondisi",
                "name" : "id_kondisi",
                "width": "5%",
                "render": function (data, type, row) {
                    if(row.id_kondisi==1){
						if(row.aging>0){
							return '<label class="label label-warning">Expired</label>';
						}else{
							return '<label class="label label-success">Beroperasi</label>';
						}
                    }else{
                        return '<label class="label label-danger">Tidak Beroperasi</label>';
                    }
                }
            },
            {
                // "data": "tbl_inv_aset.id_aset",
				"data": "id_aset",
                "name" : "id_aset",
                "width": "5%",
                "render": function (data, type, row) {
					if(row.na == 'n'){
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						if(row.id_kondisi==1){
							if(row.berat=='n'){
								output += "					<li><a href='javascript:void(0)' class='perawatan' data-id_aset='"+row.id_aset+"'><i class='fa fa-sun-o'></i> Perawatan Rutin</a></li>";
								output += "					<li><a href='javascript:void(0)' class='edit' data-edit='"+row.id_aset+"'><i class='fa fa-pencil-square-o'></i> Edit Asset</a></li>";
								output += "					<li><a href='javascript:void(0)' class='delete' data-delete='"+row.id_aset+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
								output += "					<li><a href='javascript:void(0)' class='histori' data-id_aset='"+row.id_aset+"'><i class='fa fa-h-square'></i> History Asset</a></li>";
							}else{
								output += "					<li><a href='javascript:void(0)' class='perubahan' data-id_aset='"+row.id_aset+"'><i class='fa fa-clock-o'></i> Perbarui Jam Jalan</a></li>";
								output += "					<li><a href='javascript:void(0)' class='perawatan' data-id_aset='"+row.id_aset+"'><i class='fa fa-sun-o'></i> Perawatan Rutin</a></li>";
								output += "					<li><a href='javascript:void(0)' class='perbaikan' data-id_aset='"+row.id_aset+"'><i class='fa fa-wrench'></i> Perbaikan Kerusakan</a></li>";
								output += "					<li><a href='javascript:void(0)' class='edit' data-edit='"+row.id_aset+"'><i class='fa fa-pencil-square-o'></i> Edit Asset</a></li>";
							}
						}else{
							output += "					<li><a href='javascript:void(0)' class='edit' data-edit='"+row.id_aset+"'><i class='fa fa-pencil-square-o'></i> Edit Asset</a></li>";
						}
						output += "				</ul>";
						output += "	        </div>";
						
					}else{
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a href='javascript:void(0)' class='setactive' data-setactive='"+row.id_aset+"'><i class='fa fa-check-square-o'></i> Set Akif</a></li>";
						output += "				</ul>";
						output += "	        </div>";
					}
                    return output;
                }
            }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            if(info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });

    return mydDatatables;
}

function convert(str) {
    var date = new Date(str),
        mnth = ("0" + (date.getMonth()+1)).slice(-2),
        day  = ("0" + date.getDate()).slice(-2);
    return [ day, mnth, date.getFullYear()].join(".");
}
