<?php $this->load->view('header') ?>
	<!--devexpress-->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.spa.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.common.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.light.css">
	
	<div class="content-wrapper">
		<section class="content">
			<div class="row">
				<div class="col-sm-12">
					<div class="box box-success">
						<div class="box-header">
							<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div id="gridContainer"></div>
						</div>	
					</div>
				</div>
			</div>
		</section>
	</div>
	<footer class="main-footer">
		<div class="pull-right hidden-xs">
	    	<b>Version</b> 2.0.0 Beta
	    </div>
	    <strong>Copyright © 2018 Kirana Megatara ICT Division.</strong> All rights reserved.
		<!--devexpress-->
		<script src="<?php echo base_url() ?>assets/plugins/devexpress/jszip.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/devexpress/jquery.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/devexpress/dx.all.js"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="<?php echo base_url() ?>assets/bootstrap/js/bootstrap.min.js"></script>
		<!-- DataTables -->
		<script src="<?php echo base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
		<!-- SlimScroll -->
		<script src="<?php echo base_url() ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<!-- AdminLTE App -->
		<script src="<?php echo base_url() ?>assets/dist/js/app.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/select2/select2.full.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/pace/pace.min.js"></script>
		<script src="<?php echo base_url() ?>assets/apps/js/general.js"></script>
		<style type="text/css">
			.select2{
				width: 100% !important;
			}
		</style>
	</footer>
</html>
<?php
	$n 	  = 0;
	$list = "";
	foreach($biaya as $dt){
		$n++;
		$list .= "{
			'ID': '$n', 
			'Root':'".$dt->root."',
			'Jenis':'".$dt->jenis_program."',
			'Program':'".$dt->nama_program."',
			'Program Batch':'".$dt->nama."',
			'Tahun':'".$dt->tahun."',
			'Tanggal':'".$dt->tanggal_awal." sd ".$dt->tanggal_akhir."',
			'Budget Training': ".$dt->budget_training.",
			'Aktual Training': ".$dt->aktual_training.",
			'Budget Traveling': ".$dt->budget_traveling.",
			'Aktual Traveling': ".$dt->aktual_traveling.",
			'Budget': ".$dt->budget.",
			'Aktual':".$dt->aktual.",
			'Sisa':".$dt->sisa."
		  },";
	}
	$list_data = substr($list, 0, -1);
?>
<script>
$(function(){
	var customers = [<?php echo $list_data;?>];	
    var dataGrid = $("#gridContainer").dxDataGrid({
        dataSource: customers,
		allowColumnReordering: true,
		allowColumnResizing: true,
		columnAutoWidth: true,	
		showColumnLines: true,
		showRowLines: true,
		rowAlternationEnabled: true,
		"export": {
			enabled: true,
			fileName: "Laporan_Budget_dan_Aktual_Biaya"
		},				
		filterRow: {
			visible: false,
			applyFilter: "auto"
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
		paging: {
			pageSize: 0
		},  
		groupPanel: {
			visible: true
		},	
		columnFixing: { 
			enabled: true
		},	
        columns: [
			{dataField: "Root",caption: "Root",groupIndex: 0,fixed: true, autoExpandGroup: true},
			{dataField: "Jenis",caption: "Jenis",groupIndex: 1,fixed: true, autoExpandGroup: true},
			{dataField: "Program",caption: "Program",groupIndex: 2,fixed: true, autoExpandGroup: true},
			{dataField: "Program Batch",caption: "Program Batch"},
			{dataField: "Tahun",caption: "Tahun"},
			{dataField: "Tanggal",caption: "Tanggal"},
			{dataField: "Budget Training",caption: "Budget Training",format: "fixedPoint",precision : 0},
			{dataField: "Aktual Training",caption: "Aktual Training",format: "fixedPoint",precision : 0},
			{dataField: "Budget Traveling",caption: "Budget Traveling",format: "fixedPoint",precision : 0},
			{dataField: "Aktual Traveling",caption: "Aktual Traveling",format: "fixedPoint",precision : 0},
			{dataField: "Budget",caption: "Total Budget",format: "fixedPoint",precision : 0},
			{dataField: "Aktual",caption: "Total Aktual",format: "fixedPoint",precision : 0},
			{dataField: "Sisa",caption: "Sisa",format: "fixedPoint",precision : 0}
        ],
		sortByGroupSummaryInfo: [
			{summaryItem: "sum"}
		],
		summary: {
			groupItems: [
				{column: "Budget Training",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Aktual Training",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Budget Traveling",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Aktual Traveling",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Budget",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Aktual",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Sisa",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true}
			]
		}
		
    }).dxDataGrid("instance");
    
    $("#autoExpand").dxCheckBox({
        value: true,
        text: "Expand All Groups",
        onValueChanged: function(data) {
            dataGrid.option("grouping.autoExpandAll", data.value);
        }
    });
});
</script>	

