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
                    <h3 class="screen_title">OPD Patient List</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">


                        <?php echo $this->element('message'); ?>


                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hospital_search_box">
                            <div class="row">
                                <?php echo $this->element('app_admin_inventory_tab'); ?>
                            </div>
                            <div class="row search_main">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_opd'))); ?>
                                <div class="form-group">
                                    <div class="col-md-2">
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
                                        <th>Appointment With</th>
                                        <th>Token Number</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Admit Date</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($opd_list as $key => $list){

                                        $patient_type = 'CU';
                                        $patient_id = base64_encode($list['AppointmentCustomer']['id']);
                                        $appointment_id = base64_encode($list['AppointmentCustomerStaffService']['id']);


                                        $admit_status = $list['CustomerAdmitDetail']['admit_status'].$list['ChildrenAdmitDetail']['admit_status'];
                                        $admit_date = $list['CustomerAdmitDetail']['admit_date'].$list['ChildrenAdmitDetail']['admit_date'];
                                        $ipd_id = @trim($list['CustomerAdmitDetail']['id'].$list['ChildrenAdmitDetail']['id']);

                                        if(!empty($admit_date)){
                                            $admit_date = date('d-m-Y H:i',strtotime($admit_date));
                                        }
                                        if($ipd_id > 0){
                                            $ipd_id = base64_encode($ipd_id);
                                        }

                                        if(empty($patient_id)){
                                            $patient_type = 'CH';
                                            $patient_id = base64_encode($list['Children']['id']);
                                        }

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
                                            <td><?php echo $list['AppointmentCustomerStaffService']['amount'];?></td>
                                            <td><?php echo $this->AppAdmin->showIpdStatus($admit_status); ?></td>
                                            <td><?php echo $admit_date; ?></td>
                                            <td><?php echo date('d-m-Y H:i',strtotime($list['AppointmentCustomerStaffService']['created']));?></td>
                                            <td>


                                                <div class="dropdown">
                                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Option
                                                        <span class="caret"></span></button>
                                                    <ul class="dropdown-menu pull-right option_btn_panel">
                                                        <?php if(empty($admit_status)){ ?>
                                                            <a class="btn btn-xs btn-info" href="<?php echo Router::url('/app_admin/admit_patient/',true).$patient_type.'/'.$patient_id.'/'.$appointment_id; ?>"><i class="fa fa-sign-in"></i> Admit</a>
                                                        <?php }else{

                                                            $discharge_id = @trim($list['CustomerAdmitDetail']['HospitalDischarge']['id'].$list['ChildrenAdmitDetail']['HospitalDischarge']['id']);
                                                            $discharge_id = !empty($discharge_id)?'/'.base64_encode($discharge_id):'';
                                                            ?>
                                                            <a class="btn btn-xs btn-danger" href="<?php echo Router::url('/app_admin/discharge_patient/',true).$ipd_id.$discharge_id; ?>"><i class="fa fa-sign-in"></i> Discharge</a>
                                                        <?php } ?>

                                                        <?php  if($list['AppointmentCustomer']['uhid'].$list['Children']['uhid'] != '' ){ ?>
                                                            <a href="<?php echo Router::url('/app_admin/add_hospital_receipt/'.base64_encode($list['AppointmentCustomer']['uhid'].$list['Children']['uhid']),true); ?>" target="_blank" class="btn btn-info btn-xs receipt_btn" ><i class="fa fa-edit"></i> Add Receipt </a>

                                                            <a href="<?php echo Router::url('/app_admin/hospital_patient_invoice_list/'.base64_encode($list['AppointmentCustomer']['uhid'].$list['Children']['uhid']),true); ?>" target="_blank" class="btn btn-success btn-xs receipt_btn" ><i class="fa fa-list"></i> Receipt </a>
                                                        <?php }?>
                                                    </ul>
                                                </div>






                                            </td>

                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                           <?php }else{ ?>

                            </div>
                            <div class="no_data">
                                <h2>No opd found.</h2>
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
    .option_btn_panel .btn{
        width: 49%;
        float: left;
        height: 22px;
        margin: 1px 1px;
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



        $("head > title").text('IPD');

    });
</script>





