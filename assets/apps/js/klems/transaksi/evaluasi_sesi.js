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
});

// function save_evaluasi(id_batch,id_karyawan,id_feedback_pertanyaan,nilai,id_feedback_kategori){
function save_evaluasi(id_batch,id_karyawan,id_feedback_pertanyaan,id_feedback_nilai,id_feedback_kategori,id_trainer){
	$.ajax({
		url: baseURL+'klems/transaksi/save/evaluasi',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_batch		 		: id_batch,
			id_karyawan 			: id_karyawan,
			id_feedback_pertanyaan 	: id_feedback_pertanyaan,
			id_feedback_kategori 	: id_feedback_kategori,
			id_feedback_nilai 		: id_feedback_nilai,
			id_trainer		 		: id_trainer
		},
		success: function(data){
			console.log(data);
			if(data.sts == 'OK'){
				// alert(data.msg);
				// location.reload();
			}else{
				// alert(data.msg);
			}
		}
	});
}
