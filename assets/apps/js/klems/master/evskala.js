$(document).ready(function(){
	$("#nilai").on("keyup", function(e){
    	$("#kode").val("");
    	$("#nama").val("");
		$("#keterangan").val("");
    	var nilai	= $(this).val();
    	$.ajax({
    		url: baseURL+'klems/master/get_data/evskala_cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				nilai	: nilai
			},
			success: function(data){
				$.each(data, function(i, v){
					
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
					$("input[name='keterangan']").val(v.keterangan);
					// $("input[name='id_feedback_nilai']").val(v.id_feedback_nilai);
				});
			}
		});
    });
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-evskala").on("click", function(e){
		var id_feedback_nilai	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/evskala',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_nilai : id_feedback_nilai
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
    	var id_feedback_nilai = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/evskala',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_nilai : id_feedback_nilai
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
    	var id_feedback_nilai	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/evskala',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_nilai : id_feedback_nilai
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Edit Master Trainner");
				$.each(data, function(i, v){
					$("#evskala").val(v.evskala);
					$("input[name='id_feedback_nilai']").val(v.id_feedback_nilai);
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
					$("input[name='keterangan']").val(v.keterangan);
					$("input[name='nilai']").val(v.nilai);
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
		    	var formData = new FormData($(".form-master-evskala")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/evskala',
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
                title: 'Master Skala Penilaian',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            }
        ]
    } );	
});