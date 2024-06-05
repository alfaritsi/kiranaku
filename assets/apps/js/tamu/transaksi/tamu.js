$(document).ready(function(){
    //=======FILTER=======//
	$(document).on("change", "#filter_from,#filter_to,#filter_status", function(){
		get_data_tamu();
	});
	//konfirmasi
	$(document).on("click", ".konfirmasi", function(){
		var id_tamu		= $(this).data("id_tamu");
		$('#set_nik').val('').trigger('change');
		$.ajax({
    		url: baseURL+'tamu/transaksi/get/data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tamu : id_tamu
			},
			success: function(data){
				$(".title-form").html("Konfirmasi Kepulangan");
				$.each(data, function(i,v){
					$("#id_tamu").val(v.id_tamu);
					$("input[name='caption_tanggal_kunjungan']").val(v.caption_tanggal_kunjungan);
					$("input[name='nama_tamu']").val(v.nama_tamu);
					$("input[name='perusahaan']").val(v.perusahaan);
					$("input[name='nama_karyawan']").val(v.nama_karyawan);
				});
			},
			complete: function () {
				$('#konfirmasi_modal').modal('show');
			}
		});
    });
	//auto complete old
	$('#set_nik').select2({
		dropdownParent: $('.form-transaksi-tamu'),
		ajax: {
			delay: 250,
			url: baseURL + 'tamu/transaksi/get/karyawan',
			method: 'POST',
			dataType: 'json',
			processResults: function (data) {
				data.data.forEach(function (v) {
					v.id = v.nik;
					v.text = v.nama;
					v.id_divisi = v.id_divisi;
				});
				return {
					results: data.data
				};
			},
			cache: true
		},
		placeholder: 'Cari Karyawan (Nama atau NIK)',
		allowClear: true,
		minimumInputLength: 3,
		escapeMarkup: function (markup) {
			return markup;
		},
		templateResult: formatSearchAset,
		templateSelection: formatAsetSelection
	});
	
	// save konfirmasi
    $(document).on("click", "button[name='action_btn_konfirmasi']", function (e) {
        var empty_form = validate('.form-transaksi-tamu');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-tamu")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'tamu/transaksi/save/konfirmasi',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                // location.reload();
								get_data_tamu();
								$('#konfirmasi_modal').modal('hide');
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
					complete: function () {
						$("input[name='isproses']").val(0);
					}
                });
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });

	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "material/transaksi/set/request",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item : $(this).data($(this).attr("class")),
				type 	  	 	: $(this).attr("class")
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
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Data Buku Tamu',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9]
                }
            }
        ],
		scrollX:true
    } );

	//tanggal
    $('.datePicker').datepicker({
    	format: 'dd.mm.yyyy', 
        changeMonth: true,
        changeYear: true,
        autoclose: true
    });

	//konfirmasi hadir
	$(document).on("click", ".konfirmasi_hadir", function(){
		let id_tamu	= $(this).data("id_tamu");
		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			$.ajax({
				url: baseURL+'tamu/transaksi/save/konfirmasi_hadir',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_tamu : id_tamu
				},
				success: function (data) {
					if (data.sts == 'OK') {
						swal('Success', data.msg, 'success').then(function () {
							// location.reload();
							get_data_tamu();
						});
					} else {
						$("input[name='isproses']").val(0);
						swal('Error', data.msg, 'error');
					}
				},
				complete: function () {
					$("input[name='isproses']").val(0);
				}
			});
		} else {
			swal({
				title: "Silahkan tunggu proses selesai.",
				icon: 'info'
			});
		}
	});

	//hasil assessment
	$(document).on("click", ".hasil_assessment", function(){
		$("#table-assessment tbody").empty();
		let id_tamu		= $(this).data("id_tamu");
		let score		= $(this).data("score_assessment");
		let score_danger = $(this).data("score_assessment_danger");
		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			$.ajax({
				url: baseURL+'tamu/transaksi/get/assessment',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_tamu : id_tamu,
					return: 'json'
				},
				success: function(data){
					if (score >= 15 || score_danger > 0)
						$("#total_score_assessment").html('<span class="badge bg-red">' + score + '</span>');
					else if (score >= 10)
						$("#total_score_assessment").html('<span class="badge bg-yellow">' + score + '</span>');
					else 
						$("#total_score_assessment").html('<span class="badge bg-green">' + score + '</span>');
					// console.log(data);
					let no = 0;
					$.each(data, function(i,v){
						$("#suhu_assessment").html(v.suhu_tubuh);
						if (v.score > 0) {
							no++;
							const class_text = (v.is_danger == 1) ? 'text-red' : '';
							$("#table-assessment tbody").append('<tr class="' + class_text + '">' +
								'<td style="text-align:center">' + no + '</td>' +
								'<td>' + v.pertanyaan + '</td>' +
								'<td style="text-align:center">' + v.score + '</td>'
							);
						}
					});
					if (no == 0){
						$("#table-assessment tbody").html('<tr><td colspan="3">No Data</td></tr>');
					}
				},
				complete: function () {
					$("input[name='isproses']").val(0);
					$('#modal_hasil_assessment').modal('show');
				}
			});
		} else {
			swal({
				title: "Silahkan tunggu proses selesai.",
				icon: 'info'
			});
		}
    });

});

