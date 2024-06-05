$(document).ready(function(){
    $(document).on("change", "#tipe", function(e){
		var tipe = $("#tipe").val();
   		if(tipe=='Approver'){
   			$("#show_posisi").hide();
   			$("#show_divisi").show();
   			$("#show_seksi").show();
			$('#posisi').prop('required', false);
			$('#divisi').prop('required', true);
			$('#seksi').prop('required', true);
   		}
   		if(tipe=='Requestor'){
   			$("#show_posisi").show();
   			$("#show_divisi").hide();
   			$("#show_seksi").hide();
			$('#posisi').prop('required', true);
			$('#divisi').prop('required', false);
			$('#seksi').prop('required', false);
   		}
    });
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "material/master/set/role",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_setting_user 	: $(this).data($(this).attr("class")),	
				type 	  	 			: $(this).attr("class")
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
    	var id_item_setting_user	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'material/master/get/role',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_setting_user : id_item_setting_user
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Master Role");
				$.each(data, function(i, v){
					$("input[name='id_item_setting_user']").val(v.id_item_setting_user);
					$("select[name='id_item_master_pic']").val(v.id_item_master_pic).trigger('change');
					$("select[name='tipe']").val(v.tipe).trigger('change');
					if(v.posisi!==null){
						var id_posisi	= v.posisi.split(",");
						$("select[name='posisi[]']").val(id_posisi).trigger("change");
					}
					if(v.divisi!==null){
						var id_divisi	= v.divisi.split(",");
						$("select[name='divisi[]']").val(id_divisi).trigger("change");
					}
					if(v.seksi!==null){
						var id_seksi	= v.seksi.split(",");
						$("select[name='seksi[]']").val(id_seksi).trigger("change");
					}
					//
					if(v.tipe=='Approver'){
						$('#posisi').prop('required', false);
						$('#divisi').prop('required', true);
						$('#seksi').prop('required', true);
					}
					if(v.tipe=='Requestor'){
						$('#posisi').prop('required', true);
						$('#divisi').prop('required', false);
						$('#seksi').prop('required', false);
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
		    	var formData = new FormData($(".form-master-role")[0]);

				$.ajax({
					url: baseURL+'material/master/save/role',
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
                title: 'Master Role',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            }
        ]
    } );	

});