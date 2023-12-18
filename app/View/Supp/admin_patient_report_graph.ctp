<?php
if(json_decode($chart_data,true)){
    ?>

    <?php echo $this->Html->script(array('Chart.bundle.min.js'));?>

    <canvas id="myChart" style="position: relative;"  height="130"></canvas>
    <script>




        var backgourndArray = [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(101, 255, 94, 0.5)'

        ];
        var borderColor = [
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(101, 255, 94, 1)'

        ];

        var backgoround =[];
        var border =[];
        var counter =0;
        var data_arr = '<?php echo $chart_data; ?>';
        var data = JSON.parse(data_arr);
        $.each(data['label'], function( index, value ) {

            if(counter == (backgourndArray.length)){
                counter = 0;
            }

            backgoround[index] = backgourndArray[counter];
            border[index] = borderColor[counter++];


        });



        //console.log(data);
        var ctx = document.getElementById("myChart").getContext('2d');

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data['label'],
                datasets: [{
                    label: "",
                    data: data['data'],
                    backgroundColor: backgoround,
                    borderColor: border,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            autoSkip : false,
                            beginAtZero:true
                        }
                    }],
                    xAxes: [{
                        stacked: false,
                        scaleLabel: {
                            labelString: ''
                        },
                        ticks: {
                            autoSkip : false,
                            beginAtZero:true
                        }
                    }]
                },
                legend: {
                    display: false
                },
                onClick: graphClickEvent,
                animation: {
                    duration: 500,
                    onComplete: function() {
                        var ctx = this.chart.ctx;
                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, 'normal', Chart.defaults.global.defaultFontFamily);
                        ctx.fillStyle = "black";
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'BOTTOM';
                        this.data.datasets.forEach(function(dataset) {

                            var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                return previousValue + currentValue;
                            });

                            for (var i = 0; i < dataset.data.length; i++) {
                                for (var key in dataset._meta) {
                                    var model = dataset._meta[key].data[i]._model;
                                    var val = Math.floor(((dataset.data[i]/total) * 100)+0.5);
                                    val = isNaN(val)?0:val;
                                    ctx.fillText(dataset.data[i]+"%", model.x, model.y - 0.5);

                                }
                            }
                        });
                    }
                }

            }
        });
        function graphClickEvent(event, array){
            if(array[0]){
                load_sub_module_bar_chart(array[0]._model.label);
            }
        }

        function load_sub_module_bar_chart(module_name){
            var tid = $(".thinapp_id").val();
            var fd = $(".from_date").val();
            var td = $(".to_date").val();
            $.ajax({
                type:'POST',
                url: baseurl+"/admin/supp/load_bar_chart_sub_module_graph",
                data:{tid:tid,fd:fd,td:td,mn:module_name},
                beforeSend:function(){
                    $(".ajax-loader").show();
                },
                success:function(data){

                    $(".sub_module_chart_div").html(data);
                    $(".ajax-loader").hide();
                    $(".row_master2").slideDown(500);
                },
                error: function(data){
                    $(".ajax-loader").show();
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });

        }



    </script>

<?php }else{ ?>
    <strong class="record_title">No data Available</strong>
<?php } ?>

<style>



    .record_title {
        width: 100%;
        position: absolute;
        left: 45%;
        top: 50%;
        font-size: 15px;

    }


</style>

