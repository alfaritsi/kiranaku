$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-program").on("click", function(e){
		var id_program	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/program',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program : id_program
			},
			success: function(data){
				if (data.sts == 'OK') {
					swal('Success', data.msg, 'success').then(function () {
						location.reload();
					});
				} else {
					$("input[name='isproses']").val(0);
					swal('Error', data.msg, 'error');
				}
			}
		});
    });

	$(".delete").on("click", function(e){
    	var id_program	= $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/program',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program : id_program
			},
			success: function(data){
				if (data.sts == 'OK') {
					swal('Success', data.msg, 'success').then(function () {
						location.reload();
					});
				} else {
					$("input[name='isproses']").val(0);
					swal('Error', data.msg, 'error');
				}
			}
		});
    });

	$(".edit").on("click", function(e){
    	var id_program	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/program',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program : id_program
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Program");
				$.each(data, function(i, v){
					$("#program").val(v.program);
					$("input[name='id_program']").val(v.id_program);
					$("select[name='jenis']").val(v.jenis).trigger('change');
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
					$("input[name='abbreviation']").val(v.abbreviation);
					// $("input[name='sertifikat_keahlian']").val(v.sertifikat_keahlian);
					// $("select[name='id_sertifikat']").val(v.id_sertifikat).trigger('change');
					//for check box
					if(v.sertifikat_keahlian=='1'){
						$("#show_id_sertifikat").show();
						$('input[name=sertifikat_keahlian]').prop('checked', true);
						$('input[name=id_sertifikat]').prop('required', true);
						$("#id_sertifikat").val(v.id_sertifikat).trigger('change');
					} else {
						$('input[name=sertifikat_keahlian]').prop('checked', false);
						$('input[name=id_sertifikat]').prop('required', false);
						$("#id_sertifikat").val("");
					}
					$("select[name='kategori']").val(v.kategori).trigger('change');
					$("select[name='tipe_program']").val(v.tipe_program).trigger('change');
					$("select[name='tipe_penyelenggara']").val(v.tipe_penyelenggara).trigger('change');
					$("select[name='jenis_sertifikat']").val(v.jenis_sertifikat).trigger('change');
					$("input[name='keterangan']").val(v.keterangan);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-program")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/program',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function () {
								location.reload();
							});
						} else {
							$("input[name='isproses']").val(0);
							swal('Error', data.msg, 'error');
						}
					}
				});
			}else{
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
			}
		}
		e.preventDefault();
		return false;
    });
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Setting Program',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                }
            }
        ],
		scrollX:true
    } );	
	//set on click
    $(document).on("click", "#sertifikat_keahlian", function(e){
    	var valcheck = $("#sertifikat_keahlian").val();
   		if($('#sertifikat_keahlian').is(':checked')){
   			$("#show_id_sertifikat").show();
			$('select[name=id_sertifikat]').attr('required', 'required');
   		} else {
   			$("#show_id_sertifikat").hide();
   			$("#id_sertifikat").val("");
			$('select[name=id_sertifikat]').removeAttr('required');
   			
   		}
   		
    });
	
	
});