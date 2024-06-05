$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			// url: baseURL + "vendor/master/set/master_role",
			url: baseURL + "bank/master/set/role",
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
    		// url: baseURL+'vendor/master/get/role',
    		url: baseURL+'bank/master/get/role',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_role : id_role
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Form Role Bank Specimen");
				$.each(data, function(i, v){
					$("#id_role").val(v.id_role);
					$("input[name='nama']").val(v.nama);
					$("input[name='level']").val(v.level);
					$("select[name='tipe_user']").val(v.tipe_user).trigger("change.select2");
					$("select[name='if_approve']").val(v.if_approve).trigger("change.select2");
					$("select[name='if_decline']").val(v.if_decline).trigger("change.select2");
					$("select[name='if_approve_perubahan']").val(v.if_approve_perubahan).trigger("change.select2");
					$("select[name='if_decline_perubahan']").val(v.if_decline_perubahan).trigger("change.select2");
					$("select[name='if_approve_penutupan']").val(v.if_approve_penutupan).trigger("change.select2");
					$("select[name='if_decline_penutupan']").val(v.if_decline_penutupan).trigger("change.select2");
					$("select[name='if_approve_ho']").val(v.if_approve_ho).trigger("change.select2");
					$("select[name='if_decline_ho']").val(v.if_decline_ho).trigger("change.select2");
					$("select[name='if_approve_perubahan_ho']").val(v.if_approve_perubahan_ho).trigger("change.select2");
					$("select[name='if_decline_perubahan_ho']").val(v.if_decline_perubahan_ho).trigger("change.select2");
					$("select[name='if_approve_penutupan_ho']").val(v.if_approve_penutupan_ho).trigger("change.select2");
					$("select[name='if_decline_penutupan_ho']").val(v.if_decline_penutupan_ho).trigger("change.select2");
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-role-bank_specimen");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-role-bank_specimen")[0]);
				$.ajax({
					// url: baseURL+'vendor/master/save/role',
					url: baseURL+'bank/master/save/role',
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
                title: 'Master Role Bank Specimen',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2]
                }
            }
        ]
    } );	

});
