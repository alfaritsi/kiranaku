$(document).ready(function () {
	$.ajax({
		url: baseURL + 'umb/scoring/get/list-scoring',
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			console.log(data);
			if (data) {
				$('.my-datatable-extends-order').DataTable().destroy();
				
				// var t = generate_table('.my-datatable-extends-order');
				var t = $('.my-datatable-extends-order').DataTable({
					ordering: true,
					columnDefs: [
                        {"type": 'date-eu',"className": "text-center", "targets": 0},
                        {"type": 'date-eu',"className": "text-center", "targets": 6},
                    ],
					order: [0, 'desc'],
				});
				t.clear().draw();
				var statusArray = ["finish", "drop"];
				var status = "";

				$.each(data, function (i, v) {

					if (v.status !== "finish" && v.status !== "drop" && v.status !== "completed" && v.status !== "stop") {
						status = "<small>Sedang diproses di " + v.role.replace(/,\s*$/, "").replace(/,/g, ', '); + "</small>";
						if (v.status == '2' && v.jumlah_penilaian < v.jumlah_detail) {
							status = "<small>Menunggu kelengkapan <b>Penilaian Jaminan</b> oleh Manager Kantor</small>";
						}
						if (v.status == '1' && v.jumlah_penilaian < v.jumlah_detail) {
							status = "<small>Sedang diproses di " + v.role.replace(/,\s*$/, "").replace(/,/g, ', ') + " & </small><small>Menunggu kelengkapan <b>Penilaian Jaminan</b> oleh Manager Kantor</small>";
						}
					}else{
						if (v.status == "finish") {
							status = (v.status_mou == '1' ? "<small>Menunggu kelengkapan <b>MOU</b> oleh Manager Kantor</small>" : (v.status_mou == '5' ? "<small>Menunggu Approval <b>MOU</b> oleh Div Head Legal HO</small>" : ""));
						}else{
							status = "";
						}
					}

					var action_btn = '<div class="input-group-btn">';
					action_btn += '		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
					action_btn += '		<ul class="dropdown-menu pull-right">';
					if(v.file_berita_acara){
						action_btn += '			<li><a href="' + baseURL + 'umb/scoring/detail/nonreguler/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-list"></i> Detail</a></li>';
					}else{
						action_btn += '			<li><a href="' + baseURL + 'umb/scoring/detail/reguler/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-list"></i> Detail</a></li>';
					}
					if ($("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1' && v.status == '1') {
						action_btn += '			<li><a href="' + baseURL + 'umb/scoring/edit/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
					}
					if ($("input[name='session_role_isRenewal']").val() == '1' && v.status == 'completed' && v.tipe_scoring == 'Depo Mitra Trial' && v.no_extend == null) {
						action_btn += '			<li><a href="javascript:void(0)" class="renewal" data-nomor="'+v.no_form_scoring+'"><i class="fa fa-hourglass-half"></i> Perpanjang</a></li>';
					}
					action_btn += '		</ul>';
					action_btn += '	  </div>';

					var myrow = t.row.add([
						v.tanggal_scoring,
						v.no_form_scoring,
						v.tipe_scoring,
						(v.id_scoring_tipe == '1' ? v.nama_supplier +' - ['+v.kode_supplier+']' : v.nama_depo +' - ['+v.depo+']'),
						numberWithCommas(v.um_minta),
						v.view_status + "<br>" + status,
						v.tanggal_akhir,
						v.aging,
						action_btn
					]).draw(false).node();
				});
			}
		}
	});

	$(document).on("change", "#plant, #tahun, #status, #tipe", function () {
		var plant = $("#plant").val();
		var tahun = $("#tahun").val();
		var status = $("#status").val();
		var tipe = $("#tipe").val();

		$.ajax({
			url: baseURL + 'umb/scoring/get/list-scoring',
			type: 'POST',
			dataType: 'JSON',
			data: {
				plant: plant,
				tahun: tahun,
				status: status,
				tipe: tipe,
			},
			success: function (data) {
				if (data) {
					$('.my-datatable-extends-order').DataTable().destroy();
					// var t = generate_table('.my-datatable-extends-order');
					var t = $('.my-datatable-extends-order').DataTable({
						ordering: true,
						columnDefs: [
	                        {"type": 'date-eu',"className": "text-center", "targets": 0},
	                        {"type": 'date-eu',"className": "text-center", "targets": 6},
	                    ],
						order: [0, 'desc'],
					});
					t.clear().draw();
					var statusArray = ["finish", "drop"];
					var status = "";

					$.each(data, function (i, v) {

						if (v.status !== "finish" && v.status !== "drop" && v.status !== "completed" && v.status !== "stop") {
							status = "<small>Sedang diproses di " + v.role.replace(/,\s*$/, "").replace(/,/g, ', '); + "</small>";
							if (v.status == '2' && v.jumlah_penilaian < v.jumlah_detail) {
								status = "<small>Menunggu kelengkapan <b>Penilaian Jaminan</b> oleh Manager Kantor</small>";
							}
							if (v.status == '1' && v.jumlah_penilaian < v.jumlah_detail) {
								status = "<small>Sedang diproses di " + v.role.replace(/,\s*$/, "").replace(/,/g, ', ') + " & </small><small>Menunggu kelengkapan <b>Penilaian Jaminan</b> oleh Manager Kantor</small>";
							}
						}else{
							if (v.status == "finish") {
								status = (v.status_mou == '1' ? "<small>Menunggu kelengkapan <b>MOU</b> oleh Manager Kantor</small>" : (v.status_mou == '5' ? "<small>Menunggu Approval <b>MOU</b> oleh Div Head Legal HO</small>" : ""));
							}else{
								status = "";
							}
						}

						var action_btn = '<div class="input-group-btn">';
						action_btn += '		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
						action_btn += '		<ul class="dropdown-menu pull-right">';
						// adjusting to reguler and non reguler
						if(v.file_berita_acara){
							action_btn += '			<li><a href="' + baseURL + 'umb/scoring/detail/nonreguler/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-list"></i> Detail</a></li>';
						}else{
							action_btn += '			<li><a href="' + baseURL + 'umb/scoring/detail/reguler/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-list"></i> Detail</a></li>';
						}
						if ($("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1' && v.status == '1') {
							action_btn += '			<li><a href="' + baseURL + 'umb/scoring/edit/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
						}
						if ($("input[name='session_role_isRenewal']").val() == '1' && v.status == 'completed' && v.tipe_scoring == 'Depo Mitra Trial' && v.no_extend == null) {
							action_btn += '			<li><a href="javascript:void(0)" class="renewal" data-nomor="'+v.no_form_scoring+'"><i class="fa fa-hourglass-half"></i> Perpanjang</a></li>';
						}
						action_btn += '		</ul>';
						action_btn += '	  </div>';

						var myrow = t.row.add([
							v.tanggal_scoring,
							v.no_form_scoring,
							v.tipe_scoring,
							(v.id_scoring_tipe == '1' ? v.nama_supplier +' - ['+v.kode_supplier+']' : v.nama_depo +' - ['+v.depo+']'),
							numberWithCommas(v.um_minta),
							v.view_status + "<br>" + status,
							v.tanggal_akhir,
							v.aging,
							action_btn
						]).draw(false).node();
					});
				}
			}
		});

	});

	$(document).on("click", ".renewal", function () {
		var no_form_scoring =$(this).data("nomor");

		$("#KiranaModals").removeAttr("class");
		$("#KiranaModals").addClass("modal");
		$("#KiranaModals").addClass("modal-success");

		var output = '';
		output += '<form class="form-extend-dmt" enctype="multipart/form-data">';
		output += '	<div class="modal-body">';
		output += '		<div class="form-horizontal">';
		output += '			<div class="form-group">';
		output += '				<label for="komentar" class="col-sm-12 control-label text-left">Komentar</label>';
		output += '				<div class="col-sm-12">';
		output += '					<textarea class="form-control" name="komentar" required></textarea>';
		output += '				</div>';
		output += '			</div>';
		output += '		</div>';
		output += '	</div>';
		output += '	<div class="modal-footer">';
		output += '		<div class="form-group">';
		output += '			<input type="hidden" name="no_form_scoring" value="' + no_form_scoring + '">';
		output += '			<button type="button" class="btn btn-primary" name="submit-form-extend">Submit</button>';
		output += '		</div>';
		output += '	</div>';
		output += '</form>';

		$('#KiranaModals .modal-body').remove();
		$('#KiranaModals .modal-footer').remove();
		$('#KiranaModals form').remove();
		$('#KiranaModals .modal-content').append(output);

		$('#KiranaModals').modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});
	});

	$(document).on("click", "button[name='submit-form-extend']", function () {
		var empty_form = validate(".form-extend-dmt");
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-extend-dmt")[0]);
				$.ajax({
					url: baseURL + "umb/scoring/save/renewal",
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
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					complete: function () {
						$("body .overlay-wrapper .overlay").remove();
					}
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
	});

	$(document).on("click", "#plafon_terpakai", function () {
		$.ajax({
			url: baseURL + "umb/scoring/get/plafon-terpakai",
			type: 'POST',
			dataType: 'JSON',
			beforeSend: function () {
				$('#KiranaModals .modal-title').html("Data Plafon Terpakai");

				var elements = '<table class="table table-bordered table-modals">';
				elements += '	<thead>';
				elements += '		<th>Plant</th>';
				elements += '		<th>On Progress</th>';
				elements += '		<th>Finish</th>';
				elements += '	</thead>';
				elements += '	<tbody></tbody>';
				elements += '</table>';
				$('#KiranaModals .modal-body').html(elements);
			},
			success: function (data) {
				if (data) {
					$('.table-modals').DataTable().destroy();
					var t = $('.table-modals').DataTable({
						columnDefs: [
	                        {"className": "text-center", "targets": 0},
	                        {"className": "text-right", "targets": 1},
	                        {"className": "text-right", "targets": 2},
	                    ]
					});
					t.clear().draw();

					$.each(data, function (i, v) {
						var myrow = t.row.add([
							v.plant,
							v.progress,
							v.finish
						]).draw(false);
					});
				}
			},
			complete: function () {
				setTimeout(function () {
					adjustDatatableWidth();
				}, 1000);
				$('#KiranaModals').modal('show');
			}
		});
	});

});
