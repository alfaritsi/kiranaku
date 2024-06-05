$(document).ready(function() {
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
    $(document).on("change", "#id_tipe_filter, #id_kategori_filter, #status_filter", function() {
        datatables_ssp();
    });

    //edit, copy dan change  
    $(document).on("click", ".edit", function() {
        resetForm_use($('.form-transaksi-vendor'), 'edit');
        var id_data = $(this).data("edit");
        var btn_save = $(this).data("btn_save");
        var btn_status = $(this).data("btn_status");
        $.ajax({
            url: baseURL + 'material/transaksi/get/spec',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data,
                btn_status: btn_status
            },
            success: function(data) {
                if (btn_status == 'copy') {
                    $(".modal-title").html("Copy Form Item Spec");
                } else if (btn_status == 'change') {
                    $(".modal-title").html("Change Description");
                } else {
                    $(".modal-title").html("Form Item Spec");
                }
                $.each(data, function(i, v) {
                    console.log(data);
                    if (btn_status == 'copy') {
                        //cek kode sap
                        var id_item_group = v.id_item_group;
                        var id_item_name = v.id_item_name;
                        $.ajax({
                            url: baseURL + 'material/transaksi/get/nomor',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                id_item_group: id_item_group,
                                id_item_name: id_item_name
                            },
                            success: function(data) {
                                var no = 0;
                                var terpakai = data.terpakai.join(",");
                                if (data.terpakai.length > 0) {
                                    kiranaConfirm({
                                        title: "Konfirmasi",
                                        text: "Kode Material " + terpakai + " Sudah Terpakai di SAP dan akan diganti dengan Kode " + data.nomor + " ,apakah proses akan dilanjutkan?",
                                        dangerMode: true,
                                        successCallback: function() {
                                            $("#code").val(data.nomor);
                                            $("input[name='code']").val(data.nomor);
                                        },
                                        failCallback: function() {
                                            $("#id_item_name").val(0).trigger('change.select2');
                                            $("#code").val('');
                                            $("input[name='code']").val('');
                                        }
                                    });
                                } else {
                                    $("#id_data").val('');
                                    $("#code").val(data.nomor);
                                    $("input[name='code']").val(data.nomor);
                                }
                            }
                        });
                    } else {
                        $("#id_data").val(v.id_data);
                        $("input[name='code']").val(v.code);
                    }
                    $("#id_item_request").val(v.id_item_request);
                    $("select[name='id_item_group']").val(v.id_item_group).trigger("change.select2");
                    $("select[name='msehi_uom']").val(v.msehi_uom).trigger("change");
                    //gambar
                    if (v.list_gambar !== null) {
                        var list_gambar = v.list_gambar.slice(0, -1).split(",");
                        var array = [];
                        var det = "";
                        $.each(list_gambar, function(x, y) {
                            console.log(y);
                            det += "<img src='" + y + "' class='img-thumbnail' style='height:80px;'>";
                        });
                        $("#show_images").html(det);
                    }
                    // $("select[name='id_item_name']").val(v.id_item_name).trigger("change");
                    //load id_item_name
                    var output = '';
                    $.each(v.arr_item, function(x, y) {
                        var selected = (y.id_item_name == v.id_item_name ? 'selected' : '');
                        output += '<option value="' + y.id_item_name + '" ' + selected + '>' + y.description + '</option>';
                    });
                    $("select[name='id_item_name']").html(output).select2();

                    $("select[name='msehi_order']").val(v.msehi_order).trigger("change");

                    $("input[name='old_material_number']").val(v.old_material_number);
                    $("input[name='description']").val(v.description);
                    $("input[name='description_awal']").val(v.description);

                    $("select[name='ekgrp']").val(v.ekgrp).trigger("change");
                    $("select[name='availability_check']").val(v.availability_check).trigger("change");
                    var plant = v.plant.split(",");
                    $("select[name='plant[]']").val(plant).trigger("change");
                    // load_plant(v.plant);
                    $("select[name='lgort']").val(v.lgort).trigger("change");
                    // //load lgort
                    // var output = '';
                    // $.each(v.arr_lgort, function (x, y) {

                    // var selected = (y.lgort == v.lgort ? 'selected' : '');
                    // output += '<option value="' + y.lgort + '" '+selected+'>[' + y.lgort + '] ' +y.lgobe+'</option>';
                    // });
                    // $("select[name='lgort']").html(output).select2();

                    $("select[name='mrp_group']").val(v.mrp_group).trigger("change");
                    $("input[name='service_level']").val(v.service_level);
                    $("select[name='mrp_type']").val(v.mrp_type).trigger("change");
                    $("select[name='disls']").val(v.disls).trigger("change");
                    $("select[name='dispo']").val(v.dispo).trigger("change");
                    $("select[name='period_indicator']").val(v.period_indicator).trigger("change");
                    // $("select[name='sales_plant']").val(v.sales_plant).trigger("change");
                    if (v.sales_plant == 'X') {
                        $("input[name='sales_plant']").attr('checked');
                        $("input[name='sales_plant']").bootstrapToggle('on');
                    } else {
                        $("input[name='sales_plant']").removeAttr('checked');
                        $("input[name='sales_plant']").bootstrapToggle('off');
                    }

                    $("select[name='gen_item_cat_group']").val(v.gen_item_cat_group).trigger("change");
                    // $("select[name='vtweg']").val(v.vtweg).trigger("change");
                    //load vtweg
                    if (v.vtweg != null) {
                        var output = '';
                        $.each(v.arr_vtweg, function(x, y) {
                            var selected = v.vtweg.split(',').some(vv => vv === y.vtweg) ? 'selected' : '';
                            output += '<option value="' + y.kd + '" ' + selected + '>[' + y.kd + '] ' + y.nm + '</option>';
                        });
                        $("select[name='vtweg[]']").html(output).select2();
                    }

                    $("select[name='material_pricing_group']").val(v.material_pricing_group).trigger("change");
                    $("select[name='spart']").val(v.spart).trigger("change");
                    $("select[name='material_statistic_group']").val(v.material_statistic_group).trigger("change");
                    $("input[name='net_weight']").val(v.net_weight);
                    $("select[name='acct_assignment_group']").val(v.acct_assignment_group).trigger("change");
                    $("input[name='gross_weight']").val(v.gross_weight);
                    $("select[name='prctr']").val(v.prctr).trigger("change");
                    $("select[name='taxm1']").val(v.taxm1).trigger("change");
                    if (v.xchpf == 'X') {
                        $("input[name='xchpf']").attr('checked');
                        $("input[name='xchpf']").bootstrapToggle('on');
                    } else {
                        $("input[name='xchpf']").removeAttr('checked');
                        $("input[name='xchpf']").bootstrapToggle('off');
                    }
                    $("input[name='detail']").val(v.detail);
                    $("input[name='prmod']").val(v.prmod);
                    $("input[name='peran']").val(v.peran);
                    $("input[name='anzpr']").val(v.anzpr);
                    $("input[name='kzini']").val(v.kzini);
                    $("input[name='siggr']").val(v.siggr);
                    $("input[name='umrez']").val(v.umrez);


                    //matrix
                    $.each(v.arr_kolom, function(x, y) {
                        console.log(x);
                        //matrix jika matrix option
                        if (y.req == 'y') {
                            $('#' + y.id_field).prop('required', true);
                            $('#' + y.id_field).prop('disabled', false);
                        } else {
                            $('#' + y.id_field).prop('required', false);
                            $('#' + y.id_field).prop('disabled', true);
                        }
                    });
                    //set default by request sipi
                    $('#code').prop('required', true);
                    $('#code').prop('disabled', true);
                    $('#detail').prop('required', true);
                    $('#detail').prop('disabled', true);
                    $('#msehi_uom').prop('required', true);
                    $('#msehi_uom').prop('disabled', false);
                    $('#xchpf').prop('required', false);
                    $('#xchpf').prop('disabled', false);

                    $('#msehi_order').prop('required', false);
                    $('#msehi_order').prop('disabled', false);
                    if ((v.msehi_order == 0) || (v.msehi_order == null)) {
                        $('#umrez').prop('required', false);
                        $('#umrez').prop('disabled', false);
                    }
                    $('#old_material_number').prop('required', false);
                    $('#old_material_number').prop('disabled', false);

                    $('#sales_plant').prop('required', false);
                    $('#sales_plant').prop('disabled', false);


                });
            },
            complete: function() {
                $("#btn_change").hide();
                if (btn_save == 'hidden') {
                    $('.form-control-hide').prop('disabled', true);
                    $("#gambar").hide();
                    $("#btn_save").hide();
                }
                if (btn_status == 'change') {
                    $('.form-control-hide').prop('disabled', true);
                    $("#gambar").hide();
                    $("#btn_save").hide();
                    $("#btn_change").show();
                    $('#description').prop('required', true);
                    $('#description').prop('disabled', false);
                }
                $('#add_modal').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_modal')
                });

            }

        });
    });


    $(document).on("click", ".nonactive, .setactive, .delete", function(e) {
        $.ajax({
            url: baseURL + "vendor/transaksi/set/spec",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: $(this).data($(this).attr("class")),
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
    //add
    $(document).on("click", "button[name='action_btn']", function(e) {
        var empty_form = validate('.form-transaksi-vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				let acc_group = $("input[name='acc_group']").val();
				if (acc_group.length > 4) {
					kiranaAlert("notOK", "Acc Group maksimal 4 karakter.", "error", "no");
				}
				let nama = $("input[name='nama']").val();
				if (nama.length > 35) {
					kiranaAlert("notOK", "Nama Supplier maksimal 35 karakter.", "error", "no");
				}
				let alamat = $("input[name='alamat']").val();
				if (alamat.length > 60) {
					kiranaAlert("notOK", "Alamat Supplier maksimal 60 karakter.", "error", "no");
				}
				// let no = $("input[name='no']").val();
				// if (no.length > 10) {
					// kiranaAlert("notOK", "Nomor Rumah maksimal 10 karakter.", "error", "no");
				// }
				// let kode_pos = $("input[name='kode_pos']").val();
				// if (kode_pos.length > 10) {
					// kiranaAlert("notOK", "Kode Pos maksimal 10 karakter.", "error", "no");
				// }
				// let kota = $("input[name='kota']").val();
				// if (kota.length > 40) {
					// kiranaAlert("notOK", "Kabupaten/ Kota maksimal 40 karakter.", "error", "no");
				// }
				// let email = $("input[name='email']").val();
				// if (email.length > 241) {
					// kiranaAlert("notOK", "Email maksimal 241 karakter.", "error", "no");
				// }
				// let npwp = $("input[name='npwp']").val();
				// if (npwp.length > 16) {
					// kiranaAlert("notOK", "Email maksimal 16 karakter.", "error", "no");
				// }
				// let ktp = $("input[name='ktp']").val();
				// if (ktp.length > 18) {
					// kiranaAlert("notOK", "Email maksimal 18 karakter.", "error", "no");
				// }
				
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-vendor")[0]);
				console.log();
				$.ajax({
					url: baseURL + 'vendor/transaksi/save/vendor',
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

    //open modal for add     
    $(document).on("click", "#add_button", function(e) {
		$('#id_data').val("");
        resetForm_use($('.form-transaksi-vendor'));
        $('#add_modal').modal('show');
        $("#btn_change").hide();
        $('.select2modal').select2({
            dropdownParent: $('#add_modal')
        });

    });

});

function resetForm_use($form, $act) {
    $('#myModalLabel').html("Form Input Master Vendor");
    $('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
    $form.find('input:text, input:password, input:file,  textarea').val("");
    $form.find('input:text, input:password, input:file,  textarea').prop('disabled', false);
    $form.find('select').val(0);
    $form.find('select').prop('disabled', false);
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    $form.find('input:radio, input:checkbox').prop('disabled', false);

    // $('#service_level').val("").prop('disabled', false);
    $('#net_weight').val("").prop('disabled', false);
    $('#gross_weight').val("").prop('disabled', false);
    $("#plant").val(0).trigger("change");
    $("#vtweg").val(0).trigger("change");
    $("#sales_plant").find('checkbox').removeAttr('checked');
    $('.switch-onoff').bootstrapToggle('off');
    $('.switch-onoff').removeAttr('checked');;
    $('#plant_extend').prop('disabled', false);
    if ($act != 'edit') {
        $("#show_images").hide();
    }
    $("#gambar").show();
    $("#btn_save").show();
    $('#isproses').val("");
    $('#isconvert').val('0');
    $('#code').prop('disabled', true);
    $('#detail').prop('disabled', true);
    validateReset('.form-transaksi-vendor');
}

function resetForm_extend($form) {
    $('#plant_extend').prop('disabled', false);
}

function datatables_ssp() {
    var id_tipe 		= $("#id_tipe_filter").val();
    var id_kategori		= $("#id_kategori_filter").val();
    var status_filter 	= $("#status_filter").val();

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
        ajax: {
            url: baseURL + 'vendor/transaksi/get/data/bom',
            type: 'POST',
            data: function(data) {
                data.id_tipe = id_tipe;
                data.id_kategori = id_kategori;
                data.status_filter = status_filter;
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
                "width": "15%",
                "render": function(data, type, row) {
                    return row.id_data;
                },
                "visible": false
            },
            {
                "data": "nama",
                "name": "nama",
                "width": "15%",
                "render": function(data, type, row) {
                    return '<b>'+row.nama+'</b><br>'+row.nama_propinsi+'<br>'+row.telepon;
                }
            },
            {
                "data": "nama_tipe",
                "name": "nama_tipe",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.nama_tipe+"<br><strong class='badge bg-blue'><i class='fa fa-files-o'></i> &nbsp; 1 ( 4 ) Dokumen Mandatory</strong><br><strong class='badge bg-blue'><i class='fa fa-files-o'></i> &nbsp; 1 ( 2 ) Dokumen Non Mandatory</strong>";
                }
            },
            {
                "data": "nama_kategori",
                "name": "nama_kategori",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.nama_kategori+"<br><strong class='badge bg-blue'><i class='fa fa-files-o'></i> &nbsp; 1 ( 1 ) Dokumen</strong>";
                }
            },
            {
                "data": "alamat",
                "name": "alamat",
                "width": "5%",
                "render": function(data, type, row) {
                    return '<b>80 (Lulus)</b><br>(Nilai Minimal 70)';
                }
            },
            {
                "data": "req",
                "name": "req",
                "width": "5%",
                "render": function(data, type, row) {
                    if (row.req == 'n') {
                        return '<label class="label label-success">Completed</label>';
                    } else {
                        return '<label class="label label-warning">Requested</label>';
                    }
                }
            },
            {
                "data": "id_data",
                "name": "id_data",
                "width": "5%",
                "render": function(data, type, row) {
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if (row.req == 'y') {
						output += "				<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_data + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
					}
					if (row.req == 'n') {
						output += "				<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_data + "' data-btn_status='copy'><i class='fa fa-copy'></i> Copy Data</a></li>";
						output += "				<li><a href='javascript:void(0)' class='extend' data-edit='" + row.id_data + "'><i class='fa fa-arrows'></i> Extend Data</a></li>";
						output += "				<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_data + "' data-btn_save='hidden'><i class='fa fa-search'></i> Detail</a></li>";
						output += "				<li><a href='javascript:void(0)' class='status' data-edit='" + row.id_data + "'><i class='fa fa-retweet'></i> Status SAP</a></li>";
						output += "				<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_data + "' data-btn_status='change'><i class='fa fa-pencil-square'></i> Change Description</a></li>";
						output += "				<li><a href='javascript:void(0)' class='history' data-edit='" + row.id_data + "'><i class='fa fa-h-square'></i> History Change</a></li>";
					}
					output += "				</ul>";
					output += "	        </div>";
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

function load_plant(plant) {
    alert(plant);
    $.ajax({
        url: baseURL + 'material/master/get/plant',
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            if (data) {
                var output = '';
                $.each(data, function(i, v) {
                    output += '<option value="' + v.plant + '">' + v.plant + '</option>';
                });
                $("select[name='plant[]']").html(output);
            }
        },
        complete: function() {
            // var plant	= plant.split(",");
            $("select[name='plant[]']").val(plant).trigger("change");
        }
    });
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