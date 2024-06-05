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

    //=======FILTER=======//
    $(document).on("change", "#filter_pabrik, #filter_bulan, #filter_unit", function() {
        datatables_ssp();
    });

    $(document).on("click", "#btn-upload", function () {
		resetForm_use($('.form-master-spl-imp'));
		$('#bulan_tahun').datepicker({
			startView: 'year',
			minViewMode: "months",
			format: 'mm.yyyy',
			changeMonth: true,
			changeYear: true,
			autoclose: true
		});
		$(".select2").select2();
        $("#modal_master_plan").modal("show");
    });
    
    $('#filter_bulan').datepicker({
        startView: 'year',
        minViewMode: "months",
        format: 'mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
    });
	
    //imp
    $(document).on("click", "button[name='action_btn_imp']", function(e) {
        var empty_form = validate('.form-master-spl-imp');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-master-spl-imp")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'spl/master/save/excel',
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
	
});

function datatables_ssp() {
    var filter_pabrik	= $("#filter_pabrik").val();
    var filter_bulan	= $("#filter_bulan").val();
    var filter_unit		= $("#filter_unit").val();

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
		order: [[1, 'desc']],
        ajax: {
            url: baseURL + 'spl/master/get/data/bom',
            type: 'POST',
            data: function(data) {
                data.filter_pabrik 	= filter_pabrik;
                data.filter_bulan	= filter_bulan;
                data.filter_unit	= filter_unit;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "plant",
                "name": "plant",
                "width": "10%",
                "render": function(data, type, row) {
					return row.plant;
                }
            },
            {
                "data": "tanggal_format",
                "name": "tanggal_format",
                "width": "10%",
                "render": function(data, type, row) {
					return row.tanggal_format;
                }
            },
            {
                "data": "nama_departemen",
                "name": "nama_departemen",
                "width": "20%",
                "render": function(data, type, row) {
					return row.nama_departemen;
                }
            },
            {
                "data": "nama_seksie",
                "name": "nama_seksie",
                "width": "15%",
                "render": function(data, type, row) {
					return row.nama_seksie;
                }
            },
            {
                "data": "nama_unit",
                "name": "nama_unit",
                "width": "10%",
                "render": function(data, type, row) {
					return row.nama_unit;
                }
            },
            {
                "data": "shift",
                "name": "shift",
                "width": "5%",
                "render": function(data, type, row) {
					return row.shift;
                }
            },
            {
                "data": "jumlah_jam_lembur",
                "name": "jumlah_jam_lembur",
                "width": "5%",
                "render": function(data, type, row) {
					return row.jumlah_jam_lembur;
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

function resetForm_use($form) {
    $('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
    $form.find('input:text, input:password, input:file,  textarea').val("");
    $form.find('input:text, input:password, input:file,  textarea').prop('disabled', false);
    $form.find('select').val(0);
    $form.find('select').prop('disabled', false);
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    $form.find('input:radio, input:checkbox').prop('disabled', false);

    validateReset('.form-transaksi-spec');
}
function validateReset(target = 'form') {
    var element = $("input, select, textarea", $(target));
    $.each(element, function(i, v) {
        if (v.tagName == 'SELECT' && v.nextSibling.firstChild != null) {
            v.nextSibling.firstChild.firstChild.style.borderColor = "#d2d6de";
        }
        v.style.borderColor = "#d2d6de";
    });
}
