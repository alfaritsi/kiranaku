$(document).ready(function(){
	$(".dokumen").on("click", function(e){
		resetForm_use($('.form-setting-kualifikasi'));
    	var id_kualifikasi_spk	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'vendor/setting/get/kualifikasi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_kualifikasi_spk : id_kualifikasi_spk
			},
			success: function(data){
				$(".title-form").html("Edit Setting User Role");
				$.each(data, function(i, v){
					$("#id_kualifikasi_spk").val(v.id_kualifikasi_spk);
					$("input[name='nama']").val(v.kualifikasi_spk);
					if(v.list_master_dokumen!==null){
						var id_dokumen	= v.list_master_dokumen.split(",");
						$("select[name='dokumen[]']").val(id_dokumen).trigger("change");
					}
				});
			}
		});
    });
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-setting-kualifikasi")[0]);

				$.ajax({
					url: baseURL+'vendor/setting/save/kualifikasi',
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
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Setting Kualifikasi Dokumen',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1]
                }
            }
        ],
		scrollX:true
    } );
	//auto complete nik
	$("#nik").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'vendor/setting/get_user',
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
	
});

function resetForm_use($form) {
	$('#dokumen').select2('destroy').find('option').prop('selected', false).end().select2();
	$form.find('input:text, input:password, input:file,  textarea').val("");
	$form.find('select').val(0);
	$form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
}

