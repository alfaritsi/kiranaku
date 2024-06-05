$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-evindex").on("click", function(e){
		var id_feedback_index	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/evindex',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_index : id_feedback_index
			},
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
    });

	$(".delete").on("click", function(e){
    	var id_feedback_index = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/evindex',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_index : id_feedback_index
			},
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
    });

	$(".edit").on("click", function(e){
    	var id_feedback_index	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/evindex',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_feedback_index : id_feedback_index
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Edit Master Trainner");
				$.each(data, function(i, v){
					$("#evindex").val(v.evindex);
					$("input[name='id_feedback_index']").val(v.id_feedback_index);
					$("input[name='tanggal_awal']").val(v.tanggal_awal);
					$("input[name='tanggal_akhir']").val(v.tanggal_akhir);
					$("input[name='kode']").val(v.kode);
					$("input[name='nama']").val(v.nama);
					$("input[name='nilai']").val(v.nilai);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-evindex")[0]);
				console.log();
				$.ajax({
					url: baseURL+'klems/master/save/evindex',
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
                title: 'Master Skala Penilaian',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4]
                }
            }
        ]
    } );
	// //date pitcker
	// $('.tanggal').datepicker({
		// format: 'yyyy-mm-dd',
		// // startDate: new Date(),
		// autoclose: true
		
	// });
	//set tanggal batch
	$('#tanggal_awal').datepicker({
        format: 'yyyy-mm-dd',
	    autoclose: true
    });
	
    $(document).on("change", "#tanggal_awal", function(e){
		// var tanggal_awal_program_batch = $("#tanggal_awal_program_batch").val();
        $('#tanggal_akhir').val("");
        var akhir = $(this).val();
        $("#div_tanggal_akhir").html("");
        $("#div_tanggal_akhir").html('<input type="text" class="form-control" name="tanggal_akhir" id="tanggal_akhir" placeholder="Masukkkan Tanggal Akhir"  required="required" autocomplete="off">');

        $('#tanggal_akhir').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: akhir
        });
    });
	
	
});