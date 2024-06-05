$(document).ready(function() {
    get_data_ppb();
    master_vendor($("#vendor"));

    $(document).on("change", ".item-check", function(){
        const isChecked = $(this).prop('checked');
        const row = $(this).closest(".row-item");
        if (isChecked) {
            row.find(".item-input").not("input[name^='diskon_barang_']").attr('required', true);
            row.find("input.item-input").attr('readonly', false);
            row.find("select.item-input").attr('disabled', false);
            const classification = row.find("input[name^='classification_barang_']").val();
            if (["I"].includes(classification)) {
                row.find("select[name^='cost_center_barang_']").attr('required', false);
                row.find("select[name^='cost_center_barang_']").attr('disabled', true);
            }
        } else {
            row.find("input.item-input").val("");
            row.find("select.item-input").val('').trigger("change");
            row.find("input[name^='total_barang_']").val("");
            row.find(".item-input").attr('required', false);
            row.find("input.item-input").attr('readonly', true);
            row.find("select.item-input").attr('disabled', true);
        }
        generate_check_all();
        generate_summary_total();
    });

    $(document).on("change", "#ppn", function(){
        const ppn = $(this).val();
        if (ppn == "B5") $("input[name='nilai_ppn']").val(10);
        else if (ppn == "BK") $("input[name='nilai_ppn']").val(11);
        else $("input[name='nilai_ppn']").val(0);
        generate_summary_total();
    });

    //check all
    $(document).on("change", "#checkall", function(){
        const isChecked = $(this).prop('checked');
        $(".item-check").prop("checked", isChecked).trigger("change");
    });

    //submit
    $(document).on("click", "button[name='action_btn']", function(){
        if ($(".item-check:checkbox:checked").length > 0) {
            $("input[name='action']").val($(this).attr("data-btn").toLowerCase());
            const cek_tipe = validasi_tipe_barang();
            if (cek_tipe > 1)
                swal('Error', 'Hanya Boleh Pilih Satu Tipe Barang Dalam 1 PO', 'error');
            else
                submit_po();
        } else {
            swal('Error', 'Tidak Ada item yang dipilih', 'error');
        }
    });

    $(document).on("change", "input[name^='jumlah_barang_'], input[name^='harga_barang_'], input[name^='diskon_barang_']", function() {
        if ($(this).val().replace(/,/g, "") < 0)
            $(this).val(0);

        let row = $(this).closest(".row-item");
        let jumlah = row.find("input[name^='jumlah_barang_']").val().replace(/,/g, "");
        let harga = row.find("input[name^='harga_barang_']").val().replace(/,/g, "");
        let diskon = row.find("input[name^='diskon_barang_']").val().replace(/,/g, "");
        // let total = parseFloat(jumlah * (harga - (harga * diskon / 100))).toFixed(2);
        // let total = parseFloat((jumlah * harga) - diskon).toFixed(2);
        // let total_diskon = parseFloat(jumlah * diskon).toFixed(2);
        let total = parseFloat((jumlah * harga) - diskon).toFixed(2);
        // row.find("input[name^='total_diskon_barang_']").val(numberWithCommas(total_diskon));
        row.find("input[name^='total_barang_']").val(numberWithCommas(total));

        
        generate_summary_total();
    });

    $(document).on("change", "input[name='total_diskon']", function() {
        generate_summary_total();
    });
});

function generate_check_all() {
    const jumlah_all_checkbox = $(".item-check:checkbox").length;
    const jumlah_checked = $(".item-check:checkbox:checked").length;
    const isChecked = (jumlah_all_checkbox == jumlah_checked);
    $("#checkall").prop('checked', isChecked);
}

function get_data_ppb() {
    const id_ppb		= $("#id_ppb").val();
    $.ajax({
        url: baseURL+'plantation/transaksi/get/ppb',
        type: 'POST',
        dataType: 'JSON',
        data: {
            data        : 'complete',
            return      : 'json',
            id_ppb 	    : id_ppb,
            tipe_po     : 'HO'
        },
        success: function(data){
            var output 	= "";
            var desc	= "";
            $.each(data.detail, function(i,v){
                let classification = "";
                switch (v.classification) {
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
                        classification = "";
                        break;
                }
                
                const asset_class = (v.asset_class) ? v.asset_class : "";
                const gl_account = (v.gl_account) ? v.gl_account : "";

                const opt_cost_center = create_dropdown_cost_center(v.cost_center, v.cost_center_name);

                let checkbox = '<input type="checkbox" class="item-check" name="item_ppb[]" value="' + v.no_detail + '">';

                if (v.jumlah_po > 0 && v.jumlah_po == v.jumlah_disetujui) {
                    checkbox = "";
                }

                output = '<tr class="row-item">';
                output += '    <td style="text-align: center; vertical-align: middle;">' + checkbox + '</td>';
                output += '    <td><input type="text" class="form-control" name="kode_barang_' + v.no_detail + '" value="' + v.kode_barang + '" readonly></td>';
                output += '    <td><input type="text" class="form-control" value="' + v.nama_barang + '" readonly></td>';
                output += '    <td><input type="text" class="form-control" value="' + v.deskripsi2 + '" readonly></td>';
                output += '    <td><input type="text" class="form-control" name="tipe_barang_' + v.no_detail + '" value="' + classification + '" readonly><input type="hidden" name="classification_barang_' + v.no_detail + '" value="' + v.classification + '"></td>';
                output += '    <td><input type="text" class="form-control" name="asset_class_barang_' + v.no_detail + '" value="' + asset_class + '" readonly></td>';
                output += '    <td><input type="text" class="form-control" name="gl_account_barang_' + v.no_detail + '" value="' + gl_account + '" readonly></td>';
                output += '    <td><select class="form-control item-input" name="cost_center_barang_' + v.no_detail + '" disabled>' + opt_cost_center + '</select></td>';
                output += '    <td><input type="text" class="form-control text-right" value="' + v.jumlah_disetujui + '" readonly></td>';
                output += '    <td><input type="text" class="form-control item-input text-right angka" name="jumlah_barang_' + v.no_detail + '" max="' + (v.jumlah_disetujui - v.jumlah_po) + '" readonly></td>';
                output += '    <td><input type="text" class="form-control text-center" name="satuan_barang_' + v.no_detail + '" value="' + v.satuan + '" readonly></td>';
                output += '    <td><input type="text" class="form-control item-input text-right angka" name="harga_barang_' + v.no_detail + '" readonly></td>';
                output += '    <td><input type="text" class="form-control item-input text-right angka"  name="diskon_barang_' + v.no_detail + '" readonly></td>';
                output += '    <td><input type="text" class="form-control text-right" name="total_barang_' + v.no_detail + '"  readonly></td>';
                output += '</tr>';

                $(output).appendTo("#table-detail tbody");

                $("[name=cost_center_barang_" + v.no_detail + "]").select2({
                    allowClear: true,
                    placeholder: "Pilih Cost Center"
                });
            });
        
        }
    });
}

