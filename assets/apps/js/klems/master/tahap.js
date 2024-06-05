$(document).ready(function(){
	$("#nama").on("change", function(e){
    	var id_bpo		= $("#id_bpo").val();
    	var id_program	= $("#id_program").val();
    	var nama		= $(this).val();
    	$.ajax({
    		url: baseURL+'klems/master/get_data/tahap_cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_bpo 		: id_bpo,
				id_program 	: id_program,
				nama 		: nama
			},
			success: function(data){
				// console.log(data);
				$.each(data, function(i, v){
					$("input[name='kode']").val(v.kode);
					$("input[name='id_tahap']").val(v.id_tahap);
					var topik	= v.topik.split(",");
					$("select[name='topik[]']").val(topik).trigger("change");
					$("input[name='keterangan']").val(v.keterangan);
				});
			}
		});
    });
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(".set_active-tahap").on("click", function(e){
		var id_tahap	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/tahap',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tahap : id_tahap
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
    	var id_tahap = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/tahap',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tahap : id_tahap
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
		var id_tahap	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/tahap',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tahap : id_tahap
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Tahap");
				$.each(data, function(i, v){
					$("#tahap").val(v.tahap);
					$("input[name='id_tahap']").val(v.id_tahap);
					$("select[name='id_bpo']").val(v.id_bpo).trigger("change");
					$("select[name='id_program']").val(v.id_program).trigger("change");
					$("input[name='kode']").val(v.kode);
					$("select[name='nama']").val(v.nama).trigger("change");
					var topik	= v.topik.split(",");
					$("select[name='topik[]']").val(topik).trigger("change");
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
		    	var formData = new FormData($(".form-master-tahap")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/master/save/tahap',
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
                title: 'Setting Tahap',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            }
        ],
		scrollX:true
    } );
    //cek all topik
    $(document).on("change", ".isSelectAllTopik", function(e){
        if($(".isSelectAllTopik").is(':checked')) {
            $('#topik').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#topik').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
});