
$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });


	$(".upload").on("click", function(e){
    	var id	= $(this).data("upload");
    	$(".modal-title").html("Form Upload Document");

    	$.ajax({
    		url: baseURL+'accounting/transaction/get_data/upload_jurnal',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					$("#id").val(id);
					$("#action").val("upload");
					$("#doc_no").html("");
					$("#text").html("");
					$("#tipe").html("");
					$("#fileexist").html("");
					$("#infodiv").html("");
					$("#uploaddiv").html("");

					$("#doc_no").append(v.no_doc);					
					$("#text").append(v.text);
					$("#tipe").append(v.tipe);
					$("#uploaddiv").append("<div class='clearfix'></div>"
					                	+"<div class='form-group' style='margin-bottom: 5px;'>"
										+"<label class='col-md-4'>Upload Files</label>"
										+"<div class='col-md-8'>"
										+"<input type='file' name='file[]'' id = 'file' multiple required accept='.pdf'>"
										+"</div>"
										+"<div class='col-md-8 pull-right'>"
										+"*Anda dapat mengupload multi attachment"
										+"</div>"
					                	+"</div>");

					// $("#jumlahfile").append(v.id);
					if(v.data != "" && v.data2 != null){
	              		var str = v.data2;
	              		var file = str.split("|");
						$.each(file, function(i2, v2){
							if(v2 != ""){
								var str = v2;
								if(str.substring(0,3) != "img"){
									$("#fileexist").append("<a href='"+baseURL+ 'assets/file/acc/uploadjurnal/' + v2 + '?' + new Date().getTime() +"' target='_blank' style='color:green;'><i class='fa fa-file-pdf-o'></i> "+ v2+ " </a><br/>");
								}else{
									$("#fileexist").append("<a href='http://10.0.0.249/dev/kiranaku/home/pdfviewer.php?q="+ v2 + '&' + new Date().getTime() +"' target='_blank' style='color:green;'><i class='fa fa-file-pdf-o'></i> "+ v2+ " </a><br/>");
								}
							}
						});

					}else{
						$("#fileexist").append("<p class='form-control-static'> No file exist</p>");
					}
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(".request").on("click", function(e){
    	var id	= $(this).data("request");
    	$(".modal-title").html("Form Pengajuan Re-upload");

    	$.ajax({
    		url: baseURL+'accounting/transaction/get_data/upload_jurnal',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					$("#id").val(id);
					$("#action").val("request");
					$("#doc_no").html("");
					$("#text").html("");
					$("#tipe").html("");
					$("#fileexist").html("");
					$("#infodiv").html("");
					$("#uploaddiv").html("");

					$("#doc_no").append(v.no_doc);					
					$("#text").append(v.text);
					$("#tipe").append(v.tipe);
					$("#infodiv").append("<div class='clearfix'></div>"
					                	+"<div class='form-group' style='margin-bottom: 5px;'>"
										+"<label class='col-md-4'>Keterangan</label>"
										+"<div class='col-md-8'>"
										+"<textarea name='info' id='info' required class='form-control' rows='2'>"+v.info+"</textarea>"
										+"</div>"
					                	+"</div>");


					if(v.data != "" && v.data2 != null){
	              		var str = v.data2;
	              		var file = str.split("|");
						$.each(file, function(i2, v2){
							if(v2 != ""){
								var str = v2;
								if(str.substring(0,3) != "img"){
									$("#fileexist").append("<a href='"+baseURL+ 'assets/file/acc/uploadjurnal/' + v2 + '?' + new Date().getTime() +"' target='_blank' style='color:green;'><i class='fa fa-file-pdf-o'></i> "+ str.replace('assets/file/acc/uploadjurnal/','')+ " </a><br/>");
								}else{
									$("#fileexist").append("<a href='http://10.0.0.249/dev/kiranaku/home/pdfviewer.php?q="+ v2 + '&' + new Date().getTime() +"' target='_blank' style='color:green;'><i class='fa fa-file-pdf-o'></i> "+ str.replace('img/acc/','')+ " </a><br/>");
								}
							}
						});

					}else{
						$("#fileexist").append("<p class='form-control-static'> No file exist</p>");
					}
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(".add").on("click", function(e){
    	var id	= $(this).data("add");
    	$(".modal-title").html("Form Add Upload Document");

    	$.ajax({
    		url: baseURL+'accounting/transaction/get_data/upload_jurnal',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					$("#id").val(id);
					$("#action").val("add");
					$("#doc_no").html("");
					$("#text").html("");
					$("#tipe").html("");
					$("#fileexist").html("");
					$("#infodiv").html("");
					$("#uploaddiv").html("");

					$("#doc_no").append(v.no_doc);					
					$("#text").append(v.text);
					$("#tipe").append(v.tipe);
					$("#uploaddiv").append("<div class='clearfix'></div>"
					                	+"<div class='form-group' style='margin-bottom: 5px;'>"
										+"<label class='col-md-4'>Upload Files</label>"
										+"<div class='col-md-8'>"
										+"<input type='file' name='file[]'' id = 'file' multiple required accept='.pdf'>"
										+"</div>"
										+"<div class='col-md-8 pull-right'>"
										+"*Anda dapat mengupload multi attachment"
										+"</div>"
					                	+"</div>");

					// $("#jumlahfile").append(v.id);
					if(v.data != "" && v.data2 != null){
	              		var str = v.data2;
	              		var file = str.split("|");
						$.each(file, function(i2, v2){
							if(v2 != ""){
								$("#fileexist").append("<a href='"+baseURL+ 'assets/file/acc/uploadjurnal/' + v2 + '?' + new Date().getTime() +"' target='_blank' style='color:green;'><i class='fa fa-file-pdf-o'></i> "+ v2+ " </a><br/>");
							}
						});

					}else{
						$("#fileexist").append("<p class='form-control-static'> No file exist</p>");
					}
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		e.preventDefault();
		
		var empty_form = validate(".form-uploadjurnal");

        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);

		    	var formData = new FormData($(".form-uploadjurnal")[0]);

		    	if($("#action").val() == "upload"){
					$.ajax({
						url: baseURL+'accounting/transaction/save/upload_jurnal',
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
				}else if($("#action").val() == "request"){
					$.ajax({
						url: baseURL+'accounting/transaction/set_data/update/request_upload',
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
				}else if($("#action").val() == "add"){
					$.ajax({
						url: baseURL+'accounting/transaction/set_data/update/add_upload',
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
				}

			}else{
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		// $('#sumberlimbah').attr('disabled', true);
		// e.preventDefault();
		return false;
    });

    $('.datePicker').datepicker({
    	format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
        // startDate: new Date(date)
    });


});
