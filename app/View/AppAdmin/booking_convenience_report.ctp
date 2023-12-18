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
                    <a href="<?php echo Router::url('/'); ?>app_admin/get_booking_convenience_order" class="btn btn-info all-orders">All Online Orders</a>
                    <h3 class="screen_title">Booking Convenience Report</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php
                        echo $this->element('billing_inner_header');
                        ?>




                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'app_admin','action' => 'search_booking_convenience_report'))); ?>
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
                                        <?php echo $this->Form->input('account_type', array('type' => 'select','options'=>array(''=>'ALL','MENGAGE'=>'MEngage','CLINIC'=>$login['Thinapp']['name']),'label' => 'A/c Type', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'booking_convenience_report')) ?>">Reset</a>

                                        </div>

                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>



                            <div class="row">

                                <div class="table-responsive">
                                    <h3 class="heading_lable">Billing Summary By Login Account</h3>
                                    <table id="example1" class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Staff Name</th>
                                            <th>Amount</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        foreach ($recData as $key => $list){ ?>
                                            <tr>
                                                <td><?php echo $key+1; ?></td>
                                                <td><?php echo $list['username'];?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['total_amount'];?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                            <div class="clear"></div>



                            <div class="row">

                                    <div class="table-responsive">
                                        <h3 class="heading_lable"> Receipts</h3>
                                        <table id="example" class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Receipt ID</th>
                                                <th>Patient Name</th>
                                                <th>Token Number</th>
                                                <th>Consulting Type</th>
                                                <th>Token Time</th>
                                                <th>Convenience Fee</th>
                                                <th>Online Consulting Fee</th>
                                                <th>Doctor Share Fee</th>
                                                <th>Call Charges</th>
                                                <th>Date</th>
                                                <th>Is Settled</th>
                                                <th>Booked Via</th>
                                                <th>Created By</th>
                                                <th style="width: 6%;">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            foreach ($allData as $key => $list){ ?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td>#<?php echo $list['unique_id'];?></td>
                                                    <td><?php echo $list['customer_name'];?></td>
                                                    <td><?php echo $list['queue_number'];?></td>
                                                    <td><?php echo !empty($list['convence_for'])?ucfirst(strtolower($list['consulting_type'])):'Offline';?></td>
                                                    <td><?php echo date('d/M/Y H:i',strtotime($list['appointment_datetime']));?></td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['booking_convenience_fee'];?></td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['doctor_online_consulting_fee'];?></td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['booking_doctor_share_fee'];?></td>
                                                    <td> <?php echo ($list['call_charges'])?'<i class="fa fa-inr" aria-hidden="true"></i> '.$list['call_charges']:'-' ;?></td>
                                                    <td><?php echo date('d/m/Y H:i:s',strtotime($list['created']));?></td>
                                                    <td><?php echo $list['is_settled'];?></td>
                                                    <td><?php echo $list['appointment_booked_from'];?></td>
                                                    <td><?php echo $list['username'];?></td>
                                                    <td>
                                                        <a class="btn btn-success btn-xs" href="<?php echo Router::url('/'); ?>homes/receipt/<?php echo base64_encode($list['appointment_customer_staff_service_id']); ?>" title="View Receipt" target="_blank"><i class="fa fa-file-text"></i></a>
                                                        <?php if(($list['consulting_type']=='VIDEO' || $list['consulting_type']=='AUDIO') && !empty($list['call_charges'])){ ?>
                                                            <a class="btn btn-info btn-xs call_history" href="javascript:void(0);" data-ai="<?php echo base64_encode($list['appointment_customer_staff_service_id']); ?>" title="View Video Call History" ><i class="fa <?php echo ($list['consulting_type']=='VIDEO')?'fa-video-camera':'fa-phone'; ?>"></i></a>
                                                        <?php } ?>
                                                    </td>

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

            </div>
            <!--box 2 -->


        </div>
    </div>
</div>

<style>

    table.dataTable thead th, table.dataTable thead td {
        padding: 2px 3px;
        border-bottom: 1px solid #111;
        font-size: 12px;
    }
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
    .all-orders {
        margin-top: -3px;
        float: left;
        display: table;
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

        var column = [1,2,3,4,5,6,7,8,9,10,11,12,13];
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
                        columns: column
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: column
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: column
                    }

                }
            ]
        });
        $(".from_date, .to_date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});


        $(document).on('click','.call_history',function(e){
            $("#video_call_history_modal").remove();
            var rowID = $(this).attr('data-ai');
            var thisButton = $(this);
            $.ajax({
                url: "<?php echo Router::url('/app_admin/video_call_detail_list',true); ?>",
                data:{ai:rowID},
                type:'POST',
                beforeSend:function(){
                    $(thisButton).button('loading').html('<i class="fa fa-spinner fa-pulse">');
                },
                success: function(result){
                    $(thisButton).button('reset');
                    var html = $(result).filter('#video_call_history_modal');
                    $(html).modal('show');
                }
            });
        });


        $("head > title").text('user_payment');

    });
</script>





