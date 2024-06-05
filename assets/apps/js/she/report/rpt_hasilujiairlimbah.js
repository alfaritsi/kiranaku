
$(document).ready(function(){
	//export to excel
    $(document).on('click', '#excel_button', function (e) {
        e.preventDefault();
        window.open(
            baseURL + 'she/report/excel/hasil_uji_air_bulanan/'
            +'?filterpabrik='+$("#filterpabrik").val()
            +'&from='+$('#from').val()
            +'&to='+$('#to').val()
            +'&filterkategori='+$('#filterkategori').val()
        );
    })
	
// 	$(document).on("click", "button[name='filteraction_btn']", function(e){
// 		$("#table_body").html("");

// 		var empty_form = validate(".filter-airlimbah_harian");
//         if(empty_form == 0){
// 	    	var formData = new FormData($(".filter-airlimbah_harian")[0]);

// 			$.ajax({
// 				url: baseURL+'she/report/get_data/hasil_uji_airlimbah',
// 				type: 'POST',
// 				dataType: 'JSON',
// 				data: formData,
// 				contentType: false,
// 				cache: false,
// 				processData: false,
// 				success: function(data){
// 					$.each(data, function(i, v){

//                  		if(v.na === null){
//                  			var action = "<li><a href='#' class='edit' data-edit='"+v.id+"' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
//                           	+"<li><a href='#' class='delete' data-delete='"+v.id+"'><i class='fa fa-trash-o'></i> Hapus</a></li>"
//                  		}else{
//                  			var action = "<li><a href='#' class='set_active-kategori' data-activate='"+v.id+"'><i class='fa fa-check'></i> Set Aktif</a></li>"
//                  		}

// 				        $("#table_body").append("<tr>"
// 		                                 +"<td>"+v.PARAMETER+"</td>"     
// 		                                 +"<td>"+v.PH_MIN+"</td>"     
// 		                                 +"<td>"+v.PH_MAX+"</td>"     
// 		                                 +"<td>"+v.PH_HASIL+"</td>"     
// 		                                 +"<td>"+v.COD_MUTU+"</td>"     
// 		                                 +"<td>"+v.COD_HASIL+"</td>"     
// 		                                 +"<td>"+v.BOD_MUTU+"</td>"     
// 		                                 +"<td>"+v.BOD_HASIL+"</td>"     
// 		                                 +"<td>"+v.TSS_MUTU+"</td>"     
// 		                                 +"<td>"+v.TSS_HASIL+"</td>"     
// 		                                 +"<td>"+v.Ammonia_MUTU+"</td>"     
// 		                                 +"<td>"+v.Ammonia_HASIL+"</td>"     
// 		                                 +"<td>"+v.Ammonia_MUTU+"</td>"     
// 		                                 +"<td>"+v.Ammonia_HASIL+"</td>"     
// 		                                 // +"<td></td>"     
// 		                                 +"</tr>");  

// 					});
// 				}
// 			});

// 		}
// 		e.preventDefault();
// 		return false;
//     });	

	$("#filter-btn").click(function() {
		// var pabrik = $('#filterpabrik').val();
		// var from = $('#from').val();
		// var to = $('#to').val();
		// if(pabrik === '' || from === '' || to === ''){
		// 	alert("Mohon untuk mengisi parameter dengan lengkap dan benar");
		// 	return false;
		// }


		var empty_form = validate(".filter-airlimbah_harian");
        if(empty_form > 0){
        	return false;
		}

	});


    //date pitcker
    $('.monthPicker').datepicker({
        startView: 'year',
        minViewMode: "months",
        format: 'mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });

    $('.datePicker').datepicker({
        format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });

	//export to excel
	$('#excel').DataTable( {
		destroy: true,
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Report SHU Air Limbah Bulanan',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13]
                }
            }
        ]
    } );	

    $(".my-datatable-extends-order").DataTable({
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,

        order: [[0, 'asc']],
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true
    });

	
});


function filtersubmit(){
    var pabrik = $("#filterpabrik").val();
    var kategori = $("#filterkategori").val();
    var from = $("#from").val();
    var to = $("#to").val();
    // console.log(pabrik , from , to , kategori);
    if(pabrik != "" && from != "" && to != "" && kategori != ""){
        $('#filterform').submit();
    }
    
}
