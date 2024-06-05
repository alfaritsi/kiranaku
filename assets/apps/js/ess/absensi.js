$(document).ready(function () {
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
	get_limit_date();
    $(document).on("change", "#group_produksi", function() {
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

    //open modal for req     
	$(document).on("click", "#btn_detail", function(e){
		var group_produksi	= $(this).data("group_produksi");
		var bagian			= $(this).data("bagian");
		$.ajax({
			url: baseURL+'ess/laporan/get/absen',
			type: 'POST',
			dataType: 'JSON',
			data: {
				group_produksi : group_produksi,
				bagian : bagian
			},
			success: function(data){
				var det	= "";
				var count = 0;
				$.each(data, function(i,v){
					count++;
					det	+= 		"<tr>";
					det	+= 			"<td>"+v.bagian+"</td>";
					det	+= 			"<td>Shift "+v.sub_bagian+"</td>";
					det	+= 			"<td>"+v.group_produksi+"</td>";
					det	+= 			"<td>"+v.nik+"</td>";
					det	+= 			"<td>"+v.nama+"</td>";
					det	+= 		"</tr>";
				});
				$("#show_detail").html(det);
				$('#count').val(count);
			},
			complete: function () {
                setTimeout(function () {
                    $("table.datatable-periode").DataTable().columns.adjust();
                }, 1500);				
				
				$('#detail_modal').modal('show');
				
			}
		});
	});

});

function get_limit_date() {
	var minDate = new Date(regenerateDatetimeFormat($("#tanggal_awal_filter").val(), "DD.MM.YYYY", "YYYY-MM-DD"));
	$('#tanggal_akhir_filter').datepicker('setStartDate', minDate);
	var maxDate = new Date(regenerateDatetimeFormat($('#tanggal_akhir_filter').val(), "DD.MM.YYYY", "YYYY-MM-DD"));
	$('#tanggal_awal_filter').datepicker('setEndDate', maxDate);
}

function datatables_ssp() {
	var group_produksi 	= $("#group_produksi").val();
	let tanggal_awal = $("#tanggal_awal_filter").val();
	let tanggal_akhir = $("#tanggal_akhir_filter").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        pageLength: 10,
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
            url: baseURL + 'ess/laporan/get/absensi/bom',
            type: 'POST',
            data: function(data) {
                data.group_produksi = group_produksi;
                data.tanggal_awal 	= tanggal_awal;
                data.tanggal_akhir 	= tanggal_akhir;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "group_produksi",
                "name": "group_produksi",
				"width": "15%",
                "render": function(data, type, row) {
					return row.group_produksi;
                }
            },
            {
                "data": "nik",
                "name": "nik",
				"width": "15%",
                "render": function(data, type, row) {
					return row.nik;
                }
            },
            {
                "data": "nama",
                "name": "nama",
				"width": "15%",
                "render": function(data, type, row) {
                    return row.nama;
                }
            },
            {
                "data": "jabatan",
                "name": "jabatan",
				"width": "15%",
                "render": function(data, type, row) {
                    return row.jabatan;
                }
            },
            {
                "data": "tanggal",
                "name": "tanggal",
				"width": "15%",
                "render": function(data, type, row) {
                    return row.tanggal.toString().split("-").reverse().join(".");
                    // return row.tanggal;
                }
            },
            {
                "data": "jam_ci",
                "name": "jam_ci",
				"width": "15%",
                "render": function(data, type, row) {
					var ci = row.list_ci;
					return ci;
                }
            },
            {
                "data": "jam_co",
                "name": "jam_co",
				"width": "15%",
                "render": function(data, type, row) {
					return row.list_co;
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



