$(document).ready(function () {
	// //filter pabrik
	// $.ajax({
		// url: baseURL+'spk/master/get2/plant',
		// type: 'POST',
		// dataType: 'JSON',
		// success: function(data){
			// var no = 0;
			// var list = '';
			// $.each(data, function(i,v){
				// list += '<option value="'+v.WERKS+'">'+v.WERKS+'</option>';
			// });
			// $('#plant').html(list);
		// }
	// });

	//filter vendor
	$.ajax({
		url: baseURL+'spk/master/get2/jenis_vendor',
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			var no = 0;
			var list = '';
			$.each(data, function(i,v){
				list += '<option value="'+v.id_jenis_vendor+'">'+v.jenis_vendor+'</option>';
			});
			$('#jenis_vendor').html(list);
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
    $(document).on("change", "#plant, #status_pkp, #status, #jenis_vendor", function () {
        datatables_ssp(); 
    });
	 
	//on click
    $(document).on("click", ".matrix", function () {
        var lifnr = $(this).data("lifnr"); 
        var ekorg = $(this).data("ekorg");
        var vendor = $(this).data("vendor");
        $.ajax({ 
            url: baseURL + 'spk/master/get2/matrix',
            type: 'POST',
            dataType: 'JSON',
            data: {
                lifnr: lifnr,
                ekorg: ekorg,
                vendor: vendor
            },
            success: function (data) {
				$.each(data, function(i, v){
					$("input[name='lifnr']").val(v.LIFNR);
					$("input[name='ekorg']").val(v.EKORG);
					$("input[name='plant']").val(v.EKORG);
					$("input[name='vendor']").val(v.NAME1);
					$("input[name='kualifikasi_value']").val(v.kualifikasi);
					get_data_jenis_vendor(v.id_jenis_vendor);
					get_data_jenis_spk(v.id_jenis_spk, v.kualifikasi);
					get_data_kualifikasi_spk(null, v.id_jenis_spk);
					//create folder vendor parent temporary 
					create_folder_vendor(v.LIFNR, v.NAME1);
				});
            },
            complete: function () {
				$("#id_jenis_vendor").select2();
                $('#detail_modal').modal('show'); 
            }

        });  
    });
	//save	
	$(document).on("click", "button[name='action_btn']", function(e){
		var count_mandatory 			= $("input[name='count_mandatory']").val();
		var count_dokumen 				= $("input[name='count_dokumen']").val();
		var count_mandatory_kualifikasi = $("input[name='count_mandatory_kualifikasi']").val();
		var count_dokumen_kualifikasi 	= $("input[name='count_dokumen_kualifikasi']").val();
		if(count_mandatory!=count_dokumen){
			kiranaAlert("notOK", "Dokumen Vendor Mandatory, Wajib Isi.", "warning", "no");	
		}else if(count_mandatory_kualifikasi!=count_dokumen_kualifikasi){
			kiranaAlert("notOK", "Dokumen Kualifikasi Mandatory, Wajib Isi.", "warning", "no");	
		}else{
			var empty_form = validate();
			if(empty_form == 0){
				var isproses 		= $("input[name='isproses']").val();
				if(isproses == 0){
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-master-matrix")[0]); 

					$.ajax({
						url: baseURL+'spk/master/save/matrix',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						success: function(data){
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
				}else{
					swal({
						title: "Silahkan tunggu proses selesai.",
						icon: 'info'
					});
				}
			}
			e.preventDefault();
			return false;
		}	
    });
	
	//change jenis spk 
	// $("#id_jenis_spk").change(function() {
	$(document).on("change", "#id_jenis_spk", function(e){	
	  	var id_jenis_spk = $("#id_jenis_spk").val();
		var kualifikasi = $("input[name='kualifikasi_value']").val();
		var lifnr = $("#lifnr").val();
		var vendor = $("#vendor").val();
		get_data_kualifikasi_spk(kualifikasi, id_jenis_spk, lifnr, vendor); 
    });
	
	//change jenis vendor
	// $("#id_jenis_vendor").change(function() { 
	$(document).on("change", "#id_jenis_vendor", function(e){	
		var id_jenis_vendor = $("#id_jenis_vendor").val();
		var lifnr = $("#lifnr").val();
		var vendor = $("#vendor").val();
		get_data_dokumen_vendor(null, id_jenis_vendor, lifnr, vendor);
    });
	//set on change id_item_group_filter
    $(document).on("change", "#kualifikasi", function(e){
		var kualifikasi	= $(this).val();
		
		var id_jenis_vendor = $("#id_jenis_vendor").val();
		var lifnr = $("#lifnr").val();
		var vendor = $("#vendor").val();
		if(kualifikasi!=''){
			$.ajax({ 
				url: baseURL + 'spk/master/get2/kualifikasi',
				type: 'POST',
				dataType: 'JSON',
				data: {
					kualifikasi	: kualifikasi,
					lifnr	: lifnr,
					vendor	: vendor
				}, 
				success: function (data) {
					if (data) {
						$("input[name='kualifikasi_value']").val(kualifikasi);
						var count_mandatory_kualifikasi = 0;
						var count_dokumen_kualifikasi = 0;
						var output 	= '';
						output += '<div class="col-xs12">';
						output += '<fieldset class="fieldset-info">';
						output += '<legend>Dokumen Kualifikasi</legend>';
						$.each(data, function (i, v) {
							count_mandatory_kualifikasi = count_mandatory_kualifikasi+1;
							if(v.id_file!=null){
								count_dokumen_kualifikasi = count_dokumen_kualifikasi+1;
								var ck_upload =  '<i class="fa fa-check text-green"></i>';
							}else{
								var ck_upload =  '<i class="fa fa-times text-red"></i>';
								
							} 
							output += '<div class="box-body">';
							output += '<table table-bordered table-doc">';
							output += '<tbody>';
							output += '<tr>';
							output += '		<td>'+ck_upload+' '+v.kualifikasi_spk+'</td>';
							output += "		<td width='10%'>";
							output += "			<div class='input-group-btn'>";
							output += "				<button type='button' class='btn btn-default btn-xs dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>";
							output += "				<ul class='dropdown-menu pull-right'>";
							
							if(v.id_file!=null){
								if(v.tipe_file=='pdf'){
									output += "					<li><a href='javascript:void(0)' class='view' data-url='" + v.link + "'>View Attachment</a></li>";	
								}else{
									output += "					<li><a href='"+ baseURL +"assets/" + v.link + "'>View Attachment</a></li>";	
								}
								output += "					<li><a href='javascript:void(0)' class='upload' data-lifnr='" + lifnr + "' data-vendor='" + vendor + "' data-id_folder='" + v.id_folder + "' data-id_file='" + v.id_file + "' data-nama_dokumen_vendor='" + v.kualifikasi_spk +"'>Edit</a></li>";
							}else{
								output += "					<li><a href='javascript:void(0)' class='upload' data-lifnr='" + lifnr + "' data-vendor='" + vendor + "' data-id_folder='" + v.id_folder + "' data-nama_dokumen_vendor='" + v.kualifikasi_spk +"'>Upload</a></li>";	
							}
							output += "				</ul>";
							output += "	        </div>";
							output += "		</td>";
							output += '</tr>';
							output += '</tbody>'; 
							output += '</table>';
							output += '</div>';
						});
						output += '</fieldset>';
						output += '</div>';
						$("#dokumen_kualifikasi").html(output);
						$("input[name='count_mandatory_kualifikasi']").val(count_mandatory_kualifikasi);
						$("input[name='count_dokumen_kualifikasi']").val(count_dokumen_kualifikasi);
						
					}
				} 
			});			
		}else{
			$("#dokumen_kualifikasi").html('');
		}
    });
	
	//on click upload
    $(document).on("click", ".upload","#id_jenis_vendor", function () {
		var id_folder = $(this).data("id_folder");
		var id_file = $(this).data("id_file"); 
		var vendor = $(this).data("vendor");
		var lifnr = $(this).data("lifnr");
		var nama_dokumen_vendor = $(this).data("nama_dokumen_vendor");

		$("input[name='id_folder']").val(id_folder);
		$("input[name='id_file']").val(id_file);
		$("input[name='name']").val(nama_dokumen_vendor+' - '+lifnr);
		$('#file').val(''); 
		$('#fileUpload').val('');
    	$('#upload_modal').modal('show');		 
    });
	//on click view
    $(document).on("click", ".view",function () {
		var url = $(this).data("url");
		$("#show_file").html(showPdf('assets/'+url+'?download=true&prints=true'));	
		$('#view_modal').modal('show');		
    });
	
	$(document).on("click", "button[name='submit_upload']","#id_jenis_vendor", function(e){
		var id_jenis_spk = $("#id_jenis_spk").val();
		var kualifikasi = $("#kualifikasi").val();
		var id_jenis_vendor = $("#id_jenis_vendor").val();
		var nama_dokumen = $("input[name='name']").val(); 
		var empty_form = validate("#form-upload"); 
        if(empty_form == 0){ 

        	var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1); 
				var formData = new FormData($("#form-upload")[0]);
		    	$.ajax({
		    		// url: baseURL+'folder/manage/upload_new_file_lha',
					url: baseURL + 'spk/master/save2/file',
					type: 'POST', 
					dataType: 'JSON',
					data:  formData, 
	                contentType: false,  
	                cache: false, 
	                processData: false,
					beforeSend: function () {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("button[name='submit_upload']").addClass("overlay-wrapper").append(overlay);
					},
					success: function(data){
						// console.log(data);
						if(data.sts == 'OK'){
							kiranaAlert(data.sts, data.msg, "success", "no");
							$('#upload_modal').modal('hide');
							// generate_table_after_action();
	    					$("input[name='isproses']").val(0);
						}else{
							kiranaAlert(data.sts, data.msg, "error", "no");
	    					$("input[name='isproses']").val(0);
						}
					},
					complete: function () {
						$("button[name='submit_upload']").removeClass("overlay-wrapper");
						$('.overlay').remove();
							
						get_data_jenis_vendor(id_jenis_vendor);
						get_data_jenis_spk(id_jenis_spk, kualifikasi);
						// get_data_kualifikasi_spk(kualifikasi, id_jenis_spk);
					}
				});
		    	
			}else{
				kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
			}
			e.preventDefault();
			return false;

	    }
	    e.preventDefault();
		return false;
    });	
	

});

function datatables_ssp() {
    var plant = $("#plant").val();
    var status_pkp = $("#status_pkp").val();
    var status = $("#status").val();
    var jenis_vendor = $("#jenis_vendor").val();
	$("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
		ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true, 
        bautoWidth: false, 
        initComplete: function () {
            var api = this.api(); 
            $('#sspTable_filter input').attr("placeholder", "Press enter to start searching");
            $('#sspTable_filter input').attr("title", "Press enter to start searching");
            $('#sspTable_filter input')
                .off('.DT')
                .on('keypress change', function (evt) {
                    console.log(evt.type);
                    if(evt.type == "change"){
                        api.search(this.value.toUpperCase()).draw();
                    }
                });
        },
        oLanguage: {
            sProcessing: "Please wait..." 
        },
        processing: true, 
        serverSide: true, 
        ajax: {
            url: baseURL + 'spk/master/get2/matrix/bom',
            type: 'POST',
            data: function (data) {
                data.plant = plant;
                data.status_pkp = status_pkp;
                data.status = status;
                data.jenis_vendor = jenis_vendor;
            },
            error: function (a, b, c) {
                console.log(a); 
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "LIFNR",
                "name": "ID",
                "render": function (data, type, row) {
                    return row.LIFNR;
                },
                "visible": false
            },
            { 
                "data": "EKORG",
                "name": "EKORG",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.EKORG;
                }
            },
            {
                "data": "status_pkp",
                "name": "status_pkp",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.status_pkp;
                }
            },
            {
                "data": "NAME1",
                "name": "NAME1",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.NAME1;
                }
            },
            {
                "data": "CITY1",
                "name": "CITY1",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.CITY1;
                }
            },
            {
                "data": "STRAS",
                "name": "STRAS",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.STRAS;
                }
            },
            {
                "data": "jenis_vendor",
                "name": "jenis_vendor",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.jenis_vendor;
                }
            },
            {
                "data": "jenis_spk",
                "name": "jenis_spk",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.jenis_spk;
                }
            },
            {
                "data": "kualifikasi",
                "name": "kualifikasi",
                "width": "15%",
                "render": function (data, type, row) {
					if(row.list_kualifikasi!=null){
						var list_kualifikasi = "";
						var arr = row.list_kualifikasi.split(',');
						$.each( arr, function( index, value ) {
							list_kualifikasi += '<span class="label bg-light-blue">'+value+'</span> ';
							// list_kualifikasi += '<span class="label label-info">'+value+'</span>  ';
						});									
						return list_kualifikasi;
					}else{
						return '-';
					}
                }
            },
            {
                "data": "status",
                "name": "status",
                "width": "15%",
                "render": function (data, type, row) {
					if(row.status=='Completed'){
						return '<span class="badge bg-green">'+row.status+'</span><br>Upload by:<br>'+row.nama_upload;
					}else{
						return '<span class="badge bg-red">'+row.status+'</span>';
					}
                    
                }
            },
            {
                "data": "ekorg",
                "name": "ekorg",
                "width": "5%",
                "render": function (data, type, row) {
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					output += "					<li><a href='javascript:void(0)' class='matrix' data-lifnr='" + row.LIFNR + "' data-ekorg='" + row.EKORG + "' data-vendor='" + row.NAME1 + "'><i class='fa fa-gears'></i> Upload Dokumen</a></li>";	
					output += "				</ul>";
					output += "	        </div>";
                    return output;
                }
            }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
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

