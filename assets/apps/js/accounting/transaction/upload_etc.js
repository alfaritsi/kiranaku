$(document).ready(function () {
	$("#btn-new").on("click", function (e) {
		location.reload();
		e.preventDefault();
		return false;
	});

	$(".edit").on("click", function (e) {
		var id = $(this).data("edit");

		$(".modal-title").html("Edit Upload Laporan Accounting");

		$("#div_exist").html("");
		$("#div_exist").append("<div class='form-group' style='margin-bottom: 5px;'>"
			+ "<label class='col-md-4'>Existing File</label>"
			+ "<div class='col-md-8'>"
			+ "<div id='fileexist' style='margin-bottom:10px;'></div>"
			+ "</div>"
			+ "</div>"
			+ "<div class='clearfix'></div>");

		$('#file').removeAttr('required');

		$.ajax({
			url: baseURL + 'accounting/transaction/get_data/upload_etc',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id: id
			},
			success: function (data) {
				console.log(data);
				// $(".modal-title").html("<i class='fa fa-pencil'></i> Edit Limbah B3");
				$.each(data, function (i, v) {
					$("#id").val(v.id);
					$("#judul").val(v.text);
					document.getElementById("pabrik").value = v.gsber;
					document.getElementById("jenis").value = v.id_jenis;
					$(".select2").select2();
					$("#pabrik").attr("disabled", true);

					$("#date").val(v.tgl_sap);
					$("#info").val(v.info);

					if (v.filename != "" && v.filename != "-") {
						var str = v.data;
						if (str.substring(0, 3) != "img") {
							$("#fileexist").append("<a href='" + baseURL + v.data + '?' + new Date().getTime() + "' target='_blank' style='color:green;'><i class='fa fa-file-pdf-o'></i> " + v.data2 + " </a><br/>");
						} else {
							$("#fileexist").append("<a href='http://10.0.0.249/dev/kiranaku/home/pdfviewer.php?q=" + v.data + '&' + new Date().getTime() + "' target='_blank' style='color:green;'><i class='fa fa-file-pdf-o'></i> " + str.replace('img/acc/', '') + " </a><br/>");
						}
					} else {
						$("#fileexist").append("<p class='form-control-static'> No file exist</p>");
					}

					$(".datePicker").prop('disabled', true);
					$("#btn-new").removeClass("hidden");
				});
			}
		});

	});

	$(".delete").on("click", function (e) {
		var id = $(this).data("delete");
		kiranaConfirm(
			{
				title: "Konfirmasi",
				text: "Apakah anda akan menghapus data?",
				dangerMode: true,
				successCallback: function () {
					$.ajax({
						url: baseURL + 'accounting/transaction/set_data/delete_del/upload_etc',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id: id
						},
						success: function (data) {
							if (data.sts == 'OK') {
								kiranaAlert(data.sts, data.msg);
							} else {
								kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
				}
			}
		);
	});

	$(document).on("click", "button[name='action_btn']", function (e) {
		e.preventDefault();

		var empty_form = validate(".form-uploadetc");

		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);

				$("#pabrik").attr("disabled", false);
				$(".datePicker").prop('disabled', false);
				var formData = new FormData($(".form-uploadetc")[0]);
				$("#pabrik").attr("disabled", true);

				$.ajax({
					url: baseURL + 'accounting/transaction/save/upload_etc',
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
					}
				});
				$("#pabrik").attr("disabled", true);
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
	});

	$(document).on("click", "button[name='check_btn']", function (e) {

		var chk_arr = document.getElementsByName("checkjurnal[]");
		var chklength = chk_arr.length;
		var checked = false;

		for (k = 0; k < chklength; k++) {
			if (chk_arr[k].checked === true && chk_arr[k].disabled === false) {
				checked = true;
			}
		}
		if (checked === false) {
			kiranaAlert("notOK", "Tidak ada data yang diselect", "warning", "no");
			return false;
		}
		e.preventDefault();
		kiranaConfirm(
			{
				title: "Konfirmasi",
				text: "Apakah anda akan menchecklist data?",
				dangerMode: true,
				successCallback: function () {

					var empty_form = validate(".form-check");

					if (empty_form == 0) {
						var isproses = $("input[name='isproses']").val();
						if (isproses == 0) {
							$("input[name='isproses']").val(1);

							var formData = new FormData($(".form-check")[0]);

							$.ajax({
								url: baseURL + 'accounting/transaction/set_data/update/check_upload_etc',
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
								}
							});

						} else {
							kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
						}
					}
				}
			}
		);
	});

	$('.datePicker').datepicker({
		format: 'dd.mm.yyyy',
		changeMonth: true,
		changeYear: true,
		autoclose: true
		// startDate: new Date(date)
	});

	$('#modal-form').on('hidden.bs.modal', function () {
		$(".datePicker").prop('disabled', false);
		$("#pabrik").prop('disabled', false);
		$(this).find('form').trigger('reset');
		$(".select2").select2();
		$("#fileexist").html("");
	});

});

function filtersubmit() {
	var pabrik = $("#filterpabrik").val();
	var jenis = $("#filterjenis").val();
	var from = $("#filterfrom").val();
	var to = $("#filterto").val();
	var check = $("#chknocheck").val();

	if (pabrik != "" && jenis != "" && from != "" && to != "" && check != "") {
		$('#filterform').submit();
	}
}
