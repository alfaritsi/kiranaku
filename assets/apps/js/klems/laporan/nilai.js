$(document).ready(function(){
    //=======FILTER=======//
	$(document).on("change", "#program, #pabrik, #awal, #akhir", function(){
		var program	= $("#program").val();
		var pabrik	= $("#pabrik").val();
		var awal 	= $("#awal").val();
		var akhir 	= $("#akhir").val();
		$.ajax({
			url: baseURL+'klems/laporan/nilai_filter',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	program : program,
	        	pabrik 	: pabrik,
	        	awal 	: awal,
	        	akhir	: akhir
	        },
	        success: function(data){
				var output 	= "";
	        	var desc	= "";
				
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
					console.log(v);
					//generate rows
	        		t.row.add( [
			            v.nama_karyawan,
						v.nik,
						v.gsber,
						v.kode_program_batch,
						v.nama_program_batch,
						v.nama_program,
						v.tanggal_awal_batch+' sd '+v.tanggal_akhir_batch,
			            v.average,
						v.grade,
						output
			        ] ).draw( false );
	        	});
			
	        }
		});
	});
	
	//detail
	$(document).on("click", ".detail", function(e){
		var id_program_batch	= $(this).data("id_program_batch");
		var id_karyawan			= $(this).data("id_karyawan");
		$.ajax({
    		url: baseURL+'klems/laporan/get_data/detail',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_batch : id_program_batch,
				id_karyawan : id_karyawan
			},
			success: function(data){
				var nil	= "<table class='table table-bordered'>";
				nil	 	+= "<thead>";
				nil	 	+= 		"<tr>";
				nil	 	+= 			"<th>No</th><th>Tahap</th><th>Nilai</th><th>Grade</th>";
				nil	 	+= 		"</tr>";
				nil	 	+= "</thead>";
				nil	 	+= "<tbody>";
				var n 	= 0;
				$.each(data, function(i, v){
					n++;
					nil	 	+= 		"<tr>";
					nil	 	+= 			"<td>"+n+"</td><td>"+v.nama+"</td><td>"+v.average+"</td><td>"+v.grade+"</td>";
					nil	 	+= 		"</tr>";
				});
				nil	 	+= "</tbody>";
				nil	 	+= "</table>";
				$("#container-nilai").html(nil);
				$('#show_detail').modal('show');
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
		// startDate: new Date(),
		autoclose: true
		
	});
	
});