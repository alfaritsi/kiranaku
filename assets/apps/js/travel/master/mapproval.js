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
    if ($("input[name='id_approval']").val() == "") {
        var mode = 'view';
        document.getElementById('jenis_input1').selectedIndex = 0;
        create_select($('#jenis_input1').val(), mode); //this calls it on load
        $('#jenis_input1').change(create_select);
    }

    //show data
    datatables_ssp();

    //submit form
    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate('.form-master-approval', true);
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-master-approval")[0]);
                $.ajax({
                    url: baseURL + 'travel/master/save/approval',
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
        $('#user_app_email').val("");
        var id_approval = $(this).data("edit");
        mode = 'edit';
        var jenis_user = "";
        var user = "";
        var rolex = "";
        var jenis_app = "";
        var app = "";
        var id_app = "";

        $.ajax({
            url: baseURL + 'travel/master/get/approval_detail',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_approval: id_approval
            },
            success: function (data) {
                $(".title-form").html("Edit Role");
                jenis_user = data.jns_user;
                user = data.value_user;
                rolex = data.level;
                jenis_app = data.jns_approval;
                app = data.value_approval;
                id_app = data.id_travel_approval;

                $('#user').html("");
                // ======================================================user
                if (data.jns_user == 'nik') {
                    if ($("#user").hasClass("select2") == true) {
                        $("#user").select2('destroy');
                        $("#user").removeClass('select2');
                    }
                    $('#user').html("");
                    var user = (data.value_user + '').split(",");
                    var nama_user = (data.user_name + '').split(",");
                    var array = [];
                    $.each(nama_user, function (x, y) {
                        $("#user").html("<option value=" + user[x] + ">" + y + "</option>");
                    });
                } else {
                    $("#user").select2();
                    $('#user').html("");
                    $("#user").html("<option value=" + user + ">" + data.user_name + "</option>");
                }

                // ======================================================app
                var role = data.level;
                $('#user_app').html("");
                if (role == '0') {
                    $("#user_app").prop("multiple", true);
                } else {
                    $("#user_app").prop("multiple", false);
                }
                if (data.jns_approval == 'nik') {
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
                    var approvalx = (data.value_approval).replace(/\.$/, "").substring(1);
                    var peserta = (approvalx + '').split(".");
                    var nama_peserta = (data.approval_name + '').slice(0, -1).split("|");
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
                    var user_app = app.replace(/\.$/, "").substring(1).split(".");
                    $('#user_app').val(user_app).trigger('change');
                }

                // ====================================================== email    
                $("#user_app_email").select2({
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
                        // return repo.text;        
                        if (repo.posst) $("input[name='caption']").val(repo.posst);
                        if (repo.nama && repo.nik) return repo.nama + ' - [' + repo.nik + ']';
                        else return repo.text;
                    }
                });
                if (data.email_approval != undefined) {
                    var approvalx_email = (data.email_approval).replace(/\.$/, "").substring(1);
                    var peserta = (approvalx_email + '').split(".");
                    var nama_peserta = (data.approval_email_name + '').slice(0, -1).split("|");
                    var array = [];
                    $.each(nama_peserta, function (x, y) {
                        var control = $('#user_app_email').empty().data('select2');
                        var adapter = control.dataAdapter;
                        array.push({ "id": peserta[x], "text": y });

                        adapter.addOptions(adapter.convertToOptions(array));
                        $('#user_app_email').trigger('change');
                    });
                    $('#user_app_email').val(peserta).trigger('change');
                }

            }, complete: function (data) {
                $("input[name='id_approval']").val(id_app);
                $("select[name='jenis_input1']").val(jenis_user).trigger('change.select2');
                // $("select[name='user']").val(v.value_user).trigger('change.select2');
                $("select[name='role']").val(rolex).trigger('change.select2');
                // $("select[name='user_app']").val(v.value_approval).trigger('change.select2');
                $("select[name='jenis_input2']").val(jenis_app).trigger('change.select2');

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

    // change jenis input user
    $(document).on("change", "#jenis_input1", function (e) {
        var jenis_input = $(this).val();
        $("#user").select2('destroy');
        $('#user').html("");
        if (jenis_input == 'nik') {

            //auto complete nik
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
                            // page: params.page,
                            // program: $("select[name='program']").val(),
                            // pabrik: $("select[name='pabrik[]']").val(),
                            // peserta_tambahan: 'n'
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
                    // return repo.text;        
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
                    var form = ""; $('#user').html("");
                    $.each(data, function (i, v) {
                        form += "<option value='" + v.id_jabatan + "'>" + v.nama + "</option>"
                    });
                    $("#user").select2();
                    $('#user').html(form);
                }
            });
        }
    });

    // change jenis input approver
    $(document).on("change", "#jenis_input2", function (e) {
        var jenis_input = $(this).val();
        var role = $('#role').val(); //($('#role').val() != undefined || $('#role').val() != "" ) ?  $('#role').val() : "x" ;
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

    // change LEVEL approver
    $(document).on("change click", "#role", function (e) {
        var jenis_input = $('#jenis_input2').val();
        var role = ($('#role').val() != undefined || $('#role').val() != "") ? $('#role').val() : "x";
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
                    // return repo.text;        
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
                        url: baseURL + 'travel/master/set/approval',
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
        var jenis_input = $('#jenis_input1').val();
        $("#user").select2('destroy');
        $('#user').html("");
        // for approver
        if (jenis_input == 'nik') {
            //auto complete nik
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
                    // return repo.text;        
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
                    var form = ""; $('#user').html("");
                    $.each(data, function (i, v) {
                        form += "<option value='" + v.id_jabatan + "'>" + v.nama + "</option>"
                    });
                    $("#user").select2();
                    $('#user').html(form);
                }
            });
        }

        // for email approver     
        $("#user_app_email").select2('destroy');
        $('#user_app_email').html("");
        //auto complete nik
        $("#user_app_email").select2({
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
        order: [
            [0, 'asc'],
            [1, 'asc'],
            [2, 'asc']
        ],
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        columnDefs: [
            { "className": "text-left", "targets": 0 },
            { "className": "text-left", "targets": 1, "type": 'natural' },
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
            url: baseURL + 'travel/master/travel_approval',
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
                "data": "jns_user",
                "name": "jns_user",
                "width": "10%",
                "visible": false,
                "render": function (data, type, row) {
                    return row.jns_user;
                }
            },
            {
                "data": "user_name",
                "name": "user_name",
                "width": "20%",
                "render": function (data, type, row) {
                    // label active
                    if (row.na == "n" && row.del == "n")
                        var label_active = '<span class="label label-success">ACTIVE</span>';
                    else if (row.na == "y" && row.del == "n")
                        var label_active = '<span class="label label-danger">NOT ACTIVE</span>';
                    else if (row.del == "y")
                        var label_active = '<span class="label label-danger">DELETED</span>';
                    var role = row.user_name
                        + "<br>" + label_active;
                    return role;
                }
            },
            {
                "data": "level",
                "name": "level",
                "width": "10%",
                "render": function (data, type, row) {
                    return row.role_nama;
                }
            },
            {
                "data": "approval_name",
                "name": "approval_name",
                "width": "20%",
                "render": function (data, type, row) {
                    var app = (row.approval_name).replace(/\|/g, '<br>'); //.replace(/\|$/, "");
                    return app;
                }
            },
            {
                "data": "approval_email",
                "name": "approval_email",
                "width": "20%",
                "render": function (data, type, row) {
                    var app_email = (row.approval_email) != undefined ? (row.approval_email).replace(/\|/g, '<br>') : ""; //.replace(/\|$/, "");
                    return app_email;
                }
            },
            {
                "data": "id_travel_approval",
                "name": "id_travel_approval",
                "width": "8%",
                "render": function (data, type, row) {
                    var action = "";
                    if (row.na == 'n') {
                        action = "<li><a href='javascript:void(0);' class='edit' data-edit='" + row.id_travel_approval + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
                            + "<li><a href='javascript:void(0);' class='nonactive' data-nonactive='" + row.id_travel_approval + "'><i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                            + "<li><a href='javascript:void(0);' class='delete' data-delete='" + row.id_travel_approval + "'><i class='fa fa-trash-o'></i> Hapus</a></li>";

                    }
                    if (row.na == 'y') {
                        action = "<li><a href='javascript:void(0);' class='setactive' data-setactive='" + row.id_travel_approval
                            + "'><i class='fa fa-check'></i> Set Aktif</a></li>";
                    }

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