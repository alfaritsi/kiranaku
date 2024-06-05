$(document).ready(function(){
	$("#nama").on("keyup", function(e){
		var id_nilai_kategori	= $("#id_nilai_kategori").val();
    	var nama				= $(this).val();
    	$.ajax({
    		url: baseURL+'klems/master/get_data/nil_nilai_cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_nilai_kategori : id_nilai_kategori,
				nama 			  : nama
			},
			success: function(data){
				console.log(data);
				$.each(data, function(i, v){
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
					$("select[name='id_nilai_kategori']").val(v.id_nilai_kategori).trigger('change');
					$("input[name='id_nilai']").val(v.id_nilai);
				});
			}
		});
    });
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-nilai").on("click", function(e){
		var id_nilai	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/nil_nilai',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_nilai : id_nilai
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
    	var id_nilai = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/nil_nilai',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_nilai : id_nilai
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
    	var id_nilai	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/nil_nilai',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_nilai : id_nilai
			},
			success: function(data){
				$(".title-form").html("Edit Master Penilaian");
				$.each(data, function(i, v){
					$("#nil_nilai").val(v.nil_nilai);
					$("input[name='id_nilai']").val(v.id_nilai);
					$("input[name='kode']").val(v.kode);
					$("select[name='id_nilai_kategori']").val(v.id_nilai_kategori).trigger('change');
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
		    	var formData = new FormData($(".form-master-nilai")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/nil_nilai',
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
                title: 'Master  Penilaian',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            }
        ]
    } );	
});