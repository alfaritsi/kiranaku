function reinitFancybox() {
    $.fancybox.defaults.btnTpl.downloadBtn = '<a download data-fancybox-downloadBtn class="fancybox-button fancybox-button--downloadBtn" title="Download">' +
        '<i class="fa fa-download"></i> Download' +
        '</a>';

    $(document).on('click', '[data-fancybox-downloadBtn]', function () {
        var f = $.fancybox.getInstance();
        $(this).attr('href', f.current.src);
    });

    $('[data-fancybox]', document).fancybox({
        buttons: [
            'downloadBtn',
            'zoom',
            'close'
        ],
        lang: "en",
        i18n: {
            en: {
                CLOSE: "Tutup",
                NEXT: "Selanjutnya",
                PREV: "Sebelumnya",
                ERROR: "<div class='text-center'>File tidak dapat dilihat dengan viewer. <br/> Coba untuk mendownload file dengan link <br/><strong><i class='fa fa-download'></i>&nbsp;Download</strong> <br/>dipojok kanan atas.</div>",
                PLAY_START: "Start slideshow",
                PLAY_STOP: "Pause slideshow",
                FULL_SCREEN: "Full screen",
                THUMBS: "Thumbnails",
                DOWNLOAD: "Download",
                SHARE: "Share",
                ZOOM: "Zoom"
            },
        }
    });
}

$(document).ready(function (e) {
    $(document).ajaxSend(function () {
        KIRANAKU.showLoading();
    });
    $(document).ajaxStop(function () {
        KIRANAKU.hideLoading();
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

    $(document).on('click', '[data-toggle="collapse"]', function () {
        // adjustDatatableWidth();
    });

    reinitFancybox();

});