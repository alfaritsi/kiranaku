
$(document).ready(function(){

	$("#filter-btn").click(function() {
		var empty_form = validate(".filter-logbookb3");
        if(empty_form > 0){
        	return false;
		}

	});

    $("#filterpabrik").change(function() {
        var pabrik = $(this).val();
        var formData = new FormData($(".filter-logbookb3")[0]);     
        $.ajax({
            url: baseURL+'she/report/get_data/getlimbah',
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data){
                $("#filterlimbah").html("");
                $("#filterlimbah").append("<option value=''> Silahkan Pilih</option>");  
                $.each(data, function(i, v){
                    $("#filterlimbah").append("<option value='"+v.id+"'>"+v.jenis_limbah+"</option>");  
                });
            }
        });

    });

    // date pitcker
    $('.monthPicker').datepicker({
        startView: 'year',
        minViewMode: "months",
        format: 'mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });

});


function filtersubmit(){
    var pabrik = $("#filterpabrik").val();
    var limbah = $("#filterlimbah").val();
    var from = $("#filterfrom").val();
    var to = $("#filterto").val();
    
    if(pabrik != "" && limbah != "" && from != "" && to != ""){
        $('#filterform').submit();
    }
}
