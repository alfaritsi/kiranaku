/*
@application  : KODE MATERIAL
@author       : Lukman Hakim (7143)
@contributor  : 
      1. Airiza Yuddha (7849) 14 okt 2020
         a. modified function change (#filter_from,#filter_to,#filter_request_status) 
         	- add field estimate price, confirmed date , spec desc
         b. modified datables function my-datatable-extends-order 
         	- add columndef and edit field exportOptions   
         c. add function addcommas
         
      2. Airiza Yuddha (7849) 21 okt 2020
         a. add function click (.edit)

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
            baseURL + 'material/transaksi/excel_konfirmasi'
        );

    })
	
    //=======FILTER=======//
	$(document).on("change", "#filter_from,#filter_to,#filter_request_status", function(){
		var filter_request_status	= $("#filter_request_status").val();
		var filter_from		= $("#filter_from").val();
		var filter_to		= $("#filter_to").val();
		$.ajax({
			url: baseURL+'material/transaksi/get/input',
	        type: 'POST',
	        dataType: 'JSON',
	        data: { 
	        	filter_request_status 	: filter_request_status,
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
						if(v.req == 'y'){
							output +=			"<li><a href='javascript:void(0)' class='input' data-edit='"+v.id_item_request+"'><i class='fa fa-pencil-square-o'></i> Konfirmasi</a></li>";
							output +=			"<li><a href='javascript:void(0)' class='inventory' data-edit='"+v.id_item_request+"'><i class='fa fa-pencil-square-o'></i> Konfirmasi Inventory</a></li>"; 
							output +=			"<li><a href='javascript:void(0)' class='add' data-edit='"+v.id_item_request+"'><i class='fa fa-list-alt'></i> Material Code(Belum Ada)</a></li>";
							output +=			"<li><a href='javascript:void(0)' class='tolak' data-edit='"+v.id_item_request+"' data-type='"+v.type+"' data-description='"+v.description+"'><i class='fa fa-minus-square'></i> Ditolak</a></li>"; 
						}else{
							output +=			"<li><a href='javascript:void(0)' class='edit' data-edit='"+v.id_item_request+"' data-btn_save='hidden'><i class='fa fa-search'></i> Detail</a></li>";
						}
					}
					output += "				</ul>";
					output += "	        </div>";
					var tanggal_req 	= v.tanggal != "" ? v.tanggal+'<br>'+v.jam_buat : "-";
				    var tanggal_conf 	= v.req == 'y' ? "-" : v.tanggal_conf+'<br>'+v.jam_conf ;
				    var code_spec 		= v.code != null ? v.code : v.code_spec;
						code_spec		= code_spec != null ? code_spec : '';
				    var classification 	= v.req == 'n' ? v.label_classification : "";
					var spec_desc 		= v.spec_desc != null ? v.spec_desc : v.spec_desc_sap;
					var price_wf 		= v.estimate_price != "" && v.estimate_price != null ? addCommas(v.estimate_price) : "-";
					var price_nf = v.estimate_price != "" && v.estimate_price != null ? v.estimate_price : "-";
					
					t.row.add( [
			            v.gsber,
						tanggal_req,
						tanggal_conf,
			            v.type,
			            v.description,
			            v.uom,
			            price_wf,
			            price_nf,
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
	
    //open modal for req     
	$(document).on("click", "#req_button", function(e){
		// resetForm_use($('.form-transaksi-request-pabrik'));
		// $('#req_modal').modal('show');
		var req		= 'y';
		var all		= 'all';
		var confirm	= 'pabrik';
		$.ajax({
			url: baseURL+'material/transaksi/get/request',
			type: 'POST',
			dataType: 'JSON',
			data: {
				req 	: req,
				all 	: all,
				confirm : confirm
			},
			success: function(data){
				console.log(data);
				var det	= "";
				var count = 0;
				$.each(data, function(i,v){
					count++;
					det	+= 		"<input type='hidden' name='id_item_request_"+count+"' value='"+v.id_item_request+"'>";
					det	+= 		"<tr>";
					det	+= 			"<td align='center'><input type='checkbox' class='checkbox' name='ck_"+count+"' id='ck_"+count+"'></td>";
					det	+= 			"<td>"+v.tanggal+"</td>";
					det	+= 			"<td>"+v.type+"</td>";
					det	+= 			"<td>"+v.description+"</td>";
					det	+= 		"</tr>";
				});
				$("#show_detail").html(det);
				$('#count').val(count);
			},
			complete: function () {
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
	
	//auto complete dari sini
	$(".material").select2({
		dropdownParent: $('#input_modal'),
		allowClear: true,
		placeholder: {
			id: "",
			placeholder: "Leave blank to ..."
		},
		ajax: {
			url: baseURL + 'material/transaksi/get/spec/auto',
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page
				};
			},
			processResults: function (data, page) {
				return {
					results: data.items
				};
			},
			cache: false
		},
		escapeMarkup: function (markup) {
			return markup;
		}, // let our custom formatter work
		minimumInputLength: 3,
		templateResult: function (repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">[' + repo.group_description + '] - [' + repo.name_description + '] - ' + repo.description + '</div>';
			return markup;
		},
		templateSelection: function (repo) {
			if (repo.code && repo.description) {
				return '[' + repo.group_description + '] - [' + repo.name_description + '] - ' + repo.description;
			} else {
				return repo.text;
			}
		}
	});
	$(".material").on('select2:select select2:unselecting change', function (e) {
		var id_item_spec		= "";
		var code 				= "";
		var group_description 	= "";
		var name_description 	= "";
		var spec_description	= "";
		if (typeof e.params !== "undefined" && e.params.data) {
			code 				= e.params.data.code;
			group_description 	= e.params.data.group_description;
			name_description 	= e.params.data.name_description;
			spec_description	= e.params.data.description;
			id_item_spec 		= e.params.data.id_item_spec;
		}
		$("input[name='id_item_spec']").val(id_item_spec);
		$("input[name='code']").val(code);
		$("input[name='group_description']").val(group_description);
		$("input[name='name_description']").val(name_description);
		$("input[name='spec_description']").val(spec_description);
		

	});
	//auto complete sampe sini
	
    $(document).on("change", "#type", function(e){
		var ck = $("#type").val();
   		if(ck=='Barang'){
   			$("#show_gambar").show();
   		} else {
   			$("#show_gambar").hide();
   			$("#gambar").val("");
   		}
    });
	//change
	$(document).on("change", "#id_item_spec", function(){
		var id_item_spec  = $("#id_item_spec").val();
		$.ajax({
    		url: baseURL+'material/transaksi/get/spec',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_spec : id_item_spec
			},
			success: function(data){
				$.each(data, function(i,v){
					$("#id_item_spec").val(v.id_item_spec);
					$("input[name='code']").val(v.code);
					$("input[name='group_description']").val(v.group_description);
					$("input[name='name_description']").val(v.name_description);
				});
			}
		});
    });
	
	//detail
	$(document).on("click", ".detail", function(){
		var id_item_request	= $(this).data("edit");
		var btn_save		= $(this).data("btn_save");
		// alert(btn_save);
		$.ajax({
    		url: baseURL+'material/transaksi/get/input',
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
					if(v.list_gambar!== null){
						$(".gambar").attr('src', v.list_gambar.slice(0, -1));	
					}
					
				});
			},
			complete: function () {
				if(btn_save=='hidden'){
					$("#btn_save").hide();
				}
				$('#detail_modal').modal('show');
			}

		});
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
				id_item_request : id_item_request,
				all 			: "detail"
			},
			success: function(data){
				$(".title-form").html("Form Item Spec");
				$.each(data, function(i,v){
					$("#id_item_request_detail").val(v.id_item_request);
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
						$("#show_images_detail").html(det);						
					}	
					
				});
			},
			complete: function () {
				// if(btn_save=='hidden'){
					$("#btn_save_detail").hide();
					$('#type_detail').prop('disabled', true);
					$('#description_detail').prop('disabled', true);
					$('#uom_detail').prop('disabled', true);
					$('#estimate_price_detail').prop('disabled', true);
					$("#show_images_detail").show();
					$('#gambar_detail').hide();
				// } else {
				// 	$("#btn_save").show();
				// 	$('#type').prop('disabled', false);
				// 	$('#description').prop('disabled', false);
				// 	$('#uom').prop('disabled', false);
				// 	$('#estimate_price').prop('disabled', false);
				// 	$("#show_images").show();
				// 	$('#gambar').show();	
				// }
				$('#gambar_detail').prop('required', false);
				$('#add_modal_detail').modal('show');
			}

		});
    });
	
	//input
	$(document).on("click", ".input", function(){
			var id_item_request	= $(this).data("edit");
		$("input[name='id_item_request']").val(id_item_request);
		$.ajax({
    		url: baseURL+'material/transaksi/get/input',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_request : id_item_request
			},
			success: function(data){
				$(".title-form").html("Form Item Spec");
				$.each(data, function(i,v){
					$("#id_item_request").val(v.id_item_request);
					// $("input[name='id_item_request']").val(v.id_item_request);
					$("select[name='type']").val(v.type).trigger("change");
					$("input[name='description']").val(v.description);
					if(v.list_gambar!== null){
						$(".gambar").attr('src', v.list_gambar.slice(0, -1));	
					}
					
				});
			},
			complete: function () {
				$('#input_modal').modal('show');				
			}

		});
    });
	
	//konfirmasi inventory
	$(document).on("click", ".inventory", function(){
		var id_item_request	= $(this).data("edit");
		$("input[name='id_item_request']").val(id_item_request);
		$.ajax({
    		url: baseURL+'material/transaksi/get/input',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_request : id_item_request
			},
			success: function(data){
				$(".title-form").html("Form Item Spec");
				$.each(data, function(i,v){
					$("#id_item_request").val(v.id_item_request);
					// $("input[name='id_item_request']").val(v.id_item_request);
					$("select[name='type']").val(v.type).trigger("change");
					$("input[name='description']").val(v.description);
					if(v.list_gambar!== null){
						$(".gambar").attr('src', v.list_gambar.slice(0, -1));	
					}
					
				});
			},
			complete: function () {
				$('#inventory_modal').modal('show');				
			}

		});
    });
	
	//add
	$(document).on("click", ".add", function(){
		var id_item_request	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'material/transaksi/get/input',
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
					if(v.list_gambar!== null){
						$(".gambar").attr('src', v.list_gambar.slice(0, -1));	
					}
					
				});
			},
			complete: function () {
				$('#add_modal').modal('show');
				$('.select2modal').select2({
					dropdownParent: $('#add_modal')
				});
				
			}

		});
    });
	
	//tolak
	$(document).on("click", ".tolak", function(){ 
		var id_item_request	= $(this).data("edit");
		var type			= $(this).data("type");
		var description		= $(this).data("description");
		$("input[name='id_item_request']").val(id_item_request); 
		$("input[name='description']").val(description);
		$("input[name='type']").val(type);

		$('#tolak_modal').modal('show');
    });
	//save tolak
	$(document).on("click", "button[name='action_btn_tolak']", function(e){
		var empty_form = validate('.form-transaksi-tolak');
		if(empty_form == 0){
	    	var isproses 	= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-tolak")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'material/transaksi/save/tolak',
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
		var empty_form = validate('.form-transaksi-input');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-input")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'material/transaksi/save/input',
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
	$(document).on("click", "button[name='action_btn_inventory']", function(e){
		var empty_form = validate('.form-transaksi-inventory');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-inventory")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'material/transaksi/save/inventory',
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
	$(document).on("click", "button[name='action_btn_pabrik']", function(e){
		var empty_form = validate('.form-transaksi-request-pabrik');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-request-pabrik")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'material/transaksi/save/request_pabrik',
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
                text: 'Export to Excel',
                title: 'Request Material Code',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,7,8,9,10,11]
                }
            }
        ],
        columnDefs: [
		    {
		        targets: 6,
		        className: 'text_right'
		    },
		    {
		    	targets:7,
		    	"visible": false,
		    }
		],
		scrollX:true
    } );

    //open modal for add     
	$(document).on("click", "#add_button", function(e){
		resetForm_use($('.form-transaksi-request'));
		$('#add_modal').modal('show');
	});
    // //open modal for req     
	// $(document).on("click", "#req_button", function(e){
		// resetForm_use($('.form-transaksi-request-pabrik'));
		// $('#req_modal').modal('show');
	// });
	
	//tanggal
    $('.datePicker').datepicker({ 
    	format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true
    }); 
	
});
function resetForm_use($form) {
	$('#myModalLabel').html("Form Request Material Code");
	$('#pabrik').select2('destroy').find('option').prop('selected', false).end().select2();
	$form.find('input:text, input:password, input:file,  textarea').val("");
	$form.find('select').val(0);
	$form.find('input:radio, input:checkbox')
		 .removeAttr('checked').removeAttr('selected');
	$('#isproses').val("");
	$('#isconvert').val('0');

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
