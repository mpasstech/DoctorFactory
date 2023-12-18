<?php
$login = $this->Session->read('Auth.User');
$login1 = $this->Session->read('Auth.User');
?>

<?php  echo $this->Html->css(array('dataTableBundle.css')); ?>
<?php  echo $this->Html->script(array('dataTableBundle.js')); ?>


<style>
    .dashboard_icon_li li {
        text-align: center;
        width: 18%;
    }
</style>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 30px;">
        <div class="container">

           <!-- --><?php /*echo $this->element('billing_inner_header'); */?>
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <div class="row">
                                <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_hospital_patient_invoice_list/',$patientUHID),'admin'=>true)); ?>
                                    <div class="form-group">
                                            <div class="col-md-3">
                                                <?php echo $this->Form->input('date_from', array('type'=>'text','placeholder' => 'Select Date', 'label' => 'Search by from date', 'class' => 'form-control')); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?php echo $this->Form->input('date_to', array('type'=>'text','placeholder' => 'Select Date', 'label' => 'Search by to date', 'class' => 'form-control')); ?>
                                            </div>

                                            <div class="col-md-3">
                                                <?php echo $this->Form->input('receipt_type', array('type'=>'select','empty' => 'All','options' => array('OPD'=>'OPD Receipt','DEPOSIT'=>'IPD Deposit Receipt','EXPANSE'=>'IPD Expanse Receipt','OTHER'=>'Other Receipt'), 'label' => 'Search by receipt type', 'class' => 'form-control')); ?>
                                            </div>
                                            
                                            <div class="col-md-1">
                                                <?php echo $this->Form->label('&nbsp;'); ?>
                                                <?php echo $this->Form->submit('Search',array('class'=>'btn btn-info','id'=>'search')); ?>
                                            </div>
                                            <div class="col-md-1">
                                                <?php echo $this->Form->label('&nbsp;'); ?>
                                                <div class="submit">
                                                    <a href="<?php echo Router::url('/app_admin/hospital_patient_invoice_list/'.base64_encode($patientUHID)); ?>"><button type="button" class="btn btn-warning btnReset" >Reset</button></a>
                                                </div>
                                            </div>
                                    </div>
                            <?php echo $this->Form->end(); ?>
                    </div>

                        <div class="Social-login-box payment_bx">
                            <div class="form-group row">
                                <div class="col-sm-12">


                                    <div class="table table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Receipt No.</th>
                                                <th>Patient</th>
                                                <th>Payment Type</th>
                                                <th>Receipt Type</th>
                                                <th>Amount</th>
                                                <!--th>Wallet Deduction</th-->
                                                <th>Date</th>
                                                <th>Option</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php $counter = 1; foreach($medicalProductOrder AS $item){ ?>

                                            <tr>
                                                <td><?php echo $counter; ?>.</td>
                                                <td><?php echo $item[0]['unique_id']; ?></td>
                                                <td><?php echo $item['AppointmentCustomer']['first_name'].$item['Children']['child_name']; ?></td>
                                                <td><?php echo $item['MedicalProductOrder']['payment_type_name']; ?></td>
                                                <td><?php

                                                    if($item['MedicalProductOrder']['is_opd']=="Y"){
                                                        echo "OPD";
                                                    }else if($item['MedicalProductOrder']['is_expense']=="Y"){
                                                        echo "IPD Expense";
                                                    }else if($item['MedicalProductOrder']['is_advance']=="Y"){
                                                        echo "IPD Advance";
                                                    }else if($item['MedicalProductOrder']['is_settlement']=="Y"){
                                                        echo "IPD Settlement";
                                                    }else{
                                                        echo "Direct Billing";
                                                    }
                                                    ?></td>
                                                <td><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $item['MedicalProductOrder']['total_amount']; ?></td>
                                                <!--td><?php /* echo !empty($item['WalletUserHistory']['amount'])?'<i class="fa fa-inr" aria-hidden="true"></i> '.$item['WalletUserHistory']['amount']:''; */ ?></td-->
                                                <td><?php echo date('d-m-Y', strtotime( $item['MedicalProductOrder']['created'] ) );?></td>
                                                <td>
                                                    <?php if($item['MedicalProductOrder']['is_opd'] == 'Y'){ ?>
                                                        <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['MedicalProductOrder']['id']); ?>" target="_blank">Receipt</a>
                                                    <?php }else{


                                                        if($item['MedicalProductOrder']['is_settlement'] == 'Y'){ ?>

                                                            <a class="btn btn-success  btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice_non_opd_settlement/'.base64_encode($item['MedicalProductOrder']['id']); ?>" target="_blank">Receipt</a>

                                                        <?php } else{ ?>

                                                        <?php    if($item['MedicalProductOrder']['is_advance'] == 'Y'){ ?>
                                                            <a class="btn btn-success btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['MedicalProductOrder']['id'])."/IAD"; ?>" target="_blank">Receipt</a>
                                                        <?php }else{ ?>
                                                            <a class="btn btn-success  btn-xs" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($item['MedicalProductOrder']['id'])."/IPD"; ?>" target="_blank">Receipt</a>
                                                            <?php if($login['USER_ROLE'] !='RECEPTIONIST' && $login['USER_ROLE'] !='DOCTOR' && $login['USER_ROLE'] !='STAFF' && $item['MedicalProductOrder']['is_package'] != 'Y'){ ?>
                                                                <a class="btn btn-warning delete btn-xs" href="<?php echo Router::url('/').'app_admin/delete_invoice_non_opd/'.base64_encode($patientUHID).'/'.base64_encode($item['MedicalProductOrder']['id']); ?>">Delete</a>
                                                            <?php } ?>
                                                        <?php } ?>




                                                    <?php }} ?>

                                                    <?php if($item['MedicalProductOrder']['is_opd'] == 'Y'){ ?>
                                                        <?php if($login1['USER_ROLE'] !='RECEPTIONIST' && $login1['USER_ROLE'] !='DOCTOR' && $login1['USER_ROLE'] !='STAFF'){ ?>
                                                            <button type="button" class="btn btn-primary editBtn btn-xs" data-id="<?php echo base64_encode($item['MedicalProductOrder']['id']); ?>">Edit</button>
                                                        <?php } ?>
                                                    <?php } else{
                                                    if($item['MedicalProductOrder']['is_advance'] != 'Y' ){ ?>
                                                        <?php if($login1['USER_ROLE'] !='RECEPTIONIST' && $login1['USER_ROLE'] !='DOCTOR' && $login1['USER_ROLE'] !='STAFF' && $item['MedicalProductOrder']['is_package'] != 'Y'){ ?>
                                                            <button type="button" class="btn btn-primary btn-xs editBtn" data-id="<?php echo base64_encode($item['MedicalProductOrder']['id']); ?>">Edit</button>
                                                        <?php } ?>
                                                    <?php }else{ ?>
                                                        <?php
                                                        if($item['MedicalProductOrder']['status'] == 'ACTIVE') {
                                                            if ($login1['USER_ROLE'] != 'RECEPTIONIST' && $login1['USER_ROLE'] != 'DOCTOR' && $login1['USER_ROLE'] != 'STAFF' && $login1['USER_ROLE'] != 'LAB' && $login1['USER_ROLE'] != 'PHARMACY') { ?>
                                                                <a class="btn btn-danger btn-xs delete_btn"
                                                                   href="javascript:void(0);"
                                                                   data-id="<?php echo base64_encode($item['HospitalDepositAmount']['id']); ?>">Refund</a>
                                                            <?php }
                                                            }
                                                            else
                                                            {
                                                                echo "<b>REFUNDED BY ".$this->AppAdmin->getStaffNameByUserID($item['MedicalProductOrder']['modified_by_user_id'],$item['MedicalProductOrder']['thinapp_id'])."</b>";
                                                            }
                                                        }
                                                         ?>
                                                    <?php }  ?>
                                                    <!--button type="button" class="btn btn-primary editBtn" data-id="<?php echo base64_encode($item['MedicalProductOrder']['id']); ?>">Edit</button-->
                                                </td>
                                            </tr>
                                            <?php $counter++; } ?>
                                            </tbody>
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
<div class="modal fade" id="myModalForm" role="dialog"></div>
<div class="modal fade" id="myModalFormSearch" role="dialog"></div>
<style>
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
<style>
    .ms-ctn .ms-sel-ctn {

        margin-left: unset;
        max-height: 26px;
        min-height: 26px;

    }
    .ms-res-ctn .ms-res-item {

        line-height: unset;
        text-align: left;
        padding: 9px 5px;
        color: #666;
        cursor: pointer;

    }
    .ms-helper{ display:none !important; }
    .ms-sel-ctn .ms-sel-item{ color: #000000 !important;min-height: 24px; }


    .ms-close-btn {
        top: 0;
        position: absolute;
        right: 33px;
    }

</style>
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
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [1,2,3,4,5,6,7]
                    }

                }
            ]
        });


        $(document).on('submit','#formPay',function(e){
            e.preventDefault();
            var data = $( this ).serialize();
            var dataArr = $( this ).serializeArray();
            var submit = true;
            $.each(dataArr, function( key, value ) {
                if(value.name == 'productID[]' && !(value.value > 0))
                {
                    $.alert("Service name could not be empty!");
                    submit = false;
                }
            });

            if(submit == true)
            {
                var $btn = $("#addPaySubmit");
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/web_edit_order",
                    data:data,
                    beforeSend:function(){
                        $btn.attr('disabled',true);
                    },
                    success:function(data){
                        console.log(data);
                        var response = JSON.parse(data)
                        if(response.status == 1)
                        {
                            $btn.attr('disabled',false);
                            $("#myModalForm").modal("hide");
                            if($(".doctor_drp").length > 0){
                                $("#address").trigger('change');
                            }else{
                                $(".slot_date").trigger('changeDate');
                            }

                            var ID = $("#idContainer").val();
                            var win = window.open(baseurl+"app_admin/print_invoice/"+btoa(ID), '_blank');
                            if (win) {
                                win.focus();
                                location.reload();
                            } else {
                                alert('Please allow popups for this website');
                            }
                        }
                        else
                        {
                            $btn.attr('disabled',false);
                            alert("Sorry something went wrong on server.");
                        }


                    },
                    error: function(data){
                        $btn.attr('disabled',false);
                        alert("Sorry something went wrong on server.");
                    }
                });
            }

        });

        $(document).on('click','.delete_btn',function(event) {
            var $btn = $(this);
            var ipd_id = $(this).attr('data-id');
            var row = $(this).closest('tr');
            var dialog = $.confirm({
                title: 'Refund',
                content: 'Are you sure you want to refund this amount.',
                keys: ['enter', 'shift'],
                buttons:{
                    Yes: {
                        keys: ['enter'],
                        action:function(e){
                            var $btn2 = $(this);
                            $.ajax({
                                type:'POST',
                                url: baseurl+"app_admin/delete_deposit_amount",
                                data:{ipd_id:ipd_id},
                                beforeSend:function(){
                                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                                    $btn2.button('loading');
                                },
                                success:function(data){
                                    $btn.button('reset');
                                    $btn2.button('reset');
                                    data = JSON.parse(data);
                                    if(data.status==1){
                                        dialog.close();
                                        $(row).find('td:last').html("<b>REFUNDED</b>");
                                        window.location.reload();

                                    }else{
                                        $.alert(data.message);
                                    }
                                },
                                error: function(data){
                                    $btn.button('reset');
                                    $btn2.button('reset');
                                    $.alert("Sorry something went wrong on server.");
                                }
                            });
                            return false;
                        }
                    },
                    Cancel: {
                        action:function () {
                            dialog.close();
                        }
                    }
                }


            });


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
                    $btn.button('loading').html('Wait..')
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


        $(document).on('click','.delete',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            var conf = confirm("Do you want to delete this receipt?");
            if(conf == true)
            {
                window.location.href = url;
            }
        });

        $("head > title").text("Report_<?php echo date("d-M-Y"); ?>");

        $("#SearchDateFrom").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});

        $("#SearchDateFrom").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

        $("#SearchDateTo").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});

        $("#SearchDateTo").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

        $(".btnReset").click(function(){
            $("#SearchDate").val("");
            $("#month1").val("");
        });

    });
</script>