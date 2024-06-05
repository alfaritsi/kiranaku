/*
@application  	: Equipment Management
@author     	: Lukman Hakim (7143)
@contributor  	: 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

$(document).ready(function(){

	$("#tab1").on("click", function(e){
		$("#box-add-kegiatan").removeClass("hidden");
		$("#box-add-service").addClass("hidden");
		$("#box-add-satuan").addClass("hidden");
	});

	$("#tab2").on("click", function(e){
		$("#box-add-kegiatan").addClass("hidden");
		$("#box-add-service").removeClass("hidden");
		$("#box-add-satuan").addClass("hidden");
	});

	$("#tab3").on("click", function(e){
		$("#box-add-kegiatan").addClass("hidden");
		$("#box-add-service").addClass("hidden");
		$("#box-add-satuan").removeClass("hidden");
	});

	$('.datatable-custom').DataTable({
		order: [[0, 'asc']],
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        "iDisplayLength": 50,
        "paging": true,
        columnDefs: [
            { "className": "text-center", "targets": 2 },
            { "className": "text-center", "targets": 1 },
            { "className": "text-center", "targets": 3 },
        ],
    });

    $(".edit_kegiatan").on("click", function(e){
    	var id_kegiatan	= $(this).data("edit-kegiatan");
    	$.ajax({
    		url: baseURL+'asset/master/get_kegiatan',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_kegiatan : id_kegiatan
			},
			success: function(data){
				$(".title-form-kegiatan").html("<strong>Edit Kegiatan</strong>");
				$.each(data, function(i, v){
					console.log(v.nama);
					$("input[name='kegiatan']").val(v.nama);
					$("#ket_kegiatan").val(v.keterangan);

					$("input[name='id_kegiatan']").val(v.id_kegiatan);
					$("#btn-new-kegiatan").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-kegiatan").on("click", function(e){
		$("input[name='kegiatan']").val("");
		$("#ket_kegiatan").val("");
		$("input[name='id_kegiatan']").val("");
	    $(".title-form-kegiatan").html("<strong>Buat Kegiatan Baru</strong>");
	    $("#btn-new-kegiatan").addClass("hidden");
    });


	$(".form-master-kegiatan").on("submit", function(e){
		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
			var formData = new FormData($(this)[0]);
			formData.append('pengguna', 'fo');

			$.ajax({
				url: baseURL+'asset/master/save_kegiatan',
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
			alert("Silahkan tunggu proses selesai.");
		}
		e.preventDefault();
		return false;
	});

	$(".edit_service").on("click", function(e){
    	var id_service	= $(this).data("edit-service");
    	$.ajax({
    		url: baseURL+'asset/master/get_service',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_service : id_service
			},
			success: function(data){
				// console.log(data);
				$(".title-form-service").html("<strong>Edit Service</strong>");
				$.each(data, function(i, v){
					$("input[name='service']").val(v.nama);
					$("#ket_service").val(v.keterangan);

					$("input[name='id_service']").val(v.id_service);
					$("#btn-new-service").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-service").on("click", function(e){
		$("input[name='service']").val("");
		$("#ket_service").val("");
		$("input[name='id_service']").val("");
	    $(".title-form-service").html("<strong>Buat Service Baru</strong>");
	    $("#btn-new-service").addClass("hidden");
    });


	$(".form-master-service").on("submit", function(e){
		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
			var formData = new FormData($(this)[0]);
			formData.append('pengguna', 'fo');

			$.ajax({
				url: baseURL+'asset/master/save_service',
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
			alert("Silahkan tunggu proses selesai.");
		}
		e.preventDefault();
		return false;
	});

	$(".edit_satuan").on("click", function(e){
    	var id_satuan	= $(this).data("edit-satuan");
    	$.ajax({
    		url: baseURL+'asset/master/get_satuan',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_satuan : id_satuan
			},
			success: function(data){
				// console.log(data);
				$(".title-form-satuan").html("<strong>Edit Satuan</strong>");
				$.each(data, function(i, v){
					$("input[name='satuan']").val(v.nama);
					$("#ket_satuan").val(v.keterangan);

					$("input[name='id_satuan']").val(v.id_satuan);
					$("#btn-new-satuan").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-satuan").on("click", function(e){
		$("input[name='satuan']").val("");
		$("#ket_satuan").val("");
		$("input[name='id_satuan']").val("");
	    $(".title-form-satuan").html("<strong>Buat Satuan Baru</strong>");
	    $("#btn-new-satuan").addClass("hidden");
    });


	$(".form-master-satuan").on("submit", function(e){
		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
			var formData = new FormData($(this)[0]);
			formData.append('pengguna', 'fo');

			$.ajax({
				url: baseURL+'asset/master/save_satuan',
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
			alert("Silahkan tunggu proses selesai.");
		}
		e.preventDefault();
		return false;
	});
	

	$(document).on("click", ".set_active, .non_active, .delete", function (e) {
		// alert(tabs + $(this).attr("class") + $(this).data($(this).attr("class")));
		var tabs = $(this).data("tab");
		if(tabs == 'kegiatan'){
			$.ajax({
				url: baseURL + "asset/master/set/kegiatan",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_kegiatan	 : $(this).data($(this).attr("class")),	
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
		}else if(tabs == 'service'){
			$.ajax({
				url: baseURL + "asset/master/set/service",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_service	 : $(this).data($(this).attr("class")),	
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
		}else if(tabs == 'satuan'){
			$.ajax({
				url: baseURL + "asset/master/set/satuan",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_satuan	 : $(this).data($(this).attr("class")),	
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
		}else{
			alert("something went wrong");
		}

	});

	
});