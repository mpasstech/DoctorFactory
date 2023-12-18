<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js'));

echo $this->Html->css(array('select2.min.css'));
echo $this->Html->script(array('select2.min.js'));

?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Booking Convenience Report</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'POST','url'=>array('controller'=>'supp','action' => 'admin_search_booking_convenience_report'))); ?>
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
                                        <?php echo $this->Form->input('account_type', array('type' => 'select','options'=>array(''=>'ALL','MENGAGE'=>'MEngage','CLINIC'=>'Clinic'),'label' => 'A/c Type', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('thinapp', array('id'=>'thinapp_id','type' => 'select','options'=>$thinAppList,'empty'=>'Select Thinapp','label' => 'Thinapp', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'supp','action'=>'admin_booking_convenience_report')) ?>">Reset</a>

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
                                                <th>Receipt ID</th>
                                                <th>Thinapp</th>
                                                <th>Account Type</th>
                                                <th>Patient Name</th>
                                                <th>Booking Convenience Fee</th>
                                                <th>Doctor Share</th>
                                                <th>Date</th>
                                                <th>Consulting Type</th>
                                                <th>Is Settled</th>
                                                <th>Appointment Booked From</th>
                                                <th>Created By</th>
                                                <th>Receipt</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php

                                            foreach ($allData as $key => $list){ ?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td>#<?php echo $list['unique_id'];?></td>
                                                    <td><?php echo $list['thinapp_name'];?></td>
                                                    <td><?php echo $list['payment_account'];?></td>
                                                    <td><?php echo $list['customer_name'];?></td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['booking_convenience_fee'];?></td>
                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $list['booking_doctor_share_fee'];?></td>
                                                    <td><?php echo date('d/m/Y H:i:s',strtotime($list['created']));?></td>
                                                    <td><?php echo $list['consulting_type'];?></td>
                                                    <td><?php echo $list['is_settled'];?></td>
                                                    <td><?php echo $list['appointment_booked_from'];?></td>

                                                    <td><?php echo $list['username'];?></td>
                                                    <td><a class="btn btn-success btn-xs" href="<?php echo Router::url('/'); ?>homes/receipt/<?php echo base64_encode($list['appointment_customer_staff_service_id']); ?>" target="_blank">Receipt</a></td>

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
    form .form-control{
        height: 35px;
    }
    .select2-container .select2-selection--single{
        height:35px !important;
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #ccc !important;
        border-radius: 0px !important;
    }
</style>


<script>
    $(document).ready(function(){


        $('#thinapp_id').select2();

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
                        columns: [1,2,3,4,5,6,7,8,9,10]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7,8,9,10]
                    }

                }
            ]
        });
        $(".from_date, .to_date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});



        $("head > title").text('user_payment');

    });
</script>





