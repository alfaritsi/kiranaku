$(document).ready(function () {

    $.ajax({
        url: baseURL + "kiass/deviasi/get/pengajuan",
        type: 'POST',
        dataType: 'JSON',
        data: {
            no_pp: $("input[name='no_pp']").val(),
        },
        beforeSend: function () {
            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
            $("body .overlay-wrapper").append(overlay);
        },
        success: function (data) {
            if(data) {
                $("input[name='counter']").val(data.length);
                $.each(data, function(i,v){
                    generate_row_analisa(i);
                    var row = (i+1);
                    $("input[name='id_flow']").val(v.id_flow);
                    
                    $("input[name='id_row_analisa_row"+row+"']").val(v.id_row_analisa);
                    $("input[name='id_calon_pembeli_row"+row+"']").val(v.id_calon_pembeli);
                    $("select[name='kode_material_row"+row+"']").append(new Option('[ ' + v.kode_material + ' ] ' + v.deskripsi, v.kode_material, true, true)).trigger("change.select2");
                    $("select[name='customer_row"+row+"']").append(new Option(v.nama_pembeli + ' - [ ' + v.kode_customer + ' ]', v.kode_customer, true, true)).trigger("change.select2");
                    $("input[name='nama_pembeli_row"+row+"']").val(v.nama_pembeli);
                    $("textarea[name='deskripsi_row"+row+"']").val(v.deskripsi).css('textTransform', 'capitalize');
                    $("textarea[name='rincian_row"+row+"']").val(v.rincian).css('textTransform', 'capitalize');
                    $("input[name='satuan_row"+row+"']").val(v.uom);
                    $("input[name='qty_awal_row"+row+"']").val(v.qty);
                    // $("input[name='qty_row"+row+"']").val();
                    
                    if(v.no_so !== null)
                        $("input[name='so_row"+row+"']").val(v.no_so);
                    
                    $("input[name='harga_nego_row"+row+"']").val(numberWithCommas(parseFloat(v.harga_nego)));
                    $("input[name='total_harga_nego_row"+row+"']").val(numberWithCommas(parseFloat(v.total_harga_nego)));
                    // $("input[name='harga_deviasi_row"+row+"']").val(numberWithCommas(parseFloat(v.harga_nego)));
                    // $("input[name='total_deviasi_row"+row+"']").val(numberWithCommas(parseFloat(v.total_harga_nego)));



                    // if(v.id_lampiran_deviasi)
                    //     $("input[name='caption_row"+row+"']").val(v.filename.split('/').pop());
                    //     $("input[name='caption_row"+row+"']").closest('.input-group').find('.view_file').attr("data-link", v.filename);
                    
                });               
                
			}
				
        },
        complete: function () {
            $("body .overlay-wrapper .overlay").remove();
            $(".form-deviasi-scrap select").attr('disabled','disabled');

        }
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

    $(document).on("click", "button[name='action_btn']", function (e) {
        $(".form-deviasi-scrap select").removeAttr("disabled");
        var empty_form = validate();
		if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                
                var formData = new FormData($(".form-deviasi-scrap")[0]);

                $.ajax({
                    url: baseURL + 'kiass/deviasi/save/deviasi',
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
                            $(".form-deviasi-scrap select").attr('disabled','disabled');
                        }
                    },
                    complete: function () {
                        $("input[name='isproses']").val(0);
                    }
                });
                
            } else {
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
                $(".form-deviasi-scrap select").attr('disabled','disabled');
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
    output          += "    <td class='text-center mw100'><input type='text' name='qty_awal_row"+row+"' class='text-center qty_awal form-control col-sm-12 angka' readonly></td>";
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
    output          += "    <td class='text-center mw100'><input type='text' name='qty_row"+row+"' class='text-center qty form-control col-sm-12 angka'></td>";
    output          += "    <td class='text-center mw200'>";
    output          += "        <div class='input-group'>";
    output          += "            <div class='input-group-addon'>Rp</div>";
    output          += "            <input type='text' name='harga_deviasi_row"+row+"' class='text-right form-control harga_deviasi angka' required='required' col-sm-12 angka'>";
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