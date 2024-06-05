<?php $this->load->view('header') ?>
<!--devexpress-->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.spa.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.common.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.light.css">
<style type="text/css">
	#chartdiv {
		width: 100%;
		height: 400px;
	}

	#pie_aset {
		height: 50%;
		width: 100%;
	}

	#pie_lokasi {
		height: 50%;
		width: 100%;
	}

	#chart {
		height: 50%;
		width: 100%;
	}

	.text-table-center th {
		vertical-align: middle !important;
	}
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><strong>Dashboard Asset Management for:</strong></h3>
						<i class="fa">
							<form method="post" class="form-filter-kategori">
								<select class="form-control select2" id="pengguna" name="pengguna" style="width: 100%;" data-placeholder="Pengguna">
									<?php
									foreach ($pengguna as $dt) {
										if (isset($_POST['pengguna'])) {
											$ck = ($dt->pengguna == $_POST['pengguna']) ? "selected" : "";
										} else {
											$ck = ($dt->pengguna == 'IT') ? "selected" : "";
										}
										echo "<option value='" . $dt->pengguna . "' $ck>" . strtoupper($dt->pengguna) . "</option>";
									}
									?>
								</select>
							</form>
						</i>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<form method="post" class="form-filter-kategori">
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<label>Pabrik</label>
										<select class="form-control select2" id="id_pabrik" name="id_pabrik" style="width: 100%;" data-placeholder="Pabrik">
											<?php
											foreach ($plant as $dt) {
												if (isset($_POST['id_pabrik'])) {
													$ck = ($dt->id_pabrik == $_POST['id_pabrik']) ? "selected" : "";
												} else {
													$ck = ($dt->id_pabrik == 1) ? "selected" : "";
												}
												echo "<option value='" . $dt->id_pabrik . "' $ck>" . $dt->kode . " - " . $dt->nama . "</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<label>Kategori</label>
										<select class="form-control select2" id="id_kategori" name="id_kategori" style="width: 100%;" data-placeholder="Kategori">
											<?php
											echo "<option value='all'>Pilih Kategori</option>";
											foreach ($kategori as $dt) {
												if (isset($_POST['id_kategori'])) {
													$ck = ($dt->id_kategori == $_POST['id_kategori']) ? "selected" : "";
												} else {
													$ck = ($dt->id_kategori == '') ? "selected" : "";
												}
												echo "<option value='" . $dt->id_kategori . "' $ck>" . $dt->nama . "</option>";
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</form>

						<div class="row">
							<div class="col-sm-6">
								<div id="pie_aset"></div>
							</div>
							<div class="col-sm-6">
								<div id="chartdiv"></div>
								<!--<div id="chart"></div>-->
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div id="pie_lokasi"></div>
							</div>
							<div class="col-sm-6">
								<div class="box box-success">
									<div class="box-header">
										<h3 class="box-title"><strong>Laporan Data Problem All Plant</strong></h3>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Problem</th>
													<th>Jumlah</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 0;
												$no_problem = 0;
												foreach ($problem as $dt) {
													$no_problem++;
													if ($dt->jumlah != 0) {
														$no++;
														// echo"<tr><td><a href='".base_url()."asset/transaksi/maintenance/it/".$no."' target='_blank'>".$dt->problem."</a></td><td>".$dt->jumlah."</td></tr>";	
														echo "<tr><td><a href='" . base_url() . "asset/laporan/dashboard/it/" . $no_problem . "' target='_blank'>" . $dt->problem . "</a></td><td>" . $dt->jumlah . "</td></tr>";
													}
												}
												?>
											</tbody>
										</table>
										<!--
											<div id="gridContainer"></div>
											-->
									</div>
								</div>
							</div>
						</div>
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
	<!--amchart4-->
	<script src="<?php echo base_url() ?>assets/plugins/amchart4/core.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/amchart4/charts.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/amchart4/themes/animated.js"></script>
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
		.select2 {
			width: 100% !important;
		}
	</style>
</footer>

</html>
<script>
	$(document).ready(function() {
				var dataGrid = $("#gridContainer").dxDataGrid({
					// dataSource: customers,
					dataSource: [
						<?php
						$n 	  = 0;
						foreach ($problem as $dt) {
							$n++;
							echo "{
						'ID'				: '$n', 
						'Problem'			:'" . $dt->problem . "',
						'Jumlah'			:" . $dt->jumlah . "
					  },";
						}
						?>
					],
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
					columns: [{
							dataField: "Problem",
							caption: "Problem",
							width: 400
						},
						{
							dataField: "Jumlah",
							caption: "Jumlah"
						}
					],
					summary: {
						groupItems: [{
							column: "Number",
							displayFormat: "{0}",
							summaryType: "sum",
							valueFormat: "fixedPoint",
							precision: 0,
							showInGroupFooter: false,
							alignByColumn: true
						}]
					}

				}).dxDataGrid("instance");

				$("#autoExpand").dxCheckBox({
					value: true,
					text: "Expand All Groups",
					onValueChanged: function(data) {
						dataGrid.option("grouping.autoExpandAll", data.value);
					}
				});
				//change
				$(document).on("change", "#pengguna,#id_pabrik,#id_kategori", function() {
					var id_pabrik = $("#id_pabrik").val();
					var id_kategori = $("#id_kategori").val();
					// alert(id_pabrik); 
					// alert(id_kategori);

					$('.form-filter-kategori').submit();
				});
				//cek all
				$('#cek_all').on('change', function() {
					if ($("#cek_all").is(':checked')) {
						$('#id_kategori').select2('destroy').find('option').prop('selected', 'selected').end().select2();
					} else {
						$('#id_kategori').select2('destroy').find('option').prop('selected', false).end().select2();
					}
					$('.form-filter-kategori').submit();
				});

				//pie dev express
				window.onload = function() {
					let judul = <?php echo $judul; ?>
					$("#chart").dxChart({
						// dataSource: dataSource,
						dataSource: [
							<?php
							foreach ($jenis_jumlah as $dt) {
								echo "{ state: '" . $dt->nama . "', Asset: " . $dt->asset . ", Damage: " . $dt->damage . " },";
							}
							?>
						],
						commonSeriesSettings: {
							argumentField: "state",
							type: "bar",
							palette: "Soft Pastel",
							hoverMode: "allArgumentPoints",
							selectionMode: "allArgumentPoints",
							label: {
								visible: true,
								format: {
									type: "fixedPoint",
									precision: 0
								}
							}
						},
						series: [{
								valueField: "Asset",
								name: "Asset"
							},
							{
								valueField: "Damage",
								name: "Damage"
							}
						],
						// title: "Damage vs Asset Type",
						<?php
						echo "title: 'Damage vs  $judul',";
						?>,
						legend: {
							verticalAlignment: "bottom",
							horizontalAlignment: "center"
						},
						"export": {
							enabled: false
						},
						onPointClick: function(e) {
							e.target.select();
						}
					});
					$("#pie_aset").dxPieChart({
						type: "doughnut",
						palette: "Soft Pastel",
						dataSource: [
							<?php
							foreach ($jenis_jumlah as $dt) {
								echo "{ role: '" . $dt->nama . "', jumlah: " . $dt->jumlah_aset . " },";
							}
							?>
						],
						title: {
							<?php echo "text: '$judul',"; ?>,
							font: {
								color: "black",
								family: "Segoe UI",
								weight: 400,
								size: 22
							}
						},
						resolveLabelOverlapping: 'shift',
						legend: {
							orientation: "horizontal",
							itemTextPosition: "right",
							horizontalAlignment: "center",
							verticalAlignment: "bottom",
							columnCount: 6
						},
						"export": {
							enabled: false
						},
						series: [{
							argumentField: "role",
							valueField: "jumlah",
							label: {
								visible: true,
								font: {
									size: 12
								},
								connector: {
									visible: true,
									width: 0.5
								},
								position: "columns",
								customizeText: function(arg) {
									return arg.argumentText + " : " + arg.valueText;
								}
							}
						}],
						onPointClick: function(e) {
							// alert('aa');
							var arg = e.target.argument;
							// console.log(arg);
							// alert(arg[0]);
							// console.log(arg[0]);
							// toggleVisibility(this.getAllSeries()[0].getPointsByArg(arg)[0]);
							window.open(baseURL + 'asset/laporan/dashboard/' + arg, '_blank');
						},
						onLegendClick: function(e) {
							var arg = e.target;
							// alert(arg);
							// console.log(arg);
							// toggleVisibility(this.getAllSeries()[0].getPointsByArg(arg)[0]);
							// link_url(arg);
							// window.location = baseURL + 'bank/transaksi/data';
							window.open(baseURL + 'asset/laporan/dashboard/' + arg, '_blank');

						}
					});
					$("#pie_lokasi").dxPieChart({
						type: "doughnut",
						palette: "Soft Pastel",
						dataSource: [
							<?php
							foreach ($lokasi_jumlah as $dt) {
								$nama	= str_replace('(', '', $dt->nama);
								$nama	= str_replace(')', '', $nama);
								echo "{ role: '" . $nama . "', jumlah: " . $dt->jumlah_aset . " },";
							}
							?>
						],
						title: {
							text: "Asset by Location",
							font: {
								color: "black",
								family: "Segoe UI",
								weight: 400,
								size: 22
							}
						},
						resolveLabelOverlapping: 'shift',
						legend: {
							orientation: "horizontal",
							itemTextPosition: "right",
							horizontalAlignment: "center",
							verticalAlignment: "bottom",
							columnCount: 6
						},
						"export": {
							enabled: false
						},
						series: [{
							argumentField: "role",
							valueField: "jumlah",
							label: {
								visible: true,
								font: {
									size: 12
								},
								connector: {
									visible: true,
									width: 0.5
								},
								position: "columns",
								customizeText: function(arg) {
									return arg.argumentText + " : " + arg.valueText;
								}
							}
						}]
					});

				}
</script>
<script>
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end

		// Create chart instance
		var chart = am4core.create("chartdiv", am4charts.XYChart3D);

		// Add data
		chart.data = [
			<?php
			foreach ($jenis_jumlah as $dt) {
				echo "{ jenis: '" . $dt->nama . "', damage: " . $dt->damage . ", aset: " . $dt->asset . ", repaired: " . $dt->repaired . " },";
			}
			?>
		];


		// Create axes
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "jenis";
		categoryAxis.renderer.grid.template.location = 0;
		categoryAxis.renderer.minGridDistance = 30;

		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.title.text = "";
		valueAxis.renderer.labels.template.adapter.add("text", function(text) {
			return text;
		});
		//title
		var title = chart.titles.create();
		<?php echo "title.text = 'Status $judul'"; ?>;
		title.fontSize = 25;
		title.marginBottom = 30;

		// Create series
		var series = chart.series.push(new am4charts.ColumnSeries3D());
		series.dataFields.valueY = "damage";
		series.dataFields.categoryX = "jenis";
		series.name = "Damage";
		series.clustered = false;
		series.columns.template.tooltipText = "Damage: [bold]{valueY}[/]";
		series.columns.template.fillOpacity = 0.9;
		series.columns.template.stroke = am4core.color("#ff0000"); // red outline
		series.columns.template.fill = am4core.color("#ff0000"); // red fill

		var series2 = chart.series.push(new am4charts.ColumnSeries3D());
		series2.dataFields.valueY = "repaired";
		series2.dataFields.categoryX = "jenis";
		series2.name = "Being Repaired";
		series2.clustered = false;
		series2.columns.template.tooltipText = "Being Repaired: [bold]{valueY}[/]";
		series2.columns.template.stroke = am4core.color("#f39c12"); // red outline
		series2.columns.template.fill = am4core.color("#f39c12"); // red fill


		var series3 = chart.series.push(new am4charts.ColumnSeries3D());
		series3.dataFields.valueY = "aset";
		series3.dataFields.categoryX = "jenis";
		series3.name = "Asset";
		series3.clustered = false;
		series3.columns.template.tooltipText = "Asset: [bold]{valueY}[/]";



		chart.legend = new am4charts.Legend();
		chart.legend.fontSize = 12;
		let markerTemplate = chart.legend.markers.template;
		markerTemplate.width = 10;
		markerTemplate.height = 10;
		markerTemplate.stroke = am4core.color("#ff0000");

		categoryAxis.renderer.labels.template.fontSize = 14;

	}); // end am4core.ready()

	function toggleVisibility(item) {
		if (item.isVisible()) {
			item.hide();
		} else {
			item.show();
		}
	}
</script>