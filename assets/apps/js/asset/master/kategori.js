/*
@application  	: Equipment Management
@author     	: Lukman Hakim (7143)
@contributor  	: 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

$(document).ready(function(){

	$("#tab1").on("click", function(e){
		$("#box-add-kategori").removeClass("hidden");
		$("#box-add-jenis").addClass("hidden");
	});

	$("#tab2").on("click", function(e){
		$("#box-add-kategori").addClass("hidden");
		$("#box-add-jenis").removeClass("hidden");
	});

	$('.datatable-custom').DataTable({
		order: [[0, 'asc']],
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        "iDisplayLength": 50,
        "paging": true,
        columnDefs: [
            { "className": "text-center", "targets": 2 },
            { "className": "text-center", "targets": 1 },
            { "className": "text-center", "targets": 3 },
        ],
    });

    $('.datatable-customs').DataTable({
		order: [[0, 'asc']],
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        "iDisplayLength": 50,
        "paging": true,
        columnDefs: [
            { "className": "text-left", "targets": 2 },
            { "className": "text-left", "targets": 1 },
            { "className": "text-left", "targets": 3 },
            { "className": "text-left", "targets": 4 },
        ],
    });


    // $(".edit_kategori").on("click", function(e){
	$(document).on("click", ".edit_kategori", function (e) {	
    	var id_kategori	= $(this).data("edit-kat");
    	$.ajax({
    		url: baseURL+'asset/master/get_kategori',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_kategori : id_kategori
			},
			success: function(data){
				// console.log(data);
				$(".title-form-kat").html("<strong>Edit Kategori Asset</strong>");
				$.each(data, function(i, v){
					$("#kategori").val(v.nama);
					$("#ket_kategori").val(v.keterangan);

					$("input[name='id_kategori']").val(v.id_kategori);
					$("#btn-new-kat").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-kat").on("click", function(e){
		$("#kategori").val("");
		$("#ket_kategori").val("");
		$("input[name='id_kategori']").val("");
	    $(".title-form-kat").html("<strong>Buat Kategori Baru</strong>");
	    $("#btn-new-kat").addClass("hidden");
    });


	$(".form-master-kategori").on("submit", function(e){

		//var id_kategori = $("input[name='id_kategori']").val();
		//if id kategori == null -> insert
		//if id kategori != null -> edit
		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
	    	var formData = new FormData($(this)[0]);

			$.ajax({
				url: baseURL+'asset/master/save_kategori',
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
						$("input[name='isproses']").val(0);
					}
				}
			});
		}else{
			alert("Silahkan tunggu proses selesai.");
		}
		e.preventDefault();
		return false;
	});

	// $(".edit_jenis").on("click", function(e){
	$(document).on("click", ".edit_jenis", function (e) {	
    	var id_jenis	= $(this).data("edit-jen");
    	$.ajax({
    		url: baseURL+'asset/master/get_jenis',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_jenis : id_jenis
			},
			success: function(data){

				$(".title-form-jen").html("<strong>Edit Jenis Asset</strong>");
				$.each(data, function(i, v){
					$("#kategori_jen").val(v.id_kategori).trigger('change');
					$("#input_jenis").val(v.nama);
					$("#ket_jenis").val(v.keterangan);
					$("#periode_fo").val(v.periode);
					$("#alat_berat").val(v.berat).trigger('change');

					$("input[name='id_jenis']").val(v.id_jenis);
					//for check box
					if(v.keep_it=='y'){
						$('input[name=keep_it]').prop('checked', true);
					} else {
						$('input[name=keep_it]').prop('checked', false);
					}

					//for check box
					if(v.have_ratio=='y'){
						$('input[name=have_ratio]').prop('checked', true);
					} else {
						$('input[name=have_ratio]').prop('checked', false);
					}
					$("#pic").val(v.pic).trigger('change');
					
					$("#btn-new-jen").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-jen").on("click", function(e){
		$("#kategori_jen").val("0").trigger('change');
		$("#input_jenis").val("");
		$("#ket_jenis").val("");
		$("#periode_fo").val("0");
		$("#alat_berat").val("n").trigger('change');
		$("input[name='id_jenis']").val("");
	    $(".title-form-jen").html("<strong>Buat Jenis Asset Baru</strong>");
	    $("#btn-new-jen").addClass("hidden");
    });

	$(".form-master-jenis").on("submit", function(e){

		//var id_jenis = $("input[name='id_jenis']").val();
		//var jenis_asset = $("input[name='jenis_asset']").val();
		//var ket_jenis = $("input[name='ket_jenis']").val();
		//if id jenis == null -> insert
		//if id jenis != null -> edit

		var kategori_jen = $("#kategori_jen").val();
		if (kategori_jen == 0) {
			kiranaAlert("notOK", "Pilih Kategori Jenis Asset", "warning", "no");
			e.preventDefault();
			return false;
		}else{
			var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(this)[0]);

				$.ajax({
					url: baseURL+'asset/master/save_jenis',
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
							$("input[name='isproses']").val(0);
						}
					}
				});
			}else{
				alert("Silahkan tunggu proses selesai.");
			}
			e.preventDefault();
			return false;
		}
	});


	// ==============================================================
	// $(".edit_komponen").on("click", function(e){
	$(document).on("click", ".edit_komponen", function (e) {	
    	var id_jenis_detail	= $(this).data("edit-komponen");
    	$.ajax({
    		url: baseURL+'asset/master/get_jenis_detail',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_jenis_detail : id_jenis_detail
			},
			success: function(data){
				// console.log(data);
				$(".title-form-komponen").html("<strong>Edit Komponen Jenis</strong>");
				$.each(data, function(i, v){
					$("#jenis_detail").val(v.nama);
					$("#ket_komponen").val(v.keterangan);
					$("#kolom_aset").val(v.kolom_aset).trigger('change');
					$("input[name='id_jenis_detail']").val(v.id_jenis_detail);
					$("#btn-new-komponen").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new-komponen").on("click", function(e){
		$("#jenis_detail").val("");
		$("#ket_komponen").val("");
		$("input[name='id_jenis_detail']").val("");
	    $(".title-form-komponen").html("<strong>Buat Komponen Baru</strong>");
	    $("#btn-new-komponen").addClass("hidden");
    });

	$(".form-master-komponen").on("submit", function(e){

		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
	    	var formData = new FormData($(this)[0]);

			$.ajax({
				url: baseURL+'asset/master/save_jenis_detail',
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
						$("input[name='isproses']").val(0);
					}
				}
			});
		}else{
			alert("Silahkan tunggu proses selesai.");
		}
		e.preventDefault();
		return false;
		
	});

	$(document).on("click", ".set_active, .non_active, .delete", function (e) {
		// alert(tabs + $(this).attr("class") + $(this).data($(this).attr("class")));
		var tabs = $(this).data("tab");
		if(tabs == 'kategori'){
			$.ajax({
				url: baseURL + "asset/master/set/kategori",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_kategori	 : $(this).data($(this).attr("class")),	
					type 	  	 : $(this).attr("class")
				},
				success: function(data){
					if(data.sts == 'OK'){
						kiranaAlert(data.sts, data.msg);
					}else{
						kiranaAlert("notOK", data.msg, "warning", "no");
					}
				}
			});
			e.preventDefault();
			return false;
		}else if(tabs == 'jenis_asset'){
			$.ajax({
				url: baseURL + "asset/master/set/jenis_asset",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_jenis	 : $(this).data($(this).attr("class")),	
					type 	  	 : $(this).attr("class")
				},
				success: function(data){
					if(data.sts == 'OK'){
						kiranaAlert(data.sts, data.msg);
					}else{
						kiranaAlert("notOK", data.msg, "warning", "no");
					}
				}
			});
			e.preventDefault();
			return false;
		}else if(tabs == 'komponen'){
			$.ajax({
				url: baseURL + "asset/master/set/komponen",
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_jenis_detail	 : $(this).data($(this).attr("class")),	
					type 	  	 	 : $(this).attr("class")
				},
				success: function(data){
					if(data.sts == 'OK'){
						kiranaAlert(data.sts, data.msg);
					}else{
						kiranaAlert("notOK", data.msg, "warning", "no");
					}
				}
			});
			e.preventDefault();
			return false;
		}else{
			alert("something went wrong");
		}

	});

	
});