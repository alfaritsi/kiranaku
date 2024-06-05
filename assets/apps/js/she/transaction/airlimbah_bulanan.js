
$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

    $(".my-datatable-extends-order").DataTable({
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        order: [[0, 'asc'],[1, 'asc']],
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
		paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true
    });

    // $(".my-datatable-extends-order-no-collapse").DataTable({
    // 	ordering : true,
    //     scrollX : true
    // });

    // $(".my-datatable-order-col2").DataTable({
    // 	order: [[1, 'asc']],
    //     scrollX : true
    // });

	$(".set_active-jenis").on("click", function(e){
    	var id_mjenis	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'she/limbah_air/set_data/activate/parameter',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mjenis : id_mjenis
			},
			success: function(data){
				if(data.sts == 'OK'){
                    kiranaAlert(data.sts, data.msg);
				}else{
                    kiranaAlert(data.sts, data.msg, "error", "no");
				}
			}
		});
    });

	$(".delete").on("click", function(e){
    	var id	= $(this).data("delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/transaction/set_data/delete_del0/limbah_air_bulanan',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id : id
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );
    });

	$(document).on("click", ".edit", function(e){
		// $(".edit").on("click", function(e){
    	var id	= $(this).data("edit");

    	$.ajax({
    		url: baseURL+'she/transaction/get_data/limbah_air_bulanan',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Parameter");
			    $("#table_param").html("");
				$.each(data, function(i, v){
					document.getElementById("pabrik").value = v.fk_pabrik;
					document.getElementById("lokasi").value = v.fk_jenis;
					document.getElementById("kategori").value = v.fk_kategori;
					$('.select2').select2()
					$("#tgl_sampling").val(v.tanggal_sampling);
					$("#tgl_analisa").val(v.tanggal_analisa);

			        $("#table_param").append("<tr>"
                                 +"<td><input style='width:100%;height:32px;padding:10px;' value='"+v.parameter+"' type=text class=input-sm name=parameter id=parameter readonly/><input type=hidden value='"+v.fk_parameter+"' name=idparam id=idparam></td>"     
                                 +"<td><input style='width:100%;height:32px;padding:10px;text-align:right;' value='"+v.hasil_uji+"' type=text class=input-sm name=hasil_uji id=hasil_uji/></td>" 
                                  +"</tr>");  


					$("#id").val(v.id);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate('.form-airlimbah_bulanan');
        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-airlimbah_bulanan")[0]);

				$.ajax({
					url: baseURL+'she/transaction/save/limbah_air_bulanan',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						if(data.sts == 'OK'){
			                kiranaAlert(data.sts, data.msg);
						}else{
			                kiranaAlert(data.sts, data.msg, "error", "no");
						}
					},
					error : function(){
                        kiranaAlert("notOK", "Server Error", "error", "no");
                        $("input[name='isproses']").val(0);
					},
					complete : function(){
                        $("input[name='isproses']").val(0);
					}
				});
			}else{
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
    });

	$("#lokasi, #pabrik, #kategori").change(function() {
		var pabrik 		= $("#pabrik").val();
		var jenis 		= $("#lokasi").val();
		var kategori 	= $("#kategori").val();
		$("#table_param").html("");

		if(pabrik === "" || jenis === "" || kategori === ""){
			return true;
		}
	  	$.ajax({
	  		url: baseURL+'she/transaction/get_data/get_limbah_air_bulanan_parameter',
			type: 'POST',
			dataType: 'JSON',
			data: {
				pabrik 		: pabrik,
				jenis 		: jenis,
				kategori 	: kategori,
			},
			success: function(data){
				// console.log(data);
				// $(".title-form").html("Edit Parameter");
				$.each(data, function(i, v){
			        $("#table_param").append("<tr>"
	                                 +"<td><input style='width:100%;height:32px;padding:10px;' value='"+v.data+"' type=text class=input-sm name=parameter[] id=parameter"+i+" readonly/><input type=hidden value='"+v.idparam+"' name=idparam[] id=idparam"+i+"></td>"     
	                                 +"<td><input style='width:100%;height:32px;padding:10px;text-align:right;' type=number class=input-sm name=hasil_uji[] id=hasil_uji"+i+"/></td>" 
	                                  +"</tr>");  

					// $("#id").val(v.id);

					// $("input[name='id_mjenis']").val(v.id_mjenis);
					// $("#btn-new").removeClass("hidden");
				});
			}
		});
	});

    //date pitcker
    $('.datePicker').datepicker({
        format: 'yyyy-mm-dd',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });
	//range date
    $(document).on("change", "#tgl_sampling", function(e){
        $('#tgl_analisa').val("");
        var akhir = $(this).val();
        $("#div_tgl_analisa").html("");
        $("#div_tgl_analisa").html('<input type="text" name="tgl_analisa" id="tgl_analisa" class="datePicker" style="width:100%;height:32px;padding:10px;" readonly required>');

        $('#tgl_analisa').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate: akhir
        });
    });
	
	//export to excel
    $(document).on('click', '#excel_button', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'she/transaction/excel/limbah_air_bulanan/'
        );
    })	
    //open modal for imp    
	$(document).on("click", "#imp_button", function(e){
		$('#imp_modal').modal('show');
	});
	//imp
	$(document).on("click", "button[name='action_btn_imp']", function(e){
		var empty_form = validate('.form-transaksi-bulanan-imp');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-bulanan-imp")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'she/transaction/save/import_bulanan',
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

    $("#add_button").on("click", function(e){
    	document.getElementById("pabrik").value = "";
		document.getElementById("lokasi").value = "";
		$('.select2').select2()
		$("#tgl_sampling").val("");
		$("#tgl_analisa").val("");

        $("#table_param").html("");
                     
		$("#id").val("");
		// $("#btn-new").removeClass("hidden");
    	// var id	= $(this).data("delete");
     	//    kiranaConfirm(
		//        {
		//            title: "Konfirmasi",
		//            text: "Apakah anda akan menghapus data?",
		//            dangerMode: true,
		//            successCallback: function () {
			  //   	$.ajax({
			  //   		url: baseURL+'she/transaction/set_data/delete_del0/limbah_air_bulanan',
					// 	type: 'POST',
					// 	dataType: 'JSON',
					// 	data: {
					// 		id : id
					// 	},
					// 	success: function(data){
					// 		if(data.sts == 'OK'){
			  //                   kiranaAlert(data.sts, data.msg);
					// 		}else{
			  //                   kiranaAlert(data.sts, data.msg, "error", "no");
					// 		}
					// 	}
					// });
		//            }
		//        }
     	//    );
    });
	
	
});
