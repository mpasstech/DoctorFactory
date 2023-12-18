<div class="modal fade" id="myModalRefundForm" role="dialog" tabindex='-1' >
    <style>
        .payment_type{
            width: 5% !important;
            display: inline-flex;
        }
        legend{

            margin-bottom: 7px;
            font-size: 17px;
        }
        .warning_sms{
            color: red;
            text-align: center;
            width: 100%;
            font-size: 14px;
            height: 20px;
            display: none;
        }
        .online_label{
            width: 100%;
            display: block;
            color: red;
            font-weight: 600;
        }
        .online_label input{
            margin: 3px 9px;
            display: flex;
            float: left;
            width: 16px;
            height: 16px;
        }
    </style>
    <div class="modal-dialog modal-md" style="width: 900px;">

        <div class="modal-content">

            <form method="post" id="refundFormSubmit">
                <input type="hidden" name="data_ai" value="<?php echo base64_encode($orderDetails['MedicalProductOrder']['id']); ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Refund</h4>
                </div>
                <div class="modal-body">


                    <table class="table">
                        <thead>
                        <tr>
                            <th width="2%">#</th>
                            <th width="20%">Service Name</th>
                            <th width="8%">Paid Amount</th>
                            <th  width="10%">Refunded Amount</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $counter=1; foreach($orderDetails['MedicalProductOrderDetail'] as $key => $list){  if($list['show_into_receipt'] == "YES"){ ?>
                            <tr>
                                <td class="td_valign gray"><?php echo $counter++; ?></td>
                                <td class="td_valign white" ><?php echo !empty($list['MedicalProduct']['name'])?$list['MedicalProduct']['name']:$list['service']; ?></td>
                                <td class="td_valign gray"><?php echo $list['total_amount']; ?></td>
                                <td class="td_valign gray clear_td">
                                    <input class="refund_input"  type="text" name="<?php echo "box_".$list['id']; ?>" value="<?php echo $list['total_amount']; ?>" data-id="<?php echo base64_encode($list['id']); ?>" >
                                    <a href="javascript:void(0);" class="clear_amount" >Clear</a>
                                </td>
                            </tr>
                        <?php }} ?>
                        </tbody>
                    </table>

                    <div class="row">

                        <div class="input number col-md-12">
                            <?php if(!empty($cash_free_data)){ ?>
                                <label class="online_label"><input checked="checked" data-msg="<?php echo 'Refunded amount would be '.$cash_free_data['amount'].' Rs. Please Confirm!'; ?>" type="checkbox" value="YES" name="refund_online"><?php echo "Do you want to refund the online transaction amount ?"; ?></label><br>
                            <?php } ?>
                            <div style="display: block;float: left;width: 100%;" class="payment_type_box">
                                <label>Refund Via</label><br>
                                <?php
                                $attributes = array(
                                    'legend' => false,
                                    'value' => $orderDetails['MedicalProductOrder']['hospital_payment_type_id'],
                                    'class'=>'payment_type'
                                );
                                $login = $this->Session->read('Auth.User');
                                $payment = $this->AppAdmin->getHospitalPaymentTypeArray($login['User']['thinapp_id']);
                                $payment[0]='Cash';
                                ksort($payment,SORT_NUMERIC);
                                echo $this->Form->radio('hospital_payment_type_id',$payment , $attributes);
                                ?>
                                <label class="warning_sms">Selected refunding payment mode does not have sufficient amount</label>
                            </div>
                         </div>

                    </div>


                    <div class="row">
                        <div class="input number col-md-12">
                            <label for="opdCharge">Refund Reason</label>
                            <input type="hidden" id="refundIdHolder" name="appointment_id" >
                            <textarea name="message" id="refundMessage" class="form-control" placeholder="Refund Reason"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-warning btn" data-dismiss="modal">Cancel</button>
                    <button type="reset" class="btn-success btn" >Reset</button>
                    <button type="button" id="save_btn" class="btn-info btn save_btn" >Refund</button>
                </div>

            </form>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(document).off('click', '.clear_amount');
            $(document).on('click', '.clear_amount', function (e) {
                 $(this).closest('.clear_td').find('input').val(0);
            });


            $(document).off('input', '.refund_input');
            $(document).on('input', '.refund_input', function (e) {
                $(".payment_type:checked").trigger('change');
            });
             $("input[name='refund_online']").trigger('change');
            
            $(document).off('change', '.payment_type');
            $(document).on('change', '.payment_type', function (e) {

                var pay_id = $(this).val();
                var sum = 0;
                $('.refund_input').each(function() {
                    sum += Number($(this).val());
                });

                if( ( $(".payment_type_"+pay_id).attr('data-amount') < sum && $(".payment_type_"+pay_id).length == 1) || $(".payment_type_"+pay_id).length == 0){
                    $('.warning_sms').show();
                }else{
                    $('.warning_sms').hide();
                }


            });


            $(document).off('change', "[name='refund_online']");
            $(document).on('change', "[name='refund_online']", function (e) {
                if($(this).is(":checked")){
                    $(".payment_type_box").hide();
                }else{
                    $(".payment_type_box").show();
                }
            });

            $(document).off('click', '#save_btn');
            $(document).on('click', '#save_btn', function (e) {
                var allow_refund = true;
                if($("[name='refund_online']").is(":checked")){
                    var message = $("[name='refund_online']").attr("data-msg");
                    allow_refund =   confirm(message);
                }
                if(allow_refund===true){
                    var $btn = $(this);
                    $.ajax({
                        type: 'POST',
                        url: baseurl + "app_admin/refund_order_amount",
                        data: {data: $("#refundFormSubmit").serialize()},
                        beforeSend: function () {
                            $btn.button('loading').html('Wait..');
                        },
                        success: function (data) {
                            $btn.button('reset');
                            data = JSON.parse(data);
                            if (data.status == 1) {

                                $("#appointment_date").trigger("changeDate");

                                $("#myModalRefundForm, #amount_deposit").modal('hide');
                            }else{
                                $.alert(data.message);
                            }

                        },
                        error: function (data) {
                            $btn.button('reset');
                            $.alert('Sorry, Refund could not done')
                        }
                    });
                }
            });

            setTimeout(function () {
                $(".payment_type:checked").trigger('change');
            },10)
        });
    </script>
</div>