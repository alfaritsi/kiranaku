/*
@application  : Folder Explorer
@author       : Matthew Jodi
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

$(document).ready(function() {
	// ======================ADMIN IT CREATE NEW ROOT FOLDER & SET ADMIN==================================================
	$("#rename_root_folder").on("click", function(){
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
	            $('#root_folder_name').html(output)
				$('#rename_root_folder_modal').modal('show');			
			}
		});
	});

	$("#save_rename_root_folder").on("click", function(){
	    var name 	= $("input[name='root_folder_rename']").val();
	    var id_root	= $("select[name='root_folder_name']").val();
	    if(id_root == 0){
	    	kiranaAlert("notOK", "Choose Root Folder", "warning", "no");
	    	e.preventDefault();
			return false;
	    }else if(name == ""){
	    	kiranaAlert("notOK", "New folder name couldn't be empty", "warning", "no");
	    	e.preventDefault();
			return false;
        }else if (name.includes("\\") == true){
        	kiranaAlert("notOK", "Folder name can't contain backslash", "warning", "no");
        	e.preventDefault();
			return false;
        }else{
	    	$.ajax({
	    		url: baseURL+'folder/manage/rename_root_folder',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id_root,
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

    $("#delete_root_folder").on("click", function(){
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
	            $('#root_folder_names').html(output)
				$('#delete_root_folder_modal').modal('show');			
			}
		});
	});

    $(document).on("change", "#root_folder_names", function(){		
		id = $('#root_folder_names').val();
		if (id == 0) {
  			$("#confirm_label").addClass("hidden");
		}else{
			$.ajax({
	    		url: baseURL+'folder/manage/cek_delete',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id
				},
				success: function(data){
					if(data.sts == 'OK'){
						$("#confirm_label").addClass("hidden");
					}else{
						$("#confirm_label").removeClass("hidden");
					}
				}
			});	
		}
	});


	$("#submit_delete_root_folder").on("click", function(){
	    var id_root	= $("select[name='root_folder_names']").val();
	    $.ajax({
    		url: baseURL+'folder/manage/delete_root',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_folder : id_root
			},
			success: function(data){
				// console.log(data);
				if(data.sts == 'OK'){
					kiranaAlert(data.sts, data.msg);
					$('#delete_root_folder_modal').modal('hide');
				}else{
					kiranaAlert(data.sts, data.msg, "error", "no");
				}
			}
		});
    });

	$("#root_folder").on("click", function(){
		$('#root_folder_modal').modal('show');			
	});

	$("#save_root_folder").on("click", function(){
	    var name 	= $("input[name='root_folder_name']").val();
	    if($("input[name='root_folder_name']").val() == ""){
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


    $.ajax({
		url: baseURL+'folder/manage/get_data/folder-file',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_folder : 0,
			folder 	  : "pusat dokumen",

		},
		success: function(data){
			// console.log(data);
			$('.datatable-folder').DataTable().destroy();
	        var t   = $('.datatable-folder').DataTable({
	        				order: [[4, 'asc']],
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
	                            { "visible": false, "targets": 4 },
	                        ],
	                    });
	        t.clear().draw();
			
			$.each(data, function (i, v) {
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
			});

		}
	});

	// ===============================================END===================================================

	// ================================GENERATE !ROOT DATA==================================================

	$(document).on("dblclick", ".datatable-folder tr", function(e){

    	var id_folder	= $(this).data("id"); 			
    	var tipe	    = $(this).data("jenis");
    	var parent 	    = $(this).data("parent");
    	var parent_div  = $(this).data("div");
  		var parent_dept = $(this).data("dept");
  		var read        = $(this).data("read");

  		var title_tops 	= $(this).data("title");
  		var title_top 	= title_tops;
  		
  		//att grandparent row
  		var toolbar     = $(this).data("toolbar");
  		var level 		= $(this).data("level");

  		var admin       = $(this).data("admin");
  		var level_write = $(this).data("lwrite");
  		var level_read  = $(this).data("lread");

  		if(tipe != 'Folder' && tipe != 'nav'){
    		id_folder = parent;
    	}
  		// alert(id_folder);
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
			$("#title-top").html("DOKUMEN DIVISI");

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

			// path folder
			if (tipe == 'Folder' || tipe == 'nav') {
				var path_sess = sessionStorage.getItem("path_folder");
				if(tipe == 'nav'){
					if (parent == 0){
						var path_folder = 'DOKUMEN DIVISI';
						sessionStorage.setItem("path_folder", "")
					}else{
						
						var newpath = path_sess.split("/");
						var newpath = newpath.slice(0, newpath.length - 1).join("/");
						sessionStorage.setItem("path_folder", newpath)
						var path_folder = 'DOKUMEN DIVISI / '+ sessionStorage.getItem("path_folder");  
					}
					var path_folder = 'DOKUMEN DIVISI / '+ sessionStorage.getItem("path_folder");  
				}else{
					if (path_sess){
						sessionStorage.setItem("path_folder", path_sess+" / "+title_top)
					}
					else{
						sessionStorage.setItem("path_folder", title_top)
					}	
					var path_folder = 'DOKUMEN DIVISI / '+ sessionStorage.getItem("path_folder");  
				}

				$("#title-top").html(path_folder);
				
			}

    	}

    	if(parent == 0 ){
        	sessionStorage.setItem("folder_admin", id_folder);
        	sessionStorage.setItem("id_new_folder", id_folder);
    	// }else if(parent != 0 && tipe != 'pdf'){
    	}else if((parent != 0) && (tipe == 'nav' || tipe == 'Folder')){ // MUST BE CEK AND TRY
        	sessionStorage.setItem("id_new_folder", id_folder);
        	sessionStorage.setItem("parent_write", level_write); // untuk validasi jika parent bisa write maka setelah user create file, lsg dapet akses write
    	}
    	// Toolbar end


    	if (tipe == 'docx' ||tipe == 'doc' || tipe == 'xls' || tipe == 'xlsx' || tipe == 'swf' || tipe == 'vsd') {
    		// alert("buka file");
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

    	if (tipe == 'pdf') {
    		// alert("bukas file");
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
	       			$("#show_file").html(showPdf('assets/'+data.link));
				}
			});
			$('#view_modal').modal('show');	
    	}

    	

		if (tipe == 'Folder' || tipe == 'nav') {

	    	$.ajax({
	    		url: baseURL+'folder/manage/get_data/folder-file',
				type: 'POST',
				dataType: 'JSON',
				data: {
					id_folder : id_folder,
					folder 	  : "pusat dokumen",
					isAdmin   : sessionStorage.getItem("folder_admin")
				},
				success: function(data){
					// console.log(data);
					$('.datatable-folder').DataTable().destroy();
		            var t   = $('.datatable-folder').DataTable({
	            					order: [[4, 'asc']],
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
		                                { "visible": false, "targets": 4 },
		                                { "className": "text-right", "targets": 3 },
		                            ],
		                        });
		            t.clear().draw();

					$.each(data, function (i, v) {
						// console.log(i);
						// console.log(v);
						if(i == "grandparent" && v !== null){
							var firstrow = t.row.add( [
								                "<i class='fa fa-folder-open icons_orange'></i> ...",
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
		  		$("#downloads").hide();   
		  		// $("#upload").show();
		  		$("#setting").show(); 
		  		$("#rename").hide(); 
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
		  		// $("#upload").show();
		  		$("#new_folder").hide();
				$("#upload").hide();
		  		$("#downloads").hide();   
	  			// $("#new_folder").show();
		  		$("#delete").show(); 
		  		$("#setting").show();
	  			$("#openFile").hide(); 

	  		}else if (tipe == 'nav'){
	  			hideMenu();
	  		}else{ // action list untuk file
	  			$("#openFile").show();
		  		$("#rename").show(); 
		  		$("#downloads").show();
		  		$("#delete").show();
		  		$("#setting").show();
		  		$("#upload").hide();
	  			$("#new_folder").hide();
	  		}
  		}
  		else if(parent != '0' && adminAkses != 'yes' && divAkses == 'yes' && deptAkses == 'yes' && level_write == 'yes'){// not admin, not in root
  			if (tipe == 'Folder') {
		  		$("#rename").show(); 
		  		// $("#upload").show();
		  		$("#new_folder").hide();
	  			$("#upload").hide();
	  			$("#downloads").hide();
	  			// $("#new_folder").show();
		  		$("#delete").show(); 
		  		$("#setting").hide();
	  			$("#openFile").hide();
	  		}else if (tipe == 'nav'){
	  			hideMenu();	 
	  		}else{
	  			$("#openFile").show();
		  		$("#rename").show(); 
		  		$("#downloads").show(); 
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
	  			$("#downloads").show(); 
		  		$("#reupload").hide(); 
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
	       			$("#show_file").html(showPdf('assets/'+data.link));
				}
			});
			$('#view_modal').modal('show');		
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

	$("#downloads").on("click", function(){
		kiranaConfirm({
			title : "Please Confirm",
			text  : "Are you sure you want to download this file?",
        	cancelButtonText: "Cancel",
			confirmButtonText: "Dowload",
	        icon : "warning",
	        useButton : true,
	        successCallback : function(){
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
	        	swal({
					title: "File Downloaded",
			    	type: "success",
			    });

	        },
	        failCallback : function(){
	        	swal({
					title: "Download Cancelled",
			    	type: "error",
			    });
	        },
	        dangerMode : false
		});
	});

	$("#icon_new_folder").on("click", function(){
		var ids = sessionStorage.getItem("id_new_folder");
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
			}
			e.preventDefault();
			return false;

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
		$("input[name='id_folder']").val(sessionStorage.getItem("id_new_folder"));
    	$('#upload_modal').modal('show');		
	});

	$("#upload").on("click", function(){
		$("input[name='id_folder']").val(id);
    	$('#upload_modal').modal('show');		
	});

	$('#fileUpload').on("change", function(e){
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
		$("input[name='name']").val(name);

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
	    	var level_write		 = $("#level_write").val();
	    	var division_read 	 = $("#divisi_read").val();
	    	var department_read	 = $("#department_read").val();
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

	$("#information").on("click", function(){
		swal({
			// position: 'bottom-start',
		 	title: '<strong>APPS GUIDES</strong>',
  			html: '<div class="pull-left" style="text-align:left;font-size: 1.225em;">'+ 
  				  '<span><img src="'+baseURL+'/assets/apps/img/mouse-right.png" style="padding-left: 2px; width:18px; height: 16px;"> Right-Click for Action List</span><br>' +
  				  '<span><img src="'+baseURL+'/assets/apps/img/mouse-left.png" style="padding-left: 2px; width:18px; height: 16px;"> Double-Left-Click for Enter Folder</span>' +
  				  '</div>',
		});		
	});

});


function generate_table_after_action(){
	default_id = sessionStorage.getItem("id_new_folder");
    default_isAdmin = sessionStorage.getItem("folder_admin");

	$.ajax({
		url: baseURL+'folder/manage/get_data/folder-file',
		type: 'POST',
		dataType: 'JSON',
		data: {
			id_folder : default_id,
			folder 	  : "pusat dokumen",
			isAdmin   : default_isAdmin
		},
		success: function(data){
			// console.log(data);
			$('.datatable-folder').DataTable().destroy();
            var t   = $('.datatable-folder').DataTable({
        					order: [[4, 'asc']],
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
                                { "visible": false, "targets": 4 },
                                { "className": "text-right", "targets": 3 },
                            ],
                        });
            t.clear().draw();

			$.each(data, function (i, v) {
				// console.log(i);
				// console.log(v);
				if(i == "grandparent" && v !== null){
					var firstrow = t.row.add( [
						                "<i class='fa fa-folder-open icons_orange'></i> ...",
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