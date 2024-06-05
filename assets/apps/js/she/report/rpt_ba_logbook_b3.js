
$(document).ready(function(){

	$("#filter-btn").click(function() {
		var empty_form = validate(".filter-logbookb3");
        if(empty_form > 0){
        	return false;
		}

	});

    $(".post").on("click", function(e){
        var id  = $(this).data("post");
        if(confirm("Apakah anda yakin akan melakukan transfer berita acara " + (id.replace('-','/')).replace('-','/') +" ke SAP?") == false){
            return false;
        }

        // $.ajax({
        //     url: baseURL+'she/transaction/set_data/update/postba',
        //     type: 'POST',
        //     dataType: 'JSON',
        //     data: {
        //         id : id
        //     },
        //     success: function(data){
        //         if(data.sts == 'OK'){
        //             alert(data.msg);
        //             location.reload();
        //         }else{
        //             alert(data.msg);
        //         }
        //     }
        // });

        $.ajax({
            url: baseURL+'she/rfc/post_beritaacara',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id : id
            },
            success: function(data){
                if(data.sts == 'OK'){
                    alert(data.msg);
                    location.reload();
                }else{
                    alert(data.msg);
                }
            }
        });
    });


    //date pitcker
    $('.datePicker').datepicker({
        format: 'dd.mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });
	
});

function loadDetail(ba, base_url){
    $(".modal-title").html("<i class='fa fa-plus'></i> Detail Berita Acara " + ba);
    $.ajax({
        url: baseURL+'she/report/get_data/beritaacara',
        type: 'POST',
        dataType: 'JSON',
        data: {
         beritaacara : ba
        },
        // data: formData,
        // contentType: false,
        // cache: false,
        // processData: false,
        success: function(data){
            var no = 0;
            $("#detailitem").html("");
            $.each(data, function(i, v){
                no = no + 1;
                $('#pabrik').val(v.nama);
                $('#tipe').val(v.tipe);
                $('#tanggal').val(v.tanggal_keluar);
                $('#vendor').val(v.nama_vendor);
                $('#jeniskendaraan').val(v.jenis_kendaraan);
                $('#nomorkendaraan').val(v.nomor_kendaraan);
                $('#driver').val(v.nama_driver);

                var lampiran1 = "";
                var lampiran2 = "";
                var lampiran3 = "";
                
                if(v.lampiran1 != ""){
                    lampiran1 = "<a title='Lihat file lampiran 1' target='_blank' href='"+ base_url + v.lampiran1 +"'><i class='fa fa-download'></i></a>";
                }
                if(v.lampiran2 != ""){
                    lampiran2 = "<a title='Lihat file lampiran 2' target='_blank' href='"+ base_url + v.lampiran2 +"'><i class='fa fa-download'></i></a>";
                }
                if(v.lampiran3 != ""){
                    lampiran3 = "<a title='Lihat file lampiran 3' target='_blank' href='"+ base_url + v.lampiran3 +"'><i class='fa fa-download'></i></a>";
                }
                $("#detailitem").append("<tr>"
                                    +"<td align='center'>"+ no +"</td>"
                                    +"<td align='center'>"+ v.jenis_limbah +"</td>"
                                    +"<td align='center'>"+ v.stok +"</td>"
                                    +"<td align='center'>"+ v.quantity +"</td>"
                                    +"<td align='center'>"+ v.satuan +"</td>"
                                    +"<td align='center'>"+ v.konversi_ton +"</td>"
                                    +"<td align='center'>Ton</td>"
                                    +"<td align='center'>"+ v.no_manifest +"</td>"
                                    +"<td align='center'>"+ lampiran1 +"</td>"
                                    +"<td align='center'>"+ lampiran2 +"</td>"
                                    +"<td align='center'>"+ lampiran3 +"</td>"
                                    +"</tr>");

            });
        }
    });

    var gmodal = $('#form-beritaacara-detail');
    gmodal.modal('show');
}


function filtersubmit(){
    var pabrik = $("#filterpabrik").val();
    var from = $("#filterfrom").val();
    var to = $("#filterto").val();
    
    if(pabrik != "" && from != "" && to != ""){
        $('#filterform').submit();
    }
}

