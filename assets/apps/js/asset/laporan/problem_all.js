$(document).ready(function() {
	//export to excel
    $(document).on('click', '#excel_button', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'asset/laporan/excel/'
            +'?pabrik='+$('#id_pabrik').val()
            +'&problem='+$('#problem').val()
        );

    })
	
	
    let tableHistory = $('#table-tab-history-pm').dataTable({
        destroy: true,
        'order': [
            [0, 'asc']
        ]
    });
    let tableHistoryPerbaikan = $('#table-tab-history-perbaikan').dataTable({
        destroy: true,
        'order': [
            [0, 'asc']
        ]
    });
    let tableHistoryAsset = $('#table-tab-history-asset').dataTable({
        destroy: true,
        'order': [
            [0, 'asc']
        ]
    });

    //switch
    $('.switch-onoff').bootstrapToggle({
        on: 'Yes',
        off: 'No'
    });

    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
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

    datatables_ssp();

    //=======FILTER=======//
    $(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area, #kondisi, #idle", function() {
        datatables_ssp();
    });


    $(document).on("change", ".cek_data", function(e) {
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
            success: function(data) {
                console.log(data);
                if (data != '') {
                    $(".cek_data").val('');
                    swal('Warning', 'Data Sudah Terpakai', 'warning');

                }
            }
        });
    });

    // $("#pic").select2({
    //     allowClear: true,
    //     placeholder: {
    //         id: "",
    //         placeholder: "Leave blank to ..."
    //     },
    //     ajax: {
    //         url: baseURL + 'asset/transaksi/get/pic',
    //         dataType: 'json',
    //         delay: 250,
    //         data: function (params) {
    //             return {
    //                 q: params.term, // search term
    //                 page: params.page
    //             };
    //         },
    //         processResults: function (data, page) {
    //             return {
    //                 results: data.items
    //             };
    //         },
    //         cache: false
    //     },
    //     escapeMarkup: function (markup) {
    //         return markup;
    //     }, // let our custom formatter work
    //     minimumInputLength: 3,
    //     templateResult: function (repo) {
    //         if (repo.loading) return repo.text;
    //         var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
    //         return markup;
    //     },
    //     templateSelection: function (repo) {
    //         // return repo.text;
    //         if (repo.posst) $("input[name='caption']").val(repo.posst);
    //         if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
    //         else return repo.text;
    //     }
    // });
    //
    // $("#pic").on('select2:select', function (e) {
    //     var id = e.params.data.id;
    //     var option = $(e.target).children('[value="' + id + '"]');
    //     option.detach();
    //     $(e.target).append(option).change();
    // });

    function formatAsetSelection(aset) {
        if (aset.id) {
            $('input[name="nama_user"]').val(aset.text);
            $('input[name="id_divisi"]').val(aset.id_divisi);
            return aset.id + " - " + aset.text;
        } else if (aset.nama_user)
            return aset.nama_user;
        else
            return aset.text;
        // $('input[name="kode"]').val(aset.kode);
    }

    function formatSearchAset(aset) {
        if (aset.loading) {
            return aset.text;
        }

        var markup = "<div class='select2-result-aset clearfix'>" + aset.id + " - " + aset.text + "</div>";

        return markup;
    }

    //edit
    $(document).on("click", ".edit", function() {
        var id_aset = $(this).data("edit");

        $.ajax({
            url: baseURL + 'asset/transaksi/get/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: id_aset
            },
            success: function(data) {
                console.log(data);
                $(".title-form").html("Edit Setting Program Batch");
                $.each(data, function(i, v) {
                    $("#id_aset").val(v.id_aset);
                    $("#hidden_gambar_depan").val(v.gambar_depan);
                    $("#hidden_gambar_belakang").val(v.gambar_belakang);
                    $("#hidden_gambar_kanan").val(v.gambar_kanan);
                    $("#hidden_gambar_kiri").val(v.gambar_kiri);
                    $("input[name='kode_barang']").val(v.KODE_BARANG);
                    $("input[name='nomor_sap']").val(v.nomor_sap);
                    $("input[name='nama_user']").val(v.NAMA_USER);
                    $("input[name='nama_vendor']").val(v.NAMA_VENDOR);
                    $("input[name='ip_address']").val(v.IP_ADDRESS);
                    $("select[name='os']").val(v.OS).trigger("change");
                    // $("input[name='sn_os']").val(v.SN_OS);
                    $("select[name='office_apps']").val(v.OFFICE_APPS).trigger("change");
                    $("input[name='mac_address']").val(v.MAC_ADDRESS);
                    $("input[name='tipe_processor']").val(v.TIPE_PROCESSOR);
                    $("input[name='processor_series']").val(v.PROCESSOR_SERIES);
                    $("input[name='processor_spec']").val(v.PROCESSOR_SPEC);
                    $("input[name='ram']").val(v.RAM);
                    $("input[name='hdd']").val(v.HDD);
                    $("input[name='merk_monitor']").val(v.MERK_MONITOR);
                    $("input[name='ukuran_monitor']").val(v.UKURAN_MONITOR);
                    //tambahan lha
                    if (v.lisensi_os == 'y') {
                        $("input[name='lisensi_os']").attr('checked');
                        $("input[name='lisensi_os']").bootstrapToggle('on');
                    } else {
                        $("input[name='lisensi_os']").removeAttr('checked');
                        $("input[name='lisensi_os']").bootstrapToggle('off');
                    }
                    // jenis desktop,laptop,server
                    if ((v.id_jenis == 'UENFMzB4OG1lb0JzUm00T2N3d2hVUT09') || (v.id_jenis == 'eHFYZU9KQzlJVlR3RXZ6akZmSDRCdz09') || (v.id_jenis == 'NzZLK0pSNVRtZEJTL1poaHhiYzJRUT09')) {
                        var sn_os = '<select class="form-control select2modal" name="sn_os" id="sn_os">';
                        sn_os += '<option value="0">Pilih SN/OS</option>';
                        $.each(v.arr_sn_os, function(x, y) {
                            var selected = (y.sn_os == v.SN_OS ? 'selected' : '');
                            sn_os += '<option value="' + y.sn_os + '" ' + selected + '>' + y.kode_barang + ' | ' + y.sn_os + '</option>';
                        });
                        sn_os += '</select>';
                        $('#show_sn_os').html(sn_os);
                    } else {
                        // $("input[name='sn_os']").val(v.SN_OS);
                        var sn_os = '<input type="text" class="form-control" name="sn_os" id="sn_os" value="' + v.SN_OS + '" placeholder="Serial Number">';
                        $('#show_sn_os').html(sn_os);
                    }
                    //office
                    if (v.lisensi_office == 'y') {
                        $("input[name='lisensi_office']").attr('checked');
                        $("input[name='lisensi_office']").bootstrapToggle('on');
                    } else {
                        $("input[name='lisensi_office']").removeAttr('checked');
                        $("input[name='lisensi_office']").bootstrapToggle('off');
                    }
                    // jenis desktop,laptop,server
                    if ((v.id_jenis == 'UENFMzB4OG1lb0JzUm00T2N3d2hVUT09') || (v.id_jenis == 'eHFYZU9KQzlJVlR3RXZ6akZmSDRCdz09') || (v.id_jenis == 'NzZLK0pSNVRtZEJTL1poaHhiYzJRUT09')) {
                        var sn_office = '<select class="form-control select2modal" name="sn_office" id="sn_office">';
                        sn_office += '<option value="0">Pilih SN Office</option>';
                        $.each(v.arr_sn_office, function(x, y) {
                            var selected = (y.sn_office == v.sn_office ? 'selected' : '');
                            sn_office += '<option value="' + y.sn_office + '" ' + selected + '>' + y.kode_barang + ' | ' + y.sn_office + '</option>';
                        });
                        sn_office += '</select>';
                        $('#show_sn_office').html(sn_office);
                    } else {
                        // $("input[name='sn_office']").val(v.sn_office);
                        var sn_office = '<input type="text" class="form-control" name="sn_office" id="sn_office" value="' + v.sn_office + '" placeholder="Serial Number">';
                        $('#show_sn_office').html(sn_office);
                    }



                    // $("select[name='id_jenis']").val(v.id_jenis).trigger("change");
                    //load kategori
                    get_data_kategori(v.id_kategori);
                    //load jenis
                    get_data_jenis(v.id_jenis);
                    //load merk
                    var output = '';
                    $.each(v.arr_merk, function(x, y) {
                        var selected = (y.id_merk == v.id_merk ? 'selected' : '');
                        output += '<option value="' + y.id_merk + '" ' + selected + '>' + y.nama + '</option>';
                    });
                    $("select[name='id_merk']").html(output).select2();
                    //load merk tipe
                    var output = '';
                    $.each(v.arr_merk_tipe, function(x, y) {
                        var selected = (y.id_merk_tipe == v.id_merk_tipe ? 'selected' : '');
                        output += '<option value="' + y.id_merk_tipe + '" ' + selected + '>' + y.nama + '</option>';
                    });
                    $("select[name='id_merk_tipe']").html(output).select2();
                    $("select[name='id_status']").val(v.id_status).trigger("change");
                    $("select[name='id_kondisi']").val(v.id_kondisi).trigger("change");
                    $("input[name='tanggal_perolehan']").val(v.tanggal_perolehan);
                    $("input[name='pic']").val(v.pic);
                    // if(v.pic!=null){
                    // var pic 		= v.pic.split(",");
                    // var nama_pic	= v.nama_pic.slice(0, -1).split(",");
                    // var array   	= [];
                    // $.each(nama_pic, function(x, y){
                    // // console.log(y);
                    // var control = $('#pic').empty().data('select2');
                    // var adapter = control.dataAdapter;
                    // array.push({"id":pic[x],"text":y+' - ['+ pic[x]+ ']'});

                    // adapter.addOptions(adapter.convertToOptions(array));
                    // $('#pic').trigger('change');
                    // });
                    // $('#pic').val(pic).trigger('change');

                    // }

                    $("textarea[name='keterangan']").val(v.keterangan);
                    $("select[name='id_pabrik']").val(v.id_pabrik).trigger("change");
                    //load lokasi
                    get_data_lokasi(v.id_lokasi);
                    //load sub lokasi
                    var output = '';
                    $.each(v.arr_sub_lokasi, function(x, y) {
                        var selected = (y.id_sub_lokasi == v.id_sub_lokasi ? 'selected' : '');
                        output += '<option value="' + y.id_sub_lokasi + '" ' + selected + '>' + y.nama + '</option>';
                    });
                    $("select[name='id_sub_lokasi']").html(output).select2();
                    //load area
                    var output = '';
                    $.each(v.arr_area, function(x, y) {
                        var selected = (y.id_area == v.id_area ? 'selected' : '');
                        output += '<option value="' + y.id_area + '" ' + selected + '>' + y.nama + '</option>';
                    });
                    $("select[name='id_area']").html(output).select2();

                    $(".gambar_depan").attr('src', v.gambar_depan);
                    $(".gambar_belakang").attr('src', v.gambar_belakang);
                    $(".gambar_kanan").attr('src', v.gambar_kanan);
                    $(".gambar_kiri").attr('src', v.gambar_kiri);

                    if (v.pic) {
                        var option = new Option(v.NAMA_USER, v.pic, true, true);
                        $("#pic_ajax").append(option).trigger('change');

                        // manually trigger the `select2:select` event
                        $("#pic_ajax").trigger({
                            type: 'select2:select',
                            params: {
                                data: [{ text: v.nama_pic, id: v.pic }]
                            }
                        });
                    } else
                        $("#pic_ajax").val(null).trigger('change');

                    $("input[name='id_kondisi']").val(v.id_kondisi);
                    $("input[name='id_sub_lokasi']").val(v.id_sub_lokasi);
                    $("input[name='id_area']").val(v.id_area);
                    $("input[name='nama_pic']").val(v.pic + ' - ' + v.nama_karyawan);

                });

            },
            complete: function() {
                // $('#id_sub_lokasi').prop('disabled', true);
                // $('#id_area').prop('disabled', true);
                // $('#id_kondisi').prop('disabled', true);
                $('#pic_ajax').prop('disabled', true);
                $('#nama_user').prop('disabled', true);
                // $(".select2modal").select2();
                $(".modal").each(function() {
                    var elemModal = $(this).attr("id");
                    console.log(elemModal);
                    $("#" + elemModal + " .select2modal").select2({
                        dropdownParent: $("#" + elemModal + " .modal-content"),
                        allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
                        placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
                    });
                });
                $('#add_modal').modal('show');
            }

        });
    });

    //set pic
    $(document).on("click", ".set_pic", function() {
        var id_aset = $(this).data("id_aset");

        $.ajax({
            url: baseURL + 'asset/transaksi/get/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: id_aset
            },
            success: function(data) {
                console.log(data);
                $(".title-form").html("Edit Setting Program Batch");
                $.each(data, function(i, v) {
                    $('#label_nomor_sap_move').html(v.nomor_sap);
                    $('#label_kode_barang_move').html(v.KODE_BARANG);
                    $('#label_nama_jenis_move').html(v.nama_jenis);
                    $('#label_nama_pabrik_move').html(v.nama_pabrik);
                    $('#label_nama_lokasi_move').html(v.nama_lokasi);
                    $('#label_nama_area_move').html(v.nama_area);
                    $('#label_nama_karyawan_move').html(v.nama_karyawan);
                    $('#label_kondisi_move').html(v.label_nama_kondisi);

                    $("input[name='id_aset']").val(v.id_aset);
                    $("input[name='nama_user']").val('');
                    $("input[name='pic']").val(v.pic);
                    $("input[name='pic_awal']").val(v.pic);
                    $("input[name='id_sub_lokasi_awal']").val(v.id_sub_lokasi);
                    $("input[name='id_area_awal']").val(v.id_area);
                    if (v.pic) {
                        var option = new Option(v.NAMA_USER, v.pic, true, true);
                        // $("#set_pic").append(option).trigger('change');
                        $("#set_pic").trigger({
                            type: 'select2:select',
                            params: {
                                data: [{ text: v.nama_pic, id: v.pic }]
                            }

                        });
                        // $("#set_pic").val(null).trigger('change');
                    } else
                    // $("#set_pic").val(null).trigger('change');

                        $("input[name='nama_user']").val('');

                    var output = '';
                    output += '<option value="0">Pilih Tipe Movement</option>';
                    if (v.nama_kondisi == 'Beroperasi') {
                        output += '<option value="Exit Clearance">Exit Clearance</option>';
                        output += '<option value="Keep IT">Keep IT</option>';
                    }
                    if (v.nama_kondisi == 'Stand By') {
                        output += '<option value="Temporary">Temporary</option>';
                        output += '<option value="New Employee">New Employee</option>';
                        output += '<option value="Permanent Replacement">Permanent Replacement</option>';
                    }
                    $("select[name='alasan']").html(output).select2();

                });

            },
            complete: function() {
                $('#set_pic_modal').modal('show');
            }

        });
    });

    //set kondisi
    $(document).on("click", ".set_kondisi", function() {
        var id_aset = $(this).data("id_aset");

        $.ajax({
            url: baseURL + 'asset/transaksi/get/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: id_aset
            },
            success: function(data) {
                console.log(data);
                $(".title-form").html("Edit Setting Program Batch");
                $.each(data, function(i, v) {
                    $("input[name='id_aset']").val(v.id_aset);
                    $("input[name='id_kondisi_awal']").val(v.id_kondisi);
                    $("select[name='id_kondisi']").val(v.id_kondisi).trigger("change");
                });

            },
            complete: function() {
                $('#set_kondisi_modal').modal('show');
            }

        });
    });
    //set perbaikan
    $(document).on("click", ".set_perbaikan", function() {
        var id_aset = $(this).data("id_aset");

        $.ajax({
            url: baseURL + 'asset/transaksi/get/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: id_aset
            },
            success: function(data) {
                console.log(data);
                $('.switch-onoff').bootstrapToggle({
                    on: 'Yes',
                    off: 'No'
                });

                $(".title-form").html("Perbaikan Asset");
                $.each(data, function(i, v) {
                    $("input[name='id_aset']").val(v.id_aset);
                    $("input[name='id_jenis']").val(v.id_jenis);
                    $('#label_nomor_sap').html(v.nomor_sap);
                    $('#label_kode_barang').html(v.KODE_BARANG);
                    $('#label_nama_jenis').html(v.nama_jenis);
                    $('#label_nama_pabrik').html(v.nama_pabrik);
                    $('#label_nama_lokasi').html(v.nama_lokasi);
                    $('#label_nama_area').html(v.nama_area);
                    $('#label_nama_karyawan').html(v.nama_karyawan);


                    resetTableItems();
                    $.each(v.arr_jenis_detail, function(x, y) {
                        tableItems.DataTable().row.add([
                            '<input type="checkbox" class="switch-onoff" name="cek[' + y.id_jenis_detail + ']">',
                            y.nama,
                            '<select class="form-control select2" name="pekerjaan[' + y.id_jenis_detail + ']" id="pekerjaan_' + y.id_jenis_detail + '" >' +
                            '<option value="0">-Pekerjaan-</option>' +
                            '<option value="Perbaiki">Perbaiki</option>' +
                            '<option value="Ganti">Ganti</option>' +
                            '</select>',
                            '<input size="65" type="text" class="form-control" name="keterangan[' + y.id_jenis_detail + ']" id="keterangan_' + y.id_jenis_detail + '">' +
                            '<input type="hidden" class="form-control" name="nama[' + y.id_jenis_detail + ']" value="' + y.nama + '"/>' +
                            '<input type="hidden" class="form-control" name="id_jenis_detail[' + y.id_jenis_detail + ']" value="' + y.id_jenis_detail + '" />'
                        ]);
                    });
                    tableItems.DataTable().draw();
                });

            },
            complete: function() {
                $('.my-datatable-extends-order').DataTable().destroy();
                $('.select2').select2();
                $('.switch-onoff').bootstrapToggle({
                    on: 'Yes',
                    off: 'No'
                });

                $('#set_perbaikan_modal').modal('show');
            }

        });
    });
    //set perbaikan complete
    $(document).on("click", ".set_perbaikan_complete", function() {
        var id_aset = $(this).data("id_aset");

        $.ajax({
            url: baseURL + 'asset/transaksi/get/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: id_aset
            },
            success: function(data) {
                console.log(data);
                $(".title-form").html("Perbaikan Asset");
                $.each(data, function(i, v) {
                    $("input[name='id_aset']").val(v.id_aset);
                    $("input[name='id_jenis']").val(v.id_jenis);
                    $('#label_nomor_sap_complete').html(v.nomor_sap);
                    $('#label_kode_barang_complete').html(v.KODE_BARANG);
                    $('#label_nama_jenis_complete').html(v.nama_jenis);
                    $('#label_nama_pabrik_complete').html(v.nama_pabrik);
                    $('#label_nama_lokasi_complete').html(v.nama_lokasi);
                    $('#label_nama_area_complete').html(v.nama_area);
                    $('#label_nama_karyawan_complete').html(v.nama_karyawan);
                    $.each(v.arr_main, function(x, y) {
                        $("input[name='id_main']").val(y.id_main);
                        $('#label_tanggal_rusak_complete').html(KIRANAKU.isNullOrEmpty(y.tanggal_rusak, moment(y.tanggal_rusak).format('DD.MM.YYYY'), '-'));
                        $('#label_tanggal_estimasi_complete').html(KIRANAKU.isNullOrEmpty(y.tanggal_estimasi, moment(y.tanggal_estimasi).format('DD.MM.YYYY'), '-'));
                    });

                    resetTableItemsComplete();
                    $.each(v.arr_main_detail, function(x, y) {
                        tableItemsComplete.DataTable().row.add([
                            '<input size="15" type="text" class="form-control" value="' + y.nama_jenis_detail + '" disabled>',
                            '<input size="15" type="text" class="form-control" value="' + y.nama_periode_detail + '" disabled>',
                            '<input size="55" type="text" class="form-control" value="' + y.keterangan + '" disabled>'

                        ]);
                    });
                    tableItemsComplete.DataTable().draw();
                });

            },
            complete: function() {
                $('#set_perbaikan_complete_modal').modal('show');
            }

        });
    });

    function resetTableItems() {
        tableItems.DataTable().clear();
    }

    function resetTableItemsComplete() {
        tableItemsComplete.DataTable().clear();
    }
    var tableItems = $('#table-maintenance-item').dataTable({
        destroy: true,
        'order': [
            [0, 'asc']
        ],
        'select': {
            'style': 'multi'
        },
        'paging': false,
        'searching': false
    });
    var tableItemsComplete = $('#table-maintenance-item-complete').dataTable({
        destroy: true,
        'order': [
            [0, 'asc']
        ],
        'select': {
            'style': 'multi'
        },
        'paging': false,
        'searching': false
    });


    $(document).on("click", ".nonactive, .setactive, .delete", function(e) {
        $.ajax({
            url: baseURL + "asset/transaksi/set/it",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: $(this).data($(this).attr("class")),
                type: $(this).attr("class")
            },
            success: function(data) {
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg);
                } else {
                    kiranaAlert("notOK", data.msg, "warning", "no");
                }
            }
        });
        e.preventDefault();
        return false;
    });

    $(document).on("click", ".history-pm", function(e) {
        var id_aset = $(this).data("pm");
        $.ajax({
            url: baseURL + 'asset/maintenance/get/it/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: id_aset
            },
            success: function(data) {
                resetTableHistory();
                resetTableHistoryPerbaikan();
                resetTableHistoryAsset();

                $.each(data.data, function(i, v) {
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
                //buat history Perbaikan
                var no = 0;
                $.each(data.data_perbaikan, function(i, v) {
                    no = no + 1;
                    if (v.list_item != null) {
                        var list = v.list_item.split(',').join('<br/>');
                    } else {
                        var list = '-';
                    }

                    tableHistoryPerbaikan.DataTable().row.add([
                        no,
                        KIRANAKU.isNullOrEmpty(v.tanggal_rusak, moment(v.tanggal_rusak).format('DD.MM.YYYY'), '-'),
                        KIRANAKU.isNullOrEmpty(v.tanggal_selesai, moment(v.tanggal_selesai).format('DD.MM.YYYY'), '-'),
                        list,
                        v.nama_karyawan + '<br>(' + v.nik_karyawan + ')'
                    ]);
                });
                tableHistoryPerbaikan.DataTable().draw();

                //buat history Asset
                var no = 0;
                $.each(data.data_asset, function(i, v) {
                    no = no + 1;
                    tableHistoryAsset.DataTable().row.add([
                        no,
                        v.jenis_perubahan,
                        KIRANAKU.isNullOrEmpty(v.tanggal_buat, moment(v.tanggal_buat).format('DD.MM.YYYY'), '-'),
                        v.label_status_awal,
                        v.label_status_akhir,
                        v.alasan
                    ]);
                });
                tableHistoryAsset.DataTable().draw();

            },
            complete: function() {
                $('#history_modal').modal('show');
            }

        });
    })

    function resetTableHistory() {
        tableHistory.DataTable().clear();
    }

    function resetTableHistoryPerbaikan() {
        tableHistoryPerbaikan.DataTable().clear();
    }

    function resetTableHistoryAsset() {
        tableHistoryAsset.DataTable().clear();
    }


    $(document).on("click", "button[name='action_btn']", function(e) {
        var empty_form = validate('.form-transaksi-it');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-it")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'asset/transaksi/save/it',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });
    //save perbaikan
    $(document).on("click", "button[name='action_btn_perbaikan']", function(e) {
        var empty_form = validate('.form-transaksi-perbaikan');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-perbaikan")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'asset/transaksi/save/perbaikan/it',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });
    //save perbaikan complete
    $(document).on("click", "button[name='action_btn_perbaikan_complete']", function(e) {
        var empty_form = validate('.form-transaksi-perbaikan-complete');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-perbaikan-complete")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'asset/transaksi/save/perbaikan_complete/it',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });
    // save pic
    $(document).on("click", "button[name='action_btn_pic']", function(e) {
        var empty_form = validate('.form-transaksi-pic');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-pic")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'asset/transaksi/save/set_pic',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });
    // save kondisi
    $(document).on("click", "button[name='action_btn_kondisi']", function(e) {
        var empty_form = validate('.form-transaksi-kondisi');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-kondisi")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'asset/transaksi/save/set_kondisi',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function() {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });
    //set on change pic
    $(document).on("change", "#set_pic", function(e) {
        var pic = $(this).val();
        var alasan = $("#alasan").val();
        if ((alasan != 'Exit Clearance') && (alasan != 'Keep IT')) {
            $.ajax({
                url: baseURL + 'asset/transaksi/get/aset_pic',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    pic: pic
                },
                success: function(data) {
                    // console.log(data);
                    $.each(data, function(i, v) {
                        if (v.ho == 'y') {
                            // if(v.nama_jenis=='Laptop'){
                            // swal('Warning', v.nama+' memiliki Laptop dengan Kode SAP <b>'+v.nomor_sap+'</b> yang masih beroperasi. Proses Tidak dapat dilanjutkan.', 'warning');
                            // $('#set_pic').select2('destroy').find('option').prop('selected', false).end().select2();
                            // $("input[name='nama_user']").val('');
                            // }

                            // if(v.nama_jenis=='Desktop'){
                            if ((v.nama_jenis == 'Desktop') || (v.nama_jenis == 'Laptop')) {
                                kiranaConfirm({
                                    title: "Konfirmasi",
                                    text: v.nama + " memiliki Asset Desktop dengan kode SAP " + v.nomor_sap + ", apakah proses akan dilanjutkan?",
                                    dangerMode: true,
                                    successCallback: function() {

                                    },
                                    failCallback: function() {
                                        $('#set_pic').select2('destroy').find('option').prop('selected', false).end().select2();
                                        $("input[name='nama_user']").val('');
                                    }
                                });
                            }
                        } else {
                            swal('Warning', v.nama + ' berada di Pabrik ' + v.id_gedung, 'warning');
                        }
                    });
                }
            });
        }
    });
    //set on change tipe movement
    $(document).on("change", "#alasan", function(e) {
        var alasan = $(this).val();
        if (alasan === 'Temporary') {
            var output = '';
            output += '<option value="0">Pilih Tipe Movement</option>';
            output += '<option value="Perjalanan Dinas">Perjalanan Dinas</option>';
            output += '<option value="Perbaikan">Perbaikan</option>';
            output += '<option value="Meeting / Seminar / Training">Meeting / Seminar / Training</option>';
            output += '<option value="Magang">Magang</option>';
            output += '<option value="Work From Home">Work From Home</option>';
            $("select[name='alasan_detail']").html(output).select2();
            $('.show_alasan_detail').removeClass('hide');
            $('#alasan_detail').prop('required', true);
        } else {
            $('.show_alasan_detail').addClass('hide');
            $('#alasan_detail').prop('required', false);
        }
        if ((alasan === 'Exit Clearance') || (alasan === 'Keep IT')) {
            //xx
            $('#set_pic').select2({
                dropdownParent: $('.form-transaksi-pic'),
                ajax: {
                    delay: 250,
                    url: baseURL + 'asset/maintenance/get/it/karyawan/agent',
                    method: 'POST',
                    dataType: 'json',
                    processResults: function(data) {
                        data.data.forEach(function(v) {
                            v.id = v.nik;
                            v.text = v.nama;
                            v.id_divisi = v.id_divisi;
                        });
                        return {
                            results: data.data
                        };
                    },
                    cache: true
                },
                placeholder: 'Cari Karyawan (Nama atau NIK)',
                allowClear: true,
                minimumInputLength: 3,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: formatSearchAset,
                templateSelection: formatAsetSelection
            });
        } else {
            $('#set_pic').select2({
                dropdownParent: $('.form-transaksi-pic'),
                ajax: {
                    delay: 250,
                    url: baseURL + 'asset/maintenance/get/it/karyawan',
                    method: 'POST',
                    dataType: 'json',
                    processResults: function(data) {
                        data.data.forEach(function(v) {
                            v.id = v.nik;
                            v.text = v.nama;
                            v.id_divisi = v.id_divisi;
                        });
                        return {
                            results: data.data
                        };
                    },
                    cache: true
                },
                placeholder: 'Cari Karyawan (Nama atau NIK)',
                allowClear: true,
                minimumInputLength: 3,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: formatSearchAset,
                templateSelection: formatAsetSelection
            });
        }
    });

    //set on change id_kategori
    $(document).on("change", "#id_kategori", function(e) {
        var id_kategori = $(this).val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/jenis/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_kategori: id_kategori
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Silahkan Pilih Jenis</option>';
                $.each(data, function(i, v) {
                    value += '<option value="' + v.id_jenis + '">' + v.nama + '</option>';
                });
                $('#id_jenis').html(value);

            }
        });
    });
    //set on change id_jenis
    $(document).on("change", "#id_jenis", function(e) {
        var id_jenis = $(this).val();
        var id_kategori = $("#id_kategori").val();
        // alert(id_jenis);
        $.ajax({
            url: baseURL + 'asset/transaksi/get/merk',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_jenis: id_jenis
            },
            success: function(data) {

                var value = '';
                value += '<option value="0">Silahkan Pilih Merk</option>';
                $.each(data, function(i, v) {
                    value += '<option value="' + v.id_merk + '">' + v.nama + '</option>';
                });
                $('#id_merk').html(value);
            },
            complete: function() {
                //tambahan lha
                // jenis desktop,laptop,server
                if ((id_jenis == 'UENFMzB4OG1lb0JzUm00T2N3d2hVUT09') || (id_jenis == 'eHFYZU9KQzlJVlR3RXZ6akZmSDRCdz09') || (id_jenis == 'NzZLK0pSNVRtZEJTL1poaHhiYzJRUT09')) {
                    $.ajax({
                        url: baseURL + 'asset/transaksi/get/sn_os',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_kategori: id_kategori
                        },
                        success: function(data) {
                            var sn_os = '<select class="form-control select2modal" name="sn_os" id="sn_os">';
                            sn_os += '<option value="0">Pilih SN/OS</option>';
                            $.each(data, function(i, v) {
                                sn_os += '<option value="' + v.sn_os + '">' + v.kode_barang + ' | ' + v.sn_os + '</option>';
                            });
                            sn_os += '</select>';
                            $('#show_sn_os').html(sn_os);
                        },
                        complete: function() {
                            // $(".select2modal").select2();
                            $(".modal").each(function() {
                                var elemModal = $(this).attr("id");
                                console.log(elemModal);
                                $("#" + elemModal + " .select2modal").select2({
                                    dropdownParent: $("#" + elemModal + " .modal-content"),
                                    allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
                                    placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
                                });
                            });
                        }
                    });
                } else {
                    var sn_os = '<input type="text" class="form-control" name="sn_os" id="sn_os" placeholder="Serial Number">';
                    $('#show_sn_os').html(sn_os);
                }
                // jenis desktop,laptop,server
                if ((id_jenis == 'UENFMzB4OG1lb0JzUm00T2N3d2hVUT09') || (id_jenis == 'eHFYZU9KQzlJVlR3RXZ6akZmSDRCdz09') || (id_jenis == 'NzZLK0pSNVRtZEJTL1poaHhiYzJRUT09')) {
                    $.ajax({
                        url: baseURL + 'asset/transaksi/get/sn_office',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_kategori: id_kategori
                        },
                        success: function(data) {
                            var sn_office = '<select class="form-control select2modal" name="sn_office" id="sn_office">';
                            sn_office += '<option value="0">Pilih SN Office</option>';
                            $.each(data, function(i, v) {
                                sn_office += '<option value="' + v.sn_office + '">' + v.kode_barang + ' | ' + v.sn_office + '</option>';
                            });
                            sn_office += '</select>';
                            $('#show_sn_office').html(sn_office);
                        },
                        complete: function() {
                            // $(".select2modal").select2();
                            $(".modal").each(function() {
                                var elemModal = $(this).attr("id");
                                console.log(elemModal);
                                $("#" + elemModal + " .select2modal").select2({
                                    dropdownParent: $("#" + elemModal + " .modal-content"),
                                    allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
                                    placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
                                });
                            });
                        }
                    });
                } else {
                    var sn_office = '<input type="text" class="form-control" name="sn_office" id="sn_office" placeholder="Serial Number">';
                    $('#show_sn_office').html(sn_office);
                }
            }


        });
    });
    //set on change id_merk
    $(document).on("change", "#id_merk", function(e) {
        var id_merk = $(this).val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/tipe/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_merk: id_merk
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Silahkan Pilih Type</option>';
                if (data) {
                    $.each(data, function(i, v) {
                        value += '<option value="' + v.id_merk_tipe + '">' + v.nama + '</option>';
                    });
                }
                $('#id_merk_tipe').html(value);
            }
        });
    });
    //set on change id_lokasi
    $(document).on("change", "#id_lokasi", function(e) {
        var id_lokasi = $(this).val();
        var id_pabrik = $("#id_pabrik").val();
        if ($("option:selected", this).text() == "Depo") {
            $.ajax({
                url: baseURL + 'asset/transaksi/get/depo/it',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id_pabrik: id_pabrik,
                    id_lokasi: id_lokasi
                },
                success: function(data) {
                    if (data) {
                        $('#show_depo').html('');
                        var value = '';
                        value += '<div class="form-group">';
                        value += '<div class="row">';
                        value += '<div class="col-xs-3">';
                        value += '<label for="id_depo">Nama Depo</label>';
                        value += '</div>';
                        value += '<div class="col-xs-8">';
                        value += '<select class="form-control select2modal" name="id_depo" id="id_depo"  required="required">';
                        value += '<option value="0">Silahkan Pilih Type</option>';
                        $.each(data, function(i, v) {
                            value += '<option value="' + v.DEPID + '">' + v.DEPNM + '</option>';
                        });
                        value += '</select>';
                        value += '</div>';
                        value += '</div>';
                        value += '</div>';
                        $('#show_depo').append(value + '</select>');
                    } else {
                        $('#show_depo').append('');
                    }
                },
                complete: function() {
                    // $(".select2modal").select2();
                    $(".modal").each(function() {
                        var elemModal = $(this).attr("id");
                        console.log(elemModal);
                        $("#" + elemModal + " .select2modal").select2({
                            dropdownParent: $("#" + elemModal + " .modal-content"),
                            allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
                            placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
                        });
                    });
                }
            });
        }
    });
    //set on change id_lokasi
    $(document).on("change", "#id_lokasi", function(e) {
        var id_lokasi = $(this).val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/sublokasi',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_lokasi: id_lokasi
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Silahkan Pilih Sub Lokasi</option>';
                if (data) {
                    $.each(data, function(i, v) {
                        value += '<option value="' + v.id_sub_lokasi + '">' + v.nama + '</option>';
                    });
                }
                $('#id_sub_lokasi').html(value);
            }
        });
    });
    //set on change id_sub_lokasi
    $(document).on("change", "#id_sub_lokasi", function(e) {
        var id_sub_lokasi = $(this).val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/area',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_sub_lokasi: id_sub_lokasi
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Silahkan Pilih Area</option>';
                if (data) {
                    $.each(data, function(i, v) {
                        value += '<option value="' + v.id_area + '">' + v.nama + '</option>';
                    });
                }
                $('#id_area').html(value);
            }
        });
    });
    //set on change set_id_sub_lokasi
    $(document).on("change", "#set_id_sub_lokasi", function(e) {
        var id_sub_lokasi = $(this).val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/area',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_sub_lokasi: id_sub_lokasi
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Silahkan Pilih Area</option>';
                if (data) {
                    $.each(data, function(i, v) {
                        value += '<option value="' + v.id_area + '">' + v.nama + '</option>';
                    });
                }
                $('#set_id_area').html(value);
            }
        });
    });
    //set on change jenis
    $(document).on("change", "#jenis", function(e) {
        var jenis = $("#jenis").val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/merk/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                jenis: jenis
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Pilih Merk</option>';
                $.each(data, function(i, v) {
                    value += '<option value="' + v.id_merk + '">[' + v.nama_jenis + '] ' + v.nama + '</option>';
                });
                $('#merk').html(value);
            }
        });
    });
    //set on change lokasi
    $(document).on("change", "#lokasi", function(e) {
        var lokasi = $("#lokasi").val();
        $.ajax({
            url: baseURL + 'asset/transaksi/get/area',
            type: 'POST',
            dataType: 'JSON',
            data: {
                lokasi: lokasi
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Pilih Area</option>';
                $.each(data, function(i, v) {
                    value += '<option value="' + v.id_area + '">[' + v.nama_lokasi + '] ' + v.nama + '</option>';
                });
                $('#area').html(value);
            }
        });
    });

    //export to excel
    $('.my-datatable-extends-order').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Penilaian',
            download: 'open',
            orientation: 'landscape',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
            }
        }],
        scrollX: true
    });

    $('#pic_ajax').select2({
        dropdownParent: $('.form-transaksi-it'),
        ajax: {
            delay: 250,
            url: baseURL + 'asset/maintenance/get/it/karyawan',
            method: 'POST',
            dataType: 'json',
            processResults: function(data) {
                data.data.forEach(function(v) {
                    v.id = v.nik;
                    v.text = v.nama;
                });
                return {
                    results: data.data
                };
            },
            cache: true
        },
        placeholder: 'Cari Karyawan (Nama atau NIK)',
        allowClear: true,
        minimumInputLength: 3,
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: formatSearchAset,
        templateSelection: formatAsetSelection
    });
    //xx
    $('#set_pic').select2({
        dropdownParent: $('.form-transaksi-pic'),
        ajax: {
            delay: 250,
            url: baseURL + 'asset/maintenance/get/it/karyawan',
            method: 'POST',
            dataType: 'json',
            processResults: function(data) {
                data.data.forEach(function(v) {
                    v.id = v.nik;
                    v.text = v.nama;
                    v.id_divisi = v.id_divisi;
                });
                return {
                    results: data.data
                };
            },
            cache: true
        },
        placeholder: 'Cari Karyawan (Nama atau NIK)',
        allowClear: true,
        minimumInputLength: 3,
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: formatSearchAset,
        templateSelection: formatAsetSelection
    });

    //open modal for add
    $(document).on("click", "#add_button", function(e) {
        resetForm_use($('#form-transaksi-it'));
        $('#add_modal').modal('show');
    });

    function resetForm_use($form) {
        $('#myModalLabel').html("Akuisisi/ Edit Asset IT");
        $('#pabrik').select2('destroy').find('option').prop('selected', false).end().select2();
        $form.find('input:text, input:password, input:file,  textarea').val("");
        $form.find('select').val(0);
        $form.find('input:radio, input:checkbox')
            .removeAttr('checked').removeAttr('selected');
        $('#add_attch').html("");
        $('#list_attch').html("");
        $('#hidden_file_dellist').val("");
        $('#isproses').val("");
        $('#isconvert').val('0');

    }

    //date pitcker
    $('.tanggal').datepicker({
        format: 'yyyy-mm-dd',
        // startDate: new Date(),
        autoclose: true
    });
    $('.tanggal_current').datepicker({
        format: 'dd.mm.yyyy',
        startDate: new Date(),
        autoclose: true
    });
    $('.tanggal_current_min').datepicker({
        format: 'dd.mm.yyyy',
        startDate: new Date(),
        endDate: new Date(),
        autoclose: true
    });


});

