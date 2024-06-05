$(document).ready(function(){
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
    $(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area, #main_status, #filter_status", function () {
        datatables_ssp();
    });

    var tableHistory = $('#table-tab-history-pm').dataTable({
        destroy: true,
        scrollX:true,
        'order': [[0, 'asc']]
    });

    function resetTableHistory() {
        tableHistory.DataTable().clear();
    }

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
            }

        });
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var rows_selected = $('#sspTable').DataTable().column(0).checkboxes.selected();
        if (rows_selected.length > 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                var formData = new FormData();

                // Iterate over all selected checkboxes
                $.each(rows_selected, function (index, rowId) {
                    formData.append('id_main[]',rowId);
                });
                $.ajax({
                    url: baseURL + 'asset/maintenance/save/it/konfirmasi_atasan',
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
    var jenis = $("#jenis").val();
    var merk = $("#merk").val();
    var pabrik = $("#pabrik").val();
    var lokasi = $("#lokasi").val();
    var area = $("#area").val();
    var main_status = $("#main_status").val();
    var filter_status = $("#filter_status").val();
	//hidden button approve
	if(filter_status=='y'){
		$("#btn_save_approve").hide();
	}else{
		$("#btn_save_approve").show();
	}
	
    $("#sspTable").DataTable().destroy();
    tableDt = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,

        "scrollX": true,
        pageLength: 10,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input')
                .off('.DT')
                .on('input.DT', function () {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: baseURL + 'asset/maintenance/get/' + pengguna + '/approval',
            type: 'POST',
            data: function (data) {
                data.jenis = jenis;
                data.merk = merk;
                data.pabrik = pabrik;
                data.lokasi = lokasi;
                data.area = area;
                data.main_status = main_status;
                data.filter_status = filter_status;
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

                    }
                },
                'orderable': false,
				'createdCell': function (td, cellData, rowData, row, col) {
					// console.log(rowData.final);
					if (filter_status == 'y'){
						$(td).find("input.dt-checkboxes").prop('disabled', true);
						$(td).find("input.dt-checkboxes").prop('checked', true);
					}
                }
            },
            {
                "data": "v_aset_pm_approval.detail_aset_it",
                "name": "detail_aset_it",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.detail_aset_it.split('||').join('<br/>');
                }
            },
            {
                "data": "v_aset_pm_approval.nama_pabrik",
                "name": "nama_pabrik",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.nama_pabrik;
                }
            },
            {
                "data": "v_aset_pm_approval.nama_sub_lokasi",
                "name": "nama_sub_lokasi",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_sub_lokasi;
                }
            },
            {
                "data": "v_aset_pm_approval.nama_area",
                "name": "nama_area",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.nama_area;
                }
            },
            {
                "data": "v_aset_pm_approval.nama_pic",
                "name": "nama_pic",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_pic;
                }
            },
            {
                "data": "v_aset_pm_approval.jadwal_service",
                "name": "jadwal_service",
                "width": "5%",
                "render": function (data, type, row) {
                    return KIRANAKU.isNullOrEmpty(row.jadwal_service,moment(row.jadwal_service).format('DD.MM.YYYY'),'-');
                }
            },
            {
                "data": "v_aset_pm_approval.tanggal_mulai",
                "name": "tanggal_mulai",
                "width": "5%",
                "render": function (data, type, row) {
                    return KIRANAKU.isNullOrEmpty(row.tanggal_mulai,moment(row.tanggal_mulai).format('DD.MM.YYYY'),'-');
                }
            },
            {
                "data": "tanggal_selesai",
                "name": "tanggal_selesai",
                "width": "5%",
                "render": function (data, type, row) {
                    return KIRANAKU.isNullOrEmpty(row.tanggal_selesai,moment(row.tanggal_selesai).format('DD.MM.YYYY'),'-');
                }
            },
            {
                "data": "v_aset_pm_approval.jenis_maintenance",
                "name": "jenis_maintenance",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.jenis_maintenance;
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
                "data": "final",
                "name": "final",
                "width": "5%",
                "searchable": false,
                "render": function (data, type, row) {
                    if (row.final == 'n')
                        return '<label class="label label-default">Outstanding</label>';
                    else if (row.final == 'y')
                        return '<label class="label label-success">Approved</label>';
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
            },
        ],
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