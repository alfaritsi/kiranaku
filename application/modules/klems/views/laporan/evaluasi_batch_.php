<script type="text/javascript" src="http://localhost:8080/105/kiranaku/assets/plugins/jQuery/jquery-3.3.1.min.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/js/flot/excanvas.min.js"></script><![endif]-->
<script type="text/javascript" src="http://localhost:8080/105/kiranaku/assets/plugins/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="http://localhost:8080/105/kiranaku/assets/plugins/flot/jquery.flot.time.js"></script>    
<script type="text/javascript" src="http://localhost:8080/105/kiranaku/assets/plugins/flot/jshashtable-2.1.js"></script>    
<script type="text/javascript" src="http://localhost:8080/105/kiranaku/assets/plugins/flot/jquery.numberformatter-1.2.3.min.js"></script>
<script type="text/javascript" src="http://localhost:8080/105/kiranaku/assets/plugins/flot/jquery.flot.symbol.js"></script>
<script type="text/javascript" src="http://localhost:8080/105/kiranaku/assets/plugins/flot/jquery.flot.axislabels.js"></script>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/klems/laporan/evaluasi_batch.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
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
            return $.formatNumber(v, { format: "#,###", locale: "us" });                        
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
<!-- HTML -->
<div id="flot-placeholder" style="width:450px;height:300px;margin:0 auto"></div>