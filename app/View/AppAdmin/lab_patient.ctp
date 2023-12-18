<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>

<!-- this element user for use upload file to folder methods -->
<?php echo $this->element('app_admin_inner_tab_drive'); ?>
<!--END-->

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->


                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Lab Patient</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">


                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">

                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_lab_patient'))); ?>
                                <div class="form-group">



                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('doctor',array('type'=>'select','label'=>'Select Doctor','empty'=>'All','options'=>$doctor_list,'class'=>'form-control')); ?>
                                    </div>


                                    <div class="col-md-1">
                                        <?php echo $this->Form->input('uhid', array('type'=>'text','placeholder' => '', 'label' => 'UHID', 'class' => 'form-control')); ?>
                                    </div> <div class="col-md-2">
                                        <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Patient Name', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('mobile', array('type'=>'text','placeholder' => '', 'label' => 'Patient Mobile', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'From date', 'class' => 'form-control from_date')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('to_date', array( 'type'=>'text','placeholder' => '', 'label' => 'To date', 'class' => 'form-control to_date')); ?>
                                    </div>

                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                            <a class="btn btn-warning resteButton" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'opd')) ?>">Reset</a>

                                        </div>

                                    </div>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_table_box">


                            <div class="row">
                                <?php if(!empty($opd_list)){ ?>
                                    <div class="table-responsive">

                                    <table id="example" class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>UHID</th>
                                        <th>Patient Name</th>
                                        <th>Mobile</th>
                                        <th>Consult With</th>
                                        <th>Token Number</th>
                                        <th>Appointment Time</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($opd_list as $key => $list){

                                        $patient_type = 'CU';
                                        $patient_id = base64_encode($list['AppointmentCustomer']['id']);

                                        $object = !empty($list['AppointmentCustomer']['id'])?'AppointmentCustomer':"Children";
                                        $folder_name = $list[$object]['DriveFolder']['folder_name'];
                                        $folder_id = $list[$object]['DriveFolder']['id'];



                                        ?>
                                        <tr>
                                            <td><?php echo $key+1; ?></td>
                                            <td><?php

                                                echo $list['AppointmentCustomer']['uhid'].$list['Children']['uhid'];

                                            ?></td> <td><?php

                                                echo $list['AppointmentCustomer']['first_name'].$list['Children']['child_name'];

                                            ?></td>
                                            <td><?php echo $list['AppointmentCustomer']['mobile'].$list['Children']['mobile']; ?></td>
                                            <td><?php echo $list['AppointmentStaff']['name'];?></td>
                                            <td><?php echo $list['AppointmentCustomerStaffService']['queue_number'];?></td>
                                            <td><?php echo date('d-m-Y H:i',strtotime($list['AppointmentCustomerStaffService']['appointment_datetime']));?></td>
                                            <td>

                                                <a class="btn btn-xs btn-info add_new_file" href="javascript:void(0);" data-name="<?php echo $folder_name; ?>" data-id="<?php echo $folder_id; ?>"><i class="fa fa-upload"></i> Upload Report</a>




                                            </td>

                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                           <?php }else{ ?>

                            </div>
                            <div class="no_data">
                                <h2>No OPD found</h2>
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


        $(".channel_tap a").removeClass('active');
        $("#opd_tab").addClass('active');

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
                        columns: [0,1,2,3,4,5,6,7]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    }

                }
            ]
        });

        $(".from_date, .to_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});



        $("head > title").text('opd');

    });
</script>





