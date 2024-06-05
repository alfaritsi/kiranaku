$(document).ready(function () {

	$("#tab-lokasi").on("click", function(e){
		$("#box-add-lokasi").removeClass("hidden");
		$("#box-add-sublokasi").addClass("hidden");
		$("#box-add-area").addClass("hidden");
	});

	$("#tab-sublokasi").on("click", function(e){
		$("#box-add-lokasi").addClass("hidden");
		$("#box-add-sublokasi").removeClass("hidden");
		$("#box-add-area").addClass("hidden");
	});

	$("#tab-area").on("click", function(e){
		$("#box-add-lokasi").addClass("hidden");
		$("#box-add-sublokasi").addClass("hidden");
		$("#box-add-area").removeClass("hidden");
	});

	$(".edit_lokasi").on("click", function(e){
    	var id_lokasi	= $(this).data("edit-lokasi");
    	$.ajax({
    		url: baseURL+'asset/master/get/lokasi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_lokasi : id_lokasi
			},
			success: function(data){
				$(".title-form-lokasi").html("<strong>Edit Lokasi</strong>");
				$.each(data, function(i, v){
					// console.log(v.nama);
					$("input[name='lokasi']").val(v.nama);
					$("#ket_lokasi").val(v.keterangan);
					var pengguna	= v.pengguna.split(".");
					$("select[name='pengguna[]']").val(pengguna).trigger("change");

					$("input[name='id_lokasi']").val(v.id_lokasi);
					$("#btn-new-lokasi").removeClass("hidden");
				});
			}
		});
    });

     $("#btn-new-lokasi").on("click", function(e){
		$("input[name='lokasi']").val("");
		$("select[name='pengguna[]']").val('').trigger("change");
		$("#ket_lokasi").val("");
		$("input[name='id_lokasi']").val("");
	    $(".title-form-lokasi").html("<strong>Buat Lokasi Baru</strong>");
	    $("#btn-new-lokasi").addClass("hidden");
    });

	$(document).on("submit", ".form-master-lokasi", function (e) {
		
		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			var formData = new FormData($(".form-master-lokasi")[0]);

			$.ajax({
				url: baseURL + 'asset/master/save/lokasi',
				type: 'POST',
				dataType: 'JSON',
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function (data) {
					console.log(data);
					if (data.sts == 'OK') {
						kiranaAlert(data.sts, data.msg);
					} else {
						kiranaAlert(data.sts, data.msg, "error", "no");
						$("input[name='isproses']").val(0);
					}
				},
				complete: function () {
					$("input[name='isproses']").val(0);
				}
			});
		} else {
			kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
		}
		e.preventDefault();
		return false;
	});

	$(".edit_sublokasi").on("click", function(e){
    	var id_sub_lokasi	= $(this).data("edit-sublokasi");
    	$.ajax({
    		url: baseURL+'asset/master/get/sub_lokasi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_sub_lokasi : id_sub_lokasi
			},
			success: function(data){
				$(".title-form-lokasi").html("<strong>Edit Sub Lokasi</strong>");
				$.each(data, function(i, v){
					// console.log(v.nama);
					$("select[name='lokasi_opt']").val(v.id_lokasi).trigger("change");
					$("input[name='sublokasi']").val(v.nama);
					$("#ket_sublokasi").val(v.keterangan);
					var pengguna	= v.pengguna.split(".");
					$("select[name='pengguna2[]']").val(pengguna).trigger("change");

					$("input[name='id_sub_lokasi']").val(v.id_sub_lokasi);
					$("#btn-new-sublokasi").removeClass("hidden");
				});
			}
		});
    });

     $("#btn-new-sublokasi").on("click", function(e){
		$("input[name='sublokasi']").val("");
		$("select[name='lokasi_opt']").val('0').trigger("change");
		$("select[name='pengguna2[]']").val('').trigger("change");
		$("#ket_sublokasi").val("");
		$("input[name='id_sub_lokasi']").val("");
	    $(".title-form-sublokasi").html("<strong>Buat Sub Lokasi Baru</strong>");
	    $("#btn-new-sublokasi").addClass("hidden");
    });

	$(document).on("submit", ".form-master-sublokasi", function (e) {
		
		if ($("select[name='lokasi_opt']").val() == '0') {
			kiranaAlert("notOK", "Silahkan Pilih Lokasi terlebih dahulu", "warning", "no");
			e.preventDefault();
			return false;
		}

		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			var formData = new FormData($(".form-master-sublokasi")[0]);

			$.ajax({
				url: baseURL + 'asset/master/save/sub_lokasi',
				type: 'POST',
				dataType: 'JSON',
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function (data) {
					console.log(data);
					if (data.sts == 'OK') {
						kiranaAlert(data.sts, data.msg);
					} else {
						kiranaAlert(data.sts, data.msg, "error", "no");
						$("input[name='isproses']").val(0);
					}
				},
				complete: function () {
					$("input[name='isproses']").val(0);
				}
			});
		} else {
			kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
		}
		e.preventDefault();
		return false;
	});



	$(".edit_area").on("click", function(e){
    	var id_area	= $(this).data("edit-area");
    	$.ajax({
    		url: baseURL+'asset/master/get/area',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_area : id_area
			},
			success: function(data){
				$(".title-form-area").html("<strong>Edit Area</strong>");
				$.each(data, function(i, v){
					// console.log(v.nama);
					$("select[name='lokasi_opt2']").val(v.id_lokasi).trigger("change");
	            	setTimeout(function(){
						$("select[name='sublokasi_opt']").val(v.id_sub_lokasi).trigger("change");
					}, 1000);
					$("input[name='area']").val(v.nama);
					$("#ket_area").val(v.keterangan);

					$("input[name='id_area']").val(v.id_area);
					$("#btn-new-area").removeClass("hidden");
				});
			}
		});
    });

     $("#btn-new-area").on("click", function(e){
		$("input[name='area']").val("");
		$("select[name='lokasi_opt2']").val('0').trigger("change");
		$("select[name='sublokasi_opt']").empty().trigger("change");
		$("#ket_area").val("");
		$("input[name='id_area']").val("");
	    $(".title-form-area").html("<strong>Buat Area Baru</strong>");
	    $("#btn-new-area").addClass("hidden");
    });

	$(document).on("submit", ".form-master-area", function (e) {
		
		if ($("select[name='lokasi_opt2']").val() == '0') {
			kiranaAlert("notOK", "Silahkan Pilih Lokasi terlebih dahulu", "warning", "no");
			e.preventDefault();
			return false;
		}
		
		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			var formData = new FormData($(".form-master-area")[0]);

			$.ajax({
				url: baseURL + 'asset/master/save/area',
				type: 'POST',
				dataType: 'JSON',
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function (data) {
					console.log(data);
					if (data.sts == 'OK') {
						kiranaAlert(data.sts, data.msg);
					} else {
						kiranaAlert(data.sts, data.msg, "error", "no");
						$("input[name='isproses']").val(0);
					}
				},
				complete: function () {
					$("input[name='isproses']").val(0);
				}
			});
		} else {
			kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
		}
		e.preventDefault();
		return false;
	});


	$(document).on("change", "#lokasi_opt2", function(){
		var value = $("#lokasi_opt2").val();
	    
	    if (value != "") {
	    	$.ajax({
	    		url: baseURL+'asset/master/get_opt_sub_lokasi',
				type: 'POST',
				dataType: 'JSON',
				data:{
					id_lokasi : value
				},
				success: function(data){
					var output = "<option value=''>Pilih Sub Lokasi</option>";
		            $.each(data, function(i,v){
		                output  += "<option value='"+v.id_sub_lokasi+"'>"+v.nama+"</option>";
		            });
		            $('#sublokasi_opt').html(output)
				}	  
			});	
	    }else{
	    	$('#lokasi_opt2').empty().trigger('change');
	    }
	
	});

	$(document).on("change", "#sublokasi_opt", function(){
		var value = $("#sublokasi_opt").val();
	    
	    if (value != "") {
	    	$.ajax({
	    		url: baseURL+'asset/master/get/sub_lokasi',
				type: 'POST',
				dataType: 'JSON',
				data:{
					id_sub_lokasi : value
				},
				success: function(data){
		            $.each(data, function(i,v){
						$("input[name='pengguna_sublok']").val(v.pengguna);
		            });
		            
				}	  
			});	
	    }
	
	});

	$(document).on("click", ".non_active, .set_active, .delete", function (e) {
		var tabs = $(this).data("tab");
		if(tabs=='lokasi'){
			$.ajax({
				url: baseURL + "asset/master/set/lokasi",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_lokasi 	 : $(this).data($(this).attr("class")),	
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
		}else if(tabs=='sub_lokasi'){
			$.ajax({
				url: baseURL + "asset/master/set/sub_lokasi",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_sub_lokasi 	 : $(this).data($(this).attr("class")),	
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
		}else if(tabs=='area'){
			$.ajax({
				url: baseURL + "asset/master/set/area",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_area 	 : $(this).data($(this).attr("class")),	
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
			
		}
	});
	
    //cek all pengguna
    $(document).on("change", ".isSelectAllpengguna", function(e){
        if($(".isSelectAllpengguna").is(':checked')) {
            $('#pengguna').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#pengguna').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });

    $(document).on("change", ".isSelectAllpengguna2", function(e){
        if($(".isSelectAllpengguna2").is(':checked')) {
            $('#pengguna2').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#pengguna2').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
	
});
