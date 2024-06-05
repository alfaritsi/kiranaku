$(document).ready(function() {
    //switch
    $('.switch-onoff').bootstrapToggle({
        on: 'Yes',
        off: 'No'
    });
	//hide approve
	$("#btn_approve").hide();
	
    //cek all
    $(document).on("change", ".isSelectAllPlantExtend", function(e) {
        if ($(".isSelectAllPlantExtend").is(':checked')) {
            $('#plant_extend').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#plant_extend').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
	
    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
        if (oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        }
    };

    datatables_ssp();

    //=======FILTER=======//
    $(document).on("change", "#id_tipe_filter, #id_kategori_filter, #status_filter", function() {
        datatables_ssp();
    });
	
	//set on change id_tipe
    $(document).on("change", "#id_tipe", function(e){
		var id_tipe	= $(this).val();
		if(id_tipe!=''){
			$.ajax({ 
				url: baseURL + 'vendor/master/get/tipe_dokumen',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_tipe	: id_tipe
				}, 
				success: function (data) {
					if (data) {
						var output 	= '';
						output += '<div class="col-xs12">';
						output += '<fieldset class="fieldset-info">';
						output += '<legend>Dokumen Tipe Vendor</legend>';
						$.each(data, function (i, v) {
							if(v.mandatory=='y'){
								var req 	= 'required="required"';
								var flag	= '*';	
							}else{
								var req 	= '';
								var flag	= '';	
							}
							output += '		<div class="form-group">';
							output += '			<div class="row">';
							output += '				<div class="col-xs-12">';
							output += '					<label for="nama">'+v.nama+' '+flag+'</label>';
							output += '					<input type="file" class="form-control form-control-hide" name="file_dokumen_'+v.id_tipe_dokumen+'" id="file_dokumen_'+v.id_tipe_dokumen+'" '+req+'>';
							// output += '					<input type="file" multiple="multiple" class="form-control" id="dokumen_tipe_'+v.id_tipe_dokumen+'" name="dokumen_tipe_'+v.id_tipe_dokumen+'[]" '+req+'>';
							output += '				</div>';
							output += '			</div>';
							output += '		</div>';	
						});
						output += '</fieldset>';
						output += '</div>';
						$("#show_dokumen_tipe").html(output);
					}
				} 
			});			
		}else{
			$("#show_dokumen_tipe").html('');
		}
    });
	
	//set on change id_kategori
    $(document).on("change", "#id_kategori", function(e){
		var id_kategori	= $(this).val();
		if(id_kategori!=''){
			$.ajax({ 
				url: baseURL + 'vendor/master/get/kategori_dokumen',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_kategori	: id_kategori
				}, 
				success: function (data) {
					if (data) {
						var output 	= '';
						output += '<div class="col-xs12">';
						output += '<fieldset class="fieldset-info">';
						output += '<legend>Dokumen Kategori Vendor</legend>';
						$.each(data, function (i, v) {
							if(v.mandatory=='y'){
								var req 	= 'required="required"';
								var flag	= '*';	
							}else{
								var req 	= '';
								var flag	= '';	
							}
							output += '		<div class="form-group">';
							output += '			<div class="row">';
							output += '				<div class="col-xs-12">';
							output += '					<label for="nama">'+v.nama+' '+flag+'</label>';
							output += '					<input type="file" class="form-control form-control-hide" name="file_dokumen_'+v.id_tipe_dokumen+'" id="file_dokumen_'+v.id_tipe_dokumen+'" '+req+'>';
							// output += '					<input type="file" multiple="multiple" class="form-control" id="dokumen_tipe_'+v.id_tipe_dokumen+'" name="dokumen_tipe_'+v.id_tipe_dokumen+'[]" '+req+'>';
							output += '				</div>';
							output += '			</div>';
							output += '		</div>';	
						});
						output += '</fieldset>';
						output += '</div>';
						$("#show_dokumen_kategori").html(output);
					}
				} 
			});			
		}else{
			$("#show_dokumen_kategori").html('');
		}
    });
	
    //set on change negara
    $(document).on("change", "#negara", function(e) {
        var negara = $(this).val();
        $.ajax({
            url: baseURL + 'vendor/transaksi/get/provinsi',
            type: 'POST',
            dataType: 'JSON',
            data: {
                negara: negara
            },
            success: function(data) {
                var value = '';
                value += '<option value="0">Pilih Provinsi</option>';
                $.each(data, function(i, v) {
                    // console.log(data);
                    value += '<option value="' + v.id_provinsi + '">'+v.id_provinsi+' - '+ v.nama_provinsi + '</option>';
                });
                $('#provinsi').html(value);
            }
        });
    });
    //set on change add pilihan
    $(document).on("change", "#add_pilihan", function(e) {
		var stat 	= $(this).prop('checked');
		if(stat==true){
			$('#add_vendor_existing').prop('required', true);
			$('#add_vendor_existing').prop('disabled', false);
			$('#add_alasan').prop('required', true);
			$('#add_alasan').prop('disabled', false);
			$('#add_vendor_flag').prop('required', true);
			$('#add_vendor_flag').prop('disabled', false);
		}else{
			$('#add_vendor_existing').prop('required', false);
			$('#add_vendor_existing').prop('disabled', true);
			$('#add_alasan').prop('required', false);
			$('#add_alasan').prop('disabled', true);
			$('#add_vendor_flag').prop('required', false);
			$('#add_vendor_flag').prop('disabled', true);
			$("input[name='add_vendor_existing']").val('');
			$("select[name='add_alasan']").val('').trigger("change.select2");
			$("input[name='add_vendor_flag']").val('');
			
		}
    });
	
	//change id_nilai
    $(document).on("click", "#opt_nilai", function (e) {
		// var id_nilai	= $(this).val();
		var id_kriteria	= $(this).data("id_kriteria");
		var id_nilai	= $(this).data("id_nilai");
		var nilai		= $(this).data("nilai");
		var bobot		= $(this).data("bobot");
		var max			= $(this).data("max");
		$("input[name='id_nilai_"+id_kriteria+"']").val(id_nilai);	
		$("input[name='nilai_"+id_kriteria+"']").val(numberWithCommas(parseFloat(nilai).toFixed(0)));	
		$("input[name='nilai_bobot_"+id_kriteria+"']").val(numberWithCommas(parseFloat(nilai*bobot/100).toFixed(0)));	
		$("input[name='nilai_max_"+id_kriteria+"']").val(numberWithCommas(parseFloat(max*bobot/100).toFixed(0)));	
		//total
		var nilai_bobot_1 = $('input[name="nilai_bobot_1"]').val();
		var nilai_bobot_2 = $('input[name="nilai_bobot_2"]').val();
		var nilai_bobot_3 = $('input[name="nilai_bobot_3"]').val();
		var total_penilaian	= parseInt(nilai_bobot_1)+parseInt(nilai_bobot_2)+parseInt(nilai_bobot_3);
		$("input[name='total_penilaian']").val(total_penilaian);	
    });

    //edit, copy dan change  
    $(document).on("click", ".edit", function() {
        resetForm_use($('.form-transaksi-extend-vendor'), 'edit');
        var id_data = $(this).data("id_data");
		var action 	= $(this).data("action");
		alert(id_data);
        $.ajax({
            // url: baseURL + 'material/transaksi/get/spec',
            url: baseURL + 'vendor/transaksi/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data
            },
            success: function(data) {
                if (action == 'approve') {
                    $(".modal-title").html("Approve Master Vendor");
                }else{
                    $(".modal-title").html("Edit Master Vendor");
                }
				
				
                $.each(data, function(i, v) {
                    // console.log(data);
					//tab data vendor
                    $("#id_data").val(v.id_data);
                    $("select[name='plant']").val(v.plant).trigger("change.select2");
                    $("select[name='acc_group']").val(v.acc_group).trigger("change.select2");
                    $("select[name='id_tipe']").val(v.id_tipe).trigger("change.select2");
                    $("select[name='id_kategori']").val(v.id_kategori).trigger("change.select2");
                    $("select[name='title']").val(v.title).trigger("change.select2");
					$("input[name='nama']").val(v.nama);
					$("input[name='jenis_barang_jasa1']").val(v.jenis_barang_jasa1);
					$("input[name='jenis_barang_jasa2']").val(v.jenis_barang_jasa2);
					$("input[name='nama_bank']").val(v.nama_bank);
					$("input[name='nama_rekening']").val(v.nama_rekening);
					$("input[name='nomor_rekening']").val(v.nomor_rekening);
					$("input[name='payment']").val(v.payment);
					$("input[name='npwp']").val(v.npwp);
					$("input[name='ktp']").val(v.ktp);
					//tab data detail
                    $("select[name='industri']").val(v.industri).trigger("change.select2");
                    $("select[name='dlgrp']").val(v.dlgrp).trigger("change.select2");
                    $("select[name='akont']").val(v.akont).trigger("change.select2");
                    $("select[name='zterm']").val(v.zterm).trigger("change.select2");
                    $("select[name='tax_type']").val(v.tax_type).trigger("change.select2");
                    $("select[name='tax_code']").val(v.tax_code).trigger("change.select2");
                    $("select[name='curr']").val(v.curr).trigger("change.select2");
					$("input[name='schema_grup']").val(v.schema_grup);
					$("input[name='sales_person']").val(v.sales_person);
					$("input[name='sales_phone']").val(v.sales_phone);
					$("input[name='webre']").val(v.webre);
					$("select[name='status_pkp']").val(v.status_pkp).trigger("change.select2");
					$("select[name='status_do']").val(v.status_do).trigger("change.select2");
					$("input[name='deletion_flag']").val(v.deletion_flag);
					//tab alamat
					$("select[name='negara']").val(v.negara).trigger("change.select2");
                    //load provinsi
                    var output = '';
                    $.each(v.arr_provinsi, function(x, y) {
                        var selected = (y.id_provinsi == v.provinsi ? 'selected' : '');
                        output += '<option value="' + y.id_provinsi + '" ' + selected + '>' + y.nama_provinsi + '</option>';
                    });
                    $("select[name='provinsi']").html(output).select2();
					$("input[name='kota']").val(v.kota);
					$("input[name='alamat']").val(v.alamat);
					$("input[name='no']").val(v.no);
					$("input[name='kode_pos']").val(v.kode_pos);
					$("input[name='telepon']").val(v.telepon);
					$("input[name='fax']").val(v.fax);
					$("input[name='email']").val(v.email);
					//tab nilai
					$("input[name='total_penilaian']").val(v.total_penilaian);
                    $.each(v.arr_nilai_detail, function(x, y) {
						$("input[name='id_nilai_"+y.id_kriteria+"']").val(y.id_nilai);
						$("input[name='nilai_"+y.id_kriteria+"']").val(y.nilai);
						$("input[name='nilai_bobot_"+y.id_kriteria+"']").val(y.nilai_bobot);
						$("input[name='nilai_max_"+y.id_kriteria+"']").val(y.nilai_max);
						// if(y.id_nilai==v){
							// $("input[name='opt_nilai_1_2']").prop('checked', true);
						// }else{
							// $("input[name='opt_nilai_1_2']").prop('checked', false);
						// }
                    });
					//tab additional
                    if (v.add_pilihan == 'y') {
                        $("input[name='add_pilihan']").attr('checked');
                        $("input[name='add_pilihan']").bootstrapToggle('on');
						$('#add_vendor_existing').prop('required', true);
						$('#add_vendor_existing').prop('disabled', false);
						$('#add_alasan').prop('required', true);
						$('#add_alasan').prop('disabled', false);
						$('#add_vendor_flag').prop('required', true);
						$('#add_vendor_flag').prop('disabled', false);
						$("input[name='add_vendor_existing']").val(v.add_vendor_existing);
						$("select[name='add_alasan']").val(v.add_alasan).trigger("change.select2");
						$("input[name='add_vendor_flag']").val(v.add_vendor_flag);
                    } else {
                        $("input[name='add_pilihan']").removeAttr('checked');
                        $("input[name='add_pilihan']").bootstrapToggle('off');
						$('#add_vendor_existing').prop('required', false);
						$('#add_vendor_existing').prop('disabled', true);
						$('#add_alasan').prop('required', false);
						$('#add_alasan').prop('disabled', true);
						$('#add_vendor_flag').prop('required', false);
						$('#add_vendor_flag').prop('disabled', true);
						$("input[name='add_vendor_existing']").val('');
						$("select[name='add_alasan']").val('').trigger("change.select2");
						$("input[name='add_vendor_flag']").val('');
                    }
					

                });
            },
            complete: function() {
                if (action == 'approve') {
					$('.form-control-hide').prop('disabled', true);
                    $("#btn_save").hide();
                    $("#btn_approve").show();
                }else{
					$('.form-control-hide').prop('disabled', false);
					$("#btn_save").show();
					$("#btn_approve").hide();
				}
				
                $('#add_modal').modal('show');
            }

        });
    });

    //extend
    $(document).on("click", ".extend", function() {
        var id_data = $(this).data("id_data");
        $.ajax({
            // url: baseURL + 'material/transaksi/get/spec',
			url: baseURL + 'vendor/transaksi/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='id_data']").val(v.id_data);
					$("input[name='lifnr']").val(v.lifnr);
					$("input[name='nama']").val(v.nama);
					$("#I_LIFNR").val(v.lifnr);
                    $("#I_EKORG_REF").val(v.plant);
                    $("#I_BUKRS_REF").val(v.BUKRS);
                    $("#I_KTOKK").val(v.acc_group);
                    //load plant as is
                    var output = '';
                    $.each(v.arr_plant_asis, function(x, y) {
                        output += '<option value="' + y.plant + '" selected>' + y.plant + '</option>';
                    });
                    $("select[name='plant_asis[]']").html(output).select2();
                    //load plant extend
                    var output = '';
                    $.each(v.arr_plant_extend, function(x, y) {
                        output += '<option value="' + y.plant + '">' + y.plant + '</option>';
                    });
                    $("select[name='plant_extend[]']").html(output).select2();

                });
            },
            complete: function() {
                $('#add_extend').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_extend')
                });
            }
        });
    });
	
    $(document).on("click", ".edit_extend", function() {
        var id_data = $(this).data("id_data");
		var action 	= $(this).data("action");
        $.ajax({
            // url: baseURL + 'material/transaksi/get/spec',
			url: baseURL + 'vendor/transaksi/get/data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: id_data
            },
            success: function(data) {
                if (action == 'approve') {
                    $(".modal-title").html("Approve Extend Master Vendor");
                }else{
                    $(".modal-title").html("Edit Extend Vendor");
                }
				
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='id_data']").val(v.id_data);
					$("input[name='nama']").val(v.nama);
					$("input[name='lifnr']").val(v.lifnr);
					$("#I_LIFNR").val(v.lifnr);
                    $("#I_EKORG_REF").val(v.plant);
                    $("#I_BUKRS_REF").val(v.BUKRS);
                    $("#I_KTOKK").val(v.acc_group);
                    //load plant as is
                    var output = '';
                    $.each(v.arr_plant_asis, function(x, y) {
                        output += '<option value="' + y.plant + '" selected>' + y.plant + '</option>';
                    });
                    $("select[name='plant_asis[]']").html(output).select2();
                    //load plant extend edit
                    var output = '';
                    $.each(v.arr_plant_edit, function(x, y) {
						output += '<option value="' + y.plant + '" selected>' + y.plant + '</option>';
                    });
                    // //load plant extend
                    $.each(v.arr_plant_extend, function(x, y) {
						$.each(v.arr_plant_edit, function(a, b) {
							if(b.plant!=y.plant){
								output += '<option value="' + y.plant + '">' + y.plant + '</option>';
							}
						});
                    });
				
					$("select[name='plant_extend[]']").html(output).select2();
					
                });
            },
            complete: function() {
                if (action == 'approve') {
					$('.form-control-hide').prop('disabled', true);
                    $("#btn_save").hide();
                    $("#btn_approve").show();
                }else{
					$('.form-control-hide').prop('disabled', false);
					$("#btn_save").show();
					$("#btn_approve").hide();
				}
				$('#lifnr').prop('disabled', true);
				$('#plant_asis').prop('disabled', true);
                $('#add_extend').modal('show');
                $('.select2modal').select2({
                    dropdownParent: $('#add_extend')
                });
            }
        });
    });
	//extend approve
	$(document).on("click", "button[name='action_btn_approve_extend']", function(e){
        var empty_form = validate('.form-transaksi-extend-vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-extend-vendor")[0]);
				// console.log();
				$.ajax({
					// url: baseURL + "data/rfc/set/create_vendor",
					url: baseURL + "data/rfc/set/extend_vendor",
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
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

    $(document).on("click", ".nonactive, .setactive, .delete", function(e) {
        $.ajax({
            url: baseURL + "vendor/transaksi/set/spec",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data: $(this).data($(this).attr("class")),
                type: $(this).attr("class")
            },
            success: function(data) {
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg);
                } else {
                    kiranaAlert("notOK", data.msg, "warning", "no");
                }
            }
        });
        e.preventDefault();
        return false;
    });
    //extend
    $(document).on("click", "button[name='action_btn_extend']", function(e) {
		alert('aa');
        var empty_form = validate('.form-transaksi-extend-vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-extend-vendor")[0]);
				// console.log();
				$.ajax({
					url: baseURL + 'vendor/transaksi/save/vendor_extend',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
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

    //export to excel
    $('.my-datatable-extends-order').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            text: 'Export to Excel',
            title: 'Penilaian',
            download: 'open',
            orientation: 'landscape',
            exportOptions: {
                columns: [0, 1, 2, 3, 4, 5]
            }
        }],
        scrollX: true
    });

    //open modal for add     
    $(document).on("click", "#add_button", function(e) {
		$('#id_data').val("");
        resetForm_use($('.form-transaksi-extend-vendor'));
        $('#add_modal').modal('show');
        $("#btn_change").hide();
        $('.select2modal').select2({
            dropdownParent: $('#add_modal')
        });

    });
	
    //open modal for add     
    $(document).on("click", "#cek_vendor", function(e) {
		// $('#nama_vendor').val("");
        $('#vendor_modal').modal('show');
    });
	
    //open modal for req     
	$(document).on("click", "#cek_btn_vendor", function(e){
		var nama_vendor = $("#nama_vendor").val();
		//push sap
		$.ajax({
			url: baseURL + "data/rfc/get/vendor",
			type: 'POST',
			dataType: 'JSON',
			data: {
				nama_vendor : nama_vendor
			},
			success: function(data){
				var det	= "";
					det	+= 		'<div class="row">';
					det	+= 		'<div class="col-sm-12">';
					det	+= 		'<div class="box box-success">';
					det	+= 		'<div class="box-header">';
					det	+= 		'<h4 class="box-title"><strong>Daftar Nama Vendor di SAP</h4>';
					det	+= 		'</div>';
					det	+= 		'<table class="table table-bordered datatable-vendor">';
					det	+= 		'	<thead>';
					det	+= 		'		<tr>';
					det	+= 		'			<th>Nama</th>';
					det	+= 		'			<th>Alamat</th>';
					det	+= 		'			<th>NPWP</th>';
					det	+= 		'		</tr>';
					det	+= 		'	</thead>';
					det	+= 		'	<tbody>';
					$.each(data, function(i,v){
						det	+= 		'		<tr>';
						det	+= 		'			<td>'+v.NAME1+'</td>';
						det	+= 		'			<td>'+v.CITY1+'</td>';
						det	+= 		'			<td>'+v.POST_CODE1+'</td>';
						det	+= 		'		</tr>';
					});
					det	+= 		'	</tbody>';
					det	+= 		'</table>';
					det	+= 		'</div>';
					det	+= 		'</div>';
					det	+= 		'</div>';
					$("#show_vendor").html(det);
			},
			complete: function () {
				setTimeout(function () {
					$("table.datatable-vendor").DataTable({
						"bLengthChange": false
					}).columns.adjust();
				}, 1500);				
			}
		});
	});
	

});

function resetForm_use($form, $act) {
    $('#myModalLabel').html("Form Input Master Vendor");
    $('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
    $form.find('input:text, input:password, input:file,  textarea').val("");
    $form.find('input:text, input:password, input:file,  textarea').prop('disabled', false);
    $form.find('select').val(0);
    $form.find('select').prop('disabled', false);
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    $form.find('input:radio, input:checkbox').prop('disabled', false);

    // $('#service_level').val("").prop('disabled', false);
    $('#net_weight').val("").prop('disabled', false);
    $('#gross_weight').val("").prop('disabled', false);
    $("#plant").val(0).trigger("change");
    $("#vtweg").val(0).trigger("change");
    $("#sales_plant").find('checkbox').removeAttr('checked');
    $('.switch-onoff').bootstrapToggle('off');
    $('.switch-onoff').removeAttr('checked');;
    $('#plant_extend').prop('disabled', false);
    if ($act != 'edit') {
        $("#show_images").hide();
    }
    $("#gambar").show();
    $("#btn_save").show();
    $('#isproses').val("");
    $('#isconvert').val('0');
    $('#code').prop('disabled', true);
    $('#detail').prop('disabled', true);
    validateReset('.form-transaksi-extend-vendor');
}

function resetForm_extend($form) {
    $('#plant_extend').prop('disabled', false);
}

function datatables_ssp() {
    var id_tipe 		= $("#id_tipe_filter").val();
    var id_kategori		= $("#id_kategori_filter").val();
    var status_filter 	= $("#status_filter").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        pageLength: 25,
        initComplete: function() {
            var api = this.api();
            $("#sspTable_filter input").attr(
                "placeholder",
                "Press enter to start searching"
            );
            $("#sspTable_filter input").attr(
                "title",
                "Press enter to start searching"
            );
            $("#sspTable_filter input")
                .off(".DT")
                .on("keypress change", function(evt) {
                    if (evt.type == "change") {
                        api.search(this.value).draw();
                    }
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: baseURL + 'vendor/transaksi/get/data_extend/bom',
            type: 'POST',
            data: function(data) {
                data.id_tipe = id_tipe;
                data.id_kategori = id_kategori;
                data.status_filter = status_filter;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [{
                "data": "id_data",
                "name": "id_data",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.id_data;
                },
                "visible": false
            },
            {
                "data": "nama",
                "name": "nama",
                "width": "15%",
                "render": function(data, type, row) {
                    return '<b>'+row.nama+'</b><br>'+row.nama_provinsi+' - '+row.nama_negara+'<br>'+row.telepon;
                }
            },
            {
                "data": "nama_tipe",
                "name": "nama_tipe",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.nama_tipe+"<br><strong><i class='fa fa-files-o'></i> &nbsp; "+row.upload_tipe_mandatory+"/"+row.jumlah_tipe_mandatory+" Dokumen Mandatory</strong><br><strong><i class='fa fa-files-o'></i> &nbsp; "+row.upload_tipe_non_mandatory+"/"+row.jumlah_tipe_non_mandatory+" Dokumen Non Mandatory</strong>";
                }
            },
            {
                "data": "nama_kategori",
                "name": "nama_kategori",
                "width": "15%",
                "render": function(data, type, row) {
                    return row.nama_kategori+"<br><strong><i class='fa fa-files-o'></i> &nbsp; "+row.upload_ketegori_mandatory+"/"+row.jumlah_ketegori_mandatory+" Dokumen</strong>";
                }
            },
            {
                "data": "alamat",
                "name": "alamat",
                "width": "5%",
                "render": function(data, type, row) {
					if(row.total_penilaian>=70){
						return '<b>'+row.total_penilaian+' (Lulus)</b>';
					}else{
						return '<b>'+row.total_penilaian+' (Gagal)</b>';
					}
                }
            },
            {
                "data": "req",
                "name": "req",
                "width": "5%",
                "render": function(data, type, row) {
                    if (row.extend == 'n') {
                        return '<label class="label label-success">Completed</label>';
                    } else {
                        return '<label class="label label-warning">On Progress</label><br>Menunggu Approval Proc HO';
                    }
                }
            },
            {
                "data": "id_data",
                "name": "id_data",
                "width": "5%",
                "render": function(data, type, row) {
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if (row.extend == 'y') {
						output += "				<li><a href='javascript:void(0)' class='edit_extend' data-id_data='" + row.id_data + "' data-action='edit'><i class='fa fa-pencil-square-o'></i> Edit Extend</a></li>";
						if(row.id_divisi==751){
							output += "				<li><a href='javascript:void(0)' class='edit_extend' data-id_data='" + row.id_data + "' data-action='approve'><i class='fa fa-thumbs-o-up'></i> Appove</a></li>";
						}
					}
					if (row.extend == 'n') {
						output += "				<li><a href='javascript:void(0)' class='extend' data-id_data='" + row.id_data + "'><i class='fa fa-arrows'></i> Extend</a></li>";
					}
					output += "				</ul>";
					output += "	        </div>";
                    return output;
                }
            }

        ],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            if (info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });

    return mydDatatables;
}

function load_plant(plant) {
    alert(plant);
    $.ajax({
        url: baseURL + 'material/master/get/plant',
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            if (data) {
                var output = '';
                $.each(data, function(i, v) {
                    output += '<option value="' + v.plant + '">' + v.plant + '</option>';
                });
                $("select[name='plant[]']").html(output);
            }
        },
        complete: function() {
            // var plant	= plant.split(",");
            $("select[name='plant[]']").val(plant).trigger("change");
        }
    });
}

function validateReset(target = 'form') {
    var element = $("input, select, textarea", $(target));
    $.each(element, function(i, v) {
        if (v.tagName == 'SELECT' && v.nextSibling.firstChild != null) {
            v.nextSibling.firstChild.firstChild.style.borderColor = "#d2d6de";
        }
        v.style.borderColor = "#d2d6de";
    });
}