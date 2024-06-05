var init_form_data;
$(document).ready(function () {
    $('#single_trip').iCheck('check');

    $('#multi_trip').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    $('#single_trip').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    $('#single_trip').on('ifChecked', function () {
        $('#multi_trip').iCheck('uncheck');
        $('input[name="tipe_trip"]').val('single');

        $("#pengajuan_single").html('');

        $("#pengajuan_single").removeClass('hidden');
        $("#pengajuan_multi").addClass('hidden');
    });

    $('#multi_trip').on('ifChecked', function () {
        $('#single_trip').iCheck('uncheck');
        $('input[name="tipe_trip"]').val('multi');

        $("#form_multi").html('');

        $("#pengajuan_multi").removeClass('hidden');
        $("#pengajuan_single").addClass('hidden');
    });

    const detailUangmukaTable = $('#table-uangmuka').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    $.ajax({
        url: baseURL + 'travel/spd/get/pengajuan',
        type: 'POST',
        dataType: 'JSON',
        data: {
            id: $('input[name="id_header"]').val()
        },
        success: function (data) {
            if (data.sts === 'OK') {
                const { pengajuan, details, optpabrik, opttujuan, optcountry, cancel, deklarasi, history, downpayments, rencana_aktifitas } = data.data;
                $('input[name="id_travel_header"]').val(pengajuan.id_travel_header);

                const tipe_trip = pengajuan.tipe_trip;

                //START FORM HEADER
                //tab um
                var totalum = pengajuan.total_um > 0 ? pengajuan.total_um : '0';
                $('input[name="total_um"]').val(numberWithCommas(parseFloat(totalum)));
                //AKTIFITAS
                $("select[name='activity']").val(pengajuan.activity).trigger('change');
                // *NO HP
                $('input[name="no_hp"]').val(pengajuan.no_hp);
                // *TIPE TRIP

                if (tipe_trip == 'single') {
                    $('#jenis-trip').html('SINGLE - TRIP');
                    let rowtrip = 1;
                    detail = details;

                    generate_data_trip(detail);

                    $('select[name="detail[' + rowtrip + '][country]"]').val(detail.country).trigger('change.select2');
                    $('select[name="detail[' + rowtrip + '][tujuan_persa]"]').val(detail.tujuan_persa).trigger('change.select2');
                    $('select[name="detail[' + rowtrip + '][tujuan]"]').val(detail.tujuan).trigger('change.select2');
                    $('input[name="detail[' + rowtrip + '][tujuan_lain]"]').val(detail.tujuan_lain);
                    if (detail.tujuan_lain !== "" && detail.tujuan_lain.trim() !== "") {
                        $('input[name="detail[' + rowtrip + '][tujuan_lain]"]').closest('.form-group').removeClass('hide');
                    }

                    var endDatetime = moment(detail.end_date + " " + detail.end_time).format("DD.MM.YYYY HH:mm");
                    $('input[name="detail_end"]').val(endDatetime);

                    var startDatetime = moment(detail.start_date + " " + detail.start_time).format("DD.MM.YYYY HH:mm");

                    $('input[name="detail[' + rowtrip + '][start]"]').val(startDatetime);

                    var transports = (detail.transportasi + '').split(',');
                    var transports_ticket = (detail.transportasi_tiket + '').split(',');
                    $('select[name="detail[' + rowtrip + '][trans][]"]').val(transports).trigger('change.select2');
                    $('select[name="detail[' + rowtrip + '][tiket][]"]').val(transports_ticket).trigger('change.select2');
                    if (detail.jenis_penginapan == 'mess' || detail.jenis_penginapan == 'Mess') {
                        details.jenis_penginapan = 'Mess';
                    }
                    if (detail.jenis_penginapan == 'Hotel' || detail.jenis_penginapan == 'hotel') {
                        detail.jenis_penginapan = 'Hotel';
                    }
                    $('select[name="detail[' + rowtrip + '][inap]"]').val(detail.jenis_penginapan).trigger('change.select2');

                    if (detail.pic_check == 1) {
                        $("#btn_tab_akomodasi").removeClass("hidden");
                        let info_mess = "";
                        if (detail.jenis_penginapan == 'Mess') {
                            info_mess = '<br><strong>Ketersediaan Mess:</strong> ' + (detail.mess_available == 1 ? 'Ya' : 'Tidak');
                        }

                        let output = `<div class="info-penerimaan">
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="fa fa-check"></i> Konfirmasi Penerimaan
                                    <br>
                                    ${detail.tujuan_lengkap}
                                </div>
                                <div class="col-sm-9">
                                    <strong>Kendaraan Penjemput:</strong> ${detail.transportasi_penjemput}
                                    ${info_mess}
                                </div>
                            </div>
                        </div>
                        `;

                        $("#list-penerimaan").append(output);
                    }
                } else {
                    $('#jenis-trip').html('MULTI - TRIP');
                    $.each(details, function (x, detail) {
                        let rowtrip = x + 1;

                        generate_data_trip(detail);

                        $('select[name="detail[' + rowtrip + '][country]"]').val(detail.country).trigger('change.select2');
                        $('select[name="detail[' + rowtrip + '][tujuan_persa]"]').val(detail.tujuan_persa).trigger('change.select2');
                        $('select[name="detail[' + rowtrip + '][tujuan]"]').val(detail.tujuan).trigger('change.select2');
                        $('input[name="detail[' + rowtrip + '][tujuan_lain]"]').val(detail.tujuan_lain);
                        if (detail.tujuan_lain && detail.tujuan_lain.trim() !== "") {
                            $('input[name="detail[' + rowtrip + '][tujuan_lain]"]').closest('.form-group').removeClass('hide');
                        }

                        var endDatetime = moment(detail.end_date + " " + detail.end_time).format("DD.MM.YYYY HH:mm");
                        $('input[name="detail_end"]').val(endDatetime);

                        var startDatetime = moment(detail.start_date + " " + detail.start_time).format("DD.MM.YYYY HH:mm");

                        $('input[name="detail[' + rowtrip + '][start]"]').val(startDatetime);

                        var transports = (detail.transportasi + '').split(',');
                        var transports_ticket = (detail.transportasi_tiket + '').split(',');
                        $('select[name="detail[' + rowtrip + '][trans][]"]').val(transports).trigger('change.select2');
                        $('select[name="detail[' + rowtrip + '][tiket][]"]').val(transports_ticket).trigger('change.select2');
                        if (detail.jenis_penginapan == 'mess' || detail.jenis_penginapan == 'Mess') {
                            detail.jenis_penginapan = 'Mess';
                        }
                        if (detail.jenis_penginapan == 'Hotel' || detail.jenis_penginapan == 'hotel') {
                            detail.jenis_penginapan = 'Hotel';
                        }
                        $('select[name="detail[' + rowtrip + '][inap]"]').val(detail.jenis_penginapan).trigger('change.select2');

                        if (detail.pic_check == 1) {
                            $("#btn_tab_akomodasi").removeClass("hidden");
                            let info_mess = "";
                            if (detail.jenis_penginapan == 'Mess') {
                                info_mess = '<br><strong>Ketersediaan Mess:</strong> ' + (detail.mess_available == 1 ? 'Ya' : 'Tidak');
                            }

                            let output = `<div class="info-penerimaan">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <i class="fa fa-check"></i> Konfirmasi Penerimaan
                                        <br>
                                        ${detail.tujuan_lengkap}
                                    </div>
                                    <div class="col-sm-9">
                                        <strong>Kendaraan Penjemput:</strong> ${detail.transportasi_penjemput}
                                        ${info_mess}
                                    </div>
                                </div>
                            </div>
                            `;

                            $("#list-penerimaan").append(output);
                        }
                    });
                }

                //default open
                $('.panel-heading').trigger('click');

                //UANG MUKA
                if (downpayments.length) {
                    $('#div-uangmuka').removeClass('hide');

                    $('#div-um-kembali').removeClass('hide');

                    let firstCurrency = '';

                    detailUangmukaTable
                        .clear()
                        .draw();
                    let template = $('#uangmuka_template').html();
                    $.each(downpayments, function (i, dp) {
                        let expense = $(template).clone();
                        const rateV = parseFloat(dp.value) * 100;

                        $('.uangmuka-label-expense', expense).html(dp.tipe_expense_text);
                        $('.uangmuka-label-rate', expense).html(numberWithCommas(rateV));
                        $('.uangmuka-label-durasi', expense).html(dp.durasi);
                        $('.uangmuka-label-jumlah', expense).html(numberWithCommas(parseFloat(dp.jumlah)));
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
                    $('.label_total_um_currency').html(firstCurrency);
                } else {
                    $('#div-uangmuka').addClass('hide');
                }

                if (cancel) {
                    $('input[name="approval_type"]').val("pembatalan");
                }

                if (deklarasi) {
                    $('input[name="approval_type"]').val("deklarasi");
                }

                if (KIRANAKU.isNotNullOrEmpty(history)) {
                    var history_det = history;

                    $(".tm_his").html("");
                    var appendToUl = "";
                    var x = 0;
                    $.each(history, function (i, h) {
                        let template = $('#history_spd_template_timeline').html();
                        template = template.replaceAll('{no}', x);
                        let newHistory = $(template).clone();
                        $('.span_tgl', newHistory).html(moment(h.tgl_status_f).format('DD MMMM YYYY HH:mm'));
                        // $('.span_jam', newHistory).html(moment(h.tgl_status_f).format('HH:mm'));
                        $('.action_his', newHistory).html(h.action);
                        var komen = h.comment != "" ? h.comment + ' - ' + h.remark + '<br />' : "";
                        $('.action_by', newHistory).html(komen
                            + '<span class="badge bg-aqua">Dilakukan oleh '
                            + h.action_by_name + '[' + h.action_by + '] </span>'
                        );

                        // appendToUl += newHistory; 
                        // var has_string = $('*:contains("pengajuan")');
                        var string = h.action; var classIcon = "";
                        var n = string.indexOf("Pengajuan");
                        if (string.indexOf("Pengajuan") != '-1') {
                            classIcon = "fa fa-user-plus bg-blue";
                            classBg = "bg-blue";
                        } else if (string.indexOf("Disetujui") != '-1') {
                            classIcon = "fa fa-check-circle-o bg-green";
                            classBg = "bg-green";
                        } else if (string.indexOf("Kembali") != '-1') {
                            classIcon = "fa fa-user-times bg-red";
                            classBg = "bg-red";
                        } else if (string.indexOf("Pemesanan") != '-1') {
                            classIcon = "fa fa-plane bg-orange";
                            classBg = "bg-orange";
                        }
                        $("#icon_action" + x, newHistory).addClass(classIcon);
                        $("#span_tgl" + x, newHistory).addClass(classBg);

                        $(".tm_his").append(newHistory);
                        x++;
                    });
                }

                //Rencana Aktifitas
                if (rencana_aktifitas && rencana_aktifitas.length) {
                    $("#list-aktifitas").empty();
                    $.each(rencana_aktifitas, function (i, v) {
                        let output = `<tr class="row-aktifitas">
                            <td><input type="text" class="form-control" name="tanggal_aktifitas_add[]" autocomplete="off" readonly value="${v.tanggal_aktifitas_format}"></td>
                            <td><input type="text" class="form-control" name="pabrik_aktifitas_add[]" autocomplete="off" readonly value="${v.lokasi}"></td>
                            <td><textarea type="text" class="form-control" name="aktifitas_add[]" rows="1" readonly>${v.aktifitas}</textarea></td>
                        </tr>`;
                        $("#list-aktifitas").append(output);
                    });
                }
            }
        },
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        $('#KiranaModals .modal-title').css("text-transform", "capitalize");
        $('#KiranaModals .modal-title').html("Persetujuan Perjalanan Dinas");
        var id_travel_header = $("input[name='id_header']").val();
        var flag = $("input[name='is_approval_by']").val();
        var approval_type = $('input[name="approval_type"]').val();
        var isapprovalby = "0";
        var required = "";
        if ($(this).val() !== "approve") {
            required = "required";
        }

        switch ($(this).val()) {
            case "approve":
                $("#KiranaModals").removeAttr("class");
                $("#KiranaModals").addClass("modal");
                $("#KiranaModals").addClass("modal-success");
                break;
            case "revise":
                $("#KiranaModals").removeAttr("class");
                $("#KiranaModals").addClass("modal");
                $("#KiranaModals").addClass("modal-warning");
                break;
            case "disapprove":
            case "drop":
                $("#KiranaModals").removeAttr("class");
                $("#KiranaModals").addClass("modal");
                $("#KiranaModals").addClass("modal-danger");
                break;
        }

        var output = '';
        output += '<form class="form-persetujuan" enctype="multipart/form-data">';
        output += '	<div class="modal-body">';
        output += '		<div class="form-horizontal">';
        output += '			<div class="form-group">';
        output += '				<label for="komentar" class="col-sm-12 control-label text-left">Komentar</label>';
        output += '				<div class="col-sm-12">';
        output += '					<textarea class="form-control" id="comment" name="comment" ' + required + '></textarea>';
        output += '				</div>';
        output += '			</div>';

        if (flag == "true") {
            isapprovalby = '1';
            output += '			<div class="form-group" id="approval_lampiran_div">';
            output += '				<label for="komentar" class="col-sm-12 control-label text-left">Lampiran</label>';
            output += '				<div class="col-sm-12">';
            output += '					<input type="file" class="form-control" name="lampiran" id="lampiran" ' + required + '></input>';
            output += '				</div>';
            output += '			</div>';
        }

        output += '		</div>';
        output += '	</div>';
        output += '	<div class="modal-footer">';
        output += '		<div class="form-group">';
        output += '			<input type="hidden" name="action" value="' + $(this).val() + '">';
        output += '			<input type="hidden" name="approval_type" value="' + approval_type + '">';
        output += '			<input type="hidden" name="is_approval_by" value="' + isapprovalby + '">';
        output += '			<input type="hidden" name="id_travel_header" value="' + id_travel_header + '">';
        output += '			<button type="button" class="btn btn-approval btn-primary" name="submit-approval">Submit</button>';
        output += '		</div>';
        output += '	</div>';
        output += '</form>';

        $('#KiranaModals .modal-body').remove();
        $('#KiranaModals .modal-footer').remove();
        $('#KiranaModals form').remove();
        $('#KiranaModals .modal-content').append(output);

        $('#KiranaModals').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });
    });

    $(document).on("click", ".btn-approval", function (e) {
        e.preventDefault();
        const isproses = KIRANAKU.isProses();

        if (isproses == 0) {
            const formData = new FormData($(".form-persetujuan")[0]);
            const action = $("input[name='action']").val();
            const comment = $("#comment").val();
            let commentValid = "true";
            if (action !== 'approve' && comment == "") {
                commentValid = "false";
            }

            if (commentValid == "true") {
                KIRANAKU.startProses();
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
                            KIRANAKU.alert(data.sts, data.msg, "success", baseURL + 'travel/spd/persetujuan');
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

    $(document).on('click', '#head_pengajuan', function (e) {
        if (!$(this).hasClass('whitesmoke')) {
            if ($("#head_uang_muka").hasClass('whitesmoke')) {
                $("#head_uang_muka").removeClass('whitesmoke');
                $("#tab_uang_muka").addClass('hidden');
            }

            if ($("#head_transportasi").hasClass('whitesmoke')) {
                $("#head_transportasi").removeClass('whitesmoke');
                $("#tab_transportasi").addClass('hidden');
            }

            if ($("#head_history").hasClass('whitesmoke')) {
                $("#head_history").removeClass('whitesmoke');
                $("#tab_history").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_pengajuan").removeClass('hidden');
        }
    });

    $(document).on('click', '#head_uang_muka', function (e) {
        if (!$(this).hasClass('whitesmoke')) {
            if ($("#head_pengajuan").hasClass('whitesmoke')) {
                $("#head_pengajuan").removeClass('whitesmoke');
                $("#tab_pengajuan").addClass('hidden');
            }

            if ($("#head_transportasi").hasClass('whitesmoke')) {
                $("#head_transportasi").removeClass('whitesmoke');
                $("#tab_transportasi").addClass('hidden');
            }

            if ($("#head_history").hasClass('whitesmoke')) {
                $("#head_history").removeClass('whitesmoke');
                $("#tab_history").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_uang_muka").removeClass('hidden');
        }
    });

    $(document).on('click', '#head_transportasi', function (e) {
        if (!$(this).hasClass('whitesmoke')) {

            if ($("#head_pengajuan").hasClass('whitesmoke')) {
                $("#head_pengajuan").removeClass('whitesmoke');
                $("#tab_pengajuan").addClass('hidden');
            }

            if ($("#head_uang_muka").hasClass('whitesmoke')) {
                $("#head_uang_muka").removeClass('whitesmoke');
                $("#tab_uang_muka").addClass('hidden');
            }

            if ($("#head_history").hasClass('whitesmoke')) {
                $("#head_history").removeClass('whitesmoke');
                $("#tab_history").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_transportasi").removeClass('hidden');
        }
    });

    $(document).on('click', '#head_history', function (e) {
        if (!$(this).hasClass('whitesmoke')) {
            if ($("#head_pengajuan").hasClass('whitesmoke')) {
                $("#head_pengajuan").removeClass('whitesmoke');
                $("#tab_pengajuan").addClass('hidden');
            }

            if ($("#head_transportasi").hasClass('whitesmoke')) {
                $("#head_transportasi").removeClass('whitesmoke');
                $("#tab_transportasi").addClass('hidden');
            }

            if ($("#head_uang_muka").hasClass('whitesmoke')) {
                $("#head_uang_muka").removeClass('whitesmoke');
                $("#tab_uang_muka").addClass('hidden');
            }

            $(this).addClass('whitesmoke');
            $("#tab_history").removeClass('hidden');
        }
    });

    $(document).on('click', '.panel-heading', function (e) {
        var $this = $(this);
        if (!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });

    $(document).on('click', '#sbmit', function () {
        $('#KiranaModals .modal-title').css("text-transform", "capitalize");
        $('#KiranaModals .modal-title').html("Konfirmasi Pengajuan");

        var required = "";
        if ($(this).val() !== "approve") {
            required = "required";
        }

        switch ($(this).val()) {
            case "submit":
            case "approve":
                $("#KiranaModals").removeAttr("class");
                $("#KiranaModals").addClass("modal");
                $("#KiranaModals").addClass("modal-success");
                break;
            case "decline":
                $("#KiranaModals").removeAttr("class");
                $("#KiranaModals").addClass("modal");
                $("#KiranaModals").addClass("modal-warning");
                break;
            case "assign":
                $("#KiranaModals").removeAttr("class");
                $("#KiranaModals").addClass("modal");
                $("#KiranaModals").addClass("modal-info");
                break;
            case "stop":
            case "drop":
                $("#KiranaModals").removeAttr("class");
                $("#KiranaModals").addClass("modal");
                $("#KiranaModals").addClass("modal-danger");
                break;
        }

        var output = '';
        output += '	<div class="modal-body">';
        output += '		<div class="form-horizontal">';
        output += '			<div class="form-group">';
        output += '				<label for="atasan" class="col-sm-12 control-label text-left">Pengajuan ini akan meminta persetujuan dari :</label>';
        output += '				<label for="atasan" class="col-sm-12 control-label text-left">Bapak ANDREAS BOY R. ARSAN</label>';
        output += '				<label for="atasan" class="col-sm-12 control-label text-left">Ibu FRANSISCA TINA WAHYUNINGSIH</label>';
        output += '			</div>';
        output += '		</div>';
        output += '	</div>';
        output += '	<div class="modal-footer">';
        output += '		<div class="form-group">';
        output += '			<input type="hidden" name="action" value="' + $(this).val() + '">';
        output += '			<button type="button" class="btn btn-primary" name="submit_pengajuan">Submit</button>';
        output += '		</div>';
        output += '	</div>';

        $('#KiranaModals .modal-body').remove();
        $('#KiranaModals .modal-footer').remove();
        $('#KiranaModals form').remove();
        $('#KiranaModals .modal-content').append(output);

        $('#KiranaModals').modal({
            backdrop: 'static',
            keyboard: true,
            show: true
        });
    });

    $(document).on("click", "button[name='submit_pengajuan']", function (e) {
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            KIRANAKU.showLoading();
            var formData = new FormData($("#form-pengajuan")[0]);
            $.ajax({
                url: baseURL + 'travel/spd/save/pengajuans',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.sts == 'OK') {
                        KIRANAKU.alert(data.sts, data.msg, "success", baseURL + 'travel/spd/persetujuan');
                        $("input[name='isproses']").val(0);
                        KIRANAKU.hideLoading();
                    } else {
                        KIRANAKU.alert('NotOK', 'mohon periksa kembali data yang dimasukan', 'warning', 'no');
                        $("input[name='isproses']").val(0);
                        KIRANAKU.hideLoading();
                    }
                }
            });

        } else {
            kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
        }
        e.preventDefault();
        return false;
    });


    $(document).on("click", "#hapus_trip", function () {
        var rowtrip = $("#form_multi .rowtrip").length;
        $(".rowtrip:eq(" + rowtrip + ")").remove();

        rowtrip = rowtrip - 1;

        //BUTTON NAVIGATE
        if (rowtrip > 2 && rowtrip <= 5) {
            if ($("#hapus_trip").hasClass('hidden')) {
                $("#hapus_trip").removeClass('hidden');
            }
        } else {
            if (!$("#hapus_trip").hasClass('hidden')) {
                $("#hapus_trip").addClass('hidden');
            }
        }

        if (rowtrip >= 2 && rowtrip < 5) {
            if ($("#tambah_trip").hasClass('hidden')) {
                $("#tambah_trip").removeClass('hidden');
            }
        } else {
            if (!$("#tambah_trip").hasClass('hidden')) {
                $("#tambah_trip").addClass('hidden');
            }
        }


    });

    $('#activity').select2();
    const modalPengajuan = $('#modal-spd-pengajuan');
    const modalPengajuantes = $('#modal-spd-pengajuantes');
    const setXLModal = modalPengajuan.find('.modal-dialog').addClass('modal-xl');
    const modalTambahUm = $('#modal-spd-tambah-um');
    /** Datatable related */
    const multiTripTable = $('#table-multi-trip', modalPengajuan).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    $('#popApproval').popover({
        html: true,
        // title   : 'Info Approval <button type="button" id="close" class="close pull-right" onclick="$(&quot;#example&quot;).popover(&quot;hide&quot;);">&times;</button>',
        content: function (data) {
            var html = $('#template-approval').clone().removeAttr('id').removeClass('hidden');
            var list = $(this).data('list');
            $.each(list, function (i, v) {
                $(html).find('tbody').append('<tr><td>' + v + '</td></tr>')
            });
            var $popover_togglers = this;
            return html;
        }
    });

    multiTripTable.on('draw.dt', function () {
        multiTripTable.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    const uangmukaTable = $('#table-uangmuka', modalPengajuan).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    const uangmukaTambahTable = $('#table-uangmuka-tambah', modalTambahUm).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    const uangmukaTambahBaruTable = $('#table-uangmuka-tambah-baru', modalTambahUm).DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });

    $('#filter-date input', 'form[name="filter-history"]').on('change', function () {
        $('form[name="filter-history"]').attr('action', baseURL + 'travel/spd/pengajuan#tab-history');
        $('form[name="filter-history"]').submit();
    });

    $(document).on('dp.show', '.date', function () {
        updateDatetimePickerView();
    });

    $(document).on('dp.update', '.date', function () {
        updateDatetimePickerView();
    });

    function updateDatetimePickerView() {
        $('td.disabled[data-day]', modalPengajuan).each(function (i, v) {
            var isDisabled = jQuery.inArray(moment($(v).attr('data-day'), 'DD/MM/YYYY'), tanggal_travels);
            if (isDisabled) {
                $(v).addClass('traveled');
            }
        });
    }

    $(document).on('change', '.trip_end_checkbox:visible', function () {
        const modal = $('#modal-spd-pengajuan');
        if ($(this).is(':checked')) {
            let startDatetime = $('.trip_start_datetime', modal).data('DateTimePicker').date();
            if (KIRANAKU.isNullOrEmpty(startDatetime)) {
                startDatetime = $('.trip_start_datetime_multi', modal).data('DateTimePicker').date();
            }

            if (KIRANAKU.isNotNullOrEmpty(startDatetime)) {
                startDatetime.add(1, 'minute');
            }

            $('.trip_end_datetime', modal)
                .data('DateTimePicker')
                .minDate(startDatetime)
                .enable();
            $('.trip_end_datetime_multi', modal)
                .data('DateTimePicker')
                .enable();

            $('.trip_end_datetime input, .trip_end_datetime_multi input', modal)
                .prop('required', true)
                .prop('disabled', false);
            $('#booking_kembali', modal)
                .attr('checked', false)
                .attr('disabled', false)
                .trigger('change');

            $('.trip_start_datetime').trigger('dp.change');
        } else {
            $('.trip_end_datetime', modal)
                .data('DateTimePicker')
                .disable();
            $('.trip_end_datetime_multi', modal)
                .data('DateTimePicker')
                .disable();

            $('.trip_end_datetime, .trip_end_datetime_multi', modal)
                .parents('.has-error, .has-success')
                .find('.help-block')
                .remove();
            $('.trip_end_datetime, .trip_end_datetime_multi', modal)
                .parents('.has-error, .has-success')
                .removeClass('has-error')
                .removeClass('has-success');
            $('.trip_end_datetime input, .trip_end_datetime_multi input', modal)
                .prop('required', false)
                .prop('disabled', true)
                .val(null);
            $('#booking_kembali', modal).bootstrapToggle('off');
            $('#booking_kembali', modal)
                .attr('checked', false)
                .attr('disabled', true)
                .trigger('change');
        }
    });

    /** Form trip uang muka events handler */
    let uangmukaOptions = [];
    let durasiSpd = 0;
    let expenseNo = 0;

    KIRANAKU.convertNumeric($('#total_um', modalPengajuan));

    /** On change jumlah uangmuka pengajuan */
    $(document).on('change', '#total_um, .uangmuka-jumlah', function () {
        const modal = $('#modal-spd-pengajuan');
        const totalUM = AutoNumeric.getNumber('#modal-spd-pengajuan #total_um');
        let total = 0;
        $('input.uangmuka-jumlah', modal).each(function (i, v) {
            const val = AutoNumeric.getNumber('#' + $(v).attr('id'));
            total += parseFloat(val);
        });
        AutoNumeric.set('#modal-spd-pengajuan #sisa_um', totalUM - total);
        $('#sisa_um', modal).valid();
    });

    /** On change jumlah uangmuka tambahan*/
    $(document).on('change', '#total_umt, .uangmukat-jumlah', function () {
        const modal = modalTambahUm;
        const totalUM = KIRANAKU.numericGet($('#total_umt', modal));
        let total = 0;
        $('input.uangmukat-jumlah', modal).each(function (i, v) {
            const val = KIRANAKU.numericGet($(v)); //AutoNumeric.getNumber('#' + $(v).attr('id'));
            total += parseFloat(val);
        });
        KIRANAKU.numericSet($('#sisa_umt', modal), totalUM - total);
        $('#sisa_umt', modal).valid();
    });

    function manipulateTripUangmuka() {
        const modal = $('#modal-spd-pengajuan');
        const jenisSpd = $('input[name="tipe_trip"]:checked', modal).val();
        let startDate = null;
        let endDate = null;
        if ($('.trip_start_datetime_multi').data('DateTimePicker')) {
            startDate = $('.trip_start_datetime_multi').data('DateTimePicker').date();
        }
        if ($('.trip_end_datetime_multi').data('DateTimePicker')) {
            endDate = $('.trip_end_datetime_multi').data('DateTimePicker').date();
        }
        if (startDate !== null && endDate !== null) {
            $('#total_um').prop('readonly', false);
            $('#sisa_um').prop('disabled', false);
            durasiSpd = endDate.startOf('day').diff(startDate.startOf('day'), 'days') + 1;
            /** load api expenses */
            let country = 'ID';
            if ($('.select-country:visible').length)
                country = $('.select-country:visible').first().val();
            const activity = $('#activity', modalPengajuan).val();
            $.ajax({
                url: baseURL + 'travel/spd/get/expenses',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id_header: $('input[name="id_travel_header"]').val(),
                    country: country,
                    jenis_aktifitas: activity,
                    // company_code: company_code,
                },
                success: function (data) {
                    if (data.sts === 'OK') {
                        const { defaults, expenses } = data.data;
                        uangmukaOptions = expenses;
                        expenseNo = 0;

                        uangmukaTable
                            .clear()
                            .draw();
                        let totalUangmuka = 0;
                        let totalMaxUangmuka = 0;

                        if (defaults.length === 0) {
                            $('#total_um', modalPengajuan).prop('readonly', true);
                            $('#sisa_um', modalPengajuan).prop('disabled', true);
                            $('#div-uangmuka', modalPengajuan).addClass('hide');
                        } else {
                            $('#div-uangmuka', modalPengajuan).removeClass('hide');

                            $.each(defaults, function (i, d) {
                                let template = $('#uangmuka_template').html();
                                template = template.replaceAll('{no}', expenseNo++);
                                const newExpense = $(template);
                                const rateV = parseFloat(d.value) * 100;
                                $('.uangmuka-id', newExpense).val(d.id);
                                $('.uangmuka-fk', newExpense).val(JSON.stringify({
                                    kode_expense: d.kode_expense,
                                    amount_type: d.amount_type,
                                    end_date: d.end_date,
                                    tipe_travel: d.tipe_travel,
                                    tipe_company: d.tipe_company,
                                    tipe_aktifitas: d.tipe_aktifitas,
                                    country: d.country,
                                    region: d.region,
                                    jabatan: d.jabatan,
                                    statutory: d.statutory,
                                }));
                                $('.uangmuka-kode_expense', newExpense).val(d.kode_expense);
                                $('.uangmuka-label-expense', newExpense).html(d.tipe_expense_text);
                                $('.uangmuka-label-rate', newExpense).html(rateV);
                                $('.uangmuka-durasi', newExpense).val(durasiSpd);
                                $('.uangmuka-label-durasi', newExpense).html(durasiSpd);
                                $('.uangmuka-rate', newExpense).val(rateV);
                                const jumlah = parseFloat(KIRANAKU.isNullOrEmpty(d.jumlah, d.jumlah, 0));
                                totalUangmuka += jumlah;
                                $('.uangmuka-jumlah', newExpense).val(jumlah);
                                totalMaxUangmuka += (durasiSpd * rateV);
                                if (rateV != 0) {
                                    $('.uangmuka-jumlah', newExpense).attr('numeric-max', durasiSpd * rateV);
                                }
                                if (KIRANAKU.isNullOrEmpty(d.currency)) {
                                    $('.uangmuka-currency', newExpense).val('IDR');
                                    $('.uangmuka-label-currency', newExpense).html('IDR');
                                } else {
                                    $('.uangmuka-currency', newExpense).val(d.currency);
                                    $('.uangmuka-label-currency', newExpense).html(d.currency);
                                }

                                uangmukaTable
                                    .row
                                    .add($(newExpense))
                                    .draw();
                            });

                            KIRANAKU.numericSet('#total_um', totalUangmuka);
                        }

                        KIRANAKU.convertNumericLabel('#div-uangmuka .numeric-label');
                        KIRANAKU.convertNumeric('#div-uangmuka .numeric:not([readonly])');
                        KIRANAKU.convertNumericLabel('#div-uangmuka .numeric[readonly]', {
                            digitGroupSeparator: '.',
                            decimalCharacter: ',',
                            allowDecimalPadding: false,
                            readOnly: true,
                            decimalPlaces: 0
                        });
                    }
                }
            });
        } else {
            uangmukaTable
                .clear()
                .draw();
            $('#total_um').prop('readonly', true);
            $('#sisa_um').prop('disabled', true);
            $('#div-uangmuka').addClass('hide');
            durasiSpd = 0;
            expenseNo = 0;
        }
    }

    /** Form trip detail event handler **/
    $(document).on('click', '.detail_delete', function () {
        const totalRow = multiTripTable.rows().count();
        if (totalRow > 1) {
            multiTripTable
                .row($(this).parents('tr'))
                .remove()
                .draw();
        }
    });

    let detailNo = 1;

    $(document).on('click', '#detail_add_btn', function () {
        detailNo++;
        let template = $('#multitrip_template').html();
        template = template.replaceAll('{no}', detailNo);
        let newTrip = $(template);

        $(newTrip).find('.detail_delete')
            .removeClass('hide');

        multiTripTable
            .row
            .add($(newTrip))
            .draw();

        $('.select2', newTrip).select2();
        $('.trip_start_datetime_multi', newTrip).datetimepicker(datetimepickerOptions);
        $('.select-country', newTrip).trigger('change');
        manipulateTripDateTime();
    });

    function manipulateTripDateTime() {
        const jenisSpd = $('input[name="tipe_trip"]:checked').val();

        if (jenisSpd === 'multi') {
            const allDatetimepicker = $('.trip_start_datetime_multi');
            let lastDate = null;
            $.each(allDatetimepicker, function (i, dp) {
                let dpData = $(dp).data('DateTimePicker');
                if (KIRANAKU.isNotNullOrEmpty(lastDate)) {
                    dpData.minDate(lastDate);

                    if (moment(dpData.date()).isBefore(moment(lastDate))) {
                        dpData.date(lastDate);
                    }
                }
                if (dpData.date()) {
                    lastDate = dpData.date().add(1, 'minutes');
                }
            });

            if (KIRANAKU.isNotNullOrEmpty(lastDate)) {
                const endDateMulti = $('.trip_end_datetime_multi').data('DateTimePicker');
                endDateMulti
                    .minDate(lastDate);
                if (moment(endDateMulti.date()).isBefore(moment(lastDate))) {
                    endDateMulti
                        .date(lastDate);
                }
            }
        }
    }

    $(document).on('dp.change', '.trip_start_datetime', function ({ date, oldDate }) {
        var endDate = $('.trip_end_datetime', modalPengajuan).data('DateTimePicker');
        if (KIRANAKU.isNotNullOrEmpty(endDate)) {
            endDate = endDate.date();
            if (
                (KIRANAKU.isNotNullOrEmpty(date) && KIRANAKU.isNullOrEmpty(endDate))
                || (moment(endDate).isBefore(moment(date)) && KIRANAKU.isNotNullOrEmpty(endDate))
            ) {
                date = date.add(1, 'minutes');
                $('.trip_end_datetime', modalPengajuan).data('DateTimePicker')
                    .minDate(date)
                    .date(date);
            }
        }
    });

    $(document).on('dp.change', '.trip_start_datetime, .trip_start_datetime_multi', function ({ date, oldDate }) {
        manipulateTripDateTime();
        manipulateTripUangmuka();
        var thisvalue = $(this).val();
        var value_trans = $(this).closest(this).find('.select-trans').val();
        if (($.inArray('pesawat', value_trans) != -1 || $.inArray('kereta_bus', value_trans) != -1)
        ) { //&& thisvalue != undefined && thisvalue != ""
            $('.select-tiket').prop('selectedIndex', 0).trigger('change.select2');
        } else if (value_trans == undefined && $('.multi-id-detail').val() != undefined) {
            // $('.select-tiket').val('').trigger('change.select2');
        } else {
            $('.select-tiket').val('').trigger('change.select2');
        }
    });

    $(document).on('change', '.select-trans', function () {
        var value_trans = $(this).val();
        var value_tanggal = $(this).closest('.template-trip').find('.select-tanggal-berangkat-multi').val();
        if (($.inArray('pesawat', value_trans) != -1 || $.inArray('kereta_bus', value_trans) != -1)
        ) {
            $('.select-tiket').prop('selectedIndex', 0).trigger('change.select2');
        } else {
            $('.select-tiket').val('').trigger('change.select2');
        }
    });

    $(document).on('dp.change', '.trip_end_datetime, .trip_end_datetime_multi', function ({ date, oldDate }) {
        manipulateTripUangmuka();
    });

    /** Form pengajuan submit action **/
    KIRANAKU.createValidator($('.form-pengajuan'));
    KIRANAKU.createValidator($('.form-pengajuan-um-tambahan'));

    $(document).on('click', 'button[name="simpan_btn"]', function (e) {
        const modal = $('#modal-spd-pengajuan');
        var form = $('.form-pengajuan');
        const multiTripTotal = multiTripTable.rows().count();
        form.validate();
        var valid = form.valid();
        /** Validasi total multi trip */
        const tipeTrip = $('input[name="tipe_trip"]:checked').val();
        if (tipeTrip === 'multi') {
            if (!multiTripTotal) {
                KIRANAKU.alert('NotOK', 'Diwajibkan membuat tujuan apabila memilih jenis perjalanan Multi', 'warning', 'no');
                return false;
            }
        }
        /** Validasi transportasi */
        const totalTransportasi = $('input[name="transport[]"]:checked').length;

        //jejak
        if (valid) {
            var isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses();
                var formData = new FormData($(".form-pengajuan")[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/pengajuan',
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
                                $(modal).modal('hide');
                                location.reload();
                            });
                        } else {
                            KIRANAKU.endProses();
                            KIRANAKU.alert('OK', data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        KIRANAKU.hideLoading();
                        KIRANAKU.endProses();
                        KIRANAKU.alert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                KIRANAKU.alert(false, 'Silahkan tunggu sampai proses selesai.', 'info', 'no');
            }
        } else {
            if (validator.errorList.length) {
                let switchTab = true;
                $.each(validator.errorList, function (i, el) {
                    if ($('#modal-tab-pengajuan').has($(el.element)).length) {
                        switchTab = false;
                    }
                });
                if (switchTab) {
                    $('a[href="#modal-tab-um"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                } else {
                    $('a[href="#modal-tab-pengajuan"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                }
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', 'button[name="simpan_btn_tambah_um"]', function (e) {
        const modal = $('#modal-tab-um-tambahan');
        var form = $('.form-pengajuan-um-tambah');
        const multiTripTotal = multiTripTable.rows().count();
        form.validate();
        var valid = form.valid();

        if (valid) {
            var isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses();
                var formData = new FormData($(".form-pengajuan-um-tambah")[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/spd/save/um_tambahan',
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
                                $(modal).modal('hide');
                                location.reload();
                            });
                        } else {
                            KIRANAKU.endProses();
                            KIRANAKU.alert('OK', data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        KIRANAKU.hideLoading();
                        KIRANAKU.endProses();
                        KIRANAKU.alert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                KIRANAKU.alert(false, 'Silahkan tunggu sampai proses selesai.', 'info', 'no');
            }
        } else {
            if (validator.errorList.length) {
                let switchTab = true;
                $.each(validator.errorList, function (i, el) {
                    if ($('#modal-tab-pengajuan').has($(el.element)).length) {
                        switchTab = false;
                    }
                });
                if (switchTab) {
                    $('a[href="#modal-tab-um"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                } else {
                    $('a[href="#modal-tab-pengajuan"]').tab('show');
                    modal.scrollTop($('.has-error', modal).position().top);
                }
            }
        }
        e.preventDefault();
        return false;
    });

    /** List pengajuan delete clicked */
    $(document).on("change", ".select-tiket", function (e) {
        var thisvalue = $(this).val();
        var texthtml = $("option:selected", this).html();
        const modal = $(this).closest(".modal").attr("id");

        $(this).val(null).trigger('change.select2');

        if (thisvalue.length > 0) {
            if ($.inArray('0', thisvalue) != -1) { // if tanpa pemesanan
                $(this).select2('destroy');
                $(this).removeAttr('multiple');
                $(this).select2();
                $(this).val("0").trigger('change.select2');
            } else { // if selain tanpa pemesanan\
                $(this).select2('destroy');
                $(this).attr('multiple', 'multiple');
                $(this).select2();
                $(this).val(thisvalue).trigger('change.select2');
            }
        }

    });

    $(document).on("click", ".collapse-detail", function () {
        $(this).text($(this).text() == 'Tutup Detail' ? 'Lihat Detail' : 'Tutup Detail');
    });
});

function generate_data_trip_old(data, rowtrip, single, detail) {
    const { pengajuan, optpabrik, opttujuan, optcountry, opttransport } = data.data;
    var output = '<div class="row rowtrip" id="rowtrip' + rowtrip + '"   data-rowtrip="' + rowtrip + '">';

    var countries = '<option value="0">Silahkan pilih</option>';
    var selected_countries = "";
    $.each(optcountry, function (i, v) {
        if (v.country_code === detail.country) {
            selected_countries = 'selected';
        }
        countries += '<option value="' + v.country_code + '" ' + selected_countries + '>' + v.country_name + '</option>';
        selected_countries = "";
    });

    var tujuan_persa = '<option value="0">Silahkan pilih</option>';
    tujuan_persa += '<option value="lain">Lain-lain</option>';
    var selected_tujuan_persa = "";
    $.each(optpabrik, function (i, v) {
        if (v.value === detail.tujuan_persa) {
            selected_tujuan_persa = 'selected';
        }
        tujuan_persa += '<option value="' + v.value + '" ' + selected_tujuan_persa + '>' + v.label + '</option>';
        selected_tujuan_persa = "";
    });

    var tujuan = '<option value="0">Silahkan pilih</option>';
    tujuan += '<option value="lain">Lain-lain</option>';
    var selected_tujuan = "";
    $.each(opttujuan, function (i, v) {
        if (v.company_code === detail.tujuan && v.kode_jenis_aktifitas === pengajuan.activity) {
            selected_tujuan = 'selected';
        }
        tujuan += '<option value="' + v.company_code + '" ' + selected_tujuan + '>' + v.personal_subarea_text + '</option>';
        selected_tujuan = "";
    });



    var trans = "";
    $.each(opttransport, function (i, v) {
        trans += '<option value="' + v.kode + '">' + v.nama + '</option>';
    });

    // TUJUAN - country, persa, lokasi, lain-lain
    output += '		<div class="col-sm-3">';
    output += '		    <div class="form-group">';
    output += '		        <label for="">Tujuan</label>';
    output += '			        <div class="panel panel-default">';
    output += '			            <div class="panel-heading clickable panel-collapsed" style="padding: 7px 15px !important;">';
    output += '				            <h3 class="panel-title">Detail Tujuan</h3>';
    output += '			                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-down"></i></span>';
    output += '		                </div>';
    output += '		                <div class="panel-body" style="display:none;">';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Negara</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-globe colors-purple"></span></span>';
    output += '		                            <select class="form-control select2 select-country readonly" name="detail[' + rowtrip + '][country]" disabled>';
    output += countries;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Pabrik</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-building colors-tosca"></span></span>';
    output += '		                            <select class="form-control select2 select-area readonly" name="detail[' + rowtrip + '][tujuan_persa]" disabled>';
    output += tujuan_persa;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Area</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-map-marker colors-peach"></span></span>';
    output += '		                            <select class="form-control select2 select-tujuan readonly" name="detail[' + rowtrip + '][tujuan]" disabled>';
    output += tujuan;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group hide lain-lain">';
    output += '		                        <label for="validate-select">Lain - Lain</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-pencil colors-orange1"></span></span>';
    output += '		                            <input type="text" class="form-control input-tujuan_lain readonly" name="detail[' + rowtrip + '][tujuan_lain]" disabled>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                </div>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>'; //col-sm-4

    //keperluan
    output += '		    <div class="col-sm-3">';
    output += '		        <div class="form-group">';
    output += '		            <label for="validate-select">Keperluan</label>';
    output += '		            <div class="input-group">';
    output += '		                <span class="input-group-addon"><span class="fa fa-pencil colors-peach"></span></span>';
    output += '		                <textarea style="height:37px !important;" class="form-control input-keperluan readonly" name="detail[' + rowtrip + '][keperluan]" id="detail[' + rowtrip + '][keperluan]" disabled>' + detail.keperluan + '</textarea>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>';

    // Tanggal Keberangkatan
    output += '		    <div class="col-sm-3">';
    output += '		        <div class="form-group">';
    output += '		            <label for="validate-select">Tanggal Keberangkatan</label>';
    output += '		            <div class="input-group dt_start trip_start_datetime_multi">';
    output += '		                <span class="input-group-addon"><span class="fa fa-calendar colors-tosca"></span></span>';
    output += '		                <input type="text" data-date-format="DD.MM.YYYY HH:mm:ss" class="form-control select-tanggal-berangkat-multi readonly" name="detail[' + rowtrip + '][start]" disabled>';
    // output += '		                <input type="text" class="form-control" />';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>';

    // TRANSPORTASI - transportasi, pemesanan, penginapan
    output += '		<div class="col-sm-3">';
    output += '		    <div class="form-group">';
    output += '		        <label for="">Akomodasi</label>';
    output += '			        <div class="panel panel-default">';
    output += '			            <div class="panel-heading clickable panel-collapsed" style="padding: 7px 15px !important;">';
    output += '				            <h3 class="panel-title">Detail Akomodasi</h3>';
    output += '			                <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-down"></i></span>';
    output += '		                </div>';
    output += '		                <div class="panel-body" style="display:none;">';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Transportasi</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-plane colors-orange2"></span></span>';
    output += '		                            <select class="form-control select2 select-trans readonly" multiple="multiple" name="detail[' + rowtrip + '][trans][]" disabled>';
    output += trans;
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Jenis Tiket</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-ticket colors-peach"></span></span>';
    output += '		                            <select class="form-control select2 select-tiket readonly" multiple="multiple" name="detail[' + rowtrip + '][tiket][]" disabled>';
    output += '		                                <option value="berangkat">Berangkat</option>';
    output += '		                                <option value="pulang">Pulang</option>';
    output += '		                                <option value="0">Tanpa Pemesanan</option>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                    <div class="form-group">';
    output += '		                        <label for="validate-select">Penginapan</label>';
    output += '		                        <div class="input-group">';
    output += '		                            <span class="input-group-addon"><span class="fa fa-building colors-green"></span></span>';
    output += '		                            <select class="form-control select2 select-inap readonly" name="detail[' + rowtrip + '][inap]" disabled>';
    output += '		                                <option value="Mess">MESS</option>';
    output += '		                                <option value="Hotel">HOTEL</option>';
    output += '		                            </select>';
    output += '		                        </div>';
    output += '		                    </div>';
    output += '		                </div>';
    output += '		            </div>';
    output += '		        </div>';
    output += '		    </div>'; //col-sm-4

    output += '</div>';
    if (single == 'single') {
        $("#pengajuan_single").append(output);
        $("#pengajuan_single .select2").select2();
    } else {
        $("#form_multi").append(output);
        $("#form_multi .select2").select2();
    }
    $('.dt_start').datetimepicker({});

}

function generate_data_trip(detail) {
    let output_transport = "";
    const list_transport = (detail.transportasi + '').split(',');
    list_transport.forEach((val) => {
        output_transport += '<small class="label label-primary capitalize">' + val.replace("_", " ") + '</small> ';
    });

    let output_tiket = "";
    const list_transport_tiket = (detail.transportasi_tiket + '').split(',');
    list_transport_tiket.forEach((val) => {
        if (val && val != 0)
            output_tiket += '<small class="label label-default capitalize">' + val.replace("_", " ") + '</small> ';
    });

    let output = '<div class="row">';
    output += ' <div class="col-sm-12">';
    output += '     <div class="box box-default collapsed-box">';
    output += '         <div class="box-header with-border">';
    output += '             <h4 class="box-title">' + detail.tujuan_lengkap + '</h4>';
    output += '             <div>' + detail.tanggal_berangkat + '</div>';
    output += '             <div>' + output_transport + '</div>';
    output += '             <div class="box-tools pull-right">';
    output += '                 <button type="button" class="btn btn-box-tool collapse-detail" data-widget="collapse">Lihat Detail';
    output += '                 </button>';
    output += '             </div>';
    output += '         </div>';
    output += '         <div class="box-body" style="display: none;">';
    output += '             <div>Keperluan: ' + detail.keperluan + '</div>';
    output += '             <div>Jenis Tiket: ' + output_tiket + '</div>';
    output += '             <div>Penginapan: ' + (detail.jenis_penginapan).toUpperCase() + '</div>';
    output += '         </div>';
    output += '     </div>';
    output += ' </div>';
    output += '</div>';

    $("#list-trip").append(output);
    $('.dt_start').datetimepicker({});
    return;
}