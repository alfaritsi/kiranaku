/*
@application    : PICA
@author         : Airiza Yuddha (7849)
@contributor    : 
            1. Airiza Yuddha (7849) 14 oct 2020
                a. modified function datatables_ssp() 
                    - field pica_status decode special entities html code( '&amp;' to '&') 
                b. add function decodeEntities             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function(){

	// if($('input[name=id_hide]').val() == '0'){
    if(localStorage.getItem('temuan') == undefined){
        // console.log('insert' , $('input[name=id_hide]').val());
        // set selected on index 1
        $('select#temuan_fieldname option:nth-child(1)').attr('selected', true);
        var valtriger = $('#temuan_fieldname').val();
        $('#temuan_fieldname').val(valtriger).trigger('change');
        $("select#temuan_fieldname").change(changeTemuan(valtriger));
        $("select#temuan_fieldname").change(changeTemuan_4plant(valtriger));
        $("select#temuan_fieldname").change(changeTemuan_4flow(valtriger));
        // console.log($('#temuan_fieldname').val());
    }

	//export to excel
    $(document).on('click', '#excel_button', function (e) {
		var filter_request_status	= $("#filter_request_status").val();
		var filter_status			= $("#filter_status").val();
		var filter_from				= $("#filter_from").val();
		var filter_to				= $("#filter_to").val();
		// var id_header           	= id_header;
		// console.log(id_header);
        e.preventDefault();
        window.open(
            baseURL + 'pica/trans/excel/'+id_header
        );
    })
    $(document).on("change", ".isSelectAll", function(e){
        if($(".isSelectAll").is(':checked')) {
            $('#filter_pabrik').select2('destroy').find('option').prop('selected', 'selected').end().select2();
            datatables_ssp();
        }else{
            $('#filter_pabrik').select2('destroy').find('option').prop('selected', false).end().select2();
            datatables_ssp();
        }
    });    
    $(document).on("change", ".isSelectAll1", function(e){
        if($(".isSelectAll1").is(':checked')) {
            $('#filter_report').select2('destroy').find('option').prop('selected', 'selected').end().select2();
            datatables_ssp();
        }else{
            $('#filter_report').select2('destroy').find('option').prop('selected', false).end().select2();
            datatables_ssp();
        }
    });
    $(document).on("change", ".isSelectAll2", function(e){
        if($(".isSelectAll2").is(':checked')) {
            $('#filter_temuan').select2('destroy').find('option').prop('selected', 'selected').end().select2();
            datatables_ssp();
        }else{
            $('#filter_temuan').select2('destroy').find('option').prop('selected', false).end().select2();
            datatables_ssp();
        }
    });
    $(document).on("change", ".isSelectAll3", function(e){
        if($(".isSelectAll3").is(':checked')) {
            $('#filter_buyer').select2('destroy').find('option').prop('selected', 'selected').end().select2();
            datatables_ssp();
        }else{
            $('#filter_buyer').select2('destroy').find('option').prop('selected', false).end().select2();
            datatables_ssp();
        }
    });
   	//show data
    // datatables_ssp();
    // console.log(login_ho);
    if(login_ho == 'n'){
    	// console.log(id_gedung);
    	$('#filter_pabrik').val(id_gedung).trigger('change');
    }
    datatables_ssp();
   	if($("#filter_pabrik").val() != null && $("#filter_pabrik").val() != ''){    	
        datatables_ssp();
    }
    // console.log($("#filter_pabrik").val());

    //=======FILTER=======//
    $(document).on("change", "#filter_pabrik, #filter_report, #filter_temuan, #filter_buyer, #filter_no", function(){
    	datatables_ssp();
    });

	//submit form
    $(document).on("click", "button[name='action_btn']", function(e){
    	// console.log('submit');
    	e.preventDefault();
       	var empty_form      			= validate('.form-master-template',true);
       	
       	// var id_pica_transaksi_header = id_pica_transaksi_header;
       	// var pica_status 				= pica_status;
       	// var pabrik 					= pabrik;
       	// var id_temuan 				= id_temuan_local;
       	// if respond - end
       	if(temuan != undefined){
	       	var requestor 				= temuan.split('|');
	       	requestor 					= requestor[3];
	       	var value_button 				= id_pica_transaksi_header+"|"+pica_status+"^"+pabrik+"^"+id_temuan_local+"^"+requestor+"^"+jenis_report;
			var value_if 					= if_approve+'|'+if_decline;
			var value_app 					= ($('#finding_app').val() ).split(',');
			var action_app 					= "submit";

			console.log(value_app);
			if( (jQuery.inArray( "Approve", value_app ) != -1 || jQuery.inArray( '0', value_app ) != -1) || pica_status < 2 || pica_status == "null" ){
				action_app = 'submit';
			} else {
				action_app = 'reject';
				
			}
			// console.log(action_app, value_app);	
			// add value hidden
			$('#type_hide_details').val(action_app);
			$('#if_approve_hide_details').val(if_approve);
			$('#if_decline_hide_details').val(if_decline);
			$('#status_pica_details').val(pica_status);

       	} 
       	// console.log(value_button);
       	// console.log(empty_form);
        if(empty_form == 0){
            var isproses        = $("input[name='isproses']").val();
            if(isproses == 0){
                // $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-master-template")[0]);
                // console.log();
                $.ajax({
                    url: baseURL+'pica/trans/save/pica',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data){
                        if (data.sts == 'OK') {
                        	if(temuan != undefined){
								saveApproval(value_button,value_if,action_app);
								// window.history.back();
							} else {
	                            swal('Success', data.msg, 'success').then(function () {
	                            	$("input[name='isproses']").val(1);
	                                localStorage.clear();
	        						window.history.back();
	                                // console.log('save success');
	                            });
                        	}
                        } else {
                            $("input[name='isproses']").val(0);
                                // console.log('error ');
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
        // localStorage.clear();
        // window.history.back();
        return false;
    });

    //edit form
    $(document).on("click", ".edit", function(e){
        var id_header           = $(this).data("edit");
        localStorage.clear();
        $.ajax({
            url: baseURL+'pica/trans/pica_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_header 	: id_header,
                all 		: 'only active',
                normal 		: 'normal',
                type 		: 'view'
            },
            success: function(data){
                console.log(data)+"BB";
                console.log(level_user);
                $.each(data, function(i, v){
                    var url             = baseURL+'pica/trans/input/pica';
                    var temuan_split 	= (v.temuan).split('-');
                    var number_split 	= (v.number).split('/');
                    var tanggal 		= (v.date_from.replace('-','.'));
                    var temuan      	= v.id_pica_jenis_temuan+'|'+temuan_split[0]+'|'+number_split[1]+'|'+v.requestor;
                    
					// get otorisasi 
					$.ajax({
			            url: baseURL+'pica/trans/pica_data_otorisasi',
			            type: 'POST',
			            dataType: 'JSON',
			            data: {
			                posisi 		: login_posisi,
			                id_temuan 	: v.id_pica_jenis_temuan,
			            },
			            success: function(data){
			            	console.log(data);
			            	$.each(data, function(ind, val_oto){

			            		localStorage.setItem('level_user', val_oto.level );
			            		localStorage.setItem('if_approve', val_oto.if_approve );
			            		localStorage.setItem('if_decline', val_oto.if_decline );

			                    
			                });
			            	if(v.date_prod != null){
			                	var date_prod_split = (v.date_prod).split('-');
			                	var date_prod 		=  date_prod_split[1]+'.'+date_prod_split[2]+'.'+date_prod_split[0];
			                } else {
			                	var date_prod 		= "";
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
		   					localStorage.setItem('mode', 'edit');
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
                id_header 	: id_header,
                all 		: 'only active',
                normal 		: 'normal',
                type 		: 'view'
            },
            success: function(data){
                console.log(data)+"BB";
                
                $.each(data, function(i, v){
                    var url             = baseURL+'pica/trans/input/pica';
                    var temuan_split 	= (v.temuan).split('-');
                    var number_split 	= (v.number).split('/');
                    var tanggal 		= (v.date_from.replace('-','.'));
                    var temuan      	= v.id_pica_jenis_temuan+'|'+temuan_split[0]+'|'+number_split[1]+'|'+v.requestor;

                    // get otorisasi
                    $.ajax({
			            url: baseURL+'pica/trans/pica_data_otorisasi',
			            type: 'POST',
			            dataType: 'JSON',
			            data: {
			                posisi 		: login_posisi,
			                id_temuan 	: v.id_pica_jenis_temuan,
			            },
			            success: function(data){
			            	$.each(data, function(ind, val_oto){

			            		localStorage.setItem('level_user', val_oto.level );
			            		localStorage.setItem('if_approve', val_oto.if_approve );
			            		localStorage.setItem('if_decline', val_oto.if_decline );

			                    
			                });
			            	if(v.date_prod != null){
			                	var date_prod_split = (v.date_prod).split('-');
			                	var date_prod 		=  date_prod_split[1]+'.'+date_prod_split[2]+'.'+date_prod_split[0];
			                } else {
			                	var date_prod 		= "";
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

			            },
                        complete: function(){
                            window.location.href    = url;
                        }
			        });
                });               
            }
        });
    });

    var level_user                  = localStorage.getItem('level_user');
    var if_approve                  = localStorage.getItem('if_approve');
    var if_decline                  = localStorage.getItem('if_decline');
    var id_temuan_local             = localStorage.getItem('id_temuan');
    var temuan                  	= localStorage.getItem('temuan');
    var jenis_report            	= localStorage.getItem('jenis_report');
    var id_kategori            		= localStorage.getItem('id_pica_kategori');
    var buyer                   	= localStorage.getItem('buyer');
    var jumlah_baris            	= localStorage.getItem('jumlah_baris');
    var pabrik 						= localStorage.getItem('pabrik');
    var tanggal                  	= localStorage.getItem('tanggal');
    var number            			= localStorage.getItem('number');
    var si                   		= localStorage.getItem('si');
    var so                   		= localStorage.getItem('so');
    var lot            				= localStorage.getItem('lot');
    var pallet                   	= localStorage.getItem('pallet');
    var date_prod                  	= localStorage.getItem('date_prod');
    var verificator_posisi          = localStorage.getItem('verificator_posisi');
    var verificator          		= localStorage.getItem('verificator');
    var desc            			= localStorage.getItem('desc');
    var pica_file            		= localStorage.getItem('pica_file');
    var id_pica_transaksi_header 	= localStorage.getItem('id_pica_transaksi_header');
    var id_header 					= localStorage.getItem('id_header');
    var mode 						= localStorage.getItem('mode');
    var next_nik 					= localStorage.getItem('next_nik');
    var pica_status 				= localStorage.getItem('pica_status');
    var app_action 					= localStorage.getItem('approval');

   	console.log(mode);
    // var detail_form             = localStorage.getItem('detail_form');
    // console.log(tanggal);
    // console.log(mode , pica_status);
    // console.log(mode);
	// set tombol export & history
    if(mode != 'detail' ){
        $("#hisButton").hide();
        // $("#excel_button").hide();
    }
    if(mode == 'response' || mode == 'edit'){
    	$("#hisButton").show();
    }
    if(mode == null){ 
        $("#excel_button").hide();
    }
    /*if(pica_status != 'Finish'){ 
        $("#excel_button").hide();
    }*/
    // set data for edit
    if(id_pica_transaksi_header !== undefined && id_pica_transaksi_header !== null ){

    	$('#if_approve_hide_details').val(if_approve);
		$('#if_decline_hide_details').val(if_decline);
		$('#status_pica_details').val(pica_status);
		$('#status_pica_act').val(pica_status);
    	var verificator_posisi_split 	= verificator_posisi.split(','); // change to array 
    	if(mode == 'detail'){
        	$("#title_form_all").html('<strong>Detail Data Pica</strong>');
    	} else if(mode == 'edit'){
    		$("#title_form_all").html('<strong>Edit Data Pica</strong>');
    	} else if(mode == 'response'){
    		$("#title_form_all").html('<strong>Response Data Pica</strong>');
    	}
    	 
    	
    	//mode_hidden 
    	$("input[name='mode_hidden']").val(mode);
    	$("#id_number_approval").val(number);
        $('#id_hide').val(id_pica_transaksi_header);
    	$("select[name='temuan_fieldname'] option[value!='"+temuan+"'] ").attr("disabled", true);
    	// set date when edit 
		if(mode == 'edit')
		{
			$("#tanggal_fieldname").removeClass("kiranadatepicker");
			$("#tanggal_fieldname").datepicker("remove");
			// set new datepicker 
			var date 		= new Date(), y = date.getFullYear(), m = date.getMonth();
			var dates 		= new Date();
			var firstDay 	= new Date(y, m, 1);
			var lastDay 	= new Date(y, m + 1, 0);
	        // console.log(firstDay , lastDay);

	        $('#tanggal_fieldname').datepicker({
	            format 		: 'dd.mm.yyyy',
	            autoclose 	: true,
	            startDate 	: firstDay,
	            endDate 	: lastDay,
	            pickTime 	: true,
	        });
		}
		   		
		        
    	// if for file header
        if ( pica_file.match( /(.jpg|.png|.pdf|.zip)/ ) ) { existfile = true; }else{ existfile = false; }
		
		if (existfile == false) {
            let divFileinput = $('#fileinput');
            divFileinput.removeClass('fileinput-exists');
            divFileinput.addClass('fileinput-new');
            divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
        } else {
            let divFileinput = $('#fileinput');            
            // if(tipe_file == 'gambar'){
        	divFileinput.removeClass('fileinput-new');
        	divFileinput.addClass('fileinput-exists');
        	divFileinput.find('.fileinput-zoom').attr('href', baseURL+'assets/file/pica/header/'+ pica_file);
    		divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
        	if ( pica_file.match( /(.zip)/ ) ) {
        		divFileinput.find('.fileinput-zoom').removeAttr('data-fancybox');
        	}		            
        }

        // load all data 
        $.ajax({
            url: baseURL+'pica/trans/pica_data_detail',
            type: 'POST',
            dataType: 'JSON',
            data: {
            	mode 			: mode,
                id 				: id_pica_transaksi_header,
                id_temuan 		: id_temuan_local,
                jenis_report 	: jenis_report,
                pabrik 			: pabrik,
                buyer 			: buyer,
                si 				: si,
                so 				: so,
                lot 			: lot,
                pallet 			: pallet,
                date_prod 		: date_prod
            },
            success: function(data){
                console.log(data);  
             // set opt report 
                var form_report = ""; $('#jenis_report_fieldname').html("");
                $.each(data.opt_report, function(i,v){
	            	form_report += "<option value='"+v.jenis_report+"'>"+v.jenis_report+"</option>"
	            });
	            $('#jenis_report_fieldname').html(form_report);

	         // set opt plant 
                var form_plant = ""; $('#pabrik_fieldname').html("");
                $.each(data.opt_plant, function(i,v){
	            	form_plant += "<option value='"+v.plant+"'>"+v.plant_name+"</option>"
	            });
	            $('#pabrik_fieldname').html(form_plant);

	         // set opt si 
                var form_si = "<option > Pilih no SI</option>"; $('#si_fieldname').html("");
                $.each(data.opt_si, function(i,v){
	            	form_si += "<option value='"+v.no_si+"'>"+v.no_si+"</option>"
	            });
	            $('#si_fieldname').html(form_si);

	         // set opt so 
                var form_so = "<option > Pilih no SO</option>"; $('#so_fieldname').html("");
                $.each(data.opt_so, function(i,v){
	            	form_so += "<option value='"+v.no_so+"'>"+v.no_so+"</option>"
	            });
	            $('#so_fieldname').html(form_so);

	         // set opt lot 
                var form_lot = ""; $('#lot_fieldname').html("");
                $.each(data.opt_lot, function(i,v){
	            	form_lot += "<option value='"+v.no_lot+"'>"+v.no_lot+"</option>"
	            });
	            $('#lot_fieldname').html(form_lot);

	         // set opt pallet 
                var form_pallet = ""; $('#pallet_fieldname').html("");
                $.each(data.opt_pallet, function(i,v){
	            	form_pallet += "<option value='"+v.no_pallet+"'>"+v.no_pallet+"</option>"
	            });
	            $('#pallet_fieldname').html(form_pallet);

	         // set date prod
	         	if( (data.opt_dtprod).length > 0 ){
	         		var dtprod = "";
	         		$.each(data.opt_dtprod, function(i,v){
		            	dtprod_split 	= (v.date_prod).split('-');
		            	dtprod 			= dtprod_split[2]+'.'+dtprod_split[1]+'.'+dtprod_split[0];

		            });
		            $("input[name='tanggal_prod_fieldname']").val(dtprod);
	         		$("input[name='tanggal_prod_fieldname']").removeClass("kiranadatepicker");
	         		$("input[name='tanggal_prod_fieldname']").datepicker( "remove" );
	         	} else {
	         		$("input[name='tanggal_prod_fieldname']").val(date_prod);
	         	}
	        },
            error: function(data){
            	console.log("error");
            },
            complete: function(data){
            	var dataall = data.responseJSON;
           	 // -------- set condition 
           	 // set date when edit 
				if(mode == 'detail' || mode == 'response'){	
					
		    		$("select[name='jenis_report_fieldname'] option[value!='"+jenis_report+"'] ").attr("disabled", true);
		    		$("select[name='kategori_fieldname'] option[value!='"+id_kategori+"'] ").attr("disabled", true);
		    		$("select[name='buyer_fieldname'] option[value!='"+buyer+"'] ").attr("disabled", true);
		    		$("select[name='pabrik_fieldname'] option[value!='"+pabrik+"'] ").attr("disabled", true);
		    		// $("select[name='verificator_fieldname[]'] ").attr("disabled", true).trigger('update');
		    		// $('select').select2("enable", false)
		    		// $.each(verificator_posisi_split, function(i, value_ver){
		    		// 	$("#verificator_fieldname option[value!="+value_ver+"]").attr('disabled',true);//.trigger("chosen:updated");
		    		// $("#verificator_fieldname").attr('disabled',true);
		    		// })
		    		// $("select#temuan_fieldname").prop('disabled', false).filter("[value='1|Complain|CMPLNE|Eksternal']").prop('disabled', true);
		    		$("input[name='number_fieldname']").attr("readonly", true);
		    		
		    		$("select[name='si_fieldname'] option[value!='"+si+"'] ").attr("disabled", true);
		    		$("select[name='so_fieldname'] option[value!='"+so+"'] ").attr("disabled", true);
		    		$("select[name='lot_fieldname'] option[value!='"+lot+"'] ").attr("disabled", true);
		    		$("select[name='pallet_fieldname'] option[value!='"+pallet+"'] ").attr("disabled", true);
		    		$("input[name='tanggal_fieldname']").attr("readonly", true);
		    		$('#tanggal_fieldname').removeClass( "kiranadatepicker" );
		    		$('#tanggal_fieldname').datepicker( "remove" );
		    		$('#tanggal_prod_fieldname').datepicker( "remove" );
		    		$("#def_fieldname").attr("readonly", true);

		    		// hide select tipe template 
		    		$("#divpilih_template").hide();    		
		    		$(".btn-facebook").hide();
		    		// $("select#temuan_fieldname").change(changeTemuan_4plant(temuan,pabrik,'detail'));
		    	}
		    	if(mode == 'edit' && pica_status != 'null'){
		    		$("#divpilih_template").hide(); 	
		    	}


             // --------
             	// get dropdown jenis template{
				// add_baris_detail();
		        $('#tipe_template').html('');
		        $('#action_add_template').html('');
		        // var field_value = "";
		        // var jenis_temuan 	= $('#temuan_fieldname').val(); // 1|complain
		        // var jenis_report 	= $('#jenis_report_fieldname').val(); // Corrective action request 
		        // var buyer 			= $('#buyer_fieldname').val(); // APOLLO
		        var inputvalopt		= "";
		        $.ajax({
		            url: baseURL+'pica/trans/get/pica_normal',
		            type: 'POST',
		            dataType: 'JSON',
		            data: {
		            	jenis_temuan 	: temuan,
		                jenis_report 	: jenis_report,
		                buyer 			: buyer
		            },
		            success: function(data){
		               	console.log(data); 
		                inputvalopt		= "";
		                if(data != null && data != undefined && data != "" ){
		                	$.each(data, function(i, v){
			                	for(var i=1; i <= v.jumlah_tipe; i++){
			                		inputvalopt += '<option value="'+v.id_pica_template_header+'|'+i+'">Tipe Template '+i+'</option>';
			                	}
			                	var field_value 	= '<select class="form-control input-xxlarge " name="tipe_template_fieldname" '
			        						+'id="tipe_template_fieldname" style="width: 100%;"  required="required"> '
			                                + 	inputvalopt
			                                +'</select>';
			                    var field_action 	= '<button type="button" class="btn btn-sm form-control btn-success pull-right" id="exec_add">Tambah Detail</button>';            
			                    $('#tipe_template').append(field_value);
			                    $('#action_add_template').append(field_action);

			                    // ================================ load detail template
			                    var barissplit		= ($('#tipe_template_fieldname').val()) != undefined ? ($('#tipe_template_fieldname').val()).split('|') : 0;
			                    var baris			= ($('#tipe_template_fieldname').val()) != undefined ? barissplit[1] : 0;
			                    var field_form  	= "";
					            var field_value2 	= "";
					            var n 				= 1;
					   
							});	
		                }            
		            
		                var id_pb 			= pabrik;
		        		var option_karyawan = "";
		        		$.ajax({
			                url: baseURL+'pica/trans/get/karyawan',
			                type: 'POST',
			                dataType: 'JSON',
			                data: {
				            	id_pabrik 	: id_pb,
				                // baris 		: baris,
				                // buyer 			: buyer
				            },
			                success: function(data_karyawan){
			                	// console.log(data_karyawan);
			                	
			                	// form_kosong		= form_kosong.replace('varoption',option_karyawan);

			                	$.ajax({
					                url: baseURL+'pica/trans/get/detail_trans',
					                type: 'POST',
					                dataType: 'JSON',
					                data: {
						            	id_header 	: id_header,
						                // baris 		: i,
						                // buyer 			: buyer
						            },
						            beforeSend: function () {
						                var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						                $("body .overlay-wrapper").append(overlay);
						            },
					                success: function(data_detail){
					                    console.log(data_detail)+"BB";
					                    var n = 1;
					                    for(var i=0; i < jumlah_baris; i++){
					                    	
						                    var baris       = $('#baris_hidden').val();
						                    baris++;
						                    $('#jumlah_baris_fieldname').val(baris);
						                    $('#baris_hidden').val(baris);
						                    $('#baris_act').val(baris);
						                    var field_form 			= "";
						                    var field_form2 		= "";
						                    var id_pica_mst_input 	= "";
						                    var label 				= "";
						                    var baris_det			= baris;
						                    var type_input			= "";
						                    var nama_input			= "";
						                    var id_detail			= "";
						     
											
											var arraydata 			= [];
											var brsx 				= 1;
						                    var loop 				= 0;
						                    var varAddClass			= "";
						                    var arraydata_finding	= [];
						                    
						                    $.each(data_detail, function(h, v){
						                    	var option_karyawan = "";
							                	$.each(data_karyawan, function(iKar, val_kary){
							                		var karyawan = (val_kary.nama_karyawan).replace(/\s/g, '');
							                		option_karyawan += "<option varselected"+val_kary.nik+" value='"+val_kary.nik+"'>"+val_kary.nama_karyawan+"</option>";
							                	});
						                    	loop++;
						                    	if(n == v.baris){
						                    		if(brsx != v.baris){
						                    			brsx = v.baris;
						                    			loop = 1;	
						                    		}	
						                    		var arraydata 		= [];
													var jumlah_data1 	= 0;

													if(jQuery.inArray( (v.posisi_finding+'|'+v.nama_posisi_finding+'|'+v.nama_posisi_finding), arraydata_finding) === -1){
														arraydata_finding.push(v.posisi_finding+'|'+v.nama_posisi_finding+'|'+v.nama_posisi_finding);
													}
											
													if(arraydata_finding != undefined && arraydata_finding != "" ){
														var dt_split 			= arraydata_finding[0].split('|');
														var level_finding 		= dt_split[0];
														var namalevel_finding 	= dt_split[2] == "null" ? "" : dt_split[2];
													} else {
														var level_finding 		= 0;
														var namalevel_finding 	= '-';
													}

													// set arraydata sesuai baris
													$.each(data_detail, function(i, v){
														if(v.baris == n ){
															arraydata.push(v);												
														}
													});										 
													
													jumlah_data1=Math.round((arraydata.length)/2); 
						                    		id_pica_mst_input 		+= v.id_pica_mst_input+',';
							                    	label 					+= v.label+',';
							                    	type_input 				+= v.type_input+',';
							                    	nama_input 				+= v.nama_form+',';
							                    	id_detail 				+= v.id_pica_transaksi_detail+',';
							                    	

							                        var id_name_chkbox      = v.nama_form+'_'+baris;
							                        var id_name_textfield   = v.nama_form+'_text_'+baris;
							                        var form_kosong 		= (v.code_form).replace(/'/g,"");
							                        var pica_file_detail 	= 0;
							                        if(v.type_input == "textarea"){
							                        	var form_kosong_class	= form_kosong.replace('varclass',"form-control input-xxlarge pull-right");
							                        	form_kosong_class		= form_kosong_class.replace('varstyle',"resize: none;");
							                        	// form_kosong_class 		= form_kosong_class.replace('varname',v.nama_form+'_'+baris);
							                        } else if(v.type_input == "file") {
							                        	var form_kosong_class	= form_kosong.replace('varclass',"form-control input-xxlarge pull-right");
							                        	// form_kosong_class 		= form_kosong_class.replace('varname',v.nama_form+'_'+baris+'[]');
							                        
							                        } else {
							                        	if(v.nama_form == 'duedate'){
							                        		varAddClass 	= ' kiranadatepicker' ;
							                        		
							                        	} else if(v.nama_form == 'pic'){
							                        		varAddClass 		= ' select2 pic_detail';
							                        	} 
							                        	var form_kosong_class	= form_kosong.replace('varclass',"form-control input-xxlarge pull-right"+varAddClass);
							                        	// form_kosong_class 		= form_kosong_class.replace('varname',v.nama_form+'_'+baris);
							                        	pica_file_detail 		=  v.desc;
							                        }
							                        form_kosong_class 			= form_kosong_class.replace('varname','detail['+baris+']['+loop+'][value]');
							                        // set disable if mode detail , readonly mode response
							                        var var_disable 			= "";
							                        if(mode == 'detail') {
							                        	var_disable = "disabled='disabled'";
							                        } else if (mode == 'response' && v.nama_form=='finding') {
							                        	var_disable = "readonly='readonly'";
							                        } else {
							                        	var_disable = "varother_null";
							                        }
							                        form_kosong_class			= form_kosong_class.replace('varother',var_disable);
							                        var form_valued 			= form_kosong_class.replace('varvalue',v.desc);
							                        var var_disableOther		= "";
							                        
							                        // print option pic
							                        var nm = "";
							                        if(v.nama_form == "pic" ){
							                       		nm = (v.desc).replace(/\s/g, '');	
							                       		option_karyawan 		= option_karyawan.replace('varselected'+nm , "selected='selected'");
							                       		form_valued 			= form_valued.replace('varoption',option_karyawan);
							                       		// $("select[name='detail["+baris+"]["+loop+"][value]']").val(v.desc).trigger('change');						                       		
							                       	}

							                        if(( (pica_status) != '' && pica_status != 'null' && level_finding == level_user) ){
														var_disableOther 		= "";	
													} else {
							                        	var_disableOther 		= "readonly='readonly'";
							                        	form_valued				= form_valued.replace('kiranadatepicker','');
							                        	if(v.type_input == "file"){
							                        		form_valued			= form_valued.replace('varstyle','display:none');	
							                        	}
							                        	
							                        	var nm = "";
								                        if(v.nama_form == "pic" ){								                       		
								                       		form_valued 		= form_valued.replace(/varselected/g, "disabled='disabled'");
								                       	}
													}
													form_valued					= form_valued.replace('varother_null',var_disableOther);
													
													
							                        

							                        // set form if type file 
							                        if(v.type_input == "file" ){
							                        	
							                        	href_det 		= v.desc != null ? baseURL+'assets/file/pica/detail/'+ v.desc : 'javascript:void(0);';
							                        	var string_pdf 	= v.desc != undefined ? (v.desc).indexOf(".pdf") : 0;
							                        	var string_zip 	= v.desc != undefined ? (v.desc).indexOf(".zip") : 0;
							                        	// console.log(string_zip , string_pdf);
							                        	var access_file = (string_zip == -1) && v.desc != null ? '<a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox href="'+href_det+'" ><i class="fa fa-search"></i></a>' :  '<a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" href="'+href_det+'" ><i class="fa fa-search"></i></a>';
							                        	access_file 	= v.desc != null ? access_file : "";
							                        	form_valued = '<div class="fileinput fileinput-exists" id="fileinput" data-provides="fileinput">'
							                        					+'<div class="btn-group btn-sm no-padding" >'
							                        					+access_file
							                        					+'<a class="btn btn-facebook btn-file">'
								                                        +'            			<div class="fileinput-new">Attachment</div>'
								                                        +'            			<div class="fileinput-exists">'
								                                        +'            				<i class="fa fa-edit"></i>'
								                                        +'            			</div>'
							                        					+form_valued+'</a></div></div>';
							                       	} 
							                        // type form | label | type | nama 
							                    	var detail_value = v.id_pica_mst_input+','+v.label+','+v.nama_form;
							                        // if for set location of form input ( left or right side)
							                        if(loop <= jumlah_data1){
							                        	field_form += '<div class="form-group col-sm-12">'
								                                        // +'    <div class="col-sm-4 checkbox">'
								                                        +'        <label class="col-sm-3" for="'+v.label+'" >'				                                                   
								                                        +          v.label
								                                        +'        </label>'
								                                        // +'    </div>'
								                                        +'    <div class="col-sm-8" >'
								                                        +'       <input type="hidden" name="detail['+baris+']['+loop+'][id_detail]" value="'+v.id_pica_transaksi_detail+'">'
								                                        +'       <input type="hidden" name="detail['+baris+']['+loop+'][baris]" value="'+baris+'">'
								                                        +'       <input type="hidden" name="detail['+baris+']['+loop+'][tipe]" value="'+v.type_input+'">'
								                                        +'       <input type="hidden" name="detail['+baris+']['+loop+'][detail_value]" value="'+detail_value+'">'
								                                        +		form_valued
								                                        +'    </div>'
								                                        +'</div>';
								                    } else if(loop > jumlah_data1) {
								                    	field_form2 += '<div class="form-group col-sm-12">'
								                                        // +'    <div class="col-sm-4 checkbox">'
								                                        +'        <label class="col-sm-3" for="'+v.label+'" >'				                                                   
								                                        +          v.label
								                                        +'        </label>'
								                                        // +'    </div>'
								                                        +'    <div class="col-sm-8" >'
								                                        +'       <input type="hidden" name="detail['+baris+']['+loop+'][id_detail]" value="'+v.id_pica_transaksi_detail+'">'
								                                        +'       <input type="hidden" name="detail['+baris+']['+loop+'][baris]" value="'+baris+'">'
								                                        +'       <input type="hidden" name="detail['+baris+']['+loop+'][tipe]" value="'+v.type_input+'">'
								                                        +'       <input type="hidden" name="detail['+baris+']['+loop+'][detail_value]" value="'+detail_value+'">'
								                                        +		form_valued
								                                        +'    </div>'
								                                        +'</div>';
								                    }
								                }

						                    });
											// console.log(arraydata_finding);
											if(arraydata_finding != undefined && arraydata_finding != "" ){
												var dt_split 			= arraydata_finding[0].split('|');
												var level_finding 		= dt_split[0];
												var namalevel_finding 	= dt_split[2] == "null" ? "" : dt_split[2];
											} else {
												var level_finding 		= 0;
												var namalevel_finding 	= '-';
											}
											console.log(level_finding);
											if(jQuery.trim(level_finding) == "Finish"){
												var ket_status_finding 	= "Approval Finish";
											} else {
												var ket_status_finding 	= "Sedang diproses "+namalevel_finding;
											}
											var actiondetail_x 	= level_user == 1 ? "" : "<a href='javascript:void(0)' id='detaildec_"+baris+"' class='decline_detail btn btn-danger' data-action='decline_"+baris+"' title='Decline'><i class='glyphicon glyphicon-remove'></i></a>";
																
											var actiondetail 	= ( (pica_status) != '' && pica_status != 'null' && level_finding == level_user) ?
																"					<div id='divapp_"+baris+"' class='col-sm-12'>"
																+"						<div class='btn-group pull-right'>"
																+"							<a href='javascript:void(0)' id='detailapp_"+baris+"' class='reset_detail btn btn-default' data-action='reset_"+baris+"' title='Reset'><i class='glyphicon glyphicon-refresh'></i></a>"
																+ 							actiondetail_x
																+"							<a href='javascript:void(0)' id='detailapp_"+baris+"' class='approve_detail btn btn-success' data-action='approve_"+baris+"' title='Approve'><i class='glyphicon glyphicon-ok'></i></a>"
																+"						</div>"
																+"					</div>" : "<div id='divapp_"+baris+"' class='col-sm-12'><div class='btn-group pull-right'>"
																+"						<span class='label label-info'>"+ket_status_finding+"</span>"
																+"					</div></div>"
																;

											
											actiondetail 		= pica_status == 'null' ? 
																" 					<div id='divapp_"+baris+"' class='col-sm-12'><div class='btn-group pull-right'>"
																+"						<span class='label label-info'>Draft</span>"
																+"					</div></div>" : actiondetail;
											/*var actiondetail 	= 
																"					<div id='divapp_"+baris+"' class='col-sm-12'>"
																+"						<div class='btn-group pull-right'>"
																+"							<a href='javascript:void(0)' id='detailapp_"+baris+"' class='reset_detail btn btn-default' data-action='reset_"+baris+"' title='Reset'><i class='glyphicon glyphicon-refresh'></i></a>"
																+ 							actiondetail_x
																+"							<a href='javascript:void(0)' id='detailapp_"+baris+"' class='approve_detail btn btn-success' data-action='approve_"+baris+"' title='Approve'><i class='glyphicon glyphicon-ok'></i></a>"
																+"						</div>"
																+"					</div>" */
																;
											
						                    var field_value2     = "";
						                        field_value2 	+= '<div class="detail_'+baris+'" ><div class="col-sm-12">'
						                                        +'  <fieldset class="fieldset-success fieldset_detail" id="fs_'+baris+'">'
						                                        +'      <legend>Baris '+baris+' </legend>'
						                                        +' 		<div class="action_delete" id="action_delete_'+baris+'"><div class="legend2 btn" onclick=\'onclick_delete_detail("'+baris+'")\'>X</div></div>'
						                                        +'              <div class="row" id="divdetail_'+baris+'">'
						                                        +'					<div class="col-sm-6">'
						                                        +               		field_form						
						                                        +'					</div>'
						                                        +'					<div class="col-sm-6">'
						                                        +               		field_form2
						                                        +'					</div>'
						                                        +				actiondetail                                        
						                                        +'              <input type="hidden" name="actionapp_'+baris+'" id="actionapp_'+baris+'" readonly="readonly" '
						                                        +'              <input type="hidden" name="id_pica_mst_input_'+baris+'" id="id_pica_mst_input_'+baris+'" readonly="readonly" '
						                                        +'              <input type="hidden" name="baris_'+baris+'" id="baris_'+baris+'" width="100%" readonly="readonly" '
						                                        +' 				<input type="hidden" name="label_'+baris+'" id="label_'+baris+'" width="100%" readonly="readonly" '
						                                       	+' 				<input type="hidden" name="type_input_'+baris+'" id="type_input_'+baris+'" width="100%" readonly="readonly" '
						                                        +' 				<input type="hidden" name="nama_input_'+baris+'" id="nama_input_'+baris+'" width="100%" readonly="readonly" '
						                                        +' 				<input type="hidden" name="id_detail_'+baris+'" id="id_detail_'+baris+'" width="100%" readonly="readonly"'
						                                       	+'              </div>'
						                                        +'  </fieldset>'
						                                        +'</div></div>'; 
						                       
						                    // }
						                    
						                    $('.action_delete').html('');
						                    $('#detail_template').append(field_value2);
						                    $('#id_pica_mst_input_'+baris).val(id_pica_mst_input);
						                    $('#label_'+baris).val(label);
						                    $('#baris_'+baris).val(baris_det);
						                    $('#type_input_'+baris).val(type_input);
						                    $('#nama_input_'+baris).val(nama_input);
						                    $('#id_detail_'+baris).val(id_detail);

						                     //initial datepicker
						                    $(".kiranadatepicker").datepicker({
								                endDate: ($(this).data("enddate") != null ? $(this).data("enddate") : ''),
								                todayHighlight: true,
								                disableTouchKeyboard: true,
								                format: ($(this).data("format") != null ? $(this).data("format") : "dd.mm.yyyy"),
								                startView: ($(this).data("startview") != null ? $(this).data("startview") : "days"),
								                minViewMode: ($(this).data("minviewmode") != null ? $(this).data("minviewmode") : "days"),
								                autoclose: true
								            });
						                    n++;
						                    $('.select2').select2();
							                
					                    }
					                    // set some button hide if mode detail
					                    if(mode == 'detail') {
					                    	var temuan_split    = temuan.split('|');
					                    	var id_temuan 		= temuan_split[0];
					                    	var requestor 		= temuan_split[3];
					                    	var value_button 	= id_pica_transaksi_header+"|"+pica_status+"^"+pabrik+"^"+id_temuan+"^"+requestor+"^"+jenis_report;
					                    	$(".action_delete").hide();
						                    $("a.btn-file").hide();
						                    $("#addButton").hide();
						                    var login_nik2 = (login_nik == null) ? 0 : login_nik;
						                    // console.log(pica_status);
						                    // console.log('next_nik = '+next_nik+" \nlogin_nik = "+login_nik2+" \npica status = "+pica_status);
						                    // console.log(level_user , pica_status );
						                    // if(pica_status != 'Finish'){
						                    	// if(level_user == pica_status && pica_status >= 2){
							                    // 	$("#appButton").html('Approve');
							                    // 	$("#appButton").removeClass('submit');
							                    // 	$("#appButton").attr('data-approve', value_button);
							                    // 	$("#appButton").removeAttr('data-submit');		                    	
							                    	
							                    // 	$("#rejButton").attr('data-reject', value_button);
							                    // 	$("#data_hide").val(value_button);
							                    // 	if(app_action =='1' ){
							                    // 		$("#rejButton").show();
							                    // 		$("#appButton").show();
							                    // 	}
							                    	
							                    // } else if( (level_user == pica_status && (pica_status < 2 || pica_status == 'null' ) )||(pica_status == 'null' && level_user==2)  ){
							                    	
							                    	$("#appButton").html('Submit');
							                    	$("#appButton").addClass('submit');
							                    	$("#appButton").removeClass('approve');
							                    	$("#appButton").attr('data-submit', value_button);
							                    	$("#appButton").removeAttr('data-approve');
							                    	$("#data_hide").val(value_button);
							                    	if(app_action!='1' && pica_status == 'null')
							                    		$("#appButton").show();
							                    	else if(app_action =='1' && pica_status != 'null'){
							                    		$("#appButton").show();
							                    	}
							                    // }	
						                    // }
						                    
						                }
					                    $("#bacButton").show();

					                    
					                },
					                complete: function(data_detail){
					        //         	console.log(data_detail);
					        //         	// set color detail approval per finding
								    	// var array_app = [];
								    	// $.each( dataall.data_finding, function(i,v){
								    	// 	var act 	= (v.status).trim();
								    	// 	var row_det = v.baris;
								    	// 	var color 	= "";
								    	// 	array_app.push(act);
								    	// 	if(act 		== "Approve"){
								    	// 		color 	= "rgba(0, 255, 76, 0.3)"; 
								    	// 	}else if(act == "Decline"){
								    	// 		color 	= "rgba(255, 141, 76, 0.3)";
								    	// 	}else if(act == 0){
								    	// 		color 	= "rgba(255, 255, 255, 1)";
								    	// 	}
								    	// 	// console.log(act, color , row_det);
								    	// 	// var x = 1;
							      //       	// $('#fs_'+row_det).css("background-color", color );
							      //       	// $('#fs_'+row_det).attr('readonly');
							      //       	act = (act == 0) ? "" : act;
							      //       	// $('#actionapp_'+row_det).val(act) 
									    // });
									    if($('#finding_app').val() == 0 && pica_status != "null"){
									    	$('#addButton').hide();
									    	$('#appButton').hide();
										}
										//proces spiner
										$("body .overlay-wrapper .overlay").remove();
										// console.log(array_app);
									    // $('#finding_app').val(array_app);
									 //    $('#finding_app_act').val(array_app);
					                }

					            });
			                	
			                }
			            	

			            });
		             
					},
					complete: function(data){
						 // $("body .overlay-wrapper .overlay").remove();	
					}
		        });
		    // end get dropdown jenis template }
		    	
	            // $('#so_fieldname').html(form_so);
		       
             // --------
            	$("select[name='temuan_fieldname']").val(temuan).trigger('change.select2');
            	$("select[name='jenis_report_fieldname']").val(jenis_report).trigger('change.select2');
		        $("select[name='kategori_fieldname']").val(id_kategori).trigger('change.select2');
		        $("select[name='buyer_fieldname']").val(buyer).trigger('change.select2');
		        $("select[name='pabrik_fieldname']").val(pabrik).trigger('change.select2');		        
		        $("input[name='tanggal_fieldname']").val( (tanggal.replace(/\-/g,'.')) );
		        $("input[name='number_fieldname']").val(number);

		       	$("select[name='si_fieldname']").val(si).trigger('change.select2');	
		        $("select[name='so_fieldname']").val(so).trigger('change.select2');	
		       	$("select[name='lot_fieldname']").val(lot).trigger('change.select2');	
		       	$("select[name='pallet_fieldname']").val(pallet).trigger('change.select2');			       	 

		        $("input[name='so_hidden']").val(so);              
		        $("input[name='pallet_hidden']").val(pallet);   
		        $("input[name='tanggal_prod_fieldname']").removeClass('kiranadatepicker');   

		       	$("#def_fieldname").html(desc);   	
            }
        });
	
    }

/*outstand done*/    
	$(document).on("click", ".approve_detail, .decline_detail, .reset_detail", function(e){
		var dt 			= $(this).data('action');
    	var value_data 	= dt.split('_');
    	var act 		= value_data[0];
    	var row 		= value_data[1];
    	var baris 		= $('#baris_hidden').val();
    	var array_app 	= [];
    	// console.log(act , row);
    	var rgba 		= ""; 
    	if(act == 'approve'){
    		rgba 		= "rgba(0, 255, 76, 0.3)" ;
    		$('#actionapp_'+row).val('Approve');
    	} else if(act == 'decline'){
    		rgba 		= "rgba(255, 141, 76, 0.3)";
    		$('#actionapp_'+row).val('Decline');
    	} else {
    		rgba 		= "rgba(255, 255, 255, 1)"
    		$('#actionapp_'+row).val('');
    	}

    	$(this).closest(".fieldset_detail").css("background-color", (rgba));
    	
    	// set hidden type action
    	for(var i=1; i <= baris; i++){
    		var data_app = $('#actionapp_'+i).val();
    		// console.log(data_app);
    		array_app.push(data_app);
    	}
    	// console.log(level_user);
    	if(mode != 'detail' ){
	    	if((jQuery.inArray("Approve",array_app) === -1) && (jQuery.inArray("Decline",array_app) === -1)){
	    		$('#addButton').hide();
	    		// $('#appButton').hide();
	    	} else {
	    		$('#addButton').show();
	    		// $('#appButton').show();
	    	}
	    } else {
	    	if((jQuery.inArray("Approve",array_app) === -1) && (jQuery.inArray("Decline",array_app) === -1)){
	    		$('#appButton').hide();
	    	} else {
	    		$('#appButton').show();
	    	}
	    }

	    if((jQuery.inArray("Decline",array_app) !== -1)){
    		$('#komentar').attr('required');
    		$('#komentar').prop('required', true);
    		// console.log('a');
    		// $('#komentar').removeAttr('required');
    	} else {
    		$('#komentar').attr('required');
    		$('#komentar').prop('required', false);
    		// console.log('b');
    	}

    	$('#finding_app').val(array_app);
    	$('#finding_app_act').val(array_app);
    });

    

    $(document).on("click", "#bacButton", function(e){
    	//clear localstorage
        localStorage.clear();
        window.history.back();
        // var url = baseURL+'pica/trans/data';
        // window.location.href = url;
    });

	$(document).on("click", "#add_baris", function(e){
        // add_baris_detail();
        $('#tipe_template').html('');
        $('#action_add_template').html('');
        $('#baris_hidden').val(0);

        // var field_value = "";
        var jenis_temuan 	= $('#temuan_fieldname').val(); // 1|complain
        var jenis_report 	= $('#jenis_report_fieldname').val(); // Corrective action request 
        var buyer 			= $('#buyer_fieldname').val(); // APOLLO
        var inputvalopt		= "";
        $.ajax({
            url: baseURL+'pica/trans/get/pica_normal',
            type: 'POST',
            dataType: 'JSON',
            data: {
            	jenis_temuan 	: jenis_temuan,
                jenis_report 	: jenis_report,
                buyer 			: buyer
            },
            success: function(data){
                // console.log(data); 
                inputvalopt		= "";
                $('#detail_template').html('');
                if(data != null && data != undefined && data != "" ){
                	$.each(data, function(i, v){
	                	for(var i=1; i <= v.jumlah_tipe; i++){
	                		inputvalopt += '<option value="'+v.id_pica_template_header+'|'+i+'">Tipe Template '+i+'</option>';
	                	}
	                	var field_value 	= '<select class="form-control input-xxlarge " name="tipe_template_fieldname" '
	        						+'id="tipe_template_fieldname" style="width: 100%;"  required="required"> '
	                                + 	inputvalopt
	                                +'</select>';
	                    var field_action 	= '<button type="button" class="btn btn-sm form-control btn-success pull-right" id="exec_add">Tambah Detail</button>';            
	                    $('#tipe_template').append(field_value);
	                    $('#action_add_template').append(field_action);

	                    // ================================ load detail template
	                    var barissplit		= ($('#tipe_template_fieldname').val()).split('|');
	                    var baris			= barissplit[1];
	                    var field_form  	= "";
			            var field_value2 	= "";
			            var n 				= 1;
			   
					});	
                } else {
                	$('#detail_template').html('');
                	kiranaAlert('NotOK', 'Data Template belum dibuat ! ', "error", "no");
                }            
                

            },
        });
    });

	// get temporary file name for preview
	$(document).on("change.bs.fileinput", ".fileinput", function(e){
		readURL($('input[type="file"]',$(this))[0], $('.fileinput-zoom',$(this)));
	    // console.log($('input[type="file"]',$(this))[0]);
	});
    
    // $(document).on("change", "#tipe_template_fieldname ", function(e){
    $(document).on("click", "#exec_add ", function(e){
    	// var thisvalue 		= ($(this).val()).split('|');
    	var thisvalue 		= ($('#tipe_template_fieldname').val()).split('|');
    	var id_header 		= thisvalue[0];
    	var baris 			= thisvalue[1];
    	// console.log(thisvalue);
    	// ================================ load detail template
        // var baris 			= $('#tipe_template_fieldname').val();
        var field_form  	= "";
        var field_value2 	= "";
        var n 				= 1;
        if(baris != 0 && baris != null && baris != undefined && baris != "" ) {

        	var id_pb 			= $('#pabrik_fieldname').val();
            var option_karyawan = "";
    		$.ajax({
                url: baseURL+'pica/trans/get/karyawan',
                type: 'POST',
                dataType: 'JSON',
                data: {
	            	id_pabrik 	: id_pb,
	                // baris 		: baris,
	                // buyer 			: buyer
	            },
                success: function(data_karyawan){
                	var option_karyawan = "";
                	$.each(data_karyawan, function(iKar, val_kary){
                		option_karyawan += "<option value='"+val_kary.nik+"'>"+val_kary.nama_karyawan+"</option>";

                	});
                	
                	$.ajax({
		                url: baseURL+'pica/trans/get/detail_form',
		                type: 'POST',
		                dataType: 'JSON',
		                data: {
			            	id_header 	: id_header,
			                baris 		: baris,
			                // buyer 			: buyer
			            },
		                success: function(data){
		                    // console.log(data)+"BB";
		                    var baris       = $('#baris_hidden').val();
		                    baris++;
		                    $('#jumlah_baris_fieldname').val(baris);
		                    $('#baris_hidden').val(baris);
		                    $('#baris_act').val(baris);
		                    var field_form 			= "";
		                    var field_form2 		= "";
		                    var id_pica_mst_input 	= "";
		                    var label 				= "";
		                    var baris_det			= baris;
		                    var type_input			= "";
		                    var nama_input			= "";
		                    var varAddClass			= "";

		     
							var jumlah_data1=Math.round((data.length)/2); 
							// console.log(arr);
		                    // $(".title-form").html("Edit jenis report Pica");
		                    // console.log(data);
		                    $.each(data, function(i, v){
		                    	id_pica_mst_input 		+= v.id_pica_mst_input+',';
		                    	label 					+= v.desc+',';
		                    	type_input 				+= v.type_input+',';
		                    	nama_input 				+= v.nama_form+',';

		                        var id_name_chkbox      = v.nama_form+'_'+baris;
		                        var id_name_textfield   = v.nama_form+'_text_'+baris;
		                        var form_kosong 		= (v.code_form).replace(/'/g,"");
		                        if(v.type_input == "textarea"){
		                        	var form_kosong_class	= form_kosong.replace('varclass',"form-control input-xxlarge pull-right");
		                        	form_kosong_class		= form_kosong_class.replace('varstyle',"resize: none;");
		                        	form_kosong_class 		= form_kosong_class.replace('varname','detail['+baris+']['+(i+1)+'][value]');
		                        } else if(v.type_input == "file") {
		                        	var form_kosong_class	= form_kosong.replace('varclass',"form-control input-xxlarge pull-right");
		                        	form_kosong_class 		= form_kosong_class.replace('varname','detail['+baris+']['+(i+1)+'][value]');
		                        } else {
		                        	if(v.nama_form == 'duedate'){
		                        		varAddClass 	= ' kiranadatepicker' ; 
		                        	} else if(v.nama_form == 'pic'){
		                        		varAddClass 		= ' select2 pic_detail';		                        		
		                        	}
		                        	var form_kosong_class	= form_kosong.replace('varclass',"form-control input-xxlarge pull-right "+varAddClass);
		                        	form_kosong_class 		= form_kosong_class.replace('varname','detail['+baris+']['+(i+1)+'][value]');
		                        }
		                        
		                        form_kosong_class			= form_kosong_class.replace('varother',"");
		                        form_kosong_class			= form_kosong_class.replace('varvalue',"");
		                        form_kosong_class 			= form_kosong_class.replace('varoption',option_karyawan);

		                        // type form | label | type | nama 
		                    	var detail_value = v.id_pica_mst_input+','+v.desc+','+v.nama_form;

		                        if((i+1) <= jumlah_data1){
		                        	field_form += '<div class="form-group col-sm-12">'
			                                        // +'    <div class="col-sm-4 checkbox">'
			                                        +'        <label class="col-sm-3" for="'+v.desc+'" >'				                                                   
			                                        +          v.desc
			                                        +'        </label>'
			                                        // +'    </div>'
			                                        +'    <div class="col-sm-8" >'
			                                        +'       <input type="hidden" name="detail['+baris+']['+(i+1)+'][id_detail]" value="">'
			                                        +'       <input type="hidden" name="detail['+baris+']['+(i+1)+'][baris]" value="'+baris+'">'
			                                        +'       <input type="hidden" name="detail['+baris+']['+(i+1)+'][tipe]" value="'+v.type_input+'">'
			                                        +'       <input type="hidden" name="detail['+baris+']['+(i+1)+'][detail_value]" value="'+detail_value+'">'
			                                        +		form_kosong_class
			                                        +'    </div>'
			                                        +'</div>';
			                    } else if((i+1) > jumlah_data1) {
			                    	field_form2 += '<div class="form-group col-sm-12">'
			                                        // +'    <div class="col-sm-4 checkbox">'
			                                        +'        <label class="col-sm-3" for="'+v.desc+'" >'				                                                   
			                                        +          v.desc
			                                        +'        </label>'
			                                        // +'    </div>'
			                                        +'    <div class="col-sm-8" >'
			                                        +'       <input type="hidden" name="detail['+baris+']['+(i+1)+'][id_detail]" value="">'
			                                        +'       <input type="hidden" name="detail['+baris+']['+(i+1)+'][baris]" value="'+baris+'">'
			                                        +'       <input type="hidden" name="detail['+baris+']['+(i+1)+'][tipe]" value="'+v.type_input+'">'
			                                        +'       <input type="hidden" name="detail['+baris+']['+(i+1)+'][detail_value]" value="'+detail_value+'">'
			                                        +		form_kosong_class
			                                        +'    </div>'
			                                        +'</div>';
			                    }

		                        
		                    });
							// console.log(mode);
							var actiondetail_x 	= level_user == 1 ? "" : "<a href='javascript:void(0)' id='detaildec_"+baris+"' class='decline_detail btn btn-danger' data-action='decline_"+baris+"' title='Decline'><i class='glyphicon glyphicon-remove'></i></a>";
											
							var actiondetail 	= ( (pica_status) != '' && pica_status != 'null' && (mode != 'null' && mode != undefined) ) ?
													"					<div id='divapp_"+baris+"' class='col-sm-12'>"
													+"						<div class='btn-group pull-right'>"
													+"							<a href='javascript:void(0)' id='detailapp_"+baris+"' class='reset_detail btn btn-default' data-action='reset_"+baris+"' title='Reset'><i class='glyphicon glyphicon-refresh'></i></a>"
													+							actiondetail_x
													+"							<a href='javascript:void(0)' id='detailapp_"+baris+"' class='approve_detail btn btn-success' data-action='approve_"+baris+"' title='Approve'><i class='glyphicon glyphicon-ok'></i></a>"
													+"						</div>"
													+"					</div>" : "";
		                    var field_value2     = "";
		                        field_value2 += '<div class="detail_'+baris+'" ><div class="col-sm-12">'
		                                        +'  <fieldset class="fieldset-success fieldset_detail" id="fs_'+baris+'">'
		                                        +'      <legend>Baris '+baris+' </legend>'
		                                        +' 		<div class="action_delete" id="action_delete_'+baris+'"><div class="legend2 btn" onclick=\'onclick_delete_detail("'+baris+'")\'>X</div></div>'
		                                        // +'      <div class="row">'
		                                        // +'          <div class="col-sm-12 form-horizontal">'
		                                        +'              <div class="row" id="divdetail_'+baris+'">'
		                                        +'					<div class="col-sm-6">'
		                                        +               		field_form						
		                                        +'					</div>'
		                                        +'					<div class="col-sm-6">'
		                                        +               		field_form2
		                                        +'					</div>'
		                                        + 					actiondetail
		          //                               +"					<div id='divapp_"+baris+"' class='col-sm-12 pull-right'>"
												// +"						<a href='javascript:void(0)' id='detaildec_"+baris+"' class='decline_detail btn btn-danger pull-right' data-action='decline_"+baris+"' title='Decline'><i class='glyphicon glyphicon-remove'></i></a>"
												// +"						<a href='javascript:void(0)' id='detailapp_"+baris+"' class='approve_detail btn btn-success pull-right' data-action='approve_"+baris+"' title='Approve'><i class='glyphicon glyphicon-ok'></i></a>"
												// +"					</div>"	
												+'              	<input type="hidden" name="actionapp_'+baris+'" id="actionapp_'+baris+'" readonly="readonly" '
						                        +'              	<input type="hidden" name="id_pica_mst_input_'+baris+'" id="id_pica_mst_input_'+baris+'" readonly="readonly" '
		                                        +'              	<input type="hidden" name="baris_'+baris+'" id="baris_'+baris+'" readonly="readonly" '
		                                        +' 					<input type="hidden" name="label_'+baris+'" id="label_'+baris+'" readonly="readonly" '
		                                        +' 					<input type="hidden" name="type_input_'+baris+'" id="type_input_'+baris+'" readonly="readonly" '
		                                        +' 					<input type="hidden" name="nama_input_'+baris+'" id="nama_input_'+baris+'" readonly="readonly" '
		                                        +' 					<input type="hidden" name="id_detail_'+baris+'" id="id_detail_'+baris+'" readonly="readonly" '
		                                        +'              </div>'
		                                        // +'          </div>'
		                                        // +'      </div>'
		                                        +'  </fieldset>'
		                                        +'</div></div>'; 
		                       
		                    // }
		                    $('.action_delete').html('');
		                    $('#detail_template').append(field_value2);
		                    $('#id_pica_mst_input_'+baris).val(id_pica_mst_input);
		                    $('#label_'+baris).val(label);
		                    $('#baris_'+baris).val(baris_det);
		                    $('#type_input_'+baris).val(type_input);
		                    $('#nama_input_'+baris).val(nama_input);

		                    //initial datepicker
		                    $(".kiranadatepicker").datepicker({
				                endDate: ($(this).data("enddate") != null ? $(this).data("enddate") : ''),
				                todayHighlight: true,
				                disableTouchKeyboard: true,
				                format: ($(this).data("format") != null ? $(this).data("format") : "dd.mm.yyyy"),
				                startView: ($(this).data("startview") != null ? $(this).data("startview") : "days"),
				                minViewMode: ($(this).data("minviewmode") != null ? $(this).data("minviewmode") : "days"),
				                autoclose: true
				            });
		                    n++;
		                    $('.select2').select2();
		                    
		                }
		            });
                }
            });
    		
            

		}	
    });

    //reload / create new input
    $("#btn-new").on("click", function(e){
        location.reload();
        e.preventDefault();
        return false;
    });

    //open page for input data     
    $(document).on("click", "#add_template_button", function(e){
    	//clear localstorage
        localStorage.clear();
        var url = baseURL+'pica/trans/input/pica';
        window.location.href = url;
    });
   	
    // set running number 
    $(document).on("changeDate", "#tanggal_fieldname", function(e){
		var pabrik  	= $('#pabrik_fieldname').val();
		var temuan  	= ($('#temuan_fieldname').val()).split("|");
		var kode_temuan = temuan[2];
		var valuethis 	= $(this).val();
		var valuedate 	= valuethis.split(".");		
		// var tanggal 	= valuedate[0];
		var bulan 		= valuedate[1];
		var tahun 		= valuedate[2];
		// var date_sql 	= tahun+'-'+bulan+'-'+tanggal;
		var x 			= 0;
		// console.log(date_sql);
		if(id_pica_transaksi_header == undefined && id_pica_transaksi_header == null ){
			if(pabrik != ""){
				$.ajax({
					url: baseURL + "pica/trans/get/data_pica_normal",
					type: 'POST',
					dataType: 'JSON',
					data: {
						tahun  	: tahun,
						// bulan  	: bulan,
						pabrik  : pabrik,
						order 	: 'ASC'
					},
					
					success: function (data) {
						// console.log(data);
						var no 				= (data.length) + 1;
						var last_number		= 1
						$.each(data, function(i,v){
							var number 	= (v.number).split('/');
							last_number = parseInt(number[0]) + 1;
						});
						// console.log(last_number);

						if((last_number.toString()).length == 1){
							no = "00"+last_number;
						} else if((last_number.toString()).length == 2){
							no = "0"+last_number ;
						}
						if(kode_temuan != undefined && bulan != undefined && tahun != undefined){
							var format_number_pica 	= no+'/'+kode_temuan+'/'+pabrik+'/'+bulan+'/'+tahun ;
						} else {
							if(kode_temuan == undefined) var msg_err = 'kode temuan';
							else if(bulan == undefined || tahun == undefined) var msg_err = 'tanggal';
							
							kiranaAlert('NotOK', msg_err+' belum ada', "error", "no");
						}
						$('#number_fieldname').val(format_number_pica);
						
					}
				});
			}
		}		
	});

	// set running number 
    $(document).on("change", "#pabrik_fieldname", function(e){
		var pabrik  	= $(this).val();
		var buyer 		= $('#buyer_fieldname').val();
		var temuan  	= ($('#temuan_fieldname').val()).split("|");
		var kode_temuan = temuan[2];
		var valuedates 	= $('#tanggal_fieldname').val();
		var valuedate 	= valuedates.split(".");		
		// var tanggal 	= valuedate[0];
		var bulan 		= valuedate[1];
		var tahun 		= valuedate[2];
		// var date_sql 	= tahun+'-'+bulan+'-'+tanggal;
		var x 			= 0;
		// console.log(date_sql);
		if(id_pica_transaksi_header == undefined && id_pica_transaksi_header == null ){
			if(valuedates != ""){
				$.ajax({
					url: baseURL + "pica/trans/get/data_pica_normal",
					type: 'POST',
					dataType: 'JSON',
					data: {
						tahun  	: tahun,
						// bulan  	: bulan,
						pabrik  : pabrik
					},
					
					success: function (data) {
						var no 				= (data.length) + 1;
						var last_number		= 1
						$.each(data, function(i,v){
							var number 	= (v.number).split('/');
							last_number = parseInt(number[0]) + 1;
						});
						// console.log(last_number);

						if((last_number.toString()).length == 1){
							no = "00"+last_number;
						} else if((last_number.toString()).length == 2){
							no = "0"+last_number ;
						}
						if(kode_temuan != undefined && bulan != undefined && tahun != undefined){
							var format_number_pica 	= no+'/'+kode_temuan+'/'+pabrik+'/'+bulan+'/'+tahun ;
						} else {
							if(kode_temuan == undefined) var msg_err = 'kode temuan';
							else if(bulan == undefined || tahun == undefined) var msg_err = 'tanggal';
							
							kiranaAlert('NotOK', msg_err+' belum ada', "error", "no");
						}
						$('#number_fieldname').val(format_number_pica);
						
					}
				});
			}


		}

		var id_pb 			= pabrik;
        var option_karyawan = "";
		$.ajax({
            url: baseURL+'pica/trans/get/karyawan',
            type: 'POST',
            dataType: 'JSON',
            data: {
            	id_pabrik 	: id_pb,
           	},
            success: function(data_karyawan){
            	var option_karyawan = "";
            	$.each(data_karyawan, function(iKar, val_kary){
            		option_karyawan += "<option avalue='"+val_kary.nik+"'>"+val_kary.nama_karyawan+"</option>";

            	});
            	$('.pic_detail').html('');
            	$('.pic_detail').html(option_karyawan);
            }
        });

		// get data SO
		$.ajax({
			url: baseURL + "pica/trans/get/data_buyer_si",
			type: 'POST',
			dataType: 'JSON',
			data: {
				buyer  	: buyer,
				pabrik  : pabrik
			},
			
			success: function (data) {
				$('#si_fieldname').html('');
				$('#so_fieldname').html('');
				$('#so_hidden').val('');
				$('#lot_fieldname').html('');
				$('#pallet_fieldname').html('');
				$('#tanggal_prod_fieldname').val('');
       			var form = ''; var valtriger = 0;
       			// console.log(buyer);
       			if(buyer != undefined && buyer != "" && buyer != 0){
		            $.each(data, function(i,v){
		            	if(i == 0 ) valtriger = v.no_si; 
		            	form += "<option value='"+v.no_si+"'>"+v.no_si+"</option>"
		            });
		            $('#si_fieldname').html(form);
	            	$('#si_fieldname').val(valtriger).trigger('change');
		        }
	            				
			}
		});
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
                        url: baseURL+'pica/trans/set/data',
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

    // set approval
    $(document).on("click", "#appButton , #rejButton", function(e){
    	var type                = $(this).attr("class");
        type 					= type.split(' ');
        console.log(type);
        var data_pica           = ($(this).data(type[2])).split('|');
        var id 					= data_pica[0];
        var data 				= data_pica[1];
         console.log(" id ="+id+" type ="+type[2]+" pica status ="+data);

        // set value action
        var value_app 			= ($('#finding_app').val() ).split(',');
		var action_app 			= "submit";
		console.log(value_app);
		if( (jQuery.inArray( "Approve", value_app ) != -1 || jQuery.inArray( '0', value_app ) != -1) || pica_status < 2 || pica_status == "null" ){
			action_app = 'submit';
		} else {
			action_app = 'reject';
		}
		
        var label_top = "";
        if(action_app == 'reject') {
        	label_top = 'Reject Pica';
        } else if(action_app == 'submit') {
        	label_top = 'Submit Pica';
        } else if(action_app == 'approve') {
        	label_top = 'Approve Pica';
        } 
        $('#myModalLabel_app').html(label_top);
    	$('#approve_modal').modal('show');
    	$('#type_hide').val(action_app);
    	$('#data_hide').val(data);
    	$('#if_approve_hide').val(if_approve);
    	$('#if_decline_hide').val(if_decline);
    	$('#id_hide_approval').val(id);
    });

    $(document).on("click", " button[name='action_button']", function(e){
    	e.preventDefault();
       	var empty_form      = validate('.form-approve-pica');
       	if(empty_form == 0){
            var isproses        = $("input[name='isproses']").val();
            if(isproses == 0){
                // $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-approve-pica")[0]);
                // console.log();
                var confirm_submit   	= "Apakah anda yakin ingin submit data pica ?";
		        var confirm_approval    = "Apakah anda yakin ingin approve data pica ?";
		        var confirm_reject    	= "Apakah anda yakin ingin decline data pica ?";
		        var id 					= $('#id_hide_approval').val();
		        var type 				= $('#type_hide').val();
		        var data 				= $('#data_hide').val();
		        var if_approve 			= $('#if_approve_hide').val();
		        var if_decline 			= $('#if_decline_hide').val();
		        var komentar 			= $('#komentar').val();
		        var numb 				= $('#id_number_approval').html();
		        var status_act 			= $('#status_pica_act').val();
		        var baris_act			= $('#baris_act').val();
		        var finding				= $('#finding_app_act').val();
		        
		        if(type == 'submit') {
		            text = confirm_submit;
		        } else if(type == 'approve') {
		            text = confirm_approval; 
		        } else if(type == 'reject') {
		            text = confirm_reject; 
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
		                        url: baseURL+'pica/trans/set/approval',
		                        type: 'POST',
		                        dataType: 'JSON',
		                        data: {
		                            id      	: id,  
		                            type    	: type,
		                            data 		: data,
		                            app 		: if_approve,
		                            decl 		: if_decline,
		                            desc 		: komentar,
		                            numb 		: number,
		                            status_act 	: status_act,
							        baris_act	: baris_act,
							        finding		: finding

		                        },
		                        success: function(data){
		                        	// console.log('masuk app');
		                            if(data.sts == 'OK'){
		                            	
		                                var url = baseURL+'pica/trans/approval';
		                                // var url =  window.history.back();
		                                // var url =  "";
		                                kiranaAlert(data.sts, data.msg, 'success', url);
        								// window.location.href = url;
		                            }else{
		                                kiranaAlert(data.sts, data.msg, "error", "no");
		                               
		                            }
		                            
		                        }
		                    });
		                },
		            }
		        );

            }else{
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        // var url = baseURL+'pica/trans/approval';
        // window.location.href = url;
        return false;
    });

    // view history 
    $(document).on("click", "#hisButton", function(e){
    	// console.log(id_pica_transaksi_header);
    	var id = id_pica_transaksi_header;
    	$('#history_modal').modal('show');
    	$.ajax({
            url: baseURL+'pica/trans/pica_data_log',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_header 	: id,
                all 		: 'only active',
                normal 		: 'normal',
                type 		: 'view'
            },
            success: function(data){
                console.log(data)+"BB";
                
            	var t   = $('.my-datatable-extends').DataTable();
	            t.clear().draw();
	            $.each(data, function(i,v){
	            	var status = v.action+' oleh '+v.user_log+'<br> ('+v.posst+')';
	                t.row.add( [
	                    v.number,
	                    v.date,
	                    status,
	                    v.comment,
	                ] ).draw( false );
	               
	            });
            },
            complete: function(){
                setTimeout(function () {
                    adjustDatatableWidth();
                }, 1000);
            }
        });
    });

    // Change buyer -- takeout 
    $(document).on("change", "#buyer_fieldname", function(e){
    	
        var pabrik  	= $('#pabrik_fieldname').val();
		var buyer 		= $('#buyer_fieldname').val();
		var temuan  	= ($('#temuan_fieldname').val()).split("|");
		var kode_temuan = temuan[2];
		var valuedates 	= $('#tanggal_fieldname').val();
		var valuedate 	= valuedates.split(".");		
		// var tanggal 	= valuedate[0];
		var bulan 		= valuedate[1];
		var tahun 		= valuedate[2];
		// var date_sql 	= tahun+'-'+bulan+'-'+tanggal;
		var x 			= 0;
		// console.log(date_sql);
		// get data SO
		$.ajax({
			url: baseURL + "pica/trans/get/data_buyer_si",
			type: 'POST',
			dataType: 'JSON',
			data: {
				buyer  	: buyer,
				pabrik  : pabrik
			},
			
			success: function (data) {

				$('#si_fieldname').html('');
				$('#so_fieldname').html('');
				$('#so_hidden').val('');
				$('#lot_fieldname').html('');
				$('#pallet_fieldname').html('');
				$('#tanggal_prod_fieldname').val('');
       			var form = '<option > Pilih no SI</option>'; var valtriger = 0;
       			if(buyer != undefined && buyer != "" && buyer != 0){
		            $.each(data, function(i,v){
		            	if(i == 0 ) valtriger = v.no_si; 
		            	form += "<option value='"+v.no_si+"'>"+v.no_si+"</option>"
		            });
		            $('#si_fieldname').html(form);
	            	$('#si_fieldname').val(valtriger).trigger('change');
	        	}
	            				
			}
		});
    });

    // Change si  
    $(document).on("change", "#si_fieldname", function(e){
    	// var buyer 	= $('#buyer_fieldname').val();
    	var si 		= ($(this).val() == "" || $(this).val() == undefined || $(this).val() == null ) ? "si no data" : $(this).val();
    	$('#so_fieldname').html('');
		$('#so_hidden').val('');
		$('#lot_fieldname').html('');
		$('#pallet_fieldname').html('');
    	$.ajax({
            url: baseURL+'pica/trans/get/data_buyer_si',
            type: 'POST',
            dataType: 'JSON',
            data: {
                si 		: si,
                // type 	: 'si',
                // so 		: so
            },
            success: function(data){
                // console.log(data)+"BB";
               	      
	            var form = '<option > Pilih no SO</option>'; var valtriger = 0;
	            $.each(data, function(i,v){
	            	if(i == 0 ) valtriger = v.no_so; 
	            	form += "<option value='"+v.no_so+"'>"+v.no_so+"</option>"
	            });
	            $('#so_fieldname').html(form);
	            $('#so_fieldname').val(valtriger).trigger('change');
	     //        if(mode == undefined){
	     //        	$('#so_fieldname').val(valtriger).trigger('change');	
	     //        } 
	     //        else {
	     //        	// console.log( $("#so_hidden").val() );
	     //        	var valso = ($("#so_hidden").val() != "" && $("#so_hidden").val() != null )? $("#so_hidden").val() : valtriger;
			 		// $('#pallet_fieldname').val(valso).trigger('change.select2');
	     //        }

	        }
        });
    });

    // Change so  
    $(document).on("change", "#so_fieldname", function(e){
    	var pabrik 	= $('#pabrik_fieldname').val();
    	var buyer 	= $('#buyer_fieldname').val();
    	var so 		= $(this).val();
    	$('#lot_fieldname').html('');
		$('#pallet_fieldname').html('');
    	$.ajax({
            url: baseURL+'pica/trans/get/data_buyer_so',
            type: 'POST',
            dataType: 'JSON',
            data: {
                buyer 	: buyer,
                type 	: 'lot',
                so 		: so,
                pabrik 	: pabrik
            },
            success: function(data){
                // console.log(data)+"BB";
				var form = ''; var valtriger = 0;
	            form += "<option value=0>Silahkan pilih nomor lot </option>";
	            $.each(data, function(i,v){
	            	if(i == 0 ) valtriger = v.no_lot; 
	            	form += "<option value='"+v.no_lot+"'>"+v.no_lot+"</option>"
	            });
	            $('#lot_fieldname').html(form);
	            $('#lot_fieldname').val(valtriger).trigger('change');
	     //        if(mode == undefined || mode == 'edit'){
	     //        	$('#lot_fieldname').val(valtriger).trigger('change');	
	     //        } else if(mode == 'aa'){
	     //        	var valso = $("#so_hidden").val() != "" ? $("#so_hidden").val() : valtriger;
			 		// $('#so_fieldname').val(valso).trigger('change');
	     //        }

	      //       if(mode == 'detail')
    			// {
    			// 	$("select[name='pallet_fieldname'] option[value!='"+pallet+"'] ").attr("disabled", true);	
    			// }
	        	
	        }
        });
    });

    // Change lot  
    $(document).on("change", "#lot_fieldname", function(e){
    	var pabrik 	= $('#pabrik_fieldname').val();
    	var buyer 	= $('#buyer_fieldname').val();
    	var so 		= $('#so_fieldname').val();
    	var lot 	= $(this).val();
    	$('#pallet_fieldname').html('');
    	// console.log(buyer);
    	$.ajax({
            url: baseURL+'pica/trans/get/data_buyer_so',
            type: 'POST',
            dataType: 'JSON',
            data: {
                buyer 	: buyer,
                type 	: 'pallet',
                so 		: so,
                lot 	: lot,
                pabrik 	: pabrik
            },
            success: function(data){
                // console.log(data)+"BB";
               	var form = ''; var valtriger = 0;
            	form += "<option value=0>Silahkan pilih nomor pallet </option>";
            	if(lot != 0){
		            $.each(data, function(i,v){
		            	if(i == 0 ) valtriger = v.no_pallet; 
		            	form += "<option value='"+v.no_pallet+"'>"+v.no_pallet+"</option>"
		            });
		        }
	            $('#pallet_fieldname').html(form);
	            $('#pallet_fieldname').val(valtriger).trigger('change');
	    //         if(mode == undefined)
			 	// 	$('#pallet_fieldname').val(valtriger).trigger('change');
			 	// else {
			 	// 	var valpallet = $("#pallet_hidden").val() != "" ? $("#pallet_hidden").val() : valtriger;
			 	// 	$('#pallet_fieldname').val(valpallet).trigger('change');
			 	// }
			 		
			 	// if(mode == 'detail')
    	// 		{
    	// 			$("select[name='pallet_fieldname'] option[value!='"+pallet+"'] ").attr("disabled", true);	
    	// 		}

	        }
        });
    });

    // Change pallet
    $(document).on("change", "#pallet_fieldname", function(e){
    	var pabrik 	= $('#pabrik_fieldname').val();
    	var buyer 	= $('#buyer_fieldname').val();
    	var so 		= $('#so_fieldname').val();
    	var lot 	= $('#lot_fieldname').val();
    	var pallet 	= $(this).val();
    	var thisdt 	= $('#thisday').val();
    	$.ajax({
            url: baseURL+'pica/trans/get/data_buyer_si',
            type: 'POST',
            dataType: 'JSON',
            data: {
                // buyer 	: buyer,
                // type 	: 'pallet',
                so 		: so,
                lot 	: lot,
                pallet 	: pallet,
                pabrik 	: pabrik
            },
            success: function(data){
             	var tanggal_produksi 	= '';
             	var result 				= '';
             	$.each(data, function(i,v){
	            	tanggal_produksi = v.date_prod; 
	            });	
	            if(tanggal_produksi != undefined && tanggal_produksi != "" && tanggal_produksi != "undefined" && pallet != 0 && lot != 0){
	            	// console.log(tanggal_produksi);
	            	var x = ((tanggal_produksi.toString()).replace(/,/g, "")).split("-");
	            	result =  x[2]+"."+ x[1]+"."+ x[0];
	            	// $('#tanggal_prod_fieldname').val(result); 
	            	$('#tanggal_prod_fieldname').datepicker( "remove" );
	            } else {
	            	// console.log(mode);
	            	if(mode != 'detail' && mode != 'response'){
	            		console.log(mode, 'ada');
		            	$('#tanggal_prod_fieldname').datepicker({
		            		todayHighlight: true,
			                disableTouchKeyboard: true,
			                format: ($(this).data("format") != null ? $(this).data("format") : "dd.mm.yyyy"),
			                autoclose: true
		            	});
		            } else {
		            	console.log(mode , 'kosong');
		            	$('#tanggal_prod_fieldname').removeClass( "kiranadatepicker" );
		            	$('#tanggal_prod_fieldname').datepicker( "remove" );
		            }
	            	result = thisdt; 
	            }
	            $('#tanggal_prod_fieldname').val(result);   
             	// console.log(result,tanggal_produksi, x);
	        }
        });
        if(pallet == 0 || lot == 0){
        	$('#tanggal_prod_fieldname').val(thisdt);
        }
    });

    // set header role
    $(document).on("change", "#temuan_fieldname", function(e){
    	// console.log('change');
    	e.preventDefault();
    	$('#tanggal_fieldname').val('');
    	$('#number_fieldname').val('');
    	if($(this).val() != null){
    		var thisdata 	= ($(this).val()).split('|');
	        var id_jenis    = thisdata[0];
	        var datatemuan 	= thisdata[1];
	        if(datatemuan == 'Complain'){
	        	$('#so_fieldname').attr('required', 'required');
	        } else {
	        	$('#so_fieldname').removeAttr('required', 'required');
	        }
    	}
        

        // set data verificator
        try {               

				changeTemuan_4flow($(this).val()); 
				changeTemuan_4plant($(this).val());
		        changeTemuan($(this).val());



            } catch ( e ) {
                console.log( e );
            } finally {
                console.log( 'blok finally' );
            }
    });
});

