/*
@application    : KIRANAKU
@author         : Benazi S. Bahari (10183)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function () {
    // get_data(0,null);
    // $(document).on("change", "#aging, #role", function(e){
    // var aging 	= $("#aging").val();
    // var role 	= $("#role").val();
    // get_data(aging, role);
    // });

    $('.datePicker').datepicker({
        format: 'dd.mm.yyyy',
        autoclose: true
    });

    // $(".datePicker#dari").datepicker("setDate", moment().subtract(1, 'months').format('DD.MM.YYYY'));
    // $(".datePicker#dari").datepicker("setEndDate", $(".datePicker#sampai").val());		
    // $(".datePicker#sampai").datepicker("setDate", moment().format('DD.MM.YYYY'));
    // $(".datePicker#sampai").datepicker("setStartDate", $(".datePicker#dari").val());

    get_data();
    $(document).on("changeDate", "#tanggal_perjanjian_awal, #tanggal_perjanjian_akhir", function (e) {
        get_data();
    });
    $(document).on("change", "#filter_plant", function (e) {
        get_data();
    });

    //open modal for add     
    $(document).on("click", "#add_button", function (e) {
        $('#add_modal').modal('show');
    });

    //history
    $(document).on("click", "#his", function () {
        var no_pi = $(this).data("no_pi");
        // no_pi	= 'PI-10-DWJ1-I-2019';
        // alert(no_pi);
        $.ajax({
            // url: baseURL+'invest/get_detail_pi',
            url: baseURL + 'report/get_log_pi',
            type: 'POST',
            dataType: 'JSON',
            data: {
                no_pi: no_pi
            },
            success: function (data) {
                console.log(data);
                //history
                var aging = "";
                var no = 0;
                // <table class="table table-bordered table-striped my-datatable-extends">
                var nil = "<table class='table table-bordered table-striped table-modals'>";
                nil += "<thead>";
                nil += "<tr>";
                nil += "<th width='10'>No.</th><th width='25'>No.PI</th><th width='25'>Tanggal Status</th><th width='25'>Aging(Days)</th><th width='25'>Status</th><th width='25'>Comment</th>";
                nil += "</tr>";
                nil += "</thead>";
                nil += "<tbody>";
                $.each(data, function (i, v) {
                    no = no + 1;
                    if (v.aging == null) {
                        aging = '-';
                    } else {
                        aging = v.aging;
                    }
                    nil += "<tr>";
                    nil += "<td align='center'>" + no + "</td>";
                    nil += "<td>" + v.no_pi + "</td>";
                    nil += "<td>" + v.tgl_status_konversi + "</td>";
                    nil += "<td>" + aging + "</td>";
                    nil += "<td><span style='text-transform: capitalize'>" + v.action + "</span> oleh <br> <span class='label label-info'>" + v.nama_role + " : " + v.nama + "</label></td>";
                    nil += "<td>" + v.comment + "</td>";
                    nil += "</tr>";
                });
                nil += "</tbody>";
                $("#show_history").html(nil);
            },
            complete: function () {
                var t = $('.table-modals').DataTable({

                    order: [
                        [0, 'asc']
                    ],
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"]
                    ],
                    scrollX: true

                });
                setTimeout(function () {
                    $("table.dataTable").DataTable().columns.adjust();
                }, 1500);

                $('#log_status_modal').modal('show');
            }

        });
    });
});

function moveEditColumnToLeft(dataGrid) {
    dataGrid.columnOption("command:edit", {
        visibleIndex: -1,
        width: 80
    });
}

function get_data() {
    let tanggal_perjanjian_awal = $("#tanggal_perjanjian_awal").val();
    let tanggal_perjanjian_akhir = $("#tanggal_perjanjian_akhir").val();

    $.ajax({
        url: baseURL + 'spk/report/get/sla',
        type: 'POST',
        dataType: 'JSON',
        data: {
            return: 'json',
            tanggal_perjanjian_awal: tanggal_perjanjian_awal,
            tanggal_perjanjian_akhir: tanggal_perjanjian_akhir,
            pabrik: $("select[name='filter_plant[]']").val(),
        },
        beforeSend: function () {
            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
            $("body .overlay-wrapper").append(overlay);
        },
        success: function (data) {
            // console.log(data);
            $("#gridContainer").dxDataGrid({
                dataSource: data,
                columnChooser: {
                    enabled: true
                },
                height: '60vh',
                allowColumnReordering: true,
                allowColumnResizing: true,
                columnAutoWidth: true, //xx	
                showColumnLines: true,
                showRowLines: true,
                rowAlternationEnabled: true,
                "export": {
                    enabled: true,
                    fileName: "data_excel"
                },
                searchPanel: {
                    visible: true,
                    width: 240,
                    placeholder: "Cari"
                },
                headerFilter: {
                    visible: true
                },
                allowColumnReordering: true,
                grouping: {
                    autoExpandAll: false,
                },
                searchPanel: {
                    visible: true
                },
                paging: {
                    pageSize: 0
                },
                groupPanel: {
                    visible: true
                },
                columnFixing: {
                    enabled: true
                },
                // editing: {
                // mode: "row",
                // allowUpdating: true,
                // allowDeleting: true
                // }, 
                columns: [
                    {
                        dataField: "jenis_spk",
                        caption: "Jenis Perjanjian",
                        groupIndex: 0,
                        // fixed: true, 
                        autoExpandGroup: false
                    },
                    {
                        headerCellTemplate: function (container) {
                            container.append($("<div>Pabrik</div>"));
                        },
                        // fixed: true,
                        caption: "Pabrik",
                        dataField: "plant",
                        width: 60
                    },
                    {
                        headerCellTemplate: function (container) {
                            container.append($("<div>Perihal</div>"));
                        },
                        // fixed: true,
                        dataField: "perihal",
                        // width: 150
                    },
                    {
                        // headerCellTemplate: function (container) {
                        //     container.append($("<div>Tanggal<br>Perjanjian</div>"));
                        // },
                        // fixed: true,
                        caption: "Tanggal Perjanjian",
                        visible: true,
                        dataField: "tanggal_perjanjian_format",
                        width: 100
                    },
                    {
                        headerCellTemplate: function (container) {
                            container.append($("<div>Status</div>"));
                        },
                        // fixed: true,
                        caption: "Status",
                        visible: true,
                        dataField: "status_spk",
                        width: 100
                    },
                    {
                        caption: "Verification (Day/s)",
                        alignment: "center",
                        columns: [{
                            headerCellTemplate: function (container) {
                                container.append($("<div>Submitted-<br>Approved Legal</div>"));
                            },
                            dataField: "Submitted-Approved_Legal",
                            calculateCellValue: function (rowData) {
                                if (rowData.submit_approved === null) {
                                    return 'N/A';
                                } else {
                                    return Number(rowData.submit_approved);
                                }
                            },
                            format: "fixedPoint",
                            alignment: "center",
                            width: 80
                        }]
                    },
                    {
                        caption: "Confirmation by Relation Divisions (Day/s)",
                        alignment: "center",
                        columns: [{
                            caption: "Approved TAX",
                            headerCellTemplate: function (container) {
                                container.append($("<div>Approved<br>Tax</div>"));
                            },
                            dataField: "approved_tax",
                            calculateDisplayValue: function (rowData) { // combines display values
                                if (rowData.approved_tax === null) {
                                    return 'N/A';
                                } else {
                                    return rowData.approved_tax;
                                }
                            },
                            // calculateCellValue: function (rowData) {
                            //     if (rowData.approved_tax === null) {
                            //         return 'N/A';
                            //     } else {
                            //         return Number(rowData.approved_tax);
                            //     }
                            // },
                            // format: "fixedPoint",
                            alignment: "center",
                            width: 80
                        },
                        {
                            caption: "Approved Procurement",
                            headerCellTemplate: function (container) {
                                container.append($("<div>Approved<br>Procurement</div>"));
                            },
                            dataField: "approved_procurement",
                            calculateDisplayValue: function (rowData) { // combines display values
                                if (rowData.approved_procurement === null) {
                                    return 'N/A';
                                } else {
                                    return rowData.approved_procurement;
                                }
                            },
                            // calculateCellValue: function (rowData) {
                            //     if (rowData.approved_procurement === null) {
                            //         return 'N/A';
                            //     } else {
                            //         return Number(rowData.approved_procurement);
                            //     }
                            // },
                            // format: "fixedPoint",
                            alignment: "center",
                            width: 80
                        },
                        {
                            caption: "Approved Fincon",
                            headerCellTemplate: function (container) {
                                container.append($("<div>Approved<br>Fincon</div>"));
                            },
                            dataField: "approved_fincon",
                            calculateDisplayValue: function (rowData) { // combines display values
                                if (rowData.approved_fincon === null) {
                                    return 'N/A';
                                } else {
                                    return rowData.approved_fincon;
                                }
                            },
                            // calculateCellValue: function (rowData) {
                            //     if (rowData.approved_procurement === null) {
                            //         return 'N/A';
                            //     } else {
                            //         return Number(rowData.approved_fincon);
                            //     }
                            // },
                            // format: "fixedPoint",
                            alignment: "center",
                            width: 80
                        },
                        {
                            caption: "Approved Sourcing",
                            headerCellTemplate: function (container) {
                                container.append($("<div>Approved<br>Sourcing</div>"));
                            },
                            dataField: "approved_sourcing",
                            calculateDisplayValue: function (rowData) { // combines display values
                                if (rowData.approved_sourcing === null) {
                                    return 'N/A';
                                } else {
                                    return rowData.approved_sourcing;
                                }
                            },
                            // calculateCellValue: function (rowData) {
                            //     if (rowData.approved_sourcing === null) {
                            //         return 'N/A';
                            //     } else {
                            //         return Number(rowData.approved_sourcing);
                            //     }
                            // },
                            // format: "fixedPoint",
                            alignment: "center",
                            width: 80
                        },
                        {
                            caption: "Approved HRGA",
                            headerCellTemplate: function (container) {
                                container.append($("<div>Approved<br>HRGA</div>"));
                            },
                            dataField: "approved_hrga",
                            calculateDisplayValue: function (rowData) { // combines display values
                                if (rowData.approved_hrga === null) {
                                    return 'N/A';
                                } else {
                                    return rowData.approved_hrga;
                                }
                            },
                            // calculateCellValue: function (rowData) {
                            //     if (rowData.approved_hrga === null) {
                            //         return 'N/A';
                            //     } else {
                            //         return Number(rowData.approved_hrga);
                            //     }
                            // },
                            // format: "fixedPoint",
                            alignment: "center",
                            width: 80
                        },
                        {
                            caption: "Approved FO",
                            headerCellTemplate: function (container) {
                                container.append($("<div>Approved<br>FO</div>"));
                            },
                            dataField: "approved_fo",
                            calculateDisplayValue: function (rowData) { // combines display values
                                if (rowData.approved_fo === null) {
                                    return 'N/A';
                                } else {
                                    return rowData.approved_fo;
                                }
                            },
                            // calculateCellValue: function (rowData) {
                            //     if (rowData.approved_hrga === null) {
                            //         return 'N/A';
                            //     } else {
                            //         return Number(rowData.approved_hrga);
                            //     }
                            // },
                            // format: "fixedPoint",
                            alignment: "center",
                            width: 80
                        },
                        {
                            caption: "Approved Legal - Confirmed",
                            headerCellTemplate: function (container) {
                                container.append($("<div>Approved Legal-<br>Confirmed</div>"));
                            },
                            dataField: "approved_confirmed",
                            calculateDisplayValue: function (rowData) { // combines display values
                                if (rowData.approved_confirmed === null) {
                                    return '-';
                                } else {
                                    return rowData.approved_confirmed;
                                }
                            },
                            // calculateCellValue: function (rowData) {
                            //     if (rowData.approved_confirmed === null) {
                            //         return 'N/A';
                            //     } else {
                            //         return Number(rowData.approved_confirmed);
                            //     }
                            // },
                            // format: "fixedPoint",
                            alignment: "center",
                            width: 80
                        },
                        ]
                    },
                    {
                        caption: "Complete (Day/s)",
                        alignment: "center",
                        columns: [
                            {
                                headerCellTemplate: function (container) {
                                    container.append($("<div>Final Draft-<br>Completed</div>"));
                                },
                                dataField: "Final_Draft-Completed",
                                calculateCellValue: function (rowData) {
                                    if (rowData.finaldraft_completed === null) {
                                        return '-';
                                    } else {
                                        return Number(rowData.finaldraft_completed);
                                    }
                                },
                                format: "fixedPoint",
                                alignment: "center",
                                width: 100
                            },
                            {
                                headerCellTemplate: function (container) {
                                    container.append($("<div>Submitted-<br>Completed</div>"));
                                },
                                dataField: "Submitted-Completed",
                                calculateCellValue: function (rowData) {
                                    if (rowData.submit_completed === null) {
                                        return '-';
                                    } else {
                                        return Number(rowData.submit_completed);
                                    }
                                },
                                format: "fixedPoint",
                                alignment: "center",
                                width: 100
                            }
                        ]
                    },
                ],
                summary: {
                    groupItems: [
                        { column: "jumlah_spk", displayFormat: "{0}", summaryType: "sum", valueFormat: "fixedPoint", showInGroupFooter: false, alignByColumn: true },
                        { column: "aging_ho", "precision": 1, displayFormat: "{0}", summaryType: "avg", valueFormat: "fixedPoint", showInGroupFooter: false, alignByColumn: true },
                        { column: "aging_pabrik", "precision": 1, displayFormat: "{0}", summaryType: "avg", valueFormat: "fixedPoint", showInGroupFooter: false, alignByColumn: true },
                        { column: "total_aging", "precision": 1, displayFormat: "{0}", summaryType: "avg", valueFormat: "fixedPoint", showInGroupFooter: false, alignByColumn: true },

                    ],
                    calculateCustomSummary: function (options) {
                        //aging_ho
                        if (options.name === 'aging_ho') {
                            if (options.summaryProcess === 'start') {
                                options.totalValue = { count: 0, sum: 0 };
                            }
                            if (options.summaryProcess === 'calculate') {
                                options.totalValue.count += 1;
                                options.totalValue.sum += Number(options.value.department_head) + Number(options.value.division_head) + Number(options.value.procurement_ho) + Number(options.value.finance_controller) + Number(options.value.md) + Number(options.value.cfo) + Number(options.value.ceo_group);
                            }
                            if (options.summaryProcess === 'finalize') {
                                var nilai = options.totalValue.sum / options.totalValue.count;
                                options.totalValue = nilai;
                            }
                        }

                    }
                    //xx	
                },
                showBorders: true
            });

        },
        complete: function () {
            $(".overlay").remove();
        }
    });
}