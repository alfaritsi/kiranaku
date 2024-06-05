
$(document).ready(function(){
	//export to excel
    $(document).on('click', '#excel_button', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'she/transaction/excel/limbah_air_harian/'
            +'?pabrik='+$("#filterpabrik").val()
            +'&periode='+$('#filterperiode').val()
        );
    })
	
	$("#bakaerasi_ph").keyup(function() {
	  	var value = $(this).val();
		if((value<0)||(value>14)){
			alert('Range yang diperbolehkan adalah 0-14');	
			$("#bakaerasi_ph").val('');
		}
    });
	$("#ipal_ph").keyup(function() {
	  	var value = $(this).val();
		if((value<0)||(value>14)){
			alert('Range yang diperbolehkan adalah 0-14');	
			$("#ipal_ph").val('');
		}
    });

    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-jenis").on("click", function(e){
    	var id_mjenis	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'she/limbah_air/set_data/activate/parameter',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mjenis : id_mjenis
			},
			success: function(data){
				if(data.sts == 'OK'){
                    kiranaAlert(data.sts, data.msg);
				}else{
                    kiranaAlert(data.sts, data.msg, "error", "no");
				}
			}
		});
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
			    		url: baseURL+'she/limbah_air/set_data/delete_del0/limbah_air_harian',
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

	// $(".edit").on("click", function(e){
	$(document).on("click", ".edit", function(e){
    	var id	= $(this).data("edit");
    	$.ajax({
    		url: baseURL+'she/transaction/get_data/limbah_air_harian',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Parameter");
				$.each(data, function(i, v){
					// alert(v.fk_pabrik);
					document.getElementById("pabrik").value = v.fk_pabrik;
					$('.select2').select2();
					$("#tanggal").val(v.tanggal);
					$("#bakaerasi_do").val(v.sba_do);
					$("#bakaerasi_sv").val(v.sba_sv);
					$("#bakaerasi_ph").val(v.sba_ph);
					$("#denitrifikasi_do").val(v.sd_do);
					$("#lumpurbalik_sv").val(v.slb_sv);
					$("#ipal_debit").val(v.oi_debit);
					$("#ipal_ph").val(v.oi_ph);
					$("#bi_trans").val(v.bi_transparansi);
					$("#ipal_debit_standar").val(v.oi_debit_standar);
					// $("#kategori").val(v.fk_kategori);
					document.getElementById("kategori").value = v.fk_kategori;
					$('.select2').select2();

					$("#id").val(v.id);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-airlimbah_harian");
        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-airlimbah_harian")[0]);

				$.ajax({
					url: baseURL+'she/transaction/save/limbah_air_harian',
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
		e.preventDefault();
		return false;
    });


	$("#bakaerasi_do").keyup(function() {
	  	var value = $(this).val();
	  	$("#bakaerasi_do").val(value.replace(",","."));

    });
	$("#bakaerasi_sv").keyup(function() {
	  	var value = $(this).val();
	  	$("#bakaerasi_sv").val(value.replace(",","."));

    });
	$("#bakaerasi_ph").keyup(function() {
	  	var value = $(this).val();
	  	$("#bakaerasi_ph").val(value.replace(",","."));

    });
	$("#denitrifikasi_do").keyup(function() {
	  	var value = $(this).val();
	  	$("#denitrifikasi_do").val(value.replace(",","."));

    });
	$("#lumpurbalik_sv").keyup(function() {
	  	var value = $(this).val();
	  	$("#lumpurbalik_sv").val(value.replace(",","."));

    });
	$("#ipal_debit").keyup(function() {
	  	var value = $(this).val();
	  	$("#ipal_debit").val(value.replace(",","."));

    });
	$("#ipal_ph").keyup(function() {
	  	var value = $(this).val();
	  	$("#ipal_ph").val(value.replace(",","."));

    });
	$("#bi_trans").keyup(function() {
	  	var value = $(this).val();
	  	$("#bi_trans").val(value.replace(",","."));

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
	// 	                                 +"<td align='center'>"+v.s1+"</td>"     
	// 	                                 +"<td align='right' '"+v.red_texth1+"'>"+v.sba_do+"</td>"     
	// 	                                 +"<td align='center'>"+v.s2+"</td>"     
	// 	                                 +"<td align='right' '"+v.red_texth2+"'>"+v.sba_sv+"</td>"     
	// 	                                 +"<td align='center'>"+v.s3+"</td>"     
	// 	                                 +"<td align='right' '"+v.red_texth3+">"+v.sba_ph+"</td>"     
	// 	                                 +"<td align='center'>"+v.s4+"</td>"     
	// 	                                 +"<td align='right' '"+v.red_texth4+"'>"+v.sd_do+"</td>"     
	// 	                                 +"<td align='center'>"+v.s5+"</td>"     
	// 	                                 +"<td align='right' '"+v.red_texth5+"'>"+v.slb_sv+"</td>"     
	// 	                                 +"<td align='right'>"+v.oi_debit+"</td>"     
	// 	                                 +"<td align='center'>"+v.s6+"</td>"     
	// 	                                 +"<td align='right' '"+v.red_texth6+"'>"+v.oi_ph+"</td>"     
	// 	                                 +"<td align='right'>"+v.s7+"</td>"     
	// 	                                 +"<td align='right' '"+v.red_texth7+"'>"+v.bi_transparansi+"</td>"     
	// 	                                 // +"<td></td>"     
	// 	                                 +"<td align='center'>"
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

    //date pitcker
    $('.monthPicker').datepicker({
        startView: 'year',
        minViewMode: "months",
        format: 'mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });

    $('.datePicker').datepicker({
        format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });
    $('.datePicker_7').datepicker({
        format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
		// startDate: '-7d'
    });

  //   $(".monthPicker").datepicker({
  //   	alert();
  //       dateFormat: 'mm-yy',
  //       changeMonth: true,
  //       changeYear: true,
  //       showButtonPanel: true,
		// //yearRange: '2016:<?php //echo date('Y');?>',
  //       yearRange: '<?php echo date('Y') - 1;?>:<?php echo date('Y');?>',
  //       onClose: function(dateText, inst) {
  //           var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
  //           var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
  //           $(this).val($.datepicker.formatDate('mm-yy', new Date(year, month, 1)));
  //       }
  //   });
  //   $(".monthPicker").focus(function () {
  //       $(".ui-datepicker-calendar").hide();
  //       $("#ui-datepicker-div").position({
  //           my: "center top",
  //           at: "center bottom",
  //           of: $(this)
  //       });
  //   });
  
    //open modal for imp    
	$(document).on("click", "#imp_button", function(e){
		$('#imp_modal').modal('show');
	});
	//imp
	$(document).on("click", "button[name='action_btn_imp']", function(e){
		var empty_form = validate('.form-transaksi-harian-imp');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-harian-imp")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'she/transaction/save/import_harian',
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
  	
  	// change kategori
  	$(document).on("change", "#kategori", function(e){
		
		var thiselem 	= $(this);
		var valuex 		= thiselem.val();
		if(valuex == "2"){
   			$("#show_debit").show();
   		} else {
   			$("#show_debit").hide();
   		}
		
		if(valuex == "2"){
			console.log("1");
			$("#bakaerasi_do").attr('required',true);
			$("#bakaerasi_sv").attr('required',true);
			$("#bakaerasi_ph").attr('required',true);
			$("#denitrifikasi_do").attr('required',true);
			$("#lumpurbalik_sv").attr('required',true);
			$("#ipal_debit").attr('required',true);
			$("#ipal_ph").attr('required',true);
			$("#bi_trans").attr('required',true);
			$("#ipal_debit_standar").attr('required',true);

		} else if(valuex == "8"){
			console.log("2");
			$("#bakaerasi_do").removeAttr('required');
			$("#bakaerasi_sv").removeAttr('required');
			$("#bakaerasi_ph").removeAttr('required');
			$("#denitrifikasi_do").removeAttr('required');
			$("#lumpurbalik_sv").removeAttr('required');
			$("#ipal_debit").removeAttr('required');
			$("#ipal_ph").removeAttr('required');
			$("#bi_trans").removeAttr('required');
			$("#ipal_debit_standar").removeAttr('required');
			$("#bakaerasi_do").val('0');
			$("#bakaerasi_sv").val('0');
			$("#bakaerasi_ph").val('0');
			$("#denitrifikasi_do").val('0');
			$("#lumpurbalik_sv").val('0');
			$("#ipal_debit").val('0');
			$("#ipal_ph").val('0');
			$("#bi_trans").val('0');
			$("#ipal_debit_standar").val('0');
		}
		
	});
	
	//change
	$(document).on("change", "#pabrik, #tanggal", function(){
		var id_pabrik  	= $("#pabrik").val();
		var tanggal 	= $("#tanggal").val();
		// $("input[name='produksi_sir']").val('aaa');
		if((id_pabrik!='')&&(tanggal!='')){
			$.ajax({
				url: baseURL+'she/transaction/get/produksi_sir',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_pabrik : id_pabrik,
					tanggal : tanggal
				},
				success: function(data){
					$.each(data, function(i,v){
						// $("#id_item_spec").val(v.id_item_spec);
						if(v.produksi_sir==null){
							$("input[name='produksi_sir']").val(0);
						}else{
							$("input[name='produksi_sir']").val(v.produksi_sir);
						}
						
					});
				}
			});
		}
    });
    // //description
    // $(document).on("keyup", "#debit_harian", function() {
        // var debit_harian = $("#debit_harian").val();
        // var produksi_sir = $("#produksi_sir").val();
		// var satuan_produksi =  parseFloat(produksi_sir)/parseFloat(debit_harian);
		// $("input[name='satuan_produksi']").val(parseFloat(satuan_produksi).toFixed(2));
    // });
    //description
    $(document).on("keyup", "#ipal_debit", function() {
        var ipal_debit = $("#ipal_debit").val();
        var produksi_sir = $("#produksi_sir").val();
		if(produksi_sir!=0){
			var satuan_produksi =  parseFloat(ipal_debit)/parseFloat(produksi_sir);
		}else{
			var satuan_produksi =  0;
		}
		$("input[name='satuan_produksi']").val(parseFloat(satuan_produksi).toFixed(2));
    });
	

});


function filtersubmit(){
	var pabrik = $("#filterpabrik").val();
	var periode = $("#filterperiode").val();
	var kategori = $("#filterkategori").val();
	$("#avg_sbado").html("");
	$("#avg_sbasv").html("");
	$("#avg_sbaph").html("");
	$("#avg_sddo").html("");
	$("#avg_slbsv").html("");
	$("#avg_oidebit").html("");
	$("#avg_oiph").html("");
	$("#avg_bitrans").html("");
	$("#tot_oidebit").html("");	
	if(pabrik != "" && periode != "" && kategori != ""){
		$('#filterform').submit();
	}
}
