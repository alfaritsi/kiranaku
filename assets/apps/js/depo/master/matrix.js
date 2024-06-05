$(document).ready(function () {
	//change matrix
    // $(document).on("change", "#id_matrix", function () {
		// let id_matrix_header = $("#id_matrix_header").val();
		// let id_matrix = $("#id_matrix").val();
		// if(id_matrix_header == 0){
			// add_row(id_matrix);
		// }
    // });
	
	$(document).on("click", ".add-row", function () {
		let id_matrix = $("#id_matrix").val();
		add_row(id_matrix);
	});

	$(document).on("click", ".delete-row", function () {
		var count = $("#input-score-wrapper tr").length;
		if (count > 1) $("#input-score-wrapper tr:eq(" + (count - 1) + ")").remove();
	});
	
	function add_row(id_matrix) {
		var output = "";
		output += "<tr>";
		output += '	<td>';
		output += '		<input type="text" class="form-control form-control_mitra" name="param_text[]"/>';
		output += '	</td>';
		output += '	<td width="20%">';
		output += '		<input type="text" class="form-control form-control_potensi text-center angka" name="param_awal[]"/>';
		output += '	</td>';
		output += '	<td width="20%">';
		output += '		<input type="text" class="form-control form-control_potensi text-center angka" name="param_akhir[]" />';
		output += '	</td>';
		output += '	<td width="20%">';
		output += '		<input type="text" class="form-control text-center angka" name="nilai[]" required />';
		output += '	</td>';
		output += '</tr>';
		$("#input-score-wrapper").append(output);
		if((id_matrix==6)||(id_matrix==7)){
			$('.form-control_mitra').prop('readonly', false);
			$('.form-control_potensi').prop('readonly', true);
			$('.form-control_mitra').attr("required", "required");
			$('.form-control_potensi').removeAttr("required");
		}else{
			$('.form-control_mitra').prop('readonly', true);
			$('.form-control_potensi').prop('readonly', false);
			$('.form-control_mitra').removeAttr("required");
			$('.form-control_potensi').attr("required", "required");
		}
	}
	
	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-master-matrix")[0]);

				$.ajax({
					url: baseURL + 'depo/master/save/matrix',
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
		$(".form-master-matrix input, .form-master-matrix select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "depo/master/get/matrix_header",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_matrix_header: $(this).data("edit")
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						console.log(data);
						$("input[name='id_matrix_header']").val(v.id_matrix_header);
						$("input[name='id_matrix_hidden']").val(v.id_matrix);
						$("select[name='id_matrix']").val(v.id_matrix).trigger("change");
						$("input[name='bobot']").val(v.bobot);
						
						//detail
						if (v.arr_data_detail) {
							let output = "";
							$.each(v.arr_data_detail, function(a, b){
								output += "<tr>";
								output += '	<td>';
								output += '		<input type="text" class="form-control form-control_mitra" name="param_text[]" value="'+b.param_text+'"/>';
								output += '	</td>';
								output += '	<td width="20%">';
								output += '		<input type="text" class="form-control form-control_potensi text-center angka" name="param_awal[]" min="0"  value="'+b.param_awal+'"/>';
								output += '	</td>';
								output += '	<td width="20%">';
								output += '		<input type="text" class="form-control form-control_potensi text-center angka" name="param_akhir[]"  value="'+b.param_akhir+'"/>';
								output += '	</td>';
								output += '	<td width="20%">';
								output += '		<input type="text" class="form-control text-center angka" name="nilai[]"  value="'+b.nilai+'"/>';
								output += '	</td>';
								output += '</tr>';
							});
							// $("#input-score-wrapper").append(output);
							$("#input-score-wrapper").html(output);
							
							$('#id_matrix').prop('disabled', true);
							$('#bobot').prop('disabled', false);
							if(v.jenis_matrix=='mitra'){
								$(".form-control_potensi").val('');
								$('.form-control_mitra').prop('readonly', false);
								$('.form-control_potensi').prop('readonly', true);
							}else{
								$(".form-control_mitra").val('');
								$('.form-control_mitra').prop('readonly', true);
								$('.form-control_potensi').prop('readonly', false);
							}
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
