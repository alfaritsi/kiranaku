$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".add").on("click", function(e){
    	// var gsber = $(this).data("add");
    	var gsber = $(this).data("add");
    	$(".modal-title").html("<i class='fa fa-plus-circle'></i> Add Ticket");

		$("#modal_ticket").html("");
		$("#modal_footer_ticket").html("");

		// if(gsber != "KMTR"){
		// 	$("#modal_ticket").append("<div class='form-group'>"
		// 					+"<label class='col-md-4'>User</label>"
		// 					+"<div class='col-md-8'>"
		// 					+"<select data-placeholder='Pilih User' name='user' id='user' class='form-control select2' style='width: 100%;' required>"
		// 					+"<option></option>"
		// 					+"</select>"
		// 					+"</div>"
		// 					+"</div>"
		// 					+"<div class='form-group'>"
		// 					+"<label class='col-md-4'>Location</label>"
		// 					+"<div class='col-md-8'>"
		// 					+"<input type='text' name='lokasi' id='lokasi' class='form-control' required readonly value='"+gsber+"'>"
		// 					+"</div>"
		// 					+"</div>");
		// }
	
		$("#modal_ticket").append("<div class='form-group'>"
						+"<label class='col-md-4'>Title Ticket</label>"
						+"<div class='col-md-8'>"
						+"<input type='text' name='title' id='title' class='form-control' required autocomplete='off'>"
						+"</div>"
						+"</div>"
						+"<div class='form-group'>"
						+"<label class='col-md-4'>Category</label>"
						+"<div class='col-md-8'>"
						+"<select data-placeholder='Pilih Kategori' name='kategori' id='kategori' class='form-control select2' style='width: 100%;' required>"
						+"<option></option>"
						+"</select>"
						+"</div>"
						+"</div>"
						+"<div class='form-group'>"
						+"<label class='col-md-4'>Sub Category</label>"
						+"<div class='col-md-8'>"
						+"<select data-placeholder='Pilih Kategori' name='subkategori' id='subkategori' class='form-control select2' style='width: 100%;' required>"
						+"<option value=''></option>"
						+"</select>"
						+"</div>"
						+"</div>"
						+"<div class='form-group'>"
						+"<label class='col-md-4'>Note</label>"
						+"<div class='col-md-8'>"
						+"<textarea name='keterangan' id='keterangan' class='form-control' rows='3'></textarea>"
						+"</div>"
						+"</div>"
						+"<div class='form-group'>"
						+"<label class='col-md-4'>Upload Screenshoot</label>"
						+"<div class='col-md-8'>"
						+"<input type='file' name='file' id='file' class='file'>"
						+"</div>"
						+"</div>");

		$("#modal_footer_ticket").append("<input type='hidden' name='id' id='id' style='width:100%'>"
						+"<button type='reset' class='btn btn-default'> <i class='fa fa-undo'></i> Reset</button>"
						+"<button type='submit' name='action_btn' class='btn btn-success'> <i class='fa fa-save'></i> Save</button>");

		$("#id").val("");

    	$.ajax({
    		url: baseURL+'skynet/master/get_data/user',
			type: 'POST',
			dataType: 'JSON',
			data: {
				gsber : gsber
			},
			success: function(data){
				// console.log(data);
				$.each(data, function(i, v){
                	$("#user").append("<option value='"+v.id_user+"'>"+v.nama+" ("+v.nik+")</option>");  
				});
				$(".select2").select2();
			}
		});

    	$.ajax({
    		url: baseURL+'skynet/master/get_data/category',
			type: 'POST',
			dataType: 'JSON',
			data: {
			},
			success: function(data){
				var output 	= '';
				output += "<option value=''></option>";
				$.each(data, function (i, v) {
					output += "<option value='"+v.id_hd_kategori+"'>"+v.kategori+"</option>";
				});
				$("#kategori").html(output);				
				$(".select2").select2();
				$(".select2").select2();
			}
		});

    });


	$(".close_ticket").on("click", function(e){
    	var id	= $(this).data("close");
    	$(".modal-title").html("<i class='fa fa-times-circle'></i> <strong>Set Close Ticket</strong>");

		$("#modal_ticket").html("");
		$("#modal_footer_ticket").html("");

    	$.ajax({
    		url: baseURL+'skynet/transaction/get_data/close_ticket',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					$("#modal_ticket").append("<div class='form-group'>"
									+"<label class='col-md-4'>Title Ticket</label>"
									+"<div class='col-md-8'>"
									+"<p class='form-control-static' style='margin-top:-5px;'> "+ v.title +"</p>"
									+"</div>"
									+"</div>"
									+"<div class='form-group'>"
									+"<label class='col-md-4'>Set Status</label>"
									+"<div class='col-md-8'>"
									+"<p class='form-control-static' style='margin-top:-5px;'> Close </p>"
									+"</div>"
									+"</div>"
									+"<div class='form-group'>"
									+"<label class='col-md-4'>Remark</label>"
									+"<div class='col-md-8'>"
									+"<textarea name='keterangan' id='keterangan' class='form-control' rows='3' required></textarea>"
									+"</div>"
									+"</div>");

					$("#modal_footer_ticket").append("<input type='hidden' name='id' id='id' value='"+id+"'>"
									+"<input type='hidden' name='status' id='status' value='4'>"
									+"<button type='reset' class='btn btn-default'> <i class='fa fa-undo'></i> Reset</button>"
									+"<button type='submit' name='action_set_status' class='btn btn-success'> <i class='fa fa-save'></i> Save</button>");

				});
			}
		});
    });

	$(".pending_user").on("click", function(e){
    	var id	= $(this).data("pending_user");
    	$(".modal-title").html("<i class='fa fa-users'></i> <strong>Set Pending User</strong>");

		$("#modal_ticket").html("");
		$("#modal_footer_ticket").html("");

		

    	$.ajax({
    		url: baseURL+'skynet/transaction/get_data/pending_user',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					// console.log(id);
					if(v.id_hd_status=='1'){
						var title 			= "Confirm open tiket";
						var status_next 	= "Pending Agent";
						var idstatus_next 	= 2;
					} else if(v.id_hd_status=='2'){
						var title 			= "Set pending user";
						var status_next 	= "Pending User";
						var idstatus_next 	= 3;
					}
					$(".modal-title").html("<i class='fa fa-users'></i><strong>Set status</strong>");
					if(((v.kategori=='Tikar')||(v.kategori=='Tiket'))&&(v.id_hd_status!=1)){
						$("#modal_ticket").append("<div class='form-group'>"
							+"<label class='col-md-4'>Title Ticket</label>"
							+"<div class='col-md-8'>"
							+"<p class='form-control-static' style='margin-top:-5px;'> "+ title +"</p>"
							+"</div>"
							+"</div>"
							+"<div class='form-group'>"
							+"<label class='col-md-4'>Set Status</label>"
							+"<div class='col-md-8'>"
							+"<p class='form-control-static' style='margin-top:-5px;'> "+status_next+" </p>"
							+"</div>"
							+"</div>"
							+"<div class='form-group'>"
							+"<label class='col-md-4'>Remark</label>"
							+"<div class='col-md-8'>"
							+"<textarea name='keterangan' id='keterangan' class='form-control' rows='3' required></textarea>"
							+"</div>"
							+"</div>"
							+"<div class='form-group' id='show_downtime'>"
							+"	<label class='col-md-4'>Downtime</label>"
							+"	<div class='col-md-4'>"
							+"		<label>Start</label>"		
							+"		<input type='text' data-date-format='YYYY-MM-DD HH:mm:ss' name='mulai' id='mulai' class='form-control' placeholder='Start' autocomplete='off'>"
							+"	</div>"
							+"	<div class='col-md-4'>"
							+"		<label>End</label>"
							+"		<input type='text' name='selesai' id='selesai' class='form-control' placeholder='End' autocomplete='off'>"
							+"	</div>"
							+"</div>"
						);
					}else{
						$("#modal_ticket").append("<div class='form-group'>"
							+"<label class='col-md-4'>Title Ticket</label>"
							+"<div class='col-md-8'>"
							+"<p class='form-control-static' style='margin-top:-5px;'> "+ title +"</p>"
							+"</div>"
							+"</div>"
							+"<div class='form-group'>"
							+"<label class='col-md-4'>Set Status</label>"
							+"<div class='col-md-8'>"
							+"<p class='form-control-static' style='margin-top:-5px;'> "+status_next+" </p>"
							+"</div>"
							+"</div>"
							+"<div class='form-group'>"
							+"<label class='col-md-4'>Remark</label>"
							+"<div class='col-md-8'>"
							+"<textarea name='keterangan' id='keterangan' class='form-control' rows='3' required></textarea>"
							+"</div>"
							+"</div>"
							+"</div>"
						);
					}
					//lha	
					$('#mulai,#selesai').datetimepicker({
						showTodayButton: true,
						format: 'YYYY-MM-DD HH:mm',
						sideBySide: true,
						useCurrent: false
					});
								

					$("#modal_footer_ticket").append("<input type='hidden' name='id' id='id' value='"+id+"'>"
									+"<input type='hidden' name='status' id='status' value='"+idstatus_next+"'>"
									+"<button type='reset' class='btn btn-default'> <i class='fa fa-undo'></i> Reset</button>"
									+"<button type='submit' name='action_set_status' class='btn btn-success'> <i class='fa fa-save'></i> Save</button>");

				});
			}
		});
    });

	$(".forceclose_ticket").on("click", function(e){
    	var id	= $(this).data("forceclose");
    	$(".modal-title").html("<i class='fa fa-times-circle'></i> <strong>Set Force Close Ticket</strong>");

		$("#modal_ticket").html("");
		$("#modal_footer_ticket").html("");

    	$.ajax({
    		url: baseURL+'skynet/transaction/get_data/close_ticket',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					$("#modal_ticket").append("<div class='form-group'>"
									+"<label class='col-md-4'>Title Ticket</label>"
									+"<div class='col-md-8'>"
									+"<p class='form-control-static' style='margin-top:-5px;'> "+ v.title +"</p>"
									+"</div>"
									+"</div>"
									+"<div class='form-group'>"
									+"<label class='col-md-4'>Set Status</label>"
									+"<div class='col-md-8'>"
									+"<p class='form-control-static' style='margin-top:-5px;'> Close </p>"
									+"</div>"
									+"</div>"
									+"<div class='form-group'>"
									+"<label class='col-md-4'>Remark</label>"
									+"<div class='col-md-8'>"
									+"<textarea name='keterangan' id='keterangan' class='form-control' rows='3' required></textarea>"
									+"</div>"
									+"</div>"
									+"<div class='form-group'>"
									+"<label class='col-md-4'>Lampiran</label>"
									+"<div class='col-md-8'>"
									+"<input type='file' name='file' id='file' required>"
									+"</div>"
									+"</div>");

					$("#modal_footer_ticket").append("<input type='hidden' name='id' id='id' value='"+id+"'>"
									+"<input type='hidden' name='status' id='status' value='4'>"
									+"<button type='reset' class='btn btn-default'> <i class='fa fa-undo'></i> Reset</button>"
									+"<button type='submit' name='action_set_status' class='btn btn-success'> <i class='fa fa-save'></i> Save</button>");

				});
			}
		});
    });

	$(".history").on("click", function(e){
    	var id	= $(this).data("history");

		$("#modal_ticket").html("");
		$("#modal_footer_ticket").html("");

    	$.ajax({
    		url: baseURL+'skynet/transaction/get_data/history_ticket',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				// console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$("#modal_ticket").append("<div class='box-body'>"
								+"<table id='historytable' width='100%' class='table table-bordered table-striped'>"
								+"<thead>"
								+"<th class='text-center'>Status</th>"
								+"<th class='text-center'>Author</th>"
								+"<th class='text-center'>Start Date</th>"
								+"<th class='text-center'>End Date</th>"
								+"<th class='text-center'>Remark</th>"
								+"<th class='text-center'>Response Time</th>"
								+"<th class='text-center'>Lampiran</th>"
								+"</thead>"
								+"<tbody>"
								+"</tbody>"
								+"</table>"
								+"</div>");
				
				var historytable = $('#historytable').dataTable();
				
				$.each(data, function(i, v){
    				$(".modal-title").html("<i class='fa fa-history'></i> <strong> "+ v.title +" </strong>");
					if(v.gambar != null && v.gambar != ""){						
						var gambar = v.gambar;
						if(gambar.substr(0,4) == "img/"){
							var url = "http://10.0.0.18/home/"+v.gambar;
						}else{
							var url = baseURL+v.gambar;
						}
					}else{
						var url = "";
					}

					if(url == ""){
						var img = "";
					}else{
						var img = "<a href='"+url+"' data-fancybox> <i class='fa fa-search'></i> </a>";
					}
					// var imgfull = "<div class='boximg boximg--fullsize'>"
					// 			+"<img class='fullsize' src='"+url+"' width='150px' height='150px'>"
					// 			+"</div>";
					var calculate_respon = "-";
					if(v.menit != ""){
						var hari 	= v.hari;
						var menit 	= v.menit;
						var jam 	= v.jam;
						
						//menit
						calculate_respon = menit+" menit";
						
						// jam 
						if(menit > 60 ){
							calculate_respon = jam+" jam";
							//jam
							if(jam > 24){		
					  			calculate_respon = hari+" hari";
						  	}
						} 
					}
					historytable.fnAddData([v.status, v.author, v.tanggal_awal + " " + v.jam_awal, v.tanggal_buat + " " + v.jam_buat, v.remark, calculate_respon, img]); 

				});
				
				$('#historytable').DataTable().destroy();

			}
		});
    });

	$(".attachment").on("click", function(e){
    	var id	= $(this).data("attachment");

		$("#modal_ticket").html("");
		$("#modal_footer_ticket").html("");

    	$.ajax({
    		url: baseURL+'skynet/transaction/get_data/attachment_ticket',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				// console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				
				$.each(data, function(i, v){
					if(v.gambar != null && v.gambar != ""){						
						var gambar = v.gambar;
						if(gambar.substr(0,4) == "img/"){
							var url = "http://10.0.0.18/home/"+v.gambar;
						}else{
							var url = baseURL+v.gambar;
						}
					}else{
						var url = "";
					}

    				$(".modal-title").html("<i class='fa fa-search'></i> <strong> "+ v.title +" </strong>");
					$("#modal_ticket").append("<div class='image'>"
									+"<img src='"+url+"' style='width:570px; height:500px;'></img>"
									+"</div>"
									+"</table>");
				});

			}
		});
    });


	$(".excel").on("click", function(e){
		var pabrik 		= $("#filterpabrik").val(); 
		var status 		= $("#filterstatus").val(); 
		var ketegori 	= $("#filterkategori").val(); 
		var from 		= $("#filterfrom").val(); 
		var to 			= $("#filterto").val(); 

		var url = baseURL+'skynet/transaction/ticket/excel';
	        url += "?filterpabrik[]=" + escape(pabrik);
	        url += "&filterstatus=" + escape(status);
	        url += "&filterkategori=" + escape(ketegori);
	        url += "&filterfrom=" + escape(from);
	        url += "&filterto=" + escape(to);
		
		window.location.href = url;
    });

	$(document).on("click", "button[name='action_set_status']", function(e){
		var empty_form = validate($(".form-ticket"));
        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-ticket")[0]);

				$.ajax({
					url: baseURL+'skynet/transaction/set_data/update/close_ticket',
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
		e.preventDefault();
		return false;
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate($(".form-ticket"));
        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-ticket")[0]);

				$.ajax({
					url: baseURL+'skynet/transaction/save/user_ticket',
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
		e.preventDefault();
		return false;
    });

	$(document).on("change", "#kategori", function(e){
		var id	= $(this).val();
    	$.ajax({
    		url: baseURL+'skynet/master/get_data/subcategory',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				var output 	= '';
				output += "<option value=''></option>";
				$.each(data, function (i, v) {
					output += "<option value='"+v.id_hd_subkategori+"'>"+v.nama+"</option>";
				});
				$("#subkategori").html(output);				
				$(".select2").select2();
			}
		});
    });

    $('.datePicker').datepicker({
    	format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
    });
    
    $('[data-fancybox]', document).fancybox({
        buttons: [
            'download',
            'zoom',
            'close'
        ],
        lang: "en",
        i18n: {
            en: {
                CLOSE: "Tutup",
                NEXT: "Selanjutnya",
                PREV: "Sebelumnya",
                ERROR: "File tidak dapat dibuka. <br/> Coba untuk men Download file atau refresh halaman.",
                PLAY_START: "Start slideshow",
                PLAY_STOP: "Pause slideshow",
                FULL_SCREEN: "Full screen",
                THUMBS: "Thumbnails",
                DOWNLOAD: "Download",
                SHARE: "Share",
                ZOOM: "Zoom"
            },
        }
    });
});

function init(){
	$("#title").val("");
	document.getElementById("kategori").value = "0";
	document.getElementById("subkategori").value = "0";
	$("#keterangan").val("");
	$(".select2").select2();
}


function filtersubmit(){
    $('#filterform').submit();
}

$(function () {
	var ticket	= $("#totticket").val();
	var actual	= $("#totactual").val();
	$("#total_actual").val(actual);
	$("#total_ticket").val(ticket);
});
