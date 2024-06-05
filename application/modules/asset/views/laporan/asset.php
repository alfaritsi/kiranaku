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
							<h3 class="box-title"><strong><?php echo $title;?></strong></h3>
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
	//buat pengguna hrga
	if($jumlah[0]->pengguna=='hrga'){
		$list_kolom = '
				{dataField: "Pabrik",caption: "Lokasi",groupIndex: 0,fixed: true, autoExpandGroup: false, width:300},
				{dataField: "Jenis",caption: "Sub Kategori"},
				{
					caption: \'Beroperasi\', 
						columns: [
							{dataField: "COP(Beroperasi)",caption: "COP",format: "fixedPoint",precision : 0},
							{dataField: "Perusahaan(Beroperasi)",caption: "Perusahaan",format: "fixedPoint",precision : 0}
						]
				},
				{dataField: "Total(Beroperasi)",caption: "Total",format: "fixedPoint",precision : 0},
				{
					caption: \'Rusak\',
						columns: [
							{dataField: "COP(Rusak)",caption: "COP",format: "fixedPoint",precision : 0},
							{dataField: "Perusahaan(Rusak)",caption: "Perusahaan",format: "fixedPoint",precision : 0}
						]
				},
				{dataField: "Total(Rusak)",caption: "Total",format: "fixedPoint",precision : 0}
		
		';
		$list_summary = '
					{column: "COP(Beroperasi)",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
					{column: "Perusahaan(Beroperasi)",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
					{column: "Total(Beroperasi)",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
					{column: "COP(Rusak)",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
					{column: "Perusahaan(Rusak)",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
					{column: "Total(Rusak)",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true}
		';
		$n 	  = 0;
		$list = "";
		foreach($jumlah as $dt){
			$n++;
			$tot_beroperasi	 = $dt->cop_beroperasi + $dt->perusahaan_beroperasi;
			$tot_rusak		 = $dt->cop_rusak + $dt->perusahaan_rusak;
			$list .= "{
				'ID'					: '$n', 
				'Pabrik'				:'".$dt->nama_pabrik."',
				'Jenis'					:'".$dt->nama_jenis."',
				'COP(Beroperasi)'		:".$dt->cop_beroperasi.",
				'Perusahaan(Beroperasi)':".$dt->perusahaan_beroperasi.",
				'Total(Beroperasi)'		:".$tot_beroperasi.",
				'COP(Rusak)'			:".$dt->cop_rusak.",
				'Perusahaan(Rusak)'		:".$dt->perusahaan_rusak.",
				'Total(Rusak)'			:".$tot_rusak."
				
			  },";
		}
		$list_data = substr($list, 0, -1);
	}else if($param=='fo' && $param2=='lab'){
		$list_kolom = '
				{dataField: "Pabrik",caption: "Lokasi",groupIndex: 0,fixed: true, autoExpandGroup: false, width:200},
				{dataField: "Jenis",caption: "Sub Kategori"},
				{dataField: "Beroperasi",caption: "Beroperasi"},
				{dataField: "Persen Beroperasi",caption: "Persen Beroperasi",precision : 2},
				{dataField: "Rusak",caption: "Rusak"},
				{dataField: "Persen Rusak",caption: "Persen Rusak",precision : 2},
				{dataField: "Expired",caption: "Expired"},
				{dataField: "Persen Expired",caption: "Persen Expired",precision : 2},
				{dataField: "Total",caption: "Total"}
		';
		$list_summary = '
				{column: "Beroperasi",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Rusak",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Expired",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Total",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true}
		';
		$n 	  = 0;
		$list = "";
		foreach($jumlah as $dt){
			$n++;
			$total_aset 		= $dt->tot_beroperasi+$dt->tot_rusak;
			$persen_beroperasi 	= ($total_aset!=0)?($dt->tot_beroperasi/$total_aset)*100:0;
			$persen_rusak 		= ($dt->tot_rusak!=0)?($dt->tot_rusak/$total_aset)*100:0;
			$persen_expired		= ($dt->tot_expired!=0)?($dt->tot_expired/$total_aset)*100:0;
			$list .= "{
				'ID'				: '$n', 
				'Pabrik'			:'".$dt->nama_pabrik."',
				'Jenis'				:'".$dt->nama_jenis."',
				'Beroperasi'		:".$dt->tot_beroperasi.",
				'Persen Beroperasi'	:".round($persen_beroperasi,2).",
				'Rusak'				:".$dt->tot_rusak.",
				'Persen Rusak'		:".round($persen_rusak,2).",
				'Expired'			:".$dt->tot_expired.",
				'Persen Expired'	:".round($persen_expired,2).",
				'Total'				:".$total_aset."
				
			  },";
		}
		$list_data = substr($list, 0, -1);
	}else{
		$list_kolom = '
				{dataField: "Pabrik",caption: "Lokasi",groupIndex: 0,fixed: true, autoExpandGroup: false, width:200},
				{dataField: "Jenis",caption: "Sub Kategori"},
				{dataField: "Beroperasi",caption: "Beroperasi"},
				{dataField: "Persen Beroperasi",caption: "Persen Beroperasi",precision : 2},
				{dataField: "Rusak",caption: "Rusak"},
				{dataField: "Persen Rusak",caption: "Persen Rusak",precision : 2},
				{dataField: "Total",caption: "Total"}
		';
		$list_summary = '
				{column: "Beroperasi",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Rusak",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true},
				{column: "Total",displayFormat: "{0}",summaryType: "sum",valueFormat: "fixedPoint",precision : 0,showInGroupFooter: false, alignByColumn: true}
		';
		$n 	  = 0;
		$list = "";
		foreach($jumlah as $dt){
			$n++;
			$total_aset 		= $dt->tot_beroperasi+$dt->tot_rusak;
			$persen_beroperasi 	= ($total_aset!=0)?($dt->tot_beroperasi/$total_aset)*100:0;
			$persen_rusak 		= ($dt->tot_rusak!=0)?100-$persen_beroperasi:0;
			$list .= "{
				'ID'				: '$n', 
				'Pabrik'			:'".$dt->nama_pabrik."',
				'Jenis'				:'".$dt->nama_jenis."',
				'Beroperasi'		:".$dt->tot_beroperasi.",
				'Persen Beroperasi'	:".round($persen_beroperasi,2).",
				'Rusak'				:".$dt->tot_rusak.",
				'Persen Rusak'		:".round($persen_rusak,2).",
				'Total'				:".$total_aset."
				
			  },";
		}
		$list_data = substr($list, 0, -1);
	}
	
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
		// sortByGroupSummaryInfo: [
			// {summaryItem: "sum"}
		// ],
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

