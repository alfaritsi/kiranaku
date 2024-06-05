$(document).ready(function () {

	$('.my-datatable-extends-order').DataTable({
        columnDefs: [
            {"className": "text-right", "targets": 2},
            {"className": "text-right", "targets": 3},
            {"className": "text-right", "targets": 4},
            {"className": "text-right", "targets": 5},
            {"className": "text-right", "targets": 6},
            {"className": "text-right", "targets": 7},
            {"className": "text-right", "targets": 8},
            {"className": "text-right", "targets": 9},
            {"className": "text-right", "targets": 10},
            {"className": "text-right", "targets": 11},
            {"className": "text-right", "targets": 12},
            {"className": "text-right", "targets": 13},
        ],
        paging: false,
        "rowCallback": function( row, data ) {
            if ( data[1] == "% Upload" ) {
                var class_bulan;
                for (let i = 2; i < 14; i++) {
                    class_bulan = data[i] > 0 ? (data[i] < 100 ? 'yellowcell' : 'greencell' ) : 'redcell' ;
                    $('td:eq('+i+')', row).html(data[i]+" %");
                    $('td:eq('+i+')', row).addClass(class_bulan);
                }
            }
        },
        columns: [
            {
                title: 'Kode Plant',
            },
            {
                title: 'Notes',
                "sortable": false
            },
            {
                title: 'Jan',
                "sortable": false
            },
            {
                title: 'Feb',
                "sortable": false
            },
            {
                title: 'Mar',
                "sortable": false
            },
            {
                title: 'Apr',
                "sortable": false
            },
            {
                title: 'May',
                "sortable": false
            },
            {
                title: 'Jun',
                "sortable": false
            },
            {
                title: 'Jul',
                "sortable": false
            },
            {
                title: 'Aug',
                "sortable": false
            },
            {
                title: 'Sep',
                "sortable": false
            },
            {
                title: 'Oct',
                "sortable": false
            },
            {
                title: 'Nov',
                "sortable": false
            },
            {
                title: 'Dec',
                "sortable": false
            },
        ],
        rowsGroup: [
            0
        ],
    });
    
    $.ajax({
        url: baseURL + "accounting/transaction/get_data/persentase",
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            if(data) {

                var t = $('.my-datatable-extends-order').DataTable();
                t.clear().draw();

                $.each(data, function(i,j){
                        t.row.add([
                            j.KodePlant,
                            j.Notes,
                            j.January,
                            j.February,
                            j.Maret,
                            j.April,
                            j.Mei,
                            j.Juni,
                            j.July,
                            j.Agustus,
                            j.September,
                            j.Oktober,
                            j.November,
                            j.Desember
                        ]).draw(false);                      
                });
                
            }
            
        }
    });
	


	$(document).on("click", ".btn-filter", function () {	
        
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);

            $.ajax({
                url: baseURL + "accounting/transaction/get_data/persentase",
                type: 'POST',
                dataType: 'JSON',
                data: {
                    filteryear: $("select[name='filteryear']").val(),
                    filtertype: $("select[name='filtertype']").val()
                },
                success: function (data) {
                   
                    if(data) {        
                        var t = $('.my-datatable-extends-order').DataTable();
                        t.clear().draw();
        
                        $.each(data, function(i,j){
        
                            t.row.add([
                                j.KodePlant,
                                j.Notes,
                                j.January,
                                j.February,
                                j.Maret,
                                j.April,
                                j.Mei,
                                j.Juni,
                                j.July,
                                j.Agustus,
                                j.September,
                                j.Oktober,
                                j.November,
                                j.Desember
                            ]).draw(false); 
                
                        });
                        
                    } 
                    
                    $("input[name='isproses']").val(0);
                    
                },
                complete: function () {
                    $("input[name='isproses']").val(0);
                }
            });
            
        } else {
            kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
        }
	});
});
