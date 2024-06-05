$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "depo/master/set/lokasi",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_lokasi : $(this).data($(this).attr("class")),	
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
    	var id_lokasi	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'depo/master/get/lokasi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_lokasi : id_lokasi
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Master lokasi");
				$.each(data, function(i, v){
					$("#id_lokasi").val(v.id_lokasi);
					$("input[name='nama']").val(v.nama);
					$("select[name='mandatory']").val(v.mandatory).trigger("change.select2");
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-lokasi");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-lokasi")[0]);

				$.ajax({
					url: baseURL+'depo/master/save/lokasi',
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
                title: 'Master Lokasi',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1]
                }
            }
        ]
    } );	

});
