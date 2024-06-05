$(document).ready(function(){
    $(document).on("keyup", ".cek_min_max", function(e){
        var nilai = $(this).val();
		if(nilai<0){
			alert('Nilai Minimal 0');
			$(this).val(0);
		}
		if(nilai>100){
			alert('Nilai Miksimal 100');
			$(this).val(100);
		}
    });
	
    $("#add_button_save").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	
	//export to excel
	$('.my-datatable-extends-order').DataTable();
	// $('.my-datatable-extends-order-width').dataTable( {  "columnDefs": [    { "width": "5%", "targets": 3 }  ]} );	
});

function save_score(id_batch,id_peserta,id_batch_nilai,score,id_karyawan){
	var score = score.value;
	$.ajax({
		url: baseURL+'klems/transaksi/save/score',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_batch 		: id_batch,
			id_peserta 		: id_peserta,
			id_batch_nilai 	: id_batch_nilai,
			id_karyawan 	: id_karyawan,
			score 			: score
		},
		success: function(data){
			console.log(data);
			if(data.sts == 'OK'){
				// alert(data.msg);
				// location.reload();
			}else{
				alert(data.msg);
			}
		}
	});
}
function save_alasan(id_batch,id_peserta,id_batch_nilai,alasan,id_karyawan){
	
	var alasan = alasan.value;
	$.ajax({
		url: baseURL+'klems/transaksi/save/alasan',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_batch 		: id_batch,
			id_peserta 		: id_peserta,
			id_batch_nilai 	: id_batch_nilai,
			id_karyawan 	: id_karyawan,
			alasan 			: alasan
		},
		success: function(data){
			console.log(data);
			if(data.sts == 'OK'){
				// alert(data.msg);
				// location.reload();
			}else{
				alert(data.msg);
			}
		}
	});
}

