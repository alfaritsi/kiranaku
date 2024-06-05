/*
@application    : Email Routing
@author         : 
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function(){

	//Date picker
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });


    $("#periode").on("change", function(e){
		$('#select_report').val("NULL").trigger("change");
		var periode = $(this).val();
		$.ajax({
			url: baseURL+'routing/master/get_data/report_by_periode',
			type: 'POST',
			dataType: 'JSON',
			data: {
				periode : periode
			},
			success: function(data){
				var output  = "";
                $.each(data, function(i,v){
                    output  += "<option value='"+v.id_report+"'>"+v.report_name+"</option>";
                });
                $("#select_report").html(output);
                $("#select_report").val(0).trigger("change");
			}
		});
	});
	
	$(".delete").on("click", function(e){
    	var id_topic	= $(this).data("delete");
    	$.ajax({
    		url: baseURL+'routing/master/set_data/delete/topic',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_topic : id_topic
			},
			success: function(data){
				if(data.sts == 'OK'){
					alert(data.msg);
					location.reload();
				}else{
					alert(data.msg);
				}
			}
		});
    });

	$(".set_active-topic").on("click", function(e){
    	var id_topic	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'routing/master/set_data/activate/topic',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_topic : id_topic
			},
			success: function(data){
				if(data.sts == 'OK'){
					alert(data.msg);
					location.reload();
				}else{
					alert(data.msg);
				}
			}
		});
    });

	$(".edit").on("click", function(e){
    	var id_topic	= $(this).data("edit");
    	$.ajax({
    		url: baseURL+'routing/master/get_data/topic',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_topic : id_topic
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Edit Topic");
				$.each(data, function(i, v){
					$("select[name='topic_app']").val(v.topic_app).trigger('change');
					$("#topic_code").val(v.topic_code);
					$("#topic").val(v.topic);
					$("#frekuensi").val(v.frekuensi);
					$("#start_date").val(v.start_date);
					$("#last_send_log").val(v.last_send_log);
					$("select[name='periode']").val(v.periode).trigger('change');

					if(v.report_kode_list){
						setTimeout(function(){
							var kode_report	= v.report_kode_list.replace(/,\s*$/, "").split(",").map(function(item) {
											    return parseInt(item);
											});;
							$('#select_report').val(kode_report).trigger("change");
							// console.log($('#select_report').val());
							// console.log(kode_report);
						}, 1000);
					}

					$("input[name='id_topic']").val(v.id_topic);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(document).on("click", "button[name='action_btn']", function(e){
        var empty_form = validate();
        if(empty_form == 0){	    	
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-topic")[0]);

				$.ajax({
					url: baseURL+'routing/master/save/topic',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						// console.log(data);
						if(data.sts == 'OK'){
							alert(data.msg);
							location.reload();
						}else{
							alert(data.msg);
							$("input[name='isproses']").val(0);
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
});