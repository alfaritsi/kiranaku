
$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$("#chkall").change(function() {
    	var checkbox = this.checked;
		var chk_arr =  document.getElementsByName("chkdok[]");
		var chklength = chk_arr.length;             

		if (checkbox == false){
			for(k=0;k< chklength;k++){ 
				chk_arr[k].checked = false;
			}         
		}else{
			for(k=0;k< chklength;k++){ 
				chk_arr[k].checked = true;
			} 
		}      

    });

	$(document).on("click", "button[name='approve_btn']", function(e){
		e.preventDefault();
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan mengapprove data?",
                dangerMode: true,
                successCallback: function () {

					var empty_form = validate(".form-approval");

			        if(empty_form == 0){
				    	var isproses 		= $("input[name='isproses']").val();
				    	if(isproses == 0){
				    		$("input[name='isproses']").val(1);

					    	var formData = new FormData($(".form-approval")[0]);

							$.ajax({
								url: baseURL+'accounting/transaction/set_data/update/approve_request',
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
                }
            }
        );
    });


	$(document).on("click", "button[name='reject_btn']", function(e){
		e.preventDefault();
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan mereject data?",
                dangerMode: true,
                successCallback: function () {

							
					var empty_form = validate(".form-approval");

			        if(empty_form == 0){
				    	var isproses 		= $("input[name='isproses']").val();
				    	if(isproses == 0){
				    		$("input[name='isproses']").val(1);

					    	var formData = new FormData($(".form-approval")[0]);

							$.ajax({
								url: baseURL+'accounting/transaction/set_data/update/reject_request',
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
                }
            }
        );

    });


    $('.datePicker').datepicker({
    	format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
        // startDate: new Date(date)
    });


});


