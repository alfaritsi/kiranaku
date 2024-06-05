/*
@application    : Scrao
@author         : Matthew Jodi (8944)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function () {
	//Date picker
	$('.datepicker').datepicker({
		format: 'yyyy',
		autoclose: true
	});
    

	$(document).on("keyup", ".angka", function (e) {
		var angka = $(this).val().replace(/[^0-9.,]*/g, '');
		$(this).val(angka);
		e.preventDefault();
		return false;
    });

    $(document).on("change", ".angka", function (e) {
		var angka = $(this).val().replace(/,/g, "");
		angka = numberWithCommas(angka);
		$(this).val(angka);
		e.preventDefault();
		return false;
    });
    
    
    $(document).on("keyup", ".qty, .harga-satuan", function (e) {
        
        var row = $(this).closest('tr').data('row');
        var indexTabel = $(this).closest('tr').data('tabel');

        var qty = $("input[name='qty_tabel"+indexTabel+"_row"+row+"']").val();

        var ipt = $(".harga_satuan_tabel"+indexTabel+"_row"+row);
        for (let i = 0; i < ipt.length; i++) {
            var harga_satuan = ipt.eq(i).val();
            harga_satuan = harga_satuan || 0;
            var hasil = harga_satuan.replace(/,/g, "") * 1 * qty.replace(/,/g, ""); 
            $("input[name='harga_total_tabel"+indexTabel+"_row"+row+"_calon"+(i+1)+"']").val(hasil).trigger('change');
        }        
		
    });
    
    $(document).on("change", ".total-harga-satuan", function (e) {
        
        var indexTabel = $(this).closest('tr').data('tabel');
        
        var count_row = $("tr.input-table-row"+indexTabel).length;
        
        var indexcalon = $(this).data('calon');

        
        var hasil = 0;
        for (let i = 0; i < count_row; i++) {
            
            var total_satuan =  $("input[name='harga_total_tabel"+indexTabel+"_row"+(i+1)+"_calon"+indexcalon+"']").val();
            
            
            if( hasil == 0){
                hasil = total_satuan;
            }
            else{
                hasil = numberWithCommas((hasil.replace(/,/g, "")*1) + parseFloat(total_satuan.replace(/,/g, "")*1));
            }
        }
        
        hasil = numberWithCommas(hasil) == NaN ? 0 : numberWithCommas(hasil);
        $("input[name='nilai_total_tabel"+indexTabel+"_calon"+indexcalon+"']").val(hasil);
		
	});

    var plant = $("input[name='no_pp']").val().split("/")[2];
    if(plant == 'KMTR'){
        $("select[name='lokasi']").val('HO').trigger('change');
        $('.radioJenisPabrik').attr('disabled', 'disabled');
        $('.radioJenisHO').attr('disabled', false);
        $("#STB").prop("checked", true);
    }else{
        $('.radioJenisPabrik').attr('disabled', false);
        $("#TB").prop("checked", true);
        $("select[name='pic_ho']").val('Finance Controller').trigger('change');
        $("select[name='pic_ho']").prop("disabled", true);
    }

    $(".lokasi").prop("disabled", true);

    $(document).on("click", ".radioJenisPabrik", function (e) {
        var jenis = $(this).val();
        
        if (jenis == 'SPR' || jenis == 'LB3'){
            $("select[name='pic_ho']").val('Factory Operation').trigger('change');
            $("select[name='pic_ho']").prop("disabled", true);
            $("select[name='pembeli']").prop("disabled", false);


            if(jenis == 'LB3'){
                $("select[name='pembeli']").val('pihakKetiga').trigger('change');
                $("select[name='pembeli']").prop("disabled", true);
            }

        }else if(jenis == 'TB'){
            
            $("#pic_ho").append(new Option("Finance Controller", "Finance Controller"));
            $("select[name='pic_ho']").val('Finance Controller').trigger('change');
            $("select[name='pic_ho']").prop("disabled", true);
            $("select[name='pembeli']").prop("disabled", false);

        }else{
            $("select[name='pic_ho']").val('Factory Operation').trigger('change');
            $('#pic_ho option[value="Finance Controller"]').remove();
            $("select[name='pic_ho']").prop("disabled", false);
            $("select[name='pembeli']").prop("disabled", false);

        }

        if(jenis == 'TB' || jenis == 'STB'){
            $(".qty").val('1').trigger('keyup');
            $(".qty").prop("readonly", true);
        }else{
            $(".qty").prop("readonly", false);
        }
    });


    // $(document).on("change", ".lokasi", function (e) {
    //     var lokasi = $(this).val();

        
    //     if (lokasi == 'HO'){
    //         $('.radioJenisPabrik').attr('disabled', 'disabled');
    //         $('.radioJenisHO').attr('disabled', false);

    //     }else{
    //         $('.radioJenisPabrik').attr('disabled', false);
    //     }

    // });

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
    
    var theSelect2Elements = null;
    $(".select-customer").select2({
        allowClear: true,
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

            let markup = '<div class="clearfix">' + repo.NAME1 + ' - [' + repo.KUNNR + ']</div>';
            
            return markup;
        },
        templateSelection: function(repo) {
            

            let markup = "Pilih Kode Customer / Vendor";
            if (repo) {
                
                if(repo.id){
                    var name = $(theSelect2Elements).attr('name');
                    $("input[name='nama_"+name+"']").val(repo.NAME1);
                    $("input[name='nama_"+name+"']").attr("readonly", true);
                    markup = repo.NAME1 + ' - [' + repo.KUNNR + ']';
                }
                
            }

            return markup;
        }
    }).on('select2:open', function(e){ 
        theSelect2Elements = e.currentTarget;
    });
   
    $(".select-customer").on("select2:unselect", function(e) {
        var name = $(this).attr('name');
        $("input[name='nama_"+name+"']").val("");
        $("input[name='nama_"+name+"']").attr("readonly", false);
    });
    
    $(".select-material").on("select2:unselect", function(e) {
        $(this).closest("tr").find('.deskripsi').val('');
        $(this).closest("tr").find('.deskripsi').html('');
        $(this).closest("tr").find('.uom').val('');
    });

    var theSelect2Element = null;
    $(".select-material").select2({
        allowClear: true,
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
                
                if(repo.id){
                    $(theSelect2Element).closest("tr").find('.deskripsi').val(repo.group_description + ' - ' + repo.full_description);
                    $(theSelect2Element).closest("tr").find('.uom').val(repo.uom);
                    markup = '[ ' + repo.id + ' ] ' + repo.full_description;
                }
                
            }

            return markup;
        }
    }).on('select2:open', function(e){ 
        theSelect2Element = e.currentTarget; 
    });

    $(document).on("click", "button[name='action_btn']", function (e) {

        var indexTabel = $(".boxAnalisa").length;
        var output = "";


        output += '<input type="hidden" name="counter_tabel" value="'+indexTabel+'">';

        for (let i = 0; i < indexTabel; i++) {
            var calon = $(".calon_tabel"+(i+1)).length;
            var row = $("tr.input-table-row"+(i+1)).length;
            output += '<input type="hidden" name="counter_calon_tabel'+(i+1)+'" value="'+calon+'">';
            output += '<input type="hidden" name="counter_row_tabel'+(i+1)+'" value="'+row+'">';
            
        }

        $(output).insertAfter("#counter");
		
        var empty_form = validate();
		if (empty_form == 0) {
            $(".lokasi").prop("disabled", false);
            $("select[name='pic_ho']").prop("disabled", false);
            $("select[name='pembeli']").prop("disabled", false);
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                
                var formData = new FormData($(".form-pengajuan-penjualan")[0]);

                $.ajax({
                    url: baseURL + 'kiass/transaksi/save/pengajuan',
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
                            $("select[name='pic_ho']").prop("disabled", true);
                            // $("select[name='pembeli']").prop("disabled", true);
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
    
    $(document).on("click", ".add-col-pembeli", function (e) {
        var indexTabel = $(this).val();
        var count = $(".calon_tabel"+indexTabel).length;

        var row = $("tr.input-table-row"+indexTabel).length;
        
        var output = "";

        output += '<th class="text-center calon_tabel'+indexTabel+' calon'+(count +1)+'" colspan="2">Alternatif '+(count +1);
        // output += '<button type="button" value="'+(count +1)+'" class="btn btn-default btn-sm pull-right dels-input">Hapus</button>';
        output += '</th>';

        output += '';

        var output_harga_calon = "";
        var output_th = "";
        var output_customer = "";
        var output_nama_customer = "";
        var output_identitas = "";
        var output_nilai_total = "";
        var output_tod = "";
        var output_metode = "";
        var output_lampiran = "";
        
        for (let i = 1; i < (row+1); i++) {
       
            output_harga_calon          += "<td class='text-center mw200'>";
            output_harga_calon          += "<div class='input-group'>";
            output_harga_calon          += "<div class='input-group-addon'>Rp</div>";
            output_harga_calon          += "<input type='text' name='harga_satuan_tabel"+indexTabel+"_row"+i+"_calon"+(count +1)+"' class='text-right form-control harga-satuan harga_satuan_tabel"+indexTabel+"_row"+i+" col-sm-12 angka' value='0' required='required'>";
            output_harga_calon          += "</div>";
            output_harga_calon          += "</td>";
            output_harga_calon          += "<td class='text-center mw200'>";
            output_harga_calon          += "<div class='input-group'>";
            output_harga_calon          += "<div class='input-group-addon'>Rp</div>";
            output_harga_calon          += "<input type='text' name='harga_total_tabel"+indexTabel+"_row"+i+"_calon"+(count +1)+"' data-calon='"+(count +1)+"' class='text-right form-control col-sm-12 angka total-harga-satuan calon_harga_tabel"+indexTabel+"_row"+i+"' readonly value='0' required='required'>";
            output_harga_calon          += "</div>";
            output_harga_calon          += "</td>";
            $(output_harga_calon).insertAfter($('.calon_harga_tabel'+indexTabel+'_row'+i+':last').closest('td'));
            output_harga_calon = "";

        }

        output_customer          += "<th colspan='2' class='text-center th-customer_tabel"+indexTabel+"'>";
        output_customer          += "<select name='customer_tabel"+indexTabel+"_calon"+(count +1)+"' class='form-control select-customer select_customer_tabel"+indexTabel+"_calon"+(count +1)+" autocomplete' data-allowclear='true'><option></option></select>";
        output_customer          += "</th>";

        output_nama_customer     += "<th colspan='2' class='text-center th-nama_customer_tabel"+indexTabel+"'>";
        output_nama_customer     += "<input name='nama_customer_tabel"+indexTabel+"_calon"+(count +1)+"' class='form-control' required placeholder='Nama Alternatif'>";
        output_nama_customer     += "</th>";

        output_identitas          += "<th class='text-center'>";
        output_identitas          += "<div class='input-group date'>";
        output_identitas          += "<div class='input-group-addon'>";
        output_identitas          += "<i class='fa fa-id-card'></i>";
        output_identitas          += "</div>";
        output_identitas          += "<input type='text' name='identitas_tabel"+indexTabel+"_calon"+(count +1)+"' class='form-control' required placeholder='Nomor NPWP / KTP'>";
        output_identitas          += "</div>";
        output_identitas          += "</th>";
        output_identitas          += "<th class='text-center th-identitas_tabel"+indexTabel+"'>";
        output_identitas          += "<div class='input-group date'>";
        output_identitas          += "<div class='input-group-addon'>";
        output_identitas          += "<i class='fa fa-mobile'></i>";
        output_identitas          += "</div>	";
        output_identitas          += "<input type='text' name='hp_tabel"+indexTabel+"_calon"+(count +1)+"' class='form-control' required placeholder='Nomor HP'>";
        output_identitas          += "</div>";
        output_identitas          += "</th>";

        output_th          += "<th class='text-center'>Harga Satuan</th>";
        output_th          += "<th class='text-center th-calon_tabel"+indexTabel+"'>Total</th>";

        
        output_nilai_total          += "<td colspan='2' class='text-center mw200 nilai-calon_tabel"+indexTabel+"'>";
        output_nilai_total          += "<div class='input-group'>";
        output_nilai_total          += "<div class='input-group-addon'>Rp</div>";
        output_nilai_total          += "<input type='text' name='nilai_total_tabel"+indexTabel+"_calon"+(count +1)+"' class='text-right form-control col-sm-12 angka' readonly value='0' required='required'>";
        output_nilai_total          += "</div>";
        output_nilai_total          += "</td>";

        
        output_metode          += "    <td colspan='2' class='text-center mw200 metode-calon_tabel"+indexTabel+"'>";
        output_metode          += "<select class='form-control select2'";
        output_metode          += "name='metode_tabel"+indexTabel+"_calon"+(count +1)+"'";
        output_metode          += "style='width: 100%;'";
        output_metode          += "required='required'>";
        output_metode          += "            <option value='tunai'>Tunai</option>";
        output_metode          += "            <option value='transfer'>Transfer</option>";
        output_metode          += "</select>";
        output_metode          += "    </td>";


        output_tod          += "<td class='tod-calon_tabel"+indexTabel+"' colspan='2'>";
        output_tod          += "<input type='text' name='tod_tabel"+indexTabel+"_calon"+(count +1)+"' required class='text-center form-control col-sm-12'>";
        output_tod          += "</td>";
        
        
        output_lampiran          += "<td colspan='2' class='lampiran-calon_tabel"+indexTabel+"'>";
        output_lampiran          += "<div class='input-group' style='width: 100%;'>";
        output_lampiran          += "<input type='text' class='form-control caption_file' name='caption_tabel"+indexTabel+"_calon"+(count +1)+"' required='required' readonly='readonly'>";
        output_lampiran          += "<div class='input-group-btn'>";
        output_lampiran          += "<input type='file' name='lampiran_tabel"+indexTabel+"_calon"+(count +1)+"[]' class='form-control upload_file berkas' style='display:none;'>";
        output_lampiran          += "<button type='button' class='btn btn-default btn-flat btn_upload_file' data-title='Upload'><i class='fa fa-upload'></i></button>";
        output_lampiran          += "</div>";
        output_lampiran          += "<div class='input-group-btn'>";
        output_lampiran          += "<button type='button' class='btn btn-default btn-flat view_file' data-link='' title='Lihat file'><i class='fa fa-search'></i></button>";
        output_lampiran          += "</div>";
        output_lampiran          += "</div>";
        output_lampiran          += "</td>";


        
        $(output_identitas).insertAfter($('.th-identitas_tabel'+indexTabel+':last'));
        $(output_th).insertAfter($('.th-calon_tabel'+indexTabel+':last'));
        $(output_customer).insertAfter($('.th-customer_tabel'+indexTabel+':last'));
        $(output_nama_customer).insertAfter($('.th-nama_customer_tabel'+indexTabel+':last'));
        $(output_nilai_total).insertAfter($('.nilai-calon_tabel'+indexTabel+':last'));
        $(output_metode).insertAfter($('.metode-calon_tabel'+indexTabel+':last'));
        $(output_tod).insertAfter($('.tod-calon_tabel'+indexTabel+':last'));
        $(output_lampiran).insertAfter($('.lampiran-calon_tabel'+indexTabel+':last'));
        
        var option_calon_pembeli  = "<option value='"+(count +1)+"'>Alternatif "+(count +1)+"</option>";
        
        $('.select-calon-pembeli_tabel'+indexTabel).append(option_calon_pembeli);
        $('.select-calon-pembeli_tabel'+indexTabel).select2();
        
        var theSelect2Elements = null;
        $(".select_customer_tabel"+indexTabel+"_calon"+(count +1)).select2({
            allowClear: true,
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
    
                let markup = '<div class="clearfix">' + repo.NAME1 + ' - [' + repo.KUNNR + ']</div>';
                
                return markup;
            },
            templateSelection: function(repo) {
                
    
                let markup = "Pilih Kode Customer / Vendor";
                if (repo) {
                    
                    if(repo.id){
                        var name = $(theSelect2Elements).attr('name');
                        $("input[name='nama_"+name+"']").val(repo.NAME1);
                        $("input[name='nama_"+name+"']").attr("readonly", true);
                        markup = repo.NAME1 + ' - [' + repo.KUNNR + ']';
                    }
                    
                }
    
                return markup;
            }
        }).on('select2:open', function(e){ 
            theSelect2Elements = e.currentTarget;
        });

        $(".select-customer").on("select2:unselect", function(e) {
            var name = $(this).attr('name');
            $("input[name='nama_"+name+"']").val("");
            $("input[name='nama_"+name+"']").attr("readonly", false);
        });


        $(output).insertAfter($('.calon_tabel'+indexTabel+':last'));



    });

    $(document).on("click", ".del-col-pembeli",function () {    
        
        var indexTabel = $(this).val();
        var count = $(".calon_tabel"+indexTabel).length;

        var row = $("tr.input-table-row"+indexTabel).length;

        var dvalue = 2;
        var ket_satu_pembeli = $("textarea[name='ket_satu_pembeli']").val();
        if (ket_satu_pembeli !== "" && ket_satu_pembeli.trim() !== ""){
            dvalue = 1;
        }

		if (count > dvalue){
            for (let i = 1; i < (row+1); i++) {
                $('.calon_harga_tabel'+indexTabel+'_row'+i+':last').closest('td').remove();
                $('input[name="harga_satuan_tabel'+indexTabel+'_row'+i+'_calon'+count+'"]').closest('td').remove();
            }
            
            $('input[name="identitas_tabel'+indexTabel+'_calon'+count+'"]').closest('th').remove();
            $('.th-identitas_tabel'+indexTabel+':last').closest('th').remove();
            $('.th-calon_tabel'+indexTabel+':last').prev().remove();
            $('.th-calon_tabel'+indexTabel+':last').remove();
            $('.th-customer_tabel'+indexTabel+':last').remove();
            $('.th-nama_customer_tabel'+indexTabel+':last').remove();
            $('.nilai-calon_tabel'+indexTabel+':last').remove();
            $('.metode-calon_tabel'+indexTabel+':last').remove();
            $('.tod-calon_tabel'+indexTabel+':last').remove();
            $('.lampiran-calon_tabel'+indexTabel+':last').remove();
            $('.calon_tabel'+indexTabel+':last').remove();

            
            $('.select-calon-pembeli_tabel'+indexTabel).each(function() {
                $(this).find("option:last").remove();
            });
            $('.select-calon-pembeli_tabel'+indexTabel).select2();

        }

    });

	$(document).on("click", ".add-row", function (e) {
        var indexTabel = $(this).val();
        var count = $("tr.input-table-row"+indexTabel).length;
        var row = (count+1);
        var calon = $(".calon_tabel"+indexTabel).length;

        var output_harga_calon = "";
        var option_calon_pembeli = "";
        for (let i = 1; i < (calon+1); i++) {
       
            output_harga_calon          += "<td class='text-center mw200'>";
            output_harga_calon          += "<div class='input-group'>";
            output_harga_calon          += "<div class='input-group-addon'>Rp</div>";
            output_harga_calon          += "<input type='text' name='harga_satuan_tabel"+indexTabel+"_row"+row+"_calon"+i+"' class='text-right form-control harga-satuan harga_satuan_tabel"+indexTabel+"_row"+row+" col-sm-12 angka' value='0' required='required'>";
            output_harga_calon          += "</div>";
            output_harga_calon          += "</td>";
            output_harga_calon          += "<td class='text-center mw200'>";
            output_harga_calon          += "<div class='input-group'>";
            output_harga_calon          += "<div class='input-group-addon'>Rp</div>";
            output_harga_calon          += "<input type='text' name='harga_total_tabel"+indexTabel+"_row"+row+"_calon"+i+"' data-calon='"+i+"' class='text-right form-control col-sm-12 angka total-harga-satuan calon_harga_tabel"+indexTabel+"_row"+row+"' readonly value='0' required='required'>";
            output_harga_calon          += "</div>";
            output_harga_calon          += "</td>";

            option_calon_pembeli          += "<option value='"+i+"'>Alternatif "+i+"</option>";
        }

		var output       = "<tr class='input-table-row"+(indexTabel)+" row"+row+"' data-row='"+row+"' data-tabel='"+(indexTabel)+"'>";
		output          += "    <td class='text-center'><span class='form-control'>"+row+"</span></td>";
		output          += "    <td class='mw200'><select name='kode_material_tabel"+indexTabel+"_row"+row+"' class='form-control select-material select_material_tabel"+indexTabel+"_row"+row+" autocomplete' required data-allowclear='true'></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='deskripsi_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12 deskripsi' readonly required='required'></textarea>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='rincian_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12' required='required' ></textarea>";
        output          += "    </td>";
        output          += "    <td class='text-center mw100'>";
        output          += "        <input type='text' class='text-center form-control col-sm-12 uom' required='required' readonly name='satuan_tabel"+indexTabel+"_row"+row+"'>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <input type='text' name='kode_asset_tabel"+indexTabel+"_row"+row+"' class='text-right form-control angka mw150' readonly>";
        output          += "            <div class='input-group-addon'>-</div>";
        output          += "            <input type='text' name='sno_tabel"+indexTabel+"_row"+row+"' class='text-right form-control angka mw40' readonly>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='deskripsi_asset_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12' readonly ></textarea>";
        output          += "    </td>";
		output          += "    <td class='text-center mw150'><input type='text' class='form-control col-sm-12' name='cap_date_tabel"+indexTabel+"_row"+row+"' readonly></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' class='text-center form-control col-sm-12 angka' readonly name='nbv_tabel"+indexTabel+"_row"+row+"'>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw100'><input type='text' name='qty_tabel"+indexTabel+"_row"+row+"' value='0' class='text-center qty form-control col-sm-12 angka'></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_terakhir_tabel"+indexTabel+"_row"+row+"' value='0' class='text-right form-control col-sm-12 angka' required='required'>";
        output          += "        </div>";
        output          += "    </td>";
        output          += output_harga_calon;
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_nego_tabel"+indexTabel+"_row"+row+"' class='text-right form-control col-sm-12 angka' value='0' readonly>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='total_harga_nego_tabel"+indexTabel+"_row"+row+"' class='text-right form-control col-sm-12 angka' value='0' readonly>";
        output          += "        </div>";
		output          += "    </td>";

        output          += "    <td class='text-center mw200'>";
        output          += "<select class='form-control select2 select-calon-pembeli_tabel"+indexTabel+"'";
        output          += "name='pembeli_tabel"+indexTabel+"_row"+row+"'";
        output          += "style='width: 100%;'";
        output          += ">";
        output          += option_calon_pembeli;
        output          += "</select>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_varian_tabel"+indexTabel+"_row"+row+"' class='text-right form-control col-sm-12 angka' value='0' readonly >";
        output          += "        </div>";
        output          += "    </td>"; 
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='total_varian_tabel"+indexTabel+"_row"+row+"' class='text-right form-control col-sm-12 angka' value='0' readonly >";
        output          += "        </div>";
        output          += "    </td>";
        
        
        output          += "<td class='text-center mw200'>";
        output          += "<div class='input-group'>";
        output          += "<input type='text' class='form-control caption_file' name='caption_tabel"+indexTabel+"_row"+row+"' required='required' readonly='readonly'>";
        output          += "<div class='input-group-btn'>";
        output          += "<input type='file' class='form-control upload_file berkas' name='foto_tabel"+indexTabel+"_row"+row+"[]' style='display:none;'>";
        output          += "<button type='button' class='btn btn-default btn-flat btn_upload_file' data-title='Upload'><i class='fa fa-upload'></i></button>";
        output          += "</div>";
        output          += "<div class='input-group-btn'>";
        output          += "<button type='button' class='btn btn-default btn-flat view_file' data-link='' title='Lihat file'><i class='fa fa-search'></i></button>";
        output          += "</div>";
        output          += "</div>";
        output          += "</td>"; 


        output          += "</tr>";    


        $(output).insertAfter("tr.input-table-row"+indexTabel+":last");

        var jenisBarang = $("input[name=radioJenis]:checked").val();
        if(jenisBarang == 'TB' || jenisBarang == 'STB'){
            $(".qty").val('1').trigger('keyup');
            $(".qty").prop("readonly", true);
        }

        $('.select-calon-pembeli_tabel'+indexTabel).select2();
        
        $("select[name='satuan[]'].select2").select2();

        $(".select-material").on("select2:unselect", function(e) {
            $(this).closest("tr").find('.deskripsi').val('');
            $(this).closest("tr").find('.deskripsi').html('');
            $(this).closest("tr").find('.uom').val('');
        });

        var theSelect2Element = null;
        $(".select_material_tabel"+indexTabel+"_row"+row).select2({
            allowClear: true,
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
                    
                    if(repo.id){
                        $(theSelect2Element).closest("tr").find('.deskripsi').val(repo.group_description + ' - ' + repo.full_description);
                        $(theSelect2Element).closest("tr").find('.uom').val(repo.uom);
                        markup = '[ ' + repo.id + ' ] ' + repo.full_description;
                    }
                    
                }

                return markup;
            }
        }).on('select2:open', function(e){ 
            theSelect2Element = e.currentTarget; 
        });

	});

	$(document).on("click", ".del-row", function (e) {
        var indexTabel = $(this).val();
        var count = $("tr.input-table-row"+indexTabel).length;
		if (count > 1) $("tr.input-table-row"+indexTabel).last().remove();
	});

	$(document).on("click", ".add-ann", function (e) {
        var count = $(".boxAnalisa").length;

        var indexTabel = (count+1);
        var row = 1;

        var output_harga_calon = "";
        var option_calon_pembeli = "";
        for (let i = 1; i < (2+1); i++) {
       
            output_harga_calon          += "<td class='text-center mw200'>";
            output_harga_calon          += "<div class='input-group'>";
            output_harga_calon          += "<div class='input-group-addon'>Rp</div>";
            output_harga_calon          += "<input type='text' name='harga_satuan_tabel"+indexTabel+"_row1_calon"+i+"' class='text-right form-control harga-satuan harga_satuan_tabel"+indexTabel+"_row1 col-sm-12 angka' value='0' required='required'>";
            output_harga_calon          += "</div>";
            output_harga_calon          += "</td>";
            output_harga_calon          += "<td class='text-center mw200'>";
            output_harga_calon          += "<div class='input-group'>";
            output_harga_calon          += "<div class='input-group-addon'>Rp</div>";
            output_harga_calon          += "<input type='text' name='harga_total_tabel"+indexTabel+"_row1_calon"+i+"' data-calon='"+i+"' class='text-right form-control col-sm-12 angka total-harga-satuan calon_harga_tabel"+indexTabel+"_row1' readonly value='0' required='required'>";
            output_harga_calon          += "</div>";
            output_harga_calon          += "</td>";

            option_calon_pembeli          += "<option value='"+i+"'>Alternatif "+i+"</option>";
        }

        var output       = "<div class='box boxAnalisa boxAn"+(count+1)+"' style='border: 1px solid black;'>";
        output          += "<div class='box-header' style='border-bottom: 1px solid black;'>";
        output          += "    <h3 class='box-title' style='font-size:15px;'>Analisa Harga "+(count+1)+"</h3>";
        output          += "    <div class='box-tools pull-right'>";
        output          += "        <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i>";
        output          += "        </button>";
        output          += "    </div>";
        output          += "</div>";

        output          += "<div class='box-body'>";
        output          += "<div class='row'>";
        output          += "<div class='col-sm-12'>";
        output          += "<div class='form-group'>";
        output          += "<button type='button' value='"+(count+1)+"' class='btn btn-default btn-sm add-row'>Tambah baris tabel</button>";
        output          += "<button type='button' value='"+(count+1)+"' class='btn btn-default btn-sm del-row'>Hapus baris tabel</button>";
        output          += "<button type='button' value='"+(count+1)+"' class='btn btn-default btn-sm add-col-pembeli'>Tambah kolom alternatif</button>";
        output          += "<button type='button' value='"+(count+1)+"' class='btn btn-default btn-sm del-col-pembeli'>Hapus kolom alternatif</button>";
        output          += "</div>";
        output          += "<div class='table-responsive scrolls'>";
        output          += "<table class='table table-hover table-form'>";
        output          += "<thead>";
        output          += "<tr>";
        output          += "<th class='text-center' rowspan='5'>NO</th>";
        output          += "<th class='text-center' rowspan='5'>Kode Material</th>";
        output          += "<th class='text-center' rowspan='5'>Deskripsi</th>";
        output          += "<th class='text-center' rowspan='5'>Rincian</th>";
        output          += "<th class='text-center' rowspan='5'>UOM</th>";
        output          += "<th class='text-center' rowspan='5'>Kode Asset</th>";
        output          += "<th class='text-center' rowspan='5'>Deskripsi Asset</th>";
        output          += "<th class='text-center' rowspan='5'>Cap Date</th>";
        output          += "<th class='text-center' rowspan='5'>NBV</th>";
        output          += "<th class='text-center' rowspan='5'>Qty</th>";
        output          += "<th class='text-center' rowspan='5'>Harga Terakhir</th>";
        output          += "<th class='text-center calon_tabel"+(count+1)+" calon1' colspan='2'>Alternatif 1 </th>";
        output          += "<th class='text-center calon_tabel"+(count+1)+" calon2' colspan='2'>Alternatif 2 </th>";
        output          += "<th class='text-center' colspan='3' rowspan='4'>Procurement HO</th>";
        output          += "<th class='text-center' colspan='2' rowspan='4'>Varian</th>";
        output          += "<th class='text-center' rowspan='4'>Foto Kondisi Barang</th>												";
        output          += "</tr>";

        output          += "<tr>";
        output          += "<th colspan='2' class='text-center th-customer_tabel"+indexTabel+"'>";
        
        output          += "<select name='customer_tabel"+indexTabel+"_calon1' class='form-control select-customer select_customer_tabel"+indexTabel+" autocomplete' data-allowclear='true'><option></option></select>";
        
        output          += "</th>";
        output          += "<th colspan='2' class='text-center th-customer_tabel"+indexTabel+"'>";
        
        output          += "<select name='customer_tabel"+indexTabel+"_calon2' class='form-control select-customer select_customer_tabel"+indexTabel+" autocomplete' data-allowclear='true'><option></option></select>";
        
        output          += "</th>";
        
        output          += "</tr>";

        output          += "<tr>";
        output          += "<th colspan='2' class='text-center th-nama_customer_tabel"+indexTabel+"'>";
        
        output          += "<input name='nama_customer_tabel"+indexTabel+"_calon1' class='form-control' required placeholder='Nama Alternatif'>";
        
        output          += "</th>";
        output          += "<th colspan='2' class='text-center th-nama_customer_tabel"+indexTabel+"'>";
        
        output          += "<input name='nama_customer_tabel"+indexTabel+"_calon2' class='form-control' required placeholder='Nama Alternatif'>";
        
        output          += "</th>";
        
        output          += "</tr>";


        output          += "<tr>";
        output          += "<th class='text-center'>";
        output          += "<div class='input-group date'>";
        output          += "<div class='input-group-addon'>";
        output          += "<i class='fa fa-id-card'></i>";
        output          += "</div>";
        output          += "<input type='text' name='identitas_tabel"+indexTabel+"_calon1' class='form-control' required placeholder='Nomor NPWP / KTP'>";
        output          += "</div>";
        output          += "</th>";
        output          += "<th class='text-center th-identitas_tabel"+indexTabel+"'>";
        output          += "<div class='input-group date'>";
        output          += "<div class='input-group-addon'>";
        output          += "<i class='fa fa-mobile'></i>";
        output          += "</div>	";
        output          += "<input type='text' name='hp_tabel"+indexTabel+"_calon1' class='form-control' required placeholder='Nomor HP'>";
        output          += "</div>";
        output          += "</th>";
        output          += "<th class='text-center'>";
        output          += "<div class='input-group date'>";
        output          += "<div class='input-group-addon'>";
        output          += "<i class='fa fa-id-card'></i>";
        output          += "</div>";
        output          += "<input type='text' name='identitas_tabel"+indexTabel+"_calon2' class='form-control' required placeholder='Nomor NPWP / KTP'>";
        output          += "</div>";
        output          += "</th>";
        output          += "<th class='text-center th-identitas_tabel"+indexTabel+"'>";
        output          += "<div class='input-group date'>";
        output          += "<div class='input-group-addon'>";
        output          += "<i class='fa fa-mobile'></i>";
        output          += "</div>	";
        output          += "<input type='text' name='hp_tabel"+indexTabel+"_calon2' class='form-control' required placeholder='Nomor HP'>";
        output          += "</div>";
        output          += "</th>";
        output          += "</tr>";
        output          += "<tr>";
        output          += "<th class='text-center'>Harga Satuan<br><small><em>Sebelum PPN</em></small></th>";
        output          += "<th class='text-center th-calon_tabel"+indexTabel+"'>Total<br><small><em>Sebelum PPN</em></small></th>";
        output          += "<th class='text-center'>Harga Satuan<br><small><em>Sebelum PPN</em></small></th>";
        output          += "<th class='text-center th-calon_tabel"+indexTabel+"'>Total<br><small><em>Sebelum PPN</em></small></th>";
        output          += "<th class='text-center'>Harga Nego<br><small><em>Sebelum PPN</em></small></th>";
        output          += "<th class='text-center'>Total<br><small><em>Sebelum PPN</em></small></th>";
        output          += "<th class='text-center'>Pilihan Alternatif</th>";
        output          += "<th class='text-center'>Harga Satuan<br><small><em>Sebelum PPN</em></small></th>";
        output          += "<th class='text-center'>Total<br><small><em>Sebelum PPN</em></small></th>";
        output          += "<th class='text-center'>Sesuai Template</th>													";
        output          += "</tr>";
        output          += "</thead>";
        output          += "<tbody>";
        output          += "<tr class='input-table-row"+(indexTabel)+" row"+row+"' data-row='"+row+"' data-tabel='"+(indexTabel)+"'>";
		output          += "    <td class='text-center'><span class='form-control'>"+row+"</span></td>";
		output          += "    <td class='mw200'><select name='kode_material_tabel"+indexTabel+"_row"+row+"' class='form-control select-material select_material_tabel"+indexTabel+"_row"+row+" autocomplete' required data-allowclear='true'></select></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='deskripsi_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12 deskripsi' required='required' readonly></textarea>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='rincian_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12' required='required' ></textarea>";
        output          += "    </td>";
        output          += "    <td class='text-center mw100'>";
        output          += "        <input name='satuan_tabel"+indexTabel+"_row"+row+"' class='text-center form-control col-sm-12 uom' readonly>";
		output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <input type='text' class='form-control mw150' name='kode_asset_tabel"+indexTabel+"_row"+row+"' readonly>";
        output          += "            <div class='input-group-addon'>-</div>";
        output          += "            <input type='text' class='form-control mw40' name='sno_tabel"+indexTabel+"_row"+row+"' readonly>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='deskripsi_asset_fincon_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12' readonly></textarea>";
        output          += "    </td>";
		output          += "    <td class='text-center mw150'><input type='text' class='form-control col-sm-12' name='cap_date_tabel"+indexTabel+"_row"+row+"' readonly></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' class='text-center form-control col-sm-12' readonly name='nbv_tabel"+indexTabel+"_row"+row+"'>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw100'><input type='text' name='qty_tabel"+indexTabel+"_row"+row+"' value='0' class='text-center form-control qty col-sm-12 angka'></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_terakhir_tabel"+indexTabel+"_row"+row+"' value='0' class='text-right form-control col-sm-12 angka' required='required'>";
        output          += "        </div>";
        output          += "    </td>";
        output          += output_harga_calon;
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_nego_tabel"+indexTabel+"_row"+row+"' readonly class='text-right form-control col-sm-12 angka' value='0' >";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='total_harga_nego_tabel"+indexTabel+"_row"+row+"' readonly class='text-right form-control col-sm-12 angka' value='0' >";
        output          += "        </div>";
		output          += "    </td>";

        output          += "    <td class='text-center mw200'>";
        output          += "<select class='form-control select2 select-calon-pembeli_tabel"+indexTabel+"'";
        output          += "name='pembeli_tabel"+indexTabel+"_row"+row+"'";
        output          += "style='width: 100%;'";
        output          += ">";
        output          += option_calon_pembeli;
        output          += "</select>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_varian_tabel"+indexTabel+"_row"+row+"' readonly class='text-right form-control col-sm-12 angka' value='0' >";
        output          += "        </div>";
        output          += "    </td>"; 
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='total_varian_tabel"+indexTabel+"_row"+row+"' readonly class='text-right form-control col-sm-12 angka' value='0' >";
        output          += "        </div>";
        output          += "    </td>";
        
        output          += "<td class='text-center mw200'>";
        output          += "<div class='input-group'>";
        output          += "<input type='text' class='form-control caption_file' name='caption_tabel"+indexTabel+"_row"+row+"' required='required' readonly='readonly'>";
        output          += "<div class='input-group-btn'>";
        output          += "<input type='file' class='form-control upload_file berkas' name='foto_tabel"+indexTabel+"_row"+row+"[]' style='display:none;'>";
        output          += "<button type='button' class='btn btn-default btn-flat btn_upload_file' data-title='Upload'><i class='fa fa-upload'></i></button>";
        output          += "</div>";
        output          += "<div class='input-group-btn'>";
        output          += "<button type='button' class='btn btn-default btn-flat view_file' data-link='' title='Lihat file'><i class='fa fa-search'></i></button>";
        output          += "</div>";
        output          += "</div>";
        output          += "</td>"; 


        output          += "</tr>";
        
        
        output          += "<tr>";
        output          += "<td colspan='10' style='background:#d3d3d369;'></td>";
        output          += "<td class='text-right'>Nilai Total</td>";
        output          += "<td colspan='2' class='nilai-calon_tabel"+indexTabel+"'>";
        output          += "<div class='input-group col-sm-12'>";
        output          += "<div class='input-group-addon'>Rp</div>";
        output          += "<input type='text' name='nilai_total_tabel"+indexTabel+"_calon1' class='text-right form-control col-sm-12 angka' value='0' readonly='readonly' >";
        output          += "</div>";
        output          += "</td>";
        output          += "<td colspan='2' class='nilai-calon_tabel"+indexTabel+"'>";
        output          += "<div class='input-group col-sm-12'>";
        output          += "<div class='input-group-addon'>Rp</div>";
        output          += "<input type='text' name='nilai_total_tabel"+indexTabel+"_calon2' class='text-right form-control col-sm-12 angka' value='0' readonly='readonly' >";
        output          += "</div>";
        output          += "</td>";
        output          += "<td colspan='8' style='background:#d3d3d369;'></td>";
        output          += "</tr>";
        output          += "<tr>";
        output          += "<td colspan='10' style='background:#d3d3d369;'></td>";
        output          += "<td class='text-right'>Metode Pembayaran</td>";
        output          += "<td colspan='2' class='metode-calon_tabel"+indexTabel+"'>";
        output          += "<select class='form-control metodeSelect select2'";
        output          += "name='metode_tabel"+indexTabel+"_calon1'";
        output          += "style='width: 100%;'";
        output          += "required='required'>";
        output          += "<option value='tunai'>Tunai</option>";
        output          += "<option value='transfer'>Transfer</option>";
        output          += "</select>";
        output          += "</td>";
        output          += "<td colspan='2' class='metode-calon_tabel"+indexTabel+"'>";
        output          += "<select class='form-control metodeSelect select2'";
        output          += "name='metode_tabel"+indexTabel+"_calon2'";
        output          += "style='width: 100%;'";
        output          += "required='required'>";
        output          += "<option value='tunai'>Tunai</option>";
        output          += "<option value='transfer'>Transfer</option>";
        output          += "</select>";
        output          += "</td>";
        output          += "<td colspan='10' style='background:#d3d3d369;'></td>";
        output          += "</tr>";
        output          += "<tr>";
        output          += "<td colspan='10' style='background:#d3d3d369;'></td>";
        output          += "<td class='text-right'>Term of Delivery / Duration</td>";
        output          += "<td colspan='2' class='tod-calon_tabel"+indexTabel+"'>";
        output          += "<input type='text' name='tod_tabel"+indexTabel+"_calon1' required class='text-center form-control col-sm-12'>";
        output          += "</td>";
        output          += "<td colspan='2' class='tod-calon_tabel"+indexTabel+"'>";
        output          += "<input type='text' name='tod_tabel"+indexTabel+"_calon2' required class='text-center form-control col-sm-12'>";
        output          += "</td>";
        output          += "<td colspan='10' style='background:#d3d3d369;'></td>";
        output          += "</tr>";
        output          += "<tr>";
        output          += "<td colspan='10' style='background:#d3d3d369;'></td>";
        output          += "<td class='text-right'>Lampiran</td>";
        output          += "<td colspan='2' class='lampiran-calon_tabel"+indexTabel+"'>";
        output          += "<div class='input-group' style='width: 100%;'>";
        output          += "<input type='text' class='form-control caption_file' name='caption_tabel"+indexTabel+"_calon1' required='required' readonly='readonly'>";
        output          += "<div class='input-group-btn'>";
        output          += "<input type='file' class='form-control upload_file berkas' name='lampiran_tabel"+indexTabel+"_calon1[]' style='display:none;'>";
        output          += "<button type='button' class='btn btn-default btn-flat btn_upload_file' data-title='Upload'><i class='fa fa-upload'></i></button>";
        output          += "</div>";
        output          += "<div class='input-group-btn'>";
        output          += "<button type='button' class='btn btn-default btn-flat view_file' data-link='' title='Lihat file'><i class='fa fa-search'></i></button>";
        output          += "</div>";
        output          += "</div>";
        output          += "</td>";
        output          += "<td colspan='2' class='lampiran-calon_tabel"+indexTabel+"'>";
        output          += "<div class='input-group' style='width: 100%;'>";
        output          += "<input type='text' class='form-control caption_file' name='caption_tabel"+indexTabel+"_calon2' required='required' readonly='readonly'>";
        output          += "<div class='input-group-btn'>";
        output          += "<input type='file' class='form-control upload_file berkas' name='lampiran_tabel"+indexTabel+"_calon2[]' style='display:none;'>";
        output          += "<button type='button' class='btn btn-default btn-flat btn_upload_file'  data-title='Upload'><i class='fa fa-upload'></i></button>";
        output          += "</div>";
        output          += "<div class='input-group-btn'>";
        output          += "<button type='button' class='btn btn-default btn-flat view_file' data-link='' title='Lihat file'><i class='fa fa-search'></i></button>";
        output          += "</div>";
        output          += "</div>";
        output          += "</td>";
        output          += "<td colspan='10' style='background:#d3d3d369;'></td>";
        output          += "</tr>";
        output          += "</tbody>";
        output          += "</table>";
        output          += "</div>";
        output          += "</div>";
        output          += "</div>";
        output          += "</div>";
        output          += "</div>";

        $(output).insertAfter(".boxAn" + count);

        var jenisBarang = $("input[name=radioJenis]:checked").val();
        if(jenisBarang == 'TB' || jenisBarang == 'STB'){
            $(".qty").val('1').trigger('keyup');
            $(".qty").prop("readonly", true);
        }

        $('.select-calon-pembeli_tabel'+indexTabel).select2();
        $(".metodeSelect").select2();

        var theSelect2Elements = null;
        $(".select_customer_tabel"+indexTabel).select2({
            allowClear: true,
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
    
                let markup = '<div class="clearfix">' + repo.NAME1 + ' - [' + repo.KUNNR + ']</div>';
                
                return markup;
            },
            templateSelection: function(repo) {
                
    
                let markup = "Pilih Kode Customer / Vendor";
                if (repo) {
                    
                    if(repo.id){
                        var name = $(theSelect2Elements).attr('name');
                        $("input[name='nama_"+name+"']").val(repo.NAME1);
                        $("input[name='nama_"+name+"']").attr("readonly", true);
                        markup = repo.NAME1 + ' - [' + repo.KUNNR + ']';
                    }
                    
                }
    
                return markup;
            }
        }).on('select2:open', function(e){ 
            theSelect2Elements = e.currentTarget; 
        });

        $(".select-customer").on("select2:unselect", function(e) {
            var name = $(this).attr('name');
            $("input[name='nama_"+name+"']").val("");
            $("input[name='nama_"+name+"']").attr("readonly", false);
        });

        $(".select-material").on("select2:unselect", function(e) {
            $(this).closest("tr").find('.deskripsi').val('');
            $(this).closest("tr").find('.deskripsi').html('');
            $(this).closest("tr").find('.uom').val('');
        });

        var theSelect2Element = null;
        $(".select_material_tabel"+indexTabel+"_row"+row).select2({
            allowClear: true,
            placeholder: {
                id: "",
                placeholder: "Leave blank to ...",
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
                    
                    if(repo.id){
                        $(theSelect2Element).closest("tr").find('.deskripsi').val(repo.group_description + ' - ' + repo.full_description);
                        $(theSelect2Element).closest("tr").find('.uom').val(repo.uom);
                        markup = '[ ' + repo.id + ' ] ' + repo.full_description;
                    }
                    
                }

                return markup;
            }
        }).on('select2:open', function(e){ 
            theSelect2Element = e.currentTarget; 
        });

		e.preventDefault();
		return false;

    });

    $(".del-ann").on("click", function (e) {
        var count = $(".boxAnalisa").length;
		if (count > 1) $(".boxAn" + count).remove();
    });

});


