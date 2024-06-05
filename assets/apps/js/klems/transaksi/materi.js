$(document).ready(function(){
	$(document).on("contextmenu",function(e){
		alert();
	});
	
	
	//materi, 
	$(".materi").on("click", function(e){
		var id_materi	= $(this).data("id_materi");
		var base_url	= $(this).data("base_url");
		// alert(base_url);
		$.ajax({
    		url: baseURL+'klems/transaksi/get_data/materi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_materi : id_materi
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Materi Training");
				$.each(data, function(i, v){
					$("#materi").val(v.materi);
					if(v.tipe_files==='mp4'){
						$("#show_materi").html('<video width="100%" id="player-video" controls controlsList="nodownload"><source src="'+base_url+"/"+v.files+'" type="video/mp4"></video>');
					}else{
						$("#show_materi").html(showPdf(v.files));
						// $("#show_materi").html('<iframe id="fraDisabled" src="'+base_url+"/"+v.files+'#toolbar=0" style="width:100%;height:400px;" frameborder="0" oncontextmenu="return false"></iframe>');
						// $("#show_materi").html('<iframe src="http://10.0.9.37:8080/kiranaku/'+v.files+'" style="width:100%;height:400px;" frameborder="0"></iframe>');
					}
					$('#materi_modal').modal('show');
					$(document).on("contextmenu", '#materi_modal', function(e){
						e.preventDefault();
					},false);
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
                title: 'Materi Training',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            },
			'colvis'
        ],
		scrollX:true,
		columnDefs: [
			{ "targets": 2, "visible": false },
			{ "targets": 3, "visible": false },
			{ "targets": 4, "visible": false },
			{ "targets": 6, "visible": false },
			{ "targets": 7, "visible": false },
			{ "targets": 8, "visible": false }
		]
		
    } );
});