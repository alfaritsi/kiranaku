//! moment.js locale configuration

; (function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined'
        && typeof require === 'function' ? factory(require('../moment')) :
        typeof define === 'function' && define.amd ? define(['../moment'], factory) :
            factory(global.moment)
}(this, (function (moment) {
    'use strict';


    var id = moment.defineLocale('id', {
        months: 'Januari_Februari_Maret_April_Mei_Juni_Juli_Agustus_September_Oktober_November_Desember'.split('_'),
        monthsShort: 'Jan_Feb_Mar_Apr_Mei_Jun_Jul_Agt_Sep_Okt_Nov_Des'.split('_'),
        weekdays: 'Minggu_Senin_Selasa_Rabu_Kamis_Jumat_Sabtu'.split('_'),
        weekdaysShort: 'Min_Sen_Sel_Rab_Kam_Jum_Sab'.split('_'),
        weekdaysMin: 'Mg_Sn_Sl_Rb_Km_Jm_Sb'.split('_'),
        longDateFormat: {
            LT: 'HH.mm',
            LTS: 'HH.mm.ss',
            L: 'DD/MM/YYYY',
            LL: 'D MMMM YYYY',
            LLL: 'D MMMM YYYY [pukul] HH.mm',
            LLLL: 'dddd, D MMMM YYYY [pukul] HH.mm'
        },
        meridiemParse: /pagi|siang|sore|malam/,
        meridiemHour: function (hour, meridiem) {
            if (hour === 12) {
                hour = 0;
            }
            if (meridiem === 'pagi') {
                return hour;
            } else if (meridiem === 'siang') {
                return hour >= 11 ? hour : hour + 12;
            } else if (meridiem === 'sore' || meridiem === 'malam') {
                return hour + 12;
            }
        },
        meridiem: function (hours, minutes, isLower) {
            if (hours < 11) {
                return 'pagi';
            } else if (hours < 15) {
                return 'siang';
            } else if (hours < 19) {
                return 'sore';
            } else {
                return 'malam';
            }
        },
        calendar: {
            sameDay: '[Hari ini pukul] LT',
            nextDay: '[Besok pukul] LT',
            nextWeek: 'dddd [pukul] LT',
            lastDay: '[Kemarin pukul] LT',
            lastWeek: 'dddd [lalu pukul] LT',
            sameElse: 'L'
        },
        relativeTime: {
            future: 'dalam %s',
            past: '%s yang lalu',
            s: 'beberapa detik',
            ss: '%d detik',
            m: 'semenit',
            mm: '%d menit',
            h: 'sejam',
            hh: '%d jam',
            d: 'sehari',
            dd: '%d hari',
            M: 'sebulan',
            MM: '%d bulan',
            y: 'setahun',
            yy: '%d tahun'
        },
        week: {
            dow: 1, // Monday is the first day of the week.
            doy: 7  // The week that contains Jan 7th is the first week of the year.
        }
    });

    return id;

})));

function pageScroll() {
    window.scrollBy(0, 50); // horizontal and vertical scroll increments
    scrolldelay = setTimeout('pageScroll()', 100); // scrolls every 100 milliseconds
}

