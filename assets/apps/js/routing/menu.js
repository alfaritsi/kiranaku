$(document).ready(function(){

	$(".tableTree").treeFy({
		initStatusClass: 'treetable-collapsed',
		treeColumn: 0,
		expanderExpandedClass: 'fa fa-folder-open',
		expanderCollapsedClass: 'fa fa-folder'
	});

	$(document).on("click", ".collapsible-toogle", function (e) {
		if ($(this).attr("aria-expanded") === "true") {
			$(this).html("Hide");
		} else {
			$(this).html("Show");
		}
	});


	$(".activate").on("click", function(e){
    	var id	= $(this).data("activate");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan mengaktifkan data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'routing/setting/set_data/activate_isactive/menu_kiranalytics',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id_menu : id
						},
						success: function(data){
			                if(data.sts == 'OK'){
			                    swal('Success',data.msg,'success').then(function(){
			                        location.reload();
			                    });
			                }else{
			                    $("input[name='isproses']").val(0);
			                    swal('Error',data.msg,'error');
			                }
						}
					});
                }
            }
        );
    });

	$(".delete").on("click", function(e){
    	var id	= $(this).data("delete");

        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menonaktifkan data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'routing/setting/set_data/delete_isactive/menu_kiranalytics',
						type: 'POST',
						dataType: 'JSON',
						data: {
							id : id
						},
						success: function(data){
			                if(data.sts == 'OK'){
			                    swal('Success',data.msg,'success').then(function(){
			                        location.reload();
			                    });
			                }else{
			                    $("input[name='isproses']").val(0);
			                    swal('Error',data.msg,'error');
			                }
						}
					});
                }
            }
        );

    });

	$(".edit").on("click", function(e){
    	var id	= $(this).data("edit");
    	
    	$.ajax({
    		url: baseURL+'routing/setting/get_data/menu_kiranalytics',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_menu : id
			},
			success: function(data){
				// console.log(data);
				$(".form-title").html("<strong>Edit Menu Kiranalytics</strong>");
				$.each(data, function(i, v){
					document.getElementById("parent_id").value = v.parent_id;
					$('.select2').select2();
					$("#nama_menu").val(v.nama_menu);
					$("#link").val(v.link);
					$("#tooltips").val(v.tooltips);

					$("#id_menu").val(v.id_menu);
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
        if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-menu-kiranalytics")[0]);

				$.ajax({
					url: baseURL+'routing/setting/save/menu',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
                        if(data.sts == 'OK'){
                            swal('Success',data.msg,'success').then(function(){
                                location.reload();
                            });
                        }else{
                            $("input[name='isproses']").val(0);
                            swal('Error',data.msg,'error');
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
});