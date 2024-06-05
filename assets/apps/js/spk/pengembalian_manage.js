$(document).ready(function () {

    $('#modal-pengembalian #id_kategori,#modal-pengembalian #plant').on('change', function () {
        var modal = $('#modal-pengembalian');
        var plant = $('#plant', modal).val();
        var id_kategori = $('#id_kategori', modal).val();
        if (
            !KIRANAKU.isNullOrEmpty(plant) &&
            !KIRANAKU.isNullOrEmpty(id_kategori)
        ) {
            $('#nomor_dokumen', modal).empty().trigger('change');
            $('#id_permintaan', modal).empty().trigger('change');
            $.ajax({
                url: baseURL + 'spk/pengembalian/get/nomordokumen',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    plant: plant,
                    id_kategori: id_kategori
                },
                success: function (data) {
                    if (data.data) {
                        var aNamaSPK = [[]];
                        $.each(data.data, function (i, v) {
                            var selected = false;
                            if ($('#nomor_dokumen', modal).attr('data-selected') == v.id_permintaan)
                                selected = true;
                            aNamaSPK.push({
                                id: v.id_permintaan,
                                text: v.nomor_dokumen,
                                selected: selected
                            });
                        });

                        $('#nomor_dokumen', modal).select2({data: aNamaSPK}).trigger('change');
                    } else {
                        kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                    }
                }
            });
        }
    });

    $('#modal-pengembalian #nomor_dokumen').on('change', function () {
        var modal = $('#modal-pengembalian');
        var plant = $('#plant', modal).val();
        var id_kategori = $('#id_kategori', modal).val();
        var id_permintaan = $('#nomor_dokumen', modal).val();
        if (
            !KIRANAKU.isNullOrEmpty(plant) &&
            !KIRANAKU.isNullOrEmpty(id_kategori) &&
            !KIRANAKU.isNullOrEmpty(id_permintaan)
        ) {
            $('#id_permintaan').empty().trigger('change');
            $.ajax({
                url: baseURL + 'spk/pengembalian/get/namadokumen',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    plant: plant,
                    id_kategori: id_kategori,
                    id_permintaan: id_permintaan
                },
                success: function (data) {
                    if (data.data) {
                        var aNamaSPK = [[]];
                        $.each(data.data, function (i, v) {
                            var selected = false;
                            if ($('#id_permintaan', modal).attr('data-selected') == v.id_permintaan)
                                selected = true;
                            aNamaSPK.push({
                                id: v.id_permintaan,
                                text: v.nama_permintaan_dok,
                                selected: selected
                            });
                        });

                        $('#id_permintaan', modal).select2({data: aNamaSPK}).trigger('change');
                    } else {
                        kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                    }
                }
            });
        }
    });

    $(document).on('click', '.pengembalian-resi-add', function (e) {
        var id_pengembalian = $(this).attr('data-id_pengembalian');
        var modal = $('#modal-resi');
        $('#title', modal).html('Tambah');
        $('#id_pengembalian', modal).val(id_pengembalian);
        modal.modal('show');
    });

    $(document).on('click', '.pengembalian-resi-edit', function (e) {
        var id = $(this).attr('data-id_resi');
        var modal = $('#modal-resi');
        $('#title', modal).html('Edit');
        $.ajax({
            url: baseURL + 'spk/pengembalian/get/resi',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validate('#form-resi', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_pengembalian', modal).val(dataEdit.id_pengembalian);
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
                    url: baseURL + 'spk/pengembalian/save/resi',
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

    $(document).on('click', '.pengembalian-keterangan-add', function (e) {
        var id_pengembalian = $(this).attr('data-id_pengembalian');
        var modal = $('#modal-keterangan');
        $('#title', modal).html('Tambah');
        $('#id_pengembalian', modal).val(id_pengembalian);
        modal.modal('show');
    });

    $(document).on('click', '.pengembalian-keterangan-edit', function (e) {
        var id = $(this).attr('data-id_keterangan');
        var modal = $('#modal-keterangan');
        $('#title', modal).html('Edit');
        $.ajax({
            url: baseURL + 'spk/pengembalian/get/keterangan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validate('#form-keterangan', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_pengembalian', modal).val(dataEdit.id_pengembalian);
                    $('#id_keterangan', modal).val(dataEdit.id_keterangan);
                    $('#keterangan', modal).html(dataEdit.keterangan);
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $('button[name="save_keterangan"]').on('click', function (e) {
        e.preventDefault();
        validate('#form-keterangan', true);
        var form = $('#form-keterangan:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/pengembalian/save/keterangan',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('.modal-keterangan:visible').modal('hide');
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

    $(document).on('click', '.pengembalian-result', function (e) {
        var id = $(this).attr('data-id_pengembalian');
        var modal = $('#modal-result');
        $.ajax({
            url: baseURL + 'spk/pengembalian/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validate('#form-resi', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#nama_pengembalian_dok', modal).html(dataEdit.nama_pengembalian_dok);
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

    $('#add-pengembalian').on('click', function () {
        var modal = $('#modal-pengembalian');
        $('#title', modal).html('Tambah');
        $('#id_kategori', modal).val(null).trigger('change');
        modal.modal('show');
    });

    $(document).on('click', '.pengembalian-edit', function (e) {
        var id = $(this).attr('data-id_pengembalian');
        var modal = $('#modal-pengembalian');
        $('#title', modal).html('Edit');
        $.ajax({
            url: baseURL + 'spk/pengembalian/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validate('#form-pengembalian', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_pengembalian', modal).val(dataEdit.id_pengembalian);
                    $('#id_kategori', modal).val(dataEdit.id_kategori).trigger('change');
                    $('#plant', modal).val(dataEdit.plant).trigger('change');
                    $('#nomor_dokumen', modal).attr('data-selected', dataEdit.id_permintaan);
                    $('#id_permintaan', modal).attr('data-selected', dataEdit.id_permintaan);
                    modal.modal('show');
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    });

    $(document).on('click', '.pengembalian-approve', function () {
        var id = $(this).attr('data-id_pengembalian');
        var modal = $('#modal-approval');
        $('#title', modal).html('Edit');
        $.ajax({
            url: baseURL + 'spk/pengembalian/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validate('#form-approval', true);
                if (data.data) {
                    let dataEdit = data.data;
                    $('#id_pengembalian', modal).val(dataEdit.id_pengembalian);
                    $('#nama_pengembalian_dok', modal).val(dataEdit.nama_pengembalian_dok);
                    $('#nomor_dokumen', modal).html(dataEdit.nomor_dokumen);
                    var keterangan = [];
                    $.each(dataEdit.keterangan, function (i, obj) {
                        keterangan.push(obj.keterangan);
                    });
                    $('#keterangan', modal).html(keterangan.join('<br/>'));
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
        var id = $('#id_pengembalian', modal).val();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);

            $.ajax({
                url: baseURL + 'spk/pengembalian/save/approval',
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

    $(document).on("click", ".pengembalian-kirim", function (e) {
        var id = $(this).attr("data-id_pengembalian");
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk melakukan konfirmasi pengembalian dokumen ini?",
            dangerMode: true,
            successCallback: function () {
                $.ajax({
                    url: baseURL + 'spk/pengembalian/save/kirimdokumen',
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

    $(document).on("click", ".pengembalian-terima", function (e) {
        var id = $(this).attr("data-id_pengembalian");
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Apakah anda yakin untuk melakukan konfirmasi pengembalian dokumen ini?",
            dangerMode: true,
            successCallback: function () {
                $.ajax({
                    url: baseURL + 'spk/pengembalian/save/terimadokumen',
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

    $('button[name="save_pengembalian"]').on('click', function (e) {
        e.preventDefault();
        validate('#form-pengembalian', true);
        var form = $('#form-pengembalian:visible');
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'spk/pengembalian/save/data',
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

    $(document).on("click", ".pengembalian-delete", function (e) {
        var id = $(this).attr("data-id_pengembalian");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'spk/pengembalian/delete/data',
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

    $('#modal-pengembalian').on('hide.bs.modal', function () {
        $('#id_pengembalian', $(this)).val(null);
        $('#plant', $(this)).val(null).trigger('change');
        $('#form-pengembalian')[0].reset();
    });
});