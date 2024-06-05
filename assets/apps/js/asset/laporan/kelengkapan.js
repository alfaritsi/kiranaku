$(document).ready(function(){
	//detail
	$(".detail").on("click", function(e){
		var id_aset		= $(this).data("detail");
		var id_jenis	= $(this).data("id_jenis");
		$.ajax({
    		url: baseURL+'asset/laporan/get/detail',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset : id_aset,
				id_jenis : id_jenis
			},
			success: function(data){
				// console.log(data);
	        	var t 		= $('.my-datatable-extends-order-detail').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					if(v.tanggal_berlaku == null){
						nama			= "<font color='red'>"+v.nama+"</font>";
						tanggal_berlaku	= "<font color='red'>-</font>";
						tanggal_berakhir= "<font color='red'>-</font>";
						sisa_hari		= "<font color='red'>-</font>";
					}else{
						nama			= v.nama;
						tanggal_berlaku	= v.tanggal_berlaku;
						tanggal_berakhir= v.tanggal_berakhir;
						if(v.sisa_hari<0){
							sisa_hari	="<font color='red'>("+v.sisa_hari*-1+")</font>";
						}else{
							sisa_hari	= v.sisa_hari;	
						}
						
					}
	        		t.row.add( [
			            nama,
			            tanggal_berlaku,
			            tanggal_berakhir,
						sisa_hari
			        ] ).draw( false );
	        	});
			},
			complete: function () {
				$('#add_modal').modal('show');
			}
			
		});
    });
	
    //=======FILTER=======//
	$(document).on("change", "#pabrik", function(){
		var pabrik 	= $("#pabrik").val();
		$.ajax({
			url: baseURL+'asset/laporan/get/kelengkapan/hrga',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	pabrik 	: pabrik
	        },
	        success: function(data){
				// console.log(data);
	        	var t 	= $('.my-datatable-extends-order').DataTable();
				var selisih_doc = "";
				var output		= "";
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					selisih_doc = v.total_dokumen-v.jumlah_dokumen+" of "+v.total_dokumen;
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					output += "					<li><a href='javascript:void(0)' class='detail' data-detail='"+v.id_aset+"' data-id_jenis='"+v.id_jenis+"'><i class='fa fa-search'></i> Detail</a></li>";
					output += "				</ul>";
					output += "	        </div>";
					
	        		t.row.add( [
			            v.nama_pabrik,
						v.nomor_polisi,
						v.nama_jenis,
						v.nama_merk,
						v.nama_merk_tipe,
						selisih_doc,
						v.jumlah_expired,
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
                title: 'Maintenance Email',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10]
                }
            }
        ],
		scrollX:true
    } );
});