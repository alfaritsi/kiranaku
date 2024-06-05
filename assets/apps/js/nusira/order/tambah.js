$(document).ready(function() {
    get_katalog(1, "order");

    $(document).on("click", "button[name='action_btn']", function() {
        var id = parseInt($(this).val());
        var btn = $(this).data("btn");
        if (id) {
            $(".row-form").hide();
            $("button[name='action_btn']").hide();
            $("button[name='action_btn'].btn-form" + id).removeClass("hidden");
            $("button[name='action_btn'].btn-form" + id).show();

            var len = $(".row-form").length;
            var elem = $(".row-form." + id);
            elem.removeClass("hidden");
            elem.show();

            switch (btn) {
                case 'next':
                    if ((id + 1) > len) {
                        var id_next = id;
                        var id_back = id - 1;
                    } else {
                        var id_next = id + 1;
                        var id_back = id - 1;
                    }
                    $("button[name='action_btn'].back").val(id_back);
                    $("button[name='action_btn'].next").val(id_next);
                    break;
                case 'back':
                    if ((id - 1) <= 0) {
                        var id_next = 2;
                        var id_back = 1;
                    } else {
                        var id_next = id + 1;
                        var id_back = id - 1;
                    }
                    $("button[name='action_btn'].back").val(id_back);
                    $("button[name='action_btn'].next").val(id_next);
                    break;
                case 'submit':
                    if ($(".table-summary .row-summary").length > 0) {
                        $("input[name='action']").val($(this).attr("data-btn").toLowerCase());
                        submit_order();
                    } else {
                        kiranaAlert("notOK", "List order masih kosong", "error", "no");
                    }
                    break;
            }

            if (id_back > 0 && id_next < (len + 1)) {
                $("button[name='action_btn']").hide();
                $("button[name='action_btn'].btn-form" + id).removeClass("hidden");
                $("button[name='action_btn'].btn-form" + id).show();
            }
        }
    });

    $(document).on("click", ".request_item", function(e) {
        var button = $(this);
        var idx = ($("tr.row-summary").length > 0 ? ($("tr.row-summary").length - 1) : 0);
        $.ajax({
            url: baseURL + 'nusira/order/get/material',
            type: 'POST',
            dataType: 'JSON',
            data: {
                kode: $(this).data("kode"),
                itnum: $(this).data("num"),
                no_pi: $("input[name='no_pi']").val()
            },
            beforeSend: function() {
                var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                $("body .overlay-wrapper").append(overlay);
            },
            success: function(data) {
                if (data) {
                    var output = '';
                    console.log(button.data("id"));
                    if ($("#nodata").length > 0) {
                        $("#nodata").remove();
                        generate_ongkir(data);
                        $("#ongkir .select2").select2();
                        generate_select2_budget('#ongkir');
                    }

                    $.each(data, function(i, v) {
                        output += '<tr class="row-summary summary' + button.data("id") + '">';
                        output += '	<td>';
                        output += '		<input type="text" class="form-control hidden" name="itnum[]" value="' + v.ITNUM + '"/>';
                        output += '		<input type="text" class="form-control hidden" name="matnr[]" value="' + v.MATNR + '"/>';
                        output += '		<input type="text" class="form-control hidden" name="kdmat[]" value="' + v.KDMAT + '"/>';
                        output += '		<textarea class="form-control" name="permin[]" style="resize:vertical"  rows="5" readonly="readonly">' + '[' + v.MATNR + ' | ' + v.KDMAT + '] - ' + v.MAKTX + '</textarea>';
                        output += '	</td>';
                        output += '	<td><textarea class="form-control" name="spes[]" style="resize:vertical"  rows="5" readonly="readonly">' + (v.spesifikasi ? v.spesifikasi.replace(/<br\s*[\/]?>/gi, "\r\n") : '') + '</textarea></td>';
                        output += '	<td>';
                        output += '		<select class="form-control select2" name="tipe_pi[]" required="required">';
                        output += '			<option value="budgeted">Budgeted</option>';
                        output += '			<option value="unbudgeted">Unbudgeted</option>';
                        output += '		</select>';
                        output += '	</td>';
                        output += '	<td><select class="form-control select2 budget_select" name="budget_select_' + idx + '[]" multiple="multiple"></select></td>';
                        output += '	<td><input type="text" class="form-control text-right angka" required="required" name="jml[]" value="0"/></td>';
                        output += '	<td><input type="text" class="form-control text-center" name="satuan[]" value="' + v.MEINS + '" readonly="readonly"/></td>';
                        output += '	<td>';
                        output += '		<div class="input-group">';
                        output += '			<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
                        output += '			<input type="text" class="form-control kiranadatepicker" data-autoclose="true" name="req_deliver_date[]" required="required"/>';
                        output += ' 	</div>';
                        output += ' </td>';
                        output += '	<td>';
                        output += '		<div class="input-group">';
                        output += '			<div class="input-group-addon">Rp</div>';
                        output += '			<input type="text" class="form-control text-right" name="hrg[]" value="' + v.harga_money + '" readonly="readonly"/>';
                        output += ' 	</div>';
                        output += ' </td>';
                        output += '	<td>';
                        output += '		<div class="input-group">';
                        output += '			<div class="input-group-addon">Rp</div>';
                        output += '			<input type="text" class="form-control text-right" name="total[]" value="0" readonly="readonly"/>';
                        output += ' 	</div>';
                        output += ' </td>';
                        output += '	<td class="text-center"><button type="button" class="btn btn-sm btn-danger remove_item" data-product="' + button.data("id") + '" title="Remove"><i class="fa fa-trash-o"></i></button></td>';
                        output += '</tr>';
                        // $(".table-summary").append(output);
                        $(output).insertBefore("#ongkir");
                    });

                    button.prop("disabled", "disabled");
                    $("#" + button.attr("data-id") + " .request_item").prop("disabled", "disabled");
                    $("#" + button.attr("data-id") + " .detail_item").attr("data-status", "unavailable");
                    $("#" + button.attr("data-id")).css("background-color", "rgba(0, 141, 76, 0.3)");

                    $(".request_item[data-kode='" + button.attr("data-kode") + "'][data-num='" + button.attr("data-num") + "']").prop("disabled", "disabled");
                    $(".detail_item[data-kode='" + button.attr("data-kode") + "'][data-num='" + button.attr("data-num") + "']").attr("data-status", "unavailable");
                    $(".request_item[data-kode='" + button.attr("data-kode") + "'][data-num='" + button.attr("data-num") + "']").closest(".product-thumb").css("background-color", "rgba(0, 141, 76, 0.3)");
                }
            },
            error: function() {
                $("body .overlay-wrapper .overlay").remove();
                kiranaAlert("notOK", "Server Error", "error", "no");
            },
            complete: function() {
                $(".row-summary.summary" + button.data("id") + " .select2").select2();
                generate_select2_budget('.summary' + button.data("id"));
                $("body .overlay-wrapper .overlay").remove();
                $(".row-summary .kiranadatepicker").each(function() {
                    $(".row-summary .kiranadatepicker").datepicker({
                        todayHighlight: true,
                        disableTouchKeyboard: true,
                        format: ($(this).data("format") != null ? $(this).data("format") : "dd.mm.yyyy"),
                        startView: ($(this).data("startview") != null ? $(this).data("startview") : "days"),
                        minViewMode: ($(this).data("minviewmode") != null ? $(this).data("minviewmode") : "days"),
                        autoclose: ($(this).data("autoclose") != null ? $(this).data("autoclose") : false)
                    });
                });

                var idx = ($("tr.row-summary").length > 0 ? ($("tr.row-summary").length - 1) : 0);
                $("#ongkir .budget_select").attr('name', 'budget_select_' + idx + '[]');
            }
        });
    });

    $(document).on("click", ".remove_item", function(e) {
        $(".summary" + $(this).data("product")).remove();
        $("#" + $(this).data("product") + " .request_item").removeAttr("disabled");
        $("#" + $(this).data("product") + " .detail_item").attr("data-status", "available");
        $("#" + $(this).data("product")).css("background-color", "");
        generate_summary_total();

        var idx = ($("tr.row-summary").length) - 1;
        $("#ongkir .budget_select").attr('name', 'budget_select_' + idx + '[]');

        if ($(".table-summary tbody tr").length == 0)
            $(".table-summary tbody").append('<tr id="nodata"><td colspan="9">No data found</td><td></td></tr>');
    });

    $(document).on("change", "input[name='jml[]']", function() {
        if ($(this).val().replace(/,/g, "") < 0)
            $(this).val(0);

        var row = $(this).closest(".row-summary");
        var harga = row.find("input[name='hrg[]']").val().replace(/,/g, "");
        row.find("input[name='total[]']").val(numberWithCommas($(this).val().replace(/,/g, "") * harga));

        generate_summary_total();
    });

    $(document).on("change", ".budget_select", function(e) {
        generate_summary_total();
    });

    $(document).on('select2:select', ".budget_select", function(e) {
        var id = e.params.data.id;
        var option = $(e.target).children('[value="' + id + '"]');
        option.detach();
        $(e.target).append(option).change();

        get_available_budget_to_select($(this));
    });
});

