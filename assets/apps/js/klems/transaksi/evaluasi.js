$(document).ready(function(){
	//materi
	$(".materi").on("click", function(e){
		var id_materi	= $(this).data("id_materi");
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
						$("#show_materi").html('<video width="100%" id="player-video" controls controlsList="nodownload"><source src="http://localhost:8080/105/kiranaku/'+v.files+'" type="video/mp4"></video>');
					}else{
						$("#show_materi").html('<iframe src="http://10.0.9.37:8080/105/kiranaku/'+v.files+'" style="width:100%;height:400px;" frameborder="0"></iframe>');
					}
					$('#materi_modal').modal('show');
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
                title: 'Setting Program Matrix',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            }
        ],
		scrollX:true
    } );
});