/*
@application  : Dokumen Vendor
@author       : Lukman Hakim
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

$(document).ready(function() {

	// ======================ADMIN IT CREATE NEW ROOT FOLDER & SET ADMIN==================================================
	$("#root_folder").on("click", function(){
		$('#root_folder_modal').modal('show');			
	});

	$('#view_modal').on('hidden.bs.modal', function () {
		$("#show_file").empty();
	});

	$("#save_root_folder").on("click", function(){
	    var name 	= $("input[name='root_folder_name']").val();
	    if(name == ""){
	    	kiranaAlert("notOK", "New folder name couldn't be empty", "warning", "no");
	    	e.preventDefault();
			return false;
		}else if (name.includes("\\") == true){
			kiranaAlert("notOK", "Folder name can't contain backslash", "warning", "no");
        	e.preventDefault();
			return false;
        }else{
	    	$.ajax({
	    		url: baseURL+'folder/manage/new_root_folder',
				type: 'POST',
				dataType: 'JSON',
				data: {
					name      : name
				},
				success: function(data){
					// console.log(data);
					if(data.sts == 'OK'){
						kiranaAlert(data.sts, data.msg);
					}else{
						kiranaAlert(data.sts, data.msg, "error", "no");
					}
				}
			});
		}
    });

    $("#folder_admin").select2({
		allowClear: true,
		placeholder: {
		    id: "",
		    placeholder: "Leave blank to ..."
		},
    	ajax: {
        	url: baseURL+'folder/manage/user_data',
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
        	cache: true
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

    $("#folder_admin").on("select2:unselecting", function (e) {
	    $("select[name='folderAdmin']").val(null).trigger('change');
	});

	$("#set_adminFolder").on("click", function(){
		$.ajax({
    		url: baseURL+'folder/manage/get_data/folder',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_folder : 0
			},
			success: function(data){
				var output = "<option value='0'>Choose Folder</option>";
	            $.each(data, function(i,v){
	                output  += "<option value='"+v.id_folder+"'>"+v.nama+"</option>";
	            });	
	            $('#root_folder_list').html(output)
				$('#setAdmin_modal').modal('show');		
			}
		});
	});

    $(document).on("click", "button[name='submit_setAdmin']", function(e){
		var folder 		=  $("select[name='root_folder_list']").val();
		var adminfolder =  $("#folder_admin").val();
		// alert(folder+" - "+adminfolder);
		if (folder != '0') {
	    	$.ajax({
	    		url: baseURL+'folder/manage/set_root_folder_admin',
				type: 'POST',
				dataType: 'JSON',
				data: { 
					folder  : folder,
					admin 	: adminfolder
				},
				success: function(data){
					if(data.sts == 'OK'){
						kiranaAlert(data.sts, data.msg);
					}else{
						kiranaAlert(data.sts, data.msg, "error", "no");
					}
				}
			});	
		}else{
			kiranaAlert("notOK", "Choose Root Folder", "error", "no");
		}
	    
	    e.preventDefault();
		return false;
    });


    $(document).on("change", "#root_folder_list", function(){
		$("#folder_admin").val(null).trigger('change');
		var value = $("#root_folder_list").val();
	
		$.ajax({
    		url: baseURL+'folder/manage/get_nik',
			type: 'POST',
			dataType: 'JSON',
			data:{
				folder : value
			},
			success: function(data){
				var nik	= [];
				$.each(data, function(i, v){
					nik.push(v.id);
				});
				var control = $('#folder_admin').empty().data('select2');
				var adapter = control.dataAdapter;
				adapter.addOptions(adapter.convertToOptions(data));
				$('#folder_admin').trigger('change');
				$('#folder_admin').val(nik).trigger('change');
			}      
		});	
	
	});

	// ============================================END=====================================================


	// ================================GENERATE ROOT DATA==================================================

    sessionStorage.clear(); //  ketika di refresh apus sessionstorage

    //tambahan untuk notifikasi vendor
    var para = window.location.search;
    var key = para.split('=').pop();
    
    if (key != "") {
    	$.ajax({
			url: baseURL+'folder/manage/cek_table_param',
			type: 'POST',
			dataType: 'JSON',
			data: {
				key : key,
			},
			success: function(data){
				// console.log(data);
				if ((data.parent_admin_akses == 'yes') || (data.parent_admin_akses == 'no' && 
					data.parent_div_read == 'yes' && 
					data.parent_dept_read == 'yes' && 
					data.parent_level_read == 'yes' &&
					data.file_level_read == 'yes' &&
					data.file_div_read == 'yes' &&
					data.file_dept_read == 'yes')) {

					generate_table_by_param(data.id_folder, data.id_root_folder, data.parent_admin_akses, data.parent_div_write, data.parent_dept_write, data.parent_level_write);
				}else{
					swal({
						title : "VENDOR",
						text : "You don't have permission to access this file.",
			        	showConfirmButton: true,
			        	showCancelButton: false,
						confirmButtonText: "OK",
				        type : "warning",
				    }).then(
						function(){
    						location.href= baseURL+'folder/manage/vendor';
				    });
				}
			}
		});
    }
    else{

	    //CEK AKSES WRITE FOLDER VENDOR UNTUK MUNCULIN TOMBOL TOOLBAR
	    $.ajax({
			url: baseURL+'folder/manage/cek_akses_root_vendor',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_folder : 552,
			},
			success: function(data){
				if (data.akses_admin == 'yes') {
				 	$("#icon_new_folder").removeClass("hidden");
	    			$("#icon_upload").removeClass("hidden");
				}
				else if (data.akses_admin == 'no' && data.akses_divisi_write == 'yes' && data.akses_department_write == 'yes' && data.akses_level == 'yes' ){
					$("#icon_new_folder").removeClass("hidden");
	    			$("#icon_upload").removeClass("hidden");
				}
				else{
					$("#icon_new_folder").addClass("hidden");
	    			$("#icon_upload").addClass("hidden");
				}
			}
		});



	    $.ajax({
			url: baseURL+'folder/manage/get_data/folder-file',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_folder : 552,
				folder 	  : "dokumen vendor",
				isAdmin   : 552
			},
			success: function(data){
				// console.log(data);
				$('.datatable-folder').DataTable().destroy();
		        var t   = $('.datatable-folder').DataTable({
		        				order: [[5, 'asc']],
		                        ordering : true,
		                        scrollCollapse: true,
		                        scrollY: false,
		                        scrollX : true,
		                        bautoWidth: false,
		                        "iDisplayLength": 50,
		                        "paging": true,
		                        columnDefs: [
		                            { "className": "text-right", "targets": 2 },
		                            { "className": "text-right", "targets": 1 },
		                            { "className": "text-right", "targets": 3 },
		                            { "className": "text-right", "targets": 4 },
		                            { "visible": false, "targets": 5 },
		                        ],
		                    });
		        t.clear().draw();
				
				$.each(data, function (i, v) {
					if(i !== "grandparent"){
						$.each(v, function (id, val) {
							
							let icon 	   = "<i class='fa fa-folder icons_orange'></i>";
							let tipe 	   = "Folder";
							let ukuran	   = "";
							let id_row	   = val.id_folder;
							let parent     = val.parent_folder;
							let divAkses   = val.akses_divisi_write;
							let deptAkses  = val.akses_department_write;
							let deptRead   = val.akses_department_read;
							let adminAkses = val.isAdmin;
							let levelwrite = val.akses_level_write;
							let levelread  = val.akses_level_read;
							let title_atas = val.nama;
							let sortindex  = 0;

							if(i == "file"){
								tipe	= val.tipe;
								var fileClass = "fa-file-text icons_orange";
		                        switch (tipe) {
		                            case 'pdf' : fileClass = "fa-file-pdf-o icons_red";
		                                break;
		                            case 'doc' :
		                            case 'docx': fileClass = "fa-file-word-o icons_blue";
		                                break;
		                            case 'xls' :
		                            case 'xlsx': fileClass = "fa-file-excel-o icons_green";
		                                break;
		                            case 'mp4' :
		                            case 'webm': fileClass = "fa-file-video-o icons_blue";
		                                break;
		                            case 'png' :
		                            case 'jpg' :
		                            case 'gif' :
		                            case 'jpeg': fileClass = "fa-file-img-o icons_yellow";
		                                break;
		                        }

								ukuran 	= FileSize(val.ukuran);
								icon 	= "<i class='fa "+fileClass+"'></i>";
								id_row	= val.id_file;
								parent  = val.id_folder;
								sortindex = 1;
							}


							let rows = t.row.add( [
										                icon +" "+ val.nama,
										                generateDateFormats(val.tanggal_buat),
										                "",
										                tipe,
										                ukuran,
										                sortindex
										            ] ).draw( false ).node();

							$(rows).attr("data-id", id_row);
							$(rows).attr("data-title", title_atas);
							$(rows).attr("data-jenis", tipe);
							$(rows).attr("data-parent", parent);
							$(rows).attr("data-div", divAkses);
							$(rows).attr("data-dept", deptAkses);
							$(rows).attr("data-read", deptRead);
							$(rows).attr("data-admin", adminAkses);
							$(rows).attr("data-lwrite", levelwrite);
							$(rows).attr("data-lread", levelread);
						});
					}
				});

			}
		});
    }


	// ===============================================END===================================================

	// ================================GENERATE !ROOT DATA==================================================

	$(document).on("dblclick", ".datatable-folder tr", function(e){

    	var id_folder	= $(this).data("id"); 			
    	var tipe	    = $(this).data("jenis");
    	var parent 	    = $(this).data("parent");
    	var parent_div  = $(this).data("div");
  		var parent_dept = $(this).data("dept");
  		var read        = $(this).data("read");

  		var title_top 	= $(this).data("title");
  		// var title_top 	= title_tops.toUpperCase();

  		//att grandparent row
  		var toolbar     = $(this).data("toolbar");
  		var level 		= $(this).data("level");

  		var admin       = $(this).data("admin");
  		var level_write = $(this).data("lwrite");
  		var level_read  = $(this).data("lread");
		// var open = false;

  		if(tipe != 'Folder' && tipe != 'nav'){
    		id_folder = parent;
    	}
  		
  		$("#icon_new_folder").addClass("hidden");
    	$("#icon_upload").addClass("hidden");
    	
    	if (admin == 'no' && read == 'no' && level_read == 'no') {
			kiranaAlert("notOK", "You don't have permission to open this folder", "error", "no");
			e.preventDefault();
			return false;
    	}else if (admin == 'no' && read == 'yes' && level_read == 'no'){
    		kiranaAlert("notOK", "You don't have permission to open this folder", "error", "no");
			e.preventDefault();
			return false;
    	}else if(admin == 'no' && read == 'no' && level_read == 'yes'){
    		kiranaAlert("notOK", "You don't have permission to open this folder", "error", "no");
			e.preventDefault();
			return false;
    	}

    	// button new folder & upload Toolbar
    	if (id_folder == 0) { // root folder tidak ada perintah write, hanya admin it
    		sessionStorage.clear();
    		$("#icon_new_folder").addClass("hidden");
    		$("#icon_upload").addClass("hidden");
			$("#title-top").html("<strong>DOKUMEN VENDOR</strong>");

    	}else{

			if (admin == 'yes') { // jika admin toolbar write selalu muncul
				$("#icon_new_folder").removeClass("hidden");
				$("#icon_upload").removeClass("hidden");
			}
			else if (parent_div == 'yes' && parent_dept == 'yes' && level_write == 'yes'){ // parent yg sebelumnya di click punya akses write
				$("#icon_new_folder").removeClass("hidden");
				$("#icon_upload").removeClass("hidden");
			}
			else if (toolbar == 'yes' && level == 'yes'){ // MUST BE CHECK issue feedback
				$("#icon_new_folder").removeClass("hidden");
				$("#icon_upload").removeClass("hidden");
			}
			else{
				$("#icon_new_folder").addClass("hidden");
    			$("#icon_upload").addClass("hidden");
			}

			if (id_folder == 552) {

				$.ajax({
					url: baseURL+'folder/manage/cek_akses_root_vendor',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id_folder : 552,
					},
					success: function(data){
						// console.log(data);
						if (data.akses_admin == 'yes') {
						 	$("#icon_new_folder").removeClass("hidden");
			    			$("#icon_upload").removeClass("hidden");
						}
						else if (data.akses_admin == 'no' && data.akses_divisi_write == 'yes' && data.akses_department_write == 'yes' && data.akses_level == 'yes' ){
							$("#icon_new_folder").removeClass("hidden");
			    			$("#icon_upload").removeClass("hidden");
						}
						else{
							$("#icon_new_folder").addClass("hidden");
			    			$("#icon_upload").addClass("hidden");
						}
					}
				});

				$("#title-top").html("<strong>DOKUMEN VENDOR</strong>");

			}else{
				if (tipe == 'Folder' || tipe == 'nav') {
					$("#title-top").html("<strong style='text-transform:uppercase;'>FOLDER "+title_top+"</strong>");
					
				}
			}

    	}

    	if(parent == 552 ){
        	
        	sessionStorage.setItem("folder_admin", 552);
        	sessionStorage.setItem("id_new_folder", id_folder);
        	
    	}else if((parent != 552) && (tipe == 'nav' || tipe == 'Folder')){
        	sessionStorage.setItem("id_new_folder", id_folder);
        	sessionStorage.setItem("parent_write", level_write); // untuk validasi jika parent bisa write maka setelah user create file, lsg dapet akses write
    	
    	}
    	// Toolbar end


    	if (tipe == 'docx' ||tipe == 'doc' || tipe == 'xls' || tipe == 'xlsx' || tipe == 'swf' || tipe == 'vsd') {
    		id_folder = $(this).data("parent");
    		var id_file = $(this).data("id");
    		$.ajax({
	    		url: baseURL+'folder/manage/get_prev_name',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id_file,
					tipe      : tipe
				},
				success: function(data){
					var form = document.createElement("form");
			    	var element1 = document.createElement("input"); 

			    	form.method = "POST";
			    	form.action = baseURL+'folder/manage/download';

			    	element1.value='assets/'+data.link;
				    element1.name="link";
				    element1.type="hidden";

				    form.appendChild(element1); 

				    document.body.appendChild(form);

			    	form.submit();
				}
			});
		}		

    	if ($.inArray(tipe, ['mp4', 'webm']) >= 0) {
    		id_folder = $(this).data("parent");
			var id_file = $(this).data("id");
    		$.ajax({
	    		url: baseURL+'folder/manage/get_prev_name',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id_file,
					tipe: tipe,
					log: 'yes'
				},
				success: function (data) {
					if (data.exists == false) { 
						kiranaAlert("notOK", "File not found", "warning", "no");
						return false;
					}
					open = true;

					var output = '';
					output += '<div class="nav-tabs-custom">';
					output += '	<ul class="nav nav-tabs">';
					output += '		<li class="active"><a href="#video-tab" data-toggle="tab">Video</a></li>';
					output += '		<li><a href="#detail-tab" data-toggle="tab">Detail View</a></li>';
					output += '	</ul>';
					output += '</div>';
					output += '<div class="tab-content">';
					output += '	<div class="tab-pane active"id="video-tab">';
					output += '		<video class="video-js" controls controlsList="nodownload" style="width: 100%; height: 400px" preload="metadata">';
					output += '			<source src="'+baseURL+'assets/'+data.link+'" type="video/'+tipe+'"></source>';
					output += '		</video>';
					output += '		<code>Total Views: '+(data.log.length > 0 ? data.log[0].total_count : '0')+'</code>';
					output += '	</div>';
					output += '	<div class="tab-pane"id="detail-tab">';
					output += '		<table class="table table-bordered table-striped table-responsive table-hover">';
					output += '			<thead>';
					output += '				<th>NIK</th>';
					output += '				<th>Nama</th>';
					output += '				<th>Views</th>';
					output += '			</thead>';
					output += '			<tbody>';
					if (data.log.length > 0) {
						$.each(data.log, function (i, v) {
							output += '			<tr>';
							output += '				<td class="text-center">' + v.nik + '</td>';
							output += '				<td class="text-center">' + v.nama + '</td>';
							output += '				<td class="text-center">' + v.total_view_user + '</td>';
							output += '			</tr>';
						});
					}
					output += '			</tbody>';
					output += '		</table>';
					output += '	</div>';
					output += '</div>';
       				$("#show_file").html(output);
				},
				complete: function () { 
					$("#view_modal .table").DataTable();
					if(open == true)
						$('#view_modal').modal('show');	
				}
			});
    	}		

    	if (tipe == 'pdf') {
    		id_folder = $(this).data("parent");
    		var id_file = $(this).data("id");
    		$.ajax({
	    		url: baseURL+'folder/manage/get_prev_name',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id_file,
					tipe      : tipe
				},
				success: function(data){
					console.log(data);
					// if (data.exists == false) { 
						// kiranaAlert("notOK", "File not found", "warning", "no");
						// return false;
					// }
					// open = true;

       				$("#show_file").html(showPdf('assets/'+data.link+'?download=true&prints=true'));
				},
				complete: function () {
					if(open == true)
					$('#view_modal').modal('show');	
				}
			});
    	}

		if (tipe == 'Folder' || tipe == 'nav') {
			
	    	$.ajax({
	    		url: baseURL+'folder/manage/get_data/folder-file',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id_folder,
					folder 	  : "dokumen vendor",
					isAdmin   : sessionStorage.getItem("folder_admin")
				},
				success: function(data){
					$('.datatable-folder').DataTable().destroy();
		            var t   = $('.datatable-folder').DataTable({
	            					order: [[5, 'asc']],
		                            ordering : true,
		                            scrollCollapse: true,
		                            scrollY: false,
		                            scrollX : true,
		                            bautoWidth: false,
		                            "iDisplayLength": 50,
		                            "paging": true,
		                            columnDefs: [
		                                { "className": "text-right", "targets": 2 },
		                                { "className": "text-right", "targets": 1 },
		                                { "className": "text-right", "targets": 3 },
		                                { "className": "text-right", "targets": 4 },
		                                { "visible": false, "targets": 5 },
		                            ],
		                        });
		            t.clear().draw();

					$.each(data, function (i, v) {
						if(i == "grandparent" && v.id_folder != "552"){
							var firstrow = t.row.add( [
								                "<i class='fa fa-folder-open icons_orange'></i> ...",
								                "",
								                "",
								                "",
								                "",
								                ""
								            ] ).draw( false ).node();

				            $(firstrow).attr("data-id", v.parent_folder);
				            $(firstrow).attr("data-title", v.nama_grandparent);
				            $(firstrow).attr("data-parent", v.grandparent);
				            $(firstrow).attr("data-jenis", "nav");
				            $(firstrow).attr("data-admin", v.is_admin);
				            $(firstrow).attr("data-toolbar", v.akses_write); //parentnya parent dept akses write
				            $(firstrow).attr("data-level", v.level); //parentnya parent level akses write
						}

						if(i !== "grandparent"){
							$.each(v, function (id, val) {
								let tgl_edit = "";
								let icon 	   = "<i class='fa fa-folder icons_orange'></i> "+" "+val.nama;
								let tipe 	   = "Folder";
								let id_row	   = val.id_folder;
								let ukuran	   = "";
								let parent     = val.parent_folder;
								let divAkses   = val.akses_divisi_write;
								let deptAkses  = val.akses_department_write;
								let deptRead   = val.akses_department_read;
								let adminAkses = val.isAdmin;
								let levelwrite = val.akses_level_write;
								let levelread  = val.akses_level_read;
								let title_atas = val.nama;
								let sortindex  = 0;
								if(i == "file"){
									tgl_edit = generateDateFormats(val.tanggal_edit);

									tipe	= val.tipe;
									var fileClass = "fa-file-text icons_orange";
			                        switch (tipe) {
			                            case 'pdf' : fileClass = "fa-file-pdf-o icons_red";
			                                break;
			                            case 'doc' :
			                            case 'docx': fileClass = "fa-file-word-o icons_blue";
			                                break;
										case 'mp4' :
										case 'webm': fileClass = "fa-file-video-o icons_blue";
											break;
			                            case 'xls' :
			                            case 'xlsx': fileClass = "fa-file-excel-o icons_green";
			                                break;
			                            case 'png' :
			                            case 'jpg' :
			                            case 'gif' :
			                            case 'jpeg': fileClass = "fa-file-img-o icons_yellow";
			                                break;
			                        }

									ukuran 	= FileSize(val.ukuran);
									icon 	= "<i class='fa "+fileClass+"'></i>"+" "+val.nama;
									id_row	= val.id_file;
									parent  = val.id_folder;
									sortindex = 1;
								}

								rows = t.row.add( [
										                icon,
										                generateDateFormats(val.tanggal_buat),
										                tgl_edit,
										                tipe,
										                ukuran,
										                sortindex
										            ] ).draw( false ).node();

								$(rows).attr("data-id", id_row);
								$(rows).attr("data-title", title_atas);
								$(rows).attr("data-jenis", tipe);
								$(rows).attr("data-parent", parent);
								$(rows).attr("data-div", divAkses);
								$(rows).attr("data-dept", deptAkses);
								$(rows).attr("data-read", deptRead);
								$(rows).attr("data-admin", adminAkses);
								$(rows).attr("data-lwrite", levelwrite);
								$(rows).attr("data-lread", levelread);

							});
						}
					});
				}
			});

    	}

    });

	// ===============================================END===================================================



	// ================================GENERATE RIGHT CLICK ACTION LIST=====================================
	/*
	RIGHT CLICK MENU BASE ON ROLE AKSES

	ADMIN -- New Folder
			 Open File
			 Rename
			 Upload
			 Delete
			 Setting

	READ --  Open File
			 Open Folder / View Folder

	WRITE -- Open File
			 Open Folder
			 New Folder
			 Rename
			 Upload
	*/

    // CUSTOM RIGHT CLICK
	var menu = document.querySelector('.menu');
	var bool = false;
	var id ; var tipe;


	$(document).on('contextmenu', "body", function (e) {
		if($(e.target).closest("#tbod").length == 0){
			hideMenu();
		}
	});

	$(document).on("contextmenu", "#tbod tr", function(e){

  		var id_folder	 			= $(this).data("id");
  		tipe	        			= $(this).data("jenis");
  		parent	         			= $(this).data("parent");
  		divAkses         			= $(this).data("div");
  		deptAkses        			= $(this).data("dept");
  		var parent_akses_write      = $(this).data("read");
  		adminAkses       			= $(this).data("admin");
  		level_write       			= $(this).data("lwrite");
  		level_read       			= $(this).data("lread");
  		id 				 			= id_folder;
  		bool 			 			= true;
  		// console.log(id);
  		// alert(tipe);
  		
  		if (parent == '0' && adminAkses == 'yes') { // admin in root

	  		if (tipe == 'nav') {
	  			hideMenu();
	  		}else{
		  		// $("#new_folder").show();
		  		$("#new_folder").hide();
		  		$("#upload").hide();
		  		// $("#upload").show();
		  		$("#setting").show(); 
		  		$("#rename").hide();
		  		$("#print").hide(); 
		  		$("#delete").hide(); 
		  		$("#openFile").hide(); 
	  			
	  		} 

  		}
  		else if (parent == '0' && adminAkses != 'yes' && divAkses != 'yes' && deptAkses != 'yes' && level_write != 'yes' ){// not admin not write in root
	  		hideMenu();
  		}
  		else if (parent == '0' && adminAkses != 'yes' && divAkses == 'yes' && deptAkses == 'yes' && level_write == 'yes') { // not admin but write in root
  			// $("#rename").hide(); 
  			// // $("#new_folder").show();
  			// $("#new_folder").hide();
	  		// $("#upload").hide();
	  		// // $("#upload").show();
	  		// $("#delete").hide(); 
	  		// $("#setting").hide();
  			// $("#openFile").hide();
	  		hideMenu();
	  		// button new folder dan upload ada di toolbar, yang disini di hide

  		}
  		else if (parent != '0' && adminAkses == 'yes'){// admin not in root
	  		if (tipe == 'Folder') {
		  		$("#rename").show(); 
		  		$("#new_folder").hide();
	  			$("#upload").hide();
		  		$("#print").hide();
		  		$("#delete").show(); 
		  		$("#setting").show();
	  			$("#openFile").hide(); 

	  		}else if (tipe == 'nav'){
	  			hideMenu();
	  		}else{ // action list untuk file
	  			$("#openFile").show();
		  		$("#rename").show(); 
		  		$("#print").show(); 
		  		$("#delete").show();
		  		$("#setting").show();
		  		$("#upload").hide();
	  			$("#new_folder").hide();
	  		}
  		}
  		else if(parent != '0' && adminAkses != 'yes' && divAkses == 'yes' && deptAkses == 'yes' && level_write == 'yes'){// not admin, not in root
  			if (tipe == 'Folder') {
		  		$("#rename").show(); 
		  		$("#new_folder").hide();
	  			$("#upload").hide();
		  		$("#print").hide();
		  		$("#delete").show(); 
		  		$("#setting").hide();
	  			$("#openFile").hide();
	  		}else if (tipe == 'nav'){
	  			hideMenu();	 
	  		}else{
	  			$("#openFile").show();
		  		$("#rename").show(); 
		  		$("#print").show(); 
		  		$("#delete").show();
		  		$("#setting").hide();
		  		$("#upload").hide();
	  			$("#new_folder").hide(); 
	  		}
  		}
  		//MUST BE FIXED AND TEST
  		// else if (parent != '0' && adminAkses != 'yes' && divAkses == 'no' && deptAkses == 'no' && parent_akses_write == 'yes' && tipe == 'pdf') { 
  		// else if ((parent != '0') && (adminAkses != 'yes') && (divAkses == 'no') && (deptAkses == 'no') && (parent_akses_write == 'yes') && (tipe != 'Folder' || tipe != 'nav')) { 
  		// 	// not admin but can delete file
  		// 	// inherit akses parent
  		// 	$("#openFile").show();
	  	// 	$("#delete").show();
	  	// 	$("#rename").show(); 
	  	// 	$("#setting").hide();
	  	// 	$("#upload").hide();
  		// 	$("#new_folder").hide();	
  		// }
  		else{
	  		if (tipe !== 'nav' && tipe !== 'Folder') {
	  			if (tipe == 'pdf' || tipe == 'PDF') {
	  				$("#openFile").show(); 
	  			}else{
	  				$("#openFile").hide(); 
	  			}
		  		$("#rename").hide(); 
		  		$("#new_folder").hide();
	  			$("#upload").hide();
		  		$("#print").hide();
		  		$("#delete").hide(); 
		  		$("#setting").hide();
	  		}else{
  				hideMenu();
	  		}
  		}

	});
	

	function showMenu(x, y){

		$("#menu").show();
	    menu.style.left = x + 'px';
	    menu.style.top = y + 'px';
	    if (bool != false) {
	    	menu.classList.add('menu-show');
	    }else{$("#menu").hide();}
	}

	function hideMenu(){
		$("#menu").hide();
	    menu.classList.remove('menu-show');
	    bool = false;
	}

	function onContextMenu(e){
	    e.preventDefault();
	    showMenu(e.pageX, e.pageY);
	}

	document.addEventListener('contextmenu', onContextMenu, false);

	$(document).on("click", "body", function(){
		hideMenu();
	});


	// CUSTOM RIGHT CLICK END

	$("#rename").on("click", function(e){
		$.ajax({
    		url: baseURL+'folder/manage/get_prev_name',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_folder : id,
				tipe      : tipe
			},
			success: function(data){
				// console.log(data);
				$("input[name='old_name']").val(data.nama);
			}
		});


		if (tipe != 'Folder') {
			$("#rename_title").html("RENAME FILE");
			$("#notes").removeClass("hidden");
		}else{
			$("#rename_title").html("RENAME FOLDER");
			$("#notes").addClass("hidden");
		} 
    	$('#rename_modal').modal('show');		
	});

	$("#save_rename").on("click", function(e){
        if($("input[name='rename']").val() == ""){
			kiranaAlert("notOK", "New name couldn't be empty", "warning", "no");
			e.preventDefault();
			return false;
        }else{
	    	var name = $("input[name='rename']").val();
	    	
	    	var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	$.ajax({
		    		url: baseURL+'folder/manage/rename',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id_folder : id,
						name      : name,
						tipe      : tipe,
						parent    : parent
					},
					success: function(data){
						// console.log(data);
						if(data.sts == 'OK'){
							kiranaAlert(data.sts, data.msg, "success", "no");
							$('#rename_modal').modal('hide');
							generate_table_after_action();
	    					$("input[name='isproses']").val(0);
							// Pace.restart();
						}else{
							kiranaAlert(data.sts, data.msg, "error", "no");
	    					$("input[name='isproses']").val(0);
						}
					}
				});	
			}else{
				kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
			}
			e.preventDefault();
			return false;

		}
		
    });

    $(document).on('hide.bs.modal','#rename_modal', function () {                 
    	 $(':input', this).val('');
	});

	$("#openFile").on("click", function(){
		if (tipe == 'pdf') {
			$.ajax({
	    		url: baseURL+'folder/manage/get_prev_name',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id,
					tipe      : tipe
				},
				success: function(data){
	       			$("#show_file").html(showPdf('assets/'+data.link+'?download=true&prints=true'));	
				}
			});
			$('#view_modal').modal('show');		
		} else if ($.inArray(tipe, ['mp4', 'webm']) >= 0) {
			$.ajax({
	    		url: baseURL+'folder/manage/get_prev_name',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id,
					tipe: tipe,
					log: 'yes'
				},
				success: function (data) {
					if (data.exists == false) { 
						kiranaAlert("notOK", "File not found", "warning", "no");
						return false;
					}
					open = true;

					var output = '';
					output += '<div class="nav-tabs-custom">';
					output += '	<ul class="nav nav-tabs">';
					output += '		<li class="active"><a href="#video-tab" data-toggle="tab">Video</a></li>';
					output += '		<li><a href="#detail-tab" data-toggle="tab">Detail View</a></li>';
					output += '	</ul>';
					output += '</div>';
					output += '<div class="tab-content">';
					output += '	<div class="tab-pane active"id="video-tab">';
					output += '		<video class="video-js" controls controlsList="nodownload" style="width: 100%; height: 400px" preload="metadata">';
					output += '			<source src="'+baseURL+'assets/'+data.link+'" type="video/'+tipe+'"></source>';
					output += '		</video>';
					output += '		<code>Total Views: '+(data.log.length > 0 ? data.log[0].total_count : '0')+'</code>';
					output += '	</div>';
					output += '	<div class="tab-pane"id="detail-tab">';
					output += '		<table class="table table-bordered table-striped table-responsive table-hover">';
					output += '			<thead>';
					output += '				<th>NIK</th>';
					output += '				<th>Nama</th>';
					output += '				<th>Views</th>';
					output += '			</thead>';
					output += '			<tbody>';
					if (data.log.length > 0) {
						$.each(data.log, function (i, v) {
							output += '			<tr>';
							output += '				<td class="text-center">' + v.nik + '</td>';
							output += '				<td class="text-center">' + v.nama + '</td>';
							output += '				<td class="text-center">' + v.total_view_user + '</td>';
							output += '			</tr>';
						});
					}
					output += '			</tbody>';
					output += '		</table>';
					output += '	</div>';
					output += '</div>';
       				$("#show_file").html(output);
				},
				complete: function () { 
					$("#view_modal .table").DataTable();
					if(open == true)
						$('#view_modal').modal('show');	
				}
			});
		}else{
			$.ajax({
	    		url: baseURL+'folder/manage/get_prev_name',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id,
					tipe      : tipe
				},
				success: function(data){
					// console.log(data);
					var form = document.createElement("form");
			    	var element1 = document.createElement("input"); 

			    	form.method = "POST";
			    	form.action = baseURL+'folder/manage/download';

			    	element1.value='assets/'+data.link;
				    element1.name="link";
				    element1.type="hidden";

				    form.appendChild(element1); 

				    document.body.appendChild(form);

			    	form.submit();					
				}
			});
		}
	});

	$("#icon_new_folder").on("click", function(){
		
		if (sessionStorage.getItem("id_new_folder") != null) {
			var ids = sessionStorage.getItem("id_new_folder");
		}else{
			var ids = 552;
		}
		//xxx
		// if(ids==306){
			// $("input[name='folder_names']").val('aaa');
		// }else{
			// $("input[name='folder_names']").val('bbb'); 		
		// }
		$("input[name='ids']").val(ids);
		$('#toolbar_new_folder_modal').modal('show');
	});

	$("#save_toolbar_new_folder").on("click", function(){
    	var name 	= $("input[name='folder_names']").val();
    	var ids 	= $("input[name='ids']").val();
        if (name == "") {
			kiranaAlert("notOK", "New folder name couldn't be empty", "warning", "no");
        	e.preventDefault();
			return false;
		}else if (name.includes("\\") == true){
			kiranaAlert("notOK", "Folder name can't contain backslash", "warning", "no");
        	e.preventDefault();
			return false;
        }else{	
	    	
	    	var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	$.ajax({
		    		url: baseURL+'folder/manage/new_folder',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id_folder : ids,
						name      : name
					},
					success: function(data){
						// console.log(data);
						if(data.sts == 'OK'){
							kiranaAlert(data.sts, data.msg, "success", "no");
							$('#toolbar_new_folder_modal').modal('hide');
							generate_table_after_action();
    						$("input[name='isproses']").val(0);
						}else{
							kiranaAlert(data.sts, data.msg, "error", "no");
    						$("input[name='isproses']").val(0);
						}
					}
				});
		    	
			}else{
				kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
				e.preventDefault();
				return false;
			}
		}
    });

    $(document).on('hide.bs.modal','#toolbar_new_folder_modal', function () {                 
    	 $(':input', this).val('');
	});

	$("#new_folder").on("click", function(){
		$('#new_folder_modal').modal('show');		
	});

	$("#save_new_folder").on("click", function(){
	    var name = $("input[name='folder_name']").val();
        if (name == "") {
			kiranaAlert("notOK", "New folder name couldn't be empty", "warning", "no");
        	e.preventDefault();
			return false;
		}else if(name.includes("\\") == true){
			kiranaAlert("notOK", "Folder name can't contain backslash", "warning", "no");
        	e.preventDefault();
			return false;
        }else{

        	var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	$.ajax({
		    		url: baseURL+'folder/manage/new_folder',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id_folder : id,
						name      : name
					},
					success: function(data){
						// console.log(data);
						if(data.sts == 'OK'){
							kiranaAlert(data.sts, data.msg, "success", "no");
							$('#new_folder_modal').modal('hide');
							generate_table_after_action();
    						$("input[name='isproses']").val(0);
						}else{
							kiranaAlert(data.sts, data.msg, "error", "no");
    						$("input[name='isproses']").val(0);
						}
					}
				});
		    	
			}else{
				kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
			}
			e.preventDefault();
			return false;	
		}
    });

    $(document).on('hide.bs.modal','#new_folder_modal', function () {                 
    	 $(':input', this).val('');
	});


	$("#delete").on("click", function(){
		if (tipe != 'Folder') {
			$("#delete_title").html("DELETE FILE CONFIRMATION");
			$("#del_text").html("Delete this File?");
			// $("#del_subtext").html("");
    	$('#delete_modal').modal('show');		
		}else{
			$("#delete_title").html("DELETE FOLDER CONFIRMATION");
			$("#del_text").html("Delete this Folder?");
			$.ajax({
	    		url: baseURL+'folder/manage/cek_delete',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id
				},
				success: function(data){
					if(data.sts == 'OK'){
    					$('#delete_modal').modal('show');		
					}else{
						kiranaAlert(data.sts, data.msg, "error", "no");
					}
				}
			});
		}
    		
	});

	$("#submit_delete").on("click", function(e){
    	
		var isproses 	= $("input[name='isproses']").val();
		if(isproses == 0){
    		$("input[name='isproses']").val(1);
	    	$.ajax({
	    		url: baseURL+'folder/manage/delete',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id,
					tipe      : tipe
				},
				success: function(data){
					// console.log(data);
					if(data.sts == 'OK'){
						kiranaAlert(data.sts, data.msg, "success", "no");
						$('#delete_modal').modal('hide');
						generate_table_after_action();
    					$("input[name='isproses']").val(0);
					}else{
						kiranaAlert(data.sts, data.msg);
    					$("input[name='isproses']").val(0);
					}
				}
			});
	    	
		}else{
			kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
		}
		e.preventDefault();
		return false;

    });

	$("#icon_upload").on("click", function(){
		if(sessionStorage.getItem("id_new_folder") != null){
			var upload_id = sessionStorage.getItem("id_new_folder");
		}else{
			var upload_id = '552';
		}

		$("input[name='id_folder']").val(upload_id);
    	$('#upload_modal').modal('show');		
	});

	$("#upload").on("click", function(){
		$("input[name='id_folder']").val(id);
    	$('#upload_modal').modal('show');		
	});

	$('#fileUpload').on("change", function(e){
		var id_folder 	= $("input[name='id_folder']").val();
		//Remove extension
       var output = "<label for='file_list'>List File</label><ul>";
       var name   = [];
        $.each($(this).get(0).files, function(i,v){
            output  += "<li id='file"+i+"'>"+v.name;
            // output  += "&nbsp;&nbsp;<a style='cursor:pointer' class='removeFile' data-idx='"+i+"' data-uri='file"+i+"'>remove</a>";
            output  += "</li>";
            var withoutExtension = v.name.substr(0, v.name.lastIndexOf("."));
            name.push(withoutExtension);
        });
		
        // console.log(name);
        output     += "</ul>";
        $("#file_list").html(output);
		$("input[name='name']").val(name+'-'+id_folder);

        if($(this).get(0).files.length == 0) $("#file_list").html("");
    });

	$(document).on("click", "button[name='submit_upload']", function(e){
		var empty_form = validate("#form-upload");
        if(empty_form == 0){

        	var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1);
				var formData = new FormData($("#form-upload")[0]);
		    	$.ajax({
		    		url: baseURL+'folder/manage/upload_new_file',
					type: 'POST',
					dataType: 'JSON',
					data:  formData,
	                contentType: false,
	                cache: false,
	                processData: false,
					beforeSend: function () {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("button[name='submit_upload']").addClass("overlay-wrapper").append(overlay);
					},
					success: function(data){
						// console.log(data);
						if(data.sts == 'OK'){
							kiranaAlert(data.sts, data.msg, "success", "no");
							$('#upload_modal').modal('hide');
							generate_table_after_action();
	    					$("input[name='isproses']").val(0);
						}else{
							kiranaAlert(data.sts, data.msg, "error", "no");
	    					$("input[name='isproses']").val(0);
						}
					},
					complete: function () {
						$("button[name='submit_upload']").removeClass("overlay-wrapper");
						$('.overlay').remove();
					}
				});
		    	
			}else{
				kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
			}
			e.preventDefault();
			return false;

	    }
	    e.preventDefault();
		return false;
    });

    $(document).on('hide.bs.modal','#upload_modal', function () {                 
    	$(this).find('#form-upload')[0].reset();
    	$('#file_list').html('');
	});

	// data edit akses setting 
	$("#setting").on("click", function(){
		$.ajax({
    		url: baseURL+'folder/manage/get_data_akses_folder',
			type: 'POST',
			dataType: 'JSON',
			data:{
				id_folder : id,
				tipe 	  : tipe
			},
			success: function(data){

				$("#divisi_write").val(data.divisi_write).trigger("change");
				setTimeout(function(){
					$("#department_write").val(data.department_write).trigger("change");
        			sessionStorage.setItem("temp_dept_write", $("#department_write").val()); // tampung departemen terpilih
					$("#level_write").val(data.level_write).trigger("change");
				}, 1000);

				$("#divisi_read").val(data.divisi_read).trigger("change");
	            setTimeout(function(){
					$("#department_read").val(data.department_read).trigger("change");
        			sessionStorage.setItem("temp_dept_read", data.department_read); // tampung departemen terpilih
					$("#level_akses").val(data.level_akses).trigger("change");
				}, 1000);
			}    
		});	
    	$('#setting_modal').modal('show');		
	});

	// SELECT2 WRITE ACCESS
	$(document).on("change", "#divisi_write", function(){
		var value = $("#divisi_write").val();
	    
	    if (value != "") {
	    	$("#level_write").attr('Required', true);
	    	$.ajax({
	    		url: baseURL+'folder/manage/get_departement',
				type: 'POST',
				dataType: 'JSON',
				data:{
					id_divisi : value
				},
				success: function(data){
					var output = "";
		            $.each(data.data, function(i,v){
		                output  += "<option value='"+v.id_departemen+"'>"+v.nama+" [ "+v.id_departemen+" | "+v.gsber+" ]</option>";
		            });

		            if (output != "") {
		            	$("#department_write").attr('Required', true);
		            }else{
		            	$("#department_write").attr('Required', false);
		            }

		            $('#department_write').html(output)
	            	
					

				},
				complete: function(){
					var temp = sessionStorage.getItem("temp_dept_write");
					var hasil = JSON.parse("[" + temp + "]");
					$("#department_write").val(hasil).trigger("change");
				}      
			});	
	    }else{
	    	$("#level_write").attr('Required', false);
	    	$("#department_write").attr('Required', false);
	    	$('#department_write').empty().trigger('change');
	    	$('#level_write').val('').trigger('change');
	    }
	
	});

	$("#divisi_write").on("select2:close", function(){
        sessionStorage.setItem("temp_dept_write", $("#department_write").val()); // tampung departemen terpilih
	});
	$("#department_write").on("select2:close", function(){
        sessionStorage.setItem("temp_dept_write", $("#department_write").val()); // tampung departemen terpilih
	});

	$(document).on("change", ".isSelectAll_divisionWrite", function(e){
	    if($(".isSelectAll_divisionWrite").is(':checked')) {
	    	$('#divisi_write').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#divisi_write').trigger('change');
	    }else{
	    	$('#divisi_write').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#divisi_write').trigger('change');
	    }
	});

	$(document).on("change", ".isSelectAll_departmentWrite", function(e){
	    if($(".isSelectAll_departmentWrite").is(':checked')) {
	    	$('#department_write').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#department_write').trigger('change');
        	sessionStorage.setItem("temp_dept_write", $("#department_write").val()); // tampung departemen terpilih

	    }else{
	    	$('#department_write').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#department_write').trigger('change');
        	sessionStorage.setItem("temp_dept_write", $("#department_write").val()); // tampung departemen terpilih
	    }
	});

	$(document).on("change", ".isSelectAll_levelwrite", function(e){
	    if($(".isSelectAll_levelwrite").is(':checked')) {
	    	$('#level_write').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#level_write').trigger('change');
	    }else{
	    	$('#level_write').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#level_write').trigger('change');
	    }
	});

	// SELECT2 WRITE ACCESS ENDS

	// SELECT2 READ ACCESS
	$(document).on("change", "#divisi_read", function(){
		var value = $("#divisi_read").val();
	    

	    if (value != "") {
	    	$("#level_akses").attr('Required', true);
	    	
	    	$.ajax({
	    		url: baseURL+'folder/manage/get_departement',
				type: 'POST',
				dataType: 'JSON',
				data:{
					id_divisi : value
				},
				success: function(data){
					var output = "";
		            $.each(data.data, function(i,v){
		                output  += "<option value='"+v.id_departemen+"'>"+v.nama+" [ "+v.id_departemen+" | "+v.gsber+" ]</option>";
		            });

		            if (output != "") {
		            	$("#department_read").attr('Required', true);
		            }else{
		            	$("#department_read").attr('Required', false);
		            }

		            $('#department_read').html(output)

				},
				complete: function(){
					var temps = sessionStorage.getItem("temp_dept_read");
					var hasils = JSON.parse("[" + temps + "]");
					$("#department_read").val(hasils).trigger("change");
				}      
			});	

	    }else{
	    	$("#level_akses").attr('Required', false);
	    	$("#department_read").attr('Required', false);
	    	$('#department_read').empty().trigger('change');
	    	$('#level_akses').val('').trigger('change');

	    }	
	});

	$("#divisi_read").on("select2:close", function(){
        sessionStorage.setItem("temp_dept_read", $("#department_read").val()); // tampung departemen terpilih
	});
	$("#department_read").on("select2:close", function(){
        sessionStorage.setItem("temp_dept_read", $("#department_read").val()); // tampung departemen terpilih
	});

	$(document).on("change", ".isSelectAll_divisionRead", function(e){
	    if($(".isSelectAll_divisionRead").is(':checked')) {
	    	$('#divisi_read').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#divisi_read').trigger('change');
	    }else{
	    	$('#divisi_read').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#divisi_read').trigger('change');
	    }
	});

	$(document).on("change", ".isSelectAll_departmentRead", function(e){
	    if($(".isSelectAll_departmentRead").is(':checked')) {
	    	$('#department_read').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#department_read').trigger('change');
        	sessionStorage.setItem("temp_dept_read", $("#department_read").val()); // tampung departemen terpilih
	    }else{
	    	$('#department_read').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#department_read').trigger('change');
        	sessionStorage.setItem("temp_dept_read", $("#department_read").val()); // tampung departemen terpilih
	    }
	});

	$(document).on("change", ".isSelectAll_levelakses", function(e){
	    if($(".isSelectAll_levelakses").is(':checked')) {
	    	$('#level_akses').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#level_akses').trigger('change');
	    }else{
	    	$('#level_akses').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#level_akses').trigger('change');
	    }
	});


	// SELECT2 READ ACCESS ENDS

	$(document).on("click", "button[name='submit_setting']", function(e){
		var empty_form = validate("#form-set");
        if(empty_form == 0){

	    	var division_write 	 = $("#divisi_write").val();
	    	var department_write = $("#department_write").val();
	    	var division_read 	 = $("#divisi_read").val();
	    	var department_read	 = $("#department_read").val();
	    	var level_write		 = $("#level_write").val();
	    	var level_akses	 	 = $("#level_akses").val();

	    	var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1);
				$.ajax({
		    		url: baseURL+'folder/manage/setting_akses',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id_folder 		   : id,
						tipe 			   : tipe,
						division_write     : division_write,
						department_write   : department_write,
						division_read      : division_read,
						department_read    : department_read,
						level_write	 	   : level_write,
						level_akses 	   : level_akses
					},
					success: function(data){
						// console.log(data);
						if(data.sts == 'OK'){
							kiranaAlert(data.sts, data.msg);
							kiranaAlert(data.sts, data.msg, "success", "no");
							$('#setting_modal').modal('hide');
							generate_table_after_action();
    						$("input[name='isproses']").val(0);
						}else{
							kiranaAlert(data.sts, data.msg, "error", "no");
    						$("input[name='isproses']").val(0);
						}
					}
				});
		    	
			}else{
				kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
			}
			e.preventDefault();
			return false;

		}
		e.preventDefault();
		return false;
    	
    });

    //========================SETTING AKSES KHUSUS ROOT VENDOR====================================
    $("#set_aksesvendor").on("click", function(){
		$.ajax({
    		url: baseURL+'folder/manage/get_data_akses_folder',
			type: 'POST',
			dataType: 'JSON',
			data:{
				id_folder : 552,
				tipe 	  : 'Folder'
			},
			success: function(data){
				
				$("#divisi_write2").val(data.divisi_write).trigger("change");
				setTimeout(function(){
					$("#department_write2").val(data.department_write).trigger("change");
        			sessionStorage.setItem("temp_dept_write2", $("#department_write2").val());
					$("#level_write2").val(data.level_write).trigger("change");
				}, 1000);

				$("#divisi_read2").val(data.divisi_read).trigger("change");
	            setTimeout(function(){
					$("#department_read2").val(data.department_read).trigger("change");
        			sessionStorage.setItem("temp_dept_read2", data.department_read); 
					$("#level_akses2").val(data.level_akses).trigger("change");
				}, 1000);
			}    
		});	
    	$('#setting_modal2').modal('show');		
	});

    $(document).on("change", "#divisi_write2", function(){
		var value = $("#divisi_write2").val();
	    
	    if (value != "") {
	    	$("#level_write2").attr('Required', true);
	    	$.ajax({
	    		url: baseURL+'folder/manage/get_departement',
				type: 'POST',
				dataType: 'JSON',
				data:{
					id_divisi : value
				},
				success: function(data){
					var output = "";
		            $.each(data.data, function(i,v){
		                output  += "<option value='"+v.id_departemen+"'>"+v.nama+" [ "+v.id_departemen+" | "+v.gsber+" ]</option>";
		            });

		            if (output != "") {
		            	$("#department_write2").attr('Required', true);
		            }else{
		            	$("#department_write2").attr('Required', false);
		            }

		            $('#department_write2').html(output)
	            	
					

				},
				complete: function(){
					var temp = sessionStorage.getItem("temp_dept_write2");
					var hasil = JSON.parse("[" + temp + "]");
					$("#department_write2").val(hasil).trigger("change");
				}      
			});	
	    }else{
	    	$("#level_write2").attr('Required', false);
	    	$("#department_write2").attr('Required', false);
	    	$('#department_write2').empty().trigger('change');
	    	$('#level_write2').val('').trigger('change');
	    }
	
	});

	$("#divisi_write2").on("select2:close", function(){
        sessionStorage.setItem("temp_dept_write2", $("#department_write2").val()); // tampung departemen terpilih
	});
	$("#department_write2").on("select2:close", function(){
        sessionStorage.setItem("temp_dept_write2", $("#department_write2").val()); // tampung departemen terpilih
	});

	$(document).on("change", ".isSelectAll_divisionWrite2", function(e){
	    if($(".isSelectAll_divisionWrite2").is(':checked')) {
	    	$('#divisi_write2').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#divisi_write2').trigger('change');
	    }else{
	    	$('#divisi_write2').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#divisi_write2').trigger('change');
	    }
	});

	$(document).on("change", ".isSelectAll_departmentWrite2", function(e){
	    if($(".isSelectAll_departmentWrite2").is(':checked')) {
	    	$('#department_write2').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#department_write2').trigger('change');
        	sessionStorage.setItem("temp_dept_write2", $("#department_write2").val()); 
	    }else{
	    	$('#department_write2').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#department_write2').trigger('change');
        	sessionStorage.setItem("temp_dept_write2", $("#department_write2").val()); 
	    }
	});

	$(document).on("change", ".isSelectAll_levelwrite2", function(e){
	    if($(".isSelectAll_levelwrite2").is(':checked')) {
	    	$('#level_write2').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#level_write2').trigger('change');
	    }else{
	    	$('#level_write2').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#level_write2').trigger('change');
	    }
	});

	// SELECT2 WRITE ACCESS ENDS

	// SELECT2 READ ACCESS
	$(document).on("change", "#divisi_read2", function(){
		var value = $("#divisi_read2").val();

	    if (value != "") {
	    	$("#level_akses2").attr('Required', true);
	    	
	    	$.ajax({
	    		url: baseURL+'folder/manage/get_departement',
				type: 'POST',
				dataType: 'JSON',
				data:{
					id_divisi : value
				},
				success: function(data){
					var output = "";
		            $.each(data.data, function(i,v){
		                output  += "<option value='"+v.id_departemen+"'>"+v.nama+" [ "+v.id_departemen+" | "+v.gsber+" ]</option>";
		            });

		            if (output != "") {
		            	$("#department_read2").attr('Required', true);
		            }else{
		            	$("#department_read2").attr('Required', false);
		            }

		            $('#department_read2').html(output)

				},
				complete: function(){
					var temps = sessionStorage.getItem("temp_dept_read2");
					var hasils = JSON.parse("[" + temps + "]");
					$("#department_read2").val(hasils).trigger("change");
				}      
			});	

	    }else{
	    	$("#level_akses2").attr('Required', false);
	    	$("#department_read2").attr('Required', false);
	    	$('#department_read2').empty().trigger('change');
	    	$('#level_akses2').val('').trigger('change');

	    }
	});

	$("#divisi_read2").on("select2:close", function(){
        sessionStorage.setItem("temp_dept_read2", $("#department_read2").val()); 
	});
	$("#department_read2").on("select2:close", function(){
        sessionStorage.setItem("temp_dept_read2", $("#department_read2").val()); 
	});

	$(document).on("change", ".isSelectAll_divisionRead2", function(e){
	    if($(".isSelectAll_divisionRead2").is(':checked')) {
	    	$('#divisi_read2').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#divisi_read2').trigger('change');
	    }else{
	    	$('#divisi_read2').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#divisi_read2').trigger('change');
	    }
	});

	$(document).on("change", ".isSelectAll_departmentRead2", function(e){
	    if($(".isSelectAll_departmentRead2").is(':checked')) {
	    	$('#department_read2').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#department_read2').trigger('change');
        	sessionStorage.setItem("temp_dept_read2", $("#department_read2").val()); 
	    }else{
	    	$('#department_read2').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#department_read2').trigger('change');
        	sessionStorage.setItem("temp_dept_read2", $("#department_read2").val()); 
	    }
	});

	$(document).on("change", ".isSelectAll_levelakses2", function(e){
	    if($(".isSelectAll_levelakses2").is(':checked')) {
	    	$('#level_akses2').select2('destroy').find('option').prop('selected', 'selected').end().select2();
	    	$('#level_akses2').trigger('change');
	    }else{
	    	$('#level_akses2').select2('destroy').find('option').prop('selected', false).end().select2();
	    	$('#level_akses2').trigger('change');
	    }
	});


	// SELECT2 READ ACCESS ENDS

	$(document).on("click", "button[name='submit_setting2']", function(e){
		var empty_form = validate("#form-set2");
        if(empty_form == 0){

	    	var division_write 	 = $("#divisi_write2").val();
	    	var department_write = $("#department_write2").val();
	    	var division_read 	 = $("#divisi_read2").val();
	    	var department_read	 = $("#department_read2").val();
	    	var level_write		 = $("#level_write2").val();
	    	var level_akses	 	 = $("#level_akses2").val();

	    	var isproses 	= $("input[name='isproses']").val();
			if(isproses == 0){
	    		$("input[name='isproses']").val(1);
				$.ajax({
		    		url: baseURL+'folder/manage/setting_akses',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id_folder 		   : 552,
						tipe 			   : 'Folder',
						division_write     : division_write,
						department_write   : department_write,
						division_read      : division_read,
						department_read    : department_read,
						level_write	 	   : level_write,
						level_akses 	   : level_akses
					},
					success: function(data){
						// console.log(data);
						if(data.sts == 'OK'){
							kiranaAlert(data.sts, data.msg);
							kiranaAlert(data.sts, data.msg, "success", "no");
							$('#setting_modal2').modal('hide');
							generate_table_after_action();
    						$("input[name='isproses']").val(0);
						}else{
							kiranaAlert(data.sts, data.msg, "error", "no");
    						$("input[name='isproses']").val(0);
						}
					}
				});
		    	
			}else{
				kiranaAlert("notOK", "Please wait until the current process is finished", "warning", "no");
			}
			e.preventDefault();
			return false;

		}
		e.preventDefault();
		return false;
    	
    });


    //=========================================================================================

	$("#information").on("click", function(){
		swal({
			// position: 'bottom-start',
		 	title: '<strong>APPS GUIDES</strong>',
  			html: '<div class="pull-left" style="text-align:left;font-size: 1.225em;">'+ 
  				  '<span><img src="'+baseURL+'/assets/apps/img/mouse-left.png" style="padding-left: 2px; width:18px; height: 16px;"> Double-Left-Click for Enter Folder</span><br>' +
  				  '<span><img src="'+baseURL+'/assets/apps/img/mouse-right.png" style="padding-left: 2px; width:18px; height: 16px;"> Right-Click for Action List</span>' +
  				  '</div>',
		});		
	});

});


