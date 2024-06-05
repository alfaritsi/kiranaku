$(document).ready(function(){
    //=======FILTER=======//
	$(document).on("change", "#regional, #nik, #posisi, #program, #pabrik, #tahun", function(){
		var regional= $("#regional").val();
		var nik		= $("#nik").val();
		var posisi	= $("#posisi").val();
		var program	= $("#program").val();
		var pabrik	= $("#pabrik").val();
		var tahun 	= $("#tahun").val();
		$.ajax({
			url: baseURL+'klems/transaksi/approval_filter',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	regional: regional,
	        	nik		: nik,
	        	posisi  : posisi,
	        	program : program,
	        	pabrik 	: pabrik,
	        	tahun	: tahun
	        },
	        success: function(data){
				var output 	= "";
	        	var desc	= "";
				var list_score = "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					//option action
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					output += "					<li><a href='#' class='detail' data-id_program_batch='"+v.id_program_batch+"' data-id_karyawan='"+v.nik+"'><i class='fa fa-search'></i> Detail</a></li>";
					output += "				</ul>";
					output += "	        </div>";
					
					//generate rows
	        		t.row.add( [
			            v.nama_karyawan,
						v.nik,
						v.gsber,
						v.kode_program_batch,
						v.nama_program,
						v.nomor_sertifikat,
						v.nama_ttd_kiri,
			            v.nama_ttd_kanan,
						v.status_print,
						output
			        ] ).draw( false );
	        	});
			
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
	
	//date pitcker
	$('.tanggal').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
		startDate: new Date()
	});
	
});

//set approve
function set_approve(id_karyawan,id_program_batch, posisi){
	$.ajax({
		url: baseURL+'klems/transaksi/save/approve',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_karyawan 		: id_karyawan,
			posisi				: posisi,
			id_program_batch	: id_program_batch
		},
		success: function(data){
			console.log(data);
			if(data.sts == 'OK'){
				alert(data.msg);
				location.reload();
			}else{
				alert(data.msg);
			}
		}
	});
}
//cancel approve
function cancel_approve(id_karyawan,id_program_batch, posisi){
	$.ajax({
		url: baseURL+'klems/transaksi/save/cancel',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_karyawan 		: id_karyawan,
			posisi				: posisi,
			id_program_batch	: id_program_batch
		},
		success: function(data){
			console.log(data);
			if(data.sts == 'OK'){
				alert(data.msg);
				location.reload();
			}else{
				alert(data.msg);
			}
		}
	});

}
// //cetak
// function cetak(id_karyawan,id_program_batch){
	// alert('aa');
	// $.ajax({
		// url: baseURL+'klems/transaksi/cetak',
		// type: 'POST',
		// dataType: 'JSON',
		// data: {
			// id_karyawan 		: id_karyawan,
			// id_program_batch	: id_program_batch
		// },
		// success: function(data){
			// console.log(data);
			// if(data.sts == 'OK'){
				// alert(data.msg);
				// location.reload();
			// }else{
				// alert(data.msg);
			// }
		// }
	// });
// }