function generate_summary_total() {
    var total = 0;
    $("input[name='total[]']").each(function(i) {
        total += +$(this).val().replace(/,/g, "");
    });
    $(".summary_total").val(numberWithCommas(total));

    var avbudget = 0;
    $(".budget_select").each(function(e) {
        var data = $(this).select2('data');
        if (data.length > 0) {
            $.each(data, function(i, v) {
                avbudget += v.remaining * 1;
            });
        }
    });
    $(".summary_budget").val(numberWithCommas(avbudget));

    var selisih = avbudget - total >= 0 ? numberWithCommas(avbudget - total) : "(" + numberWithCommas(Math.abs(avbudget - total)) + ")";
    $(".summary_selisih").val(numberWithCommas(selisih));
}

function submit_order() {
    var empty_form = validate();
    if (empty_form == 0) {
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            var formData = new FormData($(".form-tambah-order")[0]);
            $.ajax({
                url: baseURL + 'nusira/order/save/order',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                    $("body .overlay-wrapper").append(overlay);
                },
                success: function(data) {
                    if (data.sts == 'OK') {
                        window.open(data.link, '_blank').focus();
                        kiranaAlert(data.sts, data.msg);
                    } else {
                        kiranaAlert(data.sts, data.msg, "error", "no");
                        $("input[name='isproses']").val(0);
                    }
                },
                error: function() {
                    $("body .overlay-wrapper .overlay").remove();
                    $("input[name='isproses']").val(0);
                    kiranaAlert("notOK", "Server Error", "error", "no");
                },
                complete: function() {
                    $("body .overlay-wrapper .overlay").remove();
                }
            });
        } else {
            kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
        }
    }
}

