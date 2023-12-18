<?php
$login = $this->Session->read('Auth.User');
$user_role = $login['USER_ROLE'];
$staff_data = @$this->AppAdmin->get_doctor_by_id($login['AppointmentStaff']['id'],$login['User']['thinapp_id']);
?>

<div class="modal-dialog modal-sm modal_div">
    <div class="modal-content">





        <?php echo $this->Form->create('HospitalDepositAmount',array( 'class'=>'form','id'=>'add_form')); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <ul class="nav nav-tabs"  id="myTab">
                <li><a href="#add" data-toggle="tab">Deposit Amount</a></li>
                <li><a href="#list" data-toggle="tab">Deposit History</a></li>
                <li><a href="#expense" data-toggle="tab">Expense</a></li>
                <li><a href="#settlement" data-toggle="tab">Final Settlement</a></li>
            </ul>


        </div>
        <div class="modal-body">



            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="add">

                    <div class="row name_section">


                        <div class="input number col-md-5">
                            <label><i class="fa fa-user"></i> Patient Name : </label>
                            <?php echo $patient_name; ?>
                        </div>
                        <div class="input number col-md-3">
                            <label> IPD ID :</label>
                            <?php echo $ipd_unique_id; ?>
                        </div>

                        <div class="input number col-md-4">

                            <label><i class="fa fa-rupee"></i> Total Deposit : </label>
                            <?php echo $balance; ?>

                        </div>


                    </div>
                    <div class="row detail_section">
                        <div class="input text col-md-6">
                            <?php echo $this->Form->input('amount', array('type'=>'text','label'=>'Deposit Amount','class'=>'form-control amount')); ?>
                        </div>

                        <div class="input text col-md-6">
                            <?php echo $this->Form->input('remark', array('type'=>'text','label'=>'Remark','class'=>'form-control remark')); ?>
                        </div>

                        <!--div class="input text col-md-4">
                            <?php echo $this->Form->input('created', array('type'=>'text','value'=>date('d/m/Y h:i A'),'label'=>'Date/Time','class'=>'form-control admit_date')); ?>
                        </div-->


                        <div class="input text col-md-3" style="display: none;">
                            <?php echo $this->Form->input('discount', array('type'=>'text','value'=>'','label'=>'Discount','class'=>'form-control dis')); ?>
                        </div>

                        <div class="input text col-md-3" style="display: none;">
                            <?php echo $this->Form->input('tds', array('type'=>'text','value'=>'','label'=>'TDS','class'=>'form-control tds')); ?>
                        </div>
                        <div class="input number col-md-12">
                            <label>Payment Via</label><br>
                            <?php
                            $attributes = array(
                                'legend' => false,
                                'value' => 0,
                                'class'=>'payment_type'
                            );


                            $payment = $this->AppAdmin->getHospitalPaymentTypeArray($login['User']['thinapp_id']);
                            $payment[0]='Cash';
                            ksort($payment,SORT_NUMERIC);

                            echo $this->Form->radio('hospital_payment_type_id',$payment , $attributes);
                            ?>
                        </div>



                    </div>
                    <div class="row ">
                        <div class="input submit  col-md-offset-3 col-md-6">

                            <?php echo $this->Form->input('hda', array('type'=>'hidden','label'=>false,'class'=>'hda')); ?>
                            <?php echo $this->Form->button('Save',array('div'=>false,'type'=>'button','class'=>'btn btn-info','id'=>'save')); ?>
                            <button type="reset" class="btn-success btn" >Reset</button>
                            <button type="button" class="btn-warning btn" data-dismiss="modal">Cancel</button>

                        </div>
                    </div>

                </div>
                <div class="tab-pane"  id="list">
                    <?php if(!empty($data_list)){ ?>
                        <div class="btn_div">
                            <button type="button" data-type="deposit" data-id="<?php echo $ipd_id; ?>" class="btn btn-xs btn-success load_all_receipt">Print All Deposit </button>
                        </div>
                    <?php } ?>
                    <div class="table-responsive">

                        <?php if(!empty($data_list)){ ?>
                        <table id="example" class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>TDS</th>
                                <th>Payment Via</th>
                                <th>Remark</th>
                                <th>Date</th>
                                <th width="20%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($data_list as $key => $list){ ?>
                                <tr>
                                    <td><?php echo $key+1; ?></td>
                                    <td><?php echo $list['HospitalDepositAmount']['amount']; ?></td>
                                    <td><?php echo $list['HospitalDepositAmount']['discount'];?></td>
                                    <td><?php echo $list['HospitalDepositAmount']['tds'];?></td>
                                    <td><?php echo !empty($list['HospitalPaymentType']['name'])?$list['HospitalPaymentType']['name']:'Cash'; ?></td>
                                    <td title="<?php echo $list['HospitalDepositAmount']['remark']; ?>"><?php echo mb_strimwidth($list['HospitalDepositAmount']['remark'], 0, 15, '...') ;?></td>
                                    <td><?php echo date('d/m/Y H:i',strtotime($list['HospitalDepositAmount']['created']));?></td>
                                    <td class="action_btn">

                                        <?php if($list['HospitalDepositAmount']['status'] == 'ACTIVE'){ ?>
                                            <?php if($list['MedicalProductOrder']['id'] > 0){ ?>
                                                <a class="btn btn-xs btn-success" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($list['MedicalProductOrder']['id'])."/IAD"; ?>" target="_blank"><i class="fa fa-file-o"></i> Receipt</a>
                                            <?php } ?>
                                            <?php if(($login['USER_ROLE'] =='RECEPTIONIST' && @$staff_data['edit_appointment_payment'] =="YES") || $login['USER_ROLE'] =='ADMIN'){
                                                $list['HospitalDepositAmount']['created'] = date('d/m/Y h:i A',strtotime($list['HospitalDepositAmount']['created']));
                                                ?>
                                                <a class="btn btn-xs btn-info edit_btn" href="javascript:void(0);" data-type="<?php echo base64_encode(json_encode($list['HospitalDepositAmount'])); ?>" data-ipd="<?php echo base64_encode($list['HospitalDepositAmount']['id']); ?>" data-payment-type-detail="<?php echo base64_encode(json_encode($list['HospitalPaymentTypeDetail'])); ?>"><i class="fa fa-edit"></i> Edit</a>

                                            <?php } ?>
                                            <?php if ((in_array($user_role, array('ADMIN', 'STAFF', 'DOCTOR')) || ($user_role == "RECEPTIONIST" && @$staff_data['allow_refund_payment'] == "YES"))) { ?>
                                                <a class="btn btn-xs btn-danger delete_btn" href="javascript:void(0);" data-id="<?php echo base64_encode($list['HospitalDepositAmount']['id']); ?>"><i class="fa fa-trash"></i> Refund</a>
                                            <?php } ?>


                                        <?php }else{ ?>
                                            <?php
                                            $staffName = $this->AppAdmin->getStaffNameByUserID($list['MedicalProductOrder']['modified_by_user_id'],$list['MedicalProductOrder']['thinapp_id']);
                                            $text = "";
                                            if($staffName != "")
                                            {
                                                $text = "BY ".$staffName;
                                            }
                                            ?>
                                            <b>REFUNDED <?php echo $text; ?></b>
                                        <?php }?>



                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php }else{ ?>
                            <label style="text-align: center; width: 100%;"> No deposit amount history found</label>
                        <?php } ?>


                </div>

            </div>
                <div class="tab-pane"  id="expense">
                    <?php if(!empty($userExpenseData)){ ?>
                        <div class="btn_div" >
                            <button type="button" data-type="expense" data-id="<?php echo $ipd_id; ?>" class="btn btn-xs btn-success load_all_receipt">Print All Expense </button>
                        </div>

                    <?php } ?>
                    <div class="table-responsive">

                        <?php if(!empty($userExpenseData)){ ?>
                            <table id="example" class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Service Name</th>
                                    <th>Amount</th>
                                    <th>Bill No.</th>
                                    <th>Remark</th>
                                    <th width="15%">Payment Status</th>
                                    <th>Date</th>
                                    <th width="20%">Action</th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($userExpenseData as $key => $list){ ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $list['name']; ?></td>
                                        <td><?php echo $list['total_amount'];?></td>
                                        <td><?php echo $list['bill_id'];?></td>
                                        <td><?php echo $list['payment_description'];?></td>
                                        <td>
                                            <?php
                                                if($list['is_refunded']=="YES"){
                                                    echo 'Refunded '.$list['refund_amount'].' Rs/-';
                                                    
                                                }else{
                                                    echo $list['payment_status'];
                                                }
                                            ?>

                                        </td>
                                        <td><?php echo date("d/m/Y",strtotime($list['created']));?></td>
                                        <td>

                                            <?php if((($login['USER_ROLE'] =='RECEPTIONIST' && @$staff_data['edit_appointment_payment'] =="YES") || $login['USER_ROLE'] =='ADMIN')){ ?>
                                                <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>"  class="btn btn-xs btn-info pay_edit_btn"><i class="fa fa-edit"></i> Edit</button>
                                            <?php } ?>


                                            <?php if($list['is_refunded']=="NO"){ ?>
                                                <?php if($list['payment_status'] =='PAID' && (($login['USER_ROLE'] =='RECEPTIONIST' && @$staff_data['allow_refund_payment'] =="YES") || $login['USER_ROLE'] =='ADMIN')){ ?>
                                                    <a class="btn btn-xs btn-warning refund_expense_btn" href="javascript:void(0);"  data-id="<?php echo base64_encode($list['id']); ?>"><i class="fa fa-inr"></i> Refund</a>
                                                <?php } ?>
                                                <a class="btn btn-xs btn-success" href="<?php echo Router::url('/').'app_admin/print_invoice/'.base64_encode($list['id'])."/IPD"; ?>" target="_blank"><i class="fa fa-file-o"></i> Receipt</a>

                                            <?php } ?>


                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php }else{ ?>
                            <label style="text-align: center; width: 100%;"> No expense history found</label>
                        <?php } ?>


                    </div>

                </div>
                <div class="tab-pane" id="settlement">
                    <?php

                    $ipd_data = $this->AppAdmin->ipdTotalBalance(base64_decode($ipd_id));
                    $advance  = !empty($ipd_data['advance'])?$ipd_data['advance']:0;;
                    $expense  = !empty($ipd_data['expense'])?$ipd_data['expense']:0;;
                    $total  = $advance - $expense;

                    ?>

                    <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr><th>Total Deposit</th><td><?php echo $advance; ?></td></tr>
                                    <tr><th>Total Dues</th><td><?php echo $expense; ?></td></tr>
                                    <tr><th>Settlement Amount</th><td>

                                            <?php echo $total; ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <button data-type="settlement"  type="button" data-id="<?php echo $ipd_id; ?>" class="btn  btn-success load_all_receipt">Final Settlement </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                    </div>

                </div>
        </div>

        <?php echo $this->Form->end(); ?>
    </div>
    </div>

    <div class="modal fade" id="myModalPaymentTypeDetailAdd" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <form method="POST" id="paymentTypeDetailForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close closeMyModalPaymentTypeDetailAdd">&times;</button>
                        <h4 class="modal-title modal-title-payment-type" style="text-align: center;font-weight: bold;">Payment</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row opd_row">
                            <div class="input number col-md-6 card_no payment_detail_input">
                                <label for="opdCharge">Card No.</label>
                                <input type="text" class="form-control" name="card_no">
                            </div>
                            <div class="input number col-md-6 holder_name payment_detail_input">
                                <label for="opdCharge">Holder Name</label>
                                <input type="text" class="form-control" name="holder_name">
                            </div>
                            <div class="input number col-md-6 valid_upto payment_detail_input">
                                <label for="opdCharge">Valid Upto</label>
                                <input type="text" class="form-control" name="valid_upto">
                            </div>
                            <div class="input number col-md-6 transaction_id payment_detail_input">
                                <label for="opdCharge">Transaction ID</label>
                                <input type="text" class="form-control" name="transaction_id">
                            </div>
                            <div class="input number col-md-6 bank_account payment_detail_input">
                                <label for="opdCharge">Bank Account</label>
                                <input type="text" class="form-control" name="bank_account">
                            </div>
                            <div class="input number col-md-6 beneficiary_name payment_detail_input">
                                <label for="opdCharge">Beneficiary Name</label>
                                <input type="text" class="form-control" name="beneficiary_name">
                            </div>
                            <div class="input number col-md-6 txn_no payment_detail_input">
                                <label for="opdCharge">Txn No.</label>
                                <input type="text" class="form-control" name="txn_no">
                            </div>
                            <div class="input number col-md-6 mobile_no payment_detail_input">
                                <label for="opdCharge">Mobile No</label>
                                <input type="text" class="form-control" name="mobile_no">
                            </div>
                            <div class="input number col-md-6 remark payment_detail_input">
                                <label for="opdCharge">Remark</label>
                                <input type="text" class="form-control" name="remark">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<style>
    #myModalPaymentTypeDetailAdd {
        z-index: 999999999;
    }
    .btn_div{width: 100%;text-align: right;}
    .name_section, .detail_section{

        background: #fcfcfc;
        padding: 19px 0px;
        border: 1px solid #f5f2f2;
        margin: 3px 0px;
    }
    .payment_type{
        width: 5% !important;
        display: inline-flex;
    }
    legend{

        margin-bottom: 7px;
        font-size: 17px;
    }
    .modal_div .modal-header {
        min-height: 4.43px;
        padding: 10px 16px;
        border-bottom:none;
        /* border-bottom: 1px solid #e5e5e5; */
    }
    .modal_div .modal-body{
        padding: 0px 20px 20px 20px;
    }
    .table-responsive{
        max-height: 400px;
        overflow-y: auto;

    }
    .removeRow{
        margin-top: 30px;
    }
