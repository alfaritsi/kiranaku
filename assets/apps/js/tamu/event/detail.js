$(document).ready(function() {
    get_data_event();
    $("#sspTable").dataTable();

    $(document).on("click", ".btn_upload", function(){
        $("input[name='file_excel']").val('');
        $('#modal_upload').modal('show');
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

    // save upload
    $(document).on("click", "button[name='action_btn_save']", function (e) {
        var empty_form = validate('.form-upload-peserta');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-upload-peserta")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'tamu/event/save/upload/peserta',
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
                                get_data_event();
                                $('#modal_upload').modal('hide');
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function () {
                        swal('Error', 'Server Error', 'error');
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

    //kirim ulang email ke peserta
    $(document).on("click", ".send_email", function(){
		let id_event	= $(this).data("id_event");
        let id_peserta	= $(this).data("id_peserta");
		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			$.ajax({
				url: baseURL+'tamu/event/set/send_email',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_event : id_event,
                    id_peserta : id_peserta
				},
				success: function (data) {
					if (data.sts == 'OK') {
						swal('Success', data.msg, 'success').then(function () {
							get_data_event();
						});
					} else {
						$("input[name='isproses']").val(0);
						swal('Error', data.msg, 'error');
					}
				},
                error: function () {
                    swal('Error', 'Server Error', 'error');
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

    //hapus peserta
    $(document).on("click", ".delete", function(e) {
        const id_peserta = $(this).data("id_peserta");
        const type = $(this).data("action");

        kiranaConfirm({
            title: "Konfirmasi",
            text: "Apakah anda akan menghapus data?",
            dangerMode: true,
            successCallback: function() {
                $.ajax({
                    url: baseURL + "tamu/event/set/peserta",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id_peserta : id_peserta,
                        type : type
                    },
                    success: function(data){
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                get_data_event();
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
});

function get_data_event() {
    const id_event		= $("#id_event").val();
    $.ajax({
        url: baseURL+'tamu/event/get/peserta',
        type: 'POST',
        dataType: 'JSON',
        data: {
            data        : 'header',
            return      : 'json',
            id_event 	: id_event,
        },
        success: function(data){
            var output 	= "";
            var desc	= "";
            var t 	= $('#sspTable').DataTable();
            t.clear().draw();
            $.each(data, function(i,v){
                //status
                let status = "";
                if(v.is_email_sent == 1) status += "<span class='badge bg-green'>Email Sent</span>";
                if(v.has_assessment == 1) status += "<br><span class='badge bg-green'>Has Assessment</span>";
                //action
                output = "			<div class='input-group-btn'>";
                output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                output += "				<ul class='dropdown-menu pull-right'>";
                output += "                 <li><a href='javascript:void(0)' class='send_email' data-id_peserta='"+v.id+"' data-id_event='"+v.id_event+"'><i class='fa fa-send'></i> Kirim Email</a></li>";
                output += "                 <li><a href='javascript:void(0)' class='delete' data-id_peserta='"+v.id+"' data-action='delete'><i class='fa fa-trash'></i> Hapus</a></li>";
                output += "				</ul>";
                output += "	        </div>";
                
                t.row.add( [
                    v.nama,
                    v.email,
                    v.perusahaan,
                    v.telepon,
                    v.nik,
                    status,
                    output
                ] ).draw( false );
            });
        
        }
    });
}
