/*
@application    : K-PRO
@author         : Akhmad Syaiful Yamang (8347)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function() {

	$.ajax({
        url: baseURL + "kiass/setting/get/roleuser",
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
			if(data) {

				var t = $('.my-datatable-extends').DataTable();
				t.clear().draw({
					columnDefs: [
						{"className": "text-right", "targets": 1},
						{"className": "text-center", "targets": 3},
					]
				});

				var active = "";
				var output = '';				

                $.each(data, function(i,j){
					
					if(j.na !== "n" || j.del !== "n"){
						active = '<br><button class="btn btn-xs btn-danger">not active</button>';
					}else{
						active = '<br><button class="btn btn-xs btn-success">active</button>';
					}
					
					//option action
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(j.na == "n" && j.del == "n"){
						output += "		<li><a href='#' class='edit' data-role='"+j.kode_role+"' data-user='"+j.user+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
						output += "		<li><a href='#' class='nonactive' data-role='"+j.kode_role+"' data-user='"+j.user+"'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
						output += "		<li><a href='#' class='delete' data-role='"+j.kode_role+"' data-user='"+j.user+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
					}else{
						output += "		<li><a href='#' class='setactive' data-role='"+j.kode_role+"' data-user='"+j.user+"'><i class='fa fa-check'></i> Set Aktif</a></li>";
					}

					output += "				</ul>";
					output += "	        </div>";

					let plant = "";
					if (j.pabrik_name) {
                        let pabrik = j.pabrik_name.slice(0, -1).split(",");
                        $.each(pabrik, function(i, v) {
                            plant += '<button class="btn btn-xs bg-primary" style="margin-top:3px; margin-right:3px;">'+v+'</button>';
                        });
                    }

					t.row.add([
						j.user+' - '+ j.nama + active,
						j.nama_role,
						j.caption,
						plant,
						output
					]).draw(false);


                });

                
			}
        }
    });
	
	$(document).on("change", ".isSelectAll", function(e){
	    if($(".isSelectAll").is(':checked')) {
	    	$("select[name='pabrik[]']").select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    }else{
	    	$("select[name='pabrik[]']").select2('destroy').find('option').prop('selected', false).end().select2();
	    }
	});

    $(document).on("change", "select[name='role']", function(e) {
		let maxInput = $("option:selected", this).data("limit");
        $("select[name='user']").val(null).trigger("change");
        $("select[name='pabrik[]']").val(null).trigger("change");
        $("select[name='pabrik[]']").attr("data-maximumselectionlength", maxInput);
		$("input[name='caption']").val("");
		KIRANAKU.select2($(".role-based"));
		
		if (maxInput > 0) {
            $(".select_all").hide();
        } else {
            $(".select_all").show();
        }

		let type = $("select[name='role'] option:selected").data("type");
        switch (type) {
            case 'posisi':
                $("select[name='user']").closest(".form-group").find("label").html("Posisi");
                break;

            default:
                $("select[name='user']").closest(".form-group").find("label").html("User");
                break;
        }
    });

    $("select[name='user']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'kiass/setting/get/user',
            dataType: 'json',
            delay: 750,
            data: function(params) {
                return {
                    autocomplete: true,
                    type: $("select[name='role'] option:selected").data("type"),
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
            let type = $("select[name='role'] option:selected").data("type");
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
            let type = $("select[name='role'] option:selected").data("type");

            let markup = "Silahkan Pilih";
            if (repo) {
                if (type == 'posisi' && repo.jml_karyawan) {
                    markup = repo.nama + ' - [Jumlah ' + repo.jml_karyawan + ']';
                    $("input[name='caption']").val(repo.nama);
                } else if (type == 'nik' && repo.nik) {
                    markup = repo.nama + ' - [' + repo.nik + ']';
                    $("input[name='caption']").val(repo.posst);
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

    $(document).on("click", "button[name='action_btn']", function (e) {
		var role 	= $("select[name='role']").val();
		var user 	= $("select[name='user']").val();
		var pabrik 	= $("select[name='pabrik[]']").val();
		var caption = $("input[name='caption']").val();
		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			if(role !=="" && user !== "" && pabrik !== "" && caption !== ""){
				var formData = new FormData($(".form-setting-userrole")[0]);

				$.ajax({
					url: baseURL + 'kiass/setting/save/user',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					complete: function () {
						$("input[name='isproses']").val(0);
					}
				});
			}else{
				kiranaAlert("notOK", "Mohon lengkapi data", "warning", "no");
				$("input[name='isproses']").val(0);
			}
		} else {
			kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
		}
		
		e.preventDefault();
		return false;
	});

	$(document).on("click", ".edit", function (e) {
		$.ajax({
			url: baseURL + 'kiass/setting/get/roleuser',
			type: 'POST',
			dataType: 'JSON',
			data: {
				role: $(this).attr("data-role"),
				user: $(this).attr("data-user"),
				single: 'single'
			},
			success: function(response) {
				if (response && response.kode_role) {
					$("select[name='role']").val(response.kode_role).trigger("change");
	
					let type = $("select[name='role'] option:selected").data("type");
					if (type == 'posisi' && response.jml_karyawan) {
						markup = response.nama + ' - [jumlah ' + response.jml_karyawan + ']';
						value = response.nama;
					} else if (type == 'nik' && response.user) {
						markup = response.nama + ' - [' + response.user + ']';
						value = response.user;
					} else {
						markup = 'No Text';
						value = null;
					}
					$("select[name='user']").append(new Option(markup, value, true, true)).trigger("change.select2");
	
					$("input[name='caption']").val(response.caption);
					let pabrik = function() {
						if (response.pabrik)
							if (response.pabrik.charAt(response.pabrik.length - 1) === ",")
								return response.pabrik.slice(0, -1).split(",")
							else
								return response.pabrik.split(",");
						else return null;
					};
					$("select[name='pabrik[]']").val(pabrik).trigger("change.select2");
					$("input[name='action']").val('edit');
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
		e.preventDefault();
		return false;
	});

	 $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		$.ajax({
			url: baseURL + "kiass/setting/set/user",
			type: 'POST',
			dataType: 'JSON',
			data: {
				kode_role : $(this).data("role"),
				user : $(this).data("user"),
				type : $(this).attr("class")
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

