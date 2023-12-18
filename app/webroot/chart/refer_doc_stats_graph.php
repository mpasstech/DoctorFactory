<?php
$constant_path = explode(DIRECTORY_SEPARATOR,__FILE__);
$tot =count($constant_path);
unset($constant_path[$tot-2]);
unset($constant_path[$tot-1]);
include_once implode("/",$constant_path)."/constant.php";
include_once implode("/",$constant_path)."/webservice/Custom.php";
$q_data = $_REQUEST;
$param = @$q_data['p'];
$thinappID = $param;

/* THIS IS PARAM appointment INFORMATION
         THIN_APP_ID
 */

$chart_data = @Custom::get_refer_doc_stats_graph_value($thinappID);

?>
<html>
<head>
    <script  src="<?php echo SITE_PATH . 'js/jquery.js' ?>" ></script>
    <script  src="<?php echo SITE_PATH . 'js/Chart.bundle.min.js' ?>" ></script>

</head>
<body>
<?php


if($chart_data && !($chart_data['totalRefer'] == 0 && $chart_data['totalPaid'] == 0 && $chart_data['totalContacted'] == 0 && $chart_data['totalDenied'] == 0 && $chart_data['totalConverted'] == 0 && $chart_data['totalRereffered'] == 0)){
    ?>


    <div id="canvas-holder" style="position: relative;" width="100" height="50">
        <canvas id="chart-area"></canvas>
    </div>
    <script>
        var font_size  = ($(window).width() < 300)?12:28;

        var config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        <?php echo $chart_data['totalRefer']; ?>,
                        <?php echo $chart_data['totalPaid']; ?>,
                        <?php echo $chart_data['totalContacted']; ?>,
                        <?php echo $chart_data['totalDenied']; ?>,
                        <?php echo $chart_data['totalConverted']; ?>,
                        <?php echo $chart_data['totalRereffered']; ?>
                    ],
                    backgroundColor: [
                        "rgb(39, 241, 244)",
                        "rgb(80, 244, 66)",
                        "rgb(244, 241, 65)",
                        "rgb(244, 60, 36)",
                        "rgb(38, 244, 179)",
                        "rgb(37, 199, 244)"
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    'Total Refer',
                    'Total Paid',
                    'Total Contacted',
                    'Total Denied',
                    'Total Converted',
                    'Total Rereffered'
                ]
            },
            options: {
                title: {
                    display: true,
                    text: 'Refer Statics'
                },
                tooltips: {
                    // Disable the on-canvas tooltip
                    enabled: true,
                    yPadding:20
                },
                responsive: true
            }
        };

        window.onload = function() {
            var ctx = document.getElementById('chart-area').getContext('2d');
            Chart.defaults.global.defaultFontSize = font_size;

            Chart.defaults.global.defaultFontColor = "rgba(0, 0, 0, 0.88)";
            Chart.defaults.global.defaultFontStyle = "bold";
            window.myPie = new Chart(ctx, config);
        };


    </script>


<?php }else{ ?>
    <strong class="record_title">No Data Available</strong>
<?php } ?>


</body>
<style>
    .record_title{
        text-align: center;
        width: 100%;
        display: block;
        margin: 20px 0px;
    }
    body{margin: 0px;}
    canvas{width:100% !important;height:100% !important;}

</style>
</html>




