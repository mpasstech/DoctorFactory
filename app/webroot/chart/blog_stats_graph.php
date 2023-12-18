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

$chart_data = @Custom::get_blog_stats_graph_value($thinappID);

?>
<html>
<head>
    <script  src="<?php echo SITE_PATH . 'js/jquery.js' ?>" ></script>
    <script  src="<?php echo SITE_PATH . 'js/Chart.bundle.min.js' ?>" ></script>

</head>
<body>
<?php


if($chart_data){
    ?>

    <div style="position: relative;" width="100" height="50">
        <canvas id="canvas" style="position: relative;" width="100" height="50"></canvas>
    </div>
    <script>
        var font_size  = ($(window).width() < 300)?10:20;

        var barChartData = {
            labels: [<?php echo $chart_data['lable']; ?>],
            datasets: [{
                label: 'Likes',
                backgroundColor: "rgb(39, 241, 244)",
                data: [<?php echo $chart_data['like']; ?>]
            }, {
                label: 'Shares',
                backgroundColor: "rgb(80, 244, 66)",
                data: [<?php echo $chart_data['share']; ?>]
            }, {
                label: 'Views',
                backgroundColor: "rgb(244, 241, 65)",
                data: [<?php echo $chart_data['view']; ?>]
            }]

        };
        window.onload = function() {
            var ctx = document.getElementById('canvas').getContext('2d');
            Chart.defaults.global.defaultFontSize = font_size;

            Chart.defaults.global.defaultFontColor = "rgba(0, 0, 0, 0.88)";
            Chart.defaults.global.defaultFontStyle = "bold";
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    title: {
                        display: true,
                        text: 'Blog Statics'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            });
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




