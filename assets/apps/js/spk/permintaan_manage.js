$(document).ready(function () {
    $(document).on('click', '.permintaan-resi-add', function (e) {
        var id_permintaan = $(this).attr('data-id_permintaan');
        var modal = $('#modal-resi');
        $('#title', modal).html('Tambah');
        $('#id_permintaan', modal).val(id_permintaan);
        modal.modal('show');
    });

    $(document).on('click', '.permintaan-resi-edit', function (e) {
        var id = $(this).attr('data-id_resi');
        var modal = $('#modal-resi');
        $('#title', modal).html('Edit');
        $.ajax({
            url: baseURL + 'spk/permintaan/get/resi',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validate('#form-resi', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_permintaan', modal).val(dataEdit.id_permintaan);
                    $('#id_resi', modal).val(dataEdit.id_resi);
                    $('#ekspedisi', modal).val(dataEdit.ekspedisi);
                    $('#no_resi', modal).val(dataEdit.no_resi);
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $('button[name="save_resi"]').on('click', function (e) {
        e.preventDefault();
        validate('#form-resi', true);
        var form = $('#form-resi:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/permintaan/save/resi',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('.modal-resi:visible').modal('hide');
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

    $('#modal-resi').on('hide.bs.modal', function () {
        $('#id_resi', $(this)).val(null);
        $('#form-resi')[0].reset();
    });

    $(document).on('click', '.permintaan-result', function (e) {
        var id = $(this).attr('data-id_permintaan');
        var modal = $('#modal-result');
        $.ajax({
            url: baseURL + 'spk/permintaan/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validate('#form-resi', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#nama_permintaan_dok', modal).html(dataEdit.nama_permintaan_dok);
                    $('#nomor_dokumen', modal).html(dataEdit.nomor_dokumen);
                    $('#tanggal_kirim', modal).html(
                        KIRANAKU.isNullOrEmpty(
                            dataEdit.tanggal_kirim,
                            moment(dataEdit.tanggal_kirim).format('DD.MM.YYYY'),
                            '-'
                        )
                    );
                    $('#tanggal_terima', modal).html(
                        KIRANAKU.isNullOrEmpty(
                            dataEdit.tanggal_terima,
                            moment(dataEdit.tanggal_terima).format('DD.MM.YYYY'),
                            '-'
                        )
                    );
                    $('#status_result', modal).html(dataEdit.status_result);
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $('#add-permintaan').on('click', function () {
        var modal = $('#modal-permintaan');
        $('#title', modal).html('Tambah');
        $('#id_kategori', modal).val(null).trigger('change');
        modal.modal('show');
    });

    $(document).on('click', '.permintaan-edit', function (e) {
        var id = $(this).attr('data-id_permintaan');
        var modal = $('#modal-permintaan');
        $('#title', modal).html('Edit');
        $.ajax({
            url: baseURL + 'spk/permintaan/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validate('#form-permintaan', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_permintaan', modal).val(dataEdit.id_permintaan);
                    $('#id_kategori', modal).val(dataEdit.id_kategori).trigger('change');
                    $('#nama_permintaan_dok', modal).val(dataEdit.nama_permintaan_dok);
                    $('#nomor_dokumen', modal).val(dataEdit.nomor_dokumen);
                    $('#keterangan', modal).html(dataEdit.keterangan);
                    $('#keperluan', modal).html(dataEdit.keperluan);
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $(document).on('click', '.permintaan-approve', function () {
        var id = $(this).attr('data-id_permintaan');
        var modal = $('#modal-approval');
        $('#title', modal).html('Edit');
        $.ajax({
            url: baseURL + 'spk/permintaan/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validate('#form-approval', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_permintaan', modal).val(dataEdit.id_permintaan);
                    $('#nama_permintaan_dok', modal).val(dataEdit.nama_permintaan_dok);
                    $('#nomor_dokumen', modal).val(dataEdit.nomor_dokumen);
                    $('#keterangan', modal).html(dataEdit.keterangan);
                    $('#keperluan', modal).html(dataEdit.keperluan);
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $(document).on('click', 'button.approval', function (e) {
        e.preventDefault();
        var modal = $('#modal-approval');
        var action = $(this, modal).attr('data-action');
        var id = $('#id_permintaan', modal).val();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);

            $.ajax({
                url: baseURL + 'spk/permintaan/save/approval',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id,
                    action: action
                },
                success: function (data) {
                    if (data.sts == 'OK') {
                        swal('Success', data.msg, 'success').then(function () {
                            $('.modal-review:visible').modal('hide');
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
        return false;
    });

    $(document).on("click", ".permintaan-kirim", function (e) {
        var id = $(this).attr("data-id_permintaan");
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk melakukan konfirmasi pengiriman dokumen ini?",
            dangerMode: true,
            successCallback: function () {
                $.ajax({
                    url: baseURL + 'spk/permintaan/save/kirimdokumen',
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

    $(document).on("click", ".permintaan-terima", function (e) {
        var id = $(this).attr("data-id_permintaan");
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk melakukan konfirmasi penerimaan dokumen ini?",
            dangerMode: true,
            successCallback: function () {
                $.ajax({
                    url: baseURL + 'spk/permintaan/save/terimadokumen',
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

    $('button[name="save_permintaan"]').on('click', function (e) {
        e.preventDefault();
        validate('#form-permintaan', true);
        var form = $('#form-permintaan:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/permintaan/save/data',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('.modal-resi:visible').modal('hide');
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

    $(document).on("click", ".permintaan-delete", function (e) {
        var id = $(this).attr("data-id_permintaan");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'spk/permintaan/delete/data',
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
            }
        );
    });

    $('#modal-permintaan').on('hide.bs.modal', function () {
        $('#id_permintaan', $(this)).val(null);
        $('#keperluan,#keterangan', $(this)).html('');
        $('#form-permintaan')[0].reset();
    });
});