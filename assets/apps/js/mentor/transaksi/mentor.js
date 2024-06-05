$(document).ready(function() {
	$('.tanggal').datepicker({
        format: 'yyyy-mm-dd',
	    autoclose: true
    });

    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
        if (oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        }
    };

    datatables_ssp();

    //=======FILTER=======//
    $(document).on("change", "#filter_status", function() {
        datatables_ssp();
    });
	
	//auto complete nik_mentor_dmc1
	$("select[name='nik_mentor_dmc1']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'mentor/transaksi/get/user_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
					jenis		: 'mentor',
                    q			: params.term, // search term
                    page		: params.page
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
			var markup = '<div class="clearfix">'+ repo.nama+' - ['+repo.nik + ']</div>';
			return markup;
		},
      	templateSelection: function(repo){ 
			if(repo.posst) $("input[name='caption']").val(repo.posst);
			if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
			else return repo.nama;
		}
		   
    });

    $("#nik_mentor_dmc1").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });	
	
	//auto complete nik_mentor_dmc2
	$("select[name='nik_mentor_dmc2']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'mentor/transaksi/get/user_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
					jenis		: 'mentor',
                    q			: params.term, // search term
                    page		: params.page
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
			var markup = '<div class="clearfix">'+ repo.nama+' - ['+repo.nik + ']</div>';
			return markup;
		},
      	templateSelection: function(repo){ 
			if(repo.posst) $("input[name='caption']").val(repo.posst);
			if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
			else return repo.nama;
		}
		   
    });

    $("#nik_mentor_dmc2").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });	
	
	//auto complete nik_mentor_dmc3
	$("select[name='nik_mentor_dmc3']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            url: baseURL+'mentor/transaksi/get/user_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
					jenis		: 'mentor',
                    q			: params.term, // search term
                    page		: params.page
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
			var markup = '<div class="clearfix">'+ repo.nama+' - ['+repo.nik + ']</div>';
			return markup;
		},
      	templateSelection: function(repo){ 
			if(repo.posst) $("input[name='caption']").val(repo.posst);
			if(repo.nama && repo.nik) return repo.nama+' - ['+repo.nik+']';
			else return repo.nama;
		}
		   
    });

    $("#nik_mentor_dmc3").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });	
	
    //detail
    $(document).on("click", ".detail", function() {
        resetForm_use($('.form-input-detail'), 'edit');
        var nomor 	= $(this).data("nomor");
        var act 	= $(this).data("act");
        var nik_mentor_dmc1 	= $(this).data("nik_mentor_dmc1");
        var nik_mentor_dmc2 	= $(this).data("nik_mentor_dmc2");
        var nik_mentor_dmc3 	= $(this).data("nik_mentor_dmc3");
		
        $.ajax({
            url: baseURL + 'mentor/transaksi/get/mentor',
            type: 'POST',
            dataType: 'JSON',
            data: {
                nomor: nomor
            },
            success: function(data) {
                $.each(data, function(i, v) {
					$("input[name='act']").val(act);
					$("input[name='nomor']").val(v.nomor);
					// // $("input[name='nik_mentor_dmc1']").val(v.nik_mentor);
					// // $("input[name='nik_mentor_dmc2']").val(v.nik_mentor);
					// // $("input[name='nik_mentor_dmc3']").val(v.nik_mentor);
					$("input[name='nik_mentor']").val(v.nik_mentor);
					$("input[name='nik_mentor_dmc1']").val(v.nik_mentor_dmc1);
					$("input[name='nik_mentor_dmc2']").val(v.nik_mentor_dmc2);
					$("input[name='nik_mentor_dmc3']").val(v.nik_mentor_dmc3);
					$("input[name='nomor_mentoring']").val(v.nomor_mentoring);
					$("input[name='nama_mentor']").val(v.nama_mentor);
					$("input[name='nama_jabatan_mentee']").val(v.nama_jabatan_mentee);
					$("input[name='nama_departemen_mentee']").val(v.nama_departemen_mentee);
					$("input[name='telepon_mentee']").val(v.telepon_mentee);
					$("input[name='nik_mentee']").val(v.nama_mentee+' - ['+v.nik_mentee+']');
					$("input[name='tanggal_sesi1_rencana']").val(v.tanggal_sesi1_rencana_format);
					$("input[name='tanggal_sesi2_rencana']").val(v.tanggal_sesi2_rencana_format);
					$("input[name='tanggal_dmc1_rencana']").val(v.tanggal_dmc1_rencana_format);
					$("input[name='tanggal_dmc2_rencana']").val(v.tanggal_dmc2_rencana_format);
					$("input[name='tanggal_dmc3_rencana']").val(v.tanggal_dmc3_rencana_format);
					//buat auto nik_mentor_dmc1
					if(v.nik_mentor_dmc1!=null){
						var control = $('#nik_mentor_dmc1').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = v.nama_mentor_dmc1+' - ['+v.nik_mentor_dmc1+']';
						adapter.addOptions(adapter.convertToOptions([{"id":v.nik_mentor_dmc1,"nama":nama}]));
						$('#nik_mentor_dmc1').trigger('change');					
					}
					//buat auto nik_mentor_dmc2
					if(v.nik_mentor_dmc2!=null){
						var control = $('#nik_mentor_dmc2').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = v.nama_mentor_dmc2+' - ['+v.nik_mentor_dmc2+']';
						adapter.addOptions(adapter.convertToOptions([{"id":v.nik_mentor_dmc2,"nama":nama}]));
						$('#nik_mentor_dmc2').trigger('change');					
					}
					//buat auto nik_mentor_dmc3
					if(v.nik_mentor_dmc3!=null){
						var control = $('#nik_mentor_dmc3').empty().data('select2');
						var adapter = control.dataAdapter;
						var nama = v.nama_mentor_dmc3+' - ['+v.nik_mentor_dmc3+']';
						adapter.addOptions(adapter.convertToOptions([{"id":v.nik_mentor_dmc3,"nama":nama}]));
						$('#nik_mentor_dmc3').trigger('change');					
					}
					$("textarea[name='sasaran_pengembangan_dmc1']").val(v.sasaran_pengembangan_dmc1);
					$("textarea[name='kriteria_keberhasilan_dmc1']").val(v.kriteria_keberhasilan_dmc1);
					$("textarea[name='sasaran_pengembangan_dmc2']").val(v.sasaran_pengembangan_dmc2);
					$("textarea[name='kriteria_keberhasilan_dmc2']").val(v.kriteria_keberhasilan_dmc2);
					$("textarea[name='sasaran_pengembangan_dmc3']").val(v.sasaran_pengembangan_dmc3);
					$("textarea[name='kriteria_keberhasilan_dmc3']").val(v.kriteria_keberhasilan_dmc3);
					
					if(v.nama_mentor_dmc1!=null)
						$(".modal-title_dmc1").html("Mentee Rating DMC 1 ("+v.nama_mentor_dmc1+") - Additional Mentor");
					if(v.nama_mentor_dmc2!=null)
						$(".modal-title_dmc2").html("Mentee Rating DMC 2 ("+v.nama_mentor_dmc2+") - Additional Mentor");
					if(v.nama_mentor_dmc3!=null)
						$(".modal-title_dmc3").html("Mentee Rating DMC 3 ("+v.nama_mentor_dmc3+") - Additional Mentor");
					
					
					//detail-pertanyaan
					if (v.arr_data_feedback) {
						let no = 0;
						$("#nodata_feedback_dmc1").remove();
						$("#nodata_feedback_dmc2").remove();
						$("#nodata_feedback_dmc3").remove();
						$.each(v.arr_data_feedback, function(a, b){
							//dmc1
							let output_dmc1 	  = "";
							let ck_jawaban_dmc1_1 = (b.jawaban_dmc1==1)?"checked":""; 
							let ck_jawaban_dmc1_2 = (b.jawaban_dmc1==2)?"checked":""; 
							let ck_jawaban_dmc1_3 = (b.jawaban_dmc1==3)?"checked":""; 
							let ck_jawaban_dmc1_4 = (b.jawaban_dmc1==4)?"checked":""; 
							let ck_jawaban_dmc1_5 = (b.jawaban_dmc1==5)?"checked":""; 
							output_dmc1 += "<tr class='row-feedback_dmc1 feedback_dmc1" + b.id_feedback + "'>";
							output_dmc1 += "	 <td>";
							output_dmc1 += "		<input type='hidden' class='form-control' name='id_feedback_dmc1[]' value='"+b.id_feedback+"'/>";
							output_dmc1 += "	 	"+b.pertanyaan+"";
							output_dmc1 += "	 </td>";
							output_dmc1 += "	 <td><input "+ck_jawaban_dmc1_1+" type='radio' class='form-control-radio' name='feedback_dmc1_"+b.id_feedback+"' value='1' required='required'></td>";
							output_dmc1 += "	 <td><input "+ck_jawaban_dmc1_2+"  type='radio' class='form-control-radio' name='feedback_dmc1_"+b.id_feedback+"' value='2' required='required'></td>";
							output_dmc1 += "	 <td><input "+ck_jawaban_dmc1_3+"  type='radio' class='form-control-radio' name='feedback_dmc1_"+b.id_feedback+"' value='3' required='required'></td>";
							output_dmc1 += "	 <td><input "+ck_jawaban_dmc1_4+"  type='radio' class='form-control-radio' name='feedback_dmc1_"+b.id_feedback+"' value='4' required='required'></td>";
							output_dmc1 += "	 <td><input "+ck_jawaban_dmc1_5+"  type='radio' class='form-control-radio' name='feedback_dmc1_"+b.id_feedback+"' value='5' required='required'></td>";
							output_dmc1 += "</tr>";
							$(output_dmc1).appendTo(".table-feedback_dmc1 tbody");
							//dmc2
							let output_dmc2 = "";
							let ck_jawaban_dmc2_1 = (b.jawaban_dmc2==1)?"checked":""; 
							let ck_jawaban_dmc2_2 = (b.jawaban_dmc2==2)?"checked":""; 
							let ck_jawaban_dmc2_3 = (b.jawaban_dmc2==3)?"checked":""; 
							let ck_jawaban_dmc2_4 = (b.jawaban_dmc2==4)?"checked":""; 
							let ck_jawaban_dmc2_5 = (b.jawaban_dmc2==5)?"checked":""; 
							output_dmc2 += "<tr class='row-feedback_dmc2 feedback_dmc2" + b.id_feedback + "'>";
							output_dmc2 += "	 <td>";
							output_dmc2 += "		<input type='hidden' class='form-control' name='id_feedback_dmc2[]' value='"+b.id_feedback+"'/>";
							output_dmc2 += "	 	"+b.pertanyaan+"";
							output_dmc2 += "	 </td>";
							output_dmc2 += "	 <td><input "+ck_jawaban_dmc2_1+"  type='radio' class='form-control-radio' name='feedback_dmc2_"+b.id_feedback+"' value='1' required='required'></td>";
							output_dmc2 += "	 <td><input "+ck_jawaban_dmc2_2+" type='radio' class='form-control-radio' name='feedback_dmc2_"+b.id_feedback+"' value='2' required='required'></td>";
							output_dmc2 += "	 <td><input "+ck_jawaban_dmc2_3+" type='radio' class='form-control-radio' name='feedback_dmc2_"+b.id_feedback+"' value='3' required='required'></td>";
							output_dmc2 += "	 <td><input "+ck_jawaban_dmc2_4+" type='radio' class='form-control-radio' name='feedback_dmc2_"+b.id_feedback+"' value='4' required='required'></td>";
							output_dmc2 += "	 <td><input "+ck_jawaban_dmc2_5+" type='radio' class='form-control-radio' name='feedback_dmc2_"+b.id_feedback+"' value='5' required='required'></td>";
							output_dmc2 += "</tr>";
							$(output_dmc2).appendTo(".table-feedback_dmc2 tbody");
							//dmc3
							let output_dmc3 = "";
							let ck_jawaban_dmc3_1 = (b.jawaban_dmc3==1)?"checked":""; 
							let ck_jawaban_dmc3_2 = (b.jawaban_dmc3==2)?"checked":""; 
							let ck_jawaban_dmc3_3 = (b.jawaban_dmc3==3)?"checked":""; 
							let ck_jawaban_dmc3_4 = (b.jawaban_dmc3==4)?"checked":""; 
							let ck_jawaban_dmc3_5 = (b.jawaban_dmc3==5)?"checked":""; 
							output_dmc3 += "<tr class='row-feedback_dmc3 feedback_dmc3" + b.id_feedback + "'>";
							output_dmc3 += "	 <td>";
							output_dmc3 += "		<input type='hidden' class='form-control' name='id_feedback_dmc3[]' value='"+b.id_feedback+"'/>";
							output_dmc3 += "	 	"+b.pertanyaan+"";
							output_dmc3 += "	 </td>";
							output_dmc3 += "	 <td><input "+ck_jawaban_dmc3_1+" type='radio' class='form-control-radio' name='feedback_dmc3_"+b.id_feedback+"' value='1' required='required'></td>";
							output_dmc3 += "	 <td><input "+ck_jawaban_dmc3_2+" type='radio' class='form-control-radio' name='feedback_dmc3_"+b.id_feedback+"' value='2' required='required'></td>";
							output_dmc3 += "	 <td><input "+ck_jawaban_dmc3_3+" type='radio' class='form-control-radio' name='feedback_dmc3_"+b.id_feedback+"' value='3' required='required'></td>";
							output_dmc3 += "	 <td><input "+ck_jawaban_dmc3_4+" type='radio' class='form-control-radio' name='feedback_dmc3_"+b.id_feedback+"' value='4' required='required'></td>";
							output_dmc3 += "	 <td><input "+ck_jawaban_dmc3_5+" type='radio' class='form-control-radio' name='feedback_dmc3_"+b.id_feedback+"' value='5' required='required'></td>";
							output_dmc3 += "</tr>";
							$(output_dmc3).appendTo(".table-feedback_dmc3 tbody");
						});
					}							
                });
            },
            complete: function() {
                $('.select2modal').select2({
                    dropdownParent: $('#add_modal')
                });
				if(act=='sesi1'){
					$(".modal-title").html("Upload Dokumen AIM Assessment");
					$('.show_sesi1').removeClass('hide');
					$('.nav-tabs-custom').addClass('hide');
					$('.form-control').prop('required', false);
					$('.form-control-radio').prop('required', false);
					$('#dokumen_scraft').prop('required', true);
					$('#btn_save').show();
				}
				if(act=='dmc1'){
					$(".modal-title").html("Nilai Mentor DMC 1");
					$('.show_dmc1').removeClass('hide');
					// $('.nav-tabs-custom').addClass('hide');
					$('#sasaran_pengembangan_dmc1').prop('required', true);
					$('#kriteria_keberhasilan_dmc1').prop('required', true);
					if(nik_mentor_dmc1!=null){
						$('.show_mentor_dmc1').removeClass('hide');
						$('#nik_mentor_dmc1').prop('disabled', true);
					}
					$('#btn_save').show();
				}
				if(act=='dmc2'){
					$(".modal-title").html("Nilai Mentor DMC 2");
					$('.show_dmc2').removeClass('hide');
					// $('.nav-tabs-custom').addClass('hide');
					$('#sasaran_pengembangan_dmc2').prop('required', true);
					$('#kriteria_keberhasilan_dmc2').prop('required', true);
					if(nik_mentor_dmc2!=null){
						$('.show_mentor_dmc2').removeClass('hide');
						$('#nik_mentor_dmc2').prop('disabled', true);
					}
					$('#btn_save').show();
				}
				if(act=='dmc3'){
					$(".modal-title").html("Nilai Mentor DMC 3");
					$('.show_dmc3').removeClass('hide');
					// $('.nav-tabs-custom').addClass('hide');
					$('#sasaran_pengembangan_dmc3').prop('required', true);
					$('#kriteria_keberhasilan_dmc3').prop('required', true);
					if(nik_mentor_dmc3!=null){
						$('.show_mentor_dmc3').removeClass('hide');
						$('#nik_mentor_dmc3').prop('disabled', true);
					}
					$('#btn_save').show();
				}
				if(act=='all'){
					$(".modal-title").html("Detail Mentoring");
					$('.show_sesi1').addClass('hide');
					$('.show_sesi2').addClass('hide');
					$('.show_dmc1').removeClass('hide');
					$('.show_dmc2').removeClass('hide');
					$('.show_dmc3').removeClass('hide');
					$('.form-input-detail .form-control').prop('disabled', true);
					$('.form-control-radio').prop('disabled', true);
					$('#btn_save').hide();
				}
				setTimeout(function () {
					$("table-feedback_dmc1").DataTable({
						"bLengthChange": false,
						"ordering": true,
						"pageLength":3,
						"searching": false,
						"info": false
					}).columns.adjust();
					$("table-feedback_dmc2").DataTable({
						"bLengthChange": false,
						"ordering": true,
						"pageLength":3,
						"searching": false,
						"info": false
					}).columns.adjust();
					$("table-feedback_dmc3").DataTable({
						"bLengthChange": false,
						"ordering": true,
						"pageLength":3,
						"searching": false,
						"info": false
					}).columns.adjust();
				}, 1500);				
				$('#detail_modal').modal('show');
            }

        });
    });	
	
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-input-detail");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-input-detail")[0]);
				$.ajax({
					url: baseURL+'mentor/transaksi/save/approve_mentee',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
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
	
    //history
    $(document).on("click", ".history", function() {
        var nomor = $(this).data("nomor");
        $.ajax({
			url: baseURL + 'mentor/transaksi/get/history',
            type: 'POST',
            dataType: 'JSON',
            data: {
                nomor: nomor
            },
            success: function(data) {
				var det_pengajuan	= "";
					det_pengajuan	+= 		'<table class="table table-bordered datatable-vendor">';
					det_pengajuan	+= 		'	<thead>';
					det_pengajuan	+= 		'		<tr>';
					det_pengajuan	+= 		'			<th>Nomor Mentoring</th>';
					det_pengajuan	+= 		'			<th>Tanggal Status</th>';
					det_pengajuan	+= 		'			<th>Status</th>';
					det_pengajuan	+= 		'		</tr>';
					det_pengajuan	+= 		'	</thead>';
					det_pengajuan	+= 		'	<tbody>';

                $.each(data, function(i, v) {
					det_pengajuan	+= 		'		<tr>';
					det_pengajuan	+= 		'			<td>'+v.nomor+'</td>';
					det_pengajuan	+= 		'			<td>'+v.tanggal_format+'<br>'+v.jam_format+'</td>';
					det_pengajuan	+= 		'			<td>'+v.action.toUpperCase()+' OLEH :<br><span class="label label-info">'+v.author.toUpperCase()+' : '+v.nama_karyawan+'</span></td>';
					det_pengajuan	+= 		'		</tr>';
                });
					det_pengajuan	+= 		'	</tbody>';
					det_pengajuan	+= 		'</table>';
					$("#histori_mentor").html(det_pengajuan);
				
            },
            complete: function() {
				setTimeout(function () {
					$("table.datatable-vendor").DataTable({
						"order": [[1, 'desc']],
						"bLengthChange": false
					}).columns.adjust();
				}, 1500);				
                $('#modal-history').modal('show');
            }
        });
    });
	

});

