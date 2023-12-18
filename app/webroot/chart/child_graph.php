<?php
$constant_path = explode(DIRECTORY_SEPARATOR,__FILE__);
$tot =count($constant_path);
unset($constant_path[$tot-2]);
unset($constant_path[$tot-1]);
include_once implode("/",$constant_path)."/constant.php";
include_once implode("/",$constant_path)."/webservice/Custom.php";
$q_data = $_REQUEST;
$thin_app_id = @$q_data['t'];
$child_id = @$q_data['c'];
$cat = @$q_data['cat'];

$cat_array = explode("-",$cat);

$cat = @$cat_array[0];
$size = @$cat_array[1];


if($cat==1){
    $cat = "HEIGHT";
}else if($cat==2){
    $cat = "WEIGHT";
}else{
    $cat = "CIRCUMFERENCE";
}
$chart_data = @Custom::get_child_graph($thin_app_id,$child_id,$cat);

?>
<html>
<head>
    <script  src="<?php echo SITE_PATH . 'js/jquery.js' ?>" ></script>
    <script  src="<?php echo SITE_PATH . 'js/Chart.bundle.min.js' ?>" ></script>
    <script  src="<?php echo SITE_PATH . 'js/canvas2image.js' ?>" ></script>

</head>
<body>


<?php


if($chart_data){
    ?>

    <canvas id="myChart3" style="position: relative;" width="100" height="50" ></canvas>
    <script>

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
        var data_arr = '<?php echo $chart_data; ?>';
        var size = '<?php echo $size; ?>';
        console.log(data_arr.length);
        var data = JSON.parse(data_arr);
        var g_data =[];
        $.each(data['label'], function( index, value ) {

            if(counter == (backgourndArray.length)){
                counter = 0;
            }

            backgoround[index] = backgourndArray[counter];
            border[index] = borderColor[counter++];


        });

        $.each(data['data'], function( index, value ) {
            if(true) {
                //var data_val = value.split(',');
                var point = 0;
                var fill = false;

                if(parseInt(index) == 3){
                    if(size=="S"){
                        point =10;
                    }else{
                        point =8.0;
                    }
                }
                var  borderWidth =6;
                if(size=="S"){
                    borderWidth =13;
                }
                g_data.push({
                    data: value,
                    backgroundColor: backgoround[index],
                    borderColor: border[index],
                    label:'',
                    pointBackgroundColor:backgoround[index],
                    pointBorderColor:border[index],
                    borderWidth:borderWidth,
                    pointRadius:point,
                    pointHitRadius:35,
                    fill: fill,
                    lineTension:0
                });
            }
        });
        var config = {

            type: 'line',

            data: {
                labels: data['label'],
                datasets: g_data
            },
            options: {
                bezierCurve : false,
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
                    enabled: false
                },
                scales: {
                    xAxes: [{
                        display: true,
                        drawBorder:0,
                        lineWidth:10,
                        scaleLabel: {
                            display: false,

                            labelString: 'Month'

                        }
                    }],
                    yAxes: [{
                        display: true,
                        drawBorder:0,
                        scaleLabel: {
                            display: false,
                            labelString: 'Value'
                        },
                        ticks: {
                            beginAtZero: true
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
        window.onload = function() {
            var ctx = document.getElementById("myChart3").getContext('2d');
            Chart.defaults.global.defaultFontSize = 40;
            if(size=='S'){
                Chart.defaults.global.defaultFontSize = 70;
                Chart.defaults.global.defaultFontColor = "rgba(0, 0, 0, 0.88)";
                Chart.defaults.global.defaultFontStyle = "bold";
                window.myLine = new Chart(ctx, config);
            }else{

                Chart.defaults.global.defaultFontColor = "rgba(0, 0, 0, 0.88)";
                Chart.defaults.global.defaultFontStyle = "bold";
                window.myLine = new Chart(ctx, config);
            }

            <?php if($size == "S"){ ?>
            var inter = setInterval(function () {
                var canvas  = document.getElementById("myChart3");
                var dataUrl = canvas.toDataURL('image/png');
                var img = new Image();
                img.src = dataUrl;
                canvas.remove();
                document.body.appendChild(img);
                clearInterval(inter);
            },1000);


            <?php } ?>


            <?php if($size == "S"){ ?>
            var inter = setInterval(function () {
                var canvas  = document.getElementById("myChart3");
                var dataUrl = canvas.toDataURL('image/png');
                var img = new Image();
                img.src = dataUrl;
                canvas.remove();
                document.body.appendChild(img);
                clearInterval(inter);
            },1000);
            <?php } ?>



        };





    </script>

<?php }else{ ?>
    <strong class="record_title">No Data Available9</strong>
<?php } ?>


</body>
<style>
    .record_title{
        text-align: center;
        width: 100%;
        display: block;
        margin: 20px 0px;
    }
    <?php if($size=="S"){ ?>
    canvas, img{width:1000px !important;height:600px !important;}
    <?php }else{ ?>
    canvas, img{width:900px !important;height:400px !important;}
    <?php } ?>

</style>
</html>




