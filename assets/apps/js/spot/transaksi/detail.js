$(document).ready(function () {
	//export to excel
    $(document).on('click', '#excel_button', function (e) {
		// var filter_request_status	= $("#filter_request_status").val();
		// var filter_status			= $("#filter_status").val();
		// var filter_from		= $("#filter_from").val();
		// var filter_to		= $("#filter_to").val();
		
        e.preventDefault();
        window.open(
            baseURL + 'spot/transaksi/excel'
        );

    })
    //open modal for imp    
	$(document).on("click", "#imp_button", function(e){
		resetForm_use($('.form-transaksi-spot-imp'));
		$('#imp_modal').modal('show');
	});
	//imp
	$(document).on("click", "button[name='action_btn_imp']", function(e){
		var empty_form = validate('.form-transaksi-spot-imp');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-spot-imp")[0]);
				$.ajax({
					url: baseURL+'spot/transaksi/save/excel_spot',
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
    });
	
	//filter pabrik
	$.ajax({
		url: baseURL+'spot/master/get/plant',
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			var no = 0;
			var list = '';
			$.each(data, function(i,v){
				list += '<option value="'+v.WERKS+'">'+v.WERKS+'</option>';
			});
			$('#plant').html(list);
		}
	});
	
	//filter buyer
	$.ajax({
		url: baseURL+'spot/master/get/buyer',
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			var no = 0;
			var list = '';
			$.each(data, function(i,v){
				list += '<option value="'+v.NMBYR+'">'+v.NMBYR+'</option>';
			});
			$('#buyer').html(list);
		}
	});
	// Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        if (oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
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
    $(document).on("change", "#plant, #tahun, #buyer, #status", function () {
        datatables_ssp();
    });

});