function resetForm_use($form, $act) {
    $('.select2modal').select2('destroy').find('option').prop('selected', false).end().select2();
    $form.find('input:text, input:password, input:file,  textarea, input:hidden').val("");
    $form.find('input:text, input:password, input:file,  textarea, input:hidden').prop('disabled', false);
    $form.find('select').val(0);
    $form.find('select').prop('disabled', false);
    $form.find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    $form.find('input:radio, input:checkbox').prop('disabled', false);

    validateReset('.form-input-detail');
}

function resetForm_extend($form) {
    $('#plant_extend').prop('disabled', false);
}

function datatables_ssp() {
    var jenis_depo_filter 	= $("#jenis_depo_filter").val();
    var pabrik_filter		= $("#pabrik_filter").val();
    var filter_status		= $("#filter_status").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        pageLength: 25,
        initComplete: function() {
            var api = this.api();
            $("#sspTable_filter input").attr(
                "placeholder",
                "Press enter to start searching"
            );
            $("#sspTable_filter input").attr(
                "title",
                "Press enter to start searching"
            );
            $("#sspTable_filter input")
                .off(".DT")
                .on("keypress change", function(evt) {
                    if (evt.type == "change") {
                        api.search(this.value).draw();
                    }
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
		order: [[0, 'desc']],
        ajax: {
            // url: baseURL + 'depo/evaluasi/get/data/bom',
            url: baseURL + 'mentor/transaksi/get/mentor/bom',
            type: 'POST',
            data: function(data) {
                data.filter_status		= filter_status;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "nomor_mentoring",
                "name": "nomor_mentoring",
                "width": "10%",
                "render": function(data, type, row) {
					return row.nomor_mentoring;
                }
            },
            {
                "data": "nik_mentor",
                "name": "nik_mentor",
                "width": "10%",
                "render": function(data, type, row) {
					return row.nik_mentor;
                }
            },
            {
                "data": "nama_mentor",
                "name": "nama_mentor",
                "width": "10%",
                "render": function(data, type, row) {
					return row.nama_mentor;
                }
            },
            {
                "data": "jabatan_mentor",
                "name": "jabatan_mentor",
                "width": "20%",
                "render": function(data, type, row) {
					return row.jabatan_mentor;
                }
            },
            {
                "data": "departemen_mentor",
                "name": "departemen_mentor",
                "width": "15%",
                "render": function(data, type, row) {
					return row.departemen_mentor;
                }
            },
            {
                "data": "tanggal_sesi1_rencana_format",
                "name": "tanggal_sesi1_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					if(row.url_scraft!=null){
						link_scraft = "<a href='"+baseURL+""+row.url_scraft+"' target='_blank'>Dokumen AIM Assessment</a>"; 
					}else{
						link_scraft = "";
					}
					return '<b>Rencana:</b><br>'+row.tanggal_sesi1_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_sesi1_aktual_format+"<br>"+link_scraft;
                }
            },
            {
                "data": "tanggal_sesi2_rencana_format",
                "name": "tanggal_sesi2_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					return '<b>Rencana:</b><br>'+row.tanggal_sesi2_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_sesi2_aktual_format;
                }
            },
            {
                "data": "tanggal_dmc1_rencana_format",
                "name": "tanggal_dmc1_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					if((row.nik_mentor_dmc1!=null)&&(row.nik_login!=row.nik_mentor_dmc1)){
						show_mentor_dmc1 = "<b>Additional Mentor:</b><br>"+row.nama_mentor_dmc1;
					}else{
						show_mentor_dmc1 = "";
					}
					return '<b>Rencana:</b><br>'+row.tanggal_dmc1_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_dmc1_aktual_format+"<br>"+show_mentor_dmc1;
                }
            },
            {
                "data": "tanggal_dmc2_rencana_format",
                "name": "tanggal_dmc2_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					if((row.nik_mentor_dmc2!=null)&&(row.nik_login!=row.nik_mentor_dmc2)){
						show_mentor_dmc2 = "<b>Additional Mentor:</b><br>"+row.nama_mentor_dmc2;
					}else{
						show_mentor_dmc2 = "";
					}
					return '<b>Rencana:</b><br>'+row.tanggal_dmc2_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_dmc2_aktual_format+"<br>"+show_mentor_dmc2;
                }
            },
            {
                "data": "tanggal_dmc3_rencana_format",
                "name": "tanggal_dmc3_rencana_format",
                "width": "10%",
                "render": function(data, type, row) {
					if((row.nik_mentor_dmc3!=null)&&(row.nik_login!=row.nik_mentor_dmc3)){
						show_mentor_dmc3 = "<b>Additional Mentor:</b><br>"+row.nama_mentor_dmc3;
					}else{
						show_mentor_dmc3 = "";
					}
					return '<b>Rencana:</b><br>'+row.tanggal_dmc3_rencana_format+"<br><b>Aktual:</b><br>"+row.tanggal_dmc3_aktual_format+"<br>"+show_mentor_dmc3;
                }
            },
            {
                "data": "sla",
                "name": "sla",
                "width": "10%",
                "render": function(data, type, row) {
					return row.sla;
                }
            },
            {
                "data": "nama_status",
                "name": "nama_status",
                "width": "15%",
                "render": function(data, type, row) {
					if(row.id_status==6){
						return "<label class='label label-success'>"+row.nama_status_group+"</label><br>"+row.detail_status;
					}else if(row.warna_status==7){
						return "<label class='label label-error'>"+row.nama_status_group+"</label><br>"+row.detail_status;
					}else{
						return "<label class='label label-warning'>"+row.nama_status_group+"</label><br>"+row.detail_status;
					}
                }
            },
            {
                "data": "nomor",
                "name": "nomor",
                "width": "5%",
                "render": function(data, type, row) {
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if((row.id_status==1)&&(row.nik_login==row.nik_mentee))
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='sesi1'><i class='fa fa-clipboard'></i> Upload AIM Assessment</a></li>";
					if((row.id_status==3)&&(row.nik_login==row.nik_mentee)&&(row.tanggal_dmc1_aktual_format!='-'))
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='dmc1' data-nik_mentor_dmc1='" + row.nik_mentor_dmc1+ "'><i class='fa fa-bar-chart-o'></i> Mentee Rating DMC 1</a></li>";
					if((row.id_status==4)&&(row.nik_login==row.nik_mentee)&&(row.tanggal_dmc2_aktual_format!='-'))
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='dmc2' data-nik_mentor_dmc2='" + row.nik_mentor_dmc2+ "'><i class='fa fa-bar-chart-o'></i> Mentee Rating DMC 2</a></li>";
					if((row.id_status==5)&&(row.nik_login==row.nik_mentee)&&(row.tanggal_dmc3_aktual_format!='-'))
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='dmc3' data-nik_mentor_dmc3='" + row.nik_mentor_dmc3+ "'><i class='fa fa-bar-chart-o'></i> Mentee Rating DMC 3</a></li>";
					output += "					<li><a href='javascript:void(0)' class='detail' data-nomor='" + row.nomor+ "' data-act='all'><i class='fa fa-search'></i> Detail</a></li>";
					
					output += "					<li><a href='javascript:void(0)' class='history' data-nomor='" + row.nomor + "'><i class='fa fa-h-square'></i> History</a></li>";
					output += "				</ul>";
					output += "	        </div>";
                    return output;
                }
            }

        ],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            if (info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });

    return mydDatatables;
}

