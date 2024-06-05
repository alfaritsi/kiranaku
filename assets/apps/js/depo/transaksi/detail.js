/*
@application    : MASTER DEPO
@author         : Lukman Hakim(7143)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

function previewFile(id_foto) {
  const preview = document.querySelector('#id_foto'+id_foto);
  const file = document.querySelector('#view_foto'+id_foto).files[0];
  const reader = new FileReader();

  reader.addEventListener("load", function () {
    // convert image file to base64 string
    preview.src = reader.result;
  }, false);

  if (file) {
    reader.readAsDataURL(file);
  }
}

$(document).ready(function () {
    $.ajax({
        url: baseURL + "depo/transaksi/get/data",
        type: "POST",
        dataType: "JSON",
        data: {
            nomor: $("#nomor").val(),
            return: "json",
            data: "complete",
        },
        beforeSend: function () { },
        success: function (data) {
			// console.log(data);
			$.each(data, function (i, v) {
				//header-supplier
				$("input[name='id_depo_master']").val(v.id_depo_master);
				$("input[name='kode_sj']").val(v.kode_sj);
				$("select[name='propinsi']").val(v.propinsi).trigger("change.select2");
				//load kabupaten
				var output = '';
				output += '<option></option>';
				$.each(v.arr_kabupaten, function(x, y) {
					var selected = (y.id_kabupaten == v.kabupaten ? 'selected' : '');
					output += '<option value="' + y.id_kabupaten + '" ' + selected + '>' + y.nama_kabupaten + '</option>';
				});
				// $("select[name='kabupaten']").html(output).select2();
				$('#kabupaten').html(output);
				$("input[name='jenis_depo']").val(v.jenis_depo);
				$("input[name='pabrik']").val(v.pabrik);
				$("select[name='jenis_depo']").val(v.jenis_depo).trigger("change.select2");
				$("select[name='pabrik']").val(v.pabrik).trigger("change.select2");
				$("input[name='nomor']").val(v.nomor);
				$("input[name='status_akhir']").val(v.status);
				$("input[name='level']").val(v.level);
				$("input[name='nama']").val(v.nama);
				$("input[name='nip']").val(v.nip);
				$("input[name='npwp']").val(v.npwp);
				$("textarea[name='alamat_rumah']").val(v.alamat_rumah);
				$("textarea[name='alamat_depo']").val(v.alamat_depo);
				$("input[name='gps_depo']").val(v.gps_depo);
				$("input[name='pekerjaan']").val(v.pekerjaan);
				$("select[name='status_kepemilikan_tanah']").val(v.status_kepemilikan_tanah).trigger("change.select2");
				$("select[name='status_sertifikat_tanah']").val(v.status_sertifikat_tanah).trigger("change.select2");
				$("select[name='dana_pembelian_bokar']").val(v.dana_pembelian_bokar).trigger("change.select2");
				$("select[name='rekomendasi_oleh']").val(v.rekomendasi_oleh).trigger("change.select2");
				//header-lingkungan
				$("input[name='luas_gudang']").val(v.luas_gudang);
				$("input[name='luas_tanah']").val(v.luas_tanah);
				$("select[name='koneksi_internet']").val(v.koneksi_internet).trigger("change.select2");
				$("select[name='akses_jalan']").val(v.akses_jalan).trigger("change.select2");
				//header-aktifitas
				$("select[name='kualitas_bokar']").val(v.kualitas_bokar).trigger("change.select2");
				$("select[name='cara_penyimpanan']").val(v.cara_penyimpanan).trigger("change.select2");
				$("select[name='jenis_bokar']").val(v.jenis_bokar).trigger("change.select2");
				$("select[name='jenis_pembayaran']").val(v.jenis_pembayaran).trigger("change.select2");
				$("select[name='pph_22']").val(v.pph_22).trigger("change.select2");
				$("select[name='pengelola_keuangan']").val(v.pengelola_keuangan).trigger("change.select2");

				$("input[name='frekuensi_penjualan_mitra_per_minggu']").val(v.frekuensi_penjualan_mitra_per_minggu);
				$("input[name='volume_bokar_mitra_per_hari']").val(v.volume_bokar_mitra_per_hari);
				$("select[name='sumber_pendapatan_mitra']").val(v.sumber_pendapatan_mitra).trigger("change.select2");
				$("input[name='frekuensi_penjualan_rekan_mitra_per_minggu']").val(v.frekuensi_penjualan_rekan_mitra_per_minggu);
				$("input[name='volume_bokar_rekan_mitra_per_hari']").val(v.volume_bokar_rekan_mitra_per_hari);
				$("select[name='status_sosial_mitra']").val(v.status_sosial_mitra).trigger("change.select2");
				$("input[name='total_volume_penjualan_per_hari']").val(v.total_volume_penjualan_per_hari);
				$("select[name='modal_kerja']").val(v.modal_kerja).trigger("change.select2");
				$("input[name='estimasi_tonase_kering']").val(v.estimasi_tonase_kering);
				$("select[name='pengiriman_dana_bokar']").val(v.pengiriman_dana_bokar).trigger("change.select2");
				$("select[name='rekening_tujuan']").val(v.rekening_tujuan).trigger("change.select2");
				$("input[name='jumlah_pelelangan']").val(v.jumlah_pelelangan);
				$("input[name='jumlah_tronton_per_minggu']").val(v.jumlah_tronton_per_minggu);
				//detail-supplier-jarak depo dg lokasi
                if (v.arr_data_lokasi) {
					let output = "";
					let no = 0;
					$("#nodata_lokasi").remove();
					$.each(v.arr_data_lokasi, function(a, b){
						if(b.id_lokasi !=null){
							no++;
							output += "<tr class='row-lokasi lokasi" + no + "'>";
							output += "	<td>";
							output += "		<input type='hidden' class='form-control' name='id_lokasi[]' value='"+b.id_lokasi+"'/>";
							output += "		<input type='text' class='form-control' name='nama_lokasi[]' value='"+b.nama_lokasi+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='jarak_lokasi[]' value='"+b.jarak+"' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='waktu_lokasi[]' value='"+b.waktu+"' required='required'/>";
							output += "	</td>";
							output += "	<td>";
							output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_lokasi[]' style='resize:vertical' rows='3'>"+b.keterangan+"</textarea>";
							output += "	</td>";
							output += "</tr>";
						}
					});
					$(output).appendTo(".table-lokasi tbody");
                }				
				
				//detail-supplier-jarak depo dg depo KMG
                if (v.arr_data_lokasi) {
					let no = 0;
					$("#nodata").remove();
					$.each(v.arr_data_lokasi, function(a, b){ 
						if(b.id_depo !=null){
							no++;
							let output = "";
							output += "<tr class='row-summary summary" + b.id_depo + "'>";
							output += "	<td>";
							// output += "		<input type='hidden' class='form-control' name='id_depo[]' value='"+b.id_depo+"'/>";
							output += "		<select class='form-control select2 autocomplete' name='id_depo[]' required='required'>";
							output += "		</select>";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='jarak_depo[]' value='"+b.jarak+"' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='"+b.waktu+"' required='required'/>";
							output += "	</td>";
							output += "	<td>";
							output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_depo[]' style='resize:vertical' rows='3'>"+b.keterangan+"</textarea>";
							output += "	</td>";
							output += '	<td class="text-center">';
							output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
							output += "	</td>";
							output += "</tr>";
							$(output).appendTo(".table-summary tbody");
						
							const elem = ".row-summary.summary" + b.id_depo;
							master_depo(elem + " select[name='id_depo[]']");
							console.log(master_depo);
							let control = $(elem+ " select[name='id_depo[]']").empty().data("select2");
							console.log(control);
							let adapter = control.dataAdapter;
							let desc = `[${b.id_depo}] ${b.nama_depo}`;
							adapter.addOptions(
								adapter.convertToOptions([{
									id: b.id_depo,
									text: desc,
								},])
							);
							$(elem+ " select[name='id_depo[]']").trigger("change");
						}
					});
                }								
				
				//detail-supplier-jarak depo dg gudang kompetitor
                if (v.arr_data_lokasi) {
					let no = 0;
					let output = "";
					$("#nodata_gudang").remove();
					$.each(v.arr_data_lokasi, function(a, b){
						if(b.gudang_kompetitor !=null){
							output += "<tr class='row-gudang gudang" + no + "'>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='gudang_kompetitor[]' value='"+b.gudang_kompetitor+"' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='jarak_gudang[]' value='"+b.jarak+"' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='waktu_gudang[]' value='"+b.waktu+"' required='required'/>";
							output += "	</td>";
							output += "	<td>";
							output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_gudang[]' style='resize:vertical' rows='3'>"+b.keterangan+"</textarea>";
							output += "	</td>";
							output += '	<td class="text-center">';
							output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_gudang' title='Remove'><i class='fa fa-trash-o'></i></button>";
							output += "	</td>";
							output += "</tr>";
							no++;
						}
					});
					$(output).appendTo(".table-gudang tbody");
                }				
				
				//detail-supplier-jarak depo dg pabrik kompetitor
                if (v.arr_data_lokasi) {
					let no = 0;
					let output = "";
					$("#nodata_pabrik").remove();
					$.each(v.arr_data_lokasi, function(a, b){
						if(b.pabrik_kompetitor !=null){
							output += "<tr class='row-pabrik pabrik" + no + "'>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='pabrik_kompetitor[]' value='"+b.pabrik_kompetitor+"' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='jarak_pabrik[]' value='"+b.jarak+"' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='waktu_pabrik[]' value='"+b.waktu+"' required='required'/>";
							output += "	</td>";
							output += "	<td>";
							output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_pabrik[]' style='resize:vertical' rows='3'>"+b.keterangan+"</textarea>";
							output += "	</td>";
							output += '	<td class="text-center">';
							output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_pabrik' title='Remove'><i class='fa fa-trash-o'></i></button>";
							output += "	</td>";
							output += "</tr>";
							no++;
						}
					});
					$(output).appendTo(".table-pabrik tbody");
                }				
				
				//detail-lingkungan-gambar
				if (v.arr_data_gambar) {
					let output = "";
					$.each(v.arr_data_gambar, function (a, b) {
						if(b.url !=null){
							var default_img = baseURL+''+b.url;
						}else{
							var default_img = baseURL+'assets/apps/img/test/dummy.png';
						}
						
						var id_foto = 'id_foto'+a;
						var view_foto = 'view_foto'+a;
						var file_foto = 'file_foto'+a;
						output += '<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">';
						output += '	<div id="product' + a + '" class="product-thumb transition">';
						output += '		<div class="image">';
						output += '			<img id="'+ id_foto +'" alt="Preview Image" src="' + default_img + '" width="300px" height="300px">'; 
						output += '		</div>';
						output += '		<div class="button-group">';
						output += '			<button type="button" class="btn btn-default col-lg-12 col-md-12 col-sm-12 col-xs-12">FOTO <span>'+b.nama_gambar+'</span></button>';
						output += '			<input type="hidden" name="id_gambar[]" class="form-control" value="'+b.id_gambar+'">'; 
						output += '			<input type="hidden" name="nama_foto[]" class="form-control" value="'+b.nama_gambar+'">'; 
						output += '			<input type="file" name="'+file_foto+'[]" class="form-control" id="'+ view_foto +'" onchange="previewFile(' + a + ');">'; 
						output += '		</div>';
						output += '	</div>';
						output += '</div>';
					});

					$(".katalog-product").html(output);
					$(".pagination-wrapper").html(data.links);
				} else {
					output += '<div class="col-sm-12">';
					output += '	<div class="well text-center">No data found</div>';
					output += '</div>';
					$(".katalog-product").html(output);
				}

				//detail-potensi-desa
                if (v.arr_data_desa) {
					let no = 0;
					let output = "";
					let total = 0;
					$("#nodata_desa").remove();
					$.each(v.arr_data_desa, function(a, b){
						output += "<tr class='row-desa desa" + no + "'>";
						output += "	<td>";
						output += "		<input type='text' class='form-control' name='nama_desa[]' value='"+b.nama+"' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-center' name='luas_desa[]' value='"+numberWithCommas(b.luas)+"' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_desa[]' style='resize:vertical' rows='3'>"+b.keterangan+"</textarea>";
						output += "	</td>";
						output += '	<td class="text-center">';
						output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_desa' title='Remove'><i class='fa fa-trash-o'></i></button>";
						output += "	</td>";
						output += "</tr>";
						no++;
						total += +b.luas;
					});
					$(output).appendTo(".table-desa tbody");
					//footer
					$("input[name='total_luas_area']").val(numberWithCommas(total.toFixed(2)));
                }				
				
				//detail-potensi-survei
				let sicom = 0;
				let kadar = 0;
				let qty_beli = 0;
				let harga_beli_pabrik = 0;
				let budget_total_cost = 0;
                if (v.arr_data_survei) {
					let no_survei = 0;
					let total_harga_per_hari_survei = 0;
					let total_harga_notarin_survei = 0;
					let total_harga_sicom_survei = 0;
					let total_total_produksi_survei = 0;
					let total_rata_rata_survei = 0;
					let output = "";
					$("#nodata_survei").remove();
					$.each(v.arr_data_survei, function(a, b){
						no_survei++;
						total_harga_per_hari_survei += parseFloat(b.harga_per_hari);
						total_harga_notarin_survei += parseFloat(b.harga_notarin);
						total_harga_sicom_survei += parseFloat(b.harga_sicom);
						total_total_produksi_survei += parseFloat(b.total_produksi);
						total_rata_rata_survei += parseFloat(b.rata_rata);
						
						output += "<tr class='row-survei survei" + no_survei + "'>";
						output += "	<td>";
						output += "		<input type='text' class='form-control tanggal' name='tanggal_survei[]' value='"+KIRANAKU.isNullOrEmpty(b.tanggal, moment(b.tanggal).format('DD.MM.YYYY'), '-')+"' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-center' name='harga_per_hari_survei[]' value='"+numberWithCommas(b.harga_per_hari)+"' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-center' name='harga_notarin_survei[]' value='"+numberWithCommas(b.harga_notarin)+"' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-center' name='harga_sicom_survei[]' value='"+numberWithCommas(b.harga_sicom)+"' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-center' name='total_produksi_survei[]' value='"+numberWithCommas(b.total_produksi)+"' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-center' name='rata_rata_survei[]' value='"+numberWithCommas(parseFloat(b.rata_rata).toFixed(2))+"' required='required' />";
						output += "	</td>";
						output += '	<td class="text-center">';
						output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_survei' title='Remove'><i class='fa fa-trash-o'></i></button>";
						output += "	</td>";
						output += "</tr>";
						
					});
					$(output).appendTo(".table-survei tbody");
					//total table-survei
					$("input[name='total_harga_per_hari_survei']").val(numberWithCommas(parseFloat(total_harga_per_hari_survei/no_survei).toFixed(0)));
					$("input[name='total_harga_notarin_survei']").val(numberWithCommas(parseFloat(total_harga_notarin_survei/no_survei).toFixed(2)));
					$("input[name='total_harga_sicom_survei']").val(numberWithCommas(parseFloat(total_harga_sicom_survei/no_survei).toFixed(2)));
					$("input[name='total_total_produksi_survei']").val(numberWithCommas(parseFloat(total_total_produksi_survei/no_survei).toFixed(2)));
					$("input[name='total_rata_rata_survei']").val(numberWithCommas(parseFloat(total_rata_rata_survei/no_survei).toFixed(2)));
					
					
					//set value untuk rumus
					kadar 		= parseFloat(total_rata_rata_survei/no_survei).toFixed(2);
					harga_beli 	= parseFloat(total_harga_per_hari_survei/no_survei).toFixed(0);
					sicom		= parseFloat(total_harga_sicom_survei/no_survei).toFixed(0);
					budget_total_cost = parseFloat(total_total_produksi_survei/no_survei).toFixed(0);
					
					//C. Profitabilitas 
					harga_beli_pabrik = parseFloat(total_harga_notarin_survei/no_survei);
					$("input[name='harga_beli_pabrik']").val(numberWithCommas(parseFloat(harga_beli_pabrik).toFixed(2)));
					
					$('.tanggal').datepicker({ 
						format: 'dd.mm.yyyy',
						changeMonth: true,
						changeYear: true, 
						autoclose: true
					}); 
                }				
				//detail-potensi-survei
				let avg_target = 0;
				// let harga_beli = 0;
                if (v.arr_data_target) {
					$.each(v.arr_data_target, function(a, b){
						avg_target = (b.m1+b.m2+b.m3+b.m4+b.m5+b.m6+b.m7+b.m8+b.m9+b.m10+b.m11+b.m12)/12;
						$("input[name='target_m1']").val(b.m1);
						$("input[name='target_m2']").val(b.m2);
						$("input[name='target_m3']").val(b.m3);
						$("input[name='target_m4']").val(b.m4);
						$("input[name='target_m5']").val(b.m5);
						$("input[name='target_m6']").val(b.m6);
						$("input[name='target_m7']").val(b.m7);
						$("input[name='target_m8']").val(b.m8);
						$("input[name='target_m9']").val(b.m9);
						$("input[name='target_m10']").val(b.m10);
						$("input[name='target_m11']").val(b.m11);
						$("input[name='target_m12']").val(b.m12);
						
						$("input[name='avg_target']").val(numberWithCommas(parseFloat(avg_target).toFixed(2)));
					});
					//set value untuk rumus
					avg_target	= parseFloat(avg_target);
                }				
				
				//detail-biaya-depo
                if (v.arr_data_biaya_depo) {
					let no = 0;
					$("#nodata_biaya_depo").remove();
					$.each(v.arr_data_biaya_depo, function(a, b){
							no++;
							let output = "";
							output += "<tr class='row-biaya_depo biaya_depo" + b.id_biaya + "'>";
							output += "	<td>";
							output += "		<select class='form-control select2' name='id_biaya_depo[]' required='required'>";
							output += "			<option></option>";
							output += "		</select>";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='biaya_depo[]' value='"+numberWithCommas(parseFloat(b.biaya).toFixed(2))+"' placeholder='Biaya'  required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='tonase_depo[]' value='"+numberWithCommas(parseFloat(b.tonase).toFixed(2))+"' placeholder='Tonase'  required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='total_depo[]' value='"+numberWithCommas(parseFloat(b.biaya/b.tonase).toFixed(2))+"' placeholder='Total'  required='required'  readonly/>";
							output += "	</td>";
							output += '	<td class="text-center">';
							output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_depo' title='Remove'><i class='fa fa-trash-o'></i></button>";
							output += "	</td>";
							output += "</tr>";
							$(output).appendTo(".table-biaya_depo tbody");

							const elem = ".row-biaya_depo.biaya_depo" + b.id_biaya;
							if(v.jenis_depo=='mitra'){
								master_biaya(elem + " select[name='id_biaya_depo[]']", 'mitra','operational','transaksi');
							}else{
								master_biaya(elem + " select[name='id_biaya_depo[]']", 'tetap','operational','transaksi');
							}
							let control = $(elem+ " select[name='id_biaya_depo[]']").empty().data("select2");
							let adapter = control.dataAdapter;
							let desc = `${b.nama_biaya}`;
							adapter.addOptions(
								adapter.convertToOptions([{
									id: b.id_biaya,
									text: desc,
								},])
							);
							$(elem+ " select[name='id_biaya_depo[]']").trigger("change");
					});
                }								
				
				//detail-biaya-sdm
				let total_biaya_sdm_gapok = 0;
				let total_biaya_sdm_tunjangan = 0;
                if (v.arr_data_biaya_sdm) {
					let no = 0;
					$("#nodata_biaya_sdm").remove();
					$.each(v.arr_data_biaya_sdm, function(a, b){
						no++;
						total_biaya_sdm_gapok += parseFloat(b.gaji_pokok);
						total_biaya_sdm_tunjangan += parseFloat(b.tunjangan);
						let output = "";
						output += "<tr class='row-biaya_sdm biaya_sdm" + b.id_biaya + "'>";
						output += "	<td>";
						output += "		<select class='form-control select2' name='id_biaya_sdm[]' required='required'>";
						output += "			<option></option>";
						output += "		</select>";
						output += "	</td>";
						output += "	<td>";
						output += "		<select class='form-control select2' name='jenis_budget_sdm[]' required='required'  data-placeholder='Pilih Status'>";
						output += "			<option ></option>";
						output += "			<option value='budget'>Budget</option>";
						output += "			<option value='unbudget'>Unbudget</option>";
						output += "		</select>";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='nik_sdm[]' value='"+b.nik+"' placeholder='NIK'  required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control' name='nama_sdm[]' value='"+b.nama+"' placeholder='Nama' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-center' name='gaji_pokok_sdm[]' value='"+numberWithCommas(b.gaji_pokok)+"' placeholder='Gaji Pokok' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='angka form-control text-center' name='tunjangan_sdm[]' value='"+numberWithCommas(b.tunjangan)+"' placeholder='Tunjangan' required='required' />";
						output += "	</td>";
						output += "	<td>";
						output += "		<select class='form-control select2' name='status_sdm[]' required='required' data-placeholder='Pilih Status'>";
						output += "			<option ></option>";
						output += "			<option value='tetap'>Tetap</option>";
						output += "			<option value='kontrak'>Kontrak</option>";
						output += "		</select>";
						output += "	</td>";
						output += '	<td class="text-center">';
						output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_sdm' title='Remove'><i class='fa fa-trash-o'></i></button>";
						output += "	</td>";
						output += "</tr>";
						$(output).appendTo(".table-biaya_sdm tbody");
						
						const elem = ".row-biaya_sdm.biaya_sdm" + b.id_biaya;
						master_biaya(elem + " select[name='id_biaya_sdm[]']", 'all','operational','sdm');
						let control = $(elem+ " select[name='id_biaya_sdm[]']").empty().data("select2");
						let adapter = control.dataAdapter;
						let desc = `${b.nama_biaya}`;
						adapter.addOptions(
							adapter.convertToOptions([{
								id: b.id_biaya,
								text: desc,
							},])
						);
						$(elem+ " select[name='id_biaya_sdm[]']").trigger("change");
						$(elem+ " select[name='jenis_budget_sdm[]']").val(b.jenis_budget).trigger("change.select2");							
						$(elem+ " select[name='status_sdm[]']").val(b.status).trigger("change.select2");							
					});
					$("input[name='total_biaya_sdm_gapok']").val(numberWithCommas(total_biaya_sdm_gapok));
					$("input[name='total_biaya_sdm_tunjangan']").val(numberWithCommas(total_biaya_sdm_tunjangan));
                }								
				
				//detail-biaya-trans-darat
				let total_biaya_darat = 0;
                if (v.arr_data_biaya_trans) {
					let output = "";
					let no = 0;
					$("#nodata_biaya_darat").remove();
					$.each(v.arr_data_biaya_trans, function(a, b){
						if(b.jenis_trans =='darat'){
							no++;
							total_biaya_darat += parseFloat(b.biaya_per_kg);
							output += "<tr class='row-biaya_darat biaya_darat" + no + "'>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='nomor_vendor_darat[]' value='"+b.nomor_vendor+"' placeholder='Nomor Vendor'  required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='nama_vendor_darat[]' value='"+b.nama_vendor+"' placeholder='Nama Vendor'  required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<select class='form-control select2' name='penentuan_tarif_darat[]' required='required'  data-placeholder='Pilih Status'>";
							output += "			<option value='pabrik'>Pabrik</option>";
							output += "			<option value='depo'>Depo</option>";
							output += "		</select>";
							output += "	</td>";
							output += "	<td>";
							output += "		<select class='form-control select2' name='kapasitas_basah_darat[]' required='required'  data-placeholder='Pilih Status'>";
							output += "			<option value='1'>< 8 Ton</option>";
							output += "			<option value='2'>8 - 10 Ton</option>";
							output += "			<option value='3'>10 - 20 Ton</option>";
							output += "			<option value='4'>20 - 25 Ton</option>";
							output += "			<option value='5'>25 - 35 Ton</option>";
							output += "			<option value='6'>35 - 45 Ton</option>";
							output += "		</select>";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control' name='biaya_per_trip_darat[]' value='"+numberWithCommas(b.biaya_per_trip)+"' placeholder='Rp/ Unit' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='biaya_per_kg_darat[]' value='"+numberWithCommas(b.biaya_per_kg)+"' placeholder='Total' required='required' />";
							output += "	</td>";
							output += '	<td class="text-center">';
							output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_darat' title='Remove'><i class='fa fa-trash-o'></i></button>";
							output += "	</td>";
							output += "</tr>";
							const elem = ".row-biaya_darat .biaya_darat" + no;
							console.log(elem);
							$(elem+ " select[name='penentuan_tarif_darat[]']").val(b.penentuan_tarif).trigger("change");							
							$(elem+ " select[name='kapasitas_basah_darat[]']").val(b.kapasitas_basah).trigger("change");							
						}
					});
					$(output).appendTo(".table-biaya_darat tbody");
                }												
				
				//detail-biaya-trans-air
				let total_biaya_air = 0;
                if (v.arr_data_biaya_trans) {
					let output = "";
					let no = 0;
					$("#nodata_biaya_air").remove();
					$.each(v.arr_data_biaya_trans, function(a, b){
						if(b.jenis_trans =='air'){
							no++;
							total_biaya_air += parseFloat(b.biaya_per_kg);
							output += "<tr class='row-biaya_air biaya_air" + no + "'>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='nomor_vendor_air[]' value='"+b.nomor_vendor+"' placeholder='Nomor Vendor'  required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='nama_vendor_air[]' value='"+b.nama_vendor+"' placeholder='Nama Vendor'  required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<select class='form-control select2' name='kapasitas_basah_air[]' required='required'  data-placeholder='Pilih Status'>";
							output += "			<option value='1'>< 300 Ton</option>";
							output += "			<option value='2'>300 - 500 Ton</option>";
							output += "			<option value='3'>500 - 700 Ton</option>";
							output += "			<option value='4'>> 700 Ton</option>";
							output += "		</select>";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control' name='biaya_per_trip_air[]' value='"+numberWithCommas(b.biaya_per_trip)+"' placeholder='Rp/ Unit' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='biaya_per_kg_air[]' value='"+numberWithCommas(b.biaya_per_kg)+"' placeholder='Total' required='required' />";
							output += "	</td>";
							output += '	<td class="text-center">';
							output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_air' title='Remove'><i class='fa fa-trash-o'></i></button>";
							output += "	</td>";
							output += "</tr>";
							$(output).appendTo(".table-biaya_air tbody");
							
							const elem = ".row-biaya_air .biaya_air" + no;
							$(elem+ " select[name='kapasitas_basah_air[]']").val(3).trigger("change.select2");							
						}
					});
					
                }								
				
				//detail-biaya-investasi
				let total_biaya_investasi = 0;
                if (v.arr_data_biaya_investasi) {
					let no = 0;
					$("#nodata_biaya_investasi").remove();
					$.each(v.arr_data_biaya_investasi, function(a, b){
							no++;
							total_biaya_investasi += parseFloat(b.total);
							let output = "";
							output += "<tr class='row-biaya_investasi biaya_investasi" + b.id_biaya + "'>";
							output += "	<td>";
							output += "		<select class='form-control select2' name='id_biaya_investasi[]' required='required'>";
							output += "			<option></option>";
							output += "		</select>";
							output += "	</td>";
							output += "	<td>";
							output += "		<select class='form-control select2' name='kepemilikan_investasi[]' required='required'  data-placeholder='Pilih Status'>";
							output += "			<option ></option>";
							output += "			<option value='ada'>Ada</option>";
							output += "			<option value='tidak_ada'>Tidak Ada</option>";
							output += "		</select>";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='jumlah_investasi[]' value='"+numberWithCommas(b.jumlah)+"' placeholder='QTY'  required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control' name='harga_investasi[]' value='"+numberWithCommas(b.harga)+"' placeholder='Rp/ Unit' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='total_investasi[]' value='"+numberWithCommas(b.total)+"' placeholder='Total' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_investasi[]' style='resize:vertical' rows='3'>"+b.keterangan+"</textarea>";
							output += "	</td>";
							output += '	<td class="text-center">';
							output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_investasi' title='Remove'><i class='fa fa-trash-o'></i></button>";
							output += "	</td>";
							output += "</tr>";
							$(output).appendTo(".table-biaya_investasi tbody");
							
							const elem = ".row-biaya_investasi.biaya_investasi" + b.id_biaya;
							master_biaya(elem + " select[name='id_biaya_investasi[]']", 'all','investasi',0);
							let control = $(elem+ " select[name='id_biaya_investasi[]']").empty().data("select2");
							let adapter = control.dataAdapter;
							let desc = `${b.nama_biaya}`;
							adapter.addOptions(
								adapter.convertToOptions([{
									id: b.id_biaya,
									text: desc,
								},])
							);
							$(elem+ " select[name='id_biaya_investasi[]']").trigger("change");
							$(elem+ " select[name='kepemilikan_investasi[]']").val(b.kepemilikan).trigger("change.select2");							
					});
					
					$("input[name='total_biaya_investasi']").val(numberWithCommas(total_biaya_investasi));
                }							
				//======================		
				//===tab dokumen==========		
				//======================		
                if (v.arr_data_dokumen) {
					let no = 0;
					let output = "";
					$("#nodata_lampiran_dokumen").remove();
					$.each(v.arr_data_dokumen, function(a, b){
						no++;
						var file_lampiran = 'file_lampiran'+a;
						var mandatory = (b.mandatory=='y') ? 'Mandatory':'Tidak Mandatory';
						var disabled = (b.url!=null) ? '':'disabled';
						output += "<tr class='row-lampiran_dokumen lampiran_dokumen" + a + "'>";
						output += "	<td>";
						output += "		<input type='text' class='form-control' name='nama_dokumen[]' value='"+b.nama_dokumen+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control' name='mandatory_dokumen[]' value='"+mandatory+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += '		<input type="hidden" name="id_dokumen[]" class="form-control" value="'+b.id_dokumen+'">'; 
						output += '		<input type="file" name="'+file_lampiran+'[]" class="form-control" id="'+ file_lampiran +'">';
						output += "	</td>";
						output += "	<td align='center'>";
						output += '	<a href="'+baseURL+''+b.url+'" target="_blank">';
						output += "		<button type='button' class='btn btn-default pull-center' title='Lihat Dokumen' "+disabled+"><i class='fa fa-clipboard'></i></button>";
						output += "	</a>";
						output += "	</td>";
						output += "</tr>";
					});
					$(output).appendTo(".table-lampiran_dokumen tbody");
                }								
				
				//======================		
				//===tab analisis==========		
				//======================		
				//analisis-biaya-depo_analisis
				let total_biaya_depo_kgb_analisis = 0;
				let total_biaya_depo_kgk_analisis = 0;
				let biaya_opex_kgb_analisis		  = 0;	
                if (v.arr_data_biaya_detail) {
					$("#nodata_biaya_depo_analisis").remove();
					let no = 0;
					$.each(v.arr_data_biaya_detail, function(a, b){
						if((b.jenis_depo=='tetap')&&(b.jenis_biaya=='operational')&&(b.jenis_biaya_detail=='transaksi')){
							biaya_opex_kgb_analisis += parseFloat(b.biaya);
						}
						if((b.jenis_depo=='mitra')&&(b.jenis_biaya=='operational')&&(b.jenis_biaya_detail=='transaksi')){
							no++;
							total_biaya_depo_kgb_analisis += parseFloat(b.biaya);
							total_biaya_depo_kgk_analisis += parseFloat(b.biaya/(kadar/100));

							let output = "";
							output += "<tr class='row-biaya_depo_analisis biaya_depo_analisis" + b.id_biaya + "'>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='nama_depo_kgb_analisis[]' value='"+numberWithCommas(b.nama_biaya)+"' placeholder='Nama Biaya'  required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgb_analisis[]' value='"+numberWithCommas(parseFloat(b.biaya).toFixed(2))+"' placeholder='Biaya'  required='required'  readonly/>";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgk_analisis[]' value='"+numberWithCommas(parseFloat(b.biaya/(kadar/100)).toFixed(2))+"' placeholder='Biaya'  required='required'  readonly/>";
							output += "	</td>";
							output += "</tr>";
							$(output).appendTo(".table-biaya_depo_analisis tbody");
							const elem = ".row-biaya_depo_analisis.biaya_depo_analisis" + b.id_biaya;
						}
					});
					$("input[name='total_biaya_depo_kgb_analisis']").val(numberWithCommas(parseFloat(total_biaya_depo_kgb_analisis).toFixed(2)));
					$("input[name='total_biaya_depo_kgk_analisis']").val(numberWithCommas(parseFloat(total_biaya_depo_kgk_analisis).toFixed(2)));
                }								

				
				//analisis biaya-opex
				let biaya_opex_kgk_analisis = 0;
				if(v.jenis_depo=='tetap'){
					biaya_opex_kgk_analisis = parseFloat(biaya_opex_kgb_analisis/(kadar/100));
					$("input[name='biaya_opex_kgb_analisis']").val(numberWithCommas(parseFloat(biaya_opex_kgb_analisis).toFixed(2)));
					$("input[name='biaya_opex_kgk_analisis']").val(numberWithCommas(parseFloat(biaya_opex_kgk_analisis).toFixed(2)));
				}else{
					
					$("input[name='biaya_opex_kgb_analisis']").val(0);
					$("input[name='biaya_opex_kgk_analisis']").val(0);
				}
				// biaya_opex_kgk_analisis = parseFloat(biaya_opex_kgb_analisis/(kadar/100)).toFixed(0);
				// let total_cost_depo = parseFloat(biaya_opex_kgk_analisis);

				//analisis biaya-trans
				let biaya_angkut_kgb_analisis = parseFloat(total_biaya_darat)+parseFloat(total_biaya_air);				
				let biaya_angkut_kgk_analisis = biaya_angkut_kgb_analisis/kadar*100;				
				$("input[name='biaya_angkut_kgb_analisis']").val(numberWithCommas(parseFloat(biaya_angkut_kgb_analisis).toFixed(2)));
				$("input[name='biaya_angkut_kgk_analisis']").val(numberWithCommas(parseFloat(biaya_angkut_kgk_analisis).toFixed(2)));
				//analisis biaya-sdm
				let total_biaya_sdm = parseFloat(total_biaya_sdm_gapok)+parseFloat(total_biaya_sdm_tunjangan);				
				let biaya_sdm_gapok_kgk_analisis = total_biaya_sdm_gapok/(avg_target*1000);
				let biaya_sdm_tunjangan_kgk_analisis = total_biaya_sdm_tunjangan/(avg_target*1000);
				let total_biaya_sdm_kgk_analisis = biaya_sdm_gapok_kgk_analisis + biaya_sdm_tunjangan_kgk_analisis;
				$("input[name='biaya_sdm_gapok_analisis']").val(numberWithCommas(total_biaya_sdm_gapok));
				$("input[name='biaya_sdm_tunjangan_analisis']").val(numberWithCommas(total_biaya_sdm_tunjangan));
				$("input[name='total_biaya_sdm_analisis']").val(numberWithCommas(total_biaya_sdm));
				
				$("input[name='biaya_sdm_gapok_kgk_analisis']").val(numberWithCommas(parseFloat(biaya_sdm_gapok_kgk_analisis).toFixed(2)));
				$("input[name='biaya_sdm_tunjangan_kgk_analisis']").val(numberWithCommas(parseFloat(biaya_sdm_tunjangan_kgk_analisis).toFixed(2)));
				$("input[name='total_biaya_sdm_kgk_analisis']").val(numberWithCommas(parseFloat(total_biaya_sdm_kgk_analisis).toFixed(2)));
				
				//analisis biaya-asuransi
				let biaya_cash_save_analisis = (((avg_target/25) / (kadar/100) )*harga_beli*1000)*0.002;				
				let biaya_cash_transit_analisis = (((avg_target/25) / (kadar/100) )*harga_beli*1000)*0.0002;
				let biaya_expedition_analisis = (((avg_target*0.9) / (kadar) )*harga_beli*1000)*0.0425;				
				
				let total_biaya_asuransi_analisis = biaya_cash_save_analisis+biaya_cash_transit_analisis+biaya_expedition_analisis;				
				let biaya_cash_save_kgk_analisis = biaya_cash_save_analisis/(avg_target*1000);;				
				let biaya_cash_transit_kgk_analisis = biaya_cash_transit_analisis/(avg_target*1000);;
				let biaya_expedition_kgk_analisis = biaya_expedition_analisis/(avg_target*1000);;				
				let total_biaya_asuransi_kgk_analisis = parseFloat(biaya_cash_save_kgk_analisis)+parseFloat(biaya_cash_transit_kgk_analisis)+parseFloat(biaya_expedition_kgk_analisis);				
				$("input[name='biaya_cash_save_analisis']").val(numberWithCommas(parseFloat(biaya_cash_save_analisis).toFixed(0)));
				$("input[name='biaya_cash_transit_analisis']").val(numberWithCommas(parseFloat(biaya_cash_transit_analisis).toFixed(0)));
				$("input[name='biaya_expedition_analisis']").val(numberWithCommas(parseFloat(biaya_expedition_analisis).toFixed(0)));
				$("input[name='total_biaya_asuransi_analisis']").val(numberWithCommas(parseFloat(total_biaya_asuransi_analisis).toFixed(0)));
				$("input[name='biaya_cash_save_kgk_analisis']").val(numberWithCommas(parseFloat(biaya_cash_save_kgk_analisis).toFixed(2)));
				$("input[name='biaya_cash_transit_kgk_analisis']").val(numberWithCommas(parseFloat(biaya_cash_transit_kgk_analisis).toFixed(2)));
				$("input[name='biaya_expedition_kgk_analisis']").val(numberWithCommas(parseFloat(biaya_expedition_kgk_analisis).toFixed(2)));
				$("input[name='total_biaya_asuransi_kgk_analisis']").val(numberWithCommas(parseFloat(total_biaya_asuransi_kgk_analisis).toFixed(2)));
				
				//C. Profitabilitas
				// let total_cost_depo = parseFloat(biaya_opex_kgk_analisis)+parseFloat(biaya_angkut_kgk_analisis);
				let total_cost_depo = parseFloat(total_biaya_depo_kgk_analisis)+parseFloat(biaya_opex_kgk_analisis)+parseFloat(biaya_angkut_kgk_analisis)+parseFloat(total_biaya_sdm_kgk_analisis)+parseFloat(total_biaya_asuransi_kgk_analisis);
				$("input[name='total_cost_depo']").val(numberWithCommas(parseFloat(total_cost_depo).toFixed(2)));
				let target_beli_depo = parseFloat(harga_beli_pabrik)-parseFloat(total_cost_depo);
				$("input[name='target_beli_depo']").val(numberWithCommas(parseFloat(target_beli_depo).toFixed(2)));
				
				let target_harga_beli_depo = parseFloat(harga_beli_pabrik)-parseFloat(total_cost_depo);
				$("input[name='target_harga_beli_depo']").val(numberWithCommas(parseFloat(target_harga_beli_depo).toFixed(2)));
				let survei_harga_beli_depo = parseFloat(harga_beli/(kadar/100));
				$("input[name='survei_harga_beli_depo']").val(numberWithCommas(parseFloat(survei_harga_beli_depo).toFixed(2)));
				let deviasi_depo_pabrik = target_harga_beli_depo-survei_harga_beli_depo;
				$("input[name='deviasi_depo_pabrik']").val(numberWithCommas(parseFloat(deviasi_depo_pabrik).toFixed(2)));
				
				$("input[name='sicom_kode_pabrik']").val(numberWithCommas(parseFloat(sicom).toFixed(2)));
				$("input[name='harga_beli_depo']").val(numberWithCommas(parseFloat(survei_harga_beli_depo).toFixed(2)));
				$("input[name='budget_total_cost']").val(numberWithCommas(parseFloat(budget_total_cost).toFixed(2)));
				
				$("input[name='total_biaya_operasional']").val(numberWithCommas(parseFloat(total_cost_depo).toFixed(2)));
				let net_margin = parseFloat(sicom)-(parseFloat(survei_harga_beli_depo)+parseFloat(budget_total_cost)+parseFloat(total_cost_depo));
				$("input[name='net_margin']").val(numberWithCommas(parseFloat(net_margin).toFixed(2)));
				
				//PAYBACK PERIOD
				let rata_target_beli 	= avg_target*1000;
				let total_profit 		= avg_target*1000*net_margin;
				let payback_periode		= (total_biaya_investasi/total_profit/12)<0?'-':numberWithCommas(parseFloat(total_biaya_investasi/total_profit/12).toFixed(2));

				
				$("input[name='rata_target_beli']").val(numberWithCommas(parseFloat(rata_target_beli).toFixed(0)));
				$("input[name='proyeksi_net_margin']").val(numberWithCommas(parseFloat(net_margin).toFixed(0)));
				$("input[name='total_profit']").val(numberWithCommas(parseFloat(total_profit).toFixed(0)));
				$("input[name='payback_periode']").val(payback_periode);

			
				//======================		
				//===tab scoring==========		
				//======================		
				//potensi
				let total_bobot_potensi = 0;
				let total_nilai_potensi = 0;
                if (v.arr_data_scoring) {
					let output = "";
					$("#nodata_scoring_potensi").remove();
					$.each(v.arr_data_scoring, function(a, b){
						if(b.jenis_matrix=='potensi'){
							let arr_nilai = b.nilai.split("|");
							let nilai_potensi = parseFloat((arr_nilai[1]*b.bobot)/100).toFixed(2);
							total_bobot_potensi += parseFloat(b.bobot);
							total_nilai_potensi += parseFloat(nilai_potensi);
							output += "<tr class='row-scoring_potensi scoring_potensi" + a + "'>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='nama_scoring_potensi[] text-center' value='"+b.nama_matrix+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='bobot_scoring_potensi[]' value='"+arr_nilai[0]+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai1_scoring_potensi[]' value='"+nilai_potensi+"' required='required' readonly />";
							output += "	</td>";
							output += "</tr>";
						}
					});
					$(output).appendTo(".table-scoring_potensi tbody");
					$("#caption_scoring_potensi").html("POTENSI ("+total_bobot_potensi+"%)");
					$("input[name='total_nilai_potensi']").val(parseFloat(total_nilai_potensi).toFixed(2));
					
                }								
				//mitra
				let total_bobot_mitra = 0;
				let total_nilai_mitra = 0;
                if (v.arr_data_scoring) {
					let output = "";
					$("#nodata_scoring_mitra").remove();
					$.each(v.arr_data_scoring, function(a, b){
						if(b.jenis_matrix=='mitra'){
							let arr_nilai = b.nilai.split("|");
							let nilai_mitra = parseFloat((arr_nilai[1]*b.bobot)/100).toFixed(2);
							total_bobot_mitra += parseFloat(b.bobot);
							total_nilai_mitra += parseFloat(nilai_mitra);
							output += "<tr class='row-scoring_mitra scoring_mitra" + a + "'>";
							output += "	<td align='center'>";
							output += "		<input type='text' class='form-control' name='nama_scoring_mitra[] text-center' value='"+b.nama_matrix+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='bobot_scoring_mitra[]' value='"+arr_nilai[0]+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai1_scoring_mitra[]' value='"+nilai_mitra+"' required='required' readonly />";
							output += "	</td>";
							output += "</tr>";
						}
					});
					$(output).appendTo(".table-scoring_mitra tbody");
					$("#caption_scoring_mitra").html("MITRA ("+total_bobot_mitra+"%)");
					$("input[name='total_nilai_mitra']").val(parseFloat(total_nilai_mitra).toFixed(2));
                }								
				//analisis
				let total_bobot_analisis = 0;
				let total_nilai_analisis = 0;
                if (v.arr_data_scoring) {
					let output = "";
					$("#nodata_scoring_analisis").remove();
					$.each(v.arr_data_scoring, function(a, b){
						if(b.jenis_matrix=='analisis'){
							let arr_nilai = b.nilai.split("|");
							let nilai_analisis = parseFloat((arr_nilai[0]*b.bobot)/100).toFixed(2);
							total_bobot_analisis += parseFloat(b.bobot);
							total_nilai_analisis += parseFloat(nilai_analisis);
							
							output += "<tr class='row-scoring_analisis scoring_analisis" + a + "'>";
							output += "	<td align='center'>";
							output += "		<input type='text' class='form-control' name='nama_scoring_analisis[] text-center' value='"+b.nama_matrix+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='bobot_scoring_analisis[]' value='"+numberWithCommas(parseFloat(deviasi_depo_pabrik).toFixed(2))+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai1_scoring_analisis[]' value='"+numberWithCommas(parseFloat(deviasi_depo_pabrik*b.bobot/100).toFixed(2))+"' required='required' readonly />";
							output += "	</td>";
							output += "</tr>";
						}
					});
					$(output).appendTo(".table-scoring_analisis tbody");
					$("#caption_scoring_analisis").html("ANALISIS ("+total_bobot_analisis+"%)");
					$("input[name='total_nilai_analisis']").val(parseFloat(total_nilai_analisis).toFixed(2));
                }	
				//buat nilai total
				let total_nilai_scoring = total_nilai_potensi + total_nilai_mitra + total_nilai_analisis;
				$("input[name='total_nilai_scoring']").val(parseFloat(total_nilai_scoring).toFixed(2));		
				if(total_nilai_scoring<2){
					$("#status_nilai_scoring").html("<button type='button' class='btn btn-sm btn-danger'>TIDAK DIREKOMENDASIKAN</button>");	
				}else if(total_nilai_scoring<=2 && total_nilai_scoring<3){
					$("#status_nilai_scoring").html("<button type='button' class='btn btn-sm btn-success'>DIREKOMENDASIKAN</button>");	
				}else{
					$("#status_nilai_scoring").html("<button type='button' class='btn btn-sm btn-success'>SANGAT DIREKOMENDASIKAN</button>");	
				}	
				
                // if (v.arr_data_master_nilai) {
					// $.each(v.arr_data_master_nilai, function(a, b){
						// // if((b.nilai_awal>=total_nilai_scoring)&&(b.nilai_akhir<=total_nilai_scoring)){
						// if((3.75>=3)&&(4>=3.75)){
							// $("input[name='status_nilai_scoring']").val(b.keterangan);
						// }
						// // $("input[name='status_nilai_scoring']").val(b.nilai_awal);
					// });
					
                // }
				
				
								
				
				//======================		
				//===tab matrix==========		
				//======================		
				//potensi
                if (v.arr_data_matrix) {
					let output = "";
					let total_bobot_potensi = 0;
					$("#nodata_matrix_potensi").remove();
					$.each(v.arr_data_matrix, function(a, b){
						if(b.jenis_matrix=='potensi'){
							total_bobot_potensi += parseFloat(b.bobot);
							let arr_nilai_1 = b.nilai_1.split("|");
							let arr_nilai_2 = b.nilai_2.split("|");
							let arr_nilai_3 = b.nilai_3.split("|");
							let arr_nilai_4 = b.nilai_4.split("|");
							
							output += "<tr class='row-matrix_potensi matrix_potensi" + a + "'>";
							output += "	<td align='center'>";
							output += "		<input type='text' class='form-control' name='nama_matrix_potensi[] text-center' value='"+b.nama_matrix+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='bobot_matrix_potensi[]' value='"+b.bobot+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai1_matrix_potensi[]' value='"+arr_nilai_1[1]+"-"+arr_nilai_1[2]+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai2_matrix_potensi[]' value='"+arr_nilai_2[1]+"-"+arr_nilai_2[2]+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai3_matrix_potensi[]' value='"+arr_nilai_3[1]+"-"+arr_nilai_3[2]+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai4_matrix_potensi[]' value='"+arr_nilai_4[1]+"-"+arr_nilai_4[2]+"' required='required' readonly />";
							output += "	</td>";
							output += "</tr>";
						}
					});
					$(output).appendTo(".table-matrix_potensi tbody");
					$("#caption_potensi").html("POTENSI ("+total_bobot_potensi+"%)");
                }								
				//mitra
                if (v.arr_data_matrix) {
					let output = "";
					let total_bobot_mitra	 = 0;
					let nilai1_value = "-";
					let nilai2_value = "-";
					let nilai3_value = "-";
					let nilai4_value = "-";
					$("#nodata_matrix_mitra").remove();
					$.each(v.arr_data_matrix, function(a, b){
						if(b.jenis_matrix=='mitra'){
							total_bobot_mitra += parseFloat(b.bobot);
							if(b.nilai_1!=null){
								let arr_nilai_1 = b.nilai_1.split("|");
								nilai1_value = arr_nilai_1[0];
							}else{
								nilai1_value = '';
							}
							if(b.nilai_2!=null){
								let arr_nilai_2 = b.nilai_2.split("|");
								nilai2_value = arr_nilai_2[0];
							}else{
								nilai2_value = '';
							}
							if(b.nilai_3!=null){
								let arr_nilai_3 = b.nilai_3.split("|");
								nilai3_value = arr_nilai_3[0];
							}else{
								nilai3_value = '';
							}
							if(b.nilai_4!=null){
								let arr_nilai_4 = b.nilai_4.split("|");
								nilai4_value = arr_nilai_4[0];
							}else{
								nilai4_value = '';
							}
							output += "<tr class='row-matrix_mitra matrix_mitra" + a + "'>";
							output += "	<td align='center'>";
							output += "		<input type='text' class='form-control' name='nama_matrix_mitra[] text-center' value='"+b.nama_matrix+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='bobot_matrix_mitra[]' value='"+b.bobot+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai1_matrix_mitra[]' value='"+nilai1_value+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai2_matrix_mitra[]' value='"+nilai2_value+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai3_matrix_mitra[]' value='"+nilai3_value+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='nilai4_matrix_mitra[]' value='"+nilai4_value+"' required='required' readonly />";
							output += "	</td>";
							output += "</tr>";
						}
					});
					$(output).appendTo(".table-matrix_mitra tbody");
					$("#caption_mitra").html("MITRA ("+total_bobot_mitra+"%)");
                }								
				//analisis
                if (v.arr_data_matrix) {
					let output = "";
					let total_bobot_analisis = 0;
					$("#nodata_matrix_analisis").remove();
					$.each(v.arr_data_matrix, function(a, b){
						if(b.jenis_matrix=='analisis'){
							total_bobot_analisis += parseFloat(b.bobot);
							let arr_nilai_1 = b.nilai_1.split("|");
							let arr_nilai_2 = b.nilai_2.split("|");
							let arr_nilai_3 = b.nilai_3.split("|");
							let arr_nilai_4 = b.nilai_4.split("|");
							
							output += "<tr class='row-matrix_analisis matrix_analisis" + a + "'>";
							output += "	<td align='center'>";
							output += "		<input type='text' class='form-control' name='nama_matrix_analisis[] text-center' value='"+b.nama_matrix+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='bobot_matrix_analisis[]' value='"+b.bobot+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai1_matrix_analisis[]' value='"+arr_nilai_1[1]+"-"+arr_nilai_1[2]+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai2_matrix_analisis[]' value='"+arr_nilai_2[1]+"-"+arr_nilai_2[2]+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai3_matrix_analisis[]' value='"+arr_nilai_3[1]+"-"+arr_nilai_3[2]+"' required='required' readonly />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='form-control text-center' name='nilai4_matrix_analisis[]' value='"+arr_nilai_4[1]+"-"+arr_nilai_4[2]+"' required='required' readonly />";
							output += "	</td>";
							output += "</tr>";
						}
					});
					$(output).appendTo(".table-matrix_analisis tbody");
					$("#caption_analisis").html("ANALISIS PROFITABILITAS ("+total_bobot_analisis+"%)");
                }								
				//nilai
                if (v.arr_data_master_nilai) {
					let output = "";
					$("#nodata_matrix_nilai").remove();
					$.each(v.arr_data_master_nilai, function(a, b){
						output += "<tr class='row-matrix_nilai matrix_nilai" + a + "'>";
						output += "	<td align='center'>";
						output += "		<input type='text' class='form-control' name='range_matrix_nilai[] text-center' value='"+b.nilai_awal+" - "+b.nilai_akhir+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control' name='keterangan_matrix_nilai[]' value='"+b.keterangan+"' required='required' readonly />";
						output += "	</td>";
						output += "</tr>";
					});
					$(output).appendTo(".table-matrix_nilai tbody");
                }								
				
				
				
			});
        },
        error: function (xhr, status, error) {
            // let errorMessage = xhr.status + ': ' + xhr.statusText;
            // KIRANAKU.alert({
                // text: `Server Error, (${errorMessage})`,
                // icon: "error",
                // html: false,
                // reload: false
            // });
        },
        complete: function () {
			$('.form-control').prop('disabled', true);
			$('.btn').prop('disabled', true);
			$('.btn-primary').hide();
			$('.btn-warning').hide();
			// $('.btn-danger').hide();
			$('.btn-default').prop('disabled', false);
			
			//control button approve/decline		
			$('#btn_decline').hide();
			let status_akhir = $("#form_depo_dokumen input[name='status_akhir']").val();
			let level 		 = $("#form_depo_dokumen input[name='level']").val();
			if(status_akhir!=1){
				if(status_akhir==999){
					$('#btn_decline').hide();
				}else{
					if(status_akhir==level){	
						$('#btn_decline').show();
						$('#btn_decline').prop('disabled', false);
					}
				}
			}
			if(status_akhir==level){	
				$('#btn_approve').show();
				$('#btn_approve').prop('disabled', false);
			}
        }
    });

	//save approve
    $(document).on("click", "#save-form-action-depo", function(e) {
		var id_data_temp = $("#id_data_temp").val();
        var empty_form = validate('#form-save-depo');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($("#form-save-depo")[0]);
				// console.log();
				$.ajax({
					url: baseURL + 'depo/transaksi/save/approve',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
								window.location = baseURL + 'depo/transaksi/approve';
								// location.reload();
							});
						} else {
							$("input[name='isproses']").val(0);
							swal('Error', data.msg, 'error');
						}
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

	//save approve
    $(document).on("click", "#save-form-action-depo_", function(e) {
        let action = $("input[name='action']").val();

        if (KIRANAKU.validate("#form-action-fpb")) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                let formData = new FormData($("#form-action-fpb")[0]);
                $.ajax({
                    url: baseURL + 'fpb/order/save/approval',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response) {
                            let icon = "error";
                            let reload = false;

                            if (response.sts == "OK") {
                                icon = "success";
                                reload = true;
                            }

                            if (response.html) {
                                KIRANAKU.alert({
                                    icon: icon,
                                    html: response.msg,
                                    reload: reload
                                });
                            } else {
                                KIRANAKU.alert({
                                    text: response.msg,
                                    icon: icon,
                                    html: false,
                                    reload: reload
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.status + ': ' + xhr.statusText;
                        KIRANAKU.alert({
                            text: `Server Error, (${errorMessage})`,
                            icon: "error",
                            html: false,
                            reload: false
                        });
                    },
                    complete: function() {}
                });
            } else {
                KIRANAKU.alert({
                    text: "Silahkan tunggu proses selesai",
                    icon: "error",
                    html: false,
                    reload: false
                });
            }
        } else {
            KIRANAKU.alert({
                text: "Silahkan lengkapi form terlebih dahulu",
                icon: "error",
                html: false,
                reload: false
            });
        }
    });
	
	//generate nomor pengajuan 
    $(document).on("change", "#jenis_depo, #pabrik", function () {
		let jenis_depo = $("#jenis_depo").val();
		let pabrik = $("#pabrik").val();
        if((jenis_depo!=0)&&(pabrik!=0)){
            $.ajax({
                url: baseURL + "depo/transaksi/get/nomor",
                type: "POST",
                dataType: "JSON",
                data: {
                    jenis_depo: jenis_depo,
                    pabrik: pabrik
                },
                success: function (response) {
                    if (response.sts == "OK") {
                        $("input[name='nomor']").val(response.msg);
						//get input dokumen
						input_dokumen(jenis_depo);
                    }
                }
            });
		}else{
			$("input[name='nomor']").val("");
		}
    });
	
	//triger add jarak depo	- pabrik kompetitor
    $(document).on("click", "button[name='add_pabrik']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-pabrik").length;
			let jenis_pr = $("select[name='jenis_pr']").val();
			let elem = ".row-pabrik.pabrik" + idx;
			let output = "";
			if ($("#nodata_pabrik").length > 0) {
				$("#nodata_pabrik").remove();
			}

			output += "<tr class='row-pabrik pabrik" + idx + "'>";
			output += "	<td>";
			output += "		<input type='text' class='form-control' name='pabrik_kompetitor[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='jarak_pabrik[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_pabrik[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_pabrik[]' style='resize:vertical' rows='3'></textarea>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_pabrik' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-pabrik tbody");
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item_pabrik", function (e) {
        if ($("tr.row-pabrik").length > 1) {
            $(this).closest("tr.row-pabrik").remove();
        }

        $("tr.row-pabrik").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-pabrik");
            $(this).addClass("pabrik" + i);
        });

        if ($(".table-pabrik tbody tr").length == 0) {
            show_nodata_pabrik();
        }
    });
	
	//triger add jarak depo	- gudang kompetitor
    $(document).on("click", "button[name='add_gudang']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-gudang").length;
			let elem = ".row-gudang.gudang" + idx;
			let output = "";
			if ($("#nodata_gudang").length > 0) {
				$("#nodata_gudang").remove();
			}

			output += "<tr class='row-gudang gudang" + idx + "'>";
			output += "	<td>";
			output += "		<input type='text' class='form-control' name='gudang_kompetitor[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='jarak_gudang[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_gudang[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_gudang[]' style='resize:vertical' rows='3'></textarea>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_gudang' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-gudang tbody");
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item_gudang", function (e) {
        if ($("tr.row-gudang").length > 1) {
            $(this).closest("tr.row-gudang").remove();
        }

        $("tr.row-gudang").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-gudang");
            $(this).addClass("gudang" + i);
        });

        if ($(".table-gudang tbody tr").length == 0) {
            show_nodata_gudang();
        }
    });

	//triger add desa
    $(document).on("click", "button[name='add_desa']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-desa").length;
			let elem = ".row-desa.desa" + idx;
			let output = "";
			if ($("#nodata_desa").length > 0) {
				$("#nodata_desa").remove();
			}

			output += "<tr class='row-desa desa" + idx + "'>";
			output += "	<td>";
			output += "		<input type='text' class='form-control' name='nama_desa[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='luas_desa[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_desa[]' style='resize:vertical' rows='3'></textarea>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_desa' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-desa tbody");
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item_desa", function (e) {
        if ($("tr.row-desa").length > 1) {
            $(this).closest("tr.row-desa").remove();
        }

        $("tr.row-desa").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-desa");
            $(this).addClass("desa" + i);
        });

        if ($(".table-desa tbody tr").length == 0) {
            show_nodata_desa();
        }
    });
	
	//triger add survei
    $(document).on("click", "button[name='add_survei']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-survei").length;
			let elem = ".row-survei.survei" + idx;
			let output = "";
			if ($("#nodata_survei").length > 0) {
				$("#nodata_survei").remove();
			}

			output += "<tr class='row-survei survei" + idx + "'>";
			output += "	<td>";
			output += "		<input type='text' class='form-control tanggal' name='tanggal_survei[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='harga_per_hari_survei[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='harga_notarin_survei[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='harga_sicom_survei[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='total_produksi_survei[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='rata_rata_survei[]' value='' required='required' />";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_survei' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-survei tbody");
			
			$('.tanggal').datepicker({ 
				format: 'dd.mm.yyyy',
				changeMonth: true,
				changeYear: true, 
				autoclose: true
			}); 
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}
    });
    $(document).on("click", ".remove_item_survei", function (e) {
        if ($("tr.row-survei").length > 1) {
            $(this).closest("tr.row-survei").remove();
        }

        $("tr.row-survei").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-survei");
            $(this).addClass("survei" + i);
        });

        if ($(".table-survei tbody tr").length == 0) {
            show_nodata_survei();
        }
    });

	//triger add jarak depo	- depo KMG
    $(document).on("click", "button[name='add_depo']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-summary").length;
			let elem = ".row-summary.summary" + idx;
			let output = "";
			if ($("#nodata").length > 0) {
				$("#nodata").remove();
			}

			output += "<tr class='row-summary summary" + idx + "'>";
			output += "	<td>";
			output += "		<select class='form-control select2 autocomplete' name='id_depo[]' required='required'>";
			output += "			<option></option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='jarak_depo[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_depo[]' style='resize:vertical' rows='3'></textarea>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-summary tbody");
			//autocomplete depo KMG
			master_depo(elem + " select[name='id_depo[]']");
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item", function (e) {
        if ($("tr.row-summary").length > 1) {
            $(this).closest("tr.row-summary").remove();
        }

        $("tr.row-summary").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-summary");
            $(this).addClass("summary" + i);
        });

        if ($(".table-summary tbody tr").length == 0) {
            show_nodata();
        }
    });
    $(document).on('select2:clear', "select[name='id_depo[]']", function (e) {
        $(this).closest("tr.row-summary").find("input[name='waktu_depo[]']").val("");
        $(this).closest("tr.row-summary").find("input[name='jarak_depo[]']").val("");
    });
	
	//hitung target depo
    $(document).on("keyup", "input[name*='target_m']", function() {
		let total  = 0;
		let jumlah = 0;
		$("#form_depo_peta input[name^='target_m']").each(function(i) {
			console.log($(this).val().replace(/,/g, ""));
			total  += +$(this).val().replace(/,/g, "");
			jumlah += 1;
		});
		avg = (total/jumlah).toFixed(2);
		$("input[name='avg_target']").val(numberWithCommas(avg));	
		
		//generate_nilai_tonase_depo
		generate_nilai_tonase_depo();
    });
	
	//triger add biaya depo
    $(document).on("click", "button[name='add_biaya_depo']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-biaya_depo").length;
			let jenis_depo = $("select[name='jenis_depo']").val();
			let elem = ".row-biaya_depo.biaya_depo" + idx;
			let output = "";
			if ($("#nodata_biaya_depo").length > 0) {
				$("#nodata_biaya_depo").remove();
			}

			output += "<tr class='row-biaya_depo biaya_depo" + idx + "'>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='id_biaya_depo[]' required='required'>";
			output += "			<option></option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='biaya_depo[]' value='' placeholder='Biaya'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='tonase_depo[]' value='' placeholder='Tonase'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='total_depo[]' value='' placeholder='Total'  required='required' />";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_depo' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-biaya_depo tbody");
			if(jenis_depo=='mitra'){
				//get master biaya depo mitra
				master_biaya(elem + " select[name='id_biaya_depo[]']", 'mitra','operational','transaksi');
				
			}else{
				//get master biaya depo tetap
				master_biaya(elem + " select[name='id_biaya_depo[]']", 'tetap','operational','transaksi');
			}
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item_biaya_depo", function (e) {
        if ($("tr.row-biaya_depo").length > 1) {
            $(this).closest("tr.row-biaya_depo").remove();
        }

        $("tr.row-biaya_depo").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-biaya_depo");
            $(this).addClass("biaya_depo" + i);
        });

        if ($(".table-biaya_depo tbody tr").length == 0) {
            show_nodata_biaya_depo();
        }
    });
	
    $(document).on('select2:clear', "select[name='id_biaya_depo[]']", function (e) {
        $(this).closest("tr.row-biaya_depo").find("input[name='biaya_depo[]']").val("");
        $(this).closest("tr.row-biaya_depo").find("input[name='tonase_depo[]']").val("");
        $(this).closest("tr.row-biaya_depo").find("input[name='total_depo[]']").val("");
    });
	//sampe sini
	
	//triger add biaya sdm
    $(document).on("click", "button[name='add_biaya_sdm']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-biaya_sdm").length;
			let elem = ".row-biaya_sdm.biaya_sdm" + idx;
			let output = "";
			if ($("#nodata_biaya_sdm").length > 0) {
				$("#nodata_biaya_sdm").remove();
			}

			output += "<tr class='row-biaya_sdm biaya_sdm" + idx + "'>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='id_biaya_sdm[]' required='required'>";
			output += "			<option></option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='jenis_budget_sdm[]' required='required'  data-placeholder='Pilih Status'>";
			output += "			<option ></option>";
			output += "			<option value='budget'>Budget</option>";
			output += "			<option value='unbudget'>Unbudget</option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='form-control text-center' name='nik_sdm[]' value='' placeholder='NIK'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='form-control' name='nama_sdm[]' value='' placeholder='Nama' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='gaji_pokok_sdm[]' value='' placeholder='Gaji Pokok' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='tunjangan_sdm[]' value='' placeholder='Tunjangan' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='status_sdm[]' required='required' data-placeholder='Pilih Status'>";
			output += "			<option ></option>";
			output += "			<option value='tetap'>Tetap</option>";
			output += "			<option value='kontrak'>Kontrak</option>";
			output += "		</select>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_sdm' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-biaya_sdm tbody");
			master_biaya(elem + " select[name='id_biaya_sdm[]']", 'all','operational','sdm');
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item_biaya_sdm", function (e) {
        if ($("tr.row-biaya_sdm").length > 1) {
            $(this).closest("tr.row-biaya_sdm").remove();
        }

        $("tr.row-biaya_sdm").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-biaya_sdm");
            $(this).addClass("biaya_sdm" + i);
        });

        if ($(".table-biaya_sdm tbody tr").length == 0) {
            show_nodata_biaya_sdm();
        }
    });
	
	
    $(document).on('select2:clear', "select[name='id_biaya_sdm[]']", function (e) {
        $(this).closest("tr.row-biaya_sdm").find("input[name='jenis_budget_biaya_sdm[]']").val("");
        $(this).closest("tr.row-biaya_sdm").find("input[name='nik_biaya_sdm[]']").val("");
        $(this).closest("tr.row-biaya_sdm").find("input[name='nama_biaya_sdm[]']").val("");
        $(this).closest("tr.row-biaya_sdm").find("input[name='gapok_biaya_sdm[]']").val("");
        $(this).closest("tr.row-biaya_sdm").find("input[name='tunjangan_biaya_sdm[]']").val("");
        $(this).closest("tr.row-biaya_sdm").find("input[name='status_biaya_sdm[]']").val("");
    });
	//sampe sini
	
	//triger add biaya investasi
    $(document).on("click", "button[name='add_biaya_investasi']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-biaya_investasi").length;
			let jenis_pr = $("select[name='jenis_pr']").val();
			let elem = ".row-biaya_investasi.biaya_investasi" + idx;
			let output = "";
			if ($("#nodata_biaya_investasi").length > 0) {
				$("#nodata_biaya_investasi").remove();
			}
			output += "<tr class='row-biaya_investasi biaya_investasi" + idx + "'>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='id_biaya_investasi[]' required='required'>";
			output += "			<option></option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='kepemilikan_investasi[]' required='required'  data-placeholder='Pilih Status'>";
			output += "			<option ></option>";
			output += "			<option value='ada'>Ada</option>";
			output += "			<option value='tidak_ada'>Tidak Ada</option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='jumlah_investasi[]' value='' placeholder='QTY'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control' name='harga_investasi[]' value='' placeholder='Rp/ Unit' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='total_investasi[]' value='' placeholder='Total' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<textarea class='form-control textarea-limit-per-row' data-limit-per-row='100' name='keterangan_investasi[]' style='resize:vertical' rows='3'></textarea>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_investasi' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-biaya_investasi tbody");
			master_biaya(elem + " select[name='id_biaya_investasi[]']", 'all','investasi',0);
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item_biaya_investasi", function (e) {
        if ($("tr.row-biaya_investasi").length > 1) {
            $(this).closest("tr.row-biaya_investasi").remove();
        }

        $("tr.row-biaya_investasi").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-biaya_investasi");
            $(this).addClass("biaya_investasi" + i);
        });

        if ($(".table-biaya_investasi tbody tr").length == 0) {
            show_nodata_biaya_investasi();
        }
    });
    $(document).on('select2:clear', "select[name='id_biaya_investasi[]']", function (e) {
        $(this).closest("tr.row-biaya_investasi").find("input[name='jenis_budget_biaya_investasi[]']").val("");
        $(this).closest("tr.row-biaya_investasi").find("input[name='nik_biaya_investasi[]']").val("");
        $(this).closest("tr.row-biaya_investasi").find("input[name='nama_biaya_investasi[]']").val("");
        $(this).closest("tr.row-biaya_investasi").find("input[name='gapok_biaya_investasi[]']").val("");
        $(this).closest("tr.row-biaya_investasi").find("input[name='tunjangan_biaya_investasi[]']").val("");
        $(this).closest("tr.row-biaya_investasi").find("input[name='status_biaya_investasi[]']").val("");
    });
	//sampe sini
	
	//triger add biaya tranportasi darat
    $(document).on("click", "button[name='add_biaya_darat']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-biaya_darat").length;
			let jenis_pr = $("select[name='jenis_pr']").val();
			let elem = ".row-biaya_darat.biaya_darat" + idx;
			let output = "";
			if ($("#nodata_biaya_darat").length > 0) {
				$("#nodata_biaya_darat").remove();
			}
			output += "<tr class='row-biaya_darat biaya_darat" + idx + "'>";
			output += "	<td>";
			output += "		<input type='text' class='form-control' name='nama_vendor_darat[]' value='' placeholder='Nama Vendor'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='penentuan_tarif_darat[]' required='required'  data-placeholder='Pilih Status'>";
			output += "			<option ></option>";
			output += "			<option value='pabrik'>Pabrik</option>";
			output += "			<option value='depo'>Depo</option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='kapasitas_basah_darat[]' required='required'  data-placeholder='Pilih Status'>";
			output += "			<option ></option>";
			output += "			<option value='1'>< 8 Ton</option>";
			output += "			<option value='2'>8 - 10 Ton</option>";
			output += "			<option value='3'>10 - 20 Ton</option>";
			output += "			<option value='4'>20 - 25 Ton</option>";
			output += "			<option value='5'>25 - 35 Ton</option>";
			output += "			<option value='6'>35 - 45 Ton</option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control' name='biaya_per_trip_darat[]' value='' placeholder='Rp/ Unit' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='biaya_per_kg_darat[]' value='' placeholder='Total' required='required' />";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_darat' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-biaya_darat tbody");
			// master_biaya(elem + " select[name='id_biaya_darat[]']", 'all','darat',0);
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item_biaya_darat", function (e) {
        if ($("tr.row-biaya_darat").length > 1) {
            $(this).closest("tr.row-biaya_darat").remove();
        }

        $("tr.row-biaya_darat").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-biaya_darat");
            $(this).addClass("biaya_darat" + i);
        });

        if ($(".table-biaya_darat tbody tr").length == 0) {
            show_nodata_biaya_darat();
        }
    });
    $(document).on('select2:clear', "select[name='id_biaya_darat[]']", function (e) {
        $(this).closest("tr.row-biaya_darat").find("input[name='jenis_budget_biaya_darat[]']").val("");
        $(this).closest("tr.row-biaya_darat").find("input[name='nik_biaya_darat[]']").val("");
        $(this).closest("tr.row-biaya_darat").find("input[name='nama_biaya_darat[]']").val("");
        $(this).closest("tr.row-biaya_darat").find("input[name='gapok_biaya_darat[]']").val("");
        $(this).closest("tr.row-biaya_darat").find("input[name='tunjangan_biaya_darat[]']").val("");
        $(this).closest("tr.row-biaya_darat").find("input[name='status_biaya_darat[]']").val("");
    });
	//sampe sini	
	
	//triger add biaya tranportasi air
    $(document).on("click", "button[name='add_biaya_air']", function () {
		let nomor = $("input[name='nomor']").val();
		if(nomor){
			let idx = $("tr.row-biaya_air").length;
			let elem = ".row-biaya_air.biaya_air" + idx;
			let output = "";
			if ($("#nodata_biaya_air").length > 0) {
				$("#nodata_biaya_air").remove();
			}
			output += "<tr class='row-biaya_air biaya_air" + idx + "'>";
			output += "	<td>";
			output += "		<input type='text' class='form-control' name='nama_vendor_air[]' value='' placeholder='Nama Vendor'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control select2' name='kapasitas_basah_air[]' required='required'  data-placeholder='Pilih Status'>";
			output += "			<option ></option>";
			output += "			<option value='1'>< 300 Ton</option>";
			output += "			<option value='2'>300 - 500 Ton</option>";
			output += "			<option value='3'>500 - 700 Ton</option>";
			output += "			<option value='4'>> 700 Ton</option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control' name='biaya_per_trip_air[]' value='' placeholder='Rp/ Unit' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='biaya_per_kg_air[]' value='' placeholder='Total' required='required' />";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_air' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-biaya_air tbody");
			// master_biaya(elem + " select[name='id_biaya_air[]']", 'all','darat',0);
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item_biaya_air", function (e) {
        if ($("tr.row-biaya_air").length > 1) {
            $(this).closest("tr.row-biaya_air").remove();
        }

        $("tr.row-biaya_air").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-biaya_air");
            $(this).addClass("biaya_air" + i);
        });

        if ($(".table-biaya_air tbody tr").length == 0) {
            show_nodata_biaya_air();
        }
    });
    $(document).on('select2:clear', "select[name='id_biaya_air[]']", function (e) {
        $(this).closest("tr.row-biaya_air").find("input[name='jenis_budget_biaya_air[]']").val("");
        $(this).closest("tr.row-biaya_air").find("input[name='nik_biaya_air[]']").val("");
        $(this).closest("tr.row-biaya_air").find("input[name='nama_biaya_air[]']").val("");
        $(this).closest("tr.row-biaya_air").find("input[name='gapok_biaya_air[]']").val("");
        $(this).closest("tr.row-biaya_air").find("input[name='tunjangan_biaya_air[]']").val("");
        $(this).closest("tr.row-biaya_air").find("input[name='status_biaya_air[]']").val("");
    });
	//sampe sini
	

    $(document).on("click", "button[name='action_btn']", function(e) {
        generate_modal_action($(this));
    });
	
	// $(document).on("click", "button[name='action_btn']", function(e){
		// // let action 	 = $(this).data('action');
		// // $(".nav-tabs a[href='#"+action+"']").tab('show');
		// let act 	 = $(this).data('act');
		// let action 	 = $(this).data('action');
		// let back 	 = $(this).data('back');
		// if(back=='yes'){
			// $(".nav-tabs a[href='#"+action+"']").tab('show');
		// }else{
			// var empty_form = validate("#"+$(this).val());
			// if( empty_form == 0){
				// var isproses = $("input[name='isproses']").val();
				// if(isproses == 0){
					// if ($("#form_depo_supplier .table-summary .row-summary").length == 0) {	//cek inputan jarak depo
						// kiranaAlert("notOK", 'Jarak dan Waktu (Depo - Depo KMG Minimal Input 1 Data)', "error", "no");	
					// }else if($("#form_depo_supplier .table-gudang .row-gudang").length == 0){ //cek inputan jarak gudang kompetitor
						// kiranaAlert("notOK", 'Jarak dan Waktu (Depo - Gudang Kompetitor Terdekat Minimal Input 1 Data)', "error", "no");	
					// }else if($("#form_depo_supplier .table-pabrik .row-pabrik").length == 0){ //cek inputan jarak pabrik kompetitor
						// kiranaAlert("notOK", 'Jarak dan Waktu (Depo - Pabrik Kompetitor Terdekat Minimal Input 1 Data)', "error", "no");	
					// }else{
						// $("input[name='isproses']").val(1);
						// var formData = new FormData($("#"+$(this).val())[0]);
						// $.ajax({
							// url: baseURL+'depo/transaksi/save/depo/'+$(this).data('link'),
							// type: 'POST',
							// dataType: 'JSON',
							// data: formData,
							// contentType: false,
							// cache: false,
							// processData: false,
							// success: function(data){
								// if (data.sts == 'OK') {
									// if(act=='finish'){
										// kiranaAlert(data.sts, data.msg);
										// window.location = baseURL + 'depo/transaksi/approve';
									// }else{
										// kiranaAlert(data.sts, data.msg, "success", "no");
										// $(".nav-tabs a[href='#"+action+"']").tab('show');
									// }
								// } else {
									// $("input[name='isproses']").val(0);
									// kiranaAlert("notOK", data.msg, "error", "no");	
								// }
							// }
						// });
					// }
				// }else{
					// kiranaAlert("notOK", "Silahkan tunggu proses selesai.", "info", "no");	
				// }
			// }			
		// }	
		// e.preventDefault();
		// return false;
    // });
	
	// //button save
    // $(document).on("click", "button[name='action_btn']", function () {
		// $("input[name='action']").val($(this).attr("data-btn").toLowerCase());
        // if ($(".table-summary .row-summary").length > 0) {
			// submit_order();
        // } else {
            // KIRANAKU.alert({
                // text: "List order masih kosong",
                // icon: "error",
                // html: false,
                // reload: false
            // });
        // }
    // });
	
	
});

function show_nodata_pabrik() {
    let col_not_found = $(".table-pabrik thead th").not(".d-none").length;
    $(".table-pabrik tbody").html('<tr id="nodata"><td colspan="' + col_not_found + '">No data found</td></tr>');
}

function show_nodata_desa() {
    let col_not_found = $(".table-desa thead th").not(".d-none").length;
    $(".table-desa tbody").html('<tr id="nodata"><td colspan="' + col_not_found + '">No data found</td></tr>');
}

function show_nodata_survei() {
    let col_not_found = $(".table-survei thead th").not(".d-none").length;
    $(".table-survei tbody").html('<tr id="nodata"><td colspan="' + col_not_found + '">No data found</td></tr>');
}

function show_nodata_gudang() {
    let col_not_found = $(".table-gudang thead th").not(".d-none").length;
    $(".table-gudang tbody").html('<tr id="nodata"><td colspan="' + col_not_found + '">No data found</td></tr>');
}

function show_nodata() {
    let col_not_found = $(".table-summary thead th").not(".d-none").length;
    $(".table-summary tbody").html('<tr id="nodata"><td colspan="' + col_not_found + '">No data found</td></tr>');
}
function show_nodata_biaya_depo() {
    let col_not_found = $(".table-biaya_depo thead th").not(".d-none").length;
    $(".table-biaya_depo tbody").html('<tr id="nodata_biaya_depo"><td colspan="' + col_not_found + '">No data found</td></tr>');
}
function show_nodata_biaya_sdm() {
    let col_not_found = $(".table-biaya_sdm thead th").not(".d-none").length;
    $(".table-biaya_sdm tbody").html('<tr id="nodata_biaya_sdm"><td colspan="' + col_not_found + '">No data found</td></tr>');
}
function show_nodata_biaya_investasi() {
    let col_not_found = $(".table-biaya_investasi thead th").not(".d-none").length;
    $(".table-biaya_investasi tbody").html('<tr id="nodata_biaya_investasi"><td colspan="' + col_not_found + '">No data found</td></tr>');
}
function show_nodata_biaya_darat() {
    let col_not_found = $(".table-biaya_darat thead th").not(".d-none").length;
    $(".table-biaya_darat tbody").html('<tr id="nodata_biaya_investasi"><td colspan="' + col_not_found + '">No data found</td></tr>');
}
function show_nodata_biaya_air() {
    let col_not_found = $(".table-biaya_air thead th").not(".d-none").length;
    $(".table-biaya_air tbody").html('<tr id="nodata_biaya_investasi"><td colspan="' + col_not_found + '">No data found</td></tr>');
}

function master_depo(elem) {
    if ($(elem).hasClass("select2-hidden-accessible")) {
        $(elem).select2("destroy");
    }

    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        ajax: {
            url: baseURL + "depo/transaksi/get/master_depo",
            dataType: "json",
            delay: 750,
            cache: false,
            data: function (params) {
				console.log(data);
                let selected_id_depo = [];
				$("select[name='id_depo[]']").each(function (i, v) {
                    selected_id_depo.push($(v).val());
                });
                let data = {
                    pabrik: $("select[name='pabrik']").val(),
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
                    not_in_depo: selected_id_depo
                };

                return data;
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function (xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                KIRANAKU.alert({
                    text: `Server Error, (${errorMessage})`,
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            return `<div class="clearfix">[${repo.id}] ${repo.nama_depo}</div>`;
        },
        templateSelection: function (repo) {
			
            let markup = "Silahkan Pilih";
            if (repo.nama_depo)
                markup = `[${repo.id}] ${repo.nama_depo}`;
            if (repo.text)
                markup = repo.text;

            return markup;
        }
    });
}

function master_biaya(elem, jenis_depo, jenis_biaya, jenis_biaya_detail) {
    if ($(elem).hasClass("select2-hidden-accessible")) {
        $(elem).select2("destroy");
    }

    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        ajax: {
            // url: baseURL + "depo/transaksi/get/master_depo",
            url: baseURL + "depo/transaksi/get/master_biaya",
            dataType: "json",
            delay: 750,
            cache: false,
            data: function (params) {
                let selected_id_biaya = [];
				$("select[name='id_depo[]']").each(function (i, v) {
                    selected_id_biaya.push($(v).val());
                });
                let data = {
                    jenis_depo: jenis_depo,
                    jenis_biaya: jenis_biaya,
                    jenis_biaya_detail: jenis_biaya_detail,
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
                    not_in_biaya: selected_id_biaya
                };

                return data;
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function (xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                KIRANAKU.alert({
                    text: `Server Error, (${errorMessage})`,
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            return `<div class="clearfix">${repo.nama_biaya}</div>`;
        },
        templateSelection: function (repo) {
			
            let markup = "Silahkan Pilih";
            if (repo.nama_biaya)
                markup = `${repo.nama_biaya}`;
            if (repo.text)
                markup = repo.text;

            return markup;
        }
    });
}
function submit_order() {
    if (KIRANAKU.validate("#form-depo-input")) {
        let isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
			$("input[name='isproses']").val(1);
			const formData = new FormData($("#form-depo-input")[0]);
			$.ajax({
				// url: baseURL + "fpb/order/save/fpbxx",
				url: baseURL + "depo/transaksi/save/input",
				type: "POST",
				dataType: "JSON",
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				beforeSend: function () { },
				error: function (xhr, status, error) {
					let errorMessage = xhr.status + ': ' + xhr.statusText;
					KIRANAKU.alert({
						text: `Server Error, (${errorMessage})`,
						icon: "error",
						html: false,
						reload: false
					});
				},
				success: function (response) {
					
					if (response) {
						let icon = "error";
						let reload = false;

						if (response.sts == "OK") {
							icon = "success";
							reload = baseURL + "fpb/order/data/approve";
						}

						if (response.html) {
							KIRANAKU.alert({
								icon: icon,
								html: response.msg,
								reload: reload
							});
						} else {
							KIRANAKU.alert({
								text: response.msg,
								icon: icon,
								html: false,
								reload: reload
							});
						}
					}
				},
				complete: function () { }
			});
			
        } else {
            KIRANAKU.alert({
                text: "Silahkan tunggu proses selesai",
                icon: "warning",
                html: false,
                reload: false
            });
        }
    } else {
        KIRANAKU.alert({
            text: "Silahkan lengkapi form terlebih dahulu",
            icon: "error",
            html: false,
            reload: false
        });
    }
}

function generate_modal_action(elem) {
    $('#KiranaModals .modal-dialog').removeClass("modal-lg");
    $('#KiranaModals .modal-dialog').removeClass("modal-xl");
    $("#KiranaModals .modal-content").removeClass("bg-success");
    $("#KiranaModals .modal-content").removeClass("bg-warning");
    $("#KiranaModals .modal-content").removeClass("bg-info");
    $("#KiranaModals .modal-content").removeClass("bg-danger");
    let jenis_depo 	 = $("#form_depo_supplier select[name='jenis_depo']").val();
    let nomor 		 = $("#form_depo_dokumen input[name='nomor']").val();
    let status_akhir = $("#form_depo_dokumen input[name='status_akhir']").val();
    let action 		 = elem.val();
    switch (action) {
        case "approve":
            $("#KiranaModals .modal-content").addClass("bg-success");
            break;
        case "decline":
            $("#KiranaModals .modal-content").addClass("bg-warning");
            break;
    }
    $("#KiranaModals .modal-title").css("text-transform", "capitalize");
    $("#KiranaModals .modal-title").html(action + " Master Depo (" + nomor + ")");

    let output = '';
    output += '<div class="row">';
    output += ' <div class="col-sm-12">';
    output += '     <form role="form" id="form-save-depo">';
    output += '         <div class="form-group">';
    output += '             <label>Komentar</label>';
    output += '             <textarea class="form-control" name="komentar_approve_depo" required="required"></textarea>';
    output += '             <input type="hidden" name="nomor">';
    output += '             <input type="hidden" name="action">';
    output += '             <input type="hidden" name="status_akhir">';
    output += '             <input type="hidden" name="jenis_depo">';
    output += '         </div>';
    output += '     </form>';
    output += ' </div>';
    output += '</div>';
    $("#KiranaModals .modal-body").html(output);

    if (action == 'approve') {
        $("#KiranaModals textarea[name='komentar_approve_depo']").removeAttr("required");
    } else {
        $("#KiranaModals textarea[name='komentar_approve_depo']").attr("required", "required");
    }

    let output_footer = '';
    output_footer += '<div class="modal-footer">';
	if(action=='approve')
    output_footer += '  <button type="button" class="btn btn-primary" id="save-form-action-depo">Approve</button>';
	if(action=='decline')
    output_footer += '  <button type="button" class="btn btn-danger" id="save-form-action-depo">Decline</button>';
    output_footer += '</div>';
    if ($("#KiranaModals .modal-footer").length > 0) {
        $("#KiranaModals .modal-footer").remove();
    }
    $('#KiranaModals .modal-content').append(output_footer);

    $("#KiranaModals input[name='nomor']").val(nomor);
    $("#KiranaModals input[name='action']").val(action);
    $("#KiranaModals input[name='status_akhir']").val(status_akhir);
    $("#KiranaModals input[name='jenis_depo']").val(jenis_depo);


    $('#KiranaModals').modal({
        backdrop: 'static',
        keyboard: true,
        show: true
    });

    KIRANAKU.select2('#KiranaModals');
}


