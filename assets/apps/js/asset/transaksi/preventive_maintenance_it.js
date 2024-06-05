$(document).ready(function () {
    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        if (oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        }
    };

    $('.input-daterange').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        autoclose: true
    });

    var datatable = datatables_ssp();

    //=======FILTER=======//
    $(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area, #main_status, #filter_operator", function () {
        datatables_ssp();
    });
    $(document).on("changeDate", "#tanggal_awal_filter, #tanggal_akhir_filter", function (e) {
        if (e.target == $("#tanggal_awal_filter")[0]) {
            var minDate = new Date(regenerateDatetimeFormat($(this).val(), "DD.MM.YYYY", "YYYY-MM-DD"));
            console.log(minDate);
            $('#tanggal_akhir_filter').datepicker('setStartDate', minDate);
        }
        if (e.target == $("#tanggal_akhir_filter")[0]) {
            var maxDate = new Date(regenerateDatetimeFormat($(this).val(), "DD.MM.YYYY", "YYYY-MM-DD"));
            console.log(maxDate);
            $('#tanggal_awal_filter').datepicker('setEndDate', maxDate);
        }

        datatables_ssp();
    });
    // $(document).on("click", "#filter_pm", function (e) {
    // datatables_ssp();
    // });
    // $(document).on("change", "#outstanding", function () {
    // $('#tanggal_awal_filter, #tanggal_akhir_filter').attr('disabled',$(this).is(':checked'));
    // datatables_ssp();
    // });

    $(document).on("change", ".cek_data", function (e) {
        var value = $(this).val();
        var tabel = $(this).data("tabel");
        var field = $(this).data("field");
        $.ajax({
            url: baseURL + 'asset/transaksi/get/cek',
            type: 'POST',
            dataType: 'JSON',
            data: {
                value: value,
                tabel: tabel,
                field: field
            },
            success: function (data) {
                if (data != '') {
                    $(".cek_data").val('');
                    swal('Warning', 'Data Sudah Terpakai', 'warning');

                }
            }
        });
    });

    $(document).on("click", ".delete", function (e) {
        var id = $(this).data('delete');
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                successCallback: function () {
                    $.ajax({
                        url: baseURL + "asset/maintenance/set/main",
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_main: id,
                            type: 'delete'
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                KIRANAKU.alert(data.sts, data.msg, "success", "no");
                                datatables_ssp();
                            } else {
                                KIRANAKU.alert("notOK", data.msg, "warning", "no");
                            }
                        },
                        error: function (data) {
                            KIRANAKU.alert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );
    });

    $(document).on("click", ".pm", function () {
        var modal = $('#pm_modal');
        var id_main = $(this).data("main");
        $.ajax({
            url: baseURL + 'asset/maintenance/get/' + pengguna + '/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_main: id_main
            },
            success: function (data) {
                $('#id_main', modal).val(data.id_main);
                $('#label_no_aset', modal).html(KIRANAKU.isNullOrEmpty(data.nomor, data.nomor, '-'));
                $('#label_nama_pabrik', modal).html(KIRANAKU.isNullOrEmpty(data.nama_pabrik, data.nama_pabrik, '-'));
                $('#label_nama_sub_lokasi', modal).html(KIRANAKU.isNullOrEmpty(data.nama_sub_lokasi, data.nama_sub_lokasi, '-'));
                $('#label_nama_area', modal).html(KIRANAKU.isNullOrEmpty(data.nama_area, data.nama_area, '-'));
                $('#label_nama_pic', modal).html(KIRANAKU.isNullOrEmpty(data.nama_pic, data.nama_pic, '-'));
                $('#label_jadwal_service', modal).html(KIRANAKU.isNullOrEmpty(data.jadwal_service, moment(data.jadwal_service).format('DD.MM.YYYY'), '-'));
                resetTableItems();
                $.each(data.detail, function (i, v) {
                    tableItems.DataTable().row.add([
                        v.nama_jenis_detail,
                        v.nama_periode_detail,
                        '<input type="text" class="form-control" name="keterangan[' + v.id_main_detail + ']" id="keterangan_' + v.id_main_detail + '" />'
                        + '<input class="cek_cb" type="hidden" name="cek[' + v.id_main_detail + ']" id="cek_' + v.id_main_detail + '" />',
                        v.id_main_detail
                    ]);
                });
                tableItems.DataTable().draw();
            },
            complete: function () {
                $('#pm_modal').modal('show');
            }

        });
    });

    $(document).on("click", ".perbaikan", function () {
        var modal = $('#perbaikan_modal');
        var id_main = $(this).data("main");
        $.ajax({
            url: baseURL + 'asset/maintenance/get/' + pengguna + '/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_main: id_main
            },
            success: function (data) {
                $('#id_main', modal).val(data.id_main);
                $('#label_no_aset', modal).html(KIRANAKU.isNullOrEmpty(data.nomor, data.nomor, '-'));
                $('#label_nama_pabrik', modal).html(KIRANAKU.isNullOrEmpty(data.nama_pabrik, data.nama_pabrik, '-'));
                $('#label_nama_sub_lokasi', modal).html(KIRANAKU.isNullOrEmpty(data.nama_sub_lokasi, data.nama_sub_lokasi, '-'));
                $('#label_nama_area', modal).html(KIRANAKU.isNullOrEmpty(data.nama_area, data.nama_area, '-'));
                $('#label_nama_pic', modal).html(KIRANAKU.isNullOrEmpty(data.nama_pic, data.nama_pic, '-'));
                $('#label_jadwal_service', modal).html(KIRANAKU.isNullOrEmpty(data.jadwal_service, moment(data.jadwal_service).format('DD.MM.YYYY'), '-'));
            },
            complete: function () {
                $('#perbaikan_modal').modal('show');
            }

        });
    });

    $(document).on("click", "#add_perbaikan_item", function () {

    });

    $(document).on("click", ".history", function () {
        var id_aset = $(this).data("aset");
        $.ajax({
            url: baseURL + 'asset/maintenance/get/' + pengguna + '/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: id_aset
            },
            success: function (data) {
                resetTableHistory();
                $.each(data.data, function (i, v) {
                    tableHistory.DataTable().row.add([
                        KIRANAKU.isNullOrEmpty(v.tanggal_mulai, moment(v.tanggal_mulai).format('DD.MM.YYYY'), '-'),
                        v.jenis_maintenance,
                        v.pm_item,
                        v.nama_pabrik,
                        v.nama_sub_lokasi,
                        v.nama_area,
                        v.nama_pic,
                    ]);
                });
                tableHistory.DataTable().draw();
                //buat history Asset
                var no = 0;
                $.each(data.data_asset, function (i, v) {
                    no = no + 1;
                    tableHistoryAsset.DataTable().row.add([
                        no,
                        v.jenis_perubahan,
                        KIRANAKU.isNullOrEmpty(v.tanggal_buat, moment(v.tanggal_buat).format('DD.MM.YYYY'), '-'),
                        v.label_status_awal,
                        v.label_status_akhir,
                        v.alasan,
                    ]);
                });
                tableHistoryAsset.DataTable().draw();

            },
            complete: function () {
                $('#history_modal').modal('show');
            }

        });
    });

    function get_data_kategori(id_kategori) {
        $.ajax({
            url: baseURL + 'asset/transaksi/get/kategori/it',
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                if (data) {
                    var output = '';
                    $.each(data, function (i, v) {
                        output += '<option value="' + v.id_kategori + '">' + v.nama + '</option>';
                    });
                    $("select[name='id_kategori']").html(output);
                }
            },
            complete: function () {
                if (id_kategori) {
                    $("select[name='id_kategori']").val(id_kategori).trigger("change.select2");
                }
            }
        });
    }

    function get_data_jenis(id_jenis, input_name = 'id_jenis') {
        $.ajax({
            url: baseURL + 'asset/transaksi/get/jenis/it',
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                if (data) {
                    var output = '';
                    $.each(data, function (i, v) {
                        output += '<option value="' + v.id_jenis + '">' + v.nama + '</option>';
                    });
                    $("select[name='" + input_name + "']").html(output);
                }
            },
            complete: function () {
                if (id_jenis) {
                    $("select[name='" + input_name + "']").val(id_jenis).trigger("change.select2");
                }
            }
        });
    }

    function get_option_jenis(id_kategori, input_name = 'id_jenis') {
        $.ajax({
            url: baseURL + 'asset/transaksi/get/jenis/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_kategori: id_kategori
            },
            success: function (data) {
                if (data) {
                    var output = '';
                    $.each(data, function (i, v) {
                        output += '<option value="' + v.id_jenis + '">' + v.nama + '</option>';
                    });
                    $("select[name='" + input_name + "']").html(output);
                }
            },
            complete: function () {
                if (id_jenis) {
                    $("select[name='" + input_name + "']").val(id_jenis).trigger("change.select2");
                }
            }
        });
    }

    function get_data_lokasi(id_lokasi) {
        $.ajax({
            url: baseURL + 'asset/transaksi/get/lokasi',
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                if (data) {
                    var output = '';
                    $.each(data, function (i, v) {
                        output += '<option value="' + v.id_lokasi + '">' + v.nama + '</option>';
                    });
                    $("select[name='id_lokasi']").html(output);
                }
            },
            complete: function () {
                if (id_lokasi) {
                    $("select[name='id_lokasi']").val(id_lokasi).trigger("change.select2");
                }
            }
        });
    }

    $(document).on("click", "form[name='form-transaksi-pm'] button[name='action_btn']", function (e) {
        e.preventDefault();

        var empty_form = validate('.form-transaksi-pm', true);
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                $('.form-transaksi-pm .cek_cb').val('n');
                var rows_selected = $('#table-maintenance-item').DataTable().column(3).checkboxes.selected();

                if (rows_selected.length < $('.form-transaksi-pm .cek_cb').length) {
                    KIRANAKU.alert('OK', "Harap selesaikan semua item maintenance yang disediakan.", 'warning', 'no');
                    $("input[name='isproses']").val(0);
                } else {
                    $.each(rows_selected, function (index, rowId) {
                        // Create a hidden element
                        $('.form-transaksi-pm #cek_' + rowId).val('y');
                    });

                    var formData = new FormData($(".form-transaksi-pm")[0]);
                    $.ajax({
                        url: baseURL + 'asset/maintenance/save/' + pengguna + '/pm',
                        type: 'POST',
                        dataType: 'JSON',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            $("input[name='isproses']").val(0);
                            if (data.sts == 'OK') {
                                KIRANAKU.alert(data.sts, data.msg, 'success', 'no');
                                datatables_ssp();
                            } else {
                                KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            $("input[name='isproses']").val(0);
                        }
                    });
                    $("input[name='isproses']").val(0);
                }

            } else {
                KIRANAKU.alert('OK', "Silahkan tunggu proses selesai.", 'info', 'no');
            }
        }
        return false;

    });

    $(document).on("click", "form[name='form-transaksi-perbaikan'] button[name='action_btn']", function (e) {
        e.preventDefault();

        var empty_form = validate('.form-transaksi-perbaikan', true);
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                var formData = new FormData($(".form-transaksi-perbaikan")[0]);
                $.ajax({
                    url: baseURL + 'asset/maintenance/save/' + pengguna + '/perbaikan',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $("input[name='isproses']").val(0);
                        if (data.sts == 'OK') {
                            KIRANAKU.alert(data.sts, data.msg, 'success', 'no');
                            datatables_ssp();
                        } else {
                            KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                    }
                });
                $("input[name='isproses']").val(0);
            } else {
                KIRANAKU.alert('OK', "Silahkan tunggu proses selesai.", 'info', 'no');
            }
        }
        return false;

    });

    $(document).on("click", "form[name='form-transaksi-jadwal-pm'] button[name='action_btn']", function (e) {
        var empty_form = validate('.form-transaksi-jadwal-pm');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                // var rows_selected = $('#table-tab-assets').DataTable().column(0).checkboxes.selected();
                var rows_selected = $('#table-tab-assets input.dt-checkboxes:checkbox:checked');

                // Iterate over all selected checkboxes
                $('input.id_aset').remove();
                $.each(rows_selected, function (index, rowId) {
                    // Create a hidden element
                    $('.form-transaksi-jadwal-pm .modal-footer').append(
                        $('<input>')
                            .attr('class', 'id_aset')
                            .attr('type', 'hidden')
                            .attr('name', 'id_aset[]')
                            .val($(rowId).val())
                    );
                });

                var formData = new FormData($(".form-transaksi-jadwal-pm")[0]);
                $.ajax({
                    url: baseURL + 'asset/maintenance/save/it/bulk_jadwal',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $("input[name='isproses']").val(0);
                        if (data.sts == 'OK') {
                            KIRANAKU.alert(data.sts, data.msg, 'success');
                        } else {
                            KIRANAKU.alert(data.sts, data.msg, 'error', 'no', data.msg);
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                    }
                });
            } else {
                KIRANAKU.alert('OK', "Silahkan tunggu proses selesai.", 'info', 'no');
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on("click", "form[name='form-transaksi-jadwal-perbaikan'] button[name='action_btn']", function (e) {
        var empty_form = validate('.form-transaksi-jadwal-perbaikan');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                var formData = new FormData($(".form-transaksi-jadwal-perbaikan")[0]);
                $.ajax({
                    url: baseURL + 'asset/maintenance/save/' + pengguna + '/perbaikan_jadwal',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        $("input[name='isproses']").val(0);
                        if (data.sts == 'OK') {
                            KIRANAKU.alert(data.sts, data.msg, 'success');
                        } else {
                            KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                    }
                });
            } else {
                KIRANAKU.alert('OK', "Silahkan tunggu proses selesai.", 'info', 'no');
            }
        }
        e.preventDefault();
        return false;
    });

    //set on change id_kategori
    $(document).on("change", "#id_kategori", function (e) {
        // resetTableAssets();
        $('#id_periode').html("<option></option>");
        var id_kategori = $(this).val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/jenis/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_kategori: id_kategori
            },
            success: function (data) {
                var value = '';
                value += '<option></option>';
                $.each(data, function (i, v) {
                    value += '<option value="' + v.id_jenis + '">' + v.nama + '</option>';
                });
                $('#id_jenis').html(value);
            }
        });
    });

    $(document).on("change", "#id_jenis", function (e) {
        var id_jenis = $(this).val();
        resetTableAssets();
        if (!KIRANAKU.isNullOrEmpty(id_jenis)) {
            $.ajax({
                url: baseURL + 'asset/transaksi/get/periode/it',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id_jenis: id_jenis
                },
                success: function (data) {
                    var value = '';
                    value += '<option></option>';
                    $.each(data, function (i, v) {
                        value += '<option value="' + v.id_periode + '">' + v.nama + '</option>';
                    });
                    $('#id_periode').html(value);
                },
                complete: function () {
                    $("#id_pabrik").val('').trigger("change.select2");
                    // $('#id_pabrik').html("<option></option>");
                }
            });

            // refreshListPerawatan({
            // jenis: [id_jenis],
            // // jenis_tindakan: 'perawatan'
            // })
        } else {
            $('#id_periode').html("<option></option>");

        }
    });

    $(document).on("change", "#id_pabrik", function (e) {
        var id_jenis = $('#id_jenis').val();
        var id_pabrik = $(this).val();
        resetTableAssets();
        if (!KIRANAKU.isNullOrEmpty(id_pabrik) && !KIRANAKU.isNullOrEmpty(id_jenis)) {
            refreshListPerawatan({
                jenis: [id_jenis],
                pabrik: id_pabrik,
                // jenis_tindakan: 'perawatan'
            })
            $('#id_periode').prop('disabled', false);
            $('#jadwal_service').prop('disabled', false);

        }
    });

    $('#add_modal').on('shown.bs.modal', function () {
        $('#id_pabrik')
            .val(null)
            .trigger('change');
        $('#id_pabrik').select2({
            dropdownParent: $("#add_modal")
        });
    });

    // refresh table list perawatan
    function refreshListPerawatan(props) {
        $.ajax({
            url: baseURL + 'asset/maintenance/get/' + pengguna + '/jadwal',
            type: 'POST',
            dataType: 'JSON',
            data: props,
            success: function (data) {
                if (data.recordsFiltered > 0) {
                    $('a[href="#tab-assets"]').parents('li').addClass('in');
                    $('a[href="#tab-assets"]').parents('li').removeClass('hide');
                    var dataAset = Array.from(new Set(data.data.map(s => s.id_aset)))
                        .map(id => {
                            return data.data.find(s => s.id_aset === id);
                        });
                    $('#jumlah_asset').html(dataAset.length);
                    $.each(dataAset, function (i, d) {
                        table.DataTable().row.add([
                            d.id_aset,
                            d.id_main,
                            KIRANAKU.isNullOrEmpty(d.detail_aset_it, d.detail_aset_it.split('||').join('<br/>'), '-'),
                            d.nama_pabrik,
                            d.nama_user,
                            d.nama_kondisi,
                            (((d.main_status != 'noschedule' && d.main_status != 'complete') && d.jenis_tindakan == 'perawatan') ? 'Sudah terjadwal' : ''),
                            d.nama_operator
                        ]);
                    });
                    table.DataTable().draw();
                }
            }
        });
    }

    //set on change kategori
    $(document).on('change', '#kategori', function (e) {
        get_option_jenis($(this).val(), 'jenis');
    });
    //set on change jenis
    $(document).on("change", "#jenis", function (e) {
        var jenis = $("#jenis").val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/merk/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                jenis: jenis
            },
            success: function (data) {
                var value = '';
                $.each(data, function (i, v) {
                    value += '<option value="' + v.id_merk + '">[' + v.nama_jenis + '] ' + v.nama + '</option>';
                });
                $('#merk').html(value);
            }
        });
    });
    //set on change lokasi
    $(document).on("change", "#lokasi", function (e) {
        var lokasi = $("#lokasi").val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/area',
            type: 'POST',
            dataType: 'JSON',
            data: {
                lokasi: lokasi
            },
            success: function (data) {
                var value = '';
                $.each(data, function (i, v) {
                    value += '<option value="' + v.id_area + '">[' + v.nama_lokasi + '] ' + v.nama + '</option>';
                });
                $('#area').html(value);
            }
        });
    });

    //export to excel
    $('.my-datatable-extends-order').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Penilaian',
                download: 'open',
                orientation: 'landscape',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }
        ],
        scrollX: true
    });

    //open modal for add pm
    $(document).on("click", "#add_button_pm", function (e) {
        resetForm_use($('form[name="form-transaksi-jadwal-perbaikan"]'));
        $('#add_modal').modal('show');
    });

    //open modal for add perbaikan
    function formatRepoSelection(aset) {
        $('input[name="id_jenis"]').val(aset.id_jenis);
        $('input[name="kode"]').val(aset.kode);
        return aset.text || aset.detail_aset_it.split('||').join(' - ');
    }

    function formatSearchAset(aset) {
        if (aset.loading) {
            return aset.text;
        }

        var markup = "<div class='select2-result-aset clearfix'>" + aset.detail_aset_it.split('||').join('<br/>') + "</div>";

        return markup;
    }

    $(document).on("click", "#add_button_perbaikan", function (e) {
        resetForm_use($('form[name="form-transaksi-jadwal-perbaikan"]'));
        $('#id_aset_ajax').select2({
            ajax: {
                delay: 250,
                url: baseURL + 'asset/maintenance/get/' + pengguna + '/jadwal',
                method: 'POST',
                data: function (params) {
                    return {
                        term: params.term,
                        _type: 'query',
                        // jenis_tindakan: 'perbaikan'
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    // Tranforms the top-level key of the response object from 'items' to 'results'

                    data.data.forEach(function (v) {
                        v.id = v.id_aset;
                        v.text = v.detail_aset_it.split('||').join(' - ');
                    });

                    let result = Array.from(new Set(data.data.map(s => s.id)))
                        .map(id => {
                            return data.data.find(s => s.id === id);
                        });

                    return {
                        results: result
                    };
                },
                cache: true
            },
            placeholder: 'Cari aset (kode barang atau nomor sap)',
            minimumInputLength: 3,
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: formatSearchAset,
            templateSelection: formatRepoSelection
        });
        $('#add_modal_perbaikan').modal('show');
    });

    $(document).on("change", "#id_aset_ajax", function (e) {
        var kode = $("input[name=kode]").val();
        $.ajax({
            url: baseURL + 'asset/maintenance/get/' + pengguna + '/agent',
            type: 'POST',
            dataType: 'JSON',
            data: {
                kode: kode
            },
            success: function (data) {
                var value = '';
                value += '<option></option>';
                $.each(data, function (i, v) {
                    value += '<option value="' + v.agent + '">[' + v.agent + '] ' + v.nama + '</option>';
                });
                $('#operator').html(value);
            }
        });
    });

    function resetForm_use($form) {
        resetTableAssets();
        $('.select2modal').val(null).trigger('change');
        $('.select2modal', $form).select2('destroy').find('option').prop('selected', false).end().select2();
        $form.find('input:text, input:password, input:file,  textarea').val("");
        $form.find('select').val(0);
        $form.find('input:radio, input:checkbox')
            .removeAttr('checked').removeAttr('selected');
    }

    //date pitcker
    // $('.tanggal').datepicker({
    //     format: 'dd.mm.yyyy',
    //     startDate: moment().format('DD.MM.YYYY'),
    //     todayHighlight: true,
    //     autoclose: true,
    //     weekStart: 1,
    //     language: 'id'
    // });
    $('.tanggal').datepicker({
        format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
    });

    $('input[name="tanggal_mulai"],input[name="jadwal_service"]').datepicker({
        format: 'dd.mm.yyyy',
        startDate: moment().subtract(1, 'month').format('DD.MM.YYYY'),
        todayHighlight: true,
        autoclose: true,
        weekStart: 1,
        language: 'id'
    });
    $('input[name="tanggal_mulai"]').on('changeDate', function (e) {
        $('#tanggal_selesai').datepicker('setStartDate', e.date);
        if (isNaN($('#tanggal_selesai').datepicker('getDate'))) {
            $('#tanggal_selesai').datepicker('setDate', e.date);
        }
    });
    $('input[name="tanggal_selesai"]').datepicker({
        format: 'dd.mm.yyyy',
        startDate: moment().format('DD.MM.YYYY'),
        todayHighlight: true,
        autoclose: true,
        weekStart: 1,
        language: 'id'
    });

    $(document).on('change', '#jenis_maintenance', function () {
        if ($(this).val() == 'perbaikan') {
            $('.div_items').addClass('hide');
            $('.div_rusak').removeClass('hide');
            $('.div_rusak input, .div_rusak textarea').attr('required', 'required');
        }
        else {
            $('.div_items').removeClass('hide');
            $('.div_rusak').addClass('hide');
            $('.div_rusak input, .div_rusak textarea').attr('required', null);
        }
    });

    var table = $('#table-tab-assets').dataTable({
        destroy: true,
        'columnDefs': [
            {
                'targets': 0,
                'checkboxes': {
                    'selectRow': true,
                    'selectCallback': function () {

                    }
                },
                'orderable': false,
                'createdCell': function (td, cellData, rowData, row, col) {
                    if (!KIRANAKU.isNullOrEmpty(rowData[6]))
                        $(td).find("input.dt-checkboxes").attr("disabled", "disabled");//this.api().cell(td).checkboxes.disable();

                    $(td).find("input.dt-checkboxes").prop('value', cellData);
                }
            },
            {
                'targets': 1,
                'visible': false
            }
        ],
        'order': [[1, 'asc']],
        'select': {
            'style': 'multi'
        }
    });

    function resetTableAssets() {
        $('a[href="#tab-assets"]').parents('li').removeClass('in');
        $('a[href="#tab-assets"]').parents('li').addClass('hide');
        $('.nav-tabs a[href="#tab-data"]').tab('show');
        table.DataTable().clear();
    }

    var tableItems = $('#table-maintenance-item').dataTable({
        destroy: true,
        'drawCallback': function () {
            $('#table-maintenance-item input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-blue'
            });
        },
        'columnDefs': [
            {
                'targets': 3,
                'checkboxes': {
                    'selectAll': false,
                    'selectRow': true,
                    'selectCallback': function (nodes, selected) {
                        $('input[type="checkbox"]', nodes).iCheck('update');
                    },
                    'selectAllCallback': function (nodes, selected, indeterminate) {
                        $('input[type="checkbox"]', nodes).iCheck('update');
                    }
                }
            }
        ],
        'order': [[0, 'asc']],
        'select': {
            'style': 'multi'
        },
        'paging': false,
        'searching': false
    });

    var tableHistory = $('#table-tab-history-pm').dataTable({
        destroy: true,
        'order': [[0, 'asc']]
    });
    var tableHistoryAsset = $('#table-tab-history-asset').dataTable({
        destroy: true,
        'order': [[0, 'asc']]
    });


    $('#table-maintenance-item').on('ifChanged', '.dt-checkboxes', function (event) {
        var cell = $('#table-maintenance-item').DataTable().cell($(this).closest('td'));
        cell.checkboxes.select(this.checked);
    });

    function resetTableItems() {
        tableItems.DataTable().clear();
    }

    function resetTableHistory() {
        tableHistory.DataTable().clear();
    }
});

