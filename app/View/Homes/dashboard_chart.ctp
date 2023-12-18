<script src="<?php echo Router::url('/js/chart.js',true); ?>"></script>
<div style="padding: 10px;border-bottom: 1px solid;margin-bottom: 20px;" class="col-sm-12 col-md-6 col-lg-6">
    <h3>Patient Visit Graph</h3>
    <canvas style="width: 100% !important;" id="PATIENT_VISITOR"></canvas>
</div>

<div style="padding: 10px;border-bottom: 1px solid;margin-bottom: 20px;" class="col-sm-12 col-md-6 col-lg-6">
    <h3>Patient TAT Average Graph (In Minutes)</h3>
    <canvas  id="PATIENT_TAT_AVG"></canvas>
</div>

<div style="padding: 10px;border-bottom: 1px solid;margin-bottom: 20px;" class="col-sm-12 col-md-6 col-lg-6">
    <h3>Counter TAT Average Graph (In Minutes)</h3>
    <canvas  id="COUNTER_WISE_TAT_AVG"></canvas>
</div>

<script type="text/javascript">
    var ctx = document.getElementById('PATIENT_VISITOR');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo '"' . implode('","', $label_array) . '"'; ?>],
            datasets:<?php echo json_encode($patient_visitor); ?>
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,

                }

            }
        }
    });

    var ctx2 = document.getElementById('PATIENT_TAT_AVG');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: [<?php echo '"' . implode('","', $label_array) . '"'; ?>],
            datasets:<?php echo json_encode($patient_tat_avg); ?>
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true

                }

            }
        }
    });

    var ctx3 = document.getElementById('COUNTER_WISE_TAT_AVG');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: [<?php echo '"' . implode('","', $label_array) . '"'; ?>],
            datasets:<?php echo json_encode($counter_wise_tat_avg); ?>
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true

                }

            }
        }
    });

</script>
