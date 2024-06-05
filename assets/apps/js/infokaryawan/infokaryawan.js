$(document).ready(function () {
	
	// Setup datatables
	$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
	    if(oSettings) {
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
    $(document).on("change", "#lokasi", function(){
         datatables_ssp();
    });
	
	
    $('#lokasi').select2({
        closeOnSelect: true
    });

    // $('#lokasi').on('change.select2',function () {
        // $('.form-filter-lokasi').submit();
    // });
    // $(".detail").on("click", function (e) {
	$(document).on("click", ".detail", function(){	
        var id = $(this).data("edit");
        $.ajax({
            url: baseURL + 'settings/adminstaff/get_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $.each(data.data, function (i, v) {
                    $(".inik").html(v.nik);
                    $(".inama").html(v.nama);
                    $(".itelepon").html(v.telepon);
                    $(".iemail").html(v.email);
                    $(".idepartemen").html(v.nama_departemen);
                    $(".iimage").attr('src',v.user_image);

                    $('#modalDetail').modal('show');
                });
            }
        });
    });
    //cek all
    $(document).on("change", ".isSelectAllPlant,.isSelectAllSalesPlant", function(e){
        if($(".isSelectAllPlant").is(':checked')) {
            $('#lokasi').select2('destroy').find('option').prop('selected', 'selected').end().select2();
        }else{
            $('#lokasi').select2('destroy').find('option').prop('selected', false).end().select2();
        }
		$('.form-filter-lokasi').submit();
    });
	
});

function datatables_ssp(){
    var lokasi	= $("#lokasi").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
	    pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 50,
	    paging: $(".my-datatable-extends-order",this).data("paging") ? $(".my-datatable-extends-order",this).data("paging") : true,
        // pageLength: 10,

        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input')
                .off('.DT')
                .on('input.DT', function () {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "Please wait..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: baseURL+'infokaryawan/get/karyawan/bom',
            type: 'POST',
            data: function(data){
                data.lokasi = lokasi;
            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            }
        },
        columns: [
            {
                "data": "tbl_karyawan.id_karyawan",
                "name" : "id_karyawan",
                "width": "0%",
                "render": function (data, type, row) {
                    return row.id_karyawan;
                },
                "visible": false
            },
            {
				"data": "tbl_karyawan.nama",
                "name" : "nama",
                "width": "20%",
				"class": "detail",
                "render": function (data, type, row) {
                    return row.nama;
                }
            },
            {
				"data": "tbl_karyawan.nik",
                "name" : "nik",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.nik;
                }
            },
            {
				"data": "tbl_karyawan.ho",
                "name" : "ho",
                "width": "15%",
                "render": function (data, type, row) {
                    if(row.ho=='y'){
                        return 'Head Office';
                    }else{
                        return row.plant_name;
                    }

                }
            },
            {
				"data": "tbl_karyawan.ho",
                "name" : "ho",
                "width": "20%",
                "render": function (data, type, row) {
                    if(row.ho=='y'){
						if(row.nama_departemen==null){
							return row.nama_divisi;	
						}else{
							return row.nama_departemen;
						}
                    }else{
						if((row.nama_seksi==null)&&(row.nama_sub_divisi==null)){
							return row.plant_name;
						}else if(row.nama_seksi==null){
							return row.nama_departemen;
						}else{
							return row.nama_seksi;
						}
                    }
                }
            },
            {
				"data": "tbl_karyawan.email",
                "name" : "email",
                "width": "20%",
                "render": function (data, type, row) {
                    return "<a href='mailto:" + row.email + "' >"+row.email+"</a>";
                }
            },
            {
				"data": "tbl_karyawan.telepon",
                "name" : "telepon",
                "width": "5%",
                "render": function (data, type, row) {
                    return row.telepon;
                }
            },
            {
				"data": "tbl_karyawan.id_karyawan",
                "name" : "id_karyawan",
                "width": "1%",
                "render": function (data, type, row) {
					return "<a href='javascript:void(0)' class='detail' data-edit='"+row.id_karyawan+"'><span class='label label-default'>Detail</span></a>";
                }
            }
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            if(info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });

    return mydDatatables;
}
