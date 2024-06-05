
$(document).ready(function(){

	$("#filter-btn").click(function() {
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

    // $(".my-datatable").DataTable({
    //     ordering : true,
    //     scrollCollapse: true,
    //     scrollY: false,
    //     scrollX : true,
    //     bautoWidth: false
    // });
	
});


function filtersubmit(){
    var pabrik = $("#filterpabrik").val();
    var kategori = $("#filterkategori").val();
    var from = $("#from").val();
    var to = $("#to").val();
    var parameter = $("#parameter").val();
	
    
    if(pabrik != "" && from != "" && to != "" && kategori != ""){
        $('#filterform').submit();
    }
    if(parameter != ""){
        $('#filterform').submit();
    }
}