function generate_table_after_action(){
	
	if (sessionStorage.getItem("id_new_folder") != null) {
		default_id = sessionStorage.getItem("id_new_folder");
	}else{
		default_id = 552;
	}

	$.ajax({
		url: baseURL+'folder/manage/get_data/folder-file',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_folder : default_id,
			folder    : "dokumen vendor",
			isAdmin   : 552
		},
		success: function(data){
			// console.log(data);
			$('.datatable-folder').DataTable().destroy();
            var t   = $('.datatable-folder').DataTable({
        					order: [[5, 'asc']],
                            ordering : true,
                            scrollCollapse: true,
                            scrollY: false,
                            scrollX : true,
                            bautoWidth: false,
                            "iDisplayLength": 50,
                            "paging": true,
                            columnDefs: [
                                { "className": "text-right", "targets": 2 },
                                { "className": "text-right", "targets": 1 },
                                { "className": "text-right", "targets": 3 },
                                { "className": "text-right", "targets": 4 },
                                { "visible": false, "targets": 5 },
                            ],
                        });
            t.clear().draw();

			$.each(data, function (i, v) {
				// console.log(i);
				// console.log(v);
				if (i == "grandparent" && v !== null) {
					if(i == "grandparent" && v.id_folder != "552"){
						var firstrow = t.row.add( [
							                "<i class='fa fa-folder-open icons_orange'></i> ...",
							                "",
							                "",
							                "",
							                "",
							                ""
							            ] ).draw( false ).node();

			            $(firstrow).attr("data-id", v.parent_folder);
				        $(firstrow).attr("data-title", v.nama_grandparent);
				        $(firstrow).attr("data-parent", v.grandparent);
				        $(firstrow).attr("data-jenis", "nav");
			            $(firstrow).attr("data-admin", v.is_admin);
			            $(firstrow).attr("data-toolbar", v.akses_write); //parent dept akses write
			            $(firstrow).attr("data-level", v.level); //parent level akses write
					}
					
				}

				if(i !== "grandparent"){
					$.each(v, function (id, val) {
						let icon 	= "<i class='fa fa-folder icons_orange'></i> "+" "+val.nama;
						let tipe 	= "Folder";
						let id_row	= val.id_folder;
						let ukuran	= "";
						let parent  = val.parent_folder;
						let divAkses = val.akses_divisi_write;
						let deptAkses = val.akses_department_write;
						let deptRead = val.akses_department_read;
						let title_atas = val.nama;
						let adminAkses = val.isAdmin;
						let levelwrite = val.akses_level_write;
						let levelread  = val.akses_level_read;
						let sortindex = 0;
						if(i == "file"){
							tipe	= val.tipe;
							var fileClass = "fa-file-text icons_orange";
	                        switch (tipe) {
	                            case 'pdf' : fileClass = "fa-file-pdf-o icons_red";
	                                break;
	                            case 'doc' :
	                            case 'docx': fileClass = "fa-file-word-o icons_blue";
	                                break;
	                            case 'xls' :
	                            case 'xlsx': fileClass = "fa-file-excel-o icons_green";
	                                break;
								case 'mp4' :
								case 'webm': fileClass = "fa-file-video-o icons_blue";
									break;
	                            case 'png' :
	                            case 'jpg' :
	                            case 'gif' :
	                            case 'jpeg': fileClass = "fa-file-img-o icons_yellow";
	                                break;
	                        }

							ukuran 	= FileSize(val.ukuran);
							icon 	= "<i class='fa "+fileClass+"'></i>"+" "+val.nama;
							id_row	= val.id_file;
							parent  = val.id_folder;
							sortindex = 1;
						}

						rows = t.row.add( [
								                icon,
										        generateDateFormats(val.tanggal_buat),
								                generateDateFormats(val.tanggal_edit),
								                tipe,
								                ukuran,
								                sortindex
								            ] ).draw( false ).node();

						$(rows).attr("data-id", id_row);
						$(rows).attr("data-title", title_atas);
						$(rows).attr("data-jenis", tipe);
						$(rows).attr("data-parent", parent);
						$(rows).attr("data-div", divAkses);
						$(rows).attr("data-dept", deptAkses);
						$(rows).attr("data-read", deptRead);
						$(rows).attr("data-admin", adminAkses);
						$(rows).attr("data-lwrite", levelwrite);
						$(rows).attr("data-lread", levelread);

					});
				}
			});
		}
	});
}

