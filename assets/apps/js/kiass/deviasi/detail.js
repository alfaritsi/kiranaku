$(document).ready(function () {

    $.ajax({
        url: baseURL + "kiass/deviasi/get/deviasi",
        type: 'POST',
        dataType: 'JSON',
        data: {
            no_deviasi: $("input[name='no_deviasi']").val(),
        },
        beforeSend: function () {
            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
            $("body .overlay-wrapper").append(overlay);
        },
        success: function (data) {
            
            if(data) {
                var header = data.header;
                var detail = data.detail;
                var session = data.session_role;

                $("input[name='counter']").val(detail.length);
                $("input[name='no_pp']").val(header.no_pp);
                $("input[name='tgl_pengajuan']").val(generateDateFormat(header.tanggal_pengajuan));
                $("textarea[name='latar_belakang']").val(header.latar_belakang).css('textTransform', 'capitalize');
                $("input[name='id_flow']").val(header.id_flow);

                if(header.id_lampiran_deviasi) {
                    $("input[name='caption_lampiran']").val(header.filename.split('/').pop());
                    $("input[name='caption_lampiran']").closest('.input-group').find('.view_file').attr("data-link", header.filename);
                }

                if(header.filename_fincon) {
                    $("input[name='caption_lampiran_fincon']").closest('.input-group').find('.view_file').attr("data-link", header.filename_fincon);
                    $("input[name='caption_lampiran_fincon']").val(header.filename_fincon.split('/').pop());
                }
                
                $.each(detail, function(i,v){
                    generate_row_analisa(i);
                    var row = (i+1);
                    
                    $("input[name='id_row_analisa_row"+row+"']").val(v.id_row_analisa);
                    $("input[name='id_calon_pembeli_row"+row+"']").val(v.id_calon_pembeli);
                    $("select[name='kode_material_row"+row+"']").append(new Option('[ ' + v.kode_material + ' ] ' + v.full_description, v.kode_material, true, true)).trigger("change.select2");
                    $("select[name='customer_row"+row+"']").append(new Option(v.nama_pembeli + ' - [ ' + v.kode_customer + ' ]', v.kode_customer, true, true)).trigger("change.select2");
                    $("input[name='nama_pembeli_row"+row+"']").val(v.nama_pembeli);
                    $("textarea[name='deskripsi_row"+row+"']").val(v.deskripsi).css('textTransform', 'capitalize');
                    $("textarea[name='rincian_row"+row+"']").val(v.rincian).css('textTransform', 'capitalize');
                    $("input[name='satuan_row"+row+"']").val(v.uom);
                    $("input[name='qty_awal_row"+row+"']").val(v.qty);
                    $("input[name='qty_row"+row+"']").val(v.qty_deviasi);
                    
                    if(v.no_so !== null)
                        $("input[name='so_row"+row+"']").val(v.no_so);
                    
                    $("input[name='harga_nego_row"+row+"']").val(numberWithCommas(parseFloat(v.harga_nego)));
                    $("input[name='total_harga_nego_row"+row+"']").val(numberWithCommas(parseFloat(v.total_harga_nego)));

                    $("input[name='harga_deviasi_row"+row+"']").val(numberWithCommas(v.harga_deviasi * 1));
                    $("input[name='total_deviasi_row"+row+"']").val(numberWithCommas(v.total_deviasi * 1));

                    $("textarea[name='keterangan_row"+row+"']").val(v.keterangan).css('textTransform', 'capitalize');
                    
                }); 

                $(".form-deviasi-scrap select, .form-deviasi-scrap input, .form-deviasi-scrap textarea").attr('disabled','disabled');

                
                if(session.level == '5' && header.status == '5' ){ //Fincon dept head
                    $("button[name='btn_fincon']").removeClass('hide');
                    $(".btn_approve").hide();
                    $(".otorisasi").removeAttr("disabled");
                    $(".otorisasi").removeAttr("readonly");
                    $(".otorisasi").attr("required", true);     
                    $(".fincon").removeAttr("disabled");
                }
                
			}
				
        },
        complete: function () {
            $("body .overlay-wrapper .overlay").remove();

        }
    });

    $(document).on("click", "#log_status", function() {
		$.ajax({
			url: baseURL + "kiass/deviasi/get/log-status",
			type: 'POST',
			dataType: 'JSON',
			data: {
				no_deviasi: $("input[name='no_deviasi']").val()
			},
			beforeSend: function() {
                $("#KiranaModals").removeAttr("class");
	            $("#KiranaModals").addClass("modal");

				$('#KiranaModals .modal-title').html("Log Status Pengajuan");
        		$("#KiranaModals .modal-dialog").addClass("modal-lg");

				var elements = '<table class="table table-bordered table-modals">';
				elements += '	<thead>';
				elements += '		<th>No Pengajuan Deviasi</th>';
				elements += '		<th>Tanggal Status</th>';
				elements += '		<th>Status</th>';
				elements += '		<th>Comment</th>';
				elements += '	</thead>';
				elements += '	<tbody></tbody>';
				elements += '</table>';
				$('#KiranaModals .modal-body').html(elements);
			},
			success: function(data) {
				if (data) {
					$('.table-modals').DataTable().destroy();
					var t = $('.table-modals').DataTable({
						scrollX: true
					});
					t.clear().draw();

					$.each(data, function(i, v) {
						var myrow = t.row.add([
							v.no_deviasi,
							generateDatetimeFormat(v.format_tanggal_status),
							"<span style='text-transform: capitalize'>" + v.action + "</span> oleh <br> <span class='label label-info'>" + v.nama_role + " : " + v.nama + "</label>",
							v.comment
						]).draw(false);
					});
				}
			},
			complete: function() {
				setTimeout(function() {
				    adjustDatatableWidth();
				}, 3000);
				$('#KiranaModals').modal('show');
			}
		});
	});

    $(document).on("keyup", ".qty, .harga_deviasi", function (e) {
        
        var row = $(this).closest('tr').data('row');

        var qty = $("input[name='qty_row"+row+"']").val();
        var harga_deviasi = $("input[name='harga_deviasi_row"+row+"']").val();
        var hasil = qty.replace(/,/g, "") * parseFloat(harga_deviasi.replace(/,/g, ''));
        $("input[name='total_deviasi_row"+row+"']").val(numberWithCommas(hasil));
          
    });

    $(document).on("change", ".upload_file", function (e) {
		$(this).closest(".input-group").find(".caption_file").val(e.target.files[0].name);
		$(this).closest(".input-group").find(".caption_file").attr("title", e.target.files[0].name);
	});

	$(document).on("click", ".view_file", function () {
		if ($(this).data("link") !== "") {
			window.open(baseURL + $(this).data("link"), '_blank');
		} else {
			kiranaAlert("notOK", "File Tidak Ditemukan", "warning", "no");
		}
	});

    $(document).on("click", ".btn_upload_file", function() {
		$(this).closest(".input-group-btn").find(".upload_file").click();
    });

    $(".select-material").on("select2:unselect", function(e) {
        $(this).closest("tr").find('.deskripsi').val('');
        $(this).closest("tr").find('.deskripsi').html('');
        $(this).closest("tr").find('.uom').val('');
    });

    $(document).on("click", "button[name='btn_fincon']", function (e) {
		
        var empty_form = validate(".form-deviasi-scrap");
		if (empty_form == 0) {
            
            $('#KiranaModals .modal-title').css("text-transform", "capitalize");
            $('#KiranaModals .modal-title').html("Approve Deviasi Pengajuan");
            $("#KiranaModals").removeAttr("class");
            $("#KiranaModals").addClass("modal");
            $("#KiranaModals").addClass("modal-success");
                    

            var output = '';
            output += '<form class="form-approval-deviasi" enctype="multipart/form-data">';
            output += '	<div class="modal-body">';
            output += '		<div class="form-horizontal">';
            output += '			<div class="form-group">';
            output += '				<label for="komentar" class="col-sm-12 control-label text-left">Komentar</label>';
            output += '				<div class="col-sm-12">';
            output += '					<textarea class="form-control" name="komentar_fincon"></textarea>';
            output += '				</div>';
            output += '			</div>';
            output += '		</div>';
            output += '	</div>';
            output += '	<div class="modal-footer">';
            output += '		<div class="form-group">';
            output += '			<button type="button" class="btn btn-primary" name="submit-form-fincon">Submit</button>';
            output += '		</div>';
            output += '	</div>';
            output += '</form>';

            $('#KiranaModals .modal-body').remove();
            $('#KiranaModals .modal-footer').remove();
            $('#KiranaModals form').remove();
            $('#KiranaModals .modal-content').append(output);

            $('#KiranaModals').modal({
                backdrop: 'static',
                keyboard: true,
                show: true
            });
        }
    });
    
    $(document).on("click", "button[name='submit-form-fincon']", function (e) {
        $(".form-deviasi-scrap select, .form-deviasi-scrap input, .form-deviasi-scrap textarea").removeAttr("disabled");
        
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            
            var formData = new FormData($(".form-deviasi-scrap")[0]);
            formData.append('komentar', $("textarea[name='komentar_fincon']").val());

            $.ajax({
                url: baseURL + 'kiass/deviasi/save/fincon',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.sts == 'OK') {
                        kiranaAlert(data.sts, data.msg, "success", baseURL + 'kiass/deviasi/lists');
                    } else {
                        kiranaAlert(data.sts, data.msg, "error", "no");
                        $("input[name='isproses']").val(0);
                        $(".form-deviasi-scrap select, .form-deviasi-scrap input, .form-deviasi-scrap textarea").attr('disabled','disabled');
                        $(".otorisasi").removeAttr("disabled");
                        $(".otorisasi").removeAttr("readonly");
                        $(".otorisasi").attr("required", true);
                        $(".fincon").removeAttr("disabled");
                    }
                },
                complete: function () {
                    $("input[name='isproses']").val(0);
                }
            });
            
        } else {
            kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
        }

        // }

		
		e.preventDefault();
		return false;
    });
    
    $(document).on("click", "button[name='action_btn']", function (e) {
		
		$('#KiranaModals .modal-title').css("text-transform", "capitalize");
		$('#KiranaModals .modal-title').html($(this).val() + " Pengajuan Deviasi");
		var no_deviasi = $("input[name='no_deviasi']").val();
		var no_pp = $("input[name='no_pp']").val();
		var id_flow = $("input[name='id_flow']").val();
		var required = "";
		if ($(this).val() !== "approve" && $(this).val() !== "assign") {
			required = "required";
		}

		switch ($(this).val()) {
			case "approve" :
				$("#KiranaModals").removeAttr("class");
				$("#KiranaModals").addClass("modal");
				$("#KiranaModals").addClass("modal-success");
				break;
			case "decline" :
				$("#KiranaModals").removeAttr("class");
				$("#KiranaModals").addClass("modal");
				$("#KiranaModals").addClass("modal-warning");
				break;
			case "assign" :
				$("#KiranaModals").removeAttr("class");
				$("#KiranaModals").addClass("modal");
				$("#KiranaModals").addClass("modal-info");
				break;
			case "stop" :
			case "drop" :
				$("#KiranaModals").removeAttr("class");
				$("#KiranaModals").addClass("modal");
				$("#KiranaModals").addClass("modal-danger");
				break;
		}

		

		var output = '';
		output += '<form class="form-approval-deviasi" enctype="multipart/form-data">';
		output += '	<div class="modal-body">';
		output += '		<div class="form-horizontal">';
		output += '			<div class="form-group">';
		output += '				<label for="komentar" class="col-sm-12 control-label text-left">Komentar</label>';
		output += '				<div class="col-sm-12">';
		output += '					<textarea class="form-control" name="komentar" ' + required + '></textarea>';
		output += '				</div>';
		output += '			</div>';
		output += '		</div>';
		output += '	</div>';
		output += '	<div class="modal-footer">';
		output += '		<div class="form-group">';
		output += '			<input type="hidden" name="action" value="' + $(this).val() + '">';
		output += '			<input type="hidden" name="no_deviasi" value="' + no_deviasi + '">';
		output += '			<input type="hidden" name="no_pp" value="' + no_pp + '">';
		output += '			<input type="hidden" name="id_flow" value="' + id_flow + '">';
		output += '			<button type="button" class="btn btn-primary" name="submit-form-deviasi-scrap">Submit</button>';
		output += '		</div>';
		output += '	</div>';
		output += '</form>';

		$('#KiranaModals .modal-body').remove();
		$('#KiranaModals .modal-footer').remove();
		$('#KiranaModals form').remove();
		$('#KiranaModals .modal-content').append(output);

		$('#KiranaModals').modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});
    });

    $(document).on("click", "button[name='submit-form-deviasi-scrap']", function (e) {
        		
        var empty_form = validate();
		if (empty_form == 0) {
            
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                
                var formData = new FormData($(".form-approval-deviasi")[0]);

                $.ajax({
                    url: baseURL + 'kiass/deviasi/save/approval',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);
                        } else {
                            kiranaAlert(data.sts, data.msg, "error", "no");
                            $("input[name='isproses']").val(0);
                        }
                    },
                    complete: function () {
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

    

});

function generate_row_analisa(indexRow){

    var row = (indexRow + 1);

    var output       = "<tr class='input-table-row row"+row+"' data-row='"+row+"' >";
    output          += "    <td class='text-center'><span class='form-control'>"+row+"</span>";
    output          += "    <input type='hidden' name='id_row_analisa_row"+row+"'>";
    output          += "    <input type='hidden' name='id_calon_pembeli_row"+row+"'>";
    output          += "    <input type='hidden' name='nama_pembeli_row"+row+"'>";
    output          += "    </td>";
    output          += "    <td class='text-center mw200'><select name='kode_material_row"+row+"' class='form-control select-material autocomplete' required data-allowclear='true'></td>";
    output          += "    <td class='text-center mw200'>";
    output          += "        <textarea name='deskripsi_row"+row+"' class='form-control col-sm-12 deskripsi' readonly required='required'></textarea>";
    output          += "    </td>";
    output          += "    <td class='text-center mw200'>";
    output          += "        <textarea name='rincian_row"+row+"' class='form-control col-sm-12' required='required' readonly></textarea>";
    output          += "    </td>";
    output          += "    <td class='text-center mw100'>";
    output          += "        <input type='text' class='text-center form-control col-sm-12 uom' required='required' readonly name='satuan_row"+row+"'>";
    output          += "    </td>";
    // output          += "    <td class='text-center mw200'><input type='text' class='form-control col-sm-12' name='kode_asset_row"+row+"' readonly value='Data tidak ditemukan'></td>";
    output          += "    <td class='text-center mw200'><input type='text' class='text-center form-control col-sm-12' required='required' readonly name='so_row"+row+"' value='testes'></td>";
    output          += "    <td class='text-center mw200'><select name='customer_row"+row+"' class='form-control select-customer autocomplete' required data-allowclear='true'></td>";
    output          += "    <td class='text-center mw100'><input type='text' name='qty_awal_row"+row+"' class='otorisasi text-center qty_awal form-control col-sm-12 angka' readonly></td>";
    output          += "    <td class='text-center mw200'>";
    output          += "        <div class='input-group'>";
    output          += "            <div class='input-group-addon'>Rp</div>";
    output          += "            <input type='text' name='harga_nego_row"+row+"' class='text-right form-control col-sm-12 angka' readonly>";
    output          += "        </div>";
    output          += "    </td>";
    output          += "    <td class='text-center mw200'>";
    output          += "        <div class='input-group'>";
    output          += "            <div class='input-group-addon'>Rp</div>";
    output          += "            <input type='text' name='total_harga_nego_row"+row+"' class='text-right form-control col-sm-12 angka' readonly>";
    output          += "        </div>";
    output          += "    </td>";
    output          += "    <td class='text-center mw100'><input type='text' name='qty_row"+row+"' class='otorisasi text-center qty form-control col-sm-12 angka' readonly></td>";
    output          += "    <td class='text-center mw200'>";
    output          += "        <div class='input-group'>";
    output          += "            <div class='input-group-addon'>Rp</div>";
    output          += "            <input type='text' name='harga_deviasi_row"+row+"' class='otorisasi text-right form-control harga_deviasi angka' required='required' col-sm-12 angka'>";
    output          += "        </div>";
    output          += "    </td>";
    output          += "    <td class='text-center mw200'>";
    output          += "        <div class='input-group'>";
    output          += "            <div class='input-group-addon'>Rp</div>";
    output          += "            <input type='text' name='total_deviasi_row"+row+"' class='text-right form-control col-sm-12' required='required' readonly >";
    output          += "        </div>";
    output          += "    </td>";
    output          += "    <td class='text-center mw200'>";
    output          += "        <textarea name='keterangan_row"+row+"' class='form-control col-sm-12'></textarea>";
    output          += "    </td>";
    output          += "</tr>";

   
    $(".tbody").append(output); 

    var theSelect2Element = null;
    $(".select-material").select2({
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'kiass/setting/get/material',
            dataType: 'json',
            delay: 750,
            data: function(params) {
                return {
                    autocomplete: true,
                    plant: $("input[name='no_pp']").val().split("/")[2],
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, page) {
                
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
        
            if (repo.loading) return repo.text;

            let markup = '<div class="clearfix"> [ ' + repo.id + ' ] ' + repo.full_description + '</div>';
            
            return markup;
        },
        templateSelection: function(repo) {
            
            let markup = "Pilih Kode Material";
            if (repo) {
                
                if(repo.id && repo.full_description){
                    $(theSelect2Element).closest("tr").find('.deskripsi').val(repo.group_description + ' - ' + repo.full_description);
                    $(theSelect2Element).closest("tr").find('.uom').val(repo.uom);
                    markup = '[ ' + repo.id + ' ] ' + repo.full_description;
                }else{
                    markup = repo.text;
                }
                
            }

            return markup;
        }
    }).on('select2:open', function(e){ 
        theSelect2Element = e.currentTarget; 
    });

    $(".select-customer").select2({
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL + 'kiass/setting/get/customer',
            dataType: 'json',
            delay: 750,
            data: function(params) {
                return {
                    autocomplete: true,
                    search: params.term, // search term
                    page: params.page
                };
            },
            processResults: function(data, page) {
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function(markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,
        templateResult: function(repo) {
           
            if (repo.loading) return repo.text;

            let markup = '<div class="clearfix">' + repo.NAME1 + ' - [' + repo.KUNNR + ']</div>';
            
            return markup;
        },
        templateSelection: function(repo) {
            
            let markup = "Pilih Kode Customer";
            if (repo) {
                
                if (repo.id) {
                    
                    if(repo.text){      
                        markup = repo.text;
                    }else{
                        markup = repo.NAME1 + ' - [' + repo.KUNNR + ']';
                    }
                    
                }
                
            }
            
            return markup;
        }
    });


}