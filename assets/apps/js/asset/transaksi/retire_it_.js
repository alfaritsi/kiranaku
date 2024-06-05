$(document).ready(function () {
    $(document).on("change", "#opt_alasan", function (e) {
        var value = $(this).val();
		if(value=='Lain-Lain'){
			var nil = '<input type="text" class="form-control" name="alasan" id="alasan">';	
			$("#show_alasan").html(nil);
		}else{
			var nil = '<input type="hidden" class="form-control" name="alasan" id="alasan" value="'+value+'">';	
			$("#show_alasan").html(nil);
		}
    });

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
    $(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area, #kondisi, #idle", function () {
        datatables_ssp();
    });

    //set retire
    $(document).on("click", ".set_retire", function () {
        var id_aset = $(this).data("id_aset");

        $.ajax({
            url: baseURL + 'asset/transaksi/get/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: id_aset
            },
            success: function (data) {
                console.log(data);
                $(".title-form").html("Edit Setting Program Batch");
                $.each(data, function (i, v) {
					$('#label_nomor_sap_move').html(v.nomor_sap);
					$('#label_kode_barang_move').html(v.KODE_BARANG);
					$('#label_nama_jenis_move').html(v.nama_jenis);
					$('#label_nama_pabrik_move').html(v.nama_pabrik);
					$('#label_nama_lokasi_move').html(v.nama_lokasi);
					$('#label_nama_area_move').html(v.nama_area);
					$('#label_nama_karyawan_move').html(v.nama_karyawan);
					$('#label_kondisi_move').html(v.nama_kondisi);
					
					$("input[name='id_aset']").val(v.id_aset);
                    $("input[name='nama_user']").val(v.NAMA_USER);
                    $("input[name='pic']").val(v.pic);
                    $("input[name='pic_awal']").val(v.pic);
					$("input[name='id_kondisi_awal']").val(v.id_kondisi);
                    $("input[name='id_sub_lokasi_awal']").val(v.id_sub_lokasi);
                    $("input[name='id_area_awal']").val(v.id_area);

                });

            },
            complete: function () {
                $('#set_retire_modal').modal('show');
            }

        });
    });
	
    $(document).on("click", ".nonactive, .setactive, .delete", function (e) {
        $.ajax({
            url: baseURL + "asset/transaksi/set/it",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset: $(this).data($(this).attr("class")),
                type: $(this).attr("class")
            },
            success: function (data) {
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

	// save retire
    $(document).on("click", "button[name='action_btn_retire']", function (e) {
        var empty_form = validate('.form-transaksi-retire');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-retire")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'asset/transaksi/save/set_retire',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
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


});


function datatables_ssp() {
    var jenis = $("#jenis").val();
    var merk = $("#merk").val();
    var pabrik = $("#pabrik").val();
    var lokasi = $("#lokasi").val();
    var area = $("#area").val();
    var kondisi = $("#kondisi").val();
    // var idle = $("#idle").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
		ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        // pageLength: 10,
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
                    if(evt.type == "change"){
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
            url: baseURL + 'asset/transaksi/get/retire',
            type: 'POST',
            data: function (data) {
                data.jenis = jenis;
                data.merk = merk;
                data.pabrik = pabrik;
                data.lokasi = lokasi;
                data.area = area;
				data.kondisi = kondisi;
				// data.idle = idle;
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "id_aset",
                "name": "id_aset",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.id_aset;
                },
                "visible": false
            },
            {
                "data": "kode_barang",
                "name": "kode_barang",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.kode_barang;
                }
            },
            {
                "data": "nomor_sap",
                "name": "nomor_sap",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nomor_sap;
                }
            },
            {
                "data": "nama_jenis",
                "name": "nama_jenis",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_jenis;
                }
            },
            {
                "data": "nama_merk",
                "name": "nama_merk",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_merk;
                },
                "visible": false
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
                "data": "nama_lokasi",
                "name": "nama_lokasi",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_lokasi;
                }
            },
            {
                "data": "nama_sub_lokasi",
                "name": "nama_sub_lokasi",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_sub_lokasi;
                },
                "visible": false
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
                "data": "nama_user",
                "name": "nama_user",
                "width": "5%",
                "render": function (data, type, row) {
                    if (row.nama_pic)
                        return row.pic + " - " + row.nama_pic;
                    else
                        return row.nama_user;
                }
            },
            {
                "data": "nama_vendor",
                "name": "nama_vendor",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nama_vendor;
                },
                "visible": false
            },
            {
                "data": "id_kondisi",
                "name": "id_kondisi",
                "width": "5%",
                "render": function (data, type, row) {
                    if (row.id_kondisi == 1) {
                        return '<label class="label label-success">Beroperasi</label>';
                    } else if(row.id_kondisi == 2) {
                        return '<label class="label label-danger">Tidak Beroperasi</label>';
                    } else if(row.id_kondisi == 4){
						return '<label class="label label-warning">Dalam Perbaikan</label>';
					} else if(row.id_kondisi == 5){
						return '<label class="label label-danger">Scrap</label>';
					} else if(row.id_kondisi == 6){
						return '<label class="label label-warning">Stand By</label>';
					}else{
						return '<label class="label label-danger">Tidak Beroperasi</label>';
					}
                },
                "visible": true
            },            {
                "data": "status",
                "name": "status",
                "width": "5%",
                "render": function (data, type, row) {
                    if (row.id_kondisi == 1) {
                        return '<label class="label label-success">Beroperasi</label>';
                    } else if(row.id_kondisi == 2) {
                        return '<label class="label label-danger">Tidak Beroperasi</label>';
                    } else if(row.id_kondisi == 4){
						return '<label class="label label-warning">Dalam Perbaikan</label>';
					} else if(row.id_kondisi == 5){
						return '<label class="label label-danger">Scrap</label>';
					} else if(row.id_kondisi == 6){
						return '<label class="label label-warning">Stand By</label>';
					}else{
						return '<label class="label label-danger">Tidak Beroperasi</label>';
					}
                },
                "visible": true
            },
            {
                "data": "na",
                "name": "na",
                "width": "5%",
                "render": function (data, type, row) {
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
                "render": function (data, type, row) {
                    if (row.na == 'n') {
                        output = "			<div class='input-group-btn'>";
                        output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                        output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a href='javascript:void(0)' class='set_retire' data-id_aset='" + row.id_aset + "'><i class='fa fa-trash'></i> Set Retire</a></li>";	
                        output += "					<li class='divider'></li>";
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