function generate_table_by_param(parent_folder, id_root, parent_admin_akses, parent_div_write, parent_dept_write, parent_level_write){
	
	sessionStorage.setItem("id_new_folder", parent_folder);
	sessionStorage.setItem("folder_admin", id_root);
	sessionStorage.setItem("parent_write", parent_div_write);

	if (parent_admin_akses == 'yes') {
	 	$("#icon_new_folder").removeClass("hidden");
		$("#icon_upload").removeClass("hidden");
	}
	else if (parent_admin_akses == 'no' && parent_div_write == 'yes' && parent_dept_write == 'yes' && parent_level_write == 'yes' ){
		$("#icon_new_folder").removeClass("hidden");
		$("#icon_upload").removeClass("hidden");
	}
	else{
		$("#icon_new_folder").addClass("hidden");
		$("#icon_upload").addClass("hidden");
	}

	$.ajax({
		url: baseURL+'folder/manage/get_data/folder-file',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_folder : parent_folder,
			folder    : "dokumen vendor",
			isAdmin   : 552
		},
		success: function(data){
			// console.log(data);
			$('.datatable-folder').DataTable().destroy();
            var t   = $('.datatable-folder').DataTable({
        					order: [[5, 'asc']],
                            ordering : true,
                            scrollCollapse: true,
                            scrollY: false,
                            scrollX : true,
                            bautoWidth: false,
                            "iDisplayLength": 50,
                            "paging": true,
                            columnDefs: [
                                { "className": "text-right", "targets": 2 },
                                { "className": "text-right", "targets": 1 },
                                { "className": "text-right", "targets": 3 },
                                { "className": "text-right", "targets": 4 },
                                { "visible": false, "targets": 5 },
                            ],
                        });
            t.clear().draw();

			$.each(data, function (i, v) {
				// console.log(i);
				// console.log(v);
				if (i == "grandparent" && v !== null) {
					if(i == "grandparent" && v.id_folder != "552"){
						var firstrow = t.row.add( [
							                "<i class='fa fa-folder-open icons_orange'></i> ...",
							                "",
							                "",
							                "",
							                "",
							                ""
							            ] ).draw( false ).node();

			            $(firstrow).attr("data-id", v.parent_folder);
				        $(firstrow).attr("data-title", v.nama_grandparent);
				        $(firstrow).attr("data-parent", v.grandparent);
				        $(firstrow).attr("data-jenis", "nav");
			            $(firstrow).attr("data-admin", v.is_admin);
			            $(firstrow).attr("data-toolbar", v.akses_write); //parent dept akses write
			            $(firstrow).attr("data-level", v.level); //parent level akses write
					}
					
				}

				if(i !== "grandparent"){
					$.each(v, function (id, val) {
						let icon 	= "<i class='fa fa-folder icons_orange'></i> "+" "+val.nama;
						let tipe 	= "Folder";
						let id_row	= val.id_folder;
						let ukuran	= "";
						let parent  = val.parent_folder;
						let divAkses = val.akses_divisi_write;
						let deptAkses = val.akses_department_write;
						let deptRead = val.akses_department_read;
						let title_atas = val.nama;
						let adminAkses = val.isAdmin;
						let levelwrite = val.akses_level_write;
						let levelread  = val.akses_level_read;
						let sortindex = 0;
						if(i == "file"){
							tipe	= val.tipe;
							var fileClass = "fa-file-text icons_orange";
	                        switch (tipe) {
	                            case 'pdf' : fileClass = "fa-file-pdf-o icons_red";
	                                break;
	                            case 'doc' :
	                            case 'docx': fileClass = "fa-file-word-o icons_blue";
	                                break;
	                            case 'xls' :
	                            case 'xlsx': fileClass = "fa-file-excel-o icons_green";
	                                break;
								case 'mp4' :
								case 'webm': fileClass = "fa-file-video-o icons_blue";
									break;
	                            case 'png' :
	                            case 'jpg' :
	                            case 'gif' :
	                            case 'jpeg': fileClass = "fa-file-img-o icons_yellow";
	                                break;
	                        }

							ukuran 	= FileSize(val.ukuran);
							icon 	= "<i class='fa "+fileClass+"'></i>"+" "+val.nama;
							id_row	= val.id_file;
							parent  = val.id_folder;
							sortindex = 1;
						}

						rows = t.row.add( [
								                icon,
										        generateDateFormats(val.tanggal_buat),
								                generateDateFormats(val.tanggal_edit),
								                tipe,
								                ukuran,
								                sortindex
								            ] ).draw( false ).node();

						$(rows).attr("data-id", id_row);
						$(rows).attr("data-title", title_atas);
						$(rows).attr("data-jenis", tipe);
						$(rows).attr("data-parent", parent);
						$(rows).attr("data-div", divAkses);
						$(rows).attr("data-dept", deptAkses);
						$(rows).attr("data-read", deptRead);
						$(rows).attr("data-admin", adminAkses);
						$(rows).attr("data-lwrite", levelwrite);
						$(rows).attr("data-lread", levelread);

					});
				}
			});
		}
	});
}

function clearStorage(){
	sessionStorage.clear();
}

function generateDateFormats(tgl){
	// alert(tgl);
	if(tgl != null && tgl != ""){
		var date  	= new Date(tgl);
		var hours 	= date.getHours();
	    hours 		= hours < 10 ? '0'+hours : hours;
	    var minutes = date.getMinutes();
	    minutes 	= minutes < 10 ? '0'+minutes : minutes;
	    var tanggal	= date.getDate();
	    tanggal   	= tanggal < 10 ? '0'+tanggal : tanggal;
	    var month 	= (date.getMonth()*1)+(1*1);
	    month   	= month < 10 ? '0'+month : month;
	    // return tanggal + "-" + month + "-" + date.getFullYear() + "  " + hours + ":" + minutes;
	    return tanggal + "." + month + "." + date.getFullYear();
	}else{
		return "";
	}
}

function FileSize(size) {
    var i = size == 0 ? 0 : Math.floor( Math.log(size) / Math.log(1024) );
    return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'KB', 'MB', 'GB', 'TB'][i];
}

function myCallback(){
	alert();
}