$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-spesialis").on("click", function(e){
		var id_spesialis	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/spesialis',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_spesialis : id_spesialis
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
    	var id_spesialis	= $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/spesialis',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_spesialis : id_spesialis
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
    	var id_spesialis	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/spesialis',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_spesialis : id_spesialis
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Master Spesialis Program");
				$.each(data, function(i, v){
					$("#spesialis").val(v.spesialis);
					$("input[name='id_spesialis']").val(v.id_spesialis);
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
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
		    	var formData = new FormData($(".form-master-spesialis")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/spesialis',
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
                title: 'Master Spesialis',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            }
        ]
    } );	
	
});