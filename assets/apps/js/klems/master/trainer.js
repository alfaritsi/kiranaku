$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-trainer").on("click", function(e){
		var id_trainer	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/trainer',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_trainer : id_trainer
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
    	var id_trainer = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/trainer',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_trainer : id_trainer
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
    	var id_trainer	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/trainer',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_trainer : id_trainer
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Edit Master Trainer");
				$.each(data, function(i, v){
					$("#trainer").val(v.trainer);
					$("input[name='id_trainer']").val(v.id_trainer);
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
					$("select[name='id_institusi']").val(v.id_institusi).trigger('change');
					$("input[name='telepon']").val(v.telepon);
					$("input[name='email']").val(v.email);
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
		    	var formData = new FormData($(".form-master-trainer")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/trainer',
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
                title: 'Master Trainer',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            }
        ],
		scrollX:true
    } );	
});