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

    datatables_ssp();

    //=======FILTER=======//
    $(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area, #main_status", function () {
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

    var tableHistory = $('#table-tab-history-pm').dataTable({
        destroy: true,
        scrollX: true,
        'order': [[0, 'asc']]
    });

    function resetTableHistory() {
        tableHistory.DataTable().clear();
    }

    $(document).on("click", ".history", function () {
        var id_aset = $(this).data("aset");
        var modal = $('#history_modal');
        $.ajax({
            url: baseURL + 'asset/maintenance/get/' + pengguna + '/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: id_aset
            },
            success: function (data) {
                resetTableHistory();
                $('.movement_fo', modal).addClass('hide');
                $('.perbaikan_fo', modal).addClass('hide');
                $.each(data.data, function (i, v) {
                    tableHistory.DataTable().row.add([
                        KIRANAKU.isNullOrEmpty(v.tanggal_mulai, moment(v.tanggal_mulai).format('DD.MM.YYYY'), '-'),
                        v.jenis_maintenance,
                        v.pm_item,
                        v.keterangan,
                        KIRANAKU.isNullOrEmpty(v.jadwal_service, moment(v.jadwal_service).format('DD.MM.YYYY'), '-'),
                        v.nama_pabrik,
                        v.nama_sub_lokasi,
                        v.nama_area,
                        v.nama_pic,
                    ]);
                });
                tableHistory.DataTable().draw();
            },
            complete: function () {
                $('#history_modal').modal('show');
                $("table.dataTable").DataTable().columns.adjust();
            }

        });
    });

    $(document).on("click", ".detail", function () {
        var modal = $('#pm_detail_fo_modal');
        var id_main = $(this).data("main");
        $.ajax({
            url: baseURL + 'asset/maintenance/get/' + pengguna + '/detail',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_main: id_main
            },
            success: function (data) {
                $('#id_main', modal).val(data.id_main);
                $('#label_no_aset', modal).html(KIRANAKU.isNullOrEmpty(data.nomor, data.nomor, '-'));
                $('#label_nama_pabrik', modal).html(KIRANAKU.isNullOrEmpty(data.nama_pabrik, data.nama_pabrik, '-'));
                $('#label_nama_lokasi', modal).html(KIRANAKU.isNullOrEmpty(data.nama_lokasi, data.nama_lokasi, '') + ' - ' + KIRANAKU.isNullOrEmpty(data.nama_sub_lokasi, data.nama_sub_lokasi, ''));
                $('#label_nama_kategori', modal).html(KIRANAKU.isNullOrEmpty(data.nama_kategori, data.nama_kategori + ' - ' + data.nama_jenis, '-'));

                if (data.nama_kategori == 'Alat Berat') {
                    $('input[name="jam_jalan"]', modal).val(KIRANAKU.isNullOrEmpty(data.jam_jalan, data.jam_jalan, '0'));
                    $('input[name="jam_jalan"]', modal).attr("readonly", true);
                    $('.alat_berat', modal).removeClass('hide');
                } else {
                    $('.alat_berat', modal).addClass('hide');
                }

                $('select[name="kondisi"]', modal).val(data.id_kondisi).trigger("change");
                $('select[name="kondisi"]', modal).attr("disabled", true);
                $('#label_nama_operator', modal).html(KIRANAKU.isNullOrEmpty(data.nama_operator, data.operator + ' - ' + data.nama_operator, '-'));
                $('#label_jadwal_service', modal).html(KIRANAKU.isNullOrEmpty(data.jadwal_service, moment(data.jadwal_service).format('DD.MM.YYYY'), '-'));
                $('input[name="tanggal_mulai"]', modal).val(KIRANAKU.isNullOrEmpty(data.tanggal_mulai, moment(data.tanggal_mulai).format('DD.MM.YYYY'), '-'));
                $('input[name="tanggal_selesai"]', modal).val(KIRANAKU.isNullOrEmpty(data.tanggal_selesai, moment(data.tanggal_selesai).format('DD.MM.YYYY'), '-'));
                resetTableItemsDetail();
                $.each(data.detail, function (i, v) {
                    tableItemsDetail.DataTable().row.add([
                        v.nama_jenis_detail,
                        v.nama_periode_detail,
                        '<input type="text" class="form-control" name="keterangan[' + v.id_main_detail + ']" id="keterangan_' + v.id_main_detail + '" value="' + KIRANAKU.isNullOrEmpty(v.keterangan, v.keterangan, '') + '" readonly/>'
                        + '<input class="cek_cb" type="hidden" name="cek[' + v.id_main_detail + ']" id="cek_' + v.id_main_detail + '" />',
                        v.id_main_detail
                    ]);
                });
                tableItemsDetail.DataTable().draw();
                $('#table-maintenance-item-detail input[type="checkbox"]').iCheck('check');
                $('#table-maintenance-item-detail input[type="checkbox"]').attr("disabled", true);
            },
            complete: function () {
                $('#pm_detail_fo_modal').modal('show');
            }

        });
    });

    function resetTableItemsDetail() {
        tableItemsDetail.DataTable().clear();
    }

    var tableItemsDetail = $('#table-maintenance-item-detail').dataTable({
        destroy: true,
        'drawCallback': function () {
            $('#table-maintenance-item-detail input[type="checkbox"]').iCheck({
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

    $(document).on("click", "button[name='action_btn']", function (e) {
        // var rows_selected = $('#sspTable').DataTable().column(0).checkboxes.selected();
        // console.log(rows_selected);
        var rows_selected = $('#sspTable input.dt-checkboxes:checkbox:checked');
        if (rows_selected.length > 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                var formData = new FormData();

                // Iterate over all selected checkboxes
                $.each(rows_selected, function (index, rowId) {
                    formData.append('id_main[]', $(rowId).val());
                });
                $.ajax({
                    url: baseURL + 'asset/maintenance/save/fo/konfirmasi_multi',
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
});

var tableDt = null;

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

    $("#sspTable").DataTable().destroy();
    tableDt = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,

        stateSave: true,
        "scrollX": true,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input').attr("placeholder", "Press enter to start searching");
            $('#sspTable_filter input').attr("title", "Press enter to start searching");
            $("#sspTable_filter input").off(".DT").on("keypress change", function (evt) {
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
                data.tanggal_awal = tanggal_awal;
                data.tanggal_akhir = tanggal_akhir;

            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        'select': {
            'style': 'multi'
        },
        columns: [
            {
                "data": "id_main",
                "name": "id_main",
                'checkboxes': {
                    'selectRow': true,
                    'selectAll': true,
                    'selectCallback': function () {
                        console.log("select");
                    }
                },
                'orderable': false,
                'createdCell': function (td, cellData, rowData, row, col) {
                    if (rowData.main_status !== 'confirmpic') {
                        $(td).find("input.dt-checkboxes").prop('disabled', true);
                        $(td).find("input.dt-checkboxes").prop('checked', false);
                    } else {
                        $(td).find("input.dt-checkboxes").prop('value', rowData.id_main);
                        $(td).find("input.dt-checkboxes").prop('checked', true);
                    }
                }
                // "render": function(data, type, row){
                //     data = '<input type="checkbox" class="dt-checkboxes">'
                //     if(row.main_status !== 'confirmpic'){
                //         data = '<input type="checkbox" class="dt-checkboxes" disabled>';
                //     }

                //     return data;
                // }      

            },
            {
                "data": "detail_aset_it",
                "name": "detail_aset",
                "width": "20%",
                "render": function (data, type, row) {
                    // return row.detail_aset.split('||').join('<br/>');
                    return row.nomor + '<br>' + row.nama_kategori + '<br>' + row.nama_jenis;
                }
            },
            {
                "data": "nama_pabrik",
                "name": "nama_pabrik",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.nama_pabrik;
                }
            },
            {
                "data": "nama_sub_lokasi",
                "name": "nama_sub_lokasi",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_sub_lokasi;
                }
            },
            {
                "data": "nama_area",
                "name": "nama_area",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.nama_area;
                }
            },
            {
                "data": "pic_asset",
                "name": "pic_asset",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.pic_asset;
                }
            },
            {
                "data": "jadwal_service",
                "name": "jadwal_service",
                "width": "5%",
                "render": function (data, type, row) {
                    let jadwal = "-";
                    if (!KIRANAKU.isNullOrEmpty(row.jadwal_service)) {
                        jadwal = moment(row.jadwal_service).format('DD.MM.YYYY');
                    }
                    return jadwal;
                }
            },
            {
                "data": "tanggal_mulai",
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
                "data": "jenis_maintenance",
                "name": "jenis_maintenance",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.jenis_maintenance;
                }
            },
            {
                "data": "nama_operator",
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
                        return '<label class="label label-warning">Waiting PIC Confirmation</label>';
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

    return tableDt;
}