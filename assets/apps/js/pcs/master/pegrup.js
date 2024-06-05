$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-pegrup").on("click", function(e){
    	var id_mpegrup	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'pcs/master/set_data/activate/pegrup',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mpegrup : id_mpegrup
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
    	var id_mpegrup	= $(this).data("delete");
    	$.ajax({
    		url: baseURL+'pcs/master/set_data/delete/pegrup',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mpegrup : id_mpegrup
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

	$(".edit").on("click", function(e){
    	var id_mpegrup	= $(this).data("edit");
    	$.ajax({
    		url: baseURL+'pcs/master/get_data/pegrup',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mpegrup : id_mpegrup
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Edit PE Grup");
				$("input[name='id_mpegrup']").val(data.id);
				$.each(data.data, function(i, v){
					$("#nama_pegrup").val(v.nama_grup);
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
		    	var formData = new FormData($(".form-master-pegrup")[0]);

				$.ajax({
					url: baseURL+'pcs/master/save/pegrup',
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