$(document).ready(function () {
	add_row();
    $(document).on("keyup", ".cek_min_max", function(e){
        var id_scoring_tipe = $("#id_scoring_tipe").val();
        var nilai = $(this).val();
		if(nilai<6 && id_scoring_tipe !== "3"){
			kiranaAlert("NotOK",'Nilai Minimal 6',"warning", "no");
			$(this).val(6);
		}
		if(nilai>24){
            kiranaAlert("NotOK",'Nilai Miksimal 24',"warning", "no");
			$(this).val(24);
		}
    });
	
	//set on click
    $(document).on("change", "#id_scoring_tipe", function(e){
    	var id_scoring_tipe = $("#id_scoring_tipe").val();
		if((id_scoring_tipe=='1') || (id_scoring_tipe=='2')){
			$("#show_kelas").show();
            $("#show_kelas input").attr("required", "required");
            $("#show_kelas select").attr("required", "required");
		}else{
   			$("#show_kelas").hide();
            $("#show_kelas input").removeAttr("required");
            $("#show_kelas select").removeAttr("required");
   			$("#kelas").val("");
		}
    });
	
	$(document).on("click", ".add-row", function () {
		add_row();
	});

	$(document).on("click", ".delete-row", function () {
		var count = $("#input-score-wrapper tr").length;
		if (count > 1) $("#input-score-wrapper tr:eq(" + (count - 1) + ")").remove();
	});
	
	function add_row(myelement) {
		// $.ajax({
			// url: baseURL + "umb/master/get/plant",
			// type: 'POST',
			// dataType: 'JSON',
			// success: function (data) {
				// if (data.length > 0) {
					// var output = "<tr>";
					// output += '	<td width="25%">';
					// output += '		<input type="text" class="form-control text-right angka" name="score_awal[]" required="required"/>';
					// output += '	</td>';
					// output += '	<td width="25%">';
					// output += '		<input type="text" class="form-control text-right angka" name="score_akhir[]" required="required"/>';
					// output += '	</td>';
					// output += '	<td>';
					// output += '		<input type="text" class="form-control text-right angka" name="uang_muka[]" required="required"/>';
					// output += '	</td>';
					// output += '</tr>';
					// $("#input-score-wrapper").append(output);
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
		output += '	<td width="25%">';
		output += '		<input type="text" class="form-control text-right angka" name="score_awal[]" min="0" required="required"/>';
		output += '	</td>';
		output += '	<td width="25%">';
		output += '		<input type="text" class="form-control text-right angka" name="score_akhir[]" required="required"/>';
		output += '	</td>';
		output += '	<td>';
		output += '		<input type="text" class="form-control text-right angka" name="uang_muka[]" required="required"/>';
		output += '	</td>';
		output += '</tr>';
		$("#input-score-wrapper").append(output);
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
				var formData = new FormData($(".form-master-scoring")[0]);

				$.ajax({
					url: baseURL + 'umb/master/save/scoring',
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
		$(".form-master-scoring input, .form-master-scoring select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "umb/master/get/scoring",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mscoring_header: $(this).data("edit")
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						console.log(data);
						$("input[name='id_mscoring_header']").val(v.id_mscoring_header);
						$("select[name='id_scoring_tipe']").val(v.id_scoring_tipe == null ? 0 : v.id_scoring_tipe).trigger("change");
						$("select[name='kelas']").val(v.kelas == null ? 0 : v.kelas).trigger("change");
						$("input[name='std_minimal']").val(v.std_minimal);
						$("input[name='min_bln_supply']").val(v.min_bln_supply);
						$("input[name='batas_bawah']").val(v.batas_bawah);
						$("input[name='batas_atas']").val(v.batas_atas);
						
						//detail
						var output = "";
						if(v.list_detail!=null){
							var list_detail		= v.list_detail.slice(0, -1).split(",");
							$.each(list_detail, function(x, y){
								var a   = list_detail[x].split("|");
								output += '<tr>';
								output += '	<td width="25%">';
								output += '		<input type="text" class="form-control text-right angka" name="score_awal[]" value="'+a[1]+'" required="required"/>';
								output += '	</td>';
								output += '	<td width="25%">';
								output += '		<input type="text" class="form-control text-right angka" name="score_akhir[]" value="'+a[2]+'" required="required"/>';
								output += '	</td>';
								output += '	<td>';
								output += '		<input type="text" class="form-control text-right angka" name="uang_muka[]" value="'+numberWithCommas(a[3])+'" required="required"/>';
								output += '	</td>';
								output += '</tr>';
							});
							$("#input-score-wrapper").html(output);
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
		var id_mscoring_header	= $(this).data("detail");
		$.ajax({
    		url: baseURL+'umb/master/get/scoring',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mscoring_header : id_mscoring_header
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						console.log(data);
						$("input[name='tipe_scoring']").val(v.tipe_scoring);
						$("input[name='kelas']").val(v.kelas);
						$("input[name='std_minimal']").val(v.std_minimal);
						$("input[name='min_bln_supply']").val(v.min_bln_supply);
						$("input[name='batas_bawah']").val(v.batas_bawah);
						$("input[name='batas_atas']").val(v.batas_atas);
						
						//detail
						var output = "";
						output += '<table class="table table-bordered table-striped">';
						output += '<thead><tr><th>Score<br>Awal</th><th>Score<br>Akhir</th><th>Nilai UM<br>yang diberikan</th></tr></thead><tbody>';
						if(v.list_detail!=null){
							var list_detail		= v.list_detail.slice(0, -1).split(",");
							$.each(list_detail, function(x, y){
								var a   = list_detail[x].split("|");
								output += '<tr>';
								output += '	<td width="25%">';
								output += '		<input type="text" class="form-control text-right angka" name="score_awal[]" value="'+a[1]+'" required="required" disabled/>';
								output += '	</td>';
								output += '	<td width="25%">';
								output += '		<input type="text" class="form-control text-right angka" name="score_akhir[]" value="'+a[2]+'" required="required"  disabled/>';
								output += '	</td>';
								output += '	<td>';
								output += '		<input type="text" class="form-control text-right angka" name="uang_muka[]" value="'+a[3]+'" required="required"  disabled/>';
								output += '	</td>';
								output += '</tr>';
							});
						output += '</tbody></table>';
							
						}
						$("#show_detail_scoring").html(output);
					});
					$('#detail_master_scoring').modal('show');
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
			url: baseURL + "umb/master/set/scoring",
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
});
