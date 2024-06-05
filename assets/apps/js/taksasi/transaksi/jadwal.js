$(document).ready(function() {
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
	
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
    $(document).on("change", "#tahap_filter, #status_filter", function() {
        datatables_ssp();
    });

	//change id_tahap
    $(document).on("change", "#id_tahap", function(e){
		var id_tahap	= $(this).val();
        $.ajax({
			url: baseURL + 'taksasi/master/get/tahap',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_tahap: id_tahap
            },
            success: function(data) {
                $.each(data, function(i, v) {
                    console.log(data);
					$("input[name='pra_syarat']").val(v.pra_syarat);
                });
            }
        });
    });
	
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "taksasi/master/set/tahap",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_tahap : $(this).data($(this).attr("class")),	
				type 	: $(this).attr("class")
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

	$(document).on("click", ".edit", function (e) {	
    	var id_jadwal	= $(this).data("id_data");
		$.ajax({
    		url: baseURL+'taksasi/transaksi/get/data',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_jadwal : id_jadwal
			},
			success: function(data){
				$(".title-form").html("Setting Grade Nilai BOKIN");
				$(".table-grade tbody").html('');
				$.each(data, function(i, v){
					$("#id_jadwal").val(v.id_jadwal);
					$("input[name='nama']").val(v.caption_nama);
					$("input[name='nama_tahap']").val(v.caption_nama_tahap);
					//buat autocomplete peserta
					if (v.list_peserta != null) {
						var peserta = v.peserta ? v.peserta.slice(0, -1).split(",") : "";
						var arr_list_peserta = v.list_peserta.slice(0, -1).split(",");
						var array = [];
						$.each(arr_list_peserta, function (x, y) {
							var arr_peserta = y ? y.split("|") : "";
							var control = $('#peserta').empty().data('select2');
							var adapter = control.dataAdapter;
							array.push({ "id": peserta[x], "text": arr_peserta[1] + ' - [' + arr_peserta[0] + ']' });
							adapter.addOptions(adapter.convertToOptions(array));
							$('#peserta').trigger("change.select2");
						});
						console.log(array);
						$('#peserta').val(peserta).trigger("change.select2");
					}
					//detail bobot
					if (v.arr_bobot) {
						let no = 0;
						let total_bobot = 0;
						$("#nodata_grade").remove();
						$.each(v.arr_bobot, function(a, b){
							if(b.id_nilai !=null){
								no++;
								total_bobot += parseFloat(b.bobot);
								let output = "";
								output += "<tr class='row-grade grade" + b.id_nilai + "'>";
								output += "	<td>";
								output += "		<select class='form-control select2 autocomplete' name='id_nilai[]' required='required'>";
								output += "			<option></option>";
								output += "		</select>";
								output += "	</td>";
								output += "	<td>";
								output += "		<input type='text' class='angka form-control text-center' name='bobot[]' value='"+b.bobot+"' required='required' />";
								output += "	</td>";
								output += '	<td class="text-center">';
								output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
								output += "	</td>";
								output += "</tr>";
								$(output).appendTo(".table-grade tbody");
							
								const elem = ".row-grade.grade" + b.id_nilai;
								master_nilai(elem + " select[name='id_nilai[]']");

								let control = $(elem+ " select[name='id_nilai[]']").empty().data("select2");
								console.log(control);
								let adapter = control.dataAdapter;
								let desc = `${b.nama_nilai}`;
								adapter.addOptions(
									adapter.convertToOptions([{
										id: b.id_nilai,
										text: desc,
									},])
								);
								$(elem+ " select[name='id_nilai[]']").trigger("change");
							}
							
							
						});
						$("input[name='total_bobot']").val(parseFloat(total_bobot).toFixed(2));
					}								
					
					
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var total_bobot = $("input[name='total_bobot']").val();
		var empty_form = validate(".form-taksasi-jadwal");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
				if (total_bobot!=100){
					swal('Error', 'Total Bobot Harus 100.', 'error');
				}else{
					$("input[name='isproses']").val(1);
					var formData = new FormData($(".form-taksasi-jadwal")[0]);

					$.ajax({
						url: baseURL+'taksasi/transaksi/save/jadwal',
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
				}
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
	//triger add grade
    $(document).on("click", "button[name='add_grade']", function () {
		let nama_tahap = $("input[name='nama_tahap']").val();
		if(nama_tahap){
			let idx = $("tr.row-grade").length;
			let elem = ".row-grade.grade" + idx;
			let output = "";
			if ($("#nodata_grade").length > 0) {
				$("#nodata_grade").remove();
			}

			output += "<tr class='row-grade grade" + idx + "'>";
			output += "	<td>";
			output += "		<select class='form-control select2 autocomplete' name='id_nilai[]' required='required'>";
			output += "			<option></option>";
			output += "		</select>";
			output += "	</td>";
			output += "	<td>";
			output += "		<input type='text' class='angka form-control text-center' name='bobot[]' value='' required='required' />";
			output += "	</td>";
			output += '	<td class="text-center">';
			output += "	    <button type='button' class='btn btn-sm btn-danger remove_item' title='Remove'><i class='fa fa-trash-o'></i></button>";
			output += "	</td>";
			output += "</tr>";
			$(output).appendTo(".table-grade tbody");
			//autocomplete grade KMG
			master_nilai(elem + " select[name='id_nilai[]']");
		}else{
			swal('Warning', 'Mohon isi Nama dan Tahap terlebih dahulu.', 'warning');
		}	
    });
    $(document).on("change", "input[name*='bobot']", function() {
		//set total
		generate_total_bobot();			
		
    });
	
	
    $(document).on("click", ".remove_item", function (e) {
        if ($("tr.row-grade").length > 1) {
            $(this).closest("tr.row-grade").remove();
        }

        $("tr.row-grade").each(function (i, v) {
            $(this).removeAttr("class");
            $(this).addClass("row-grade");
            $(this).addClass("grade" + i);
        });

        if ($(".table-grade tbody tr").length == 0) {
            show_nodata();
        }
    });
    $(document).on('select2:clear', "select[name='id_nilai[]']", function (e) {
        $(this).closest("tr.row-grade").find("input[name='bobot[]']").val("");
    });

	//
	
	
	//auto complete peserta
	$("select[name='peserta[]']").select2({
        allowClear: true,
        placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
        },
        ajax: {
            // url: baseURL+'bank/transaksi/get/user_auto',
            url: baseURL+'taksasi/transaksi/get/user_auto',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
					autocomplete: true,
					pra_syarat	: $("input[name='pra_syarat']").val(),
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
			else if(repo.text)
				return repo.text;
			else 
				return repo.nama;
		}
    });

    $("select[name='peserta[]']").on('select2:select', function(e){
		var id = e.params.data.id;
		var option = $(e.target).children('[value="'+id+'"]');
		option.detach();
		$(e.target).append(option).change();
    });	
	//auto complete peserta sampe sini	


});


function datatables_ssp() {
    var tahap_filter 	= $("#tahap_filter").val();
    var status_filter	= $("#status_filter").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
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
        ajax: {
            // url: baseURL + 'grade/transaksi/get/data/bom',
            url: baseURL + 'taksasi/transaksi/get/data/bom',
            type: 'POST',
            data: function(data) {
                data.tahap_filter  	= tahap_filter;
                data.status_filter	= status_filter;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "caption_nama",
                "name": "caption_nama",
                "render": function(data, type, row) {
					return row.caption_nama;
                }
            },
            {
                "data": "caption_nama_tahap",
                "name": "caption_nama_tahap",
                "render": function(data, type, row) {
					return row.caption_nama_tahap;
                }
            },
            {
                "data": "caption_tanggal_awal",
                "name": "caption_tanggal_awal",
                "render": function(data, type, row) {
					return row.caption_tanggal_awal;
                }
            },
            {
                "data": "caption_tanggal_akhir",
                "name": "caption_tanggal_akhir",
                "render": function(data, type, row) {
					return row.caption_tanggal_akhir;
                }
            },
            {
                "data": "label_status",
                "name": "label_status",
                "width": "15%",
                "render": function(data, type, row) {
					return row.label_status;
                }
            },
            {
                "data": "id_data",
                "name": "id_data",
                "width": "5%",
                "render": function(data, type, row) {
					var url_nilai 	= baseURL + "taksasi/transaksi/nilai/" + row.id_data;
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					if(row.main_status=='completed'){
						output += "					<li><a href='"+url_nilai+"' ><i class='fa fa-search'></i> Detail Nilai</a></li>";
					}else{
						output += "					<li><a href='javascript:void(0)' class='edit' data-id_data='" + row.id_data + "'><i class='fa fa-pencil-square-o'></i> Set Grade</a></li>";						
						if(row.pass_grade!=null)
						output += "					<li><a href='"+url_nilai+"' ><i class='fa fa-clipboard'></i> Input Nilai</a></li>";					
					}
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
function generate_total_bobot() {
    let total = 0;
    $(".form-taksasi-jadwal input[name^='bobot']").each(function(i) {
        total += +$(this).val().replace(/,/g, "");
    });
    total = total.toFixed(2);
	$("input[name='total_bobot']").val(numberWithCommas(total));	
}

function master_nilai(elem) {
    if ($(elem).length > 0 && $(elem).hasClass("select2-hidden-accessible")) {
        // $(elem).select2("destroy");
        // $(elem).empty();
    }

    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        ajax: {
            url: baseURL + "taksasi/transaksi/get/penilaian_auto",
            dataType: "json",
            delay: 750,
            cache: false,
            data: function (params) {
                let selected_id_nilai = [];
				$("select[name='id_nilai[]']").each(function (i, v) {
                    selected_id_nilai.push($(v).val());
                });
                let data = {
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
                    not_in_nilai: selected_id_nilai
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
            return `<div class="clearfix">${repo.nama_nilai}</div>`;
        },
        templateSelection: function (repo) {
            if (repo.nama_nilai)
                markup = `${repo.nama_nilai}`;
            if (repo.text)
                markup = repo.text;

            return markup;
        }
    });
}


