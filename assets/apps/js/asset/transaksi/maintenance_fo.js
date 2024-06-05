$(document).ready(function(){
	//export to excel
    $(document).on('click', '#excel_button', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'asset/transaksi/excel/fo/berat/'
            +'?jenis='+$('#jenis').val()
            +'&merk='+$('#merk').val()
            +'&kondisi='+$('#kondisi').val()
            +'&status='+$('#status').val()
            +'&pabrik='+$('#pabrik').val()
            +'&lokasi='+$('#lokasi').val()
            +'&area='+$('#area').val()
            +'&jam_mulai='
            +'&jam_selesai='
            +'&umur_mulai='
            +'&overdue='
            +'&berat='
            +'&pengguna='
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
		resetForm_use($('.form-transaksi-fo'));
		$.ajax({
    		url: baseURL+'asset/transaksi/get/fo',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset
			},
			success: function(data){
				// console.log(data);
				// $(".title-form").html("Edit Setting Program Batch");
				$.each(data, function(i,v){
					$("input[name='id_aset']").val(v.id_aset);
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

					// if(v.nama_kategori == "Gearbox"){
					if(v.have_ratio == 'y' ){
						$(".divratio").show();
					} else $(".divratio").hide();
					if(v.nama_kondisi == "Tidak Beroperasi"){
						$(".divrusak").show();
					} else $(".divrusak").hide();
					$("input[name='ratio']").val(v.ratio);
					$("select[name='id_jenis_kerusakan']").val(v.id_kerusakan).trigger("change");
					$(".gambar_fo").attr('src', v.gambar_fo);
					$("input[name='hidden_gambar_fo']").val(v.gambar_fo);
				});
				
			},
			complete: function () {
				$('#add_modal').modal('show');
			}
			
		});
    });

    // compare ratio
    $(document).on("click", ".compare", function(){	
		var data_compare	= $(this).data("compare");
		var datasplit 		= data_compare.split("|");
		var jenis 			= [datasplit[0]];
		var ratio 			= [datasplit[1]];
		var nomor_except 	= [datasplit[2]];
		var kondisi			= ["1","6"];
		console.log(data_compare);
		resetForm_use($('.form-transaksi-fo'));
		$.ajax({
    		url: baseURL+'asset/transaksi/get/fo',
			type: 'POST',
			dataType: 'JSON',
			data: {
				jenis 	: jenis,
				ratio 	: ratio,
				kondisi : kondisi
			},
			beforeSend: function () {
                var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                $("body .overlay-wrapper").append(overlay);
            },
			success: function(data){
				console.log(data);
				$("#myModalLabel_compare").html("<strong>Detail Merk yang Kompatibel</strong>");
				var t           = $('#sspTable2').DataTable();
                //swap warning datatable from alert to console.log 
                $.fn.dataTable.ext.errMode = 'none'; 
                t.clear().draw();
                $.each(data, function(i,v){
                	if(v.nomor != nomor_except) {
	                   	t.row.add( [
	                       	v.nomor,
	                       	v.nama_jenis,
	                        (v.nama_merk+" - "+v.nama_merk_tipe).toUpperCase(),
	                        (v.ratio).toUpperCase(),
	                        v.nama_pabrik,
	                        
	                    ] ).draw( false );                
	                    //refresh width
	                    t.columns.adjust().draw();
	                }
                });
				
			},
			complete: function () {
				$('#modal_compare').modal('show');
				$("body .overlay-wrapper .overlay").remove();
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
				// console.log(data);
				$(".title-form").html("Maintenance Asset FO");
				$.each(data, function(i,v){
					$("input[name='id_aset']").val(v.id_aset);
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

					if(v.have_ratio == 'y' ){
						$(".divratio").show();
					} else $(".divratio").hide();
					if(v.nama_kondisi == "Tidak Beroperasi"){
						$(".divrusak").show();
					} else $(".divrusak").hide();
					$("input[name='ratio']").val(v.ratio);
					$("select[name='id_jenis_kerusakan']").val(v.id_kerusakan).trigger("change");
					$(".gambar_fo").attr('src', v.gambar_fo);
					$("input[name='hidden_gambar_fo']").val(v.gambar_fo);
					
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
				// console.log(data);
				$(".title-form").html("Perbaikan Asset FO");
				$.each(data, function(i,v){
					$("input[name='id_aset']").val(v.id_aset);
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
					
					if(v.have_ratio == 'y' ){
						$(".divratio").show();
					} else $(".divratio").hide();
					if(v.nama_kondisi == "Tidak Beroperasi"){
						$(".divrusak").show();
					} else $(".divrusak").hide();
					$("input[name='ratio']").val(v.ratio);
					$("select[name='id_jenis_kerusakan']").val(v.id_kerusakan).trigger("change");
					$(".gambar_fo").attr('src', v.gambar_fo);
					$("input[name='hidden_gambar_fo']").val(v.gambar_fo);
				});
				
			},
			complete: function () {
				$('#perbaikan_modal').modal('show');
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
			beforeSend: function(){
						 $("#show_maintenance").html("");
						 $("#show_hour_meter").html("");
			},			
			success: function(data){
				// console.log(data);
				$(".title-form").html("Historical Asset FO");
				$.each(data, function(i,v){
					$("input[name='id_aset']").val(v.id_aset);
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
					//load history hour meter
					var no = 0;
					var nil	= "<table class='table table-bordered table-striped table-modals'>";
						nil	 	+= "<thead>";
						nil	 	+= 		"<tr>";
						nil	 	+= 			"<th>No</th><th>Last Update</th><th>Hour Meter</th><th>Updated By </th>";
						nil	 	+= 		"</tr>";
						nil	 	+= "</thead>";
						nil	 	+= "<tbody>";
					$.each(v.arr_main, function (x, y) {
						if(y.jenis_tindakan=='perubahan'){
							no = no+1;
							if(y.jam_jalan>0){
								var jam_jalan = numberWithCommas(y.jam_jalan);
							}else{
								var jam_jalan = y.jam_jalan;
							}
							nil	 	+= 		"<tr>";
							nil	 	+= 			"<td>"+no+"</td><td>"+y.tanggal_input+"</td><td>"+jam_jalan+"</td><td>"+y.nama_karyawan+"</td>";
							nil	 	+= 		"</tr>";
						}
						
					});
						nil	 	+= "</tbody>";
					$("#show_hour_meter").append(nil);
					
					//load history maintenance
					var no = 0;
					var nil2	= "<table class='table table-bordered table-striped table-modals'>";
						nil2	 	+= "<thead>";
						nil2	 	+= 		"<tr>";
						nil2	 	+= 			"<th>No</th><th>Tanggal Input</th><th>Jenis Tindakan</th><th>Hour Meter</th><th>Catatan</th><th>Tanggal Mulai</th><th>Tanggal Selesai</th><th>Updated By</th>";
						nil2	 	+= 		"</tr>";
						nil2	 	+= "</thead>";
						nil2	 	+= "<tbody>";
					$.each(v.arr_main, function (a, b) {
						if((b.jenis_tindakan=='perbaikan')||(b.jenis_tindakan=='perawatan')){
							no = no+1;
							if(b.jam_jalan>0){
								var jam_jalan = numberWithCommas(b.jam_jalan);
							}else{
								var jam_jalan = b.jam_jalan;
							}
							nil2	 	+= 		"<tr>";
							nil2	 	+= 			"<td>"+no+"</td><td>"+b.tanggal_input+"</td><td>"+b.nama_jenis_tindakan+"</td><td>"+jam_jalan+"</td><td>"+b.catatan+"</td><td>"+b.tanggal_mulai2+"</td><td>"+b.tanggal_selesai2+"</td><td>"+b.nama_karyawan+"</td>";
							nil2	 	+= 		"</tr>";
						}
						
					});
						nil2	 	+= "</tbody>";
					$("#show_maintenance").append(nil2);
					
					if(v.have_ratio == 'y' ){
						$(".divratio").show();
					} else $(".divratio").hide();
					if(v.nama_kondisi == "Tidak Beroperasi"){
						$(".divrusak").show();
					} else $(".divrusak").hide();
					$("input[name='ratio']").val(v.ratio);
					$("select[name='id_jenis_kerusakan']").val(v.id_kerusakan).trigger("change");
					$(".gambar_fo").attr('src', v.gambar_fo);
					$("input[name='hidden_gambar_fo']").val(v.gambar_fo);
					
				});
				
			},
			complete: function () {
				var t = $('.table-modals').DataTable({
					
					order: [[0, 'asc']],
					lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
					scrollX: true
					
				});
                setTimeout(function () {
                    $("table.dataTable").DataTable().columns.adjust();
                }, 1500);				
				
				$('#histori_modal').modal('show');
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
				// console.log(data);
				$(".title-form").html("Perbaikan Asset FO");
				$.each(data, function(i,v){
					$("input[name='id_aset']").val(v.id_aset);
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
					
					//load periode
					var output = '';
					$.each(v.arr_periode, function (x, y) {
						var selected = (y.id_periode == v.id_periode ? 'selected' : '');
						output += '<option value="' + y.id_periode + '" '+selected+'>' + y.nama + '</option>';
					});
					$("select[name='id_periode']").html(output).select2();
					
					if(v.have_ratio == 'y' ){
						$(".divratio").show();
					} else $(".divratio").hide();
					if(v.nama_kondisi == "Tidak Beroperasi"){
						$(".divrusak").show();
					} else $(".divrusak").hide();
					$("input[name='ratio']").val(v.ratio);
					$("select[name='id_jenis_kerusakan']").val(v.id_kerusakan).trigger("change");
					$(".gambar_fo").attr('src', v.gambar_fo);
					$("input[name='hidden_gambar_fo']").val(v.gambar_fo);
					
				});
				
			},
			complete: function () {
				$('#modal_perawatan').modal('show');
			}
			
		});
    });

    //histori
	$(document).on("click", ".detail", function(){	
		var id_aset	= $(this).data("id_aset");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/fo',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset
			},
			beforeSend: function(){
						 // $("#show_maintenance").html("");
						 // $("#show_hour_meter").html("");
			},			
			success: function(data){
				// console.log(data);
				// $(".title-form").html("Historical Asset FO");
				$.each(data, function(i,v){
					$("input[name='id_aset']").val(v.id_aset);
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
					
					if(v.have_ratio == 'y' ){
						$(".divratio").show();
					} else $(".divratio").hide();
					if(v.nama_kondisi == "Tidak Beroperasi"){
						$(".divrusak").show();
					} else $(".divrusak").hide();
					$("input[name='ratio']").val(v.ratio);
					$("select[name='id_jenis_kerusakan']").val(v.id_kerusakan).trigger("change");
					$(".gambar_fo").attr('src', v.gambar_fo);
					$("input[name='hidden_gambar_fo']").val(v.gambar_fo);
					
				});
				
			},
			complete: function () {
				// show tab detail
				$('#tab-detail_det').addClass("tab-pane active");
				$('li > a[href="#tab-detail_det"]').tab("show");
				$('#detail_modal').modal('show');
			}
			
		});
    });
	
	// ubah status
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

	//submit save
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

	// submit perawatan
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
	
	//submit perubahan
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

	// submit perbaikan
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

	//set on change id_kondisi
    $(document).on("change", "select[name='id_kondisi']", function(e){
		var id_kondisi	= $(this).val();
		$.ajax({
    		url: baseURL+'asset/transaksi/convert/id',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id	: id_kondisi
			},
			success: function(data){
				// console.log(data);
				if(data == 2){
					$('.divrusak').show();
					// $("select[name='id_jenis_kerusakan']").val("").trigger("change.select2");
				} else {
					$('.divrusak').hide();
					$("select[name='id_jenis_kerusakan']").val("").trigger("change.select2");
				}
			}
		});
		// alert( yourSelect.options[ yourSelect.selectedIndex ].value );
    });

    //set on change id_kategori
    $(document).on("change", "select[name='id_kategori']", function(e){
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
				$("select[name='id_jenis']").html(value);
			}
		});
    });

    //set on change id_kategori
    $(document).on("change", "select[name='id_jenis']", function(e){
		var id_jenis	= $(this).val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/jenis',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_jenis	: id_jenis
			},
			success: function(data){
				// console.log(data);
				$.each(data, function(i,v){
					if(v.have_ratio == "y"){
						$('.divratio').show();
					} else {
						$('.divratio').hide();
						$("input[name='ratio']").val("");
					}
				});				
			}
		});
		// alert( yourSelect.options[ yourSelect.selectedIndex ].value );
    });

	//set on change id_jenis
    $(document).on("change", "select[name='id_jenis']", function(e){
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
				$("select[name='id_merk']").html(value);
			}
		});
    });

	//set on change id_merk
    $(document).on("change", "select[name='id_merk']", function(e){
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
				$("select[name='id_merk_tipe']").html(value);
			}
		});
    });

	//set on change id_lokasi
    $(document).on("change", "select[name='id_lokasi']", function(e){
		var id_lokasi	= $(this).val();
		var id_pabrik	= $("select[name='id_pabrik']").val();
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
    $(document).on("change", "select[name='id_lokasi']", function(e){
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
				$("select[name='id_sub_lokasi']").html(value);
			}
		});
    });

	//set on change id_sub_lokasi
    $(document).on("change", "select[name='id_sub_lokasi']", function(e){
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
				$("select[name='id_area']").html(value);
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
		resetForm_use($('.form-transaksi-fo'));
		$('#add_modal').modal('show');
	});

	// reset form
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
		
		// ========================= use 
		$('.form-transaksi-fo').trigger("reset");
		$('input[type="hidden"]').val('');
  		$('li > a[href="#tab-data"]').tab("show");
		$('.form-transaksi-fo select').val("").trigger("change");
		$(".gambar_fo").attr('src', "");
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
            url: baseURL+'asset/transaksi/get/fo/bom/berat',
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
                "data": "ratio",
                "name" : "ratio",
                "width": "10%",
                "render": function (data, type, row) {
                	var ratio = row.ratio == 0 ? "" : row.ratio;
                    return ratio;
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
                "data": "jam_jalan",
                "name" : "jam_jalan",
                "width": "5%",
                "render": function (data, type, row) {
					if(row.jam_jalan!=null){
						return numberWithCommas(row.jam_jalan);	
					}else{
						return row.jam_jalan;
					}
                    
                }
            },
            // {
                // "data": "umur_aset",
                // "name" : "umur_aset",
                // "width": "5%",
                // "render": function (data, type, row) {
                    // return row.umur_aset;
                // }
            // },
            // {
                // "data": "service_next",
                // "name" : "service_next",
                // "width": "5%",
                // "render": function (data, type, row) {
                    // return row.service_next;
                // }
            // },
            // {
                // "data": "jam_jalan_next",
                // "name" : "jam_jalan_next",
                // "width": "5%",
                // "render": function (data, type, row) {
                    // return row.jam_jalan_next;
                // },
                // "visible": false
            // },
            // {
				// "data": "jam_jalan"+"jam_jalan_next",
                // "name" : "jam_overdue",
                // "width": "5%",
                // "render": function (data, type, row) {
					// var selisih = row.jam_jalan_next - row.jam_jalan;
                    // if(selisih<0){
                        // return '<div class="text-red">('+numberWithCommas(selisih*-1)+')</div>';
                    // }else{
                        // return '<div>'+numberWithCommas(selisih)+'</div>';
                    // }
                // },
                // "visible": false
            // },
            // {
                // "data": "periode_bulan"+"main_bulan",
                // "name" : "tanggal_service_next",
                // "width": "5%",
                // "render": function (data, type, row) {
					// var selisih_bulan = row.periode_bulan - row.main_bulan;
					// tanggal_service_next = new Date();
					// tanggal_service_next.setMonth(tanggal_service_next.getMonth()+selisih_bulan);	

					// return convert(tanggal_service_next);
                // },
                // "visible": false
            // },
            // {
                // "data": "periode_bulan"+"main_bulan",
                // "name" : "bulan_overdue",
                // "width": "5%",
                // "render": function (data, type, row) {
					// var selisih_bulan = row.periode_bulan - row.main_bulan;
					// tanggal_service_next = new Date();
					// tanggal_service_next.setMonth(tanggal_service_next.getMonth()+selisih_bulan);	
					// var oneDay = 24*60*60*1000;
					// bulan_overdue = Math.round(Math.round((tanggal_service_next.getTime() - new Date()) / (oneDay*30)));
                    // if(bulan_overdue<0){
                        // return '<div class="text-red">('+bulan_overdue*-1+')</div>';
                    // }else{
                        // return '<div>'+bulan_overdue+'</div>';
                    // }
                // },
                // "visible": false
            // },
            {
                "data": "tanggal_edit",
                "name" : "tanggal_edit",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.tanggal_edit;
                }
            },
            {
                "data": "id_kondisi",
                "name" : "id_kondisi",
                "width": "5%",
                "render": function (data, type, row) {
                    if(row.id_kondisi==1){
                        return '<label class="label label-success">Beroperasi</label>';
                    }else{
                        return '<label class="label label-danger">Tidak Beroperasi</label>';
                    }
                }
            },
            {
                "data": "na",
                "name" : "na",
                "width": "5%",
                "render": function (data, type, row) {
                    if(row.na=='n'){
                        return '<label class="label label-success">AKTIF</label>';
                    }else{
                        return '<label class="label label-danger">NON AKTIF</label>';
                    }
                }
            },
            {
                // "data": "id_aset",
				"data": "id_aset"+"na",
                "name" : "id_aset",
                "width": "5%",
                "render": function (data, type, row) {
                	var perbandingan_button = "<li><a href='javascript:void(0)' class='compare' data-compare='"
                								+row.id_jenis+"|"+row.ratio+"|"+row.nomor+"'><i class='fa fa-exchange'></i> Perbandingan Gearbox</a></li>";
					perbandingan_button 	= row.have_ratio == 'y'
												? perbandingan_button : "";
					
					if(row.na == 'n'){
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a href='javascript:void(0)' class='perubahan' data-id_aset='"+row.id_aset+"'><i class='fa fa-clock-o'></i> Perbarui Jam Jalan</a></li>";
						output += "					<li><a href='javascript:void(0)' class='perawatan' data-id_aset='"+row.id_aset+"'><i class='fa fa-sun-o'></i> Perawatan Rutin</a></li>";
						output += "					<li><a href='javascript:void(0)' class='perbaikan' data-id_aset='"+row.id_aset+"'><i class='fa fa-wrench'></i> Perbaikan Kerusakan</a></li>";
						output += "					<li><a href='javascript:void(0)' class='histori' data-id_aset='"+row.id_aset+"'><i class='fa fa-h-square'></i> History</a></li>";
						output += "					<li><a href='javascript:void(0)' class='detail' data-id_aset='"+row.id_aset+"'><i class='fa fa-list'></i> Detail</a></li>";
						output += 					perbandingan_button;
						output += "					<li><a href='javascript:void(0)' class='edit' data-edit='"+row.id_aset+"'><i class='fa fa-pencil-square-o'></i> Edit Asset</a></li>";
						output += "					<li><a href='javascript:void(0)' class='nonactive' data-nonactive='"+row.id_aset+"'><i class='fa fa-minus-square-o'></i> Set Tidak Akif</a></li>";
						output += "					<li><a href='javascript:void(0)' class='delete' data-delete='"+row.id_aset+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
						output += "				</ul>";
						output += "	        </div>";
						
					}else{
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a href='javascript:void(0)' class='setactive' data-setactive='"+row.id_aset+"'><i class='fa fa-check-square-o'></i> Set Akif</a></li>";
						output += "					<li><a href='javascript:void(0)' class='delete' data-delete='"+row.id_aset+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";						
						output += "				</ul>";
						output += "	        </div>";
					}
                    return output;
                }
            }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            // console.log(data);
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
