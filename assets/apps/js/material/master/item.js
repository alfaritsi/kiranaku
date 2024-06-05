$(document).ready(function(){
    //open modal for imp    
	$(document).on("click", "#imp_button", function(e){
		resetForm_use($('.form-master-item-imp'));
		$('#imp_modal').modal('show');
	});
	//imp
	$(document).on("click", "button[name='action_btn_imp']", function(e){
		var empty_form = validate('.form-master-item-imp');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-item-imp")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'material/master/save/excel_item',
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
	
    //=======FILTER=======//
	$(document).on("change", "#id_item_group_filter", function(){
		var id_item_group_filter	= $("#id_item_group_filter").val();
		$.ajax({
			url: baseURL+'material/master/get/item',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	id_item_group_filter 	: id_item_group_filter
	        },
	        success: function(data){
				var output 	= "";
	        	var desc	= "";
				var bkbez	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					bkbez	= (v.bklas!=0)?v.bklas+"-"+v.bkbez:"-";
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(v.jumlah == 0){
						if((v.na == 'n')&&(v.req == 'n')){ 
							output += "				<li><a href='javascript:void(0)' class='nonactive' data-nonactive='"+v.id_item_name+"'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
						}
						if((v.na == 'n')&&(v.req == 'y')){ 
							output += "				<li><a href='javascript:void(0)' class='edit' data-edit='"+v.id_item_name+"'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
							output += "				<li><a href='javascript:void(0)' class='nonactive' data-nonactive='"+v.id_item_name+"'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
						}
						if(v.na == 'y'){
							output += "				<li><a href='javascript:void(0)' class='setactive' data-setactive='"+v.id_item_name+"'><i class='fa fa-check'></i> Set Aktif</a></li>";	
						}
					}
					output += "				</ul>";
					output += "	        </div>";
					
	        		t.row.add( [
			            v.id_item_group+'-'+v.group_description,
			            v.code,
			            v.description.toUpperCase(),
			            bkbez,
			            v.matkl+'-'+v.wgbez,
			            v.classification_name,
			            v.label_jasa,
			            v.label_req,
			            v.label_active,
			            output
			        ] ).draw( false );
	        	});
			
	        }
		});
	});
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	// $(document).on("change", "#description", function(){
		// var description = $("#description").val();
		// $.ajax({
    		// url: baseURL+'material/master/get/item',
			// type: 'POST',
			// dataType: 'JSON',
			// data: {
				// description : description
			// },
			// success: function(data){
				// console.log(data);
				// $(".title-form").html("Form Item Group");
				// $.each(data, function(i, v){
					// $("select[name='id_item_group']").val(v.id_item_group).trigger('change');
					// $("input[name='id_item_name']").val(v.id_item_name);
					// $("input[name='description']").val(v.description);
					// $("select[name='bklas']").val(v.bklas).trigger('change');
					// $("select[name='matkl']").val(v.matkl).trigger('change');
					// $("select[name='classification']").val(v.classification).trigger('change');
					// $("#btn-new").removeClass("hidden");
				// });
			// }
		// });
	// });		
	
	//onchange
	//set on change id_item_group for get item
    $(document).on("change", "#id_item_group", function(e){
		
		var id_item_group	= $(this).val();
		var id_item_name	= $("#id_item_name").val();
		if(id_item_name==''){
			$.ajax({
				url: baseURL+'material/master/get/bklas',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_item_group	: id_item_group
				},
				success: function(data){
					var count = 0;
					var value = '';
					value += '<option value="0">Pilih Valuation Class</option>';
					$.each(data, function(i,v){
						count++;
						value += '<option value="'+v.bklas+'">'+v.bklas+'-'+v.bkbez+'</option>';
					});
					$('#bklas').html(value);
					if(count==0){
						$('#bklas').prop('required', false);	
					}else{
						$('#bklas').prop('required', true);	
					}
				},
				complete: function () {
					//xx
					if(id_item_group=='004'){
						var value 	 = "";
							value	+= "<option value='IE' selected>[IE] Inventory Expense</option>";
						$('#classification').html(value);
						$('#classification').prop('required', true);	
						
					}else{
						var value 	 = "";
							value	+= "<option value='0'>Pilih Classification</option>";
							value	+= "<option value='A'>[A] Asset</option>";
							value	+= "<option value='E'>[E] Expense</option>";
							value	+= "<option value='I'>[I] Inventory</option>";
							value	+= "<option value='IE' selected>[IE] Inventory Expense</option>";
						$('#classification').html(value);
						// $("select[name='classification']").val('IE').trigger('change');
						$('#classification').prop('required', true);	
						
					}
				}
			});
		}
    });
	//set on change id_item_group for get nomor
    $(document).on("change", "#id_item_group", function(e){
		var id_item_group	= $("#id_item_group").val();
		// alert(id_item_name);
		$.ajax({
    		url: baseURL+'material/master/get/nomor',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_group 	: id_item_group
			},
			success: function(data){
				$.each(data, function(i,v){
					if((id_item_group!=0)&&(id_item_name!=0)){
						$("#code").val(v.nomor);
						$("input[name='code']").val(v.nomor);	
					}
				});
			},
			complete: function () {
				//xx
				if(id_item_group=='004'){
					var value 	 = "";
						value	+= "<option value='IE' selected>[IE] Inventory Expense</option>";
					$('#classification').html(value);
					$('#classification').prop('required', true);	
					
				}else{
					var value 	 = "";
						value	+= "<option value='0'>Pilih Classification</option>";
						value	+= "<option value='A'>[A] Asset</option>";
						value	+= "<option value='E'>[E] Expense</option>";
						value	+= "<option value='I'>[I] Inventory</option>";
						value	+= "<option value='IE' selected>[IE] Inventory Expense</option>";
					$('#classification').html(value);
					// $("select[name='classification']").val('IE').trigger('change');
					$('#classification').prop('required', true);	
					
				}
			}
		});
    });
	// // //set on change id_item_group for set clasification
    // // $(document).on("change", "#id_item_group", function(e){
		// // var id_item_group	= $("#id_item_group").val();
		// // $.ajax({
    		// // url: baseURL+'material/master/get/group',
			// // type: 'POST',
			// // dataType: 'JSON',
			// // data: {
				// // id_item_group 	: id_item_group
			// // },
			// // success: function(data){
				// // $.each(data, function(i,v){
					// // if(v.mtart=='ZNOV'){
						// // var value 	 = "";
							// // value	+= "<option value='E'>[E] Expense</option>";
						// // $('#classification').html(value);
						// // $('#classification').prop('required', true);	
						
					// // }else{
						// // var value 	 = "";
							// // value	+= "<option value='0'>Pilih Classification</option>";
							// // value	+= "<option value='A'>[A] Asset</option>";
							// // value	+= "<option value='E'>[E] Expense</option>";
							// // value	+= "<option value='I'>[I] Inventory</option>";
							// // value	+= "<option value='IE' selected>[IE] Inventory Expense</option>";
						// // $('#classification').html(value);
						// // // $("select[name='classification']").val('IE').trigger('change');
						// // $('#classification').prop('required', true);	
						
					// // }
				// // });
			// // }
		// // });
    // // });
	
	//set aktif
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "material/master/set/item",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_name 	: $(this).data($(this).attr("class")),	
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

	$(".edit").on("click", function(e){
    	var id_item_name	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'material/master/get/item',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_item_name : id_item_name
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Item");
				$.each(data, function(i, v){
					$("input[name='bklas']").val(v.bklas);
					$("input[name='id_item_name']").val(v.id_item_name);
					$("input[name='code']").val(v.code);
					$("input[name='description']").val(v.description);
					// $("select[name='id_item_group']").val(v.id_item_group).trigger('change');
					// $("select[name='bklas']").val(v.bklas).trigger('change');
					//load item_group
					get_data_group(v.id_item_group);
					//load id_item_name
					var output = '';
					$.each(v.arr_bklas, function (x, y) {
						var selected = (y.bklas == v.bklas ? 'selected' : '');
						output += '<option value="' + y.bklas + '" '+selected+'>'+y.bklas+'-'+y.bkbez+'</option>';
					});
					$("select[name='bklas']").html(output).select2();
					
					$("select[name='matkl']").val(v.matkl).trigger('change');
					$("select[name='classification']").val(v.classification).trigger('change');
					$("select[name='jasa']").val(v.jasa).trigger('change');
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-item")[0]);

				$.ajax({
					url: baseURL+'material/master/save/item',
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
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Item Name',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7]
                }
            }
        ]
    } );	

});


function get_data_group(id_item_group) {
	$.ajax({
		url: baseURL + 'material/master/get/group',
		type: 'POST',
		dataType: 'JSON',
		success: function (data) {
			if (data) {
				var output = '';
				$.each(data, function (i, v) {
					output += '<option value="' + v.id_item_group + '">' + v.description + '</option>';
				});
				$("select[name='id_item_group']").html(output);
			}
		},
		complete: function () {
			if (id_item_group) {
				$("select[name='id_item_group']").val(id_item_group).trigger("change.select2");
			}
		}
	});
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

