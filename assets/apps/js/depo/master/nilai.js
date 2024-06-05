$(document).ready(function(){

	$(document).on("click", ".edit", function (e) {	
    	var keterangan	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'depo/master/get/nilai',
			type: 'POST',
			dataType: 'JSON',
			data: {
				keterangan : keterangan
			},
			success: function(data){
				$(".title-form").html("Form Master Nilai");
				$.each(data, function(i, v){
					$("input[name='nilai_awal']").val(v.nilai_awal);
					$("input[name='nilai_akhir']").val(v.nilai_akhir);
					$("input[name='keterangan']").val(v.keterangan);
				});
				
				$('#nilai_awal').prop('readonly', false);
				$('#nilai_akhir').prop('readonly', false);
			}
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-nilai");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-nilai")[0]);

				$.ajax({
					url: baseURL+'depo/master/save/nilai',
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
                title: 'Master Grade Nilai',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3]
                }
            }
        ]
    } );	

});
