$(document).ready(function () {
	//submit form
	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-master-criteria")[0]);
				$.ajax({
					url: baseURL + 'cctv/master/save/criteria',
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
						$("input[name='isproses']").val(0);
						$("body .overlay-wrapper .overlay").remove();
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
		// $(".form-master-dot input, .form-master-dot select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "cctv/master/get/criteria",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_criteriaAchv: $(this).data("edit")
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						console.log(data);
						$("input[name='id_criteriaAchv']").val(v.id_criteriaAchv);						
						$("input[name='kriteria_fieldname']").val(v.criteria);
						$("select[name='warna_fieldname']").val(v.id_css);
						$("input[name='min_fieldname']").val(v.val_min);
						$("input[name='max_fieldname']").val(v.val_max);
						// $("#jenis").val(v.id_perijinan+"-"+v.perijinan).triger('change');					
						
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
			url: baseURL + "cctv/master/set/criteria",
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
