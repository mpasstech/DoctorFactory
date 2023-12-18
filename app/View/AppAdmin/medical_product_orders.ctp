<?php
$login = $this->Session->read('Auth.User.User');
$doctor_list = $this->AppAdmin->getHospitalDoctorList($login['thinapp_id']);

?>
<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js','jquery.maskedinput-1.2.2-co.min.js','comman.js')); ?>


<style>
    .dashboard_icon_li li {

        text-align: center;


    }

    #example_length {
        width: 32%;
        text-align: right;
    }
    .modal-header{
        background-color: #3a80bc;
        color: #FFFFFF;
        text-align: center;
    }
    .close {
        margin-top: 35px;
    }
</style>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->

                    <h3 class="screen_title">OPD Receipt</h3>
                    <?php echo $this->element('app_admin_book_appointment'); ?>
                    <div class="col-lg-12 right_box_div">
                        <?php //echo $this->element('appointment_header_tab'); ?>



                        <div class="Social-login-box payment_bx">
                            <div class="row">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_medical_product_orders'),'admin'=>true)); ?>
                                <div class="form-group">

                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('name', array('type'=>'text','placeholder' => '', 'label' => 'Search by Name', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('from_date', array('type'=>'text','placeholder' => '', 'label' => 'Date From', 'class' => 'form-control date_from')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('to_date', array('type'=>'text','placeholder' => '', 'label' => 'Date To', 'class' => 'form-control date_to')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <?php echo $this->Form->input('doctor_id', array('type' => 'select','empty'=>'All','options'=>$doctor_list,'label' => 'Search By Doctor', 'class' => 'form-control')); ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php echo $this->Form->input('payment', array('type' => 'select','empty'=>'All','options'=>array('CASH'=>'Cash','ONLINE'=>'Online'),'label' => 'Search by Payment', 'class' => 'form-control')); ?>
                                    </div>


                                    <div class="col-sm-2 action_btn" >
                                        <div class="input text">
                                            <label style="display: block;">&nbsp;</label>
                                            <button type="submit" class="btn btn-info">Search</button>
                                            <a class="btn btn-warning" href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'medical_product_orders')) ?>">Reset</a>

                                        </div>

                                    </div>

                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Receipt No.</th>
                                                <th>UHID</th>
                                                <th>Patient</th>
                                                <th>Doctor</th>
                                                <th>Payment Type</th>
                                                <th>Amount</th>
                                                <th>Wallet Deduction</th>
                                                <th>Date</th>
                                                <th>Option</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $counter = 1; $total_amount = 0; foreach($medicalProductOrder AS $item){ ?>

                                                <tr>
                                                    <td><?php echo $counter; ?>.</td>
                                                    <td><?php echo $item[0]['unique_id']; ?></td>
                                                    <td><?php echo !empty($item['AppointmentCustomer']['uhid'])?$item['AppointmentCustomer']['uhid']:$item['Children']['uhid'] ?></td>
                                                    <td><?php echo $item['AppointmentCustomer']['first_name'].$item['Children']['child_name']; ?></td>
                                                    <td><?php echo $item['AppointmentStaff']['name']; ?></td>
                                                    <td><?php
                                                        if($item['AppointmentCustomerStaffService']['booking_payment_type'] == "ONLINE" && $item['MedicalProductOrder']['hospital_payment_type_id'] == "0" ){
                                                            echo "ONLINE";
                                                        }else{
                                                            echo isset($item['HospitalPaymentType']['name'])?$item['HospitalPaymentType']['name']:"CASH";
                                                        }
                                                        ?>
                                                    </td>

                                                    <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $item['MedicalProductOrder']['total_amount']; $total_amount += $item['MedicalProductOrder']['total_amount']; ?></td>
                                                    <td><?php echo !empty($item['WalletUserHistory']['amount'])?'<i class="fa fa-inr" aria-hidden="true"></i> '.$item['WalletUserHistory']['amount']:''; ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime( $item['MedicalProductOrder']['created'] ) );?></td>
                                                    <td>
                                                        <a class="btn-xs btn btn-success" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['MedicalProductOrder']['id']); ?>" target="_blank">Receipt</a>

                                                        <?php
                                                        $user_role = $this->AppAdmin->getHospitalUserRole($login['thinapp_id'],$login['mobile'],$login['role_id']);
                                                        if($user_role =="ADMIN"){ ?>
                                                            <button type="button" class="btn-xs  btn btn-primary editBtn" data-id="<?php echo base64_encode($item['MedicalProductOrder']['id']); ?>">Edit</button>
                                                        <?php } ?>



                                                    </td>
                                                </tr>
                                                <?php $counter++; } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $totalEarnings[0]['total']; ?></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    </div>


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


<script>



    $(document).ready(function () {




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
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8]
                    }

                }
            ]
        });


        $(document).on('click','.editBtn',function(e){


            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/getEditMedicalOrder",
                data: {id: id},
                beforeSend: function () {
                    $btn.button('loading').html('Wait')
                },
                success: function (data) {
                    $btn.button('reset');
                    var html = $(data).filter('#editPaymentModal');
                    $(html).modal('show');

                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });

        });





        $("head > title").text("Report_<?php echo date("d-M-Y"); ?>");

        $(".date_from, .date_to").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});

        $(".date_from, .date_to").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});


    });
</script>
