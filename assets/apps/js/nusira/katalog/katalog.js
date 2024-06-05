$(document).ready(function () {
	$.ajax({
		url: baseURL + 'nusira/order/get/material',
		type: 'POST',
		dataType: 'JSON',
		data: {
			type: 'B'
		},
		success: function (data) {
			if (data) {
				var output = '<option value="0">Silahkan pilih</option>';
				$.each(data, function (i, v) {
					output += '<option value="' + v.MATNR + '"> [' + v.MATNR + '] - ' + v.MAKTX + '</option>';
				});
				$("select[name='mesin']").html(output);
			}
		},
		error: function () {
			kiranaAlert("notOK", "Server Error", "error", "no");
		}
	});

	$(document).on("click", ".pagination li a", function (e) {
		get_katalog($(this).data("ci-pagination-page"), $(this).closest(".pagination").data("katalog"));
		e.preventDefault();
	});

	$(document).on("change", "input[name='search'], select[name='jenis'], select[name='mesin']", function () {
		get_katalog(1, $(this).data("katalog"));
	});

	$(document).on("click", ".detail_item", function (e) {
		$('#KiranaModals .modal-dialog').addClass("modal-lg");
		$('#KiranaModals .modal-title').html("Detail Barang");

		var product = ($(this).data("id") ? $(this).data("id") : $(".request_item[data-kode='" + $(this).attr("data-kode") + "'][data-num='" + $(this).attr("data-num") + "']").closest(".product-thumb").attr("id"));
		var status = "";
		if ($(this).attr("data-status") == "unavailable") {
			status = "disabled";
		}

		var button = $(this);

		$.ajax({
			url: baseURL + 'nusira/order/get/material',
			type: 'POST',
			dataType: 'JSON',
			data: {
				kode: $(this).data("kode"),
				itnum: $(this).data("num")
			},
			beforeSend: function () {
				var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
				$("body .overlay-wrapper").append(overlay);
			},
			success: function (data) {
				if (data) {
					var output = '';
					$.each(data, function (i, v) {
						var img = (v.img_list && v.img_list.indexOf(";") >= 0 ? v.img_list.slice(0, -1).split(";") : null);
						var first_img = 'assets/apps/img/test/dummy.png';
						if (!img) {
							img = [];
							img.push(first_img);
						}
						output += '<div class="row">';
						output += '	<div class="product-layout col-sm-4">';
						if (img && img.length > 0) {
							output += '<div id="carousel-img-material" class="carousel slide" data-ride="carousel" data-interval="2000">';
							output += '	<ol class="carousel-indicators">';
							$.each(img, function (id, val) {
								var active = (id == 0 ? "active" : "");
								output += '		<li data-target="#carousel-img-material" data-slide-to="' + id + '" class="' + active + '"></li>';
							});
							output += '	</ol>';
							output += '	<div class="carousel-inner">';
							$.each(img, function (id, val) {
								var ext = (val !== "" ? val.split('.').pop() : null);
								var act = "";
								if (id == 0) act = "active";
								switch (ext) {
									case 'png' :
									case 'jpg' :
										output += '		<div class="item ' + act + ' item' + id + ' image">';
										output += '			<img />';
										output += '		</div>';
										checkImage(baseURL + val + '?' + new Date().getTime(), "#KiranaModals #carousel-img-material .item.item" + id + " img", baseURL + first_img + '?' + new Date().getTime());
										break;
								}
							});
							output += '	</div>';
							output += '</div>';
						}
						output += '	</div>';
						output += '	<div class="product-layout col-sm-8">';
						output += '		<div class="caption">';
						output += '			<h4>' + v.MAKTX + '</h4>';
						output += '			<div class="row">';
						output += '				<div class="col-sm-3">Material</div><div class="col-sm-6">: [' + v.MATNR + ' | ' + v.KDMAT + ']</div>';
						output += '			</div>';
						output += '			<div class="row">';
						output += '				<div class="col-sm-3">Price</div><div class="col-sm-6">: ' + (v.harga_money !== null ? 'Rp ' + v.harga_money + ' <small style="color: red">(exclude PPN)</small>' : 'Out of Stock') + '</div>';
						output += '			</div>';
						if (v.harga_money !== null && button.attr("data-katalog") == "order") {
							output += '			<button type="button" class="btn btn-default request_item" data-kode="' + v.MATNR + '" data-num="' + v.ITNUM + '" ' + status + ' data-id="' + product + '"><i class="fa fa-shopping-cart"></i> <span>Request</span></button>';
						}
						output += '			<h5><strong>Spesifikasi:</strong></h5>';
						output += '			<p class="thumb-spec">' + (v.spesifikasi == null ? "-" : v.spesifikasi) + '</p>';
						output += '		</div>';
						output += '	</div>';
						output += '</div>';

						if (v.child && v.child.length > 0) {
							var length = v.child.length;
							var indicator = Math.ceil(length / 4);

							output += '<div class="row" style="padding: 10px; border-top: 1px solid rgba(0, 0, 0, 0.22);">';
							output += '	<div class="col-sm-12">';
							output += '		<h5>Daftar Komponen ' + v.MAKTX + '</h5>';
							output += '		<div id="carousel-img-material-comp" class="carousel slide" data-ride="carousel" data-interval="4000">';
							output += '			<ol class="carousel-indicators">';
							for (i = 0; i < indicator; i++) {
								var act = "";
								if (i == 0) act = "active";
								output += '			<li data-target="#carousel-img-material-comp" data-slide-to="' + i + '" class="' + act + '"></li>';
							}
							output += '			</ol>';
							output += '			<div class="carousel-inner">';
							$.each(v.child, function (idx, val) {
								var img = (val.img_list && val.img_list.indexOf(";") >= 0 ? val.img_list.slice(0, -1).split(";") : null);
								var default_img = 'assets/apps/img/test/dummy.png';
								var first_img = default_img;
								if (img) {
									first_img = img[0];
								}
								checkImage(baseURL + first_img + '?' + new Date().getTime(), "#KiranaModals .modal-body .item .images img", baseURL + default_img + '?' + new Date().getTime());
								var act = "";
								if (idx == 0) act = "active";
								if (idx % 4 == 0)
									output += '			<div class="item ' + act + '">';
								output += '				<div class="product-layout col-sm-3">';
								output += '					<div class="images">';
								output += '						<img alt="images" title="' + val.MAKTX + '" class="img-responsive">';
								output += '					</div>';
								output += '					<div class="product-thumb">';
								output += '						<div class="button-group">';
								output += '							<button type="button" class="btn btn-default col-lg-12 col-md-12 col-sm-12 col-xs-12 detail_item" data-kode="' + val.MATNR + '" data-num="' + val.ITNUM + '" data-katalog="' + button.attr("data-katalog") + '"><span>' + val.MAKTX + '</span></button>';
								output += '						</div>';
								output += '					</div>';
								output += '				</div>';
								if (idx % 4 == 3)
									output += '			</div>';
							});
							output += '			</div>';
							output += '		</div>';
							output += '	</div>';
							output += '</div>';
						}
					});
					$('#KiranaModals .modal-body').html(output);
					$('#carousel-img-material, #carousel-img-material-comp').carousel({
						interval: $(this).data("interval"),
						pause: 'hover'
					});
					$('#KiranaModals .modal-body .thumb-spec').slimScroll({
						height: '100px'
					});
				}
			},
			error: function () {
				kiranaAlert("notOK", "Server Error", "error", "no");
			},
			complete: function () {
				$("body .overlay-wrapper .overlay").remove();

				$('#KiranaModals').modal({
					backdrop: 'static',
					keyboard: true,
					show: true
				});
			}
		});
	});
});