let tempTanggal_akhir = null;
function datatables_ssp() {
    $('.modal').modal('hide');
    var jenis = $("#jenis").val();
    var merk = $("#merk").val();
    var pabrik = $("#pabrik").val();
    var lokasi = $("#lokasi").val();
    var area = $("#area").val();
    var main_status = $("#main_status").val();
    var tanggal_awal = $("#tanggal_awal_filter").val();
    var tanggal_akhir = $("#tanggal_akhir_filter").val();
    var outstanding = $("#outstanding").is(':checked');
    var filter_operator = $("#filter_operator").val();


    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        stateSave: true,
        "scrollX": true,
        pageLength: 10,
        // initComplete: function () {
        // var api = this.api();
        // $('#sspTable_filter input')
        // .off('.DT')
        // .on('input.DT', function () {
        // api.search(this.value).draw();
        // });
        // },
        // oLanguage: {
        // sProcessing: "Please wait..."
        // },
        // processing: true,
        // serverSide: true,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input').attr("placeholder", "Press enter to start searching");
            $('#sspTable_filter input').attr("title", "Press enter to start searching");
            $('#sspTable_filter input')
                .off('.DT')
                .on('keypress change', function (evt) {
                    console.log(evt.type);
                    // if(evt.type == "keypress" && evt.keyCode == 13) {
                    //     api.search(this.value).draw();
                    // }
                    if (evt.type == "change") {
                        api.search(this.value).draw();
                    }
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,

        ajax: {
            url: baseURL + 'asset/maintenance/get/' + pengguna + '/jadwal',
            type: 'POST',
            data: function (data) {
                data.jenis = jenis;
                data.merk = merk;
                data.pabrik = pabrik;
                data.lokasi = lokasi;
                data.area = area;
                data.main_status = main_status;
                if (!outstanding) {
                    data.tanggal_awal = tanggal_awal;
                    data.tanggal_akhir = tanggal_akhir;
                } else {
                    data.tanggal_akhir = moment().subtract('days', 1).format('DD.MM.YYYY');
                }
                data.filter_operator = filter_operator;
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "v_aset_pm.id_main",
                "name": "id_main",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.id_aset;
                },
                "visible": false
            },
            {
                "data": "v_aset_pm.detail_aset_it",
                "name": "detail_aset_it",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.detail_aset_it.split('||').join('<br/>');
                }
            },
            {
                "data": "v_aset_pm.nama_pabrik",
                "name": "nama_pabrik",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.nama_pabrik;
                }
            },
            {
                "data": "v_aset_pm.nama_sub_lokasi",
                "name": "nama_sub_lokasi",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_sub_lokasi;
                }
            },
            {
                "data": "v_aset_pm.nama_area",
                "name": "nama_area",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.nama_area;
                }
            },
            {
                "data": "v_aset_pm.nama_pic",
                "name": "nama_pic",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_pic;
                }
            },
            {
                "data": "v_aset_pm.jadwal_service",
                "name": "jadwal_service",
                "width": "5%",
                "render": function (data, type, row) {
                    let jadwal = "-";
                    if (!KIRANAKU.isNullOrEmpty(row.jadwal_service)) {
                        jadwal = moment(row.jadwal_service).format('DD.MM.YYYY');
                        // if(moment().subtract('days',1).isSameOrAfter(moment(row.jadwal_service)))
                        // jadwal = "<small class='badge bg-red'>Outstanding</small><br/>"+jadwal;
                    }
                    return jadwal;
                }
            },
            {
                "data": "v_aset_pm.tanggal_mulai",
                "name": "tanggal_mulai",
                "width": "5%",
                "render": function (data, type, row) {
                    return KIRANAKU.isNullOrEmpty(row.tanggal_mulai, moment(row.tanggal_mulai).format('DD.MM.YYYY'), '-');
                }
            },
            {
                "data": "tanggal_selesai",
                "name": "tanggal_selesai",
                "width": "5%",
                "render": function (data, type, row) {
                    return KIRANAKU.isNullOrEmpty(row.tanggal_selesai, moment(row.tanggal_selesai).format('DD.MM.YYYY'), '-');
                }
            },
            {
                "data": "v_aset_pm.jenis_maintenance",
                "name": "jenis_maintenance",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.jenis_maintenance;
                }
            },
            {
                "data": "v_aset_pm.nama_operator",
                "name": "nama_operator",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_operator;
                }
            },
            {
                "data": "main_status",
                "name": "main_status",
                "width": "5%",
                "searchable": false,
                "render": function (data, type, row) {
                    if (row.main_status == 'noschedule')
                        return '<label class="label label-default">No Schedule</label>';
                    else if (row.main_status == 'scheduled')
                        return '<label class="label label-info">Scheduled</label>';
                    else if (row.main_status == 'onprogress')
                        return '<label class="label label-warning">On Progress</label>';
                    else if (row.main_status == 'confirmpic')
                        return '<label class="label label-warning">Waiting User Confirmation</label>';
                    else if (row.main_status == 'complete')
                        return '<label class="label label-success">Complete</label>';
                }
            },
            {
                // "data": "tbl_inv_aset.id_aset",
                "data": "id_main",
                "name": "id_main",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.buttons;
                }
            }
        ],
        'order': [[6, 'asc']],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            if (info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });

    return mydDatatables;
}