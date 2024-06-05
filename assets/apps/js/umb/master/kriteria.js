$(document).ready(function () {
	add_row();
	//set on click
  //   $(document).on("change", "#id_scoring_tipe", function(e){
  //   	var id_scoring_tipe = $("#id_scoring_tipe").val();
		// if((id_scoring_tipe=='1') || (id_scoring_tipe=='2')){
		// 	$("#show_kelas").show();
		// }else{
  //  			$("#show_kelas").hide();
  //  			$("#kelas").val("");
		// }
  //   });
	
	$(document).on("click", ".add-row", function () {
		add_row();
	});

	$(document).on("click", ".delete-row", function () {
		var count = $("#input-kriteria-wrapper tr").length;
		if (count > 1) $("#input-kriteria-wrapper tr:eq(" + (count - 1) + ")").remove();
	});
	
	function add_row(myelement) {
		// $.ajax({
			// url: baseURL + "umb/master/get/plant",
			// type: 'POST',
			// dataType: 'JSON',
			// success: function (data) {
				// if (data.length > 0) {
					// var output = "<tr>";
					// output += '	<td width="35%">';
					// output += '		<input type="text" class="form-control text-right angka" name="param_awal[]" required="required"/>';
					// output += '	</td>';
					// output += '	<td width="35%">';
					// output += '		<input type="text" class="form-control text-right angka" name="param_akhir[]" required="required"/>';
					// output += '	</td>';
					// output += '	<td>';
					// output += '		<input type="text" class="form-control text-right angka" name="nilai[]" required="required"/>';
					// output += '	</td>';
					// output += '</tr>';
					// $("#input-kriteria-wrapper").append(output);
				// }
			// },
			// complete: function () {
				// $(".select2").select2();
				// if (myelement) {
					// $.each(myelement, function (i, v) {
						// $(v.element).val(v.value).trigger("change");
					// });
				// }
			// }
		// });
		var output = "<tr>";
		output += '	<td width="35%">';
		output += '		<input type="text" class="form-control text-right angka" name="param_awal[]" required="required"/>';
		output += '	</td>';
		output += '	<td width="35%">';
		output += '		<input type="text" class="form-control text-right angka" name="param_akhir[]" required="required"/>';
		output += '	</td>';
		output += '	<td>';
		output += '		<input type="text" class="form-control text-right angka" name="nilai[]" required="required"/>';
		output += '	</td>';
		output += '</tr>';
		$("#input-kriteria-wrapper").append(output);
		$(".select2").select2();
		if (myelement) {
			$.each(myelement, function (i, v) {
				$(v.element).val(v.value).trigger("change");
			});
		}
	}
	
	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-master-kriteria")[0]);

				$.ajax({
					url: baseURL + 'umb/master/save/kriteria',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						console.log(data);
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					complete: function () {
						$("input[name='isproses']").val(0);
					}
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
	});

	$(document).on("click", ".edit", function (e) {
		$(".form-master-kriteria input, .form-master-kriteria select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "umb/master/get/kriteria",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mkriteria_header: $(this).data("edit")
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						console.log(data);
						$("input[name='id_mkriteria_header']").val(v.id_mkriteria_header);
						$("select[name='kelas']").val(v.kelas).trigger("change");
						$("select[name='id_mjenis_kriteria']").val(v.id_mjenis_kriteria == null ? 0 : v.id_mjenis_kriteria).trigger("change");
						$("input[name='persen_bobot']").val(v.persen_bobot);
						$("select[name='satuan']").val(v.satuan == null ? 0 : v.satuan).trigger("change");
						
						//detail
						var output = "";
						if(v.list_detail!=null){
							var list_detail		= v.list_detail.slice(0, -1).split(",");
							$.each(list_detail, function(x, y){
								var a   = list_detail[x].split("|");
								output += '<tr>';
								output += '	<td width="35%">';
								output += '		<input type="text" class="form-control text-right angka" name="param_awal[]" value="'+a[1]+'" required="required"/>';
								output += '	</td>';
								output += '	<td width="35%">';
								output += '		<input type="text" class="form-control text-right angka" name="param_akhir[]" value="'+a[2]+'" required="required"/>';
								output += '	</td>';
								output += '	<td>';
								output += '		<input type="text" class="form-control text-right angka" name="nilai[]" value="'+a[3]+'" required="required"/>';
								output += '	</td>';
								output += '</tr>';
							});
							$("#input-kriteria-wrapper").html(output);
						}
						
					});

					$("#btn-new").show();
				}
			}
		});
		e.preventDefault();
		return false;
	});
	//detail
	$(".detail").on("click", function(e){
		var id_mkriteria_header	= $(this).data("detail");
		$.ajax({
    		url: baseURL+'umb/master/get/kriteria',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mkriteria_header : id_mkriteria_header
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						console.log(data);
						$("input[name='alias']").val(v.alias);
						$("input[name='dkelas']").val(v.kelas);
						$("input[name='persen_bobot']").val(v.persen_bobot);
						$("input[name='satuan']").val(v.satuan);
						
						//detail
						var output = "";
						output += '<table class="table table-bordered table-striped">';
						output += '<thead><tr><th>Parameter<br>Awal</th><th>Parameter<br>Akhir</th><th>Nilai</th></tr></thead><tbody>';
						if(v.list_detail!=null){
							var list_detail		= v.list_detail.slice(0, -1).split(",");
							$.each(list_detail, function(x, y){
								var a   = list_detail[x].split("|");
								output += '<tr>';
								output += '	<td width="35%">';
								output += '		<input type="text" class="form-control text-right angka" name="param_awal[]" value="'+a[1]+'" required="required" disabled/>';
								output += '	</td>';
								output += '	<td width="35%">';
								output += '		<input type="text" class="form-control text-right angka" name="param_akhir[]" value="'+a[2]+'" required="required"  disabled/>';
								output += '	</td>';
								output += '	<td>';
								output += '		<input type="text" class="form-control text-right angka" name="nilai[]" value="'+a[3]+'" required="required"  disabled/>';
								output += '	</td>';
								output += '</tr>';
							});
						output += '</tbody></table>';
							
						}
						$("#show_detail_kriteria").html(output);
					});
					$('#detail_master_kriteria').modal('show');
				}
			}
			
		});
    });
	
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "umb/master/set/kriteria",
			type: 'POST',
			dataType: 'JSON',
			data: {
				kode : $(this).data($(this).attr("class")),
				type : $(this).attr("class")
			},
			success: function(data){
				if(data.sts == 'OK'){
					kiranaAlert(data.sts, data.msg);
				}else{
					kiranaAlert("notOK", data.msg, "warning", "no");
				}
			}
		});
		e.preventDefault();
		return false;
	});


	// $("#persen_bobot").on("change", function(e){
	// 	var id_mkriteria_header = $("input[name='id_mkriteria_header']").val();
 //    	var persen_bobot		= $(this).val();
 //    	$.ajax({
 //    		url: baseURL+'umb/master/get/cek_kriteria',
	// 		type: 'POST',
	// 		dataType: 'JSON',
	// 		data: {
	// 			id_mkriteria_header : id_mkriteria_header,
	// 			persen_bobot 		: persen_bobot
	// 		},
	// 		success: function(data){
	// 			console.log(data);
	// 			$.each(data, function(i, v){
	// 				sisa_persen_bobot = 100-v.total_persen_bobot;
	// 				if(persen_bobot>sisa_persen_bobot){
	// 					alert('Maksimal Persentase Bobot '+sisa_persen_bobot);
	// 					$("input[name='persen_bobot']").val(100-v.total_persen_bobot);
	// 				}
	// 				if(persen_bobot<0){
	// 					alert('Minimal Persentase Bobot 0');
	// 					$("input[name='persen_bobot']").val(0);
	// 				}
					
	// 			});
	// 		}
	// 	});
 //    });
	
});
