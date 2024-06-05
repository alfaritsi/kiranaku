$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(document).on("change", "#description", function(){
		var description = $("#description").val();
		$.ajax({
    		url: baseURL+'material/master/get/item',
			type: 'POST',
			dataType: 'JSON',
			data: {
				description : description
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Item Group");
				$.each(data, function(i, v){
					$("select[name='id_item_group']").val(v.id_item_group).trigger('change');
					$("input[name='id_item_name']").val(v.id_item_name);
					$("input[name='description']").val(v.description);
					$("select[name='bklas']").val(v.bklas).trigger('change');
					$("select[name='matkl']").val(v.matkl).trigger('change');
					$("select[name='classification']").val(v.classification).trigger('change');
					$("#btn-new").removeClass("hidden");
				});
			}
		});
	});		
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
					$("select[name='id_item_group']").val(v.id_item_group).trigger('change');
					$("input[name='id_item_name']").val(v.id_item_name);
					$("input[name='description']").val(v.description);
					$("select[name='bklas']").val(v.bklas).trigger('change');
					$("select[name='matkl']").val(v.matkl).trigger('change');
					$("select[name='classification']").val(v.classification).trigger('change');
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
                    columns: [0,1,2,3,4,5,6]
                }
            }
        ]
    } );	

});