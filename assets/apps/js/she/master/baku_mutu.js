$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-jenis").on("click", function(e){
    	var id_mjenis	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'she/master/set_data/activate/jenis',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mjenis : id_mjenis
			},
			success: function(data){
				if(data.sts == 'OK'){
                    kiranaAlert(data.sts, data.msg);
				}else{
                    kiranaAlert(data.sts, data.msg, "error", "no");
				}
			}
		});
    });

	$(".delete").on("click", function(e){
    	var id	= $(this).data("delete");

        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/master/set_data/delete_del0/bakumutu',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id : id
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );

    });

	$(".edit").on("click", function(e){
    	var id	= $(this).data("edit");

    	$.ajax({
    		url: baseURL+'she/master/get_data/bakumutu',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				// console.log(data);
				$(".modal-title").html("<i class='fa fa-pencil-square-o'></i> Edit Data Baku Mutu");
				$.each(data, function(i, v){
					$("#tgl_awal").val(v.tgl_mulai);
					$("#tgl_akhir").val(v.tgl_akhir);
					document.getElementById("kategori").value = v.fk_kategori;
					document.getElementById("jenis").value = v.fk_jenis;
					document.getElementById("parameter").value = v.fk_parameter;
					$('.select2').select2()

					$("#limit_uji").val(v.bakumutu_hasilujilimit);
					$("#min_uji").val(v.bakumutu_hasilujimin);
					$("#max_uji").val(v.bakumutu_hasilujimax);
					$("#limit_cemar").val(v.bakumutu_bebancemarlimit);
					$("#min_cemar").val(v.bakumutu_bebancemarmin);
					$("#max_cemar").val(v.bakumutu_bebancemarmax);
					$("#regulasi").val(v.regulasi);
					$("#id").val(v.id);
					// $("input[name='id']").val(v.id);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });
	
	$(".history").on("click", function(e){
    	var id	= $(this).data("edit");
    	$.ajax({
    		url: baseURL+'she/master/get_data/history',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				$(".modal-title").html("History Master Baku Mutu");
				$.each(data, function(i,v){
					console.log(data);
					$("#kategori_val").val(v.kategori);
					$("#jenis_val").val(v.jenis);
					$("#parameter_val").val(v.parameter);
					//history
					var nil	= "<table class='table table-bordered'>";
						nil	 	+= "<thead>";
						nil	 	+= 		"<tr>";
						nil	 	+= 			"<th rowspan='2'>Tanggal Mulai</th><th rowspan='2'>Tanggal Berakhir</th><th colspan='3' align='center'>Kriteria Baku Mutu Hasil Uji</th><th colspan='3' align='center'>Kriteria Baku Mutu Beban Cemar</th><th rowspan='2'>Edit Oleh</th><th rowspan='2'>Tanggal Edit</th>";
						nil	 	+= 		"</tr>";
						nil	 	+= 		"<tr>";
						nil	 	+= 			"<th>Limit</th><th>Min</th><th>Max</th><th>Limit</th><th>Min</th><th>Max</th>";
						nil	 	+= 		"</tr>";
						nil	 	+= "</thead>";
						nil	 	+= "<tbody>";
					$.each(v.arr_history, function (x, y) {
						nil	 	+= 		"<tr>";
						nil	 	+= 			"<td>"+y.tgl_mulai+"</td><td>"+y.tgl_akhir+"</td><td align='center'>"+y.bakumutu_hasilujilimit+"</td><td align='center'>"+y.bakumutu_hasilujimin+"</td><td align='center'>"+y.bakumutu_hasilujimax+"</td><td align='center'>"+y.bakumutu_bebancemarlimit+"</td><td align='center'>"+y.bakumutu_bebancemarmin+"</td><td align='center'>"+y.bakumutu_bebancemarmax+"</td><td>"+y.namabuat+"</td><td>"+y.tanggalbuat+"</td>";
						nil	 	+= 		"</tr>";
					});
						nil	 	+= "</tbody>";
					$("#show_history").html(nil);
				});
			},
			complete: function () {
				$('#status_modal').modal('show');
			}
			// success: function(data){
				// // console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil-square-o'></i> Edit Data Baku Mutu");
				// $.each(data, function(i, v){
					// $("#tgl_awal").val(v.tgl_mulai);
					// $("#tgl_akhir").val(v.tgl_akhir);
					// document.getElementById("kategori").value = v.fk_kategori;
					// document.getElementById("jenis").value = v.fk_jenis;
					// document.getElementById("parameter").value = v.fk_parameter;
					// $('.select2').select2()

					// $("#limit_uji").val(v.bakumutu_hasilujilimit);
					// $("#min_uji").val(v.bakumutu_hasilujimin);
					// $("#max_uji").val(v.bakumutu_hasilujimax);
					// $("#limit_cemar").val(v.bakumutu_bebancemarlimit);
					// $("#min_cemar").val(v.bakumutu_bebancemarmin);
					// $("#max_cemar").val(v.bakumutu_bebancemarmax);
					// $("#id").val(v.id);
					// // $("input[name='id']").val(v.id);
					// $("#btn-new").removeClass("hidden");
				// });
			// }
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-bakumutu")[0]);

				$.ajax({
					url: baseURL+'she/master/save/bakumutu',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						if(data.sts == 'OK'){
		                    kiranaAlert(data.sts, data.msg);
						}else{
		                    kiranaAlert(data.sts, data.msg, "error", "no");
						}
					}
				});
				$(".modal-title").html("<i class='fa fa-plus'></i> Tambah Data Baku Mutu");
			}else{
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
    });

    //date pitcker
    $('.datePicker').datepicker({
        format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
    });

});

function init(){
	document.getElementById("kategori").value = "";
	document.getElementById("jenis").value = "";
	document.getElementById("parameter").value = "";
	$(".select2").select2();
	$(".init").val("");
}
