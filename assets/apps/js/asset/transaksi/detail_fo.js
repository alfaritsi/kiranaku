$(document).ready(function(){
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
    $(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area, #jam_mulai, #jam_sampai, #umur_mulai, #umur_sampai, #status", function(){
         datatables_ssp();
    });
	
	//maintenance
	// $(".maintenance").on("click", function(e){
	$(document).on("click", ".maintenance", function(){			
		var id_main	= $(this).data("id_main");
		var id_aset	= $(this).data("id_aset");
		var jenis_tindakan	= $(this).data("jenis_tindakan");
		if(jenis_tindakan=='perawatan'){
			$.ajax({
				url: baseURL+'asset/transaksi/get/detail/fo',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_main : id_main,
					id_aset : id_aset
				},
				success: function(data){
					console.log(data);
					$(".title-form").html("Maintenance Asset FO");
					$.each(data, function(i,v){
						$("#id_aset").val(v.id_aset);
						$("#id_main").val(v.id_main);
						$("input[name='id_aset']").val(v.id_aset);
						$("input[name='id_main']").val(v.id_main);
						$("input[name='nomor_sap']").val(v.nomor_sap);
						$("input[name='nama_kategori']").val(v.nama_kategori);
						$("input[name='nama_jenis']").val(v.nama_jenis);
						$("input[name='nama_merk']").val(v.nama_merk);
						$("input[name='nama_merk_tipe']").val(v.nama_merk_tipe);
						$("input[name='nama_status']").val(v.nama_status);
						$("input[name='nama_kondisi']").val(v.nama_kondisi);
						$("input[name='tahun_pembuatan']").val(v.tahun_pembuatan);
						$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
						$("input[name='spesifikasi']").val(v.spesifikasi);
						$("input[name='nama_satuan']").val(v.nama_satuan);
						$("input[name='nomor_rangka']").val(v.nomor_rangka);
						$("input[name='nomor_mesin']").val(v.nomor_mesin);
						$("input[name='nama_aksesoris1']").val(v.nama_aksesoris1);
						$("input[name='nama_aksesoris2']").val(v.nama_aksesoris2);
						$("textarea[name='keterangan']").val(v.keterangan);
						$("input[name='nama_pabrik']").val(v.nama_pabrik);
						$("input[name='nama_lokasi']").val(v.nama_lokasi);
						$("input[name='nama_sub_lokasi']").val(v.nama_sub_lokasi);
						$("input[name='nama_area']").val(v.nama_area);
						$("input[name='jam_jalan']").val(v.jam_jalan);
						$("input[name='operator']").val(v.operator);
						$("textarea[name='catatan']").val(v.catatan);
						//detail perawatan
						var t 		= $('.my-datatable-extends-order-detail').DataTable();
						var ket		= '';
						t.clear().draw();
						$.each(v.arr_main_detail, function (x, y) {
							t.row.add( [
								y.nama_jenis_detail,
								y.nama_periode_detail,
								'<center><input type="checkbox" class="periksa" data-id_main_detail="'+y.id_main_detail+'" '+y.label_cek+'></center>',
								'<input type="text" class="keterangan" value="'+y.label_keterangan+'" data-id_main_detail="'+y.id_main_detail+'">'
							] ).draw( false );						
							
						});
					});
					
				},
				complete: function () {
					$('#perawatan_modal').modal('show');
				}
			});
		}else{
			$.ajax({
				url: baseURL+'asset/transaksi/get/detail/fo',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_main : id_main,
					id_aset : id_aset
				},
				success: function(data){
					console.log(data);
					$(".title-form").html("Maintenance Asset FO");
					$.each(data, function(i,v){
						$("#id_aset").val(v.id_aset);
						$("#id_main").val(v.id_main);
						$("input[name='id_aset']").val(v.id_aset);
						$("input[name='id_main']").val(v.id_main);
						$("input[name='nomor_sap']").val(v.nomor_sap);
						$("input[name='nama_kategori']").val(v.nama_kategori);
						$("input[name='nama_jenis']").val(v.nama_jenis);
						$("input[name='nama_merk']").val(v.nama_merk);
						$("input[name='nama_merk_tipe']").val(v.nama_merk_tipe);
						$("input[name='nama_status']").val(v.nama_status);
						$("input[name='nama_kondisi']").val(v.nama_kondisi);
						$("input[name='tahun_pembuatan']").val(v.tahun_pembuatan);
						$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
						$("input[name='spesifikasi']").val(v.spesifikasi);
						$("input[name='nama_satuan']").val(v.nama_satuan);
						$("input[name='nomor_rangka']").val(v.nomor_rangka);
						$("input[name='nomor_mesin']").val(v.nomor_mesin);
						$("input[name='nama_aksesoris1']").val(v.nama_aksesoris1);
						$("input[name='nama_aksesoris2']").val(v.nama_aksesoris2);
						$("textarea[name='keterangan']").val(v.keterangan);
						$("input[name='nama_pabrik']").val(v.nama_pabrik);
						$("input[name='nama_lokasi']").val(v.nama_lokasi);
						$("input[name='nama_sub_lokasi']").val(v.nama_sub_lokasi);
						$("input[name='nama_area']").val(v.nama_area);
						$("input[name='jam_jalan']").val(v.jam_jalan);
						$("input[name='operator']").val(v.operator);
						$("textarea[name='catatan']").val(v.catatan);
					});
					
				},
				complete: function () {
					$('#perbaikan_modal').modal('show');
				}
			});
		}
	
    });
	//set on click
    $(document).on("click", ".periksa", function(e){
		var id_main_detail	= $(this).data("id_main_detail");
   		if($('.periksa').is(':checked')){
			$.ajax({
				url: baseURL+'asset/transaksi/save/main_detail',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_main_detail 	: id_main_detail,
					cek 			: 'y'
				}
			});
   		} else {
			$.ajax({
				url: baseURL+'asset/transaksi/save/main_detail',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_main_detail 	: id_main_detail,
					cek		 		: 'n'
				}
			});
   		}
   		
    });
	//save main detail
    $(document).on("change", ".keterangan", function(e){
		var id_main_detail	= $(this).data("id_main_detail");
		var keterangan 		= $(this).val();
		$.ajax({
    		url: baseURL+'asset/transaksi/save/main_detail',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_main_detail 	: id_main_detail,
				keterangan 		: keterangan
			}
		});
    });
	//detail perbaikan
	// $(".detail").on("click", function(e){
	$(document).on("click", ".detail", function(){	
		var id_main			= $(this).data("id_main");
		var id_aset			= $(this).data("id_aset");
		var jenis_tindakan	= $(this).data("jenis_tindakan");
		if(jenis_tindakan=='perbaikan'){
			$.ajax({
				url: baseURL+'asset/transaksi/get/detail/fo',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_main : id_main,
					id_aset : id_aset
				},
				success: function(data){
					console.log(data);
					$(".title-form").html("Detail Asset FO");
					$.each(data, function(i,v){
						$("#id_aset").val(v.id_aset);
						$("#id_main").val(v.id_main);
						$("input[name='nomor_sap']").val(v.nomor_sap);
						$("input[name='nama_kategori']").val(v.nama_kategori);
						$("input[name='nama_jenis']").val(v.nama_jenis);
						$("input[name='nama_merk']").val(v.nama_merk);
						$("input[name='nama_merk_tipe']").val(v.nama_merk_tipe);
						$("input[name='nama_status']").val(v.nama_status);
						$("input[name='nama_kondisi']").val(v.nama_kondisi);
						$("input[name='tahun_pembuatan']").val(v.tahun_pembuatan);
						$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
						$("input[name='spesifikasi']").val(v.spesifikasi);
						$("input[name='nama_satuan']").val(v.nama_satuan);
						$("input[name='nomor_rangka']").val(v.nomor_rangka);
						$("input[name='nomor_mesin']").val(v.nomor_mesin);
						$("input[name='nama_aksesoris1']").val(v.nama_aksesoris1);
						$("input[name='nama_aksesoris2']").val(v.nama_aksesoris2);
						$("textarea[name='keterangan']").val(v.keterangan);
						$("input[name='nama_pabrik']").val(v.nama_pabrik);
						$("input[name='nama_lokasi']").val(v.nama_lokasi);
						$("input[name='nama_sub_lokasi']").val(v.nama_sub_lokasi);
						$("input[name='nama_area']").val(v.nama_area);
						$("input[name='jam_jalan']").val(v.jam_jalan);
						$("input[name='operator']").val(v.operator);
						$("textarea[name='catatan']").val(v.catatan);
						$("input[name='tanggal_rusak']").val(v.tanggal_rusak);
						$("input[name='tanggal_mulai']").val(v.tanggal_mulai);
						$("input[name='tanggal_selesai']").val(v.tanggal_selesai);
						$("input[name='nama_jenis_tindakan']").val(v.nama_jenis_tindakan);
						$("input[name='id_karyawan']").val(v.id_karyawan);
						$("input[name='nama_karyawan']").val(v.nama_karyawan);
						$("input[name='tanggal_buat']").val(v.tanggal_buat);

					});
					
				},
				complete: function () {
					$('#modal_detail_perbaikan').modal('show');	
				}
			});			
		}
		if(jenis_tindakan=='perubahan'){
			$.ajax({
				url: baseURL+'asset/transaksi/get/detail/fo',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_main : id_main,
					id_aset : id_aset
				},
				success: function(data){
					console.log(data);
					$(".title-form").html("Detail Asset FO");
					$.each(data, function(i,v){
						$("#id_aset").val(v.id_aset);
						$("#id_main").val(v.id_main);
						$("input[name='nomor_sap']").val(v.nomor_sap);
						$("input[name='nama_kategori']").val(v.nama_kategori);
						$("input[name='nama_jenis']").val(v.nama_jenis);
						$("input[name='nama_merk']").val(v.nama_merk);
						$("input[name='nama_merk_tipe']").val(v.nama_merk_tipe);
						$("input[name='nama_status']").val(v.nama_status);
						$("input[name='nama_kondisi']").val(v.nama_kondisi);
						$("input[name='tahun_pembuatan']").val(v.tahun_pembuatan);
						$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
						$("input[name='spesifikasi']").val(v.spesifikasi);
						$("input[name='nama_satuan']").val(v.nama_satuan);
						$("input[name='nomor_rangka']").val(v.nomor_rangka);
						$("input[name='nomor_mesin']").val(v.nomor_mesin);
						$("input[name='nama_aksesoris1']").val(v.nama_aksesoris1);
						$("input[name='nama_aksesoris2']").val(v.nama_aksesoris2);
						$("textarea[name='keterangan']").val(v.keterangan);
						$("input[name='nama_pabrik']").val(v.nama_pabrik);
						$("input[name='nama_lokasi']").val(v.nama_lokasi);
						$("input[name='nama_sub_lokasi']").val(v.nama_sub_lokasi);
						$("input[name='nama_area']").val(v.nama_area);
						$("input[name='jam_jalan']").val(v.jam_jalan);
						$("input[name='operator']").val(v.operator);
						$("textarea[name='catatan']").val(v.catatan);
						$("input[name='tanggal_rusak']").val(v.tanggal_rusak);
						$("input[name='tanggal_mulai']").val(v.tanggal_mulai);
						$("input[name='tanggal_selesai']").val(v.tanggal_selesai);
						$("input[name='nama_jenis_tindakan']").val(v.nama_jenis_tindakan);
						$("input[name='id_karyawan']").val(v.id_karyawan);
						$("input[name='nama_karyawan']").val(v.nama_karyawan);
						$("input[name='tanggal_buat']").val(v.tanggal_buat);
					});
					
				},
				complete: function () {
					$('#modal_detail_perubahan').modal('show');	
				}
			});			
		}
		if(jenis_tindakan=='perawatan'){
			$.ajax({
				url: baseURL+'asset/transaksi/get/detail/fo',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_main : id_main,
					id_aset : id_aset
				},
				success: function(data){
					console.log(data);
					$(".title-form").html("Detail Asset FO");
					$.each(data, function(i,v){
						$("#id_aset").val(v.id_aset);
						$("#id_main").val(v.id_main);
						$("input[name='nomor_sap']").val(v.nomor_sap);
						$("input[name='nama_kategori']").val(v.nama_kategori);
						$("input[name='nama_jenis']").val(v.nama_jenis);
						$("input[name='nama_merk']").val(v.nama_merk);
						$("input[name='nama_merk_tipe']").val(v.nama_merk_tipe);
						$("input[name='nama_status']").val(v.nama_status);
						$("input[name='nama_kondisi']").val(v.nama_kondisi);
						$("input[name='tahun_pembuatan']").val(v.tahun_pembuatan);
						$("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
						$("input[name='spesifikasi']").val(v.spesifikasi);
						$("input[name='nama_satuan']").val(v.nama_satuan);
						$("input[name='nomor_rangka']").val(v.nomor_rangka);
						$("input[name='nomor_mesin']").val(v.nomor_mesin);
						$("input[name='nama_aksesoris1']").val(v.nama_aksesoris1);
						$("input[name='nama_aksesoris2']").val(v.nama_aksesoris2);
						$("textarea[name='keterangan']").val(v.keterangan);
						$("input[name='nama_pabrik']").val(v.nama_pabrik);
						$("input[name='nama_lokasi']").val(v.nama_lokasi);
						$("input[name='nama_sub_lokasi']").val(v.nama_sub_lokasi);
						$("input[name='nama_area']").val(v.nama_area);
						$("input[name='jam_jalan']").val(v.jam_jalan);
						$("input[name='operator']").val(v.operator);
						$("textarea[name='catatan']").val(v.catatan);
						$("input[name='tanggal_rusak']").val(v.tanggal_rusak);
						$("input[name='tanggal_mulai']").val(v.tanggal_mulai);
						$("input[name='tanggal_selesai']").val(v.tanggal_selesai);
						$("input[name='nama_jenis_tindakan']").val(v.nama_jenis_tindakan);
						$("input[name='id_karyawan']").val(v.id_karyawan);
						$("input[name='nama_karyawan']").val(v.nama_karyawan);
						$("input[name='tanggal_buat']").val(v.tanggal_buat);
						//detail perawatan view only
						var t 		= $('.my-datatable-extends-order-detail').DataTable();
						var ket		= '';
						t.clear().draw();
						$.each(v.arr_main_detail, function (x, y) {
							t.row.add( [
								y.nama_jenis_detail,
								y.nama_periode_detail,
								'<center><input type="checkbox" class="periksa" data-id_main_detail="'+y.id_main_detail+'" '+y.label_cek+' disabled></center>',
								'<input type="text" class="keterangan" value="'+y.label_keterangan+'" data-id_main_detail="'+y.id_main_detail+'" disabled>'
							] ).draw( false );						
							
						});
						
					});
					
				},
				complete: function () {
					$('#modal_detail_perawatan').modal('show');	
				}
			});			
		}

    });
	$(document).on("click", "button[name='action_btn_proses_perbaikan']", function(e){
		var empty_form = validate('.form-transaksi-fo-proses_perbaikan');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-fo-proses_perbaikan")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/proses_perbaikan',
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
	$(document).on("click", "button[name='action_btn_proses_perawatan']", function(e){
		var empty_form = validate('.form-transaksi-fo-proses_perawatan');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-fo-proses_perawatan")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/proses_perawatan',
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
    //=======FILTER=======//
	// $(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area", function(){
	// 	var jenis	= $("#jenis").val();
	// 	var merk 	= $("#merk").val();
	// 	var pabrik 	= $("#pabrik").val();
	// 	var lokasi 	= $("#lokasi").val();
	// 	var area 	= $("#area").val();
	// 	$.ajax({
	// 		url: baseURL+'asset/transaksi/get/detail/fo',
	//         type: 'POST',
	//         dataType: 'JSON',
	//         data: {
	//         	jenis 	: jenis,
	//         	merk 	: merk,
	//         	pabrik 	: pabrik,
	//         	lokasi 	: lokasi,
	//         	area	: area
	//         },
	//         success: function(data){
	// 			// console.log(data);
	// 			var output 	= "";
	//         	var desc	= "";
	//         	var t 	= $('.my-datatable-extends-order').DataTable();
	//         	t.clear().draw();
	//         	$.each(data, function(i,v){
	// 				// //option action
	// 				if((v.final != 'y')&&(v.jenis_tindakan=='perawatan')){
	// 					output = "			<div class='input-group-btn'>";
	// 					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
	// 					output += "				<ul class='dropdown-menu pull-right'>";
	// 					output += "					<li><a target='_blank' href='"+baseURL+"asset/transaksi/pdf/"+v.id_main+"' ><i class='fa fa-print'></i>Cetak Form Maintenance</a></li>";
	// 					output += "				</ul>";
	// 					output += "	        </div>";
	// 				}
	// 				if(v.final != 'y'){
	// 					output = "			<div class='input-group-btn'>";
	// 					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
	// 					output += "				<ul class='dropdown-menu pull-right'>";
	// 					output += "					<li><a href='javascript:void(0)' class='maintenance' data-id_main='"+v.id_main+"' data-id_aset='"+v.id_aset+"' data-jenis_tindakan='"+v.jenis_tindakan+"'><i class='fa fa-wrench'></i>Proses Maintenance</a></li>";
	// 					output += "				</ul>";
	// 					output += "	        </div>";
	// 				}
	// 				if(v.final == 'y'){
	// 					output = "			<div class='input-group-btn'>";
	// 					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
	// 					output += "				<ul class='dropdown-menu pull-right'>";
	// 					output += "					<li><a href='javascript:void(0)' class='detail' data-id_main='"+v.id_main+"' data-id_aset='"+v.id_aset+"' data-jenis_tindakan='"+v.jenis_tindakan+"'><i class='fa fa-search'></i>Detail</a></li>";
	// 					output += "				</ul>";
	// 					output += "	        </div>";
	// 				}
	//
	// 				// console.log(v);
	// 				//generate rows
	//         		t.row.add( [
	// 		            v.nomor,
	// 		            v.nama_pabrik,
	// 		            v.nama_lokasi,
	// 					v.nama_sub_lokasi,
	// 					v.nama_area,
	// 					v.nama_jenis,
	// 					v.nama_merk,
	// 					v.nomor_sap,
	// 					v.tanggal_mulai,
	// 					v.tanggal_selesai,
	// 					v.jenis_tindakan,
	// 					v.operator,
	// 					v.catatan,
	// 					v.label_status,
	// 					output
	// 		        ] ).draw( false );
	//         	});
	//
	//         }
	// 	});
	// });
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
		$('#myModalLabel').html("Tambah/ Edit Asset IT");
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
    var alat		= $("#alat").val();
	

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
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
            url: baseURL+'asset/transaksi/get/detail/fo/'+alat+'/bom',
            // url: baseURL+'asset/transaksi/get/detail/fo/lab/bom',
            type: 'POST',
            dataType: 'json',
            data: {
                jenis 		: jenis,
                merk 		: merk,
                pabrik 	: pabrik,
                lokasi 	: lokasi,
                area 		: area,
                jam_mulai 	: jam_mulai,
                jam_sampai : jam_sampai,
                umur_mulai : umur_mulai,
                umur_sampai: umur_sampai,
                overdue	: overdue,
                kondisi	: kondisi,
                status		: status
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "tbl_inv_main.id_main",
                "name" : "id_main",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.id_main;
                },
                "visible": false
            },
            {
                "data": "tbl_inv_aset.nomor",
                "name" : "nomor",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.nomor;
                }
            },
            {
                "data": "tbl_inv_pabrik.nama",
                "name" : "nama_pabrik",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.nama_pabrik;
                }
            },
            {
                "data": "tbl_inv_lokasi.nama",
                "name" : "nama_lokasi",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_lokasi;
                }
            },
            {
                "data": "tbl_inv_sub_lokasi.nama",
                "name" : "nama_sub_lokasi",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_sub_lokasi;
                }
            },
            {
                "data": "tbl_inv_area.nama",
                "name" : "nama_area",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.nama_area;
                }
            },
            {
                "data": "tbl_inv_jenis.nama",
                "name" : "nama_jenis",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_jenis;
                }
            },
            {
                "data": "tbl_inv_merk.nama",
                "name" : "nama_merk",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_merk;
                }
            },
            {
                "data": "tbl_inv_aset.nomor_sap",
                "name" : "nomor_sap",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nomor_sap;
                }
            },
            {
                "data": "tbl_inv_main.tanggal_mulai",
                "name" : "tanggal_mulai",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.tanggal_mulai;
                }
            },
            {
                "data": "tbl_inv_main.tanggal_selesai",
                "name" : "tanggal_selesai",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.tanggal_selesai;
                }
            },
            {
                "data": "tbl_inv_main.jenis_tindakan",
                "name" : "jenis_tindakan",
                "width": "5%",
                "render": function (data, type, row) {
                    if(row.jenis_tindakan != null){
                        return row.jenis_tindakan;
                    }else{
                        return 'perbaikan';
                    }
                }
            },
            {
                "data": "tbl_inv_main.operator",
                "name" : "operator",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.operator;
                }
            },
            {
                "data": "tbl_inv_main.catatan",
                "name" : "catatan",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.catatan;
                }
            },
            {
                "data": "tbl_inv_main.final",
                "name" : "final",
                "width": "5%",
                "render": function (data, type, row) {
                    if(row.final=='y'){
                        return '<label class="label label-success">Done</label>';
                    }else{
                        return '<label class="label label-danger">On Progress</label>';
                    }
                }
            },
            {
                // "data": "tbl_inv_aset.id_aset",
				"data": "tbl_inv_main.id_main",
                "name" : "id_main",
                "width": "5%",
                "render": function (data, type, row) {
					if((row.final != 'y')&&(row.jenis_tindakan=='perawatan')){	
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a target='_blank' href='"+baseURL+"asset/transaksi/pdf/"+row.id_main+"' ><i class='fa fa-print'></i>Cetak Form Maintenance</a></li>";
						output += "				</ul>";
						output += "	        </div>";
					}
					if(row.final != 'y'){	
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a href='javascript:void(0)' class='maintenance' data-id_main='"+row.id_main+"' data-id_aset='"+row.id_aset+"' data-jenis_tindakan='"+row.jenis_tindakan+"'><i class='fa fa-wrench'></i>Proses Maintenance</a></li>";
						output += "				</ul>";
						output += "	        </div>";
					}
					if(row.final == 'y'){	
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a href='javascript:void(0)' class='detail' data-id_main='"+row.id_main+"' data-id_aset='"+row.id_aset+"' data-jenis_tindakan='"+row.jenis_tindakan+"'><i class='fa fa-search'></i>Detail</a></li>";
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
