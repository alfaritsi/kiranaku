$(document).ready(function () {
    $('#filter-date input, select', 'form[name="filter-bak-sap"]').on('change', function () {
        $('form[name="filter-bak-sap"]').submit();
    });

    $(document).on('click', '[name="reset_btn"]', function (e) {
        e.preventDefault();
        var form = $('form[name="form-batal-bak"]:visible');
        form[0].reset();
    });

    $(document).on('click', '#btn-sap-bak-nik', function (e) {
        // swal({
        //     title: 'Sinkronisasi BAK by NIK',
        //     html: '<div class="text-center"><input id="swal-nik" class="swal2-input" placeholder="ketik NIK">' +
        //     // '<p class="text-warning">* Pilih tanggal jika ingin menentukan tanggal sinkronisasi</p>' +
        //     // '<div id="swal-tanggal"></div>',
        //     '</div>',
        //     onOpen: function () {
        //         // $('#swal-tanggal').datepicker({
        //         //     format: 'dd.mm.yyyy',
        //         //     todayHighlight: true
        //         // });
        //         // $('#swal-tanggal').on('changeDate', function () {
        //         //     $('#tanggal').val(
        //         //         $('#swal-tanggal').datepicker('getFormattedDate')
        //         //     );
        //         // });
        //     },
        //     focusConfirm: false,
        //     preConfirm: () => {
        //         if ($('#swal-nik').val()) {
        //             $('#nik').val($('#swal-nik').val());
        //             $('.btn-sap-bak').trigger('click');
        //         }
        //         // return [
        //         //     document.getElementById('nik').value,
        //         //     document.getElementById('tanggal-sync').value
        //         // ]
        //     }
        // });
        swal({
            input: 'number',
            inputPlaceholder: 'Ketik NIK',
            showCancelButton: true,
            preConfirm: function (text) {
                if (text) {
                    $('#nik').val(text);
                    sync_data();
                }
            }
        });
    });

    $(document).on('click', '.bak-batal', function (e) {
        var id = $(this).attr('data-batal');
        var modal = $('#modal-batal-bak');
        $.ajax({
            url: baseURL + 'ess/bak/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $('#id_bak', modal).val(data.data.enId);
                $('#nik', modal).html(data.data.nik);
                $('#nama_karyawan', modal).html(data.data.nama_karyawan);
                $('#tanggal_absen', modal).html(
                    moment(data.data.tanggal_absen).format('DD.MM.YYYY')
                );
                $('#absen_masuk', modal).html(data.data.absen_masuk);
                $('#absen_keluar', modal).html(data.data.absen_keluar);
                $('#tanggal_input', modal).html(
                    KIRANAKU.isNullOrEmpty(
                        data.data.tanggal_buat,
                        moment(data.data.tanggal_buat).format('DD.MM.YYYY'),
                        moment(data.data.tanggal_migrasi).format('DD.MM.YYYY')
                    )
                );
                $('#alasan', modal).html(data.data.alasan);
                $('#keterangan', modal).html(data.data.keterangan);

                if (data.data.login_buat != null) {
                    $('#tanggal_input_div,#alasan_div,#keterangan_div', modal).removeClass('hide');
                } else {
                    $('#tanggal_input_div,#alasan_div,#keterangan_div', modal).addClass('hide');
                }


                if (KIRANAKU.isNullOrEmpty(data.data.catatan, true, false)) {
                    $('#catatan_div', modal).removeClass('hide');
                    $('#catatan', modal).html(data.data.catatan);
                } else {
                    $('#catatan_div', modal).addClass('hide');
                }

                modal.modal('show');

            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', '.btn-sap-bak', function (e) {
        e.preventDefault();
        $('#nik').val(null);
        sync_data();
    });

    function sync_data() {
        var formData = new FormData($('form[name="bak-sap"]')[0]);
        $.ajax({
            url: baseURL + 'ess/bak/rfc/sync',
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                // if (data.sts == 'OK') {
                //     kiranaAlert(data.sts, data.msg);
                // } else {
                //     kiranaAlert(data.sts, data.msg, 'error', 'no');
                // }

                if (data.sts == 'OK') {
                    if ($('#nik').val() === '')
                        kiranaAlert(data.sts, data.msg);
                    else
                        $.ajax({
                            url: baseURL + 'ess/scheduler/bak_time_event',
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
                                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                                }
                            },
                            error: function (data) {
                                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                            }
                        });
                } else {
                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                }
            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    }

    $(document).on("click", "button[name='btn-pembatalan']", function (e) {
        e.preventDefault();
        var form = $('form[name="form-batal-bak"]');
        validate('form[name="form-batal-bak"]', true);
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'ess/bak/save/batal',
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
                        swal('Error', 'Server error. Mohon ulangi proses.', 'error');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    type: 'info'
                });
            }
        }

        return false;
    });


    /** On click lampiran bak pembatalan **/
    $(document).on('click', '.bak-lampiran-batal', function (e) {
        var id = $(this).attr('data-lampiran');
        $.ajax({
            url: baseURL + 'ess/bak/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                var lampiran = [];

                var ext = data.data.gambar_bukti != null ? data.data.gambar_bukti.split('.').pop().toLowerCase() : null;

                if (ext != null)
                    lampiran.push({
                        src: data.data.gambar_bukti
                    });

                $.fancybox.open(lampiran);
            }
        });
    });
});