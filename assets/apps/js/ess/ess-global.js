$(document).ready(function (e) {

    $(document).on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
    })
    $(document).on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll')
    })

    $(document).on('shown.bs.dropdown', '.dataTable .input-group-btn:has(>.dropdown-menu)', function () {
        var $menu = $("ul", this);
        offset = $menu.offset();
        position = $menu.position();
        $('body').append($menu);
        $menu.show();
        $menu.css('position', 'absolute');
        $menu.css('top', (offset.top) + 'px');
        $menu.css('left', (offset.left) + 'px');
        $(this).data("myDropdownMenu", $menu);
    });

    $(document).on('hide.bs.dropdown', '.dataTable .input-group-btn:has(.dropdown-toggle)', function () {
        $(this).append($(this).data("myDropdownMenu"));
        $(this).data("myDropdownMenu").removeAttr('style');

    });

    String.prototype.trim = function () {
        return this.replace(/^\s+|\s+$/g, "");
    };
    String.prototype.ltrim = function () {
        return this.replace(/^\s+/, "");
    };
    String.prototype.rtrim = function () {
        return this.replace(/\s+$/, "");
    };

    $.validator.addMethod("numeric-max", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value <= params;
    }, jQuery.validator.format("Harap masukkan nilai lebih kecil atau sama dengan {0}"));

    $.validator.addMethod("numeric-min", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value >= params;
    }, jQuery.validator.format("Harap masukkan nilai lebih besar atau sama dengan {0}"));

    $.validator.addMethod("numeric-total-max", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value <= params;
    }, jQuery.validator.format("Total hanya boleh lebih kecil atau sama dengan {0}"));

    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop() || $('html').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
    });

    $.fn.datepicker.dates['en'] = {
        days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
        daysShort: ["Ming", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
        daysMin: ["Mn", "Sn", "Sl", "Rb", "Km", "Jm", "Sa"],
        months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        today: "Today",
        clear: "Clear",
        format: "dd.mm.yyyy",
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        weekStart: 1
    };

    $('.input-daterange').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        autoclose: true
    });
    $('#filter-date').removeClass('input-daterange');

    $(document).ajaxSend(function () {
        KIRANAKU.showLoading();
    });
    $(document).ajaxStop(function () {
        KIRANAKU.hideLoading();
    });

    /** detail cuti ijin **/
    $(document).on('click', '.detail', function (e) {
        let id = $(this).attr('data-detail');
        let showSaldo = $(this).attr('data-saldo');
        let sisa = 0;
        if (typeof showSaldo === "undefined")
            showSaldo = false;

        let modal = $('#modal-detail');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'ess/cutiijin/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {

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
                    $('#isaldo', modal).html((sisa) + " Hari");
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

    $(document).on('hide.bs.modal', '#modal-detail', function () {
        $('#datepicker-detail').datepicker('remove');
    });

    /** detail cuti ijin **/

    /** detail medical **/
    var showEstimasi = true;
    $(document).on('click', '.detail-medical', function (e) {
        var id = $(this).attr('data-detail');
        showEstimasi = KIRANAKU.isNullOrEmpty($(this).attr('data-estimasi'), $(this).attr('data-estimasi'), true);
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'ess/medical/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                e.preventDefault();

                var modal = $('#modal-medical-detail');
                if (modal != null) {
                    let pengajuan = data.data;
                    $('#nik', modal).html(pengajuan.nik);
                    $('#nama_pasien', modal).html(pengajuan.nama_pasien);
                    $('#nama_karyawan', modal).html(pengajuan.nama_karyawan);
                    $('#nomor', modal).html(KIRANAKU.isNullOrEmpty(pengajuan.nomor, pengajuan.nomor, '-'));
                    $('#jumlah_kwitansi span', modal).html(pengajuan.jumlah_kwitansi);
                    $('#total_kwitansi span', modal).html(pengajuan.total_kwitansi);
                    $('.fbk-jenis-detail', modal).addClass('hide');
                    if (pengajuan.total_ganti > 0 && pengajuan.catatan != null) {
                        $('#box-pembiayaan', modal).removeClass('hide');
                        $('#box-pembiayaan', modal).removeClass('hide');
                        $('#catatan', modal).html(KIRANAKU.isNullOrEmpty(pengajuan.catatan, pengajuan.catatan, '-'));
                    }
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
                            $('#plafon_bersalin span', div_fbk_detail).html(pengajuan.sisa_plafon_akhir);
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
                        var total_dibayar = 0;
                        var total_kwitansi = 0;
                        var total_kwitansi_disetujui = 0;

                        if (!KIRANAKU.isNullOrEmpty(pengajuan.gambar)) {
                            $('#gambar_kwitansi', modal)
                                .parents('.form-group')
                                .removeClass('hide');
                            $('#gambar_kwitansi', modal).find('a').attr('href', pengajuan.gambar);
                            $('#gambar_kwitansi', modal).find('a').attr('data-fancybox', pengajuan.gambar);
                        } else
                            $('#gambar_kwitansi', modal)
                                .parents('.form-group')
                                .addClass('hide');

                        pengajuan.kwitansi.forEach(function (kwitansi, i) {
                            var clone = template.clone();

                            clone.removeClass('template');
                            clone.removeClass('hide');

                            clone.find('p').each(function (j, el) {
                                var id = $(el).attr('id').replace('$', i);
                                $(el).attr('id', id);
                            });

                            if (kwitansi.disetujui == 'y')
                                clone.find('#kwitansi_disetujui_' + i).addClass('fa-check text-green');
                            else
                                clone.find('#kwitansi_disetujui_' + i).addClass('fa-times text-red');

                            clone.find('#kwitansi_nomor_detail_' + i).html(KIRANAKU.isNullOrEmpty(kwitansi.nomor, kwitansi.nomor, '-'));
                            clone.find('#kwitansi_nomor_' + i).html(KIRANAKU.isNullOrEmpty(kwitansi.nomor_kwitansi, kwitansi.nomor_kwitansi, '-'));
                            clone.find('#kwitansi_tanggal_' + i).html(moment(kwitansi.tanggal_kwitansi).format('DD.MM.YYYY'));
                            clone.find('#kwitansi_nominal_' + i + ' span').html(kwitansi.amount_kwitansi);
                            clone.find('#kwitansi_amount_ganti_' + i + ' span').html(
                                KIRANAKU.isNullOrEmpty(kwitansi.amount_ganti, kwitansi.amount_ganti, 0)
                            );
                            total_kwitansi += kwitansi.amount_kwitansi;

                            var ext = kwitansi.gambar != null ? kwitansi.gambar.split('.').pop().toLowerCase() : null;

                            var lampiran = "";

                            if (ext != null) {
                                lampiran = "<a data-fancybox='" + kwitansi.gambar + "' alt='kwitansi " + kwitansi.nomor_kwitansi + "' href='" + kwitansi.gambar + "'>" +
                                    "<i class='fa fa-search'></i> " +
                                    "</a>";
                            } else
                                lampiran = '-';

                            if (kwitansi.disetujui === 'y') {
                                total_dibayar += parseInt(kwitansi.amount_ganti);
                                total_kwitansi_disetujui += kwitansi.amount_kwitansi;
                            }

                            clone.find('#kwitansi_lampiran_' + i).html(lampiran);

                            table.find('tbody').append(clone);
                        });

                        $('#total_akan_dibayar', modal).parents('tr').removeClass('hide');
                        $('#total_akan_dibayar span', modal).html(total_dibayar);
                        if (pengajuan.id_fbk_status == 4)
                            $('#total_akan_dibayar_label', modal).html('Total Dibayar Perusahaan');
                        else
                            $('#total_akan_dibayar_label', modal).html('Total Dibayar Perusahaan');

                        if (total_dibayar > 0 && pengajuan.fbk_jenis != 'inap') {
                            $('#total_estimasi', modal).parents('tr').addClass('hide');
                        } else {
                            var diff = (pengajuan.plafon_kamar / pengajuan.biaya_kamar);
                            var estimasi = 0;
                            if (diff > 1) {
                                estimasi = total_kwitansi;
                            }
                            else {
                                if (total_kwitansi_disetujui > 0)
                                    estimasi = Math.round(diff * total_kwitansi_disetujui);
                                else
                                    estimasi = Math.round(diff * total_kwitansi);
                            }
                            console.log(showEstimasi);
                            if (showEstimasi && showEstimasi !== 'false')
                                $('#total_estimasi', modal).parents('tr').removeClass('hide');
                            $('#total_estimasi span', modal).html(estimasi);
                        }
                        $('#total_akan_dibayar_karyawan span', modal).html(total_kwitansi - total_dibayar);

                        AutoNumeric.multiple('#modal-medical-detail span.numeric', {
                            digitGroupSeparator: '.',
                            decimalCharacter: ',',
                            allowDecimalPadding: false,
                            readOnly: true,
                            noEventListeners: true,
                            decimalPlaces: 0
                        });

                        modal.off(e);
                    });
                }
            },
            error: function (data) {

                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });
    /** detail medical **/

    /** detail bak **/
    $(document).on('click', '.bak-detail', function (e) {
        var id = $(this).attr('data-detail');
        var modal = $('#modal-detail-bak');
        KIRANAKU.showLoading();
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
                $('#absen_masuk .jam', modal).html(data.data.absen_masuk);
                $('#absen_masuk .tanggal', modal).html(
                    KIRANAKU.isNullOrEmpty(
                        data.data.tanggal_masuk,
                        moment(data.data.tanggal_masuk).format('DD.MM.YYYY'),
                        ''
                    )
                );
                $('#absen_keluar .jam', modal).html(data.data.absen_keluar);
                $('#absen_keluar .tanggal', modal).html(
                    KIRANAKU.isNullOrEmpty(
                        data.data.tanggal_keluar,
                        moment(data.data.tanggal_keluar).format('DD.MM.YYYY'),
                        ''
                    )
                );
                $('#tanggal_input', modal).html(
                    KIRANAKU.isNullOrEmpty(
                        data.data.tanggal_buat,
                        moment(data.data.tanggal_buat).format('DD.MM.YYYY'),
                        moment(data.data.tanggal_migrasi).format('DD.MM.YYYY')
                    )
                );
                $('#alasan', modal).html(data.data.alasan);
                $('#keterangan', modal).html(data.data.keterangan);

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
    /** detail bak **/

    /** Event Click Cetak Medical **/
    $(document).on('click', '.cetak-medical', function (e) {
        var id = $(this).attr('data-cetak');
        KIRANAKU.showLoading();
        window.location = baseURL + 'ess/medical/cetak/' + id;
    });

    $('footer', document).addClass('no-print');
    /** Event Click Cetak Medical **/

    $(document).on('click', '.history', function (e) {
        var id = $(this).attr('data-history');
        var modal = $('#modal-history');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'ess/cutiijin/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {

                let table_history = $('#table-history-persetujuan');
                table_history.dataTable({
                    searching: false,
                    ordering: false
                });
                table_history.DataTable().clear().draw();
                $.each(data, function (i, v) {
                    table_history.DataTable().row.add([
                        (v.nama_status.rtrim() == 'Menunggu') ? 'Pengajuan' : v.nama_status,
                        v.nama_author,
                        v.nama_bagian,
                        moment(v.tanggal_buat).format('DD.MM.YYYY')
                    ])
                });
                table_history.DataTable().draw();
                modal.modal('show');
            },
            error: function (data) {

                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', '.history-medical', function (e) {
        var id = $(this).attr('data-history');
        var modal = $('#modal-history');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'ess/medical/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {

                let table_history = $('.table-history', modal);
                table_history.dataTable({
                    searching: false,
                    ordering: false
                });
                table_history.DataTable().clear().draw();
                $.each(data, function (i, v) {
                    table_history.DataTable().row.add([
                        (v.nama_status.rtrim() == 'Menunggu') ? 'Pengajuan' : v.nama_status,
                        v.nama_author,
                        v.nama_bagian,
                        moment(v.tanggal_buat).format('DD.MM.YYYY')
                    ])
                });
                table_history.DataTable().draw();
                modal.modal('show');
            },
            error: function (data) {

                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', '.bak-history', function (e) {
        var id = $(this).attr('data-history');
        var modal = $('#modal-history');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'ess/bak/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {

                let table_history = $('.table-history', modal);
                table_history.dataTable({
                    searching: false,
                    ordering: false
                });
                table_history.DataTable().clear().draw();
                $.each(data, function (i, v) {
                    table_history.DataTable().row.add([
                        (v.nama_status.rtrim() == 'Menunggu') ? 'Pengajuan' : v.nama_status,
                        v.nama_author,
                        v.nama_bagian,
                        moment(v.tanggal_buat).format('DD.MM.YYYY')
                    ])
                });
                table_history.DataTable().draw();
                modal.modal('show');
            },
            error: function (data) {

                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('click', '.detail-kwitansi,.detail-kwitansi-disetujui', function (e) {
        var id = $(this).attr('data-kwitansi');
        var filter_disetujui = $(this).data('disetujui');
        console.log(filter_disetujui);
        var modal = $('#modal-kwitansi');

        $.ajax({
            url: baseURL + 'ess/medical/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                let table_kwitansi = $('.table-kwitansi', modal);
                table_kwitansi.dataTable({
                    searching: false,
                    ordering: false,
                    destroy: true
                });
                table_kwitansi.DataTable().clear().draw();
                $.each(data.data.kwitansi, function (i, v) {
                    let disetujui = "<i class='fa fa-times text-red'></i>";
                    if (v.disetujui == 'y')
                        disetujui = "<i class='fa fa-check text-green'></i>";
                    let dibayar = "<i class='fa fa-times text-red'></i>";
                    if (v.status_migrasi == 'A')
                        dibayar = "<i class='fa fa-check text-green'></i>";

                    var ext = v.gambar != null ? v.gambar.split('.').pop().toLowerCase() : null;

                    var lampiran = "";

                    if (ext != null) {
                        if (ext == 'pdf')
                            lampiran = "<a data-fancybox='" + v.gambar + "' " +
                                "alt='kwitansi " + v.nomor_kwitansi + "' href='" + v.gambar + "'>" +
                                "<i class='fa fa-file'></i> " +
                                "</a>";
                        else
                            lampiran = "<a data-fancybox='" + v.gambar + "' " +
                                "alt='kwitansi " + v.nomor_kwitansi + "' href='" + v.gambar + "'>" +
                                "<i class='fa fa-image'></i> " +
                                "</a>";
                    } else
                        lampiran = '-';

                    if (
                        v.disetujui == 'y' ||
                        (filter_disetujui === false && (v.disetujui == 'n' || v.disetujui == null))
                    )
                        table_kwitansi.DataTable().row.add([
                            v.nomor,
                            v.nomor_kwitansi,
                            moment(v.tanggal_kwitansi).format('DD.MM.YYYY'),
                            "Rp. <span class='numeric'>" + v.amount_kwitansi + "</span>",
                            "Rp. <span class='numeric'>" +
                            KIRANAKU.isNullOrEmpty(v.amount_ganti, v.amount_ganti, 0) +
                            "</span>",
                            lampiran,
                            disetujui,
                            dibayar
                        ]);
                });
                table_kwitansi.DataTable().draw();
                AutoNumeric.multiple('#modal-kwitansi span.numeric', {
                    digitGroupSeparator: '.',
                    decimalCharacter: ',',
                    allowDecimalPadding: false,
                    readOnly: true,
                    noEventListeners: true,
                    decimalPlaces: 0
                });
                modal.modal('show');
            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });
    /** On click lampiran medical **/
    $(document).on('click', '.lampiran', function (e) {
        var id = $(this).attr('data-lampiran');
        $.ajax({
            url: baseURL + 'ess/medical/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                var kwitansi = [];
                $.each(data.data.kwitansi, function (i, v) {
                    var ext = v.gambar != null ? v.gambar.split('.').pop().toLowerCase() : null;

                    if (ext != null)
                        kwitansi.push({
                            src: v.gambar
                        })
                });

                $.fancybox.open(kwitansi);
            }
        });
    });

    $('#modal-history').on('hide.bs.modal', function () {
        let table_history = $('.table-history', $(this));
        table_history.DataTable().destroy();
    });
});