$(document).ready(function() {
	$(".add-row").click(function(){
		var count=$('#table-berita-acara tr').length;
		$("input[name='jumlah_berita_acara']").val(count);
		var tanggal 	= $("#tanggal").val();
		var gejala 		= $("#gejala").val();
		var riwayat 	= $("#riwayat").val();
		var tindakan 	= $("#tindakan").val();
		// var markup 		= "<tr><td><input type='checkbox' name='record'></td><td>"+count+"</td><td>"+tanggal+"</td><td>"+gejala+"</td><td>"+riwayat+"</td><td>"+tindakan+"</td></tr>";
		var markup	=  "";
			markup	+= "<tr>";
			markup	+= "<td><input type='checkbox' name='record'></td>";
			markup	+= "<td><input class='form-control tanggal' type='text' id='tanggal_ba_"+count+"' name='tanggal_ba_"+count+"' placeholder='Tanggal'></td>";
			markup	+= "<td><input class='form-control' type='text' id='gejala_ba_"+count+"' name='gejala_ba_"+count+"' placeholder='Gejala'></td>";
			markup	+= "<td><input class='form-control' type='text' id='riwayat_ba_"+count+"' name='riwayat_ba_"+count+"' placeholder='Riwayat'></td>";
			markup	+= "<td><input class='form-control' type='text' id='tindakan_ba_"+count+"' name='tindakan_ba_"+count+"' placeholder='Tindakan'></td>";
			markup	+= "</tr>";
		
		$("#table-berita-acara").append(markup);
		$('.tanggal').datepicker({ 
			format: 'yyyy-mm-dd',
			changeMonth: true,
			changeYear: true, 
			autoclose: true
		}); 
		
	});
	
	// Find and remove selected table rows
	$(".delete-row").click(function(){
		$("#table-berita-acara").find('input[name="record"]').each(function(){
			if($(this).is(":checked")){
				$(this).parents("tr").remove();
			}
		});
	});
	
	//tanggal
    $('.tanggal').datepicker({ 
    	format: 'yyyy-mm-dd',
        changeMonth: true,
        changeYear: true,
        autoclose: true
    }); 
	
	$(document).on("keyup", "#suhu_tubuh", function(e) {
		var suhu = $(this).val();
			suhu = parseFloat(suhu);
			suhu = suhu.toFixed(2);
		if(suhu >= 37.3){
			$("input[name='jumlah_berita_acara']").val(1);
			//
            $('.score_6_1').prop('disabled', false);
			$('.score_6_0').prop('disabled', true);
			$('.score_6_1').prop('checked', true);
			$('#suhu_tertinggi_6').prop('readonly', false);
            $("input[name='suhu_tertinggi_6']").val(suhu);
			$('#gejala_6').prop('readonly', false);
			$('#riwayat_dokter_6').prop('readonly', false);
			//show berita acara
			$("#div_berita_acara").show();
			$('#tanggal_ba_1').prop('required', true);
			$('#gejala_ba_1').prop('required', true);
			$('#riwayat_ba_1').prop('required', true);
			$('#tindakan_ba_1').prop('required', true);
		}else{
			$("input[name='jumlah_berita_acara']").val(0);
            //
			$('.score_6_1').prop('disabled', false);
			$('.score_6_0').prop('disabled', false);
			$('.score_6_1').prop('checked', false);
            $('#suhu_tertinggi_6').prop('readonly', true);
            $("input[name='suhu_tertinggi_6']").val('');
			$('#gejala_6').prop('readonly', true);
			$('#riwayat_dokter_6').prop('readonly', true);
			//hide berita acara
			$("#div_berita_acara").hide();
			$('#tanggal_ba_1').prop('required', false);
			$('#gejala_ba_1').prop('required', false);
			$('#riwayat_ba_1').prop('required', false);
			$('#tindakan_ba_1').prop('required', false);
		}
	});
	//
	$(document).on("change", "#score_5, #score_6", function(e) {
		var score_5_val = $("input[name='score_5_val']").val();
		var score_6_val = $("input[name='score_6_val']").val();
		var score_total_val	= parseFloat(score_5_val) + parseFloat(score_6_val);
		if (score_total_val>=5){
			$("input[name='jumlah_berita_acara']").val(1);
			//show berita acara
			$("#div_berita_acara").show();
			$('#tanggal_ba_1').prop('required', true);
			$('#gejala_ba_1').prop('required', true);
			$('#riwayat_ba_1').prop('required', true);
			$('#tindakan_ba_1').prop('required', true);
		}else{
			$("input[name='jumlah_berita_acara']").val(0);
			//hide berita acara
			$("#div_berita_acara").hide();
			$('#tanggal_ba_1').prop('required', false);
			$('#gejala_ba_1').prop('required', false);
			$('#riwayat_ba_1').prop('required', false);
			$('#tindakan_ba_1').prop('required', false);
		}
	}); 
	//
    $(document).on("click", "#score_5", function(e) {
        var score_5 = document.querySelector('input[name="score_5"]:checked').value;
		$("input[name='score_5_val']").val(score_5);
        if (score_5 > 0){
            $('#hubungan_keluarga_5').prop('required', true);
            $('#hubungan_keluarga_5').prop('readonly', false);
            $('#hubungan_kategori_5').prop('required', true);
            $('#hubungan_kategori_5').prop('disabled', false);
        } else {
            $('#hubungan_keluarga_5').prop('required', false);
            $('#hubungan_keluarga_5').prop('readonly', true);
            $('#hubungan_kategori_5').prop('required', false);
            $('#hubungan_kategori_5').prop('disabled', true);
			$("input[name='hubungan_keluarga_5']").val('');
			$("select[name='hubungan_kategori_5']").val('').trigger("change");
        }
    });
    $(document).on("click", "#score_6", function(e) {
        var score_6 = document.querySelector('input[name="score_6"]:checked').value;
		$("input[name='score_6_val']").val(score_6);
        if (score_6 > 0) {
            $('#suhu_tertinggi_6').prop('required', true);
            $('#suhu_tertinggi_6').prop('readonly', false);
            $('#gejala_6').prop('required', true);
            $('#gejala_6').prop('readonly', false);
            $('#riwayat_dokter_6').prop('required', true);
            $('#riwayat_dokter_6').prop('readonly', false);
        } else {
            $('#suhu_tertinggi_6').prop('required', false);
            $('#suhu_tertinggi_6').prop('readonly', true);
            $('#gejala_6').prop('required', false);
            $('#gejala_6').prop('readonly', true);
            $('#riwayat_dokter_6').prop('required', false);
            $('#riwayat_dokter_6').prop('readonly', true);
			$("input[name='suhu_tertinggi_6']").val('');
			$("input[name='gejala_6']").val('');
			$("input[name='riwayat_dokter_6']").val('');
        }
    });	
	//	
    $(document).on("click", "#jawaban_8", function(e) {
        var jawaban_8 = document.querySelector('input[name="jawaban_8"]:checked').value;
        if (jawaban_8 == 'Ya') {
			$('#hubungan_keluarga_ganda_8').prop('required', true);
			$('#jarak_ganda_8').prop('required', true);
			$('.interaksi_ganda_8').prop('required', true);
			$('#hubungan_keluarga_ganda_8').prop('readonly', false);
			$('#jarak_ganda_8').prop('disabled', false);
			$('.interaksi_ganda_8').prop('disabled', false);
        } else {
			$('#hubungan_keluarga_ganda_8').prop('required', false);
			$('#jarak_ganda_8').prop('required', false);
			$('.interaksi_ganda_8').prop('required', false);
			$('#hubungan_keluarga_ganda_8').prop('readonly', true);
			$('#jarak_ganda_8').prop('disabled', true);
			$('.interaksi_ganda_8').prop('disabled', true);
			$("input[name='hubungan_keluarga_ganda_8']").val('');
			$("select[name='jarak_ganda_8']").val('').trigger("change");
			$('.interaksi_ganda_8').prop('checked', false);
        }
    });
    $(document).on("click", "#jawaban_9", function(e) {
        var jawaban_9 = document.querySelector('input[name="jawaban_9"]:checked').value;
        if (jawaban_9 == 'Ya') {
			$('#hubungan_keluarga_ganda_9').prop('required', true);
			$('#jarak_ganda_9').prop('required', true);
			$('.interaksi_ganda_9').prop('required', true);
			$('#hubungan_keluarga_ganda_9').prop('readonly', false);
			$('#jarak_ganda_9').prop('disabled', false);
			$('.interaksi_ganda_9').prop('disabled', false);
        } else {
			$('#hubungan_keluarga_ganda_9').prop('required', false);
			$('#jarak_ganda_9').prop('required', false);
			$('.interaksi_ganda_9').prop('required', false);
			$('#hubungan_keluarga_ganda_9').prop('readonly', true);
			$('#jarak_ganda_9').prop('disabled', true);
			$('.interaksi_ganda_9').prop('disabled', true);
			$("input[name='hubungan_keluarga_ganda_9']").val('');
			$("select[name='jarak_ganda_9']").val('').trigger("change");
			$('.interaksi_ganda_9').prop('checked', false);
        }
    });
    $(document).on("click", "#jawaban_10", function(e) {
        var jawaban_10 = document.querySelector('input[name="jawaban_10"]:checked').value;
        if (jawaban_10 == 'Ya') {
			$('#hubungan_keluarga_ganda_10').prop('required', true);
			$('#jarak_ganda_10').prop('required', true);
			$('.interaksi_ganda_10').prop('required', true);
			$('#hubungan_keluarga_ganda_10').prop('readonly', false);
			$('#jarak_ganda_10').prop('disabled', false);
			$('.interaksi_ganda_10').prop('disabled', false);
			
        } else {
			$('#hubungan_keluarga_ganda_10').prop('required', false);
			$('#jarak_ganda_10').prop('required', false);
			$('.interaksi_ganda_10').prop('required', false);
			$('#hubungan_keluarga_ganda_10').prop('readonly', true);
			$('#jarak_ganda_10').prop('disabled', true);
			$('.interaksi_ganda_10').prop('disabled', true);
			$("input[name='hubungan_keluarga_ganda_10']").val('');
			$("select[name='jarak_ganda_10']").val('').trigger("change");
			$('.interaksi_ganda_10').prop('checked', false);
        }
    });
    $(document).on("click", "#jawaban_11", function(e) {
        var jawaban_11 = document.querySelector('input[name="jawaban_11"]:checked').value;
        if (jawaban_11 == 'Ya') {
			$('#hubungan_keluarga_ganda_11').prop('required', true);
			$('#jarak_ganda_11').prop('required', true);
			$('.interaksi_ganda_11').prop('required', true);
			$('#hubungan_keluarga_ganda_11').prop('readonly', false);
			$('#jarak_ganda_11').prop('disabled', false);
			$('.interaksi_ganda_11').prop('disabled', false);
			
        } else {
			$('#hubungan_keluarga_ganda_11').prop('required', false);
			$('#jarak_ganda_11').prop('required', false);
			$('.interaksi_ganda_11').prop('required', false);
			$('#hubungan_keluarga_ganda_11').prop('readonly', true);
			$('#jarak_ganda_11').prop('disabled', true);
			$('.interaksi_ganda_11').prop('disabled', true);
			$("input[name='hubungan_keluarga_ganda_11']").val('');
			$("select[name='jarak_ganda_11']").val('').trigger("change");
			$('.interaksi_ganda_11').prop('checked', false);
        }
    });
	
	
	
	
	//sampe sini
    $(document).on("click", "#jawaban_1", function(e) {
        var jawaban_1 = document.querySelector('input[name="jawaban_1"]:checked').value;
        if (jawaban_1 == 'Lain-Lain') {
            $('#catatan_ganda_1').prop('required', true);
            $('#catatan_ganda_1').prop('disabled', false);
        } else {
            $('#catatan_ganda_1').prop('required', false);
            $('#catatan_ganda_1').prop('disabled', true);
			$("input[name='catatan_ganda_1']").val('');
        }
        //set pertanyaan2
        if (jawaban_1 == 'Tidak Ada') {
            $('.jawaban_2_1').prop('disabled', true);
            $('.jawaban_2_2').prop('disabled', true);
            $('.jawaban_2_3').prop('checked', true);
            $('.jawaban_2_4').prop('disabled', true); 
			$('#catatan_ganda_2').prop('required', false);
            $('#catatan_ganda_2').prop('disabled', true); 
			$("input[name='catatan_ganda_2']").val('');
        } else {
            $('.jawaban_2_1').prop('disabled', false);
            $('.jawaban_2_2').prop('disabled', false);
            $('.jawaban_2_3').prop('disabled', false);
            $('.jawaban_2_4').prop('disabled', false);
            $('.jawaban_2_3').prop('checked', false);
			
        }
    });
    $(document).on("click", "#jawaban_2", function(e) {
        var jawaban_2 = document.querySelector('input[name="jawaban_2"]:checked').value;
        if (jawaban_2 == 'Rawat di Rumah Sakit') {
            $('#catatan_ganda_2').prop('required', true);
            $('#catatan_ganda_2').prop('disabled', false);
        } else {
            $('#catatan_ganda_2').prop('required', false);
            $('#catatan_ganda_2').prop('disabled', true);
			$("input[name='catatan_ganda_2']").val('');
        }
    });
    $(document).on("click", "#jawaban_3", function(e) {
        var jawaban_3 = document.querySelector('input[name="jawaban_3"]:checked').value;
        if (jawaban_3 == 'Ada, hubungan & keluhannya adalah') {
            $('#catatan_ganda_3').prop('required', true);
            $('#catatan_ganda_3').prop('disabled', false);
        } else {
            $('#catatan_ganda_3').prop('required', false);
            $('#catatan_ganda_3').prop('disabled', true);
			$("input[name='catatan_ganda_3']").val('');
        }
        //set pertanyaan 4
        if (jawaban_3 == 'Tidak Ada') {
            $('.jawaban_4_1').prop('disabled', true);
            $('.jawaban_4_2').prop('checked', true);
            $('#catatan_ganda_4').prop('required', true);
            $('#catatan_ganda_4').prop('disabled', false);
            $("input[name='catatan_ganda_4']").val('Tidak Ada');
        } else {
            $('.jawaban_4_1').prop('disabled', false);
            $('.jawaban_4_2').prop('checked', false);
            $('#catatan_ganda_4').prop('required', false);
            $('#catatan_ganda_4').prop('disabled', true);
            $("input[name='catatan_ganda_4']").val('');
        }

    });
    $(document).on("click", "#jawaban_4", function(e) {
        $('#catatan_ganda_4').prop('required', true);
        $('#catatan_ganda_4').prop('disabled', false);
    });
    $(document).on("click", "#jawaban_5", function(e) {
        var jawaban_5 = document.querySelector('input[name="jawaban_5"]:checked').value;
        if (jawaban_5 == 'Tidak Ada') {
            $("input[name='catatan_ganda_6']").val('Tidak Ada');
        } else {
            $("input[name='catatan_ganda_6']").val('');
        }
        //set pertanyaan 4
        if (jawaban_5 == 'Tidak Ada') {
            $('.jawaban_7_1').prop('disabled', true);
            $('.jawaban_7_2').prop('disabled', true);
            $('.jawaban_7_3').prop('disabled', true);
            $('.jawaban_7_4').prop('disabled', true);
            $('.jawaban_7_5').prop('checked', true);
            $('#catatan_ganda_7').prop('required', true);
            $('#catatan_ganda_7').prop('disabled', false);
            $("input[name='catatan_ganda_7']").val('Tidak Ada');
        } else {
            $('.jawaban_7_1').prop('disabled', false);
            $('.jawaban_7_2').prop('disabled', false);
            $('.jawaban_7_3').prop('disabled', false);
            $('.jawaban_7_4').prop('disabled', false);
            $('.jawaban_7_5').prop('checked', false);
            $('#catatan_ganda_7').prop('required', false);
            $('#catatan_ganda_7').prop('disabled', true);
            $("input[name='catatan_ganda_7']").val('');
        }

    });

    $(document).on("click", "button[name='action_btn']", function(e) {
        let btn = $(this).val();
        if (btn == "back") {
			sessionStorage.clear();			  
            window.location = baseURL + 'assessment/transaksi/logout';
            return false;
        }
        var empty_form = validate(".form-input-assessment");
        if (empty_form == 0) {
            var formData = new FormData($(".form-input-assessment")[0]);
			console.log('aaaa');
            $.ajax({
                url: baseURL + 'assessment/transaksi/save/assessment',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: (data) => {
					
                    if (data.sts == 'OK') {
                        myAlert({
                            icon: "success",
                            html: data.msg,
                            reload: baseURL + 'assessment/transaksi/logout'
                        });
                    } else {
                        $("input[name='isproses']").val(0);
                        myAlert({
                            text: data.msg,
                            icon: "error",
                            html: false,
                            reload: false
                        });
                    }
                },
                error: () => {
                    myAlert({
                        text: "Server Error",
                        icon: "error",
                        html: false,
                        reload: false
                    });
                }
            });
        }
        e.preventDefault();
        return false;
    });
});