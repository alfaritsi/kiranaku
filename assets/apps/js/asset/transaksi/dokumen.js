$(document).ready(function(){
    //=======FILTER=======//
	$(document).on("change", "#pabrik, #dokumen", function(){
		var pabrik 	= $("#pabrik").val();
		var dokumen = $("#dokumen").val();
		$.ajax({
			url: baseURL+'asset/laporan/get/dokumen',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	pabrik 	: pabrik,
	        	dokumen	: dokumen
	        },
	        success: function(data){
				// console.log('bbb');
				console.log(data);
				var output 	= "";
	        	var desc	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					if(v.selisih_hari > 0){ 
						selisih_hari = v.selisih_hari;
					}else{
						selisih_hari = "<font color='red'>("+v.selisih_hari*-1 +")</font>";
					}
	        		t.row.add( [
			            v.nomor_dokumen,
			            v.nama_pabrik,
						v.nomor_sap,
						v.nomor_polisi,
						v.nama_jenis,
						v.nama_merk,
						v.nama_dokumen,
						v.tanggal_berlaku,
						v.periode,
						v.tanggal_berakhir,
						selisih_hari
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