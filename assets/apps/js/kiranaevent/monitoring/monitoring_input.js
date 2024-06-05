/*
@application    : Kiranaku v2 MODULE cctv
@author 		: Airiza Yuddha (8347)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

$(document).ready(function () {
	/*aktifkan ketika live jangan di hapus */
	var tanggal_sekarang= moment().date(); 
	var bulan_sekarang	= moment().month()+1;
    var tahun_sekarang 	= moment().year();
    var y 		= tahun_sekarang+'-'+bulan_sekarang+'-'+tanggal_sekarang;
	var nowday	= moment(y,"YYYY-MM-DD").day(); // get day 6 is saturday 
	var addaction = '';
	if(ho != 'y'){
		if((nowday == 6 || nowday == 5)){
			$('#divAddButton').html('');
				// addaction = "<a href='#' class='edit-group-monitoring' data-edit='"+datamonths+"'><i class='fa fa-pencil-square-o'></i>Edit </a>";
			addaction = '<button type="button" class="btn btn-sm btn-success pull-right" id="add_monitoring_button">Tambah Data</button>';
		}
	} else if(ho == 'y'){
		$('#divAddButton').html('');
		addaction = '<button type="button" class="btn btn-sm btn-success pull-right" id="add_monitoring_button">Tambah Data</button>&nbsp;&nbsp;';
		if(nik == '7849'){
			addaction += '<button type="button" class="btn btn-sm btn-success pull-right" id="sync_monitoring_button">Sync Data</button>';
			addaction += '<div class="col-sm-2 pull-right"><input type="text" class="form-control " id="tahun_sync" name="tahun_sync" autocomplete="off" placeholder="pilih tahun sync"> <div class="col-sm-6">';
		}
	}
	$('#divAddButton').html(addaction);
	
	
	// date pitcker
    $('#filtertahun').datepicker({
        startView: 'year',
        minViewMode: "years",
        format: 'yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    }).on("changeDate", function () {
        var tahun = $("#filtertahun").val();
        get_lokasi(null,tahun);
    });

    // date pitcker
    $('#tahun_sync').datepicker({
        startView: 'year',
        minViewMode: "years",
        format: 'yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    
    });
    
	$(document).on("change", " #filterpabrik, #filtertahun ", function(e){
        //var tahun       = $("#tahun").val();
        var pabrik  = $("#filterpabrik").val();     	
        var tahun	= $("#filtertahun").val();
        get_lokasi(pabrik,tahun);
    });

 	$('.switch-onoff').bootstrapToggle();
     $(document).on("change", ".switch-onoff", function(e){
    	// console.log($(this).prop('checked'));
    	var stat = $(this).prop('checked');
    	if(stat == false){
    		$(this).parents(".row-lokasi").find('.keterangan').prop('readonly',false);
    		$(this).parents(".row-lokasi").find('.keterangan').prop('required',true);
    		console.log(1);
    	} else {
    		$(this).parents(".row-lokasi").find('.keterangan').prop('readonly',true);
    		$(this).parents(".row-lokasi").find('.keterangan').val('');
    		$(this).parents(".row-lokasi").find('.keterangan').prop('required',false);
    		console.log(2);
    	}
        // $('#add_monitoring_modal').modal('show');
    });
    // $("#GudangBokar_attch_fieldname").click(function(){
    //button after blur attch
 //    $(document).on("blur", ".spanfiles", function(e){
 //    	var idspan = $(this).attr('id');
 //    	var res = idspan.replace("span", "fieldname");
 //    	var theFilex = document.getElementById(res);
 //    	// initialize(theFilex,idspan);   
	// });

	// function initialize(thefile,idspan)
	// {
	// 	// var theFile = document.getElementById(idspan);
	//     document.body.onfocus = checkIt(thefile,idspan);
	//     // console.log('initializing');
	// }		    
	// function checkIt(theFile,idspan)
	// {
	// 	var res2 = idspan.replace("span", "fieldname");
	//     if($('#'+res2).val()){
	//     	color = '#696969';
	//     }
	//     else{ 
	//     	color = '';
	//     }

	//     $('#'+idspan).css('background-color', color);
	//     // document.body.onfocus = null;
	//     // console.log('checked');
	// }

	

    $(document).on("change", "#pabrik", function(e){
    	var plant = $(this).val();
    	$('#divdetail_titik').html('');
    	var week = $("input[name='waktu']").val();

    	var arraylok_parent = [];
    	var arraydot = [];
    	$('#dot_hidden').val('');
    	//header tab
    	$.ajax({
			url: baseURL + "cctv/monitoring/get/sublokasi",
			type: 'POST',
			dataType: 'JSON',
			data: {
				pabrik: plant
			},
			beforeSend: function () {
                var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                $("body .overlay-wrapper").append(overlay);
            },
			success: function (data) {
				if(data && week!= 0) {
					var dataheader = ''; 
					
					// var arraylok_parent = [];
					$.each(data, function(i,v){
						var activepane='';
						if(i < 1){activepane = 'active ';}
						dataheader += '<li class="litab '+activepane+'" id="'+v.nama+'"><a href="#'+v.nama+'page" '
										+'data-toggle="tab" aria-expanded="false">'+v.nama+'</a></li>';	
						arraylok_parent.push(v.nama);					

					});
					$('#divdetail_titik').append('<ul class="nav nav-tabs">'
							+ dataheader
							+'</ul>');

			// titik 
					$.ajax({
						url: baseURL + "cctv/monitoring/get/tabdot",
						type: 'POST',
						dataType: 'JSON',
						data: {
							pabrik: plant
						},
						
						success: function (data) {
							// console.log(data);
							$.each(data, function(i,v){
								arraydot.push({
												id_mdot 	: v.id_mdot,
				                            	dot : v.dot,
				                            	dot_fieldname : v.dot_fieldname,
				                            	id_sublokasi : v.id_sublokasi
											});
							});

					//detail tab
					    	$.ajax({
								url: baseURL + "cctv/monitoring/get/lokasi",
								type: 'POST',
								dataType: 'JSON',
								data: {
									pabrik: plant
								},
								
								success: function (data) {
									console.log(data);
									if(data) {
										var datadetail = ''; 
										dot_hiden 	= "";
										dot_count 	= 0;
										// $('#divdetail_titik').show();
										$.each(data, function(i,v){
											// console.log(data);
											var activepane='';
											if(i < 1){activepane = 'active ';}
											
											datadetail += '<div class="'+activepane+'tab-pane" id="'+arraylok_parent[i]+'page">';	

											$.each(arraydot, function(ii,vv){
												
												// console.log(vv.id_sublokasi+"|"+v.id_sub_lokasi);
												if(vv.id_sublokasi == v.id_sub_lokasi) // loop detail dot
												{
													datadetail += ''
															+'<div class="row-lokasi">'
															+' 	<div class="row"> '
															+' 		<div class="col-sm-12"> '
															+'		<label for="'+vv.dot+'" class="col-sm-3 pull-left">'+vv.dot+'</label>'
															+'		<div class="col-sm-2" >'
															+'			<input class="switch-onoff" type="checkbox" name="condition_fieldname'+vv.id_mdot+'" '
												            +'    			id="'+vv.dot_fieldname+'_fieldname" checked data-toggle="toggle">'
															+'		</div>'
															+'		<div class="col-sm-4 pull-left form-group">'
															+'			<div class="">'
															// +'			<div class="input-group-addon"> Keterangan </div>'
															// +'			<input type="text" class="form-control keterangan" name="keterangan_fieldname'+vv.id_mdot+'" '
															// +'				id="'+vv.dot_fieldname+'_keterangan_fieldname" placeholder="Keterangan" readonly="readonly">'
															+' 				<textarea class="form-control keterangan" rows="3"  name="keterangan_fieldname'+vv.id_mdot+'" '
															+'				id="'+vv.dot_fieldname+'_keterangan_fieldname" placeholder="Keterangan" readonly="readonly"></textarea>'
																		
															+'			</div>'
															+'		</div>'
															// +' 		<div class="col-sm-3 pull-left" ><input multiple type="file" name="attch'+vv.id_mdot+'[]" id="'+vv.dot_fieldname+'_attch_fieldname"></div>'
															+'		<div class="col-sm-3 pull-left" ><div class="fileinput fileinput-new" data-provides="fileinput">'
                                            				+'			<div class="btn-group btn-sm no-padding">'
                                                			+'				<a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox="image" data-type="image"><i class="fa fa-search"></i></a>'
                                                			+'				<a class="btn btn-facebook btn-file">'
                                                    		+'				<div class="fileinput-new">Attachment</div>'
                                                    		+'				<div class="fileinput-exists"><i class="fa fa-edit"></i></div>'
                                                    		+'				<input type="file" name="attch'+vv.id_mdot+'[]" id="'+vv.id_mdot+'_attch_fieldname"></a>' 
                                                			+'				<a href="#" class="btn btn-pinterest fileinput-exists"data-dismiss="fileinput"><i class="fa fa-trash"></i></a>'
                                            				+'			</div>'
                                        					+'		</div></div>'
															
															+' 	</div>'
															+' 	</div>'
															+'</div>';
													
													// <span class="btn btn-default btn-file spanfile" id="'+vv.dot_fieldname+'_attch_span"> Browse </span>  name="lampiran" id="lampiran"
													dot_hiden += vv.id_mdot+"|"+vv.dot+",";	
													dot_count++;		
												}
											});
											datadetail += "</div>";
										});
										$('#dot_hidden').val(dot_hiden);
										$('#hidden_file_count').val(dot_count);
										$('#divdetail_titik').append('<div class="tab-content">'
																		+ datadetail
																		+'</div>');
										$('.switch-onoff').bootstrapToggle();
										$('#divdetail_titik').show();
										$('.fieldset-success').show();
									}
								}
							});
						}
					});
				} else {
					alert("Data Master penentuan hari kerja pabrik belum Sync SAP ! Hubungi IT Support Ho terkait.");
					location.reload();
				}
			},
			complete: function () {
                $("body .overlay-wrapper .overlay").remove();                
            }
		});
	}); 
	
	//open modal for add     
    $(document).on("click", "#add_monitoring_button", function(e){
    	resetform();
        $('#add_monitoring_modal').modal('show');
        if(ho == 'y' && $('#id_hide').val() == 0){
			$('#divweekho').show();
			$('#divweekplant').hide();
		} else {
			$('#divweekho').hide();
			$('#divweekplant').show();
		}
    });
    //open modal for add     
    // $(document).on("change", "#week_display", function(e){
    	
    //     $('#week_hidden').val($(this).val());
    //     $('#week_hidden_bc').val($(this).val());
        
    // });
	
	//submit form
	$(document).on("click", "button[name='action_btn']", function (e) {
		e.preventDefault();
		var empty_form = validate(".form-trans-monitoring",true);
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-trans-monitoring")[0]);
				// add proces spiner
				var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
		        $(".modal-dialog").append(overlay);
				$.ajax({
					url: baseURL + 'cctv/monitoring/save/data',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
			
					success: function (data) {
						// console.log(data);
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
							$(".modal-dialog").remove(); 
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
							$(".modal-dialog").remove(); 
						}

					}
					
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		
		
	});

	// reload new 
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
    //edit action
	$(document).on("click", ".edit-group-monitoring", function (e) {
		$(".form-master-dot input, .form-master-dot select").val(null).trigger("change");
		var valuehide = $(this).data("edit");
		$('#myModalLabel').html("Ubah Data");
		$('#divdetail_titik').html('');
    	var arraylok_parent = [];
    	var arraydot = [];
    	$('#dot_hidden').val('');

    	// form for backdate
    	$('#divweekho').hide();
		$('#divweekplant').show();
		// $('.switch-onoff').bootstrapToggle('destroy');
		$.ajax({
			url: baseURL + "cctv/monitoring/get/dataedit",
			type: 'POST',
			dataType: 'JSON',
			data: {
				dataedit: $(this).data("edit")
			},			
			beforeSend: function () {
                var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                $("body .overlay-wrapper").append(overlay);
            },
			success: function (data) {
				
				var datatitik = data.var1;
				if(data) {

					//show detail					
					var plant = '';
					$.each(data.var2, function(i,v){
					    // do something with `substr[i]`
					    plant = v.plant;

					});
					$.ajax({
						url: baseURL + "cctv/monitoring/get/sublokasi",
						type: 'POST',
						dataType: 'JSON',
						data: {
							pabrik: plant
						},
						success: function (data) {
							if(data) {
								var dataheader = ''; 
								
								// var arraylok_parent = [];
								$.each(data, function(i,v){
									var activepane='';
									if(i < 1){activepane = 'active ';}
									dataheader += '<li class="litab '+activepane+'" id="'+v.nama+'"><a href="#'+v.nama+'page" '
													+'data-toggle="tab" aria-expanded="false">'+v.nama+'</a></li>';	
									arraylok_parent.push(v.nama);					

								});
								$('#divdetail_titik').append('<ul class="nav nav-tabs">'
										+ dataheader
										+'</ul>');

						// titik 
								$.ajax({
									url: baseURL + "cctv/monitoring/get/tabdot",
									type: 'POST',
									dataType: 'JSON',
									data: {
										pabrik: plant
									},
									
									success: function (data) {
										// console.log(data);
										$.each(data, function(i,v){
											arraydot.push({
															id_mdot 	: v.id_mdot,
							                            	dot : v.dot,
							                            	dot_fieldname : v.dot_fieldname,
							                            	id_sublokasi : v.id_sublokasi
														});
										});

								//detail tab
								    	$.ajax({
											url: baseURL + "cctv/monitoring/get/lokasi",
											type: 'POST',
											dataType: 'JSON',
											data: {
												pabrik: plant
											},
											
											success: function (data) {
												if(data) {
													var datadetail = ''; 
													dot_hiden 	= "";
													dot_count 	= 0;
													// $('#divdetail_titik').show();
													$.each(data, function(i,v){
														// console.log(data);
														var activepane='';
														if(i < 1){activepane = 'active ';}
														
														datadetail += '<div class="'+activepane+'tab-pane" id="'+arraylok_parent[i]+'page">';	

														$.each(arraydot, function(ii,vv){
															
															// console.log(vv.id_sublokasi+"|"+v.id_sub_lokasi);
															if(vv.id_sublokasi == v.id_sub_lokasi) // loop detail dot
															{
																datadetail += ''
																		+'<div class="row-lokasi">'
																		+' 	<div class="row"> '
																		+'		<label for="'+vv.dot+'" class="col-sm-3 pull-left">'+vv.dot+'</label>'
																		+'		<div class="col-sm-2" >'
																		+'			<input class="switch-onoff" type="checkbox" name="condition_fieldname'+vv.id_mdot+'" '
															            +'    			id="'+vv.dot_fieldname+'_fieldname" checked data-toggle="toggle">'
																		+'		</div>'
																		+'		<div class="col-sm-4 pull-left form-group" >'
																		+'			<div class="">'
																		// +'			<div class="input-group-addon"> Keterangan </div>'
																		// +'			<input type="text" class="form-control keterangan" name="keterangan_fieldname'+vv.id_mdot+'" '
																		// +'				id="'+vv.dot_fieldname+'_keterangan_fieldname" placeholder="Keterangan" readonly="readonly">'
																		+' 				<textarea class="form-control keterangan" rows="3" name="keterangan_fieldname'+vv.id_mdot+'" '
																		+'				id="'+vv.dot_fieldname+'_keterangan_fieldname" placeholder="Keterangan" readonly="readonly"></textarea>'
																		+'			</div>'
																		+'		</div>'
																		//+' 		<div class="col-sm-2 pull-left" > <input multiple type="file" name="attch'+vv.id_mdot+'[]" id="'+vv.dot_fieldname+'_attch_fieldname"></div>'
																		+'		<div class="col-sm-3 pull-left" ><div class="fileinput fileinput-new" id="fileinput-'+vv.id_mdot+'" data-provides="fileinput">'
			                                            				+'			<div class="btn-group btn-sm no-padding">'
			                                                			+'				<a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox="image'+vv.id_mdot+'" data-type="image"><i class="fa fa-search"></i></a>'
			                                                			+'				<a class="btn btn-facebook btn-file">'
			                                                    		//+'				<div class="fileinput-new"><i class="fa fa-plus"></i></div>'
			                                                    		+'				<div class="fileinput-new">Attachment</div>'
			                                                    		+'				<div class="fileinput-exists"><i class="fa fa-edit"></i></div>'
			                                                    		+'				<input type="file" name="attch'+vv.id_mdot+'[]" id="'+vv.id_mdot+'_attch_fieldname"></a>' 
			                                                			+'				<a href="#" class="btn btn-pinterest fileinput-exists"data-dismiss="fileinput"><i class="fa fa-trash"></i></a>'
			                                            				+'			</div>'
			                                        					+'		</div></div>'
																		+' 	</div>'
																		+'</div>';
																// <span class="btn btn-default btn-file spanfile" id="'+vv.dot_fieldname+'_attch_span"> Browse </span>
																dot_hiden += vv.id_mdot+"|"+vv.dot+",";	
																dot_count++;																		
															}

													    	
														});
														datadetail += "</div>";
													});
													$('#dot_hidden').val(dot_hiden);
													$('#hidden_file_count').val(dot_count);
													// setTimeout(function(){ 

														$('#divdetail_titik').append('<div class="tab-content">'
																+ datadetail
																+'</div>');
														$('.switch-onoff').bootstrapToggle();
													// console.log(datatitik);
													// set form edit
													$.each(datatitik, function(i,v){	
														// if (KIRANAKU.isNullOrEmpty(v.attch, false, true)) {
														var existfile = '';
														// var exists = pattern.test(v.attch);
														// if(v.attch.indexOf(pattern) != -1){
														if ( v.attch.match( /(.jpg|.png)/ ) ) {
														  //true statement, do whatever
															existfile = true;
														}else{
														  //false statement..do whatever
														  	existfile = false;
														}
														
														if (v.attch.length <= 52 && existfile == false ) {
										                    let divFileinput = $('#fileinput-'+v.id_mdot);
										                    divFileinput.removeClass('fileinput-exists');
										                    divFileinput.addClass('fileinput-new');
										                    divFileinput.find('[data-dismiss="fileinput"]').removeClass('hide');
										                } else {
										                    let divFileinput = $('#fileinput-'+v.id_mdot);
										                    divFileinput.removeClass('fileinput-new');
										                    divFileinput.addClass('fileinput-exists');

										                    divFileinput.find('.fileinput-zoom').attr('href', v.attch);
										                    divFileinput.find('[data-dismiss="fileinput"]').addClass('hide');
										                    // $('#gambar_old', modal).attr(data.detail.gambar);
										                }								    																		
														//var x = "'01"+'-'+v.month+'-'+v.year+"'";
														$("input[name='id_hide']").val(valuehide);
														// var x = "'"+v.year+'-'+v.month+"-01'";														 
														var monthyear = moment(v.month, 'M').format('MMM')+" "+moment(v.year, "YYYY").format('YYYY');
														//plant
														$("select[name='pabrik']").val(v.plant);
														$("select[name='pabrik'] option[value!='"+v.plant+"']").hide();
														// week 
														$("input[name='waktu']").val(v.week);
														$("input[name='week_hidden']").val(v.week);
														$("input[name='month_hidden']").val(v.month);
														$("input[name='year_hidden']").val(v.year);
														$("#monthyear_gaddon").html(monthyear);
														
														$("input[name='week_hidden_bc']").val(v.week);
														$("input[name='month_hidden_bc']").val(v.month);
														$("input[name='year_hidden_bc']").val(v.year);
														//detail
														// var valcondition = "";
														var valcondition = '';
														if(v.condition=='OFF'){
															
															valcondition = 'off';
															$("input[name='condition_fieldname"+v.id_mdot+"']").removeAttr('checked');
														} else {
															
															valcondition = 'on';
															$("input[name='condition_fieldname"+v.id_mdot+"']").attr('checked');
														}
														// console.log(x+'|'+valuehide+'|'+v.id_mdot+'|'+valcondition);
														$("input[name='condition_fieldname"+v.id_mdot+"']").bootstrapToggle(valcondition);
														$("textarea[name='keterangan_fieldname"+v.id_mdot+"']").html(v.note_monitoring);
														// end detail 												
													});
													// }, 2000);
													//header tab
													
													$('#divdetail_titik').show();
													$('.fieldset-success').show();

												} 
											}
											
										});
									}
									
								});

							}
						}
						
					});
					
			    	$('#add_monitoring_modal').modal('show');
					
				}
			},
			complete: function (data) {
			    $("body .overlay-wrapper .overlay").remove(); 
			    
			}
		});
		e.preventDefault();
		return false;
	});

	//sync action
	$(document).on("click", "#sync_monitoring_button", function (e) {
		var tahun = $('#tahun_sync').val();
		$.ajax({
			url: baseURL + "cctv/monitoring/get/data_achv",
			type: 'POST',
			dataType: 'JSON',
			data: {
				tahun_sync: tahun
			},			
			beforeSend: function () {
                var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                $("body .overlay-wrapper").append(overlay);
            },
			success: function (data) {
				
				// var datatitik = data;
				if(data) {
					alert("success");
					// console.log(data);
					location.reload();
				}
			},
			complete: function (data) {
			    $("body .overlay-wrapper .overlay").remove(); 
			    
			}
		});
		e.preventDefault();
		return false;
	});

	// get temporary file name for preview
	$(document).on("change.bs.fileinput", ".fileinput", function(e){
		readURL($('input[type="file"]',$(this))[0], $('.fileinput-zoom',$(this)));
	    console.log($('input[type="file"]',$(this))[0]);
	});

	// $("#bln_thn").datepicker({
	//     onSelect: function(dateText, inst) {
	//         var date = $(this).val();
	//         var time = $('#time').val();
	//         alert('on select triggered');
	//         $("#start").val(date + time.toString(' HH:mm').toString());
	//         console.log(date + time.toString(' HH:mm').toString());
	//     }
	// });

// set hiden value for backdate uncomplete
	$(document).on("changeDate", "#bln_thn", function(e){
		var valuethis 	= $(this).val();
		valuethis 		= valuethis.split(".");
		var bulan 		= valuethis[0];
		var tahun 		= valuethis[1];
		var tanggal 	= $(this).val();
		var x 			= 0;
		console.log(tanggal);
		$.ajax({
			url: baseURL + "cctv/monitoring/get/listweek",
			type: 'POST',
			dataType: 'JSON',
			data: {
				dateinput  : tanggal
			},
			
			success: function (data) {
				x++;
				var datax = [];
				if(data){
					// console.log(data);
					$.each(data, function(i,v){
						datax.push(v);
					});
					console.log(datax);
					$('#divweekheader').html('');
					/*
						<select class="form-control input-xxlarge " name="pabrik" id="pabrik" style="width: 100%;"  required>
							                				<option value="0">Silahkan pilih pabrik</option>
					*/
					var data_dropdownweek = '<select class="form-control" name="week_display" id="week_display" style="width: 100%;"  required>';
					$.each(datax, function(i,v){
						data_dropdownweek += '<option value="'+v.WKMNT+'">'+v.WKMNT+'</option>';
					});
					data_dropdownweek += '</select>';
					$('#divweekheader').html(data_dropdownweek);
					$('#month_hidden').val(bulan);
				    $('#month_hidden_bc').val(bulan);
				    $('#year_hidden').val(tahun);
				    $('#year_hidden_bc').val(tahun);	
				    $('#week_hidden').val(1);
        			$('#week_hidden_bc').val(1);	
				}

			}
		});		
		
	});
	// set hiden value for backdate uncomplete
	$(document).on("change", "#week_display", function(e){
		var valuethis 	= $(this).val();
		$('#week_hidden').val(valuethis);
		$('#week_hidden_bc').val(valuethis);		
		
	});




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
function getAmountOfWeekDaysInMonth(date, weekday) {
    date.date(1);
    var dif = (7 + (weekday - date.weekday())) % 7 + 1;
    console.log("weekday: " + weekday + ", FirstOfMonth: " + date.weekday() + ", dif: " + dif);
    return Math.floor((date.daysInMonth() - dif) / 7) + 1;
}
var lokasis = [];
var lokasi = [];
// get_lokasi(); tambah filter ===================================================

function get_lokasi(pabrik=null,tahun=null){
	// pabrik = 'ABL1';
	// var nullarray = [];
	lokasis = [];
	lokasi = [];
	$.ajax({
		url: baseURL+'cctv/master/get/mdot',
        type: 'POST',
        dataType: 'JSON',  
        data: {            
            pabrik  : pabrik,
            // tahun   : tahun
        },      
        success: function(data){
        	$.each(data,function(ilokasi,vlokasi){
        		// console.log(data);
        		lokasis.push({
        			id_mdot : vlokasi.id_mdot,
        			nama : vlokasi.dot,
        			plant: vlokasi.plant
        		});
        		// if(jQuery.inArray(vlokasi.dot,lokasis) === -1){
	        		lokasi.push({
	        			// id_mdot : vlokasi.id_mdot,
	        			nama : vlokasi.dot
	        		});
	        	// }
        	})
        	// console.log(lokasis);
			// get_datas(pabrik_sess);

			get_datas(pabrik,tahun);
        }
	})
}
function get_datas(pabrik=null,tahun=null){
	var x = 1;	

	var datas_dot = [];
    $.ajax({
        url: baseURL+'cctv/monitoring/get/data',
        type: 'POST',
        dataType: 'JSON',
        data: {            
           pabrik		: pabrik,
           tahun 		: tahun
        },
        beforeSend: function () {
            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
            $("body .overlay-wrapper").append(overlay);
        },
        success: function(data){
        	// console.log(data);
            //var t   = $('.my-datatable-extends').DataTable(); table_main
            // var t   = $('#table_main').DataTable();
            $("#div_mainTable").html("");
            $('#divOut').html("");
            $("#div_mainTable").append(""
                            +'<table class="table table-bordered  " id="table_main" >'
                            +'<thead>'
                            +' 	<tr>'
                            +'    <th width=400 rowspan="2" >Titik CCTV</th>'
                            +'    <th class="text-center" data-month="month1" colspan="5">Month -1 </th>'
                            +'    <th class="text-center" data-month="month2" colspan="5">Month 1 </th>'
                            +'	</tr>'
                            +'	<tr>'
                            +'    <th class="text-align-top text-center" data-headerweek = "w1_m1">W1</th>'                              
                            +'    <th class="text-align-top text-center" data-headerweek = "w2_m1">W2</th>'
                            +'    <th class="text-align-top text-center" data-headerweek = "w3_m1">W3</th>'
                            +'    <th class="text-align-top text-center" data-headerweek = "w4_m1">W4</th>'
                            +'    <th class="text-align-top text-center" data-headerweek = "w5_m1">W5</th>'
                            +'    <th class="text-align-top text-center" data-headerweek = "w1_m2">W1</th>'                              
                            +'    <th class="text-align-top text-center" data-headerweek = "w2_m2">W2</th>'
                            +'    <th class="text-align-top text-center" data-headerweek = "w3_m2">W3</th>'
                            +'    <th class="text-align-top text-center" data-headerweek = "w4_m2">W4</th>'
                            +'    <th class="text-align-top text-center" data-headerweek = "w5_m2">W5</th>'    
                            +'	</tr>'                        
                            // +'    <th class="text-center">Action</th>'
                            +'</thead>'
                            +'<tbody id="divOut">'
                            );
    						// console.log(lokasis);
                            var datas = [];                          
                            var first_dot = "";
                            $.each(lokasi,function(i,val) {
                            	if(datas == ''){
                            		first_dot = val.nama;
                            	}
                            	datas_dot.push(val.nama);
                            })
                            
                            // get periode
							var tanggal_sekarang= moment().date(); 
							var bulan_sekarang	= moment().month()+1;
                            var tahun_sekarang 	= moment().year();
                           	var minggu_sekarang = '';
                           	
							var x 		= tahun_sekarang+'-'+bulan_sekarang+'-01';
							var y 		= tahun_sekarang+'-'+bulan_sekarang+'-'+tanggal_sekarang;
							// var nowday	= moment(y).isoWeekday(); // get day 6 is saturday 
							var nowday	= moment(y,"YYYY-MM-DD").day(); // get day 6 is saturday 
							var m1 		= moment(x,'YYYY-MM-DD').add(-1,"months").format('YYYY-MM');
    						var m2 		= moment(x,'YYYY-MM-DD').add(0,"months").format('YYYY-MM');
    						var m1_name = moment(x,'YYYY-MM-DD').add(-1,"months").format('MMMM YYYY');
    						var m2_name = moment(x,'YYYY-MM-DD').add(0,"months").format('MMMM YYYY');
							// console.log(moment(y).isoWeekday()); // get day 6 is saturday 11|1|ABL1|2018
							// console.log(nowday);
							
							var datamonths = '';
							// bulan+"|"+minggu_sekarang+"|"+pabrik+"|"+tahun;

							datamonths = getweek(bulan_sekarang,y,pabrik,tahun_sekarang);
							var datamonth_explode = datamonths.split("|");
							var w = datamonth_explode[1];
							var minggulalu = Number(w) - 1;
							// console.log(datamonths);
							var editaction='';
							//set header periode
							$('th[data-month="month1"]').html(m1_name);
							$('th[data-month="month2"]').html(m2_name);
                        	// set action edit w1_m2
                        	
                          	// loop year 
                          	// console.log(datas_dot+"|"+datas_dot.length);

                          	$.each(datas_dot, function(idot,vdot){
                          		$("#divOut").append("<tr data-tt-id='"+vdot+"'><td>"+vdot+"</td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w1_m1'></td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w2_m1'></td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w3_m1'></td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w4_m1'></td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w5_m1'></td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w1_m2'></td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w2_m2'></td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w3_m2'></td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w4_m2'></td>"
                          							+"<td class='text-center' data-mon='"+vdot+"_w5_m2'></td>"
                          							+"</tr>"); 	                          		
                          	});
                          	
                          	var datas_m1 = [];
                          	var datas_m2 = [];
                          	// console.log(data);
                          	// console.log(data.var1);
                          	//loop for data
                          	var week_maks1 = 0;
                          	$.each(data.var1, function(i,v){
                          		var month1 = String(v.month);
                          		if(month1.length == 1) month1 = "0"+month1; else month1 = month1;
                          		// set array m-1
								if(v.year+'-'+month1 == m1 && v.plant == pabrik)
                          			datas_m1.push({
                          				plant 		: v.plant,
                          				dot 		: v.dot,                          				
                          				condition 	: v.condition,
                          				week 		: v.week,
                          				month 		: v.month,
                          				year 		: v.year
                          			});
                          		if(week_maks1==0 || week_maks1 < v.week ){
                          			week_maks1=v.week;
                          		}
                          	});
                          	// console.log(+datas_m1);
                          	//loop for data 2

                          	$.each(data.var1, function(i,v){
                          		var month2 = String(v.month);
                          		if(month2.length == 1) month2 = "0"+month2; else month2 = month2;
                          		if(v.year+'-'+month2 == m2 && v.plant == pabrik)
                          			datas_m2.push({
                          				plant 		: v.plant,
                          				dot 		: v.dot,                          				
                          				condition 	: v.condition,
                          				week 		: v.week,
                          				month 		: v.month,
                          				year 		: v.year
                          			});
                          	});
                          	 // console.log(datas_m2);
                          	
                          	//cetak data m
                          	var jumlah_edit=0;
                          	$.each(datas_m2, function(i,v){
                          		var datamon2 = v.dot+'_w'+v.week+'_m2';
                          		var batas_edit = v.week - 2 ;
                          		
                          		// ayy 11.01.2019
                          		var datamonth_ho2 = v.month+"|"+v.week+"|"+v.plant+"|"+v.year;                          		
                          		var valuedata2 = "";
								if(v.condition == 'ON'){
	                       			valuedata2 = "<div class='label label-success'>"+v.condition+"</div>";
	                       		} else {
	                       			valuedata2 = "<div class='label label-danger'>"+v.condition+"</div>";	
	                       		}
                          		$('td[data-mon="'+datamon2+'"]').html(valuedata2);
                          		// console.log(v.week+' == '+w);
                          		// ayy 11.01.2019
                          		if(ho != 'y') {
	                          		if((nowday == 6 || nowday == 5) && v.week == w){ //aktifkan ketika live
	                          		// if(v.week == w){
										editaction = "<a href='#' class='edit-group-monitoring' data-edit='"+datamonths+"'><i class='fa fa-pencil-square-o'></i>Edit </a>";
									} 
									$('th[data-headerweek="w'+w+'_m2"]').html('W'+w+' <br>'+editaction);
                          		} else if(ho == 'y'){
                          			// console.log(v.week+" == "+minggulalu+" == "+w);
                          			// if(v.week > batas_edit && v.week <= w && v.dot == first_dot && jumlah_edit < 2){ //aktifkan ketika live
                          			if(v.dot == first_dot &&  (Number(v.week) == Number(w) || Number(v.week) == Number(minggulalu) ) ){ //aktifkan ketika live
                          				editaction = "<a href='#' class='edit-group-monitoring' data-edit='"+datamonth_ho2+"'><i class='fa fa-pencil-square-o'></i>Edit </a>";	
                          				$('th[data-headerweek="w'+v.week+'_m2"]').html('W'+v.week+' <br>'+editaction);
                          				jumlah_edit++;
                          			}                          			
                          		}
                          	});
                          	// console.log(jumlah_edit);
                          	//cetak data m-1
                          	$.each(datas_m1, function(i,v){
                          		var datamon = v.dot+'_w'+v.week+'_m1';
                          		// ayy 11.01.2019
                          		var datamonth_ho1 = v.month+"|"+v.week+"|"+v.plant+"|"+v.year;
                          		var batas_edit = v.week - 2 ;
                          		var valuedata = "";
								if(v.condition == 'ON'){
	                       			valuedata = "<div class='label label-success'>"+v.condition+"</div>";
	                       		} else {
	                       			valuedata = "<div class='label label-danger'>"+v.condition+"</div>";	
	                       		}
                          		$('td[data-mon="'+datamon+'"]').html(valuedata);
                          		// ayy 11.01.2019
                          		if(ho == 'y'){
                          			
                          			if(jumlah_edit < 2 && v.week == week_maks1 && ((datas_m2.length > 1 && w > 1) || (w==1) ) ){//aktifkan ketika live
                          				editaction = "<a href='#' class='edit-group-monitoring' data-edit='"+datamonth_ho1+"'><i class='fa fa-pencil-square-o'></i>Edit </a>";	
                          				$('th[data-headerweek="w'+v.week+'_m1"]').html('W'+v.week+' <br>'+editaction);
                          				jumlah_edit++;
 									}
                          		}
                          		
                          	});
                          	// console.log(w);
                          	// $('th[data-headerweek="w'+w+'_m2"]').html('W'+w+' <br>'+editaction);

            //set null if data no available
            if(datas_m1.length < 1 && datas_m2.length < 1 ) {
            	$("#divOut").html('');            	
              	$("#divOut").append("<tr readonly data-tt-id='nulldata'><td colspan=13>No data available in table</td></tr>");  
            }      
                          	
            $("#div_mainTable").append(""
                            +'</tbody>'
                            +'</table>');
                            
            $('#table_main').treetable({ expandable: true });         
        },
        complete: function () {
            $("body .overlay-wrapper .overlay").remove();
   //          var tanggal_sekarang= moment().date(); 
			// var bulan_sekarang	= moment().month()+1;
   //          var tahun_sekarang 	= moment().year();
   //         	var minggu_sekarang = '';
           	
			// var x 		= tahun_sekarang+'-'+bulan_sekarang+'-01';
			// var y 		= tahun_sekarang+'-'+bulan_sekarang+'-'+tanggal_sekarang;
			// datamonths = getweek(bulan_sekarang,y,pabrik,tahun_sekarang);
			// var datamonth_explode = datamonths.split("|");
			// var w = datamonth_explode[1];
   //          $('.box-title').html("Data Monitoring CCTV"+y+"week "+w);

        }
    });
}
function getweek(bulan,tanggal,pabrik,tahun){
	var datamonth = 'error';
	$.ajax({
		url: baseURL+'cctv/monitoring/get/week',
        type: 'POST',
        async: false,  
        data: {            
            dateinput  : tanggal
            // tahun   : tahun
        },      
        success: function(datadate){
        	minggu_sekarang = datadate.replace('"', "");
        	datamonth = bulan+"|"+minggu_sekarang+"|"+pabrik+"|"+tahun;
        }
	}); 
	// datamonth.replace('"', "");
	return datamonth.replace('"', "");
}
function resetform(){
	$('.litab').show();
	$('a[href*="page"]').show();
	$('.litab').removeClass("active");
	$('div[id*="page"]').removeClass("active");

	$('.litab').eq( "0" ).addClass(" active ");
	$('div[id*="page"]').eq( "0" ).addClass(" active ");

	$("input[name='id_hide']").val("0");
	$('#myModalLabel').html("Tambah Data");
	// kondisi lokasi
	$("input[name*='condition_fieldname']").attr('checked');
	$("input[name*='condition_fieldname']").bootstrapToggle('on');
	//keterangan kondisi lokasi
	$("input[name*='keterangan_fieldname']").val('');
	$("input[name*='keterangan_fieldname']").attr('readonly="readonly"');

	$("select[name='pabrik'] option").show();
	$("select[name='pabrik']").val(0);
	//plant
	// $("select[name='pabrik']").val(v.plant);
	// $("select[name='pabrik'] option[value!='"+v.plant+"']").hide();
	// week 
	var hiddenweek = $("input[name='week_hidden_bc']").val();
	var hiddenmonth = $("input[name='month_hidden_bc']").val();
	var hiddenyear = $("input[name='year_hidden_bc']").val();
	$("input[name='waktu']").val(hiddenweek);	
	$("input[name='week_hidden']").val(hiddenweek);
	$("input[name='month_hidden']").val(hiddenmonth);
	$("input[name='year_hidden']").val(hiddenyear);
	$('.fieldset-success').hide();
	$('#divdetail_titik').html('');
}
