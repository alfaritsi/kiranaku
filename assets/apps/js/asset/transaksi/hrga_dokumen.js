$(document).ready(function(){
	
	//set on change id_inv_doc
    $(document).on("change", "#id_inv_doc", function(e){
		var id_inv_doc	= $(this).val();
		var id_aset		= $("#id_aset").val();
		$.ajax({
    		url: baseURL+'asset/transaksi/get/dokumen',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset		: id_aset,
				id_inv_doc	: id_inv_doc
			},
			success: function(data){
				$('#id_inv_doc_transaksi').val("");
				$('#hidden_gambar').val("");
				$('#nomor_dokumen').val("");
				$('#tanggal_berlaku').val("");
				$('#keterangan').val("");
				$.each(data, function(i,v){
					$("#id_inv_doc_transaksi").val(v.id_inv_doc_transaksi);
					$("#hidden_gambar").val(v.gambar);
					$("input[name='nomor_dokumen']").val(v.nomor_dokumen);
					$("input[name='tanggal_berlaku']").val(v.tanggal_berlaku);
					$("textarea[name='keterangan']").val(v.keterangan);
					$(".gambar").attr('src', v.gambar);
				});
			}
		});
    });
	
	//edit	
	$(".edit").on("click", function(e){
		var id_inv_doc_transaksi	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'asset/transaksi/get/dokumen',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_inv_doc_transaksi : id_inv_doc_transaksi
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Program Batch");
				$.each(data, function(i,v){
					$("#id_inv_doc_transaksi").val(v.id_inv_doc_transaksi);
					$("#hidden_gambar").val(v.gambar);
					$("select[name='id_inv_doc']").val(v.id_inv_doc).trigger("change");
					$("input[name='nomor_dokumen']").val(v.nomor_dokumen);
					$("input[name='tanggal_berlaku']").val(v.tanggal_berlaku);
					$("input[name='plat']").val(v.plat);
					$("input[name='no_pol']").val(v.no_pol);
					$("input[name='bel_nomor_polisi']").val(v.bel_nomor_polisi);
					$("textarea[name='keterangan']").val(v.keterangan);
					$(".gambar").attr('src', v.gambar);
				});
				
			},
			complete: function () {
				$('#add_modal').modal('show');
			}
			
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate('.form-dokumen-hrga');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-dokumen-hrga")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'asset/transaksi/save/dokumen',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function () {
								location.reload();
							});
						} else {
							$("input[name='isproses']").val(0);
							swal('Error', data.msg, 'error');
						}
					}
				});
			}else{
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
			}
		}
		e.preventDefault();
		return false;
    });
	
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Dokumen Aset HRGA',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            }
        ],
		scrollX:true
    } );
	
    //open modal for add     
	$(document).on("click", "#add_button", function(e){
		$('#add_modal').modal('show');
	});
	//date pitcker
	$('.tanggal').datepicker({
		format: 'yyyy-mm-dd',
		// startDate: new Date(),
		autoclose: true
		
	});
	
	
});