function generate_select2_budget(elem) {
    $(elem + " .budget_select").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'nusira/order/get/budget',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                    tipe_pi: $(this).closest(".row-summary").find("select[name='tipe_pi[]']").val(),
                    is_main: $(this).val().length == 0 ? 'yes' : 'no',
                    nomor_budget: $(this).val(),
                    not_in: function() {
                        var no_budget = [];
                        $(".budget_select").each(function() {
                            if ($(this).val().length > 0) {
                                $.merge(no_budget, $(this).val());
                            }
                        });
                        return no_budget;
                    }
                };
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: false
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: function(repo) {
            if (repo.loading) return repo.text;
            var markup = '<div class="clearfix">' + repo.no_budget + ' || ' + repo.investasi + ' || ' + repo.kategori + ' || ' + repo.remaining_money + ' || ' + repo.budget_money + '</div>';
            return markup;
        },
        templateSelection: function(repo) {
            if (repo.investasi && repo.kategori) {
                return repo.no_budget + ' || ' + repo.investasi + ' || ' + repo.kategori + ' || ' + repo.remaining_money + ' || ' + repo.budget_money;
            } else {
                return repo.text;
            }
        }
    });
}

function get_available_budget_to_select(elem) {
    var tipe = elem.closest("tr.row-summary").find("select[name='tipe_pi[]']").val();
    $.ajax({
        url: baseURL + 'nusira/order/get/count_budget',
        type: 'POST',
        dataType: 'JSON',
        data: {
            tipe_pi: tipe
        },
        success: function(data) {
            $("input[name='budget_available_to_select']").val(data.length);
            if (tipe === "unbudgeted" && data.length == 0) {
                elem.removeAttr("required");
            } else {
                elem.attr("required", "required");
            }
        },
        error: function() {
            kiranaAlert("notOK", "Server Error", "error", "no");
        }
    });
}

