let dataBales = [];

$(document).ready(function () {
    get_data_cek();
});

function get_data_cek() {
    const id_cek = $("#id_cek").val();
    $.ajax({
        url: baseURL + 'outspec/transaksi/get/data',
        type: 'POST',
        dataType: 'JSON',
        data: {
            data: 'complete',
            return: 'json',
            id_cek: id_cek,
            tipe: 'permukaan'
        },
        success: function (result) {
            if (result.files) {
                let output = `
                    <strong>Gambar:</strong>
                    <br>
                    <img class="img-responsive" src="${baseURL}assets/${result.files}" alt="photo" style="max-width: 100%; max-height:500px;">
                `;
                $("#attachment-cek").html(output);
                $("#link-tab-attachment").removeClass("hidden");
            }
            //data label
            $.each(result.data_label, function (i, v) {
                let output = '<tr>';
                output += ' <td>' + v.nama_label + '</td>';
                if (v.kondisi_label == 1)
                    output += ' <td><i class="fa fa-check text-green"></i></td>';
                else
                    output += ' <td><i class="fa fa-times text-red"></i></td>';
                output += '</tr>';

                $("#list-label").append(output);
            });
            
            //data bales
            $.each(result.data_bales, function (i, v) {
                const arr_bales = {
                    id: v.id_parameter,
                    value: v.jumlah,
                    nama: v.nama_parameter,
                    satuan: v.satuan,
                };
                const objIndex = dataBales.findIndex(
                    (obj) => obj.balesKe == v.bales_ke
                );
                if (objIndex >= 0) {
                    dataBales[objIndex].data.push(arr_bales);
                } else {
                    dataBales.push({
                        layerKe: v.layer_ke,
                        balesKe: v.bales_ke,
                        data: [arr_bales],
                    });
                }
            });
            generate_data_bales();
        }
    });
}

function generate_data_bales() {
    $.each(dataBales, function (i, v) {
        let output = '<div class="box-group" id="list-bales">';
        output += '  <div class="panel box box-default">';
        output += '      <div class="box-header with-border">';
        output += '          <h4 class="box-title">';
        output += '              <a data-toggle="collapse" data-parent="#list-bales" href="#data-bales-' + v.balesKe + '">Bales Ke-' + v.balesKe + '</a>';
        output += '          </h4>';
        output += '      </div>';
        output += '      <div id="data-bales-' + v.balesKe + '" class="panel-collapse collapse">';
        output += '          <div class="box-body">';
        //detail bales
        output += '             <table class="table">';
        output += '                 <tr>';
        output += '                     <th>Parameter</th>';
        output += '                     <th>Jumlah</th>';
        output += '                     <th>Satuan</th>';
        output += '                 </tr>';
        $.each(v.data, function (i, val) {
            output += '                 <tr>';
            output += '                     <td>' + val.nama + '</td>';
            output += '                     <td>' + val.value + '</td>';
            output += '                     <td>' + val.satuan + '</td>';
            output += '                 </tr>';
        });
        output += '             </table>';
        output += '          </div>';
        output += '      </div>';
        output += '  </div>';
        output += '</div>';

        $("#tab_bales").append(output);
    });
}