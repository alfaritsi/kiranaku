/*
@application  : KODE MATERIAL
@author       : Lukman Hakim (7143)
@contributor  : 
      1. Airiza Yuddha (7849) 14 okt 2020
         a. modified change function (#filter_from,#filter_to,#filter_request_status,#filter_status) 
         	- add field estimate price
         b. modified datables function my-datatable-extends-order 
         	- add columndef and edit field exportOptions   
         c. add function addcommas
         d. add change function (.angkas)
         e. modified click function (button[name='action_btn']) - add estimate price
         
	  2. Airiza Yuddha (7849) 21 okt 2020
         a. add field spec_desc

      3. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

$(document).ready(function(){
	//export to excel
    $(document).on('click', '#excel_button', function (e) {
		var filter_request_status	= $("#filter_request_status").val();
		var filter_status			= $("#filter_status").val();
		var filter_from		= $("#filter_from").val();
		var filter_to		= $("#filter_to").val();
		
        e.preventDefault();
        window.open(
            baseURL + 'material/transaksi/excel'
        );

    })

    // function only number
    $(document).on("change", ".angkas", function(e) {
		var angka = $(this).val().replace(/[^0-9.^-]*/g, ''); 
		$(this).val(angka);
		e.preventDefault();
		return false;
	});
	 
    //=======FILTER=======//
	$(document).on("change", "#filter_from,#filter_to,#filter_request_status,#filter_status", function(){
		var filter_request_status	= $("#filter_request_status").val();
		var filter_status			= $("#filter_status").val();
		var filter_from		= $("#filter_from").val();
		var filter_to		= $("#filter_to").val();
		$.ajax({
			url: baseURL+'material/transaksi/get/request',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	filter_request_status 	: filter_request_status,
	        	filter_status 			: filter_status,
	        	filter_from 			: filter_from,
	        	filter_to 				: filter_to
	        },
	        success: function(data){
				var gambar 	= "";
				var output 	= "";
	        	var desc	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					//gambar
					// if(v.list_gambar!==null){
						// gambar	 	= "<img src='"+v.list_gambar.slice(0, -1)+"' height='80'>";		
					// }
					if(v.list_gambar!== null){
						var list_gambar		= v.list_gambar.slice(0, -1).split("|");
						var array  = [];
						var det	= "";
						$.each(list_gambar, function(x, y){
							det	+= 		"<img src='"+y+"' class='img-thumbnail' style='height:80px;'>";
						});
					}else{
						det	= 		"-";
					}	
					
					//action
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(v.na == 'n'){ 
						if(v.req == 'o'){
							output += 			"<li><a href='javascript:void(0)' class='edit' data-edit='"+v.id_item_request+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
							output += 			"<li><a href='javascript:void(0)' class='nonactive' data-nonactive='"+v.id_item_request+"'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
						}else{
							output += 			"<li><a href='javascript:void(0)' class='edit' data-edit='"+v.id_item_request+"' data-btn_save='hidden'><i class='fa fa-search'></i> Detail</a></li>";
						}
					}
					if(v.na == 'y'){
						output += 				"<li><a href='javascript:void(0)' class='setactive' data-setactive='"+v.id_item_request+"'><i class='fa fa-check'></i> Set Aktif</a></li>";
					}
					output += "				</ul>";
					output += "	        </div>";

					var tanggal_req 	= v.req != 'o' ? v.tanggal+'<br>'+v.jam_buat : "-";
				    var tanggal_conf 	= v.req != 'o' ? v.tanggal_conf+'<br>'+v.jam_conf : "-";
				    var code_spec 		= v.code != null ? v.code : v.code_spec;
						code_spec		= code_spec != null ? code_spec : '';
				    var classification 	= v.req == 'n' ? v.label_classification : "";
				    var spec_desc 		= v.spec_desc != null ? v.spec_desc : v.spec_desc_sap;
					var price_wf 		= v.estimate_price != "" && v.estimate_price != null ? addCommas(v.estimate_price) : "-";
					var price_nf 		= v.estimate_price != "" && v.estimate_price != null ? v.estimate_price : "-";
									
	        		t.row.add( [
			            tanggal_req,
						tanggal_conf,
			            v.type,
			            v.description,
			            v.uom,
			            (price_wf),
			            (price_nf),
			            det,
			            code_spec+'<br>'+classification,
			            spec_desc,
			            v.label_request,
			            v.nama_pic+'-'+v.nik_pic,
			            v.label_status,
			            output
			        ] ).draw( false );
	        	});
			
	        }
		});
	});
	//change	
    $(document).on("change", "#type", function(e){
		var ck = $("#type").val();
   		if(ck=='Barang'){
			// $('#gambar').prop('disabled', false);
			$('#gambar').prop('required', true);
   		} else {
			// $('#gambar').prop('disabled', false);
			$('#gambar').prop('required', false);
   		}
    });
	//edit
	$(document).on("click", ".edit", function(){
		var id_item_request	= $(this).data("edit");
		var btn_save		= $(this).data("btn_save");
		$.ajax({
    		url: baseURL+'material/transaksi/get/request',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_request : id_item_request
			},
			success: function(data){
				$(".title-form").html("Form Item Spec");
				$.each(data, function(i,v){
					$("#id_item_request").val(v.id_item_request);
					$("select[name='type']").val(v.type).trigger("change");
					$("input[name='description']").val(v.description);
					$("input[name='uom']").val(v.uom);
					$("input[name='estimate_price']").val(v.estimate_price);
					// if(v.list_gambar!== null){
						// $(".gambar").attr('src', v.list_gambar.slice(0, -1));	
					// }
					if(v.list_gambar!== null){
						var list_gambar		= v.list_gambar.slice(0, -1).split("|");
						var array  = [];
						var det	= "";
						$.each(list_gambar, function(x, y){
							// console.log(y);
							det	+= 		"<img src='"+y+"' class='img-thumbnail' style='height:80px;'>";
						});
						$("#show_images").html(det);						
					}	
					
				});
			},
			complete: function () {
				if(btn_save=='hidden'){
					$("#btn_save").hide();
					$('#type').prop('disabled', true);
					$('#description').prop('disabled', true);
					$('#uom').prop('disabled', true);
					$('#estimate_price').prop('disabled', true);
					$("#show_images").show();
					$('#gambar').hide();
				} else {
					$("#btn_save").show();
					$('#type').prop('disabled', false);
					$('#description').prop('disabled', false);
					$('#uom').prop('disabled', false);
					$('#estimate_price').prop('disabled', false);
					$("#show_images").show();
					$('#gambar').show();	
				}
				$('#gambar').prop('required', false);
				$('#add_modal').modal('show');
			}

		});
    });


	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "material/transaksi/set/request",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_request : $(this).data($(this).attr("class")),
				type 	  	 	: $(this).attr("class")
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
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form 	= validate('.form-transaksi-request');
		var price 		= $('#estimate_price');
		
		// replace price
		var angka = price.val().replace(/[^0-9.^-]*/g, ''); 
		price.val(angka);
		// return false;
		valprice = parseInt(price.val());
		
		if((valprice > 0) == false ){
			console.log("masuk");
			$("input[name='isproses']").val(0);
			price.val("");
			swal('Error', "Mohon isikan angka pada field estimasi harga !", 'error');
			e.preventDefault();
			return false;
		}

		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-request")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'material/transaksi/save/request',
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
	$(document).on("click", "button[name='action_btn_ho']", function(e){
		var empty_form = validate('.form-transaksi-request-ho');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-request-ho")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'material/transaksi/save/request_ho',
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
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		// dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel (Tanpa Gambar)',
                title: 'Request Material Code',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,6,7,8,9,10]
                },
            }
        ],
        columnDefs: [
		    {
		        targets: 5,
		        className: 'text_right'
		    },
		    {
		    	targets:6,
		    	"visible": false,
		    }
		],
		scrollX:true
    } );

    //open modal for add     
	$(document).on("click", "#add_button", function(e){
		resetForm_use($('.form-transaksi-request'));
		$("#btn_save").show();
		$('#type').select2('destroy').find('option').prop('selected', false).end().select2();
		$("#show_images").hide();
		$("#gambar").show();	
		$('#add_modal').modal('show');
	});
    //open modal for req     
	$(document).on("click", "#req_button", function(e){
		// resetForm_use($('.form-transaksi-request-ho'));
		// $('#req_modal').modal('show');
		var req	= 'o';
		$.ajax({
			url: baseURL+'material/transaksi/get/request',
			type: 'POST',
			dataType: 'JSON',
			data: {
				req : req
			},
			success: function(data){
				var det	= "";
				var count = 0;
				$.each(data, function(i,v){
					count++;
					det	+= 		"<tr>";
					det	+= 			"<td align='center'><input type='checkbox' class='checkbox' name='ck_"+count+"' id='ck_"+count+"'>";
					det	+= 			"<input type='hidden' name='id_item_request_"+count+"' value='"+v.id_item_request+"'>";
					det	+=			"</td>";
					det	+= 			"<td>"+v.tanggal+"</td>";
					det	+= 			"<td>"+v.type+"</td>";
					det	+= 			"<td>"+v.description+"</td>";
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
	//tanggal
    $('.datePicker').datepicker({
    	format: 'dd.mm.yyyy', 
        changeMonth: true,
        changeYear: true,
        autoclose: true
    }); 
});
function resetForm_use($form) {
	$form.find('input:text, input:password, input:file,  textarea').val("");
	$form.find('input:text, input:password, input:file,  textarea').prop('disabled', false);
	$form.find('select').val(0);
	$form.find('select').prop('disabled', false);
	$form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
	$form.find('input:radio, input:checkbox').prop('disabled', false);
}

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
}
