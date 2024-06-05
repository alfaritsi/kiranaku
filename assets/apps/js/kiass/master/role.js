$(document).ready(function () {
	
	
    $.ajax({
        url: baseURL + "kiass/master/get/role",
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
			if(data.role) {

				var dropdownRole = '<option value="finish">Finish</option>';

				var t = $('.my-datatable-order-col2').DataTable();
				t.clear().draw({
					columnDefs: [
						{"className": "text-right", "targets": 1},
						{"className": "text-center", "targets": 3},
					]
				});

				var active = "";
				var output = '';				

                $.each(data.role, function(i,j){
					active = (j.na == "n" && j.del == "n") ? '<br><button class="btn btn-xs btn-success">active</button>' :'<br><button class="btn btn-xs btn-danger">not active</button>' ;
					//option action
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(j.na == "n" && j.del == "n"){
						output += "		<li><a href='#' class='edit' data-edit='"+j.kode_role+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
						output += "		<li><a href='#' class='delete' data-delete='"+j.kode_role+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
					}else{
						output += "		<li><a href='#' class='setactive' data-setactive='"+j.kode_role+"'><i class='fa fa-check'></i> Set Aktif</a></li>";
					}

					output += "				</ul>";
					output += "	        </div>";

					t.row.add([
						j.nama_role + active,
						j.level,
						"",
						output
					]).draw(false);

					
					if (j.na == 'n' && j.del == 'n'){
						dropdownRole += '<option value="'+j.level+'">'+j.nama_role+'</option>';
					}

                });

                
			}
			
			if(data.flow){
				var tabFlow = '';
				var contentFlow = '';
				var active = '';
				var counter = 0;
				$.each(data.flow, function(i,v){
					
					if(i == 0){
						active = 'active';
					}else{
						active = '';
					}

					tabFlow += '<li class="'+active+'"><a href="#flow_'+v.id_flow+'" data-toggle="tab">'+ v.lokasi +' - '+ v.keterangan +'</a></li>';

					contentFlow += '<div class="tab-pane '+active+'" id="flow_'+v.id_flow+'">';
					contentFlow += '		<input type="hidden" name="id_flow_'+i+'" value="'+v.id_flow+'"></input>';
					//if approve, decline, assign, drop, limit
					contentFlow += '<div class="col-md-6"><div class="form-group"><label for="if_approve">Jika Approve</label>';
					contentFlow += '		<select name="if_approve_flow_'+v.id_flow+'" class="form-control select2"><option value="">Silahkan Pilih</option>'+dropdownRole+'</select>';
					contentFlow += '</div>';
					contentFlow += '<div class="form-group"><label for="if_approve">Jika Decline</label>';
					contentFlow += '		<select name="if_decline_flow_'+v.id_flow+'" class="form-control select2"><option value="">Silahkan Pilih</option>'+dropdownRole+'</select>';
					contentFlow += '</div>';
					contentFlow += '<div class="form-group"><label for="if_approve">Jika Assign</label>';
					contentFlow += '		<select name="if_assign_flow_'+v.id_flow+'" class="form-control select2"><option value="">Silahkan Pilih</option>'+dropdownRole+'</select>';
					contentFlow += '</div>';
					contentFlow += '<div class="form-group"><label for="if_approve">Jika Drop</label>';
					contentFlow += '		<select name="if_drop_flow_'+v.id_flow+'" class="form-control select2"><option value="">Silahkan Pilih</option>'+dropdownRole+'</select>';
					contentFlow += '</div>';
					contentFlow += '<div class="form-group"><label for="limit-app">Limit Approval</label>';
					contentFlow += '		<input type="text" class="form-control angka" name="limit_app_flow_'+v.id_flow+'" value="0"></input>';
					contentFlow += '</div></div>';
					//deviasi
					contentFlow += '<div class="col-md-6"><div class="form-group"><label for="if_approve">Jika Approve (Deviasi)</label>';
					contentFlow += '		<select name="if_approve_deviasi_flow_'+v.id_flow+'" class="form-control select2"><option value="">Silahkan Pilih</option>'+dropdownRole+'</select>';
					contentFlow += '</div>';
					contentFlow += '<div class="form-group"><label for="if_approve">Jika Decline (Deviasi)</label>';
					contentFlow += '		<select name="if_decline_deviasi_flow_'+v.id_flow+'" class="form-control select2"><option value="">Silahkan Pilih</option>'+dropdownRole+'</select>';
					contentFlow += '</div>';
					contentFlow += '<div class="form-group"><label for="if_approve">Jika Assign (Deviasi)</label>';
					contentFlow += '		<select name="if_assign_deviasi_flow_'+v.id_flow+'" class="form-control select2"><option value="">Silahkan Pilih</option>'+dropdownRole+'</select>';
					contentFlow += '</div>';
					contentFlow += '<div class="form-group"><label for="if_approve">Jika Drop (Deviasi)</label>';
					contentFlow += '		<select name="if_drop_deviasi_flow_'+v.id_flow+'" class="form-control select2"><option value="">Silahkan Pilih</option>'+dropdownRole+'</select>';
					contentFlow += '</div>';
					contentFlow += '<div class="form-group"><label for="limit-app">Limit Approval (Deviasi)</label>';
					contentFlow += '		<input type="text" class="form-control angka" name="limit_app_deviasi_flow_'+v.id_flow+'" value="0"></input>';
					contentFlow += '</div></div>'; //col md
					contentFlow += '</div>'; // tab pane

					counter += 1;

				});
				var outputFlow = '<ul class="nav nav-pills nav-stacked col-md-3" style="border-right:1px solid lightgray">';
				
					outputFlow += tabFlow;
					outputFlow += '</ul>';
					outputFlow += '<div class="tab-content col-md-9">';
					outputFlow += '<input type="hidden" name="counter_flow" value="'+counter+'"></input>';
					outputFlow += contentFlow;
					outputFlow += '</div>';
				
				$("#inpFlow").html('');
				$("#inpFlow").html(outputFlow);
				$(".select2").select2();
				$("input[name='counter_flow']").val(counter);

			}
        }
    });
    
    
    
    
    $(this).scrollTop(0);

    $(document).on("click", "#btn_form", function(e) {
        $("#bottomView")[0].scrollIntoView(true);
        window.scrollBy(0, -65); 
	});
	
	$("#aksesLimitPabrik").on("change", function(e){
	    if($("#aksesLimitPabrik").is(':checked')) {
	      $("#isLimitPabrik").val("1");
	    }else{
	      $("#isLimitPabrik").val("0");
	    }
	});

	$("#aksesDelete").on("change", function(e){
	    if($("#aksesDelete").is(':checked')) {
	      $("#isDelete").val("1");
	    }else{
	      $("#isDelete").val("0");
	    }
	});

	
	$(document).on("change", "#flow", function () {	
		$.ajax({
			url: baseURL + "kiass/master/get/rolelist",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_flow: $("select[name='flow']").val()
			},
			success: function (data) {
				if(data) {
	
					var t = $('.my-datatable-order-col2').DataTable();
					t.clear().draw({
						columnDefs: [
							{"className": "text-right", "targets": 1},
							{"className": "text-center", "targets": 3},
						]
					});
	
					
					
					$.each(data, function(i,j){
						var active = "";
						var action = "";
						var output = "";
						active = (j.na == "n" && j.del == "n") ? '<br><button class="btn btn-xs btn-success">active</button>' :'<br><button class="btn btn-xs btn-danger">not active</button>' ;
						//option action
						action = "			<div class='input-group-btn'>";
						action += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						action += "				<ul class='dropdown-menu pull-right'>";
						if(j.na == "n" && j.del == "n"){
							action += "		<li><a href='#' class='edit' data-edit='"+j.kode_role+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
							action += "		<li><a href='#' class='delete' data-delete='"+j.kode_role+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
						}else{
							action += "		<li><a href='#' class='setactive' data-setactive='"+j.kode_role+"'><i class='fa fa-check'></i> Set Aktif</a></li>";
						}
	
						action += "				</ul>";
						action += "	        </div>";

						output += "<dl>";
						output += "<small>";
						if (j.approve || j.decline)
							output += "<dt>Pengajuan Penjualan</dt>";
						if (j.approve)
							output += "<dd class='mb-0'>If Approve : " + j.approve + "</dd>";
						if (j.assign)
							output += "<dd class='mb-0'>If Assign : " + j.assign + "</dd>";
						if (j.decline)
							output += "<dd class='mb-0'>If Decline : " + j.decline + "</dd>";
						if (j.drops)
							output += "<dd class='mb-0'>If Drop : " + j.drops + "</dd>";
						if (j.app_lim_val > 0)
							output += "<dd class='mb-0'>Limit Approval : " + j.app_lim_val + "</dd>";

						if (j.approve_capex || j.decline_capex)
							output += "<dt>Pengajuan Deviasi</dt>";
						if (j.approve_capex)
							output += "<dd class='mb-0'>If Approve : " + j.approve_capex + "</dd>";
						if (j.assign_capex)
							output += "<dd class='mb-0'>If Assign : " + j.assign_capex + "</dd>";
						if (j.decline_capex)
							output += "<dd class='mb-0'>If Decline : " + j.decline_capex + "</dd>";
						if (j.drops_capex)
							output += "<dd class='mb-0'>If Drop : " + j.drops_capex + "</dd>";
						if (j.app_lim_val_ho > 0)
							output += "<dd class='mb-0'>Limit Approval : " + j.app_lim_val_capex + "</dd>";
						

						output += "<dt>Lain-lain</dt>";
						output += "<dd class='mb-0'>Limit Pabrik : " + (j.is_limit_pabrik == 0 ? "Tidak" : "Ya") + "</dd>";
						output += "<dd class='mb-0'>Akses Delete : " + (j.akses_delete == 0 ? "Tidak" : "Ya") + "</dd>";
						output += "<dd class='mb-0'>Tipe User : " + (j.tipe_user == 'nik' ? "NIK" : "Jabatan") + "</dd>";
	
						output += "</small>";
						output += "</dl>";

	
						t.row.add([
							j.nama_role + active,
							j.level,
							output,
							action
						]).draw(false);
							
					});
					
				}
				
			}
		});
		
	});


	$(document).on("click", "button[name='action_btn']", function (e) {
		
		var role 	= $("input[name='role']").val();
		var level 	= $("input[name='level']").val();
		var isproses = $("input[name='isproses']").val();
		if (isproses == 0) {
			$("input[name='isproses']").val(1);
			if(role.trim() !=="" && level.trim() !== ""){
				var formData = new FormData($(".form-master-role")[0]);

				$.ajax({
					url: baseURL + 'kiass/master/save/role',
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
				kiranaAlert("notOK", "Mohon lengkapi Nama Role dan Level", "warning", "no");
				$("input[name='isproses']").val(0);
			}
		} else {
			kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
		}
		
		e.preventDefault();
		return false;
	});

	$(document).on("click", ".edit", function (e) {
		// $(".form-master-role input, .form-master-role select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "kiass/master/get/roledtl",
			type: 'POST',
			dataType: 'JSON',
			data: {
				kode_role: $(this).data("edit")
			},
			success: function (data) {
				if(data) {
					
					var kode_role = "";
					var nama_role = "";
					var tipe_user = "";
					var akses_delete = "";
					var isLimitPabrik = "";

					$("#aksesLimitPabrik").prop("checked", false);
					$("input[name='isLimitPabrik']").val(0);
					$("#aksesDelete").prop("checked", false);
					$("input[name='isDelete']").val(0);
					$.each(data, function(i,v){
						kode_role = v.kode_role;
						nama_role = v.nama_role;
						level = v.level;
						tipe_user = v.tipe_user;
						akses_delete = v.akses_delete;
						isLimitPabrik = v.is_limit_pabrik;

						if(v.is_limit_pabrik == '1'){
							$("#aksesLimitPabrik").prop("checked", true);
							$("input[name='isLimitPabrik']").val(v.is_limit_pabrik);
						}

						if(v.akses_delete == '1'){
							$("#aksesDelete").prop("checked", true);
							$("input[name='isDelete']").val(v.akses_delete);
						}

						$("select[name='if_approve_flow_"+v.id_flow+"']").val(v.if_approve).trigger("change");
						$("select[name='if_decline_flow_"+v.id_flow+"']").val(v.if_decline).trigger("change");
						$("select[name='if_assign_flow_"+v.id_flow+"']").val(v.if_assign).trigger("change");
						$("select[name='if_drop_flow_"+v.id_flow+"']").val(v.if_drop).trigger("change");
						$("input[name='limit_app_flow_"+v.id_flow+"']").val(v.app_lim_val);
						$("select[name='if_approve_deviasi_flow_"+v.id_flow+"']").val(v.if_approve_capex).trigger("change");
						$("select[name='if_decline_deviasi_flow_"+v.id_flow+"']").val(v.if_decline_capex).trigger("change");
						$("select[name='if_assign_deviasi_flow_"+v.id_flow+"']").val(v.if_assign_capex).trigger("change");
						$("select[name='if_drop_deviasi_flow_"+v.id_flow+"']").val(v.if_drop_deviasi).trigger("change");
						$("input[name='limit_app_deviasi_flow_"+v.id_flow+"']").val(v.app_lim_val_capex);
						
					});

					$("input[name='kode_role']").val(kode_role);
					$("input[name='level']").val(level);
					$("input[name='role']").val(nama_role);
					$("select[name='tipe_user']").val(tipe_user).trigger("change");

					$("#btn-new").show();
				}

				$("#bottomView")[0].scrollIntoView(true);
        		window.scrollBy(0, -65);


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
			url: baseURL + "kiass/master/set/role",
			type: 'POST',
			dataType: 'JSON',
			data: {
				kode_role : $(this).data($(this).attr("class")),
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