// show modal approval pica
function saveApproval(param=NULL,param2=NULL,param3=NULL){
	// e.preventDefault();
	// var type                = $(this).attr("class");
	var type 		= param3;
	var if_data 	= param2.split('|');
	var if_app 		= if_data[0];
	var if_dec 		= if_data[1];
    var data_pica   = (param).split('|');
    var id 			= data_pica[0];
    var data 		= data_pica[1];
    // console.log(" id ="+id+" type = submit pica status ="+data);
    var label_top 	= "";
    if(type 		== 'reject') {
    	label_top 	= 'Reject Pica';
    } else if(type 	== 'submit') {
    	label_top 	= 'Submit Pica';
    } else if(type 	== 'approve') {
    	label_top 	= 'Approve Pica';
    } 

    $("#komentar").css("border-color", "#d2d6de");

	$('#type_hide').val(type);
	$('#data_hide').val(data);
    $('#myModalLabel_app').html(label_top);
	$('#approve_modal').modal('show');
	$('#if_approve_hide').val(if_app);
	$('#if_decline_hide').val(if_dec);
	$('#id_hide_approval').val(id);
}

// read url image
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
    $('#baris_act').val(baris_hidden);
    // set jumlah baris value form header
    $('#jumlah_baris_fieldname').val(baris_hidden);
    // set button action delete detail 
    $('#action_delete_'+baris_hidden).html('<div class="legend2 btn" onclick=\'onclick_delete_detail("'+baris_hidden+'")\'>X</div>');
}

