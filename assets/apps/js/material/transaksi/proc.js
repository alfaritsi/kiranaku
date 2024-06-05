$(document).ready(function(){
	let mydDatatables = null;
	
    $(document).on("click", ".check", function(e) {
        if ($(this).is(':checked')) {
            $(this).val($(this).attr("id"))
		}
    });

    //open modal for req     
	$(document).on("click", "#req_button", function(e){
		var req		      = 'y';
		$.ajax({
			url: baseURL+'material/transaksi/get/spec/',
			type: 'POST',
			dataType: 'JSON',
			data: {
				req 			: req
			},
			success: function(data){
				var det	= "";
				var count = 0;
				$.each(data, function(i,v){
					det	+= 		"<tr>";
					det	+= 			"<td align='center'><input type='checkbox' class='checkbox check'  name='ck[]' id='"+count+"'>";
					det	+= 			"<input type='hidden' name='id_item_spec[]' value='"+v.id_item_spec+"'>";
					det	+= 			"<input type='hidden' name='code[]' value='"+v.code+"'>";
					det	+= 			"</td>";
					det	+= 			"<td>"+v.id_item_group+" - "+v.group_description+"</td>";
					det	+= 			"<td>"+v.code_item_name+" - "+v.name_description+"</td>";
					det	+= 			"<td>"+v.code+"</td>";
					det	+= 			"<td>"+v.description+"</td>";
					det	+= 			"<td>"+v.purchase_type+"</td>";
					det	+= 			"<td>"+v.purchase_authorization+"</td>";
					// det	+= 			"<td>"+v.beli_di_nsi2+"</td>";
					// det	+= 			"<td>"+v.specification_check+"</td>";
					det	+= 		"</tr>";
					count++;
				});
				$("#show_detail").html(det);
				$('#count').val(count);
			},
			complete: function () {
                setTimeout(function () {
                    $("table.datatable-periode").DataTable().columns.adjust();
                }, 1500);				
				
				$('#req_modal').modal('show');
				
			}
		});
	});
	//cek all
	$(document).on("change", ".selectALL", function(e){
		let allPages = $("table.datatable-periode").DataTable().rows({'search': 'applied'}).nodes();
	    if($(".selectALL").is(':checked')) {
	    	// Check
			// $(".checkbox").attr("checked", true);
			$(".checkbox", allPages).each(function(){
				$(this).attr("checked", true);
				$(this).val($(this).attr("id"));
			});
	    }else{
			// Uncheck
			// $(".checkbox").attr("checked", false);
			$(".checkbox", allPages).each(function(){ 
				$(this).attr("checked", false);
			});
	    }
		
		e.stopImmediatePropagation();
	});
	
	// Setup datatables
	$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
	    if(oSettings) {
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

    mydDatatables = datatables_ssp();

    //=======FILTER=======//
    $(document).on("change", "#id_item_group_filter, #id_item_name_filter, #status_filter, #filter_request_status", function(){
         datatables_ssp();
    });

	//set on change id_item_group_filter
    $(document).on("change", "#id_item_group_filter", function(e){
		var id_item_group_filter	= $(this).val();
		$.ajax({
    		url: baseURL+'material/transaksi/get/item',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_group_filter	: id_item_group_filter
			},
			success: function(data){
				var value = '';
				$.each(data, function(i,v){
					console.log(data);
					value += '<option value="'+v.id_item_name+'">'+v.description+'</option>';
				});
				$('#id_item_name_filter').html(value);
			}
		});
    });
	
    // //open modal for req     
	// $(document).on("click", "#req_button", function(e){
		// $('#req_modal').modal('show');
	// });
	//sync sap
	$(document).on("click", "#rfc_button", function(e){
		$.ajax({
			url: baseURL + "data/rfc/set/kode_material",
			type: 'POST',
			dataType: 'JSON',
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
	//confirm
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate('.form-transaksi-proc');  
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val(); 
	    	if(isproses == 0){ 
	    		$("input[name='isproses']").val(1);
		    	var formData = $("table.datatable-periode").DataTable().$("input").serializeArray(); //new FormData($(".form-transaksi-proc")[0]);
				
				kiranaConfirm(
					{
						title: "Konfirmasi",
						text: "Kode Material yang dipilih akan dibuat di SAP, apakah proses akan dilanjutkan?",
						dangerMode: true,
						successCallback: function () {
							//push sap
							$.ajax({
								url: baseURL + "data/rfc/set/kode_material",
								type: 'POST',
								dataType: 'JSON',
								data: formData,
								// contentType: false,
								// cache: false,
								// processData: false,
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
							
							// $.ajax({
								// url: baseURL+'material/transaksi/save/proc_confirm',
								// type: 'POST',
								// dataType: 'JSON',
								// data: formData,
								// contentType: false,
								// cache: false,
								// processData: false,
								// success: function(data){
									// if (data.sts == 'OK') {
										// //push data sap
										// rfc_sap();
										// swal('Success', data.msg, 'success').then(function () {
											// location.reload();
										// });
									// } else {
										// $("input[name='isproses']").val(0);
										// swal('Error', data.msg, 'error');
									// }
								// }
							// });
						}
					}
				);
			}
			else{
				$("input[name='isproses']").val(0);
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
			}
		}
		e.preventDefault();
		return false;
    });

	//set purchase_type
	$(document).on("change", "#purchase_type", function (e) {
		var id_item_spec			= $(this).data("id_item_spec");
		var purchase_type			= $(this).val();
		$.ajax({
			url: baseURL + "material/transaksi/save/proc",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_spec 			: id_item_spec,
				purchase_type 			: purchase_type
			},
			success: function(data){
				// if(data.sts == 'OK'){
					// kiranaAlert(data.sts, data.msg);
				// }else{
					// kiranaAlert("notOK", data.msg, "warning", "no");
				// }
			}
		});
		e.preventDefault();
		return false;
	});
	//set purchase_authorization
	$(document).on("change", "#purchase_authorization", function (e) {
		var id_item_spec			= $(this).data("id_item_spec");
		var purchase_authorization	= $(this).val();
		$.ajax({
			url: baseURL + "material/transaksi/save/proc",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_spec 			: id_item_spec,
				purchase_authorization	: purchase_authorization
			},
			success: function(data){
				// if(data.sts == 'OK'){
					// kiranaAlert(data.sts, data.msg);
				// }else{
					// kiranaAlert("notOK", data.msg, "warning", "no");
				// }
			}
		});
		e.preventDefault();
		return false;
	});
	//set purchase_type
	$(document).on("change", "#specification_check", function (e) {
		var id_item_spec			= $(this).data("id_item_spec");
		var specification_check			= $(this).val();
		$.ajax({
			url: baseURL + "material/transaksi/save/proc",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_spec 			: id_item_spec,
				specification_check 	: specification_check
			},
			success: function(data){
				// if(data.sts == 'OK'){
					// kiranaAlert(data.sts, data.msg);
				// }else{
					// kiranaAlert("notOK", data.msg, "warning", "no");
				// }
			}
		});
		e.preventDefault();
		return false;
	});
	//set purchase_type
	$(document).on("change", "#beli_di_nsi2", function (e) {
		var id_item_spec			= $(this).data("id_item_spec");
		var beli_di_nsi2			= $(this).val();
		$.ajax({
			url: baseURL + "material/transaksi/save/proc",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_spec 			: id_item_spec,
				beli_di_nsi2 			: beli_di_nsi2
			},
			success: function(data){
				// if(data.sts == 'OK'){
					// kiranaAlert(data.sts, data.msg);
				// }else{
					// kiranaAlert("notOK", data.msg, "warning", "no");
				// }
			}
		});
		e.preventDefault();
		return false;
	});
	
    //edit
    $(document).on("click", ".edit", function() {
        var id_item_spec = $(this).data("edit");
        $.ajax({
            url: baseURL + 'material/transaksi/get/spec',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_item_spec: id_item_spec
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
                    $("#id_item_spec").val(v.id_item_spec);
                    $("input[name='id_item_spec']").val(v.id_item_spec);
                    $("input[name='code']").val(v.code);
                    $("input[name='description']").val(v.description);
					$("select[name='purchase_type_edit']").val(v.purchase_type).trigger('change');
					$("select[name='purchase_authorization_edit']").val(v.purchase_authorization).trigger('change');
                });
            },
            complete: function() {
                $('#modal_edit').modal('show');
            }
        });
    });	
    //save edit
    $(document).on("click", "button[name='action_btn_edit']", function(e) {
        var empty_form = validate('.form-transaksi-edit-proc');
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-transaksi-edit-proc")[0]);
				console.log();
				$.ajax({
					url: baseURL + 'material/transaksi/save/proc_edit',
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
});
function rfc_sap(){
	$.ajax({
		url: baseURL + "data/rfc/set/kode_material",
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			if(data.sts == 'OK'){
				kiranaAlert(data.sts, data.msg);
			}else{
				kiranaAlert("notOK", data.msg, "warning", "no");
			}
		}
	});
	// e.preventDefault();
	return false;
}
function datatables_ssp(){
    var id_item_group	= $("#id_item_group_filter").val();
    var id_item_name 	= $("#id_item_name_filter").val();
    var status		 	= $("#status_filter").val();
    var filter_request_status 	= $("#filter_request_status").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
	    // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
	    // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        pageLength: 25,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input')
                .off('.DT')
                .on('input.DT', function () {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: baseURL+'material/transaksi/get/spec/bom',
            type: 'POST',
            data: function(data){
                data.id_item_group = id_item_group;
                data.id_item_name = id_item_name;
                data.status = status;
                data.filter_request_status = filter_request_status;
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "id_item_spec",
                "name" : "id_item_spec",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.id_item_spec;
                },
                "visible": false
            },
            {
				"data": "id_item_group",
                "name" : "group_description",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.id_item_group+'-'+row.group_description;
                }
            },
            {
				"data": "id_item_name",
                "name" : "name_description",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.name_code+'-'+row.name_description;
                }
            },
            {
				"data": "code",
                "name" : "code",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.code;
                }
            },
            {
				"data": "description",
                "name" : "description",
                "width": "30%",
                "render": function (data, type, row) {
                    return row.description;
                }
            },
            {
                "data": "purchase_type",
                "name" : "purchase_type",
                "width": "5%",
                "render": function (data, type, row) {
					if((row.purchase_type!='')&&(row.req=='n')){
						output = row.purchase_type;
					}else{
						if(row.purchase_type=='PO'){
							var ck1 = 'selected';
						}
						if(row.purchase_type=='Non PO'){
							var ck2 = 'selected';
						}
						output = "";
						output += "	<select class='form-control select2modal' name='purchase_type' id='purchase_type' data-id_item_spec='"+row.id_item_spec+"'  required='required'>";
						output += "		<option value='0'>-Chose-</option>";
						output += "		<option value='PO' "+ck1+">PO</option>";
						output += "		<option value='Non PO' "+ck2+">Non PO</option>";
						output += "	</select>";
					}
                    return output;
                }
            },
            {
                "data": "purchase_authorization",
                "name" : "purchase_authorization",
                "width": "5%",
                "render": function (data, type, row) {
					if((row.purchase_authorization!='')&&(row.req=='n')){
						output = row.purchase_authorization;
					}else{
						if(row.purchase_authorization=='HO'){
							var ck1 = 'selected';
						}
						if(row.purchase_authorization=='Pabrik'){
							var ck2 = 'selected';
						}
						output = "";
						output += "	<select class='form-control select2modal' name='purchase_authorization' id='purchase_authorization' data-id_item_spec='"+row.id_item_spec+"'  required='required'>";
						output += "		<option value='0'>-Chose-</option>";
						output += "		<option value='HO' "+ck1+">HO</option>";
						output += "		<option value='Pabrik' "+ck2+">Pabrik</option>";
						output += "	</select>";
					}
                    return output;
                }
            },
            // {
                // "data": "tbl_item_spec.beli_di_nsi2",
                // "name" : "beli_di_nsi2",
                // "width": "5%",
                // "render": function (data, type, row) {
					// if((row.beli_di_nsi2!='')&&(row.req=='n')){
						// output = row.beli_di_nsi2;
					// }else{
						// if(row.beli_di_nsi2=='Yes'){
							// var ck1 = 'selected';
						// }
						// if(row.beli_di_nsi2=='No'){
							// var ck2 = 'selected';
						// }
						// output = "";
						// output += "	<select class='form-control select2modal' name='beli_di_nsi2' id='beli_di_nsi2' data-id_item_spec='"+row.id_item_spec+"'  required='required'>";
						// output += "		<option value='0'>-Chose-</option>";
						// output += "		<option value='Yes' "+ck1+">Yes</option>";
						// output += "		<option value='No' "+ck2+">No</option>";
						// output += "	</select>";
					// }
                    // return output;
                // }
            // },
            // {
                // "data": "tbl_item_spec.specification_check",
                // "name" : "specification_check",
                // "width": "5%",
                // "render": function (data, type, row) {
					// if((row.specification_check!='')&&(row.req=='n')){
						// output = row.specification_check;
					// }else{
						// if(row.specification_check=='Yes'){
							// var ck1 = 'selected';
						// }
						// if(row.specification_check=='No'){
							// var ck2 = 'selected';
						// }
						// output = "";
						// output += "	<select class='form-control select2modal' name='specification_check' id='specification_check' data-id_item_spec='"+row.id_item_spec+"'  required='required'>";
						// output += "		<option value='0'>-Chose-</option>";
						// output += "		<option value='Yes' "+ck1+">Yes</option>";
						// output += "		<option value='No' "+ck2+">No</option>";
						// output += "	</select>";
					// }
                    // return output;
                // }
            // },
            {
                "data": "req",
                "name" : "req",
                "width": "5%",
                "render": function (data, type, row) {
                    if(row.req=='n'){
                        return '<label class="label label-success">Completed</label>';
                    }else{
                        return '<label class="label label-warning">Requested</label>';
                    }
                }
            },
            {
                "data": "id_item_spec",
                "name": "id_item_spec",
                "width": "5%",
                "render": function(data, type, row) {
					if(row.req=='n'){
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "					<li><a href='javascript:void(0)' class='edit' data-edit='" + row.id_item_spec + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
						output += "				</ul>";
						output += "	        </div>";
					}else{
						output = "";
					}
                    return output;
                }
            }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            if(info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });

    return mydDatatables;
}