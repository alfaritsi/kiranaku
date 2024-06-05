$(document).ready(function(){
    $(document).on("change", "#tipe", function(e){
		var tipe = $("#tipe").val();
   		if(tipe=='Approver'){
   			$("#show_posisi").hide();
   			$("#show_divisi").show();
   			$("#show_seksi").show();
			$('#posisi').prop('required', false);
			$('#divisi').prop('required', true);
			$('#seksi').prop('required', true);
   		}
   		if(tipe=='Requestor'){
   			$("#show_posisi").show();
   			$("#show_divisi").hide();
   			$("#show_seksi").hide();
			$('#posisi').prop('required', true);
			$('#divisi').prop('required', false);
			$('#seksi').prop('required', false);
   		}
    });
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "settings/mapping/set/plant_header",
			type: 'POST',
			dataType: 'JSON',
			data: {
				apps 	: $(this).data($(this).attr("class")),	
				type 	: $(this).attr("class")
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
    	var apps	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'settings/mapping/get/plant_header',
			type: 'POST',
			dataType: 'JSON',
			data: {
				apps : apps
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Mapping Plant Header");
				$.each(data, function(i, v){
					$("input[name='apps']").val(v.apps);
					if(v.plant_exclude!==null){
						var plant	= v.plant_exclude.split(",");
						$("select[name='plant_exclude[]']").val(plant).trigger("change");
					}
					$("#btn-new").removeClass("hidden");
				});
				$('#apps').prop('readonly', true);
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-mapping-header")[0]);

				$.ajax({
					url: baseURL+'settings/mapping/save/plant_header',
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
                title: 'Mapping Plant Exclude',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2]
                }
            }
        ]
    } );	

});