// show data table
function datatables_ssp(){
    var filter_pabrik   = $("#filter_pabrik").val();
    var filter_report   = $("#filter_report").val();
    var filter_temuan   = $("#filter_temuan").val();
    var filter_buyer    = $("#filter_buyer").val();
    var filter_no     	= $("#filter_no").val();
    
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
            url: baseURL+'pica/trans/pica_data',
            type: 'POST',
            data: {
                filter_pabrik    	: filter_pabrik,
                filter_report    	: filter_report,
                filter_temuan     	: filter_temuan,
                filter_buyer     	: filter_buyer,
                filter_no     		: filter_no,

                type 				: 'view',
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
                "width": "5%",
                "render": function (data, type, row) {
                    var date_from = row.date_from;
                    return date_from;
                }
            },
            {
                "data": "pabrik",
                "name" : "pabrik",
                "width": "5%",
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
                "width": "10%",
                "render": function (data, type, row) {
                	if(row.buyer == '0' ) {var buyer = 'Tidak ada buyer';} else {var buyer = row.buyer;}
                                
                    // var buyer = row.buyer;                    
                    return buyer;
                }
            },

            {
                "data": "pica_status,role_posisi ",
                "name" : "pica_status",
                "width": "30%",
                "render": function (data, type, row) {
                	var data_finding = [];
                	if(row.finding != undefined && row.finding != "" && row.finding != null){
                		// console.log(row.finding);
                		var splitdata 			= (row.finding).split(',');
                		var data_finding 		= [];
                		data_finding['baris'] 	= [];
                		data_finding['status'] 	= [];
                		data_finding['date_app']= [];
                		data_finding['level'] 	= [];
                		data_finding['nm_level']= [];
                		$.each(splitdata, function(i, v){
                			//baris,status, date_approval, level_app
                			var splitv 	= v.split('|');
                			var baris 	= splitv[0];
                			var status 	= splitv[1];
                			var date_app= splitv[2];
                			var level 	= splitv[3]; 
                			var nmlevel	= splitv[4]; 
                			// console.log(baris, status, date_app, level);
                			// console.log(v);
                			if(baris != ""){
	                			data_finding['baris'].push(baris);
	                			data_finding['status'].push(status);
	                			data_finding['date_app'].push(date_app);
	                			data_finding['level'].push(level);
	                			data_finding['nm_level'].push(nmlevel);
                			}

                			// data_finding.push();
                		})
                		// console.log(row.number);
                		// console.log(data_finding['baris'].length);
                		var jum_finding 	= data_finding['baris'].length;
						var arr_finish 		= data_finding['level'].filter(checkstat);
						var arr_draft 		= data_finding['status'].filter(checkstat_draft);
                		function checkstat(stat) {
						  return stat == "Finish";
						}
						function checkstat_draft(stat) {
						  return stat == "0";
						}
						// function checkstat_onprog(stat) {
						//   return stat == "Fi";
						// }
                		// console.log(x.length);
                		// console.log(data_finding , jum_finding , arr_finish, arr_draft);

                		var status = "";
	                	if(jum_finding == arr_finish.length){
	                		var status = '<div class="label label-success">FINISH</div>';
	                	} else if(jum_finding == arr_draft.length && row.pica_status == 'null') {
	                		var status = '<div class="label label-default">Draft</div>';
	                	} else {
	                		var status = '<div class="label label-warning"><ON PROGRESS</div>';
	                	
	                		var data_finding_st = '';
	                		var lvl 			= [];
                            $.each(data_finding['nm_level'], function(i, v){
	                			if(jQuery.inArray(v,lvl) === -1){
	                				lvl.push(v);

		                			if(v == "Finish"){
		                				data_finding_st += '<div>'+arr_finish.length+' Item '+v+'</div>';
		                			}else if(v == "Draft"){
		                				data_finding_st += '<div>'+arr_draft.length+' Item '+v+'</div>';
		                			} else {
		                				arr = jQuery.grep(data_finding['nm_level'], function( value ) {
										  return value == v;
										});
                                        var jb = "";
                                        if (v.indexOf('~') > -1){
                                            jb = (v.replace(/~/g,',')); 
                                            jb = jb.replace(/,\s*$/, "");                         
                                        } else {
                                            jb = v;
                                        }                                     
                                        data_finding_st += '<div>'+arr.length+' Item Sedang diproses di '+decodeEntities(jb)+'</div>';
		                			}
		                		}
	                		})
	                		var status = '<div class="label label-warning">ON PROGRESS</div>'
	                						+data_finding_st;
	                	} 

                	} else {
	                	var status = "";
	                	// if(row.pica_status == 'Finish' ){
	                	// 	var status = '<div class="label label-success">FINISH</div>';
	                	// } else if(row.pica_status == null ) {
	                	// 	var status = '<div class="label label-default">Draft</div>';
	                	// } else {
	                	// 	var status = '<div class="label label-warning">ON PROGRESS</div><div>Sedang diproses di '+row.role_posisi+'</div>';
	                	// } 
	                }                
                    return status;
                }
            },


            // {
            //     "data": "jumlah_baris",
            //     "name" : "jumlah_baris",
            //     "width": "20%",
            //     "className" : "text-right",
            //     "render": function (data, type, row) {
            //         var jumlah_baris = row.jumlah_baris;                    
            //         return jumlah_baris;
            //     }
            // },

            
            {
                // "data": "tbl_inv_aset.id_aset",
                "data": "id_pica_transaksi_header ",
                "name" : "id_pica_transaksi_header",
                "width": "12%",
                "render": function (data, type, row) {
                    var action 		= "";

                    // var actionedit 	= baseURL+'pica/trans/input/pica';
                    if(row.na == 'n'){
                    	
                    	var btn_edit    = ( row.pica_status == null ) ? "<li><a href='javascript:void(0);' class='edit' data-edit='"+row.id_pica_transaksi_header+"'><i class='fa fa-pencil-square-o'></i> Edit</a></li>" : "";
                        action = btn_edit
                        		+"<li><a href='javascript:void(0);' class='detail' data-detail='"+row.id_pica_transaksi_header+"'><i class='fa fa-pencil-square-o'></i> Detail</a></li>"
                              	+"<li><a href='javascript:void(0);' class='nonactive' data-nonactive='"+row.id_pica_transaksi_header+"'><i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                              	+"<li><a href='javascript:void(0);' class='delete' data-delete='"+row.id_pica_transaksi_header+"'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                        
                    }
                    if(row.na == 'y'){
                        action = "<li><a href='javascript:void(0);' class='setactive' data-setactive='"+row.id_pica_transaksi_header
                                +"'><i class='fa fa-check'></i> Set Aktif</a></li>";
                    }

                    var output = "<div class='input-group-btn'>"
                        		+"<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>"
                        		+"<ul class='dropdown-menu pull-right'>"
                        		+   action 
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
                // console.log(data)+"BB"; 
                $('#jenis_report_fieldname').html(''); 
                if(data[0] != undefined){
                    // console.log('ada');
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
                    $('#verificator_fieldname').val('');
    	            $('#id_verificator').val('');
    	            $('#jenis_report_fieldname').html('');
    	            $('#pabrik_fieldname').html('');
                    swal('Error', data_msg, 'error');    
                } 
            }        
        });
    }
}

