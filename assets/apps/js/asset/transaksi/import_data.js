$(document).ready(function () {

    var defaultOptions = {
        destroy: true,
        columnDefs: [
            {
                'targets': 0,
                'checkboxes': {
                    'selectAll': true,
                    'selectRow': true
                }
            }
        ],
        'order': [[1, 'asc']],
        'select': {
            'style': 'multi'
        },
        'paging': false,
        'searching': true
    };
    var copiedDefOptions = Object.assign({}, JSON.parse(JSON.stringify(defaultOptions)));

    var tableTasks = $('#table-validasi-tasks').dataTable({
        order: [[0, 'asc']],
        paging: false,
        searching: false,
        ordering: false,
        bInfo: false,
    });

    var tableItems = $('#table-validasi-import').dataTable(copiedDefOptions);

    function refreshImportList(data) {
        tableTasks.DataTable().clear().draw();
        tableItems.DataTable().clear().draw();
        tableItems.DataTable().destroy();
        let details = data.details;
        let imports = [];

        /** Remove old maintenance items **/
            // $('#table-validasi-import').find('thead>tr').children().slice(6).detach();

        const defaultOptionsNew = Object.assign({}, JSON.parse(JSON.stringify(defaultOptions)));
        $.each(data.details, function (i, row) {
            // details.push({
            //     'id_jenis_detail': row.id_jenis_detail,
            //     'id_periode_detail': row.id_periode_detail,
            //     'nama_periode_detail': row.nama,
            //     'nama_jenis_detail': row.nama_jenis_detail,
            //     'keterangan': row.keterangan
            // });
            // defaultOptionsNew.columnDefs.push({
            //     'targets': 6 + i,
            //     'checkboxes': {
            //         'selectAll': true,
            //         'selectRow': true,
            //         'selectAllRender': 'T' + i + '<br/>(Pilih semua)<br/><input type="checkbox">',
            //     },
            //     'createdCell': function (td, cellData, rowData, row, col) {
            //         this.api().cell(td).checkboxes.select();
            //     }
            // });
            /** Add new maintenance item one by one **/
            // $('#table-validasi-import').find('thead>tr').append('<th>' + row.nama + '</th>');

            tableTasks.DataTable().row.add([
                'T' + i,
                row.nama,
                row.nama_jenis_detail,
                row.keterangan
            ]).draw(false);
        });

        $('#table-validasi-import').dataTable(defaultOptionsNew);

        $.each(data.imports, function (i, row) {
            let assetStatus = "<br/><span class='badge bg-red'>Aset tidak ditemukan</span>";

            let operator = data.agents.find(function (el) {
                return el.agent == row.operator;
            });

            if (!operator)
                operator = row.operator;
            else
                operator = operator.agent + " - " + operator.nama;

            let idData = {
                nomor: row.nomor_aset,
                nik: row.nik_user,
                operator: row.operator,
                tanggal: row.tanggal,
                keterangan: row.keterangan,
            };

            let dataRow = [
                // JSON.stringify(idData),
                null,
                row.nomor_aset + assetStatus,
                row.nik_user,
                row.tanggal,
                row.keterangan,
                operator,
            ];

            if (row.detail_aset) {
                idData = {
                    id_aset: row.detail_aset.id_aset,
                    nik: row.nik_user,
                    operator: row.operator,
                    tanggal: row.tanggal,
                    keterangan: row.keterangan,
                };

                dataRow = [
                    JSON.stringify(idData),
                    row.detail_aset.detail_aset_it.split('||').join('<br/>'),
                    row.detail_aset.nama_user,
                    row.tanggal,
                    row.keterangan,
                    operator,
                ];
            }

            tableItems.DataTable().row.add(dataRow).draw(false);
        });

        let detailPeriode = [];
        detailPeriode.push('Kategori : ' + data.periode.kategori);
        detailPeriode.push('Nama : ' + data.periode.nama);
        detailPeriode.push('Keterangan : ' + data.periode.keterangan);
        $('#label_detail_periode').html(detailPeriode.join('<br/>'));

        $('#id_periode_validate').val(data.periode.id_periode);
        $('#id_jenis_validate').val(data.periode.id_jenis);

        $('#box-validasi-import').addClass('in');


        $('html, body').animate({
            scrollTop: ($('#box-validasi-import').offset().top - 60)
        }, 500);
    }

    $('#jadwal_service').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        autoclose: true,
        weekStart: 1,
        language: 'id'
    });

    $('#import_excel').on('submit', function (e) {
        e.preventDefault();
        $('#box-validasi-import').removeClass('in');
        $("input[name='isproses']").val(1);
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: baseURL + 'asset/maintenance/save/' + pengguna + '/import_excel',
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                $("input[name='isproses']").val(0);
                if (data.sts == 'OK') {
                    refreshImportList(data);
                } else {
                    KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                }
                $("input[name='isproses']").val(0);
            },
            error: function (data) {
                $("input[name='isproses']").val(0);
            }
        });
        return false;
    });

    $('#import_validate').on('submit', function (e) {
        e.preventDefault();
        var table = tableItems.DataTable();

        var empty_form = validate('#import_validate', true);
        if (empty_form == 0) {
            $("input[name='isproses']").val(1);
            var formData = new FormData($(this)[0]);

            var selectedImport = table.column(0).checkboxes.selected();

            $.each(selectedImport, function (i, data) {
                formData.append('items[]', data);
            });
            // return false;
            $.ajax({
                url: baseURL + 'asset/maintenance/save/' + pengguna + '/import_validate',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $("input[name='isproses']").val(0);
                    if (data.sts == 'OK') {
                        if(data.msg){
                            KIRANAKU.alert(data.sts, data.msg, 'success');
                        }
                    } else {
                        KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                    }
                    $("input[name='isproses']").val(0);
                },
                error: function (data) {
                    $("input[name='isproses']").val(0);
                }
            });
        }
        return false;
    });

    //set on change kategori
    $(document).on('change', '#kategori', function (e) {
        get_option_jenis($(this).val(), 'id_jenis');
    });

    $(document).on("change", "#id_jenis", function (e) {
        var id_jenis = $(this).val();
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
                }
            });

        } else {
            $('#id_periode').html("<option></option>");
        }
    });

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
});