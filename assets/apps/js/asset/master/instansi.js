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
	
    $(".edit_jenis").on("click", function(e){
    	var id_jenis_instansi	= $(this).data("edit-jenis");
    	$.ajax({
    		url: baseURL+'asset/master/get_jenis_instansi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_jenis_instansi : id_jenis_instansi
			},
			success: function(data){
				// console.log(data);
				$(".title-form-jenis_instansi").html("<strong>Edit Jenis Instansi</strong>");
				$.each(data, function(i, v){
					$("#jenis_instansi").val(v.nama);
					$("#ket_jenis_instansi").val(v.keterangan);

					$("input[name='id_jenis_instansi']").val(v.id_jenis_instansi);
					$("#btn-new-jenis_instansi").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-jenis_instansi").on("click", function(e){
		$("#jenis_instansi").val("");
		$("#ket_jenis_instansi").val("");
		$("input[name='id_jenis_instansi']").val("");
	    $(".title-form-jenis_instansi").html("<strong>Buat Jenis Instansi Baru</strong>");
	    $("#btn-new-jenis_instansi").addClass("hidden");
    });


	$(".form-master-jenis-instansi").on("submit", function(e){
	
		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
	    	var formData = new FormData($(this)[0]);

			$.ajax({
				url: baseURL+'asset/master/save_jenis_instansi',
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

	$(".edit_instansi").on("click", function(e){
    	var id_instansi	= $(this).data("edit-instansi");
    	$.ajax({
    		url: baseURL+'asset/master/get_instansi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_instansi : id_instansi
			},
			success: function(data){
				// console.log(data);
				$(".title-form-instansi").html("<strong>Edit Instansi</strong>");
				$.each(data, function(i, v){
					$("#instansi").val(v.nama);
					$("#ket_instansi").val(v.keterangan);

					$("input[name='id_instansi']").val(v.id_instansi);
					$("#btn-new-instansi").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-instansi").on("click", function(e){
		$("#instansi").val("");
		$("#ket_instansi").val("");
		$("input[name='id_instansi']").val("");
	    $(".title-form-instansi").html("<strong>Buat Instansi Baru</strong>");
	    $("#btn-new-instansi").addClass("hidden");
    });


	$(".form-master-instansi").on("submit", function(e){

		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
	    	var formData = new FormData($(this)[0]);

			$.ajax({
				url: baseURL+'asset/master/save_instansi',
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
		if(tabs == 'jenis_instansi'){
			$.ajax({
				url: baseURL + "asset/master/set/jenis_instansi",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_jenis_instansi	: $(this).data($(this).attr("class")),	
					type 	  	 		: $(this).attr("class")
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
		}else if(tabs == 'instansi'){
			$.ajax({
				url: baseURL + "asset/master/set/instansi",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_instansi : $(this).data($(this).attr("class")),	
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