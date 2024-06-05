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

    // if($('input[name=id_hide]').val() == '0'){
    if(localStorage.getItem('temuan') == undefined){
        console.log('insert' , $('input[name=id_hide]').val());
        var valtriger = $('#temuan_fieldname').val();
        // set selected on index 1
        $('select#temuan_fieldname option:nth-child(1)').attr('selected', true);
        $("select#temuan_fieldname").change(changeTemuan(valtriger));
        // console.log($('#temuan_fieldname').val());
    }
 
    //show data
    datatables_ssp();

    
    //submit form
    $(document).on("click", "button[name='action_btn']", function(e){
        localStorage.clear();
        var empty_form      = validate('.form-master-template');
        if(empty_form == 0){
            var isproses        = $("input[name='isproses']").val();
            if(isproses == 0){
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-master-template")[0]);
                
                $.ajax({
                    url: baseURL+'pica/master/save/templatereport',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data){
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                var url = baseURL+'pica/master/template';
                                window.location.href = url;
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
        var id_header   = $(this).data("edit");
        $.ajax({
            url: baseURL+'pica/master/get/templatereport_normal',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_header : id_header
            },
            success: function(data){
                $.each(data, function(i, v){
                    var url                 = baseURL+'pica/master/input/template';
                    var temuan_split= (v.temuan).split('-');
                    var temuan      = v.id_pica_jenis_temuan+'|'+temuan_split[0];
                    localStorage.setItem('temuan', temuan );
                    localStorage.setItem('jenis_report', v.jenis_report);
                    localStorage.setItem('buyer', v.buyer);
                    localStorage.setItem('jumlah_tipe', v.jumlah_tipe);
                    localStorage.setItem('id_pica_template_header', v.id_pica_template_header);
                    localStorage.setItem('detail_form', v.detail_form);
                    
                    window.location.href    = url;
                    console.log(temuan)+"AA";
                });
            }
        });
    });

    var temuan                  = localStorage.getItem('temuan');
    var jenis_report            = localStorage.getItem('jenis_report');
    var buyer                   = localStorage.getItem('buyer');
    var jumlah_tipe             = localStorage.getItem('jumlah_tipe');
    var id_pica_template_header = localStorage.getItem('id_pica_template_header');
    var detail_form             = localStorage.getItem('detail_form');
    
    // set data for edit
    if(id_pica_template_header !== undefined && id_pica_template_header !== null ){
        $("select[name='temuan_fieldname']").val(temuan).trigger('change');
        // trigger change temuan
        $("select#temuan_fieldname").change(changeTemuan(temuan,jenis_report));
        // $("select[name='jenis_report_fieldname']").val(jenis_report).trigger('change');
        $("select[name='buyer_fieldname']").val(buyer).trigger('change');
        $("input[name='jumlah_baris_fieldname']").val(jumlah_tipe);
        $('#id_hide').val(id_pica_template_header);
        
        var x = jumlah_tipe -1;
        $('#baris_hidden').val(0);
        var n = 1;
        for(var i=0; i < jumlah_tipe; i++){
            var field_form  = "";
            var field_form2 = "";
            var field_value = "";
            $.ajax({
                url: baseURL+'pica/master/get/detail_form',
                type: 'POST',
                dataType: 'JSON',
                success: function(data){
                    var baris       = $('#baris_hidden').val();
                    baris++;
                    $('#jumlah_baris_fieldname').val(baris);
                    $('#baris_hidden').val(baris);
                    var field_form  = "";
                    var field_form2 = "";
                    // $(".title-form").html("Edit jenis report Pica");
                    $.each(data, function(i, v){
                        var jumlah_data1        =Math.round((data.length)/2);
                        var id_name_chkbox      = v.nama_form+'_'+baris;
                        var id_name_textfield   = v.nama_form+'_text_'+baris;
                        if((i+1) <= jumlah_data1){
                            field_form += '<div class="form-group col-sm-12">'
                                        +'    <div class="col-sm-12 checkbox">'
                                        +'        <label for="'+v.nama_form+'" >'
                                        +'            <input class=" pull-left" type="checkbox" name="'+id_name_chkbox+'" id="'+id_name_chkbox+'" for="'+id_name_textfield+'"'
                                        +'                    onclick=\'onclick_chkbox("'+id_name_textfield+'","'+id_name_chkbox+'","id_form_'+baris+'");\' '
                                        +'                    value="'+v.id_pica_mst_input+'|'+v.nama_form+'"  >'
                                        +            v.desc_form
                                        +'        </label>'
                                        +'    </div>'
                                        +'    <div class="col-sm-11 pull-right" >'
                                        +'        <input type="text" class="form-control input-xxlarge pull-right" name="'+id_name_textfield+'" id="'+id_name_textfield+'" width="100%" readonly="readonly" '
                                        +'            style=" background: #d1c0c0;">'
                                        +'    </div>'
                                        +'</div>';
                        } else if((i+1) > jumlah_data1) {
                            field_form2 += '<div class="form-group col-sm-12">'
                                        +'    <div class="col-sm-12 checkbox">'
                                        +'        <label for="'+v.nama_form+'" >'
                                        +'            <input class=" pull-left" type="checkbox" name="'+id_name_chkbox+'" id="'+id_name_chkbox+'" for="'+id_name_textfield+'"'
                                        +'                    onclick=\'onclick_chkbox("'+id_name_textfield+'","'+id_name_chkbox+'","id_form_'+baris+'");\' '
                                        +'                    value="'+v.id_pica_mst_input+'|'+v.nama_form+'"  >'
                                        +            v.desc_form
                                        +'        </label>'
                                        +'    </div>'
                                        +'    <div class="col-sm-11 pull-right" >'
                                        +'        <input type="text" class="form-control input-xxlarge pull-right" name="'+id_name_textfield+'" id="'+id_name_textfield+'" width="100%" readonly="readonly" '
                                        +'            style=" background: #d1c0c0;">'
                                        +'    </div>'
                                        +'</div>';
                        }
                        
                    });

                    var field_value     = "";
                        field_value += '<div class="detail_'+baris+'" ><div class="col-sm-12">'
                                        +'  <fieldset class="fieldset-success">'
                                        +'      <legend>Tipe '+baris+' </legend>'
                                        +'      <div class="action_delete" id="action_delete_'+baris+'"><div class="legend2 btn" onclick=\'onclick_delete_detail("'+baris+'")\'>X</div></div>'
                                        +'      <div class="row">'
                                        +'          <div class="col-sm-12 form-horizontal">'
                                        +'              <div class="nav-tabs-custom" id="divdetail_'+baris+'">'
                                        +'                  <div class="form-group col-sm-6">'
                                        +                       field_form                      
                                        +'                  </div>'
                                        +'                  <div class="form-group col-sm-6">'
                                        +                       field_form2
                                        +'                  </div>'
                                        +'              <input type="hidden" class="form-control input-xxlarge pull-right" '
                                        +'                name="id_form_'+baris+'" id="id_form_'+baris+'" width="100%" readonly="readonly" '
                                        +'            style=" background: #d1c0c0;">'
                                        +'              </div>'
                                        +'          </div>'
                                        +'      </div>'
                                        +'  </fieldset>'
                                        +'</div></div>'; 
                    
                    $('.action_delete').html('');
                    $('#detail_template').append(field_value);

                    // console.log(detail_form);
                    var dt_detail_form = (detail_form.replace(/^,|,$/g,'')).split(',');
                    // console.log(dt_detail_form[0]);
                    var array_baris='';
                    for(var z=0; z < dt_detail_form.length ; z++){
                        // if(jQuery.inArray('baris'+n, dt_detail_form[z]) !== -1){ //inarray
                        if(dt_detail_form[z].indexOf('baris'+n) != -1 ) {   
                            var valhidden_split         = dt_detail_form[z].split('~');
                            var checked_split           = valhidden_split[0].split('|');
                            var setfield_split          = valhidden_split[1].split('|');
                            var name_detail             = checked_split[1];
                            var desc_detail             = setfield_split[0]
                            $('#'+name_detail+'_text_'+n).val(desc_detail);
                            $('#'+name_detail+'_text_'+n).removeAttr('readonly');
                            $('#'+name_detail+'_text_'+n).css({ background: 'white' });
                            $('#'+name_detail+'_'+n).prop('checked', true);
                            array_baris += valhidden_split[0]+',';   
                            // console.log(z);     finding_text_1  
                        }
                    }
                    var valhidden_detail = array_baris.slice(0, -1);
                    // console.log(valhidden_detail);
                    // console.log('baris '+n+'================================');
                    $('#id_form_'+n).val(valhidden_detail);
                    n++;
                    
                }
            });
        }
        
        //clear localstorage
        // localStorage.clear();
       
    }

    //reload / create new input
    $("#btn-new").on("click", function(e){
        location.reload();
        e.preventDefault();
        return false;
    });


    //open modal for add     
    $(document).on("click", "#add_template_button", function(e){
        localStorage.clear();
        var url = baseURL+'pica/master/input/template';
        window.location.href = url;
    });

    //create template    
    // $(document).on("change", "#jumlah_baris", function(e){
    $(document).on("click", "#add_baris", function(e){
        add_baris_detail();
    });

    // $(document).on("change", "#jumlah_baris", function(e){
    $(document).on("change", "#temuan_fieldname", function(e){
        changeTemuan($(this).val());
        /*var jenis_temuan    = ($(this).val()).split('|');
        var id_temuan       = jenis_temuan[0];
        var nama_temuan     = jenis_temuan[1];
        
        $.ajax({
            url: baseURL+'pica/master/pica_jenisreport_normal',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_temuan      : id_temuan,
                // type    : 'Responder'
            },
            success: function(data){
                console.log(data[0])+"BB"; 
                $('#jenis_report_fieldname').html(''); 
                if(data[0] != undefined){
                    console.log('ada');
                    var varoption = ''; var varvalue = [];
                    $.each(data, function(i, v){
                        if(jQuery.inArray(v.jenis_report,varvalue) === -1){
                            varvalue.push(v.jenis_report);
                            varoption += '<option value="'+v.jenis_report+'" >'+v.jenis_report+'</option>';
                        }                        
                    });
                    $('#jenis_report_fieldname').append(varoption);
                } else {
                    var data_msg = "Mohon lengkapi data master jenis report";
                    swal('Error', data_msg, 'error');    
                } 
            }
            
        });*/
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
                        url: baseURL+'pica/master/set/templatereport',
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

    // click back button
    $(document).on("click", "#bacButton", function(e){
        //clear localstorage
        localStorage.clear();
        window.history.back();
    });

});

 // hit chkbox on detail 
function onclick_chkbox(var_enable,this_id,hidden_formdesc){

    var id_form     = $('#'+this_id).val();
    // var valid_old   = $('#'+hidden_formdesc).val();
    // var valid_now   = valid_old+','+id_form;
    // console.log(hidden_formdesc);
    // console.log(valid_now);

    var list_add = $('#'+hidden_formdesc).val();
    var arr_list = list_add.split(',');
    
    // console.log(spanid);

    if ($('#'+this_id).is(':checked')) { // if checked
        $('#'+var_enable).removeAttr('readonly');
        $('#'+var_enable).css({ background: 'white' });

        if(jQuery.inArray(id_form, arr_list) !== -1){ //inarray
        } else { 
            if(arr_list==""){
                arr_list = id_form;
            } else {
                arr_list += ","+id_form;
            }       
        }
        $('#'+hidden_formdesc).val(arr_list);
        
    } else  {
        $('#'+var_enable).attr('readonly',true);
        $('#'+var_enable).val('');
        $('#'+var_enable).css({ background: "#d1c0c0" });

        // remove data
        if(jQuery.inArray(id_form, arr_list) !== -1){ //inarray
            arr_list.remove(id_form);   
        }
        $('#'+hidden_formdesc).val(arr_list);

    }
}
 // hit delete detail 
function onclick_delete_detail(baris){
    $('.detail_'+baris).html('');
    var baris_hidden = baris - 1;
    // set baris hiden value
    $('#baris_hidden').val(baris_hidden);
    // set jumlah baris value form header
    $('#jumlah_baris_fieldname').val(baris_hidden);
    // set button action delete detail 
    $('#action_delete_'+baris_hidden).html('<div class="legend2 btn" onclick=\'onclick_delete_detail("'+baris_hidden+'")\'>X</div>');
}

// load datatable
function datatables_ssp(){
    // var filterdivisi        = $("#filterdivisi").val();
    // var filterjenisakses    = $("#filterjenisakses").val();
    // var filterstataktif     = $("#filterstataktif").val();
    // var filterjenisijin     = $("#filterjenisijin").val();
    
    $('#sspTable').DataTable().clear().destroy();
    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        return {
            "iStart"        : oSettings._iDisplayStart,
            "iEnd"          : oSettings.fnDisplayEnd(),
            "iLength"       : oSettings._iDisplayLength,
            "iTotal"        : oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage"         : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages"   : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };
    $("#sspTable").dataTable({
       
        ordering    : true,
        aaSorting   : [[0, 'asc']],
        // bLengthChange: false,
        scrollY     : false,
        scrollX     : true,
        bautoWidth  : false,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input')
                .off('.DT')
                .on('input.DT', function () {
                    api.search(this.value).draw();
                });
        },
        oLanguage   : {
                        sProcessing: "Please wait..."
        },
        processing  : true,
        serverSide  : true,
        ajax        : {
                        url     : baseURL+'pica/master/pica_templatereport',
                        type    : 'POST',
                        data    : {
                                    filterbom   : "bom",
                        },
                        error   : function (a, b, c) {
                                    console.log(a);
                                    console.log(b);
                                    console.log(c);
                        }
        },
        
        columns: [

            {
                "data"      : "buyer , del, na",
                "name"      : "buyer",
                "width"     : "15%",
                "render"    : function (data, type, row) {                   
                                // label active
                                if(row.na == "n" && row.del == "n") {var label_active = '<span class="label label-success">ACTIVE</span>';}
                                else if(row.na == "y" && row.del == "n") {var label_active = '<span class="label label-danger">NOT ACTIVE</span>';}
                                else if(row.del == "y") {var label_active = '<span class="label label-danger">DELETED</span>';}
                                if(row.buyer == '0' ) {var buyer = 'Tidak ada buyer';} else {var buyer = row.buyer;}
                                // result
                                var result = buyer+"<br>"+label_active;
                                return result;
                }
            },

            
            {
                "data"      : "temuan",
                "name"      : "temuan",
                "width"     : "20%",
                "render"    : function (data, type, row) {
                                var temuan = row.temuan;
                                return temuan;
                }
            },


            {
                "data"      : "jenis_report ",
                "name"      : "jenis_report ",
                "width"     : "20%",
                "render"    : function (data, type, row) {
                                var jenis_report = row.jenis_report;                    
                                return jenis_report;
                }
            },

            {
                "data"      : "jumlah_tipe",
                "name"      : "jumlah_tipe",
                "width"     : "10%",
                "className" : "text-right",
                "render"    : function (data, type, row) {
                                var jumlah_tipe = row.jumlah_tipe;                    
                                return jumlah_tipe;
                }
            },

            
            {
                // "data": "tbl_inv_aset.id_aset",
                "data"      : "id_pica_template_header",
                "name"      : "id_pica_template_header",
                "width"     : "5%",
                "className" : "text-center",
                "render"    : function (data, type, row) {
                                var action = "";
                                if(row.na == 'n'){
                                    action ="<li><a href='javascript:void(0);' class='edit' data-edit='"+row.id_pica_template_header+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>"
                                            +"<li><a href='javascript:void(0);' class='nonactive' data-nonactive='"+row.id_pica_template_header+"'><i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                                            +"<li><a href='javascript:void(0);' class='delete' data-delete='"+row.id_pica_template_header+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                                    
                                }
                                if(row.na == 'y'){
                                    action = "<li><a href='javascript:void(0);' class='setactive' data-setactive='"+row.id_pica_template_header
                                            +"'><i class='fa fa-check'></i> Set Aktif</a></li>";
                                }

                                var output = "<div class='input-group-btn text-center'>"
                                            +"<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>"
                                            +"<ul class='dropdown-menu pull-right'>"
                                            +   action 
                                            +"</ul></div>"
                                
                                return output;
                }
            }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info        = this.fnPagingInfo();
            if(info) {
                var page    = info.iPage;
                var length  = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });
}

//function add detail
function add_baris_detail(varbaris){
   
    var field_form  = "";
    var field_form2 = "";
    $('.action_delete').html('');
    // $('#detail_template').html('');
    $.ajax({
        url     : baseURL+'pica/master/get/detail_form',
        type    : 'POST',
        dataType: 'JSON',
        
        success : function(data){
                    // console.log(data)+"BB";
                    var baris       = $('#baris_hidden').val();
                    baris++;
                    $('#jumlah_baris_fieldname').val(baris);
                    $('#baris_hidden').val(baris);
                    // $(".title-form").html("Edit jenis report Pica");
                    $.each(data, function(i, v){
                        var jumlah_data1        =Math.round((data.length)/2);
                        var id_name_chkbox      = v.nama_form+'_'+baris;
                        var id_name_textfield   = v.nama_form+'_text_'+baris;

                        if((i+1) <= jumlah_data1){
                            field_form += '<div class="form-group col-sm-12">'
                                            +'    <div class="col-sm-12 checkbox">'
                                            +'        <label for="'+v.nama_form+'" >'
                                            +'            <input class=" pull-left" type="checkbox" name="'+id_name_chkbox+'" id="'+id_name_chkbox+'" for="'+id_name_textfield+'"'
                                            +'                    onclick=\'onclick_chkbox("'+id_name_textfield+'","'+id_name_chkbox+'","id_form_'+baris+'");\' '
                                            +'                    value="'+v.id_pica_mst_input+'|'+v.nama_form+'"  >'
                                            +            v.desc_form
                                            +'        </label>'
                                            +'    </div>'
                                            +'    <div class="col-sm-11 pull-right" >'
                                            +'        <input type="text" class="form-control input-xxlarge pull-right" name="'+id_name_textfield+'" id="'+id_name_textfield+'" width="100%" readonly="readonly" '
                                            +'            style=" background: #d1c0c0;">'
                                            +'    </div>'
                                            +'</div>';
                        } else if((i+1) > jumlah_data1) {
                            field_form2 += '<div class="form-group col-sm-12">'
                                            +'    <div class="col-sm-12 checkbox">'
                                            +'        <label for="'+v.nama_form+'" >'
                                            +'            <input class=" pull-left" type="checkbox" name="'+id_name_chkbox+'" id="'+id_name_chkbox+'" for="'+id_name_textfield+'"'
                                            +'                    onclick=\'onclick_chkbox("'+id_name_textfield+'","'+id_name_chkbox+'","id_form_'+baris+'");\' '
                                            +'                    value="'+v.id_pica_mst_input+'|'+v.nama_form+'"  >'
                                            +            v.desc_form
                                            +'        </label>'
                                            +'    </div>'
                                            +'    <div class="col-sm-11 pull-right" >'
                                            +'        <input type="text" class="form-control input-xxlarge pull-right" name="'+id_name_textfield+'" id="'+id_name_textfield+'" width="100%" readonly="readonly" '
                                            +'            style=" background: #d1c0c0;">'
                                            +'    </div>'
                                            +'</div>';
                        }
                        
                    });
                    var field_value     = "";
                    // for(var i=1; i <= jumlah_baris; i++){
                        field_value += '<div class="detail_'+baris+'" ><div class="col-sm-12">'
                                        +'  <fieldset class="fieldset-success" >'
                                        +'      <legend>Tipe '+baris+'</legend>'
                                        +'      <div class="action_delete " id="action_delete_'+baris+'"><div class="btn legend2" onclick=\'onclick_delete_detail('+baris+')\'>X</div></div>'
                                        +'      <div class="row">'
                                        +'          <div class="col-sm-12 form-horizontal">'
                                        +'              <div class="nav-tabs-custom" id="divdetail_'+baris+'">'
                                        +'                  <div class="form-group col-sm-6">'
                                        +                       field_form                      
                                        +'                  </div>'
                                        +'                  <div class="form-group col-sm-6">'
                                        +                       field_form2
                                        +'                  </div>'
                                        +'              <input type="hidden" class="form-control input-xxlarge pull-right" '
                                        +'                name="id_form_'+baris+'" id="id_form_'+baris+'" width="100%" readonly="readonly" '
                                        +'            style=" background: #d1c0c0;">'
                                        +'              </div>'
                                        +'          </div>'
                                        +'      </div>'
                                        +'  </fieldset>'
                                        +'</div></div>'; 
                       
                    // }
                    $('#detail_template').append(field_value);
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

// on change temuan on ready function
function changeTemuan(param,param2=null){
    if(param != undefined) {
        var jenis_temuan    = param != undefined ? param.split('|') : '' ;
        var id_temuan       = jenis_temuan[0];
        var nama_temuan     = jenis_temuan[1];
    } else {
        var jenis_temuan    = null;
        var id_temuan       = null;
        var nama_temuan     = null;
    }
    var selectedVal = param2 != null ? param2 : '';
    if(jenis_temuan != null){
        $.ajax({
            url: baseURL+'pica/master/pica_jenisreport_normal',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_temuan      : id_temuan,
                // type    : 'Responder'
            },
            success: function(data){
                console.log(data[0])+"BB"; 
                $('#jenis_report_fieldname').html(''); 
                if(data[0] != undefined){
                    console.log('ada');
                    var varoption = ''; var varvalue = [];
                    $.each(data, function(i, v){
                        if(jQuery.inArray(v.jenis_report,varvalue) === -1){
                            varvalue.push(v.jenis_report);
                            var selected = v.jenis_report == selectedVal ? "selected = 'selected'" : '' ;
                            varoption += '<option '+selected+' value="'+v.jenis_report+'" >'+v.jenis_report+'</option>';
                        }                        
                    });
                    $('#jenis_report_fieldname').append(varoption);
                } else {
                    var data_msg = "Mohon lengkapi data master jenis report";
                    swal('Error', data_msg, 'error');    
                } 
            }        
        });
    }
}