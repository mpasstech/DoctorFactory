<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
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

                    <h3 class="screen_title">

                        Appointment List



                    </h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">
                            <div class="row">
                                <?php echo $this->element('app_admin_without_appointment'); ?>
                            </div>
                            <div class="row search_main">

                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_get_appointment_without_token_list'))); ?>
                                <div class="form-group">
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('uhid', array('type'=>'text','placeholder' => '', 'label' => 'UHID', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('appointment_staff_id', array('type'=>'select', 'label' => 'Doctor','options'=>$doctor_list,'empty'=>'Please Select', 'class' => 'form-control')); ?>
                                    </div>

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Patient Name', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('mobile', array('type'=>'text','placeholder' => '', 'label' => 'Patient Mobile', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); ?>
                                    </div>
                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('to_date', array( 'type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); ?>
                                    </div>


                                    <div class="col-sm-3 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>"get_appointment_without_token_list")) ?>">Reset</a>

                                        </div>

                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_table_box">


                            <div class="row">
                                <?php if(!empty($appointment_list)){ ?>
                                    <div class="table-responsive">

                                        <table id="example" class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>

                                                <th>UHID</th>

                                                <th>Patient Name</th>
                                                <th>Mobile</th>
                                                <th>Consultant</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php


                                            foreach ($appointment_list as $key => $list){

                                                $patient_id = base64_encode($list['AppointmentCustomer']['id']);

                                                ?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td><?php

                                                        echo $list['AppointmentCustomer']['uhid'];

                                                        ?>
                                                    </td>

                                                    <td><?php

                                                        echo $list['AppointmentCustomer']['first_name'];

                                                        ?>
                                                    </td>
                                                    <td><?php echo $list['AppointmentCustomer']['mobile']; ?></td>
                                                    <td><?php echo $list['AppointmentStaff']['name'];?></td>
                                                   <td><?php echo date('d-m-Y',strtotime($list['AppointmentWithoutToken']['appointment_datetime']));?></td>
                                                    <td class="action_btn">

                                                        <?php  if($list['AppointmentCustomer']['uhid'] != '' ){ ?>
                                                            <a href="<?php echo Router::url('/app_admin/add_hospital_receipt/'.base64_encode($list['AppointmentCustomer']['uhid']),true); ?>" target="_blank" class="btn btn-info btn-xs receipt_btn" ><i class="fa fa-edit"></i> Add Receipt </a>

                                                            <a href="<?php echo Router::url('/app_admin/hospital_patient_invoice_list/'.base64_encode($list['AppointmentCustomer']['uhid']),true); ?>" target="_blank" class="btn btn-success btn-xs receipt_btn" ><i class="fa fa-list"></i> Receipt </a>
                                                        <?php }?>

                                                    </td>

                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>

                                    </div>
                                <?php }else{ ?>

                            </div>
                            <div class="no_data">


                                <h2>No Patient Yet</h2>
                            </div>
                            <?php } ?>
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
</style>


<script>
    $(document).ready(function(){


        //$(".channel_tap a").removeClass('active');

        //var selected = 'all_tab';

        //$("#"+selected).addClass('active');

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
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9]
                    }

                }
            ]
        });

        $(".from_date, .to_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});



        $("head > title").text('Appointment Without Token');


        $('title').html('Appointment Without Token');
    });
</script>
<style>
    .search_main [class*="col-"]{
        padding-right: 0px !important;
    }
    .search_main [class*="col-"] input{
        padding: 6px 6px;
    }
</style>




