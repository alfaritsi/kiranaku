/*
@application	: Travel
@author 		: Airiza Yuddha (7849)
@contributor	: 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>			   
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function () {

    if ($("input[name='id_pic_book']").val() == "") {
        var mode = 'view';
        var mode2 = 'subarea';

        document.getElementById('jenis_input2').selectedIndex = 0;
        create_select($('#jenis_input2').val(), mode); //this calls it on load
        $('#jenis_input2').change(create_select);

        // set val subarea
        document.getElementById('personal_area').selectedIndex = 0;
        var valtriger = $('#personal_area').val();
        create_select(valtriger, mode2);
        $("#personal_area").change(create_select);
    }

    //show data
    datatables_ssp();

    //submit form
    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate('.form-master-pic_book', true);
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-master-pic_book")[0]);
                $.ajax({
                    url: baseURL + 'travel/master/save/pic_book',
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
    //edit form
    $(document).on("click", ".edit", function (e) {
        var data_sync = $(this).data("edit");
        mode = 'edit';
        var jenis_user = "";
        var user = "";
        var rolex = "";
        var jenis_app = "";
        var app = "";
        var id_app = "";

        $.ajax({
            url: baseURL + 'travel/master/get/pic_book_detail',
            type: 'POST',
            dataType: 'JSON',
            data: {
                // id_pic_book : id_pic_book,
                dataedit: data_sync
            },
            success: function (data) {
                $(".title-form").html("<strong>Edit Pic Booking</strong>");
                user = data.nik;
                personal_area = data.personal_area;
                company_code = data.company_code;
                personal_subarea = data.personal_subarea;
                jenis_level = data.jns_user;
                level = data.value_user;
                id_pic_book = data.nik + '|' + data.company_code + '|' + data.personal_area + '|' + data.jns_user;

                if ($("#user").hasClass("select2") == true) {
                    $("#user").select2('destroy');
                    $("#user").removeClass('select2');
                }
                $('#user').html("");
                // ======================================================user
                var user = (data.nik + '').split(",");
                var nama_user = (data.nama_karyawan + '').split(",");
                var array = [];
                $.each(nama_user, function (x, y) {
                    $("#user").html("<option value=" + user[x] + ">" + y + "</option>");
                });

                // ======================================================app
                var role = data.level;
                $('#user_app').html("");
                if (data.jns_user == 'nik') {
                    var persa = ($('#personal_area').val() != undefined || $('#personal_area').val() != ""
                        || $('#personal_area').val() != '0')
                        ? $('#personal_area').val() : null;
                    //auto complete nik
                    $("#user_app").select2({
                        allowClear: true,
                        placeholder: {
                            id: "",
                            placeholder: "Leave blank to ..."
                        },
                        ajax: {
                            url: baseURL + 'travel/master/get/nik',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    persa: persa

                                };
                            },
                            processResults: function (data, page) {
                                return {
                                    results: data.items
                                };
                            },
                            cache: false
                        },
                        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                        minimumInputLength: 3,
                        templateResult: function (repo) {
                            if (repo.loading) return repo.text;
                            var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
                            return markup;
                        },
                        templateSelection: function (repo) {
                            if (repo.posst) $("input[name='caption']").val(repo.posst);
                            if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
                            else return repo.text;
                        }
                    });
                    var peserta = (data.value_user + '').split('|');
                    var nama_peserta = (data.level_name + '').split("|");
                    // remove null array value
                    nama_peserta = jQuery.grep(nama_peserta, function (n, i) {
                        return (n !== "" && n != null);
                    });
                    var array = [];
                    $.each(nama_peserta, function (x, y) {
                        var control = $('#user_app').empty().data('select2');
                        var adapter = control.dataAdapter;

                        array.push({ "id": peserta[x], "text": y });

                        adapter.addOptions(adapter.convertToOptions(array));
                        $('#user_app').trigger('change');
                    });
                    $('#user_app').val(peserta).trigger('change');
                } else {
                    var form = "";
                    var form = ""; $('#user_app').html("");
                    $.each(data.opt_jabatan, function (i, v) {
                        form += "<option value='" + v.id_jabatan + "'>" + v.nama + "</option>"
                    });

                    $("#user_app").select2();
                    $('#user_app').html(form);
                    var user_app = level.split('|');
                    $('#user_app').val(user_app).trigger('change');
                }

                // =====================================================subarea
                var form = "";
                var form = ""; $('#personal_subarea').html("");
                $.each(data.opt_subarea, function (i, v) {
                    form += "<option value='" + v.company_code + "'>" + v.personal_subarea_text + "</option>"
                });

                $("#personal_subarea").select2();
                $('#personal_subarea').html(form);

            }, complete: function (data) {
                $("input[name='id_pic_book']").val(id_pic_book);
                $("select[name='personal_area']").val(personal_area + '|' + company_code).trigger('change.select2');
                $("select[name='personal_subarea']").val(personal_subarea).trigger('change.select2');
                $("select[name='jenis_input2']").val(jenis_level).trigger('change.select2');

                // destroy multiple
                if (jenis_level == "nik") {
                    var persa = ($('#personal_area').val() != undefined || $('#personal_area').val() != ""
                        || $('#personal_area').val() != '0')
                        ? $('#personal_area').val() : null;
                    $("#user_app").select2({
                        allowClear: true,
                        placeholder: {
                            id: "",
                            placeholder: "Leave blank to ..."
                        },
                        ajax: {
                            url: baseURL + 'travel/master/get/nik',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    persa: persa

                                };
                            },
                            processResults: function (data, page) {
                                return {
                                    results: data.items
                                };
                            },
                            cache: false
                        },
                        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                        minimumInputLength: 3,
                        templateResult: function (repo) {
                            if (repo.loading) return repo.text;
                            var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
                            return markup;
                        },
                        templateSelection: function (repo) {
                            if (repo.posst) $("input[name='caption']").val(repo.posst);
                            if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
                            else return repo.text;
                        }
                    });
                } else {
                    $("#user_app").select2();
                }
                $("#btn-new").show();
            }
        });
    });

    // ============================================================================

    //reload / create new input
    $("#btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
    });

    // change jenis input approver
    $(document).on("change", "#jenis_input2", function (e) {
        var jenis_input = $(this).val();
        var role = ($('#role').val() != undefined || $('#role').val() != "") ? $('#role').val() : "x";
        var persa = ($('#personal_area').val() != undefined || $('#personal_area').val() != ""
            || $('#personal_area').val() != '0')
            ? $('#personal_area').val() : null;
        $("#user_app").select2('destroy');
        $('#user_app').html("");
        if (jenis_input == 'nik') {
            //auto complete nik
            $("#user_app").select2({
                allowClear: true,
                placeholder: {
                    id: "",
                    placeholder: "Leave blank to ..."
                },
                ajax: {
                    url: baseURL + 'travel/master/get/nik',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            persa: persa
                        };
                    },
                    processResults: function (data, page) {
                        return {
                            results: data.items
                        };
                    },
                    cache: false
                },
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 3,
                templateResult: function (repo) {
                    if (repo.loading) return repo.text;
                    var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
                    return markup;
                },
                templateSelection: function (repo) {
                    if (repo.posst) $("input[name='caption']").val(repo.posst);
                    if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
                    else return repo.text;
                }
            });
        } else {
            $.ajax({
                url: baseURL + 'travel/master/get/jabatan',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    // id_role : id_role
                },
                success: function (data) {
                    var form = ""; $('#user_app').html("");
                    $.each(data, function (i, v) {
                        form += "<option value='" + v.id_jabatan + "'>" + v.nama + "</option>"
                    });
                    $("#user_app").select2();
                    $('#user_app').html(form);
                }
            });
        }
    });

    // change jenis input approver
    $(document).on("change", "#personal_area", function (e) {
        var data_area = ($(this).val()).split('|');
        var personal_area = data_area[0];

        $.ajax({
            url: baseURL + 'travel/master/get/subarea',
            type: 'POST',
            dataType: 'JSON',
            data: {
                personal_area: personal_area
            },
            success: function (data) {
                var form = "";
                $('#personal_subarea').html("");
                $("#personal_subarea").select2('destroy');
                $.each(data, function (i, v) {
                    form += "<option value='" + v.company_code + "'>" + v.personal_subarea_text + "</option>"
                });

                $("#personal_subarea").select2();
                $('#personal_subarea').html(form);
            },
            complete: function (data) {
                $("#personal_subarea").select2();

                // create nik option
                var jenis_input = $("#jenis_input2").val();
                var persa = ($('#personal_area').val() != undefined || $('#personal_area').val() != ""
                    || $('#personal_area').val() != '0')
                    ? $('#personal_area').val() : null;
                if (jenis_input == 'nik') {
                    $("#user_app").select2('destroy');
                    $('#user_app').html("");
                    $("#user_app").select2({
                        allowClear: true,
                        placeholder: {
                            id: "",
                            placeholder: "Leave blank to ..."
                        },
                        ajax: {
                            url: baseURL + 'travel/master/get/nik',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    persa: persa

                                };
                            },
                            processResults: function (data, page) {
                                return {
                                    results: data.items
                                };
                            },
                            cache: false
                        },
                        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                        minimumInputLength: 3,
                        templateResult: function (repo) {
                            if (repo.loading) return repo.text;
                            var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
                            return markup;
                        },
                        templateSelection: function (repo) {
                            if (repo.posst) $("input[name='caption']").val(repo.posst);
                            if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
                            else return repo.text;
                        }
                    });
                }
            }
        });
    });

    // set active , non active and delete 
    $(document).on("click", ".nonactive, .setactive, .delete", function (e) {
        var confirm_nonactive = "Apakah anda yakin ingin mengubah sistem aktif data ?";
        var confirm_delete = "Apakah anda yakin ingin menghapus data ?";
        var id = $(this).data($(this).attr("class"));
        var type = $(this).attr("class");
        var text = '';
        if (type == 'delete') {
            text = confirm_delete;
        } else if (type == 'nonactive' || type == 'setactive') {
            text = confirm_nonactive;
        }
        kiranaConfirm(
            {
                title: "Kiranaku",
                text: text,
                dangerMode: true,
                useButton: null,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: "OK",
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'travel/master/set/pic_book',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id,
                            type: type
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg);
                            } else {
                                kiranaAlert(data.sts, data.msg, "error", "no");
                            }
                        }
                    });
                },
            }
        );
    });

});

function create_select(data, mode) {
    if (mode == 'view') {
        var jenis_input = $('#jenis_input2').val();
        var persa = ($('#personal_area').val() != undefined || $('#personal_area').val() != ""
            || $('#personal_area').val() != '0')
            ? $('#personal_area').val() : null;
        $("#user_app").select2('destroy');
        $('#user_app').html("");
        if (jenis_input == 'nik') {
            //auto complete nik
            $("#user_app").select2({
                allowClear: true,
                placeholder: {
                    id: "",
                    placeholder: "Leave blank to ..."
                },
                ajax: {
                    url: baseURL + 'travel/master/get/nik',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            persa: persa
                        };
                    },
                    processResults: function (data, page) {
                        return {
                            results: data.items
                        };
                    },
                    cache: false
                },
                escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
                minimumInputLength: 3,
                templateResult: function (repo) {
                    if (repo.loading) return repo.text;
                    var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
                    return markup;
                },
                templateSelection: function (repo) {
                    if (repo.posst) $("input[name='caption']").val(repo.posst);
                    if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
                    else return repo.text;
                }
            });
        } else {
            $.ajax({
                url: baseURL + 'travel/master/get/jabatan',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    // id_role : id_role
                },
                success: function (data) {
                    var form = ""; $('#user_app').html("");
                    $.each(data, function (i, v) {
                        form += "<option value='" + v.id_jabatan + "'>" + v.nama + "</option>"
                    });
                    $("#user_app").select2();
                    $('#user_app').html(form);
                }
            });
        }

        //auto complete nik for user
        $("#user").select2({
            allowClear: true,
            placeholder: {
                id: "",
                placeholder: "Leave blank to ..."
            },
            ajax: {
                url: baseURL + 'travel/master/get/nik',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        persa: 0
                    };
                },
                processResults: function (data, page) {
                    return {
                        results: data.items
                    };
                },
                cache: false
            },
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 3,
            templateResult: function (repo) {
                if (repo.loading) return repo.text;
                var markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
                return markup;
            },
            templateSelection: function (repo) {
                if (repo.posst) $("input[name='caption']").val(repo.posst);
                if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
                else return repo.text;
            }
        });
    }

    if (mode == 'subarea') {
        var data_area = data.split('|');
        var personal_area = data_area[0];
        $.ajax({
            url: baseURL + 'travel/master/get/subarea',
            type: 'POST',
            dataType: 'JSON',
            data: {
                personal_area: personal_area
            },
            success: function (data) {
                var form = "";
                $('#personal_subarea').html("");
                $("#personal_subarea").select2('destroy');
                $.each(data, function (i, v) {
                    form += "<option value='" + v.company_code + "'>" + v.personal_subarea_text + "</option>"
                });

                $("#personal_subarea").select2();
                $('#personal_subarea').html(form);
            }
        })
    }
}

function datatables_ssp() {
    $('#sspTable').DataTable().clear().destroy();
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
    $("#sspTable").dataTable({

        ordering: true,
        order: [[0, 'asc']],
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        columnDefs: [
            { "className": "text-left", "targets": 0 },
            { "className": "text-center", "targets": 1 },
            { "className": "text-left", "targets": 2 },
        ],
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
            url: baseURL + 'travel/master/travel_pic_book',
            type: 'POST',
            data: {
                filterbom: "bom",

            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },
            complete: function () {
                setTimeout(function () {
                    adjustDatatableWidth();
                }, 1000);
            }
        },
        columns: [
            {
                "data": "nama_karyawan",
                "name": "nama_karyawan",
                "width": "20%",
                "render": function (data, type, row) {
                    var user = row.nama_karyawan;//+"<br>"+ label_active; 
                    return user;
                }
            },
            {
                "data": "company_code",
                "name": "company_code",
                "width": "10%",
                "render": function (data, type, row) {
                    return row.company_code;
                }
            },
            {
                "data": "subarea",
                "name": "subarea",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.subarea;
                }
            },
            {
                "data": "level_name",
                "name": "level_name",
                "width": "10%",
                "render": function (data, type, row) {
                    var datalevel = ""; var d = "";
                    if ((row.level_name) != undefined) {
                        $.each((row.level_name).split("|"), function (i, v) {
                            if (d.indexOf(v) === -1) {
                                d += v + ',';
                                datalevel += '<span class="label label-info"> ' + ($.trim(v)) + '</span>|';
                            }
                        })
                        var level_name = (datalevel).replace(/\|/g, '<br>'); //.replace(/\|$/, "");
                        level_name = $.trim(level_name);
                    } else level_name = "";
                    return level_name;
                }
            },
            {
                "data": "id_travel_pic_book",
                "name": "id_travel_pic_book",
                "width": "8%",
                "render": function (data, type, row) {
                    var action = "";
                    var data_edit = row.nik + '|' + row.company_code + '|' + row.personal_area + '|' + row.personal_subarea + '|' + row.jns_user;

                    action = "<li><a href='javascript:void(0);' class='edit' data-edit='" + data_edit + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
                        + "<li><a href='javascript:void(0);' class='delete' data-delete='" + data_edit + "'><i class='fa fa-trash-o'></i> Hapus</a></li>";

                    var output = "<div class='input-group-btn'>"
                        + "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>"
                        + "<ul class='dropdown-menu pull-right'>"
                        + action
                        + "</ul></div>"

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
}