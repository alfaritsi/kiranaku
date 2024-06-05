$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
    $("#btn_reset").on("click", function(e){
		$("select[name='id_role']").val('').trigger("change.select2");
		$("select[name='user']").val('').trigger("change.select2");
		$("select[name='pabrik[]']").val('').trigger("change.select2");
    });

	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "bank/setting/set/user",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_user_role : $(this).data($(this).attr("class")),	
				type 	: $(this).attr("class")
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

	$(".edit").on("click", function(e){
    	var id_user_role	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'bank/setting/get/user_role',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_user_role : id_user_role
			},
			success: function(data){
				$(".title-form").html("Edit Setting User Role");
				$.each(data, function(i, v){
					$("#id_user_role").val(v.id_user_role);
					$("select[name='id_role']").val(v.id_role).trigger("change.select2");
					//buat caption user
					let tipe_user = $("select[name='id_role'] option:selected").data("tipe_user");
					if(tipe_user=='posisi'){
						$("select[name='user']").closest(".form-group").find("label").html("Posisi");
					}else{
						$("select[name='user']").closest(".form-group").find("label").html("User");
					}
					//buat auto complete user
					var control = $('#user').empty().data('select2');
					var adapter = control.dataAdapter;
					var nama = (tipe_user=='posisi') ? v.user+' - ['+v.caption_user+']' : v.caption_user+' - ['+v.user+']';
					adapter.addOptions(adapter.convertToOptions([{"id":v.user,"nama":nama}]));
					$('#user').trigger('change');					
					//buat pabrik	
					if(v.pabrik!=null){
						var pabrik	= v.pabrik.split(",");
						$("select[name='pabrik[]']").val(pabrik).trigger("change");
					}
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
		    	var formData = new FormData($(".form-master-user_role")[0]);

				$.ajax({
					url: baseURL+'bank/setting/save/user',
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
                title: 'Setting User Role',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1]
                }
            }
        ],
		scrollX:true
    } );
	
	//check all plant
    $(document).on("change", ".isSelectAllPlant", function(e) {
        if ($(".isSelectAllPlant").is(':checked')) {
            $('#pabrik').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        } else {
            $('#pabrik').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
	
	//change id_role
    $(document).on("change", "select[name='id_role']", function(e) {
        let tipe_user = $("select[name='id_role'] option:selected").data("tipe_user");
        switch (tipe_user) {
            case 'posisi':
                $("select[name='user']").closest(".form-group").find("label").html("Posisi");
                break;

            default:
                $("select[name='user']").closest(".form-group").find("label").html("User");
                break;
        }
    });
	
	//auto complete user
	$("select[name='user']").select2({
	// $("#user").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
			
            // url: baseURL+'bank/setting/get_user_auto',
            // url: baseURL+'bank/setting/get_posisi_auto',
            url: baseURL+'bank/setting/get/user',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
					tipe_user: $("select[name='id_role'] option:selected").data("tipe_user"),
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
            let tipe_user = $("select[name='id_role'] option:selected").data("tipe_user");
            if (typeof tipe_user == 'undefined') {
				swal('Error', "Silahkan pilih role terlebih dahulu", 'error');
            }
			
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

    $("#user").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });
	
});