function get_data_tamu () {
	var filter_status	= $("#filter_status").val();
	var filter_from		= $("#filter_from").val();
	var filter_to		= $("#filter_to").val();
	$.ajax({
		url: baseURL+'tamu/transaksi/get/data',
		type: 'POST',
		dataType: 'JSON',
		data: {
			filter_status 			: filter_status,
			filter_from 			: filter_from,
			filter_to 				: filter_to
		},
		success: function(data){
			var output 	= "";
			var desc	= "";
			var t 	= $('.my-datatable-extends-order').DataTable();
			t.clear().draw();
			$.each(data, function(i,v){
				//hasil assessment
				let hasil_assessment = "<td></td>";
				if (v.is_assessment == 1) {
					if (v.score_assessment >= 15 || v.score_assessment_danger > 0)
						hasil_assessment = "<td style='text-align:center;'><a href='javascript:void(0)' class='hasil_assessment' data-id_tamu='" + v.id_tamu + "' data-score_assessment='" + v.score_assessment + "' data-score_assessment_danger='" + v.score_assessment_danger + "'><span class='badge bg-red'>Resiko Besar</span></a></td>";
					else if (v.score_assessment >= 10)
						hasil_assessment = "<td style='text-align:center;'><a href='javascript:void(0)' class='hasil_assessment' data-id_tamu='" + v.id_tamu + "' data-score_assessment='" + v.score_assessment + "' data-score_assessment_danger='" + v.score_assessment_danger + "'><span class='badge bg-yellow'>Resiko Sedang</span></a></td>";
					else 
						hasil_assessment = "<td style='text-align:center;'><a href='javascript:void(0)' class='hasil_assessment' data-id_tamu='" + v.id_tamu + "' data-score_assessment='" + v.score_assessment + "' data-score_assessment_danger='" + v.score_assessment_danger + "'><span class='badge bg-green'>Resiko Kecil</span></a></td>";
				}
				//action
				output = "			<div class='input-group-btn'>";
				output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
				output += "				<ul class='dropdown-menu pull-right'>";
				if(v.completed == 'n'){
					if(v.is_assessment == 1 && !v.waktu_datang)
						output += 			"<li><a href='javascript:void(0)' class='konfirmasi_hadir' data-id_tamu='"+v.id_tamu+"'><i class='fa fa-pencil-square-o'></i> Konfirmasi Kehadiran</a></li>";
					else 
						output += 			"<li><a href='javascript:void(0)' class='konfirmasi' data-id_tamu='"+v.id_tamu+"'><i class='fa fa-pencil-square-o'></i> Konfirmasi Kepulangan</a></li>";
				}
				output += "				</ul>";
				output += "	        </div>";
				
				t.row.add( [
					v.caption_tanggal_kunjungan,
					v.nama_tamu,
					v.perusahaan,
					v.caption_waktu_datang,
					v.caption_waktu_pulang,
					// v.nik_tamu,
					// v.telepon,
					v.tujuan_kunjungan,
					v.nama_karyawan,
					v.label_status,
					hasil_assessment,
					output
				] ).draw( false );
			});
		
		}
	});
}

function formatAsetSelection(aset) {
	if (aset.id) {
		$('input[name="nama_karyawan"]').val(aset.text);
		$('input[name="nik_karyawan"]').val(aset.id);
		return aset.id + " - " + aset.text;
	}
	else
		return aset.text;
	// $('input[name="kode"]').val(aset.kode);
}

function formatSearchAset(aset) {
	if (aset.loading) {
		return aset.text;
	}

	var markup = "<div class='select2-result-aset clearfix'>" + aset.id + " - " + aset.text + "</div>";

	return markup;
}

function resetForm_use($form) {
	$form.find('input:text, input:password, input:file,  textarea').val("");
	$form.find('input:text, input:password, input:file,  textarea').prop('disabled', false);
	$form.find('select').val(0);
	$form.find('select').prop('disabled', false);
	$form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
	$form.find('input:radio, input:checkbox').prop('disabled', false);
}
