$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(".set_active-topik_trainer").on("click", function(e){
		var id_topik_trainer	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/topik_trainer',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_topik_trainer : id_topik_trainer
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
    	var id_topik_trainer = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/topik_trainer',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_topik_trainer : id_topik_trainer
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
    	var id_topik_trainer	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/topik_trainer',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_topik_trainer : id_topik_trainer
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Topik Trainner");
				$.each(data, function(i, v){
					$("#topik").val(v.topik);
					$("input[name='id_topik_trainer']").val(v.id_topik_trainer);
					$("input[name='id_topik']").val(v.id_topik);
					//for check box
					if(v.trainer=='luar'){
						$("#show_trainer_eksternal").show();
						$("#show_trainer_internal").hide();
						$('input[name=trainer]').prop('checked', true);
						$("#id_trainer_eksternal").val(v.id_trainer).trigger('change');
					} else {
						$("#show_trainer_eksternal").hide();
						$("#show_trainer_internal").show();
						$('input[name=trainer]').prop('checked', false);
						$("#id_trainer_internal").val(v.id_trainer).trigger('change');
					}
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
		    	var formData = new FormData($(".form-master-topik_trainer")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/master/save/topik_trainer',
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
                title: 'Setting Topik Trainner',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            }
        ],
		scrollX:true
    } );
	//set on click
    $(document).on("click", "#trainer", function(e){
    	var valcheck = $("#trainer").val();
   		if($('#trainer').is(':checked')){
   			$("#show_trainer_eksternal").show();
   			$("#show_trainer_internal").hide();
   		} else {
   			$("#show_trainer_eksternal").hide();
   			$("#show_trainer_internal").show();
   		}
   		
    });
	
});