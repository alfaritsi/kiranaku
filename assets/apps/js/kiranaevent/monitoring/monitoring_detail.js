$(document).ready(function () {
	
   	// date pitcker
    $('#filtertahun').datepicker({
        startView: 'year',
        minViewMode: "years",
        format: 'yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });

    // date pitcker
    $('#filterbulan').datepicker({
        startView: 'month',
        minViewMode: "months",
        format: 'm',
        changeMonth: false,
        changeYear: false,
        autoclose: true,
        // startDate: new Date()
    });
	// get_datas();
	// get_datas($("select[name='filterpabrik']").val());

    // $(document).on("click", " #filter ", function(e){
    //     //var tahun       = $("#tahun").val();
    //     var pabrik  = $("#filterpabrik").val();     	
    //     var tahun	= $("#filtertahun").val();
    //     get_datas(pabrik,tahun);
    // });

    $(document).on("change", " #filterpabrik, #filtertahun, #filterbulan ", function(e){
        //var tahun       = $("#tahun").val();
        var pabrik  = $("#filterpabrik").val();     	
        var tahun	= $("#filtertahun").val();
        var bulan 	= $("#filterbulan").val();
        get_datas(pabrik,tahun,bulan);
    });
});


function get_datas(pabrik=null,tahun=null,bulan=null){
	// $('#table_main').DataTable().destroy();
	var x = 1;	
    $.ajax({
        url: baseURL+'cctv/monitoring/get/data',
        type: 'POST',
        dataType: 'JSON',
        data: {
            
            pabrik 	: pabrik,
            tahun   : tahun,
            bulan 	: bulan
            
        },
        beforeSend: function () {
            var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
            $("body .overlay-wrapper").append(overlay);
        },
        success: function(data){
        	$(".table_main").DataTable().destroy();
        	var t   = $("#table_main").on( 'error.dt', function ( e, settings, techNote, message ) {
            console.log( 'An error has been reported by DataTables: ', message );
            } ).DataTable({
            	ordering : false,
		        searching : true,
		        columnDefs: [
		            {"className": "text-left", "targets": 0},
		            {"className": "text-left", "targets": 1},
		            {"className": "text-left", "targets": 2},
		            {"className": "text-left", "targets": 3},
		            {"className": "text-left", "targets": 4},
		            {"className": "text-left", "targets": 5},
		            {"className": "text-center", "targets": 6},
		            // {"className": "text-center", "targets": 7},
		            
		        ]
            });
            t.clear().draw();
            // console.log(data);
            $.each(data.var1,function(i,val) {
            	console.log(data.var1);
            	var months = [
						    'Januari', 'Februari', 'Maret', 'April', 'Mei',
						    'Juni', 'July', 'Augustus', 'September',
						    'Oktober', 'November', 'Desember'
						    ];

            	var pabrik 	= val.plant;
            	var area 	= val.sublok;
            	var dot 	= val.dot;
            	var periode	= "Week-"+val.week+" "+months[val.month-1]+" "+val.year;
            	var kondisi = val.condition;
            	var ket 	= '';
            	if(ket == null){
            		ket = ' ';
            	} else {
            		ket 	= val.note_monitoring;
            	}
            	// set condition 
            	if(val.condition == 'ON'){
           			kondisi = "<div class='label label-success'>"+val.condition+"</div>";
           		} else {
           			kondisi = "<div class='label label-danger'>"+val.condition+"</div>";	
           		}
            	// set view image
            	
            	if(val.attch.length > 47 && val.attch.match( /(.jpg|.png|.jpeg)/ )){
            		var thmb = val.attch;
            	} else {
            		var thmb = baseURL+'assets/file/cctv/default.png';
            	}
            	// <a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox="image'+vv.id_mdot+'" data-type="image"><i class="fa fa-search"></i></a>'
	           	var view 	= '<a target="#" data-fancybox="image'+dot+'_'+periode+'" data-type="image" href="'+thmb+'" class="fileinput-zoom"><img src="'+thmb+'" class="img-thumbnail" alt="" style="width: 100px; height: 100px;"></a>'
	            t.row.add( [
	            	i+1,
					// pabrik,
					area,
					dot,
					periode,
					kondisi,
					ket,
					view                        
	            ] ).draw( false );
                
                
                //refresh width
                t.columns.adjust().draw();
	        })
                 
        },
        complete: function () {
            $("body .overlay-wrapper .overlay").remove();
        }
    });
}
