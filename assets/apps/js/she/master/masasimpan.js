$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-jenis").on("click", function(e){
    	var id_mjenis	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'she/master/set_data/activate/masasimpan',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mjenis : id_mjenis
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
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/master/set_data/delete_del0/masasimpan',
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
    	$.ajax({
    		url: baseURL+'she/master/get_data/masasimpan',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("<i class='fa fa-pencil-square-o'></i> Edit Masa Simpan Limbah");
				$.each(data, function(i, v){
					document.getElementById("pabrik").value = v.fk_pabrik;
					$("#jenis_limbah").val(v.jenis_limbah);
					$("#masasimpan").val(v.masa_simpan);
					$('.select2').select2();

					$("#id").val(v.id);
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
		    	var formData = new FormData($(".form-master-masasimpan")[0]);

				$.ajax({
					url: baseURL+'she/master/save/masasimpan',
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

	$("#konversiton").keyup(function() {
		var konversi = $("#konversiton").val();
	  	$("#konversiton").val(konversi.replace(",","."));
    });

});