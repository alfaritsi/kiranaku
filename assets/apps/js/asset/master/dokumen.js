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
            { "className": "text-center", "targets": 5 },
            { "className": "text-center", "targets": 6 },
        ],
    });

    $('input:radio[name="radio"]').on("change", function(e){
        if ($(this).is(':checked') && $(this).val() == 1) {
        	$("#form-periode").removeClass("hidden");
        	$("#form-reminder").removeClass("hidden");           
        	$("#expired").val(1);           
        }else{
        	$("#form-periode").addClass("hidden");
        	$("#form-reminder").addClass("hidden");
        	$("#expired").val(0);           

        }
    });
	
    $(".edit_dokumen").on("click", function(e){
    	var id_inv_doc	= $(this).data("dokumen");
    	$.ajax({
    		url: baseURL+'asset/master/get_dokumen',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_inv_doc : id_inv_doc
			},
			success: function(data){
				console.log(data);
				$(".title-form-dokumen").html("<strong>Edit Dokumen</strong>");
				$.each(data, function(i, v){

					if (i != "jenis_kendaraan") {
						$.each(v, function (id, vl) {

							$("#dokumen").val(vl.nama);
							if (vl.doc_expired == 1) {
								$("#expired").val(vl.doc_expired);
								$('input:radio[name="radio"]').filter('[value=1]').attr('checked', true);
							}else{
								$("#expired").val(vl.doc_expired);
								$('input:radio[name="radio"]').filter('[value=0]').attr('checked', true);
								$("#form-periode").addClass("hidden");
        						$("#form-reminder").addClass("hidden");
							}
							$("#periode").val(vl.periode);
							$("#reminder").val(vl.hari);


							$("#jenis_instansi").val(vl.id_jenis_instansi).trigger("change");
							$("input[name='id_inv_doc']").val(vl.id_inv_doc);
						});

					}

					if (i == "jenis_kendaraan"){
						var jenis_kendaraan = [];
						$.each(v, function (id, val) {
							jenis_kendaraan.push(val);
						});
						$("#jenis_kendaraan").val(jenis_kendaraan).trigger("change");
					}

					$("#btn-new-dokumen").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-dokumen").on("click", function(e){
		$("#dokumen").val("");
		$("#expired").val(1);
		$("#periode").val(0);
		$("#reminder").val(0);
		$("#form-periode").removeClass("hidden");
		$("#form-reminder").removeClass("hidden");
		$("#jenis_kendaraan").val(null).trigger("change");
		$("#jenis_instansi").val(null).trigger("change");
		$("input[name='id_inv_doc']").val("");
	    $(".title-form-dokumen").html("<strong>Buat Dokumen Baru</strong>");
	    $("#btn-new-dokumen").addClass("hidden");
    });


	$(".form-master-dokumen").on("submit", function(e){

		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
	    	var formData = new FormData($(this)[0]);
	    	// console.log(formData);
			$.ajax({
				url: baseURL+'asset/master/save_dokumen',
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
		if(tabs == 'dokumen'){
			$.ajax({
				url: baseURL + "asset/master/set/dokumen",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_inv_doc	: $(this).data($(this).attr("class")),	
					type 	  	: $(this).attr("class")
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