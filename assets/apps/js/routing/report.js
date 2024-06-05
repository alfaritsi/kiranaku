/*
@application  : Email Routing
@author       : Matthew Jodi
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function(){
	//LHA
	$("#exclude_nik").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'routing/master/get_data/user',
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
								// return repo.text;		
      							if(repo.posst) $("input[name='caption']").val(repo.posst);
      							if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
      							else return repo.text;
      					   }
    });

    $("#exclude_nik").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });
	
	
	$(".delete").on("click", function(e){
    	var id_report	= $(this).data("delete");
    	$.ajax({
    		url: baseURL+'routing/master/set_data/deactivate/report',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_report : id_report
			},
			success: function(data){
				if(data.sts == 'OK'){
                    swal('Success',data.msg,'success').then(function(){
                        location.reload();
                    });
				}else{
                    swal('Error',data.msg,'error');
				}
			}
		});
    });

	$(".set_active-report").on("click", function(e){
    	var id_report	= $(this).data("activate");
    	$.ajax({
            url: baseURL+'routing/master/set_data/activate/report',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_report : id_report
            },
			success: function(data){
				if(data.sts == 'OK'){
                    swal('Success',data.msg,'success').then(function(){
                        location.reload();
                    });
				}else{
                    swal('Error',data.msg,'error');
				}
			}
		});
    });

    /*userrole*/
	$(".select2-user-search").select2({
		allowClear: true,
		placeholder: {
		    id: "",
		    placeholder: "Leave blank to ..."
		},
    	ajax: {
        	url: baseURL+'routing/master/get_data/user',
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
        	cache: true
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

    $(".select2-user-search").on("select2:unselecting", function (e) {
	    $("select[name='requestor']").val(null).trigger('change');
	});

	$(".edit").on("click", function(e){
    	var id_report	= $(this).data("edit");
    	$.ajax({
    		url: baseURL+'routing/master/get_data/report',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_report : id_report
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("Edit Report");
				$.each(data, function(i, v){
					$("select[name='report_app']").val(v.report_app).trigger('change');
					$("select[name='report_type']").val(v.report_type).trigger('change');
					$("#id_report").val(v.id_report);
					$("#report_code").val(v.report_code);
					$("#report_name").val(v.report_name);
					$("#report_function").val(v.report_function);
					$("#editorial").val(v.editorial);
					$("#footnote").val(v.footnote);

					if(v.requestor && v.requestor.trim() !== ""){
						var control = $('#requestor').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = v.nama+' - ['+v.nik+']';  // bikin fungsi lagi diluar fungsi ini karena get_report ga ada data2 karyawan
						adapter.addOptions(adapter.convertToOptions([{"id":v.nik,"nama":nama}]));
						// console.log(adapter);
						$('#requestor').trigger('change');
					}
					
					//LHA
					if(v.exclude_nik_list!=null){
						var nik 	= v.exclude_nik_list.slice(0, -1).split(";");
						var nama	= v.exclude_nama_list.slice(0, -1).split(";");
					}
                    console.log(nik.length);
                    console.log(nama.length);
					var array   		= [];
					$.each(nik, function(x, y){
						var control = $('#exclude_nik').empty().data('select2');
						var adapter = control.dataAdapter;
						array.push({"id":nik[x],"text":y+' - ['+ nama[x]+ ']'});


						adapter.addOptions(adapter.convertToOptions(array));
						$('#exclude_nik').trigger('change');
					});
					$('#exclude_nik').val(nik).trigger('change');

					$("input[name='id_report']").val(v.id_report);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(document).on("click", "button[name='action_btn']", function(e){
        var empty_form = validate();
        if(empty_form == 0){
    	
	    	var isproses 			= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
			    	var formData = new FormData($(".form-report")[0]);

					$.ajax({
						url: baseURL+'routing/master/save/report',
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,
						cache: false,
						processData: false,
						success: function(data){
							// console.log(data);
							if(data.sts == 'OK'){
								kiranaAlert(data.sts,data.msg);
							}else{
								kiranaAlert(data.sts,data.msg,'eror','no');
								$("input[name='isproses']").val(0);
							}
						}
					});
			}else{
				kiranaAlert('notOk','Silahkan tunggu proses selesai.','eror','no');
			}
		}
		e.preventDefault();
		return false;
    });		
});