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
	
    	var jenis_temuan 	= $("#jenis_temuan").val();
    	// var isproses 		= $("input[name='isproses']").val();
		var empty_form 		= validate('.form-master-temuan');
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		// $("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-temuan")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'pica/master/save/temuan',
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
    	var id_temuan	= $(this).data("edit");
    	// console.log(id_temuan);
    	$.ajax({
    		url: baseURL+'pica/master/get/temuan',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_temuan : id_temuan
			},
			success: function(data){
				// console.log(data);
				// console.log('success');
				$(".title-form").html("Edit Jenis Temuan Pica");
                $("input[name='val_departemen_edit']").val("");
				$.each(data.data, function(i, v){
					// console.log(v);
                    $("input[name='jenis_temuan']").val(v.jenis_temuan);					
                    $("input[name='kode_temuan']").val(v.kode_temuan);					
					$("input[name='id_temuan']").val(v.id_pica_jenis_temuan);    
					$("select[name='requestor']").val(v.requestor).trigger('change');                
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
		var id 					= $(this).data($(this).attr("class"));
		var type 				= $(this).attr("class");
		var text 				= '';
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
			    		url: baseURL+'pica/master/set/temuan',
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
            url: baseURL+'pica/master/pica_temuan',
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
                "data": "tbl_pica_jenis_temuan.jenis_temuan",
                "name" : "jenis_temuan",
                "width": "20%",
                "render": function (data, type, row) {
                    
                    // label active
                    if(row.na == "n" && row.del == "n") {var label_active = '<span class="label label-success">ACTIVE</span>';}
                    else if(row.na == "y" && row.del == "n") {var label_active = '<span class="label label-danger">NOT ACTIVE</span>';}
                    else if(row.del == "y") {var label_active = '<span class="label label-danger">DELETED</span>';}
                    // result
                    var jenis_temuan = row.jenis_temuan+"<br>"+label_active;
                    return jenis_temuan;
                }
            },

            {
                "data": "tbl_pica_jenis_temuan.requestor",
                "name" : "requestor",
                "width": "20%",
                "render": function (data, type, row) {
                    
                    var requestor = row.requestor;
                    return requestor;
                }
            },

            {
                "data": "tbl_pica_jenis_temuan.kode_temuan",
                "name" : "kode_temuan",
                "width": "20%",
                "render": function (data, type, row) {
                    
                    var kode_temuan = row.kode_temuan;
                    return kode_temuan;
                }
            },
            
            {
                // "data": "tbl_inv_aset.id_aset",
                "data": "tbl_pica_jenis_temuan.id_pica_jenis_temuan",
                "name" : "id_pica_jenis_temuan",
                "width": "12%",
                "render": function (data, type, row) {
                    var action = "";
                    if(row.na == 'n'){
                        action = "<li><a href='javascript:void(0);' class='edit' data-edit='"+row.id_pica_jenis_temuan+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
                              +"<li><a href='javascript:void(0);' class='nonactive' data-nonactive='"+row.id_pica_jenis_temuan+"'><i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                              +"<li><a href='javascript:void(0);' class='delete' data-delete='"+row.id_pica_jenis_temuan+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                        
                    }
                    if(row.na == 'y'){
                        action = "<li><a href='javascript:void(0);' class='setactive' data-setactive='"+row.id_pica_jenis_temuan
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