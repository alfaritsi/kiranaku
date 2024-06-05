$(document).ready(function(){
	//link upload
	$(".upload").on("click", function(e){
		var id_batch	= $(this).data("batch");
		var id_trainer	= $(this).data("trainer");
		var jenis		= $(this).data("jenis");
		$("input[name='id_batch']").val(id_batch);
		$("input[name='id_trainer']").val(id_trainer);
		$("input[name='jenis']").val(jenis);
		$('#add_modal').modal('show');
    });
	
	//link save
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-upload_feedback")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/transaksi/save/upload',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						if(data.sts == 'OK'){
							alert(data.msg);
							location.reload();
						}else{
							alert(data.msg);
							$("input[name='isproses']").val(0);
						}
					}
				});
			}else{
				alert("Silahkan tunggu proses selesai.");
			}
		}
		e.preventDefault();
		return false;
    });
	
    //=======FILTER=======//
	$(document).on("change", "#program, #awal, #akhir", function(){
		var program	= $("#program").val();
		var awal 	= $("#awal").val();
		var akhir 	= $("#akhir").val();
		$.ajax({
			url: baseURL+'klems/transaksi/upload_filter',
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
					output += "					<li><a href='#' class='upload' data-batch='"+v.id_batch+"' data-trainer='0' data-jenis='program'><i class='fa fa-edit'></i> Upload Feedback  Program</a></li>";
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