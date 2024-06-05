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
    var mode = 'view';

    //show data
    datatables_ssp();

    //submit form
    $(document).on("click", "button[name='action_btn']", function (e) {

        // var jenis_temuan 	= $("#jenis_temuan").val();
        // var isproses 		= $("input[name='isproses']").val();
        var empty_form = validate('.form-master-role', true);
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-master-role")[0]);
                $.ajax({
                    url: baseURL + 'travel/master/save/role',
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
        var id_role = $(this).data("edit");
        mode = 'edit';
        $.ajax({
            url: baseURL + 'travel/master/get/role',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_role: id_role
            },
            success: function (data) {
                $(".title-form").html("Edit Role");
                $.each(data, function (i, v) {
                    $("input[name='id_role']").val(v.id_travel_role);
                    $("input[name='nama_role']").val(v.role);
                    $("input[name='level']").val(v.level);
                    $("input[name='if_approve_spd_hidden']").val(v.if_approve_spd);
                    $("input[name='if_approve_dec_hidden']").val(v.if_approve_declare);
                    $("input[name='if_approve_spd_um_hidden']").val(v.if_approve_spd_um);
                    $("input[name='if_approve_cancel_hidden']").val(v.if_approve_cancel);
                    $("input[name='if_decline_spd_hidden']").val(v.if_decline_spd);
                    $("input[name='if_decline_spd_um_hidden']").val(v.if_decline_spd_um);
                    $("input[name='if_decline_dec_hidden']").val(v.if_decline_declare);
                    $("input[name='if_decline_cancel_hidden']").val(v.if_decline_cancel);

                    $("select[name='if_approve_spd']").val(v.if_approve_spd).trigger('change.select2');
                    $("select[name='if_approve_spd_um']").val(v.if_approve_spd_um).trigger('change.select2');
                    $("select[name='if_approve_dec']").val(v.if_approve_declare).trigger('change.select2');
                    $("select[name='if_approve_cancel']").val(v.if_approve_cancel).trigger('change.select2');
                    $("select[name='if_decline_spd']").val(v.if_decline_spd).trigger('change.select2');
                    $("select[name='if_decline_spd_um']").val(v.if_decline_spd_um).trigger('change.select2');
                    $("select[name='if_decline_dec']").val(v.if_decline_declare).trigger('change.select2');
                    $("select[name='if_decline_cancel']").val(v.if_decline_cancel).trigger('change.select2');

                    $("#btn-new").show();
                });

            }
        });
    });

    //reload / create new input
    $("#btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
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
                        url: baseURL + 'travel/master/set/role',
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
        var datatemuan = data;
        var splitdata = datatemuan.split("|");
        var id_temuan = splitdata[0];
        $.ajax({
            url: baseURL + 'pica/master/pica_role_normal',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_temuan: id_temuan,
                // type    : 'si'
            },
            success: function (data) {
                $('#if_approve').html('');
                $('#if_decline').html('');
                var form = ''; var valtriger = 0;
                $.each(data, function (i, v) {
                    if (i == 0) valtriger = v.level;
                    form += "<option value='" + v.level + "'>" + v.nama_role + "</option>"
                });
                var formapp = form + "<option value='100'>Finish</option>";
                var formdec = "<option value='-'>Silahkan pilih role</option>" + form;
                $('#if_approve').html(formapp);
                $('#if_decline').html(formdec);
                if ($("#id_role").val() == undefined) {
                    $('#if_approve').val(valtriger).trigger('change');
                } else {
                    var val_app = $("#if_approve_hidden").val();
                    var val_dec = $("#if_decline_hidden").val();
                    $('#if_approve').val(val_app).trigger('change');
                    $('#if_decline').val(val_dec).trigger('change');
                }
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
            url: baseURL + 'travel/master/travel_role',
            type: 'POST',
            data: {
                filterbom: "bom",
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },
            complete: function (data) {
                setTimeout(function () {
                    adjustDatatableWidth();
                }, 1000);
            }
        },
        columns: [
            {
                "data": "role, approve_spd, approve_declare, approve_cancel, decline_spd, decline_declare, decline_cancel",
                "name": "role",
                "width": "20%",
                "render": function (data, type, row) {
                    // label active
                    if (row.na == "n" && row.del == "n") { var label_active = '<span class="label label-success">ACTIVE</span>'; }
                    else if (row.na == "y" && row.del == "n") { var label_active = '<span class="label label-danger">NOT ACTIVE</span>'; }
                    else if (row.del == "y") { var label_active = '<span class="label label-danger">DELETED</span>'; }
                    // result
                    var app_spd = row.if_approve_spd == "99" ? "Finish" : row.approve_spd;
                    var app_spd_um = row.if_approve_spd_um == "99" ? "Finish" : row.approve_spd_um;
                    var app_declare = row.if_approve_declare == "99" ? "Finish" : row.approve_declare;
                    var app_cancel = row.if_approve_cancel == "99" ? "Finish" : row.approve_cancel;
                    var role = row.role
                        + "<br><span class='label label-info'>form SPD :</span>"
                        + "<br><span class='label label-default'>Jika Disetujui : " + app_spd
                        + "</span>"
                        + "<br><span class='label label-default'>Jika Ditolak/Revisi : " + row.decline_spd
                        + "</span>"
                        + "<br><span class='label label-info'>form SPD dengan UM:</span>"
                        + "<br><span class='label label-default'>Jika Disetujui : " + app_spd_um
                        + "</span>"
                        + "<br><span class='label label-default'>Jika Ditolak/Revisi : " + row.decline_spd_um
                        + "</span>"
                        + "<br><span class='label label-info'>form Declaration :</span>"
                        + "<br><span class='label label-default'> Jika Disetujui : " + app_declare
                        + "</span>"
                        + "<br><span class='label label-default'> Jika Ditolak/Revisi : " + row.decline_declare
                        + "</span>"
                        + "<br><span class='label label-info'>form Cancel :</span>"
                        + "<br><span class='label label-default'> Jika Disetujui : " + app_cancel
                        + "</span>"
                        + "<br><span class='label label-default'> Jika Ditolak/Revisi : " + row.decline_cancel
                        + "</span>"
                        + "<br>" + label_active;
                    return role;
                }
            },
            {
                "data": "level",
                "name": "level",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.level;
                }
            },
            {
                "data": "id_travel_role",
                "name": "id_travel_role",
                "width": "12%",
                "render": function (data, type, row) {
                    var action = "";
                    if (row.na == 'n') {
                        action = "<li><a href='javascript:void(0);' class='edit' data-edit='" + row.id_travel_role + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
                            + "<li><a href='javascript:void(0);' class='nonactive' data-nonactive='" + row.id_travel_role + "'><i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                            + "<li><a href='javascript:void(0);' class='delete' data-delete='" + row.id_travel_role + "'><i class='fa fa-trash-o'></i> Hapus</a></li>";

                    }
                    if (row.na == 'y') {
                        action = "<li><a href='javascript:void(0);' class='setactive' data-setactive='" + row.id_travel_role
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