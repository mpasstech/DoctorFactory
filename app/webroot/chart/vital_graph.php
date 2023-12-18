<?php
$constant_path = explode(DIRECTORY_SEPARATOR,__FILE__);
$tot =count($constant_path);
unset($constant_path[$tot-2]);
unset($constant_path[$tot-1]);
include_once implode("/",$constant_path)."/constant.php";
include_once implode("/",$constant_path)."/webservice/Custom.php";
$q_data = $_REQUEST;
$param = @$q_data['p'];

$size = "S";
/* THIS IS PARAM VITAL INFORMATION
         THIN_APP_ID - MASTER_VITAL_ID - PATIENT_ID - PATIENT_TYPE
 */
$cat_array = explode("-",$param);

$thin_app_id = @$cat_array[0];
$vital_id = @$cat_array[1];
$patient_id = @$cat_array[2];
$patient_type = @$cat_array[3];
if($patient_type=="CH"){
    $patient_type = "CHILDREN";
}else{
    $patient_type = "CUSTOMER";
}
$chart_data = @Custom::get_vital_graph_value($thin_app_id, $vital_id, $patient_id, $patient_type);
?>
<html>
<head>
    <script  src="<?php echo SITE_PATH . 'js/jquery.js' ?>" ></script>
    <script  src="<?php echo SITE_PATH . 'js/Chart.bundle.min.js' ?>" ></script>

</head>
<body>


<?php


if($chart_data){
if($vital_id != 9) {
    ?>
    <div style="position: relative;" width="100" height="50">
        <canvas id="myChart3" style="position: relative;" width="100" height="50"></canvas>

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

        var backgoround = [];
        var border = [];
        var counter = 0;
        var data_arr = <?php echo json_encode($chart_data, true); ?>;
        var data = JSON.parse(data_arr);

        var config = {

            type: 'line',
            data: {
                labels: data['dates'],
                datasets: [{
                    data: data['vitalValue'],
                    backgroundColor: 'rgba(244, 0, 0, 0.5)',
                    borderColor: 'rgb(53, 239, 16)',
                    label: '',
                    pointBackgroundColor: 'rgba(244, 0, 0, 0.5)',
                    pointBorderColor: 'rgba(244, 0, 0, 0.5)',
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHitRadius:35,
                    fill: 0,
                    lineTension: 0
                }]
            },
            options: {
                title: {
                    display: true,
                    text: data['vitalName']
                },
                responsive: true,
                maintainAspectRatio: true,
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'black',
                        defaultFontSize: 15
                    }
                }, plugins: {
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
                        drawBorder: 0,
                        lineWidth: 25,
                         type: "time",
                        time: {
                            format: "DD/MM/YYYY HH:mm",
                            unit: 'day',
                            unitStepSize: 2,
                            displayFormats: {
                                'minute': 'DD/MM/YYYY',
                                'hour': 'DD/MM/YYYY',
                                'day': 'DD/MM/YYYY'
                            },
                            tooltipFormat: 'DD/MM/YYYY HH:mm'
                        },
                        scaleLabel: {
                            display: false,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        drawBorder: 0,
                        scaleLabel: {
                            display: false,
                            labelString: data['vitalUnit']
                        }
                    }]
                },
                layout: {
                    padding: {
                        left: 30
                    }
                }
            }
        };
        window.onload = function () {
            var ctx = document.getElementById("myChart3").getContext('2d');
            Chart.defaults.global.defaultFontSize = 20;

            Chart.defaults.global.defaultFontColor = "rgba(0, 0, 0, 0.88)";
            Chart.defaults.global.defaultFontStyle = "bold";
            window.myLine = new Chart(ctx, config);
        };
    </script>


<?php } else{ $chart_data = json_decode($chart_data,true); ?>

    <!--table width="100%" height="100%" border="1px solid skyblue">
        <tr><th colspan="2" align="center">Other Notes</th></tr>
        <tr><th width="20%">Date</th><th>Notes</th></tr>

        <?php foreach($chart_data['vitalValue'] AS $val) { ?>
            <tr><td><?php echo $val['x']; ?></td><td><?php echo $val['y']; ?></td></tr>
        <?php } ?>
    </table-->

    <div class="notes-container">
        <ul class="notes-container-ul">
            <li class="title-container">
                <ul>
                    <li>Other Notes</li>
                </ul>
            </li>
            <li class="name-container">
                <ul>
                    <li class="date-container">Date</li>
                    <li class="note-container">Notes</li>
                </ul>
            </li>
            <?php foreach($chart_data['vitalValue'] AS $val) { ?>
                <li class="value-container">
                    <ul>
                        <li class="date-container"><?php echo $val['x']; ?></li>
                        <li class="note-container"><?php echo $val['y']; ?></li>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </div>

<?php }
}else{ ?>
    <strong class="record_title">No Data Available</strong>
<?php } ?>


</body>
<style>

    canvas{width:100% !important;height:100% !important;}

    .notes-container {
        width: 100%;
        height: 100%;
        overflow: scroll;
        margin:0;
        padding:0;
    }
    ul{
        margin:0;
        padding:0;
    }
    li{
        margin:0;
        padding:0;
    }
    .notes-container-ul {
        list-style: none;
    }
    .title-container ul {
        list-style: none;
    }
    .title-container ul li {
        text-align: center;
        font-size: x-large;
        font-weight: bold;
        background-color: #1a7cf9;
        color: #FFFFFF;
    }
    .name-container ul li {
        border: 1px solid #33A851;
        border-top: none;
    }
    .name-container {
        list-style: none;
    }
    .name-container ul {
        list-style: none;
    }
    .name-container ul li {
        float: left;
        text-align: center;
    }
    .name-container ul li {
        float: left;
        text-align: center;
        font-weight: bold;
    }
    .date-container {
        width: calc(25% - 4px);;
    }
    .note-container {
        width: 75%;
    }
    .value-container ul {
        list-style: none;
    }
    .value-container ul li {
        float: left;
        text-align: center;
        border: 1px solid #33A851;
        border-top: none;
    }

</style>
</html>