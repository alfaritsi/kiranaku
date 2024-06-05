$(document).ready(function () {
	$.ajax({
		url: baseURL + 'umb/scoring/get/list-scoring',
		type: 'POST',
		dataType: 'JSON',
		data: {
			approval: 'approve'
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
				var statusArray = ["complete", "drop", "stop"];
				var status = "";

				$.each(data, function (i, v) {

					if ($.inArray(v.status, statusArray) < 0) {
						status = "<small>Sedang diproses di " + v.role.replace(/,\s*$/, "").replace(/,/g, ', ') + "</small>";
						if (v.status == '2' && v.jumlah_penilaian < v.jumlah_detail) {
							status = "<small>Menunggu kelengkapan <b>Penilaian Jaminan</b> oleh Manager Kantor</small>";
						}
						if (v.status == '1' && v.jumlah_penilaian < v.jumlah_detail) {
							status = "<small>Sedang diproses di " + v.role.replace(/,\s*$/, "").replace(/,/g, ', ') + " & </small><small>Menunggu kelengkapan <b>Penilaian Jaminan</b> oleh Manager Kantor</small>";
						}
					}

					var action_btn = '<div class="input-group-btn">';
					action_btn += '		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
					action_btn += '		<ul class="dropdown-menu pull-right">';
					// adjusting to reguler and non reguler
					action_btn += '			<li><a href="' + baseURL + 'umb/scoring/detail/reguler/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-list"></i> Detail</a></li>';
					if ($("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1' && v.status == '1') {
						action_btn += '			<li><a href="' + baseURL + 'umb/scoring/edit/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
					}
					if ($("input[name='session_role_isRenewal']").val() == '1' && v.status == 'completed' && v.tipe_scoring == 'Depo Mitra Trial') {
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
		var tipe = $("#tipe").val();

		$.ajax({
			url: baseURL + 'umb/scoring/get/list-scoring',
			type: 'POST',
			dataType: 'JSON',
			data: {
				approval: 'approve',
				plant: plant,
				tahun: tahun,
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
					var statusArray = ["drop", "stop", "completed"];
					var status = "";

					$.each(data, function (i, v) {

						if ($.inArray(v.status, statusArray) < 0) {
							status = "<small>Sedang diproses di " + v.role.replace(/,\s*$/, "").replace(/,/g, ', '); + "</small>";
							if (v.status == '2' && v.jumlah_penilaian < v.jumlah_detail) {
								status = "<small>Menunggu kelengkapan <b>Penilaian Jaminan</b> oleh Manager Kantor</small>";
							}
							if (v.status == '1' && v.jumlah_penilaian < v.jumlah_detail) {
								status = "<small>Sedang diproses di " + v.role.replace(/,\s*$/, "").replace(/,/g, ', ') + " & </small><small>Menunggu kelengkapan <b>Penilaian Jaminan</b> oleh Manager Kantor</small>";
							}
						}

						var action_btn = '<div class="input-group-btn">';
						action_btn += '		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
						action_btn += '		<ul class="dropdown-menu pull-right">';
						action_btn += '			<li><a href="' + baseURL + 'umb/scoring/detail/reguler/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-list"></i> Detail</a></li>';
						if ($("input[name='session_role_nama']").val() == 'Manager Kantor' && $("input[name='session_role_level']").val() == '1' && v.status == '1') {
							action_btn += '			<li><a href="' + baseURL + 'umb/scoring/edit/' + v.no_form_scoring.replace(/\//g, "-") + '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
						}
						if ($("input[name='session_role_isRenewal']").val() == '1' && v.status == 'completed' && v.tipe_scoring == 'Depo Mitra Trial') {
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
							v.aging,
							action_btn
						]).draw(false).node();
					});
				}
			}
		});

	});
});