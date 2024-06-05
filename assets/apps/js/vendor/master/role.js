$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			// url: baseURL + "material/master/set/group",
			// url: baseURL + "vendor/master/set/tipe",
			url: baseURL + "vendor/master/set/master_role",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_role : $(this).data($(this).attr("class")),	
				type 	: $(this).attr("class")
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

	$(document).on("click", ".edit", function (e) {	
    	var id_role	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'vendor/master/get/role',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_role : id_role
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Role Master Vendor");
				$.each(data, function(i, v){
					$("#id_role").val(v.id_role);
					$("input[name='nama']").val(v.nama);
					$("input[name='level']").val(v.level);
					//ho
					$("select[name='if_approve_create_ho']").val(v.if_approve_create_ho).trigger("change.select2");
					$("select[name='if_decline_create_ho']").val(v.if_decline_create_ho).trigger("change.select2");
					$("select[name='if_approve_create_legal_ho']").val(v.if_approve_create_legal_ho).trigger("change.select2");
					$("select[name='if_decline_create_legal_ho']").val(v.if_decline_create_legal_ho).trigger("change.select2");
					$("select[name='if_approve_change_ho']").val(v.if_approve_change_ho).trigger("change.select2");
					$("select[name='if_decline_change_ho']").val(v.if_decline_change_ho).trigger("change.select2");
					$("select[name='if_approve_change_legal_ho']").val(v.if_approve_change_legal_ho).trigger("change.select2");
					$("select[name='if_decline_change_legal_ho']").val(v.if_decline_change_legal_ho).trigger("change.select2");
					$("select[name='if_approve_extend_ho']").val(v.if_approve_extend_ho).trigger("change.select2");
					$("select[name='if_decline_extend_ho']").val(v.if_decline_extend_ho).trigger("change.select2");
					$("select[name='if_approve_delete_ho']").val(v.if_approve_delete_ho).trigger("change.select2");
					$("select[name='if_decline_delete_ho']").val(v.if_decline_delete_ho).trigger("change.select2");
					$("select[name='if_approve_undelete_ho']").val(v.if_approve_undelete_ho).trigger("change.select2");
					$("select[name='if_decline_undelete_ho']").val(v.if_decline_undelete_ho).trigger("change.select2");
					//pabrik
					$("select[name='if_approve_create_pabrik']").val(v.if_approve_create_pabrik).trigger("change.select2");
					$("select[name='if_decline_create_pabrik']").val(v.if_decline_create_pabrik).trigger("change.select2");
					$("select[name='if_approve_create_legal_pabrik']").val(v.if_approve_create_legal_pabrik).trigger("change.select2");
					$("select[name='if_decline_create_legal_pabrik']").val(v.if_decline_create_legal_pabrik).trigger("change.select2");
					$("select[name='if_approve_change_pabrik']").val(v.if_approve_change_pabrik).trigger("change.select2");
					$("select[name='if_decline_change_pabrik']").val(v.if_decline_change_pabrik).trigger("change.select2");
					$("select[name='if_approve_change_legal_pabrik']").val(v.if_approve_change_legal_pabrik).trigger("change.select2");
					$("select[name='if_decline_change_legal_pabrik']").val(v.if_decline_change_legal_pabrik).trigger("change.select2");
					$("select[name='if_approve_extend_pabrik']").val(v.if_approve_extend_pabrik).trigger("change.select2");
					$("select[name='if_decline_extend_pabrik']").val(v.if_decline_extend_pabrik).trigger("change.select2");
					$("select[name='if_approve_delete_pabrik']").val(v.if_approve_delete_pabrik).trigger("change.select2");
					$("select[name='if_decline_delete_pabrik']").val(v.if_decline_delete_pabrik).trigger("change.select2");
					$("select[name='if_approve_undelete_pabrik']").val(v.if_approve_undelete_pabrik).trigger("change.select2");
					$("select[name='if_decline_undelete_pabrik']").val(v.if_decline_undelete_pabrik).trigger("change.select2");
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-role");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-role")[0]);

				$.ajax({
					url: baseURL+'vendor/master/save/role',
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
                title: 'Master Tipe Supplier',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2]
                }
            }
        ]
    } );	

});
