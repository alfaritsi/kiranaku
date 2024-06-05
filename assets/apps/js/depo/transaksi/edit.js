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
						// var default_img = baseURL+'assets/apps/img/test/dummy.png';
						
						var id_foto = 'id_foto'+a;
						var view_foto = 'view_foto'+a;
						var file_foto = 'file_foto'+a;
						output += '<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">';
						output += '	<div id="product' + a + '" class="product-thumb transition">';
						output += '		<div class="image">';
						output += '			<img id="'+ id_foto +'" alt="Preview Image" src="' + default_img + '" width="300px" height="300px">'; 
						output += '		</div>';
						output += '		<div class="button-group">';
						output += '			<button type="button" class="btn btn-default col-lg-12 col-md-12 col-sm-12 col-xs-12">FOTO <span>'+b.nama_gambar+'*</span></button>';
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
                if (v.arr_data_survei) {
					let no = 0;
					let output = "";
					let avg_harga_per_hari_survei = 0;
					let avg_harga_notarin_survei = 0;
					let avg_harga_sicom_survei = 0;
					let avg_total_produksi_survei = 0;
					let avg_rata_rata_survei = 0;
					$("#nodata_survei").remove();
					$.each(v.arr_data_survei, function(a, b){
						output += "<tr class='row-survei survei" + no + "'>";
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
						avg_harga_per_hari_survei += +b.harga_per_hari;
						avg_harga_notarin_survei += +b.harga_notarin;
						avg_harga_sicom_survei += +b.harga_sicom;
						avg_total_produksi_survei += +b.total_produksi;
						avg_rata_rata_survei += +b.rata_rata;
						no++;
					});
					$(output).appendTo(".table-survei tbody");
					//footer
					$("input[name='avg_harga_per_hari_survei']").val(numberWithCommas((avg_harga_per_hari_survei/no).toFixed(2)));
					$("input[name='avg_harga_notarin_survei']").val(numberWithCommas((avg_harga_notarin_survei/no).toFixed(2)));
					$("input[name='avg_harga_sicom_survei']").val(numberWithCommas((avg_harga_sicom_survei/no).toFixed(2)));
					$("input[name='avg_total_produksi_survei']").val(numberWithCommas((avg_total_produksi_survei/no).toFixed(2)));
					$("input[name='avg_rata_rata_survei']").val(numberWithCommas((avg_rata_rata_survei/no).toFixed(2)));

					
					$('.tanggal').datepicker({ 
						format: 'dd.mm.yyyy',
						changeMonth: true,
						changeYear: true, 
						autoclose: true
					}); 
                }				
				//detail-potensi-survei
                if (v.arr_data_target) {
					$.each(v.arr_data_target, function(a, b){
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
						let avg_target = (b.m1+b.m2+b.m3+b.m4+b.m5+b.m6+b.m7+b.m8+b.m9+b.m10+b.m11+b.m12)/12;
						$("input[name='avg_target']").val(numberWithCommas(avg_target.toFixed(2)));
					});
					
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
							output += "		<input type='text' class='angka form-control text-center nilai_tonase_depo' name='tonase_depo[]' value='"+numberWithCommas(parseFloat(b.tonase).toFixed(2))+"' placeholder='Tonase'  required='required' readonly/>";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='total_depo[]' value='"+numberWithCommas(parseFloat(b.biaya/b.tonase).toFixed(2))+"' placeholder='Total'  required='required' />";
							output += "	</td>";
							output += '	<td class="text-center">';
							output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_depo' title='Remove'><i class='fa fa-trash-o'></i></button>";
							output += "	</td>";
							output += "</tr>";
							$(output).appendTo(".table-biaya_depo tbody");

							const elem = ".row-biaya_depo.biaya_depo" + b.id_biaya;
							if(v.jenis_depo=='mitra'){
								master_biaya(elem + " select[name='id_biaya_depo[]']", 'mitra','operational','transaksi');
								// $("#form_depo_biaya  input[name='tonase_depo[]']").prop('readonly', false);
								// $(".nilai_tonase_depo").val(0);
								generate_nilai_tonase_depo();
								
							}else{
								master_biaya(elem + " select[name='id_biaya_depo[]']", 'tetap','operational','transaksi');
								// $("#form_depo_biaya input[name='tonase_depo[]']").prop('readonly', true);
								// //hitung tonase kg basah(depo tetap)
								generate_nilai_tonase_depo();
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
                if (v.arr_data_biaya_sdm) {
					let no = 0;
					$("#nodata_biaya_sdm").remove();
					$.each(v.arr_data_biaya_sdm, function(a, b){
							no++;
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
							output += "		<input type='text' class='angka form-control text-center' name='tunjangan_sdm[]' value='"+numberWithCommas(b.tunjangan)+"' placeholder='Tunjangan'/>";
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
                }								
				
				//detail-biaya-trans-darat
                if (v.arr_data_biaya_trans) {
					let output = "";
					let no = 0;
					$("#nodata_biaya_darat").remove();
					$.each(v.arr_data_biaya_trans, function(a, b){
						if(b.jenis_trans =='darat'){
							no++;
							output += "<tr class='row-biaya_darat biaya_darat" + no + "'>";
							output += "	<td>";
							output += "		<input type='text' class='form-control' name='nomor_vendor_darat[]' value='"+b.nomor_vendor+"' placeholder='Nama Vendor'  required='required' />";
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
							output += "		<input type='text' class='angka form-control' name='biaya_per_trip_darat[]' value='"+b.biaya_per_trip+"' placeholder='Rp/ Unit' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='biaya_per_kg_darat[]' value='"+b.biaya_per_kg+"' placeholder='Total' required='required' />";
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
                if (v.arr_data_biaya_trans) {
					let output = "";
					let no = 0;
					$("#nodata_biaya_air").remove();
					$.each(v.arr_data_biaya_trans, function(a, b){
						if(b.jenis_trans =='air'){
							no++;
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
							output += "		<input type='text' class='angka form-control' name='biaya_per_trip_air[]' value='"+b.biaya_per_trip+"' placeholder='Rp/ Unit' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='biaya_per_kg_air[]' value='"+b.biaya_per_kg+"' placeholder='Total' required='required' />";
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
                if (v.arr_data_biaya_investasi) {
					let no = 0;
					$("#nodata_biaya_investasi").remove();
					$.each(v.arr_data_biaya_investasi, function(a, b){
							no++;
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
							output += "		<input type='text' class='angka form-control text-center' name='jumlah_investasi[]' value='"+numberWithCommas(parseFloat(b.jumlah).toFixed(0))+"' placeholder='QTY'  required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='harga_investasi[]' value='"+numberWithCommas(parseFloat(b.harga).toFixed(0))+"' placeholder='Rp/ Unit' required='required' />";
							output += "	</td>";
							output += "	<td>";
							output += "		<input type='text' class='angka form-control text-center' name='total_investasi[]' value='"+numberWithCommas(parseFloat(b.total).toFixed(0))+"' placeholder='Total' readonly />";
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
						var required = (b.mandatory=='y') ? 'required':'';
						output += "<tr class='row-lampiran_dokumen lampiran_dokumen" + a + "'>";
						output += "	<td>";
						output += "		<input type='text' class='form-control' name='nama_dokumen[]' value='"+b.nama_dokumen+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control' name='mandatory_dokumen[]' value='"+mandatory+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += '		<input type="hidden" name="id_dokumen[]" class="form-control" value="'+b.id_dokumen+'">'; 
						output += '		<input type="file" name="'+file_lampiran+'[]" class="form-control" id="'+ file_lampiran +'"  '+required+'>';
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
				
				//
				if(v.jenis_depo=='tetap'){
					$('#label_nip').html("NIP");
					$("#form_depo_supplier input[name='nip']").prop('required', false);
					$('#label_alamat_rumah').html("Alamat Rumah");
					$("#form_depo_supplier textarea[name='alamat_rumah']").prop('required', false);
					$('#label_pekerjaan').html("Pekerjaan");
					$("#form_depo_supplier input[name='pekerjaan']").prop('required', false);
					$('#label_status_kepemilikan_tanah').html("Status Kepemilikan Tanah");
					$("#form_depo_supplier select[name='status_kepemilikan_tanah']").prop('required', false);
					$('#label_status_sertifikat_tanah').html("Status Sertifikat Tanah");
					$("#form_depo_supplier select[name='status_sertifikat_tanah']").prop('required', false);
					//
					$('#label_frekuensi_penjualan_mitra_per_minggu').html("Frekuensi Penjualan Mitra Per Minggu");
					$("#form_depo_aktifitas select[name='frekuensi_penjualan_mitra_per_minggu']").prop('required', false);
					$('#label_volume_bokar_mitra_per_hari').html("Volume Bokar Mitra Per Hari");
					$("#form_depo_aktifitas input[name='volume_bokar_mitra_per_hari']").prop('required', false);
					$('#label_sumber_pendapatan_mitra').html("Sumber Pendapatan Mitra");
					$("#form_depo_aktifitas select[name='sumber_pendapatan_mitra']").prop('required', false);
					$('#label_frekuensi_penjualan_rekan_mitra_per_minggu').html("Frekuensi Penjualan Rekan Mitra Per Minggu");
					$("#form_depo_aktifitas select[name='frekuensi_penjualan_rekan_mitra_per_minggu']").prop('required', false);
					$('#label_volume_bokar_rekan_mitra_per_hari').html("Volume Bokar Rekan Mitra Per Hari");
					$("#form_depo_aktifitas select[name='volume_bokar_rekan_mitra_per_hari']").prop('required', false);
					$('#label_status_sosial_mitra').html("Status Sosial Mitra");
					$("#form_depo_aktifitas select[name='status_sosial_mitra']").prop('required', false);
					//
					$('#label_pengiriman_dana_bokar').html("Pemgiriman Dana Untuk Pemeblain Bokar");
					$("#form_depo_aktifitas select[name='pengiriman_dana_bokar']").prop('required', false);
					$('#label_rekening_tujuan').html("Rekening Tujuan");
					$("#form_depo_aktifitas select[name='rekening_tujuan']").prop('required', false);
				}else{
					$('#label_nip').html("NIP*");
					$("#form_depo_supplier input[name='nip']").prop('required', true);
					$('#label_alamat_rumah').html("Alamat Rumah*");
					$("#form_depo_supplier textarea[name='alamat_rumah']").prop('required', true);
					$('#label_pekerjaan').html("Pekerjaan*");
					$("#form_depo_supplier input[name='pekerjaan']").prop('required', true);
					$('#label_status_kepemilikan_tanah').html("Status Kepemilikan Tanah*");
					$("#form_depo_supplier select[name='status_kepemilikan_tanah']").prop('required', true);
					$('#label_status_sertifikat_tanah').html("Status Sertifikat Tanah*");
					$("#form_depo_supplier select[name='status_sertifikat_tanah']").prop('required', true);
					//
					$('#label_frekuensi_penjualan_mitra_per_minggu').html("Frekuensi Penjualan Mitra Per Minggu*");
					$("#form_depo_aktifitas select[name='frekuensi_penjualan_mitra_per_minggu']").prop('required', true);
					$('#label_volume_bokar_mitra_per_hari').html("Volume Bokar Mitra Per Hari*");
					$("#form_depo_aktifitas input[name='volume_bokar_mitra_per_hari']").prop('required', true);
					$('#label_sumber_pendapatan_mitra').html("Sumber Pendapatan Mitra*");
					$("#form_depo_aktifitas select[name='sumber_pendapatan_mitra']").prop('required', true);
					$('#label_frekuensi_penjualan_rekan_mitra_per_minggu').html("Frekuensi Penjualan Rekan Mitra Per Minggu*");
					$("#form_depo_aktifitas select[name='frekuensi_penjualan_rekan_mitra_per_minggu']").prop('required', true);
					$('#label_volume_bokar_rekan_mitra_per_hari').html("Volume Bokar Rekan Mitra Per Hari*");
					$("#form_depo_aktifitas select[name='volume_bokar_rekan_mitra_per_hari']").prop('required', true);
					$('#label_status_sosial_mitra').html("Status Sosial Mitra*");
					$("#form_depo_aktifitas select[name='status_sosial_mitra']").prop('required', true);
					//
					$('#label_pengiriman_dana_bokar').html("Pemgiriman Dana Untuk Pemeblain Bokar*");
					$("#form_depo_aktifitas select[name='pengiriman_dana_bokar']").prop('required', true);
					$('#label_rekening_tujuan').html("Rekening Tujuan*");
					$("#form_depo_aktifitas select[name='rekening_tujuan']").prop('required', true);
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
            // KIRANAKU.datepicker($(".row-summary"));
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

    //set on change propinsi
    $(document).on("change", "#propinsi", function(e) {
        var id_provinsi = $(this).val();
        $.ajax({
            // url: baseURL + 'vendor/transaksi/get/provinsi',
            url: baseURL + 'depo/transaksi/get/kabupaten',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_provinsi: id_provinsi
            },
            success: function(data) {
                var value = '';
				value += '<option ></option>';
                $.each(data, function(i, v) {
                    value += '<option value="' + v.id_kabupaten + '">'+ v.nama_kabupaten + '</option>';
                });
                $('#kabupaten').html(value);
            }
        });
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
		//set total
		generate_total_luas_area();
    });
    $(document).on("keyup", "input[name*='luas_desa']", function() {
		//set total
		generate_total_luas_area();			
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
		generate_avg_harga_per_hari_survei();
		generate_avg_harga_notarin_survei();
		generate_avg_harga_sicom_survei();
		generate_avg_total_produksi_survei();
		generate_avg_rata_rata_survei();
    });
    $(document).on("keyup", "input[name*='harga_per_hari_survei']", function() {
		//set avg
		generate_avg_harga_per_hari_survei();			
    });
    $(document).on("keyup", "input[name*='harga_notarin_survei']", function() {
		//set avg
		generate_avg_harga_notarin_survei();			
    });
    $(document).on("keyup", "input[name*='harga_sicom_survei']", function() {
		//set avg
		generate_avg_harga_sicom_survei();			
    });
    $(document).on("keyup", "input[name*='total_produksi_survei']", function() {
		//set avg
		generate_avg_total_produksi_survei();			
    });
    $(document).on("keyup", "input[name*='rata_rata_survei']", function() {
		//set avg
		generate_avg_rata_rata_survei();			
		//generate_nilai_tonase_depo
		generate_nilai_tonase_depo();
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
			output += "		<input type='text' class='angka form-control text-center nilai_tonase_depo' name='tonase_depo[]' value='' placeholder='Tonase'  required='required' readonly/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='total_depo[]' value='' placeholder='Total'  required='required'/>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item_biaya_depo' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-biaya_depo tbody");
			if(jenis_depo=='mitra'){
				//get master biaya depo mitra
				master_biaya(elem + " select[name='id_biaya_depo[]']", 'mitra','operational','transaksi');
				// $("#form_depo_biaya  input[name='tonase_depo[]']").prop('readonly', false);
				// $(".nilai_tonase_depo").val(0);
				generate_nilai_tonase_depo();
				
			}else{
				//get master biaya depo tetap
				master_biaya(elem + " select[name='id_biaya_depo[]']", 'tetap','operational','transaksi');
				// $("#form_depo_biaya input[name='tonase_depo[]']").prop('readonly', true);
				//hitung tonase kg basah(depo tetap)
				generate_nilai_tonase_depo();
				
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

    $(document).on("keyup", "input[name*='biaya_depo']", function() {
        if ($(this).val().replace(/,/g, "") < 0)
			$(this).val('');

        let row 		= $(this).closest(".row-biaya_depo");
        let biaya_depo 	= $(this).val().replace(/,/g, "");
        let tonase_depo = row.find("input[name*='tonase_depo']").val().replace(/,/g, "");
        let total_depo 	= parseFloat(biaya_depo) / parseFloat(tonase_depo);
        row.find("input[name*='total_depo']").val(numberWithCommas(parseFloat(total_depo).toFixed(2)));
    });
	
    $(document).on("keyup", "input[name*='tonase_depo']", function() {
        if ($(this).val().replace(/,/g, "") < 0)
			$(this).val('');

        let row 		= $(this).closest(".row-biaya_depo");
        let biaya_depo 	= row.find("input[name*='biaya_depo']").val().replace(/,/g, "");
        let tonase_depo = $(this).val().replace(/,/g, "");
        let total_depo 	= parseFloat(biaya_depo) / parseFloat(tonase_depo);
        row.find("input[name*='total_depo']").val(numberWithCommas(parseFloat(total_depo).toFixed(2)));
    });
	
    $(document).on("keyup", "input[name*='total_depo']", function() {
        if ($(this).val().replace(/,/g, "") < 0)
			$(this).val('');

        let row 		= $(this).closest(".row-biaya_depo");
        let tonase_depo = row.find("input[name*='tonase_depo']").val().replace(/,/g, "");
        let total_depo 	= $(this).val().replace(/,/g, "");
		let biaya_depo 	= parseFloat(total_depo) * parseFloat(tonase_depo);
        row.find("input[name*='biaya_depo']").val(numberWithCommas(parseFloat(biaya_depo).toFixed(2)));
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
			if(jenis_depo=='mitra'){
				output += "	<td>";
				output += "		<select class='form-control modalselect2' name='jenis_budget_sdm[]'  required='required' data-placeholder='Pilih Budget'>";
				output += "			<option value=''></option>";
				output += "			<option value='budget'>Budget</option>";
				output += "			<option value='unbudget'>Unbudget</option>";
				output += "		</select>";
				output += "	</td>";
			}else{
				output += "	<td>";
				output += "		<select class='form-control modalselect2' name='jenis_budget_sdm[]'  required='required' data-placeholder='Pilih Budget'>";
				output += "			<option value='budget'>Budget</option>";
				output += "		</select>";
				output += "	</td>";
			}
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
			output += "		<select class='form-control modalselect2' name='kepemilikan_investasi[]' required='required'  data-placeholder='Pilih Status'>";
			output += "			<option ></option>";
			output += "			<option value='ada'>Ada</option>";
			output += "			<option value='tidak_ada'>Tidak Ada</option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='jumlah_investasi[]' value='' placeholder='QTY'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control  text-center' name='harga_investasi[]' value='' placeholder='Rp/ Unit' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='total_investasi[]' value='' placeholder='Total' readonly />";
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
			$(".modalselect2").select2();
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
    $(document).on("change", "select[name*='kepemilikan_investasi']", function() {
        let row 		= $(this).closest(".row-biaya_investasi");
        let kepemilikan_investasi = $(this).val();
		if(kepemilikan_investasi=='ada'){
			row.find("input[name*='jumlah_investasi']").val(0);
			row.find("input[name*='jumlah_investasi']").prop('readonly', false);
			row.find("input[name*='harga_investasi']").val(0);
			row.find("input[name*='harga_investasi']").prop('readonly', false);
			row.find("input[name*='total_investasi']").val(0);
			row.find("input[name*='total_investasi']").prop('readonly', true);
		}else{
			row.find("input[name*='jumlah_investasi']").val(0);
			row.find("input[name*='jumlah_investasi']").prop('readonly', false);
			row.find("input[name*='harga_investasi']").val(0);
			row.find("input[name*='harga_investasi']").prop('readonly', false);
		}
		
    });
    $(document).on("keyup", "input[name*='jumlah_investasi']", function() {
        if ($(this).val().replace(/,/g, "") < 0)
			$(this).val('');

        let row 		= $(this).closest(".row-biaya_investasi");
        let kepemilikan_investasi  = row.find("select[name*='kepemilikan_investasi']").val().replace(/,/g, "");
		let jumlah_investasi = $(this).val().replace(/,/g, "");
        let harga_investasi  = row.find("input[name*='harga_investasi']").val().replace(/,/g, "");
		let total_investasi  = parseFloat(jumlah_investasi) * parseFloat(harga_investasi);
		if((jumlah_investasi>=0)&&(harga_investasi>=0)){
			if(kepemilikan_investasi=='ada'){
				row.find("input[name*='total_investasi']").val(0);
			}else{
				row.find("input[name*='total_investasi']").val(numberWithCommas(parseFloat(total_investasi).toFixed(0)));
			}
		}
    });
    $(document).on("keyup", "input[name*='harga_investasi']", function() {
        if ($(this).val().replace(/,/g, "") < 0)
			$(this).val('');

        let row 		= $(this).closest(".row-biaya_investasi");
		let kepemilikan_investasi  = row.find("select[name*='kepemilikan_investasi']").val().replace(/,/g, "");
        let jumlah_investasi = row.find("input[name*='jumlah_investasi']").val().replace(/,/g, "");
        let harga_investasi  = $(this).val().replace(/,/g, "");
		let total_investasi  = parseFloat(jumlah_investasi) * parseFloat(harga_investasi);
		if((jumlah_investasi>=0)&&(harga_investasi>=0)){
			if(kepemilikan_investasi=='ada'){
				row.find("input[name*='total_investasi']").val(0);
			}else{
				row.find("input[name*='total_investasi']").val(numberWithCommas(parseFloat(total_investasi).toFixed(0)));
			}
		}
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
			output += "		<input type='text' class='form-control' name='nomor_vendor_darat[]' value='' placeholder='Nomor Vendor'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='form-control' name='nama_vendor_darat[]' value='' placeholder='Nama Vendor'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control modalselect2' name='penentuan_tarif_darat[]' required='required'  data-placeholder='Pilih Status'>";
			output += "			<option ></option>";
			output += "			<option value='pabrik'>Pabrik</option>";
			output += "			<option value='depo'>Depo</option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control modalselect2' name='kapasitas_basah_darat[]' required='required'  data-placeholder='Pilih Status'>";
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
			$(".modalselect2").select2();
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
			output += "		<input type='text' class='form-control' name='nomor_vendor_air[]' value='' placeholder='Nomor Vendor'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='form-control' name='nama_vendor_air[]' value='' placeholder='Nama Vendor'  required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<select class='form-control modalselect2' name='kapasitas_basah_air[]' required='required'  data-placeholder='Pilih Status'>";
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
			$(".modalselect2").select2();
		}else{
			swal('Warning', 'Mohon isi Jenis Depo dan Pabrik Lebih Dulu.', 'warning');
		}	
    });
    $(document).on("click", ".remove_item_biaya_air", function (e) {
        if ($("tr.row-biaya_air").length >= 1) {
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
	
	
	$(document).on("click", "button[name='action_btn']", function(e){
		// let action 	 = $(this).data('action');
		// $(".nav-tabs a[href='#"+action+"']").tab('show');
		let act 	 = $(this).data('act');
		let action 	 = $(this).data('action');
		let back 	 = $(this).data('back');
		if(back=='yes'){
			$(".nav-tabs a[href='#"+action+"']").tab('show');
		}else{
			var empty_form = validate("#"+$(this).val());
			if( empty_form == 0){
				var isproses = $("input[name='isproses']").val();
				if(isproses == 0){
					if ($("#form_depo_supplier .table-summary .row-summary").length == 0) {	//cek inputan jarak depo
						kiranaAlert("notOK", 'Jarak dan Waktu (Depo - Depo KMG Minimal Input 1 Data)', "error", "no");	
					}else if($("#form_depo_supplier .table-gudang .row-gudang").length == 0){ //cek inputan jarak gudang kompetitor
						kiranaAlert("notOK", 'Jarak dan Waktu (Depo - Gudang Kompetitor Terdekat Minimal Input 1 Data)', "error", "no");	
					}else if($("#form_depo_supplier .table-pabrik .row-pabrik").length == 0){ //cek inputan jarak pabrik kompetitor
						kiranaAlert("notOK", 'Jarak dan Waktu (Depo - Pabrik Kompetitor Terdekat Minimal Input 1 Data)', "error", "no");	
					}else{
						$("input[name='isproses']").val(1);
						var formData = new FormData($("#"+$(this).val())[0]);
						$.ajax({
							url: baseURL+'depo/transaksi/save/depo/'+$(this).data('link'),
							type: 'POST',
							dataType: 'JSON',
							data: formData,
							contentType: false,
							cache: false,
							processData: false,
							success: function(data){
								if (data.sts == 'OK') {
									if(act=='finish'){
										kiranaAlert(data.sts, data.msg);
										window.location = baseURL + 'depo/transaksi/approve';
									}else{
										kiranaAlert(data.sts, data.msg, "success", "no");
										$(".nav-tabs a[href='#"+action+"']").tab('show');
									}
								} else {
									$("input[name='isproses']").val(0);
									kiranaAlert("notOK", data.msg, "error", "no");	
								}
							}
						});
					}
				}else{
					kiranaAlert("notOK", "Silahkan tunggu proses selesai.", "info", "no");	
				}
			}			
		}	
		e.preventDefault();
		return false;
    });
	
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
    // if ($(elem).hasClass("select2-hidden-accessible")) {
        // $(elem).select2("destroy");
    // }

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
    // if ($(elem).hasClass("select2-hidden-accessible")) {
        // $(elem).select2("destroy");
    // }

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

function generate_nilai_tonase_depo() {
	let avg_target 			 = $("input[name='avg_target']").val();
	let avg_rata_rata_survei = $("input[name='avg_rata_rata_survei']").val();
	let nilai_tonase_depo 	 = (parseFloat(avg_target)*1000)/parseFloat(avg_rata_rata_survei);
	$(".nilai_tonase_depo").val(numberWithCommas(parseFloat(nilai_tonase_depo).toFixed(2)));
}

function generate_total_luas_area() {
    let total = 0;
    $("#form_depo_peta input[name^='luas_desa']").each(function(i) {
        total += +$(this).val().replace(/,/g, "");
    });
    total = total.toFixed(2);
	$("input[name='total_luas_area']").val(numberWithCommas(total));	
}

function generate_avg_harga_per_hari_survei() {
    let total  = 0;
	let jumlah = 0;
    $("#form_depo_peta input[name^='harga_per_hari_survei']").each(function(i) {
		console.log($(this).val().replace(/,/g, ""));
        total  += +$(this).val().replace(/,/g, "");
		jumlah += 1;
    });
	avg = (total/jumlah).toFixed(2);
	$("input[name='avg_harga_per_hari_survei']").val(numberWithCommas(avg));	
}
function generate_avg_harga_notarin_survei() {
    let total  = 0;
	let jumlah = 0;
    $("#form_depo_peta input[name^='harga_notarin_survei']").each(function(i) {
		console.log($(this).val().replace(/,/g, ""));
        total  += +$(this).val().replace(/,/g, "");
		jumlah += 1;
    });
	avg = (total/jumlah).toFixed(2);
	$("input[name='avg_harga_notarin_survei']").val(numberWithCommas(avg));	
}
function generate_avg_harga_sicom_survei() {
    let total  = 0;
	let jumlah = 0;
    $("#form_depo_peta input[name^='harga_sicom_survei']").each(function(i) {
		console.log($(this).val().replace(/,/g, ""));
        total  += +$(this).val().replace(/,/g, "");
		jumlah += 1;
    });
	avg = (total/jumlah).toFixed(2);
	$("input[name='avg_harga_sicom_survei']").val(numberWithCommas(avg));	
}
function generate_avg_total_produksi_survei() {
    let total  = 0;
	let jumlah = 0;
    $("#form_depo_peta input[name^='total_produksi_survei']").each(function(i) {
		console.log($(this).val().replace(/,/g, ""));
        total  += +$(this).val().replace(/,/g, "");
		jumlah += 1;
    });
	avg = (total/jumlah).toFixed(2);
	$("input[name='avg_total_produksi_survei']").val(numberWithCommas(avg));	
}
function generate_avg_rata_rata_survei() {
    let total  = 0;
	let jumlah = 0;
    $("#form_depo_peta input[name^='rata_rata_survei']").each(function(i) {
		console.log($(this).val().replace(/,/g, ""));
        total  += +$(this).val().replace(/,/g, "");
		jumlah += 1;
    });
	avg = (total/jumlah).toFixed(2);
	$("input[name='avg_rata_rata_survei']").val(numberWithCommas(avg));	
}


