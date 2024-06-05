$(document).ready(function () {
    get_data_spk();

    //lha 
    $(document).on('click', '.reset-form', function () {
        reset_form_spk();
    });

    // //change nama vendor xxxx
    $(document).on("change", "#lifnr", function (e) {
        let perihal = $("input[name='perihal']").val();
        let plant = $("input[name='plant']").val();
        let lifnr = $("#lifnr").val();
        let id_kualifikasi = $("input[name='id_spesifikasi']").val();
        $("select[name='id_kualifikasi']").html('');
        $.ajax({
            url: baseURL + 'spk/spk/get2/kualifikasi_vendor',
            type: 'POST',
            dataType: 'JSON',
            data: {
                plant: plant,
                lifnr: lifnr
            },
            success: function (data) {
                $.each(data, function (i, v) {
                    let list_id_kualifikasi = v.list_id_kualifikasi ? v.list_id_kualifikasi.slice(0, -1).split(",") : '';
                    let list_kualifikasi = v.list_kualifikasi ? v.list_kualifikasi.slice(0, -1).split(",") : '';
                    // let list_id_kualifikasi = v.list_id_kualifikasi.split(",");
                    // let list_kualifikasi = v.list_kualifikasi.slice(0, -1).split(",");
                    let array = [];
                    let output = '';
                    output += '<option value="">Pilih Kualifikasi</option>';
                    if (list_id_kualifikasi && list_kualifikasi)
                        $.each(list_id_kualifikasi, function (x, y) {
                            if (id_kualifikasi == list_id_kualifikasi[x]) {
                                output += '<option value="' + list_id_kualifikasi[x] + '" selected>' + list_kualifikasi[x] + '</option>';
                            } else {
                                output += '<option value="' + list_id_kualifikasi[x] + '">' + list_kualifikasi[x] + '</option>';
                            }

                        });
                    $("select[name='id_kualifikasi']").html(output);
                });
            }
        });
    });

    $(document).on('click', '.spk_drop', function () {
        let modal = $('#modal-spk_drop');
        $("[name='id_spk']", modal).val($(this).attr('data-id_spk'));
        $("[name='jenis_spk']", modal).html($(this).attr('data-jenis_spk'));
        // $("[name='nomor_spk']", modal).val($(this).attr('data-nomor_spk'));
        modal.modal('show');
    });
    $(document).on('click', '.spk_cancel', function () {
        let modal = $('#modal-spk_cancel');
        $('#id_spk', modal).val($(this).attr('data-id_spk'));
        $('#nama_spk', modal).html($(this).attr('data-nama_spk'));
        $('#nomor_spk', modal).val($(this).attr('data-nomor_spk'));
        $('#status_akhir', modal).val($(this).attr('data-status_akhir'));
        modal.modal('show');
    });

    $(document).on("click", ".spk_cancel_old", function (e) {
        let id_spk = $(this).attr('data-id_spk');
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Data Perjanjian dicancel, apakah proses akan dilanjutkan?",
            dangerMode: true,
            successCallback: function () {

                $.ajax({
                    url: baseURL + 'spk/save/cancelspk',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id_spk: id_spk
                    },
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);

                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    complete: function () {
                        location.reload();
                    }
                });
            }
        });
        e.preventDefault();
        return false;
    });

    $('button[name="save_drop_spk"]').on('click', function (e) {
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
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('.modal-cancel_spk:visible').modal('hide');
                                location.reload();
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

    $('button[name="save_cancel_spk"]').on('click', function (e) {
        e.preventDefault();
        validate('#form-cancel-spk', true);
        let form = $('#form-cancel-spk:visible');
        let valid = form.valid();
        if (valid) {
            let isproses = $("input[name='isproses']").val();
            // let isproses = 0; 

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/cancelspk',
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
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    complete: function () {
                        location.reload();
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

    //filter data spk
    $(document).on("change", "#filter_plant, #filter_jenis, #filter_status", function () {
        get_data_spk();
    });

    $(document).on("changeDate", "input[name='filter_tanggal_perjanjian_awal'], input[name='filter_tanggal_perjanjian_akhir'], input[name='filter_tanggal_submit_awal'], input[name='filter_tanggal_submit_akhir']", function () {
        get_data_spk();
    });

    master_vendor($("#lifnr"));

    $("#lifnr").on('select2:select select2:unselecting change', function (e) {
        let nama_vendor = "";
        let LIFNR = "";
        let CITY1 = "";
        let STRAS = "";
        if (typeof e.params !== "undefined" && e.params.data) {
            nama_vendor = e.params.data.NAME1;
            LIFNR = e.params.data.LIFNR;
            CITY1 = e.params.data.CITY1;
            STRAS = e.params.data.STRAS;
        }
        $("input[name='nama_vendor']").val(nama_vendor);
        $("input[name='LIFNR']").val(LIFNR);
        $("input[name='CITY1']").val(CITY1);
        $("input[name='STRAS']").val(STRAS);
    });
    //auto complete sampe sini	

    $('#download-template').on('click', function () {
        let modal = $('#modal-download');
        $('#id_jenis_spk_d', modal).empty().trigger('change');
        $.ajax({
            url: baseURL + 'spk/get/jenisspk',
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                if (data.sts == 'OK') {
                    let jenisSPK = [];
                    $.each(data.data, function (i, v) {
                        jenisSPK.push({
                            id: v.link,
                            text: v.jenis_spk
                        });
                    });

                    $('#id_jenis_spk_d', modal).select2({ data: jenisSPK });

                    $('#btn_download', modal).on('click', function () {
                        let win = window.open($('#id_jenis_spk_d', modal).val(), '_blank');
                        win.focus();
                    });
                    modal.modal('show');
                } else {
                    kiranaAlert(false, data.msg, 'error', 'no');
                }
            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', '.spk-upload,.spk-edit-upload', function () {
        let modal = $('#modal-upload');
        $('#id_spk', modal).val($(this).attr('data-id_spk'));
        $('#id_upload', modal).val($(this).attr('data-id_upload'));
        if ($(this).attr('data-tipe') == 'template') {
            $('#id_oto', modal).val($(this).attr('data-id_oto_jenis'));
        } else {
            $('#id_oto', modal).val($(this).attr('data-id_oto_vendor'));
        }
        $('#tipe', modal).val($(this).attr('data-tipe'));
        modal.modal('show');
    });

    $(document).on('change', '#id_jenis_spk', function () {
        $('#id_nama_spk').empty().trigger('change');
        $("select[name='id_jenis_vendor']").val('').trigger("change.select2");
        $("select[name='LIFNR']").val('').trigger("change");
        $("input[name='LIFNR']").val('');
        $("input[name='CITY1']").val('');
        $("input[name='STRAS']").val('');
        $("select[name='id_kualifikasi']").val('').trigger("change.select2");
        $('#lifnr').val('').trigger('change');
    });

    $(document).on('change', '#id_jenis_vendor', function () {
        $("select[name='id_kualifikasi']").val('').trigger("change.select2");
        $("select[name='LIFNR']").val('').trigger("change");
        $("input[name='LIFNR']").val('');
        $("input[name='CITY1']").val('');
        $("input[name='STRAS']").val('');
        $('#lifnr').val('').trigger('change');
    });


    $('#add-spk').on('click', function () {
        let modal = $('#modal-spk');
        reset_form_spk();
        $('#title', modal).html('Tambah');
        $('#tanggal_perjanjian,#tanggal_berlaku_spk,#tanggal_berakhir_spk').datepicker('setDate', moment().toDate());
        modal.modal('show');
    });

    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        autoclose: true
    });

    $('button[name="save_upload"]').on('click', function (e) {
        e.preventDefault();
        validate('#form-upload', true);
        let form = $('#form-upload:visible');
        let valid = form.valid();
        if (valid) {
            let isproses = $("input[name='isproses']").val();
            // let isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData(form[0]);

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

    $('button[name="save_spk"]').on('click', function (e) {
        e.preventDefault();
        validate('#form-spk', true);
        let form = $('#form-spk:visible');
        let valid = form.valid();
        if (valid) {
            let isproses = $("input[name='isproses']").val();
            // let isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/save/spk',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('.modal-spk:visible').modal('hide');
                                location.reload();
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

    $(document).on('click', '.spk-edit', function (e) {
        let id_spk = $(this).attr('data-id_spk');
        let modal = $('#modal-spk');
        $.ajax({
            url: baseURL + 'spk/get/spk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_spk: id_spk,
                return: 'json'
            },
            success: function (data) {
                // validate('#form-spk', true);
                if (data) {
                    let dataEdit = data;
                    $("input[name='id_spk']", modal).val(dataEdit.id_spk);
                    $("select[name='plant']", modal).val(dataEdit.plant).trigger('change');
                    $("select[name='id_jenis_spk']", modal).val(dataEdit.id_jenis_spk).trigger('change');
                    // $('#id_nama_spk', modal).attr('data-selected', dataEdit.id_nama_spk);
                    // $('#id_nama_spk', modal).val(dataEdit.id_nama_spk).trigger('change');
                    $('#id_jenis_vendor', modal).val(dataEdit.id_jenis_vendor).trigger('change');
                    $('#perihal', modal).val(dataEdit.perihal);
                    $("input[name='tanggal_perjanjian']", modal).datepicker('setDate', moment(dataEdit.tanggal_perjanjian).toDate());
                    $("input[name='tanggal_berlaku_spk']", modal).datepicker('setDate', moment(dataEdit.tanggal_berlaku_spk).toDate());
                    $("input[name='tanggal_berakhir_spk']", modal).datepicker('setDate', moment(dataEdit.tanggal_berakhir_spk).toDate());
                    $("input[name='SPPKP']", modal).val(dataEdit.SPPKP);
                    $('#id_spesifikasi', modal).val(dataEdit.id_kualifikasi);

                    const elemVendor = ("#form-spk #lifnr");
                    let control_vendor = $(elemVendor).empty().data('select2');
                    let adapter_vendor = control_vendor.dataAdapter;
                    let nama_vendor = dataEdit.nama_vendor;
                    // console.log(nama);
                    adapter_vendor.addOptions(adapter_vendor.convertToOptions([{ "id": dataEdit.lifnr, "text": nama_vendor }]));
                    $(elemVendor).trigger('change');

                    // let control_kualifikasi = $('#id_kualifikasi').empty().data('select2');
                    // let adapter_kualifikasi = control_kualifikasi.dataAdapter;
                    // let nama_kualifikasi = dataEdit.nama_kualifikasi + ' (' + dataEdit.plant + ')';
                    // // console.log(nama);
                    // adapter_kualifikasi.addOptions(adapter_kualifikasi.convertToOptions([{ "id": dataEdit.lifnr, "text": nama_vendor }]));
                    // $('#id_kualifikasi').trigger('change');

                    // $('#id_kualifikasi', modal).val(dataEdit.id_kualifikasi).trigger('change');
                    $('#nama_vendor', modal).val(dataEdit.nama_vendor);
                    $('#LIFNR', modal).val(dataEdit.lifnr);
                    $('#CITY1', modal).val(dataEdit.CITY1);
                    $('#STRAS', modal).val(dataEdit.STRAS);

                    $('#title', modal).html("Edit");

                    $("select[name='id_jenis_spk']", modal).prop('disabled', true);
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $(document).on("click", ".spk-delete", function (e) {
        let id = $(this).attr("data-id_spk");
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Apakah anda akan menghapus data?",
            dangerMode: true,
            successCallback: function () {
                $.ajax({
                    url: baseURL + 'spk/delete/spk',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id: id
                    },
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);
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

    $(document).on('click', '.spk-attachments', function () {
        let tipe = $(this).attr('data-tipe');
        let id = $(this).attr('data-id_spk');
        let id_jenis_spk = $(this).attr('data-id_jenis_spk');
        loadAttachments(id, tipe, true);
    });

    $(document).on('click', '.spk-history', function (e) {
        // $("#tb-history tbody").empty();
        $('#tb-history').DataTable().clear();
        $('#tb-history').DataTable().destroy();
        let id_spk = $(this).attr('data-id_spk');
        let modal = $('#modal-history');
        $.ajax({
            url: baseURL + 'spk/get/logspk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_spk: id_spk,
                return: 'json'
            },
            success: function (data) {
                $.fn.dataTable.moment('DD.MM.YYYY HH:mm:ss');
                let t = $('#tb-history').DataTable({
                    order: [
                        [0, 'desc']
                    ],
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"]
                    ],
                    scrollCollapse: true,
                    scrollY: false,
                    scrollX: true,
                    bautoWidth: false,
                    columnDefs: [
                        { "orderable": false, "targets": '_all' },
                    ]
                });
                if (data && Array.isArray(data)) {
                    $.each(data, function (i, v) {
                        t.row.add([
                            v.tgl_status_format + " " + v.jam_status_format,
                            '<span style="text-transform: capitalize">' + v.action + '</span> oleh <br>' + v.nama_role + ' : ' + v.nama + ((v.nama_divisi) ? " (" + v.nama_divisi + ")" : ""),
                            v.comment
                        ]).draw(false);
                    });

                    setTimeout(function () {
                        adjustDatatableWidth();
                    }, 1500);

                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Server error.', 'error', 'no');
                }

            },
            error: function (data) {
                $("input[name='isproses']").val(0);
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', '.div-terkait', function (e) {
        let id_spk = $(this).attr('data-id_spk');
        let modal = $('#modal-divterkait');
        $.ajax({
            url: baseURL + 'spk/get/divterkait',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_spk: id_spk,
                return: 'json'
            },
            success: function (data) {
                if (data && data.length) {
                    let output = "";
                    $.each(data, function (i, v) {
                        output += '<tr>';
                        output += '<td>' + v.nama_divisi + '</td>';
                        output += '<td>' + ((v.tgl_status_format) ? v.tgl_status_format : "-") + '</td>';
                        output += '</tr>';
                    });
                    $("#tb-divterkait tbody").html(output);
                    modal.modal('show');
                } else {
                    $("#tb-divterkait tbody").html('<tr><td colspan="2">No Data</td></tr>');
                }

                modal.modal('show');
            }
        });
    });

    function loadAttachments(id, tipe, showModal = false) {
        if (tipe == 'vendor_dokumen') {
            let caption_tipe = 'vendor';
        } else if (tipe == 'vendor_kualifikasi') {
            let caption_tipe = 'kualifikasi';
        } else {
            let caption_tipe = tipe;
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
                    let modal = $('#modal-attachments');
                    modal.find('#container-attachments').html(data.data);
                    if (showModal) {
                        modal.modal('show');
                    }
                    reinitFancybox();
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    }

    $(document).on('hidden.bs.modal', '#modal-upload', function () {
        let tipe = $('#tipe', $(this)).val();
        let id = $('#id_spk', $(this)).val();
        loadAttachments(id, tipe);
        $('#id_spk', $(this)).val(null);
        $('#id_oto', $(this)).val(null);
        $('#id_upload', $(this)).val(null);
        $('#tipe', $(this)).val(null);
        $('input[name="dokumen"]', $(this)).val(null);
    });

    $('#modal-spk').on('hide.bs.modal', function () {
        $('#id_spk', $(this)).val(null);
        // validator.resetForm();
    });

    $('#modal-final-draft').on('hide.bs.modal', function () {
        $('#id_spk', $(this)).val(null);
        // validator.resetForm();
    });

    $('#modal-komentar').on('hide.bs.modal', function () {
        $('#id_spk', $(this)).val(null);
        // validator.resetForm();
    });

    $('#modal-download').on('hide.bs.modal', function () {
        $('#btn_download').off('click');
    });
});

function get_data_spk() {
    $("#sspTable").DataTable().clear().destroy();

    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        return {
            iStart: oSettings._iDisplayStart,
            iEnd: oSettings.fnDisplayEnd(),
            iLength: oSettings._iDisplayLength,
            iTotal: oSettings.fnRecordsTotal(),
            iFilteredTotal: oSettings.fnRecordsDisplay(),
            iPage: Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            iTotalPages: Math.ceil(
                oSettings.fnRecordsDisplay() / oSettings._iDisplayLength
            )
        };
    };

    $("#sspTable").dataTable({
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
        ],
        ordering: $("#sspTable").data("ordering") ? $("#sspTable").data("ordering") : false,
        scrollY: $("#sspTable").data("scrolly") ? $("#sspTable").data("scrolly") : false,
        scrollX: $("#sspTable").data("scrollx") ? $("#sspTable").data("scrollx") : false,
        bautoWidth: $("#sspTable").data("bautowidth") ? $("#sspTable").data("bautowidth") : false,
        pageLength: $("#sspTable").data("pagelength") ? $("#sspTable").data("pagelength") : 10,
        paging: $("#sspTable").data("paging") ? $("#sspTable").data("paging") : true,
        fixedHeader: $("#sspTable").data("fixedheader") ? $("#sspTable").data("fixedheader") : false,
        order: [
            [2, 'desc']
        ],
        initComplete: function () {
            let api = this.api();
            $("#sspTable_filter input").attr("placeholder", "Press enter to start searching");
            $("#sspTable_filter input").attr("title", "Press enter to start searching");
            $("#sspTable_filter input").off(".DT").on("keypress change", function (evt) {
                if (evt.type == "change") {
                    api.search(this.value).draw();
                }
            });
        },
        oLanguage: {
            sProcessing: "Please wait ..."
        },
        processing: true,
        serverSide: true,
        searching: true,
        // columnDefs: [{ "targets": 3, "type": "date-eu" }],
        ajax: {
            url: baseURL + "spk/spk/get/spk",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                pabrik: $("select[name='filter_plant[]']").val(),
                IN_jenis_spk: $("select[name='filter_jenis[]']").val(),
                tanggal_perjanjian_awal: $("input[name='filter_tanggal_perjanjian_awal']").val(),
                tanggal_perjanjian_akhir: $("input[name='filter_tanggal_perjanjian_akhir']").val(),
                tanggal_submit_awal: $("input[name='filter_tanggal_submit_awal']").val(),
                tanggal_submit_akhir: $("input[name='filter_tanggal_submit_akhir']").val(),
                IN_status: $("select[name='filter_status[]']").val(),
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
                KIRANAKU.alert({
                    text: "Server Error",
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
            complete: function () { }
        },
        columns: [{
            data: "plant",
            name: "plant",
            width: "10%",
            render: function (data, type, row) {
                return row.plant;
            },
            visible: true,
            orderable: false
        },
        {
            data: "jenis_spk",
            name: "jenis_spk",
            // width: "20%",
            visible: true,
            orderable: false
        },
        {
            data: "tanggal_submit",
            name: "tanggal_submit",
            width: "10%",
            render: function (data, type, row) {
                return row.tanggal_submit_format;
            },
            visible: true
        },
        {
            data: "tanggal_perjanjian",
            name: "tanggal_perjanjian",
            width: "10%",
            render: function (data, type, row) {
                return row.tanggal_perjanjian_format;
            },
            visible: true
        },
        {
            data: "perihal",
            name: "perihal",
            visible: true,
            orderable: false,
        },
        {
            data: "nama_vendor",
            name: "nama_vendor",
            width: "20%",
            visible: true,
            orderable: false,
            searchable: false,
            // className: 'text-center'
        },
        {
            data: "status",
            name: "status",
            width: "25%",
            render: function (data, type, row) {
                let status = "";
                switch (row.status) {
                    case 'confirmed':
                        status = '<div class="badge bg-blue">CONFIRMED</div>';
                        status += '<br><small>Menunggu dokumen final draft</small>';
                        break;
                    case 'finaldraft':
                        status = '<div class="badge bg-purple">FINAL DRAFT</div>';
                        break;
                    case 'completed':
                        status = '<div class="badge bg-green">COMPLETED</div>';
                        break;
                    case 'drop':
                        status = '<div class="badge bg-red">DROP</div>';
                        break;
                    case 'cancelled':
                        status = '<div class="badge bg-red">CANCELLED</div>';
                        if (row.status_spk_cancel)
                            status += '<br><small>Dicancel oleh ' + row.status_spk_cancel + '</small>';
                        break;
                    default:
                        let sts = (row.status_spk) ? row.status_spk.slice(0, -1) : "";
                        if (row.paralel === 1)
                            status = '<a href="javascript:void(0)" class="div-terkait" data-id_spk="' + row.id_spk + '"><div class="badge bg-yellow">ON PROGRESS</div></a>';
                        else
                            status = '<div class="badge bg-yellow">ON PROGRESS</div>';
                        status += '<br><small>Sedang diproses oleh ' + sts + '</small>';
                        break;
                }
                return status;
            },
            visible: true,
            orderable: false,
            searchable: false
        },
        {
            data: "files",
            name: "files",
            width: "5%",
            "render": function (data, type, row) {
                if (row.files !== null) {
                    let urlfile = baseURL + 'spk/view_file?file=' + row.files;
                    return '<a href="' + urlfile + '" data-fancybox><span class="badge bg-red-gradient"><i class="fa fa-file-pdf-o"></i></span> </a>';
                }
                return '';
            }
        },
        {
            data: "status_spk",
            name: "status_spk",
            width: "5%",
            render: function (data, type, row) {
                const link_detail = baseURL + "spk/spk/detail/" + row.id_spk;
                const link_create_poho = baseURL + "plantation/transaksi/createpoho/" + row.id;
                const target_link = "_blank";

                output = "<div class='btn-group'>";
                output += " <button type='button' class='btn btn-data btn-default dropdown-toggle' data-toggle='dropdown'>";
                if (row.jumlah_komentar > 0)
                    output += "<span class='badge bg-yellow'>" + row.jumlah_komentar + "</span>";
                output += "<span class='fa fa-caret-down'></span></button>";
                output += " <ul class='dropdown-menu pull-right'>";

                let komentar = (row.jumlah_komentar > 0) ? " (" + row.jumlah_komentar + ")" : "";
                output += "     <li><a href='" + link_detail + "' target='" + target_link + "' class='spk-detail' ><i class='fa fa-search'></i> Detail" + komentar + "</a></li>";
                output += "     <li><a href='javascript:void(0)' class='spk-history' data-id_spk='" + row.id_spk + "'><i class='fa fa-list'></i> History</a></li>";
                if (row.akses == 1 && row.akses_edit == 1 && !['confirmed', 'finaldraft', 'completed'].includes(row.status))
                    output += "     <li><a href='javascript:void(0)' class='spk-edit' data-id_spk='" + row.id_spk + "'><i class='fa fa-pencil'></i> Edit Perjanjian</a></li>";
                if (row.akses_delete == 1)
                    output += "     <li><a href='javascript:void(0)' class='delete' data-id_spk='" + row.id_spk + "'><i class='fa fa-trash'></i> Hapus</a></li>";

                output += " </ul>";
                output += "</div>";
                return output;
            },
            visible: true,
            orderable: false
        }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            let info = this.fnPagingInfo();
            let page = info.iPage;
            let length = info.iLength;
            $("td:eq(0)", row).html();
        }
    });
}

function master_vendor(elem) {
    if ($(elem).hasClass("select2-hidden-accessible"))
        $(elem).select2("destroy");

    $(elem).select2({
        dropdownParent: $('#form-spk'),
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'spk/spk/get2/vendor',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                    plant: $("input[name='plant']").val(),
                    id_jenis_spk: $("select[name='id_jenis_spk']").val(),
                    id_jenis_vendor: $("select[name='id_jenis_vendor']").val()
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
            // let markup = '<div class="clearfix">' + repo.NAME1 + ' (' + repo.EKORG + ')</div>';
            let markup = '<div class="clearfix">' + repo.NAME1 + '</div>';
            return markup;
        },
        templateSelection: function (repo) {
            // if (repo.LIFNR) {
            //     // return repo.NAME1 + ' (' + repo.EKORG + ')';
            //     return repo.NAME1;
            // } else {
            //     return repo.text;
            // }
            let markup = "Silahkan Pilih";
            if (repo.text && repo.id) return repo.text;
            if (repo.LIFNR)
                markup = `${repo.NAME1}`;

            return markup;
        }
    });
}

function reset_form_spk() {
    $("input[name='id_spk']").val('');
    $("input[name='perihal']").val('');
    $("select[name='id_jenis_spk']").val('').trigger("change.select2");
    $("select[name='id_jenis_spk']").prop('disabled', false);
    // $("select[name='id_nama_spk']").val('').trigger("change.select2");
    $("input[name='SPPKP']").val('');
    $("select[name='id_jenis_vendor']").val('').trigger("change.select2");
    // $("select[name='LIFNR']").val('').trigger("change.select2");
    $("select[name='id_kualifikasi']").val('').trigger("change.select2");
    $("input[name='LIFNR']").val('');
    $("input[name='CITY1']").val('');
    $("input[name='STRAS']").val('');
    $('#lifnr').val('').trigger('change');
}