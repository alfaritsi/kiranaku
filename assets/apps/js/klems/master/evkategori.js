$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-evkategori").on("click", function(e){
		var id_feedback_kategori	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/evkategori',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_kategori : id_feedback_kategori
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
    	var id_feedback_kategori = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/evkategori',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_kategori : id_feedback_kategori
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
    	var id_feedback_kategori	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/evkategori',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_kategori : id_feedback_kategori
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Edit Master Jenis & Kategori");
				$.each(data, function(i, v){
					$("#evkategori").val(v.evkategori);
					$("input[name='id_feedback_kategori']").val(v.id_feedback_kategori);
					$("input[name='kode']").val(v.kode);
					$("select[name='jenis']").val(v.jenis).trigger('change');
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
		    	var formData = new FormData($(".form-master-trainer")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/evkategori',
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