$(document).ready(function() {
    // get_data_event();
    $("#sspTable").dataTable();

    //=======FILTER=======//
	$(document).on("change", "#filter_from,#filter_to,#filter_status", function(){
		get_data_event();
	});

    $(document).on("click", ".add_event", function(){
		$("#id_event").val("");
        $("input[name='nama']").val('');
        $("input[name='perusahaan']").val('');
        $("input[name='nama_karyawan']").val('');
        $('#modal_event').modal('show');
    });

    $(document).on("click", ".edit", function(){
		var id_tamu		= $(this).data("id_tamu");
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

    $(document).on("click", ".delete", function(e) {
        const id_event = $(this).data("id_event");
        const type = $(this).data("action");

        kiranaConfirm({
            title: "Konfirmasi",
            text: "Apakah anda akan menghapus data?",
            dangerMode: true,
            successCallback: function() {
                $.ajax({
                    url: baseURL + "tamu/event/set/event",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id_event : id_event,
                        type : type
                    },
                    success: function(data){
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    }
                });
                e.preventDefault();
                return false;
            }
        });
    });

    // save konfirmasi
    $(document).on("click", "button[name='action_btn_save']", function (e) {
        var empty_form = validate('.form-master-event');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-master-event")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'tamu/event/save/event',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
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

    $('#set_nik').select2({
		dropdownParent: $('.form-master-event'),
		ajax: {
			delay: 250,
			url: baseURL + 'tamu/event/get/karyawan',
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

    $('#jam_mulai, #jam_selesai').datetimepicker({
        sideBySide: true,
        keepOpen: true,
        format: 'HH:mm'
    });

    $('.datePicker').datepicker({
    	format: 'dd.mm.yyyy', 
        changeMonth: true,
        changeYear: true,
        autoclose: true
    });
});

function get_data_event() {
    const filter_from		= $("#filter_from").val();
    const filter_to		= $("#filter_to").val();
    $.ajax({
        url: baseURL+'tamu/event/get/event',
        type: 'POST',
        dataType: 'JSON',
        data: {
            data            : 'header',
            return          : 'json',
            // filter_from 	: filter_from,
            // filter_to 		: filter_to
        },
        success: function(data){
            var output 	= "";
            var desc	= "";
            var t 	= $('#sspTable').DataTable();
            t.clear().draw();
            $.each(data, function(i,v){
                //action
                output = "			<div class='input-group-btn'>";
                output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                output += "				<ul class='dropdown-menu pull-right'>";
                output += 			"<li><a href='" + baseURL + "/tamu/event/detail/" + v.id + "' class='konfirmasi' data-id_event='"+v.id+"'><i class='fa fa-search'></i> Data Peserta</a></li>";
                // output += 			"<li><a href='javascript:void(0)' class='konfirmasi' data-id_event='"+v.id+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
                output += 			"<li><a href='javascript:void(0)' class='delete' data-id_event='"+v.id+"' data-action='delete'><i class='fa fa-trash'></i> Hapus</a></li>";
                output += "				</ul>";
                output += "	        </div>";
                
                t.row.add( [
                    v.tanggal_format,
                    v.nama_event,
                    v.waktu_mulai_format,
                    v.waktu_selesai_format,
                    v.nama_pic,
                    output
                ] ).draw( false );
            });
        
        }
    });
}

function formatAsetSelection(aset) {
	if (aset.id) {
		$('input[name="nama_karyawan"]').val(aset.text);
		$('input[name="nik_pic"]').val(aset.id);
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
