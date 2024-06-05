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
	
	$('.datatable-customs').DataTable({
		order: [[0, 'asc']],
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        "iDisplayLength": 50,
        "paging": true,
        columnDefs: [
            { "className": "text-left", "targets": 2 },
            { "className": "text-left", "targets": 1 },
            { "className": "text-left", "targets": 3 },
            { "className": "text-left", "targets": 4 },
        ],
	});

	

	$.ajax({
        url: baseURL + 'asset/master/get/mobile',
        type: 'POST',
        dataType: 'JSON',
		data: {
			pengguna : 'fo'
		},
        success: function (data) {
			if(data) {

				var t = $('.my-datatable-extends').DataTable();
				t.clear().draw({
					columnDefs: [
						{"className": "text-right", "targets": 1},
					]
				});

				var active = "";
				
				var output = '';				

                $.each(data, function(i,j){
					if(j.na !== "n" || j.del !== "n"){
						active = '<br><button class="btn btn-xs btn-danger">NOT ACTIVE</button>';
					}else{
						active = '<br><button class="btn btn-xs btn-success">ACTIVE</button>';
					}
					
					//option action
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(j.na == "n" && j.del == "n"){
						output += "		<li><a href='#' class='edit_mobile' data-mobile='"+j.id_mobile+"' ><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
						output += "		<li><a href='#' class='non_active' data-mobile='"+j.id_mobile+"' ><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
						output += "		<li><a href='#' class='delete' data-mobile='"+j.id_mobile+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
					}else{
						output += "		<li><a href='#' class='set_active' data-mobile='"+j.id_mobile+"'><i class='fa fa-check'></i> Set Aktif</a></li>";
					}

					output += "				</ul>";
					output += "	        </div>";

					let plant = "";
					if (j.pabrik) {
                        let pabrik = j.pabrik.split(",");
                        $.each(pabrik, function(i, v) {
                            plant += '<button class="btn btn-xs bg-primary" style="margin-top:3px; margin-right:3px;">'+v+'</button>';
                        });
                    }

					t.row.add([
						j.nik+' - '+ j.nama + active,
						j.role,
						plant,
						output
					]).draw(false);


                });

                
			}
        }
    });
	
	$("select[name='user']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'asset/master/get/user',
            dataType: 'json',
            delay: 750,
            data: function(params) {
                return {
                    autocomplete: true,
                    type: 'nik',
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
            let type = 'nik';
            if (typeof type == 'undefined') {
                // KIRANAKU.alert({
                //     text: "Silahkan pilih role terlebih dahulu",
                //     icon: "error",
                //     html: false,
                //     reload: false
				// });
				kiranaAlert("notOK", "Silahkan pilih role terlebih dahulu", "warning", "no");
                return false;
            }
            if (repo.loading) return repo.text;
            let markup = '<div class="clearfix">' + repo.nama + ' - [' + repo.nik + ']</div>';
            if (type == 'posisi')
                markup = '<div class="clearfix">' + repo.nama + ' - [Jumlah ' + repo.jml_karyawan + ']</div>';
            return markup;
        },
        templateSelection: function(repo) {
            let type = 'nik';

            let markup = "Silahkan Pilih";
            if (repo) {
                if (type == 'posisi' && repo.jml_karyawan) {
                    markup = repo.nama + ' - [Jumlah ' + repo.jml_karyawan + ']';
                    $("input[name='caption']").val(repo.nama);
                } else if (type == 'nik' && repo.nik) {
                    markup = repo.nama + ' - [' + repo.nik + ']';
                } else {
                    if (repo.text)
                        markup = repo.text;
                    if (repo.id == "")
                        $("input[name='caption']").val("");
                }
            }

            return markup;
        }
	});
	
	$(document).on("change", ".isSelectAll", function(e){
	    if($(".isSelectAll").is(':checked')) {
	    	$("select[name='pabrik[]']").select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    }else{
	    	$("select[name='pabrik[]']").select2('destroy').find('option').prop('selected', false).end().select2();
	    }
	});

	$("button[name='action_btn']").on("click", function(e){
		var empty_form = validate('.form-master-mobile');
		if(empty_form == 0){
			var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-master-mobile")[0]);

				$.ajax({
					url: baseURL+'asset/master/save/mobile',
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
		}
		e.preventDefault();
		return false;
	});

	$(document).on("click", ".edit_mobile", function (e) {
    	var id_mobile = $(this).data("mobile");
    	$.ajax({
    		url: baseURL + 'asset/master/get/mobile',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mobile : id_mobile,
				pengguna : 'fo'
			},
			success: function(response){
				if (response && response.id_mobile) {
					$("input[name='id_mobile']").val(response.id_mobile);
					$("select[name='role']").val(response.role).trigger("change");
	
					let type = 'nik';
					if (type == 'nik' && response.nik) {
						markup = response.nama + ' - [' + response.nik + ']';
						value = response.nik;
					} else {
						markup = 'No Text';
						value = null;
					}
					$("select[name='user']").append(new Option(markup, value, true, true)).trigger("change.select2");
	
					let pabrik = function() {
						if (response.pabrik)
							if (response.pabrik.charAt(response.pabrik.length - 1) === ",")
								return response.pabrik.slice(0, -1).split(",")
							else
								return response.pabrik.split(",");
						else return null;
					};
					$("select[name='pabrik[]']").val(pabrik).trigger("change.select2");					
					
					$("#btn-new-mobile").removeClass("hidden");
					$(".title-form-mobile").html("<strong>Edit User mobile</strong>");

				} else {
					KIRANAKU.alert({
						text: "Data tidak ditemukan",
						icon: "error",
						html: false,
						reload: false
					});
				}
			}
		});
    });

    $("#btn-new-mobile").on("click", function(e){
		location.reload();
    	e.preventDefault();
		return false;
    });

    $(document).on("click", ".set_active, .non_active, .delete", function (e) {
		
		$.ajax({
			url: baseURL + "asset/master/set/mobile",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mobile : $(this).data('mobile'),	
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