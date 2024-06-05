var init_form_data;
$(document).ready(function () {
    $(document).on('click', '#head_booking_transportasi', function (e) {
        if (!$(this).hasClass('whitesmoke')) {
            if ($("#head_booking_penginapan").hasClass('whitesmoke')) {
                $("#head_booking_penginapan").removeClass('whitesmoke');
                $("#tab_booking_penginapan").addClass('hidden');
                $(".ws_temp_hotel").removeClass('whitesmoke');
            }

            $(this).addClass('whitesmoke');
            $(".ws_temp_trans").addClass('whitesmoke');
            $("#tab_booking_transportasi").removeClass('hidden');
        }
    });
    $(document).on('click', '#head_booking_penginapan', function (e) {
        if (!$(this).hasClass('whitesmoke')) {
            if ($("#head_booking_transportasi").hasClass('whitesmoke')) {
                $("#head_booking_transportasi").removeClass('whitesmoke');
                $("#tab_booking_transportasi").addClass('hidden');
                $(".ws_temp_trans").removeClass('whitesmoke');

            }

            $(this).addClass('whitesmoke');
            $(".ws_temp_hotel").addClass('whitesmoke');
            $("#tab_booking_penginapan").removeClass('hidden');
        }
    });

    // initial datatble

    var tabledt = $("#table-transportasi, #table-penginapan").DataTable({
        ordering: false,
        fixedColumns: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends", this).data("page") ? $(".my-datatable-extends", this).data("page") : 10,
        paging: $(".my-datatable-extends-order", this).data("paging") ? $(".my-datatable-extends-order", this).data("paging") : true
    });

    $('#refundable').iCheck('check');
    $('#refundable').prop('checked', true);

    $('#unrefundable').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    $('#refundable').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    $('#refundable').on('ifChecked', function () {
        $('#unrefundable').iCheck('uncheck');
        $('input[name="transport[1][status_tiket_refund]"]').val('refundable');
    });

    $('#unrefundable').on('ifChecked', function () {
        $('#refundable').iCheck('uncheck');
        $('input[name="transport[1][status_tiket_refund]"]').val('unrefundable');
    });

    // set default screen pemesan dan user
    // set for pemesan dan user
    var tipe_screen = $("#tipe_screen").val();
    if (tipe_screen != "pemesan") {
        adjustDatatableWidth();
        $("#submit_pesawat").addClass('hide');
        $("#cancel_pesawat").addClass('hide');
        $("#submit_taxi").addClass('hide');
        $("#cancel_taxi").addClass('hide');
        $("#submit_hotel").addClass('hide');
        $("#cancel_hotel").addClass('hide');

        $(':disabled').attr('disabled', true);
        $('.text_button_add').html('Lihat');

        $('#button-add-trans').addClass('hide');
        $('#button-add-hotel').addClass('hide');
        $('#simpan_btn_complete').addClass('hide');
    }

    $(document).on('click', '#head_booking_transportasi, #head_booking_penginapan', function (e, data) {
        resetform_trans_general();
        resetform_trans_pesawat();
        resetform_penginapan();

        $("#box_table_trans").removeClass('col-sm-8');
        $("#box_table_trans").removeClass('col-sm-8');
        $("#box_table_hotel").addClass('col-sm-12');
        $("#box_table_hotel").addClass('col-sm-12');

        $("#box_form_trans").addClass('hide');
        $("#box_form_hotel").addClass('hide');

        // hide all trans
        $("#form_book").addClass('hide');
        $("#fieldset_book").addClass('hide');
        $("#template-transport-pesawat1").addClass('hide');
        $("#template-transport-taxi").addClass('hide');
        $("#submit_pesawat").addClass('hide');
        $("#cancel_pesawat").addClass('hide');
        $("#submit_taxi").addClass('hide');
        $("#cancel_taxi").addClass('hide');

        // hide all hotel
        $("#form_book_hotel").addClass('hide');
        $("#fieldset_book_hotel").addClass('hide');
        $("#template-penginapan-hotel").addClass('hide');
        $("#submit_hotel").addClass('hide');
        $("#cancel_hotel").addClass('hide');

        adjustDatatableWidth();
        $('#simpan_btn_complete').removeClass('hide');
        $('#back_btn_complete').removeClass('hide');
    });

    $(document).on('click', '.transport_add_btn', function (e, data) {
        // add by ayy
        $('.transport_jadwal').datetimepicker(datetimepickerOptions);
        e.preventDefault();
        // reset form
        resetform_trans_general();
        resetform_trans_pesawat();
        resetform_penginapan();

        var tipe_trans = $(this).data('type');

        validator = $('.form-booking_' + tipe_trans).validate({
            ignore: '.hide input , .hide select, .hide textarea',
            errorElement: "em",
            errorPlacement: function (error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    if (element.parents('.form-group').length) {
                        error.appendTo(element.parents('.form-group > div'));
                    } else if (element.parents('td').length) {
                        error.appendTo(element.parents('td'));
                    }
                }
            },
            highlight: function (element, errorClass, validClass) {
                if ($(element).parents('.form-group').length) {
                    $(element).parents(".form-group > div").addClass("has-error").removeClass("has-success");
                } else if ($(element).parents('td').length) {
                    $(element).parents("td").addClass("has-error").removeClass("has-success");
                }
            },
            unhighlight: function (element, errorClass, validClass) {

                if ($(element).parents('.form-group').length) {
                    $(element).parents(".form-group > div").addClass("has-success").removeClass("has-error");
                } else if ($(element).parents('td').length) {
                    $(element).parents("td").addClass("has-success").removeClass("has-error");
                }
            }
        });

        $("#box_form_success_trans").removeClass('hide');
        $("#box_form_success_hotel").removeClass('hide');

        if (tipe_trans == "pesawat") {
            $("#box_table_trans").removeClass('col-sm-12');
            $("#box_table_trans").addClass('col-sm-8');
            $("#box_form_trans").removeClass('hide');

            $("#form_book").removeClass('hide');
            $("#fieldset_book").removeClass('hide');
            $("#template-transport-pesawat1").removeClass('hide');
            $("#template-transport-taxi").addClass('hide');

            //hide hotel
            $("#form_book_hotel").addClass('hide');
            $("#fieldset_book_hotel").addClass('hide');
            $("#template-penginapan-hotel").addClass('hide');
            $("#submit_hotel").addClass('hide');
            $("#cancel_hotel").addClass('hide');

            $("#submit_taxi").addClass('hide');
            $("#cancel_taxi").addClass('hide');
        } else if (tipe_trans == "taxi") {
            $("#box_table_trans").removeClass('col-sm-12');
            $("#box_table_trans").addClass('col-sm-8');
            $("#box_form_trans").removeClass('hide');

            $("#form_book").removeClass('hide');
            $("#fieldset_book").removeClass('hide');
            $("#template-transport-pesawat1").addClass('hide');
            $("#template-transport-taxi").removeClass('hide');

            //hide hotel
            $("#form_book_hotel").addClass('hide');
            $("#fieldset_book_hotel").addClass('hide');
            $("#template-penginapan-hotel").addClass('hide');
            $("#submit_hotel").addClass('hide');
            $("#cancel_hotel").addClass('hide');

            $("#submit_pesawat").addClass('hide');
            $("#cancel_pesawat").addClass('hide');
        } else if (tipe_trans == "hotel") {

            $("#box_table_trans").removeClass('col-sm-12');
            $("#box_table_trans").addClass('col-sm-8');
            $("#box_form_hotel").removeClass('hide');
            // form_book_hotel
            $("#form_book_hotel").removeClass('hide');
            $("#fieldset_book_hotel").removeClass('hide');
            $("#template-penginapan-hotel").removeClass('hide');
            // $("#template-transport-taxi").removeClass('hide');

            //hide trans
            $("#form_book").addClass('hide');
            $("#fieldset_book").addClass('hide');
            $("#template-transport-pesawat1").addClass('hide');
            $("#template-transport-taxi").addClass('hide');

            $("#submit_pesawat").addClass('hide');
            $("#cancel_pesawat").addClass('hide');
            $("#submit_taxi").addClass('hide');
            $("#cancel_taxi").addClass('hide');
        }

        $("#submit_" + tipe_trans).removeClass('hide');
        $("#cancel_" + tipe_trans).removeClass('hide');
        $(':disabled').attr('disabled', false);

        e.preventDefault();
        var dataform = $(this).data('pesan');
        // set status tiket
        $('.select-status-tiket').bootstrapToggle('on');
        if (dataform != "" && dataform != undefined) {
            var splitval = dataform.split("|");

            idHeader = splitval[0];
            idDetail = splitval[1];
            tujuan = splitval[2];
            tanggal_jln = splitval[3];
            jam_jln = splitval[4];
            jenis_trans = splitval[5];
            for_trans = splitval[6];
            datetime = splitval[7];
            idTrans = splitval[8];
            var trans_kembali = for_trans == "berangkat" ? 0 : 1;
            // set hidden val
            if (tipe_trans == "hotel") {
                data_tujuan_hidden = idDetail + "|" + datetime + "|" + tujuan + "~";
                $('input[name="tujuan_trip"]').val(data_tujuan_hidden);

                var status_primary = "primary";
                $('input[name="penginapan[1][status_tiket_primary]"]').val(status_primary);

                // set first field perjalanan
                $('select[name="penginapan[1][id_travel_detail]"]').html('');
                var option = new Option(tujuan, idDetail, false, false);
                option.setAttribute('data-jadwal', tanggal_jln);
                $('select[name="penginapan[1][id_travel_detail]"]')
                    .append(option)
                    .val(idDetail).trigger('change.select2');

                var datest = moment(tanggal_jln).format('DD.MM.YYYY');;
                $('.penginapan_start_date input')
                    .val(datest);

                $('.penginapan_start_date, .penginapan_end_date')
                    .datetimepicker(datepickerOptions);
                // .trigger('change');

                var status_primary = "primary";
                $('input[name="penginapan[1][status_tiket_primary]"]').val(status_primary);
            } else {
                $('input[name="transport[1][transport_kembali]"]').val(trans_kembali);
                data_tujuan_hidden = idDetail + "|" + datetime + "|" + tujuan + "~";
                $('input[name="tujuan_trip"]').val(data_tujuan_hidden);
                $('input[name="transport[1][jenis_kendaraan]"]').val(jenis_trans);
                var status_primary = "primary";
                $('input[name="transport[1][status_tiket_primary]"]').val(status_primary);

                // set first field perjalanan
                $('select[name="transport[1][id_travel_detail]"]').html('');
                var option = new Option(tujuan, idDetail, false, false);

                option.setAttribute('data-jadwal', tanggal_jln);
                $('select[name="transport[1][id_travel_detail]"]')
                    .append(option)
                    .val(idDetail).trigger('change.select2');
                $('input[name="transport[1][jadwal]"]').val(datetime);
                $('input[name="transport[1][status_tiket_refund]"]').val('Refundable');
                $('select[name="transport[1][vendor]"]').prop('selectedIndex', 0).change();
            }

            if (idTrans != "") {
                if (tipe_trans == "hotel") {
                    $('input[name="penginapan[1][id_travel_hotel]"]').val(idTrans);
                    $.ajax({
                        url: baseURL + 'travel/booking/get/booking_hotel',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_travel_hotel: idTrans,

                        },
                        success: function (data) {
                            if (data.sts === 'OK' && (data.data) != null && (data.data) != "null") {
                                var data_tujuan_hidden = "";
                                $.each(data, function (i, val) {
                                    if (jenis_trans == "hotel") {
                                        var enddate = moment(val.end_date).format('DD.MM.YYYY');
                                        $('input[name="penginapan[1][lampiran]"]').removeAttr("required");
                                        $('input[name="penginapan[1][end_date]"]').val(enddate);
                                        $('input[name="penginapan[1][nama_hotel]"]').val(val.nama_hotel);
                                        $('input[name="penginapan[1][pic_hotel]"]').val(val.PIC_hotel);
                                        $('input[name="penginapan[1][alamat]"]').val(val.alamat);
                                        $('textarea[name="penginapan[1][keterangan]"]').val(val.keterangan);
                                        $('input[name="penginapan[1][status_tiket_primary]"]').val(val.status_tiket_primary);

                                        if (val.lampiran == '') {
                                            let divFileinput = $('.fileinput');
                                            divFileinput.removeClass('fileinput-exists');
                                            divFileinput.addClass('fileinput-new');
                                            divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
                                        } else {
                                            let divFileinput = $('.fileinput');
                                            divFileinput.removeClass('fileinput-new');
                                            divFileinput.addClass('fileinput-exists');

                                            divFileinput.find('.fileinput-zoom').attr('href', '../../../assets/file/travel/' + val.lampiran);
                                            divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
                                        }
                                    }

                                    return false;
                                });

                            } else {
                                KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                            }
                            KIRANAKU.hideLoading();
                        },
                        complete: function (data) {

                        },
                        error: function (data) {
                            KIRANAKU.hideLoading();
                            KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                        }
                    });
                } else {
                    $('input[name="transport[1][id_travel_transport]"]').val(idTrans);
                    $.ajax({
                        url: baseURL + 'travel/booking/get/booking_trans',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_travel_transport: idTrans,

                        },
                        success: function (data) {
                            if (data.sts === 'OK' && (data.data) != null && (data.data) != "null") {
                                var data_tujuan_hidden = "";
                                $.each(data, function (i, val) {
                                    if (idTrans != "") {
                                        $('input[name="transport[1][id_travel_transport]"]').val(idTrans);
                                    }

                                    if (jenis_trans == "pesawat") {
                                        $('input[name="transport[1][lampiran]"]').removeAttr("required");

                                        $('input[name="transport[1][status_tiket_primary]"]').val(val.status_tiket_primary);
                                        $('input[name="transport[1][transport_kembali]"]').val(val.transport_kembali);
                                        $('select[name="transport[1][vendor]"]').val(val.vendor);
                                        $('input[name="transport[1][no_tiket]"]').val(val.no_tiket);

                                        if (val.harga != "" && val.harga != undefined) {
                                            var harga = parseInt(val.harga);
                                            harga = format(harga);
                                        } else {
                                            var harga = "";
                                        }
                                        $('input[name="transport[1][harga]"]').val(harga);
                                        $('input[name="transport[1][status_tiket_refund]"]').val(val.status_tiket_refund);

                                        if (val.status_tiket_refund == "refundable") {
                                            $('#refundable').prop('checked', true);
                                            $('#unrefundable').prop('checked', false);
                                            $('#unrefundable').iCheck({
                                                checkboxClass: 'icheckbox_flat-blue',
                                                radioClass: 'iradio_flat-blue'
                                            });

                                            $('#refundable').iCheck({
                                                checkboxClass: 'icheckbox_flat-green',
                                                radioClass: 'iradio_flat-green'
                                            });

                                        }
                                        if (val.status_tiket_refund == "unrefundable") {
                                            $('#refundable').prop('checked', false);
                                            $('#unrefundable').prop('checked', true);

                                            $('#unrefundable').iCheck({
                                                checkboxClass: 'icheckbox_flat-green',
                                                radioClass: 'iradio_flat-green'
                                            });

                                            $('#refundable').iCheck({
                                                checkboxClass: 'icheckbox_flat-blue',
                                                radioClass: 'iradio_flat-blue'
                                            });
                                        }

                                        $('textarea[name="transport[1][keterangan]"]').val(val.keterangan);

                                        // set for status tiket
                                        if (val.status_tiket == "Issued") {
                                            $('.select-status-tiket').bootstrapToggle('on');
                                        } else if (val.status_tiket == "Cancel") {
                                            $('.select-status-tiket').bootstrapToggle('off');
                                            $('textarea[name="transport[1][alasan_cancel]"]').val(val.alasan_cancel);
                                        } else {

                                        }

                                        // set for refundable =============================
                                        $('#unrefundable').iCheck({
                                            checkboxClass: 'icheckbox_flat-green',
                                            radioClass: 'iradio_flat-green'
                                        });

                                        $('#refundable').iCheck({
                                            checkboxClass: 'icheckbox_flat-green',
                                            radioClass: 'iradio_flat-green'
                                        });

                                        $('#refundable').on('ifChecked', function () {
                                            $('#unrefundable').iCheck('uncheck');
                                            $('input[name="transport[1][status_tiket_refund]"]').val('refundable');
                                        });

                                        $('#unrefundable').on('ifChecked', function () {
                                            $('#refundable').iCheck('uncheck');
                                            $('input[name="transport[1][status_tiket_refund]"]').val('unrefundable');
                                        });
                                        // end for refundable =============================

                                    }

                                    if (jenis_trans == "taxi") {
                                        $('input[name="transport[1][lampiran]"]').removeAttr("required");

                                        $('input[name="transport[1][status_tiket_primary]"]').val(val.status_tiket_primary);
                                        $('input[name="transport[1][transport_kembali]"]').val(val.transport_kembali);
                                        $('select[name="transport[1][vendor]"]').val(val.vendor);
                                        $('input[name="transport[1][no_tiket]"]').val(val.no_tiket);
                                        $('input[name="transport[1][status_tiket_refund]"]').val(val.status_tiket_refund);
                                        $('textarea[name="transport[1][keterangan]"]').val(val.keterangan);
                                    }

                                    if (val.lampiran == '') {
                                        let divFileinput = $('.fileinput');
                                        divFileinput.removeClass('fileinput-exists');
                                        divFileinput.addClass('fileinput-new');
                                        divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
                                    } else {
                                        let divFileinput = $('.fileinput');
                                        divFileinput.removeClass('fileinput-new');
                                        divFileinput.addClass('fileinput-exists');

                                        divFileinput.find('.fileinput-zoom').attr('href', '../../../assets/file/travel/' + val.lampiran);
                                        divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
                                    }

                                    return false;
                                });

                            } else {
                                KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                            }
                            KIRANAKU.hideLoading();
                        },
                        complete: function (data) {

                        },
                        error: function (data) {
                            KIRANAKU.hideLoading();
                            KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                        }
                    });
                }
            }

        } else {
            if (tipe_trans == "hotel") {
                var data_hotel = $(this).data('add');
                $('select[name="penginapan[1][id_travel_detail]"]').html('');

                $.each(data_hotel, function (i, val) {
                    var tujuan = val.tujuan;
                    var idDetail = val.id_travel_detail;
                    var tanggal_jln = moment(val.start_date).format('DD.MM.YYYY');

                    let option = new Option(tujuan, idDetail, false, false);
                    option.setAttribute('data-jadwal', tanggal_jln);
                    $('select[name="penginapan[1][id_travel_detail]"]')
                        .append(option)
                        .val(idDetail).trigger('change.select2');

                    $('.penginapan_start_date, .penginapan_end_date')
                        .datetimepicker(datepickerOptions);

                });
                $('input[name="penginapan[1][status_tiket_primary]"]').val('secondary');
            } else {
                var data_transport = $(this).data('add');
                var jenis_trans = $(this).data('type');
                $('select[name="transport[1][id_travel_detail]"]').html('');
                var myarraytujuan = [];
                var it = 0;
                var pul_dari = "";
                var pul_ke = "";
                var length_ar = data_transport.length;
                $.each(data_transport, function (i, val) {
                    if (jQuery.inArray(val.tujuan, myarraytujuan) == -1) {
                        if (it == 0) {
                            var splitke = (val.tujuan).split("ke");
                            pul_ke = splitke[0];
                        }
                        it++;
                        if (it == length_ar) {
                            var splitdari = (val.tujuan).split("ke");
                            pul_dari = splitdari[1];

                        }

                        var tujuan = val.tujuan;
                        var idDetail = val.id_travel_detail;
                        var tanggal_jln = moment(val.start_date).format('DD.MM.YYYY');

                        let option = new Option(tujuan, idDetail, false, false);
                        option.setAttribute('data-jadwal', tanggal_jln);
                        option.setAttribute('data-kebutuhan', val.tiket_keperluan);
                        $('select[name="transport[1][id_travel_detail]"]')
                            .append(option)
                            .val(idDetail).trigger('change');

                        // pulang
                        if (it == length_ar && val.tiket_keperluan != "pulang") {
                            var tujuan2 = pul_dari + " ke " + pul_ke;
                            var idDetail2 = val.id_travel_detail;
                            var tanggal_jln2 = moment(val.end_date).format('DD.MM.YYYY');
                            let option2 = new Option(tujuan2, idDetail2, false, false);
                            option2.setAttribute('data-jadwal', tanggal_jln2);
                            option2.setAttribute('data-kebutuhan', "pulang");
                            $('select[name="transport[1][id_travel_detail]"]')
                                .append(option2)
                                .val(idDetail2).trigger('change.select2');

                        }
                        myarraytujuan.push(tujuan);
                    }
                });

                $(document).on('change', 'select[name="transport[1][id_travel_detail]"]', function (e) {
                    var x = $(this);
                    var y = $(this).select2().find(":selected").data("id");
                    var kebutuhan = $(this).select2().find(":selected").data("kebutuhan");
                    if (kebutuhan == "berangkat") {
                        $('input[name="transport[1][transport_kembali]"]').val('');
                    } else if (kebutuhan == "pulang") {
                        $('input[name="transport[1][transport_kembali]"]').val(1);
                    }
                })

                $('input[name="transport[1][status_tiket_refund]"]').val('Refundable');
                $('input[name="transport[1][status_tiket_primary]"]').val('secondary');
                $('input[name="transport[1][jenis_kendaraan]"]').val(jenis_trans);
                $('select[name="transport[1][vendor]"]').prop('selectedIndex', 0).change();
                $('.transport_jadwal').datetimepicker(datetimepickerOptions);
            }
        }

        // set for pemesan dan user
        var tipe_screen = $("#tipe_screen").val();
        if (tipe_screen != "pemesan") {
            $("#submit_" + tipe_trans).addClass('hide');
            $("#cancel_" + tipe_trans).addClass('hide');
            $(':disabled').attr('disabled', true);
            $('.text_button_add').html('Lihat pemesanan');
        }
        //add by lha(set disable form  with login user)
        if (tipe_screen == "user") {
            $('.select-perjalanan').attr('disabled', true);
            $('.transport-vendor').attr('disabled', true);
            $('.transport-no_tiket').attr('disabled', true);
            $('.transport-harga').attr('disabled', true);
            $('.iradio_flat-blue').attr('disabled', true);
            $('#lampiran_pesawat').attr('disabled', true);
            $('.transport-keterangan').attr('disabled', true);
            $('.select-status-tiket').attr('disabled', true);
            $("#show_input_file").addClass('hide');
            $("#show_input_file_exist").removeClass('hide');
            $(".btn-facebook").addClass('hide');
            $(".btn-pinterest").attr('disabled', true);
        }

        adjustDatatableWidth();
        $('#simpan_btn_complete').addClass('hide');
        $('#back_btn_complete').addClass('hide');
    });

    // format number rupiah
    $('input[name="transport[1][harga]"]').keyup(function (e) {
        $(this).val(format($(this).val()));
    });
    var format = function (num) {
        var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
        if (str.indexOf(",") > 0) {
            parts = str.split(",");
            str = parts[0];
        }
        str = str.split("").reverse();
        for (var j = 0, len = str.length; j < len; j++) {
            if (str[j] != ".") {
                output.push(str[j]);
                if (i % 3 == 0 && j < (len - 1)) {
                    output.push(".");
                }
                i++;
            }
        }
        formatted = output.reverse().join("");
        return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
    };
    // end

    $(document).on('change', '.select-status-tiket', function (e) {
        var stat = $(this).prop('checked');
        var baris = $(this).data("baris");

        if (stat == false) {
            $('#div_alasan_cancel' + baris).show();
            $('textarea[name="transport[' + baris + '][alasan_cancel]"]').prop('required', true);
        } else {
            $('#div_alasan_cancel' + baris).hide();
            $('textarea[name="transport[' + baris + '][alasan_cancel]"]').val('');
            $('textarea[name="transport[' + baris + '][alasan_cancel]"]').prop('required', false);
        }
    });

    function resetform_trans_general() {
        adjustDatatableWidth();
        $('input[name="transport[1][transport_kembali]"]').val('');
        $('input[name="transport[1][id_travel_transport]"]').val('');
        $('input[name="transport[1][id_travel_hotel]"]').val('');
        $('input[name="transport[1][jenis_kendaraan]"]').val('');
        $('input[name="transport[1][status_tiket_primary]"]').val('');
        $('select[name="transport[1][id_travel_detail]"]').html('');
        $('select[name="transport[1][id_travel_detail]"]').val('').trigger('change.select2');
        $('input[name="transport[1][jadwal]"]').val('');
        $('input[name="transport[1][lampiran]"]').attr("required");
        let divFileinput = $('.fileinput');
        divFileinput.removeClass('fileinput-exists');
        divFileinput.addClass('fileinput-new');
        divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
    }

    function resetform_trans_pesawat() {
        adjustDatatableWidth();
        $('input[name="transport[1][lampiran]"]').attr("required");
        $('#tujuan_trip_pesawat').val('');
        $('select[name="transport[1][vendor]"]').val('');
        $('input[name="transport[1][no_tiket]"]').val('');
        $('input[name="transport[1][harga]"]').val('');
        $('input[name="transport[1][status_tiket_refund]"]').val('');
        $('textarea[name="transport[1][keterangan]"]').val('');
    }

    function resetform_penginapan() {
        adjustDatatableWidth();
        $('input[name="penginapan[1][lampiran]"]').attr("required");
        $('input[name="tujuan_trip"]').val('');
        $('input[name="penginapan[1][end_date]"]').val('');
        $('input[name="penginapan[1][nama_hotel]"]').val('');
        $('input[name="penginapan[1][pic_hotel]"]').val('');
        $('input[name="penginapan[1][alamat]"]').val('');
        $('textarea[name="penginapan[1][keterangan]"]').val('');
    }

    //aaaa
    const modalDeklarasi = $('#modal-spd-deklarasi');
    /** Datatable related */
    const detailMultiTripTable = $('#table-multi-trip').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    const biayaTable = $('#table-biaya').DataTable({
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false
    });
    // add by ayy
    const datetimepickerOptions = {
        useCurrent: false,
        format: 'DD.MM.YYYY HH:mm',
        showTodayButton: true,
        sideBySide: false,
        ignoreReadonly: true,

        showClose: true,
        showClear: true,
        toolbarPlacement: 'top',

        widgetPositioning: {
            horizontal: 'left',
            vertical: 'top'
        },
    };

    const datepickerOptions = {
        useCurrent: false,
        format: 'DD.MM.YYYY',
        showTodayButton: true,
        sideBySide: true,
        ignoreReadonly: true,

        showClose: true,
        showClear: true,
        toolbarPlacement: 'top',

        widgetPositioning: {
            horizontal: 'left',
            vertical: 'top'
        },
    };
    var mindate = moment().subtract(1, 'months').millisecond(0).second(0).minute(0).hour(0);
    $('.dt_start').datetimepicker({
        showTodayButton: true,
        sideBySide: true,
        minDate: mindate,
    });

    /** variable index detail */
    let biayaNo = 0;
    let detailNo = 0;

    let expensesOptions = [];
    let expensesCurrencyOptions = [];

    let totalDays = 0;

    $(document).on('click', '#cancel_pesawat , #cancel_taxi, #cancel_hotel', function (e) {
        resetform_trans_general();
        resetform_trans_pesawat();
        resetform_penginapan();

        $("#box_table_trans").removeClass('col-sm-8');
        $("#box_table_trans").addClass('col-sm-12');
        $("#box_table_hotel").removeClass('col-sm-8');
        $("#box_table_hotel").addClass('col-sm-12');

        $("#box_form_trans").addClass('hide');
        $("#box_form_hotel").addClass('hide');

        adjustDatatableWidth();
        $('#simpan_btn_complete').removeClass('hide');
        $('#back_btn_complete').removeClass('hide');

    })
    $(document).on('click', 'button[name="simpan_btn"]', function (e) {
        var jenis = $(this).data('jenis');
        const form = $('.form-booking_' + jenis);
        const transportList = $('#transport-list');
        const availablehotel = $('#availablehotel').val();
        const modalBookinginap = $('#modal-tab-booking-transportasi');

        form.validate();
        let valid = form.valid();
        /** Validasi transportasi */
        const totalTransportasi = $('.transport-booking', transportList).length;

        if (valid) {
            const isproses = KIRANAKU.isProses();
            if (isproses == 0) {
                KIRANAKU.startProses();
                const formData = new FormData(form[0]);
                KIRANAKU.showLoading();
                $.ajax({
                    url: baseURL + 'travel/booking/save_booking_spd',
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
                    if ($('#modal-tab-booking-transportasi').has($(el.element)).length) {
                        switchTab = false;
                    }
                });
                if (switchTab) {
                    $('a[href="#modal-tab-booking-penginapan"]').tab('show');
                } else {
                    $('a[href="#modal-tab-booking-transportasi"]').tab('show');
                }
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', 'button[name="simpan_btn_complete"]', function (e) {
        var form1 = $('.form-complete_trans');
        var jenis = $(this).data('jenis');
        var isComplete = $('#complete_trans_hotel').val();
        var idHeader = $('#complete_trans_hotel').data('idheader');

        form1.validate();
        let valid = form1.valid();
        /** Validasi transportasi */
        if (jenis == "submitall" && isComplete != '1') {
            valid = false;
            KIRANAKU.endProses();
            KIRANAKU.alert('OK', 'Pemesanan belum lengkap', 'error', 'no');
        } else if (jenis == "submitall" && isComplete == '1') {
            if (valid) {
                const isproses = KIRANAKU.isProses();
                if (isproses == 0) {
                    KIRANAKU.startProses();
                    var formData = new FormData(form1[0]);
                    KIRANAKU.showLoading();
                    $.ajax({
                        url: baseURL + 'travel/booking/save/final',
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
                                    history.go(-1);
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
                //
            }
        } else {
            KIRANAKU.endProses();
            KIRANAKU.alert('OK', 'Pemesanan belum lengkap', 'error', 'no');
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', 'button[name="back_btn_complete"]', function (e) {
        history.go(-1);
    });

    // get temporary file name for preview
    $(document).on("change.bs.fileinput", ".fileinput", function (e) {
        readURL($('input[type="file"]', $(this))[0], $('.fileinput-zoom', $(this)));
    });
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
