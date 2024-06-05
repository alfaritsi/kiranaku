$(document).ready(function() {
	//export to excel
    $(document).on('click', '#export_excel_aim', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'mentor/laporan/excel/aim/'+'?filter_status='+$('#filter_status').val()
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
    $(document).on("change", "#filter_status", function() {
        datatables_ssp();
    });

});

function resetForm_use($form, $act) {
    $('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
    $form.find('input:text, input:password, input:file,  textarea, input:hidden').val("");
    $form.find('input:text, input:password, input:file,  textarea, input:hidden').prop('disabled', false);
    $form.find('select').val(0);
    $form.find('select').prop('disabled', false);
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    $form.find('input:radio, input:checkbox').prop('disabled', false);

    validateReset('.form-input-mentee');
}

function resetForm_extend($form) {
    $('#plant_extend').prop('disabled', false);
}

function datatables_ssp() {
    var filter_status		= $("#filter_status").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
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
		order: [[0, 'desc']],
        ajax: {
            url: baseURL + 'mentor/laporan/get/all/bom',
            type: 'POST',
            data: function(data) {
                data.jenis	= 'aim';
                data.filter_status	= filter_status;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "nomor",
                "name": "nomor",
                "width": "10%",
                "render": function(data, type, row) {
					return row.nomor;
                }
            },
            {
                "data": "nik_mentor",
                "name": "nik_mentor",
                "render": function(data, type, row) {
					return row.nik_mentor;
                }
            },
            {
                "data": "nama_mentor",
                "name": "nama_mentor",
                "render": function(data, type, row) {
					return row.nama_mentor;
                }
            },
            {
                "data": "nik_mentor_additional",
                "name": "nik_mentor_additional",
                "render": function(data, type, row) {
					if(row.nik_mentor_additional==null){
						return '-';
					}else{
						return row.nik_mentor_additional;
					}
                }
            },
            {
                "data": "nama_mentor_additional",
                "name": "nama_mentor_additional",
                "render": function(data, type, row) {
					if(row.nama_mentor_additional==null){
						return '-';
					}else{
						return row.nama_mentor_additional;
					}
                }
            },
            {
                "data": "tanggal_sesi2_rencana_format",
                "name": "tanggal_sesi2_rencana_format",
                "render": function(data, type, row) {
					return '<b>Rencana:</b><br>'+row.tanggal_sesi2_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_sesi2_aktual_format;
                }
            },
            {
                "data": "nik_mentee",
                "name": "nik_mentee",
                "render": function(data, type, row) {
					return row.nik_mentee;
                }
            },
            {
                "data": "nama_mentee",
                "name": "nama_mentee",
                "render": function(data, type, row) {
					return row.nama_mentee;
                }
            },
            {
                "data": "nama_departemen_mentee",
                "name": "nama_departemen_mentee",
                "render": function(data, type, row) {
					return row.nama_departemen_mentee;
                }
            },
            {
                "data": "sasaran_pengembangan",
                "name": "sasaran_pengembangan",
                "render": function(data, type, row) {
					return row.sasaran_pengembangan;
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
