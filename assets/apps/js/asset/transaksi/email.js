$(document).ready(function(){
	//edit	
	$(".edit").on("click", function(e){
		var id_email	= $(this).data("id_email");
		var id_karyawan	= $(this).data("id_karyawan");
		var update		= $(this).data("update");
		var value_apar	= $(this).data("value_apar");
		var value_lab	= $(this).data("value_lab");
		$.ajax({
    		url: baseURL+'asset/transaksi/save/email',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_email 	: id_email,
				id_karyawan : id_karyawan,
				update 		: update,
				value_apar  : value_apar,
				value_lab   : value_lab
			},
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
    });
	
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Maintenance Email',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            }
        ],
		scrollX:true
    } );
});