</style>
<script>
    $(document).ready(function(){
        //$(".admit_date").datetimepicker({format: 'DD/MM/YYYY hh:mm A',defaultDate:new Date()});
        $(document).off('click','#save');
        $(document).on('click','#save',function(e){
            e.preventDefault();
            var $btn = $(this);
            var amount = $("input[name='data[HospitalDepositAmount][amount]']").val();
            var dataToSend = $("#add_form, #paymentTypeDetailForm").serialize();
            if(amount){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/add_deposit_amount/<?php echo $ipd_id; ?>",
                    data:{data:dataToSend},
                    beforeSend:function(){
                        $btn.button('loading').html('Wait..');
                    },
                    success:function(data){
                        $btn.button('reset');
                        data = JSON.parse(data);
                        if(data.status==1){

                            $("#amount_deposit").modal('hide').html('');
                            $("#amount_deposit").modal('hide').html('');

                            var win = window.open(baseurl+"app_admin/print_invoice/"+data.orderID+"/IAD", '_blank');
                            if (win) {
                                win.focus();
                            } else {
                                alert('Please allow popups for this website');
                            }

                        }

                        var dialog = $.confirm({
                            title: 'Deposit Amount',
                            content: data.message,
                            buttons:{
                                Ok: {
                                    text: 'Ok',
                                    btnClass: 'btn-info',
                                    keys: ['enter', 'shift'],
                                    action: function(){
                                       dialog.close();
                                    }
                                }
                            }

                        });


                    },
                    error: function(data){
                        $btn.button('reset');
                        var dialog = $.confirm({
                            title: 'Deposit Amount',
                            content: "Sorry something went wrong on server.",
                            buttons:{
                                Ok: {
                                    text: 'Ok',
                                    btnClass: 'btn-info',
                                    keys: ['enter'],
                                    action: function(){
                                        dialog.close();
                                    }
                                }
                            }

                        });

                    }
                });
            }else{
                $(".amount").focus();
                $.alert('Please enter amount');

            }

        });
        $('#myTab a:first').tab('show');

        $(document).off('click','.edit_btn');
        $(document).on('click','.edit_btn',function(e){

            var data_type = JSON.parse(atob($(this).attr('data-type')));

            $('.amount').val(data_type.amount);
            $('.dis').val(data_type.discount);
            $('.tds').val(data_type.tds);
            $('.remark').val(data_type.remark);
            //$('#HospitalDepositAmountCreated').val(data_type.created);
            $('.hda').val(btoa(data_type.id));
            $("input[name='data[HospitalDepositAmount][hospital_payment_type_id]'][value=" + data_type.hospital_payment_type_id + "]").attr('checked', 'checked');

            var paymentTypeDetail = JSON.parse(atob($(this).attr('data-payment-type-detail')));
            console.log(paymentTypeDetail);
            $("[name='card_no']").val((paymentTypeDetail['card_no'] != null)?paymentTypeDetail['card_no']:"");
            $("[name='holder_name']").val((paymentTypeDetail['holder_name'] != null)?paymentTypeDetail['holder_name']:"");
            $("[name='valid_upto']").val((paymentTypeDetail['valid_upto'] != null)?paymentTypeDetail['valid_upto']:"");
            $("[name='transaction_id']").val((paymentTypeDetail['transaction_id'] != null)?paymentTypeDetail['transaction_id']:"");
            $("[name='bank_account']").val((paymentTypeDetail['bank_account'] != null)?paymentTypeDetail['bank_account']:"");
            $("[name='beneficiary_name']").val((paymentTypeDetail['beneficiary_name'] != null)?paymentTypeDetail['beneficiary_name']:"");
            $("[name='txn_no']").val((paymentTypeDetail['txn_no'] != null)?paymentTypeDetail['txn_no']:"");
            $("[name='mobile_no']").val((paymentTypeDetail['mobile_no'] != null)?paymentTypeDetail['mobile_no']:"");
            $("[name='remark']").val((paymentTypeDetail['remark'] != null)?paymentTypeDetail['remark']:"");


            $('#myTab a:first').tab('show');

        });


        $(document).off("click",".payment_type");
        $(document).on("click",".payment_type",function(){
            //alert("here");
            var paymentTypeID = $(".payment_type:checked").val();
            $("#paymentTypeDetailForm").trigger("reset");
            $(".payment_detail_input").hide();
            if(paymentTypeID == '<?php echo DEFAULT_GOOGLE_PAY; ?>')
            {
                $(".remark").show();
                $(".mobile_no").show();
                $(".txn_no").show();
                $(".holder_name").show();
                $(".modal-title-payment-type").text("Google Pay");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_PHONE_PE; ?>')
            {
                $(".remark").show();
                $(".mobile_no").show();
                $(".txn_no").show();
                $(".holder_name").show();
                $(".modal-title-payment-type").text("PhonePe");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_PAYTM; ?>')
            {
                $(".remark").show();
                $(".mobile_no").show();
                $(".txn_no").show();
                $(".holder_name").show();
                $(".modal-title-payment-type").text("Paytm");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_NEFT; ?>')
            {
                $(".remark").show();
                $(".bank_account").show();
                $(".beneficiary_name").show();
                $(".modal-title-payment-type").text("NEFT");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_DEBIT_CARD; ?>')
            {
                $(".remark").show();
                $(".card_no").show();
                $(".holder_name").show();
                $(".valid_upto").show();
                $(".transaction_id").show();
                $(".modal-title-payment-type").text("Debit Card");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_CREDIT_CARD; ?>')
            {
                $(".remark").show();
                $(".card_no").show();
                $(".holder_name").show();
                $(".valid_upto").show();
                $(".transaction_id").show();
                $(".modal-title-payment-type").text("Credit Card");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }

        });

        $(document).off("click",".closeMyModalPaymentTypeDetailAdd");
        $(document).on("click",".closeMyModalPaymentTypeDetailAdd",function(e){
            $("#myModalPaymentTypeDetailAdd").modal('hide');
        });

        $(document).off('hidden.bs.modal','#myModalPaymentTypeDetailAdd');
        $(document).on('hidden.bs.modal', '#myModalPaymentTypeDetailAdd', function (e) {
            e.stopPropagation();
            e.stopImmediatePropagation();
        });

        $(document).off('keypress','.amount, .dis, .tds');
        $(document).on('keypress','.amount, .dis, .tds',function(event) {
            priceValidation(event,$(this), 2);
        });

        $(document).off('click','.delete_btn');
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
                                            /*.fadeOut(1000, function() {
                                            //Delete the old row
                                            $(this).slideUp(1000);
                                        });*/

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

        $('body').off('keydown', 'input, select, textarea');
        $('body').on('keydown', 'input, select, textarea', function(e) {
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            var self = $(this)
                , form = self.parents('form:eq(0)')
                , focusable
                , next
            ;
            if (keyCode == 13) {
                focusable = form.find('input[type="text"],input[type="password"],input[type="text"],a,select,button,textarea').filter(':visible');
                next = focusable.eq(focusable.index(this)+1);
                if (next.length) {
                    next.focus();
                } else {
                    form.submit();
                }
                return false;
            }
        });

        $(document).off('click','.refund_expense_btn');
        $(document).on('click','.refund_expense_btn',function(){
            var id = $(this).attr('data-id');
            var $btn = $(this);
            $.ajax({
                url: "<?php echo Router::url('/app_admin/refund_amount',true);?>",
                type:'POST',
                data:{id:id},
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#myModalRefundForm');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });

        $(document).off('click','.pay_edit_btn');
        $(document).on('click','.pay_edit_btn',function(e){


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

    });
</script>