function generate_ongkir(data) {
    var pabrik = $("input[name='no_pi']").val().split('/')[2];
    var idx = ($("tr.row-summary").length > 0 ? ($("tr.row-summary").length - 1) : 0);
    var ongkir = (data && data[0].ongkir[0] ? data[0].ongkir[0].ongkir_money : parseFloat("1000000.00").toFixed(2));

    var output = '';
    output += '<tr class="row-summary summary" id="ongkir">';
    output += '	<td>';
    output += '		<input type="text" class="form-control hidden" name="itnum[]" value=""/>';
    output += '		<input type="text" class="form-control hidden" name="matnr[]" value=""/>';
    output += '		<input type="text" class="form-control hidden" name="kdmat[]" value=""/>';
    output += '		<textarea class="form-control" name="permin[]" style="resize:vertical"  rows="5" readonly="readonly">Biaya pengiriman dari NSI ke ' + pabrik + '</textarea>';
    output += '	</td>';
    output += '	<td><textarea class="form-control" name="spes[]" style="resize:vertical"  rows="5" readonly="readonly">Biaya pengiriman dari NSI ke ' + pabrik + '</textarea></td>';
    output += '	<td>';
    output += '		<select class="form-control select2" name="tipe_pi[]" required="required" >';
    output += '			<option value="budgeted" >Budgeted</option>';
    output += '			<option value="unbudgeted">Unbudgeted</option>';
    output += '		</select>';
    output += '	</td>';
    output += '	<td><select class="form-control select2 budget_select" name="budget_select_' + idx + '[]" multiple="multiple"></select></td>';
    output += '	<td><input type="text" class="form-control text-right angka" required="required" name="jml[]" value="1" readonly="readonly"/></td>';
    output += '	<td><input type="text" class="form-control text-center" name="satuan[]" value="AU" readonly="readonly"/></td>';
    output += '	<td>';
    output += '		<div class="input-group">';
    output += '			<div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
    output += '			<input type="text" class="form-control" name="req_deliver_date[]" readonly="readonly"/>';
    output += ' 	</div>';
    output += ' </td>';
    output += '	<td>';
    output += '		<div class="input-group">';
    output += '			<div class="input-group-addon">Rp</div>';
    output += '			<input type="text" class="form-control text-right" name="hrg[]" value="' + numberWithCommas(ongkir) + '" readonly="readonly"/>';
    output += ' 	</div>';
    output += ' </td>';
    output += '	<td>';
    output += '		<div class="input-group">';
    output += '			<div class="input-group-addon">Rp</div>';
    output += '			<input type="text" class="form-control text-right" name="total[]" value="' + numberWithCommas(ongkir) + '" readonly="readonly"/>';
    output += ' 	</div>';
    output += ' </td>';
    output += '	<td class="text-center"></td>';
    output += '</tr>';
    $(".table-summary").append(output);
}