function get_data_kategori(id_kategori) {
    $.ajax({
        url: baseURL + 'asset/transaksi/get/kategori/it',
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            if (data) {
                var output = '';
                $.each(data, function(i, v) {
                    output += '<option value="' + v.id_kategori + '">' + v.nama + '</option>';
                });
                $("select[name='id_kategori']").html(output);
            }
        },
        complete: function() {
            if (id_kategori) {
                $("select[name='id_kategori']").val(id_kategori).trigger("change.select2");
            }
        }
    });
}

function get_data_jenis(id_jenis) {
    $.ajax({
        url: baseURL + 'asset/transaksi/get/jenis/it',
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            if (data) {
                var output = '';
                $.each(data, function(i, v) {
                    output += '<option value="' + v.id_jenis + '">' + v.nama + '</option>';
                });
                $("select[name='id_jenis']").html(output);
            }
        },
        complete: function() {
            if (id_jenis) {
                $("select[name='id_jenis']").val(id_jenis).trigger("change.select2");
            }
        }
    });
}

function get_data_lokasi(id_lokasi) {
    $.ajax({
        url: baseURL + 'asset/transaksi/get/lokasi',
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            if (data) {
                var output = '';
                $.each(data, function(i, v) {
                    output += '<option value="' + v.id_lokasi + '">' + v.nama + '</option>';
                });
                $("select[name='id_lokasi']").html(output);
            }
        },
        complete: function() {
            if (id_lokasi) {
                $("select[name='id_lokasi']").val(id_lokasi).trigger("change.select2");
            }
        }
    });
}

