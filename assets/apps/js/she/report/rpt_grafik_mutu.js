/*
@application    : SHE
@author         : Lukman Hakim (7143)
@contributor    : 
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>             
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

$(document).ready(function(){
	// get_data();
	$(document).on("change", "#pabrik, #dari, #sampai", function(e){
		var pabrik 	= $("#pabrik").val();
		var dari 	= $("#dari").val();
		var sampai 	= $("#sampai").val();
		if((pabrik!='') && (dari!='') && (sampai!='')){
			get_data(pabrik,dari, sampai);
			get_grafik_cod(pabrik,dari, sampai);
			get_grafik_bod(pabrik,dari, sampai);
			get_grafik_tss(pabrik,dari, sampai);
			get_grafik_ammonia(pabrik,dari, sampai);
			get_grafik_nitro(pabrik,dari, sampai);
		}
	});
    //date pitcker
    $('.monthPicker').datepicker({
        startView: 'year',
        minViewMode: "months",
        format: 'mm.yyyy',
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        // startDate: new Date()
    });
	
});

function get_grafik_nitro(pabrik, dari, sampai, param){
	$.ajax({
		url: baseURL+'she/report/get_grafik_cemar_chart',
		type: 'POST',
		dataType: 'JSON',
		data: {
			pabrik 	: pabrik,
			dari 	: dari,
			sampai 	: sampai,
			param 	: 6
		},
		success: function(data){	
			//buat chart
			var chart = $("#chart_nitro").dxChart({
				palette: "violet",
				dataSource: data,
				commonSeriesSettings: {
					type: "spline",
					argumentField: "bulan"
				},
				commonAxisSettings: {
					grid: {
						visible: true
					}
				},
				margin: {
					bottom: 20
				},
				series: [
					{ valueField: "nilai", name: "Nilai" },
					{ valueField: "mutu", name: "Baku Mutu" }
				],
				tooltip:{
					enabled: true
				},
				legend: {
					verticalAlignment: "bottom",
					horizontalAlignment: "center"
				},
				"export": {
					enabled: true
				},
				argumentAxis: {
					label:{
						format: {
							type: "decimal"
						}
					},
					allowDecimals: false,
					axisDivisionFactor: 60
				},
				title: 'Total Nitrogen'
			}).dxChart("instance");
		}
	});
}function get_grafik_ammonia(pabrik, dari, sampai, param){
	$.ajax({
		url: baseURL+'she/report/get_grafik_cemar_chart',
		type: 'POST',
		dataType: 'JSON',
		data: {
			pabrik 	: pabrik,
			dari 	: dari,
			sampai 	: sampai,
			param 	: 5
		},
		success: function(data){	
			//buat chart
			var chart = $("#chart_ammonia").dxChart({
				palette: "violet",
				dataSource: data,
				commonSeriesSettings: {
					type: "spline",
					argumentField: "bulan"
				},
				commonAxisSettings: {
					grid: {
						visible: true
					}
				},
				margin: {
					bottom: 20
				},
				series: [
					{ valueField: "nilai", name: "Nilai" },
					{ valueField: "mutu", name: "Baku Mutu" }
				],
				tooltip:{
					enabled: true
				},
				legend: {
					verticalAlignment: "bottom",
					horizontalAlignment: "center"
				},
				"export": {
					enabled: true
				},
				argumentAxis: {
					label:{
						format: {
							type: "decimal"
						}
					},
					allowDecimals: false,
					axisDivisionFactor: 60
				},
				title: 'Ammonia'
			}).dxChart("instance");
		}
	});
}function get_grafik_tss(pabrik, dari, sampai, param){
	$.ajax({
		url: baseURL+'she/report/get_grafik_cemar_chart',
		type: 'POST',
		dataType: 'JSON',
		data: {
			pabrik 	: pabrik,
			dari 	: dari,
			sampai 	: sampai,
			param 	: 4
		},
		success: function(data){	
			//buat chart
			var chart = $("#chart_tss").dxChart({
				palette: "violet",
				dataSource: data,
				commonSeriesSettings: {
					type: "spline",
					argumentField: "bulan"
				},
				commonAxisSettings: {
					grid: {
						visible: true
					}
				},
				margin: {
					bottom: 20
				},
				series: [
					{ valueField: "nilai", name: "Nilai" },
					{ valueField: "mutu", name: "Baku Mutu" }
				],
				tooltip:{
					enabled: true
				},
				legend: {
					verticalAlignment: "bottom",
					horizontalAlignment: "center"
				},
				"export": {
					enabled: true
				},
				argumentAxis: {
					label:{
						format: {
							type: "decimal"
						}
					},
					allowDecimals: false,
					axisDivisionFactor: 60
				},
				title: 'TSS'
			}).dxChart("instance");
		}
	});
}
function get_grafik_bod(pabrik, dari, sampai, param){
	$.ajax({
		url: baseURL+'she/report/get_grafik_cemar_chart',
		type: 'POST',
		dataType: 'JSON',
		data: {
			pabrik 	: pabrik,
			dari 	: dari,
			sampai 	: sampai,
			param 	: 3
		},
		success: function(data){	
			//buat chart
			var chart = $("#chart_bod").dxChart({
				palette: "violet",
				dataSource: data,
				commonSeriesSettings: {
					type: "spline",
					argumentField: "bulan"
				},
				commonAxisSettings: {
					grid: {
						visible: true
					}
				},
				margin: {
					bottom: 20
				},
				series: [
					{ valueField: "nilai", name: "Nilai" },
					{ valueField: "mutu", name: "Baku Mutu" }
				],
				tooltip:{
					enabled: true
				},
				legend: {
					verticalAlignment: "bottom",
					horizontalAlignment: "center"
				},
				"export": {
					enabled: true
				},
				argumentAxis: {
					label:{
						format: {
							type: "decimal"
						}
					},
					allowDecimals: false,
					axisDivisionFactor: 60
				},
				title: 'BOD'
			}).dxChart("instance");
		}
	});
}
function get_grafik_cod(pabrik, dari, sampai, param){
	$.ajax({
		url: baseURL+'she/report/get_grafik_cemar_chart',
		type: 'POST',
		dataType: 'JSON',
		data: {
			pabrik 	: pabrik,
			dari 	: dari,
			sampai 	: sampai,
			param 	: 2
		},
		success: function(data){	
			//buat chart
			var chart = $("#chart_cod").dxChart({
				palette: "violet",
				dataSource: data,
				commonSeriesSettings: {
					type: "spline",
					argumentField: "bulan"
				},
				commonAxisSettings: {
					grid: {
						visible: true
					}
				},
				margin: {
					bottom: 20
				},
				series: [
					{ valueField: "nilai", name: "Nilai" },
					{ valueField: "mutu", name: "Baku Mutu" }
				],
				tooltip:{
					enabled: true
				},
				legend: {
					verticalAlignment: "bottom",
					horizontalAlignment: "center"
				},
				"export": {
					enabled: true
				},
				argumentAxis: {
					label:{
						format: {
							type: "decimal"
						}
					},
					allowDecimals: false,
					axisDivisionFactor: 60
				},
				title: 'COD'
			}).dxChart("instance");
		}
	});
}
function get_grafik_ph(pabrik, dari, sampai, param){
	$.ajax({
		url: baseURL+'she/report/get_grafik_cemar_chart',
		type: 'POST',
		dataType: 'JSON',
		data: {
			pabrik 	: pabrik,
			dari 	: dari,
			sampai 	: sampai,
			param 	: 1
		},
		success: function(data){	
			//buat chart
			var chart = $("#chart_ph").dxChart({
				palette: "violet",
				dataSource: data,
				commonSeriesSettings: {
					type: "spline",
					argumentField: "bulan"
				},
				commonAxisSettings: {
					grid: {
						visible: true
					}
				},
				margin: {
					bottom: 20
				},
				series: [
					{ valueField: "nilai", name: "Nilai" },
					{ valueField: "min", name: "Baku Mutu Min" },
					{ valueField: "max", name: "Baku Mutu Max" }
				],
				tooltip:{
					enabled: true
				},
				legend: {
					verticalAlignment: "bottom",
					horizontalAlignment: "center"
				},
				"export": {
					enabled: true
				},
				title: 'pH'
			}).dxChart("instance");
		}
	});
}
	
function get_data(pabrik, dari, sampai){
	$.ajax({
		url: baseURL+'she/report/get_grafik_mutu',
		type: 'POST',
		dataType: 'JSON',
		data: {
			pabrik 	: pabrik,
			dari 	: dari,
			sampai 	: sampai
		},
		success: function(data){	
			//buat table
			var salesPivotGrid = $("#sales").dxPivotGrid({
				changeShowGrandTotals:false,
				showColumnGrandTotals: false,	
				showColumnTotals:false,
				showRowGrandTotals:false,
				showRowTotals:false,
				allowSortingBySummary: true,
				allowSorting: true,
				allowFiltering: true,
				showBorders: true,
				fieldChooser: {
					enabled: true,
					applyChangesMode: "instance",
					allowSearch: true
				},				
				dataSource: {
					fields: [{
						caption: "Parameter",
						width: 120,
						dataField: "parameter",
						area: "row",
						expanded: true,
						headerFilter: {
							allowSearch: true
						} 
					}, {
						caption: "Param",
						dataField: "param",
						width: 150,
						area: "row",
						headerFilter: {
							allowSearch: true
						}
					}, {
						caption: "Tanggal",
						dataField: "tanggal",
						dataType: "date",
						area: "column",
						groupInterval: "year",
						expanded: true
					}, {
						caption: "Tanggal",
						dataField: "tanggal",
						dataType: "date",
						area: "column",
						groupInterval: "month",
						expanded: true
					}, {
						caption: "Nilai",
						dataField: "nilai",
						dataType: "number",
						summaryType: "sum",
						area: "data",
						precision : 0
					}],
					store: data
				},
				fieldChooser: {
					enabled: false
				} 
			}).dxPivotGrid("instance");
			
		},
		complete: function () {
			$("#sales").dxPivotGrid("instance").option("showColumnGrandTotals", false);
		}
	});
}	
