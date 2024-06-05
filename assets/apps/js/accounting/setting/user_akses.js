
$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active").on("click", function(e){
    	var id	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'accounting/setting/set_data/activate/user',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
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
    	var id	= $(this).data("delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menonaktifkan data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'accounting/setting/set_data/deactivate/user',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id : id
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );
    });


	$(".edit").on("click", function(e){
    	var id	= $(this).data("edit");
    	$(".title-form").html("Edit Akses User");

    	$.ajax({
    		url: baseURL+'accounting/setting/get_data/user',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function(i, v){
					$("#tipe").val(v.tipe);
					document.getElementById("nik").value = v.nik;
					
					var pabrik = v.pabrik.split(", ");
					$("#pabrik").val(pabrik);

					$(".select2").select2();
                	$('#pabrik').multiselect('resync');

					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });


	$("#nik").change(function() {
    	var id	= this.value;
    	$.ajax({
    		url: baseURL+'accounting/setting/get_data/nik',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				// console.log(data);
				$.each(data, function(i, v){ 
					if(v.ho == "n"){
						var lokasi = "Pabrik";
					}else{
						var lokasi = "HO";
					}
					$("#tipe").val(lokasi);					
				});
			}
		});
    });


	$(document).on("click", "button[name='action_btn']", function(e){
		e.preventDefault();
		
		var empty_form = validate(".form-user");

        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);

		    	var formData = new FormData($(".form-user")[0]);

				$.ajax({
					url: baseURL+'accounting/setting/save/access',
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

    $('#pabrik').multiselect({
        classes: 'form-control',
        buttonWidth: '100%',
        menuHeight: '200px',
        menuWidth: '100%'
    }).multiselectfilter();


});
