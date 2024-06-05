$(document).ready(function(){
	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "asset/transaksi/set/hrga",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_aset 	 : $(this).data($(this).attr("class")),	
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
	});	
	
    //=======FILTER=======//
	$(document).on("change", "#jenis, #merk, #pabrik, #lokasi, #area", function(){
		var jenis	= $("#jenis").val();
		var merk 	= $("#merk").val();
		var pabrik 	= $("#pabrik").val();
		var lokasi 	= $("#lokasi").val();
		var area 	= $("#area").val();
		//buat session login
		var session_id_user 	= $("#session_id_user").val();
		var session_id_divisi 	= $("#session_id_divisi").val();
		var session_id_level 	= $("#session_id_level").val();
		
		
		$.ajax({
			url: baseURL+'asset/transaksi/get/approval/it/retire',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	jenis 	: jenis,
	        	merk 	: merk,
	        	pabrik 	: pabrik,
	        	lokasi 	: lokasi,
	        	area	: area,
				proses	: 'set_retire'
	        },
	        success: function(data){
				console.log(data);
				var output 	= "";
	        	var desc	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					
					// //option action
					// if(v.flag == 'menunggu'){ 
						// output = "			<div class='input-group-btn'>";
						// output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						// output += "				<ul class='dropdown-menu pull-right'>";
						// output += "					<li><a href='javascript:void(0)' class='set_proses' data-id_aset='"+v.id_aset+"'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
						// output += "					<li><a href='javascript:void(0)' class='detail' data-id_aset='"+v.id_aset+"'><i class='fa fa-search'></i> Detail</a></li>";
						// output += "				</ul>";
						// output += "	        </div>";
					// }else{
						// output = "			<div class='input-group-btn'>";
						// output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						// output += "				<ul class='dropdown-menu pull-right'>";
						// output += "					<li><a href='javascript:void(0)' class='detail' data-id_aset='"+v.id_aset+"'><i class='fa fa-search'></i> Detail</a></li>";
						// output += "				</ul>";
						// output += "	        </div>";
						
					// }
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
												if((session_id_divisi == 754)&&(session_id_level == 9102)){
													if(v.flag=='menunggu'){
														output += "<li><a href='javascript:void(0)' class='set_proses' data-id_aset='"+v.id_aset+"'><i class='fa fa-arrow-circle-right'></i> Proses Persetujuan</a></li>";
													}
												}
												if(session_id_user == v.login_buat){
													output += "<li><a href='javascript:void(0)' class='detail' data-id_aset='"+v.id_aset+"' data-act='batal'><i class='fa fa-search'></i> Detail</a></li>";
												}else{
													output += "<li><a href='javascript:void(0)' class='detail' data-id_aset='"+v.id_aset+"'><i class='fa fa-search'></i> Detail</a></li>";
												}
						output += "				</ul>";
						output += "	        </div>";
					
					
					// console.log(v);
					//generate rows
	        		t.row.add( [
			            generateDateFormat(v.tanggal_buat),
			            v.KODE_BARANG,
			            v.nomor_sap,
			            v.nama_jenis,
						v.nama_pabrik,
						v.nama_lokasi,
						v.nama_area,
						generateDateFormat(v.tanggal_retire),
						v.alasan,
						v.opt_opsi,
						v.no_doc,
						v.file_ba,
						v.label_flag,
						output
			        ] ).draw( false );
	        	});
			
	        }
		});
	});
	
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Retirement Asset',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12]
                }
            }
        ],
		scrollX:true
    } );
	
	//opt alasan
    $(document).on("change", "#opt_alasan", function (e) {
        var value = $(this).val();
		if(value=='Lain-Lain'){
			var nil = '<input type="text" class="form-control" name="alasan" id="alasan">';	
			$("#show_alasan").html(nil);
		}else{
			var nil = '<input type="hidden" class="form-control" name="alasan" id="alasan" value="'+value+'">';	
			$("#show_alasan").html(nil);
		}
    });

    //opt Opsi
    $(document).on("change", "#opt_opsi", function (e) {
        var value = $(this).val();
		if(value=='Pengajuan KIASS'){
            $(".p-asset").addClass('hide');
			$(".p-asset").attr("required", false);
			$(".p-kiass").removeClass('hide');
            $(".p-kiass").attr("required", true);
            
            $("input[name='caption_lampiran']").val("");
            $("input[name='caption_lampiran']").attr("title", "");
            $("input[name='lampiran[]']").val("");

        }else if(value=='Penghapusan Asset'){
			$(".p-asset").removeClass('hide');
			$(".p-asset").attr("required", true);
			$(".p-kiass").addClass('hide');
            $(".p-kiass").attr("required", false);
            $("input[name='no_doc']").val("");
		}else{
			$(".p-asset").addClass('hide');
			$(".p-asset").attr("required", false);
			$(".p-kiass").addClass('hide');
            $(".p-kiass").attr("required", false);
            $("input[name='caption_lampiran']").val("");
            $("input[name='caption_lampiran']").attr("title", "");
            $("input[name='lampiran[]']").val("");
            $("input[name='no_doc']").val("");
		}
    });

    $(document).on("change", ".upload_file", function (e) {
		$(this).closest(".input-group").find(".caption_file").val(e.target.files[0].name);
		$(this).closest(".input-group").find(".caption_file").attr("title", e.target.files[0].name);
	});

	$(document).on("click", ".view_file", function () {
		if ($(this).data("link") !== "") {
			window.open(baseURL + $(this).data("link"), '_blank');
		} else {
			kiranaAlert("notOK", "File Tidak Ditemukan", "warning", "no");
		}
	});

    $(document).on("click", ".btn_upload_file", function() {
		$(this).closest(".input-group-btn").find(".upload_file").click();
    });

	// save retire
    $(document).on("click", "button[name='action_btn_retire']", function (e) {
        var empty_form = validate('.form-transaksi-retire');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-retire")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'asset/transaksi/save/set_retire',
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
	
    //open modal for add     
    $(document).on("click", "#add_button", function (e) {
        resetForm_use($('form[name="form-transaksi-jadwal-perbaikan"]'));
        $('#id_aset_ajax').select2({
            ajax: {
                delay: 250,
                url: baseURL + 'asset/maintenance/get/it/jadwal',
                method: 'POST',
                data: function (params) {
                    return {
                        term: params.term,
                        _type: 'query',
                        // jenis_tindakan: 'perbaikan'
                    };
                },
                dataType: 'json',
                processResults: function (data) {

                    // Tranforms the top-level key of the response object from 'items' to 'results'

                    data.data.forEach(function (v) {
                        v.id = v.id_aset;
                        v.text = v.detail_aset_it.split('||').join(' - ');
                    });

                    let result = Array.from(new Set(data.data.map(s => s.id)))
                        .map(id => {
                            return data.data.find(s => s.id === id);
                        });

                    return {
                        results: result
                    };
                },
                cache: true
            },
            placeholder: 'Cari aset (kode barang atau nomor sap)',
            minimumInputLength: 3,
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: formatSearchAset,
            templateSelection: formatRepoSelection
        });
		
        $('#add_modal_perbaikan').modal('show');
    });
	
    //set proses
    $(document).on("click", ".set_proses", function () {
        var id_aset = $(this).data("id_aset");

        $.ajax({
            url: baseURL + 'asset/transaksi/get/approval',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset : id_aset,
				proses	: 'set_retire'
            },
            success: function (data) {
                console.log(data);
                $(".title-form").html("Edit Setting Program Batch");
                $.each(data, function (i, v) {
					$('#label_nomor_sap_move').html(v.nomor_sap);
					$('#label_kode_barang_move').html(v.KODE_BARANG);
					$('#label_nama_jenis_move').html(v.nama_jenis);
					$('#label_nama_pabrik_move').html(v.nama_pabrik);
					$('#label_nama_lokasi_move').html(v.nama_lokasi);
					$('#label_nama_area_move').html(v.nama_area);
					$('#label_nama_alasan_move').html(v.alasan);
					
					$("input[name='id_aset']").val(v.id_aset);
                    $("input[name='nama_user']").val(v.NAMA_USER);
                    $("input[name='pic']").val(v.pic);
                    $("input[name='pic_awal']").val(v.pic);
                    $("input[name='id_sub_lokasi_awal']").val(v.id_sub_lokasi);
                    $("input[name='id_area_awal']").val(v.id_area);
                    
                    // CR 2418
                    $('#label_nama_opsi_move').html(v.opt_opsi);                    
                    if(v.file_ba){
                        $(".mv-ba").removeClass('hide');
                        $("input[name='caption_lampiran_move']").closest('.input-group').find('.view_file').attr("data-link", v.file_ba);
                        $("input[name='caption_lampiran_move']").val(v.file_ba.split('/').pop());
                    }

                    if(v.no_doc){
                        $(".mv-kiass").removeClass('hide');
                        $('#label_nama_no_doc_move').html(v.no_doc);
                    }

                    if (v.pic) {
                        var option = new Option(v.NAMA_USER, v.pic, true, true);
                        $("#set_pic").append(option).trigger('change');
                        $("#set_pic").trigger({
                            type: 'select2:select',
                            params: {
                                data: [{text: v.nama_pic, id: v.pic}]
                            }
							
                        });
                    }else
                        $("#set_pic").val(null).trigger('change');
                });

            },
            complete: function () {
                $('#set_proses_modal').modal('show');
            }

        });
    });
    //detail
    $(document).on("click", ".detail", function () {
        var id_aset = $(this).data("id_aset");
		var action 	= $(this).data("act");
        $.ajax({
            url: baseURL + 'asset/transaksi/get/approval',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset : id_aset,
				proses	: 'set_retire'
            },
            success: function (data) {
                console.log(data);
                $(".title-form").html("Detail Set Retire");
                $.each(data, function (i, v) {
                    $("#id_aset_post").val(v.id_aset);
                    $("#flag").val(v.flag);
                    $("#proses").val(v.proses);
					
					$('#label_nomor_sap').html(v.nomor_sap);
					$('#label_kode_barang').html(v.KODE_BARANG);
					$('#label_nama_jenis').html(v.nama_jenis);
					$('#label_nama_pabrik').html(v.nama_pabrik);
					$('#label_nama_lokasi').html(v.nama_lokasi);
					$('#label_nama_area').html(v.nama_area);
					$('#label_nama_alasan').html(v.alasan);
					$('#label_nama_label_flag').html(v.label_flag);
                    $('#label_nama_catatan').html(v.keterangan);
                    
                    // CR 2418
                    $('#label_nama_opsi').html(v.opt_opsi);
                    if(v.file_ba){
                        $(".det-ba").removeClass('hide');
                        $("input[name='caption_lampiran_detail']").closest('.input-group').find('.view_file').attr("data-link", v.file_ba);
                        $("input[name='caption_lampiran_detail']").val(v.file_ba.split('/').pop());
                    }

                    if(v.no_doc){
                        $(".det-kiass").removeClass('hide');
                        $('#label_nama_no_doc').html(v.no_doc);
                    }

                });
            },
            complete: function () {
				if(action=='batal'){
					$("#action_btn_batal").show();
				}else{
					$("#action_btn_batal").hide();
				}
                $('#set_detail_modal').modal('show');
            }

        });
    });
	
	// save proses
    $(document).on("click", "button[name='action_btn_proses']", function (e) {
        var empty_form = validate('.form-transaksi-proses');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-proses")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'asset/transaksi/save/proses_retire',
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
	// batal proses
    $(document).on("click", "button[name='action_btn_batal']", function (e) {
        var empty_form = validate('.form-transaksi-proses-detail');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-transaksi-proses-detail")[0]);
                // console.log();
                $.ajax({
                    url: baseURL + 'asset/transaksi/save/batal_retire',
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

    function formatRepoSelection(aset) {
        $('input[name="id_jenis"]').val(aset.id_jenis);
        $('input[name="kode"]').val(aset.kode);
        return aset.text || aset.detail_aset_it.split('||').join(' - ');
    }

    function formatSearchAset(aset) {
        if (aset.loading) {
            return aset.text;
        }

        var markup = "<div class='select2-result-aset clearfix'>" + aset.detail_aset_it.split('||').join('<br/>') + "</div>";

        return markup;
    }
	
	
	function resetForm_use($form) {
		$('#myModalLabel').html("Retire Asset");
		$('#pabrik').select2('destroy').find('option').prop('selected', false).end().select2();
		$form.find('input:text, input:password, input:file,  textarea').val("");
		$form.find('select').val(0);
		$form.find('input:radio, input:checkbox')
			 .removeAttr('checked').removeAttr('selected');
		$('#add_attch').html("");
		$('#list_attch').html("");    
		$('#hidden_file_dellist').val("");
		$('#isproses').val("");
		$('#isconvert').val('0');
		
	}
	//date pitcker
	$('.tanggal').datepicker({
		format: 'yyyy-mm-dd',
		// startDate: new Date(),
		autoclose: true
		
	});
	
	
});