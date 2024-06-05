$(document).ready(function () {
    $(document).on('click', '#btn-sync-sap-pabrik-nik', function (e) {

        swal({
            input: 'number',
            inputPlaceholder: 'Ketik NIK',
            showCancelButton: true,
            preConfirm: function (text) {
                if (text) {
                    $('#nik').val(text);
                    $('#btn-sync-sap-pabrik').trigger('click');
                }
            }
        });
    });

    $('#btn-sync-sap-pabrik').on('click', function (e) {
        e.preventDefault();
        var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
        $("body .overlay-wrapper").append(overlay);
        var formData = new FormData($('form[name="filter-cuti-sap"]')[0]);

        $.ajax({
            url: baseURL + 'ess/cutiijin/rfc/cuti_import',
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                $("body .overlay-wrapper").find('.overlay').remove();
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg);
                } else {
                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                }
            },
            error: function (data) {
                $("body .overlay-wrapper").find('.overlay').remove();
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });$(document).on('click', '.cutiijin-batal', function (e) {
        var id = $(this).attr('data-batal');
        let showSaldo = $(this).attr('data-saldo');
        let sisa = 0;
        if (typeof showSaldo === "undefined")
            showSaldo = false;
        var modal = $('#modal-batal-cutiijin');
        $.ajax({
            url: baseURL + 'ess/cutiijin/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $('#id_cuti',modal).val(id);
                let arTanggalCuti = [];
                for (let tgl of data.tanggal) {
                    arTanggalCuti.push(moment(tgl));
                }
                let arTanggalCutiDate = [];
                let jumlah = 0;
                for (let tgl of arTanggalCuti) {

                    arTanggalCutiDate.push(tgl.toDate());
                }

                sisa = parseInt(data.saldo.sisa);

                $('#datepicker-detail', modal).datepicker({
                    multidate: true,
                    daysOfWeekDisabled: [0, 1, 2, 3, 4, 5, 6],
                    startDate: moment.min(arTanggalCuti).toDate(),
                    endDate: moment.max(arTanggalCuti).toDate(),
                    defaultViewDate: moment.min(arTanggalCuti).toDate(),
                    maxViewMode: 0,
                    beforeShowDay: function (date) {
                        let curDate = moment(date).format('YYYY-MM-DD');
                        let libur = data.tanggal_libur.indexOf(curDate) >= 0;
                        let active = arTanggalCuti.indexOf(curDate) >= 0;
                        return {
                            enabled: !libur,
                            classes: libur && !active ? 'weekend' : ''
                        };
                    }
                });
                $('#inik', modal).html(data.detail.nik);
                $('#inama_karyawan', modal).html(data.detail.nama_karyawan);
                if (data.detail.form == "Cuti") {
                    $('#row-ijenis', modal).addClass('hide');
                    $('.modal-title span', modal).html(data.detail.form);
                }
                else {
                    $('#row-ijenis', modal).removeClass('hide');
                    $('#inama_jenis', modal).html(data.detail.nama_jenis);
                    $('.modal-title span', modal).html(data.detail.form);
                }
                $('#ijumlah', modal).html(data.detail.jumlah + ' Hari');
                jumlah = parseInt(data.detail.jumlah);
                if (data.saldo.sisa <= 0)
                    sisa = Math.abs(data.saldo.negatif - data.saldo.sisa);
                else
                    sisa = data.saldo.sisa;
                $('#ialasan', modal).html(data.detail.alasan);
                $('#icatatan', modal).html(KIRANAKU.isNullOrEmpty(data.detail.catatan, data.detail.catatan, '-'));

                if (KIRANAKU.isNullOrEmpty(data.detail.gambar, true, false)) {
                    $('#div-detail-lampiran', modal).removeClass('hide');
                    $('#ilampiran a', modal).attr('href', data.detail.gambar);

                } else {
                    $('#div-detail-lampiran', modal).addClass('hide');
                }

                if (showSaldo) {
                    $('#row-isaldo', modal).removeClass('hide');
                    $('#isaldo', modal).html((sisa + jumlah) + " Hari");
                } else {
                    $('#row-isaldo', modal).addClass('hide');
                }
                $('#datepicker-detail', modal).datepicker('setDates', arTanggalCutiDate);
                modal.modal('show');

            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on("click", "button[name='btn-pembatalan']", function (e) {
        e.preventDefault();
        var form = $('form[name="form-batal-cutiijin"]');
        validate('form[name="form-batal-cutiijin"]', true);
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'ess/cutiijin/save/batal',
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

    /** On click lampiran cutiijin pembatalan **/
    $(document).on('click', '.bak-lampiran-batal', function (e) {
        var id = $(this).attr('data-lampiran');
        $.ajax({
            url: baseURL + 'ess/cutiijin/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                var lampiran = [];

                var ext = data.detail.gambar_bukti != null ? data.detail.gambar_bukti.split('.').pop().toLowerCase() : null;

                if (ext != null)
                    lampiran.push({
                        src: data.detail.gambar_bukti
                    });

                $.fancybox.open(lampiran);
            }
        });
    });

    /** On click lampiran cutiijin surat **/
    $(document).on('click', '.cuti-lampiran-surat', function (e) {
        var id = $(this).attr('data-lampiran');
        $.ajax({
            url: baseURL + 'ess/cutiijin/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                var lampiran = [];

                var ext = data.detail.gambar != null ? data.detail.gambar.split('.').pop().toLowerCase() : null;

                if (ext != null)
                    lampiran.push({
                        src: data.detail.gambar
                    });

                $.fancybox.open(lampiran);
            }
        });
    });
});