function get_katalog(page, action) {
	if (page) {
		var search = $("input[name='search']").val();
		var mesin = $("select[name='mesin']").val();
		var type = $("select[name='jenis']").val();

		if (type == "C") {
			$("#filter_mesin").removeClass("hidden");
		} else {
			$("select[name='mesin']").val(0).trigger("change.select2");
			mesin = $("select[name='mesin']").val();
			$("#filter_mesin").addClass("hidden");
		}

		if (typeof action == "undefined")
			action = "";

		$.ajax({
			url: baseURL + 'nusira/order/get/katalog/' + page,
			type: 'POST',
			dataType: 'JSON',
			data: {
				search: search,
				mesin: mesin,
				type: type,
			},
			beforeSend: function () {
				var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
				$("body .overlay-wrapper").append(overlay);
			},
			success: function (data) {
				if (data) {
					var output = '';
					if (data.results.length > 0) {
						$.each(data.results, function (i, v) {
							var img = (v.img_list && v.img_list.indexOf(";") >= 0 ? v.img_list.split(";") : null);
							var default_img = 'assets/apps/img/test/dummy.png';
							var first_img = default_img;
							if (img) {
								first_img = img[0];
							}
							checkImage(baseURL + first_img + '?' + new Date().getTime(), ".product-layout #product" + page + '_' + i + " img", baseURL + default_img + '?' + new Date().getTime());
							var status = "";
							var status_modal = "available";
							if (v.harga_money == null || parseFloat(v.harga) <= 0 || v.spesifikasi == null) {
								status = "disabled";
								status_modal = "unavailable";
							}

							output += '<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">';
							output += '	<div id="product' + page + '_' + i + '" class="product-thumb transition">';
							output += '		<div class="image">';
							output += '			<img src="' + baseURL + first_img + '?' + new Date().getTime() + '" alt="images" title="' + v.MAKTX + '" class="img-responsive">';
							output += '		</div>';
							output += '		<div class="caption">';
							output += '			<h4>' + v.MAKTX + '</h4>';
							output += '			<p>[' + v.MATNR + ' | ' + v.KDMAT + ']</p>';
							output += '			<p class="thumb-spec">' + (v.spesifikasi == null ? "-" : v.spesifikasi) + '</p>';
							output += '			<p class="thumb-prize">' + (v.harga_money !== null ? 'Rp ' + v.harga_money : '-') + '*</p>';
							output += '			<p><small style="color: red">*Price exclude PPN</small></p>';
							output += '		</div>';
							output += '		<div class="button-group">';
							var lebar_detail_btn = "col-lg-12 col-md-12 col-sm-12 col-xs-12";
							//untuk halaman katalog order
							if (action === "order") {
								lebar_detail_btn = "col-lg-6 col-md-6 col-sm-6 col-xs-6";
								output += '			<button type="button" class="btn btn-default col-lg-6 col-md-6 col-sm-6 col-xs-6 request_item" data-kode="' + v.MATNR + '" data-num="' + v.ITNUM + '" data-id="product' + page + '_' + i + '" ' + status + '><i class="fa fa-shopping-cart"></i> <span>Request</span></button>';
							}
							output += '			<button type="button" class="btn btn-default ' + lebar_detail_btn + ' detail_item" data-kode="' + v.MATNR + '" data-num="' + v.ITNUM + '" data-status="' + status_modal + '" data-id="product' + page + '_' + i + '" data-katalog="' + action + '"><i class="fa fa-search"></i> <span>Details</span></button>';
							output += '		</div>';
							output += '	</div>';
							output += '</div>';
						});

						$(".katalog-product").html(output);
						$(".pagination-wrapper").html(data.links);
					} else {
						output += '<div class="col-sm-12">';
						output += '	<div class="well text-center">No data found</div>';
						output += '</div>';

						$(".katalog-product").html(output);
						$(".pagination-wrapper").html("");
					}
				}
			},
			error: function () {
				$("body .overlay-wrapper .overlay").remove();
				kiranaAlert("notOK", "Server Error", "error", "no");
			},
			complete: function () {
				$("body .overlay-wrapper .overlay").remove();


				$("input[name='matnr[]']").each(function (e) {
					var matnr = $(this).val();
					$(".request_item[data-kode='" + matnr + "']").prop("disabled", "disabled");
					$(".detail_item[data-kode='" + matnr + "']").attr("data-status", "unavailable");
					$(".request_item[data-kode='" + matnr + "']").closest(".product-thumb").css("background-color", "rgba(0, 141, 76, 0.3)");
				});
			}
		});
	}
}
