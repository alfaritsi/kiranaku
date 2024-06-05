$(document).ready(function(){
	$("#tahun").on("change", function(e){
    	$("#budget_training").val("");
    	$("#budget_traveling").val("");
    	$("input[name='id_program_budget']").val("");
    	var id_program	= $("#id_program").val();
    	var tahun		= $(this).val();
    	$.ajax({
    		url: baseURL+'klems/master/get_data/program_budget_cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program 	: id_program,
				tahun 		: tahun
			},
			success: function(data){
				// console.log(data);
				$.each(data, function(i, v){
					$("input[name='budget_training']").val(numberWithCommas(v.budget_training));
					$("input[name='budget_traveling']").val(numberWithCommas(v.budget_traveling));
					$("input[name='id_program']").val(v.id_program);
					$("input[name='id_program_budget']").val(v.id_program_budget);
				});
			}
		});
    });
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(".set_active-program_budget").on("click", function(e){
		var id_program_budget	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/program_budget',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_budget : id_program_budget
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
    	var id_program_budget = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/program_budget',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_budget : id_program_budget
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
    	var id_program_budget	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/program_budget',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_budget : id_program_budget
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Budget Program");
				$.each(data, function(i, v){
					$("#program").val(v.program);
					$("input[name='id_program_budget']").val(v.id_program_budget);
					$("input[name='id_program']").val(v.id_program);
					$("input[name='kode_program']").val(v.kode_program);
					$("select[name='tahun']").val(v.tahun).trigger('change');
					$("input[name='budget_training']").val(numberWithCommas(v.budget_training));
					$("input[name='budget_traveling']").val(numberWithCommas(v.budget_traveling));
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
		    	var formData = new FormData($(".form-master-program_budget")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/master/save/program_budget',
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
                title: 'Setting Budget Program',
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