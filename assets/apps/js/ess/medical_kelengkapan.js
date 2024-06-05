jQuery.validator.setDefaults({
    debug: true
});


$.validator.methods.max = function (value, element, param) {
    return this.optional(element) || parseFloat(value) <= parseFloat(param);
};
$.validator.methods.min = function (value, element, param) {
    return this.optional(element) || parseFloat(value) >= parseFloat(param);
};

$(document).ready(function () {
    let multiplierPembiayaan = 1;

    validator = $('.form-kelengkapan').validate({
        ignore: ':hidden:not(.do-not-ignore)',
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.appendTo(element.parents('.form-group > div').first());
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group > div").first().addClass("has-error").removeClass("has-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group > div").first().addClass("has-success").removeClass("has-error");
        }
    });

    $(document).on('click', '.lengkap', function (e) {
        var id = $(this).attr('data-lengkap');
        $.ajax({
            url: baseURL + 'ess/medical/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                e.preventDefault();
                var modal = $('#modal-kelengkapan');
                if (modal != null) {
                    let pengajuan = data.data;
                    $('input[name="id_fbk"]', modal).val(pengajuan.id_fbk);
                    $('#nik', modal).html(pengajuan.nik);
                    $('#nama_pasien', modal).html(pengajuan.nama_pasien);
                    $('#nama_karyawan', modal).html(pengajuan.nama_karyawan);
                    $('#nomor', modal).html(pengajuan.nomor);
                    $('#jumlah_kwitansi span', modal).html(pengajuan.jumlah_kwitansi);
                    $('#total_kwitansi span', modal).html(pengajuan.total_kwitansi);
                    $('#tr_total_akan_dibayar', modal).removeClass('hide');
                    $('#tr_total_akan_dibayar_karyawan', modal).removeClass('hide');
                    $('#input_dibayar', modal).addClass('hide');
                    AutoNumeric.set('#total_akan_dibayar span.numeric', 0);
                    $('.fbk-jenis-detail', modal).addClass('hide');
                    let div_fbk_detail = null;
                    switch (pengajuan.fbk_jenis) {
                        case "jalan":
                            $('#form-medical', modal).html('Rawat Jalan');
                            div_fbk_detail = $('#fbk-jenis-jalan', modal);
                            div_fbk_detail.removeClass('hide');
                            $('#sakit', div_fbk_detail).html(pengajuan.sakit);
                            $('#plafon span', div_fbk_detail).html(pengajuan.plafon_medical);
                            break;
                        case "inap":
                            $('#form-medical', modal).html('Rawat Inap');
                            div_fbk_detail = $('#fbk-jenis-inap', modal);
                            div_fbk_detail.removeClass('hide');
                            // $('#tr_total_estimasi', modal).removeClass('hide');
                            $('#sakit', div_fbk_detail).html(pengajuan.sakit);
                            $('#rs', div_fbk_detail).html(pengajuan.rs);
                            $('#jumlah_hari span', div_fbk_detail).html(pengajuan.jumlah_hari);
                            $('#plafon_kamar span', div_fbk_detail).html(pengajuan.plafon_kamar);
                            $('#biaya_kamar span', div_fbk_detail).html(pengajuan.biaya_kamar);
                            break;
                        case "bersalin":
                            $('#form-medical', modal).html('Persalinan');
                            div_fbk_detail = $('#fbk-jenis-bersalin', modal);
                            div_fbk_detail.removeClass('hide');
                            let jenis = "Normal";
                            if (pengajuan.kode === 'BBCS')
                                jenis = 'Cesar';
                            $('#jenis_bersalin', div_fbk_detail).html(jenis);
                            $('#plafon_bersalin span', div_fbk_detail).html(pengajuan.plafon_persalinan);
                            $('#biaya_bersalin span', div_fbk_detail).html(pengajuan.biaya_persalinan);
                            break;
                        case "lensa":
                            $('#form-medical', modal).html('Lensa');
                            div_fbk_detail = $('#fbk-jenis-lensa', modal);
                            div_fbk_detail.removeClass('hide');
                            $('#plafon_lensa span', div_fbk_detail).html(pengajuan.plafon_lensa);
                            $('#biaya_lensa span', div_fbk_detail).html(pengajuan.biaya_lensa);
                            break;
                        case "frame":
                            $('#form-medical', modal).html('Frame');
                            div_fbk_detail = $('#fbk-jenis-frame', modal);
                            div_fbk_detail.removeClass('hide');
                            $('#plafon_frame span', div_fbk_detail).html(pengajuan.plafon_frame);
                            $('#biaya_frame span', div_fbk_detail).html(pengajuan.biaya_frame);
                            break;
                    }

                    $('#keterangan', modal).html(KIRANAKU.isNullOrEmpty(pengajuan.keterangan, pengajuan.keterangan, '-'));

                    modal.modal('show');

                    modal.on('shown.bs.modal', function (e) {

                        $('.div-kwitansi table tbody tr:not(.template)', modal).remove();

                        var div_kwitansi = $('.div-kwitansi', modal);

                        div_kwitansi.removeClass('hide');
                        var template = div_kwitansi.find('.template');
                        var table = div_kwitansi.find('table');
                        var total_kwitansi = 0;

                        if (pengajuan.fbk_jenis == 'inap') {
                            var diff = (pengajuan.plafon_kamar / pengajuan.biaya_kamar);
                            if (diff > 1)
                                multiplierPembiayaan = 1;
                            else
                                multiplierPembiayaan = diff;
                        } else
                            multiplierPembiayaan = 1;

                        if(!KIRANAKU.isNullOrEmpty(pengajuan.gambar))
                        {
                            $('#gambar_kwitansi', modal)
                                .parents('.form-group')
                                .removeClass('hide');
                            $('#gambar_kwitansi', modal).find('a').attr('href', pengajuan.gambar);
                            $('#gambar_kwitansi', modal).find('a').attr('data-fancybox', pengajuan.gambar);
                        }else
                            $('#gambar_kwitansi', modal)
                                .parents('.form-group')
                                .addClass('hide');

                        pengajuan.kwitansi.forEach(function (kwitansi, i) {
                            var clone = template.clone();

                            clone.removeClass('template');
                            clone.removeClass('hide');
                            clone.find('input').each(function (j, el) {
                                var name = $(el).attr('name').replace('$', i);
                                $(el).attr('disabled', null);
                                $(el).attr('name', name);
                                $(el).attr('id', name.replace(/[^a-zA-Z ]/g, "") + "_" + i);
                            });

                            clone.find('p').each(function (j, el) {
                                var id = $(el).attr('id').replace('$', i);
                                $(el).attr('id', id);
                            });

                            clone.find('#kwitansi_nomor_detail_' + i).html(kwitansi.nomor);
                            clone.find('input[name="kwitansi[' + i + '][id_fbk_kwitansi]"]').val(kwitansi.id_fbk_kwitansi);
                            clone.find('input[name="kwitansi[' + i + '][disetujui]"]').attr('data-nominal', kwitansi.amount_kwitansi);
                            clone.find('input[name="kwitansi[' + i + '][nomor]"]').val(kwitansi.nomor_kwitansi);
                            clone.find('input[name="kwitansi[' + i + '][amount_ganti]"]').val(0);
                            clone.find('input[name="kwitansi[' + i + '][amount_ganti]"]').attr('numeric-min', 0);
                            clone.find('#kwitansi_tanggal_' + i).html(moment(kwitansi.tanggal_kwitansi).format('DD.MM.YYYY'));
                            clone.find('#kwitansi_nominal_' + i + ' span').html(kwitansi.amount_kwitansi);
                            total_kwitansi += kwitansi.amount_kwitansi;

                            var ext = kwitansi.gambar != null ? kwitansi.gambar.split('.').pop().toLowerCase() : null;

                            var lampiran = "";

                            if (ext != null) {
                                lampiran = "<a data-fancybox='" + kwitansi.gambar + "' alt='kwitansi " + kwitansi.nomor_kwitansi + "' href='" + kwitansi.gambar + "'>" +
                                    "<i class='fa fa-search'></i> " +
                                    "</a>";
                            } else
                                lampiran = '-';

                            clone.find('#kwitansi_lampiran_' + i).html(lampiran);
                            table.find('tbody').append(clone);
                        });

                        $('#input_total_kwitansi', modal).val(total_kwitansi);
                        $('#total_ganti span', modal).html(0);

                        KIRANAKU.convertNumericLabel('#modal-kelengkapan span.numeric');

                        KIRANAKU.convertNumeric('#modal-kelengkapan input.numeric');

                        AutoNumeric.set('#modal-kelengkapan #estimasi span.numeric', 0);
                        AutoNumeric.set('#modal-kelengkapan #total_ganti span.numeric', 0);
                        AutoNumeric.set('#modal-kelengkapan #total_akan_dibayar_karyawan span.numeric', total_kwitansi);

                        modal.off(e);
                    });
                }
            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', '.kwitansi_disetujui', function () {
        var modal = $('#modal-kelengkapan');
        var total = 0;
        var totalEstimasi = 0;
        var totalGanti = 0;
        var totalGantiKaryawan = 0;

        var nilaiGanti = Math.round($(this).attr('data-nominal') * multiplierPembiayaan);

        if ($(this).is(':checked')) {
            $(this).parents('tr').find('.input_disetujui').attr('numeric-max', $(this).attr('data-nominal'));
            $(this).parents('tr').find('.input_disetujui').attr('numeric-min', 1);
            AutoNumeric.set(
                '#modal-kelengkapan input[name="' + $(this).parents('tr').find('.input_disetujui').attr('name') + '"]',
                nilaiGanti
            );
        } else {
            $(this).parents('tr').find('.input_disetujui').attr('numeric-max', 0);
            $(this).parents('tr').find('.input_disetujui').attr('numeric-min', 0);
            AutoNumeric.set(
                '#modal-kelengkapan input[name="' + $(this).parents('tr').find('.input_disetujui').attr('name') + '"]',
                0
            );
        }

        $('.kwitansi_disetujui:checked', modal).each(function (i, el) {
            total += parseInt($(el).attr('data-nominal'));
            totalEstimasi += Math.round($(el).attr('data-nominal') * multiplierPembiayaan);
            totalGanti += AutoNumeric.getNumber('#' + $(el).parents('tr').find('.input_disetujui').attr('id'));
        });
        totalGantiKaryawan = $('#input_total_kwitansi', modal).val() - totalGanti;

        // AutoNumeric.set('#modal-kelengkapan #tr_total_kwitansi span.numeric', total);
        AutoNumeric.set('#modal-kelengkapan #tr_total_estimasi span.numeric', totalEstimasi);
        AutoNumeric.set('#modal-kelengkapan #tr_total_akan_dibayar span.numeric', totalGanti);
        AutoNumeric.set('#modal-kelengkapan #tr_total_akan_dibayar_karyawan span.numeric', totalGantiKaryawan);
        $('#total_ganti', modal).attr('max', totalEstimasi);
        $('#total_ganti', modal).val(totalGanti);
        $('#total_ganti', modal).trigger('change');
        $('.form-kelengkapan', modal).valid();
    });

    /** validasi preventif jika inpit disetujui dikosongkan */
    $(document).on('change', '.input_disetujui', function (el) {
        var modal = $('#modal-kelengkapan');
        var total = $('#input_total_kwitansi', modal).val();
        var totalGanti = 0;
        if (KIRANAKU.isNullOrEmpty($(this).val())) {
            AutoNumeric.set('#modal-kelengkapan input[name="' + $(this).attr('name') + '"]', 0);
        }

        $('.kwitansi_disetujui:checked', modal).each(function (i, el) {
            totalGanti += AutoNumeric.getNumber('#' + $(el).parents('tr').find('.input_disetujui').attr('id'));
        });
        var totalGantiKaryawan = total - totalGanti;

        AutoNumeric.set('#modal-kelengkapan #tr_total_akan_dibayar span.numeric', totalGanti);
        AutoNumeric.set('#modal-kelengkapan #tr_total_akan_dibayar_karyawan span.numeric', totalGantiKaryawan);
        $('#total_ganti', modal).val(totalGanti);
        $('#total_ganti', modal).trigger('change');
        $('.form-kelengkapan', modal).valid();
    });

    $(document).on('click', '.btn-lengkap', function (e) {
        e.preventDefault();
        var form = $('.form-kelengkapan:visible');
        var action = $(this).attr('data-action');
        // form.valid();
        var valid = form.valid();
        if (valid) {
            var isproses = $("input[name='isproses']").val();
            // var isproses = 0;

            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData(form[0]);

                $.ajax({
                    url: baseURL + 'ess/medical/save/' + action,
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('.modal-kelengkapan:visible').modal('hide');
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
});