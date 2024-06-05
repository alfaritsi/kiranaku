$(document).ready(function(){
	//persen grade
	$(".persen_grade").on("click", function(e){
		$('#add_persen_grade_modal').modal('show');
    });
	
    //=======FILTER=======//
	$(document).on("change", "#program, #awal, #akhir", function(){
		var program	= $("#program").val();
		var awal 	= $("#awal").val();
		var akhir 	= $("#akhir").val();
		$.ajax({
			url: baseURL+'klems/transaksi/nilai_filter',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	program : program,
	        	awal 	: awal,
	        	akhir	: akhir
	        },
	        success: function(data){
				console.log(data);
	        	var output 	= "";
	        	var desc	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					//option action
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					output += "					<li><a href='"+baseURL+'klems/transaksi/data/nilai_batch/'+v.id_batch+"'><i class='fa fa-edit'></i> Input Nilai</a></li>";
					output += "				</ul>";
					output += "	        </div>";
					console.log(v);
					//generate rows
	        		t.row.add( [
			            v.nama_program,
			            v.kode_program_batch+'<br>'+v.nama_program_batch+'<br>'+v.tanggal_awal_program_batch+' sd '+v.tanggal_akhir_program_batch,
			            v.nama_tahap+'<br>'+v.tanggal_awal_batch+' sd '+v.tanggal_akhir_batch,
			            v.tanggal_test+'<br>'+v.jam_awal+' - '+v.jam_akhir,
			            v.tempat,
						v.label_online,
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