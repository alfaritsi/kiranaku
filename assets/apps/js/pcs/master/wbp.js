$(document).ready(function(){
	$("#plant").on("change", function(e){
    	$("#norma").val("");
    	$("input[name='id_mwbp']").val("");

    	var plant	= $(this).val();
    	$.ajax({
    		url: baseURL+'pcs/master/get_data/wbp',
			type: 'POST',
			dataType: 'JSON',
			data: {
				plant : plant
			},
			success: function(data){
				// console.log(data);
				$.each(data, function(i, v){
					$("#norma").val(v.norma);

					$("input[name='id_mwbp']").val(v.id_mwbp);
				});
			}
		});
    });

	$(".set_active-wbp").on("click", function(e){
    	var id_mwbp	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'pcs/master/set_data/activate/wbp',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mwbp : id_mwbp
			},
			success: function(data){
                if(data.sts == 'OK'){
                    kiranaAlert(data.sts, data.msg);
                }else{
                    kiranaAlert(data.sts, data.msg, "error", "no");
                }
			}
		});
    });

	$(".delete").on("click", function(e){
    	var id_mwbp	= $(this).data("delete");
    	$.ajax({
    		url: baseURL+'pcs/master/set_data/delete/wbp',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mwbp : id_mwbp
			},
			success: function(data){
                if(data.sts == 'OK'){
                    kiranaAlert(data.sts, data.msg);
                }else{
                    kiranaAlert(data.sts, data.msg, "error", "no");
                }
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-wbp")[0]);

				$.ajax({
					url: baseURL+'pcs/master/save/wbp',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
                        if(data.sts == 'OK'){
                            kiranaAlert(data.sts, data.msg);
                        }else{
                            kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					}
				});
			}else{
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
    });
});