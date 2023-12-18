<?php
$login = $this->Session->read('Auth.User');
?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Online Booking Convenience Orders</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php
                        echo $this->element('billing_inner_header');
                        ?>




                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_booking_convenience_order'))); ?>
                                <div class="form-group">


                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('appointment_booked_from', array('type' => 'select','options'=>array(''=>'ALL','IVR'=>'IVR','APP'=>'APP','DOCTOR_PAGE'=>'DOCTOR PAGE'),'label' => 'Appointment Booked From', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('to_date', array( 'type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); ?>
                                    </div>



                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('status', array('type' => 'select','options'=>array(''=>'ALL','ACTIVE'=>'ACTIVE','PAID'=>'PAID','FAILED'=>'FAILED'),'label' => 'Status', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'get_booking_convenience_order')) ?>">Reset</a>

                                        </div>

                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>




                            <div class="clear"></div>



                            <div class="row">

                                    <div class="table-responsive">
                                        <h3 class="heading_lable"> Receipts</h3>
                                        <table id="example" class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Order ID</th>
                                                <th>Patient Name</th>
                                                <th>Token Number</th>
                                                <th>Token Time</th>
                                                <th>Booked From</th>
                                                <th>Booking Convenience Fee</th>

                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            foreach ($allData as $key => $list){ ?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td><?php echo $list['order_id'];?></td>
                                                    <td><?php echo $list['customer_name'];?></td>
                                                    <td><?php echo $list['queue_number'];?></td>
                                                    <td><?php echo date('d/M/Y H:i',strtotime($list['appointment_datetime']));?></td>
                                                    <td><?php echo $list['appointment_booked_from'];?></td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['amount'];?></td>

                                                    <td><?php echo date('d/m/Y H:i:s',strtotime($list['created']));?></td>
                                                    <td><?php echo $list['status'];?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                            </div>
                            <div class="clear"></div>
                        </div>


                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->


        </div>
    </div>
</div>

<style>
    #example_length {
        width: 32%;
        text-align: right;
    }
    .heading_lable {
        text-align: center;
        background:#d6d6d6;
        padding: 5px 0px;
        float: left;
        width: 100%;
    }
</style>


<script>
    $(document).ready(function(){


        $('#example1').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 150, 200, -1 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2]
                    }

                }
            ]
        });
        $('#example').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 150, 200, -1 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9]
                    }

                }
            ]
        });
        $(".from_date, .to_date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});



        $("head > title").text('user_payment');

    });
</script>





