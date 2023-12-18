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

        <?php $title = "Token Report"; if(!empty($data_array)){ ?>
            <div class="table-responsive">

                <table id="report_table" class="table">
                    <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Doctor Name</th>
                        <th>Patient Name</th>
                        <th>Mobile</th>
                        <th>Token</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Payment Via</th>
                        <th>Amount</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php $total=array(); foreach ($data_array as $key => $list){   ?>
                        <tr>
                            <td><?php echo $key+1; ?></td>
                            <td><?php echo $list['doctor_name']; ?></td>
                            <td><?php echo $list['appointment_patient_name']; ?></td>
                            <td><?php echo $list['patient_mobile']; ?></td>
                            <td><?php echo $list['token']; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($list['appointment_datetime'])); ?></td>
                            <td><?php echo date('h:i A',strtotime($list['appointment_datetime'])); ?></td>
                            <td><?php echo $list['status']; ?></td>
                            <td><?php echo $list['payment_type_name']; ?></td>
                            <td><?php echo $list['total_amount']; ?></td>
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





