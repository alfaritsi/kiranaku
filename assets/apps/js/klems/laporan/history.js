$(document).ready(function(){
    //=======FILTER=======//
	$(document).on("change", "#regional, #nik, #posisi, #program, #pabrik, #awal, #akhir", function(){
		var regional= $("#regional").val();
		var nik		= $("#nik").val();
		var posisi	= $("#posisi").val();
		var program	= $("#program").val();
		var pabrik	= $("#pabrik").val();
		var awal 	= $("#awal").val();
		var akhir 	= $("#akhir").val();
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
	        	awal	: awal,
	        	akhir	: akhir
	        },
	        success: function(data){
				var output 	= "";
	        	var desc	= "";
				var list_score = "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					var list = [
								v.nik,
								v.nama_karyawan,
								v.gsber,
								v.posisi_batch,
								v.posisi_sekarang,
								v.tanggal_join,
								v.gbpas,
								v.kode_program_batch,
								v.nama_program_batch,
								v.nama_program,
								v.tanggal_awal_batch+' sd '+v.tanggal_akhir_batch
								];
					var score		= v.list_nilai_tahap.slice(0, -1).split(",");
					$.each(score, function(x, y){	
						list.push(parseFloat(y).toFixed(2));
					});
					list.push(v.average);					
					
					console.log(score);
					//generate rows
	        		t.row.add(list).draw( false );
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
                title: 'Laporan Histori Penilaian',
                download: 'open',
                orientation:'landscape'
            }
        ],
		scrollX:true
    } );
	
	//date pitcker
	$('.tanggal').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true,
		// startDate: new Date()
	});
	
});