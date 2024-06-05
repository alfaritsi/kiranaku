/*
@application  	: Equipment Management
@author     	: Airiza Yuddha (7849)
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

	$(".form-master-kerusakan").on("submit", function(e){

		// var jenis_asset = $("#jenis_asset").val();
		// if (jenis_asset == 0) {
		// 	kiranaAlert("notOK", "Pilih Jenis Asset", "warning", "no");
		// 	e.preventDefault();
		// 	return false;
		// }else{
			var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(this)[0]);

				$.ajax({
					url: baseURL+'asset/master/save_kerusakan',
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
		// }
	});

	$(".edit_kerusakan").on("click", function(e){
    	var id_kerusakan = $(this).data("edit-kerusakan");
    	$.ajax({
    		url: baseURL+'asset/master/get_kerusakan',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_kerusakan : id_kerusakan
			},
			success: function(data){
				// console.log(data);
				$(".title-form-kerusakan").html("<strong>Edit Jenis Kerusakan</strong>");
				$.each(data, function(i, v){
					$("#kerusakan").val(v.kerusakan);
					$("#kerusakan_ket").val(v.keterangan);

					$("input[name='id_kerusakan']").val(v.id_kerusakan);
					$("#btn-new-kerusakan").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-kerusakan").on("click", function(e){
		$("#tipe_merk").val("");
		$("#ket_tipe").val("");
		$("input[name='id_tipe']").val("");
	    $(".title-form-kerusakan").html("<strong>Buat Jenis Kerusakan Baru</strong>");
	    $("#btn-new-kerusakan").addClass("hidden");
    });

    $(document).on("click", ".set_active, .non_active, .delete", function (e) {
		// alert(tabs + $(this).attr("class") + $(this).data($(this).attr("class")));
		var tabs = $(this).data("tab");
		if(tabs == 'kerusakan'){
			$.ajax({
				url: baseURL + "asset/master/set/kerusakan",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_kerusakan : $(this).data($(this).attr("class")),	
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