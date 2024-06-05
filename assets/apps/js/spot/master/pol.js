$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "spot/master/set/pol",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_spot_setting_pol 	: $(this).data($(this).attr("class")),	
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
	//set on change plant for get lgort(storage location)
    $(document).on("change", "#port", function(e){
		var port	= $(this).val();
		$.ajax({
    		url: baseURL+'spot/master/get/pol',
			type: 'POST',
			dataType: 'JSON',
			data: {
				port : port
			},
			success: function(data){
				console.log(data);
				if(data){
					$(".title-form").html("Setting Port Of Load");
					$("input[name='id_spot_setting_pol']").val('');
					$("input[name='no_urut']").val('');
					$("input[name='selisih']").val('');
					$("select[name='plant[]']").val('').trigger("change");
					$.each(data, function(i, v){
						$("input[name='id_spot_setting_pol']").val(v.id_spot_setting_pol);	
						$("input[name='no_urut']").val(v.no_urut);
						$("input[name='selisih']").val(v.selisih);
						if(v.werks!==null){
							var in_werks	= v.werks.split(",");
							$("select[name='plant[]']").val(in_werks).trigger("change");
						}
						$("#btn-new").removeClass("hidden");
					});
					
				}
			}
		});
    });
	
	$(".edit").on("click", function(e){
    	var id_spot_setting_pol	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'spot/master/get/pol',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_spot_setting_pol : id_spot_setting_pol
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Setting Port Of Load");
				$.each(data, function(i, v){
					$("input[name='id_spot_setting_pol']").val(v.id_spot_setting_pol);
					$("select[name='port']").val(v.port).trigger('change');
					$("input[name='no_urut']").val(v.no_urut);
					$("input[name='selisih']").val(v.selisih);
					if(v.werks!==null){
						var in_werks	= v.werks.split(",");
						$("select[name='plant[]']").val(in_werks).trigger("change");
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
		    	var formData = new FormData($(".form-master-pol")[0]);

				$.ajax({
					url: baseURL+'spot/master/save/pol',
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
                title: 'Port Of Load',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            }
        ]
    } );	
});