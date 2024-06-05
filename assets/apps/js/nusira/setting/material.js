$(document).ready(function () {
	// Setup datatables
	$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
		return {
			"iStart": oSettings._iDisplayStart,
			"iEnd": oSettings.fnDisplayEnd(),
			"iLength": oSettings._iDisplayLength,
			"iTotal": oSettings.fnRecordsTotal(),
			"iFilteredTotal": oSettings.fnRecordsDisplay(),
			"iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
			"iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
		};
	};

	/**
	 * Get data using serverside datatables
	 * Rules:
	 * if you need to get data from more than 1 table,
	 *        you need to write down table alias + real column in column->data ex: tb1.column
	 *        and you need to write down column alias in column->name
	 */
	$("#sspTable").dataTable({
		ordering: true,
		scrollCollapse: true,
		scrollY: false,
		scrollX: true,
		bautoWidth: false,
		pageLength: $(".my-datatable-extends-order", this).data("page") ? $(".my-datatable-extends-order", this).data("page") : 10,
		paging: $(".my-datatable-extends-order", this).data("paging") ? $(".my-datatable-extends-order", this).data("paging") : true,
		initComplete: function () {
			var api = this.api();
			$('#sspTable_filter input')
				.off('.DT')
				.on('input.DT', function () {
					api.search(this.value).draw();
				});
		},
		oLanguage: {
			sProcessing: "Please wait ..."
		},
		processing: true,
		serverSide: true,
		ajax: {
			url: baseURL + 'nusira/setting/get/bom_datatables',
			type: 'POST',
			error: function (a, b, c) {
				console.log(a);
				console.log(b);
				console.log(c);
			}
		},
		columns: [
			{
				"data": "MAKTX",
				"name": "MAKTX",
				"width": "20%",
				"render": function (data, type, row) {
					return row.MAKTX + '<br><label class="label label-success">' + row.MATNR + '</label>';
				},
				"className" : "wrap-text"
			},
			{
				"data": "spesifikasi",
				"name": "spesifikasi",
				"width": "35%",
				"searchable": false,
				"orderable": false,
				"render": function (data, type, row) {
					return row.spesifikasi;
				},
				"className" : "wrap-text"
			},
			{
				"data": "harga",
				"name": "harga",
				"width": "20%",
				"className": "text-right",
				"render": function (data, type, row) {
					return row.harga;
				}
			},
			{
				"data": "img_list",
				"name": "img_list",
				"width": "10%",
				"searchable": false,
				"orderable": false,
				"render": function (data, type, row) {
					var output = '';
					output += '<div class="input-group-btn">';
					output += '		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action';
					output += '			<span class="fa fa-caret-down"></span>';
					output += '		</button>';
					output += '		<ul class="dropdown-menu pull-right">';
					output += '			<li><a href="javascript:void(0)" class="show_img" data-title="' + row.MAKTX + '" data-image="' + row.img_list + '"><i class="fa fa-search"></i> Lihat Gambar</a></li>';
					output += '		</ul>';
					output += '</div>';

					return output;
				}
			}
		],
		rowCallback: function (row, data, iDisplayIndex) {
			var info = this.fnPagingInfo();
			var page = info.iPage;
			var length = info.iLength;
			$('td:eq(0)', row).html();
		}
	});

	$(".material").select2({
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'nusira/setting/get/bom',
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
			cache: false
		},
		escapeMarkup: function (markup) {
			return markup;
		}, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">[' + repo.MATNR + '] - ' + repo.MAKTX + '</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.MATNR && repo.MAKTX) {
				return '[' + repo.MATNR + '] - ' + repo.MAKTX;
			} else {
				return repo.text;
			}
		}
	});

	$(".material").on('select2:select select2:unselecting change', function (e) {
		var id = "";
		var harga = "";
		var spec = "";
		var img = "";
		var itnum = "";
		if (typeof e.params !== "undefined" && e.params.data) {
			itnum = e.params.data.ITNUM;
			id = e.params.data.MATNR;
			spec = e.params.data.spesifikasi ? e.params.data.spesifikasi.replace(/<br\s*[\/]?>/gi, "\r\n") : "";
			harga = e.params.data.harga_money;
			img = e.params.data.img_list;
		}
		$("input[name='kode']").val(id);
		$("input[name='itnum']").val(itnum);
		$("textarea[name='spesifikasi']").val(spec);
		$("input[name='harga']").val(harga);
		$("input[name='file_material_hidden']").val(img);
		$("input[name='file_material[]']").val(null);
	});

	$(document).on("click", ".show_img", function (e) {
		var img = $(this).data("image");
		show_img_carousel(img, $(this).data("title"));
	});

	$(document).on("click", ".lihat-file", function (e) {
		var img = $(this).closest(".input-group-btn").find(".data-lihat-file").val();
		show_img_carousel(img, $(this).data("title"));
	});

	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate();
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-setting-material")[0]);

				$.ajax({
					url: baseURL + 'nusira/setting/save/material',
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
					error: function () {
						$("input[name='isproses']").val(0);
						kiranaAlert("notOK", "Server Error", "error", "no");
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
});

function show_img_carousel(img = null, title) {
	img = (img && img.indexOf(";") && img.trim() !== "" ? img.slice(0, -1).split(";") : null);

	$('#KiranaModals .modal-title').html(title);

	var output = '';
	if (img && img.length > 0) {
		output += '<div id="carousel-img-material" class="carousel slide" data-ride="carousel">';
		output += '	<ol class="carousel-indicators">';
		$.each(img, function (id, val) {
			var active = (id == 0 ? "active" : "");
			output += '		<li data-target="#carousel-img-material" data-slide-to="' + id + '" class="' + active + '"></li>';
		});
		output += '	</ol>';
		output += '	<div class="carousel-inner">';
		$.each(img, function (i, v) {
			var ext = (v !== "" ? v.split('.').pop() : null);
			var act = "";
			if (i == 0) act = "active";
			switch (ext) {
				case 'png' :
				case 'jpg' :
					output += '		<div class="item ' + act + '">';
					output += '			<img src="' + baseURL + v + '?' + new Date().getTime() + '">';
					output += '		</div>';
					break;
			}
		});
		output += '	</div>';
		output += '</div>';
	}

	if (output == "") {
		kiranaAlert("notOK", "File tidak ditemukan", "error", "no");
	} else {
		$('#KiranaModals .modal-body').html(output);
		$('#carousel-img-material').carousel({
			interval: 2000,
			pause: 'hover'
		});

		$('#KiranaModals').modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});
	}
}
