let dataLayer = [];

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
            tipe: 'random'
        },
        success: function (result) {
            //data layer
            $.each(result.data_layer, function (i, layer) {
                const layerIndex = i;

                dataLayer.push({
                    layerKe: layer.layer_ke,
                    idLayout: layer.id_layout,
                    namaLayout: layer.nama_layout,
                    fileLayout: (layer.image_layout ? baseURL + 'assets/' + layer.image_layout : null),
                    layoutSesuai: layer.layout_sesuai,
                    dataBales: []
                });

                const arr_bales = result.data_bales.filter(
                    (obj) => obj.layer_ke == layer.layer_ke
                );
                // masukkan data bales ke data layer
                arr_bales.forEach((bales) => {
                    const detail_bales = {
                        id: bales.id_parameter,
                        value: bales.jumlah,
                        nama: bales.nama_parameter,
                        satuan: bales.satuan,
                    };
                    const dataBalesIndex = dataLayer[layerIndex].dataBales.findIndex(
                        (obj) => obj.balesKe == bales.bales_ke
                    );
                    if (dataBalesIndex >= 0) {
                        dataLayer[layerIndex].dataBales[dataBalesIndex].detail.push(detail_bales);
                    } else {
                        dataLayer[layerIndex].dataBales.push({
                            balesKe: bales.bales_ke,
                            detail: [detail_bales],
                        });
                    }
                })
            });
            generate_data_layer();
        }
    });
}

function generate_data_layer() {
    $.each(dataLayer, function (i, v) {
        let output = '<div class="row">';
        output += ' <div class="col-sm-12">';
        output += '     <div class="box box-default box-solid collapsed-box">';
        output += '         <div class="box-header with-border">';
        output += '             <h4 class="box-title">Layer Ke-' + v.layerKe + '</h4>';
        output += '             <div class="box-tools pull-right">';
        output += '                 <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>';
        output += '             </div>';
        output += '         </div>';
        output += '         <div class="box-body">';
        output += '             <div class="row">';
        output += '                 <div class="col-sm-3">';
        output += '                     <strong>Gambar Layout:</strong><br>';
        output += '                     ' + v.namaLayout + '<br>' + (v.layoutSesuai == 1 ? 'Sesuai' : 'Tidak Sesuai');
        output += '                 </div>';
        output += '                 <div class="col-sm-3">';
        output += '                     <img class="img-responsive" src="' + v.fileLayout + '" alt="photo" style="max-width: 100%; max-height:500px;">';
        output += '                 </div>';
        output += '             </div>';
        output += '             <br>';
        output += '             <strong>Data Bales:</strong><br>';
        output += '             <div id="data-layer-' + v.layerKe + '"></div>';
        output += '         </div>';
        output += '     </div>';
        output += ' </div>';
        output += '</div>';

        $("#tab_layer").append(output);
        generate_data_bales('#data-layer-' + v.layerKe, v.layerKe, v.dataBales)
    });
}

function generate_data_bales(elem, layerKe, data) {
    $.each(data, function (i, v) {
        let output = '<div class="box-group" id="list-bales-' + layerKe + '">';
        output += '  <div class="panel box box-default">';
        output += '      <div class="box-header with-border">';
        output += '          <h4 class="box-title">';
        output += '              <a data-toggle="collapse" data-parent="#list-bales-' + layerKe + '" href="#data-bales-' + layerKe + '-' + v.balesKe + '">Bales Ke-' + v.balesKe + '</a>';
        output += '          </h4>';
        output += '      </div>';
        output += '      <div id="data-bales-' + layerKe + '-' + v.balesKe + '" class="panel-collapse collapse">';
        output += '          <div class="box-body">';
        //detail bales
        output += '             <table class="table">';
        output += '                 <tr>';
        output += '                     <th>Parameter</th>';
        output += '                     <th>Jumlah</th>';
        output += '                     <th>Satuan</th>';
        output += '                 </tr>';
        $.each(v.detail, function (i, val) {
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

        $(elem).append(output);
    });
}