function datatables_ssp() {
    var plant = $("#plant").val();
    var tahun = $("#tahun").val();
    var buyer = $("#buyer").val();
    var status = $("#status").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
		ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        // bautoWidth: false,
		// dom: 'Bfrtip',
        // buttons: [
            // {
                // extend: 'excelHtml5',
                // text: 'Export to Excel',
                // title: 'List Detail Sales SPOT',
                // download: 'open',
                // orientation:'landscape',
                // exportOptions: {
                    // columns: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16],
					// modifier: {
					  // page: 'all',
					  // search: 'none'   
					// }
					
                // }
            // }
        // ],
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input').attr("placeholder", "Press enter to start searching");
            $('#sspTable_filter input').attr("title", "Press enter to start searching");
            $('#sspTable_filter input')
                .off('.DT')
                .on('keypress change', function (evt) {
                    console.log(evt.type);
                    if(evt.type == "change"){
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
            url: baseURL + 'spot/transaksi/get/detail/bom',
            type: 'POST',
            data: function (data) {
                data.plant = plant;
                data.tahun = tahun;
                data.buyer = buyer;
                data.status = status;
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "id_simulate",
                "name": "ID",
                "render": function (data, type, row) {
                    return row.id_simulate;
                },
                "visible": false
            },
            {
                "data": "date",
                "name": "date",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.tanggal_view;
                }
            },
            {
                "data": "no_form",
                "name": "no_form",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.no_form;
                }
            },
            {
                "data": "no_contract",
                "name": "no_contract",
                "width": "6%",
                "render": function (data, type, row) {
                    return row.no_contract;
                }
            },
            {
                "data": "buyer",
                "name": "buyer",
                "width": "50%",
                "render": function (data, type, row) {
                    return row.buyer;
                }
            },
            {
                "data": "tppco",
                "name": "tppco",
                "width": "3%",
                "render": function (data, type, row) {
                    return row.tppco;
                }
            },
            {
                "data": "qty",
                "name": "qty",
                "width": "3%",
                "render": function (data, type, row) {
					return '<div align="right">'+numberWithCommas(parseFloat(row.qty).toFixed(2))+'</div>';
                }
            },
            {
                "data": "shipment_periode",
                "name": "shipment_periode",
                "width": "3%",
                "render": function (data, type, row) {
                    return row.shipment_periode;
                }
            },
            {
                "data": "prod_grade",
                "name": "prod_grade",
                "width": "3%",
                "render": function (data, type, row) {
                    return row.prod_grade;
                }
            },
            {
                "data": "pol",
                "name": "pol",
                "width": "3%",
                "render": function (data, type, row) {
                    return row.pol;
                }
            },
            {
                "data": "selling_price_usc",
                "name": "selling_price_usc",
                "width": "3%",
                "render": function (data, type, row) {
					return '<div align="right">'+numberWithCommas(parseFloat(row.selling_price_usc).toFixed(2))+'</div>';
               }
            },
            {
                "data": "cur_rate",
                "name": "cur_rate",
                "width": "3%",
                "render": function (data, type, row) {
					return '<div align="right">'+numberWithCommas(parseFloat(row.cur_rate).toFixed(2))+'</div>';
                }
            },
            {
                "data": "selling_price",
                "name": "selling_price",
                "width": "3%",
                "render": function (data, type, row) {
                    return '<div align="right">'+numberWithCommas(parseFloat(row.selling_price).toFixed(2))+'</div>';
                }
            },
            {
                "data": "mtd_price",
                "name": "mtd_price",
                "width": "3%",
                "render": function (data, type, row) {
                    return '<div align="right">'+numberWithCommas(parseFloat(row.mtd_price).toFixed(2))+'</div>';
                }
            },
            {
                "data": "deal_harga_pembelian",
                "name": "deal_harga_pembelian",
                "width": "3%",
                "render": function (data, type, row) {
                    if(row.deal_harga_pembelian != undefined && row.deal_harga_pembelian != ""){
                        return '<div align="right">'+numberWithCommas(parseFloat(row.deal_harga_pembelian).toFixed(2))+'</div>';
                    } else {
                        return '<div align="right">'+0+'</div>';
                    }
                }
            },
            {
                "data": "prod_cost",
                "name": "prod_cost",
                "width": "3%",
                "render": function (data, type, row) {
                    return '<div align="right">'+numberWithCommas(parseFloat(row.prod_cost).toFixed(2))+'</div>';
                }
            },
            {
                "data": "trucking_cost",
                "name": "trucking_cost",
                "width": "3%",
                "render": function (data, type, row) {
                    return '<div align="right">'+numberWithCommas(parseFloat(row.trucking_cost).toFixed(0))+'</div>';
                }
            },
            {
                "data": "carry_cost",
                "name": "carry_cost",
                "width": "3%",
                "render": function (data, type, row) {
					return '<div align="right">'+numberWithCommas(parseFloat(row.carry_cost).toFixed(2))+'</div>';	
                }
            },
            {
                "data": "margin",
                "name": "margin",
                "width": "3%",
                "render": function (data, type, row) {
					if(row.margin<0){
						return '<div align="right"><font color="red">'+numberWithCommas(parseFloat(row.margin).toFixed(2))+'</font></div>';	
					}else{
						return '<div align="right">'+numberWithCommas(parseFloat(row.margin).toFixed(2))+'</div>';	
					}
                }
            },
            {
                "data": "sicom",
                "name": "sicom",
                "width": "3%",
                "render": function (data, type, row) {
                    if(row.sicom<0){
                        return '<div align="right">'+0+'</div>'; 
                    }else{
                        return '<div align="right">'+parseFloat(row.sicom).toFixed(2)+'</div>';  
                    }
                }
            },
            {
                "data": "amount",
                "name": "amount",
                "width": "3%",
                "render": function (data, type, row) {
					if(row.amount<0){
						return '<div align="right"><font color="red">'+numberWithCommas(parseFloat(row.amount).toFixed(2))+'</font></div>';	
					}else{
						return '<div align="right">'+numberWithCommas(parseFloat(row.amount).toFixed(2))+'</div>';	
					}
                }
            },
            {
                "data": "margin_after_packing",
                "name": "margin_after_packing",
                "width": "3%",
                "render": function (data, type, row) {
					var margin_after_packing = row.margin-(0.005*row.cur_rate);
					return '<div align="right">'+numberWithCommas(parseFloat(margin_after_packing).toFixed(2)) +'</div>';	
                }
            },
            {
                "data": "amount_after_packing",
                "name": "amount_after_packing",
                "width": "3%",
                "render": function (data, type, row) {
					var margin_after_packing = row.margin-(0.005*row.cur_rate);
					var amount_after_packing = row.qty * 1000 * margin_after_packing;
					return '<div align="right">'+ numberWithCommas(parseFloat(amount_after_packing).toFixed(0)) +'</div>';	
                }
            },
            {
                "data": "note",
                "name": "note",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.note;
                }
            },
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

function resetForm_use($form,$act) {
	$('#myModalLabel').html("Form Item Spec");
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
	$("#sales_plant").val(0).trigger("change");
	$('#plant_extend').prop('disabled', false);
	if($act!='edit'){
		$("#show_images").hide();
	}
	$("#gambar").show();	
	$("#btn_save").show();
	$('#isproses').val("");
	$('#isconvert').val('0');
	$('#code').prop('disabled', true);
	$('#detail').prop('disabled', true);
}

