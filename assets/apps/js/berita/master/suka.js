$(document).ready(function(){
	//auto complete nik
	$("#nik").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'berita/master/get_user',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, page) {
                return {
                    results: data.items  
                };
            },
            cache: false
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
		templateResult: function(repo) {
						    if (repo.loading) return repo.text;
							var markup = '<div class="clearfix">'+ repo.nama+' - ['+repo.nik + ']</div>';
							return markup;
						  },
      	templateSelection: function(repo){ 
      							if(repo.posst) $("input[name='caption']").val(repo.posst);
      							if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
      							else return repo.nama;
      					   }
    });

    $("#nik").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });
	
	//set tanggal program batch
	$('#tanggal').datepicker({
        format: 'yyyy-mm-dd',
	    autoclose: true
    });
	//add	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	//set data
	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "berita/master/set/duka",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_notif_berita	: $(this).data($(this).attr("class")),	
				type 	  	 	: $(this).attr("class")
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
	
	
	//sent email
	$(document).on("click", ".sent", function (e) {
		$.ajax({
			url: baseURL + "berita/master/sent_email",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_notif_berita	: $(this).data("edit"),
				to				: $(this).data("to"),
				nik_duka		: $(this).data("nik_duka")
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
	//status
	$(document).on("click", ".status", function(){
		var id_notif_berita	= $(this).data("edit");
		var nik_duka		= $(this).data("nik_duka");
		$.ajax({
    		url: baseURL+'berita/master/get/penerima',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_notif_berita : id_notif_berita,
				nik_duka		: nik_duka
			},
			success: function(data){
				$(".modal-title").html("Detail Penerima Email");
				var nil		= "<table class='table table-bordered datatables'>";
					nil	 	+= "<thead>";
					nil	 	+= 		"<tr>";
					nil	 	+= 			"<th>NIK</th><th>Nama</th><th>Email</th><th>Status</th>";
					nil	 	+= 		"</tr>";
					nil	 	+= "</thead>";
					nil	 	+= "<tbody>";
					$.each(data, function(i,v){
						nil	+= 		"<tr>";
						nil	+= 			"<td>"+v.nik+"</td><td>"+v.nama+"</td><td>"+v.email+"</td><td>"+v.label_sent+"</td>";
						nil	+= 		"</tr>";
					});
					nil	 	+= "</tbody>";
					
				$("#show_status").html(nil);
				
				$('.datatables').dataTable({
					destroy: true,
					'order': [[1, 'asc']]
				});	
				
			},
			complete: function () {
				$('#status_modal').modal('show');
			}

		});
    });

	$(".edit").on("click", function(e){
    	var id_notif_berita	= $(this).data("edit");
		var btn_hide		= $(this).data("btn_hide");
		$.ajax({
    		url: baseURL+'berita/master/get/duka',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_notif_berita : id_notif_berita
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Form List Berita Duka");
				$.each(data, function(i, v){
					$("#editorial1").val(v.editorial1);
					$("#editorial2").val(v.editorial2);
					$("#editorial3").val(v.editorial3);
					$("#editorial4").val(v.editorial4);
					$("input[name='id_notif_berita']").val(v.id_notif_berita);
					$("input[name='gambar']").val(v.template);
					$("input[name='gambar_url']").val(v.template);
					$("input[name='nama_anak']").val(v.nama_anak);
					$("select[name='gender']").val(v.gender).trigger("change");
					$("input[name='tanggal']").val(v.tanggal);
					var control = $('#nik').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = v.nama_karyawan+' - ['+v.nik+']';
					adapter.addOptions(adapter.convertToOptions([{"id":v.nik,"nama":nama}]));
					$('#nik').trigger('change');				
					if(v.email!==null){
						var email	= v.email.split(",");
						$("select[name='email[]']").val(email).trigger("change");
					}
					
					
					$("#btn-new").removeClass("hidden");
					
				});
			},
			complete: function () {
				if(btn_hide=='yes'){
					$(".btn_hide").hide();	
				}
				
			}
		});
    });
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-berita_suka")[0]);

				$.ajax({
					url: baseURL+'berita/master/save/suka',
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
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'List Berita Duka Cita',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5]
                }
            }
        ]
    } );	

});


function resetForm_use($form,$act) {
	$('#myModalLabel').html("Form Item Spec");
	$('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
	$form.find('input:text, input:password, input:file,  textarea').val("");
	$form.find('input:text, input:password, input:file,  textarea').prop('disabled', false);
	$form.find('select').val(0);
	$form.find('select').prop('disabled', false);
	$form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
	$form.find('input:radio, input:checkbox').prop('disabled', false);
}