$(document).ready(function () {
    $(document).ajaxSend(function () {
        KIRANAKU.showLoading();
    });
    $(document).ajaxStop(function () {
        KIRANAKU.hideLoading();
    });
    /** Validasi related */
    $.validator.addMethod("numeric-max", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value <= params;
    }, jQuery.validator.format("<small>Harap masukkan nilai lebih kecil atau sama dengan {0}</small>"));

    $.validator.addMethod("numeric-min", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value >= params;
    }, jQuery.validator.format("<small>Harap masukkan nilai lebih besar atau sama dengan {0}</small>"));

    $.validator.addMethod("numeric-total-max", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value <= params;
    }, jQuery.validator.format("<small>Total hanya boleh lebih kecil atau sama dengan {0}</small>"));

    $.validator.addMethod("numeric-total-min", function (value, element, params) {
        value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        return this.optional(element) || value >= params;
    }, jQuery.validator.format("<small>Total hanya boleh lebih besar atau sama dengan {0}</small>"));

    $.validator.addMethod("numeric-leftover", function (value, element, params) {
        // value = AutoNumeric.getNumber("#" + $(element).attr('id'));
        value = KIRANAKU.numericGet(element);
        return this.optional(element) || parseFloat(value) === parseFloat(params);
    }, jQuery.validator.format("<small>Sisa hanya boleh sama dengan {0}</small>"));

    /** Plugin related */
    $('.icheck', document).bootstrapToggle({
        on: 'Ya',
        off: 'Tidak',
        size: 'small',
        onstyle: 'success',
        offstyle: 'default',
        width: 70
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

    $(document).on('shown.bs.dropdown', '.dataTable .input-group-btn:has(>.dropdown-menu), .bootstrap-datetimepicker-widget', function () {
        var $menu = $("ul", this);
        offset = $menu.offset();
        position = $menu.position();
        $('body').append($menu);
        $menu.show();
        if (offset) {
            $menu.css('position', 'absolute');
            $menu.css('top', (offset.top) + 'px');
            $menu.css('left', (offset.left) + 'px');
            $(this).data("myDropdownMenu", $menu);
        }
    });

    $(document).on('hide.bs.dropdown', '.dataTable .input-group-btn:has(.dropdown-toggle), bootstrap-datetimepicker-widget', function () {
        if ($(this).data("myDropdownMenu")) {
            $(this).append($(this).data("myDropdownMenu"));
            $(this).data("myDropdownMenu").removeAttr('style');
        }
    });

    $(document).on('dp.show', function (e) {
        var dp = $('.bootstrap-datetimepicker-widget.dropdown-menu');
        offset = dp.offset();
        position = dp.position();
        if (offset) {
            $('body').append(dp);
            dp.css('position', 'absolute');
            dp.css('top', (offset.top) + 'px');
            dp.css('left', (offset.left) + 'px');
            dp.data("dpStyle", dp);
        }
        $(window).trigger('resize');
    });

    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $('.nav-tabs a', $('.content-wrapper')).click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop() || $('html').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
    });

    /** Modal declare */
    const modalDetail = $('#modal-detail-spd-pengajuan');
    const modalTujuan = $('#modal-tujuan-spd');
    const modalHistory = $('#modal-history-spd');
    const modalChat = $('#modal-chat-spd');
    /** Detail spd related */
    const detailMultiTripTable = $('#table-detail-multi-trip', modalDetail).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const detailUangmukaTable = $('#table-detail-uangmuka', modalDetail).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    /** Tujuan SPD */
    const tujuanSpdTable = $('#table-tujuan-spd', modalTujuan).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    /** History SPD */
    const historySpdTable = $('#table-history-spd', modalHistory).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    const deklarasiBiayaTable = $('#table-detail-biaya', modalDetail).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    $(document).on('hide.bs.modal', modalDetail, function () {
        detailMultiTripTable
            .clear()
            .draw();
        deklarasiBiayaTable
            .clear()
            .draw();
    });

    /** Link tujuan spd clicked */
    $(document).on('click', '.spd-tujuan', function (e) {
        e.preventDefault();
        const modal = $('#modal-tujuan-spd');
        const idHeader = $(this).data('id');
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
                    const { details } = data.data;

                    let template = $('#tujuan_spd_template', modal).html();
                    $.each(details, function (i, trip) {
                        let newTrip = $(template).clone();
                        $('.label_multi_no', newTrip).html(trip.no_urut);
                        $('.label_multi_tujuan', newTrip).html(trip.tujuan_lengkap);
                        $('.label_multi_start', newTrip).html(trip.tanggal_berangkat);
                        $('.label_multi_keperluan', newTrip).html(trip.keperluan);

                        tujuanSpdTable
                            .row
                            .add($(newTrip))
                            .draw();
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
    });
    $(document).on('hide.bs.modal', '#modal-tujuan-spd', function (e) {
        tujuanSpdTable
            .clear()
            .draw();
    });

    /** Link detail spd clicked */
    $(document).on('click', '.spd-detail', function (e) {
        e.preventDefault();
        const modal = $('#modal-detail-spd-pengajuan');
        const idHeader = $(this).data('id');
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
                    const { pengajuan, details, personel, downpayments,
                        deklarasi, deklarasi_details, cancel, history, approval
                    } = data.data;
                    // set variable untuk lihat approvalpada modal detail
                    var appdata = (approval.list_atasan + "").split(",");
                    $('#popApprovalDetail').attr("data-list", JSON.stringify(appdata));

                    const tipe_trip = pengajuan.tipe_trip;
                    const setXLModal = modal.find('.modal-dialog').addClass('modal-xl');
                    $(".badge-status", modalDetail).removeClass(function (index, className) {
                        return (className.match(/(^|\s)bg-\S+/g) || []).join(' ');
                    });
                    $('.badge-status', modalDetail).html(pengajuan.status);
                    $('.badge-status', modalDetail).addClass(pengajuan.status_color);
                    /** Detail perjalanan */
                    $('#label_activity', modal).html(pengajuan.activity_label);
                    if (tipe_trip == "single") {
                        var tipe_trip_text = "Pulang pergi";
                        var dataloop = pengajuan;
                    } else {
                        var tipe_trip_text = "Multi Perjalanan";
                        var dataloop = details;
                    }
                    if (tipe_trip === 'single') { // not use
                        $('#label_tipe_trip', modal).html(tipe_trip_text);
                        $('#div-multi-trip', modal).removeClass('hide');
                        $('#div-single-trip', modal).addClass('hide');

                        let template = $('#detail_multitrip_template', modal).html();

                        let newTrip = $(template).clone();
                        $('.label_multi_no', newTrip).html(1);
                        $('.label_multi_tujuan', newTrip).html(pengajuan.tujuan_lengkap);
                        $('.label_multi_keperluan', newTrip).html(pengajuan.keperluan);
                        $('.label_multi_start', newTrip).html(pengajuan.tanggal_berangkat);
                        $('.label_multi_trans', newTrip).html('Transportasi : ' + pengajuan.transportasi + '<br> Pembelian Tiket pesawat '
                            + pengajuan.transportasi_tiket + ' <br> Penginapan : '
                            + pengajuan.jenis_penginapan);

                        detailMultiTripTable
                            .row
                            .add($(newTrip))
                            .draw();

                        $('#label_multi_end', modal).html(pengajuan.tanggal_kembali);
                    } else {
                        $('#label_tipe_trip', modal).html(tipe_trip_text);
                        $('#div-multi-trip', modal).removeClass('hide');
                        $('#div-single-trip', modal).addClass('hide');
                        let template = $('#detail_multitrip_template', modal).html();
                        $.each(dataloop, function (i, trip) {
                            let newTrip = $(template).clone();
                            var no = i + 1;
                            $('.label_multi_no', newTrip).html(no);
                            $('.label_multi_tujuan', newTrip).html(trip.tujuan_lengkap);
                            $('.label_multi_keperluan', newTrip).html(trip.keperluan);
                            $('.label_multi_start', newTrip).html(trip.tanggal_berangkat);
                            $('.label_multi_trans', newTrip).html('Transportasi : ' + trip.transportasi + '<br> Pembelian Tiket pesawat '
                                + trip.transportasi_tiket + ' <br> Penginapan : '
                                + trip.jenis_penginapan);

                            detailMultiTripTable
                                .row
                                .add($(newTrip))
                                .draw();
                        });
                        $('#label_multi_end', modal).html(pengajuan.tanggal_kembali);
                    }

                    $('#label_transportasi', modal).html('');
                    $.each(pengajuan.transportasi_label, function (i, tr) {
                        $('#label_transportasi', modal).append(
                            '<li>' + tr + '</li>'
                        );
                    });
                    $('#label_booking_kembali, #label_booking_brgkt', modal)
                        .prop('disabled', false)
                        .trigger('change');
                    $('#label_booking_brgkt', modal)
                        .prop('checked', pengajuan.booking_brgkt)
                        .trigger('change');
                    $('#label_booking_kembali', modal)
                        .prop('checked', pengajuan.booking_kembali)
                        .trigger('change');
                    $('#label_booking_kembali, #label_booking_brgkt', modal)
                        .prop('disabled', true)
                        .trigger('change');
                    $('#label_jenis_penginapan', modal).html(pengajuan.jenis_penginapan);
                    /** Detail personel */
                    $('#label_no_hp', modal).html(pengajuan.no_hp);
                    $('#label_p_nik', modal).html(personel.nik);
                    $('#label_p_nama', modal).html(personel.nama);
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
                    $('#label_p_kantor', modal).html(kantor);
                    $('#label_p_bagian', modal).html(bagian);
                    $('#label_p_jabatan', modal).html(personel.posst);
                    /** Uang muka */
                    $('.label_total_um_jumlah', modal).html(parseFloat(pengajuan.total_um));
                    if (downpayments.length) {
                        $('#div-uangmuka', modal).removeClass('hide');

                        $('#div-um-kembali', modal).removeClass('hide');

                        let firstCurrency = '';

                        detailUangmukaTable
                            .clear()
                            .draw();
                        let template = $('#detail_uangmuka_template', modal).html();
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

                            detailUangmukaTable
                                .row
                                .add($(expense))
                                .draw();
                        });
                        $('.label_total_um_currency', modalDetail).html(firstCurrency);
                    } else {
                        $('#div-uangmuka', modal).addClass('hide');
                    }

                    /** Auto click tab pengajuan */
                    $('a[href="#modal-tab-detail-pengajuan"]', modal).trigger('click');

                    /** Hide Tab deklarasi */
                    $('a[href="#modal-tab-deklarasi"]', modal)
                        .parents('li')
                        .addClass('hide');
                    /** Detail pembatalan */
                    if (KIRANAKU.isNotNullOrEmpty(cancel)) {
                        $('#fieldset-pembatalan', modalDetail).removeClass('hide');
                        if (cancel.approval_status != 5) {
                            $('.badge-pembatalan', modalDetail).removeClass('hide');
                        }
                        $('.label_jumlah_kembali_jumlah', modal).html(cancel.jumlah_kembali);
                        $('#label_batal_um', modal)
                            .prop('disabled', false)
                            .prop('checked', cancel.batal_um_only)
                            .trigger('change')
                            .prop('disabled', true)
                            .trigger('change');

                        $('#label_catatan', modal).html(KIRANAKU.isNullOrEmpty(cancel.catatan, cancel.catatan, '-'));
                    }
                    /** Detail Deklarasi */
                    else if (KIRANAKU.isNotNullOrEmpty(deklarasi)) {
                        $('#div-biaya', modal).removeClass('hide');
                        $('a[href="#modal-tab-deklarasi"]', modal)
                            .parents('li')
                            .removeClass('hide');

                        deklarasiBiayaTable
                            .clear()
                            .draw();
                        $.each(deklarasi_details, function (i, detail) {
                            let template = $('#biaya_template', modal).html();
                            let newBiaya = $(template);

                            $('.biaya_tanggal', newBiaya)
                                .html(
                                    moment(detail.tanggal).format('DD.MM.YYYY')
                                );
                            $('.biaya_jenis', newBiaya).html(detail.tipe_expense_text);
                            $('.biaya_keterangan', newBiaya).html(detail.keterangan);
                            KIRANAKU.numericSet($('.biaya_jumlah .jumlah', newBiaya), parseFloat(detail.jumlah));
                            $('.biaya_jumlah .currency', newBiaya).html(detail.currency);

                            deklarasiBiayaTable
                                .row
                                .add($(newBiaya))
                                .draw();
                        });
                        KIRANAKU.numericSet($('.total_biaya .jumlah', modal), parseFloat(deklarasi.total_biaya));
                        KIRANAKU.numericSet($('.total_bayar .jumlah', modal), parseFloat(deklarasi.total_bayar));
                        KIRANAKU.numericSet($('.uang_muka .jumlah', modal), parseFloat(pengajuan.total_um));
                    } else {
                        $('#fieldset-pembatalan', modalDetail).addClass('hide');
                        $('.badge-pembatalan', modalDetail).addClass('hide');
                    }

                    // ================================================history
                    if (KIRANAKU.isNotNullOrEmpty(history)) {
                        var history_det = history;

                        $(".tm_his").html("");
                        var appendToUl = "";
                        var x = 0;
                        $.each(history, function (i, h) {
                            let template = $('#history_spd_template_timeline', modal).html();
                            template = template.replaceAll('{no}', x);
                            let newHistory = $(template).clone();
                            $('.span_tgl', newHistory).html(moment(h.tgl_status_f).format('DD.MMMM.YYYY'));
                            $('.span_jam', newHistory).html(moment(h.tgl_status_f).format('HH:mm'));
                            $('.action_his', newHistory).html(h.action);
                            var komen = h.comment != "" ? h.comment + ' - ' + h.remark + '<br />' : "";
                            $('.action_by', newHistory).html(komen
                                + '<span class="badge bg-aqua">Dilakukan oleh '
                                + h.action_by_name + '[' + h.action_by + '] </span>'
                            );

                            var string = h.action; var classIcon = "";
                            string.indexOf("Pengajuan");
                            if (string.indexOf("Pengajuan") != '-1') {
                                classIcon = "fa fa-user-plus bg-blue";
                                classBg = "bg-blue";
                            } else if (string.indexOf("Disetujui") != '-1') {
                                classIcon = "fa fa-check-circle-o bg-green";
                                classBg = "bg-green";
                            } else if (string.indexOf("Kembali") != '-1') {
                                classIcon = "fa fa-user-times bg-red";
                                classBg = "bg-red";
                            }
                            $("#icon_action" + x, newHistory).addClass(classIcon);
                            $("#span_tgl" + x, newHistory).addClass(classBg);

                            $(".tm_his").append(newHistory);
                            x++;
                        });
                    }

                    AutoNumeric.multiple('#modal-detail-spd-pengajuan .numeric-label', {
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
    });

    $('#popApprovalDetail').popover({
        html: true,
        // title   : 'Info Approval <button type="button" id="close" class="close pull-right" onclick="$(&quot;#example&quot;).popover(&quot;hide&quot;);">&times;</button>',
        content: function (data) {
            var html = $('#template-approval-detail').clone().removeAttr('id').removeClass('hidden');
            var list = $(this).data('list');
            $.each(list, function (i, v) {
                $(html).find('tbody').append('<tr><td>' + v + '</td></tr>')
            });
            var $popover_togglers = this;
            return html;
        }
    });

    /** Link history spd clicked */
    $(document).on('click', '.spd-history', function (e) {
        e.preventDefault();
        const modal = $('#modal-history-spd');
        const idHeader = $(this).data('id');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/spd/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idHeader
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const history = data.data;

                    let template = $('#history_spd_template', modal).html();
                    $.each(history, function (i, h) {
                        let newHistory = $(template).clone();
                        $('.label_tanggal', newHistory).html(moment(h.tgl_status_f).format('DD.MM.YYYY HH:mm:ss'));
                        $('.label_action', newHistory).html(h.action);
                        $('.label_remark', newHistory).html(KIRANAKU.isNullOrEmpty(h.remark, h.remark, '-'));
                        $('.label_comment', newHistory).html(KIRANAKU.isNullOrEmpty(h.comment, h.comment, '-'));
                        $('.label_by', newHistory).html(
                            '[' + h.action_by + '] <br/>' + h.action_by_name
                        );

                        historySpdTable
                            .row
                            .add($(newHistory))
                            .draw();
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
    });
    $(document).on('hide.bs.modal', '#modal-history-spd', function (e) {
        historySpdTable
            .clear()
            .draw();
    });

    /** Link chat spd clicked */
    $(document).on('shown.bs.modal', '#modal-chat-spd', function () {
        lastElementTop = $('.direct-chat-messages', $(this)).get(0).scrollHeight;
        scrollAmount = lastElementTop;

        $('.direct-chat-messages', $(this)).animate({ scrollTop: scrollAmount }, 500);

    });
    $(document).on('click', '.spd-chat', function (e) {
        e.preventDefault();
        const modal = $('#modal-chat-spd');
        const idHeader = $(this).data('id');
        $('textarea[name="comment"]', modalChat).val('');
        let divFileinput = $('.fileinput', modalChat);
        divFileinput.removeClass('fileinput-exists');
        divFileinput.addClass('fileinput-new');
        divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
        divFileinput.find('input[type="file"]').val(null);
        KIRANAKU.showLoading();
        getDiscuss(idHeader);
    });

    function getDiscuss(idHeader) {
        const modal = $('#modal-chat-spd');
        $.ajax({
            url: baseURL + 'travel/spd/get/discuss',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: idHeader
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const form = $('#form-komentar', modal);
                    $('#id', form).val(idHeader);
                    refreshChats(data.discusses);
                    modal.modal('show');
                } else {
                    KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                }
                KIRANAKU.hideLoading();
            },
            error: function (data) {
                KIRANAKU.hideLoading();
                KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
            },
            complete: function () {
                setTimeout(function () {
                    if (modal.is(':visible')) {
                        getDiscuss(idHeader);
                    }
                }, 20000);
            }
        });
    }

    function refreshChats(data) {
        $('.chats', modalChat).remove();
        $.each(data, function (i, v) {

            var template = $('.template-left').clone().removeClass('template-left hide').addClass('chats');
            if (v.me)
                template = $('.template-right').clone().removeClass('template-right hide').addClass('chats');

            $(template).find('.direct-chat-img').attr('src', v.gambar);
            $(template).find('.direct-chat-name').html(v.nama);
            if (KIRANAKU.isNotNullOrEmpty(v.lampiran)) {
                $(template).find('.direct-chat-text .lampiran').removeClass('hide');
                $(template).find('.direct-chat-text .lampiran').attr('href', v.lampiran);
            }
            $(template).find('.direct-chat-text .message').html(v.comment);
            // add by ayy
            $(template).find('.direct-chat-timestamp').html(v.tanggal_disc + ' ' + v.jam_disc);
            $('#chat-body', modalChat).append(template).scrollTop($("#chat-body")[0].scrollHeight);
        });
    }

    $(document).on('submit', '#form-komentar', function (e) {
        e.preventDefault();
    });

    $('button[name="btn_komentar"]').on('click', function (e) {

        e.preventDefault();
        validate('#form-komentar', true);
        var form = $('#form-komentar', modalChat);
        var valid = form.valid();
        if (valid && $('#comment').val().length > 0) {
            KIRANAKU.showLoading();
            var isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                var formData = new FormData(form[0]);
                $.ajax({
                    url: baseURL + 'travel/spd/save/diskusi',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        KIRANAKU.hideLoading();
                        if (data.sts == 'OK') {
                            $('textarea[name="comment"]', modalChat).val('');
                            refreshChats(data.discusses);
                            let divFileinput = $('.fileinput', modalChat);
                            divFileinput.removeClass('fileinput-exists');
                            divFileinput.addClass('fileinput-new');
                            divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
                            divFileinput.find('input[type="file"]').val(null);
                        } else {
                            kiranaAlert('notOK', data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        KIRANAKU.hideLoading();
                        kiranaAlert('notOK', 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                KIRANAKU.hideLoading();
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        } else {
            kiranaAlert('notOK', 'Pesan Tidak Boleh Kosong!', 'warning', 'no');
        }
        return false;
    });

    /** Utilities */
    $(document).on('change.bs.fileinput', '.fileinput', function (e) {
        readURL($('input[type="file"]', $(this))[0], $('.fileinput-zoom', $(this)));
    });

    function readURL(input, targetPreview) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = function (e) {
                targetPreview.attr('href', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
});