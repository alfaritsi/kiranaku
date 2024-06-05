
$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(".post").on("click", function(e){
		var id	= $(this).data("post");
        
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan memposting data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/transaction/set_data/update/postlimbahB3',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id : id
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );

    });

	$(".delete").on("click", function(e){
    	var id	= $(this).data("delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/transaction/set_data/delete_del0/deletelimbahB3',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id : id
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );

    });

	$(".request").on("click", function(e){
    	var id	= $(this).data("request");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan request untuk menghapus data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/transaction/set_data/delete_req/requestdeletelimbahB3',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id : id
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );

    });
	
	$(".cancel_request").on("click", function(e){
    	var id	= $(this).data("request");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan membatalkan request untuk menghapus data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/transaction/set_data/delete_req/cancelrequestdeletelimbahB3',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id : id
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );

    });

	$(".edit").on("click", function(e){
    	var id	= $(this).data("edit");

    	$.ajax({
    		url: baseURL+'she/transaction/get_data/limbahB3',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				$(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					$("#id").val(v.id);

					if(v.type == "OUT" || v.type == "Out"){
						$("#divOut").html("");
						$("#divIn").html("");
						$("#divOut").append("<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>Nama Vendor :</label>"
										+"<select name='vendor' id='vendor' class='form-control select2' required>"
										+"<option value='"+ v.fk_vendor +"' selected>"+ v.nama_vendor +"</option>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>Jenis Kendaraan :</label>"
										+"<input type='text' name='jeniskendaraan' id='jeniskendaraan' style='width:100%;height:32px;padding:10px;' value='"+ v.jenis_kendaraan +"' required>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>No. Kendaraan :</label>"
										+"<input type='text' name='nomorkendaraan' id='nomorkendaraan' style='width:100%;height:32px;padding:10px;' value='"+ v.nomor_kendaraan +"' required>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>Nama Driver :</label>"
										+"<input type='text' name='driver' id='driver' style='width:100%;height:32px;padding:10px' value='"+ v.nama_driver +"' required>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='clearfix'></div>"
										+"<div class='col-md-12' style='margin-top: 20px;'>"
										+"<div class='form-group'>"
										+"<label for='limbah_b3' class='list-group-item list-group-item-info text-center' style='width: 100%;'>Detail Item</label>"
										+"</div>"
										+"</div>"

										+"<div class='clearfix'></div>"
										+"<div class='col-md-12'>"
										+"<table id='tablelist' width='100%' border='1'>"
										+"<thead>"
										+"<tr>"
										+"<th rowspan='2' width='3%' class='text-center'>Item</th>"
										+"<th rowspan='2' width='15%' class='text-center'>Jenis Limbah</th>"
										+"<th rowspan='2' width='3%' class='text-center'>Stock</th>"
										+"<th rowspan='2' width='5%' class='text-center'>Qty</th>"
										+"<th rowspan='2' width='3%' class='text-center'>UoM</th>"
										+"<th rowspan='2' width='10%' class='text-center'>Qty (Konversi Ton)</th>"
										+"<th rowspan='2'width='10%' class='text-center''>No. Manifest</th>"
										+"<th colspan='3' class='text-center'>Lampiran Manifest</th>"
										+"</tr>"
										+"<tr>"
										+"<th class='text-center'>Lembar 2</th>"
										+"<th class='text-center'>Lembar 3</th>"
										+"<th class='text-center'>Lembar 7</th>"
										+"</tr>"
										+"</thead>"
										+"<tbody>"
										+"<tr>"
										+"<td align='center'>1</td>"
										+"<td>"
										+"<select name='jenislimbahlist[]' id='jenislimbahlist' class='form-control select2' onchange='jenislimbah_keluar()' required>"
										+"<option value='"+ v.fk_limbah +"' selected>"+ v.jenis_limbah +"</option>"
										+"</select>"
										+"</td>"
										+"<td>"
										+"<input type='number' min='0' name='stoklist[]' id='stoklist' style='width:100%;height:32px;text-align:center;' value='"+ v.stok +"' readonly required>"
										+"</td>"
										+"<td>"
										+"<input type='text' name='qtylist[]' id='qtylist' style='width:100%;height:32px;text-align:center;' value='"+ v.quantity +"' required>"
										+"</td>"
										+"<td>"
										+"<input type='text' name='uomlist[]' id='uomlist' style='width:100%;height:32px;text-align:center;' value='"+ v.satuan +"' readonly required>"
										+"</td>"
										+"<td>"
										+"<input type='text' name='qtykonversilist[]' id='qtykonversilist' style='width:100%;height:32px;text-align:center;' value='"+ v.konversi_ton +" Ton' readonly required>"
										+"</td>"
										+"<td>"
										+"<input type='text' name='manifestlist[]' id='manifestlist' style='width:100%;height:32px;text-align:center;' value='"+ v.no_manifest +"' required>"
										+"</td>"
										+"<td>"
										+"<input type='file' name='lampiran1[]' id='lampiran1' style='width:100%;height:32px;'>"
										+"</td>"
										+"<td>"
										+"<input type='file' name='lampiran2[]' id='lampiran2' style='width:100%;height:32px;'>"
										+"</td>"
										+"<td>"
										+"<input type='file' name='lampiran3[]' id='lampiran3' style='width:100%;height:32px;'>"
										+"</td>"
										+"</tr>"
										+"</tbody id='bodylist'>"
										+"</table>"
										+"</div>"
										+"</div>"
										+"<div class='clearfix'></div>");
					}else{
						$("#divIn").html("");
						$("#divOut").html("");
						$("#divIn").append("<div class='col-md-4'>"
										+"<div class='form-group'>"
										+"<label>Jenis Limbah :</label>"
										+"<select name='jenislimbah' id='jenislimbah' class='form-control select2' style='width: 100%;' required onchange='jenislimbah_masuk()'>"
										+"<option value='"+ v.fk_limbah +"' selected>"+ v.jenis_limbah +"</option>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>Sumber Limbah :</label>"
										+"<select name='sumberlimbah' id='sumberlimbah' class='form-control select2' style='width: 100%;' required>"
										+"<option value='"+ v.fk_sumber_limbah +"' selected>"+ v.sumber_limbah +"</option>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>Quantity :</label>"
										+"<input type='text' name='qty' id='qty' style='width:100%;height:32px;padding:10px;text-align:right;' value='"+ v.quantity +"' required autocomplete='off'>"
										+"</div>"
										+"</div>");
					}

					// $('#pabrik').attr('disabled', false);
					document.getElementById("pabrik").value = v.fk_pabrik;
					// $('#pabrik').attr('disabled', true);
					document.getElementById("tipe").value = v.type;
					$('#tipe').attr('disabled', true);
					// document.getElementById("jenislimbah").value = v.fk_limbah;
					$("#tanggal").val(v.tanggal_transaksi);
					$('#tanggal').attr('disabled', true);
					// $("#qty").val(v.quantity);

					// if(v.type == 'IN'){
					// 	alert("In");
					// 	$('#jenislimbah').attr('disabled', true);
					// 	document.getElementById("sumberlimbah").value = v.fk_sumber_limbah;
					// 	$('#sumberlimbah').attr('disabled', true);
					// 	$('.select2').select2()
					// }else{
					// 	alert("Out");
					// 	document.getElementById("vendor").value = v.fk_vendor;
					// 	$("#jeniskendaraan").val(v.jenis_kendaraan);
					// 	$("#nomorkendaraan").val(v.nomor_kendaraan);
					// 	$("#driver").val(v.nama_driver);
					// 	$("#manifest").val(v.no_manifest);
					// 	$('.select2').select2()

					// 	jenislimbah_keluar();
					// 	addrow();
					// }
					$("#id").val(v.id);
					$('.select2').select2();
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(".reupload").on("click", function(e){
    	var id	= $(this).data("reupload");
    	// console.log('reupload');
    	$.ajax({
    		url: baseURL+'she/transaction/get_data/limbahB3',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				$(".modal-title").html("<i class='fa fa-pencil'></i> Upload Doc Limbah B3");
				$.each(data, function(i, v){
					$("#id").val(v.id);

					if(v.type == "OUT" || v.type == "Out"){
						$("#divOut").html("");
						$("#divIn").html("");
						$("#divOut").append("<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>Nama Vendor :</label>"
										+"<select name='vendor' id='vendor' class='form-control select2' required disabled='disabled'>"
										+"<option value='"+ v.fk_vendor +"' selected>"+ v.nama_vendor +"</option>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>Jenis Kendaraan :</label>"
										+"<input type='text' name='jeniskendaraan' id='jeniskendaraan' style='width:100%;height:32px;padding:10px;' value='"+ v.jenis_kendaraan +"' required disabled='disabled'>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>No. Kendaraan :</label>"
										+"<input type='text' name='nomorkendaraan' id='nomorkendaraan' style='width:100%;height:32px;padding:10px;' value='"+ v.nomor_kendaraan +"' required disabled='disabled'>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='col-md-3'>"
										+"<div class='form-group'>"
										+"<label>Nama Driver :</label>"
										+"<input type='text' name='driver' id='driver' style='width:100%;height:32px;padding:10px' value='"+ v.nama_driver +"' required disabled='disabled'>"
										+"</select>"
										+"</div>"
										+"</div>"
										+"<div class='clearfix'></div>"
										+"<div class='col-md-12' style='margin-top: 20px;'>"
										+"<div class='form-group'>"
										+"<label for='limbah_b3' class='list-group-item list-group-item-info text-center' style='width: 100%;'>Detail Item</label>"
										+"</div>"
										+"</div>"

										+"<div class='clearfix'></div>"
										+"<div class='col-md-12'>"
										+"<table id='tablelist' width='100%' border='1'>"
										+"<thead>"
										+"<tr>"
										+"<th rowspan='2' width='3%' class='text-center'>Item</th>"
										+"<th rowspan='2' width='15%' class='text-center'>Jenis Limbah</th>"
										+"<th rowspan='2' width='3%' class='text-center'>Stock</th>"
										+"<th rowspan='2' width='5%' class='text-center'>Qty</th>"
										+"<th rowspan='2' width='3%' class='text-center'>UoM</th>"
										+"<th rowspan='2' width='10%' class='text-center'>Qty (Konversi Ton)</th>"
										+"<th rowspan='2'width='10%' class='text-center''>No. Manifest</th>"
										+"<th colspan='3' class='text-center'>Lampiran Manifest</th>"
										+"</tr>"
										+"<tr>"
										+"<th class='text-center'>Lembar 2</th>"
										+"<th class='text-center'>Lembar 3</th>"
										+"<th class='text-center'>Lembar 7</th>"
										+"</tr>"
										+"</thead>"
										+"<tbody>"
										+"<tr>"
										+"<td align='center'>1</td>"
										+"<td>"
										+"<select name='jenislimbahlist[]' id='jenislimbahlist' class='form-control select2' onchange='jenislimbah_keluar()' required disabled='disabled'>"
										+"<option value='"+ v.fk_limbah +"' selected>"+ v.jenis_limbah +"</option>"
										+"</select>"
										+"</td>"
										+"<td>"
										+"<input type='number' min='0' name='stoklist[]' id='stoklist' style='width:100%;height:32px;text-align:center;' value='"+ v.stok +"' readonly required readonly='readonly'>"
										+"</td>"
										+"<td>"
										+"<input type='text' name='qtylist[]' id='qtylist' style='width:100%;height:32px;text-align:center;' value='"+ v.quantity +"' required readonly='readonly'>"
										+"</td>"
										+"<td>"
										+"<input type='text' name='uomlist[]' id='uomlist' style='width:100%;height:32px;text-align:center;' value='"+ v.satuan +"' readonly required readonly='readonly'>"
										+"</td>"
										+"<td>"
										+"<input type='text' name='qtykonversilist[]' id='qtykonversilist' style='width:100%;height:32px;text-align:center;' value='"+ v.konversi_ton +" Ton' readonly required readonly='readonly'>"
										+"</td>"
										+"<td>"
										+"<input type='text' name='manifestlist[]' id='manifestlist' style='width:100%;height:32px;text-align:center;' value='"+ v.no_manifest +"' required readonly='readonly'>"
										+"</td>"
										+"<td>"
										+"<input type='file' name='lampiran1[]' id='lampiran1' style='width:100%;height:32px;'>"
										+"</td>"
										+"<td>"
										+"<input type='file' name='lampiran2[]' id='lampiran2' style='width:100%;height:32px;'>"
										+"</td>"
										+"<td>"
										+"<input type='file' name='lampiran3[]' id='lampiran3' style='width:100%;height:32px;'>"
										+"</td>"
										+"</tr>"
										+"</tbody id='bodylist'>"
										+"</table>"
										+"</div>"
										+"</div>"
										+"<div class='clearfix'></div>");
					}

					// $('#pabrik').attr('disabled', false);
					document.getElementById("pabrik").value = v.fk_pabrik;
					// $('#pabrik').attr('disabled', true);
					document.getElementById("tipe").value = v.type;
					$('#tipe').attr('disabled', true);
					// document.getElementById("jenislimbah").value = v.fk_limbah;
					$("#tanggal").val(v.tanggal_transaksi);
					$('#tanggal').attr('disabled', true);
					// $("#qty").val(v.quantity);

					// if(v.type == 'IN'){
					// 	alert("In");
					// 	$('#jenislimbah').attr('disabled', true);
					// 	document.getElementById("sumberlimbah").value = v.fk_sumber_limbah;
					// 	$('#sumberlimbah').attr('disabled', true);
					// 	$('.select2').select2()
					// }else{
					// 	alert("Out");
					// 	document.getElementById("vendor").value = v.fk_vendor;
					// 	$("#jeniskendaraan").val(v.jenis_kendaraan);
					// 	$("#nomorkendaraan").val(v.nomor_kendaraan);
					// 	$("#driver").val(v.nama_driver);
					// 	$("#manifest").val(v.no_manifest);
					// 	$('.select2').select2()

					// 	jenislimbah_keluar();
					// 	addrow();
					// }
					$("#id").val(v.id);
					$("#type").val('reupload');
					$('.select2').select2();
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		e.preventDefault();
		
		var empty_form = validate(".form-limbahb3_inputdata");

        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);

	    		$('#pabrik').attr('disabled', false);
	    		$('#tipe').attr('disabled', false);
	    		$('#tanggal').attr('disabled', false);

		    	var formData = new FormData($(".form-limbahb3_inputdata")[0]);

				$.ajax({
					url: baseURL+'she/transaction/save/limbahB3',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						if(data.sts == 'OK'){
		                    kiranaAlert(data.sts, data.msg);
						}else{
		                    kiranaAlert(data.sts, data.msg, "error", "no");
		                    $("input[name='isproses']").val(0);
						}
					}
				});
			}else{
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		// $('#sumberlimbah').attr('disabled', true);
		// e.preventDefault();
		return false;
    });

	// $(document).on("click", "button[name='filteraction_btn']", function(e){
	// 	$("#table_trx").html("");
	// 	$("#ipal").val("");
	// 	$("#avg_sbado").html("");
	// 	$("#avg_sbasv").html("");
	// 	$("#avg_sbaph").html("");
	// 	$("#avg_sddo").html("");
	// 	$("#avg_slbsv").html("");
	// 	$("#avg_oidebit").html("");
	// 	$("#avg_oiph").html("");
	// 	$("#avg_bitrans").html("");
	// 	$("#tot_oidebit").html("");		

	// 	var empty_form = validate(".filter-airlimbah_harian");
 //        if(empty_form == 0){
	//     	var formData = new FormData($(".filter-airlimbah_harian")[0]);

	// 		$.ajax({
	// 			url: baseURL+'she/transaction/get_data/limbah_air_harian_filter',
	// 			type: 'POST',
	// 			dataType: 'JSON',
	// 			data: formData,
	// 			contentType: false,
	// 			cache: false,
	// 			processData: false,
	// 			success: function(data){
	// 				$.each(data, function(i, v){

 //                 		if(v.na === null){
 //                 			var action = "<li><a href='#' class='edit' data-edit='"+v.id+"' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
 //                          	+"<li><a href='#' class='delete' data-delete='"+v.id+"'><i class='fa fa-trash-o'></i> Hapus</a></li>"
 //                 		}else{
 //                 			var action = "<li><a href='#' class='set_active-kategori' data-activate='"+v.id+"'><i class='fa fa-check'></i> Set Aktif</a></li>"
 //                 		}

	// 			        $("#table_trx").append("<tr>"
	// 	                                 +"<td>"+v.tanggal+"</td>"     
	// 	                                 +"<td>"+v.s1+"</td>"     
	// 	                                 +"<td '"+v.red_texth1+"'>"+v.sba_do+"</td>"     
	// 	                                 +"<td>"+v.s2+"</td>"     
	// 	                                 +"<td '"+v.red_texth2+"'>"+v.sba_sv+"</td>"     
	// 	                                 +"<td>"+v.s3+"</td>"     
	// 	                                 +"<td '"+v.red_texth3+">"+v.sba_ph+"</td>"     
	// 	                                 +"<td>"+v.s4+"</td>"     
	// 	                                 +"<td '"+v.red_texth4+"'>"+v.sd_do+"</td>"     
	// 	                                 +"<td>"+v.s5+"</td>"     
	// 	                                 +"<td '"+v.red_texth5+"'>"+v.slb_sv+"</td>"     
	// 	                                 +"<td>"+v.oi_debit+"</td>"     
	// 	                                 +"<td>"+v.s6+"</td>"     
	// 	                                 +"<td '"+v.red_texth6+"'>"+v.oi_ph+"</td>"     
	// 	                                 +"<td>"+v.s7+"</td>"     
	// 	                                 +"<td '"+v.red_texth7+"'>"+v.bi_transparansi+"</td>"     
	// 	                                 // +"<td></td>"     
	// 	                                 +"<td>"
	// 	                                 	+"<div class='input-group-btn'>"
	// 	                                 		+"<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>"
	// 	                                 		+"<ul class='dropdown-menu pull-right'>"
	// 	                                 			+action
	// 											+"</ul>"
	// 				                        +"</div>"
	// 	                                 +"</td>"
	// 	                                 +"</tr>");  

	// 				});
	// 			}
	// 		});

	// 		$.ajax({
	// 			url: baseURL+'she/transaction/get_data/limbah_air_harian_ipal',
	// 			type: 'POST',
	// 			dataType: 'JSON',
	// 			data: formData,
	// 			contentType: false,
	// 			cache: false,
	// 			processData: false,
	// 			success: function(data){
	// 				$.each(data, function(i, v){
	// 			        $("#ipal").val(v.kapasitas_ipal);  
	// 					$("#avg_sbado").html(v.sba_do_avg);
	// 					$("#avg_sbasv").html(v.sba_sv_avg);
	// 					$("#avg_sbaph").html(v.sba_ph_avg);
	// 					$("#avg_sddo").html(v.sd_do_avg);
	// 					$("#avg_slbsv").html(v.slb_sv_avg);
	// 					$("#avg_oidebit").html(v.oi_debit_avg);
	// 					$("#avg_oiph").html(v.oi_ph_avg);
	// 					$("#avg_bitrans").html(v.bi_transparansi_avg);
	// 					$("#tot_oidebit").html(v.oi_debit_sum);
	// 				});
	// 			}
	// 		});
	// 	}
	// 	e.preventDefault();
	// 	return false;
 //    });

	// $("#jenislimbah").change(function() {
	$(document).on("change", "#jenislimbah", function () {		
		var pabrik = $("#pabrik").val();
		var limbah = $("#jenislimbah").val();
		let kodematerial = $(this).find(":selected").data('kode_material');
		$("#kode_material").val(kodematerial);
		
		$("#tanggal").val("");

	    //date pitcker
		$.ajax({			
			url: baseURL+'she/transaction/get_data/lasttrx',
			type: 'POST',
			dataType: 'JSON',
			// data: formData,
			data: {
				pabrik : pabrik,
				limbah : limbah,
			},
			success: function(data){
				$.each(data, function(i, v){
	            	var date = v.tanggal;
					// var date = "2018-08-01";
				    $('.datePicker2').datepicker({
				    	format: 'dd.mm.yyyy',
				        changeMonth: true,
				        changeYear: true,
				        autoclose: true,
				        startDate: generateDateFormat(date),
				        // startDate: '10.12.2021',
						endDate: '+0d'
				    });

				});
			}
		});

    });

	$("#tipe").change(function() {
	  	var pabrik = $("#pabrik").val();
		var tipe = $("#tipe").val();

		if(tipe === "IN" || tipe === ""){	
			$("#divIn").html("");
			$("#divOut").html("");
			$("#divIn").append("<div class='col-md-4'>"
								+"<div class='form-group'>"
								+"<label>Jenis Limbah :</label>"
								+"<select name='jenislimbah' id='jenislimbah' class='form-control select2' style='width: 100%;' required onchange='jenislimbah_masuk()'>"
								+"<option value='' selected>Silahkan Pilih</option>"
								+"</select>"
								+"</div>"
								+"</div>"
								+"<div class='col-md-3'>"
								+"<div class='form-group'>"
								+"<label>Sumber Limbah :</label>"
								+"<select name='sumberlimbah' id='sumberlimbah' class='form-control select2' style='width: 100%;' required>"
								+"<option value='' selected>Silahkan Pilih</option>"
								+"</select>"
								+"</div>"
								+"</div>"
								+"<div class='col-md-3'>"
								+"<div class='form-group'>"
								+"<label>Quantity :</label>"
								+"<input type='text' name='qty' id='qty' class='init' style='width:100%;height:32px;padding:10px;text-align:right;' required autocomplete='off'>"
								+"</div>"
								+"</div>");


			// Load Jenis Limbah
			$('#pabrik').attr('disabled', false);
			var formData = new FormData($(".form-limbahb3_inputdata")[0]);
			$.ajax({
				url: baseURL+'she/master/get_data/loadlimbah',
				type: 'POST',
				dataType: 'JSON',
				// data: {
				// 	pabrik2 : pabrik
				// },
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data){
					$("#jenislimbah").html("");
                    $("#jenislimbah").append("<option value=''>Silahkan Pilih</option>");  
					$.each(data, function(i, v){
                    	$("#jenislimbah").append("<option value='"+v.id+"' data-kode_material='"+v.kode_material+"'>"+v.jenis_limbah+"</option>");  
					});
				}
			});

			// $('#pabrik').attr('disabled', true);

			$.ajax({
				url: baseURL+'she/master/get_data/sumberlimbah',
				type: 'GET',
				dataType: 'JSON',
				// data: {
				// 	pabrik : pabrik
				// },
				// data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data){
					$.each(data, function(i, v){
                    	$("#sumberlimbah").append("<option value='"+v.id+"'>"+v.sumber_limbah+"</option>");  
					});
				}
			});

		}else{
			$("#divOut").html("");
			$("#divIn").html("");
			$("#divOut").append("<div class='col-md-4'>"
									+"<div class='form-group'>"
									+"<label>Nama Vendor :</label>"
									+"<select name='vendor' id='vendor' class='form-control select2' required>"
									+"<option value='' selected>Silahkan Pilih</option>"
									+"</select>"
									+"</div>"
									+"</div>"
									+"<div class='col-md-2'>"
									+"<div class='form-group'>"
									+"<label>Jenis Kendaraan :</label>"
									+"<input type='text' name='jeniskendaraan' id='jeniskendaraan' class='init' style='width:100%;height:32px;padding:10px' required autocomplete='off'>"
									+"</select>"
									+"</div>"
									+"</div>"
									+"<div class='col-md-2'>"
									+"<div class='form-group'>"
									+"<label>No. Kendaraan :</label>"
									+"<input type='text' name='nomorkendaraan' id='nomorkendaraan' class='init' style='width:100%;height:32px;padding:10px;' required autocomplete='off'>"
									+"</select>"
									+"</div>"
									+"</div>"
									+"<div class='col-md-3'>"
									+"<div class='form-group'>"
									+"<label>Nama Driver :</label>"
									+"<input type='text' name='driver' id='driver' class='init' style='width:100%;height:32px;padding:10px;' required autocomplete='off'>"
									+"</select>"
									+"</div>"
									+"</div>"
									+"<div class='clearfix'></div>"
									+"<div class='col-md-12' style='margin-top: 20px;'>"
									+"<div class='form-group'>"
									+"<label for='limbah_b3' class='list-group-item list-group-item-info text-center' style='width: 100%;'>Detail Item</label>"
									+"</div>"
									+"</div>"

									+"<div class='col-md-3'>"
									+"<div class='form-group'>"
									+"<label>Jenis Limbah :</label>"
									+"<select name='jenislimbah' id='jenislimbah' class='form-control select2' onchange='jenislimbah_keluar()'>"
									+"<option value='' selected>Silahkan Pilih</option>"
									+"</select>"
									+"</div>"
									+"</div>"
									+"<div class='col-md-1'>"
									+"<div class='form-group'>"
									+"<label style='width:100%;'>Stock :</label>"
									+"<input type='hidden' name='lastdate' id='lastdate' class='init' readonly>"
									+"<input type='text' name='stok' id='stok' class='init' style='width:100%;height:32px;text-align:center;' readonly>"
									+"</div>"
									+"</div>"
									+"<div class='col-md-2'>"
									+"<div class='form-group'>"
									+"<label style='width:100%;'>Qty :</label>"
									+"<input type='text' name='qty' id='qty' class='init' style='width:50%;height:32px;text-align:center;' onkeyup='validasi_stock()' autocomplete='off'>"
									+"<input type='text' name='uom' id='uom' class='init' style='width:50%;height:32px;text-align:center;' readonly>"
									+"</div>"
									+"</div>"
									+"<div class='col-md-2'>"
									+"<div class='form-group'>"
									+"<label style='width:100%;'>Qty Konversi :</label>"
									+"<input type='text' name='qtykonversi' id='qtykonversi' class='init' style='width:50%;height:32px;text-align:right;' readonly>"
									+"<input type='text' name='uomkonversi' id='uomkonversi' class='init' style='width:50%;height:32px;text-align:center;' readonly>"
									+"</div>"
									+"</div>"
									+"<div class='col-md-2'>"
									+"<div class='form-group'>"
									+"<label>No. Manifest :</label>"
									+"<input type='text' name='manifest' id='manifest' class='init' style='width:100%;height:32px;padding:10px;' autocomplete='off'>"
									+"</select>"
									+"</div>"
									+"</div>"
									+"<div class='col-md-1'>"
									+"<div class='form-group'>"
									+"<label></label>"
									+"<a class='btn btn-success' onclick='addrow()'>Add Item</a>"
									+"</div>"
									+"</div>"

									+"<div class='clearfix'></div>"
									+"<div class='col-md-12'>"
									+"<table id='tablelist' width='100%' border='1'>"
									+"<thead>"
									+"<tr>"
									+"<th rowspan='2' width='3%' class='text-center'>Item</th>"
									+"<th rowspan='2' width='15%' class='text-center'>Jenis Limbah</th>"
									+"<th rowspan='2' width='3%' class='text-center'>Stock</th>"
									+"<th rowspan='2' width='3%' class='text-center'>Qty</th>"
									+"<th rowspan='2' width='3%' class='text-center'>Qty (Konversi Ton)</th>"
									+"<th rowspan='2'width='10%' class='text-center'>No. Manifest</th>"
									+"<th colspan='3' class='text-center'>Lampiran Manifest</th>"
									+"</tr>"
									+"<tr>"
									+"<th class='text-center'>Lembar 2</th>"
									+"<th class='text-center'>Lembar 3</th>"
									+"<th class='text-center'>Lembar 7</th>"
									+"</tr>"
									+"</thead>"
									+"<tbody id='bodylist'>"
									+"</tbody>"
									+"</table>"
									+"</div>"
									+"</div>"
									+"<div class='clearfix'></div>");


			$('#pabrik').attr('disabled', false);
			var formData = new FormData($(".form-limbahb3_inputdata")[0]);
			$.ajax({
				url: baseURL+'she/master/get_data/loadlimbah',
				type: 'POST',
				dataType: 'JSON',
				// data: {
				// 	pabrik : pabrik
				// },
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data){
					$("#jenislimbah").html("");
                	$("#jenislimbah").append("<option value=''>Silahkan Pilih</option>");  
					$.each(data, function(i, v){
                    	$("#jenislimbah").append("<option value='"+v.id+"' data-kode_material='"+v.kode_material+"'>"+v.jenis_limbah+"</option>");                      	
					});
				}
			});

			// $('#pabrik').attr('disabled', true);

			// // Load Jenis Limbah
			// $.ajax({
			// 	url: baseURL+'she/master/get_data/limbah',
			// 	type: 'GET',
			// 	dataType: 'JSON',
			// 	// data: formData,
			// 	contentType: false,
			// 	cache: false,
			// 	processData: false,
			// 	success: function(data){
			// 		$.each(data, function(i, v){
   //                  	$("#jenislimbah").append("<option value='"+v.id+"'>"+v.jenis_limbah+"</option>");  
			// 		});
			// 	}
			// });

			$.ajax({
				url: baseURL+'she/master/get_data/mastervendor',
				type: 'POST',
				dataType: 'JSON',
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data){
                    $("#vendor").html("");  
                    $("#vendor").append("<option value=''>Silahkan Pilih</option>");  
					$.each(data, function(i, v){
                    	$("#vendor").append("<option value='"+v.id+"'>"+v.kode_vendor+" - "+v.nama_vendor+"</option>");  
					});
				}
			});

			$('.datePicker').datepicker('remove');
		    $('.datePicker').datepicker({
		    	format: 'dd.mm.yyyy',
		        changeMonth: true,
		        changeYear: true,
		        autoclose: true,
		        // startDate: new Date()
		    });


		}
		
		$('#divOut .select2 , #divIn .select2').select2();

	});

    $('.datePicker').datepicker({
    	format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
        // startDate: new Date(date)
    });


});

// function jenislimbah_masuk(){
//   	var pabrik = $("#pabrik").val();
// 	var jenislimbah = $("#jenislimbah").val();
// 	if(pabrik == "" || jenislimbah == ""){
// 		return false;
// 	}

// 	// GET ENDING STOCK
// 	$('#pabrik').attr('disabled', false);
// 	var formData = new FormData($(".form-limbahb3_inputdata")[0]);
// 	$.ajax({
// 		url: baseURL+'she/transaction/get_data/endingstock',
// 		type: 'POST',
// 		dataType: 'JSON',
// 		data: formData,
// 		contentType: false,
// 		cache: false,
// 		processData: false,
// 		success: function(data){
// 			$.each(data, function(i, v){
//             	$("#stok").val(v.stok);  
// 			});
// 		}
// 	});
// 	$('#pabrik').attr('disabled', true);

// }

function jenislimbah_masuk(){
	// $("#tanggal").val("");
	var pabrik = $("#pabrik").val();
	var jenislimbah = $("#jenislimbah").val();

    //date pitcker
	$.ajax({			
		url: baseURL+'she/transaction/get_data/endingstock',
		type: 'POST',
		dataType: 'JSON',
		// data: formData,
		data: {
			pabrik : pabrik,
			jenislimbah : jenislimbah
		},
		success: function(data){
			$.each(data, function(i, v){
            	var date = v.last_transaction;
            	// alert(date);
				if(v.stok < 0){
	                kiranaAlert("notOK", "Input tidak bisa diproses, masih ada data yang belum dipost", "warning", "no");
	                document.getElementById("jenislimbah").value = "";
	               	$(".select2").select2();
					return false;
				}
				// console.log(v.lampiran1, v.lampiran2, v.lampiran3, v.type);
				// console.log((v.lampiran1).indexOf(".pdf") , (v.lampiran2).indexOf(".pdf"), (v.lampiran3).indexOf(".pdf"))
				// string.indexOf("problems")
				/*
					nik 
						windu = 1512;
						dwj		3925;
				*/
				if( (v.lampiran1 == "" || v.lampiran2 == "" || v.lampiran3 == "") && v.type == 'OUT' ){
				// if( ((v.lampiran1).indexOf(".pdf") == -1 || (v.lampiran2).indexOf(".pdf") == -1 || (v.lampiran3).indexOf(".pdf") == -1) && v.type == 'OUT' ){
	                kiranaAlert("notOK", "Input tidak bisa diproses, masih ada data yang belum lengkap dokumen manifest", "warning", "no");
	                document.getElementById("jenislimbah").value = "";
	               	$(".select2").select2();
					return false;
				}

				$('.datePicker').datepicker('remove');
			    $('.datePicker').datepicker({
			    	format: 'dd.mm.yyyy',
			        changeMonth: true,
			        changeYear: true,
			        autoclose: true,
			        // startDate: new Date(date)
			    });

			});
		}
	});

}


function jenislimbah_keluar(){
  	var pabrik = $("#pabrik").val();
	var jenislimbah = $("#jenislimbah").val();
	// var id = $("#id").val();
	if(pabrik == "" || jenislimbah == ""){
		return false;
	}

	$("#lastdate").val("");
	$("#stok").val("");
	$("#qty").val("");
	$("#uom").val("");
	$("#qtykonversi").val("");
	$("#uomkonversi").val("");
	$("#manifest").val("");

	// GET ENDING STOCK
	$('#pabrik').attr('disabled', false);
	var formData = new FormData($(".form-limbahb3_inputdata")[0]);
	$.ajax({
		url: baseURL+'she/transaction/get_data/endingstock',
		type: 'POST',
		dataType: 'JSON',
		data: {
			pabrik : pabrik,
			jenislimbah : jenislimbah
		},
		// contentType: false,
		// cache: false,
		// processData: false,
		success: function(data){
			console.log(data);
			$.each(data, function(i, v){
				// alert(v.stok);
				if(v.stok == 0){
	                kiranaAlert("notOK", "Input tidak bisa diproses, tidak ada stock", "warning", "no");
	                document.getElementById("jenislimbah").value = "";
	               	$(".select2").select2();
					return false;
				}
				if(v.stok <= -1){
	                kiranaAlert("notOK", "Input tidak bisa diproses, masih ada data yang belum dipost", "warning", "no");
	                document.getElementById("jenislimbah").value = "";
	               	$(".select2").select2();
					return false;
				}
				if( (v.lampiran1 == "" || v.lampiran2 == "" || v.lampiran3 == "") && v.type == 'OUT' ){
	                kiranaAlert("notOK", "Input tidak bisa diproses, masih ada data yang belum lengkap dokumen manifest", "warning", "no");
	                document.getElementById("jenislimbah").value = "";
	               	$(".select2").select2();
					return false;
				}
            	$("#lastdate").val(v.last_transaction);  
            	$("#stok").val(v.stok);  
            	$("#uom").val(v.uom);  
            	$("#qtykonversi").val(v.qty_konversi);  
            	$("#uomkonversi").val("Ton");  
			});
		}
	});

	$('#pabrik').attr('disabled', true);
}

function validasi_stock(){
	if($("#qty").val() != ""){
		if($("#tipe").val() == "OUT"){
		  	var stok = $("#stok").val();
			var qty = $("#qty").val();
			if(parseInt(stok) < parseInt(qty) || qty == ""){
                kiranaAlert("notOK", "Input tidak bisa diproses, Stock tidak mencukupi", "warning", "no");
				$("#qty").val("")
				return false;
			}
		}
	}
	
}

               

function addrow(){
	var jenislimbah = $("#jenislimbah").val();
	var stok = $("#stok").val();
	var qty = $("#qty").val();
	var qtykonversi = $("#qtykonversi").val();
	var manifest = $("#manifest").val();

	if(jenislimbah == "" || stok == "" || qty == "" || qtykonversi == "" || manifest == ""){
        kiranaAlert("notOK", "Cek kembali data yang diinput", "warning", "no");
		return false;
	}

	// var tableOpts = {
	//   "sPaginationType": "full_numbers",
	//   "sScrollY": "150px",
	//   "bFilter": false,
	//   "fnCreatedRow": function (nRow, aData, iDataIndex) {
	//       $(nRow).attr('id', aData[0]);          
	      
	//       var txtBox = $(nRow).find("input[type=text]");   
	//       txtBox.attr('id', 'text-' + aData[0]);
	      
	//       var checkBox = $(nRow).find("input[type=checkbox]");            
	//       checkBox.attr('id', 'check-' + aData[0]);
	//   }
	// }   

	// var tablelist = $('#tablelist').dataTable(tableOpts); 
  	var tablelist = $('#tablelist').dataTable();
  	var sel = document.getElementById("jenislimbah");
	var textlastdate = '<input type="hidden" class="lastdatelist" nama="lastdatelist[]" value="' + $("#lastdate").val() + '" readonly>';
	var limbah = '<input type="text" value="' + sel.options[sel.selectedIndex].text + '" style="width:100%;height:32px;text-align:center;" readonly>';
    var textjenislimbah = '<input type="hidden" id="jenislimbahlist" name="jenislimbahlist[]" value="' + $("#jenislimbah").val() + '" style="width:100%;height:32px;" readonly>';
    var textstock = '<input type="hidden" id="stoklist" name="stoklist[]" value="' + $("#stok").val() + '" style="width:100%;height:32px;" readonly>';
    var stock = '<input type="text" value="' + $("#stok").val() + ' ' + $("#uom").val() + '" style="width:100%;height:32px;text-align:center;" readonly>';
    var textqty = '<input type="hidden" id="qtylist" name="qtylist[]" value="' + $("#qty").val() + '" style="width:100%;height:32px;" readonly>';
    var qty = '<input type="text" value="' + $("#qty").val() + ' ' + $("#uom").val() + '" style="width:100%;height:32px;text-align:center;" readonly>';
    var textuom = '<input type="hidden" id="uomlist" name="uomlist[]" value="' + $("#uom").val() + '" style="width:100%;height:32px;" readonly>';
    var textqtykonversi = '<input type="hidden" id="qtykonversilist" name="qtykonversilist[]" value="' + $("#qtykonversi").val() + '" style="width:100%;height:32px;" readonly>';
    var qtykonversi = '<input type="text" value="' + $("#qtykonversi").val() + ' ' + $("#uomkonversi").val() + '" style="width:100%;height:32px;text-align:center;" readonly>';
    var textuomkonversi = '<input type="hidden" id="uomkonversilist" name="uomkonversilist[]" value="' + $("#uomkonversi").val() + '" style="width:100%;height:32px;" readonly>';
    var textmanifest = '<input type="text" id="manifestlist" name="manifestlist[]" value="' + $("#manifest").val() + '" style="width:100%;height:32px;text-align:center;" readonly>';
    var textlembar2 = '<input type="file" id="lampiran1" name="lampiran1[]" style="width:100%;height:32px;" >';
    var textlembar3 = '<input type="file" id="lampiran2" name="lampiran2[]" style="width:100%;height:32px;" >';
    var textlembar7 = '<input type="file" id="lampiran3" name="lampiran3[]" style="width:100%;height:32px;">';
    var item = parseInt($('input[name="jenislimbahlist[]"]').length) + 1;
    var textitem = '<input type="text" value="' + item + '" style="width:100%;height:32px;text-align:center;" readonly>';
    
    tablelist.fnAddData([textitem, limbah + textjenislimbah, stock +  textlastdate + textstock, qty + textqty + textuom, qtykonversi + textqtykonversi + textuomkonversi, textmanifest, textlembar2, textlembar3, textlembar7]); 

    $('#tablelist').DataTable().destroy();

	$("#jenislimbah option:selected").remove();

	// init
	document.getElementById("jenislimbah").value = "";
	$(".select2").select2();
	$("#lastdate").val(""); 
	$("#stok").val(""); 
	$("#qty").val(""); 
	$("#uom").val(""); 
	$("#qtykonversi").val(""); 
	$("#uomkonversi").val(""); 
	$("#manifest").val(""); 

// alert($("#lastdatelist").val());
	var dateprev = null;
	$('.lastdatelist').each(function(i,element){
		if(dateprev == null){
			dateprev = moment($(element).val());
		}else{
			var currentdate = moment($(element).val());
			if(dateprev.isSameOrBefore(currentdate)){
				dateprev = currentdate;
			}
		}
		// alert($(element).val());
	});
 	// console.log(dateprev);

	// $('.datePicker').datepicker('remove');
 //    $('.datePicker').datepicker({
 //    	format: 'dd.mm.yyyy',
 //        changeMonth: true,
 //        changeYear: true,
 //        autoclose: true,
 //        startDate: new Date(dateprev)
 //    });

}

function init(){
	if( $("#tipe").val() == "IN" || $("#tipe").val() == ""){
		document.getElementById("sumberlimbah").value = "";
	}else{
		document.getElementById("vendor").value = "";
	}
	document.getElementById("tipe").value = "";
	$(".select2").select2();
	$(".init").val("");
	$("#bodylist").html("");
	$('#tanggal').attr('disabled', false);
	$('#tipe').attr('disabled', false);
	$("#tipe").change();
	document.getElementById("jenislimbah").value = "";
	$("#type").val('');
}

function filtersubmit(){
	var pabrik = $("#filterpabrik").val();
	var from = $("#filterfrom").val();
	var to = $("#filterto").val();
	var filter_pabrik = $("#filter_pabrik").val();
	var filter_status = $("#filter_status").val();

	if(filter_pabrik != "" && filter_status != ""){
    	$('#filterform').submit();
	}

}


