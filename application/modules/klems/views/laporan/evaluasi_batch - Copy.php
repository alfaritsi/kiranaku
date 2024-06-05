<!--
/*
@application  : KLEMS (Kirana Learning Management System)
@author     : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->
<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
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
						<div class="col-sm-2">Sesi</div>
						<div class="col-sm-2">: <?php echo $batch[0]->kode_program_batch;?></div><br>
						<?php
						$list_trainer = str_replace('|luar','(Eksternal)',substr($batch[0]->list_trainer,0,-1));
						$list_trainer = str_replace('|dalam','(Internal)',$list_trainer);
						?>
						<div class="col-sm-2">Nama Trainer</div>
						<div class="col-sm-4">: <?php echo $list_trainer;?></div><br>
						<div class="col-sm-2">Tanggal</div>
						<div class="col-sm-2">: <?php echo $batch[0]->tanggal_awal." - ".$batch[0]->tanggal_akhir;?></div><br>
						<div class="col-sm-2">Lokasi</div>
						<div class="col-sm-2">: <?php echo $batch[0]->tempat;?></div><br><br>
						<?php 
						if($feedback_pertanyaan){
							echo"<div class='col-sm-6'><div class='box box-success'>";
							echo'
								<div class="box-header">
									<div class="box-title pull-right"><strong>KPI = 4</strong></div>
								</div>
								<table class="table table-bordered table-striped">
							';
							$nn = 0;
							foreach($feedback_pertanyaan as $tanya){
								$nn++;
								if($nn==1){
								echo '
									<thead>
										<tr>
											<th width="3%">No</th>
											<th>Dimensi</th>
											<th width="10%"><center>Nilai</center></th>
										</tr>
									</thead>
								';
								}
								$n = 0;
								$nil = 0;
								echo "<tbody>";
								foreach($tanya as $t){
									$n++;
									$nil += $t->average;
									echo "<tr>";
									echo "<td>".$n."</td>";
									echo "<td>".$t->pertanyaan."</td>";
									echo "<td align='right'>".number_format($t->average, 1, '.', ',')."</td>";
									echo "</tr>";
								}
								echo "</tbody>";
								foreach($tanya as $t2){}
								echo '
									<thead>
										<tr>
											<th colspan="2">'.$t2->nama_kategori.'</th>
											<th><div align="right">'.number_format($nil/$n, 1, '.', ',').'</div></th>
										</tr>
									</thead>
								';
								
							}
							echo "</table>";
							echo"</div></div>";
						}		
						//agregat
						echo"
						<div class='col-sm-6'>
							<div class='box box-success'>
								<div class='box-header'>
									<div class='box-title'><strong>Average Feedback Trainer KPI 4</strong></div>
								</div>
								<div class='box-body'>
									<div id='bar-chart' style='height: 300px;'></div>
								</div>
							</div>
						</div>	
						";
						//feedback kategori
						echo"
						<div class='col-sm-6'>
							<div class='box box-success'>
								<div class='box-header'>
									<div class='box-title'><strong>Average Feedback Trainer KPI 4</strong></div>
								</div>
								<div class='box-body'>
									<div id='flot-placeholder' style='height: 300px;'></div>
								</div>
							</div>
						</div>	
						";
						?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/klems/laporan/evaluasi_batch.js"></script>-->
<!-- FLOT CHARTS -->
<script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script>
<!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
<script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/flot/excanvas.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.time.js"></script>    
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/flot/jshashtable-2.1.js"></script>    
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/flot/jquery.numberformatter-1.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.symbol.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.axislabels.js"></script>    
<script>
	/*
	 * BAR CHART
	 * ---------                 
	 */

	var bar_data = {
		data: [
		<?php
		foreach($feedback_kategori as $kategori){
			echo"['".$kategori->nama."', ".$kategori->average."],";
		}
		?>
		],
		color: "#3c8dbc"
	};
	$.plot("#bar-chart", [bar_data], {
		rotated: true,
		grid: {
			borderWidth: 1,
			borderColor: "#f3f3f3",
			tickColor: "#f3f3f3"
		},
		series: {
			bars: {
				show: true,
				barWidth: 0.5,
				align: "center"
			}
		},
		xaxis: {
			mode: "categories",
			tickLength: 0
		}
	});
	/* END BAR CHART */
</script>
<script>
//******* Precious Metal Price - HORIZONTAL BAR CHART
var rawData = [
    [1582.3, 0], //Gold/oz
    [28.95, 1],  //Silver/oz
    [1603, 2],   //PLATINUM /oz
    [774, 3],     //PALLADIUM /oz
    [1245, 4],     //Rhodium
    [85, 5],       //Ruthenium 
    [1025, 6]      //Iridium 
];

var dataSet = [
    { label: "Precious Metal Price", data: rawData, color: "#E8E800" }
];

var ticks = [
    [0, "Gold"], [1, "Silver"], [2, "Platinum"], [3, "Palldium"], [4, "Rhodium"], [5, "Ruthenium"], [6, "Iridium"]
];


var options = {
    series: {
        bars: {
            show: true
        }
    },
    bars: {
        align: "center",
        barWidth: 0.5,
        horizontal: true,
        fillColor: { colors: [{ opacity: 0.5 }, { opacity: 1}] },
        lineWidth: 1
    },
    xaxis: {
        axisLabel: "Price (USD/oz)",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 10,
        max: 5,
        tickColor: "#5E5E5E",                        
        tickFormatter: function (v, axis) {
            return (v < 1000 ? v : $.formatNumber(v, { format: "#,###", locale: "us" }));                        
        },
        color:"black"
    },
    yaxis: {
        axisLabel: "Precious Metals",
        axisLabelUseCanvas: true,
        axisLabelFontSizePixels: 12,
        axisLabelFontFamily: 'Verdana, Arial',
        axisLabelPadding: 3,
        tickColor: "#5E5E5E",        
        ticks: ticks, 
        color:"black"
    },
    legend: {
        noColumns: 0,
        labelBoxBorderColor: "#858585",
        position: "ne"
    },
    grid: {
        hoverable: true,
        borderWidth: 2,        
        backgroundColor: { colors: ["#171717", "#4F4F4F"] }
    }
};

$(document).ready(function () {
    $.plot($("#flot-placeholder"), dataSet, options);    
    $("#flot-placeholder").UseTooltip();
});



var previousPoint = null, previousLabel = null;

$.fn.UseTooltip = function () {
    $(this).bind("plothover", function (event, pos, item) {
        if (item) {
            if ((previousLabel != item.series.label) || 
                 (previousPoint != item.dataIndex)) {
                previousPoint = item.dataIndex;
                previousLabel = item.series.label;
                $("#tooltip").remove();

                var x = item.datapoint[0];
                var y = item.datapoint[1];

                var color = item.series.color;
                //alert(color)
                //console.log(item.series.xaxis.ticks[x].label);                
                
                showTooltip(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.label + "</strong><br>" + item.series.yaxis.ticks[y].label + 
                        " : <strong>" + $.formatNumber(x, { format: "#,###", locale: "us" })  + "</strong> USD/oz");                
            }
        } else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
};

function showTooltip(x, y, color, contents) {
    $('<div id="tooltip">' + contents + '</div>').css({
        position: 'absolute',
        display: 'none',
        top: y - 10,
        left: x + 10,
        border: '2px solid ' + color,
        padding: '3px',
        'font-size': '9px',
        'border-radius': '5px',
        'background-color': '#fff',
        'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
        opacity: 0.9
    }).appendTo("body").fadeIn(200);
}
</script>