// on change temuan on ready function
function changeTemuan_4plant(param,param2=null,param3=null){
	// console.log('4plant');
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
    /*$.ajax({
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
    });*/
    if(jenis_temuan != null){
        $.ajax({
            url: baseURL+'pica/trans/pica_data_plant',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_temuan 		: id_temuan,
                
            },
            success: function(data){
                // console.log(data);
                if(data.sts == 'NotOK'){ 
                	var data_msg = "Mohon lengkapi data role posisi pabrik untuk jenis temuan tersebut";
                    $('#verificator_fieldname').val('');
    	            $('#id_verificator').val('');
    	            $('#jenis_report_fieldname').html('');
    	            $('#pabrik_fieldname').html('');
                    swal('Error', data_msg, 'error'); 
                   	
            	} else{
    	         	$('#pabrik_fieldname').html('');       
    	            var form = ''; var valtriger = 0;
    	            $.each(data, function(i,v){
    	            	if(i == 0 ) valtriger = v.plant; 
    	            	form += "<option value='"+v.plant+"'>"+v.plant_name+"</option>"
    	            });
    	            $('#pabrik_fieldname').html(form);  
    	            if(param3 == 'detail'){
    		        	
    		        	$("select[name='pabrik_fieldname']").val(selectedVal).trigger('change.select2');
    			        // disable function edit buyer
    			        $("select[name='pabrik_fieldname'] option[value!='"+selectedVal+"'] ").attr("disabled", true);
    		        } else if(param3 == 'edit'){
    		        	$("select[name='pabrik_fieldname']").val(selectedVal).trigger('change.select2');
    		        	$("select[name='pabrik_fieldname'] option[value!='"+selectedVal+"'] ").attr("disabled", true);
    		        }
                }
            },
            error: function(data){
            	console.log("error role posisi pabrik");
            	$('#verificator_fieldname').val('');
                $('#id_verificator').val('');
                $('#jenis_report_fieldname').html('');
                $('#pabrik_fieldname').html('');
            }
        });
    }
}

