$(document).ready(function() {
	//export to excel
    $(document).on('click', '#export_excel', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'bank/laporan/excel/'+'?pabrik='+$('#pabrik_filter').val()
        );

    })
	
	
   // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
        if (oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
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
    $(document).on("change", "#pabrik_filter", function() {
        datatables_ssp();
    });
	
});

function datatables_ssp() {
    var pabrik_filter			= $("#pabrik_filter").val();
    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        pageLength: 25,
        initComplete: function() {
            var api = this.api();
            $("#sspTable_filter input").attr(
                "placeholder",
                "Press enter to start searching"
            );
            $("#sspTable_filter input").attr(
                "title",
                "Press enter to start searching"
            );
            $("#sspTable_filter input")
                .off(".DT")
                .on("keypress change", function(evt) {
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
            url: baseURL + 'bank/transaksi/get/data/bom',
            type: 'POST',
            data: function(data) {
                data.pabrik_filter	= pabrik_filter;
                data.status_sap		= 'y';
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [{
                "data": "id_data",
                "name": "id_data",
                "width": "10%",
                "render": function(data, type, row) {
                    return row.id_data;
                },
                "visible": false
            },
            {
                "data": "pabrik",
                "name": "pabrik",
                "width": "5%",
                "render": function(data, type, row) {
					return row.pabrik;
                }
            },
            {
                "data": "nama_bank",
                "name": "nama_bank",
                "width": "5%",
                "render": function(data, type, row) {
					return row.nama_bank;
                }
            },
            {
                "data": "cabang_bank",
                "name": "cabang_bank",
                "width": "10%",
                "render": function(data, type, row) {
					return row.cabang_bank;
                }
            },
            {
                "data": "nomor_rekening",
                "name": "nomor_rekening",
                "width": "10%",
                "render": function(data, type, row) {
					return row.nomor_rekening;
                }
            },
            {
                "data": "coa",
                "name": "coa",
                "width": "10%",
                "render": function(data, type, row) {
					return row.no_coa;
                }
            },
            {
                "data": "mata_uang",
                "name": "mata_uang",
                "width": "5%",
                "render": function(data, type, row) {
					return row.mata_uang;
                }
            },
            {
                "data": "tujuan",
                "name": "tujuan",
                "width": "15%",
                "render": function(data, type, row) {
					return row.caption_tujuan;
                }
            },
            {
                "data": "prioritas1",
                "name": "prioritas1",
                "width": "15%",
                "render": function(data, type, row) {
					return row.prioritas1+' - '+row.nama_prioritas1;
                }
            },
            {
                "data": "prioritas2",
                "name": "prioritas2",
                "width": "15%",
                "render": function(data, type, row) {
					return row.prioritas2+' - '+row.nama_prioritas2;
                }
            },
            {
                "data": "list_pendamping",
                "name": "list_pendamping",
                "width": "15%",
                "render": function(data, type, row) {
					if(row.caption_list_pendamping != null){
						var list_pendamping 	= ''; 
						var arr_pendamping	= row.caption_list_pendamping.slice(0, -1).split(",");
						$.each(arr_pendamping, function(x, y){
							list_pendamping += "<li>"+y+"</li>";
						});
						return '<ul>'+list_pendamping+'</ul>';
					}else{
						return '-';
					}
                }
            },
            {
                "data": "status",
                "name": "status",
                "width": "10%",
                "render": function(data, type, row) {
					return row.caption_na;
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