function datatables_ssp() {
    var jenis = $("#jenis").val();
    var merk = $("#merk").val();
    var pabrik = $("#pabrik").val();
    var lokasi = $("#lokasi").val();
    var area = $("#area").val();
    var kondisi = $("#kondisi").val();
    var problem = $("#problem").val();
    var id_merk_tipe = $("#id_merk_tipe").val();
    // var idle = $("#idle").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        pageLength: $(".my-datatable-extends-order", this).data("page") ? $(".my-datatable-extends-order", this).data("page") : 10,
        paging: $(".my-datatable-extends-order", this).data("paging") ? $(".my-datatable-extends-order", this).data("paging") : true,
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        // pageLength: 10,
        initComplete: function() {
            var api = this.api();
            $('#sspTable_filter input').attr("placeholder", "Press enter to start searching");
            $('#sspTable_filter input').attr("title", "Press enter to start searching");
            $('#sspTable_filter input')
                .off('.DT')
                .on('keypress change', function(evt) {
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
            url: baseURL + 'asset/transaksi/get/it/bom',
            type: 'POST',
            data: function(data) {
                data.jenis = jenis;
                data.merk = merk;
                data.pabrik = pabrik;
                data.lokasi = lokasi;
                data.area = area;
                data.kondisi = kondisi;
                data.problem = problem;
                data.id_merk_tipe = id_merk_tipe;
                // data.idle = idle;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [{
                "data": "id_aset",
                "name": "id_aset",
                "width": "20%",
                "render": function(data, type, row) {
                    return row.id_aset;
                },
                "visible": false
            },
            {
                "data": "kode_barang",
                "name": "kode_barang",
                "width": "5%",
                "render": function(data, type, row) {
                    return row.kode_barang;
                }
            },
            {
                "data": "nomor_sap",
                "name": "nomor_sap",
                "width": "5%",
                "render": function(data, type, row) {
                    return row.nomor_sap;
                }
            },
            {
                "data": "nama_jenis",
                "name": "nama_jenis",
                "width": "5%",
                "render": function(data, type, row) {
                    return row.nama_jenis;
                }
            },
            {
                "data": "nama_merk",
                "name": "nama_merk",
                "width": "10%",
                "render": function(data, type, row) {
                    return row.nama_merk;
                },
            },
            {
                "data": "nama_merk_tipe",
                "name": "nama_merk_tipe",
                "width": "10%",
                "render": function(data, type, row) {
                    return row.nama_merk_tipe;
                },
            },
            {
                "data": "nama_pabrik",
                "name": "nama_pabrik",
                "width": "10%",
                "render": function(data, type, row) {
                    return row.nama_pabrik;
                }
            },
            {
                "data": "nama_lokasi",
                "name": "nama_lokasi",
                "width": "5%",
                "render": function(data, type, row) {
                    return row.nama_lokasi;
                }
            },
            {
                "data": "nama_sub_lokasi",
                "name": "nama_sub_lokasi",
                "width": "5%",
                "render": function(data, type, row) {
                    return row.nama_sub_lokasi;
                },
                "visible": false
            },
            {
                "data": "nama_area",
                "name": "nama_area",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.nama_area;
                }
            },
            {
                "data": "nama_user",
                "name": "nama_user",
                "width": "5%",
                "render": function(data, type, row) {
                    if (row.nama_pic)
                        return row.pic_detail;
                    else
                        return row.nama_user;
                }
            },
            {
                "data": "nama_vendor",
                "name": "nama_vendor",
                "width": "5%",
                "render": function(data, type, row) {
                    return row.nama_vendor;
                },
                "visible": false
            },
            {
                "data": "id_kondisi",
                "name": "id_kondisi",
                "width": "5%",
                "render": function(data, type, row) {
                    if (row.id_kondisi == 1) {
                        return '<label class="label label-success">Beroperasi</label>';
                    } else if (row.id_kondisi == 2) {
                        return '<label class="label label-danger">Tidak Beroperasi</label>';
                    } else if (row.id_kondisi == 4) {
                        return '<label class="label label-warning">Dalam Perbaikan</label>';
                    } else if (row.id_kondisi == 5) {
                        return '<label class="label label-danger">Scrap</label>';
                    } else if (row.id_kondisi == 6) {
                        return '<label class="label label-primary">Stand By</label>';
                    } else {
                        return '<label class="label label-danger">Tidak Beroperasi</label>';
                    }
                },
                "visible": true
            },
            {
                "data": "na",
                "name": "na",
                "width": "5%",
                "render": function(data, type, row) {
                    if (row.na == 'n') {
                        return '<label class="label label-success">AKTIF</label>';
                    } else {
                        return '<label class="label label-danger">NON AKTIF</label>';
                    }
                },
                "visible": false
            },
            {
                // "data": "tbl_inv_aset.id_aset",
                "data": "id_aset",
                "name": "id_aset",
                "width": "5%",
                "render": function(data, type, row) {
                    if (row.na == 'n') {
                        output = "			<div class='input-group-btn'>";
                        output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                        output += "				<ul class='dropdown-menu pull-right'>";
                        if (row.id_kondisi != 5) {
                            output += "					<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_aset + "'><i class='fa fa-pencil-square-o'></i> Edit Asset</a></li>";
                            if (row.id_kondisi == 4) { //status dalam perbaikan
                                output += "					<li><a href='javascript:void(0)' class='set_perbaikan_complete' data-id_aset='" + row.id_aset + "'><i class='fa fa-wrench'></i> Perbaikan</a></li>";
                            } else {
                                output += "					<li><a href='javascript:void(0)' class='set_perbaikan' data-id_aset='" + row.id_aset + "'><i class='fa fa-wrench'></i> Perbaikan</a></li>";
                            }
                            output += "					<li><a href='javascript:void(0)' class='set_pic' data-id_aset='" + row.id_aset + "'><i class='fa fa-random'></i> Movement</a></li>";
                            output += "					<li class='divider'></li>";
                        }

                        output += "					<li><a href='javascript:void(0)' class='history-pm' data-pm='" + row.id_aset + "'><i class='fa  fa-bookmark'></i> History</a></li>";
                        output += "				</ul>";
                        output += "	        </div>";
                    } else {
                        output = "			<div class='input-group-btn'>";
                        output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                        output += "				<ul class='dropdown-menu pull-right'>";
                        output += "					<li><a href='javascript:void(0)' class='setactive' data-setactive='" + row.id_aset + "'><i class='fa fa-check-square-o'></i> Set Akif</a></li>";
                        output += "				</ul>";
                        output += "	        </div>";
                    }
                    return output;
                }
            }
        ],
        rowCallback: function(row, data, iDisplayIndex) {
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