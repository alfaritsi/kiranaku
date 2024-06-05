$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-soal").on("click", function(e){
		var id_soal	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/soal',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_soal : id_soal
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
    	var id_soal = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/soal',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_soal : id_soal
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
    	var id_soal	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/soal',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_soal : id_soal
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Soal");
				$.each(data, function(i, v){
					$("#soal").val(v.soal);
					$("select[name='id_bpo']").val(v.id_bpo).trigger('change');
					$("select[name='id_topik']").val(v.id_topik).trigger('change');
					$("select[name='id_soal_tipe']").val(v.id_soal_tipe).trigger('change');
					$("input[name='id_soal']").val(v.id_soal);
					$("input[name='kode']").val(v.kode);
					$("input[name='soal']").val(v.soal);
					//array jawaban
					var arr_jawaban = v.jawaban;
					var jawaban = arr_jawaban.split(";");
					$("input[name='jawaban1']").val(jawaban[0]);
					$("input[name='jawaban2']").val(jawaban[1]);
					$("input[name='jawaban3']").val(jawaban[2]);
					$("input[name='jawaban4']").val(jawaban[3]);
					//array nama
					var arr_nama_jawaban = v.nama_jawaban;
					var nama_jawaban = arr_nama_jawaban.split(";");
					$("input[name='nama_jawaban1']").val(nama_jawaban[0]);
					$("input[name='nama_jawaban2']").val(nama_jawaban[1]);
					$("input[name='nama_jawaban3']").val(nama_jawaban[2]);
					$("input[name='nama_jawaban4']").val(nama_jawaban[3]);
					$("input[name='gambar']").val(v.gambar);
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
		    	var formData = new FormData($(".form-master-soal")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/soal',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						console.log(data);
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
                title: 'Setting Soal',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,3,4]
                }
            }
        ],
		scrollX:true
    } );

});