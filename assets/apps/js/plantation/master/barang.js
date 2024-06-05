$(document).ready(function() {
    get_data_barang();
    // $("#sspTable").dataTable();

    master_asset_class($("#asset_class"));
    master_gl_account($("#gl_account"));
    master_cost_center($("#cost_center"));

    $(document).on("click", ".set_data", function(){
        $('#modal_master_barang').modal('show');
		const kode_barang = this.dataset.id;
        const nama_barang = this.dataset.desc;
        const tipe = this.dataset.classification;

        let cost_center = this.dataset.cost_center ? this.dataset.cost_center : "";
        let cost_center_name = this.dataset.cost_center_name ? this.dataset.cost_center_name : "";
        
        $("#kode_barang").val(kode_barang);
        $("#nama_barang").val(nama_barang);
        $("#tipe_barang").val(tipe).trigger('change');
        
        if (tipe == "A") {
            const elem = $("#form-set-data #asset_class");
            master_asset_class(elem);
            let control = $(elem).empty().data('select2');
            let adapter = control.dataAdapter;
            let text = `[${this.dataset.asset_class}] ${this.dataset.asset_class_desc}`;
            adapter.addOptions(adapter.convertToOptions([{'id': this.dataset.asset_class, 'text': text}]));
            $(elem).trigger('change');
        } else if (tipe == "K") {
            const elem = $("#form-set-data #gl_account");
            master_gl_account(elem);
            let control = $(elem).empty().data('select2');
            let adapter = control.dataAdapter;
            let text = `[${this.dataset.gl_account}] ${this.dataset.gl_account_desc}`;
            adapter.addOptions(adapter.convertToOptions([{'id': this.dataset.gl_account, 'text': text}]));
            $(elem).trigger('change');
        }

        $("select[name='cost_center[]']").empty();
        
        if (cost_center !== "") {
            cost_center_name = cost_center_name.split(";");
            $.each(cost_center.split(";"), function(i, v) {
                cost_center_opt = new Option(
                    "[" + v + "] - " + cost_center_name[i],
                    v,
                    true,
                    true
                );
                $("select[name='cost_center[]']")
                    .append(cost_center_opt)
                    .trigger("change");
            });
        } else {
            $("select[name='cost_center[]']")
                .empty()
                .trigger("change");
        }
    });

    // save
    $(document).on("click", "button[name='action_btn_save']", function (e) {
        var empty_form = validate('#form-set-data');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($("#form-set-data")[0]);
                $.ajax({
                    url: baseURL + 'plantation/master/save/barang',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                // location.reload();
                                get_data_barang();
                                $('#modal_master_barang').modal('hide');
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function () {
                        swal('Error', 'Server Error', 'error');
                    },
                    complete: function () {
                        $("input[name='isproses']").val(0);
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

    $(document).on("change", "#tipe_barang", function(e) {
        const tipe = $(this).val();
        
        $("#form-set-data .additional").addClass('hidden');
        $('#asset_class').val('').trigger('change');
        $('#gl_account').val('').trigger('change');
        $('#cost_center').val('').trigger('change');
        $('#asset_class').prop('required', false);
        $('#gl_account').prop('required', false);
        $('#cost_center').prop('required', false);

        if (tipe == "A") {
            $('#asset_class').prop('required', true);
            $('#cost_center').prop('required', true);
            $("#form-set-data #add_asset_class").removeClass('hidden');
            $("#form-set-data #add_cost_center").removeClass('hidden');
        } else if (tipe == "K") {
            $('#gl_account').prop('required', true);
            $('#cost_center').prop('required', true);
            $("#form-set-data #add_gl_account").removeClass('hidden');
            $("#form-set-data #add_cost_center").removeClass('hidden');
        }
    })
});

function get_data_barang() {
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
        serverSide: true,
        searching: true,
        // columnDefs: [{ "targets": 2, "type": "date-eu" }],
        ajax: {
            url: baseURL + "plantation/master/get/barang",
            type: "POST",
            dataType: "JSON",
            data: {
                return: "datatables",
                data: "header",
                pabrik: $("select[name='pabrik']").val(),
                is_active: 1
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
                KIRANAKU.alert({
                    text: "Server Error",
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
            complete: function() {}
        },
        columns: [{
                data: "MATNR",
                name: "MATNR",
                width: "15%",
                render: function(data, type, row) {
                    let output = "";
                    output += row.MATNR;
                    return output;
                },
                visible: true,
                orderable: true
            },
            {
                data: "MAKTX",
                name: "MAKTX",
                // width: "30%",
                render: function(data, type, row) {
                    return row.MAKTX;
                },
                visible: true,
                orderable: true
            },
            {
                data: "GROES",
                name: "GROES",
                // width: "45%",
                render: function(data, type, row) {
                    return row.GROES;
                },
                visible: true,
                orderable: true
            },
            {
                data: "classification",
                name: "classification",
                width: "10%",
                render: function(data, type, row) {
                    let classification = "-"
                    switch (row.classification) {
                        case 'A':
                            classification = "Asset";
                            break;
                        case 'K':
                            classification = "Expense";
                            break;
                        case 'I':
                            classification = "Inventory";
                            break;
                        default:
                            classification = "-";
                            break;
                    }
                    return classification;
                },
                visible: true
            },
            {
                data: "asset_class",
                name: "asset_class",
                width: "10%",
                render: function(data, type, row) {
                    return row.asset_class;
                },
                visible: true
            },
            {
                data: "gl_account",
                name: "gl_account",
                width: "10%",
                render: function(data, type, row) {
                    return row.gl_account;
                },
                visible: true
            },
            {
                data: "cost_center",
                name: "cost_center",
                render: function(data, type, row) {
                    let cost_center = "";
                    if (row.cost_center) {
                        arr_cost = row.cost_center.split(";");
                        arr_cost_name = row.cost_center_name.split(";");
                        $.each(arr_cost, function(i, v) {
                            cost_center += "<span class='pl-3'>" + (i + 1) + ". [" + v + "] - " + arr_cost_name[i] + "</span><br>";
                        });
                    } else {
                        cost_center = " - ";
                    }
                    return cost_center;
                },
                visible: true,
                orderable: false
            },
            {
                data: "deskripsi",
                name: "deskripsi",
                width: "5%",
                render: function(data, type, row) {
                    let cost_center = (row.cost_center) ? row.cost_center : "";
                    let cost_center_name = (row.cost_center_name) ? row.cost_center_name : "";

                    output = "			<div class='btn-group'>";
                    output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
                    output += "				<ul class='dropdown-menu pull-right'>";
                    output += "                 <li><a href='javascript:void(0)' class='set_data' data-id='" + row.MATNR + "' data-desc='" + row.MAKTX + "' data-classification='" + row.classification + "' data-asset_class='" + row.asset_class + "' data-asset_class_desc='" + row.asset_class_desc + "' data-gl_account='" + row.gl_account + "' data-gl_account_desc='" + row.gl_account_desc + "' data-cost_center='" + cost_center + "' data-cost_center_name='" + cost_center_name + "'><i class='fa fa-pencil'></i> Set Data</a></li>";
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

function master_asset_class(elem) {
    let classification = null;

    if ($(elem).hasClass("select2-hidden-accessible"))
        $(elem).select2("destroy");

    $(elem).select2({
        dropdownParent: $('#form-set-data'),
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        maximumSelectionLength: 1,
        ajax: {
            url: baseURL + "plantation/master/get/asset_class",
            dataType: "json",
            delay: 250,
            cache: false,
            data: function(params) {
                // let matnr = $(this).closest(".row-summary").find("input[name='matnr[]']").val();
                let data = {
                    classification: classification,
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page
                };

                return data;
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function(xhr, status, error) {
                if (xhr.statusText != "abort"){
                    let errorMessage = xhr.status + ': ' + xhr.statusText;
                    swal('Error', `Server Error, (${errorMessage})`, 'error');
                }
            },
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
            if (repo.loading) return repo.text;
            return `<div class="clearfix">[${repo.id}] ${repo.TXK50}</div>`;
        },
        templateSelection: function(repo) {
            let markup = "Silahkan Pilih";
            if (repo.text && repo.id) return repo.text;
            if (repo.TXK50)
                markup = `[${repo.id}] ${repo.TXK50}`;

            return markup;
        }
    });
}

function master_cost_center(elem) {
    let classification = null;

    if ($(elem).hasClass("select2-hidden-accessible"))
        $(elem).select2("destroy");

    $(elem).select2({
        dropdownParent: $('#form-set-data'),
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        // maximumSelectionLength: 1,
        ajax: {
            url: baseURL + "plantation/master/get/cost_center",
            dataType: "json",
            delay: 250,
            cache: false,
            data: function(params) {
                // let matnr = $(this).closest(".row-summary").find("input[name='matnr[]']").val();
                let data = {
                    type: 'cost_center',
                    master: true,
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page
                };

                return data;
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function(xhr, status, error) {
                if (xhr.statusText != "abort"){
                    let errorMessage = xhr.status + ': ' + xhr.statusText;
                    swal('Error', `Server Error, (${errorMessage})`, 'error');
                }
            },
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
            if (repo.loading) return repo.text;
            return `<div class="clearfix">[${repo.id}] ${repo.KTEXT}</div>`;
        },
        templateSelection: function(repo) {
            let markup = "Silahkan Pilih";
            if (repo.text && repo.id) return repo.text;
            if (repo.KTEXT)
                markup = `[${repo.id}] ${repo.KTEXT}`;

            return markup;
        }
    });
}

function master_gl_account(elem) {
    let classification = null;

    if ($(elem).hasClass("select2-hidden-accessible"))
        $(elem).select2("destroy");

    $(elem).select2({
        dropdownParent: $('#form-set-data'),
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        maximumSelectionLength: 1,
        ajax: {
            url: baseURL + "plantation/master/get/gl_account",
            dataType: "json",
            delay: 250,
            cache: false,
            data: function(params) {
                // let matnr = $(this).closest(".row-summary").find("input[name='matnr[]']").val();
                let data = {
                    classification: classification,
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page
                };

                return data;
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function(xhr, status, error) {
                if (xhr.statusText != "abort"){
                    let errorMessage = xhr.status + ': ' + xhr.statusText;
                    swal('Error', `Server Error, (${errorMessage})`, 'error');
                }
            },
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
            if (repo.loading) return repo.text;
            return `<div class="clearfix">[${repo.id}] ${repo.GLTXT}</div>`;
        },
        templateSelection: function(repo) {
            let markup = "Silahkan Pilih";
            if (repo.text && repo.id) return repo.text;
            if (repo.GLTXT)
                markup = `[${repo.id}] ${repo.GLTXT}`;

            return markup;
        }
    });
}