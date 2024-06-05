$(document).ready(function(){
    //=======FILTER=======//
	$(document).on("change", "#program, #awal, #akhir", function(){
		var program	= $("#program").val();
		var awal 	= $("#awal").val();
		var akhir 	= $("#akhir").val();
		$.ajax({
			url: baseURL+'klems/laporan/evaluasi_filter',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	program : program,
	        	awal 	: awal,
	        	akhir	: akhir
	        },
	        success: function(data){
				var output 	= "";
	        	var desc	= "";
				
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					console.log
					//list topik
					if(v.topik_list!=null){
						var topik_list 	= "";	
						var topik 		= v.topik_list.length > 0 ? v.topik_list.slice(0, -1) : "";
						var arr_topik 	= topik.split(",");
						$.each(arr_topik,function(x,val) {
							topik_list += "<button class='btn btn-sm btn-info btn-role'>"+val+"</button>";	
						});					
					}else{
						topik_list += "-";	
					}
					//list trainer
					if(v.list_trainer!=null){
						var trainer_list 	= "";	
						var trainer 		= v.list_trainer.length > 0 ? v.list_trainer.slice(0, -1) : "";
						var arr_trainer 	= trainer.split(",");
						$.each(arr_trainer,function(x,val) {
							trainer_list += "<li style='list-style-type:none'><i class='fa fa-user'></i> "+val+"</li>";	
						});					
					}else{
						trainer_list = "-";	
					}
					//option action
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(v.list_trainer2!=null){
						var list_trainer2	= v.list_trainer2.length > 0 ? v.list_trainer2.slice(0, -1) : "";
						var arr_trainer2 	= list_trainer2.split(",");
						$.each(arr_trainer2,function(x,val) {
							myarr = val.split("|");
							output += "					<li><a href='"+baseURL+'klems/laporan/data/sesi/'+v.id_batch+'/'+myarr[2]+'/'+myarr[1]+"'><i class='fa fa-file-text'></i> Feedback Trainer Evaluasi Sesi ("+myarr[0]+")</a></li>";
						});					
					}
					output += "					<li><a href='"+baseURL+'klems/laporan/data/prog/'+v.id_batch+"'><i class='fa fa-file-text'></i> Feedback Evaluasi Program</a></li>";
					output += "				</ul>";
					output += "	        </div>";
					console.log(v);
					//generate rows
	        		t.row.add( [
			            v.kode_program_batch,
			            v.nama_program_batch,
						v.nama_program,
						v.tanggal_awal+' sd '+v.tanggal_akhir,
			            v.nama_tahap,
						trainer_list,
						v.jumlah_evaluasi,
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
                    columns: [0,1,2,3,4,5]
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