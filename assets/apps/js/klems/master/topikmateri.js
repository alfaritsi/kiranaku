$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(".set_active-topik_materi").on("click", function(e){
		var id_materi	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/topik_materi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_materi : id_materi
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
    	var id_materi = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/topik_materi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_materi : id_materi
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
    	var id_materi	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/topik_materi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_materi : id_materi
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Materi Topik");
				$.each(data, function(i, v){
					$("#materi").val(v.materi);
					$("input[name='id_materi']").val(v.id_materi);
					$("input[name='nama']").val(v.nama);
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
		    	var formData = new FormData($(".form-master-topik_materi")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/master/save/topik_materi',
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
                title: 'Setting Materi Topik',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6],
                }
            }
        ],
		scrollX:true
    } );
	
});