$(document).ready(function(){
	$("#param").on("change", function(){
		if($(this).val() == "bulan"){
			var output   = "<label for='value'>Nilai</label>";
            output      += "<select class='form-control select2' name='value' id='value' required='required'>";
            for(var i = 1; i<=12; i++){
                output      += "<option value='"+i+"'>"+i+" bulan ke belakang</option>"; 
            }
            output      += "</select>";

            $("#container-value").html(output);

            $("select[name='value']").select2();
		}else{
            $("#container-value").html("");
        }
	});

	 $(document).on("click", "button[name='action_btn']", function(e){
        var empty_form = validate();
        if(empty_form == 0){
        	var isproses        = $("input[name='isproses']").val();
            if(isproses == 0){
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-setting-historybackward")[0]);

                $.ajax({
                    url: baseURL+'pcs/setting/save/historybackward',
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