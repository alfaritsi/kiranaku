
$(document).ready(function(){

	$("#filter-btn").click(function() {
		var empty_form = validate(".filter-neracalimbahb3");
        if(empty_form > 0){
        	return false;
		}

	});

    //date pitcker
    $('.monthPicker').datepicker({
        startView: 'year',
        minViewMode: "years",
        format: 'yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });
	
});


function filtersubmit(){
    var pabrik = $("#filterpabrik").val();
    var periode = $("#filterperiode").val();
    var tahun = $("#filtertahun").val();
    
    if(pabrik != "" && periode != "" && tahun != ""){
        $('#filterform').submit();
    }
}

