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
			url: baseURL+'klems/laporan/history_filter',
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
					var score	= v.list_nilai_tahap.slice(0, -1).split(",");
					// $.each(score, function(x, y){	
						// list_score += y.average+',';
					// }		
					console.log(score);
					//generate rows
	        		t.row.add( [
			            v.nama_karyawan,
						v.nik,
						v.gsber,
						v.kode_program_batch,
						v.nama_program,
						v.tanggal_awal_batch+' sd '+v.tanggal_akhir_batch,
			            v.average,
						v.average,
						v.average,
						v.average,
						v.average
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