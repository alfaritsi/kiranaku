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

	
	//onchange check all pabrik
	$(document).on("change", ".isSelectAll", function(e){
        if($(".isSelectAll").is(':checked')) {
            $('#pabrik').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#pabrik').select2('destroy').find('option').prop('selected', false).end().select2();
        }
    });
    
   	//show data
    datatables_ssp();

 	//submit form
	$(document).on("click", "button[name='action_btn']", function(e){
	   
    	// var jenis_temuan 	= $("#jenis_temuan").val();
    	// var isproses 		= $("input[name='isproses']").val();
		var empty_form 		= validate('.form-master-roleposisi');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-roleposisi")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'pica/master/save/roleposisi',
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
    	var role	= $(this).data("edit");
    	// console.log(role);
    	$.ajax({
    		url: baseURL+'pica/master/get/roleposisi',
			type: 'POST',
			dataType: 'JSON',
			data: {
				role : role
			},
			success: function(data){
				// console.log(data)+"BB";
				$(".title-form").html("Edit Setting Role Posisi");
                $.each(data.data, function(i, v){
					// set value witout trigger change
                    $("select[name='nama_role']").val(v.id_pica_role+"|"+v.id_pica_jenis_temuan).trigger('change.select2');
                    
                    if(v.id_posisi != null) {
                        var posisi = v.id_posisi+'|'+v.posisi;
                        // $("#posisi").removeAttr("multiple");
                        // var posisi = posisi.split(",");
                        $("#posisi").val(posisi).trigger("change");
                    }
                    
                    
                    if(v.pabrik != null) {
                        var pabrik = v.pabrik;
                        var pabrik = pabrik.split(",");
                    }

                    $("#pabrik").select2('destroy');
                    if(v.multiple_plan == 0){
                        $('#label_checkall').hide();
                        $('#pabrik').removeAttr('multiple','multiple');
                        $("#pabrik").select2();
                        $("#pabrik").val(pabrik).trigger("change");                       
                    } else if(v.multiple_plan == 1){
                        $('#label_checkall').show();
                        $('#pabrik').attr('multiple','multiple');
                        $("#pabrik").select2();
                        $("#pabrik").val(pabrik).trigger("change");   
                    }
                    /*$("#pabrik").select2('destroy');
                    $("#pabrik").select2();
                    $("#pabrik").val(pabrik).trigger("change");*/
                    // console.log(v.id_pica_role+"|"+v.id_pica_jenis_temuan);

                    // $("#posisi").val('1').trigger('change');
                    // $("select[name='if_decline']").val(v.if_decline).trigger('change');
					$("input[name='id_roleposisi']").val(v.id_pica_role_posisi);                    
					$("#btn-new").show();
				});
				
			}
		});
    });

    $(document).on("change", "#nama_role", function(e){
        var data        = ($(this).val()).split('|');
        var id_role     = data[0];
        var id_temuan   = data[1];
        // var mode        = $('#id_roleposisi').val() != undefined ? 'edit' : 'insert';
        // console.log(data);

        $.ajax({
            url: baseURL+'pica/master/get/role_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_role : id_role
            },
            success: function(data){

                // console.log(data.data)+"BB";
                // $(".title-form").html("Edit jenis report Pica");
                $.each(data.data, function(i, v){
                // console.log(v.multiple_plan);
                // if(mode == 'insert') {
                    $("#pabrik").select2('destroy');
                    if(v.multiple_plan == 0){
                        $('#label_checkall').hide();
                        $('#pabrik').removeAttr('multiple','multiple');
                        $("#pabrik").select2();
                        $("#pabrik").val('1').trigger('change');                       
                    } else if(v.multiple_plan == 1){
                        $('#label_checkall').show();
                        $('#pabrik').attr('multiple','multiple');
                        $("#pabrik").select2();
                        $("#pabrik").val('1').trigger('change');    
                    }
                // }
                //     // console.log(v.id_pica_role)+"AA";
                //     $("input[name='jenis_report']").val(v.jenis_report);  
                //     $("input[name='duedate']").val(v.lama_duedate);  
                //     $("select[name='jenis_temuan']").val(v.id_pica_jenis_temuan+'|'+v.jenis_temuan).trigger('change');                    
                //     // if(v.id_posisi != null) {
                //     //     var posisi = v.id_posisi;
                //     //     $("#posisi").removeAttr("multiple");
                //     //     // var posisi = posisi.split(",");
                //     //     $("#posisi").val(posisi).trigger("change");
                //     // }
                    
                //     // console.log(v.responder_id)
                //     if(v.responder != null) {
                //         var responder = v.responder_id;
                //         var responder = responder.split(",");
                //     }
                //     $("#responder").val(responder).trigger("change");

                //     // if(v.verificator != null) {
                //     //     var verificator = v.verificator_id;
                //     //     var verificator = verificator.split(",");
                //     // }
                //     // $("#verificator").val(verificator).trigger("change");
                    

                //     // $("#posisi").val('1').trigger('change');
                //     // $("select[name='if_decline']").val(v.if_decline).trigger('change');
                //     $("input[name='id_jenisreport']").val(v.id_pica_jenis_report);                    
                //     $("#btn-new").show();
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
                        url: baseURL+'pica/master/set/roleposisi',
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
            url: baseURL+'pica/master/pica_roleposisi',
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
                "data": "posisi",
                "name" : "posisi",
                "width": "20%",
                "render": function (data, type, row) {
                   
                    // label active
                    if(row.na == "n" && row.del == "n") {var label_active = '<span class="label label-success">ACTIVE</span>';}
                    else if(row.na == "y" && row.del == "n") {var label_active = '<span class="label label-danger">NOT ACTIVE</span>';}
                    else if(row.del == "y") {var label_active = '<span class="label label-danger">DELETED</span>';}
                    // result
                    var result = row.posisi+"<br>"+label_active;
                    return result;
                }
            },

            
            {
                "data": "nama_role",
                "name" : "nama_role",
                "width": "20%",
                "render": function (data, type, row) {
                   
                    // result
                    var role = row.nama_role;
                    return role;
                }
            },

            {
                "data": "nama_temuan",
                "name" : "nama_temuan",
                "width": "20%",
                "render": function (data, type, row) {
                   
                    // result
                    var nama_temuan = row.nama_temuan;
                    return nama_temuan;
                }
            },

            {
                "data": "pabrik",
                "name" : "pabrik",
                "width": "20%",
                "render": function (data, type, row) {
                    var plant = row.pabrik;
                    if(plant != null){
                        var pabrik = plant.split(",");
                        var result = '';
                        for(var i = 0; i < pabrik.length; i++)
                        {
                            if(pabrik[i] != ''){
                                result += '<button class="btn btn-sm btn-info btn-role">'+pabrik[i]+'</button>';
                            }
                        } 
                    } else {
                        result = "";
                    }
                    return result;
                }
            },

            
            {
                // "data": "tbl_inv_aset.id_aset",
                "data": "id_pica_role_posisi",
                "name" : "id_pica_role_posisi",
                "width": "12%",
                "render": function (data, type, row) {
                    var action = "";
                    if(row.na == 'n'){
                        action = "<li><a href='javascript:void(0);' class='edit' data-edit='"+row.id_pica_role_posisi+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
                                +"<li><a href='javascript:void(0);' class='nonactive' data-nonactive='"+row.id_pica_role_posisi+"'><i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                                +"<li><a href='javascript:void(0);' class='delete' data-delete='"+row.id_pica_role_posisi+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                        
                    }
                    if(row.na == 'y'){
                        action = "<li><a href='javascript:void(0);' class='setactive' data-setactive='"+row.id_pica_role_posisi
                                +"'><i class='fa fa-check'></i> Set Aktif</a></li>";
                    }

                    var output = "<div class='input-group-btn'>"
                                +"<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>"
                                +"<ul class='dropdown-menu pull-right'>"
                                +action 
                                +"</ul></div>"
                    
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