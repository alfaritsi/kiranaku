$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-soal_tipe").on("click", function(e){
		var id_soal_tipe	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/soal_tipe',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_soal_tipe : id_soal_tipe
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
    	var id_soal_tipe	= $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/soal_tipe',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_soal_tipe : id_soal_tipe
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
    	var id_soal_tipe	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/soal_tipe',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_soal_tipe : id_soal_tipe
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Edit Tipe Soal");
				$.each(data, function(i, v){
					$("#soal_tipe").val(v.soal_tipe);
					$("input[name='id_soal_tipe']").val(v.id_soal_tipe);
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
					$("input[name='waktu']").val(v.waktu);
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
		    	var formData = new FormData($(".form-master-soal_tipe")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/soal_tipe',
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
                title: 'Master Tipe Soal',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            }
        ]
    } );	

});