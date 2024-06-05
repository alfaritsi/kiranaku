$(document).ready(function() {
	$(".form-control_komentar").css({'visibility' : 'hidden'});
	$(".form-control_change").css({'visibility' : 'hidden'});
	// $(document).on("change", ".tanggal", function(e) {
	$('.tanggal').datepicker({
        format: 'yyyy-mm-dd',
	    autoclose: true
    });

    //switch
    $('.switch-onoff').bootstrapToggle({
        on: 'Yes',
        off: 'No'
    });
	//hide approve
	$("#action_btn_approve_manager").hide();
	$("#action_btn_approve_legal").hide();
	$("#action_btn_approve_proc").hide();
	
    //cek all
    $(document).on("change", ".isSelectAllPlantExtend", function(e) {
        if ($(".isSelectAllPlantExtend").is(':checked')) {
            $('#plant_extend').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#plant_extend').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    $(document).on("change", ".isSelectAllPlantDelete", function(e) {
        if ($(".isSelectAllPlantDelete").is(':checked')) {
            $('#plant_delete').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#plant_delete').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    $(document).on("change", ".isSelectAllPlantUndelete", function(e) {
        if ($(".isSelectAllPlantUndelete").is(':checked')) {
            $('#plant_undelete').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#plant_undelete').select2('destroy').find('option').prop('selected', false).end().select2();
        }
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
    $(document).on("change", "#id_jenis_vendor_filter, #id_kualifikasi_spk_filter, #id_role_filter, #status_filter_pengajuan, #status_filter_extend, #status_filter_change, #status_filter_delete, #status_filter_undelete, #filter_jenis_pengajuan", function() {
        datatables_ssp();
    });
	
	//set on change id_jenis_vendor
    $(document).on("change", "#id_jenis_vendor", function(e){
		var id_jenis_vendor	= $(this).val();
		var action = $('input[name="action"]').val();
		var action_detail = $('input[name="action_detail"]').val();
		var id_data = $('input[name="id_data"]').val();
		var id_data_temp = $('input[name="id_data_temp"]').val();
		// alert(id_data_temp);
		if(id_jenis_vendor!=''){
			$.ajax({ 
				url: baseURL + 'vendor/transaksi/get/jenis_vendor_dokumen',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_jenis_vendor	: id_jenis_vendor,
					id_data			: id_data,
					id_data_temp	: id_data_temp
				}, 
				success: function (data) {
					if (data) {
						var output 	= '';
						output += '<div class="col-xs12">';
						output += '<fieldset class="fieldset-info">';
						output += '<legend>Dokumen Vendor</legend>';
						$.each(data, function (i, v) {
							if(v.mandatory=='Mandatory'){
								var req 	= 'required="required"';
								var flag	= '*';	
							}else{
								var req 	= '';
								var flag	= '';	
							}
							var tanggal_awal 		= (v.tanggal_awal!=null ? v.tanggal_awal : '');
							var tanggal_akhir 		= (v.tanggal_akhir!=null ? v.tanggal_akhir : '');
							
							output += '		<div class="form-group">';
							output += '			<div class="row">';
							output += '				<div class="col-xs-12">';
							if(v.link_file!=null){
								output += '					<label for="nama">'+v.nama_dokumen+' '+flag+' <a href="'+baseURL+'assets/'+v.link_file+'" target="_blank"><i class="fa fa-search"></i></a></label>';
							}else{
								output += '					<label for="nama">'+v.nama_dokumen+' '+flag+'</label>';
							}
							output += '					<input type="file" class="form-control form-control-hide form-control-approval_proc" name="file_dokumen_'+v.id_master_dokumen+'[]" id="file_dokumen_'+v.id_master_dokumen+'">';
							output += '					<input type="hidden" class="form-control form-control-hide" name="nama_dokumen_hide_'+v.id_master_dokumen+'" value="'+v.nama_dokumen_hide+'">';
							output += '				</div>';
							output += '			</div>';
							output += '			<div class="row">';
							output += '				<div class="col-sm-6">';
							output += '					<label for="nama">Tanggal Awal</label>';
							output += '					<input type="text" class="form-control form-control-hide tanggal" name="tanggal_awal_jenis_'+v.id_master_dokumen+'" value="'+tanggal_awal+'" '+req+'>';
							output += '				</div>';
							output += '				<div class="col-sm-6">';
							output += '					<label for="nama">Tanggal Akhir</label>';
							output += '					<input type="text" class="form-control form-control-hide tanggal" name="tanggal_akhir_jenis_'+v.id_master_dokumen+'"  value="'+tanggal_akhir+'"  '+req+'>';
							output += '				</div>';
							output += '			</div>';
							output += '		</div>';	
							
						});
						
						output += '</fieldset>';
						output += '</div>';
						$("#show_dokumen_jenis").html(output);
					}
				},
				complete: function() {
					$('.tanggal').datepicker({
						format: 'dd.mm.yyyy',
						autoclose: true
					});
					// if((action=='approve')||(action=='detail')||(action=='change')){
					if((action=='approve')||(action=='detail')||(action_detail=='approve')){
						$('.form-control-hide').prop('required', false);
						$('.form-control-hide').prop('disabled', true);
					}
					
				}
				
			});			
		}else{
			$("#show_dokumen_jenis").html('');
		}
    });
	//set on change id_jenis_vendor_temp
    $(document).on("change", "#id_jenis_vendor_temp", function(e){
		var id_jenis_vendor	= $(this).val();
		var action = $('input[name="action"]').val();
		var action_detail = $('input[name="action_detail"]').val();
		var id_data = $('input[name="id_data"]').val();
		var id_data_temp = $('input[name="id_data_temp"]').val();
		// alert(id_data_temp);
		if(id_jenis_vendor!=''){
			$.ajax({ 
				url: baseURL + 'vendor/transaksi/get/jenis_vendor_dokumen_temp',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_jenis_vendor	: id_jenis_vendor,
					id_data			: id_data,
					id_data_temp	: id_data_temp
				}, 
				success: function (data) {
					if (data) {
						var output 	= '';
						output += '<div class="col-xs12">';
						output += '<fieldset class="fieldset-info">';
						output += '<legend>Dokumen Vendor</legend>';
						$.each(data, function (i, v) {
							if(v.mandatory=='Mandatory'){
								var req 	= 'required="required"';
								var flag	= '*';	
							}else{
								var req 	= '';
								var flag	= '';	
							}
							var tanggal_awal 		= (v.tanggal_awal!=null ? v.tanggal_awal : '');
							var tanggal_akhir 		= (v.tanggal_akhir!=null ? v.tanggal_akhir : '');
							
							output += '		<div class="form-group">';
							output += '			<div class="row">';
							output += '				<div class="col-xs-12">';
							if(v.link_file!=null){
								output += '					<label for="nama">'+v.nama_dokumen+' '+flag+' <a href="'+baseURL+'assets/'+v.link_file+'" target="_blank"><i class="fa fa-search"></i></a></label>';
							}else{
								output += '					<label for="nama">'+v.nama_dokumen+' '+flag+'</label>';
							}
							output += '					<input type="file" class="form-control form-control-hide form-control-approval_proc" name="file_dokumen_'+v.id_master_dokumen+'[]" id="file_dokumen_'+v.id_master_dokumen+'">';
							output += '					<input type="hidden" class="form-control form-control-hide" name="nama_dokumen_hide_'+v.id_master_dokumen+'" value="'+v.nama_dokumen_hide+'">';
							output += '				</div>';
							output += '			</div>';
							output += '			<div class="row">';
							output += '				<div class="col-sm-6">';
							output += '					<label for="nama">Tanggal Awal</label>';
							output += '					<input type="text" class="form-control form-control-hide tanggal" name="tanggal_awal_jenis_'+v.id_master_dokumen+'" value="'+tanggal_awal+'" '+req+'>';
							output += '				</div>';
							output += '				<div class="col-sm-6">';
							output += '					<label for="nama">Tanggal Akhir</label>';
							output += '					<input type="text" class="form-control form-control-hide tanggal" name="tanggal_akhir_jenis_'+v.id_master_dokumen+'"  value="'+tanggal_akhir+'"  '+req+'>';
							output += '				</div>';
							output += '			</div>';
							output += '		</div>';	
							
						});
						
						output += '</fieldset>';
						output += '</div>';
						$("#show_dokumen_jenis_temp").html(output);
					}
				},
				complete: function() {
					$('.tanggal').datepicker({
						format: 'dd.mm.yyyy',
						autoclose: true
					});
					// if((action=='approve')||(action=='detail')||(action=='change')){
					if((action=='approve')||(action=='detail')||(action_detail=='approve')){
						$('.form-control-hide').prop('required', false);
						$('.form-control-hide').prop('disabled', true);
					}
					
				}
				
			});			
		}else{
			$("#show_dokumen_jenis").html('');
		}
    });
    $(document).on("change", "#kualifikasi_spk", function(e){
		var id_kualifikasi_spk	= $(this).val();
		var id_data 		= $('input[name="id_data"]').val();
		var id_data_temp 	= $('input[name="id_data_temp"]').val();
		var action 			= $('input[name="action"]').val();
		var action_detail 	= $('input[name="action_detail"]').val();
		// alert(id_data_temp);
		if(id_kualifikasi_spk!=''){
			$.ajax({ 
				url: baseURL + 'vendor/transaksi/get/kualifikasi_dokumen',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_kualifikasi_spk	: id_kualifikasi_spk,
					id_data				: id_data,
					id_data_temp		: id_data_temp
				}, 
				success: function (data) {
					if (data) {
						var output 	= '';
						output += '<div class="col-xs12">';
						output += '<fieldset class="fieldset-info">';
						output += '<legend>Dokumen Kualifikasi</legend>';
						$.each(data, function (i, v) {
							if(v.mandatory=='Mandatory'){
								var req 	= 'required="required"';
								var flag	= '*';	
							}else{
								var req 	= '';
								var flag	= '';	
							}
							var tanggal_awal 		= (v.tanggal_awal!=null ? v.tanggal_awal : '');
							var tanggal_akhir 		= (v.tanggal_akhir!=null ? v.tanggal_akhir : '');
							
							output += '		<div class="form-group">';
							output += '			<div class="row">';
							output += '				<div class="col-xs-12">';
							if(v.link_file!=null){
								output += '					<label for="nama">'+v.nama_dokumen+' '+flag+' <a href="'+baseURL+'assets/'+v.link_file+'" target="_blank"><i class="fa fa-search"></i></a></label>';
							}else{
								output += '					<label for="nama">'+v.nama_dokumen+' '+flag+'</label>';
							}
							output += '					<input type="file" class="form-control form-control-hide form-control-approval_legal" name="file_dokumen_'+v.id_master_dokumen+'[]" id="file_dokumen_'+v.id_master_dokumen+'" '+req+'>';
							output += '					<input type="hidden" class="form-control form-control-hide form-control-approval_legal" name="nama_dokumen_hide_'+v.id_master_dokumen+'" value="'+v.nama_dokumen_hide+'">';
							output += '				</div>';
							output += '			</div>';
							output += '			<div class="row">';
							output += '				<div class="col-sm-6">';
							output += '					<label for="nama">Tanggal Awal</label>';
							output += '					<input type="text" class="form-control form-control-hide tanggal" name="tanggal_awal_kualifikasi_'+v.id_master_dokumen+'" value="'+tanggal_awal+'" '+req+'>';
							output += '				</div>';
							output += '				<div class="col-sm-6">';
							output += '					<label for="nama">Tanggal Akhir</label>';
							output += '					<input type="text" class="form-control form-control-hide tanggal" name="tanggal_akhir_kualifikasi_'+v.id_master_dokumen+'"  value="'+tanggal_akhir+'" '+req+'>';
							output += '				</div>';
							output += '			</div>';
							output += '		</div>';	
						});
						output += '</fieldset>';
						output += '</div>';
						$("#show_dokumen_kualifikasi").html(output);
					}
				},
				complete: function() {
					$('.tanggal').datepicker({
						format: 'dd.mm.yyyy',
						autoclose: true
					});
					// if((action=='approve')||(action=='detail')||(action=='change')){
					if((action=='approve')||(action=='detail')||(action_detail=='approve')){
						$('.form-control-hide').prop('required', false);
						$('.form-control-hide').prop('disabled', true);
					}
					
				} 
			});			
		}else{
			$("#show_dokumen_kualifikasi").html('');
		}
    });
	
    $(document).on("change", "#kualifikasi_spk_temp", function(e){
		var id_kualifikasi_spk	= $(this).val();
		var id_data 		= $('input[name="id_data"]').val();
		var id_data_temp 	= $('input[name="id_data_temp"]').val();
		var action 			= $('input[name="action"]').val();
		var action_detail 	= $('input[name="action_detail"]').val();
		// alert(id_data_temp);
		if(id_kualifikasi_spk!=''){
			$.ajax({ 
				url: baseURL + 'vendor/transaksi/get/kualifikasi_dokumen_temp',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_kualifikasi_spk	: id_kualifikasi_spk,
					id_data				: id_data,
					id_data_temp		: id_data_temp
				}, 
				success: function (data) {
					if (data) {
						var output 	= '';
						output += '<div class="col-xs12">';
						output += '<fieldset class="fieldset-info">';
						output += '<legend>Dokumen Kualifikasi</legend>';
						$.each(data, function (i, v) {
							if(v.mandatory=='Mandatory'){
								var req 	= 'required="required"';
								var flag	= '*';	
							}else{
								var req 	= '';
								var flag	= '';	
							}
							var tanggal_awal 		= (v.tanggal_awal!=null ? v.tanggal_awal : '');
							var tanggal_akhir 		= (v.tanggal_akhir!=null ? v.tanggal_akhir : '');
							
							output += '		<div class="form-group">';
							output += '			<div class="row">';
							output += '				<div class="col-xs-12">';
							if(v.link_file!=null){
								output += '					<label for="nama">'+v.nama_dokumen+' '+flag+' <a href="'+baseURL+'assets/'+v.link_file+'" target="_blank"><i class="fa fa-search"></i></a></label>';
							}else{
								output += '					<label for="nama">'+v.nama_dokumen+' '+flag+'</label>';
							}
							output += '					<input type="file" class="form-control form-control-hide form-control-approval_legal" name="file_dokumen_'+v.id_master_dokumen+'[]" id="file_dokumen_'+v.id_master_dokumen+'" '+req+'>';
							output += '					<input type="hidden" class="form-control form-control-hide form-control-approval_legal" name="nama_dokumen_hide_'+v.id_master_dokumen+'" value="'+v.nama_dokumen_hide+'">';
							output += '				</div>';
							output += '			</div>';
							output += '			<div class="row">';
							output += '				<div class="col-sm-6">';
							output += '					<label for="nama">Tanggal Awal</label>';
							output += '					<input type="text" class="form-control form-control-hide tanggal" name="tanggal_awal_kualifikasi_'+v.id_master_dokumen+'" value="'+tanggal_awal+'" '+req+'>';
							output += '				</div>';
							output += '				<div class="col-sm-6">';
							output += '					<label for="nama">Tanggal Akhir</label>';
							output += '					<input type="text" class="form-control form-control-hide tanggal" name="tanggal_akhir_kualifikasi_'+v.id_master_dokumen+'"  value="'+tanggal_akhir+'" '+req+'>';
							output += '				</div>';
							output += '			</div>';
							output += '		</div>';	
						});
						output += '</fieldset>';
						output += '</div>';
						$("#show_dokumen_kualifikasi_temp").html(output);
					}
				},
				complete: function() {
					$('.tanggal').datepicker({
						format: 'dd.mm.yyyy',
						autoclose: true
					});
					// if((action=='approve')||(action=='detail')||(action=='change')){
					if((action=='approve')||(action=='detail')||(action_detail=='approve')){
						$('.form-control-hide').prop('required', false);
						$('.form-control-hide').prop('disabled', true);
					}
					
				} 
			});			
		}else{
			$("#show_dokumen_kualifikasi").html('');
		}
    });
	
	//change alasan_delete
    $(document).on("change", "#alasan_delete", function(e){
		var alasan_delete	= $(this).val();
		if((alasan_delete=='Ganti Vendor')||(alasan_delete=='Double Vendor')||(alasan_delete=='Perubahan Legalitas')){
			// output = '<div class="form-group">';
			// output += '<label for="description">Kode Vendor</label>';
			// output += '<input type="text" class="form-control form-control-hide" name="alasan_delete_detail" id="alasan_delete_detail" placeholder="Kode Vendor">';
			// output += '</div>';
			// $("#show_alasan_detail").html(output);			
			// $('#alasan_delete_detail').prop('required', true);
			$('.alasan_delete_detail').prop('required', true);
			$('.alasan_delete_detail').prop('disabled', false);
			
		}else{
			// output = '';
			// $("#show_alasan_detail").html(output);			
			// $('#alasan_delete_detail').prop('required', false);
			$("input[name='alasan_delete_detail']").val('');
			$('.alasan_delete_detail').prop('required', false);
			$('.alasan_delete_detail').prop('disabled', true);
		}
    });
	//change form-control-perubahan_data
    $(document).on("change", ".form-control-perubahan_data", function(e){
		$("input[name='perubahan_data']").val('y');
		// var id_data_temp = $('input[name="id_data_temp"]').val();
		// if(id_data_temp!=null){
			// $("input[name='perubahan_data']").val('y');
		// }else{
			// $("input[name='perubahan_data']").val('n');
		// }
    });
	//change form-control-approval_proc
    $(document).on("change", ".form-control-approval_proc", function(e){
		$("input[name='approval_proc']").val('y');
    });
	//change form-control-approval_legal
    $(document).on("change", ".form-control-approval_legal", function(e){
		$("input[name='approval_legal']").val('y');
    });
	//change industri
    $(document).on("change", "#industri", function(e){
		var industri	= $(this).val();
		if(industri=='ES'){
			$('#status_do').prop('disabled', false);
		}else{
			$('#status_do').prop('disabled', true);
			$("select[name='status_do']").val('0').trigger("change.select2");
		}
    });
	//change industri
    $(document).on("change", "#acc_group", function(e){
		var acc_group	= $(this).val();
		if(acc_group=='ONVE'){
			$('#kota').prop('required', true);
		}
		if(acc_group!='ONVE'){
			$('#kota').prop('required', false);
		}
    });
	
	// //set on change id_tipe
    // $(document).on("change", "#id_tipe", function(e){
		// var id_tipe	= $(this).val();
		// if(id_tipe!=''){
			// $.ajax({ 
				// url: baseURL + 'vendor/master/get/tipe_dokumen',
				// type: 'POST',
				// dataType: 'JSON',
				// data: {
					// id_tipe	: id_tipe
				// }, 
				// success: function (data) {
					// if (data) {
						// var output 	= '';
						// output += '<div class="col-xs12">';
						// output += '<fieldset class="fieldset-info">';
						// output += '<legend>Dokumen Tipe Vendor</legend>';
						// $.each(data, function (i, v) {
							// if(v.mandatory=='y'){
								// var req 	= 'required="required"';
								// var flag	= '*';	
							// }else{
								// var req 	= '';
								// var flag	= '';	
							// }
							// output += '		<div class="form-group">';
							// output += '			<div class="row">';
							// output += '				<div class="col-xs-12">';
							// output += '					<label for="nama">'+v.nama+' '+flag+'</label>';
							// output += '					<input type="file" class="form-control form-control-hide" name="file_dokumen_'+v.id_tipe_dokumen_post+'[]" id="file_dokumen_'+v.id_tipe_dokumen+'" '+req+'>';
							// // output += '					<input type="file" class="form-control form-control-hide" name="file_dokumen_'+v.id_tipe_dokumen+'" id="file_dokumen_'+v.id_tipe_dokumen+'" '+req+'>';
							// output += '				</div>';
							// output += '			</div>';
							// output += '		</div>';	
						// });
						// output += '</fieldset>';
						// output += '</div>';
						// $("#show_dokumen_tipe").html(output);
					// }
				// } 
			// });			
		// }else{
			// $("#show_dokumen_tipe").html('');
		// }
    // });
	
	// //set on change id_kategori
    // $(document).on("change", "#id_kategori", function(e){
		// var id_kategori	= $(this).val();
		// if(id_kategori!=''){
			// $.ajax({ 
				// url: baseURL + 'vendor/master/get/kategori_dokumen',
				// type: 'POST',
				// dataType: 'JSON',
				// data: {
					// id_kategori	: id_kategori
				// }, 
				// success: function (data) {
					// if (data) {
						// var output 	= '';
						// output += '<div class="col-xs12">';
						// output += '<fieldset class="fieldset-info">';
						// output += '<legend>Dokumen Kategori Vendor</legend>';
						// $.each(data, function (i, v) {
							// if(v.mandatory=='y'){
								// var req 	= 'required="required"';
								// var flag	= '*';	
							// }else{
								// var req 	= '';
								// var flag	= '';	
							// }
							// output += '		<div class="form-group">';
							// output += '			<div class="row">';
							// output += '				<div class="col-xs-12">';
							// output += '					<label for="nama">'+v.nama+' '+flag+'</label>';
							// output += '					<input type="file" class="form-control form-control-hide" name="file_dokumen_kategori_'+v.id_kategori_dokumen_post+'[]" id="file_dokumen_kategori_'+v.id_kategori_dokumen_post+'" '+req+'>';
							// // output += '					<input type="file" class="form-control form-control-hide" name="file_dokumen_'+v.id_tipe_dokumen+'" id="file_dokumen_'+v.id_tipe_dokumen+'" '+req+'>';
							// // output += '					<input type="file" multiple="multiple" class="form-control" id="dokumen_tipe_'+v.id_tipe_dokumen+'" name="dokumen_tipe_'+v.id_tipe_dokumen+'[]" '+req+'>';
							// output += '				</div>';
							// output += '			</div>';
							// output += '		</div>';	
						// });
						// output += '</fieldset>';
						// output += '</div>';
						// $("#show_dokumen_kategori").html(output);
					// }
				// } 
			// });			
		// }else{
			// $("#show_dokumen_kategori").html('');
		// }
    // });
	
    //set on change tax_type
    $(document).on("change", "#tax_type", function(e) {
        var tax_type = $(this).val();
        $.ajax({
            url: baseURL + 'vendor/transaksi/get/tax_code',
            type: 'POST',
            dataType: 'JSON',
            data: {
                tax_type: tax_type
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Pilih Tax Code</option>';
                $.each(data, function(i, v) {
                    value += '<option value="' + v.tax_code + '">'+v.tax_code+' - '+ v.tax_code_name + '</option>';
                });
                $('#tax_code').html(value);
            },
            complete: function() {
				// $('.form-control-hide').prop('disabled', true);
            }
        });
    });
	
    //set on change negara
    $(document).on("change", "#negara", function(e) {
        var negara = $(this).val();
        $.ajax({
            url: baseURL + 'vendor/transaksi/get/provinsi',
            type: 'POST',
            dataType: 'JSON',
            data: {
                negara: negara
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Pilih Provinsi</option>';
                $.each(data, function(i, v) {
                    // console.log(data);
                    value += '<option value="' + v.id_provinsi + '">'+v.id_provinsi+' - '+ v.nama_provinsi + '</option>';
                });
                $('#provinsi').html(value);
            }
        });
    });
	
    //set on change search term1
    $(document).on("change", "#jenis_barang_jasa1", function(e) {
        var jenis_barang_jasa1 = $(this).val();
        $.ajax({
            url: baseURL + 'vendor/transaksi/get/term2',
            type: 'POST',
            dataType: 'JSON',
            data: {
                term1: jenis_barang_jasa1
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Search Term 2</option>';
                $.each(data, function(i, v) {
                    // console.log(data);
                    value += '<option value="' + v.nama + '">'+v.nama+'</option>';
                });
                $('#jenis_barang_jasa2').html(value);
            }
        });
    });
	
    // //set on change hitung_nilai
    // $(document).on("change", ".hitung_nilai", function(e) {
		// alert('aa');
		// var nilai_1 = $('input[name="nilai_1"]').val();
		// $("input[name='total_nilai']").val(nilai_1);
    // });
    //set on change add pilihan
    $(document).on("change", "#add_pilihan", function(e) {
		var stat 	= $(this).prop('checked');
		if(stat==true){
			$('#add_vendor_existing').prop('required', true);
			$('#add_vendor_existing').prop('disabled', false);
			$('#add_alasan').prop('required', true);
			$('#add_alasan').prop('disabled', false);
			$('#add_vendor_flag').prop('required', true);
			$('#add_vendor_flag').prop('disabled', false);
		}else{
			$('#add_vendor_existing').prop('required', false);
			$('#add_vendor_existing').prop('disabled', true);
			$('#add_alasan').prop('required', false);
			$('#add_alasan').prop('disabled', true);
			$('#add_vendor_flag').prop('required', false);
			$('#add_vendor_flag').prop('disabled', true);
			$("input[name='add_vendor_existing']").val('');
			$("select[name='add_alasan']").val('').trigger("change.select2");
			$("input[name='add_vendor_flag']").val('');
			
		}
    });
    //set on extend add pilihan
    $(document).on("change", "#add_pilihan_extend", function(e) {
		var stat 	= $(this).prop('checked');
		if(stat==true){
			$('#add_vendor_existing_extend').prop('required', true);
			$('#add_vendor_existing_extend').prop('disabled', false);
			$('#add_alasan_extend').prop('required', true);
			$('#add_alasan_extend').prop('disabled', false);
			$('#add_vendor_flag_extend').prop('required', true);
			$('#add_vendor_flag_extend').prop('disabled', false);
		}else{
			$('#add_vendor_existing_extend').prop('required', false);
			$('#add_vendor_existing_extend').prop('disabled', true);
			$('#add_alasan_extend').prop('required', false);
			$('#add_alasan_extend').prop('disabled', true);
			$('#add_vendor_flag_extend').prop('required', false);
			$('#add_vendor_flag_extend').prop('disabled', true);
			$("input[name='add_vendor_existing_extend']").val('');
			$("select[name='add_alasan_extend']").val('').trigger("change.select2");
			$("input[name='add_vendor_flag_extend']").val('');
			
		}
    });
	
	//change id_nilai
    $(document).on("click", "#opt_nilai", function (e) {
		// var id_nilai	= $(this).val();
		var id_kriteria	= $(this).data("id_kriteria");
		var id_nilai	= $(this).data("id_nilai");
		var nilai		= $(this).data("nilai");
		var bobot		= $(this).data("bobot");
		var max			= $(this).data("max");
		$("input[name='id_nilai_"+id_kriteria+"']").val(id_nilai);	
		$("input[name='nilai_"+id_kriteria+"']").val(numberWithCommas(parseFloat(nilai).toFixed(2)));	
		$("input[name='nilai_bobot_"+id_kriteria+"']").val(numberWithCommas(parseFloat(nilai*bobot/100).toFixed(2)));	
		$("input[name='nilai_max_"+id_kriteria+"']").val(numberWithCommas(parseFloat(max*bobot/100).toFixed(2)));	
		//hitung total_nilai
		var nilai_1 = $('input[name="nilai_1"]').val();
		var nilai_2 = $('input[name="nilai_2"]').val();
		var nilai_3 = $('input[name="nilai_3"]').val();
		if((nilai_1!='')&&(nilai_2!='')&&(nilai_3!='')){
			var total_nilai	= parseFloat(nilai_1)+parseFloat(nilai_2)+parseFloat(nilai_3);
			$("input[name='total_nilai']").val(parseFloat(total_nilai).toFixed(2));	
		}
		//total nilai bobot
		var nilai_bobot_1 = $('input[name="nilai_bobot_1"]').val();
		var nilai_bobot_2 = $('input[name="nilai_bobot_2"]').val();
		var nilai_bobot_3 = $('input[name="nilai_bobot_3"]').val();
		if((nilai_bobot_1!='')&&(nilai_bobot_2!='')&&(nilai_bobot_3!='')){
			var total_penilaian	= parseFloat(nilai_bobot_1)+parseFloat(nilai_bobot_2)+parseFloat(nilai_bobot_3);
			$("input[name='total_penilaian']").val(parseFloat(total_penilaian).toFixed(2));	
		}
		//hitung total_nilai_max
		var nilai_max_1 = $('input[name="nilai_max_1"]').val();
		var nilai_max_2 = $('input[name="nilai_max_2"]').val();
		var nilai_max_3 = $('input[name="nilai_max_3"]').val();
		if((nilai_max_1!='')&&(nilai_max_2!='')&&(nilai_max_3!='')){
			var total_nilai_max	= parseFloat(nilai_max_1)+parseFloat(nilai_max_2)+parseFloat(nilai_max_3);
			$("input[name='total_nilai_max']").val(parseFloat(total_nilai_max).toFixed(2));	
		}

    });

    //edit, copy dan change  
    $(document).on("click", ".edit", function() {
		
        resetForm_use($('.form-transaksi-vendor'), 'edit');
        var id_data = $(this).data("id_data");
		var action 	= $(this).data("action");
		var action_detail 	= $(this).data("action_detail");
		var pengajuan 	= $(this).data("pengajuan");
		var level 		= $(this).data("level");
		var nama_role 	= $(this).data("nama_role");
		$("input[name='perubahan_data']").val('n');
		$("input[name='approval_legal']").val('n');
		$("input[name='approval_proc']").val('n');
		
        $.ajax({
            // url: baseURL + 'material/transaksi/get/spec',
            url: baseURL + 'vendor/transaksi/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data,
				action: action
            },
            success: function(data) {
                if(action == 'approve'){
					$(".modal-title").html("Approve "+nama_role);
				}
                if(action == 'change'){
					$(".modal-title").html("Change Vendor");
				}
                $.each(data, function(i, v) {
					//tab data vendor
					$("input[name='action']").val(action);
					$("input[name='action_detail']").val(action_detail);
					$("input[name='pengajuan']").val(pengajuan);
					if(action_detail=='approve'){
						$("input[name='id_data_temp']").val(v.id_data_temp_change);
					}
					if(action == 'detail'){
						if(v.id_data_temp_change!=null){
							$("input[name='id_data_temp']").val(v.id_data_temp_change);
						}
						if(v.id_data_temp_delete!=null){
							$("input[name='id_data_temp']").val(v.id_data_temp_delete);
						}
						if(v.id_data_temp_extend!=null){
							$("input[name='id_data_temp']").val(v.id_data_temp_extend);
						}
						if(v.id_data_temp_undelete!=null){
							$("input[name='id_data_temp']").val(v.id_data_temp_undelete);
						}
						
					}  
                    $("#id_data").val(v.id_data);
                    $("#level").val(v.level);
                    $("#I_LIFNR").val(v.lifnr);
                    $("#I_EKORG").val(v.plant);
                    $("#I_BUKRS").val(v.BUKRS);
                    $("#I_KTOKK").val(v.acc_group);
                    $("#id_status").val(v.id_status);
                    $("#id_jenis_vendor_hide").val(v.id_jenis_vendor);
                    $("#kualifikasi_spk_hide").val(v.kualifikasi_spk);
                    $("#arr_kualifikasi_spk_hide").val(v.kualifikasi_spk);
                    $("#nama_hide").val(v.nama);
					if(action == 'change'){
						$("#pengajuan_ho_temp").val(v.pengajuan_ho_temp);
					}else{
						$("#pengajuan_ho").val(v.pengajuan_ho);
					}
					
					if((action == 'approve')||(action == 'change')){  
						$("select[name='plant']").val(v.plant).trigger("change.select2");
					}else{
						$("select[name='plant']").val(v.plant).trigger("change.select2");
						// $("select[name='plant']").val(v.gsber).trigger("change.select2");
					}	
                    $("select[name='acc_group']").val(v.acc_group).trigger("change.select2");
                    // $("select[name='id_jenis_vendor']").val(v.id_jenis_vendor).trigger("change.select2");
					
					//tambahan jika change ambil data dari vendor_temp 
					if(action == 'change'){  
						$.each(v.arr_vendor_temp, function(x, y) { 
							$("input[name='id_data_temp']").val(v.id_data_temp_change);
							$("input[name='nama']").val(y.nama); 
							$("input[name='alamat']").val(y.alamat);
							$("input[name='no']").val(y.no);
							$("input[name='kode_pos']").val(y.kode_pos);
							$("select[name='provinsi']").val(y.provinsi).trigger("change.select2");
							$("input[name='kota']").val(y.kota);
							$("select[name='negara']").val(y.negara).trigger("change.select2");
							$("input[name='npwp']").val(y.npwp);
							$("input[name='ktp']").val(y.ktp);
							$("input[name='approval_legal']").val(y.approval_legal);
							$("input[name='approval_proc']").val(y.approval_proc);
							$("input[name='perubahan_data']").val(y.perubahan_data);
							$("select[name='tax_type']").val(y.tax_type).trigger("change.select2");
							$("select[name='tax_code']").val(y.tax_code).trigger("change.select2");
							$("select[name='tax_code2']").val(y.tax_code2).trigger("change.select2");
							
							$("select[name='id_jenis_vendor_temp']").val(y.id_jenis_vendor).trigger("change");
							if(y.kualifikasi_spk!==null){
								var id_kualifikasi_spk	= y.kualifikasi_spk.split(",");
								$("select[name='kualifikasi_spk_temp[]']").val(id_kualifikasi_spk).trigger("change");
							}
							
						});
						$('#jenis_barang_jasa1').prop('required', false);
						$('#jenis_barang_jasa2').prop('required', false);
						$('#nama_bank').prop('required', false);
						$('#nama_rekening').prop('required', false);
						$('#nomor_rekening').prop('required', false);
						$('#akont').prop('required', false);
						$('#curr').prop('required', false);

						$("select[name='id_jenis_vendor']").val(v.id_jenis_vendor).trigger("change");
					}else{
						$("select[name='id_jenis_vendor']").val(v.id_jenis_vendor).trigger("change");
					}

					
					if(v.kualifikasi_spk!==null){
						var id_kualifikasi_spk	= v.kualifikasi_spk.split(",");
						$("select[name='kualifikasi_spk[]']").val(id_kualifikasi_spk).trigger("change");
					}
					
                    $("select[name='title']").val(v.title).trigger("change.select2");
					
					// $("input[name='jenis_barang_jasa1']").val(v.jenis_barang_jasa1);
					// $("input[name='jenis_barang_jasa2']").val(v.jenis_barang_jasa2);
					
					$("select[name='jenis_barang_jasa1']").val(v.jenis_barang_jasa1).trigger("change.select2");
                    //load jenis_barang_jasa2(term2)
                    var output = '';
                    $.each(v.arr_term2, function(x, y) {
                        var selected = (y.nama == v.jenis_barang_jasa2 ? 'selected' : '');
                        output += '<option value="' + y.nama + '" ' + selected + '>' + y.nama + '</option>';
                    });
                    $("select[name='jenis_barang_jasa2']").html(output).select2();
					
					$("input[name='nama_bank']").val(v.nama_bank);
					$("input[name='nama_rekening']").val(v.nama_rekening);
					$("input[name='nomor_rekening']").val(v.nomor_rekening);
					$("input[name='payment']").val(v.payment);
					$("input[name='npwp']").val(v.npwp);
					$("input[name='ktp']").val(v.ktp);
					//tab data detail
                    $("select[name='industri']").val(v.industri).trigger("change.select2");
                    $("select[name='dlgrp']").val(v.dlgrp).trigger("change.select2");
                    $("select[name='akont']").val(v.akont).trigger("change.select2");
                    $("select[name='zterm']").val(v.zterm).trigger("change.select2");
                    $("select[name='tax_type']").val(v.tax_type).trigger("change.select2");
					// //cek tax_type jika isi maka tax_code wajib isi
					// if((v.tax_type!=null)||(v.tax_type!='')||(v.tax_type!=0)){
						// $('#tax_code').prop('required', true);
					// }else{
						// $('#tax_code').prop('required', false);
					// }
					
                    //load tax code
                    var output = '';
					output += '<option value="0">Pilih Tax Code</option>';
                    $.each(v.arr_tax_code, function(x, y) {
                        var selected = (y.tax_code == v.tax_code ? 'selected' : '');
						output += '<option value="' + y.tax_code + '" ' + selected + '>'+y.tax_code+' - '+ y.tax_code_name + '</option>';
                    });
                    $("select[name='tax_code']").html(output).select2();
                    $("select[name='tax_code']").val(v.tax_code).trigger("change.select2");

                    // $("select[name='tax_code2']").val(v.tax_code2).trigger("change.select2");
                    $("select[name='curr']").val(v.curr).trigger("change.select2");
					$("input[name='schema_grup']").val(v.schema_grup);
					var sales_person = (v.sales_person==" ")?"":v.sales_person;
					$("input[name='sales_person']").val(sales_person);
					var sales_phone = (v.sales_phone==" ")?"":v.sales_phone;
					$("input[name='sales_phone']").val(sales_phone);
					$("input[name='webre']").val(v.webre);
					$("select[name='status_pkp']").val(v.status_pkp).trigger("change.select2");
					$("select[name='status_do']").val(v.status_do).trigger("change.select2");
					$("input[name='deletion_flag']").val(v.deletion_flag);
					//tab alamat
					$("select[name='negara']").val(v.negara).trigger("change.select2");
                    //load provinsi
                    var output = '';
					output += '<option value="0">Pilih Provinsi</option>';
                    $.each(v.arr_provinsi, function(x, y) {
                        var selected = (y.id_provinsi == v.provinsi ? 'selected' : '');
                        output += '<option value="' + y.id_provinsi + '" ' + selected + '>' + y.nama_provinsi + '</option>';
                    });
                    $("select[name='provinsi']").html(output).select2();
						
					//xx
					
					if(v.alamat==" "){
						$("input[name='alamat']").val("");
					}else{
						$("input[name='alamat']").val(v.alamat);
					}
					if(v.no==" "){
						$("input[name='no']").val("");
					}else{
						$("input[name='no']").val(v.no);
					}
					if(v.kota==" "){
						$("input[name='kota']").val("");
					}else{
						$("input[name='kota']").val(v.kota);
					}
					
					$("input[name='kode_pos']").val(v.kode_pos);
					$("input[name='telepon']").val(v.telepon);
					if(v.fax==" "){
						$("input[name='fax']").val("");
					}else{
						$("input[name='fax']").val(v.fax);
					}
					if(v.email==" "){
						$("input[name='email']").val("");
					}else{
						$("input[name='email']").val(v.email);
					}
					
					//tab nilai
					$("input[name='total_nilai']").val(v.total_nilai);
					$("input[name='total_penilaian']").val(v.total_penilaian);
					$("input[name='total_nilai_max']").val(v.total_nilai_max);
                    $.each(v.arr_nilai_detail, function(x, y) {
						$("input[name='id_nilai_"+y.id_kriteria+"']").val(y.id_nilai);
						$("input[name='nilai_"+y.id_kriteria+"']").val(y.nilai);
						$("input[name='nilai_bobot_"+y.id_kriteria+"']").val(y.nilai_bobot);
						$("input[name='nilai_max_"+y.id_kriteria+"']").val(y.nilai_max);
						$('.checked_'+y.id_kriteria+"_"+y.id_nilai).prop('checked', true);
                    });
					//tab additional
                    if (v.add_pilihan == 'y') {
                        $("input[name='add_pilihan']").attr('checked');
                        $("input[name='add_pilihan']").bootstrapToggle('on');
						$('#add_vendor_existing').prop('required', true);
						$('#add_vendor_existing').prop('disabled', false);
						$('#add_alasan').prop('required', true);
						$('#add_alasan').prop('disabled', false);
						$('#add_vendor_flag').prop('required', true);
						$('#add_vendor_flag').prop('disabled', false);
						$("input[name='add_vendor_existing']").val(v.add_vendor_existing);
						$("select[name='add_alasan']").val(v.add_alasan).trigger("change.select2");
						$("input[name='add_vendor_flag']").val(v.add_vendor_flag);
                    } else {
                        $("input[name='add_pilihan']").removeAttr('checked');
                        $("input[name='add_pilihan']").bootstrapToggle('off');
						$('#add_vendor_existing').prop('required', false);
						$('#add_vendor_existing').prop('disabled', true);
						$('#add_alasan').prop('required', false);
						$('#add_alasan').prop('disabled', true);
						$('#add_vendor_flag').prop('required', false);
						$('#add_vendor_flag').prop('disabled', true);
						$("input[name='add_vendor_existing']").val('');
						$("select[name='add_alasan']").val('').trigger("change.select2");
						$("input[name='add_vendor_flag']").val('');
                    }
					
					//
					if(v.acc_group=='ONVE'){
						$('#kota').prop('required', true);
					}
					if(v.acc_group!='ONVE'){
						$('#kota').prop('required', false);
					}
					//tambahan
					if(v.arr_vendor_temp!=''){
						$.each(v.arr_vendor_temp, function(x, y) { 
							// if(y.pengajuan_ho=='n'){
								// $("select[name='plant']").val(y.plant).trigger("change.select2");
							// }else{
								// $("select[name='plant']").val(v.gsber).trigger("change.select2");
							// }
							$("input[name='nama']").val(y.nama);
							$("input[name='ktp']").val(y.ktp);
							$("input[name='npwp']").val(y.npwp);
							$("select[name='tax_type']").val(y.tax_type).trigger("change.select2");
							$("select[name='tax_code']").val(y.tax_code).trigger("change.select2");
							$("select[name='negara']").val(y.negara).trigger("change.select2");
							$("select[name='provinsi']").val(y.provinsi).trigger("change.select2");
							$("input[name='kota']").val(y.kota);
							$("input[name='alamat']").val(y.alamat);
							$("input[name='no']").val(y.no);
							$("input[name='kode_pos']").val(y.kode_pos);
							//xx
						});
					}else{
						// $("select[name='plant']").val(v.gsber).trigger("change.select2");
					
						$("input[name='nama']").val(v.nama);
						$("input[name='ktp']").val(v.ktp);
						$("input[name='npwp']").val(v.npwp);
						$("select[name='tax_type']").val(v.tax_type).trigger("change.select2");
						$("select[name='tax_code']").val(v.tax_code).trigger("change.select2");
						$("select[name='negara']").val(v.negara).trigger("change.select2");
						$("select[name='provinsi']").val(v.provinsi).trigger("change.select2");
						$("input[name='kota']").val(v.kota);
						$("input[name='alamat']").val(v.alamat);
						$("input[name='no']").val(v.no);
						$("input[name='kode_pos']").val(v.kode_pos);
						
					}
					// //tab change
					// var tab_change	= '';
						// $.each(v.arr_vendor_temp, function(i,v2) {
							// if(v2.title!=v.title){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Title (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.title+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Title (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.title+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.nama!=v.nama){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Nama Vendor (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.nama+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Nama Vendor (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.nama+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.ktp!=v.ktp){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>KTP (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.ktp+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>KTP (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.ktp+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.npwp!=v.npwp){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>NPWP (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.npwp+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>NPWP (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.npwp+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.tax_type_name!=v.tax_type_name){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>WH Tax Type (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.tax_type_name+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>WH Tax Type (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.tax_type_name+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.tax_code_name!=v.tax_code_name){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>WH Tax Code (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.tax_code_name+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>WH Tax Code (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.tax_code_name+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.nama_negara!=v.nama_negara){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Negara (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.nama_negara+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Negara (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.nama_negara+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.nama_provinsi!=v.nama_provinsi){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Provinsi (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.nama_provinsi+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Provinsi (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.nama_provinsi+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.kota!=v.kota){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Kota (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.kota+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Kota (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.kota+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.alamat!=v.alamat){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Alamat (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.alamat+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Alamat (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.alamat+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.no!=v.no){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Nomor Rumah (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.no+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Nomor Rumah (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.no+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
							// if(v2.kode_pos!=v.kode_pos){
								// tab_change	+= '<div class="form-group">';
								// tab_change	+= '<div class="row">';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Kode Pos (As Is)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v.kode_pos+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '	<div class="col-xs-6">';
								// tab_change	+= '		<label>Kode Pos (To Be)</label>';
								// tab_change	+= '		<input type="text" class="form-control" value="'+v2.kode_pos+'" readonly>';
								// tab_change	+= '	</div>';
								// tab_change	+= '</div>';
								// tab_change	+= '</div>';
							// }
						// });
						
						// $("#show_tab_change").html(tab_change);
					//tab change
					var tab_change	= '';
						$.each(v.arr_vendor_temp, function(i,v2) {
							if(v2.approval_proc=='y'){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-12">';
								tab_change	+= '		<label>* Ada perubahan dokumen Jenis Vendor</label>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
								
							}
							if(v2.approval_legal=='y'){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-12">';
								tab_change	+= '		<label>* Ada perubahan dokumen Kualaifikasi Vendor</label>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
								
							}
							if(v2.title!=v2.title_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>Title</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.title+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.title_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.nama!=v2.nama_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>Nama Vendor</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.nama+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.nama_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.ktp!=v2.ktp_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>KTP</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.ktp+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.ktp_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.npwp!=v2.npwp_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>NPWP</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.npwp+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.npwp_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.tax_type_name!=v2.tax_type_name_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>WH Tax Type</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.tax_type_name+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.tax_type_name_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.tax_code_name!=v2.tax_code_name_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>WH Tax Code</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.tax_code_name+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.tax_code_name_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.negara!=v2.negara_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>Negara</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.nama_negara+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.nama_negara_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.nama_provinsi!=v2.nama_provinsi_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>Provinsi</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.nama_provinsi+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.nama_provinsi_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.kota!=v2.kota_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>Kota</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.kota+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.kota_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.alamat!=v2.alamat_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>Alamat</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.alamat+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.alamat_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.no!=v2.no_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>Nomor Rumah</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.no+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.no_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
							if(v2.kode_pos!=v2.kode_pos_new){
								tab_change	+= '<div class="form-group">';
								tab_change	+= '<div class="row">';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>Kode Pos</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.kode_pos+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-1" align="center">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<div><i class="fa fa-arrow-circle-right"></i></div>';
								tab_change	+= '	</div>';
								tab_change	+= '	<div class="col-xs-5">';
								tab_change	+= '		<label>&nbsp;</label>';
								tab_change	+= '		<input type="text" class="form-control" value="'+v2.kode_pos_new+'" readonly>';
								tab_change	+= '	</div>';
								tab_change	+= '</div>';
								tab_change	+= '</div>';
							}
						});
						
						$("#show_tab_change").html(tab_change);					

                });
            },
            complete: function() {
				$('.form-control-hide').prop('disabled', true);
				$("#btn_save").hide();
				$("#btn_change").hide();
				$("#btn_approve_change").hide();
				$("#btn_decline_change").hide();
				// $("#btn_change_sap").hide();
				$("#btn_approve").hide();
				$("#btn_decline").hide();
				$("#btn_approve_sap").hide();
                if (action == 'approve') {
					$('.form-control').prop('required', false);
					$('#form-control-hide').prop('required', false);
					$("#btn_decline").show();
					// if(level==6){
						// $("#btn_approve_sap").show();
					// }else{
						// $("#btn_approve").show();
					// }
					$("#btn_approve").show();
					$('#id_jenis_vendor').prop('disabled', true);
					$('#id_jenis_vendor').prop('required', false);
					$('#kualifikasi_spk').prop('disabled', true);
					$('#kualifikasi_spk').prop('required', false);
					
                }else if (action == 'change') {
					//form yang bisa di change
					$('#title').prop('disabled', false);
					$('#title').prop('required', true);
					$('#nama').prop('disabled', false);
					$('#nama').prop('required', true);
					$('#negara').prop('disabled', false);
					$('#negara').prop('required', true);
					$('#provinsi').prop('disabled', false);
					$('#provinsi').prop('required', false);
					$('#kota').prop('disabled', false);
					$('#kota').prop('required', false);
					$('#alamat').prop('disabled', false);
					$('#alamat').prop('required', false);
					$('#no').prop('disabled', false);
					$('#no').prop('required', false);
					$('#kode_pos').prop('disabled', false);
					$('#kode_pos').prop('required', false);
					$('#ktp').prop('disabled', false);
					$('#ktp').prop('required', false);
					$('#npwp').prop('disabled', false);
					$('#npwp').prop('required', false);
					$('#tax_type').prop('disabled', false);
					$('#tax_type').prop('required', false);
					$('#tax_code').prop('disabled', false);
					$('#tax_code').prop('required', false);
					$('#tax_code2').prop('disabled', false);
					$('#tax_code2').prop('required', false);
					$('#id_jenis_vendor').prop('disabled', false);
					$('#id_jenis_vendor').prop('required', true);
					$('#kualifikasi_spk').prop('disabled', false);
					$('#kualifikasi_spk').prop('required', false);
					
					if((level==3)||(level==4)||(level==5)){
						// $("#btn_change_sap").show();
						$("#btn_approve_change").show();
						$("#btn_decline_change").show();
						
						$('#id_jenis_vendor').prop('disabled', true);
						$('#id_jenis_vendor').prop('required', false);
						$('#kualifikasi_spk').prop('disabled', true);
						$('#kualifikasi_spk').prop('required', false);
						
						
					}else{
						$("#btn_change").show();
					}
                }else if(action == 'detail'){
					$("#btn_approve").hide();
					$('#id_jenis_vendor').prop('disabled', true);
					$('#id_jenis_vendor').prop('required', false);
					$('#kualifikasi_spk').prop('disabled', true);
					$('#kualifikasi_spk').prop('required', false);
					
				}else{
					$('.form-control-hide').prop('disabled', false);
					$("#btn_save").show();
					$('#id_jenis_vendor').prop('disabled', false);
					$('#kualifikasi_spk').prop('disabled', false);
					
				}
				if (action_detail == 'approve') {
					$('.form-control-hide').prop('disabled', true);
					
				}
				
				// //untuk show hide tab komentar
				if ((action == 'approve')||(action_detail == 'approve')) {
					$(".form-control_komentar").css({'visibility' : 'visible'});
				}else{
					$(".form-control_komentar").css({'visibility' : 'hidden'});
				}
				// //untuk show hide tab data change
				if ((action == 'change')&&(action_detail == 'approve')) {
					$(".form-control_change").css({'visibility' : 'visible'});
				}else{
					$(".form-control_change").css({'visibility' : 'hidden'});
				}
				
                $('#add_modal').modal('show');
				$('#id_jenis_vendor_temp').prop('disabled', true);
				$('#id_jenis_vendor_temp').prop('required', false);
				$('#kualifikasi_spk_temp').prop('disabled', true);
				$('#kualifikasi_spk_temp').prop('required', false);

            }

        });
    });
    //history
    $(document).on("click", ".history", function() {
        var id_data = $(this).data("id_data");
        $.ajax({
			url: baseURL + 'vendor/transaksi/get/data/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    // console.log(data);
					$("input[name='id_data']").val(v.id_data);
					$("input[name='lifnr']").val(v.lifnr);
					$("input[name='nama']").val(v.nama);
					//
					var det_pengajuan	= "";
						det_pengajuan	+= 		'<table class="table table-bordered datatable-vendor_pengajuan">';
						det_pengajuan	+= 		'	<thead>';
						det_pengajuan	+= 		'		<tr>';
						det_pengajuan	+= 		'			<th>Nama Vendor</th>';
						det_pengajuan	+= 		'			<th>Tanggal Status</th>';
						det_pengajuan	+= 		'			<th>Status</th>';
						det_pengajuan	+= 		'			<th>Comment</th>';
						det_pengajuan	+= 		'		</tr>';
						det_pengajuan	+= 		'	</thead>';
						det_pengajuan	+= 		'	<tbody>';
						$.each(v.arr_history, function(i,v2) {	
							let komentar = (v2.komentar==null)?'-':v2.komentar;
							det_pengajuan	+= 		'		<tr>';
							det_pengajuan	+= 		'			<td><b>'+v2.kode_vendor+'</b><br>'+v2.nama_vendor+'</td>';
							det_pengajuan	+= 		'			<td>'+v2.tanggal_buat+' '+v2.jam_buat+'</td>';
							det_pengajuan	+= 		'			<td>'+v2.nama_status+' Oleh:<br>'+v2.nama_karyawan+' - '+v2.nik+'<br><span class="label label-info">'+v2.nama_role+'</span></td>';
							det_pengajuan	+= 		'			<td>'+komentar+'</td>';
							det_pengajuan	+= 		'		</tr>';
						});
						det_pengajuan	+= 		'	</tbody>';
						det_pengajuan	+= 		'</table>';
						$("#histori_pengajuan").html(det_pengajuan);
						
					var det_extend	= "";
						det_extend	+= 		'<table class="table table-bordered datatable-vendor_extend">';
						det_extend	+= 		'	<thead>';
						det_extend	+= 		'		<tr>';
						det_extend	+= 		'			<th>Nama Vendor</th>';
						det_extend	+= 		'			<th>Extend Plant</th>';
						det_extend	+= 		'			<th>Tanggal Status</th>';
						det_extend	+= 		'			<th>Status</th>';
						det_extend	+= 		'			<th>Comment</th>';
						det_extend	+= 		'		</tr>';
						det_extend	+= 		'	</thead>';
						det_extend	+= 		'	<tbody>';
						$.each(v.arr_history_extend, function(i,v2) {	
							let komentar = (v2.komentar==null)?'-':v2.komentar;
							var list_plant 	= ''; 
							var arr_plant	= v2.list_plant_extend.slice(0, -1).split(",");
							$.each(arr_plant, function(x, y){
								list_plant += "<button class='btn btn-sm btn-success btn-role'>"+y+"</button>";
							});
						
							det_extend	+= 		'		<tr>';
							det_extend	+= 		'			<td><b>'+v.lifnr+'</b><br> '+v.nama+'</td>';
							det_extend	+= 		'			<td>'+list_plant+'</td>';
							det_extend	+= 		'			<td>'+v2.tanggal_buat+' '+v2.jam_buat+'</td>';
							det_extend	+= 		'			<td>'+v2.nama_status+' Oleh:<br>'+v2.nama_karyawan+' - '+v2.nik+'<br><span class="label label-info">'+v2.nama_role+'</span></td>';
							det_extend	+= 		'			<td>'+komentar+'</td>';
							det_extend	+= 		'		</tr>';
						});
						det_extend	+= 		'	</tbody>';
						det_extend	+= 		'</table>';
						$("#histori_extend").html(det_extend);
						
					var det_delete	= "";
						det_delete	+= 		'<table class="table table-bordered datatable-vendor_delete">';
						det_delete	+= 		'	<thead>';
						det_delete	+= 		'		<tr>';
						det_delete	+= 		'			<th>Nama Vendor</th>';
						det_delete	+= 		'			<th>Delete Plant</th>';
						det_delete	+= 		'			<th>Tanggal Status</th>';
						det_delete	+= 		'			<th>Status</th>';
						det_delete	+= 		'			<th>Comment</th>';
						det_delete	+= 		'		</tr>';
						det_delete	+= 		'	</thead>';
						det_delete	+= 		'	<tbody>';
						$.each(v.arr_history_delete, function(i,v2) {	
							let komentar = (v2.komentar==null)?'-':v2.komentar;
							var list_plant 	= '';
							if(v2.list_plant_delete!=null){
								var arr_plant	= v2.list_plant_delete.slice(0, -1).split(",");
								$.each(arr_plant, function(x, y){
									list_plant += "<button class='btn btn-sm btn-success btn-role'>"+y+"</button>";
								});
							}	
						
							det_delete	+= 		'		<tr>';
							det_delete	+= 		'			<td><b>'+v.lifnr+'</b><br> '+v.nama+'</td>';
							det_delete	+= 		'			<td>'+list_plant+'</td>';
							det_delete	+= 		'			<td>'+v2.tanggal_buat+' '+v2.jam_buat+'</td>';
							// det_delete	+= 		'			<td>'+v2.nama_status+' Oleh:<br><span class="label label-info">'+v2.nama_karyawan+' - '+v2.nik+'</span></td>';
							det_delete	+= 		'			<td>'+v2.nama_status+' Oleh:<br>'+v2.nama_karyawan+' - '+v2.nik+'<br><span class="label label-info">'+v2.nama_role+'</span></td>';
							det_delete	+= 		'			<td>'+komentar+'</td>';
							det_delete	+= 		'		</tr>';
						});
						det_delete	+= 		'	</tbody>';
						det_delete	+= 		'</table>';
						$("#histori_delete").html(det_delete);

					var det_undelete	= "";
						det_undelete	+= 		'<table class="table table-bordered datatable-vendor_undelete">';
						det_undelete	+= 		'	<thead>';
						det_undelete	+= 		'		<tr>';
						det_undelete	+= 		'			<th>Nama Vendor</th>';
						det_undelete	+= 		'			<th>Undelete Plant</th>';
						det_undelete	+= 		'			<th>Tanggal Status</th>';
						det_undelete	+= 		'			<th>Status</th>';
						det_undelete	+= 		'			<th>Comment</th>';
						det_undelete	+= 		'		</tr>';
						det_undelete	+= 		'	</thead>';
						det_undelete	+= 		'	<tbody>';
						$.each(v.arr_history_undelete, function(i,v2) {	
							let komentar = (v2.komentar==null)?'-':v2.komentar;
							var list_plant 	= ''; 
							if(v2.list_plant_undelete!=null){
								var arr_plant	= v2.list_plant_undelete.slice(0, -1).split(",");
								$.each(arr_plant, function(x, y){
									list_plant += "<button class='btn btn-sm btn-success btn-role'>"+y+"</button>";
								});
							}
							det_undelete	+= 		'		<tr>';
							det_undelete	+= 		'			<td><b>'+v.lifnr+'</b><br> '+v.nama+'</td>';
							det_undelete	+= 		'			<td>'+list_plant+'</td>';
							det_undelete	+= 		'			<td>'+v2.tanggal_buat+' '+v2.jam_buat+'</td>';
							det_undelete	+= 		'			<td>'+v2.nama_status+' Oleh:<br>'+v2.nama_karyawan+' - '+v2.nik+'<br><span class="label label-info">'+v2.nama_role+'</span></td>';
							det_undelete	+= 		'			<td>'+komentar+'</td>';
							det_undelete	+= 		'		</tr>';
						});
						det_undelete	+= 		'	</tbody>';
						det_undelete	+= 		'</table>';
						$("#histori_undelete").html(det_undelete);
						
					var det_change	= "";
						det_change	+= 		'<table class="table table-bordered datatable-vendor">';
						det_change	+= 		'	<thead>';
						det_change	+= 		'		<tr>';
						det_change	+= 		'			<th>Nama Vendor</th>';
						det_change	+= 		'			<th>Change Vendor</th>';
						det_change	+= 		'			<th>Tanggal Status</th>';
						det_change	+= 		'			<th>Status</th>';
						det_change	+= 		'			<th>Comment</th>';
						det_change	+= 		'		</tr>';
						det_change	+= 		'	</thead>';
						det_change	+= 		'	<tbody>';
						$.each(v.arr_history_change, function(i,v2) {	
							let komentar = (v2.komentar==null)?'-':v2.komentar;
							det_change	+= 		'		<tr>';
							det_change	+= 		'			<td><b>'+v.lifnr+'</b><br> '+v.nama+'</td>';
							// det_change	+= 		'			<td>'+v2.change_title+''+v2.change_nama+''+v2.change_ktp+''+v2.change_npwp+''+v2.change_tax_type+''+v2.change_tax_code+''+v2.change_negara+''+v2.change_provinsi+''+v2.change_kota+''+v2.change_alamat+''+v2.change_nomor_rumah+''+v2.change_kode_pos+'</td>';
							det_change	+= 		'			<td>';
							det_change	+= 						v2.change_dokumen_jenis;
							det_change	+= 						v2.change_dokumen_kualifikasi;
							det_change	+= 						v2.change_title;
							det_change	+= 						v2.change_nama;
							det_change	+= 						v2.change_ktp;
							det_change	+= 						v2.change_npwp;
							det_change	+= 						v2.change_tax_type;
							det_change	+= 						v2.change_tax_code;
							det_change	+= 						v2.change_negara;
							det_change	+= 						v2.change_provinsi;
							det_change	+= 						v2.change_kota;
							det_change	+= 						v2.change_alamat;
							det_change	+= 						v2.change_nomor_rumah;
							det_change	+= 						v2.change_kode_pos;
							det_change	+= 		'			</td>';
							det_change	+= 		'			<td>'+v2.tanggal_buat+' '+v2.jam_buat+'</td>';
							det_change	+= 		'			<td>'+v2.nama_status+' Oleh:<br>'+v2.nama_karyawan+' - '+v2.nik+'<br><span class="label label-info">'+v2.nama_role+'</span></td>';
							det_change	+= 		'			<td>'+komentar+'</td>';
							det_change	+= 		'		</tr>';
						});
						det_change	+= 		'	</tbody>';
						det_change	+= 		'</table>';
						$("#histori_change").html(det_change);						
						
					
                });
            },
            complete: function() {
				setTimeout(function () {
					$("table.datatable-vendor_pengajuan").DataTable({
						"bLengthChange": false,
						order: [[1, 'desc']]
					}).columns.adjust();
				}, 1500);				
				setTimeout(function () {
					$("table.datatable-vendor_extend").DataTable({
						"bLengthChange": false,
						order: [[2, 'desc']]
					}).columns.adjust();
				}, 1500);				
				setTimeout(function () {
					$("table.datatable-vendor_change").DataTable({
						"bLengthChange": false,
						order: [[0, 'desc']]
					}).columns.adjust();
				}, 1500);				
				setTimeout(function () {
					$("table.datatable-vendor_delete").DataTable({
						"bLengthChange": false,
						order: [[2, 'desc']]
					}).columns.adjust();
				}, 1500);				
				setTimeout(function () {
					$("table.datatable-vendor_undelete").DataTable({
						"bLengthChange": false,
						order: [[2, 'desc']]
					}).columns.adjust();
				}, 1500);				
                $('#modal-history').modal('show');
            }
        });
    });


    //extend
    $(document).on("click", ".extend", function() {
        var id_data = $(this).data("id_data");
		var action 	= $(this).data("action");
		var pengajuan 	= $(this).data("pengajuan");
        $.ajax({
            // url: baseURL + 'material/transaksi/get/spec',
			url: baseURL + 'vendor/transaksi/get/data/extend',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='id_data']").val(v.id_data);
					$("input[name='id_data_temp']").val(v.id_data_temp_extend);
					$("input[name='level']").val(v.level);
					$("input[name='pengajuan']").val(pengajuan);
					$("input[name='pengajuan_ho']").val(v.pengajuan_ho_temp);
					$("input[name='nama']").val(v.nama);
					$("input[name='nama_hide']").val(v.nama);
					$("input[name='plant_extend_hide']").val(v.list_plant_extend);
					$("input[name='lifnr']").val(v.lifnr);
					$("input[name='I_LIFNR']").val(v.lifnr);
					$("input[name='I_EKORG_REF']").val(v.plant);
					$("input[name='I_BUKRS_REF']").val(v.BUKRS);
					$("input[name='I_KTOKK']").val(v.acc_group);
					
                    //load plant as is
                    var output = '';
                    $.each(v.arr_plant_asis, function(x, y) {
                        output += '<option value="' + y.plant + '" selected>' + y.plant + '</option>';
                    });
                    $("select[name='plant_asis[]']").html(output).select2();
					
					
                    //load plant edit
					if (action == 'approve') {
						var output = '';
						$.each(v.arr_plant_edit, function(x, y) {
							output += '<option value="' + y.plant + '" selected>' + y.plant + '</option>';
						});
						$("select[name='plant_extend[]']").html(output).select2();
					}else{
						var output = '';
						$.each(v.arr_plant_extend, function(x, y) {
							output += '<option value="' + y.plant + '">' + y.plant + '</option>';
						});
						$("select[name='plant_extend[]']").html(output).select2();
					}
					if(v.list_plant_extend!=null){
						var plant_extend	= v.list_plant_extend.split(",");
						$("select[name='plant_extend[]']").val(plant_extend).trigger("change");
					}
					//tab additional
					$('#add_vendor_existing_extend').prop('required', false);
					$('#add_vendor_existing_extend').prop('disabled', true);
					$('#plant_asis').prop('disabled', true);
					$('#add_alasan_extend').prop('disabled', true);
					$('#add_vendor_flag_extend').prop('disabled', true);
					
					$.each(v.arr_vendor_temp, function(x, y) { 
						if (y.add_pilihan == 'y') {
							$("input[name='add_pilihan_extend']").attr('checked');
							$("input[name='add_pilihan_extend']").bootstrapToggle('on');
							$('#add_vendor_existing_extend').prop('required', true);
							$('#add_vendor_existing_extend').prop('disabled', false);
							$('#add_alasan_extend').prop('required', true);
							$('#add_alasan_extend').prop('disabled', false);
							$('#add_vendor_flag_extend').prop('required', true);
							$('#add_vendor_flag_extend').prop('disabled', false);
							$("input[name='add_vendor_existing_extend']").val(y.add_vendor_existing);
							$("select[name='add_alasan_extend']").val(y.add_alasan).trigger("change.select2");
							$("input[name='add_vendor_flag_extend']").val(y.add_vendor_flag);
						} else {
							$("input[name='add_pilihan_extend']").removeAttr('checked');
							$("input[name='add_pilihan_extend']").bootstrapToggle('off');
							$('#add_vendor_existing_extend').prop('required', false);
							$('#add_vendor_existing_extend').prop('disabled', true);
							$('#add_alasan_extend').prop('required', false);
							$('#add_alasan_extend').prop('disabled', true);
							$('#add_vendor_flag_extend').prop('required', false);
							$('#add_vendor_flag_extend').prop('disabled', true);
							$("input[name='add_vendor_existing_extend']").val('');
							$("select[name='add_alasan_extend']").val('').trigger("change.select2");
							$("input[name='add_vendor_flag_extend']").val('');
						}
					});
					

                });
            },
            complete: function() {
				$("#btn_save_extend").hide();
				$("#btn_save_extend_sap").hide();
				$("#btn_decline_extend").hide();
				
                if (action == 'approve') {
					$('.form-control-hide').prop('disabled', true);
					$('#ck_all').prop('disabled', true);
					$('#plant_extend').prop('required', false);
					$('#plant_extend').prop('disabled', true);
					$("#btn_save_extend_sap").show();
					$("#btn_decline_extend").show();
                }else{
					// $('.form-control-hide').prop('disabled', false);
					$('#ck_all').prop('disabled', false);
					$('#plant_extend').prop('required', true);
					$('#plant_extend').prop('disabled', false);
					$("#btn_save_extend").show();
				}
				//untuk show hide tab komentar
				if ((action == 'approve')||(action_detail == 'approve')) {
					$(".form-control_komentar").css({'visibility' : 'visible'});
				}else{
					$(".form-control_komentar").css({'visibility' : 'hidden'});
				}
				
                $('#add_extend').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_extend')
                });
            }
        });
    });
    //delete
    $(document).on("click", ".delete", function() {
        var id_data = $(this).data("id_data");
		var action 	= $(this).data("action");
        $.ajax({
            // url: baseURL + 'material/transaksi/get/spec',
			url: baseURL + 'vendor/transaksi/get/data/delete',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='id_data']").val(v.id_data);
					$("input[name='id_data_temp']").val(v.id_data_temp_delete);
					$("input[name='level']").val(v.level);
					$("input[name='I_LIFNR']").val(v.lifnr);
					$("input[name='I_EKORG']").val(v.plant);
					$("input[name='I_BUKRS']").val(v.BUKRS);
					$("input[name='nama']").val(v.nama);
					$("input[name='lifnr']").val(v.lifnr);
					$("input[name='plant_delete_hide']").val(v.list_plant_temp_delete);
					$.each(v.arr_vendor_temp, function(x, y) { 
						$("select[name='alasan_delete']").val(y.alasan_delete).trigger("change.select2");
						$("input[name='alasan_delete_detail']").val(y.alasan_delete_detail);
						// var alasan_delete = y.alasan_delete;
						// if((alasan_delete=='Ganti Vendor')||(alasan_delete=='Double Vendor')||(alasan_delete=='Perubahan Legalitas')){
							// output = '<div class="form-group">';
							// output += '<label for="description">Kode Vendor</label>';
							// output += '<input type="text" class="form-control form-control-hide" name="alasan_delete_detail" id="alasan_delete_detail" placeholder="Kode Vendor" value=".++.y.alasan_delete_detail">';
							// output += '</div>';
							// $("#show_alasan_detail").html(output);			
							// $('#alasan_delete_detail').prop('required', true);
						// }else{
							// output = '';
							// $("#show_alasan_detail").html(output);			
							// $('#alasan_delete_detail').prop('required', false);
						// }
						
						
					});
					
					// if(v.list_plant_temp_delete!==null){
						// var plant_delete	= v.list_plant_temp_delete.split(",");
						// $("select[name='plant_delete[]']").val(plant_delete).trigger("change");
					// }
					// var arr_plant_delete	= v.list_plant_temp_delete.slice(0, -1).split(",");
					// var my_array_plant_del 	= [];
					// $.each(arr_plant_delete, function(x, y){
						// my_array_plant_del.push(y);
					// });
					
					if(v.ho=='y'){
						var arr_plant	= v.list_plant.slice(0, -1).split(",");
						var output = '';
						$.each(arr_plant, function(x, y){
							var dt = y.split("|");
							if(dt[1]=='n'){	//status delete
								output += '<option value="' + dt[0] + '">' + dt[0] + '</option>';
							}
						});
						$("select[name='plant_delete[]']").html(output).select2();
					}else{
						var	output = '<option value="' + v.gsber + '">' + v.gsber + '</option>';
						$("select[name='plant_delete[]']").html(output).select2();
					}
					if(v.list_plant_temp_delete!=null){
						var plant_temp_delete	= v.list_plant_temp_delete.split(",");
						$("select[name='plant_delete[]']").val(plant_temp_delete).trigger("change");
					}
                    // //load plant delete
					// var output = '';
					// $.each(v.arr_plant_temp, function(x, y) {
						// output += '<option value="' + y.plant + '" selected>' + y.plant + '</option>';
					// });
					// $("select[name='plant_delete[]']").html(output).select2();
					
					// if(v.id_status_delete==4){
						// var output = '';
						// $.each(v.arr_plant_temp, function(x, y) {
							// output += '<option value="' + y.plant + '" selected>' + y.plant + '</option>';
						// });
						// $("select[name='plant_delete[]']").html(output).select2();
					// }else{
						// var output = '';
						// $.each(v.arr_plant_asis, function(x, y) {
							// output += '<option value="' + y.plant + '">' + y.plant + '</option>';
						// });
						// $("select[name='plant_delete[]']").html(output).select2();
					// }

                });
            },
            complete: function() {
				$("#btn_save_delete").hide();
				$("#btn_decline_delete").hide();
				$("#btn_save_delete_sap").hide();
				$('.form-control-hide').prop('disabled', false);
				$('.alasan_delete_detail').prop('required', false);
				$('.alasan_delete_detail').prop('disabled', true);
				
                if (action == 'approve') {
					$('.form-control-hide').prop('required', false);
					$('.form-control-hide').prop('disabled', true);
					
					$('#ck_all_delete').prop('disabled', true);
					$('#plant_delete').prop('required', false);
					$('#plant_delete').prop('disabled', true);
					$("#btn_decline_delete").show();
					$("#btn_save_delete_sap").show();
					$('.form-control_komentar').show();
                }else{
					$('#ck_all_delete').prop('disabled', false);
					$('#plant_delete').prop('required', true);
					$('#plant_delete').prop('disabled', false);
					$("#btn_save_delete").show();
					$('.form-control_komentar').hide();
				}
                $('#add_delete').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_extend')
                });
            }
        });
    });
    //undelete
    $(document).on("click", ".undelete", function() {
        var id_data = $(this).data("id_data");
		var action 	= $(this).data("action");
        $.ajax({
            // url: baseURL + 'material/transaksi/get/spec',
			url: baseURL + 'vendor/transaksi/get/data/undelete',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='id_data']").val(v.id_data);
					$("input[name='id_data_temp']").val(v.id_data_temp_undelete);
					$("input[name='level']").val(v.level);
					$("input[name='I_LIFNR']").val(v.lifnr);
					$("input[name='I_EKORG']").val(v.plant);
					$("input[name='I_BUKRS']").val(v.BUKRS);
					$("input[name='id_status_undelete']").val(v.id_status_undelete);
					$("input[name='plant_undelete_hide']").val(v.list_plant_temp_undelete);
					
					$("input[name='nama']").val(v.nama);
					$("input[name='alasan_undelete']").val(v.alasan_undelete);
					$("input[name='lifnr']").val(v.lifnr);
					$.each(v.arr_vendor_temp, function(x, y) { 
						$("input[name='alasan_undelete']").val(y.alasan_undelete);
					});
					
					
					
					// if(v.list_plant_temp_undelete!==null){
						// var plant_undelete	= v.list_plant_temp_undelete.split(",");
						// $("select[name='plant_undelete[]']").val(plant_undelete).trigger("change");
					// }
					if(v.ho=='y'){
						var arr_plant	= v.list_plant.slice(0, -1).split(",");
						var output = '';
						$.each(arr_plant, function(x, y){
							var dt = y.split("|");
							if(dt[1]=='y'){	//status delete
								output += '<option value="' + dt[0] + '" >' + dt[0] + '</option>';
							}
						});
						$("select[name='plant_undelete[]']").html(output).select2();
					}else{
						var	output = '<option value="' + v.gsber + '" >' + v.gsber + '</option>';
						$("select[name='plant_undelete[]']").html(output).select2();
					}
					if(v.list_plant_temp_undelete!=null){
						var plant_temp_undelete	= v.list_plant_temp_undelete.split(",");
						$("select[name='plant_undelete[]']").val(plant_temp_undelete).trigger("change");
					}
					
                    // //load plant delete
					// if(v.id_status_undelete==4){
						// var output = '';
						// $.each(v.arr_plant_temp, function(x, y) {
							// output += '<option value="' + y.plant + '" selected>' + y.plant + '</option>';
						// });
						// $("select[name='plant_undelete[]']").html(output).select2();
					// }else{
						// var output = '';
						// $.each(v.arr_plant_asis, function(x, y) {
							// output += '<option value="' + y.plant + '">' + y.plant + '</option>';
						// });
						// $("select[name='plant_undelete[]']").html(output).select2();
					// }

                });
            },
            complete: function() {
				$("#btn_save_undelete").hide();
				$("#btn_decline_undelete").hide();
				$("#btn_save_undelete_sap").hide();
				
                if (action == 'approve') {
					$('#ck_all_undelete').prop('disabled', true);
					$('#plant_undelete').prop('required', false);
					$('#plant_undelete').prop('disabled', true);
					$('#alasan_undelete').prop('required', false);
					$('#alasan_undelete').prop('disabled', true);
					$("#btn_decline_undelete").show();
					$("#btn_save_undelete_sap").show();
					$('.form-control_komentar').show();
					
                }else{
					$('#ck_all_undelete').prop('disabled', false);
					$('#plant_undelete').prop('required', true);
					$('#plant_undelete').prop('disabled', false);
					$('#alasan_undelete').prop('required', true);
					$('#alasan_undelete').prop('disabled', false);
					$("#btn_save_undelete").show();
					$('.form-control_komentar').hide();
				}
                $('#add_undelete').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_extend')
                });
            }
        });
    });
    //change
    $(document).on("click", ".change", function() {
        var id_data = $(this).data("id_data");
		var action 	= $(this).data("action");
        $.ajax({
            // url: baseURL + 'material/transaksi/get/spec',
			url: baseURL + 'vendor/transaksi/get/data/change',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    // $("#id_data").val(v.id_data);
                    // $("#level").val(v.level);
                    // $("#I_LIFNR").val(v.lifnr);
                    // $("#I_EKORG").val(v.plant);
                    // $("#I_BUKRS").val(v.BUKRS);
                    // $("#I_KTOKK").val(v.acc_group);
                    // $("#id_status").val(v.id_status);
                    // $("#id_jenis_vendor_hide").val(v.id_jenis_vendor);
                    // $("#kualifikasi_spk_hide").val(v.kualifikasi_spk);
                    // $("#nama_hide").val(v.nama);
                    // $("#pengajuan_ho").val(v.pengajuan_ho);
					
					//
					$("input[name='id_data']").val(v.id_data);
					$("input[name='level']").val(v.level);
					$("input[name='I_LIFNR']").val(v.lifnr);
					$("input[name='I_EKORG']").val(v.plant);
					$("input[name='I_BUKRS']").val(v.BUKRS);
					$("input[name='I_KTOKK']").val(v.acc_group);
					$("input[name='id_status']").val(v.id_status);
					$("input[name='id_jenis_vendor_hide']").val(v.id_jenis_vendor);
					$("input[name='kualifikasi_spk_hide']").val(v.kualifikasi_spk);
					$("input[name='nama_hide']").val(v.nama);
					$("input[name='pengajuan_ho']").val(v.pengajuan_ho);
					
					$("input[name='nama']").val(v.nama);
					$("input[name='ktp']").val(v.ktp);
					$("input[name='ktp_awal']").val(v.ktp);
					$("input[name='nama_awal']").val(v.nama);
					$("input[name='npwp']").val(v.npwp);
					$("input[name='npwp_awal']").val(v.npwp);
                    $("select[name='tax_type']").val(v.tax_type).trigger("change.select2");
                    $("input[name='tax_type_awal']").val(v.tax_type);
                    //load tax code
                    var output = '';
                    $.each(v.arr_tax_code, function(x, y) {
                        var selected = (y.tax_code == v.tax_code ? 'selected' : '');
						output += '<option value="' + y.tax_code + '" ' + selected + '>'+y.tax_code+' - '+ y.tax_code_name + '</option>';
                    });
                    $("select[name='tax_code']").html(output).select2();
                    $("input[name='tax_code_awal']").val(v.tax_code);
					//tab alamat
					$("select[name='negara']").val(v.negara).trigger("change.select2");
                    //load provinsi
                    var output = '';
                    $.each(v.arr_provinsi, function(x, y) {
                        var selected = (y.id_provinsi == v.provinsi ? 'selected' : '');
                        output += '<option value="' + y.id_provinsi + '" ' + selected + '>' + y.nama_provinsi + '</option>';
                    });
                    $("select[name='provinsi']").html(output).select2();
					$("input[name='kota']").val(v.kota);
					$("input[name='alamat']").val(v.alamat);
					$("input[name='no']").val(v.no);
					$("input[name='kode_pos']").val(v.kode_pos);
                });
            },
            complete: function() {
				$('.form-control-hide_temp').hide();
				$("#btn_save_delete").hide();
				$("#btn_save_delete_sap").hide();
				
                if (action == 'approve') {
					$('#ck_all_delete').prop('disabled', true);
					$('#plant_delete').prop('required', false);
					$('#plant_delete').prop('disabled', true);
					$("#btn_save_delete_sap").show();
                }else{
					$('#ck_all_delete').prop('disabled', false);
					$('#plant_delete').prop('required', true);
					$('#plant_delete').prop('disabled', false);
					$("#btn_save_delete").show();
				}
                $('#change_modal').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_extend')
                });
            }
        });
    });

    // $(document).on("click", ".nonactive, .setactive, .delete", function(e) {
        // $.ajax({
            // url: baseURL + "vendor/transaksi/set/spec",
            // type: 'POST',
            // dataType: 'JSON',
            // data: {
                // id_data: $(this).data($(this).attr("class")),
                // type: $(this).attr("class")
            // },
            // success: function(data) {
                // if (data.sts == 'OK') {
                    // kiranaAlert(data.sts, data.msg);
                // } else {
                    // kiranaAlert("notOK", data.msg, "warning", "no");
                // }
            // }
        // });
        // e.preventDefault();
        // return false;
    // });
    //add
    $(document).on("click", "button[name='action_btn']", function(e) {
		//validasi email
		let email = $("input[name='email']").val();
		// alert(email);
		// alert(isEmail(email));
		// alert(email.length);
		let kode_pos = $("input[name='kode_pos']").val();
		let npwp = $("input[name='npwp']").val();
		let ktp = $("input[name='ktp']").val();
		if ((kode_pos.length != 5)&&(kode_pos.length != 0)) {
			kiranaAlert("notOK", "Kodepos Harus 5 karakter.", "error", "no");
		}else if((npwp.length != 15)&&(npwp.length != 0)){
			kiranaAlert("notOK", "NPWP Harus 15 karakter.", "error", "no");
		}else if((ktp.length != 16)&&(ktp.length != 0)){
			kiranaAlert("notOK", "KTP Harus 16 karakter.", "error", "no");
		}else if((isEmail(email)==false)&&(email.length != 0)){
			kiranaAlert("notOK", "Email harus mengandung '.' dan '@'.", "error", "no");
		}else{
			var empty_form = validate('.form-transaksi-vendor');
			if (empty_form == 0) {
				var isproses = $("input[name='isproses']").val();
				if (isproses == 0) {
					// let nama = $("input[name='nama']").val();
					// if (nama.length > 35) {
						// kiranaAlert("notOK", "Nama Supplier maksimal 35 karakter.", "error", "no");
					// }
					// let alamat = $("input[name='alamat']").val();
					// if (alamat.length > 60) {
						// kiranaAlert("notOK", "Alamat Supplier maksimal 60 karakter.", "error", "no");
					// }
					// let no = $("input[name='no']").val();
					// if (no.length >= 10) {
						// kiranaAlert("notOK", "Nomor Rumah maksimal 10 karakter.", "error", "no");
					// }
					// let kota = $("input[name='kota']").val();
					// if (kota.length >= 40) {
						// kiranaAlert("notOK", "Kota maksimal 40 karakter.", "error", "no");
					// }
					// let kode_pos = $("input[name='kode_pos']").val();
					// if (kode_pos.length != 5) {
						// kiranaAlert("notOK", "Kode Pos Harus 5 karakter.", "error", "no");
					// }
					// let npwp = $("input[name='npwp']").val();
					// if (npwp.length != 15) {
						// kiranaAlert("notOK", "NPWP Harus 15 karakter.", "error", "no");
					// }
					
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-transaksi-vendor")[0]);
					// console.log();
					$.ajax({
						url: baseURL + 'vendor/transaksi/save/vendor',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						success: function(data) {
							if (data.sts == 'OK') {
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
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
		}
    });
    //approve pengajuan
    $(document).on("click", "button[name='btn_approve']", function(e) {
		let level = $("input[name='level']").val();
        var empty_form = validate('.form-transaksi-vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-vendor")[0]);
				// console.log();
				$.ajax({
					// url: baseURL + 'vendor/transaksi/save/vendor_approve_manager',
					url: baseURL + 'vendor/transaksi/save/approve',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							if(level==6){	
								$.ajax({
									url: baseURL + "vendor/rfc/set/create_vendor",
									type: 'POST',
									dataType: 'JSON',
									data: formData,
									contentType: false,
									cache: false,
									processData: false,
									success: function(data) {
										if (data.sts == 'OK') {
											swal('Success', data.msg, 'success').then(function() {
												location.reload();
											});
										} else {
											$("input[name='isproses']").val(0);
											swal('Error', data.msg, 'error');
										}
									}
								});
							}else{
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
								});
							}
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
	
    //decline pengajuan
    $(document).on("click", "button[name='btn_decline']", function(e) {
		let komentar = $("input[name='komentar']").val();
		if(komentar== ''){
			kiranaAlert("notOK", "Jika Decline, Komentar wajib diisi.", "error", "no");
		}else{
			var empty_form = validate('.form-transaksi-vendor');
			if (empty_form == 0) {
				var isproses = $("input[name='isproses']").val();
				if (isproses == 0) {
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-transaksi-vendor")[0]);
					// console.log();
					$.ajax({
						// url: baseURL + 'vendor/transaksi/save/vendor_approve_manager',
						url: baseURL + 'vendor/transaksi/save/decline',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						success: function(data) {
							if (data.sts == 'OK') {
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
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
		}
		
    });
	
    //decline change
    $(document).on("click", "button[name='btn_decline_change']", function(e) {
		let komentar = $("input[name='komentar']").val();
		if(komentar== ''){
			kiranaAlert("notOK", "Jika Decline, Komentar wajib diisi.", "error", "no");
		}else{
			var empty_form = validate('.form-transaksi-vendor');
			if (empty_form == 0) {
				var isproses = $("input[name='isproses']").val();
				if (isproses == 0) {
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-transaksi-vendor")[0]);
					// console.log();
					$.ajax({
						// url: baseURL + 'vendor/transaksi/save/vendor_approve_manager',
						url: baseURL + 'vendor/transaksi/save/decline_change',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						success: function(data) {
							if (data.sts == 'OK') {
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
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
			
		}
		
    });
    //decline extend
    $(document).on("click", "button[name='btn_decline_extend']", function(e) {
		let komentar = $("input[name='komentar_extend']").val();
		if(komentar== ''){
			kiranaAlert("notOK", "Jika Decline, Komentar wajib diisi.", "error", "no");
		}else{
			var empty_form = validate('.form-transaksi-extend_vendor');
			if (empty_form == 0) {
				var isproses = $("input[name='isproses']").val();
				if (isproses == 0) {
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-transaksi-extend_vendor")[0]);
					// console.log();
					$.ajax({
						// url: baseURL + 'vendor/transaksi/save/vendor_approve_manager',
						url: baseURL + 'vendor/transaksi/save/decline_extend',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						success: function(data) {
							if (data.sts == 'OK') {
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
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
		}
    });
    //decline delete
    $(document).on("click", "button[name='btn_decline_delete']", function(e) {
		let komentar = $("input[name='komentar_delete']").val();
		if(komentar== ''){
			kiranaAlert("notOK", "Jika Decline, Komentar wajib diisi.", "error", "no");
		}else{
			var empty_form = validate('.form-transaksi-delete_vendor');
			if (empty_form == 0) {
				var isproses = $("input[name='isproses']").val();
				if (isproses == 0) {
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-transaksi-delete_vendor")[0]);
					// console.log();
					$.ajax({
						// url: baseURL + 'vendor/transaksi/save/vendor_approve_manager',
						url: baseURL + 'vendor/transaksi/save/decline_delete',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						success: function(data) {
							if (data.sts == 'OK') {
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
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
		}
		
    });
	
    //decline undelete
    $(document).on("click", "button[name='btn_decline_undelete']", function(e) {
		//xx
		let komentar = $("input[name='komentar_undelete']").val();
		if(komentar== ''){
			kiranaAlert("notOK", "Jika Decline, Komentar wajib diisi.", "error", "no");
		}else{
			var empty_form = validate('.form-transaksi-undelete_vendor');
			if (empty_form == 0) {
				var isproses = $("input[name='isproses']").val();
				if (isproses == 0) {
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-transaksi-undelete_vendor")[0]);
					// console.log();
					$.ajax({
						// url: baseURL + 'vendor/transaksi/save/vendor_approve_manager',
						url: baseURL + 'vendor/transaksi/save/decline_undelete',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						success: function(data) {
							if (data.sts == 'OK') {
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
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
		}
		
    });
	
    //change pengajuan
    $(document).on("click", "button[name='btn_change']", function(e) {
		var approval_proc = $("input[name='approval_proc']").val();
		var approval_legal = $("input[name='approval_legal']").val();
		var perubahan_data = $("input[name='perubahan_data']").val();
		var pengajuan_ho = $("#pengajuan_ho").val();
		
		let kode_pos = $("input[name='kode_pos']").val();
		let npwp = $("input[name='npwp']").val();
		let ktp = $("input[name='ktp']").val();
		let level = $("input[name='level']").val();
		if ((kode_pos.length != 5)&&(kode_pos.length != 0)) {
			kiranaAlert("notOK", "Kodepos Harus 5 karakter.", "error", "no");
		}else if((npwp.length != 15)&&(npwp.length != 0)){
			kiranaAlert("notOK", "NPWP Harus 15 karakter.", "error", "no");
		}else if((ktp.length != 16)&&(ktp.length != 0)){
			kiranaAlert("notOK", "KTP Harus 16 karakter.", "error", "no");
		}else{
			var empty_form = validate('.form-transaksi-vendor');
			if (empty_form == 0) {
				var isproses = $("input[name='isproses']").val();
				if (isproses == 0) {
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-transaksi-vendor")[0]);
					// jika pengajuan ho tanpa approval legal
					// if((level==4)&&(perubahan_data=='y')&&(approval_proc=='n')&&(approval_legal=='n')){
						// alert('aa');
						// // $.ajax({
							// // url: baseURL + 'vendor/rfc/set/change_vendor',
							// // type: 'POST',
							// // dataType: 'JSON',
							// // data: formData,
							// // contentType: false,
							// // cache: false,
							// // processData: false,
							// // success: function(data) {
								// // if (data.sts == 'OK') {
									// // swal('Success', data.msg, 'success').then(function() {
										// // location.reload();
									// // });
								// // } else {
									// // $("input[name='isproses']").val(0);
									// // swal('Error', data.msg, 'error');
								// // }
							// // }
						// // });
					// }else{
						// alert('bb');
						$.ajax({
							url: baseURL + 'vendor/transaksi/save/change',
							type: 'POST',
							dataType: 'JSON',
							data: formData,
							contentType: false,
							cache: false,
							processData: false,
							success: function(data) {
								if (data.sts == 'OK') {
									if(level==4){	
										$.ajax({
											url: baseURL + 'vendor/rfc/set/change_vendor',
											type: 'POST',
											dataType: 'JSON',
											data: formData,
											contentType: false,
											cache: false,
											processData: false,
											success: function(data) {
												if (data.sts == 'OK') {
													swal('Success', data.msg, 'success').then(function() {
														location.reload();
													});
												} else {
													$("input[name='isproses']").val(0);
													swal('Error', data.msg, 'error');
												}
											}
										});
									}else{
										swal('Success', data.msg, 'success').then(function() {
											location.reload();
										});
									}
									
								} else {
									$("input[name='isproses']").val(0);
									swal('Error', data.msg, 'error');
								}
							}
						});
					// }
					
					
					// // jika pengajuan ho tanpa approval legal
					// if((level==4)&&(approval_legal!='y')){
						// $.ajax({
							// // url: baseURL + "data/rfc/set/change_vendor",
							// url: baseURL + 'vendor/rfc/set/change_vendor',
							// type: 'POST',
							// dataType: 'JSON',
							// data: formData,
							// contentType: false,
							// cache: false,
							// processData: false,
							// success: function(data) {
								// if (data.sts == 'OK') {
									// swal('Success', data.msg, 'success').then(function() {
										// location.reload();
									// });
								// } else {
									// $("input[name='isproses']").val(0);
									// swal('Error', data.msg, 'error');
								// }
							// }
						// });
					// }else{
						// $.ajax({
							// url: baseURL + 'vendor/transaksi/save/change',
							// type: 'POST',
							// dataType: 'JSON',
							// data: formData,
							// contentType: false,
							// cache: false,
							// processData: false,
							// success: function(data) {
								// if (data.sts == 'OK') {
									// swal('Success', data.msg, 'success').then(function() {
										// location.reload();
									// });
								// } else {
									// $("input[name='isproses']").val(0);
									// swal('Error', data.msg, 'error');
								// }
							// }
						// });
					// }
				} else {
					swal({
						title: "Silahkan tunggu proses selesai.",
						icon: 'info'
					});
				}
			}
			e.preventDefault();
			return false;
		}
    });
	
	//approve head proc dan push ke sap
	$(document).on("click", "button[name='btn_approve_sap']", function(e){
        var empty_form = validate('.form-transaksi-vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-vendor")[0]);
				// console.log();
				$.ajax({
					// url: baseURL + 'vendor/transaksi/save/vendor',
					// url: baseURL + "data/rfc/set/create_vendor",
					url: baseURL + "vendor/rfc/set/create_vendor",
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
								location.reload();
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
	// //change vendor sap
	// $(document).on("click", "button[name='btn_change_sap']", function(e){
        // var empty_form = validate('.form-transaksi-vendor');
        // if (empty_form == 0) {
            // var isproses = $("input[name='isproses']").val();
            // if (isproses == 0) {
				// $("input[name='isproses']").val(1);
				// var formData = new FormData($(".form-transaksi-vendor")[0]);
				// // console.log();
				// $.ajax({
					// // url: baseURL + "data/rfc/set/change_vendor",
					// url: baseURL + 'vendor/rfc/set/change_vendor',
					// type: 'POST',
					// dataType: 'JSON',
					// data: formData,
					// contentType: false,
					// cache: false,
					// processData: false,
					// success: function(data) {
						// if (data.sts == 'OK') {
							// swal('Success', data.msg, 'success').then(function() {
								// location.reload();
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
	
	//approve change vendor
	$(document).on("click", "button[name='btn_approve_change']", function(e){
		var level = $("#level").val();
		var approval_legal = $("input[name='approval_legal']").val();
		var approval_proc = $("input[name='approval_proc']").val();
		var perubahan_data = $("input[name='perubahan_data']").val();
		var pengajuan_ho = $("#pengajuan_ho").val();
		// alert(perubahan_data);
        var empty_form = validate('.form-transaksi-vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-vendor")[0]);
				$.ajax({
					url: baseURL + 'vendor/transaksi/save/approve_change',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							if(level==4){
								$.ajax({
									// url: baseURL + "data/rfc/set/change_vendor",
									url: baseURL + 'vendor/rfc/set/change_vendor',
									type: 'POST',
									dataType: 'JSON',
									data: formData,
									contentType: false,
									cache: false,
									processData: false,
									success: function(data) {
										if (data.sts == 'OK') {
											swal('Success', data.msg, 'success').then(function() {
												location.reload();
											});
										} else {
											$("input[name='isproses']").val(0);
											swal('Error', data.msg, 'error');
										}
									}
								});
							}else{
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
								});
							}
						} else {
							$("input[name='isproses']").val(0);
							swal('Error', data.msg, 'error');
						}
					}
				});
				
				
				// console.log();
				// if(level==4){
					// $.ajax({
						// // url: baseURL + "data/rfc/set/change_vendor",
						// url: baseURL + 'vendor/rfc/set/change_vendor',
						// type: 'POST',
						// dataType: 'JSON',
						// data: formData,
						// contentType: false,
						// cache: false,
						// processData: false,
						// success: function(data) {
							// if (data.sts == 'OK') {
								// swal('Success', data.msg, 'success').then(function() {
									// location.reload();
								// });
							// } else {
								// $("input[name='isproses']").val(0);
								// swal('Error', data.msg, 'error');
							// }
						// }
					// });
				// }else{
					// $.ajax({
						// url: baseURL + 'vendor/transaksi/save/approve_change',
						// type: 'POST',
						// dataType: 'JSON',
						// data: formData,
						// contentType: false,
						// cache: false,
						// processData: false,
						// success: function(data) {
							// if (data.sts == 'OK') {
								// swal('Success', data.msg, 'success').then(function() {
									// location.reload();
								// });
							// } else {
								// $("input[name='isproses']").val(0);
								// swal('Error', data.msg, 'error');
							// }
						// }
					// });
				// }
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
	
	//save extend
	$(document).on("click", "button[name='btn_save_extend']", function(e){
        var empty_form = validate('.form-transaksi-extend_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-extend_vendor")[0]);
				// console.log();
				$.ajax({
					url: baseURL + 'vendor/transaksi/save/extend',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
								location.reload();
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
	//extend sap
	$(document).on("click", "button[name='btn_save_extend_sap']", function(e){
		var level = $("#level").val();
		var pengajuan_ho = $("#pengajuan_ho").val();
        var empty_form = validate('.form-transaksi-extend_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-extend_vendor")[0]);
				// console.log();
				$.ajax({
					url: baseURL + 'vendor/transaksi/save/approve_extend',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							if(level==6){
								$.ajax({
									url: baseURL + "vendor/rfc/set/extend_vendor",
									type: 'POST',
									dataType: 'JSON',
									data: formData,
									contentType: false,
									cache: false,
									processData: false,
									success: function(data) {
										if (data.sts == 'OK') {
											swal('Success', data.msg, 'success').then(function() {
												location.reload();
											});
										} else {
											$("input[name='isproses']").val(0);
											swal('Error', data.msg, 'error');
										}
									}
								});
							}else{
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
								});
							}	
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
	//save delete
	$(document).on("click", "button[name='btn_save_delete']", function(e){
		var level = $("#level").val();
        var empty_form = validate('.form-transaksi-delete_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-delete_vendor")[0]);
				$.ajax({
					url: baseURL + 'vendor/transaksi/save/delete',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							if(level==4){
								$.ajax({
									url: baseURL + 'vendor/rfc/set/delete_vendor/ho',
									type: 'POST',
									dataType: 'JSON',
									data: formData,
									contentType: false,
									cache: false,
									processData: false,
									success: function(data) {
										if (data.sts == 'OK') {
											swal('Success', data.msg, 'success').then(function() {
												location.reload();
											});
										} else {
											$("input[name='isproses']").val(0);
											swal('Error', data.msg, 'error');
										}
									}
								});
							}else{
								swal('Success', data.msg, 'success').then(function() {
									location.reload();
								});
							}								
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
	
	//delete sap
	$(document).on("click", "button[name='btn_save_delete_sap']", function(e){
        var empty_form = validate('.form-transaksi-delete_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-delete_vendor")[0]);
				// console.log();
				$.ajax({
					url: baseURL + 'vendor/rfc/set/delete_vendor',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
								location.reload();
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
	//save undelete
	$(document).on("click", "button[name='btn_save_undelete']", function(e){
		var id_status_undelete = $("#id_status_undelete").val();
        var empty_form = validate('.form-transaksi-undelete_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-undelete_vendor")[0]);
				// console.log();
				$.ajax({
					url: baseURL + 'vendor/transaksi/save/undelete',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
								location.reload();
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
	
	//undelete sap
	$(document).on("click", "button[name='btn_save_undelete_sap']", function(e){
		var level = $("#level").val();
		var id_status_undelete = $("#id_status_undelete").val();
        var empty_form = validate('.form-transaksi-undelete_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-undelete_vendor")[0]);
					$.ajax({
						url: baseURL + 'vendor/transaksi/save/approve_undelete',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						success: function(data) {
							if (data.sts == 'OK') {
								if(level==6){
									$.ajax({
										url: baseURL + 'vendor/rfc/set/undelete_vendor',
										type: 'POST',
										dataType: 'JSON',
										data: formData,
										contentType: false,
										cache: false,
										processData: false,
										success: function(data) {
											if (data.sts == 'OK') {
												swal('Success', data.msg, 'success').then(function() {
													location.reload();
												});
											} else {
												$("input[name='isproses']").val(0);
												swal('Error', data.msg, 'error');
											}
										}
									});
								}else{
									swal('Success', data.msg, 'success').then(function() {
										location.reload();
									});
								}
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
	
	//approve
	$(document).on("click", "button[name='action_btn_approve_']", function(e){
        var empty_form = validate('.form-transaksi-vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-vendor")[0]);
				kiranaConfirm(
					{
						title: "Konfirmasi",
						text: "Vendor akan langsung dibuat ke SAP, apakah proses akan dilanjutkan?",
						dangerMode: true,
						successCallback: function () {
							// alert('aa');
							//push sap
							$.ajax({
								// url: baseURL + "data/rfc/set/kode_material",
								// url: baseURL + "data/rfc/set/create_vendor",
								url: baseURL + "vendor/rfc/set/create_vendor",
								type: 'POST',
								dataType: 'JSON',
								data: formData,
								success: function(data){
									if(data.sts == 'OK'){
										kiranaAlert(data.sts, data.msg);
									}else{
										kiranaAlert("notOK", data.msg, "warning", "no");
									}
								},
								complete: function () {
									$("input[name='isproses']").val(0);
								}
							});
						}
					}
				);
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

    //export to excel
    $('.my-datatable-extends-order').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Penilaian',
            download: 'open',
            orientation: 'landscape',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
            }
        }],
        scrollX: true
    });

    //open modal for add     
    $(document).on("click", "#add_button", function(e) {
		$('#id_data').val("");
		$('#id_jenis_vendor').val("");
		// $('#kualifikasi_spk').val("");
		$("select[name='kualifikasi_spk[]']").val('').trigger("change");
		$("#show_dokumen_jenis").html('');
		$("#show_dokumen_kualifikasi").html('');
		
        resetForm_use($('.form-transaksi-vendor'));
        $('#add_modal').modal('show');
        $("#btn_change").hide();
        $("#btn_change_sap").hide();
        $("#btn_approve_change").hide();
        $("#btn_decline_change").hide();
        $("#btn_decline").hide();
        $("#btn_decline_extend").hide();
        $("#btn_approve").hide();
        $("#btn_approve_sap").hide();
        $("#cek_dok_jenis_vendor").hide();
        $("#cek_dok_kualifikasi_vendor").hide();
        $('.select2modal').select2({
            dropdownParent: $('#add_modal')
        });

    });
	
    //open modal for add     
    $(document).on("click", "#cek_vendor", function(e) {
		// $('#nama_vendor').val("");
        $('#vendor_modal').modal('show');
    });
	
    //open modal for req     
	$(document).on("click", "#cek_btn_vendor", function(e){
		var nama_vendor = $("#nama_vendor").val();
		//push sap
		$.ajax({
			// url: baseURL + "data/rfc/get/vendor",
			url: baseURL + "vendor/rfc/get/vendor",
			type: 'POST',
			dataType: 'JSON',
			data: {
				nama_vendor : nama_vendor
			},
			success: function(data){
				var det	= "";
					det	+= 		'<div class="row">';
					det	+= 		'<div class="col-sm-12">';
					det	+= 		'<div class="box box-success">';
					det	+= 		'<div class="box-header">';
					det	+= 		'<h4 class="box-title"><strong>Daftar Nama Vendor di SAP</h4>';
					det	+= 		'</div>';
					det	+= 		'<table class="table table-bordered datatable-vendor">';
					det	+= 		'	<thead>';
					det	+= 		'		<tr>';
					det	+= 		'			<th>Pabrik</th>';
					det	+= 		'			<th>Nama Vendor</th>';
					det	+= 		'			<th>Alamat</th>';
					det	+= 		'			<th>NPWP</th>';
					det	+= 		'		</tr>';
					det	+= 		'	</thead>';
					det	+= 		'	<tbody>';
					$.each(data, function(i,v){
						det	+= 		'		<tr>';
						det	+= 		'			<td>'+v.EKORG+'</td>';
						det	+= 		'			<td>'+v.NAME1.toUpperCase()+'<br>('+v.LIFNR+')</td>';
						det	+= 		'			<td>'+v.CITY1+'</td>';
						det	+= 		'			<td>'+v.POST_CODE1+'</td>';
						det	+= 		'		</tr>';
					});
					det	+= 		'	</tbody>';
					det	+= 		'</table>';
					det	+= 		'</div>';
					det	+= 		'</div>';
					det	+= 		'</div>';
					$("#show_vendor").html(det);
			},
			complete: function () {
				setTimeout(function () {
					$("table.datatable-vendor").DataTable({
						"bLengthChange": false
					}).columns.adjust();
				}, 1500);				
			}
		});
	});
	

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

function resetForm_extend($form) {
    $('#plant_extend').prop('disabled', false);
}

function datatables_ssp() {
    var id_jenis_vendor 	= $("#id_jenis_vendor_filter").val();
    var id_kualifikasi_spk	= $("#id_kualifikasi_spk_filter").val();
    var status_pengajuan 	= $("#status_filter_pengajuan").val();
    var id_role				= $("#id_role_filter").val();
    var jenis_pengajuan		= $("#filter_jenis_pengajuan").val();
	// var status_extend	 	= $("#status_filter_extend").val();
    // var status_change 		= $("#status_filter_change").val();
    // var status_delete 		= $("#status_filter_delete").val();
    // var status_undelete 	= $("#status_filter_undelete").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        pageLength: 25,
		"order": [[ 1, "desc" ]],
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
        ajax: {
            url: baseURL + 'vendor/transaksi/get/data/bom',
            type: 'POST',
            data: function(data) {
                data.id_jenis_vendor 	= id_jenis_vendor;
                data.id_kualifikasi_spk = id_kualifikasi_spk;
                data.status_pengajuan 	= status_pengajuan;
				data.id_role 			= id_role;
                data.jenis_pengajuan	= jenis_pengajuan;
				// data.status_extend 		= status_extend;
                // data.status_change	 	= status_change;
                // data.status_delete 		= status_delete;
                // data.status_undelete 	= status_undelete;
                
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [{
                "data": "id_data",
                "name": "id_data",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.id_data;
                },
                "visible": false
            },
            {
                "data": "lifnr",
                "name": "lifnr",
                "width": "10%",
                "render": function(data, type, row) {
					if(row.lifnr!=null){
						return '<b>'+parseInt(row.lifnr)+'</b>';
					}else{
						return '<b>-</b>';
					}
					
                }
            },
            {
                "data": "nama",
                "name": "nama",
                "width": "15%",
                "render": function(data, type, row) {
					if(row.acc_group=='ONVE'){
						var nama_provinsi = (row.kota!=null)? row.kota+" - ":"";
					}else{
						var nama_provinsi = (row.nama_provinsi!=null)? row.nama_provinsi+" - ":"";
					}
					if(row.lifnr!=null){
						return '<b>'+row.nama+'</b><br>'+nama_provinsi+''+row.nama_negara+'<br>'+row.telepon;
					}else{
						return '<b>'+row.nama+'</b><br>'+nama_provinsi+''+row.nama_negara+'<br>'+row.telepon;
					}
                }
            },
            {
                "data": "jenis_vendor",
                "name": "jenis_vendor",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.jenis_vendor+"<br><strong><i class='fa fa-files-o'></i> &nbsp; "+row.jum_dok_jenis_man_upload+"/"+row.jum_dok_jenis_man+" Dokumen Mandatory</strong><br><strong><i class='fa fa-files-o'></i> &nbsp; "+row.jum_dok_jenis_non_man_upload+"/"+row.jum_dok_jenis_non_man+" Dokumen Non Mandatory</strong>";
                }
            },
            {
                "data": "list_kualifikasi_spk",
                "name": "list_kualifikasi_spk",
                "width": "15%",
                "render": function(data, type, row) {
                    if(row.list_kualifikasi_spk!=null){
						return row.list_kualifikasi_spk.slice(0,-1)+"<br><strong><i class='fa fa-files-o'></i> &nbsp; "+row.jum_dok_kualifikasi_man_upload+"/"+row.jum_dok_kualifikasi_man+" Dokumen</strong>";
					}else{
						return row.list_kualifikasi_spk;
					}
                }
            },
            {
                "data": "nilai",
                "name": "nilai",
                "width": "5%",
                "render": function(data, type, row) {
					if(row.total_penilaian>=70){
						return '<b>'+row.total_penilaian+' (Lulus)</b>';
					}else{
						return '<b>'+row.total_penilaian+' (Gagal)</b>';
					}
                }
            },
            {
                "data": "plant",
                "name": "plant",
                // "width": "5%",
                "render": function(data, type, row) {
					if(row.list_plant != null){
						var list_plant 	= ''; 
						var arr_plant	= row.list_plant.slice(0, -1).split(",");
						$.each(arr_plant, function(x, y){
							var dt = y.split("|");
							if(dt[2]=='y'){	//status_sap
								if(dt[1]=='y'){	//status delete
									list_plant += "<button class='btn btn-sm btn-danger btn-role'>"+dt[0]+"</button>";
								}else{
									list_plant += "<button class='btn btn-sm btn-success btn-role'>"+dt[0]+"</button>";
								}
							}else{
								list_plant += "<button class='btn btn-sm btn-default btn-role'>"+dt[0]+"</button>";
							}	
						});
						return list_plant;
					}else{
						return '';
					}
                }
            },
            {
                "data": "req",
                "name": "req",
                "width": "5%",
                "render": function(data, type, row) {
					var list_status = '';
					//pengajuan
                    if (row.req == 'n') {
                        list_status += '<b>Pengajuan Vendor</b> <label class="label label-success">Completed</label>';
                    } else {
						if(((row.komentar!=null)&&(row.pengajuan_ho=='n')&&(row.level=='1'))||((row.komentar!=null)&&(row.pengajuan_ho=='y')&&(row.level=='4'))){
							list_status += '<b>Pengajuan Vendor</b> <label class="label label-warning">On Progress</label><br>'+row.nama_role;
						}else{
							list_status += '<b>Pengajuan Vendor</b> <label class="label label-warning">On Progress</label><br>'+row.nama_role;
						}
                        
                    }
					//extend
					if(row.id_status_extend!=null){
						if (row.id_status_extend == 99) {
							// list_status += '<br><br><b>Extend Vendor</b> <label class="label label-success">Completed</label>';
							list_status += '';
						} else {
							if(((row.komentar_extend!=null)&&(row.pengajuan_ho_temp=='n')&&(row.level=='1'))||((row.komentar_extend!=null)&&(row.pengajuan_ho_temp=='y')&&(row.level=='4'))){
								list_status += '<br><br><b>Extend Vendor</b> <label class="label label-warning">On Progress</label><br>'+row.nama_role_extend+'<br><p class="text-red">('+row.komentar_extend+')</p>';
							}else{
								list_status += '<br><br><b>Extend Vendor</b> <label class="label label-warning">On Progress</label><br>'+row.nama_role_extend;
							}
						}
					}
					//change
					if(row.id_status_change!=null){
						if (row.id_status_change == 99) {
							// list_status += '<br><br><b>Change Vendor</b> <label class="label label-success">Completed</label>';
							list_status += '';
						} else {
							if(((row.komentar_change!=null)&&(row.pengajuan_ho_temp=='n')&&(row.level=='1'))||((row.komentar_change!=null)&&(row.pengajuan_ho_temp=='y')&&(row.level=='4'))){
								list_status += '<br><br><b>Change Vendor</b> <label class="label label-warning">On Progress</label><br>'+row.nama_role_change+'<br><p class="text-red">('+row.komentar_change+')</p>';
							}else{
								list_status += '<br><br><b>Change Vendor</b> <label class="label label-warning">On Progress</label><br>'+row.nama_role_change;
							}
						}
					}
					//delete
					if(row.id_status_delete!=null){
						if (row.id_status_delete == 99) {
							// list_status += '<br><br><b>Delete Vendor</b> <label class="label label-success">Completed</label>';
							list_status += '';
						} else {
							list_status += '<br><br><b>Delete Vendor</b> <label class="label label-warning">On Progress</label><br>'+row.nama_role_delete;
						} 
					}
					//undelete
					if(row.id_status_undelete!=null){
						if (row.id_status_undelete == 99) {
							// list_status += '<br><br><b>Undelete Vendor</b> <label class="label label-success">Completed</label>';
							list_status += '';
						} else {
							list_status += '<br><br><b>Undelete Vendor</b> <label class="label label-warning">On Progress</label><br>'+row.nama_role_undelete;
						}
					}
					return list_status;
                }
            },
            {
                "data": "id_data",
                "name": "id_data",
                "width": "5%",
                "render": function(data, type, row) {
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					// output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='edit'><i class='fa fa-pencil-square-o'></i> "+row.login_buat+" xx "+row.id_user+" yy "+row.id_status+" zz "+row.pengajuan_ho+"</a></li>";
					if (row.req == 'y') {
						if((row.login_buat == row.id_user)&&(row.id_status == 2)&&(row.pengajuan_ho == 'n'))
							output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='edit'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
						if((row.login_buat == row.id_user)&&((row.id_status == 3)||(row.id_status == 5))&&(row.pengajuan_ho == 'y'))
							output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='edit'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
						//pengajuan ulang
						if((row.login_buat == row.id_user)&&(row.id_status == 1)&&(row.pengajuan_ho == 'n'))
							output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='edit'  data-pengajuan='ulang'><i class='fa fa-pencil-square-o'></i> Pengajuan Ulang</a></li>";
						if((row.login_buat == row.id_user)&&(row.id_status == 4)&&(row.pengajuan_ho == 'y'))
							output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='edit'  data-pengajuan='ulang'><i class='fa fa-pencil-square-o'></i> Pengajuan Ulang</a></li>";

						//approve pengajuan
						if((row.level == row.id_status)&&(row.id_status!=1)&&(row.pengajuan_ho=='n'))
							output += "				<li><a href='javascript:void(0)' class='edit' data-action='approve' data-id_data='" + row.id_data + "' data-level='" + row.level + "' data-nama_role='" + row.nama_role+ "'><i class='fa fa-thumbs-o-up'></i> Appove Pengajuan</a></li>";
						if((row.level == row.id_status)&&(row.id_status!=4)&&(row.pengajuan_ho=='y'))
							output += "				<li><a href='javascript:void(0)' class='edit' data-action='approve' data-id_data='" + row.id_data + "' data-level='" + row.level + "' data-nama_role='" + row.nama_role+ "'><i class='fa fa-thumbs-o-up'></i> Appove Pengajuan</a></li>";
						
					}
					if (row.req == 'n') {
						//acc group jika bukan ONVE(kepala 9)
						// if(row.acc_group!='ONVE'){
							if(row.list_plant!=null){
								var arr_plant	= row.list_plant.slice(0, -1).split(",");
								var my_array_plant 		= [];
								var my_array_plant_del 	= [];
								$.each(arr_plant, function(x, y){
									var dt = y.split("|");
									//list plant
									my_array_plant.push(dt[0]);
									//list plant delete
									if(dt[1]=='y'){	//status delete
										my_array_plant_del.push(dt[0]);
									}
								});
								//jika ada akses plant
								if($.inArray(row.gsber, my_array_plant) != -1){
									
									if($.inArray(row.gsber, my_array_plant_del) != -1){
										var akses_extend = 'n';
										var akses_change = 'n';
										var akses_delete = 'n';
										var akses_undelete = 'y';
									}else{
										var akses_extend = 'n';
										var akses_change = 'y';
										var akses_delete = 'y';
										var akses_undelete = 'n';
									}
									
								}else{
									var akses_extend = 'y';
									var akses_change = 'n';
									var akses_delete = 'n';
									var akses_undelete = 'n';
								}
								///jika staf proc HO
								if(row.level==4){
									var akses_extend = 'y';
									var akses_change = 'y';
									var akses_delete = 'y';
									var akses_undelete = 'y';
									
								}
								///jika staf Legal HO
								if(row.level==3){
									var akses_change = 'y';
								}
								
								///jika staf proc Pabrik memiliki lebih dari 1 plant
								if((row.level==1)&&((row.gsber=='NSI1')||(row.gsber=='KPT1')||(row.gsber=='KPT2')||(row.gsber=='KUT1')||(row.gsber=='KUT2'))){
									var akses_extend = 'y';
								}
							}
							
							
							//cek jika ada proses, maka semua link di tutup
							var sum_status_extend 	= ((row.id_status_extend==null || row.id_status_extend==99) ? 0 : row.id_status_extend);
							var sum_status_change 	= ((row.id_status_change==null || row.id_status_change==99) ? 0 : row.id_status_change);
							var sum_status_delete 	= ((row.id_status_delete==null || row.id_status_delete==99) ? 0 : row.id_status_delete);
							var sum_status_undelete	= ((row.id_status_undelete==null || row.id_status_undelete==99) ? 0 : row.id_status_undelete);
							var total_status		=  sum_status_extend+sum_status_change+sum_status_delete+sum_status_undelete;
							if(total_status==0){
								//extend
								if(((row.level==1)||(row.level==4))&&(akses_extend=='y')&&(row.acc_group!='ONVE')){
									// if(((row.id_status_extend==null)||(row.id_status_extend == 99))||(row.id_user==row.id_user_extend))
									output += "				<li><a href='javascript:void(0)' class='extend' data-id_data='" + row.id_data + "'><i class='fa fa-arrows'></i> Extend</a></li>";
								}
								//change
								if(((row.id_status_change==99)||(row.id_status_change==null))&&(akses_change=='y'))
									output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='change'><i class='fa fa-pencil-square-o'></i> Change Data</a></li>";
									// output += "				<li><a href='javascript:void(0)' class='change' data-id_data='" + row.id_data + "'><i class='fa fa-pencil-square-o'></i> Change Data</a></li>";
								//delete
								if(((row.id_status_delete==99)||(row.id_status_delete==null))&&(akses_delete=='y')&&(row.acc_group!='ONVE'))
									output += "				<li><a href='javascript:void(0)' class='delete' data-id_data='" + row.id_data + "'><i class='fa fa-ban'></i> Delete Data</a></li>";
								//undelete
								if(((row.id_status_undelete==99)||(row.id_status_undelete==null))&&(akses_undelete=='y')&&(row.acc_group!='ONVE'))
								output += "				<li><a href='javascript:void(0)' class='undelete' data-id_data='" + row.id_data + "'><i class='fa fa-rotate-right '></i> Undelete Data</a></li>";
							}
								

							
							//approve extend
							if((row.level == row.id_status_extend)&&(row.id_status_extend!=1) && (row.pengajuan_ho_temp=='n')){
								output += "				<li><a href='javascript:void(0)' class='extend' data-action='approve' data-id_data='" + row.id_data + "' data-level='" + row.level + "' data-nama_role='" + row.nama_role+ "'><i class='fa fa-thumbs-o-up'></i> Approve Extend</a></li>";
							}
							if((row.level == row.id_status_extend)&&(row.id_status_extend!=4) && (row.pengajuan_ho_temp=='y')){
								output += "				<li><a href='javascript:void(0)' class='extend' data-action='approve' data-id_data='" + row.id_data + "' data-level='" + row.level + "' data-nama_role='" + row.nama_role+ "'><i class='fa fa-thumbs-o-up'></i> Approve Extend</a></li>";
							}
							
							// extend ulang
							if((row.level == row.id_status_extend)&&(row.id_status_extend==1)&&(row.pengajuan_ho_temp=='n')){
								output += "				<li><a href='javascript:void(0)' class='extend' data-id_data='" + row.id_data + "' data-pengajuan='ulang'><i class='fa fa-arrows'></i> Extend Ulang</a></li>";
							}
							if((row.level == row.id_status_extend)&&(row.id_status_extend==4)&&(row.pengajuan_ho_temp=='y')){
								output += "				<li><a href='javascript:void(0)' class='extend' data-id_data='" + row.id_data + "' data-pengajuan='ulang'><i class='fa fa-arrows'></i> Extend Ulang</a></li>";
							}
								
							//approve change
							if((row.level == row.id_status_change)&&(row.id_status_change==4)&&(row.pengajuan_ho_temp=='n'))	
								output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='change' data-action_detail='approve' data-level='" + row.level + "' data-nama_role='" + row.nama_role+ "' data-pengajuan='ulang'><i class='fa fa-thumbs-o-up'></i> Approve Change</a></li>";
							if((row.level == row.id_status_change)&&(row.id_status_change==3))
								output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='change' data-action_detail='approve' data-level='" + row.level + "' data-nama_role='" + row.nama_role+ "' data-pengajuan='ulang'><i class='fa fa-thumbs-o-up'></i> Approve Change</a></li>";
							//change ulang
							if((row.level == row.id_status_change)&&(row.id_status_change == 1)&&(row.pengajuan_ho_temp == 'n'))
								output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='change' data-pengajuan='ulang'><i class='fa fa-pencil-square-o'></i> Change Ulang</a></li>";
							if((row.level == row.id_status_change)&&(row.id_status_change == 4)&&(row.pengajuan_ho_temp == 'y'))
								output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='change' data-pengajuan='ulang'><i class='fa fa-pencil-square-o'></i> Change Ulang</a></li>";

							//approve delete
							if(row.level == row.id_status_delete)
								output += "				<li><a href='javascript:void(0)' class='delete' data-id_data='" + row.id_data + "' data-action='approve' data-level='" + row.level + "' data-nama_role='" + row.nama_role+ "'><i class='fa fa-thumbs-o-up'></i> Approve Delete</a></li>";
							//approve undelete
							if(row.level == row.id_status_undelete)
								output += "				<li><a href='javascript:void(0)' class='undelete' data-id_data='" + row.id_data + "' data-action='approve' data-level='" + row.level + "' data-nama_role='" + row.nama_role+ "'><i class='fa fa-thumbs-o-up'></i> Approve Undelete</a></li>";
						// }else{
								
						// }
					}
					output += "				<li><a href='javascript:void(0)' class='history' data-id_data='" + row.id_data + "'><i class='fa fa-h-square'></i> History "+total_status+"</a></li>";
					output += "				<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "' data-action='detail'><i class='fa fa-search'></i> Detail</a></li>";					
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
function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
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