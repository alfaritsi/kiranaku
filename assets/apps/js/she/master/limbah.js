$(document).ready(function(){
	master_material($("#kodematerial"));

    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });

	$(".set_active-jenis").on("click", function(e){
    	var id_mjenis	= $(this).data("activate");
    	$.ajax({
    		url: baseURL+'she/master/set_data/activate/limbah',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_mjenis : id_mjenis
			},
			success: function(data){
				if(data.sts == 'OK'){
                    kiranaAlert(data.sts, data.msg);
				}else{
                    kiranaAlert(data.sts, data.msg, "error", "no");
				}
			}
		});
    });

	$(".delete").on("click", function(e){
    	var jenislimbah	= $(this).data("delete");

        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan non-aktifkan data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/master/set_data/delete_del0/limbah',
						type: 'POST',
						dataType: 'JSON',
						data: {
							jenislimbah : jenislimbah
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );

    });

	$(".set_aktif").on("click", function(e){
    	var jenislimbah	= $(this).data("delete");

        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan mengaktifkan data?",
                dangerMode: true,
                successCallback: function () {
			    	$.ajax({
			    		url: baseURL+'she/master/set_data/delete_del1/limbah',
						type: 'POST',
						dataType: 'JSON',
						data: {
							jenislimbah : jenislimbah
						},
						success: function(data){
							if(data.sts == 'OK'){
			                    kiranaAlert(data.sts, data.msg);
							}else{
			                    kiranaAlert(data.sts, data.msg, "error", "no");
							}
						}
					});
                }
            }
        );

    });

	$(".edit").on("click", function(e){
    	var jenislimbah	= $(this).data("edit");
    	$.ajax({
    		url: baseURL+'she/master/get_data/limbah',
			type: 'POST',
			dataType: 'JSON',
			data: {
				jenislimbah : jenislimbah
			},
			success: function(data){
				// console.log(data);
				$(".title-form").html("<i class='fa fa-pencil-square-o'></i> Edit Limbah");
				$.each(data, function(i, v){
					$("#jenislimbah").val(v.jenis_limbah);
					// $("#kodematerial").val(v.kode_material);
					$("#kodelimbahregulasi").val(v.kode_reglimbah);
					document.getElementById("satuan").value = v.fk_satuan;
					$("#konversiton").val(v.konversi_ton);
					document.getElementById("satuanpengiriman").value = v.fk_satuan_pengiriman;
					$("#konversisatuanpengiriman").val(v.konversi_satuan_pengiriman);
					$("#formlog").val(v.form_log_book_number);
					$("#masasimpan").val(v.masa_simpan);
					$("#satuan").val(v.fk_satuan).trigger('change');
					$("#satuanpengiriman").val(v.fk_satuan_pengiriman).trigger('change');
					// $('.select2').select2();
					const elem = $(".form-master-limbah #kodematerial");
					master_material(elem);
					let control = $(elem).empty().data('select2');
					let adapter = control.dataAdapter;
					let text = `[${v.kode_material}] ${v.description_detail}`;
					adapter.addOptions(adapter.convertToOptions([{'id': v.kode_material, 'text': text}]));
					$(elem).trigger('change');

					$("#id").val(v.jenis_limbah);
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
		    	var formData = new FormData($(".form-master-limbah")[0]);

				$.ajax({
					url: baseURL+'she/master/save/limbah',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
						if(data.sts == 'OK'){
		                    kiranaAlert(data.sts, data.msg);
						}else{
		                    kiranaAlert(data.sts, data.msg, "error", "no");
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

	$("#konversiton").keyup(function() {
		var konversi = $("#konversiton").val();
	  	$("#konversiton").val(konversi.replace(",","."));
    });

	// master_asset_class($("#kodematerial"));
});

function init(){
	document.getElementById("satuan").value = "";
	document.getElementById("satuanpengiriman").value = "";
	// $(".select2").select2();
	$(".init").val("");
	$("#satuan").val('').trigger('change');
	$("#satuanpengiriman").val('').trigger('change');
	$("#kodematerial").val('').trigger('change');
}

function master_material(elem) {
	let classification = null;
	
    if ($(elem).hasClass("select2-hidden-accessible"))
		$(elem).select2("destroy");
	
    $(elem).select2({
		dropdownParent: $('.form-master-limbah'),
        allowClear: true,
        placeholder: {
			id: "",
            text: "Silahkan Pilih"
        },
        maximumSelectionLength: 1,
        ajax: {
			url: baseURL + "she/master/get_data/material",
            dataType: "json",
            delay: 250,
            cache: false,
            data: function(params) {
                let data = {
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page
                };

                return data;
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function(xhr, status, error) {
                if (xhr.statusText != "abort"){
                    let errorMessage = xhr.status + ': ' + xhr.statusText;
                    swal('Error', `Server Error, (${errorMessage})`, 'error');
                }
            },
        },
        escapeMarkup: function(markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
			if (repo.loading) return repo.text;
            return `<div class="clearfix">[${repo.id}] ${repo.description_detail}</div>`;
        },
        templateSelection: function(repo) {
            let markup = "Silahkan Pilih";
            if (repo.text && repo.id) return repo.text;
            if (repo.description_detail)
                markup = `[${repo.id}] ${repo.description_detail}`;

            return markup;
        }
    });
}