function get_data_jenis_vendor(id_jenis_vendor) {
    $.ajax({
        url: baseURL + 'spk/master/get2/jenis_vendor',
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            if (data) {
                var output = '';
				output += '<option value="0">Silahkan Pilih</option>';
                $.each(data, function (i, v) {
					output += '<option value="'+v.id_jenis_vendor+'">'+v.jenis_vendor+'</option>';
                });
                $("select[name='id_jenis_vendor']").html(output);
            }
        },
        complete: function () {
            if (id_jenis_vendor) {
                $("select[name='id_jenis_vendor']").val(id_jenis_vendor).trigger("change");
            }else{
				$("#dokumen_vendor").html('');
			}
        }
    });
}
function get_data_jenis_spk(id_jenis_spk, kualifikasi) {
    $.ajax({
        url: baseURL + 'spk/master/get2/jenis_spk',
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            if (data) {
                var output = '';
				output += '<option value="0">Silahkan Pilih</option>';
                $.each(data, function (i, v) {
					output += '<option value="'+v.id_jenis_spk+'">'+v.jenis_spk+'</option>';
                });
                $("select[name='id_jenis_spk']").html(output);
            }
        },
        complete: function () {
            if (id_jenis_spk) {
                $("select[name='id_jenis_spk']").val(id_jenis_spk).trigger("change");
            }
        }
    });
}

