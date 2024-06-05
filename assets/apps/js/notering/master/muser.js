/*
@application	: Notering
@author 		: Airiza Yuddha (7849)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

$(document).ready(function(){
    //auto complete nik
    $("#nik").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'notering/master/get_user_auto',
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
	
	//onchange check all filter 1
	// $(document).on("change", ".isSelectAll", function(e){
 //        if($(".isSelectAll").is(':checked')) {
 //            $('#filterdivisi').select2('destroy').find('option').prop('selected', 'selected').end().select2();
 //            // get_datas($("select[name='filterdivisi']").val(),$("select[name='filterjenisakses']").val(),$("select[name='filterstataktif']").val(),$("select[name='filterjenisijin']").val());
 //            datatables_ssp();
 //        }else{
 //            $('#filterdivisi').select2('destroy').find('option').prop('selected', false).end().select2();
 //            // get_datas($("select[name='filterdivisi']").val(),$("select[name='filterjenisakses']").val(),$("select[name='filterstataktif']").val(),$("select[name='filterjenisijin']").val());
 //            datatables_ssp();
 //        }
 //    });
    
   	//show data
    datatables_ssp();

    // //=======FILTER=======//
    // $(document).on("change", "#filterdivisi, #filterjenisakses, #filterstataktif, #filterjenisijin", function(){
    //      datatables_ssp();       
    // });

    

 	//submit form
	$(document).on("click", "button[name='action_btn']", function(e){
	
    	// var jenis_temuan 	= $("#jenis_temuan").val();
    	// var isproses 		= $("input[name='isproses']").val();
		var empty_form 		= validate('.form-master-user');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		// $("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-user")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'notering/master/save/user',
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
								// console.log('save success');
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
	//edit form
    $(document).on("click", ".edit", function(e){
    	var id_user	= $(this).data("edit");
    	// console.log(id_temuan);
    	$.ajax({
    		url: baseURL+'notering/master/get/user',
			type: 'POST',
			dataType: 'JSON',
			data: {
                id_user : id_user,
				jenis : 'normal'
			},
			success: function(data){
				
				// console.log('success');
				$(".title-form").html("Edit Data User");
                // $("input[name='val_departemen_edit']").val("");
				$.each(data, function(i, v){
                    // console.log(v);
					// console.log(v);
                    var control = $('#nik').empty().data('select2');
                    var adapter = control.dataAdapter;
                    var nama = v.nama_karyawan+' - ['+v.nik+']';
                    adapter.addOptions(adapter.convertToOptions([{"id":v.nik,"nama":nama}]));
                    $('#nik').trigger('change');

                    $("input[name='nik']").prop('readonly',true);
                    // $("input[name='nik']").val(v.nik).trigger('change');					
					$("select[name='kode_role']").val(v.kode_role).trigger('change');                   
                    $("input[name='id_user']").val(v.id_user);    
					$("#btn-new").show();
				});
				
			}
		});
    });

    //reload / create new input
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

    // set active , non active, verifikasi and delete
    $(document).on("click", ".nonactive, .setactive, .verifikasi, .delete", function(e){
        var confirm_nonactive   = "Apakah anda yakin ingin mengubah sistem aktif data ?";
    	var confirm_delete      = "Apakah anda yakin ingin menghapus data ?";
		var id 					= $(this).data($(this).attr("class"));
		var type 				= $(this).attr("class");
		var text 				= '';
		if(type == 'delete') {
			text = confirm_delete;
		} else if(type == 'nonactive' || type == 'setactive' ) {
			text = confirm_nonactive; 
		}else{
            text = "Verifikasi Device user ini?";
        }
		kiranaConfirm(
            {     
				title: "Kiranaku",
				text: text,
				dangerMode: true,
				useButton: null,
				showConfirmButton: true,
				showCancelButton: true,
				confirmButtonText: "OK",
				successCallback: function () {
					$.ajax({
			    		url: baseURL+'notering/master/set/user',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id 		 : id,	
							type  	 : type
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
				},
            }
        );
    });

    // onchange nik
    $(document).on("change", "#nik", function(e){
        var nik   = $(this).val();
            // console.log(id_temuan);
        $.ajax({
            url: baseURL+'notering/master/get/karyawan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                nik : nik
            },
            success: function(data){
                
                $.each(data, function(i, v){
                    // console.log(v);
                    if(v.plant_group != undefined && v.plant_group != ""){
                        var plant = (v.plant_group).replace(/,+$/,'');
                        $("input[name='nama']").val(v.nama);                    
                        $("input[name='plant']").val(plant);
                    }  
                //     $("select[name='requestor']").val(v.requestor).trigger('change');                
                //     $("input[name='id_temuan']").val(v.id_pica_jenis_temuan);    
                //     $("input[name='kode_temuan']").val(v.kode_temuan);                  
                //     $("#btn-new").show();
                });
                
            }
        });   
    });

});

function datatables_ssp(){
    // var filterdivisi        = $("#filterdivisi").val();
    // var filterjenisakses    = $("#filterjenisakses").val();
    // var filterstataktif     = $("#filterstataktif").val();
    // var filterjenisijin     = $("#filterjenisijin").val();
    
    $('#sspTable').DataTable().clear().destroy();
    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };
    $("#sspTable").dataTable({
       
        ordering: true,
        order: [[0, 'asc']],
       	// bLengthChange: false,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input')
                .off('.DT')
                .on('input.DT', function () {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: baseURL+'notering/master/get/user',
            type: 'POST',
            data: {
                // filterdivisi        : filterdivisi,
                // filterjenisakses    : filterjenisakses,
                // filterstataktif     : filterstataktif,
                // filterjenisijin     : filterjenisijin,
                filterbom           : "bom",
                
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        
        columns: [
            
            {
                "data": "nik",
                "name" : "nik",
                "width": "20%",
                "render": function (data, type, row) {
                    
                    // label active
                    if(row.na == "n" && row.del == "n") {var label_active = '<span class="label label-success">ACTIVE</span>';}
                    else if(row.na == "y" && row.del == "n") {var label_active = '<span class="label label-danger">NOT ACTIVE</span>';}
                    else if(row.del == "y") {var label_active = '<span class="label label-danger">DELETED</span>';}
                    // result
                    var nik = row.nik != "" && row.nik != undefined ? 
                                '<span class="label label-info">'+row.nik+'</span>' : '';
                    var nama = row.nama_karyawan != "" && row.nama_karyawan != undefined ? row.nama_karyawan : '';
                    var result = nama+"<br>"+nik+"<br>"+label_active;
                    return result;
                }
            },

            {
                "data": "nama_role",
                "name" : "nama_role",
                "width": "20%",
                "render": function (data, type, row) {
                    
                    var nama_role = row.nama_role;
                    return nama_role;
                }
            },

            {
                "data": "plant_group",
                "name" : "plant_group",
                "width": "20%",
                "render": function (data, type, row) {
                    // (row.plant_group).replace(/,+$/,'');
                    var plant_group = (row.plant_group).replace(/,+$/,'');
                    console.log(plant_group);
                    if(plant_group.indexOf(",") > 0 ){
                        var result = "";
                        plant_group = plant_group.split(",");

                        $.each(plant_group, function(i, v){
                            result += ' <span class="label label-info">'+v+'</span> ';
                        });
                    } else {
                        var result = '<span class="label label-info">'+plant_group+'</span>';;

                    }
                    return result;
                }
            },

            {
                "data": "deviceId",
                "name" : "deviceId",
                "width": "20%",
                "render": function (data, type, row) {
                    
                    var deviceId = row.deviceId;
                    if (deviceId !== null) {
                        deviceId = deviceId.substring(0, 15) + " ...";
                    }

                    return deviceId;
                }
            },

            {
                "data": "tempDeviceId",
                "name" : "tempDeviceId",
                "width": "20%",
                "render": function (data, type, row) {
                    
                    var tempDeviceId = row.tempDeviceId;
                    if (tempDeviceId !== null ) {
                        tempDeviceId = tempDeviceId.substring(0, 15) + " ...";
                    }
                    return tempDeviceId;
                }
            },
            
            {
                // "data": "tbl_inv_aset.id_aset",
                "data": "id_user",
                "name" : "id_user",
                "width": "12%",
                "render": function (data, type, row) {
                    var action = "";
                    if(row.na == 'n'){
                        action = "<li><a href='javascript:void(0);' class='edit' data-edit='"+row.id_user+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
                              +"<li><a href='javascript:void(0);' class='nonactive' data-nonactive='"+row.id_user+"'><i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                              +"<li><a href='javascript:void(0);' class='delete' data-delete='"+row.id_user+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                        
                    }
                    
                    if(row.verify == 'y'){
                        action += "<li><a href='javascript:void(0);' class='verifikasi' data-verifikasi='"+row.id_user
                                +"'><i class='fa fa-check'></i> Verifikasi</a></li>";
                    }

                    if(row.na == 'y'){
                        action = "<li><a href='javascript:void(0);' class='setactive' data-setactive='"+row.id_user
                                +"'><i class='fa fa-check'></i> Set Aktif</a></li>";
                    }


                    var output = "<div class='input-group-btn'>"
                        +   "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>"
                        +   "<ul class='dropdown-menu pull-right'>"
                        +   action 
                        +   "</ul></div>"
                    
                    return output;
                }
            }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            if(info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });
	
}