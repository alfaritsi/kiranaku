$(document).ready(function(){
	//switch
	$('.switch-onoff').bootstrapToggle({
		on: 'Yes',       
		off: 'No'
	});
	
	//update default change
	$(document).on("change", ".def", function(e){
		var id	= $(this).data("id");
		var def	= $(this).val();
		$.ajax({
			url: baseURL + "material/master/save/default",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id 	: id,
				def : def
			}
		});
		e.preventDefault();
		return false;
    });
	//update default text
	$(document).on("keyup", ".def2", function(e){
		var id	= $(this).data("id");
		var def	= $(this).val();
		$.ajax({
			url: baseURL + "material/master/save/default",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id 	: id,
				def : def
			}
		});
		e.preventDefault();
		return false;
    });

	//update req
	$(document).on("change", "#req", function(e){
		var id		= $(this).data("id");
		var stat 	= $(this).prop('checked');
		$.ajax({
			url: baseURL + "material/master/save/required",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id 	: id,
				stat: stat
			}
		});
		e.preventDefault();
		return false;
    });
	
    //=======FILTER=======//
	$(document).on("change", "#filter_kolom, #filter_mtart, #filter_class", function(){
		var filter_kolom	= $("#filter_kolom").val();
		var filter_mtart	= $("#filter_mtart").val();
		var filter_class	= $("#filter_class").val();
		$.ajax({
			url: baseURL+'material/master/get/matrix',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	filter_kolom	: filter_kolom,
	        	filter_mtart	: filter_mtart,
	        	filter_class 	: filter_class
	        },
	        success: function(data){
				var req 	= "";
				var def 	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					if(v.requir == 'y'){
						req = "<input type='checkbox' class='switch-onoff' name='req' id='req' data-id='"+v.id_item_master_matrix+"' checked>";	
					}else{
						req = "<input type='checkbox' class='switch-onoff' name='req' id='req' data-id='"+v.id_item_master_matrix+"'>";
					}
					if(v.tabel_sap!=null){
						dev =  "<select class='form-control select2 def' name='def' data-id='"+v.id_item_master_matrix+"'>";
						dev += 	"<option value=''>Pilih Set Default</option>";
						$.each(v.arr_default, function (a, b) {
							if(v.def==b.kd){
								dev	+=	"	<option value='"+b.kd+"' selected>["+b.kd+"] "+b.nm+"</option>";
							}else{
								dev	+=	"	<option value='"+b.kd+"'>["+b.kd+"] "+b.nm+"</option>";	
							}
						});								
						dev += "</select>"; 
					}else{
						if(v.kolom=='Conversion'){
							dev = '<td><input value="'+v.def+'" type="number" class="form-control form-control-hide def2" name="def" id="def" placeholder="Set Default" data-id="'+v.id_item_master_matrix+'"></td>';
						}else{
							dev = '<td><input value="'+v.def+'" type="text" class="form-control form-control-hide def2" name="def" id="def" placeholder="Set Default" data-id="'+v.id_item_master_matrix+'"></td>';	
						}
					}
					
	        		t.row.add( [
			            v.kolom,
			            v.mtart+'-'+v.mtbez,
			            v.classification+'-'+v.classification_name,
			            req,
						dev
			        ] ).draw( false );
	        	});
	        },
			complete: function () {
				$('.my-datatable-extends-order').DataTable().destroy();
				$('.switch-onoff').bootstrapToggle({
					on: 'Yes',       
					off: 'No'
				});
				$('.select2').select2();
				$('.my-datatable-extends-order').dataTable();
			}
		});
	});
	
    $("#matrix_button").on("click", function(e){
		$.ajax({
			url: baseURL + "material/master/save/matrix",
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
	
    $("#btn-new").on("click", function(e){
    	location.reload();
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
                title: 'Master Matrix Material Code',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3]
                }
            }
        ]
    } );	

});
