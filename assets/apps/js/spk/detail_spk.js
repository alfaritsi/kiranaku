let id_spk;

$(document).ready(function () {
    id_spk = $("#id_spk").val();

    loadAttachments(id_spk, 'template');
    if ($("#id_kualifikasi").val()) {
        loadAttachments(id_spk, 'vendor_dokumen');
        loadAttachments(id_spk, 'vendor_kualifikasi');
    } else {
        loadAttachments(id_spk, 'vendor');
    }

    $(document).on('click', '.spk-upload,.spk-edit-upload', function () {
        var modal = $('#modal-upload');
        $("input[name='id_spk']", modal).val($(this).attr('data-id_spk'));
        $("input[name='dokumen[]']", modal).val("");
        $("input[name='id_upload']", modal).val($(this).attr('data-id_upload'));
        if ($(this).attr('data-tipe') == 'template') {
            $("input[name='id_oto']", modal).val($(this).attr('data-id_oto_jenis'));
        } else {
            $("input[name='id_oto']", modal).val($(this).attr('data-id_oto_vendor'));
        }
        $("input[name='tipe']", modal).val($(this).attr('data-tipe'));
        modal.modal('show');
    });

    $('button[name="save_upload"]').on('click', function (e) {
        e.preventDefault();
        validate('#form-upload', true);
        var form = $('#form-upload:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/dokumen',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $("input[name='isproses']").val(0);
                                $('#modal-upload').modal('hide');
                                loadAttachments(id_spk, 'template');
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $('button[name="btn_komentar"]').on('click', function (e) {
        e.preventDefault();
        validate('#form-komentar', true);
        var form = $('#form-komentar:visible');
        var valid = form.valid();
        if (valid && $('#komentar').val().length > 0) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/komentar',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $("input[name='isproses']").val(0);
                        if (data.sts == 'OK') {
                            refreshChats(data.data);
                        } else {
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        show_modal_action($(this));
    });

    $(document).on("click", "#save-form-action-spk", function (e) {
        const empty_form = validate('#form-action-spk');

        if (empty_form == 0) {
            let isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData($("#form-action-spk")[0]);

                $.ajax({
                    url: baseURL + 'spk/save/approval',
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
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', '.spk-final-draft', function() {
        const modal = $('#modal-final-draft');
        $('#id_spk', modal).val($(this).attr('data-id_spk'));
        $('#jenis_spk', modal).html($(this).attr('data-jenis_spk'));
        $('#nomor_spk', modal).val($(this).attr('data-nomor_spk'));
        modal.modal('show');
    });

    $('button[name="save_final_draft"]').on('click', function(e) {
        e.preventDefault();
        validate('#form-final-draft', true);
        var form = $('#form-final-draft:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);
                $.ajax({
                    url: baseURL + 'spk/save/finaldraft',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                $('.modal-final-draft:visible').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $(document).on('click', '.spk-final-spk', function(e) {
        var id = $(this).attr('data-id_spk');
        var modal = $('#modal-final');
        $.ajax({
            url: baseURL + 'spk/get/spk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_spk: id,
                data: 'complete',
                return: 'json'
            },
            success: function(data) {
                validate('#form-final', true);
                if (data) {
                    let dataEdit = data;
                    $('#id_spk', modal).val(dataEdit.id_spk);
                    $('#plant', modal).html(dataEdit.plant);
                    $('#jenis_spk', modal).html(dataEdit.jenis_spk);
                    // $('#nama_spk', modal).html(dataEdit.nama_spk);
                    $('#jenis_vendor', modal).html(dataEdit.jenis_vendor);
                    $('#tanggal_perjanjian', modal).html(moment(dataEdit.tanggal_perjanjian).format('DD.MM.YYYY'));
                    $('#tanggal_berlaku_spk', modal).html(moment(dataEdit.tanggal_berlaku_spk).format('DD.MM.YYYY'));
                    $('#tanggal_berakhir_spk', modal).html(moment(dataEdit.tanggal_berakhir_spk).format('DD.MM.YYYY'));
                    $('#SPPKP', modal).html(dataEdit.SPPKP);
                    $('#nomor_spk', modal).html(dataEdit.nomor_spk);
                    $('#tanggal_kirim', modal).datepicker('setDate', moment().toDate());
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $('button[name="save_final"]').on('click', function(e) {
        e.preventDefault();
        validate('#form-final', true);
        var form = $('#form-final:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/final',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                $('.modal-final:visible').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $(document).on('click', '.spk_drop', function() {
        let modal = $('#modal-spk_drop');
        $("[name='id_spk']", modal).val($(this).attr('data-id_spk'));
        $("[name='jenis_spk']", modal).html($(this).attr('data-jenis_spk'));
        // $("[name='nomor_spk']", modal).val($(this).attr('data-nomor_spk'));
        modal.modal('show');
    });

    $('button[name="save_drop_spk"]').on('click', function(e) {
        e.preventDefault();
        validate('#form-drop-spk', true);
        let form = $('#form-drop-spk:visible');
        let valid = form.valid();
        if (valid) {
            let isproses = $("input[name='isproses']").val();
            // let isproses = 0; 

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData(form[0]);

                $.ajax({
                    // url: baseURL + 'spk/save/finaldraft',
                    url: baseURL + 'spk/save/dropspk',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                $('.modal-cancel_spk:visible').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            kiranaAlert(false, data.msg, 'error', 'no');
                        }
                    },
                    error: function(data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        return false;
    });

    $(document).on("click", ".spk_delete", function (e) {
        let id = $(this).attr("data-id_spk");
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Apakah anda akan menghapus data?",
            dangerMode: true,
            successCallback: function () {
                $.ajax({
                    url: baseURL + 'spk/set/delete',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id_spk: id
                    },
                    success: function (data) {
                        if (data.sts == 'OK') {
                            reload = baseURL + "spk/manage";
                            kiranaAlert(data.sts, data.msg, 'success', reload);
                        } else {
                            kiranaAlert(data.sts, data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            }
        });
    });
});

function loadAttachments(id, tipe, showModal = false) {
    let caption_tipe = tipe;
    if (tipe == 'vendor_dokumen') {
        caption_tipe = 'vendor';
    } else if (tipe == 'vendor_kualifikasi') {
        caption_tipe = 'kualifikasi';
    }

    $('#modal-attachments').find('#title').html(caption_tipe);
    // $('#modal-attachments').find('#container-attachments').html(null)
    $.ajax({
        url: baseURL + 'spk/get/attachments',
        type: 'POST',
        dataType: 'JSON',
        data: {
            id: id,
            tipe: tipe
        },
        success: function (data) {
            if (data.data) {
                let list = data.data;
                let output = '';
                // $.each(list, function (i, v) {
                // 	if(v.uploadStatus){
                // 		var ck_upload =  '<i class="fa fa-check text-green"></i>';
                // 	}else{
                // 		var ck_upload =  '<i class="fa fa-times text-red"></i>';

                // 	}
                // 	output += '<tr>';
                // 	output += '		<td>'+ck_upload+' '+v.nama_doc+'</td>';
                // 	output += "		<td width='10%'>";
                // 	output += "			<div class='input-group-btn'>";
                // 	output += "				<button type='button' class='btn btn-default btn-xs dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>";
                // 	output += "				<ul class='dropdown-menu pull-right'>";
                // 	output += "                 "+v.links;
                // 	output += "				</ul>";
                // 	output += "	        </div>";
                // 	output += "		</td>";
                // 	output += '</tr>';
                // });
                // $("#view_dokumen_"+tipe).html(output);
                $("#tb-dokumen-" + caption_tipe).html(data.data);
                reinitFancybox();
            } else {
                kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
            }
        }
    });
}

function show_komentar() {
    // var modal = $('#modal-komentar');
    // $('#form-komentar', "input[name='id_spk']").val(id_spk);
    $.ajax({
        url: baseURL + 'spk/get/komentar',
        type: 'POST',
        dataType: 'JSON',
        data: {
            id: $("#id_spk").val(),
            jumlah_komentar: $("#jumlah_komentar").val(),
        },
        success: function (data) {
            if (data.sts == 'OK') {
                refreshChats(data.data);
                // $('#title-spk').html(data.spk.jenis_spk + ", " + data.spk.nama_spk);
                $("#view_jumlah_komentar").html('');
                // modal.modal('show');
            } else {
                kiranaAlert(false, data.msg, 'error', 'no');
            }
        },
        error: function (data) {
            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
        }
    });
}

const refreshChats = (data) => {
    $('.chats').remove();
    // console.log(data);
    $.each(data, function (i, v) {
        // var komentar = (v.komentar).replace(' ', '<br>');
        // var komentar = v.komentar;
        // komentar	 = komentar.str.replace('/', '<br />');


        let template = $('.template-left-chat').clone().removeClass('template-left-chat hide').addClass('chats');
        if (v.me)
            template = $('.template-right-chat').clone().removeClass('template-right-chat hide').addClass('chats');

        $(template).find('.direct-chat-img').attr('src', v.gambar);
        $(template).find('.direct-chat-name').html(v.nama);
        $(template).find('.direct-chat-text').html(v.komentar);
        $(template).find('.direct-chat-timestamp').html(moment(v.tanggal_buat + " " + v.jam).format('DD.MM.YYYY HH:mm'));
        $('#chat-body').append(template).scrollTop($("#chat-body")[0].scrollHeight);

    });
    // $('input[name="komentar"]').val('');
    $('textarea[name="komentar"]').val('');
}

function show_modal_action(elem) {
    console.log(elem.val());
    const modal = $('#modal-action');
    const action = elem.val();

    $("#modal-action .modal-title").html(action.toUpperCase() + " Perjanjian");
    $("#modal-action input[name='id_spk']").val(id_spk);
    $("#modal-action input[name='action']").val(action);
    $("#modal-action [name='note_spk']").prop("required", false);

    if (["decline", "cancel"].includes(elem.val()))
        $("#modal-action [name='note_spk']").prop("required", true);

    modal.modal("show");
}