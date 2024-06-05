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

$(document).ready(function () {
    $.ajax({
        url: baseURL + "depo/evaluasi/get/data",
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
				//header
				$("input[name='id_depo_master']").val(v.id_depo_master);
				$("input[name='kode_sj']").val(v.kode_sj);
				$("input[name='kabupaten']").val(v.nama_kabupaten);
				$("input[name='propinsi']").val(v.nama_propinsi);
				$("input[name='jenis_depo']").val(v.jenis_depo);
				$("input[name='pabrik']").val(v.pabrik);
				$("input[name='nomor']").val(v.nomor);
				$("input[name='status_akhir']").val(v.status);
				$("input[name='level']").val(v.level);
				$("input[name='nama']").val(v.nama);
				$("textarea[name='alamat_rumah']").val(v.alamat_rumah);
				$("textarea[name='alamat_depo']").val(v.alamat_depo);
				let total_target = 0;
				let total_aktual_kering = 0;
				let total_aktual_basah = 0;
				let total_harga_beli_depo = 0;
				let total_cis_detail	= 0;
				let no = 0;
				//detail aktual
                if (v.arr_data_detail) {
					let output = "";
					$("#nodata_detail").remove();
					$.each(v.arr_data_detail, function(a, b){
						no++;
						total_target += parseFloat(b.target);
						total_aktual_kering += parseFloat(b.aktual_kering);
						total_aktual_basah += parseFloat(b.aktual_basah);
						total_harga_beli_depo += parseFloat(b.harga_beli_depo);
						total_cis_detail += parseFloat(b.aktual_kering)*parseFloat(b.harga_beli_depo);
						
						output += "<tr class='row-detail detail" + no + "'>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='bulan_tahun[]' value='"+b.bulan+"-"+b.tahun+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='target[]' value='"+numberWithCommas(parseFloat(b.target).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='aktual_kering[]' value='"+numberWithCommas(parseFloat(b.aktual_kering).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='aktual_basah[]' value='"+numberWithCommas(parseFloat(b.aktual_basah).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='harga_notarin[]' value='"+numberWithCommas(parseFloat(b.harga_notarin).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='sicom[]' value='"+numberWithCommas(parseFloat(b.sicom).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='biaya_pabrik[]' value='"+numberWithCommas(parseFloat(b.biaya_pabrik).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='harga_beli_depo[]' value='"+numberWithCommas(parseFloat(b.harga_beli_depo).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='susut_pabrik[]' value='"+numberWithCommas(parseFloat(b.susut_pabrik).toFixed(2))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='harga_beli_batch_pabrik[]' value='"+numberWithCommas(parseFloat(b.harga_beli_batch_pabrik).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='susut_depo[]' value='"+numberWithCommas(parseFloat(b.susut_depo).toFixed(2))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='harga_beli_batch_depo[]' value='"+numberWithCommas(parseFloat(b.harga_beli_batch_depo).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "</tr>";
					});
					$(output).appendTo(".table-detail tbody");
                }				
				let drc_rata_rata = parseFloat(total_aktual_kering)/parseFloat(total_aktual_basah)*100;
				
				$("input[name='drc_rata_rata']").val(numberWithCommas(parseFloat(drc_rata_rata).toFixed(2)));
				//
				let total_biaya_asuransi_kgb = parseFloat(0.002*(total_cis_detail/6)) + parseFloat(0.0002*(total_cis_detail/6)) + parseFloat(0.000425*(total_cis_detail/6));
				let total_biaya_asuransi_kgk = parseFloat((0.002*(total_cis_detail/6))/total_aktual_kering) + parseFloat((0.0002*(total_cis_detail/6))/total_aktual_kering)+parseFloat((0.000425*(total_cis_detail/6))/total_aktual_kering);
				$("input[name='biaya_cash_save_kgb']").val(numberWithCommas(parseFloat(0.002*(total_cis_detail/6)).toFixed(0)));
				$("input[name='biaya_cash_save_kgk']").val(numberWithCommas(parseFloat((0.002*(total_cis_detail/6))/total_aktual_kering).toFixed(0)));
				$("input[name='biaya_cash_transit_kgb']").val(numberWithCommas(parseFloat(0.0002*(total_cis_detail/6)).toFixed(0)));
				$("input[name='biaya_cash_transit_kgk']").val(numberWithCommas(parseFloat((0.0002*(total_cis_detail/6))/total_aktual_kering).toFixed(0)));
				$("input[name='biaya_expedition_kgb']").val(numberWithCommas(parseFloat(0.000425*(total_cis_detail/6)).toFixed(0)));
				$("input[name='biaya_expedition_kgk']").val(numberWithCommas(parseFloat((0.000425*(total_cis_detail/6))/total_aktual_kering).toFixed(0)));
				$("input[name='total_biaya_asuransi_kgb']").val(numberWithCommas(parseFloat(total_biaya_asuransi_kgb).toFixed(0)));
				$("input[name='total_biaya_asuransi_kgk']").val(numberWithCommas(parseFloat(total_biaya_asuransi_kgk).toFixed(0)));
				

				//evaluasi biaya
				let total_biaya_profesional_kgb = 0;
				let total_biaya_profesional_kgk = 0;
				let total_biaya_gaji_kgb = 0;
				let total_biaya_gaji_kgk = 0;
				let total_biaya_kgk = 0;

				$("#nodata_biaya_profesional").remove();
				$("#nodata_biaya_opex").remove();
				$("#nodata_biaya_angkut").remove();
				$("#nodata_biaya_gaji").remove();
                if (v.arr_data_biaya) {
					let output_operasional = "";
					let output_opex = "";
					let output_angkut = "";
					let output_gaji = "";
					$.each(v.arr_data_biaya, function(a, b){
						let biaya_kgb = (b.biaya_kgb_evaluasi>=0)?b.biaya_kgb_evaluasi:b.biaya_kgb_pembukaan;
						total_biaya_kgk += parseFloat(biaya_kgb/drc_rata_rata);	
						//operasional
						if((b.id_evaluasi_biaya==1)||(b.id_evaluasi_biaya==2)){
							total_biaya_profesional_kgb += parseFloat(biaya_kgb);
							total_biaya_profesional_kgk += parseFloat(biaya_kgb/drc_rata_rata);
							output_operasional += "<tr class='row-biaya_depo_profesional biaya_depo_profesional" + b.id_biaya + "'>";
							output_operasional += "	<td>";
							output_operasional += "		<input type='text' class='form-control' name='caption_nama_biaya' value='"+b.nama+"' placeholder='Biaya'  required='required' />";
							output_operasional += "	</td>";
							output_operasional += "	<td>";
							output_operasional += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgb_analisis[]' value='"+numberWithCommas(parseFloat(biaya_kgb).toFixed(0))+"' placeholder='Biaya'  required='required' />";
							output_operasional += "	</td>";
							output_operasional += "	<td>";
							output_operasional += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgk_analisis[]' value='"+numberWithCommas(parseFloat(biaya_kgb/drc_rata_rata).toFixed(0))+"' placeholder='Biaya'  required='required' />";
							output_operasional += "	</td>";
							output_operasional += "</tr>";
						}
						//opex
						if(b.id_evaluasi_biaya==3){
							output_opex += "<tr class='row-biaya_depo_opex biaya_depo_opex" + b.id_biaya + "'>";
							output_opex += "	<td>";
							output_opex += "		<input type='text' class='form-control' name='caption_nama_biaya' value='"+b.nama+"' placeholder='Biaya'  required='required' />";
							output_opex += "	</td>";
							output_opex += "	<td>";
							output_opex += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgb_analisis[]' value='"+numberWithCommas(parseFloat(biaya_kgb).toFixed(0))+"' placeholder='Biaya'  required='required' />";
							output_opex += "	</td>";
							output_opex += "	<td>";
							output_opex += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgk_analisis[]' value='"+numberWithCommas(parseFloat(biaya_kgb/drc_rata_rata).toFixed(0))+"' placeholder='Biaya'  required='required' />";
							output_opex += "	</td>";
							output_opex += "</tr>";
						}
						//angkut
						if(b.id_evaluasi_biaya==4){
							output_angkut += "<tr class='row-biaya_depo_opex biaya_depo_angkut" + b.id_biaya + "'>";
							output_angkut += "	<td>";
							output_angkut += "		<input type='text' class='form-control' name='caption_nama_biaya' value='"+b.nama+"' placeholder='Biaya'  required='required' />";
							output_angkut += "	</td>";
							output_angkut += "	<td>";
							output_angkut += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgb_analisis[]' value='"+numberWithCommas(parseFloat(biaya_kgb).toFixed(0))+"' placeholder='Biaya'  required='required' />";
							output_angkut += "	</td>";
							output_angkut += "	<td>";
							output_angkut += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgk_analisis[]' value='"+numberWithCommas(parseFloat(biaya_kgb/drc_rata_rata).toFixed(0))+"' placeholder='Biaya'  required='required' />";
							output_angkut += "	</td>";
							output_angkut += "</tr>";
						}
						//gaji
						if((b.id_evaluasi_biaya==5)||(b.id_evaluasi_biaya==6)){
							total_biaya_gaji_kgb += parseFloat(biaya_kgb);
							// total_biaya_gaji_kgk += parseFloat(biaya_kgb/drc_rata_rata);
							total_biaya_gaji_kgk += parseFloat(biaya_kgb/total_aktual_kering);
							output_gaji += "<tr class='row-biaya_depo_profesional biaya_depo_profesional" + b.id_biaya + "'>";
							output_gaji += "	<td>";
							output_gaji += "		<input type='text' class='form-control' name='caption_nama_biaya' value='"+b.nama+"' placeholder='Biaya'  required='required' />";
							output_gaji += "	</td>";
							output_gaji += "	<td>";
							output_gaji += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgb_analisis[]' value='"+numberWithCommas(parseFloat(biaya_kgb).toFixed(0))+"' placeholder='Biaya'  required='required' />";
							output_gaji += "	</td>";
							output_gaji += "	<td>";
							output_gaji += "		<input type='text' class='angka form-control text-center' name='biaya_depo_kgk_analisis[]' value='"+numberWithCommas(parseFloat(biaya_kgb/drc_rata_rata).toFixed(0))+"' placeholder='Biaya'  required='required' />";
							output_gaji += "	</td>";
							output_gaji += "</tr>";
						}
					});
					$(".table-biaya_profesional tbody").html(output_operasional);
					$(".table-biaya_opex tbody").html(output_opex);
					$(".table-biaya_angkut tbody").html(output_angkut);
					$(".table-biaya_gaji tbody").html(output_gaji);
                }			
				$("input[name='total_biaya_profesional_kgb']").val(numberWithCommas(parseFloat(total_biaya_profesional_kgb).toFixed(0)));
				$("input[name='total_biaya_profesional_kgk']").val(numberWithCommas(parseFloat(total_biaya_profesional_kgk).toFixed(0)));
				$("input[name='total_biaya_gaji_kgb']").val(numberWithCommas(parseFloat(total_biaya_gaji_kgb).toFixed(0)));
				$("input[name='total_biaya_gaji_kgk']").val(numberWithCommas(parseFloat(total_biaya_gaji_kgk).toFixed(0)));
				
				//total_biaya_operasional
				let total_biaya_operasional = parseFloat(total_biaya_kgk)+parseFloat(total_biaya_asuransi_kgk);
				$("input[name='total_biaya_operasional']").val(numberWithCommas(parseFloat(total_biaya_operasional).toFixed(0)));
			
			
				//detail asumsi depo
				let total_net_margin_depo_efek_batch = 0;
                if (v.arr_data_detail) {
					let output = "";
					let no = 0;
					$("#nodata_asumsi_depo").remove();
					$.each(v.arr_data_detail, function(a, b){
						let harga_beli_depo_efek_batch = b.harga_beli_depo-(b.susut_depo*b.harga_beli_depo);
						let net_margin_depo = parseFloat(b.sicom) - parseFloat(b.harga_beli_depo) - parseFloat(b.biaya_pabrik) - parseFloat(total_biaya_operasional);
						let net_margin_depo_efek_batch = parseFloat(b.sicom) - parseFloat(harga_beli_depo_efek_batch) - parseFloat(b.biaya_pabrik) - parseFloat(total_biaya_operasional);
						no++;
						total_net_margin_depo_efek_batch+=net_margin_depo_efek_batch;
						output += "<tr class='row-asumsi_depo asumsi_depo" + no + "'>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='bulan_tahun_depo[]' value='"+b.bulan+"-"+b.tahun+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='sicom_depo[]' value='"+numberWithCommas(parseFloat(b.sicom).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='harga_beli_depo_depo[]' value='"+numberWithCommas(parseFloat(b.harga_beli_depo).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='harga_beli_depo_efek_batch[]' value='"+numberWithCommas(parseFloat(harga_beli_depo_efek_batch).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='biaya_pabrik_depo[]' value='"+numberWithCommas(parseFloat(b.biaya_pabrik).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='total_biaya_operasional_depo[]' value='"+numberWithCommas(parseFloat(total_biaya_operasional).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='net_margin_depo_depo[]' value='"+numberWithCommas(parseFloat(net_margin_depo).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='net_margin_depo_efek_batch[]' value='"+numberWithCommas(parseFloat(net_margin_depo_efek_batch).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "</tr>";
					});
					output += "<tr>";
					output += "	<td colspan='7'>";
					output += "		<input type='text' class='form-control text-right' value='Nett Margin Effect Batch Depo' required='required' readonly />";
					output += "	</td>";
					output += "	<td>";
					output += "		<input type='text' class='form-control text-center' name='total_net_margin_depo_efek_batch' value='"+numberWithCommas(parseFloat(total_net_margin_depo_efek_batch).toFixed(0))+"' required='required' readonly />";
					output += "	</td>";
					output += "</tr>";
					$(output).appendTo(".table-asumsi_depo tbody");
					$("input[name='total_net_margin_depo_efek_batch']").val(numberWithCommas(parseFloat(total_net_margin_depo_efek_batch).toFixed(0)));
					if(total_net_margin_depo_efek_batch<=0){
						$("input[name='nilai_net_margin_depo_efek_batch']").val('NEGATIF');
					}else{
						$("input[name='nilai_net_margin_depo_efek_batch']").val('POSITIF');
					}
                }			

				//detail asumsi pabrik
				let total_margin_depo_pabrik = 0;
                if (v.arr_data_detail) {
					let output = "";
					let no = 0;
					
					$("#nodata_asumsi_pabrik").remove();
					$.each(v.arr_data_detail, function(a, b){
						let margin_depo_pabrik = parseFloat(b.harga_notarin) - parseFloat(b.harga_beli_depo) - parseFloat(total_biaya_operasional);
						no++;
						total_margin_depo_pabrik+=margin_depo_pabrik;
						output += "<tr class='row-asumsi_pabrik asumsi_pabrik" + no + "'>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='bulan_tahun[]' value='"+b.bulan+"-"+b.tahun+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='harga_notarin_pabrik[]' value='"+numberWithCommas(parseFloat(b.harga_notarin).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='harga_beli_depo_pabrik[]' value='"+numberWithCommas(parseFloat(b.harga_beli_depo).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='total_biaya_operasional_pabrik[]' value='"+numberWithCommas(parseFloat(total_biaya_operasional).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "	<td>";
						output += "		<input type='text' class='form-control text-center' name='margin_depo_pabrik[]' value='"+numberWithCommas(parseFloat(margin_depo_pabrik).toFixed(0))+"' required='required' readonly />";
						output += "	</td>";
						output += "</tr>";
					});
					output += "<tr>";
					output += "	<td colspan='4'>";
					output += "		<input type='text' class='form-control text-right' value='Nett Selisih Margin Depo VS Pabrik' required='required' readonly />";
					output += "	</td>";
					output += "	<td>";
					output += "		<input type='text' class='form-control text-center' name='total_margin_depo_pabrik' value='"+numberWithCommas(parseFloat(total_margin_depo_pabrik).toFixed(0))+"' required='required' readonly />";
					output += "	</td>";
					output += "</tr>";
					$(output).appendTo(".table-asumsi_pabrik tbody");
					$("input[name='total_margin_depo_pabrik']").val(numberWithCommas(parseFloat(total_margin_depo_pabrik).toFixed(0)));
					if(total_margin_depo_pabrik<=0){
						$("input[name='nilai_margin_depo_pabrik']").val('NEGATIF');
					}else{
						$("input[name='nilai_margin_depo_pabrik']").val('POSITIF');
					}
                }	
				let pencapaian_depo = total_aktual_kering/(total_target*1000);
				$("input[name='pencapaian_depo']").val(numberWithCommas(parseFloat(pencapaian_depo).toFixed(2)));		
				if(pencapaian_depo>=50){
					$("input[name='nilai_pencapaian_depo']").val('>=50%');
				}else{
					$("input[name='nilai_pencapaian_depo']").val('<50%');
				}
				if((total_net_margin_depo_efek_batch>0)&&(total_margin_depo_pabrik>0)){
					$("#nilai_hasil_perhitungan").html("<button type='button' class='btn btn-sm btn-success'>DILANJUTKAN</button>");				
				}else if((total_net_margin_depo_efek_batch<0)&&(total_margin_depo_pabrik>0)&&(pencapaian_depo>=50)){
					$("#nilai_hasil_perhitungan").html("<button type='button' class='btn btn-sm btn-success'>DILANJUTKAN</button>");				
				}else{
					$("#nilai_hasil_perhitungan").html("<button type='button' class='btn btn-sm btn-danger'>DITUTUP</button>");				
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
			let status_akhir = $("#form_evaluasi_depo input[name='status_akhir']").val();
			let level 		 = $("#form_evaluasi_depo input[name='level']").val();
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
					url: baseURL + 'depo/evaluasi/save/approve',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
								window.location = baseURL + 'depo/evaluasi/approve';
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
    let id_depo_master 	 = $("#form_evaluasi_depo input[name='id_depo_master']").val();
    let jenis_depo 	 = $("#form_evaluasi_depo input[name='jenis_depo']").val();
    let nomor 		 = $("#form_evaluasi_depo input[name='nomor']").val();
    let status_akhir = $("#form_evaluasi_depo input[name='status_akhir']").val();
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
    $("#KiranaModals .modal-title").html(action + " Evaluasi Depo (" + nomor + ")");

    let output = '';
    output += '<div class="row">';
    output += ' <div class="col-sm-12">';
    output += '     <form role="form" id="form-save-depo">';
	if(status_akhir==8){
    output += '         <div class="form-group">';
    output += '             <label>Status Evaluasi</label>';
    output += '             <select class="form-control form-control-hide select2" name="status_evaluasi" id="status_evaluasi" required="required"  data-placeholder="Pilih Status Evaluasi">';
    output += '             	<option ></option>';
    output += '             	<option value="diterima">DITERIMA</option>';
    output += '             	<option value="ditolak">DITOLAK</option>';
    output += '             </select>';
    output += '         </div>';
	}
    output += '         <div class="form-group">';
    output += '             <label>Komentar</label>';
    output += '             <textarea class="form-control" name="komentar_approve_evaluasi" required="required"></textarea>';
    output += '             <input type="hidden" name="nomor">';
    output += '             <input type="hidden" name="action">';
    output += '             <input type="hidden" name="status_akhir">';
    output += '             <input type="hidden" name="jenis_depo">';
    output += '             <input type="hidden" name="id_depo_master">';
    output += '         </div>';
    output += '     </form>';
    output += ' </div>';
    output += '</div>';
    $("#KiranaModals .modal-body").html(output);

    if (action == 'approve') {
        $("#KiranaModals textarea[name='komentar_approve_evaluasi']").removeAttr("required");
    } else {
        $("#KiranaModals textarea[name='komentar_approve_evaluasi']").attr("required", "required");
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
    $("#KiranaModals input[name='id_depo_master']").val(id_depo_master);


    $('#KiranaModals').modal({
        backdrop: 'static',
        keyboard: true,
        show: true
    });

    KIRANAKU.select2('#KiranaModals');
}


