$(document).ready(function () {
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
    $(document).on("change", "#pabrik, #group_produksi, #filter_bagian", function() {
        datatables_ssp();
    });

	//edit
	$(document).on("click", ".edit", function(){
		var nik	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'ess/laporan/get/mapping',
			type: 'POST',
			dataType: 'JSON',
			data: {
				nik : nik
			},
			success: function(data){
				$(".title-form").html("Set Group Produksi");
				$.each(data, function(i,v){
					$("#nik").val(v.nik);
					$("input[name='nik']").val(v.nik);
					$("input[name='nama']").val(v.nama);
					$("select[name='group']").val(v.prgrp_web).trigger("change");
					$("select[name='bagian']").val(v.prunt_web).trigger("change");
				});
			},
			complete: function () {
				$('#add_modal').modal('show');
			}

		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form 	= validate('.form-transaksi-mapping');
		
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-transaksi-mapping")[0]);
				// console.log();
				$.ajax({
					url: baseURL+'ess/laporan/save/mapping',
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

});

function datatables_ssp() {
	var pabrik 			= $("#pabrik").val();
	var group_produksi 	= $("#group_produksi").val();
	var bagian 			= $("#filter_bagian").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        // pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
        // paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        pageLength: 10,
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
            url: baseURL + 'ess/laporan/get/mapping/bom',
            type: 'POST',
            data: function(data) {
                data.pabrik = pabrik;
                data.group_produksi = group_produksi;
                data.bagian = bagian;
            },
            error: function(a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "nik",
                "name": "nik",
                "render": function(data, type, row) {
					return row.nik;
                }
            },
            {
                "data": "nama",
                "name": "nama",
                "render": function(data, type, row) {
					return row.nama;
                }
            },
            {
                "data": "email",
                "name": "email",
                "render": function(data, type, row) {
                    return row.email;
                }
            },
            {
                "data": "jabatan",
                "name": "jabatan",
                "render": function(data, type, row) {
                    return row.jabatan;
                }
            },
            {
                "data": "group_produksi",
                "name": "group_produksi",
                "render": function(data, type, row) {
                    return row.group_produksi;
                }
            },
            {
                "data": "bagian",
                "name": "bagian",
                "render": function(data, type, row) {
					return row.bagian;
                }
            },
		
            {
                "data": "nik",
                "name": "nik",
                "width": "5%",
                "render": function(data, type, row) {
					output = "			<div class='input-group-btn'>";
					output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
					output += "				<ul class='dropdown-menu pull-right'>";
					output += "					<li><a href='javascript:void(0)' class='edit' data-edit='" + row.nik + "'><i class='fa fa-pencil-square-o'></i> Set Mapping</a></li>";
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



