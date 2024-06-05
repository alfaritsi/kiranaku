$(document).ready(function(){
    $(document).on("change", "#level", function(e){
        var jabatan = $(this).val();
		$.ajax({
    		url: baseURL+'klems/master/get_data/posisi_cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				jabatan	: jabatan
			},
			success: function(data){
				var posisi = '';
				posisi		+= '<select id="posisi" name="posisi[]" multiple class="form-control col-sm-12">';
				$.each(data, function(i, v){	
					posisi	+= '<option value='+ v.id_posisi +'>'+ v.nama +'</option>';
				});
				posisi		+= '</select>';
				$('#show_posisi').html(posisi);
			},
			complete:function(){
				$('#departemen_akses,#posisi').multiselect({
					classes: 'form-control',
					buttonWidth: '100%'
				}).multiselectfilter();
			}
		});
		
    });
    $(document).on("change", "#organisasi_level", function(e){
		var jabatan	= $("#level").val();
        var level = $(this).val();
		$.ajax({
    		url: baseURL+'klems/master/get_data/posisi_cek',
			type: 'POST',
			dataType: 'JSON',
			data: {
				jabatan	: jabatan,
				level	: level
			},
			success: function(data){
				var posisi = '';
				posisi		+= '<select id="posisi" name="posisi[]" multiple class="form-control col-sm-12">';
				$.each(data, function(i, v){	
					posisi	+= '<option value='+ v.id_posisi +'>'+ v.nama +'</option>';
				});
				posisi		+= '</select>';
				$('#show_posisi').html(posisi);
			},
			complete:function(){
				$('#departemen_akses,#posisi').multiselect({
					classes: 'form-control',
					buttonWidth: '100%'
				}).multiselectfilter();
			}
		});
		
    });
	
    Array.prototype.clean = function (deleteValue) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == deleteValue) {
                this.splice(i, 1);
                i--;
            }
        }
        return this;
    };
	
    $('#departemen_akses,#posisi').multiselect({
        classes: 'form-control',
        buttonWidth: '100%'
    }).multiselectfilter();
	
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	$(".set_active-program_matrix").on("click", function(e){
		var id_program_matrix	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/activate_na/program_matrix',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_matrix : id_program_matrix
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
    	var id_program_matrix = $(this).data("delete");
    	$.ajax({
    		url: baseURL+'klems/master/set_data/delete_na/program_matrix',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_matrix : id_program_matrix
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
		var id_program_matrix	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'klems/master/get_data/program_matrix',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_program_matrix : id_program_matrix
			},
			success: function(data){
				console.log(data);
				$(".title-form").html("Edit Setting Program Matrix");
				$.each(data, function(i, v){
					$("#program").val(v.program);
					$("input[name='id_program_matrix']").val(v.id_program_matrix);
					$("input[name='id_program']").val(v.id_program);
					$("input[name='nama_program']").val(v.nama_program);
					$("input[name='tanggal_awal']").val(v.tanggal_awal);
					$("input[name='tanggal_akhir']").val(v.tanggal_akhir);
					var id_level	= v.level.split(",");
					$("select[name='level[]']").val(id_level).trigger("change");
					var id_organisasi_level	= v.organisasi_level.split(",");
					$("select[name='organisasi_level[]']").val(id_organisasi_level).trigger("change");

                    var divArray = v.posisi.split(',').clean("");
                    $('#posisi').val(divArray);
					
					// var id_posisi	= v.posisi.split(",");
					// $("select[name='posisi[]']").val(id_posisi).trigger("change");
					
					// //posisi
					// var posisi 		= v.posisi.slice(0, -1).split(",");
					// var posisi_list	= v.posisi_list.slice(0, -1).split(",");
					// var array  		= [];
					// $.each(posisi_list, function(x, y){
						// console.log(x);
						// var control = $('#posisi').empty().data('select2');
						// var adapter = control.dataAdapter;
						// array.push({"id":posisi[x],"text":y});

						// adapter.addOptions(adapter.convertToOptions(array));
						// $('#posisi').trigger('change');
					// });
					// $('#posisi').val(posisi).trigger('change');
					
					$("#btn-new").removeClass("hidden");
				});
				$('#divisi_akses,#posisi').multiselect('resync');
			}
		});
    });
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-program_matrix")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'klems/master/save/program_matrix',
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
                title: 'Setting Program Matrix',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8]
                }
            }
        ],
		scrollX:true
    } );
	//date pitcker
	$('.tanggal').datepicker({
		format: 'yyyy-mm-dd',
		// startDate: new Date(),
		autoclose: true
		
	});
    //cek all level
    $(document).on("change", ".isSelectAllLevel", function(e){
        if($(".isSelectAllLevel").is(':checked')) {
            $('#level').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#level').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    //cek all Organisasi level
    $(document).on("change", ".isSelectAllOrganisasi", function(e){
        if($(".isSelectAllOrganisasi").is(':checked')) {
            $('#organisasi_level').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#organisasi_level').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    //cek all Posisi
    $(document).on("change", ".isSelectAllPosisi", function(e){
        if($(".isSelectAllPosisi").is(':checked')) {
            $('#posisi').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#posisi').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
	// //auto complete posisi
	$("#posisi_xx").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'klems/master/get_data/posisi',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
					level: $("select[name='level[]']").val(),
					organisasi_level: $("select[name='organisasi_level[]']").val()
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
							var markup = '<div class="clearfix">'+ repo.nama+'</div>';
							return markup;
						  },
      	templateSelection: function(repo){
      							if(repo.nama) return repo.nama;
      							else return repo.text;
      					   }
    });

    $("#posisixx").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });	
	
});