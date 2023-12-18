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
        var border =data_set =[];
        var counter =0;
        var data_arr = '<?php echo $chart_data; ?>';
        var data_arr_2 = '<?php echo $chart_data_2; ?>';
        var data = JSON.parse(data_arr);
        var data_2 = JSON.parse(data_arr_2);
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
            showTooltips: false,
            type: 'bar',
            data: {
                labels: data['label'],
                datasets: [{
                    label: "",
                    data: data['data'],
                    backgroundColor: backgoround,
                    borderColor: border,
                    borderWidth: 1
                },{
                    label: "",
                    data: data_2['data'],
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
                    onProgress: function () {
                        var chartInstance = this.chart;
                        var ctx = chartInstance.ctx;
                        ctx.textAlign = "center";
                        ctx.fillStyle = "black";
                        ctx.font = "normal 10px";

                        //get the current items value

                        var global_total = 0;
                        Chart.helpers.each(this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                return previousValue + currentValue;
                            });
                            if(global_total==0){
                                global_total = total
                            }

                            Chart.helpers.each(meta.data.forEach(function (bar, index) {
                                ctx.save();
                                // Translate 0,0 to the point you want the text


                                var currentValue = dataset.data[index];
                                var extra_words = currentValue.toString().length;


                                //calculate the precentage based on the total and current item, also this does a rough rounding to give a whole number
                                var precentage = ((currentValue/global_total) * 100)
                                precentage = precentage.toFixed(2);
                                if(precentage >= 10){
                                    ctx.translate(bar._model.x+4, bar._model.y +42+extra_words);
                                }else{
                                    var value = dataset.data[index];

                                    ctx.translate(bar._model.x+4, bar._model.y - (35+extra_words));
                                }
                                // Rotate context by -90 degrees
                                ctx.rotate(-0.5 * Math.PI);

                                // Draw text


                                ctx.fillText((dataset.data[index]).toLocaleString('en')+" ("+precentage+"%)", 0, 0);
                                ctx.restore();


                            }),this)
                        }),this);
                    }
                },
                tooltips: {
                    enabled: false
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

