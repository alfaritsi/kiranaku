$(document).ready(function () {
	/*userrole*/
	
	$("#isAksesRekom").on("change", function(e){
	    $("#isRekom").val("");
	    if($("#isAksesRekom").is(':checked')) {
	    	$("#isRekom").val("on");
	    }else{
	    	$("#isRekom").val("off");
	    }
	});

	$("#isAksesRenewal").on("change", function(e){
	    $("#isRenewal").val("");
	    if($("#isAksesRenewal").is(':checked')) {
	    	$("#isRenewal").val("on");
	    }else{
	    	$("#isRenewal").val("off");
	    }
	});

	$(".select2-user-search").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'umb/master/get_user_autocomplete',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page
				};
			},
			processResults: function (data, page) {
				return {
					results: data.items
				};
			},
			cache: true
		},
		escapeMarkup: function (markup) {
			return markup;
		}, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.posst) $("input[name='caption']").val(repo.posst);
			if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
			else return repo.nama;
		}
	});

	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-master-role")[0]);

				$.ajax({
					url: baseURL + 'umb/master/save/role',
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
		$(".form-master-role input, .form-master-role select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "umb/master/get/role",
			type: 'POST',
			dataType: 'JSON',
			data: {
				kode: $(this).data("edit")
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						$("input[name='kode_role']").val(v.kode_role);
						$("input[name='role']").val(v.nama_role);
						$("input[name='level']").val(v.level);
						$("input[name='limit-app']").val((v.limit_app == null ? 0 : numberWithCommas(v.limit_app)));
						$("select[name='if_approve']").val(v.if_approve == null ? 0 : v.if_approve).trigger("change");
						$("select[name='if_assign']").val(v.if_assign == null ? 0 : v.if_assign).trigger("change");
						$("select[name='if_decline']").val(v.if_decline == null ? 0 : v.if_decline).trigger("change");
						$("select[name='if_drop']").val(v.if_drop == null ? 0 : v.if_drop).trigger("change");
						$("select[name='hak_akses_plafon']").val(v.akses_plafon == null ? 0 : v.akses_plafon).trigger("change");
						if(v.is_rekom == 1) {
							$("#isAksesRekom").prop("checked", true);
							$("#isRekom").val("on");
						}else{
							$("#isAksesRekom").prop("checked", false);
							$("#isRekom").val("off");
						}

						if(v.is_renewal == 1) {
							$("#isAksesRenewal").prop("checked", true);
							$("#isRenewal").val("on");
						}else{
							$("#isAksesRenewal").prop("checked", false);
							$("#isRenewal").val("off");
						}

						if(v.disposisi_nik !== null) {
							var control = $('.select2-user-search').empty().data('select2');
							var adapter = control.dataAdapter;
							var nama = v.disposisi_nama + ' - [' + v.disposisi_nik + ']';
							adapter.addOptions(adapter.convertToOptions([{"id": v.disposisi_nik, "nama": nama}]));
							$('.select2-user-search').trigger('change');
						}
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
			url: baseURL + "umb/master/set/role",
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
