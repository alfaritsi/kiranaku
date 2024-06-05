$(document).ready(function() {
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
    $(document).on("change", "#id_item_group_filter, #id_item_name_filter, #status_filter, #filter_request_status, #filter_classification", function() {
        datatables_ssp();
    });


    //set on change id_item_group_filter
    $(document).on("change", "#id_item_group_filter", function(e) {
        var id_item_group_filter = $(this).val();
        $.ajax({
            url: baseURL + 'material/transaksi/get/item',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_group_filter: id_item_group_filter
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Pilih Item Name</option>';
                $.each(data, function(i, v) {
                    console.log(data);
                    value += '<option value="' + v.id_item_name + '">' + v.description + '</option>';
                });
                $('#id_item_name_filter').html(value);


            }
        });
    });
    // //set on change plant for get lgort(storage location)
    // $(document).on("change", "#plant", function(e){
    // var plant	= $(this).val();
    // $.ajax({
    // url: baseURL+'material/transaksi/get/lgort',
    // type: 'POST',
    // dataType: 'JSON',
    // data: {
    // plant	: plant
    // },
    // success: function(data){
    // var value = '';
    // value += '<option value="0">Pilih Storage Location</option>';
    // $.each(data, function(i,v){
    // value += '<option value="'+v.lgort+'">['+v.lgort+'] '+v.lgobe+'</option>';
    // });
    // $('#lgort').html(value);
    // }
    // });
    // });
    //set on change mrp_type
    $(document).on("change", "#mrp_type", function(e) {
        var mrp_type = $(this).val();
        if ((mrp_type == 'ND') || (mrp_type == null)) {
            $('#disls').prop('required', false);
        } else {
            $('#disls').prop('required', true);
        }

    });
    //set on change msehi_order
    $(document).on("change", "#msehi_order", function(e) {
        var msehi_order = $(this).val();
        if ((msehi_order == 0) || (msehi_order == null)) {
            $('#umrez').prop('required', false);
        } else {
            $('#umrez').prop('required', true);
        }

    });

    //set on change id_item_group
    $(document).on("change", "#id_item_group", function(e) {
        var id_item_group = $(this).val();
        $.ajax({
            url: baseURL + 'material/transaksi/get/item',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_group: id_item_group,
                na: 'n'
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Pilih Item Name</option>';
                $.each(data, function(i, v) {
                    // console.log(data);
                    value += '<option value="' + v.id_item_name + '">' + v.description + '</option>';
                });
                $('#id_item_name').html(value);
            }
        });
    });
    //set on change id_item_name
    $(document).on("change", "#id_item_name", function(e) {
        // var id_item_group	= $(this).val();
        var id_item_name = $(this).val();
        $.ajax({
            url: baseURL + 'material/transaksi/get/item',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_name: id_item_name
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    $.each(v.arr_kolom, function(x, y) {
                        if (y.req == 'y') {
                            $('#' + y.id_field).prop('required', true);
                            $('#' + y.id_field).prop('disabled', false);
                            //cek jika input option
                            if (y.tabel_sap != null) {
                                $("select[name='" + y.id_field + "']").val(y.def).trigger('change');
                            } else {
                                if (y.def > 0) {
                                    $("input[name='" + y.id_field + "']").val(parseInt(y.def));
                                } else {
                                    $("input[name='" + y.id_field + "']").val(y.def);
                                }
                            }

                        } else {
                            $('#' + y.id_field).prop('required', false);
                            $('#' + y.id_field).prop('disabled', true);
                            //cek jika input option
                            if (y.tabel_sap != null) {
                                $("select[name='" + y.id_field + "']").val('').trigger('change');
                            } else {
                                $("input[name='" + y.id_field + "']").val(y.def);
                            }
                        }
                    });
                    if ((v.msehi_order == 0) || (v.msehi_order == null)) {
                        $('#umrez').prop('required', false);
                        $('#umrez').prop('disabled', false);
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
                $('#old_material_number').prop('required', false);
                $('#old_material_number').prop('disabled', false);

                $('#sales_plant').prop('required', false);
                $('#sales_plant').prop('disabled', false);


            }
        });
    });
    //description
    $(document).on("keyup", "#description", function() {
        var id_item_name = $("#id_item_name").val();
        var description = $("#description").val();
        $.ajax({
            url: baseURL + 'material/transaksi/get/item',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_name: id_item_name
            },
            success: function(data) {
                console.log(data);
                $.each(data, function(i, v) {
                    if (description !== '') {
                        let detail = v.description + ' ' + description;
                        if (detail.length > 40) {
                            kiranaAlert("notOK", "Panjang Material Description maksimal 40 karakter.", "warning", "no");
                            detail = detail.substr(0, 40);
                            description_cek = (detail.replace(v.description+' ', ''));
							$("input[name='description']").val(description_cek);
                        }
                        $("input[name='detail']").val(detail);
                    } else {
                        $("input[name='detail']").val('');
                    }
                });

            }
        });
    });

    //sync sap
    $(document).on("click", "#rfc_button", function(e) {
        $.ajax({
            url: baseURL + "data/rfc/set/kode_material",
            type: 'POST',
            dataType: 'JSON',
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

    //set kode
    // $(document).on("change", "#id_item_group,#id_item_name", function(e){
    $(document).on("change", "#id_item_name", function(e) {
        var id_item_group = $("#id_item_group").val();
        var id_item_name = $("#id_item_name").val();
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
                // $.each(data.terpakai, function (x, y) {
                // terpakai 	+= y+",";
                // });
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
                    $("#code").val(data.nomor);
                    $("input[name='code']").val(data.nomor);
                }
                $('#old_material_number').prop('required', false);
                $('#old_material_number').prop('disabled', false);

            }
        });
    });

    //cek ada data
    $(document).on("change", ".cek_data", function(e) {
        var tabel = $(this).data("tabel");
        var field = $(this).data("field");
        var value = $(this).val();
        var field2 = $(this).data("field2");
        var value2 = $("#id_item_name").val();
        $.ajax({
            url: baseURL + 'material/transaksi/get/cek',
            type: 'POST',
            dataType: 'JSON',
            data: {
                tabel: tabel,
                field: field,
                value: value,
                field2: field2,
                value2: value2
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

    //cek order unit
    $(document).on("change", "#msehi_order", function(e) {
        var msehi_uom = $("#msehi_uom").val();
        var msehi_order = $(this).val();
        if ((msehi_uom == msehi_order) && (msehi_order != null) && (msehi_order != 0)) {
            // $("select[name='msehi_order']").val('');
            $("select[name='msehi_order']").val(0).trigger('change');
            swal('Warning', 'Pilihan UOM dan Order Unit tidak boleh sama.', 'warning');
        }
    });

    //status
    $(document).on("click", ".status", function() {
        var id_item_spec = $(this).data("edit");
        var btn_save = $(this).data("btn_save");
        $.ajax({
            url: baseURL + 'material/transaksi/get/spec',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_spec: id_item_spec
            },
            success: function(data) {
                $(".modal-title").html("Status SAP");
                $.each(data, function(i, v) {
                    $("#id_item_spec").val(v.id_item_spec);
                    $("#id_item_request").val(v.id_item_request);
                    $("input[name='code']").val(v.code);
                    $("input[name='description']").val(v.description);
                    //plant
                    var nil = "<table class='table table-bordered'>";
                    nil += "<thead>";
                    nil += "<tr>";
                    nil += "<th width='25'>Plant</th><th width='25'>Status SAP</th><th width='25'>Block Procurement</th><th width='25'>Flag Deletion</th>";
                    nil += "</tr>";
                    nil += "</thead>";
                    nil += "<tbody>";
                    $.each(v.arr_plant, function(x, y) {
                        nil += "<tr>";
                        nil += "<td>" + y.plant + "</td><td>" + y.label_sap + "</td><td align='center'>" + y.label_block + "</td><td align='center'>" + y.label_del + "</td>";
                        nil += "</tr>";
                    });
                    nil += "</tbody>";
                    $("#show_plant").html(nil);
                });
            },
            complete: function() {
                $('#status_modal').modal('show');
            }

        });
    });

    //history
    $(document).on("click", ".history", function() {
        var id_item_spec = $(this).data("edit");
        var btn_save = $(this).data("btn_save");
        $.ajax({
            url: baseURL + 'material/transaksi/get/spec',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_spec: id_item_spec
            },
            success: function(data) {
                $(".modal-title").html("History Change Description");
                $.each(data, function(i, v) {
                    $("#id_item_spec").val(v.id_item_spec);
                    $("#id_item_request").val(v.id_item_request);
                    $("input[name='code']").val(v.code);
                    $("input[name='description']").val(v.description);
                    //history
                    var nil = "<table class='table table-bordered'>";
                    nil += "<thead>";
                    nil += "<tr>";
                    nil += "<th width='25'>Old Material Description</th><th width='25'>New Material Description</th><th width='25'>Change User</th><th width='25'>Date</th>";
                    nil += "</tr>";
                    nil += "</thead>";
                    nil += "<tbody>";
                    $.each(v.arr_history, function(x, y) {
                        nil += "<tr>";
                        nil += "<td>" + y.description_old + "</td><td>" + y.description_new + "</td><td align='center'>" + y.nama_user + "</td><td align='center'>" + y.tanggal + "</td>";
                        nil += "</tr>";

                    });
                    nil += "</tbody>";
                    $("#show_plant").html(nil);
                });
            },
            complete: function() {
                $('#status_modal').modal('show');
            }

        });
    });

    //edit, copy dan change  
    $(document).on("click", ".edit", function() {
        resetForm_use($('.form-transaksi-spec'), 'edit');
        var id_item_spec = $(this).data("edit");
        var btn_save = $(this).data("btn_save");
        var btn_status = $(this).data("btn_status");
		var edit_detail = $(this).data("edit_detail");
        $.ajax({
            url: baseURL + 'material/transaksi/get/spec',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_spec: id_item_spec,
                btn_status: btn_status
            },
            success: function(data) {
                if (btn_status == 'copy') {
                    $(".modal-title").html("Copy Form Item Spec");
                } else if (btn_status == 'change') {
                    $(".modal-title").html("Change Description & UOM");
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
                                    $("#id_item_spec").val('');
                                    $("#code").val(data.nomor);
                                    $("input[name='code']").val(data.nomor);
                                }
                            }
                        });
                    } else {
                        $("#id_item_spec").val(v.id_item_spec);
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
                    $("input[name='description']").val(decodeHTMLEntities(v.description));
                    $("input[name='description_awal']").val(decodeHTMLEntities(v.description));
                    $("input[name='msehi_uom_awal']").val(decodeHTMLEntities(v.msehi_uom));
                    
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
                    $("input[name='detail']").val(decodeHTMLEntities(v.detail));
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
                    $('#msehi_uom').prop('required', true);
                    $('#msehi_uom').prop('disabled', false);
                }
				//lha untuk edit ulang
				if (edit_detail == 'edit_ulang') {
                    $('#id_item_group').prop('disabled', true);
                    $('#id_item_name').prop('disabled', true);
				}
				
				
                $('#add_modal').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_modal')
                });

            }

        });
    });

    // EXTEND TAB SALES
    $(document).on("click", ".sales", function() {
        var kode_material = $(this).data("kode");
        var id_item_spec = $(this).data("sales");
        
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Extend Tab Sales Kode Material "+kode_material+" ?",
            dangerMode: true,
            successCallback: function() {
                $.ajax({
                    url: baseURL + 'material/transaksi/save/sales',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id_item_spec: id_item_spec
                    },
                    success: function(data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);
                        } else {
                            kiranaAlert("notOK", data.msg, "warning", "no");
                        }
                    }
                });
            }
        });
    });
	
    // delete_request
    $(document).on("click", ".delete_request", function() {
        var kode_material = $(this).data("kode");
        var id_item_spec = $(this).data("edit");
        
        kiranaConfirm({
            title: "Konfirmasi",
            text: "Hapus Pengajuan Kode Material "+kode_material+" ?",
            dangerMode: true,
            successCallback: function() {
                $.ajax({
                    url: baseURL + 'material/transaksi/save/delete_request',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        id_item_spec: id_item_spec
                    },
                    success: function(data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);
                        } else {
                            kiranaAlert("notOK", data.msg, "warning", "no");
                        }
                    }
                });
            }
        });
    });

    // $(document).on("click", ".sales", function() {
    //     resetForm_use($('.form-transaksi-spec'), 'edit');
    //     var id_item_spec = $(this).data("sales");
        
    //     $.ajax({
    //         url: baseURL + 'material/transaksi/get/spec',
    //         type: 'POST',
    //         dataType: 'JSON',
    //         data: {
    //             id_item_spec: id_item_spec,
    //             btn_status: 'hidden'
    //         },
    //         success: function(data) {
    //             console.log('btn-save= '+btn_save);

                
    //             $(".modal-title").html("Form Extend Tab Sales");
                
    //             $.each(data, function(i, v) {
    //                 console.log(data);
                    
    //                 $("#id_item_spec").val(v.id_item_spec);
    //                 $("input[name='code']").val(v.code);
                    
    //                 $("#id_item_request").val(v.id_item_request);
    //                 $("select[name='id_item_group']").val(v.id_item_group).trigger("change.select2");
    //                 $("select[name='msehi_uom']").val(v.msehi_uom).trigger("change");
    //                 //gambar
    //                 if (v.list_gambar !== null) {
    //                     var list_gambar = v.list_gambar.slice(0, -1).split(",");
    //                     var array = [];
    //                     var det = "";
    //                     $.each(list_gambar, function(x, y) {
    //                         console.log(y);
    //                         det += "<img src='" + y + "' class='img-thumbnail' style='height:80px;'>";
    //                     });
    //                     $("#show_images").html(det);
    //                 }
    //                 // $("select[name='id_item_name']").val(v.id_item_name).trigger("change");
    //                 //load id_item_name
    //                 var output = '';
    //                 $.each(v.arr_item, function(x, y) {
    //                     var selected = (y.id_item_name == v.id_item_name ? 'selected' : '');
    //                     output += '<option value="' + y.id_item_name + '" ' + selected + '>' + y.description + '</option>';
    //                 });
    //                 $("select[name='id_item_name']").html(output).select2();

    //                 $("select[name='msehi_order']").val(v.msehi_order).trigger("change");

    //                 $("input[name='old_material_number']").val(v.old_material_number);
    //                 $("input[name='description']").val(decodeHTMLEntities(v.description));
    //                 $("input[name='description_awal']").val(decodeHTMLEntities(v.description));
                    
    //                 $("select[name='ekgrp']").val(v.ekgrp).trigger("change");
    //                 $("select[name='availability_check']").val(v.availability_check).trigger("change");
    //                 var plant = v.plant.split(",");
    //                 $("select[name='plant[]']").val(plant).trigger("change");
    //                 // load_plant(v.plant);
    //                 $("select[name='lgort']").val(v.lgort).trigger("change");
    //                 // //load lgort
    //                 // var output = '';
    //                 // $.each(v.arr_lgort, function (x, y) {

    //                 // var selected = (y.lgort == v.lgort ? 'selected' : '');
    //                 // output += '<option value="' + y.lgort + '" '+selected+'>[' + y.lgort + '] ' +y.lgobe+'</option>';
    //                 // });
    //                 // $("select[name='lgort']").html(output).select2();

    //                 $("select[name='mrp_group']").val(v.mrp_group).trigger("change");
    //                 $("input[name='service_level']").val(v.service_level);
    //                 $("select[name='mrp_type']").val(v.mrp_type).trigger("change");
    //                 $("select[name='disls']").val(v.disls).trigger("change");
    //                 $("select[name='dispo']").val(v.dispo).trigger("change");
    //                 $("select[name='period_indicator']").val(v.period_indicator).trigger("change");
    //                 // $("select[name='sales_plant']").val(v.sales_plant).trigger("change");
    //                 if (v.sales_plant == 'X') {
    //                     $("input[name='sales_plant']").attr('checked');
    //                     $("input[name='sales_plant']").bootstrapToggle('on');
    //                 } else {
    //                     $("input[name='sales_plant']").removeAttr('checked');
    //                     $("input[name='sales_plant']").bootstrapToggle('off');
    //                 }

    //                 $("select[name='gen_item_cat_group']").val(v.gen_item_cat_group).trigger("change");
    //                 // $("select[name='vtweg']").val(v.vtweg).trigger("change");
    //                 //load vtweg
    //                 if (v.vtweg != null) {
    //                     var output = '';
    //                     $.each(v.arr_vtweg, function(x, y) {
    //                         var selected = v.vtweg.split(',').some(vv => vv === y.vtweg) ? 'selected' : '';
    //                         output += '<option value="' + y.kd + '" ' + selected + '>[' + y.kd + '] ' + y.nm + '</option>';
    //                     });
    //                     $("select[name='vtweg[]']").html(output).select2();
    //                 }

    //                 $("select[name='material_pricing_group']").val(v.material_pricing_group).trigger("change");
    //                 $("select[name='spart']").val(v.spart).trigger("change");
    //                 $("select[name='material_statistic_group']").val(v.material_statistic_group).trigger("change");
    //                 $("input[name='net_weight']").val(v.net_weight);
    //                 $("select[name='acct_assignment_group']").val(v.acct_assignment_group).trigger("change");
    //                 $("input[name='gross_weight']").val(v.gross_weight);
    //                 $("select[name='prctr']").val(v.prctr).trigger("change");
    //                 $("select[name='taxm1']").val(v.taxm1).trigger("change");
    //                 if (v.xchpf == 'X') {
    //                     $("input[name='xchpf']").attr('checked');
    //                     $("input[name='xchpf']").bootstrapToggle('on');
    //                 } else {
    //                     $("input[name='xchpf']").removeAttr('checked');
    //                     $("input[name='xchpf']").bootstrapToggle('off');
    //                 }
    //                 $("input[name='detail']").val(decodeHTMLEntities(v.detail));
    //                 $("input[name='prmod']").val(v.prmod);
    //                 $("input[name='peran']").val(v.peran);
    //                 $("input[name='anzpr']").val(v.anzpr);
    //                 $("input[name='kzini']").val(v.kzini);
    //                 $("input[name='siggr']").val(v.siggr);
    //                 $("input[name='umrez']").val(v.umrez);


    //                 //matrix
    //                 $.each(v.arr_kolom, function(x, y) {
    //                     console.log(x);
    //                     //matrix jika matrix option
    //                     if (y.req == 'y') {
    //                         $('#' + y.id_field).prop('required', true);
    //                         $('#' + y.id_field).prop('disabled', false);
    //                     } else {
    //                         $('#' + y.id_field).prop('required', false);
    //                         $('#' + y.id_field).prop('disabled', true);
    //                     }
    //                 });
    //                 //set default by request sipi
    //                 $('#code').prop('required', true);
    //                 $('#code').prop('disabled', true);
    //                 $('#detail').prop('required', true);
    //                 $('#detail').prop('disabled', true);
    //                 $('#msehi_uom').prop('required', true);
    //                 $('#msehi_uom').prop('disabled', false);
    //                 $('#xchpf').prop('required', false);
    //                 $('#xchpf').prop('disabled', false);

    //                 $('#msehi_order').prop('required', false);
    //                 $('#msehi_order').prop('disabled', false);
    //                 if ((v.msehi_order == 0) || (v.msehi_order == null)) {
    //                     $('#umrez').prop('required', false);
    //                     $('#umrez').prop('disabled', false);
    //                 }
    //                 $('#old_material_number').prop('required', false);
    //                 $('#old_material_number').prop('disabled', false);

    //                 $('#sales_plant').prop('required', false);
    //                 $('#sales_plant').prop('disabled', false);


    //             });
    //         },
    //         complete: function() {
    //             $("#btn_change").hide();
    //             $('.form-control-hide').prop('disabled', true);
    //             $("#gambar").hide();
    //             $("#btn_save").hide();
                
    //             $('#add_modal').modal('show');
    //             $('.select2modal').select2({
    //                 dropdownParent: $('#add_modal')
    //             });

    //         }

    //     });
    // });

    //extend
    $(document).on("click", ".extend", function() {
        var id_item_spec = $(this).data("edit");
        $.ajax({
            url: baseURL + 'material/transaksi/get/spec',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_spec: id_item_spec
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
                    $("#id_item_spec").val(v.id_item_spec);
                    $("input[name='id_item_spec']").val(v.id_item_spec);
                    $("input[name='code']").val(v.code);
                    $("input[name='plant']").val(v.plant);
                    $("input[name='vtweg']").val(v.vtweg);
                    $("input[name='description']").val(v.description);
                    var plant = v.plant.split(",");
                    $("select[name='plant[]']").val(plant).trigger("change");
                    //load plant extend
                    var output = '';
                    $.each(v.arr_plant_extend, function(x, y) {
                        output += '<option value="' + y.plant + '">' + y.plant + '</option>';
                    });
                    $("select[name='plant_extend[]']").html(output).select2();

                    // //load vtweg
                    // var output = '';
                    // $.each(v.arr_vtweg, function (x, y) {
                    // var selected = v.vtweg.split(',').some(vv => vv === y.vtweg)  ? 'selected' : '';
                    // output += '<option value="' + y.kd + '" '+selected+'>['+ y.kd +'] '+ y.nm +'</option>';
                    // });
                    // $("select[name='vtweg[]']").html(output).select2();

                    // //load vtweg extend
                    // var output = '';
                    // $.each(v.arr_vtweg, function (x, y) {
                    // if((v.vtweg.split(',').some(vv => vv === y.vtweg))===false){
                    // output += '<option value="' + y.kd + '">['+ y.kd +'] '+ y.nm +'</option>';	
                    // }

                    // });
                    // $("select[name='vtweg_extend[]']").html(output).select2();
                });
            },
            complete: function() {
                $('#add_extend').modal('show');
                $('#plant_extend').prop('required', false);
                $('#plant_extend').prop('disabled', false);
                $('#vtweg_extend').prop('required', false);
                $('#vtweg_extend').prop('disabled', false);
                $("input[name='detail']").val('');
                $('.select2modal').select2({
                    dropdownParent: $('#add_extend')
                });
            }
        });
    });

    $(document).on("click", ".nonactive, .setactive, .delete", function(e) {
        $.ajax({
            url: baseURL + "material/transaksi/set/spec",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_spec: $(this).data($(this).attr("class")),
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
    //imp
    $(document).on("click", "button[name='action_btn_imp']", function(e) {
        var empty_form = validate('.form-transaksi-spec-imp');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-spec-imp")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'material/transaksi/save/excel_spec',
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
    //add
    $(document).on("click", "button[name='action_btn']", function(e) {
        var empty_form = validate('.form-transaksi-spec');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                let detail = $("input[name='detail']").val();
                if (detail.length > 40) {
                    kiranaAlert("notOK", "Panjang Material Description maksimal 40 karakter.", "error", "no");
                } else {
                    $("input[name='isproses']").val(1);
                    var formData = new FormData($(".form-transaksi-spec")[0]);
                    console.log();
                    $.ajax({
                        url: baseURL + 'material/transaksi/save/spec',
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
                }
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
    //save change
    $(document).on("click", "button[name='action_btn_change']", function(e) {
        var empty_form = validate('.form-transaksi-spec');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-spec")[0]);
                //push sap
                $.ajax({
                    url: baseURL + "data/rfc/set/change_kode_material",
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);
                        } else {
                            kiranaAlert("notOK", data.msg, "warning", "no");
                        }
                    }
                });
                // $.ajax({
                // url: baseURL+'material/transaksi/save/change',
                // type: 'POST',
                // dataType: 'JSON',
                // data: formData,
                // contentType: false,
                // cache: false,
                // processData: false,
                // success: function(data){
                // if (data.sts == 'OK') {
                // swal('Success', data.msg, 'success').then(function () {
                // location.reload();
                // });
                // } else {
                // $("input[name='isproses']").val(0);
                // swal('Error', data.msg, 'error');
                // }
                // }
                // });
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
    //extend
    $(document).on("click", "button[name='action_btn_extend']", function(e) {
        var empty_form = validate('.form-transaksi-extend');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-extend")[0]);
                kiranaConfirm({
                    title: "Konfirmasi",
                    text: "Data akan dikirim ke SAP, apakah proses akan dilanjutkan?",
                    dangerMode: true,
                    successCallback: function() {
                        $.ajax({
                            url: baseURL + 'material/transaksi/save/extend',
                            type: 'POST',
                            dataType: 'JSON',
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(data) {
                                if (data.sts == 'OK') {
                                    //push sap
                                    $.ajax({
                                        url: baseURL + "data/rfc/set/extend_kode_material",
                                        type: 'POST',
                                        dataType: 'JSON',
                                        data: formData,
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function(data) {
                                            if (data.sts == 'OK') {
                                                kiranaAlert(data.sts, data.msg);
                                            } else {
                                                kiranaAlert("notOK", data.msg, "warning", "no");
                                            }
                                        }
                                    });
                                } else {
                                    $("input[name='isproses']").val(0);
                                    swal('Error', data.msg, 'error');
                                }
                            }
                        });
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
		$('#id_item_spec').val("");
        resetForm_use($('.form-transaksi-spec'));
        $('#add_modal').modal('show');
        $("#btn_change").hide();
        $('.select2modal').select2({
            dropdownParent: $('#add_modal')
        });

    });
    //open modal for imp    
    $(document).on("click", "#imp_button", function(e) {
        resetForm_use($('.form-transaksi-spec-imp'));
        $('#imp_modal').modal('show');
    });

    //cek all
    $(document).on("change", ".isSelectAllVtwegExtend,.isSelectAllPlantExtend,.isSelectAllPlant,.isSelectAllSalesPlant,.isSelectAllVtweg", function(e) {
        if ($(".isSelectAllVtweg").is(':checked')) {
            $('#vtweg').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#vtweg').select2('destroy').find('option').prop('selected', false).end().select2();
        }
        if ($(".isSelectAllPlant").is(':checked')) {
            $('#plant').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#plant').select2('destroy').find('option').prop('selected', false).end().select2();
        }
        if ($(".isSelectAllPlantExtend").is(':checked')) {
            $('#plant_extend').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#plant_extend').select2('destroy').find('option').prop('selected', false).end().select2();
        }
        if ($(".isSelectAllVtwegExtend").is(':checked')) {
            $('#vtweg_extend').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#vtweg_extend').select2('destroy').find('option').prop('selected', false).end().select2();
        }
        if ($(".isSelectAllSalesPlant").is(':checked')) {
            $('#sales_plant').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#sales_plant').select2('destroy').find('option').prop('selected', false).end().select2();
        }

    });

});

function resetForm_use($form, $act) {
    $('#myModalLabel').html("Form Item Spec");
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
    validateReset('.form-transaksi-spec');
}

function resetForm_extend($form) {
    $('#plant_extend').prop('disabled', false);
}

function datatables_ssp() {
    var id_item_group = $("#id_item_group_filter").val();
    var id_item_name = $("#id_item_name_filter").val();
    var status = $("#status_filter").val();
    var ho = $("#ho").val();
    var filter_request_status = $("#filter_request_status").val();
    var filter_classification = $("#filter_classification").val();

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
            url: baseURL + 'material/transaksi/get/spec/bom',
            type: 'POST',
            data: function(data) {
                data.id_item_group = id_item_group;
                data.id_item_name = id_item_name;
                data.status = status;
                data.filter_request_status = filter_request_status;
                data.filter_classification = filter_classification;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [{
                "data": "id_item_spec",
                "name": "id_item_spec",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.id_item_spec;
                },
                "visible": false
            },
            {
                "data": "group_mtart",
                "name": "group_mtart",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.group_mtart;
                }
            },
            {
                "data": "group_description",
                "name": "group_description",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.id_item_group + '-' + row.group_description;
                }
            },
            {
                "data": "name_description",
                "name": "name_description",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.name_code + '-' + row.name_description;
                }
            },
            {
                "data": "code",
                "name": "code",
                "width": "15%",
                "render": function(data, type, row) {
                    if(row.sales_plant == 'X'){
                        return row.code+'<br>'+row.label_classification
                        +'<br><span class="label label-success">Tab Sales <i class="fa fa-check"></i></span>';
                    }else{
                        return row.code+'<br>'+row.label_classification;
                    }
                }
            },
            {
                "data": "description_detail",
                "name": "description_detail",
                "width": "30%",
                "render": function(data, type, row) {
                    // return row.name_description+' '+row.description;
                    return row.description_detail;
                }
            },
            {
                "data": "req",
                "name": "req",
                "width": "5%",
                "render": function(data, type, row) {
                    if (row.req == 'n') {
                        return '<label class="label label-success">Completed</label>';
                    } else if (row.req == 'd') {
                        return '<label class="label label-danger">Deleted</label>';
                    }else{
                        return '<label class="label label-warning">Requested</label>';
                    }
                }
            },
            {
                "data": "id_item_spec",
                "name": "id_item_spec",
                "width": "5%",
                "render": function(data, type, row) {
                    if (ho == 'y') {
                        if (row.na == 'n') {
                            output = "			<div class='input-group-btn'>";
                            output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                            output += "				<ul class='dropdown-menu pull-right'>";
                            if (row.req == 'd') {
                                output += "				<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_item_spec + "' data-edit_detail='edit_ulang'><i class='fa fa-pencil-square-o'></i> Edit Ulang</a></li>";
                            }
                            if (row.req == 'y') {
                                output += "				<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_item_spec + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
                                output += "				<li><a href='javascript:void(0)' class='delete_request' data-edit='" + row.id_item_spec + "' data-kode='" + row.code + "'><i class='fa fa-minus-circle'></i> Delete</a></li>";
                            }
                            if (row.req == 'n') { 
                                output += "				<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_item_spec + "' data-btn_status='copy'><i class='fa fa-copy'></i> Copy Data</a></li>";
                                output += "				<li><a href='javascript:void(0)' class='extend' data-edit='" + row.id_item_spec + "'><i class='fa fa-arrows'></i> Extend Data</a></li>";
                                output += "				<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_item_spec + "' data-btn_save='hidden'><i class='fa fa-search'></i> Detail</a></li>";
                                output += "				<li><a href='javascript:void(0)' class='status' data-edit='" + row.id_item_spec + "'><i class='fa fa-retweet'></i> Status SAP</a></li>";
                                output += "				<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_item_spec + "' data-btn_status='change'><i class='fa fa-pencil-square'></i> Change Description & UOM</a></li>";
                                output += "				<li><a href='javascript:void(0)' class='history' data-edit='" + row.id_item_spec + "'><i class='fa fa-h-square'></i> History Change</a></li>";
                                // output += "				<li><a href='javascript:void(0)' class='sales' data-sales='" + row.id_item_spec + "'><i class='fa fa-arrows'></i> Extend Tab Sales</a></li>";
                                if(row.classification == 'A' || row.classification == 'E'){
                                    output += "				<li><a href='javascript:void(0)' class='sales' data-sales='" + row.id_item_spec + "' data-kode='" + row.code + "'><i class='fa fa-arrows'></i> Add Sales Data</a></li>";
                                }
                            }
                            output += "				</ul>";
                            output += "	        </div>";
                        }
                    } else {
                        if (row.na == 'n') {
                            output = "			<div class='input-group-btn'>";
                            output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                            output += "				<ul class='dropdown-menu pull-right'>";
                            // output += "					<li><a href='javascript:void(0)' class='edit' data-edit='"+row.id_item_spec+"' data-btn_save='hidden'><i class='fa fa-search'></i> Detail</a></li>";		
                            output += "					<li><a href='javascript:void(0)' class='status' data-edit='" + row.id_item_spec + "'><i class='fa fa-retweet'></i> Status SAP</a></li>";
                            output += "				</ul>";
                            output += "	        </div>";
                        }
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

function decodeHTMLEntities(text) {
  return $("<textarea/>")
    .html(text)
    .text();
}
