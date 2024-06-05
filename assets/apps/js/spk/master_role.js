$(document).ready(function () {
    get_data_role();

    $(document).on("change", "select[name='jenis_spk_filter']", function (e) {
        get_data_role();
    });

    $(document).on("change", "select[name='id_jenis_spk']", function (e) {
        get_role_dtl();
    });

    $(document).on("change", "input[name='is_paralel']", function(){
        const isChecked = $(this).prop('checked');
        if (isChecked) {
            $(".input-paralel").removeClass("hidden");
            $("select[name='divisi_terkait[]']").attr('required', true);
        } else {
            $(".input-paralel").addClass("hidden");
            $("select[name='divisi_terkait[]']").attr('required', false);
        }
    });

    $(".btn-new").on("click", function (e) {
        // location.reload();
        // e.preventDefault();
        // return false;
        reset_form();
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        const empty_form = validate('#form-master');

        if (empty_form == 0) {
            let isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData($("#form-master")[0]);

                $.ajax({
                    url: baseURL + 'spk/master/save/role',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                get_data_role();
                                reset_form();
                                $("input[name='isproses']").val(0);
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', '.edit', function (e) {
        const id_role = $(this).attr('data-edit');
        $.ajax({
            url: baseURL + 'spk/master/get/role',
            type: 'POST',
            dataType: 'JSON',
            data: {
                return: 'json',
                all: 'yes',
                id_role: id_role
            },
            success: function (data) {
                // validator.resetForm();
                if (data) {
                    $('input[name="id_role"]').val(id_role);
                    $('input[name="nama_role"]').val(data.nama_role);
                    $('input[name="level"]').val(data.level);
                    $('select[name="tipe_user"]').val(data.tipe_user).trigger("change");
                    $('input[name="is_akses_buat"]').prop("checked", data.akses_buat);
                    $('input[name="is_akses_hapus"]').prop("checked", data.akses_hapus);
                    $('input[name="is_ho"]').prop("checked", data.ho);
                    $('input[name="is_paralel"]').prop("checked", data.paralel).trigger("change");
                    $('select[name="id_jenis_spk"]').val("").trigger("change");

                    $(".btn-new").removeClass("hidden");

                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    })

    $(document).on("click", '.delete', function (e) {
        const id_role = $(this).attr("data-delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'spk/master/set/role',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_role: id_role,
                            action: 'delete'
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg, 'success', 'no');
                                get_data_role();
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );

    });

    $(document).on("click", ".activate", function (e) {
        const id_role = $(this).attr("data-active");
        const action = $(this).attr("data-action");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan " + action + " data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'spk/master/set/role',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_role: id_role,
                            action: action
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg, 'success', 'no');
                                get_data_role();
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );
    });
});

function get_data_role() {
    $("#sspTable").DataTable().clear().destroy();

    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        return {
            iStart: oSettings._iDisplayStart,
            iEnd: oSettings.fnDisplayEnd(),
            iLength: oSettings._iDisplayLength,
            iTotal: oSettings.fnRecordsTotal(),
            iFilteredTotal: oSettings.fnRecordsDisplay(),
            iPage: Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            iTotalPages: Math.ceil(
                oSettings.fnRecordsDisplay() / oSettings._iDisplayLength
            )
        };
    };

    $("#sspTable").dataTable({
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"]
        ],
        ordering: $("#sspTable").data("ordering") ? $("#sspTable").data("ordering") : false,
        scrollY: $("#sspTable").data("scrolly") ? $("#sspTable").data("scrolly") : false,
        scrollX: $("#sspTable").data("scrollx") ? $("#sspTable").data("scrollx") : false,
        bautoWidth: $("#sspTable").data("bautowidth") ? $("#sspTable").data("bautowidth") : false,
        pageLength: $("#sspTable").data("pagelength") ? $("#sspTable").data("pagelength") : 10,
        paging: $("#sspTable").data("paging") ? $("#sspTable").data("paging") : true,
        fixedHeader: $("#sspTable").data("fixedheader") ? $("#sspTable").data("fixedheader") : false,
        order: [
            [0, 'asc']
        ],
        initComplete: function () {
            var api = this.api();
            $("#sspTable_filter input").attr("placeholder", "Press enter to start searching");
            $("#sspTable_filter input").attr("title", "Press enter to start searching");
            $("#sspTable_filter input").off(".DT").on("keypress change", function (evt) {
                if (evt.type == "change") {
                    api.search(this.value).draw();
                }
            });
        },
        oLanguage: {
            sProcessing: "Please wait ..."
        },
        processing: true,
        serverSide: true,
        searching: true,
        // columnDefs: [{ "targets": 2, "type": "date-eu" }],
        ajax: {
            url: baseURL + "spk/master/get/role",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                all: 'yes',
                id_jenis_spk: $("select[name='jenis_spk_filter']").val(),
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },
            complete: function () { }
        },
        columns: [{
            data: "nama_role",
            name: "nama_role",
            width: "30%",
            render: function (data, type, row) {
                let output = row.nama_role;
                if (row.na == "n") output += '<div><small class="label bg-green">Aktif</small></div>';
                else output += '<div><small class="label bg-red">Non Aktif</small></div>';
                return output;
            },
            visible: true,
            orderable: true
        },
        {
            data: "level",
            name: "level",
            width: "10%",
            render: function (data, type, row) {
                return row.level;
            },
            visible: true,
            orderable: true
        },
        {
            data: "detail",
            name: "detail",
            render: function (data, type, row) {
                let output = "<small>";
                output += "<div>Tipe User: " + row.tipe_user + "</div>";
                output += "<div>Akses Buat: " + (row.akses_buat == 0 ? "Tidak" : "Ya") + "</div>";
                output += "<div>Akses Hapus: " + (row.akses_hapus == 0 ? "Tidak" : "Ya") + "</div>";
                output += "<div>Lokasi: " + (row.ho == 0 ? "Pabrik" : "HO") + "</div>";
                output += "<br>"
                if (row.id_jenis_spk) {
                    output += "<div><b>Jenis Perjanjian:</b> " + row.jenis_spk + "</div>";
                    if (row.if_approve) {
                        if (row.if_approve === "confirmed")
                            output += "<div><b>If Approve:</b> Confirmed (Final Draft)</div>";
                        else 
                            output += "<div><b>If Approve:</b> " + row.approve + "</div>";
                    }
                    // if (row.is_limit) {
                    //     output += "<div><b>If Approve:</b> Finish (Final Draft)</div>";
                    // }
                    if (row.if_decline) {
                        if (row.if_decline === "owner")
                            output += "<div><b>If Decline:</b> Pembuat Perjanjian</div>";
                        else 
                            output += "<div><b>If Decline:</b> " + row.decline + "</div>";
                    }
                    if (row.paralel) {
                        output += "<div><b>Divisi Terkait:</b> " + row.nama_divisi_terkait + "</div>";
                    }
                }
                output += "</small>";
                return output;
            },
            visible: true,
            orderable: false
        },
        {
            data: "deskripsi",
            name: "deskripsi",
            width: "5%",
            render: function (data, type, row) {
                output = "			<div class='btn-group'>";
                output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                output += "				<ul class='dropdown-menu pull-right'>";
                output += "                 <li><a href='#' class='edit' data-edit='" + row.id_role + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
                if (row.na == 'n') {
                    output += "                 <li><a href='#' class='activate' data-active='" + row.id_role + "' data-action='deactivate'><i class='fa fa-minus text-danger'></i> Non Active</a></li>";
                    output += "                 <li><a href='#' class='delete' data-delete='" + row.id_role + "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                } else {
                    output += "                 <li><a href='#' class='activate' data-active='" + row.id_role + "' data-action='activate'><i class='fa fa-check text-success'></i> Set Active</a></li>";
                }
                output += "				</ul>";
                output += "	        </div>";
                return output;
            },
            visible: true,
            orderable: false
        }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $("td:eq(0)", row).html();
        }
    });
}

function get_role_dtl() {
    const id_role = $('input[name="id_role"]').val();
    const id_jenis_spk = $('select[name="id_jenis_spk"]').val();
    $('select[name="divisi_terkait[]"]').val("").trigger("change");
    if (id_role && id_jenis_spk) {
        $.ajax({
            url: baseURL + 'spk/master/get/role',
            type: 'POST',
            dataType: 'JSON',
            data: {
                return: 'json',
                id_role: id_role,
                id_jenis_spk: id_jenis_spk
            },
            success: function (data) {
                // validator.resetForm();
                if (data) {
                    $('select[name="if_approve"]').val(data.if_approve).trigger("change");
                    $('select[name="if_decline"]').val(data.if_decline).trigger("change");
                    // if (data.is_limit == 1) $('select[name="if_approve"]').val("finish").trigger("change");
                    if (data.divisi_terkait) {
                        const divisi = data.divisi_terkait.split(",");
                        $("select[name='divisi_terkait[]']").val(divisi).trigger("change");
                    }
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    } else {
        $('select[name="if_approve"]').val("").trigger("change");
        $('select[name="if_decline"]').val("").trigger("change");
    }
}

const reset_form = () => {
    $('input[name="id_role"]').val("");
    $('input[name="nama_role"]').val("");
    $('input[name="level"]').val("");
    $('select[name="tipe_user"]').val("").trigger("change");
    $('input[name="is_akses_buat"]').prop("checked", false);
    $('input[name="is_akses_hapus"]').prop("checked", false);
    $('input[name="is_ho"]').prop("checked", false);
    $('input[name="is_paralel"]').prop("checked", false).trigger("change");
    $('select[name="tipe_user"]').val("").trigger("change");
    $('select[name="id_jenis_spk"]').val("").trigger("change");
}