$(document).ready(function () {
    get_data_ppb();

    $(document).on("click", ".btn_upload", function () {
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
                    url: baseURL + 'plantation/transaksi/save/ppb',
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
            window.open(baseURL + $(this).data("link"), '_blank');
        } else {
            var overlay = "<label class='err_msg' style='font-size:12px;color:red;'>&nbspFile tidak ditemukan</label>";
            if ($(".err_msg").length > 0) {
                $(".err_msg").remove();
            }
            $(this).closest("td").append(overlay);
        }
    });
});

function get_data_ppb() {
    const id_ppb = $("#id_ppb").val();
    $.ajax({
        url: baseURL + 'plantation/transaksi/get/ppb',
        type: 'POST',
        dataType: 'JSON',
        data: {
            data: 'complete',
            return: 'json',
            id_ppb: id_ppb,
            // tipe_po     : 'HO'
        },
        success: function (data) {
            var output = "";
            var desc = "";
            $.each(data.detail, function (i, v) {
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

                let jumlah_disetujui = (!v.jumlah_disetujui) ? "-" : v.jumlah_disetujui;
                let jumlah_po = (!v.jumlah_po) ? "-" : parseFloat(v.jumlah_po);
                let tipe_po = (!v.tipe_po) ? "-" : v.tipe_po;

                output = '<tr class="row-item">';
                output += '    <td class="text-center">' + tipe_po + '</td>';
                output += '    <td>' + v.kode_barang + '</td>';
                output += '    <td>' + v.nama_barang + '</td>';
                output += '    <td>' + v.deskripsi2 + '</td>';
                output += '    <td>' + classification + '</td>';
                output += '    <td class="text-right angka">' + v.jumlah + '</td>';
                output += '    <td class="text-center">' + v.satuan + '</td>';
                output += '    <td class="text-right angka">' + numberWithCommas(v.harga) + '</td>';
                output += '    <td class="text-right">' + jumlah_disetujui + '</td>';
                output += '    <td class="text-right">' + numberWithCommas(jumlah_po) + '</td>';
                output += '</tr>';

                $(output).appendTo("#table-detail tbody");

            });

        }
    });
}

const export_detail = () => {
    const table = document.getElementById("table-detail");
    let html_element = document.createElement('table');
    html_element.style.width = "100%";

    let data = '';
    for (let i = 0, row; row = table.rows[i]; i++) {
        data += '<tr>';
        data += '   <td style="border: thin solid;">' + (i == 0 ? "No" : i) + '</td>';
        data += '   <td style="border: thin solid;">' + row.cells[1].innerText + '</td>';//Kode Barang
        data += '   <td style="border: thin solid;">' + row.cells[2].innerText + '</td>';//deskripsi 1
        data += '   <td style="border: thin solid;">' + row.cells[3].innerText + '</td>';//deskripsi 2
        data += '   <td style="border: thin solid;">' + row.cells[8].innerText + '</td>';//jumlah disetujui
        data += '   <td style="border: thin solid;">' + row.cells[6].innerText + '</td>';//satuan
        data += '</tr>';
    }
    // data += '</table>';
    html_element.innerHTML = data;

    let filename = "Detail_" + $("#no_ppb").val().replace("/", "-");
    save_html_to_excel(html_element, filename);
}

const save_html_to_excel = (data, filename) => {
    let downloadLink;
    const dataType = 'application/vnd.ms-excel';
    let tableHTML = data.outerHTML.replace(/ /g, '%20');

    // Specify file name
    filename = filename ? filename + '.xls' : 'excel_data.xls';

    // Create download link element
    downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);

    if (navigator.msSaveOrOpenBlob) {
        let blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

        // Setting the file name
        downloadLink.download = filename;

        //triggering the function
        downloadLink.click();
    }
}