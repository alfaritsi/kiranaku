$(document).ready(function () {

	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-master-flow")[0]);

				$.ajax({
					url: baseURL + 'kiass/master/save/flow',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
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
		$(".form-master-flow input, .form-master-flow select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "kiass/master/get/flow",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_flow: $(this).data("edit")
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						$("input[name='id_flow']").val(v.id_flow);
						$("input[name='keterangan']").val(v.keterangan);
						$("input[name='alias_flow']").val(v.alias_flow);
						$("select[name='lokasi']").val(v.lokasi).trigger("change");
					});

					$("#btn-new").show();
				}
			}
		});
		e.preventDefault();
		return false;
	});

	 $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "kiass/master/set/flow",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_flow : $(this).data($(this).attr("class")),
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
