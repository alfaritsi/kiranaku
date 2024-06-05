$(document).ready(function(){
	$("#id_bpo").on("change", function(e){
    	var id_bpo		= $(this).val();
		$("input[name='nama']").val("");
		$("input[name='abbreviation']").val("");
		$("input[name='minimal_soal']").val("");
    });

	$("#nama").on("keyup", function(e){
		var id_bpo		= $("#id_bpo").val();
    	var nama		= $(this).val();
    	$.ajax({
    		url: baseURL+'klems/master/get_data/topik_cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_bpo : id_bpo,
				nama : nama
			},
			success: function(data){
				console.log(data);
				$.each(data, function(i, v){
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
					$("input[name='abbreviation']").val(v.abbreviation);
					$("input[name='minimal_soal']").val(v.minimal_soal);
					$("input[name='tujuan']").val(v.tujuan);
					$("input[name='id_topik']").val(v.id_topik);
				});
			}
		});
    });
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-topik").on("click", function(e){
		var id_topik	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/topik',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_topik : id_topik
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
    	var id_topik = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/topik',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_topik : id_topik
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
    	var id_topik	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/topik',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_topik : id_topik
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Edit Setting Topik");
				$.each(data, function(i, v){
					$("#topik").val(v.topik);
					$("input[name='id_topik']").val(v.id_topik);
					$("select[name='id_bpo']").val(v.id_bpo).trigger('change');
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
					$("input[name='abbreviation']").val(v.abbreviation);
					$("input[name='minimal_soal']").val(v.minimal_soal);
					$("input[name='tujuan']").val(v.tujuan);
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
		    	var formData = new FormData($(".form-master-topik")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/topik',
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
                title: 'Setting Topik',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            }
        ],
		scrollX:true
    } );
});