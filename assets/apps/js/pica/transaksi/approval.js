/*
@application    : PICA
@author         : Airiza Yuddha (7849)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function(){

   	//show data
    datatables_ssp();
    
    //edit form
    $(document).on("click", ".edit", function(e){
        var id_header           = $(this).data("edit");
        localStorage.clear();
        $.ajax({
            url: baseURL+'pica/trans/pica_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_header   : id_header,
                all         : 'only active',
                normal      : 'normal',
                type        : 'view'
            },
            success: function(data){
                console.log(data)+"BB";
                console.log(level_user);
                $.each(data, function(i, v){
                    var url             = baseURL+'pica/trans/input/pica';
                    var temuan_split    = (v.temuan).split('-');
                    var number_split    = (v.number).split('/');
                    var tanggal         = (v.date_from.replace('-','.'));
                    var temuan          = v.id_pica_jenis_temuan+'|'+temuan_split[0]+'|'+number_split[1]+'|'+v.requestor;
                    
                    // get otorisasi 
                    $.ajax({
                        url: baseURL+'pica/trans/pica_data_otorisasi',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            posisi      : login_posisi,
                            id_temuan   : v.id_pica_jenis_temuan,
                        },
                        success: function(data){
                            console.log(data);
                            var level = "";
                            $.each(data, function(ind, val_oto){

                                localStorage.setItem('level_user', val_oto.level );
                                localStorage.setItem('if_approve', val_oto.if_approve );
                                localStorage.setItem('if_decline', val_oto.if_decline );
                                level = val_oto.level;
                                
                            });
                            if(v.date_prod != null){
                                var date_prod_split = (v.date_prod).split('-');
                                var date_prod       =  date_prod_split[1]+'.'+date_prod_split[2]+'.'+date_prod_split[0];
                            } else {
                                var date_prod       = "";
                            }
                            localStorage.setItem('id_temuan', v.id_pica_jenis_temuan );
                            localStorage.setItem('temuan', temuan );
                            localStorage.setItem('jenis_report', v.jenis_report);
                            localStorage.setItem('id_pica_kategori', v.id_pica_kategori);
                            localStorage.setItem('buyer', v.buyer);
                            localStorage.setItem('jumlah_baris', v.jumlah_baris);
                            localStorage.setItem('pabrik', v.pabrik);
                            localStorage.setItem('tanggal', v.date_from);
                            localStorage.setItem('number', v.number);
                            localStorage.setItem('id_pica_transaksi_header', v.id_pica_transaksi_header);
                            localStorage.setItem('id_header', v.id_header);
                            // localStorage.setItem('detail_form', v.detail_form);

                            localStorage.setItem('si', v.si);
                            localStorage.setItem('so', v.so);
                            localStorage.setItem('lot', v.lot);
                            localStorage.setItem('pallet', v.pallet);
                            localStorage.setItem('date_prod', date_prod);
                            localStorage.setItem('desc', v.desc);
                            localStorage.setItem('verificator_posisi', v.verificator_posisi);
                            localStorage.setItem('verificator', v.verificator);
                            localStorage.setItem('pica_file', v.pica_file);
                            localStorage.setItem('next_nik', v.next_nik);
                            localStorage.setItem('pica_status', v.pica_status);
                            if(level > 1){
                                localStorage.setItem('mode', 'edit');
                            } else if(level == 1){
                                localStorage.setItem('mode', 'response');
                            }
                            
                        },
                        complete: function(){
                            window.location.href    = url;
                        }
                    });
                });               
            }
        });
    });

    //detail form
    $(document).on("click", ".detail", function(e){
        var id_header           = $(this).data("detail");
        localStorage.clear();
        $.ajax({
            url: baseURL+'pica/trans/pica_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_header   : id_header,
                all         : 'only active',
                normal      : 'normal',
                type        : 'view'
            },
            success: function(data){
                console.log(data)+"BB";
                
                $.each(data, function(i, v){
                    var url             = baseURL+'pica/trans/input/pica';
                    var temuan_split    = (v.temuan).split('-');
                    var number_split    = (v.number).split('/');
                    var tanggal         = (v.date_from.replace('-','.'));
                    var temuan          = v.id_pica_jenis_temuan+'|'+temuan_split[0]+'|'+number_split[1]+'|'+v.requestor;

                    // get otorisasi
                    $.ajax({
                        url: baseURL+'pica/trans/pica_data_otorisasi',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            posisi      : login_posisi,
                            id_temuan   : v.id_pica_jenis_temuan,
                        },
                        success: function(data){
                            $.each(data, function(ind, val_oto){

                                localStorage.setItem('level_user', val_oto.level );
                                localStorage.setItem('if_approve', val_oto.if_approve );
                                localStorage.setItem('if_decline', val_oto.if_decline );

                                if(v.date_prod != null){
                                    var date_prod_split = (v.date_prod).split('-');
                                    var date_prod       =  date_prod_split[1]+'.'+date_prod_split[2]+'.'+date_prod_split[0];
                                } else {
                                    var date_prod       = "";
                                }
                                localStorage.setItem('id_temuan', v.id_pica_jenis_temuan );
                                localStorage.setItem('temuan', temuan );
                                localStorage.setItem('jenis_report', v.jenis_report);
                                localStorage.setItem('id_pica_kategori', v.id_pica_kategori);
                                localStorage.setItem('buyer', v.buyer);
                                localStorage.setItem('jumlah_baris', v.jumlah_baris);
                                localStorage.setItem('pabrik', v.pabrik);
                                localStorage.setItem('tanggal', v.date_from);
                                localStorage.setItem('number', v.number);
                                localStorage.setItem('id_pica_transaksi_header', v.id_pica_transaksi_header);
                                localStorage.setItem('id_header', v.id_header);
                                // localStorage.setItem('detail_form', v.detail_form);
                                localStorage.setItem('si', v.si);
                                localStorage.setItem('so', v.so);
                                localStorage.setItem('lot', v.lot);
                                localStorage.setItem('pallet', v.pallet);
                                localStorage.setItem('date_prod', date_prod);
                                localStorage.setItem('desc', v.desc);
                                localStorage.setItem('verificator_posisi', v.verificator_posisi);
                                localStorage.setItem('verificator', v.verificator);
                                localStorage.setItem('pica_file', v.pica_file);
                                localStorage.setItem('mode', 'detail');
                                localStorage.setItem('next_nik', v.next_nik);
                                localStorage.setItem('pica_status', v.pica_status);
                                localStorage.setItem('approval', 1);
                                
                            });
                        },
                        complete: function(){
                            window.location.href    = url;
                        }
                    });
                });               
            }
        });
    });


    // $(document).on("click", "#bacButton", function(e){
    // 	//clear localstorage
    //     localStorage.clear();
    //     var url = baseURL+'pica/trans/data';
    //     window.location.href = url;
    // });

	// get temporary file name for preview
	$(document).on("change.bs.fileinput", ".fileinput", function(e){
		readURL($('input[type="file"]',$(this))[0], $('.fileinput-zoom',$(this)));
	    // console.log($('input[type="file"]',$(this))[0]);
	});
    
    //open page for input data     
    $(document).on("click", "#add_template_button", function(e){
    	//clear localstorage
        localStorage.clear();
        var url = baseURL+'pica/trans/input/pica';
        window.location.href = url;
    });

    // set approval
    // $(document).on("click", "#appButton", function(e){
    //     var confirm_submit   	= "Apakah anda yakin ingin submit data pica ?";
    //     var confirm_approval    = "Apakah anda yakin ingin approve data pica ?";
    //     var type                = $(this).attr("class");
    //     type 					= type.split(' ');
    //     var data_pica           = ($(this).data(type[2])).split('|');
    //     var id 					= data_pica[0];
    //     var data 				= data_pica[1];
    //     var text                = '';
    //     if(type == 'submit') {
    //         text = confirm_submit;
    //     } else if(type == 'approval') {
    //         text = confirm_approval; 
    //     }
    //     console.log(" id ="+id+" type ="+type[2]+" pica status ="+data);
    //     kiranaConfirm(
    //         {     
    //             title: "Kiranaku",
    //             text: text,
    //             dangerMode: true,
    //             useButton: null,
    //             showConfirmButton: true,
    //             showCancelButton: true,
    //             confirmButtonText: "OK",
    //             successCallback: function () {
    //                 $.ajax({
    //                     url: baseURL+'pica/trans/set/approval',
    //                     type: 'POST',
    //                     dataType: 'JSON',
    //                     data: {
    //                         id      : id,  
    //                         type    : type,
    //                         data 	: data
    //                     },
    //                     success: function(data){
    //                         if(data.sts == 'OK'){
    //                             kiranaAlert(data.sts, data.msg);
    //                         }else{
    //                             kiranaAlert(data.sts, data.msg, "error", "no");
    //                         }
    //                     }
    //                 });
    //             },
    //         }
    //     );
    // });
});

function readURL(input, targetPreview) {

    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            targetPreview.attr('href', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

// hit delete detail 
function onclick_delete_detail(baris){
    
    var list_delete_all 	= null;
    var list_delete_detail 	= $('#id_detail_'+baris).val();
    var list_delete 		= $('#id_delete_hidden').val();

    if(list_delete == 0){
    	list_delete_all = list_delete_detail;
    } else {
    	list_delete_all = list_delete+''+list_delete_detail;
    }
    // set list id to delete 
    $('#id_delete_hidden').val(list_delete_all);

    // delete tampilan per baris
    $('.detail_'+baris).html('');

    var baris_hidden = baris - 1;
    // set baris hiden value
    $('#baris_hidden').val(baris_hidden);
    // set jumlah baris value form header
    $('#jumlah_baris_fieldname').val(baris_hidden);
    // set button action delete detail 
    $('#action_delete_'+baris_hidden).html('<div class="legend2 btn" onclick=\'onclick_delete_detail("'+baris_hidden+'")\'>X</div>');
}

function datatables_ssp(){
    // var leveluser_sess  = level_user_sess.replace('[',"");
    // leveluser_sess      = leveluser_sess.replace(']',"");
    // leveluser_sess      = leveluser_sess.split('","');
    // data_lvl            = [];
    // // get otorisasi / level per finding
    // $.each(leveluser_sess, function(i, v){
    //     var dt = (v.replace('"',"")).split('|');
    //     console.log(dt);
    //     data_lvl.push(dt[0]+'_'+dt[3]);   
    // }); 
    // console.log(data_lvl);
    // data_leveluser      = data_leveluser.split('|');
    // var leveluser_sess  = 
    // var filterdivisi        = $("#filterdivisi").val();
    // var filterjenisakses    = $("#filterjenisakses").val();
    // var filterstataktif     = $("#filterstataktif").val();
    // var filterjenisijin     = $("#filterjenisijin").val();
    var filternext_nik         = login_nik;
    var type                   = 'approval';
    
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
            url: baseURL+'pica/trans/pica_data_approval',
            type: 'POST',
            data: {
                // filterdivisi        : filterdivisi,
                // filterjenisakses    : filterjenisakses,
                type                : type,
                filternext_nik      : filternext_nik,
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
                "data": "number , del, na",
                "name" : "number",
                "width": "20%",
                "render": function (data, type, row) {
                    console.log(data , type, row);
                    // label active
                    if(row.na == "n" && row.del == "n") {var label_active = '<span class="label label-success">ACTIVE</span>';}
                    else if(row.na == "y" && row.del == "n") {var label_active = '<span class="label label-danger">NOT ACTIVE</span>';}
                    else if(row.del == "y") {var label_active = '<span class="label label-danger">DELETED</span>';}
                    // result
                    var result = row.number+"<br>"+label_active;
                    return result;
                }
            },

            {
                "data": "date_from",
                "name" : "date_from",
                "width": "20%",
                "render": function (data, type, row) {
                    var date_from = row.date_from;
                    return date_from;
                }
            },
            {
                "data": "pabrik",
                "name" : "pabrik",
                "width": "20%",
                "render": function (data, type, row) {
                    var pabrik = row.pabrik;
                    return pabrik;
                }
            },

            {
                "data": "jenis_report",
                "name" : "jenis_report",
                "width": "20%",
                "render": function (data, type, row) {
                    var jenis_report = row.jenis_report;
                    return jenis_report;
                }
            },
            
            {
                "data": "temuan",
                "name" : "temuan",
                "width": "20%",
                "render": function (data, type, row) {
                    var temuan = row.temuan;
                    return temuan;
                }
            },


            {
                "data": "buyer",
                "name" : "buyer",
                "width": "20%",
                "render": function (data, type, row) {
                    if(row.buyer == '0' ) {var buyer = 'Tidak ada buyer';} else {var buyer = row.buyer;}
                    return buyer;
                }
            },

            // {
            //     "data": "pica_status,role_posisi ",
            //     "name" : "pica_status",
            //     "width": "30%",
            //     "render": function (data, type, row) {
            //         var status = "";
            //         if(row.pica_status == 'Finish' ){
            //             var status = '<div class="label label-success">FINISH</div>';
            //         } else if(row.pica_status == null ) {
            //             var status = '<div class="label label-default">Draft</div>';
            //         } else {
            //             var status = '<div class="label label-warning">ON PROGRESS</div><div>Sedang diproses di '+row.role_posisi+'</div>';
            //         }                 
            //         return status;
            //     }
            // },

            {
                // "data": "tbl_inv_aset.id_aset",
                "data": "id_pica_transaksi_header , pica_status",
                "name" : "id_pica_transaksi_header",
                "width": "12%",
                "render": function (data, type, row) {
                    var action 		= "";
                    // var actionedit 	= baseURL+'pica/trans/input/pica';
                    if(row.na == 'n'){
                        // console.log(row.id_pica_jenis_temuan);
                        // var compareleveltemuan  = row.id_pica_jenis_temuan+'_'+row.nama_role;

                        var btn_respond     = (level_user < 3 )? "<li><a href='javascript:void(0);' class='edit' data-edit='"+row.id_pica_transaksi_header+"'><i class='fa fa-pencil-square-o'></i> Respon </a></li>" : "";
                        var btn_detail      = (level_user >= 3 )? "<li><a href='javascript:void(0);' class='detail' data-detail='"+row.id_pica_transaksi_header+"'><i class='fa fa-pencil-square-o'></i> Detail</a></li>" : ""; 
                        action      = btn_respond + btn_detail
                        		      
                              	// +"<li><a href='#' class='nonactive' data-nonactive='"+row.id_pica_transaksi_header+"'><i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                              	// +"<li><a href='#' class='delete' data-delete='"+row.id_pica_transaksi_header+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                        
                    }
                    if(row.na == 'y'){
                        action = "";
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

//function pecah array / remove array with specific value
Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};