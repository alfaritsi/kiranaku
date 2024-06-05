/*
@application    : MASTER DEPO
@author         : Lukman Hakim(7143)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function () {
	//auto complete id_data(depo)
	$("select[name='id_depo_master']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
			// url: baseURL+'bank/transaksi/get/rekening_auto',
			url: baseURL+'depo/penutupan/get/depo_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, page) {
                return {
                    results: data.items  
                };
            },
            cache: false
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
		templateResult: function(repo) {
			if (repo.loading) return repo.text;
			var markup = '<div class="clearfix">'+repo.id_depo_master+' - '+repo.nama+'</div>';
			return markup;
		},
      	templateSelection: function(repo){ 
			if(repo.id_depo_master && repo.nama) 
				return repo.id_depo_master+' - '+repo.nama;
			else 
				return '';
		}
    });	
	//auto complete sampe sini
	
	//change id_depo_master
    $(document).on("change", "#id_depo_master", function(e){
		var id_depo_master	= $(this).val();
        $.ajax({
			url: baseURL + 'depo/penutupan/get/data_depo',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_depo_master: id_depo_master
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='nomor']").val(v.nomor_penutupan);
					$("select[name='jenis_depo']").val(v.jenis_depo).trigger("change.select2");
					$("select[name='pabrik']").val(v.pabrik).trigger("change.select2");
                });
            }
        });
    });
	//triger add sdm(karyawan)
    $(document).on("click", "button[name='add_sdm']", function () {
		let id_depo_master = $("select[name='id_depo_master']").val();
		// if(id_depo_master){
			let idx = $("tr.row-sdm").length;
			let elem = ".row-sdm.sdm" + idx;
			let output = "";
			if ($("#nodata").length > 0) {
				$("#nodata").remove();
			}

			output += "<tr class='row-sdm sdm" + idx + "'>";
			output += "	<td>";
			output += "		<select class='form-control select2 autocomplete' name='id_depo[]' required='required'>";
			output += "			<option></option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='jarak_depo[]' value='' required='required' />";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='waktu_depo[]' value='' required='required'/>";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-sdm tbody");
			//autocomplete depo KMG
			master_depo(elem + " select[name='id_depo[]']");
		// }else{
			// swal('Warning', 'Mohon isi Nama Depo Lebih Dulu.', 'warning');
		// }	
    });
    $(document).on("click", ".remove_item", function (e) {
        if ($("tr.row-sdm").length > 1) {
            $(this).closest("tr.row-sdm").remove();
        }

        $("tr.row-sdm").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-sdm");
            $(this).addClass("sdm" + i);
        });

        if ($(".table-sdm tbody tr").length == 0) {
            show_nodata();
        }
    });
	
	
	
});


function show_nodata() {
    let col_not_found = $(".table-sdm thead th").not(".d-none").length;
    $(".table-sdm tbody").html('<tr id="nodata"><td colspan="' + col_not_found + '">No data found</td></tr>');
}

function master_depo(elem) {
    if ($(elem).hasClass("select2-hidden-accessible")) {
        $(elem).select2("destroy");
    }

    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        ajax: {
            url: baseURL + "depo/transaksi/get/master_depo",
            dataType: "json",
            delay: 750,
            cache: false,
            data: function (params) {
				console.log(data);
                let selected_id_depo = [];
				$("select[name='id_depo[]']").each(function (i, v) {
                    selected_id_depo.push($(v).val());
                });
                let data = {
                    pabrik: $("select[name='pabrik']").val(),
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
                    not_in_depo: selected_id_depo
                };

                return data;
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function (xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                KIRANAKU.alert({
                    text: `Server Error, (${errorMessage})`,
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            return `<div class="clearfix">[${repo.id}] ${repo.nama_depo}</div>`;
        },
        templateSelection: function (repo) {
			
            let markup = "Silahkan Pilih";
            if (repo.nama_depo)
                markup = `[${repo.id}] ${repo.nama_depo}`;
            if (repo.text)
                markup = repo.text;

            return markup;
        }
    });
}

function master_biaya(elem, jenis_depo, jenis_biaya, jenis_biaya_detail) {
    if ($(elem).hasClass("select2-hidden-accessible")) {
        $(elem).select2("destroy");
    }

    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        ajax: {
            // url: baseURL + "depo/transaksi/get/master_depo",
            url: baseURL + "depo/transaksi/get/master_biaya",
            dataType: "json",
            delay: 750,
            cache: false,
            data: function (params) {
                let selected_id_biaya = [];
				$("select[name='id_depo[]']").each(function (i, v) {
                    selected_id_biaya.push($(v).val());
                });
                let data = {
                    jenis_depo: jenis_depo,
                    jenis_biaya: jenis_biaya,
                    jenis_biaya_detail: jenis_biaya_detail,
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
                    not_in_biaya: selected_id_biaya
                };

                return data;
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function (xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                KIRANAKU.alert({
                    text: `Server Error, (${errorMessage})`,
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            return `<div class="clearfix">${repo.nama_biaya}</div>`;
        },
        templateSelection: function (repo) {
			
            let markup = "Silahkan Pilih";
            if (repo.nama_biaya)
                markup = `${repo.nama_biaya}`;
            if (repo.text)
                markup = repo.text;

            return markup;
        }
    });
}
function submit_order() {
    if (KIRANAKU.validate("#form-depo-input")) {
        let isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
			$("input[name='isproses']").val(1);
			const formData = new FormData($("#form-depo-input")[0]);
			$.ajax({
				// url: baseURL + "fpb/order/save/fpbxx",
				url: baseURL + "depo/transaksi/save/input",
				type: "POST",
				dataType: "JSON",
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				beforeSend: function () { },
				error: function (xhr, status, error) {
					let errorMessage = xhr.status + ': ' + xhr.statusText;
					KIRANAKU.alert({
						text: `Server Error, (${errorMessage})`,
						icon: "error",
						html: false,
						reload: false
					});
				},
				success: function (response) {
					
					if (response) {
						let icon = "error";
						let reload = false;

						if (response.sts == "OK") {
							icon = "success";
							reload = baseURL + "fpb/order/data/approve";
						}

						if (response.html) {
							KIRANAKU.alert({
								icon: icon,
								html: response.msg,
								reload: reload
							});
						} else {
							KIRANAKU.alert({
								text: response.msg,
								icon: icon,
								html: false,
								reload: reload
							});
						}
					}
				},
				complete: function () { }
			});
			
        } else {
            KIRANAKU.alert({
                text: "Silahkan tunggu proses selesai",
                icon: "warning",
                html: false,
                reload: false
            });
        }
    } else {
        KIRANAKU.alert({
            text: "Silahkan lengkapi form terlebih dahulu",
            icon: "error",
            html: false,
            reload: false
        });
    }
}

function generate_modal_action(elem) {
    $('#KiranaModals .modal-dialog').removeClass("modal-lg");
    $('#KiranaModals .modal-dialog').removeClass("modal-xl");
    $("#KiranaModals .modal-content").removeClass("bg-success");
    $("#KiranaModals .modal-content").removeClass("bg-warning");
    $("#KiranaModals .modal-content").removeClass("bg-info");
    $("#KiranaModals .modal-content").removeClass("bg-danger");
    let jenis_depo 	 = $("#form_evaluasi_depo input[name='jenis_depo']").val();
    let nomor 		 = $("#form_evaluasi_depo input[name='nomor']").val();
    let status_akhir = $("#form_evaluasi_depo input[name='status_akhir']").val();
    let action 		 = elem.val();
    switch (action) {
        case "approve":
            $("#KiranaModals .modal-content").addClass("bg-success");
            break;
        case "decline":
            $("#KiranaModals .modal-content").addClass("bg-warning");
            break;
    }
    $("#KiranaModals .modal-title").css("text-transform", "capitalize");
    $("#KiranaModals .modal-title").html(action + " Evaluasi Depo (" + nomor + ")");

    let output = '';
    output += '<div class="row">';
    output += ' <div class="col-sm-12">';
    output += '     <form role="form" id="form-save-depo">';
	if(status_akhir==9){
    output += '         <div class="form-group">';
    output += '             <label>Status Evaluasi</label>';
    output += '             <select class="form-control form-control-hide select2" name="status_evaluasi" id="status_evaluasi" required="required"  data-placeholder="Pilih Status Evaluasi">';
    output += '             	<option ></option>';
    output += '             	<option value="diterima">DITERIMA</option>';
    output += '             	<option value="ditolak">DITOLAK</option>';
    output += '             </select>';
    output += '         </div>';
	}
    output += '         <div class="form-group">';
    output += '             <label>Komentar</label>';
    output += '             <textarea class="form-control" name="komentar_approve_evaluasi" required="required"></textarea>';
    output += '             <input type="text" name="nomor">';
    output += '             <input type="text" name="action">';
    output += '             <input type="text" name="status_akhir">';
    output += '             <input type="text" name="jenis_depo">';
    output += '         </div>';
    output += '     </form>';
    output += ' </div>';
    output += '</div>';
    $("#KiranaModals .modal-body").html(output);

    if (action == 'approve') {
        $("#KiranaModals textarea[name='komentar_approve_evaluasi']").removeAttr("required");
    } else {
        $("#KiranaModals textarea[name='komentar_approve_evaluasi']").attr("required", "required");
    }

    let output_footer = '';
    output_footer += '<div class="modal-footer">';
	if(action=='approve')
    output_footer += '  <button type="button" class="btn btn-primary" id="save-form-action-depo">Approve</button>';
	if(action=='decline')
    output_footer += '  <button type="button" class="btn btn-danger" id="save-form-action-depo">Decline</button>';
    output_footer += '</div>';
    if ($("#KiranaModals .modal-footer").length > 0) {
        $("#KiranaModals .modal-footer").remove();
    }
    $('#KiranaModals .modal-content').append(output_footer);

    $("#KiranaModals input[name='nomor']").val(nomor);
    $("#KiranaModals input[name='action']").val(action);
    $("#KiranaModals input[name='status_akhir']").val(status_akhir);
    $("#KiranaModals input[name='jenis_depo']").val(jenis_depo);


    $('#KiranaModals').modal({
        backdrop: 'static',
        keyboard: true,
        show: true
    });

    KIRANAKU.select2('#KiranaModals');
}


