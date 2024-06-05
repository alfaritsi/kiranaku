$(document).ready(function(){
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Penilaian',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            }
        ],
		scrollX:true
    } );
	$('.my-datatable-extends-order-width').dataTable( {  "columnDefs": [    { "width": "5%", "targets": 3 }  ]} );	
	
	$(".upload").on("click", function(e){
		var id_batch	= $(this).data("batch");
		var id_karyawan	= $(this).data("karyawan");
		var id_peserta	= $(this).data("peserta");
		var id_trainer	= $(this).data("trainer");
		$("input[name='id_batch']").val(id_batch);
		$("input[name='id_karyawan']").val(id_karyawan);
		$("input[name='id_peserta']").val(id_peserta);
		$("input[name='id_trainer']").val(id_trainer);
		$('#add_modal').modal('show');
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		alert('aa');
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-upload_feedback")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/transaksi/save/upload',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						if(data.sts == 'OK'){
							alert(data.msg);
							location.reload();
						}else{
							alert(data.msg);
							$("input[name='isproses']").val(0);
						}
					}
				});
			}else{
				alert("Silahkan tunggu proses selesai.");
			}
		}
		e.preventDefault();
		return false;
    });
	
});