function load_plant(plant) {
    $.ajax({
        url: baseURL + 'material/master/get/plant',
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
            if (data) {
                var output = '';
                $.each(data, function(i, v) {
                    output += '<option value="' + v.plant + '">' + v.plant + '</option>';
                });
                $("select[name='plant[]']").html(output);
            }
        },
        complete: function() {
            // var plant	= plant.split(",");
            $("select[name='plant[]']").val(plant).trigger("change");
        }
    });
}

function validateReset(target = 'form') {
    var element = $("input, select, textarea", $(target));
    $.each(element, function(i, v) {
        if (v.tagName == 'SELECT' && v.nextSibling.firstChild != null) {
            v.nextSibling.firstChild.firstChild.style.borderColor = "#d2d6de";
        }
        v.style.borderColor = "#d2d6de";
    });
}


// function hitung_nilai() {
	// //hitung total_nilai
	// if($('input[name="nilai_1"]').val()!=''){
		// var nilai_1 = $('input[name="nilai_1"]').val();
	// }else{
		// var nilai_1 = 0;
	// }
	// if($('input[name="nilai_2"]').val()!=''){
		// var nilai_2 = $('input[name="nilai_2"]').val();
	// }else{
		// var nilai_2 = 0;
	// }
	// if($('input[name="nilai_3"]').val()!=''){
		// var nilai_3 = $('input[name="nilai_3"]').val();
	// }else{
		// var nilai_3 = 0;
	// }
	// var total_nilai = parseInt(nilai_1)+parseInt(nilai_2)+parseInt(nilai_3);
	// $("input[name='total_nilai']").val(total_nilai);
// }