
$(document).ready(function(){
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
			    		url: baseURL+'she/transaction/set/kualitasudara',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id 	: id
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
    		url: baseURL+'she/transaction/get_data/kualitasudara',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				$(".title-form").html("Edit Parameter");
			    $("#parameter_hasiluji").html("");
				$.each(data, function(i, v){
					document.getElementById("pabrik").value = v.fk_pabrik;
					document.getElementById("kategori").value = v.fk_kategori;
					document.getElementById("jenis").value = v.fk_jenis;
					$('.select2').select2()
					$("#tglsampling").val(v.tanggal_sampling);
					$("#tglanalisa").val(v.tanggal_analisa);
					if(v.fk_kategori == 3){
						if($("#emisiform").html() === ""){
							$("#emisiform").append("<div class='form-group'>"
												+"<label>Laju Air :</label>"
												+"<input type='text' name='laju_air' id='laju_air' style='width:100%;height:32px;padding:10px;text-align:right;' required autocomplete='off'>"
												+"</div>"
												+"<div class='form-group'>"
												+"<label>Jam Operasi :</label>"
												+"<input type='text' name='jam_operasi' id='jam_operasi' style='width:100%;height:32px;padding:10px;text-align:right;' required autocomplete='off'>"
												+"</div>"
												);
						}
						$("#laju_air").val(v.laju_air);
						$("#jam_operasi").val(v.jam_operasi);
					}
			        $("#parameter_hasiluji").append("<tr>"
	                                 +"<td>"
	                                 +"<input type='text' style='width:100%;height:32px;padding:10px;' id='parameter' nama='parameter[]' value='"+v.parameter+"' readonly>"
	                                 +"<input type='hidden' id='idparam' nama='idparam[]' class='idparam' value='"+v.fk_parameter+"'>"
	                                 +"</td>"     
	                                 +"<td>"
	                                 +"<input type='text' style='width:100%;height:32px;padding:10px;text-align:right;' id='hasiluji' nama='hasiluji[]' class='hasiluji' value='"+v.hasil_uji+"' autocomplete='off'>"
	                                 +"</td>"     
	                                 +"</tr>");  

					$("#id").val(v.id);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){	
		e.preventDefault();
	
		var empty_form = validate(".kualitasudara-form");
        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses >= 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".kualitasudara-form")[0]);


// alert($(".idparam").length);


				// var parameter = document.getElementById('idparam').length;
				// var paramCount = $(".idparam").length;

				$(".hasiluji").each(function(index,element){
					if($(element).val() > 0){
					    formData.append("hasiluji[]", $(element).val());
					    formData.append("idparam[]", $(element).closest('tr').find('.idparam').val());		
					}
				});

// alert(idparam);
				// console.log(formData);
                    
//                 }

// 				var ujiCount = $("#hasiluji").length;
// 				for (var i = 0; i < ujiCount; i++)
//                 {
//                     var hasiluji = uji[i];
//                     formData.append("hasiluji[]", hasiluji);
//                 }

				$.ajax({
					url: baseURL+'she/transaction/save/kualitasudara',
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
						}
					}
				});
			}else{
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		return false;
    });

	// $(document).on("click", "button[name='filteraction_btn']", function(e){
	// 	// $("#table_trx").html("");
	// 	$("#table_trx tr").remove();
	// 	var empty_form = validate(".filter-kualitasudara");
 //        if(empty_form == 0){
	//     	var formData = new FormData($(".filter-kualitasudara")[0]);

	// 		$.ajax({
	// 			url: baseURL+'she/transaction/get_data/filter_kualitasudara',
	// 			type: 'POST',
	// 			dataType: 'JSON',
	// 			data: formData,
	// 			contentType: false,
	// 			cache: false,
	// 			processData: false,
	// 			success: function(data){
	// 				$.each(data, function(i, v){

 //                 		if(v.na === null){
 //                 			alert(v.id);
 //                 			var action = "<li><a href='#' class='edit' data-edit='"+v.id+"' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
 //                          	+"<li><a href='#' class='delete' data-delete='"+v.id+"'><i class='fa fa-trash-o'></i> Hapus</a></li>"
 //                 		}else{
 //                 			var action = "<li><a href='#' class='set_active-kategori' data-activate='"+v.id+"'><i class='fa fa-check'></i> Set Aktif</a></li>"
 //                 		}
	// 			        $("#table_trx").append("<tr>"
	// 	                                 +"<td align='center'>"+v.tanggal_sampling+"</td>"     
	// 	                                 +"<td align='center'>"+v.tanggal_analisa+"</td>"     
	// 	                                 +"<td>"+v.kategori+"</td>"     
	// 	                                 +"<td>"+v.jenis+"</td>"     
	// 	                                 +"<td>"+v.parameter+"</td>"
	// 	                                 +"<td align='right'>"+v.hasil_uji+"</td>"     
	// 	                                 +"<td align='right'>"+v.laju_air+"</td>"     
	// 	                                 +"<td align='right'>"+v.jam_operasi+"</td>"     
	// 	                                 +"<td align='center'><a title='Lihat file lampiran 1' target='_blank' href='"+baseURL+v.lampiran+"'><i class='fa fa-download'></i></a></td>"     
	// 	                                 +"<td>"
	// 	                                 	+"<div class='input-group-btn'>"
	// 	                                 		+"<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>"
	// 	                                 		+"<ul class='dropdown-menu pull-right'>"
	// 	                                 			+action
	// 											+"</ul>"
	// 				                        +"</div>"
	// 	                                 +"</td>"
	// 	                                 +"</tr>");  

	// 			        $('#tablelist').dataTable();

	// 				});
	// 			}
	// 		});
	// 		// $("#mytatable").datatable();
	// 	}
	// 	e.preventDefault();
	// 	return false;
 //    });


	// $("#id_lokasi,#filterpabrik").change(function() {
	// 	// $("#table_trx").html("");
	// 	$("#table_trx").html("");
	// 	var empty_form = validate(".filter_kualitasudara");
 //        if(empty_form == 0){
	//     	var formData = new FormData($(".filter_kualitasudara")[0]);

	// 		$.ajax({
	// 			url: baseURL+'she/transaction/get_data/filter_kualitasudara',
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
	// 	                                 +"<td>"+v.tanggal_sampling+"</td>"     
	// 	                                 +"<td>"+v.tanggal_analisa+"</td>"     
	// 	                                 +"<td>"+v.kategori+"</td>"     
	// 	                                 +"<td>"+v.jenis+"</td>"     
	// 	                                 +"<td>"+v.parameter+"</td>"     
	// 	                                 +"<td>"+v.hasil_uji+"</td>"     
	// 	                                 +"<td>"+v.laju_air+"</td>"     
	// 	                                 +"<td>"+v.jam_operasi+"</td>"     
	// 	                                 +"<td align='center'><a title='Lihat file lampiran 1' target='_blank' href='"+v.lampiran+"'><i class='fa fa-download'></i></a></td>"     
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
	// 		// $("#mytatable").datatable();
	// 	}
	// 	e.preventDefault();
	// 	return false;
 //    });


	$("#filterpabrik, #filterkategori").change(function() {
		var pabrik = $("#filterpabrik").val();
		var kategori = $("#filterkategori").val();
		
		if(pabrik === "" || kategori === ""){
			return false;
		}		
		var formData = new FormData($(".filter-kualitasudara")[0]);		
		$.ajax({
			url: baseURL+'she/transaction/get_data/kualitasudara_filterjenis',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function(data){
				$("#filterjenis").html("");
				$("#filterjenis").append("<option value=''> Silahkan Pilih</option>");  
				$.each(data, function(i, v){
			        $("#filterjenis").append("<option value='"+v.id+"'>"+v.jenis+"</option>");  
				});
			}
		});
    });

	$("#pabrik, #kategori").change(function() {
		var pabrik = $("#pabrik").val();
		var kategori = $("#kategori").val();
		var jenis = $("#jenis").val();

		if(pabrik != "" && kategori != ""){
			$.ajax({
				url: baseURL+'she/transaction/get_data/kualitasudara_filterjenis',
				type: 'POST',
				dataType: 'JSON',
				data: {
					filterpabrik : pabrik,
					filterkategori : kategori
				},
				// contentType: false,
				// cache: false,
				// processData: false,
				success: function(data){
					$("#jenis").html("");
					$("#jenis").append("<option value=''> Silahkan Pilih</option>");  
					$.each(data, function(i, v){
				        $("#jenis").append("<option value='"+v.id+"'>"+v.jenis+"</option>");  
					});
				}
			});
		}		

		if($("#kategori").val() === "3"){
			if($("#emisiform").html() === ""){
				$("#emisiform").append("<div class='form-group'>"
									+"<label>Laju Air :</label>"
									+"<input type='text' name='laju_air' id='laju_air' style='width:100%;height:32px;padding:10px;text-align:right;' required autocomplete='off'>"
									+"</div>"
									+"<div class='form-group'>"
									+"<label>Jam Operasi :</label>"
									+"<input type='text' name='jam_operasi' id='jam_operasi' style='width:100%;height:32px;padding:10px;text-align:right;' required autocomplete='off'>"
									+"</div>"
									);
			}
		}else{
			$("#emisiform").html("");
		}

		if($("#pabrik").val() == "" || $("#kategori").val() == "" || $("#jenis").val() == ""){
			return false;
		}
		$("#table-param").html("");
		
		var formData = new FormData($(".kualitasudara-form")[0]);		
		$.ajax({
			url: baseURL+'she/transaction/get_data/filter_kualitasudara_parameter',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function(data){
				$.each(data, function(i, v){
			        $("#table-param").append("<tr>"
	                                 +"<td>"
	                                 +"<input type='text' style='width:100%;height:32px;padding:10px;' id='parameter' nama='parameter[]' value='"+v.parameter+"' readonly>"
	                                 +"<input type='hidden' id='idparam"+v.id+"' nama='idparam[]' class='idparam' value='"+v.id+"'>"
	                                 +"</td>"     
	                                 +"<td>"
	                                 +"<input type='text' style='width:100%;height:32px;padding:10px;text-align:right;' id='hasiluji"+v.id+"' nama='hasiluji[]' class='hasiluji' autocomplete='off'>"
	                                 +"</td>"     
	                                 +"</tr>");  

				});
			}
		});
    });

	$("#jenis").change(function() {
		if($("#pabrik").val() == "" || $("#kategori").val() == "" || $("#jenis").val() == ""){
			return false;
		}
		
		var formData = new FormData($(".kualitasudara-form")[0]);		
		$.ajax({
			url: baseURL+'she/transaction/get_data/filter_kualitasudara_parameter',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function(data){
				$("#parameter_hasiluji").html("");
				$.each(data, function(i, v){
			        $("#parameter_hasiluji").append("<tr>"
											+"<td>"
											+"<input type='text' style='width:100%;height:32px;padding:10px;' id='parameter' nama='parameter[]' value='"+v.parameter+"' readonly>"
											+"<input type='hidden' id='idparam"+v.id+"' nama='idparam[]' value='"+v.id+"' class='idparam'>"
											+"</td>"     
											+"<td>"
											+"<input type='number' style='width:100%;height:32px;padding:10px;text-align:right;' id='hasiluji"+v.id+"' nama='hasiluji[]' class='hasiluji' autocomplete='off'>"
											+"</td>"     
											+"</tr>");
				});
			}
		});
    });

    //date pitcker
    $('.datePicker').datepicker({
        format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
        // startDate: new Date()
    });

    //date pitcker
    $('.monthPicker').datepicker({
        startView: 'year',
        minViewMode: "months",
        format: 'mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
        // startDate: new Date()
    });


  //   $(".monthPicker").datepicker({
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

});

function filtersubmit(){
    var pabrik = $("#filterpabrik").val();
    var kategori = $("#filterkategori").val();
    var jenis = $("#filterjenis").val();
    var from = $("#from").val();
    var to = $("#to").val();
    
    if(pabrik != "" && kategori != "" && jenis != "" && from != "" && to != ""){
        $('#filterform').submit();
    }
}
