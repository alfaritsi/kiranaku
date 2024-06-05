
$(document).ready(function(){

	$("#filter-btn").click(function() {
		var empty_form = validate(".filter-bpaemisiudara");
        if(empty_form > 0){
        	return false;
		}

	});

    $("#filterpabrik").change(function() {
        var pabrik = $("#filterpabrik").val();
        var formData = new FormData($(".filter-bpaemisiudara")[0]);      
        $.ajax({
            url: baseURL+'she/report/get_data/jenisemisiudara',
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data){
                $("#filterjenis").html("");
                $("#filterjenis").append("<option value=''>Silahkan Pilih</option>");  
                $.each(data, function(i, v){
                    $("#filterjenis").append("<option value='"+v.id+"'>"+v.jenis+"</option>");  
                });
            }
        });
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
    var jenis = $("#filterjenis").val();
    var periode = $("#filterperiode").val();
    
    if(pabrik != "" && jenis != "" && periode != ""){
        $('#filterform').submit();
    }
}
