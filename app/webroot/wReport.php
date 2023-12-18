<?php
date_default_timezone_set("Asia/Kolkata");
include_once "webservice/Custom.php";


$html_array=array();
$mobile_array =explode(",",SEND_ALERT_MOBILES);
$email_array =explode(",",SEND_ALERT_EMAILS);
$status = $_REQUEST['s'];
$date = $_REQUEST['d'];
$list = Custom::getLastSixMonthAverage($date,$status);

$title_date =date('d-M-Y',strtotime($date));
$title = ucfirst(strtolower($status))." Report For $title_date";
$reportTitle = $title;

$css = ($status=='PAID')?"background-color: green;color:#fff;":"background-color: blue;color:#fff;";


?>

<html>
<title>
    <?php echo $title; ?>
</title>
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="mengage">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>

    <script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap4.min.js"></script>
    <script type="text/javascript" src="js/dataTableBundle.js"></script>
    <link rel="stylesheet" rel href="css/bootstrap4.min.css" >
    <link rel="stylesheet" href="css/dataTableBundle.css" >
    <style>
        td,th{
            font-size: 1rem;
        }
    </style>
</head>
<body>


<div class="container-fluid">
    <h3 style='<?php echo $css; ?>;text-align: center;width: 100%;font-size:1.1rem; padding:1rem 0;display: block;margin: 10px 0px;'><?php echo $status." TOKEN FEE REPORT FOR ".$title_date ?></h3>
    <table id='dataTable' class="table table-responsive-sm">
        <thead>
        <th>S.No</th>
        <th>App Name</th>
        <th>Last 6  Months Token</th>
        <th>Last 6  Months Avg.</th>
        <th>Last 6  Days Token</th>
        <th>Last 6  Days Avg.</th>
        <th>Last Week Token</th>
        <th>Last Week Avg.</th>
        <th>Percentage (%)</th>
        </thead>
        <tbody>
        <?php $tot_six_months = $tot_six_days = $tot_month_avg = $tot_day_avg = $counter =$tot_week_days=$tot_week_avg=0;
        foreach ($list as $key => $val){
            $s_no = ++$counter;
            $app_name = $val['app_name'];
            $last_six_months = $val['last_six_months'];
            $tot_six_months +=$last_six_months;
            $last_week_days = $val['last_week_days'];
            $tot_week_days+=$last_week_days;

            $last_six_days = $val['last_six_days'];
            $tot_six_days+=$last_six_days;


            $percentage = $val['percentage'].'%';

            $six_month_avg = $val['six_month_avg'];


            $tot_month_avg += $six_month_avg;

            $six_day_avg = $val['six_day_avg'];
            $tot_day_avg += $six_day_avg;

            $last_week_avg = $val['last_week_avg'];
            $tot_week_avg += $last_week_avg;

            $css = ($val['colour']=="red")?"background-color: red;color:#fff;":"";

            ?>

            <tr style='<?php echo $css; ?>'>
                <td><?php echo $s_no ?></td>
                <td><?php echo $app_name; ?></td>
                <td><?php echo $last_six_months; ?></td>
                <td><?php echo $six_month_avg; ?></td>
                <td><?php echo $last_six_days; ?></td>
                <td><?php echo $six_day_avg; ?></td>
                <td><?php echo $last_week_days; ?></td>
                <td><?php echo $last_week_avg; ?></td>
                <td><?php echo $percentage; ?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>


        <tr>
            <th></th>
            <th>Total</th>
            <th><?php echo $tot_six_months ?></th>
            <th><?php echo $tot_month_avg ?></th>
            <th><?php echo $tot_six_days ?></th>
            <th><?php echo $tot_day_avg ?></th>
            <th><?php echo $tot_week_days ?></th>
            <th><?php echo $tot_week_avg ?></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>


</body>

<script type="text/javascript">

    $(function(){
        var column = [0,1,2,3,4,5,6];
        $('#dataTable').DataTable({
            dom: 'Blfrtip',
            "bPaginate": false,
            lengthMenu: [
                [ 100, 150, 200, -1 ],
                [ '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            buttons: [
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '<?php echo $title; ?>',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '<?php echo $title; ?>',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '<?php echo $title; ?>',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '<?php echo $title; ?>',
                    exportOptions: {
                        columns: column
                    }

                }
            ]
        });
    })

</script>
</html>



