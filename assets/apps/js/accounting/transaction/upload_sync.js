
$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });


	$(document).on("click", "button[name='view_btn']", function(e){
		e.preventDefault();
        var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
        $("body .overlay-wrapper").append(overlay);
		
		var empty_form = validate(".filter-transaction-upload-sync");

        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);

		    	var formData = new FormData($(".filter-transaction-upload-sync")[0]);

				$.ajax({
					url: baseURL+'accounting/transaction/get_data/upload_rfc',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						$("body .overlay-wrapper").find('.overlay').remove();
						if(data.sts == 'OK'){
		                    $('#filterform').submit();
						}else{
		                    kiranaAlert(data.sts, data.msg, "error", "no");
		                    $("input[name='isproses']").val(0);
						}
					}
				});	
			}else{
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}else{
			$("body .overlay-wrapper").find('.overlay').remove();
		}
    });


	$(document).on("click", "button[name='sync_btn']", function(e){
		e.preventDefault();
        var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
        $("body .overlay-wrapper").append(overlay);
		
		var empty_form = validate(".filter-transaction-upload-sync");

        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);

		    	var formData = new FormData($(".filter-transaction-upload-sync")[0]);

				$.ajax({
					url: baseURL+'accounting/transaction/get_data/upload_rfc',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						$("body .overlay-wrapper").find('.overlay').remove();
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
		// $('#sumberlimbah').attr('disabled', true);
		// e.preventDefault();
		return false;
    });

    $('.datePicker').datepicker({
    	format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
        // startDate: new Date(date)
    });


});
