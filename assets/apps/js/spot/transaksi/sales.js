$(document).ready(function () {
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
    $(document).on("change", "#tahun, #buyer, #status", function () {
        datatables_ssp();
    });
	//delete
	$(document).on("click", ".delete", function (e) {
		var nomor = $(this).data("nomor");
		var no_form = $(this).data("no_form");
		kiranaConfirm(
			{
				title: "Konfirmasi",
				text: "Apakah anda akan menghapus Sales Confirmation Form No "+nomor,
				dangerMode: true,
				successCallback: function () {
					$.ajax({
						url: baseURL + "spot/transaksi/set/sales",
						type: 'POST',
						dataType: 'JSON',
						data: {
							no_form	: no_form,	
							type 	: 'non_active'
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
				}
			}
		);
		
	});
	
	//sap
	$(document).on("click", ".sap", function (e) {
        var no_form = $(this).data("no_form");
        $.ajax({
            // url: baseURL + 'spot/transaksi/get/resend',
			url: baseURL + "data/rfc/set/spot/"+no_form,
            type: 'POST',
            dataType: 'JSON',
            data: {
                no_form: no_form
            },
            success: function (data) {
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
	
	//resend
	$(document).on("click", ".resend", function (e) {
        var no_form = $(this).data("no_form");
        var buyer = $(this).data("buyer");
        $.ajax({
            url: baseURL + 'spot/transaksi/get/resend',
            type: 'POST',
            dataType: 'JSON',
            data: {
                no_form: no_form,
                buyer: buyer
            },
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
		e.preventDefault();
		return false;
    });
	
	//on click
    $(document).on("click", ".detail", function () {
        var no_form = $(this).data("no_form");
        $.ajax({
            url: baseURL + 'spot/transaksi/get/detail',
            type: 'POST',
            dataType: 'JSON',
            data: {
                no_form: no_form
            },
            success: function (data) {
                console.log(data);
				var list_nilai	= '';
                $.each(data, function (i, v) {
					$(".modal-title").html(v.nomor);
					list_nilai	+=			'			<fieldset class="fieldset-info">';
					list_nilai	+=			'				<legend>Group Name</legend>';
					list_nilai	+=			'				<div class="row">';
					list_nilai	+=			'					<div class="col-xs-5">';
					list_nilai	+=			'						<div class="form-group">';
					list_nilai	+=			'							<div class="row">';
					list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Buyer</label></div>';
					list_nilai	+= 			'								<div class="col-xs-7">: '+v.buyer+'</div>';
					list_nilai	+=			'							</div>';	
					list_nilai	+=			'						</div>';
					list_nilai	+=			'						<div class="form-group">';
					list_nilai	+=			'							<div class="row">';
					list_nilai	+=			'									<div class="col-xs-5"><label for="buyer">Factory</label></div>';
					list_nilai	+= 			'									<div class="col-xs-7">: '+v.werks+'</div>';
					list_nilai	+=			'							</div>';	
					list_nilai	+=			'						</div>';
					list_nilai	+=			'						<div class="form-group">';
					list_nilai	+=			'							<div class="row">';
					list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Grade</label></div>';
					list_nilai	+=			'								<div class="col-xs-7">: '+v.prod_grade+'</div>';
					list_nilai	+=			'							</div>';	
					list_nilai	+=			'						</div>';
					list_nilai	+=			'						<div class="form-group">';
					list_nilai	+=			'							<div class="row">';
					list_nilai	+=			'								<div class="col-xs-5"><label for="prod_grade_det">Qty</label></div>';
					list_nilai	+=			'								<div class="col-xs-7">: '+v.qty+'</select>';
					list_nilai	+=			'								</div>';
					list_nilai	+=			'							</div>';	
					list_nilai	+=			'						</div>';
					list_nilai	+=			'					</div>';
					list_nilai	+=			'					<div class="col-xs-2"></div>';
					list_nilai	+=			'					<div class="col-xs-5">';
					list_nilai	+=			'						<div class="form-group">';
					list_nilai	+=			'							<div class="row">';
					list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Shipment Period</label></div>';
					list_nilai	+=			'								<div class="col-xs-7">: '+v.shipment_periode+'</div>';
					list_nilai	+=			'							</div>';	
					list_nilai	+=			'						</div>';
					list_nilai	+=			'						<div class="form-group">';
					list_nilai	+=			'							<div class="row">';
					list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Shipment Term</label></div>';
					list_nilai	+=			'								<div class="col-xs-7">: '+v.shipment_term+'</div>';
					list_nilai	+=			'							</div>';	
					list_nilai	+=			'						</div>';
					list_nilai	+=			'						<div class="form-group">';
					list_nilai	+=			'							<div class="row">';
					list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Price</label></div>';
					list_nilai	+=			'								<div class="col-xs-7">: '+v.price+'</div>';
					list_nilai	+=			'							</div>	';
					list_nilai	+=			'						</div>';
					list_nilai	+=			'						<div class="form-group">';
					list_nilai	+=			'							<div class="row">';
					list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Tentative Margin</label></div>';
					list_nilai	+=			'								<div class="col-xs-7">: '+v.margin+'</div>';
					list_nilai	+=			'							</div>';	
					list_nilai	+=			'						</div>';
					list_nilai	+=			'					</div>';
					list_nilai	+=			'				</div>';	
					list_nilai	+=			'			</fieldset>';								
                });
				$("#show_detail").html(list_nilai);
            },
            complete: function () {
                $('#detail_modal').modal('show');
            }

        });
    });
	

});

function datatables_ssp() {
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
            url: baseURL + 'spot/transaksi/get/sales/bom',
            type: 'POST',
            data: function (data) {
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
                "data": "no_form",
                "name": "ID",
                "render": function (data, type, row) {
                    return row.no_form;
                },
                "visible": false
            },
            {
                "data": "nomor",
                "name": "nomor",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nomor;
                }
            },
            {
                "data": "no_contract",
                "name": "no_contract",
                "width": "5%",
                "render": function (data, type, row) {
					return row.no_contract;
                },
                "visible": true
            },
            {
                "data": "buyer",
                "name": "buyer",
                "width": "15%",
                "render": function (data, type, row) {
                    return row.buyer;
                }
            },
            {
                "data": "shipment_periode",
                "name": "shipment_periode",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.shipment_periode;
                }
            },
            {
                "data": "status",
                "name": "status",
                "width": "5%",
                "render": function (data, type, row) {
                    if (row.status == 1) {
                        var status_email = '<label class="label label-success">Sent</label>';
                    }else{
						var status_email = '<label class="label label-danger">Failed</label>';
					}
                    if (row.na == 'y') {
                        var status_del = '<label class="label label-danger">Deleted</label>';
                    }else{
						var status_del = '';
					}
					return status_email+'<br>'+status_del;
                },
                "visible": true
            },
            {
                "data": "no_form",
                "name": "no_form",
                "width": "5%",
                "render": function (data, type, row) {
					var url = baseURL+"spot/transaksi/sales/"+row.no_form;
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					output += "					<li><a href='javascript:void(0)' class='detail' data-no_form='" + row.no_form + "'><i class='fa fa-search'></i> Detail</a></li>";	
					if(row.na == 'n'){
						output += "					<li><a href='javascript:void(0)' class='resend' data-no_form='" + row.no_form + "' data-buyer='" + row.buyer + "'><i class='fa fa-envelope'></i> Resend</a></li>";
						output += "					<li><a href='javascript:void(0)' class='sap' data-no_form='" + row.no_form + "' ><i class='fa fa-repeat'></i> Sync SAP</a></li>";
						if(row.no_contract == null){
							output += "				<li><a href='"+url+"' class='edit' data-no_form='" + row.no_form + "' ><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
							output += "				<li><a href='javascript:void(0)' class='delete' data-no_form='" + row.no_form + "' data-nomor='" + row.nomor + "'><i class='fa fa-trash-o'></i> Delete</a></li>";
						}
					}
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