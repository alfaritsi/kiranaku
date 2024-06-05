$(document).ready(function(){
    //open modal for imp    
	$(document).on("click", "#imp_button", function(e){
		resetForm_use($('.form-master-group-imp'));
		$('#imp_modal').modal('show');
	});
	//imp
	$(document).on("click", "button[name='action_btn_imp']", function(e){
		var empty_form = validate('.form-master-group-imp');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-group-imp")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'material/master/save/excel_group',
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
	
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	// $(document).on("change", "#description", function(){
		// var description = $("#description").val();
		// $.ajax({
    		// url: baseURL+'material/master/get/group',
			// type: 'POST',
			// dataType: 'JSON',
			// data: {
				// description : description
			// },
			// success: function(data){
				// console.log(data);
				// $(".title-form").html("Form Item Group");
				// $.each(data, function(i, v){
					// $("#id_item_group").val(v.bpo);
					// $("input[name='id_item_group']").val(v.id_item_group);
					// $("input[name='description']").val(v.description);
					// $("select[name='mtart']").val(v.mtart).trigger('change');
					// $("#btn-new").removeClass("hidden");
				// });
			// }
		// });
	// });		
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "material/master/set/group",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_group 	: $(this).data($(this).attr("class")),	
				type 	  	 	: $(this).attr("class")
			},
			success: function(data){
				if(data.sts == 'OK'){
					kiranaAlert(data.sts, data.msg);
				}else{
					kiranaAlert("notOK", data.msg, "warning", "no");
				}
			}
		});
		e.preventDefault();
		return false;
	});	

	$(".edit").on("click", function(e){
    	var id_item_group	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'material/master/get/group',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_group : id_item_group
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Master Item Group");
				$.each(data, function(i, v){
					$("#id_item_group").val(v.bpo);
					$("input[name='id_item_group']").val(v.id_item_group);
					$("input[name='description']").val(v.description);
					$("select[name='mtart']").val(v.mtart).trigger('change');
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
		    	var formData = new FormData($(".form-master-group")[0]);

				$.ajax({
					url: baseURL+'material/master/save/group',
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
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Item Group',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3]
                }
            }
        ]
    } );	

});
function resetForm_use($form,$act) {
	$('#myModalLabel').html("Form Item Spec");
	$('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
	$form.find('input:text, input:password, input:file,  textarea').val("");
	$form.find('input:text, input:password, input:file,  textarea').prop('disabled', false);
	$form.find('select').val(0);
	$form.find('select').prop('disabled', false);
	$form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
	$form.find('input:radio, input:checkbox').prop('disabled', false);

	// $('#service_level').val("").prop('disabled', false);
	$('#net_weight').val("").prop('disabled', false);
	$('#gross_weight').val("").prop('disabled', false);
	$("#plant").val(0).trigger("change");
	$("#sales_plant").val(0).trigger("change");
	$('#plant_extend').prop('disabled', false);
	if($act!='edit'){
		$("#show_images").hide();
	}
	$("#gambar").show();	
	$("#btn_save").show();
	$('#isproses').val("");
	$('#isconvert').val('0');
	$('#code').prop('disabled', true);
	$('#detail').prop('disabled', true);
}
