$(document).ready(function () {
    $("#btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
    });
    $(document).on("click", ".nonactive, .setactive", function (e) {
        $.ajax({
            url: baseURL + "spot/master/set/pol",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_spot_setting_pol: $(this).data($(this).attr("class")),
                type: $(this).attr("class")
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg);
                } else {
                    kiranaAlert("notOK", data.msg, "warning", "no");
                }
            }
        });
        e.preventDefault();
        return false;
    });
    //set on change plant for get lgort(storage location)
    $(document).on("change", "#port", function (e) {
        var port = $(this).val();
        $.ajax({
            url: baseURL + 'spot/master/get/pol',
            type: 'POST',
            dataType: 'JSON',
            data: {
                port: port
            },
            success: function (data) {
                console.log(data);
                if (data) {
                    $(".title-form").html("Setting Port Of Load");
                    $("input[name='id_spot_setting_pol']").val('');
                    $("input[name='no_urut']").val('');
                    $("input[name='selisih']").val('');
                    $("select[name='plant[]']").val('').trigger("change");
                    $.each(data, function (i, v) {
                        $("input[name='id_spot_setting_pol']").val(v.id_spot_setting_pol);
                        $("input[name='no_urut']").val(v.no_urut);
                        $("input[name='selisih']").val(v.selisih);
                        if (v.werks !== null) {
                            var in_werks = v.werks.split(",");
                            $("select[name='plant[]']").val(in_werks).trigger("change");
                        }
                        $("#btn-new").removeClass("hidden");
                    });

                }
            }
        });
    });

    $(".edit").on("click", function (e) {
        var id_spot_setting_pol = $(this).data("edit");
        $.ajax({
            url: baseURL + 'spot/master/get/pol',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_spot_setting_pol: id_spot_setting_pol
            },
            success: function (data) {
                console.log(data);
                $(".title-form").html("Setting Port Of Load");
                $.each(data, function (i, v) {
                    $("input[name='id_spot_setting_pol']").val(v.id_spot_setting_pol);
                    $("select[name='port']").val(v.port).trigger('change');
                    $("input[name='no_urut']").val(v.no_urut);
                    $("input[name='selisih']").val(v.selisih);
                    if (v.werks !== null) {
                        var in_werks = v.werks.split(",");
                        $("select[name='plant[]']").val(in_werks).trigger("change");
                    }
                    $("#btn-new").removeClass("hidden");
                });
            }
        });
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var empty_form = validate();
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-master-upload")[0]);

                $.ajax({
                    url: baseURL + 'spot/master/save/upload',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
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
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });
    //export to excel
    $('.my-datatable-extends-order').DataTable({
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends-order", this).data("page") ? $(".my-datatable-extends-order", this).data("page") : 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Port Of Load',
                download: 'open',
                orientation: 'landscape',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4]
                }
            }
        ]
    });

    $(document).on("change.bs.fileinput", ".fileinput", function (e) {
        readURL($('input[type="file"]', $(this))[0], $('.fileinput-zoom', $(this)));
        console.log($('input[type="file"]', $(this))[0]);
    });

    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
        if (oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        }
    };

    datatables_ssp();
    $(document).on("change", " #plant, #tanggal ", function (e) {
        datatables_ssp();
    });
});

function rupiah(num) {
    // var number = parseInt(num);
    var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
    if (str.indexOf(",") > 0) {
        parts = str.split(",");
        str = parts[0];
    }
    str = str.split("").reverse();
    for (var j = 0, len = str.length; j < len; j++) {
        if (str[j] != ".") {
            output.push(str[j]);
            if (i % 3 == 0 && j < (len - 1)) {
                output.push(".");
            }
            i++;
        }
    }
    formatted = output.reverse().join("");
    return ("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
};

function tanggaltitik(tgl) {
    var result = "";
    if (tgl != undefined && tgl != "") {
        var a = tgl.split(" ");
        var b = a[0];

        if (b != "") {
            var c = b.split('-');
            result = c[2] + '.' + c[1] + '.' + c[0];
        } else {
            result = "";
        }
    } else {
        result = "";
    }
    return result;
}

function datatables_ssp() {
    var jenis = 1;
    var plant = $("#plant").val();
    var tanggal = $("#tanggal").val();

    $("#sspTable").DataTable().destroy();
    var mydDatatables = $("#sspTable").DataTable({
        columnDefs: [
            {
                targets: [3, 4],
                className: 'text-right'
            }
        ],
        // //export to excel
        // dom: 'Bfrtip',
        // buttons: [
        // {
        // extend: 'excelHtml5',
        // text: 'Export to Excel',
        // title: 'Asset FO',
        // download: 'open',
        // orientation:'landscape',
        // exportOptions: {
        // columns: [1,2,3,4,5,6,7,8,9,10,11,12]
        // }
        // }
        // ],
        ordering: true,
        order: [[2, "desc"]],
        pageLength: $(".my-datatable-extends-order", this).data("page") ? $(".my-datatable-extends-order", this).data("page") : 10,
        paging: $(".my-datatable-extends-order", this).data("paging") ? $(".my-datatable-extends-order", this).data("paging") : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,

        pageLength: 10,
        initComplete: function () {
            var api = this.api();
            $('#sspTable_filter input').attr("placeholder", "Press enter to start searching");
            $('#sspTable_filter input').attr("title", "Press enter to start searching");
            $('#sspTable_filter input')
                .off('.DT')
                .on('keypress change', function (evt) {
                    console.log(evt.type);
                    // if(evt.type == "keypress" && evt.keyCode == 13) {
                    //     api.search(this.value).draw();
                    // }
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
            url: baseURL + 'spot/master/get/list_upload_deal_beli',
            type: 'POST',
            data: function (data) {
                data.jenis = jenis;
                // data.plant       = plant;
                // data.tanggal     = tanggal;

            },
            error: function (a, b, c) {
                console.log(a);
                console.log(b);
                console.log(c);
            },

        },
        columns: [
            {
                "data": "id_deal_beli",
                "name": "id_deal_beli",
                "width": "10%",
                "render": function (data, type, row) {
                    return row.id_deal_beli;
                },
                "visible": true
            },
            {
                "data": "plant_deal",
                "name": "plant_deal",
                "width": "10%",
                "render": function (data, type, row) {
                    return row.plant_deal;
                },
                // "visible": tipe_sc
            },
            {
                "data": "tanggal_deal",
                "name": "tanggal_deal",
                "width": "15%",
                "render": function (data, type, row) {
                    return (row.tanggal_deal);
                },
                // "visible": tipe_sc
            },
            {
                "data": "qty_deal",
                "name": "qty_deal",
                "width": "5%",
                "align": 'right',
                "render": function (data, type, row) {
                    return rupiah(row.qty_deal);
                }
            },
            {
                "data": "harga_deal",
                "name": "harga_deal",
                "width": "10%",
                "align": 'right',
                "render": function (data, type, row) {
                    return rupiah(row.harga_deal);
                }
            },
            {
                "data": "app",
                "name": "app",
                "width": "10%",
                "align": 'right',
                "render": function (data, type, row) {
                    return '[' + row.nik + '] - ' + row.nama + '<br><code>' + row.app + '</code>';
                }
            },
        ],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            // console.log(data);
            if (info) {
                var page = info.iPage;
                var length = info.iLength;
            }
            $('td:eq(0)', row).html();
        }
    });

    return mydDatatables;
}