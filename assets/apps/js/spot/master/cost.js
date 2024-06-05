$(document).ready(function(){
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-production-cost")[0]);

				$.ajax({
					url: baseURL+'spot/master/save/cost',
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
	//history
	$(document).on("click", ".history", function(){
		var werks	= $(this).data("edit");
		var plant	= $(this).data("plant");
		$.ajax({
    		url: baseURL+'spot/master/get/history',
			type: 'POST',
			dataType: 'JSON',
			data: {
				werks : werks
			},
			success: function(data){
				console.log(data);
				$(".modal-title").html("<b>Historical Production Cost "+plant+"</b>");
				var no  = 0; 
				var nil	= "<table class='table table-bordered table-striped table-modals'>";
					nil	 	+= "<thead>";
					nil	 	+= 		"<tr>";
					nil	 	+= 			"<th>No</th><th>Plant</th><th>Factory</th><th>Prod. Cost</th><th>Notes</th><th>Last Update</th>";
					nil	 	+= 		"</tr>";
					nil	 	+= "</thead>";
					nil	 	+= "<tbody>";
					$.each(data, function(i,v){
						no = no+1;
						nil	 	+= 		"<tr>";
						nil	 	+= 			"<td>"+no+"</td><td>"+v.werks+"</td><td>"+v.tppco+"</td><td>"+numberWithCommas(v.cost)+"</td><td>"+v.note+"</td><td>"+v.nama_karyawan+" ("+v.nik+")<br>"+v.tanggal_input+"</td>";
						nil	 	+= 		"</tr>";
					});
					nil	 	+= "</tbody>";
					$("#data_history").html(nil);
			},
			complete: function () {
				var t = $('.table-modals').DataTable({
					order: [[0, 'asc']],
					lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
					scrollX: true
				});
                setTimeout(function () {
                    $("table.dataTable").DataTable().columns.adjust();
                }, 1500);				
				$('#show_history').modal('show');
			}

		});
    });
	
	// //export to excel
	// $('.my-datatable-extends-order').DataTable( {
		// paging:   false,
		// bInfo: false,
        // ordering : true,
        // scrollCollapse: true,
        // scrollY: false,
        // scrollX : true,
        // bautoWidth: false,
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 20,
		// dom: 'Bfrtip',
        // buttons: [
            // {
                // extend: 'excelHtml5',
                // text: 'Export to Excel',
                // title: 'Production Cost',
                // download: 'open',
                // orientation:'landscape',
                // exportOptions: {
                    // columns: [0,1,2,3],
					// format: {
						// body: function ( data, row, column, node ) {
							// console.log(node.firstChild);
							// //check if type is input using jquery
							// return node.firstChild.tagName === "INPUT" ?
									// node.firstElementChild.value :
									// data;							
						// }
					// }
                // }
            // }
        // ]
    // } );	

});