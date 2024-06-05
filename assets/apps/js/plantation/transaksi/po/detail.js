$(document).ready(function() {
    get_data_po();

    $(document).on("click", ".btn_upload", function(){
        $("input[name='file']").val('');
        $('#modal_upload').modal('show');
    });

    // save upload
    $(document).on("click", "button[name='action_btn_save']", function (e) {
        var empty_form = validate('.form-upload-attachment');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-upload-attachment")[0]);
                $.ajax({
                    url: baseURL + 'plantation/transaksi/save/attachment_transaksi',
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
                                $('#modal_upload').modal('hide');
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

    $(document).on("click", ".view_file", function () {
		if ($(this).data("link") !== "") {
			window.open(baseURL+$(this).data("link"), '_blank');
		}else{
			// kiranaAlert("notOK", "File Tidak Ditemukan", "warning", "no");
			var overlay = "<label class='err_msg' style='font-size:12px;color:red;'>&nbspFile tidak ditemukan</label>"; 
			if ($(".err_msg").length > 0) {
				$(".err_msg").remove();
			}
			$(this).closest("td").append(overlay);
		}
	});
});

function get_data_po() {
    const id_po		= $("#id_po").val();
    $.ajax({
        url: baseURL+'plantation/transaksi/get/po',
        type: 'POST',
        dataType: 'JSON',
        data: {
            data        : 'complete',
            return      : 'json',
            id_po 	    : id_po,
            tipe_po     : $("[name='tipe_po']").val()
        },
        success: function(data){
            let output 	= "";

            $("[name='ppn']").val(data.ppn);
            $("input[name='nilai_ppn']").val(data.nilai_ppn);

            const elem = $("#vendor");
            master_vendor(elem);
            let control = $(elem).empty().data('select2');
            let adapter = control.dataAdapter;
            let text = `[${data.vendor}] ${data.nama_vendor}`;
            adapter.addOptions(adapter.convertToOptions([{'id': data.vendor, 'text': text}]));
            $(elem).trigger('change');
            
            // var t 	= $('#sspTable').DataTable();
            // t.clear().draw();
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

                const opt_cost_center = create_dropdown_cost_center(v.cost_center+';', v.cost_center_desc+';');

                const total_diskon = parseFloat(v.jumlah * v.diskon);

                output = '<tr class="row-item">';
                output += '    <td><input type="text" class="form-control" name="kode_barang_' + v.no_detail + '" value="' + v.kode_barang + '" readonly></td>';
                output += '    <td><input type="text" class="form-control" value="' + v.nama_barang + '" readonly></td>';
                output += '    <td><input type="text" class="form-control" value="' + v.no_ppb + '" readonly></td>';
                output += '    <td><input type="text" class="form-control" name="tipe_barang_' + v.no_detail + '" value="' + classification + '" readonly></td>';
                output += '    <td><input type="text" class="form-control" name="asset_class_barang_' + v.no_detail + '" value="' + asset_class + '" readonly></td>';
                output += '    <td><input type="text" class="form-control" name="gl_account_barang_' + v.no_detail + '" value="' + gl_account + '" readonly></td>';
                output += '    <td><select class="form-control item-input" name="cost_center_barang_' + v.no_detail + '" disabled>' + opt_cost_center + '</select></td>';
                // output += '    <td><input type="text" class="form-control text-right" value="' + v.jumlah_disetujui + '" readonly></td>';
                output += '    <td><input type="text" class="form-control item-input text-right angka" name="jumlah_barang_' + v.no_detail + '" value="' + v.jumlah + '" readonly></td>';
                output += '    <td><input type="text" class="form-control text-center" name="satuan_barang_' + v.no_detail + '" value="' + v.satuan + '" readonly></td>';
                output += '    <td><input type="text" class="form-control item-input text-right angka" name="harga_barang_' + v.no_detail + '" value="' + numberWithCommas(v.harga) + '" readonly></td>';
                output += '    <td><input type="text" class="form-control item-input text-right angka" name="diskon_barang_' + v.no_detail + '" value="' + numberWithCommas(parseFloat(v.diskon)) + '" readonly></td>';
                output += '    <td><input type="text" class="form-control item-input text-right" name="total_barang_' + v.no_detail + '" value="' + numberWithCommas(v.total) + '" readonly></td>';
                output += '</tr>';

                $(output).appendTo("#table-detail tbody");

                $("[name=cost_center_barang_" + v.no_detail + "]").select2({
                    allowClear: true,
                    placeholder: "Pilih Cost Center"
                });

                $("[name=cost_center_barang_" + v.no_detail + "]").val(v.cost_center).trigger('change');
            });
                
        
        }
    }).done(function() {
        generate_summary_total();
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