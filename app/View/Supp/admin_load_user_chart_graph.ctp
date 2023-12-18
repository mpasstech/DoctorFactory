<?php
if(json_decode($chart_data,true)){
    ?>

    <?php echo $this->Html->script(array('Chart.bundle.min.js'));?>
    <canvas id="myChart3" style="position: relative;"  height="130"></canvas>
    <script>

        var backgourndArray = [
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(101, 255, 94, 0.5)',
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)'


        ];
        var borderColor = [
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(101, 255, 94, 1)',
            'rgba(255,99,132,1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)'


        ];

        var backgoround =[];
        var border =[];
        var counter =0;
        var data_arr = '<?php echo $chart_data; ?>';
        console.log(data_arr.length);
        var data = JSON.parse(data_arr);
        $.each(data['label'], function( index, value ) {

            if(counter == (backgourndArray.length)){
                counter = 0;
            }

            backgoround[index] = backgourndArray[counter];
            border[index] = borderColor[counter++];


        });



        //console.log(data);
        var ctx = document.getElementById("myChart3").getContext('2d');

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
                                    if(i==2){
                                        ctx.fillText(dataset.data[i]+"%", model.x, model.y - 0.5);

                                    }else{
                                        ctx.fillText(dataset.data[i], model.x, model.y - 0.5);

                                    }
                                }
                            }
                        });
                    }
                }

            }
        });
        function graphClickEvent(event, array){
            if(array[0]){

            }
        }

    </script>

<?php }else{ ?>
    <strong class="record_title">No data Available</strong>
<?php } ?>



