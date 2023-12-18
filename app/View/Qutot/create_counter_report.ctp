<?php
$login = $this->Session->read('Auth.User');


error_reporting(E_ALL);
ini_set('display_errors', 1);

?>


<?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js')); ?>
<?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css'),array("media"=>'all')); ?>


<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


<style>
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

        <?php $title = "Time Report"; if(!empty($save_data)){ ?>
            <div class="table-responsive">

                <table id="report_table" class="table">
                    <thead>
                    <tr>
                        <th colspan="<?php echo count($counter_list)+9; ?>"><h3 style="text-align: center;width: 100%;"><?php echo $title; ?></h3></th>
                    </tr>
                    <tr>
                        <th>S.No</th>
                        <th>Mobile</th>
                        <th>Token</th>
                        <th>Date</th>
                        <th>Time Spent in Hospital</th>
                        <th>Wait After Booking</th>

                        <?php  foreach ($counter_list as $c_key => $counter){ ?>
                            <th><?php echo "Time Spent At ".$counter; ?></th>
                        <?php } ?>
                        <th>Time Spent For Consultation</th>
                        <th>Time(Send to Billing - Flash on Billing Counter)</th>
                        <th>Log</th>


                    </tr>
                    </thead>
                    <tbody>
                    <?php $total=array(); foreach ($save_data as $key => $list){ if(true){  ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><?php echo $list['patient_name']; ?></td>
                            <td>
                                <?php
                                if($list['thinapp_id']==CK_BIRLA_APP_ID){
                                    echo $token = $this->AppAdmin->get_ck_birla_token($list['appointment_staff_id'],$list['token']);
                                }else{
                                    echo $token = $list['token'];
                                }
                                ?>
                            </td>

                            <td><?php echo date('d-m-Y',strtotime($list['appointment_date'])); ?></td>
                            <td><?php
                                    if(!empty($list['TOTAL_SPEND_TIME']['label'])){
                                        echo @$list['TOTAL_SPEND_TIME']['label'];
                                    }else{
                                        if($list['status'] !='CLOSED'){
                                            echo "Not Closed";
                                        }
                                    }
                                ?></td>
                            <td><?php

                                $total['WAITING_AFTER_BOOKING']['minutes'] = @($total['WAITING_AFTER_BOOKING']['minutes'] + $list['WAITING_AFTER_BOOKING']['minutes']);
                                $total['WAITING_AFTER_BOOKING']['seconds'] = @($total['WAITING_AFTER_BOOKING']['seconds'] + $list['WAITING_AFTER_BOOKING']['seconds']);


                                $total_pat =  $this->AppAdmin->getPatientCountFromArray($list['WAITING_AFTER_BOOKING']['label']);
                                $total['WAITING_AFTER_BOOKING']['total'] = @!empty($total['WAITING_AFTER_BOOKING']['total'])?($total['WAITING_AFTER_BOOKING']['total']+$total_pat):$total_pat;

                                echo $this->AppAdmin->check_label(@$list['WAITING_AFTER_BOOKING']);

                                ?></td>

                           <?php foreach ($counter_list as $c_key => $counter){ ?>
                                <td>
                                    <?php

                                        $total[$counter]['minutes'] = @($total[$counter]['minutes'] + $list[$counter]['minutes']);
                                        $total[$counter]['seconds'] = @($total[$counter]['seconds'] + $list[$counter]['seconds']);

                                        $total_pat =  $this->AppAdmin->getPatientCountFromArray($list[$counter]['label']);
                                        $total[$counter]['total'] = @!empty($total[$counter]['total'])?($total[$counter]['total']+$total_pat):$total_pat;
                                        echo $this->AppAdmin->check_label(@$list[$counter]);
                                    ?>
                                </td>
                            <?php }} ?>

                        <td><?php


                            //echo $list['SEND_TO_DOCTOR']['minutes']."<br><br>";

                            $total['SEND_TO_DOCTOR']['minutes'] = @($total['SEND_TO_DOCTOR']['minutes'] + $list['SEND_TO_DOCTOR']['minutes']);
                            $total['SEND_TO_DOCTOR']['seconds'] = @($total['SEND_TO_DOCTOR']['seconds'] + $list['SEND_TO_DOCTOR']['seconds']);

                            $total_pat =  $this->AppAdmin->getPatientCountFromArray($list['SEND_TO_DOCTOR']['label']);

                            $total['SEND_TO_DOCTOR']['total'] = @!empty($total['SEND_TO_DOCTOR']['total'])?($total['SEND_TO_DOCTOR']['total']+$total_pat):$total_pat;
                            echo $this->AppAdmin->check_label(@$list['SEND_TO_DOCTOR']);
                            ?></td>

                        <td><?php
                            $total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['minutes'] = @($total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['minutes'] + $list['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['minutes']);
                            $total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['seconds'] = @($total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['seconds'] + $list['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['seconds']);
                            $total_pat =  $this->AppAdmin->getPatientCountFromArray($list['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['label']);
                            $total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['total'] = @!empty($total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['total'])?($total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['total']+$total_pat):$total_pat;
                            echo $this->AppAdmin->check_label(@$list['WAIT_BEFORE_BILLING_COUNTER_FLUSH']);
                            ?></td>

                        <td><?php echo implode(", ",$list['log_sequence']); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>

                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Average Time Per Patient</th>
                        <th><?php

                            $total['WAITING_AFTER_BOOKING']['sum'] = $this->AppAdmin->calculateTotalMinutes($total['WAITING_AFTER_BOOKING']);

                            echo $this->AppAdmin->createAvgLabel($total['WAITING_AFTER_BOOKING']);


                            ?></th>

                        <?php foreach ($counter_list as $c_key => $counter){ ?>
                            <th><?php

                               $total[$counter]['sum'] = $this->AppAdmin->calculateTotalMinutes($total[$counter]);
                               echo $this->AppAdmin->createAvgLabel($total[$counter]);


                            ?></th>
                        <?php } ?>
                        <th><?php
                            $total['SEND_TO_DOCTOR']['sum'] = $this->AppAdmin->calculateTotalMinutes($total['SEND_TO_DOCTOR']);
                            echo $this->AppAdmin->createAvgLabel($total['SEND_TO_DOCTOR']);

                        ?></th>
                        <th><?php

                           // echo $total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['sum'].'##'.$total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['total'].'@';
                            $total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']['sum'] = $this->AppAdmin->calculateTotalMinutes($total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']);
                            echo $this->AppAdmin->createAvgLabel($total['WAIT_BEFORE_BILLING_COUNTER_FLUSH']); ?>
                        </th>
                        <th></th>
                    </tr>

                    </tfoot>
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


        var title = "<?php echo $title; ?>";
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
                    title:title


                },
                {
                    extend: 'excel',
                    header: true,
                    footer:true,
                    title:title


                },
                {
                    extend: 'pdf',
                    header: true,
                    footer:true,
                    title:title


                }

            ]
        });
        $("head > title").text('appoinemtn_payment');

    });
</script>





