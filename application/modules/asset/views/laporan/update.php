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
	$list_kolom = '
			{dataField: "Pabrik",caption: "Lokasi",groupIndex: 0,fixed: true, autoExpandGroup: false, width:300},
			{dataField: "Kategori",caption: "Kategori",groupIndex: 1,fixed: true, autoExpandGroup: false, width:300},
			{dataField: "Jenis",caption: "Jenis"},
			{dataField: "Total Asset",caption: "Total Asset"},
			{dataField: "Ada Update",caption: "Ada Update"},
			{dataField: "Belum Update",caption: "Belum Update"},
			{dataField: "Last Update",caption: "Last Update"}
	';
	$list_summary = '
			{column: "Total Asset",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
			{column: "Ada Update",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
			{column: "Belum Update",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true}
	';
	$n 	  = 0;
	$list = "";
	foreach($jumlah as $dt){
		$n++;
		$tot_non_update = $dt->tot_aset-$dt->tot_update;
		$list .= "{
			'ID'			: '$n', 
			'Pabrik'		:'".$dt->nama_pabrik."',
			'Kategori'		:'".$dt->nama_kategori."',
			'Jenis'			:'".$dt->nama_jenis."',
			'Total Asset'	:".$dt->tot_aset.",
			'Ada Update'	:".$dt->tot_update.",
			'Belum Update'	:".$tot_non_update.",
			'Last Update'	:'".$dt->last_update."'
			
		  },";
	}
	$list_data = substr($list, 0, -1);
	// echo"<pre>$list_data</pre>";
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
			fileName: "Laporan Ringkasan Asset"
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
        columns: [<?php echo $list_kolom;?>],
		sortByGroupSummaryInfo: [
			{summaryItem: "sum"}
		],
		summary: {
			groupItems: [<?php echo $list_summary;?>]
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

