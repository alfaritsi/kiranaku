$(document).ready(function () {
	$("#btn-new").on("click", function (e) {
		location.reload();
		e.preventDefault();
		return false;
	});

	/*userrole*/
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

	$(document).on("change", ".isSelectAll", function (e) {
		if ($(".isSelectAll").is(':checked')) {
			$('select[name="pabrik[]"').select2('destroy').find('option').prop('selected', 'selected').end().select2();
		} else {
			$('select[name="pabrik[]"').select2('destroy').find('option').prop('selected', false).end().select2();
		}
	});

	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-setting-user")[0]);

				$.ajax({
					url: baseURL + 'umb/setting/save/user',
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
		$(".form-setting-user input, .form-setting-user select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "umb/setting/get/user",
			type: 'POST',
			dataType: 'JSON',
			data: {
				rolenik: $(this).data("edit")
			},
			success: function (data) {
				if (data) {
					$.each(data, function (i, v) {
						$("select[name='role']").val(v.kode_role == null ? 0 : v.kode_role).trigger("change");
						$("input[name='id']").val(v.id_rolenik);

						if (v.nik !== null) {
							var control = $('.select2-user-search').empty().data('select2');
							var adapter = control.dataAdapter;
							var nama = v.nama + ' - [' + v.nik + ']';
							adapter.addOptions(adapter.convertToOptions([{"id": v.nik, "nama": nama}]));
							$('.select2-user-search').trigger('change');
						}

						if(v.kode_pabrik_list){
							array	= v.kode_pabrik_list.split(",");
							$('select[name="pabrik[]"').val(array).trigger("change");
						}
					});

					$("#btn-new").show();
				}
			}
		});
		e.preventDefault();
		return false;
	});

	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "umb/setting/set/user",
			type: 'POST',
			dataType: 'JSON',
			data: {
				rolenik : $(this).data($(this).attr("class")),
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
