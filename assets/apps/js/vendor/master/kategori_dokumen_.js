$(document).ready(function(){
    //=======FILTER=======//
	$(document).on("change", "#id_kategori_filter", function(){
		var id_kategori_filter	= $("#id_kategori_filter").val();
		$.ajax({
			url: baseURL+'vendor/master/get/kategori_dokumen',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	        	id_kategori_filter 	: id_kategori_filter
	        },
	        success: function(data){
				var output 	= "";
	        	var desc	= "";
				var bkbez	= "";
	        	var t 	= $('.my-datatable-extends-order').DataTable();
	        	t.clear().draw();
	        	$.each(data, function(i,v){
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
												if(v.na == 'n'){ 
													output += "<li><a href='javascript:void(0)' class='edit' data-edit='"+v.id_kategori_dokumen+"'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
													output += "<li><a href='javascript:void(0)' class='nonactive' data-nonactive='"+v.id_kategori_dokumen+"'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
												}
												if(v.na == 'y'){
													output += "<li><a href='javascript:void(0)' class='setactive' data-setactive='"+v.id_kategori_dokumen+"'><i class='fa fa-check'></i> Set Aktif</a></li>";
												}
					output += "				</ul>";
					output += "	        </div>";
					
	        		t.row.add( [
			            v.nama_kategori,
			            v.nama,
			            v.label_mandatory,
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
	//set aktif
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "vendor/master/set/kategori_dokumen",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_kategori_dokumen : $(this).data($(this).attr("class")),	
				type 	  	 		: $(this).attr("class")
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
    	var id_kategori_dokumen	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'vendor/master/get/kategori_dokumen',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_kategori_dokumen : id_kategori_dokumen
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form Item");
				$.each(data, function(i, v){
					$("#id_kategori_dokumen").val(v.id_kategori_dokumen);
					$("input[name='nama']").val(v.nama);
					$("select[name='id_kategori']").val(v.id_kategori).trigger('change');
					$("select[name='mandatory']").val(v.mandatory).trigger('change');
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-kategori_dokumen");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-kategori_dokumen")[0]);

				$.ajax({
					url: baseURL+'vendor/master/save/kategori_dokumen',
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
                title: 'Master Kategori Vendor Dokumen',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3]
                }
            }
        ]
    } );	

});
