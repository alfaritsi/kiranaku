$(document).ready(function(){
	//filter buyer
	$.ajax({
		url: baseURL+'spot/master/get/buyer',
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			var no = 0;
			var list = '';
			list += '<option value="0">Pilih Buyer</option>';
			$.each(data, function(i,v){
				list += '<option value="'+v.NMBYR+'">'+v.KUNNR+' - '+v.NMBYR+'</option>';
			});
			$('#buyer').html(list);
		}
	});
	
	//show
	var formData = new FormData($(".form-production-cost-simulasi")[0]);
	$.ajax({
		url: baseURL+'spot/transaksi/get/cost',
		type: 'POST',
		dataType: 'JSON',
		data: formData,
		contentType: false,
		cache: false,
		processData: false,
		success: function(data){

			var nil	= '';
				nil	+='<div class="row">';
				nil	+=	'<div class="col-sm-12">';
				nil	+=		'<div class="box box-success">';
				nil	+=			'<div class="box-header">';
				nil	+=				'<h3 class="box-title"><strong>Hasil Perhitungan Simulasi Penjualan SPOT</strong></h3>';
				nil	+=				'<div class="btn-group pull-right">';
				nil	+=					'<button type="button" class="btn btn-primary" name="action_btn">Simulasi</button>';
				nil	+=					'<button type="button" class="btn" name="action_btn_list">List</button>';
				nil	+=				'</div>';
				nil	+=			'</div>';
			
				nil	+=			'<div class="box-body">';
				nil	+=			'<div class="row">';
				nil	+=			'	<div class="col-sm-3">';
				nil	+=			'		<div class="form-group">';
				nil	+=			'        	<label> Plant: </label>';
				nil	+=			'        	<select class="form-control select2modal" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Factory">';
				$.each(data, function(a,b){
					nil	+=		'				<option value="'+b.plant+'">'+b.plant+'</option>';	
				});
				nil	+=			'          	</select>';
				nil	+=			'    	</div>';
				nil	+=			'	</div>';
				nil	+=			'</div>';
				nil	+=			'</div>	';				
				
				nil	+=			'<div class="box-body">';
				nil	+=				'<table id="example" class="table table-bordered table-striped table-modals">';
				nil	+=					'<thead>';
				nil	+=						'<th>Factory</th>';
				nil	+=						'<th>Plant</th>';
				nil	+=						'<th>Harga Modal SPOT<br>(IDR/KG)</th>';
				// add ayy
				nil	+=						'<th>Harga Deal Pembelian<br>(IDR/KG)</th>';
				
				nil	+=						'<th>Selling Price<br>(USC/KG)</th>';
				nil	+=						'<th>Selling Price<br>(IDR/KG)</th>';
				nil	+=						'<th>Prod Cost<br>(IDR/KG)</th>';
				nil	+=						'<th>Trucking Cost<br>(IDR/KG)</th>';
				nil	+=						'<th>Total Cost<br>(IDR/KG)</th>';
				nil	+=						'<th>Carry Cost<br>(IDR/KG)</th>';
				nil	+=						'<th>Margin<br>(IDR/KG)</th>';
				nil	+=						'<th>OCP</th>';
				nil	+=						'<th>Breakeven Price<br>(USC/KG)</th>';
				nil	+=						'<th>Plant Name</th>';
				nil	+=						'<th>List Buyer</th>';
				nil	+=						'<th>Currency Rate</th>';
				nil	+=						'<th>Pol Value</th>';
				nil	+=						'<th>LIBOR Rate</th>';
				nil	+=						'<th>Interest Rate</th>';
				nil	+=						'<th>Days</th>';
				nil	+=						'<th>Prod Cost Tipe</th>';
				nil	+=						'<th>Trucking Cost Value</th>';
				nil	+=					'</thead>';
				nil	+=					'</tbody>';
				nil	+=				'</table>';
				nil	+=			'</div>';
				nil	+=		'</div>';
				nil	+=	'</div>';
				nil	+='</div>';
			$("#show_simulasi").html(nil);	
		},
		complete: function () {
			$('.select2modal').select2();
			// table = $('.table-modals').DataTable({
			table = $('.table-modals').DataTable({
				"columnDefs": [
					// { "visible": false, "targets": 11 },
					{ "visible": false, "targets": 12 },
					{ "visible": false, "targets": 13 },
					{ "visible": false, "targets": 14 },
					{ "visible": false, "targets": 15 },
					{ "visible": false, "targets": 16 },
					{ "visible": false, "targets": 17 },
					{ "visible": false, "targets": 18 },
					{ "visible": false, "targets": 19 },
					{ "visible": false, "targets": 20 }
				],						
				paging:   false,
				bInfo: false,
				ordering : true,
				scrollCollapse: true,
				scrollY: false,
				scrollX : true,
				bautoWidth: false,
				select: true
			});
			$('.table-modals tbody').on( 'click', 'tr', 'td:not(.exclude)', function () {
				$(this).toggleClass('selected');
				var selected 	= table.rows('.selected');
				console.log(selected.data());
				var plant_selected = [];
				selected.data().map(function(value){
					plant_selected.push(value[1]);
				});
				$("input[name='plant_selected']").val(plant_selected.join());
			} );	
			
			//hide row by dbclick
			$('.table-modals tbody').on( 'dblclick', 'tr', function () {
				$(this).toggleClass('hide');
			} );					
		}		
		
	});
	
	//last input
	$.ajax({
		url: baseURL+'spot/transaksi/get/last',
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			$.each(data, function(i,v){
				$("input[name='libor_rate']").val(numberWithCommas(parseFloat(v.libor_rate).toFixed(2)));
				$("input[name='interest_rate']").val(numberWithCommas(parseFloat(v.interest_rate).toFixed(2)));
				$("input[name='interest']").val(numberWithCommas(parseFloat(v.interest).toFixed(2)));
			});
		}
	});
	//currency rate
	$.ajax({
		url: baseURL+'spot/transaksi/get/currency',
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			$.each(data, function(i,v){
				$("input[name='currency_rate']").val(numberWithCommas(parseFloat(v.rate).toFixed(2)));
			});
		}
	});
	//filter port
	$.ajax({
		url: baseURL+'spot/master/get/port',
		type: 'POST',
		dataType: 'JSON',
		success: function(data){
			var no = 0;
			var value = '';
			$.each(data, function(i,v){
				no = no+1;
				value += '<div class="form-group">';
				value += 	'<label for="no_urut">'+v.name+'</label>';
				value += 	'<div class="input-group">';
				if(no==1){
					value += 	'<input type="text" class="form-control angka text-right" name="port_'+no+'" id="port_'+no+'" required="required">';	
				}else{
					value += 	'<input type="text" class="form-control text-right" name="port_'+no+'" id="port_'+no+'" disabled>';	
				}
				value += 	'<span class="input-group-addon">USC / KG</span>';
				value += 	'</div>';
				value += '</div>';
			});
			$('#filter_port').html(value);
		}
	});
	//keyup port
    $(document).on("keyup", "#port_1", function (e) {
		var port_1	= $("#port_1").val();
		$.ajax({
			url: baseURL+'spot/master/get/port',
			type: 'POST',
			dataType: 'JSON',
			success: function(data){
				var no = 0;
				var value = '';
				$.each(data, function(i,v){
					var nilai = parseFloat(port_1.replace(",",""))-parseFloat(v.selisih);
					no = no+1;
					if(no>1){
						$("input[name='port_"+no+"']").val(numberWithCommas(nilai));
					}
				});
			}
		});		
    });
	
    //sales date pitcker
    $('#sales').datepicker({
        startView: 'year',
        minViewMode: "months",
        format: 'mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        startDate: '+0m'
    });
	
	//keyup
    $(document).on("keyup", "#libor_rate, #interest_rate", function (e) {
		var libor_rate 		= $("#libor_rate").val();
		var interest_rate 	= $("#interest_rate").val();
		if((libor_rate!='')&&(interest_rate!='')){
			var interest	= parseFloat(libor_rate)+parseFloat(interest_rate);
			$("input[name='interest']").val(interest);
		}
    });
	
	//change sales
	$(document).on("change", "#sales", function (e) {
		var tanggal_awal = '01.'+$("#sales").val();
		var tanggal_sekarang = moment().format('DD/MM/YYYY');

		//rubah fortmat tanggal ke moment
		var tanggal_awal_moment = moment(tanggal_awal,'DD/MM/YYYY');
		var tanggal_sekarang_moment = moment(tanggal_sekarang,'DD/MM/YYYY');

		//mencari selisih per tahun, per bulan dan per hari
		var selisih_hari = tanggal_awal_moment.diff(tanggal_sekarang_moment,'days');
		$("input[name='days']").val(selisih_hari-31);
		
    });
	//change port
    $(document).on("change", "#port", function (e) {
		var port			= $(this).val();
		var port_1			= $("#port_1").val();
		var currency_rate 	= $("#currency_rate").val();
		var plant			= $(this).data("plant");
		var total_cost		= $(this).data("total_cost");
		var carry_cost		= $(this).data("carry_cost");
		$.ajax({
    		url: baseURL+'spot/master/get/pol',
			type: 'POST',
			dataType: 'JSON',
			data: {
				name	: port
			},
			success: function(data){
				$.each(data, function(i,v){
					var selling_price_usc = (parseFloat(port_1.replace(",",""))-parseFloat(v.selisih));
					var selling_price	= (parseFloat(port_1.replace(",",""))-parseFloat(v.selisih))*parseFloat(currency_rate.replace(",","")).toFixed(0)/100;
					var margin			= selling_price-total_cost-carry_cost;
					
					$('#label_selling_price_'+plant).html(numberWithCommas(parseFloat(selling_price).toFixed(0)));
					$('#label_margin_'+plant).html(numberWithCommas(parseFloat(margin).toFixed(0)));
					
					$("input[name='selling_price_"+plant+"']").val(selling_price);	
					$("input[name='margin_"+plant+"']").val(margin);	

					$("input[name='pol_"+plant+"']").val(v.nama_port);	
					$("input[name='pol_value_"+plant+"']").val(selling_price_usc);	
					$("input[name='selling_price_usc_"+plant+"']").val(selling_price_usc);	
					
				});
				
			}
		});		

    });
	
	//keyup trucking cost
    $(document).on("keyup", "#trucking_cost", function (e) {
		if($(this).val()!=''){
			var trucking_cost	= parseFloat($(this).val().replace(",",""));
		}else{
			var trucking_cost	= 0;
		}
		var plant			= $(this).data("plant");
		var total_cost		= $(this).data("total_cost");
		var selling_price	= $(this).data("selling_price");
		var carry_cost		= $(this).data("carry_cost");
		
		var total_cost_new	= total_cost+trucking_cost;
		var margin_new		= selling_price-total_cost_new-carry_cost;
		$('#label_trucking_cost_value_'+plant).html(trucking_cost);
		$('#label_total_cost_'+plant).html(numberWithCommas(parseFloat(total_cost_new).toFixed(0)));
		$('#label_margin_'+plant).html(numberWithCommas(parseFloat(margin_new).toFixed(0)));
		
		$('#total_cost_'+plant).val(parseFloat(total_cost_new).toFixed(0)); 
		$('#margin_'+plant).val(parseFloat(margin_new).toFixed(0));
		// console.log($('#label_margin_'+plant).html());
    });
	
	// //change buyer
    // $(document).on("change", "#buyer", function (e) {
		// var NMBYR	= $(this).val();
		// $.ajax({
    		// url: baseURL+'spot/master/get/buyer',
			// type: 'POST',
			// dataType: 'JSON',
			// data: {
				// NMBYR	: NMBYR
			// },
			// success: function(data){
				// $.each(data, function(i,v){
					// $("input[name='buyer']").val(NMBYR+' - '+v.NMBYR);
					// $("input[name='buyer_detail']").val(v.NMBYR);
				// });
			// }
		// });		
    // });
	
	$(document).on("click", "button[name='action_btn_cancel']", function(e){
		window.location.href = baseURL+'spot/transaksi/sales';
    });
	

	var table = null;
	$(document).on("click", "button[name='action_btn_list']", function(e){
		
			$.ajax({
				url: baseURL+'spot/transaksi/get/simulasi',
				type: 'POST',
				dataType: 'JSON',
				// data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data){
	
					var nil	= '';
						nil	+='<div class="row">';
						nil	+=	'<form role="form" class="form-production-cost-list" name="form-production-cost-list" enctype="multipart/form-data">';
						nil	+=	'<input type="hidden" class="form-control" name="plant_selected" id="plant_selected">';						
						nil	+=	'<input type="hidden" class="form-control" name="id_simulate_selected" id="id_simulate_selected">';						
						nil	+=	'<div class="col-sm-12">';
						nil	+=		'<div class="box box-success">';
						nil	+=			'<div class="box-header">';
						nil	+=				'<h3 class="box-title"><strong>List Perhitungan Simulasi Penjualan SPOT</strong></h3>';
						nil	+=				'<div class="btn-group pull-right">';
						nil	+=					'<button type="button" class="btn" name="action_btn">Simulasi</button>';
						nil	+=					'<button type="button" class="btn btn-primary" name="action_btn_list">List</button>';
						nil	+=				'</div>';
						nil	+=			'</div>';
					
						nil	+=			'<div class="box-body">';
			          	nil	+=			'<div class="row">';
			          	nil	+=			'	<div class="col-sm-3">';
			            nil	+=			'		<div class="form-group">';
				        nil	+=			'        	<label> Plant: </label>';
				        nil	+=			'        	<select class="form-control select2modal" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Factory">';
						if(data.length!=0){
							var arr_plant 		= data[0].list_plant.slice(0, -1).split(",");
							$.each(arr_plant, function(x, y){
								plant 	 = y.split("|");
								nil	 	+= 					'<option value="'+ plant[1]+'">' + plant[1]+'</option>';
							});
						}
				        nil	+=			'          	</select>';
				        nil	+=			'    	</div>';
			            nil	+=			'	</div>';
			          	nil	+=			'	<div class="col-sm-3">';
			            nil	+=			'		<div class="form-group">';
				        nil	+=			'        	<label> Buyer: </label>';
				        nil	+=			'        	<select class="form-control select2modal" multiple="multiple" id="filter_buyer" name="filter_buyer[]" style="width: 100%;" data-placeholder="Pilih Buyer">';
						if(data.length!=0){
							var arr_buyer 		= data[0].list_buyer.slice(0, -1).split(",");
							$.each(arr_buyer, function(x, y){
								buyer 	 = y.split("|");
								nil	 	+= 					'<option value="'+ buyer[1]+'">' + buyer[1]+'</option>';
							});
						}
				        nil	+=			'          	</select>';
				        nil	+=			'    	</div>';
			            nil	+=			'	</div>';
						nil	+=			'	<div class="col-sm-6">';
						nil	+=			'		<div class="btn-group pull-right">';
						nil	+=			'			<button type="button" class="btn btn-warning" name="action_btn_delete_selected">Delete</button>';						
						nil	+=			'			<button type="button" class="btn btn-primary" name="action_btn_confirm">Confirmation</button>';						
						nil	+=			'		</div>';
						nil	+=			'	</div>';
						
		            	nil	+=			'</div>';
						nil	+=			'</div>	';				
						
						nil	+=			'<div class="box-body">';
						nil	+=				'<table id="example" class="table table-bordered table-striped table-modals">';
						nil	+=					'<thead>';
						nil	+=						'<th>Factory</th>';
						nil	+=						'<th>Plant</th>';
						nil	+=						'<th>Harga Modal SPOT<br>(IDR/KG)</th>';
						nil	+=						'<th>Selling Price<br>(USC/KG)</th>';
						nil	+=						'<th>Selling Price<br>(IDR/KG)</th>';
						nil	+=						'<th>Prod Cost<br>(IDR/KG)</th>';
						nil	+=						'<th>Trucking Cost<br>(IDR/KG)</th>';
						nil	+=						'<th>Total Cost<br>(IDR/KG)</th>';
						nil	+=						'<th>Carry Cost<br>(IDR/KG)</th>';
						nil	+=						'<th>Margin<br>(IDR/KG)</th>';
						nil	+=						'<th>OCP</th>';
						nil	+=						'<th>Breakeven Price<br>(USC/KG)</th>';
						nil	+=						'<th>Plant Name</th>';
						nil	+=						'<th>List Buyer</th>';
						nil	+=						'<th>Currency Rate</th>';
						nil	+=						'<th>Pol Value</th>';
						nil	+=						'<th>LIBOR Rate</th>';
						nil	+=						'<th>Interest Rate</th>';
						nil	+=						'<th>Days</th>';
						nil	+=						'<th>Prod Cost Tipe</th>';
						nil	+=						'<th>Trucking Cost Value</th>';
						nil	+=						'<th>Selling Price USC Value</th>';
						nil	+=						'<th>Pol</th>';
						nil	+=						'<th>Shipment Periode</th>';
						nil	+=						'<th>Tanggal Buat</th>';
						nil	+=						'<th>SICOM Price</th>';
						nil	+=						'<th>Buyer</th>';
						nil	+=						'<th>Last Nomor</th>';
						nil	+=						'<th>Buyer Detail</th>';
						nil	+=						'<th>List DC</th>';
						nil	+=						'<th>ID Simulate</th>';
						nil	+=					'</thead>';
						nil	+=					'<tbody>';
						$.each(data, function(i,v){
							nil	 	+= 				'<tr class="'+v.plant+'" data-user="'+v.plant+'">';
							nil	 	+= 					'<td>'+v.factory+'</td>';
							nil	 	+= 					'<input type="hidden" name="factory_'+v.plant+'" value="'+v.factory+'">';
							nil	 	+= 					'<td>'+v.plant+'</td>';
							nil	 	+= 					'<input type="hidden" name="plant_'+v.plant+'" value="'+v.plant+'">';
							nil	 	+= 					'<td>'+numberWithCommas(parseFloat(v.mtd_price).toFixed(0))+'</td>';
							nil	 	+= 					'<td>'+v.pol+' - '+numberWithCommas(parseFloat(v.selling_price_usc).toFixed(2))+'</td>';
							nil	 	+= 					'<td>'+numberWithCommas(parseFloat(v.selling_price).toFixed(0))+'</td>';
							nil	 	+= 					'<td>'+numberWithCommas(parseFloat(v.prod_cost).toFixed(0))+'</td>';
							nil	 	+= 					'<td>'+numberWithCommas(parseFloat(v.trucking_cost).toFixed(0))+'</td>';
							nil	 	+= 					'<td>'+numberWithCommas(parseFloat(v.total_cost).toFixed(0))+'</td>';
							nil	 	+= 					'<td>'+numberWithCommas(parseFloat(v.carry_cost).toFixed(0))+'</td>';
							nil	 	+= 					'<td>'+numberWithCommas(parseFloat(v.margin).toFixed(0))+'</td>';
							nil	 	+= 					'<input type="hidden" name="margin_'+v.plant+'" value="'+v.margin+'">';
							nil	 	+= 					'<td>'+numberWithCommas(parseFloat(v.ocp).toFixed(2))+'</td>';
							nil	 	+= 					'<td>'+numberWithCommas(parseFloat(v.breakeven_price).toFixed(2))+'</td>';
							nil	 	+= 					'<td>'+v.plant+'</td>';
							nil	 	+= 					'<td>'+v.list_buyer+'</td>';
							nil	 	+= 					'<td>'+parseFloat(v.cur_rate).toFixed(2)+'</td>';
							nil	 	+= 					'<td>'+parseFloat(v.pol_value).toFixed(2)+'</td>';
							nil	 	+= 					'<td>'+parseFloat(v.libor_rate).toFixed(2)+'</td>';
							nil	 	+= 					'<td>'+parseFloat(v.interest_rate).toFixed(2)+'</td>';
							nil	 	+= 					'<td>'+v.days+'</td>';
							nil	 	+= 					'<td>'+v.prod_cost_type+'</td>';
							nil	 	+= 					'<td>'+parseFloat(v.trucking_cost).toFixed(2)+'</td>';
							nil	 	+= 					'<td>'+parseFloat(v.selling_price_usc).toFixed(2)+'</td>';
							nil	 	+= 					'<td>'+v.pol+'</td>';
							nil	 	+= 					'<td>'+v.shipment_periode+'</td>';
							nil	 	+= 					'<input type="hidden" name="shipment_periode_'+v.plant+'" value="'+v.shipment_periode+'">';
							nil	 	+= 					'<td>'+v.tanggal_buat+'</td>';
							nil	 	+= 					'<td>'+v.sicom+'</td>';
							nil	 	+= 					'<td>'+v.buyer+'</td>';
							nil	 	+= 					'<input type="hidden" name="buyer_'+v.plant+'" value="'+v.buyer+'">';
							nil	 	+= 					'<td>'+parseFloat(v.last_nomor)+'</td>';
							nil	 	+= 					'<td>'+v.buyer_detail+'</td>';
							nil	 	+= 					'<td>'+v.list_dc+'</td>';
							nil	 	+= 					'<td>'+v.id_simulate+'</td>';
							nil	 	+= 				'</tr>';
						});
						
						nil	+=					'</tbody>';
						nil	+=				'</table>';
						nil	+=			'</div>';
						nil	+=		'</div>';
						nil	+=	'</div>';
						nil	+=	'</form>';
						nil	+='</div>';
					$("#show_simulasi").html(nil);	
				},
				complete: function () {
					$('.select2modal').select2();
					// table = $('.table-modals').DataTable({
					table = $('.table-modals').DataTable({
						"columnDefs": [
							// { "visible": false, "targets": 11 },
							{ "visible": false, "targets": 2 },
							{ "visible": false, "targets": 4 },
							{ "visible": false, "targets": 5 },
							{ "visible": false, "targets": 6 },
							{ "visible": false, "targets": 7 },
							{ "visible": false, "targets": 8 },
							{ "visible": false, "targets": 10 },
							{ "visible": false, "targets": 11 },
							{ "visible": false, "targets": 12 },
							{ "visible": false, "targets": 13 },
							{ "visible": false, "targets": 14 },
							{ "visible": false, "targets": 15 },
							{ "visible": false, "targets": 16 },
							{ "visible": false, "targets": 17 },
							{ "visible": false, "targets": 18 },
							{ "visible": false, "targets": 19 },
							{ "visible": false, "targets": 20 },
							{ "visible": false, "targets": 21 },
							{ "visible": false, "targets": 22 },
							// { "visible": false, "targets": 23 },
							{ "visible": false, "targets": 24 },
							{ "visible": false, "targets": 25 },
							{ "visible": false, "targets": 26 },
							{ "visible": false, "targets": 27 },
							// { "visible": false, "targets": 28 },
							{ "visible": false, "targets": 29 },
							{ "visible": false, "targets": 30 }
						],						
						paging:   false,
						bInfo: false,
						ordering : true,
						scrollCollapse: true,
						scrollY: false,
						scrollX : true,
						bautoWidth: false,
						select: true,
						pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 20,
						initComplete: function () {
							// var row = table.row ($(this).closest('tr'));
							// var data = row.data();
							// data.margin = margin_new;
							// row.invalidate().draw(false);
							
							$(document).on("change", "#trucking_cost", function (e) {
								if($(this).val()!=''){
									var trucking_cost	= parseFloat($(this).val().replace(",",""));
								}else{
									var trucking_cost	= 0;
								}
								var plant			= $(this).data("plant");
								var total_cost		= $(this).data("total_cost");
								var selling_price	= $(this).data("selling_price");
								var carry_cost		= $(this).data("carry_cost");
								
								var total_cost_new	= total_cost+trucking_cost;
								var margin_new		= selling_price-total_cost_new-carry_cost;
								$('#label_trucking_cost_value_'+plant).html(trucking_cost);
								$('#label_total_cost_'+plant).html(numberWithCommas(parseFloat(total_cost_new).toFixed(0)));
								
								$('#total_cost_'+plant).val(parseFloat(total_cost_new).toFixed(0)); 
								$('#margin_'+plant).val(parseFloat(margin_new).toFixed(0));
								
								// $('#label_margin_'+plant).html(numberWithCommas(parseFloat(margin_new).toFixed(0)));
								// console.log($('#label_margin_'+plant).html());
								var row = table.row ($(this).closest('tr'));
								var data = row.data();
								data.total_cost = total_cost_new;
								data.margin = margin_new;
								data.trucking_cost = trucking_cost;
								row.invalidate().draw(false);
							});
						}
					});
					$('.table-modals tbody').on( 'click', 'tr', 'td:not(.exclude)', function () {
						$(this).toggleClass('selected');
						var selected 	= table.rows('.selected');
						var plant_selected = [];
						selected.data().map(function(value){
							plant_selected.push(value[1]);
						});
						$("input[name='plant_selected']").val(plant_selected.join());
						
						var id_simulate_selected = [];
						selected.data().map(function(value){
							id_simulate_selected.push(value[30]);
						});
						$("input[name='id_simulate_selected']").val(id_simulate_selected.join());
					} );	
					
					//hide row by dbclick
					$('.table-modals tbody').on( 'dblclick', 'tr', function () {
						$(this).toggleClass('hide');
					} );					
				}
			});
		e.preventDefault();
		return false;

    });	
	
	var table = null;
	$(document).on("click", "button[name='action_btn']", function(e){
		var port_1 			= $("#port_1").val();
		var currency_rate 	= $("#currency_rate").val();
		var type 			= $("#type").val();
		var years 			= $("#years").val();
		var days 			= $("#days").val();
		var libor_rate 		= $("#libor_rate").val();
		var interest_rate 	= $("#interest_rate").val();
		var interest 		= $("#interest").val();
		var sales 			= $("#sales").val();
		var sicom			= $("#sicom").val();
		var buyer 			= $("#buyer").val();
		
		var empty_form = validate();
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
			$("input[name='isproses']").val(1);
			var formData = new FormData($(".form-production-cost-simulasi")[0]);
			$.ajax({
				url: baseURL+'spot/transaksi/get/cost',
				type: 'POST',
				dataType: 'JSON',
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data){
	
					var nil	= '';
						nil	+='<div class="row">';
						nil	+=	'<form role="form" class="form-production-cost-list" name="form-production-cost-list" enctype="multipart/form-data">';
						nil	+=	'<input type="hidden" class="form-control" name="plant_selected" id="plant_selected">';						
						nil	+=	'<div class="col-sm-12">';
						nil	+=		'<div class="box box-success">';
						nil	+=			'<div class="box-header">';
						nil	+=				'<h3 class="box-title"><strong>Hasil Perhitungan Simulasi Penjualan SPOT</strong></h3>';
						nil	+=				'<div class="btn-group pull-right">';
						nil	+=					'<button type="button" class="btn btn-primary" name="action_btn">Simulasi</button>';
						nil	+=					'<button type="button" class="btn" name="action_btn_list">List</button>';
						// nil	+=					'<button type="button" class="btn btn-warning" name="action_btn_list">List</button>';
						// nil	+=					'<button type="button" class="btn btn-primary" name="action_btn_save_selected">Save</button>';
						// nil	+=					'<button type="button" class="btn btn-primary" name="action_btn_confirm">Confirmation</button>';
						nil	+=				'</div>';
						nil	+=			'</div>';
					
						nil	+=			'<div class="box-body">';
			          	nil	+=			'<div class="row">';
			          	nil	+=			'	<div class="col-sm-3">';
			            nil	+=			'		<div class="form-group">';
				        nil	+=			'        	<label> Plant: </label>';
				        nil	+=			'        	<select class="form-control select2modal" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Factory">';
						$.each(data, function(a,b){
							nil	+=		'				<option value="'+b.plant+'">'+b.plant+'</option>';	
						});
				        nil	+=			'          	</select>';
						nil	 += 					'<input type="hidden" name="filter_buyer" id="filter_buyer" value="">';
						
				        nil	+=			'    	</div>';
			            nil	+=			'	</div>';
						nil	+=			'	<div class="col-sm-9">';
						nil	+=			'		<div class="btn-group pull-right">';
						nil	+=			'			<button type="button" class="btn btn-primary" name="action_btn_save_selected">Save</button>';						
						nil	+=			'		</div>';
						nil	+=			'	</div>';
						
		            	nil	+=			'</div>';
						nil	+=			'</div>	';				
						
						nil	+=			'<div class="box-body">';
						nil	+=				'<table id="example" class="table table-bordered table-striped table-modals">';
						nil	+=					'<thead>';
						nil	+=						'<th>Factory</th>';
						nil	+=						'<th>Plant</th>';
						nil	+=						'<th>Harga Modal SPOT<br>(IDR/KG)</th>';
						// add ayy
						nil	+=						'<th>Harga Deal Pembelian<br>(IDR/KG)</th>';
						nil	+=						'<th>Selling Price<br>(USC/KG)</th>';
						nil	+=						'<th>Selling Price<br>(IDR/KG)</th>';
						nil	+=						'<th>Prod Cost<br>(IDR/KG)</th>';
						nil	+=						'<th>Trucking Cost<br>(IDR/KG)</th>';
						nil	+=						'<th>Total Cost<br>(IDR/KG)</th>';
						nil	+=						'<th>Carry Cost<br>(IDR/KG)</th>';
						nil	+=						'<th>Margin<br>(IDR/KG)</th>';
						nil	+=						'<th>OCP</th>';
						nil	+=						'<th>Breakeven Price<br>(USC/KG)</th>';
						nil	+=						'<th>Plant Name</th>';
						nil	+=						'<th>List Buyer</th>';
						nil	+=						'<th>Currency Rate</th>';
						nil	+=						'<th>Pol Value</th>';
						nil	+=						'<th>LIBOR Rate</th>';
						nil	+=						'<th>Interest Rate</th>';
						nil	+=						'<th>Days</th>';
						nil	+=						'<th>Prod Cost Tipe</th>';
						nil	+=						'<th>Trucking Cost Value</th>';
						nil	+=						'<th>Shipment Periode</th>';
						nil	+=						'<th>SICOM Price</th>';
						nil	+=						'<th>Buyer</th>';
						// nil	+=						'<th>Total Cost Value</th>';
						// nil	+=						'<th>Margin Value</th>';
						nil	+=					'</thead>';
						nil	+=					'<tbody>';
						
						
						$.each(data, function(i,v){
							var val_ocp 		= v.ocp ? v.ocp : 0;
							var mtd_price		= v.mtd ? parseFloat(v.mtd).toFixed(0) : 0;
							var selling_price	= (parseFloat(port_1.replace(",",""))-parseFloat(v.selisih))*parseFloat(currency_rate.replace(",","")).toFixed(0)/100;
							if(v.cost==0){
								var prod_cost 	= (v.qty_tahun!=0)?parseFloat((v.biaya_tahun/v.qty_tahun)*1000).toFixed(0):0;
									prod_cost 	= (prod_cost=='NaN')?0:prod_cost;
							}else{
								var prod_cost 	= v.cost;
							}
							// var total_cost		= parseInt(mtd_price)+parseInt(prod_cost);
							var total_cost		= parseInt(0);
							// ============================================= add by ayy
							var nilai_deal_harga_pembelian = 0;
							var tot_qty=parseFloat(0);
							var tot_harga=parseFloat(0);
							var qty_kemarin=parseFloat(0);
							var harga_kemarin=parseFloat(0);
							if(v.deal_beli != "" && v.deal_beli != undefined){
								var a = v.deal_beli;
								if(a.indexOf('~') != -1){
									var b = a.split('~');
									var c = (b.length)-1;
									var qty_deal = parseFloat(0);
									var harga_deal = parseFloat(0);
									var arr = "";
									// $.each(b, function(x1, y1){
									if(c > 0){	
										for(var it=0; it < c; it++){
											var qty = b[it].split('|');
											qty_deal += parseFloat(qty[0]);
											harga_deal += parseFloat(qty[0])*parseFloat(qty[1]);
											// console.log('a1',qty_deal,harga_deal);	
										}
									} else {
										var qty 	= b[it].split('|');
										qty_deal 	= parseFloat(qty[0]); 
										harga_deal 	= parseFloat(qty[0])*parseFloat(qty[1]);
										// console.log('a2',qty_deal,harga_deal);	
									}

									// under buy atau ocp minus
									if(val_ocp <= 0){
										tot_qty = parseFloat(qty_deal);
										tot_harga = parseFloat(harga_deal);
										nilai_deal_harga_pembelian = parseFloat(tot_harga/tot_qty).toFixed(0);

									// console.log('a',tot_qty,tot_harga);
									// over buy atau ocp surplus
									} else {
										qty_kemarin   = parseFloat(val_ocp);
										harga_kemarin = parseFloat(val_ocp) * parseFloat(mtd_price);
										tot_qty = parseFloat(qty_deal) + parseFloat(qty_kemarin);
										tot_harga = parseFloat(harga_deal) + parseFloat(harga_kemarin);
										nilai_deal_harga_pembelian = parseFloat(tot_harga/tot_qty).toFixed(0);
									
									// console.log('b',qty_kemarin,harga_kemarin,tot_qty,tot_harga, nilai_deal_harga_pembelian);
									}
									

								} else {
									// console.log('c');
									nilai_deal_harga_pembelian = 0;
								}

							} else {

									// console.log('d');
								nilai_deal_harga_pembelian = 0;
							}

							if(nilai_deal_harga_pembelian > 0 && nilai_deal_harga_pembelian != "" && nilai_deal_harga_pembelian != undefined){
								total_cost = parseFloat(nilai_deal_harga_pembelian) + parseFloat(prod_cost);	
							} else {
								total_cost = parseFloat(mtd_price) + parseFloat(prod_cost);
							}
							total_cost = total_cost ? total_cost : 0;
							// =================================================================
							/*===========syaiful============*/
							if(nilai_deal_harga_pembelian > 0 && nilai_deal_harga_pembelian != "" && nilai_deal_harga_pembelian != undefined){
								cek_carry_cost = (parseFloat(nilai_deal_harga_pembelian).toFixed(0)*parseInt(days)/parseInt(years)) * (parseFloat(interest.replace(",","")).toFixed(2)/100);
							} else {
								cek_carry_cost = (parseFloat(v.mtd).toFixed(0)*parseInt(days)/parseInt(years)) * (parseFloat(interest.replace(",","")).toFixed(2)/100);
							}
							/*==============================*/
							cek_carry_cost = cek_carry_cost ? cek_carry_cost : 0;
							if(cek_carry_cost<=0){
								carry_cost		= 0;
							}else{
								carry_cost		= cek_carry_cost;
							}
							var margin			= selling_price-total_cost-carry_cost;
							margin = margin ? margin : 0;
							var breakeven_price	= (total_cost+carry_cost) / (parseFloat(currency_rate.replace(",","")).toFixed(2))*100;
							nil	 	+= 				'<tr class="'+v.plant+'" data-user="'+v.plant+'">';
							nil	 	+= 					'<td>'+v.TPPCO+'</td>';
							nil	 	+= 					'<input type="hidden" name="factory_'+v.plant+'" value="'+v.TPPCO+'">';
							nil	 	+=					'<td>'+v.plant+'</td>';
							nil	 	+= 					'<input type="hidden" name="plant_'+v.plant+'" value="'+v.plant+'">';
							nil	 	+=					'<td align="right">'+numberWithCommas(mtd_price)+'</td>';
							nil	 	+= 					'<input type="hidden" name="mtd_price_'+v.plant+'" value="'+mtd_price+'">';
							// add ayy
							nil	 	+=					'<td align="right">'+numberWithCommas(nilai_deal_harga_pembelian)+'</td>';
							nil	 	+= 					'<input type="hidden" name="deal_harga_pembelian_'+v.plant+'" value="'+nilai_deal_harga_pembelian+'">';
							if(v.jumlah_pol>1){
								nil	 	+= 				'<td class="exclude">';	
								nil	 	+= 					'<select class="form-control select2" name="port_'+v.plant+'" id="port" data-plant="'+v.plant+'" data-total_cost="'+total_cost+'" data-carry_cost="'+carry_cost+'">';
								var arr_port 		= v.list_port.slice(0, -1).split(",");
								$.each(arr_port, function(x, y){
									port 	 = y.split("|");
									port_value 	 = port[1].split("-");
									nil	 	+= 					'<option value="'+ port[1]+'">' + port[1]+'</option>';
								});
								nil	 	+= 					'</select>';
								//get value default
								port 	 	= arr_port[0].split("|");
								port_value 	 = port[1].split("-");
								nil	 	+= 				'<input type="hidden" name="pol_'+v.plant+'" value="'+ port_value[0]+'">';
								nil	 	+= 				'<input type="hidden" name="pol_value_'+v.plant+'" value="'+parseFloat(port_value[1]).toFixed(2)+'">';
								nil	 	+= 				'<input type="hidden" name="selling_price_usc_'+v.plant+'" value="'+parseFloat(port_value[1]).toFixed(2)+'">';
								nil	 	+= 				'</td>';
							}else{
								var arr_port 		= v.list_port.slice(0, -1).split(",");
								port 	 = arr_port[0].split("|");
								port_value 	 = port[1].split("-");
								nil	 	+= 			'<td>';
								nil	 	+= 			'<input type="hidden" name="port_'+v.plant+'" value="'+ port[1]+'">';
								nil	 	+= 			'<input type="hidden" name="pol_'+v.plant+'" value="'+ port_value[0]+'">';
								nil	 	+= 			'<input type="hidden" name="pol_value_'+v.plant+'" value="'+parseFloat(port_value[1]).toFixed(2)+'">';
								nil	 	+= 			'<input type="hidden" name="selling_price_usc_'+v.plant+'" value="'+parseFloat(port_value[1]).toFixed(2)+'">';
								nil	 	+= 			port[1]
								nil	 	+= 			'</td>';
							
							}
							nil	 	+= 					'<td align="right" id="label_selling_price_'+v.plant+'">'+numberWithCommas(parseFloat(selling_price).toFixed(0))+'</td>';
							nil	 	+= 					'<input type="hidden" name="selling_price_'+v.plant+'" id="selling_price" value="'+selling_price+'">';							
							nil	 	+= 					'<td align="right">'+numberWithCommas(prod_cost)+'</td>';
							nil	 	+= 					'<input type="hidden" name="prod_cost_'+v.plant+'" id="prod_cost" value="'+prod_cost+'">';
							// lha zz
							nil	 	+= 					'<td align="right"><input type="text" class="form-control angka text-right col-xs-2" name="trucking_cost_'+v.plant+'" id="trucking_cost" data-plant="'+v.plant+'" data-total_cost="'+total_cost+'" data-selling_price="'+selling_price+'" data-carry_cost="'+carry_cost+'" placeholder="Trucking Cost"></td>';
							nil	 	+= 					'<td align="right" id="label_total_cost_'+v.plant+'">'+numberWithCommas(total_cost)+'</td>';
							nil	 	+= 					'<input type="hidden" name="total_cost_'+v.plant+'" id="total_cost_'+v.plant+'" value="'+total_cost+'">';
							nil	 	+= 					'<td align="right">'+numberWithCommas(parseFloat(carry_cost).toFixed(2))+'</td>';
							nil	 	+= 					'<input type="hidden" name="carry_cost_'+v.plant+'" id="carry_cost" value="'+parseFloat(carry_cost).toFixed(2)+'">';
							if(margin<0){
								nil	 += 				'<td align="right" id="label_margin_'+v.plant+'" style="color:red;">'+numberWithCommas(parseFloat(margin).toFixed(0))+'</td>';
								nil	 	+= 				'<input type="hidden" name="margin_'+v.plant+'" id="margin_'+v.plant+'" value="'+parseFloat(margin).toFixed(0)+'">';
							}else{
								nil	 += 				'<td align="right" id="label_margin_'+v.plant+'">'+numberWithCommas(parseFloat(margin).toFixed(0))+'</td>';
								nil	 	+= 				'<input type="hidden" name="margin_'+v.plant+'" id="margin_'+v.plant+'" value="'+parseFloat(margin).toFixed(0)+'">';
							}
							//xxxxx nanti diaktifin
							if(val_ocp<0){
								nil	 += 				'<td align="right"  style="color:red;">'+numberWithCommas(parseFloat(val_ocp).toFixed(2))+'</td>';
							}else{
								nil	 += 				'<td align="right">'+numberWithCommas(parseFloat(val_ocp).toFixed(2))+'</td>';
								
							}
							nil	 	+= 					'<input type="hidden" name="ocp_'+v.plant+'" id="ocp" value="'+parseFloat(val_ocp).toFixed(2)+'">';
							// nil	 	+= 					'<td align="right">'+numberWithCommas(parseFloat(10000).toFixed(2))+'</td>';
							nil	 	+= 					'<input type="hidden" name="ocp_'+v.plant+'" id="ocp" value="'+parseFloat(10000).toFixed(2)+'">';
							
							nil	 	+= 					'<td align="right" id="label_breakeven_price">'+numberWithCommas(parseFloat(breakeven_price).toFixed(2))+'</td>';
							nil	 	+= 					'<input type="hidden" name="breakeven_price_'+v.plant+'" id="breakeven_price" value="'+parseFloat(breakeven_price).toFixed(2)+'">';
							nil	 	+=					'<td>'+v.plant_name+'</td>';
							nil	 	+=					'<td>'+v.list_buyer+'</td>';
							nil	 	+=					'<td>'+currency_rate+'</td>';
							nil	 	+= 					'<input type="hidden" name="cur_rate_'+v.plant+'" id="currency_rate" value="'+currency_rate+'">';
							nil	 	+=					'<td>'+port_1+'</td>';
							nil	 	+= 					'<input type="hidden" name="pol_value_default_'+v.plant+'" id="pol_value_default" value="'+port_1+'">';
							nil	 	+=					'<td>'+libor_rate+'</td>';
							nil	 	+= 					'<input type="hidden" name="libor_rate_'+v.plant+'" id="libor_rate" value="'+libor_rate+'">';
							nil	 	+=					'<td>'+interest_rate+'</td>';
							nil	 	+= 					'<input type="hidden" name="interest_rate_'+v.plant+'" id="interest_rate" value="'+interest_rate+'">';
							nil	 	+=					'<td>'+days+'</td>';
							nil	 	+= 					'<input type="hidden" name="days_'+v.plant+'" id="days" value="'+days+'">';
							nil	 	+=					'<td>'+type+'</td>';
							nil	 	+= 					'<input type="hidden" name="prod_cost_type_'+v.plant+'" id="prod_cost" value="'+type+'">';
							nil	 	+= 					'<td align="right" id="label_trucking_cost_value_'+v.plant+'">0</td>';
							nil	 	+=					'<td>'+sales+'</td>';
							nil	 	+= 					'<input type="hidden" name="shipment_periode_'+v.plant+'" id="prod_cost" value="'+sales+'">';

							nil	 	+=					'<td>'+sicom+'</td>';
							nil	 	+= 					'<input type="hidden" name="sicom_'+v.plant+'" id="sicom" value="'+sicom+'">';
							nil	 	+=					'<td>'+buyer+'</td>';
							nil	 	+= 					'<input type="hidden" name="buyer_'+v.plant+'" id="buyer" value="'+buyer+'">';
							// nil	 	+= 					'<td align="right"><input type="text" name="total_cost_'+v.plant+'" id="total_cost_'+v.plant+'" value="'+total_cost+'"></td>';		
							// nil	 	+= 					'<td align="right"><input type="text" name="margin_'+v.plant+'" id="margin_'+v.plant+'" value="'+parseFloat(margin).toFixed(0)+'"></td>';		
							nil	 	+= 				'</tr>';
						});
						
						nil	+=					'</tbody>';
						nil	+=				'</table>';
						nil	+=			'</div>';
						nil	+=		'</div>';
						nil	+=	'</div>';
						nil	+=	'</form>';
						nil	+='</div>';
					$("#show_simulasi").html(nil);	
				},
				complete: function () {
					$('.select2modal').select2();
					// table = $('.table-modals').DataTable({
					table = $('.table-modals').DataTable({
						"columnDefs": [
							// { "visible": false, "targets": 11 },
							{ "visible": false, "targets": 12 },
							{ "visible": false, "targets": 13 },
							{ "visible": false, "targets": 14 },
							{ "visible": false, "targets": 15 },
							{ "visible": false, "targets": 16 },
							{ "visible": false, "targets": 17 },
							{ "visible": false, "targets": 18 },
							{ "visible": false, "targets": 19 },
							{ "visible": false, "targets": 20 },
							{ "visible": false, "targets": 21 },
							{ "visible": false, "targets": 22 },
							{ "visible": false, "targets": 23 },
							// { "visible": false, "targets": 24 },
							// { "visible": false, "targets": 25 }
						],						
						paging:   false,
						bInfo: false,
						ordering : true,
						scrollCollapse: true,
						scrollY: false,
						scrollX : true,
						bautoWidth: false,
						select: true,
						pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 20,
						initComplete: function () {
							// var row = table.row ($(this).closest('tr'));
							// var data = row.data();
							// data.margin = margin_new;
							// row.invalidate().draw(false);
							
							$(document).on("change", "#trucking_cost", function (e) {
								if($(this).val()!=''){
									var trucking_cost	= parseFloat($(this).val().replace(",",""));
								}else{
									var trucking_cost	= 0;
								}
								var plant			= $(this).data("plant");
								var total_cost		= $(this).data("total_cost");
								var selling_price	= $(this).data("selling_price");
								var carry_cost		= $(this).data("carry_cost");
								
								var total_cost_new	= total_cost+trucking_cost;
								var margin_new		= selling_price-total_cost_new-carry_cost;
								$('#label_trucking_cost_value_'+plant).html(trucking_cost);
								$('#label_total_cost_'+plant).html(numberWithCommas(parseFloat(total_cost_new).toFixed(0)));
								// $('#label_margin_'+plant).html(numberWithCommas(parseFloat(margin_new).toFixed(0)));
								// console.log($('#label_margin_'+plant).html());
								var row = table.row ($(this).closest('tr'));
								var data = row.data();
								data.total_cost = total_cost_new;
								data.margin = margin_new;
								data.trucking_cost = trucking_cost;
								row.invalidate().draw(false);
							});
						}
					});
					$('.table-modals tbody').on( 'click', 'tr', 'td:not(.exclude)', function () {
						$(this).toggleClass('selected');
						var selected 	= table.rows('.selected');
						console.log(selected.data());
						var plant_selected = [];
						selected.data().map(function(value){
							plant_selected.push(value[1]);
						});
						$("input[name='plant_selected']").val(plant_selected.join());
					} );	
					
					//hide row by dbclick
					$('.table-modals tbody').on( 'dblclick', 'tr', function () {
						$(this).toggleClass('hide');
					} );					
				}
			});
		}
		e.preventDefault();
		return false;

    });
	
	$(document).on("change", "#pabrik, #filter_buyer", function () {
		table.draw();
	});
	//save selected
	$(document).on("click", "button[name='action_btn_save_selected']", function(e){
		var formData = new FormData($(".form-production-cost-list")[0]);
		$.ajax({
			url: baseURL+'spot/transaksi/save/selected',
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
		e.preventDefault();
		return false;
    });
	
	//delete selected
	$(document).on("click", "button[name='action_btn_delete_selected']", function(e){
		var formData = new FormData($(".form-production-cost-list")[0]);
		$.ajax({
			url: baseURL+'spot/transaksi/save/deleted',
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
		e.preventDefault();
		return false;
    });
	
	//show confirm
	$(document).on("click", "button[name='action_btn_confirm']", function(e){
		// //get no_form
		// $.ajax({
    		// url: baseURL+'spot/transaksi/get/no_form',
			// type: 'POST',
			// dataType: 'JSON',
			// success: function(data){
				// // $("input[name='no_form']").val(data.nomor);	
				// $("#no_form").val(data.nomor);
			// }
		// });		

		// var selected_all_plant = table.rows().data();

		// var all_plant = [];
		// var list_hidden = '';
		// $.each(selected_all_plant, function (i, v) {
			// console.log(v[3]);
			// all_plant.push(v[1]);
			// // port 	 = $(v[3]).val().split("-");	//kolom ke-4
			// list_hidden	 	+= 				'<input type="hidden" name="factory_'+v[1]+'" value="'+v[0]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="plant_'+v[1]+'" value="'+v[1]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="mtd_price_'+v[1]+'" value="'+v[2]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="selling_price_usc_'+v[1]+'" value="'+v[21]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="selling_price_'+v[1]+'" id="selling_price" value="'+v[4]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="prod_cost_'+v[1]+'" id="prod_cost" value="'+v[5]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="trucking_cost_'+v[1]+'" id="trucking_cost" value="'+v[20]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="total_cost_'+v[1]+'" id="total_cost" value="'+v[7]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="carry_cost_'+v[1]+'" id="carry_cost" value="'+v[8]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="margin_'+v[1]+'" id="margin" value="'+v[9]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="ocp_'+v[1]+'" id="ocp" value="'+v[10]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="breakeven_price_'+v[1]+'" id="breakeven_price" value="'+v[11]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="cur_rate_'+v[1]+'" id="currency_rate" value="'+v[14]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="pol_value_default_'+v[1]+'" id="pol_value_default" value="'+v[15]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="libor_rate_'+v[1]+'" id="libor_rate" value="'+v[16]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="interest_rate_'+v[1]+'" id="interest_rate" value="'+v[17]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="days_'+v[1]+'" id="days" value="'+v[18]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="prod_cost_type_'+v[1]+'" id="prod_cost" value="'+v[19]+'">';

			// list_hidden	 	+= 				'<input type="hidden" name="pol_'+v[1]+'" id="pol" value="'+v[22]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="pol_value_'+v[1]+'" id="pol_value" value="'+v[21]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="shipment_periode_'+v[1]+'" id="shipment_periode" value="'+v[23]+'">';
			// list_hidden	 	+= 				'<input type="hidden" name="tanggal_buat_'+v[1]+'" id="tanggal_buat" value="'+v[24]+'">';
		// });
		
		var sales		= $("#sales").val();
		var selected 	= table.rows('.selected');
		console.log(selected.data());
		var list_nilai	= '';
		var nomor		= 0;
		var last_number	= 0;
		var plant_selected = [];
		var id_simulate_selected = [];
		selected.data().map(function(value){
			
			plant_selected.push(value[1]);
			id_simulate_selected.push(value[30]);
			nomor = nomor+1;
			last_number = parseFloat(value[27])+nomor;
			// port 	 = $(value[3]).val().split("-");	//kolom ke-4
			list_nilai	+=			'			<fieldset class="fieldset-info">';
			list_nilai	+=			'				<legend>Sales Confirmation '+nomor+'</legend>';
			list_nilai	+=			'				<div class="row">';
			list_nilai	+=			'					<div class="col-xs-5">';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="sicom">SICOM Price</label></div>';
			list_nilai	+=			'								<div class="col-xs-7">';
			list_nilai	+=			'									<input type="text" class="form-control" name="sicom_'+value[30]+'" required="required" value="'+value[25]+'" readonly>';
			list_nilai	+=			'									<input type="hidden" class="form-control" name="plant_selected" id="plant_selected" value="'+plant_selected.join()+'">';
			list_nilai	+=			'									<input type="hidden" class="form-control" name="id_simulate_selected" id="plant_selected" value="'+id_simulate_selected.join()+'">';
			list_nilai	+=			'								</div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="no_form">Form No</label></div>';
			list_nilai	+=			'								<div class="col-xs-7">';
			list_nilai	+=			'									<input type="text" class="form-control" name="no_form_'+value[30]+'" value="'+padLeft(last_number,4)+'/'+moment().format('YYYY')+'" required="required" readonly>';
			// list_nilai	+=			'									<input type="text" class="form-control" name="no_form_'+value[30]+'" id="no_form" required="required" readonly>';
			list_nilai	+=			'								</div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Buyer</label></div>';
			list_nilai	+= 			'								<div class="col-xs-7">';
			list_nilai	+=			'									<input type="text" class="form-control" name="buyer_'+value[30]+'" id="buyer" value="'+value[26]+'" readonly>';	
			list_nilai	+=			'								</div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'									<div class="col-xs-5"><label for="buyer">Factory</label></div>';
			list_nilai	+=			'								<div class="col-xs-7">';
			list_nilai	+=			'									<input type="text" class="form-control" name="factory_'+value[30]+'" id="factory" value="'+value[12]+'" readonly>';
			
			// list_nilai	+=			'									<input type="hidden" class="form-control" name="factory_det_'+value[30]+'" id="factory_det" value="'+value[12]+'">';
			// list_nilai	+=			'									<input type="hidden" class="form-control" name="tppco_det_'+value[30]+'" id="tppco_det" value="'+value[0]+'">';
			// list_nilai	+=			'									<input type="hidden" class="form-control" name="werks_det_'+value[30]+'" id="werks_det" value="'+value[30]+'">';
			// list_nilai	+=			'									<input type="hidden" class="form-control" name="shipment_periode_det_'+value[30]+'" id="shipment_periode_det" value="'+value[23]+'">';
			// list_nilai	+=			'									<input type="hidden" class="form-control" name="shipment_term_det_'+value[30]+'" id="shipment_term_det" value="'+value[22]+'">';
			// list_nilai	+=			'									<input type="hidden" class="form-control" name="price_det_'+value[30]+'" id="price_det" value="'+value[21]+'">';
			// list_nilai	+=			'									<input type="hidden" class="form-control" name="margin_det_'+value[30]+'" id="margin_det" value="'+value[9]+'">';
			// list_nilai	+=			'									<input type="hidden" class="form-control" name="tanggal_buat_'+value[30]+'" id="margin_det" value="'+value[24]+'">';
			list_nilai	+=			'								</div>';	
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Factory Code</label></div>';
			list_nilai	+=			'								<div class="col-xs-7"><input type="text" class="form-control" name="factory_code_'+value[30]+'" id="factory_code_'+value[30]+'" value="'+value[0]+'" readonly></div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="prod_grade_det">Product Grade</label></div>';
			list_nilai	+=			'								<div class="col-xs-7">';
			list_nilai	+=			'									<select class="form-control select2" name="prod_grade_'+value[30]+'" id="prod_grade_det">';
			list_nilai	+=			'										<option value="">Pilih Product Grade</option>';
			list_nilai	+=			'										<option value="SIR-0010">SIR-0010</option>';
			list_nilai	+=			'										<option value="SIR-0020">SIR-0020</option>';
			list_nilai	+=			'										<option value="SIR-10MR">SIR-10MR</option>';
			list_nilai	+=			'										<option value="SIR-20CP">SIR-20CP</option>';
			list_nilai	+=			'										<option value="SIR-20MR">SIR-20MR</option>';
			list_nilai	+=			'										<option value="SIR-20VK">SIR-20VK</option>';
			list_nilai	+=			'									</select>';
			list_nilai	+=			'								</div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="qty">Quantity</label></div>';
			list_nilai	+=			'								<div class="col-xs-7">';
			list_nilai	+=			'									<div class="input-group">';
			list_nilai	+=			'										<input type="text" class="form-control angka text-right" name="qty_'+value[30]+'" id="qty_det" required="required">';
			list_nilai	+=			'										<span class="input-group-addon">MT</span>';
			list_nilai	+=			'									</div>';	
			list_nilai	+=			'								</div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Margin</label></div>';
			list_nilai	+=			'								<div class="col-xs-7">';
			list_nilai	+=			'									<div class="input-group">';
			list_nilai	+=			'										<input type="text" class="form-control angka text-right" name="margin_'+value[30]+'" id="margin_'+value[30]+'" value="'+value[9]+'" readonly>';
			list_nilai	+=			'										<span class="input-group-addon">IDR/KG</span>';
			list_nilai	+=			'									</div>';
			list_nilai	+=			'								</div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'					</div>';
			list_nilai	+=			'					<div class="col-xs-2"></div>';
			list_nilai	+=			'					<div class="col-xs-5">';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Distribution Channel</label></div>';
			list_nilai	+=			'								<div class="col-xs-7">';
			list_nilai	+=			'									<select class="form-control select2" name="distribution_channel_'+value[30]+'">';
			list_nilai	+=			'										<option value="">Distribution Channel</option>';
			var arr_dc 		= value[29].slice(0, -1).split(",");
			$.each(arr_dc, function(x, y){
				list_nilai	+= 					'<option value="'+ padLeft(y[1],2)+'">' + padLeft(y[1],2)+'</option>';
			});
			list_nilai	+=			'									</select>';
			list_nilai	+=			'								</div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="cntty">Contract Type</label></div>';
			list_nilai	+=			'								<div class="col-xs-7">';
			list_nilai	+=			'									<select class="form-control select2" name="contract_type_'+value[30]+'">';
			list_nilai	+=			'										<option value="">Contract Type</option>';
			list_nilai	+=			'										<option value="SPOT-R">SPOT-R</option>';
			list_nilai	+=			'										<option value="SPOT-F">SPOT-F</option>';
			list_nilai	+=			'										<option value="SPOT-O">SPOT-O</option>';
			list_nilai	+=			'									</select>';
			list_nilai	+=			'								</div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Shipment Period</label></div>';
			list_nilai	+=			'								<div class="col-xs-7"><input type="text" class="form-control" name="shipment_periode_'+value[30]+'" id="shipment_periode_'+value[30]+'" value="'+value[23]+'" readonly></div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Shipment Term</label></div>';
			list_nilai	+=			'								<div class="col-xs-7"><input type="text" class="form-control" name="shipment_term_'+value[30]+'" id="shipment_term_'+value[30]+'" value="'+value[22]+'" readonly></div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Price</label></div>';
			list_nilai	+=			'								<div class="col-xs-7">';
			list_nilai	+=			'									<div class="input-group">';
			list_nilai	+=			'										<input type="text" class="form-control angka text-right" name="price_'+value[30]+'" id="price_'+value[30]+'" value="'+value[21]+'" readonly>';
			list_nilai	+=			'										<span class="input-group-addon">USC/KG</span>';
			list_nilai	+=			'									</div>';
			list_nilai	+=			'								</div>';
			list_nilai	+=			'							</div>	';
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-5"><label for="buyer">Notes</label></div>';
			list_nilai	+=			'								<div class="col-xs-7"><textarea name="note_'+value[30]+'" id="note_det" class="form-control" rows="3" placeholder="Notes"></textarea></div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-12"></div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'						<div class="form-group">';
			list_nilai	+=			'							<div class="row">';
			list_nilai	+=			'								<div class="col-xs-12"><label for="libor_rate">* SICOM, Form No, Margin hanya dikirimkan kepada pihak internal perusahaan</label></div>';
			list_nilai	+=			'							</div>';	
			list_nilai	+=			'						</div>';
			list_nilai	+=			'					</div>';
			list_nilai	+=			'				</div>';	
			list_nilai	+=			'			</fieldset>';			
			
		});
		var nil	= '';
			
			nil	+=  '<div class="row">';
			nil	+=	'<form role="form" class="form-production-cost-email" name="form-production-cost-email" enctype="multipart/form-data">';
			nil	+=	'<div class="col-sm-12">';
			nil	+=		'<div class="box box-success">';
			nil	+=			'<div class="box-header">';
			nil	+=				'<h3 class="box-title"><strong>Send Sales Confirmation</strong></h3>';
			nil	+=				'<div class="btn-group pull-right">';
			nil	+=					'<button type="button" class="btn btn-danger" name="action_btn_cancel">Back</button>';
			nil	+=					'<button type="button" class="btn btn-primary" name="action_btn_email_konfirmasi">Send Email</button>';
			nil	+=				'</div>';
			nil	+=			'</div>';
			nil	+=			'<div class="box-body">';
			nil	+=			'	<div class="row">';
			nil	+=			'		<div class="col-xs-12">';
			// nil	+=			'			<div class="row">';
			// nil	+=			'				<div class="col-xs-5">';
			// nil	+=			'					<div class="form-group">';
			// nil	+=			'						<div class="row">';
			// nil	+=			'							<div class="col-xs-5"><label for="buyer">SICOM Price</label></div>';
			// nil	+=			'							<div class="col-xs-7">';
			// nil	+=			'								<div class="input-group">';
			// nil	+=			'									<input type="text" class="form-control angka text-right" name="sicom" id="sicom" required="required">';
			// nil	+=			'									<span class="input-group-addon">USC/KG</span>';
			// nil	+=			'								</div>';	
			// nil	+=			'							</div>';
			// nil	+=			'						</div>';	
			// nil	+=			'					</div>';
			// nil	+=			'				</div>';	
			// nil	+=			'				<div class="col-xs-2"></div>';
			// nil	+=			'				<div class="col-xs-5">';
			// nil	+=			'					<div class="form-group">';
			// nil	+=			'						<div class="row">';
			// nil	+=			'							<div class="col-xs-5"><label for="no_form">Form No</label></div>';
			// nil	+=			'							<div class="col-xs-7">';
			// nil	+=			'								<input type="text" class="form-control" name="no_form" id="no_form" required="required" disabled>';
			// nil	+=			'								<input type="hidden" class="form-control" name="no_form" id="no_form" required="required">';
			// nil	+=			'								<input type="text" class="form-control" name="plant_selected" id="plant_selected" value="'+plant_selected.join()+' aaaa">';
			// nil	+=			'								<input type="hidden" class="form-control" name="all_plant" id="all_plant" value="'+all_plant.join()+'">';
			// nil	+=			'							</div>';	
			// nil	+=			'						</div>';	
			// nil	+=			'					</div>';
			// nil	+=			'				</div>';	
			// nil	+=			'			</div>';
			nil	+=						list_nilai;
			// nil	+=						list_hidden;
			nil	+=			'		</div>';
			nil	+=			'	</div>';						
			nil	+=			'</div>';
			nil	+=		'</div>';
			nil	+=	'</div>';
			nil	+=	'</form>';
			nil	+='</div>';
			
		$("#show_confirmation").html(nil);	
		$("#show_filter").hide();
		$("#show_simulasi").hide();
		
		e.preventDefault();
		return false;
    });
	//konfirmasi email
	$(document).on("click", "button[name='action_btn_email_konfirmasi']", function(e){

		var formData = new FormData($(".form-production-cost-email")[0]);
		var	jumlah 				= 0;
		var	date 				= moment().format('DD-MMM-YYYY');
		// var plant_selected 		= $("input[name='plant_selected']").val();
		// var arr_plant_selected 	= plant_selected.split(",");
		var id_simulate_selected 		= $("input[name='id_simulate_selected']").val();
		var arr_id_simulate_selected 	= id_simulate_selected.split(",");
		// alert(plant_selected);
		var message = '<body>';
		message +=	'<table>';
		$.each(arr_id_simulate_selected, function(x, id_simulate){
			var sicom			= $("input[name='sicom_"+id_simulate+"']").val();
			var no_form			= $("input[name='no_form_"+id_simulate+"']").val();
			var buyer			= $("input[name='buyer_"+id_simulate+"']").val();
			var factory 		= $("input[name='factory_"+id_simulate+"']").val();
			var factory_code	= $("input[name='factory_code_"+id_simulate+"']").val();
			var prod_grade		= $("select[name='prod_grade_"+id_simulate+"']").val();
			var qty				= $("input[name='qty_"+id_simulate+"']").val();
			var shipment_periode= $("input[name='shipment_periode_"+id_simulate+"']").val();
			var shipment_term	= $("input[name='shipment_term_"+id_simulate+"']").val();
			var price			= $("input[name='price_"+id_simulate+"']").val();
			var note			= $("textarea[name='note_"+id_simulate+"']").val();
			var distribution_channel	= $("select[name='distribution_channel_"+id_simulate+"']").val();
			var contract_type			= $("select[name='contract_type_"+id_simulate+"']").val();
			
			message +=	'	<tr><td colspan="3"><b>SALES CONFIRMATION</b></td></tr>';
			message +=	'	<tr><td width="30%">SICOM PRICE</td><td>:</td><td>'+sicom+'</td></tr>';
			message +=	'	<tr><td width="30%">FORM NO</td><td>:</td><td>'+no_form+'</td></tr>';
			message +=	'	<tr><td width="30%">DATE</td><td>:</td><td>'+date+'</td></tr>';
			message +=	'	<tr><td width="30%">BUYER</td><td>:</td><td>'+buyer+'</td></tr>';
			message +=	'	<tr><td>FACTORY</td><td>:</td><td>'+factory+'</td></tr>';
			message +=	'	<tr><td>FACTORY CODE</td><td>:</td><td>'+factory_code+'</td></tr>';
			if(prod_grade==''){
				jumlah = jumlah+1;
				message +=	'	<tr><td><font color="red">*PRODUCT GRADE</font></td><td>:</td><td><font color="red">-</font></td></tr>';
			}else{
				message +=	'	<tr><td>PRODUCT GRADE</td><td>:</td><td>'+prod_grade+'</td></tr>';	
			}
			if(qty==''){
				jumlah = jumlah+1;
				message +=	'	<tr><td><font color="red">*QUANTITY</font></td><td>:</td><td><font color="red">-</font></td></tr>';
			}else{
				message +=	'	<tr><td>QUANTITY</td><td>:</td><td>'+qty+' MT</td></tr>';	
			}
			if(distribution_channel==''){
				jumlah = jumlah+1;
				message +=	'	<tr><td><font color="red">*DISTRIBUTION CHANNEL</font></td><td>:</td><td><font color="red">-</font></td></tr>';
			}else{
				message +=	'	<tr><td>DISTRIBUTION CHANNEL</td><td>:</td><td>'+distribution_channel+'</td></tr>';	
			}
			if(contract_type==''){
				jumlah = jumlah+1;
				message +=	'	<tr><td><font color="red">*CONTRACT TYPE</font></td><td>:</td><td><font color="red">-</font></td></tr>';
			}else{
				message +=	'	<tr><td>CONTRACT TYPE</td><td>:</td><td>'+contract_type+'</td></tr>';	
			}
			message +=	'	<tr><td>SHIPMENT PERIOD</td><td>:</td><td>'+shipment_periode+'</td></tr>';
			message +=	'	<tr><td>SHIPMENT TERM</td><td>:</td><td>FOB '+shipment_term+'</td></tr>';
			message +=	'	<tr><td>PRICE</td><td>:</td><td>'+price+' USC/KG</td></tr>';
			message +=	'	<tr><td>NOTES</td><td>:</td><td>'+note+'</td></tr>';
			message +=	'	<tr><td colspan="3">&nbsp;</td></tr>';
			
		});
		message +=	'</table>';
			if(jumlah==0){
				$("#kirim_email_yes").show();
				$("#kirim_email_no").show();
				message +=	'<center><b>Apakah data yang diinput sudah sesuai?<b></center>';
			}else{
				$("#kirim_email_yes").hide();
				$("#kirim_email_no").hide();
				message +=	'<center><b><font color="red">*Mohon lengkapi data!</font></center>';
			}
			
			message +=	'</body>';

		$("#data_konfirmasi_email").html(message);
		$('#show_konfirmasi_email').modal('show');

		e.preventDefault();
		return false;
    });
	//save 
	$(document).on("click", "button[name='kirim_email']", function(e){
		$("input[name='isproses']").val(1);
		var formData = new FormData($(".form-production-cost-email")[0]);
		// console.log();
		$.ajax({
			url: baseURL+'spot/transaksi/save/simulasi',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
			contentType: false, 
			cache: false,
			processData: false,
			success: function(data){
				if (data.sts == 'OK') {
					$.ajax({
						url: baseURL + "data/rfc/set/spot",
						type: 'POST',
						dataType: 'JSON',
						data: formData,
						contentType: false,  
						cache: false,
						processData: false,
						success: function(data) {
							
							// if (data.sts == 'OK') {
								// kiranaAlert(data.sts, data.msg);
							// } else {
								// kiranaAlert("notOK", data.msg, "warning", "no");
							// }
						}
					});    
					swal('Success', data.msg, 'success').then(function () {
						location.reload(); 
					});
					
				} else {
					$("input[name='isproses']").val(0);
					swal('Error', data.msg, 'error');
				}
			},
            complete: function(){
				$.ajax({
					url: baseURL + "data/rfc/set/margin",
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,  
					cache: false,
					processData: false,
					success: function(data) {
						
						// if (data.sts == 'OK') {
							// kiranaAlert(data.sts, data.msg);
						// } else {
							// kiranaAlert("notOK", data.msg, "warning", "no");
						// }
					}
				});    
				
			}
		});
		e.preventDefault();
		return false;

    });
	
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
		paging:   false,
		bInfo: false,
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 20
    } );	
	
    // //=======FILTER=======//
    // $(document).on("change", "#plant, #tahun, #buyer, #status", function () {
        // datatables_ssp();
    // });

});


$.fn.dataTable.ext.search.push(
	function( settings, data, dataIndex ) {
		var pabrik = $("#pabrik").val();
		var arr_plant = data[1]; // use data for the age column
		if($.inArray(arr_plant, pabrik) >= 0){
			console.log(arr_plant);
			return true;
		}
		if(pabrik.length > 0)
			return false;
		
		var filter_buyer = $("#filter_buyer").val();
		// alert(filter_buyer);
		//aaa
		var arr_buyer = data[28]; // use data for the age column
		if($.inArray(arr_buyer, filter_buyer) >= 0){
			console.log(arr_buyer);
			return true;
		}
		
		if(filter_buyer.length > 0)
			return false;
		else return true;
	}
);
function padLeft(nr, n, str){
    return Array(n-String(nr).length+1).join(str||'0')+nr;
}