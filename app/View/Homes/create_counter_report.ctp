<?php
$login = $this->Session->read('Auth.User');


error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<title><?php echo $title; ?></title>
<?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js')); ?>
<?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css'),array("media"=>'all')); ?>


<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


<style>

    .log_ul{
        margin: 0;
        padding: 0;
    }
    .log_ul li{
        display: block;
        width: 100%;
    }
    .container{
        width: 100% !important;
        padding-right: 0px !important;
        padding-left: 0px !important;

    }
    .row{
        margin-right: 0px !important;
        margin-left: 0px !important;
    }
</style>

<div class="container_1">
    <div class="row">
        <h6 style="text-align: center;width: 100%;"><?php echo $title; ?></h6>
        <?php $title = "Time Report"; if(!empty($final_array)){ ?>
            <div class="table-responsive">

                <table id="report_table" class="table">
                    <thead>

                    <tr>
                        <th>S.No</th>
                        <th>Patient Name</th>
                        <th>Token</th>
                        <th>Date</th>
                        <th>Log</th>
                        <th>Waiting Time</th>
                        <th>Patient Delay</th>
                        <th>Activity Time</th>
                        <th>Turn Around Time</th>


                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($final_array as $key => $list){   ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><?php echo $list['appointment_patient_name']; ?></td>
                            <td><?php echo $list['queue_number']; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($list['created'])); ?></td>
                            <td><ul class="log_ul"><?php
                                    $log_array = json_decode($list['log'],true);
                                    $last_time = "";

                                    $tokenCloseTime = $tokenGenerateTime =$waiting_time = $patient_delay = $lastScreenTime = $firstScreenTime ="";

                                    foreach ($log_array as $k => $val){

                                        $flag = $val['flag'];
                                        $counter_name = $val['counter'];
                                        $label = "";
                                        $time = date('h:i:s A',strtotime($val['time']));

                                        if($flag=="APPOINTMENT_BOOKED"){
                                                $label = "Token Generate - <span>$time</span>";
                                            $tokenGenerateTime =  $time;
                                        }else if($flag=="CHANGE_TO_PAYMENT_COUNTER"){
                                            $label = "Screen display by $counter_name - <span>$time</span>";
                                            if(empty($waiting_time) && !empty($tokenGenerateTime)){
                                                $date1 = new DateTime($tokenGenerateTime);
                                                $date2 = new DateTime($time);
                                                $diff = $date1->diff($date2);
                                                $waiting_time =  $diff->format('%h:%i:%s');
                                            }
                                            if(empty($firstScreenTime)){
                                                $firstScreenTime = $time;
                                            }else{
                                                $lastScreenTime = $time;
                                            }


                                        }else if($flag=="PLAY"){
                                            $label = "Token announce by $counter_name - <span>$time</span>";
                                        }else if($flag=="SKIPPED"){
                                            $label = "Token Skip by $counter_name - <span>$time</span>";
                                        }else if($flag=="CLOSED"){
                                            $label = "Token close by $counter_name - <span>$time</span>";
                                            $tokenCloseTime =$time;
                                        }else if($flag=="IVR_CALL"){
                                            $label = "IVR Call by $counter_name - <span>$time</span>";
                                        }

                                        if($last_time != $time){
                                            echo "<li>$label</li>";
                                        }
                                        $last_time = $time;


                                    }

                            ?></ul></td>
                            <td>
                                <?php echo $waiting_time; ?>
                            </td>

                            <td>
                                <?php

                                if(!empty($firstScreenTime) && !empty($lastScreenTime)){
                                        $date1 = new DateTime($firstScreenTime);
                                        $date2 = new DateTime($lastScreenTime);
                                        $diff = $date1->diff($date2);
                                        echo $diff->format('%h:%i:%s');
                                    }
                                ?>
                            </td>


                            <td>
                                <?php

                                if(!empty($tokenCloseTime)){
                                    $lastScreenTime = !empty($lastScreenTime)?$lastScreenTime:$firstScreenTime;
                                    $date1 = new DateTime($lastScreenTime);
                                    $date2 = new DateTime($tokenCloseTime);
                                    $diff = $date1->diff($date2);
                                    echo $diff->format('%h:%i:%s');
                                }
                                ?>
                            </td>


                            <td>
                                <?php
                                if(!empty($tokenCloseTime) && !empty($tokenGenerateTime)){
                                    $date1 = new DateTime($tokenGenerateTime);
                                    $date2 = new DateTime($tokenCloseTime);
                                    $diff = $date1->diff($date2);
                                    echo $diff->format('%h:%i:%s');
                                }
                                ?>
                            </td>



                        </tr>
                    <?php } ?>
                    </tbody>

                </table>

            </div>
        <?php }else{ ?>
            <div class="no_data" style="width: 100%;">
                <h2 style="text-align: center;width: 100%; ">No record found</h2>
            </div>
        <?php } ?>
        <div class="clear"></div>

    </div>
</div>



<script>
    $(document).ready(function(){


        var title = '<?php echo $title; ?>';
        $('#report_table').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ -1 ],
                [ 'Show all' ]
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            searching: false,
            paging: false,
            info: false,
            buttons: [

                {
                    extend: 'csv',
                    header: true,
                    footer:true,


                },
                {
                    extend: 'excel',
                    header: true,
                    footer:true,


                },
                {
                    extend: 'pdf',
                    header: true,
                    footer:true,

                }

            ]
        });

    });
</script>





