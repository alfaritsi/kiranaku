$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			// url: baseURL + "material/master/set/group",
			url: baseURL + "vendor/master/set/tipe",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tipe : $(this).data($(this).attr("class")),	
				type 	: $(this).attr("class")
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

	$(document).on("click", ".edit", function (e) {	
    	var id_tipe	= $(this).data("edit");
		$.ajax({
    		// url: baseURL+'material/master/get/group',
    		url: baseURL+'vendor/master/get/tipe',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tipe : id_tipe
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Master Tipe Supplier");
				$.each(data, function(i, v){
					$("#id_tipe").val(v.id_tipe);
					$("input[name='nama']").val(v.nama);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-tipe");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-tipe")[0]);

				$.ajax({
					url: baseURL+'vendor/master/save/tipe',
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
                title: 'Master Tipe Supplier',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2]
                }
            }
        ]
    } );	

});
