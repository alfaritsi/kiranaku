$(document).ready(function() {
	$('.tanggal').datepicker({
        format: 'yyyy-mm-dd',
	    autoclose: true
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
    $(document).on("change", "#jenis_depo_filter, #pabrik_filter, #status_filter", function() {
        datatables_ssp();
    });
    //rekening
    $(document).on("click", ".rekening", function() {
        var id_data_temp = $(this).data("id_data");
        $.ajax({
			url: baseURL + 'bank/transaksi/get/data_temp',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data_temp: id_data_temp
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='id_data_temp']").val(v.id_data_temp);
					$("input[name='nomor']").val(v.nomor);
					$("input[name='nama_bank']").val(v.nama_bank);
					$("input[name='cabang_bank']").val(v.cabang_bank);
					$("input[name='mata_uang']").val(v.mata_uang);
					$("input[name='nomor_rekening']").val(v.nomor_rekening);
                });
            },
            complete: function() {
				$('#modal_rekening').modal('show');
            }
        });
    });
	//save rekening
	$(document).on("click", "button[name='btn_save_rekening']", function(e){
		var id_data_temp = $("#id_data_temp").val();
        var empty_form = validate('.form-transaksi-rekening');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-rekening")[0]);
				// console.log();
				$.ajax({
					url: baseURL + 'bank/transaksi/save/rekening',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
								window.location = baseURL + 'bank/transaksi/approve';
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
    //coa
    $(document).on("click", ".coa", function() {
        var id_data_temp = $(this).data("id_data");
        $.ajax({
			url: baseURL + 'bank/transaksi/get/data_temp',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_data_temp: id_data_temp
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='id_data_temp']").val(v.id_data_temp);
					$("input[name='nomor']").val(v.nomor);
					$("input[name='nama_bank']").val(v.nama_bank);
					$("input[name='cabang_bank']").val(v.cabang_bank);
					$("input[name='mata_uang']").val(v.mata_uang);
					$("input[name='no_coa']").val(v.no_coa);
                });
            },
            complete: function() {
				$('#modal_coa').modal('show');
            }
        });
    });
	//save coa
	$(document).on("click", "button[name='btn_save_coa']", function(e){
		var id_data_temp = $("#id_data_temp").val();
        var empty_form = validate('.form-transaksi-coa');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-coa")[0]);
				// console.log();
				$.ajax({
					url: baseURL + 'bank/transaksi/save/coa',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data) {
						if (data.sts == 'OK') {
							swal('Success', data.msg, 'success').then(function() {
								// location.reload();
								window.location = baseURL + 'bank/transaksi/approve';
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
	
	//extend sap
	$(document).on("click", "button[name='btn_save_extend_sap']", function(e){
		var level = $("#level").val();
		var pengajuan_ho = $("#pengajuan_ho").val();
        var empty_form = validate('.form-transaksi-extend_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-extend_vendor")[0]);
				// console.log();
				if((level==4)&&(pengajuan_ho=='n')){
					$.ajax({
						url: baseURL + 'vendor/transaksi/save/approve_extend',
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
					
				}else{
					$.ajax({
						// url: baseURL + "data/rfc/set/create_vendor",
						// url: baseURL + "data/rfc/set/extend",
						url: baseURL + "vendor/rfc/set/extend_vendor",
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
				}
				
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
	//save delete
	$(document).on("click", "button[name='btn_save_delete']", function(e){
		var level = $("#level").val();
        var empty_form = validate('.form-transaksi-delete_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-delete_vendor")[0]);
				// console.log();
				if(level==4){
					$.ajax({
						url: baseURL + 'vendor/rfc/set/delete_vendor/ho',
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
				}else{
					$.ajax({
						url: baseURL + 'vendor/transaksi/save/delete',
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
					
				}
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
	
	//delete sap
	$(document).on("click", "button[name='btn_save_delete_sap']", function(e){
        var empty_form = validate('.form-transaksi-delete_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-delete_vendor")[0]);
				// console.log();
				$.ajax({
					// url: baseURL + "data/rfc/set/create_vendor",
					url: baseURL + 'vendor/rfc/set/delete_vendor',
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
	//save undelete
	$(document).on("click", "button[name='btn_save_undelete']", function(e){
		var id_status_undelete = $("#id_status_undelete").val();
        var empty_form = validate('.form-transaksi-undelete_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-undelete_vendor")[0]);
				// console.log();
				$.ajax({
					url: baseURL + 'vendor/transaksi/save/undelete',
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
	
	//undelete sap
	$(document).on("click", "button[name='btn_save_undelete_sap']", function(e){
		var id_status_undelete = $("#id_status_undelete").val();
        var empty_form = validate('.form-transaksi-undelete_vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-undelete_vendor")[0]);
				// console.log();
				if((id_status_undelete==4)||(id_status_undelete==5)){
					$.ajax({
						// url: baseURL + "data/rfc/set/create_vendor",
						url: baseURL + 'vendor/transaksi/save/approve_undelete',
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
				}else{
					$.ajax({
						// url: baseURL + "data/rfc/set/create_vendor",
						url: baseURL + 'vendor/rfc/set/undelete_vendor',
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
				}
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
	
	//approve
	$(document).on("click", "button[name='action_btn_approve_']", function(e){
        var empty_form = validate('.form-transaksi-vendor');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-vendor")[0]);
				kiranaConfirm(
					{
						title: "Konfirmasi",
						text: "Vendor akan langsung dibuat ke SAP, apakah proses akan dilanjutkan?",
						dangerMode: true,
						successCallback: function () {
							// alert('aa');
							//push sap
							$.ajax({
								// url: baseURL + "data/rfc/set/kode_material",
								// url: baseURL + "data/rfc/set/create_vendor",
								url: baseURL + "vendor/rfc/set/create_vendor",
								type: 'POST',
								dataType: 'JSON',
								data: formData,
								success: function(data){
									if(data.sts == 'OK'){
										kiranaAlert(data.sts, data.msg);
									}else{
										kiranaAlert("notOK", data.msg, "warning", "no");
									}
								},
								complete: function () {
									$("input[name='isproses']").val(0);
								}
							});
						}
					}
				);
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
		$('#id_jenis_vendor').val("");
		// $('#kualifikasi_spk').val("");
		$("select[name='kualifikasi_spk[]']").val('').trigger("change");
		$("#show_dokumen_jenis").html('');
		$("#show_dokumen_kualifikasi").html('');
		$(".form-control_komentar").css({'visibility' : 'hidden'});
		
        resetForm_use($('.form-transaksi-vendor'));
        $('#add_modal').modal('show');
        $("#btn_change").hide();
        $("#btn_change_sap").hide();
        $("#btn_approve_change").hide();
        $("#btn_decline_change").hide();
        $("#btn_decline").hide();
        $("#btn_decline_extend").hide();
        $("#btn_approve").hide();
        $("#btn_approve_sap").hide();
        $("#cek_dok_jenis_vendor").hide();
        $("#cek_dok_kualifikasi_vendor").hide();
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
			// url: baseURL + "data/rfc/get/vendor",
			url: baseURL + "vendor/rfc/get/vendor",
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
					det	+= 		'			<th>Pabrik</th>';
					det	+= 		'			<th>Nama Vendor</th>';
					det	+= 		'			<th>Alamat</th>';
					det	+= 		'			<th>NPWP</th>';
					det	+= 		'		</tr>';
					det	+= 		'	</thead>';
					det	+= 		'	<tbody>';
					$.each(data, function(i,v){
						det	+= 		'		<tr>';
						det	+= 		'			<td>'+v.EKORG+'</td>';
						det	+= 		'			<td>'+v.NAME1.toUpperCase()+'</td>';
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

    //history
    $(document).on("click", ".history", function() {
        var nomor = $(this).data("nomor");
        $.ajax({
			url: baseURL + 'depo/transaksi/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                nomor: nomor
            },
            success: function(data) {
				var det_pengajuan	= "";
					det_pengajuan	+= 		'<table class="table table-bordered datatable-vendor">';
					det_pengajuan	+= 		'	<thead>';
					det_pengajuan	+= 		'		<tr>';
					det_pengajuan	+= 		'			<th>Nomor Pengajuan</th>';
					det_pengajuan	+= 		'			<th>Tanggal Status</th>';
					det_pengajuan	+= 		'			<th>Status</th>';
					det_pengajuan	+= 		'			<th>Comment</th>';
					det_pengajuan	+= 		'		</tr>';
					det_pengajuan	+= 		'	</thead>';
					det_pengajuan	+= 		'	<tbody>';

                $.each(data, function(i, v) {
					det_pengajuan	+= 		'		<tr>';
					det_pengajuan	+= 		'			<td>'+v.nomor_specimen+'</td>';
					det_pengajuan	+= 		'			<td>'+v.tanggal_format+'<br>'+v.jam_format+'</td>';
					det_pengajuan	+= 		'			<td>'+v.action+' Oleh :<br><span class="label label-info">'+v.role_approval+' : '+v.nama_approval+'</span></td>';
					det_pengajuan	+= 		'			<td>'+v.label_catatan+'</span></td>';
					det_pengajuan	+= 		'		</tr>';
                });
					det_pengajuan	+= 		'	</tbody>';
					det_pengajuan	+= 		'</table>';
					$("#histori_pengajuan").html(det_pengajuan);
				
            },
            complete: function() {
				setTimeout(function () {
					$("table.datatable-vendor").DataTable({
						"bLengthChange": false
					}).columns.adjust();
				}, 1500);				
                $('#modal-history').modal('show');
            }
        });
    });
	

});

function resetForm_use($form, $act) {
    $('#myModalLabel').html("Create Vendor");
    $('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
    $form.find('input:text, input:password, input:file,  textarea, input:hidden').val("");
    $form.find('input:text, input:password, input:file,  textarea, input:hidden').prop('disabled', false);
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
    $('#status_do').prop('disabled', true);
    validateReset('.form-transaksi-vendor');
}

function resetForm_extend($form) {
    $('#plant_extend').prop('disabled', false);
}

function datatables_ssp() {
    var jenis_depo_filter 	= $("#jenis_depo_filter").val();
    var pabrik_filter		= $("#pabrik_filter").val();
    var status_filter		= $("#status_filter").val();

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
		order: [[4, 'desc']],
        ajax: {
            url: baseURL + 'depo/transaksi/get/data/bom',
            type: 'POST',
            data: function(data) {
                data.jenis_depo_filter  = jenis_depo_filter;
                data.pabrik_filter		= pabrik_filter;
                data.status_filter		= status_filter;
                data.view_data			= 'approve';
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "jenis_depo_format",
                "name": "jenis_depo_format",
                "width": "10%",
                "render": function(data, type, row) {
					return row.jenis_depo_format;
                }
            },
            {
                "data": "pabrik",
                "name": "pabrik",
                "width": "10%",
                "render": function(data, type, row) {
					return row.pabrik;
                }
            },
            {
                "data": "nama",
                "name": "nama",
                "width": "20%",
                "render": function(data, type, row) {
					return row.nama;
                }
            },
            {
                "data": "nomor",
                "name": "nomor",
                "width": "15%",
                "render": function(data, type, row) {
					return row.nomor;
                }
            },
            {
                "data": "tanggal_buat",
                "name": "tanggal_buat",
                "width": "10%",
                "render": function(data, type, row) {
					return row.tanggal_format;
                }
            },
            {
                "data": "status",
                "name": "status",
                "width": "15%",
                "render": function(data, type, row) {
					if(row.status==999){
						return row.label_status;
					}else{
						return row.label_status+'<br><small>'+row.label_status_detail+'</small>';
					}
                }
            },
            {
                "data": "id_data",
                "name": "id_data",
                "width": "5%",
                "render": function(data, type, row) {
					var url_edit 	= baseURL + "depo/transaksi/edit/" + row.nomor_format;
					var url_detail 	= baseURL + "depo/transaksi/detail/" + row.nomor_format;
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(row.status==1){
						output += "				<li><a href='"+url_edit+"' ><i class='fa fa-pencil-square-o'></i> Edit</a></li>";						
					}
					output += "					<li><a href='"+url_detail+"' ><i class='fa fa-search'></i> Detail</a></li>";					
					output += "					<li><a href='javascript:void(0)' class='history' data-nomor='" + row.nomor_format + "'><i class='fa fa-h-square'></i> History</a></li>";
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
// function hitung_nilai() {
	// //hitung total_nilai
	// if($('input[name="nilai_1"]').val()!=''){
		// var nilai_1 = $('input[name="nilai_1"]').val();
	// }else{
		// var nilai_1 = 0;
	// }
	// if($('input[name="nilai_2"]').val()!=''){
		// var nilai_2 = $('input[name="nilai_2"]').val();
	// }else{
		// var nilai_2 = 0;
	// }
	// if($('input[name="nilai_3"]').val()!=''){
		// var nilai_3 = $('input[name="nilai_3"]').val();
	// }else{
		// var nilai_3 = 0;
	// }
	// var total_nilai = parseInt(nilai_1)+parseInt(nilai_2)+parseInt(nilai_3);
	// $("input[name='total_nilai']").val(total_nilai);
// }