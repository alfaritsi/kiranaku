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
	
	$('.datatable-customs').DataTable({
		order: [[0, 'asc']],
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        "iDisplayLength": 50,
        "paging": true,
        columnDefs: [
            { "className": "text-left", "targets": 2 },
            { "className": "text-left", "targets": 1 },
            { "className": "text-left", "targets": 3 },
            { "className": "text-left", "targets": 4 },
        ],
    });


    // $(".edit_merk").on("click", function(e){
	$(document).on("click", ".edit_merk", function (e) {	
    	var id_merk	= $(this).data("edit-merk");
    	$.ajax({
    		url: baseURL+'asset/master/get_merk',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_merk : id_merk
			},
			success: function(data){
				// console.log(data);
				$(".title-form-merk").html("<strong>Edit Merk</strong>");
				$.each(data, function(i, v){
					$("#jenis_asset").val(v.id_jenis).trigger('change');
					$("#merk").val(v.nama);
					$("#ket_merk").val(v.keterangan);

					$("input[name='id_merk']").val(v.id_merk);
					$("#btn-new-merk").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-merk").on("click", function(e){
		$("#jenis_asset").val("0").trigger('change');
		$("#merk").val("");
		$("#ket_merk").val("");
		$("input[name='id_merk']").val("");
	    $(".title-form-merk").html("<strong>Buat Merk Baru</strong>");
	    $("#btn-new-merk").addClass("hidden");
    });


	$(".form-master-merk").on("submit", function(e){

		var jenis_asset = $("#jenis_asset").val();
		if (jenis_asset == 0) {
			kiranaAlert("notOK", "Pilih Jenis Asset", "warning", "no");
			e.preventDefault();
			return false;
		}else{
			var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(this)[0]);

				$.ajax({
					url: baseURL+'asset/master/save_merk',
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
		}
	});

	// $(".edit_tipe").on("click", function(e){
	$(document).on("click", ".edit_tipe", function (e) {	
    	var id_tipe	= $(this).data("edit-tipe");
    	$.ajax({
    		url: baseURL+'asset/master/get_merk_tipe',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tipe : id_tipe
			},
			success: function(data){
				// console.log(data);
				$(".title-form-tipe").html("<strong>Edit Tipe Merk</strong>");
				$.each(data, function(i, v){
					$("#tipe_merk").val(v.nama);
					$("#ket_tipe").val(v.keterangan);

					$("input[name='id_tipe']").val(v.id_merk_tipe);
					$("#btn-new-tipe").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-tipe").on("click", function(e){
		$("#tipe_merk").val("");
		$("#ket_tipe").val("");
		$("input[name='id_tipe']").val("");
	    $(".title-form-tipe").html("<strong>Buat Tipe Merk Baru</strong>");
	    $("#btn-new-tipe").addClass("hidden");
    });


	$(".form-master-tipe").on("submit", function(e){

		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
	    	var formData = new FormData($(this)[0]);

			$.ajax({
				url: baseURL+'asset/master/save_merk_tipe',
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
		if(tabs == 'merk'){
			$.ajax({
				url: baseURL + "asset/master/set/merk",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_merk	 	 : $(this).data($(this).attr("class")),	
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
		}else if(tabs == 'tipe'){
			$.ajax({
				url: baseURL + "asset/master/set/merk_tipe",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_merk_tipe : $(this).data($(this).attr("class")),	
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