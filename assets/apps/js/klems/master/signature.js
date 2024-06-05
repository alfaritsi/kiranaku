$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-signature").on("click", function(e){
		var id_tandatangan	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/signature',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tandatangan : id_tandatangan
			},
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
    });

	$(".delete").on("click", function(e){
    	var id_tandatangan = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/signature',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tandatangan : id_tandatangan
			},
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
    });

	$(".edit").on("click", function(e){
    	var id_tandatangan	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/signature',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tandatangan : id_tandatangan
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Tanda Tangan");
				$.each(data, function(i, v){
					$("#tandatangan").val(v.tandatangan);
					$("input[name='id_tandatangan']").val(v.id_tandatangan);
					// $("select[name='nik']").val(v.nik).trigger('change');
					var control = $('#nik').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = v.nama+' - ['+v.nik+']';
					adapter.addOptions(adapter.convertToOptions([{"id":v.nik,"nama":nama}]));
					$('#nik').trigger('change');					
					$("input[name='gambar']").val(v.gambar);
					$("input[name='posisi_sertifikat']").val(v.posisi_sertifikat);
					$("input[name='gambar_url']").val(v.gambar);
					$("#btn-new").removeClass("hidden");
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
		    	var formData = new FormData($(".form-master-signature")[0]);

				$.ajax({
					url: baseURL+'klems/master/save/signature',
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
                title: 'Setting Tanda Tangan',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,3,4]
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
            url: baseURL+'klems/master/get_user',
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