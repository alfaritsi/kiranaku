$(document).ready(function () {

	$(document).on("change", "  #filterstataktif ", function(e){        
        var filterstataktif = $("#filterstataktif").val(); 
        // var filterpabrik = $("#filterpabrik").val();         
        get_datas(filterstataktif);
    });
	// get_datas();
	//submit form
	$(document).on("click", "button[name='action_btn']", function (e) {
		var empty_form = validate('.form-master-template',true);
		if (empty_form == 0) {
			var isproses = $("input[name='isproses']").val();
			if (isproses == 0) {
				$("input[name='isproses']").val(1);
				var formData = new FormData($(".form-master-template")[0]);
				$.ajax({
					url: baseURL + 'kiranaevent/master/save/template',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function () {
			            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
			            $("body .overlay-wrapper").append(overlay);
			        },
					success: function (data) {
						console.log(data);
						if (data.sts == 'OK') {
							kiranaAlert(data.sts, data.msg);
						} else {
							kiranaAlert(data.sts, data.msg, "error", "no");
							$("input[name='isproses']").val(0);
						}
					},
					complete: function () {
						$("body .overlay-wrapper .overlay").remove();
						$("input[name='isproses']").val(0);
					}
				});
			} else {
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
	});

	

	$(document).on("click", ".edit", function (e) {
		// $(".form-master-dot input, .form-master-dot select").val(null).trigger("change");
		$.ajax({
			url: baseURL + "kiranaevent/master/get/data_type",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_typeberita: $(this).data("edit")
			},
			success: function (data) {
				if(data) {
					$.each(data, function(i,v){
						// console.log(data);
						$("input[name='id_templategb']").val(v.id_templategb);						
						// $("input[name='type_fieldname']").val(v.type_berita);
						$("textarea[name='desc_fieldname']").val(v.keterangan);
					});
					$("#btn-new").show();
				}
			}
		});
		e.preventDefault();
		return false;
	});
	
	// reload 
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

    //nonactive
	$(document).on("click", ".nonactive, .setactive, .delete", function (e) {
		if($(this).attr("class") == 'nonactive' || $(this).attr("class") == 'setactive' )
			var confirm_   = "Apakah anda yakin ingin mengubah sistem aktif data ?";
		else 
			var confirm_   = "Apakah anda yakin ingin menghapus data ?";

		if(!confirm(confirm_)){
            e.preventDefault();
            return false;
        }
		$.ajax({
			url: baseURL + "kiranaevent/master/set/template",
			type: 'POST',
			dataType: 'JSON',
			data: {
				kode : $(this).data($(this).attr("class")),
				type : $(this).attr("class")
			},
			success: function(data){
				if(data.sts == 'OK'){
					kiranaAlert(data.sts, data.msg);
				}else{
					kiranaAlert("notOK", data.msg, "warning", "no");
				}
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

function get_datas(aktif=null){
	var x = 1;	
	$.ajax({
        url: baseURL+'kiranaevent/master/get/data_template',
        type: 'POST',
        dataType: 'JSON',
        data: {            
           // pabrik		: pabrik,
           // tahun 		: tahun
        },
        beforeSend: function () {
            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
            $("body .overlay-wrapper").append(overlay);
        },
        success: function(data){
      
      		var t   = $('#main_table').DataTable();
            t.clear().draw();
        	$.each(data, function(i,v){
        		var template = v.type_berita+"<br>"+v.label_active+"<br>";

        		//set action button
        		var action = "";
        		if(v.na == 'n' && v.del == 'n'){
                    action = "<li><a href='#' class='edit' data-edit='"+v.id_templategb+"'>"
                    			+"<i class='fa fa-pencil-square-o'></i> Edit</a></li>"
                          	+"<li><a href='#' class='nonactive' data-nonactive='"+v.id_templategb+"'>"
                          		+"<i class='fa fa-eye-slash'></i> Non Aktif</a></li>"
                          	+"<li><a href='#' class='delete' data-delete='"+v.id_templategb+"'>"
                          		+"<i class='fa fa-trash-o'></i> Hapus</a></li>";
                    
                }
                if(v.na == 'y' && v.del == 'n'){
                    action = "<li><a href='#' class='setactive' "
                    			+"data-setactive='"+v.id_templategb+"'><i class='fa fa-check'></i> Set Aktif</a></li>";
                }
                // set view image            	
            	if(val.template_gb.length > 47 && val.template_gb.match( /(.jpg|.png|.jpeg)/ )){
            		var thmb = val.template_gb;
            	} else {
            		var thmb = baseURL+'assets/file/kiranaevent/default.png';
            	}
            	// <a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox="image'+vv.id_mdot+'" data-type="image"><i class="fa fa-search"></i></a>'
	           	var view 	= '<a target="#" data-fancybox="image'+template+'" data-type="image" href="'+thmb+'" class="fileinput-zoom"><img src="'+thmb+'" class="img-thumbnail" alt="" style="width: 100px; height: 100px;"></a>'
	            
        		t.row.add( [
                    template,
                    view,
                    "<div class='input-group-btn'>"
                    +   "<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>"
                    +   "<ul class='dropdown-menu pull-right'>"
                    +   action 
                    +   "</ul></div>"
                              
                   
                ] ).draw( false );
                t.columns.adjust().draw();
                x++;
        	});

                            
                 
        },
        complete: function () {
            $("body .overlay-wrapper .overlay").remove();
        }
    });
}
