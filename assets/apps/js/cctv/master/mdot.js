$(document).ready(function () {
	//submit form
	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate('.form-master-dot',true);
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-master-dot")[0]);
				$.ajax({
					url: baseURL + 'cctv/master/save/dot',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function () {
			            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
			            $("body .overlay-wrapper").append(overlay);
			        },
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
						$("body .overlay-wrapper .overlay").remove();
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

	// get sublok
	$(document).on("change", "#lokasi_fieldname_parent", function(e){
    	var lokasi_parent = $(this).val();
    	$('#divsublok').html('');
    	//header tab
    	$.ajax({
			url: baseURL + "cctv/master/get/sublokasi",
			type: 'POST',
			dataType: 'JSON',
			data: {
				lokasi: lokasi_parent
			},
			
			success: function (data) {
				$('#divsublok').html('');
				var value = '';
				value += '<select class="form-control input-xxlarge " name="lokasi_fieldname" id="lokasi_fieldname" style="width: 100%;"  required>'
						+'<option value="0">Silahkan pilih sublokasi</option>';
				$.each(data, function(i,v){
					value += '<option value="'+v.id_sub_lokasi+'">'+v.nama+'</option>';
				});
				
				$('#divsublok').append(value+'</select>');
								
			}

		});
	});

	// get Area
	$(document).on("change", "#lokasi_fieldname", function(e){
		var lokasi_parent 	= $('#lokasi_fieldname_parent').val(); 
    	var sub_lokasi 		= $(this).val();
    	$('#divarea').html('');
    	//header tab
    	$.ajax({
			url: baseURL + "cctv/master/get/area",
			type: 'POST',
			dataType: 'JSON',
			data: {
				// lokasi 		: lokasi_parent,
				sublokasi 	: sub_lokasi
			},
			
			success: function (data) {
				$('#divarea').html('');
				var value = '';
				value += '<select class="form-control input-xxlarge " name="area_fieldname" id="area_fieldname" style="width: 100%;"  required>'
						+'<option value="0">Silahkan pilih area</option>';
				$.each(data, function(i,v){
					value += '<option value="'+v.id_area+'">'+v.nama+'</option>';
				});
				
				$('#divarea').append(value+'</select>');
								
			}

		});
	});

	$(document).on("click", ".edit", function (e) {
		// $(".form-master-dot input, .form-master-dot select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "cctv/master/get/dot",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mdot: $(this).data("edit")
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						console.log(data);
						$("input[name='id_mdot']").val(v.id_mdot);						
						$("input[name='dot_fieldname']").val(v.dot);
						$("select[name='lokasi_fieldname']").val(v.id_sublokasi);
						$("select[name='lokasi_fieldname_parent']").val(v.id_lokasi);
						$("select[name='area_fieldname']").val(v.id_area);

						//set sublok
						var lokasi_parent = v.id_lokasi;
				    	$('#divsublok').html('');
				    	$('#divarea').html('');
				    	//header tab
				    	$.ajax({
							url: baseURL + "cctv/master/get/sublokasi",
							type: 'POST',
							dataType: 'JSON',
							data: {
								lokasi: lokasi_parent
							},
							
							success: function (data) {
								$('#divsublok').html('');
								var value = '';
								value += '<select class="form-control input-xxlarge " name="lokasi_fieldname" id="lokasi_fieldname" style="width: 100%;"  required>'
										+'<option value="0">Silahkan pilih sublokasi</option>';
								$.each(data, function(i,v){
									value += '<option value="'+v.id_sub_lokasi+'">'+v.nama+'</option>';
								});
								
								$('#divsublok').append(value+'</select>');

								// set area

						    	var sub_lokasi 		= v.id_sublokasi;
						    	$('#divarea').html('');
						    	//header tab
						    	$.ajax({
									url: baseURL + "cctv/master/get/area",
									type: 'POST',
									dataType: 'JSON',
									data: {
										// lokasi 		: lokasi_parent,
										sublokasi 	: sub_lokasi
									},
									
									success: function (data) {
										$('#divarea').html('');
										var value = '';
										value += '<select class="form-control input-xxlarge " name="area_fieldname" id="area_fieldname" style="width: 100%;"  required>'
												+'<option value="0">Silahkan pilih area</option>';
										$.each(data, function(ii,vv){
											value += '<option value="'+vv.id_area+'">'+vv.nama+'</option>';
										});
										
										$('#divarea').append(value+'</select>');

										// set 3 lokasi value
										$("select[name='lokasi_fieldname']").val(v.id_sublokasi);
										$("select[name='lokasi_fieldname_parent']").val(v.id_lokasi);
										$("select[name='area_fieldname']").val(v.id_area);
														
									}

								});

												
							}

						});
						// $("select[name='pabrik_fieldname[]']").select2(v.plant);


						$("#pabrik_fieldname").select2('destroy');
						$("#divpabrik").html('');
						$("#divpabrik").append('<label for="pabrik_fieldname"> Pabrik </label>'
							+'	<input type=text class="form-control" multiple="multiple" name="pabrik_fieldname_text"'
							+'		id="pabrik_fieldname_text" style="width: 100%;" value="'+v.plant_name+'" readonly="readonly" required>'
							+'	<input type=hidden class="form-control" multiple="multiple" name="pabrik_fieldname[]"'
							+'		id="pabrik_fieldname" style="width: 100%;" value='+v.plant+' readonly="readonly" required>'
							+'');
										
						
					});

					$("#btn-new").show();
				}
			}
		});
		e.preventDefault();
		return false;
	});
	
	// reload 
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

    //nonactive
	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "cctv/master/set/dot",
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
