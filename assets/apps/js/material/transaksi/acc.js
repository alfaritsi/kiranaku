$(document).ready(function(){
    //open modal for req     
	$(document).on("click", "#req_button", function(e){
		var req		      = 'y';
		$.ajax({
			// url: baseURL+'material/transaksi/get/request',
			url: baseURL+'material/master/get/item',
			type: 'POST',
			dataType: 'JSON',
			data: {
				req 			: req
			},
			success: function(data){
				console.log(data);
				var det	= "";
				var count = 0;
				$.each(data, function(i,v){
					count++;
					det	+= 		"<tr>";
					det	+= 			"<td align='center'><input type='checkbox' class='checkbox' name='ck_"+count+"' id='ck_"+count+"'></td>";
					det	+= 			"<input type='hidden' name='id_item_name_"+count+"' value='"+v.id_item_name+"'>";
					det	+= 			"</td>";
					det	+= 			"<td>"+v.id_item_group+" - "+v.group_description+"</td>";
					det	+= 			"<td>"+v.code+" - "+v.description+"</td>";
					det	+= 			"<td>"+v.bklas+" - "+v.bkbez+"</td>";
					det	+= 			"<td>"+v.matkl+" - "+v.wgbez+"</td>";
					det	+= 			"<td>"+v.classification_name+"</td>";
					det	+= 			"<td>"+v.price_control_name+"</td>";
					det	+= 		"</tr>";
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
	    if($(".selectALL").is(':checked')) {
	    	// Check
			$(".checkbox").attr("checked", true);
	    }else{
			// Uncheck
			$(".checkbox").attr("checked", false);
	    }
	});
	
    //=======FILTER=======//
	$(document).on("change", "#filter_request_status", function(){
		var filter_request_status	= $("#filter_request_status").val();
		$.ajax({
			url: baseURL+'material/master/get/item',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	filter_request_status 	: filter_request_status
	        },
	        success: function(data){
				var output 	= "";
	        	var desc	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					if((v.price_control!='')&&(v.req=='n')){
						output = v.price_control_name;
					}else{
						if(v.price_control=='S'){
							var ck_s = 'selected';
						}
						if(v.price_control=='V'){
							var ck_v = 'selected';
						}
						output = "	<select class='form-control select2modal' name='price_control' id='price_control' data-id_item_name='"+v.id_item_name+"'  required='required'>";
						output += "		<option value='0'>-Chose Price Control-</option>";
						output += "		<option value='S' "+ck_s+">Standard Price</option>";
						output += "		<option value='V' "+ck_v+">Moving Price</option>";
						output += "	</select>";
					}
	        		t.row.add( [
			            v.id_item_group+' - '+v.group_description,
			            v.code+' - '+v.description,
			            v.classification_name,
						v.bklas+' - '+v.bkbez,
			            v.matkl+' - '+v.wgbez,
						v.mtart+' - '+v.mtbez,
						output,
			            v.label_req
			            
			        ] ).draw( false );
					
	        	});
			
	        }
		});
	});
	
    // //open modal for req     
	// $(document).on("click", "#req_button", function(e){
		// $('#req_modal').modal('show');
	// });
	//confirm
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate('.form-transaksi-acc');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-acc")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'material/transaksi/save/acc_confirm',
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
	//set price control	
	$(document).on("change", "#price_control", function (e) {
		var id_item_name	= $(this).data("id_item_name");
		var price_control	= $(this).val();
		$.ajax({
			url: baseURL + "material/transaksi/save/acc",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_name 	: id_item_name,
				price_control 	: price_control
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
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Confirm Accounting Data',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6]
                }
            }
        ],
		scrollX:true
    } );
});
