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

$chart_data = @Custom::get_appointment_stats_graph_value($thinappID);

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
    <canvas id="myChart3" style="position: relative;" width="100" height="50" ></canvas>

</div>
    <script>
        var size = "L";
        var backgourndArray = [
            'rgba(244, 0, 0, 0.5)',
            'rgb(255, 255, 0)',
            'rgb(12, 173, 39)',
            'rgba(0, 0, 0, 1)',
            'rgb(255, 255, 0)',
            'rgba(244, 0, 0, 0.5)',
            'rgba(255, 206, 86, 0.5)'


        ];
        var borderColor = [
            'rgba(244, 0, 0, 0.5)',
            'rgb(255, 255, 0)',
            'rgb(12, 173, 39)',
            'rgba(0, 0, 0, 1)',
            'rgb(255, 255, 0)',
            'rgba(244, 0, 0, 0.5)',
            'rgba(255, 206, 86, 0.5)'


        ];

        var backgoround =[];
        var border =[];
        var counter =0;
        var data_arr = '<?php echo json_encode($chart_data,true); ?>';
        var data = JSON.parse(data_arr);
        var ticks = {beginAtZero: true};
        if( (data['max'] > 0)){
            ticks = {
                min: data['min'],
                max: data['max']

            };
        }

        var font_size  = ($(window).width() < 300)?12:20;
        var border_width  = ($(window).width() < 300)?1:3;

        var config = {

            type: 'line',
            data: {
                labels:data['labels'],
                datasets: [{
                    data: data['data'],
                    backgroundColor: 'rgba(244, 0, 0, 0.5)',
                    borderColor: 'rgb(53, 239, 16)',
                    label:'',
                    pointBackgroundColor:'rgba(244, 0, 0, 0.5)',
                    pointBorderColor:'rgba(244, 0, 0, 0.5)',
                    borderWidth:border_width,
                    pointRadius:5,
                    pointHitRadius:35,
                    fill: 0,
                    lineTension:0
                }]
            },
            options: {
                title: {
                    display: true,
                    text: 'Appointment Statics'
                },
                responsive:true,
                maintainAspectRatio:true,
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'black',
                        defaultFontSize:15
                    }
                },plugins: {
                    filler: {
                        propagate: true
                    }
                },
                tooltips: {
                    enabled: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        drawBorder:0,
                        lineWidth:100,
                        scaleLabel: {
                            display: false,

                            labelString: 'Day'

                        }
                    }],
                    yAxes: [{
                        display: true,
                        drawBorder:0,
                        scaleLabel: {
                            display: false,
                            labelString: 'Appointments'
                        },
                        ticks: ticks
                    }]
                },
                layout: {
                    padding: {
                        left: 30
                    }
                }
            }
        };
        window.onload = function() {


            var ctx = document.getElementById("myChart3").getContext('2d');
            Chart.defaults.global.defaultFontSize = font_size;
            Chart.defaults.global.defaultFontColor = "rgba(0, 0, 0, 0.88)";
            Chart.defaults.global.defaultFontStyle = "bold";
            window.myLine = new Chart(ctx, config);
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




