$(document).ready(function () {
    $(document).on('click', '#head_pengajuan', function (e) {
        if (!$(this).hasClass('whitesmoke')) {

            if ($("#head_uang_muka").hasClass('whitesmoke')) {
                $("#head_uang_muka").removeClass('whitesmoke');
                $("#tab_uang_muka").addClass('hidden');
            }
            if ($("#head_deklarasi").hasClass('whitesmoke')) {
                $("#head_deklarasi").removeClass('whitesmoke');
                $("#tab_deklarasi").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_pengajuan").removeClass('hidden');
        }
    });
    $(document).on('click', '#head_deklarasi', function (e) {
        if (!$(this).hasClass('whitesmoke')) {

            if ($("#head_uang_muka").hasClass('whitesmoke')) {
                $("#head_uang_muka").removeClass('whitesmoke');
                $("#tab_uang_muka").addClass('hidden');
            }
            if ($("#head_pengajuan").hasClass('whitesmoke')) {
                $("#head_pengajuan").removeClass('whitesmoke');
                $("#tab_pengajuan").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_deklarasi").removeClass('hidden');
        }
    });
    $(document).on('click', '#head_uang_muka', function (e) {
        if (!$(this).hasClass('whitesmoke')) {

            if ($("#head_pengajuan").hasClass('whitesmoke')) {
                $("#head_pengajuan").removeClass('whitesmoke');
                $("#tab_pengajuan").addClass('hidden');
            }
            if ($("#head_deklarasi").hasClass('whitesmoke')) {
                $("#head_deklarasi").removeClass('whitesmoke');
                $("#tab_deklarasi").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_uang_muka").removeClass('hidden');
        }
    });
    $(document).on('click', 'button[name="batal_btn"]', function (e) {
        window.opener = self;
        window.close();
    });
    //aaa	
    'use strict';
    const modalPersetujuan = $('#modal-spd-persetujuan');
    /** Detail spd related */
    const approvalMultiTripTable = $('#table-multi-trip').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const approvalUangmukaTable = $('#table-uangmuka').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const approvalBiayaTable = $('#table-biaya').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    // KIRANAKU.createValidator($('.form-persetujuan'));

    $('#filter-date input', 'form[name="filter-history"]').on('change', function () {
        $('form[name="filter-history"]').attr('action', baseURL + 'travel/spd/persetujuan#tab-history');
        $('form[name="filter-history"]').submit();
    });

    $(document).on('hide.bs.modal', '#modal-spd-persetujuan', function () {
        $('.numeric-label').each(function (i, el) {
            AutoNumeric.set(el, 0);
            $(el).attr('value', 0);
        });

        approvalUangmukaTable
            .clear()
            .draw();

        approvalMultiTripTable
            .clear()
            .draw();
    });

    /** Link detail spd clicked */
    const modal = $('#modal-spd-persetujuan');
    const idHeader = $("#id_travel_header").val();
    const isApprovalBy = $("#is_approval_by").val();
    const approvalByDiv = $('#approval_lampiran_div');
    KIRANAKU.showLoading();
    $.ajax({
        url: baseURL + 'travel/spd/get/pengajuan',
        type: 'POST',
        dataType: 'JSON',
        data: {
            id: idHeader
        },
        success: function (data) {
            if (data.sts === 'OK') {
                const { pengajuan, details, personel, downpayments, cancel, deklarasi, deklarasi_details } = data.data;
                const tipe_trip = pengajuan.tipe_trip;

                $('input[name="id_travel_header"]').val(pengajuan.id_travel_header);
                $('#label_activity').html(pengajuan.activity_label);
                $('#label_tipe_trip').html('Pulang Pergi');

                /** Approval mewakili by superuser */
                $('input[name="is_approval_by"]').val(isApprovalBy);
                if (isApprovalBy) {
                    approvalByDiv.removeClass('hide');
                    $('input', approvalByDiv).prop('required', true);
                } else {
                    approvalByDiv.addClass('hide');
                    $('input', approvalByDiv).prop('required', false);
                }

                if (tipe_trip === 'single') {
                    $('#div-single-trip').removeClass('hide');
                    $('#div-multi-trip').addClass('hide');
                    $('#label_keperluan').html(pengajuan.keperluan);
                    $('#label_tujuan').html(pengajuan.tujuan_lengkap);
                    $('#label_single_start').html(pengajuan.tanggal_berangkat);
                    $('#label_single_end').html(pengajuan.tanggal_kembali);
                } else {
                    $('#div-multi-trip').removeClass('hide');
                    $('#div-single-trip').addClass('hide');
                    let template = $('#detail_multitrip_template').html();
                    $.each(details, function (i, trip) {
                        let newTrip = $(template).clone();
                        $('.label_multi_no', newTrip).html(trip.no_urut);
                        $('.label_multi_tujuan', newTrip).html(trip.tujuan_lengkap);
                        $('.label_multi_start', newTrip).html(trip.tanggal_berangkat);
                        $('.label_multi_keperluan', newTrip).html(trip.keperluan);
                        approvalMultiTripTable
                            .row
                            .add($(newTrip))
                            .draw();
                    });
                    $('#label_multi_end').html(pengajuan.tanggal_kembali);
                }

                $('#label_transportasi').html('');
                $.each(pengajuan.transportasi_label, function (i, tr) {
                    $('#label_transportasi').append(
                        '<li>' + tr + '</li>'
                    );
                });
                $('#label_booking_brgkt')
                    .prop('checked', pengajuan.booking_brgkt)
                    .trigger('change');
                $('#label_booking_kembali')
                    .prop('checked', pengajuan.booking_kembali)
                    .trigger('change');
                $('#label_booking_kembali, #label_booking_brgkt')
                    .prop('disabled', true)
                    .trigger('change');
                $('#label_jenis_penginapan').html(pengajuan.jenis_penginapan);
                /** Detail personel */
                $('#label_no_hp').html(pengajuan.no_hp);
                $('#label_p_nik').html(personel.nik);
                $('#label_p_nama').html(personel.nama);
                let kantor = '';
                let bagian = '';
                if (personel.ho === 'y') {
                    kantor = 'Head Office';
                    if (personel.nama_departemen == null) {
                        bagian = personel.nama_divisi;
                    } else {
                        bagian = personel.nama_departemen;
                    }
                } else {
                    kantor = personel.nama_pabrik;
                    if ((personel.nama_seksi == null) && (personel.nama_sub_divisi == null)) {
                        bagian = personel.nama_pabrik;
                    } else if (personel.nama_seksi == null) {
                        bagian = personel.nama_departemen;
                    } else {
                        bagian = personel.nama_seksi;
                    }
                }
                $('#label_p_kantor').html(kantor);
                $('#label_p_bagian').html(bagian);
                $('#label_p_jabatan').html(personel.posst);

                $('input[name="nik_label"]').val(personel.nik);
                $('input[name="nama_label"]').val('aaa');
                $('input[name="kantor_label"]').val(kantor);
                $('input[name="jabatan_label"]').val(personel.posst);
                $('input[name="bagian_label"]').val('aaa');
                $('input[name="no_hp_label"]').val(personel.no_hp);

                /** Uang muka */
                $('.label_total_um_jumlah').html(parseFloat(pengajuan.total_um));
                if (downpayments.length) {
                    $('#div-uangmuka').removeClass('hide');
                    $('#div-um-kembali').removeClass('hide');

                    let firstCurrency = '';

                    approvalUangmukaTable
                        .clear()
                        .draw();
                    let template = $('#uangmuka_template').html();
                    $.each(downpayments, function (i, dp) {
                        let expense = $(template).clone();
                        const rateV = parseFloat(dp.value) * 100;

                        $('.uangmuka-label-expense', expense).html(dp.tipe_expense_text);
                        $('.uangmuka-label-rate', expense).html(rateV);
                        $('.uangmuka-label-durasi', expense).html(dp.durasi);
                        $('.uangmuka-label-jumlah', expense).html(parseFloat(dp.jumlah));
                        $('.uangmuka-label-currency', expense).html(dp.currency);

                        /** Set first currency */
                        if (KIRANAKU.isNullOrEmpty(firstCurrency)) {
                            firstCurrency = dp.currency;
                        }

                        approvalUangmukaTable
                            .row
                            .add($(expense))
                            .draw();
                    });
                    $('.label_total_um_currency').html(firstCurrency);
                } else {
                    $('#div-uangmuka').addClass('hide');
                    $('#div-um-kembali').addClass('hide');
                }
                /** reset button tolak */
                $('button[data-action="disapprove"]').removeClass('hide');
                /** Detail pembatalan */
                if (KIRANAKU.isNotNullOrEmpty(cancel)) {
                    $('.modal-title').html('Persetujuan Pembatalan');
                    $('input[name="approval_type"]').val('pembatalan');
                    $('#detail-cancel').removeClass('hide');

                    $('.label_jumlah_kembali_jumlah').html(cancel.jumlah_kembali);
                    $('#label_batal_um')
                        .prop('disabled', false)
                        .prop('checked', cancel.batal_um_only)
                        .trigger('change')
                        .prop('disabled', true)
                        .trigger('change');

                    $('.label_cancel_catatan').html(KIRANAKU.isNullOrEmpty(cancel.catatan, cancel.catatan, '-'));
                }
                /** Detail Deklarasi */
                else if (KIRANAKU.isNotNullOrEmpty(deklarasi)) {
                    $('.modal-title').html('Persetujuan Deklarasi');
                    $('input[name="approval_type"]').val('deklarasi');
                    $('#div-biaya').removeClass('hide');
                    $('a[href="#modal-tab-deklarasi"]')
                        .parents('li')
                        .removeClass('hide');
                    $('a[href="#modal-tab-deklarasi"]').trigger('click');

                    approvalBiayaTable
                        .clear()
                        .draw();
                    $.each(deklarasi_details, function (i, detail) {
                        let template = $('#biaya_template').html();
                        let newBiaya = $(template);

                        $('.biaya_tanggal', newBiaya)
                            .html(
                                moment(detail.tanggal).format('DD.MM.YYYY')
                            );
                        $('.biaya_jenis', newBiaya).html(detail.tipe_expense_text);
                        $('.biaya_keterangan', newBiaya).html(detail.keterangan);
                        KIRANAKU.numericSet($('.biaya_jumlah .jumlah', newBiaya), parseFloat(detail.jumlah));
                        $('.biaya_jumlah .currency', newBiaya).html(detail.currency);

                        approvalBiayaTable
                            .row
                            .add($(newBiaya))
                            .draw();
                    });
                    KIRANAKU.numericSet($('.total_biaya .jumlah'), parseFloat(deklarasi.total_biaya));
                    KIRANAKU.numericSet($('.total_bayar .jumlah'), parseFloat(deklarasi.total_bayar));
                    KIRANAKU.numericSet($('.uang_muka .jumlah'), parseFloat(pengajuan.total_um));
                    $('button[data-action="disapprove"]').addClass('hide');
                } else {
                    $('.modal-title').html('Persetujuan Perjalanan Dinas');
                    $('input[name="approval_type"]').val('pengajuan');
                    $('#detail-cancel').addClass('hide');
                }

                KIRANAKU.convertNumericLabel($('.numeric-label'), {
                    digitGroupSeparator: '.',
                    decimalCharacter: ',',
                    allowDecimalPadding: false,
                    readOnly: true,
                    noEventListeners: true,
                    decimalPlaces: 0
                });
                modal.modal('show');
            } else {
                KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
            }
            KIRANAKU.hideLoading();
        },
        error: function (data) {
            KIRANAKU.hideLoading();
            KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
        }
    });

    $(document).on("click", ".btn-approval", function (e) {
        e.preventDefault();
        const isproses = KIRANAKU.isProses();

        if (isproses == 0) {
            const formData = new FormData($(".form-persetujuan")[0]);
            const action = $(this).data('action');
            let commentValid = true;
            if (action !== 'approve') {
                commentValid = KIRANAKU.isNotNullOrEmpty($('#comment').val());
            }
            var valid = $(".form-persetujuan").valid();

            if (commentValid && valid) {
                KIRANAKU.startProses();
                formData.append('action', action);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/persetujuan',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        KIRANAKU.hideLoading();
                        if (data.sts === 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                history.go(-2);
                            });
                        } else {
                            KIRANAKU.endProses();
                            KIRANAKU.alert('OK', data.msg, 'error', 'no', data.msg);
                        }
                    },
                    error: function (data) {
                        KIRANAKU.hideLoading();
                        KIRANAKU.endProses();
                        KIRANAKU.alert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                KIRANAKU.alert(false, 'Harap isi catatan apabila meminta untuk revisi atau menolak pengajuan.', 'error', 'no');
            }
        } else {
            KIRANAKU.alert(false, 'Silahkan tunggu sampai proses selesai.', 'info', 'no');
        }
    });

    $(document).on('click', 'button[name="back_btn_deklarasi"]', function (e) {
        history.go(-1);
    });
});