
$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	// $(".set_active-jenis").on("click", function(e){
 //    	var id_mjenis	= $(this).data("activate");
 //    	$.ajax({
 //    		url: baseURL+'she/limbah_air/set_data/activate/parameter',
	// 		type: 'POST',
	// 		dataType: 'JSON',
	// 		data: {
	// 			id_mjenis : id_mjenis
	// 		},
	// 		success: function(data){
 //                if(data.sts == 'OK'){
 //                    swal('Success',data.msg,'success').then(function(){
 //                        location.reload();
 //                    });
 //                }else{
 //                    $("input[name='isproses']").val(0);
 //                    swal('Error',data.msg,'error');
 //                }
	// 		}
	// 	});
 //    });

	$(".delete").on("click", function(e){
    	var id	= $(this).data("delete");

        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/transaction/set_data/delete_del0/deletelimbahB3',
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

	$(".edit").on("click", function(e){
    	var id	= $(this).data("edit");
    	$.ajax({
    		url: baseURL+'she/transaction/get_data/editperpanjang_masa_b3',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id : id
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Parameter");
				$("#custom-table").html("");
				$.each(data, function(i, v){
					document.getElementById("pabrik").value = v.fk_pabrik;
					$('.select2').select2()
					$("#tgllimbahmasuk").val(v.tanggal_masuk);
					$("#tglmaxsimpan").val(v.dmasasimpan_max);
					$("#tglmaxsimpanbaru").val(v.dsimpan_max);
					$("#masaperpanjangan").val(v.ext_days);
			        $("#custom-table").append("<tr>"
	                                 +"<td>"
	                                 +"<label><input type='checkbox' class='limbahchkbox' name='chklimbah' id='chklimbah' value='"+ v.fk_limbah + "|" + v.stok + "' checked disabled> &nbsp;"+ v.jenis_limbah +" | "+ v.kode_material +"</label>"
	                                 +"</td>"     
	                                 +"<td align='center'>"+ v.stok +"</td>"     
	                                 +"<td align='center'>"+ v.satuan +"</td>"     
	                                 +"</tr>");  

					$("#id").val(v.id);
					$("#btn-new").removeClass("hidden");
				});

				$('#pabrik').attr('disabled', true);
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-perpanjangmasaB3");
        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
	    		$('#pabrik').attr('disabled', false);
	    		$('#chklimbah').attr('disabled', false);
		    	var formData = new FormData($(".form-perpanjangmasaB3")[0]);

				$.ajax({
					url: baseURL+'she/transaction/save/perpanjang_masa_b3',
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
					}
				});
			}else{
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
				$('#pabrik').attr('disabled', true);
				$('#chklimbah').attr('disabled', true);
			}
		}
		e.preventDefault();
		return false;
    });

    $(".my-datatable").DataTable({
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false
    });

    $("#adddata").click(function() {
        resetform();
        $('#pabrik').attr('disabled', false);
		var formData = new FormData($(".form-perpanjangmasaB3")[0]);      
		$.ajax({
			url: baseURL+'she/transaction/get_data/addperpanjang_masa_b3',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function(data){
				$("#custom-table").html("");
				$.each(data, function(i, v){
					// alert();
					if(v.extend != null){		
						var disable = "disabled" 				
						var small = "<small> (sudah diextend)</small>" 				
				    }else{
				    	var disable = "" 				
						var small = "" 				
				    }
			        $("#custom-table").append("<tr>"
	                                 +"<td>"
	                                 +"<label><input type='checkbox'"+ disable +" class='limbahchkbox' name='chklimbah' id='chklimbah' value='"+ v.id + "|" + v.batch + "|" + v.stock + "|" + v.tanggal_masuk + "|" + v.dsimpan_max +"' onclick='limbahchkbox(this)'> &nbsp;"+ v.limbah + " | "+ v.kode_material +"</label>"+ small
	                                 +"</td>"     
	                                 +"<td align='center'>"+ v.stock +"</td>"     
	                                 +"<td align='center'>"+ v.satuan +"</td>"     
	                                 +"</tr>");  
				});
				$('#pabrik').attr('disabled', true);
			}
		});
    });

    $("#masaperpanjangan").keyup(function() {
        var tglmaxsimpan = $("#tglmaxsimpan").val();
        var masasimpan = $("#masaperpanjangan").val();

        if(masasimpan == ""){
        	var masasimpan = 0;	
        }else{
        	var masasimpan = $("#masaperpanjangan").val();
        }
        
	 //    var date = new Date(tglmaxsimpan);
	 //    alert(date);
	 //    var newdate = new Date(date);

	 //    newdate.setDate(newdate.getDate() + parseInt(masasimpan));
	    
	 //    var dd = newdate.getDate();
	 //    var mm = newdate.getMonth() + 1;
	 //    var y = newdate.getFullYear();

	 //    var someFormattedDate = dd + '.' + mm + '.' + y;
	 //    var dateObj = new Date(someFormattedDate);
		// var dateStr = String(dateObj.getDate()).padStart(2, "0") + "." + String(dateObj.getMonth() + 1).padStart(2, "0") + "." + dateObj.getFullYear();

		var dateStr = moment(tglmaxsimpan,"DD.MM.YYYY").add(masasimpan,"days").format("DD.MM.YYYY");
	    $("#tglmaxsimpanbaru").val(dateStr);

    });

    // //date pitcker
    // $('.datePicker').datepicker({
    //     format: 'dd.mm.yyyy',
    //     changeMonth: true,
    //     changeYear: true,
    //     autoclose: true,
    //     // startDate: new Date()
    // });

});

function limbahchkbox(x) {
	// alert(x.checked);
    $(".limbahchkbox").prop('checked',false);
    // x.prop('checked',true);
    x.checked = true;
	// alert(x.value);
    // $('#pabrik').attr('disabled', false);
    var value 		= x.value;
	var valuesplit 	= value.split('|');
	var limbah     	= valuesplit[0];
	var batch      	= valuesplit[1];
	var stok       	= valuesplit[2];
	var tgltx      	= moment(valuesplit[3],"YYYY-MM-DD").format("DD.MM.YYYY");
	var dmax       	= moment(valuesplit[4],"YYYY-MM-DD").format("DD.MM.YYYY");
	// alert(moment(valuesplit[3]).format("YYYY-MM-DD"));
	
	$("#tgllimbahmasuk").val(tgltx);
	$("#tglmaxsimpan").val(dmax);

	$("#masaperpanjangan").keyup();

	// var formData = new FormData($(".form-perpanjangmasaB3")[0]);      	
	// $.ajax({
	// 	url: baseURL+'she/transaction/get_data/perpanjang_masasimpan_b3',
	// 	type: 'POST',
	// 	dataType: 'JSON',
	// 	data: formData,
	// 	contentType: false,
	// 	cache: false,
	// 	processData: false,
	// 	success: function(data){
	// 		$.each(data, function(i, v){
	// 			// alert();
	// 			$("#tgllimbahmasuk").val(v.tanggal_masuk);
	// 			$("#tglmaxsimpan").val(v.dsimpan_max);

	// 		});
	// 		$('#pabrik').attr('disabled', true);
	// 		$("#masaperpanjangan").keyup();
	// 	}
	// });
}

function resetform() {
	$("#tgllimbahmasuk").val("");
	$("#tglmaxsimpan").val("");
	$("#masaperpanjangan").val("");
	$("#tglmaxsimpanbaru").val("");
	$("#custom-table").html("");

}

