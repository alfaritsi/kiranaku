$(document).ready(function () {
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
    };
    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };

    $(document).on('shown.bs.dropdown', '.dataTable .input-group-btn:has(>.dropdown-menu)', function () {
        var $menu = $("ul", this);
        offset = $menu.offset();
        position = $menu.position();
        $('body').append($menu);
        $menu.show();
        $menu.css('position', 'absolute');
        $menu.css('top', (offset.top) + 'px');
        $menu.css('left', (offset.left) + 'px');
        $(this).data("myDropdownMenu", $menu);
    });

    $(document).on('hide.bs.dropdown', '.dataTable .input-group-btn:has(.dropdown-toggle)', function () {
        $(this).append($(this).data("myDropdownMenu"));
        $(this).data("myDropdownMenu").removeAttr('style');
    });

    $(document).on('change', '#id_kategori', function (e) {
        get_option_jenis($(this).val(), 'jenis_aset');
    });

    function get_option_jenis(id_kategori, input_name = 'jenis_aset') {
        $("select[name='" + input_name + "']")
            .html('<option></option>')
            .trigger("change");
        $.ajax({
            url: baseURL + 'asset/transaksi/get/jenis/it',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_kategori: id_kategori
            },
            success: function (data) {
                if (data) {
                    var output = '<option></option>';
                    $.each(data, function (i, v) {
                        output += '<option value="' + v.id_jenis + '">' + v.nama + '</option>';
                    });
                    $("select[name='" + input_name + "']")
                        .html(output)
                        .trigger("change");
                }
            },
            complete: function () {
                var id_jenis = $("select[name='" + input_name + "']").attr('data-id_jenis');
                if (id_jenis) {

                    $("select[name='" + input_name + "']")
                        .val(id_jenis)
                        .trigger("change");
                }
            }
        });
    }

    $("#sspsTable").dataTable({
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input')
                .off('.DT')
                .on('input.DT', function () {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "loading..."
        },
        processing: true,
        serverSide: true,
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends-order", this).data("page") ? $(".my-datatable-extends-order", this).data("page") : 10,
        paging: $(".my-datatable-extends-order", this).data("paging") ? $(".my-datatable-extends-order", this).data("paging") : true,
        ajax: {
            url: baseURL + 'asset/master/get_ssp_periode/it',
            type: 'POST',
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "tbl2.nama",
                "name": "nama_jenis",
                "width": "10%",
                "render": function (data, type, row) {
                    return row.nama_jenis;
                }
            },
            {
                "data": "tbl1.nama",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.nama;
                }
            },
            {
                "data": "tbl1.periode",
                "width": "5%",
                "className": "text-right text-capitalize",
                "render": function (data, type, row) {
                    return row.periode_jumlah + " "+ row.periode;
                }
            },
            {
                "data": "tbl1.lama",
                "width": "5%",
                "className": "text-right text-capitalize",
                "render": function (data, type, row) {
                    return row.lama_jumlah + " "+ row.lama;
                }
            },
            {
                "data": "tbl1.delay_hari",
                "width": "5%",
                "className": "text-right",
                "render": function (data, type, row) {
                    return row.delay_hari + " Hari";
                }
            },
            {
                "data": "tbl1.kategori",
                "width": "10%",
                "className": "text-right",
                "render": function (data, type, row) {
                    return row.kategori;
                }
            },
            {
                "data": "tbl1.id_periode" + "tbl1.id_jenis",
                "width": "10%",
                // "orderable": false,
                "searchable": false,
                "render": function (data, type, row) {
                    var id = row.id_periode;
                    var id_jenis = row.id_jenis;
                    return '<a class="label label-primary detail" id="detail" data-periode=' + id + ' data-jenis=' + id_jenis + ' style="cursor:pointer;">Show Detail</a>';
                }
            },
            {
                "data": "tbl1.na",
                "width": "10%",
                "render": function (data, type, row) {
                    if (row.na == 'n') {
                        return "<span class='label label-success'>ACTIVE</span>";
                    } else {
                        return "<span class='label label-danger'>NOT ACTIVE</span>";
                    }
                }
            },
            {
                width: '5%',
                "data": "tbl1.id_periode" + "tbl1.na",
                "className": "center",
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row) {
                    var id = row.id_periode;
                    if (row.na == 'n') {
                        return '<div class="input-group-btn">' +
                            '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action' +
                            '<span class="fa fa-caret-down"></span>' +
                            '</button>' +
                            '<ul class="dropdown-menu pull-right">' +
                            '<li><a href="javascript:void(0)" class="edit_periode" data-periode=' + id + '><i class="fa fa-pencil-square-o"></i> Edit</a></li>' +
                            '<li><a href="javascript:void(0)" class="non_active" data-tab="periode" data-non_active=' + id + '><i class="fa fa-times"></i> Non Aktif</a></li>' +
                            '<li><a href="javascript:void(0)" class="delete" data-tab="periode" data-delete=' + id + '><i class="fa fa-trash-o"></i> Hapus</a></li>' +
                            '</ul>' +
                            '</div>';
                    } else {
                        return '<div class="input-group-btn">' +
                            '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action' +
                            '<span class="fa fa-caret-down"></span>' +
                            '</button>' +
                            '<ul class="dropdown-menu pull-right">' +
                            '<li><a href="javascript:void(0)" class="set_active" data-tab="periode" data-set_active=' + id + '><i class="fa fa-check"></i> Set Aktif</a></li>' +
                            '</ul>' +
                            '</div>';
                    }
                }
            },
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $('td:eq(0)', row).html();
        }
    });

    $("#add_periode").on("click", function () {
        $("#id_kategori")
            .val(null)
            .trigger("change");
        $('#auto_gen').attr('checked',false);
        $('#add_periode_modal').modal('show');
    });

    $(document).on("click", ".edit_periode", function () {
        var id_periode = $(this).data("periode");
        $.ajax({
            url: baseURL + 'asset/master/get_periode',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_periode: id_periode
            },
            success: function (data) {
                // console.log(data);
                $.each(data, function (i, v) {
                    $("#id_kategori").val(v.id_kategori).trigger('change');
                    $("#jenis_aset").attr("data-id_jenis", v.id_jenis);
                    $("#kode").val(v.kode);
                    $("#periode").val(v.nama);
                    $("#ket_periode").val(v.keterangan);
                    $("#sequence").val(v.squence);
                    $("#jam").val(v.jam);
                    $("#lama").val(v.lama).trigger('change');
                    $("#lama_jumlah").val(v.lama_jumlah);
                    $("#periode2").val(v.periode).trigger('change');
                    $("#periode_jumlah").val(v.periode_jumlah);
                    $("#delay_hari").val(v.delay_hari);
                    $("#service").val(v.id_service).trigger('change');
                    $("input[name='id_periode']").val(v.id_periode);
                    if (v.auto_gen == 'y')
                        $("#auto_gen").attr('checked', true);
                    else
                        $("#auto_gen").attr('checked', false);
                });
            },
            complete: function () {
                $('#add_periode_modal').modal('show');
            }
        });

    });

    $(document).on('hide.bs.modal', '#add_periode_modal', function () {
        //clear form
        $('.form-master-periode')[0].reset();
        $("#jenis_aset").val('').trigger('change');

    });

    $(".form-master-periode").on("submit", function (e) {
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            var formData = new FormData($(this)[0]);
			formData.append('pengguna', 'it');

            // console.log(formData);
            $.ajax({
                url: baseURL + 'asset/master/save_periode',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.sts == 'OK') {
                        kiranaAlert(data.sts, data.msg);
                    } else {
                        kiranaAlert(data.sts, data.msg, "error", "no");
                        $("input[name='isproses']").val(0);
                    }
                }
            });
        } else {
            alert("Silahkan tunggu proses selesai.");
        }
        e.preventDefault();
        return false;

    });

    $(document).on("click", ".set_active, .non_active, .delete", function (e) {
        var tabs = $(this).data("tab");
        if (tabs == 'periode') {
            $.ajax({
                url: baseURL + "asset/master/set/periode",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id_periode: $(this).data($(this).attr("class")),
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
        } else {
            alert("something went wrong");
        }

    });

// ==========================================================================

    $(document).on("click", ".detail", function () {
        var id_periode = $(this).data("periode");
        var id_jenis = $(this).data("jenis");
        // alert(id_periode+" "+id_jenis);

        $("input[name='fd_id_jenis']").val(id_jenis);
        $("input[name='fd_id_periode']").val(id_periode);

        $.ajax({
            url: baseURL + "asset/master/get_periode_detail_by_jenis",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_jenis: id_jenis,
                id_periode: id_periode,
                pengguna : 'it'
            },
            success: function (data) {
                // console.log(data);
                $('#periode_detail').DataTable().destroy();
                var t = $('#periode_detail').DataTable({
                    order: [[0, 'asc']],
                    ordering: true,
                    scrollCollapse: true,
                    scrollY: false,
                    scrollX: true,
                    bautoWidth: false,
                    "paging": false,
                    columnDefs: [
                        // { "visible": false, "targets": 0 },
                        // { "visible": false, "targets": 1 },
                        {"className": "text-center", "targets": 0},
                        {"className": "text-center", "targets": 1},
                        {"className": "text-center", "targets": 2},
                        {"className": "text-center", "targets": 3},
                        // { "className": "text-center", "targets": 4 },
                        // { "className": "text-center", "targets": 5 },
                    ],
                });
                t.clear().draw();

                var total = 0;
                $.each(data, function (i, v) {
                    let input_disable = '';
                    let checkbox = "<input type='checkbox' class='checkbox' name='pilih" + i + "' id='pilih" + i + "'>";
                    let id_periode_detail = "<input type='hidden' class='form-control col-sm-12' value='' name='id_periode_detail" + i + "' id='id_periode_detail" + i + "'>"
                    if (v.id_periode_detail != null) {
                        checkbox = "<input type='checkbox' class='checkbox' name='pilih" + i + "' id='pilih" + i + "' checked>";
                        id_periode_detail = "<input type='hidden' class='form-control col-sm-12' value='" + v.id_periode_detail + "' name='id_periode_detail" + i + "' id='id_periode_detail" + i + "'>"
                        input_disable = '';
                    }

                    total = i;
                    let optionss = "<select id='kegiatan" + i + "' name='kegiatan" + i + "' class='kegiatan form-control select2modal col-sm-12' " + input_disable + ">";

                    if (v.id_kegiatan != null) {
                        var id_kegiatan = v.id_kegiatan;
                    } else {
                        var id_kegiatan = null;
                    }

                    if (v.list_kegiatan) {
                        $.each(v.list_kegiatan, function (id, val) {
                            // console.log(val);
                            var pilihan = val.split('-');
                            var id = pilihan.shift();
                            var nama = pilihan.pop();
                            if (id_kegiatan == id) {
                                optionss += "<option value='" + id + "' selected>" + nama + "</option>";
                            } else {
                                optionss += "<option value='" + id + "'>" + nama + "</option>";
                            }
                        });
                    }
                    optionss += "</select>";


                    let keterangan = "";
                    if (v.keterangan != null) {
                        keterangan = v.keterangan;
                    }



                    let rows = t.row.add([
                        id_periode_detail + "<input type='hidden' class='form-control col-sm-12' value='" + v.id_jenis_details + "' name='id_jenis_detail" + i + "' id='id_jenis_detail" + i + "'><input type='text' class='form-control col-sm-12' value='" + v.jenis_detail + "' name='jenis_detail" + i + "' id='jenis_detail" + i + "' readonly>",
                        optionss,
                        "<input type='text' class='keterangan form-control col-sm-12' value='" + keterangan + "' name='keterangan" + i + "' id='keterangan" + i + "' " + input_disable + ">",
                        checkbox,
                    ]).draw(false).node();

                    $('.select2modal').select2();
                });

                $("input[name='total_row']").val(total + 1);
                $('#detail_modal').modal('show');
            }
        });
    });

    $('#detail_modal').on('shown.bs.modal', function(e) {
        adjustDatatableWidth();
    });

    $(document).on("change", ".selectALL", function (e) {
        if ($(".selectALL").is(':checked')) {
            // Check
            $(".checkbox").attr("checked", true);
            // $(".kegiatan").attr("readonly", '');
            // $(".keterangan").attr("readonly", '');
        } else {
            // Uncheck
            $(".checkbox").attr("checked", false);
            // $(".kegiatan").attr("readonly", 'readonly');
            // $(".keterangan").attr("readonly", 'readonly');
        }
    });

    // $(document).on("change", ".checkbox", function(e){
    //     var nama = this.name;
    //     var indeks = nama.slice(5);
    //     // alert(this + indeks);
    //     if($("#pilih"+indeks).is(':checked')) {
    //     	// Check
    // 		$("#keterangan"+indeks).attr('readonly', '');
    // 		$("#kegiatan"+indeks).attr('readonly', '');
    //     }else{
    // 		// Uncheck
    // 		$("#keterangan"+indeks).attr('readonly', 'readonly');
    // 		$("#kegiatan"+indeks).attr('readonly', 'readonly');
    //     }
    // });

    $(".form-periode-detail").on("submit", function (e) {
        var total_row = $("input[name='total_row']").val();
        for (var i = 0; i < total_row; i++) {
            // console.log($("input[name='checkbox"+i+"']").val()+"--");
            if ($("input[name='pilih" + i + "']").is(':checked') && $("input[name='keterangan" + i + "']").val() == "") {
                kiranaAlert("notOK", "Mohon Lengkapi Keterangan pada baris yang telah anda Pilih", "warning", "no");
                e.preventDefault();
                return false;
            } else if ($("input[name='pilih" + i + "']").is(':checked') && $("input[name='kegiatan" + i + "']").val() == "") {
                kiranaAlert("notOK", "Mohon Pilih Kegiatan pada baris yang telah anda Pilih", "warning", "no");
                e.preventDefault();
                return false;
            }
        }

        // var datas = $('.datatable-periode').DataTable().$('input,select').serializeArray();
        // var datas = $('.datatable-periode').DataTable().rows().data();
        // console.log(datas);

        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            var formData = new FormData($(this)[0]);
			formData.append('pengguna', 'it');

            $.ajax({
                url: baseURL + 'asset/master/save_periode_detail',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.sts == 'OK') {
                        kiranaAlert(data.sts, data.msg);
                    } else {
                        kiranaAlert(data.sts, data.msg, "error", "no");
                        $("input[name='isproses']").val(0);
                    }
                }
            });
        } else {
            alert("Silahkan tunggu proses selesai.");
        }
        e.preventDefault();
        return false;

    });

});



