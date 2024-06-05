
$(document).ready(function(){

	$("#filter-btn").click(function() {
		var empty_form = validate(".filter-bpavssample");
        if(empty_form > 0){
        	return false;
		}

	});


    $(".my-datatable").DataTable({
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false
    });

	
});



function filtersubmit(){
    var pabrik = $("#filterpabrik").val();
    var periode = $("#filterperiode").val();

    if(pabrik != "" && periode != ""){
        $('#filterform').submit();
    }
}

