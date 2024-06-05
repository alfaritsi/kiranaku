$(document).ready(function () {
    get_data_user();

    $(document).on("click", "#btn-new, #btn_reset", function (e) {
        reset_form();
    });

    $("select[name='nik']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'outspec/setting/get/karyawan',
            dataType: 'json',
            delay: 750,
            data: function(params) {
                return {
                    autocomplete: true,
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
            if (repo.loading) return repo.text;
            let markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
            return markup;
        },
        templateSelection: function(repo) {
            let markup = "Silahkan Pilih";
            if (repo) {
                if (repo.nik) {
                    markup = repo.nama + ' - [' + repo.nik + ']';
                } else {
                    if (repo.text)
                        markup = repo.text;
                }
            }

            return markup;
        }
    });

    $("select[name='nik']").on('select2:select select2:unselecting change', function (e) {
        let plant = "";
        if (typeof e.params !== "undefined" && e.params.data) {
            plant = e.params.data.gsber;
        }
        $("input[name='plant']").val(plant);
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        const empty_form = validate('#form-setting');

        if (empty_form == 0) {
            let isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData($("#form-setting")[0]);

                $.ajax({
                    url: baseURL + 'outspec/setting/save/user',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                get_data_user()
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

    $(document).on("click", '.delete', function (e) {
        const id_user = $(this).attr("data-delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'outspec/setting/set/user',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_user: id_user,
                            action: 'delete'
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg, 'success', 'no');
                                get_data_user();
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
        const id_user = $(this).attr("data-active");
        const action = $(this).attr("data-action");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan " + action + " data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'outspec/setting/set/user',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id_user: id_user,
                            action: action
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg, 'success', 'no');
                                get_data_user();
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

function get_data_user() {
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
        // serverSide: true,
        searching: true,
        // columnDefs: [{ "targets": 2, "type": "date-eu" }],
        ajax: {
            url: baseURL + "outspec/setting/get/user",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                all: 'yes',
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },
            complete: function () { }
        },
        columns: [{
            data: "nik",
            name: "nik",
            render: function (data, type, row) {
                let output = row.nik + " (" + row.nama + ")";

                let label = 'success';
                let status = 'AKTIF'
                if (row.na != 'n') {
                    label = 'danger';
                    status = 'NON AKTIF';
                }
                output += '<div><button class="btn btn-sm btn-' + label + '">' + status + '</button></div>';
                return output;
            },
            visible: true,
            orderable: true
        },
        {
            data: "plant",
            name: "plant",
            visible: true,
            orderable: true
        },
        {
            data: "deskripsi",
            name: "deskripsi",
            width: "5%",
            render: function (data, type, row) {
                output = "			<div class='btn-group'>";
                output += "				<button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><span class='fa fa-caret-down'></span></button>";
                output += "				<ul class='dropdown-menu pull-right'>";
                if (row.na == 'n') {
                    output += "                 <li><a href='#' class='activate' data-active='" + row.id_user + "' data-action='deactivate'><i class='fa fa-minus text-danger'></i> Non Active</a></li>";
                    output += "                 <li><a href='#' class='delete' data-delete='" + row.id_user + "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                } else {
                    output += "                 <li><a href='#' class='activate' data-active='" + row.id_user + "' data-action='activate'><i class='fa fa-check text-success'></i> Set Active</a></li>";
                }
                output += "				</ul>";
                output += "	        </div>";
                return output;
            },
            visible: true,
            orderable: false,
            className: 'text-center'
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

const reset_form = () => {
    $('input[name="id_user"]').val("");
    $('select[name="nik"]').val("").trigger("change");
    $('input[name="plant"]').val("");
}