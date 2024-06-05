/*
@application	: PICA
@author 		: Airiza Yuddha (7849)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

$(document).ready(function(){
    var mode = 'view';
    // console.log(mode);
	// $('#jenis_temuan option:eq(0)').trigger('change');
    // $('#jenis_temuan').eq(2);
    /*document.getElementById('jenis_temuan').selectedIndex = 4; 
    $('#jenis_temuan').trigger('change');*/
    if($("input[name='id_role']").val() == ""){
        document.getElementById('jenis_temuan').selectedIndex = 0;
        var jenis = $('#jenis_temuan').val() != null ? $('#jenis_temuan').val() : null;
        create_select( jenis, mode); //this calls it on load
        $('#jenis_temuan').change(create_select);
    }
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

    //=======FILTER=======//
    $(document).on("change", "#filterdivisi, #filterjenisakses, #filterstataktif, #filterjenisijin", function(){
         datatables_ssp();       
    });

    

 	//submit form
	$(document).on("click", "button[name='action_btn']", function(e){
	   
    	// var jenis_temuan 	= $("#jenis_temuan").val();
    	// var isproses 		= $("input[name='isproses']").val();
		var empty_form 		= validate('.form-master-role',true);
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-role")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'pica/master/save/role',
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
    	var id_role	= $(this).data("edit");
    	mode = 'edit';
    	$.ajax({
    		url: baseURL+'pica/master/get/role',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_role : id_role
			},
			success: function(data){
				
				$(".title-form").html("Edit Role");
                $.each(data.data, function(i, v){
					
                    // $("input[name='nama_role']").val(v.nama_role);					
                    $("select[name='nama_role']").val(v.nama_role).trigger('change');     
                    $("input[name='level']").val(v.level);  
                    $("select[name='if_approve']").val(v.if_approve).trigger('change');
                    $("select[name='if_decline']").val(v.if_decline).trigger('change');
                    $("select[name='jenis_temuan']").val(v.id_pica_jenis_temuan+"|"+v.jenis_temuan).trigger('change');
					$("input[name='id_role']").val(v.id_pica_role); 
                    // set hiden val 
                    $("input[name='if_approve_hidden']").val(v.if_approve);
                    $("input[name='if_decline_hidden']").val(v.if_decline);
                    // console.log(v.nama_temuan);
                    if(v.multiple_plan == 1){
                        $("#multiple_plan").prop('checked', true);  
                    } else {
                        $("#multiple_plan").prop('checked', false);
                    }
                    if(v.akses_delete == 1){
                        $("#akses_delete").prop('checked', true);
                    } else {
                        $("#akses_delete").prop('checked', false);
                    }
                    if(v.isresponder == 1){
                        $("#isresponder").prop('checked', true);
                    } else {
                        $("#isresponder").prop('checked', false);
                    }


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

    // set active , non active and delete
    $(document).on("click", ".nonactive, .setactive, .delete", function(e){
        var confirm_nonactive   = "Apakah anda yakin ingin mengubah sistem aktif data ?";
        var confirm_delete      = "Apakah anda yakin ingin menghapus data ?";
        var id                  = $(this).data($(this).attr("class"));
        var type                = $(this).attr("class");
        var text                = '';
        if(type == 'delete') {
            text = confirm_delete;
        } else if(type == 'nonactive' || type == 'setactive' ) {
            text = confirm_nonactive; 
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
                        url: baseURL+'pica/master/set/role',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id       : id,  
                            type     : type
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

    // Change jenis temuan  
    $(document).on("change", "#jenis_temuan", function(e){
        var datatemuan  = $(this).val();
        var splitdata   = datatemuan.split("|");
        var id_temuan   = splitdata[0];
        // console.log(id_temuan);
        // console.log(buyer);
        $.ajax({
            url: baseURL+'pica/master/pica_role_normal',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_temuan   : id_temuan,
                // type    : 'si'
            },
            success: function(data){
                $('#if_approve').html('');
                $('#if_decline').html('');
                var form = ''; var valtriger = 0;
                $.each(data, function(i,v){
                    if(i == 0 ) valtriger = v.level; 
                    form += "<option value='"+v.level+"'>"+v.nama_role+"</option>"
                });
                var formapp     = form+"<option value='100'>Finish</option>";
                var formdec     = "<option value='-'>Silahkan pilih role</option>"+form;
                $('#if_approve').html(formapp);
                $('#if_decline').html(formdec);
                console.log($("input[name='id_role']").val());
                if($("input[name='id_role']").val() == ""){
                    // console.log('und');
                    $('#if_approve').val(valtriger).trigger('change');
                    $('#if_decline').val(valtriger).trigger('change');    
                } else {
                    // console.log('def');
                    var val_app = $("#if_approve_hidden").val();
                    var val_dec = $("#if_decline_hidden").val();
                    $('#if_approve').val(val_app).trigger('change');
                    $('#if_decline').val(val_dec).trigger('change');
                }
                
            }
        });
    });
    
});

function create_select(data, mode){
    if(mode == 'view'){
        var datatemuan  = data;
        var splitdata   = (data != 0 && data != null) ? datatemuan.split("|") : null;
        var id_temuan   = (data != 0 && data != null) ? splitdata[0] : null;
        // console.log(id_temuan);
        // console.log(buyer);
        $.ajax({
            url: baseURL+'pica/master/pica_role_normal',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_temuan   : id_temuan,
                // type    : 'si'
            },
            success: function(data){
                $('#if_approve').html('');
                $('#if_decline').html('');
                var form = ''; var valtriger = 0;
                $.each(data, function(i,v){
                    if(i == 0 ) valtriger = v.level; 
                    form += "<option value='"+v.level+"'>"+v.nama_role+"</option>"
                });
                var formapp     = form+"<option value='100'>Finish</option>";
                var formdec     = "<option value='-'>Silahkan pilih role</option>"+form;
                $('#if_approve').html(formapp);
                $('#if_decline').html(formdec);
                if($("#id_role").val() == undefined){
                    // console.log('und');
                    $('#if_approve').val(valtriger).trigger('change');
                    // $('#if_decline').val(valtriger).trigger('change');    
                } else {
                    console.log('def');
                    var val_app = $("#if_approve_hidden").val();
                    var val_dec = $("#if_decline_hidden").val();
                    $('#if_approve').val(val_app).trigger('change');
                    $('#if_decline').val(val_dec).trigger('change');
                }
                
            }
        });  
    }
    
}

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
        columnDefs: [
            {"className": "text-left", "targets": 0},
            {"className": "text-center", "targets": 1},
            {"className": "text-left", "targets": 2},
            // {"className": "text-left", "targets": 3},
            // {"className": "text-left", "targets": 4},
        ],
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
            url: baseURL+'pica/master/pica_role',
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
                "data": "nama_role, approve, decline, akses_delete, isresponder",
                "name" : "nama_role",
                "width": "20%",
                "render": function (data, type, row) {
                    
                    // label active
                    if(row.na == "n" && row.del == "n") {var label_active = '<span class="label label-success">ACTIVE</span>';}
                    else if(row.na == "y" && row.del == "n") {var label_active = '<span class="label label-danger">NOT ACTIVE</span>';}
                    else if(row.del == "y") {var label_active = '<span class="label label-danger">DELETED</span>';}
                    // result
                    // var akses_delete    = (row.akses_delete == 1) ? 'Allow' : 'Disallow';
                    var multiple_plan   = (row.multiple_plan == 1) ? 'Multiple' : 'Single';
                    var approve         = (row.approve== null) ? 'Finish' : row.approve;
                    var isresponder     = (row.isresponder== 1) ? '<span class="label label-info">Role Responder</span>' : "";
                    var role = row.nama_role+"<br> If Approve : "+approve+"<br> If Decline : "+row.decline+"<br> Akses Pabrik : "+multiple_plan+"<br>"+isresponder+" "+label_active; 
                    return role;
                }
            },

            {
                "data": "nama_temuan",
                "name" : "nama_temuan",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.nama_temuan;
                }
            },

            {
                "data": "level",
                "name" : "level",
                "width": "20%",
                "render": function (data, type, row) {
                    return row.level;
                }
            },

            // {
            //     "data": "approve",
            //     "name" : "approve",
            //     "width": "20%",
            //     "render": function (data, type, row) {
            //         var approve = row.approve+"["+row.if_approve+"]";
            //         return approve;
            //     }
            // },

            // {
            //     "data": "decline",
            //     "name" : "decline",
            //     "width": "20%",
            //     "render": function (data, type, row) {
            //         var decline = row.decline+"["+row.if_decline+"]";
            //         return decline;
            //     }
            // },
            
            {
                // "data": "tbl_inv_aset.id_aset",
                "data": "id_pica_role",
                "name" : "id_pica_role",
                "width": "12%",
                "render": function (data, type, row) {
                    var action = "";
                    if(row.na == 'n'){
                        action = "<li><a href='javascript:void(0);' class='edit' data-edit='"+row.id_pica_role+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
                              +"<li><a href='javascript:void(0);' class='nonactive' data-nonactive='"+row.id_pica_role+"'><i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                              +"<li><a href='javascript:void(0);' class='delete' data-delete='"+row.id_pica_role+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                        
                    }
                    if(row.na == 'y'){
                        action = "<li><a href='javascript:void(0);' class='setactive' data-setactive='"+row.id_pica_role
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