// on change temuan on ready function
function changeTemuan_4flow(param,param2=null){
	// console.log('4plant');
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
    /*$.ajax({
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
    });*/
    if(jenis_temuan != null){
        $.ajax({
            url: baseURL+'pica/master/get/workflow',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id 		: id_temuan,
                type 	: 'Verificator' 
            },
            success: function(data){
                // console.log(data[0])+"BB";  
                if(data[0] == undefined){
                	var data_msg = "Mohon lengkapi data role posisi ";
                    $('#verificator_fieldname').val('');
                    $('#id_verificator').val('');
                    $('#jenis_report_fieldname').html('');
                    $('#pabrik_fieldname').html('');
                    swal('Error', data_msg, 'error');
                } else {
                    $('#verificator_fieldname').val(data[0]['posisi']);
                    $('#id_verificator').val(data[0]['id_posisi']);    
                } 
            },
            error: function(data){
                var data_msg = "Mohon pilih jenis temuan terlebih dahulu";
                $('#verificator_fieldname').val('');
                $('#id_verificator').val('');
                $('#jenis_report_fieldname').html('');
                $('#pabrik_fieldname').html('');
                swal('Error', data_msg, 'error');    
                 
            }
            
        });
    }
}

// decode special entities html to normal text ayy 
function decodeEntities(encodedString) {
  var textArea = document.createElement('textarea');
  textArea.innerHTML = encodedString;
  return textArea.value;
}