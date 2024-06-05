$(document).ready(function(){
	$('.my-datatable-extends-order').DataTable({
		ordering : true,
		scrollCollapse: true,
		scrollY: false,
		scrollX : true,
		bautoWidth: false,
		"paging": false,
		columnDefs: [
			{"className": "text-right", "targets": 4},
			{"className": "text-right", "targets": 5},
		]
	});

	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate();
		if(empty_form == 0){
			var isproses 		= $("input[name='isproses']").val();
			if(isproses == 0){
				var qty_prod    = $("input[name='jml_prod_SIR']").val().replace(/,/g , "");
				var formData    = new FormData($(".form-calculate-pcs")[0]);
				let total_exclude = 0;
				let total_exclude_perKG = 0;
				var COA_exclude = ["4418001","4402002","4402003"];
				var COA_unique = [];
				var idx = 0;

				$.ajax({
					url: baseURL+'pcs/calculate/get_data/simulation',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					beforeSend: function () {
						var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
						$("body .overlay-wrapper").append(overlay);
					},
					success: function(data){
						// console.log(data);
						var gruping = $("#grouping").val() == 'standart' ? 'grup1' : 'grup2';
						$('.my-datatable-extends-order').DataTable().destroy();
						var t   = $('.my-datatable-extends-order').DataTable({
							ordering : true,
							scrollCollapse: true,
							scrollY: false,
							scrollX : true,
							bautoWidth: false,
							"paging": false,
							dom: 'Bfrtip',
							buttons: [
								{
									extend: 'excelHtml5',
									text: '<i class="fa fa-file-excel-o"></i>&nbsp; Export to Excel',
									title: 'Production Cost Simulation',
									download: 'open',
									orientation:'landscape',
									exportOptions: {
										columns: (gruping == "grup1" ? [0,1,2,5,6] : [0,1,2,3,4,5,6])
									},
									footer: true
								}
							],
							columnDefs: [
								{ "className": "text-right", "targets": 0, "visible": false },
								{ "className": "text-right", "targets": 3, "visible": (gruping == "grup1" ? false : true) },
								{ "className": "text-right", "targets": 4, "visible": (gruping == "grup1" ? false : true) },
								{ "className": "text-right", "targets": 5 },
								{ "className": "text-right", "targets": 6 },
								{ "className": "text-right", "targets": 7, "visible": false },
								{ "className": "text-right", "targets": 8, "visible": false },
								{ "className": "text-right", "targets": 9, "visible": false },
								{ "className": "text-right", "targets": 10, "visible": false },
							],
                            "rowCallback": function ( row, data) {
								// console.log(data);
                                if (COA_unique.includes(data[1]+data[0]+data[9]+data[10]) === false && COA_exclude.includes(data[1]) === false) {
                                    idx++;
                                    // console.log(idx);
                                    COA_unique.push(data[1]+data[0]+data[9]+data[10]);
                                    // $('td', row).eq(5).addClass('highlight');
									total_exclude 		+= +(data[5].replace(/[\$,]/g, '') == null || data[5].replace(/[\$,]/g, '') == "" ? 0 : data[5].replace(/[\$,]/g, ''));
                                    total_exclude_perKG += +(data[6].replace(/[\$,]/g, '') == null || data[6].replace(/[\$,]/g, '') == "" ? 0 : data[6].replace(/[\$,]/g, ''));
                                    // console.log(data[1] + " => " + data[5].replace(/[\$,]/g, '') + " => " + data[6].replace(/[\$,]/g, '') + " => " + total_exclude + " => " + total_exclude_perKG);
                                }
                            },
							"footerCallback" : function ( row, data, start, end, display ) {
								var api = this.api(), data;

								// Remove the formatting to get integer data for summation
								var intVal = function ( i ) {
									return typeof i === 'string' ?
										i.replace(/[\$,]/g, '')*1 :
										typeof i === 'number' ?
											i : 0;
								};

								// Total over all pages
								total_biaya = api
									.column(5, { page: 'current'} )
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );

								// Total over all pages
								total_biaya_perkg = api
									.column(6, { page: 'current'} )
									.data()
									.reduce( function (a, b) {
										return intVal(a) + intVal(b);
									}, 0 );

								// Update footer
								$( api.column(5).footer() ).html(
									numberWithCommas(total_biaya.toFixed(2))
								);

								// Update footer
								$( api.column(6).footer() ).html(
									numberWithCommas(total_biaya_perkg.toFixed(2))
								);

                                // console.log("footer");
                                var secondRow = $(row).next()[0];
                                $("th",secondRow).eq(1).html(numberWithCommas(total_exclude.toFixed(2)));
                                $("th",secondRow).eq(2).html(numberWithCommas(total_exclude_perKG.toFixed(2)));

							},
							"drawCallback": function ( settings ) {
								var api     = this.api();
								var rows    = api.rows( {page:'current'} ).nodes();
								var last    = null;

								api.column(0, {page:'current'} ).data().each( function ( group, i ) {
									if ( last !== group ) {
                                        // console.log(api.rows( {page:'current'} ).data()[i]);
                                        var data	= api.rows( {page:'current'} ).data()[i];
                                        var clmn 	= (gruping == "grup1" ? 2 : 4);
										var output  = '<tr class="group" id="'+group.replace(/[&\/\\#,+()$~%.'":*?<>{}\s]/g, '')+'">';
										output     += '<td colspan="'+clmn+'">'+group+'</td>';
										output     += '<td class="summ_COA text-right">'+data[7]+'</td>';
										output     += '<td class="summ_COA_perKG text-right">'+data[8]+'</td>';
										output     += '</tr>';
										$(rows).eq( i ).before(output);

										last = group;
									}
								});
							}
						});
						t.clear().draw();



						$.each(data, function(i, v){
							t.row.add( [
								v[gruping],
								v.SAKNR,
								v.GLTXT,
								(v.jumlah > 0 ) ? numberWithCommas(v.jumlah) : "",
								(v.jumlah > 0 ) ? v.satuan_jumlah : "",
								numberWithCommas((v.COA == null ? 0 : v.COA)),
								numberWithCommas((v.COA_perKG == null ? 0 : v.COA_perKG)),
								numberWithCommas((v.summ_COA == null ? 0 : v.summ_COA)),
								numberWithCommas((v.summ_COA_perKG == null ? 0 : v.summ_COA_perKG)),
								v.jns_formula,
								v.norma
							] ).draw( false );
						});

                        t.on( 'search.dt', function () {
                            COA_unique = [];
                            total_exclude = 0;
                            total_exclude_perKG = 0;
                            idx = 0;
                        } );
					},
					complete: function () {
						$("body .overlay-wrapper .overlay").remove();
                        COA_unique = [];
                        total_exclude = 0;
                        total_exclude_perKG = 0;
                        idx = 0;
					}
				});
			}else{
				kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
			}
		}
		e.preventDefault();
		return false;
	});

	$(document).on("keyup", ".cek_max", function(e){
		var nilai = $(this).val().replace(/,/g, "");

		if(nilai>20000){
			alert('Input Jumlah produksi SIR Maks 20.000 Ton');
			$(this).val(20000);
		}
	});

	$("#bulan").datepicker().on("changeDate", function () {
		var bulan = $("#bulan").val();
		var plant = $("select[name='plant']").val();

		get_data_listrik(bulan,plant);
	});

	$(document).on("change", "select[name='plant']", function(e) {
		var bulan = $("#bulan").val();
		var plant = $("select[name='plant']").val();
		get_data_listrik(bulan,plant);
	});
});


function get_data_listrik(bulan,plant){
	if(bulan && plant) {
		$.ajax({
			url: baseURL + 'pcs/calculate/get_data/listrik',
			type: 'POST',
			dataType: 'JSON',
			data: {
				bulan: bulan,
				plant: plant
			},
			beforeSend: function(){
				$("input[name='listrik_lwbp']").val('');
				$("input[name='listrik_wbp']").val('');
			},
			success: function (data) {
				$.each(data, function (i, v) {
					if (v.lwbp != null) {
						$("input[name='listrik_lwbp']").val(numberWithCommas(parseFloat(v.lwbp)));
					} else {
						$("input[name='listrik_lwbp']").val('');
					}
					if (v.wbp != null) {
						$("input[name='listrik_wbp']").val(numberWithCommas(parseFloat(v.wbp)));
					} else {
						$("input[name='listrik_wbp']").val('');
					}
				});

			}
		});
	}
}
