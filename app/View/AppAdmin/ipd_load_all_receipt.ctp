<?php
$login = $this->Session->read('Auth.User');

?>
<?php  echo $this->Html->script(array('printThis.js')); ?>
<?php  echo $this->Html->css(array('font-awesome.min.css')); ?>




<div  class="modal-dialog modal-sm modal_div">
    <div class="modal-content">

        <div id="print_table"  class="modal-body">

            <div  style="margin: 0 auto !important; width: 100%;" class="container">
                <table  style="width: 100%;" >
                    <tbody>
                    <tr class="header_tr">
                        <td style="width: 20%;" align="left"><img style="width: 35px;height: 35px;" src="<?php echo $patient['logo']; ?>" </td>
                        <td style="font-size: 20px; text-align: center" align="center"><?php echo $patient['app_name']; ?></td>
                        <td style="width:20%; font-size: 16px; font-weight: 600;" align="right"><?php

                            if($receipt_for == "settlement"){
                                echo "IPD Billing";
                            }else if($receipt_for =="expense"){
                                echo "Expense List";
                            }else if($receipt_for =="deposit"){
                                echo 'Deposit List';
                            }


                            ?></td>
                    </tr>
                    <tr>

                        <td colspan="3">
                            <table style="width: 100%;" class="detail_table">

                                <tbody>
                                <tr><th colspan="4" style="font-size: 13px; border: 1px solid #efefef; text-align: center;background-color: #efefef;">Patient Detail</th></tr>
                                <tr>
                                    <th>UHID</th>
                                    <td style="text-align: right; padding-right: 20px;"><?php echo $patient['uhid']; ?></td>
                                    <th style="text-align: left; padding-left: 0px;">IPD ID</th>
                                    <td style="text-align: right;"><?php echo $patient['ipd_unique_id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Patient Name</th>
                                    <td style="text-align: right; padding-right: 20px;"><?php echo $patient['patient_name']; ?></td>
                                    <th  style="text-align: left; padding-left: 0px;">Gender</th>
                                    <td style="text-align: right;"><?php echo $patient['gender']; ?></td>
                                <tr>
                                    <th>Mobile</th>
                                    <td style="text-align: right; padding-right: 20px;"><?php echo $patient['patient_mobile']; ?></td>
                                    <th  style="text-align: left; padding-left: 0px;">Age</th>
                                    <td style="text-align: right;"><?php echo $patient['age']; ?></td>
                                </tr>
                                <tr>
                                    <th>Parents Name</th>
                                    <td style="text-align: right; padding-right: 20px;"><?php echo $patient['parents_name']; ?></td>
                                    <th  style="text-align: left; padding-left: 0px;">Admit Date, Time</th>
                                    <td style="text-align: right;"><?php echo date('d-m-Y, h:i A',strtotime($patient['admit_date'])); ?></td>
                                </tr>



                                <?php if($receipt_for == "settlement" && !empty($patient['settlement_date'])){ ?>
                                    <tr>
                                        <th>Ward</th>
                                        <td style="text-align: right; padding-right: 20px;"><?php echo $patient['ward_name']; ?></td>

                                        <th>Settlement Date</th>
                                        <td style="text-align: right; padding-right: 0px;"><?php echo date('d-m-Y',strtotime($patient['settlement_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Bill No.</th>
                                        <td style="text-align: right; padding-right: 20px;"><?php echo $patient['ipd_unique_id']; ?></td>
                                        <?php if($patient['admit_status']=='DISCHARGE') {
                                            $discharge_date =  $this->AppAdmin->getIPDDischargeDate(base64_decode($ipd_id),$login['User']['thinapp_id']);
                                            if(!empty($discharge_date)){
                                                ?>
                                                <th  style="text-align: left; padding-left: 0px;">Discharge Date, Time</th>
                                                <td style="text-align: right;"><?php echo date('d-m-Y, h:i A',strtotime($discharge_date)); ?></td>
                                            <?php }}else{ ?>
                                            <th></th>
                                            <td></td>
                                        <?php } ?>
                                    </tr>
                                <?php }else{ ?>
                                    <tr>
                                        <th>Ward</th>
                                        <td style="text-align: right; padding-right: 20px;"><?php echo $patient['ward_name']; ?></td>
                                        <th style="text-align: left; padding-left: 20px;">Bill No.</th>
                                        <td style="text-align: right;"><?php echo $patient['ipd_unique_id']; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>

                            </table>


                        </td>

                    </tr>
                    <tr>
                        <?php
                        $total_exp_tax = $total_exp_net = $total_exp_discount =0;
                        $total_ad_discount = $total_ad_net =0;  $paid_expense =0;
                        ?>
                        <?php if(!empty($deposit)){ ?>
                            <table><tr><td colspan="5">Deposit Detail</td></tr></table>
                            <table class="data_table" style="width: 100%;">

                                <thead >
                                <tr>
                                    <th  class="date_th" style="width: 13%;">Date</th>
                                    <th  class="date_th" style="width: 13%;">Receipt No.</th>
                                    <th  class="date_th" style="width: 8%;">Bill No.</th>
                                    <th style="width: 20%;">Payment Via</th>
                                    <th style="width: 10%;" >Discount</th>
                                    <th style="width: 10%;">TDS..</th>
                                    <th style="width: 20%;">Net Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($deposit as $key => $list){ ?>
                                    <?php if($list['type'] == 'deposit'){ ?>
                                        <tr style="-webkit-print-color-adjust: exact; background-color: <?php echo ($key%2!=0)?'#f1f1f1':'#fff';?> !important;">
                                            <td><?php echo date('d/m/Y',strtotime($list['created']));?></td>
                                            <td><?php
                                                $receiptID = $this->AppAdmin->get_receipt_id_by_order_id($list['order_id']);
                                                echo  !empty($receiptID)?$receiptID:'';

                                                ?>
                                            </td>
                                            <td></td>
                                            <td><?php echo $list['payment_via']; ?></td>
                                            <td><?php  $total_ad_discount += sprintf("%.2f",$list['discount']); echo sprintf("%.2f",$list['discount']); ?></td>
                                            <td><?php echo sprintf("%.2f",$list['tds']); ?></td>
                                            <td><?php  $total_ad_net += sprintf("%.2f",$list['amount']); echo sprintf("%.2f",$list['amount']); ?></td>

                                        </tr>
                                    <?php }else{ ?>
                                        <tr style="-webkit-print-color-adjust: exact; background-color: <?php echo ($key%2!=0)?'#f1f1f1':'#fff';?> !important;">
                                            <td><?php echo date('d/m/Y',strtotime($list['created']));?></td>
                                            <td><?php
                                                $receiptID = $this->AppAdmin->get_receipt_id_by_order_id($list['order_id']);
                                                echo  !empty($receiptID)?$receiptID:'';

                                                ?>
                                            </td>
                                            <td><?php echo $list['bill_id']; ?></td>
                                            <td><?php echo ucfirst(strtolower($list['payment_type_name'])); ?></td>
                                            <td><?php  $total_ad_discount += sprintf("%.2f",$list['discount_amount']); echo sprintf("%.2f",$list['discount_amount']); ?></td>
                                            <td></td>
                                            <td><?php  $total_ad_net += sprintf("%.2f",$list['total_amount']); echo sprintf("%.2f",$list['total_amount']); ?></td>

                                        </tr>
                                    <?php } ?>

                                <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align: center;"> Total </th>
                                    <th ><?php echo sprintf("%.2f",$total_ad_discount); ?></th>
                                    <th ></th>
                                    <th ><?php echo sprintf("%.2f",$total_ad_net); ?></th>
                                </tr>
                                </tfoot>

                            </table>
                        <?php } ?>

                        <?php if(!empty($expense)){ ?>
                            <table><tr><td colspan="9">Expense Detail</td></tr></table>
                            <table class="data_table" style="width: 100%;">
                                <thead >
                                <tr>
                                    <th  class="date_th" style="width: 13%;">Date</th>
                                    <th  class="date_th" style="width: 13%;">Receipt No.</th>
                                    <th style="width: 8%;">Bill No.</th>
                                    <th style="width: 40%;">Service</th>
                                    <th style="width: 8%;" >Rates</th>
                                    <th style="width: 5%;">Qty.</th>
                                    <th style="width: 15%;">Tax</th>
                                    <th style="width: 10%;">Tax Amo.</th>
                                    <th style="width: 5%;">Discount</th>
                                    <th style="width: 10%;">Dis. Amount</th>
                                    <th style="width: 15%;">Net Amount</th>
                                    <!--th style="width: 5%;">Payment Status</th-->
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                foreach ($expense as $key => $list){ ?>
                                <tr style="-webkit-print-color-adjust: exact; background-color: <?php echo ($key%2!=0)?'#f1f1f1':'#fff';?> !important;">
                                    <td><?php echo date('d/m/Y',strtotime($list['created']));?></td>
                                    <td><?php
                                        $receiptID = '';
                                        if($list['payment_status']=="PAID"){
                                            $receiptID = $this->AppAdmin->get_receipt_id_by_order_id($list['order_id']);
                                        }
                                        echo  !empty($receiptID)?$receiptID:'';

                                        ?></td>
                                    <td><?php echo $list['bill_id']; ?></td>
                                    <td><?php echo $list['name']; ?></td>
                                    <td><?php echo sprintf("%.2f",$list['product_price']); ?></td>
                                    <td><?php echo $list['quantity']; ?></td>
                                    <td><?php echo $list['tax_type']; ?></td>
                                    <td><?php  $total_exp_tax +=sprintf("%.2f",$list['tax_amount']); echo sprintf("%.2f",$list['tax_amount']); ?></td>
                                    <td><?php
                                        $unit = ($list['discount_type']=="PERCENTAGE")?'%':' Rs';
                                        echo $list['discount_value'].$unit ;
                                        ?></td>
                                    <td><?php  $total_exp_discount += sprintf("%.2f",$list['discount_amount']); echo sprintf("%.2f",$list['discount_amount']);; ?></td>
                                    <td><?php  $total_exp_net += sprintf("%.2f",$list['total_amount']); echo sprintf("%.2f",$list['total_amount']);; ?></td>
                                    <!--td><?php

                                    if($list['payment_status']=="PAID"){
                                        $paid_expense += sprintf("%.2f",$list['total_amount']);
                                    }
                                    echo ucfirst(strtolower($list['payment_status']));

                                    ?></td>

                                            </tr-->
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="7" style="text-align: center;"> Total </th>
                                    <th ><?php echo sprintf("%.2f", $total_exp_tax); ?></th>
                                    <th ></th>
                                    <th ><?php echo sprintf("%.2f",$total_exp_discount); ?></th>
                                    <th ><?php echo sprintf("%.2f",$total_exp_net); ?></th>
                                    <!--th ></th-->
                                </tr>
                                </tfoot>
                            </table>
                        <?php } ?>

                    </tr>
                    </tbody>
                </table>
                <?php if($receipt_for =="settlement"){ ?>

                    <table style="width: 100%;" class="footer_table">
                        <tr>
                            <th>Total Deposit</th>

                            <th>Expense Amount</th>

                            <th>Refund Amount</th>

                            <th>Payable Amount</th>

                            <th>Settlement Amount</th>
                            <th style="width: 20% !important;"><?php echo ($patient['hospital_ipd_settlement_id'] ==0)?"Rebate Amount":"Payment Status"; ?> </th>
                            <th>Remark</th>

                        </tr>
                        <tr>
                            <?php $td = $te = $tr = $pa =0; ?>
                            <?php if($patient['hospital_ipd_settlement_id'] ==0){ ?>
                                <?php $total_exp_net = $total_exp_net; ?>
                                <td><?php echo $td = sprintf("%.2f",$total_ad_net); ?></td>
                                <td><?php echo $te =sprintf("%.2f",$total_exp_net); ?></td>
                                <td><?php $tr = ($total_ad_net - $total_exp_net); echo $tr = ($tr > 0)?sprintf("%.2f",$tr):0.00; ?></td>
                                <td><?php $pa = ($total_ad_net - $total_exp_net); echo $pa = ($pa < 0)?abs(sprintf("%.2f",$pa)):0.00; ?></td>
                                <td><input type="text" class="settlement_amount" value="<?php echo ($tr > $pa)?$tr:$pa; ?>" ></td>
                                <script type="text/javascript">var deposit = "<?php echo ($tr > $pa)?$tr:$pa; ?>"; </script>
                                <td ><label class="rebate_value"></label> </td>
                                <td>
                                    <input type="text" value="<?php echo $patient['remark']; ?>" id="remarkSettlement">
                                </td>
                            <?php }else{ ?>
                            <?php $total_exp_net = $total_exp_net; ?>
                                <td><?php echo @$patient['total_deposit']; ?></td>
                                <td><?php echo @$patient['total_expense']; ?></td>


                                <td><?php echo @$patient['total_refund']; ?></td>
                                <td><?php echo @$patient['payable_amount']; ?></td>
                                <td><?php echo @$patient['settlement_amount']; ?></td>
                                <td><?php
                                    if($patient['payment_status'] =="RECEIVED"){
                                        $rebate = $patient['payable_amount']- $patient['settlement_amount'];
                                        $rebate = sprintf('%0.2f', $rebate);
                                        $rebate =($rebate > 0)?"<br>( Rebate $rebate Rs/- )":"";
                                        echo @ucfirst(strtolower($patient['payment_status'])).$rebate;
                                    }else{
                                        echo @ucfirst(strtolower($patient['payment_status']));
                                    }

                                    ?></td>
                                <td>
                                    <?php echo $patient['remark']; ?>
                                </td>
                            <?php } ?>

                        </tr>

                    </table>
                <?php } ?>

                <div class="action_div">
                    <?php if($receipt_for =="settlement" && $patient['hospital_ipd_settlement_id'] == 0 ){ ?>
                        <button type="button" class="btn btn-warning btn-xs" id="settlement_all" data-id="<?php echo $ipd_id; ?>"><i  class="fa fa-power-off"></i> Close Settlement</button>
                        <?php echo $this->Form->input('hospital_payment_type_id',array('div'=>false,'id'=>'p_type_id','type'=>'select','label'=>false,'empty'=>'Cash','options'=>$this->AppAdmin->getHospitalPaymentTypeArray($login['Thinapp']['id']),'class'=>'hospital_payment_type_id')); ?>
                    <?php } ?>

                    <button type="button" id="print_btn" class="btn btn-success btn-xs"><i  class="fa fa-print"></i> Print</button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger btn-xs"><i  class="fa fa-arrow-left"></i> Back</button>



                    <select  class="size" id="prescription_size">
                        <option value="A4">A4</option>
                        <option value="A4/4">A4/4</option>
                    </select>

                </div>
            </div>

        </div>

    </div>





</div>
<div class="modal fade" id="myModalPaymentTypeDetailAdd1" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="POST" id="paymentTypeDetailForm1">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close closeMyModalPaymentTypeDetailAdd1">&times;</button>
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
<script>



    $(document).ready(function(){

        $(document).off('change','#p_type_id');
        $(document).on('change','#p_type_id',function(e){

            var paymentTypeID = $('#p_type_id').val();
            $("#paymentTypeDetailForm1").trigger("reset");
            $(".payment_detail_input").hide();
            if(paymentTypeID == '<?php echo DEFAULT_GOOGLE_PAY; ?>')
            {
                $(".remark").show();
                $(".mobile_no").show();
                $(".txn_no").show();
                $(".holder_name").show();
                $(".modal-title-payment-type").text("Google Pay");
                $("#myModalPaymentTypeDetailAdd1").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_PHONE_PE; ?>')
            {
                $(".remark").show();
                $(".mobile_no").show();
                $(".txn_no").show();
                $(".holder_name").show();
                $(".modal-title-payment-type").text("PhonePe");
                $("#myModalPaymentTypeDetailAdd1").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_PAYTM; ?>')
            {
                $(".remark").show();
                $(".mobile_no").show();
                $(".txn_no").show();
                $(".holder_name").show();
                $(".modal-title-payment-type").text("Paytm");
                $("#myModalPaymentTypeDetailAdd1").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_NEFT; ?>')
            {
                $(".remark").show();
                $(".bank_account").show();
                $(".beneficiary_name").show();
                $(".modal-title-payment-type").text("NEFT");
                $("#myModalPaymentTypeDetailAdd1").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_DEBIT_CARD; ?>')
            {
                $(".remark").show();
                $(".card_no").show();
                $(".holder_name").show();
                $(".valid_upto").show();
                $(".transaction_id").show();
                $(".modal-title-payment-type").text("Debit Card");
                $("#myModalPaymentTypeDetailAdd1").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_CREDIT_CARD; ?>')
            {
                $(".remark").show();
                $(".card_no").show();
                $(".holder_name").show();
                $(".valid_upto").show();
                $(".transaction_id").show();
                $(".modal-title-payment-type").text("Credit Card");
                $("#myModalPaymentTypeDetailAdd1").modal("show");
            }

        });

        $(document).off("click",".closeMyModalPaymentTypeDetailAdd1");
        $(document).on("click",".closeMyModalPaymentTypeDetailAdd1",function(e){
            $("#myModalPaymentTypeDetailAdd1").modal('hide');
            e.stopPropagation();
            e.stopImmediatePropagation();
        });

        $(document).off('hidden.bs.modal','#myModalPaymentTypeDetailAdd1');
        $(document).on('hidden.bs.modal', '#myModalPaymentTypeDetailAdd1', function (e) {
            e.stopPropagation();
            e.stopImmediatePropagation();
        });


        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        function checkCookie() {
            var user = getCookie("print_size");
            if (user != "") {

                $("#prescription_size").val(user);

                if(user == 'A4')
                {

                    $("#all_receipt .modal_div").css('width','780px');
                    $("#all_receipt .date_th").css('width','13%');
                }
                else
                {

                    $("#all_receipt .modal_div").css('width','660px');
                    $("#all_receipt .date_th").css('width','15%');

                }

            } else {
                $("#prescription_size").val('A4');
                if (user != "" && user != null) {
                    setCookie("print_size", 'A4', 365);
                }


            }
        }
        $(document).off("change","#prescription_size");
        $(document).on("change","#prescription_size",function(){
            var value = $(this).val();
            setCookie("print_size", value, 365);
            checkCookie();
        });
        $(document).off("click","#print_btn");
        $(document).on("click","#print_btn",function(){


            var cssRules = {
                'propertyGroups' : {
                    'block' : ['margin', 'padding'],
                    'inline' : ['color','background-color'],
                    'headings' : ['font-size', 'font-family',]
                },
                'elementGroups' : {
                    'block' : ['DIV', 'P', 'H1','SPAN','TABLE'],
                    'inline' : ['TABLE','DIV'],
                    'headings' : ['H1', 'H2', 'H3', 'H4', 'H5', 'H6']
                }
            }
            $(".inline").html('');
            $(".inline").html($("#print_table").html());
            $(".inline .action_div, .inline .modal-header").remove();
            $(".inline").inlineStyler( cssRules);


            $('.inline').printThis({              // show the iframe for debugging
                importCSS: true,            // import page CSS
                importStyle: true,         // import style tags
                pageTitle: "",              // add title to print page
                removeInline: false,        // remove all inline styles from print elements
                printDelay: 10,            // variable print delay; depending on complexity a higher value may be necessary
                header: null,               // prefix to html
                footer: null,               // postfix to html
                base: false ,               // preserve the BASE tag, or accept a string for the URL
                formValues: true,           // preserve input/form values
                canvas: false,              // copy canvas elements (experimental)
                doctypeString: ".",       // enter a different doctype for older markup
                removeScripts: false,       // remove script tags from print content
                copyTagClasses: false       // copy classes from the html & body tag
            });
            var inter = setInterval(function () {
                $(".inline").html('');
                clearInterval(inter);
            },1000);

            // $(".inline").html('');
        });

        $(document).off('click','#settlement_all');
        $(document).on('click','#settlement_all',function(){

            var ipdID = $(this).attr("data-id");
            var remark = $("#remarkSettlement").val();
            var dialog = $.confirm({
                title: 'Settlement',
                content: "Do you want to settlement?",
                buttons:{
                    Ok: {
                        text: 'Yes',
                        btnClass: 'btn-warning yes_btn',
                        keys: ['enter'],
                        action: function(){
                            var $btn = $(".yes_btn");
                            $.ajax({
                                type:'POST',
                                url: baseurl+"app_admin/pay_all_expense",
                                data:{
                                    ipd_id:ipdID,
                                    td:'<?php echo $td; ?>',
                                    te:'<?php echo $te; ?>',
                                    tr:'<?php echo $tr; ?>',
                                    pa:'<?php echo $pa; ?>',
                                    remark:remark,
                                    sa:$('.settlement_amount').val(),
                                    pay_via_id:$('.hospital_payment_type_id').val(),
                                    pay_via:$('.hospital_payment_type_id option:selected').text()

                                },
                                beforeSend:function(){
                                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait...');
                                },
                                success:function(data){
                                    $btn.button('reset');
                                    data = JSON.parse(data);
                                    if(data.status==1){
                                        dialog.close();
                                        $("#all_receipt").modal('hide');

                                        window.open(baseurl+"app_admin/print_invoice_non_opd_settlement/"+data.data.receipt,'_blank');
                                        window.location.reload();
                                    }else{
                                        $.alert(data.message);
                                    }
                                },
                                error: function(data){
                                    $btn.button('reset');
                                    $.alert("Sorry something went wrong on server.");
                                }
                            });
                            return false;
                        }
                    },
                    Cancel: {
                        text: 'No',
                        btnClass: 'btn-info',
                        keys: ['esc'],
                        action: function(){
                            dialog.close();
                        }
                    }
                }

            });
        });

        $(document).off('input propertychange paste','.settlement_amount');
        $(document).on('input propertychange paste','.settlement_amount',function(){
            var amt = $(this).val();
            $('.rebate_value').html(parseFloat(deposit - amt).toFixed(2));

        });

        checkCookie();

    });





</script>
<style>
    #myModalPaymentTypeDetailAdd1 {
        z-index: 999999999;
    }

    .modal-body{
        padding: 10px 10px 10px 10px !important;
    }
    .footer_table th, .footer_table td{
        font-size: 13px;
    }

    .data_table thead, .data_table tfoot{

        -webkit-print-color-adjust: exact;
        background: rgb(208, 208, 208) !important;
        border: 1px solid #ccc;
        font-size: 12px;
    }
    .data_table thead th{
        padding: 4px 3px;
        line-height: 10px;
        border-right: 1px solid #abaaaa;
        border-left: 1px solid #abaaaa;

    }
    .data_table td{
        border-right: 1px solid #ccc;font-size: 11px;border-left: 1px solid #ccc;padding: 2px 3px;
    }
    .detail_table tr th{

        -webkit-print-color-adjust: exact;
        background: #6767670f !important;
        border-bottom: 1px solid #fff;
        font-size: 12px;
        font-weight: 600;
        width: 25%;
        padding: 0px 2px;

    }
    .detail_table{
        border: 1px solid #ccc;
    }
    .detail_table tr td{
        -webkit-print-color-adjust: exact;
        background: #d0cece1f !important;
        border-bottom: 1px solid #fff;
        font-size: 12px;
        font-weight: 500;
        width: 25%;
        padding: 0px 2px;

    }
    .header_tr td{
        padding: 0px 5px;

    }
    .header_tr{
        -webkit-print-color-adjust: exact;
        background: #ccc !important;
    }
    #all_receipt .action_div{
        text-align: right;
        margin-top: 8px;
        float: left;
        width: 100%;
    }
    #all_receipt .action_div button,
    #all_receipt .action_div select
    {
        height: 25px;
        float: right;
        min-width: 15%;
        margin: 0px 3px;
    }






</style>


