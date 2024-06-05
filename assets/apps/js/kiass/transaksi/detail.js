$(document).ready(function () {
    
    $.ajax({
        url: baseURL + "kiass/transaksi/get/detail",
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
            // var header = data.header;
            // var analisa = data.analisa_harga;
            // var calon = data.calon_pembeli;
            
            var session = data.session_role;
            
            
            if(data.header) {

                var counter_tabel = '1';
                var status_pp, jenis_barang ;
                $.each(data.header, function(i,header){

                    status_pp = header.status;
                    jenis_barang = header.alias_flow; 
                    $("input[name='bukrs']").val(header.BUKRS);
                    $("input[name='tgl_pengajuan']").val(generateDateFormat(header.tanggal_pengajuan));
                    $("select[name='lokasi']").val(header.lokasi).trigger("change");
                    $("select[name='pembeli']").val(header.pembeli).trigger("change");
                    $("input[name='perihal']").val(header.perihal).css('textTransform', 'capitalize');
                    //$("input[name='nilai_penawaran']").val(header.perihal); ??
                    $("select[name='pic_ho']").val(header.pic_ho).trigger("change");
                    $("select[name='pic_pabrik']").val(header.pic_proj).trigger("change");

                    $("textarea[name='latar_belakang']").val(header.latar_belakang).css('textTransform', 'capitalize');


                    $("textarea[name='ket_satu_pembeli']").val(header.ket_satu_pembeli).css('textTransform', 'capitalize');
                    $("textarea[name='keterangan_spk']").val(header.keterangan_spk).css('textTransform', 'capitalize');
                    $("textarea[name='catatan_proc']").val(header.catatan_proc).css('textTransform', 'capitalize');

                    $('input[name=radioJenis][value='+header.alias_flow+']').attr('checked', true); 
                    $('input[name=radiospk][value='+header.is_spk+']').attr('checked', true);
                    
                    if(header.filename){
                        $("input[name='caption_lampiran']").closest('.input-group').find('.view_file').attr("data-link", header.filename);
                        $("input[name='caption_lampiran']").val(header.filename.split('/').pop());
                    }

                    if(header.filename_proc){
                        $("input[name='caption_lampiran_procurement']").closest('.input-group').find('.view_file').attr("data-link", header.filename_proc);
                        $("input[name='caption_lampiran_procurement']").val(header.filename_proc.split('/').pop());
                    }
                    
                    
                    counter_tabel = header.counter_analisa_harga;
                
                });
                
                

                //setup Table dan calon pemeneang
                //Generate Jumlah table
                for (let i = 1; i < (counter_tabel+1); i++) {
                    if (i > 1)
                        $(".add-ann").trigger('click');  
                }
                
                //Generate Row per table


                counter_tabels = 0;
                
                var flag_test_run_so = false;
                $.each(data.analisa_harga, function(i,v){
                    //inisialisasi 
                    var row = $("tr.input-table-row"+(v.no_tabel)).length;
                    
                    if(counter_tabel !== v.no_tabel){
                        // Gaush generate row, index pertama dari tabel

                        //generate calon pemenang per table
                        if( (v.counter_pemenang - 2) > 0 ){
                            for (let i = 1; i < (v.counter_pemenang - 1); i++) {
                                $(".boxAn" + v.no_tabel).find(".add-col-pembeli").trigger('click'); 
                            }
                        }


                        if($("textarea[name='ket_satu_pembeli']").val() !== "" && v.counter_pemenang < 2 ){
                            for (let i = 0; i < (2 - v.counter_pemenang); i++) {
                                $(".boxAn" + v.no_tabel).find(".del-col-pembeli").trigger('click'); 
                            }
                        }

                    }else{
                        // generate row
                        $(".boxAn" + v.no_tabel).find(".add-row").trigger('click'); 
                        row = $("tr.input-table-row"+(v.no_tabel)).length;
                    }
                    
                    if(v.test_so == null){
                        flag_test_run_so = true;
                    }
                    

                    // TAMBAHAN CR ASSET
                    if(v.kode_asset){
                        $("textarea[name='deskripsi_asset_tabel"+v.no_tabel+"_row"+row+"']").val(v.deskripsi_asset).css('textTransform', 'capitalize');
                        var nilai_nbv = (v.nilai_nbv > 0) ? v.nilai_nbv : parseFloat(v.nilai_nbv_temp*100).toFixed();
                        $("input[name='nbv_tabel"+v.no_tabel+"_row"+row+"']").val(numberWithCommas(parseFloat(nilai_nbv)));
                        $("input[name='cap_date_tabel"+v.no_tabel+"_row"+row+"']").val(generateDateFormat(v.cap_date));
                        $("input[name='kode_asset_tabel"+v.no_tabel+"_row"+row+"']").val(v.kode_asset);
                        $("input[name='sno_tabel"+v.no_tabel+"_row"+row+"']").val(v.sno);
                        
                    }

                    // insert data edit
                    // $("input[name='nbv_tabel"+v.no_tabel+"_row"+row+"']").val(v.nbv);
                    // $("input[name='tahun_beli_tabel"+v.no_tabel+"_row"+row+"']").val(v.yearbeli);
                    
                    $("textarea[name='deskripsi_tabel"+v.no_tabel+"_row"+row+"']").val(v.deskripsi).css('textTransform', 'capitalize');
                    $("textarea[name='rincian_tabel"+v.no_tabel+"_row"+row+"']").val(v.rincian).css('textTransform', 'capitalize');
                    $("input[name='satuan_tabel"+v.no_tabel+"_row"+row+"']").val(v.uom);
                    $("input[name='qty_tabel"+v.no_tabel+"_row"+row+"']").val(v.qty);

                    $("input[name='harga_terakhir_tabel"+v.no_tabel+"_row"+row+"']").val(numberWithCommas(parseInt(v.harga_terakhir)));
                    if(v.harga_varian !== null){
                        $("input[name='harga_varian_tabel"+v.no_tabel+"_row"+row+"']").val(numberWithCommas(parseFloat(v.harga_varian)));
                        $("input[name='total_varian_tabel"+v.no_tabel+"_row"+row+"']").val(numberWithCommas(parseFloat(v.total_varian)));
                    }
                    if(v.harga_nego !== null){
                        $("input[name='harga_nego_tabel"+v.no_tabel+"_row"+row+"']").val(numberWithCommas(parseFloat(v.harga_nego)));
                        $("input[name='total_harga_nego_tabel"+v.no_tabel+"_row"+row+"']").val(numberWithCommas(parseFloat(v.total_harga_nego)));
                    }

                    $("select[name='pembeli_tabel"+v.no_tabel+"_row"+row+"']").val(v.pemenang).trigger('change');
                    $("textarea[name='keterangan_fincon_tabel"+v.no_tabel+"_row"+row+"']").val(v.keterangan_fincon).css('textTransform', 'capitalize');
                    
                    // if (v.recom_fincon == 'y'){
                    //     $(".recom_fincon_tabel"+v.no_tabel+"_row"+row).attr('checked', true);
                    // }
                    
                    if(v.id_foto_kondisi){
                        $("input[name='caption_tabel"+v.no_tabel+"_row"+row+"']").val(v.filename.split('/').pop());
                        $("input[name='caption_tabel"+v.no_tabel+"_row"+row+"']").closest('.input-group').find('.view_file').attr("data-link", v.filename);
                    }
                    
                    $("select[name='kode_material_tabel"+v.no_tabel+"_row"+row+"']").append(new Option('[ ' + v.kode_material + ' ] ' + v.full_description, v.kode_material, true, true)).trigger("change.select2");
                    $("input[name='id_row_analisa_tabel"+v.no_tabel+"_row"+row+"']").val(v.id_row_analisa);
                    counter_tabel = v.no_tabel;
                
                });
                
                $.each(data.calon_pembeli, function(i,v){
                   
                    
                    $("input[name='nama_customer_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").val(v.nama_pembeli);
                    if(v.kode_customer){
                        $("select[name='customer_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").append(new Option(v.nama_pembeli + ' - [ ' + v.kode_customer + ' ]', v.kode_customer, true, true)).trigger("change.select2");
                        $("input[name='nama_customer_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").attr("readonly", true);
                    }
                    $("input[name='identitas_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").val(v.identitas)
                    $("input[name='hp_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").val(v.no_hp)
                    
                    $("select[name='metode_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").val(v.metode_pembayaran).trigger('change');
                    $("input[name='tod_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").val(v.durasi)
                    
                    $("input[name='harga_satuan_tabel"+v.no_tabel+"_row"+v.no_row+"_calon"+v.no_urut+"']").val(numberWithCommas(parseFloat(v.harga_satuan)));
                    $("input[name='id_calon_pembeli_tabel"+v.no_tabel+"_row"+v.no_row+"_calon"+v.no_urut+"']").val(v.id_calon_pembeli)


                    // var harga_total = (v.harga_satuan *  $("input[name='qty_tabel"+v.no_tabel+"_row"+v.no_row+"']").val())
                    $("input[name='harga_total_tabel"+v.no_tabel+"_row"+v.no_row+"_calon"+v.no_urut+"']").val(parseFloat(v.harga_total));
                    var total_sementara = $("input[name='total_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").val();
                    var total_harga_per_calon = parseFloat(total_sementara) + parseFloat(v.harga_total);
                    $("input[name='total_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").val(total_harga_per_calon);

                    if(v.id_lampiran_calon){
                        $("input[name='caption_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").val(v.filename.split('/').pop());
                        $("input[name='caption_tabel"+v.no_tabel+"_calon"+v.no_urut+"']").closest('.input-group').find('.view_file').attr("data-link", v.filename);
                    }

                });

                $(".total-harga-satuan").trigger('change');
                $(".form-pengajuan-penjualan input, .form-pengajuan-penjualan select, .form-pengajuan-penjualan textarea, .btn_upload_file").attr('disabled','disabled');
                
                // SPECIAL CASE Accounting
                // Tarik data Kode aset dari RFC SAP jika jenis barang (Tanah dan Bangunan) || (Selain Tanah dan Bangunan) diatur di Master Flow
                if(session.level == '6' && status_pp == '6' ){
                    $(".input-acc").attr("readonly", false);
                    $(".acc").removeAttr("disabled");
                    $(".acc").attr("required", true);
                    $(".sno").attr("required", false);
                    $(".nbv").attr("required", false);
                    $(".btn-input-acc").removeClass('hide');
                }

                //Special Case FINCON DEPT HEAD tipe AFFILIASI
                if(session.level == '5' && status_pp == '5' && $("select[name='pembeli']").val() !== "pihakKetiga"){  
                    $(".varian").removeAttr("disabled");
                    $(".varian-total").removeAttr("disabled");
                    $(".harga-satuan").removeAttr("disabled");
                    $(".total-harga-satuan").removeAttr("disabled");
                    $(".harga-nego").removeAttr("disabled");
                    $(".total_harga_nego").removeAttr("disabled");
                    $(".harga-nego").removeAttr("readonly");
                    $(".select-calon").removeAttr("disabled");
                    for (let i = 0; i < $(".tr-row").length; i++) {
                        var indexTabel = $(".tr-row").eq(i).data('tabel');
                        var indexRow = $(".tr-row").eq(i).data('row');
                        var pemenang = $("select[name='pembeli_tabel"+indexTabel+"_row"+indexRow+"']").val();
                        var indexPemenang = pemenang;
                        var harga_nego = $("input[name='harga_satuan_tabel"+indexTabel+"_row"+indexRow+"_calon"+indexPemenang+"']").val();

                        $("input[name='harga_nego_tabel"+indexTabel+"_row"+indexRow+"']").val(numberWithCommas(harga_nego)).trigger('keyup');
                        
                    }
                    
                }

                // SPECIAL CASE PROCUREMENT
                // ISI HARGA NEGO dan Tambah Alternatif (mandatory)
                if(session.level == '7' && status_pp == '7' ){ //Procurement HO
                    $(".procurement").removeAttr("disabled");
                    $(".procurement").removeAttr("readonly");
                    $(".procurement").attr("required", true);
                    $(".file-proc").attr("required", false);

                    $(".varian").removeAttr("disabled");
                    $(".varian-total").removeAttr("disabled");
                    $(".total_harga_nego").removeAttr("disabled");

                    $("textarea[name='catatan_proc']").attr("required", false);
                    $(".add-col-pembeli").removeClass('hide');
                    // $("button[name='btn_proc']").removeClass('hide');
                    // $(".btn_approve").hide();
                    // $('.flag_penilaian_jaminan :input[value="approve"]').hide();
                    for (let i = 0; i < $(".tr-row").length; i++) {
                        var indexTabel = $(".tr-row").eq(i).data('tabel');
                        var indexRow = $(".tr-row").eq(i).data('row');
                        var pemenang = $("select[name='pembeli_tabel"+indexTabel+"_row"+indexRow+"']").val();
                        var indexPemenang = pemenang;
                        var harga_nego = $("input[name='harga_satuan_tabel"+indexTabel+"_row"+indexRow+"_calon"+indexPemenang+"']").val();
                        
                        // console.log(harga_nego);
                        // console.log($("input[name='harga_nego_tabel"+indexTabel+"_row"+indexRow+"']").val());
                        if($("input[name='harga_nego_tabel"+indexTabel+"_row"+indexRow+"']").val() == "" || $("input[name='harga_nego_tabel"+indexTabel+"_row"+indexRow+"']").val() == null || $("input[name='harga_nego_tabel"+indexTabel+"_row"+indexRow+"']").val() == '0'){
                            $("input[name='harga_nego_tabel"+indexTabel+"_row"+indexRow+"']").val(numberWithCommas(harga_nego)).trigger('keyup');
                        }
                        
                    }
                }


            }
            
            generate_nilai_penawaran();
            var nilai_penawaran = $("input[name='nilai_penawaran']").val();
            if(parseFloat(nilai_penawaran) > 0){
                $(".nilai_penawaran").show();
            }else{
                $(".nilai_penawaran").hide();
            }
			
			
        },
        complete: function () {
            $("body .overlay-wrapper .overlay").remove();

        }
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

    var theSelect2Elementt = null;
    $(".select-kunnr").select2({
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
            if (repo.id) {
                if(repo.text){        
                    markup = repo.text;
                }else{
                    markup = repo.NAME1 + ' - [' + repo.KUNNR + ']';
                }
            }

            return markup;
        }
    }).on('select2:open', function(e){ 
        theSelect2Elementt = e.currentTarget; 
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
            if (repo.id) {
                
                if(repo.text){     
                    markup = repo.text;
                }else{
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

    $(document).on("click", "#accounting_kode", function () {
        var no_pp = $("input[name='no_pp']").val();
        $('#kunnr_content').html('');
        
        $.ajax({
			url: baseURL + "kiass/transaksi/get/kunnr",
			type: 'POST',
			dataType: 'JSON',
			data: {
				no_pp: no_pp
			},
			success: function (data) {
				if (data) {
                    var tempTabel = '1';
                    var tempArray = [];
                    var temp = 1;

					$.each(data, function (i, v) {
                        
                        // cek tabel sama ga, if beda reset arr

                        if(tempTabel !== v.no_tabel){
                            tempArray = [];
                            tempTabel = v.no_tabel;
                            temp = $(".trKunnr").length + 1;

                        }
                        
                        if(tempArray.includes(v.nama_pembeli) !== true){
                            var count = $(".trKunnr").length;
                            $(".add-kunnr").trigger('click');                 
                            $("input[name='kode_id"+(count+1)+"']").val(v.id_row_analisa);
                            $("input[name='kode_tabel"+(count+1)+"']").val(v.no_tabel);
                            $("input[name='caption_kode_tabel"+(count+1)+"']").val('Analisa Harga '+v.no_tabel);
                            $("input[name='kode_baris"+(count+1)+"']").val('Baris '+v.no_row);
                            $("input[name='kode_nama"+(count+1)+"']").val(v.nama_pembeli);
                            tempArray.push(v.nama_pembeli);
    
                            if(v.kode_customer !== " "){
                                $("select[name='kunnr"+(count+1)+"']").append(new Option(v.nama_pembeli + ' - [ ' + v.kode_customer + ' ]', v.kode_customer, true, true)).trigger("change.select2");
                                $("select[name='kunnr"+(count+1)+"']").attr('disabled','disabled');
                            }
    
                            $("input[name='counter_kode']").val((count+1));
                        }else{
                            var arrIndex = (tempArray.indexOf(v.nama_pembeli));
                            var tempBaris = $("input[name='kode_baris"+(arrIndex+temp)+"']").val();
                            $("input[name='kode_baris"+(arrIndex+1)+"']").val(tempBaris+', Baris '+v.no_row);
                        }
					});
					$('#kunnr_modal').modal('show');
				}else{
                    kiranaAlert("notOK", "Data tidak ditemukan", "warning", "no");
                }
			}
        });
        
    });
    
    $(document).on("click", "button[name='submit-form-kunnr']", function (e) {
        $(".form-kode-accounting select").removeAttr("disabled");
        var no_pp = $("input[name='no_pp']").val();
        $("input[name='no_pp_kode']").val(no_pp);
        var empty_form = validate(".form-kode-accounting");
		if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                
                var formData = new FormData($(".form-kode-accounting")[0]);

                $.ajax({
                    url: baseURL + 'kiass/transaksi/save/kunnr',
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

    $(document).on('click', '.panel-heading', function(e){
        var $this = $(this);
        if(!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });

    $(document).on("click", ".btn-input-acc", function() {
        var row = $(this).closest('tr').data('row');
        var indexTabel = $(this).closest('tr').data('tabel');
        var kode_asset = $("input[name='kode_asset_tabel"+indexTabel+"_row"+row+"']").val();
        var sno = $("input[name='sno_tabel"+indexTabel+"_row"+row+"']").val();
        // console.log(kode_asset +'---'+sno);
        if (kode_asset !== "" && sno !== "") {
            // LOGIC RFC NBV
            $.ajax({
                url: baseURL + 'kiass/transaksi/get/nbv',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    kode_asset: kode_asset,
                    sno: sno,
                    // plant: $("input[name='no_pp']").val().split("/")[2],
                    plant: 'NSI1',
                    bukrs: $("input[name='bukrs']").val(),
                },
                success: function (datas) {

                    if (datas) {
                        var AKTIV  = datas.AKTIV;
                        // var cap_date = new Date(AKTIV.substring(0,4), AKTIV.substring(4,6)-1, AKTIV.substring(6,8));
                        $("input[name='cap_date_tabel"+indexTabel+"_row"+row+"']").val(generateDateFormat(AKTIV));
                        $("textarea[name='deskripsi_asset_tabel"+indexTabel+"_row"+row+"']").val(datas.TXT50).css('textTransform', 'capitalize');
                        $("input[name='nbv_tabel"+indexTabel+"_row"+row+"']").val(numberWithCommas(parseFloat(datas.BUCHWERT*100).toFixed()));
                        $("input[name='isproses']").val(0);
                    } else {
                        kiranaAlert('NotOK', 'Gagal menemukan Detil Asset.', "error", "no");
                        $("input[name='isproses']").val(0);
                    }
                },
                complete: function () {
                    $("input[name='isproses']").val(0);
                }
            });
		} else {
			kiranaAlert("notOK", "Mohon isi kode asset dan sub number terlebih dahulu", "warning", "no");
		}
    });

    $(document).on("click", "button[name='submit-form-approval-scrap']", function (e) {
        // $(".form-pengajuan-penjualan input, .form-pengajuan-penjualan select, .form-pengajuan-penjualan textarea, .btn_upload_file").removeAttr("disabled");
        
        var empty_form = validate(".form-approval-scrap");
		if (empty_form == 0) {
            
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                $(".id_row").removeAttr("disabled");
                $(".id_calon").removeAttr("disabled");
                $(".nbv").removeAttr("disabled");
                
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
                
                var formData = new FormData($(".form-pengajuan-penjualan")[0]);
                formData.append('komentar', $('textarea[name="komentar"]', '.form-approval-scrap').val());
                formData.append('id_flow', $('input[name="id_flow"]', '.form-approval-scrap').val());
                formData.append('action', $('input[name="action"]', '.form-approval-scrap').val());
                formData.append('no_pp', $('input[name="no_pp"]', '.form-approval-scrap').val());


                $.ajax({
                    url: baseURL + 'kiass/transaksi/save/approval',
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

    
    
    
    $(document).on("keyup", ".harga-nego", function (e) {
        
        var row = $(this).closest('tr').data('row');
        var indexTabel = $(this).closest('tr').data('tabel');
        
        var harga_nego = $(this).val();
        var qty = $("input[name='qty_tabel"+indexTabel+"_row"+row+"']").val();

        var hasil = parseFloat(harga_nego.replace(/,/g, '')) * qty;
        
        var harga_terakhir = $(this).closest('tr').find('.harga-terakhir').val();
        var varian = Math.abs(parseFloat(harga_terakhir.replace(/,/g, '')) - parseFloat(harga_nego.replace(/,/g, '')));
        var varian_total =  varian * qty;

        $(this).closest('tr').find('.total_harga_nego').val(numberWithCommas(hasil));
        
        $(this).closest('tr').find('.varian').val(numberWithCommas(varian));
        $(this).closest('tr').find('.varian-total').val(numberWithCommas(varian_total));

        
              
		
    });


    $(document).on("click", ".lihat-file", function () {
		var link = $(this).closest(".input-group-btn").find(".data-lihat-file").val();
		var ext = (link !== "" ? link.split('.').pop() : null);

		$('#KiranaModals .modal-title').html($(this).data("title"));

		var output = '';
		switch (ext) {
			case 'pdf' :
				output += showPdf(link);
				break;
			case 'png' :
			case 'jpg' :
				output += '<img class="img-responsive" style="margin: 0 auto;" alt="Photo" src="' + baseURL + link + '">';
				break;
		}

		if (output == "") {
			kiranaAlert("notOK", "File tidak ditemukan", "error", "no");
		} else {
			$('#KiranaModals .modal-body').html(output);

			$('#KiranaModals').modal({
				backdrop: 'static',
				keyboard: true,
				show: true
			});

			setTimeout(function(){

			},100);
		}
    });
    
    $(document).on("click", "#log_status", function() {
		$.ajax({
			url: baseURL + "kiass/transaksi/get/log-status",
			type: 'POST',
			dataType: 'JSON',
			data: {
				no_pp: $("input[name='no_pp']").val()
			},
			beforeSend: function() {

                $("#KiranaModals").removeAttr("class");
	            $("#KiranaModals").addClass("modal");

				$('#KiranaModals .modal-title').html("Log Status Pengajuan");
        		$("#KiranaModals .modal-dialog").addClass("modal-lg");

				var elements = '<table class="table table-bordered table-modals">';
				elements += '	<thead>';
				elements += '		<th>No Pengajuan Penjualan</th>';
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
							v.no_pp,
							generateDatetimeFormat(v.format_tanggal_status),
							"<span style='text-transform: capitalize'>" + v.action + "</span> oleh <br> <span class='label label-info'>" + v.nama_role + " : " + v.nama + "</label>",
							v.comment
						]).draw(false);
                    });
                    
                    
				}
			},
			complete: function() {
                $('#KiranaModals').modal('show');
                setTimeout(function() {
                    adjustDatatableWidth();
                }, 1000);
			}
		});
	});


    $(document).on("click", "button[name='action_btn']", function (e) {
		
        if($(this).val() !== 'approve'){
            $('#KiranaModals .modal-title').css("text-transform", "capitalize");
                $('#KiranaModals .modal-title').html($(this).val() + " Pengajuan Penjualan");
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
                output += '<form class="form-approval-scrap" enctype="multipart/form-data">';
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
                output += '			<input type="hidden" name="no_pp" value="' + no_pp + '">';
                output += '			<input type="hidden" name="id_flow" value="' + id_flow + '">';
                output += '			<button type="button" class="btn btn-primary" name="submit-form-approval-scrap">Submit</button>';
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
        }else{

            var empty_form = validate(".form-pengajuan-penjualan");
            if (empty_form == 0) {
                $('#KiranaModals .modal-title').css("text-transform", "capitalize");
                $('#KiranaModals .modal-title').html($(this).val() + " Pengajuan Penjualan");
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
                output += '<form class="form-approval-scrap" enctype="multipart/form-data">';
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
                output += '			<input type="hidden" name="no_pp" value="' + no_pp + '">';
                output += '			<input type="hidden" name="id_flow" value="' + id_flow + '">';
                output += '			<button type="button" class="btn btn-primary" name="submit-form-approval-scrap">Submit</button>';
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
        }
        
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
            output_harga_calon          += "<input type='hidden' class='id_calon' name='id_calon_pembeli_tabel"+indexTabel+"_row"+i+"_calon"+(count +1)+"'>";
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
        output_nama_customer     += "<input name='nama_customer_tabel"+indexTabel+"_calon"+(count +1)+"' class='form-control' placeholder='Nama Pembeli'>";
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
        output_nilai_total          += "<input type='text' name='nilai_total_tabel"+indexTabel+"_calon"+(count +1)+"' class='text-right form-control col-sm-12 angka' value='0' readonly required='required'>";
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
                if (repo.id) {
                    
                    if(repo.text){     
                        markup = repo.text;
                    }else{
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

        var dvalue = 3;
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

    $(document).on("click", ".view_file", function () {
		if ($(this).data("link") !== "") {
			window.open(baseURL + $(this).data("link"), '_blank');
		} else {
			kiranaAlert("notOK", "File Tidak Ditemukan", "warning", "no");
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
            output_harga_calon          += "<input type='hidden' class='id_calon' name='id_calon_pembeli_tabel"+indexTabel+"_row"+row+"_calon"+i+"'>";
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

		var output       = "<tr class='input-table-row"+(indexTabel)+" row"+row+" tr-row' data-row='"+row+"' data-tabel='"+(indexTabel)+"'>";
		output          += "    <td class='text-center'><span class='form-control'>"+row+"</span></td>";
		output          += "    <td class='text-center mw200'><select name='kode_material_tabel"+indexTabel+"_row"+row+"' class='form-control select-material autocomplete' required data-allowclear='true'>";
        output          += "    <input type='hidden' class='id_row' name='id_row_analisa_tabel"+indexTabel+"_row"+row+"'></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='deskripsi_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12 deskripsi' readonly required='required'></textarea>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='rincian_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12' required='required' ></textarea>";
        output          += "    </td>";
        output          += "    <td class='text-center mw100'>";
        output          += "        <input name='satuan_tabel"+indexTabel+"_row"+row+"' class='form-control text-center col-sm-12 uom' readonly>";
        output          += "    </td>";
        
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <input type='text' name='kode_asset_tabel"+indexTabel+"_row"+row+"' class='form-control mw110 input-acc acc' readonly>";
        output          += "            <div class='input-group-addon'>-</div>";
        output          += "            <input type='text' name='sno_tabel"+indexTabel+"_row"+row+"' class='form-control mw40 input-acc acc sno' readonly>";
        output          += "            <div class='btn input-group-addon btn-input-acc hide'><i class='fa fa-download'></i></div>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='deskripsi_asset_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12 acc' readonly ></textarea>";
        output          += "    </td>";
		output          += "    <td class='text-center mw150'><input type='text' class='text-center form-control col-sm-12 acc' name='cap_date_tabel"+indexTabel+"_row"+row+"' readonly></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' class='text-right form-control col-sm-12 angka nbv acc' readonly name='nbv_tabel"+indexTabel+"_row"+row+"'>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw100'><input type='text' name='qty_tabel"+indexTabel+"_row"+row+"' value='0' class='text-center qty form-control col-sm-12 angka'></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_terakhir_tabel"+indexTabel+"_row"+row+"' value='0' class='text-right harga-terakhir form-control col-sm-12 angka' required='required'>";
        output          += "        </div>";
        output          += "    </td>";
        output          += output_harga_calon;
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_nego_tabel"+indexTabel+"_row"+row+"' class='text-right procurement harga-nego form-control col-sm-12 angka' value='0' readonly>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='total_harga_nego_tabel"+indexTabel+"_row"+row+"' class='text-right total_harga_nego form-control col-sm-12 angka' value='0' readonly>";
        output          += "        </div>";
		output          += "    </td>";

        output          += "    <td class='text-center mw200'>";
        output          += "<select class='form-control readonly select2 procurement select-calon select-calon-pembeli_tabel"+indexTabel+"'";
        output          += "name='pembeli_tabel"+indexTabel+"_row"+row+"'";
        output          += "style='width: 100%;'";
        output          += "readonly>";
        output          += option_calon_pembeli;
        output          += "</select>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_varian_tabel"+indexTabel+"_row"+row+"' class='text-right varian form-control col-sm-12' readonly >";
        output          += "        </div>";
        output          += "    </td>"; 
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='total_varian_tabel"+indexTabel+"_row"+row+"' class='text-right varian-total form-control col-sm-12 angka' value='0' readonly >";
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
        
        $("select[name='satuan[]'].select2").select2();
        $('.select-calon-pembeli_tabel'+indexTabel).select2();
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

	});

	$(document).on("click", ".del-row", function (e) {
        var indexTabel = $(this).val();
        var count = $("tr.input-table-row"+indexTabel).length;
		if (count > 1) $("tr.input-table-row"+indexTabel).last().remove();
    });
    
    $(document).on("click", ".add-kunnr", function (e) {
        var count = $(".trKunnr").length;

        var output = ' <tr class="trKunnr">';
            output += '     <td>';
            output += '         <input type="hidden" name="kode_id'+(count+1)+'">';
            output += '         <input type="hidden" name="kode_tabel'+(count+1)+'">';
            output += '         <input type="text" class="form-control" name="caption_kode_tabel'+(count+1)+'" readonly>';
            output += '     </td>';
            output += '     <td>';
            output += '         <input type="text" class="form-control kode_baris" name="kode_baris'+(count+1)+'" readonly>';
            output += '     </td>';
            output += '     <td>';
            output += '         <input type="text" class="form-control kode_nama" name="kode_nama'+(count+1)+'" required readonly>';
            output += '     </td>';
            output += '     <td>';
            output += '         <select name="kunnr'+(count+1)+'" class="form-control select-kunnr select-kunnr'+(count+1)+' autocomplete" data-allowclear="true" required><option></option></select>';
            output += '     </td>';
            output += '     </td>';
            output += ' </tr>';

        if(count == 0){
            $('#kunnr_content').html(output);
        }else{
            $("#kunnr_content").append(output);
        }


        $('.select-kunnr'+(count+1)).select2();
        $(".select-kunnr").select2({
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
                if (repo.id) {
                    
                    if(repo.text){
                            
                        markup = repo.text;
                    }else{
                        markup = repo.NAME1 + ' - [' + repo.KUNNR + ']';
                    }
                    
                }
    
                return markup;
            }
        });



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
            output_harga_calon          += "<input type='text' name='harga_satuan_tabel"+indexTabel+"_row1_calon"+i+"' class='text-right form-control harga-satuan harga_satuan_tabel"+indexTabel+"_row1 col-sm-12 angka' required='required'>";
            output_harga_calon          += "<input type='hidden' class='id_calon' name='id_calon_pembeli_tabel"+indexTabel+"_row1_calon"+i+"'>";   
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

        var output       = "<div class='box boxAnalisa boxAn"+(count+1)+" collapsed-box' style='border: 1px solid black;'>";
        output          += "<div class='box-header' style='border-bottom: 1px solid black;'>";
        output          += "    <h3 class='box-title' style='font-size:15px;'>Analisa Harga "+(count+1)+"</h3>";
        output          += "    <div class='box-tools pull-right'>";
        output          += "        <button type='button' class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-plus'></i>";
        output          += "        </button>";
        output          += "    </div>";
        output          += "</div>";

        output          += "<div class='box-body'>";
        output          += "<div class='row'>";
        output          += "<div class='col-sm-12'>";
        output          += "<div class='form-group'>";
        output          += "<button type='button' value='"+(count+1)+"' class='btn btn-default btn-sm hide add-row'>Tambah baris tabel</button>";
        output          += "<button type='button' value='"+(count+1)+"' class='btn btn-default btn-sm hide del-row'>Hapus baris tabel</button>";
        output          += "<button type='button' value='"+(count+1)+"' class='btn btn-default btn-sm hide add-col-pembeli'>Tambah kolom alternatif</button>";
        output          += "<button type='button' value='"+(count+1)+"' class='btn btn-default btn-sm hide del-col-pembeli'>Hapus kolom alternatif</button>";
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
        output          += "<th class='text-center'>Sesuai Template</th>											";
        output          += "</tr>";
        output          += "</thead>";
        output          += "<tbody>";
        output          += "<tr class='input-table-row"+(indexTabel)+" row"+row+" tr-row' data-row='"+row+"' data-tabel='"+(indexTabel)+"'>";
		output          += "    <td class='text-center'><span class='form-control'>"+row+"</span></td>";
        output          += "    <td class='text-center mw200'><select name='kode_material_tabel"+indexTabel+"_row"+row+"' class='form-control select-material autocomplete' required data-allowclear='true'>";
        output          += "    <input type='hidden' class='id_row' name='id_row_analisa_tabel"+indexTabel+"_row"+row+"'></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='deskripsi_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12 deskripsi' readonly required='required'></textarea>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='rincian_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12' required='required' ></textarea>";
        output          += "    </td>";
        output          += "    <td class='text-center mw100'>";
        output          += "        <input name='satuan_tabel"+indexTabel+"_row"+row+"' class='form-control text-center col-sm-12 uom' readonly>";
		output          += "    </td>";
		output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <input type='text' class='form-control mw110 input-acc acc' name='kode_asset_tabel"+indexTabel+"_row"+row+"' readonly>";
        output          += "            <div class='input-group-addon'>-</div>";
        output          += "            <input type='text' class='form-control mw40 input-acc acc sno' name='sno_tabel"+indexTabel+"_row"+row+"' readonly>";
        output          += "            <div class='btn input-group-addon btn-input-acc hide'><i class='fa fa-download'></i></div>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <textarea name='deskripsi_asset_tabel"+indexTabel+"_row"+row+"' class='form-control col-sm-12 acc' readonly></textarea>";
        output          += "    </td>";
		output          += "    <td class='text-center mw150'><input type='text' class='text-center form-control col-sm-12 acc' name='cap_date_tabel"+indexTabel+"_row"+row+"' readonly></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' class='text-right form-control col-sm-12 nbv acc' readonly name='nbv_tabel"+indexTabel+"_row"+row+"'>";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw100'><input type='text' name='qty_tabel"+indexTabel+"_row"+row+"' value='0' class='text-center form-control qty col-sm-12 angka'></td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_terakhir_tabel"+indexTabel+"_row"+row+"' value='0' class='text-right harga-terakhir form-control col-sm-12 angka' required='required'>";
        output          += "        </div>";
        output          += "    </td>";
        output          += output_harga_calon;
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_nego_tabel"+indexTabel+"_row"+row+"' readonly value='0' class='text-right procurement harga-nego form-control col-sm-12 angka' >";
        output          += "        </div>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='total_harga_nego_tabel"+indexTabel+"_row"+row+"' readonly value='0' class='text-right form-control total_harga_nego col-sm-12 angka' >";
        output          += "        </div>";
		output          += "    </td>";

        output          += "    <td class='text-center mw200'>";
        output          += "<select class='form-control select2 procurement select-calon select-calon-pembeli_tabel"+indexTabel+"'";
        output          += "name='pembeli_tabel"+indexTabel+"_row"+row+"'";
        output          += "style='width: 100%;'";
        output          += "readonly>";
        output          += option_calon_pembeli;
        output          += "</select>";
        output          += "    </td>";
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='harga_varian_tabel"+indexTabel+"_row"+row+"' readonly class='text-right varian form-control col-sm-12' >";
        output          += "        </div>";
        output          += "    </td>"; 
        output          += "    <td class='text-center mw200'>";
        output          += "        <div class='input-group'>";
        output          += "            <div class='input-group-addon'>Rp</div>";
        output          += "            <input type='text' name='total_varian_tabel"+indexTabel+"_row"+row+"' readonly value='0' class='text-right varian-total form-control col-sm-12 angka' >";
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
        output          += "<td colspan='10' style='background:#d3d3d369;'></td>";
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
        output          += "<td colspan='12' style='background:#d3d3d369;'></td>";
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
                if (repo.id) {
                    
                    if(repo.text){     
                        markup = repo.text;
                    }else{
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

		e.preventDefault();
		return false;

    });

    $(".del-ann").on("click", function (e) {
        var count = $(".boxAnalisa").length;
		if (count > 1) $(".boxAn" + count).remove();
    });


	
});

function generate_nilai_penawaran(){
    var thn = $(".total_harga_nego");
    var nilai_penawaran = 0;
    for (let i = 0; i < thn.length; i++) {
        var total_hn = thn.eq(i).val();
        total_hn = total_hn !== "" ? total_hn.replace(/,/g, '') : 0;
        nilai_penawaran = parseFloat(total_hn) + parseFloat(nilai_penawaran);
    }
    $("input[name='nilai_penawaran']").val(numberWithCommas(nilai_penawaran));
}


