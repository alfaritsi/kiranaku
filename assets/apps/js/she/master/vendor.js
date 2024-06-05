$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-jenis").on("click", function(e){
    	var id_mjenis	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'she/master/set_data/activate/vendor',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mjenis : id_mjenis
			},
			success: function(data){
				if(data.sts == 'OK'){
                    kiranaAlert(data.sts, data.msg);
				}else{
                    kiranaAlert(data.sts, data.msg, "error", "no");
				}
			}
		});
    });

	$(".delete").on("click", function(e){
    	var id	= $(this).data("delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/master/set_data/delete_del0/vendor',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id : id
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );
    });

	$(".detail").on("click", function(e){
    	var id	= $(this).data("detail");

    	$('.input-sm').removeAttr('required');

        $("#jenislimbah_pengumpul").html("");
        $("#jenislimbah_mou").html("");
        $("#jenislimbah_rekom").html("");
        $("#jenislimbah_hubdar").html("");

    	$.ajax({
    		url: baseURL+'she/master/get_data/vendor',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id,
				pabrik : ''
			},
			success: function(data){
				// console.log(data);
				$(".modal-title").html("<i class='fa fa-folder-open-o'></i> Detail Data Transporter");
				$.each(data, function(i, v){
					$("#id").val(v.id);
					document.getElementById("pabrik").value = v.fk_pabrik;
					$('.select2').select2();

					var dataVendor = v;

			    	var pabrik = $("#pabrik").val()
			    	$.ajax({
			    		url: baseURL+'she/master/get_data/limbah',
						type: 'POST',
						dataType: 'JSON',
						data: {
							jenislimbah : '',
							pabrik : pabrik
						},
						success: function(data){					
			                // $.each(data, function(index, item){
			                //     $("#jenislimbah_pengumpul").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                //     $("#jenislimbah_mou").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                //     $("#jenislimbah_rekom").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                //     $("#jenislimbah_hubdar").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                // });		

			                for(var item of data){
			                    $("#jenislimbah_pengumpul").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                    $("#jenislimbah_mou").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                    $("#jenislimbah_rekom").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                    $("#jenislimbah_hubdar").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  	
			                }

							var jenislimbah = eval(dataVendor.kumpul_jenislimbah);
							$('#jenislimbah_pengumpul').val(jenislimbah).trigger("change");
							var jenislimbah = eval(dataVendor.mou_jenislimbah);
							$('#jenislimbah_mou').val(jenislimbah).trigger("change");
							var jenislimbah = eval(dataVendor.klhk_jenislimbah);
							$('#jenislimbah_rekom').val(jenislimbah).trigger("change");
							var jenislimbah = eval(dataVendor.dhd_jenislimbah);
							$('#jenislimbah_hubdar').val(jenislimbah).trigger("change");
							// $('.select2').select2();

						}
					});		
			    	$('.select2').select2();

					$("#vendor").append("<option value='"+v.LIFNR+"'>"+v.NAME1+"</option>");
					document.getElementById("vendor").value = v.LIFNR;

					$("#kodevendor").val(v.kode_vendor);
					$("#namapengumpul").val(v.nama_pengumpul);
					$("#namapemanfaat").val(v.nama_pemanfaat);

					ceklampiran_SPBP();
					$("#exppengumpul").val(v.izin_kumpul_expdate);
					$("#lampiranpengumpul").val(v.file_ikumpul);
					$("#view_file_ikumpul").append("<a title='Lihat file lampiran izin pengumpul' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_ikumpul+"' target='_blank'></a>");
					
					$('.select2').select2();
					$('.datePicker').datepicker({
						format: 'dd.mm.yyyy',
						changeMonth: true,
						changeYear: true,
						autoclose: true,
						startDate: new Date()
					});

					// tab 2
					$("#expmou").val(v.pihak_ketiga_expdate);
					$("#lampiranmou").val(v.file_ipihak_ketiga);
					$("#view_file_ipihak_ketiga").append("<a title='Lihat file lampiran MoU' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_ipihak_ketiga+"' target='_blank'></a>");
					$("#expbebascemar").val(v.pihak_ketiga_spbp_expdate);
					$("#lampiranbebascemar").val(v.file_pihak_ketiga_spbp);
					$("#view_file_pihak_ketiga_spbp").append("<a title='Lihat file lampiran Dokumen SP Bebas Pencemaran' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_pihak_ketiga_spbp+"' target='_blank'></a>");
					
					if(v.pihak_ketiga_spbp_expdate2 != null){
						$("#expbebascemar2").val(v.pihak_ketiga_spbp_expdate2);
						$("#lampiranbebascemar2").val(v.file_pihak_ketiga_spbp2);
						$("#view_file_pihak_ketiga_spbp2").append("<a title='Lihat file lampiran Dokumen SP Bebas Pencemaran (2)' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_pihak_ketiga_spbp2+"' target='_blank'></a>");
					}
					
					if(v.pihak_ketiga_spbp_expdate3 != null){
						$("#expbebascemar3").val(v.pihak_ketiga_spbp_expdate3);
						$("#lampiranbebascemar3").val(v.file_pihak_ketiga_spbp3);
						$("#view_file_pihak_ketiga_spbp3").append("<a title='Lihat file lampiran Dokumen SP Bebas Pencemaran (3)' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_pihak_ketiga_spbp3+"' target='_blank'></a>");
					}

					$("#exppemanfaat").val(v.pemanfaat_expdate);
					$("#lampiranpemanfaat").val(v.file_pemanfaat);
					$("#view_file_pemanfaat").append("<a title='Lihat file lampiran izin Pemanfaat' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_pemanfaat+"' target='_blank'></a>");
					$("#exppengumpulpemanfaat").val(v.pengumpulpemanfaat_expdate);
					$("#lampiranexppengumpulpemanfaat").val(v.file_pengumpulpemanfaat);
					$("#view_file_pengumpulpemanfaat").append("<a title='Lihat file lampiran MoU pengumpul & Pemanfaat' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_pengumpulpemanfaat+"' target='_blank'></a>");
					$('.select2').select2();

					// tab 4
					$("#exphubdar").val(v.angkut_dhd_expdate);
					$("#lampiranhubdar").val(v.file_angkut_dhd);
					$("#view_file_angkut_dhd").append("<a title='Lihat file lampiran Dokumen Rekom Angkut KLHK' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_angkut_dhd+"' target='_blank'></a>");
					$("#exphubdarspbp").val(v.angkut_dhd_spbp_expdate);
					$("#lampiranhubdarspbp").val(v.file_angkut_dhd_spbp);
					$("#view_file_angkut_dhd_spbp").append("<a title='Lihat file lampiran Dokumen Rekom Angkut KLHK' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_angkut_dhd_spbp+"' target='_blank'></a>");
					$('.select2').select2();

					// tab 3
					$("#exprekom").val(v.angkut_klhk_expdate);
					$("#lampiranrekom").val(v.file_angkut_klhk);
					$("#view_file_angkut_klhk").append("<a title='Lihat file lampiran Dokumen Rekom Angkut KLHK' class='glyphicon glyphicon-download-alt' href='"+baseURL+v.file_angkut_klhk+"' target='_blank'></a>");
					
					$("#btn-new").removeClass("hidden");
					viewmode();
				});

			}
		});

    });

	$(".edit").on("click", function(e){
    	var id	= $(this).data("edit");
    	$('.input-sm').removeAttr('required');

        $("#jenislimbah_pengumpul").html("");
        $("#jenislimbah_mou").html("");
        $("#jenislimbah_rekom").html("");
        $("#jenislimbah_hubdar").html("");

    	$.ajax({
    		url: baseURL+'she/master/get_data/vendor',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id,
				pabrik : ''
			},
			success: function(data){
				// console.log(data);
				$(".modal-title").html("<i class='fa fa-pencil-square-o'></i> Edit Data Transporter");
				$.each(data, function(i, v){
					$("#id").val(v.id);
					document.getElementById("pabrik").value = v.fk_pabrik;
					$('.select2').select2();

					var dataVendor = v;

			    	var pabrik = $("#pabrik").val()
			    	$.ajax({
			    		url: baseURL+'she/master/get_data/limbah',
						type: 'POST',
						dataType: 'JSON',
						data: {
							jenislimbah : '',
							pabrik : pabrik
						},
						success: function(data){					
			                // $.each(data, function(index, item){
			                //     $("#jenislimbah_pengumpul").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                //     $("#jenislimbah_mou").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                //     $("#jenislimbah_rekom").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                //     $("#jenislimbah_hubdar").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                // });		

			                for(var item of data){
			                    $("#jenislimbah_pengumpul").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                    $("#jenislimbah_mou").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                    $("#jenislimbah_rekom").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  
			                    $("#jenislimbah_hubdar").append("<option value='"+item.id+"'>"+item.jenis_limbah+"</option>");  	
			                }

							var jenislimbah = eval(dataVendor.kumpul_jenislimbah);
							$('#jenislimbah_pengumpul').val(jenislimbah).trigger("change");
							var jenislimbah = eval(dataVendor.mou_jenislimbah);
							$('#jenislimbah_mou').val(jenislimbah).trigger("change");
							var jenislimbah = eval(dataVendor.klhk_jenislimbah);
							$('#jenislimbah_rekom').val(jenislimbah).trigger("change");
							var jenislimbah = eval(dataVendor.dhd_jenislimbah);
							$('#jenislimbah_hubdar').val(jenislimbah).trigger("change");
							// $('.select2').select2();

						}
					});		
			    	$('.select2').select2();

					$("#vendor").append("<option value='"+v.LIFNR+"'>"+v.NAME1+"</option>");
					document.getElementById("vendor").value = v.LIFNR;

					$("#kodevendor").val(v.kode_vendor);
					$("#namapengumpul").val(v.nama_pengumpul);
					$("#namapemanfaat").val(v.nama_pemanfaat);

					ceklampiran_SPBP();
					$("#exppengumpul").val(v.izin_kumpul_expdate);
					$("#lampiranpengumpul").val(v.file_ikumpul);
					
					$('.select2').select2();
					$('.datePicker').datepicker({
						format: 'dd.mm.yyyy',
						changeMonth: true,
						changeYear: true,
						autoclose: true,
						startDate: new Date()
					});

					// tab 2
					$("#expmou").val(v.pihak_ketiga_expdate);
					$("#lampiranmou").val(v.file_pihak_ketiga);
					$("#expbebascemar").val(v.pihak_ketiga_spbp_expdate);
					$("#lampiranbebascemar").val(v.file_pihak_ketiga_spbp);
					
					if(v.pihak_ketiga_spbp_expdate2 != null){
						$("#expbebascemar2").val(v.pihak_ketiga_spbp_expdate2);
						$("#lampiranbebascemar2").val(v.file_pihak_ketiga_spbp2);
					}
					
					if(v.pihak_ketiga_spbp_expdate3 != null){
						$("#expbebascemar3").val(v.pihak_ketiga_spbp_expdate3);
						$("#lampiranbebascemar3").val(v.file_pihak_ketiga_spbp3);
					}

					$("#exppemanfaat").val(v.pemanfaat_expdate);
					$("#lampiranpemanfaat").val(v.file_pemanfaat);
					$("#exppengumpulpemanfaat").val(v.pengumpulpemanfaat_expdate);
					$("#lampiranexppengumpulpemanfaat").val(v.file_pengumpulpemanfaat);
					$('.select2').select2();

					// tab 4
					$("#exphubdar").val(v.angkut_dhd_expdate);
					$("#lampiranhubdar").val(v.file_angkut_dhd);
					$("#exphubdarspbp").val(v.angkut_dhd_spbp_expdate);
					$("#lampiranhubdarspbp").val(v.file_angkut_dhd_spbp);
					$('.select2').select2();

					// tab 3
					$("#exprekom").val(v.angkut_klhk_expdate);
					$("#lampiranrekom").val(v.file_angkut_klhk);
					

					$("#btn-new").removeClass("hidden");
					$("#action_btn").attr('disabled',false);
					editmode();
				});
			}
		});

    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-vendor");

        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses >= 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-vendor")[0]);
		    	// $("#action_btn").attr('disabled',true);
				$.ajax({
					url: baseURL+'she/master/save/vendor',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						if(data.sts == 'OK'){
		                    kiranaAlert(data.sts, data.msg);
						}else{
		                    kiranaAlert(data.sts, data.msg, "error", "no");
						}
					}
				});
			}else{
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
    });

	$("#pabrik").change(function() {
	  	var pabrik = $("#pabrik").val();

    	$.ajax({
    		url: baseURL+'she/master/get_data/vendor',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : '',
				pabrik : pabrik
			},
			success: function(data){
                $("#vendor").html("");
                $("#kodevendor").val("");
                $("#vendor").append("<option value=''> Silahkan Pilih</option>");  
                $.each(data, function(i, v){
                    $("#vendor").append("<option value='"+v.LIFNR+"'>"+v.NAME1+"</option>");  
                });				
			}
		});

    	$.ajax({
    		url: baseURL+'she/master/get_data/limbah',
			type: 'POST',
			dataType: 'JSON',
			data: {
				jenislimbah : '',
				pabrik : pabrik
			},
			success: function(data){
                $("#jenislimbah_pengumpul").html("");
                $("#jenislimbah_mou").html("");
                $("#jenislimbah_rekom").html("");
                $("#jenislimbah_hubdar").html("");
                $.each(data, function(i, v){
                    $("#jenislimbah_pengumpul").append("<option value='"+v.id+"'>"+v.jenis_limbah+"</option>");  
                    $("#jenislimbah_mou").append("<option value='"+v.id+"'>"+v.jenis_limbah+"</option>");  
                    $("#jenislimbah_rekom").append("<option value='"+v.id+"'>"+v.jenis_limbah+"</option>");  
                    $("#jenislimbah_hubdar").append("<option value='"+v.id+"'>"+v.jenis_limbah+"</option>");  
                });				
			}
		});

    });

	$("#vendor").change(function() {
	  	var vendor = $("#vendor").val();
		$("#kodevendor").val(vendor);
    });

	$("#chktranspengumpul").click(function() {
	  	var chk = this.checked;
	  	var sel = document.getElementById("vendor");
		var vendor = sel.options[sel.selectedIndex].text;
		if(chk == true){
			if(vendor == ""){
                kiranaAlert("notOK", "Nama Transporter belum dipilih", "warning", "no");
				return false;
			}
			$("#namapengumpul").val(vendor);
			document.getElementById("namapengumpul").readOnly = true;
		}else{
			$("#namapengumpul").val("");
			document.getElementById("namapengumpul").readOnly = false;
		}
		ceklampiran_SPBP();
    });

	$("#chkpengumpulpemanfaat").click(function() {
	  	var chkpengumpul = document.getElementById("chkpengumpulpemanfaat").checked;
	  	var chktransporter = document.getElementById("chktranspemanfaat").checked;
	  	var pengumpul = $("#namapengumpul").val();
		if(chkpengumpul == true){
			if(pengumpul == ""){
                kiranaAlert("notOK", "Nama Pengumpul belum terisi", "warning", "no");
				return false;
			}
			document.getElementById("chktranspemanfaat").checked = false;
			document.getElementById("namapemanfaat").readOnly = true;
			$("#namapemanfaat").val($("#namapengumpul").val());
		}else{
			$("#namapemanfaat").val("");
			document.getElementById("namapemanfaat").readOnly = false;
		}
		ceklampiran_SPBP();
    });

	$("#chktranspemanfaat").click(function() {
	  	var chkpengumpul = document.getElementById("chkpengumpulpemanfaat").checked;
	  	var chktransporter = document.getElementById("chktranspemanfaat").checked;
	  	var vendor = $("#vendor").val();
		if(chktransporter == true){
			if(vendor == ""){
                kiranaAlert("notOK", "Nama Transporter belum dipilih", "warning", "no");
				return false;
			}
			document.getElementById("chkpengumpulpemanfaat").checked = false;
		  	var sel = document.getElementById("vendor");
			var vendor= sel.options[sel.selectedIndex].text;
			$("#namapemanfaat").val(vendor);
			document.getElementById("namapemanfaat").readOnly = true;
		}else{
			$("#namapemanfaat").val("");
			document.getElementById("namapemanfaat").readOnly = false;
		}
		ceklampiran_SPBP();
    });

	$("#namapengumpul,#namapemanfaat").change(function() {
		ceklampiran_SPBP();
    });

	$("#jenislimbah_pengumpul").change(function() {
	  	var jenislimbah = $("#jenislimbah_pengumpul").val();
		// document.getElementById("jenislimbah_hubdar").value = jenislimbah;
		$("#jenislimbah_mou").val(jenislimbah);
		$("#jenislimbah_mou").trigger('change');
    });

	$("#jenislimbah_mou").change(function() {
	  	var jenislimbah = $("#jenislimbah_mou").val();
		// document.getElementById("jenislimbah_hubdar").value = jenislimbah;
		$("#jenislimbah_rekom").val(jenislimbah);
		$("#jenislimbah_rekom").trigger('change');
    });

	$("#jenislimbah_rekom").change(function() {
	  	var jenislimbah = $("#jenislimbah_rekom").val();
		// document.getElementById("jenislimbah_hubdar").value = jenislimbah;
		$("#jenislimbah_hubdar").val(jenislimbah);
		$("#jenislimbah_hubdar").trigger('change');
    });


    //date pitcker
    $('.datePicker').datepicker({
        format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        startDate: new Date()
    });

});

function cek_exppengumpul(){
  	$("#div_warning_exppengumpul").html("");
  	var date_sap = $("#exppengumpul").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");

  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);
	if(doc - now < 0 || $("#exppengumpul").val() == ""){
		$("#div_warning_exppengumpul").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}

function cek_expmou(){
  	$("#div_warning_expmou").html("");

  	var date_sap = $("#expmou").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");

  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);

	if(doc - now < 0 || $("#expmou").val() == ""){
		$("#div_warning_expmou").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}

function cek_expbebascemar(){
  	$("#div_warning_expbebascemar").html("");

  	var date_sap = $("#expbebascemar").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");
  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);

	if(doc - now < 0 || $("#expbebascemar").val() == ""){
		$("#div_warning_expbebascemar").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}

function cek_expbebascemar2(){
  	$("#div_warning_expbebascemar2").html("");

  	var date_sap = $("#expbebascemar2").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");
  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);

	if(doc - now < 0 || $("#expbebascemar2").val() == ""){
		$("#div_warning_expbebascemar2").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}

function cek_expbebascemar3(){
  	$("#div_warning_expbebascemar3").html("");

  	var date_sap = $("#expbebascemar3").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");
  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);

	if(doc - now < 0 || $("#expbebascemar3").val() == ""){
		$("#div_warning_expbebascemar3").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}

function cek_exppemanfaat(){
  	$("#div_warning_exppemanfaat").html("");

  	var date_sap = $("#exppemanfaat").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");
  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);

	if(doc - now < 0 || $("#exppemanfaat").val() == ""){
		$("#div_warning_exppemanfaat").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}

function cek_exppengumpulpemanfaat(){
  	$("#div_warning_exppengumpulpemanfaat").html("");

  	var date_sap = $("#exppengumpulpemanfaat").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");
  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);

	if(doc - now < 0 || $("#exppengumpulpemanfaat").val() == ""){
		$("#div_warning_exppengumpulpemanfaat").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}

function cek_exprekom(){
  	$("#div_warning_exprekom").html("");

  	var date_sap = $("#exprekom").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");
  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);

	if(doc - now < 0 || $("#exprekom").val() == ""){
		$("#div_warning_exprekom").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}

function cek_exphubdar(){
  	$("#div_warning_exphubdar").html("");

  	var date_sap = $("#exphubdar").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");
  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);

	if(doc - now < 0 || $("#exphubdar").val() == ""){
		$("#div_warning_exphubdar").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}

function cek_exphubdarspbp(){
  	$("#div_warning_exphubdarspbp").html("");

  	var date_sap = $("#exphubdarspbp").val();
  	var date = date_sap.replace('.', '-');
	date = date.replace('.', '-');
	date = date.split("-").reverse().join("-");
  	if(date == ""){
	  	var date = new Date();
  	}
  	var now = new Date();
	var doc = new Date(date);

	if(doc - now < 0 || $("#exphubdarspbp").val() == ""){
		$("#div_warning_exphubdarspbp").append("<span><strong>Harap perbaharui tanggal</strong></span>");
	}else{
	}
}


function ceklampiran_SPBP(){
	var sel = document.getElementById("vendor");
	var vendor = sel.options[sel.selectedIndex].text;
	var pengumpul = $("#namapengumpul").val();
	var pemanfaat = $("#namapemanfaat").val();

	$("#SPBP2").html("");
	$("#SPBP3").html("");
	$("#pemanfaat").html("");
	$("#pengumpulpemanfaat").html("");

	if(pengumpul != ""){
		$("#ijinpengumpul").html("");
		$("#ijinpengumpul").append("<div class='col-md-4'>"
				+"<div class='form-group'>"
				+"<label>Expdate Pengumpul :</label>"
				+"<div class='input-group date'>"
				+"<div class='input-group-addon'>"
				+"<i class='fa fa-calendar'></i>"
				+"</div>"
				+"<input type='text' class='datePicker init' name='exppengumpul' id='exppengumpul' onchange='cek_exppengumpul()' style='width:100%;height:32px;padding:10px;' readonly required>"
				+"<div id='div_warning_exppengumpul'></div>"
				+"</div>"
				+"</div>"
				+"</div>"
				+"<div class='col-md-6'>"
				+"<div class='form-group'>"
				+"<label>Izin Pengumpulan : </label>"
				+"<div id='view_file_ikumpul'> </div>"
				+"<input type='file' class='input-sm init' name='file_ikumpul' id='file_ikumpul' onchange='cek_exppengumpul()' style='width:100%;height:32px;'>"
				+"</div>"
				+"</div>");
	}else{
		$("#ijinpengumpul").html("");
	}

	if(pengumpul == "" && pemanfaat == ""){

	}else if(pengumpul != "" && pemanfaat == ""){
		if(vendor == pengumpul){

		}else{
			$("#SPBP2").html("");
			$("#SPBP3").html("");
			$("#SPBP2").append("<div class='col-md-4'>"
								+"<div class='form-group'>"
								+"<label>Expdate SP Bebas Pencemaran (2) :</label>"
								+"<div class='input-group date'>"
								+"<div class='input-group-addon'>"
								+"<i class='fa fa-calendar'></i>"
								+"</div>"
								+"<input type='text' class='datePicker init' name='expbebascemar2' id='expbebascemar2' onchange='cek_expbebascemar2()'  style='width:100%;height:32px;padding:10px;' readonly>"
								+"<div id='div_warning_expbebascemar2'></div>"
								+"</div>"
								+"</div>"
								+"</div>"
								+"<div class='col-md-8'>"
								+"<div class='form-group'>"
								+"<label>Dokumen SP Bebas Pencemaran (2) :</label>"
								+"<div id='view_file_pihak_ketiga_spbp2'> </div>"
								+"<input type='file' class='input-sm init' name='file_pihak_ketiga_spbp2' id='file_pihak_ketiga_spbp2' onchange='cek_expbebascemar2()' style='width:100%'>"
								+"</div>"
								+"</div>"
								+"<div class='clearfix'></div>");			
		}
	}else if(pengumpul == "" && pemanfaat != ""){
		if(vendor == pemanfaat){

		}else{
			$("#SPBP2").append("<div class='col-md-4'>"
								+"<div class='form-group'>"
								+"<label>Expdate SP Bebas Pencemaran (2) :</label>"
								+"<div class='input-group date'>"
								+"<div class='input-group-addon'>"
								+"<i class='fa fa-calendar'></i>"
								+"</div>"
								+"<input type='text' class='datePicker init' name='expbebascemar2' id='expbebascemar2' onchange='cek_expbebascemar2()' style='width:100%;height:32px;padding:10px;' readonly>"
								+"<div id='div_warning_expbebascemar2'></div>"
								+"</div>"
								+"</div>"
								+"</div>"
								+"<div class='col-md-8'>"
								+"<div class='form-group'>"
								+"<label>Dokumen SP Bebas Pencemaran (2) :</label>"
								+"<div id='view_file_pihak_ketiga_spbp2'> </div>"
								+"<input type='file' class='input-sm init' name='file_pihak_ketiga_spbp2' id='file_pihak_ketiga_spbp2' onchange='cek_expbebascemar2()' style='width:100%'>"
								+"</div>"
								+"</div>"
								+"<div class='clearfix'></div>");			
		}
	}else{
		if(vendor == pengumpul && vendor == pemanfaat && pengumpul == pemanfaat){

		}else if((vendor != pengumpul && vendor == pemanfaat) || (vendor == pengumpul && vendor != pemanfaat) || pengumpul == pemanfaat){
			$("#SPBP2").append("<div class='col-md-4'>"
								+"<div class='form-group'>"
								+"<label>Expdate SP Bebas Pencemaran (2) :</label>"
								+"<div class='input-group date'>"
								+"<div class='input-group-addon'>"
								+"<i class='fa fa-calendar'></i>"
								+"</div>"
								+"<input type='text' class='datePicker init' name='expbebascemar2' id='expbebascemar2' onchange='cek_expbebascemar2()' style='width:100%;height:32px;padding:10px;' readonly>"
								+"<div id='div_warning_expbebascemar2'></div>"
								+"</div>"
								+"</div>"
								+"</div>"
								+"<div class='col-md-8'>"
								+"<div class='form-group'>"
								+"<label>Dokumen SP Bebas Pencemaran (2) :</label>"
								+"<div id='view_file_pihak_ketiga_spbp2'> </div>"
								+"<input type='file' class='input-sm init' name='file_pihak_ketiga_spbp2' id='file_pihak_ketiga_spbp2' onchange='cek_expbebascemar2()' style='width:100%'>"
								+"</div>"
								+"</div>"
								+"<div class='clearfix'></div>");			
		}else{
			$("#SPBP2").append("<div class='col-md-4'>"
								+"<div class='form-group'>"
								+"<label>Expdate SP Bebas Pencemaran (2) :</label>"
								+"<div class='input-group date'>"
								+"<div class='input-group-addon'>"
								+"<i class='fa fa-calendar'></i>"
								+"</div>"
								+"<input type='text' class='datePicker init' name='expbebascemar2' id='expbebascemar2' onchange='cek_expbebascemar2()' style='width:100%;height:32px;padding:10px;' readonly>"
								+"<div id='div_warning_expbebascemar2'></div>"
								+"</div>"
								+"</div>"
								+"</div>"
								+"<div class='col-md-8'>"
								+"<div class='form-group'>"
								+"<label>Dokumen SP Bebas Pencemaran (2) :</label>"
								+"<div id='view_file_pihak_ketiga_spbp2'> </div>"
								+"<input type='file' class='input-sm init' name='file_pihak_ketiga_spbp2' id='file_pihak_ketiga_spbp2' onchange='cek_expbebascemar2()' style='width:100%'>"
								+"</div>"
								+"</div>"
								+"<div class='clearfix'></div>");			
			$("#SPBP3").append("<div class='col-md-4'>"
								+"<div class='form-group'>"
								+"<label>Expdate SP Bebas Pencemaran (3) :</label>"
								+"<div class='input-group date'>"
								+"<div class='input-group-addon'>"
								+"<i class='fa fa-calendar'></i>"
								+"</div>"
								+"<input type='text' class='datePicker init' name='expbebascemar3' id='expbebascemar3' onchange='cek_expbebascemar3()' style='width:100%;height:32px;padding:10px;' readonly>"
								+"<div id='div_warning_expbebascemar3'></div>"
								+"</div>"
								+"</div>"
								+"</div>"
								+"<div class='col-md-8'>"
								+"<div class='form-group'>"
								+"<label>Dokumen SP Bebas Pencemaran (3) :</label>"
								+"<div id='view_file_pihak_ketiga_spbp3'> </div>"
								+"<input type='file' class='input-sm init' name='file_pihak_ketiga_spbp3' id='file_pihak_ketiga_spbp3' onchange='cek_expbebascemar3()' style='width:100%'>"
								+"</div>"
								+"</div>"
								+"<div class='clearfix'></div>");							
		}	
	}

	if(pemanfaat != ""){
		$("#pemanfaat").append("<div class='col-md-4'>"
							+"<div class='form-group'>"
							+"<label>Expdate Pemanfaat :</label>"
							+"<div class='input-group date'>"
							+"<div class='input-group-addon'>"
							+"<i class='fa fa-calendar'></i>"
							+"</div>"
							+"<input type='text' class='datePicker init' name='exppemanfaat' id='exppemanfaat' onchange='cek_exppemanfaat()' style='width:100%;height:32px;padding:10px;' readonly>"
							+"<div id='div_warning_exppemanfaat'></div>"
							+"</div>"
							+"</div>"
							+"</div>"
							+"<div class='col-md-8'>"
							+"<div class='form-group'>"
							+"<label>Izin Pemanfaat :</label>"
							+"<div id='view_file_pemanfaat'> </div>"
							+"<input type='file' class='input-sm init' name='file_pemanfaat' id='file_pemanfaat' onchange='cek_exppemanfaat()' style='width:100%'>"
							+"</div>"
							+"</div>"
							+"<div class='clearfix'></div>");			
	}

	if(pemanfaat != "" & pengumpul != "" && pemanfaat != pengumpul){
		$("#pengumpulpemanfaat").append("<div class='col-md-4'>"
							+"<div class='form-group'>"
							+"<label>Expdate pengumpul & Pemanfaat :</label>"
							+"<div class='input-group date'>"
							+"<div class='input-group-addon'>"
							+"<i class='fa fa-calendar'></i>"
							+"</div>"
							+"<input type='text' class='datePicker init' name='exppengumpulpemanfaat' id='exppengumpulpemanfaat' onchange='cek_exppengumpulpemanfaat()' style='width:100%;height:32px;padding:10px;' readonly>"
							+"<div id='div_warning_exppengumpulpemanfaat'></div>"
							+"</div>"
							+"</div>"
							+"</div>"
							+"<div class='col-md-8'>"
							+"<div class='form-group'>"
							+"<label>Copy MoU pengumpul & Pemanfaat :</label>"
							+"<div id='view_file_pengumpulpemanfaat'> </div>"
							+"<input type='file' class='input-sm init' name='file_pengumpulpemanfaat' id='file_pengumpulpemanfaat' onchange='cek_exppengumpulpemanfaat()' style='width:100%'>"
							+"</div>"
							+"</div>"
							+"<div class='clearfix'></div>");			
	}

    $('.datePicker').datepicker({
        format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        startDate: new Date()
    });

}

function init(){
	editmode();
	document.getElementById("pabrik").value = "";
	document.getElementById("vendor").value = "";
	document.getElementById("jenislimbah_pengumpul").value = "";
	document.getElementById("jenislimbah_mou").value = "";
	document.getElementById("jenislimbah_rekom").value = "";
	document.getElementById("jenislimbah_hubdar").value = "";
	$(".select2").select2();
	$(".init").val("");
}

function viewmode(){
	$("#file_ikumpul").hide();
	$("#file_ipihak_ketiga").hide();
	$("#file_pihak_ketiga_spbp").hide();
	$("#file_pihak_ketiga_spbp2").hide();
	$("#file_pihak_ketiga_spbp3").hide();
	$("#file_pemanfaat").hide();
	$("#file_pengumpulpemanfaat").hide();
	$("#file_angkut_klhk").hide();
	$("#file_angkut_dhd").hide();
	$("#file_angkut_dhdspbp").hide();

	$("#action_btn").hide();
	$(".init").attr('disabled',true);
	$("#pabrik").attr('disabled',true);
	$("#vendor").attr('disabled',true);
	$("#jenislimbah_pengumpul").attr('disabled',true);
	$("#jenislimbah_mou").attr('disabled',true);
	$("#jenislimbah_rekom").attr('disabled',true);
	$("#jenislimbah_hubdar").attr('disabled',true);
}

function editmode(){
	$("#file_ikumpul").show();
	$("#file_ipihak_ketiga").show();
	$("#file_pihak_ketiga_spbp").show();
	$("#file_pihak_ketiga_spbp2").show();
	$("#file_pihak_ketiga_spbp3").show();
	$("#file_pemanfaat").show();
	$("#file_pengumpulpemanfaat").show();
	$("#file_angkut_klhk").show();
	$("#file_angkut_dhd").show();
	$("#file_angkut_dhdspbp").show();

	$("#action_btn").show();
	$("#action_btn").attr('disabled',false);
	$(".init").attr('disabled',false);
	$("#pabrik").attr('disabled',false);
	$("#vendor").attr('disabled',false);
	$("#jenislimbah_pengumpul").attr('disabled',false);
	$("#jenislimbah_mou").attr('disabled',false);
	$("#jenislimbah_rekom").attr('disabled',false);
	$("#jenislimbah_hubdar").attr('disabled',false)
}
