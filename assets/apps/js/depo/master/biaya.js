$(document).ready(function(){
    //=======FILTER=======//
	$(document).on("change", "#filter_jenis_depo, #filter_jenis_biaya", function(){
		var filter_jenis_depo	= $("#filter_jenis_depo").val();
		var filter_jenis_biaya	= $("#filter_jenis_biaya").val();
		$.ajax({
			// url: baseURL+'material/master/get/item',
			url: baseURL+'depo/master/get/biaya',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	filter_jenis_depo 	: filter_jenis_depo,
	        	filter_jenis_biaya 	: filter_jenis_biaya
	        },
	        success: function(data){
				var output 	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(v.na == 'n'){ 
						output += "				<li><a href='javascript:void(0)' class='edit' data-edit='"+v.id_biaya+"'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
						output += "				<li><a href='javascript:void(0)' class='nonactive' data-nonactive='"+v.id_biaya+"'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
					}
					if(v.na == 'y'){
						output += "				<li><a href='javascript:void(0)' class='setactive' data-setactive='"+v.id_biaya+"'><i class='fa fa-check'></i> Set Aktif</a></li>";	
					}
					output += "				</ul>";
					output += "	        </div>";
					
	        		t.row.add( [
			            v.jenis_depo.toUpperCase(),
			            v.jenis_biaya.toUpperCase(),
			            v.jenis_biaya_detail.toUpperCase(),
			            v.nama.toUpperCase(),
			            v.satuan.toUpperCase(),
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
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "depo/master/set/biaya",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_biaya : $(this).data($(this).attr("class")),	
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

	$(document).on("click", ".edit", function (e) {	
    	var id_biaya	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'depo/master/get/biaya',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_biaya : id_biaya
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Master Biaya");
				$.each(data, function(i, v){
					$("#id_biaya").val(v.id_biaya);
					$("select[name='jenis_depo']").val(v.jenis_depo).trigger("change.select2");
					$("select[name='jenis_biaya']").val(v.jenis_biaya).trigger("change.select2");
					$("select[name='jenis_biaya_detail']").val(v.jenis_biaya_detail).trigger("change.select2");
					$("input[name='nama']").val(v.nama);
					$("input[name='satuan']").val(v.satuan);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-biaya");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-biaya")[0]);

				$.ajax({
					url: baseURL+'depo/master/save/biaya',
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
                title: 'Master Biaya',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            }
        ]
    } );	

});
