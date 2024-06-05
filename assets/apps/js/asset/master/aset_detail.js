/*
@application  	: Equipment Management
@author     	: Lukman Hakim (7143)
@contributor  	: 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

$(document).ready(function(){

    $('.datatable-custom').DataTable({
        order: [[0, 'asc']],
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        "iDisplayLength": 50,
        "paging": true,
        columnDefs: [
            { "className": "text-center", "targets": 2 },
            { "className": "text-center", "targets": 1 },
            { "className": "text-center", "targets": 3 },
        ],
    });


    $(".edit_master").on("click", function(e){
        var id_aset_detail_master	= $(this).data("edit");
        $.ajax({
            url: baseURL+'asset/master/get_aset_detail_master',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset_detail_master : id_aset_detail_master
            },
            success: function(data){
                $(".title-form-opsi").html("<strong>Edit Detail Aset</strong>");
                $.each(data, function(i, v){
                    $("#nama_kolom").val(v.nama_kolom).trigger('change');
                    $("#nama").val(v.nama);
                    $("#satuan").val(v.satuan);

                    $("input[name='id_aset_detail_master']").val(v.id_aset_detail_master);
                    $("#btn-new-master").removeClass("hidden");
                });
            }
        });
    });

    $("#btn-new-master").on("click", function(e){
        $("#nama_kolom").val(null).trigger('change');
        $("#nama").val("");
        $("#satuan").val("");
        $("input[name='id_aset_detail_master']").val("");
        $(".title-form-master").html("<strong>Buat Detail Aset Baru</strong>");
        $("#btn-new-master").addClass("hidden");
    });


    $(".form-master").on("submit", function(e){

        var isproses 	= $("input[name='isproses']").val();
        if(isproses == 0){
            $("input[name='isproses']").val(1);
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: baseURL+'asset/master/save_aset_detail_master',
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
                        $("input[name='isproses']").val(0);
                    }
                }
            });
        }else{
            alert("Silahkan tunggu proses selesai.");
        }
        e.preventDefault();
        return false;
    });

    $(document).on("click", ".set_active, .non_active, .delete", function (e) {
        $.ajax({
            url: baseURL + "asset/master/set/aset_detail_master",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_aset_detail_master	 	 : $(this).data($(this).attr("class")),
                type 	  	 : $(this).attr("class")
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

});