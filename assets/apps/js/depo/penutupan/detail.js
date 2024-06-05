/*
@application    : MASTER DEPO
@author         : Lukman Hakim(7143)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/
var opt_sdm = '';
master_sdm("select[name='id_biaya_sdm[]']", 'all','operational','sdm');
$(document).ready(function () {
	master_sdm("select[name='id_biaya_sdm[]']", 'all','operational','sdm');
	// input_keuangan();
	
	//dari sini get data
    $.ajax({
        url: baseURL + "depo/penutupan/get/data",
        type: "POST",
        dataType: "JSON",
        data: {
            nomor: $("#nomor").val(),
            return: "json",
            data: "complete",
        },
        beforeSend: function () { },
        success: function (data) {
			$.each(data, function (i, v) {
				//header
				$("input[name='id_depo_master']").val(v.id_depo_master);
				$("input[name='nama_depo']").val(v.id_depo_master+' - '+v.nama_depo);
				$("input[name='nomor']").val(v.nomor);
				$("input[name='jenis_depo']").val(v.jenis_depo_format);
				$("input[name='pabrik']").val(v.pabrik);
				$("input[name='status_akhir']").val(v.status);
				$("input[name='level']").val(v.level);
				//detail sdm
                if (v.arr_data_sdm) {
					$("#nodata").remove();
					$.each(v.arr_data_sdm, function(a, b){
						let output = "";
						output += "<tr class='row-sdm sdm" + b.nik + "'>";
						output += "	<td>";
						output += "		<select class='form-control select2 autocomplete' name='id_biaya_sdm[]' data-placeholder='Pilih SDM' required='required'>";
						output += "			<option></option>";
						output += 			opt_sdm;
						output += "		</select>";
						output += "	</td>";
						output += "	<td>";
						output += "		<select class='form-control select2 autocomplete' name='nik[]' required='required'>";
						output += "			<option></option>";
						output += "		</select>";
						output += "	</td>";
						output += "	<td>";
						output += "		<select class='form-control select2' name='sdm_status_rencana[]' data-placeholder='Pilih Status' required='required'>";
						output += "			<option></option>";
						output += "			<option value='mutasi'>Mutasi</option>";
						output += "			<option value='rotasi'>Rotasi</option>";
						output += "			<option value='promosi'>Promosi</option>";
						output += "		</select>";
						output += "	</td>";
						output += "	<td>";
						output += "		<select class='form-control select2' name='sdm_lokasi_rencana[]' data-placeholder='Pilih Lokasi'  required='required'>";
						output += "			<option></option>";
						output += "			<option value='pabrik'>Pabrik</option>";
						output += "			<option value='depo'>Depo</option>";
						output += "		</select>";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control tanggal' name='sdm_tanggal_rencana[]' value='"+b.tanggal_rencana_format+"' placeholder='Tanggal'  required='required'/>";
						output += "	</td>";
						output += '	<td class="text-center">';
						output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
						output += "	</td>";
						output += "</tr>";
						$(output).appendTo(".table-sdm tbody");
					
						const elem = ".row-sdm.sdm" + b.nik;
						master_karyawan(elem + " select[name='nik[]']");
						let control = $(elem+ " select[name='nik[]']").empty().data("select2");
						let adapter = control.dataAdapter;
						let desc = b.nik+' - '+b.nama_karyawan;
						adapter.addOptions(
							adapter.convertToOptions([{
								id: b.nik,
								text: desc,
							},])
						);
						$(elem+ " select[name='nik[]']").trigger("change");
						$(elem+ " select[name='id_biaya_sdm[]']").val(b.id_biaya).trigger("change.select2");
						$(elem+ " select[name='sdm_status_rencana[]']").val(b.status_rencana).trigger("change.select2");
						$(elem+ " select[name='sdm_lokasi_rencana[]']").val(b.lokasi_rencana).trigger("change.select2");
						//set select2	
						$("select[name='id_biaya_sdm[]']").select2();
						$("select[name='sdm_status_rencana[]']").select2();
						$("select[name='sdm_lokasi_rencana[]']").select2();
						//set tanggal	
						$('.tanggal').datepicker({
							format: 'dd.mm.yyyy',
							autoclose: true
						});
					});
                }								
				//detail asset
                if (v.arr_data_asset) {
					$("#nodata_asset").remove();
					$.each(v.arr_data_asset, function(a, b){
						let output = "";
						output += "<tr class='row-asset asset" + b.kode + "'>";
						output += "	<td>";
						output += "		<select class='form-control select2 autocomplete' name='asset[]' required='required'>";
						output += "			<option></option>";
						output += "		</select>";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center angka' name='asset_jumlah[]' value='"+numberWithCommas(parseFloat(b.jumlah).toFixed(0))+"' placeholder='Jumlah'  required='required'/>";
						output += "	</td>";
						output += "	<td>";
						output += "		<select class='form-control select2' name='asset_status_rencana[]' data-placeholder='Pilih Keterangan' required='required'>";
						output += "			<option></option>";
						output += "			<option value='mutasi'>Mutasi</option>";
						output += "			<option value='disposal'>Disposal</option>";
						output += "		</select>";
						output += "	</td>";
						output += "	<td>";
						output += "		<select class='form-control select2' name='asset_lokasi_rencana[]' data-placeholder='Pilih Lokasi' required='required'>";
						output += "			<option></option>";
						output += "			<option value='pabrik'>Pabrik</option>";
						output += "			<option value='depo'>Depo</option>";
						output += "		</select>";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control tanggal' name='asset_tanggal_rencana[]' value='"+b.tanggal_rencana_format+"' placeholder='Tanggal'  required='required'/>";
						output += "	</td>";
						output += '	<td class="text-center">';
						output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
						output += "	</td>";
						output += "</tr>";
						$(output).appendTo(".table-asset tbody");
						
						const elem = ".row-asset.asset" + b.kode;
						master_asset(elem + " select[name='asset[]']");
						let control = $(elem+ " select[name='asset[]']").empty().data("select2");
						let adapter = control.dataAdapter;
						let desc = b.kode+' - '+b.nama_asset;
						adapter.addOptions(
							adapter.convertToOptions([{
								id: b.kode,
								text: desc,
							},])
						);
						$(elem+ " select[name='asset_status_rencana[]']").val(b.status_rencana).trigger("change.select2");
						$(elem+ " select[name='asset_lokasi_rencana[]']").val(b.lokasi_rencana).trigger("change.select2");
						
						//set select2
						$("select[name='asset_status_rencana[]']").select2();
						$("select[name='asset_lokasi_rencana[]']").select2();
						$('.tanggal').datepicker({
							format: 'dd.mm.yyyy',
							autoclose: true
						});
					});
                }								
				//detail keuangan
                if (v.arr_data_keuangan) {
					let no = 0;
					$("#nodata_keuangan").remove();
					$.each(v.arr_data_keuangan, function(a, b){
						let output = "";
						let val_keuangan_jumlah = (b.jumlah>0)?numberWithCommas(parseFloat(b.jumlah).toFixed(0)):'';
						let val_keuangan_penyelesaian_rencana = (b.penyelesaian_rencana>0)?numberWithCommas(parseFloat(b.penyelesaian_rencana).toFixed(0)):'';
						let val_keuangan_tanggal_rencana = (b.tanggal_rencana_format!='01.01.1970')?b.tanggal_rencana_format:'';
						output += "<tr class='row-keuangan keuangan" + no + "'>";
						output += "	<td>";
						output += "		<input type='hidden' class='form-control' name='id_keuangan[]' value='"+b.id_keuangan+"'/>";
						output += "		<input type='text' class='form-control' name='keuangan_nama[]' value='"+b.nama+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-right' name='keuangan_jumlah[]' value='"+val_keuangan_jumlah+"'/>";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-right' name='keuangan_penyelesaian_rencana[]' value='"+val_keuangan_penyelesaian_rencana+"'/>";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control tanggal' name='keuangan_tanggal_rencana[]' value='"+val_keuangan_tanggal_rencana+"'/>";
						output += "	</td>";
						output += "</tr>";
						$(output).appendTo(".table-keuangan tbody");
						$('.tanggal').datepicker({
							format: 'dd.mm.yyyy',
							autoclose: true
						});
					});
                }								
				//detail bokar
                if (v.arr_data_bokar) {
					$.each(v.arr_data_bokar, function(a, b){
						$("input[name='bokar_nama']").val(b.nama);
						if(b.jumlah>0){
							$("input[name='bokar_jumlah']").val(numberWithCommas(parseFloat(b.jumlah).toFixed(2)));
						}else{
							$("input[name='bokar_jumlah']").val('');
						}
						if(b.penyelesaian_rencana>0){
							$("input[name='bokar_penyelesaian_rencana']").val(numberWithCommas(parseFloat(b.penyelesaian_rencana).toFixed(2)));
						}else{
							$("input[name='bokar_penyelesaian_rencana']").val('');
						}
						if(b.bokar_tanggal_rencana>0){
							$("input[name='bokar_tanggal_rencana']").val(b.tanggal_rencana_format);
						}else{
							$("input[name='bokar_tanggal_rencana']").val('');
						}
						
						$('.tanggal').datepicker({
							format: 'dd.mm.yyyy',
							autoclose: true
						});
					});
                }								
				//detail lain
                if (v.arr_data_lain) {
					let no = 0;
					$("#nodata_lain").remove();
					$.each(v.arr_data_lain, function(a, b){
						let output = "";
						output += "<tr class='row-lain lain" + no + "'>";
						output += "	<td>";
						output += "		<input type='text' class='form-control' name='lain_nama[]' value='"+b.nama+"' placeholder='Nama Item'  required='required'/>";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control angka' name='lain_jumlah[]' value='"+numberWithCommas(parseFloat(b.jumlah).toFixed(0))+"' placeholder='Jumlah'  required='required'/>";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control angka' name='lain_penyelesaian_rencana[]' value='"+numberWithCommas(parseFloat(b.penyelesaian_rencana).toFixed(0))+"' placeholder='Penyelesaian'  required='required'/>";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control tanggal' name='lain_tanggal_rencana[]' value='"+b.tanggal_rencana_format+"' placeholder='Tanggal'  required='required'/>";
						output += "	</td>";
						output += '	<td class="text-center">';
						output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
						output += "	</td>";
						output += "</tr>";
						$(output).appendTo(".table-lain tbody");
						$('.tanggal').datepicker({
							format: 'dd.mm.yyyy',
							autoclose: true
						});
					});
                }								

				
				
			});
        },
        error: function (xhr, status, error) {
            // let errorMessage = xhr.status + ': ' + xhr.statusText;
            // KIRANAKU.alert({
                // text: `Server Error, (${errorMessage})`,
                // icon: "error",
                // html: false,
                // reload: false
            // });
        },
        complete: function () {
			$('#form_penutupan_depo_detail .form-control').prop('disabled', true);
			$('#form_penutupan_depo_detail .btn').prop('disabled', true);
			$('#form_penutupan_depo_detail .btn-primary').hide();
			$('#form_penutupan_depo_detail .btn-warning').hide();
			$('#form_penutupan_depo_detail .btn-default').prop('disabled', false);
			
			//control button approve/decline		
			$('#form_penutupan_depo_detail #btn_decline').hide();
			let status_akhir = $("#form_penutupan_depo_detail input[name='status_akhir']").val();
			let level 		 = $("#form_penutupan_depo_detail input[name='level']").val();
			if(status_akhir!=1){
				if(status_akhir==999){
					$('#btn_decline').hide();
				}else{
					if(status_akhir==level){
						$('#btn_decline').show();
						$('#btn_decline').prop('disabled', false);
					}
				}
			}
			if(status_akhir==level){	
				$('#btn_approve').show();
				$('#btn_approve').prop('disabled', false);
			}
        }
    });
	
	//sampe sini get data
	
	//auto complete id_data(depo)
	$("select[name='id_depo_master']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
			// url: baseURL+'bank/transaksi/get/rekening_auto',
			url: baseURL+'depo/penutupan/get/depo_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
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
			var markup = '<div class="clearfix">'+repo.id_depo_master+' - '+repo.nama+'</div>';
			return markup;
		},
      	templateSelection: function(repo){ 
			if(repo.id_depo_master && repo.nama) 
				return repo.id_depo_master+' - '+repo.nama;
			else 
				return '';
		}
    });	
	//auto complete sampe sini
	
	//change id_depo_master
    $(document).on("change", "#id_depo_master", function(e){
		var id_depo_master	= $(this).val();
        $.ajax({
			url: baseURL + 'depo/penutupan/get/data_depo',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_depo_master: id_depo_master
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='nomor']").val(v.nomor_penutupan);
					$("select[name='jenis_depo']").val(v.jenis_depo).trigger("change.select2");
					$("select[name='pabrik']").val(v.pabrik).trigger("change.select2");
					$("input[name='pabrik']").val(v.pabrik);
                });
            }
        });
    });
	
	//triger add sdm(karyawan)
    $(document).on("click", "button[name='add_sdm']", function () {
		let idx = $("tr.row-sdm").length;
		let elem = ".row-sdm.sdm" + idx;
		let output = "";
		if ($("#nodata").length > 0) {
			$("#nodata").remove();
		}

		output += "<tr class='row-sdm sdm" + idx + "'>";
		output += "	<td>";
		output += "		<select class='form-control select2 autocomplete' name='id_biaya_sdm[]' data-placeholder='Pilih SDM' required='required'>";
		output += "			<option></option>";
		output += 			opt_sdm;
		output += "		</select>";
		output += "	</td>";
		output += "	<td>";
		output += "		<select class='form-control select2 autocomplete' name='nik[]' required='required'>";
		output += "			<option></option>";
		output += "		</select>";
		output += "	</td>";
		output += "	<td>";
		output += "		<select class='form-control select2' name='sdm_status_rencana[]' data-placeholder='Pilih Status' required='required'>";
		output += "			<option></option>";
		output += "			<option value='mutasi'>Mutasi</option>";
		output += "			<option value='rotasi'>Rotasi</option>";
		output += "			<option value='promosi'>Promosi</option>";
		output += "		</select>";
		output += "	</td>";
		output += "	<td>";
		output += "		<select class='form-control select2' name='sdm_lokasi_rencana[]' data-placeholder='Pilih Lokasi'  required='required'>";
		output += "			<option></option>";
		output += "			<option value='pabrik'>Pabrik</option>";
		output += "			<option value='depo'>Depo</option>";
		output += "		</select>";
		output += "	</td>";
		output += "	<td>";
		output += "		<input type='text' class='form-control tanggal' name='sdm_tanggal_rencana[]' value='' placeholder='Tanggal'  required='required'/>";
		output += "	</td>";
		output += '	<td class="text-center">';
		output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
		output += "	</td>";
		output += "</tr>";
		$(output).appendTo(".table-sdm tbody");

		master_karyawan(elem + " select[name='nik[]']");
		$("select[name='id_biaya_sdm[]']").select2();
		$("select[name='sdm_status_rencana[]']").select2();
		$("select[name='sdm_lokasi_rencana[]']").select2();
		$('.tanggal').datepicker({
			format: 'dd.mm.yyyy',
			autoclose: true
		});
    });
    $(document).on("click", ".remove_item", function (e) {
        if ($("tr.row-sdm").length > 1) {
            $(this).closest("tr.row-sdm").remove();
        }

        $("tr.row-sdm").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-sdm");
            $(this).addClass("sdm" + i);
        });

        if ($(".table-sdm tbody tr").length == 0) {
            show_nodata();
        }
    });
	//sampe sini add sdm
	
	//triger add asset
    $(document).on("click", "button[name='add_asset']", function () {
		let id_depo_master = $("select[name='id_depo_master']").val();
		// if(id_depo_master){
			let idx = $("tr.row-asset").length;
			let elem = ".row-asset.asset" + idx;
			let output = "";
			if ($("#nodata_asset").length > 0) {
				$("#nodata_asset").remove();
			}

			output += "<tr class='row-asset asset" + idx + "'>";
			output += "	<td>";
			output += "		<select class='form-control select2 autocomplete' name='asset[]' required='required'>";
			output += "			<option></option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='form-control text-center angka' name='asset_jumlah[]' value='' placeholder='Jumlah'  required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='asset_status_rencana[]' data-placeholder='Pilih Keterangan' required='required'>";
			output += "			<option></option>";
			output += "			<option value='mutasi'>Mutasi</option>";
			output += "			<option value='disposal'>Disposal</option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='asset_lokasi_rencana[]' data-placeholder='Pilih Lokasi' required='required'>";
			output += "			<option></option>";
			output += "			<option value='pabrik'>Pabrik</option>";
			output += "			<option value='depo'>Depo</option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='form-control tanggal' name='asset_tanggal_rencana[]' value='' placeholder='Tanggal'  required='required'/>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-asset tbody");

			master_asset(elem + " select[name='asset[]']");
			$("select[name='id_biaya_asset[]']").select2();
			$("select[name='asset_status_rencana[]']").select2();
			$("select[name='asset_lokasi_rencana[]']").select2();
			$('.tanggal').datepicker({
				format: 'dd.mm.yyyy',
				autoclose: true
			});
		// }else{
			// swal('Warning', 'Mohon isi Nama Depo Lebih Dulu.', 'warning');
		// }	
    });
    $(document).on("click", ".remove_item", function (e) {
        if ($("tr.row-asset").length > 1) {
            $(this).closest("tr.row-asset").remove();
        }

        $("tr.row-asset").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-asset");
            $(this).addClass("asset" + i);
        });

        if ($(".table-asset tbody tr").length == 0) {
            nodata_asset();
        }
    });
	//sampe sini add asset

	//triger add lain-lain
    $(document).on("click", "button[name='add_lain']", function () {
		let id_depo_master = $("select[name='id_depo_master']").val();
		// if(id_depo_master){
			let idx = $("tr.row-lain").length;
			let elem = ".row-lain.lain" + idx;
			let output = "";
			if ($("#nodata_lain").length > 0) {
				$("#nodata_lain").remove();
			}

			output += "<tr class='row-lain lain" + idx + "'>";
			output += "	<td>";
			output += "		<input type='text' class='form-control' name='lain_nama[]' value='' placeholder='Nama Item'  required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='form-control angka' name='lain_jumlah[]' value='' placeholder='Jumlah'  required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='form-control angka' name='lain_penyelesaian_rencana[]' value='' placeholder='Penyelesaian'  required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='form-control tanggal' name='lain_tanggal_rencana[]' value='' placeholder='Tanggal'  required='required'/>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-lain tbody");

			$('.tanggal').datepicker({
				format: 'dd.mm.yyyy',
				autoclose: true
			});
		// }else{
			// swal('Warning', 'Mohon isi Nama Depo Lebih Dulu.', 'warning');
		// }	
    });
    $(document).on("click", ".remove_item", function (e) {
        if ($("tr.row-lain").length > 1) {
            $(this).closest("tr.row-lain").remove();
        }

        $("tr.row-lain").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-lain");
            $(this).addClass("lain" + i);
        });

        if ($(".table-lain tbody tr").length == 0) {
            show_nodata();
        }
    });
	//sampe sini add lain-lain
	
	//approve
	$(document).on("click", "button[name='action_btn']", function(e) {
        generate_modal_action($(this));
    });
	
	//save approve
    $(document).on("click", "#save_form_action_penutupan_depo", function(e) {
        var empty_form = validate('#form_save_penutupan_depo');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($("#form_save_penutupan_depo")[0]);
				$.ajax({
					url: baseURL + 'depo/penutupan/save/approve',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
								window.location = baseURL + 'depo/penutupan/approve';
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
	
	
});


function show_nodata() {
    let col_not_found = $(".table-sdm thead th").not(".d-none").length;
    $(".table-sdm tbody").html('<tr id="nodata"><td colspan="' + col_not_found + '">No data found</td></tr>');
}
function nodata_asset() {
    let col_not_found = $(".table-asset thead th").not(".d-none").length;
    $(".table-asset tbody").html('<tr id="nodata_asset"><td colspan="' + col_not_found + '">No data found</td></tr>');
}
function show_nodata_lain() {
    let col_not_found = $(".table-lain thead th").not(".d-none").length;
    $(".table-lain tbody").html('<tr id="nodata_lain"><td colspan="' + col_not_found + '">No data found</td></tr>');
}

function input_keuangan(){
	$.ajax({
		url: baseURL+'depo/master/get/keuangan',
		type: 'POST',
		dataType: 'JSON',
		data: {
			na : 'n'
		},
		success: function(data){
			let output = "";
			let no = 0;
			$("#nodata_keuangan").remove();
			$.each(data, function(i, v){
				no++;
				output += "<tr class='row-keuangan keuangan" + no + "'>";
				output += "	<td>";
				output += "		<input type='hidden' class='form-control' name='id_keuangan[]' value='"+v.id_keuangan+"'/>";
				output += "		<input type='text' class='form-control' name='keuangan_nama[]' value='"+v.nama+"' required='required' readonly />";
				output += "	</td>";
				output += "	<td>";
				output += "		<input type='text' class='angka form-control text-right' name='keuangan_jumlah[]' value='' required='required' />";
				output += "	</td>";
				output += "	<td>";
				output += "		<input type='text' class='angka form-control text-right' name='keuangan_penyelesaian_rencana[]' value='' required='required'/>";
				output += "	</td>";
				output += "	<td>";
				output += "		<input type='text' class='form-control tanggal' name='keuangan_tanggal_rencana[]' value='' required='required'/>";
				output += "	</td>";
				output += "</tr>";
			});
			$(output).appendTo(".table-keuangan tbody");

			$('.tanggal').datepicker({
				format: 'dd.mm.yyyy',
				autoclose: true
			});
			
		}
	});
}

function master_sdm(elem) {
	$.ajax({
		url: baseURL + 'depo/penutupan/get/master_sdm',
		type: 'POST',
		dataType: 'JSON',
		data: {
			jenis_biaya_detail: 'sdm'
		},
		success: function(data) {
			var value = '';
			$.each(data, function(i, v) {
				value += '<option value="' + v.id_biaya + '">'+ v.nama + '</option>';
			});
			opt_sdm = value;
		}
	});

}
function master_karyawan(elem) {
    if ($(elem).hasClass("select2-hidden-accessible")) {
        $(elem).select2("destroy");
    }
	let pabrik = $("select[name='pabrik']").val();
    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        ajax: {
            url: baseURL + "depo/penutupan/get/master_karyawan",
            dataType: "json",
            delay: 750,
            cache: false,
            data: function (params) {
                let selected_nik = [];
				$("select[name='nik[]']").each(function (i, v) {
                    selected_nik.push($(v).val());
                });
                let data = {
                    pabrik: pabrik,
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
                    not_in_nik: selected_nik
                };

                return data;
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function (xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                KIRANAKU.alert({
                    text: `Server Error, (${errorMessage})`,
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            return '<div class="clearfix">'+ repo.nik+' - '+repo.nama_karyawan + '</div>';
        },
        templateSelection: function (repo) {
			
            let markup = "Silahkan Pilih";
            if (repo.nama_karyawan)
                markup = '<div class="clearfix">'+ repo.nik+' - '+repo.nama_karyawan + '</div>';
            if (repo.text)
                markup = repo.text;

            return markup;
        }
    });
}

function master_asset(elem) {
    if ($(elem).hasClass("select2-hidden-accessible")) {
        $(elem).select2("destroy");
    }
	let pabrik = $("select[name='pabrik']").val();
    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        ajax: {
            url: baseURL + "depo/penutupan/get/master_asset",
            dataType: "json",
            delay: 750,
            cache: false,
            data: function (params) {
                let selected_asset = [];
				$("select[name='asset[]']").each(function (i, v) {
                    selected_asset.push($(v).val());
                });
                let data = {
                    pabrik: pabrik,
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
                    not_in_asset: selected_asset
                };

                return data;
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function (xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                KIRANAKU.alert({
                    text: `Server Error, (${errorMessage})`,
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            return '<div class="clearfix">'+ repo.kode+' - '+repo.nama + '</div>';
        },
        templateSelection: function (repo) {
			
            let markup = "Silahkan Pilih";
            if (repo.nama)
                markup = '<div class="clearfix">'+ repo.kode+' - '+repo.nama + '</div>';
            if (repo.text)
                markup = repo.text;

            return markup;
        }
    });
}

function submit_order() {
    if (KIRANAKU.validate("#form-depo-input")) {
        let isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
			$("input[name='isproses']").val(1);
			const formData = new FormData($("#form-depo-input")[0]);
			$.ajax({
				// url: baseURL + "fpb/order/save/fpbxx",
				url: baseURL + "depo/transaksi/save/input",
				type: "POST",
				dataType: "JSON",
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				beforeSend: function () { },
				error: function (xhr, status, error) {
					let errorMessage = xhr.status + ': ' + xhr.statusText;
					KIRANAKU.alert({
						text: `Server Error, (${errorMessage})`,
						icon: "error",
						html: false,
						reload: false
					});
				},
				success: function (response) {
					
					if (response) {
						let icon = "error";
						let reload = false;

						if (response.sts == "OK") {
							icon = "success";
							reload = baseURL + "fpb/order/data/approve";
						}

						if (response.html) {
							KIRANAKU.alert({
								icon: icon,
								html: response.msg,
								reload: reload
							});
						} else {
							KIRANAKU.alert({
								text: response.msg,
								icon: icon,
								html: false,
								reload: reload
							});
						}
					}
				},
				complete: function () { }
			});
			
        } else {
            KIRANAKU.alert({
                text: "Silahkan tunggu proses selesai",
                icon: "warning",
                html: false,
                reload: false
            });
        }
    } else {
        KIRANAKU.alert({
            text: "Silahkan lengkapi form terlebih dahulu",
            icon: "error",
            html: false,
            reload: false
        });
    }
}

function generate_modal_action(elem) {
    $('#KiranaModals .modal-dialog').removeClass("modal-lg");
    $('#KiranaModals .modal-dialog').removeClass("modal-xl");
    $("#KiranaModals .modal-content").removeClass("bg-success");
    $("#KiranaModals .modal-content").removeClass("bg-warning");
    $("#KiranaModals .modal-content").removeClass("bg-info");
    $("#KiranaModals .modal-content").removeClass("bg-danger");
    let jenis_depo 	 = $("#form_penutupan_depo_detail input[name='jenis_depo']").val();
    let nomor 		 = $("#form_penutupan_depo_detail input[name='nomor']").val();
    let status_akhir = $("#form_penutupan_depo_detail input[name='status_akhir']").val();
    let id_depo_master = $("#form_penutupan_depo_detail input[name='id_depo_master']").val();
    let action 		 = elem.val();
    switch (action) {
        case "approve":
            $("#KiranaModals .modal-content").addClass("bg-success");
            break;
        case "decline":
            $("#KiranaModals .modal-content").addClass("bg-warning");
            break;
    }
    $("#KiranaModals .modal-title").css("text-transform", "capitalize");
    $("#KiranaModals .modal-title").html(action + " Penutupan Depo (" + nomor + ")");

    let output = '';
    output += '<div class="row">';
    output += ' <div class="col-sm-12">';
    output += '     <form role="form" id="form_save_penutupan_depo">';
    output += '         <div class="form-group">';
    output += '             <label>Komentar</label>';
    output += '             <textarea class="form-control" name="komentar_penutupan" required="required"></textarea>';
    output += '             <input type="hidden" name="nomor">';
    output += '             <input type="hidden" name="action">';
    output += '             <input type="hidden" name="status_akhir">';
    output += '             <input type="hidden" name="id_depo_master">';
    output += '             <input type="hidden" name="jenis_depo">';
    output += '         </div>';
    output += '     </form>';
    output += ' </div>';
    output += '</div>';
    $("#KiranaModals .modal-body").html(output);

    if (action == 'approve') {
        $("#KiranaModals textarea[name='komentar_penutupan']").removeAttr("required");
    } else {
        $("#KiranaModals textarea[name='komentar_penutupan']").attr("required", "required");
    }

    let output_footer = '';
    output_footer += '<div class="modal-footer">';
	if(action=='approve')
    output_footer += '  <button type="button" class="btn btn-primary" id="save_form_action_penutupan_depo">Approve</button>';
	if(action=='decline')
    output_footer += '  <button type="button" class="btn btn-danger" id="save_form_action_penutupan_depo">Decline</button>';
    output_footer += '</div>';
    if ($("#KiranaModals .modal-footer").length > 0) {
        $("#KiranaModals .modal-footer").remove();
    }
    $('#KiranaModals .modal-content').append(output_footer);

    $("#KiranaModals input[name='nomor']").val(nomor);
    $("#KiranaModals input[name='action']").val(action);
    $("#KiranaModals input[name='status_akhir']").val(status_akhir);
    $("#KiranaModals input[name='id_depo_master']").val(id_depo_master);
    $("#KiranaModals input[name='jenis_depo']").val(jenis_depo);


    $('#KiranaModals').modal({
        backdrop: 'static',
        keyboard: true,
        show: true
    });

    KIRANAKU.select2('#KiranaModals');
}


