$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-evitem").on("click", function(e){
		var id_feedback_pertanyaan	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/evitem',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_pertanyaan : id_feedback_pertanyaan
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
    	var id_feedback_pertanyaan = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/evitem',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_pertanyaan : id_feedback_pertanyaan
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
    	var id_feedback_pertanyaan	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/evitem',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_pertanyaan : id_feedback_pertanyaan
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Master Item Kuesioner");
				$.each(data, function(i, v){
					$("#evitem").val(v.evitem);
					$("input[name='id_feedback_pertanyaan']").val(v.id_feedback_pertanyaan);
					$("input[name='kode']").val(v.kode);
					$("select[name='id_feedback_kategori']").val(v.id_feedback_kategori).trigger('change');
					$("textarea[name='pertanyaan']").val(v.pertanyaan);
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
		    	var formData = new FormData($(".form-master-item")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/evitem',
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
                title: 'Master  Evaluasi Jenis & Kategori',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            }
        ]
    } );	
});