function get_data_kualifikasi_spk(kualifikasi, id_jenis_spk, lifnr, vendor) {
	var lifnr = $("#lifnr").val();
	var vendor = $("#vendor").val();
    $.ajax({
        url: baseURL + 'spk/master/get2/kualifikasi',
        type: 'POST',
        dataType: 'JSON',
		data: {
			id_jenis_spk : id_jenis_spk,
			lifnr : lifnr,
			vendor : vendor
		},
        success: function (data) {
            if (data) {
                var output = '';
                $.each(data, function (i, v) {
					output += '<option value="'+v.id_kualifikasi_spk+'">'+v.kualifikasi_spk+'</option>';
                });
                $("select[name='kualifikasi[]']").html(output);
            }
        },
        complete: function () {
            if (kualifikasi) {
				var id_kualifikasi_spk	= kualifikasi.split(",");
                $("select[name='kualifikasi[]']").val(id_kualifikasi_spk).trigger("change");
            }else{
				$("#dokumen_kualifikasi").html('');
			}
        }
    });
}
function get_data_dokumen_vendor(dokumen_vendor, id_jenis_vendor, lifnr, vendor) {
    $.ajax({
        url: baseURL + 'spk/master/get2/dokumen_vendor',
        type: 'POST',
        dataType: 'JSON',
		data: {
			id_jenis_vendor : id_jenis_vendor,
			lifnr : lifnr,
			vendor : vendor
		},
        success: function (data) {
            if (data) {
                var count_mandatory = 0;
				var count_dokumen = 0;
				var output 	= '';
				output += '<div class="col-xs12">';
				output += '<fieldset class="fieldset-info">';
				output += '<legend>Dokumen Vendor</legend>'; 
                $.each(data, function (i, v) {
					if(v.id_file!=null){
						var ck_upload =  '<i class="fa fa-check text-green"></i>';
					}else{
						var ck_upload =  '<i class="fa fa-times text-red"></i>';
						
					}
					if(v.mandatory=='Mandatory'){
						count_mandatory=count_mandatory+1;
						if(v.id_file!=null){
							count_dokumen=count_dokumen+1;	
						}
					}
					output += '<div class="box-body">';
					output += '<table table-bordered table-doc">';
					output += '<tbody>';
					output += '<tr>';
					output += '		<td>'+ck_upload+' '+v.nama_dokumen_vendor+' ('+v.mandatory+')</td>';
					output += "		<td width='10%'>";
					output += "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default btn-xs dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(v.id_file!=null){
						if(v.tipe_file=='pdf'){
							output += "					<li><a href='javascript:void(0)' class='view' data-url='" + v.link + "'>View Attachment</a></li>";	
						}else{
							output += "					<li><a href='"+ baseURL +"assets/" + v.link + "'>View Attachment</a></li>";	
						}
						output += "					<li><a href='javascript:void(0)' class='upload' data-lifnr='" + lifnr + "' data-vendor='" + vendor + "' data-id_folder='" + v.id_folder + "' data-id_file='" + v.id_file + "' data-nama_dokumen_vendor='" + v.nama_dokumen_vendor + "'>Edit</a></li>";
					}else{
						output += "					<li><a href='javascript:void(0)' class='upload' data-lifnr='" + lifnr + "' data-vendor='" + vendor + "' data-id_folder='" + v.id_folder + "' data-nama_dokumen_vendor='" + v.nama_dokumen_vendor + "'>Upload</a></li>";			
					}
					output += "				</ul>";
					output += "	        </div>";
					output += "		</td>";
					output += '</tr>';
					output += '</tbody>';
					output += '</table>';
					output += '</div>';
                });
				output += '</fieldset>';
				output += '</div>';
				$("#dokumen_vendor").html(output);
				$("input[name='count_mandatory']").val(count_mandatory);
				$("input[name='count_dokumen']").val(count_dokumen);
            }
        },
		complete: function () {
			setTimeout(function () {
				$("table.table-doc").DataTable().columns.adjust();
			}, 1500);				
			
			$('.select2').select2({
				dropdownParent: $('#dokumen_vendor')
			});
		}
    });
}
function create_folder_vendor(lifnr, vendor) {
    $.ajax({
        url: baseURL + 'spk/master/save2/folder',
        type: 'POST',
        dataType: 'JSON',
		data: {
			lifnr : lifnr,
			vendor : vendor
		}
    });
}

