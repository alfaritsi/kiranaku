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
            { "className": "text-center", "targets": 4 },
        ],
    });

    $('input:radio[name="radio"]').on("change", function(e){
        if ($(this).is(':checked') && $(this).val() == 1) {
        	$("#km").val(1);           
        }else{
        	$("#km").val(0);           
        }
    });
	
    $(".edit_biaya").on("click", function(e){
    	var id_inv_biaya	= $(this).data("biaya");
    	$.ajax({
    		url: baseURL+'asset/master/get_biaya',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_inv_biaya : id_inv_biaya
			},
			success: function(data){
				// console.log(data);
				$(".title-form-biaya").html("<strong>Edit Biaya</strong>");
				$.each(data, function(i, v){
					$("#kode_sap").val(v.kode_sap);
					$("#biaya").val(v.nama);
					
					if (v.km == 'y') {
						$("#km").val(1);
						$('input:radio[name="radio"]').filter('[value=1]').attr('checked', true);
					}else{
						$("#km").val(0);
						$('input:radio[name="radio"]').filter('[value=0]').attr('checked', true);
					}
					$("input[name='id_inv_biaya']").val(v.id_inv_biaya);
					$("#btn-new-biaya").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-biaya").on("click", function(e){
		$("#kode_sap").val("");
		$("#biaya").val("");
		$("#km").val(1);
		$('input:radio[name="radio"]').filter('[value=1]').attr('checked', true);
		$("input[name='id_inv_biaya']").val("");
	    $(".title-form-biaya").html("<strong>Buat Biaya Baru</strong>");
	    $("#btn-new-biaya").addClass("hidden");
    });


	$(".form-master-biaya").on("submit", function(e){
		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
	    	var formData = new FormData($(this)[0]);
	    	// console.log(formData);
			$.ajax({
				url: baseURL+'asset/master/save_biaya',
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
		if(tabs == 'biaya'){
			$.ajax({
				url: baseURL + "asset/master/set/biaya",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_inv_biaya	: $(this).data($(this).attr("class")),	
					type 	  		: $(this).attr("class")
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