$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "depo/master/set/dokumen",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_dokumen : $(this).data($(this).attr("class")),	
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
    	var id_dokumen	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'depo/master/get/dokumen',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_dokumen : id_dokumen
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Master Dokumen");
				$.each(data, function(i, v){
					$("#id_dokumen").val(v.id_dokumen);
					$("select[name='jenis_depo']").val(v.jenis_depo).trigger("change.select2");
					$("input[name='nama']").val(v.nama);
					$("select[name='mandatory']").val(v.mandatory).trigger("change.select2");
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-dokumen");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-dokumen")[0]);

				$.ajax({
					url: baseURL+'depo/master/save/dokumen',
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
                title: 'Master Dokumen',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3]
                }
            }
        ]
    } );	

});