function generate_summary_total(){
    let total = 0;
    $("#form-createpoho input[name^='total_barang_']").each(function(i) {
        total += +$(this).val().replace(/,/g, "");
    });
    $("input[name='subtotal']").val(numberWithCommas(total.toFixed(2)));
    
    generate_diskon_total();
    // let diskon = $("#total_diskon").val().replace(/,/g, "");

    hitung_ppn();
    let ppn = parseFloat($("#total_ppn").val().replace(/,/g, ""));

    total = (total + ppn).toFixed(2);
    $("input[name='summary_item']").val(numberWithCommas(total));
}

function generate_diskon_total(){
    let total = 0;
    $("#form-createpoho input[name^='diskon_barang_']").each(function(i) {
        total += +$(this).val().replace(/,/g, "");
    });
    $("input[name='total_diskon']").val(numberWithCommas(total.toFixed(2)));
}

function hitung_ppn() {
    const subtotal = $("#subtotal").val().replace(/,/g, "");
    // let diskon = $("#total_diskon").val().replace(/,/g, "");
    let ppn = $("input[name='nilai_ppn']").val();
    // const total_ppn = (subtotal-diskon) * ppn / 100;
    const total_ppn = subtotal * ppn / 100;
    $("input[name='total_ppn']").val(numberWithCommas(total_ppn.toFixed(2)));
}

function master_vendor(elem) {
    let classification = null;

    if ($(elem).hasClass("select2-hidden-accessible"))
        $(elem).select2("destroy");

    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        maximumSelectionLength: 1,
        ajax: {
            url: baseURL + "plantation/master/get/vendor",
            dataType: "json",
            delay: 250,
            cache: false,
            data: function(params) {
                // let matnr = $(this).closest(".row-summary").find("input[name='matnr[]']").val();
                let data = {
                    plant: $('[name=plant]').val(),
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
            return `<div class="clearfix">[${repo.id}] ${repo.NAME1}</div>`;
        },
        templateSelection: function(repo) {
            let markup = "Silahkan Pilih";
            if (repo.text && repo.id) return repo.text;
            if (repo.NAME1)
                markup = `[${repo.id}] ${repo.NAME1}`;

            return markup;
        }
    });
}

function create_dropdown_cost_center(cost_center, cost_center_desc) {
    let output = '<option value=""></option>';
    if (cost_center && cost_center !== "") {
        cost_center_desc = cost_center_desc.split(";");
        $.each(cost_center.split(";").filter(item => item), function(i, v) {
            output += '<option value="' + v + '">[' + v + '] - ' + cost_center_desc[i] + '</option>';
        });
    }

    return output;
}

function validasi_tipe_barang() {
    /*cek jumlah tipe barang dari item yg dipilih*/
    let tipe_barang = [];
    $(".item-check:checkbox:checked").each(function(i) {
        const row = $(this).closest(".row-item");
        const tipe = row.find("input[name^='classification_barang_']").val();
        tipe_barang.push(tipe);
    });

    return new Set(tipe_barang).size;
}

function submit_po() {
    const empty_form = validate('#form-createpoho');
    if (empty_form == 0) {
        const isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            var formData = new FormData($("#form-createpoho")[0]);
            $.ajax({
                url: baseURL + 'plantation/transaksi/save/po_ho',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.sts == 'OK') {
                        swal('Success', data.msg, 'success').then(function () {
                            location.href = baseURL + 'plantation/transaksi/data/po';
                        });
                        // myAlert({
                        //     icon: "success",
                        //     html: data.msg,
                        //     reload: baseURL + 'plantation/transaksi/data/po'
                        // });
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
    // e.preventDefault();
    return false;
}