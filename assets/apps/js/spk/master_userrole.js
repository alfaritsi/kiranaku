$(document).ready(function () {
    get_data_userrole();

    $(document).on("change", "select[name='id_role']", function(e) {
        $('select[name="user"]').val("").trigger("change");
    });

    $(document).on("click", ".btn-new, #btn_reset", function (e) {
        // location.reload();
        // e.preventDefault();
        // return false;
        reset_form();
    });

    $("select[name='user']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'spk/master/get/user',
            dataType: 'json',
            delay: 750,
            data: function(params) {
                return {
                    autocomplete: true,
                    tipe_user: $("select[name='id_role'] option:selected").data("tipe_user"),
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
            let type = $("select[name='id_role'] option:selected").data("tipe_user");
            if (typeof type == 'undefined') {
                kiranaAlert(false, "Silahkan pilih role terlebih dahulu", "error", 'no');
                return false;
            }

            if (repo.loading) return repo.text;
            let markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
            if (type == 'posisi')
                markup = '<div class="clearfix">' + repo.nama + '</div>';
            return markup;
        },
        templateSelection: function(repo) {
            let type = $("select[name='id_role'] option:selected").data("tipe_user");

            let markup = "Silahkan Pilih";
            if (repo) {
                if (type == 'posisi' && repo.jml_karyawan) {
                    markup = repo.nama;
                    // $("input[name='caption']").val(repo.nama);
                } else if (type == 'nik' && repo.nik) {
                    markup = repo.nama + ' - [' + repo.nik + ']';
                    // $("input[name='caption']").val(repo.posst);
                } else {
                    if (repo.text)
                        markup = repo.text;
                    // if (repo.id == "")
                    //     $("input[name='caption']").val("");
                }
            }

            return markup;
        }
    });

    //check all plant
    $(document).on("change", ".isSelectAllPlant", function(e) {
        if ($(".isSelectAllPlant").is(':checked')) {
            $('#pabrik').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#pabrik').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        const empty_form = validate('#form-master');

        if (empty_form == 0) {
            let isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData($("#form-master")[0]);

                $.ajax({
                    url: baseURL + 'spk/master/save/userrole',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                get_data_userrole()
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
        reset_form();
        const id_user_role = $(this).attr('data-edit');
        $.ajax({
            url: baseURL + 'spk/master/get/userrole',
            type: 'POST',
            dataType: 'JSON',
            data: {
                return: 'json',
                all: 'yes',
                id_user_role: id_user_role
            },
            success: function (data) {
                // validator.resetForm();
                if (data) {
                    $("input[name='id_user_role']").val(id_user_role);
                    $('select[name="id_role"]').val(data.id_role).trigger("change");
                    const tipe_user = $("select[name='id_role'] option:selected").data("tipe_user");
                    //buat auto complete user
					if (tipe_user == 'posisi' && data.jml_karyawan) {
                        markup = data.caption + ' - [jumlah ' + data.jml_karyawan + ']';
                        value = data.id;
                    } else if (tipe_user == 'nik' && data.user) {
                        markup = data.caption + ' - [' + data.user + ']';
                        value = data.id;
                    } else {
                        markup = 'No Text';
                        value = null;
                    }
                    $("select[name='user']").append(new Option(markup, value, true, true)).trigger("change.select2");
					//buat pabrik	
					if(data.pabrik!=null){
						const pabrik = data.pabrik.split(",");
						$("select[name='pabrik[]']").val(pabrik).trigger("change");
					}
                    $('select[name="id_role"]').prop("disabled", true);
                    $('select[name="user"]').prop("disabled", true);
                } else {
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    })

    $(document).on("click", '.delete', function (e) {
        const id_user_role = $(this).attr("data-delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'spk/master/set/userrole',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_user_role: id_user_role,
                            action: 'delete'
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg, 'success', 'no');
                                get_data_userrole();
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
        const id_user_role = $(this).attr("data-active");
        const action = $(this).attr("data-action");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan "+action+" data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'spk/master/set/userrole',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_user_role: id_user_role,
                            action: action
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg, 'success', 'no');
                                get_data_userrole();
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

function get_data_userrole() {
    $("#sspTable").DataTable().clear().destroy();

    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
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
        initComplete: function() {
            var api = this.api();
            $("#sspTable_filter input").attr("placeholder", "Press enter to start searching");
            $("#sspTable_filter input").attr("title", "Press enter to start searching");
            $("#sspTable_filter input").off(".DT").on("keypress change", function(evt) {
                if (evt.type == "change") {
                    api.search(this.value).draw();
                }
            });
        },
        oLanguage: {
            sProcessing: "Please wait ..."
        },
        processing: true,
        // serverSide: true,
        searching: true,
        // columnDefs: [{ "targets": 2, "type": "date-eu" }],
        ajax: {
            url: baseURL + "spk/master/get/userrole",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                all: 'yes',
                id_jenis_spk: $("select[name='jenis_spk_filter']").val(),
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },
            complete: function() {}
        },
        columns: [{
            data: "user",
            name: "user",
            width: "25%",
            render: function(data, type, row) {
                let output = row.user + " (" + row.caption + ")";
                output += '<div><button class="btn btn-xs btn-' + row.label_active + '">' + row.status_active + '</button></div>';
                return output;
            },
            visible: true,
            orderable: true
        },
        {
            data: "nama_role",
            name: "nama_role",
            width: "25%",
            render: function(data, type, row) {
                return row.nama_role;
            },
            visible: true,
            orderable: true
        },
        {
            data: "pabrik",
            name: "pabrik",
            render: function(data, type, row) {
                let output = "";
                // const list_pabrik = row.pabrik.split(",");
                $.each(row.pabrik.split(",").filter(item => item), function(i, v) {
                    output += '<small class="label bg-blue">' + v + '</small> ';
                });
                output += "";
                return output;
            },
            visible: true,
            orderable: false
        },
        {
            data: "deskripsi",
            name: "deskripsi",
            width: "5%",
            render: function(data, type, row) {
                output = "			<div class='btn-group'>";
                output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                output += "				<ul class='dropdown-menu pull-right'>";
                output += "                 <li><a href='#' class='edit' data-edit='" + row.id_user_role + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
                if (row.na == 'n') {
                    output += "                 <li><a href='#' class='activate' data-active='" + row.id_user_role + "' data-action='deactivate'><i class='fa fa-minus text-danger'></i> Non Active</a></li>";
                    output += "                 <li><a href='#' class='delete' data-delete='" + row.id_user_role + "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                }else{
                    output += "                 <li><a href='#' class='activate' data-active='" + row.id_user_role + "' data-action='activate'><i class='fa fa-check text-success'></i> Set Active</a></li>";
                }
                output += "				</ul>";
                output += "	        </div>";
                return output;
            },
            visible: true,
            orderable: false
        }
    ],
        rowCallback: function(row, data, iDisplayIndex) {
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
                    if (data.is_limit == 1) $('select[name="if_approve"]').val("finish").trigger("change");
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
    $('input[name="id_user_role"]').val("");
    $('select[name="id_role"]').val("").trigger("change");
    $('select[name="id_role"]').prop("disabled", false);
    $('select[name="user"]').val("").trigger("change");
    $('select[name="user"]').prop("disabled", false);
    $('select[name="pabrik[]"]').val("").trigger("change");
    $('.isSelectAllPlant').prop("checked", false);
}